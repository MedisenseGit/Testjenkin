<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
include('../premium/short_url.php');
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');
//$ccmail="medical@medisense.me";
//$ccmail="salmabanu.h@gmail.com";

//Book Appointment - Both Walk IN and Direct Appointment
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	   
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$hospital_id = $_POST['hosp_id'];
	
	$txtName = $_POST['se_pat_name'];
	//$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state']);
	$txtAppointType = addslashes($_POST['appoint_type']);
	$patient_id = (int) $_POST['Pat_Id'];
	$transid=time();
	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	
	if($logintype == 1)			// Premium Login
	{
		$get_pro = $objQuery->mysqlSelect('*','referal',"ref_id='".$admin_id."'");
	
		if($txtAppointType == 2)			// 2- Future Book Appointment Direct 
		{
		$chkInDate = $chkInDate;
		$chkInTime = $chkInTime;
		$status="Pending";
		}
		else if($txtAppointType == 1)		// 1- Appointment / Walk In Appointment
		{
			$chkInDate = date('Y-m-d'); //Current Date
			$status="At reception";
		
			$day_val=date('D', strtotime($chkInDate));
			$GetTiming= $objQuery->mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","b.time_id desc","","","");
			foreach($GetTiming as $TimeList) {
			$chkDocTimeSlot = $objQuery->mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$admin_id."' and doc_type='1' and hosp_id = '".$hospital_id."'","","","","");
			
			//echo $chkDocTimeSlot[0]['num_patient_hour'];
			$countPrevAppBook = $objQuery->mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$admin_id."' and hosp_id = '".$hospital_id."' and Visiting_date = '".$chkInDate."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
				if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour'])
				{
				$chkInTime = $TimeList["time_id"];	
				}
			}
		}
		
		if($patient_id == 0) {
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			/* $arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge; */

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;
			
			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;

			$arrFields_patient[] = 'patient_loc';
			$arrValues_patient[] = $txtLoc;

			$arrFields_patient[] = 'pat_state';
			$arrValues_patient[] = $txtState;

			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $txtCountry;

			$arrFields_patient[] = 'patient_addrs';
			$arrValues_patient[] = $txtAddress;

			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $admin_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $curDate;
		
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;
		
		$patientcreate=$objQuery->mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = mysql_insert_id();  //Get Patient Id
		
		$getPatInfo = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		}
		else {
			$patientid = $patient_id;
			$getPatInfo = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		}
		
			$arrFields1 = array();
			$arrValues1 = array();
				
			$arrFields1[] = 'appoint_trans_id';
			$arrValues1[] = $transid;
			$arrFields1[] = 'patient_id';
			$arrValues1[] = $patientid;
			$arrFields1[] = 'pref_doc';
			$arrValues1[] = $admin_id;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $hospital_id;
			/*$arrFields1[] = 'department';
			$arrValues1[] = $get_pro[0]['doc_spec']; */
			$arrFields1[] = 'Visiting_date';
			$arrValues1[] = date('Y-m-d',strtotime($chkInDate));
			$arrFields1[] = 'Visiting_time';
			$arrValues1[] = $chkInTime;
			$arrFields1[] = 'patient_name';
			$arrValues1[] = $txtName;
			$arrFields1[] = 'Mobile_no';
			$arrValues1[] = $txtMob;
			$arrFields1[] = 'Email_address';
			$arrValues1[] = $txtMail;
				
			$arrFields1[] = 'pay_status';
			$arrValues1[] = $status;
			$arrFields1[] = 'visit_status';
			$arrValues1[] = "new_visit";
			$arrFields1[] = 'Time_stamp';
			$arrValues1[] = $curDate;
				
			$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
			
			$getTime=$objQuery->mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
			
			$arrFieldsAppSlot = array();
			$arrValuesAppSlot = array();
				
			if($txtAppointType == 1)  // 1- Direct / Walk In Appointment
			{
				//Check Last Appointment Token No
				$getLastAppInfo = $objQuery->mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($chkInDate))."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."' and token_no!='555'" ,"token_no desc","","","");
				if(COUNT($getLastAppInfo)>0){
					$getTokenNo = $getLastAppInfo[0]['token_no']+1;
				}
				else{
					$getTokenNo = 1;
				}
				
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = $getTokenNo;
			}
			else if($txtAppointType == 2){		// 2 - Future Book Appointment
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = "555"; //For Online Booking
			}
			$arrFieldsAppSlot[] = 'patient_id';
			$arrValuesAppSlot[] = $patientid;
			$arrFieldsAppSlot[] = 'appoint_trans_id';
			$arrValuesAppSlot[] = $transid;
			$arrFieldsAppSlot[] = 'patient_name';
			$arrValuesAppSlot[] = $txtName;
			$arrFieldsAppSlot[] = 'doc_id';
			$arrValuesAppSlot[] = $admin_id;
			$arrFieldsAppSlot[] = 'doc_type';
			$arrValuesAppSlot[] = "1";
			$arrFieldsAppSlot[] = 'hosp_id';
			$arrValuesAppSlot[] = $hospital_id;
			$arrFieldsAppSlot[] = 'status';
			$arrValuesAppSlot[] = $status;
			$arrFieldsAppSlot[] = 'app_date';
			$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
			$arrFieldsAppSlot[] = 'app_time';
			$arrValuesAppSlot[] = $getTime[0]['Timing'];				
			$arrFieldsAppSlot[] = 'created_date';
			$arrValuesAppSlot[] = $curDate;
			$createappointment=$objQuery->mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
			
			//Patient Info EMAIL notification Sent to Doctor
			if(!empty($get_pro[0]['ref_mail'])){
			$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
		
				$url_page = 'pat_appointment_info.php';
				$url = rawurlencode($url_page);
				$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
				$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
				$url .= "&patAddress=".urlencode($PatAddress);
				$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
				$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
				$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
				$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
				$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
				$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
				$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
				//$url .= "&ccmail=" . urlencode($ccmail);	
				$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
				send_mail($url);	
			}
			
			//Save for Appointment Payment Transaction
			if(!empty($_POST['consult_charge']))
			{
				$arrFieldsPayment=array();	
				$arrValuesPayment=array();
						
				$arrFieldsPayment[]='patient_name';
				$arrValuesPayment[]=$getPatInfo[0]['patient_name'];
				$arrFieldsPayment[]='patient_id';
				$arrValuesPayment[]=$getPatInfo[0]['patient_id'];
				$arrFieldsPayment[]='trans_date';
				$arrValuesPayment[]=$curDate;
				$arrFieldsPayment[]='narration';
				$arrValuesPayment[]="Consultation Charge";
				$arrFieldsPayment[]='amount';
				$arrValuesPayment[]=$_POST['consult_charge'];
				$arrFieldsPayment[]='user_id';
				$arrValuesPayment[]=$admin_id;
				$arrFieldsPayment[]='user_type';
				$arrValuesPayment[]="1";
				$arrFieldsPayment[]='payment_status';
				$arrValuesPayment[]="PAID";
				$arrFieldsPayment[]='pay_method';
				$arrValuesPayment[]="Cash";
				$insert_pay_transaction= $objQuery->mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
			}
			//Save for Appointment Payment Transaction ends here

			//Send SMS to patient
			$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			//$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			//Get Shorten Url
			$getUrl= get_shorturl($longurl);	
			
			//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
			$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$getUrl." Thanks";
			send_msg($txtMob,$msg);
			
			// Send Patient App link
			$checkAppLink = $objQuery->mysqlSelect("login_id","login_user","sub_contact='".$txtMob."'" ,"","","","");
			if(count($checkAppLink)==0){			
			$offlineMsg="Welcome to Medisense Healthcare App. Download the patient app Now! \n Download link - https://goo.gl/u8P5us \n Thanks Medisense";
			send_msg($txtMob,$offlineMsg);	
			}
		
		//$appointmentToday = $objQuery->mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$hospital_id."' and app_date='".$curdate."' and status!='Cancelled'","token_no ASC","","","");
		$appointmentToday = $objQuery->mysqlSelect("a.token_id as token_id, a.token_no as token_no, a.patient_id as patient_id, a.appoint_trans_id as appoint_trans_id, a.patient_name as patient_name, a.doc_id as doc_id, a.doc_type as doc_type, a.hosp_id as hosp_id, a.status as status, a.app_date as app_date, a.app_time as app_time, a.created_date as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id","a.doc_id='".$admin_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."' and a.app_date='".$curdate."' and a.status!='Cancelled'","a.token_no DESC","","","");
				
		$success = array('result' => "success","appointment_today_details" => $appointmentToday);
		echo json_encode($success);
	}
	else {
		$success = array('result' => "failure","appointment_today_details" => $appointmentToday);
		echo json_encode($success);
	}
}


?>