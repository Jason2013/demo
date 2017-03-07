var swtTestIDListSet = [];

function swtGetTestListCode(_divTag)
{
    
    $.post("../phplibs/getInfo/swtGetTestList.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            var cardListCode = "";
            var testListCode = "";
            cardListCode += "<select id=\"cardList\" name=\"cardList\" onchange=\"swtGetTestData(-1);\">\n";
            for (var i = 0; i < json.uniqueCardNameList.length; i++)
            {
                cardListCode += "<option value=\"" + json.uniqueCardIDList[i] + "\">" +
                                json.uniqueCardNameList[i] + "</option>\n";
            }
            cardListCode += "</select>\n";
            
            testListCode += "<select id=\"testList\" name=\"testList\" onchange=\"swtGetTestData(-1);\">\n";
            for (var i = 0; i < json.testNameList.length; i++)
            {
                testListCode += "<option value=\"" + json.testIDList[i] + "\">" +
                                json.testNameList[i] + "</option>\n";
            }
            testListCode += "</select>\n";

            //console.log(json.uniqueCardNameList.length);
            $("#" + _divTag).html(cardListCode + "&nbsp&nbsp" + testListCode);
            
            $( "#subTestChoice" ).slider();
            $( "#subTestChoice" ).on( "slidestop", swtGetSubTestData );
            swtGetTestData(-1);
            
        }
        
    });
}

function swtGetTestListCode2(_apiCompTag, _gpuCompTag, _sysCompTag, _graphListTag)
{
    var barWidth = $("#pageRightPart").width() * 0.95;
    //alert(barWidth);
    
    $.post("../phplibs/getInfo/swtGetTestList2.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            var testSlotCode = "";

            for (var i = 0; i < json.testNameList.length; i++)
            {
                testSlotCode += "<br />&nbsp&nbsp";
                testSlotCode += "<div class=\"graphMoveCanvas\"><table><tr>";
                testSlotCode += "<td><button id=\"sliderDec" + i + "\" class=\"graphMoveButton\" " +
                                "onclick=\"swtIncDecSubTest('subTestChoice', " + i + ", -1);\" ><<</button>&nbsp&nbsp</td>";
                testSlotCode += "<td><div id=\"subTestChoice" + i + "\" style=\"width: 350px;\">&nbsp&nbsp</div></td>";
                testSlotCode += "<td><button id=\"sliderInc" + i + "\" class=\"graphMoveButton\" " +
                                "onclick=\"swtIncDecSubTest('subTestChoice', " + i + ", 1);\" >>></button>&nbsp&nbsp</td>";
                testSlotCode += "<td><input type=\"text\" id=\"subTestFilter" + i + "\" " +
                                "name=\"subTestFilter" + i + "\" />&nbsp&nbsp</td>";
                //testSlotCode += "<td><button id=\"filterButton" + i + "\" class=\"graphMoveButton\" " +
                //                "onclick=\"swtGetTestData2(" + i + ", -1);\" >refresh</button></td>";
                testSlotCode += "</tr></table></div>";
                testSlotCode += "<div id=\"chartContainer" + i + "\" class=\"graphMoveCanvas\" ";
                testSlotCode += "style=\"width: " + barWidth + "px; height: 300px;\"></div>";
                testSlotCode += "<div class=\"graphMoveCanvasEnd\">&nbsp</div><br />";
            }
            
            var apiListCode = "<legend><input id=\"apiCompCheck\" name=\"apiCompCheck\" type=\"checkbox\" onchange=\"swtCheckTestInfoOption('apiCompCheck');\" checked=\"checked\"></input> API Comparison</legend>\n";
            var gpuListCode = "<legend><input id=\"gpuCompCheck\" name=\"gpuCompCheck\" type=\"checkbox\" onchange=\"swtCheckTestInfoOption('gpuCompCheck');\" checked=\"checked\"></input> GPU Comparison</legend>";
            var sysListCode = "<legend><input id=\"sysCompCheck\" name=\"sysCompCheck\" type=\"checkbox\" onchange=\"swtCheckTestInfoOption('sysCompCheck');\" checked=\"checked\"></input> OS Comparison</legend>";
            for (var i = 0; i < json.uniqueDriverNameList.length; i++)
            {
                apiListCode += "<input id=\"api" + i + "\" name=\"api" + i + "\" " +
                               "type=\"checkbox\" value=\"" + i + "\"  onchange=\"swtTestOptionChanged('api');\"  checked=\"checked\"></input>";
                apiListCode += "&nbsp" + json.uniqueDriverNameList[i] + "</br>";
            }
            
            for (var i = 0; i < json.uniqueCardNameList.length; i++)
            {
                gpuListCode += "<input id=\"gpu" + i + "\" name=\"gpu" + i + "\" " +
                               "type=\"checkbox\" value=\"" + json.uniqueCardIDList[i] + "\"  onchange=\"swtTestOptionChanged('gpu');\" checked=\"checked\"></input>";
                gpuListCode += "&nbsp" + json.uniqueCardNameList[i] + "</br>";
            }
            
            for (var i = 0; i < json.uniqueSysNameList.length; i++)
            {
                sysListCode += "<input id=\"sys" + i + "\" name=\"sys" + i + "\" " +
                               "type=\"checkbox\" value=\"" + json.uniqueSysNameList[i] + "\"  onchange=\"swtTestOptionChanged('sys');\" checked=\"checked\"></input>";
                sysListCode += "&nbsp" + json.uniqueSysNameList[i] + "</br>";
            }

            $("#" + _apiCompTag).html(apiListCode);
            $("#" + _gpuCompTag).html(gpuListCode);
            $("#" + _sysCompTag).html(sysListCode);
            $("#" + _graphListTag).html(testSlotCode);
            
            // save api num & gpu num for later use
            $("#apiNum").val(json.uniqueDriverNameList.length);
            $("#gpuNum").val(json.uniqueCardNameList.length);
            $("#sysNum").val(json.uniqueSysNameList.length);
            $("#testNum").val(json.testNameList.length);
            
            var t1 = swtImplode(json.testIDList, ",");
            var t2 = swtImplode(json.uniqueCardIDList, ",");
            $("#testIDList").val(t1);
            $("#cardIDList").val(t2);
            var t3 = swtImplode(json.uniqueCardNameList, ",");
            $("#cardNameList").val(t3);
            
            for (var i = 0; i < json.testNameList.length; i++)
            {
                $( "#subTestChoice" + i ).slider();
                $( "#subTestChoice" + i ).on( "slidestop", "", {testPosID: i}, swtGetSubTestData2 );
                
                $( "#sliderDec" + i ).button();
                $( "#sliderInc" + i ).button();
                
                $( "#filterButton" + i ).button();
                
                swtGetTestData2(i, -1, 1);
            }
            //swtGetTestData2(0, -1);
        }
    });
}

function swtGetTestListCode3(_subTestKeyWord, _testID)
{
    $.post("../phplibs/getInfo/swtGetTestList2.php", 
    {
        subTestKeyWord : _subTestKeyWord
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            var testSlotCode = "";

        }
    });
}

function swtCheckTestInfoOption(_checkTag)
{
    var b1 = $("#" + _checkTag).is(":checked");
    //alert (t1);
    if (_checkTag == "apiCompCheck")
    {
        var n1 = parseInt($("#apiNum").val());
        //console.log(n1);
        for (var i = 0; i < n1; i++)
        {
            $("#api" + i).prop("checked", b1);
        }
    }
    else if (_checkTag == "gpuCompCheck")
    {
        var n1 = parseInt($("#gpuNum").val());
        //console.log(n1);
        for (var i = 0; i < n1; i++)
        {
            $("#gpu" + i).prop("checked", b1);
        }
    }
    else if (_checkTag == "sysCompCheck")
    {
        var n1 = parseInt($("#sysNum").val());
        //console.log(n1);
        for (var i = 0; i < n1; i++)
        {
            $("#sys" + i).prop("checked", b1);
        }
    }
    swtTestOptionChanged(_checkTag);
}
//*/
function swtGetMaxFloatLevel(_val)
{
    if (_val <= 1.0)
    {
        return _val;
    }
    if (isNaN(_val))
    {
        return 0.0;
    }
    var startVal = 1.0;
    for (var i = 0; i < 100; i++)
    {
        console.log("1----");
        if (startVal >= _val)
        {
            break;
        }
        startVal *= 10.0;
    }
    
    var f1 = startVal * 0.1;
    var f2 = f1;
    for (var i = 0; i < 100; i++)
    {
        console.log("2----");
        if (f2 > _val)
        {
            break;
        }
        f2 += f1;
    }
    console.log(f2);
    return f2;
}

function swtGetMinFloatLevel(_val)
{
    if (_val <= 1.0)
    {
        return _val;
    }
    if (isNaN(_val))
    {
        return 0.0;
    }
    var startVal = 1.0;
    for (var i = 0; i < 100; i++)
    {
        console.log("3----");
        if (startVal >= _val)
        {
            break;
        }
        startVal *= 10.0;
    }
    
    var f1 = startVal * 0.1;
    var f2 = f1;
    for (var i = 0; i < 100; i++)
    {
        console.log("4----");
        if (f2 > _val)
        {
            break;
        }
        f2 += f1;
    }
    console.log(f2 - f1);
    return f2 - f1;
}

function swtGetSubTestData(event, ui)
{
    swtGetTestData(ui.value);
    //console.log("evt: " + event.type);
    //console.log("pos: " + ui.value);
}

function swtGetTestData(_subTestPosID)
{
    var t1 = $("#cardList").val();
    var t2 = $("#testList").val();
    
    var barWidth = $("#pageRightPart").width() * 0.95;
    $("#chartContainer").width(barWidth);
    
    $.post("../phplibs/getInfo/swtGetTestData.php", 
    {
        cardPosID:    t1,
        testPosID:    t2,
        subTestPosID: _subTestPosID
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            //console.log(data);
            var cardNum = json.uniqueCardNameList.length;
            var umdNum = json.uniqueDriverNameList.length;
            var batchNum = json.batchTimeList.length;
            
            var subTestNum = json.subTestNum;
            //$( "#subTestChoice" ).slider();
            if (_subTestPosID == -1)
            {
                $( "#subTestChoice" ).slider("option", "value", 0);
            }
            $( "#subTestChoice" ).slider("option", "max", subTestNum);
            //$( "#subTestChoice" ).on( "slidestop", swtGetSubTestData );
            
            var testVal = [];
            //swtTestVal = [];
            
            for (var j = 0; j < umdNum; j++)
            {
                var testValSec = { type:          "line",
                                   lineThickness: 3,
                                   axisYType:     "primary",
                                   showInLegend:  true,
                                   name:          json.uniqueDriverNameList[j],
                                   dataPoints:    []};
                                   
                //testValSec.dataPoints.push([]);
                testVal.push(testValSec);
            }
            
            var maxVal = 0.0;
            var minVal = Number.MAX_VALUE;
            
            for (var i = 0; i < batchNum; i++)
            {
                var tmpTime = "" + json.batchTimeList[i];
                
                for (var j = 0; j < umdNum; j++)
                {
                    // new Date(Date.parse(tmpTime.replace(/-/g, "/")))
                    //var tmpDataSec = {x: new Date(Date.parse(tmpTime.replace(/-/g, "/"))),
                    //                  y: parseFloat(json.testValList[i * umdNum + j])};
                    var d1 = new Date(Date.parse(tmpTime.replace(/-/g, "/")));
                    var t1 = d1.toDateString();
                    var tmpDataSec = {x: batchNum - i - 1,
                                      y: parseFloat(json.testValList[i * umdNum + j]),
                                      label: t1};
                    //console.log(tmpDataSec.x + ", " + tmpDataSec.y);
                    testVal[j].dataPoints.push(tmpDataSec);
                    
                    maxVal = Math.max(maxVal, tmpDataSec.y);
                    minVal = Math.min(minVal, tmpDataSec.y);
                }
            }
            
            var maxY = maxVal + ((maxVal - minVal) * 0.0);
            var minY = minVal - ((maxVal - minVal) * 0.0);
            minY = Math.max(minY, 0.0);
            
            var graphTitle = json.testName;
            if ((json.subTestName != null) && (json.subTestName.length > 0))
            {
                graphTitle += " - ";
                graphTitle += json.subTestName;
            }
            
            if (maxY > 1.0)
            {
                var maxYLimit = swtGetMaxFloatLevel(maxY);
                var minYLimit = swtGetMinFloatLevel(minY);
                var intervalYLimit = swtGetMinFloatLevel((maxYLimit - minYLimit) / 5.0);
            
                var chart = new CanvasJS.Chart("chartContainer", {
                    zoomEnabled: false,
                    animationEnabled: true,
                    title: {
                        text: graphTitle
                    },
                    subtitles:[
                    {
                        text: "Unit: " + json.testUnit,
                        fontSize: 15,
                        horizontalAlign: "left",
                        padding: 8
                    }
                    ],
                    axisY: {
                        valueFormatString: "0.0 ",

                        //maximum: maxVal * 2.0,
                        //interval: maxVal * 2.0 / 5.0,
                        
                        maximum: swtGetMaxFloatLevel(maxY),
                        minimum: swtGetMinFloatLevel(minY),
                        interval: intervalYLimit,
                        interlacedColor: "#F5F5F5",
                        gridColor: "#D7D7D7",
                        tickColor: "#D7D7D7"
                    },
                    axisX: {
                       //interval: 1,
                       //intervalType: "number",
                    },
                    theme: "theme2",
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        verticalAlign: "bottom",
                        horizontalAlign: "center",
                        fontSize: 15,
                        fontFamily: "Lucida Sans Unicode"
                    },
                    data: testVal,
                    legend: {
                        cursor: "pointer",
                        itemclick: function (e) {
                            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                            }
                            else {
                                e.dataSeries.visible = true;
                            }
                            chart.render();
                        }
                    }
                });

                chart.render();
            }
            else
            {
                //alert("");
            }

        }
    });
}

function swtTestOptionChanged(_caller)
{
    var t1 = $("#testNum").val();
    var testNum = parseInt(t1);
    
    var b1 = false;
    var n1 = parseInt($("#apiNum").val());
    for (var i = 0; i < n1; i++)
    {
        if ($("#api" + i).is(":checked"))
        {
            b1 = true;
            break;
        }
    }
    if (b1 == false)
    {
        //$("#apiCompCheck").prop("checked", true);
        for (var i = 0; i < n1; i++)
        {
            //$("#api" + i).prop("checked", true);
        }
    }
    else
    {
        if (_caller == "api")
        {
            $("#apiCompCheck").prop("checked", false);
        }
    }

    b1 = false;
    n1 = parseInt($("#gpuNum").val());
    for (var i = 0; i < n1; i++)
    {
        if ($("#gpu" + i).is(":checked"))
        {
            b1 = true;
            break;
        }
    }
    if (b1 == false)
    {
        //$("#gpuCompCheck").prop("checked", true);
        for (var i = 0; i < n1; i++)
        {
            //$("#gpu" + i).prop("checked", true);
        }
    }
    else
    {
        if (_caller == "gpu")
        {
            $("#gpuCompCheck").prop("checked", false);
        }
    }
    
    b1 = false;
    n1 = parseInt($("#sysNum").val());
    for (var i = 0; i < n1; i++)
    {
        if ($("#sys" + i).is(":checked"))
        {
            b1 = true;
            break;
        }
    }
    if (b1 == false)
    {
        //$("#sysCompCheck").prop("checked", true);
        for (var i = 0; i < n1; i++)
        {
            //$("#sys" + i).prop("checked", true);
        }
    }
    else
    {
        if (_caller == "sys")
        {
            $("#sysCompCheck").prop("checked", false);
        }
    }
    
    $("#btnNeedResfresh").show();
    
    //for (var i = 0; i < testNum; i++)
    //{
    //    swtGetTestData2(i, -1);
    //}
}

function swtManualRefreshGraphs()
{
    var t1 = $("#testNum").val();
    var testNum = parseInt(t1);
    
    for (var i = 0; i < testNum; i++)
    {
        swtGetTestData2(i, -1, testNum);
    }
    
    $("#btnNeedResfresh").hide();
}

function swtGetSubTestData2(event, ui)
{
    swtGetTestData2(event.data.testPosID, ui.value, 1);
    //console.log("evt: " + event.type);
    //console.log("pos: " + ui.value);
}

function swtIncDecSubTest(_tag, _testPosID, _step)
{

    var maxVal = $( "#" + _tag + _testPosID ).slider( "option", "max" );
    var curVal = $( "#" + _tag + _testPosID ).slider( "option", "value" );
    var nextVal = curVal + _step;
    nextVal = nextVal < 0 ? 0 : nextVal;
    nextVal = nextVal > maxVal ? maxVal : nextVal;
    
    $( "#subTestChoice" + _testPosID ).slider("option", "value", nextVal);
    
    swtGetTestData2(_testPosID, nextVal, 1);
}

function swtGetTestData2(_testPosID, _subTestPosID, _updateNum)
{
    //var t1 = $("#cardList").val();
    //var t2 = $("#testList").val();
    
    if (_updateNum > 1)
    {
        if (_testPosID >= _updateNum)
        {
            return;
        }
    }
    
    var t1 = $("#gpuNum").val();
    var cardNum = parseInt(t1);
    t1 = $("#apiNum").val();
    var driverNum = parseInt(t1);
    t1 = $("#sysNum").val();
    var systemNum = parseInt(t1);
    t1 = $("#testIDList").val();
    var testIDList = t1.split(",");
    //var testIDList = swtTestIDListSet[_testPosID].split(",");
    t1 = $("#cardIDList").val();
    var cardIDList = t1.split(",");
    t1 = $("#cardNameList").val();
    var cardNameList = t1.split(",");
    
    console.log("123cnl2--" + t1);
    
    var subTestKeyWord = $("#subTestFilter" + _testPosID).val();
    
    // get selected card ID
    var curCardIDList = [];
    var curCardPosIDList = [];
    var curHasCardPosIDMap = {};
    for (var i = 0; i < cardIDList.length; i++)
    {
        var b1 = $("#gpu" + i).is(":checked");
        if (b1)
        {
            curCardIDList.push(cardIDList[i]);
            curCardPosIDList.push(i);
            curHasCardPosIDMap[i] = true;
        }
    }
    
    var curSystemNameMap = {};
    for (var i = 0; i < systemNum; i++)
    {
        var b1 = $("#sys" + i).is(":checked");
        var t1 = $("#sys" + i).val();
        if (b1)
        {
            curSystemNameMap[t1] = true;
        }
    }

    var curDriverPosIDList = [];
    var curDriverPosIDMap = {};
    for (var i = 0; i < driverNum; i++)
    {
        var b1 = $("#api" + i).is(":checked");
        if (b1)
        {
            curDriverPosIDList.push(i);
            curDriverPosIDMap[i] = true;
        }
    }
    
    var curCardIDListString = swtImplode(curCardIDList, ",");
    
    $.post("../phplibs/getInfo/swtGetTestData2.php", 
    {
        cardIDList:      curCardIDListString,
        testID:          testIDList[_testPosID],
        subTestPosID:    (_subTestPosID == -1 ? _subTestPosID : (_subTestPosID - 1)),
        subTestKeyWord : subTestKeyWord
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //swtGetTestData2(_testPosID + 1, _subTestPosID, _updateNum);
            //alert(json.errorMsg);
            //console.log(data);
            var cardNum = json.uniqueCardNameList.length;
            var umdNum = json.uniqueDriverNameList.length;
            var machineNum = json.uniqueMachineIDList.length;
            var batchNum = json.batchTimeList.length;
            
            var curMachineIDMap = {};
            for (var i = 0; i < json.uniqueMachineIDList.length; i++)
            {
                curMachineIDMap[i] = json.uniqueMachineIDList[i];
            }
            var curSystemMachineIDMap = {};
            for (var i = 0; i < json.sysNameList.length; i++)
            {
                if (json.machineIDList[i] > 200000000)
                {
                    continue;
                }
                curSystemMachineIDMap[json.machineIDList[i]] = json.sysNameList[i];
            }
            var curCardMachineIDMap = {};
            for (var i = 0; i < json.cardIDList.length; i++)
            {
                if (json.machineIDList[i] > 200000000)
                {
                    continue;
                }
                curCardMachineIDMap[json.machineIDList[i]] = json.cardIDList[i];
            }
            var curCardPosIDMap = {};
            // json.uniqueCardIDList.length
            for (var i = 0; i < cardIDList.length; i++)
            {
                curCardPosIDMap[cardIDList[i]] = i;
            }
            
            var subTestNum = json.subTestNum;
            //$( "#subTestChoice" ).slider();
            if (_subTestPosID == -1)
            {
                $( "#subTestChoice" + _testPosID ).slider("option", "value", 0);
            }
            $( "#subTestChoice" + _testPosID ).slider("option", "max", subTestNum + 1);
            //$( "#subTestChoice" ).on( "slidestop", swtGetSubTestData );
            
            var testVal = [];
            //swtTestVal = [];
            
            var umdNumList = [];
            var umdAccumNumList = [];
            var allUmdNum = 0;
            var usedUmdNameList = [];
            var machineConfigMap = {};
            for (var k = 0; k < machineNum; k++)
            {
                umdNumList.push(0);
                umdAccumNumList.push(0);
                
                var machineID = curMachineIDMap[k];
                var systemName = curSystemMachineIDMap[machineID];
                var cardID = curCardMachineIDMap[machineID];
                var cardPosID = curCardPosIDMap[cardID];
                var isUbunu = systemName.search(/ubuntu/i) != -1;
                //if (curHasCardPosIDMap[k])
                if ((curSystemNameMap[systemName]  == true) &&
                    (curHasCardPosIDMap[cardPosID] == true))
                {
                    for (var j = 0; j < umdNum; j++)
                    {
                        var umdName = json.uniqueDriverNameList[j];
                        var isVulkan = umdName.search(/vulkan/i) != -1;
                        if (curDriverPosIDMap[j])
                        {
                            var n1 = k * umdNum + j;
                            if ((k < json.resultIDList[0].length) &&
                                (json.resultIDList[0][n1] < 200000000) &&
                                (isUbunu ? isVulkan : true))
                            {
                                
                                umdNumList[k]++;
                                allUmdNum++;
                                umdAccumNumList[k] = allUmdNum;
                                usedUmdNameList.push(umdName);
                                // json.uniqueCardNameList
                                var t1 = cardNameList[cardPosID] + "-" + systemName + "-" + json.uniqueDriverNameList[j];
                                
                                machineConfigMap[t1] = testVal.length;
                                
                                var testValSec = { type:          "line",
                                                   lineThickness: 3,
                                                   axisYType:     "primary",
                                                   showInLegend:  true,
                                                   name:          t1,
                                                   dataPoints:    []};
                                                   
                                testVal.push(testValSec);
                            }
                        }
                    }
                }
            }
            //console.log("uuu: " + testVal.length);
            
            var maxVal = 0.0;
            var minVal = Number.MAX_VALUE;
            
            var batchMachineIDMapList = [];
            var batchMachineIDMapList = [];
            
            
            var totalSlotNum = 0;
            for (var i = 0; i < batchNum; i++)
            {
                console.log("---5: " + testVal.length);
                if (testVal.length == 0)
                {
                    // if no data selected
                    continue;
                }
                var tmpTime = "" + json.batchTimeList[i];
                var usedCardNum = 0;
                var usedSlotNum = 0;
                
                for (var k = 0; k < json.resultIDList[i].length; k++)
                {
                    
                    var d1 = new Date(Date.parse(tmpTime.replace(/-/g, "/")));
                    var t1 = d1.toDateString();
                    
                    
                    f1 = parseFloat(json.testValList[totalSlotNum++]);

                    f1 = f1 == null ? 0.0 : f1;
                    f1 = isNaN(f1) ? 0.0 : f1;
                    var tmpDataSec = {x: batchNum - i - 1,
                                      y: f1,
                                      label: t1};
                                      
                    
                    //var t3 = json.uniqueCardNameList[cardPosID] + "-" + systemName + "-" + json.uniqueDriverNameList[j];

                    var t3 = json.cardNameListAll[i][k] + "-" + json.sysNameListAll[i][k] + "-" + json.driverNameListAll[i][k];
                    
                    var n3 = machineConfigMap[t3];
                    
                    console.log("123--t" + t3);
                    console.log("123--n" + n3);
                    
                    if (n3 == undefined)
                    {
                        continue;
                    }
                    testVal[n3].dataPoints.push(tmpDataSec);

                    maxVal = Math.max(maxVal, tmpDataSec.y);
                    minVal = Math.min(minVal, tmpDataSec.y);
                    
                }
            }
            
            
            
            /*
            var totleMachineNum = 0;
            for (var i = 0; i < batchNum; i++)
            {
                console.log("---5: " + testVal.length);
                if (testVal.length == 0)
                {
                    // if no data selected
                    continue;
                }
                var tmpTime = "" + json.batchTimeList[i];
                var usedCardNum = 0;
                var usedSlotNum = 0;
                // json.machineIDListAll[i].length
                //for (var k = 0; k < machineNum; k++)
                for (var k = 0; k < Math.floor(json.resultIDList[i].length / 3); k++)
                {
                    var machineID = curMachineIDMap[k];
                    var systemName = curSystemMachineIDMap[machineID];
                    var isUbunu = systemName.search(/ubuntu/i) != -1;
                    var cardID = curCardMachineIDMap[machineID];
                    var cardPosID = curCardPosIDMap[cardID];
                    if ((curSystemNameMap[systemName]  == true) &&
                        (curHasCardPosIDMap[cardPosID] == true))
                    {
                        var usedDriverNum = 0;
                        for (var j = 0; j < umdNum; j++)
                        {
                            var umdName = json.uniqueDriverNameList[j];
                            var isVulkan = umdName.search(/vulkan/i) != -1;
                            if (curDriverPosIDMap[j])
                            {
                                var d1 = new Date(Date.parse(tmpTime.replace(/-/g, "/")));
                                var t1 = d1.toDateString();

                                
                                var f1 = 0.0;
                                var n1 = k * umdNum + j;
                                if ((k < json.resultIDList[i].length) &&
                                    (json.resultIDList[i][n1] < 200000000) &&
                                    (isUbunu ? isVulkan : true))
                                {
                                    f1 = parseFloat(json.testValList[totleMachineNum * umdNum + k * umdNum + j]);
                                    //f1 = parseFloat(json.testValList[i * machineNum * umdNum + k * umdNum + j]);
                                    //f1 = parseFloat(json.testValList[totleSlotNum]);

                                    f1 = f1 == null ? 0.0 : f1;
                                    f1 = isNaN(f1) ? 0.0 : f1;
                                    var tmpDataSec = {x: batchNum - i - 1,
                                                      y: f1,
                                                      label: t1};
                                                      
                                    
                                    //var n2 = usedCardNum * curDriverPosIDList.length + usedDriverNum;
                                    //if (n2 >= testVal.length)
                                    //{
                                    //    continue;
                                    //}
                                    //testVal[n2].dataPoints.push(tmpDataSec);
                                    //testVal[umdAccumNumList[k] - umdNumList[k] + j].dataPoints.push(tmpDataSec);
                                    
                                    var t3 = json.uniqueCardNameList[cardPosID] + "-" + systemName + "-" + json.uniqueDriverNameList[j];
                                    
                                    var n3 = machineConfigMap[t3];
                                    
                                    console.log("123--t" + t3);
                                    console.log("123--n" + n3);
                                    
                                    if (n3 == undefined)
                                    {
                                        continue;
                                    }
                                    testVal[n3].dataPoints.push(tmpDataSec);
                                    //testVal[usedSlotNum++].dataPoints.push(tmpDataSec);
                                    
                                    maxVal = Math.max(maxVal, tmpDataSec.y);
                                    minVal = Math.min(minVal, tmpDataSec.y);
                                    usedDriverNum++;
                                }
                            }
                            //totleSlotNum++;
                        }
                        usedCardNum++;
                    }
                    totleMachineNum++;
                }
            }
            
            //*/
            
            var maxY = maxVal + ((maxVal - minVal) * 0.0);
            var minY = minVal - ((maxVal - minVal) * 0.0);
            minY = Math.max(minY, 0.0);
            
            var graphTitle = json.testName;
            if ((json.subTestName != null) && (json.subTestName.length > 0))
            {
                graphTitle += " - ";
                graphTitle += json.subTestName;
            }
            
            //if (maxY > 1.0)
            {
                var maxYLimit = swtGetMaxFloatLevel(maxY);
                var minYLimit = swtGetMinFloatLevel(minY);
                var intervalYLimit = swtGetMinFloatLevel((maxYLimit - minYLimit) / 5.0);
                maxYLimit = maxYLimit < 1.0 ? 1.0 : maxYLimit;
                minYLimit = minYLimit < 0.0 ? 0.0 : minYLimit;
                intervalYLimit = intervalYLimit < 1.0 ? 1.0 : intervalYLimit;
                if ((maxYLimit - minYLimit) < 1.0)
                {
                    maxYLimit = minYLimit + 5.0
                }
            
            
                var chart = new CanvasJS.Chart("chartContainer" + _testPosID, {
                    zoomEnabled: false,
                    animationEnabled: true,
                    title: {
                        text: graphTitle
                    },
                    axisY: {
                        valueFormatString: "0.0 ",
                        
                        maximum: maxYLimit,
                        minimum: minYLimit,
                        interval: intervalYLimit,
                        interlacedColor: "#F5F5F5",
                        gridColor: "#D7D7D7",
                        tickColor: "#D7D7D7"
                    },
                    subtitles:[
                    {
                        text: "Unit: " + json.testUnit,
                        fontSize: 15,
                        horizontalAlign: "left",
                        padding: 8
                    }
                    ],
                    theme: "theme2",
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        verticalAlign: "bottom",
                        horizontalAlign: "left",
                        fontSize: 15,
                        fontFamily: "Lucida Sans Unicode"
                    },
                    data: testVal,
                    legend: {
                        cursor: "pointer",
                        itemclick: function (e) {
                            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                            }
                            else {
                                e.dataSeries.visible = true;
                            }
                            chart.render();
                        }
                    }
                });

                chart.render();
                //*/
            }

            
        }
    });
}

function swtGetTestListCodeBatch(_batchID, _apiCompTag, _gpuCompTag, _graphListTag)
{
    var barWidth = $("#pageRightPart").width() * 0.95;
    //alert(barWidth);
    
    $.post("../phplibs/getInfo/swtGetTestListBatch.php", 
    {
        batchID:    _batchID
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            var testSlotCode = "";

            testSlotCode += "<p><div id=\"loadingPic\"><img src=\"../images/loading.gif\" width=\"40px\" height=\"40px\" /></div><div id=\"chartContainer01\" style=\"width: " + barWidth + "px; height: 300px;display: inline-block;\"></div></p>";

            var gpuListCode = "<legend>GPU Selection</legend>";
            
            for (var i = 0; i < json.uniqueCardNameList.length; i++)
            {
                var t1 = "";
                if (i == 0)
                {
                    t1 = "checked=\"checked\"";
                }
                gpuListCode += "<input id=\"gpu01\" name=\"gpu01\" " +
                               "type=\"radio\" value=\"" + json.uniqueCardIDList[i] + "\"  onchange=\"swtGetTestDataBatch(" + _batchID + ");\" " + t1 + " ></input>";
                gpuListCode += "&nbsp" + json.uniqueCardNameList[i] + "</br>";
            }

            //console.log("///: " + gpuListCode);
            
            //$("#" + _apiCompTag).html(apiListCode);
            $("#" + _gpuCompTag).html(gpuListCode);
            $("#" + _graphListTag).html(testSlotCode);
            
            // save api num & gpu num for later use
            $("#apiNum").val(json.uniqueDriverNameList.length);
            $("#gpuNum").val(json.uniqueCardNameList.length);
            $("#testNum").val(json.testNameList.length);
            
            var t1 = swtImplode(json.testIDList, ",");
            var t2 = swtImplode(json.uniqueCardIDList, ",");
            $("#testIDList").val(t1);
            $("#cardIDList").val(t2);
            
            swtGetTestDataBatch(_batchID);
        }
        
    });
}

function swtGetTestDataBatch(_batchID)
{
    //var t1 = $("#cardList").val();
    //var t2 = $("#testList").val();
    
    var t1 = $("#gpuNum").val();
    var cardNum = parseInt(t1);
    t1 = $("#apiNum").val();
    var driverNum = parseInt(t1);
    t1 = $("#testIDList").val();
    var testIDList = t1.split(",");
    t1 = $("#cardIDList").val();
    var cardIDList = t1.split(",");
    
    // get selected card ID
    var curCardIDList = [];
    var curCardPosID = 0;
    var curCardID = $("input[name='gpu01']:checked").val();
    for (var i = 0; i < cardIDList.length; i++)
    {
        if (cardIDList[i] == curCardID)
        {
            curCardPosID = i;
        }
    }
    $("#loadingPic").css("display", "");
    //console.info(cardIDList);
    
    $.post("../phplibs/getInfo/swtGetTestDataBatch.php", 
    {
        batchID:      _batchID
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            //console.log(data);
            var cardNum = json.uniqueCardNameList.length;
            var umdNum = json.uniqueDriverNameList.length;
            var testNum = json.testNameList.length;
            
            var testVal = [];

            for (var j = 0; j < umdNum; j++)
            {
                var t1 = json.uniqueDriverNameList[j];
                var testValSec = { type:          "column",
                                   lineThickness: 3,
                                   axisYType:     "primary",
                                   showInLegend:  true,
                                   name:          t1,
                                   dataPoints:    []};
                                   
                testVal.push(testValSec);
            }

            
            var maxVal = 0.0;
            var minVal = Number.MAX_VALUE;

            for (var j = 0; j < umdNum; j++)
            {
                for (var m = 0; m < testNum; m++)
                {
                    var t1 = "";
                    if (j == Math.floor(umdNum * 0.5))
                    {
                        t1 = json.testNameList[m];
                    }
                    // real value
                    var f1 = parseFloat(json.testValList[m * cardNum * umdNum + curCardPosID * umdNum + j]);
                    // value of DX11
                    var f2 = parseFloat(json.testValList[m * cardNum * umdNum + curCardPosID * umdNum]);
                    f1 /= f2;
                    f1 = f1 == null ? 0.0 : f1;
                    f1 = isNaN(f1) ? 0.0 : f1;
                    
                    var tmpDataSec = {x: m,
                                      y: f1,
                                      label: t1};
                    //console.log("///: " + t1);
                    testVal[j].dataPoints.push(tmpDataSec);
                    
                    maxVal = Math.max(maxVal, tmpDataSec.y);
                    minVal = Math.min(minVal, tmpDataSec.y);
                }
            }
            
            var maxY = maxVal + ((maxVal - minVal) * 0.5);
            var minY = minVal - ((maxVal - minVal) * 0.5);
            minY = Math.max(minY, 0.0);
            
            var graphTitle = json.uniqueCardNameList[curCardPosID];
            
            //if (maxY > 1.0)
            {
                var maxYLimit = swtGetMaxFloatLevel(maxY);
                var minYLimit = swtGetMinFloatLevel(minY);
                var intervalYLimit = swtGetMinFloatLevel((maxYLimit - minYLimit) / 5.0);
                maxYLimit = maxYLimit < 1.0 ? 1.0 : maxYLimit;
                minYLimit = minYLimit < 0.0 ? 0.0 : minYLimit;
                intervalYLimit = intervalYLimit < 1.0 ? 1.0 : intervalYLimit;
                if ((maxYLimit - minYLimit) < 1.0)
                {
                    maxYLimit = minYLimit + 5.0
                }
            
            
                var chart = new CanvasJS.Chart("chartContainer01", {
                    zoomEnabled: false,
                    animationEnabled: true,
                    title: {
                        text: graphTitle
                    },
                    theme: "theme2",
                    toolTip: {
                        shared: true
                    },
                    axisX:{
                        interval: 1,
                        intervalType: "number",
                        labelAutoFit: false,
                        labelWrap: false,
                        labelAngle: -45
                    },
                    legend: {
                        verticalAlign: "bottom",
                        horizontalAlign: "left",
                        fontSize: 15,
                        fontFamily: "Lucida Sans Unicode"
                    },
                    data: testVal,
                    legend: {
                        cursor: "pointer",
                        itemclick: function (e) {
                            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                            }
                            else {
                                e.dataSeries.visible = true;
                            }
                            chart.render();
                        }
                    }
                });
                $("#loadingPic").css("display", "none");
                chart.render();
                //*/
            }

        }
    });
}

function swtGetTestListCodeBatch2(_batchID, _sysCompTag, _gpuCompTag, _graphListTag)
{
    var barWidth = $("#pageRightPart").width() * 0.95;
    //alert(barWidth);
    
    $.post("../phplibs/getInfo/swtGetTestListBatch.php", 
    {
        batchID:    _batchID
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            var testSlotCode = "";

            var apiNum = json.uniqueDriverNameList.length;
            var machineNum = (json.resultIDList.length) / apiNum;
            
            console.log("---:" + machineNum);
            console.log("---:" + apiNum);
            console.log("---:" + json.resultIDList.length);
            console.info(json.resultIDList);
            var cardNum = json.uniqueCardNameList.length;
            for (var i = 0; i < machineNum; i++)
            {
                testSlotCode += "<p><div id=\"loadingPic" + i + "\"><img src=\"../images/loading.gif\" " +
                                "width=\"40px\" height=\"40px\" /></div>" +
                                "<div id=\"chartContainerFrame" + i + "\" " +
                                "class=\"chartContainerFrame\"><div id=\"chartContainer" + i + "\" " +
                                "style=\"width: " + barWidth + "px; height: 300px;display: inline-block;\"></div>" +
                                "<div id=\"chartContainerDownloadLayer" + i + "\" " +
                                "class=\"chartContainerDownloadLayer\">Download Report</div>" +
                                "</div></p>";
            }
            
            var gpuListCode = "<legend><input id=\"gpuCompCheck\" name=\"gpuCompCheck\" type=\"checkbox\" onchange=\"swtCheckTestInfoOption2('gpuCompCheck');\" checked=\"checked\"></input> GPU Comparison</legend>";
            var sysListCode = "<legend><input id=\"sysCompCheck\" name=\"sysCompCheck\" type=\"checkbox\" onchange=\"swtCheckTestInfoOption2('sysCompCheck');\" checked=\"checked\"></input> System Comparison</legend>";
            
            for (var i = 0; i < json.uniqueCardNameList.length; i++)
            {
                gpuListCode += "<input id=\"gpu" + i + "\" name=\"gpu" + i + "\" " +
                               "type=\"checkbox\" value=\"" + json.uniqueCardIDList[i] + "\"  " +
                               "onchange=\"swtTestOptionChanged2('gpu');\" checked=\"checked\"></input>";
                gpuListCode += "&nbsp" + json.uniqueCardNameList[i] + "</br>";
            }
            
            for (var i = 0; i < json.uniqueSysNameList.length; i++)
            {
                sysListCode += "<input id=\"sys" + i + "\" name=\"sys" + i + "\" " +
                               "type=\"checkbox\" value=\"" + json.uniqueSysNameList[i] + "\"  " +
                               "onchange=\"swtTestOptionChanged2('sys');\" checked=\"checked\"></input>";
                sysListCode += "&nbsp" + json.uniqueSysNameList[i] + "</br>";
            }

            //console.info("3: " + machineNum);
            
            $("#" + _sysCompTag).html(sysListCode);
            $("#" + _gpuCompTag).html(gpuListCode);
            $("#" + _graphListTag).html(testSlotCode);
            
            // save api num & gpu num for later use
            $("#apiNum").val(json.uniqueDriverNameList.length);
            $("#gpuNum").val(json.uniqueCardNameList.length);
            $("#sysNum").val(json.uniqueSysNameList.length);
            $("#testNum").val(json.testNameList.length);
            $("#machineNum").val(machineNum);
            
            var cardIDList = [];
            var sysNameList = [];
            for (var i = 0; i < machineNum; i++)
            {
                cardIDList.push(json.cardIDList[i * apiNum]);
                sysNameList.push(json.sysNameList[i * apiNum]);
            }
            
            var t1 = swtImplode(json.testIDList, ",");
            var t2 = swtImplode(json.uniqueCardIDList, ",");
            var t3 = swtImplode(json.uniqueSysNameList, ",");
            var t4 = swtImplode(cardIDList, ",");
            var t5 = swtImplode(sysNameList, ",");
            $("#testIDList").val(t1);
            $("#uniqueCardIDList").val(t2);
            $("#uniqueSysNameList").val(t3);
            $("#cardIDList").val(t4);
            $("#sysNameList").val(t5);
            
            var amdMachineList = [];
            var nvMachineList = [];
            var allTagList = [];
            for (var i = 0; i < machineNum; i++)
            {
                //var cardPosID = Math.floor(i % cardNum);
                //var sysPosID = Math.floor(i / cardNum);
                //swtGetTestDataBatch2(_batchID, i, i, cardPosID, sysPosID);
                var cardName = json.cardNameList[i * apiNum];
                var isNVCard = cardName.search(/gt/i);
                if (isNVCard == -1)
                {
                    // if amd card
                    amdMachineList.push(i);
                    console.log("333: " + cardName + ", " + i);
                }
                else
                {
                    // if nv card
                    nvMachineList.push(i);
                    console.log("222: " + cardName + ", " + i);
                }
            }
            var tagID = 0;
            for (var i = 0; i < amdMachineList.length; i++)
            {
                swtGetTestDataBatch2(_batchID, amdMachineList[i], tagID++);
                allTagList.push(amdMachineList[i]);
            }
            for (var i = 0; i < nvMachineList.length; i++)
            {
                swtGetTestDataBatch2(_batchID, nvMachineList[i], tagID++);
                allTagList.push(nvMachineList[i]);
            }
            var t1 = swtImplode(allTagList, ",");
            console.log("555: " + t1);
            $("#tagIDList").val(t1);
        }
        
    });
}

function swtGetTestDataBatch2(_batchID, _tagID, _divID)
{
    var t1 = $("#gpuNum").val();
    var cardNum = parseInt(t1);
    t1 = $("#apiNum").val();
    var driverNum = parseInt(t1);
    t1 = $("#testIDList").val();
    var testIDList = t1.split(",");
    t1 = $("#cardIDList").val();
    var cardIDList = t1.split(",");
    t1 = $("#sysNameList").val();
    var sysNameList = t1.split(",");
    t1 = $("#uniqueCardIDList").val();
    var uniqueCardIDList = t1.split(",");
    t1 = $("#uniqueSysNameList").val();
    var uniqueSysNameList = t1.split(",");
    
    // get selected card ID
    var curCardIDList = [];
    var curCardPosID = 0;
    var curCardID = $("input[name='gpu01']:checked").val();
    for (var i = 0; i < cardIDList.length; i++)
    {
        if (cardIDList[i] == curCardID)
        {
            curCardPosID = i;
        }
    }
    $("#loadingPic" + _tagID).css("display", "");
    //console.info(cardIDList);
    
    $.post("../phplibs/getInfo/swtGetTestDataBatch.php", 
    {
        batchID:      _batchID
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            //console.log(data);
            var cardNum = json.uniqueCardNameList.length;
            var umdNum = json.uniqueDriverNameList.length;
            var testNum = json.testNameList.length;
            var sysNum = json.uniqueSysNameList.length;
            var machineNum = (json.resultIDList.length) / umdNum;
            var cardName = json.cardNameList[_tagID * umdNum];
            var sysName = json.sysNameList[_tagID * umdNum];
            var reportFileName = json.reportFileNameList[_tagID * umdNum];
            var reportFolderName = json.reportFolder;
            
            //console.log("--: " + sysName);
            var isUbunu = sysName.search(/ubuntu/i);
            var firstValidDriverName = "";
            var firstValidDriverID = 0;
            var tagIDList = [];
            var cardWinTagID = 0;
            for (var j = 0; j < umdNum; j++)
            {
                if ((json.resultIDList[_tagID * umdNum + j] < 200000000) &&
                    ((json.uniqueDriverNameList[j] == "Vulkan") || (json.uniqueDriverNameList[j] == "vulkan")))
                {
                    // if not PHP_INT_MAX
                    firstValidDriverName = json.uniqueDriverNameList[j];
                    firstValidDriverID = j;
                    break;
                }
            }

            for (var j = 0; j < machineNum; j++)
            {
                if (json.cardNameList[j * umdNum] == cardName)
                {
                    tagIDList.push(j);
                    var t1 = json.sysNameList[j * umdNum];
                    var n1 = t1.search(/win/i);
                    if (n1 != -1)
                    {
                        cardWinTagID = j;
                        tagIDList[tagIDList.length - 1] = tagIDList[0];
                        tagIDList[0] = j;
                    }
                }
            }
            
            var testVal = [];

            if (isUbunu == -1)
            {
                for (var j = 0; j < umdNum; j++)
                {
                    var t1 = json.uniqueDriverNameList[j];
                    var testValSec = { type:          "column",
                                       lineThickness: 3,
                                       axisYType:     "primary",
                                       showInLegend:  true,
                                       name:          t1,
                                       dataPoints:    []};
                                       
                    testVal.push(testValSec);
                }
            }
            else
            {
                // is ubuntu
                for (var j = 0; j < tagIDList.length; j++)
                {
                    var t1 = firstValidDriverName + " - " + json.sysNameList[tagIDList[j] * umdNum];
                    var testValSec = { type:          "column",
                                       lineThickness: 3,
                                       axisYType:     "primary",
                                       showInLegend:  true,
                                       name:          t1,
                                       dataPoints:    []};
                                       
                    testVal.push(testValSec);
                }
            }

            
            var maxVal = 0.0;
            var minVal = Number.MAX_VALUE;

            if (isUbunu == -1)
            {
                for (var j = 0; j < umdNum; j++)
                {
                    for (var m = 0; m < testNum; m++)
                    {
                        var t1 = "";
                        if (j == Math.floor(umdNum * 0.5))
                        {
                            t1 = json.testNameList[m];
                        }
                        // real value
                        var f1 = parseFloat(json.testValList[m * machineNum * umdNum + _tagID * umdNum + j]);
                        // value of DX11
                        var f2 = 0;//parseFloat(json.testValList[m * machineNum * umdNum + _tagID * umdNum]);
                        for (var k = 0; k < umdNum; k++)
                        {
                            f2 = parseFloat(json.testValList[m * machineNum * umdNum + _tagID * umdNum + k]);
                            if (f2 > 0)
                            {
                                break;
                            }
                        }
                        if (f2 == 0)
                        {
                            // if invalid
                        }
                        else
                        {
                            f1 /= f2;
                            f1 = f1 > 10 ? 1.0 : f1;
                        }
                        f1 = f1 == null ? 0.0 : f1;
                        f1 = isNaN(f1) ? 0.0 : f1;
                        
                        var tmpDataSec = {x: m,
                                          y: f1,
                                          label: t1};
                        //console.log("///: " + t1);
                        testVal[j].dataPoints.push(tmpDataSec);
                        
                        maxVal = Math.max(maxVal, tmpDataSec.y);
                        minVal = Math.min(minVal, tmpDataSec.y);
                    }
                }
            }
            else
            {
                // is ubuntu
                for (var j = 0; j < tagIDList.length; j++)
                {
                    for (var m = 0; m < testNum; m++)
                    {
                        var t1 = "";
                        t1 = json.testNameList[m];
                        // real value
                        var f1 = parseFloat(json.testValList[m * machineNum * umdNum + tagIDList[j] * umdNum + firstValidDriverID]);
                        // value of DX11
                        var f2 = parseFloat(json.testValList[m * machineNum * umdNum + cardWinTagID * umdNum + firstValidDriverID]);
                        if (f2 == 0)
                        {
                            //f1 = 0;
                        }
                        else
                        {
                            f1 /= f2;
                            f1 = f1 > 10 ? 1.0 : f1;
                        }
                        f1 = f1 == null ? 0.0 : f1;
                        f1 = isNaN(f1) ? 0.0 : f1;
                        
                        var tmpDataSec = {x: m,
                                          y: f1,
                                          label: t1};
                        //console.log("///: " + t1);
                        testVal[j].dataPoints.push(tmpDataSec);
                        
                        maxVal = Math.max(maxVal, tmpDataSec.y);
                        minVal = Math.min(minVal, tmpDataSec.y);
                    }
                }
            }
            
            var maxY = maxVal + ((maxVal - minVal) * 0.5);
            var minY = minVal - ((maxVal - minVal) * 0.5);
            minY = Math.max(minY, 0.0);
            
            //var graphTitle = json.uniqueCardNameList[curCardPosID];
            var graphTitle = json.cardNameList[_tagID * umdNum] + " - " +
                             json.sysNameList[_tagID * umdNum];
            
            //if (maxY > 1.0)
            {
                var maxYLimit = swtGetMaxFloatLevel(maxY);
                var minYLimit = swtGetMinFloatLevel(minY);
                var intervalYLimit = swtGetMinFloatLevel((maxYLimit - minYLimit) / 5.0);
                maxYLimit = maxYLimit < 1.0 ? 1.0 : maxYLimit;
                minYLimit = minYLimit < 0.0 ? 0.0 : minYLimit;
                intervalYLimit = intervalYLimit < 1.0 ? 1.0 : intervalYLimit;
                if ((maxYLimit - minYLimit) < 1.0)
                {
                    maxYLimit = minYLimit + 5.0
                }

                var t1 = "<a href=\"" + reportFolderName + "/" + reportFileName + "\">Download Report</a>";
                $("#chartContainerDownloadLayer" + _divID).html(t1);
            
                var chart = new CanvasJS.Chart("chartContainer" + _divID, {
                    zoomEnabled: false,
                    animationEnabled: true,
                    title: {
                        text: graphTitle
                    },
                    theme: "theme2",
                    toolTip: {
                        shared: true
                    },
                    axisX:{
                        interval: 1,
                        intervalType: "number",
                        labelAutoFit: false,
                        labelWrap: false,
                        labelAngle: -45
                    },
                    legend: {
                        verticalAlign: "bottom",
                        horizontalAlign: "left",
                        fontSize: 15,
                        fontFamily: "Lucida Sans Unicode"
                    },
                    data: testVal,
                    legend: {
                        cursor: "pointer",
                        itemclick: function (e) {
                            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                            }
                            else {
                                e.dataSeries.visible = true;
                            }
                            chart.render();
                        }
                    }
                });
                $("#loadingPic" + _tagID).css("display", "none");
                chart.render();
                //*/
            }
        }
    });
}

function swtCheckTestInfoOption2(_checkTag)
{
    var b1 = $("#" + _checkTag).is(":checked");

    if (_checkTag == "gpuCompCheck")
    {
        var n1 = parseInt($("#gpuNum").val());
        //console.log(n1);
        for (var i = 0; i < n1; i++)
        {
            $("#gpu" + i).prop("checked", b1);
        }
    }
    else if (_checkTag == "sysCompCheck")
    {
        var n1 = parseInt($("#sysNum").val());
        //console.log(n1);
        for (var i = 0; i < n1; i++)
        {
            $("#sys" + i).prop("checked", b1);
        }
    }
    swtTestOptionChanged2(_checkTag);
}

function swtTestOptionChanged2(_caller)
{
    var t1 = $("#gpuNum").val();
    var cardNum = parseInt(t1);
    t1 = $("#apiNum").val();
    var driverNum = parseInt(t1);
    t1 = $("#machineNum").val();
    var machineNum = parseInt(t1);
    t1 = $("#testIDList").val();
    var testIDList = t1.split(",");
    t1 = $("#cardIDList").val();
    var cardIDList = t1.split(",");
    t1 = $("#sysNameList").val();
    var sysNameList = t1.split(",");
    t1 = $("#uniqueCardIDList").val();
    var uniqueCardIDList = t1.split(",");
    t1 = $("#uniqueSysNameList").val();
    var uniqueSysNameList = t1.split(",");
    t1 = $("#tagIDList").val();
    console.log("--: " + t1);
    var allTagList = t1.split(",");
    
    var cardIDListUsedMap = {};
    var sysNameListUsedMap = {};
    var allTagListMap = {};
    var b1 = false;
    for (var i = 0; i < uniqueCardIDList.length; i++)
    {
        if ($("#gpu" + i).is(":checked"))
        {
            b1 = true;
            cardIDListUsedMap[uniqueCardIDList[i]] = true;
        }
    }
    if (b1 == true)
    {
        if (_caller == "gpu")
        {
            $("#gpuCompCheck").prop("checked", false);
        }
    }
    
    b1 = false;
    for (var i = 0; i < uniqueSysNameList.length; i++)
    {
        if ($("#sys" + i).is(":checked"))
        {
            b1 = true;
            sysNameListUsedMap[uniqueSysNameList[i]] = true;
        }
    }
    if (b1 == true)
    {
        if (_caller == "sys")
        {
            $("#sysCompCheck").prop("checked", false);
        }
    }
    for (var i = 0; i < allTagList.length; i++)
    {
        allTagListMap[allTagList[i]] = i;
    }
    
    console.log("5:--");
    console.info(machineNum);
    //console.info(cardIDListUsedMap);
    //console.info(sysNameList);
    
    for (var i = 0; i < machineNum; i++)
    {
        cardID = cardIDList[i];
        sysName = sysNameList[i];
        if ((cardIDListUsedMap[cardID]   == true) &&
            (sysNameListUsedMap[sysName] == true))
        {
            // machine selected
            $("#chartContainerFrame" + allTagListMap[i]).show();
            console.log("1: " + i);
            console.log("cardID: " + cardID);
            console.log("sysName: " + sysName);
        }
        else
        {
            $("#chartContainerFrame" + allTagListMap[i]).hide();
            console.log("2: " + i);
            console.log("cardID: " + cardID);
            console.log("sysName: " + sysName);
        }
        //swtGetTestData2(i, -1);
    }

    
}

function swtSetBatchTime(_batchTag, _dateTag)
{
    var t1 = $("#" + _batchTag).val();
    var t2 = $("#" + _dateTag).val();
    
    if (t1.length == 0)
    {
        alert("please fill in batchID");
        return;
    }
    if (t2.length == 0)
    {
        alert("please select target date");
        return;
    }
    
    $.post("../phplibs/generalLibs/setBatchTime.php", 
    {
        batchID:   t1,
        tagetDate: t2
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        //console.log(data);
        //alert(json.errorMsg);
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg );
            alert("time set to: " + json.insertTime);
        }
        
    });
}