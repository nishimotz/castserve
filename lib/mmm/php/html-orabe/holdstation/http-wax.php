<?php 
/*
 * $Id: http-wax.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
header('Content-type: audio/x-ms-wax'); mb_http_output('UTF-8');
require_once("session_lib.php");
$f = get_request_value("f", "");
?>
<ASX version = "3.0">
<Entry>
<Ref href="<?php echo $f ?>" />
</Entry>
</ASX>
