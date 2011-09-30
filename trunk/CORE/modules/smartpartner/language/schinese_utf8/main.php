<?php

/**
* $Id: main.php,v 1.7 2004/12/19 17:48:07 malanciault Exp $
* Module: SmartAssociate
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

// Including common language file
Global $xoopsConfig;

$commonFile = "../include/common.php";
if (!file_exists($commonFile)) {
	$commonFile = "include/common.php";
}

include_once($commonFile);

$fileName = SMARTPARTNER_ROOT_PATH . 'language/' . $xoopsConfig['language'] . '/common.php';
if (file_exists($fileName)) {
	include_once $fileName;
} else {
	include_once SMARTPARTNER_ROOT_PATH . 'language/english/common.php';
}

define("_MD_SPARTNER_ADMIN_PAGE", "：：行政处：：");
define("_MD_SPARTNER_NOPARTNERSELECTED", "您没有选择一个有效的联系。");
define("_MD_SPARTNER_NOTIFY", "关于批准通知");
define("_MD_SPARTNER_READMORE", "读更多...");
define("_MD_SPARTNER_SUBMIT_ERROR", "发生错误时提交联营。");
define("_MD_SPARTNER_SUBMIT_SUCCESS", "该联营公司已成功提交。");

define("_MD_SPARTNER_EDIT", "编辑");
define("_MD_SPARTNER_DELETE", "删除");
define("_MD_SPARTNER_JOIN", "成为准");
define("_MD_SPARTNER_PARTNERS", "联营公司");
define("_MD_SPARTNER_PARTNERSTITLE", "该联营公司的");
define("_MD_SPARTNER_PARTNER", "协理");
define("_MD_SPARTNER_DESCRIPTION", "描述");
define("_MD_SPARTNER_HITS", "点击");
define("_MD_SPARTNER_NOPART", "目前还没有同伙显示。");
//file join.php
define("_MD_SPARTNER_IMAGE", "标志");
define("_MD_SPARTNER_TITLE", "名字");
define("_MD_SPARTNER_URL", "网址：");
define("_MD_SPARTNER_SEND", "发送请求");
define("_MD_SPARTNER_ERROR1", "你没有正确地填写表格。请再试一次。");
define("_MD_SPARTNER_ERROR2", "<center>图像尺寸大于110x50。 <a href='javascript:history.back(1)'>试着用另一种形象</a> </center>");
define("_MD_SPARTNER_ERROR3", "<center>图像文件不存在。 <a href='javascript:history.back(1)'>试着用另一种形象</a> </center>");
define("_MD_SPARTNER_GOBACK", "<a href='javascript:history.back(1)'>后面</a>");
define("_MD_SPARTNER_NEWPARTNER", "％š协会请求");
define("_MD_SPARTNER_SENDMAIL", "请求发送给管理员。 <br /> <a href='index.php'>返回首页</a>");


// Hack by marcan : More information on the SmartAssociate module
define("_MD_SPARTNER_INTRO_TEXT", "这里是我们首选的伙伴名单。你可以通过点击其标志他们的网站");
define("_MD_SPARTNER_INTRO_JOIN", "您想成为％的联营s吗？填写以下表单，我们将与您联系尽快。");
define("_MD_SPARTNER_TITLE_DSC", "你的组织名称");
define("_MD_SPARTNER_IMAGE_DSC", "您的徽标地址");
define("_MD_SPARTNER_URL_DSC", "您的网站");
define("_MD_SPARTNER_DESCRIPTION_DSC", "说明你的组织");
define("_MD_SPARTNER_BACKTOINDEX", "回到联营指数");

?><?php // Translation done by xtransam & wishcraft - 2010-01-31 09:35 ?>
