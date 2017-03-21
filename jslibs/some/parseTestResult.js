var swtLastBatchMachineIDList = null;

function swtSubmitTestResultsMannual(_inputTagName, _percentTagName)
{
    $("#" + _percentTagName).html("feeding database: 0%");
    var t1 = $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        alert("please fill in folder name");
        return;
    }
    swtDoSubmitTestResults(_inputTagName,
                           _percentTagName,
                           t1,
                           0,
                           0,
                           0,
                           0,
                           0,
                           0,
                           -1);
}

function swtSubmitTestResultsMannual2(_inputTagName,
                                      _percentTagName,
                                      _usernameTagName,
                                      _passwordTagName,
                                      _targetTagName)
{
    var t5 = $("input[name='reportGroup']:checked").val();
    
    $("#" + _percentTagName).html("copying files: 0%");
    var t1 = $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        alert("please fill in folder name");
        return;
    }
    var t2 = $("#" + _usernameTagName).val();
    var t3 = $("#" + _passwordTagName).val();
    var t4 = $("#" + _targetTagName).val();
    $.cookie('benchMaxUsername', t2);
    $.cookie('benchMaxPassword', t3);
    swtDoCopyResultFiles(_inputTagName,
                         _percentTagName,
                         t4, // batch ID
                         t1,
                         t2,
                         t3,
                         "",
                         0,
                         "",
                         "");
}

function swtDoCopyResultFiles(_inputTagName,
                              _percentTagName,
                              _batchID,
                              _logFolderName,
                              _username,
                              _password,
                              _allFileListString,
                              _fileID,
                              _parentFolder,
                              _parentFolderOnly)
{
    $.post("../phplibs/getInfo/swtGetFolderAllFileNames.php", 
    {
        batchID:           _batchID,
        logFolderName:     _logFolderName,
        username:          _username,
        password:          _password,
        allFileListString: _allFileListString,
        fileID:            _fileID,
        parentFolder:      _parentFolder,
        parentFolderOnly:  _parentFolderOnly
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (json.copyFileFinished == "1")
            {
                $("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("copying files: 100%");
                // copying result files finished,
                // start to feed data into database
                swtDoSubmitTestResults(_inputTagName,
                                       _percentTagName,
                                       json.parentFolderOnly,
                                       0,
                                       0,
                                       0,
                                       0,
                                       0,
                                       0,
                                       _batchID);
            }
            else
            {
                swtDoCopyResultFiles(_inputTagName,
                                     _percentTagName,
                                     _batchID,
                                     _logFolderName,
                                     _username,
                                     _password,
                                     json.allFileListString,
                                     json.fileID,
                                     json.parentFolder,
                                     json.parentFolderOnly);
                if (json.fileID <= json.fileNum)
                {
                    $("#" + _percentTagName).html("copying files: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            alert(json.errorMsg);
        }
    });
}

function swtDoSubmitTestResults(_inputTagName,
                                _percentTagName,
                                _logFolderName,
                                _nextResultFileID,
                                _nextLineID,
                                _curFileLineNum,
                                _resultFileNum,
                                _curTestID,
                                _nextSubTestID,
                                _batchID)
{
    var reportGroupName = $("input[name='reportGroup']:checked").val();
    var reportGroup = 1;
    if (reportGroupName == "routineReport")
    {
        reportGroup = 1;
    }
    else if (reportGroupName == "tempReport")
    {
        reportGroup = 2;
    }
    
    //alert(reportGroup);
    
    $.post("../phplibs/importResult/swtParseBenchLogManual.php", 
    {
        logFolderName:    _logFolderName,
        nextResultFileID: _nextResultFileID,
        nextLineID:       _nextLineID,
        curFileLineNum:   _curFileLineNum,
        resultFileNum:    _resultFileNum,
        curTestID:        _curTestID,
        nextSubTestID:    _nextSubTestID,
        batchID:          _batchID,
        reportGroup:      reportGroup
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (json.parseFinished == "1")
            {
                $("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("feeding database: 100%");
                alert("import success");
                //location.reload(true);
            }
            else
            {
                swtDoSubmitTestResults(_inputTagName,
                                       _percentTagName,
                                       _logFolderName,
                                       json.nextResultFileID,
                                       json.nextLineID,
                                       json.curFileLineNum,
                                       json.resultFileNum,
                                       json.curTestID,
                                       json.nextSubTestID,
                                       json.batchID);
                if (parseInt(_resultFileNum) > 0)
                {
                    var resultFileNum = parseFloat(_resultFileNum);
                    var nextResultFileID = parseFloat(json.nextResultFileID);
                    var nextLineID = parseFloat(json.nextLineID);
                    var curFileLineNum = parseFloat(json.curFileLineNum);
                    var f1 = 0.0;
                    if (resultFileNum > 0)
                    {
                        f1 = 1.0 / resultFileNum;
                    }
                    var f2 = 0.0;
                    
                    if ((curFileLineNum > 0) && (curFileLineNum > nextLineID))
                    {
                        f2 = nextLineID / curFileLineNum;
                    }
                    var f3 = f1 * f2;
                    var f4 = nextResultFileID * f1;
                    // parseFloat(s).toFixed(1);
                    $("#" + _percentTagName).html("feeding database: " + ((f3 + f4) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
    });
}

function swtGenerateRoutineReport(_percentTagName, _reportListTag, _reportType, _batchID)
{
    $("#" + _percentTagName).html("0%");
    
    $("#" + _reportListTag).html("");

    var t1 = $("#valMachineIDList").val();
    //console.log("valMachineIDList: " + t1);
    var machineIDList = [];
    if (t1.length > 0)
    {
        machineIDList = t1.split(",");
    }
    var machineIDPair = [];
    var checkedMachineIDList = [];
    var t2 = "";
    var t3 = "";
    if (_reportType == 0)
    {
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            t1 = $("#selMachineID" + machineIDList[i]).val();
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1 == false)
            {
                //console.log("skip" + i);
                continue;
            }
            var n1 = parseInt(t1);
            if (n1 != -1)
            {
                machineIDPair.push(parseInt(machineIDList[i]));
                machineIDPair.push(n1);
            }
            checkedMachineIDList.push(parseInt(machineIDList[i]));
        }
        t2 = swtImplode(machineIDPair, ",");
        t3 = swtImplode(checkedMachineIDList, ",");
    }


    //console.log("---: " + t2);
    swtDoGenerateFlatData(_percentTagName,
                          _batchID,
                          _reportType,
                          -1,
                          t2,
                          t3
                          );
}

function swtDoGenerateFlatData(_percentTagName,
                               _batchID,
                               _reportType,
                               _curReportFolder,
                               _machineIDPair,
                               _machineIDList
                               )
{
    $.post("../phplibs/createReport/swtGenTempFlatData.php", 
    {
        batchID:               _batchID,
        reportType:            _reportType,
        curReportFolder:       _curReportFolder,
        machineIDPair:         _machineIDPair,
        machineIDList:         _machineIDList
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (json.parseFinished == "1")
            {
                //console.log("----");
                swtDoGenerateRoutineReport(_percentTagName,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           1,
                                           _reportType,
                                           0,
                                           0,
                                           0,
                                           0,
                                           0,
                                           0,
                                           0,
                                           json.curReportFolder,
                                           -1,
                                           -1,
                                           0
                                           //""
                                           );
            }
            else
            {
                swtDoGenerateFlatData(_percentTagName,
                                      _batchID,
                                      _reportType,
                                      json.curReportFolder,
                                      _machineIDPair,
                                      _machineIDList
                                      );
                if (json.fileID <= json.fileNum)
                {
                    $("#" + _percentTagName).html("gen flatData: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            alert(json.errorMsg);
        }
    });
}

function swtDoGenerateRoutineReport(_percentTagName,
                                    _batchID,
                                    _machineIDList,
                                    _machineIDPair,
                                    _forceGenReport,
                                    _reportType,
                                    _resultPos,
                                    _curTestPos,
                                    _nextSubTestPos,
                                    _subTestNum,
                                    _lineNumPos,
                                    _tempFileLineNumPos,
                                    _reportToken,
                                    _curReportFolder,
                                    _firstTestPos,
                                    _firstSubTestPos,
                                    _sheetLinePos
                                    //_subTestUmdDataMaskList
                                    )
{
    $.post("../phplibs/createReport/swtCompileReportAddition.php", 
    {
        batchID:        _batchID,
        machineIDPair:  _machineIDPair,
        machineIDList:  _machineIDList,
        forceGenReport: _forceGenReport,
        reportType:     _reportType,
        resultPos:      _resultPos,
        curTestPos:     _curTestPos,
        nextSubTestPos: _nextSubTestPos,
        subTestNum:     _subTestNum,
        lineNumPos:     _lineNumPos,
        tempFileLineNumPos: _tempFileLineNumPos,
        reportToken:        _reportToken,
        curReportFolder:    _curReportFolder,
        firstTestPos:       _firstTestPos,
        firstSubTestPos:    _firstSubTestPos,
        sheetLinePos:       _sheetLinePos
        //subTestUmdDataMaskList: _subTestUmdDataMaskList
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (json.compileFinished == "1")
            {
                $("#" + _percentTagName).html("converting XML to XLSX, please wait...");
                swtXLSXBatchReport(_percentTagName, json.batchID, _reportType, 0, _curReportFolder);
            }
            else
            {   
                swtDoGenerateRoutineReport(_percentTagName,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           0,
                                           _reportType,
                                           json.resultPos,
                                           json.curTestPos,
                                           json.nextSubTestPos,
                                           json.subTestNum,
                                           json.lineNumPos,
                                           json.tempFileLineNumPos,
                                           json.reportToken,
                                           json.curReportFolder,
                                           json.firstTestPos,
                                           json.firstSubTestPos,
                                           json.sheetLinePos
                                           //json.subTestUmdDataMaskList
                                           );
                if ((json.resultNum > 0) &&
                    (json.testNum   > 0))
                {
                    var f1 = 1.0 / json.resultNum;
                    var f2 = f1 * json.curTestPos / json.testNum;
                    var f3 = f1 * json.resultPos;
                    var f4 = (f3 + f2) * 100.0;
                    $("#" + _percentTagName).html("gen report: " + f4.toFixed(1) + "%");
                }
            }
        }
    });
}

function swtZipBatchReport(_percentTagName, _batchID, _reportType, _fileID)
{
    $.post("../phplibs/createReport/swtZipBatchReport.php", 
    {
        batchID:        _batchID,
        fileID:         _fileID
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (_reportType == 0)
            {
                // only generating latest report
                alert("generate success");
                location.reload(true);
            }
            else
            {
                // generate all history reports
                $("#" + _percentTagName).html("100%");
                swtGenAllHistoryReports();
            }
        }
        else if (json.errorCode == "2")
        {
            // generate all history reports
            $("#" + _percentTagName).html(json.errorMsg);
            swtZipBatchReport(_percentTagName, _batchID, _reportType, _fileID + 1);
        }
    });
}

function swtXLSXBatchReport(_percentTagName, _batchID, _reportType, _fileID, _curReportFolder)
{
    $.post("../phplibs/createReport/swtXLSXBatchReport.php", 
    {
        batchID:         _batchID,
        fileID:          _fileID,
        reportType:      _reportType,
        curReportFolder: _curReportFolder
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            $("#" + _percentTagName).html("generating Graphs, please wait...");
            swtXLSX2XLSM(_percentTagName, _batchID, _reportType, 0, _curReportFolder);
        }
        else if (json.errorCode == "2")
        {
            // generate all history reports
            $("#" + _percentTagName).html(json.errorMsg);
            swtXLSXBatchReport(_percentTagName, _batchID, _reportType, _fileID + 1, _curReportFolder);
        }
        else
        {
            alert("error occurs, please restart report generation again!");
            $("#" + _percentTagName).html(json.errorMsg);
            location.reload(true);
        }
    });
}

function swtXLSX2XLSM(_percentTagName, _batchID, _reportType, _fileID, _curReportFolder)
{
    $.post("../phplibs/createReport/swtXLSX2XLSM.php", 
    {
        batchID:         _batchID,
        fileID:          _fileID,
        reportType:      _reportType,
        curReportFolder: _curReportFolder
    }, 
    function(data,status) 
    {
        //alert(data);
		console.log("<<< xlsm");
		console.log(data);
		console.log("<<< xlsm");
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            if (_reportType == 0)
            {
                // only generating latest report
                //alert(json.reportFolder);
                alert("generate success");
                location.reload(true);
            }
            else
            {
                // generate all history reports
                $("#" + _percentTagName).html("zipping XLSM to ZIP, please wait...");
                swtZipBatchReport(_percentTagName, _batchID, _reportType, 0);
            }
        }
        else if (json.errorCode == "2")
        {
            // generate all history reports
            $("#" + _percentTagName).html(json.errorMsg);
            swtXLSX2XLSM(_percentTagName, _batchID, _reportType, _fileID + 1, _curReportFolder);
        }
    });
}

function swtGenAllHistoryReports()
{
    var t1 = $("#batchesToGenerate").text();
    var batchIDList = [];

    if (t1 == "...")
    {
        // start generating
        $.post("../phplibs/getInfo/swtGetBatchIDList.php", 
        {
            batchGroup:          1,
            batchState:          1,
            batchNum:            -1
        }, 
        function(data,status) 
        {
            //alert(data);
            var json = eval("(" + data + ")");

            console.log(json.errorMsg);
            if (json.errorCode == "1")
            {
                //alert(json.errorMsg);

                var t1 = swtImplode(json.batchIDList, ",");
                $("#batchesToGenerate").text(t1);
                swtGenAllHistoryReports();
            }
        });
    }
    else if (t1.length > 0)
    {
        batchIDList = t1.split(",");
        
        if (batchIDList.length > 0)
        {
            // generating next batch
            var batchID = parseInt(batchIDList.shift());
            
            console.log("333: " + batchID);
            var t1 = swtImplode(batchIDList, ",");
            $("#batchesToGenerate").text(t1);
            swtGenerateRoutineReport('finishPercentBar', 'reportList', 1, batchID);
        }
    }
    else
    {
        alert ("all reports finished");
    }

}

function swtDelBatches(_startTag, _endTag)
{
    var t1 = $("#" + _startTag).val();
    var t2 = $("#" + _endTag).val();
    
    if (t1.length == 0)
    {
        alert("please input at least the first batch ID");
        return;
    }
    
    if (t2.length == 0)
    {
        t2 = t1;
    }
    
    if (parseInt(t1) > parseInt(t2))
    {
        alert("input batch ID area invalid");
        return;
    }
    
    $.post("../phplibs/generalLibs/delBatch.php", 
    {
        startBatchID:        t1,
        endBatchID:          t2
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

        }
    });
}

function swtDelBatchesDirect(_startBatchID, _endBatchID)
{
    if (confirm("sure to delete?") == false)
    {
        return;
    }
    if (parseInt(_startBatchID) > parseInt(_endBatchID))
    {
        alert("input batch ID area invalid");
        return;
    }
    
    $.post("../phplibs/generalLibs/delBatch.php", 
    {
        startBatchID:        _startBatchID,
        endBatchID:          _endBatchID
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        alert(json.errorMsg);
        location.reload(true);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

        }
    });
}

function swtSetBatchLogPath(_batchTag, _pathTag)
{
    var t1 = $("#" + _batchTag).val();
    var t2 = $("#" + _pathTag).val();
    
    if (t1.length == 0)
    {
        alert("please input batch ID");
        return;
    }
    
    if (t2.length == 0)
    {
        alert("please input result files path");
        return;
    }
    
    $.post("../phplibs/generalLibs/setBatchLogPath.php", 
    {
        batchID:         t1,
        pathName:        t2
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

        }
    });
}

function swtGetCardChoiceCode(_divTag, _reportTag)
{
    
    $.post("../phplibs/getInfo/swtGetBatchMachinesInfo.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
		console.log("<<<");
		console.log(data);
		console.log("<<<");
        var json = eval("(" + data + ")");

        
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            swtLastBatchMachineIDList = json.machineIDList;
            var t4 = "";
            var t1 = "<table>\n";
            for (var i = 0; i < json.machineIDList.length; i++)
            {
                t1 += "<tr>\n";
                t1 += "<td>\n";
                t1 += "-&nbsp&nbsp";
                t1 += json.cardNameList[i] + " - " + json.systemNameList[i];
                t1 += "&nbsp&nbsp";
                t1 += "</td>\n";
                t1 += "<td>\n";
                var t3 = "selMachineID" + json.machineIDList[i];
                var t5 = "checkMachineID" + json.machineIDList[i];
                var t2 = "<select id=\"" + t3 + "\" name=\"" + t3 + "\">\n" +
                         "<option value=\"-1\">no comparison</option>\n";
                t4 += json.machineIDList[i];
                if (i < (json.machineIDList.length - 1))
                {
                    t4 += ",";
                }
                for (var j = 0; j < json.machineIDList.length; j++)
                {
                    if (i == j)
                    {
                        continue;
                    }
                    t2 += "<option value=\"" + json.machineIDList[j] + "\">" + json.cardNameList[j] + " - " + json.systemNameList[j] + "</option>";
                }
                t2 += "</select>\n";
                
                t1 += t2;
                
                t1 += "</td>\n";
                
                t1 += "<td>\n";
                t1 += "&nbsp&nbsp";
                t1 += "<input id=\"" + t5 + "\" name=\"" + t5 + "\" type=\"checkbox\" checked=\"checked\">\n";
                t1 += "</td>\n";
                
                t1 += "<tr>\n";
            }
            console.log("machines: " + t4);
            
            t1 += "</table>\n";
            t1 += "<input type=\"hidden\" id=\"valMachineIDList\" name=\"valMachineIDList\" value=\"" + t4 + "\" />\n";
            $("#" + _divTag).html(t1);
            
            var reportListCode = "";
            for (var i = 0; i < json.reportFileNameList.length; i++)
            {
                var tmpList = json.reportFileNameList[i].split("/");
                var t1 = "";
                if (tmpList.length > 0)
                {
                    t1 = tmpList[tmpList.length - 1];
                }
                
                reportListCode += "<p><a href=\"" + json.reportFileNameList[i] + "\">" +
                                  t1 +
                                  "</a></p>\n";
            }
            $("#" + _reportTag).html(reportListCode);
        }
    });
}

function swtGetShortBatchIDList(_comboTag)
{
    $.post("../phplibs/getInfo/swtGetBatchIDList.php", 
    {
        batchGroup:          1,
        batchState:          1,
        batchNum:            10
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var t1 = "<select id=\"selBatchID\" name=\"selBatchID\">";
            t1 += "<option value=\"-1\" checked=\"checked\">create new batch</option>";
            
            for (var i = 0; i < json.batchIDList.length; i++)
            {
                t1 += "<option value=\"" + json.batchIDList[i] + "\">" + json.batchIDList[i] + "</option>";
            }
            
            t1 += "</select>";
            
            $("#" + _comboTag).html(t1);
        }
    });
}

function swtGetSkynetResultList(_listTag, _dataTag)
{
    $.post("../phplibs/getInfo/swtGetSkynetResults.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var t1 = "<table border=\"0\" cellspacing=\"0\">";
            t1 += "<tr>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "</tr>";
            
            var t2 = "";
            
            var resultNum = 0;
            
            for (var i = 0; i < json.resultsTree.length; i++)
            {
                t1 += "<tr style=\"background-color: #a0a0a0; text-align: center;\">";
                t1 += "<td style=\"width: 80px;\">";
                t1 += json.resultsDateList[i];
                t1 += "</td>";
                t1 += "<td>&nbsp&nbsp&nbsp";
                t1 += "</td>";
                t1 += "<td >";
                t1 += "</td>";
                t1 += "</tr>";
                
                var t3 = "";
                if (i == (json.resultsTree.length - 1))
                {
                    t3 = "checked=\"checked\"";
                }
                
                var dataNum = json.resultsDataNum;
                var n1 = json.resultsTree[i].length / dataNum;
                
                for (var j = 0; j < n1; j++)
                {
                    t1 += "<tr >";
                    t1 += "<td>";
                    t1 += "<input id=\"result" + resultNum + 
                          "\" name=\"result" + resultNum + 
                          "\" type=\"checkbox\" " + t3 + " >";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td style=\"background-color: #e0e0e0;\">";
                    t1 += "TIME: &nbsp&nbsp" + json.resultsTree[i][dataNum * j + 3];
                    t1 += "</td>";
                    t1 += "</tr>";
                    
                    t1 += "<tr>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td style=\"width: 800px;\">";
                    t1 += "ASIC:      &nbsp&nbsp" + json.resultsTree[i][dataNum * j + 0] + "<br />";
                    t1 += "CPU:       &nbsp&nbsp" + json.resultsTree[i][dataNum * j + 1] + "<br />";
                    t1 += "SYSTEM:    &nbsp&nbsp" + json.resultsTree[i][dataNum * j + 2] + "<br />";
                    t1 += "TEST LIST: &nbsp&nbsp" + json.resultsTree[i][dataNum * j + 4] + "<br />";
                    t1 += "</td>";
                    t1 += "</tr>";
                    
                    t2 += "<input type=\"hidden\" id=\"resultDate" + resultNum + 
                          "\" name=\"resultDate" + resultNum + 
                          "\" value=\"" + json.resultsDateList[i] + "\" />";
                          
                    t2 += "<input type=\"hidden\" id=\"resultFolder" + resultNum + 
                          "\" name=\"resultFolder" + resultNum + 
                          "\" value=\"" + json.resultsTree[i][dataNum * j + 5] + "\" />";
                    
                    resultNum++;
                }
            }
            
            t1 += "</table>";
            
            $("#" + _listTag).html(t1);
            $("#" + _dataTag).html(t2);
            $("#resultNum").val(resultNum);
        }
    });
}

function swtSelectSkynetResults(_inputTagName,
                                _percentTagName,
                                _usernameTagName,
                                _passwordTagName,
                                _targetTagName)
{
    var resultNum = parseInt($("#resultNum").val());
    if (resultNum == 0)
    {
        alert("no results found");
        return;
    }
    
    var dateList = [];
    var folderList = [];
    
    for (var i = 0; i < resultNum; i++)
    {
        var b1 = $("#result" + i).is(":checked");
        
        if (b1)
        {
            var tmpDate = $("#resultDate" + i).val();
            var tmpFolder = $("#resultFolder" + i).val();
            
            dateList.push(tmpDate);
            folderList.push(tmpFolder);
        }
    }
    
    if (folderList.length == 0)
    {
        alert("please select results to import");
        return;
    }
    
    var dx11CL = $("#dx11CL").val();
    var dx12CL = $("#dx12CL").val();
    var vulkanCL = $("#vulkanCL").val();
    
    if ((dx11CL.length == 0) &&
        (dx12CL.length == 0) &&
        (vulkanCL.length == 0))
    {
        alert("please insert changelist");
        return;
    }
    
    var t1 = swtImplode(dateList, ",");
    var t2 = swtImplode(folderList, ",");
    
    $.post("../phplibs/server/swtReorganizeSkynetResult.php", 
    {
        resultDateList:   t1,
        resultFolderList: t2,
        dx11CL:           dx11CL,
        dx12CL:           dx12CL,
        vulkanCL:         vulkanCL
    }, 
    function(data,status) 
    {
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var batchID = $("#" + _targetTagName).val();
            
            swtDoSubmitTestResults(_inputTagName,
                                   _percentTagName,
                                   json.logFolderName,
                                   0,
                                   0,
                                   0,
                                   0,
                                   0,
                                   0,
                                   batchID);
        }
    });
}