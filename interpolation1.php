<?php 
require 'dbconnect.php';
if(isset($_GET['type'])){
	$type = $_GET['type'];
	$time = $_GET['time'];
	if($type == 'speed'){
?>
<script type="text/javascript" src = "jquery.js"></script>
<script>
	$.get("getPosition.php","table=object",function(data){
		result = JSON.parse(data);
		//document.write("DATA:"+data+"<br>	");
		//interpolation3(result);
		//var demo = [{"mac":"07","time":"16:44:40","lat":"1"},{"mac":"08","time":"16:44:41","lat":"3"},{"mac":"16","time":"16:44:42","lat":"5"},{"mac":"13","time":"16:44:43","lat":"9"},{"mac":"11","time":"16:44:44","lat":"12"}];
		var demo = [{"mac":"07","time":"16:44:40","lat":"1"},{"mac":"08","time":"16:44:41","lat":"2.25"},{"mac":"16","time":"16:44:42","lat":"3.75"},{"mac":"13","time":"16:44:43","lat":"4.25"}];
		//document.write(getDividedDifferences(result,'lat'));
		var tem = parseFloat(-0.6);
		//interpolation3(result);
		interpolation10points(result,10);
		//interpolationSpeedOneDirect(result,'lat',<?php echo $time;?>);
		//document.write("HD:" + newtonInterpolation(result,'lat',tem)+"<br>");
		//document.write("HD:" + newtonInterpolation(result,'lat',tem)+"<br>");
	});
	
	//tra ve mang gia tri cac ty hieu phuc vu cho tinh toan noi suy newton
	function getDividedDifferences(dataArray,type){
		var result = [];
		var dataA = JSON.parse(JSON.stringify(dataArray));		//copy array json
		var lengthArr = dataA.length;
		result.push(dataA[0][type]);
		for (var i=1;i<lengthArr;i++){
			for(var j=0;j<lengthArr-i;j++){
				dataA[j][type] = (dataA[j+1][type]-dataA[j][type])/(dataA[j+i].time-dataA[j].time); 
			}
			result.push(dataA[0][type]);
		}
		return result;
	}
	
	function newtonInterpolation(dataArray,type,deltaT){
		var dataArrayIn = JSON.parse(JSON.stringify(dataArray));		//copy array json
		////convert time
		for (var i=0;i<dataArrayIn.length;i++){
			var timeTemp = dataArrayIn[i].time.split(':');
			dataArrayIn[i].time = timeTemp[0]*3600 + timeTemp[1]*60 + parseFloat(timeTemp[2]);
		}
		/////sắp xếp
		for (var i=0;i<dataArrayIn.length-1;i++){		 
			var timeTemp1 = dataArrayIn[i].time;
			for (var j= i+1;j<dataArrayIn.length;j++){
				var timeTemp2 = dataArrayIn[j].time;
				if(timeTemp1>timeTemp2) {
					var temo = dataArrayIn[j];
					dataArrayIn[j] = dataArrayIn[i];
					dataArrayIn[i] = temo;
				}
			}
		}		
		//try { var divDiff = getDividedDifferences(dataArrayIn,type);} catch(err) {	document.write(err.message);
		var divDiff = getDividedDifferences(dataArrayIn,type);			
		var timeSample = dataArrayIn[dataArrayIn.length-1].time + deltaT ;	
		var result = divDiff[0], temp = 1;		
		for (var i=1;i<divDiff.length;i++){
			temp = temp * (timeSample - dataArrayIn[i-1].time);
			result = parseFloat(result) + parseFloat(divDiff[i]*temp);
		}
		return result;
	}	
	
	function interpolation10points(dataArrayInput,number){
		var dataArr = JSON.parse(JSON.stringify(dataArrayInput));
		var	sampleTime = 5;
		/*for (var k=0;k<dataArr.length;k++){
				document.write("dataArrayIn[i].lat=" + dataArr[k].lat+"--"+dataArr[k].lng+"<br>");
		}*/
		for (var j= 1; j<=number; j++){
			var timeSampl = parseFloat(sampleTime*j);
			var latTem = newtonInterpolation(dataArr,'lat',timeSampl);
			var lngTem = newtonInterpolation(dataArr,'lng',timeSampl);
			document.write(timeSampl+"Lat: " + latTem + " - Lng: "+lngTem+"<br>");
			//vx = interpolation3SpeedOneDirect(time0,dataArr[0].lat,time1,dataArr[1].lat,time2,dataArr[2].lat,timeSampl);
			//vy = interpolation3SpeedOneDirect(time0,dataArr[0].lng,time1,dataArr[1].lng,time2,dataArr[2].lng,timeSampl);
			//document.write("Vx: " + vx + " - Vy: "+vy+"<br>");
			//speed = sqrt(vx * vx + vy*vy);
			/*$.ajax({
				url:"tools/insertPosition.php",
				type:"POST",
				data:"lat="+latTem+"&lng="+lngTem+"&time="+timeSampl + "&speed="+speed,
				success: function(result){
					alert(result);
				}
			});*/
		}    		
	}
	
	function interpolationSpeed(dataArrayInput,time){
		var dataArr = JSON.parse(JSON.stringify(dataArrayInput));
		var vx = newtonInterpolationSpeed(dataArr,'lat',time);
		var vy = newtonInterpolationSpeed(dataArr,'lng',time);
		document.write(timeSampl+"Lat: " + latTem + " - Lng: "+lngTem+"<br>");
	}
	
	function interpolationSpeedOneDirect(dataArray,type,time){
		var dataArrayIn = JSON.parse(JSON.stringify(dataArray));		//copy array json	
		time = parseInt(time/5)*5;
		vx = parseFloat(newtonInterpolation(dataArrayIn,'lat',time+5) - newtonInterpolation(dataArrayIn,'lat',time-5))/10;
		vy = parseFloat(newtonInterpolation(dataArrayIn,'lng',time+5) - newtonInterpolation(dataArrayIn,'lng',time-5))/10;
		var speed = Math.sqrt(vx * vx + vy*vy);
		document.write(speed);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function interpolation(dataArray){
		document.write(dataArray[0].lat);
		count = 0;
		$.each (dataArray, function (key, item){
			count += 1;
			//document.write((item['mac']));
		});
		//document.write(count);
	}
	
	/* lat - 
	*/
	function interpolation2(dataArray){
		var sampleTime = 5;	//thoi gian lay gia tri du doan cach nhau 5s
		var timeTemp0 = dataArray[0].time.split(':');
		var timeTemp1 = dataArray[1].time.split(':');
		var time0 = timeTemp0[0]*3600 + timeTemp0[1]*60 + parseFloat(timeTemp0[2]);
		var time1 = timeTemp1[0]*3600 + timeTemp1[1]*60 + parseFloat(timeTemp1[2]);
		var deltaTime = time0 - time1;
		$.ajax({
				url:"tools/insertPosition.php",
				type:"POST",
				data:"lat="+dataArray[1].lat+"&lng="+dataArray[1].lng+"&time="+time1,
				success: function(data){
				}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[0].lat+"&lng="+dataArray[0].lng+"&time="+time0,
			success: function(data){
			}
		});
		var lat = 0, lng = 0;
		for (var i=1; i<11; i = i + 1){
			var timeSampl = sampleTime*i;
			lat = parseFloat(dataArray[0].lat) + (parseFloat(dataArray[0].lat) - parseFloat(dataArray[1].lat))*timeSampl/deltaTime;
			lng = parseFloat(dataArray[0].lng) + (parseFloat(dataArray[0].lng) - parseFloat(dataArray[1].lng))*timeSampl/deltaTime;
			var timeSampl2 = parseFloat(time0) + parseFloat(timeSampl);
			alert(lat +"|"+lng+"|Time:" + timeSampl2);
			//alert ("lat:" + lat + "\nlng:" + lng+"\mTime:" + timeSampl);
			$.ajax({
				url:"tools/insertPosition.php",
				type:"POST",
				data:"lat="+lat+"&lng="+lng+"&time="+timeSampl2,
				success: function(data){
				}
			});
		}
		document.write(time1);
		document.write(time0+'\n');
		document.write(timeSampl2+'\n');
	}l
	
	function interpolation3(dataArray){
		var sampleTime = 5;	//thoi gian lay gia tri du doan cach nhau 5s		
		for (var i=0;i<2;i++){		//sắp xếp 
			var timeTemp1 = dataArray[i].time.split(':');
			var tim1 = timeTemp1[0]*3600 + timeTemp1[1]*60 + parseFloat(timeTemp1[2]);
			for (var j= i+1;j<3;j++){
				var timeTemp2 = dataArray[j].time.split(':');
				var tim2 = timeTemp2[0]*3600 + timeTemp2[1]*60 + parseFloat(timeTemp2[2]);
				if(tim1>tim2) {
					var temo = dataArray[j];
					dataArray[j] = dataArray[i];
					dataArray[i] = temo;
				}
			}
		}
		
		var timeTemp0 = dataArray[0].time.split(':');
		var timeTemp1 = dataArray[1].time.split(':');
		var timeTemp2 = dataArray[2].time.split(':');
		var time0 = timeTemp0[0]*3600 + timeTemp0[1]*60 + parseFloat(timeTemp0[2]);
		var time1 = timeTemp1[0]*3600 + timeTemp1[1]*60 + parseFloat(timeTemp1[2]);
		var time2 = timeTemp2[0]*3600 + timeTemp2[1]*60 + parseFloat(timeTemp2[2]);
		document.write(dataArray[0].time+"|"+dataArray[1].time+"|"+dataArray[2].time+"<br>");
		document.write(time0+"|"+time1+"|"+time2);
		/*$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[0].lat+"&lng="+dataArray[0].lng+"&time="+time0,
			success: function(data){
			}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[1].lat+"&lng="+dataArray[1].lng+"&time="+time1,
			success: function(data){
			}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[2].lat+"&lng="+dataArray[2].lng+"&time="+time2,
			success: function(data){
			}
		});*/
		var lat = 0, lng = 0, speed = 0, vx = 0, vy = 0;
		for (var i=1; i<11; i = i + 1){
			var timeSampl = parseFloat(time2) + sampleTime*i;
			lat = interpolation3Value(time0,dataArray[0].lat,time1,dataArray[1].lat,time2,dataArray[2].lat,timeSampl);
			lng = interpolation3Value(time0,dataArray[0].lng,time1,dataArray[1].lng,time2,dataArray[2].lng,timeSampl);
			vx = interpolation3SpeedOneDirect(time0,dataArray[0].lat,time1,dataArray[1].lat,time2,dataArray[2].lat,timeSampl);
			vy = interpolation3SpeedOneDirect(time0,dataArray[0].lng,time1,dataArray[1].lng,time2,dataArray[2].lng,timeSampl);
			var speed = Math.sqrt(vx * vx + vy*vy);
			document.write("Vx: " + vx + " - Vy: "+vy+" - Speed:"+speed+"<br>");
			/*$.ajax({
				url:"tools/insertPosition.php",
				type:"POST",
				data:"lat="+lat+"&lng="+lng+"&time="+timeSampl + "&speed="+speed,
				success: function(data){
				// do nothing here
				}
			});*/
		}    
	}
	
	function interpolation4(dataArray){
		var sampleTime = 5;	//thoi gian lay gia tri du doan cach nhau 5s		
		for (var i=0;i<3;i++){
			var timeTemp1 = dataArray[i].time.split(':');
			var tim1 = timeTemp1[0]*3600 + timeTemp1[1]*60 + parseFloat(timeTemp1[2]);
			for (var j= i+1;i<4;i++){
				var timeTemp2 = dataArray[j].time.split(':');
				var tim2 = timeTemp2[0]*3600 + timeTemp2[1]*60 + parseFloat(timeTemp2[2]);
				if(tim1>tim2) {
					var temo = dataArray[j];
					dataArray[j] = dataArray[i];
					dataArray[i] = temo;
				}
			}
		}
		
		var timeTemp0 = dataArray[0].time.split(':');
		var timeTemp1 = dataArray[1].time.split(':');
		var timeTemp2 = dataArray[2].time.split(':');
		var timeTemp3 = dataArray[3].time.split(':');
		var time0 = timeTemp0[0]*3600 + timeTemp0[1]*60 + parseFloat(timeTemp0[2]);
		var time1 = timeTemp1[0]*3600 + timeTemp1[1]*60 + parseFloat(timeTemp1[2]);
		var time2 = timeTemp2[0]*3600 + timeTemp2[1]*60 + parseFloat(timeTemp2[2]);
		var time3 = timeTemp3[0]*3600 + timeTemp3[1]*60 + parseFloat(timeTemp3[2]);
		document.write(dataArray[0].time+"|"+dataArray[1].time+"|"+dataArray[2].time+dataArray[3].time+"<br>");
		document.write(time0+"|"+time1+"|"+time2+"|"+time3);
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[0].lat+"&lng="+dataArray[0].lng+"&time="+time0,
			success: function(data){
			}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[1].lat+"&lng="+dataArray[1].lng+"&time="+time1,
			success: function(data){
			}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[2].lat+"&lng="+dataArray[2].lng+"&time="+time2,
			success: function(data){
			}
		});
		$.ajax({
			url:"tools/insertPosition.php",
			type:"POST",
			data:"lat="+dataArray[3].lat+"&lng="+dataArray[3].lng+"&time="+time3,
			success: function(data){
			}
		});
		var lat = 0, lng = 0;
		for (var i=1; i<11; i = i + 1){
			var timeSampl = parseFloat(time2) + sampleTime*i;
			lat = interpolation3Value(time0,dataArray[0].lat,time1,dataArray[1].lat,time2,dataArray[2].lat,time3,dataArray[3].lat,timeSampl);
			lng = interpolation3Value(time0,dataArray[0].lng,time1,dataArray[1].lng,time2,dataArray[2].lng,time3,dataArray[3].lng,timeSampl);
			//document.write(lat +'|'+lng+'|'+timeSampl+'<br>');
			//alert(lat +"|"+lng+"|Time:" + timeSampl2);
			//alert ("lat:" + lat + "\nlng:" + lng+"\mTime:" + timeSampl);
			$.ajax({
				url:"tools/insertPosition.php",
				type:"POST",
				data:"lat="+lat+"&lng="+lng+"&time="+timeSampl,
				success: function(data){
					
				}
			});
		}
	}
	
	function interpolation3Value(t0,val0,t1,val1,t2,val2,t3){ //noi suy gia tri val3 ung voi gia tri t3
		var p0 = parseFloat(t3 - t1)*parseFloat(t3-t2)/(parseFloat(t0-t1)*parseFloat(t0-t2));
		var p1 = parseFloat(t3 - t0)*parseFloat(t3-t2)/(parseFloat(t1-t0)*parseFloat(t1-t2));
		var p2 = parseFloat(t3 - t0)*parseFloat(t3-t1)/(parseFloat(t2-t0)*parseFloat(t2-t1));
		var val3 = p0*parseFloat(val0) + p1*parseFloat(val1) + p2*parseFloat(val2);
		return val3;
	}
	
	function interpolation3SpeedOneDirect(t0,val0,t1,val1,t2,val2,t3){ //noi suy gia tri vận tốc ung voi gia tri t3
		var p0 = parseFloat(2*t3 - t1 - t2)/((parseFloat(t0-t1)*parseFloat(t0-t2)));
		var p1 = parseFloat(2*t3 - t0 - t2)/((parseFloat(t1-t0)*parseFloat(t1-t2)));
		var p2 = parseFloat(2*t3 - t0 - t1)/((parseFloat(t2-t0)*parseFloat(t2-t1)));
		var val3 = p0*parseFloat(val0) + p1*parseFloat(val1) + p2*parseFloat(val2);
		return val3;
	}
	
	function interpolation4Value(t0,val0,t1,val1,t2,val2,t3,val3,t4){ //noi suy gia tri val3 ung voi gia tri t3
		var p0 = parseFloat(t4 - t1)*parseFloat(t4-t2)*parseFloat(t4-t3)/(parseFloat(t0-t1)*parseFloat(t0-t2)*parseFloat(t0-t3));
		var p1 = parseFloat(t4 - t0)*parseFloat(t4-t2)*parseFloat(t4-t3)/(parseFloat(t1-t0)*parseFloat(t1-t2)*parseFloat(t1-t3));
		var p2 = parseFloat(t4 - t0)*parseFloat(t4-t1)*parseFloat(t4-t3)/(parseFloat(t2-t0)*parseFloat(t2-t1)*parseFloat(t2-t3));
		var p3 = parseFloat(t4 - t0)*parseFloat(t4-t1)*parseFloat(t4-t2)/(parseFloat(t2-t0)*parseFloat(t2-t1)*parseFloat(t2-t2));
		var val4 = p0*parseFloat(val0) + p1*parseFloat(val1) + p2*parseFloat(val2) + p3*parseFloat(val3);
		return val4;
	}		
</script>
<script type="text/javascript">
	$(document).ready(function(){
		document.write(getPositionObject());
	});
</script>

<?php
	}
}
?>