<?php 
// $Id: html-detail.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
//
// see also: 
// function get_html_file_item()
// function html_top_page_logged()
//
require_once("str_lib.php");
require_once("memo_color.php");
// *******************************************
function get_html_media_info($station, $file)
{
	$filedir = get_filesdir_by_station($station);
	$httpdir = get_httpfilesdir_by_station($station);
  	$fullpath = $filedir . $file;
  	$httppath = $httpdir . $file;
	$fsize = 0;
	$html = "";
  	if (file_exists($fullpath)) {
	  	$fsize = filesize($fullpath);
  		$html = get_html_windows_player($httppath) . "<a href=\"$httppath\">($fsize)</a>";
  	}
  	return $html;
}
// *******************************************
function html_detail_form()
{
  	$station = get_session_station();
  	$file = get_session_file();
	$uid = get_session_uid();
		 
    $html_fin = get_html_media_info($station, $file);
	
	if (endsWith($file, "_fin.wav")) {
		$file_test1 = str_replace("_fin.wav", "_test1.wav", $file);
    	$html_test1 = get_html_media_info($station, $file_test1);
		$file_test2 = str_replace("_fin.wav", "_test2.wav", $file);
    	$html_test2 = get_html_media_info($station, $file_test2);
		$file_test3 = str_replace("_fin.wav", "_test3.wav", $file);
    	$html_test3 = get_html_media_info($station, $file_test3);
		$file_test4 = str_replace("_fin.wav", "_test4.wav", $file);
    	$html_test4 = get_html_media_info($station, $file_test4);
		$file_test5 = str_replace("_fin.wav", "_test5.wav", $file);
    	$html_test5 = get_html_media_info($station, $file_test5);
	} 
	if (endsWith($file, "_fin.wav.deleted")) {
		$file_test1 = str_replace("_fin.wav.deleted", "_test1.wav", $file);
    	$html_test1 = get_html_media_info($station, $file_test1);
		$file_test2 = str_replace("_fin.wav.deleted", "_test2.wav", $file);
    	$html_test2 = get_html_media_info($station, $file_test2);
		$file_test3 = str_replace("_fin.wav.deleted", "_test3.wav", $file);
    	$html_test3 = get_html_media_info($station, $file_test3);
		$file_test4 = str_replace("_fin.wav.deleted", "_test4.wav", $file);
    	$html_test4 = get_html_media_info($station, $file_test4);
		$file_test5 = str_replace("_fin.wav.deleted", "_test5.wav", $file);
    	$html_test5 = get_html_media_info($station, $file_test5);
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

    $sessionid = $uid . "_" . $date . "_" . $time;
    list($author, $title, $category) = load_memo($station, $file);
    if ($category == "sticker") {
    	$caption1 = $file;
    	$caption2 = "";
    }
    
	if (endsWith($file, ".deleted")) {
		$checkcaption = "キープ解除";
		$checkname = "unkeepitems[]";
    } else {
		$checkcaption = "キープ";
    	$checkname = "keepitems[]";
    }
    	
	$color = get_memo_color($station, $file, $uid);

  	$html = <<< EOD
<h2>詳細表示</h2>

<form method="POST" action="./">
<table border="1">
<tbody>
 <tr>  <td>修正を実行</td> <td><input type="submit" name="a" value="modify" /></td> </tr>
 <tr>  <td>アクセス番号</td> 
       <td>$station
       <input type="hidden" name="$file.station_org" value="$station" />
       </td> </tr>
 <tr>  <td>移動先番号</td> 
       <td> <input type="text" name="$file.station_new" value="$station" /> </td> </tr>
 <tr>  <td>セッション</td> <td>$sessionid</td> </tr>
 <tr>  <td>caption1</td> <td>$caption1</td> </tr>
 <tr>  <td>caption2</td> <td>$caption2</td> </tr>
 <tr>  <td>$checkcaption</td> 
       <td> <input type="checkbox" name="$checkname" value="$file" /> </td> </tr>
 <tr>  <td>名前</td> 
       <td> <input type="text" name="$file.author" value="$author" /> </td> </tr>
 <tr>  <td>内容</td>
       <td> <input type="text" name="$file.title" value="$title" /> </td> </tr>
 <tr>  <td>分類</td>
       <td> <small>$category</small>
            <input type="hidden" name="$file.category" value="$category" /> 
            </td> </tr>
 <tr>  <td>color</td>
       <td> <small>$color</small>
            <input type="hidden" name="$file.color" value="$color" /> 
            </td> </tr>
 <tr>  <td>fin</td> <td> $html_fin </td> </tr>
 <tr>  <td>test1</td> <td> $html_test1 </td> </tr>
 <tr>  <td>test2</td> <td> $html_test2 </td> </tr>
 <tr>  <td>test3</td> <td> $html_test3 </td> </tr>
 <tr>  <td>test4</td> <td> $html_test4 </td> </tr>
 <tr>  <td>test5</td> <td> $html_test5 </td> </tr>
</tbody>
</table>
</form>

EOD;
	print $html;
}
// *******************************************

?>

