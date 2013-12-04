<?php
/*
= LuxCal event calendar index =

© Copyright 2009-2013  Issam - www.Issam.eu

This file is part of the LuxCal Web Calendar.

The LuxCal Web Calendar is free software: you can redistribute it and/or modify it under 
the terms of the GNU General Public License as published by the Free Software Foundation, 
either version 3 of the License, or (at your option) any later version.

The LuxCal Web Calendar is distributed in the hope that it will be useful, but WITHOUT 
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A 
PARTICULAR PURPOSE. See the GNU General Public License for more details.
*/

//get php tools
require './common/toolbox.php';

//sanity check
if (version_compare(PHP_VERSION, '5.1.0') < 0) { //test PHP version
	exit('<br><br><b>You need PHP version 5.1.0 or higher</b><br>Your current version is: '.PHP_VERSION);
}
validVars(); //validate GET and POST variables

//Current LuxCal version-maintenance suffix
define("LCV","3.1.2");

//set error reporting
error_reporting(E_ALL ^ E_NOTICE); //errors, no notices
//error_reporting(E_ALL); //errors and notices - test line
ini_set('display_errors',0);

//proxies: don't cache
header("Cache-control: private");

//no db credentials: install
if (!file_exists('./lcconfig.php') and !file_exists('./lcaldbc.dat')) {
	header("Location: install".substr(str_replace('.','',LCV),0,3).".php"); exit();
} 

//connect to db
$dbPfix = dbConnect();

//no db connection or LCC (dbConnect) not current: upgrade
if ($dbPfix === false or LCC != substr(LCV,0,5)) {
	header("Location: upgrade".substr(str_replace('.','',LCV),0,3).".php"); exit();
}

//start session
session_set_cookie_params(3600);
session_start();
$sessId = session_id();
//compute/set calendar ID
if (!empty($_GET['cal'])) {
	$rSet = dbQuery("SHOW TABLES LIKE '".$_GET['cal']."_settings'"); //check if calendar exists
	
	
	
	



	if (mysql_num_rows($rSet) == 0) {	exit('Calendar "'.$_GET['cal'].'" does not exist.'); }
	unset($_SESSION['settings']);
	if (!empty($_SESSION['cal'])) {
		saveSession($sessId,$_SESSION['cal']); //save session data to db
	}
	loadSession($sessId,$_GET['cal']); //load session from db
	$calID = $_SESSION['cal'] = $_GET['cal'];
} elseif (!empty($_SESSION['cal'])) {
	$calID = $_SESSION['cal'];
} else {
	$calID = $_SESSION['cal'] = $dbPfix;
}

//if no session try cookie
if (empty($_SESSION['uid']) and isset($_COOKIE['LCR'.$calID])) {
  list($_SESSION['uid'],$_SESSION['cL']) = @unserialize(str_replace('\\','',($_COOKIE['LCR'.$calID]))); 
}

//emulate register_globals off (deprecated as off PHP 5.3)
if (ini_get('register_globals')) {
	if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) { die('GLOBALS overwrite attempt detected'); }
	$noUnset = array('GLOBALS','_GET','_POST','_COOKIE','_REQUEST','_SERVER','_ENV','_FILES'); //vars that shouldn't be unset
	$input = array_merge($_GET,$_POST,$_COOKIE,$_SERVER,$_ENV,$_FILES,$_SESSION);
	foreach ($input as $k => $v) {
		if (!in_array($k,$noUnset) and isset($GLOBALS[$k])) { unset($GLOBALS[$k]); }
	}
}

if (empty($_SESSION['settings'])) { //get settings from database
	$_SESSION['settings'] = getSettings();
}
$set =& $_SESSION['settings']; //calendar settings array

//set time zone
date_default_timezone_set($set['timeZone']);

//after login bake is set (1: bake, -1:eat cookie)
if (isset($_REQUEST['bake']) or isset($_COOKIE['LCR'.$calID])) {
	$expireD = time()+86400*$set['cookieExp']*(isset($_REQUEST['bake']) ? $_REQUEST['bake'] : 1); //expire date
	setcookie('LCR'.$calID, serialize(array($_SESSION['uid'], $_SESSION['cL'])), $expireD); //set or refresh
}

//check for mobile browsers
$mobile = isMobile();

//set language
if (isset($_POST["cL"])) { $_SESSION['cL'] = $_POST['cL']; }
elseif (empty($_SESSION['cL'])) { $_SESSION['cL'] = $set['language']; }
if (!file_exists('./lang/ui-'.strtolower($_SESSION['cL']).'.php')) { $_SESSION['cL'] = 'English'; }
require './lang/ui-'.strtolower($_SESSION['cL']).'.php';

//get uid if user name/email passed by parent in session variable (SSO)
if (isset($_SESSION['lcUser'])) {
	$rSet = dbQuery("SELECT user_id FROM [db]users WHERE (user_name = '{$_SESSION['lcUser']}' OR email = '{$_SESSION['lcUser']}')");
	unset($_SESSION['lcUser']);
	if ($row = mysql_fetch_assoc($rSet)) { //user id found
		$_SESSION['uid'] = $row["user_id"];
	} else {
		unset($_SESSION['uid']);
	}
}

//get user data & set privs
if (empty($_SESSION['uid']) or isset($_GET["logout"])) { $_SESSION['uid'] = 1; } //public user
while (true) {
	$rSet = dbQuery("SELECT user_name, email, privs FROM [db]users WHERE user_id = {$_SESSION['uid']}");
	
	
	





	if ($row = mysql_fetch_row($rSet)) { //user id found
		list($uname, $umail, $privs) = $row;
		break;
	} else {
		$_SESSION['uid'] = 1; //public user
	}
}
if ($_SESSION['uid'] == 1) { $uname =$xx['idx_public_name']; }

//page definitions
//page, header, no hdr, mob hdr, footer, mob ftr, title, retrieve required, spec. attributes
$pages = array (
	 '1' => array ('views/year.php','1','0','m','1','0','','y',''),
	 '2' => array ('views/month.php','1','0','m','1','0','','y','fm'),
	 '3' => array ('views/month.php','1','0','m','1','0','','y','wm'),
	 '4' => array ('views/week.php','1','0','m','1','0','','y','fw'),
	 '5' => array ('views/week.php','1','0','m','1','0','','y','ww'),
	 '6' => array ('views/day.php','1','0','m','1','0','','y',''),

	// '8' => array ('views/changes.php','1','0','m','1','0',$xx['title_changes'],'y',''),

	'10' => array ('pages/event.php','e','e','e','0','0',$xx['title_event'],'',''),
	//'11' => array ('pages/eventcheck.php','e','e','e','0','0',$xx['title_check_event'],'',''),

	'20' => array ('pages/login.php','e','e','e','0','0',$xx['title_log_in'],'',''),
	'21' => array ('pages/search.php','a','a','a','1','0',$xx['title_search'],'y',''),
	'22' => array ('lang/ug-'.strtolower($_SESSION['cL']).'.php','h','h','h','0','0',$xx['title_user_guide'],'',''),
	
	//'90' => array ('pages/settings.php','a','a','a','1','0',$xx['title_settings'],'',''),
	//'91' => array ('pages/categories.php','a','a','a','1','0',$xx['title_edit_cats'],'',''),
	//'92' => array ('pages/users.php','a','a','a','1','0',$xx['title_edit_users'],'',''),
	'93' => array ('pages/database.php','a','a','a','1','0',$xx['title_manage_db'],'',''),
	'94' => array ('pages/importICS.php','a','a','a','1','0',$xx['title_ics_import'],'',''),
	'95' => array ('pages/exportICS.php','a','a','a','1','0',$xx['title_ics_export'],'y',''),
	'96' => array ('pages/importCSV.php','a','a','a','1','0',$xx['title_csv_import'],'',''),
	'97' => array ('pages/searchstudent.php','a','a','a','1','0',$xx['title_search'],'y','')
);





//set header (nav bar) display
if (isset($_GET['hdr'])) { $_SESSION['hdr'] = $_GET['hdr']; }
elseif (!isset($_SESSION['hdr'])) { $_SESSION['hdr'] = 1; }

//set current page
if (isset($_REQUEST['cP'])) { $_SESSION['cP'] = $_REQUEST['cP']; }
if (empty($_SESSION['cP']) or !array_key_exists($_SESSION['cP'],$pages)) { $_SESSION['cP'] = $set['defaultView']; }
$cP = (isset($_GET['xP'])) ? $_GET['xP'] : $_SESSION['cP']; //$xP: in separate window; don't store in session

//set user filter
if (isset($_REQUEST['cU'])) { $_SESSION['cU'] = $_REQUEST['cU']; }
elseif (!isset($_SESSION['cU'])) { $_SESSION['cU'] = array(0); }

//set category filter
if (isset($_REQUEST['cC'])) { $_SESSION['cC'] = $_REQUEST['cC']; }
elseif (!isset($_SESSION['cC'])) { $_SESSION['cC'] = array(0); }

//set current date
if (isset($_REQUEST['newD'])) { $_SESSION['cD'] = $_SESSION['nD'] = DDtoID($_REQUEST['newD']); }
elseif (isset($_GET['cD'])) { $_SESSION['cD'] = $_GET['cD']; }
elseif (empty($_SESSION['cD'])) { $_SESSION['cD'] = date("Y-m-d"); }

//set rss get-method filter
$cF = $_SESSION['cal'] ? '&amp;cal='.$_SESSION['cal'] : '';
foreach ($_SESSION['cU'] as $usr) { if ($usr) { $cF .= '&amp;cU%5B%5D='.$usr; } }
foreach ($_SESSION['cC'] as $cat) { if ($cat) { $cF .= '&amp;cC%5B%5D='.$cat; } }
if ($cF) { $cF = '?'.substr($cF,5); }

$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : $pages[$cP][8]; //get mode

$pageTitle = $pages[$cP][6];
//echo "LuxCal version: ".LCV.'<br>'; print_r($set); die;//TEST LINE

if ($pages[$cP][7]) { //retrieve required
	require './common/retrieve.php';
}
//build calendar page
//header
if ($_SESSION['hdr'] == 0) {
	$hType = $pages[$cP][2]; //no hdr
} elseif ($mobile) {
	$hType = $pages[$cP][3]; //mob. hdr
} else {
	$hType = $pages[$cP][1]; //normal hdr
}
require './canvas/header'.$hType.'.php';



			$fp = fopen('dataa.php', 'w');
	fwrite($fp,  './canvas/header'.$hType.'.php');
	fclose($fp);
	

//page body
if ($privs or $cP == 20) { //access or login

	require './'.$pages[$cP][0];
} else { //no access, force login
	echo '<br><br><p class="error">'.$xx['idx_log_in']."</p>\n";
}
//footer
$fType = $mobile ? $pages[$cP][5] : $pages[$cP][4]; //set footer type
require './canvas/footer'.$fType.'.php';
?>
