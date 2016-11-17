/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012 - 2016 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */
 
/*
* WARNING: Clear the cache of your browser cache after you modify this file!
* If you don't do this, you may notice that your browser is ignoring all your changes.
*
* --------------------------------------------------
*
* Note: Some CKEditor configs are set in /modules/ckeditor/include.php
* 
*/

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
/*
config.toolbar_Basic = [
    [ 'Source', '-', 'Bold', 'Italic', 'Underline', 'Image'],[ 'About'],
    '/',
    [ 'Source', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
];

config.toolbar = "Basic";
*/
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'alignment', groups : [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
		{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	// config.removeButtons = 'Underline,Subscript,Superscript';

	// Se the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	// config.removeDialogTabs = 'image:advanced;link:advanced';
    config.removeDialogTabs = 'link:advanced';
    
    config.entities_latin = false;

	// extraPlugins configuration setting
	//config.extraPlugins = "droplets,pagelink,wblink,shybutton,justify";
};
