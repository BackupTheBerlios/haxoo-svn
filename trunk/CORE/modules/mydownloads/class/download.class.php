<?php
// $Id: download.class.php,v 1.14 2008/01/22 17:01:03 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                             mydownloads_fileup                            //
//                   Copyright (c) 2005 - 2008 Bluemoon inc.                 //
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
class download {
	var $ext2mime = array(
		"123"=>"application/vnd.lotus-1-2-3",
		"3g2"=>"video/3gpp2",
		"3gp"=>"video/3gpp",
		"ai"=>"application/postscript",
		"aif"=>"audio/x-aiff",
		"aifc"=>"audio/x-aiff",
		"aiff"=>"audio/x-aiff",
		"album"=>"application/album",
		"amc"=>"application/x-mpeg",
		"api"=>"application/x-httpd-isapi",
		"asc"=>"text/plain",
		"asd"=>"application/astound",
		"asf"=>"video/x-ms-asf",
		"asis"=>"httpd/send-as-is",
		"asn"=>"application/astound",
		"asp"=>"application/x-asap",
		"asx"=>"video/x-ms-asf",
		"au"=>"audio/basic",
		"avi"=>"video/x-msvideo",
		"bcpio"=>"application/x-bcpio",
		"bin"=>"application/octet-stream",
		"bmp"=>"image/bmp",
		"cco"=>"application/x-cocoa",
		"cct"=>"application/x-cct",
		"cdf"=>"application/x-netcdf",
		"cgi"=>"application/x-httpd-cgi",
		"class"=>"application/octet-stream",
		"clp"=>"application/x-msclip",
		"cocoa"=>"application/x-cocoa",
		"com"=>"application/octet-stream",
		"cpio"=>"application/x-cpio",
		"cpt"=>"application/mac-compactpro",
		"crd"=>"application/x-mscardfile",
		"csh"=>"application/x-csh",
		"csm"=>"chemical/x-csml",
		"csml"=>"chemical/x-csml",
		"css"=>"text/css",
		"d96"=>"x-world/x-d96",
		"dcr"=>"application/x-director",
		"dir"=>"application/x-director",
		"dl"=>"application/x-WebSync-plugin",
		"dms"=>"application/octet-stream",
		"doc"=>"application/msword",
		"dot"=>"application/x-dot",
		"dvi"=>"application/x-dvi",
		"dwf"=>"drawing/x-dwf",
		"dwg"=>"image/vnd",
		"dxr"=>"application/x-director",
		"ebk"=>"application/x-expandedbook",
		"emb"=>"chemical/x-embl-dl-nucleotide",
		"embl"=>"chemical/x-embl-dl-nucleotide",
		"eps"=>"application/postscript",
		"es"=>"audio/echospeech",
		"esl"=>"audio/echospeech",
		"etc"=>"application/x-earthtime",
		"etx"=>"text/x-setext",
		"evy"=>"application/x-envoy",
		"exe"=>"application/octet-stream",
		"ez"=>"application/andrew-inset",
		"fdf"=>"application/vnd",
		"fgd"=>"application/x-director",
		"fif"=>"image/fif",
		"fm"=>"application/x-maker",
		"fvi"=>"video/isivideo",
		"gau"=>"chemical/x-gaussian-input",
		"gif"=>"image/gif",
		"gtar"=>"application/x-gtar",
		"gz"=>"application/octet-stream",
		"hdf"=>"application/x-hdf",
		"hdml"=>"text/x-hdml;charset=Shift_JIS",
		"hlp"=>"application/winhlp",
		"hqx"=>"application/mac-binhex40",
		"htm"=>"text/html",
		"html"=>"text/html",
		"ice"=>"x-conference/x-cooltalk",
		"ief"=>"image/ief",
		"ifm"=>"image/gif",
		"ifs"=>"image/ifs",
		"iges"=>"model/iges",
		"igs"=>"model/iges",
		"ins"=>"application/x-NET-Install",
		"ips"=>"application/x-ipscript",
		"ipx"=>"application/x-ipix",
		"ivr"=>"i-world/i-vrml",
		"jbw"=>"application/x-js-taro",
		"jfw"=>"application/x-js-taro",
		"jnlp"=>"application/x-java-jnlp-file",
		"jpe"=>"image/jpeg",
		"jpeg"=>"image/jpeg",
		"jpg"=>"image/jpeg",
		"js"=>"application/x-javascript",
		"jtd"=>"application/x-js-taro",
		"kar"=>"audio/midi",
		"kjx"=>"application/x-kjx",
		"latex"=>"application/x-latex",
		"lcc"=>"application/fastman",
		"lcl"=>"application/x-digitalloca",
		"lcr"=>"application/x-digitalloca",
		"lha"=>"application/octet-stream",
		"LZH"=>"application/octet-stream",
		"lzh"=>"application/octet-stream",
		"m13"=>"application/x-msmediaview",
		"m14"=>"application/x-msmediaview",
		"m3u"=>"audio/x-mpegurl",
		"man"=>"application/x-troff-man",
		"map"=>"application/x-httpd-imap",
		"mbd"=>"application/mbedlet",
		"mcf"=>"text/mcf",
		"mcp"=>"application/netmc",
		"mct"=>"application/x-mascot",
		"mdb"=>"application/x-msaccess",
		"mdc"=>"application/x-mediadesc",
		"mdx"=>"application/x-mediadesc",
		"me"=>"application/x-troff-me",
		"mesh"=>"model/mesh",
		"MID"=>"audio/midi",
		"mid"=>"audio/midi",
		"midi"=>"audio/midi",
		"mif"=>"application/vnd.mif",
		"mio"=>"audio/x-mio",
		"mmf"=>"application/x-smaf",
		"mng"=>"video/x-mng",
		"mny"=>"application/x-msmoney",
		"mocha"=>"application/x-mocha",
		"mof"=>"application/x-yumekara",
		"mol"=>"chemical/x-mdl-molfile",
		"mop"=>"chemical/x-mopac-input",
		"mov"=>"video/quicktime",
		"movie"=>"video/x-sgi-movie",
		"mp2"=>"audio/mpeg",
		"mp3"=>"audio/mpeg",
		"mp4"=>"video/mp4",
		"mpe"=>"video/mpeg",
		"mpeg"=>"video/mpeg",
		"mpg"=>"video/mpeg",
		"mpg4"=>"video/mp4",
		"mpga"=>"audio/mpeg",
		"mpp"=>"application/vnd.ms-project",
		"mrl"=>"text/x-mrml",
		"ms"=>"application/x-troff-ms",
		"msh"=>"model/mesh",
		"mus"=>"x-world/x-d96",
		"nc"=>"application/x-netcdf",
		"nm"=>"application/x-nvat",
		"nva"=>"application/x-neva1",
		"nvat"=>"application/x-nvat",
		"oda"=>"application/oda",
		"oke"=>"audio/karaoke",
		"olh"=>"application/x-onlivehead",
		"olr"=>"application/x-onlivereg",
		"olv"=>"x-world/x-vrml1.0",
		"pac"=>"audio/x-pac",
		"pae"=>"audio/x-epac",
		"pbm"=>"image/x-portable-bitmap",
		"pcv"=>"application/x-pcnavi",
		"pdb"=>"chemical/x-pdb",
		"pdf"=>"application/pdf",
		"pfr"=>"application/font-tdpfr",
		"pgm"=>"image/x-portable-graymap",
		"pgn"=>"application/x-chess-pgn",
		"pmd"=>"application/x-pmd",
		"png"=>"image/png",
		"pnm"=>"image/x-portable-anymap",
		"pot"=>"application/vnd.ms-powerpoint",
		"ppm"=>"image/x-portable-pixmap",
		"pps"=>"application/vnd.ms-powerpoint",
		"ppt"=>"application/vnd.ms-powerpoint",
		"prc"=>"application/x-palmpilot",
		"ps"=>"application/postscript",
		"pub"=>"application/x-mspublisher",
		"qcp"=>"audio/vnd.qcelp",
		"qt"=>"video/quicktime",
		"ra"=>"audio/x-realaudio",
		"ram"=>"audio/x-pn-realaudio",
		"ras"=>"image/x-cmu-raster",
		"rgb"=>"image/x-rgb",
		"rm"=>"audio/x-pn-realaudio",
		"roff"=>"application/x-troff",
		"rp"=>"image/vnd.rn-realpix",
		"rpm"=>"audio/x-pn-realaudio-plugin",
		"rt"=>"text/vnd.rn-realtext",
		"rtf"=>"text/rtf",
		"rtx"=>"text/richtext",
		"rv"=>"video/vnd.rn-realvideo",
		"rxnfile"=>"chemical/x-mdl-rxnfile",
		"scd"=>"application/x-msschedule",
		"sds"=>"application/x-onlive",
		"sea"=>"application/octet-stream",
		"sgm"=>"text/sgml",
		"sgml"=>"text/sgml",
		"sh"=>"application/x-sh",
		"shar"=>"application/x-shar",
		"shtml"=>"text/x-server-parsed-html",
		"silo"=>"model/mesh",
		"sit"=>"application/x-stuffit",
		"skd"=>"application/x-koan",
		"skm"=>"application/x-koan",
		"skp"=>"application/x-koan",
		"skt"=>"application/x-koan",
		"slc"=>"application/x-salsa",
		"smi"=>"application/smil",
		"smil"=>"application/smil",
		"smp"=>"application/studiom",
		"snd"=>"audio/basic",
		"spl"=>"application/x-futuresplash",
		"sprite"=>"application/x-sprite",
		"spt"=>"application/x-spt",
		"src"=>"application/x-wais-source",
		"sv4cpio"=>"application/x-sv4cpio",
		"sv4crc"=>"application/x-sv4crc",
		"svf"=>"image/vnd",
		"svi"=>"application/softvision",
		"svr"=>"x-world/x-svr",
		"swf"=>"application/x-shockwave-flash",
		"t"=>"application/x-troff",
		"talk"=>"text/x-speech",
		"tar"=>"application/x-tar",
		"tbp"=>"application/x-timbuktu",
		"tbt"=>"application/timbuktu",
		"tcl"=>"application/x-tcl",
		"tex"=>"application/x-tex",
		"texinfo"=>"application/x-texinfo",
		"tgf"=>"chemical/x-mdl-tgf",
		"tif"=>"image/tiff",
		"tiff"=>"image/tiff",
		"tki"=>"application/x-tkined",
		"tkined"=>"application/x-tkined",
		"tlk"=>"application/x-tlk",
		"tr"=>"application/x-troff",
		"trm"=>"application/x-msterminal",
		"tsp"=>"application/dsptype",
		"tsv"=>"text/tab-separated-values",
		"ttz"=>"application/t-time",
		"txt"=>"text/plain",
		"ustar"=>"application/x-ustar",
		"vcd"=>"application/x-cdlink",
		"vdo"=>"video/vdo",
		"vex"=>"application/x-yumekara",
		"vgm"=>"video/x-videogram",
		"vgp"=>"video/x-videogram-plugin",
		"vgx"=>"video/x-videogram",
		"vif"=>"video/x-vif",
		"viv"=>"video/vnd.vivo",
		"vivo"=>"video/vnd.vivo",
		"vqe"=>"audio/x-twinvq-plugin",
		"vqf"=>"audio/x-twinvq",
		"vql"=>"audio/x-twinvq",
		"vrml"=>"model/vrml",
		"vrt"=>"x-world/x-vrt",
		"wav"=>"audio/x-wav",
		"wax"=>"audio/x-ms-wax",
		"wbmp"=>"image/vnd.wap.wbmp",
		"wbxml"=>"application/vnd.wap.wbxml",
		"wfp"=>"application/wfphelpap",
		"wi"=>"image/wavelet",
		"wk1"=>"application/vnd.lotus-1-2-3",
		"wk3"=>"application/vnd.lotus-1-2-3",
		"wk4"=>"application/vnd.lotus-1-2-3",
		"wm"=>"video/x-ms-wm",
		"wma"=>"audio/x-ms-wma",
		"wmd"=>"video/x-ms-wmd",
		"wmf"=>"application/x-msmetafile",
		"wml"=>"text/vnd.wap.wml;charset=Shift_JIS",
		"wmlc"=>"application/vnd.wap.wmlc",
		"wmls"=>"text/vnd.wap.wmlscript",
		"wmlsc"=>"application/vnd.wap.wmlscriptc",
		"wmv"=>"video/x-ms-wmv",
		"wmx"=>"video/x-ms-wmx",
		"wmz"=>"video/x-ms-wmz",
		"wri"=>"application/x-mswrite",
		"wrl"=>"model/vrml",
		"ws2"=>"application/x-WebSync2-Plugin",
		"wse"=>"application/x-WebSync-plugin",
		"wss"=>"application/x-WebSync-plugin",
		"wv"=>"video/wavelet",
		"wvh"=>"video/x-webview-h",
		"wvp"=>"video/x-webview-p",
		"wvx"=>"video/x-ms-wvx",
		"xbd"=>"application/vnd.fujixerox.docuworks.binder",
		"xbm"=>"image/x-xbitmap",
		"xdm"=>"application/x-xdma",
		"xdw"=>"application/vnd.fujixerox.docuworks",
		"xhm"=>"application/xhtml+xml",
		"xht"=>"application/xhtml+xml",
		"xhtm"=>"application/xhtml+xml",
		"xhtml"=>"application/xhtml+xml",
		"xla"=>"application/vnd.ms-excel",
		"xlc"=>"application/vnd.ms-excel",
		"xll"=>"application/x-excel",
		"xlm"=>"application/vnd.ms-excel",
		"xls"=>"application/vnd.ms-excel",
		"xlt"=>"application/vnd.ms-excel",
		"xlw"=>"application/vnd.ms-excel",
		"XML"=>"text/xml",
		"xml"=>"text/xml",
		"xpm"=>"image/x-xpixmap",
		"xsl"=>"text/xml",
		"xwd"=>"image/x-xwindowdump",
		"xyz"=>"chemical/x-xyz",
		"Z"=>"application/octet-stream",
		"zac"=>"application/x-zaurus-zac",
		"zip"=>"application/x-zip"
	);
	var $fnameOnServer;
	var $fnameToDwonload;
	var $extension;
	var $filesize;
	var $contentType = "application/octet-stream-dummy";
	
	function download($url){
		global $xoopsModuleConfig;
		preg_match("/[^\/]+$/", $url, $matches);		// take after "/"
		$this->fnameToDwonload = $this->fnameOnServer = $matches[0];
		if (extension_loaded('mbstring')){
			$this->fnameToDwonload =
				 mb_convert_encoding($matches[0],mb_internal_encoding(),$xoopsModuleConfig['filename_code']);
		}
		$path_parts = pathinfo( $matches[0] );
		$this->extension = $path_parts['extension'];
		if (isset($this->ext2mime[$this->extension]))
			$this->contentType = $this->ext2mime[$this->extension];
	}
	function fnameToDownload(){
		return $this->fnameToDwonload;
	}
	function fnameOnServer(){
		return $this->fnameOnServer;
	}
	function contentType(){
		return $this->contentType;
	}
	//
	// For image file
	//
	function get_original_name($nameinblog){	// extract ofile from uname_time_ofile
		$afile = basename($nameinblog);		// Added by hoshiba-farm.com 2006.03.02
		$ofile = preg_replace("/^([^\W_]+)?_(\d{1,14})?_/","",$afile);
		return $ofile;
	}
	function isImageFile($afile){		// Added by hoshiba-farm.com  2006.03.02
		$t = "/^" . ereg_replace("/", "\/", XOOPS_ROOT_PATH) . ereg_replace("/", "\/", $GLOBALS['BlogCNF']['thumb_dir'])."(.+\.(gif|jpe?g|png|bmp)$)/i";
		$o = "/^" . ereg_replace("/", "\/", XOOPS_ROOT_PATH) . ereg_replace("/", "\/", $GLOBALS['BlogCNF']['img_dir'])."(.+\.(gif|jpe?g|png|bmp)$)/i";
		
		if( preg_match($t, $afile, $ss) || preg_match($o, $afile, $ss) ){
			$res = download::get_original_name($ss[count($ss)-2]);	// thumbnail file
		} else {
			$res = null;
		}
		return $res;
	}
}
?>
