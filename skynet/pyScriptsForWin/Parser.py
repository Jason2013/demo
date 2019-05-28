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
        if(os.path.isfile(self.BENCHMARK_DIR+"\\test_results_for_analysis.txt")):
            self.GENLOG.write("\n[Result]Status=Passed$Description=SearchForTestResults$$Actual=test_results is found$expected=test_results found")
        else:
            self.GENLOG.write("\n[Result]Status=FAILED$Description=SearchForTestResults$$Actual=test_results is not found$expected=test_results found")
            exit(0)

    def Parse(self, parsed_jason, CL_Status):
        BenchmarkLogs = open(self.BENCHMARK_DIR+"\\test_results_for_analysis.txt");
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
        for i in range(2,len(lines)):
            #print(lines[i].split(','), len(lines[i].split(','))-1,lines[i].split(',')[len(lines[i].split(','))-1].strip())
            api_index = len(lines[i].split(','))-1
            api = lines[i].split(',')[api_index].strip()
            test_case = lines[i].split(',')[1].strip()
            score = lines[i].split(',')[self.SCORE_COL].strip()
            actual_ressult = "";
            for j in range(0,self.SCORE_COL):
                actual_ressult = actual_ressult+lines[i].split(',')[j].strip()+";"
            print actual_ressult;
            print("""\n[Result]status=Passed$Step_Name=%s$Score=%s$API=%s$resolution=%s$Units=%s$expected=%s$actual=%s"""%(test_case,score,api,parsed_jason[api],units,expected_result,actual_ressult))
            self.GENLOG.write(("""\n[Result]status=Passed$Step_Name=%s$Score=%s$zdepth=%s$resolution=%s$Units=%s$expected=%s$actual=%s""")%(test_case,score,api,parsed_jason[api],units,expected_result,actual_ressult))



