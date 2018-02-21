<?php

/**
 * This file is part of an ADDON for use with LEPTON Core.
 * This ADDON is released under the GNU GPL.
 * Additional license terms can be seen in the info.php of this module.
 *
 * @module          wysiwyg
 * @author          LEPTON Project
 * @copyright       2010-2017 LEPTON Project 
 * @license         http://www.gnu.org/licenses/gpl.html
 * @license_terms   please see info.php of this module
 *
 */


$files_to_register = array(
	'modules/ckeditor/ckeditor/plugins/pagelink/pages.php'
);

LEPTON_secure::getInstance()->accessFiles( $files_to_register );

?>