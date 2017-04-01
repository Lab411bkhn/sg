<?php 
require 'dbconnect.php';
if(isset($_GET['type'])) {
	$type = $_GET['type'];
	if($type == "getposition"){
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
	}
	else if ($type == "updateSensorPosition"){
		if(isset($_GET['mac']) && isset($_GET['lat']) && isset($_GET['lng'])){
			$mac = $_GET['mac'];
			$lat = $_GET['lat'];
			$lng = $_GET['lng'];
			$sql1 = "SELECT * FROM cdata WHERE mac = '".$mac."'";
			$query1 = mysql_query($sql1);
			$row_no = mysql_num_rows($query1); // đếm số dòng dữ liệu trong bảng
			if( $row_no == 0){
				$my_query2 = "INSERT INTO cdata(mac, nodecat, status, lat, lng) VALUES ('".$mac."', 'sensor', '0','".$lat."', '".$lng."')";
				$query1 = mysql_query($my_query2);
				echo "Insert Position Sucessfully";
			}
			else {
				$mysql = "UPDATE cdata SET nodecat = 'sensor', status = '0',lat = '".$lat."',lng = '".$lng."' WHERE mac = '".$mac."'";
				$query1 = mysql_query($mysql);	
				echo "Update Position Sucessfully<br>mac = '".$mac."', lat = '".$lat."',lng = '".$lng."'";
			}
			
		}
	}
}
?>
	