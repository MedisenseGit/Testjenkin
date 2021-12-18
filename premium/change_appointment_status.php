<?php
ob_start();
error_reporting(0); 
session_start();

include('send_text_message.php');
include('short_url.php');

$admin_id = $_SESSION['user_id'];
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$cudate=date('Y-m-d');

//Update patient status
if(isset($_GET['transid']) && !empty($_GET['transid'])){
	if($_GET['statusId']==1){
		$visitStatus="Confirmed";
	}else if($_GET['statusId']==2){
		$visitStatus="Consulted";
	}else if($_GET['statusId']==3){
		$visitStatus="Cancelled";
	}else if($_GET['statusId']==4){
		$visitStatus="Pending";
	}else if($_GET['statusId']==5){
		$visitStatus="Missed";
	}else if($_GET['statusId']==6){
		$visitStatus="At reception";
	}else if($_GET['statusId']==7){
		$visitStatus="VC Ready";
	}else if($_GET['statusId']==8){
		$visitStatus="VC Confirmed";
	}
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'pay_status';
	$arrValues[]= $visitStatus;
	//Update Patient Status
	$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_GET['transid']."'");
	

	$arrFieldsToken[]= 'status';
	$arrValuesToken[]= $visitStatus;
	//Update Patient Status
	$patientRef=mysqlUpdate('appointment_token_system',$arrFieldsToken,$arrValuesToken,"appoint_trans_id='".$_GET['transid']."'");
	
	if($_GET['statusId']==3){
		
		$getPatDetails= mysqlSelect("a.patient_name as patient_name,a.Mobile_no as Mobile_no,b.ref_name as doc_name,b.ref_id as doc_id","appointment_transaction_detail as a left join referal as b on a.pref_doc=b.ref_id","a.appoint_trans_id='".$_GET['transid']."'","","","","");
		
		$longurl = "/SendRequestLink/?d=".md5($getPatDetails[0]['doc_id'])."&hid=".md5($_SESSION['login_hosp_id']);
		//Get Shorten Url
		$getUrl= get_shorturl($longurl);
		
		$txtMob = $getPatDetails[0]['Mobile_no'];					
		$recieptmsg= "Your appointment with ".$getPatDetails[0]['doc_name']." has been cancelled. Pls book again using this ".$getUrl." Thanks ".$_SESSION['login_hosp_name'];

		send_msg($txtMob,$recieptmsg);
		
	}
	
}
	
?>	

