<?php
ob_start();
error_reporting(0);
session_start();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

// get posted data
$data = json_decode(file_get_contents("php://input"));

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
include('../premium/send_mail_function.php');
include("../premium/send_text_message.php");
include('../premium/short_url.php');
include('../VISTAS_HEALTH_APP/send_push_notifications.php');
$ccmail = "medical@medisense.me";

// Book Consultation	
if(HEALTH_API_KEY == $data ->api_key){
	
	$user_id = $data ->user_id;
	$member_id = $data ->member_id;
	$patient_id = $data ->patient_id;
	$doc_id = $data ->doc_id;
	$appointTransID = $data ->appoint_trans_id;
	$appointConsultCharge = $data ->consult_charge;
	$appointConsultChargeType = addslashes($data ->consult_charge_type);
	$hospital_id = $data ->hospital_id;
	$transid = time();
		
		$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
		$get_patInfo = mysqlSelect('*','doc_my_patient',"patient_id='".$patient_id."'");
		//$patient_VideoCallLink = $get_patInfo[0]['pat_video_link'];
		$patient_VideoCallLink = $get_patInfo[0]['pat_agora_link'];
		
			$getWalletBalance = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
			$deduct_amount = $get_pro[0]['cons_charge'];
			
			if($getWalletBalance[0]['amount_currency_type'] == $appointConsultChargeType) {
				if(empty($getWalletBalance)){
					$payment_status = 0;		// Payment Pending
					$payment_type = $appointConsultChargeType;	// Payment Mode Type
				}
				else {
					if($getWalletBalance[0]['Total_Amount'] >= $get_pro[0]['cons_charge']) {
						$payment_status = 1;					// Payment Done
						$current_balance = $getWalletBalance[0]['Total_Amount'];
						$deduct_amount = $get_pro[0]['cons_charge'];
						$remaining_balance = $current_balance - $deduct_amount;
						$currency_type = $get_pro[0]['cons_charge_currency_type'];
						
						$arrFieldsWallet=array();	
						$arrValuesWallet=array();
						
						$arrFieldsWallet[]='tansaction_id';
						$arrValuesWallet[]=$appointTransID;
						$arrFieldsWallet[]='login_id';
						$arrValuesWallet[]=$user_id;
						$arrFieldsWallet[]='amount_deducted';
						$arrValuesWallet[]=$deduct_amount;
						$arrFieldsWallet[]='Total_Amount';
						$arrValuesWallet[]=$remaining_balance;
						$arrFieldsWallet[]='amount_currency_type';
						$arrValuesWallet[]=$currency_type;
						$arrFieldsWallet[]='created_date';
						$arrValuesWallet[]=$curDate;
						$app_notify= mysqlInsert('health_app_wallet',$arrFieldsWallet,$arrValuesWallet);
						$pay_status = "VC Confirmed";
						
						// Update Payment Status in  Appointment Transaction Detail Table
						$arrFieldsAppointTrans = array();
						$arrValuesAppointTrans = array();
						$arrFieldsAppointTrans[]='pay_status';
						$arrValuesAppointTrans[]=$pay_status;
						$updateAppointTrans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppointTrans,$arrValuesAppointTrans,"appoint_trans_id='".$appointTransID."'");
					
						// Update Payment Status in  Appointment appointment_token_system Table
						$arrFieldsAppointToken = array();
						$arrValuesAppointToken = array();
						$arrFieldsAppointToken[]='status';
						$arrValuesAppointToken[]=$pay_status;
						$updateAppointTrans=mysqlUpdate('appointment_token_system',$arrFieldsAppointToken,$arrValuesAppointToken,"appoint_trans_id='".$appointTransID."'");
					
						// Update Payment Transaction Table
						$arrFieldsPayment = array();
						$arrValuesPayment = array();
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$get_patInfo[0]['patient_name'];
						$arrFieldsPayment[]='patient_id';
						$arrValuesPayment[]=$patient_id;
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$curDate;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]='Consultation Charge';
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$deduct_amount;
						$arrFieldsPayment[]='currency_type';
						$arrValuesPayment[]=$currency_type;
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$doc_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]='1';
						$arrFieldsPayment[]='hosp_id';
						$arrValuesPayment[]=$hospital_id;
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]='PAID';
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]='Health Wallet';
						$arrFieldsPayment[]='appoint_trans_id';
						$arrValuesPayment[]=$appointTransID;
						$arrFieldsPayment[]='transaction_id';
						$arrValuesPayment[]=$transid;
						$arrFieldsPayment[]='login_uer_id';
						$arrValuesPayment[]=$user_id;
						$payment_add= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
						
						// Add to Appointment Tracking
						$arrFieldsTrackDefault = array();
						$arrValuesTrackDefault = array();
							
						$arrFieldsTrackDefault[] = 'doc_id';
						$arrValuesTrackDefault[] = $doc_id;
						$arrFieldsTrackDefault[] = 'patient_id';
						$arrValuesTrackDefault[] = $patient_id;
						$arrFieldsTrackDefault[] = 'appoint_trans_id';
						$arrValuesTrackDefault[] = $appointTransID;
						$arrFieldsTrackDefault[] = 'message';
						$arrValuesTrackDefault[] = 'Payment is done';
						$arrFieldsTrackDefault[] = 'status';
						$arrValuesTrackDefault[] = '6';		// 1- Booked an appointment, 2 - Appointment Request has been sent, 3 - Accepted the request, 4 - Rejected the request, 5 - Payment is in process, 6 - Payment is done, 7 - Payment not done, 8 - Patient joined the call, 9 - Doctor joined the call
						$arrFieldsTrackDefault[] = 'created_date';
						$arrValuesTrackDefault[] = $curDate;
						$insertTrack = mysqlInsert('appointment_tracking',$arrFieldsTrackDefault,$arrValuesTrackDefault);
					}
					else {
						$payment_status = 0;		// Payment Pending
						$payment_type = $appointConsultChargeType;	// Payment Mode Type
					}
				}
			}
			else {
				$payment_status = 0;		// Payment Pending
				$payment_type = $appointConsultChargeType;	// Payment Mode Type
			}
			
		$patient_payment_PayTM_link = "https://medisensemd.com/premium/patient_profile_payment.php?d=" . md5($doc_id)."&p=" . md5($get_patInfo[0]['patient_id'])."&t=".$appointTransID;		
			
		
		
		$share_tests = array('status' => "true", 'payment_status' => $payment_status, 'payment_type' => $payment_type, 'patient_payment_PayTM_link' => $patient_payment_PayTM_link, 'patient_video_call_link' => $patient_VideoCallLink, 'message' => "Your order request has been sent successfully.", 'err_msg' => '');
		echo json_encode($share_tests);
}
else {
    $success_consults = array('status' => "false", 'err_msg' => "You have not permitted to access the account !!!");
	echo json_encode($success_consults);
}
?>
