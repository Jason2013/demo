<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=4\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Import ShaderBench Results:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Import Options:</p>
        <table>
        <tr>
            <td>
                report Type:&nbsp&nbsp&nbsp
            </td>
            <td>
                <select id="reportGroup" name="reportGroup">
                    <option value="shaderReport" selected="selected">shader report</option>
                    <option value="shaderSlot00">shader slot01</option>
                    <option value="shaderSlot01">shader slot02</option>
                    <option value="shaderSlot02">shader slot03</option>
                    <option value="shaderSlot03">shader slot04</option>
                    <option value="shaderSlot04">shader slot05</option>
                    <option value="shaderSlot05">shader slot06</option>
                    <option value="shaderSlot06">shader slot07</option>
                    <option value="shaderSlot07">shader slot08</option>
                    <option value="shaderSlot08">shader slot09</option>
                    <option value="shaderSlot09">shader slot10</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                folder name:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input id="inputFolderName" type="text" name="foldername" size="64" />&nbsp&nbsp&nbsp
            </td>
        </tr>
        <tr>
            <td>
                username:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input id="inputUsername" type="text" name="username" size="16" />&nbsp&nbsp&nbsp
                password:&nbsp&nbsp&nbsp
                <input id="inputPassword" type="password" name="password" size="16" />&nbsp&nbsp&nbsp
            </td>
        </tr>
        </table>
        <p>like: \\oglserver\Incoming\Davy\deletable\benchMax\2016-03-29-man</p>
        <p>finishing percent: <div id="finishPercentBar" >0%</div></p>
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannualShaderBench('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
        import
        </div>
        <div style="float: left;">&nbsp&nbsp</div>
    </div>
    <div id="" class = "pageArticleContent01">

    </div>
    <p></p>

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

//swtGetShortBatchIDList("targetBatchID");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>