<?php

/**
* $Id: admin.php,v 1.9 2005/04/21 15:09:32 malanciault Exp $
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

define("_AM_SPARTNER_ABOUT", "大约");
define("_AM_SPARTNER_ACTION", "行动");
define("_AM_SPARTNER_ACTIVATE_PARTNER", "激活联营");
define("_AM_SPARTNER_ACTIVE", _CO_SPARTNER_ACTIVE);
define("_AM_SPARTNER_ACTIVE_EDIT_SUCCESS", "该联营公司已成功编辑。");
define("_AM_SPARTNER_ACTIVE_EDITING", "积极联系编辑");
define("_AM_SPARTNER_ACTIVE_EDITING_INFO", "您可以编辑此积极联系。马上要修改将在用户的副作用。");
define("_AM_SPARTNER_ACTIVE_EXP", "<b>主动联系</b> ：本网站的实际积极联系。");
define("_AM_SPARTNER_ACTIVE_PARTNERS", "主动联系");
define("_AM_SPARTNER_ACTIVE_PARTNERS_DSC", "下面是一个积极的联营公司名单。这些联营公司将显示在用户端。您可以编辑或删除关于相关的<b>操作</b>列按钮每个员工。");
define("_AM_SPARTNER_ALL", "全部");
define("_AM_SPARTNER_ALL_EXP", "<b>所有状况</b> ：模块的所有员工，无论其地位如何。");
define("_AM_SPARTNER_ALLITEMS", "模块的所有员工");
define("_AM_SPARTNER_ALLITEMSMSG", "选择一个身份，看看所选的地位的所有可用联营。您也可以选择所显示的联系排序。");
define("_AM_SPARTNER_APPROVE", "批准");
define("_AM_SPARTNER_APPROVING", "审批");
define("_AM_SPARTNER_ASC", "递增");
define("_AM_SPARTNER_AVAILABLE", "<span style='font-weight: bold; color: green;'>可用的</span>");
define("_AM_SPARTNER_BLOCKS", "块和团体");
define("_AM_SPARTNER_BLOCKSANDGROUPS", "块和团体");
define("_AM_SPARTNER_BLOCKSTXT", "该模块有以下模块，您可以配置在这里或在系统模块。");
define("_AM_SPARTNER_BY", "通过");
define("_AM_SPARTNER_CANCEL", _CO_SPARTNER_CANCEL);
define("_AM_SPARTNER_CLEAR", _CO_SPARTNER_CLEAR);
define("_AM_SPARTNER_CREATE", _CO_SPARTNER_CREATE);
define("_AM_SPARTNER_CREATETHEDIR", "创建文件夹");
define("_AM_SPARTNER_DELETE", "删除");
define("_AM_SPARTNER_DELETEPARTNER", _CO_SPARTNER_DELETEPARTNER);
define("_AM_SPARTNER_DELETETHISP", "你真的要删除此联营？");
define("_AM_SPARTNER_DESC", "降序");
define("_AM_SPARTNER_DESCRIPTION", _CO_SPARTNER_DESCRIPTION);
define("_AM_SPARTNER_DESCRIPTION_DSC", _CO_SPARTNER_DESCRIPTION_DSC);
define("_AM_SPARTNER_DIRCREATED", "成功创建文件夹");
define("_AM_SPARTNER_DIRNOTCREATED", "该文件夹不能创建");
define("_AM_SPARTNER_EDITING", "编辑");
define("_AM_SPARTNER_EDITPARTNER", _CO_SPARTNER_EDITPARTNER);
define("_AM_SPARTNER_GOMOD", "转到模块");
define("_AM_SPARTNER_GROUPS", "团体");
define("_AM_SPARTNER_GROUPSINFO", "配置模块和模块为每个组的权限");
define("_AM_SPARTNER_HELP", "帮助");
define("_AM_SPARTNER_HITS", _CO_SPARTNER_HITS);
define("_AM_SPARTNER_ID", "准的id");
define("_AM_SPARTNER_IMPORT", "进口");
define("_AM_SPARTNER_IMPORT_ALL_PARTNERS", "所有员工");
define("_AM_SPARTNER_IMPORT_AUTOAPPROVE", "自动批准");
define("_AM_SPARTNER_IMPORT_BACK", "回到导入页面");
define("_AM_SPARTNER_IMPORT_ERROR", "时出错进口联营。");
define("_AM_SPARTNER_IMPORT_FILE_NOT_FOUND", "导入文件没有找到<b>虏％</b>");
define("_AM_SPARTNER_IMPORT_FROM", "从％导入š");
define("_AM_SPARTNER_IMPORT_INFO", "您可以导入直接SmartAssociate联系。只要选择模块，从中您想导入联营和&#39;进口&#39;按钮。");
define("_AM_SPARTNER_IMPORT_MODULE_FOUND", "％s模块被发现。有％š可以导入的同伙。");
define("_AM_SPARTNER_IMPORT_NO_MODULE", "正如XoopsAssociates不在此站点上安装，没有同伙进口。");
define("_AM_SPARTNER_IMPORT_PARTNER_ERROR", "发生错误，进口&#39;％š&#39;。");
define("_AM_SPARTNER_IMPORT_RESULT", "这里是进口的结果。");
define("_AM_SPARTNER_IMPORT_SETTINGS", "进口设置");
define("_AM_SPARTNER_IMPORT_SUCCESS", "该联营公司已成功导入的模块。");
define("_AM_SPARTNER_IMPORT_TITLE", "进出口联营公司");
define("_AM_SPARTNER_IMPORT_XOOPSPARTNERS_110", "协会从XoopsAssociates 1.1");
define("_AM_SPARTNER_IMPORTED_PARTNER", "进口联营<em>：％š</em>");
define("_AM_SPARTNER_IMPORTED_PARTNERS", "联营公司进口<em>：％š</em>");
define("_AM_SPARTNER_IMPORT_SELECTION", "进口选择");
define("_AM_SPARTNER_IMPORT_SELECT_FILE", "联营公司");
define("_AM_SPARTNER_IMPORT_SELECT_FILE_DSC", "选择模块从中导入联营。");
define("_AM_SPARTNER_INACTIVATE_PARTNER", "停用联营");
define("_AM_SPARTNER_INACTIVE", _CO_SPARTNER_INACTIVE);
define("_AM_SPARTNER_INACTIVE_EDITING", "编辑不活动的联营公司");
define("_AM_SPARTNER_INACTIVE_EDITING_INFO", "您可以编辑此无效联营。修改将被保存在该联营公司。不过，如果您想显示在用户端这个联系，您将需要设置的<b>状态</b>字段&#39;活动&#39;。");
define("_AM_SPARTNER_INACTIVE_EXP", "<b>无效联营</b> ：正在进行的联营公司，已成为出于某种原因，不爱运动。不活动的联系是不会显示在用户端。");
define("_AM_SPARTNER_INACTIVE_FIELD", "无效");
define("_AM_SPARTNER_INACTIVE_PARTNERS", "无效联营");
define("_AM_SPARTNER_INDEX", "指数");
define("_AM_SPARTNER_INTRO", _CO_SPARTNER_INTRO);
define("_AM_SPARTNER_INVENTORY", _CO_SPARTNER_INVENTORY);
define("_AM_SPARTNER_LOGO", _CO_SPARTNER_LOGO);
define("_AM_SPARTNER_LOGO_DSC", _CO_SPARTNER_LOGO_DSC);
define("_AM_SPARTNER_LOGO_UPLOAD", _CO_SPARTNER_LOGO_UPLOAD);
define("_AM_SPARTNER_LOGO_UPLOAD_DSC", _CO_SPARTNER_LOGO_UPLOAD_DSC);
define("_AM_SPARTNER_MODADMIN", "管理员：");
define("_AM_SPARTNER_MODIFY", _CO_SPARTNER_MODIFY);
define("_AM_SPARTNER_NAME", _CO_SPARTNER_NAME);
define("_AM_SPARTNER_NO", "否");
define("_AM_SPARTNER_NOPARTNERS", _CO_SPARTNER_NOPARTNERS);
define("_AM_SPARTNER_NOTAVAILABLE", "<span style='font-weight: bold; color: red;'>不适用</span>");
define("_AM_SPARTNER_OPTS", "偏好");
define("_AM_SPARTNER_OPTIONS", "选项");
define("_AM_SPARTNER_PARTNER", _CO_SPARTNER_PARTNER);
define("_AM_SPARTNER_PARTNER_APPROVE", "批准这项联营");
define("_AM_SPARTNER_PARTNER_CREATE", _CO_SPARTNER_PARTNER_CREATE);
define("_AM_SPARTNER_PARTNER_CREATED", _CO_SPARTNER_PARTNER_CREATED);
define("_AM_SPARTNER_PARTNER_CREATING", _CO_SPARTNER_PARTNER_CREATING);
define("_AM_SPARTNER_PARTNER_CREATING_DSC", _CO_SPARTNER_PARTNER_CREATING_DSC);
define("_AM_SPARTNER_PARTNER_DELETE", _CO_SPARTNER_PARTNER_DELETE);
define("_AM_SPARTNER_PARTNER_DELETE_ERROR", "发生错误时删除这一联营。");
define("_AM_SPARTNER_PARTNER_DELETE_SUCCESS", "该联营公司已成功删除。");
define("_AM_SPARTNER_PARTNER_EDIT", _CO_SPARTNER_PARTNER_EDIT);
define("_AM_SPARTNER_PARTNER_INFORMATIONS", _CO_SPARTNER_PARTNER_INFORMATIONS);
define("_AM_SPARTNER_PARTNER_NOT_CREATED", _CO_SPARTNER_PARTNER_NOT_CREATED);
define("_AM_SPARTNER_PARTNER_NOT_SELECTED", "您没有选择一个有效的联系。");
define("_AM_SPARTNER_PARTNER_NOT_UPDATED", _CO_SPARTNER_PARTNER_NOT_UPDATED);
define("_AM_SPARTNER_PARTNERS", _CO_SPARTNER_PARTNERS);
define("_AM_SPARTNER_PATH", "路径");
define("_AM_SPARTNER_PATH_ITEM", "上传项目");
define("_AM_SPARTNER_PATH_IMAGES", "协会标志");
define("_AM_SPARTNER_PATHCONFIGURATION", "模块路径配置");
define("_AM_SPARTNER_POSITION", "位置");
define("_AM_SPARTNER_REJECTED", _CO_SPARTNER_REJECTED);
define("_AM_SPARTNER_REJECTED_EXP", "<b>拒绝联营</b> ：提交已驳回联系。阿拒绝联营公司是不会显示在用户端。");
define("_AM_SPARTNER_REJECTED_PARTNERS", "被拒绝的联营公司");
define("_AM_SPARTNER_REJECTED_EDITING", "编辑拒绝联营");
define("_AM_SPARTNER_REJECTED_EDITING_INFO", "您可以编辑此拒绝联系。修改将被保存在该联营公司。不过，如果您想显示在用户端这个联系，您将需要设置的<b>状态</b>字段&#39;活动&#39;。");
define("_AM_SPARTNER_SELECT_SORT", "选择顺序：");
define("_AM_SPARTNER_SELECT_STATUS", "选择状态");
define("_AM_SB_SETMPERM", "设置权限");
define("_AM_SPARTNER_SHOWING", "显示");
define("_AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS", "SmartAssociate导入设置");
define("_AM_SPARTNER_SMARTPARTNER_IMPORT_SETTINGS_VALUE", "没有导入设置。请点击导入按钮开始导入。");
define("_AM_SPARTNER_STATUS", _CO_SPARTNER_STATUS);
define("_AM_SPARTNER_STATUS_DSC", "选择地位的联营公司。无效联营公司是不会显示在公共部分。");
define("_AM_SPARTNER_SUMMARY", _CO_SPARTNER_SUMMARY);
define("_AM_SPARTNER_SUMMARY_REQ", _CO_SPARTNER_SUMMARY_REQ);
define("_AM_SPARTNER_SUMMARY_DSC", _CO_SPARTNER_SUMMARY_DSC);
define("_AM_SPARTNER_SUBMITTED", _CO_SPARTNER_SUBMITTED);
define("_AM_SPARTNER_SUBMITTED_APPROVE_SUCCESS", "提交的同伙已得到批准。");
define("_AM_SPARTNER_SUBMITTED_EXP", "<b>提交联营</b> ：已提交他们的企业能够成为这个网站的一同伙潜在联系。");
define("_AM_SPARTNER_SUBMITTED_INFO", "这种联系已经提交您的网站上。您可以编辑您想要的。您可以进行一些修改，如果你喜欢。经批准后，这个同事会显示在此网站的用户端。");
define("_AM_SPARTNER_SUBMITTED_PARTNERS", "提交联营");
define("_AM_SPARTNER_SUBMITTED_TITLE", "提交联营");
define("_AM_SPARTNER_TITLE", "联营公司名称");
define("_AM_SPARTNER_TITLE_REQ", _CO_SPARTNER_TITLE_REQ);
define("_AM_SPARTNER_TOTAL_SUBMITTED", "提交：");
define("_AM_SPARTNER_TOTAL_ACTIVE", "活动：");
define("_AM_SPARTNER_TOTAL_REJECTED", "被拒绝：");
define("_AM_SPARTNER_TOTAL_INACTIVE", "无效：");
define("_AM_SPARTNER_URL", _CO_SPARTNER_URL);
define("_AM_SPARTNER_URL_DSC", _CO_SPARTNER_URL_DSC);
define("_AM_SPARTNER_WEIGHT", _CO_SPARTNER_WEIGHT);
define("_AM_SPARTNER_WEIGHT_DSC", _CO_SPARTNER_WEIGHT_DSC);
define("_AM_SPARTNER_YES", "是");


//Redirect messages
define("_AM_SPARTNER_NOTSET_ACTIVE_SUCCESS", "该联营公司已创建。");
define("_AM_SPARTNER_NOTSET_INACTIVE_SUCCESS", "该联营公司已建立并停用。");
define("_AM_SPARTNER_SUBMITTED_ACTIVE_SUCCESS", "提交的同伙已被批准！");
define("_AM_SPARTNER_SUBMITTED_INACTIVE_SUCCESS", "提交的同伙已被停用。");
define("_AM_SPARTNER_SUBMITTED_REJECTED_SUCCESS", "提交的同伙已被拒绝。");
define("_AM_SPARTNER_ACTIVE_ACTIVE_SUCCESS", "该联营公司已被更新。");
define("_AM_SPARTNER_ACTIVE_INACTIVE_SUCCESS", "该联营公司已被停用。");
define("_AM_SPARTNER_INACTIVE_ACTIVE_SUCCESS", "不活动的联系已被激活。");
define("_AM_SPARTNER_INACTIVE_INACTIVE_SUCCESS", "不活动的联系已被更新。");
define("_AM_SPARTNER_REJECTED_ACTIVE_SUCCESS", "被拒绝的联营公司现已启动！");
define("_AM_SPARTNER_REJECTED_INACTIVE_SUCCESS", "被拒绝的同伙已被停用。");
define("_AM_SPARTNER_REJECTED_REJECTED_SUCCESS", "被拒绝的同伙已被更新。");
?><?php // Translation done by xtransam & wishcraft - 2010-01-31 09:35 ?>
