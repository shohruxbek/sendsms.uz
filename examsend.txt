<?php
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
//fetch_data.php

if($_POST["access_token"]){



    $id = $_POST["access_token"];
$connect = new PDO("mysql:host=localhost;dbname=u1257703_sms", "u1257703_sms", "vY3tW9xL1mbZ2i");
$time = time();
include('../library/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Tashkent');
$db->sql("SET NAMES 'utf8'");


$sql = "SELECT `status` FROM `sender` WHERE `sender`.`name` = '$id' LIMIT 1";
        $db->sql($sql);
        $res = $db->getResult();
        foreach($res as $row){
$status = $row["status"];
        }


if($status=="1"){
$sql1 = "UPDATE `sender` SET `status` = '0' WHERE `sender`.`name` = '$id'";
        $db->sql($sql1);



echo json_encode(["ok"=>"true"]);
 aed($id);



}else{
echo json_encode(["ok"=>"false"]);
}
}



function aed($id){


global $db;

 $sql11 = "SELECT * FROM `sms` WHERE `status`='1' and `progress`='0'";
    $db->sql($sql11);
    $res11 = $db->getResult();
  $er =  count($res11);


if($er%100==0){
    $sql11 = "UPDATE `sender` SET `status` = '1' WHERE `sender`.`name` = '$id'";
        $db->sql($sql11);
}

if($er>0){

$sql = "SELECT * FROM `".$id."` WHERE `status`='1' and `progress`='0' LIMIT 1";

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


    $leng = 141 - strlen($sms);
$params = array(
    'act' => 3,
    'Prefix' => "998".$kod,
    'Subscriber' => $nomer,
    'gmess' => $sms,
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
        'timeout'=>"$tezlik"
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



aed($id);

}
}


?>