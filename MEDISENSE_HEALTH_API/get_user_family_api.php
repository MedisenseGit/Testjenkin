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
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && isset($data ->member_id))
	{
		$user_member = mysqlSelect('*','user_family_member',"member_id='".$data ->member_id."'","","","","");

		$user_health = mysqlSelect('*','user_family_general_health',"member_id='".$data ->member_id."'","","","","");

		$response = array('status' => "true","user_member" => $user_member,"user_health" => $user_health);	
		echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}
		
 
	

?>


