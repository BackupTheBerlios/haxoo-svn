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
 * @version         $Id: regstep.php 4941 2010-07-22 17:13:36Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

class ProfileRegstep extends XoopsObject
{
    function __construct()
    {
        $this->initVar('step_id', XOBJ_DTYPE_INT);
        $this->initVar('step_name', XOBJ_DTYPE_TXTBOX);
        $this->initVar('step_desc', XOBJ_DTYPE_TXTAREA);
        $this->initVar('step_order', XOBJ_DTYPE_INT, 1);
        $this->initVar('step_save', XOBJ_DTYPE_INT, 0);
    }

    function ProfileRegstep()
    {
        $this->__construct();
    }
}

class ProfileRegstepHandler extends XoopsPersistableObjectHandler
{
    function ProfileRegstepHandler(&$db)
    {
        $this->__construct($db);
    }

    function __construct($db)
    {
        parent::__construct($db, 'profile_regstep', 'profileregstep', 'step_id', 'step_name');
    }

    /**
     * Delete an object from the database
     * @see XoopsPersistableObjectHandler
     *
     * @param profileRegstep $obj
     * @param bool $force
     *
     * @return bool
     */
    function delete($obj, $force = false)
    {
        if (parent::delete($obj, $force)) {
            $field_handler =& xoops_getmodulehandler('field');
            return $field_handler->updateAll('step_id', 0, new Criteria('step_id', $obj->getVar('step_id')));
        }
        return false;
    }
}
?>