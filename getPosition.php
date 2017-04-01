<?php 
require 'dbconnect.php';
if(isset($_GET['table'])){
	$table = $_GET['table'];
	if($table === 'cdata'){
		$sql = "SELECT * FROM cdata";
		$result = mysql_query($sql) or die(" Error in Selecting ");		
		$myObj = new stdClass();
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
	}
	else if ($table === 'object'){
		$sql = "SELECT * FROM object";
		$result = mysql_query($sql) or die(" Error in Selecting ");	
		$myObj = new stdClass();
		$sensor = array();
		while($row = mysql_fetch_array($result))
		{ 
			$resultPos = mysql_query("SELECT * FROM cdata WHERE mac='".$row['mac']."'");
			$row1 = mysql_fetch_array($resultPos);
			$sensor[] = array(
						'mac' => $row['mac'],
						'time' => $row['time'],
						'lat' => $row1['lat'],
						'lng' => $row1['lng']
					);
		}
		die (json_encode($sensor));
		//mysql_query("DELETE FROM object");
	}
}
?>
	