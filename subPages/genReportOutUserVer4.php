<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js?v=201706291304\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/browserFolder/browserFolder.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201711211541\"></script>\n");

$html1->outPageBodyStart();
$html1->outPageBodyCheckLog();

?>

<div class = "pageArticleTitle">
    <p><span style="">Generate Reports:</span></p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p><span style="font-weight:bold;">Generation Options:</span></p>
        <table>
        <tr>
            <td>
                choose folder:&nbsp&nbsp&nbsp
            </td>
            <td>
                <!--
                <input id="inputFolderName" type="text" name="foldername" size="64" />&nbsp&nbsp&nbsp
                -->
                <input type="file" id="inputFileList" name="inputFileList" style="" 
                 webkitdirectory multiple />&nbsp&nbsp&nbsp
                <button id="subBtn">upload</button>&nbsp&nbsp&nbsp
            </td>
            <td>
                <div id="loadProgressBar" style="width: 270px; position: relative;">
                    <div id="loadProgressBarText" style="position: absolute; left: 45%; top: 4px; font-weight: bold; text-shadow: 1px 1px 0 #fff;">
                        &nbsp
                    </div>
                </div>
            </td>
        </tr>

        </table>
        <p>
        
        <table>
        <tr>
        <td>
        <fieldset id="crossAPI" style="width: 380px; float: left;">
        <legend>cross API<input id="crossAPICheck" name="crossAPICheck" type="checkbox"></input></legend>
        ...
        </fieldset>
        </td>
        <td>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        </td>
        <td>
        <fieldset id="crossASIC" style="width: 380px; float: left;">
        <legend>cross ASIC / OS<input id="crossASICCheck" name="crossASICCheck" type="checkbox"></input></legend>
        ...
        </fieldset>
        </td>
        </tr>
        </table>
        </p>
        
        <div style="clear: both;">&nbsp&nbsp</div>

        <!--
        <p>choose your local folder that has MB result files to upload.</p>

        <p>see an example here: <span style="background-color: #FFFF00;">\\oglserver\Incoming\Davy\deletable\benchMax\2017-08-11-man00001</span></p>
        <p>please <span style="background-color: #FF0000; color: #FFFFFF;">don't close this page in process of reports generation!</span></p>
        -->
        <p>Generating Progress:&nbsp&nbsp<span id="finishPercentBar" >ready...</span></p>
        
        <!--
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannualOutUserVer3('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
        start generation
        </div>
        -->
        
        <div id="startGen" class="button01 roundCorner" onclick="swtGenerateRoutineReportVer4('finishPercentBar', 'reportList', 0, -1, -1, 10);" onselectstart="return false;" style="float: left;">
        start generation
        </div>
        
        <div style="float: left;">&nbsp&nbsp</div>
        
        
        <div style="float: left;">&nbsp&nbsp</div>
        
        
    </div>
    <div id="" class = "pageArticleContent01">
    </div>
    <div id="divMachineListFlat">
    </div>
    <p></p>
    
    <div id="reportList"></div>

</div>

<?php

$html1->outPageBodyNext();
//$html1->outPageBodyCheckLog();

?>
    
<script>

var t2 = $.cookie('benchMaxUsername');
var t3 = $.cookie('benchMaxPassword');

//alert(t2);

$("#inputUsername").val(t2);
$("#inputPassword").val(t3);


$('#subBtn').click(function(){

    swtDoCopyResultFilesVer3a("inputFileList",
                              "finishPercentBar",
                              -1,
                              0,
                              "",
                              "",
                              0);
                              
                              
    //console.info($('#inputFileList').get(0).files);
    
    //console.log($('#inputFileList').get(0).files[0].webkitRelativePath);

});


$("#inputFolderName").bind("input propertychange", function(){
    var t1 = $("#inputFolderName").val();
    //alert(t1);
    //swtGetFolderMachineNameListOutUser(t1, "crossAPI", "crossASIC", "crossBuild");
});

swtGetCardChoiceCodeVer2("reportList");

$("#loadProgressBar").progressbar({
    value: 0,
    change: function() {
        $("#loadProgressBarText").text( $("#loadProgressBar").progressbar( "value" ) + "%" );
    }
});

var progressbarValue = $("#loadProgressBar").find( ".ui-progressbar-value" );
progressbarValue.css({
  "background": "#00a000"
});

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>

<script>

function isInputDirSupported() {
    var tmpInput = document.createElement('input');
    if ('webkitdirectory' in tmpInput 
        || 'mozdirectory' in tmpInput 
        || 'odirectory' in tmpInput 
        || 'msdirectory' in tmpInput 
        || 'directory' in tmpInput) return true;

    return false;
}

if (isInputDirSupported() == false)
{
    $("#inputFileList").attr("disabled", "disabled");
    $("#subBtn").attr("disabled", "disabled");
    $("#startGen").attr("disabled", "disabled");
    alert("your web browser doesn't support folder uploading, please try WinEdge or Chrome or Firefox.");
}

</script>

</script>