<?php
require 'dbconnect.php';
if(isset($_GET['mac']) && $_GET['type']) {
	$mac = $_GET['mac'];
	$type = $_GET['type'];
	/*function getTempAvgDay($dd,$ss,$va) //lay nhiet do trung binh ngay
	{
		$result = 0;
		$numRow = 0;
		$sql = "SELECT * FROM data_avg WHERE date LIKE '".$dd."%' and sensor='".$ss."'";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			$result += $row[$va];
			$numRow += 1;
		}
		if($numRow != 0) return $result/$numRow;
		else return 0;
	}*/
	
	function getTempAvgDay($dd,$ss,$va) //lay nhiet do trung binh ngay
	{
		$result = 0;
		$numRow = 0;
		$sql = "SELECT * FROM data_sensor WHERE time LIKE '".$dd."%' and mac='".$ss."'";
		$query = mysql_query($sql);
		if($query === FALSE) { 
			die(mysql_error()); // TODO: better error handling
		}
		while($row = mysql_fetch_array($query))
		{
			$result += $row[$va];
			$numRow += 1;
		}
		if($numRow != 0) return $result/$numRow;
		else return 0;
	}
	
	function getTempAvgMonth($yearMonth,$ss,$va) //lay nhiet do trung binh tháng
	{
		$result = 0;
		$numRow = 0;
		$sql = "SELECT * FROM data_sensor WHERE time LIKE '".$yearMonth."%' and mac='".$ss."'";
		$query = mysql_query($sql);
		if($query === FALSE) { 
			die(mysql_error()); // TODO: better error handling
		}
		while($row = mysql_fetch_array($query))
		{
			$result += $row[$va];
			$numRow += 1;
		}
		if($numRow != 0) return $result/$numRow;
		else return 0;
	}
	
	if($type == 'day'){
		$numDay = 12;
		$column = array();
		for($i = 0; $i < $numDay; $i++)
		{	
			$dateTemp = mktime(0, 0, 0, date("m"), date("d") - $i,   date("Y"));
			$dateTime = date('Y-m-d' , $dateTemp);
			$day = 	date('d', $dateTemp);
			$column[] = array(
						'day' => $day,
						'temp' => getTempAvgDay($dateTime,$mac,'temp'),
						'humi' => getTempAvgDay($dateTime,$mac,'humi'),
						'ener' => getTempAvgDay($dateTime,$mac,'ener')
			);	
		}
		die (json_encode($column));
	}
	else if ($type == 'year'){
		$numDay = 12;
		$column = array();
		for($i = 0; $i < $numDay; $i++)
		{	
			$dateTemp = mktime(0, 0, 0, $i, date("d"),   date("Y"));
			$yearMonth = date('Y-m' , $dateTemp);
			$month = 	date('m', $dateTemp);	
			$column[] = array(
						'month' => $month,
						'temp' => getTempAvgMonth($yearMonth,$mac,'temp'),
						'humi' => getTempAvgMonth($yearMonth,$mac,'humi'),
						'ener' => getTempAvgMonth($yearMonth,$mac,'ener')
			);	
		}
		die (json_encode($column));
	}
}

?>