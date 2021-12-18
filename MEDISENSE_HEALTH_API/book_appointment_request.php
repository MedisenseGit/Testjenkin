<?php ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

require_once("../classes/querymaker.class.php");
ob_start();
include('../premium/send_mail_function.php');
include("../premium/send_text_message.php");
include('../premium/short_url.php');
$ccmail = "medical@medisense.me";

if(HEALTH_API_KEY == $data ->api_key){

	$hospital_id 	= $data ->hospital_id;
	$doc_id			= $data ->doc_id;
	$user_id 		= $data ->user_id;
	$member_id 		= $data ->member_id;
	$txtName 		= $data ->patient_name;
	$txtAge 		= $data ->pat_age;
	$txtMail 		= addslashes($data ->emailid);
	$txtGen 		= $data ->pat_gen;
	$txtHeight		= $data ->height;
	$txtWeight		= $data ->weight;
	$txtBlood		= $data ->bloodGroup;
	$txtMob 		= addslashes($data ->Mobile_no);
	$txtAddress 	= addslashes($data ->Address);
	$txtLoc 		= addslashes($data ->City);
	$txtCountry 	= addslashes($data ->Country);
	$txtState 		= addslashes($data ->State);
	$txtConsultType = $data ->consult_type;
	$chkInDate 		= date('Y-m-d',strtotime($data ->visit_date));
	$chkInTime 		= $data ->visit_time;
	$Time_slot 		= $data ->time_slot;
	$txtAppointType = "2";
	
	
	$transid = time();
	$get_pro = mysqlSelect('*','referal',"ref_id='".$doc_id."'");

	if($txtConsultType == 2) 
	{
		$txtTeleCommunication = 1;
	}
	else 
	{
		$txtTeleCommunication = 0;
	}
	
	
	if($txtAppointType == 2)			// 2- Future Book Appointment Direct 
	{
		$chkInDate = $chkInDate;
		$chkInTime = $chkInTime;
		$Time_slot = $Time_slot;
		$status="Pending";
	}


	$txtbp           = addslashes($data ->bp);
	$txtthyroid      = addslashes($data ->thyroid);
	$txthypertension = addslashes($data ->hypertension);
	$txtasthama      = addslashes($data ->asthama);
	$txtcholestrol   = addslashes($data ->cholesterol);
	$txtepilepsy     = addslashes($data ->epilepsy);
	$txtdiabetic     = addslashes($data ->diabetic);
	$txtallergies    = addslashes($data ->allergies);

	$arrFields_patient[] = 'patient_name';
	$arrValues_patient[] = $txtName;

	$arrFields_patient[] = 'member_id';
	$arrValues_patient[] = $member_id; 

	$arrFields_patient[] = 'login_id';
	$arrValues_patient[] = $user_id;

	$arrFields_patient[] = 'patient_email';
	$arrValues_patient[] = $txtMail; 

	$arrFields_patient[] = 'patient_mobile';
	$arrValues_patient[] = $txtMob;

	$arrFields_patient[] = 'patient_dob';
	$arrValues_patient[] = $txtAge;

	$arrFields_patient[] = 'patient_gender';
	$arrValues_patient[] = $txtGen;
	
	$patientcreate	=	mysqlInsert('patients_appointment',$arrFields_patient,$arrValues_patient); // doc_my_patient to patients_appointment
	$patientid 		= 	$patientcreate;  //Get Patient Id
		
	$getPatInfo 	= 	mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");

	$arrFields1 = array();
	$arrValues1 = array();
		
	$arrFields1[] = 'patient_id';
	$arrValues1[] = $patientid;

	$arrFields1[] = 'service_type';
	$arrValues1[] = '1';  // 1 for medisense 

	$arrFields1[] = 'transaction_id';
	$arrValues1[] = $transid;

	$arrFields1[] = 'payment_id'; // empty
	$arrValues1[] = '1';

	$arrFields1[] = 'doc_id';
	$arrValues1[] = $doc_id;

	$arrFields1[] = 'hosp_id';
	$arrValues1[] = $hospital_id; 

	$arrFields1[] = 'contact_person';
	$arrValues1[] = $txtName;

	$arrFields1[] = 'patient_age';
	$arrValues1[] = $txtAge;

	$arrFields1[] = 'address';
	$arrValues1[] = $txtAddress;

	$arrFields1[] = 'city';
	$arrValues1[] = $txtLoc; 

	$arrFields1[] = 'state';
	$arrValues1[] = $txtState;

	$arrFields1[] = 'country';
	$arrValues1[] = $txtCountry;

	$arrFields1[] = 'height_cms';
	$arrValues1[] = $txtHeight;

	$arrFields1[] = 'weight';
	$arrValues1[] = $txtWeight;

	$arrFields1[] = 'hyper_cond';
	$arrValues1[] = $txthypertension;

	$arrFields1[] = 'diabetes_cond';
	$arrValues1[] = $txtdiabetic;

	$arrFields1[] = 'smoking'; // smoking
	$arrValues1[] = '';//$smoking;

	$arrFields1[] = 'alcoholic';
	$arrValues1[] = '';//$alcoholic;

	$arrFields1[] = 'blood_group';
	$arrValues1[] = $txtBlood;

	$arrFields1[] = 'drug_abuse';
	$arrValues1[] = 'drug_abuse'; // empty 

	$arrFields1[] = 'other_details';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'family_history';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'prev_intervention';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'neuro_issue';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'kidney_issue';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'pat_bp';
	$arrValues1[] = $txtbp; 

	$arrFields1[] = 'pat_thyroid';
	$arrValues1[] = $txtthyroid; 

	$arrFields1[] = 'pat_cholestrole';
	$arrValues1[] = $txtcholestrol; 

	$arrFields1[] = 'pat_epilepsy';
	$arrValues1[] = $txtepilepsy; 
	
	$arrFields1[] = 'pat_asthama';
	$arrValues1[] = $txtxtasthamatbp; 

	$arrFields1[] = 'allergies_any';
	$arrValues1[] = $txtallergies; 

	$arrFields1[] = 'subscriber_id'; // from subscriber table( empty )
	$arrValues1[] = '';//$subscriber_id; 

	$arrFields1[] = 'doc_video_link';
	$arrValues1[] = '';//$doc_video_link;

	$arrFields1[] = 'pat_video_link';
	$arrValues1[] = '';//$pat_video_link;

	$arrFields1[] = 'doc_agora_link';
	$arrValues1[] = '';

	$arrFields1[] = 'pat_agora_link';
	$arrValues1[] = "";

	$arrFields1[] = 'user_type';
	$arrValues1[] = '1';


	$arrFields1[] = 'Visiting_date';
	$arrValues1[] = date('Y-m-d',strtotime($chkInDate));

	$arrFields1[] = 'Visiting_time';
	$arrValues1[] = $chkInTime;

	$arrFields1[] = 'Time_slot';
	$arrValues1[] = $Time_slot;

	$arrFields1[]='amount';
	$arrValues1[]=$get_pro[0]['consult_charge'];

	$arrFields1[] = 'currency_type';  // QAR, INR, USD
	$arrValues1[] = $get_pro[0]['cons_charge_currency_type'];

	$arrFields1[] = 'pay_status';
	$arrValues1[] = $status;

	$arrFields1[] = 'visit_status';
	$arrValues1[] = 'new_visit';

	$arrFields1[] = 'patientEMR_consent';  // need to check 
	$arrValues1[] = "";

	$arrFields1[] = 'reference_id';  // need to check 
	$arrValues1[] = '';

	$arrFields1[] = 'referring_hosp';  // need to check 
	$arrValues1[] = '';

	$arrFields1[] = 'referring_doc';  // need to check 
	$arrValues1[] = '';

	$arrFields1[] = 'referal_note';  // need to check 
	$arrValues1[] = '';

	
	$createappointment	=	 mysqlInsert('patients_transactions',$arrFields1,$arrValues1);  //appointment_transaction_detail to " patients_transactions "
	$patient_trans_id	=	 $createappointment;

	$arrFields_token  = array();
	$arrValues1_token = array();
			
	$arrFields_token[]  = 'patient_trans_id';
	$arrValues1_token[] = $patient_trans_id;

	if($txtAppointType == 1)  // 1- Direct / Walk In Appointment
	{
		//Check Last Appointment Token No
		$getLastAppInfo = mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($chkInDate))."' and doc_id='".$doc_id."' and doc_type='1' and hosp_id='".$hospital_id."' and token_no!='555'" ,"token_no desc","","","");
		if(COUNT($getLastAppInfo)>0){
			$getTokenNo = $getLastAppInfo[0]['token_no']+1;
		}
		else
		{
			$getTokenNo = 1;
		}
			
		$arrFields_token[] = 'token_no';
		$arrValues1_token[] = $getTokenNo;
	}
	else if($txtAppointType == 2)
	{		// 2 - Future Book Appointment
		$arrFields_token[] = 'token_no';
		$arrValues1_token[] = "555"; //For Online Booking
	}

	

	$createappointment	=	mysqlInsert('patients_token_system',$arrFields_token,$arrValues1_token); // appointment_token_system to patients_token_system
	
	$arrhealthFields = array();
	$arrhealthValues = array();
		
	$arrhealthFields[] = 'member_id';
	$arrhealthValues[] = $member_id;
		
	$arrhealthFields[] = 'user_id';
	$arrhealthValues[] = $user_id;
		
	$arrhealthFields[] = 'bp';
	$arrhealthValues[] = $txtbp;
		
	$arrhealthFields[] = 'hypertension';
	$arrhealthValues[] = $txthypertension;
		
	$arrhealthFields[] = 'cholesterol';
	$arrhealthValues[] = $txtcholestrol;
			
	$arrhealthFields[] = 'diabetic';
	$arrhealthValues[] = $txtdiabetic;
		
	$arrhealthFields[] = 'thyroid';
	$arrhealthValues[] = $txtthyroid;
		
	$arrhealthFields[] = 'asthama';
	$arrhealthValues[] = $txtasthama;
		
	$arrhealthFields[] = 'epilepsy';
	$arrhealthValues[] = $txtepilepsy;
		
	$arrhealthFields[] = 'allergies_any';
	$arrhealthValues[] = $txtallergies;
	
	$usercraete=mysqlUpdate('user_family_general_health',$arrhealthFields,$arrhealthValues,"member_id='".$member_id."'");

	
	//Save for Appointment Payment Transaction
	if(!empty($get_pro[0]['consult_charge']))
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
		$arrValuesPayment[]=$get_pro[0]['consult_charge'];

		$arrFieldsPayment[]='user_id';
		$arrValuesPayment[]=$doc_id;

		$arrFieldsPayment[]='user_type';
		$arrValuesPayment[]="1";

		$arrFieldsPayment[]='hosp_id';
		$arrValuesPayment[]=$hospital_id;

		$arrFieldsPayment[]='payment_status';
		$arrValuesPayment[]="PENDING";

		$arrFieldsPayment[]='pay_method';
		$arrValuesPayment[]="Cash";
		
		$arrFieldsPayment[] = 'appoint_trans_id';
		$arrValuesPayment[] = $transid;			 
		$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
	}
	//Save for Appointment Payment Transaction ends here

	if(!empty($get_pro[0]['ref_mail']))
	{
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
		$url .= "&ccmail=" . urlencode($ccmail);	
		$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
		send_mail($url);	
	}

	//Send SMS to patient
	$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	//$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);

	$patient_profile_link = "/premium/Patient-Profile-Details?d=" . md5($doc_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;
	
	//Get Shorten Url
	$getUrl= get_shorturl($patient_profile_link);	
		
	//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
	$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$getUrl." Thanks";
	send_msg($txtMob,$msg);
	
	$link 		= HOST_MAIN_URL."premium/Patient-Profile-Details?d=" . md5($doc_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;			
	$toEmail	=$getPatInfo[0]['patient_email'];
	$mailSubject='Appointment Request';  
	$fromContent='Appointment';
	$email		='medical@medisense.me';
	$contentSection="Dear ".$getPatInfo[0]['patient_name'].",<br/> Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].".<br/> If you have any reports, upload here:".$link." <br/>Thanks";
			
	$url_page = 'send_medical_tourism_email.php';
	$url  = rawurlencode($url_page);
	$url .= "?contentSection=".urlencode($contentSection);
	$url .= "&toEmail=".urlencode($toEmail);
	$url .= "&mailSubject=".urlencode($mailSubject);
	$url .= "&replyEmail=".urlencode($email);
	$url .= "&fromContent=".urlencode($fromContent);
	send_mail($url);

    $success_appointment = array('result' => "success",'result_bookappoint' => "success","arrFields1"=>$arrFields1,"arrFields1"=>$arrValues1);
    echo json_encode($success_appointment);
	
	
}
else
{
	$success_appointment = array('result' => "failure",'result_bookappoint' => "failure",'err_msg' => "API key mismatch");
	echo json_encode($success_appointment);
}
