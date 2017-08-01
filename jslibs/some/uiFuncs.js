var swtPageLeftBarSlotNum = 0;

function swtChangeDivColor(_tag, _color, _bgColor)
{
    //alert($("#" + _tag).css("color"));
    $("#" + _tag).css("background-color", _bgColor);
    $("#" + _tag).css("color", "#ff0000");
}

function swtRollOutOrUpDiv(_tag)
{
    //var n1 = $("#" + _tag).height();
    //var n2 = 24;
    //var n3 = swtPageLeftBarSlotNum * n2;
    //$("#" + _tag).show();
    //n3 = $("#" + _tag).css("height", "auto").height();
    //
    //// console.log(n1 + ", " + n3);
    //if ((n1 >= n3) && (n1 != 0))
    //{
    //    $("#" + _tag).animate({height: "0px"}, 300,  function() { $("#" + _tag).hide(); });
    //}
    //else
    //{
    //    $("#" + _tag).show();
    //    $("#" + _tag).animate({height: n3 + "px"}, 300, function() { $("#" + _tag).show(); });
    //}
    
    $("#" + _tag).slideToggle(300);
}

function swtInitAccordion(_tag, _prePath, _pageDate)
{
    var hCode = "";
    var divNum = 0;
    $("#" + _tag).children().each(function(index, data){ 

    var t2 = "pageLeftAccordionTitle" + divNum;
    var t3 = "pageLeftAccordionBar" + divNum;
    if ($(data).is("h3"))
    {
        var t1 = $(data).text();
        
        if (divNum > 0)
        {
            hCode += "</div>\n";
        }

        hCode += "<div id=\"" + t2 + "\" class=\"pageLeftAccordionTitle roundCorner01\" onclick=\"swtRollOutOrUpDiv('" + t3 + "');\">\n";
        hCode += "●" + t1 + "\n";
        hCode += "</div>\n";
        hCode += "<div id=\"" + t3 + "\">\n";
        divNum++;
    }
    else if ($(data).is("div"))
    {
        $(data).children().each(function(subIndex, subData){
            if ($(subData).is("a"))
            {
                var t1 = $(subData).prop("href");
                var t2 = $(subData).text();

                hCode += "<div class=\"pageLeftAccordionBar\" onclick=\"window.location.href='" + t1 + "';\">\n";
                
                hCode += "&nbsp&nbsp- " + t2 + "\n"; // ◆
                hCode += "</div>\n";
            }
            else if ($(subData).is("div"))
            {
                if ($(subData).attr("class") == "simpleCalender")
                {
                    //console.log("777");
                    var idName = $(subData).attr("id");
                    var t1 = f_tcalGetHTML(_prePath, idName, null, [], []);
                    
                    //console.log("777: " + _pageDate);
                    
                    var pageDate = (_pageDate == null) ? null : new Date(_pageDate);
                    swtUpdateLeftBarCalender(_prePath, idName, pageDate);
                    
                    //console.log(t1);
                    hCode += "<div class=\"pageLeftAccordionBackGround\"><div id=\"" + idName + "\">\n";
                    hCode += "" + t1 + "\n"; // ◆
                    hCode += "</div></div>\n";
                }
            }
        });
    }
    });

    $("#" + _tag).html(hCode);
    swtPageLeftBarSlotNum = divNum;
}

function swtUpdateLeftBarCalender(_prePath, _tagName, _checkDate)
{
    dateString = moment(_checkDate).format("YYYY-MM-DD");
    console.log("nnn: " + dateString);
    $.post(_prePath + "phplibs/getInfo/swtGetLeftBarBatchList.php", 
    {
        checkDate: "" + dateString
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        console.log(data);
        var json = eval("(" + data + ")");

        //if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            
            var batchIDList = [];
            for (var i = 0; i < json.batchIDList.length; i++)
            {
                batchIDList.push(json.batchIDList[i]);
            }
            var batchDateList = [];
            for (var i = 0; i < json.batchDateList.length; i++)
            {
                batchDateList.push(json.batchDateList[i]);
            }
            
            var t1 = f_tcalGetHTML(_prePath, _tagName, _checkDate, batchIDList, batchDateList);
            $("#" + _tagName).html(t1);
        }
    });
}