#!/usr/bin/env bash
# Run analyze.py with the correct arguments
set -x
configpy=/home/pi/BirdNET-Analyzer-Pi/config.py
source <(grep -ve '^$' -e '^#' <(sed 's/ //g' $configpy | sed '/Getandset/q'))
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

for file in $(find ${RECS_DIR} -type f);do
  /home/pi/BirdNET-Analyzer-Pi/analyze.py --i ${file} --o ${file}.csv --week ${week} --locale ${LANGUAGE} --lat ${LATITUDE} --lon ${LONGITUDE} --sensitivity ${SIGMOID_SENSITIVITY} --min_conf ${MIN_CONFIDENCE} --overlap ${SIG_OVERLAP} --sf-thresh ${LOCATION_FILTER_THRESHOLD}
done
