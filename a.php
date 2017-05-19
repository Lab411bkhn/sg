<td> <textarea rows="3px" cols="100%" id="textArea" >Data received</textarea> </td>
<td> <button onClick="btnSend_Click()" id="btnSend" type="button" >Send</button></td>
<script>
      	function btnSend_Click(dataSend) {
      	  var xhttp = new XMLHttpRequest();
      	  xhttp.open("GET", "http://192.168.0.120/data="+"duong", true);
      	  xhttp.send(); 
      	}
</script>