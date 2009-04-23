<?php
/*
 * $Id: config-sticker.php,v 1.1 2009/03/30 07:02:05 nishi Exp $
 */

$sticker_titles_db = array(
	"88803" => array(
		"13-boku-orabe_fin.mp3" => "ぼくオラビー",
		"22-doumo_fin.mp3" => "どうも",
	  	"23-douzo_fin.mp3" => "どうぞ",
	  	"33-naruhodo_fin.mp3" => "なるほど！",
	  	"34-omigoto_fin.mp3" => "おみごと！",
	  	"36-yattane_fin.mp3" => "やったね！",
	  	"38-nabeyakan-no_fin.mp3" => "なべやかんの居留守"
	)
);

// array_kay_exists ならその box は static box となる
$static_sound_db = array(
 "00100" => array(
   array(
	"file" => "http://radiofly.to/mmm/files/00100/atama01.mp3",
	"title"	=> "頭01 髪型1 ロン毛 天パー",
	"color" => "white"
   ),
   array(
	"file" => "http://radiofly.to/mmm/files/00100/atama02.mp3",
	"title"	=> "頭02 mp3",
	"color" => "white"
   ),
   array(
	"file" => "http://radiofly.to/mmm/files/00100/atama03.mp3",
	"title"	=> "03",
	"color" => "white"
   ),
   array(
	"file" => "file:c:/work/bakudan/atama/atama04.mp3",
	"title"	=> "04 local",
	"color" => "white"
   ),
   array(
	"file" => "file:c:/work/bakudan/atama/atama05-128k.mp3",
	"title"	=> "05-128k local",
	"color" => "white"
   )
 )
);

?>
