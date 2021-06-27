<?php
session_start();
if(!isset($_SESSION['id']) && !isset($_SESSION['username'])){
	header("location:index.php");
	return false;
}
include('library/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Tashkent');
$db->sql("SET NAMES 'utf8'");


//8. add_question
$time = time();
	if($_POST){
		//var_dump($_POST);
		//echo $_POST['textareas'];
		//echo $_POST['numbers'];
		//file_put_contents("asd.txt",$_POST['speed']);
		//$li = $_POST['lineCount'];
		//echo $_POST['speed'];


$df = "CREATE TABLE `$time` (
  `id` int(11) NOT NULL,
  `sms` varchar(250) NOT NULL,
  `nomer` varchar(50) NOT NULL,
  `status` int(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `progress` int(11) NOT NULL,
  `timed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";


$db->sql($df);

$ry = "ALTER TABLE `$time`
  ADD PRIMARY KEY (`id`)";
   $db->sql($ry);
/*  $yi = "ALTER TABLE `$time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;";
  $db->sql($yi);*/
/*	$res = $db->getResult();

$je = "INSERT INTO `$time` (`id`, `nomer`, `status`, `progress`) VALUES
(1, '+998995948233', 1, 0),
(2, '+998995948234', 1, 0),
(3, '+998995948233', 1, 0),
(4, '+998995948234', 1, 0),
(5, '+998995948233', 1, 0),
(6, '+998995948234', 1, 0),
(7, '+998995948233', 1, 0),
(8, '+998995948234', 1, 0)";

var_dump($res);
*/
 $url = "ovito.uz";
    $host = "localhost";
    $user_d = "u1257703_sms";
    $password = "vY3tW9xL1mbZ2i";
    $db = "u1257703_sms";
    $con = mysqli_connect($host, $user_d, $password, $db);

/*foreach (array('textareas', 'numbers', 'lineCount','speed') as $pos) {
    foreach ($_POST[$pos] as $id => $row) {
        $_POST[$pos][$id] = mysqli_real_escape_string($con, $row);
    }
}*/

$ids = $_POST['textareas'];
$quantities = explode("\n",$_POST['numbers']);
$prices =  $_POST['lineCount'];
$spe =  $_POST['speed'];

$items = array();

$size = count($quantities);

for($i = 0 ; $i < $size ; $i++){
    // Check for part id
  /*  if (empty($ids) || empty($quantities[$i]) || empty($prices[$i])) {
        continue;
    }*/
    $items[] = array(
        "id"     => $i, 
        "sms"     => $ids, 
        "nomer"     => $quantities[$i], 
        "status"    => "1",
        "speed"       => $spe,
        "progress"       =>"0"
    );
}

if (!empty($items)) {
    $values = array();
    foreach($items as $item){

$tem = str_replace("'", "\'",$item['sms'] );

        $values[] = "('{$item['id']}','{$tem}', '{$item['nomer']}', '{$item['status']}', '{$item['speed']}', '{$item['progress']}')";
    }

    $values = implode(", ", $values);

    $sql = "INSERT INTO `$time` (`id`,`sms`, `nomer`,`status`,`speed`,`progress`) VALUES  {$values}";
//var_dump($sql);
//$tr = $db->sql($sql);
	//$result = $db->getResult();

$result = mysqli_query($con, $sql );


$res1 = file_get_contents("exam.txt"); 
$ri = str_replace("##", $time, $res1);
file_put_contents($time.".php",$ri);

$res13 = file_get_contents("examsend.txt"); 
file_put_contents("./sendsms/".$time.".php",$res13);

$sql2 = "INSERT INTO `sender` (`id`, `name`, `uzunlik`, `status`) VALUES (NULL, '$time', '$prices', '1')";
mysqli_query($con, $sql2 );

    if ($result) {
echo '<label class="alert alert-success">Ma\'lumotlar saqlandi!</label>';
    } else {
       
echo '<label class="alert alert-danger">Ma\'lumotlar saqlanmadi!</label>';
    }
}




	}

?>