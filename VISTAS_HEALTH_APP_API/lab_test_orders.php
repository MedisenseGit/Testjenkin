<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "GET", "", $user_id, $device_id);

//My Pharma Orders Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		
		$login_id = $user_id;//2837;//$user_id;
		
		$orderlist_details = mysqlSelect("*","health_lab_test_request","login_id ='".$login_id."'","id DESC","","","");
		$order_details= array();
		foreach($orderlist_details as $result_orderList) {
				$getReportList['order_id']=$result_orderList['id'];
				$getReportList['login_id']=$result_orderList['login_id'];
				$getReportList['customer_name']=$result_orderList['customer_name'];
				$getReportList['customer_mobile']=$result_orderList['customer_mobile'];
				$getReportList['customer_email']=$result_orderList['customer_email'];
				$getReportList['customer_address']=$result_orderList['customer_address'];
				$getReportList['customer_city']=$result_orderList['customer_city'];
				$getReportList['customer_state']=$result_orderList['customer_state'];
				$getReportList['customer_country']=$result_orderList['customer_country'];
				$getReportList['created_date']=$result_orderList['created_date'];
				$getReportList['order_status']=$result_orderList['order_status'];
				
				$attachment_details = mysqlSelect("id as attachment_id, attachments as attachment_name","health_lab_test_request_attachments","customer_id ='".$result_orderList['id']."'","id ASC","","","");
				$getReportList['attachments']= $attachment_details;
				
			array_push($order_details, $getReportList);
		}
		
		$orderEMRlist_details = mysqlSelect("b.dr_id as id, b.login_id as login_id, a.diagnostic_customer_name as customer_name, a.diagnostic_customer_phone as customer_mobile, a.diagnostic_customer_email as customer_email, a.diagnostic_cust_address as customer_address, a.diagnostic_cust_city as customer_city, a.diagnostic_cust_state as customer_state, a.diagnostic_cust_country as pharma_cust_country, b.referred_date as created_date, b.order_status as order_status","diagnostic_customer as a inner join diagnostic_referrals as b on b.diagnostic_customer_id = a.diagnostic_customer_id","b.login_id ='".$login_id."'","b.dr_id DESC","","","");
		foreach($orderEMRlist_details as $result_orderEMRList) {
				$getReportList['order_id']=$result_orderEMRList['id'];
				$getReportList['login_id']=$result_orderEMRList['login_id'];
				$getReportList['customer_name']=$result_orderEMRList['customer_name'];
				$getReportList['customer_mobile']=$result_orderEMRList['customer_mobile'];
				$getReportList['customer_email']=$result_orderEMRList['customer_email'];
				$getReportList['customer_address']=$result_orderEMRList['customer_address'];
				$getReportList['customer_city']=$result_orderEMRList['customer_city'];
				$getReportList['customer_state']=$result_orderEMRList['customer_state'];
				$getReportList['customer_country']=$result_orderEMRList['customer_country'];
				$getReportList['created_date']=$result_orderEMRList['created_date'];
				$getReportList['order_status']=$result_orderEMRList['order_status'];
				
				//$attachment_details = mysqlSelect("id as attachment_id, attachments as attachment_name","health_pharma_request_attachments","customer_id ='".$result_orderList['id']."'","id ASC","","","");
				$attachment_details1= array();
				$getReportList['attachments']= $attachment_details1;
				
			array_push($order_details, $getReportList);
		}
		
					
		$success_wallet = array('result' => "success", "labTestOrder_details"=>$order_details, 'message' => "Your Pharma Orders !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
	}
	else {
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
