<?php
/**
 * XOOPS Kernel Class
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
 * @version         $Id: groupperm.php 7630 2011-09-17 15:29:05Z trabis $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A group permission
 *
 * These permissions are managed through a {@link XoopsGroupPermHandler} object
 *
 * @package     kernel
 *
 * @author      Kazumi Ono  <onokazu@xoops.org>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 */
class XoopsGroupPerm extends XoopsObject
{
    /**
     * Constructor
     *
     */
    function XoopsGroupPerm()
    {
        $this->XoopsObject();
        $this->initVar('gperm_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_groupid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_itemid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('gperm_modid', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('gperm_name', XOBJ_DTYPE_OTHER, null, false);
    }

    /**
     * Returns Class Base Variable gperm_id
     */
    function id($format = 'N')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * Returns Class Base Variable gperm_id
     */
    function gperm_id($format = '')
    {
        return $this->getVar('gperm_id', $format);
    }

    /**
     * Returns Class Base Variable gperm_groupid
     */
    function gperm_groupid($format = '')
    {
        return $this->getVar('gperm_groupid', $format);
    }

    /**
     * Returns Class Base Variable gperm_itemid
     */
    function gperm_itemid($format = '')
    {
        return $this->getVar('gperm_itemid', $format);
    }

    /**
     * Returns Class Base Variable gperm_modid
     */
    function gperm_modid($format = '')
    {
        return $this->getVar('gperm_modid', $format);
    }

    /**
     * Returns Class Base Variable gperm_name
     */
    function gperm_name($format = '')
    {
        return $this->getVar('gperm_name', $format);
    }

}

/**
 * XOOPS group permission handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS group permission class objects.
 * This class is an abstract class to be implemented by child group permission classes.
 *
 * @see          XoopsGroupPerm
 * @author       Kazumi Ono  <onokazu@xoops.org>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 */
class XoopsGroupPermHandler extends XoopsObjectHandler
{
    /**
     * Create a new {@link XoopsGroupPerm}
     *
     * @return    bool    $isNew  Flag the object as "new"?
     */
    function &create($isNew = true)
    {
        $perm = new XoopsGroupPerm();
        if ($isNew) {
            $perm->setNew();
        }
        return $perm;
    }

    /**
     * Retrieve a group permission
     *
     * @param    int $id ID
     *
     * @return    object  {@link XoopsGroupPerm}, FALSE on fail
     */
    function &get($id)
    {
        $id = intval($id);
        $perm = false;
        if ($id > 0) {
            $sql = sprintf("SELECT * FROM %s WHERE gperm_id = %u", $this->db->prefix('group_permission'), $id);
            if (!$result = $this->db->query($sql)) {
                return $perm;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $perm = new XoopsGroupPerm();
                $perm->assignVars($this->db->fetchArray($result));
            }
        }
        return $perm;
    }

    /**
     * Store a {@link XoopsGroupPerm}
     *
     * @param    object  &$perm  {@link XoopsGroupPerm} object
     *
     * @return    bool    TRUE on success
     */
    function insert(&$perm)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($perm, 'xoopsgroupperm')) {
            return false;
        }
        if (!$perm->isDirty()) {
            return true;
        }
        if (!$perm->cleanVars()) {
            return false;
        }
        foreach ($perm->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($perm->isNew()) {
            $gperm_id = $this->db->genId('group_permission_gperm_id_seq');
            $sql = sprintf("INSERT INTO %s (gperm_id, gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (%u, %u, %u, %u, %s)", $this->db->prefix('group_permission'), $gperm_id, $gperm_groupid, $gperm_itemid, $gperm_modid, $this->db->quoteString($gperm_name));
        } else {
            $sql = sprintf("UPDATE %s SET gperm_groupid = %u, gperm_itemid = %u, gperm_modid = %u WHERE gperm_id = %u", $this->db->prefix('group_permission'), $gperm_groupid, $gperm_itemid, $gperm_modid, $gperm_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($gperm_id)) {
            $gperm_id = $this->db->getInsertId();
        }
        $perm->assignVar('gperm_id', $gperm_id);
        return true;
    }

    /**
     * Delete a {@link XoopsGroupPerm}
     *
     * @param    object  &$perm
     *
     * @return    bool    TRUE on success
     */
    function delete(&$perm)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($perm, 'xoopsgroupperm')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE gperm_id = %u", $this->db->prefix('group_permission'), $perm->getVar('gperm_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Retrieve multiple {@link XoopsGroupPerm}s
     *
     * @param    object  $criteria   {@link CriteriaElement}
     * @param    bool    $id_as_key  Use IDs as array keys?
     *
     * @return    array   Array of {@link XoopsGroupPerm}s
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->db->prefix('group_permission');
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
            $perm = new XoopsGroupPerm();
            $perm->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $perm;
            } else {
                $ret[$myrow['gperm_id']] =& $perm;
            }
            unset($perm);
        }
        return $ret;
    }

    /**
     * Count some {@link XoopsGroupPerm}s
     *
     * @param    object  $criteria   {@link CriteriaElement}
     *
     * @return    int
     */
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('group_permission');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list ($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * Delete all permissions by a certain criteria
     *
     * @param    object  $criteria   {@link CriteriaElement}
     *
     * @return    bool    TRUE on success
     */
    function deleteAll($criteria = null)
    {
        $sql = sprintf("DELETE FROM %s", $this->db->prefix('group_permission'));
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Delete all module specific permissions assigned for a group
     *
     * @param   int  $gperm_groupid ID of a group
     * @param   int  $gperm_modid ID of a module
     *
     * @return  bool TRUE on success
     */
    function deleteByGroup($gperm_groupid, $gperm_modid = null)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_groupid', intval($gperm_groupid)));
        if (isset($gperm_modid)) {
            $criteria->add(new Criteria('gperm_modid', intval($gperm_modid)));
        }
        return $this->deleteAll($criteria);
    }

    /**
     * Delete all module specific permissions
     *
     * @param   int  $gperm_modid ID of a module
     * @param   string  $gperm_name Name of a module permission
     * @param   int  $gperm_itemid ID of a module item
     *
     * @return  bool TRUE on success
     */
    function deleteByModule($gperm_modid, $gperm_name = null, $gperm_itemid = null)
    {
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', intval($gperm_modid)));
        if (isset($gperm_name)) {
            $criteria->add(new Criteria('gperm_name', $gperm_name));
            if (isset($gperm_itemid)) {
                $criteria->add(new Criteria('gperm_itemid', intval($gperm_itemid)));
            }
        }
        return $this->deleteAll($criteria);
    }

    /**
     * Check permission
     *
     * @param   string    $gperm_name       Name of permission
     * @param   int       $gperm_itemid     ID of an item
     * @param   int/array $gperm_groupid    A group ID or an array of group IDs
     * @param   int       $gperm_modid      ID of a module
     * @param   bool       $trueifadmin      Returns true for admin groups
     *
     * @return  bool    TRUE if permission is enabled
     */
    function checkRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1, $trueifadmin = true)
    {
        if (empty($gperm_groupid)) {
            return false;
        } else if (is_array($gperm_groupid)) {
            if (in_array(XOOPS_GROUP_ADMIN, $gperm_groupid) && $trueifadmin) {
                return true;
            }
            $criteria_group = new CriteriaCompo();
            foreach ($gperm_groupid as $gid) {
                $criteria_group->add(new Criteria('gperm_groupid', $gid), 'OR');
            }
        } else {
            if (XOOPS_GROUP_ADMIN == $gperm_groupid && $trueifadmin) {
                return true;
            }
            $criteria_group = new CriteriaCompo(new Criteria('gperm_groupid', $gperm_groupid));
        }
        $criteria = new CriteriaCompo(new Criteria('gperm_modid', $gperm_modid));
        $criteria->add($criteria_group);
        $criteria->add(new Criteria('gperm_name', $gperm_name));
        $gperm_itemid = intval($gperm_itemid);
        if ($gperm_itemid > 0) {
            $criteria->add(new Criteria('gperm_itemid', $gperm_itemid));
        }
        if ($this->getCount($criteria) > 0) {
            return true;
        }
        return false;
    }

    /**
     * Add a permission
     *
     * @param   string  $gperm_name       Name of permission
     * @param   int     $gperm_itemid     ID of an item
     * @param   int     $gperm_groupid    ID of a group
     * @param   int     $gperm_modid      ID of a module
     *
     * @return  bool    TRUE if success
     */
    function addRight($gperm_name, $gperm_itemid, $gperm_groupid, $gperm_modid = 1)
    {
        $perm =& $this->create();
        $perm->setVar('gperm_name', $gperm_name);
        $perm->setVar('gperm_groupid', $gperm_groupid);
        $perm->setVar('gperm_itemid', $gperm_itemid);
        $perm->setVar('gperm_modid', $gperm_modid);
        return $this->insert($perm);
    }

    /**
     * Get all item IDs that a group is assigned a specific permission
     *
     * @param   string    $gperm_name       Name of permission
     * @param   int/array $gperm_groupid    A group ID or an array of group IDs
     * @param   int       $gperm_modid      ID of a module
     *
     * @return  array     array of item IDs
     */
    function getItemIds($gperm_name, $gperm_groupid, $gperm_modid = 1)
    {
        $ret = array();
        $criteria = new CriteriaCompo(new Criteria('gperm_name', $gperm_name));
        $criteria->add(new Criteria('gperm_modid', intval($gperm_modid)));
        if (is_array($gperm_groupid)) {
            $criteria2 = new CriteriaCompo();
            foreach($gperm_groupid as $gid) {
                $criteria2->add(new Criteria('gperm_groupid', $gid), 'OR');
            }
            $criteria->add($criteria2);
        } else {
            $criteria->add(new Criteria('gperm_groupid', intval($gperm_groupid)));
        }
        $perms = $this->getObjects($criteria, true);
        foreach (array_keys($perms) as $i) {
            $ret[] = $perms[$i]->getVar('gperm_itemid');
        }
        return array_unique($ret);
    }

    /**
     * Get all group IDs assigned a specific permission for a particular item
     *
     * @param   string  $gperm_name       Name of permission
     * @param   int     $gperm_itemid     ID of an item
     * @param   int     $gperm_modid      ID of a module
     *
     * @return  array   array of group IDs
     */
    function getGroupIds($gperm_name, $gperm_itemid, $gperm_modid = 1)
    {
        $ret = array();
        $criteria = new CriteriaCompo(new Criteria('gperm_name', $gperm_name));
        $criteria->add(new Criteria('gperm_itemid', intval($gperm_itemid)));
        $criteria->add(new Criteria('gperm_modid', intval($gperm_modid)));
        $perms = $this->getObjects($criteria, true);
        foreach(array_keys($perms) as $i) {
            $ret[] = $perms[$i]->getVar('gperm_groupid');
        }
        return $ret;
    }
}
?>