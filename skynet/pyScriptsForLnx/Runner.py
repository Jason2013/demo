__author__ = 'mahemad'
import subprocess
import io
import os
import sys

class Runner:
    BENCHMARK_DIR="";
    GENLOG=""
    LogFile=""
    test=""
    def __init__(self, BENCHMARK_DIR, GENLOG, LogFile,test):
        print(LogFile)
        self.BENCHMARK_DIR = BENCHMARK_DIR
        self.GENLOG = GENLOG
        self.LogFile = LogFile
	self.test=test
        self.LogFile.write("\n in Run object ... ")
    def run(self):
        try:
            self.LogFile.write("\nLaunching Benchmark.........")
	    print ("env display:=0 XAUTHORITY=/home/taccuser/.Xauthority " +self.test);	    
	    os.chdir(self.BENCHMARK_DIR);
	    #os.system("sudo -i | env display:=0 XAUTHORITY=/home/taccuser/.Xauthority " +self.test,True);
	    	    
            proc =subprocess.check_output(["env display:=0 XAUTHORITY=/home/taccuser/.Xauthority "+self.test], shell=True)
        except RuntimeError as details:
            self.LogFile.write("\nException occured while running benchmark : "+details)
            self.GENLOG.write("""\n[Result]Status=Fail$description=%s""", details)
            exit(0)

