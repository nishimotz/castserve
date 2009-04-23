<?php header('Content-type: text/plain'); mb_http_output('UTF-8'); 
echo '<?xml version="1.0" encoding="UTF-8" ?>' ."\n" ?>
<?php 
// $Id: vxml-later.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
?>
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="later">
<block>
 <prompt bargein="false">
 <audio src="../wav/ok.wav"/>   <!-- ok -->
 <audio src="../wav/m028.wav"/> <!-- tadaima maintain -->
 <audio src="../wav/m029.wav"/> <!-- moushiwake arimasenga nochihodo -->
 <break/>
 </prompt>
</block>
</form>
</vxml>


