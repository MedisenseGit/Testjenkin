<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers)
{
    $user_id 	= $headers['user-id'];
	$timestamp 	= $headers['x-timestamp'];
	$hashKey 	= $headers['x-hash'];
	$device_id	= $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
		$filter_type 	= $_POST['doc_filter_type'];
		$spec_id 		= $_POST['doc_spec_id'];
		$doc_city 		= $_POST['doc_city'];
		$pageVal 		= $_POST['page_val'];
		$preferred_language 	= $_POST['preferred_language'];
		$preferred_country = $_POST['preferred_country'];
		
		
	//	echo"filter_type  = ".$filter_type; 
		if($pageVal==1)
		{
			$this1 = 0;
			$page_limit = 15;
		}
		else if($pageVal>1)
		{
			$limit = 15*$pageVal;
			$page_limit = 15;
			$this1 = $limit-15;
		}


		if($filter_type == 1)
		{
			
			if(!empty($_POST['doc_spec_id']) && !empty($preferred_country) && !empty($preferred_language)) 
			{	
				//echo "one";		
				// Specialty selected is not empty
				$specialization_id	= $_POST['doc_spec_id'];	//implode(',',$_POST['doc_spec_id']);
				$result_doctor		= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND  a.doc_country_id IN ('".$preferred_country."')  AND b.language_id in('".$preferred_language."')  AND c.spec_id IN ('".$specialization_id."')","a.ref_id DESC","","","$this1, $page_limit");		
			}
			else if(empty($_POST['doc_spec_id']) && !empty($preferred_country) && !empty($preferred_language)) 
			{  
				// Specialty selected is empty
				//echo "two";	
				$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND a.doc_country_id IN ('".$preferred_country."') AND b.language_id in('".$preferred_language."')","a.ref_id DESC","","","$this1, $page_limit");
			}
			else if(!empty($_POST['doc_spec_id']) && empty($preferred_country) && !empty($preferred_language))
			{
				//echo "three";	
				$specialization_id	= $_POST['doc_spec_id'];
				$result_doctor		= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND b.language_id in('".$preferred_language."')  AND c.spec_id IN ('".$specialization_id."')","a.ref_id DESC","","","$this1, $page_limit");		
			}
			else if(!empty($_POST['doc_spec_id']) && !empty($preferred_country) && empty($preferred_language))
			{
				//echo "three";	
				$specialization_id	= $_POST['doc_spec_id'];
				$result_doctor		= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND  a.doc_country_id IN ('".$preferred_country."') AND c.spec_id IN ('".$specialization_id."')","a.ref_id DESC","","","$this1, $page_limit");		
			
			}
			else if(empty($_POST['doc_spec_id']) && !empty($preferred_country) && empty($preferred_language))
			{
				//echo "four";
				$result_doctor	= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND  a.doc_country_id IN ('".$preferred_country."')","a.ref_id DESC","","","$this1, $page_limit");		
			
			}
			else if(!empty($_POST['doc_spec_id']) && empty($preferred_country) && empty($preferred_language)) 
			{	
				//echo "five";
				// Specialty selected is not empty
				$specialization_id	= $_POST['doc_spec_id'];	//implode(',',$_POST['doc_spec_id']);
				$result_doctor		= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND c.spec_id IN ('".$specialization_id."')","a.ref_id DESC","","","$this1, $page_limit");		
			}
			else if(empty($_POST['doc_spec_id']) && empty($preferred_country) && !empty($preferred_language)) 
			{	
				//echo "six";
				$result_doctor		= mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file, a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND b.language_id in('".$preferred_language."')","a.ref_id DESC","","","$this1, $page_limit");		
			}
			else 
			{
				//echo "seven";
				$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1","a.ref_id DESC","","","$this1, $page_limit");
			}
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList) 
			{
				
				$getDocList['ref_id']		=	$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']	=	$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_defaultdoctorList['cons_charge']; 
				
				$getDocList['cons_charge_currency_type']=$result_defaultdoctorList['cons_charge_currency_type']; 
				
				
				$getDocList['professional_contribution']	 =	$result_defaultdoctorList['professional_contribution']; //Professional Contribution
				$getDocList['professional_contribution_file']=	$result_defaultdoctorList['professional_contribution_file'];
				
				$getDocList['research_details']				 =	$result_defaultdoctorList['research_details'];	//Research Details
				$getDocList['research_details_file']		 =	$result_defaultdoctorList['research_details_file'];
				
				$getDocList['Publications']				 	 =	$result_defaultdoctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_defaultdoctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_defaultdoctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_defaultdoctorList['verified_by_medisense_user'];
				
				
				
				

				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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
				
				
				
				
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				
				$getDocList['doc_specializations']	=	$doc_specialization;
				
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				
				$getDocList['doc_hospitals']	=	$getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				
				$getDocList['doc_languages']	= 	$docLanguages;
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_defaultdoctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0)
				{
					$getDocList['doc_consulted']	= 	1;
				}
				else 
				{
					$getDocList['doc_consulted']	= 0;
				}
						
				array_push($default_doc_details, $getDocList);  
			}

			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList)
			{
				
				$getDocList['ref_id']		=	$result_doctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_doctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_doctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_doctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_doctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_doctorList['doc_city'];
				$getDocList['doc_country']	=	$result_doctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_doctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_doctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
				
				
				$getDocList['doc_description']					=	$result_doctorList['description']; 
				$getDocList['professional_contribution']		=	$result_doctorList['professional_contribution']; 
				$getDocList['professional_contribution_file']	=	$result_doctorList['professional_contribution_file']; 
				$getDocList['research_details']				    =	$result_doctorList['research_details'];
				$getDocList['research_details_file']		 	=	$result_doctorList['research_details_file'];
				
				$getDocList['Publications']				 	 =	$result_doctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_doctorList['Publications_file'];
				$getDocList['verified_by_medisense']		 =	$result_doctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_doctorList['verified_by_medisense_user'];
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_doctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_doctorList['ref_id']."'","","","","");
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

			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']	= 	$getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				
				$getDocList['doc_languages']	=	$docLanguages;
				
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_doctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) 
				{
					$getDocList['doc_consulted']= 1;
				}
				else 
				{
					$getDocList['doc_consulted']= 0;
				}
						
				array_push($doc_details, $getDocList);  
			}
			
			if(COUNT($result_doctor)==$page_limit)
			{
				$page_val=$pageVal+1;
			}
			else
			{
				$page_val=0;
			}
			$success = array('status' => "true", "doctor_list" => $doc_details, "default_doctor_list" => $default_doc_details, "pagination_val" => $page_val);
			echo json_encode($success);
		}
		
		if($filter_type == 2)		
		{
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense as verified_by_medisense,a.verified_by_medisense_user as verified_by_medisense_user,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList)
			{
				
				$getDocList['ref_id']		=	$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']	=	$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_defaultdoctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']	=	$result_defaultdoctorList['cons_charge_currency_type']; 
				
				$getDocList['doc_description']					=	$result_defaultdoctorList['description']; 
				$getDocList['professional_contribution']		=	$result_defaultdoctorList['professional_contribution'];
				$getDocList['professional_contribution_file']	=	$result_defaultdoctorList['professional_contribution_file']; 
				$getDocList['research_details']				    =	$result_defaultdoctorList['research_details'];
				$getDocList['research_details_file']		 	=	$result_defaultdoctorList['research_details_file'];
				
				$getDocList['Publications']				 	 =	$result_defaultdoctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_defaultdoctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_defaultdoctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_defaultdoctorList['verified_by_medisense_user'];
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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

			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']	= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']	= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']	= $docLanguages;
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_defaultdoctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) 
				{
					$getDocList['doc_consulted']	= 1;
				}
				else 
				{
					$getDocList['doc_consulted']	= 0;
				}
						
				array_push($default_doc_details, $getDocList);  
			}
			
			$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense as verified_by_medisense,a.verified_by_medisense_user as verified_by_medisense_user,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details,a.ref_id ,md5(a.ref_id) as doc_encyid,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution, a.ref_name,a.doc_interest as description, a.ref_exp, a.doc_photo, a.doc_city, a.doc_country, a.ref_address, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doc_specialization as b on b.doc_id =a.ref_id inner join doctor_langauges as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 and  c.language_id in('".$preferred_language."')","a.ref_id DESC","","","$this1, $page_limit");
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) 
			{
				$getDocList['ref_id']		=	$result_doctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_doctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_doctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_doctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_doctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_doctorList['doc_city'];
				$getDocList['doc_country']	=	$result_doctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_doctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_doctorList['cons_charge'];
				
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
				
				$getDocList['doc_description']				 =	$result_doctorList['description']; 
				$getDocList['professional_contribution']	 =	$result_doctorList['professional_contribution'];
				$getDocList['professional_contribution_file']=	$result_doctorList['professional_contribution_file'];
				$getDocList['research_details']				 =	$result_doctorList['research_details'];
				$getDocList['research_details_file']		 =	$result_doctorList['research_details_file'];
				
				
				$getDocList['Publications']				 	 =	$result_doctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_doctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_doctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_doctorList['verified_by_medisense_user'];
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_doctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_doctorList['ref_id']."'","","","","");
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

			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']	= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']	= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']	= $docLanguages;
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_doctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0)
				{
					$getDocList['doc_consulted']= 1;
				}
				else 
				{
					$getDocList['doc_consulted']= 0;
				}
						
				array_push($doc_details, $getDocList);
			}
			
			if(COUNT($result_doctor)== $page_limit)
			{
				$page_val=$pageVal+1;
			}
			else
			{
				$page_val=0;
			}
			$success = array('status' => "true","doctor_list" => $doc_details, "default_doctor_list" => $default_doc_details, "pagination_val" => $page_val);
			echo json_encode($success);
		}
		
		if($filter_type == 3)	
		{
		//$params  = explode(" ", $doc_city);
		//$postid1 = $params[0];
		//$postid2 = $params[1];

			// $result_doctor = mysqlSelect("a.ref_id as ref_id,md5(a.ref_id) as doc_encyid,a.ref_name as ref_name, a.ref_exp as doc_exp, a.doc_photo as doc_photo, a.doc_city as doc_city,  a.doc_qual as doc_qual,a.doc_interest as doc_interest,b.spec_id as spec_id, b.spec_name as spec_name,d.hosp_name as hosp_name,d.hosp_addrs as hosp_addrs,d.hosp_city as hosp_city,d.hosp_state as hosp_state,d.hosp_country as hosp_country","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status=0 and ((a.doc_spec!=555 and a.anonymous_status!=1) and ((( a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","$this1, $page_limit");
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense as verified_by_medisense,a.verified_by_medisense_user as verified_by_medisense_user,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as research_details_file,a.doc_research as research_details, md5(a.ref_id) as doc_encyid,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList)
			{
				
				$getDocList['ref_id']		=	$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']	=	$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_defaultdoctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_defaultdoctorList['cons_charge_currency_type'];

				$getDocList['doc_description']				 =	$result_defaultdoctorList['description']; 
				$getDocList['professional_contribution']	 =	$result_defaultdoctorList['professional_contribution'];
				$getDocList['professional_contribution_file']=	$result_defaultdoctorList['professional_contribution_file'];
				$getDocList['research_details']				 =	$result_defaultdoctorList['research_details'];
				$getDocList['research_details_file']		 =	$result_defaultdoctorList['research_details_file'];
				
				$getDocList['Publications']				 	 =	$result_defaultdoctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_defaultdoctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_defaultdoctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_defaultdoctorList['verified_by_medisense_user'];
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
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

			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']	=	 $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']	= $docLanguages;
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_defaultdoctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) 
				{
					$getDocList['doc_consulted']= 1;
				}
				else
				{
					$getDocList['doc_consulted']= 0;
				}
						
				array_push($default_doc_details, $getDocList);  
			}
			
			$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.ref_id ,a.Research_Details_file as research_details_file,a.doc_research as research_details,md5(a.ref_id) as doc_encyid, a.ref_name,a.Professional_Construction_file as professional_contribution_file,a.doc_contribute as professional_contribution,a.doc_interest as description, a.ref_exp, a.doc_photo, a.doc_city, a.doc_country, a.ref_address, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doc_specialization as b on b.doc_id =a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 and  a.doc_country_id IN ('".$preferred_country."')","a.ref_id DESC","","","$this1, $page_limit");
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) 
			{
				$getDocList['ref_id']		=	$result_doctorList['ref_id'];
				$getDocList['doc_encyid']	=	$result_doctorList['doc_encyid'];
				$getDocList['ref_name']		=	$result_doctorList['ref_name'];
				$getDocList['doc_exp']		=	$result_doctorList['ref_exp'];
				$getDocList['doc_photo']	=	$result_doctorList['doc_photo'];
				$getDocList['doc_city']		=	$result_doctorList['doc_city'];
				$getDocList['doc_country']	=	$result_doctorList['doc_country'];
				$getDocList['doc_qual']		=	$result_doctorList['doc_qual'];
				$getDocList['doc_interest']	=	$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']	=	$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=	$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']	=	$result_doctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
				
				$getDocList['doc_description']				 =	$result_doctorList['description'];
				$getDocList['professional_contribution']	 =	$result_doctorList['professional_contribution'];
				$getDocList['professional_contribution_file']=	$result_doctorList['professional_contribution_file'];
				$getDocList['research_details']				 =	$result_doctorList['research_details'];
				$getDocList['research_details_file']		 =	$result_doctorList['research_details_file'];
				
				$getDocList['Publications']				 	 =	$result_doctorList['doc_pub'];	//Publications
				$getDocList['Publications_file']			 =	$result_doctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_doctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_doctorList['verified_by_medisense_user'];
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_doctorList['ref_id']."'","","","","");
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
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_doctorList['ref_id']."'","","","","");
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

				
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']	= 	$doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']	= 	$getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']	= $docLanguages;
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_ratings']	=	$docRatings;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_doctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) 
				{
					$getDocList['doc_consulted']= 1;
				}
				else 
				{
					$getDocList['doc_consulted']= 0;
				}
						
				array_push($doc_details, $getDocList);
			}
			
			if(COUNT($result_doctor)==$page_limit)
			{
				$page_val	=	$pageVal+1;
			}
			else
			{
				$page_val	=	0;
			}
			$success = array('status' => "true", "doctor_list" => $doc_details, "default_doctor_list" => $default_doc_details, "pagination_val" => $page_val);
			echo json_encode($success);
		}
		/*else
		{
			$success = array('status' => "false","doctor_list" => $result_doctor);
			echo json_encode($success);
		} */
		
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
