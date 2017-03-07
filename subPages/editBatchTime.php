<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Gen Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jscalendar/calendar.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/graphRelated.js\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Set Batch Time:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Set Options:</p>
    </div>
    <div id="" class = "pageArticleContent01">
        <p>target batch:</p>
        <input id="startBatchTag" type="text" name="fname" />&nbsp&nbsp
        <p>target date:</p>
        <input name="dateTag" type="text" id="dateTag" onclick="calendar.show(this);" size="10" maxlength="10" readonly="readonly" />
    </div>
    <p></p>
    <div id="pageBottom01" style="display: inline;">
        <div id="dayForwardButton01" class="button01 roundCorner" onclick="swtSetBatchTime('startBatchTag', 'dateTag');" onselectstart="return false;" style="float: left;">
        set batch
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