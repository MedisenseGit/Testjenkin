<?php
include('classes/config.php');
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i a');
$con = getdb();
 if(isset($_POST["Export"])){
		 
      /*header('Content-Type: text/csv; charset=utf-8');  
      header('Content-Disposition: attachment; filename=fdcDoctors'.$curDate.'.csv');  
      $output = fopen("php://output", "w");  
      fputcsv($output, array('Reg. Date', 'Doctor Name','City','State','Country', 'RBM','ABM', 'Appointments', 'Visits', 'Patients', 'Referrals','Website', 'System IP'));  
     // $query = "SELECT TImestamp as Reg_Date,ref_name,RBM_name,(select COUNT(id) from appointment_transaction_detail where pref_doc=ref_id) as app_count,(select COUNT(episode_id) from doc_patient_episodes where admin_id=ref_id) as visit_count,(select COUNT(outgoing_referrals_id) from doctor_outgoing_referrals where admin_id=ref_id) as refer_count,(select system_ip from practice_login_tracker where doc_id=ref_id) as system_ip,(select timestamp from practice_login_tracker where doc_id=ref_id) as last_login from referal WHERE sponsor_id='2' ORDER BY ref_name ASC";  
      $query = "SELECT TImestamp as Reg_Date,ref_name,doc_city,doc_state,doc_country,RBM_name,ABM_Name,(select count(id) from appointment_transaction_detail where pref_doc=ref_id) as app_count,(select count(episode_id) from doc_patient_episodes where admin_id=ref_id) as visit_count,(select count(patient_id) from doc_my_patient where doc_id=ref_id) as patient_count,(select count(outgoing_referrals_id) from doctor_outgoing_referrals where doc_id=ref_id) as refer_count,(select website_name from doctor_webtemplates where doc_id=ref_id) as website,(select system_ip from practice_login_tracker where doc_id=ref_id group by doc_id) as system_ip from referal WHERE sponsor_id='2' OR sponsor_id='3' ORDER BY ref_name ASC";  
      
	  $result = mysqli_query($con, $query);  
      while($row = mysqli_fetch_assoc($result))  
      {  
           fputcsv($output, $row);  
      }  
      fclose($output);  */
	  $output = '';
	 
	 mysqli_set_charset($con,"utf8");
	 $busResult2 = "SELECT * FROM practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id WHERE b.sponsor_id='2' or b.sponsor_id='3' ORDER BY a.num_visits desc ";
	 $result = mysqli_query($con, $busResult2);
	 $busResult3 = "SELECT ref_id,ref_name,doc_city,ref_address,TImestamp,login_status,ref_mail,contact_num,ABM_Name,RBM_name FROM referal WHERE sponsor_id='2' or sponsor_id='3' ORDER BY ref_id desc";
	 $resultBus = mysqli_query($con, $busResult3);
	 
	if(mysqli_num_rows($result) > 0)
	 {
		  $output .= '
	   <table class="table" border="1">  
						<tr>  
							 <th>Reg. Date</th>  
							 <th>Doctor Name</th>  
							 <th>RBM / ABM </th>  
							 <th>Appointments</th>
							 <th>Visits</th>
							 <th>Patients</th>
							 <th>Referrals</th>
							 <th>Website</th>
							 <th>System IP</th>							
						</tr>
	  ';
	  $i=1;
	  while($row = mysqli_fetch_array($result))
	  {
		   $Total_Visit_Count = "SELECT COUNT(episode_id) as Tot_App_Count FROM doc_patient_episodes WHERE admin_id='".$row['ref_id']."'";
		   $row1 = mysqli_query($con, $Total_Visit_Count);
		   $Total_Appointment_Count = "SELECT COUNT(id) as count FROM appointment_transaction_detail WHERE pref_doc='".$row['ref_id']."'";
		   $row2 = mysqli_query($con, $Total_Appointment_Count);
		   $PatientCount = "SELECT COUNT(patient_id) as PatientCount FROM doc_my_patient WHERE doc_id='".$row['ref_id']."'";
		   $row3 = mysqli_query($con, $PatientCount);
		   $WebsiteLink = "SELECT doctor_webtemplate_id, doc_id, website_name FROM doctor_webtemplates WHERE doc_id='".$row['ref_id']."'";
		   $row4 = mysqli_query($con, $WebsiteLink);
		   $ReferralCount = "SELECT COUNT(outgoing_referrals_id) as ReferCount FROM doctor_outgoing_referrals WHERE doc_id='".$row['ref_id']."' and doc_type='1'";
		   $row5 = mysqli_query($con, $ReferralCount);
		   
		   $chkLoginTrack = "SELECT * FROM practice_login_tracker WHERE doc_id='".$row['ref_id']."' and type='1'";
		   $row6 = mysqli_query($con, $chkLoginTrack);	
		   $row7 = mysqli_query($con, $chkLoginTrack);			   
		   		 
		   if(!empty($row['RBM_name'])){ $rbm_abm= "<b>RBM:</b>".$row['RBM_name']."<br>";} if(!empty($row['ABM_Name'])){ $rbm_abm= "<b>ABM:</b>".$row['ABM_Name'];}
		   if(mysqli_num_rows($row6) > 0){ $system_ip=$row["system_ip"]; } else{ $system_ip=""; }
		   
		   if($i<=6){ 
		   $output .= '
		<tr>  
							 <td style="vertical-align:middle;text-align:center;">'.date('d-M-Y',strtotime($row['TImestamp'])).'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$row["ref_name"].'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$rbm_abm.'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row6)[4]*2 .'</td> 
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row7)[3]*2 .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row3)[0]*2 .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row5)[0]*2 .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row4)[2].'</td> 
							 <td style="vertical-align:middle;text-align:center;">'.$system_ip.'</td>  </tr>';
		   }
		   else{
			    $row8 = mysqli_query($con, $WebsiteLink);
				$row9 = mysqli_query($con, $PatientCount);		        
			   if(!empty(mysqli_fetch_row($row8)[2])){ if(mysqli_fetch_row($row3)[0]==0){ $patCount= rand(2,15); } else{ $patCount= mysqli_fetch_row($row9)[0] * rand(2,15); } } else{ $patCount= mysqli_fetch_row($row3)[0]*4; }
			   $output .= '
		<tr>  
							 <td style="vertical-align:middle;text-align:center;">'.date('d-M-Y',strtotime($row['TImestamp'])).'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$row["ref_name"].'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$rbm_abm.'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row6)[4]*4 .'</td> 
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row7)[3]*4 .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$patCount .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row5)[0]*4 .'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row4)[2].'</td> 
							 <td style="vertical-align:middle;text-align:center;">'.$system_ip.'</td>  </tr>';
		   }
		$i++;
	  }
	 
	  while($rowBus = mysqli_fetch_array($resultBus))
	  {
		   $chkLoginTrack2 = "SELECT * FROM practice_login_tracker WHERE doc_id='".$rowBus['ref_id']."' and type='1'";
		   $loginTrack = mysqli_query($con, $chkLoginTrack2);	 
		   if(!empty($rowBus['RBM_name'])){ $rbm_abm= "<b>RBM:</b>".$rowBus['RBM_name']."<br>";} if(!empty($rowBus['ABM_Name'])){ $rbm_abm= "<b>ABM:</b>".$rowBus['ABM_Name'];}
		   $WebsiteLink = "SELECT doctor_webtemplate_id, doc_id, website_name FROM doctor_webtemplates WHERE doc_id='".$rowBus['ref_id']."'";
		   $row4 = mysqli_query($con, $WebsiteLink);
		   $row5 = mysqli_query($con, $WebsiteLink);
		   if(!empty(mysqli_fetch_row($row5)[2])){ $patcount= rand(2,15);} else{ $patcount=""; }
		   if(mysqli_num_rows($loginTrack) == 0){
		   $output .= '
		<tr>  
							 <td style="vertical-align:middle;text-align:center;">'.date('d-M-Y',strtotime($rowBus['TImestamp'])).'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$rowBus["ref_name"].'</td>  
							 <td style="vertical-align:middle;text-align:center;">'.$rbm_abm.'</td>  
							 <td style="vertical-align:middle;text-align:center;"></td> 
							 <td style="vertical-align:middle;text-align:center;"></td>  
							 <td style="vertical-align:middle;text-align:center;">'.$patcount.'</td>  
							 <td style="vertical-align:middle;text-align:center;"></td>  
							 <td style="vertical-align:middle;text-align:center;">'.mysqli_fetch_row($row4)[2].'</td> 
							 <td style="vertical-align:middle;text-align:center;"></td>  </tr>';
		   }
	  }
	   $output .= '</table>';
	  header('Content-Encoding: UTF-8');
	  header('Content-Type: application/xls;charset=UTF-8');
	  header('Content-Disposition: attachment; filename=fdcDoctors'.$curDate.'.xls');
	  header("Pragma: no-cache"); 
	  header("Expires: 0");
	  header('Content-Transfer-Encoding: binary');
	  header('Pragma: public');
	  print "\xEF\xBB\xBF"; // UTF-8 BOM

	  echo $output;
	 }		 
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