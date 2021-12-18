<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();

// Book Appointment
 if(API_KEY == $_POST['API_KEY'] ) {

	$login_type = $_POST['login_type'];  // 1 - Hospital Doctor, 2 - Partner, 3 - MArketing Person
	$user_id = $_POST['user_id'];
	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	$txtName = $_POST['se_pat_name'];
	$txtMob = $_POST['se_phone_no'];
	$txtMail = $_POST['se_email'];
	$txtContactPerson = $_POST['se_con_per'];
	$txtAddress = $_POST['se_address'];
	$docid = $_POST['docid'];
	$docspec = $_POST['docspec'];
	$docName = $_POST['se_doc_name'];
	$txtQuery = $_POST['se_doc_query'];
	$transid=time();
	$Cur_Date = date('Y-m-d H:i:s');
	
	
	$chkDate = date('Y-m-d',strtotime($Cur_Date));
	$getSource = $objQuery->mysqlSelect("source_id","source_list","partner_id='".$user_id."'" ,"","","","");
				
	if($login_type == 1)
	{
		
	}
	else if($login_type == 2) 
	{
	
	
		$arrFields_patient[] = 'TImestamp';
		$arrValues_patient[] = $Cur_Date;
		$arrFields_patient[] = 'patient_name';
		$arrValues_patient[] = $txtName;
		$arrFields_patient[] = 'patient_email';
		$arrValues_patient[] = $txtMail;
		
		$arrFields_patient[] = 'patient_mob';
		$arrValues_patient[] = $txtMob;
		$arrFields_patient[] = 'patient_addrs';
		$arrValues_patient[] = $txtAddress;
		$arrFields_patient[] = 'patient_src';
		$arrValues_patient[] = $getSource[0]['source_id'];
		$arrFields_patient[] = 'medDept';
		$arrValues_patient[] = $docspec;
		$arrFields_patient[] = 'system_date';
		$arrValues_patient[] = $cur_Date;
		$arrFields_patient[] = 'transaction_id';
		$arrValues_patient[] = $transid;
		$arrFields_patient[] = 'data_source';
		$arrValues_patient[] = "Android";
		$arrFields_patient[] = 'patient_loc';
		$arrValues_patient[] = $txtAddress;
		$arrFields_patient[] = 'pat_query';
		$arrValues_patient[] = $txtQuery;
		
		$usercreate=$objQuery->mysqlInsert('patient_tab',$arrFields_patient,$arrValues_patient);
		$patientid = mysql_insert_id();  //Get Patient Id
		
		//Update Patient Status	
		$arrFields_ref[] = 'patient_id';
		$arrValues_ref[] = $patientid;
		$arrFields_ref[] = 'status1';
		$arrValues_ref[] = "1";
		$arrFields_ref[] = 'ref_id';
		$arrValues_ref[] = $docid;
		$arrFields_ref[] = 'status2';
		$arrValues_ref[] = "8";
		$arrFields_ref[] = 'bucket_status';
		$arrValues_ref[] = "8";
		$arrFields_ref[] = 'conversion_status';
		$arrValues_ref[] = "2";
		$arrFields_ref[] = 'timestamp';
		$arrValues_ref[] = $Cur_Date;
		$insertpatref=$objQuery->mysqlInsert('patient_referal',$arrFields_ref,$arrValues_ref);
		//$msg="Referred to ".$_SESSION['docname']." Successfully";
		$mednote=$txtName." want to take an appointment from ".$docName." -".$Cur_Date; //MEDISENSE NOTE
		$arrFields_chat[] = 'patient_id';
		$arrValues_chat[] = $patientid;		
		$arrFields_chat[] = 'user_id';
		$arrValues_chat[] = '10';
		$arrFields_chat[] = 'status_id';
		$arrValues_chat[] = '8';
		$arrFields_chat[] = 'ref_id';
		$arrValues_chat[] = $docid;
		$arrFields_chat[] = 'chat_note';
		$arrValues_chat[] = $mednote;
		$arrFields_chat[] = 'TImestamp';
		$arrValues_chat[] = $Cur_Date;
		
		$insertchat=$objQuery->mysqlInsert('chat_notification',$arrFields_chat,$arrValues_chat);
		
		$arrFields = array();
		$arrValues = array();
				$arrFields[] = 'Transaction_id';
				$arrValues[] = $transid;
				$arrFields[] = 'pat_name';
				$arrValues[] = $txtName;
				$arrFields[] = 'Email_id';
				$arrValues[] = $txtMail;
				$arrFields[] = 'Mobile_number';
				$arrValues[] = $txtMob;
				$arrFields[] = 'Address';
				$arrValues[] = $txtAddress;
				
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields,$arrValues);
				$newvisitorid= mysql_insert_id();
				$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patientid."'" ,"","","","");
				
				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'appoint_trans_id';
				$arrValues1[] = $transid;
				$arrFields1[] = 'pref_doc';
				$arrValues1[] = $docid;
				$arrFields1[] = 'department';
				$arrValues1[] = $docspec;
				$arrFields1[] = 'Visiting_date';
				$arrValues1[] = $chkInDate;
				$arrFields1[] = 'Visiting_time';
				$arrValues1[] = $chkInTime;
				$arrFields1[] = 'patient_name';
				$arrValues1[] = $txtName;
				$arrFields1[] = 'Mobile_no';
				$arrValues1[] = $txtMob;
				$arrFields1[] = 'Email_address';
				$arrValues1[] = $txtMail;
				
				$arrFields1[] = 'pay_status';
				$arrValues1[] = "Pending";
				$arrFields1[] = 'visit_status';
				$arrValues1[] = "new_visit";
				$arrFields1[] = 'Time_stamp';
				$arrValues1[] = $Cur_Date;
				
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
				
				$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
				$docmsg="Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense Practice dash board or email . Thanks";
				$mobile = $get_pro[0]['contact_num'];
				send_msg($mobile,$docmsg);
				
			/*	//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense Practice dash board. Many Thanks";
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Appointment Request";
				$subtitle="New Appointment Request";
				$tickerText="Aster CMI new blog";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				} */
				
				$getTime=$objQuery->mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
				//Patient Info EMAIL notification Sent to Doctor
					if(!empty($get_pro[0]['ref_mail'])){
						$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
		
						$url_page = 'Doc_pat_info.php';
						$url = rawurlencode($url_page);
						$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
						$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patAddress=".urlencode($PatAddress);
						$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
						$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
						$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
						$url .= "&prefDate=" . urlencode($chkInDate);
						$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
						$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
						$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
						send_mail($url);	
					}
		
					$success = array('status' => "true","book_appointment_result" => "Appointment has been sent successfully");   
					echo json_encode($success);	
		
	}
	else if($login_type == 3) 
	{
	}
	
}


?>