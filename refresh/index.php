<!DOCTYPE html>
<html>
 <head>
  <title>Auto Refresh Div Content using AngularJS in PHP</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.7/angular.min.js"></script>
  <style>
  #load_tweets
    {
      padding:16px;
      background-color:#f1f1f1;
      margin-bottom:30px;
    }
    #load_tweets li
    {
      padding:12px;
      border-bottom:1px dotted #ccc;
      list-style: none;
    }
  </style>
 </head>
 <body>
  <br />
   <h3 align="center">PHP da chat...</h3>
  <br />
  <div ng-app="autoRefreshApp" ng-controller="autoRefreshController" class="container" style="width:100%; max-width:600px;">
   <h3 align="center">Chat boshlandi</h3>

   <div class="alert alert-success alert-dismissible" ng-show="success">
    <a href="#" class="close" aria-label="close">&times;</a>
    {{ successMessage }}
   </div>

   <form method="post" ng-model="form_data" ng-submit="submitForm()">
    <div class="form-group">
     <label>Xabaringizni kiriting</label>
     <textarea name="content" class="form-control" ng-model="form_data.content"></textarea>
    </div>
    <div class="form-group" align="right">
     <input type="submit" name="submit" class="btn btn-info" value="Yuborish" />
    </div>
   </form>
   <div id="load_tweets">
    <ul>
     <li ng-repeat="messageData in messagesData">
      {{ messageData.chat_content }}  {{ messageData.text }}
     </li>
    </ul>
   </div>
  </div>
 </body>
</html>

<script>

var app = angular.module('autoRefreshApp', []);

app.controller('autoRefreshController', function($scope, $http, $interval){

 $scope.success = false;

 $scope.submitForm = function(){
  $http({
   method:"POST",
   url:'insert.php', 
   data:$scope.form_data
  }).success(function(data){
   if(data.message != '')
   {
    $scope.form_data = null;
    $scope.success = true;
    $scope.successMessage = data.message;
    $interval(function(){
     $scope.success = false;
    }, 1000);
   }
  });
 };

 $scope.fetchData = function(){
  $http.get('fetch.php').success(function(data){
   $scope.messagesData = data;
  });
 };

 $interval(function(){
  $scope.fetchData();
 }, 1000);

});



</script>