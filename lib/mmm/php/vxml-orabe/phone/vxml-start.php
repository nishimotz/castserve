<?php header('Content-type: text/plain'); mb_http_output('UTF-8'); 
error_reporting(E_ERROR); // 致命的なエラーのみ表示
echo '<?xml version="1.0" encoding="UTF-8" ?>' ."\n";

// $Id: vxml-start.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
// http://hil.t.u-tokyo.ac.jp/~nishi/ora-be-wiki/?uploader%B8%B6%B9%C6

require_once("config.php");
require_once("auth.php");
require_once("peak.php");
require_once("vxml-log.php");

do_main();

// *******************************************
function get_vxml_start_url()
{
  	return basename(__FILE__);
}
// *******************************************
function do_main()
{
	$nextdoc = get_session_value('nextdoc', 'greeting');
	$func = 'get_vxml_document_' . $nextdoc;
	$doc = $func();
	vxml_log($doc);
  	print $doc;
}
// *******************************************
function write_meta_file($local_meta_file, $str)
{
	$fp = @fopen($local_meta_file, "a");
	@flock($fp, LOCK_EX);
	@fputs($fp, $str);
	@flock($fp, LOCK_UN);
	@fclose($fp);
	@chmod($local_meta_file, 0666);
}
// *******************************************
function is_valid_box_num($box)
{
	global $filesdir_base;
	$d = $filesdir_base . $box . "/";
	if (is_dir($d) && is_writable($d)) {
		return true;
	}
	return false;
}
// *******************************************
function get_length_announce_file($sec)
{
	$f = "t1005";
	if ($sec < 6) {
		$f = "t1005";
	} else if ($sec < 11) {
		$f = "t1010";
	} else if ($sec < 21) {
		$f = "t1020";
	} else if ($sec < 31) {
		$f = "t1030";
	} else if ($sec < 41) {
		$f = "t1040";
	} else if ($sec < 51) {
		$f = "t1050";
	} else if ($sec < 61) {
		$f = "t1060";
	} else if ($sec < 71) {
		$f = "t1070";
	} else if ($sec < 81) {
		$f = "t1080";
	} else if ($sec < 91) {
		$f = "t1090";
	} else if ($sec < 101) {
		$f = "t1100";
	} else if ($sec < 111) {
		$f = "t1110";
	} else if ($sec < 121) {
		$f = "t1120";
	} else if ($sec < 131) {
		$f = "t1130";
	} else {
		$f = "t1140";
	}
	return "../wav/" . $f . ".wav";
}
// *******************************************
function get_vxml_document_greeting() {
	$self_url = get_vxml_start_url();
    $datetime = date("Ymd_His"); // 20050801_133035 (session start time)
	return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="greeting">
<var name="nextdoc" expr="'input_station'"/>
<var name="phonenum" expr="session.telephone.ani"/>
<var name="datetime" expr="'$datetime'"/>
<block>
 <submit next="$self_url"
  method="post"
  namelist="nextdoc phonenum datetime"/>
</block>
</form>
</vxml>
EOD
. "\n";
}
// *******************************************
function get_vxml_document_input_station() {
	$self_url = get_vxml_start_url();
    $phonenum = get_session_value('phonenum', '0');
    $datetime = get_session_value('datetime', '20060101_000000');
    $fetchaudio = FETCHAUDIO;
	return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="input_station">
<property name="inputmodes" value="dtmf" /> 
<var name="nextdoc" expr="'check_station'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<block>
 <audio src="../wav/m104.wav"/> <!-- お電話ありがとう -->
 <audio src="../wav/m105.wav"/> <!-- ５桁のアクセスナンバーをしっかり -->
</block>
<field name="station" type="digits?length=5">
 <!-- Enter the station number. -->
 <prompt bargein="true" timeout="30s">
 <audio src="../wav/wait.wav"/>
 </prompt>
 <catch event="nomatch noinput help">
  <assign name="station" expr="'xxxxx'"/>
  <submit next="$self_url"
   method="post"
   namelist="nextdoc phonenum datetime station"/>
 </catch>
</field>
<block>
 <submit next="$self_url"
  method="post"
  namelist="nextdoc phonenum datetime station"
  fetchaudio="$fetchaudio" />
</block>
</form>
</vxml>
EOD
. "\n";
}
// *******************************************
function get_vxml_document_check_station() {
	global $station_db;
	$self_url = get_vxml_start_url();
    $phonenum = get_session_value('phonenum', '0');
    $datetime = get_session_value('datetime', '20060101_000000');
    $station  = get_session_value('station', '00000');
	if (is_valid_box_num($station)) {
		if (array_key_exists($station, $station_db)) {
			// 番組を聞かせて「＃」が押されたら録音を行う
			$opening_file = $station_db[$station]['opening_file'];
			$prog_file = $station_db[$station]['prog_file'];
			// if ($station == "89000") 
		    // $opening_file = "../wav/m101.wav";
		    // $http_prog_file = "http://radiofly.to/hatsune/prog/asimul061002-phone.wav";
			return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="check_station">
<property name="inputmodes" value="dtmf" /> 
<var name="nextdoc"  expr="'record_start'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<var name="station"  expr="'$station'"/>
<var name="rectake"  expr="0"/>
<field name="cmd" type="digits?length=1">
 <prompt bargein="true" timeout="10s">
  number <say-as type="acronym">$station</say-as>.
  <audio src="$opening_file"/>
  <audio src="../wav/m102.wav"/> <!-- 録音をするときは＃を -->
  <audio src="$prog_file"/>
  <audio src="../wav/m102.wav"/> <!-- 録音をするときは＃を -->
 </prompt>
 <catch event="nomatch">
  <assign name="cmd" expr="'nomatch'"/>
 </catch>
 <catch event="noinput">
  <assign name="cmd" expr="'noinput'"/>
 </catch>
 <catch event="help">
  <assign name="cmd" expr="'help'"/>
 </catch>
</field>
<block>
 <audio src="../wav/ok.wav"/>   
 <submit next="$self_url" method="post"
  namelist="nextdoc phonenum datetime station rectake cmd"/>
</block>
</form>
</vxml>
EOD
. "\n";
		} else {
			// 番組を聞かせずに録音のみを行う
			return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="check_station">
<var name="nextdoc"  expr="'record_start'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<var name="station"  expr="'$station'"/>
<block>
 <prompt bargein="false">
 <audio src="../wav/ok.wav"/>
 number <say-as type="acronym">$station</say-as>.
 </prompt>
 <submit next="$self_url"
  method="post"
  namelist="nextdoc phonenum datetime station"/>
</block>
</form>
</vxml>
EOD
. "\n";
		}
	} else {
		
		return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="check_station">
<var name="nextdoc" expr="'input_station'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<block>
 <prompt bargein="false">
  <audio src="../wav/sin.wav"/>
 </prompt>
 <submit next="$self_url"
  method="post"
  namelist="nextdoc phonenum datetime"/>
</block>
</form>
</vxml>
EOD
. "\n";

	}
}

// *******************************************
function get_vxml_document_record_start() {
	$self_url = get_vxml_start_url();
    $phonenum = get_session_value('phonenum', '0');
    $datetime = get_session_value('datetime', '20060101_000000');
    $station  = get_session_value('station', '00000');
    $rectake  = get_session_value('rectake', '0') + 1;
    $cmd      = get_session_value('cmd', '#');
    if ($cmd != "noinput") {
 		return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="record_start">
<var name="nextdoc"  expr="'record_end'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<var name="station"  expr="'$station'"/>
<var name="rectake"  expr="'$rectake'"/>
<block>
 <prompt bargein="false">
 <audio src="../wav/m106.wav"/> <!-- これからあなたの -->
 <audio src="../wav/m107.wav"/> <!-- 喋り終わったら＃を -->
 take $rectake
 <audio src="../wav/m108.wav"/> <!-- では、録音します。3,2,1 -->
 </prompt>
</block>
<record name="msg" 
 type="audio/x-wav" beep="true" dtmfterm="true"
 maxtime="140s" finalsilence="10s" >
</record>
<block>
 <prompt bargein="false">
 <audio src="../wav/sin.wav"/>
 </prompt>
</block>
<block>
 <submit next="$self_url"
  method="post"
  namelist="nextdoc phonenum datetime station msg rectake"/>
</block>
</form>
</vxml>
EOD
. "\n";
    } else {
		return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="f">
<block>
  <audio src="../wav/m017.wav"/> <!-- お疲れ様でした -->
  <disconnect/>
</block>
</form>
</vxml>
EOD
. "\n";
    }
}
// *******************************************
function get_vxml_document_record_end() {
	$self_url = get_vxml_start_url();
    $phonenum = get_session_value('phonenum', '0');
    $datetime = get_session_value('datetime', '20060101_000000');
    $station  = get_session_value('station', '00000');
    $rectake  = get_session_value('rectake', '1');

	$tmpfile = $_FILES['msg']['tmp_name'];
	$srcname = $_FILES['msg']['name'];

	// phonenum をマスクする
    $len = strlen($phonenum);
    if ($len < 7) {
    	$tel = $phonenum;
    } else {
    	$tel = substr($phonenum, 0, $len - 7) . "xxx" . substr($phonenum, $len-4,4);	
    }

	$wavefile = sprintf("%s_001_%s_%s_test%s.wav",  
		$station, $datetime, $tel, $rectake);
	$metafile = sprintf("%s_001_%s_%s_test%s.meta", 
		$station, $datetime, $tel, $rectake);
	
    $filesdir = get_filesdir_by_station($station);
	$destfile = $filesdir . $wavefile;
	$local_meta_file = $filesdir . $metafile;
	
	$is_error = false;	
	if (move_uploaded_file($tmpfile, $destfile) == FALSE) {
		$is_error = true;	
  		$status = "upload error. number is " . $_FILES['msg']['error'];
		// write meta file (error)
		$str = <<< EOD
<station>$station</station>
<datetime>$datetime</datetime>
<phonenum>$tel</phonenum>
<take>$rectake</take>
<status>$status</status>
EOD
. "\n";
		write_meta_file($local_meta_file, $str);
		
	} else {
  		chmod($destfile, 0666);
  		
		// do mmmpeak
		$mmmpeak = do_mmmpeak($destfile);
		
		// get values
		$result = null;
		if (ereg("<mediaTime>([^<]+)</mediaTime>", $mmmpeak, $result)) {
			$media_time = $result[1];
		}
		if (ereg("<clipCount>([^<]+)</clipCount>", $mmmpeak, $result)) {
			$clip_count = $result[1];
		}
		
		// write meta file		
		$str = <<< EOD
<station>$station</station>
<datetime>$datetime</datetime>
<phonenum>$tel</phonenum>
<take>$rectake</take>
<wavefile>$wavefile</wavefile>
$mmmpeak
EOD
. "\n";
		write_meta_file($local_meta_file, $str);
		
		// copy _testX to _fin
		$wave_fin = sprintf("%s_001_%s_%s_fin.wav",  
			$station, $datetime, $tel);
		copy($filesdir . $wavefile, $filesdir . $wave_fin);
  		chmod($filesdir . $wave_fin, 0666);
	}
	
	if ($is_error == true) {
		return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="record_end">
<block>
 <prompt bargein="false">
 <audio src="../wav/sin.wav"/>
 $status
 </prompt>
</block>
</form>
</vxml>
EOD
. "\n";
	}
	
	$http_filesdir = get_httpfilesdir_by_station($station);
	$http_wave_file = $http_filesdir . $wavefile;

	// yaku xxx fun xxx byou deshita
	$length_wave_file = get_length_announce_file($media_time);
	
	// クリッピングは１回は許容する
	if ($clip_count > 1) {
		$vxml_info = <<< EOD
  <audio src="../wav/m018.wav"/> <!-- 声が大きすぎて音が割れています -->
  <audio src="../wav/m033.wav"/> <!-- 033 受話器に口を近づけすぎないで -->
EOD;
	} else {
		$vxml_info = <<< EOD
  <audio src="../wav/m017.wav"/> <!-- お疲れさまでした -->
EOD;
	}
	return <<< EOD
<vxml version="2.0" xmlns="http://www.w3.org/2001/vxml">
<form id="record_end">
<property name="inputmodes" value="dtmf" /> 
<var name="nextdoc"  expr="'record_start'"/>
<var name="phonenum" expr="'$phonenum'"/>
<var name="datetime" expr="'$datetime'"/>
<var name="station"  expr="'$station'"/>
<var name="rectake"  expr="'$rectake'"/>
<field name="cmd" type="digits?length=1">
 <prompt bargein="true" timeout="10s">
  <audio src="../wav/ok.wav"/>   <!-- ok -->
  <audio src="../wav/m016.wav"/> <!-- あなたの録音が登録されました -->
  <audio src="../wav/m010.wav"/> <!-- 再生します -->
  <audio src="../wav/sin.wav"/>  
  <audio src="$http_wave_file"/>
  <audio src="../wav/sin.wav"/>  
  <audio src="../wav/t1002.wav"/> <!-- 録音時間は -->
  <audio src="$length_wave_file"/> <!-- 約？？？でした -->
  $vxml_info
  <audio src="../wav/m109.wav"/> <!-- 取り直すときは＃を。終了するときは受話器を -->
  <audio src="../wav/wait.wav"/>
 </prompt>
 <catch event="nomatch">
  <assign name="cmd" expr="'nomatch'"/>
 </catch>
 <catch event="noinput">
  <assign name="cmd" expr="'noinput'"/>
 </catch>
 <catch event="help">
  <assign name="cmd" expr="'help'"/>
 </catch>
</field>
<block>
 <audio src="../wav/ok.wav"/>   
 <submit next="$self_url" method="post"
  namelist="nextdoc phonenum datetime station rectake cmd"/>
</block>
</form>
</vxml>
EOD
. "\n";
}

// *******************************************
?>

