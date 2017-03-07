<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Generate Routine Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/canvasjs/canvasjs.min.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/graphRelated.js?v=201610141306\"></script>\n");

$html1->outPageBodyStart();

$batchID = intval($_GET["batchID"]);

?>

<!--div class = "pageArticleTitle">
    <p>Historical Graphs:</p>
</div-->
<div id="pageArticleContent" class = "pageArticleContent">

    <!--div class = "pageArticleContent01">
        <p>Graph Options:</p>

    </div-->
    <!--
    <div id="" class = "pageArticleContent01">

    </div>
    -->
    <br />
    <div>
        <!--
        <fieldset id="apiCompGroup" style="width: 350px; float: left;">
        <legend><input id="apiCompCheck" name="apiCompCheck" type="checkbox"></input> API Comparison</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        -->
        <fieldset id="gpuCompGroup" style="width: 350px; float: left;">
        <legend><input id="gpuCompCheck" name="gpuCompCheck" type="checkbox"></input> GPU Comparison</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <fieldset id="sysCompGroup" style="width: 350px; float: left;">
        <legend><input id="sysCompCheck" name="sysCompCheck" type="checkbox"></input> System Comparison</legend>
        ...
        </fieldset>
        <input type="hidden" id="apiNum" name="apiNum" value="0" />
        <input type="hidden" id="gpuNum" name="gpuNum" value="0" />
        <input type="hidden" id="testNum" name="testNum" value="0" />
        <input type="hidden" id="sysNum" name="sysNum" value="0" />
        <input type="hidden" id="machineNum" name="machineNum" value="0" />
        <input type="hidden" id="testIDList" name="testIDList" value="0" />
        <input type="hidden" id="cardIDList" name="cardIDList" value="0" />
        <input type="hidden" id="sysNameList" name="sysNameList" value="0" />
        <input type="hidden" id="uniqueCardIDList" name="uniqueCardIDList" value="0" />
        <input type="hidden" id="uniqueSysNameList" name="uniqueSysNameList" value="0" />
        <input type="hidden" id="tagIDList" name="tagIDList" value="0" />
    </div>
    <p><div style="clear: both;"></div></p>
    <p><div id="graphList"></div></p>
    <p style="width: 300px; clear: both;">&nbsp</p>
</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>

swtGetTestListCodeBatch2(<?php echo $batchID; ?>, "sysCompGroup", "gpuCompGroup", "graphList");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>