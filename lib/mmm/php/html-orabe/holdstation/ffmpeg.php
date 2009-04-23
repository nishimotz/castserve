<?php
/*
 * $Id: ffmpeg.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */

require_once("auth.php");

// *******************************************
// wav を mp3 に変換する
function ffmpeg_wav_to_mp3($srcfile, $mp3file, $tmpfile)
{
	$sox_bin = get_mmm_bin_dir() . "sox";
	$ffmpeg_bin = get_mmm_bin_dir() . "ffmpeg";
	
	$sox_exec = "$sox_bin $srcfile -r 44100 $tmpfile";
	$ret  = "[$sox_exec]\n";
	$ret .= shell_exec($sox_exec);
   	@chmod($tmpfile, 0666);
	
	$ffmpeg_exec = "$ffmpeg_bin -y -i $tmpfile $mp3file";
	$ret .= "[$ffmpeg_exec]\n";
	$ret .= shell_exec($ffmpeg_exec);
   	@chmod($mp3file, 0666);
   	
   	return $ret;
}

// *******************************************
// mp3 を wav に変換する
function ffmpeg_mp3_to_wav($srcfile, $mp3file)
{
	$ffmpeg_bin = get_mmm_bin_dir() . "ffmpeg";
	$ffmpeg_exec = "$ffmpeg_bin -y -i $srcfile $mp3file";
	$ret .= "[$ffmpeg_exec]\n";
	$ret .= shell_exec($ffmpeg_exec);
   	@chmod($mp3file, 0666);
   	return $ret;
}

// *******************************************
?>
