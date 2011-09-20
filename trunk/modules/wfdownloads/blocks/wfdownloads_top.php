<?php
/**
 * $Id: wfdownloads_top.php,v 1.4 2006/05/06 15:57:28 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

/**
 * Function: b_mydownloads_top_show
 * Input   : $options[0] = date for the most recent downloads
 *                     hits for the most popular downloads
 *            $block['content'] = The optional above content
 *            $options[1]   = How many downloads are displayes
 * Output  : Returns the most recent or most popular downloads
 */
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

function b_wfdownloads_top_show($options)
{
    global $xoopsUser;

    $block = array();

    $modhandler = xoops_gethandler('module');
    $wfModule = $modhandler->getByDirname("wfdownloads");
    $config_handler = xoops_gethandler('config');
    $wfModuleConfig = $config_handler->getConfigsByCat(0, $wfModule->getVar('mid'));

    $groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler = &xoops_gethandler('groupperm');
    $allowed_cats = $gperm_handler->getItemIds("WFDownCatPerm", $groups, $wfModule->getVar('mid'));

    $criteria = new Criteria("cid", "(".implode(',', $allowed_cats).")", "IN");
	$criteria = new CriteriaCompo(new Criteria('offline', 0));
    $criteria->setSort($options[0]);
    $criteria->setOrder("DESC");
    $criteria->setLimit($options[1]);
    $download_handler = xoops_getmodulehandler('download', 'wfdownloads');
    $downloads = $download_handler->getObjects($criteria);

    foreach (array_keys($downloads) as $i) {
        $download = $downloads[$i]->toArray();
        $download['title'] =  xoops_substr($download['title'], 0, ($options[2] -1));
        $download['id'] = $download['lid'];
        if ($options[0] == "published")
        {
            $download['date'] = formatTimestamp($download['published'], $wfModuleConfig['dateformat']);
        }
        $download['dirname'] = $wfModule->dirname();
        $block['downloads'][] = $download;
    }
    return $block;
}

function b_wfdownloads_top_edit($options)
{
    $form = "" . _MB_WFD_DISP . "&nbsp;";
    $form .= "<input type='hidden' name='options[]' value='";
    if ($options[0] == "published")
    {
        $form .= "published'";
    }
    else
    {
        $form .= "hits'";
    }
    $form .= " />";
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_WFD_FILES . "";
    $form .= "&nbsp;<br />" . _MB_WFD_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "' />&nbsp;" . _MB_WFD_LENGTH . "";
    return $form;
}

?>
