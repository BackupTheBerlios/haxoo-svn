<?php
/**
 * From Article module for XOOPS
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         article
 * @since           1.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: about.php 2178 2008-09-26 08:34:09Z phppp $
 */
 
include "header.php";

xoops_cp_header();
loadModuleAdminMenu(1);

function map_getInfo($var)
{
    global $xoopsModule;
    
    // Moved from xoops_version.php
    $modinfo["license"] = "GNU see LICENSE";
    $modinfo["license_file"] = XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") . "/gpl.txt";
    $modinfo["release"] = "17.08.2009";
    
    $modinfo["author_website_url"] = "http://xfr.svn.sourceforge.net/viewvc/xfr/language/modules/sitemap/";
    $modinfo["author_website_name"] = "Xoops Forge";
    $modinfo["author_word"] = _AM_SITEMAP_ABOUT_AUTHOR_WORD_TEXT;
    
    $modinfo["module_status"] = "RC";
    $modinfo["module_team"] = "The Xoops Community.";
    
    $modinfo["module_website_url"] = "http://xfr.svn.sourceforge.net/viewvc/xfr/language/modules/sitemap/";
    $modinfo["module_website_name"] = "XOOPS FORGE";
    
    if(empty($var)) return null;
    if(!$val = $xoopsModule->getInfo( $var )){
        $val = @$modinfo[$var];
    }
    return $val;
}

echo "
    <style type=\"text/css\">
    label,text {
        display: block;
        float: left;
        margin-bottom: 2px;
    }
    label {
        text-align: right;
        width: 150px;
        padding-right: 20px;
    }
    br {
        clear: left;
    }
    </style>
";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . $xoopsModule->getVar("name"). "</legend>";
echo "<div style='padding: 8px;'>";
echo "<img src='" . XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") . "/" . map_getInfo( 'image' ) . "' alt='' hspace='10' vspace='0' /></a>\n";
echo "<div style='padding: 5px;'><strong>" . map_getInfo( 'name' ) . " version " . map_getInfo( 'version' ) . "</strong></div>\n";
echo "<label>" . _AM_SITEMAP_ABOUT_RELEASEDATE . ":</label><text>" . map_getInfo( 'release' ) . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_AUTHOR . ":</label><text>" . map_getInfo( 'author' ) . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_CREDITS . ":</label><text>" . map_getInfo( 'credits' ) . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_LICENSE . ":</label><text><a href=\"".map_getInfo( 'license_file' )."\" target=\"_blank\" >" . map_getInfo( 'license' ) . "</a></text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_HELP . ":</label><text><a href=\"".map_getInfo( 'help' )."\" target=\"_blank\" >Article FAQ</a></text>\n";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_SITEMAP_ABOUT_MODULE_INFO . "</legend>";
echo "<div style='padding: 8px;'>";
echo "<label>" . _AM_SITEMAP_ABOUT_MODULE_STATUS . ":</label><text>" . map_getInfo( 'module_status' ) . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_WEBSITE . ":</label><text>" . "<a href='" . map_getInfo( 'module_website_url' ) . "' target='_blank'>" . map_getInfo( 'module_website_name' ) . "</a>" . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_MODULE_TEAM . ":</label><text>" . map_getInfo( 'module_team' ) . "</text><br />";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_SITEMAP_ABOUT_AUTHOR_INFO . "</legend>";
echo "<div style='padding: 8px;'>";
echo "<label>" . _AM_SITEMAP_ABOUT_AUTHOR_NAME . ":</label><text>" . map_getInfo( 'author' ) . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_WEBSITE . ":</label><text>" . "<a href='" . map_getInfo( 'author_website_url' ) . "' target='_blank'>" . map_getInfo( 'author_website_name' ) . "</a>" . "</text><br />";
echo "<label>" . _AM_SITEMAP_ABOUT_AUTHOR_WORD . ":</label><text>" . map_getInfo( 'author_word' ) . "</text><br />";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_SITEMAP_ABOUT_DISCLAIMER . "</legend>";
echo "<div style='padding: 8px;'>";
echo "<div>". _AM_SITEMAP_ABOUT_DISCLAIMER_TEXT . "</div>";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

$file = XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->getVar("dirname") . "/release_note.txt";
if ( is_readable( $file ) ) {
    echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_SITEMAP_ABOUT_CHANGELOG . "</legend>";
    echo "<div style='padding: 8px;'>";
    echo "<div>". implode("<br />", file( $file )) . "</div>";
    echo "</div>";
    echo "</fieldset>";
    echo "<br clear=\"all\" />";
}

xoops_cp_footer();
?>