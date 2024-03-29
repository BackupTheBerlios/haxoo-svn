<?php
/**
 * Extended User Profile
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
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: visibility.php 7606 2011-09-15 20:10:30Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class ProfileVisibility extends XoopsObject
{
    function __construct()
    {
        $this->initVar('field_id', XOBJ_DTYPE_INT);
        $this->initVar('user_group', XOBJ_DTYPE_INT);
        $this->initVar('profile_group', XOBJ_DTYPE_INT);
    }

    function ProfileVisibility()
    {
        $this->__construct();
    }
}

class ProfileVisibilityHandler extends XoopsPersistableObjectHandler
{
    function ProfileVisibilityHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct(&$db)
    {
        parent::__construct($db, 'profile_visibility', 'profilevisibility', 'field_id');
    }

    /**
     * Get fields visible to the $user_groups on a $profile_groups profile
     *
     * @param array $profile_groups groups of the user to be accessed
     * @param array $user_groups    groups of the visitor, default as $GLOBALS['xoopsUser']
     *
     * @return array
     */
    function getVisibleFields($profile_groups, $user_groups = null)
    {
        $profile_groups[] = $user_groups[] = 0;
        $sql = "SELECT field_id FROM {$this->table} WHERE profile_group IN (" . implode(',', $profile_groups) . ")";
        $sql .= " AND user_group IN (" . implode(',', $user_groups) . ")";
        $field_ids = array();
        if ($result = $this->db->query($sql)) {
            while (list($field_id) = $this->db->fetchRow($result)) {
                $field_ids[] = $field_id;
            }
        }
        return $field_ids;
    }
}
?>