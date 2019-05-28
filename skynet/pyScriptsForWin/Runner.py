__author__ = 'mahemad'
import subprocess
import io
import os
import sys

class Runner:
    BENCHMARK_DIR="";
    GENLOG=""
    LogFile="";	test = ""
    def __init__(self, BENCHMARK_DIR, GENLOG, LogFile,test):
        print(LogFile)
        self.BENCHMARK_DIR = BENCHMARK_DIR
        self.GENLOG = GENLOG
        self.LogFile = LogFile
        self.LogFile.write("\n in Run object ... ");self.test=test
    def run(self):
        try:
            self.LogFile.write("\nLaunching Benchmark.........")
            proc =subprocess.check_output(self.BENCHMARK_DIR+"\\microbench.exe "+self.test,cwd=self.BENCHMARK_DIR)
        #except RuntimeError as details:
        #   self.LogFile.write("\nException occured while running benchmark : "+details)
        #   self.GENLOG.write("""\n[Result]Status=Fail$description=%s""", details)
        #   exit(0)
        except:
            self.LogFile.write("\nException occured while running benchmark")

