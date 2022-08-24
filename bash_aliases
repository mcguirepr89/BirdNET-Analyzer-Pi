alias vimrc='vim ~/.bash_aliases'
alias rerc='source ~/.bash_aliases'

alias stop_services='sudo systemctl stop birdnet\* weather'
alias start_services='sudo systemctl start birdnet\* weather'

alias restart_services='sudo systemctl restart birdnet_recording birdnet_analysis weather caddy avahi-alias@$(hostname).local.service'
alias logs='sudo tail -f /dev/shm/birdnet_analysis.log'

alias tinker='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;php artisan tinker'
alias seed='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;php artisan migrate:fresh --seed'
alias build='cd ~/BirdNET-Analyzer-Pi/birdnet_pi_app;npm run build'

cd ~/BirdNET-Analyzer-Pi
