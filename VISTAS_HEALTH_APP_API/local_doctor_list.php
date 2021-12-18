<?php
ob_start();
session_start();
error_reporting(0);

require_once("classes/querymaker.class.php");
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
//$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey)
	{
		$pageVal 		= 	$_POST['page_val'];
		$country_id1 	=	$_POST['country_id'];
		//echo $pageVal;
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

		$getCountry=mysqlSelect('*','countries',"country_id ='".$country_id1."'","","","","");
		
		if(!empty($getCountry))
		{
			$country_id = $getCountry[0]['country_id'];
			
		}
		else 
		{
			$country_id = '179'; 					// Default Local doctors 
		}
		
		$result_local_doctor = mysqlSelect("DISTINCT(ref_id) ,md5(ref_id) as doc_encyid,cons_charge,doc_country,last_active_timestamp,active_status as active_status,verified_by_medisense,verified_by_medisense_user, Publications_file, doc_pub,Research_Details_file, doc_research,Professional_Construction_file,doc_contribute,doc_interest,cons_charge_currency_type, ref_name, ref_exp, doc_photo, doc_country_id, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_country_id = '".$country_id."' AND anonymous_status=0 and doc_type='featured'","active_status desc","","","","$this1, $page_limit");
		
		
		
			$localDoc_details= array();
			foreach($result_local_doctor as $result_local_doctorList)
			{
				$getLocalDocList['ref_id']		=	$result_local_doctorList['ref_id'];
				$getLocalDocList['doc_encyid']  =	$result_local_doctorList['doc_encyid'];
				$getLocalDocList['ref_name']	=	$result_local_doctorList['ref_name'];
				$getLocalDocList['doc_exp']		=	$result_local_doctorList['ref_exp'];
				$getLocalDocList['doc_photo']	=	$result_local_doctorList['doc_photo'];
				$getLocalDocList['doc_city']	=	$result_local_doctorList['doc_city'];
				$getLocalDocList['doc_qual']	=	$result_local_doctorList['doc_qual'];
				$getLocalDocList['doc_interest']=	$result_local_doctorList['doc_interest'];
				$getLocalDocList['geo_latitude']=	$result_local_doctorList['geo_latitude'];
				$getLocalDocList['geo_longitude']=	$result_local_doctorList['geo_longitude']; 
				$getLocalDocList['doc_country_id']=$result_local_doctorList['doc_country_id'];
				//$getLocalDocList['doc_country']	=	$result_local_doctorList['doc_country'];
				
				$getLocalDocList['cons_charge']					 =	$result_local_doctorList['cons_charge']; 
				$getLocalDocList['cons_charge_currency_type']	 = 	$result_local_doctorList['cons_charge_currency_type']; 
				//$getDocList['doc_description']				 =	$result_defaultdoctorList['description']; //Area_of_interest
				$getLocalDocList['professional_contribution']	 =	$result_local_doctorList['doc_contribute']; //Professional Contribution
				$getLocalDocList['professional_contribution_file']=	$result_local_doctorList['Professional_Construction_file'];
				$getLocalDocList['research_details']			=	$result_local_doctorList['doc_research'];	//Research Details
				$getLocalDocList['research_details_file']		 =	$result_local_doctorList['Research_Details_file'];
				$getLocalDocList['Publications']				 =	$result_local_doctorList['doc_pub'];	//Publications
				$getLocalDocList['Publications_file']			 =	$result_local_doctorList['Publications_file'];
				
				$getLocalDocList['verified_by_medisense']		 =	$result_local_doctorList['verified_by_medisense'];	//Publications
				$getLocalDocList['verified_by_medisense_user']	 =	$result_local_doctorList['verified_by_medisense_user'];
				//$getLocalDocList['active_status']				 =	$result_local_doctorList['active_status'];
				$getLocalDocList['last_active_timestamp']		 =	$result_local_doctorList['last_active_timestamp'];

				// to check last Active status 
				$dateTime    = $Cur_Date;
				$tz_from     = $timezone;
				$newDateTime = new DateTime($dateTime, new DateTimeZone($tz_from));
				$newDateTime->setTimezone(new DateTimeZone("UTC"));
				$dateTimeUTC = $newDateTime->format("Y-m-d H:i:s"); //currenct time to utc
				
				$last_active_time =	$result_local_doctorList['last_active_timestamp'];
				$minutes_to_add   = 3;
				$time = new DateTime($last_active_time);
				$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
				$stamp = $time->format('Y-m-d H:i:s');
				
				$arry_Field     =   array();
				$arry_Value     =   array();
				if($result_local_doctorList['active_status']!= "2")
				{
					if($dateTimeUTC <= $stamp)
					{
						$arry_Field[]   =   "active_status";
						$arry_Value[]   =   '1';
						$getLocalDocList['active_status']	=	'1';
					}
					else
					{
						$arry_Field[]   =   "active_status";
						$arry_Value[]   =   '0';
						$getLocalDocList['active_status'] =	'0';
					}
					$update_active_status = mysqlUpdate('referal',$arry_Field,$arry_Value,"ref_id='".$result_local_doctorList['ref_id']."'");
				
				}
				else
				{
					$getLocalDocList['active_status'] =	$result_local_doctorList['active_status'];
				}
				
				
				
				
				//Academic Information
				$getAcademic_Info = mysqlSelect("*","doctor_academics","doc_id='".$result_local_doctorList['ref_id']."'","","","","");
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
				$getLocalDocList['academic_details']			 =	$academic_details;
				
				
				//work history 
				$getworkhistory = mysqlSelect("*","doc_work_exp","doc_id='".$result_local_doctorList['ref_id']."'","","","","");
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
				$getLocalDocList['workhistory']			 =	$workhistory_details;
				
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".
				$result_local_doctorList['ref_id']."'","","","","");
				
				$getLocalDocList['doc_specializations']	= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".
				$result_local_doctorList['ref_id']."'","","","","");
				
				$getLocalDocList['doc_hospitals']	= $getDocHospital;
	 
				$getDocCountry=mysqlSelect("b.country_id as country_id, b.country_name as country_name","referal as a inner join countries as b on b.country_id = a.doc_country_id","b.country_id='".$result_local_doctorList['doc_country_id']."'","","","","");
				
				$getLocalDocList['doc_country']	= $getDocCountry[0]['country_name'];
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_local_doctorList['ref_id']."'","","","","");
				
				///echo $result_local_doctorList['ref_id'];
				
				
				$getLocalDocList['doc_ratings']	=	$docRatings;
				
				array_push($localDoc_details, $getLocalDocList);
			
			}
			
			if(COUNT($result_local_doctor)==$page_limit)
			{
				$page_val	=	$pageVal+1;
			}
			else
			{
				$page_val=0;
			}
			
			$success = array('status' => "true", "local_doctor_array" => $localDoc_details, "pagination_val" => $page_val);
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
