<?php
class Data_Send{

public function __construct(){
	
	
}	
	public function send_patDet($patName,$patGen,$patMbl,$patLoc,$patDesc){
		
	$Desc="";
	$Desc=urlencode($patDesc);

	$url="http://chottu.org/web/receive_patient.php?name=".$patName."&gen=".$patGen."&mob=".$patMbl."loc=".$patLoc."&desc=".$Desc;
			
			
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
	
}


?>