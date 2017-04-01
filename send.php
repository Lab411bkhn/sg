<?php
require 'dbconnect.php';
	$msg = $_POST['command'];
	$mac = substr($msg,0,2); 
	$state = substr($msg,2,3);   
	$sql="select netip from cdata WHERE mac='".$mac."'";
	$query=mysql_query($sql);
	if ($query){
		while($row = mysql_fetch_array($query)){
			$network_ip = $row['netip'];
			$command = $mac.$network_ip.$state."$";
			$sql1="SELECT * FROM command WHERE command LIKE '%".$command."%'";
			$query1 = mysql_query($sql1);
			$row_no = mysql_num_rows($query1);
			if($row_no == 0)
				mysql_query("INSERT into command values ('".$command."')");
			echo "Successful(".$command.")";
		}
	}
else echo "false";
//mysqli_close($connect);
?>