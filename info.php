<?php

/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012 - 2016 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
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

$module_directory	= 'ckeditor_4';
$module_name		= 'CKEditor-4';
$module_function	= 'WYSIWYG';
$module_version		= '4.6.0.0';
$module_platform	= '2.x';
$module_author		= 'erpe, Dietrich Roland Pehlke (Aldus)';
$module_license		= '<a target="_blank" href="http://www.gnu.org/licenses/lgpl.html">LGPL</a>';
$module_license_terms = '-';
$module_description = 'Includes latest (see release no) CKEditor; CKE allows editing content and can be integrated in frontend and backend modules.';
$module_guid 		= '76c55e3d-3ba9-4222-9db8-5aa2ba5b52ba';
$module_home		= 'http://cms-lab.com';

/**
 *	Detailed changelog: see the commits on github at
 *	https://github.com/labby/ckeditor_4/commits/master
 *
 */
?>