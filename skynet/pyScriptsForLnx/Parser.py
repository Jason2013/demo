__author__ = 'mahemad'
import os
import io
import subprocess
import re

class Parser:
    GENLOG = ""
    BENCHMARK_DIR=""
    SCORE_COL=0
    UNIT_COL=0
    UNIT_NAME=0
    SCORE_COL_NAME=""

    def __init__(self, GENLOG,BENCHMARK_DIR,SCORE_COL_NAME):
        self.GENLOG = GENLOG
        self.BENCHMARK_DIR = BENCHMARK_DIR
        self.SCORE_COL_NAME=SCORE_COL_NAME

    def Check_Result(self):
        if(os.path.isfile(self.BENCHMARK_DIR+"/test_results_for_analysis.txt")):
            self.GENLOG.write("\n[Result]Status=Passed$Description=SearchForTestResults$$Actual=test_results is found$expected=test_results found")
        else:
            self.GENLOG.write("\n[Result]Status=FAILED$Description=SearchForTestResults$$Actual=test_results is not found$expected=test_results found")
            exit(0)

    def Parse(self, parsed_jason, CL_Status):
        BenchmarkLogs = open("test_results_for_analysis.txt");
        lines = list();
        for line in BenchmarkLogs:
            lines.append(line)
        print(lines[1])
        print(lines[1].split(','))
        expected_result=""

        for col in lines[1].split(','):
            if (col.strip()== self.SCORE_COL_NAME):
                print("SCORE COL :", self.SCORE_COL)
                break
            else:
                #print ("Incrementing", col.strip(),self.SCORE_COL_NAME)
                expected_result = expected_result+col.strip()+";"
                self.SCORE_COL = self.SCORE_COL + 1
        print(expected_result)

        units = self.SCORE_COL_NAME
        Results = dict()

        for i in range(2,len(lines)):
            #print(lines[i].split(','), len(lines[i].split(','))-1,lines[i].split(',')[len(lines[i].split(','))-1].strip())
            api_index = len(lines[i].split(','))-1
            api = lines[i].split(',')[api_index].strip()
            test_case = lines[i].split(',')[1].strip()
            score = lines[i].split(',')[self.SCORE_COL].strip()
            actual_ressult = "";
            for j in range(0,self.SCORE_COL):
                actual_ressult = actual_ressult+lines[i].split(',')[j].strip()+";"
            
            ResultItem = dict();
            ResultItem["Status"] = "Passed"
            ResultItem["score"]= score
            ResultItem["api"]=api
            ResultItem["test_case"]=test_case
            ResultItem["expected_result"]=expected_result
            ResultItem["actual_result"]=actual_ressult
            ResultItem["units"]=units
            ResultItem["CL"]=parsed_jason[api]
            print ResultItem
            Results[i-1] = ResultItem;


        if(os.path.isfile("results.ini")):
            os.system('rm -rf '+"results.ini")

        RESULTFILE= open("results.ini",'w')
        RESULTFILE.write("[STEPS]\n")
        RESULTFILE.write("Number="+str(len(Results.keys())))
        RESULTFILE.write("\n")
	print (Results.keys())	
        for step in Results.keys():
            KeyCode = ""
            if step < 10:
                KeyCode ="STEP_00"+str(step)
            elif step >9 and step<100:
                KeyCode = "STEP_0"+str(step)
            else:
                KeyCode = "STEP_"+str(step)
            RESULTFILE.write("["+KeyCode+"]"+"\n")
            RESULTFILE.write("status="+Results[step]["Status"]+"\n")
            RESULTFILE.write("description="+Results[step]["test_case"]+"\n")
            RESULTFILE.write("actual="+Results[step]["actual_result"]+"\n")
            RESULTFILE.write("expected="+Results[step]["expected_result"]+"\n")
            RESULTFILE.write("score="+Results[step]["score"]+"\n")
            RESULTFILE.write("resolution="+Results[step]["api"]+"\n")
            RESULTFILE.write("units="+Results[step]["units"]+"\n")
            RESULTFILE.write("zdepth="+Results[step]["CL"]+"\n")



