<?php
/**
 * Block Class Manager
*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     system
 * @version     $Id:$
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

require_once XOOPS_ROOT_PATH . '/kernel/block.php';

/**
 * System Block
 *
 * @copyright   copyright (c) 2000 XOOPS.org
 * @package     system
 */
class SystemBlock extends XoopsBlock
{
    function __construct()
    {
        parent::__construct();
    }

    function getForm($mode='edit')
    {
        if ($this->isNew()) {
            $title = _AM_SYSTEM_BLOCKS_ADDBLOCK;
            $modules = array(-1);
            $groups = array( XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS, XOOPS_GROUP_ADMIN );
            $this->setVar('block_type', 'C');
            $this->setVar('visible',1);
            $op = 'save';
        } else {
            // Search modules
            $blocklinkmodule_handler =& xoops_getmodulehandler('blocklinkmodule');
            $criteria = new CriteriaCompo(new Criteria('block_id', $this->getVar('bid') ));
            $blocklinkmodule = $blocklinkmodule_handler->getObjects($criteria);
            foreach ($blocklinkmodule  as $link) {
                $modules[] = $link->getVar('module_id');
            }
            // Saerch perms
            $groupperm_handler =& xoops_gethandler('groupperm');
            $groups =& $groupperm_handler->getGroupIds('block_read', $this->getVar('bid'));
            switch ($mode) {
                case 'edit':
                    $title = _AM_SYSTEM_BLOCKS_EDITBLOCK;
                    break;
                case 'clone':
                    $title = _AM_SYSTEM_BLOCKS_CLONEBLOCK;
                    $this->setVar('bid', 0);
                    if ( $this->isCustom() ) {
                        $this->setVar('block_type', 'C');
                    } else {
                        $this->setVar('block_type', 'D');
                    }
                    break;
            }
            $op = 'save';
        }
        $form = new XoopsThemeForm($title, 'blockform', 'admin.php', 'post', true);
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormLabel(_AM_SYSTEM_BLOCKS_NAME, $this->getVar('name')));
        }
        // Side position
        $side_select = new XoopsFormSelect(_AM_SYSTEM_BLOCKS_TYPE, 'side', $this->getVar('side'));
        $side_select->addOptionArray(array(
            0 => _AM_SYSTEM_BLOCKS_SBLEFT,
            1 => _AM_SYSTEM_BLOCKS_SBRIGHT,
            3 => _AM_SYSTEM_BLOCKS_CBLEFT,
            4 => _AM_SYSTEM_BLOCKS_CBRIGHT,
            5 => _AM_SYSTEM_BLOCKS_CBCENTER,
            7 => _AM_SYSTEM_BLOCKS_CBBOTTOMLEFT,
            8 => _AM_SYSTEM_BLOCKS_CBBOTTOMRIGHT,
            9 => _AM_SYSTEM_BLOCKS_CBBOTTOM));
        $form->addElement($side_select);
        // Order
        $form->addElement(new XoopsFormText(_AM_SYSTEM_BLOCKS_WEIGHT, 'weight', 2, 5, $this->getVar('weight')));
        // Display
        $form->addElement(new XoopsFormRadioYN(_AM_SYSTEM_BLOCKS_VISIBLE, 'visible', $this->getVar('visible')));
        // Visible In
        $mod_select = new XoopsFormSelect(_AM_SYSTEM_BLOCKS_VISIBLEIN, 'modules', $modules, 5, true);
        $module_handler =& xoops_gethandler('module');
        $criteria = new CriteriaCompo(new Criteria('hasmain', 1));
        $criteria->add(new Criteria('isactive', 1));
        $module_list = $module_handler->getList($criteria);
        $module_list[-1] = _AM_SYSTEM_BLOCKS_TOPPAGE;
        $module_list[0] = _AM_SYSTEM_BLOCKS_ALLPAGES;
        ksort($module_list);
        $mod_select->addOptionArray($module_list);
        $form->addElement($mod_select);
        // Title
        $form->addElement(new XoopsFormText(_AM_SYSTEM_BLOCKS_TITLE, 'title', 50, 255, $this->getVar('title')), false );
        if ($this->isNew() || $this->isCustom()) {
            $editor_configs=array();
            $editor_configs["name"] ="content_block";
            $editor_configs["value"] = $this->getVar('content', 'e');
            $editor_configs["rows"] = 20;
            $editor_configs["cols"] = 100;
            $editor_configs["width"] = "100%";
            $editor_configs["height"] = "400px";
            $editor_configs["editor"] = xoops_getModuleOption('blocks_editor', 'system');
            $form->addElement(new XoopsFormEditor(_AM_SYSTEM_BLOCKS_CONTENT, "content_block", $editor_configs), true);
            if ( in_array( $editor_configs["editor"], array('dhtmltextarea','textarea' ) ) ) {
                $ctype_select = new XoopsFormSelect(_AM_SYSTEM_BLOCKS_CTYPE, 'c_type', $this->getVar('c_type'));
                $ctype_select->addOptionArray(array(
                    'H' => _AM_SYSTEM_BLOCKS_HTML,
                    'P' => _AM_SYSTEM_BLOCKS_PHP,
                    'S' => _AM_SYSTEM_BLOCKS_AFWSMILE,
                    'T' => _AM_SYSTEM_BLOCKS_AFNOSMILE));
                $form->addElement($ctype_select);
            } else {
                $form->addElement(new XoopsFormHidden('c_type', 'H'));
            }
        } else {
            if ($this->getVar('template') != '') {
                $tplfile_handler =& xoops_gethandler('tplfile');
                $btemplate = $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $this->getVar('bid') );
                if (count($btemplate) > 0) {
                    $form->addElement(new XoopsFormLabel(_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate[0]->getVar('tpl_id') . '">' . _AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
                } else {
                    $btemplate2 = $tplfile_handler->find('default', 'block', $this->getVar('bid'));
                    if (count($btemplate2) > 0) {
                        $form->addElement(new XoopsFormLabel(_AM_SYSTEM_BLOCKS_CONTENT, '<a href="' . XOOPS_URL . '/modules/system/admin.php?fct=tplsets&amp;op=edittpl&amp;id=' . $btemplate2[0]->getVar('tpl_id') . '" rel="external">' . _AM_SYSTEM_BLOCKS_EDITTPL . '</a>'));
                    }
                }
            }
            if ( $this->getOptions() != false ) {
                $form->addElement(new XoopsFormLabel(_AM_SYSTEM_BLOCKS_OPTIONS, $this->getOptions()));
            } else {
                $form->addElement(new XoopsFormHidden('options', $this->getVar('options')));
            }
            $form->addElement(new XoopsFormHidden('c_type', 'H'));
        }
        $cache_select = new XoopsFormSelect(_AM_SYSTEM_BLOCKS_BCACHETIME, 'bcachetime', $this->getVar('bcachetime'));
        $cache_select->addOptionArray(array(
            '0' => _NOCACHE,
            '30' => sprintf(_SECONDS, 30),
            '60' => _MINUTE,
            '300' => sprintf(_MINUTES, 5),
            '1800' => sprintf(_MINUTES, 30),
            '3600' => _HOUR,
            '18000' => sprintf(_HOURS, 5),
            '86400' => _DAY,
            '259200' => sprintf(_DAYS, 3),
            '604800' => _WEEK,
            '2592000' => _MONTH));
        $form->addElement($cache_select);
        // Groups
        $form->addElement(new XoopsFormSelectGroup( _AM_SYSTEM_BLOCKS_GROUP, 'groups', true, $groups, 5, true) );

        $form->addElement(new XoopsFormHidden('block_type', $this->getVar('block_type')));
        $form->addElement(new XoopsFormHidden('mid', $this->getVar('mid')));
        $form->addElement(new XoopsFormHidden('func_num', $this->getVar('func_num')));
        $form->addElement(new XoopsFormHidden('func_file', $this->getVar('func_file')));
        $form->addElement(new XoopsFormHidden('show_func', $this->getVar('show_func')));
        $form->addElement(new XoopsFormHidden('edit_func', $this->getVar('edit_func')));
        $form->addElement(new XoopsFormHidden('template', $this->getVar('template')));
        $form->addElement(new XoopsFormHidden('dirname', $this->getVar('dirname')));
        $form->addElement(new XoopsFormHidden('name', $this->getVar('name')));
        $form->addElement(new XoopsFormHidden('bid', $this->getVar('bid')));
        $form->addElement(new XoopsFormHidden('op', $op ));
        $form->addElement(new XoopsFormHidden('fct', 'blocksadmin'));
        $button_tray = new XoopsFormElementTray('', '&nbsp;');
        if ($this->isNew() || $this->isCustom()) {
            $preview = new XoopsFormButton('', 'previewblock', _PREVIEW, 'preview');
            $preview->setExtra("onclick=\"blocks_preview();\"");
            $button_tray->addElement( $preview );
        }
        $button_tray->addElement(new XoopsFormButton('', 'submitblock', _SUBMIT, 'submit'));
        $form->addElement($button_tray);

        return $form;
    }

    /**
     * XoopsBlock::getOptions()
     *
     * @return
     */
    function getOptions()
    {
        global $xoopsConfig;
        if (!$this->isCustom()) {
            $edit_func = $this->getVar('edit_func');
            if (!$edit_func) {
                return false;
            }
            if (file_exists($GLOBALS['xoops']->path('modules/' . $this->getVar('dirname') . '/blocks/' . $this->getVar('func_file')))) {
                if (file_exists($file = $GLOBALS['xoops']->path('modules/' . $this->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/blocks.php'))) {
                    include_once $file;
                } elseif (file_exists($file = $GLOBALS['xoops']->path('modules/' . $this->getVar('dirname') . '/language/english/blocks.php'))) {
                    include_once $file;
                }
                include_once $GLOBALS['xoops']->path('modules/' . $this->getVar('dirname') . '/blocks/' . $this->getVar('func_file'));
                $options = explode("|", $this->getVar("options"));
                $edit_form = $edit_func($options);
                if (!$edit_form) {
                    return false;
                }
                return $edit_form;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function isCustom()
    {
        if ($this->getVar('block_type') == 'C') return true;
        return false;
    }

    /**
     * do stripslashes/htmlspecialchars according to the needed output
     *
     * @param $format      output use: S for Show and E for Edit
     * @param $c_type    type of block content
     * @returns string
     */
    function getContent($format = 's', $c_type = 'T')
    {
        $format = strtolower($format);
        $c_type = strtoupper($c_type);
        switch ($format) {
            case 's':
                // check the type of content
                // H : custom HTML block
                // P : custom PHP block
                // S : use text sanitizater (smilies enabled)
                // T : use text sanitizater (smilies disabled)
                if ($c_type == 'H') {
                    return str_replace('{X_SITEURL}', XOOPS_URL . '/', $this->getVar('content', 'n'));
                } else if ($c_type == 'P') {
                    ob_start();
                    echo eval($this->getVar('content', 'n'));
                    $content = ob_get_contents();
                    ob_end_clean();
                    return str_replace('{X_SITEURL}', XOOPS_URL . '/', $content);
                } else if ($c_type == 'S') {
                    $myts =& MyTextSanitizer::getInstance();
                    $content = str_replace('{X_SITEURL}', XOOPS_URL . '/', $this->getVar('content', 'n'));
                    return $myts->displayTarea($content, 1, 1);
                } else {
                    $myts =& MyTextSanitizer::getInstance();
                    $content = str_replace('{X_SITEURL}', XOOPS_URL . '/', $this->getVar('content', 'n'));
                    return $myts->displayTarea($content, 1, 0);
                }
                break;
            case 'e':
                return $this->getVar('content', 'e');
                break;
            default:
                return $this->getVar('content', 'n');
                break;
        }
    }
}

/**
 * System block handler class. (Singelton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS block class objects.
 *
 * @copyright   copyright (c) 2000 XOOPS.org
 * @package     system
 * @subpackage  avatar
 */
class SystemBlockHandler extends XoopsPersistableObjectHandler
{
    function __construct($db)
    {
        parent::__construct($db, 'newblocks', 'SystemBlock', 'bid', 'title');
    }

    function insert($obj)
    {
        $obj->setVar('last_modified', time());
        return parent::insert($obj);
    }

    /**
     * retrieve array of {@link XoopsBlock}s meeting certain conditions
     *
     * @param   object  $criteria   {@link CriteriaElement} with conditions for the blocks
     * @param   bool    $id_as_key  should the blocks' bid be the key for the returned array?
     * @return  array               {@link XoopsBlock}s matching the conditions
     **/
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT DISTINCT(b.bid), b.* FROM ' . $this->db->prefix('newblocks') . ' b LEFT JOIN ' . $this->db->prefix('block_module_link') . ' l ON b.bid=l.block_id';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        while ($myrow = $this->db->fetchArray($result)) {
            $block = new SystemBlock();
            $block->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $block;
            } else {
                $ret[$myrow['bid']] = & $block;
            }
            unset($block);
        }
        return $ret;
    }

    /**
     * get all the blocks that match the supplied parameters
     *
     * @param $side
     *        0: sideblock - left
     *        1: sideblock - right
     *        2: sideblock - left and right
     *        3: centerblock - left
     *        4: centerblock - right
     *        5: centerblock - center
     *        6: centerblock - left, right, center
     * @param   $groupid    groupid (can be an array)
     * @param   $visible    0: not visible 1: visible
     * @param   $orderby    order of the blocks
     * @returns             array of block objects
     */
    function getAllBlocksByGroup($groupid, $asobject = true, $side = null, $visible = null, $orderby = "b.weight,b.bid", $isactive = 1)
    {
        $db =& XoopsDatabaseFactory::getDatabaseConnection();
        $ret = array();
        if (!$asobject) {
            $sql = 'SELECT b.bid ';
        } else {
            $sql = 'SELECT b.* ';
        }
        $sql .= "FROM " . $db->prefix("newblocks") . " b LEFT JOIN " . $db->prefix("group_permission") . " l ON l.gperm_itemid=b.bid WHERE gperm_name = 'block_read' AND gperm_modid = 1";
        if (is_array($groupid)) {
            $sql .= " AND (l.gperm_groupid=" . $groupid[0] . "";
            $size = count($groupid);
            if ($size > 1) {
                for($i = 1; $i < $size; $i ++) {
                    $sql .= " OR l.gperm_groupid=" . $groupid[$i] . "";
                }
            }
            $sql .= ")";
        } else {
            $sql .= " AND l.gperm_groupid=" . $groupid . "";
        }
        $sql .= " AND b.isactive=" . $isactive;
        if (isset($side)) {
            // get both sides in sidebox? (some themes need this)
            if ($side == XOOPS_SIDEBLOCK_BOTH) {
                $side = "(b.side=0 OR b.side=1)";
            } elseif ($side == XOOPS_CENTERBLOCK_ALL) {
                $side = "(b.side=3 OR b.side=4 OR b.side=5 OR b.side=7 OR b.side=8 OR b.side=9 )";
            } else {
                $side = "b.side=" . $side;
            }
            $sql .= " AND " . $side;
        }
        if (isset($visible)) {
            $sql .= " AND b.visible=$visible";
        }
        $sql .= " ORDER BY $orderby";
        $result = $db->query($sql);
        $added = array();
        while ($myrow = $db->fetchArray($result)) {
            if (!in_array($myrow['bid'], $added)) {
                if (!$asobject) {
                    $ret[] = $myrow['bid'];
                } else {
                    $ret[] = new XoopsBlock($myrow);
                }
                array_push($added, $myrow['bid']);
            }
        }
        return $ret;
    }

    function getBlockByPerm( $groupid )
    {
        $ret = array();
        if (isset($groupid)) {
            $sql = "SELECT DISTINCT gperm_itemid FROM " . $this->db->prefix('group_permission') . " WHERE gperm_name = 'block_read' AND gperm_modid = 1";
            if ( is_array($groupid) ) {
                $sql .= ' AND gperm_groupid IN (' . implode(',', $groupid) . ')';
            } else {
                if (intval($groupid) > 0) {
                    $sql .= ' AND gperm_groupid=' . intval($groupid);
                }
            }
            $result = $this->db->query($sql);
            $blockids = array();
            while ( $myrow = $this->db->fetchArray( $result ) ) {
                $blockids[] = $myrow['gperm_itemid'];
            }
            if (empty($blockids)) {
                return $blockids;
            }
            return $blockids;
        }
    }

    function getAllByGroupModule($groupid, $module_id = 0, $toponlyblock = false, $visible = null, $orderby = 'b.weight, m.block_id', $isactive = 1)
    {
        $isactive = intval($isactive);
        $db = $GLOBALS['xoopsDB'];
        $ret = array();
        if (isset($groupid)) {
            $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = 1";
            if ( is_array($groupid) ) {
                $sql .= ' AND gperm_groupid IN ('.implode(',', $groupid).')';
            } else {
                if (intval($groupid) > 0) {
                    $sql .= ' AND gperm_groupid='.intval($groupid);
                }
            }
            $result = $db->query($sql);
            $blockids = array();
            while ( $myrow = $db->fetchArray($result) ) {
                $blockids[] = $myrow['gperm_itemid'];
            }
            if (empty($blockids)) {
                return $blockids;
            }
        }
        $sql = 'SELECT b.* FROM '.$db->prefix('newblocks').' b, '.$db->prefix('block_module_link').' m WHERE m.block_id=b.bid';
        $sql .= ' AND b.isactive='.$isactive;
        if (isset($visible)) {
            $sql .= ' AND b.visible='.intval($visible);
        }
        if (!isset($module_id)) {
        } elseif (!empty($module_id)) {
            $sql .= ' AND m.module_id IN (0,'. intval($module_id);
            if ($toponlyblock) {
                $sql .= ',-1';
            }
            $sql .= ')';
        } else {
            if ($toponlyblock) {
                $sql .= ' AND m.module_id IN (0,-1)';
            } else {
                $sql .= ' AND m.module_id=0';
            }
        }
        if (!empty($blockids)) {
            $sql .= ' AND b.bid IN ('.implode(',', $blockids).')';
        }
        $sql .= ' ORDER BY '.$orderby;
        $result = $db->query($sql);
        while ( $myrow = $db->fetchArray($result) ) {
            $block = new XoopsBlock($myrow);
            $ret[$myrow['bid']] =& $block;
            unset($block);
        }
        return $ret;
    }

    function getNonGroupedBlocks($module_id = 0, $toponlyblock = false, $visible = null, $orderby = 'b.weight, m.block_id', $isactive = 1)
    {
        $db = $GLOBALS['xoopsDB'];
        $ret = array();
        $bids = array();
        $sql = "SELECT DISTINCT(bid) from ".$db->prefix('newblocks');
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $bids[] = $myrow['bid'];
            }
        }
        $sql = "SELECT DISTINCT(p.gperm_itemid) from ".$db->prefix('group_permission')." p, ".$db->prefix('groups')." g WHERE g.groupid=p.gperm_groupid AND p.gperm_name='block_read'";
        $grouped = array();
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $grouped[] = $myrow['gperm_itemid'];
            }
        }
        $non_grouped = array_diff($bids, $grouped);
        if (!empty($non_grouped)) {
            $sql = 'SELECT b.* FROM '.$db->prefix('newblocks').' b, '.$db->prefix('block_module_link').' m WHERE m.block_id=b.bid';
            $sql .= ' AND b.isactive='.intval($isactive);
            if (isset($visible)) {
                $sql .= ' AND b.visible='.intval($visible);
            }
            if (!isset($module_id)) {
            } elseif (!empty($module_id)) {
                $sql .= ' AND m.module_id IN (0,'. intval($module_id);
                if ($toponlyblock) {
                    $sql .= ',-1';
                }
                $sql .= ')';
            } else {
                if ($toponlyblock) {
                    $sql .= ' AND m.module_id IN (0,-1)';
                } else {
                    $sql .= ' AND m.module_id=0';
                }
            }
            $sql .= ' AND b.bid IN ('.implode(',', $non_grouped).')';
            $sql .= ' ORDER BY '.$orderby;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $block = new XoopsBlock($myrow);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;
    }

    /**
     * XoopsBlock::countSimilarBlocks()
     *
     * @param mixed $moduleId
     * @param mixed $funcNum
     * @param mixed $showFunc
     * @return
     */
    function countSimilarBlocks($moduleId, $funcNum, $showFunc = null)
    {
        $funcNum = intval($funcNum);
        $moduleId = intval($moduleId);
        if ($funcNum < 1 || $moduleId < 1) {
            // invalid query
            return 0;
        }
        $db =& XoopsDatabaseFactory::getDatabaseConnection();
        if (isset($showFunc)) {
            // showFunc is set for more strict comparison
            $sql = sprintf("SELECT COUNT(*) FROM %s WHERE mid = %d AND func_num = %d AND show_func = %s", $db->prefix('newblocks'), $moduleId, $funcNum, $db->quoteString(trim($showFunc)));
        } else {
            $sql = sprintf("SELECT COUNT(*) FROM %s WHERE mid = %d AND func_num = %d", $db->prefix('newblocks'), $moduleId, $funcNum);
        }
        if (!$result = $db->query($sql)) {
            return 0;
        }
        list ($count) = $db->fetchRow($result);
        return $count;
    }
}

?>