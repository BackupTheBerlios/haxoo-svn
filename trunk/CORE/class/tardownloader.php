<?php
/**
 * Send tar files through a http socket
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
 * @package     kernel
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @version     $Id: tardownloader.php 4941 2010-07-22 17:13:36Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * base class
 */
include_once XOOPS_ROOT_PATH . '/class/downloader.php';
/**
 * Class to handle tar files
 */
include_once XOOPS_ROOT_PATH . '/class/class.tar.php';

class XoopsTarDownloader extends XoopsDownloader
{
    /**
     * Constructor
     *
     * @param string $ext file extension
     * @param string $mimyType Mimetype
     */
    function XoopsTarDownloader($ext = '.tar.gz', $mimyType = 'application/x-gzip')
    {
        $this->archiver = new tar();
        $this->ext = trim($ext);
        $this->mimeType = trim($mimyType);
    }
    
    /**
     * Add a file to the archive
     *
     * @param string $filepath Full path to the file
     * @param string $newfilename Filename (if you don't want to use the original)
     */
    function addFile($filepath, $newfilename = null)
    {
        $this->archiver->addFile($filepath);
        if (isset($newfilename)) {
            // dirty, but no other way
            for($i = 0; $i < $this->archiver->numFiles; $i ++) {
                if ($this->archiver->files[$i]['name'] == $filepath) {
                    $this->archiver->files[$i]['name'] = trim($newfilename);
                    break;
                }
            }
        }
    }
    
    /**
     * Add a binary file to the archive
     *
     * @param string $filepath Full path to the file
     * @param string $newfilename Filename (if you don't want to use the original)
     */
    function addBinaryFile($filepath, $newfilename = null)
    {
        $this->archiver->addFile($filepath, true);
        if (isset($newfilename)) {
            // dirty, but no other way
            for($i = 0; $i < $this->archiver->numFiles; $i ++) {
                if ($this->archiver->files[$i]['name'] == $filepath) {
                    $this->archiver->files[$i]['name'] = trim($newfilename);
                    break;
                }
            }
        }
    }
    
    /**
     * Add a dummy file to the archive
     *
     * @param string $data Data to write
     * @param string $filename Name for the file in the archive
     * @param integer $time
     */
    function addFileData(&$data, $filename, $time = 0)
    {
        $dummyfile = XOOPS_CACHE_PATH . '/dummy_' . time() . '.html';
        $fp = fopen($dummyfile, 'w');
        fwrite($fp, $data);
        fclose($fp);
        $this->archiver->addFile($dummyfile);
        unlink($dummyfile);
        // dirty, but no other way
        for($i = 0; $i < $this->archiver->numFiles; $i ++) {
            if ($this->archiver->files[$i]['name'] == $dummyfile) {
                $this->archiver->files[$i]['name'] = $filename;
                if ($time != 0) {
                    $this->archiver->files[$i]['time'] = $time;
                }
                break;
            }
        }
    }
    
    /**
     * Add a binary dummy file to the archive
     *
     * @param string $data Data to write
     * @param string $filename Name for the file in the archive
     * @param integer $time
     */
    function addBinaryFileData(&$data, $filename, $time = 0)
    {
        $dummyfile = XOOPS_CACHE_PATH . '/dummy_' . time() . '.html';
        $fp = fopen($dummyfile, 'wb');
        fwrite($fp, $data);
        fclose($fp);
        $this->archiver->addFile($dummyfile, true);
        unlink($dummyfile);
        // dirty, but no other way
        for($i = 0; $i < $this->archiver->numFiles; $i ++) {
            if ($this->archiver->files[$i]['name'] == $dummyfile) {
                $this->archiver->files[$i]['name'] = $filename;
                if ($time != 0) {
                    $this->archiver->files[$i]['time'] = $time;
                }
                break;
            }
        }
    }
    
    /**
     * Send the file to the client
     *
     * @param string $name Filename
     * @param boolean $gzip Use GZ compression
     */
    function download($name, $gzip = true)
    {
        $this->_header($name . $this->ext);
        echo $this->archiver->toTarOutput($name . $this->ext, $gzip);
    }
}

?>