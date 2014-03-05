<?php

/**
 *	First experimental version of a (new) WYSIWYG-Admin support (-class).
 *	Some informations about skin(-s) and used toolbar(-s) and there definations inside this file.
 *
 *	@version	0.1.0
 *	@date		2014-03-04
 *	@author		Dietrich Roland Pehlke (CMS-LAB)
 *
 */
 
class editorinfo
{

	protected $name		= "CK Editor 4";
	
	protected $guid		= "E3355C6B-794A-4E8C-A505-75A0C2AEFA4F";

	protected $version	= "0.1.0";

	protected $author	= "Dietrich Roland Pehkle (Aldus)";
	
	public $skins = array(
		'moono',
		'moonocolor'
	);
	
	public $toolbars = array(
		
		'Full'	=> 	array(
			array ( 'Source','-','NewPage','Templates','Preview' ),
			array ( 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Print','SpellChecker','-','Scayt' ),
			array ( 'Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat' ),
			array ( 'Maximize','ShowBlocks','-','gMap','Code','About' ),
			'/',
			array ( 'Bold','Italic','Underline','Strike','-','Subscript','Superscript','Shy' ),
			array ( 'NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv' ),
			array ( 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ),
			array ( 'wbdroplets','wblink','Link','Unlink','Anchor' ),
			array ( 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ),
			'/',
			array ( 'Styles','Format','Font','FontSize' ),
			array ( 'TextColor','BGColor' )
		),
		
		'Smart' => array(
			array( 'Source', '-', 'Italic', 'Bold', 'Underline' ),
			array( 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Print', 'SpellChecker', '-', 'Scayt' ),
			array( 'Undo','Redo' ),
			array( 'Image' ),
			array( 'About' )
		),

		'Simple' => array(
			array( 'Source', '-', 'Italic', 'Bold' ),
			array( 'Image' ),
			array( 'About' )
		)
	);
	
	public function __construct() {
	
	}
	
	public function __destruct() {
	
	}
	
	/**
	 *	@param	string	What (toolbars or skins)
	 *	@param	string	Name of the select
	 *	@param	string	Name of the selected item.
	 *	@return	string	The generated (HTML-) select tag.
	 *
	 */
	public function build_select( $what="toolbars", $name="menu", $selected_item) {
		switch( $what ) {
			case "toolbars":
				$data_ref = array_keys($this->toolbars);
				break;
			
			case 'skins':
				$data_ref = &$this->skins;
				break;
				
			default:
				return "";
		}
		
		$s = "\n<select name='".$name."'>\n";
		foreach($data_ref as &$key) {
			$s .= "<option name='".$key."' ".( $key == $selected_item ? "selected='selected'" : "" )."'>".$key."</option>\n";
		}
		$s .= "</select>\n";
		
		return $s;
	}
}
?>