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
    to wbdroplets plugin.
*/

// include class.secure.php to protect this file and the whole CMS!
if (defined('WB_PATH')) {	
	include(WB_PATH.'/framework/class.secure.php'); 
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

if(!function_exists('cleanup')) {

	function cleanup ($string) {
		// if magic quotes on
		if (get_magic_quotes_gpc())
		{
			$string = stripslashes($string);
		}
		return preg_replace("/\r?\n/", "\\n", $string );

	} // end function cleanup
}

$DropletSelectBox = "\nvar DropletSelectBox = new Array( ";
$description = "\nvar DropletInfoBox = new Array( ";
$usage = "\nvar DropletUsageBox = new Array( ";

$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'mod_droplets` ';
$sql .= 'WHERE `active`=1 ';
$sql .= 'ORDER BY `name` ASC';
if($resRec = $database->query($sql))
{
	while( !false == ($droplet = $resRec->fetchRow( MYSQL_ASSOC ) ) )
	{
		$title = cleanup($droplet['name']);
		$desc = cleanup($droplet['description']);
		$comments = cleanup($droplet['comments']);

		$DropletSelectBox .=  "new Array( '".$title."', '".$droplet['name']."'), ";
		$description .=  "new Array( '".$title."', '".$desc."'), ";
		$usage .=  "new Array( '".$title."', '".$comments."'), ";
	}
}
	
$DropletSelectBox = substr($DropletSelectBox,0,-2);
$description = substr($description,0,-2);
$usage = substr($usage,0,-2);

echo $DropletSelectBox .= " );\n";
echo $description .= " );\n";
echo $usage .= " );\n";

?>