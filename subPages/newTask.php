<?php
include_once "../phplibs/generalLibs/swtHtmlTemple.php";

$html1 = new CSwtHtmlTemple("Grapher New Task", "../");




$html1->outPageHead("<link rel=\"stylesheet\" type=\"text/css\" href=\"../jslibs/uploadify/uploadify.css\">\n",
                    "<script type=\"text/javascript\" src=\"../jslibs/uploadify/jquery.uploadify.js\"></script>\n" .
                    "<script type=\"text/javascript\" src=\"../jslibs/some/md5.js\"></script>\n" .
                    "<script type=\"text/javascript\" src=\"../jslibs/some/newTaskLibs.js\"></script>\n" .
                    "<script type=\"text/javascript\" src=\"../jslibs/some/newDriverLibs.js\"></script>\n");
$html1->outPageBodyStart();

?>

<div class = "pageArticleTitle">
    <p>New Task:</p>
</div>
<div id="pageArticleContent" class = "pageArticleContent">
    <div class = "">
        <p>Available Test Machines:</p>
        <div id="machineList">
            <!--
            <div id="" class="machineItem">
                <div id="machineIcon001" onclick="swtCheckMachine('machineIcon001');" onmouseover="swtShowMachineInfo('machineInfo001', true);" onmouseout="swtShowMachineInfo('machineInfo001', false);">
                    <img src="../images/monitor13.png" width="120px" height="120px"/>
                </div>
                <div id="machineInfo001" class="machineInfo">
                    <h4>card: Fiji</h4>
                    <h4>card: Fiji</h4>
                    <h4>card: Fiji</h4>
                    <h4>card: Fiji</h4>
                </div>
            </div>
            -->
        </div>
    </div>
    <div class = "pageArticleContent01">
        <p>Upload UMD Drivers:</p>
    </div>
    <div class = "pageArticleContent01">
        <p>...</p>

        <div id="addButton01" class="button01 roundCorner" onclick="swtAddDriver(1);" onselectstart="return false;">
        add driver
        </div>
        <div >&nbsp&nbsp</div>

        <div id="driverSlots" class="">
        </div>
        
        <div >&nbsp&nbsp</div>
        
        <div id="addButton01" class="button03 roundCorner" onclick="swtSubmitBench();" onselectstart="return false;">
        start bench
        </div>
        
        <div id="driverDialog01" class="driverSlotTemple01" style="display: none;">

            <p class="driverSlotTemple02">driver #1:&nbsp(<font color="#ff0000">*</font> item must be filled)</p>
            <table>
            <tr class="driverSlotTemple04">
            <td>
                driver Type:&nbsp
            </td>
            <td>
                <select id="driverType01" name="driverType01">
                <option value="DX11">DX11</option>
                <option value="DX12">DX12</option>
                <option value="Vulkan">Vulkan</option>
                </select>&nbsp
                <font color="#ff0000">*</font>
            </td>
            </tr>
            <tr>
            <td>
                change list:&nbsp
            </td>
            <td>
                <input type="text" id="cl_number01" name="cl_number01" />
            </td>
            </tr>
            <td>
                driver path:&nbsp
            </td>
            <td>
                <input type="text" id="driverPath01" name="driverPath01" readonly="readonly" size="35" />
            </td>
            </tr>
            <tr>
            <td valign="top">
                select driver:&nbsp
            </td>
            <td>
                <div id="queue"></div>
                <input id="file_upload01" name="file_upload01" type="file" >
            </td>
            </tr>
            </table>
            <div style="display: inline;">
                <div id="closeDialog01" class="button01 roundCorner" onclick="swtCloseDialog('driverDialog01');" onselectstart="return false;" style="float: left;">
                cancel
                </div>
                <div style="float: left;">&nbsp&nbsp</div>
                <div id="insertDriver01" class="button01 roundCorner" onclick="swtInsertDriver();" onselectstart="return false;" style="float: left;">
                insert driver
                </div>
            </div>
            <div style="clear: both;">&nbsp&nbsp</div>
        </div>
        <div >&nbsp&nbsp</div>
    </div>

</div>

<?php

$html1->outPageBodyNext();

?>
    
<script>

swtGetAvailableMachineList();



/*
$(function() {

});
//*/
</script>

<p>&nbsp</p>
<p>&nbsp</p>

<div id="logMsg">xxx</div>

<?php

$html1->outPageBodyEnd();

?>