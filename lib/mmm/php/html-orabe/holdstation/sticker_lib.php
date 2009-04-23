<?php
/*
 * $Id: sticker_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
require_once("config-sticker.php");

// *******************************************
// usage: $is_sticker = is_sticker_box($station);
function is_sticker_box($station)
{
	global $sticker_titles_db;
	if (in_array($station, $sticker_titles_db)) {
		return true;
	}
	return false;
}

// *******************************************
// usage: $title = get_sticker_title($station, $file);
function get_sticker_title($station, $file) {
	global $sticker_titles_db;
	return $sticker_titles_db[$station][$file];
}

// *******************************************
?>
