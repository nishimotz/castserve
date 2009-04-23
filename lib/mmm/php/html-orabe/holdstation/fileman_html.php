<?php 
// $Id: fileman_html.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// Mmm file manager 
// by Takuya Nishimoto

// *******************************************

require_once("str_lib.php");
require_once("fileman_upload.php");
require_once("filelist.php");
require_once("auth.php");
require_once("html-filelist.php");
require_once("html-modify.php");
require_once("html-detail.php");
require_once("html-admin.php");

// *******************************************
function html_head()
{
 	global $title, $top_page_h1;
 	print <<< EOD
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../css/pc.css" />
<title>$title</title>
</head>
<body>
<h1>$top_page_h1</h1>
EOD;
}

// *******************************************

function html_footer()
{
 	global $top_page_footer_address;
 	print <<< EOD
<hr />
<address>$top_page_footer_address</address>
</body>
</html>
EOD;
}

// *******************************************

function html_top_page_not_logged()
{
	print <<< EOD
<form method="post" enctype="multipart/form-data" action="./">
<table>
<tr>
 <td>Station Number : </td>
 <td><input type="text" name="station"> </td>
</tr>
<tr>
 <td>User Number : </td>
 <td><input type="text" name="uid"> </td>
</tr>
<tr>
 <td>User Pass : </td>
 <td><input type="password" name="pwd"> </td>
</tr>
<tr>
 <td> <input type="submit" value="login"> </td>
 <td></td>
</tr>
</table>
</form>
EOD;
}

// *******************************************
function html_top_page_logged()
{
	global $jnlp_jar_href;  
	global $jnlp_href;
	global $mixed_station_db;
	
	$uid = get_session_uid();
	$station = get_session_station();
	$screen_station = get_screen_station($station);
	$screen_name = get_screen_name($uid);

//	if (array_key_exists($station, $mixed_station_db)) {
//		$special = <<< EOD
// <form method="post" enctype="multipart/form-data" action="$jnlp_href">
// <input type="hidden" name="station" value="$station">
// <input type="hidden" name="uid" value="$uid">
// <input type="hidden" name="rssmode" value="1">
// <input type="hidden" name="logging" value="1">
// <input type="hidden" name="jar_href" value="$jnlp_jar_href">
// <input type="submit" value="CastStudio (Mixed Mode)">
// </form>
//EOD;
//	}
	  
	print <<< EOD
<table border="1" id="boxinfo">
<tr>
<td>User</td>
<td>$uid</td>
<td>$screen_name </td>
<td>
 <form method="post" enctype="multipart/form-data" action="./">
 <input type="hidden" name="uid" value="__logout__">
 <input type="hidden" name="pwd" value="">
 <input type="submit" value="Logout (Change User)">
 </form>
</td>
<td>
 $special
</td>
</tr>

<tr>
<td>Station </td>
<td>$station</td>
<td> $screen_station </td>
<td>
 <form method="post" enctype="multipart/form-data" action="$jnlp_href">
 <input type="hidden" name="station" value="$station">
 <input type="hidden" name="uid" value="$uid">
<!--
 <input type="hidden" name="rssmode" value="0">
 -->
 <input type="hidden" name="logging" value="0">
 <input type="hidden" name="jar_href" value="$jnlp_jar_href">
 <input type="submit" value="CastStudio ($station)">
 </form>
</td>
<td>
 <form method="post" enctype="multipart/form-data" action="./">
 Station : <input type="text" name="station">
 <input type="submit" value="GO">
 </form>
</td>
</tr>
</table>

EOD;

	// change action
	print <<< EOD
<br />
[<a href="./?a=file_list">オンエア用素材を表示</a>]
[<a href="./?a=keep_list">キープ素材を表示</a>]
[<a href="./?a=uploader_form">アップロードする</a>]
-
[<a href="./?a=admin_list">システム管理</a>] 
EOD;

  	// handle action
  	switch (get_session_action()) {
	case 'upload':
  		handle_action_upload();
		break;
	case 'modify': // keep, unkeep, author, title, station_new
  		handle_action_modify();
		break;
	case 'keep_list':
  		html_keep_list();
		break;
	case 'admin_list':
  		html_admin_list();
		break;
	case 'uploader_form':
		html_uploader_form();
		break;
	case 'detail_form':
		html_detail_form();
		break;
	case 'file_list':
	default:
		html_file_list();
		break;		
  	}
  
  	//
  	print <<< EOD
<hr>
<ul>
 <li> メモ機能は「名前」を空欄にできません </li>
<!--
 <li> オラビー電話ボックス（東京）: 03-3556-0310 （大阪）: 06-6133-3889</li>
 <li> 緊急連絡先（にしもっつ携帯）：090-9346-6544 / nishi726@ezweb.ne.jp </li>
 <li> <a href="http://radiofly.to/mmm/setup/mmm-win-setup-051223.zip">
      CastStudio Setup Files for Windows
      </a> </li>
-->
</ul>
EOD;

}

// *******************************************

function html_uploader_form()
{
  	print <<< EOD
<h2>ファイルのアップロード（2メガバイトまで）</h2>

<form method="post" enctype="multipart/form-data" action="./">
<input type="hidden" name="a" value="upload">
ファイル名 : <input type="file" name="upfile"> 

<input type="submit" value="アップロードする">
</form>

EOD;
}

// *******************************************

?>
