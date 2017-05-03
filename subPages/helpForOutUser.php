<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "../");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"../jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jpagination/jquery.pagination.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201704201555\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/getPageCode.js?v=201704201555\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Help Center:</p>
</div>
<div id="pageContent01" class = "">
    <p>1. you need to organize your microbench result files into a folders tree hierarchy, before start everything.</p>
    <p>&nbsp&nbsp&nbsp&nbsphere is an example: \\gfxbench\Grapher\result_example</p>
    <p>2. open this link <a href="importLogFilesOutUser.php">"import results"</a> to import your results into our database.</p>
    <p>&nbsp&nbsp&nbsp&nbspby above example, please fill <span style="background-color: #FFFF00;">\\gfxbench\Grapher\result_example\2017-4-25-man00001</span> as folder name.</p>
    <p>&nbsp&nbsp&nbsp&nbspand don't forget to click the <span style="background-color: #0000FF; color: #FFFFFF;">import</span> button </p>
    <p>&nbsp&nbsp&nbsp&nbspduring the process of result importing, please <span style="background-color: #FF0000; color: #FFFFFF;">don't close current page or switch to other page!</span></p>
    <p>3. after importing results, please open this link <a href="compileReportBatchID.php">"gnerate report"</a> to generate reports.</p>
    <p>&nbsp&nbsp&nbsp&nbspand don't forget to click the <span style="background-color: #0000FF; color: #FFFFFF;">generate</span> button </p>
    <p>&nbsp&nbsp&nbsp&nbspduring the process of report generating, please <span style="background-color: #FF0000; color: #FFFFFF;">don't close current page or switch to other page!</span></p>
    <p>&nbsp&nbsp&nbsp&nbspafter report generating is done, report downloading links will be shown on current webpage.</p>
</div>

<p></p>
<div id="pageBottom01" class="pagination">

</div>



<?php

$html1->outPageBodyNext();

?>
    
<script>


</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>