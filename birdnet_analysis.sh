#!/usr/bin/env bash
# Run analyze.py with the correct arguments
set -x
set -e
my_dir=/home/pi/BirdNET-Analyzer-Pi
configpy=${my_dir}/config.py
analyzepy=${my_dir}/analyze.py
segmentspy=${my_dir}/segments.py
source <(grep -ve '^$' -e '^#' <(sed 's/ //g' $configpy | sed '/Getandset/q'))
STORED=/dev/null
set +x

#Calculate Week
week_of_year="$(echo "($(date +%m)-1) * 4" | bc -l)"
day_of_month="$(date +%d)"
if [ ${day_of_month} -le 7 ];then
  week="$(echo "${week_of_year} + 1" |bc -l)"
elif [ ${day_of_month} -le 14 ];then
  week="$(echo "${week_of_year} + 2" |bc -l)"
elif [ ${day_of_month} -le 21 ];then
  week="$(echo "${week_of_year} + 3" |bc -l)"
elif [ ${day_of_month} -ge 22 ];then
  week="$(echo "${week_of_year} + 4" |bc -l)"
fi

#Create necessary directories
[ -d ${ANALYZED_DIR} ] || mkdir ${ANALYZED_DIR}

# Run analyze.py and segments.py on files that have reached the 
# ${RECORDING_LENGTH}
while true;do
for file in $(find ${RECS_DIR} -type f -name '*.wav'|sort);do
  file_results=${file//.wav/.BirdNET.results.csv}
  set -x
  file_length=$(soxi -D $file|cut -d'.' -f1)
  if [ $file_length -ge $RECORDING_LENGTH ];then
    sleep 1
    echo "Starting Analysis"
    $analyzepy \
      --i ${file} \
      --o ${file_results} \
      --week ${week} \
      --locale ${LANGUAGE} \
      --lat ${LATITUDE} \
      --lon ${LONGITUDE} \
      --sensitivity ${SIGMOID_SENSITIVITY} \
      --rtype ${RESULT_TYPE} \
      --min_conf ${MIN_CONFIDENCE} \
      --overlap ${SIG_OVERLAP} \
      --sf_thresh ${LOCATION_FILTER_THRESHOLD} \
      --threads ${CPU_THREADS}
    sleep 1
    echo "Moving results"
    mv -v ${file} ${file_results} ${ANALYZED_DIR} &&
    echo "Starting segments" &&
    $segmentspy \
      --audio ${ANALYZED_DIR}/ \
      --results ${ANALYZED_DIR}/ \
      --o ${SEGMENTS_DIR} \
      --min_conf ${MIN_CONFIDENCE} \
      --seg_length ${SEGMENT_LENGTH} \
      --threads ${CPU_THREADS}
    rm -v ${ANALYZED_DIR}/*
    set +x
  else
    sleep 1
  fi
done
done
