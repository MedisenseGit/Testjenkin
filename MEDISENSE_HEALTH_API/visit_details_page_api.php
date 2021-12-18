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

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");

//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if(HEALTH_API_KEY == $data ->api_key && isset($data ->appid))
{
		
		$appointid = $data ->appid;
		
	$appointResult = $objQuery->mysqlSelect("a.pref_doc as Pref_Doc,a.patient_id as patient_id,e.ref_name as ref_name,e.doc_photo as doc_photo,e.anonymous_status as anonymous_status,a.pay_status as Pay_Status,a.visit_status as Visit_Status","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","md5(a.id)='".$appointid."'","","","","");
	$patient_details = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$appointResult[0]['patient_id']."' and doc_id='".$appointResult[0]['Pref_Doc']."'","","","","");
	$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$appointResult[0]['Pref_Doc']."'","","","","");
	
	$patient_episodes = $objQuery->mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","admin_id = '". $appointResult[0]['Pref_Doc'] ."' and patient_id = '". $appointResult[0]['patient_id'] ."' "," episode_id DESC ","","","");
	
	$patEpisode = array();
	foreach($patient_episodes as $patient_episodes_list){
		$get_medical_complaint = $objQuery->mysqlSelect("b.symptoms as symptoms","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$patient_episodes_list['episode_id']."'","","","","");
		$doc_patient_episode_prescriptions = $objQuery->mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episodes_list['episode_id'] ."' "," prescription_seq ASC","","","");
											
		$episodeDetail['formated_date_time'] = $patient_episodes_list['formated_date_time'];
		$episodeDetail['patientSymptoms'] = $get_medical_complaint;
		$episodeDetail['doc_patient_episode_prescriptions'] = $doc_patient_episode_prescriptions;
		array_push($patEpisode,$episodeDetail);
	}
	/*$docResponse = array();
	foreach($opinion_details as $opinion_details_list){
		$refDetails = $objQuery->mysqlSelect('ref_id,ref_name,ref_address,doc_state,doc_country,doc_photo,anonymous_status','referal',"ref_id='".$opinion_details_list['ref_id']."'","","","","");
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$opinion_details_list['ref_id']."'","","","","");
		$chatDetails = $objQuery->mysqlSelect('a.chat_note as chat_note, a.TImestamp as TImestamp','chat_notification as a inner join referal as b on a.ref_id=b.ref_id',"md5(a.patient_id)='".$patientid."' and a.ref_id='".$opinion_details_list['ref_id']."'","a.chat_id desc","","","");
		$chkEvntStatus = $objQuery->mysqlSelect("event_id","patient_email_event","eventtype='3' and md5(patient_id)='".$patientid."' and random_id='".$refDetails[0]['ref_id']."'","","","","");
		
		$docResp['doc_id'] = $refDetails[0]['ref_id'];
		$docResp['doc_name'] = $refDetails[0]['ref_name'];
		$docResp['doc_city'] = $refDetails[0]['ref_address'];
		$docResp['doc_state'] = $refDetails[0]['doc_state'];
		$docResp['doc_country'] = $refDetails[0]['doc_country'];
		$docResp['doc_photo'] = $refDetails[0]['doc_photo'];
		$docResp['cur_doc_status'] = $opinion_details_list['status2'];
		$docResp['doc_specializations']= $doc_specialization;
		$docResp['docLikeStatus'] =	COUNT($chkEvntStatus);
		$docResp['doc_note'] = $chatDetails;
		array_push($docResponse,$docResp);
	}
	*/
	$success = array('status' => "true","patient_details" => $patient_details,"appointResult" => $appointResult,"doc_specialization" => $doc_specialization,"patient_episodes" =>$patEpisode);
	echo json_encode($success);
}

else 
{
			
	$success["status"] = "false";
	echo json_encode($success);
}

?>


