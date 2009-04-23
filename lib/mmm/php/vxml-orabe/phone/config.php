<?php 
/*
 * $Id: config.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */

// last '/' is required!!
$filesdir_base     = "/Users/nishimoto/public_html/mmm/files/";
$log_dir_base      = "/Users/nishimoto/mmm_data/log/";
$mmm_bin_dir       = "/Users/nishimoto/bin/";
$tmp_dir           = "/Users/nishimoto/mmm_tmp/";

$httpfilesdir_base = "http://radiofly.to/mmm/files/";

define("FETCHAUDIO", "../wav/opening060828-mulaw.wav");

$station_db = array(
 "89000" => array(
	'opening_file' => "../wav/m101.wav",
	'prog_file' => "http://radiofly.to/hatsune/prog/asimul061022-phone.wav"
 )
);

?>
