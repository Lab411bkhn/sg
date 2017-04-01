<?php	
//xu ly cac lenh
//Cau truc lenh gui sang:	- mac: dia chia mac sensor dap ung
//							- cmd: lenh
//							- cmdType: loai lenh (RET: lenh restasking)
/////////////////////////////////////////////////////////////////////////////////			
	require 'dbconnect.php';
	$mac = $_POST['mac'];
	/*$cmd = $_POST['cmd']; 
	$sql="select netip from cdata WHERE mac='".$mac."'";
	$query=mysql_query($sql);
	if ($query){
		while($row = mysql_fetch_array($query)){
			$network_ip = $row['netip'];
				$command = "#".$network_ip.$cmd;
				mysql_query("insert into command values ('".$command."')");
				echo "Successful";
		}
	}*/
	echo $mac;
?>