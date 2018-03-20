<?php

include_once __dir__ . "/../configuration/swtConfig.php";
include_once __dir__ . "/../userManage/swtUserManager.php";

class CSwtHtmlTemple
{
    private $pageTitle = "";
    private $pagePrePath = "";
    private $userLoged = false;
    public  $userName = "";

	public function __construct( $_title, $_prePath)
	{
        $userChecker = new CUserManger();
        $this->userLoged = $userChecker->tryLogIn();

        $this->userName = $userChecker->getSessionOrCookieInfo("userName");
        
		$this->pageTitle = $_title;
        $this->pagePrePath = $_prePath;
	}
    
    public function outPageHead($_linkTags, $_scriptTags)
    {
        echo "<!DOCTYPE html>\n";
        echo "<html>\n";
        echo "<head>\n";
		echo "    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n";
        echo "    <meta http-equiv=\"Content-Type\" content=\"text/html\" charset=\"utf-8\"/>\n";
        echo "    <meta id=\"viewport\" name=\"viewport\" content=\"width=device-width, initial-scale=0.33, maximum-scale=0.33, user-scalable=no\">\n";
        echo "    <title>" . $this->pageTitle . "</title>\n";
        
        echo "    <link rel=\"stylesheet\" href=\"" . $this->pagePrePath . "jslibs/jquery-ui/jquery-ui.min.css\" type=\"text/css\" />\n";
        echo "    <link rel=\"stylesheet\" href=\"" . $this->pagePrePath . "css/main.css?v=201711151440\" type=\"text/css\" />\n";
        echo "    <link rel=\"stylesheet\" href=\"" . $this->pagePrePath . "jslibs/simple_calendar/tcal.css\" type=\"text/css\" />\n";
        echo $_linkTags . "\n";
        echo "    <script src=\"" . $this->pagePrePath . "jslibs/some/moment.js\"></script>\n";
        echo "    <script src=\"" . $this->pagePrePath . "jslibs/some/uiFuncs.js?v=201711151449\"></script>\n";
        echo "    <script src=\"" . $this->pagePrePath . "jslibs/simple_calendar/tcal.js\"></script>\n";
        echo "    <script src=\"" . $this->pagePrePath . "jslibs/jquery.js?v=201608191638\"></script>\n";
        
        echo "    <script src=\"" . $this->pagePrePath . "jslibs/jquery-ui/jquery-ui.min.js\"></script>\n";
        echo $_scriptTags . "\n";
        echo "</head>\n";
    }

    public function outPageBodyStart()
    {
        
        
        echo "<body>\n";
        echo "<div id=\"pageMask\" class=\"pageMask\" style=\"display: none;\">\n";
        echo "<div id=\"pageMaskImg\" class=\"pageMaskImg\" style=\"display: none;\">\n";
        echo "<img src=\"../images/loading.gif\" width=\"50px\" height=\"50px\" />\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "<div class = \"mainBodyFrame\">\n";
        echo "<div class = \"pageTitle\">\n";
        echo "<img src=\"" . $this->pagePrePath . "images/logo01.png\" width=\"220px\" height=\"50px\" />\n";
        echo "</div>\n";
        echo "<div id = \"pageHeader\" class = \"pageHeader\">\n"; // roundCorner02
        //echo "<div class=\"pageHeaderItem\" onselectstart=\"return false;\" onclick=\"window.location.href='" . $this->pagePrePath . "'\">mainpage</div>\n";
        echo "</div>\n";
        echo "<div >\n";
        echo "&nbsp\n";
        echo "</div>\n";
        echo "<div class = \"pageContent\">\n";
        echo "    <div class = \"pageLeftBar2\">\n";
        
        echo "<div id=\"pageLeftBarAccordion\" >\n";
        echo "    <h3>Microbench Reports</h3>\n";
        echo "    <div style=\"width: 100%;\">\n";
        echo "        <div id=\"simpleCalender01\" class=\"simpleCalender\"></div>\n";
        echo "        <a href=\"" . $this->pagePrePath . "subPages/drawGraph2.php\">Historical Graphs</a>\n";
        echo "        <a href=\"" . $this->pagePrePath . "./\">Report Archive</a>\n";
        echo "    </div>\n";
        echo "    <h3>Administration</h3>\n";
        echo "    <div>\n";
        
        $userChecker = new CUserManger();
        
        if ($userChecker->isManager())
        {
            //echo "        <a href=\"" . $this->pagePrePath . "subPages/newTask.php\">new task</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/batchList.php\">Batch List</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/importLogFiles2.php\">MicroBench Import</a>\n";
            //echo "        <a href=\"" . $this->pagePrePath . "subPages/importLogFilesSkynet.php\">Impt Skynet Res</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/compileReportBatchID.php\">MicroBench Report</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/importLogFiles3.php\">ShaderBench Import</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/compileReportShaderBench.php\">ShaderBench Report</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/compileReportAll.php\">Generate All Reports</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/userLogOut.php\">Logout</a>\n";
        }
        else if ($userChecker->isUser())
        {
            echo "        <a href=\"" . $this->pagePrePath . "subPages/genReportOutUserVer4.php\">Generate Reports</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/batchListOutUser.php\">Batch List</a>\n";
            //echo "        <a href=\"" . $this->pagePrePath . "subPages/genReportOutUser.php\">generate Reports</a>\n";
            //echo "        <a href=\"" . $this->pagePrePath . "subPages/importLogFilesOutUser.php\">import results</a>\n";
            //echo "        <a href=\"" . $this->pagePrePath . "subPages/compileReportBatchID.php\">generate report</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/helpForOutUser.php\">Help Center</a>\n";
            echo "        <a href=\"" . $this->pagePrePath . "subPages/userLogOut.php\">Logout</a>\n";
        }
        else
        {
            echo "        <a href=\"" . $this->pagePrePath . "subPages/userLogIn.php\">Login</a>\n";
        }
        echo "    </div>\n";
        echo "</div>\n";
        
        echo "    </div>\n";
        echo "    <div class = \"pageMiddleSpace\">\n";
        echo "        &nbsp\n";
        echo "    </div>\n";
        echo "    <div id = \"pageRightPart\" class = \"pageRightPart roundCorner02\">\n";
    }
    
    public function outPageBodyNext()
    {
        echo "    </div>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n";
    }
    
    public function outPageBodyCheckLog()
    {
        $userChecker = new CUserManger();
        if ($userChecker->isUser() == false)
        {
            echo "<script>\n";
            echo "window.location.href = \"userLogIn.php\";\n";
            echo "</script>\n";
        }
    }
    
    public function outPageBodyEnd()
    {
        global $_GET;
        
        echo "<script>\n";
        //echo "  $(\"#pageLeftBarAccordion\").accordion({\n";
        //echo "  collapsible: true,\n";
        //echo "  heightStyle: \"auto\"\n";
        //echo "});\n";
        $pageDate = isset($_GET["pageDate"]) ? floatval($_GET["pageDate"]) : "null";
        echo "    swtInitAccordion('pageLeftBarAccordion', '" . $this->pagePrePath . "', " . $pageDate . ");";
        
        //echo "function showMask()\n";
        //echo "{\n";
        //echo "    $(\"#pageMask\").css(\"height\",$(document).height());\n";
        //echo "    $(\"#pageMask\").css(\"width\",$(document).width());\n";
        //echo "    $(\"#pageMask\").show();\n";
        //echo "}\n";
        //echo "\n";
        //echo "function hideMask()\n";
        //echo "{\n";
        //echo "    $(\"#pageMask\").hide();\n";
        //echo "}\n";
        
        echo "</script>\n";
        
        echo "</body>\n";
        echo "</html>\n";
    }
}


?>