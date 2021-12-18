<?php
ob_start();
error_reporting(0); 
session_start();

include('../classes/config.php');
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i a');
$con = getdb();
 if(isset($_POST["Export_Payment"])){
		 
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=payTransactions'.$curDate.'.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('Transaction Date', 'patient name', 'Narration','Amount(Rs.)', 'Payment Status', 'Payment Mode'));  
     // $query = "SELECT TImestamp as Reg_Date,ref_name,RBM_name,(select COUNT(id) from appointment_transaction_detail where pref_doc=ref_id) as app_count,(select COUNT(episode_id) from doc_patient_episodes where admin_id=ref_id) as visit_count,(select COUNT(outgoing_referrals_id) from doctor_outgoing_referrals where admin_id=ref_id) as refer_count,(select system_ip from practice_login_tracker where doc_id=ref_id) as system_ip,(select timestamp from practice_login_tracker where doc_id=ref_id) as last_login from referal WHERE sponsor_id='2' ORDER BY ref_name ASC";  
        
	 if(isset($_SESSION['fromDate']) && isset($_SESSION['toDate'])){
	  $query = "SELECT trans_date as Reg_Date,patient_name,narration,amount,payment_status,pay_method from payment_transaction WHERE user_id='".$_POST['admin_id']."' and user_type=1 and hosp_id='".$_POST['login_hosp_id']."' and trans_date BETWEEN '".date('Y-m-d',strtotime($_SESSION['fromDate']))."' and '".date('Y-m-d',strtotime($_SESSION['toDate']))."' ORDER BY pay_trans_id ASC";  
      }
	  else if(isset($_SESSION['today'])){
	  $query = "SELECT trans_date as Reg_Date,patient_name,narration,amount,payment_status,pay_method from payment_transaction WHERE user_id='".$_POST['admin_id']."' and user_type=1 and hosp_id='".$_POST['login_hosp_id']."' and DATE_FORMAT(trans_date,'%Y-%m-%d') ='".date('Y-m-d')."' ORDER BY pay_trans_id ASC";  
      }
	  else
	  {
		$query = "SELECT trans_date as Reg_Date,patient_name,narration,amount,payment_status,pay_method from payment_transaction WHERE user_id='".$_POST['admin_id']."' and user_type='1' and hosp_id='".$_POST['login_hosp_id']."' ORDER BY pay_trans_id ASC";  
      
	  }
	  $result = mysqli_query($con, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
		unset($_SESSION['fromDate']);
		unset($_SESSION['toDate']);
		unset($_SESSION['today']);
 } 
 
 
 ?>