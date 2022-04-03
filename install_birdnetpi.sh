#!/usr/bin/env bash
# This installs BirdNET-Pi for you!
set -x

# Environment
USER=pi
HOME=/home/pi
my_dir=$HOME/BirdNET-Analyzer-Pi
configpy=$my_dir/config.py

#caddy_url="https://dl.cloudsmith.io/public/caddy/stable/setup.deb.sh"
dependencies=(git python3-dev python3-venv python3-pip ffmpeg sqlite3 alsa-utils pulseaudio bc)

install_birdnet() {
  git clone git@github.com:mcguirepr89/BirdNET-Analyzer-Pi.git /home/pi/BirdNET-Analyzer-Pi
  cd ~/BirdNET-Analyzer-Pi
  python3 -m venv birdnet
  source ./birdnet/bin/activate
  pip3 install --upgrade pip
  pip3 install librosa tflite-runtime
  deactivate
}

auto-detect_settings() {
  LATITUDE="LATITUDE = $(curl -s4 ifconfig.co/json | awk '/lat/ {print $2}' | tr -d ',')"
  sed -i "s/LATITUDE = -1/$LATITUDE/" ${configpy}
  echo $LATITUDE
  LONGITUDE="LONGITUDE = $(curl -s4 ifconfig.co/json | awk '/lon/ {print $2}' | tr -d ',')"
  sed -i "s/LONGITUDE = -1/$LONGITUDE/" ${configpy}
  echo $LONGITUDE
  LANGUAGE="LANGUAGE = \'$(echo $LANG|cut -d'_' -f1)\'"
  sed -i "s/LANGUAGE =.*/$LANGUAGE/" ${configpy}
  echo $LANGUAGE

  source <(grep -ve '^$' -e '^#' <(sed 's/ //g' $configpy | sed '/Getandset/q'))
}

install_birdnet_analysis() {
  cat << EOF > /home/pi/BirdNET-Analyzer-Pi/templates/birdnet_analysis.service
[Unit]
Description=BirdNET Analysis
[Service]
Restart=always
Type=simple
RestartSec=2
User=${USER}
ExecStart=/home/pi/BirdNET-Analyzer-Pi/birdnet_analysis.sh
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf /home/pi/BirdNET-Analyzer-Pi/templates/birdnet_analysis.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_analysis.service
}

install_recording_service() {
  echo "Installing birdnet_recording.service"
  cat << EOF > /home/pi/BirdNET-Analyzer-Pi/templates/birdnet_recording.service
[Unit]
Description=BirdNET Recording
[Service]
Environment=XDG_RUNTIME_DIR=/run/user/1000
Restart=always
Type=simple
RestartSec=3
User=${USER}
ExecStart=/home/pi/BirdNET-Analyzer-Pi/birdnet_recording.sh
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf /home/pi/BirdNET-Analyzer-Pi/templates/birdnet_recording.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_recording.service
}

set_login() {
  set -x
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

#echo "Adding dependency repos to apt-sources"
#curl -1sLf "$caddy_url" | sudo -E bash

echo "Updating system"
sudo apt update && sudo apt -y upgrade

echo "Installing dependencies"
sudo apt -y install --no-install-recommends ${dependencies[@]}

echo "Installing BirdNET-Analyzer"
install_birdnet

echo "Auto-detecting some settings"
auto-detect_settings

echo "Install BirdNET Analysis Service"
install_birdnet_analysis

echo "Installing Recording Service"
install_recording_service

echo "Configuring System Settings"
set_login
