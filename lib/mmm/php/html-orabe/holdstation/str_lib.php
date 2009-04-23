<?php 
// $Id: str_lib.php,v 1.1 2009/03/30 07:02:05 nishi Exp $

// returns true if $str begins with $sub
function beginsWith( $str, $sub ) {
   return ( substr( $str, 0, strlen( $sub ) ) == $sub );
}

function startsWith( $str, $sub ) {
   return ( substr( $str, 0, strlen( $sub ) ) == $sub );
}

// return true if $str ends with $sub
function endsWith( $str, $sub ) {
   return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );
}

?>
