<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js?v=201706291304\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/browserFolder/browserFolder.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201708171304\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p><span style="font-weight:bold;">Generate Reports:</span></p>
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
                <input type="file" id="inputFileList" name="inputFileList" webkitdirectory multiple />&nbsp&nbsp&nbsp
                <button id="subBtn">upload</button>&nbsp&nbsp&nbsp
            </td>
        </tr>

        </table>
        <p>
        
        <fieldset id="crossAPI" style="width: 250px; float: left;">
        <legend>cross API<input id="crossAPICheck" name="crossAPICheck" type="checkbox"></input></legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <fieldset id="crossASIC" style="width: 400px; float: left;">
        <legend>cross ASIC / Build<input id="crossASICCheck" name="crossASICCheck" type="checkbox"></input></legend>
        ...
        </fieldset>
        
        </p>
        
        <div style="clear: both;">&nbsp&nbsp</div>
        
        <p>choose your local folder that has MB result files to upload.</p>

        <p>see an example here: <span style="background-color: #FFFF00;">\\oglserver\Incoming\Davy\deletable\benchMax\2017-08-11-man00001</span></p>
        <p>please <span style="background-color: #FF0000; color: #FFFFFF;">don't close this page in process of reports generation!</span></p>
        
        <p>finishing state: <div id="finishPercentBar" >0%</div></p>
        
        <!--
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannualOutUserVer3('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
        start generation
        </div>
        -->
        
        <div id="" class="button01 roundCorner" onclick="swtGenerateRoutineReportVer3('finishPercentBar', 'reportList', 0, -1, -1, 10);" onselectstart="return false;" style="float: left;">
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




</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>