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

if(HEALTH_API_KEY == $data ->api_key && isset($data ->arrFields_family)){

	//$login_id = $data ->admin_id;
	
	
	$arrFields_family = $data ->arrFields_family;
	
	$arrValues_family = $data ->arrValues_family;
	
	$answer_id = $data ->answer_id;
	
	
	
	
	
	$getanswers = $objQuery->mysqlSelect("answers","personalize_app_answers","id='".$answer_id."'","","","","");
	
	$arrFields_family[] = 'rdoEmoHealth';
	$arrValues_family[] = $getanswers[0]['answers'];


	$patientNote=$objQuery->mysqlInsert('personalize_app_user_results',$arrFields_family,$arrValues_family);		
	$id = mysql_insert_id();

	$success_wallet = array('result' => "success", "arrFields_family"=>$arrFields_family, "arrValues_family"=>$arrValues_family, "answer_id" =>$answer_id);
	echo(json_encode($success_wallet));
}
else
{
	$response["status"] = "false";
			echo(json_encode($response));
}
?>