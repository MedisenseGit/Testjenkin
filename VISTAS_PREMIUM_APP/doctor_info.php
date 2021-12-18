<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
if(!empty($doctor_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
		$admin_id	= $doctor_id;
		
		$result_doctor = mysqlSelect("DISTINCT(ref_id) as ref_id,last_active_timestamp,verified_by_medisense_user as verified_by_medisense_user,verified_by_medisense as verified_by_medisense,Publications_file as Publications_file,doc_pub as doc_pub,Research_Details_file as research_details_file,doc_research as research_details,Professional_Construction_file as professional_contribution_file,doc_contribute as professional_contribution,doc_interest as description,ref_id as ref_id, md5(ref_id) as doc_encyid, ref_name as ref_name, ref_exp as ref_exp, doc_photo as doc_photo, doc_city as doc_city, doc_country as doc_country, doc_qual as doc_qual, doc_interest as doc_interest, geo_latitude as geo_latitude, geo_longitude as geo_longitude, cons_charge as cons_charge, cons_charge_currency_type as cons_charge_currency_type","referal","doc_spec!=555 and anonymous_status=0 AND nova_default_doctor!=1 AND ref_id='".$admin_id."'","ref_id DESC","","","");
		
		$doc_details= array();
		foreach($result_doctor as $list) 
		{
				
			$getDocList['ref_id']			=	$list['ref_id'];
			$getDocList['doc_encyid']		=	$list['doc_encyid'];
			$getDocList['ref_name']			=	$list['ref_name'];
			$getDocList['doc_exp']			=	$list['ref_exp'];
			$getDocList['doc_photo']		=	$list['doc_photo'];
			$getDocList['doc_city']			=	$list['doc_city'];
			$getDocList['doc_country']		=	$list['doc_country'];
			$getDocList['doc_qual']			=	$list['doc_qual'];
			$getDocList['doc_interest']		=	$list['doc_interest'];
			$getDocList['geo_latitude']		=	$list['geo_latitude'];
			$getDocList['geo_longitude']	=	$list['geo_longitude']; 
			$getDocList['cons_charge']		=	$list['cons_charge']; 
		
			$getDocList['cons_charge_currency_type']	 =	$list['cons_charge_currency_type']; 
			$getDocList['professional_contribution']	 =	$list['professional_contribution']; //Professional Contribution
			$getDocList['professional_contribution_file']=	$list['professional_contribution_file'];
			$getDocList['research_details']				 =	$list['research_details'];	//Research Details
			$getDocList['research_details_file']		 =	$list['research_details_file'];
			$getDocList['Publications']				 	 =	$list['doc_pub'];	//Publications
			$getDocList['Publications_file']			 =	$list['Publications_file'];
			$getDocList['verified_by_medisense']		 =	$list['verified_by_medisense'];	//Publications
			$getDocList['verified_by_medisense_user']	 =	$list['verified_by_medisense_user'];
			$getDocList['last_active_timestamp']	 	 =	$list['last_active_timestamp'];
			
			
			//Academic Information
			$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$list['ref_id']."'","","","","");
			$academic_details= array();
			foreach($getAcademic_Info as $Academic_Info)
			{
				$getAcdList['acdemic_id']		 =	$Academic_Info['id'];
				$getAcdList['acdemic_doc_id']	 =	$Academic_Info['doc_id'];
				$getAcdList['acdemic_type']		 =	$Academic_Info['qualification_type'];
				$getAcdList['acdemic_country']	 =	$Academic_Info['country'];
				$getAcdList['acdemic_city']		 =	$Academic_Info['city'];
				$getAcdList['acdemic_start_date']=	$Academic_Info['start_date'];
				$getAcdList['acdemic_end_date']	 =	$Academic_Info['end_date'];
				
				array_push($academic_details, $getAcdList);  
			}	
			$getDocList['academic_details']			 =	$academic_details;
			
			
			//work history 
			$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$list['ref_id']."'","","","","");
			$workhistory_details= array();
			foreach($getworkhistory as $workhistory)
			{
				$getWrkList['workhistory_id']		 =	$workhistory['id'];
				$getWrkList['workhistory_doc_id']	 =	$workhistory['doc_id'];
				$getWrkList['Institution_Name']		 =	$workhistory['Institution_Name'];
				$getWrkList['work_type'] 			 =	$workhistory['work_type'];
				$getWrkList['Communication_Address'] =	$workhistory['Communication_Address'];
				$getWrkList['phone_num_extension']	 =	$workhistory['phone_num_extension'];
				$getWrkList['Phone_Number']			 =	$workhistory['Phone_Number'];
				$getWrkList['work_Start_Date']		 =	$workhistory['work_Start_Date'];
				$getWrkList['work_End_Date']		 =	$workhistory['work_End_Date'];
				
				
				
				array_push($workhistory_details, $getWrkList);  
			}	
			$getDocList['workhistory']			 =	$workhistory_details;
		
		
		
				
				
			$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$list['ref_id']."'","","","","");
			
			$getDocList['doc_specializations']	=	$doc_specialization;
			
			
			$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$list['ref_id']."'","","","","");
			
			$getDocList['doc_hospitals']	=	$getDocHospital;
			
			$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$list['ref_id']."'","a.id ASC","","","");
			
			$getDocList['doc_languages']	= 	$docLanguages;
			
			$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$list['ref_id']."'","","","","");
			$getDocList['doc_ratings']		=	$docRatings;
			
			$mydoctor = mysqlSelect("*","doc_my_patient","doc_id='".$list['ref_id']."'","patient_id DESC","","","");
			if(count($mydoctor)>0)
			{
				$getDocList['doc_consulted']	= 	1;
			}
			else 
			{
				$getDocList['doc_consulted']	= 0;
			}
					
			array_push($doc_details, $getDocList);  
		}

		
		$success = array('status' => "true", "doctor_info" => $doc_details);
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
