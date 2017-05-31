<table bgcolor="#99FFCC"><tr><td><font size="-1">
<?php
	require 'dbconnect.php';
	$mac = $_GET['mac'];
	//echo "Sensor ".$mac." ngày ".date('d/m/Y')."<br />";
	//echo "Sensor ".$mac."<br />";
	/*$sql = "SELECT * FROM cdata WHERE mac='".$mac."'";
	$result = mysql_query($sql) or die(" Error in Selecting 1");
    $row =mysql_fetch_assoc($result);*/
	
	$sql = "SELECT * FROM data_sensor WHERE mac = '".$mac."' ORDER BY STT DESC LIMIT 1";
	$result = mysql_query($sql) or die(" Error in Selecting 2");
	$row =mysql_fetch_array($result);
	echo "Sensor ".$mac;
?>
</font></td></tr>
    <tr onclick="getTempHumi()">
    	<td>
        <img src="http://www.maps.google.com/mapfiles/kml/pal4/icon42.png" height="20px" width="20px"/><font size="+1" color="red"><?php echo "&nbsp;&nbsp;".$row['temp']."&#186;C"?></font><font><?php echo "/".$row['humi']."%"?></font></img>
        </td>
    </tr> 
    <tr>
    	<td><img src="imagesensor/Sensor<?php echo $mac?>/
		<?php //lay ten image moi nhat
			$sql = "SELECT nameImgageLatest FROM cdata WHERE mac='".$mac."'";
			$result = mysql_query($sql) or die(" Error in Selecting ");
    		$row =mysql_fetch_assoc($result);
			echo $row['nameImgageLatest'];			
		?>.jpeg" height="90px" width="120px" onclick="takePhoto()"></img></td>
	</tr> 
</table>
<script>
	function getTempHumi(){
		alert ("CAp nhat nhiet do do am");
	}
	function takePhoto(){
		alert ("C:\\xampp\\htdocs\\sg\\imagesensor\\Sensor<?php echo $mac?>\\<?php //lay ten image moi nhat
			$sql = "SELECT nameImgageLatest FROM cdata WHERE mac=".$mac."";
			$result = mysql_query($sql) or die(" Error in Selecting ");
    		$row =mysql_fetch_assoc($result);
			echo $row['nameImgageLatest'];			
		?>.jpeg");
	}
</script>
