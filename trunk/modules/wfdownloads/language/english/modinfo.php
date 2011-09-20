<?php
/**
 * $Id: modinfo.php,v 1.6 2006/04/08 21:15:08 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

// Module Info
// The name of this module
define("_MI_WFD_NAME","WF-Downloads");

// A brief description of this module
define("_MI_WFD_DESC","Creates a downloads section where users can download/submit/rate various files.");

// Names of blocks for this module (Not all module has blocks)
define("_MI_WFD_BNAME1","Recent WF-Downloads");
define("_MI_WFD_BNAME2","Top WF-Downloads");

// Sub menu titles
define("_MI_WFD_SMNAME1","Submit");
define("_MI_WFD_SMNAME2","Popular");
define("_MI_WFD_SMNAME3","Top Rated");

// Names of admin menu items
define("_MI_WFD_BINDEX","Index");
define("_MI_WFD_INDEXPAGE","Index Management");
define("_MI_WFD_MCATEGORY","Categories");
define("_MI_WFD_MDOWNLOADS","Files");
define('_MI_WFD_REVIEWS','Reviews');
define("_MI_WFD_MUPLOADS","Image Upload");
define("_MI_WFD_MMIMETYPES","Mimetypes");
define("_MI_WFD_PERMISSIONS","Permissions");
define("_MI_WFD_MVOTEDATA","Votes");
define("_MI_WFD_MMIRRORS","Mirrors");

// Title of config items
define('_MI_WFD_POPULAR', 'Download Popular Count');
define('_MI_WFD_POPULARDSC', 'The number of hits before a Download status will be considered as popular.');

//Display Icons
define("_MI_WFD_ICONDISPLAY","Downloads Popular and New:");
define("_MI_WFD_DISPLAYICONDSC", "Select how to display the popular and new icons in download listing.");
define("_MI_WFD_DISPLAYICON1", "Display As Icons");
define("_MI_WFD_DISPLAYICON2", "Display As Text");
define("_MI_WFD_DISPLAYICON3", "Do Not Display");

define("_MI_WFD_DAYSNEW","Downloads Days New:");
define("_MI_WFD_DAYSNEWDSC","The number of days a download status will be considered as new.");
define("_MI_WFD_DAYSUPDATED","Downloads Days Updated:");
define("_MI_WFD_DAYSUPDATEDDSC","The amount of days a download status will be considered as updated.");

define('_MI_WFD_PERPAGE', 'Download Listing Count');
define('_MI_WFD_PERPAGEDSC', 'Number of Downloads to display in each category listing.');

define('_MI_WFD_TEMPLATESET', 'Select Template Set');
define('_MI_WFD_TEMPLATESETDSC', 'Select Templates to use for your module.<br />This will allow you to choose how your downloads are listed');
define('_MI_WFD_TEMPLATESET1', 'Default');
define('_MI_WFD_TEMPLATESET2', 'Professional');

define('_MI_WFD_USESHOTS', 'Display Screenshot Images?');
define('_MI_WFD_USESHOTSDSC', 'Select yes to display screenshot images for each download item');
define('_MI_WFD_SHOTWIDTH', 'Image Display Width');
define('_MI_WFD_SHOTWIDTHDSC', 'Display width for screenshot image');
define('_MI_WFD_SHOTHEIGHT', 'Image Display Height');
define('_MI_WFD_SHOTHEIGHTDSC', 'Display height for screenshot image');
define('_MI_WFD_CHECKHOST', 'Disallow direct download linking? (leeching)');
define('_MI_WFD_REFERERS', 'These sites can directly link to your files <br />Separate with | ');

define("_MI_WFD_ANONPOST","Anonymous User Submission:");
define("_MI_WFD_ANONPOSTDSC","Allow Anonymous users to submit new Downloads/Mirrors to your website?");
define("_MI_WFD_ANONPOST1","None");
define("_MI_WFD_ANONPOST2","Download Only");
define("_MI_WFD_ANONPOST3","Mirror Only");
define("_MI_WFD_ANONPOST4","Both");

define('_MI_WFD_AUTOAPPROVE','Auto Approve Submitted Downloads/Mirrors');
define('_MI_WFD_AUTOAPPROVEDSC','Select to approve submitted downloads/mirrors without moderation.');
define('_MI_WFD_AUTOAPPROVE1','None');
define('_MI_WFD_AUTOAPPROVE2','Download Only');
define('_MI_WFD_AUTOAPPROVE3','Mirror Only');
define('_MI_WFD_AUTOAPPROVE4','Both');

define('_MI_WFD_REVIEWAPPROVE','Auto Approve Submitted Reviews');
define('_MI_WFD_REVIEWAPPROVEDSC','Select to approve submitted reviews without moderation.');
define("_MI_WFD_REVIEWANONPOST","Anonymous User Reviews:");
define("_MI_WFD_REVIEWANONPOSTDSC","Allow Anonymous users to submit new Reviews to your website?");

define('_MI_WFD_MAXFILESIZE','Upload Size (KB)');
define('_MI_WFD_MAXFILESIZEDSC','Maximum file size permitted with file uploads.');
define('_MI_WFD_IMGWIDTH','Upload Image width');
define('_MI_WFD_IMGWIDTHDSC','Maximum image width permitted when uploading image files');
define('_MI_WFD_IMGHEIGHT','Upload Image height');
define('_MI_WFD_IMGHEIGHTDSC','Maximum image height permitted when uploading image files');

define('_MI_WFD_AUTOSUMMARY','Enable Auto Summary');
define('_MI_WFD_AUTOSUMMARYDESC','Automatically create summary based on x amount of characters defined');
define('_MI_WFD_AUTOSUMMARYLENGTH','Summary Length');
define('_MI_WFD_AUTOSUMMARYLENGTHDESC','The maximum amount of characters displayed for the summary');

define('_MI_WFD_UPLOADDIR','Upload Directory (No trailing slash)');
define('_MI_WFD_UPLOADDIRDSC','Upload Directory *MUST* be an absolute path!');

define('_MI_WFD_DOWNLOADMINPOSTS', "Minimum posts required to download");
define('_MI_WFD_DOWNLOADMINPOSTSDSC', "Enter the minimum number of posts required to download a file");
define('_MI_WFD_UPLOADMINPOSTS', "Minimum posts required to upload");
define('_MI_WFD_UPLOADMINPOSTSDSC', "Enter the minimum number of posts required to upload a file");

define('_MI_WFD_ALLOWSUBMISS','User Submissions:');
define('_MI_WFD_ALLOWSUBMISSDSC','Allow Users to Submit new Downloads/Mirrors');
define('_MI_WFD_ALLOWSUBMISS1','None');
define('_MI_WFD_ALLOWSUBMISS2','Download Only');
define('_MI_WFD_ALLOWSUBMISS3','Mirror Only');
define('_MI_WFD_ALLOWSUBMISS4','Both');

define('_MI_WFD_ALLOWUPLOADS','User Uploads:');
define('_MI_WFD_ALLOWUPLOADSDSC','Allow Users to upload files directly to your website');
define('_MI_WFD_SCREENSHOTS','Screenshots Upload Directory');
define('_MI_WFD_CATEGORYIMG','Category Image Upload Directory');
define('_MI_WFD_MAINIMGDIR','Main Image Directory');
define('_MI_WFD_USETHUMBS', 'Use Thumb Nails:');
define("_MI_WFD_USETHUMBSDSC", "Supported file types: JPG, GIF, PNG.<div style='padding-top: 8px;'>WF-Downloads will use thumb nails for images. Set to 'No' to use orginal image if the server does not support this option.</div>");
define('_MI_WFD_DATEFORMAT', 'Timestamp:');
define('_MI_WFD_DATEFORMATDSC', 'Default Timestamp for WF-Downloads:');
define('_MI_WFD_SHOWDISCLAIMER', 'Show Disclaimer before User Submission?');
define('_MI_WFD_SHOWDOWNDISCL', 'Show Disclaimer before User Download?');
define('_MI_WFD_DISCLAIMER', 'Enter Submission Disclaimer Text:');
define('_MI_WFD_DOWNDISCLAIMER', 'Enter Download Disclaimer Text:');
define('_MI_WFD_PLATFORM', 'Enter Platforms:');
define('_MI_WFD_SUBCATS', 'Sub-Categories:');
define('_MI_WFD_VERSIONTYPES', 'Version Status:');
define('_MI_WFD_LICENSE', 'Enter License:');
define('_MI_WFD_LIMITS', 'Enter File Limitations:');

define("_MI_WFD_SUBMITART", "Download Submission:");
define("_MI_WFD_SUBMITARTDSC", "Select groups that can submit new downloads.");

define("_MI_WFD_IMGUPDATE", "Update Thumbnails?");
define("_MI_WFD_IMGUPDATEDSC", "If selected Thumbnail images will be updated at each page render, otherwise the first thumbnail image will be used regardless. <br /><br />");
define("_MI_WFD_QUALITY", "Thumb Nail Quality:");
define("_MI_WFD_QUALITYDSC", "Quality Lowest: 0 Highest: 100");
define("_MI_WFD_KEEPASPECT", "Keep Image Aspect Ratio?");
define("_MI_WFD_KEEPASPECTDSC", "");
define("_MI_WFD_ADMINPAGE", "Admin Index Files Count:");
define("_MI_WFD_AMDMINPAGEDSC", "Number of new files to display in module admin area.");
define("_MI_WFD_ARTICLESSORT", "Default download Order:");
define("_MI_WFD_ARTICLESSORTDSC", "Select the default order for the download listings.");
define("_MI_WFD_TITLE", "Title");
define("_MI_WFD_RATING", "Rating");
define("_MI_WFD_WEIGHT", "Weight");
define("_MI_WFD_POPULARITY", "Popularity");
define("_MI_WFD_SUBMITTED2", "Submission Date");
define('_MI_WFD_COPYRIGHT', 'Copyright Notice:');
define('_MI_WFD_COPYRIGHTDSC', 'Select to display a copyright notice on download page.');

// Description of each config items
define('_MI_WFD_PLATFORMDSC', 'List of platforms to enter <br />Separate with | IMPORTANT: Do not change this once the site is Live, Add new to the end of the list!');
define('_MI_WFD_SUBCATSDSC', 'Select Yes to display sub-categories. Selecting No will hide sub-categories from the listings');
define('_MI_WFD_LICENSEDSC', 'List of platforms to enter <br />Separate with |');

// Text for notifications
define('_MI_WFD_GLOBAL_NOTIFY', 'Global');
define('_MI_WFD_GLOBAL_NOTIFYDSC', 'Global downloads notification options.');

define('_MI_WFD_CATEGORY_NOTIFY', 'Category');
define('_MI_WFD_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current file category.');

define('_MI_WFD_FILE_NOTIFY', 'File');
define('_MI_WFD_FILE_NOTIFYDSC', 'Notification options that apply to the current file.');

define('_MI_WFD_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_WFD_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new file category is created.');
define('_MI_WFD_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new file category is created.');
define('_MI_WFD_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file category');                              

define('_MI_WFD_GLOBAL_FILEMODIFY_NOTIFY', 'Modify File Requested');
define('_MI_WFD_GLOBAL_FILEMODIFY_NOTIFYCAP', 'Notify me of any file modification request.');
define('_MI_WFD_GLOBAL_FILEMODIFY_NOTIFYDSC', 'Receive notification when any file modification request is submitted.');
define('_MI_WFD_GLOBAL_FILEMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : File Modification Requested');

define('_MI_WFD_GLOBAL_FILEBROKEN_NOTIFY', 'Broken File Submitted');
define('_MI_WFD_GLOBAL_FILEBROKEN_NOTIFYCAP', 'Notify me of any broken file report.');
define('_MI_WFD_GLOBAL_FILEBROKEN_NOTIFYDSC', 'Receive notification when any broken file report is submitted.');
define('_MI_WFD_GLOBAL_FILEBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken File Reported');

define('_MI_WFD_GLOBAL_FILESUBMIT_NOTIFY', 'File Submitted');
define('_MI_WFD_GLOBAL_FILESUBMIT_NOTIFYCAP', 'Notify me when any new file is submitted (awaiting approval).');
define('_MI_WFD_GLOBAL_FILESUBMIT_NOTIFYDSC', 'Receive notification when any new file is submitted (awaiting approval).');
define('_MI_WFD_GLOBAL_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file submitted');

define('_MI_WFD_GLOBAL_NEWFILE_NOTIFY', 'New File');
define('_MI_WFD_GLOBAL_NEWFILE_NOTIFYCAP', 'Notify me when any new file is posted.');
define('_MI_WFD_GLOBAL_NEWFILE_NOTIFYDSC', 'Receive notification when any new file is posted.');
define('_MI_WFD_GLOBAL_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file');

define('_MI_WFD_CATEGORY_FILESUBMIT_NOTIFY', 'File Submitted');
define('_MI_WFD_CATEGORY_FILESUBMIT_NOTIFYCAP', 'Notify me when a new file is submitted (awaiting approval) to the current category.');   
define('_MI_WFD_CATEGORY_FILESUBMIT_NOTIFYDSC', 'Receive notification when a new file is submitted (awaiting approval) to the current category.');      
define('_MI_WFD_CATEGORY_FILESUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file submitted in category'); 

define('_MI_WFD_CATEGORY_NEWFILE_NOTIFY', 'New File');
define('_MI_WFD_CATEGORY_NEWFILE_NOTIFYCAP', 'Notify me when a new file is posted to the current category.');   
define('_MI_WFD_CATEGORY_NEWFILE_NOTIFYDSC', 'Receive notification when a new file is posted to the current category.');      
define('_MI_WFD_CATEGORY_NEWFILE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New file in category'); 

define('_MI_WFD_FILE_APPROVE_NOTIFY', 'File Approved');
define('_MI_WFD_FILE_APPROVE_NOTIFYCAP', 'Notify me when this file is approved.');
define('_MI_WFD_FILE_APPROVE_NOTIFYDSC', 'Receive notification when this file is approved.');
define('_MI_WFD_FILE_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : File Approved');

define('_MI_WFD_AUTHOR_INFO', "Developer Information");
define('_MI_WFD_AUTHOR_NAME', "Developer");
define('_MI_WFD_AUTHOR_DEVTEAM', "Development Team");
define('_MI_WFD_AUTHOR_WEBSITE', "Developer website");
define('_MI_WFD_AUTHOR_EMAIL', "Developer email");
define('_MI_WFD_AUTHOR_CREDITS', "Credits");
define('_MI_WFD_MODULE_INFO', "Module Development Information");
define('_MI_WFD_MODULE_STATUS', "Development Status");
define('_MI_WFD_MODULE_DEMO', "Demo Site");
define('_MI_WFD_MODULE_SUPPORT', "Official support site");
define('_MI_WFD_MODULE_BUG', "Report a bug for this module");
define('_MI_WFD_MODULE_FEATURE', "Suggest a new feature for this module");
define('_MI_WFD_MODULE_DISCLAIMER', "Disclaimer");
define('_MI_WFD_RELEASE', "Release Date: ");

define('_MI_WFD_MODULE_MAILLIST', "SmartFactory Mailing Lists");

define('_MI_WFD_MODULE_MAILANNOUNCEMENTS', "Announcements Mailing List");
define('_MI_WFD_MODULE_MAILBUGS', "Bug Mailing List");
define('_MI_WFD_MODULE_MAILFEATURES', "Features Mailing List");

define('_MI_WFD_MODULE_MAILANNOUNCEMENTSDSC', "Get the latest announcements from SmartFactory.");
define('_MI_WFD_MODULE_MAILBUGSDSC', "Bug Tracking and submission mailing list");
define('_MI_WFD_MODULE_MAILFEATURESDSC', "Request New Features mailing list.");

define('_MI_WFD_WARNINGTEXT', "THE SOFTWARE IS PROVIDED BY SMARTFACTORY \"AS IS\" AND \"WITH ALL FAULTS.\"
SMARTFACTORY MAKES NO REPRESENTATIONS OR WARRANTIES OF ANY KIND CONCERNING
THE QUALITY, SAFETY OR SUITABILITY OF THE SOFTWARE, EITHER EXPRESS OR
IMPLIED, INCLUDING WITHOUT LIMITATION ANY IMPLIED WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT.
FURTHER, SMARTFACTORY MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE TRUTH,
ACCURACY OR COMPLETENESS OF ANY STATEMENTS, INFORMATION OR MATERIALS
CONCERNING THE SOFTWARE THAT IS CONTAINED IN SMARTFACTORY WEBSITE. IN NO
EVENT WILL SMARTFACTORY BE LIABLE FOR ANY INDIRECT, PUNITIVE, SPECIAL,
INCIDENTAL OR CONSEQUENTIAL DAMAGES HOWEVER THEY MAY ARISE AND EVEN IF
SMARTFACTORY HAS BEEN PREVIOUSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES..");

define('_MI_WFD_AUTHOR_CREDITSTEXT',"The SmartFactory Team would like to thank the following people for their help and support during the development phase of this module:<br /><br />tom, mking, paco1969, mharoun, Talis, m0nty, steenlnielsen, Clubby, Geronimo, bd_csmc, herko, LANG, Stewdio, tedsmith, veggieryan, carnuke, MadFish, Kiang<br />and anyone else who has contributed to either directly or indirectly.");
define('_MI_WFD_AUTHOR_BUGFIXES', "Bug Fix History");

define('_MI_WFD_COPYRIGHTIMAGE', "Images copyright WF-Project/SmartFactory and may only be used with permission");

// mirror defines
define('_MI_WFD_MIRROR_USEIMAGES', 'Display Mirror Logos?'); // not implemented yet
define('_MI_WFD_MIRROR_USEIMAGESDSC', 'Select yes to display logo for each mirror'); // not implemented yet
define('_MI_WFD_MIRROR_IMGWIDTH', 'Logo Display Width'); // not implemented yet
define('_MI_WFD_MIRROR_IMGWIDTHDSC', 'Display width for mirror logo'); // not implemented yet
define('_MI_WFD_MIRROR_IMGHEIGHT', 'Logo Display Height'); // not implemented yet
define('_MI_WFD_MIRROR_IMGHEIGHTDSC', 'Display height for mirror logo'); // not implemented yet
define('_MI_WFD_MIRROR_AUTOAPPROVE','Auto Approve Submitted Mirrors');
define('_MI_WFD_MIRROR_AUTOAPPROVEDSC','Select to approve submitted mirrors without moderation.');

define('_MI_WFD_MIRROR_MAXIMGWIDTH','Upload Logo width'); // not implemented yet
define('_MI_WFD_MIRROR_MAXIMGWIDTHDSC','Maximum logo width permitted when uploading logo files'); // not implemented yet
define('_MI_WFD_MIRROR_MAXIMGHEIGHT','Upload logo height'); // not implemented yet
define('_MI_WFD_MIRROR_MAXIMGHEIGHTDSC','Maximum logo height permitted when uploading logo files'); // not implemented yet

define('_MI_WFD_MIRROR_ENABLE','Enable Mirror System');
define('_MI_WFD_MIRROR_ENABLEDSC','');
define('_MI_WFD_MIRROR_ENABLEONCHK','Enable Server Online Check');
define('_MI_WFD_MIRROR_ENABLEONCHKDSC','Enables the host server check for the Mirrors<br />This can slow your page load down if<br />you have many mirrors');
define('_MI_WFD_MIRROR_ALLOWSUBMISS','User Mirror Submissions:');
define('_MI_WFD_MIRROR_ALLOWSUBMISSDSC','Allow Users to Submit new mirrors');
define('_MI_WFD_MIRROR_MIRRORIMAGES','Mirror Logo Upload Directory'); // not implemented yet
define('_MI_WFD_MIRROR_MIRRORIMAGESDSC','Mirror Logo Upload Directory'); // not implemented yet
?>
