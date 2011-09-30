****************************************************************************
 
  Sitemap v1.30b with CVD updates| Xoops Module Hack by Tank | Dec 01, 2008
  Website: http://www.customvirtualdesigns.com
 
****************************************************************************

Sitemap is an XOOPS module that provides a site structure layout and generates
an XML Sitemap which meets Google's requirements. For background info and
previous developer credits refer to "changes.txt" and "modules.txt".

Changes/Updates by CVD  12/01/2008

 - Add this readme file and perform a general update to meet module standards
 - Add preference parameters for generating xml tags 'lastmod', 'changefreq'
   and 'priority' to XML Sitemap. XML URL's categorized are home, module and
   parent/child/sublink.
 - Add style to the sitemap template so module names appear in bordered
   divs with background color applied
 - Add some javascript functionality to the sitemap block so the link to
   display the sitemap toggles between Show and Hide.
 - Updated the 'newbb' plug-in to support the addition of lastmod, changefreq
   and priority XML tags.

Tested on XOOPS 2.3.0

New Install v1.30b with updates - Instructions:
1) After downloading, unzip the package
2) Upload the directory labeled 'sitemap' to your server into the 'modules' subdirectory
3) Select Administration Menu >> System >> Modules
4) Scroll to bottom of screen and click the install icon associated with Sitemap module
5) After installation complete is indicated
   - copy the file 'xml_google.php' which appears in the module root directory
   - place this file in your site's root directory
6) Don't forget to modify Sitemap Preferences for desired features

Update from any previous version to v1.30b with updates - Instructions:
1) After downloading, unzip the package
2) VERY IMPORTANT: Backup your XOOPS database before continuing with this procedure
3) Upload the directory labeled 'sitemap' to your server into the 'modules' subdirectory
   overwriting all existing files
4) Select Administration Menu >> System >> Modules
5) Scroll to Sitemap module and click the Update icon
6) After update indicates successful completion
   - copy the file 'xml_google.php' which appears in the module root directory
   - place this file in your site's root directory
   - if this file already exists then overwrite it with the new copy
7) Don't forget to modify Sitemap Preferences for desired features

Additional Notes:

1) This is not an official software release as GIJoe has taken over module development.
   These files contain our own hacks and updates to version 1.30b.
2) You can view your XML file, the search engines will see, by going to the following URL 
   http://www.yoursite.com/xml_google.php
   substituting your actual site name for 'yoursite'
3) Get registered with Google to submit your sitemap. You will find the link to the
   Google Webmaster Tools page on the sitemap Admin Main display screen.
4) Another step you can perform is adding a sitemap id line in your robots.txt file
   that looks like this after the addition:

   User-agent: *
   Disallow: /cgi-bin/
   Disallow: /tmp/
   Disallow: /cache/
   Disallow: /class/
   Disallow: /images/
   Disallow: /include/
   Disallow: /install/
   Disallow: /kernel/
   Disallow: /language/
   Disallow: /templates_c/
   Disallow: /themes/
   Disallow: /uploads/
   Sitemap: /xml_google.php 
   
5) Currently the lastmod XML tag (when enabled) just grabs the current date or
   current date and time (based on your preference setting). We are looking at
   how easy it will be to make the lastmod field data meaningful.
   
6) If your only purpose in using Sitemap is to generate the Sitemap XML file
   you can go into Modules-Admin and set the order to "0" so sitemap will not
   appear in the Main Menu.
   
7) Another idea you can employ is downloading Main Menu Link module to create
   a menu item that links to http://www.yoursite.com/xml_google.php and set the
   group permissions so only administrators can view this item. This makes it
   easy to check the file to see if you are generating expected results.
   
