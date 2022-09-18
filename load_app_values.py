import os
import json
import requests
from requests.exceptions import HTTPError
from dotenv import dotenv_values

laravel_env = os.path.expanduser('~')+'/BirdNET-Analyzer-Pi/birdnet_pi_app/.env'
laravel_config = dotenv_values(laravel_env)
app_url = laravel_config.get('APP_URL')

response = requests.get(app_url+"/api/config")
response.raise_for_status()

jsonResponse = response.json()

print(jsonResponse[0])
