<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Generate Routine Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201706081304\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Generate Routine Report</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Report Type Selection:</p>
        
        <p>Cross API:</p>
        <div id="" class="button01 roundCorner" onclick="swtGotoPage('./sepCrossAPI.php');" onselectstart="return false;" style="">
        Select
        </div>
        <p>Cross ASIC & System:</p>
        <div id="" class="button01 roundCorner" onclick="swtGotoPage('./sepCrossASIC.php');" onselectstart="return false;" style="">
        Select
        </div>
        <p>Cross Build:</p>
        <div id="" class="button01 roundCorner" onclick="swtGotoPage('./sepCrossBuild.php');" onselectstart="return false;" style="">
        Select
        </div>
        
        <p>&nbsp</p>
        
        <div id="" class="button02 roundCorner" onclick="swtGotoPage('./importLogFilesOutUser.php');" onselectstart="return false;" style="float: left;">
        Step Back
        </div>

        <div style="float: left;">&nbsp&nbsp</div>
        <br />
        <div id="reportList"></div>
    </div>
    <div id="" class = "pageArticleContent01">

    </div>
    <p></p>

</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>

//swtGetCardChoiceCodeCrossAPI("cardChoice");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>