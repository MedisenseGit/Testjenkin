<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");
include('../premium/short_url.php');
$ccmail = "medical@medisense.me";


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Consultation Payments
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$user_id = $user_id;
		$member_id = $_POST['member_id'];

		$doc_id = $_POST['doc_id'];
		$consultation_type = $_POST['consultation_type'];
		$txtName = $_POST['member_name'];
		$txtGen = $_POST['member_gender'];			 // 1- Male, 2-Female, 3-Other, 0-Not Mentioned
		$txtAge = $_POST['member_age'];
		$member_height = $_POST['member_height'];
		$member_weight = $_POST['member_weight'];
		$member_blood_group = $_POST['member_blood_group'];
		$member_bp = $_POST['member_bp'];
		$member_thyroid = $_POST['member_thyroid'];
		$member_hypertension = $_POST['member_hypertension'];
		$member_asthama = $_POST['member_asthama'];
		$member_cholestrol = $_POST['member_cholestrol'];
		$member_epilepsy = $_POST['member_epilepsy'];
		$member_diabetic = $_POST['member_diabetic'];
		$member_allergies = $_POST['member_allergies'];
		$member_smoking = $_POST['member_smoking'];
		$member_alcohol = $_POST['member_alcohol'];
		$member_consult_lang_id = $_POST['member_consult_lang_id'];
		$member_consult_lang_name = $_POST['member_consult_lang_name'];
		$member_doc_origin = $_POST['member_doc_origin'];
		$hospital_id = $_POST['doc_hospital_id'];
		$consult_charge = $_POST['consultation_charge'];
		$txtMob = $_POST['contact_num'];
		$txtMail = $_POST['user_email'];
		$txtAppointType = 2;  				// 0 - Walkin, 1- Appointment, 2-Teleconsultation
		$transaction_id = $_POST['transactionID'];
		$transaction_status = $_POST['transactionStatus'];
		$transaction_amount = $_POST['transactionAmount'];
		$transaction_currency = $_POST['transactionCurrency'];
		
		$doc_my_patient_id = $_POST['docMyPatientID'];
		$appointment_transaction_id = $_POST['appointTransactionID'];
		$appointment_token_id = $_POST['appointTokenID'];
		
		$transid = time();
		$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
			
			
					$pay_status = "VC Confirmed";
					
					// Update Payment Status in  Appointment Transaction Detail Table
					$arrFieldsAppointTrans = array();
					$arrValuesAppointTrans = array();
					$arrFieldsAppointTrans[]='pay_status';
					$arrValuesAppointTrans[]=$pay_status;
					$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointTrans,$arrValuesAppointTrans,"id='".$appointment_transaction_id."'");
				
					// Update Payment Status in  Appointment appointment_token_system Table
					$arrFieldsAppointToken = array();
					$arrValuesAppointToken = array();
					$arrFieldsAppointToken[]='pay_status';
					$arrValuesAppointToken[]=$pay_status;
					$updateAppointTrans=mysqlUpdate('appointment_token_system',$arrFieldsAppointToken,$arrValuesAppointToken,"token_id='".$appointment_token_id."'");
				
					// Update Payment Transaction Table
					$arrFieldsPayment = array();
					$arrValuesPayment = array();
					$arrFieldsPayment[]='patient_name';
					$arrValuesPayment[]=$txtName;
					$arrFieldsPayment[]='patient_id';
					$arrValuesPayment[]=$doc_my_patient_id;
					$arrFieldsPayment[]='trans_date';
					$arrValuesPayment[]=$curDate;
					$arrFieldsPayment[]='narration';
					$arrValuesPayment[]='Consultation Charge';
					$arrFieldsPayment[]='amount';
					$arrValuesPayment[]=$transaction_amount;
					$arrFieldsPayment[]='currency_type';
					$arrValuesPayment[]=$transaction_currency;
					$arrFieldsPayment[]='user_id';
					$arrValuesPayment[]=$doc_id;
					$arrFieldsPayment[]='user_type';
					$arrValuesPayment[]='1';
					$arrFieldsPayment[]='hosp_id';
					$arrValuesPayment[]=$hospital_id;
					$arrFieldsPayment[]='payment_status';
					$arrValuesPayment[]='PAID';
					$arrFieldsPayment[]='pay_method';
					$arrValuesPayment[]='PAYPAL';
					$arrFieldsPayment[]='appoint_trans_id';
					$arrValuesPayment[]=$appointment_transaction_id;
					$arrFieldsPayment[]='login_uer_id';
					$arrValuesPayment[]=$user_id;
					$payment_add= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
				
				
				$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
						
		
		$success_consults = array('result' => "success",  'result_wallet' => $result_wallet, 'message' => "Consultation Booked Successfully !!! \nYou will receive an Email/SMS with payment link to confirm the consultation.", 'err_msg' => '');
		echo json_encode($success_consults);

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
