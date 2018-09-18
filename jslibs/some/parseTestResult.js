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
    
    //var reportGroupName = $("input[name='reportGroup']:checked").val();
    var reportGroupName = $("#reportGroup").val();
    
    var reportGroupNameHead = reportGroupName.substr(0, 10);
    var reportGroup = 1;
    if (reportGroupName == "routineReport")
    {
        reportGroup = 1;
    }
    else if (reportGroupName == "tempReport")
    {
        reportGroup = 2;
    }
    else if (reportGroupNameHead == "reportSlot")
    {
        var t1 = reportGroupName.substr(10, 2);
        var n1 = parseInt(t1, 10);
        reportGroup = 100 + n1;
    }
    
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
                         "",
                         reportGroup);
}

function swtSubmitTestResultsMannualOutUser(_inputTagName,
                                      _percentTagName,
                                      _usernameTagName,
                                      _passwordTagName,
                                      _targetTagName)
{
    // for outside users
    var reportGroup = 0;
    
    $("#" + _percentTagName).html("copying files: 0% (importing is started...)");
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
                         "",
                         reportGroup);
}

function swtSubmitTestResultsMannualShaderBench(_inputTagName,
                                      _percentTagName,
                                      _usernameTagName,
                                      _passwordTagName,
                                      _targetTagName)
{
    //var reportGroup = 3;
    
    var reportGroupName = $("#reportGroup").val();
    
    var reportGroupNameHead = reportGroupName.substr(0, 10);
    var reportGroup = 3;
    if (reportGroupName == "shaderReport")
    {
        reportGroup = 3;
    }
    else if (reportGroupNameHead == "shaderSlot")
    {
        var t1 = reportGroupName.substr(10, 2);
        var n1 = parseInt(t1, 10);
        reportGroup = 200 + n1;
    }
    
    //alert("reportGroup: " + reportGroup);
    //console.log("reportGroup: " + reportGroup);
    
    $("#" + _percentTagName).html("copying files: 0%");
    var t1 = $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        alert("please fill in folder name");
        return;
    }
    var t2 = $("#" + _usernameTagName).val();
    var t3 = $("#" + _passwordTagName).val();
    //var t4 = $("#" + _targetTagName).val();
    $.cookie('benchMaxUsername', t2);
    $.cookie('benchMaxPassword', t3);
    swtDoCopyResultFilesShaderBench(_inputTagName,
                         _percentTagName,
                         -1, // batch ID
                         t1,
                         t2,
                         t3,
                         "",
                         0,
                         "",
                         "",
                         reportGroup);
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
                              _parentFolderOnly,
                              _reportGroup)
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
                //$("#" + _inputTagName).val("");
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
                                       _batchID,
                                       _reportGroup);
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
                                     json.parentFolderOnly,
                                     _reportGroup);
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

function swtDoCopyResultFilesShaderBench(_inputTagName,
                              _percentTagName,
                              _batchID,
                              _logFolderName,
                              _username,
                              _password,
                              _allFileListString,
                              _fileID,
                              _parentFolder,
                              _parentFolderOnly,
                              _reportGroup)
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("copying files: 100%");
                // copying result files finished,
                // start to feed data into database
                swtDoSubmitTestResultsShaderBench(_inputTagName,
                                       _percentTagName,
                                       json.parentFolderOnly,
                                       0,
                                       0,
                                       0,
                                       0,
                                       0,
                                       0,
                                       _batchID,
                                       _reportGroup);
            }
            else
            {
                swtDoCopyResultFilesShaderBench(_inputTagName,
                                     _percentTagName,
                                     _batchID,
                                     _logFolderName,
                                     _username,
                                     _password,
                                     json.allFileListString,
                                     json.fileID,
                                     json.parentFolder,
                                     json.parentFolderOnly,
                                     _reportGroup);
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
                                _batchID,
                                _reportGroup)
{
    //$.post("../phplibs/importResult/swtParseBenchLogManualVer3.php",
    $.post("../phplibs/importResult/swtParseBenchLogManualSkipNoise.php",
    {
        logFolderName:    _logFolderName,
        nextResultFileID: _nextResultFileID,
        nextLineID:       _nextLineID,
        curFileLineNum:   _curFileLineNum,
        resultFileNum:    _resultFileNum,
        curTestID:        _curTestID,
        nextSubTestID:    _nextSubTestID,
        batchID:          _batchID,
        reportGroup:      _reportGroup
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("feeding database: 100%");
                //alert("import success");
                //swtGotoPage('./sepStartPage.php');
                //location.reload(true);
                
                swtCalcNoiseAverage(_inputTagName,
                                    _percentTagName,
                                    json.batchID,
                                    0,
                                    0,
                                    0,
                                    0,
                                    0
                                    );
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
                                       json.batchID,
                                       _reportGroup);
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

function swtDoSubmitTestResultsShaderBench(_inputTagName,
                                _percentTagName,
                                _logFolderName,
                                _nextResultFileID,
                                _nextLineID,
                                _curFileLineNum,
                                _resultFileNum,
                                _curTestID,
                                _nextSubTestID,
                                _batchID,
                                _reportGroup)
{
    //$.post("../phplibs/importResult/swtParseBenchLogManual.php",
    $.post("../phplibs/importResult/swtParseBenchLogManualShaderBench.php",
    {
        logFolderName:    _logFolderName,
        nextResultFileID: _nextResultFileID,
        nextLineID:       _nextLineID,
        curFileLineNum:   _curFileLineNum,
        resultFileNum:    _resultFileNum,
        curTestID:        _curTestID,
        nextSubTestID:    _nextSubTestID,
        batchID:          _batchID,
        reportGroup:      _reportGroup
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("feeding database: 100%");
                //alert("import success");
                //swtGotoPage('./sepStartPage.php');
                //location.reload(true);
                
                swtCalcNoiseAverageShaderBench(_inputTagName,
                                    _percentTagName,
                                    json.batchID,
                                    0,
                                    0,
                                    0,
                                    0,
                                    0
                                    );
            }
            else
            {
                swtDoSubmitTestResultsShaderBench(_inputTagName,
                                       _percentTagName,
                                       _logFolderName,
                                       json.nextResultFileID,
                                       json.nextLineID,
                                       json.curFileLineNum,
                                       json.resultFileNum,
                                       json.curTestID,
                                       json.nextSubTestID,
                                       json.batchID,
                                       _reportGroup);
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

function swtCalcNoiseAverage(_inputTagName,
                             _percentTagName,
                             _batchID,
                             _resultPos,
                             _testPos,
                             _testCasePos,
                             _curTestCaseNum,
                             _curTestNoiseNum
                             )
{
    //$.post("../phplibs/importResult/swtCalcNoiseAverage.php",
    $.post("../phplibs/importResult/swtCalcNoiseAverageskipNoise.php",
    {
        batchID:     _batchID,
        resultPos:   _resultPos,
        testPos:     _testPos,
        testCasePos: _testCasePos,
        curTestCaseNum:  _curTestCaseNum,
        curTestNoiseNum: _curTestNoiseNum
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("calc average: 100%");
                alert("import success");
                //swtGotoPage('./sepStartPage.php');
                //location.reload(true);
            }
            else
            {
                swtCalcNoiseAverage(_inputTagName,
                                    _percentTagName,
                                    _batchID,
                                    json.resultPos,
                                    json.testPos,
                                    json.testCasePos,
                                    json.curTestCaseNum,
                                    json.curTestNoiseNum
                                    );
                                    
                var tmpResultPos = parseFloat(json.resultPos);
                var tmpResultNum = parseFloat(json.resultNum);
                var tmpTestPos = parseFloat(json.testPos);
                var tmpTestNum = parseFloat(json.testNum);
                var f1 = tmpResultPos / tmpResultNum;
                var f2 = tmpTestPos / tmpTestNum;
                var f3 = f2 / tmpResultNum;
                $("#" + _percentTagName).html("calc average: " + ((f1 + f3) * 100.0 ).toFixed(1) + "%");
            }
        }
        else
        {
            console.log(json.errorMsg);
        }
    });
}

function swtCalcNoiseAverageShaderBench(_inputTagName,
                             _percentTagName,
                             _batchID,
                             _resultPos,
                             _testPos,
                             _testCasePos,
                             _curTestCaseNum,
                             _curTestNoiseNum
                             )
{
    $.post("../phplibs/importResult/swtCalcNoiseAverageShaderBench.php",
    {
        batchID:     _batchID,
        resultPos:   _resultPos,
        testPos:     _testPos,
        testCasePos: _testCasePos,
        curTestCaseNum:  _curTestCaseNum,
        curTestNoiseNum: _curTestNoiseNum
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("calc average: 100%");
                alert("import success");
                //swtGotoPage('./sepStartPage.php');
                //location.reload(true);
            }
            else
            {
                swtCalcNoiseAverageShaderBench(_inputTagName,
                                    _percentTagName,
                                    _batchID,
                                    json.resultPos,
                                    json.testPos,
                                    json.testCasePos,
                                    json.curTestCaseNum,
                                    json.curTestNoiseNum
                                    );
                                    
                var tmpResultPos = parseFloat(json.resultPos);
                var tmpResultNum = parseFloat(json.resultNum);
                var tmpTestPos = parseFloat(json.testPos);
                var tmpTestNum = parseFloat(json.testNum);
                var f1 = tmpResultPos / tmpResultNum;
                var f2 = tmpTestPos / tmpTestNum;
                var f3 = f2 / tmpResultNum;
                $("#" + _percentTagName).html("calc average: " + ((f1 + f3) * 100.0 ).toFixed(1) + "%");
            }
        }
        else
        {
            console.log(json.errorMsg);
        }
    });
}

function swtSubmitTestResultsMannualOutUserVer2(_inputTagName,
                                                _percentTagName,
                                                _usernameTagName,
                                                _passwordTagName,
                                                _targetTagName)
{
    // for outside users
    var reportGroup = 0;
    
    $("#" + _percentTagName).html("copying files: 0% (importing is started...)");
    var t1 = "" + $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        $("#" + _percentTagName).html("0%");
        alert("please fill in folder name");
        return;
    }
    var t2 = ""; //$("#" + _usernameTagName).val();
    var t3 = ""; //$("#" + _passwordTagName).val();
    var t4 = -1; //$("#" + _targetTagName).val();
    $.cookie('benchMaxUsername', t2);
    $.cookie('benchMaxPassword', t3);
    swtDoCopyResultFilesVer2(_inputTagName,
                             _percentTagName,
                             t4, // batch ID
                             t1,
                             t2,
                             t3,
                             "",
                             0,
                             "",
                             "",
                             reportGroup);
}

function swtDoCopyResultFilesVer2(_inputTagName,
                                  _percentTagName,
                                  _batchID,
                                  _logFolderName,
                                  _username,
                                  _password,
                                  _allFileListString,
                                  _fileID,
                                  _parentFolder,
                                  _parentFolderOnly,
                                  _reportGroup)
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("copying files: 100%");
                // copying result files finished,
                // start to feed data into database
                swtDoSubmitTestResultsVer2(_inputTagName,
                                           _percentTagName,
                                           json.parentFolderOnly,
                                           0,
                                           0,
                                           0,
                                           0,
                                           0,
                                           0,
                                           _batchID,
                                           _reportGroup,
                                           0,
                                           0);
            }
            else
            {
                swtDoCopyResultFilesVer2(_inputTagName,
                                         _percentTagName,
                                         _batchID,
                                         _logFolderName,
                                         _username,
                                         _password,
                                         json.allFileListString,
                                         json.fileID,
                                         json.parentFolder,
                                         json.parentFolderOnly,
                                         _reportGroup);
                if (json.fileID <= json.fileNum)
                {
                    $("#" + _percentTagName).html("copying files: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            $("#" + _percentTagName).html("0%");
            alert(json.errorMsg);
            console.log("check out 001");
        }
    });
}

function swtSubmitTestResultsMannualOutUserVer3(_inputTagName,
                                                _percentTagName,
                                                _usernameTagName,
                                                _passwordTagName,
                                                _targetTagName)
{
    // for outside users
    var reportGroup = 0;
    
    $("#" + _percentTagName).html("copying files: 0% (importing is started...)");
    var t1 = "" + $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        $("#" + _percentTagName).html("0%");
        alert("please fill in folder name");
        return;
    }
    var t2 = ""; //$("#" + _usernameTagName).val();
    var t3 = ""; //$("#" + _passwordTagName).val();
    var t4 = -1; //$("#" + _targetTagName).val();
    $.cookie('benchMaxUsername', t2);
    $.cookie('benchMaxPassword', t3);
                             
    swtDoCopyResultFilesVer3(_inputTagName,
                             _percentTagName,
                             t4, // batch ID
                             t1,
                             t2,
                             t3,
                             "",
                             0,
                             "",
                             "",
                             reportGroup);
}

function swtDoCopyResultFilesVer3(_inputTagName,
                                  _percentTagName,
                                  _batchID,
                                  _logFolderName,
                                  _username,
                                  _password,
                                  _allFileListString,
                                  _fileID,
                                  _parentFolder,
                                  _parentFolderOnly,
                                  _reportGroup)
{
    $.post("../phplibs/getInfo/swtGetFolderAllFileNamesVer3.php", 
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("copying files: 100%");
                // copying result files finished,
                // start to feed data into database
                
                swtGenerateRoutineReportVer3('finishPercentBar', 'reportList', 0, -1, -1, 10);
            }
            else
            {
                swtDoCopyResultFilesVer3(_inputTagName,
                                         _percentTagName,
                                         _batchID,
                                         _logFolderName,
                                         _username,
                                         _password,
                                         json.allFileListString,
                                         json.fileID,
                                         json.parentFolder,
                                         json.parentFolderOnly,
                                         _reportGroup);
                if (json.fileID <= json.fileNum)
                {
                    $("#" + _percentTagName).html("copying files: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            $("#" + _percentTagName).html("0%");
            alert(json.errorMsg);
            console.log("check out 001");
        }
    });
}

function swtSubmitTestResultsMannualOutUserVer4(_inputTagName,
                                                _percentTagName,
                                                _usernameTagName,
                                                _passwordTagName,
                                                _targetTagName)
{
    // for outside users
    var reportGroup = 0;
    
    $("#" + _percentTagName).html("copying files: 0% (importing is started...)");
    var t1 = "" + $("#" + _inputTagName).val();
    if (t1.length == 0)
    {
        $("#" + _percentTagName).html("0%");
        alert("please fill in folder name");
        return;
    }
    var t2 = ""; //$("#" + _usernameTagName).val();
    var t3 = ""; //$("#" + _passwordTagName).val();
    var t4 = -1; //$("#" + _targetTagName).val();
    $.cookie('benchMaxUsername', t2);
    $.cookie('benchMaxPassword', t3);
                             
    swtDoCopyResultFilesVer4(_inputTagName,
                             _percentTagName,
                             t4, // batch ID
                             t1,
                             t2,
                             t3,
                             "",
                             0,
                             "",
                             "",
                             reportGroup);
}

function swtDoCopyResultFilesVer4(_inputTagName,
                                  _percentTagName,
                                  _batchID,
                                  _logFolderName,
                                  _username,
                                  _password,
                                  _allFileListString,
                                  _fileID,
                                  _parentFolder,
                                  _parentFolderOnly,
                                  _reportGroup)
{
    $.post("../phplibs/getInfo/swtGetFolderAllFileNamesVer3.php", 
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("copying files: 100%");
                // copying result files finished,
                // start to feed data into database
                
                swtGenerateRoutineReportVer4('finishPercentBar', 'reportList', 0, -1, -1, 10);
            }
            else
            {
                swtDoCopyResultFilesVer4(_inputTagName,
                                         _percentTagName,
                                         _batchID,
                                         _logFolderName,
                                         _username,
                                         _password,
                                         json.allFileListString,
                                         json.fileID,
                                         json.parentFolder,
                                         json.parentFolderOnly,
                                         _reportGroup);
                if (json.fileID <= json.fileNum)
                {
                    $("#" + _percentTagName).html("copying files: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            $("#" + _percentTagName).html("0%");
            alert(json.errorMsg);
            console.log("check out 001");
        }
    });
}

function swtDoCopyResultFilesVer3a(_inputTagName,
                                   _percentTagName,
                                   _batchID,
                                   _fileID,
                                   _parentFolder,
                                   _parentFolderOnly,
                                   _reportGroup)
{
    var fileNum = $('#' + _inputTagName).get(0).files.length;
    
    if (fileNum == 0)
    {
        alert("please choose folder to upload.");
        return;
    }
    
    if (_fileID < fileNum)
    {
        var pic_data = new FormData();
        
        pic_data.append("file" + _fileID,   $('#' + _inputTagName).get(0).files[_fileID]);
        pic_data.append("srcFilePath",      $('#' + _inputTagName).get(0).files[_fileID].webkitRelativePath);
        pic_data.append("batchID",          _batchID);
        pic_data.append("parentFolder",     _parentFolder);
        pic_data.append("parentFolderOnly", _parentFolderOnly);
        pic_data.append("fileID",           _fileID);
        pic_data.append("fileNum",          fileNum);
        
        console.log("files:--");
        console.info($('#' + _inputTagName).get(0).files);
        
        $.ajax({
            url:"../phplibs/getInfo/swtGetFolderAllFileNamesVer3a.php",  
            type: "POST",
            //method: "POST",
            cache: false,
            enctype: "multipart/form-data",
            processData:false,  
            contentType:false,  
            data:pic_data,
            //async: false,
            success:function(data)
            {  
                console.log(data);
                var json = eval("(" + data + ")");
                
                if (json.errorCode == "1")
                {
                    //alert(json.errorMsg);
                    if (json.copyFileFinished == "1")
                    {
                        // uploading complete
                        //$("#" + _percentTagName).html("copying files: 100%");
                        $("#" + _percentTagName).html("copying files finished.");
                        $("#loadProgressBar").progressbar({
                                value: 100
                            });
                        console.log("004-" + json.batchID);
                        //console.info();
                        swtGetFolderMachineNameListOutUser(json.parentFolder,
                                                           json.batchID,
                                                           "crossAPI",
                                                           "crossASIC",
                                                           "crossBuild");
                    }
                    else
                    {
                        if (_fileID <= fileNum)
                        {
                            console.log("xxxppp");
                            console.log(_fileID);
                            console.log(fileNum);
                            var f1 = (_fileID * 1.0 / fileNum) * 100.0;
                            //$("#" + _percentTagName).html("copying files: " + f1.toFixed(1) + "%");
                            $("#" + _percentTagName).html("copying files...");
                            
                            $("#loadProgressBar").progressbar({
                                value: Math.floor(f1)
                            });
                        }
                        
                        swtDoCopyResultFilesVer3a(_inputTagName,
                                                  _percentTagName,
                                                  json.batchID,
                                                  json.fileID,
                                                  json.parentFolder,
                                                  json.parentFolderOnly,
                                                  _reportGroup);
                                                  
                        //if (json.fileID <= fileNum)
                        {
                            //$("#" + _percentTagName).html("copying files: " + ((json.fileID / fileNum) * 100.0 ).toFixed(1) + "%");
                        }
                    }
                }
                else if (json.errorCode == "0")
                {
                    //$("#" + _percentTagName).html("0%");
                    $("#" + _percentTagName).html("");
                    alert(json.errorMsg);
                    console.log("check out 001");
                }

            },
            error:function(data)
            {  
                alert(data);  
            }  
        });
        
        
        //if (json.fileID <= fileNum)

    }
}

function swtDoSubmitTestResultsVer2(_inputTagName,
                                    _percentTagName,
                                    _logFolderName,
                                    _nextResultFileID,
                                    _nextLineID,
                                    _curFileLineNum,
                                    _resultFileNum,
                                    _curTestID,
                                    _nextSubTestID,
                                    _batchID,
                                    _reportGroup,
                                    _curFileOffset1,
                                    _curFileOffset2)
{   
    $.post("../phplibs/importResult/swtParseBenchLogManualVer2.php", 
    {
        logFolderName:    _logFolderName,
        nextResultFileID: _nextResultFileID,
        nextLineID:       _nextLineID,
        curFileLineNum:   _curFileLineNum,
        resultFileNum:    _resultFileNum,
        curTestID:        _curTestID,
        nextSubTestID:    _nextSubTestID,
        batchID:          _batchID,
        reportGroup:      _reportGroup,
        curFileOffset1:   _curFileOffset1,
        curFileOffset2:   _curFileOffset2
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
                //$("#" + _inputTagName).val("");
                $("#" + _percentTagName).html("feeding database: 100%");
                //alert("import success");
                
                swtGenerateRoutineReportVer2('finishPercentBar', 'reportList', 0, -1, -1, 10);
                //swtGotoPage('./sepStartPage.php');
                //location.reload(true);
            }
            else
            {
                swtDoSubmitTestResultsVer2(_inputTagName,
                                           _percentTagName,
                                           _logFolderName,
                                           json.nextResultFileID,
                                           json.nextLineID,
                                           json.curFileLineNum,
                                           json.resultFileNum,
                                           json.curTestID,
                                           json.nextSubTestID,
                                           json.batchID,
                                           _reportGroup,
                                           json.curFileOffset1,
                                           json.curFileOffset2);
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
        else
        {
            alert(json.errorMsg);
        }
    });
}

// _crossType, 0 for cross API, 1 for cross ASIC, SYS, 2 for cross Builds
// _reportType, 0 for routine report, 1 for all reports
function swtGenerateRoutineReport(_percentTagName, _reportListTag, _reportType, _batchID, _crossType)
{
    $("#" + _percentTagName).html("0% (generating is started...)");
    
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
                // if cross asic, pair are machineID, machineID
                // if cross build, pair are machineID, batch
            }
            else if ((_crossType == 1) || 
                     (_crossType == 2))
            {
                // cross ASIC, SYS
                // cross build
                continue;
            }
            checkedMachineIDList.push(parseInt(machineIDList[i]));
        }
        t2 = swtImplode(machineIDPair, ",");
        t3 = swtImplode(checkedMachineIDList, ",");
    }

    console.log("---: " + t2);

    swtDoGenerateFlatData(_percentTagName,
                          _reportListTag,
                          _batchID,
                          _reportType,
                          _crossType,
                          -1,
                          t2,
                          t3
                          );
}

// _crossType, 0 for cross API, 1 for cross ASIC, SYS, 2 for cross Builds, 
//             10 for version 2 cross API, 11 for version 2 cross ASIC / build (cross machines)
// _reportType, 0 for routine report, 1 for all reports
function swtGenerateRoutineReportVer2(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, _crossType)
{
    var t1 = "" + $("#inputFolderName").val();
    if (t1.length == 0)
    {
        //$("#" + _percentTagName).html("0%");
        //alert("please fill in folder name");
        //return;
    }
    
    $("#" + _percentTagName).html("0% (generating is started...)");
    
    $("#" + _reportListTag).html("");

    var t1 = "" + $("#valMachineIDList").val();
    //console.log("valMachineIDList: " + t1);
    var machineIDList = [];
    if (t1.length > 0)
    {
        machineIDList = t1.split(",");
    }
    t1 = "" + $("#valFolderMachineNameList").val();
    var folderMachineNameList = [];
    if (t1.length > 0)
    {
        folderMachineNameList = t1.split(",");
    }
    
    var machineIDPair = [];
    var checkedMachineIDList = [];
    var checkedMachineIDListCopy = [];
    var t2 = "";
    var t3 = "";
    if (_crossType == 10)
    {
        // cross API
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
            }
        }
        console.log("tmp---002B:" + checkedMachineIDList.length);
        if (checkedMachineIDList.length == 0)
        {
            // if cross api no machine selected go to next step
            // goto cross machine
            console.log("tmp---002A:" + checkedMachineIDList.length);
            swtGenerateRoutineReportVer2(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, 11);
            return;
        }
        console.log("tmp---002C:" + checkedMachineIDList.length);
    }
    else if (_crossType == 11)
    {
        // cross machines
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            t1 = $("#selMachineID" + machineIDList[i]).val();

            if ((t1 == null) ||
                (t1 == undefined) ||
                (t1.length == 0))
            {
                continue;
            }
            console.log("tmp---001:" + t1);
            
            var n1 = parseInt(t1);
            if (n1 != -1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
                machineIDPair.push(parseInt(machineIDList[i]));
                machineIDPair.push(n1);
                // if cross asic, pair are machineID, machineID
            }
        }
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDListCopy.push(parseInt(machineIDList[i]));
            }
        }
        if (machineIDPair.length == 0)
        {
            // if no machine selected go back
            $("#" + _percentTagName).html("0%");
            
            if (checkedMachineIDListCopy.length == 0)
            {
                console.log("tmp---008:" + checkedMachineIDList.length);
                alert("please select report to generate");
                return;
            }
            alert("generate success");
            location.reload(true);
            return;
        }
        console.log("tmp---002D:" + checkedMachineIDList.length);
    }
    t2 = swtImplode(machineIDPair, ",");
    t3 = swtImplode(checkedMachineIDList, ",");

    console.log("tmp---: " + t2);
    console.log("tmp---: " + t3);
    console.log("tmp---08:" + _crossType);

    swtDoGenerateFlatData(_percentTagName,
                          _reportListTag,
                          _batchID,
                          _reportType,
                          _crossType,
                          _curReportFolder,
                          t2,
                          t3
                          );
}

// _crossType, 0 for cross API, 1 for cross ASIC, SYS, 2 for cross Builds
// _reportType, 0 for routine report, 1 for all reports
function swtGenerateRoutineReportShaderBench(_percentTagName, _reportListTag, _reportType, _batchID, _crossType)
{
    $("#" + _percentTagName).html("0% (generating is started...)");
    
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
                // if cross asic, pair are machineID, machineID
                // if cross build, pair are machineID, batch
            }
            else if ((_crossType == 1) || 
                     (_crossType == 2))
            {
                // cross ASIC, SYS
                // cross build
                continue;
            }
            checkedMachineIDList.push(parseInt(machineIDList[i]));
        }
        t2 = swtImplode(machineIDPair, ",");
        t3 = swtImplode(checkedMachineIDList, ",");
    }

    console.log("---: " + t2);

    swtDoGenerateFlatDataShaderBench(_percentTagName,
                          _reportListTag,
                          _batchID,
                          _reportType,
                          _crossType,
                          -1,
                          t2,
                          t3
                          );
}

function swtDoGenerateFlatData(_percentTagName,
                               _reportListTag,
                               _batchID,
                               _reportType,
                               _crossType,
                               _curReportFolder,
                               _machineIDPair,
                               _machineIDList
                               )
{
    var tmpMachineIDPair = _machineIDPair;
    if (_crossType == 2)
    {
        // cross build
        tmpMachineIDPair = "";
    }
    
    console.log("tmp---07:" + _crossType);
    
    //$.post("../phplibs/createReport/swtGenTempFlatData.php", 
    $.post("../phplibs/createReport/swtGenTempFlatDataNoise.php", 
    {
        batchID:               _batchID,
        reportType:            _reportType,
        crossType:             _crossType,
        curReportFolder:       _curReportFolder,
        machineIDPair:         tmpMachineIDPair, //_machineIDPair,
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
                                           _reportListTag,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           1,
                                           _reportType,
                                           _crossType,
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
                                      _reportListTag,
                                      _batchID,
                                      _reportType,
                                      _crossType,
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
            console.log("tmp---08:" + json.errorMsg);
            alert(json.errorMsg);
        }
    });
}

function swtDoGenerateFlatDataShaderBench(_percentTagName,
                               _reportListTag,
                               _batchID,
                               _reportType,
                               _crossType,
                               _curReportFolder,
                               _machineIDPair,
                               _machineIDList
                               )
{
    var tmpMachineIDPair = _machineIDPair;
    if (_crossType == 2)
    {
        // cross build
        tmpMachineIDPair = "";
    }
    
    console.log("tmp---07:" + _crossType);
    
    //$.post("../phplibs/createReport/swtGenTempFlatData.php", 
    $.post("../phplibs/createReport/swtGenTempFlatDataShaderBench.php", 
    {
        batchID:               _batchID,
        reportType:            _reportType,
        crossType:             _crossType,
        curReportFolder:       _curReportFolder,
        machineIDPair:         tmpMachineIDPair, //_machineIDPair,
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
                swtDoGenerateRoutineReportShaderBench(_percentTagName,
                                           _reportListTag,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           1,
                                           _reportType,
                                           _crossType,
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
                swtDoGenerateFlatDataShaderBench(_percentTagName,
                                      _reportListTag,
                                      _batchID,
                                      _reportType,
                                      _crossType,
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
            console.log("tmp---08:" + json.errorMsg);
            alert(json.errorMsg);
        }
    });
}

// _crossType, 0 for cross API, 1 for cross ASIC, SYS, 2 for cross Builds, 
//             10 for version 2 cross API, 11 for version 2 cross ASIC / build (cross machines)
// _reportType, 0 for routine report, 1 for all reports
function swtGenerateRoutineReportVer3(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, _crossType)
{
    var t1 = "" + $("#inputFolderName").val();
    if (t1.length == 0)
    {
        //$("#" + _percentTagName).html("0%");
        //alert("please fill in folder name");
        //return;
    }
    
    var fileNum = $('#inputFileList').get(0).files.length;
    
    if (fileNum == 0)
    {
        alert("please choose folder to upload.");
        return;
    }
    
    $("#" + _percentTagName).html("0% (generating is started...)");
    
    $("#" + _reportListTag).html("");

    var t1 = "" + $("#valMachineIDList").val();
    //console.log("valMachineIDList: " + t1);
    var machineIDList = [];
    if (t1.length > 0)
    {
        machineIDList = t1.split(",");
    }
    t1 = "" + $("#valFolderMachineNameList").val();
    var folderMachineNameList = [];
    if (t1.length > 0)
    {
        folderMachineNameList = t1.split(",");
    }
    
    var machineIDPair = [];
    var checkedMachineIDList = [];
    var checkedMachineIDListCopy = [];
    var t2 = "";
    var t3 = "";
    if (_crossType == 10)
    {
        // cross API
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
            }
        }
        console.log("tmp---002B:" + checkedMachineIDList.length);
        if (checkedMachineIDList.length == 0)
        {
            // if cross api no machine selected go to next step
            // goto cross machine
            console.log("tmp---002A:" + checkedMachineIDList.length);
            swtGenerateRoutineReportVer3(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, 11);
            return;
        }
        console.log("tmp---002C:" + checkedMachineIDList.length);
    }
    else if (_crossType == 11)
    {
        // cross machines
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            t1 = $("#selMachineID" + machineIDList[i]).val();

            if ((t1 == null) ||
                (t1 == undefined) ||
                (t1.length == 0))
            {
                continue;
            }
            console.log("tmp---001:" + t1);
            
            var n1 = parseInt(t1);
            if (n1 != -1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
                machineIDPair.push(parseInt(machineIDList[i]));
                machineIDPair.push(n1);
                // if cross asic, pair are machineID, machineID
            }
        }
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDListCopy.push(parseInt(machineIDList[i]));
            }
        }
        if (machineIDPair.length == 0)
        {
            // if no machine selected go back
            $("#" + _percentTagName).html("0%");
            
            if (checkedMachineIDListCopy.length == 0)
            {
                console.log("tmp---008:" + checkedMachineIDList.length);
                alert("please select report to generate");
                return;
            }
            alert("generate success");
            location.reload(true);
            return;
        }
        console.log("tmp---002D:" + checkedMachineIDList.length);
    }
    t2 = swtImplode(machineIDPair, ",");
    t3 = swtImplode(checkedMachineIDList, ",");

    console.log("tmp---: " + t2);
    console.log("tmp---: " + t3);
    console.log("tmp---08:" + _crossType);

    swtDoGenerateFlatDataVer3(_percentTagName,
                              _reportListTag,
                              _batchID,
                              _reportType,
                              _crossType,
                              _curReportFolder,
                              t2,
                              t3
                              );
}

function swtDoGenerateFlatDataVer3(_percentTagName,
                                   _reportListTag,
                                   _batchID,
                                   _reportType,
                                   _crossType,
                                   _curReportFolder,
                                   _machineIDPair,
                                   _machineIDList
                                   )
{
    var tmpMachineIDPair = _machineIDPair;
    if (_crossType == 2)
    {
        // cross build
        tmpMachineIDPair = "";
    }
    
    console.log("tmp---07:" + _crossType);
    
    $.post("../phplibs/createReport/swtGenTempFlatDataVer3.php", 
    {
        batchID:               _batchID,
        reportType:            _reportType,
        crossType:             _crossType,
        curReportFolder:       _curReportFolder,
        machineIDPair:         tmpMachineIDPair, //_machineIDPair,
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
                
                //alert("next");
                
                swtDoGenerateRoutineReportVer3(_percentTagName,
                                               _reportListTag,
                                               _batchID,
                                               _machineIDList,
                                               _machineIDPair,
                                               _reportType,
                                               _crossType,
                                               0,
                                               0,
                                               0,
                                               0,
                                               json.curReportFolder,
                                               0
                                               );
            }
            else
            {
                swtDoGenerateFlatDataVer3(_percentTagName,
                                          _reportListTag,
                                          _batchID,
                                          _reportType,
                                          _crossType,
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
            console.log("tmp---08:" + json.errorMsg);
            alert(json.errorMsg);
        }
    });
}

// _crossType, 0 for cross API, 1 for cross ASIC, SYS, 2 for cross Builds, 
//             10 for version 2 cross API, 11 for version 2 cross ASIC / build (cross machines)
// _reportType, 0 for routine report, 1 for all reports
function swtGenerateRoutineReportVer4(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, _crossType)
{
    var t1 = "" + $("#inputFolderName").val();
    if (t1.length == 0)
    {
        //$("#" + _percentTagName).html("0%");
        //alert("please fill in folder name");
        //return;
    }
    
    var fileNum = $('#inputFileList').get(0).files.length;
    
    if (fileNum == 0)
    {
        alert("please choose folder to upload.");
        return;
    }
    
    $("#" + _percentTagName).html("(generating is started...)");
    
    $("#" + _reportListTag).html("");

    var t1 = "" + $("#valMachineIDList").val();
    //console.log("valMachineIDList: " + t1);
    var machineIDList = [];
    if (t1.length > 0)
    {
        machineIDList = t1.split(",");
    }
    t1 = "" + $("#valFolderMachineNameList").val();
    var folderMachineNameList = [];
    if (t1.length > 0)
    {
        folderMachineNameList = t1.split(",");
    }
    
    var machineIDPair = [];
    var checkedMachineIDList = [];
    var checkedMachineIDListCopy = [];
    var t2 = "";
    var t3 = "";
    if (_crossType == 10)
    {
        // cross API
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
            }
        }
        console.log("tmp---002B:" + checkedMachineIDList.length);
        if (checkedMachineIDList.length == 0)
        {
            // if cross api no machine selected go to next step
            // goto cross machine
            console.log("tmp---002A:" + checkedMachineIDList.length);
            swtGenerateRoutineReportVer4(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, 11);
            return;
        }
        console.log("tmp---002C:" + checkedMachineIDList.length);
    }
    else if (_crossType == 11)
    {
        // cross machines
        // generating latest report
        for (var i = 0; i < machineIDList.length; i++)
        {
            t1 = $("#selMachineID" + machineIDList[i]).val();

            if ((t1 == null) ||
                (t1 == undefined) ||
                (t1.length == 0))
            {
                continue;
            }
            console.log("tmp---001:" + t1);
            
            var n1 = parseInt(t1);
            if (n1 != -1)
            {
                checkedMachineIDList.push(parseInt(machineIDList[i]));
                machineIDPair.push(parseInt(machineIDList[i]));
                machineIDPair.push(n1);
                // if cross asic, pair are machineID, machineID
            }
        }
        for (var i = 0; i < machineIDList.length; i++)
        {
            var b1 = $("#checkMachineID" + machineIDList[i]).is(":checked");
            //console.log(b1);
            if (b1)
            {
                checkedMachineIDListCopy.push(parseInt(machineIDList[i]));
            }
        }
        if (machineIDPair.length == 0)
        {
            // if no machine selected go back
            $("#" + _percentTagName).html("");
            
            if (checkedMachineIDListCopy.length == 0)
            {
                console.log("tmp---008:" + checkedMachineIDList.length);
                alert("please select report to generate");
                return;
            }
            alert("generate success");
            location.reload(true);
            return;
        }
        console.log("tmp---002D:" + checkedMachineIDList.length);
    }
    t2 = swtImplode(machineIDPair, ",");
    t3 = swtImplode(checkedMachineIDList, ",");

    console.log("tmp---: " + t2);
    console.log("tmp---: " + t3);
    console.log("tmp---08:" + _crossType);

    swtDoGenerateFlatDataVer4(_percentTagName,
                              _reportListTag,
                              _batchID,
                              _reportType,
                              _crossType,
                              _curReportFolder,
                              t2,
                              t3
                              );
}

function swtDoGenerateFlatDataVer4(_percentTagName,
                                   _reportListTag,
                                   _batchID,
                                   _reportType,
                                   _crossType,
                                   _curReportFolder,
                                   _machineIDPair,
                                   _machineIDList
                                   )
{
    var tmpMachineIDPair = _machineIDPair;
    if (_crossType == 2)
    {
        // cross build
        tmpMachineIDPair = "";
    }
    
    console.log("tmp---07:" + _crossType);
    
    $.post("../phplibs/createReport/swtGenTempFlatDataVer4.php", 
    {
        batchID:               _batchID,
        reportType:            _reportType,
        crossType:             _crossType,
        curReportFolder:       _curReportFolder,
        machineIDPair:         tmpMachineIDPair, //_machineIDPair,
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
                
                //alert("next");
                
                swtDoGenerateRoutineReportVer4(_percentTagName,
                                               _reportListTag,
                                               _batchID,
                                               _machineIDList,
                                               _machineIDPair,
                                               _reportType,
                                               _crossType,
                                               0,
                                               0,
                                               json.curReportFolder,
                                               0,
                                               20,
                                               0,
                                               0,
                                               0,
                                               0
                                               );
            }
            else
            {
                swtDoGenerateFlatDataVer4(_percentTagName,
                                          _reportListTag,
                                          _batchID,
                                          _reportType,
                                          _crossType,
                                          json.curReportFolder,
                                          _machineIDPair,
                                          _machineIDList
                                          );
                if (json.fileID <= json.fileNum)
                {
                    //$("#" + _percentTagName).html("gen flatData: " + ((json.fileID / json.fileNum) * 100.0 ).toFixed(1) + "%");
                    $("#" + _percentTagName).html("generating flatData...");
                }
            }
        }
        else if (json.errorCode == "0")
        {
            console.log("tmp---08:" + json.errorMsg);
            alert(json.errorMsg);
        }
    });
}

function swtDoGenerateRoutineReport(_percentTagName,
                                    _reportListTag,
                                    _batchID,
                                    _machineIDList,
                                    _machineIDPair,
                                    _forceGenReport,
                                    _reportType,
                                    _crossType,
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
    //$.post("../phplibs/createReport/swtCompileReportAddition.php",
    $.post("../phplibs/createReport/swtCompileReportAdditionNoise.php",
    {
        batchID:        _batchID,
        machineIDPair:  _machineIDPair,
        machineIDList:  _machineIDList,
        forceGenReport: _forceGenReport,
        reportType:     _reportType,
        crossType:      _crossType,
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
                swtXLSXBatchReport(_percentTagName,
                                   _reportListTag,
                                   json.batchID, _reportType, 0, _curReportFolder, _crossType);
            }
            else
            {   
                swtDoGenerateRoutineReport(_percentTagName,
                                           _reportListTag,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           0,
                                           _reportType,
                                           _crossType,
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

function swtDoGenerateRoutineReportVer3(_percentTagName,
                                        _reportListTag,
                                        _batchID,
                                        _machineIDList,
                                        _machineIDPair,
                                        _reportType,
                                        _crossType,
                                        _folderID,
                                        _curTestPos,
                                        _subTestNum,
                                        _reportToken,
                                        _curReportFolder,
                                        _sheetLinePos
                                        )
{
    $.post("../phplibs/createReport/swtCompileReportAdditionVer3.php", 
    {
        batchID:        _batchID,
        machineIDPair:  _machineIDPair,
        machineIDList:  _machineIDList,
        reportType:     _reportType,
        crossType:      _crossType,
        folderID:       _folderID,
        curTestPos:     _curTestPos,
        subTestNum:     _subTestNum,
        reportToken:        _reportToken,
        curReportFolder:    _curReportFolder,
        sheetLinePos:       _sheetLinePos
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
                //alert("next");
                //return;
                
                
                $("#" + _percentTagName).html("converting XML to XLSX, please wait...");
                swtXLSXBatchReport(_percentTagName,
                                   _reportListTag,
                                   json.batchID, _reportType, 0, _curReportFolder, _crossType);
            }
            else
            {   
                swtDoGenerateRoutineReportVer3(_percentTagName,
                                               _reportListTag,
                                               _batchID,
                                               _machineIDList,
                                               _machineIDPair,
                                               _reportType,
                                               _crossType,
                                               json.folderID,
                                               json.curTestPos,
                                               json.subTestNum,
                                               json.reportToken,
                                               json.curReportFolder,
                                               json.sheetLinePos
                                               );
                if ((json.folderNum > 0) &&
                    (json.testNum   > 0))
                {
                    var f1 = 1.0 / json.folderNum;
                    var f2 = f1 * json.curTestPos / json.testNum;
                    var f3 = f1 * json.folderID;
                    var f4 = (f3 + f2) * 100.0;
                    $("#" + _percentTagName).html("gen report: " + f4.toFixed(1) + "%");
                }
            }
        }
    });
}

function swtDoGenerateRoutineReportShaderBench(_percentTagName,
                                    _reportListTag,
                                    _batchID,
                                    _machineIDList,
                                    _machineIDPair,
                                    _forceGenReport,
                                    _reportType,
                                    _crossType,
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
    $.post("../phplibs/createReport/swtCompileReportAdditionShaderBench.php",
    {
        batchID:        _batchID,
        machineIDPair:  _machineIDPair,
        machineIDList:  _machineIDList,
        forceGenReport: _forceGenReport,
        reportType:     _reportType,
        crossType:      _crossType,
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
                swtXLSXBatchReport(_percentTagName,
                                   _reportListTag,
                                   json.batchID, _reportType, 0, _curReportFolder, _crossType);
            }
            else
            {   
                swtDoGenerateRoutineReportShaderBench(_percentTagName,
                                           _reportListTag,
                                           _batchID,
                                           _machineIDList,
                                           _machineIDPair,
                                           0,
                                           _reportType,
                                           _crossType,
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

function swtSetMachineReportProgress(_crossType, _phaseTag, _machineID, _value)
{
    if (_crossType == 10)
    {
        $("#pBarAPI_" + _machineID).progressbar({
            value: Math.floor(_value)
        });
        $("#pBarAPI_" + _machineID + "_Text").text( _value + "%" );
    }
    else if (_crossType == 11)
    {
        $("#pBarASIC_" + _machineID).progressbar({
            value: Math.floor(_value)
        });
        $("#pBarASIC_" + _machineID + "_Text").text( _value + "%" );
    }
}

function swtGetMachineReportProgress(_crossType, _phaseTag, _machineID)
{
    var tmpTag = "";
    if (_crossType == 10)
    {
        tmpTag = "pBarAPI_" + _machineID;
    }
    else if (_crossType == 11)
    {
        tmpTag = "pBarASIC_" + _machineID;
    }
    else
    {
        return -1;
    }
    var tmpVal = $("#" + tmpTag).progressbar( "value" );
    return tmpVal;
}

function swtSetMachineReportFileName(_crossType, _phaseTag, _machineID, _value)
{
    if (_crossType == 10)
    {
        $("#pBarAPI_" + _machineID + "_ReportName").text(_value);
    }
    else if (_crossType == 11)
    {
        $("#pBarASIC_" + _machineID + "_ReportName").text(_value);
    }
}

function swtGetMachineReportFileName(_crossType, _phaseTag, _machineID)
{
    var tmpTag = "";
    if (_crossType == 10)
    {
        tmpTag = "pBarAPI_" + _machineID + "_ReportName" ;
    }
    else if (_crossType == 11)
    {
        tmpTag = "pBarASIC_" + _machineID + "_ReportName";
    }
    else
    {
        return "";
    }
    console.log("003-" + tmpTag);
    var tmpVal = $("#" + tmpTag).text();
    return tmpVal;
}

function swtDoGenerateRoutineReportVer4(_percentTagName,
                                        _reportListTag,
                                        _batchID,
                                        _machineIDList,
                                        _machineIDPair,
                                        _reportType,
                                        _crossType,
                                        _folderID,
                                        _curTestPos,
                                        _curReportFolder,
                                        _sheetLinePos,
                                        _subjectNameFilterNumMax,
                                        _curFolderFileID,
                                        _curFileOffset,
                                        _cmpFolderFileID,
                                        _cmpFileOffset
                                        )
{
    $.post("../phplibs/createReport/swtCompileReportAdditionVer4.php", 
    {
        batchID:        _batchID,
        machineIDPair:  _machineIDPair,
        machineIDList:  _machineIDList,
        reportType:     _reportType,
        crossType:      _crossType,
        folderID:       _folderID,
        curTestPos:     _curTestPos,
        curReportFolder:    _curReportFolder,
        sheetLinePos:       _sheetLinePos,
        subjectNameFilterNumMax:    _subjectNameFilterNumMax,
        curFolderFileID:            _curFolderFileID,
        curFileOffset:              _curFileOffset,
        cmpFolderFileID:            _cmpFolderFileID,
        cmpFileOffset:              _cmpFileOffset
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
                //alert("next");
                //return;
                //swtSetMachineReportProgress(_crossType, "01", json.curMachineID, 60);
                
                $("#" + _percentTagName).html("converting XML to XLSX, please wait...");
                swtXLSXBatchReport(_percentTagName,
                                   _reportListTag,
                                   json.batchID, _reportType, 0, _curReportFolder, _crossType);
            }
            else
            {   
                swtDoGenerateRoutineReportVer4(_percentTagName,
                                               _reportListTag,
                                               _batchID,
                                               _machineIDList,
                                               _machineIDPair,
                                               _reportType,
                                               _crossType,
                                               json.folderID,
                                               json.curTestPos,
                                               json.curReportFolder,
                                               json.sheetLinePos,
                                               json.subjectNameFilterNumMax,
                                               json.curFolderFileID,
                                               json.curFileOffset,
                                               json.cmpFolderFileID,
                                               json.cmpFileOffset
                                               );
                if (json.folderNum > 0)
                {
                    var f1 = 1.0 / json.folderNum;
                    var f2 = f1 * (json.curVisitedFileSizeSum + json.cmpVisitedFileSizeSum) / 
                                  (json.curToVisitFileSizeSum + json.cmpToVisitFileSizeSum);
                    var f3 = f1 * json.folderID;
                    var f4 = (f3 + f2) * 100.0;
                    var f5 = 100.0 * (json.curVisitedFileSizeSum + json.cmpVisitedFileSizeSum) / 
                                     (json.curToVisitFileSizeSum + json.cmpToVisitFileSizeSum);
                    //var f1 = 1.0 / json.folderNum;
                    //var f2 = f1 * json.curTestPos / json.testNum;
                    //var f3 = f1 * json.folderID;
                    //var f4 = (f3 + f2) * 100.0;
                    
                    //$("#" + _percentTagName).html("gen report: " + f4.toFixed(1) + "%");
                    $("#" + _percentTagName).html("generating report...");
                    // generating report use 0% - 60% of progress bar
                    // converting Excel spreadsheet use 60% - 100%
                    swtSetMachineReportProgress(_crossType, "01", json.curMachineID, Math.ceil(f5 * 0.6));
                    
                    //var tmpVal = swtGetMachineReportFileName(_crossType, "03", json.curMachineID);
                    //console.log("001-" + tmpVal + ", " + json.curMachineID);
                    //if ((tmpVal.length                   == 0) &&
                    //    (json.finalReportFileName.length >  0))
                    //{
                    //    console.log("002-" + json.finalReportFileName);
                    //    swtSetMachineReportFileName(_crossType, "03", json.curMachineID, json.finalReportFileName);
                    //}
                }
            }
        }
    });
}

function swtZipBatchReport(_percentTagName, _reportListTag, _batchID, _reportType, _fileID)
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
            swtZipBatchReport(_percentTagName, _reportListTag, _batchID, _reportType, _fileID + 1);
        }
    });
}

function swtXLSXBatchReport(_percentTagName, _reportListTag, _batchID, _reportType, _fileID, _curReportFolder, _crossType)
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
            swtXLSX2XLSM(_percentTagName, _reportListTag, _batchID, _reportType, 0, _curReportFolder, _crossType);
            
            var f1 = swtGetMachineReportProgress(_crossType, "02", json.curMachineID);
            f1 = f1 < 60 ? 60 : f1;
            swtSetMachineReportProgress(_crossType, "02", json.curMachineID, Math.ceil(f1) + 10);
        }
        else if (json.errorCode == "2")
        {
            // generate all history reports
            $("#" + _percentTagName).html(json.errorMsg);
            swtXLSXBatchReport(_percentTagName, _reportListTag, _batchID, _reportType, _fileID + 1, _curReportFolder, _crossType);
            
            //var f1 = json.fileID * 0.5 / json.fileNum * 100.0;
            //var f1 = swtGetMachineReportProgress(_crossType, "02", json.curMachineID);
            //swtSetMachineReportProgress(_crossType, "02", json.curMachineID, Math.ceil(f1) + 10);
            var f1 = swtGetMachineReportProgress(_crossType, "02", json.curMachineID);
            f1 = f1 < 60 ? 60 : f1;
            swtSetMachineReportProgress(_crossType, "02", json.curMachineID, Math.ceil(f1) + 10);
        }
        else
        {
            alert("error occurs, please restart report generation again!");
            $("#" + _percentTagName).html(json.errorMsg);
            location.reload(true);
        }
    });
}

function swtXLSX2XLSM(_percentTagName, _reportListTag, _batchID, _reportType, _fileID, _curReportFolder, _crossType)
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
            var f1 = swtGetMachineReportProgress(_crossType, "02", json.curMachineID);
            swtSetMachineReportProgress(_crossType, "02", json.curMachineID, Math.ceil(f1) + 10);
            
            if (_reportType == 0)
            {
                // only generating latest report
                //alert(json.reportFolder);
                
                if (_crossType >= 10)
                {
                    swtMoveReportToFolder(_percentTagName, _reportListTag, _batchID, _reportType, _curReportFolder, _crossType);
                    return;
                }
                
                $("#inputFolderName").val("");
                alert("generate success");
                location.reload(true);
            }
            else
            {
                // generate all history reports
                $("#" + _percentTagName).html("zipping XLSM to ZIP, please wait...");
                swtZipBatchReport(_percentTagName, _reportListTag, _batchID, _reportType, 0);
            }
        }
        else if (json.errorCode == "2")
        {
            // generate all history reports
            $("#" + _percentTagName).html(json.errorMsg);
            swtXLSX2XLSM(_percentTagName, _reportListTag, _batchID, _reportType, _fileID + 1, _curReportFolder, _crossType);
            
            var f1 = swtGetMachineReportProgress(_crossType, "02", json.curMachineID);
            swtSetMachineReportProgress(_crossType, "02", json.curMachineID, Math.ceil(f1) + 10);
        }
    });
}

function swtMoveReportToFolder(_percentTagName, _reportListTag, _batchID, _reportType, _curReportFolder, _crossType)
{
    $.post("../phplibs/createReport/swtMoveReportToFolder.php", 
    {
        batchID:         _batchID,
        crossType:       _crossType,
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
            if (_crossType == 10)
            {
                swtGenerateRoutineReportVer4(_percentTagName, _reportListTag, _reportType, _curReportFolder, _batchID, 11);
                return;
            }
            
            alert("generate success");
            location.reload(true);
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
            swtGenerateRoutineReport('finishPercentBar', 'reportList', 1, batchID, -1);
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
    
    pageShowMask();
    
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
            pageHideMask();
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

function swtGetCardChoiceCodeShaderBench(_divTag, _reportTag)
{
    
    $.post("../phplibs/getInfo/swtGetBatchMachinesInfoShaderBench.php", 
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

function swtGetCardChoiceCodeVer2(_reportTag)
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
            
            var reportListCode = "";
            var curFolderName = "";
            for (var i = 0; i < json.reportFileNameList.length; i++)
            {
                var tmpList = json.reportFileNameList[i].split("/");
                var t1 = "";
                if (tmpList.length > 0)
                {
                    t1 = tmpList[tmpList.length - 1];
                }
                if (json.reportFolderMatchList[i] != curFolderName)
                {
                    curFolderName = json.reportFolderMatchList[i];
                    reportListCode += "<p><span style=\"color: #a0a000; background-color: #f0f0f0;\">" + curFolderName + "</span> : </p>\n";
                }
                
                reportListCode += "<p><a href=\"" + json.reportFileNameList[i] + "\">" +
                                  t1 +
                                  "</a></p>\n";
            }
            $("#" + _reportTag).html(reportListCode);
        }
    });
}

function swtGetCardChoiceCodeCrossAPI(_divTag, _reportTag)
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
                var t2 = "<select id=\"" + t3 + "\" name=\"" + t3 + "\" style=\"display:none;\">\n" +
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

function swtGetCardChoiceCodeCrossASIC(_divTag, _reportTag)
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
                var t2 = "<select id=\"" + t3 + "\" name=\"" + t3 + "\" >\n" +
                         "<option value=\"-1\">skip</option>\n";
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
                t1 += "<input id=\"" + t5 + "\" name=\"" + t5 + 
                      "\" type=\"checkbox\" checked=\"checked\" style=\"display:none;\">\n";
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

function swtGetCardChoiceCodeCrossBuild(_divTag, _reportTag)
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
                var t2 = "<select id=\"" + t3 + "\" name=\"" + t3 + "\" >\n" +
                         "<option value=\"-1\">skip</option>\n";
                t4 += json.machineIDList[i];
                if (i < (json.machineIDList.length - 1))
                {
                    t4 += ",";
                }
                for (var j = 0; j < json.historyBatchList[i].length; j++)
                {
                    if (json.batchID == json.historyBatchList[i][j])
                    {
                        continue;
                    }
                    t2 += "<option value=\"" + json.historyBatchList[i][j] + "\">" + json.historyTimeList[i][j] + 
                          " - batch: " + json.historyBatchList[i][j] +
                          "</option>";
                }
                t2 += "</select>\n";
                
                console.log(t2);
                
                t1 += t2;
                
                t1 += "</td>\n";
                
                t1 += "<td>\n";
                t1 += "&nbsp&nbsp";
                t1 += "<input id=\"" + t5 + "\" name=\"" + t5 + 
                      "\" type=\"checkbox\" checked=\"checked\" style=\"display:none;\">\n";
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

function swtGetShortBatchIDListOutUser(_comboTag)
{
    $.post("../phplibs/getInfo/swtGetBatchIDListOutUser.php", 
    {
        batchGroup:          1,
        batchState:          1,
        batchNum:            10
    }, 
    function(data,status) 
    {
        console.log(data);
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
        else
        {
            alert(json.errorMsg);
        }
    });
}

function swtCheckCrossFolderOption(_checkTag)
{
    var b1 = $("#" + _checkTag).is(":checked");

    var t1 = $("#valMachineIDList").val();
    var machineIDList = t1.split(",");
    
    if (_checkTag == "crossAPICheck")
    {
        for (var i = 0; i < machineIDList.length; i++)
        {
            $("#checkMachineID" + machineIDList[i]).prop("checked", b1);
            
            var t2 = "pBarAPI_" + machineIDList[i] + "_ReportName";
            if (b1)
            {
                //$("#" + t2).show();
                $("#" + t2).css("color", "#000000");
            }
            else
            {
                //$("#" + t2).hide();
                $("#" + t2).css("color", "#ffffff");
            }
        }
    }
    else if (_checkTag == "crossASICCheck")
    {
        if (b1 == false)
        {
            for (var i = 0; i < machineIDList.length; i++)
            {
                $("#selMachineID" + machineIDList[i]).val(-1);
                
                var t2 = "pBarASIC_" + machineIDList[i] + "_ReportName";
                $("#" + t2).css("color", "#ffffff");
            }
        }
    }
    //swtReportOptionChanged(_checkTag);
}

function swtCheckCrossAPIMachine(_machineID)
{
    var b1 = $("#checkMachineID" + _machineID).is(":checked");
    
    var t1 = "pBarAPI_" + _machineID + "_ReportName";
    if (b1)
    {
        //console.log("1");
        //$("#" + t1).show();
        $("#" + t1).css("color", "#000000");
    }
    else
    {
        //console.log("2");
        //$("#" + t1).hide();
        $("#" + t1).css("color", "#ffffff");
    }
}

function swtCheckCrossASICMachine(_machineID)
{
    var n1 = $("#selMachineID" + _machineID).val();
    
    var t1 = "pBarASIC_" + _machineID + "_ReportName";
    if (n1 == -1)
    {
        //$("#" + t1).hide();
        $("#" + t1).css("color", "#ffffff");
    }
    else
    {
        //$("#" + t1).show();
        $("#" + t1).css("color", "#000000");
    }
}

function swtGetFolderMachineNameListOutUser(_srcFolderName, _batchID, _crossAPITag, _crossASICTag, _crossBuildTag)
{
    $("#" + _crossAPITag).html("<img src=\"../images/loading.gif\" width=\"20px\" height=\"20px\" />");
    $("#" + _crossASICTag).html("<img src=\"../images/loading.gif\" width=\"20px\" height=\"20px\" />");
    $.post("../phplibs/importResult/swtGetSourceDirInfo.php", 
    {
        batchID:       _batchID,
        srcFolderName: _srcFolderName
    }, 
    function(data,status) 
    {
        console.log(data);
        var json = eval("(" + data + ")");

        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var crossAPICode = "<legend>Cross API<input id=\"crossAPICheck\" name=\"crossAPICheck\" type=\"checkbox\" onchange=\"swtCheckCrossFolderOption('crossAPICheck');\" checked=\"checked\"></input></legend>";
            var crossASICCode = "<legend>Cross ASIC / Build<input id=\"crossASICCheck\" name=\"crossASICCheck\" type=\"checkbox\" onchange=\"swtCheckCrossFolderOption('crossASICCheck');\" checked=\"checked\"></input></legend>";
            
            crossAPICode += "<table>\n";
            crossASICCode += "<table>\n";
            
            for (var i = 0; i < json.folderMachineNameList.length; i++)
            {
                crossAPICode += "<tr>\n";
                crossAPICode += "<td style=\"font-weight: bold;\"> - " + json.folderMachineNameList[i] + "</td><td>&nbsp</td>\n";
                crossAPICode += "<td> <input id=\"checkMachineID" + json.machineIDList[i] + 
                            "\" type=\"checkbox\" value=\"" + 
                            json.machineIDList[i] + "\" checked=\"checked\" " +
                            "onclick=\"swtCheckCrossAPIMachine(" + json.machineIDList[i] + ");\" " +
                            " /></td>\n";
                crossAPICode += "</tr>\n";
                crossAPICode += "<tr>\n";
                crossAPICode += "<td colspan=\"3\">\n";
                crossAPICode += "<div id=\"pBarAPI_" + json.machineIDList[i] +
                                 "_ReportName\" style=\"width: 350px; color: #101010;\">" + json.reportNameList[i] +
                                 "</div>\n";
                crossAPICode += "</td>\n";
                crossAPICode += "</tr>\n";
                crossAPICode += "<tr>\n";
                crossAPICode += "<td colspan=\"3\">\n";
                crossAPICode += "<div id=\"pBarAPI_" + json.machineIDList[i] + "\" style=\"width: 350px; position: relative;\">" +
                                     "<div id=\"pBarAPI_" + json.machineIDList[i] + "_Text\" " +
                                     "style=\"position: absolute; left: 45%; top: 4px; font-weight: bold; text-shadow: 1px 1px 0 #fff;\">&nbsp" +
                                     "</div>\n";
                                 "</div>\n";
                crossAPICode += "</td>\n";
                crossAPICode += "</tr>\n";

                
                crossASICCode += "<tr>\n";
                crossASICCode += "<td style=\"font-weight: bold;\"> - " + json.folderMachineNameList[i] + "</td><td>&nbsp</td>\n";
                crossASICCode += "<td> <select id=\"selMachineID" + json.machineIDList[i] + "\" " +
                                 "onchange=\"swtCheckCrossASICMachine(" + json.machineIDList[i] + ");\" " +
                                 ">\n";
                crossASICCode += "<option value=\"-1\">Skip</option>";
                
                for (var j = 0; j < json.folderMachineNameList.length; j++)
                {
                    if (json.folderMachineNameList[j] ==
                        json.folderMachineNameList[i])
                    {
                        continue;
                    }
                    crossASICCode += "<option value=\"" + json.machineIDList[j] + 
                                 "\">" + json.folderMachineNameList[j] + "</option>";
                }
                
                crossASICCode += "</select></td>\n";
                crossASICCode += "</tr>\n";
                crossASICCode += "<tr>\n";
                crossASICCode += "<td colspan=\"3\">\n";
                crossASICCode += "<div id=\"pBarASIC_" + json.machineIDList[i] + 
                                 "_ReportName\" style=\"width: 350px; color: #101010; color: #ffffff;\">" + json.reportNameList[i] +
                                 "</div>\n";
                crossASICCode += "</td>\n";
                crossASICCode += "</tr>\n";
                crossASICCode += "<tr>\n";
                crossASICCode += "<td colspan=\"3\">\n";
                crossASICCode += "<div id=\"pBarASIC_" + json.machineIDList[i] + "\" style=\"width: 350px; position: relative;\">" +
                                     "<div id=\"pBarASIC_" + json.machineIDList[i] + "_Text\" " +
                                     "style=\"position: absolute; left: 45%; top: 4px; font-weight: bold; text-shadow: 1px 1px 0 #fff;\">&nbsp" +
                                     "</div>\n";
                                 "</div>\n";
                crossASICCode += "</td>\n";
                crossASICCode += "</tr>\n";

            }
            crossAPICode += "</table>";
            crossASICCode += "</table>";
            
            var t1 = swtImplode(json.machineIDList, ",");
            var t3 = swtImplode(json.folderMachineNameList, ",");
            var t2 = "<input type=\"hidden\" id=\"valMachineIDList\" name=\"valMachineIDList\" value=\"" + t1 + "\" />\n" +
                     "<input type=\"hidden\" id=\"valFolderMachineNameList\" name=\"valFolderMachineNameList\" value=\"" + t3 + "\" />\n";
            
            //$("#" + _crossAPITag).html(crossAPI);
            //$("#" + _crossASICTag).html(crossASIC);
            $("#" + _crossAPITag).html(crossAPICode);
            $("#" + _crossASICTag).html(crossASICCode);
            
            for (var i = 0; i < json.folderMachineNameList.length; i++)
            {
                $("#pBarAPI_" + json.machineIDList[i]).progressbar();
                var progressbarValue = $("#pBarAPI_" + json.machineIDList[i]).find( ".ui-progressbar-value" );
                progressbarValue.css({
                  "background": "#00a000"
                });
                
                $("#pBarASIC_" + json.machineIDList[i]).progressbar();
                progressbarValue = $("#pBarASIC_" + json.machineIDList[i]).find( ".ui-progressbar-value" );
                progressbarValue.css({
                  "background": "#00a000"
                });
            }
            
            console.log(">>>>");
            console.log(crossASIC);
            console.log(">>>>");
            
            $("#divMachineListFlat").html(t2);
        }
        else
        {
            $("#" + _crossAPITag).html("");
            $("#" + _crossASICTag).html("");
            alert(json.errorMsg);
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