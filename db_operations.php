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
if(isset($_POST['question']) and isset($_POST['add_question'])){
	

$df = "CREATE TABLE `nomer1` ( `id` INT NOT NULL AUTO_INCREMENT , `nomer` VARCHAR(50) NOT NULL , `status` INT NOT NULL DEFAULT '0' , `progress` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB";
$tr = sql($df);
	$res = $tr->getResult();
echo '<label class="alert alert-success">Question created successfully!</label>';

exit();

	$question = $db->escapeString($_POST['question']);
	$category = $db->escapeString($_POST['category']);
	$subcategory = $db->escapeString($_POST['subcategory']);
	$a = $db->escapeString($_POST['a']);
	$b = $db->escapeString($_POST['b']);
	$c = $db->escapeString($_POST['c']);
	$d = $db->escapeString($_POST['d']);
	$level = $db->escapeString($_POST['level']);
	$answer = $db->escapeString($_POST['answer']);
	$note = $db->escapeString($_POST['note']);
	
	$filename = $full_path = '';
	// common image file extensions
	if($_FILES['image']['error'] == 0 && $_FILES['image']['size'] > 0){
		if (!is_dir('images/questions')) {
			mkdir('images/questions', 0777, true);
		}
		
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		// $extension = explode(".", $_FILES["image"]["name"]);
		// $extension = end($extension);
		$extension = pathinfo($_FILES["image"]["name"])['extension'];
		if(!(in_array($extension, $allowedExts))){
			$response['error']=true;
			$response['message']='Image type is invalid';
			echo json_encode($response);
			return false;
		}
		$target_path = 'images/questions/';
		$filename = microtime(true).'.'. strtolower($extension);
		$full_path = $target_path."".$filename;
		if(!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)){
			$response['error']=true;
			$response['message']='Image type is invalid';
			echo json_encode($response);
			return false;
		}
	}
	



	$sql = "INSERT INTO `question`(`category`, `subcategory`, `image`, `question`, `optiona`, `optionb`, `optionc`, `optiond`, `level`, `answer`, `note`) VALUES 
	('".$category."','".$subcategory."','".$filename."','".$question."','".$a."','".$b."','".$c."','".$d."','".$level."','".$answer."','".$note."')";
	
	$db->sql($sql);
	$res = $db->getResult();
	echo '<label class="alert alert-success">Question created successfully!</label>';
}



if(isset($_GET['delete_question']) && $_GET['delete_question'] != '' ) {
    $id		= $_GET['id'];
    $image 	= $_GET['image'];
	
$et = $image.".php";


$etq = "./sendsms/".$image.".php";

			unlink($et);
			unlink($etq);



$servername = "localhost";
$username = "u1257703_sms";
$password = "vY3tW9xL1mbZ2i";
$dbname = "u1257703_sms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
 
}

$conn->query("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");


    $sql = 'DELETE FROM `sender` WHERE `name`='.$image;
    $sql1 = 'DROP TABLE '.$image;

    $conn->query($sql1);
	$conn->query($sql);

		echo 1;
}


?>