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
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//$data = json_decode(file_get_contents('php://input'), true);

// Order Lab Tests
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
		
		$labtest_list= array();
		
		$dr_id = $_POST['dr_id']; //$_POST['admin_id']; // 77
		
		//$allRecord = mysqlSelect("*","diagnostic_referrals","diagnostic_id='".$admin_id."'","dr_id desc","","","");
		
		$diagno_referal = mysqlSelect("*","diagnostic_referrals","(dr_id)='".$dr_id."'","","","","");
		
		$patient_tab = mysqlSelect("*","diagnostic_customer","diagnostic_customer_id='".$diagno_referal[0]['diagnostic_customer_id']."'","","","",""); 
		
		if($diagno_referal[0]['referral_type']=="2")
		{
			foreach($patient_tab as $list)
			{
				$labtest_details['customer_id']		= $diagno_referal[0]['diagnostic_customer_id'];
				$labtest_details['diagnostic_id']	= $diagno_referal[0]['diagnostic_id'];
				
				$labtest_details['patient_id']		= $list['patient_id'];
				$labtest_details['customer_name']	= $list['diagnostic_customer_name'];
				$labtest_details['customer_phone']	= $list['diagnostic_customer_phone'];
				$labtest_details['cust_address']	= $list['diagnostic_cust_address'];
				$labtest_details['cust_city']		= $list['diagnostic_cust_city'];
				$labtest_details['cust_state']		= $list['diagnostic_cust_state'];
				$labtest_details['cust_country']	= $list['diagnostic_cust_country'];
				$labtest_details['cust_age']		= $list['diagnostic_cust_age'];		//$patient_tab[0]['diagnostic_cust_age'];
				
				
				$labtest_details['order_status']	= $diagno_referal[0]['order_status'];
				$labtest_details['referral_type']	= $diagno_referal[0]['referral_type'];
				$labtest_details['customer_note']	= $diagno_referal[0]['customer_note'];
				$labtest_details['dr_id']			= $diagno_referal[0]['dr_id'];
				
			
		
				$attachments = mysqlSelect("*","health_lab_test_request_attachments","customer_id = '".$diagno_referal[0]['dr_id']."'","","","","");
				
				$labtest_details['attachments_id']		= $attachments[0]['id'];
				$labtest_details['attachments_file']	= $attachments[0]['attachments'];
				
				$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$diagno_referal[0]['dr_id']."' and request_from='1'","","","","");
			
				$labtest_details['payment_amount']	= $payment_amount[0]['payment_amount'];
			
			
				$investigation_results = mysqlSelect('test_name','order_labtest',"(dr_id)='".$diagno_referal[0]['dr_id']."' ","","","","");
				
				
				
				$labtest_details['investigation_result']		= $investigation_results;
				
					
				array_push($labtest_list, $labtest_details);
			
			}
				
				
			
		}	
		else
		{
				foreach($patient_tab as $list)
				{
					$labtest_details['customer_id']		= $diagno_referal[0]['diagnostic_customer_id'];
					$labtest_details['diagnostic_id']	= $diagno_referal[0]['diagnostic_id'];
					
					$labtest_details['patient_id']		= $list['patient_id'];
					$labtest_details['customer_name']	= $list['diagnostic_customer_name'];
					$labtest_details['customer_phone']	= $list['diagnostic_customer_phone'];
					$labtest_details['cust_address']	= $list['diagnostic_cust_address'];
					$labtest_details['cust_city']		= $list['diagnostic_cust_city'];
					$labtest_details['cust_state']		= $list['diagnostic_cust_state'];
					$labtest_details['cust_country']	= $list['diagnostic_cust_country'];
					$labtest_details['cust_age']		= $list['diagnostic_cust_age'];		//$patient_tab[0]['diagnostic_cust_age'];
					
					
					$labtest_details['order_status']	= $diagno_referal[0]['order_status'];
					$labtest_details['referral_type']	= $diagno_referal[0]['referral_type'];
					$labtest_details['customer_note']	= $diagno_referal[0]['customer_note'];
					$labtest_details['dr_id']			= $diagno_referal[0]['dr_id'];
					
					
				
					$attachments = mysqlSelect("*","health_lab_test_request_attachments","customer_id = '".$diagno_referal[0]['dr_id']."'","","","","");
					
					$labtest_details['attachments_id']		= $attachments[0]['id'];
					$labtest_details['attachments_file']	= $attachments[0]['attachments'];
					
					$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$diagno_referal[0]['dr_id']."' and request_from='1'","","","","");
					
					$labtest_details['payment_amount']	= $payment_amount[0]['payment_amount'];
				
					$get_Episodes 	=	mysqlSelect('a.episode_id as episode_id, a.emr_type as emr_type, a.admin_id as admin_id, a.patient_id as patient_id, a.episode_medical_complaint as episode_medical_complaint, a.examination as examination, a.treatment as treatment, a.next_followup_date as next_followup_date, a.date_time as date_time, b.ref_name as ref_name, b.ref_id as ref_id, a.prescription_note as prescription_note, a.diagnosis_details as diagnosis_details, a.treatment_details as treatment_details','doc_patient_episodes as a inner join referal as b on a.admin_id = b.ref_id',"(a.patient_id) ='".$list['patient_id']."'","a.episode_id DESC","","","");
						
				
					//$labtest_details['patient_id']		= $get_patient[0]['patient_id'];
					//$labtest_details['member_id']			= $member['member_id'];
					//$labtest_details['member_name']		= $get_member[0]['member_name'];
					$labtest_details['episode_id']			= $get_Episodes[0]['episode_id'];
					//echo $get_Episodes[0]['episode_id'];
					$investigation_results = mysqlSelect('test_name','patient_temp_investigation',"(episode_id)='".$get_Episodes[0]['episode_id']."' and patient_id='".$list['patient_id']."'","","","","");
					
					
					
					$labtest_details['investigation_result']		= $investigation_results;
					
						
					array_push($labtest_list, $labtest_details);
				
				}
		}		
				
		
		
		
		$success_opinion = array('result' => "success", 'labtest_details' =>$labtest_list,'err_msg' => '');
		echo json_encode($success_opinion);
		
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
