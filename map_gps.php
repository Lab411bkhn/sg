<?php
session_start();
require 'dbconnect.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>Map</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="map.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src = "jquery.js"></script>
<script type="text/javascript" src="jquery142.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
$(document).ready(function(){	
	var IdCmd = '<?php 
		$result = mysql_query("SELECT MAX(STT) FROM bantin");
	    $row = mysql_fetch_row($result);
	    $highest_id = $row[0];
	    echo $highest_id;
	?>';
	setInterval(function() { 
		updatePosUBU();
		$.get("test.php",function(data){
			if(data > IdCmd){
				updateSensor();
				IdCmd = data;
			}
		});
	}, 600);		
	
	//////////////////////////////////////////////
	function load(id){
		$.ajax({
			url: 'check_db.php',
			type:'POST',
			data:'id='+id,
			success: function(str)
			{
				var i = str.search("#JN");
				var j = str.search("#OK");
				if(i > -1 || j > -1)
				{
					window.location.reload();
					//$("#icon").remove();
					//$(".icsensor").remove();
					//$(".icactor").remove();
					//$(".icval").remove();
					//$("#mymap").remove();
					
					//$("#mainpage").load("loadmap.php");
					//$("#mainpage").fadeOut("fast").load("loadmap.php").fadeIn("fast");
				}
			}
		});
	}
	$("#vuonlan").click(function(e){
		     window.location.href="mapvl.php";
		});
	$("#baochay").click(function(e){
	     window.location.href="mapbc.php";
	});
	$("#gps").click(function(e){
		 var checkbc=document.getElementById("gps");
			 checkbc.checked=true;
	     window.location.href="map_gps.php";
		 
	});
	var isLogin='<?php if(isset($_SESSION['login_status'])){if($_SESSION['login_status']=='yes')echo 'yes';else echo 'no';}else echo 'no';?>';
	var isLevel='<?php if(isset($_SESSION['level'])){echo $_SESSION['level'];}?>';
	if(isLogin=='yes'){			 
		//var content = "<table><tr><td><a href='signout.php'>ÃÄƒng xuáº¥t</a></td><td></td></tr></table>";
		var content = "<table><tr><td><i>Xin chÃ o báº¡n </i><b> [ </b><u id='log'><?php if(isset($_SESSION['login_status'])){ echo $_SESSION['user'];} ?></u><b> ] </b></td><td></td></tr></table>";
		$('#ttlogin').append(content);
	}
	else{
		var content = "<table><tr><td> <a href='register.php'>Đăng ký</a>&nbsp&nbsp&nbsp&nbsp<a href='login.php'>Đăng nhập</a></td></td></tr></table>";
		$('#ttlogin').append(content);
	}
	$("#log").click(function(e) {
		$("#menulogin").css('left',e.pageX - 150);
		$("#menulogin").css('top',e.pageY + 10);
        $("#menulogin").show();
    });
	$(document).bind("mouseup",function(e){
		$("#menulogin").hide();
		});
		
	$('.icactor').bind("contextmenu",function(e){
		$("#menusensor").hide();
		$("#menuval").hide();
		$("#menuactor").hide();
		$("#menubaochay").hide();
		$("#menu0").hide();
		macactor = $(this).attr('id');
		if(macactor =="00"){
			$.ajax({
				url: "ttactor.php",                           
				type: "POST",
				data: "id="+macactor, 
			    success:function(data){
			    	var getData = $.parseJSON(data);		    	
			    	$("#menuactor").css('left',e.pageX);
				    $("#menuactor").css('top',e.pageY);
				    
				    $("#idactor").text('Actor: '+getData.mac);
				    $('#battatca').attr('title',getData.mac);
				    $('#tattatca').attr('title',getData.mac);		    		 
				    $("#menuactor").show();			    
			    } 
			});	
		}
		if(macactor =="B1"){
			$.ajax({
				url: "ttbaochay.php",                           
				type: "POST",
				data: "id="+macactor, 
			    success:function(data){
			    	var getData = $.parseJSON(data);		    	
			    	$("#menubaochay").css('left',e.pageX);
				    $("#menubaochay").css('top',e.pageY);
				    
				    $("#idactor_bc").text('Actor : '+getData.mac);
				    $("#level_bc").text('Level : '+getData.level);
				    $('#reset').attr('title',getData.mac);			    		 
				    $("#menubaochay").show();			    
			    } 
			});	
		}	

        return false;
 	});
	
	$('.icval').bind("contextmenu",function(e){
		$("#menusensor").hide();
		$("#menuval").hide();
		$("#menuactor").hide();
		$("#menubaochay").hide();
		$("#menu0").hide();
		macval = $(this).attr('id');
		$.ajax({
			url: "ttval.php",                           
			type: "POST",
			data: "id="+macval, 
		    success:function(data){
		    	var getData = $.parseJSON(data);		    	
		    	$("#menuval").css('left',e.pageX);
			    $("#menuval").css('top',e.pageY);
			    
			    $("#idval").text('Van: '+getData.val);
			    $("#status").text('Status: '+getData.status);
			    $('#batvan').attr('title',getData.val);
			    $('#tatvan').attr('title',getData.val);	
			    $("#menuval").show();
		    } 
		});		
        return false;
 	});

	$("#batvan").click(function(e) {				
		$("#menuval").hide();
		var id = $(this).attr('title');
		if(isLevel==1){	
			$.get("change_van_no.php", {vanno: id }).done(function(data) {	
				
				var command;
				switch(id){
					case '1': command = "00011$"; break;
					case '2': command = "00021$"; break;
					case '3': command = "00031$"; break;
					case '4': command = "00041$"; break;
					case '5': command = "00051$"; break;
					case '6': command = "00061$"; break;
					default: command = "00011$";
				} 
				
				$.ajax({
					url:"send.php",
					type:"POST",
					data:"command="+command,
					success: function(string ){		
						if(string=="false"){
							alert("Khong tim thay chua nang cac Actor !");
						}
						else{		
							$.ajax({
								url:"rxbatvan.php",
								type:"POST",
								data:"command="+string,
								success: function(bdata){
									alert(bdata);
									
								}
							});
						}				
					}
				});
			});
		}
		else{
			alert("Báº¡n chÆ°a Ä‘Äƒng nháº­p hoáº·c khÃ´ng Ä‘Æ°á»£c phÃ©p Ä‘iá»u khiá»ƒn van!");
		}		
	});

	$("#tatvan").click(function(e) {
		$("#menuval").hide();
		var id = $(this).attr('title');
		if(isLevel==1){	
			$.get("change_van_no.php", {vanno: id }).done(function(data) {
				//alert(data);
				var command;
				switch(id){
					case '1':command = "00010$";break;
					case '2':command = "00020$";break;
					case '3':command = "00030$";break;
					case '4':command = "00040$";break;
					case '5':command = "00050$";break;
					case '6':command = "00060$";break;
					default:command = "00010$";
				} 
				$.ajax({
					url:"send.php",
					type:"POST",
					data:"command="+command,
					success: function(string ){
						if(string=="false"){
							alert("KhÃ´ng tÃ¬m tháº¥y Ä‘á»‹a chá»‰ máº¡ng cá»§a Actor !");
						}
						else{
							$.ajax({
								url:"rxtatvan.php",
								type:"POST",
								data:"command="+string,
								success: function(bdata){
									alert(bdata);
								}
							});
						}
					}
				}); 
			});
		}
		else{
			alert("Báº¡n chÆ°a Ä‘Äƒng nháº­p hoáº·c khÃ´ng Ä‘Æ°á»£c phÃ©p Ä‘iá»u khiá»ƒn van!");
		}	
	});
	$('.icsensor').bind("contextmenu",function(e){
		$("#menusensor").hide();
		$("#menuval").hide();
		$("#menuactor").hide();
		$("#menubaochay").hide();
		$("#menu0").hide();
		macss = $(this).attr('id');
		$.ajax({
			url: "ttsensor.php",                           
			type: "POST",
			data: "id="+macss, 
		    success:function(data){
		    	var getData = $.parseJSON(data);				    
			    
			    if(getData.status == 0){				    
			    	$('#laydulieu').attr('title','no');
			    	$("#menu0").css('left',e.pageX);
				    $("#menu0").css('top',e.pageY);
			    	$("#menu0").show();
			    }
			    else{
			    	$("#idsensor").text('Sensor: '+getData.mac);
				    $("#nhietdo").text('Nhiá»‡t Ä‘á»™: '+getData.temp);
				    $("#doam").text('Äá»™ áº©m: '+getData.humi);
				    $("#nangluong").text('NÄƒng lÆ°á»£ng: '+getData.ener);
					$("#layanh").attr('title', getData.mac);
			    	$('#laydulieu').attr('title',getData.mac);
					
			    	
			    	$("#menusensor").css('left',e.pageX);
				    $("#menusensor").css('top',e.pageY);
			    	$("#menusensor").show();
			    }			   
		    } 
		});		
        return false;
 	});
    $("#laydulieu").click(function(e) {
    	$("#menusensor").hide();
        mac = $(this).attr('title');
        $.get("changestt.php", {macid: mac}).done(function(data) {
			//alert(data);           
			$.ajax({
				url:"send.php",
				type:"POST",
				data:"command="+mac+"000$",
				success: function(string ){
					if(string=="false"){
						alert("KhÃ´ng tÃ¬m tháº¥y Ä‘á»‹a chá»‰ máº¡ng cá»§a Actor !");
					}
					else{
						$.ajax({
							url:"laydulieu.php",
							type:"POST",
							data:"command="+string,
							success: function(data){
								if(data !="false"){
									var getData = $.parseJSON(data);
									alert("Nut mang: "+getData.mac+"\Dia chi mang: "+getData.netip+"\nNhiet do: "+getData.temp+"\nDo am: "+getData.humi+"\nNang luong: "+getData.ener);
								}
								else{
									alert("Chuc nang lay du lieu phan hoi.");
								}
							}
						});
					}
				}
			});
        });
    });
	
	$("#layanh").click(function(e) {
		$("#menusensor").hide();
		window.location= "ClickMap.php";
		
		
		});
	
	$("#battatca").click(function(e) {
		$("#menuactor").hide();
		var id = $(this).attr('title');
		if(isLevel==1){	
			$.get("change_van_no.php", {vanno: "F" }).done(function(data) {
				//alert(data);
				$.ajax({
					url:"send.php",
					type:"POST",
					data:"command="+id+"151$",
					success: function(string ){
						if(string=="false"){
							alert("Khong tim thay cac chua nang cua Actor !");
						}
						else{
							$.ajax({
								url:"battatca.php",
								type:"POST",
								success: function(data1){
									alert(data1);
								}
							});
						}
					}
				});
			});
		}
		else{
			alert("Báº¡n chÆ°a Ä‘Äƒng nháº­p hoáº·c khÃ´ng Ä‘Æ°á»£c phÃ©p Ä‘iá»u khiá»ƒn van!");
		}	
    });
	$("#tattatca").click(function(e) {
		$("#menuactor").hide();
		var id = $(this).attr('title');
		if(isLevel==1){	
			$.get("change_van_no.php", {vanno: "F" }).done(function(data) {
				//alert(data);
				$.ajax({
					url:"send.php",
					type:"POST",
					data:"command="+id+"150$",
					success: function(string ){
						if(string=="false"){
						}
						else{
							$.ajax({
								url:"tattatca.php",
								type:"POST",
								data:"command="+string,
								success: function(data1){
									alert(data1);
								}
							});
						}
					}
				});
			});
		}
		else{
			alert("Báº¡n chÆ°a Ä‘Äƒng nháº­p hoáº·c khÃ´ng Ä‘Æ°á»£c phÃ©p Ä‘iá»u khiá»ƒn van!");
		}
    });
    $("#reset").click(function(e) {
    	$("#menubaochay").hide();
    	var id = $(this).attr('title');
    	if(isLevel==1){
	    	$.get("change_reset.php",function(data){        	
	        	//alert(data);
	    		$.ajax({
	    			url:"send.php",
	    			type:"POST",
	    			data:"command="+id+"031$",
	    			success: function(string ){ 		
	    				$.ajax({
	    					url:"resetbc.php",
	    					type:"POST",
	    					data:"command="+string,
	    					success: function(data){
	    						alert(data);
	    					}
	    				});
	    			}
	    		});	    	
        	});    	
    	}
		else{
			alert("Ban chua dang nhap hoac khong duoc phep dieu khien van!");
		}
    });
	
    $("#mainpage").bind("contextmenu",function(e){
        return false;
 	});
 	$("#mainpage").bind("mousedown",function(e){
 		$("#menusensor").hide();
		$("#menuval").hide();
		$("#menuactor").hide();
		$("#menubaochay").hide();
		$("#menu0").hide();
 	});
});
</script>
</head>

<body>
	<link rel="stylesheet" type="text/css" href="map.css">
    <div id = "menubaochay" class="ctmenu">
<table>
	<tr><td class="menuitem0" id = "idactor_bc"></td></tr>
	<tr><td class="menuitem0" id = "level_bc"></td></tr>
	<tr><td class="menuitem" id = "reset">Reset</td></tr>
</table>
</div>
<div id = "menuactor" class="ctmenu">

<table>
	<tr><td class="menuitem" id = "idactor"></td></tr>
	<tr><td class="menuitem" id = "battatca">Bat tat cac van</td></tr>
    <tr><td class="menuitem" id = "tattatca">Tat tat car cac van</td></tr>
</table>
</div>
<div id = "menuval" class="ctmenu">
<table>
	<tr><td class="menuitem0" id = "idval"></td></tr>
	<tr><td class="menuitem0" id = "status"></td></tr>
	<tr><td class="menuitem" id = "batvan">Bat van</td></tr>
    <tr><td class="menuitem" id = "tatvan">Tat van</td></tr>
</table>   
</div>
<div id = "menu0" class="ctmenu">
<table>
	<tr><td class="menuitem0" ></td></tr>
	<tr><td class="menuitem0" id = "id0">Chua gia nhap mang</td></tr>
	<tr><td class="menuitem0" ></td></tr>
</table>   
</div>
<div id = "menusensor" class="ctmenu">
<table>
	<tr><td class="menuitem0" id = "idsensor"></td></tr>
	<tr><td class="menuitem0" id = "nhietdo"></td></tr>
	<tr><td class="menuitem0" id = "doam"></td></tr>
	<tr><td class="menuitem0" id = "nangluong"></td></tr>
	<tr><td class="menuitem" id = "laydulieu">Lay nhiet do, do am</td></tr>
    <tr><td class="menuitem" id = "layanh">Hien thi anh</td></tr>
    <tr><td class="menuitem" id = "laymbando">Hien thi ban do</td></tr>
</table>
</div>
<div id="wrap">
  	<div id="top">
    	<div id="ttlogin"></div> <br />
        <div id="banner">
	    <embed src="http://bannertudong.com/uploads/system/flash/20110503/view.swf" quality="high" bgcolor="#ffffff" wmode="transparent" menu="false" width="1000" height="250" name="Editor" align="middle" allowScriptAccess="always" flashVars='xml=http://bannertudong.com/uploads/user/20130904/19948/19948.xml?0' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
        </div>
        <div id = "menulogin" class="ctmenu">
           <table>
	           <tr><td class="menuitem" id = "tt"><a href="#">Chinh sua thong tin</a></td></tr>
	           <tr><td class="menuitem" id = "pass"><a href="change_pass.php"> Doi mat khau </a></td></tr>
               <tr><td class="menuitem" id = "out"> <a href="signout.php">Dang xuat</a></td></tr>
           </table>
        </div>
	<div id="menu">
		<ul>			
	      	<li ><a href="index.php"><span>Bo nhung</span></a></li>
	      	<li class="active"><a href="map_gps.php"><span>Ban do</span></a></li>
            <li><a href="draw.php"><span>Ve do thi</span></a></li>
	      	<li><a href="video.php"><span>Video</span></a></li>
	      	<li><a href="camera.php"><span>Camera</span></a></li>	      
            <li ><a href="truccanh.php"><span>Truc canh</span></a></li>
            <li ><a href="viewImage.php"><span>Image</span></a></li>
            <?php if(isset($_SESSION['login_status'])) 
					if($_SESSION['login_status']=='yes') {
						if($_SESSION['level'] == 1){
			?>
            <li><a href="manage.php"><span>Quan ly</span></a></li>
            <?php }}?>
	   	</ul>
	</div>
    </div>
    <div style="padding-bottom:170px"></div>
  	<div id="contentwrap">
    
    
 <!-- Ban do khu vuc vuon lan -->
    <div id="mainpage" class="dropdownSetting" >
   
      	<button class="dropbtnSetting buttonBC">Setting</button>
        <div class="dropdown-content-setting">
            <a href="#">Set center</a>
            <a href="#">Link 2</a>
            <a href="#">Link 3</a>
        </div>
      </div>
       <input  type="radio" value="GPS" name="map"  id="gps" checked="true">&nbsp;GPS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input   type="radio" value="Vuon Lan"  name="map" id="vuonlan" >&nbsp;Vuon lan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input  type="radio" value="Bao chay" name="map"  id="baochay">&nbsp;Bao chay
        <input onclick="clearMarkers();" type=button value="Hide Markers" class="button buttonLan">
        <input onclick="showMarkers();" type=button value="Show All Markers" class="button buttonLan">
        <input onclick="deleteMarkers();" type=button value="Delete Markers" class="button buttonLan">
</script>
 
</head> 
<body onLoad="loadmap()">
    <div id="div_unallocated_sensor" style="height:30px; "> </div>
    <div id="div_speed" style="height:20px; "> Toc do: </div>
    <div id="div_map" style="height:500px; "></div>
    <div id="div_chart" style="height:200px;"></div>
    <div id="div_chart_month" style="height:200px;"></div> 
    <button onclick="drawChartMonth();drawChart();"> Draw</button>
</body>
	<div id="bottom">
		<div id="footer">
	        <div id="fl_right">Copyright by <a href="#">Lab 411</a></div>
	        <div class="clear"></div>
		</div>
    </div>
</div>
<div id="contentbtm">
</div>
</div>
</body>

<script type="text/javascript">
 var map;
 var poly;
var iconSensorVl = 'https://www.google.com/mapfiles/ms/icons/green.png';
var iconSensorBc = 'https://www.google.com/mapfiles/ms/icons/red.png';
var iconSensorSleepMini = 'http://maps.google.com/mapfiles/kml/pal2/icon18.png';
var iconSensorSleep = 'http://www.google.com/mapfiles/ms/micons/lightblue.png';
var iconSensorSleep1 = 'http://www.google.com/mapfiles/ms/micons/blue.png';
var iconOBU =   'https://www.google.com/mapfiles/ms/micons/truck.png';
var iconTrucThang = 'https://www.google.com/mapfiles/ms/icons/helicopter.png';
var iconHiker = 'https://www.google.com/mapfiles/ms/icons/hiker.png';
 var markers = [];
 var ubuMarkers = [];
 var ubu;
 //var ubuMark[20];
 	function loadmap() {
		map = new google.maps.Map(document.getElementById('div_map'), {
			zoom: 16,
			center: new google.maps.LatLng(21.004896,105.843695),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		ubu = new google.maps.Marker({
        	position: new google.maps.LatLng(21.005126,105.842296),
        	map: map, 
			icon: iconTrucThang
    	});
		// This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
        	//updateSensor();
        });
		//updateSensor();
		poly = new google.maps.Polyline({
          strokeOpacity: 1.0,
		  strokeColor: '#FF0000',
          strokeWeight: 3
        });
        poly.setMap(map);
		/////////////Prediction
		var lineSymbol = {
			//path: google.maps.SymbolPath.CIRCLE,
			path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
			scale: 5,
			strokeColor: '#393'
        };

		///////////////////////////////tao doi tuong chuyen dong////////////////////////////		  
		var arrayData = [];
		$.ajax({
			url: "getPosition.php",                           
			type: "GET",
			async: false,
			data: "table=object_predicted",
			success:function(req){ 
				result = JSON.parse(req);
				$.each (result, function (key, item){
					var pos = new google.maps.LatLng(item['lat'],item['lng']);
					arrayData.push(pos);   
                });
			}	
		});		
		  /////////////////////////////////////////////////
		  // Create the polyline and add the symbol to it via the 'icons' property.
        var line = new google.maps.Polyline({
			path: arrayData,
			icons: [{
				icon: lineSymbol,
				offset: '100%'
			  }],
			map: map
        });
		animateCircle(line);
	}
	
	function addMarkerMac(location, mac,status) {
 		var infowindow = new google.maps.InfoWindow({maxWidth: 220, Height:300});
		var marker = new google.maps.Marker({
        position: location,
		draggable: true,
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
				infowindow.setContent('<iframe src="action.php?mac='+mac+'"></iframe>' +
									  '<a href="action.php?mac='+mac+'" type="button">Go to details</a>'
				);
				infowindow.open(map, marker);				
			}
		})(marker)); 
		
		//marker.addListener('click', function() {
		//	drawChart(mac,'day');	//sénsor mac ve theo ngay
        //  	drawChartMonth(mac,'year');
        //});
		$.ajax({
				url: "jsonSensor.php",                           
				type: "GET",
				async: false,
				data: "mac=" + mac,
				success:function(req){ 
					google.maps.event.addListener(marker, 'click', (function(marker) {
						return function() {
							infowindow.setContent(req + '<button onclick="drawAll('+mac+')" class="button button1">Draw Chart</button>');
							infowindow.open(map, marker);
							//updateSensor();
						}
					})(marker)); 
				}	
		});
		var obj = {
			"mac": mac,
			"mark": marker	
		}
		//alert(obj["mac"]);
		markers.push(obj);
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
	  		markers[i]["mark"].setMap(map);
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
	
	function detectedObject(mac,time){
		for (var i = 0; i < markers.length; i++) {
			if(markers[i]["mac"] == mac){
				var marker = markers[i]["mark"];
				marker.setAnimation(google.maps.Animation.BOUNCE);
				/*$.ajax({	//hien thi anh khi phat hien xam nhap
					url: "jsonSensor.php",                           
					type: "GET",
					async: false,
					data: "mac=" + mac,
					success:function(req){ 
						var infowindow = new google.maps.InfoWindow({
							content: 'Phát hiện xâm nhập tại node ' + mac+'lúc ' + time + '<br>'+req
						});
						infowindow.open(map, marker);
						setTimeout(function(){  marker.setAnimation(null); }, 5000);
					}	
				});*/
				var infowindow = new google.maps.InfoWindow({
          			content: 'Phát hiện xâm nhập tại node ' + mac+'lúc ' + time
        		});
				infowindow.open(map, marker);
				setTimeout(function(){  marker.setAnimation(null); }, 5000);
			}
		}
	}
	
	function stopObject(mac){
		for (var i = 0; i < markers.length; i++) {
			if(markers[i]["mac"] == mac){
				var marker = markers[i]["mark"];
			    marker.setAnimation(null);
				//alert( markers[i]["mac"]);	
			}
		}
	}
	
	function updateSensor(){
		deleteMarkers();
		$.ajax({
			url: "getPosition.php",                           
			type: "GET",
			data: "table=cdata",
			async: false,
			success:function(req){ 
				result = JSON.parse(req);
				$("#div_unallocated_sensor").empty();
				$("#div_unallocated_sensor").append('<strong>Node chưa cập nhật vị trí:</strong>');
				$.each (result, function (key, item){
					var lat = item['lat'];
					var lng = item['lng'];
					if(lat == null || lng == null){
						createInput(item["mac"]);
					}
                	var sensorPosition = new google.maps.LatLng(lat,lng);
					addMarkerMac(sensorPosition, item['mac'],item['status']);
                });
			}	
		});	
		$.get("getPosition.php","table=object",function(data){
			result = JSON.parse(data);
			var count = 0;
			$.each (result, function (key, item){
				count += 1;
				detectedObject(item['mac'],item['time']);
			});
			//if (count > 1) alert ("Thực hiện nội suy");
		});
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

<script language="javascript">	
	google.load("visualization", "1", {packages:["corechart"]});
	charts.setOnLoadCallback(drawChart);
	function drawChart(mac,type) {
		$.ajax({
			url: "drawChart.php",                           
			type: "GET",
			async: false,
			data: "mac=" + mac + "&type=" + type,
			success:function(req){ 
				result = JSON.parse(req);
				var arrayData = [['Tháng','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']];
				$.each (result, function (key, item){
					arrayData.push([item['day'],item['temp'],item['humi'],item['ener']]);   
                });
				//var data = google.visualization.arrayToDataTable([['Tháng','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']]);
				var data = google.visualization.arrayToDataTable(arrayData);
				var options = {
					title: 'Dữ liệu sensor '+mac+' trong 12 ngày gần đây',
					hAxis: {title: 'Sensor vườn lan', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('div_chart'));
				chart.draw(data, options);
			}	
		});			
	}
	
	function drawChartMonth(mac,type) {
		$.ajax({
			url: "drawChart.php",                           
			type: "GET",
			async: false,
			data: "mac=" + mac + "&type=" + type,
			success:function(req){ 
				result = JSON.parse(req);
				var arrayData = [['Tháng','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']];
				$.each (result, function (key, item){
					arrayData.push([item['month'],item['temp'],item['humi'],item['ener']]);   
                });
				//var data = google.visualization.arrayToDataTable([['Tháng','Nhiệt độ (ºC)','Độ ẩm (%)','Năng lượng (V)']]);
				var data = google.visualization.arrayToDataTable(arrayData);
				var options = {
					title: 'Dữ liệu sensor '+mac+' trong năm',
					hAxis: {title: 'Sensor vườn lan', titleTextStyle: {color: 'red'}}
				};
				var chart = new google.visualization.ColumnChart(document.getElementById('div_chart_month'));
				chart.draw(data, options);
			}	
		});		
	}
	
	function drawAll(mac){
		drawChart(mac,'day');
		drawChartMonth(mac,'year');
	}
	
	function createInput(mac){
		if(mac < 30) $("#div_unallocated_sensor").append('<button onclick="updateLatLng(&#39;'+mac+'&#39;)" class="button buttonLan">'+mac+'</button>');
		else $("#div_unallocated_sensor").append('<button onclick="updateLatLng(&#39;'+mac+'&#39;)" class="button buttonBC">'+mac+'</button>');
			
	}
	function updateLatLng(mac){
		var mac_str = mac.toString();
		lat = prompt("Cập nhật vị trí node " + mac_str +"\nLatitude: ");
		lng = prompt("Cập nhật vị trí node " + mac_str +"\nLongitude: ");
		//alert (lat + lng);
		if(lat!== null && lng !== null){
			$.ajax({
				url: "rx.php",                           
				type: "GET",
				async: false,
				data: "data=PS:0000" + mac_str + lat + lng,
				success:function(req){ 
					alert(req);
				}	
			});	
			updateSensor();
		}
	}
	
	function animateCircle(line) {
         var count = 0;
         window.setInterval(function() {
            count = (count + 1) % 200;
            var icons = line.get('icons');
            icons[0].offset = (count / 2) + '%';
			/*var infowind = new google.maps.InfoWindow({
          			content: 'duong'
        		});
			infowind.open(icons[0]);*/
			document.getElementById('div_speed').innerHTML = 'Speed: ' + count + 'm/s';
            line.set('icons', icons);
        }, 200);
	}
</script> 
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

</html>

<style>
.button {
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size:14px;
    margin: 4px 2px;
    cursor: pointer;
}
.buttonBC {
	border-radius: 50%;;
	background-color: #C03; /* Green */
}
.buttonLan {
	border-radius: 50%;
	background-color: #4CAF50;
}
.dropbtnSetting {
    background-color: #4CAF50;
    color: white;
    padding: 5px;
    font-size: 14px;
    border: none;
    cursor: pointer;
}

.dropdownSetting {
    position: relative;
    display: inline-block;
}

.dropdown-content-setting {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 120px;
    box-shadow: 0px 8px 8px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content-setting a {
    color: black;
    padding: 5px 5px;
    text-decoration: none;
    display: block;
}

.dropdown-content-setting a:hover {background-color: #f1f1f1}

.dropdownSetting:hover .dropdown-content-setting {
    display: block;
}

.dropdownSetting:hover .dropbtnSetting {
    background-color: #3e8e41;
}
</style>



