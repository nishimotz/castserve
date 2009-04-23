<?php
/*
 * memo_lib.php
 * $Id: memo_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */  
require_once("sticker_lib.php");

// *******************************************
// usage: list($author, $title, $category) = get_default_memo($station, $file);
function get_default_memo($station, $file) {
	if (is_sticker_box($station)) {
  		$category = "sticker";
  		$author = "ステッカー[" . $station . "]";
  		$title = get_sticker_title($station, file);
		return array($author, $title, $category);
  	} else {
		$category = "message";
	  	if (endsWith($file, ".deleted")) {
	  		$file = str_replace(".deleted", "", $file);
	  	}
	  	if (endsWith($file, ".wav")) {
	    	$body = str_replace(".wav", "", $file);
	    	list($st,$uid,$date,$time,$phonenum,$take) = split ("_", $body, 6);
	    	$monthday = substr($date, 4, 2) . "/" . substr($date, 6, 2); 
	    	$minsec = substr($time, 0, 2) . ":" . substr($time, 2, 2); 

		    $len = strlen($phonenum);
		    if ($len < 7) {
		    	$tel = "$phonenum";
		    } else {
		    	$tel = substr($phonenum, 0, $len - 7) . "xxx" . substr($phonenum, $len-4,4);	
		    }
			// $tel = $phonenum;
			
			// rss_lib と author / title が逆
	    	$author = "電話$tel";
	  		$title = "$monthday $minsec [$station]";
	  	} else if (endsWith($file, ".mp3")) {
	    	$body = str_replace(".mp3", "", $file);
	    	list($st,$uid,$date,$time,$phonenum,$take) = split ("_", $body, 6);
	    	$monthday = substr($date, 4, 2) . "/" . substr($date, 6, 2); 
	    	$minsec = substr($time, 0, 2) . ":" . substr($time, 2, 2); 
			// rss_lib と author / title が逆
	    	$author = "MP3素材";
	  		$title = "$monthday $minsec [$station]";
	  	}
  	}
	return array($author, $title, $category);
}


// *******************************************
// usage: list($author, $title, $category) = load_memo($station, $file);
function load_memo($station, $file)
{
	// . の前後で分割
	list($file_body, $file_ext) = split('\.', $file, 2);
	$filesdir = get_filesdir_by_station($station);
	$memo_fullpath = $filesdir . $file_body . ".memo";
	
	list($author, $title, $category) = array('', '', 'message');
	if (is_sticker_box($station)) {
		list($author, $title, $category) = get_default_memo($station, $file);
	}
	
	if (file_exists($memo_fullpath) == TRUE) {
		$fp = fopen($memo_fullpath, "r");
		if (!$fp) {
		 	return array($author, $title, $category);
		}
		while (!feof($fp)) {
			$str .= fread($fp, 10000);
		}
		fclose($fp);
		
		// $matches[0]はパターン全体にマッチしたテキスト
		// $matches[1]は最初の括弧付きのサブパターンにマッチしたテキスト
		$matches = Array();

		// <title>hoge</title>		
		if (preg_match("/\<title\>([^\<]*)\<\/title\>/", $str, $matches) > 0) {
			$title = $matches[1];
		}
		
		// <author>hoge</author>		
		if (preg_match("/\<author\>([^\<]*)\<\/author\>/", $str, $matches) > 0) {
			$author = $matches[1];
		}
		
		// <category>hoge</category>		
		if (preg_match("/\<category\>([^\<]*)\<\/category\>/", $str, $matches) > 0) {
			$category = $matches[1];
		}
	}
	// TODO 
    if ($category == "") {
    	$category = "message";
    }
    if ($category == "message-color-0") {
    	$category = "message";
    }
	return array($author, $title, $category);
}
// *******************************************
function save_memo($station, $file, $author, $title, $category)
{
	list($file_body, $file_ext) = split('\.', $file, 2);
	$filesdir = get_filesdir_by_station($station);
	$memo_fullpath = $filesdir . $file_body . ".memo";
	$fp = @fopen($memo_fullpath, "w");
	@flock($fp, LOCK_EX);
	@fputs($fp, "<title>$title</title>\n");
	@fputs($fp, "<author>$author</author>\n");
	@fputs($fp, "<category>$category</category>\n");
	@flock($fp, LOCK_UN);
	@fclose($fp);
    @chmod($memo_fullpath, 0666);
}
// *******************************************
?>
