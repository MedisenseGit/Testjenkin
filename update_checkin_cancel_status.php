<?php ob_start();
 error_reporting(0);
 session_start(); 


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


	$chkinTransId = addslashes($_POST['appointtransid']);
	$Paystatus = addslashes($_POST['paystatus']);
	$Paymentid = addslashes($_POST['paymentid']);
	
	$arrFields = array();
	$arrValues = array();
	if($Paystatus=="Confirmed"){
		$Paystatus1=$Paystatus;
	}else if($Paystatus=="Canceled"){
		$Paystatus1=$Paystatus;
	}	
		$arrFields[] = 'pay_status';
		$arrValues[] = $Paystatus1;
		$arrFields[] = 'Payment_id';
		$arrValues[] = $Paymentid;
		$updatePay=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$chkinTransId."'");
	
	
 

?>