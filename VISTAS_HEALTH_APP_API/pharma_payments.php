<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Pharma Payments
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$user_id = $user_id;
		$order_id = $_POST['order_id'];
		$refer_type = $_POST['refer_type'];
		$transid = time();
		
		$getWalletBalance = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
		$get_pro = mysqlSelect("*","payment_diagno_pharma","referred_id ='".$order_id."'","id DESC","","","1");
		$get_patientInfo = mysqlSelect("b.pharma_customer_name as pharma_customer_name, a.patient_id as patient_id","pharma_referrals as a inner join pharma_customer as b on b.pharma_customer_id = a.pharma_customer_id","a.pr_id ='".$order_id."'","a.pr_id DESC","","","1");
		$deduct_amount = $get_pro[0]['payment_amount'];
		$deduct_amount_type = $get_pro[0]['currency_code'];
		$paytm_url = "https://medisensemd.com/Pharma/Patient-Profile-PaymentPharma/Patient-Profile-Payment?r=".md5($order_id)."&t=".$refer_type;
				
				
		if($getWalletBalance[0]['amount_currency_type'] == $deduct_amount_type) {
			if(empty($getWalletBalance)){
				$payment_status = 0;		// Payment Pending
				$payment_type = $deduct_amount_type;	// Payment Mode Type
			}
			else {
				if($getWalletBalance[0]['Total_Amount'] >= $get_pro[0]['payment_amount']) {
					$payment_status = 1;					// Payment Done
					$current_balance = $getWalletBalance[0]['Total_Amount'];
					$deduct_amount = $get_pro[0]['payment_amount'];
					$remaining_balance = $current_balance - $deduct_amount;
					$currency_type = $get_pro[0]['currency_code'];
					
					$arrFieldsWallet=array();	
					$arrValuesWallet=array();
					
					$arrFieldsWallet[]='tansaction_id';
					$arrValuesWallet[]=$transid;
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
					
					// Update payment_diag_pha_transaction Table
					$arrFields_arr1 = array();
					$arrValues_arr1 = array();	
					$arrFields_arr1[] = 'patient_name';
					$arrValues_arr1[] = $get_patientInfo[0]['pharma_customer_name'];
					
					$arrFields_arr1[] = 'patient_id';
					$arrValues_arr1[] = $get_patientInfo[0]['patient_id'];
					$arrFields_arr1[] = 'trans_date';
					$arrValues_arr1[] = $curDate;
					$arrFields_arr1[] = 'amount';
					$arrValues_arr1[] = $deduct_amount;
					$arrFields_arr1[] = 'payment_status';
					$arrValues_arr1[] = "PAID";
					$arrFields_arr1[] = 'pay_method';
					$arrValues_arr1[] = 'Health Wallet';
					$arrFields_arr1[] = 'type';
					$arrValues_arr1[] = $refer_type;
					$arrFields_arr1[] = 'diagno_pharma_id';
					$arrValues_arr1[] = $get_pro[0]['diagno_pharma_id']; 
					
					$arrFields_arr1[] = 'request_from';
					$arrValues_arr1[] = '2';					// 1-Request from Diagnostic, 2- from Pharmacy
					$arrFields_arr1[] = 'referred_id';
					$arrValues_arr1[] = $order_id;
					$arrFields_arr1[] = 'transaction_id';
					$arrValues_arr1[] = $transid;
					
			
					$check_pay_trans = mysqlSelect("*","payment_diag_pha_transaction","referred_id='".$order_id."' and type='".$refer_type."' and request_from='2'","","","","");
						if(COUNT($check_pay_trans)>0){	
						   $usercreate1=mysqlUpdate('payment_diag_pha_transaction',$arrFields_arr1,$arrValues_arr1,"referred_id='".$order_id."' and type='".$refer_type."' and request_from='2'");
							
						}
						else{
							$usercreate1=mysqlInsert('payment_diag_pha_transaction',$arrFields_arr1,$arrValues_arr1);
						}

					$arrFiedPay1=array();
					$arrValuePay1=array();
					
					$arrFiedPay1[]='order_status';
					$arrValuePay1[]='3';
					
					$update_pay=mysqlUpdate('pharma_referrals',$arrFiedPay1,$arrValuePay1,"pr_id='".$order_id."'");
			
				}
				else {
					$payment_status = 0;		// Payment Pending
					$payment_type = $deduct_amount_type;	// Payment Mode Type
				}
			}
		}
		else {
			$payment_status = 0;		// Payment Pending
			$payment_type = $deduct_amount_type;	// Payment Mode Type
		}
		
		$success_wallet = array('result' => "success", "payment_status"=>$payment_status, "payment_type"=>$payment_type, "paytm_url"=>$paytm_url, 'message' => "Your Pharma Orders !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
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
