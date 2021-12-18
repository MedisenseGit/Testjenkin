<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

//$postdata = $_POST;
//$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET","", $user_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Frequently Ordered Medicine
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey)
	{
			
			$loginid = $user_id;
			
			$previous_labtest	=	array();
			//$getFrequentTests 	= mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a inner join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_type='1'","a.freq_test_count DESC","","","");
			
			$getFrequentTests 	= mysqlSelect("c.group_test_id as group_test_id,a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department","doctor_frequent_investigations as a inner join patient_diagnosis_tests as b on a.main_test_id=b.id  left join patient_temp_investigation as c on c.main_test_id  = a.main_test_id","a.doc_type='1'","a.freq_test_count DESC"," b.test_name_site_name","","");
			
		
			//$investigation_detail =array();
			$admin_id 	 = $user_id;
			$get_member = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."'","","","","");
			$investigation_details	= array();
			foreach($get_member as $member)
			{
				
				$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id ='".$member['member_id']."' and teleconsult_status=1","","","","");
				
				foreach($get_patient as $patient_list)
				{
					
					
					$get_Episodes 	=	mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$patient_list['patient_id']."'","a.episode_id DESC","","","");
					
					//echo $patient_list['patient_id']."=".$get_Episodes[0]['episode_id']."<br>";
					if(count($get_Episodes)>0)
					{
						$getPrescList['patient_id']		= $patient_list['patient_id'];
						$getPrescList['member_id']		= $member['member_id'];
						$getPrescList['member_name']	= $member['member_name'];
						$getPrescList['episode_id']		= $get_Episodes[0]['episode_id'];
						$getPrescList['date_time']		= $get_Episodes[0]['date_time'];
						
						$investigation_result1 = mysqlSelect('pti_id,main_test_id,group_test_id,test_name','patient_temp_investigation',"(episode_id)='".$get_Episodes[0]['episode_id']."' and patient_id='".$patient_list['patient_id']."'","","","","");
						
						$getPrescList['investigation_result']	= $investigation_result1;
						array_push($investigation_details, $getPrescList);
					}
			
				}
					
			}
			
			
			
			$success = array('status' => "true", "frequent_lab_test_details" => $getFrequentTests, "previous_labtest"=>$investigation_details,'err_msg' => '' );
			echo json_encode($success);
		
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>
