import os
import requests
import json
from datetime import datetime
import sqlite3

database = os.path.expanduser('~')+'/BirdNET-Analyzer-Pi/birdnet_pi_app/database/database.sqlite'
config = requests.get("http://wttr.in/"+latitude+","+longitude)

