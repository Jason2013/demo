#!/usr/bin/python
# -*- coding: UTF-8 -*-

import glob
import os
import zipfile
import os.path

from poster.encode import multipart_encode
from poster.streaminghttp import register_openers
import urllib2,sys

def __line__ ():
    caller = inspect.stack()[1]
    return int(caller[2])

# zipping result files, then send to server
f = zipfile.ZipFile('resultFiles.zip', 'w', zipfile.ZIP_DEFLATED)

tmpPathList = glob.glob(os.path.join("C:/testres/MISTestSkynet", "*.txt"))

if len(tmpPathList) == 0:
    print "result files all missing, line: "
    f.close()
    exit()

for tmpName in tmpPathList:
    f.write(tmpName, os.path.basename(tmpName))

tmpPath = os.path.join("c:/MIS/json", "machine_info.json")
if os.path.isfile(tmpPath):
    f.write(tmpPath, os.path.basename(tmpPath))
else:
    print tmpPath, "is missing, line: ", __line__()
    f.close()
    exit()
    
tmpPath = os.path.join("c:/MIS/json", "default_info.json")
if os.path.isfile(tmpPath):
    f.write(tmpPath, os.path.basename(tmpPath))
else:
    print tmpPath, "is missing, line: ", __line__()
    f.close()
    exit()
	
f.close()
print "result files zipping done"


register_openers()

tmpPath = os.path.join(".", "resultFiles.zip")
datagen, headers = multipart_encode({'resultFiles': open(tmpPath, "rb")})
#request = urllib2.Request("http://localhost/benchMax/phplibs/server/swtGetResultFile.php", datagen, headers)
request = urllib2.Request("http://gfxbench/phplibs/server/swtGetResultFile.php", datagen, headers)

print urllib2.urlopen(request).read()



