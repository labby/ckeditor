<?php

/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012-2017 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */

/**
 *	Class for the wysiwyg-admin.
 */ 
class editorinfo_CKEDITOR_4
{

	/**
	 *	@var	string	Holds the name of the editor - display inside wysiwyg-admin.	
	 */
	protected $name		= "CK Editor 4";
	
	/**
	 *	@var	string	Holds the guid of this class.
	 */
	protected $guid		= "E3355C6B-794A-4E8C-A505-75A0C2AEFA4F";

	/**
	 *	@var	string	Holds the current version of this class.
	 */
	protected $version	= "0.1.2";

	/**
	 *	@var	string	Holds the (last) author of this class.
	 */
	protected $author	= "Dietrich Roland Pehlke (Aldus)";
	
	/**
	 *	@var	array	Holds the supported skins of this wysiwyg-editor.
	 */
	public $skins = array(
		'moono',
		'moonocolor',
		'moono-lisa'
	);
	
	
	/**
	 *	@var	array	Holds the toolbar-definitions of this wysiwyg-editor.
	 */
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
			array ( 'Droplets', 'Pagelink', 'Link','Unlink','Anchor' ),
			array ( 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ),
			'/',
			array ( 'Styles','Format','Font','FontSize' ),
			array ( 'TextColor','BGColor' )
		),
		
		'Smart' => array(
			array( 'Source', '-', 'Italic', 'Bold', 'Underline' ),
			array( 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Droplets', 'Pagelink', '-', 'Print', 'SpellChecker', '-', 'Scayt' ),
			array( 'Undo','Redo' ),
			array( 'Image' ),
			array( 'About' )
		),

		'Simple' => array(
			array( 'Source', '-', 'Italic', 'Bold' ),
			array( 'Image' ),
			array( 'Droplets', 'Pagelink' ),
			array( 'About' )
		),
		
		/**
		 *	This one is for experimental use only. Use this one for your own studies and
		 *	development e.g. own plugins, icons, tools, etc.
		 *
		 */
		'Experimental' => array(
			array( 'Source', '-', 'Italic', 'Bold', 'Underline', 'Strike', '-', 'Undo', 'Redo' ),
			array( 'Image', 'HorizontalRule', 'SpecialChar' ),
			'/',
			array( 'Droplets', 'Pagelink', '-', 'Unlink', 'Anchor', 'Link' ),
			array( 'Shybutton' ),
			array( 'About' )
		)
	);
	
	/**
	 *	@var	string	Holds the default width of this editor.
	 */
	public $default_width = "100%";
	
	/**
	 *	@var	string	Holds the default height of the editor.
	 */
	public $default_height = "250px";
	
	/**
	 *	The constructor of the class.
	 */
	public function __construct() {
	
	}
	
	/**
	 *	The destructor of this class.
	 */
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
	
	/**
	 *	Looking for entries in the table of the wysiwyg-admin,
	 *	if nothing found we fill it up within the "default" values of this editor.
	 *	This function is called from the install.php and upgrade.php of this module.
	 *
	 *	@param	object	A valid DB handle object. In LEPTON-CMS 1.3 it's an instance of PDO.
	 *					DB connector has at last to support some methods:
	 *					- list_tables (list of all installed tables inside the current database).
	 *					- query (to execute a given query).
	 *					- numRows (number of results of the last query).
	 *					- build_mysql_query (for building MySQL queries)
	 *
	 *					see class.database.php inside framework of LEPTON-CMS for details!.
	 *
	 */
	public function wysiwyg_admin_init( &$db_handle= NULL ) {
		
		// Only execute if first param is set
		if (NULL !== $db_handle) {
		
			$ignore = TABLE_PREFIX;
			$all_fields = $db_handle->list_tables( $ignore );
					
			if (true == in_array("mod_wysiwyg_admin", $all_fields)) {
				
				$table = TABLE_PREFIX."mod_wysiwyg_admin";
				
				$query = "SELECT `id`,`skin`,`menu`,`height`,`width` from `".$table."` where `editor`='ckeditor_4' limit 0,1";
				$result = $db_handle->query ($query );
				if ($result->numRows() == 0) {
									
					$toolbars = array_keys( $this->toolbars );
					
					$fields = array(
						'editor'	=> "ckeditor_4",
						'skin'		=> $this->skins[0],		// first entry
						'menu'		=> $toolbars[0],		// first entry
						'width'		=> $this->default_width,
						'height'	=> $this->default_height
					);
					
					$db_handle->query( 
						$db_handle->build_mysql_query(
							'INSERT',
							TABLE_PREFIX."mod_wysiwyg_admin",
							$fields
						)
					);
				}
			}
			
			$this->__init_droplets( $db_handle );
		}
	}
	
	/**
	 *	CK-Editor 4 comes up within a "shy"-entitie plug in that works
	 *	together within a droplet.
	 */
	private function __init_droplets ( &$db_handle ) {
		$droplet_name = "-"; // !
		$droplet_desc = "Adds a shy-entity.";
		$droplet_code = "return \"&shy;\";";
		$droplet_comment = "Adds a shy-entity. Used e.g. by the CK-Editor.";
		
		$table = "mod_droplets";
		
		$ignore = TABLE_PREFIX;
		$all_tables = $db_handle->list_tables( $ignore );
		
		if (true == in_array( $table, $all_tables)) {
			$query = "SELECT `name` from `".TABLE_PREFIX.$table."` where `name`='".$droplet_name."'";
			$result = $db_handle->query( $query );
			if ($result) {
				if ($result->numRows() == 0) {
					
					$fields = array(
						'name'	=> $droplet_name,
						'description'	=> $droplet_desc,
						'code' => $droplet_code,
						'active' => 1,
						'modified_when' => TIME(),
						'modified_by'	=> 1,
						'comments' => $droplet_comment
					);
					
					$db_handle->query(
						$db_handle->build_mysql_query(
							'INSERT',
							TABLE_PREFIX.$table,
							$fields
						)
					);						
				}
			}
		}
	}
}
?>