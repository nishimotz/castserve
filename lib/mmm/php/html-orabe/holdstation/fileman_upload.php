<?php 
// $Id: fileman_upload.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// by Takuya Nishimoto
// *******************************************

require_once("str_lib.php");

function handle_action_upload()
{
  	$uid = get_session_uid();
  	$station = get_session_station();

  	$filesdir = get_filesdir_by_station($station);
  	$datetime = date("Ymd_His"); // 20050801_133035

  	$orgname = $_FILES['upfile']['name'];
  	$orgname = strtolower($orgname);
  	
  	$ext = ".dat";
  	if (endsWith($orgname, ".mp3")) {
  		$ext = ".mp3";
  	} else if (endsWith($orgname, ".wav")) {
  		$ext = ".wav";
  	}

  	$filename = $station . "_" . $uid . "_" . $datetime . "_x_fin". $ext;

  	$destfile = $filesdir . $filename;
  	if (move_uploaded_file($_FILES['upfile']['tmp_name'], $destfile) == FALSE) {
    	html_upload_failed($_FILES['upfile']['error']);
  	} else {
    	chmod($destfile, 0666);
    	html_upload_ok($filename);
  	}
}

// *******************************************

function html_upload_failed($error)
{
 	switch ($error) {
 	case 0: // UPLOAD_ERR_OK:
  		$errmsg = "UPLOAD_ERR_OK";
  		break;
 	case 1: // UPLOAD_ERR_INI_SIZE:
  		$errmsg = "UPLOAD_ERR_INI_SIZE";
  		break;
 	case 2: // UPLOAD_ERR_FORM_SIZE:
  		$errmsg = "UPLOAD_ERR_FORM_SIZE";
  		break;
 	case 3: // UPLOAD_ERR_PARTIAL:
  		$errmsg = "UPLOAD_ERR_PARTIAL";
  		break;
 	case 4: // UPLOAD_ERR_NO_FILE:
  		$errmsg = "UPLOAD_ERR_NO_FILE";
  		break;
 	default:
  		$errmsg = "UPLOAD_UNKNOWN";
	}

	print <<< EOD
<h2>アップロード失敗</h2>
<p>
アップロードできませんでした。エラーコード：<b> $errmsg </b>
<a href="./">[素材リストに戻る]</a>
</p>
EOD;
}

// *******************************************

function html_upload_ok($filename)
{
	print <<< EOD
<h2>アップロード完了</h2>
<p>
<b> $filename </b> をアップロードしました。
<a href="./">[素材リストに戻る]</a>
</p>
EOD;
}

// *******************************************

?>

