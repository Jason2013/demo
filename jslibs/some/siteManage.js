function swtUserLogIn(_unTag, _pwTag)
{
    var userName = $("#" + _unTag).val();
    var passWord = $("#" + _pwTag).val();
    
    if ((userName.length == 0) ||
        (passWord.length == 0))
    {
        alert("input info empty");
        return;
    }
    
    $.post("../phplibs/userManage/swtLogIn.php", 
    {
        userName: userName,
        passWord: passWord
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            window.location = "../";
        }
        else
        {
            alert(json.errorMsg);
        }
    });
}

function swtUserLogOut()
{
    if (confirm("sure to log out?") == false)
    {
        window.location = "../";
        return;
    }
    $.post("../phplibs/userManage/swtLogOut.php", 
    {
    }, 
    function(data,status) 
    {
        //alert(data);
        //console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);

            window.location = "../";
        }
    });
}