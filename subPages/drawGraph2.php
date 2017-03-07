<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher Generate Routine Report", "../");

$html1->outPageHead("", "" .
                        "<script type=\"text/javascript\" src=\"../jslibs/jquery-cookie/jquery.cookie.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/canvasjs/canvasjs.min.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/genFuncs.js\"></script>\n" .
                        "<script type=\"text/javascript\" src=\"../jslibs/some/graphRelated.js?v=201701111354\"></script>\n");

$html1->outPageBodyStart();

?>
<!--
<div class = "pageArticleTitle">
    <p>Historical Graphs:</p>
</div>
-->
<div id="pageArticleContent" class = "pageArticleContent">

    <!--
    <div class = "pageArticleContent01">
        <p>Graph Options:</p>

    </div>
    -->
    <!--
    <div id="" class = "pageArticleContent01">

    </div>
    -->
    <br />
    
    <div style="">
        <fieldset id="apiCompGroup" style="width: 300px; float: left;">
        <legend><input id="apiCompCheck" name="apiCompCheck" type="checkbox"></input> API Comparison</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <fieldset id="gpuCompGroup" style="width: 300px; float: left;">
        <legend><input id="gpuCompCheck" name="gpuCompCheck" type="checkbox"></input> GPU Comparison</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <fieldset id="sysCompGroup" style="width: 300px; float: left;">
        <legend><input id="sysCompCheck" name="sysCompCheck" type="checkbox"></input> OS Comparison</legend>
        ...
        </fieldset>
        <div style="width: 20px; float: left;">
        &nbsp&nbsp
        </div>
        <div>
            <span id="btnNeedResfresh" class="btnNeedResfresh">●</span>
            <button id="btnRefreshAll" class="btnRefreshAll" onclick="swtManualRefreshGraphs();" >refresh Graphs</button>
        </div>

        
        <input type="hidden" id="apiNum" name="apiNum" value="0" />
        <input type="hidden" id="gpuNum" name="gpuNum" value="0" />
        <input type="hidden" id="sysNum" name="sysNum" value="0" />
        <input type="hidden" id="testNum" name="testNum" value="0" />
        <input type="hidden" id="testIDList" name="testIDList" value="0" />
        <input type="hidden" id="cardIDList" name="cardIDList" value="0" />
        <input type="hidden" id="cardNameList" name="cardNameList" value="0" />
    </div>
    <p><div style="clear: both;"></div></p>
    <p><div id="graphList"></div></p>
    <p style="width: 300px; clear: both;">&nbsp</p>
</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>

$("body").keydown(function() {
    if (event.keyCode == "13")
    {
        var t1 = $("#testNum").val();
        var testNum = parseInt(t1);
        
        var id = $("input:focus").attr("id");
        //console.log("--: " + id);
        if (id != undefined)
        {
            //console.log("--: " + id);
            var t3 = "subTestFilter";
            var t2 = id.substr(0, t3.length);
            
            //console.log("--: " + t2);
            if (t2 == t3)
            {
                var t4 = id.substr(t3.length);
                //$("#filterButton" + t4).click();
                
                swtGetTestData2(t4, -1);
                //console.log("--: " + t4);
            }
        }
    }
});

$("#btnRefreshAll").button();

$("#btnNeedResfresh").hide();

swtGetTestListCode2("apiCompGroup", "gpuCompGroup", "sysCompGroup", "graphList");

//window.location.href = "#chartContainer3";①

</script>

<p>&nbsp</p>
<p>&nbsp</p>

<?php

$html1->outPageBodyEnd();

?>