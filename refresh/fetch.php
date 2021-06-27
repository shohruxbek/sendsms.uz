<?php

//fetch_data.php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    //header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');    
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
}   
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
} 

$id = $_GET["id"];
$connect = new PDO('mysql:host=localhost;dbname=u1257703_sms', "u1257703_sms", "vY3tW9xL1mbZ2i");
$time = time();

/*$query1 = "INSERT INTO `chat` (`chat_id`, `chat_content`) VALUES (NULL, '$time');";
 $statement1 = $connect->prepare($query1);
$statement1->execute();*/

include('../library/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Tashkent');
$db->sql("SET NAMES 'utf8'");

$sql = "SELECT * FROM `$id` WHERE status=1 and progress=0 LIMIT 1";
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){

			$ids = $row['id'];
			$sms = $row['sms'];
			$nomer = substr($row['nomer'], 6,7);
			$kod = substr($row['nomer'], 4,2);
			$tezlik = $row['speed'];	


 
 $resulte = file_get_contents("http://sms.uzmobile.uz");
  $re = explode("askod", $resulte);
  $ref = explode("value=", $re[2]);
  $df = explode("\"", $ref[1]);
$rasmkod  = $df[1];

$ko = file_get_contents("http://www.nitrxgen.net/md5db/$rasmkod");
//$der = post($chat_id,$text,$rasmkod);

 $url = 'http://sms.uzmobile.uz/index.php';
    $leng = 141 - strlen("$sms");

$params = array(
    'act' => 3,
    'Prefix' => "998".$kod,
    'Subscriber' => "$nomer",
    'gmess' => "$sms",
    'gip' => "111.111.111.111",
    'Count' => "2",
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
$sql1 = "UPDATE `".$id."` SET `status` = '0',`progress` = '1',`timed`='$time' WHERE `".$id."`.`id` = '$ids'";

		$db->sql($sql1);
}
else if(strpos($der, "В процессе отправки СМС возникли ошибки!")){//ОШИБКА: Не верный код!
$sql3 = "UPDATE `".$id."` SET `status` = '0',`progress` = '-1',`timed`='$time' WHERE `".$id."`.`id` = '$ids'";

		$db->sql($sql3);
}else{
$sql2 = "UPDATE `".$id."` SET `status` = '0',`progress` = '2',`timed`='$time' WHERE `".$id."`.`id` = '$ids'";

		$db->sql($sql2);
}
		}
	


$query = "SELECT * FROM `".$id."` WHERE `status`='0' ORDER by `id` DESC";

$statement = $connect->prepare($query);

if($statement->execute())
{
 while($row = $statement->fetch(PDO::FETCH_ASSOC))
 {
  $data[] = $row;
 }
 echo json_encode($data);
}

?>