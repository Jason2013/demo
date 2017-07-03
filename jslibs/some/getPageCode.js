var swtBatchListDayBack = 0;
var swtReportBatchSelection = [];
var swtReportBatchState = [];
var swtReportBatchListHtml = "";

function swtCheckReportBatchSelection(_batchID, _id)
{
    //console.log("==");
    var tmpList = [];
    var b1 = false;
    for (var i = 0; i < swtReportBatchSelection.length; i++)
    {
        if (swtReportBatchSelection[i] == _batchID)
        {
            b1 = true;
        }
        else
        {
            tmpList.push(swtReportBatchSelection[i]);
        }
    }
    if (b1 == false)
    {
        if ((swtReportBatchState != null) &&
            (_id < swtReportBatchState.length))
        {
            if (swtReportBatchState[_id] == 0)
            {
                $("#checkBox" + _batchID).attr("checked", null);
                alert("batch not finished, can't be selected");
                return;
            }
        }
        tmpList.push(_batchID);
    }
    swtReportBatchSelection = tmpList;
    
    var t1 = swtReportBatchSelection.join(",");
    $.cookie('reportBatchSelection', t1);
    
    //console.log("999: " + $.cookie('reportBatchSelection'));
    
}

function swtClearReportBatchSelection()
{
    $.cookie('reportBatchSelection', "");
    location.reload(true);
}

function swtListReportBatchSelection(_tagID)
{
    var t1 = $.cookie('reportBatchSelection');
    var batchIDList = t1.split(",");
    var t2 = "";
    t2 += "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
    t2 += "<tr class='pageArticleSubject01'>";
    t2 += "<td>batch ID&nbsp&nbsp&nbsp";
    t2 += "</td>";
    t2 += "<td>...";
    t2 += "</td>";
    t2 += "</tr>";
    
    for (var i = 0; i < batchIDList.length; i++)
    {
        t2 += "<tr>";
        t2 += "<td># " + batchIDList[i] + "&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td>...";
        t2 += "</td>";
        t2 += "</tr>";
    }
    t2 += "</table>";
    
    $("#" + _tagID).html(t2);
}

function swtShowBatchMachineList(_batchID, _state)
{
    var t1 = "batchCell" + _batchID;
    if (_state)
    {
        var x1 = $("#" + t1).position().left;
        var y1 = $("#" + t1).position().top;
        var w1 = $("#" + t1).width();
        
        //console.log(y1);
        $("#machineList01").css("left", "" + (parseInt(x1) + parseInt(w1)) + "px");
        $("#machineList01").css("top", "" + y1 + "px");
        $("#machineList01").css("display", "block");
        
        swtGetBatchMachineListHtml("machineList01", _batchID);
    }
    else
    {
        $("#machineList01").css("display", "none");
    }
}

function swtGetTodayTaskListHtml(_tagName, _dayBack)
{
    $.post("phplibs/getInfo/swtGetTaskStateListHtml.php", 
    {
        dayBack: "" + _dayBack
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");
        var t1 = "<p>checking date is " + swtBatchListDayBack + " days back</p>";
        var t2 = "";
        t2 += "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
        t2 += "<tr class='pageArticleSubject01'>";
        t2 += "<td>batch ID&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td>state&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td>insert time&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td>gen report&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "</tr>";
        
        var t3 = "";
        t3 += "</table>";
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            swtReportBatchState = json.batchStateList.slice();
            var tmpList1 = [];
            for (var i = 0; i < json.batchIDList.length; i++)
            {
                var b1 = false;
                for (var j in swtReportBatchSelection)
                {
                    if (swtReportBatchSelection[j] == json.batchIDList[i])
                    {
                        b1 = true;
                        break;
                    }
                }
                var t5 = "";
                if (b1)
                {
                    t5 = "checked=\"checked\"";
                }
                
                var t4 = "background-color: #00ff00;";
                if (json.batchStateList[i] == 0)
                {
                    t4 = "background-color: #ffa000;";
                }
                if (json.batchStateList[i] == 3)
                {
                    t4 = "background-color: #00f0ff;";
                }
                t2 += "<tr >";
                t2 += "<td id=\"batchCell" + json.batchIDList[i] +
                      "\" style=\"background-color: #ffff00;\" " +
                      " onmouseover=\"swtShowBatchMachineList('" + json.batchIDList[i] +
                      "', true);\" onmouseout=\"swtShowBatchMachineList('" + json.batchIDList[i] +
                      "', false);\">" +
                      "&nbsp#&nbsp" + json.batchIDList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td style=\"" + t4 + "\">" + json.batchStateNameList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td>" + json.batchInsertTimeList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td>" + "<input id=\"checkBox" + json.batchIDList[i] + "\" type=\"checkbox\" name=\"" + json.batchIDList[i] +
                               "\" onchange=\"swtCheckReportBatchSelection('" + json.batchIDList[i] + "', " + i + ");\" " + t5 + " >" + "&nbsp&nbsp&nbsp";
                               // 
                t2 += "</td>";
                t2 += "</tr>";
            }
            t2 += t3;
            
            //console.log(t2);
            
            if (t2 != swtReportBatchListHtml)
            {
                $("#" + _tagName).html(t2 + t1);
                swtReportBatchListHtml = t2;
            }
            return;
        }
        else
        {
            t2 += t3;
            $("#" + _tagName).html(t2 + json.errorMsg + t1);
            swtReportBatchListHtml = "";
            return;
        }
    });
}

var swtBatchListPageID = 0;
function swtGetTodayTaskListHtml2(_tagName, _pageTag, _pageID, _batchGroup)
{
    $.post("../phplibs/getInfo/swtGetTaskStateListHtml2.php", 
    {
        pageID:     _pageID,
        reportType: 0,
        batchGroup: _batchGroup
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");
        var t1 = "<p>checking date is " + swtBatchListDayBack + " days back</p>";
        var t2 = "";
        t2 += "<table class=\"batchList04 roundCorner02\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
        t2 += "<tr class=''>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 0% 0%;'>batch ID&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 20% 0%;'>state&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 40% 0%;'>group&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 60% 0%;'>import time&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 80% 0%;'>log path&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "<td class='batchList02 batchList01' style='background-position: 100% 0%;'>delete&nbsp&nbsp&nbsp";
        t2 += "</td>";
        t2 += "</tr>";
        
        var t3 = "";
        t3 += "</table>";
        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            swtReportBatchState = json.batchStateList.slice();
            var tmpList1 = [];
            for (var i = 0; i < json.batchIDList.length; i++)
            {
                var b1 = false;
                for (var j in swtReportBatchSelection)
                {
                    if (swtReportBatchSelection[j] == json.batchIDList[i])
                    {
                        b1 = true;
                        break;
                    }
                }
                var t5 = "";
                if (b1)
                {
                    t5 = "checked=\"checked\"";
                }
                
                var t4 = "background-color: #30f000;";
                if (json.batchStateList[i] == 0)
                {
                    t4 = "background-color: #ffa000;";
                }
                if (json.batchStateList[i] == 3)
                {
                    t4 = "background-color: #00f0ff;";
                }
                t2 += "<tr >";
                t2 += "<td id=\"batchCell" + json.batchIDList[i] +
                      "\" style=\"background-color: #ffff00;\" " +
                      " onmouseover=\"swtShowBatchMachineList('" + json.batchIDList[i] +
                      "', true);\" onmouseout=\"swtShowBatchMachineList('" + json.batchIDList[i] +
                      "', false);\">" +
                      "&nbsp#&nbsp" + json.batchIDList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td class=\"batchList03\" style=\"" + t4 + "\">" + json.batchStateNameList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td class=\"batchList03\" style=\"" + t4 + "\">" + json.batchGroupNameList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td class=\"batchList03\">" + json.batchInsertTimeList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td class=\"batchList03\">" + json.logPathNameList[i] + "&nbsp&nbsp&nbsp";
                t2 += "</td>";
                t2 += "<td>&nbsp&nbsp<button class=\"roundCorner02\" type=\"button\" onclick=\"swtDelBatchesDirect(" +
                      json.batchIDList[i] + ", " + json.batchIDList[i] +
                      ");\">delete</button>&nbsp";
                t2 += "</td>";
                t2 += "</tr>";
            }
            t2 += t3;
            
            var pageNum = Math.floor((json.batchNum + json.pageItemNum - 1) / json.pageItemNum);
            
            var initPagination = function() {
                var num_entries = pageNum;
                // 创建分页
                $("#" + _pageTag).pagination(num_entries, {
                    num_edge_entries: 1, //边缘页数
                    num_display_entries: 5, //主体页数
                    callback: pageselectCallback,
                    items_per_page:1 //每页显示1项
                });
             }();
             
            function pageselectCallback(page_index, jq){
                //var new_content = $("#" + _pageTag + " div.result:eq("+page_index+")").clone();
                //$("#Searchresult").empty().append(new_content); //装载对应分页的内容
                
                swtBatchListPageID = page_index;
                
                //swtGetTodayTaskListHtml2(_tagName, _pageTag, _pageID, _batchGroup);
                
                return false;
            }
            
            if (t2 != swtReportBatchListHtml)
            {
                $("#" + _tagName).html(t2);
                swtReportBatchListHtml = t2;
            }
            return;
        }
        else
        {
            t2 += t3;
            $("#" + _tagName).html(t2 + json.errorMsg);
            swtReportBatchListHtml = "";
            return;
        }
    });
}

function swtCheckReportInfoOption(_checkTag)
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
    swtReportOptionChanged(_checkTag);
}

function swtReportOptionChanged(_caller)
{
    var b1 = false;
    var n1 = parseInt($("#gpuNum").val());
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
    
    swtGetHistoryReportInfo("reportList", "", "");
}

function swtGetHistoryReportInfo(_tagName, _gpuCompTag, _sysCompTag)
{
    $.post("phplibs/getInfo/swtGetHistoryReportInfo.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log("aaabbb: ");
        console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var gpuSelectMap = {};
            var sysSelectMap = {};
            
            if ((_gpuCompTag.length != 0) &&
                (_sysCompTag.length != 0))
            {
                // if null no need to update selection group
                
                var gpuListCode = "<legend><input id=\"gpuCompCheck\" name=\"gpuCompCheck\" type=\"checkbox\" onchange=\"swtCheckReportInfoOption('gpuCompCheck');\" checked=\"checked\"></input> GPU Selection</legend>";
                var sysListCode = "<legend><input id=\"sysCompCheck\" name=\"sysCompCheck\" type=\"checkbox\" onchange=\"swtCheckReportInfoOption('sysCompCheck');\" checked=\"checked\"></input> System Selection</legend>";
                
                for (var i = 0; i < json.allCardNameList.length; i++)
                {
                    gpuListCode += "<input id=\"gpu" + i + "\" name=\"gpu" + i + "\" " +
                                   "type=\"checkbox\" value=\"" + json.allCardNameList[i] + "\"  onchange=\"swtReportOptionChanged('gpu');\" checked=\"checked\"></input>";
                    gpuListCode += "&nbsp" + json.allCardNameList[i] + "</br>";
                }
                
                for (var i = 0; i < json.allSysNameList.length; i++)
                {
                    sysListCode += "<input id=\"sys" + i + "\" name=\"sys" + i + "\" " +
                                   "type=\"checkbox\" value=\"" + json.allSysNameList[i] + "\"  onchange=\"swtReportOptionChanged('sys');\" checked=\"checked\"></input>";
                    sysListCode += "&nbsp" + json.allSysNameList[i] + "</br>";
                }

                $("#" + _gpuCompTag).html(gpuListCode);
                $("#" + _sysCompTag).html(sysListCode);
                
                // save api num & gpu num for later use
                $("#gpuNum").val(json.allCardNameList.length);
                $("#sysNum").val(json.allSysNameList.length);
            
            }
            
            for (var i = 0; i < json.allCardNameList.length; i++)
            {
                if ($("#gpu" + i).is(":checked"))
                {
                    gpuSelectMap[json.allCardNameList[i]] = true;
                }
                else
                {
                    gpuSelectMap[json.allCardNameList[i]] = false;
                }
            }
            
            for (var i = 0; i < json.allSysNameList.length; i++)
            {
                if ($("#sys" + i).is(":checked"))
                {
                    sysSelectMap[json.allSysNameList[i]] = true;
                }
                else
                {
                    sysSelectMap[json.allSysNameList[i]] = false;
                }
            }
            
            var t1 = "<table>";
            t1 += "<tr>";
            t1 += "<td class=\"textStyle05\">PRODUCT";
            t1 += "</td>";
            t1 += "<td>&nbsp&nbsp";
            t1 += "</td>";
            t1 += "<td class=\"textStyle05\">GPU INFORMATION";
            t1 += "</td>";
            t1 += "<td>&nbsp&nbsp";
            t1 += "</td>";
            t1 += "<td class=\"textStyle05\">SYSTEM INFORMATION";
            t1 += "</td>";
            t1 += "</tr>";
            
            t1 += "<tr>";
            t1 += "<td>&nbsp";
            t1 += "</td>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "<td>";
            t1 += "</td>";
            t1 += "</tr>";
            
            for (var i = 0; i < json.batchTimeList.length; i++)
            {
                for (var j = 0; j < json.finalDriverNameList[i].length; j++)
                {
                    if (gpuSelectMap[json.finalCardNameList[i][j]] != true)
                    {
                        continue;
                    }
                    if (sysSelectMap[json.finalSysNameList[i][j]] != true)
                    {
                        continue;
                    }
                    
                    t1 += "<tr class=\"tableCellStyle01\">";
                    t1 += "<td class=\"textStyle06\">";
                    t1 += "" + json.finalCardNameList[i][j] + "<br />";
                    t1 += "(" + json.finalSysNameList[i][j] + ")<br />";
                    t1 += "" + json.batchTimeList[i];
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    
                    var t2 = "N/A, ";
                    if ((json.finalSClockNameList[i][j] != null) &&
                        (json.finalMClockNameList[i][j] != null))
                    {
                        t2 = "" + json.finalSClockNameList[i][j] + "E/";
                        t2 += "" + json.finalMClockNameList[i][j] + "M";
                    }
                    t1 += "<div class=\"textStyle07\" style=\"float: left;\">" + t2 + "</div>, ";
                    t1 += "" + json.finalGpuMemNameList[i][j] + ", ";
                    t1 += "" + json.finalMainLineNameList[i][j] + ", ";
                    t1 += "" + json.finalChangeListNumList[i][j] + "<br />";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td class=\"textStyle02\">";
                    t1 += "" + json.finalCpuNameList[i][j] + ", ";
                    t1 += "" + json.finalDriverNameList[i][j] + "<br />";
                    t1 += "<div class=\"textStyle09\" style=\"float: left;\">" + json.finalSysNameList[i][j] + "</div>";
                    t1 += "</td>";
                    t1 += "</tr>";
                    
                    t1 += "<tr>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td class=\"link04\">";
                    t1 += "<a href=\"" + json.xmlFileNameList[i][j] +
                          "\">" + "[" + json.finalSysNameList[i][j] + " Weekly" + "]" + "</a>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td class=\"link04\">";
                    t1 += "<a href=\"subPages/drawGraphBatch2.php?batchID=" + json.batchIDList[i] +
                          "\">" + "[" + json.finalSysNameList[i][j] + " History" + "]" + "</a>";
                    t1 += "</td>";
                    t1 += "</tr>";
                    
                    t1 += "<tr>";
                    t1 += "<td>&nbsp";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "<td>";
                    t1 += "</td>";
                    t1 += "</tr>";
                }
            }
            
            t1 += "</table>";

            $("#" + _tagName).html(t1);
        }
    });
}

function swtGetAllBatchList()
{
    $.post("../phplibs/getInfo/swtGetTaskStateListHtml2.php", 
    {
        pageID:     0,
        reportType: 0
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            var t1 = swtImplode(json.batchIDList, ",");
            $("#batchList").html(t1);
            
            
        }
    });
}

function swtCheckBatchListDayForward(_tagName)
{
    swtBatchListDayBack--;
    if (swtBatchListDayBack < 0)
    {
        swtBatchListDayBack = 0;
    }
    swtGetTodayTaskListHtml(_tagName, swtBatchListDayBack);
}

function swtCheckBatchListDayBack(_tagName)
{
    swtBatchListDayBack++;
    swtGetTodayTaskListHtml(_tagName, swtBatchListDayBack);
}

function swtCheckBatchListToday(_tagName)
{
    swtBatchListDayBack = 0;
    swtGetTodayTaskListHtml(_tagName, swtBatchListDayBack);
}

function swtGetBatchMachineListHtml(_tagName, _batchID)
{
    $.post("../phplibs/getInfo/swtGetMachineListHtml.php", 
    {
        batchID: "" + _batchID
    }, 
    function(data,status) 
    {
        //alert(data);
        
        console.log (">>>");
        console.log (data);
        console.log ("<<<");
        
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            
            var t1 = "";
            t1 += "<div>";
            for (var i = 0; i < json.machineNameList.length; i++)
            {
                t1 += "<b>MachineName:</b> " + json.machineNameList[i] + "<br />";
                t1 += "<b>CardName:</b> " + json.cardNameList[i] + "<br />";
                t1 += "<b>SystemName:</b> " + json.sysNameList[i] + "<br />";
                if ( i < (json.machineNameList.length - 1))
                {
                    t1 += "<br />\n";
                }
            }
            t1 += "</div>";
            $("#" + _tagName).html(t1);
            return;
        }
        else
        {
            $("#" + _tagName).html(json.errorMsg);
            return;
        }
    });
}