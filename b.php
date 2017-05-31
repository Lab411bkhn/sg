<?php
require 'dbconnect.php';
if(isset($_POST['data']) || isset($_GET['data'])){
	if(isset($_POST['data'])) $type = $_POST['data'];
	else if(isset($_GET['data']))$type = $_GET['data']; 
	echo $type;
}
?>