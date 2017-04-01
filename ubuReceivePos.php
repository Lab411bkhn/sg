<?php   
require 'dbconnect.php';
if(isset($_GET['data'])) echo $_GET['data'];
$result="#".$_GET['data'];
	if(strpos($result,"UB")!== false)
	{
		//$my_query = "UPDATE cdata SET status=0 WHERE 1";
		//mysql_query($my_query);
		//$my_query = "UPDATE cdata SET status=1 WHERE mac ='00'";
		//mysql_query($my_query);
		$mac = substr($result, 3, 2 ); //NN
		$lat = substr($result, 5, 10);
		$lng = substr($result, 15, 10); 
		$my_query = "INSERT INTO ubupos(mac, lat, lng) VALUE ('".$mac."', '".$lat."', '".$lng."')";
		mysql_query($my_query);
	}

?>