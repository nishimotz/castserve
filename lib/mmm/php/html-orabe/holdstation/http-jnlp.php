<?php 
/*
 * $Id: http-jnlp.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */
header('Content-type: application/x-java-jnlp-file'); 
mb_http_output('UTF-8');
echo '<?xml version="1.0" encoding="UTF-8" ?>' ."\n";
require_once("config.php");
require_once("auth.php");

$station  = get_session_value('station', '77701');
$uid      = get_session_value('uid', '101');
//$rssmode  = get_session_value('rssmode', '0');
$logging  = get_session_value('logging', '0');
$jar_href = get_session_value('jar_href', $jnlp_jar_href);
 
print <<< EOD
<jnlp spec="1.0+"
 codebase="$jnlp_codebase"
 href="$jnlp_href">
 <information>
  <title>$jnlp_title</title>
  <vendor>$jnlp_vendor</vendor>
 </information>
 <resources>
  <j2se version="1.5+"
   initial-heap-size="$jnlp_heap_size"
   max-heap-size="$jnlp_heap_size" />
  <jar href="$jar_href" main="true" download="eager" />
 </resources>
 <application-desc main-class="com.nishimotz.mmm.CastStudio">
      <argument>-g</argument> <argument>$logging</argument>
      <argument>-s</argument> <argument>$station</argument>
      <argument>-u</argument> <argument>$uid</argument>
      <argument>-r</argument> <argument>$jnlp_rss</argument>
<!--
      <argument>-m</argument> <argument>$rssmode</argument>
-->
 </application-desc>
 <security>
  <all-permissions/>
 </security>
</jnlp>
EOD;
?>
