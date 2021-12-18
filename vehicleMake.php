<?php

$ch = curl_init();
$url = 'https://devapi.anoudapps.com/qicservices/aggregator/getVehMake';


$headers  = [
			'Authorization: Basic dmlzdGFzZzpTZXB0QDIwMjA=',
            'company: 001',
            'Content-Type: application/json'
        ];
$postData = [
    'userId' => 'online',
    'company' => '001'
];
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_PUT, true); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));           
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
$result     = curl_exec ($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "statusCode: ". $statusCode;
echo "<br>";

echo json_encode($postData);
echo "<br>";

if($result === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
else
{
	echo 'Operation completed without any errors';
	
	echo "<br>";
	var_dump($result);
}

curl_close($ch);  

?>
