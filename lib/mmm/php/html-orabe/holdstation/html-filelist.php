<?php 
// $Id: html-filelist.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// Mmm file manager 
// by Takuya Nishimoto

// *******************************************

require_once("str_lib.php");
require_once("memo_lib.php");
require_once("fileman_upload.php");
require_once("filelist.php");
require_once("html-player.php");

// *******************************************
function get_html_file_item($station, $filedir, $httpdir, $file, $use_player, $is_keeplist)
{
  	$fullpath = $filedir . $file;
  	$httppath = $httpdir . $file;
  	$fsize = filesize($fullpath);
  	
	if ($use_player) {
		if (endsWith($file, ".mp3") || endsWith($file, ".wav")) {
			$player = get_html_windows_player($httppath);
		} else if (endsWith($file, ".mp3.deleted") || endsWith($file, ".wav.deleted")) {
		    $player = get_html_windows_player($httppath);
		} else {
			$player = "";
		}
	} else {
		$player = "";
	}

    list($st,$uid,$date,$time,$phonenum,$take) = split ("_", $file, 6);
	$monthday = substr($date, 4, 2) . "/" . substr($date, 6, 2); 
	$minsec = substr($time, 0, 2) . ":" . substr($time, 2, 2); 
    $caption1 = "$monthday $minsec";
    
    // 01234567890    01234567890 [pos]
    // 09012345678 -> 0901xxx5678 len=11 pos=7
    // 0312345678  -> 031xxx5678  len=10 pos=6
    $len = strlen($phonenum);
    if ($len < 7) {
    	$caption2 = "($phonenum)";
    } else {
    	$caption2 = "(" . substr($phonenum, 0, $len - 7) . "xxx" . substr($phonenum, $len-4,4) . ")";	
    }
    
    list($author, $title, $category) = load_memo($station, $file);
	// if ($category == "") {
	//     $category = "message";
	// }
    if ($category == "sticker") {
    	$caption1 = $file;
    	$caption2 = "";
    }
    
    if ($is_keeplist == true) {
    	$checkname = "unkeepitems[]";
    } else {
    	$checkname = "keepitems[]";
    }
    
  	return <<< EOD
<tr>
 <td> $caption1 <small>$caption2</small></td>
 <td> $player </td>
 <td> <a href="$httppath">$fsize</a> </td>
 <td> <input type="checkbox" name="$checkname" value="$file" /> </td>
 <td> <input type="text" name="$file.author" value="$author" /> </td>
 <td> <input type="text" name="$file.title" value="$title" /> </td>
 <td>
      <small><a href="./?a=detail_form&s=$station&f=$file">$category</a></small>
      <input type="hidden" name="$file.category" value="$category" /> 
      <!-- for move -->
      <!--
      <input type="hidden" name="$file.station_org" value="$station" />
      <small>移動先番号:</small> 
      <input type="text" name="$file.station_new" value="" />
      -->
 </td>
</tr>
EOD;
}

// *******************************************

function html_file_list()
{
  	$station = get_session_station();
	$filesdir = get_filesdir_by_station($station);
	$httpfilesdir = get_httpfilesdir_by_station($station);
	
	$str = <<< EOD
<h2>ファイルリスト</h2>
<form method="POST" action="./">
<input type="hidden" name="station" value="$station">
<table border="border">
<tr>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td> <input type="submit" name="a" value="modify" /> </td>
</tr>
<tr>
 <td>日時</td>
 <td>再生</td>
 <td>サイズ</td>
 <td>キープ</td>
 <td>名前</td>
 <td>内容</td>
 <td>分類</td>
</tr>
EOD;

	$arr = array_reverse(get_files($filesdir));
	foreach ($arr as $file) {
	    $str .= get_html_file_item($station, $filesdir, $httpfilesdir, $file, true, false);
	}

  	$str .= <<< EOD
</table>
</form>
EOD;

  	print $str;
}

// *******************************************
function html_keep_list()
{
  	$station = get_session_station();
	$filesdir = get_filesdir_by_station($station);
	$httpfilesdir = get_httpfilesdir_by_station($station);
	  
	$str = <<< EOD
<h2>キープリスト</h2>
<form method="POST" action="./">
<input type="hidden" name="station" value="$station">
<table border="border">
<tr>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td></td>
 <td> <input type="submit" name="a" value="modify" /> </td>
</tr>
<tr>
 <td>日時</td>
 <td>再生</td>
 <td>サイズ</td>
 <td>キープ解除</td>
 <td>名前</td>
 <td>内容</td>
 <td>分類</td>
</tr>
EOD;

	$arr = array_reverse(get_keep_files($filesdir));
	foreach ($arr as $file) {
	    $str .= get_html_file_item($station, $filesdir, $httpfilesdir, $file, true, true);
	}

  	$str .= <<< EOD
</table>
</form>
EOD;

  	print $str;
}

// *******************************************

?>
