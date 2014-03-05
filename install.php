<?php

/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright	2012 - 2014 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */
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

/**
 *	WYSIWYG-Admin
 */
 
/**
 *	Looking for the WYSIWYG-Admin table in the db.
 */

$ignore = TABLE_PREFIX;
$all_fields = $database->list_tables( $ignore );
		
if (true == in_array("mod_wysiwyg_admin", $all_fields)) {
	
	$table = TABLE_PREFIX."mod_wysiwyg_admin";
	
	$query = "SELECT `id`,`skin`,`menu`,`height`,`width` from `".$table."` where `editor`='ckeditor_4'limit 0,1";
	$result = $database->query ($query );
	if ($result->numRows() == 0) {
		
		require_once(dirname(__FILE__)."/class.editorinfo.php");
		$ck_info = new editorinfo();
		
		$toolbars = array_keys( $ck_info->toolbars );
		
		$fields = array(
			'editor'	=> "ckeditor_4",
			'skin'	=> $ck_info->skins[0],		// first entry
			'menu'	=> $toolbars[0],			// first entry
			'width'	=> $ck_info->default_width,
			'height' => $ck_info->default_height
		);
		
		$database->query( 
			$database->build_mysql_query(
				'INSERT',
				TABLE_PREFIX."mod_wysiwyg_admin",
				$fields
			)
		);
	}
}
?>