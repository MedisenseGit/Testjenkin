<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && isset($data ->userid))
	
	{
		//Get Recommended Doctor List
		$memberid = $data ->userid;
		$getUserLocation=$objQuery->mysqlSelect('sub_city','login_user',"login_id='".$memberid."'","","","","");
		if(count($getUserLocation)==0){
			$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name,ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status=0 ","doc_type_val asc","","","0,100");
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['ref_address']=$result_doctorList['ref_address'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
					
				array_push($doc_details, $getDocList);
			}
		
		
		}
		else 
		{
		$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address,ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status=0 and ref_address LIKE '%".$getUserLocation[0]['sub_city']."%'","doc_type_val asc","","","0,100");
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['ref_address']=$result_doctorList['ref_address'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
						
				array_push($doc_details, $getDocList);
			}
          		
		}
					
		$getCountries= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
		$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
		$getSpec= $objQuery->mysqlSelect("*","specialization","","spec_id asc","","","");
		$memberName = $objQuery->mysqlSelect("*", "user_family_member", "user_id='".$memberid."'", "", "", "", "");
		
		$response = array('status' => "true","doctor_array" => $doc_details,"getCountries" => $getCountries,"getState" => $GetState,"spec_array" => $getSpec,"memberDet"=>$memberName);
		
			echo json_encode($response);
		
		
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==1 && isset($data ->userid))	
	{
		$memberid = $data ->userid;
		$result_doctor = $objQuery->mysqlSelect("a.ref_id ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_address,a.ref_exp, a.doc_photo, a.doc_city, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a inner join (select ref_id,max(timestamp) as timestamp ,status2,bucket_status from patient_referal where status2=5 and bucket_status=5 group by ref_id) as b on a.ref_id=b.ref_id","a.doc_spec!=555 and a.anonymous_status=0","b.timestamp desc","","","0,100");
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['ref_address']=$result_doctorList['ref_address'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
						
				array_push($doc_details, $getDocList);
			}
			
			$getUserDet=$objQuery->mysqlSelect('*','login_user',"login_id='".$memberid."'","","","",""); 
			$appointResult = $objQuery->mysqlSelect("DISTINCT(a.id) as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.hosp_id as hosp_id, e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.Mobile_no='".$getUserDet[0]['sub_contact']."'","a.Visiting_date desc","","","");
			$getOpinionResult = $objQuery->mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$getUserDet[0]['sub_contact']."'","a.patient_id desc","","","");
	
			$response = array('status' => "true","doctor_array" => $doc_details,"opinion_array" => $getOpinionResult,"appoint_array" => $appointResult);
		
			echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)	
	{
		$memberid = $data ->userid;
		$result_doctor = $objQuery->mysqlSelect("DISTINCT(a.ref_id) ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_address,a.ref_exp, a.doc_photo, a.doc_city, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id","a.doc_spec!=555 and a.anonymous_status=0 and md5(b.hosp_id)='".$data->filter_search."'","","","","0,100");
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['ref_address']=$result_doctorList['ref_address'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
						
				array_push($doc_details, $getDocList);
			}
			$hosp_name=$objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","md5(b.hosp_id)= '".$data->filter_search."'","","","","");
			
			$getUserDet=$objQuery->mysqlSelect('*','login_user',"login_id='".$memberid."'","","","",""); 
			$appointResult = $objQuery->mysqlSelect("DISTINCT(a.id) as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.hosp_id as hosp_id, e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.Mobile_no='".$getUserDet[0]['sub_contact']."'","a.Visiting_date desc","","","");
			$getOpinionResult = $objQuery->mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$getUserDet[0]['sub_contact']."'","a.patient_id desc","","","");
	
			$response = array('status' => "true","doctor_array" => $doc_details,"hosp_array"=>$hosp_name,"opinion_array" => $getOpinionResult,"appoint_array" => $appointResult);
		
			echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


