<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Gen Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Set Batch Result Files Path:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Set Options:</p>
    </div>
    <div id="" class = "pageArticleContent01">
        <p>batch result Files path:</p>
        batch:&nbsp&nbsp
        <input id="batchTag" type="text" name="fname" />
        path:&nbsp&nbsp
        <input id="pathTag"type="text" name="fname" size="64" />
    </div>
    <p></p>
    <div id="pageBottom01" style="display: inline;">
        <div id="dayForwardButton01" class="button01 roundCorner" onclick="swtSetBatchLogPath('batchTag', 'pathTag');" onselectstart="return false;" style="float: left;">
        set path
        </div>
        <div style="float: left;">&nbsp&nbsp</div>
    </div>
    <p>&nbsp</p>

</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>



</script>

<p>&nbsp</p>
<p>&nbsp</p>

<div id="logMsg">xxx</div>

<?php

$html1->outPageBodyEnd();

?>