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

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

if(HEALTH_API_KEY == $data ->api_key){

	$login_id = $data ->member_id;
	$user_name = addslashes($data ->patient_name);
	$gender = $data ->pat_gen;
	$emailId = addslashes($data ->emailid);
	$age = $data ->pat_age;
	$mobile_no = addslashes($data ->Mobile_no);
	$address = addslashes($data ->Address);
	$city = addslashes($data ->City);
	$country = addslashes($data ->Country);
	$state = addslashes($data ->State);
	
		$result_referrring = $objQuery->mysqlSelect("*","login_user","login_id ='".$login_id."'","","","","");
	if($result_referrring==true){	
			$arrFields_family[] = 'sub_name';
			$arrValues_family[] = $user_name;
			$arrFields_family[] = 'sub_gender';
			$arrValues_family[] = $gender;
			$arrFields_family[] = 'sub_email';
			$arrValues_family[] = $emailId;
			$arrFields_family[] = 'sub_age';
			$arrValues_family[] = $age;
			$arrFields_family[] = 'sub_address';
			$arrValues_family[] = $address;
			$arrFields_family[] = 'sub_city';
			$arrValues_family[] = $city;
			$arrFields_family[] = 'sub_country';
			$arrValues_family[] = $country;
			$arrFields_family[] = 'sub_state';
			$arrValues_family[] = $state;
			$patientNote=$objQuery->mysqlUpdate('login_user',$arrFields_family,$arrValues_family,"login_id='".$result_referrring[0]['login_id']."'");
					
	        $family_member = $objQuery->mysqlSelect("*","user_family_member","user_id ='".$login_id."' and member_type='primary'","","","","");
		
		    $arrFields_Userfamily[] = 'gender';
			$arrValues_Userfamily[] = $gender;
			$arrFields_Userfamily[] = 'age';
			$arrValues_Userfamily[] = $age;
			$userFamily=$objQuery->mysqlUpdate('user_family_member',$arrFields_Userfamily,$arrValues_Userfamily,"member_id='".$family_member[0]['member_id']."'");
			
		
      $success_register = array('result' => "success","family_details" => $result_family);
      echo json_encode($success_register);
	}
	else
{
	$response["status"] = "false";
			echo(json_encode($response));
}
		
}
else
{
	$response["status"] = "false";
			echo(json_encode($response));
}
