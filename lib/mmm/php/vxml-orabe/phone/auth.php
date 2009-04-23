<?php 
// $Id: auth.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// HoldStation by Takuya Nishimoto
// auth.php
// *******************************************
function get_session_value($name, $default) 
{
  	$value = $default;
  	if(isset($_REQUEST[$name]) && $_REQUEST[$name] != '') { 
    	$value = $_REQUEST[$name];
    	$_SESSION[$name] = $value;
  	} else if(isset($_SESSION[$name]) && $_SESSION[$name] != '') { 
    	$value = $_SESSION[$name];
  	}
  	return $value;
}
// *******************************************
function get_session_action()
{
	$action = '';
  	if (isset($_REQUEST['a']) && $_REQUEST['a'] != '') { 
  		$action = $_REQUEST['a'];
  	}
  	return $action;
}
// *******************************************
function get_session_station() 
{
 	$station = "000";
  	if(isset($_REQUEST['station']) && $_REQUEST['station'] != '') { 
    	$station = $_REQUEST['station'];
    	$_SESSION['station'] = $station;
  	} else if(isset($_SESSION['station']) && $_SESSION['station'] != '') { 
    	$station = $_SESSION['station'];
  	}
  	return $station;
}
// *******************************************
function get_session_password() 
{
  	$pwd = "";
  	if(isset($_REQUEST['pwd']) && $_REQUEST['pwd'] != '') { 
    	$pwd = $_REQUEST['pwd'];
    	$_SESSION['pwd'] = $pwd;
  	} else if(isset($_SESSION['pwd']) && $_SESSION['pwd'] != '') { 
    	$pwd = $_SESSION['pwd'];
  	} else {
    	$pwd = "";
  	}
  	return $pwd;
}
// *******************************************
function get_session_uid() 
{
  	$uid = "";
  	if(isset($_REQUEST['uid']) && $_REQUEST['uid'] != '') { 
    	$uid = $_REQUEST['uid'];
    	$_SESSION['uid'] = $uid;
  	} else if(isset($_SESSION['uid']) && $_SESSION['uid'] != '') { 
    	$uid = $_SESSION['uid'];
  	} else {
    	$uid = "";
  	}
  	if ($uid == "__logout__") {
    	$uid = "";  
    	$_SESSION['uid'] = "";
    	$_SESSION['pwd'] = "";
  	}
  	return $uid;
}
// *******************************************
function is_valid_password($uid, $pwd)
{
	global $passcode_db;
	$the_passcode = $passcode_db[$uid];
	if ($uid != "" && $pwd == $the_passcode) {
		return TRUE;
	} 
	return FALSE;
}
// *******************************************
function get_screen_name($uid)
{
	global $scrnname_db;
	return $scrnname_db[$uid];
}
// *******************************************
function get_screen_station($station)
{
	global $scrnstation_db;
	return $scrnstation_db[$station];
}
// *******************************************
function get_caststudio_version() 
{
	global $caststudio_version;
	return $caststudio_version;
}
// *******************************************
function get_caststudio_test_version() 
{
	global $caststudio_test_version;
	return $caststudio_test_version;
}
// *******************************************
function get_filesdir_base()
{
	global $filesdir_base;
	return $filesdir_base;
}
// *******************************************
function get_mmm_bin_dir()
{
	global $mmm_bin_dir;
	return $mmm_bin_dir;
}
// *******************************************
function get_httpfilesdir_by_station($station)
{
	global $httpfilesdir_base;
	$d = $httpfilesdir_base . $station . "/";
	return $d;
}
// *******************************************
function get_filesdir_by_station($station)
{
	global $filesdir_base;
	$d = $filesdir_base . $station . "/";
	return $d;
}
// *******************************************
function get_log_dir_base()
{
	global $log_dir_base;
	return $log_dir_base;
}
// *******************************************
function get_http_raw_post_data() 
{
	$str = "";
	$fp = fopen("php://input", "r");
	if (!$fp) {
	 	return "";
	}
	while (!feof($fp)) {
		$str .= fread($fp, 10000);
	}
	fclose($fp);
	return $str;
}
// *******************************************
?>
