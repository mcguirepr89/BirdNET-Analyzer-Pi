#!/usr/bin/env bash
# Reformat the config based on options provided
#set -x

usage() {
  cat <<EOF
Usage: $(basename $0) [-iph] FILE
  -i Format the file as a $file.ini
  -h Print this help menu.
EOF
exit 1
}

while getopts 'iph' format;do
  case $format in
    i) new_format=ini;shift;;
    h) usage;;
   \?) usage;;
  esac
done

if [ -z $new_format ];then echo "No format specified. Exiting.";exit 1;fi

if [ $new_format == "ini" ];then
  sed 's/^# /[/;s/ #$/]/;/Get and set config/q' $1 | grep -ve '^#' -ve '= c\[' -ve'^$' -ve 'Get and set config'
fi

