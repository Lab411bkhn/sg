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
	
	function getTempAvgOneDay($dateTemp,$sensor,$value) //lay nhiet do trung binh ngay
	{
		$myquerry = "SELECT AVG(".$value.") FROM data_sensor WHERE mac='".$sensor."' AND time LIKE '".$dateTemp."%'";
		$query = mysql_query($myquerry);
		if($query === FALSE) { 
			die(mysql_error()); // TODO: better error handling
		}
		$row = mysql_fetch_array($query);
		if( $row[0] != NULL) return (double)$row[0];
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
	
	if(isset($_GET['date']) && $_GET['begin'] && $_GET['end']){
		if ($type == 'avgDay')
		{
			$date = $_GET['date'];  //năm-thang-ngày
			$begin = (int)$_GET['begin'];
			$end = $_GET['end'];
			$column = array();
			for($i = $begin; $i<=$end; $i++) {
				if($i < 10) $i_temp = '0'.$i;
				else $i_temp = $i;
				$dateGet = $date.'-'.$i_temp;
				$column[] = array(
						'day' => $i_temp,
						'temp' => getTempAvgOneDay($dateGet,$mac,'temp'),
						'humi' => getTempAvgOneDay($dateGet,$mac,'humi'),
						'ener' => getTempAvgOneDay($dateGet,$mac,'ener')
				);	
			}
			die (json_encode($column));
		}
	}
}

?>