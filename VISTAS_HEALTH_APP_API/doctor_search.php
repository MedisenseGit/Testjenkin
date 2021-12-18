<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
$Cur_Date=date('Y-m-d H:i:s');

$ip     = $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//DOCTOR SEARCH 
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey)
	{
		//$_POST['search_string']='john';
		$search_string = 	$_POST['search_string'];
		$params    	   = 	explode(" ", $_POST['search_string']);
		$user_id	   = 	$user_id;
		$pageVal 	   =	$_POST['page_val'];
		if(!empty($postid1) && !empty($postid2))
		{
			$postid1 = $params[0];
			$postid2 = $params[1];
		}
		else
		{
			$postid1 = $_POST['search_string'];
			$postid2 = $_POST['search_string'];
			
		}
		
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
		//$_POST['specialty_id']
		
		if(!empty($_POST['specialty_id'])) 
		{		
				// Specialty selected is not empty
				
				$specialization_id=implode(',',$_POST['specialty_id']);
				if(empty($specialization_id))
				{
					$specialization_id=$_POST['specialty_id'];
				}
				$spec_id="'".$specialization_id."'";
				
				$result_doctor = mysqlSelect("a.active_status as active_status,a.last_active_timestamp as last_active_timestamp,a.verified_by_medisense_user as verified_by_medisense_user,a.verified_by_medisense as verified_by_medisense,a.Publications_file as Publications_file,a.doc_pub as doc_pub,a.Research_Details_file as Research_Details_file, a.doc_research as doc_research,a.Professional_Construction_file as Professional_Construction_file,a.doc_contribute as doc_contribute,a.doc_interest as doc_interest,a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_country as doc_country, a.doc_qual as doc_qual, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type","referal as a inner join doctor_langauges as b on b.doc_id=a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id", "(a.doc_spec!=555 and a.anonymous_status!=1) and (a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_city LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') AND c.spec_id IN (".$spec_id.") or (a.ref_address LIKE '%".$postid2."%' or a.doc_city LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%')","a.active_status DESC","","","$this1, $page_limit");
				
				

		
		}
		else 
		{  
			
			$result_doctor = mysqlSelect("last_active_timestamp as last_active_timestamp,active_status as active_status,verified_by_medisense,verified_by_medisense_user,Publications_file,doc_pub, Research_Details_file,doc_research,Professional_Construction_file, doc_contribute,ref_id, md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_country, doc_qual, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type","referal","(doc_spec!=555 and anonymous_status!=1) and (ref_name LIKE '%".$postid1."%' or ref_address LIKE '%".$postid1."%' or doc_city LIKE '%".$postid1."%' or doc_state LIKE '%".$postid1."%') or (ref_address LIKE '%".$postid2."%' or doc_city LIKE '%".$postid2."%' or doc_state LIKE '%".$postid2."%') ","active_status desc","","","","$this1, $page_limit");
				
				
				 
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
				
				
				
				$getDocList['professional_contribution']	 =	$result_doctorList['doc_contribute']; 			//Professional Contribution
				$getDocList['professional_contribution_file']=	$result_doctorList['Professional_Construction_file'];
				$getDocList['research_details']				 =	$result_doctorList['doc_research'];				//Research Details
				$getDocList['research_details_file']		 =	$result_doctorList['Research_Details_file'];
				$getDocList['Publications']				 	 =	$result_doctorList['doc_pub'];					//Publications
				$getDocList['Publications_file']			 =	$result_doctorList['Publications_file'];
				
				$getDocList['verified_by_medisense']		 =	$result_doctorList['verified_by_medisense'];	//Publications
				$getDocList['verified_by_medisense_user']	 =	$result_doctorList['verified_by_medisense_user'];
				$getDocList['active_status']	 			 =	$result_doctorList['active_status'];
				$getDocList['last_active_timestamp']	 	 =	$result_doctorList['last_active_timestamp'];
				
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
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$docLanguages = mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
				$getDocList['doc_languages']= $docLanguages;
				
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
			
			$success = array('status' => "true", 'specialization_id' => $specialization_id, "doctor_list" => $doc_details,"pagination_val" => $page_val);
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