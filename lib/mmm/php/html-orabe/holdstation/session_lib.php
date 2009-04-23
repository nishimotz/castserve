<?php 
/*
 * $Id: session_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
function get_request_value($name, $default) 
{
  	$value = $default;
  	if(isset($_REQUEST[$name]) && $_REQUEST[$name] != '') { 
    	$value = $_REQUEST[$name];
  	}
  	return $value;
}
?>
