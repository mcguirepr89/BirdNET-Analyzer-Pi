#!/usr/bin/env bash
# Disk space management
set -x
#variables
my_dir=$(realpath $(dirname $0))
dbpath=$my_dir/birdnet_pi_app/database/database.sqlite
storage_limit=70
current_use=$(awk '/root/{print $5}' <(df -h))

#from database
SEGMENTS_DIR=$my_dir/$(sqlite3 $dbpath 'select SEGMENTS_DIR from configs')

cleanup() {
  if [ ${current_use//%} -gt $storage_limit ];then
    echo "current_use ($current_use) is greater than storage_limit ($storage_limit%)"
    until [ ${current_use//%} -le $storage_limit ];do
      find -L $SEGMENTS_DIR -type f \! -name '*currently*' -print0| sort -rn -t '.' -k2 | tail -n10 #| xargs -0 rm -fv
      current_use=$(awk '/root/{print $5}' <(df -h))
    done
  fi
}

cleanup
