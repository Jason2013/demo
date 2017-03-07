<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Gen Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Delete Batches:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Del Options:</p>
    </div>
    <div id="" class = "pageArticleContent01">
        <p>you selected batches area:</p>
        <input id="startBatchTag" type="text" name="fname" /> To
        <input id="endBatchTag"type="text" name="fname" />
    </div>
    <p></p>
    <div id="pageBottom01" style="display: inline;">
        <div id="dayForwardButton01" class="button01 roundCorner" onclick="swtDelBatches('startBatchTag', 'endBatchTag');" onselectstart="return false;" style="float: left;">
        del batches
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