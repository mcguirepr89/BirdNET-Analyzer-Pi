#!/usr/bin/env bash
# Run analyze.py with the correct arguments
#set -x #Uncomment to debug

#variables
my_dir=$(realpath $(dirname $0))
configpy=$my_dir/config.py
pyvenv=$my_dir/birdnet/bin/python3
analyzepy=$my_dir/analyze.py
segmentspy=$my_dir/segments.py
source $my_dir/birdnet_pi_app/.env

json_config=$(curl -s $APP_URL/api/config)
RANDOM_SEED=$(echo $json_config|jq .[].RANDOM_SEED |  tr -d '"')
[ -z $RANDOM_SEED ] && exit 1
MODEL_PATH=$(echo $json_config|jq .[].MODEL_PATH |  tr -d '"')
MDATA_MODEL_PATH=$(echo $json_config|jq .[].MDATA_MODEL_PATH |  tr -d '"')
LABELS_FILE=$(echo $json_config|jq .[].LABELS_FILE |  tr -d '"')
TRANSLATED_LABELS_PATH=$(echo $json_config|jq .[].TRANSLATED_LABELS_PATH |  tr -d '"')
REC_CARD=$(echo $json_config|jq .[].REC_CARD |  tr -d '"')
CHANNELS=$(echo $json_config|jq .[].CHANNELS |  tr -d '"')
SAMPLE_RATE=$(echo $json_config|jq .[].SAMPLE_RATE |  tr -d '"')
SIG_LENGTH=$(echo $json_config|jq .[].SIG_LENGTH |  tr -d '"')
SIG_OVERLAP=$(echo $json_config|jq .[].SIG_OVERLAP |  tr -d '"')
SIG_MINLEN=$(echo $json_config|jq .[].SIG_MINLEN |  tr -d '"')
RECORDING_LENGTH=$(echo $json_config|jq .[].RECORDING_LENGTH |  tr -d '"')
SEGMENT_LENGTH=$(echo $json_config|jq .[].SEGMENT_LENGTH |  tr -d '"')
AUDIO_FMT=$(echo $json_config|jq .[].AUDIO_FMT |  tr -d '"')
RECS_DIR=$my_dir/$(echo $json_config|jq .[].RECS_DIR |  tr -d '"')
ANALYZED_DIR=$my_dir/$(echo $json_config|jq .[].ANALYZED_DIR |  tr -d '"')
SEGMENTS_DIR=$my_dir/$(echo $json_config|jq .[].SEGMENTS_DIR |  tr -d '"')
STORAGE_DIR=$my_dir/$(echo $json_config|jq .[].STORAGE_DIR |  tr -d '"')
DATABASE_PATH=$my_dir/$(echo $json_config|jq .[].DATABASE_PATH |  tr -d '"')
LANGUAGE=$(echo $json_config|jq .[].LANGUAGE |  tr -d '"')
STORAGE=$(echo $json_config|jq .[].STORAGE |  tr -d '"')
STORAGE_LIMIT=$(echo $json_config|jq .[].STORAGE_LIMIT |  tr -d '"')
LATITUDE=$(echo $json_config|jq .[].LATITUDE |  tr -d '"')
LONGITUDE=$(echo $json_config|jq .[].LONGITUDE |  tr -d '"')
LOCATION_FILTER_THRESHOLD=$(echo $json_config|jq .[].LOCATION_FILTER_THRESHOLD |  tr -d '"')
CODES_FILE=$(echo $json_config|jq .[].CODES_FILE |  tr -d '"')
SPECIES_LIST_FILE=$(echo $json_config|jq .[].SPECIES_LIST_FILE |  tr -d '"')
INPUT_PATH=$(echo $json_config|jq .[].INPUT_PATH |  tr -d '"')
OUTPUT_PATH=$(echo $json_config|jq .[].OUTPUT_PATH |  tr -d '"')
CPU_THREADS=$(echo $json_config|jq .[].CPU_THREADS |  tr -d '"')
TFLITE_THREADS=$(echo $json_config|jq .[].TFLITE_THREADS |  tr -d '"')
APPLY_SIGMOID=$(echo $json_config|jq .[].APPLY_SIGMOID |  tr -d '"')
SIGMOID_SENSITIVITY=$(echo $json_config|jq .[].SIGMOID_SENSITIVITY |  tr -d '"')
MIN_CONFIDENCE=$(echo $json_config|jq .[].MIN_CONFIDENCE |  tr -d '"')
BATCH_SIZE=$(echo $json_config|jq .[].BATCH_SIZE |  tr -d '"')
RESULT_TYPE=$(echo $json_config|jq .[].RESULT_TYPE |  tr -d '"')
CODES=$(echo $json_config|jq .[].CODES |  tr -d '"')
LABELS=$(echo $json_config|jq .[].LABELS |  tr -d '"')
TRANSLATED_LABELS=$(echo $json_config|jq .[].TRANSLATED_LABELS |  tr -d '"')
SPECIES_LIST=$(echo $json_config|jq .[].SPECIES_LIST |  tr -d '"')
ERROR_LOG_FILE=$(echo $json_config|jq .[].ERROR_LOG_FILE |  tr -d '"')

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
  if [[ ! -z $LONGITUDE && ! -z $LATITUDE && $SPECIES_LIST_FILE == "null" ]];then
    $pyvenv $analyzepy \
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
      echo "
        --i $file
        --o $file_results
        --week $week
        --locale $LANGUAGE
        --lat $LATITUDE
        --lon $LONGITUDE
        --sensitivity $SIGMOID_SENSITIVITY
        --rtype $RESULT_TYPE
        --min_conf $MIN_CONFIDENCE
        --overlap $SIG_OVERLAP
        --sf_thresh $LOCATION_FILTER_THRESHOLD
        --threads $CPU_THREADS
	"
   elif [[ ! -z $SPECIES_LIST_FILE && $SPECIES_LIST_FILE != "null" ]];then
    $pyvenv $analyzepy \
       --i $file \
       --o $file_results \
       --week $week \
       --locale $LANGUAGE \
       --slist $my_dir/$SPECIES_LIST_FILE \
       --sensitivity $SIGMOID_SENSITIVITY \
       --rtype $RESULT_TYPE \
       --min_conf $MIN_CONFIDENCE \
       --overlap $SIG_OVERLAP \
       --sf_thresh $LOCATION_FILTER_THRESHOLD \
       --threads $CPU_THREADS
   echo "
        --i $file
        --o $file_results
        --week $week
        --locale $LANGUAGE
        --slist $SPECIES_LIST_FILE
        --sensitivity $SIGMOID_SENSITIVITY
        --rtype $RESULT_TYPE
        --min_conf $MIN_CONFIDENCE
        --overlap $SIG_OVERLAP
        --sf_thresh $LOCATION_FILTER_THRESHOLD
        --threads $CPU_THREADS
	"
  fi
}

segments() {
  $pyvenv $segmentspy \
     --audio $ANALYZED_DIR/ \
     --results $ANALYZED_DIR/ \
     --o $SEGMENTS_DIR \
     --min_conf $MIN_CONFIDENCE \
     --seg_length $SEGMENT_LENGTH \
     --threads $CPU_THREADS
}

cleanup() {
  space=$(du -b $STORAGE_DIR|awk '{print $1}')
  STORAGE_LIMIT=$(numfmt --from=iec $STORAGE_LIMIT)
  if [ $space -gt $STORAGE_LIMIT ];then
    until [ $space -le $STORAGE_LIMIT ];do
      find -L $STORAGE_DIR -type f | sort -r | tail -n10 | xargs rm -fv
      space=$(du -b $STORAGE_DIR|awk '{print $1}')
    done
  fi
} 


get_week

while true;do
  for file in $(find -L $RECS_DIR -type f -name '*.wav'|sort);do
    file_results=${file//.wav/.BirdNET.results.csv}
    if ! lsof $file &>/dev/null;then
      sleep 1.5
      set -e #Exit if anything fails
      analyze
      set +e #Turn off the exit on error
      echo "Moving results"
      [ -d $ANALYZED_DIR ] || mkdir -p $ANALYZED_DIR
      mv $file $file_results $ANALYZED_DIR
      echo "Starting segments"
      segments
      if [[ $STORAGE == 'keep' ]];then
        echo "Storing raw data"
        [ -d $STORAGE_DIR ] || mkdir -p $STORAGE_DIR
        mv $ANALYZED_DIR/* $STORAGE_DIR
        cleanup
      elif [[ $STORAGE == 'purge' ]];then
        echo "Purging raw data"
        rm $ANALYZED_DIR/*
      fi
    else
      sleep 1
    fi
  done
  get_week
done
