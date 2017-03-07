var assignedDriverInfoList = [];
var driverTocken = "";


function swtCloseDialog(_tag)
{
    $("#" + _tag).css("display", "none");
}

function swtAddDriver(_id)
{
    var displayState = $("#driverDialog01").css("display");
    if (displayState != "none")
    {
        return;
    }
    
    $("#driverPath01").val("");
    
    var screenWidth = parseInt($("#pageRightPart").width());
    var screenHeight = parseInt($("#pageRightPart").height());

    var dialogWidth = parseInt($("#driverDialog01").width());
    var dialogHeight = parseInt($("#driverDialog01").height());

    var dialogLeft = Math.floor((screenWidth - dialogWidth) * 0.5);
    var dialogTop = Math.floor((screenHeight - dialogHeight) * 0.5);

    $("#driverDialog01").css("position", "absolute");
    $("#driverDialog01").css("display", "block");
    $("#driverDialog01").css("left", "" + dialogLeft + "px");
    $("#driverDialog01").css("top", "" + dialogTop + "px");
    
    var d = new Date();
    var timeStamp = d.getTime();
    var tocken = MD5("unique_salt" + timeStamp);
    
    driverTocken = tocken;
    
    console.log("" + timeStamp + ", " + tocken);
    //var driverType = $("#driverType01").val();
    
    $('#file_upload01').uploadify({
        'formData'     : {
            'timestamp' : '' + timeStamp,
            'token'     : '' + tocken
        },
        'swf'      : '../jslibs/uploadify/uploadify.swf',
        'uploader' : '../jslibs/uploadify/uploadify.php',
        'multi'    : false,
        'fileTypeDesc' : 'DLL File, SO File',
        'fileTypeExts' : '*.dll; *.so',
        //'fileTypeExts' : '*.dll;',
        'onUploadComplete' : function(file){$("#driverPath01").val(file.name);}
    });
}

function swtGetDriverWithInfoHtml(_driverType, _driverName, _changeList, _uploadTocken, _curSlotID)
{
    var c01 = "<div >\n" +
                "<div >&nbsp&nbsp</div>\n" +
                "<div id=\"removeButton01\" class=\"button02 roundCorner\" onclick=\"swtRemoveDriver(" + _curSlotID + ");\">\n" +
                "remove driver\n" +
                "</div>\n" +
                "<table>\n" +
                "<tr>\n" +
                "<td class=\"driverSlotTemple02a\">\n" +
                "driver #" + (_curSlotID + 1) +
                "</td>\n" +
                "<td>\n" +
                "&nbsp\n" +
                "</td>\n" +
                "</tr>\n" +
                "<tr>\n" +
                "<td>\n" +
                "driver type:\n" +
                "</td>\n" +
                "<td>\n" +
                "&nbsp\n" + _driverType +
                "</td>\n" +
                "</tr>\n" +
                "<tr>\n" +
                "<td>\n" +
                "driver name:\n" +
                "</td>\n" +
                "<td>\n" +
                "&nbsp\n" + _driverName +
                "</td>\n" +
                "</tr>\n" +
                "<tr>\n" +
                "<td>\n" +
                "change list:\n" +
                "</td>\n" +
                "<td>\n" +
                "&nbsp\n" + _changeList +
                "</td>\n" +
                "</tr>\n" +
                "<tr style=\"display: none;\">\n" +
                "<td>\n" +
                "upload tocken:\n" +
                "</td>\n" +
                "<td>\n" +
                "&nbsp\n" + _uploadTocken +
                "</td>\n" +
                "</tr>\n" +
                "</table>\n" +
            "</div>\n";
    return c01;
}

function swtInsertDriverWithInfo(_driverType, _driverName, _changeList, _uploadTocken)
{
    var driverInfo = {};
    driverInfo.driverType = _driverType;
    driverInfo.driverName = _driverName;
    driverInfo.changeList = _changeList;
    driverInfo.uploadTocken = _uploadTocken;
    
    var curSlotID = assignedDriverInfoList.length;
    
    for (var i = 0; i < assignedDriverInfoList.length; i++)
    {
        if (assignedDriverInfoList[i].driverType == _driverType)
        {
            alert("driver type duplicated");
            return "";
        }
    }
    
    var c01 = swtGetDriverWithInfoHtml(_driverType, _driverName, _changeList, _uploadTocken, curSlotID);
             
    assignedDriverInfoList.push(driverInfo);
    
    var t1 = $("#driverSlots").html();
    $("#driverSlots").html(t1 + c01);
    
    return c01;
}

function swtInsertDriver()
{
    var t2 = $("#driverPath01").val();
    if (t2.length == 0)
    {
        alert("please select driver file");
        return;
    }
    
    var driverType = $("#driverType01").val();
    var driverName = $("#driverPath01").val();
    var changeList = $("#cl_number01").val();
    
    var t3 = swtInsertDriverWithInfo(driverType, driverName, changeList, driverTocken);
    if (t3.length == 0)
    {
        return;
    }
    
    var curSlotID = assignedDriverInfoList.length;
    


    // close upload dialog
    swtCloseDialog('driverDialog01');
}

function swtRemoveDriver(_id)
{
    if (_id >= assignedDriverInfoList.length)
    {
        return;
    }
    if (confirm("sure to delete?") == false)
    {
        return;
    }
    
    assignedDriverInfoList.splice(_id, 1);

    var t1 = "";
    
    for (var i = 0; i < assignedDriverInfoList.length; i++)
    {
        var slot = assignedDriverInfoList[i];
        t1 += swtGetDriverWithInfoHtml(slot.driverType, slot.driverName, slot.changeList, driverTocken, i);
    }
    $("#driverSlots").html(t1);
}

function swtSubmitBench()
{
    if (confirm("sure to start bench?") == false)
    {
        return;
    }
    
    //var t1 = swtMachineCheck.toString();
    //console.log(t1);
    
    var machineIDList = [];
    
    if (swtMachineCheck.length != swtMachineIDList.length)
    {
        console.log("machine id list num error");
    }
    for (var i = 0; i < swtMachineCheck.length; i++)
    {
        if (swtMachineCheck[i] == true)
        {
            if (swtMachineIDList[i] == null)
            {
                continue;
            }
            machineIDList.push(swtMachineIDList[i]);
        }
    }
    if (machineIDList.length == 0)
    {
        alert("please select test machines");
        return;
    }
    // selected machine id list
    var t1 = machineIDList.toString();
    
    if (assignedDriverInfoList.length == 0)
    {
        alert("please upload driver");
        return;
    }
    
    var tockenList = [];
    var changeListSet = [];
    var umdTypeList = [];
    for (var i = 0; i < assignedDriverInfoList.length; i++)
    {
        var slot = assignedDriverInfoList[i];
        if (slot == null)
        {
            continue;
        }
        if (slot.uploadTocken == null)
        {
            continue;
        }
        //alert("888");
        tockenList.push(slot.uploadTocken);
        changeListSet.push(slot.changeList);
        umdTypeList.push(slot.driverType);
    }
    // uploaded driver tocken list
    var t2 = tockenList.toString();
    var t3 = changeListSet.toString();
    var t4 = umdTypeList.toString();
    
    $.post("../phplibs/server/swtStartBench.php", 
    {
        //userguid: "" + userGuid,
        machineIDList : t1,
        uploadTockenList : t2,
        changeListSet : t3,
        umdTypeList : t4
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            alert(json.errorMsg);
            return;
        }

    });
//*/
}