<?php

$host = "localhost"; /* Host name */
$user = "u1257703_sms"; /* User */
$password = "vY3tW9xL1mbZ2i"; /* Password */
$dbname = "u1257703_sms"; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}