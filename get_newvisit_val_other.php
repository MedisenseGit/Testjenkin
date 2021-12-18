<?php ob_start();
 error_reporting(0);
 session_start(); 

include('send_text_message.php');
include('send_mail_function.php');

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

	$Appointtransid = addslashes($_POST['appointtransid']);	
	$Patname = addslashes($_POST['patname']);
	$Userid = addslashes($_POST['userid']);
	$Email = addslashes($_POST['emailid']);
	$Mobnum = addslashes($_POST['mobnum']);
	$Age = addslashes($_POST['patage']);
	$Gend = addslashes($_POST['patgen']);
	$Occup = addslashes($_POST['occup']);
	$City = addslashes($_POST['city']);
	$State = addslashes($_POST['state']);
	$Country = addslashes($_POST['country']);
	$Address = addslashes($_POST['address']);	
	$Prefdoc = addslashes($_POST['prefdoc']);
	$Dept = addslashes($_POST['dept']);
	$Visitdate = addslashes($_POST['visitdate']);
	$Visittime = addslashes($_POST['visittime']);
	$Timestamp = addslashes($_POST['timestamp']);
	
	
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[] = 'Transaction_id';
				$arrValues[] = $Appointtransid;
				$arrFields[] = 'pat_name';
				$arrValues[] = $Patname;
				$arrFields[] = 'Login_User_Id';
				$arrValues[] = $Userid;
				$arrFields[] = 'Email_id';
				$arrValues[] = $Email;
				$arrFields[] = 'Mobile_number';
				$arrValues[] = $Mobnum;
				$arrFields[] = 'pat_age';
				$arrValues[] = $Age;
				$arrFields[] = 'pat_gen';
				$arrValues[] = $Gend;
				$arrFields[] = 'Occupation';
				$arrValues[] = $Occup;
				$arrFields[] = 'City';
				$arrValues[] = $City;
				$arrFields[] = 'State';
				$arrValues[] = $State;
				$arrFields[] = 'Country';
				$arrValues[] = $Country;
				$arrFields[] = 'Address';
				$arrValues[] = $Address;
		
				$addnewpatient=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields,$arrValues);
			
				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'appoint_trans_id';
				$arrValues1[] = $Appointtransid;
				$arrFields1[] = 'pref_doc';
				$arrValues1[] = $Prefdoc;
				$arrFields1[] = 'department';
				$arrValues1[] = $Dept;
				$arrFields1[] = 'Login_User_Id';
				$arrValues1[] = $Userid;				
				
				$arrFields1[] = 'patient_name';
				$arrValues1[] = $Patname;
				$arrFields1[] = 'Mobile_no';
				$arrValues1[] = $Mobnum;
				$arrFields1[] = 'Email_address';
				$arrValues1[] = $Email;
				$arrFields1[] = 'Visiting_date';
				$arrValues1[] = $Visitdate;
				$arrFields1[] = 'Visiting_time';
				$arrValues1[] = $Visittime;
				$arrFields1[] = 'pay_status';
				$arrValues1[] = "Pending";
				$arrFields1[] = 'visit_status';
				$arrValues1[] = "new_visit";
				$arrFields1[] = 'Time_stamp';
				$arrValues1[] = $Timestamp;
				
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
	
	//This is only for non hospital checkin appointments

		$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$Prefdoc."'");
		$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$Prefdoc."'","","","","");
		
		//To check Doctor communication status, 1 for Only to Doc/2 for Only to Hospital/ 3 for Both hosp & Doc
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
	
					//Patient Info EMAIL notification Sent to Doctor
					if(!empty($docmail)){
						$PatAddress=$Address.",<br>".$City.", ".$State.", ".$Country;
						$ccmail="medical@medisense.me";
									$url_page = 'Doc_pat_info1.php';
									
									$url = rawurlencode($url_page);
									$url .= "?patname=".urlencode($Patname);
									$url .= "&transId=".urlencode($Appointtransid);
									$url .= "&prefDate=".urlencode($Visitdate);
									$url .= "&prefTime=".urlencode($Visittime);
									$url .= "&patAddress=".urlencode($PatAddress);
									$url .= "&patEmail=".urlencode($Email);
									$url .= "&patContact=".urlencode($Mobnum);
									$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
									//$url .= "&docmail=" . urlencode($docmail);
									$url .= "&ccmail=" . urlencode($ccmail);		
											
									send_mail($url);
									
					}
					//SMS notification to Doctor/Hospital	
					if(!empty($docnum)){
					$msg = "Dear Doctor ".$Patname."( Ph: ".$Mobnum." )has expressed interest to meet you in person. Please check your mail for more information. Thanks";
					//send_msg($docnum,$msg);
					}
					
					if(!empty($hospnum)){
					$msg = "Dear Doctor ".$Patname."( Ph: ".$Mobnum." )has expressed interest to meet you in person. Please check your mail for more information. Thanks";
					//send_msg($hospnum,$msg);
					}
					
					//SMS notification to Patient
					if($Mobnum!=""){
					$msg = "Your request for appointment with the doctor has been successfully sent to the doctor. You may receive a mail/call on the confirmed date and time within 24Hrs. If you don't hear then call 70266 46022.";
					send_msg($Mobnum,$msg);
					} 
	
?>