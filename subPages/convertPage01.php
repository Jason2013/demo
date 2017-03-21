<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="author" content="Jony Zhu">
		<meta name="description" content="HTML5 Base Template.">
		<meta name="keywords" content="HTML5,Template">
		<title>HTML5</title>
		<!--[if IE]><script src="js/excanvas.js"></script><![endif]-->
		<script src="../jslibs/jquery.js"></script>				
	</head>
	<body>
    
<script>

function convertTestCaseFilter(_pageID)
{
    $.post("../phplibs/importResult/convertComaToSplit.php", 
    {
        pageID:     _pageID
    }, 
    function(data,status) 
    {
        //alert(data);
        console.log(data);
        var json = eval("(" + data + ")");

        if (json.errorCode == "1")
        {
            //alert(json.errorMsg);
            convertTestCaseFilter(_pageID + 1);
        }
        else
        {
            alert(json.errorMsg);
        }
    });
}

convertTestCaseFilter(0);

</script>
    
    
    
	</body>
</html>