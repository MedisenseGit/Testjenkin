<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
include("push_notification_function.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();

$hostname="https://medisensecrm.com/"; //For Prod version
// Book Appointment
 if(API_KEY == $_POST['API_KEY'] ) {

	$login_type = $_POST['login_type'];  // 1 -  Hospital Doctor, 2 - Partner, 3 - MArketing Person
	
	$docid = $_POST['selectref2'];
	$patid = $_POST['Pat_Id'];
	$selectTime = $_POST['selectTime'];
	$selectDate = $_POST['check_date'];
	//$department = $_POST['department'];
	$admin_id = $_POST['user_id'];
	$curDate = date('Y-m-d H:i:s');
	
	if($login_type == 1)
	{
		$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$_POST['selectTime']."'","","","","");
		$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'","","","","");
		$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
		$chkPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$patid."' and ref_id='".$docid."'","","","","");
		//To check whether Doctor belongs Medisense Panel or Hospital 
			if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
				$docnum=$getDocDet[0]['contact_num'];
				$docmail .= $getDocDet[0]['ref_mail'];
			}
			else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
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
			
			//CHECK WHETHER PATIENT IS ALREADY REFERED OR NOT
			if($chkPatReferal==true){
				$arrFields1[]= 'status1';
				$arrValues1[]= '1';
				$arrFields1[]= 'status2';
				$arrValues1[]= '8';
				$arrFields1[]= 'bucket_status';
				$arrValues1[]= '8';
				$arrFields1[]= 'conversion_status';
				$arrValues1[]= '2';
		
				$updatereferer=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patid."' and ref_id='".$docid."'");
		
				$arrFields_bkt[]= 'bucket_status';
				$arrValues_bkt[]= '8';	
		
				$updatereferer=$objQuery->mysqlUpdate('patient_referal',$arrFields_bkt,$arrValues_bkt,"patient_id='".$patid."'");
			} 
			else	{
				$arrFields1[]= 'ref_id';
				$arrValues1[]= $docid;
				$arrFields1[]= 'patient_id';
				$arrValues1[]= $patid;
				$arrFields1[]= 'status1';
				$arrValues1[]= '1';
				$arrFields1[]= 'status2';
				$arrValues1[]= '8';
				$arrFields1[]= 'bucket_status';
				$arrValues1[]= '8';
				$arrFields1[]= 'conversion_status';
				$arrValues1[]= '2';
				$arrFields1[]= 'timestamp';
				$arrValues1[]= $curDate;
		
				$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$patid."'");	
						
				$insertRefer=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
				//REFER COUNT INCREMENTED BY ONE
				$getNumRef=$getDocDet[0]['Total_Referred'];
				$getNumRef=$getNumRef+1;
				$arrFields3 = array();
				$arrValues3 = array();
				$arrFields3[]= 'Total_Referred';
				$arrValues3[]= $getNumRef;
				
				$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$docid."'");
			}
			
			//Insert to appointment table
			if($chkPatDet[0]['patient_gen']=="1"){
				$gender="Male";
			}else{
				$gender="Female";
			}
			$trans_id=time();
			$arrFields_app=array();
			$arrValues_app=array();
				$arrFields_app[] = 'Transaction_id';
				$arrValues_app[] = $trans_id;
				$arrFields_app[] = 'pat_name';
				$arrValues_app[] = $chkPatDet[0]['patient_name'];
				$arrFields_app[] = 'Email_id';
				$arrValues_app[] = $chkPatDet[0]['patient_email'];
				$arrFields_app[] = 'Mobile_number';
				$arrValues_app[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app[] = 'pat_age';
				$arrValues_app[] = $chkPatDet[0]['patient_age'];
				$arrFields_app[] = 'pat_gen';
				$arrValues_app[] = $gender;
				$arrFields_app[] = 'City';
				$arrValues_app[] = $chkPatDet[0]['patient_loc'];
				$arrFields_app[] = 'State';
				$arrValues_app[] = $chkPatDet[0]['pat_state'];
				$arrFields_app[] = 'Country';
				$arrValues_app[] = $chkPatDet[0]['pat_country'];
				$arrFields_app[] = 'Address';
				$arrValues_app[] = $chkPatDet[0]['patient_addrs'];
				
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields_app,$arrValues_app);
				$newvisitorid= mysql_insert_id();
				
				$arrFields_app1[] = 'appoint_trans_id';
				$arrValues_app1[] = $trans_id;
				$arrFields_app1[] = 'pref_doc';
				$arrValues_app1[] = $docid;
				$arrFields_app1[] = 'Visiting_date';
				$arrValues_app1[] = date('Y-m-d',strtotime($_POST['check_date']));
				$arrFields_app1[] = 'Visiting_time';
				$arrValues_app1[] = $getTiming[0]['Timing_id'];
				$arrFields_app1[] = 'patient_name';
				$arrValues_app1[] = $chkPatDet[0]['patient_name'];
				$arrFields_app1[] = 'Mobile_no';
				$arrValues_app1[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app1[] = 'Email_address';
				$arrValues_app1[] = $chkPatDet[0]['patient_email'];
				$arrFields_app1[] = 'pay_status';
				$arrValues_app1[] = "Pending";
				$arrFields_app1[] = 'visit_status';
				$arrValues_app1[] = "new_visit";
				$arrFields_app1[] = 'Time_stamp';
				$arrValues_app1[] = $curDate;
				$arrFields_app1[] = 'department';
				$arrValues_app1[] = $getDocDet[0]['doc_spec'];
				//$arrValues_app1[] = $_POST['department'];
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields_app1,$arrValues_app1);
				
				//Insert records into doctors personal patient table 
				$arrFields_myPatient[] = 'patient_name';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_name'];

				$arrFields_myPatient[] = 'patient_age';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_age'];

				$arrFields_myPatient[] = 'patient_email';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_email'];

				$arrFields_myPatient[] = 'patient_gen';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_gen'];

				$arrFields_myPatient[] = 'hyper_cond';
				$arrValues_myPatient[] = $chkPatDet[0]['hyper_cond'];

				$arrFields_myPatient[] = 'diabetes_cond';
				$arrValues_myPatient[] = $chkPatDet[0]['diabetes_cond'];

				$arrFields_myPatient[] = 'contact_person';
				$arrValues_myPatient[] = $chkPatDet[0]['contact_person'];
				
				/*profession*/
				$arrFields_myPatient[] = 'patient_mob';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_mob'];

				$arrFields_myPatient[] = 'patient_loc';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_loc'];

				$arrFields_myPatient[] = 'pat_state';
				$arrValues_myPatient[] = $chkPatDet[0]['pat_state'];

				$arrFields_myPatient[] = 'pat_country';
				$arrValues_myPatient[] = $chkPatDet[0]['pat_country'];

				$arrFields_myPatient[] = 'patient_addrs';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_addrs'];

				$arrFields_myPatient[] = 'doc_id';
				$arrValues_myPatient[] = $docid;

				$arrFields_myPatient[] = 'system_date';
				$arrValues_myPatient[] = date('Y-m-d',strtotime($curDate));
			
				$arrFields_myPatient[] = 'TImestamp';
				$arrValues_myPatient[] = $curDate;	
			
				$arrFields_myPatient[] = 'transaction_id';
				$arrValues_myPatient[] = $trans_id;
				$userPersonal=$objQuery->mysqlInsert('doc_my_patient',$arrFields_myPatient,$arrValues_myPatient);
				
				$mednote=$chkPatDet[0]['patient_name']." want to take an appointment from ".$getDocDet[0]['ref_name']." -".$curDate; //MEDISENSE NOTE
				$arrFields2 = array();
				$arrValues2 = array();
				$arrFields2[] = 'patient_id';
				$arrValues2[] = $patid;
				$arrFields2[] = 'ref_id';
				$arrValues2[] = $docid;
				$arrFields2[] = 'chat_note';
				$arrValues2[] = $mednote;
				$arrFields2[] = 'status_id';
				$arrValues2[] = '8';
				$arrFields2[] = 'TImestamp';
				$arrValues2[] = $curDate;
				$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
				
				//Patient Info EMAIL notification Sent to Doctor
				if(!empty($docmail)){
					$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
		
					$get_partner_details = $objQuery->mysqlSelect('partner_name,Email_id','our_partners',"partner_id='".$admin_id."'");
		
					$url_page = 'Custom_doc_pat_info.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&prefDate=" . urlencode($_POST['check_date']);
					$url .= "&prefTime=" . urlencode($getTiming[0]['Timing']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
					$url .= "&compEmail=".urlencode($get_partner_details[0]['Email_id']);
					//$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&hospName=".urlencode($get_partner_details[0]['partner_name']);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);
					
				}
				
					//SMS notification to Doctor
					$msg = "Dear Doctor ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to meet you in person. We have also sent your appointment link. Thanks";
					
					if(!empty($docnum)){
					send_msg($docnum,$msg);
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$msg);
					}
					
					$success = array('status' => "true","book_appointment" => "Appointment resquest sent successfully");   
					echo json_encode($success);	
		
	}
	else if($login_type == 2) 
	{
		$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$_POST['selectTime']."'","","","","");
		$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'","","","","");
		$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
		$chkPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$patid."' and ref_id='".$docid."'","","","","");
		$getPartnerDet = $objQuery->mysqlSelect("partner_id,partner_name,contact_person,doc_photo","our_partners","partner_id='".$admin_id."'","","","","");			
		$getMarketPerson = $objQuery->mysqlSelect("a.person_email as person_email,a.person_mobile as person_mobile,a.gcm_tokenid as gcm_id","hosp_marketing_person as a left join mapping_hosp_referrer as b on a.person_id=b.market_person_id","b.partner_id='".$admin_id."' and b.hosp_id='".$getDocDet[0]['hosp_id']."'","","","","");
			
		//To check whether Doctor belongs Medisense Panel or Hospital 
			if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
				$docnum=$getDocDet[0]['contact_num'];
				$docmail .= $getDocDet[0]['ref_mail'];
			}
			else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
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
			
			//CHECK WHETHER PATIENT IS ALREADY REFERED OR NOT
			if($chkPatReferal==true){
				$arrFields1[]= 'status1';
				$arrValues1[]= '1';
				$arrFields1[]= 'status2';
				$arrValues1[]= '8';
				$arrFields1[]= 'bucket_status';
				$arrValues1[]= '8';
				$arrFields1[]= 'conversion_status';
				$arrValues1[]= '2';
		
				$updatereferer=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patid."' and ref_id='".$docid."'");
		
				$arrFields_bkt[]= 'bucket_status';
				$arrValues_bkt[]= '8';	
		
				$updatereferer=$objQuery->mysqlUpdate('patient_referal',$arrFields_bkt,$arrValues_bkt,"patient_id='".$patid."'");
			} 
			else	{
				$arrFields1[]= 'ref_id';
				$arrValues1[]= $docid;
				$arrFields1[]= 'patient_id';
				$arrValues1[]= $patid;
				$arrFields1[]= 'status1';
				$arrValues1[]= '1';
				$arrFields1[]= 'status2';
				$arrValues1[]= '8';
				$arrFields1[]= 'bucket_status';
				$arrValues1[]= '8';
				$arrFields1[]= 'conversion_status';
				$arrValues1[]= '2';
				$arrFields1[]= 'timestamp';
				$arrValues1[]= $curDate;
		
				$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$patid."'");	
						
				$insertRefer=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
				//REFER COUNT INCREMENTED BY ONE
				$getNumRef=$getDocDet[0]['Total_Referred'];
				$getNumRef=$getNumRef+1;
				$arrFields3 = array();
				$arrValues3 = array();
				$arrFields3[]= 'Total_Referred';
				$arrValues3[]= $getNumRef;
				
				$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$docid."'");
			}
			
			//Insert to appointment table
			if($chkPatDet[0]['patient_gen']=="1"){
				$gender="Male";
			}else{
				$gender="Female";
			}
			$trans_id=time();
			$arrFields_app=array();
			$arrValues_app=array();
				$arrFields_app[] = 'Transaction_id';
				$arrValues_app[] = $trans_id;
				$arrFields_app[] = 'pat_name';
				$arrValues_app[] = $chkPatDet[0]['patient_name'];
				$arrFields_app[] = 'Email_id';
				$arrValues_app[] = $chkPatDet[0]['patient_email'];
				$arrFields_app[] = 'Mobile_number';
				$arrValues_app[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app[] = 'pat_age';
				$arrValues_app[] = $chkPatDet[0]['patient_age'];
				$arrFields_app[] = 'pat_gen';
				$arrValues_app[] = $gender;
				$arrFields_app[] = 'City';
				$arrValues_app[] = $chkPatDet[0]['patient_loc'];
				$arrFields_app[] = 'State';
				$arrValues_app[] = $chkPatDet[0]['pat_state'];
				$arrFields_app[] = 'Country';
				$arrValues_app[] = $chkPatDet[0]['pat_country'];
				$arrFields_app[] = 'Address';
				$arrValues_app[] = $chkPatDet[0]['patient_addrs'];
				
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields_app,$arrValues_app);
				$newvisitorid= mysql_insert_id();
				
				$arrFields_app1[] = 'appoint_trans_id';
				$arrValues_app1[] = $trans_id;
				$arrFields_app1[] = 'pref_doc';
				$arrValues_app1[] = $docid;
				$arrFields_app1[] = 'Visiting_date';
				$arrValues_app1[] = date('Y-m-d',strtotime($_POST['check_date']));
				$arrFields_app1[] = 'Visiting_time';
				$arrValues_app1[] = $getTiming[0]['Timing_id'];
				$arrFields_app1[] = 'patient_name';
				$arrValues_app1[] = $chkPatDet[0]['patient_name'];
				$arrFields_app1[] = 'Mobile_no';
				$arrValues_app1[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app1[] = 'Email_address';
				$arrValues_app1[] = $chkPatDet[0]['patient_email'];
				$arrFields_app1[] = 'pay_status';
				$arrValues_app1[] = "Pending";
				$arrFields_app1[] = 'visit_status';
				$arrValues_app1[] = "new_visit";
				$arrFields_app1[] = 'Time_stamp';
				$arrValues_app1[] = $curDate;
				$arrFields_app1[] = 'department';
				$arrValues_app1[] = $getDocDet[0]['doc_spec'];
				//$arrValues_app1[] = $_POST['department'];
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields_app1,$arrValues_app1);
				
				//Insert records into doctors personal patient table 
				$arrFields_myPatient[] = 'patient_name';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_name'];

				$arrFields_myPatient[] = 'patient_age';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_age'];

				$arrFields_myPatient[] = 'patient_email';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_email'];

				$arrFields_myPatient[] = 'patient_gen';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_gen'];

				$arrFields_myPatient[] = 'hyper_cond';
				$arrValues_myPatient[] = $chkPatDet[0]['hyper_cond'];

				$arrFields_myPatient[] = 'diabetes_cond';
				$arrValues_myPatient[] = $chkPatDet[0]['diabetes_cond'];

				$arrFields_myPatient[] = 'contact_person';
				$arrValues_myPatient[] = $chkPatDet[0]['contact_person'];
				
				/*profession*/
				$arrFields_myPatient[] = 'patient_mob';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_mob'];

				$arrFields_myPatient[] = 'patient_loc';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_loc'];

				$arrFields_myPatient[] = 'pat_state';
				$arrValues_myPatient[] = $chkPatDet[0]['pat_state'];

				$arrFields_myPatient[] = 'pat_country';
				$arrValues_myPatient[] = $chkPatDet[0]['pat_country'];

				$arrFields_myPatient[] = 'patient_addrs';
				$arrValues_myPatient[] = $chkPatDet[0]['patient_addrs'];

				$arrFields_myPatient[] = 'doc_id';
				$arrValues_myPatient[] = $docid;

				$arrFields_myPatient[] = 'system_date';
				$arrValues_myPatient[] = date('Y-m-d',strtotime($curDate));
			
				$arrFields_myPatient[] = 'TImestamp';
				$arrValues_myPatient[] = $curDate;	
			
				$arrFields_myPatient[] = 'transaction_id';
				$arrValues_myPatient[] = $trans_id;
				$userPersonal=$objQuery->mysqlInsert('doc_my_patient',$arrFields_myPatient,$arrValues_myPatient);
				
				$mednote=$chkPatDet[0]['patient_name']." want to take an appointment from ".$getDocDet[0]['ref_name']." -".$curDate; //MEDISENSE NOTE
				$arrFields2 = array();
				$arrValues2 = array();
				$arrFields2[] = 'patient_id';
				$arrValues2[] = $patid;
				$arrFields2[] = 'ref_id';
				$arrValues2[] = $docid;
				$arrFields2[] = 'chat_note';
				$arrValues2[] = $mednote;
				$arrFields2[] = 'status_id';
				$arrValues2[] = '8';
				$arrFields2[] = 'TImestamp';
				$arrValues2[] = $curDate;
				$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
				
				//Patient Info EMAIL notification Sent to Doctor
				if(!empty($docmail)){
					$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
		
					$get_partner_details = $objQuery->mysqlSelect('partner_name,Email_id','our_partners',"partner_id='".$admin_id."'");
		
					$url_page = 'Custom_doc_pat_info.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&prefDate=" . urlencode($_POST['check_date']);
					$url .= "&prefTime=" . urlencode($getTiming[0]['Timing']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
					$url .= "&compEmail=".urlencode($get_partner_details[0]['Email_id']);
					//$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&hospName=".urlencode($get_partner_details[0]['partner_name']);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);
					
				}
				
					//SMS notification to Doctor
					$msg = "Dear Doctor ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to meet you in person. We have also sent your appointment link. Thanks";
					
					if(!empty($docnum)){
					send_msg($docnum,$msg);
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$msg);
					}
					
					
					//Here we need to Send Push notification to Partner
				
				
					$docmsg = "Dear Doctor ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to meet you in person. Thanks";
					$title="Appointment Request";
					$subtitle=$chkPatDet[0]['patient_name'] . "( Patient ID: ". $chkPatDet[0]['patient_id'] .")";
					$tickerText="Test Ticker";
					$type="4"; //For Event type 2
					$patientid=$chkPatDet[0]['patient_id'];
					$postid="0";
					$docid=$docid;
					$largeimg='large_icon';	
					$postkey="0";
					if(!empty($getPartnerDet[0]['doc_photo'])){ 
					$smalimg=$hostname."/standard/partnerProfilePic/".$getPartnerDet[0]['partner_id']."/".$getPartnerDet[0]['doc_photo'];
					}else{
					$smalimg="https://medisensecrm.com/assets/images/practice_push_icon.png";
					}
					//Push Notification to premium doctor	
					if(!empty($getDocDet[0]['gcm_tokenid'])){
					$regid = $getDocDet[0]['gcm_tokenid'];
					push_notification_prem_doc($regid,$docmsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
					}
					//Push Notification to marketing person
					if(!empty($getMarketPerson[0]['gcm_id'])){
					$regid=$getMarketPerson[0]['gcm_id'];
					push_notification_prem_doc($regid,$docmsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
					}
					//End Push notification functionality
		
					
					$success = array('status' => "true","book_appointment" => "Appointment resquest sent successfully");   
					echo json_encode($success);	
	}
	else if($login_type == 3) 
	{
	}
	
}


?>