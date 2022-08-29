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

dependencies=(git 
python3-dev 
python3-venv 
python3-pip 
ffmpeg 
sqlite3 
alsa-utils 
avahi-utils 
pulseaudio 
bc 
caddy 
sox 
apt-transport-https 
lsb-release 
ca-certificates 
curl)

install_birdnet() {
  git clone https://github.com/mcguirepr89/BirdNET-Analyzer-Pi.git $my_dir
  cd $my_dir
  python3 -m venv birdnet
  source ./birdnet/bin/activate
  pip3 install --upgrade -r $my_dir/requirements.txt
  deactivate
  PYTHON_VENV="$my_dir/birdnet/bin/python3"
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
  SEGMENTS_DIR=$my_dir/$SEGMENTS_DIR
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
ExecStart=$PYTHON_VENV $my_dir/weather_DB.py
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

install_birdnet_stats() {
  cat << EOF > $my_dir/templates/birdnet_stats.service
[Unit]
Description=Streamlit Statistics
[Service]
Restart=on-failure
RestartSec=5
Type=simple
User=$USER
ExecStart=$my_dir/birdnet/bin/streamlit run $my_dir/plotly_streamlit.py --browser.gatherUsageStats false --server.address localhost --server.baseUrlPath "/stats"
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/birdnet_stats.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_stats.service
}

install_birdnet_logs() {
  cat << EOF > $my_dir/templates/birdnet_logs.service
[Unit]
Description=BirdNET Logs
[Service]
Restart=always
Type=simple
RestartSec=2
User=$USER
ExecStart=ttyd -p 8000 -R -b /logs tail -f $my_dir/birdnet_analysis.log
[Install]
WantedBy=multi-user.target
EOF
  sudo ln -sf $my_dir/templates/birdnet_logs.service /usr/lib/systemd/system
  sudo systemctl enable birdnet_logs.service
}


install_Caddyfile() {
  cat << EOF > $my_dir/templates/Caddyfile
http:// {
  root * $my_dir
  file_server browse
  reverse_proxy /stats* localhost:8501
  reverse_proxy /logs* localhost:8000
}
EOF
  sudo ln -sf $my_dir/templates/Caddyfile /etc/caddy/Caddyfile
  sudo systemctl reload caddy
}

install_laravel_depends() {
  sudo apt -y install php8.1-fpm php8.1-curl php8.1-mbstring php8.1-sqlite3 phpunit
}

install_composer() {
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  sudo mv composer.phar /usr/local/bin/composer
}

install_nodejs() {
  curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
  sudo apt-get install -y nodejs
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
sudo curl -sSLo /usr/share/keyrings/deb.sury.org-php.gpg https://packages.sury.org/php/apt.gpg
sudo sh -c 'echo "deb [signed-by=/usr/share/keyrings/deb.sury.org-php.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
sudo apt-get update

echo "Updating system"
sudo apt update && sudo apt -y upgrade

echo "Installing dependencies"
sudo apt -y install --no-install-recommends ${dependencies[@]}

echo "Installing BirdNET-Analyzer"
install_birdnet

echo "Install bin"
sudo cp -r $my_dir/bin/* /usr/local/bin

echo "Auto-detecting some settings"
auto-detect_settings

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

echo "Install Laravel Dependencies"
install_laravel_depends

echo "Install Composer"
install_composer

echo "Install NodeJS"
install_nodejs

echo "Installing bash_aliases"
install_bash_aliases

echo "Configuring System Settings"
set_login
