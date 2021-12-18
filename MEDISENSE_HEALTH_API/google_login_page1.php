<?php
 ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");
function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}
//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
if(HEALTH_API_KEY == $data ->api_key && isset($data ->useremail))
	{
		
		
		$user_email = $data -> useremail;
		//echo"user email".$user_email;
		//exit();
		$check_referring = $objQuery->mysqlSelect('*','login_user',"sub_email='".$user_email."'","","","","");

  if($check_referring==false){
    

   	$response = array('status' => "false");
	echo json_encode($response);
  }
  else if($check_referring==true)
  {
  
	  if(empty($check_referring[0]['sub_contact'])){
		  $response = array('status' => "false");
	  echo json_encode($response);
	  }
		else{
	   /*$otp = randomOtp();
		  $arrFields = array();
		  $arrValues = array();

		  $arrFields[] = 'otp';
		  $arrValues[] = $otp;

		  $editrecord=$objQuery->mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$check_referring[0]['login_id']."'");
         */
		  $txtMob=$check_referring[0]['sub_contact'];
		  $member_id = $check_referring[0]['login_id'];
		 /* $userotp="Your otp is ".$otp." for Medisense Health App. \nThanks Medisense Health";
		  send_msg($txtMob,$userotp);
			
			// Send OTP via Email
			if(!empty($user_email)) {
							
					$url_page = 'health_otp_request.php';
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($user_name);
					$url .= "&otp=".urlencode($otp);
					$url .= "&reqmail=".urlencode($user_email);
							
					send_mail($url);
			}*/
			$response = array('status' => "true",'mobile_num' => $txtMob,'user_name' =>$user_name, 'member_id' =>$member_id);
		  echo json_encode($response);
		}

  
  
  }
 else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}
}
else{
$response["status"] = 'false';
echo json_encode($response);
}
?>


