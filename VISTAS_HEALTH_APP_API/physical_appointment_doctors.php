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

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Doctors Lists - for Appointments
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$filter_type = $_POST['doc_filter_type']; 
		$spec_id = $_POST['doc_spec_id'];
		$doc_city = $_POST['doc_city'];
		$pageVal = $_POST['page_val'];
		$preferred_language = $_POST['preferred_language'];
		
		if($pageVal==1){
			$this1 = 0;
			$page_limit = 15;
		}
		else if($pageVal>1)
		{
			$limit = 15*$pageVal;
			$page_limit = 15;
			$this1 = $limit-15;
		}
		
		$novaHospitalID = 1093;   // Here we display only Nova doctors for physical appointments


		if($filter_type == 1)
		{
			$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.physical_consultation_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_hosp as b on b.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND b.hosp_id='".$novaHospitalID."' ","a.ref_id DESC","","","$this1, $page_limit");
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
			else{
				$page_val=0;
			}
			$success = array('status' => "true", 'novaHospitalID' => $novaHospitalID, "doctor_list" => $doc_details,"pagination_val" => $page_val);
			echo json_encode($success);
		}
		
		if($filter_type == 2)		
		{
			
			//$result_doctor = mysqlSelect("DISTINCT(a.ref_id) ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_country, a.ref_address, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doc_specialization as b on b.doc_id =a.ref_id inner join doctor_langauges as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.nova_default_doctor!=1 and c.language_id = '".$preferred_language."'","a.ref_id DESC","","","$this1, $page_limit");
			$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.physical_consultation_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_hosp as b on b.doc_id=a.ref_id inner join doctor_langauges as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND b.hosp_id='".$novaHospitalID."' and c.language_id = '".$preferred_language."'","a.ref_id DESC","","","$this1, $page_limit");
			
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
		
		if($filter_type == 3)
		{
			
			$result_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.physical_consultation_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_hosp as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND b.hosp_id='".$novaHospitalID."' AND a.nova_default_doctor!=1 AND c.spec_id IN (".$spec_id.")","a.ref_id DESC","","","$this1, $page_limit");
			
			
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
			else{
				$page_val=0;
			}
			$success = array('status' => "true", "doctor_list" => $doc_details, "pagination_val" => $page_val);
			echo json_encode($success);
		}
		
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>
