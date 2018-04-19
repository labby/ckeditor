<?php

/**
 *	@module			ckeditor
 *	@version		see info.php of this module
 *	@authors		Dietrich Roland Pehlke, erpe
 *	@copyright		2012-2018 Dietrich Roland Pehlke, erpe
 *	@license		GNU General Public License
 *	@license terms	see info.php of this module
 *
 */

class ckeditor
{

	/**
	 *	@var	string	Holds the guid of this class.
	 */
	private $guid = "4B6C3AF1-DAF7-4761-ACDF-9D4ED96C2275";

	/**
	 *	@var	array	Holds the basic configuration-values for the CK-Editor module.
	 *	@access	public
	 *
	 */
	public $config = array(
		'width'	=> "100%",
		'height' => "250px",
		'content' => "",
		'id'	=> '',
		'name'	=> '(no name)',
		'language' => 'en',
		'contentsCss' => '',
		'customConfig' => ''
	);
		
	/**
	 *	@var	array	More-dimensional array for the 'look-up' paths for
	 *					editor.css, editor.style.js, templates.js and the config.js
	 *	@access	public
	 *
	 */
	public $files = array(
		'contentsCss' => Array(
			'/editor.css',
			'/css/editor.css',
			'/editor/editor.css'
		),
		'stylesSet' => Array(
			'/editor.styles.js',
			'/js/editor.styles.js',
			'/editor/editor.styles.js'
		),
		'templates_files' => Array(
			'/editor.templates.js',
			'/js/editor.templates.js',
			'/editor/editor.templates.js'
		),
		'customConfig' => Array(
			'/ckconfig.js',
			'/js/ckconfig.js',
			'/editor/ckconfig.js'
		)
	);

	/**
	 *	@var	string	An internal template-string for the generated textarea.
	 *	@access	public
	 *
	 */
	public $textarea = "\n<textarea name='%s' id='%s' width='%s' height='%s' cols='8' rows='8'>%s</textarea>\n";
	
	/**
	 *	@var	string	Path to the basic script file of CkEditor.js
 	 *	@access	public
	 *
	 */
	public $ckeditor_file = "";
	
	/**
	 *	@var	boolean	Holds whenever the scrip/class is loaded.
	 *	@access	private
	 */
	private $script_loaded = false;
	
	/**
	 *	Boolean to force given height and width from the function call instead of the current settings here.
 	 *	@access	public
	 *
	 */
	public $force = false;
	
	/**
	 *	Boolean for WYSIWYG Admin support (avaible or not)
	 *	@access	public
	 *
	 */
	public $wysiwyg_admin = false;
	
	/**
	 *	Private DB handle
	 *	@access	private
	 *
	 */
	private $db = NULL;
	
	/**
	 *	The constructor of the class
	 *	@access	public
	 *	@param	object	Any valid instance of a database connector.
	 */
	public function __construct( &$db_ref ) {
		$this->db = $db_ref;
		
		/**
		 *	Looking for the WYSIWYG-Admin table in the db.
		 */
		$ignore = TABLE_PREFIX;
		$all = $this->db->list_tables( $ignore );
		$this->wysiwyg_admin = in_array("mod_wysiwyg_admin", $all);
	}
	
	/**
	 *	The destructor of the class.
	 *
	 */
	public function __destruct() {
	
	}
	
	/**
	 *	@param	string	Any HTML-Source, pass by reference
	 *	@access	public
	 *
	 */
	public function reverse_htmlentities(&$html_source) {

		$html_source = str_replace(
			array_keys( $this->lookup_html ),
			array_values( $this->lookup_html ),
			$html_source
		);
    }

	/**
	 *	Build the JS-Script ans return it.
	 *	@return	string	The generated js-config (HTML-) string.
	 */
	public function toHTML() {
		$html  = $this->__build_textarea();
		$html .= $this->__build_replace(); // use of "CKEDITOR.replace" ....
		
		return $html;
	}
	
	/**
	 *	Internal - build the text-area part
	 *	@return	string	The generated HTML-Code.	
	 */
	private function __build_textarea() {
		return sprintf(
			$this->textarea,
			$this->config['name'],
			$this->config['id'],
			$this->config['width'],
			$this->config['height'],
			htmlspecialchars_decode( $this->config['content'] )
		);
	}
	
	/**
	 *	Build the js for the editor.
	 *	@return	string	The generated code.
	 */
	private function __build_replace() {
	
		$s = "";
		if (false == $this->script_loaded) {
			$s .= "\n<script type='text/javascript' src='".$this->ckeditor_file."'></script>\n";
			$this->script_loaded = true;
		}

		$s .= "
			<script>
			CKEDITOR.replace('".$this->config['id']."',{
		";
		
		foreach( $this->config as $key => $value ) {
			$s .= "'".$key."' : ".$this->jsEncode( $value ).",\n";
		}
		
		$s .= "});
		</script>
		";
		
		return $s;
	}
	
	/**
	 * This little function provides a basic JSON support.
	 *
	 * @param mixed $val
	 * @return string
	 */
	private function jsEncode($val)
	{
		if (is_null($val)) {
			return 'null';
		}
		if (is_bool($val)) {
			return $val ? 'true' : 'false';
		}
		if (is_int($val)) {
			return $val;
		}
		if (is_float($val)) {
			return str_replace(',', '.', $val);
		}
		if (is_array($val) || is_object($val)) {
			if (is_array($val) && (array_keys($val) === range(0,count($val)-1))) {
				return '[' . implode(',', array_map(array($this, 'jsEncode'), $val)) . ']';
			}
			$temp = array();
			foreach ($val as $k => $v){
				$temp[] = $this->jsEncode("{$k}") . ':' . $this->jsEncode($v);
			}
			return '{' . implode(',', $temp) . '}';
		}
		// String otherwise
		if (strpos($val, '@@') === 0)
			return substr($val, 2);
		if (strtoupper(substr($val, 0, 9)) == 'CKEDITOR.')
			return $val;

        $aChars = array(
            "\\"    => '\\\\',
            "/"     => '\\/',
            "\n"    => '\\n',
            "\t"    => '\\t',
            "\r"    => '\\r',
            "\x08"  => '\\b',
            "\x0c"  => '\\f',
            '"'     => '\"'
        );
		return '"' . str_replace( array_keys( $aChars ), array_values( $aChars ), $val) . '"';
	}
	
	/**
	 *	Looking for paths inside the current frontend-template.
	 *
	 *	@param string 	A valid "key" from the internal files-array.
	 *					(contentCss, stylesSet, template_files, customConfig)
	 *	@return string	Absolute (LEPTON_URL) to the template-file or an emty string.
	 *
	 */
	public function resolve_path($sTheme="contentsCss")
	{
		global $database, $page_id;
		
		$paths = &$this->files[$sTheme];
		$result = array();
		$database->execute_query(
			"SELECT `template` FROM `".TABLE_PREFIX."pages` WHERE `page_id`=".$page_id."0000",
			true,
			$result,
			false
		);
		
		$lookup_path = LEPTON_PATH."/templates/".( (isset($result['template']) && ($result['template'] != "" ) ) ? $result['template'] : DEFAULT_TEMPLATE );
		foreach($paths as &$path) 
		{
			$filename = $lookup_path.$path;
			if(file_exists($filename))
			{
				return str_replace(LEPTON_PATH, LEPTON_URL, $filename);
			}
		}
		return "";
	}

    /**
     *  Coerce a given format with a valid value, if non given "%" is used.
     *
     *  @param  string  sValue  Any given value-string.
     *  @return string  The coerced value string.
     *
     */
    public function coerceFormat( $sValue )
    {   
        $sValue = trim( $sValue );
        
        if(preg_match('/%|px|em|pt|mm$/i',$sValue))
        {
            return $sValue;
        } else {
            return intval( $sValue )."%";   
        }
    }
}