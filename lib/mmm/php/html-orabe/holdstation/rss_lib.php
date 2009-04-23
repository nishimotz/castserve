<?php 
// $Id: rss_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $

require_once("config.php");
require_once("auth.php");
require_once("str_lib.php");
require_once("memo_lib.php");
require_once("memo_color.php");
require_once("filelist.php");

// *******************************************
function get_items_by_station($station, $uid)
{
	global $static_sound_db;
	$str = "";
	if (array_key_exists($station, $static_sound_db)) {
		foreach ($static_sound_db[$station] as $item) {
			$str .= get_rss_item_static($item, $uid);
		}
	} else {
	  	$filesdir = get_filesdir_by_station($station);
		$arr = get_files($filesdir);
		foreach ($arr as $file) {
			$str .= get_rss_item_irusu($station, $file, $uid);
		}
	}
	return $str;
}
// *******************************************
function make_rss_irusu($uid)
{
	global $mixed_station_db;
  	$station = get_session_station();
    $title = "番組素材($station)";
    
    $link = RSS_LINK;
    $description = "CastStudio";
    $ch_category = "CastStudio";
    $language = "ja";
    $ttl = "90";
    $pubdate = date("r"); // RFC822
  	$str = <<< EOD
<rss version="2.0">
<channel>
<title>$title</title>
<link>$link</link>
<description>$description</description>
<language>$language</language>
<pubDate>$pubdate</pubDate>
<category>$ch_category</category>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<ttl>$ttl</ttl>
EOD
. "\n";
	if (array_key_exists($station, $mixed_station_db)) {
		foreach ($mixed_station_db[$station] as $box) {
			$str .= get_items_by_station($box, $uid);
		}
	} else {
	  	$station = get_session_station();
	  	$filesdir = get_filesdir_by_station($station);
	  	$station_name = get_screen_station($station);
		$arr = get_files($filesdir);
		foreach ($arr as $file) {
			$str .= get_rss_item_irusu($station, $file, $uid);
		}
	}
  	$str .= <<< EOD
</channel>
</rss>
EOD
. "\n";

  	print $str;
}
// *******************************************
//function rss_file_list($shape, $uid)
//{
//  	$station = get_session_station();
//  	$filesdir = get_filesdir_by_station($station);
//  	$station_name = get_screen_station($station);
//  
//    $pubdate = date("r"); // RFC822
//    
//    $title = $station_name;
//    $link = RSS_LINK;
//    $description = "CastStudio";
//    $ch_category = "CastStudio";
//    $language = "ja";
//    $ttl = "90";
//  
//  	$str = <<< EOD
//<rss version="2.0">
//<channel>
//<title>$title</title>
//<link>$link</link>
//<description>$description</description>
//<language>$language</language>
//<pubDate>$pubdate</pubDate>
//<category>$ch_category</category>
//<docs>http://blogs.law.harvard.edu/tech/rss</docs>
//<ttl>$ttl</ttl>
//EOD
//. "\n";
//
//	$arr = get_files($filesdir);
//	foreach ($arr as $file) {
//		$str .= get_rss_item_irusu($station, $file, $shape, $uid);
//	}
//
//  	$str .= <<< EOD
//</channel>
//</rss>
//EOD
//. "\n";
//
//  	print $str;
//}

// *******************************************
function get_rss_item_irusu($station, $file, $uid)
{
  	$filesdir = get_filesdir_by_station($station);
  	$httpfilesdir = get_httpfilesdir_by_station($station);
  	$fullpath = $filesdir . $file;
  	$httppath = $httpfilesdir . $file;
//  	$http_shape_path = str_replace(WAV2SHAPE_S1, WAV2SHAPE_S2, $httppath);
  
 	list($author, $title, $category) = load_memo($station, $file); 
	list($author_d, $title_d, $category_d) = get_default_memo($station, $file);
	if ($author == '') { 
		$author = $author_d;
	}
	if ($title == '') { 
		$title = $title_d;
	}
	if ($category == '') { 
		$category = $category_d;
	}
  
  	$color = get_memo_color($station, $file, $uid); // 0-4
  	
  	if (endsWith($file, ".wav")) {
    	$type = "audio/x-wav";
  	} else if (endsWith($file, ".mp3")) {
    	$type = "audio/mpeg";
  	} else {
  		return "";
  	}

  	$link = RSS_LINK;
  	$desc = "";
  	$pubdate = date("r"); // RFC822
  	// $length = "0";

//<enclosure url="$httppath" length="0" type="application/x-mmm-info" />
//<enclosure url="$http_shape_path" length="0" type="application/x-mmm-shape" />
  	
  	$ret = <<< EOD
<item>
<title>$title</title>
<link>$link</link>
<description>$desc</description>
<pubDate>$pubdate</pubDate>
<author>$author</author>
<category>$category</category>
<enclosure url="$httppath" length="0" type="$type" />
</item>
EOD
. "\n";
	return $ret;
}
// *******************************************
function get_rss_item_static($item, $uid) {
	$file = $item["file"];
	$title = $item["title"];
	$color = $item["color"];
	$pubdate = date("r"); // RFC822
	$author = "local file";
	$category = "message"; // sound-effect
	$length = 0;
	$type = "unknown";
  	if (endsWith($file, ".wav")) {
    	$type = "audio/x-wav";
  	} else if (endsWith($file, ".mp3")) {
    	$type = "audio/mpeg";
  	}
  	$link = RSS_LINK;
  	$ret = <<< EOD
<item>
<title>$title</title>
<link>$link</link>
<description></description>
<pubDate>$pubdate</pubDate>
<author>$author</author>
<category>$category</category>
<enclosure url="$file" length="$length" type="$type" />
</item>
EOD
. "\n";
	return $ret;
}		
// *******************************************
	
?>
