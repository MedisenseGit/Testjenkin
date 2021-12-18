<?php ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//Update Teleconsult Requests
if(API_KEY == $_POST['API_KEY']) {

	$doc_id = $_POST['doctor_id'];
	$patient_id = $_POST['patient_id'];
	$appoint_transaction_id = $_POST['transaction_id'];
	
	$result_payments = $objQuery->mysqlSelect("*","payment_transaction","patient_id='".$patient_id."' AND user_id='".$doc_id."' AND appoint_trans_id='".$appoint_transaction_id."'","pay_trans_id DESC","","","");	
	if(!empty($result_payments)) {
		$pay_status = $result_payments[0]['payment_status'];
		if($pay_status == 'PAID')
		{
			$payment_status = 1;
		}
		else {
			$payment_status = 0;
		}
	}
	else {
		$payment_status = 0;
	}
	
	$success = array('status' => "true","payment_status" => $payment_status);     
	echo json_encode($success);
	

}	