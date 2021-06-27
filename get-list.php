<?php
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include('library/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
$db->sql("SET NAMES 'utf8'");

/*
	1. category
	2. subcategory
	3. question
	
	1. users
	2. tracker
	3. payment_requests
*/


	if(isset($_GET['table']) && $_GET['table'] == 'category'){
		$offset = 0;$limit = 10;
		$sort = 'row_order'; $order = 'ASC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		if(isset($_GET['order']))
			$order = $_GET['order'];
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `category_name` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `category` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `category` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$image = (!empty($row['image']))?'category/'.$row['image']:'';
			$operate = "<a class='btn btn-xs btn-primary edit-category' data-id='".$row['id']."' data-toggle='modal' data-target='#editCategoryModal' title='Edit'><i class='fas fa-edit'></i></a>";
			$operate .= "<a class='btn btn-xs btn-danger delete-category' data-id='".$row['id']."' data-image='".$image."' title='Delete'><i class='fas fa-trash'></i></a>";

			$tempRow['id'] = $row['id'];
			$tempRow['category_name'] = $row['category_name'];
			$tempRow['row_order'] = $row['row_order'];
			$tempRow['image'] = (!empty($row['image']))?'<img src="category/'.$row['image'].'" height=30 >':'<img src="images/logo.png" height=30>';
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && $_GET['table'] == 'subcategory'){
		$offset = 0;$limit = 10;
		$sort = 'row_order'; $order = 'ASC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		if(isset($_GET['order']))
			$order = $_GET['order'];
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `maincat_id` like '%".$search."%' OR `subcategory_name` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `subcategory` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `subcategory` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$image = (!empty($row['image']))?'subcategory/'.$row['image']:'';
			$operate = "<a class='btn btn-xs btn-primary edit-subcategory' data-id='".$row['id']."' data-toggle='modal' data-target='#editCategoryModal' title='Edit'><i class='fas fa-edit'></i></a>";
			$operate .= "<a class='btn btn-xs btn-danger delete-subcategory' data-id='".$row['id']."' data-image='".$image."' title='Delete'><i class='fas fa-trash'></i></a>";

			$tempRow['id'] = $row['id'];
			$tempRow['maincat_id'] = $row['maincat_id'];
			$tempRow['subcategory_name'] = $row['subcategory_name'];
			$tempRow['row_order'] = $row['row_order'];
			$tempRow['image'] = (!empty($row['image']))?'<img src="subcategory/'.$row['image'].'" height=30 >':'<img src="images/logo.png" height=30>';
			$tempRow['status'] = ($row['status'])?'<label class="label label-success">Active</label>':'<label class="label label-danger">Deactive</label>';
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}



	if(isset($_GET['table']) && $_GET['table'] == 'users'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['status'])){
			$status = $_GET['status'];
			if($_GET['status']!= '')
				$where = "where (`status` = ".$status.")";
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			if($_GET['status']!= '')
				$where .= " AND (`id` like '%".$search."%' OR `name` like '%".$search."%' OR `username` like '%".$search."%' OR `email` like '%".$search."%' OR `refer` like '%".$search."%' OR `ip_address` like '%".$search."%' OR `date_registered` like '%".$search."%' )";
			else
				$where = " where (`id` like '%".$search."%' OR `name` like '%".$search."%' OR `username` like '%".$search."%' OR `email` like '%".$search."%' OR `refer` like '%".$search."%' OR `ip_address` like '%".$search."%' OR `date_registered` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `users` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `users` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		$icon = array('email' => 'far fa-envelope-open', 'gmail' => 'fab fa-google-plus-square text-danger', 'fb' => 'fab fa-facebook-square text-primary');

		foreach($res as $row){
			$operate = "<a class='btn btn-xs btn-primary edit-users' data-id='".$row['id']."' data-toggle='modal' data-target='#editUserModal' title='Edit'><i class='far fa-edit'></i></a>";
			$operate .= "<a class='btn btn-xs btn-success' href='activity-tracker.php?username=".$row['username']."' target='_blank' title='Track Activities'><i class='far fa-chart-bar'></i></a>";
			$operate .= "<a class='btn btn-xs btn-danger' href='payment-requests.php?username=".$row['username']."' target='_blank' title='Payment Requests'><i class='fas fa-rupee-sign'></i></a>";

			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['username'] = $row['username'];
			$tempRow['email'] = $row['email'];
			$tempRow['type'] = '<i class="'.$icon[$row['type']].' fa-2x"></i>';
			$tempRow['fcm_id'] = $row['fcm_id'];
			$tempRow['points'] = $row['points'];
			$tempRow['refer'] = $row['refer'];
			$tempRow['ip_address'] = $row['ip_address'];
			$tempRow['date_registered'] = $row['date_registered'];
			$tempRow['status'] = ($row['status'])?"<label class='label label-danger'>Deactive</label>":"<label class='label label-success'>Active</label>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && $_GET['table'] == 'tracker'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['username'])){
			$username = $_GET['username'];
			if($_GET['username']!= '')
				$where = "where (`username` = '".$username."')";
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			if($_GET['username']!= '')
				$where .= " AND (`id` like '%".$search."%' OR `username` like '%".$search."%' OR `points` like '%".$search."%' OR `type` like '%".$search."%' OR `date` like '%".$search."%' )";
			else
				$where = " where (`id` like '%".$search."%' OR `username` like '%".$search."%' OR `points` like '%".$search."%' OR `type` like '%".$search."%' OR `date` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `tracker` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `tracker` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$tempRow['id'] = $row['id'];
			$tempRow['username'] = $row['username'];
			$tempRow['points'] = $row['points'];
			$tempRow['type'] = $row['type'];
			$tempRow['date'] = $row['date'];
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && $_GET['table'] == 'payment_requests'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['username'])){
			$username = $_GET['username'];
			if($_GET['username']!= '')
				$where = "where (`username` = '".$username."')";
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			if($_GET['username']!= '')
				$where .= " AND (`id` like '%".$search."%' OR `username` like '%".$search."%' OR `payment_address` like '%".$search."%' OR `request_type` like '%".$search."%' OR `request_amount` like '%".$search."%' OR `points_used` like '%".$search."%' OR `date` like '%".$search."%' )";
			else
				$where = " where (`id` like '%".$search."%' OR `username` like '%".$search."%' OR `payment_address` like '%".$search."%' OR `request_type` like '%".$search."%' OR `request_amount` like '%".$search."%' OR `points_used` like '%".$search."%' OR `date` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `payment_requests` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `payment_requests` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$operate = "<a class='btn btn-xs btn-primary edit-status' data-id='".$row['id']."' data-toggle='modal' data-target='#editStatusModal' title='Edit'><i class='far fa-edit'></i></a>";

			$tempRow['id'] = $row['id'];
			$tempRow['username'] = $row['username'];
			$tempRow['payment_address'] = $row['payment_address'];
			$tempRow['request_type'] = $row['request_type'];
			$tempRow['request_amount'] = $row['request_amount'];
			$tempRow['points_used'] = $row['points_used'];
			$tempRow['remarks'] = $row['remarks'];
			$tempRow['status'] = ($row['status'])?"<label class='label label-success'>Completed</label>":"<label class='label label-warning'>Pending</label>";
			$tempRow['date'] = $row['date'];
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	if(isset($_GET['table']) && $_GET['table'] == 'question'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['category']) && !empty($_GET['category'])){
			$where = 'where `category` = '.$_GET['category'];
			if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
				$where .= ' and `subcategory`='.$_GET['subcategory'];
			}
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `question` like '%".$search."%' OR `optiona` like '%".$search."%' OR `optionb` like '%".$search."%' OR `optionc` like '%".$search."%' OR `optiond` like '%".$search."%' OR `answer` like '%".$search."%' )";
			if(isset($_GET['category']) && !empty($_GET['category'])){
				$where .= ' and `category` = '.$_GET['category'];
				if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
					$where .= ' and `subcategory`='.$_GET['subcategory'];
				}
			}
		}

		$sql = "SELECT COUNT(*) as total FROM `question` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `nomer` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$tempRow['id'] = $row['id'];
			$tempRow['image'] = $row['nomer'];
			$tempRow['question'] = $row['status'];
			$tempRow['optiona'] = $row['progress'];


			if($row['progress']==-1){
				$tempRow['optiona'] = '<p>Yuborilmadi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
				<path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
				<path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
				</svg></p>';
			}if($row['progress']==0){
				$tempRow['optiona'] = '<p>Yuborilmoqda: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
				<path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
				<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
				</svg> </p>';
			}if($row['progress']==1){
				$tempRow['optiona'] = '<p>Yuborildi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
				<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
				</svg></p>';
			}if($row['progress']==2){
				$tempRow['optiona'] = '<p>Juda tez yuborildi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
				<path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
				<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
				</svg></p>';
			}
		//$tempRow['optiona'] = ($row['progress']==)?'<a data-fancybox="Question-Image" href="images/questions/'.$row['image'].'" data-caption="'.$row['question'].'"><img src="images/questions/'.$row['image'].'" height=30 ></a>':'No image';


			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && $_GET['table'] == 'sender'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['category']) && !empty($_GET['category'])){
			$where = 'where `category` = '.$_GET['category'];
			if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
				$where .= ' and `subcategory`='.$_GET['subcategory'];
			}
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `question` like '%".$search."%' OR `optiona` like '%".$search."%' OR `optionb` like '%".$search."%' OR `optionc` like '%".$search."%' OR `optiond` like '%".$search."%' OR `answer` like '%".$search."%' )";
			if(isset($_GET['category']) && !empty($_GET['category'])){
				$where .= ' and `category` = '.$_GET['category'];
				if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
					$where .= ' and `subcategory`='.$_GET['subcategory'];
				}
			}
		}

		$sql = "SELECT COUNT(*) as total FROM `sender` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `sender` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$tempRow['id'] = $row['id'];
			$tempRow['image'] = $row['name'];
			$tempRow['question'] = $row['uzunlik'];
			
			if($row['progress']==1){
				$tempRow['optiona'] = '<p>Yuborilmoqda: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
				<path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
				<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
				</svg> </p>';
			}if($row['progress']==0){
				$tempRow['optiona'] = '<p>Yuborildi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
				<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
				</svg></p>';
			}
			
		$operate = "<a class='btn btn-xs btn-danger delete-question' data-id='".$row['id']."' data-image='".$row['name']."' title='Delete'><i class='fas fa-trash'></i></a>";

		$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && $_GET['table'] == 'question_reports'){
		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `username` like '%".$search."%' OR `payment_address` like '%".$search."%' OR `request_type` like '%".$search."%' OR `request_amount` like '%".$search."%' OR `points_used` like '%".$search."%' OR `date` like '%".$search."%' )";
		}

		$sql = "SELECT COUNT(*) as total FROM `question_reports` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT *,(select `question` from `question` where `question_reports`.`question_id` = `question`.`id` ) as `question` FROM `question_reports` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$operate = "<a class='btn btn-xs btn-danger delete-report' data-id='".$row['id']."' title='Delete'><i class='fas fa-trash'></i></a>";

			$tempRow['id'] = $row['id'];
			$tempRow['question_id'] = $row['question_id'];
			$tempRow['question'] = $row['question'];
			$tempRow['message'] = $row['message'];
			$tempRow['date'] = $row['date'];
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}

	if(isset($_GET['table']) && is_numeric($_GET['table'])){
		$te = $_GET['table'];


		$offset = 0;$limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		$table = $_GET['table'];

		if(isset($_POST['id']))
			$id = $_POST['id'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];

		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];

		if(isset($_GET['category']) && !empty($_GET['category'])){
			$where = 'where `category` = '.$_GET['category'];
			if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
				$where .= ' and `subcategory`='.$_GET['subcategory'];
			}
		}

		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " where (`id` like '%".$search."%' OR `question` like '%".$search."%' OR `optiona` like '%".$search."%' OR `optionb` like '%".$search."%' OR `optionc` like '%".$search."%' OR `optiond` like '%".$search."%' OR `answer` like '%".$search."%' )";
			if(isset($_GET['category']) && !empty($_GET['category'])){
				$where .= ' and `category` = '.$_GET['category'];
				if(isset($_GET['subcategory']) && !empty($_GET['subcategory'])){
					$where .= ' and `subcategory`='.$_GET['subcategory'];
				}
			}
		}

		$sql = "SELECT COUNT(*) as total FROM `question` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}

		$sql = "SELECT * FROM `$te` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();

		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();

		foreach($res as $row){
			$tempRow['id'] = $row['id'];
			$tempRow['image'] = $row['nomer'];
			$tempRow['question'] = $row['status'];
			$tempRow['optiona'] = $row['progress'];


			if($row['progress']==-1){
				$tempRow['optiona'] = '<p>Yuborilmadi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
				<path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z"/>
				<path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z"/>
				</svg></p>';
			}if($row['progress']==0){
				$tempRow['optiona'] = '<p>Yuborilmoqda: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
				<path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
				<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
				</svg> </p>';
			}if($row['progress']==1){
				$tempRow['optiona'] = '<p>Yuborildi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
				<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
				<path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
				</svg></p>';
			}if($row['progress']==2){
				$tempRow['optiona'] = '<p>Juda tez yuborildi: <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-square" viewBox="0 0 16 16">
				<path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
				<path d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.235.235 0 0 1 .02-.022z"/>
				</svg></p>';
			}
		//$tempRow['optiona'] = ($row['progress']==)?'<a data-fancybox="Question-Image" href="images/questions/'.$row['image'].'" data-caption="'.$row['question'].'"><img src="images/questions/'.$row['image'].'" height=30 ></a>':'No image';


			$rows[] = $tempRow;
		}

		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	?>