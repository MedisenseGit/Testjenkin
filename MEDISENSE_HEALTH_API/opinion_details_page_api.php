<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

include('../MEDISENSE_HEALTH_APP/send_mail_function.php');
include("../MEDISENSE_HEALTH_APP/send_text_message.php");

//Random Password Generator
function randomOtp() {
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if(HEALTH_API_KEY == $data ->api_key && isset($data ->patientid))
{
		
		$patientid = $data ->patientid;

	$patient_details = $objQuery->mysqlSelect('a.patient_id as pat_id, a.patient_name as pat_name, a.patient_age as pat_age, a.patient_loc as pat_loc,  a.patient_addrs as patient_addrs,a.pat_state as pat_state, a.pat_country as pat_country, a.weight as weight, a.hyper_cond as hyper_cond, a.diabetes_cond as diabetes_cond, a.patient_gen as patient_gen, a.patient_desc as patient_desc, a.pat_query as pat_query, a.patient_complaint as patient_complaint, a.patient_mob as patient_mob, a.patient_email as patient_email, a.external_hubid as external_hubid, a.external_orderid as external_orderid, a.transaction_id as transaction_id, b.status2 as pat_status, d.source_name as pat_refered_by,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id inner join source_list as d on d.source_id = a.patient_src',"md5(a.patient_id)='".$patientid."'","","","","");
	
	$opinion_details = $objQuery->mysqlSelect('*','patient_referal',"md5(patient_id)='".$patientid."'","","","","");
	$payment_reminder = $objQuery->mysqlSelect('*','payment_reminder',"md5(patient_id)='".$patientid."'","","","","");
	
	$docResponse = array();
	foreach($opinion_details as $opinion_details_list){
		$refDetails = $objQuery->mysqlSelect('ref_id,ref_name,ref_address,doc_state,doc_country,doc_photo,anonymous_status','referal',"ref_id='".$opinion_details_list['ref_id']."'","","","","");
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$opinion_details_list['ref_id']."'","","","","");
		$chatDetails = $objQuery->mysqlSelect('a.chat_note as chat_note, a.TImestamp as TImestamp','chat_notification as a inner join referal as b on a.ref_id=b.ref_id',"md5(a.patient_id)='".$patientid."' and a.ref_id='".$opinion_details_list['ref_id']."'","a.chat_id desc","","","");
		$chkEvntStatus = $objQuery->mysqlSelect("event_id","patient_email_event","eventtype='3' and md5(patient_id)='".$patientid."' and random_id='".$refDetails[0]['ref_id']."'","","","","");
		
		$docResp['doc_id'] = $refDetails[0]['ref_id'];
		$docResp['doc_name'] = $refDetails[0]['ref_name'];
		$docResp['doc_city'] = $refDetails[0]['ref_address'];
		$docResp['doc_state'] = $refDetails[0]['doc_state'];
		$docResp['doc_country'] = $refDetails[0]['doc_country'];
		$docResp['doc_photo'] = $refDetails[0]['doc_photo'];
		$docResp['cur_doc_status'] = $opinion_details_list['status2'];
		$docResp['doc_specializations']= $doc_specialization;
		$docResp['docLikeStatus'] =	COUNT($chkEvntStatus);
		$docResp['doc_note'] = $chatDetails;
		array_push($docResponse,$docResp);
	}
	$payReminder = array();
	foreach($payment_reminder as $payment_reminder_list){
		$refDetails = $objQuery->mysqlSelect('ref_id,ref_name,ref_address,doc_state,doc_country,doc_photo,anonymous_status,on_op_cost','referal',"ref_id='".$payment_reminder_list['doc_id']."'","","","","");
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$payment_reminder_list['doc_id']."'","","","","");
		$doc_payment = $objQuery->mysqlSelect('Payment_id','customer_transaction',"patient_id='".$patient_details[0]['pat_id']."' and ref_id='".$payment_reminder_list['doc_id']."'","","","","");
		
		
		$service="Second Opinion";
		$opcost=$refDetails[0]['on_op_cost'].".00";
		$paylink=HOST_HEALTH_URL."turn-to-pay.php?patid=".$patient_details[0]['pat_id']."&patname=".$patient_details[0]['pat_name']."&mobile=".$patient_details[0]['patient_mob']."&email=".$patient_details[0]['patient_email']."&amount=".$opcost."&service=".$service."&docname=".$refDetails[0]['ref_name']."&docid=".$refDetails[0]['ref_id']."&authToken=".$chkMember[0]['medAuthToken']."&healthHubId=".$patient_details[0]['external_hubid']."&healthHubOrderId=".$patient_details[0]['external_orderid']."&transactionID=".$patient_details[0]['transaction_id'];
							
		
		
		$docPayRem['payment_status'] = $payment_reminder_list['payment_status'];
		$docPayRem['payment_request_date'] = $payment_reminder_list['TImestamp'];
		$docPayRem['doc_id'] = $refDetails[0]['ref_id'];
		$docPayRem['doc_name'] = $refDetails[0]['ref_name'];
		$docPayRem['doc_city'] = $refDetails[0]['ref_address'];
		$docPayRem['doc_state'] = $refDetails[0]['doc_state'];
		$docPayRem['doc_op_cost'] = $refDetails[0]['on_op_cost'];
		$docPayRem['doc_pay_status'] = $doc_payment[0]['Payment_id'];
		$docPayRem['doc_pay_link'] = $paylink;
		$docPayRem['doc_country'] = $refDetails[0]['doc_country'];
		$docPayRem['doc_photo'] = $refDetails[0]['doc_photo'];
		$docPayRem['doc_specializations']= $doc_specialization;
		
		array_push($payReminder,$docPayRem);
	}
	$success = array('status' => "true","patient_details" => $patient_details,"pat_status" => $opinion_details[0]['bucket_status'],"Doc_response" => $docResponse,"payReminder" => $payReminder);
	echo json_encode($success);
}

else if(HEALTH_API_KEY == $data ->api_key && $data->eventtype==3)
{	

	if($data->liketype=="ADD")
	{
			$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$data->patid."'","","","","");
			$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$data->randid."'");
			if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
				$docnum=$getDocDet[0]['contact_num'];
				
				$docmail .= $getDocDet[0]['ref_mail'] . ', ';
				$docmail .= $getDocDet[0]['ref_mail1'] . ', ';
				$docmail .= $getDocDet[0]['ref_mail2'];
				
			}else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
				$docnum=$getDocDet[0]['hosp_contact'];
				
				$docmail .= $getDocDet[0]['hosp_email'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email1'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email2'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email3'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email4'];
				
			}
			else if($getDocDet[0]['communication_status']==3){ //If communication_status=3 then Notification will Send to both  Hospital POint of contact as well as Doctor personal No. 
				$docnum=$getDocDet[0]['contact_num'];
				$hospnum=$getDocDet[0]['hosp_contact'];
				
				$docmail .= $getDocDet[0]['ref_mail'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email1'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email2'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email3'] . ', ';
				$docmail .= $getDocDet[0]['hosp_email4'];
			}
			
			$arrFields = array();
			$arrValues = array();
			
			$arrFields[]= 'eventtype';
			$arrValues[]= $data->eventtype;
			$arrFields[]= 'patient_id';
			$arrValues[]= $data->patid;
			$arrFields[]= 'random_id';
			$arrValues[]= $data->randid;
			$arrFields[]= 'TImestamp';
			$arrValues[]= $Cur_Date;
			
			$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields,$arrValues);
			
			$mednote="Patient appreciated to ".$getDocDet[0]['ref_name']."-".$Cur_Date; //MEDISENSE NOTE
			$arrFields2 = array();
			$arrValues2 = array();
			$arrFields2[] = 'patient_id';
			$arrValues2[] = $data->patid;
			$arrFields2[] = 'ref_id';
			$arrValues2[] = $data->randid;
			$arrFields2[] = 'chat_note';
			$arrValues2[] = $mednote;
			$arrFields2[] = 'user_id';
			$arrValues2[] = '10';
			$arrFields2[] = 'TImestamp';
			$arrValues2[] = $Cur_Date;
						
			$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
			
				
						//EMAIL notification to Doctor
							if(!empty($docmail)){
				
							$url_page = 'Doc_Thanks_mail.php';
							
							$url = rawurlencode($url_page);
							$url .= "?docname=".urlencode($getDocDet[0]['ref_name']);
							$url .= "&patname=" . urlencode($chkPatDet[0]['patient_name']);
							$url .= "&patid=" . urlencode($chkPatDet[0]['patient_id']);
							$url .= "&docmail=" . urlencode($docmail);
							$url .= "&ccmail=" . urlencode($ccmail);		
							send_mail($url);
							
							}	


				
							//SMS notification to Refering Doctors
							$msg = "Dear Sir, ".$chkPatDet[0]['patient_name']." ( ".$chkPatDet[0]['patient_mob']." )has expressed his gratitude for the help offered by you. Pls check your mail. Many Thanks";
							if(!empty($docnum)){
							send_msg($docnum,$msg);
							}
							if(!empty($hospnum)){
							send_msg($hospnum,$msg);
							}
			//header('Location:Respone-note?response=3');	
			$success = array('status' => "true");
			echo json_encode($success);
	
	}
	
	if($data->liketype=="DISPLAY")
	{
		$chkEvntStatus = $objQuery->mysqlSelect("*","patient_email_event","eventtype='".$data->eventtype."' and patient_id='".$data->patid."' and random_id='".$data->randid."'","","","","");
		if($chkEvntStatus==true)
		{
			$success = array('status' => "true");
			echo json_encode($success);
			
		}
		
	}
	
}
else 
{
			
	$success["status"] = "false";
	echo json_encode($success);
}

?>


