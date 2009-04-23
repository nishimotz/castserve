<?php
/*
 * $Id: filelist.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
// *******************************************
function get_files($filesdir) {
	$arr = array();
  	if (is_dir($filesdir)) {
    	if ($dh = opendir($filesdir)) {
      		while (($file = readdir($dh)) !== false) {
        		$ftype = filetype($filesdir . $file);
        		if ($ftype == "file") {
		        	if ( endsWith($file, "_fin.wav")
		        	  || endsWith($file, "_fin.mp3") ) {
	        			$arr[] = $file;
		        	}
        		}
      		}
    	}
    	closedir($dh);
  	}
  	return $arr;
}
// *******************************************
function get_keep_files($filesdir) {
	$arr = array();
  	if (is_dir($filesdir)) {
    	if ($dh = opendir($filesdir)) {
      		while (($file = readdir($dh)) !== false) {
        		$ftype = filetype($filesdir . $file);
        		if ($ftype == "file") {
		        	if ( endsWith($file, ".deleted") ) {
	        			$arr[] = $file;
		        	}
        		}
      		}
    	}
    	closedir($dh);
  	}
  	return $arr;
}
// *******************************************
function get_all_files($filesdir) {
	$arr = array();
  	if (is_dir($filesdir)) {
    	if ($dh = opendir($filesdir)) {
      		while (($file = readdir($dh)) !== false) {
        		$ftype = filetype($filesdir . $file);
        		if ($ftype == "file") {
        			$arr[] = $file;
        		}
      		}
    	}
    	closedir($dh);
  	}
  	return $arr;
}
// *******************************************
?>
