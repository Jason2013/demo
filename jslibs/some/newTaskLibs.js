var swtMachineCheck = [];
var swtMachineIDList = [];

function swtCheckMachine(_id, _pos)
{
    // console.log(_id);
    var n1 = parseInt(_pos);
    if (swtMachineCheck[n1] == null)
    {
        swtMachineCheck[n1] = true;
        $("#" + _id).html("<img src=\"../images/monitor13check.png\" width=\"120px\" height=\"120px\"/>");
    }
    else
    {
        if (swtMachineCheck[n1] == true)
        {
            swtMachineCheck[n1] = false;
            $("#" + _id).html("<img src=\"../images/monitor13.png\" width=\"120px\" height=\"120px\"/>");
        }
        else
        {
            swtMachineCheck[n1] = true;
            $("#" + _id).html("<img src=\"../images/monitor13check.png\" width=\"120px\" height=\"120px\"/>");
        }
    }
}

function swtShowMachineInfo(_id, _state)
{
    if (_state)
    {
        $("#" + _id).css("display", "block");
        var t1 = $("#" + _id).css("height");
        t1 = t1.replace(/(px)/, "");
        var n1 = parseInt(t1);
        //$("#" + _id).css("top", "");
    }
    else
    {
        $("#" + _id).css("display", "none");
    }
}

function swtPrintNumber(_val, _len)
{
    var t1 = "" + _val;
    var n1 = t1.length;
    var t2 = "";
    var n2 = _len - n1;
    for (var i = 0; i < n2; i++)
    {
        t2 += "0";
    }
    return (t2 + t1);
}

function swtGetAvailableMachineList()
{
    $.post("../phplibs/server/swtGetAvailableMachine.php", 
    {
        //userguid: "" + userGuid,
    }, 
    function(data,status) 
    {
        //alert(data);
        var json = eval("(" + data + ")");
        if (json.errorCode != "1")
        {
            //alert(json.errorMsg);
            return;
        }
        var c1 = "<div id=\"%s\" class=\"machineInfo\">\n";
        var c2 = "<h4>%s</h4>\n";
        var c3 = "</div>\n";
        // machineItem
        var c4 = "<div id=\"\" class=\"machineItem\">\n";
        // machineIcon001, machineIcon001
        // machineInfo001, machineInfo001
        var c5 = "<div id=\"%s\" onclick=\"swtCheckMachine('%s', '%s');\" onmouseover=\"swtShowMachineInfo('%s', true);\" onmouseout=\"swtShowMachineInfo('%s', false);\">\n";
        var c6 = "<img src=\"../images/monitor13.png\" width=\"120px\" height=\"120px\"/>\n";
        var c7 = ["machineIcon", "machineIcon", "", "machineInfo", "machineInfo"];
        
        var c8 = "<table>\n";
        var c9 = "</table>\n";
        var c10 = "<tr>\n";
        var c11 = "</tr>\n";
        var c12 = "<td>\n";
        var c13 = "</td>\n";
        swtMachineIDList.length = 0;
        var sum1 = "";
        for (var i = 0; i < json.machineNum; i++)
        {
            var t4 = "machineInfo" + swtPrintNumber(i + 1, 3);
            var t5 = "";
            var t6 = "";
            var t1 = "";
            
            var j = 0;
            var t7 = c5.replace(/(%s)/g,function(){
                var t101 = "";
                if (j == 2)
                {
                    // pos in machine list
                    // second param of swtCheckMachine()
                    t101 = "" + i;
                }
                else
                {
                    t101 = (c7[j] + swtPrintNumber(i + 1, 3));
                }
                j++;
                return t101;
            });

            t1 = c1.replace(/(%s)/, t4);

            t6 += c8;
            for (var j = 0; j < json.keyNum; j++)
            {
                t6 += c10;
                t6 += c12;
                t6 += (json.keyNameList[j] + ": ");
                t6 += c13;
                t6 += c12;
                t6 += (json.keyValueList[j][i] + "\n");
                t6 += c13;
                t6 += c11;
                
                
                if (("" + json.keyNameList[j]) == "machineID")
                {
                    // save machine id for later assign task
                    swtMachineIDList[i] = parseInt("" + json.keyValueList[j][i]);
                }
            }
            // set machines as unselected
            swtMachineCheck[i] = false;
            
            t6 += c9;
            
            sum1 += c4;
            sum1 += t7;
            sum1 += c6;
            sum1 += c3;
            
            sum1 += t1;
            sum1 += t6;
            sum1 += c3;
            sum1 += c3;
        }
        //console.log(sum1);
        $("#machineList").html(sum1);
    });

}
