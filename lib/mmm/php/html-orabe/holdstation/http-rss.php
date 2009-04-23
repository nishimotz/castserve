<?php 
/*
 * $Id: http-rss.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
header('Content-type: text/plain'); mb_http_output('UTF-8');
require_once("config.php");
require_once("auth.php");
require_once("str_lib.php");
require_once("rss_lib.php");
require_once("session_lib.php");
//$shape = get_request_value("shape", "0"); 
$uid = get_request_value("uid", "101"); 
make_rss_irusu($uid);
//$rssmode = get_request_value("rssmode", "0"); 
//if ($rssmode == 1) {
//	make_rss_irusu($shape, $uid);
//} else {
//	rss_file_list($shape, $uid);
//}
?>
