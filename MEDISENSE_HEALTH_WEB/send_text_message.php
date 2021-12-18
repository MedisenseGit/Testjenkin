<?php 
function send_msg($mobile,$mobile_msg){

	$msg="";

	$msg=urlencode($mobile_msg);
		
		$url="http://sms6.routesms.com:8080/bulksms/bulksms?username=medisense&password=medi2015&type=5&dlr=0&destination=".$mobile."&source=MEDICL&message=".$msg;
	
	//$logger->write("INFO :","login with url".$url);

	$ch = curl_init();  // setup a curl
	curl_setopt($ch, CURLOPT_URL, $url);  // set url to send to
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // required as godaddy fails
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$output=curl_exec($ch);
	//echo "output".$output;
	curl_close($ch);
	//return $output;
}
?>