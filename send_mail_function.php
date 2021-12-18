<?php 
function send_mail($page_url){

		
		$url = "https://referralio.com/EMAIL/";
		$url .=$page_url;
		$ch = curl_init (); // setup a curl
		curl_setopt ( $ch, CURLOPT_URL, $url); // set url to send to
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
		$output = curl_exec ( $ch );
		// echo "output".$output;
		curl_close ( $ch );
}
?>