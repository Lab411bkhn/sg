<?php
session_start();
require 'dbconnect.php';
require('SampleImage.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>ViewImage</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="map1.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	#top_page{
		width:980px;
		height:350px;
		
		}
	.slide{
	cursor:pointer;
	}
	#bottom_page{
	width: 960px;
	height: auto;
	background: #6E8B3D;
	margin: 10px;
	position: relative;
	padding-top: 10px;
		}
	#allview{
	display:none;
		
		}
	
	#left_con{
		width: 300px;
		height: 300px;
		float: left;
    	overflow: auto; 
		
		}
	#right_con{
	width: 500px;
	
	height: 300px;
	float: right;
	overflow: auto; 
}
.log4{
	border-bottom: solid 1px #b0bb88;
	display: block;
	font: bold 21px Comfortaa;
	padding-bottom: 10px;
	color: #212713;
	margin-top: 50px;
}
.log3{
	border-bottom: solid 1px #b0bb88;
	display: block;
	font: bold 30px Comfortaa;
	padding-bottom: 10px;
	color: #212713;
	float: right;
}

</style>
<script type="text/javascript" src = "jquery.js"></script>
<script language="javascript">
        //*******************************
	     $(document).ready(function(e) {
         var isLogin='<?php if(isset($_SESSION['login_status'])){if($_SESSION['login_status']=='yes')echo 'yes';else echo 'no';}else    echo 'no';?>';
	     var isLevel='<?php if(isset($_SESSION['level'])){echo $_SESSION['level'];}?>';
	     if(isLogin=='yes'){			 
		//var content = "<table><tr><td><a href='signout.php'>Ðăng xuất</a></td><td></td></tr></table>";
		var content = "<table><tr><td><i>Xin chào bạn </i><b> [ </b><u id='log'><?php if(isset($_SESSION['login_status'])){ echo $_SESSION['user'];} ?></u><b> ] </b></td><td></td></tr></table>";
		$('#ttlogin').append(content);
	}
	else{
		var content = "<table><tr><td> <a href='register.php'>Đăng ký</a>&nbsp&nbsp&nbsp&nbsp<a href='login.php'>Đăng nhập</a></td></td></tr></table>";
		$('#ttlogin').append(content);
	}
	
	$("#log").click(function(e) {
		$("#menulogin").css('left',e.pageX - 150);
		$("#menulogin").css('top',e.pageY + 10);
        $("#menulogin").show();
    });
	
	$("#txtbutton").click(function(e) {
		$("#allview").slideToggle("fast");
		});
	
	$(document).bind("mouseup",function(e){
		$("#menulogin").hide();
		});
    });
</script>
	<script type="text/javascript" src="js/jquery.jmslideshow.js"></script>
<?php
$j=0;
while($j<30)
{
 ?>   
    <script type="text/javascript">
$(document).ready(function(){			
	$('<?php print '#lvd'.$j; ?>').click(function(){
		$('<?php print '#pvd'.$j ?>').slideToggle("fast");
        
	});				
});
		</script>
        <?php 
        $j++;
        } ?>

</head>

<body>
<form method="POST" name="video">
   
<div id="wrap">
  	<div id="top">
    	<div id="ttlogin"></div> <br />
        <div id="banner">
	    <embed src="http://bannertudong.com/uploads/system/flash/20110503/view.swf" quality="high" bgcolor="#ffffff" wmode="transparent" menu="false" width="1000" height="250" name="Editor" align="middle" allowScriptAccess="always" flashVars='xml=http://bannertudong.com/uploads/user/20150818/33399/33399.xml?0' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
        </div>
        <div id = "menulogin" class="ctmenu">
           <table>
	           <tr><td class="menuitem" id = "tt"><a href="">Chỉnh sửa thông tin</a></td></tr>
	           <tr><td class="menuitem" id = "pass"><a href="change_pass.php"> Đổi mật khẩu </a></td></tr>
               <tr><td class="menuitem" id = "out"> <a href="signout.php">Đăng xuất</a></td></tr>
           </table>
        </div>
	<div id="menu">
		<ul>			
	      	<li ><a href="index.php"><span>Bo nhúng</span></a></li>
	      	<li ><a href="mapvl.php"><span>Bản đồ</span></a></li>
            <li><a href="draw.php"><span>Vẽ đồ thị</span></a></li>
	      	<li><a href="video.php"><span>Video</span></a></li>
	      	<li><a href="camera.php"><span>Camera</span></a></li>	      
            <li ><a href="truccanh.php"><span>Trực canh</span></a></li>
            <li class="active"><a href="viewImage.php"><span>Image</span></a></li>
            <?php if(isset($_SESSION['login_status'])) 
					if($_SESSION['login_status']=='yes') {
						if($_SESSION['level'] == 1){
			?>
            <li><a href="manage.php"><span>Quản lý</span></a></li>
            <?php }}?>
	   	</ul>
	</div>
    </div>
    <div style="padding-bottom:170px"></div>

  	<div id="contentwrap">
    
 <!-- Ban do khu vuc vuon lan -->
    <div id="mainpage">
		<div id = "top_page" >
	
    <?php
		
		/*****************create image*********************************/
		
		$sql1="SELECT image, mac FROM camera ORDER BY id DESC LIMIT 1";
		$query =mysql_query($sql1);
		$row1 = mysql_fetch_array($query);
		$data = $row1['image'];
		$mac = 	$row1 ['mac'];
		$binary=pack('H*', str_replace(' ', '', $data));
		file_put_contents("camerasensor.jpeg", $binary);
		
		$sql= "INSERT INTO camera1(img, mac) VALUES ('".$data."', '".$mac."')";
		mysql_query($sql);
		
	?>
	<script type="text/javascript">
		function checkOther(select){
			$size = select[select.selectedIndex].value;
			if( select[select.selectedIndex].value=="1" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(100);
					 $image1->save('cam.jpeg');  
				?>	
				$("#imageid").attr("src","cam.jpeg");
			}
			if( select[select.selectedIndex].value=="2" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(50);
					 $image1->save('cam1.jpeg');  
				?>	
				$("#imageid").attr("src","cam1.jpeg");
			}
			if( select[select.selectedIndex].value=="3" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(75);
					 $image1->save('cam2.jpeg');  
				?>	
				$("#imageid").attr("src","cam2.jpeg");
			}
			if( select[select.selectedIndex].value=="4" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(125);
					 $image1->save('cam3.jpeg');  
				?>	
				$("#imageid").attr("src","cam3.jpeg");
			}
			if( select[select.selectedIndex].value=="5" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(150);
					 $image1->save('cam4.jpeg');  
				?>	
				$("#imageid").attr("src","cam4.jpeg");
			}
			if( select[select.selectedIndex].value=="6" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(175);
					 $image1->save('cam5.jpeg');  
				?>	
				$("#imageid").attr("src","cam5.jpeg");
			}
			if( select[select.selectedIndex].value=="7" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(200);
					 $image1->save('cam6.jpeg');  
				?>	
				$("#imageid").attr("src","cam6.jpeg");
			}
			if( select[select.selectedIndex].value=="8" ){
				<?php
					$image1 = new SimpleImage();
					$image1->load('camerasensor.jpeg');
					$image1->scale(250);
					 $image1->save('cam7.jpeg');  
				?>	
				$("#imageid").attr("src","cam7.jpeg");
			}
			
			
		}
	
	</script>
    
        
<script type="text/javascript" 
src="http://maps.googleapis.com/maps/api/js?sensor=false&language=vi&key=AIzaSyCygRXb-HJURc5BaMAcdBXrf_q1va_Q2pc"></script>
<script type="text/javascript">
var map;
function initialize() {
      var myLatlng = new google.maps.LatLng(21.0194672,105.7913715);
      var myOptions = {
    zoom: 10,
    center: myLatlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
}
map = new google.maps.Map(document.getElementById("div_id"), myOptions); 
  // Bi?n text ch?a n?i dung s? du?c hi?n th?
var text;
text= "<b style='color:#00F' " + 
         "style='text-align:center'>Cúc Phương - Ninh Bình!<br />";
   var infowindow = new google.maps.InfoWindow(
    { content: text,
        size: new google.maps.Size(100,50),
        position: myLatlng
    });
       infowindow.open(map);    
    var marker = new google.maps.Marker({
      position: myLatlng, 
      map: map,
   //   title:"Lab411!"
  });
}
</script>
</head>
<body onLoad="initialize()">
    <div id="div_id" style="height:500px; width:600px"></div>
</body>
		
		
	<div id="bottom">
		<div id="footer">
	        <div id="fl_right">Copyright by <a href="#">Lab 411</a></div>
	        <div class="clear"></div>
		</div>
    </div>
</div>
<div id="contentbtm"></div>
</div>
</body>
</html>


<?php
//mysql_close($connect);
?>
