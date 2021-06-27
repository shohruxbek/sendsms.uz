<?php


 $resulte = file_get_contents("http://sms.uzmobile.uz");
  $re = explode("askod", $resulte);
  $ref = explode("value=", $re[2]);
  $df = explode("\"", $ref[1]);
$rasmkod  = $df[1];

$ko = file_get_contents("http://www.nitrxgen.net/md5db/$rasmkod");
//$der = post($chat_id,$text,$rasmkod);

 $url = 'http://sms.uzmobile.uz/index.php';
    $leng = 141 - strlen("salom");
$params = array(
    'act' => 3,
    'Prefix' => "99899",
    'Subscriber' => "5948233",
    'gmess' => "salom",
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
        'timeout'=>"30"
    )
)));
$time = time();
$der = $result;
if(strpos($der, "Спасибо!")){//Сообщение доставлено до СМС центра.//Отправить СМС
echo "sp";
}
else if(strpos($der, "В процессе отправки СМС возникли ошибки!")){//ОШИБКА: Не верный код!
echo "bp";
}else{
echo "ep";
}
		
	

	?>