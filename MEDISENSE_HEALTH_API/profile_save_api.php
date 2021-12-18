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
//$objQuery = new CLSQueryMaker();

$arrFields_newAddress = $data ->arrFields_newAddress;
if(HEALTH_API_KEY == $data ->api_key  && isset($data ->userid) )	
{	
	$admin_id=$data ->userid;
	$user_array = mysqlSelect("*","login_user","login_id ='".$admin_id."' ","","","","");
	$family_names_array = mysqlSelect("*","user_family_member","user_id='".$admin_id."' and member_type='primary'","","","","");
	//$user_addresses_array = mysqlSelect("*","user_address","user_id='".$admin_id."' ","","","","");

	$response = array('status' => "true","user" => $user_array,"family_names" => $family_names_array,"user_addresses" => $user_addresses_array);
	echo json_encode($response );
}

/*if(HEALTH_API_KEY == $data->api_key  && isset($data->update) )

//if(HEALTH_API_KEY == $data->api_key  && isset($data->userid)  &&  isset($data->arrFields_customer) && isset($data->arrValues_customer) && isset($data->arrFields_customer1) && isset($data->arrValues_customer1))	
{	

	$usercraete=mysqlUpdate('login_user',$data->arrFields_customer,$data->arrValues_customer,"user_id='".$data ->userid."'");
	$usercraete1=mysqlUpdate('user_family_member',$data ->arrFields_customer1,$data ->arrValues_customer1,"user_id='".$data ->userid."'");
	$response = array('status' => "true");
	echo json_encode($data->arrFields_customer );
}*/

?>