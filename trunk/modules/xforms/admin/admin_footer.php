<?php
/**
 * xForms module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright::  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license::    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package::    xDirectory
 * @subpackage:: admin
 * @since:       2.5.0
 * @author::     Magic.Shao <magic.shao@gmail.com> - Susheng Yang <ezskyyoung@gmail.com>
 * @version::	 $Id $
**/

echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=" . XOOPS_URL ."/". $moduleInfo->getInfo("icons32")."/xoopsmicrobutton.gif"." alt=\"XOOPS\" title=\"XOOPS\"></a></div>";
echo "<div class='center smallsmall italic pad5'><strong>" . $xoopsModule->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a></div>";
xoops_cp_footer();

###############################################################################
##                           See license.txt                                 ##
###############################################################################
// $version = number_format($xoopsModule->getVar('version')/100, 2);
// $version = !substr($version, -1, 1) ? substr($version, 0, 3) : $version;

//$credits = "<div style='text-align: right; font-size: x-small; margin-top: 15px;'>Powered by <a href='about.php'>xforms ".$version."</a>";
//echo $credits;

// $version_check = preg_match('/2\.0\./', XOOPS_VERSION) ||  preg_match('/2\.3\./', XOOPS_VERSION) ;
// if( !$version_check ){
	// echo '<br /><span style="color: #F00;"><b>'._AM_XOOPS_VERSION_WRONG.'</b></span>';
// }

// echo '</div>';
