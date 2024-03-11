# Quick script to convert the Boxicons website JSON into YAML

# pip3 install pyyaml
import json, yaml

# https://github.com/atisawd/boxicons_web/blob/master/_next/data/DNHfRjsQaNmPABrOMCg7E/index.json
with open('index.json') as f:
    icons = json.load(f)

icons = icons['pageProps']['icons']

style_map = {
    'REGULAR': 'regular',
    'SOLID': 'solid',
    'LOGO': 'logos'
}

icon_map = {}

for i in icons:
    if i['name'] in icon_map:
        icon_map[ i['name'] ]['styles'].append(style_map[ i['type_of_icon'] ])
        icon_map[ i['name'] ]['search']['terms'] = icon_map[ i['name'] ]['search']['terms'] + i['term']
    else:
        icon_map[ i['name'] ] = {
            'label': i['name'],
            'styles': [ style_map[ i['type_of_icon'] ] ],
            'search': {
                'terms': i['term']
            }
        }

with open('icons.yml', 'w') as yaml_file:
    yaml.dump(icon_map, yaml_file)