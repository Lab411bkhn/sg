<?php
require '../dbconnect.php';
if(isset($_POST['lat']) && $_POST['lng'] && $_POST['time']) {
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$time = $_POST['time'];
	$my_query = "INSERT INTO object_predicted(lat, lng, time) VALUE ('".$lat."', '".$lng."', '".$time."')";	
	mysql_query($my_query) or die("Error in Selecting ");
}
?>