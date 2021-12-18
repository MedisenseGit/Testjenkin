<?php
ob_start();
session_start();
error_reporting(0);

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


//My Pharma Orders Lists
if(API_KEY == $_POST['API_KEY']) {

		$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON

		$login_id = $_POST['user_id'];
		
		/* $orderlist_details = $objQuery->mysqlSelect("*","health_pharma_request","login_id ='".$login_id."'","id DESC","","","");
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
				
				$attachment_details = $objQuery->mysqlSelect("id as attachment_id, attachments as attachment_name","health_pharma_request_attachments","customer_id ='".$result_orderList['id']."'","id ASC","","","");
				$getReportList['attachments']= $attachment_details;
				
			array_push($order_details, $getReportList);
		} */
		
		$orderEMRlist_details = $objQuery->mysqlSelect("b.pr_id as id, b.login_id as login_id, a.pharma_customer_name as customer_name, a.pharma_customer_phone as customer_mobile, a.pharma_customer_email as customer_email, a.pharma_cust_address as customer_address, a.pharma_cust_city as customer_city, a.pharma_cust_state as customer_state, a.pharma_cust_country as pharma_cust_country, a.pharma_customer_pincode as pharma_customer_pincode, a.pharma_shipping_address as pharma_shipping_address, a.pharma_shipping_city as pharma_shipping_city, a.pharma_shipping_state as pharma_shipping_state, a.pharma_shipping_country as pharma_shipping_country, a.pharma_shipping_pincode as pharma_shipping_pincode, b.referred_date as created_date, b.order_status as order_status, b.customer_note as customer_note, b.referral_type as referral_type","pharma_customer as a inner join pharma_referrals as b on b.pharma_customer_id = a.pharma_customer_id","b.login_id ='".$login_id."'","b.pr_id DESC","","","");
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
				
				//$attachment_details = $objQuery->mysqlSelect("id as attachment_id, attachments as attachment_name","health_pharma_request_attachments","customer_id ='".$result_orderList['id']."'","id ASC","","","");
				$attachment_details1= array();
				$getReportList['attachments']= $attachment_details1;
				
			array_push($order_details, $getReportList);
		}
		
					
		$success_wallet = array('result' => "success", "pharmaOrder_details"=>$order_details, 'message' => "Your Pharma Orders !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
}
else {
		$success_wallet = array('result' => "failed", 'err_msg' => "You have not permitted to access the account !!!");
		echo json_encode($success_wallet);
}
?>
