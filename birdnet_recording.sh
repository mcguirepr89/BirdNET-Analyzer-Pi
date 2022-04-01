#!/usr/bin/env bash
set -x
my_dir=/home/pi/BirdNET-Analyzer-Pi
configpy=${my_dir}/config.py
source <(grep -ve '^$' -e '^#'  <(sed 's/ //g' $configpy | sed '/Getandset/q'))

[ -z $RECORDING_LENGTH ] && RECORDING_LENGTH=15
[ -z $CHANNELS] && CHANNELS=2
[ -z $RECS_DIR] && RECS_DIR=/home/pi/BirdNET-Analyzer-Pi/Raw

if ! pulseaudio --check;then pulseaudio --start;fi

if pgrep arecord &> /dev/null ;then
  echo "Recording"
else
  arecord -f S16_LE -c${CHANNELS} -r48000 -t wav --max-file-time \
    ${RECORDING_LENGTH} --use-strftime \
    ${RECS_DIR}/%F-birdnet-%H:%M:%S.wav
fi
