<?php

/**
* $Id: modinfo.php,v 1.9 2005/05/17 20:23:55 fx2024 Exp $
* Module: SmartAssociate
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

define('_MI_SPARTNER_ADMENU1', 'Index');
define('_MI_SPARTNER_ADMENU2', 'Associates');
define('_MI_SPARTNER_ADMENU3', 'Blocks');
define('_MI_SPARTNER_ADMENU4', 'Groups');
define('_MI_SPARTNER_ADMENU5', 'Got to module');

define('_MI_SPARTNER_PARTNERS_NAME', 'SmartAssociate');
define('_MI_SPARTNER_PARTNERS_DESC', 'Associates Management System for your XOOPS 2.x site');
define('_MI_SPARTNER_PARTNERS_ADMIN', 'Manage Associates');
define('_MI_SPARTNER_PARTNERS_ADMIN_ADD', 'Add Associate');
define('_MI_SPARTNER_ID', 'ID');
define('_MI_SPARTNER_HITS', 'Hits');
define('_MI_SPARTNER_TITLE', 'Title');
define('_MI_SPARTNER_WEIGHT', 'Weight');
define('_MI_SPARTNER_RECLICK', 'Reclick Time:');
define('_MI_SPARTNER_IMAGES', 'Images');
define('_MI_SPARTNER_TEXT', 'Text Links');
define('_MI_SPARTNER_BOTH', 'Both');
define('_MI_SPARTNER_MLIMIT', 'Limit main page to xx entries: (0 = no limit)');
define('_MI_SPARTNER_MSHOW', 'On main page show:');
define('_MI_SPARTNER_INDEX_SORTBY', 'Sort the main page on :');
define('_MI_SPARTNER_INDEX_SORTBY_DSC', '');
define('_MI_SPARTNER_INDEX_ORDERBY', 'Order the main page on :');
define('_MI_SPARTNER_INDEX_ORDERBY_DSC', '');
define('_MI_SPARTNER_HOUR', '1 hour');
define('_MI_SPARTNER_3HOURS', '3 hours');
define('_MI_SPARTNER_5HOURS', '5 hours');
define('_MI_SPARTNER_10HOURS', '10 hours');
define('_MI_SPARTNER_DAY', '1 day');

define('_MI_SPARTNER_BLOCK_PARTNERS_LIST', 'Associates');
define('_MI_SPARTNER_BLOCK_PARTNERS_LIST_DESC', 'Displays a list of associates');

define("_MI_SPARTNER_ALLOWSUBMIT", "Associate submissions");
define("_MI_SPARTNER_ALLOWSUBMITDSC", "Allow Associate submission on your web site ?");

define("_MI_SPARTNER_ANONPOST", "Allow anonymous submission");
define("_MI_SPARTNER_ANONPOSTDSC", "Allow anonymous users submit a associates");

define('_MI_SPARTNER_AUTOAPPROVE', "Auto approve submitted associates");
define('_MI_SPARTNER_AUTOAPPROVE_DSC', "Auto approves submitted associates without admin intervention.");

define('_MI_SPARTNER_PERPAGE_USER', "Maximum associates per page (User side):");
define('_MI_SPARTNER_PERPAGE_USER_DSC', "Maximum number of associates per page to be displayed at once in the user side.");

define('_MI_SPARTNER_PERPAGE_ADMIN', "Maximum associates per page (Admin side):");
define('_MI_SPARTNER_PERPAGE_ADMIN_DSC', "Maximum number of associates per page to be displayed at once in the admin side.");

define('_MI_SPARTNER_WELCOMEMSG', 'Welcome message:');
define('_MI_SPARTNER_WELCOMEMSG_DSC', 'Welcome message to be displayed in the index page of the module.');
define('_MI_SPARTNER_WELCOMEMSG_DEF', "Here are the associates of this site. Click on the logo for more informations."); 

define('_MI_SPARTNER_USEIMAGENAVPAGE', 'Use the image Page Navigation:');
define('_MI_SPARTNER_USEIMAGENAVPAGEDSC', 'If you set this option to "Yes", the Page Navigation will be displayed with image, otherwise, the original Page Naviagation will be used.');

define('_MI_SPARTNER_IMG_MAX_WIDTH', "Maximum width of the associate's logo:");
define('_MI_SPARTNER_IMG_MAX_WIDTH_DSC', "This will be the maximum width of a logo that is uploaded to the module. It will also be the width of the first column in the associates index page.");

define('_MI_SPARTNER_IMG_MAX_HEIGHT', "Maximum height of the associate's logo:");
define('_MI_SPARTNER_IMG_MAX_HEIGHT_DSC', "This will be the maximum height of a logo that is uploaded to the module.");

define('_MI_SPARTNER_HELP_PATH_SELECT', "Path of SmartAssociate help files");
define('_MI_SPARTNER_HELP_PATH_SELECT_DSC', "Select from where you would like to access SmartAssociate's help files. If you downloaded the 'SmartAssociate's Help Package' and uploaded it in 'modules/smartassociates/doc/', you can select 'Inside the module'. Alternatively, you can access the module's help file directly from docs.xoops.org by chosing this in the selector. You can also select 'Custom Path' and specify yourself the path of the help files in the next config option 'Custom path of SmartAssociate's help files'");

define('_MI_SPARTNER_HELP_PATH_CUSTOM', "Custom path of SmartAssociate's help files");
define('_MI_SPARTNER_HELP_PATH_CUSTOM_DSC', "If you selected 'Custom path' in the previous option 'Path of SmartAssociate's help files', please specify the URL of SmartAssociate's help files, in that format : http://www.yoursite.com/doc");

define('_MI_SPARTNER_HELP_INSIDE', "Inside the module");
define('_MI_SPARTNER_HELP_CUSTOM', "Custom Path");

define('_MI_SPARTNER_STATS_GROUP', "Display the Statistics block for these groups");
define('_MI_SPARTNER_STATS_GROUP_DSC', "Select the group that will be able to see the statistics blocks in the associates page.");

define('_MI_SPARTNER_BACKTOINDEX', "Display the 'Back to associates index' link");
define('_MI_SPARTNER_BACKTOINDEX_DSC', "Select 'Yes' to display the 'Back to associates index' link on the associate's page.");

define("_MI_SPARTNER_HIGHLIGHT_COLOR", "Color used for highlighting searched words");
define("_MI_SPARTNER_HIGHLIGHT_COLORDSC", "");

define("_MI_SPARTNER_HIDE_MOD_NAME", "Hide module's name in the user's pages");
define("_MI_SPARTNER_HIDE_MOD_NAMEDSC", "");


// Text for notifications
define('_MI_SPARTNER_GLOBAL_PARTNER_NOTIFY', "Global associates");
define('_MI_SPARTNER_GLOBAL_PARTNER_NOTIFY_DSC', "Notification options that apply to all associates.");

define('_MI_SPARTNER_PARTNER_NOTIFY', "Associate");
define('_MI_SPARTNER_PARTNER_NOTIFY_DSC', "Notification options that apply to the current associates.");

define('_MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY', "Associate submitted");
define('_MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_CAP', "Notify me when any associates is submitted and is awaiting approval.");
define('_MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_DSC', "Receive notification when any associates is submitted and is waiting approval.");
define('_MI_SPARTNER_GLOBAL_PARTNER_SUBMITTED_NOTIFY_SBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : New associates submitted");

define('_MI_SPARTNER_PARTNER_APPROVED_NOTIFY', "Associate approved");
define('_MI_SPARTNER_PARTNER_APPROVED_NOTIFY_CAP', "Notify me when this associates is approved.");   
define('_MI_SPARTNER_PARTNER_APPROVED_NOTIFY_DSC', "Receive notification when this associates is approved.");      
define('_MI_SPARTNER_PARTNER_APPROVED_NOTIFY_SBJ', "[{X_SITENAME}] {X_MODULE} auto-notify : Associate approved"); 


// About.php constants
define('_MI_SPARTNER_AUTHOR_INFO', "Developers");
define('_MI_SPARTNER_DEVELOPER_LEAD', "Lead developer(s)");
define('_MI_SPARTNER_DEVELOPER_CONTRIBUTOR', "Contributor(s)");
define('_MI_SPARTNER_DEVELOPER_WEBSITE', "Website");
define('_MI_SPARTNER_DEVELOPER_EMAIL', "Email");
define('_MI_SPARTNER_DEVELOPER_CREDITS', "Credits");
define('_MI_SPARTNER_DEMO_SITE', "SmartFactory Demo Site");
define('_MI_SPARTNER_MODULE_INFO', "Module Developpment Informations");
define('_MI_SPARTNER_MODULE_STATUS', "Status");
define('_MI_SPARTNER_MODULE_RELEASE_DATE', "Release date");
define('_MI_SPARTNER_MODULE_DEMO', "Demo Site");
define('_MI_SPARTNER_MODULE_SUPPORT', "Official support site");
define('_MI_SPARTNER_MODULE_BUG', "Report a bug for this module");
define('_MI_SPARTNER_MODULE_SUBMIT_BUG', "Submit a bug");
define('_MI_SPARTNER_MODULE_FEATURE', "Suggest a new feature for this module");
define('_MI_SPARTNER_MODULE_SUBMIT_FEATURE', "Request a feature");
define('_MI_SPARTNER_MODULE_DISCLAIMER', "Disclaimer");
define('_MI_SPARTNER_AUTHOR_WORD', "The Author's Word");
define('_MI_SPARTNER_VERSION_HISTORY', "Version History");
define('_MI_SPARTNER_BY', "By");

// Beta
define('_MI_SPARTNER_WARNING_BETA', "This module comes as is, without any guarantees whatsoever. 
This module is BETA, meaning it is still under active development. This release is meant for
<b>testing purposes only</b> and we <b>strongly</b> recommend that you do not use it on a live 
website or in a production environment.");

// RC
define('_MI_SPARTNER_WARNING_RC', "This module comes as is, without any guarantees whatsoever. This module 
is a Release Candidate and should not be used on a production web site. The module is still under 
active development and its use is under your own responsibility, which means the author is not responsible.");

// Final
define('_MI_SPARTNER_WARNING_FINAL', "This module comes as is, without any guarantees whatsoever. Although this 
module is not beta, it is still under active development. This release can be used in a live website 
or a production environment, but its use is under your own responsibility, which means the author 
is not responsible.");

?>