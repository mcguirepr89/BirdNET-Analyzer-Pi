#!/usr/bin/env bash
set -x
#variables
my_dir=$(realpath $(dirname $0))
configpy=${my_dir}/config.py
dbpath=$my_dir/birdnet_pi_app/database/database.sqlite

#from database
RECS_DIR=$my_dir/$(sqlite3 $dbpath 'select RECS_DIR from configs')
REC_CARD=$(sqlite3 $dbpath 'select REC_CARD from configs')
DATABASE_PATH=$dbpath
CHANNELS=$(sqlite3 $dbpath 'select CHANNELS from configs')
RECORDING_LENGTH=$(sqlite3 $dbpath 'select RECORDING_LENGTH from configs')

[ -z $RECORDING_LENGTH ] && RECORDING_LENGTH=15
[ -z $CHANNELS ] && CHANNELS=2

if ! pulseaudio --check;then pulseaudio --start;fi

if pgrep arecord &> /dev/null ;then
  echo "Recording"
elif [[ $(date +%H) -gt 18 || $(date +%H) -le 6 ]];then
  arecord -f S16_LE -c${CHANNELS} -r384000 -D ${REC_CARD} -t wav --max-file-time \
    $RECORDING_LENGTH --use-strftime \
    $RECS_DIR/%F_%H:%M:%S.wav
else
  arecord -f S16_LE -c${CHANNELS} -r48000 -D ${REC_CARD} -t wav --max-file-time \
    $RECORDING_LENGTH --use-strftime \
    $RECS_DIR/%F_%H:%M:%S.wav
fi
