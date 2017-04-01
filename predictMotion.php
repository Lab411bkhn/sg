<?php
require 'dbconnect.php';
?>
<script type="text/javascript">
	$(document).ready(function(){
		setInterval(function() { 	
			<?php
			$result = mysql_query("DELETE FROM object");
			?>
		}, 4000);		
	}
</script>
