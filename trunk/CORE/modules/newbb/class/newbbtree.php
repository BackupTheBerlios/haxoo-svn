<?php 
// $Id: newbbtree.php,v 1.3 2005/10/19 17:20:32 phppp Exp $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: phppp (D.J., infomax@gmail.com)                                  //
// URL: http://xoopsforge.com, http://xoops.org.cn                          //
// Project: Article Project                                                 //
// ------------------------------------------------------------------------ //
 
if (!defined("XOOPS_ROOT_PATH")) {
	exit();
}
include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";

class NewBBTree extends XoopsTree {
    var $prefix = '&nbsp;&nbsp;';
    var $increment = '&nbsp;&nbsp;';
    var $postArray = '';

    function NewBBTree($table_name, $id_name = "post_id", $pid_name = "pid")
    {
        $this->XoopsTree($table_name, $id_name, $pid_name);
    } 

    function setPrefix($val = '')
    {
        $this->prefix = $val;
        $this->increment = $val;
    } 

    function getAllPostArray($sel_id, $order = '')
    {
        $this->postArray = $this->getAllChild($sel_id, $order);
    } 

    function setPostArray($postArray)
    {
        $this->postArray = &$postArray;
    } 
    // returns an array of first child objects for a given id($sel_id)
    function getPostTree(&$postTree_array, $pid = 0, $prefix = '&nbsp;&nbsp;')
    {
        if (!is_array($postTree_array)) $postTree_array = array();

        $newPostArray = array();
        $prefix .= $this->increment;
        foreach ($this->postArray as $post) {
            if ($post->getVar('pid') == $pid) {
                $postTree_array[] = array('prefix' => $prefix,
                    'icon' => $post->getVar('icon'),
                    'post_time' => $post->getVar('post_time'),
                    'post_id' => $post->getVar('post_id'),
                    'forum_id' => $post->getVar('forum_id'),
                    'subject' => $post->getVar('subject'),
                    'poster_name' => $post->getVar('poster_name'),
                    'uid' => $post->getVar('uid')
                    );
                $this->getPostTree($postTree_array, $post->getVar('post_id'), $prefix);
            } else {
                $newPostArray[] = $post;
            } 
        } 
        $this->postArray = $newPostArray;
        unset($newPostArray);

        return true;
    } 
} 

?>