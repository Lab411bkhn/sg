﻿<?php
require 'dbconnect.php';
$id_now = $_POST['id_now'];
//$id_now=$_POST['id_now'];
$bantin = "";
$sql1="SELECT bantin FROM bantin WHERE STT='".$id_now."'";
$query1 =mysql_query($sql1);

while( $row1 = mysql_fetch_array($query1)){
	$result = $row1['bantin'];	
	if(strpos ($result,"#D") !== false)
	{
		$len = strlen($result) - 1;
		$time = substr($result,3,$len);
		if($time =='0')
		{
			$bantin = "Thời gian đáp ứng lấy dữ liệu sensor < 1 giây <br />";
			}
		else
		{
			$bantin = "Thời gian đáp ứng lấy dữ liệu sensor ".$time." giây <br />";
		}
		if($result['2'] == 'A')
			$bc = 0;
		else
			$bc = 1;
		}
	else if(strpos ($result,"#F") !== false)
	{
		$len = strlen($result) - 1;
		$time = substr($result,2,$len);
		if($time == '0')
		{
			$bantin = "Thời gian đáp ứng cảnh báo cháy < 1 giây <br />";

			}
		else
		{
			$bantin = "Thời gian đáp ứng cảnh báo cháy ".$time." giây <br />";
		}
		$bc = 1;
		}
	else if(strpos ($result,"#T") !== false)
	{
		$len = strlen($result) - 1;
		$time = substr($result,2,$len);
		if($time == '0')
		{
			$bantin = "Thời gian đáp ứng tưới cây tự động < 1 giây <br />";
			}
		else
		{
			$bantin = "Thời gian đáp ứng tưới cây tự động ".$time." giây <br />";
		}
		$bc = 0;
		}
	else if(strpos ($result,"#C") !== false)
	{
		$len = strlen($result) - 1;
		$time = substr($result,3,$len);
		if($time == '0')
		{
			$bantin = "Thời gian đáp ứng điều khiển actor < 1 giây <br />";
			}
		else
		{
			$bantin = "Thời gian đáp ứng điều khiển actor ".$time." giây <br />";
		}
		if($result['2'] == 'A')
			$bc = 0;
		else
			$bc = 1;
		}
	else if( strpos ($result,"#RD") !== false){	//#RD:NNNNMMDDDDDDDDEEEE	
		$network_ip= substr($result,4,4);
		$mac = substr($result,8,2);
		
		$temp_16 = substr($result,10,4);
		$temp_10 = base_convert($temp_16, 16, 10);
		$tempreture = $temp_10*0.01-39.6;
		
		$humidity_16 = substr($result,14,4);
		$humidity_10 = base_convert($humidity_16,16,10);
		$h1 = 0.0367*$humidity_10-0.0000015955*$humidity_10*$humidity_10- 2.0468;
		$humidity = ($tempreture - 25)*(0.01+0.00008*$humidity_10) + $h1;
		
		$EE_16 = substr($result,18,4);
		$EE_10 = base_convert($EE_16,16,10);
		$energy =(0.78/((doubleval($EE_10)/4096)));
		
		
		$bantin= $bantin."<b>Bản tin thông tin node mạng theo yêu cầu tại node ".$mac."</b><br/>";
		$bantin= $bantin."Địa chỉ: ".$network_ip."<br />";
		$bantin= $bantin."Địa chỉ MAC: ".$mac."<br />";
		$bantin= $bantin."Nhiệt độ: ".$tempreture."<br />";
		$bantin= $bantin."Độ ẩm : ".$humidity."<br />";
		$bantin= $bantin."Năng lượng : ".$energy."<br />";
		
		if ('29' < $mac && $mac< '40') {
			$bc = 1;
		}else $bc = 0;
	}
	
	else if( strpos ($result,"#AD") !== false){		
		$network_ip= substr($result,4,4);
		$mac = substr($result,8,2);
		
		$temp_16 = substr($result,10,4);
		$temp_10 = base_convert($temp_16, 16, 10);
		$tempreture = $temp_10*0.01-39.6;
		
		$humidity_16 = substr($result,14,4);
		$humidity_10 = base_convert($humidity_16,16,10);
		$h1 = 0.0367*$humidity_10-0.0000015955*$humidity_10*$humidity_10- 2.0468;
		$humidity = ($tempreture - 25)*(0.01+0.00008*$humidity_10) + $h1;
		
		$EE_16 = substr($result,18,4);
		$EE_10 = base_convert($EE_16,16,10);
		$energy =(0.78/((doubleval($EE_10)/4096)));
		
		$bantin= $bantin."<b>Bản tin thông tin node mạng định kỳ ".$mac."</b><br/>";
		$bantin= $bantin."Địa chỉ mạng : ".$network_ip."<br />";
		$bantin= $bantin."Địa chỉ MAC: ".$mac."<br />";
		$bantin= $bantin."Nhiệt độ : ".$tempreture."<br />";
		$bantin= $bantin."Độ ẩm : ".$humidity."<br />";
		$bantin= $bantin."Năng lượng : ".$energy."<br />";


		if ('29' < $mac && $mac< '40') {
			$bc = 1;
		}else $bc = 0;
	}
	
	else if(strpos($result,"#JN:")!==false){
		$network_ip = substr ($result,4,4);
		$mac = substr ($result,8,2);
		$node_type = substr ($result,10,2);
		if("01"<=$mac && $mac < "A0"){
			$bantin= $bantin."<b>Bản tin gia nhập mạng ....</b><br/>";			
			$bantin= $bantin."Sensor ".$mac." đã gia nhập vào mạng <br />";
		}
		else{
			$bantin= $bantin."<b>Bản tin thông báo trạng thái Actor ....</b><br/>";			
			$bantin= $bantin."Actor ".$mac." vẫn trong mạng <br />";
			
		}
		$bantin= $bantin."Địa chỉ MAC : ".$mac."<br />";
		$bantin= $bantin."Địa chỉ mạng : ".$network_ip."<br />";		
		if ('29' < $mac && $mac< '40' || $mac =="B1") { 
			$bc = 1;
		}else $bc = 0;
	}


	else if(strpos($result, "#PS:") !== false){ //#PS:MMNNNNVVVVVVVVVVKKKKKKKKKK
		$mac = substr($result,4,2); //MM
		$network_ip = substr($result,6,4);
		$VD = substr($result,10,10);	//Vi do
		$KD = substr($result,20,10);	//kinh do
		
		$bantin= $bantin."<b>Bản tin toạ độ tại node ".$mac."</b><br/>";
		$bantin= $bantin."Địa chỉ: ".$network_ip."<br />";
		$bantin= $bantin."Địa chỉ MAC: ".$mac."<br />";
		$bantin= $bantin."Vĩ độ: ".$VD."<br />";
		$bantin= $bantin."Kinh độ : ".$KD."<br />";
		
		if ('29' < $mac && $mac< '40' || $mac =="B1") { 
			$bc = 1;
		}else $bc = 0;
	}

	else if (strpos($result, "#RC") !== false){ // #RI:NNNNMMD1D2D3D4 D5D6D7D8..... 
		$network_ip = substr($result, 4, 4);//NNNN
		$mac = substr($result, 8, 2);//MM
		$data = substr($result, 16); // D1D2D3D4D5D6D7D8......
		if("01"<=$mac && $mac < "A0"){
			$bantin = $bantin."<b> Bản tin yêu cầu hiển thị ảnh của sensor ...</b></br>";	
			$sql2 = "INSERT INTO camera(image, mac) VALUES ('".$data."', '".$mac."')";
			mysql_query($sql2);		
			$bantin = $bantin."Du Lieu Anh : ".$data."<br/>";	
		}
		if ('29' < $mac && $mac< '40' || $mac =="B1") { 
			$bc = 1;
		}
		else $bc = 0;
	}
	
	else if(strpos($result,"SN") !== false){
     	$network_ip = substr($result,4,4);
     	$mac = substr($result,8,2);
     	$state_node = substr($result,10,2);
		
		$bantin= $bantin."<b>Bản tin cảnh báo tại node mạng ".$mac."</b><br/>";
		
		
     	if($state_node == "02"){
     		$bantin= $bantin."Phát hiện cảnh báo cháy tại node: ";     		
		}
     	elseif($state_node == "04"){
     		$bantin= $bantin."Phát hiện xâm nhập tại node: ";
     	}
		elseif($state_node == "03"){
     		$bantin= $bantin."Phát hiện hết năng lượng tại node: ";
     	}
		elseif($state_node == "07"){
     		$bantin= $bantin."Canh bao tại camera ";
     	}
		elseif($state_node == "06"){
     		$bantin= $bantin."Phát hiện xâm nhập tại Camera: ";
     	}
     	$bantin= $bantin.$mac."<br />";
     	$bantin= $bantin."Địa chỉ mạng node:".$network_ip."<br />";
     	
     	if ('29' < $mac && $mac< '40') {
			$bc = 1;
		}else $bc = 0;
	}	
	
	else if(strpos($result,"OK") !== false){//#OK:NNNNMM8x,1x
		$network_ip = substr($result, 4,4);
		$stt_16 = substr($result, 10,2);	
		$stt_10 = base_convert($stt_16, 16, 10);
		$mac = substr($result,8,2);

		if ($mac == '00'){
			$bc = 0;
			if ($stt_10 > 128 && $stt_10 < 170){
				$val = $stt_10 - 128;
				if ($val == 15){
					$bantin = $bantin."Tất cả các van đã bật thành công.</b><br />";
				}
				else {
					$bantin = $bantin."Van số ".$val." đã bật thành công.</b><br />";
				}
			}
			if (64 < $stt_10 && $stt_10 < 128){
				
			}
			if ($stt_10 < 64){
				$val = $stt_10;
				if ($val == 15){
						$bantin = $bantin."Tất cả các van đã tắt thành công.</b><br />";
					}
					else {
						$bantin = $bantin."Van số ".$val." đã tắt thành công.</b><br />";
					}
				}
		}
		if ($mac == 'B1'){
			$bc = 1;
			if($stt_10 <170)
			{
			$trangthai = $stt_10 - 128;
			$bantin = $bantin."Đã bật cảnh báo mức ".$trangthai." tại đồng hồ báo cháy.</b><br />";
			}
		}
	}
	
	else if(strpos($result,"VL") !== false){//#SL:MM
		$bc = 1;
    	$mac = substr($result,4,2);
     	$bantin= $bantin."<b>Node số '".$mac."' đã vào trạng thái ngủ.</b><br>";     	
	}
	else {
		$bc = 1;
		$bantin = $bantin."Ban tin khong xac dinh: ".$row1['bantin']."<br>";	
	}
	

	$member = array('bc' => $bc
			,'bantin' => $bantin);
	echo json_encode($member);
	die;
}
	//mysql_close($connect);
?>