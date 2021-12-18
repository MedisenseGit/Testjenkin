<?php ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

require_once("../classes/querymaker.class.php");

ob_start();
include('send_mail_function.php');
include("send_text_message.php");
include('../premium/short_url.php');
$ccmail = "medical@medisense.me";


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Book Physical Appointments
if(!empty($user_id) && !empty($finalHash)) 
{	
	if($finalHash == $hashKey)
	{
		$member_id 		= 	$_POST['member_id'];
		$hospital_id 	= 	$_POST['hospital_id'];
		$doc_id 		= 	$_POST['doc_id'];
		$txtName 		= 	$_POST['patient_name'];
		$txtAge 		= 	$_POST['pat_age'];
		$txtMail 		= 	addslashes($_POST['emailid']);
		$txtGen 		= 	$_POST['pat_gen'];
		$txtMob 		= 	addslashes($_POST['Mobile_no']);
		$txtAddress 	= 	addslashes($_POST['Address']);
		$txtLoc 		= 	addslashes($_POST['City']);
		$txtCountry 	= 	addslashes($_POST['Country']);
		$txtState 		= 	addslashes($_POST['State']);
		$chkInDate 		= 	date('Y-m-d',strtotime($_POST['visit_date']));
		$chkInTime 		= 	$_POST['visit_time'];//6.00 PM 
		$txtAppointType = 	addslashes($_POST['appoint_type']);
		$transid 		= 	time();
		
		$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");
		
		if($txtAppointType == 1)			// 2- Future Book Appointment Direct 
		{
			$chkInDate = $chkInDate;
			$chkInTime = $chkInTime;
			$status="Pending";
		}
		else if($txtAppointType == 2)			// 2- Tele Consultation Appointment Direct 
		{
			$chkInDate = $chkInDate;
			$chkInTime = $chkInTime;
			$status="VC Confirmed";
		}
		else if($txtAppointType == 0)		// 1- Appointment / Walk In Appointment
		{
			$chkInDate = date('Y-m-d'); //Current Date
			$status="At reception";
			
			$day_val	=	date('D', strtotime($chkInDate));
			
			$getday_id 	= mysqlSelect("*","seven_days","da_name='".$day_val."'","","","","");
			
			$GetTimeSlot = mysqlSelect("b.time_id as time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hosp_id."' AND a.utc_slots='".$chkInTime."' AND b.day_id = '".$day_id."'","","","","");
			$slot_details = array();
			if(!empty($GetTimeSlot))
			{
				$chkInTime = $GetTimeSlot[0]["time_id"];
				$time_slot = $GetTimeSlot[0]["utc_slots"];
				
			}	
			
			/*$GetTiming= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hospital_id."' and a.da_name='".$day_val."'","b.time_id desc","","","");
			foreach($GetTiming as $TimeList) 
			{
				$chkDocTimeSlot = mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$doc_id."' and doc_type='1' and hosp_id = '".$hospital_id."'","","","","");
					
				//echo $chkDocTimeSlot[0]['num_patient_hour'];
				$countPrevAppBook = mysqlSelect("COUNT(id) as Appoint_Count","appointment_transaction_detail","pref_doc='".$doc_id."' and hosp_id = '".$hospital_id."' and Visiting_date = '".$chkInDate."' and Visiting_time = '".$TimeList["time_id"]."'","","","","");
				if($countPrevAppBook[0]['Appoint_Count']<$chkDocTimeSlot[0]['num_patient_hour'])
				{
					$chkInTime = $TimeList["time_id"];	
				}
			}*/
		}
		
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge; 

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
			$arrValues_patient[] = $doc_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
				
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $curDate;
			
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;
			
			$patientcreate		=	mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
			$patientid 			= 	$patientcreate;  //Get Patient Id
			
			$getPatInfo 		= 	mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
			
			$arrFields1 = array();
			$arrValues1 = array();
					
			$arrFields1[] = 'appoint_trans_id';
			$arrValues1[] = $transid;
			$arrFields1[] = 'patient_id';
			$arrValues1[] = $patientid;
			$arrFields1[] = 'pref_doc';
			$arrValues1[] = $doc_id;
			$arrFields1[] = 'member_id';
			$arrValues1[] = $member_id;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $hospital_id;
			/*$arrFields1[] = 'department';
			$arrValues1[] = $get_pro[0]['doc_spec']; */
			$arrFields1[] = 'Visiting_date';
			$arrValues1[] = date('Y-m-d',strtotime($chkInDate));
			$arrFields1[] = 'Visiting_time';
			$arrValues1[] = $chkInTime;  // time id 
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
			$arrFields1[] = 'src_type';
			$arrValues1[] = '1';			// 1 - Medisense Health Src
			$arrFields1[] = 'appointment_type';
			$arrValues1[] = $txtAppointType;
			
			$arrFields1[] = 'Time_slot'; // new column added
			$arrValues1[] = $time_slot;
					
			$createappointment=mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
				
			$getTime=mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
			
			$arrFieldsAppSlot = array();
			$arrValuesAppSlot = array();
					
			if($txtAppointType == 0)  // 1- Direct / Walk In Appointment
			{
				//Check Last Appointment Token No
				$getLastAppInfo = mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($chkInDate))."' and doc_id='".$doc_id."' and doc_type='1' and hosp_id='".$hospital_id."' and token_no!='555'" ,"token_no desc","","","");
				if(COUNT($getLastAppInfo)>0)
				{
					$getTokenNo = $getLastAppInfo[0]['token_no']+1;
				}
				else
				{
					$getTokenNo = 1;
				}
					
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = $getTokenNo;
			}
			else if($txtAppointType == 1){		// 2 - Future Book Appointment
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = "555"; //For Online Booking
			}
			
			else if($txtAppointType == 2){		// 2 - Tele Consultation Appointment
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
			$arrValuesAppSlot[] = $doc_id;
			$arrFieldsAppSlot[] = 'doc_type';
			$arrValuesAppSlot[] = "1";
			$arrFieldsAppSlot[] = 'hosp_id';
			$arrValuesAppSlot[] = $hospital_id;
			$arrFieldsAppSlot[] = 'status';
			$arrValuesAppSlot[] = $status;
			$arrFieldsAppSlot[] = 'app_date';
			$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
			$arrFieldsAppSlot[] = 'app_time';
			$arrValuesAppSlot[] = $time_slot;//$getTime[0]['Timing'];				
			$arrFieldsAppSlot[] = 'created_date';
			$arrValuesAppSlot[] = $curDate;
			$createappointment=mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
			
			//Patient Info EMAIL notification Sent to Doctor
			if(!empty($get_pro[0]['ref_mail']))
			{
				$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
			
				$url_page = 'pat_appointment_info.php';
				$url  = rawurlencode($url_page);
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
				$url .= "&ccmail=" . urlencode($ccmail);	
				$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
				send_mail($url);	
			}
			
			//Send SMS to patient
			$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			//$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
			//Get Shorten Url
			$getUrl= get_shorturl($longurl);	
				
			//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
			$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$getUrl." Thanks";
			send_msg($txtMob,$msg);
						
				

		$success_appointment = array('result' => "success",'result_bookappoint' => "success");
		echo json_encode($success_appointment);
	}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
		$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
		echo json_encode($failure);
}	
?>
