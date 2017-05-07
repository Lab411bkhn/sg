<?php
$xml = new SimpleXMLElement('<xml/>');

for ($i = 1; $i <= 8; ++$i) {
    $node = $xml->addChild('node');
    $node->addChild('mac', "$i");
    $node->addChild('time', "14:3".$i.":00");
    $node->addChild('lat', "027.32156".$i);	
    $node->addChild('lng', "107.32156".$i);
}

Header('Content-type: text/xml');
print($xml->asXML());
?>