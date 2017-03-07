<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Import Results", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/parseTestResult.js?v=201610181644\"></script>\n");

$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>Import Test Results:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">

    <div class = "pageArticleContent01">
        <p>Import Options:</p>
        <table>
        <tr>
            <td>
                target batch ID:&nbsp&nbsp&nbsp
            </td>
            <td>
                <div id="targetBatchID"></div>
            </td>
        </tr>

        </table>
        
        <div id="skynetResultList"><img src="../images/loading.gif" width="20px" height="20px" /></div>
        
        <p>
        <table border="0" cellspacing="0">
        <tr style="background-color: #a0a0a0; text-align: center;"><td >&nbsp&nbspchangelist</td><td></td></tr>
        <tr><td>&nbsp&nbspDX11:</td><td><input type="text" id="dx11CL" name="dx11CL" /></td></tr>
        <tr><td>&nbsp&nbspDX12:</td><td><input type="text" id="dx12CL" name="dx12CL" /></td></tr>
        <tr><td>&nbsp&nbspVulkan:</td><td><input type="text" id="vulkanCL" name="vulkanCL" /></td></tr>
        </table>
        </p>
        

        <p>finishing percent: <div id="finishPercentBar" >0%</div></p>
        <div id="" class="button01 roundCorner" onclick="swtSelectSkynetResults('inputFolderName', 'finishPercentBar', 'inputUsername', 'inputPassword', 'selBatchID');" onselectstart="return false;" style="float: left;">
        import
        </div>
        <div style="float: left;">&nbsp&nbsp</div>
    </div>
    <div id="" class = "pageArticleContent01">

    </div>
    
    <input type="hidden" id="resultNum" name="resultNum" value="0" />
    <div id="skynetResultData"></div>

    
    <p></p>

</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>


swtGetShortBatchIDList("targetBatchID");
swtGetSkynetResultList("skynetResultList", "skynetResultData");

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>