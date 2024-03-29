<?php
/**
 * ****************************************************************************
 * marquee - MODULE FOR XOOPS
 * Copyright (c) Herv� Thouzard (http://www.herve-thouzard.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Herv� Thouzard (http://www.herve-thouzard.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         marquee
 * @author 			Herv� Thouzard (http://www.herve-thouzard.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */

// Script to list the recent links from the mylinks module version 1.10
function b_marquee_mylinks($limit, $dateformat, $itemssize)
{
	include_once XOOPS_ROOT_PATH.'/modules/marquee/include/functions.php';
	include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
	$block=array();
	$myts =& MyTextSanitizer::getInstance();
	$db =& Database::getInstance();
	$result = $db->query("SELECT m.lid, m.cid, m.title, m.date, m.hits, m.submitter, c.title as catitle FROM ".$db->prefix("mylinks_links")." m, ".$db->prefix("mylinks_cat")." c WHERE (c.cid=m.cid) AND (m.status>0) ORDER BY date DESC",$limit,0);
	while($myrow = $db->fetchArray($result)) {
		$title = $myts->htmlSpecialChars($myrow["title"]);
		if($itemssize>0) {
			$title=xoops_substr($title,0,$itemssize+3);
		}
		$block[]=array(	'date'	=> formatTimestamp($myrow['date'],$dateformat),
						'category' =>$myts->htmlSpecialChars($myrow['catitle']),
						'author'=> $myrow['submitter'] ,
						'title'=> $title,
						'link' =>"<a href='".XOOPS_URL.'/modules/mylinks/singlelink.php?cid='.$myrow['cid']."&amp;lid=".$myrow['lid']."'>".$title.'</a>');
	}
	return $block;
}
?>