<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "../");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"../jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jpagination/jquery.pagination.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/getPageCode.js\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Batch List:</p>
</div>
<div id="pageContent01" class = "">
    ...
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

var swtBatchListPageID = 0;
swtGetTodayTaskListHtml2('pageContent01', 'pageBottom01', swtBatchListPageID);
setInterval("swtGetTodayTaskListHtml2('pageContent01', 'pageBottom01', swtBatchListPageID)", 1000);



</script>

<p>&nbsp</p>
<p>&nbsp</p>

<div id="logMsg">xxx</div>

<?php

$html1->outPageBodyEnd();

?>