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

	if( HEALTH_API_KEY == $data -> api_key && $data->filter_type == 0 && isset($data->memberid) )	
	{
		$member_id = $data->memberid;

		$member_basic = $objQuery->mysqlSelect("*","user_family_member","md5(member_id)='".$member_id."' ","","","","");
		$member_general_health = $objQuery->mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");

		if(empty($member_general_health)){
			$arrFileds_medical[]='member_id';
			$arrValues_medical[]= $member_basic[0]['member_id'];
        	$family_general_health = $objQuery->mysqlInsert('user_family_general_health',$arrFileds_medical,$arrValues_medical );

			$member_general_health = $objQuery->mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");
		}

		//fetch report images

		$report_list = $objQuery->mysqlSelect('*','health_app_healthfile_reports',"md5(member_id)='".$member_id."'","created_date DESC","","","");
		$report_attachments = $objQuery->mysqlSelect('*','health_app_healthfile_report_attachments',"md5(member_id)='".$member_id."'","","","","");
		
		$response['status'] = "true";
		$response['member_basic_array'] = $member_basic;
		$response['general_health_array'] = $member_general_health;

		$response['report_list'] = $report_list;
		$response['report_attachments'] = $report_attachments;

		echo json_encode($response);
	} else {
			
		$response["status"] = "false";
		$response["data"] = "Some error came";
		echo(json_encode($response));
	}


?>


