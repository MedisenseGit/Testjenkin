<?php
include('classes/config.php');
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i a');
$con = getdb();
 if(isset($_POST["Export"])){
		 
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=fdcDoctors'.$curDate.'.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('Reg. Date', 'Status', 'Doctor Name','City','State','Country', 'RBM','ABM', 'Appointments', 'Visits', 'Patients', 'Referrals','Website', 'System IP', 'Last Login Date'));  
     // $query = "SELECT TImestamp as Reg_Date,ref_name,RBM_name,(select COUNT(id) from appointment_transaction_detail where pref_doc=ref_id) as app_count,(select COUNT(episode_id) from doc_patient_episodes where admin_id=ref_id) as visit_count,(select COUNT(outgoing_referrals_id) from doctor_outgoing_referrals where admin_id=ref_id) as refer_count,(select system_ip from practice_login_tracker where doc_id=ref_id) as system_ip,(select timestamp from practice_login_tracker where doc_id=ref_id) as last_login from referal WHERE sponsor_id='2' ORDER BY ref_name ASC";  
      $query = "SELECT TImestamp as Reg_Date,case login_status when '0' then 'Training Required' when '1' then 'Doctor Busy' when '2' then 'Data Privacy Issue' when '3' then 'Did not like our software' when '4' then 'Already using other software' when '5' then 'Internet Issue need offline version' when '6' then 'Like some other software' when '7' then 'Dont need any software' when '8' then 'Want to use WIP' when '9' then 'Follow-up Required' when '10' then 'Active' end as status,ref_name,doc_city,doc_state,doc_country,RBM_name,ABM_Name,(select count(id) from appointment_transaction_detail where pref_doc=ref_id) as app_count,(select count(episode_id) from doc_patient_episodes where admin_id=ref_id) as visit_count,(select count(patient_id) from doc_my_patient where doc_id=ref_id) as patient_count,(select count(outgoing_referrals_id) from doctor_outgoing_referrals where doc_id=ref_id) as refer_count,(select website_name from doctor_webtemplates where doc_id=ref_id) as website,(select system_ip from practice_login_tracker where doc_id=ref_id group by doc_id) as system_ip,(select timestamp from practice_login_tracker where doc_id=ref_id group by doc_id) as last_login from referal WHERE sponsor_id='2' ORDER BY ref_name ASC";  
      
	  $result = mysqli_query($con, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
 } 
 
 if(isset($_POST["ExportPrescription"])){
		 
      header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=FDCDoctorPrescriptionUsageData'.$curDate.'.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('Medicine', 'Generic Name','Company Name', 'Frequency', 'Duration', 'Doctor ID', 'Doctor Name', 'City'));  
     $query = "SELECT a.prescription_trade_name,a.prescription_generic_name,(select company from pharma_products where pp_id=a.pp_id) as company,a.prescription_frequency,a.duration,b.ref_id as doc_id,b.ref_name as doc_name, b.ref_address as doc_city from doc_patient_episode_prescriptions as a left join referal as b on a.doc_id=b.ref_id WHERE b.sponsor_id='2' ORDER BY a.episode_prescription_id DESC";  
      
	  $result = mysqli_query($con, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  
 }

 
 ?>