<html>
<body>
	<script type="text/javascript" src = "jquery.js"></script>
</body>
</html>
<script>
var time =12;
$.get("tools/interpolation.php","type=speed&time="+time,function(data){
	alert(data);
});
</script>