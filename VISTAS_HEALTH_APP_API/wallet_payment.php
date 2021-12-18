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

//Wallet Payment Transactions
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$user_id = $user_id;
		$transaction_id = $_POST['transactionID'];
		$transaction_status = $_POST['transactionStatus'];
		$transaction_amount = $_POST['transactionAmount'];
		$transaction_currency = $_POST['transactionCurrency'];
		$transid = time();
		
		$arrFields1 = array();
		$arrValues1 = array();
				
		$arrFields1[] = 'login_id';
		$arrValues1[] = $user_id;
		$arrFields1[] = 'amount';
		$arrValues1[] = $transaction_amount;
		$arrFields1[] = 'currency_type';
		$arrValues1[] = $transaction_currency;
		$arrFields1[] = 'transaction_date';
		$arrValues1[] = $curDate;
		$arrFields1[] = 'narration';
		$arrValues1[] = 'Health Wallet';
		$arrFields1[] = 'payment_status';
		$arrValues1[] = 'SUCCESS';
		$arrFields1[] = 'pay_method';
		$arrValues1[] = 'PAYPAL';
		$arrFields1[] = 'Payment_id';
		$arrValues1[] = $transaction_id;
		$arrFields1[] = 'Payment_id';
		$arrValues1[] = $transaction_id;
		$arrFields1[] = 'transaction_id';
		$arrValues1[] = $transid;
		$arrFields1[] = 'created_date';
		$arrValues1[] = $curDate;
		$arrFields1[] = 'payment_paypal_status';
		$arrValues1[] = $transaction_status;
		
		$transactionAdd=mysqlInsert('health_app_wallet_transactions',$arrFields1,$arrValues1);
		$wallet_trans_id = $transactionAdd;  //Get Payment Id
		
		$getWallet = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
		$totalBalance = $getWallet[0]['Total_Amount'];
		$currentBalance = $totalBalance + $transaction_amount;
		
		
		$arrFields_wallet = array();
		$arrValues_wallet = array();
				
		$arrFields_wallet[] = 'tansaction_id';
		$arrValues_wallet[] = $transid;
		$arrFields_wallet[] = 'login_id';
		$arrValues_wallet[] = $user_id;
		$arrFields_wallet[] = 'amount_added';
		$arrValues_wallet[] = $transaction_amount;
		$arrFields_wallet[] = 'Total_Amount';
		$arrValues_wallet[] = $currentBalance;
		$arrFields_wallet[] = 'amount_currency_type';
		$arrValues_wallet[] = $transaction_currency;
		$arrFields_wallet[] = 'created_date';
		$arrValues_wallet[] = $curDate;
		$transactionAdd=mysqlInsert('health_app_wallet',$arrFields_wallet,$arrValues_wallet);
		
		$result_wallet = mysqlSelect("*","health_app_wallet","login_id ='".$user_id."'","id DESC","","","1");
		
					
		$success_wallet = array('result' => "success", 'result_wallet' => $result_wallet, 'message' => "Amount added to your account successfully !!!", 'err_msg' => '');
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
