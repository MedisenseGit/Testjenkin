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


if(HEALTH_API_KEY == $data ->api_key && isset($data ->spec_id))
	{
		$doc_state = mysqlSelect('DISTINCT(a.doc_state) as doc_state','referal as a left join doc_specialization as b on a.ref_id=b.doc_id',"b.spec_id='".$data ->spec_id."'","","","","");
		$response = array('status' => "true","getState" => $doc_state);	
		echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && isset($data ->country_name)){
		$getStates= mysqlSelect("*",'countries as a left join states as b on a.country_id=b.country_id',"a.country_name='".$data ->country_name."'","b.state_name asc","","","");

		$getDefaultState= mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_name=India","b.state_name asc","","","");
		$response = array('status' => "true","getStates" => $getStates,"defaultState" => $getDefaultState);	
		echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && isset($data ->state_name)){
		$getCity=mysqlSelect("d.city_name",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"d.state='".$data ->state_name."'","d.city_name asc","d.city_name","","");
		$response = array('status' => "true","getCity" => $getCity);	
		echo json_encode($response);
	}	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}
		
 
	

?>


