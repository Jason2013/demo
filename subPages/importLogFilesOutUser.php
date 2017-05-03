<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201704271708\"></script>\n");

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
                target batch ID:&nbsp&nbsp&nbsp
            </td>
            <td>
                <div id="targetBatchID"></div>
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
        
        <p><span style="background-color: #FFFF00;">folder name</span> must be a windows server folder can be accessed by our server.</p>
        <p><span style="background-color: #FFFF00;">username</span> & <span style="background-color: #FFFF00;">password</span> are your AMD domain username & password,</p>
        <p>they are used to enable our server to access above result folder.</p>
        
        <p>like: <span style="background-color: #FFFF00;">\\oglserver\Incoming\Davy\deletable\benchMax\2016-03-29-man</span></p>
        <p>please <span style="background-color: #FF0000; color: #FFFFFF;">don't close this page in process of importing result!</span></p>
        
        <p>finishing percent: <div id="finishPercentBar" >0%</div></p>
        <div id="" class="button01 roundCorner" onclick="swtSubmitTestResultsMannualOutUser('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
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

swtGetShortBatchIDListOutUser("targetBatchID");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>