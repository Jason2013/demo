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
    <p>&nbsp&nbsp&nbsp&nbsphere is an example: <a href="\\gfxbench\Grapher\result_example">\\gfxbench\Grapher\result_example</a></p>
    <p>2. open this link <a href="genReportOutUser.php">"generate report"</a> to import your results into our database, and start generating reports.</p>
    <p>&nbsp&nbsp&nbsp&nbspby above example, please fill \\gfxbench\Grapher\result_example\2017-05-18-man00003 as folder name.</p>
    <p>&nbsp&nbsp&nbsp&nbspand don't forget to click the start generation button </p>
    <p>&nbsp&nbsp&nbsp&nbspduring the process of result importing, please don't close current page or switch to other page!</p>
    <p>3. after reports generation is done, reports downloading links will be shown on current webpage.</p>
    
    <p>see an example here: \\oglserver\Incoming\Davy\deletable\benchMax\2017-08-11-man00001</p>
    <p>please don't close this page in process of reports generation!</p>
    
    
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