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
 * @version         $Id: imagesetimg.php 4941 2010-07-22 17:13:36Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/** 
 * XOOPS Image Sets Image 
 * 
 * @package     kernel 
 * @author      Kazumi Ono  <onokazu@xoops.org> 
 * @copyright   (c) 2000-2003 The Xoops Project - www.xoops.org 
 */ 
class XoopsImagesetimg extends XoopsObject 
{ 
    /** 
     * Constructor 
     */ 
    function XoopsImagesetimg() 
    { 
        $this->XoopsObject(); 
        $this->initVar('imgsetimg_id', XOBJ_DTYPE_INT, null, false); 
        $this->initVar('imgsetimg_file', XOBJ_DTYPE_OTHER, null, false); 
        $this->initVar('imgsetimg_body', XOBJ_DTYPE_SOURCE, null, false); 
        $this->initVar('imgsetimg_imgset', XOBJ_DTYPE_INT, null, false); 
    } 

    /** 
     * Returns Class Base Variable imgsetimg_id with default format N 
     */ 
    function id($format = 'N') 
    { 
        return $this->getVar('imgsetimg_id', $format); 
    } 

    /** 
     * Returns Class Base Variable imgsetimg_id 
     */ 
    function imgsetimg_id($format = '') 
    { 
        return $this->getVar('imgsetimg_id', $format); 
    } 

    /** 
     * Returns Class Base Variable imgsetimg_file 
     */ 
    function imgsetimg_file($format = '') 
    { 
        return $this->getVar('imgsetimg_file', $format); 
    } 

    /** 
     * Returns Class Base Variable imgsetimg_body 
     */ 
    function imgsetimg_body($format = '') 
    { 
        return $this->getVar('imgsetimg_body', $format); 
    } 

    /** 
     * Returns Class Base Variable imgsetimg_imgset 
     */ 
    function imgsetimg_imgset($format = '') 
    { 
        return $this->getVar('imgsetimg_imgset', $format); 
    } 

}


/**
 * XOOPS imageset image handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS imageset image class objects.
 *
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 */

class XoopsImagesetimgHandler extends XoopsObjectHandler
{
    /**
     * Create a new {@link XoopsImageSetImg}
     *
     * @param   boolean $isNew  Flag the object as "new"
     * @return  object
     **/
    function &create($isNew = true)
    {
        $imgsetimg = new XoopsImagesetimg();
        if ($isNew) {
            $imgsetimg->setNew();
        }
        return $imgsetimg;
    }

    /**
     * Load a {@link XoopsImageSetImg} object from the database
     *
     * @param   int     $id     ID
     * @param   boolean $getbinary
     * @return  object  {@link XoopsImageSetImg}, FALSE on fail
     **/
    function &get($id)
    {
        $imgsetimg = false;
        $id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('imgsetimg') . ' WHERE imgsetimg_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return $imgsetimg;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $imgsetimg = new XoopsImagesetimg();
                $imgsetimg->assignVars($this->db->fetchArray($result));
            }
        }
        return $imgsetimg;
    }

    /**
     * Write a {@link XoopsImageSetImg} object to the database
     *
     * @param   object  &$imgsetimg {@link XoopsImageSetImg}
     * @return  bool
     **/
    function insert(&$imgsetimg)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($imgsetimg, 'xoopsimagesetimg')) {
            return false;
        }

        if (!$imgsetimg->isDirty()) {
            return true;
        }
        if (!$imgsetimg->cleanVars()) {
            return false;
        }
        foreach ($imgsetimg->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($imgsetimg->isNew()) {
            $imgsetimg_id = $this->db->genId('imgsetimg_imgsetimg_id_seq');
            $sql = sprintf("INSERT INTO %s (imgsetimg_id, imgsetimg_file, imgsetimg_body, imgsetimg_imgset) VALUES (%u, %s, %s, %s)", $this->db->prefix('imgsetimg'), $imgsetimg_id, $this->db->quoteString($imgsetimg_file), $this->db->quoteString($imgsetimg_body), $this->db->quoteString($imgsetimg_imgset));
        } else {
            $sql = sprintf("UPDATE %s SET imgsetimg_file = %s, imgsetimg_body = %s, imgsetimg_imgset = %s WHERE imgsetimg_id = %u", $this->db->prefix('imgsetimg'), $this->db->quoteString($imgsetimg_file), $this->db->quoteString($imgsetimg_body), $this->db->quoteString($imgsetimg_imgset), $imgsetimg_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($imgsetimg_id)) {
            $imgsetimg_id = $this->db->getInsertId();
        }
        $imgsetimg->assignVar('imgsetimg_id', $imgsetimg_id);
        return true;
    }

    /**
     * Delete an image from the database
     *
     * @param   object  &$imgsetimg {@link XoopsImageSetImg}
     * @return  bool
     **/
    function delete(&$imgsetimg)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($imgsetimg, 'xoopsimagesetimg')) {
            return false;
        }

        $sql = sprintf("DELETE FROM %s WHERE imgsetimg_id = %u", $this->db->prefix('imgsetimg'), $imgsetimg->getVar('imgsetimg_id'));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Load {@link XoopsImageSetImg}s from the database
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   boolean $id_as_key  Use the ID as key into the array
     * @param   boolean $getbinary
     * @return  array   Array of {@link XoopsImageSetImg} objects
     **/
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT DISTINCT i.* FROM ' . $this->db->prefix('imgsetimg') . ' i LEFT JOIN ' . $this->db->prefix('imgset_tplset_link') . ' l ON l.imgset_id=i.imgsetimg_imgset LEFT JOIN ' . $this->db->prefix('imgset') . ' s ON s.imgset_id=l.imgset_id';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sql .= ' ORDER BY imgsetimg_id ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $imgsetimg = new XoopsImagesetimg();
            $imgsetimg->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $imgsetimg;
            } else {
                $ret[$myrow['imgsetimg_id']] =& $imgsetimg;
            }
            unset($imgsetimg);
        }
        return $ret;
    }

    /**
     * Count some imagessetsimg
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @return  int
     **/
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(i.imgsetimg_id) FROM ' . $this->db->prefix('imgsetimg') . ' i LEFT JOIN ' . $this->db->prefix('imgset_tplset_link') . ' l ON l.imgset_id=i.imgsetimg_imgset';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere() . ' GROUP BY i.imgsetimg_id';
        }
        if (!$result =& $this->db->query($sql)) {
            return 0;
        }
        list ($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * Function-Documentation
     * @param type $imgset_id documentation
     * @param type $id_as_key = false documentation
     * @return type documentation
     * @author Kazumi Ono <onokazu@xoops.org>
     **/
    function getByImageset($imgset_id, $id_as_key = false)
    {
        return $this->getObjects(new Criteria('imgsetimg_imgset', intval($imgset_id)), $id_as_key);
    }

    /**
     * Function-Documentation
     * @param type $filename documentation
     * @param type $imgset_id documentation
     * @return type documentation
     * @author Kazumi Ono <onokazu@xoops.org>
     **/
    function imageExists($filename, $imgset_id)
    {
        $criteria = new CriteriaCompo(new Criteria('imgsetimg_file', $filename));
        $criteria->add(new Criteria('imgsetimg_imgset', intval($imgset_id)));
        if ($this->getCount($criteria) > 0) {
            return true;
        }
        return false;
    }
}
?>