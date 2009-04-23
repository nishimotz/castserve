<?php
/*
 * $Id: peak.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */

require_once("auth.php");

// *******************************************

function do_mmmpeak($wavfile)
{
	$bin_dir = get_mmm_bin_dir();
	$exec = $bin_dir . "sox $wavfile -t sw - | " . $bin_dir . "mmmpeak";
	return trim(shell_exec($exec));
}

// *******************************************
?>
