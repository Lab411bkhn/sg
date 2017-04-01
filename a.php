<?php
require 'dbconnect.php';
?>	
<script type="text/javascript" src = "jquery.js"></script>
<script type="text/javascript">

$(document).ready(function(){	

$.get("test.php",function(data){
		alert(data);
		});
});
<?php
$result = mysql_query("SELECT * FROM object");
	    while($row = mysql_fetch_array($result)){
		?>
			('<?php echo $row['mac']?>')
<?php }?>
</script>