<?php
include_once "phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"jslibs/jpagination/jquery.pagination.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"jslibs/some/parseTestResult.js?v=201610181644\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"jslibs/some/getPageCode.js?v=201610181644\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <!--p>History Reports List:</p-->
</div>

<div id="pageContent01" class = "">

    <div style="clear: both;">
        <fieldset id="gpuCompGroup" style="width: 300px; float: left;">
        <legend><input id="gpuCompCheck" name="gpuCompCheck" type="checkbox"></input> GPU Selection</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <fieldset id="sysCompGroup" style="width: 300px; float: left;">
        <legend><input id="sysCompCheck" name="sysCompCheck" type="checkbox"></input> System Selection</legend>
        ...
        </fieldset>
        <input type="hidden" id="gpuNum" name="gpuNum" value="0" />
        <input type="hidden" id="sysNum" name="sysNum" value="0" />

    </div>
    <div style="clear: both; height: 20px;">
    </div>
    <div id="reportList" style="clear: both;">
    </div>
</div>
<div id="machineList01" class = "machineListInfo" style="display: none;">
    ...
    ...
    ...
</div>
<p></p>
<div id="pageBottom01" class="pagination">

</div>



<?php

$html1->outPageBodyNext();

?>
    
<script>

swtGetHistoryReportInfo("reportList", "gpuCompGroup", "sysCompGroup");



</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>