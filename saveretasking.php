<?php
require 'dbconnect.php';
//$mac = $_POST['mac'];
$cmd = $_POST['cmd']; 
$command = "#".$mac.$cmd;   
mysql_query("insert into command values ('".$command."')");

mysqli_connect($connect);
?>