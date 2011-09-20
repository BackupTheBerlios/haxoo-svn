<?php
/**
 * $Id: admin.php,v 1.19 2006/05/25 14:13:55 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

// %%%%%%	Module NMDe 'MyDownloads' (Admin)	  %%%%%
// Buttons
define("_AM_WFD_BMODIFY", "Modify");
define("_AM_WFD_BDELETE", "Delete");
define("_AM_WFD_BADD", "Add");
define("_AM_WFD_BAPPROVE", "Approve");
define("_AM_WFD_BIGNORE", "Ignore");
define("_AM_WFD_BCANCEL", "Cancel");
define("_AM_WFD_BSAVE", "Save");
define("_AM_WFD_BRESET", "Reset");
define("_AM_WFD_BMOVE", "Move Files");
define("_AM_WFD_BUPLOAD", "Upload");
define("_AM_WFD_BDELETEIMAGE", "Delete Selected Image");
define("_AM_WFD_BRETURN", "Return to where you where!");
define("_AM_WFD_DBERROR", "Database Access Error: Please report this error to the SmartFactory Website");
//Banned Users
define("_AM_WFD_NONBANNED", "Not Banned");
define("_AM_WFD_BANNED", "Banned");
define("_AM_WFD_EDITBANNED", "Edit Banned Users");
// Other Options
define("_AM_WFD_TEXTOPTIONS", "Text Options:");
define("_AM_WFD_ALLOWHTML", " Allow HTML Tags");
define("_AM_WFD_ALLOWSMILEY", " Allow Smiley Icons");
define("_AM_WFD_ALLOWXCODE", " Allow XOOPS Codes");
define("_AM_WFD_ALLOWIMAGES", " Allow Images");
define("_AM_WFD_ALLOWBREAK", " Use XOOPS linebreak conversion?");
define("_AM_WFD_UPLOADFILE", "File Uploaded Successfully");
define("_AM_WFD_NOMENUITEMS", "No menu items within the menu");

// Admin Bread crumb
define("_AM_WFD_PREFS", "Preferences");
define("_AM_WFD_PERMISSIONS", "Permissions");
define("_AM_WFD_BINDEX", "Main Index");
define("_AM_WFD_BLOCKADMIN", "Blocks");
define("_AM_WFD_GOMODULE", "Go to module");
define("_AM_WFD_BHELP", "Help");
define("_AM_WFD_ABOUT", "About");
// Admin Summary
define("_AM_WFD_SCATEGORY", "Category: ");
define("_AM_WFD_SFILES", "Files: ");
define("_AM_WFD_SNEWFILESVAL", "Submitted: ");
define("_AM_WFD_SMODREQUEST", "Modified: ");
define("_AM_WFD_SREVIEWS", "Reviews: ");
define("_AM_WFD_SMIRRORS", "Mirrors: ");
// Admin Main Menu
define("_AM_WFD_MCATEGORY", "Category Management");
define("_AM_WFD_INDEXPAGE", "Index Page Management");
define("_AM_WFD_MUPLOADS", "Image Upload");
define("_AM_WFD_MMIMETYPES", "Mimetypes Management");
define("_AM_WFD_MCOMMENTS", "Comments");
define("_AM_WFS_MVOTEDATA", "Vote Data");
// waiting reviews
define("_AM_WFD_AREVIEWS", "Reviews Management");
define("_AM_WFD_AREVIEWS_WAITING", "Reviews Waiting Validation:");
define("_AM_WFD_AREVIEWS_INFO", "Reviews Management Information");
define("_AM_WFD_AREVIEWS_APPROVE", "<b>Approve</b> new review without validation.");
define("_AM_WFD_AREVIEWS_APPROVED", "Review has been approved.");
define("_AM_WFD_AREVIEWS_EDIT", "<b>Edit</b> new review and then approve.");
define("_AM_WFD_AREVIEWS_DELETE", "<b>Delete</b> the new review information.");

// Catgeory defines
define("_AM_WFD_CCATEGORY_CREATENEW", "Create New Category");
define("_AM_WFD_CCATEGORY_MODIFY", "Modify Category");
define("_AM_WFD_CCATEGORY_MOVE", "Move Category Files");
define("_AM_WFD_CCATEGORY_MODIFY_TITLE", "Category Title:");
define("_AM_WFD_CCATEGORY_MODIFY_FAILED", "Failed Moving Files: Cannot move to this Category");
define("_AM_WFD_CCATEGORY_MODIFY_FAILEDT", "Failed Moving Files: Cannot find this Category");
define("_AM_WFD_CCATEGORY_MODIFY_MOVED", "Files Moved");
define("_AM_WFD_CCATEGORY_CREATED", "New Category Created and Database Updated Successfully");
define("_AM_WFD_CCATEGORY_MODIFIED", "Selected Category Modified and Database Updated Successfully");
define("_AM_WFD_CCATEGORY_DELETED", "Selected Category Deleted and Database Updated Successfully");
define("_AM_WFD_CCATEGORY_AREUSURE", "WARNING: Are you sure you want to delete this Category and ALL its Files and Comments?");
define("_AM_WFD_CCATEGORY_NOEXISTS", "You must create a Category before you can add a new file");
define("_AM_WFD_FCATEGORY_GROUPPROMPT", "Category Access Permissions:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Select user groups who will have access to this Category.</span></div>");
define("_AM_WFD_FCATEGORY_TITLE", "Category Title:");
define("_AM_WFD_FCATEGORY_WEIGHT", "Category Weight:");
define("_AM_WFD_FCATEGORY_SUBCATEGORY", "Set As Sub-Category:");
define("_AM_WFD_FCATEGORY_CIMAGE", "Select Category Image:");
define("_AM_WFD_FCATEGORY_DESCRIPTION", "Set Category Description:");
define("_AM_WFD_FCATEGORY_SUMMARY", "Set Category Summary:");
define("_AM_WFD_CCATEGORY_CHILDASPARENT", "You cannot set a child category as the category's parent");
/*
* Index page Defines
*/
define("_AM_WFD_IPAGE_UPDATED", "Index Page Modified and Database Updated Successfully!");
define("_AM_WFD_IPAGE_INFORMATION", "Index Page Information");
define("_AM_WFD_IPAGE_MODIFY", "Modify Index Page");
define("_AM_WFD_IPAGE_CIMAGE", "Select Index Image:");
define("_AM_WFD_IPAGE_CTITLE", "Index Title:");
define("_AM_WFD_IPAGE_CHEADING", "Index Heading:");
define("_AM_WFD_IPAGE_CHEADINGA", "Index Heading Alignment:");
define("_AM_WFD_IPAGE_CFOOTER", "Index Footer:");
define("_AM_WFD_IPAGE_CFOOTERA", "Index Footer Alignment:");
define("_AM_WFD_IPAGE_CLEFT", "Align Left");
define("_AM_WFD_IPAGE_CCENTER", "Align Center");
define("_AM_WFD_IPAGE_CRIGHT", "Align Right");

/*
*  Permissions defines
*/
define("_AM_WFD_PERM_MANAGEMENT", "Permissions Management");
define("_AM_WFD_PERM_PERMSNOTE", "<div><b>NOTE:</b> Please be aware that even if you&#8217ve set correct viewing permissions here, a group might not see the articles or blocks if you don&#8217t also grant that group permissions to access the module. To do that, go to <b>System admin > Groups</b>, choose the appropriate group and click the checkboxes to grant its members the access.</div>");
define("_AM_WFD_PERM_CPERMISSIONS", "Category Permissions");
define("_AM_WFD_PERM_CSELECTPERMISSIONS", "Select categories that each group is allowed to view");
define("_AM_WFD_PERM_CNOCATEGORY", "Cannot set permission's: No Categories's have been created yet!");
define("_AM_WFD_PERM_FPERMISSIONS", "File Permissions");
define("_AM_WFD_PERM_FNOFILES", "Cannot set permission's: No files have been created yet!");
define("_AM_WFD_PERM_FSELECTPERMISSIONS", "Select the files that each group is allowed to view");
/*
* Upload defines
*/
define("_AM_WFD_DOWN_IMAGEUPLOAD", "Image successfully uploaded to server destination");
define("_AM_WFD_DOWN_NOIMAGEEXIST", "Error: No file was selected for uploading.  Please try again!");
define("_AM_WFD_DOWN_IMAGEEXIST", "Image already exists in upload area!");
define("_AM_WFD_DOWN_FILEDELETED", "File has been deleted.");
define("_AM_WFD_DOWN_FILEERRORDELETE", "Error deleting File: File not found on server.");
define("_AM_WFD_DOWN_NOFILEERROR", "Error deleting File: No File Selected For Deleting.");
define("_AM_WFD_DOWN_DELETEFILE", "WARNING: Are you sure you want to delete this Image file?");
define("_AM_WFD_DOWN_IMAGEINFO", "Server Status");
define("_AM_WFD_DOWN_NOTSET", "Upload Path Not Set");
define("_AM_WFD_DOWN_SERVERPATH", "Server Path to XOOPS Root: ");
define("_AM_WFD_DOWN_UPLOADPATH", "Current Upload Path: ");
define("_AM_WFD_DOWN_UPLOADPATHDSC", "Note. Upload Path *MUST* contain the full server path of your upload folder.");
define("_AM_WFD_DOWN_SPHPINI", "<b>Information taken from PHP ini File:</b>");
define("_AM_WFD_DOWN_SAFEMODESTATUS", "Safe Mode Status: ");
define("_AM_WFD_DOWN_REGISTERGLOBALS", "Register Globals: ");
define("_AM_WFD_DOWN_SERVERUPLOADSTATUS", "Server Uploads Status: ");
define("_AM_WFD_DOWN_MAXUPLOADSIZE", "Max Upload Size Permitted: ");
define("_AM_WFD_DOWN_MAXPOSTSIZE", "Max Post Size Permitted: ");
define("_AM_WFD_DOWN_SAFEMODEPROBLEMS", " (This May Cause Problems)");
define("_AM_WFD_DOWN_GDLIBSTATUS", "GD Library Support: ");
define("_AM_WFD_DOWN_GDLIBVERSION", "GD Library Version: ");
define("_AM_WFD_DOWN_GDON", "<b>Enabled</b> (Thumbs Nails Available)");
define("_AM_WFD_DOWN_GDOFF", "<b>Disabled</b> (No Thumb Nails Available)");
define("_AM_WFD_DOWN_OFF", "<b>OFF</b>");
define("_AM_WFD_DOWN_ON", "<b>ON</b>");
define("_AM_WFD_DOWN_CATIMAGE", "Category Images");
define("_AM_WFD_DOWN_SCREENSHOTS", "Screenshot Images");
define("_AM_WFD_DOWN_MAINIMAGEDIR", "Main images");
define("_AM_WFD_DOWN_FCATIMAGE", "Category Image Path");
define("_AM_WFD_DOWN_FSCREENSHOTS", "Screenshot Image Path");
define("_AM_WFD_DOWN_FMAINIMAGEDIR", "Main image Path");
define("_AM_WFD_DOWN_FUPLOADIMAGETO", "Upload Image: ");
define("_AM_WFD_DOWN_FUPLOADPATH", "Upload Path: ");
define("_AM_WFD_DOWN_FUPLOADURL", "Upload URL: ");
define("_AM_WFD_DOWN_FOLDERSELECTION", "Select Upload Destination:");
define("_AM_WFD_DOWN_FSHOWSELECTEDIMAGE", "Display Selected Image:");
define("_AM_WFD_DOWN_FUPLOADIMAGE", "Upload New Image to Selected Destination:");

// Main Index defines
define("_AM_WFD_MINDEX_DOWNSUMMARY", "Module Admin Summary");
define("_AM_WFD_MINDEX_PUBLISHEDDOWN", "Published Files:");
define("_AM_WFD_MINDEX_AUTOPUBLISHEDDOWN", "Auto Published Files:");
define("_AM_WFD_MINDEX_AUTOEXPIRE", "Auto Expire Files:");
define("_AM_WFD_MINDEX_OFFLINEDOWN", "Offline Files:");
define("_AM_WFD_MINDEX_ID", "ID");
define("_AM_WFD_MINDEX_TITLE", "File Title");
define("_AM_WFD_MINDEX_POSTER", "Poster");
define("_AM_WFD_MINDEX_SUBMITTED", "Submission Date");
define("_AM_WFD_MINDEX_ONLINESTATUS", "Online Status");
define("_AM_WFD_MINDEX_PUBLISHED", "Published");
define("_AM_WFD_MINDEX_ACTION", "Action");
define("_AM_WFD_MINDEX_NODOWNLOADSFOUND", "NOTICE: There are no files that match this criteria");
define("_AM_WFD_MINDEX_PAGE", "<b>Page:<b> ");
define('_AM_WFD_MINDEX_PAGEINFOTXT', '<ul><li>WF-Downloads main page details.</li><li>You can easily change the image logo, heading, main index header and footer text to suit your own look</li></ul><br /><br />Note: The Logo image choosen will be used throughout WF-Downloads.');

// Submitted Files
define("_AM_WFD_SUB_SUBMITTEDFILES", "Submitted Files");
define("_AM_WFD_SUB_FILESWAITINGINFO", "Waiting Files Information");
define("_AM_WFD_SUB_FILESWAITINGVALIDATION", "Files Waiting Validation: ");
define("_AM_WFD_SUB_APPROVEWAITINGFILE", "<b>Approve</b> new file information without validation.");
define("_AM_WFD_SUB_EDITWAITINGFILE", "<b>Edit</b> new file information and then approve.");
define("_AM_WFD_SUB_DELETEWAITINGFILE", "<b>Delete</b> the new file information.");
define("_AM_WFD_SUB_NOFILESWAITING", "There are no files that match this critera");
define("_AM_WFD_SUB_NEWFILECREATED", "New File Data Created and Database Updated Successfully");
// Mimetypes
define("_AM_WFD_MIME_ID", "ID");
define("_AM_WFD_MIME_EXT", "EXT");
define("_AM_WFD_MIME_NAME", "Application Type");
define("_AM_WFD_MIME_ADMIN", "Admin");
define("_AM_WFD_MIME_USER", "User");
// Mimetype Form
define("_AM_WFD_MIME_CREATEF", "Create Mimetype");
define("_AM_WFD_MIME_MODIFYF", "Modify Mimetype");
define("_AM_WFD_MIME_EXTF", "File Extension:");
define("_AM_WFD_MIME_NAMEF", "Application Type/Name:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter application associated with this extension.</span></div>");
define("_AM_WFD_MIME_TYPEF", "Mimetypes:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter each mimetype associated with the file extension. Each mimetype must be seperated with a space.</span></div>");
define("_AM_WFD_MIME_ADMINF", "Allowed Admin Mimetype");
define("_AM_WFD_MIME_ADMINFINFO", "<b>Mimetypes that are available for Admin uploads:</b>");
define("_AM_WFD_MIME_USERF", "Allowed User Mimetype");
define("_AM_WFD_MIME_USERFINFO", "<b>Mimetypes that are available for User uploads:</b>");
define("_AM_WFD_MIME_NOMIMEINFO", "No mimetypes selected.");
define("_AM_WFD_MIME_FINDMIMETYPE", "Find New Mimetype:");
define("_AM_WFD_MIME_EXTFIND", "Search File Extension:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter file extension you wish to search.</span></div>");
define("_AM_WFD_MIME_INFOTEXT", "<ul><li>New mimetypes can be created, edit or deleted easily via this form.</li>
	<li>Search for a new mimetypes via an external website.</li>
	<li>View displayed mimetypes for Admin and User uploads.</li>
	<li>Change mimetype upload status.</li></ul>
	");

// Mimetype Buttons
define("_AM_WFD_MIME_CREATE", "Create");
define("_AM_WFD_MIME_CLEAR", "Reset");
define("_AM_WFD_MIME_CANCEL", "Cancel");
define("_AM_WFD_MIME_MODIFY", "Modify");
define("_AM_WFD_MIME_DELETE", "Delete");
define("_AM_WFD_MIME_FINDIT", "Get Extension!");
// Mimetype Database
define("_AM_WFD_MIME_DELETETHIS", "Delete Selected Mimetype?");
define("_AM_WFD_MIME_MIMEDELETED", "Mimetype %s has been deleted");
define("_AM_WFD_MIME_CREATED", "Mimetype Information Created");
define("_AM_WFD_MIME_MODIFIED", "Mimetype Information Modified");
// Vote Information
define("_AM_WFD_VOTE_RATINGINFOMATION", "Voting Information");
define("_AM_WFD_VOTE_TOTALVOTES", "Total votes: ");
define("_AM_WFD_VOTE_REGUSERVOTES", "Registered User Votes: %s");
define("_AM_WFD_VOTE_ANONUSERVOTES", "Anonymous User Votes: %s");
define("_AM_WFD_VOTE_USER", "User");
define("_AM_WFD_VOTE_IP", "IP Address");
define("_AM_WFD_VOTE_USERAVG", "Average User Rating");
define("_AM_WFD_VOTE_TOTALRATE", "Total Ratings");
define("_AM_WFD_VOTE_DATE", "Submitted");
define("_AM_WFD_VOTE_RATING", "Rating");
define("_AM_WFD_VOTE_NOREGVOTES", "No Registered User Votes");
define("_AM_WFD_VOTE_NOUNREGVOTES", "No Unregistered User Votes");
define("_AM_WFD_VOTE_VOTEDELETED", "Vote data deleted.");
define("_AM_WFD_VOTE_ID", "ID");
define("_AM_WFD_VOTE_FILETITLE", "File Title");
define("_AM_WFD_VOTE_DISPLAYVOTES", "Voting Data Information");
define("_AM_WFD_VOTE_NOVOTES", "No User Votes to display");
define("_AM_WFD_VOTE_DELETE", "No User Votes to display");
define("_AM_WFD_VOTE_DELETEDSC", "<b>Deletes</b> the chosen vote information from the database.");

// Modifications
/*
define("_AM_WFD_MOD_TOTMODREQUESTS", "Total Modification Requests: ");
define("_AM_WFD_MOD_MODREQUESTS", "Modified Files");
define("_AM_WFD_MOD_MODREQUESTSINFO", "Modified Files Information");
define("_AM_WFD_MOD_MODID", "ID");
define("_AM_WFD_MOD_MODTITLE", "Title");
define("_AM_WFD_MOD_MODPOSTER", "Original Poster: ");
define("_AM_WFD_MOD_DATE", "Submitted");
define("_AM_WFD_MOD_NOMODREQUEST", "There are no requests that match this critera");
define("_AM_WFD_MOD_MODIFYSUBMIT", "Submitter");
define("_AM_WFD_MOD_ORIGINAL", "Orginal Download Details");
define("_AM_WFD_MOD_REQDELETED", "Modification request removed from the database");
define("_AM_WFD_MOD_REQUPDATED", "Selected Download Modified and Database Updated Successfully");

*/
define("_AM_WFD_MOD_TOTMODREQUESTS", "Total Modification Requests: ");
define("_AM_WFD_MOD_MODREQUESTS", "Modified Files");
define("_AM_WFD_MOD_MODREQUESTSINFO", "Modified Files Information");
define("_AM_WFD_MOD_MODID", "ID");
define("_AM_WFD_MOD_MODTITLE", "Title");
define("_AM_WFD_MOD_MODPOSTER", "Original Poster: ");
define("_AM_WFD_MOD_DATE", "Submitted");
define("_AM_WFD_MOD_NOMODREQUEST", "There are no requests that match this critera");
define("_AM_WFD_MOD_TITLE", "Download Title: ");
define("_AM_WFD_MOD_LID", "Download ID: ");
define("_AM_WFD_MOD_CID", "Category: ");
define("_AM_WFD_MOD_URL", "Remote Url: ");
define("_AM_WFD_MOD_MIRROR", "Download Mirror: ");
define("_AM_WFD_MOD_SIZE", "Download Size: ");
define("_AM_WFD_MOD_PUBLISHER", "Publisher: ");
define("_AM_WFD_MOD_LICENSE", "Software Licence: ");
define("_AM_WFD_MOD_FEATURES", "Key Features: ");
define("_AM_WFD_MOD_FORUMID", "Forum: ");
define("_AM_WFD_MOD_LIMITATIONS", "Software limitations: ");
define("_AM_WFD_MOD_DHISTORY", "Download History: ");
define("_AM_WFD_MOD_SCREENSHOT", "Screenshot Image: ");
define("_AM_WFD_MOD_HOMEPAGE", "Home Page: ");
define("_AM_WFD_MOD_HOMEPAGETITLE", "Home Page Title: ");
define("_AM_WFD_MOD_VERSION", "Version: ");
define("_AM_WFD_MOD_SHOTIMAGE", "Screenshot Image: ");
define("_AM_WFD_MOD_FILESIZE", "File Size: ");
define("_AM_WFD_MOD_PLATFORM", "Software Platform: ");
define("_AM_WFD_MOD_PRICE", "Price: ");
define("_AM_WFD_MOD_LICENCE", "Software Licence: ");
define("_AM_WFD_MOD_DESCRIPTION", "Description: ");
define("_AM_WFD_MOD_REQUIREMENTS", "Requirements: ");
define("_AM_WFD_MOD_MODIFYSUBMITTER", "Submitter: ");
define("_AM_WFD_MOD_MODIFYSUBMIT", "Submitter");
define("_AM_WFD_MOD_PROPOSED", "Proposed Download Details");
define("_AM_WFD_MOD_ORIGINAL", "Orginal Download Details");
define("_AM_WFD_MOD_REQDELETED", "Modification request removed from the database");
define("_AM_WFD_MOD_REQUPDATED", "Selected Download Modified and Database Updated Successfully");
define('_AM_WFD_MOD_VIEW','View');
define("_AM_WFD_MOD_FILENAME", "Local Filename: ");
define("_AM_WFD_MOD_FILETYPE", "Local Filetype: ");
define("_AM_WFD_MOD_STATUS", "Status: ");
define("_AM_WFD_MOD_RATING", "Rating: ");
define("_AM_WFD_MOD_HITS", "Hits: ");
define("_AM_WFD_MOD_VOTES", "Votes: ");
define("_AM_WFD_MOD_COMMENTS", "Comments: ");
define("_AM_WFD_MOD_PUBLISHED", "Published: ");
define("_AM_WFD_MOD_EXPIRED", "Expired: ");
define("_AM_WFD_MOD_UPDATED", "Updated: ");
define("_AM_WFD_MOD_OFFLINE", "Offline: ");
define("_AM_WFD_MOD_REQUESTDATE", "Request Date: ");
define("_AM_WFD_MOD_IPADDRESS", "IP Address: ");
define("_AM_WFD_MOD_NOTIFYPUB", "Notify: ");
define("_AM_WFD_MOD_PAYPALEMAIL", "PayPal Email: ");
define("_AM_WFD_MOD_SUMMARY", "Summary: ");

//Reviews defines
define("_AM_WFD_REV_SNEWMNAMEDESC", "Approve Review: ");
define("_AM_WFD_REV_ID", "ID");
define("_AM_WFD_REV_TITLE", "Title");
define("_AM_WFD_REV_REVIEWTITLE", "Review Title");
define("_AM_WFD_REV_POSTER", "Submitter");
define("_AM_WFD_REV_SUBMITDATE", "Submitted");
define("_AM_WFD_REV_FTITLE", "Review Title: ");
define("_AM_WFD_REV_FRATING", "Review Rating: ");
define("_AM_WFD_REV_FDESCRIPTION", "Review Description: ");
define("_AM_WFD_REV_FAPPROVE", "Review Approve: ");
define("_AM_WFD_REV_ACTION", "Action");
define("_AM_WFD_REV_NOWAITINGREVIEWS", "No Waiting Reviews Found");
define("_AM_WFD_REVIEW_APPROVETHIS", "Approve Review");
define("_AM_WFD_REV_NOPUBLISHEDREVIEWS", "No Published Reviews Found");
define("_AM_WFD_REV_REVIEW_UPDATED", "Selected Review Modified and Database Updated Successfully");
define("_AM_WFD_REV_REVIEW_TOTAL", "Total Reviews:");
define("_AM_WFD_REV_REVIEW_WAITING", "Waiting Reviews");
define("_AM_WFD_REV_REVIEW_PUBLISHED", "Published Reviews");

//File management
define("_AM_WFD_FILE_SUBMITTERID", "Submitter User Id: <br /><br />Leave this as it is, Unless you want to change who submitted the download");
define("_AM_WFD_FILE_ID", "File ID: ");
define("_AM_WFD_FILE_IP", "Uploaders IP Address: ");
define("_AM_WFD_FILE_ALLOWEDAMIME", "<div style='padding-top: 4px; padding-bottom: 4px;'><b>Allowed Admin File Extensions</b>:</div>");
define("_AM_WFD_FILE_MODIFYFILE", "Modify File Information");
define("_AM_WFD_FILE_CREATENEWFILE", "Create New File");
define("_AM_WFD_FILE_TITLE", "File Title: ");
define("_AM_WFD_FILE_DLURL", "Remote URL: ");
define("_AM_WFD_FILE_FILENAME", "Local Filename:<br /><br /><span style='font-weight: normal;'>Note: If using local file as download, then you must also enter the correct filetype below!</span>");
define("_AM_WFD_FILE_FILETYPE", "File Type: ");
define("_AM_WFD_FILE_MIRRORURL", "File Mirror: ");
define("_AM_WFD_FILE_SUMMARY", "File Summary: ");
define("_AM_WFD_FILE_DESCRIPTION", "File Description: ");
define("_MD_WFD_FILE_SUMMARY", "File Summary: ");
define("_AM_WFD_FILE_DUPLOAD", " Upload File:");
define("_AM_WFD_FILE_CATEGORY", "Select Category: ");
define("_AM_WFD_FILE_HOMEPAGETITLE", "Home Page Title: ");
define("_AM_WFD_FILE_HOMEPAGE", "Home Page: ");
define("_AM_WFD_FILE_SIZE", "File Size: ");
define("_AM_WFD_FILE_VERSION", "File Version: ");
define("_AM_WFD_FILE_PUBLISHER", "File Publisher: ");
define("_AM_WFD_FILE_PLATFORM", "Software Platform: ");
define("_AM_WFD_FILE_LICENCE", "Software Licence: ");
define("_AM_WFD_FILE_LIMITATIONS", "Software limitations: ");
define("_AM_WFD_FILE_PRICE", "Price: ");
define("_AM_WFD_FILE_KEYFEATURES", "Key Features:<br /><br /><span style='font-weight: normal;'>Seperate each Key Feature with a |</span>");
define("_AM_WFD_FILE_REQUIREMENTS", "System Requirements:<br /><br /><span style='font-weight: normal;'>Seperate each Requirement with |</span>");
define("_AM_WFD_FILE_HISTORY", "Download History Edit:<br /><br /><span style='font-weight: normal;'>Add New Download History and only use this field to if you need to edit the previous history.</span>");
define("_AM_WFD_FILE_HISTORYD", "Add New Download History:<br /><br /><span style='font-weight: normal;'>The version Number and date will be added automatically</span>");
define("_AM_WFD_FILE_HISTORYVERS", "<b>Version: </b>");
define("_AM_WFD_FILE_HISTORDATE", " <b>Updated:</b> ");
define("_AM_WFD_FILE_FILESSTATUS", " Set Download offline?<br /><br /><span style='font-weight: normal;'>Download will not be viewable to all users.</span>");
define("_AM_WFD_FILE_SETASUPDATED", " Set Download Status as Updated?<br /><br /><span style='font-weight: normal;'>Download will Display updated icon.</span>");
define("_AM_WFD_FILE_SHOTIMAGE", "Select Screenshot Image: <br /><br /><span style='font-weight: normal;'>Note that screenshots will only be displayed if activated in module preferences.</span>");
define("_AM_WFD_FILE_DISCUSSINFORUM", "Add Discuss in this Forum?");
define("_AM_WFD_FILE_PUBLISHDATE", "File Publish Date:");
define("_AM_WFD_FILE_EXPIREDATE", "File Expire Date:");
define("_AM_WFD_FILE_CLEARPUBLISHDATE", "<br /><br />Remove Publish date:");
define("_AM_WFD_FILE_CLEAREXPIREDATE", "<br /><br />Remove Expire date:");
define("_AM_WFD_FILE_PUBLISHDATESET", " Publish date set: ");
define("_AM_WFD_FILE_SETDATETIMEPUBLISH", " Set the date/time of publish");
define("_AM_WFD_FILE_SETDATETIMEEXPIRE", " Set the date/time of expire");
define("_AM_WFD_FILE_SETPUBLISHDATE", "<b>Set Publish Date: </b>");
define("_AM_WFD_FILE_SETNEWPUBLISHDATE", "<b>Set New Publish Date: </b><br />Published:");
define("_AM_WFD_FILE_SETPUBDATESETS", "<b>Publish Date Set: </b><br />Publishes On Date:");
define("_AM_WFD_FILE_EXPIREDATESET", " Expire date set: ");
define("_AM_WFD_FILE_SETEXPIREDATE", "<b>Set Expire Date: </b>");
define("_AM_WFD_FILE_MUSTBEVALID", "Screenshot image must be a valid image file under %s directory (ex. shot.gif). Leave it blank if there is no image file.");
define("_AM_WFD_FILE_EDITAPPROVE", "Approve download:");
define("_AM_WFD_FILE_NEWFILEUPLOAD", "New File Created and Database Updated Successfully");
define("_AM_WFD_FILE_FILEMODIFIEDUPDATE", "Selected File Modified and Database Updated Successfully");
define("_AM_WFD_FILE_REALLYDELETEDTHIS", "Really delete the selected file?");
define("_AM_WFD_FILE_FILEWASDELETED", "File %s successfully removed from the database!");
define("_AM_WFD_FILE_USE_UPLOAD_TITLE", " Use upload filename for file title.");
define("_AM_WFD_FILE_FILEAPPROVED", "File Approved and Database Updated Successfully");
define("_AM_WFD_FILE_CREATENEWSSTORY", "<b>Create News Story From Download</b>");
define("_AM_WFD_FILE_SUBMITNEWS", "Submit New file as News item?");
define("_AM_WFD_FILE_NEWSCATEGORY", "Select News Category to submit News:");
define("_AM_WFD_FILE_NEWSTITLE", "News Title:<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: normal;'>Leave Blank to use File Title</span></div>");

/*
* Broken downloads defines
*/
define("_AM_WFD_SBROKENSUBMIT", "Broken: ");
define("_AM_WFD_BROKEN_FILE", "Broken Reports");
define("_AM_WFD_BROKEN_FILEIGNORED", "Broken report ignored and successfully removed from the database!");
define("_AM_WFD_BROKEN_NOWACK", "Acknowledged status changed and database updated!");
define("_AM_WFD_BROKEN_NOWCON", "Confirmed status changed and database updated!");
define("_AM_WFD_BROKEN_REPORTINFO", "Broken Report Information");
define("_AM_WFD_BROKEN_REPORTSNO", "Broken Reports Waiting:");
define("_AM_WFD_BROKEN_IGNOREDESC", "<b>Ignores</b> the report and only deletes the broken file report.");
define("_AM_WFD_BROKEN_IGNORE_ALT", "Ignore and delete the broken file report");
define("_AM_WFD_BROKEN_DELETEDESC", "<b>Deletes</b> the reported download data and broken file reports for the file.");
define("_AM_WFD_BROKEN_DELETE_ALT", "Delete the reported download data and broken file report");
define("_AM_WFD_BROKEN_EDITDESC", "<b>Edit</b> download to correct the problem.");
define("_AM_WFD_BROKEN_EDIT_ALT", "Edit download to correct the problem");
define("_AM_WFD_BROKEN_ACKDESC", "<b>Acknowledged</b> Set Acknowledged state of broken file report.");
define("_AM_WFD_BROKEN_ACK_ALT", "Acknowledge state of broken file report");
define("_AM_WFD_BROKEN_CONFIRMDESC", "<b>Confirmed</b> Set confirmed state of broken file report.");
define("_AM_WFD_BROKEN_CONFIRM_ALT", "Confirme state of broken file report");

define("_AM_WFD_BROKEN_ID", "ID");
define("_AM_WFD_BROKEN_TITLE", "Title");
define("_AM_WFD_BROKEN_REPORTER", "Reporter");
define("_AM_WFD_BROKEN_FILESUBMITTER", "Submitter");
define("_AM_WFD_BROKEN_DATESUBMITTED", "Submit Date");
define("_AM_WFD_BROKEN_ACTION", "Action");
define("_AM_WFD_BROKEN_NOFILEMATCH", "There are no Broken reports that match this critera");
define("_AM_WFD_BROKENFILEDELETED", "Download description removed from database and broken report removed");
define("_AM_WFD_BROKEN_DOWNLOAD_DONT_EXISTS", "The file does not exists anymore");


/*
* About defines
*/
define("_AM_WFD_BY", "by");

//block defines
define("_AM_WFD_BADMIN","Block Administration");
define("_AM_WFD_BLKDESC","Description");
define("_AM_WFD_TITLE","Title");
define("_AM_WFD_SIDE","Alignment");
define("_AM_WFD_WEIGHT","Weight");
define("_AM_WFD_VISIBLE","Visible");
define("_AM_WFD_ACTION","Action");
define("_AM_WFD_SBLEFT","Left");
define("_AM_WFD_SBRIGHT","Right");
define("_AM_WFD_CBLEFT","Center Left");
define("_AM_WFD_CBRIGHT","Center Right");
define("_AM_WFD_CBCENTER","Center Middle");
define("_AM_WFD_ACTIVERIGHTS","Active Rights");
define("_AM_WFD_ACCESSRIGHTS","Access Rights");

//image admin icon
define("_AM_WFD_ICO_EDIT","Edit This Item");
define("_AM_WFD_ICO_DELETE","Delete This Item");
define("_AM_WFD_ICO_ONLINE","Online");
define("_AM_WFD_ICO_OFFLINE","Offline");
define("_AM_WFD_ICO_APPROVED","Approved");
define("_AM_WFD_ICO_NOTAPPROVED","Not Approved");

define("_AM_WFD_ICO_LINK","Related Link");
define("_AM_WFD_ICO_URL","Add Related URL");
define("_AM_WFD_ICO_ADD","Add");
define("_AM_WFD_ICO_APPROVE","Approve");
define("_AM_WFD_ICO_STATS","Stats");

define("_AM_WFD_ICO_IGNORE","Ignore");
define("_AM_WFD_ICO_ACK","Broken Report Acknowledged");
define("_AM_WFD_ICO_REPORT","Acknowledge Broken Report?");
define("_AM_WFD_ICO_CONFIRM","Broken Report Confirmed");
define("_AM_WFD_ICO_CONBROKEN","Confirm Broken Report?");





define("_AM_WFD_DB_IMPORT", "Import");
define("_AM_WFD_DB_CURRENTVER", "Current version: <span class='currentVer'>%s</span>");
define("_AM_WFD_DB_DBVER", "Database Version %s");
define("_AM_WFD_DB_MSG_ADD_DATA", "Data added in table %s");
define("_AM_WFD_DB_MSG_ADD_DATA_ERR", "Error adding data in table %s");
define("_AM_WFD_DB_MSG_CHGFIELD", "Changing field %s in table %s");
define("_AM_WFD_DB_MSG_CHGFIELD_ERR", "Error changing field %s in table %s");
define("_AM_WFD_DB_MSG_CREATE_TABLE", "Table %s created");
define("_AM_WFD_DB_MSG_CREATE_TABLE_ERR", "Error creating table %s");
define("_AM_WFD_DB_MSG_NEWFIELD", "Successfully added field %s");
define("_AM_WFD_DB_MSG_NEWFIELD_ERR", "Error adding field %s");
define("_AM_WFD_DB_NEEDUPDATE", "Your database is out-of-date. Please upgrade your database tables!<br><b>Note : The SmartFactory strongly recommends you to backup all SmartSection tables before running this upgrade script.</b><br>");
define("_AM_WFD_DB_NOUPDATE", "Your database is up-to-date. No updates are necessary.");
define("_AM_WFD_DB_UPDATE_DB", "Updating Database");
define("_AM_WFD_DB_UPDATE_ERR", "Errors updating to version %s");
define("_AM_WFD_DB_UPDATE_NOW", "Update Now!");
define("_AM_WFD_DB_UPDATE_OK", "Successfully updated to version %s");
define("_AM_WFD_DB_UPDATE_TO", "Updating to version %s");

define("_AM_WFD_GOMOD", "Go to module");
define("_AM_WFD_UPDATE_MODULE", "Update module");
define("_AM_WFD_MDOWNLOADS","File Management");
define("_AM_WFD_DB_MSG_UPDATE_TABLE", "Updating field values in %s");
define("_AM_WFD_DB_MSG_UPDATE_TABLE_ERR", "Errors updating field values in %s");

// Mirrors
// waiting mirrors
define("_AM_WFD_AMIRRORS", "Mirrors Management");
define("_AM_WFD_AMIRRORS_WAITING", "Mirrors Waiting Validation:");
define("_AM_WFD_AMIRRORS_INFO", "Mirrors Management Information");
define("_AM_WFD_AMIRRORS_APPROVE", "<b>Approve</b> new mirror without validation.");
define("_AM_WFD_AMIRRORS_EDIT", "<b>Edit</b> new mirror and then approve.");
define("_AM_WFD_AMIRRORS_DELETE", "<b>Delete</b> the new mirror information.");

//mirrors defines
define("_AM_WFD_MIRROR_MIRRORTITLE", "Mirror Host");
define("_AM_WFD_MIRROR_NOPUBLISHEDMIRRORS", "No Published Mirrors Found");
define("_AM_WFD_MIRROR_MIRROR_TOTAL", "Total Mirrors:");
define("_AM_WFD_MIRROR_MIRROR_WAITING", "Waiting Mirrors");
define("_AM_WFD_MIRROR_MIRROR_PUBLISHED", "Published Mirrors");
define("_AM_WFD_MIRROR_SNEWMNAMEDESC", "Approve Mirror: ");
define("_AM_WFD_MIRROR_ID", "ID");
define("_AM_WFD_MIRROR_TITLE", "Title");
define("_AM_WFD_MIRROR_MUSTBEVALID", "Home page logo  must be a valid image file under %s directory (ex. shot.gif). Leave it blank if there is no image file.");
define("_AM_WFD_MIRROR_POSTER", "Submitter");
define("_AM_WFD_MIRROR_SUBMITDATE", "Submitted");
define("_AM_WFD_MIRROR_FHOMEURLTITLE", "Home Page Title: ");
define("_AM_WFD_MIRROR_FHOMEURL", "Home Page URL: ");
define("_AM_WFD_MIRROR_UPLOADIMAGE", "Upload Site Logo:<br /><br />A small logo representing your website.");
define("_AM_WFD_MIRROR_MIRRORIMAGE", "Site Logo:");
define("_AM_WFD_MIRROR_CONTINENT", "Continent:");
define("_AM_WFD_MIRROR_LOCATION", "Location:<br /><br />Example: London, UK");
define("_AM_WFD_MIRROR_DOWNURL", "Download URL:<br /><br />Enter the url to the file.");
define("_AM_WFD_MIRROR_FAPPROVE", "Mirror Approve: ");
define("_AM_WFD_MIRROR_ACTION", "Action");
define("_AM_WFD_MIRROR_NOWAITINGMIRRORS", "No Waiting Mirrors Found");
define("_AM_WFD_MIRROR_MIRROR_UPDATED", "Selected Mirror Modified and Database Updated Successfully");
define("_AM_WFD_MIRROR_APPROVETHIS", "Approve Mirror");

//continents (used in mirrors page)
define("_AM_WFD_CONT1","Africa");
define("_AM_WFD_CONT2","Antarctica");
define("_AM_WFD_CONT3","Asia");
define("_AM_WFD_CONT4","Europe");
define("_AM_WFD_CONT5","North America");
define("_AM_WFD_CONT6","South America");
define("_AM_WFD_CONT7","Oceania");

define("_AM_WFD_HELP","Help");
?>