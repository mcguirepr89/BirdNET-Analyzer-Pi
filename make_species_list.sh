#!/usr/bin/env bash
# Generate a species list

#variables
my_dir=$(realpath $(dirname $0))
pyvenv=$my_dir/birdnet/bin/python3
speciespy=$my_dir/species.py
dbpath=$my_dir/birdnet_pi_app/database/database.sqlite

#from database
LATITUDE=$(sqlite3 $dbpath 'select LATITUDE from configs')
LONGITUDE=$(sqlite3 $dbpath 'select LONGITUDE from configs')
THRESHOLD=$(sqlite3 $dbpath 'select LOCATION_FILTER_THRESHOLD from configs')

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
get_week

$pyvenv $speciespy --lat $LATITUDE --lon $LONGITUDE --week $week --threshold $THRESHOLD --o $my_dir

echo Customizing
echo "Removing the Eastern Screech Owl"
sed -i '/Screech/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
echo "Removing the Herons"
sed -i '/Heron/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
echo "Removing the Wild Turkey"
sed -i '/Turkey/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
echo "Removing the Canadian Goose"
sed -i '/Goose/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
echo "Removing the Cooper's Hawk"
sed -i '/Cooper/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
echo "Removing the Yellowlegs"
sed -i '/Yellowlegs/d' $my_dir/species_list.txt
echo "Species list now contains $(wc -l $my_dir/species_list.txt) species"
