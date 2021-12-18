<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



//Update Teleconsult Requests

$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$doc_id = $doctor_id;
		$patient_id = $_POST['patient_id'];
		$appoint_transaction_id = $_POST['transaction_id'];
		
		$result_payments = mysqlSelect("*","payment_transaction","patient_id='".$patient_id."' AND user_id='".$doc_id."' AND appoint_trans_id='".$appoint_transaction_id."'","pay_trans_id DESC","","","");	
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