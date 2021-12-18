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

	if( HEALTH_API_KEY == $data ->api_key && $data->filter_type == 0 && isset($data->memberid))	
	{
		$member_id = $data->memberid;
		$report_id = $data->report_id;
		$user_id = $data->adminid;
		$country_name = $data ->country_name;

		$member_basic = mysqlSelect("*","user_family_member","md5(member_id)='".$member_id."' ","","","","");

		$getMemberDet=mysqlSelect('*','user_family_member',"user_id='".$user_id."'","","","","");
		
		$member_general_health = mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");

		if(empty($member_general_health)){
			$arrFileds_medical[]='member_id';
			$arrValues_medical[]= $member_basic[0]['member_id'];
        	$family_general_health = mysqlInsert('user_family_general_health',$arrFileds_medical,$arrValues_medical );

			$member_general_health = mysqlSelect('*','user_family_general_health',"md5(member_id)='".$member_id."'","","","","");
		}

		//fetch report images

		$episodeReport= array();
		
		if(!empty($report_id)) {
			$report_list = mysqlSelect('*','health_app_healthfile_reports',"md5(member_id)='".$member_id."' and md5(id)='".$report_id."'","created_date DESC","","","");

			$report_attachments = mysqlSelect('*','health_app_healthfile_report_attachments',"md5(member_id)='".$member_id."' and md5(report_id)='".$report_id."'","","","","");

			$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","md5(member_id) ='".$member_id."' and md5(id)='".$report_id."'","id DESC","","","");

		}
		else {
			$report_list = mysqlSelect('*','health_app_healthfile_reports',"md5(member_id)='".$member_id."'","created_date DESC","","","");

			$report_attachments = mysqlSelect('*','health_app_healthfile_report_attachments',"md5(member_id)='".$member_id."'","","","","");

			$reportlist_details = mysqlSelect("*","health_app_healthfile_reports","md5(member_id) ='".$member_id."'","id DESC","","","");

		}

		
		$reports_details= array();
		foreach($reportlist_details as $result_reportList) {

				$getReportList['report_id']=$result_reportList['id'];
				$getReportList['title']=$result_reportList['title'];
				$getReportList['description']=$result_reportList['description'];
				$getReportList['report_date']=$result_reportList['report_date'];
				$getReportList['report_date']=$result_reportList['report_date'];
				$getReportList['date_time']=$result_reportList['created_date'];
				$getReportList['doc_id']="";
				
				$attachment_details = mysqlSelect("id as attachment_id, attachment_name as attachment_name","health_app_healthfile_report_attachments","report_id ='".$result_reportList['id']."'","id ASC","","","");

				$getReportList['attachments']= $attachment_details;

				$getReportList['type']= '1';
				
			array_push($reports_details, $getReportList);
		}

		
		$episodeList_details = mysqlSelect("*","doc_my_patient as a inner join doc_patient_episodes as b on a.patient_id=b.patient_id","md5(a.member_id) ='".$member_id."'","episode_id DESC","","","");

		foreach($episodeList_details as $result_reportList){

			$getReportList['report_id']=$result_reportList['episode_id'];
			$getReportList['patient_id']=$result_reportList['patient_id'];
			$getReportList['doc_id']=$result_reportList['doc_id'];
			$getReportList['report_date']=$result_reportList['date_time'];
			$getReportList['date_time']=$result_reportList['date_time'];

			$doctor_name= mysqlSelect("ref_name","referal","ref_id ='".$result_reportList['doc_id']."'","","","","");

			$getReportList['title']= $doctor_name[0]['ref_name'];

			$patient_symptons = mysqlSelect("*","doc_patient_symptoms_active as a inner join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$result_reportList['episode_id']."'","","","","");

			$getReportList['description']= $patient_symptons;

			$patient_prescription = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$result_reportList['episode_id']."'","","","","");

			$getReportList['attachments']= $patient_prescription;
			
			$getReportList['type']= '2';

			array_push($reports_details, $getReportList);
		}

		
	

		$response['status'] = "true";
		$response['member_basic_array'] = $member_basic;


		$response['getMember'] = $getMemberDet;

		$response['general_health_array'] = $member_general_health;

		$response['report_list'] = $report_list;
		$response['report_attachments'] = $report_attachments;

		$response['reports_details'] = $reports_details;

		echo json_encode($response);
		

	}




?>


