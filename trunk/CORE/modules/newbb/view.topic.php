<?php
// $Id: view.topic.php,v 1.3 2005/10/19 17:20:28 phppp Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  Author: phppp (D.J., infomax@gmail.com)                                  //
//  URL: http://xoopsforge.com, http://xoops.org.cn                          //
//  Project: Article Project                                                 //
//  ------------------------------------------------------------------------ //
include 'header.php';
mod_loadFunctions("read", "newbb");
mod_loadFunctions("render", "newbb");
// To enable image auto-resize by js
$xoops_module_header .= '<script src="'.XOOPS_URL.'/Frameworks/textsanitizer/xoops.js" type="text/javascript"></script>';

/*
 * Build the page query
 */
$query_vars = array("post_id", "topic_id", "status", "order", "start", "move", "mode", "viewmode");
$query_array = array();
foreach ($query_vars as $var) {
	if (!empty($_GET[$var])) $query_array[$var] = "{$var}={$_GET[$var]}";
}
$page_query = htmlspecialchars(implode("&", array_values($query_array)));
unset($query_array);

$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
$post_id = !empty($_GET['post_id']) ? intval($_GET['post_id']) : 0;
$forum_id = !empty($_GET['forum']) ? intval($_GET['forum']) : 0;
$move = isset($_GET['move'])? strtolower($_GET['move']) : '';
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$status = (!empty($_GET['status']) && in_array($_GET['status'], array("active", "pending", "deleted")))? $_GET['status'] : "";
$mode = !empty($_GET['mode']) ? intval($_GET['mode']) : (!empty($status) ? 2 : 0);

if ( !$topic_id && !$post_id ) {
	$redirect = empty($forum_id) ? "index.php" : "viewforum.php?forum={$forum_id}";
    redirect_header($redirect, 2, _MD_ERRORTOPIC);
}

$topic_handler =& xoops_getmodulehandler('topic', 'newbb');
if ( !empty($post_id) ) {
    $topic_obj =& $topic_handler->getByPost($post_id);
} elseif (!empty($move)) {
    $topic_obj = $topic_handler->getByMove($topic_id, ($move == "prev")?-1:1, $forum_id);
    $topic_id = $topic_obj->getVar("topic_id");
} else {
    $topic_obj = $topic_handler->get($topic_id);
}
if ( !is_object($topic_obj) || !$topic_id = $topic_obj->getVar('topic_id') ) {
	$redirect = empty($forum_id) ? "index.php" : "viewforum.php?forum={$forum_id}";
    redirect_header($redirect, 2, _MD_ERRORTOPIC);
}
$forum_id = $topic_obj->getVar('forum_id');
$forum_handler = xoops_getmodulehandler('forum', 'newbb');
$forum_obj = $forum_handler->get($forum_id);

$isadmin = newbb_isAdmin($forum_obj);

if ( !$isadmin && $topic_obj->getVar('approved') < 0 ) {
    redirect_header("viewforum.php?forum=".$forum_id, 2, _MD_NORIGHTTOVIEW);
    exit();
}
if (!$forum_handler->getPermission($forum_obj)) {
    redirect_header("index.php", 2, _MD_NORIGHTTOACCESS);
    exit();
}
/* Only admin has access to admin mode */
if (!$isadmin) {
	$status = "";
	$mode = 0;
}
if ($mode) {
	$_GET['viewmode'] = "flat";
}

if (!$topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "view")) {
    redirect_header("viewforum.php?forum=".$forum_id, 2, _MD_NORIGHTTOVIEW);
    exit();
}

$karma_handler =& xoops_getmodulehandler('karma', 'newbb');
$user_karma = $karma_handler->getUserKarma();

$valid_modes = array("flat", "thread", "compact");
$viewmode_cookie = newbb_getcookie("V");
if (isset($_GET['viewmode']) && in_array($_GET['viewmode'], $valid_modes)) {
	newbb_setcookie("V", $_GET['viewmode'], $forumCookie['expire']);
}
$viewmode = isset($_GET['viewmode'])? $_GET['viewmode'] : 
			(
				!empty($viewmode_cookie)?
				$viewmode_cookie:
				(
					@$valid_modes[$xoopsModuleConfig['view_mode']-1]
				)
			);
$viewmode = in_array($viewmode, $valid_modes)?$viewmode:"flat";
$order = (isset($_GET['order']) && in_array(strtoupper($_GET['order']),array("DESC","ASC")))?$_GET['order']:"ASC";

$total_posts = $topic_handler->getPostCount($topic_obj, $status);

if ($viewmode == "thread") {
    $xoopsOption['template_main'] =  'newbb_viewtopic_thread.html';
    if (!empty($xoopsModuleConfig["posts_for_thread"]) && $total_posts > $xoopsModuleConfig["posts_for_thread"]) {
	    redirect_header("view.topic.php?topic_id=$topic_id&amp;viewmode=flat", 2, _MD_EXCEEDTHREADVIEW);
	    exit();
    }
	$postsArray = $topic_handler->getAllPosts($topic_obj, $order, $total_posts, $start, 0, $status);
} else {
    $xoopsOption['template_main'] =  'newbb_viewtopic_flat.html';
    $postsArray = $topic_handler->getAllPosts($topic_obj, $order, $xoopsModuleConfig['posts_per_page'], $start, $post_id, $status);
}

$topic_obj->incrementCounter();
newbb_setRead("topic", $topic_id, $topic_obj->getVar("topic_last_post_id"));

if (!empty($xoopsModuleConfig['rss_enable'])) {
	$xoops_module_header .= '<link rel="alternate" type="application/rss+xml" title="'.$xoopsModule->getVar('name').'-'.$forum_obj->getVar('forum_name').'" href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/rss.php?f='.$forum_obj->getVar("forum_id").'" />';
}
$xoops_pagetitle = $topic_obj->getVar('topic_title') . ' [' . $xoopsModule->getVar('name') ." - ". $forum_obj->getVar('forum_name') . "]";

$xoopsOption['xoops_pagetitle']= $xoops_pagetitle;
$xoopsOption['xoops_module_header']= $xoops_module_header;
include XOOPS_ROOT_PATH."/header.php";
if ($xoopsTpl->xoops_canUpdateFromFile() && is_dir(XOOPS_THEME_PATH."/".$xoopsConfig['theme_set']."/templates/".$xoopsModule->getVar("dirname", "n"))) {
	$xoopsTpl->assign('newbb_template_path', XOOPS_THEME_PATH."/".$xoopsConfig['theme_set']."/templates/".$xoopsModule->getVar("dirname", "n"));
} else {
	$xoopsTpl->assign('newbb_template_path', XOOPS_ROOT_PATH."/modules/".$xoopsModule->getVar("dirname", "n")."/templates");
}
$xoopsTpl->assign('xoops_pagetitle', $xoops_pagetitle);
$xoopsTpl->assign('xoops_module_header', $xoops_module_header);

if ($xoopsModuleConfig['wol_enabled']) {
	$online_handler =& xoops_getmodulehandler('online', 'newbb');
	$online_handler->init($forum_obj, $topic_obj);
    $xoopsTpl->assign('online', $online_handler->show_online());
}

$xoopsTpl->assign("parentforum", $forum_handler->getParents($forum_obj));

$xoopsTpl->assign(array(
	'topic_title' 		=> '<a href="'.XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/view.topic.php?topic_id='.$topic_id.'">'. $topic_obj->getFullTitle().'</a>', 
	'forum_name' 		=> $forum_obj->getVar('forum_name'), 
	'lang_nexttopic' 	=> _MD_NEXTTOPIC, 
	'lang_prevtopic' 	=> _MD_PREVTOPIC
	));

$category_handler =& xoops_getmodulehandler("category");
$category_obj =& $category_handler->get($forum_obj->getVar("cat_id"), array("cat_title"));
$xoopsTpl->assign('category', array("id" => $forum_obj->getVar("cat_id"), "title" => $category_obj->getVar('cat_title')));

$xoopsTpl->assign('folder_topic', newbb_displayImage($forumImage['folder_topic']));

$xoopsTpl->assign('topic_id', $topic_id);
$xoopsTpl->assign('forum_id', $forum_id);

$order_current = ($order == 'DESC')?'DESC':'ASC';
$xoopsTpl->assign('order_current', $order_current);

$t_new = newbb_displayImage($forumImage['t_new'], _MD_POSTNEW);
$t_reply = newbb_displayImage($forumImage['t_reply'], _MD_REPLY);

if ($topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "post")) {
    $xoopsTpl->assign('forum_post_or_register', "<a href=\"newtopic.php?forum=".$forum_id."\">".$t_new."</a>");
} elseif ( !empty($GLOBALS["xoopsModuleConfig"]["show_reg"]) ) {
    if ($topic_obj->getVar('topic_status')) {
    	$xoopsTpl->assign('forum_post_or_register', _MD_TOPICLOCKED);
    } elseif ( !is_object($xoopsUser)) {
    	$xoopsTpl->assign('forum_post_or_register', '<a href="'.XOOPS_URL.'/user.php?xoops_redirect='.htmlspecialchars($xoopsRequestUri).'">'._MD_REGTOPOST.'</a>');
	}
} else {
    $xoopsTpl->assign('forum_post_or_register', '');
}
if ($topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "reply")) {
    $xoopsTpl->assign('forum_reply', "<a href=\"reply.php?topic_id=".$topic_id."\">".$t_reply."</a>");
}

$poster_array = array();
$require_reply = false;
foreach ($postsArray as $eachpost) {
	if ($eachpost->getVar('uid')>0) $poster_array[$eachpost->getVar('uid')] = 1;
	if ($eachpost->getVar('require_reply')>0) $require_reply = true;
}
$userid_array=array();
$online = array();
if (count($poster_array) > 0) {
	$member_handler =& xoops_gethandler('member');
	$userid_array = array_keys($poster_array);
	$user_criteria = "(".implode(",",$userid_array).")";
	$users = $member_handler->getUsers( new Criteria('uid', $user_criteria, 'IN'), true);
} else {
	$users = array();
}

if ($xoopsModuleConfig['wol_enabled'] && $online_handler) {
	$online = $online_handler->checkStatus(array_keys($poster_array));
}

if ($xoopsModuleConfig['groupbar_enabled']) {
	$groups_disp = array();
	$groups = $member_handler->getGroups();
	$count = count($groups);
	for ($i = 0; $i < $count; $i++) {
		$groups_disp[$groups[$i]->getVar('groupid')] = $groups[$i]->getVar('name');
	}
	unset($groups);
}

$viewtopic_users = array();
if (count($userid_array)>0) {
	$user_handler =& xoops_getmodulehandler('user', 'newbb');
	$user_handler->setUsers($users);
	$user_handler->groups =& $groups_disp;
	$user_handler->status =& $online;
	foreach ($userid_array as $userid) {
		$viewtopic_users[$userid] =& $user_handler->get($userid);
	    if (!$forum_obj->getVar('allow_sig')) {
	        $viewtopic_users[$userid]["signature"] = "";
	    }
	}
}
unset($users);

if ($xoopsModuleConfig['allow_require_reply'] && $require_reply) {
	if (!empty($xoopsModuleConfig['cache_enabled'])) {
		$viewtopic_posters = newbb_getsession("t".$topic_id, true);
		if (!is_array($viewtopic_posters) || count($viewtopic_posters)==0) {
			$viewtopic_posters = $topic_handler->getAllPosters($topic_obj);
			newbb_setsession("t".$topic_id, $viewtopic_posters);
		}
	} else {
		$viewtopic_posters = $topic_handler->getAllPosters($topic_obj);
	}
} else {
	$viewtopic_posters =array();
}

if ($viewmode == "thread") {
	if (!empty($post_id)) {
		$post_handler =& xoops_getmodulehandler('post', 'newbb');
		$currentPost = $post_handler -> get($post_id);
		
		if (!$isadmin && $currentPost->getVar('approved')<0 ) {
		    redirect_header("view.topic.php?topic_id=".$topic_id, 2, _MD_NORIGHTTOVIEW);
		    exit();
		}

		$top_pid = $topic_handler->getTopPostId($topic_id);
	} else {
		$currentPost =& $topic_handler->getTopPost($topic_id);
	    $top_pid = $currentPost->getVar('post_id');
	}

    $xoopsTpl->append('topic_posts', $currentPost->showPost($isadmin));
	
    $postArray =& $topic_handler->getPostTree($postsArray);
    if ( count($postArray) > 0 ) {
        foreach ($postArray as $treeItem) {
            $topic_handler->showTreeItem($topic_obj, $treeItem);
            if ($treeItem['post_id'] == $post_id) $treeItem['subject'] = '<strong>'.$treeItem['subject'].'</strong>';
            $xoopsTpl->append("topic_trees", array("post_id" => $treeItem['post_id'], "post_time" => $treeItem['post_time'], "post_image" => $treeItem['icon'], "post_title" => $treeItem['subject'], "post_prefix" => $treeItem['prefix'], "poster" => $treeItem['poster']));
        }
        unset($postArray);
    }
}
else {
    foreach ($postsArray as $eachpost) {
    	$xoopsTpl->append('topic_posts', $eachpost->showPost($isadmin));
    }

    if ( $total_posts > $xoopsModuleConfig['posts_per_page'] ) {
        include XOOPS_ROOT_PATH.'/class/pagenav.php';
        $nav = new XoopsPageNav($total_posts, $xoopsModuleConfig['posts_per_page'], $start, "start", 'topic_id='.$topic_id.'&amp;viewmode='.$viewmode.'&amp;order='.$order.'&amp;status='.$status."&amp;mode=".$mode);
        $xoopsTpl->assign('forum_page_nav', $nav->renderNav(4));
    } else {
        $xoopsTpl->assign('forum_page_nav', '');
    }
}
unset($postsArray);

$xoopsTpl->assign('topic_print_link', "print.php?form=1&amp;{$page_query}");

$admin_actions = array();

$ad_merge = "";
$ad_move = "";
$ad_delete = "";
$ad_lock = "";
$ad_unlock = "";
$ad_sticky = "";
$ad_unsticky = "";
$ad_digest = "";
$ad_undigest = "";

$admin_actions['merge'] = array(
	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=merge&amp;topic_id='.$topic_id,
	"name" => _MD_MERGETOPIC,
	"image" => $ad_merge);
$admin_actions['move'] = array(
	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=move&amp;topic_id='.$topic_id,
	"name" => _MD_MOVETOPIC,
	"image" => $ad_move);
$admin_actions['delete'] = array(
	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=delete&amp;topic_id='.$topic_id,
	"name" => _MD_DELETETOPIC,
	"image" => $ad_delete);
if ( !$topic_obj->getVar('topic_status') ) {
    $admin_actions['lock'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=lock&amp;topic_id='.$topic_id,
    	"image"=>$ad_lock,
    	"name"=>_MD_LOCKTOPIC);
} else {
    $admin_actions['unlock'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=unlock&amp;topic_id='.$topic_id,
    	"image"=>$ad_unlock,
    	"name"=>_MD_UNLOCKTOPIC);
}
if ( !$topic_obj->getVar('topic_sticky') ) {
    $admin_actions['sticky'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=sticky&amp;topic_id='.$topic_id,
    	"image"=>$ad_sticky,
    	"name"=>_MD_STICKYTOPIC);
} else {
    $admin_actions['unsticky'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=unsticky&amp;topic_id='.$topic_id,
    	"image"=>$ad_unsticky,
    	"name"=>_MD_UNSTICKYTOPIC);
}
if ( !$topic_obj->getVar('topic_digest') ) {
    $admin_actions['digest'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=digest&amp;topic_id='.$topic_id,
    	"image"=>$ad_digest,
    	"name"=>_MD_DIGESTTOPIC);
} else {
    $admin_actions['undigest'] = array(
    	"link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/topicmanager.php?mode=undigest&amp;topic_id='.$topic_id,
    	"image"=>$ad_undigest,
    	"name"=>_MD_UNDIGESTTOPIC);
}
$xoopsTpl->assign_by_ref('admin_actions', $admin_actions);

$xoopsTpl->assign('viewer_level', ($isadmin) ? 2 : is_object($xoopsUser) );

if ($xoopsModuleConfig['show_permissiontable']) {
	$perm_handler =& xoops_getmodulehandler('permission', 'newbb');
	$permission_table = $perm_handler->permission_table($forum_obj, $topic_obj->getVar('topic_status'), $isadmin);
	$xoopsTpl->assign_by_ref('permission_table', $permission_table);
	unset($permission_table);
}

///////////////////////////////
// show Poll
if (
	(
		$topic_obj->getVar('topic_haspoll')
		&& $topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "vote")
	)
	|| $topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "addpoll")
) {
	@include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspoll.php";
	@include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolloption.php";
	@include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolllog.php";
	@include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspollrenderer.php";
}

if ( $topic_obj->getVar('topic_haspoll') 
	&& $topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "vote")
) {

	$xoopsTpl->assign('topic_poll', 1);
	$poll = new XoopsPoll($topic_obj->getVar('poll_id'));
	$renderer = new XoopsPollRenderer($poll);
	$uid = is_object($xoopsUser)?$xoopsUser->getVar("uid"):0;
	if ( XoopsPollLog::hasVoted($topic_obj->getVar('poll_id'), $_SERVER['REMOTE_ADDR'], $uid) ) {
		$renderer->assignResults($xoopsTpl);
		$xoopsTpl->assign('topic_pollresult', 1);
		setcookie("bb_polls[".$topic_obj->getVar("poll_id")."]", 1);
	} else {
	    $renderer->assignForm($xoopsTpl);
	    $xoopsTpl->assign('lang_vote' , _PL_VOTE);
	    $xoopsTpl->assign('lang_results' , _PL_RESULTS);
		setcookie("bb_polls[".$topic_obj->getVar("poll_id")."]", 1);
	}
}
if ($topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "addpoll")
) {
	if (!$topic_obj->getVar('topic_haspoll')) {	
		if ( is_object($xoopsUser) && $xoopsUser->getVar("uid")==$topic_obj->getVar("topic_poster") ) {
			$t_poll = newbb_displayImage($forumImage['t_poll'],_MD_ADDPOLL);
			$xoopsTpl->assign('forum_addpoll', "<a href=\"polls.php?op=add&amp;topic_id=".$topic_id."\">".$t_poll."</a>&nbsp;");
		}
	} elseif ($isadmin
		|| (is_object($poll) && is_object($xoopsUser) && $xoopsUser->getVar("uid")==$poll->getVar("user_id") )
	) {
		$poll_edit = "";
	    $poll_delete = "";
	    $poll_restart = "";

		$adminpoll_actions = array();
	    $adminpoll_actions['editpoll'] = array(
	    	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/polls.php?op=edit&amp;poll_id='.$topic_obj->getVar('poll_id').'&amp;topic_id='.$topic_id,
	    	"image" => $poll_edit,
	    	"name" => _MD_EDITPOLL);
	    $adminpoll_actions['deletepoll'] = array(
	    	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/polls.php?op=delete&amp;poll_id='.$topic_obj->getVar('poll_id').'&amp;topic_id='.$topic_id,
	    	"image" => $poll_delete,
	    	"name" => _MD_DELETEPOLL);
	    $adminpoll_actions['restartpoll'] = array(
	    	"link" => XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n").'/polls.php?op=restart&amp;poll_id='.$topic_obj->getVar('poll_id').'&amp;topic_id='.$topic_id.'&amp;forum='.$forum_id,
	    	"image" => $poll_restart,
	    	"name" => _MD_RESTARTPOLL);

	    $xoopsTpl->assign_by_ref('adminpoll_actions', $adminpoll_actions);	
	    unset($adminpoll_actions);
	}
}
if (isset($poll)) unset($poll);
//endif;

$xoopsTpl->assign('p_up', newbb_displayImage($forumImage['p_up'],_MD_TOP));
$xoopsTpl->assign('rating_enable', $xoopsModuleConfig['rating_enabled']);
$xoopsTpl->assign('groupbar_enable', $xoopsModuleConfig['groupbar_enabled']);
$xoopsTpl->assign('anonymous_prefix', $xoopsModuleConfig['anonymous_prefix']);

$xoopsTpl->assign('threaded', newbb_displayImage($forumImage['threaded']));
$xoopsTpl->assign('flat', newbb_displayImage($forumImage['flat']));
$xoopsTpl->assign('left', newbb_displayImage($forumImage['left']));
$xoopsTpl->assign('right', newbb_displayImage($forumImage['right']));
$xoopsTpl->assign('down', newbb_displayImage($forumImage['doubledown']));
$xoopsTpl->assign('down2', newbb_displayImage($forumImage['down']));
$xoopsTpl->assign('up', newbb_displayImage($forumImage['up']));
$xoopsTpl->assign('printer', newbb_displayImage($forumImage['printer']));
$xoopsTpl->assign('personal', newbb_displayImage($forumImage['personal']));
$xoopsTpl->assign('post_content', newbb_displayImage($forumImage['post_content']));

if (!empty($xoopsModuleConfig['rating_enabled'])) {
	$xoopsTpl->assign('votes',$topic_obj->getVar('votes'));
	$rating = number_format($topic_obj->getVar('rating')/2, 0);
	if ( $rating < 1 ) {
		$rating_img = newbb_displayImage($forumImage['blank']);
	} else {
		$rating_img  = newbb_displayImage($forumImage['rate'.$rating]);
	}
	$xoopsTpl->assign('rating_img',$rating_img);
	$xoopsTpl->assign('rate1', newbb_displayImage($forumImage['rate1'], _MD_RATE1));
	$xoopsTpl->assign('rate2', newbb_displayImage($forumImage['rate2'], _MD_RATE2));
	$xoopsTpl->assign('rate3', newbb_displayImage($forumImage['rate3'], _MD_RATE3));
	$xoopsTpl->assign('rate4', newbb_displayImage($forumImage['rate4'], _MD_RATE4));
	$xoopsTpl->assign('rate5', newbb_displayImage($forumImage['rate5'], _MD_RATE5));
}

// create jump box
if (!empty($xoopsModuleConfig['show_jump'])) {
	mod_loadFunctions("forum", "newbb");
	$xoopsTpl->assign('forum_jumpbox', newbb_make_jumpbox($forum_id));
}
$xoopsTpl->assign(array('lang_forum_index' => sprintf(_MD_FORUMINDEX,htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)), 'lang_from' => _MD_FROM, 'lang_joined' => _MD_JOINED, 'lang_posts' => _MD_POSTS, 'lang_poster' => _MD_POSTER, 'lang_thread' => _MD_THREAD, 'lang_edit' => _EDIT, 'lang_delete' => _DELETE, 'lang_reply' => _REPLY, 'lang_postedon' => _MD_POSTEDON,'lang_groups' => _MD_GROUPS));

$viewmode_options = array();
if ($viewmode=="thread") {
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=flat&amp;topic_id=".$topic_id, "title"=>_FLAT);
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=compact&amp;topic_id=".$topic_id,	"title"=>_MD_COMPACT);
} elseif ($viewmode=="compact") {
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=thread&amp;topic_id=".$topic_id, "title"=>_THREADED);
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=flat&amp;order=".$order_current."&amp;topic_id=".$topic_id,	"title"=>_FLAT);
	if ($order == 'DESC') {
		$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=compact&amp;order=ASC&amp;topic_id=".$topic_id, "title"=>_OLDESTFIRST);
	} else {
		$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=compact&amp;order=DESC&amp;topic_id=".$topic_id, "title"=>_NEWESTFIRST);
	}
} else {
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=thread&amp;topic_id=".$topic_id, "title"=>_THREADED);
	$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=compact&amp;order=".$order_current."&amp;topic_id=".$topic_id, "title"=>_MD_COMPACT);
	if ($order == 'DESC') {
		$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=flat&amp;order=ASC&amp;status=$status&amp;topic_id=".$topic_id, "title"=>_OLDESTFIRST);
	} else {
		$viewmode_options[]= array("link"=>XOOPS_URL.'/modules/'.$xoopsModule->getVar("dirname", "n")."/view.topic.php?viewmode=flat&amp;order=DESC&amp;status=$status&amp;topic_id=".$topic_id, "title"=>_NEWESTFIRST);
	}
}
switch($status) {
	case 'active':
		$current_status = '['._MD_TYPE_ADMIN.']';
		break;
	case 'pending':
		$current_status = '['._MD_TYPE_PENDING.']';
		break;
	case 'deleted':
		$current_status = '['._MD_TYPE_DELETED.']';
		break;
	default:
		$current_status = '';
		break;
	}
$xoopsTpl->assign('topicstatus', $current_status);

$xoopsTpl->assign('mode', $mode);
$xoopsTpl->assign('status', $status);
$xoopsTpl->assign('viewmode_compact', ($viewmode=="compact")?1:0);
$xoopsTpl->assign_by_ref('viewmode_options', $viewmode_options);
unset($viewmode_options);
$xoopsTpl->assign('menumode',$menumode);
$xoopsTpl->assign('menumode_other',$menumode_other);

if ( !empty($xoopsModuleConfig['quickreply_enabled'])
	&& $topic_handler->getPermission($forum_obj, $topic_obj->getVar('topic_status'), "reply")
) {
	//include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

	require_once XOOPS_ROOT_PATH."/class/xoopsform/formelement.php";
	require_once XOOPS_ROOT_PATH."/class/xoopsform/formhidden.php";
	require_once XOOPS_ROOT_PATH."/class/xoopsform/formhiddentoken.php";
	require_once XOOPS_ROOT_PATH."/class/xoopsform/formbutton.php";
	require_once XOOPS_ROOT_PATH."/class/xoopsform/formelementtray.php";
	require_once XOOPS_ROOT_PATH."/class/xoopsform/themeform.php";
	$forum_form = new XoopsThemeForm(_MD_POSTREPLY, 'quick_reply', "post.php", 'post', true);

	if (!is_object($xoopsUser)) {
		require_once XOOPS_ROOT_PATH."/class/xoopsform/formpassword.php";
		require_once XOOPS_ROOT_PATH."/class/xoopsform/formcheckbox.php";
		require_once XOOPS_ROOT_PATH."/class/xoopsform/formtext.php";
		$config_handler =& xoops_gethandler('config');
		$user_tray = new XoopsFormElementTray(_MD_ACCOUNT);
		$user_tray->addElement(new XoopsFormText(_MD_NAME, "uname", 26, 255));
		$user_tray->addElement(new XoopsFormPassword(_MD_PASSWORD, "pass", 10, 32));
		$login_checkbox = new XoopsFormCheckBox('', 'login', 1);
		$login_checkbox->addOption(1, _MD_LOGIN);
		$user_tray->addElement($login_checkbox);
		$forum_form->addElement($user_tray, '');
	}

	$quickform = "textarea";
	$editor_configs = array();
	$editor_configs["caption"] = _MD_MESSAGEC;
	$editor_configs["name"] ="message";
	$editor_configs["rows"] = 10;
	$editor_configs["cols"] = 60;
	if (!$editor_handler =& xoops_gethandler("editor", true)) {
		require_once XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php";
		$editor_handler =& new XoopsEditorHandler();
	}
	$editor_object = & $editor_handler->get($quickform, $editor_configs, "", 1);
	$forum_form->addElement($editor_object, true);
	if (!is_object($xoopsUser)) {
		require_once XOOPS_ROOT_PATH."/class/xoopsform/formcaptcha.php";
		$forum_form->addElement(new XoopsFormCaptcha());
	}

	$forum_form->addElement(new XoopsFormHidden('dohtml', 0));
	$forum_form->addElement(new XoopsFormHidden('dosmiley', 1));
	$forum_form->addElement(new XoopsFormHidden('doxcode', 1));
	$forum_form->addElement(new XoopsFormHidden('dobr', 1));
	$forum_form->addElement(new XoopsFormHidden('attachsig', 1));

	$forum_form->addElement(new XoopsFormHidden('isreply', 1));
	
	$forum_form->addElement(new XoopsFormHidden('subject', _MD_RE.': '.$topic_obj->getVar('topic_title', 'e')));
	$forum_form->addElement(new XoopsFormHidden('pid', empty($post_id)?$topic_handler->getTopPostId($topic_id):$post_id));
	$forum_form->addElement(new XoopsFormHidden('topic_id', $topic_id));
	$forum_form->addElement(new XoopsFormHidden('forum', $forum_id));
	$forum_form->addElement(new XoopsFormHidden('viewmode', $viewmode));
	$forum_form->addElement(new XoopsFormHidden('order', $order));
	$forum_form->addElement(new XoopsFormHidden('start', $start));

	// backward compatible
	if (!class_exists("XoopsSecurity")) {
		$post_valid = 1;
		$_SESSION['submit_token'] = $post_valid;
		$forum_form->addElement(new XoopsFormHidden('post_valid', $post_valid));
	}

	$forum_form->addElement(new XoopsFormHidden('notify', -1));
	$forum_form->addElement(new XoopsFormHidden('contents_submit', 1));
	$submit_button = new XoopsFormButton('', 'quick_submit', _SUBMIT, "submit");
	$submit_button->setExtra('onclick="if (document.forms.quick_reply.message.value == \'RE\' || document.forms.quick_reply.message.value == \'\') { alert(\''._MD_QUICKREPLY_EMPTY.'\'); return false;} else { return true;}"');
	$button_tray = new XoopsFormElementTray('');
	$button_tray->addElement($submit_button);
	$forum_form->addElement($button_tray);

	$toggles = newbb_getcookie('G', true);
	$display = (in_array('qr', $toggles)) ? 'none;' : 'block;';
    $xoopsTpl->assign('quickreply', array( 'show' => 1, 'display' => $display, 'icon' => newbb_displayImage($forumImage['t_qr']), 'form' => $forum_form->render()));
	unset($forum_form);
} else {
	$xoopsTpl->assign('quickreply', array( 'show' => 0));
}

if ($xoopsModuleConfig["do_tag"] && @include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php") {
	$xoopsTpl->assign('tagbar', tagBar($topic_obj->getVar("topic_tags", "n")));
}

if ($transferbar = include(XOOPS_ROOT_PATH."/Frameworks/transfer/bar.transfer.php")) {
	$xoopsTpl->assign('transfer', $transferbar);
}

include XOOPS_ROOT_PATH.'/footer.php';
?>