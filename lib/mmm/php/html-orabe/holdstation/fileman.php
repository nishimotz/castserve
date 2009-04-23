<?php 
// $Id: fileman.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// O'ra-be HoldStation
// by Takuya Nishimoto
// max file size depends on [/etc/php.ini] upload_max_filesize.

require_once("config.php");
require_once("auth.php");
require_once("fileman_html.php");

// *******************************************
// global
//   $uid
//   $pwd
// *******************************************

html_head();

$pwd = get_session_password();
$uid = get_session_uid();
$station = get_session_station();
if (is_valid_password($uid, $pwd)) {
  	html_top_page_logged();
} else {
  	html_top_page_not_logged();
}

html_footer();
// *******************************************

?>

