#!/usr/bin/python
# -*- coding: UTF-8 -*-

__author__ = 'davychen'

import os
import shutil
import sys
import glob

defaultInfoFileName = "default_info.json"
machineInfoFileName = "machine_info.json"
resultFileName = "test_results_for_analysis.txt"
runLogFileName = "runlog.txt"

def getFolderList(_sourceFolder):
    sourceFolderCheck = os.path.join(_sourceFolder, "*")
    tmpFileList = glob.glob(sourceFolderCheck)
    
    tmpFolderList = []
    for tmpFile in tmpFileList :
        if os.path.isdir(tmpFile) :
            tmpFolderList.append(tmpFile)
            
    return tmpFolderList

def main():
    if (len(sys.argv) < 2) :
        print "please input source folder: \n"
        print "combinePieces sourceFolderName \n"
        sys.exit(1)
    
    sourceFolderName = sys.argv[1]
    
    # get target folder name
    targetFolderName = ""
    tmpFolderNum = 1
    while True :
        targetFolderName = sourceFolderName + "_%05d" % tmpFolderNum
        if (os.path.exists(targetFolderName) == False) :
            os.mkdir(targetFolderName)
            break
        tmpFolderNum += 1

    tmpFolderList1 = getFolderList(sourceFolderName)
    
    for tmpPath1 in tmpFolderList1 :
        tmpSrcPathName, tmpSrcFileName = os.path.split(tmpPath1)
        tmpDestPath1 = os.path.join(targetFolderName, tmpSrcFileName)
        os.mkdir(tmpDestPath1)
        
        # get noise data folders
        tmpFolderList2 = getFolderList(tmpPath1)
        for tmpPath2 in tmpFolderList2 :
            tmpSrcPathName, tmpSrcFileName = os.path.split(tmpPath2)
            tmpDestPath2 = os.path.join(tmpDestPath1, tmpSrcFileName)
            os.mkdir(tmpDestPath2)
            
            tmpDestPath3 = os.path.join(tmpDestPath2, "001")
            os.mkdir(tmpDestPath3)
            
            tmpDestResultFileName = os.path.join(tmpDestPath3, resultFileName)
            tmpDestRunLogFileName = os.path.join(tmpDestPath3, runLogFileName)
            
            # dest test_results_for_analysis.txt
            # combine result
            fileHandle = open(tmpDestResultFileName, "w")
            
            # get result pieces folders
            tmpFolderList3 = getFolderList(tmpPath2)
            for tmpPath3 in tmpFolderList3 :
                tmpSrcResultFileName = os.path.join(tmpPath3, resultFileName)
                tmpSrcRunLogFileName = os.path.join(tmpPath3, runLogFileName)
                
                if (os.path.exists(tmpSrcResultFileName)) :
                    for tmpLine in open(tmpSrcResultFileName) :
                        fileHandle.writelines(tmpLine)
                    fileHandle.write("\n")
            
                if (os.path.exists(tmpSrcRunLogFileName)) :
                    shutil.copyfile(tmpSrcRunLogFileName, tmpDestRunLogFileName)
            
            fileHandle.close()
            
        # copy machine_info.json
        tmpSrcPath = os.path.join(tmpPath1, machineInfoFileName)
        tmpDestPath = os.path.join(tmpDestPath1, machineInfoFileName)
        shutil.copyfile(tmpSrcPath, tmpDestPath)
            
        
        
    # copy default_info.json
    tmpSrcPath = os.path.join(sourceFolderName, defaultInfoFileName)
    tmpDestPath = os.path.join(targetFolderName, defaultInfoFileName)
    shutil.copyfile(tmpSrcPath, tmpDestPath)
        
        
        
    
    

main()