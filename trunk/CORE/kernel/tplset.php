<?php
/**
 * XOOPS kernel class
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: tplset.php 4941 2010-07-22 17:13:36Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A Template Set File
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 **/
class XoopsTplset extends XoopsObject
{
    /**
     * constructor
     **/
    function XoopsTplset()
    {
        $this->XoopsObject();
        $this->initVar('tplset_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tplset_name', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('tplset_desc', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('tplset_credits', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('tplset_created', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * Returns Class Base Variable tplset_id
     */
    function id($format = 'N')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * Returns Class Base Variable tplset_id
     */
    function tplset_id($format = '')
    {
        return $this->getVar('tplset_id', $format);
    }

    /**
     * Returns Class Base Variable tplset_name
     */
    function tplset_name($format = '')
    {
        return $this->getVar('tplset_name', $format);
    }

    /**
     * Returns Class Base Variable tplset_desc
     */
    function tplset_desc($format = '')
    {
        return $this->getVar('tplset_desc', $format);
    }

    /**
     * Returns Class Base Variable tplset_credits
     */
    function tplset_credits($format = '')
    {
        return $this->getVar('tplset_credits', $format);
    }

    /**
     * Returns Class Base Variable tplset_created
     */
    function tplset_created($format = '')
    {
        return $this->getVar('tplset_created', $format);
    }

}

/**
 * XOOPS tplset handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS tplset class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 */
class XoopsTplsetHandler extends XoopsObjectHandler
{
    /**
     * create a new block
     *
     * @see XoopsTplset
     * @param bool $isNew is the new tplsets new??
     * @return object XoopsTplset reference to the new tplsets
     **/
    function &create($isNew = true)
    {
        $tplset = new XoopsTplset();
        if ($isNew) {
            $tplset->setNew();
        }
        return $tplset;
    }

    /**
     * retrieve a specific {@link XoopsBlock}
     *
     * @see XoopsTplset
     * @param int $id tplset_id of the tplsets to retrieve
     * @return object XoopsTplset reference to the tplsets
     **/
    function &get($id)
    {
        $tplset = false;
        $id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('tplset') . ' WHERE tplset_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return $tplset;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $tplset = new XoopsTplset();
                $tplset->assignVars($this->db->fetchArray($result));
            }
        }
        return $tplset;
    }

    /**
     * retrieve a specific {@link XoopsBlock}
     *
     * @see XoopsTplset
     * @param int $id tplset_id of the block to retrieve
     * @return object XoopsTplset reference to the tplsets
     **/
    function &getByName($tplset_name)
    {
        $tplset = false;
        $tplset_name = trim($tplset_name);
        if ($tplset_name != '') {
            $sql = 'SELECT * FROM ' . $this->db->prefix('tplset') . ' WHERE tplset_name=' . $this->db->quoteString($tplset_name);
            if (!$result = $this->db->query($sql)) {
                return $tplset;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $tplset = new XoopsTplset();
                $tplset->assignVars($this->db->fetchArray($result));
            }
        }
        return $tplset;
    }

    /**
     * write a new block into the database
     *
     * @param object XoopsTplset $block reference to the tplsets to insert
     * @return bool TRUE if succesful
     **/
    function insert(&$tplset)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($tplset, 'xoopstplset')) {
            return false;
        }
        if (!$tplset->isDirty()) {
            return true;
        }
        if (!$tplset->cleanVars()) {
            return false;
        }
        foreach ($tplset->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($tplset->isNew()) {
            $tplset_id = $this->db->genId('tplset_tplset_id_seq');
            $sql = sprintf("INSERT INTO %s (tplset_id, tplset_name, tplset_desc, tplset_credits, tplset_created) VALUES (%u, %s, %s, %s, %u)", $this->db->prefix('tplset'), $tplset_id, $this->db->quoteString($tplset_name), $this->db->quoteString($tplset_desc), $this->db->quoteString($tplset_credits), $tplset_created);
        } else {
            $sql = sprintf("UPDATE %s SET tplset_name = %s, tplset_desc = %s, tplset_credits = %s, tplset_created = %u WHERE tplset_id = %u", $this->db->prefix('tplset'), $this->db->quoteString($tplset_name), $this->db->quoteString($tplset_desc), $this->db->quoteString($tplset_credits), $tplset_created, $tplset_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($tplset_id)) {
            $tplset_id = $this->db->getInsertId();
        }
        $tplset->assignVar('tplset_id', $tplset_id);
        return true;
    }

    /**
     * delete a tplset from the database
     *
     * @param object XoopsTplset tplset reference to the tplsets to delete
     * @return bool TRUE if succesful
     **/
    function delete(&$tplset)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($tplset, 'xoopstplset')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE tplset_id = %u", $this->db->prefix('tplset'), $tplset->getVar('tplset_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE tplset_name = %s", $this->db->prefix('imgset_tplset_link'), $this->db->quoteString($tplset->getVar('tplset_name')));
        $this->db->query($sql);
        return true;
    }

    /**
     * Get tplsets from the database
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   bool    $id_as_key  return the tplsets id as key?
     * @return  array   Array of {@link XoopsTplset} objects
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->db->prefix('tplset');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere() . ' ORDER BY tplset_id';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $tplset = new XoopsTplset();
            $tplset->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $tplset;
            } else {
                $ret[$myrow['tplset_id']] =& $tplset;
            }
            unset($tplset);
        }
        return $ret;
    }

    /**
     * Count tplsets
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @return  int     Count of tplsets matching $criteria
     */
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('tplset');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result =& $this->db->query($sql)) {
            return 0;
        }
        list ($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * get a list of tplsets matchich certain conditions
     *
     * @param string $criteria conditions to match
     * @return array array of tplsets matching the conditions
     **/
    function getList($criteria = null)
    {
        $ret = array();
        $tplsets = $this->getObjects($criteria, true);
        foreach(array_keys($tplsets) as $i) {
            $ret[$tplsets[$i]->getVar('tplset_name')] = $tplsets[$i]->getVar('tplset_name');
        }
        return $ret;
    }
}
?>