<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");


if(HEALTH_API_KEY == $data->api_key  && isset($data->type) )

{	
	$file_name	=	$data->file_name;
	$temp_name	=	$data->temp_name;
	
	$usercraete	=	mysqlUpdate('login_user',$data->arrFields_customer,$data->arrValues_customer,"login_id='".$data ->userid."'");
	$usercraete1=	mysqlUpdate('user_family_member',$data ->arrFields_customer1,$data ->arrValues_customer1,"user_id='".$data ->userid."'");
	$getmember_id=	mysqlSelect('*','user_family_member',"user_id='".$data ->userid."' and member_type='primary' ","","","","");
	
	if(!empty($file_name))
	{
		$folder_name	=	"memberPics"; 
		$sub_folder		=	$getmember_id[0]['member_id'];
		$filename		=	$data->file_name;
		$file_url		=	$data->temp_name;
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	$response = array('status' => "true");
	echo json_encode($response );
}

?>