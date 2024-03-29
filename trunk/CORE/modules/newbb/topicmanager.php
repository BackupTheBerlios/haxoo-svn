<?php
// $Id: topicmanager.php,v 1.3 2005/10/19 17:20:28 phppp Exp $
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
include "header.php";

if ( isset($_POST['submit']) ) {
    foreach (array('forum', 'newforum', 'newtopic') as $getint) {
        ${$getint} = intval( @$_POST[$getint]);
    }
    foreach (array('topic_id') as $getint) {
        ${$getint} = @$_POST[$getint];
    }
    if (!is_array($topic_id)) $topic_id=array($topic_id);
} else {
    foreach (array('forum', 'topic_id') as $getint) {
        ${$getint} = intval(@$_GET[$getint]);
    }
}

if ( !$topic_id ) {
	$redirect = empty($forum_id)? "index.php" : "viewforum.php?forum={$forum}";
    redirect_header($redirect, 2, _MD_ERRORTOPIC);
}

$topic_handler =& xoops_getmodulehandler('topic', 'newbb');
$forum_handler =& xoops_getmodulehandler('forum', 'newbb');

if ($xoopsModuleConfig['wol_enabled']) {
	$online_handler =& xoops_getmodulehandler('online', 'newbb');
	$online_handler->init($forum);
}


$action_array = array('merge', 'delete','move','lock','unlock','sticky','unsticky','digest','undigest');
foreach ($action_array as $_action) {
    $action[$_action] = array(
	    "name"		=> $_action,
	    "desc" 		=> constant(strtoupper("_MD_DESC_{$_action}")),
	    "submit"	=> constant(strtoupper("_MD_{$_action}")),
	    'sql' 		=> "topic_{$_action}=1",
	    'msg' 		=> constant(strtoupper("_MD_TOPIC{$_action}"))
    );
}
$action['lock']['sql']		= 'topic_status = 1';
$action['unlock']['sql']	= 'topic_status = 0';
$action['unsticky']['sql']	= 'topic_sticky = 0';
$action['undigest']['sql']	= 'topic_digest = 0';
$action['digest']['sql']	= 'topic_digest = 1, digest_time = '.time();

// Disable cache
$xoopsConfig["module_cache"][$xoopsModule->getVar("mid")] = 0;
include XOOPS_ROOT_PATH.'/header.php';

if ( isset($_POST['submit']) ) {
	$mode = $_POST['mode'];
	if ('delete' == $mode) {  
		foreach ($topic_id as $tid) {
            $topic_obj =& $topic_handler->get($tid);
            $topic_handler->delete($topic_obj,false);
            $forum_handler->synchronization($forum);
            //$topic_obj->loadFilters("delete");
            //sync($topic_id, "topic");
            //xoops_notification_deletebyitem ($xoopsModule->getVar('mid'), 'thread', $topic_id);
        }
        echo $action[$mode]['msg']."<p><a href='viewforum.php?forum=$forum'>"._MD_RETURNTOTHEFORUM."</a></p><p><a href='index.php'>"._MD_RETURNFORUMINDEX."</a></p>";
	} elseif ('restore' == $mode) {  
		//$topic_handler =& xoops_getmodulehandler('topic', 'newbb');
        $forums = array();
        $topics_obj =& $topic_handler->getAll(new Criteria("topic_id", "(".implode(",", $topic_id).")", "IN"));
		foreach (array_keys($topics_obj) as $id) {
			$topic_obj =& $topics_obj[$id];
			$topic_handler->approve($topic_obj);            
			$topic_handler->synchronization($topic_obj);
			$forums[$topic_obj->getVar("forum_id")] = 1;   
		}
        $criteria = new Criteria('topic_id', "(".implode(",", $topic_id).")", "IN");
	    $post_handler =& xoops_getmodulehandler('post', 'newbb');
        $post_handler->updateAll("approved", 1, $criteria, true);            
		$criteria_forum = new Criteria("forum_id", "(".implode(",", array_keys($forums)).")", "IN");
		$forums_obj =& $forum_handler->getAll($criteria_forum);
        foreach (array_keys($forums_obj) as $id) {
			$forum_handler->synchronization($forums_obj[$id]);
		}
		unset($topics_obj, $forums_obj);		
        echo $action[$mode]['msg']."<p><a href='viewforum.php?forum=$forum'>"._MD_RETURNTOTHEFORUM."</a></p><p><a href='index.php'>"._MD_RETURNFORUMINDEX."</a></p>";
	
    } elseif ('merge' == $mode) {
		$post_handler =& xoops_getmodulehandler('post', 'newbb');
		foreach ($topic_id as $tid) {
            $topic_obj =& $topic_handler->get($tid);
            $newtopic_obj =& $topic_handler->get($newtopic);
            /* return false if destination topic is newer or not existing */
            if ($newtopic>$tid || !is_object($newtopic_obj)) {
                //redirect_header("javascript:history.go(-1)", 2, _MD_ERROR);
                //exit();
            }
		
            $criteria_topic = new Criteria("topic_id", $tid);
            $criteria = new CriteriaCompo($criteria_topic);
            $criteria->add(new Criteria('pid', 0));
            $post_handler->updateAll("pid", $topic_handler->getTopPostId($newtopic), $criteria, true);
            $post_handler->updateAll("topic_id", $newtopic, $criteria_topic, true);
        
            $topic_views =  $topic_obj->getVar("topic_views") + $newtopic_obj->getVar("topic_views");
            $criteria_newtopic = new Criteria("topic_id", $newtopic);
            $topic_handler->updateAll("topic_views", $topic_views, $criteria_newtopic, true);
        
            $topic_handler->synchronization($newtopic);
        
            $poll_id = $topic_handler->get($tid, "poll_id");
            if ($poll_id>0) {
                if (is_dir(XOOPS_ROOT_PATH."/modules/xoopspoll/")) {
                    include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspoll.php";
                    include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolloption.php";
                    include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolllog.php";
                    include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspollrenderer.php";
			
                    $poll = new XoopsPoll($poll_id);
                    if ( $poll->delete() != false ) {
                        XoopsPollOption::deleteByPollId($poll->getVar("poll_id"));
                        XoopsPollLog::deleteByPollId($poll->getVar("poll_id"));
                        xoops_comment_delete($xoopsModule->getVar('mid'), $poll->getVar('poll_id'));
                    }
                }
            }
		
            $sql = sprintf("DELETE FROM %s WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $tid);
            $result = $xoopsDB->queryF($sql);
        
            $sql = sprintf("DELETE FROM %s WHERE topic_id = %u", $xoopsDB->prefix("bb_votedata"), $tid);
            $result = $xoopsDB->queryF($sql);

            $sql = sprintf("UPDATE %s SET forum_topics = forum_topics-1 WHERE forum_id = %u", $xoopsDB->prefix("bb_forums"), $forum);
            $result = $xoopsDB->queryF($sql);

            $topic_obj->loadFilters("delete");
            $newtopic_obj->loadFilters("update");
        
	    }
        echo $action[$mode]['msg'].
        		"<p><a href='viewtopic.php?topic_id=$newtopic'>"._MD_VIEWTHETOPIC."</a></p>".
        		"<p><a href='viewforum.php?forum=$forum'>"._MD_RETURNTOTHEFORUM."</a></p>".
        		"<p><a href='index.php'>"._MD_RETURNFORUMINDEX."</a></p>";
	} elseif ('move' == $mode) {
        if ($newforum > 0) {
            $topic_id = $topic_id[0];
			$topic_obj =& $topic_handler->get($topic_id);
			$topic_obj->loadFilters("update");
			$topic_obj->setVar("forum_id", $newforum, true);
			$topic_handler->insert($topic_obj, true);
			$topic_obj->loadFilters("update");
			
            $sql = sprintf("UPDATE %s SET forum_id = %u WHERE topic_id = %u", $xoopsDB->prefix("bb_posts"), $newforum, $topic_id);
            if ( !$r = $xoopsDB->query($sql) ) {
	            return false;
            }
			$forum_handler->synchronization($newforum);
			$forum_handler->synchronization($forum);
        	echo $action[$mode]['msg']."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$newforum'>"._MD_GOTONEWFORUM."</a></p><p><a href='index.php'>"._MD_RETURNFORUMINDEX."</a></p>";
        } else {
    		redirect_header("javascript:history.go(-1)",2,_MD_ERRORFORUM);
        }
    } else {
        $topic_id = $topic_id[0];
        $forum = $topic_handler->get($topic_id, "forum_id");
        $forum_new = !empty($newtopic)?$topic_handler->get($newtopic, "forum_id"):0;

        if (!$forum_handler->getPermission($forum, 'moderate')
            || (!empty($forum_new) && !$forum_handler->getPermission($forum_new, 'reply')) // The forum for the topic to be merged to
            || (!empty($newforum) && !$forum_handler->getPermission($newforum, 'post')) // The forum to be moved to
        ) {
            redirect_header("viewtopic.php?forum=$forum&amp;topic_id=$topic_id",2,_NOPERM);
            exit();
        }
        $sql = sprintf("UPDATE %s SET ".$action[$mode]['sql']." WHERE topic_id = %u", $xoopsDB->prefix("bb_topics"), $topic_id);
        if ( !$r = $xoopsDB->query($sql) ) {
    		redirect_header("viewtopic.php?forum=$forum&amp;topic_id=$topic_id&amp;order=$order&amp;viewmode=$viewmode", 2, _MD_ERROR_BACK.'<br />sql:'.$sql);
	        exit();
        }
        if ('digest' == $mode && $xoopsDB->getAffectedRows()) {
	    	$topic_obj =& $topic_handler->get($topic_id);
            $stats_handler =& xoops_getmodulehandler('stats', 'newbb');
			$stats_handler->update($topic_obj->getVar("forum_id"), "digest");
            $userstats_handler =& xoops_getmodulehandler('userstats', 'newbb');            
			if ($user_stat = $userstats_handler->get($topic_obj->getVar("topic_poster"))) { 
                $z = $user_stat->getVar("user_digests") + 1 ;  
				$user_stat->setVar("user_digests", intval($z));
				$userstats_handler->insert($user_stat);
			}
        }
        echo $action[$mode]['msg']."<p><a href='viewtopic.php?topic_id=$topic_id&amp;forum=$forum'>"._MD_VIEWTHETOPIC."</a></p><p><a href='viewforum.php?forum=$forum'>"._MD_RETURNFORUMINDEX."</a></p>";
    }
} else {  // No submit
    $mode = $_GET['mode'];
    echo "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
    echo "<table border='0' cellpadding='1' cellspacing='0' align='center' width='95%'>";
    echo "<tr><td class='bg2'>";
    echo "<table border='0' cellpadding='1' cellspacing='1' width='100%'>";
    echo "<tr class='bg3' align='left'>";
    echo "<td colspan='2' align='center'>".$action[$mode]['desc']."</td></tr>";

    if ( $mode == 'move' ) {
        echo '<tr><td class="bg3">'._MD_MOVETOPICTO.'</td><td class="bg1">';
        $box = '<select name="newforum" size="1">';

		$category_handler =& xoops_getmodulehandler('category', 'newbb');
	    $categories = $category_handler->getByPermission('access');
	    $forums = $forum_handler->getForumsByCategory(array_keys($categories), 'post', false);
	
		if (count($categories) > 0 && count($forums) > 0) {
			foreach (array_keys($forums) as $key) {
	            $box .= "<option value='-1'>[".$categories[$key]->getVar('cat_title')."]</option>";
	            foreach ($forums[$key] as $forumid=>$_forum) {
	                $box .= "<option value='".$forumid."'>-- ".$_forum['title']."</option>";
					if ( !isset($_forum["sub"])) continue; 
		            foreach (array_keys($_forum["sub"]) as $fid) {
		                $box .= "<option value='".$fid."'>---- ".$_forum["sub"][$fid]['title']."</option>";
	                }
	            }
			}
	    } else {
	        $box .= "<option value='-1'>"._MD_NOFORUMINDB."</option>";
	    }
    	unset($forums, $categories);
          
    	echo $box;      
        echo '</select></td></tr>';
    }
    if ( $mode == 'merge' ) {
        echo '<tr><td class="bg3">'._MD_MERGETOPICTO.'</td><td class="bg1">';
        echo _MD_TOPIC."ID-$topic_id -> ID: <input name='newtopic' value='' />";
        echo '</td></tr>';
    }
    echo '<tr class="bg3"><td colspan="2" align="center">';
    echo "<input type='hidden' name='mode' value='".$action[$mode]['name']."' />";
    echo "<input type='hidden' name='topic_id' value='".$topic_id."' />";
    echo "<input type='hidden' name='forum' value='".$forum."' />";
    echo "<input type='submit' name='submit' value='". $action[$mode]['submit']."' />";
    echo "</td></tr></form></table></td></tr></table>";
}
include XOOPS_ROOT_PATH.'/footer.php';
?>