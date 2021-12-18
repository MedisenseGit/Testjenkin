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
		$memberid = $data ->userid;
			 $familyResult = $objQuery->mysqlSelect("*","user_family_member","user_id='".$memberid."'","","","","");
			
			// $familyResult = $objQuery->mysqlSelect("DISTINCT(a.member_id) as member_id, a.member_name, a.member_type, a.gender, a.age, a.relationship, a.dob, a.user_id, member_photo, COUNT(b.doc_id) as count_doctor","user_family_member as a left join doc_my_patient as b on b.member_id = a.member_id","a.user_id='".$memberid."'","","a.member_id","","");
		    
			$getUserDet=$objQuery->mysqlSelect('*','login_user',"login_id='".$memberid."'","","","",""); 
			$appointResult = $objQuery->mysqlSelect("DISTINCT(a.id) as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.hosp_id as hosp_id, e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time","a.Mobile_no='".$getUserDet[0]['sub_contact']."'","a.Visiting_date desc","","","");
			$getOpinionResult = $objQuery->mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$getUserDet[0]['sub_contact']."'","a.patient_id desc","","","");
	
	
			$response = array('status' => "true","family_array" => $familyResult,"opinion_array" => $getOpinionResult,"appoint_array" => $appointResult);
		
			echo json_encode($response);
		
		
	}
	
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


