<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher mainpage", "../");

$html1->outPageHead("", "" .
                        "<link rel=\"stylesheet\" href=\"../jslibs/jpagination/pagination.css\"/>" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/siteManage.js\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>User Log In:</p>

</div>
<div id="pageContent01" class = "">
    <p>if you are an outside user, please use <span style="background-color: #FFFF00;">AMD Credential<span></p>
    <p>to login, thank you for using our service.</p>
    <p>username: <input type="text" id="userName" name="userName" value="<?php echo $html1->userName; ?>" /></p>
    <p>password: <input type="password" id="passWord" name="passWord" /></p>
    <p><input type="submit" value="Logon" onclick="swtUserLogIn('userName', 'passWord');" /></p>
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



</script>

<p>&nbsp</p>
<p>&nbsp</p>


<?php

$html1->outPageBodyEnd();

?>