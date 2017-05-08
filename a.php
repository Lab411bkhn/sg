<?php
// Create map with request parameters
$params = array ('data' => 'haiduong');
 
// Build Http query using params
$query = http_build_query ($params);
 
// Create Http context details
$contextData = array ( 
                'method' => 'GET',
                'header' => "Connection: close\r\n".
                            "Content-Length: ".strlen($query)."\r\n",
                'content'=> $query );
 
// Create context resource for our request
$context = stream_context_create (array ( 'http' => $contextData ));
 
// Read page rendered as result of your POST request
$result =  file_get_contents (
                  'rx.php',  // page url
                  false,
                  $context);
 
?>