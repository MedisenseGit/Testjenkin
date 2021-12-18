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

if(HEALTH_API_KEY == $data ->api_key  && isset($data ->questions_array) && isset($data ->answers_array))
{
	$que_count= $data ->que_count;
	$login_id= $data ->login_id;
	$questions_array = $data ->questions_array;
	$answers_array = $data ->answers_array;
	
	$get_result = mysqlSelect("*","personalize_app_user_results","login_id ='".$login_id."'","","","","");
	if(COUNT($get_result) >0 ) {
		$delete_result = mysqlDelete('personalize_app_user_results',"login_id ='".$login_id."'");
	}
	
	
	for($i=0;$i<COUNT($questions_array);$i++)
	{
		$arrFields_personal = array();
		$arrValues_personal = array();
	
		foreach($questions_array[$i] as $value) {
			$arrFields_personal[] = $value;
		}
		
		foreach($answers_array[$i] as $value1) {
			$arrValues_personal[] = $value1;
		}
		
		$newFeedback = mysqlInsert('personalize_app_user_results',$arrFields_personal,$arrValues_personal);
	}
	
	
   
	
	$response = array('status' => "true");
	echo json_encode($response );
}

else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}


?>


