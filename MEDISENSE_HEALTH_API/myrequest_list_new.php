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
//$objQuery = new CLSQueryMaker();
ob_start();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");

//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if(HEALTH_API_KEY == $data ->api_key && isset($data ->usermobile))
{	
	$txtMobile = $data ->usermobile;
	
	$appointResult = mysqlSelect("DISTINCT(a.id) as App_ID,a.appoint_trans_id as Trans_ID,md5(a.pref_doc) as Pref_Doc,a.hosp_id as hosp_id, e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,md5(a.patient_id) as patient_id,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings, f.payment_status as payment_made_status, a.appointment_type as appointment_type, a.pref_doc as vid_ref_id,g.pat_agora_link as video_link, a.patient_id as vid_pat_id ","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time left join payment_transaction as f on f.appoint_trans_id = a.appoint_trans_id inner join doc_my_patient as g on a.patient_id= g.patient_id","a.Mobile_no='".$txtMobile."'","a.Visiting_date desc","","","");

	$getOpinionResult = mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$txtMobile."'","a.patient_id desc","","","");

	$user_data = mysqlSelect("*","login_user","sub_contact='".$txtMobile."'","","","","");
	
	$getPharmaRequests = mysqlSelect("a.pr_id as pr_id,a.referral_type as type,b.pharma_customer_name as customer_name,b.pharma_customer_phone as customer_mobile,b.pharma_customer_email as customer_email,b.pharma_cust_state as customer_state,b.pharma_cust_city as customer_city,a.order_status as order_status","pharma_referrals as a inner join pharma_customer as b on a.pharma_customer_id=b.pharma_customer_id","a.login_id='".$user_data[0]['login_id']."'","a.pr_id desc","","","");

	$getLabRequests = mysqlSelect("a.dr_id as dr_id,a.referral_type as type,b.diagnostic_customer_name as customer_name,b.diagnostic_customer_phone as customer_mobile,b.diagnostic_customer_email as customer_email,b.diagnostic_cust_state as customer_state,b.diagnostic_cust_city as customer_city,a.order_status as order_status","diagnostic_referrals as a inner join diagnostic_customer as b on a.diagnostic_customer_id=b.diagnostic_customer_id","a.login_id='".$user_data[0]['login_id']."'","a.dr_id desc","","","");
	
	$success = array('status' => "true","appointment_details" => $appointResult,"opinion_details" => $getOpinionResult, "getPharmaRequests" => $getPharmaRequests, "getLabRequests" => $getLabRequests);
	echo json_encode($success);
}
else 
{	
	$success["status"] = "false";
	echo json_encode($success);
}

?>


