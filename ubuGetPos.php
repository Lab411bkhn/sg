<?php 
require 'dbconnect.php';
//$mac = $_POST['mac'];
//$result = mysql_query("SELECT MAX(STT) FROM ubupos");
	   // $row = mysql_fetch_row($result);
	   // $highest_id = $row[0];
		//echo $row[1];
	$myObj = new stdClass();
	$sql = "SELECT lat, lng FROM ubupos ORDER BY STT DESC LIMIT 1";
	$result = mysql_query($sql) or die(" Error in Selecting ");
	$row = mysql_fetch_array($result);
	//$myArr = array($row[0], $row[1]);
	$myObj->lat = $row[0];
	$myObj->lng = $row[1];
	$myJSON = json_encode($myObj);
	echo $myJSON;
	//mysql_query("DELETE FROM ubupos");
?>