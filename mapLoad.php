
<script type="text/javascript" src = "jquery.js"></script>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body onLoad="loadmap()">
	<input onclick="clearMarkers();" type=button value="Hide Markers">
    <input onclick="showMarkers();" type=button value="Show All Markers">
    <input onclick="deleteMarkers();" type=button value="Delete Markers">
    <input onclick="updatePosUBU();" type=button value="Update Ubu">
    <div id="div_map" style="height:500px; "></div>
    <div id="div_chart" style="height:200px;"></div>
    <div id="div_chart_month" style="height:200px;"></div> 
    <button onclick="drawChartMonth();drawChart();"> Draw</button>
</body>

</html>
<script type="text/javascript">
 var map;
 var poly;
var iconSensorVl = 'https://www.google.com/mapfiles/ms/icons/green.png';
var iconSensorBc = 'https://www.google.com/mapfiles/ms/icons/red.png';
var iconSensorSleep = 'http://maps.google.com/mapfiles/kml/pal2/icon26.png';
var iconSensorSleepMini = 'http://labs.google.com/ridefinder/images/mm_20_gray.png';
var iconOBU =   'https://www.google.com/mapfiles/ms/micons/truck.png';
var iconTrucThang = 'https://www.google.com/mapfiles/ms/icons/helicopter.png';
 var markers = [];
 var ubuMarkers = [];
 var ubu;
 var locations =[
  ['sensor 1',20.3144984, 105.5761477, 1, 32],
  ['sensor 2' ,20.309447,105.6070093,2, 33],
  ['sensor 3' ,20.3087622,105.5936706,3, 34],
  ['sensor 4',20.3084322,105.5936986,4,35]
 
];
 //var ubuMark[20];
 	function loadmap() {
		map = new google.maps.Map(document.getElementById('div_map'), {
			zoom: 18,
			center: new google.maps.LatLng(21.005705,105.842454),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		ubu = new google.maps.Marker({
        	position: new google.maps.LatLng(21.005126,105.842296),
        	map: map, 
			icon: iconTrucThang
    	});
		// This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
          addMarkerMac(event.latLng,02);
        });
		updateSensor();
		poly = new google.maps.Polyline({
          strokeColor: '#000000',
          strokeOpacity: 1.0,
          strokeWeight: 3
        });
        poly.setMap(map);
		
	}
	
	function addMarkerMac(location, mac,status) {
 		var infowindow = new google.maps.InfoWindow({maxWidth: 220, Height:300});
		var marker = new google.maps.Marker({
        position: location,
        map: map,
		label: mac
    	});
		if (status == '0') marker.setIcon(iconSensorSleep);
		else {
			if(mac > '30') marker.setIcon(iconSensorBc);
			else  marker.setIcon(iconSensorVl);
		}
		google.maps.event.addListener(marker, 'rightclick', (function(marker) {
			return function() {
				infowindow.setContent('<iframe src="action.php?mac='+mac+'"></iframe>');
				infowindow.open(map, marker);
				
			}
		})(marker)); 
		
		marker.addListener('click', function() {
          	drawChartMonth();
			drawChart();
        });
		$.ajax({
				url: "jsonSensor.php",                           
				type: "GET",
				async: false,
				data: "mac=" + mac,
				success:function(req){ 
					google.maps.event.addListener(marker, 'click', (function(marker) {
						return function() {
							infowindow.setContent(req);
							infowindow.open(map, marker);
							
						}
				})(marker)); 
				}	
		});
		markers.push(marker);
	}
	
	// Adds a marker to the map and push to the array.
    function addMarker(location) {
 		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({
        position: location,
        map: map,
		label: mac,
		icon: iconSensor
    	});
		google.maps.event.addListener(marker, 'click', (function(marker) {
				return function() {
					infowindow.setContent('<iframe src="jsonSensor.php?mac='+02+'"></iframe>');
					infowindow.open(map, marker);
				}
		})(marker)); 
		markers.push(marker);
	}
	
	// Sets the map on all markers in the array.
	function setMapOnAll(map) {
		for (var i = 0; i < markers.length; i++) {
	  		markers[i].setMap(map);
		}		  
	}
	// Shows any markers currently in the array.
	function showMarkers() {
		setMapOnAll(map);
	}
	// Removes the markers from the map, but keeps them in the array.
		function clearMarkers() {
		setMapOnAll(null);
	}
	// Deletes all markers in the array by removing references to them.
	function deleteMarkers() {
		clearMarkers();
		markers = [];
	}	 
	
	function showPath(){
		
	}
	
	function updateSensor(){
		var macTemp = null;
		for (var i = 1; i<100; i++){
			if(i<16) macTemp = "0" + i.toString(16);
			else macTemp = i;
			$.ajax({
				url: "getPosition.php",                           
				type: "POST",
				async: false,
				data: "mac=" + macTemp,
				success:function(req){ 
					myObj = JSON.parse(req);
					var sensorPosition = new google.maps.LatLng(myObj.lat,myObj.lng);
					addMarkerMac(sensorPosition, myObj.mac,myObj.status);
				}	
			});	
		}
	}
	
	function updatePosUBU(){
		//alert("duong");
		$.ajax({
			url: "ubuGetPos.php",                           
			type: "POST",
			async: false,
		    //data: "mac= 0",
			success:function(req){ 
				myObj = JSON.parse(req);
				var ubuPos = new google.maps.LatLng(myObj.lat,myObj.lng);
				ubu.setPosition(ubuPos);
				var path = poly.getPath();
				// Because path is an MVCArray, we can simply append a new coordinate
				// and it will automatically appear.
				path.push(ubuPos);
				//updatePosUbu(new google.maps.LatLng(myObj.lat,myObj.lng));
			}	
		});
	}
</script>
 <script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyCygRXb-HJURc5BaMAcdBXrf_q1va_Q2pc&callback=initMap" type="text/javascript"></script>
<?php
	function getTempAvgDay($dd,$ss,$va) //lay nhiet do trung binh ngay
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
	}
	
	function getTempAvgMonth($yearMonth,$ss,$va) //lay nhiet do trung binh tháng
	{
		$result = 0;
		$numRow = 0;
		$sql = "SELECT * FROM data_avg WHERE date LIKE '".$yearMonth."%' and sensor='".$ss."'";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			$result += $row[$va];
			$numRow += 1;
		}
		if($numRow != 0) return $result/$numRow;
		else return 0;
	}
	
?>
<script language="javascript">	
	google.load("visualization", "1", {packages:["corechart"]});
	charts.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Ngày','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']
				<?php
				$numDay = 12;
				for($i = 0; $i < $numDay; $i++)
				{
					$dateTemp = mktime(0, 0, 0, date("m"), date("d") -$i,   date("Y"));
					$dateTime = date('Y-m-d' , $dateTemp);
					$day = 	date('d', $dateTemp);			
					//$mac = dechex($i);
					//$mac = '0'.strtoupper($mac);
					$mac = '02';
					echo ",['".$day."',".getTempAvgDay($dateTime,$mac,"temp").",".getTempAvgDay($dateTime,$mac,"humi").",".getTempAvgDay($dateTime,$mac,"power")."]";
				}
				?>
			]);
			var options = {
				title: 'Dữ liệu sensor <?php echo $mac?> trong <?php echo($numDay +1)?> ngày gần đây',
				hAxis: {title: 'Sensor vườn lan', titleTextStyle: {color: 'red'}}
			};
		var chart = new google.visualization.ColumnChart(document.getElementById('div_chart'));
		chart.draw(data, options);
	}
	
	function drawChartMonth(macDraw) {
			var data = google.visualization.arrayToDataTable([
				['Tháng','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']
				<?php
				for($i = 1; $i < 13; $i++)
				{
					$dateTemp = mktime(0, 0, 0, $i, date("d"),   date("Y"));
					$yearMonth = date('Y-m' , $dateTemp);
					$month = 	date('m', $dateTemp);			
					//$mac = dechex($i);
					//$mac = '0'.strtoupper($mac);
					$mac = '02';
					echo ",['".$month."',".getTempAvgMonth($yearMonth,$mac,"temp").",".getTempAvgMonth($yearMonth,$mac,"humi").",".getTempAvgMonth($yearMonth,$mac,"power")."]";
				}
				?>
			]);
			var options = {
				title: 'Dữ liệu sensor <?php echo $mac?> trong năm',
				hAxis: {title: 'Sensor vườn lan', titleTextStyle: {color: 'red'}}
			};
		var chart = new google.visualization.ColumnChart(document.getElementById('div_chart_month'));
		chart.draw(data, options);
	}
</script> 

