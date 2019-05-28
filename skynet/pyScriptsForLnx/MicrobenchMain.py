#!/usr/bin/python
# -*- coding: UTF-8 -*-

__author__ = 'mahemad'
import Runner
import Parser
import subprocess
import io
import json
import sys

import os
import shutil
import zipfile

from poster.encode import multipart_encode
from poster.streaminghttp import register_openers
import urllib, urllib2,sys

#Global Variables
BENCHMARK_DIR = "/home/taccuser/Desktop/WeeklyTesting/today/Linux";
LogFile="";
GENLOG=""
GENLOGFILE = "general.log"
CL_Status = False;
UNIT_NAME = ""
MB_RESULT_FILE_NAME = "test_results_for_analysis.txt"
MB_RESULT_FILE_NAME2 = "runlog.txt"
MACHINE_CONFIG = "/home/taccuser/Desktop/WeeklyTesting/today/Microbench/machineConfig/machine_info.json"
DEFAULT_CONFIG = "/home/taccuser/Desktop/WeeklyTesting/today/Microbench/machineConfig/default_info.json"
CLINFO_CONFIG = "/home/taccuser/Desktop/WeeklyTesting/today/Driver_Details/CLInfo.txt"
ZIP_FOLDER_NAME = "/home/taccuser/Desktop/WeeklyTesting/today/Microbench/temp"
PHP_SERVER_NAME = "gfxbench/benchMaxDBA"

def main():
    (LogFile,GENLOG)=Prep()
    #runner = Runner.Runner(BENCHMARK_DIR, GENLOG, LogFile, sys.argv[2]);
    #runner.run();
    
    # clear zip temp folder
    try:
        shutil.rmtree(ZIP_FOLDER_NAME)
    except:
        pass
    #os.mkdir(ZIP_FOLDER_NAME + "/")
    os.makedirs(ZIP_FOLDER_NAME + "/")
    
    machineInfo = Get_Json(MACHINE_CONFIG)
    defaultInfo = Get_Json(DEFAULT_CONFIG)
    
    machineFolder = machineInfo["videoCardName"] + "_" + machineInfo["systemName"] + "_result"
    # create card & system folder
    if (os.path.exists(ZIP_FOLDER_NAME + "/" + machineFolder) == False):
        os.mkdir(ZIP_FOLDER_NAME + "/" + machineFolder + "/")
    
    testName = ""
    apiName = ""
    
    zipSrcFileList = []
    zipPackFileList = []
    for i in range(5):
        runner = Runner.Runner(BENCHMARK_DIR, GENLOG, LogFile,sys.argv[2]);
        runner.run();
        # create noise data folder
        os.mkdir(ZIP_FOLDER_NAME + "/" + machineFolder + "/" + str(i + 1) + "/")
        #get testname from result
        testName = ""
        apiPosIndex = -1
        apiName = ""
        minColumnNum = 4
        BenchmarkLogs = open(BENCHMARK_DIR + "/" + MB_RESULT_FILE_NAME);
        for line in BenchmarkLogs:
            dataSet = line.split(',')
            
            if (len(dataSet) < minColumnNum):
                continue
            
            for j in range(len(dataSet)):
                dataSet[j] = dataSet[j].strip()
            
            t1 = dataSet[0]
            if (len(t1) > 0):
                testName = t1
                
            if (apiPosIndex == -1):
                try:
                    apiPosIndex = dataSet.index("API")
                except:
                    pass
            else:
                apiName = dataSet[apiPosIndex]
            if (len(apiName) > 0):
                break
        BenchmarkLogs.close();
        
        t1 = ZIP_FOLDER_NAME + "/" + machineFolder + "/" + str(i + 1) + "/" + testName + "_" + apiName
        os.mkdir(t1 + "/")
        t2 = machineFolder + "/" + str(i + 1) + "/" + testName + "_" + apiName
        
        shutil.copyfile(BENCHMARK_DIR + "/" + MB_RESULT_FILE_NAME, t1 + "/" + MB_RESULT_FILE_NAME)
        shutil.copyfile(BENCHMARK_DIR + "/" + MB_RESULT_FILE_NAME2, t1 + "/" + MB_RESULT_FILE_NAME2)
        zipSrcFileList.append(t1 + "/" + MB_RESULT_FILE_NAME)
        zipSrcFileList.append(t1 + "/" + MB_RESULT_FILE_NAME2)
        
        zipPackFileList.append(t2 + "/" + MB_RESULT_FILE_NAME)
        zipPackFileList.append(t2 + "/" + MB_RESULT_FILE_NAME2)
        
    zipHandle = zipfile.ZipFile('resultFiles.zip', 'w', zipfile.ZIP_DEFLATED)
    for i in range(5):
        zipHandle.write(zipSrcFileList[i * 2], zipPackFileList[i * 2])
        zipHandle.write(zipSrcFileList[i * 2 + 1], zipPackFileList[i * 2 + 1])
    zipHandle.write(MACHINE_CONFIG, machineFolder + "/" +os.path.basename(MACHINE_CONFIG))
    zipHandle.write(DEFAULT_CONFIG, os.path.basename(DEFAULT_CONFIG))
    zipHandle.close()
    print "result files zipping done"
    GENLOG.write("=========================\n")
    GENLOG.write("result files zipping done\n")
    GENLOG.write("=========================\n")
    
    # must delete
    #testName = "Fillrate"
    #apiName = "D3D11"
    
    # upload zipped result
    register_openers()
    tmpPath = "./resultFiles.zip"
    datagen, headers = multipart_encode({'resultFiles': open(tmpPath, "rb")})
    request = urllib2.Request("http://" + PHP_SERVER_NAME + "/phplibs/server/swtGetResultFileSkynet.php", datagen, headers)
    #request = urllib2.Request("http://gfxbench/phplibs/server/swtGetResultFile.php", datagen, headers)

    ret = urllib2.urlopen(request).read()
    
    GENLOG.write("=========================\n")
    GENLOG.write(ret + "\n")
    GENLOG.write("=========================\n")
    
    retMsg = json.loads(ret)
    print "upload result done"
    GENLOG.write("=========================\n")
    GENLOG.write("upload result done\n")
    GENLOG.write("=========================\n")
    
    pathName = retMsg["pathName"]
    batchID = retMsg["batchID"]
    
    # import noise data to DB
    
    _logFolderName = pathName
    _nextResultFileID = 0
    _nextLineID = 0
    _curFileLineNum = 0
    _resultFileNum = 0
    _curTestID = 0
    _nextSubTestID = 0
    _batchID = batchID
    _reportGroup = 1
    
    while (1):
        url = "http://" + PHP_SERVER_NAME + "/phplibs/importResult/swtParseBenchLogManualSkynet.php"  
        params = {"logFolderName":    str(_logFolderName),
                  "nextResultFileID": str(_nextResultFileID),
                  "nextLineID":       str(_nextLineID),
                  "curFileLineNum":   str(_curFileLineNum),
                  "resultFileNum":    str(_resultFileNum),
                  "curTestID":        str(_curTestID),
                  "nextSubTestID":    str(_nextSubTestID),
                  "batchID":          str(_batchID),
                  "reportGroup":      str(_reportGroup),
                  "resultTestName":   str(testName),
                  "resultApiName":    str(apiName),
                  "machineFolder":    str(machineFolder)}
        data = urllib.urlencode(params)
        req = urllib2.Request(url,data)  
        response = urllib2.urlopen(req)  
        res = response.read()
        
        GENLOG.write("=========================\n")
        GENLOG.write(res + "\n")
        GENLOG.write("=========================\n")
        
        retMsg2 = json.loads(res)
        
        #print retMsg2
        
        errorCode =         int(retMsg2["errorCode"])
        errorMsg  =         retMsg2["errorMsg"]
        parseFinished =     int(retMsg2["parseFinished"])
        _nextResultFileID = int(retMsg2["nextResultFileID"])
        _nextLineID =       int(retMsg2["nextLineID"])
        _curFileLineNum =   int(retMsg2["curFileLineNum"])
        _resultFileNum =    int(retMsg2["resultFileNum"])
        _curTestID =        int(retMsg2["curTestID"])
        _nextSubTestID =    int(retMsg2["nextSubTestID"])
        
        if (errorCode != 1):
            print errorMsg
            #print retMsg2
            break
        
        if (parseFinished == 1):
            print "feeding database: 100%"
            break
        else:
            if (_resultFileNum > 0):
                f1 = 1.0 / _resultFileNum
                f2 = 0.0
                if ((_curFileLineNum > 0) and (_curFileLineNum > _nextLineID)):
                    f2 = _nextLineID / _curFileLineNum
                f3 = f1 * f2
                f4 = _nextResultFileID * f1
                f5 = "%.1f" % ((f3 + f4) * 100.0 )
                print "feeding database: " + f5 + "%"
    
    print "feeding database done"
    GENLOG.write("=========================\n")
    GENLOG.write("feeding database done\n")
    GENLOG.write("=========================\n")
    
    _logFolderName = pathName
    _batchID = batchID
    _resultPos = 0
    _testPos = 0
    _testCasePos = 0
    _curTestCaseNum = 0
    _curTestNoiseNum = 0
    
    while (1):
        url = "http://" + PHP_SERVER_NAME + "/phplibs/importResult/swtCalcNoiseAverageSkynet.php"  
        params = {"batchID":         str(_batchID),
                  "resultPos":       str(_resultPos),
                  "testPos":         str(_testPos),
                  "testCasePos":     str(_testCasePos),
                  "curTestCaseNum":  str(_curTestCaseNum),
                  "curTestNoiseNum": str(_curTestNoiseNum),
                  "resultTestName":  str(testName),
                  "resultApiName":   str(apiName),
                  "machineFolder":   str(machineFolder),
                  "logFolderName":   str(_logFolderName)}
        data = urllib.urlencode(params)
        req = urllib2.Request(url,data)  
        response = urllib2.urlopen(req)  
        res = response.read()
        
        GENLOG.write("=========================\n")
        GENLOG.write(res + "\n")
        GENLOG.write("=========================\n")
        
        retMsg2 = json.loads(res)
        
        #print retMsg2
        
        errorCode =         int(retMsg2["errorCode"])
        errorMsg  =         retMsg2["errorMsg"]
        parseFinished =     int(retMsg2["parseFinished"])

        if (errorCode != 1):
            print errorMsg
            #print retMsg2
            break
        
        if (parseFinished == 1):
            print "calc average: 100%"
            break
        else:
            _resultPos =        int(retMsg2["resultPos"])
            _testPos =          int(retMsg2["testPos"])
            _testCasePos =      int(retMsg2["testCasePos"])
            _curTestCaseNum =   int(retMsg2["curTestCaseNum"])
            _curTestNoiseNum =  int(retMsg2["curTestNoiseNum"])
            _resultNum =        int(retMsg2["resultNum"])
            _testNum =          int(retMsg2["testNum"])
        
            tmpResultPos = _resultPos
            tmpResultNum = _resultNum
            tmpTestPos = _testPos
            tmpTestNum = _testNum
            f1 = tmpResultPos / tmpResultNum
            f2 = tmpTestPos / tmpTestNum
            f3 = f2 / tmpResultNum
            f5 = "%.1f" % ((f1 + f3) * 100.0 )
            print "calc average: " + f5 + "%"
    
    print "calc average done"
    GENLOG.write("=========================\n")
    GENLOG.write("calc average done\n")
    GENLOG.write("=========================\n")
    
    
    '''
    parser = Parser.Parser(GENLOG,BENCHMARK_DIR,sys.argv[1])
    parser.Check_Result()
    parser.Parse(Get_CLInfo(), CL_Status)
    '''

def Prep():
    print("Checking Preconditions ... ");
    if(os.path.isfile("Logscript.txt")):
        os.system('rm -f Logscript.txt')
    LogFile = open("Logscript.txt",'w');
    LogFile.write("************Logging started **********\n")
    if(os.path.isfile(GENLOGFILE)):
    	os.system('rm -f '+GENLOGFILE)
    GENLOG= open(GENLOGFILE,'w')

    if(os.path.isdir(BENCHMARK_DIR)):
        print("Benchmark dir found .....\n")
        GENLOG.write("[Result]status=Pass$stepname=Check for Benchmark directory")
    else:
        print("Benchmark directory not found exiting benchmark "+BENCHMARK_DIR)
        GENLOG.write("[Result]Status=Fail$stepname=Check for Benchmark directory")
        exit(0)
    #os.system("del "+BENCHMARK_DIR+"/*.* /y")
    return  (LogFile,GENLOG)
'''
def Get_CLInfo():
    CL_FILE = "CLInfo.txt"
    if (os.path.isfile(CL_FILE)):
        CL_Status = True;
        clinfo = open(CL_FILE)
        lines = ""
        for line in clinfo:
            lines = lines + line.rstrip('\n').lstrip('\n');
        print(lines)
        parsed_jason = json.loads(lines);
        return parsed_jason
'''

def Get_Json(_jsonName):
    CL_FILE = _jsonName
    if (os.path.isfile(CL_FILE)):
        CL_Status = True;
        clinfo = open(CL_FILE)
        lines = ""
        for line in clinfo:
            lines = lines + line.rstrip('\n').lstrip('\n');
        #print(lines)
        parsed_jason = json.loads(lines);
        return parsed_jason

main()

