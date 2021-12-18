<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


	
	$TransId = $_POST['transid'];
	$PatId = $_POST['patid'];
	$DocId = $_POST['docid'];
	$TransStatus= $_POST['transtatus'];

	$paytmid = $_POST['paytmid'];
	$gst = $_POST['gst'];
	$opcost = $_POST['opcost'];
	$transamount = $_POST['transamount'];
	$transdate = $_POST['transdate'];
	$transmode = $_POST['transmode'];
	$service = $_POST['service'];
	$patname = $_POST['patname'];
	$patmob = $_POST['patmob'];
	$patmail = $_POST['patmail'];
	
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'transaction_status';
		$arrValues[] = $TransStatus;

	
		$update=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$PatId."' or transaction_id='".$TransId."'");

	$arrFields_custTrans = array();
	$arrValues_custTrans = array();
		
		$arrFields_custTrans[] = 'patient_id';
		$arrValues_custTrans[] = $PatId;
		$arrFields_custTrans[] = 'Payment_id';
		$arrValues_custTrans[] = $paytmid;
		$arrFields_custTrans[] = 'transaction_id';
		$arrValues_custTrans[] = $TransId;
		$arrFields_custTrans[] = 'patient_name';
		$arrValues_custTrans[] = $patname;
		$arrFields_custTrans[] = 'email_id';
		$arrValues_custTrans[] = $patmail;
		$arrFields_custTrans[] = 'mobile_no';
		$arrValues_custTrans[] = $patmob;
		$arrFields_custTrans[] = 'service_type';
		$arrValues_custTrans[] = $service;
		$arrFields_custTrans[] = 'ref_id';
		$arrValues_custTrans[] = $DocId;
		$arrFields_custTrans[] = 'opinion_cost';
		$arrValues_custTrans[] = $opcost;
		$arrFields_custTrans[] = 'GST';
		$arrValues_custTrans[] = $gst;
		$arrFields_custTrans[] = 'amount';
		$arrValues_custTrans[] = $transamount;
		$arrFields_custTrans[] = 'transaction_time';
		$arrValues_custTrans[] = $transdate;

	$usercraete=$objQuery->mysqlInsert('customer_transaction',$arrFields_custTrans,$arrValues_custTrans);
	
	$objQuery->mysqlDelete('payment_reminder',"patient_id='".$PatId."'and doc_id='".$DocId."'");
		


?>


