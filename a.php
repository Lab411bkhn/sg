<?php
require 'dbconnect.php';
$query = mysql_query("DELETE FROM data_sensor WHERE temp like '-%' or humi like '-%' or ener like '-%'");
	   if($query === FALSE) { 
			die(mysql_error()); // TODO: better error handling
		}
		else echo "OK"
?>