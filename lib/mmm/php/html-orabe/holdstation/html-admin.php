<?php 
// $Id: html-admin.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// Mmm file manager 
// by Takuya Nishimoto

// *******************************************

require_once("str_lib.php");
require_once("memo_lib.php");
require_once("fileman_upload.php");
require_once("filelist.php");
require_once("html-player.php");

// *******************************************
function get_html_all_file_item($filedir, $httpdir, $file)
{
  	$fullpath = $filedir . $file;
  	$httppath = $httpdir . $file;
  	$fsize = filesize($fullpath);
  	
	if (endsWith($file, ".mp3") || endsWith($file, ".wav")) {
	    $player = get_html_windows_player($httppath);
	} else if (endsWith($file, ".mp3.deleted") || endsWith($file, ".wav.deleted")) {
	    $player = get_html_windows_player($httppath);
	} else {
		$player = "";
	}

  	return <<< EOD
<tr>
 <td> $file</td>
 <td> $player </td>
 <td> <a href="$httppath">$fsize</a> </td>
</tr>
EOD;
}

// *******************************************
// TODO: action=all_file_list & function -> html_all_file_list
function html_admin_list()
{
  	$station = get_session_station();
	$filesdir = get_filesdir_by_station($station);
	$httpfilesdir = get_httpfilesdir_by_station($station);
	  
	$str = <<< EOD
<h2>debug_list</h2>
<form method="POST" action="./">
<input type="hidden" name="station" value="$station">
<table border="border">
<tr>
<td>filename</td>
<td>play</td>
<td>size</td>
</tr>
EOD;

	$arr = get_all_files($filesdir);
	foreach ($arr as $file) {
	    $str .= get_html_all_file_item($filesdir, $httpfilesdir, $file);
	}

  	$str .= <<< EOD
</table>
</form>
EOD;

  	print $str;
}

// *******************************************

?>
