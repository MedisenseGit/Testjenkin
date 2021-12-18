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

// Physical Appointments DOCTOR SEARCH 
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$search_string = $_POST['search_string'];
		$user_id = $user_id;
		
		$pageVal = $_POST['page_val']; 
		$params     = explode(" ", $_POST['search_string']);
		$postid1 = $params[0];
		$postid2 = $params[1];
		//echo $_POST['search_string'];
		//echo $postid1;
		//echo $postid2;
		
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
		
		$result_doctor = mysqlSelect("ref_id, md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_country, doc_qual, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type","referal", "((doc_spec!=555 and anonymous_status!=1) and (((ref_name LIKE '%".$postid1."%' or ref_address LIKE '%".$postid1."%' or doc_city LIKE '%".$postid1."%' or doc_state LIKE '%".$postid1."%') and (ref_address LIKE '%".$postid2."%' or doc_city LIKE '%".$postid2."%' or doc_state LIKE '%".$postid2."%'))))","ref_id DESC","","","$this1, $page_limit");
		

		//$result_doctor = mysqlSelect("ref_id, md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_country, doc_qual, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type","referal", "((doc_spec!=555 and anonymous_status!=1) and ((ref_name LIKE '%".$search_string."%' or doc_interest LIKE '%".$search_string."%' or doc_research LIKE '%".$search_string."%' or doc_contribute LIKE '%".$search_string."%' or doc_pub LIKE '%".$search_string."%') or ((ref_name LIKE '%".$postid1."%' or ref_address LIKE '%".$postid1."%' or doc_city LIKE '%".$postid1."%' or doc_state LIKE '%".$postid1."%') and (ref_address LIKE '%".$postid2."%' or doc_city LIKE '%".$postid2."%' or doc_state LIKE '%".$postid2."%'))))","doc_type_val asc","","","$this1, $page_limit");
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
			$success = array('status' => "true", 'specialization_id' => $specialization_id, "doctor_list" => $doc_details,"pagination_val" => $page_val);
			echo json_encode($success);
	
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