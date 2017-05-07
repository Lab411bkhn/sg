<?php
require '../dbconnect.php';
if(isset($_POST['lat']) && $_POST['lng'] && $_POST['time'] && $_POST['speed']) {
	$lat = $_POST['lat'];
	$lng = $_POST['lng'];
	$time = $_POST['time'];
	$speed = $_POST['speed'];
	$my_query = "INSERT INTO object_predicted(lat, lng, time,speed) VALUE ('".$lat."', '".$lng."', '".$time."', '".$speed."')";	
	mysql_query($my_query) or die("Error in Selecting ");
	echo "Successful";
}
?>