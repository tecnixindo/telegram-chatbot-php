<?php
include_once "functions.inc.php";

if (!stristr($abs_url,'telegram.org')) {
?>
<?php

if ($_POST['api_token'] != '') {
	write_file('db/api.txt', $_POST['api_token']);
	access_url("https://api.telegram.org/bot".$_POST['api_token']."/setWebhook?url=".urlencode('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));

?>
Seting berhasil. Silahkan kunjungi bot telegram dengan akun telegram anda
<?php
	$respon = access_url('https://api.telegram.org/bot'.$_POST['api_token'].'/sendMessage?chat_id=@FauzanGodean&text='.urlencode('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'])."&reply_to_message_id=0");

	die();
}

?>
<form id="form1" name="form1" method="post">
  <label for="api_token">API Token Telegram</label>
  <input name="api_token" type="text" id="api_token" size="22">
  <input type="submit" name="submit" id="submit" value="Simpan">
</form>
<?php

	die(); }

$masukan = file_get_contents('php://input');
$reply_to = in_string('"chat":{"id":',',',$masukan);
$obrolan = in_string('"text":"','"',$masukan);

//---log untuk testing

			$data[1] = date('d/m/Y H:i');
			$data[2] = $masukan;
			$data[3] = $reply_to;
			$data[4] = $obrolan;
			add_db('db/bot_'.date('Ymd').'.txt',$data);
//--- haspus log lama 
$log_lama = date('Ymd', strtotime('-20 days'));
if (file_exists('db/bot_'.$log_lama.'.txt')) {
 unlink('db/bot_'.$log_lama.'.txt');
}

//---anti loop
if (trim(cek_file('db/last_response.txt')) == trim($obrolan)) {die();}
//--- penanda anti loop
	write_file('db/last_response.txt',trim($obrolan)); usleep(1000);

// sekolah bot
if (preg_match('/^(?=.*\#)(?=.*\*)/i', $obrolan)) {
	//emoji
		if (stristr($obrolan,'\n')) {$obrolan = str_replace('\n','%0A',$obrolan);}		
		if (stristr($obrolan,'\u')) {$obrolan = json_decode('"'.$obrolan.'"');}

if (file_exists('db/setting.txt')) {
	$row_data = read_db('db/setting.txt',1,99);
	if ($row_data) {
		foreach ($row_data as $column_data => $value) {
			$setting[$value[1]] = $value;
		}
	}
}

$pesan = "Terima kasih sudah mengajari saya dengan respon baru :)";
$reply_to = $setting[pemilik][2];

	$respon = access_url('https://api.telegram.org/bot'.trim(cek_file('db/api.txt')).'/sendMessage?chat_id='.$reply_to.'&text='.urlencode($pesan)."&reply_to_message_id=0");



$jawaban = in_string('*','',$obrolan);
$kata_kunci = in_string('','*',$obrolan);
$kata_kunci = str_replace(' ','#',$kata_kunci);
$kata_kunci = str_replace(' ','#',$kata_kunci);
$kata_kunci = explode('#',$kata_kunci);

$data[1] = $kata_kunci[1];
$data[2] = $kata_kunci[2];
$data[3] = $kata_kunci[3];
$data[4] = $kata_kunci[4];
$data[5] = $kata_kunci[5];
$data[6] = $jawaban;
$data[7] = '';
add_db('db/chat.txt',$data);


//log untuk testing
			$data[1] = date('d/m/Y H:i');
			$data[2] = $masukan;
			$data[3] = $reply_to;
			$data[4] = $respon;
			add_db('db/bot_'.date('Ymd').'.txt',$data);

$row_chat = read_db('db/belum_belajar.txt',1,22);
foreach ($row_chat as $column_chat) {	
	if (preg_match('/^(?=.*'.$kata_kunci[1].')(?=.*'.$kata_kunci[2].')(?=.*'.$kata_kunci[3].')(?=.*'.$kata_kunci[4].')(?=.*'.$kata_kunci[5].')/i', $obrolan)) {
			$pesan = $jawaban;
			$respon = post_url("https://api.telegram.org/bot".trim(cek_file('db/api.txt'))."/sendMessage",'chat_id='.$column_chat[2].'&text='.$pesan.'&reply_to_message_id='.$column_chat[3].'&parse_mode=HTML');
	del_db('db/belum_belajar.txt',$column_chat[0]);
	break;	
	}
}

	die();
}

if (!file_exists('db/setting.txt')) {
	$data[1] = 'pemilik';	
	$data[2] = in_string('"from":{"id":',',',$masukan);		//id
	$data[3] = in_string('"username":"','"',$masukan);		//username
	$data[4] = in_string('"first_name":"','"',$masukan);	//nama awal
	$data[5] = in_string('"last_name":"','"',$masukan);		//nama akhir
	$data[6] = '';	//	
	replace_db('db/setting.txt',$data,$data[1]);
	
$pesan = "Saya adalah bot, baru pertama kali ini saya ketemu orang lain. Saya asumsikan anda adalah majikan saya.\n Salam kenal :)";
$reply_to = $data[2];
$respon = access_url('https://api.telegram.org/bot'.trim(cek_file('db/api.txt')).'/sendMessage?chat_id='.$reply_to.'&text='.urlencode($pesan)."&reply_to_message_id=0");

//log untuk testing
			$data[1] = date('d/m/Y H:i');
			$data[2] = $masukan;
			$data[3] = $reply_to;
			$data[4] = $respon;
			add_db('db/bot_'.date('Ymd').'.txt',$data);

	die();
}

//---chat otomatis
	$row_qa = read_db('db/chat.txt',1,9999);
	foreach ($row_qa as $column_qa) {
		if (preg_match('/^(?=.*'.$column_qa[1].')(?=.*'.$column_qa[2].')(?=.*'.$column_qa[3].')(?=.*'.$column_qa[4].')(?=.*'.$column_qa[5].')/i', $obrolan)) {
			$pesan = $column_qa[6];

			$respon = post_url("https://api.telegram.org/bot".trim(cek_file('db/api.txt'))."/sendMessage",'chat_id='.$reply_to.'&text='.$pesan.'&reply_to_message_id=0&parse_mode=HTML');
			
//---log untuk testing
			$data[1] = date('d/m/Y H:i');
			$data[2] = $masukan;
			$data[3] = $reply_to;
			$data[4] = $respon;
			add_db('db/bot_'.date('Ymd').'.txt',$data);


			die();
		}
	}
	
//---jika tak ada set respon
	if (file_exists('db/setting.txt')) {
		$row_data = read_db('db/setting.txt',1,99);
		if ($row_data) {
			foreach ($row_data as $column_data => $value) {
				$setting[$value[1]] = $value;
			}
		}
	}
//jika masukan berupa foto / gambar
/*
if ( stristr($masukan,'file_size') ) {
		$pesan = "Gambar apaan tuh.... ";
	
}
*/

// jika ada perintah / atau chat tak terjawab di luar grup
if ($reply_to != $setting[pemilik][2]) {	
//---emoji
		if (stristr($obrolan,'\n')) {$obrolan = str_replace('\n','%0A',$obrolan);}		
		if (stristr($obrolan,'\u')) {$obrolan = json_decode('"'.$obrolan.'"');}

	$from_id = $reply_to;
	$reply_to_message_id = in_string('"message_id":',',',$masukan);
	$pesan = $obrolan."\n\n<i>belum ada data respon untuk masukan di atas. Set respon dengan kode\n#kunci_1 #kunci_2 #kunci...*respon\nSpasi di depan dan/atau belakang kata kunci bisa mempengaruhi ketepatan respon, mohon perhatikan saat mengajari saya sesuatu yang baru ya. Terima kasih sebelumnya</i>:)";
	if (stristr($obrolan,'/start')) {
	$respon = post_url("https://api.telegram.org/bot".trim(cek_file('db/api.txt'))."/sendMessage",'chat_id='.$reply_to.'&text='.urlencode('Ada yang bisa kami bantu ?').'&parse_mode=HTML');
	$pesan = "@".in_string('"username":"','"',$masukan)." mencoba bot ini";	
	}
	$reply_to = $setting[pemilik][2];

// simpan obrolan yang belum di ajarkan
if (stristr($pesan,'belum ada data respon')) {
	$belum_belajar[1] = $obrolan;
	$belum_belajar[2] = $from_id;
	$belum_belajar[3] = $reply_to_message_id;
	$belum_belajar[4] = date('D, d/m/Y - H:i');
	$belum_belajar[5] = $_GET['q'];
	$belum_belajar[6] = '';
	add_db('db/belum_belajar.txt',$belum_belajar);	
}

	$respon = post_url("https://api.telegram.org/bot".trim(cek_file('db/api.txt'))."/sendMessage",'chat_id='.$reply_to.'&text='.$pesan.'&parse_mode=HTML');
	
//log untuk testing

				$data[1] = date('d/m/Y H:i');
				$data[2] = $masukan;
				$data[3] = $reply_to;
				$data[4] = $respon;
				add_db('db/bot_'.date('Ymd').'.txt',$data);

	die();
}

?>