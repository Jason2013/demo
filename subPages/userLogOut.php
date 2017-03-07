<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "../");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"../jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/siteManage.js\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>User Log Out:</p>

</div>
<div id="pageContent01" class = "">
    <p><input type="submit" value="logout" onclick="swtUserLogOut();" /></p>
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

//swtUserLogOut();

</script>

<p>&nbsp</p>
<p>&nbsp</p>


<?php

$html1->outPageBodyEnd();

?>