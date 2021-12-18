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
//$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Doctors Lists
/*if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey)
	{*/
		$filter_type = $_POST['doc_filter_type'];
		$spec_id     = $_POST['doc_spec_id'];
		$doc_city    = $_POST['doc_city'];
		$pageVal     = $_POST['page_val'];
		$preferred_language = $_POST['preferred_language'];
		$preferred_doc_origin = $_POST['preferred_doc_origin'];
		
		//echo "filter_type =".$filter_type."|".$spec_id."|".$preferred_language." <br>";
		
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
			
			if(!empty($_POST['specialty_id'])) 
			{		// Specialty selected is not empty
				$specialization_id=implode(',',$_POST['specialty_id']);
				$result_doctor = mysqlSelect("a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND a.doc_country LIKE '%".$preferred_doc_origin."%' AND b.language_id = '".$preferred_language."' AND c.spec_id IN (".$specialization_id.")","a.ref_id DESC","","","$this1, $page_limit");		
			}
			else 
			{  // Specialty selected is empty
				$result_doctor = mysqlSelect("a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 AND a.doc_country LIKE '%".$preferred_doc_origin."%' AND b.language_id = '".$preferred_language."'","a.ref_id DESC","","","$this1, $page_limit");
			}
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList)
			{
				
				$getDocList['ref_id']=$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']=$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']=$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']=$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']=$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']=$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']=$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']=$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']=$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_defaultdoctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_defaultdoctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
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

			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_country']=$result_doctorList['doc_country'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
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
		
		else if($filter_type == 2)		
		{
		
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList) {
				
				$getDocList['ref_id']=$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']=$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']=$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']=$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']=$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']=$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']=$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']=$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']=$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_defaultdoctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_defaultdoctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
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
			//echo $preferred_language;
			$result_doctor = mysqlSelect("a.ref_id ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_country, a.ref_address, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doc_specialization as b on b.doc_id =a.ref_id inner join doctor_langauges as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 and c.language_id = '".$preferred_language."'","a.ref_id DESC","","","");
			
			//echo"<br>";
			//var_dump($result_doctor);
			
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_country']=$result_doctorList['doc_country'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_doctorList['cons_charge'];
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_doctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) {
					$getDocList['doc_consulted']= 1;
				}
				else {
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
			$success = array('status' => "true","doctor_list" => $doc_details, "default_doctor_list" => $default_doc_details, "pagination_val" => $page_val);
			echo json_encode($success);
		}
		
		else if($filter_type == 3)	
		{
		//$params  = explode(" ", $doc_city);
		//$postid1 = $params[0];
		//$postid2 = $params[1];

			// $result_doctor = mysqlSelect("a.ref_id as ref_id,md5(a.ref_id) as doc_encyid,a.ref_name as ref_name, a.ref_exp as doc_exp, a.doc_photo as doc_photo, a.doc_city as doc_city,  a.doc_qual as doc_qual,a.doc_interest as doc_interest,b.spec_id as spec_id, b.spec_name as spec_name,d.hosp_name as hosp_name,d.hosp_addrs as hosp_addrs,d.hosp_city as hosp_city,d.hosp_state as hosp_state,d.hosp_country as hosp_country","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status=0 and ((a.doc_spec!=555 and a.anonymous_status!=1) and ((( a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","$this1, $page_limit");
			
			$getDefault_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0  AND a.nova_default_doctor=1","a.ref_id DESC","","","");
			$default_doc_details= array();
			foreach($getDefault_doctor as $result_defaultdoctorList) {
				
				$getDocList['ref_id']=$result_defaultdoctorList['ref_id'];
				$getDocList['doc_encyid']=$result_defaultdoctorList['doc_encyid'];
				$getDocList['ref_name']=$result_defaultdoctorList['ref_name'];
				$getDocList['doc_exp']=$result_defaultdoctorList['ref_exp'];
				$getDocList['doc_photo']=$result_defaultdoctorList['doc_photo'];
				$getDocList['doc_city']=$result_defaultdoctorList['doc_city'];
				$getDocList['doc_country']=$result_defaultdoctorList['doc_country'];
				$getDocList['doc_qual']=$result_defaultdoctorList['doc_qual'];
				$getDocList['doc_interest']=$result_defaultdoctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_defaultdoctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_defaultdoctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_defaultdoctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_defaultdoctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_defaultdoctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_defaultdoctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_defaultdoctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) {
					$getDocList['doc_consulted']= 1;
				}
				else {
					$getDocList['doc_consulted']= 0;
				}
						
				array_push($default_doc_details, $getDocList);  
			}
			
			$result_doctor = mysqlSelect("a.ref_id ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_country, a.ref_address, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doc_specialization as b on b.doc_id =a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 and  a.doc_country LIKE '%".$preferred_doc_origin."%'","a.ref_id DESC","","","$this1, $page_limit");
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_country']=$result_doctorList['doc_country'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
				$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
				$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
				$mydoctor = mysqlSelect("*","doc_my_patient","member_id='".$member_id."' and doc_id='".$result_doctorList['ref_id']."'","patient_id DESC","","","");
				if(count($mydoctor)>0) {
					$getDocList['doc_consulted']= 1;
				}
				else {
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
		else 
		{
			$success = array('status' => "false","doctor_list" => $result_doctor);
			echo json_encode($success);
		} 
		
	/*}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	*/
/*}
else
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}*/

?>
