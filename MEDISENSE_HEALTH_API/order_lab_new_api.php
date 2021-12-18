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




if(HEALTH_API_KEY == $data ->api_key  && isset($data ->admin_id) && !isset($data ->main_test_id) )	
{	
	$admin_id = $data ->admin_id;
	
	$getFrequentTests = mysqlSelect("a.dfi_id as dfi_id, a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name, a.doc_id as doc_id, a.doc_type as doc_type, a.freq_test_count as freq_test_count, b.department as department ","doctor_frequent_investigations as a inner join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_type='1'","a.freq_test_count DESC","","","0,8");
	
	
	$get_member  = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."'","","","","");
	
	
	
	$episode_details		= array();
	$investigation_deatils	= array();
	
	foreach($get_member as $member)
	{
		
		$get_patient = mysqlSelect("patient_id","doc_my_patient","member_id ='".$member['member_id']."' and teleconsult_status=1","patient_id DESC ","","","5");
		$getPrescList['patient_id']	= $get_patient[0]['patient_id'];
		$getPrescList['member_id']	= $member['member_id'];
		
		
		$get_Episodes =mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$get_patient[0]['patient_id']."'","","","","");
		
		
		$getPrescList['episode_id']	= $get_Episodes[0]['episode_id'];
		$getPrescList['date_time']	= $get_Episodes[0]['date_time'];
		$getPrescList['ref_name']	= $get_Episodes[0]['ref_name'];
		
		$investigation_result = mysqlSelect('test_name','patient_temp_investigation',"episode_id='".$get_Episodes[0]['episode_id']."' and patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
		
		$getPrescList['investigation'] = $investigation_result;
		$getPrescList['test_name'] = $investigation_result[0]['test_name'];
		
		array_push($investigation_deatils, $getPrescList);
	}
	
	$payment_result =  mysqlSelect("b.referral_type as referral_type,b.member_id as member_id,b.episode_id as episode_id,b.diagnostic_customer_id as diagnostic_customer_id,b.dr_id as dr_id,b.patient_id as patient_id,a.currency_code as currency_code,a.payment_amount AS payment_amount, b.order_status AS order_status ","payment_diagno_pharma AS a INNER JOIN diagnostic_referrals AS b ON a.referred_id = b.dr_id","(login_id)='".$admin_id."' and b.order_status = 2","a.referred_id DESC","","","");
	$payment_details = array();
	foreach($payment_result as $payment)
	{
		$getpayment['order_status']		= $payment['order_status'];
		$getpayment['payment_amount']	= $payment['payment_amount'];
		$getpayment['currency_code']	= $payment['currency_code'];
		
		$getpayment['episode_id']		= $payment['episode_id'];
		$getpayment['diag_customer_id']	= $payment['diagnostic_customer_id'];
		$getpayment['dr_id']			= $payment['dr_id'];
		$getpayment['member_id']		= $payment['member_id'];
		$getpayment['referral_type']	= $payment['referral_type'];
		
		
		
		array_push($payment_details, $getpayment);
		
	}
	
	$getEpiList['payment_detail']	= $payment_details;
	
		
	$getEpiList['investigation_result']	=	$investigation_deatils;
	array_push($episode_details, $getEpiList);
	
	$last_five_test = mysqlSelect(" id ,test_name_site_name","patient_diagnosis_tests","","","","","");
	$get_memberdetails  = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."' and member_type='primary'","","","","");
	
	
	$success = array('status' => "true", "frequent_lab_test_details" => $getFrequentTests,"episode_details"=>$episode_details,"member_id"=>$get_member[0]['member_id'],"getFrequentTests" => $last_five_test,'member_details'=>$get_memberdetails,'err_msg' => '' );
	echo json_encode($success);
	
}
else if (HEALTH_API_KEY == $data ->api_key  && isset($data ->main_test_id))
{
	
	$main_test_id = $data ->main_test_id;
	$admin_id 	  = $data ->admin_id;
	$member_id 	  = $data ->member_id;
	$id = $data ->id;
	
	if(!empty($admin_id))
	{
		$get_memberdetails  = mysqlSelect("member_id,member_name","user_family_member","user_id ='".$admin_id."' and member_type='primary'","","","","");
	}
	else
	{
		$get_memberdetails  = mysqlSelect("member_id,member_name","user_family_member","member_id ='".$member_id."'","","","","");
	}	
	
	if(!empty($id))
	{
		$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","(id)='".$main_test_id."'","","","","");
	}
	else
	{
		$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","md5(id)='".$main_test_id."'","","","","");
	}
	
	$prescription_details= array();
	
	//$getCheckTest= mysqlSelect("id,test_id,group_test","patient_diagnosis_tests","md5(id)='".$main_test_id."'","","","","");
	if($getCheckTest[0]['group_test']=="Y")
	{
		$getTestList= mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$getCheckTest[0]['test_id']."'","","","","");	
		if(!empty($getTestList))
		{
			while(list($key, $value) = each($getTestList))
			{
				$getTestName= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
			
			
				$check_temp_invest_active = mysqlSelect("episode_id,patient_id,main_test_id,pti_id,test_name,group_test_id,department,status","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."'  and doc_type='1'","","","","");
				
				$getPrescList['pti_id']			=	$check_temp_invest_active[0]['pti_id'];
				$getPrescList['test_name']	 	=	$check_temp_invest_active[0]['test_name'];
				$getPrescList['main_test_id']	=	$check_temp_invest_active[0]['main_test_id'];
				$getPrescList['group_test_id']	=	$check_temp_invest_active[0]['group_test_id'];
				$getPrescList['patient_id']		=	$check_temp_invest_active[0]['patient_id'];
				$getPrescList['episode_id']		=	$check_temp_invest_active[0]['episode_id'];
				$getPrescList['department']		=	$check_temp_invest_active[0]['department'];
				$getPrescList['status']			=	$check_temp_invest_active['status'];
				
				
				array_push($prescription_details, $getPrescList);
				
			}
		}
	
		
	}
	if($getCheckTest[0]['group_test']=="N")
	{
		$getTestList= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$getCheckTest[0]['id']."'","","","","");
		
		$check_temp_invest_active = mysqlSelect("episode_id,patient_id,main_test_id,pti_id,test_name,group_test_id,department,status ","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."'  and doc_type='1' ","","main_test_id","","");

		foreach($check_temp_invest_active as $check_invest_active)
		{
			$getPrescList['pti_id']			=	$check_invest_active['pti_id'];
			$getPrescList['test_name']	 	=	$check_invest_active['test_name'];
			$getPrescList['main_test_id']	=	$check_invest_active['main_test_id'];
			$getPrescList['group_test_id']	=	$check_invest_active['group_test_id'];
			$getPrescList['patient_id']		=	$check_invest_active['patient_id'];
			$getPrescList['episode_id']		=	$check_invest_active['episode_id'];
			$getPrescList['department']		=	$check_invest_active['department'];
			$getPrescList['status']			=	$check_invest_active['status'];
			
			
			array_push($prescription_details, $getPrescList);
		}
		
	}	
	
	
	$success = array('status' => "true", "getTestsName" => $prescription_details,"main_test_id"=>$main_test_id,"id"=>$getCheckTest[0]['id'],"test_id"=>$getTestList[0]['test_id'] ,'member_details'=>$get_memberdetails);
	
	
	
	echo json_encode($success);
	
}	

else if(HEALTH_API_KEY == $data ->api_key  && isset($data ->episode_id)) // this is for prevous list 
{
	$episode_id	= $data ->episode_id; 
	$investigation = array();
	$get_Episodes  = mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"md5(a.episode_id) ='".$episode_id."'","","","","");
	
	
	$investigation_result = mysqlSelect('episode_id,patient_id,main_test_id,pti_id,test_name,group_test_id,department,status','patient_temp_investigation',"md5(episode_id)='".$episode_id."' and patient_id='".$get_Episodes[0]['patient_id']."'","","","","");
	foreach($investigation_result as $result)
	{
		$get_member_id = mysqlSelect("member_id","doc_my_patient","patient_id ='".$result['patient_id']."' and teleconsult_status=1","patient_id DESC","","","");

		$get_member_name = mysqlSelect("member_name,member_id","user_family_member","member_id ='".$get_member_id[0]['member_id']."' ","","","","");		
		
		$getinvestigation['test_name'] 		= 	$result['test_name'];
		$getinvestigation['patient_id'] 	= 	$result['patient_id'];
		$getinvestigation['episode_id'] 	= 	$result['episode_id'];
		
		$getinvestigation['member_id'] 		= 	$get_member_name[0]['member_id'];
		$getinvestigation['member_name'] 	= 	$get_member_name[0]['member_name'];
		
		$getinvestigation['group_test_id'] 	= 	$result['group_test_id'];
		$getinvestigation['department'] 	= 	$result['department'];
		$getinvestigation['status'] 		= 	$result['status'];
		$getinvestigation['main_test_id'] 	= 	$result['main_test_id'];
		$getinvestigation['pti_id'] 		= 	$result['pti_id'];
		
		array_push($investigation, $getinvestigation);
	}
	
	$success = array('status' => "true","test_name" => $investigation );
	echo json_encode($success);
	
	
}
else if(HEALTH_API_KEY == $data ->api_key  && isset($data ->member_id)) // this is for search 
{
	$member_id	= $data ->member_id;
	
	
	$last_five_tests = mysqlSelect(" id ,test_name_site_name","patient_diagnosis_tests","","","","","");
	
	$get_member_name = mysqlSelect("member_name,member_id","user_family_member","member_id ='".$member_id."' ","","","","");
	
	$success = array('status' => "true","getFrequentTests" => $last_five_tests,"member_details"=>$get_member_name);
	echo json_encode($success);
	
	
}

else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}



?>



