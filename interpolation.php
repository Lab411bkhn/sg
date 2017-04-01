<?php
require 'dbconnect.php';
?>
<html>
<body>
	<script type="text/javascript" src = "jquery.js"></script>
	<p id="demo"></p>
</body>
</html> 

<script>
	$.get("getPosition.php","table=object",function(data){
		result = JSON.parse(data);
		interpolation2(result);
	});
	
	function interpolation(dataArray){
		document.write(dataArray[0].time);
		count = 0;
		$.each (dataArray, function (key, item){
			count += 1;
			//document.write((item['mac']));
		});
		//document.write(count);
	}
	
	function interpolation2(dataArray){
		var timeTemp = dataArray[0].time.split(':');
		var time0 = timeTemp[0]*360 + timeTemp[1]*60 + timeTemp[2];
		document.write(time0);
	}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		document.write(getPositionObject());
	});
</script>