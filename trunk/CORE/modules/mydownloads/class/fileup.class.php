<?php
// $Id: fileup.class.php,v 1.20 2009/01/14 12:11:03 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                             mydownloads_fileup                            //
//                   Copyright (c) 2005 - 2009 Bluemoon inc.                 //
//                       <http://www.bluemooninc.biz/>                       //
//              Original source by : mydownloads V1.1 Onokazu                //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

class fileUp{
    var $upload_folder;
    var $upload_url;
    var $maxfilesize;
    var $subtype;
    var $file_encode;
    var $errmsg = "";
    var $upfile_url = "";	// If you want force uploading, set this URL before fetchfile().
    var $upfile_size = 0;
    var $upfile_name = "";
    // embedding image MIME Content-Type
    var $imgtype = "gif|jpe?g|png|bmp|x-pmd|x-mld|x-mid|x-smd|x-smaf|x-mpeg";
    // Reject Ext. name
    var $viri = "cgi|php|jsp|pl|htm";
    
    function fileUp($distination,$maxfilesize,$subtype,$file_encode=""){
        $this->upload_folder = XOOPS_ROOT_PATH . $distination;
        $this->upload_url = XOOPS_URL . $distination;
        $this->maxfilesize = $maxfilesize;
        $this->subtype = $subtype;
        $this->file_encode = $file_encode;
        $this->chk_uploadfolder( $this->upload_folder );
    }
    // check or make uploads_dir
    function chk_uploadfolder( $uploads_dir ) {
        if( ! is_dir( $uploads_dir ) ) {
            if( ini_get( "safe_mode" ) ) {
                redirect_header(XOOPS_URL."/modules/".$module->getVar('name'),10,"At first create & chmod 777 '$uploads_dir' by ftp or shell.");
                exit ;
            }
            $rs = mkdir( $uploads_dir , 0777 ) ;
            if( ! $rs ) {
                redirect_header(XOOPS_URL."/modules/".$module->getVar('name'),10,"$uploads_dir is not a directory");
                exit ;
            } else
                @chmod( $uploads_dir , 0777 ) ;
        }
    }
    // Support for Multi-byte filename.
    function cnv2mbfile($str) {
        if (extension_loaded('mbstring')){
            return  mb_convert_encoding($str, $this->file_encode, mb_internal_encoding() );
        } else {
            return $str;
        }
    }    
    function fetchfile(){
        $upfile      = $_FILES['upfile'];                //upload file object 
        $upfile_tmp  = $_FILES['upfile']['tmp_name'];   //tmp file name 
        $upfile_name  = basename($_FILES['upfile']['name']);    //Local File Name ( Use basename for security )
        $upfile_size = $_FILES['upfile']['size'];       //File Size
        $upfile_type = $_FILES['upfile']['type'];       //File MIME Content-Type
        $upfile_url = $this->upload_url . "/" . $upfile_name;
        if ($upfile_tmp){
            if (eregi($this->subtype, $upfile_type)){
                if (eregi($this->imgtype, $upfile_type)){
                    $size = getimagesize($upfile_tmp);
                    if ( !$size || !strcmp($size['type'],$upfile_type) ){
                        $this->errmsg = $upfile_type . " is not image.";
                        return false;
                    }
                }
            } else {
                $this->errmsg = $upfile_type . " is not accepted as MIME type.";
                   return false;
            }
            $ext = strtolower(end(explode(".",$upfile_name))); 
            if (eregi($this->viri, $ext)){
                $this->errmsg = $ext . " is not accepted as extention.";
                   return false;
            }
            // convert for mbstrings
            $upfile_localname = $this->cnv2mbfile($upfile_name);
            $savedDestination = $this->upload_folder . "/" . $upfile_localname;
            if ( strcmp($this->upfile_url,$upfile_url) <> 0 ){
            	if ( is_file( $savedDestination ) ){
            		$this->errmsg = ( 'File ' . $upfile_name 
                    . ' already exists on the server. Please rename this file and try again.' );
            			return false;
            	}
            }
            if (move_uploaded_file($upfile_tmp, $savedDestination)){
                $this->upfile_url = $upfile_url;
                $this->upfile_size = $upfile_size;
                $this->upfile_name = $upfile_name;
            }
        }else{
            $this->upfile_url = NULL;
        }
        return $this->upfile_url;
    }
}
?>
