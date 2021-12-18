<?php 
	ob_start();
 	error_reporting(0);
 	session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

// time zone 
$ip = $_SERVER['REMOTE_ADDR']; // find time zone
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);
if(empty($timezone))
{
	$timezone ='Asia/Kolkata'; // this is for local 
}

$today = date("D");

// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

	if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)
	{
		if($data->doctor != "")
		{
			$doctor=$data->doctor;
			$doc = ' and MATCH(a.ref_name) AGAINST("'. $doctor .'" IN NATURAL LANGUAGE MODE) and a.ref_name LIKE "%'.$doctor.'%" ';
		}
		else
		{
			$doc="";
			
		}

		if($data->lang!="")
		{
			$lang=$data->lang;
			$language=mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","b.id='".$lang."'","","","","");
			
		}
		else
		{
			//$lang=array();
			$lang1=mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","","","","","");
			//$lang=$lang[0]['id'];

			$lang="";
	        foreach($lang1 as $language)
	        {
	            if($lang != "")
	            $lang .= ",";
	            $lang .= $language['id'];
	           
	        }
		}
		if($data->country!="")
		{
			$country=$data->country;

			$cntry=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$country."'","","","","");

			$country="and a.doc_country_id ='".$country."'";
		}
		else if($data->country_name!=""){

			$cntry=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","md5(a.country_name)='".$data->country_name."'","","","","");
			$cn=$cntry[0]['country_id'];

			$country="and a.doc_country_id ='".$cn."'";
		}

		else if($data->global_country_name!=""){

			$cntry1=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","md5(a.country_name) !='".$data->global_country_name."'","","","","");


			$cn="";
	        foreach($cntry1 as $country)
	        {
	            if($cn != "")
	            $cn .= ",";
	            $cn .= $country['country_id'];
	           
	        }
			$country="and a.doc_country_id ='".$cn."'";
		}

		else
		{
			$country="";
			
		}
		if($data->spcl!="")
		{
			$spcl=$data->spcl;
			$specialization=mysqlSelect("*","specialization","spec_id in(".$spcl.")","spec_id asc","","","");
			$spcl="and c.spec_id in (".$spcl.")";
			
		}
		else
		{
			$spcl="";
		}


		$pageVal = $data->page;
		if($pageVal==1)
		{
			$this1 = 0;
			$page_limit = 15;
		}
		else
		{

		  ($page - 1) * $limit;
		  $limit = 15*$pageVal;
		  $page_limit = 15;
		  $this1 = $limit-15;
		}

		//$result_doctor = mysqlSelect("DISTINCT a.ref_id,a.verified_by_medisense as verified_by_medisense,a.video_veification_status as video_veification_status,a.verified_by_medical_professional as verified_by_medical_professional, a.ref_name, a.ref_address, a.ref_exp, a.doc_photo,a.doc_country_id, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.physical_consultation_charge,a.cons_charge_currency_type,a.cons_charge, a.doc_contribute, a.doc_research,a.active_status","referal as a left join doctor_langauges as b on a.ref_id=b.doc_id left join doc_specialization as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and b.language_id in (".$lang.")  ".$country." ".$spcl." ".$doc,"a.active_status desc","","","$this1,$page_limit");


		$result_doctor = mysqlSelect("DISTINCT a.ref_id,a.verified_by_medisense as verified_by_medisense,a.video_veification_status as video_veification_status,a.verified_by_medical_professional as verified_by_medical_professional, a.ref_name, a.ref_address, a.ref_exp, a.doc_photo,a.doc_country_id, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.physical_consultation_charge,a.cons_charge_currency_type,a.cons_charge, a.doc_contribute, a.doc_research,a.active_status","referal as a left join doctor_langauges as b on a.ref_id=b.doc_id left join doc_specialization as c on c.doc_id=a.ref_id LEFT join doctor_appointment_slots_set as d on d.doc_id=a.ref_id LEFT join appointment_utc_slots as e on e.id=d.time_id","a.anonymous_status!=1 and b.language_id in (".$lang.")  ".$country." ".$spcl." ".$doc,"a.active_status desc,e.utc_slots DESC","","","$this1,$page_limit");


		$doctor_count = mysqlSelect("count(a.ref_id) as ref_id","referal as a inner join doctor_langauges as b on a.ref_id=b.doc_id inner join doc_specialization as c on c.doc_id=a.ref_id","a.anonymous_status!=1 and b.language_id in (".$lang.")  ".$country." ".$spcl." ".$doc,"","","","");

			$getDocList['ref_id1']=$doctor_count[0]['ref_id'];

			$doc_details= array();
			foreach($result_doctor as $result_doctorList) 
			{
				$getDocList['ref_id']			=	$result_doctorList['ref_id'];
				$getDocList['ref_name']			=	$result_doctorList['ref_name'];
				$getDocList['ref_address']		=	$result_doctorList['ref_address'];
				$getDocList['doc_exp']			=	$result_doctorList['ref_exp'];
				$getDocList['doc_photo']		=	$result_doctorList['doc_photo'];
				$getDocList['doc_country_id']	=	$result_doctorList['doc_country_id'];
				$getDocList['doc_city']			=	$result_doctorList['doc_city'];
				$getDocList['doc_state']		=	$result_doctorList['doc_state'];
				$getDocList['doc_qual']			=	$result_doctorList['doc_qual'];
				$getDocList['active_status']	=	$result_doctorList['active_status'];
				$getDocList['doc_interest']		=	$result_doctorList['doc_interest'];
				$getDocList['video_cons_charge']=	$result_doctorList['cons_charge'];
				$getDocList['doc_contribute']	=	$result_doctorList['doc_contribute'];
				$getDocList['doc_research']		=	$result_doctorList['doc_research'];
				$getDocList['physical_cons_charge']		=	$result_doctorList['physical_consultation_charge'];
				$getDocList['cons_charge_currency_type']=	$result_doctorList['cons_charge_currency_type'];
				$getDocList['verified_by_medisense']	=	$result_doctorList['verified_by_medisense'];
				$getDocList['video_veification_status']	=	$result_doctorList['video_veification_status'];
				$getDocList['verified_by_medical_professional']=	$result_doctorList['verified_by_medical_professional'];
			
				$doc_specialization = mysqlSelect('a.spec_id as spec_id, b.spec_name as spec_name','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;

				$getDocCountry=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList['doc_country_id']."'","","","","");
				$getDocList['doc_country']= $getDocCountry;

				$getDocLang = mysqlSelect('b.name','doctor_langauges as a inner join languages as b on a.language_id=b.id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				
				$getFeedBack = mysqlSelect('SUM(`ratings`) as total ,count(`patient_id`) as count_val','doctor_feedback',"doc_id='".$result_doctorList['ref_id']."'","","","","");
				
				$getComments = mysqlSelect('*','doctor_feedback',"doc_id='".$result_doctorList['ref_id']."'","","","","");

				$gethosp_id = mysqlSelect('*','doctor_hosp',"doc_id='".$result_doctorList['ref_id']."'","","","","");

				
				
				$getDocList['ratings']			=	$getFeedBack;
				$getDocList['comments']			=	$getComments;
				$getDocList['doc_language']		= 	$getDocLang;
				$getDocList['page_limit']		=	$page_limit;
				$doc_id		=	$result_doctorList['ref_id'];
				$hosp_id	=	$gethosp_id[0]['hosp_id'];
				$day		=	$today;
				
				//$selec_date	=	$_POST["selec_date"];
				//$getday_id  =   mysqlSelect("day_id","seven_days","da_name='".$day."'","","","","");
				//$GetTimeSlot=	mysqlSelect("a.id as id,b.time_id AS time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hosp_id."' AND b.day_id = '".$getday_id[0]['day_id']."'","","","","");	
				//$getDocList['GetTimeSlot']	= $GetTimeSlot;
				array_push($doc_details, $getDocList);
			
			}
					
		$getCountries= mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","","a.country_id asc","","","");
		$GetState 	 = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getUserDet[0]['sub_country']."'", "b.state_name asc", "", "", "");
		$getSpec	 = mysqlSelect("*","specialization","","spec_id asc","","","");
		$getLang	 = mysqlSelect("DISTINCT(b.id) as id,b.name","doctor_langauges as a inner join languages as b on a.language_id=b.id","","","","","");
		$default_doctor = mysqlSelect("DISTINCT(a.ref_id) as ref_id,md5(a.ref_id) as doc_encyid,a.verified_by_medisense as verified_by_medisense,a.video_veification_status as video_veification_status,a.verified_by_medical_professional as verified_by_medical_professional, a.ref_name, a.ref_address, a.ref_exp, a.doc_photo,a.doc_country_id, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.physical_consultation_charge,a.cons_charge_currency_type, a.doc_contribute, a.doc_research, a.geo_latitude, a.geo_longitude,a.active_status","referal as a left join doctor_langauges as b on a.ref_id=b.doc_id left join doc_specialization as c on c.doc_id=a.ref_id","nova_default_doctor=1","a.ref_id desc","","","");


		$doc_details1= array();
		foreach($default_doctor as $result_doctorList1) 
		{
			$getDocList1['ref_id']			=	$result_doctorList1['ref_id'];
			$getDocList1['doc_encyid']		=	$result_doctorList1['doc_encyid'];
			$getDocList1['ref_name']		=	$result_doctorList1['ref_name'];
			$getDocList1['ref_address']		=	$result_doctorList1['ref_address'];
			$getDocList1['doc_exp']			=	$result_doctorList1['ref_exp'];
			$getDocList1['doc_photo']		=	$result_doctorList1['doc_photo'];
			$getDocList1['doc_city']		=	$result_doctorList1['doc_city'];
			$getDocList1['doc_state']		=	$result_doctorList1['doc_state'];
			$getDocList1['doc_country_id']	=	$result_doctorList1['doc_country_id'];
			$getDocList1['doc_qual']		=	$result_doctorList1['doc_qual'];
			$getDocList1['ref_address']		=	$result_doctorList1['ref_address'];
			$getDocList1['doc_interest']	=	$result_doctorList1['doc_interest'];
			$getDocList1['geo_latitude']	=	$result_doctorList1['geo_latitude'];
			$getDocList1['geo_longitude']	=	$result_doctorList1['geo_longitude']; 
			$getDocList1['active_status']	=	$result_doctorList1['active_status']; 
			$getDocList1['cons_charge']		=	$result_doctorList1['cons_charge']; 
			$getDocList1['doc_contribute']	=	$result_doctorList1['doc_contribute'];
			$getDocList1['doc_research']	=	$result_doctorList1['doc_research'];
			$getDocList1['consult_charge']	=	$result_doctorList1['physical_consultation_charge'];
			$getDocList1['cons_charge_currency_type']	=	$result_doctorList1['cons_charge_currency_type']; 	
			$getDocList1['verified_by_medisense']		=	$result_doctorList1['verified_by_medisense'];
			$getDocList1['video_veification_status']		=	$result_doctorList1['video_veification_status'];
			$getDocList1['verified_by_medical_professional']=	$result_doctorList1['verified_by_medical_professional'];
		
			$doc_specialization1 = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList1['ref_id']."'","","","","");
			$getDocList1['doc_specializations']= $doc_specialization1;
			
			$getDocHospital1 = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a left join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList1['ref_id']."'","","","","");
			$getDocList1['doc_hospitals']= $getDocHospital1;

			$getDocLang1 = mysqlSelect('b.name','doctor_langauges as a left join languages as b on a.language_id=b.id',"a.doc_id='".$result_doctorList1['ref_id']."'","","","","");

			$getDocList1['doc_languages']= $getDocLang1;


			$getDocCountry1=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a left join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList1['doc_country_id']."'","","","","");
			$getDocList1['doc_country']= $getDocCountry1;
			
			$getFeedBack = mysqlSelect('SUM(`ratings`) as total ,count(`patient_id`) as count_val','doctor_feedback',"doc_id='".$result_doctorList1['ref_id']."'","","","","");
				
			$getComments = mysqlSelect('*','doctor_feedback',"doc_id='".$result_doctorList1['ref_id']."'","","","","");
				
			$getDocList1['ratings']		=	$getFeedBack;
			$getDocList1['comments']	=	$getComments;
				
			array_push($doc_details1, $getDocList1);
		}

		if(COUNT($doc_details)==$page_limit)
		{
			$page_val=$pageVal+1;
		}
		
		else
		{
			$page_val=0;
		}		
	
		

		$response = array('status' => "true","getState" => $GetState,"user_array" => $getUserDet,"doctor_array" => $doc_details,"GetTimeSlot"=>$GetTimeSlot, "default_doctor" => $doc_details1,"getCountries" => $getCountries,"spec_array" => $getSpec,"getLang" => $getLang,'doc_lang'=>$language,"specialization"=>$specialization,"cntry"=>$cntry,"pagination_val" => $page_val,"this1"=>$this1,"page_limit"=>$page_limit);
		
			echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


