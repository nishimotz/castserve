<?php 
// $Id: html-player.php,v 1.1 2009/03/30 07:02:05 nishi Exp $

// *******************************************
function get_html_quicktime_player($httppath)
{
  	return <<< EOD
 <OBJECT
  WIDTH="150"
  HEIGHT="30"
  CLASSID="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B"
  CODEBASE="http://www.apple.com/qtactivex/qtplugin.cab"
  >
  <PARAM name="SRC" VALUE="$httppath">
  <PARAM name="AUTOHREF" VALUE="false">
  <PARAM name="AUTOPLAY" VALUE="false">
  <PARAM name="CONTROLLER" VALUE="true">
  <PARAM name="CACHE" VALUE="false">
  <param name="enablejavascript" value="true">
  <EMBED
   WIDTH="150"
   HEIGHT="30"
   PLUGINSPAGE="http://www.apple.co.jp/quicktime/download/"
   SRC="$httppath"
   AUTOHREF="false"
   AUTOPLAY="false"
   CONTROLLER="true"
   CACHE="false"
   type="video/quicktime" 
   enablejavascript="true" 
   >
  </EMBED>
 </OBJECT>
EOD;
}

// *******************************************

function get_html_windows_player($httppath)
{
  	return <<< EOD
<a href="#" onclick="window.open('http-wax.php?f=$httppath', 'p', 'width=1,height=1')" >
再生
</a>
EOD;
}


?>
