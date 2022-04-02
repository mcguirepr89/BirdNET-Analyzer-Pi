#!/usr/bin/env bash
# Run analyze.py with the correct arguments
#set -x #Uncomment to debug
set -e #Exit if anything fails

#variables
my_dir=/home/pi/BirdNET-Analyzer-Pi
configpy=${my_dir}/config.py
analyzepy=${my_dir}/analyze.py
segmentspy=${my_dir}/segments.py
source <(grep -ve '^$' -e '^#' <(sed 's/ //g' $configpy | sed '/Getandset/q'))

get_week() {
  week_of_year="$(echo "($(date +%m)-1) * 4" | bc -l)"
  day_of_month="$(date +%d)"
  if [ $day_of_month -le 7 ];then
    week="$(echo "$week_of_year + 1" |bc -l)"
  elif [ $day_of_month -le 14 ];then
    week="$(echo "$week_of_year + 2" |bc -l)"
  elif [ $day_of_month -le 21 ];then
    week="$(echo "$week_of_year + 3" |bc -l)"
  elif [ $day_of_month -ge 22 ];then
    week="$(echo "$week_of_year + 4" |bc -l)"
  fi
}

analyze() {
  $analyzepy \
     --i $file \
     --o $file_results \
     --week $week \
     --locale $LANGUAGE \
     --lat $LATITUDE \
     --lon $LONGITUDE \
     --sensitivity $SIGMOID_SENSITIVITY \
     --rtype $RESULT_TYPE \
     --min_conf $MIN_CONFIDENCE \
     --overlap $SIG_OVERLAP \
     --sf_thresh $LOCATION_FILTER_THRESHOLD \
     --threads $CPU_THREADS
}

segments() {
  $segmentspy \
     --audio $ANALYZED_DIR/ \
     --results $ANALYZED_DIR/ \
     --o $SEGMENTS_DIR \
     --min_conf $MIN_CONFIDENCE \
     --seg_length $SEGMENT_LENGTH \
     --threads $CPU_THREADS
}

cleanup() {
  if grep "G" <(du -h $STORAGE_DIR);then
    space=$(du -h $STORAGE_DIR|cut -d'G' -f1)
    if [ $space -gt $STORAGE_LIMIT ];then
      until [ $space -le $STORAGE_LIMIT ];do
        tail -n20 <(ls -1t $STORAGE_DIR) | xargs rm -v
      done
    fi
  fi
} 

get_week

while true;do
  for file in $(find $RECS_DIR -type f -name '*.wav'|sort);do
    file_results=${file//.wav/.BirdNET.results.csv}
    file_length=$(soxi -D $file|cut -d'.' -f1)
    if [ $file_length -ge $RECORDING_LENGTH ];then
      sleep 1
      echo "Starting Analysis"
      analyze
      echo "Moving results"
      mv -v $file $file_results $ANALYZED_DIR
      echo "Starting segments"
      segments
      if [[ $STORAGE == 'keep' ]];then
        echo "Storing raw data"
        [ -d $STORAGE_DIR ] || mkdir -p $STORAGE_DIR
        mv -v $ANALYZED_DIR/* $STORAGE_DIR
        cleanup
      elif [[ $STORAGE == 'purge' ]];then
        echo "Purging raw data"
        rm -v $ANALYZED_DIR/*
      fi
    else
      sleep 1
    fi
  done
done
