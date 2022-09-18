#!/usr/bin/env bash
# Bat Stream
set -x

#variables
my_dir=$(realpath $(dirname $0))
configpy=${my_dir}/config.py
dbpath=$my_dir/birdnet_pi_app/database/database.sqlite

#from database
REC_CARD=$(sqlite3 $dbpath 'select REC_CARD from configs')
CHANNELS=$(sqlite3 $dbpath 'select CHANNELS from configs')
REC_CARD=default
[ ! -z $1 ] || exit 1
while getopts "acp:" class;do
  case $class in
    a)  ffmpeg -loglevel error -ac ${CHANNELS} -f alsa -i ${REC_CARD} -acodec libmp3lame \
        -b:a 320k -ac ${CHANNELS} -content_type 'audio/mpeg' \
        -f mp3 icecast://source:getinin4949@localhost:8081/stream -re
	;;
    c)  set AUDIODEV=hw:CARD=A384kHz,DEV=0
        rec -r 384000 -p | sox - -p sinc 18k pitch -q -2500 | ffmpeg -loglevel error -i - -acodec libmp3lame -b:a 320k -content_type "audio/mpeg" -f mp3 icecast://source:getinin4949@localhost:8081/stream -re
	;;
    p)  set AUDIODEV=hw:CARD=A384kHz,DEV=0
        pitch_shift=$OPTARG
        rec -r 384000 -p | sox - -p sinc 18k pitch -q $pitch_shift | ffmpeg -loglevel error -i - -acodec libmp3lame -b:a 320k -content_type "audio/mpeg" -f mp3 icecast://source:getinin4949@localhost:8081/stream -re
	;;
    \?) echo "?";exit 1;;
   esac
done


