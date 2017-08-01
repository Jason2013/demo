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
    <p>Generate Routine Report - Cross API</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Gen Options:</p>
        <p>Comparison in Report:</p>
        <p><div id="cardChoice"></div></p>
        <!--
        <p>Batch ID:&nbsp&nbsp&nbsp
           <input id="inputBatchID" type="text" name="foldername" size="16" />&nbsp&nbsp&nbsp
        </p>
        <p>like: 193</p>
        -->
        
        <p>please <span style="background-color: #FF0000; color: #FFFFFF;">don't close this page in process of generating report!</span></p>
        
        <p>finishing percent: <div id="finishPercentBar" >0%</div></p>
        
        
        <div id="" class="button02 roundCorner" onclick="swtGotoPage('./sepStartPage.php');" onselectstart="return false;" style="float: left;">
        Step Back
        </div>
        <div style="float: left; width: 100px;">
        &nbsp&nbsp&nbsp&nbsp
        </div>
        
        
        <div id="" class="button01 roundCorner" onclick="swtGenerateRoutineReport('finishPercentBar', 'reportList', 0, -1, 0);" onselectstart="return false;" style="float: left;">
        generate
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

swtGetCardChoiceCodeCrossAPI("cardChoice", "reportList");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>