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
$Cur_Date=date(' H:i A');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}
$ip = $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);

if(HEALTH_API_KEY == $data ->api_key && isset($data ->doc_id) && isset($data ->hosp_id) && empty($data ->day_val))
{
	$getDocDates	= 	mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$data ->doc_id."' and a.hosp_id='".$data ->hosp_id."'","","","","");					
	$getDocDays= mysqlSelect("*","seven_days","","","","","");
	
	$GetTimeSlot	=	mysqlSelect("a.id as id,b.time_id AS time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$data ->doc_id."' and b.hosp_id='".$data ->hosp_id."' AND b.day_id = '".$data ->day_id."'","","","","");	
	
	
	
	
	
	$response = array('status' => "true","appointDateDetails" => $getDocDates,"appointDayDetails" => $getDocDays,"GetTimeSlot"=>$GetTimeSlot);	
	echo json_encode($response);
}
else if(HEALTH_API_KEY == $data ->api_key && isset($data ->day_val))
{
	$day_val=date('D', strtotime($data ->day_val));

	$getDocTimings	=	mysqlSelect("DISTINCT(b.time_id) as time_id, b.day_id as day_id",'seven_days as a inner join doc_time_set as b on a.day_id=b.day_id',"b.doc_id='".$data ->doc_id."' and b.hosp_id='".$data ->hosp_id."' and a.da_name='".$day_val."'","","","","");

	$getTimings = mysqlSelect("*",'timings',"","","","","");
	$response = array('status' => "true",'day_val' =>$day_val,'docid' => $data ->doc_id,"doctorDetails" => $getDocTimings,"timingsTable" => $getTimings);	
	echo json_encode($response);
}

	
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}
		
 
	

?>


