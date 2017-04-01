<table bgcolor="#99FFCC"><tr><td><font size="+1">
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
		?>.jpeg" height="150px" width="200px" onclick="takePhoto()"></img></td>
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
<?php	
	/*$tomorrow  = mktime(0, 0, 0, 6  , 20, 2013);
	$yesterday = mktime(0, 0, 0, date("d")-1, date("d"),   date("Y"));
	$month  = mktime(0, 0, 0, date("m") -1 ,   date("d") + 23,   date("Y"));
	$tomo = date('Y-m-d' , $month);
	$passTemp = "SELECT * FROM data_sensor WHERE mac='02'";
	$result = mysql_query($passTemp) or die(" Error in Selecting ");
	$sumTem = 0;
	$numNode = 0;
	while($row =mysql_fetch_assoc($result))
    {
		$date =  substr($row["time"],0,10); //echo "DATE:".$date."....Tomorrow:".date('Y-m-d' , $tomorrow)."<br>";
		if($date == date('Y-m-d' , $tomorrow))
		{
			$sumTem += $row["temp"];
			$numNode ++;
		}
	}
	$sumTem = $sumTem/$numNode;
	$result = 0;
	$sensor = "02";
	$val = "temp";
	$sql = "SELECT * FROM data_avg WHERE sensor='".$sensor."' and date LIKE '".$tomo."%'";
	$query = mysql_query($sql);
	
	function getDataSensorR($dd,$ss,$va)
	{
		$result = 0;
		$sql = "SELECT * FROM data_avg WHERE date LIKE '".$dd."%' and sensor='".$ss."'";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		if($row != 0)
		{
			$result = $row[$va];
		}
		return $result;
	}
	*/
	
?>
