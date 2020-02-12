from pony.orm import *
import unidecode
import json


db = Database()
class GroupesLocaux(db.Entity):
    _table_ = "GroupesLocaux"
    id = PrimaryKey(str)
    name = Required(str)
    description = Required(str)
    lat = Required(float)
    lon = Required(float)

db.bind(provider='mysql', host='becauseofprog.fr', user='tv-player', passwd='zbTzQLd52paGWZTxQiqsr4YmYYdeMo', db='tv-player')
db.generate_mapping(create_tables=False)


with open("gl-yfc-final.json") as json_file:
    data = json.load(json_file)

    for point in data["features"]: # each row is a list
        try:
            point["properties"]["name"]
            point["properties"]["description"]
        except KeyError:
            # Undefined 
            print("No properties. Skipped")

        else:
            # Defined
            print("Adding point...")
            pointId = ''.join(e for e in point["properties"]["name"] if e.isalnum())
            finalPointId = unidecode.unidecode(pointId)

            print("Currently adding : "+finalPointId)
            
            with db_session:
                entry = GroupesLocaux(
                    id=finalPointId,
                    name=point["properties"]["name"],
                    description=point["properties"]["description"],
                    lat=point["geometry"]["coordinates"][1],
                    lon=point["geometry"]["coordinates"][0])

print("Fini!")