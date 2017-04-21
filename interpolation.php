<html>
<body>
	<script type="text/javascript" src = "jquery.js"></script>
	<p id="demo"></p>
</body>
</html> 

<script>
	$.get("getPosition.php","table=object",function(data){
		result = JSON.parse(data);
		interpolation3(result);
	});
	
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