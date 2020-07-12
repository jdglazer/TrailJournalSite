#!/usr/bin/python3

import re
import sys

schemaFile = sys.argv[1]
newContent = ""

with open(schemaFile, 'r') as file:
    for line in file.readlines():
       if re.match(r'^<database.*', line):
           newContent = newContent + re.sub(r'namespace=[^ ]+', 'namespace=""', line)
       else:
           newContent = newContent + line

with open(schemaFile, 'w') as file:
    file.write(newContent)
