<?php
// $Id: html-modify.php,v 1.1 2009/03/30 07:02:05 nishi Exp $ 

require_once "memo_lib.php";
require_once "str_lib.php";

// *******************************************
function handle_action_modify()
{
	echo "<p>変更しました：<a href=\"./\">[リストに戻る]</a></p>";
	
  	$station = get_session_station();
	$filesdir = get_filesdir_by_station($station);

	$str = get_http_raw_post_data();	
	$post_arr = split("&", $str);
	
	$save_arr = array();
	
 	foreach ($post_arr as $idx => $item) {
 		list($name, $raw) = split("=", $item, 2);
 		$value = urldecode($raw);
		// echo "<p>name=$name, raw=$raw value=$value</p>\n";
 		if (endsWith($name, ".author") && $value != '') {
 			$file = str_replace(".author", "", $name);
 			if (!isset($save_arr[$file])) {
				$save_arr[$file] = array();
 			}
			$save_arr[$file]['author'] = $value;
 		}
 		if (endsWith($name, ".title") && $value != '') {
 			$file = str_replace(".title", "", $name);
 			if (!isset($save_arr[$file])) {
				$save_arr[$file] = array();
 			}
			$save_arr[$file]['title'] = $value;
 		}
 		if (endsWith($name, ".category") && $value != '') {
 			$file = str_replace(".category", "", $name);
 			if (!isset($save_arr[$file])) {
				$save_arr[$file] = array();
 			}
			$save_arr[$file]['category'] = $value;
 		}
 		if (endsWith($name, ".station_org") && $value != '') {
 			$file = str_replace(".station_org", "", $name);
 			if (!isset($save_arr[$file])) {
				$save_arr[$file] = array();
 			}
			$save_arr[$file]['station_org'] = $value;
 		}
 		if (endsWith($name, ".station_new") && $value != '') {
 			$file = str_replace(".station_new", "", $name);
 			if (!isset($save_arr[$file])) {
				$save_arr[$file] = array();
 			}
			$save_arr[$file]['station_new'] = $value;
 		}
 	}

	//	echo "<p>" . print_r($save_arr) . "</p>\n";

	// memo 保存処理
 	foreach ($save_arr as $key => $value) {
 		$author = $value['author'];
 		$title = $value['title'];
 		$category = $value['category'];
 		$file = str_replace(".deleted", "", $key);
 		if ($author != '') {
 			// echo "<p>file=$file, author=$author title=$title category=$category</p>\n";
	 		save_memo($station, $file, $author, $title, $category);
 		}
 	}
	
	// keep 処理
	if (isset($_POST["keepitems"])) {
	 	$items = $_POST["keepitems"];
	 	foreach ($items as $idx => $file) {
			// 		echo "<p> $idx => $file </p>";
	  		$fullpath = $filesdir . $file;
	  		$rcd = file_exists($fullpath);
	  		if ($rcd == TRUE) {
				$newname = $fullpath . ".deleted";
				$rcd = rename($fullpath, $newname);
	    		if ($rcd == TRUE) {
	      			echo "<p> $file : キープしました </p>";
	    		} else {
	      			echo "<p> $file : ERROR </p>";
	    		}
	  		} else {
	      		echo "<p> $file : ERROR </p>";
	  		}
	 	}
	}

	// unkeep 処理
	if (isset($_POST["unkeepitems"])) {
	 	$items = $_POST["unkeepitems"];
	 	foreach ($items as $idx => $file) {
			// 		echo "<p> $idx => $file </p>";
	  		$fullpath = $filesdir . $file;
	  		$rcd = file_exists($fullpath);
	  		if ($rcd == TRUE) {
	    		$newname = str_replace(".deleted", "", $fullpath);
				$rcd = rename($fullpath, $newname);
	    		if ($rcd == TRUE) {
	      			echo "<p> $file : OA素材に戻しました </p>";
	    		} else {
	      			echo "<p> $file : ERROR </p>";
	    		}
	  		} else {
	      		echo "<p> $file : ERROR </p>";
	  		}
	 	}
	}

	// station 移動処理
 	foreach ($save_arr as $key => $value) {
 		$station_org = $value['station_org'];
 		$station_new = $value['station_new'];
 		if ($station_org != $station_new) {
	 		$file = $key;
 			$ret = move_item_files($file, $station_org, $station_new);
 			if ($ret == true) {
 				echo "<p>$file : $station_org から $station_new に移動しました</p>\n";
 			} else {
 				echo "<p>[ERROR] $file : $station_org から $station_new 移動失敗</p>\n";
 			}
 		}
 	}

}

// *******************************************
// return true : success
function move_item_files($file, $station_org, $station_new)
{
	$filesdir_base = get_filesdir_base();
	$org_dir = $filesdir_base . $station_org;
	$new_dir = $filesdir_base . $station_new;
	//
	$prefix = str_replace(".deleted", "", $file);
	$prefix = str_replace(".wav", "", $prefix);
	$prefix = str_replace(".mp3", "", $prefix);
	$prefix = str_replace("_fin", "", $prefix);
	//
	//echo "<p>org_dir : $org_dir</p>";
	//echo "<p>prefix : $prefix</p>";
	// 
	$success = true;
  	if (is_dir($org_dir)) {
    	if ($dh = opendir($org_dir)) {
      		while (($f = readdir($dh)) !== false) {
	        	$org_fullpath = $org_dir . "/" . $f;
        		$ftype = filetype($org_fullpath);
        		if ($ftype == "file") {
		        	if (startsWith($f, $prefix)) {
	        			$arr[] = $f;
	        			if (startsWith($f, $station_org)) {
	        				// 新しいファイル名を作成する
	        				$station_len = strpos($f, '_');
	        				$newname = $station_new . substr($f, $station_len);
	        				$new_fullpath = $new_dir . "/" . $newname;
		        			if (file_exists($new_fullpath)) {
		        				echo "<p>[error] $new_fullpath exists.</p>";
		        				$success = false;
		        			} else {
								@rename($org_fullpath, $new_fullpath);	        				
		        				echo "<p>moved: $f to $newname </p>";
		        			}
	        			}
		        	} else {
	        			// echo "<p>unmatch $f</p>";
		        	}
        		}
      		}
    	}
    	closedir($dh);
  	}
	return $success;
}

?>
