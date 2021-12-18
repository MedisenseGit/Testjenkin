<?php ob_start();
 error_reporting(0);
 session_start(); 


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


	
	$Appointtransid = addslashes($_POST['appointtransid']);
	$Prefdoc = addslashes($_POST['prefdoc']);
	$Userid = addslashes($_POST['userid']);
	$Dept = addslashes($_POST['dept']);
	$Hid = addslashes($_POST['hid']);
	$Visitdate = addslashes($_POST['visitdate']);
	$Visittime = addslashes($_POST['visittime']);
	$Patname = addslashes($_POST['patname']);
	$Mobno = addslashes($_POST['mobno']);
	$Email = addslashes($_POST['email']);
	$Amount = addslashes($_POST['amount']);
	$Paystate = addslashes($_POST['paystate']);
	$Visitstate = addslashes($_POST['visitstate']);
	$Timestamp = addslashes($_POST['timestamp']);
	
	
	$arrFields = array();
	$arrValues = array();
				
			
				$arrFields[] = 'appoint_trans_id';
				$arrValues[] = $Appointtransid;
				$arrFields[] = 'pref_doc';
				$arrValues[] = $Prefdoc;
				$arrFields[] = 'department';
				$arrValues[] = $Dept;
				$arrFields[] = 'Login_User_Id';
				$arrValues[] = $Userid;
				$arrFields[] = 'Hosp_patient_Id';
				$arrValues[] = $Hid;
				$arrFields[] = 'Visiting_date';
				$arrValues[] = $Visitdate;
				$arrFields[] = 'Visiting_time';
				$arrValues[] = $Visittime;
				$arrFields[] = 'patient_name';
				$arrValues[] = $Patname;
				$arrFields[] = 'Mobile_no';
				$arrValues[] = $Mobno;
				$arrFields[] = 'Email_address';
				$arrValues[] = $Email;
				$arrFields[] = 'Amount';
				$arrValues[] = $Amount;
				$arrFields[] = 'pay_status';
				$arrValues[] = $Paystate;
				$arrFields[] = 'visit_status';
				$arrValues[] = $Visitstate;
				$arrFields[] = 'Time_stamp';
				$arrValues[] = $Timestamp;
	
	$usercraete=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields,$arrValues);
	
?>