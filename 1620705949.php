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
session_start();
if(!isset($_SESSION['id']) && !isset($_SESSION['username'])){
	header("location:index.php");
	return false;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>1620705949 - Admin Panel </title>
	<?php include 'include-css.php';?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>

</head>
<body class="nav-md">


	<div ng-app="autoRefreshApp" ng-controller="autoRefreshController" class="container body">
		<div class="main_container">
			<?php include 'sidebar.php';?>
			<!-- page content -->
			<div class="right_col" role="main">
				<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-1">

										<form method="post">
    <button type="submit" class="btn btn-success" id="button_1" value="val_1" name="but1">SMSlarni yuborish</button>
    <input id="access_token" type="hidden" name="access_token" value="<?php echo "1620705949" ?>" />
</form>
											</div>
				<br />

				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							
							<div class="x_content">
								
								 <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th scope="col" class="th-sm">#</th>
											<th scope="col" class="th-sm">ID</th>
											<th scope="col" class="th-sm">Nomer</th>
											<th scope="col" class="th-sm">SMS</th>
											<th scope="col" class="th-sm">Holat</th>
											<th scope="col" class="th-sm" >Tezlik</th>
											<th scope="col" class="th-sm">Natija</th>
										</tr>
									</thead>
									<tbody>
										<tr ng-repeat="messageData in messagesData">
											<th scope="row">1</th>
											<td>{{ messageData.id }}</td>
											<td>{{ messageData.nomer }}</td>
											<td>{{ messageData.sms }}</td>
											<td>{{ messageData.status }}</td>
											<td>{{ messageData.speed }}</td>
											<td>{{ messageData.progress }}</td>
										</tr>
									</tbody>
								</table> 
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /page content -->

		<!-- footer content -->
		<?php include 'footer.php';?>
		<!-- /footer content -->
	</div>
</div>
</body>
</html>

<script type="text/javascript">
	$("button").click(function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "https://shohruxbek.uz/sms/sendsms/1620705949.php?access_token=1620705949",
        data: { 
            id: $(this).val(), // < note use of 'this' here
            access_token: $("#access_token").val() 
        },
        success: function(result) {
        	var obj = JSON.parse(result);

        	if(obj["ok"]=="true"){
alert("Yuborilmoqda...");
        	}else if(obj["ok"]=="ish"){
alert("Hali ancha bor");

        	}else{
        		alert("Avval yuborilgan!!!");
        	}
            
        },
        error: function(result) {
            alert("SMS yuborish fayli bilan yoki Mysqlda xatolik!!!");
        }
    });
});
</script>
<script>

	var app = angular.module('autoRefreshApp', []);

	app.controller('autoRefreshController', function($scope, $http, $interval){

		$scope.success = false;

		$scope.fetchData = function(){
			$http.get('https://shohruxbek.uz/sms/refresh/fetch.php?id=1620705949').success(function(data){
				$scope.messagesData = data;
			});
		};

		$interval(function(){
			$scope.fetchData();
		}, 2000);    
	});



</script>

