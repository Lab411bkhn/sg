<?php 
	require 'dbconnect.php';
	//$type = $_POST['typeCommand'];
	$myObj = new stdClass();
	$sql = "SELECT * FROM cdata";
	$result = mysql_query($sql) or die(" Error in Selecting ");
	$sensor = array();
	while($row = mysql_fetch_array($result))
	{ 
		$sensor[] = array(
					'mac' => $row['mac'],
					'lat' => $row['lat'],
					'lng' => $row['lng'],
					'status' => $row['status']
				);
	}
	die (json_encode($sensor));
?>
	