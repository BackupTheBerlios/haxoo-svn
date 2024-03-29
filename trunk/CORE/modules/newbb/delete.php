<?php
/**
 * CBB 4.0, or newbb, the forum module for XOOPS project
 *
 * @copyright	The XOOPS Project http://xoops.sf.net
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Taiwen Jiang (phppp or D.J.) <phppp@users.sourceforge.net>
 * @since		4.00
 * @version		$Id $
 * @package		module::newbb
 */

include 'header.php';

$ok = isset($_POST['ok']) ? intval($_POST['ok']) : 0;
foreach (array('forum', 'topic_id', 'post_id', 'order', 'pid', 'act') as $getint) {
    ${$getint} = isset($_POST[$getint]) ? intval($_POST[$getint]) : 0;
}
foreach (array('forum', 'topic_id', 'post_id', 'order', 'pid', 'act') as $getint) {
    ${$getint} = (${$getint})?${$getint}:(isset($_GET[$getint]) ? intval($_GET[$getint]) : 0);
}
$viewmode = (isset($_GET['viewmode']) && $_GET['viewmode'] != 'flat') ? 'thread' : 'flat';
$viewmode = ($viewmode)?$viewmode: (isset($_POST['viewmode'])?$_POST['viewmode'] : 'flat');

$forum_handler =& xoops_getmodulehandler('forum', 'newbb');
$topic_handler =& xoops_getmodulehandler('topic', 'newbb');
$post_handler =& xoops_getmodulehandler('post', 'newbb');

if ( !empty($post_id) ) {
    $topic =& $topic_handler->getByPost($post_id);
} else {
    $topic =& $topic_handler->get($topic_id);
}
$topic_id = $topic->getVar('topic_id');
if ( !$topic_id ) {
	$redirect = empty($forum)?"index.php":'viewforum.php?forum='.$forum;
    redirect_header($redirect, 2, _MD_ERRORTOPIC);
    exit();
}

$forum = $topic->getVar('forum_id');
$forum_obj =& $forum_handler->get($forum);
if (!$forum_handler->getPermission($forum_obj)) {
    redirect_header("index.php", 2, _MD_NORIGHTTOACCESS);
    exit();
}

$isadmin = newbb_isAdmin($forum_obj);
$uid = is_object($xoopsUser)? $xoopsUser->getVar('uid'):0;

$post_obj =& $post_handler->get($post_id);
$topic_status = $topic->getVar('topic_status');
if ( $topic_handler->getPermission($topic->getVar("forum_id"), $topic_status, 'delete')
	&& ( $isadmin || $post_obj->checkIdentity() )) {}
else {
	redirect_header("viewtopic.php?topic_id=$topic_id&amp;order=$order&amp;viewmode=$viewmode&amp;pid=$pid&amp;forum=$forum", 2, _MD_DELNOTALLOWED);
    exit();
}

if (!$isadmin && !$post_obj->checkTimelimit('delete_timelimit')) {
	redirect_header("viewtopic.php?forum=$forum&amp;topic_id=$topic_id&amp;post_id=$post_id&amp;order=$order&amp;viewmode=$viewmode&amp;pid=$pid",2,_MD_TIMEISUPDEL);
	exit();
}

if ($xoopsModuleConfig['wol_enabled']) {
	$online_handler =& xoops_getmodulehandler('online', 'newbb');
	$online_handler->init($forum_obj);
}

if ( $ok ) {
    $isDeleteOne = (1 == $ok)? true : false;
    if ($post_obj->isTopic() && $topic->getVar("topic_replies")==0) $isDeleteOne=false;
    if ($isDeleteOne && $post_obj->isTopic() && $topic->getVar("topic_replies")>0) {
       	//$post_handler->emptyTopic($post_obj);
        redirect_header("viewtopic.php?topic_id=$topic_id&amp;order=$order&amp;viewmode=$viewmode&amp;pid=$pid&amp;forum=$forum", 2, _MD_POSTFIRSTWITHREPLYNODELETED);
        exit();
    } else {
        if (!empty($_POST['post_text']))
        {
            //send a message
            $member_handler =& xoops_gethandler('member');
            $senduser =& $member_handler->getUser($post_obj->getVar('uid'));
            if ($senduser->getVar('notify_method') > 0)
            {
                $xoopsMailer =& xoops_getMailer();
                $xoopsMailer->reset();
                if ($senduser->getVar('notify_method')==1)
                    $xoopsMailer->usePM();
                else
                    $xoopsMailer->useMail();
                $xoopsMailer->setToUsers($senduser); 
                $xoopsMailer->setFromName($xoopsUser->getVar('uname'));
                $xoopsMailer->setSubject(_MD_DELEDEDMSG_SUBJECT);
                $forenurl = XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/viewtopic.php?topic_id=".$post_obj->getVar('topic_id');
                $xoopsMailer->setBody(sprintf(_MD_DELEDEDMSG_BODY, $senduser->getVar('uname'),$forenurl, $_POST['post_text'],$xoopsUser->getVar('uname'),$xoopsConfig['sitename'] ,XOOPS_URL . "/"  ));
                $xoopsMailer->send();
            }   
        }        
        $post_handler->delete($post_obj, $isDeleteOne);
		$forum_handler->synchronization($forum);
		$topic_handler->synchronization($topic_id);
    }

	$post_obj->loadFilters("delete");
    if ( $isDeleteOne ) {
        redirect_header("viewtopic.php?topic_id=$topic_id&amp;order=$order&amp;viewmode=$viewmode&amp;pid=$pid&amp;forum=$forum", 2, _MD_POSTDELETED);
    } else {
        redirect_header("viewforum.php?forum=$forum", 2, _MD_POSTSDELETED);
    }
	exit();

} else {
    include XOOPS_ROOT_PATH."/header.php";
	//xoops_confirm(array('post_id' => $post_id, 'viewmode' => $viewmode, 'order' => $order, 'forum' => $forum, 'topic_id' => $topic_id, 'ok' => 1), 'delete.php', _MD_DEL_ONE);
    echo '<div class="confirmMsg">' . _MD_DEL_ONE . '<br />
          <form method="post" action="delete.php">';
    echo _MD_DELEDEDMSG . '<br />';
    echo '<textarea name="post_text" cols="50" rows="5"></textarea><br />';  
    echo '<input type="hidden" name="post_id" value="' . htmlspecialchars($post_id) . '" />';     
    echo '<input type="hidden" name="order" value="' . htmlspecialchars($order) . '" />';
    echo '<input type="hidden" name="forum" value="' . htmlspecialchars($forum) . '" />';
    echo '<input type="hidden" name="topic_id" value="' . htmlspecialchars($topic_id) . '" />';
    echo '<input type="hidden" name="ok" value="1" />';
    echo $GLOBALS['xoopsSecurity']->getTokenHTML();
    echo '<input type="submit" name="confirm_submit" value="' . _SUBMIT .'" title="' . _SUBMIT .'"/>
          <input type="button" name="confirm_back" value="' . _CANCEL . '" onclick="javascript:history.go(-1);" title="' . _CANCEL . '" />
          </form>
          </div>';
	if ($isadmin) {
    	xoops_confirm(array('post_id' => $post_id, 'viewmode' => $viewmode, 'order' => $order, 'forum' => $forum, 'topic_id' => $topic_id, 'ok' => 99), 'delete.php', _MD_DEL_RELATED);
	}
	include XOOPS_ROOT_PATH.'/footer.php';
}
?>