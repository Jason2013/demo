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
    <p><span style="font-weight:bold;">Import Results & Generate Reports:</span></p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p><span style="font-weight:bold;">Generation Options:</span></p>
        <table>
        <tr>
            <td>
                folder name:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input id="inputFolderName" type="text" name="foldername" size="64" />&nbsp&nbsp&nbsp
                <!--<input id="pathSelector" type="file" name="fileString" multiple class="file">
                
                <input type="file" name="uploadfile" onchange="showRealPath(this.value);"/>
                <input type="hidden" name="uploadfileRealPath">
                -->
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
        
        <!--
        <table>
            <tr>
                <td style="width: 450px;">
                <span style="font-weight:bold;">Cross API</span>
                </td>
                <td style="width: 600px;">
                <span style="font-weight:bold;">Cross ASIC / Build</span>
                </td>
                <td style="width: 600px;">
                
                </td>
            </tr>
            <tr>
                <td>
                    <div id="crossAPI">...</div>
                </td>
                <td>
                    <div id="crossASIC">...</div>
                </td>
                <td>
                    <div id="crossBuild"></div>
                </td>
            </tr>
        </table>
        -->
        </p>
        
        <div style="clear: both;">&nbsp&nbsp</div>
        
        <p><span style="background-color: #FFFF00;">folder name</span> must be a windows server folder can be accessed by our server.</p>

        <p>like: <span style="background-color: #FFFF00;">\\oglserver\Incoming\Davy\deletable\benchMax\2017-08-11-man00001</span></p>
        <p>please <span style="background-color: #FF0000; color: #FFFFFF;">don't close this page in process of reports generation!</span></p>
        
        <p>finishing state: <div id="finishPercentBar" >0%</div></p>
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannualOutUserVer3('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
        start generation
        </div>
        
        <div style="float: left;">&nbsp&nbsp</div>
        
        <!--
        <div id="" class="button01 roundCorner" onclick="swtGenerateRoutineReportVer3('finishPercentBar', 'reportList', 0, -1, -1, 10);" onselectstart="return false;" style="float: left;">
        generate latest
        </div>
        -->
        
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


// H:/wamp64/www/benchMax/logStore/2017-06-14-man00001

$("#inputFolderName").bind("input propertychange", function(){
    var t1 = $("#inputFolderName").val();
    //alert(t1);
    swtGetFolderMachineNameListOutUser(t1, "crossAPI", "crossASIC", "crossBuild");
});

swtGetCardChoiceCodeVer2("reportList");


//function showRealPath(filePath){
//    
//    alert(filePath);
//    document.getElementsByName("foldername")[0].value = filePath;
//}


</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>