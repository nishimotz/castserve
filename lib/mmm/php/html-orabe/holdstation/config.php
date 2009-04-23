<?php 
/*
 * $Id: config.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
$title       = "HoldStation (hatsune)";
$top_page_h1 = "HoldStation (hatsune-061105)";
$caststudio_jar = "caststudio-061105.jar";

$copyright_notice = "(c) O'ra-be Project";
$top_page_footer_address = $copyright_notice;

// last '/' is required!!
$filesdir_base     = "/Users/nishimoto/public_html/mmm/files/";
$log_dir_base      = "/Users/nishimoto/mmm_data/log/";
$mmm_bin_dir       = "/Users/nishimoto/bin/";
$tmp_dir           = "/Users/nishimoto/mmm_tmp/";

// make sure : chmod 777 files/777xx
$httpfilesdir_base = "http://radiofly.to/mmm/files/";

// for jnlp.php
$jnlp_jar_href     = "http://radiofly.to/hatsune/java/" . $caststudio_jar;
$jnlp_vendor       = "nishimotz";
$jnlp_codebase     = "http://radiofly.to/hatsune/html-orabe/holdstation/";
$jnlp_title        = "O'ra-be CastStudio";
$jnlp_loggingmode  = "0";
$jnlp_heap_size    = "64m";
$jnlp_href         = "http-jnlp.php";
// jnlp_rss must be fully-qualified URL
$jnlp_rss          = "http://radiofly.to/hatsune/html-orabe/holdstation/http-rss.php";

// for RSS
define("RSS_LINK", "http://radiofly.to/hatsune/html-orabe/holdstation/http-rpc.php");

// http://radiofly.to/mmm/files/00000/00000_001_20060815_005624_0_fin.wav
// http://radiofly.to/hatsune/html-orabe/holdstation/shape.php/00000/00000_001_20060815_005624_0_fin.wav
//define("WAV2SHAPE_S1", "http://radiofly.to/mmm/files");
//define("WAV2SHAPE_S2", "http://radiofly.to/hatsune/html-orabe/holdstation/http-shape.php");
 
?>
