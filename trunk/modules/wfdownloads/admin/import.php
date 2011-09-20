<?php
// ========================================================
// Conversion file for any version before wf-downloads 3
// ========================================================
// This file contains 3 functions to do necessary updates either when
// converting from mydownloads to wf-downloads or upgrading any
// 2.x version of wf-downloads to version 3.
//
// Starting with WF-Downloads 3.00 we will have a different procedure
// since version information will be stored in the database of WF-Downloads
//
//      function import_mydownloads_to_wfdownloads
//         This one is needed to import data from mydownloads to wf-downloads
//
//      function update_tables_to_300
//         This function will convert any WF-Downloads 2.x format to 3.00 format
//
//      function invert_nohtm_dohtml_values
//         In WF-Downloads 3.00 some fieldnames have been changed and the namechange
//         comes along with a change of logic (0=yes;1=no --> 0=no;1=yes)
//         So the values need to be changed accordingly which is what this
//         function is doing. It needs to be run before "convert_tables_2x_to_300"
//         since it is based on the old fieldnamessince


include_once('admin_header.php');
include_once(WFDOWNLOADS_ROOT_PATH."include/update.php");
wfdownloads_xoops_cp_header();
wfdownloads_adminMenu(-1, "Import");
// =========================================================================================
// This function imports data from mydownloads into wf-downloads
// =========================================================================================
function import_mydownloads_to_wfdownloads()
{
    global $xoopsDB;

    echo "<br /><B>Importing Data</B><br />";
    $wfdownloads = array("cat" => $xoopsDB -> prefix("wfdownloads_cat"),
                         "downloads" => $xoopsDB -> prefix("wfdownloads_downloads"),
                         "broken" => $xoopsDB -> prefix("wfdownloads_broken"),
                         "mod" => $xoopsDB -> prefix("wfdownloads_mod"),
                         "votes" => $xoopsDB -> prefix("wfdownloads_votedata"));

    $mydownloads = array("cat" => $xoopsDB -> prefix("mydownloads_cat"),
                         "downloads" => $xoopsDB -> prefix("mydownloads_downloads"),
                         "broken" => $xoopsDB -> prefix("mydownloads_broken"),
                         "mod" => $xoopsDB -> prefix("mydownloads_mod"),
                         "votes" => $xoopsDB -> prefix("mydownloads_votedata"),
                         "text" => $xoopsDB -> prefix("mydownloads_text"));

    //Add temporary field to category table
    $xoopsDB->query("ALTER TABLE ".$wfdownloads['cat']." ADD `old_cid` int NOT NULL default 0");
    $xoopsDB->query("ALTER TABLE ".$wfdownloads['cat']." ADD `old_pid` int NOT NULL default 0");

    //Add temporary fields to downloads table
    $xoopsDB->query("ALTER TABLE ".$wfdownloads['downloads']." ADD `old_lid` int NOT NULL default 0,
                                                               ADD `old_cid` int NOT NULL default 0");

    //Get latest mod request ID to determine which records will need an updated lid value afterwards
    $result = $xoopsDB->query("SELECT MAX(requestid) FROM ".$wfdownloads['mod']);
    list($max_requestid) = $xoopsDB->fetchRow($result);
    //Get latest report ID to determine, which records will need an updated lid value afterwards
    $result = $xoopsDB->query("SELECT MAX(reportid) FROM ".$wfdownloads['broken']);
    list($max_reportid) = $xoopsDB->fetchRow($result);
    //Get latest vote ID to determine which records will need an updated lid value afterwards
    $result = $xoopsDB->query("SELECT MAX(ratingid) FROM ".$wfdownloads['votes']);
    list($max_ratingid) = $xoopsDB->fetchRow($result);

    //Import data into category table
    $xoopsDB->query("INSERT INTO ".$wfdownloads['cat']." (`old_cid`, `old_pid`, `title`, `imgurl`, `summary`) SELECT `cid`, `pid`, `title`, `imgurl`, '' FROM ".$mydownloads['cat']);
    echo "Imported ".$xoopsDB->getAffectedRows()." categories into ".$wfdownloads['cat']."<br />";
    //Import data into downloads table
    $xoopsDB->query("INSERT INTO ".$wfdownloads['downloads']." (`cid`, `old_lid`, `old_cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `submitter`, `status`, `published`, `hits`, `rating`, `votes`, `comments`, `features`, `requirements`, `dhistory`, `summary`, `description`)
                    SELECT 0,`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `submitter`, `status`, `date`, `hits`, `rating`, `votes`, `comments`, '', '','','', '' FROM ".$mydownloads['downloads']);
    echo "Imported ".$xoopsDB->getAffectedRows()." downloads into ".$wfdownloads['downloads']."<br />";
    //Import data into brokens table
    $xoopsDB->query("INSERT INTO ".$wfdownloads['broken']." (`lid`, `sender`, `ip`)
                     SELECT `lid`, `sender`, `ip` FROM ".$mydownloads['broken']);
    echo "Imported ".$xoopsDB->getAffectedRows()." broken reports into ".$wfdownloads['broken']."<br />";
    //Import data into votedata table
    $xoopsDB->query("INSERT INTO ".$wfdownloads['votes']." (`lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp`)
                     SELECT `lid`, `ratinguser`, `rating`, `ratinghostname`, `ratingtimestamp` FROM ".$mydownloads['votes']);
    echo "Imported ".$xoopsDB->getAffectedRows()." votes into ".$wfdownloads['votes']."<br />";
    //Import data into mod request table
    $xoopsDB->query("INSERT INTO ".$wfdownloads['mod']." (`lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `screenshot`, `description`, `modifysubmitter`,`features`, `requirements`, `publisher`, `dhistory`, `summary`)
                     SELECT `lid`, `cid`, `title`, `url`, `homepage`, `version`, `size`, `platform`, `logourl`, `description`, `modifysubmitter`,'','','','','' FROM ".$mydownloads['mod']);
    echo "Imported ".$xoopsDB->getAffectedRows()." modification requests into ".$wfdownloads['mod']."<br />";


    //Update category ID to new value
    $xoopsDB->query("UPDATE ".$wfdownloads['downloads']." d, ".$wfdownloads['cat']." c SET d.cid=c.cid WHERE d.old_cid=c.old_cid AND d.old_cid != 0");
    $xoopsDB->query("UPDATE ".$wfdownloads['cat']." c1, ".$wfdownloads['cat']." c2 SET c1.pid=c2.cid WHERE c1.old_pid=c2.old_cid AND c1.old_pid != 0");
    if ($max_requestid) {
        $xoopsDB->query("UPDATE ".$wfdownloads['mod']." m, ".$wfdownloads['cat']." c SET m.cid=c.cid WHERE m.requestid > ".$max_requestid." AND c.old_cid=m.cid");
        //Update lid values in mod table
        $xoopsDB->query("UPDATE ".$wfdownloads['mod']." m, ".$wfdownloads['downloads']." d SET m.lid=d.lid WHERE m.requestid > ".$max_requestid." AND m.lid=d.old_lid");
    }
    if ($max_ratingid) {
        //Update lid values in votedata table
        $xoopsDB->query("UPDATE ".$wfdownloads['votes']." v, ".$wfdownloads['downloads']." d SET v.lid=d.lid WHERE v.ratingid > ".$max_ratingid." AND v.lid=d.old_lid");
    }
    if ($max_reportid) {
    //Update lid values in brokens table
    $xoopsDB->query("UPDATE ".$wfdownloads['broken']." b, ".$wfdownloads['downloads']." d SET b.lid=d.lid WHERE b.reportid > ".$max_reportid." AND b.lid=d.old_lid");
    }
    //Update description
    $xoopsDB->query("UPDATE ".$wfdownloads['downloads']." d, ".$mydownloads['text']." t SET d.description=t.description WHERE t.lid=d.old_lid");

    //Remove temporary fields
    $xoopsDB->query("ALTER TABLE .".$wfdownloads['cat']." DROP `old_cid`");
    $xoopsDB->query("ALTER TABLE .".$wfdownloads['cat']." DROP `old_pid`");
    $xoopsDB->query("ALTER TABLE .".$wfdownloads['downloads']." DROP `old_cid`");
    $xoopsDB->query("ALTER TABLE .".$wfdownloads['downloads']." DROP `old_lid`");

    /**
     * Update comments
     */
    
    /* Not working well, so commented out for this release. We will see to posibly implement it in a futur release 
    $modhandler = & xoops_gethandler('module');
    $wfdownloadModule = & $modhandler -> getByDirname("wfdownloads");
    $wf_id = $wfdownloadModule -> getVar('mid');

    $modhandler = & xoops_gethandler('module');
    $downloadModule = & $modhandler -> getByDirname("mydownloads");
    $my_id = $downloadModule -> getVar('mid');
    $sql = "UPDATE " . $xoopsDB -> prefix("xoopscomments") . " SET com_modid = $wf_id WHERE com_modid = $my_id";
    if ($xoopsDB -> query($sql)) {
        echo "Moved ".$xoopsDB->getAffectedRows()." comments from MyDownloads to WF-Downloads<br />";
    }*/
}

$op = isset($_REQUEST['op']) ? intval($_REQUEST['op']) : 0;
switch ($op) {
    case 1:
        // Make sure that nohtml is properly changed to dohtml
        invert_nohtm_dohtml_values();
        // Ensure that the proper tables are present
        update_tables_to_300();
        // Import data from MyDownloads
        import_mydownloads_to_wfdownloads();
        break;

    default:
        //ask what to do
        include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
        $form = new XoopsThemeForm('Importing',"form", $_SERVER['REQUEST_URI']);

        //Is MyDownloads installed?
        $module_handler = xoops_gethandler('module');
        $mydownloadsModule = $module_handler->getByDirname("mydownloads");
        $got_options = false;
        if (is_object($mydownloadsModule)) {
            $mydownloadsButton = new XoopsFormButton("Import data from MyDownloads", "myd_button", "Import", "submit");
            $mydownloadsButton->setExtra("onclick='document.forms.form.op.value=\"1\"'");
            $form->addElement($mydownloadsButton);
        } else {
            $mydownloadsLabel = new XoopsFormLabel("Import data from MyDownloads", "Module MyDownloads not found on this site.");
            $form->addElement($mydownloadsLabel);
        }

        $form->addElement(new XoopsFormHidden('op', 0));
        $form->display();
        break;
}
wfdownloads_modFooter();
xoops_cp_footer();
?>