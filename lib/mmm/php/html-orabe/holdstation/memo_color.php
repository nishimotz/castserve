<?php
/*
 * $Id: memo_color.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */  
require_once("auth.php");

function get_memo_info($station, $file, $uid)
{
	$filesdir = get_filesdir_by_station($station);
	$user_fullpath = $filesdir . $file . "." . $uid . ".txt";
	if (file_exists($user_fullpath) == TRUE) {
		$fp = fopen($user_fullpath, "r");
		if (!$fp) {
		 	return "";
		}
		while (!feof($fp)) {
			$str .= fread($fp, 10000);
		}
		fclose($fp);
	}
	return $str;
}


function get_memo_color($station, $file, $uid)
{
	$color = 0;
	$filesdir = get_filesdir_by_station($station);
	$user_fullpath = $filesdir . $file . "." . $uid . ".txt";
	if (file_exists($user_fullpath) == TRUE) {
		$fp = fopen($user_fullpath, "r");
		if (!$fp) {
		 	return 0;
		}
		while (!feof($fp)) {
			$str .= fread($fp, 10000);
		}
		fclose($fp);
		
		// $matches[0]はパターン全体にマッチしたテキスト
		// $matches[1]は最初の括弧付きのサブパターンにマッチしたテキスト
		$matches = Array();

		// <color>hoge</color>		
		if (preg_match("/\<color\>([^\<]*)\<\/color\>/", $str, $matches) > 0) {
			$color = $matches[1];
		}
	}
	return $color;
}


function set_memo_info($station, $file, $uid, 
	$color, $t1, $t2, $px, $py, $zo, $fe, $cs, $ga)
{
	$filesdir = get_filesdir_by_station($station);
	$user_fullpath = $filesdir . $file . "." . $uid . ".txt";
	$fp = @fopen($user_fullpath, "w");
	@flock($fp, LOCK_EX);
	@fputs($fp, "<color>$color</color>\n");
	@fputs($fp, "<mediaStartTime>$t1</mediaStartTime>\n");
	@fputs($fp, "<mediaStopTime>$t2</mediaStopTime>\n");
	@fputs($fp, "<posX>$px</posX>\n");
	@fputs($fp, "<posY>$py</posY>\n");
	@fputs($fp, "<zOrder>$zo</zOrder>\n");
	@fputs($fp, "<fetched>$fe</fetched>\n");
	@fputs($fp, "<container>$co</container>\n");
	@fputs($fp, "<gain>$ga</gain>\n");
	@flock($fp, LOCK_UN);
	@fclose($fp);
    @chmod($user_fullpath, 0666);
    return $user_fullpath;
}

?>
