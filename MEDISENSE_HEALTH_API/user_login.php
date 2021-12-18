<?php 
ob_start();
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
//$objQuery = new CLSQueryMaker();

if(HEALTH_API_KEY == $data ->api_key && isset($data ->useremail) )
{
    $user_email = $data ->useremail;
    $user_password = $data ->password;

    $result_referrring =mysqlSelect('*','login_user',"sub_email='".$user_email."' AND passwd='".md5($user_password)."'","","","","");

    $user_member=mysqlSelect('*','user_family_member',"user_id='".$result_referrring[0]['login_id']."'","","","","");

    if(!empty($result_referrring)){  
          
        $success_register = array('result' => "success", 'status' => '0','message' => "Logged In Successfully.",'user_email' => $result_referrring[0]['sub_email'], 'mobile_num' => $result_referrring[0]['sub_contact'], 'user_name' => $result_referrring[0]['sub_name'], 'user_id' => $result_referrring[0]['login_id'],'member_id' => $user_member[0]['member_id']);

        echo json_encode($success_register);
    }
    else{
        $success_register = array('result' => "failed", 'status' => '1', 'message' => "Email ID and password doesn't match !!!");
        echo json_encode($success_register);
    }
}
else
{
    $success_login = array('result' => "failed", 'status' => '2' ,'message' => "You have not permitted to access the account !!!");
    echo json_encode($success_login);
}
?>
  