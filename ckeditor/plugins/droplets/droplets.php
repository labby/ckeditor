<?PHP
header('Content-type: application/javascript');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0, false');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

/*
    This Plugin read files of a directory and outputs
    a javascript array. Output is:

    var DropletSelectBox = new Array(
        new Array( name, link ),
        new Array( name, link )...
    );

    DropletSelectBox will loaded as select options
    to droplets plugin.
*/

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

if (!function_exists('wbdroplet_clean_str')) {
	function wbdroplet_clean_str( &$aStr) {
		$vars = array(
			'"' => "\\\"",
			'\'' => "",
			"\n" => "<br />",
			"\r" => ""
		);
		
		return str_replace( array_keys($vars), array_values($vars), $aStr);
	}
}

$DropletSelectBox = "\nvar DropletSelectBox = new Array( ";
$description = "\nvar DropletInfoBox = new Array( ";
$usage = "\nvar DropletUsageBox = new Array( ";

$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_droplets` ';
$sql .= 'WHERE `active`=1 ';
$sql .= 'ORDER BY `name` ASC';

$all_droplets = array();
$database->execute_query( $sql, true, $all_droplets, true );

foreach($all_droplets as $droplet) {
	$title		= wbdroplet_clean_str($droplet['name']);
	$desc		= wbdroplet_clean_str($droplet['description']);
	$comments	= wbdroplet_clean_str($droplet['comments']);

	$DropletSelectBox .=  "new Array( '".$title."', '".$droplet['name']."'), ";
	$description .=  "new Array( '".$title."', '".$desc."'), ";
	$usage .=  "new Array( '".$title."', '".$comments."'), ";
}
	
$DropletSelectBox = substr($DropletSelectBox,0,-2);
$description = substr($description,0,-2);
$usage = substr($usage,0,-2);

echo $DropletSelectBox .= " );\n";
echo $description .= " );\n";
echo $usage .= " );\n";

?>