<?php

/**
 *	@module		ckeditor
 *	@version	see info.php of this module
 *	@authors	Dietrich Roland Pehlke, erpe
 *	@copyright	2012 - 2013 Dietrich Roland Pehlke, erpe
 *	@license	GNU General Public License
 *	@license_terms	see info.php of this module
 *
 */


class ckeditor
{
	private $guid = "244312D5-DB24-4FBA-99A4-D855EA45E77A";

	public $config = array(
		'width'	=> "100%",
		'height' => "250px",
		'content' => "",
		'id'	=> '',
		'name'	=> ''
	);
	
	public $textarea = "\n<textarea name='%s' id='%s' width='%s' height='%s' cols='8' rows='8'>%s</textarea>\n";
	
	public $ckeditor_file = "";
	
	private $script_loaded = false;
	
	public function __construct() {
		
	}
	
	public function __destruct() {
	
	}
	
	public function toHTML() {
		$html  = $this->__build_textarea();
		$html  .= $this->__build_script();
		
		
		return $html;
	}
	
	private function __build_textarea() {
		return sprintf(
			$this->textarea,
			$this->config['name'],
			$this->config['id'],
			$this->config['width'],
			$this->config['height'],
			$this->config['content']
		);
	}
	
	private function __build_script() {
		$s = "";
		if (false == $this->script_loaded) {
			$s .= "\n<script type='text/javascript' src='".$this->ckeditor_file."'></script>\n";
			$this->script_loaded = true;
		}
		$s .= "
		<script>
		CKEDITOR.replace( '". $this->config['id']. "');
		</script>
		";
		
		return $s;
	}
}
?>