<?php 
/**
 * $Id: main.php,v 1.7 2006/05/23 01:14:53 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
 
//Todo - Still to remove redundat defines from this area. 
define("_MD_WFD_NODOWNLOAD", "This download does not exist!");
define("_MD_WFD_DOWNLOADMINPOSTS", "You need to increase you Post Count<br />Before you can Download Files");
define("_MD_WFD_UPLOADMINPOSTS", "You need to increase you Post Count<br />Before you can Upload Files");

define("_MD_WFD_SUBCATLISTING", "Category Listing");
define("_MD_WFD_ISADMINNOTICE", "Webmaster: There is a problem with this image."); 
define("_MD_WFD_THANKSFORINFO", "Thank-you for your submission. You will be notified once your request has be approved by the webmaster.");
define("_MD_WFD_ISAPPROVED", "Thank-you for your submission. Your request has been approved and will now appear in our listing.");
define("_MD_WFD_THANKSFORHELP", "Thank-you for helping to maintain this directory's integrity.");
define("_MD_WFD_FORSECURITY", "For security reasons your user name and IP address will also be temporarily recorded.");
define("_MD_WFD_NOPERMISETOLINK", "This file doesn't belong to the site you came from <br /><br />Please e-mail the webmaster of the site you came from and tell him:   <br /><b>NOT TO LEECH OTHER SITES LINKS!!</b> <br /><br /><b>Definition of a Leecher:</b> One who is to lazy to link from his own server or steals other peoples hard work and makes it look like his own <br /><br />  Your IP address <b>has been logged</b>.");
define("_MD_WFD_SUMMARY", "Summary<br /><br /><span style='font-weight: normal;'>You can leave this blank<br />A summary will be created if empty</span>");
define("_MD_WFD_DESCRIPTION", "Description");
define("_MD_WFD_SUBMITCATHEAD", "Submit download Form");
define("_MD_WFD_MAIN", "HOME");
define("_MD_WFD_POPULAR", "Popular");
define("_MD_WFD_NEWTHISWEEK", "New this week");
define("_MD_WFD_UPTHISWEEK", "Updated this week");
define("_MD_WFD_POPULARITYLTOM", "Popularity (Least to Most Hits)");
define("_MD_WFD_POPULARITYMTOL", "Popularity (Most to Least Hits)");
define("_MD_WFD_TITLEATOZ", "Title (A to Z)");
define("_MD_WFD_TITLEZTOA", "Title (Z to A)");
define("_MD_WFD_DATEOLD", "Date (Old Files Listed First)");
define("_MD_WFD_DATENEW", "Date (New Files Listed First)");
define("_MD_WFD_RATINGLTOH", "Rating (Lowest Score to Highest Score)");
define("_MD_WFD_RATINGHTOL", "Rating (Highest Score to Lowest Score)");
define("_MD_WFD_DESCRIPTIONC", "Description: ");
define("_MD_WFD_CATEGORYC", "Category: ");
define("_MD_WFD_VERSION", "Version");
define("_MD_WFD_SUBMITDATE", "Released");
define("_MD_WFD_DLTIMES", "Downloaded %s times");
define("_MD_WFD_FILESIZE", "File Size");
define("_MD_WFD_SUPPORTEDPLAT", "Platform");
define("_MD_WFD_HOMEPAGE", "Home Page");
define("_MD_WFD_PUBLISHERC", "Publisher: ");
define("_MD_WFD_RATINGC", "Rating: ");
define("_MD_WFD_ONEVOTE", "1 Vote");
define("_MD_WFD_NUMVOTES", "%s Votes");
define("_MD_WFD_RATETHISFILE", "Rate Resource");
define("_MD_WFD_REVIEWTHISFILE", "Review Resource");
define("_MD_WFD_REVIEWS", "Reviews:");
define("_MD_WFD_DOWNLOADHITS", "Downloads");
define("_MD_WFD_MODIFY", "Modify");
define("_MD_WFD_REPORTBROKEN", "Report Broken");
define("_MD_WFD_BROKENREPORT", "Report Broken Resource");
define("_MD_WFD_SUBMITBROKEN", "Submit");
define("_MD_WFD_BEFORESUBMIT", "Before submitting a broken resource request, please check that the actual source of the file you intend reporting broken, is no longer there and that the website is not temporally down.");
define("_MD_WFD_TELLAFRIEND", "Recommend");
define("_MD_WFD_EDIT", "Edit");
define("_MD_WFD_THEREARE", "There are <b>%s</b> <i>Categories</i> and <b>%s</b> <i>Downloads</i> listed");
define("_MD_WFD_THEREIS", "There is <b>%s</b> <i>Category</i> and <b>%s</b> <i>Downloads</i> listed");
define("_MD_WFD_LATESTLIST", "Latest Listings");
define("_MD_WFD_FILETITLE", "Download Title: ");
define("_MD_WFD_DLURL", "Remote URL: ");
define("_MD_WFD_UPLOAD_FILENAME", "Local Filename: ");
define("_MD_WFD_UPLOAD_FILETYPE", "File Type: ");

define("_MD_WFD_HOMEPAGEC", "Home Page: ");
define("_MD_WFD_UPLOAD_FILEC", "Upload File: ");
define("_MD_WFD_VERSIONC", "Version: ");
define("_MD_WFD_FILESIZEC", "File Size: ");
define("_MD_WFD_NUMBYTES", "%s bytes");
define("_MD_WFD_PLATFORMC", "Platform: ");
define("_MD_WFD_PRICE", "Price");
define("_MD_WFD_LIMITS", "Limitations");
define("_MD_WFD_DOWNLICENSE", "License");
define("_MD_WFD_NOTSPECIFIED", "Not Specified");
define("_MD_WFD_MIRRORSITE", "Download Mirror");
define("_MD_WFD_MIRROR", "Mirror Website");
define("_MD_WFD_PUBLISHER", "Publisher");
define("_MD_WFD_UPDATEDON", "Updated On");
define("_MD_WFD_PRICEFREE", "Free");
define("_MD_WFD_VIEWDETAILS", "View Full Details");
define("_MD_WFD_OPTIONS", 'Options: ');
define("_MD_WFD_NOTIFYAPPROVE", 'Notify me when this file is approved');
define("_MD_WFD_VOTEAPPRE", "Your vote is appreciated.");
define("_MD_WFD_THANKYOU", "Thank you for taking the time to vote here at %s"); // %s is your site name
define("_MD_WFD_VOTEONCE", "Please do not vote for the same resource more than once.");
define("_MD_WFD_RATINGSCALE", "The scale is 1 - 10, with 1 being poor and 10 being excellent.");
define("_MD_WFD_BEOBJECTIVE", "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define("_MD_WFD_DONOTVOTE", "Do not vote for your own resource.");
define("_MD_WFD_RATEIT", "Rate It!");
define("_MD_WFD_INTFILEFOUND", "Here is a good file to download at %s"); // %s is your site name
define("_MD_WFD_RANK", "Rank");
define("_MD_WFD_CATEGORY", "Category");
define("_MD_WFD_HITS", "Hits");
define("_MD_WFD_RATING", "Rating");
define("_MD_WFD_VOTE", "Vote");
define("_MD_WFD_SORTBY", "Sort by:");
define("_MD_WFD_TITLE", "Title");
define("_MD_WFD_DATE", "Date");
define("_MD_WFD_POPULARITY", "Popularity");
define("_MD_WFD_TOPRATED", "Rating");
define("_MD_WFD_CURSORTBY", "Files currently sorted by: %s");
define("_MD_WFD_CANCEL", "Cancel");
define("_MD_WFD_ALREADYREPORTED", "You have already submitted a broken report for this resource.");
define("_MD_WFD_MUSTREGFIRST", "Sorry, you don't have the permission to perform this action.<br />Please register or login first!");
define("_MD_WFD_NORATING", "No rating selected.");
define("_MD_WFD_CANTVOTEOWN", "You cannot vote on the resource you submitted.<br />All votes are logged and reviewed.");
define("_MD_WFD_SUBMITDOWNLOAD", "Submit Download");
define("_MD_WFD_SUB_SNEWMNAMEDESC", "<ul><li>All new Downloads's are subject to validation and may take up to 24 hours before they appear in our listing.</li><li>We reserve the rights to refuse any submitted download or change the content without approval.</li></ul>");
define("_MD_WFD_MAINLISTING", "Main Category Listings");
define("_MD_WFD_LASTWEEK", "Last Week");
define("_MD_WFD_LAST30DAYS", "Last 30 Days");
define("_MD_WFD_1WEEK", "1 Week");
define("_MD_WFD_2WEEKS", "2 Weeks");
define("_MD_WFD_30DAYS", "30 Days");
define("_MD_WFD_SHOW", "Show");
define("_MD_WFD_DAYS", "days");
define("_MD_WFD_NEWDOWNLOADS", "New Downloads");
define("_MD_WFD_TOTALNEWDOWNLOADS", "Total New Downloads");
define("_MD_WFD_DTOTALFORLAST", "Total new downloads for last");
define("_MD_WFD_AGREE", "I Agree");
define("_MD_WFD_DOYOUAGREE", "Do you agree to the above terms?");
define("_MD_WFD_DISCLAIMERAGREEMENT", "Disclaimer");
define("_MD_WFD_DUPLOADSCRSHOT", "Upload Screenshot Image:");
define("_MD_WFD_RESOURCEID", "Resource id#: ");
define("_MD_WFD_REPORTER", "Original Reporter: ");
define("_MD_WFD_DATEREPORTED", "Date Reported: ");
define("_MD_WFD_RESOURCEREPORTED", "Resource Reported Broken");
define("_MD_WFD_BROWSETOTOPIC", "<b>Browse Downloads by alphabetical listing</b>");
define("_MD_WFD_WEBMASTERACKNOW", "Broken Report Acknowledged: ");
define("_MD_WFD_WEBMASTERCONFIRM", "Broken Report Confirmed: ");
define("_MD_WFD_DELETE", "Delete");
define("_MD_WFD_DISPLAYING", "Displayed by: ");
define("_MD_WFD_LEGENDTEXTNEW", "New Today");
define("_MD_WFD_LEGENDTEXTNEWTHREE", "New 3 Days");
define("_MD_WFD_LEGENDTEXTTHISWEEK", "New This Week");
define("_MD_WFD_LEGENDTEXTNEWLAST", "Over 1 Week");
define("_MD_WFD_THISFILEDOESNOTEXIST", "Error: This file does not exist!");
define("_MD_WFD_BROKENREPORTED", "Broken File Reported");
// visit
define("_MD_WFD_DOWNINPROGRESS", "Download in Progress");
define("_MD_WFD_DOWNSTARTINSEC", "Your download should start in 3 seconds...<b>please wait</b>.");
define("_MD_WFD_DOWNNOTSTART", "If your download does not start, ");
define("_MD_WFD_CLICKHERE", "Click here!");
define("_MD_WFD_BROKENFILE", "Broken File");
define("_MD_WFD_PLEASEREPORT", "Please report this broken file to the webmaster, ");
// Reviews
define("_MD_WFD_REV_TITLE", "Review Title:");
define("_MD_WFD_REV_RATING", "Review Rating:");
define("_MD_WFD_REV_DESCRIPTION", "Review:");
define("_MD_WFD_REV_SUBMITREV", "Submit Review");
define("_MD_WFD_ERROR_CREATEREVIEW", "Error when creating a review");

define("_MD_WFD_REV_SNEWMNAMEDESC", " 
Please completely fill out the form below, and we'll add your review as soon as possible.<br /><br />
Thank you for taking the time to submit your opinion. We want to give our users a possibility to find quality software faster.<br /><br />All reviews will be reviewed by one of our webmasters before they are put up on the web site. 
");
define("_MD_WFD_ISNOTAPPROVED", "Your submission has to be approved by a moderator first.");
define("_MD_WFD_LICENCEC", "Software Licence: ");
define("_MD_WFD_LIMITATIONS", "Software limitations: ");
define("_MD_WFD_KEYFEATURESC", "Key Features:<br /><br /><span style='font-weight: normal;'>Seperate each Key Feature with a |</span>");
define("_MD_WFD_REQUIREMENTSC", "System Requirements:<br /><br /><span style='font-weight: normal;'>Seperate each Requirement with |</span>");
define("_MD_WFD_HISTORYC", "Download History:");
define("_MD_WFD_HISTORYD", "Add New Download History:<br /><br /><span style='font-weight: normal;'>The Submit date will automatically be added to this.</span>");
define("_MD_WFD_HOMEPAGETITLEC", "Home Page Title: ");
define("_MD_WFD_REQUIREMENTS", "System Requirements:");
define("_MD_WFD_FEATURES", "Features:");
define("_MD_WFD_HISTORY", "Download History:");
define("_MD_WFD_PRICEC", "Price:");
define("_MD_WFD_SCREENSHOT", "Screenshot:");
define("_MD_WFD_SCREENSHOTCLICK", "Display full image");
define("_MD_WFD_OTHERBYUID", "Other files by: ");
define("_MD_WFD_DOWNTIMES", "Download Times: ");
define("_MD_WFD_MAINTOTAL", "Total Files: ");
define("_MD_WFD_DOWNLOADNOW", "Download Now");
define("_MD_WFD_PAGES", "<b>Pages</b>");
define("_MD_WFD_REVIEWER", "Reviewer");
define("_MD_WFD_RATEDRESOURCE", "Rated Resource");
define("_MD_WFD_SUBMITTER", "Submitter");
define("_MD_WFD_REVIEWTITLE", "User Reviews");
define("_MD_WFD_REVIEWTOTAL", "<b>Reviews total:</b> %s");
define("_MD_WFD_USERREVIEWSTITLE", "User Reviews");
define("_MD_WFD_USERREVIEWS", "Read User Reviews on %s");
define("_MD_WFD_NOUSERREVIEWS", "Be the first person to review %s.");
define("_MD_WFD_ERROR", "Error Updating Database: Information not saved");
define("_MD_WFD_COPYRIGHT", "copyright");
define("_MD_WFD_INFORUM", "Discuss In Forum");

//added frankblack

//submit.php
define("_MD_WFD_NOTALLOWESTOSUBMIT","You are not allowed to submit files");
define("_MD_WFD_INFONOSAVEDB","Information not saved to database: <br /><br />");
define("_MD_WFD_NOTALLOWEDTOMOD","You are not allowed to modify this download");

//review.php
define("_MD_WFD_ERROR_CREATCHANNEL","Create Channel first");
define("_MD_WFD_REVIEW_CATPATH", "Category Path:");
define("_MD_WFD_ADDREVIEW", "Add Review");

//
define("_MD_WFD_NEWLAST","New Submitted Before Last Week");
define("_MD_WFD_NEWTHIS","New Submitted Within This week");
define("_MD_WFD_THREE","New Submitted Within Last Three days");
define("_MD_WFD_TODAY","New Submitted Today");
define("_MD_WFD_NO_FILES","No Files Yet");

//mirror.php
define("_MD_WFD_MIRROR_AVAILABLE", "Mirrors Available:");
define("_MD_WFD_MIRROR_CATPATH", "Category Path:");
define("_MD_WFD_MIRROR_FILENAME", "Filename:");
define("_MD_WFD_DOWNLOADMIRRORS", "Download Mirrors");
define("_MD_WFD_MIRROR_NOTALLOWESTOSUBMIT", "You are not allowed to submit mirrors");
define("_MD_WFD_MIRRORS", "Download Mirrors:");
define("_MD_WFD_USERMIRRORSTITLE", "Available Download Mirrors");
define("_MD_WFD_USERMIRRORS", "View Available Download Mirrors on %s");
define("_MD_WFD_NOUSERMIRRORS", "Add a new Download Mirror on %s.");
define("_MD_WFD_TOTALMIRRORS", "Total Mirrors:");
define("_MD_WFD_ADDMIRROR", "Add Mirror");
define("_MD_WFD_MIRROR_TOTAL", "<b>Total Mirrors:</b> %s");
define("_MD_WFD_MIRROR_HOMEURLTITLE", "Homepage Title:");
define("_MD_WFD_MIRROR_HOMEURL", "Homepage URL:<br /><br />Enter your Homepage url.");
define("_MD_WFD_MIRROR_UPLOADMIRRORIMAGE", "Upload Site Logo:<br /><br />A small logo representing your website.");
define("_MD_WFD_MIRROR_MIRRORIMAGE", "Site Logo:");
define("_MD_WFD_MIRROR_CONTINENT", "Continent:");
define("_MD_WFD_MIRROR_LOCATION", "Location:<br /><br />Example: London, UK");
define("_MD_WFD_MIRROR_DOWNURL", "Download URL:<br /><br />Enter the url to the file.");
define("_MD_WFD_MIRROR_SUBMITMIRROR", "Submit Mirror");
define("_MD_WFD_ERROR_CREATEMIRROR", "Error when creating mirror");
define("_MD_WFD_MIRROR_SNEWMNAMEDESC", " 
Please completely fill out the form below, and we'll add your mirror as soon as possible.<br /><br />
Thank you for your assistance in providing another location to download these files. We want to give our users a possibility to find quality software faster.<br /><br />All mirror submissions will be reviewed by one of our webmasters before they are put up on the web site. 
");
define("_MD_WFD_MIRROR_HHOST", "Host");
define("_MD_WFD_MIRROR_HLOCATION", "Location");
define("_MD_WFD_MIRROR_HCONTINENT", "Continent");
define("_MD_WFD_MIRROR_HDOWNLOAD", "Download");
define("_MD_WFD_MIRROR_OFFLINE", "Server Host Is Offline.");
define("_MD_WFD_MIRROR_ONLINE", "Server Host Is Online.");
define("_MD_WFD_MIRROR_DISABLED", "Server Host Check Disabled.");
//continents (used in mirrors page)
define("_MD_WFD_CONT1","Africa");
define("_MD_WFD_CONT2","Antarctica");
define("_MD_WFD_CONT3","Asia");
define("_MD_WFD_CONT4","Europe");
define("_MD_WFD_CONT5","North America");
define("_MD_WFD_CONT6","South America");
define("_MD_WFD_CONT7","Oceania");


define("_MD_WFD_ADMIN_PAGE",":: Administrative Section ::");
define("_MD_WFD_DOWNLOADS_LIST","Downloads list (%s)");
define("_MD_WFD_NEWDOWNLOADS_ALL","All");
define("_MD_WFD_NEWDOWNLOADS_INTHELAST","In the last %s days");
define("_MD_WFD_DOWNLOAD_MOST_POPULAR","Most popular downloads");
define("_MD_WFD_DOWNLOAD_MOST_RATED","Best rated downloads");


?>