#!/usr/bin/env bash
# A quick uninstaller

my_dir=$(realpath $(dirname $0))

echo "Removing all services"
sudo systemctl disable --now birdnet_analysis birdnet_recording weather caddy

echo "Removing database"
rm $my_dir/birds.db

echo "Removing all data"
rm -drf $my_dir/Raw
rm -drf $my_dir/Analyzed
rm -drf $my_dir/Segments
[ -d $my_dir/Storage ] && rm -drfv $my_dir/Storage
