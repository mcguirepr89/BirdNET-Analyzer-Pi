alias vimrc='vim ~/.bash_aliases'
alias rerc='source ~/.bash_aliases'

alias stop_services='sudo systemctl stop birdnet\* weather'
alias start_services='sudo systemctl start birdnet\* weather'

alias restart_services='sudo systemctl restart birdnet_recording birdnet_analysis weather caddy avahi-alias@$(hostname).local.service'
alias logs='sudo -E tail -f ~/BirdNET-Analyzer-Pi/birdnet_analysis.log'

alias tinker='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;php artisan tinker'
alias seed='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;php artisan migrate:fresh --seed'
alias db='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;php artisan db'
alias build='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;npm run build'

alias batstream='rec -r 384000 -p | sox - -p sinc 18k pitch -2500| ffmpeg -loglevel 32 -i - -acodec libmp3lame -b:a 320k -content_type "audio/mpeg" -f mp3 icecast://source:getinin4949@localhost:8081/stream -re'

cd ~/BirdNET-Analyzer-Pi
