#!/usr/bin/env bash
set -x

[ -z $RECORDING_LENGTH ] && RECORDING_LENGTH=15

if pgrep arecord &> /dev/null ;then
  echo "Recording"
else
  arecord -f S16_LE -c${CHANNELS} -r48000 -t wav --max-file-time \
    ${RECORDING_LENGTH} --use-strftime \
    ${RECS_DIR}/%Y/%m/%d/%F-birdnet-%H:%M:%S.wav
fi
