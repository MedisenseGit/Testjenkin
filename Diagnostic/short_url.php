<?php 
function get_shorturl($url)
{
	$baseUrl="medisensecrm.com";
$fields = array
(
	'url' 	=> $url,
	'domain' 	=> $baseUrl
);
 
$headers = array
(
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'http://mdsn.in/create' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
//echo $result; 
 
//var_dump(json_decode($result));
$shortUrl = json_decode($result);
return $shortUrl ->{'outgoing'};
}
?>