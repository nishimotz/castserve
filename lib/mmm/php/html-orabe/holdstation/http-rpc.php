<?php 
/*
 * $Id: http-rpc.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
header('Content-type: text/plain'); mb_http_output('UTF-8');

define("HTTP_BASE", "http://radiofly.to/mmm/files/");

require_once("session_lib.php");
require_once("memo_color.php");
require_once("config.php");
require_once("ffmpeg.php");
require_once("str_lib.php");
require_once("shape_lib.php");

$a = get_request_value("a", "");

switch ($a) {

case "save_info":
	$location = get_request_value("f", ""); 
	$uid = get_request_value("uid", "0");
	$color = get_request_value("c", "0"); 
	
	$t1 = get_request_value("t1", "0"); // mediaStartTime
	$t2 = get_request_value("t2", "-1"); // mediaStopTime
	$px = get_request_value("px", "0"); // posX
	$py = get_request_value("py", "0"); // posY
	$zo = get_request_value("zo", "0"); // z-order
	$fe = get_request_value("fe", "0"); // fetched
	$cs = get_request_value("cs", ""); // container sheet
	$ga = get_request_value("ga", "0"); // gain as DB
	
	$s = str_replace(HTTP_BASE, '', $location);
	list($station, $file) = split("/", $s, 2); 
	
	$ret = set_memo_info($station, $file, $uid, $color, 
		$t1, $t2, $px, $py, $zo, $fe, $cs, $ga);	
	echo "200 OK ($station,$file,$uid,$color,$t1,$t2,$px,$py,$zo,$fe,$cs,$ga)\n";
	break;

case "show_info":
	$location = get_request_value("f", ""); 
	$s = str_replace(HTTP_BASE, '', $location);
	list($station, $file) = split("/", $s, 2); 
//	$station = get_request_value("station", ""); 
//	$file = get_request_value("file", ""); 
	$uid = get_request_value("uid", "0");
	$ret = get_memo_info($station, $file, $uid);	
	echo "<mmmInfo>\n";
	echo $ret;
	echo "</mmmInfo>\n";
	break;

case "get_shape":
	$location = get_request_value("f", ""); 
	$src_name = str_replace(HTTP_BASE, $filesdir_base, $location);
	$reflesh = false;
	$ret1 = '';
	$ret2 = '';
	if (endsWith($src_name, ".mp3")) {
		$shape_name = str_replace(".mp3", ".shape", $src_name);
		if ($reflesh || !file_exists($shape_name)) {
			$tmp_wav_name = $tmp_dir . date("YmdHis") . "_" . mt_rand() . ".wav";
			$ret1 = ffmpeg_mp3_to_wav($src_name, $tmp_wav_name);
			$ret2 = wav_to_shape($tmp_wav_name, $shape_name);
			@unlink($tmp_wav_name);
		}
	} else if (endsWith($src_name, ".wav")) {
		$shape_name = str_replace(".wav", ".shape", $src_name);
		if ($reflesh || !file_exists($shape_name)) {
			$ret1 = wav_to_shape($src_name, $shape_name);
		}
	}
	$content_size = filesize($shape_name);
	header("Content-Length: $content_size"); 
	$fp = fopen($shape_name, "r") or die;
	while (!feof($fp)) {
		echo fread($fp, 10000);
	}
	fclose($fp);
	break;

default:
	echo "403 ERROR\n";
	break;
}

?>
