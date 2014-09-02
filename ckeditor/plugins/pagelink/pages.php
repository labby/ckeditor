<?PHP
header('Content-type: application/javascript');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0, false');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');

/*
    This Plugin read files of a directory and outputs
    a javascript array. Output is:

    var InternPagesSelectBox = new Array(
        new Array( empty, empty ),
        new Array( name, link ),
        new Array( name, link )...
    );

    InternPagesSelectBox will loaded as select options
    to internpage plugin.
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

// Create new admin object
require(LEPTON_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', false, false);

if(!function_exists('cleanup')) {

	function cleanup ($string) {
		// if magic quotes on
		if (get_magic_quotes_gpc())
		{
			$string = stripslashes($string);
		}
		$chars = array(
			"'"	=> "\\'",
			"\"" => "\\\"",
			"&quot;" => "\\\""
		);
		$string = str_replace(array_keys($chars), array_values($chars), $string);
		return preg_replace("/\r?\n/", "\\n", $string );

	} // end function cleanup
}

$InternPagesSelectBox = "var InternPagesSelectBox = new Array( ";
$PagesTitleSelectBox = "var PagesTitleSelectBox = new Array( ";

// Function to generate page list
function getPageTree($parent)
{
	global $admin, $database,$InternPagesSelectBox,$PagesTitleSelectBox;
	$sql  = 'SELECT * FROM `'.TABLE_PREFIX.'pages` ';
	$sql .= 'WHERE `parent`= '.(int)$parent.' ';
    $sql .= ((PAGE_TRASH != 'inline') ?  'AND `visibility` != \'deleted\' ' : ' ');
	$sql .= 'ORDER BY `position` ASC';

	if($resPage = $database->query($sql))
	{
		while( !false == ($page = $resPage->fetchRow() ) )
		{
			if(!$admin->page_is_visible($page)) { continue; }
			$menu_title = cleanup( $page['menu_title'] );
			$page_title = cleanup( $page['page_title'] );
			// Stop users from adding pages with a level of more than the set page level limit
			if($page['level']+1 < PAGE_LEVEL_LIMIT)
            {
				$title_prefix = '';
				for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - '; }
		        $InternPagesSelectBox .= "new Array( '".$title_prefix.$menu_title."', '[wblink".$page['page_id']."]'), ";
		        $PagesTitleSelectBox .= "new Array( '".$page_title."', '[wblink".$page['page_id']."]'), ";
			}
		getPageTree($page['page_id']);
		}
	}
}

getPageTree(0);

$InternPagesSelectBox = substr($InternPagesSelectBox,0,-2);
$PagesTitleSelectBox = substr($PagesTitleSelectBox,0,-2);
echo $InternPagesSelectBox .= " );\n";
echo $PagesTitleSelectBox .= " );\n";

//generate news lists
$NewsItemsSelectBox = "var NewsItemsSelectBox = new Array();";
$ModuleList = "var ModuleList = new Array();";
$newsSections = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE module = 'news'");
while($section = $newsSections->fetchRow()){
	$news = $database->query("SELECT title, link FROM ".TABLE_PREFIX."mod_news_posts WHERE active=1 AND section_id = ".$section['section_id']);
	$ModuleList .= "ModuleList[".$section['page_id']."] = 'News';";
	$NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."] = new Array();";
	while($item = $news->fetchRow()) {
		$NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."][NewsItemsSelectBox[".$section['page_id']."].length] = new Array('".(addslashes($item['title']))."', '".LEPTON_URL.PAGES_DIRECTORY.(addslashes($item['link'])).PAGE_EXTENSION."');";
	}
}

$topicsSections = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE module = 'topics'");
while($section = $topicsSections->fetchRow()){
	$topics = $database->query("SELECT title, link FROM ".TABLE_PREFIX."mod_topics WHERE active > 0 AND section_id = ".$section['section_id']);
	$ModuleList .= "ModuleList[".$section['page_id']."] = 'Topics';";
	$NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."] = new Array();";
	while($item = $topics->fetchRow()) {
		$NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."][NewsItemsSelectBox[".$section['page_id']."].length] = new Array('".(addslashes($item['title']))."', '".LEPTON_URL.PAGES_DIRECTORY."/topics/".(addslashes($item['link'])).PAGE_EXTENSION."');";
	}
}

$bakerySections = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE module = 'bakery'");
while($section = $bakerySections->fetchRow()){
  $bakery = $database->query("SELECT title, link FROM ".TABLE_PREFIX."mod_bakery_items WHERE active=1 AND section_id = ".$section['section_id']);
  $ModuleList .= "ModuleList[".$section['page_id']."] = 'Bakery';";
  $NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."] = new Array();";
  while($item = $bakery->fetchRow()) {
    $NewsItemsSelectBox .= "NewsItemsSelectBox[".$section['page_id']."][NewsItemsSelectBox[".$section['page_id']."].length] = new Array('".(addslashes($item['title']))."', '".LEPTON_URL.PAGES_DIRECTORY.(addslashes($item['link'])).PAGE_EXTENSION."');";
  }
}

echo $NewsItemsSelectBox;
echo $ModuleList;