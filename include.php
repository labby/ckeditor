<?php

/**
 *	@module		ckeditor
 *	@version	see info.php of this module
 *	@authors	Dietrich Roland Pehlke, erpe
 *	@copyright	2012 - 2014 Dietrich Roland Pehlke, erpe
 *	@license	GNU General Public License
 *	@license_terms	see info.php of this module
 *
 */

ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
} else {
	$root = "../";
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= "../";
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php
 
require_once( dirname(__FILE__)."/class.ckeditor4.php");
$ckeditor = new ckeditor();

/**
 *	Absolute path to the ck-editor basic script.
 *
 */
if (!defined("LEPTON_URL")) define("LEPTON_URL", WB_URL);
$ckeditor->ckeditor_file = LEPTON_URL."/modules/ckeditor_4/ckeditor/ckeditor.js";

/**
 *	Custom config-file
 *
 */
$ckeditor->config['customConfig'] = LEPTON_URL."/modules/ckeditor_4/config/ckconfig.js";
 
/**
 *	Language
 *
 */
$ckeditor->config['language'] = strtolower( LANGUAGE );

/**
 *	Load the frontend css
 *
 */
$ckeditor->config['contentsCss'] = '';

/**	*******************************************
 *	First steps for WYSIWYG-Admin support.
 *	Getting skin and Toolbar (-def.) from the 
 */
require_once( dirname(__FILE__)."/class.editorinfo.php" );
$ck_info = new editorinfo();

/**
 *	Get WYSIWYG-Admin information
 *
 *
 */
$fields = array( 'skin', 'menu', 'width', 'height' );

$q = $database->build_mysql_query(
	'SELECT',
	TABLE_PREFIX."mod_wysiwyg_admin",
	$fields,	
	'`editor` = \''.WYSIWYG_EDITOR.'\''
);
$result = $database->query( $q );
$wysiwyg_info = $result->fetchRow( MYSQL_ASSOC );

/**
 *	Skin
 *
 */
$ckeditor->config['skin'] = $wysiwyg_info['skin']; # 0 == 'moono'; # 1 == 'moonocolor';

/**
 *	Toolbar
 *
 */
$ckeditor->config['toolbar'] = $ck_info->toolbars[ $wysiwyg_info['menu'] ]; # Possibilities: Full, Smart, Simple

/**
 *	Height and width
 *
 */
$ckeditor->config['width'] = $wysiwyg_info['width'];
$ckeditor->config['height'] = $wysiwyg_info['width'];

/**	*********************************
 *	End of WYSIWYG-Admin support here
 *
 */
 
/**
 *	The filebrowser are called in the include, because later on we can make switches, use WB_URL and so on
 *	@notice	2014-03-04	Aldus	Comment not clear! M.f.i.!
 *
 */
$ckeditor->basePath = LEPTON_URL."/modules/ckeditor_4/ckeditor/";

$connectorPath = $ckeditor->basePath.'filemanager/connectors/php/connector.php';
$ckeditor->config['filebrowserBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Connector='.$connectorPath;
$ckeditor->config['filebrowserImageBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Image&Connector='.$connectorPath;
$ckeditor->config['filebrowserFlashBrowseUrl'] = $ckeditor->basePath.'filemanager/browser/default/browser.html?Type=Flash&Connector='.$connectorPath;

$ckeditor->config['uploader'] = true;

$uploadPath = $ckeditor->basePath.'filemanager/connectors/php/upload.php?Type=';
$ckeditor->config['filebrowserUploadUrl'] = $uploadPath.'File';
$ckeditor->config['filebrowserImageUploadUrl'] = $uploadPath.'Image';
$ckeditor->config['filebrowserFlashUploadUrl'] = $uploadPath.'Flash';

/**
 *	Function called by parent, default by the wysiwyg-module
 *	
 *	@param	string	The name of the textarea to watch
 *	@param	mixed	The "id" - some other modules handel this param differ
 *	@param	string	Optional the width, default "100%" of given space.
 *	@param	string	Optional the height of the editor - default is '250px'
 *
 *
 */
function show_wysiwyg_editor($name, $id, $content, $width = '100%', $height = '250px') {
	global $ckeditor;
	
	if (true === $ckeditor->force) {
		$ckeditor->config['width'] = $width;		// -> WYSIWYG-Admin
		$ckeditor->config['height'] = $height;		// -> WYSIWYG-Admin
	}
	$ckeditor->config['id'] = $id;
	$ckeditor->config['name'] = $name;
	$ckeditor->config['content'] = $content;
	
	echo $ckeditor->toHTML();
}
?>