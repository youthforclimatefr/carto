import unicodedata
import requests
import datetime
import re

USERNAME = gildas
PASSWORD = Br3t0n/GH
WEBDAV_BASE = https://youthforclimate.fr/remote.php/dav/files/
DATE_FORMAT = "%d/%m/%y %H:%M:%S"


# $ curl -u user:pass -T error.log "https://example.com/nextcloud/remote.php/dav/files/USERNAME/$(date '+%d-%b-%Y')/error.log"
# return "https://bimestriel.framapad.org/p/yfc-odj-cr-"+date.getDate()+"-"+(date.getMonth()+1)+"-"+getName().hashcode();

'''Conversion de la commande Discord en valeurs compatibles avec la fonction
Format de base :addréu "un nom" jj/mm hh:mm !Patate1 !Patate2 etc.'''
function convert(command):
    parts = command.split("\"")
    title = parts[1] # Partie entre les guillemets : c'est le titre
    rest = parts[2].split() # Reste de la commande, après le titre
    date = rest[0]+" "+rest[1]
    d = datetime.strptime(date, DATE_FORMAT) # date et heure correctement mises en forme

    if len(rest) > 3:
        # plusieurs patates participantes >>> dossier général
        filename = x.strftime("%y-%m-%d ") + title
        folder = "Général"
    else:
        # ranger dans le dossier de la bonne patate
        folder = "Communication" if rest[2]


function add_meeting(name, date, time, patates):
    
