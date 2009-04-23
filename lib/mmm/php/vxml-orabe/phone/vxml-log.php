<?php
/*
 * $Id: vxml-log.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
// *******************************************
function get_vxml_error_log_file()
{
  	return get_log_dir_base() . "mmmlog" . date("ymd") . ".txt";
}
// *******************************************
function vxml_log($message)
{
	$local_log_file = get_vxml_error_log_file();
	$fp = @fopen($local_log_file, "a");
	@flock($fp, LOCK_EX);
	@fputs($fp, "\n" . dirname(__FILE__) . "\n" . date("[Y-m-d H:i:s]\n"));
	@fputs($fp, $message);
	@flock($fp, LOCK_UN);
	@fclose($fp);
    @chmod($local_log_file, 0666);
}
// *******************************************
?>
