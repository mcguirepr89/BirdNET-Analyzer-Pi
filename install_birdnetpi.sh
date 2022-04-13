#!/usr/bin/env bash
# This installs BirdNET-Pi for you!
set -x

# Environment
USER=$USER
HOME=$HOME
my_dir=$HOME/BirdNET-Analyzer-Pi
configpy=$my_dir/config.py

export USER=$USER
export HOME=$HOME
export my_dir=$my_dir
export configpy=$configpy

caddy_url="https://dl.cloudsmith.io/public/caddy/stable/setup.deb.sh"
dependencies=(git python3-dev python3-venv python3-pip ffmpeg caddy sqlite3 alsa-utils pulseaudio)
install_birdnet() {
  git clone git@github.com:mcguirepr89/BirdNET-Analyzer-Pi.git $my_dir
  cd $my_dir
  python3 -m venv birdnet
  source ./birdnet/bin/activate
  pip3 install --upgrade -r $my_dir/requirements.txt
  deactivate
  sed -si "1 i\\#\!$my_dir/birdnet/bin/python3" $my_dir/*.py
  sed -i '1d' $my_dir/config.py
}

auto-detect_settings() {
  LATITUDE="LATITUDE = $(curl -s4 ifconfig.co/json | awk '/lat/ {print $2}' | tr -d ',')"
  sed -i "s/LATITUDE = -1/$LATITUDE/" $configpy
  echo $LATITUDE
  LONGITUDE="LONGITUDE = $(curl -s4 ifconfig.co/json | awk '/lon/ {print $2}' | tr -d ',')"
  sed -i "s/LONGITUDE = -1/$LONGITUDE/" $configpy
  echo $LONGITUDE
  LANGUAGE="LANGUAGE = \'$(echo $LANG|cut -d'_' -f1)\'"
  sed -i "s/LANGUAGE =.*/$LANGUAGE/" $configpy
  echo $LANGUAGE

  source <(grep -ve '^$' -e '^#' <(sed 's/ //g' $configpy | sed '/Getandset/q'))
  SEGMENTS_DIR=~/BirdNET-Analyzer-Pi/$SEGMENTS_DIR
}

install_birdnet_analysis() {
  cat << EOF > $my_dir/templates/birdnet_analysis.service
[Unit]
Description=BirdNET Analysis
[Service]
Restart=always
Type=simple
RestartSec=2
User=$USER
ExecStart=$my_dir/birdnet_analysis.sh
StandardOutput=append:$my_dir/birdnet_analysis.log
StandardError=append:$my_dir/birdnet_analysis.log
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/birdnet_analysis.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_analysis.service
}

install_recording_service() {
  cat << EOF > $my_dir/templates/birdnet_recording.service
[Unit]
Description=BirdNET Recording
[Service]
Environment=XDG_RUNTIME_DIR=/run/user/1000
Restart=always
Type=simple
RestartSec=3
User=$USER
ExecStart=$my_dir/birdnet_recording.sh
StandardOutput=append:$my_dir/birdnet_recording.log
StandardError=append:$my_dir/birdnet_recording.log
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/birdnet_recording.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_recording.service
}

install_weather_service() {
  cat << EOF > $my_dir/templates/weather.service
[Unit]
Description=Weather Service
[Service]
Environment=XDG_RUNTIME_DIR=/run/user/1000
Restart=always
Type=simple
RestartSec=3600
User=$USER
ExecStart=$my_dir/weather_DB.py
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/weather.service /usr/lib/systemd/system
  sudo systemctl enable weather.service
}

install_avahi_alias() {
  cat << 'EOF' > $my_dir/templates/avahi-alias@.service
[Unit]
Description=Publish %I as alias for %H.local via mdns
After=network.target network-online.target
Requires=network-online.target
[Service]
Restart=always
RestartSec=3
Type=simple
ExecStart=/bin/bash -c "/usr/bin/avahi-publish -a -R %I $(hostname -I |cut -d' ' -f1)"
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/avahi-alias@.service /usr/lib/systemd/system
  sudo systemctl enable --now avahi-alias@"$(hostname)".local.service
}

install_streamlit() {
  cat << EOF > $my_dir/templates/streamlit.service
[Unit]
Description=Streamlit Statistics
[Service]
Restart=on-failure
RestartSec=5
Type=simple
User=$USER
ExecStart=$my_dir/birdnet/bin/streamlit run $my_dir/plotly_streamlit.py --server.address localhost --server.baseUrlPath "/stats"
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/streamlit.service /usr/lib/systemd/system
  sudo systemctl enable streamlit.service
}

install_Caddyfile() {
  cat << EOF > $my_dir/templates/Caddyfile
http://$(hostname).local {
  root * $(realpath $(dirname $SEGMENTS_DIR))
  file_server browse
  reverse_proxy /stats* localhost:8501
}
EOF
  sudo ln -sf $my_dir/templates/Caddyfile /etc/caddy/Caddyfile
  sudo systemctl reload caddy
}

install_bash_aliases() {
  ln -sf $my_dir/bash_aliases $HOME/.bash_aliases
  source $HOME/.bash_aliases
}

set_login() {
  if ! [ -d /etc/lightdm ];then
    sudo systemctl set-default multi-user.target
    sudo ln -fs /lib/systemd/system/getty@.service /etc/systemd/system/getty.target.wants/getty@tty1.service
    cat << EOF | sudo tee /etc/systemd/system/getty@tty1.service.d/autologin.conf 
[Service]
ExecStart=
ExecStart=-/sbin/agetty --autologin $USER --noclear %I \$TERM
EOF
  fi
}

echo "Adding dependency repos to apt-sources"
curl -1sLf "$caddy_url" | sudo -E bash

echo "Updating system"
sudo apt update && sudo apt -y upgrade

echo "Installing dependencies"
sudo apt -y install --no-install-recommends ${dependencies[@]}

echo "Auto-detecting some settings"
auto-detect_settings

echo "Installing BirdNET-Analyzer"
install_birdnet

echo "Install BirdNET Analysis Service"
install_birdnet_analysis

echo "Installing Recording Service"
install_recording_service

echo "Installing Weather Service"
install_weather_service

echo "Installing Streamlit"
install_streamlit

echo "Installing the avahi-alias@.service"
install_avahi_alias

echo "Installing the Caddyfile"
install_Caddyfile

echo "Installing bash_aliases"
install_bash_aliases

echo "Configuring System Settings"
set_login
