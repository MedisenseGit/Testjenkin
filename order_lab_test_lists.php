<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// Order Lab Tests
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
		$login_id = $user_id;
		$admin_id = $_POST['admin_id'];
		
		$labtest_list= array();
		
		$patient_id = $_POST['patient_id'];
		
		$diagno_referal = $objQuery->mysqlSelect("*","diagnostic_referrals","(dr_id)='".$patient_id."'","","","","");
		
		$patient_tab = $objQuery->mysqlSelect("*","diagnostic_customer","diagnostic_customer_id='".$diagno_referal[0]['diagnostic_customer_id']."'","","","",""); 
		
		//$diagno_referal[0]['referral_type']
		
		
		
		foreach($patient_tab as $list)
		{
			$labtest_details['customer_id']		= $diagno_referal[0]['diagnostic_customer_id'];
			$labtest_details['diagnostic_id']	= $diagno_referal[0]['diagnostic_id'];
			//$labtest_details['dr_id']			= $diagno_referal[0]['dr_id'];
			
			$labtest_details['customer_name']	= $list['diagnostic_customer_name'];
			$labtest_details['customer_phone']	= $list['diagnostic_customer_phone'];
			$labtest_details['cust_address']	= $list['diagnostic_cust_address'];
			$labtest_details['cust_city']		= $list['diagnostic_cust_city'];
			$labtest_details['cust_state']		= $list['diagnostic_cust_state'];
			$labtest_details['cust_country']	= $list['diagnostic_cust_country'];
			$labtest_details['cust_age']		= $list['diagnostic_cust_age'];		//$patient_tab[0]['diagnostic_cust_age'];
			$labtest_details['patient_id']		= $list['patient_id'];
			
			$labtest_details['order_status']	= $diagno_referal[0]['order_status'];
			$labtest_details['referral_type']	= $diagno_referal[0]['referral_type'];
			$labtest_details['customer_note']	= $diagno_referal[0]['customer_note'];
				
				
			array_push($labtest_list, $labtest_details);
		
		}
		
		
		
		$attachments = $objQuery->mysqlSelect("*","health_lab_test_request_attachments","customer_id = '".$diagno_referal[0]['dr_id']."'","","","","");
		
		foreach($attachments as $attachList)
		{ 
			$attachments['attachments_id']	= $attachList['id'];
			$attachments['attachments']		= $attachList['attachments'];
			
			array_push($labtest_list, $attachments);
			
		}
		
		$patient_episodes = $objQuery->mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","episode_id = '".$diagno_referal[0]['episode_id']."'","","","","");
		
		foreach($patient_episodes as $patient_episode_val)
		{
			$get_invest = $objQuery->mysqlSelect("*","patient_temp_investigation","patient_id = '". $diagno_referal[0]['patient_id']."' and episode_id='". $patient_episode_val['episode_id']."'","","","","");
			
			
			$patient_episode['episode_id']			= $patient_episode_val['episode_id'];						
			$patient_episode['formated_date_time']	= $patient_episode_val['formated_date_time'];
			
			while(list($key_invest, $value_invest) = each($get_invest))	
			{
				$patient_episode['main_test_id']		= $value_invest['main_test_id'];
				$patient_episode['pti_id']				= $value_invest['pti_id'];
				$patient_episode['test_name']			= $value_invest['test_name'];
				$patient_episode['normal_range']		= $value_invest['normal_range'];
				$patient_episode['test_actual_value']	= $value_invest['test_actual_value'];
				
			}
			array_push($labtest_list, $patient_episode);
		}
		
		$payment_amount = $objQuery->mysqlSelect("*","payment_diagno_pharma","referred_id='".$diagno_referal[0]['dr_id']."' and request_from='1'","","","","");

		foreach($payment_amount as $payment_amount_details)
		{
			$payment_amount_det['payment_amount']	= $payment_amount_details['payment_amount'];
			
			array_push($labtest_list, $payment_amount_det);
			
		}
		
		$success_opinion = array('result' => "success", 'labtest_details' =>$labtest_list, 'err_msg' => '');
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
