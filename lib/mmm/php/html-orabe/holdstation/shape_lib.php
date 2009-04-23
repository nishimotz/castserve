<?php 
/*
 * $Id: shape_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 * ~/bin/ffmpeg -i hoge.mp3 hoge.wav
 * ~/bin/sox hoge.wav -r 8000 -c 1 -t sw - | ./mmmshape > hoge.shape
 */
require_once("auth.php");
// *******************************************

function wav_to_shape($srcfile, $shapefile)
{
	$sox_bin = get_mmm_bin_dir() . "sox";
	$mmmshape_bin = get_mmm_bin_dir() . "mmmshape";
	
	$exec = "$sox_bin $srcfile -r 8000 -c 1 -t sw - | $mmmshape_bin > $shapefile";
	$ret  = "[$exec]\n";
	$ret .= shell_exec($exec);
   	@chmod($shapefile, 0666);
	
   	return $ret;
}
// *******************************************
?>
