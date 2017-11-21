<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "../");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"../jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jpagination/jquery.pagination.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201711151507\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/getPageCode.js?v=201711151507\"></script>\n");
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


swtGetTodayTaskListHtml2('pageContent01', 'pageBottom01', swtBatchListPageID, -1);
setInterval("swtGetTodayTaskListHtml2('pageContent01', 'pageBottom01', swtBatchListPageID, -1)", 1000);



//pageShowMask();



</script>

<p>&nbsp</p>
<p>&nbsp</p>


<?php

$html1->outPageBodyEnd();

?>