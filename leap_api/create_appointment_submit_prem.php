<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
include('send_text_message.php');
include('send_mail_function.php');
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$admin_id = $_POST['userid'];
	$user_name = $_POST['user_name'];
	
	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	$txtName = $_POST['se_pat_name'];
	$txtMob = $_POST['se_phone_no'];
	$curDate=date('Y-m-d H:i:s');
	
	$txtCountryName = $_POST['se_country_name'];
	$txtCountryCode = $_POST['se_country_code'];
	$txtCountryNameCode = $_POST['se_country_namecode'];
	
	$transid=time();
	
			$arrFields_patient = array();
			$arrValues_patient = array();
						
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;
			
			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;
			
			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $admin_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = date('Y-m-d H:i:s');
			
			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $txtCountryName;
			
			$getSourceId= $objQuery->mysqlSelect("doc_spec","referal","ref_id='".$admin_id."'","","","","");
			$docspec=$getSourceId[0]['doc_spec'];
			
			$patientcreate=$objQuery->mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
			$patientid = mysql_insert_id();  //Get Patient Id
			
			//Insert to new_hospvisitor_details table
			$arrFields = array();
			$arrValues = array();
			
				$arrFields[] = 'appoint_trans_id';
				$arrValues[] = $transid;
				$arrFields[] = 'pref_doc';
				$arrValues[] = $admin_id;
				$arrFields[] = 'department';
				$arrValues[] = $docspec;
				$arrFields[] = 'Visiting_date';
				$arrValues[] = $chkInDate;
				$arrFields[] = 'Visiting_time';
				$arrValues[] = $chkInTime;
				$arrFields[] = 'patient_name';
				$arrValues[] = $txtName;
				$arrFields[] = 'Mobile_no';
				$arrValues[] = $txtMob;
				$arrFields[] = 'pay_status';
				$arrValues[] = "Confirmed";
				$arrFields[] = 'visit_status';
				$arrValues[] = "new_visit";
				$arrFields[] = 'Time_stamp';
				$arrValues[] = $curDate;
			
				$createvisitor=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields,$arrValues);
				$newvisitorid= mysql_insert_id();
				$getPatInfo = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
				$getTime= $objQuery->mysqlSelect("*","timings","Timing_id='".$chkInTime."'","","","","");
		
				//Message notification to patient	
				if(!empty($txtMob)) {
					$get_pro = $objQuery->mysqlSelect('ref_id,contact_num,ref_mail,ref_name','referal',"ref_id='".$admin_id."'");
					$msg="Appointment Confirmed, TransactionID ". $transid . " | Patient Name: ". $txtName . " | Doctor: ".$user_name." | Date & Time: ".$chkInDate." | ".$getTime[0]['Timing']." Thanks";
					send_msg($txtMob,$msg);
				}
		
				
	$result = array("result" => "success");
	echo json_encode($result);  
 }
?>
