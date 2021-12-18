<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../premium/send_text_message.php");

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

if(HEALTH_API_KEY == $data ->api_key && isset($data ->username) && isset($data ->contactnum))
{
		
    $mobile_num = $data -> contactnum;
    $user_name = $data -> username;
    $user_email = $data -> useremail;
    
    $check_referring = $objQuery->mysqlSelect('*','login_user',"sub_contact='".$mobile_num."'","","","","");

    if(empty($check_referring)){
	  
	    $check_refer = $objQuery->mysqlSelect('*','login_user',"sub_email='".$user_email."' and sub_email!=''","","","","");
        
	    if(empty($check_refer)){
            $otp = randomOtp();
            $arrFields_user[] = 'sub_name';
            $arrValues_user[] = $user_name;
            $arrFields_user[] = 'sub_contact';
            $arrValues_user[] = $mobile_num;
            $arrFields_user[] = 'sub_email';
            $arrValues_user[] = $user_email;
            $arrFields_user[] = 'otp';
            $arrValues_user[] = $otp;
            $arrFields_user[] = 'reg_date';
            $arrValues_user[] = $Cur_Date;

            $usercreate=$objQuery->mysqlInsert('login_user',$arrFields_user,$arrValues_user);
            $member_id = mysql_insert_id();
            
            $arrFields_src[] = 'source_name';
            $arrValues_src[] = $user_name;
            $arrFields_src[] = 'partner_id';
            $arrValues_src[] = $member_id;
            $arrFields_src[] = 'src_type';
            $arrValues_src[] = '1';
            
            $userSrccreate=$objQuery->mysqlInsert('source_list',$arrFields_src,$arrValues_src);

            $userotp="Your otp is ".$otp."  for Medisense Healthcare App. \nThanks Medisense Health";
            send_msg($mobile_num,$userotp);
	    }
	    else if($check_refer==true){
            $otp = randomOtp();
            $member_id = $check_refer[0]['login_id'];
            $arrFields_edit = array();
            $arrValues_edit = array();
            $arrFields_edit[] = 'sub_name';
            $arrValues_edit[] = $user_name;
            $arrFields_edit[] = 'sub_contact';
            $arrValues_edit[] = $mobile_num;
            $arrFields_edit[] = 'sub_email';
            $arrValues_edit[] = $user_email;
            $arrFields_edit[] = 'otp';
            $arrValues_edit[] = $otp;
        
            $editrecord=$objQuery->mysqlUpdate('login_user',$arrFields_edit,$arrValues_edit,"login_id='".$check_refer[0]['login_id']."'");

            $userotp="Your otp is ".$otp."  for Medisense Healthcare App. \nThanks Medisense Health";
            send_msg($mobile_num,$userotp);
	    }
        // Send OTP via Email
        if(!empty($user_email)) {
                        
            $url_page = 'health_otp_request.php';
            $url = rawurlencode($url_page);
            $url .= "?docname=".urlencode($user_name);
            $url .= "&otp=".urlencode($otp);
            $url .= "&reqmail=".urlencode($user_email);
                    
            send_mail($url);
        }

   	    $response = array('status' => "true",'mobile_num' => $mobile_num,'user_email' => $user_email,'user_name' =>$user_name,'otp_num' => $otp,'member_id' =>$member_id, 'otp_status' =>"pending");
        echo json_encode($response);
    }
    else
    {
        $otp = randomOtp();
        
        $arrFields = array();
        $arrValues = array();

        $arrFields[] = 'otp';
        $arrValues[] = $otp;
  
        if(!empty($user_email)) {
            $arrFields[] = 'sub_email';
            $arrValues[] = $user_email;
        }

        $editrecord=$objQuery->mysqlUpdate('login_user',$arrFields,$arrValues,"login_id='".$check_referring[0]['login_id']."'");

        $txtMob=$check_referring[0]['sub_contact'];
        $member_id = $check_referring[0]['login_id'];
        $userotp="Your otp is ".$otp." for Medisense Health App. \nThanks Medisense Health";
        send_msg($txtMob,$userotp);
	
	    // Send OTP via Email
        if(!empty($check_referring[0]['sub_email'])) {
                
            $url_page = 'health_otp_request.php';
            $url = rawurlencode($url_page);
            $url .= "?docname=".urlencode($user_name);
            $url .= "&otp=".urlencode($otp);
            $url .= "&reqmail=".urlencode($check_referring[0]['sub_email']);
                    
            send_mail($url);
	    }
        $response = array('status' => "true",'user_email' => $user_email,'mobile_num' => $mobile_num,'user_name' =>$user_name, 'otp_num' => $otp,'member_id' =>$member_id,'otp_status' =>"pending");
        echo json_encode($response);
  
    }
}
else{
    $response["status"] = "Not Allowed";
    echo json_encode($response);
}
?>


