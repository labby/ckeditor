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
	
	$ckeditor->config['width'] = $width;
	$ckeditor->config['height'] = $height;
	$ckeditor->config['id'] = $id;
	$ckeditor->config['name'] = $name;
	$ckeditor->config['content'] = $content;
	
	echo $ckeditor->toHTML();
}
?>