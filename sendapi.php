<?php
/*include('library/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Tashkent');
$db->sql("SET NAMES 'utf8'");
*/
function send($id){
    echo $id;
}


/*
		$sql = "SELECT * FROM `$id` WHERE status=1 and progress=0";
		$db->sql($sql);
		$res = $db->getResult();
file_put_contents("salom.txt", "$_GET");

		foreach($res as $row){
			$id = $row['id'];
			$sms = $row['sms'];
			$nomer = substr("abcdef", 6);
			$kod = substr("abcdef", 4,2);

			$tezlik = $row['status'];	






 $resulte = file_get_contents("http://sms.uzmobile.uz");
  $re = explode("askod", $resulte);
  $ref = explode("value=", $re[2]);
  $df = explode("\"", $ref[1]);
$rasmkod  = $df[1];

$ko = file_get_contents("http://www.nitrxgen.net/md5db/$rasmkod");
//$der = post($chat_id,$text,$rasmkod);

 $url = 'http://sms.uzmobile.uz/index.php';
    $leng = 141 - strlen($sms);
$params = array(
    'act' => 3,
    'Prefix' => "998{$kod}",
    'Subscriber' => "{$nomer}",
    'gmess' => "$text",
    'gip' => "111.111.111.111",
    'Count' => "1",
    'askod1' => $ko,
    'askod' => $rasmkod,
    'translit' => "",
    'mlength' => $leng,
    'gsend' => "%D0%9E%D1%82%D0%BF%D1%80%D0%B0%D0%B2%D0%B8%D1%82%D1%8C",
);
$result = file_get_contents($url, false, stream_context_create(array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => http_build_query($params),
        'timeout'=>2
    )
)));

$der = $result;
if(strpos($der, "Спасибо!")){//Сообщение доставлено до СМС центра.//Отправить СМС
$sql1 = "UPDATE '$te' SET `status` = '0', progress ='1' WHERE `id` = '$id'";
		$db->sql($sql1);
}
else if(strpos($der, "В процессе отправки СМС возникли ошибки!")){//ОШИБКА: Не верный код!
$sql3 = "UPDATE '$te' SET `status` = '0', progress ='-1' WHERE `id` = '$id'";
		$db->sql($sql3);
}else{
$sql2 = "UPDATE '$te' SET `status` = '0', progress ='2' WHERE `id` = '$id'";
		$db->sql($sql2);
}
		}
	}*/

?>