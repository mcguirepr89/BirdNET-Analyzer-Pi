#!/home/pi/BirdNET-Analyzer-Pi/birdnet/bin/python3
# command line to get weather for a particular Lat/Lon -
# Suggest run once an hour?
#  python weather_DB.py --lat XX.XX --lon XX.XX

import os
import argparse
import requests
from datetime import datetime
import sqlite3
from sqlite3 import Connection
import config as cfg

userDir = os.path.expanduser('~')
bnpDir = userDir + '/BirdNET-Analyzer-Pi/'
URI_SQLITE_DB = bnpDir + cfg.DATABASE_PATH

def get_connection(path:str):
    return sqlite3.connect(path,check_same_thread=False)

conn = get_connection(URI_SQLITE_DB)

#Get date and time now in standard format
now=datetime.now()
now_date = now.date().strftime("%Y-%m-%d")
now_time=now.time().strftime("%H:%M")

#Get latitude and longitude of BirdNET-Pi config
lat = cfg.LATITUDE
lon = cfg.LONGITUDE


#Get weather from wttr for location

try:
    weather = now_date+";"+now_time+";"+str(lat)+';'+str(lon)+ \
    ';'+requests.get('https://wttr.in/'+str(lat)+','+str(lon)
    +'?format=%t;%h;%C;%p;%P;%D;%S;%s;%d\n').text
except:
    weather = now_date+";"+now_time+str(";N/A")*11
    
print(weather)
with open(bnpDir+'weather.txt','a') as rfile:
    rfile.write('\n')
    rfile.write(weather)


def insert_variables_into_table(weather):
        try:
            conn = get_connection(URI_SQLITE_DB)
            cursor = conn.cursor()
            cursor.execute("""CREATE TABLE IF NOT EXISTS weather (Date,Time,Lat,Lon, Temp,Humidity,Conditions,Precipitation,Pressure,Dawn,Sunrise,Sunset,Dusk)""")
            
            
            mySql_insert_query = """INSERT INTO weather VALUES (?,?,?,?, ?,?,?,?,?,?,?,?,?) """
    
#             record = (weather)

            cursor.execute(mySql_insert_query, weather)
            conn.commit()
            print("Record inserted successfully into detections table")

    
        except conn.Error as error:
            print("Failed to insert record into detections table {}".format(error))
        
        finally:
            if conn:
                conn.close()
                print("SQL connection is closed")

weather_t=tuple(map(str,weather.split(';')))
insert_variables_into_table(weather_t)


# wttr cheatsheet
#     c    Weather condition,
#     C    Weather condition textual name,
#     x    Weather condition, plain-text symbol,
#     h    Humidity,
#     t    Temperature (Actual),
#     f    Temperature (Feels Like),
#     w    Wind,
#     l    Location,
#     m    Moon phase 🌑🌒🌓🌔🌕🌖🌗🌘,
#     M    Moon day,
#     p    Precipitation (mm/3 hours),
#     P    Pressure (hPa),
# 
#     D    Dawn*,
#     S    Sunrise*,
#     z    Zenith*,
#     s    Sunset*,
#     d    Dusk*,
#     T    Current time*,
#     Z    Local timezone.
