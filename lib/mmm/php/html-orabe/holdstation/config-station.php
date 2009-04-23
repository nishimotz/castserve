<?php 
/*
 * $Id: config-station.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */

// station
$scrnstation_db = array(
 '00000'  => "Default Box",
 '00100'  => "sound effect(local pc file)",
 '11100'  => "DEMO (nishimotz)",
 '11111'  => "試験放送 (nishimotz)",
 '22222'  => "試験放送 (kawasaki)",
 '77700'  => "FM CHAPPY (Special)",
 '77701'  => "FM CHAPPY (金) 居留守放送局",
 '77702'  => "居留守放送局へのご意見",
 '77711'  => "FM CHAPPY (土) ウィークエンドライフ",
 '77735'  => "FM CHAPPY (金) 気ままにティータイム",
 '77755'  => "FM CHAPPY (火木金) アフタヌーンスクエア (美和さなえ)",
 '77761'  => "FM CHAPPY (土) サタデーヒット・ザ・ランキング", 
 '77777'  => "FM CHAPPY (日) 週刊ブログ",
 '88801'  => "居留守放送局（芸人さん）",
 '88802'  => "居留守放送局 ボイスレコーダ素材",
 '88803'  => "居留守放送局 ステッカー素材"
// '99999' => "DAIPRO"
 );

// see rss_lib.php
$mixed_station_db = array(
	"11100" => array("00100", "11100"),
	"11111" => array("11111", "88803"),
	"22222" => array("22222", "88803"),
	"77701" => array("00000", "77701", "88801", "88802", "88803")
);

?>
