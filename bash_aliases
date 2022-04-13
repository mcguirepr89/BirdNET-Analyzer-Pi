alias vimrc='vim ~/.bash_aliases'
alias rerc='source ~/.bash_aliases'

alias stop_services='sudo systemctl stop birdnet\*'

alias restart_services='sudo systemctl restart birdnet_recording && sudo systemctl restart birdnet_analysis'

function full_reload() {
  stop_services &&
  rm -drfv ~/BirdNET-Analyzer-Pi/Raw \
    ~/BirdNET-Analyzer-Pi/Analyzed \
    ~/BirdNET-Analyzer-Pi/Segments \
    ~/BirdNET-Analyzer-Pi/Storage \
    ~/BirdNET-Analyzer-Pi/birds.db &&
  sudo systemctl daemon-reload &&
  restart_services &&
  logs
}

alias logs='tail -f ~/BirdNET-Analyzer-Pi/birdnet_analysis.log'
