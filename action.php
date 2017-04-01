<script type="text/javascript" src = "jquery.js"></script>
<table bgcolor="#99FFCC" >
	<tr>
    	<td><font size="+1">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<?php
	require 'dbconnect.php';
	$mac = $_GET['mac'];
	echo "Cau hinh sensor ".$mac."<br />";
	$sql = "SELECT mac,netip,temp, humi,ener FROM data_sensor WHERE mac=".$mac." ORDER BY STT DESC LIMIT 1";
	$result = mysql_query($sql) or die(" Error in Selecting ");
    $row =mysql_fetch_assoc($result);
?>
</font></td></tr>
    <tr onclick="sendCommand('<?php echo $mac?>','000')">
    	<td>
        <img src="http://www.maps.google.com/mapfiles/kml/pal4/icon42.png" height="20px" width="20px"/><font size="+1" color="red"><?php echo "&nbsp;&nbsp;".$row['temp']."&#186;C"?></font><font><?php echo "/".$row['humi']."%"?></font></img>
        </td>
    </tr> 
    <tr>
    	<td>
  		<input type="checkbox" id="tempRet" checked>Temperature/Humidity<br>
  		<input type="checkbox" id="photoRet" checked>Photo<br>
        <input type="checkbox" id="warnRet" >Warning<br>
        </td>	
    </tr>
    <tr>
    	<td><button onclick="sendRetasking('<?php echo $mac?>')">&nbspSet funtions&nbsp</button> <button onclick="sendCommand('<?php echo $mac?>','580')">Get funtions</button></td>
    </tr>
    <tr><td><button onclick="sendCommand('<?php echo $mac?>','400')">&nbsp;&nbsp;Take Photo&nbsp;</button> <button onclick="">Draw Chart&nbsp;</button></td></tr>
    <tr>
    	<td>
        	<a class="login-window button" href="#login-box">&nbspAll Photos&nbsp&nbsp</a>
            <a class="login-window button" href="#setPosition-box">Set Position</a>
        </td>
    </tr>
</table>
<script language="javascript">
	function getFunctions(){
		var tempCheck = document.getElementById("tempRet").checked;
		var photoCheck = document.getElementById("photoRet").checked;
		var warnCheck = document.getElementById("warnRet").checked;
		var func = tempCheck*1 + photoCheck*2 + warnCheck*4;
		return func;
	}
	function sendCommand(mac,commandCode){
		//alert("command="+mac+commandCode);
		$.ajax({
			url: "send.php",                           
			type: "POST",
			data: "command="+mac+commandCode,                   
			async: false,
			success: function(req){  
				alert(req); 
			}	
		});
	}
	
	function sendRetasking(mac){
		var cmdCode = "5" + getFunctions() + "0";
		sendCommand(mac,cmdCode);
	}
	
	////////////////////////////////////////////////////////////
$(document).ready(function() {
    $('a.login-window').click(function() {
        //lấy giá trị thuộc tính href - chính là phần tử "#login-box"
        var loginBox = $(this).attr('href');
 
        //cho hiện hộp đăng nhập trong 300ms
        $(loginBox).fadeIn(300);
 
        // thêm phần tử id="over" vào sau body
        $('body').append('<div id="over">');
        $('#over').fadeIn(300);
 
        return false;
	});
 
 // khi click đóng hộp thoại
	 $(document).on('click', "a.close, #over", function() {
		   $('#over, .login').fadeOut(300 , function() {
			   $('#over').remove();
		   });
		    $('#over, .updatePosition').fadeOut(300 , function() {
			   $('#over').remove();
		   });
		   		   
		  return false;
	 });
});
</script>

<div class="login" id="login-box">
  <?php
//Kiểm tra tính hợp lệ của đường dẫn
$path_dir = "C:\\xampp\\htdocs\\sg\\imagesensor\\Sensor".$mac."\\";
if (is_dir($path_dir) ) {
	//Thực hiện mở thư mục
	$open_dir = opendir($path_dir);
	//Duyệt qua thư mục và file
	while (($file = readdir($open_dir)) !== false) {
		if($file != '.' && $file != '..') {  // skip self and parent pointing directories
			?>
			<img src="imagesensor/Sensor<?php echo $mac?>/
			<?php
			$fullpath = $path_dir.$file;
			echo $file;
			?>
			" height="150px" width="200px" onclick="takePhoto()"></img>
            <?php
			//Thực hiện thao tác trên file
		}
	}
	closedir($open_dir);
}
else {
	if (is_link($path_dir)) {
		print "link ".$path_dir." is skipped\n";
	}
}
?>
<a class="close" href="#"><img class="img-close" title="Close Window" alt="Close" src="images/close.png" /></a>
</div>

<div class="updatePosition" id="setPosition-box">
	<strong><h3>&nbsp;Cập nhật vị trí sensor <?php echo $mac?></h3><a class="close" href="#"><img class="img-close" title="Close Window" alt="Close" src="images/close.png" /></a></strong>
    <form action="/sg/settingMap.php">
    	<input type="text" name="type" value="updateSensorPosition" hidden="true"/>
        <input type="text" name="mac" value="<?php echo $mac?>" hidden="true"/>
    	<strong>&nbsp;Longitude:</strong> <input type="text" name="lat" value=""><br/>
	    <strong>&nbsp;Latitude&nbsp;&nbsp;&nbsp;:</strong> <input type="text" name="lng" value=""><br/><br/>
    <input type="submit" value="Cập nhật" class="button"  align="right"/>
    </form>  
</div>

<style>
/*phần tử phủ toàn màn hình,không được hiển thị*/
#over {
    display: none;
    background: #000;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    opacity: 0.8;
    z-index: 999;
}
a, a:visited, a:active{
    text-decoration:none;
}
.login
{
    background-color:#85B561;
    height:auto;
    width:820px;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    font-size:14px;
    padding-bottom:5px;
    display:none;
    overflow:hidden;
    position:absolute;
    z-index:99999;
    top:10%;
	left:45%;
    margin-left:-300px;
	
}
.updatePosition {
	background-color:#6F9;
    height:auto;
    width:350px;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    font-size:14px;
	padding: 10px 10px 10px 10px;
    display:none;
    overflow:hidden;
    position:absolute;
    z-index:99999;
    text-align:center;
	vertical-align:central;
	margin-left:400px;
}
.login .login_title
{
    color:white;
    font-size:16px;
    padding:8px 0 5px 8px;
    text-align:left;
}
 
.login-content label {
    display: block;
    padding-bottom: 7px;
}
 
.login-content span {
    display: block;
}
.login-content
{
    padding-left:35px;
    background-color:white;
    margin-left:5px;
    margin-right:5px;
    height:auto;
    padding-top:15px;
    overflow:hidden;
}
 
.img-close {
    float: right;
    margin-top:-43px;
    margin-right:5px
}
.button{
    display: inline-block;
    min-width: 46px;
    text-align: center;
    color: #444;
    font-size: 14px;
    font-weight: 700;
    height: 22px;
    padding: 0px 8px;
    line-height: 20px;
    border-radius: 4px;
    transition: all 0.218s ease 0s;
    border: 1px solid #DCDCDC;
    background-color: #F5F5F5;
    background-image: -moz-linear-gradient(center top , #F5F5F5, #F1F1F1);
    cursor: pointer;
}
.button:hover{
     border: 1px solid #DCDCDC;
    text-decoration: none;
    -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    box-shadow: 0 2px 2px rgba(0,0,0,0.1);
}
.login input
{
    border:1px solid #D5D5D5;
    border-radius:5px;
    box-shadow:1px 1px 5px rgba(0,0,0,.07) inset;
    color:black;
    font:12px/25px "Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
    height:28px;
    padding:0px 8px;
    word-spacing:0.1em;
    width:160px;
}
.updatePosition input
{
    border:1px solid #D5D5D5;
    border-radius:5px;
    box-shadow:1px 1px 5px rgba(0,0,0,.07) inset;
    color:black;
    font:12px/25px "Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
    height:28px;
    padding:0px 8px;
    word-spacing:0.1em;
    width:160px;
}
.submit-button{
    display: inline-block;
    padding: auto;
    margin: 15px 75px;
    width: 150px;
}
</style>


