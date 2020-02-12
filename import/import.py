from pony.orm import *
import json

def string_escape(s, encoding='utf-8'):
    return (s.encode('latin1')         # To bytes, required by 'unicode-escape'
             .decode('unicode-escape') # Perform the actual octal-escaping decode
             .encode('latin1')         # 1:1 mapping back to bytes
             .decode(encoding))        # Decode original encoding

db = Database()
class Test2(db.Entity):
    _table_ = "test2"
    id = PrimaryKey(str)
    name = Required(str)
    description = Required(str)
    lat = Required(float)
    lon = Required(float)

db.bind(provider='mysql', host='becauseofprog.fr', user='tv-player', passwd='zbTzQLd52paGWZTxQiqsr4YmYYdeMo', db='tv-player')
db.generate_mapping(create_tables=False)


with open("import/cplc-14mars.json") as json_file:
    data = json.load(json_file)

    for city in data: # each row is a list
        with db_session:
            entry = Test2(
                id=city["ville"],
                name=city["ville"],
                description=city["ville"] + "<br/><a href=\"" + string_escape(city['url']) + "\" target=\"_blank\">Evenement Facebook : " + string_escape(city["url"]) + "</a>",
                lat=city["lat"],
                lon=city["lon"])

print("ok")