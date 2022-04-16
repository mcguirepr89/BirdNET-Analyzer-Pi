#!/usr/bin/env bash
# A quick uninstaller
set -x

my_dir=$(realpath $(dirname $0))

services=(birdnet_analysis.service
  birdnet_recording.service
  weather.service
  caddy.service
  avahi-alias@$(hostname).local.service
  avahi-alias@.service
  streamlit.service)

echo "Removing all services"
sudo systemctl disable --now ${services[@]}

for service in ${services[@]};do
  sudo rm -fv /usr/lib/systemd/system/$service
done

echo "Removing database"
rm $my_dir/birds.db

echo "Removing all data"
rm -drf $my_dir/Raw
rm -drf $my_dir/Analyzed
rm -drf $my_dir/Segments
[ -d $my_dir/Storage ] && rm -drfv $my_dir/Storage

for file in $my_dir/*.py;do
  if [ $file != "$my_dir/config.py" ] && grep "python3" <(head -n1 $file);then
    sed -si '1d' $file
  fi
done
