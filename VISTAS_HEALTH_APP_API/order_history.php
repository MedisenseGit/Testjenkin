<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

// My Order History
if(!empty($user_id) && !empty($finalHash))
{	
	if($finalHash == $hashKey)
	{
		$order_type 	= 	$_POST['order_type'];
		$login_id 		=   $user_id;
		$order_type 	= 	explode(",",$order_type);
		$new_order_type	=	"";
		//var_dump( $order_type );
		$my_order	= 	array();
		$getmobile	=	mysqlSelect('sub_contact','login_user',"login_id='".$login_id."'","","","","");
		
		
		for($i=0;$i<=7;$i++)
		{
			if($order_type[$i]=="0" || $order_type[$i]=="1" || $order_type[$i]=="2" || $order_type[$i]=="3")
			{
				
			
				$appointResult = mysqlSelect("DISTINCT(a.id) as App_ID,a.appoint_trans_id as Trans_ID,md5(a.pref_doc) as Pref_Doc,a.hosp_id as hosp_id, e.ref_name as ref_name,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,md5(a.patient_id) as patient_id,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status, d.Timing as Visit_Timings, f.payment_status as payment_made_status, a.appointment_type as appointment_type, a.pref_doc as vid_ref_id,g.pat_video_link as video_link, a.patient_id as vid_pat_id ","appointment_transaction_detail as a inner join referal as e on e.ref_id=a.pref_doc inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join timings as d on d.Timing_id = a.Visiting_time left join payment_transaction as f on f.appoint_trans_id = a.appoint_trans_id inner join doc_my_patient as g on a.patient_id= g.patient_id","a.Mobile_no='".$getmobile[0]['sub_contact']."' and a.appointment_type IN ('".$order_type[$i]."')","a.Visiting_date desc","","","");
			
				foreach($appointResult as $appoint_order)
				{
					//$order_details['id'] = $second_opinion['member_id'];
					//$order_details['member_id'] = $appoint_order['member_id'];
					$order_details['member_name'] = $appoint_order['Patient_name'];
					$order_details['type']        = $appoint_order['appointment_type'];//appointment_type'6';
					$order_details['order_date']  = $appoint_order['TImestamp'];
					$order_details['to']          = $appoint_order['ref_name'];
					$order_details['schedule']    = $appoint_order['Time_stamp']; // slot time 
					$order_details['status']      = $appoint_order['Pay_Status'];
					array_push($my_order, $order_details);
				}
			}
		}
		
		//echo"<br>sub_contact ".$getmobile[0]['sub_contact']."<br>";
		//$getOpinionResult = mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$txtMobile."'","a.patient_id desc","","","");
		
		$getOpinionResult = mysqlSelect('a.patient_id as pat_id,(select ref_name from referal where ref_id=b.ref_id) as Doc_name, a.patient_name as pat_name, b.status2 as pat_status,b.timestamp as pat_status_time','patient_tab as a inner join patient_referal as b on a.patient_id = b.patient_id',"a.patient_mob='".$getmobile[0]['sub_contact']."'","a.patient_id desc","","","");
		
		//$user_data = mysqlSelect("*","login_user","sub_contact='".$txtMobile."'","","","","");	
		
		$user_data = mysqlSelect("*","login_user","sub_contact='".$getmobile[0]['sub_contact']."'","","","","");	
		for($i=0;$i<=7;$i++)
		{
			if($order_type[$i]=="4" )
			{
				$getPharmaRequests = mysqlSelect("c.pharma_name as pharma_name,a.referred_date as referred_date ,a.pr_id as pr_id,a.referral_type as type,b.pharma_customer_name as customer_name,b.pharma_customer_phone as customer_mobile,b.pharma_customer_email as customer_email,b.pharma_cust_state as customer_state,b.pharma_cust_city as customer_city,a.order_status as order_status","pharma_referrals as a inner join pharma_customer as b on a.pharma_customer_id=b.pharma_customer_id JOIN pharma AS c ON a.pharma_id = c.pharma_id ","a.login_id='".$user_data[0]['login_id']."'","a.pr_id desc","","","");
				foreach($getPharmaRequests as $appoint_order)
				{
					//$order_details['id'] = $second_opinion['member_id'];
					//$order_details['member_id'] = $appoint_order['member_id'];
					$order_details['member_name'] = $appoint_order['customer_name'];
					$order_details['type']        = "4";
					$order_details['order_date']  = $appoint_order['referred_date'];
					$order_details['to']          = $appoint_order['pharma_name'];
					$order_details['schedule']    = "";
					$order_details['status']      = $appoint_order['order_status'];
					array_push($my_order, $order_details);
				}
			}
		}
		for($i=0;$i<=7;$i++)
		{
			if($order_type[$i]=="5" )
			{
				$getLabRequests = mysqlSelect("c.diagnosis_name as diagnosis_name,a.referred_date as referred_date,a.dr_id as dr_id,a.referral_type as type,b.diagnostic_customer_name as customer_name,b.diagnostic_customer_phone as customer_mobile,b.diagnostic_customer_email as customer_email,b.diagnostic_cust_state as customer_state,b.diagnostic_cust_city as customer_city,a.order_status as order_status","diagnostic_referrals as a inner join diagnostic_customer as b on a.diagnostic_customer_id=b.diagnostic_customer_id JOIN Diagnostic_center AS c ON b.diagnostic_id = c.diagnostic_id","a.login_id='".$user_data[0]['login_id']."'","a.dr_id desc","","","");		
				foreach($getLabRequests as $lab_request)
				{
					//$order_details['id'] = $second_opinion['member_id'];
					//$order_details['member_id'] = $appoint_order['member_id'];
					$order_details['member_name'] = $lab_request['customer_name'];
					$order_details['type']        = "5";
					$order_details['order_date']  = $lab_request['referred_date'];
					$order_details['to']          = "";
					$order_details['schedule']    = "";
					$order_details['status']      = $lab_request['order_status'];
					array_push($my_order, $order_details);
				}
			}
		}
		
		for($i=0;$i<=7;$i++)
		{
			if($order_type[$i]=="6" )
			{
				$second_opinion_order=mysqlSelect("a.patient_name as patient_name,b.timestamp as timestamp,b.status2 as order_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id"," a.login_user_id='".$user_data[0]['login_id']."'  and service_type = 1 ","","","","");
				foreach($second_opinion_order as $second_opinion) 
				{
					//$order_details['id'] = $second_opinion['member_id'];
					//$order_details['member_id'] = $appoint_order['member_id'];
					$order_details['member_name'] = $second_opinion['patient_name'];
					$order_details['type']        = "6";
					$order_details['order_date']  = $second_opinion['timestamp'];
					$order_details['to']          = "";
					$order_details['schedule']    = "";
					$order_details['status']      = $second_opinion['order_status'];
					array_push($my_order, $order_details);
				}
			}
		}
		for($i=0;$i<=7;$i++)
		{
			if($order_type[$i]=="7" )
			{
				//echo"order_type :".$order_type[$i];
				$medical_travel_list = mysqlSelect("a.patient_name as patient_name,b.timestamp as timestamp,b.status2 as order_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id"," a.login_user_id='".$user_data[0]['login_id']."'  and service_type = 2 ","","","","");
			
				foreach($medical_travel_list as $medical_travel) 
				{
					//$order_details['id'] = $second_opinion['member_id'];
					//$order_details['member_id'] = $appoint_order['member_id'];
					$order_details['member_name'] = $medical_travel['patient_name'];
					$order_details['type']        = "7";
					$order_details['order_date']  = $medical_travel['timestamp'];
					$order_details['to']          = "";
					$order_details['schedule']    = "";
					$order_details['status']      = $medical_travel['order_status'];
					array_push($my_order, $order_details);
				}
			}	
		}
		
		//$success = array('status' => "true","my_order"=>$my_order);
		$success = array('status' => "true","appointment_details" => $appointResult,"opinion_details" => $getOpinionResult, "getPharmaRequests" => $getPharmaRequests, "getLabRequests" => $getLabRequests,"my_order"=>$my_order);
		echo json_encode($success);
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
