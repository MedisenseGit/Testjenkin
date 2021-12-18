<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata 	= $_POST;
$finalHash 	= checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);


//$data = json_decode(file_get_contents('php://input'), true);

// Order Lab Tests
/*if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{*/
		
		$pharma_deatils	= array();
		
		$dr_id = $_POST['dr_id']; //$_POST['admin_id']; // 77
		
		
		$pharma_referal = mysqlSelect("*","pharma_referrals","pr_id ='".$dr_id."'","","","","");
		
		$patient_tab = mysqlSelect("*","pharma_customer","pharma_customer_id='".$pharma_referal[0]['pharma_customer_id']."'","","","",""); 
			
		$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","episode_id = '".$pharma_referal[0]['episode_id']."'","","","","");
		
		
		if($pharma_referal[0]['referral_type']=="2")
		{
			foreach($patient_tab as $list)
			{
				$pharma_detail['customer_name']	= $list['pharma_customer_name'];
				$pharma_detail['customer_phone']= $list['pharma_customer_phone'];
				$pharma_detail['order_status']	= $list['order_status'];
				$pharma_detail['customer_email']= $list['pharma_customer_email'];
				$pharma_detail['cust_address']	= $list['pharma_cust_address'];
				$pharma_detail['cust_age']		= $list['pharma_cust_age'];
				$pharma_detail['cust_gender']	= $list['pharma_cust_gender'];
				$pharma_detail['cust_city']		= $list['pharma_shipping_city'];
				$pharma_detail['cust_state']	= $list['pharma_shipping_state'];
				$pharma_detail['cust_country']	= $list['pharma_shipping_country'];
				$pharma_detail['cust_pincode']	= $list['pharma_shipping_pincode'];	
				$pharma_detail['referral_type']	= $pharma_referal[0]['referral_type'];
				$pharma_detail['customer_note']	= $pharma_referal[0]['customer_note'];
				
				$pharma_detail['patient_id']	= $list['patient_id'];
				$pharma_detail['patient_name']	= $pharma_referal[0]['patient_name'];
				
				$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$pharma_referal[0]['pr_id']."' and request_from='2'","","","","");
				
				$pharma_detail['payment_amount']	= $payment_amount[0]['payment_amount'];
				
				$prescription_details = array();
					
				$prescription_result = mysqlSelect('*','order_medicine',"pr_id='".$pharma_referal[0]['pr_id']."'","","","","");
					foreach($prescription_result as $listPrescriptionList)
					{
						$getPrescList['episode_prescription_id']	= $listPrescriptionList['episode_prescription_id'];
						$getPrescList['episode_id']					= $listPrescriptionList['episode_id'];
						$getPrescList['prescription_trade_name']	= $listPrescriptionList['prescription_trade_name'];
						$getPrescList['prescription_generic_name']	= $listPrescriptionList['prescription_generic_name'];
						$getPrescList['prescription_frequency']		= $listPrescriptionList['prescription_frequency'];
						$getPrescList['duration']					= $listPrescriptionList['duration'];
						$getPrescList['med_duration_type']			= $listPrescriptionList['med_duration_type'];
						
						$getPrescList['doc_id']						= $listPrescriptionList['doc_id'];
						$getPrescList['pp_id']						= $listPrescriptionList['pp_id'];
						$getPrescList['patient_id']					= $get_Episodes[0]['patient_id'];
						
						$prescription_timings = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$listPrescriptionList['timing']."'","","","","");
						
						$getPrescList['timing']	=	$prescription_timings[0]['english'];
						
						array_push($prescription_details, $getPrescList);
					}
				
				$pharma_detail['prescription_details']	= $prescription_details;
				
				array_push($pharma_deatils, $pharma_detail);
			
					
			}		
		}

		else
		{
	
		
				foreach($patient_tab as $list)
				{
					$pharma_detail['customer_name']	= $list['pharma_customer_name'];
					$pharma_detail['customer_phone']= $list['pharma_customer_phone'];
					$pharma_detail['order_status']	= $list['order_status'];
					$pharma_detail['customer_email']= $list['pharma_customer_email'];
					$pharma_detail['cust_address']	= $list['pharma_cust_address'];
					$pharma_detail['cust_age']		= $list['pharma_cust_age'];
					$pharma_detail['cust_gender']	= $list['pharma_cust_gender'];
					$pharma_detail['cust_city']		= $list['pharma_shipping_city'];
					$pharma_detail['cust_state']	= $list['pharma_shipping_state'];
					$pharma_detail['cust_country']	= $list['pharma_shipping_country'];
					$pharma_detail['cust_pincode']	= $list['pharma_shipping_pincode'];	
					$pharma_detail['referral_type']	= $pharma_referal[0]['referral_type'];
					$pharma_detail['customer_note']	= $pharma_referal[0]['customer_note'];
					
					$pharma_detail['patient_id']	= $list['patient_id'];
					$pharma_detail['patient_name']	= $pharma_referal[0]['patient_name'];
					
					
					
					
					$attachments = mysqlSelect("*","health_pharma_request_attachments","customer_id = '".$pharma_referal[0]['pr_id']."'","","","","");
					
					$pharma_detail['attachments_id']	= $attachments[0]['id'];
					$pharma_detail['attachments_file']	= $attachments[0]['attachments'];
					
					$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$pharma_referal[0]['pr_id']."' and request_from='2'","","","","");
					
					$pharma_detail['payment_amount']	= $payment_amount[0]['payment_amount'];
					
					
					$prescription_details = array();
					
					$get_Episodes 	=	mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$list['patient_id']."'","a.episode_id DESC","","","");
					
					
					/*$getPrescList['episode_id']	= $get_Episodes[0]['episode_id'];
					$getPrescList['date_time']	= $get_Episodes[0]['date_time'];
					$getPrescList['ref_name']	= $get_Episodes[0]['ref_name'];*/
					
					
				
					$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"(episode_id)='".$get_Episodes[0]['episode_id']."'","","","","");
				
					
					
					foreach($prescription_result as $listPrescriptionList)
					{
						$getPrescList['episode_prescription_id']	= $listPrescriptionList['episode_prescription_id'];
						$getPrescList['episode_id']					= $listPrescriptionList['episode_id'];
						$getPrescList['prescription_trade_name']	= $listPrescriptionList['prescription_trade_name'];
						$getPrescList['prescription_generic_name']	= $listPrescriptionList['prescription_generic_name'];
						$getPrescList['prescription_frequency']		= $listPrescriptionList['prescription_frequency'];
						$getPrescList['duration']					= $listPrescriptionList['duration'];
						$getPrescList['med_duration_type']			= $listPrescriptionList['med_duration_type'];
						
						$getPrescList['doc_id']						= $listPrescriptionList['doc_id'];
						$getPrescList['pp_id']						= $listPrescriptionList['pp_id'];
						$getPrescList['patient_id']					= $get_Episodes[0]['patient_id'];
						
						$prescription_timings = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$listPrescriptionList['timing']."'","","","","");
						
						$getPrescList['timing']	=	$prescription_timings[0]['english'];
						
						array_push($prescription_details, $getPrescList);
					}
				
					$pharma_detail['prescription_details']	= $prescription_details;
				
					array_push($pharma_deatils, $pharma_detail);
				}
		}		
		
		
		
		$success_opinion = array('result' => "success", 'pharma_deatils' =>$pharma_deatils,'err_msg' => '');
		echo json_encode($success_opinion);
		
	/*}
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
}*/
?>
