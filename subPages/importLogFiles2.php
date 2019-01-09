<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=1101\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Import Test Results:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Import Options:</p>
        <table>
        <tr>
            <td>
                Report Type:&nbsp&nbsp&nbsp
            </td>
            <td>
            <!--
                <input type="radio" name="reportGroup" value="routineReport" checked="checked">routine report (shown in Graphs)
                <br>
                <input type="radio" name="reportGroup" value="tempReport">temp report (not shown in Graphs)
            -->
                <select id="reportGroup" name="reportGroup">
                    <option value="routineReport" selected="selected">Manual Weekly Microbench</option>
                    <option value="tempReport">temp report</option>
                    <option value="reportSlot00">Weekly Ariel Microbench</option>
                    <option value="reportSlot01">Weekly Navi10 Microbench</option>
                    <option value="reportSlot02">report slot03</option>
                    <option value="reportSlot03">report slot04</option>
                    <option value="reportSlot04">report slot05</option>
                    <option value="reportSlot05">report slot06</option>
                    <option value="reportSlot06">report slot07</option>
                    <option value="reportSlot07">report slot08</option>
                    <option value="reportSlot08">report slot09</option>
                    <option value="reportSlot09">report slot10</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                Target Batch ID:&nbsp&nbsp&nbsp
            </td>
            <td>
                <div id="targetBatchID"></div>
            </td>
        </tr>
        <tr>
            <td>
                Import Date:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input type="text" id="importDate">
            </td>
        </tr>
        <tr>
            <td>
                Folder Name:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input id="inputFolderName" type="text" name="foldername" size="64" />&nbsp&nbsp&nbsp
            </td>
        </tr>
        <tr>
            <td>
                User Name:&nbsp&nbsp&nbsp
            </td>
            <td>
                <input id="inputUsername" type="text" name="username" size="16" />&nbsp&nbsp&nbsp
                Pass Word:&nbsp&nbsp&nbsp
                <input id="inputPassword" type="password" name="password" size="16" />&nbsp&nbsp&nbsp
            </td>
        </tr>
        </table>
        <p>like: \\oglserver\Incoming\Davy\deletable\benchMax\2016-03-29-man</p>
        <p>finishing percent: <div id="finishPercentBar" >0%</div></p>
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannual2('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
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

swtGetShortBatchIDList("targetBatchID");

$( "#importDate" ).datepicker();

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>