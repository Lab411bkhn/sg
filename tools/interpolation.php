<?php 
require '../dbconnect.php';
if(isset($_POST['type']) || isset($_GET['type'])){
	if(isset($_POST['type'])) $type = $_POST['type'];
	else if(isset($_GET['type'])) $type = $_GET['type'];
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
			//print_r($sensor);
			//die (json_encode($sensor));
		
		$demo = array(
			array("mac" => "07","time" => "16:44:40","lat" => "1"),
			array("mac" => "08","time" => "16:44:41","lat" => "2.25"),
			array("mac" => "16","time" => "16:44:42","lat" => "3.75"),
			array("mac" => "13","time" => "16:44:43","lat" => "4.25")
			);
		//tra ve mang gia tri cac ty hieu phuc vu cho tinh toan noi suy newton
		function getDividedDifferences($dataArray,$type){
			$result = array();
			$lengthArr = count($dataArray);
			//$dataA = JSON.parse(JSON.stringify(dataArray));		//copy array json
			$dataA = $dataArray;
			
			//$dataA = array_merge(array(), $dataArray);
			array_push($result,$dataA[0][$type]);
			for ($i=1;$i<$lengthArr;$i++){
				for($j=0;$j<$lengthArr-$i;$j++){
					$dataA[$j][$type] = ($dataA[$j+1][$type]-$dataA[$j][$type])/($dataA[$j+$i]['time']-$dataA[$j]['time']); 
				}
				array_push($result,$dataA[0][$type]);
			}
			return $result;
		}
		//print_r(getDividedDifferences($demo,'lat'));
		
		function newtonInterpolation($dataArray,$type,$deltaT){
			$lengthArr = count($dataArray);
			$dataArrayIn = $dataArray;		//copy array json		
			///////////////////////////////////
			////convert time
			for ($i=0;$i<$lengthArr;$i++){
				$timeTemp = split("\:",$dataArrayIn[$i]['time']);
				$dataArrayIn[$i]['time'] = ($timeTemp[0]*3600 + $timeTemp[1]*60 + $timeTemp[2]);
			}
			/////sắp xếp
			for ($i=0;$i<$lengthArr-1;$i++){		 
				$timeTemp1 = $dataArrayIn[$i]['time'];
				for ($j= $i+1;$j<$lengthArr;$j++){
					$timeTemp2 = $dataArrayIn[$j]['time'];
					if($timeTemp1>$timeTemp2) {
						$temo = $dataArrayIn[$j];
						$dataArrayIn[$j] = $dataArrayIn[$i];
						$dataArrayIn[$i] = $temo;
					}
				}
			}	
			//////////////////////////////////////
			$divDiff = getDividedDifferences($dataArrayIn,$type);	//print_r($divDiff);			
			$timeSample = $dataArrayIn[$lengthArr-1]['time'] + $deltaT;	
			$result = $divDiff[0]; $temp = 1;		
			for ($i=1;$i<$lengthArr;$i++){
				$temp = $temp * ($timeSample - $dataArrayIn[$i-1]['time']);
				$result = $result + $divDiff[$i]*$temp;
			}
			return $result;
		}	
		//print_r(newtonInterpolation($sensor,'lat',5));
		
		
		
		function interpolationSpeedOneDirect($dataArray,$time){
			$dataArrayIn = $dataArray;		//copy array json	
			$time = (int)($time/5)*5;
			$vx = (newtonInterpolation($dataArrayIn,'lat',$time+5) - newtonInterpolation($dataArrayIn,'lat',$time-5))/10;
			$vy = (newtonInterpolation($dataArrayIn,'lng',$time+5) - newtonInterpolation($dataArrayIn,'lng',$time-5))/10;
			//echo("Time: ".$time."---Vx: ".$vx."---Vy:".$vy."<br>");
			$speedAngle = sqrt($vx*$vx + $vy*$vy);
			$speedMs = $speedAngle*111317.1;
			return $speedMs;
		}	
		
		function interpolation10points($dataArrayInput,$number){
			$dataArr = $dataArrayInput;
			$sampleTime = 5;
			mysql_query("DELETE FROM object_predicted");
			for ($j= 0; $j<=$number; $j++){
				$timeSampl = $sampleTime*$j;
				$latTem = newtonInterpolation($dataArr,'lat',$timeSampl);
				$lngTem = newtonInterpolation($dataArr,'lng',$timeSampl);
				$speed = interpolationSpeedOneDirect($dataArr,$timeSampl);
				$my_query = "INSERT INTO object_predicted(lat, lng, time,speed) VALUE ('".$latTem."', '".$lngTem."', '".$timeSampl."','".$speed."')";	
				mysql_query($my_query) or die("Error in Selecting ");
				echo($timeSampl.": Lat: ".$latTem." - Lng: ".$lngTem."<br>");
			}    		
		}
		//interpolation10points($sensor,10);
		function getPercentPosition($dataArrayInput,$time,$timeTotal){
			$dataArr = $dataArrayInput;
			$lat0 = newtonInterpolation($dataArr,'lat',0);
			$lng0 = newtonInterpolation($dataArr,'lng',0);
			$lat = newtonInterpolation($dataArr,'lat',$time);
			$lng = newtonInterpolation($dataArr,'lng',$time);
			$macDetected = -1;
			$latMax = newtonInterpolation($dataArr,'lat',$timeTotal);
			$lngMax = newtonInterpolation($dataArr,'lng',$timeTotal);
			//echo ("Vx:".$lat."--Vy:".$lng."-VxT:".$latMax."-VyT:".$lngMax."<br>");
			$percent = sqrt((($lat-$lat0)*($lat-$lat0) + ($lng-$lng0)*($lng-$lng0))/(($latMax-$lat0)*($latMax-$lat0) + ($lngMax-$lng0)*($lngMax-$lng0)))*100;
			if($percent > 100) $percent = 100;
			$dataReturn = array(
				'lat' => $lat,
				'lng' => $lng,
				'percent' => $percent
			);
			return $dataReturn;
		}
		
		function getMacDetectedObject($lat,$lng){
			$getSensor = mysql_query("SELECT * FROM cdata");
			while($row = mysql_fetch_array($getSensor)){
				if(abs($lat - $row['lat']) < 0.00003){
					if(abs($lng - $row['lng']) < 0.00003){
						return $row['mac'];
					}					
				}
			}
			return NULL;
		}
		
		function getMacTimeRequestObject(){
			$macLatestQuery = mysql_query("SELECT mac FROM object ORDER BY time DESC LIMIT 1");
			$macLatest = mysql_fetch_array($macLatestQuery);
			$getSensor = mysql_query("SELECT * FROM object_predicted");
			while($row = mysql_fetch_array($getSensor)){
				$mac = getMacDetectedObject($row['lat'], $row['lng']);
				if($macLatest[0] !== $mac && $mac !== NULL) {
					$dataReturn = array(
						'mac' => $mac,
						'time' => $row['time']
					);
					return $dataReturn;
				}
			}
		}

		function makeCommandToTakePhoto($dataArray){
			$macTime = getMacTimeRequestObject();
			$sql0="SELECT MAX(time) FROM object";
			$query0 = mysql_query($sql0);
			while($row0 = mysql_fetch_array($query0)){
				$time0 = $row0[0];
			}	
			$timeTemp = split("\:",$time0);
			$tem1 = (int)($macTime['time']/3600);
			$tem2 = (int)(($macTime['time'] - $tem1*3600)/60);
			$timeTemp[0] = (int)(($timeTemp[0] + $tem1)%24);
			$timeTemp[1] = (int)$timeTemp[1] + $tem2;
			$timeTemp[2] = (int)$timeTemp[2] + $macTime['time'] - $tem1*3600 - $tem2*60;			
			if($timeTemp[2]>59) {
				$timeTemp[2] = $timeTemp[2]-60;	
				$timeTemp[1] += 1;
			}
			if($timeTemp[1] > 59) {
				$timeTemp[1] = $timeTemp[1]-60;	
				$timeTemp[0] += 1;
			}
			for($i=0;$i<3;$i++){
				if($timeTemp[$i] < 10) $timeTemp[$i] = "0".$timeTemp[$i];
			}
			$timeDoCommand = $timeTemp[0].$timeTemp[1].$timeTemp[2];
			$sql="SELECT netip FROM cdata WHERE mac='".$macTime['mac']."'";
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$network_ip = $row['netip'];
				$speed = interpolationSpeedOneDirect($dataArray,$macTime['time']);
				$command = "#P:".$network_ip."-".$timeDoCommand."-".$speed;
				mysql_query("insert into command values ('".$command."')");
				echo "Sent command '$command' to Sensor ".$macTime['mac'];
			}			
		}
	/////////////////////////handle request////////////////////
	if($type == 'position'){	
		interpolation10points($sensor,100);
		makeCommandToTakePhoto($sensor);
	}
	else if($type == 'demo'){
		$lat = 21.004806;
		$lng =  105.844116;
		makeCommandToTakePhoto($sensor);
	}
	else if($type == 'speed'){	
		$time = $_GET['time'];
		echo (interpolationSpeedOneDirect($sensor,$time));
	}
	else if($type == 'nodePredictedSecond'){	
		$macTime = getMacTimeRequestObject();
		$dataReturn = array(
			'mac' => $macTime['mac'],
			'time' => $macTime['time']
		);
		echo (json_encode($dataReturn));
	}
	else if($type == 'nodePredicted'){	
		$macTime = getMacTimeRequestObject();
		$sql0="SELECT MAX(time) FROM object";
		$query0 = mysql_query($sql0);
		while($row0 = mysql_fetch_array($query0)){
			$time0 = $row0[0];
		}	
		$timeTemp = split("\:",$time0);
		$tem1 = (int)($macTime['time']/3600);
		$tem2 = (int)(($macTime['time'] - $tem1*3600)/60);
		$timeTemp[0] = (int)(($timeTemp[0] + $tem1)%24);
		$timeTemp[1] = (int)$timeTemp[1] + $tem2;
		$timeTemp[2] = (int)$timeTemp[2] + $macTime['time'] - $tem1*3600 - $tem2*60;			
		if($timeTemp[2]>59) {
			$timeTemp[2] = $timeTemp[2]-60;	
			$timeTemp[1] += 1;
		}
		if($timeTemp[1] > 59) {
			$timeTemp[1] = $timeTemp[1]-60;	
			$timeTemp[0] += 1;
		}
		for($i=0;$i<3;$i++){
			if($timeTemp[$i] < 10) $timeTemp[$i] = "0".$timeTemp[$i];
		}
		$timeDoCommand = $timeTemp[0].":".$timeTemp[1].":".$timeTemp[2];
		$dataReturn = array(
			'mac' => $macTime['mac'],
			'time' => $timeDoCommand
		);
		echo (json_encode($dataReturn));
	}
	else if($type == 'getData'){	
		$time = $_GET['time'];
		$speed = interpolationSpeedOneDirect($sensor,$time);
		$percent = getPercentPosition($sensor,$time,500);
		$dataReturn = array(
			'speed' => $speed,
			'lat' => $percent['lat'],
			'lng' => $percent['lng'],
			'percent' => $percent['percent']
		);
		die (json_encode($dataReturn));
	}
}
?>