<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$Cur_Date_Time=date('d-m-Y h:i a');

$admin_id="10";
$ccmail="medical@medisense.me";
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

					
	$cust_id = $_POST['custid'];
	$TransId = $_POST['transid'];
	$Payid = $_POST['payid'];
	$Patname = $_POST['patname'];
	$Patmail = $_POST['patmail'];
	$Patmobile = $_POST['patmobile'];
	$Amount = $_POST['amount'];
	$PatId = $_POST['patid'];
	$TransStatus= $_POST['transtatus'];
	$Transdate= $_POST['transtime'];
	$Patservice= $_POST['patservice'];
				
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'cust_id';
		$arrValues1[] = $cust_id;
		$arrFields1[] = 'patient_id';
		$arrValues1[] = $PatId;
		$arrFields1[] = 'Payment_id';
		$arrValues1[] = $Payid;
		$arrFields1[] = 'transaction_id';
		$arrValues1[] = $TransId;
		$arrFields1[] = 'patient_name';
		$arrValues1[] = $Patname;
		$arrFields1[] = 'email_id';
		$arrValues1[] = $Patmail;
		$arrFields1[] = 'mobile_no';
		$arrValues1[] = $Patmobile;
		$arrFields1[] = 'service_type';
		$arrValues1[] = $Patservice;
		$arrFields1[] = 'ref_id';
		$arrValues1[] = "1";
		$arrFields1[] = 'amount';
		$arrValues1[] = $Amount;
		$arrFields1[] = 'Pay_status';
		$arrValues1[] = $TransStatus;
		$arrFields1[] = 'transaction_time';
		$arrValues1[] = $Transdate;
		$usercraete=$objQuery->mysqlInsert('customer_transaction',$arrFields1,$arrValues1);
	
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[] = 'transaction_status';
		$arrValues[] = $TransStatus;
		$arrFields[] = 'transaction_id';   //HERE IT UPDATE TRANS STATUS & OLD TRANS ID
		$arrValues[] = $TransId;
	
		$update=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$PatId."'");
		
		//RETRIEVE DETAILS
		$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$PatId."'" ,"","","","");
		$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$PatId."'" ,"","","","");
		$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");		
		
		
		
		//HERE SYSTEM WILL REFER THIS CASE TO PATIENT CHOSEN DOCTORS
		$DocId1 = $_POST['docid1'];
		$DocId2 = $_POST['docid2'];
		$DocId3 = $_POST['docid3'];
		$DocId4 = $_POST['docid4'];	
		
		//REFER TO FIRST CHOSEN DOCTOR
		if(!empty($DocId1)){
		$arrFields2 = array();
		$arrValues2 = array();
		
		$arrFields2[]= 'patient_id';
		$arrValues2[]= $PatId;
		$arrFields2[]= 'ref_id';
		$arrValues2[]= $DocId1;
		$arrFields2[]= 'status1';
		$arrValues2[]= "1";
		$arrFields2[]= 'status2';
		$arrValues2[]= "2";
		$arrFields2[]= 'bucket_status';
		$arrValues2[]= "2";
		$arrFields2[]= 'timestamp';
		$arrValues2[]= $Cur_Date;
		
		$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$PatId."'");	
		$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
		$ref_id=mysql_insert_id();
		
		$get_pro1 = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$DocId1."'");
		$getDocDept1= $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$DocId1."'","","","","");
		
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro1[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields11 = array();
					$arrValues11 = array();
					$arrFields11[]= 'Total_Referred';
					$arrValues11[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields11,$arrValues11,"ref_id='".$get_pro1[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro1[0]['ref_name']." Successfully";
					$arrFields12 = array();
					$arrValues12 = array();
									
					$arrFields12[]= 'patient_id';
					$arrValues12[]= $getPatInfo[0]['patient_id'];
					$arrFields12[]= 'ref_id';
					$arrValues12[]= $get_pro1[0]['ref_id'];
					$arrFields12[]= 'chat_note';
					$arrValues12[]= $txtProNote1;
					$arrFields12[]= 'user_id';
					$arrValues12[]= $admin_id;
					$arrFields12[]= 'status_id';
					$arrValues12[]= '2';
					$arrFields12[]= 'TImestamp';
					$arrValues12[]= $Cur_Date;					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields12,$arrValues12);
					
					//Medisense Note
					$msg="Refered to ".$get_pro1[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields13 = array();
					$arrValues13 = array();
					$arrFields13[] = 'patient_id';
					$arrValues13[] = $getPatInfo[0]['patient_id'];
					$arrFields13[] = 'ref_id';
					$arrValues13[] = "0";
					$arrFields13[] = 'chat_note';
					$arrValues13[] = $msg;
					$arrFields13[] = 'user_id';
					$arrValues13[] = $admin_id;
					$arrFields13[] = 'TImestamp';
					$arrValues13[] = $Cur_Date;					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields13,$arrValues13);
		
		
						$docname=$get_pro1[0]['ref_name'];
						
						if($get_pro1[0]['communication_status']==1)
						{
							$docmail1 = $get_pro1[0]['ref_mail'];
							$pro_contact1=$get_pro1[0]['contact_num'];
							
						} else if($get_pro1[0]['communication_status']==2)
						{
							$docmail1 .= $get_pro1[0]['hosp_email'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email1'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email2'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email3'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email4'];
							$pro_contact1=$get_pro1[0]['hosp_contact'];
						} else if($get_pro1[0]['communication_status']==3)
						{
							$docmail1 .= $get_pro1[0]['hosp_email'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email1'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email2'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email3'] . ', ';
							$docmail1 .= $get_pro1[0]['hosp_email4'] . ', ';
							$docmail1 .= $get_pro1[0]['ref_mail'];
							
							$pro_contact1=$get_pro1[0]['hosp_contact'];
							$doc_contact1=$get_pro1[0]['contact_num'];
						}
					send_email_doc($docname,$docmail1,$PatId);	
					
					
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
							
					//For sending SMS notification to Refering Doctors only when messge_status is active
					if($get_pro1[0]['message_status']==1 && $pro_contact1!=""){
					$mobile1 = $pro_contact1;
					send_msg($mobile1,$msg);	
					}
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact1!="" && $get_pro1[0]['message_status']==1)
					{
					$mobile1 = $doc_contact1;
					send_msg($mobile1,$msg);	
					}
		}
		
		//REFER TO SECOND CHOSEN DOCTOR
		if(!empty($DocId2)){
		$arrFields2 = array();
		$arrValues2 = array();
		
		$arrFields2[]= 'patient_id';
		$arrValues2[]= $PatId;
		$arrFields2[]= 'ref_id';
		$arrValues2[]= $DocId2;
		$arrFields2[]= 'status1';
		$arrValues2[]= "1";
		$arrFields2[]= 'status2';
		$arrValues2[]= "2";
		$arrFields2[]= 'bucket_status';
		$arrValues2[]= "2";
		$arrFields2[]= 'timestamp';
		$arrValues2[]= $Cur_Date;
		
		$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$PatId."'");	
		$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
				
		$get_pro2 = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$DocId2."'");
		$getDocDept1= $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$DocId2."'","","","","");
		
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro2[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields11 = array();
					$arrValues11 = array();
					$arrFields11[]= 'Total_Referred';
					$arrValues11[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields11,$arrValues11,"ref_id='".$get_pro2[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro2[0]['ref_name']." Successfully";
					$arrFields12 = array();
					$arrValues12 = array();
									
					$arrFields12[]= 'patient_id';
					$arrValues12[]= $getPatInfo[0]['patient_id'];
					$arrFields12[]= 'ref_id';
					$arrValues12[]= $get_pro2[0]['ref_id'];
					$arrFields12[]= 'chat_note';
					$arrValues12[]= $txtProNote1;
					$arrFields12[]= 'user_id';
					$arrValues12[]= $admin_id;
					$arrFields12[]= 'status_id';
					$arrValues12[]= '2';
					$arrFields12[]= 'TImestamp';
					$arrValues12[]= $Cur_Date;					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields12,$arrValues12);
					
					//Medisense Note
					$msg="Refered to ".$get_pro2[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields13 = array();
					$arrValues13 = array();
					$arrFields13[] = 'patient_id';
					$arrValues13[] = $getPatInfo[0]['patient_id'];
					$arrFields13[] = 'ref_id';
					$arrValues13[] = "0";
					$arrFields13[] = 'chat_note';
					$arrValues13[] = $msg;
					$arrFields13[] = 'user_id';
					$arrValues13[] = $admin_id;
					$arrFields13[] = 'TImestamp';
					$arrValues13[] = $Cur_Date;					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields13,$arrValues13);
		
		
						$docname=$get_pro2[0]['ref_name'];
						
						if($get_pro2[0]['communication_status']==1)
						{
							$docmail2 = $get_pro2[0]['ref_mail'];
							$pro_contact2=$get_pro2[0]['contact_num'];
							
						} else if($get_pro2[0]['communication_status']==2)
						{
							$docmail2 .= $get_pro2[0]['hosp_email'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email1'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email2'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email3'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email4'];
							$pro_contact2=$get_pro2[0]['hosp_contact'];
						} else if($get_pro2[0]['communication_status']==3)
						{
							$docmail2 .= $get_pro2[0]['hosp_email'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email1'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email2'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email3'] . ', ';
							$docmail2 .= $get_pro2[0]['hosp_email4'] . ', ';
							$docmail2 .= $get_pro2[0]['ref_mail'];
							
							$pro_contact2=$get_pro2[0]['hosp_contact'];
							$doc_contact2=$get_pro2[0]['contact_num'];
						}
					send_email_doc($docname,$docmail2,$PatId);	
					
					
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
							
					//For sending SMS notification to Refering Doctors only when messge_status is active
					if($get_pro2[0]['message_status']==1 && $pro_contact2!=""){
					$mobile2 = $pro_contact2;
					send_msg($mobile2,$msg);	
					}
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact2!="" && $get_pro2[0]['message_status']==1)
					{
					$mobile2 = $doc_contact2;
					send_msg($mobile2,$msg);	
					}
		}
		
		//REFER TO THIRD CHOSEN DOCTOR
		if(!empty($DocId3)){
		$arrFields2 = array();
		$arrValues2 = array();
		
		$arrFields2[]= 'patient_id';
		$arrValues2[]= $PatId;
		$arrFields2[]= 'ref_id';
		$arrValues2[]= $DocId3;
		$arrFields2[]= 'status1';
		$arrValues2[]= "1";
		$arrFields2[]= 'status2';
		$arrValues2[]= "2";
		$arrFields2[]= 'bucket_status';
		$arrValues2[]= "2";
		$arrFields2[]= 'timestamp';
		$arrValues2[]= $Cur_Date;
		
		$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$PatId."'");	
		$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
		
		$get_pro3 = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$DocId3."'");
		$getDocDept1= $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$DocId3."'","","","","");
		
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro3[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields11 = array();
					$arrValues11 = array();
					$arrFields11[]= 'Total_Referred';
					$arrValues11[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields11,$arrValues11,"ref_id='".$get_pro3[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro3[0]['ref_name']." Successfully";
					$arrFields12 = array();
					$arrValues12 = array();
									
					$arrFields12[]= 'patient_id';
					$arrValues12[]= $getPatInfo[0]['patient_id'];
					$arrFields12[]= 'ref_id';
					$arrValues12[]= $get_pro3[0]['ref_id'];
					$arrFields12[]= 'chat_note';
					$arrValues12[]= $txtProNote1;
					$arrFields12[]= 'user_id';
					$arrValues12[]= $admin_id;
					$arrFields12[]= 'status_id';
					$arrValues12[]= '2';
					$arrFields12[]= 'TImestamp';
					$arrValues12[]= $Cur_Date;					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields12,$arrValues12);
					
					//Medisense Note
					$msg="Refered to ".$get_pro3[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields13 = array();
					$arrValues13 = array();
					$arrFields13[] = 'patient_id';
					$arrValues13[] = $getPatInfo[0]['patient_id'];
					$arrFields13[] = 'ref_id';
					$arrValues13[] = "0";
					$arrFields13[] = 'chat_note';
					$arrValues13[] = $msg;
					$arrFields13[] = 'user_id';
					$arrValues13[] = $admin_id;
					$arrFields13[] = 'TImestamp';
					$arrValues13[] = $Cur_Date;					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields13,$arrValues13);
		
		
						$docname=$get_pro3[0]['ref_name'];
						
						if($get_pro3[0]['communication_status']==1)
						{
							$docmail3 = $get_pro3[0]['ref_mail'];
							$pro_contact3=$get_pro3[0]['contact_num'];
							
						} else if($get_pro3[0]['communication_status']==2)
						{
							$docmail3 .= $get_pro3[0]['hosp_email'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email1'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email2'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email3'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email4'];
							$pro_contact3=$get_pro3[0]['hosp_contact'];
						} else if($get_pro3[0]['communication_status']==3)
						{
							$docmail3 .= $get_pro3[0]['hosp_email'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email1'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email2'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email3'] . ', ';
							$docmail3 .= $get_pro3[0]['hosp_email4'] . ', ';
							$docmail3 .= $get_pro3[0]['ref_mail'];
							
							$pro_contact3=$get_pro3[0]['hosp_contact'];
							$doc_contact3=$get_pro3[0]['contact_num'];
						}
					send_email_doc($docname,$docmail3,$PatId);	
					
					
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
							
					//For sending SMS notification to Refering Doctors only when messge_status is active
					if($get_pro3[0]['message_status']==1 && $pro_contact3!=""){
					$mobile3 = $pro_contact3;
					send_msg($mobile3,$msg);	
					}
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact3!="" && $get_pro3[0]['message_status']==1)
					{
					$mobile3 = $doc_contact3;
					send_msg($mobile3,$msg);	
					}
		}
		
		//REFER TO FOURTH CHOSEN DOCTOR
		if(!empty($DocId4)){
		$arrFields2 = array();
		$arrValues2 = array();
		
		$arrFields2[]= 'patient_id';
		$arrValues2[]= $PatId;
		$arrFields2[]= 'ref_id';
		$arrValues2[]= $DocId4;
		$arrFields2[]= 'status1';
		$arrValues2[]= "1";
		$arrFields2[]= 'status2';
		$arrValues2[]= "2";
		$arrFields2[]= 'bucket_status';
		$arrValues2[]= "2";
		$arrFields2[]= 'timestamp';
		$arrValues2[]= $Cur_Date;
		
		$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$PatId."'");	
		$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
		$ref_id=mysql_insert_id();
		
		$get_pro4 = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$DocId4."'");
		$getDocDept1= $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$DocId4."'","","","","");
		
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro4[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields11 = array();
					$arrValues11 = array();
					$arrFields11[]= 'Total_Referred';
					$arrValues11[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields11,$arrValues11,"ref_id='".$get_pro4[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro4[0]['ref_name']." Successfully";
					$arrFields12 = array();
					$arrValues12 = array();
									
					$arrFields12[]= 'patient_id';
					$arrValues12[]= $getPatInfo[0]['patient_id'];
					$arrFields12[]= 'ref_id';
					$arrValues12[]= $get_pro4[0]['ref_id'];
					$arrFields12[]= 'chat_note';
					$arrValues12[]= $txtProNote1;
					$arrFields12[]= 'user_id';
					$arrValues12[]= $admin_id;
					$arrFields12[]= 'status_id';
					$arrValues12[]= '2';
					$arrFields12[]= 'TImestamp';
					$arrValues12[]= $Cur_Date;					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields12,$arrValues12);
					
					//Medisense Note
					$msg="Refered to ".$get_pro4[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields13 = array();
					$arrValues13 = array();
					$arrFields13[] = 'patient_id';
					$arrValues13[] = $getPatInfo[0]['patient_id'];
					$arrFields13[] = 'ref_id';
					$arrValues13[] = "0";
					$arrFields13[] = 'chat_note';
					$arrValues13[] = $msg;
					$arrFields13[] = 'user_id';
					$arrValues13[] = $admin_id;
					$arrFields13[] = 'TImestamp';
					$arrValues13[] = $Cur_Date;					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields13,$arrValues13);
		
		
						$docname=$get_pro4[0]['ref_name'];
						
						if($get_pro3[0]['communication_status']==1)
						{
							$docmail4 = $get_pro4[0]['ref_mail'];
							$pro_contact4=$get_pro4[0]['contact_num'];
							
						} else if($get_pro4[0]['communication_status']==2)
						{
							$docmail4 .= $get_pro4[0]['hosp_email'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email1'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email2'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email3'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email4'];
							$pro_contact4=$get_pro4[0]['hosp_contact'];
						} else if($get_pro4[0]['communication_status']==3)
						{
							$docmail4 .= $get_pro4[0]['hosp_email'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email1'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email2'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email3'] . ', ';
							$docmail4 .= $get_pro4[0]['hosp_email4'] . ', ';
							$docmail4 .= $get_pro4[0]['ref_mail'];
							
							$pro_contact4=$get_pro4[0]['hosp_contact'];
							$doc_contact4=$get_pro4[0]['contact_num'];
						}
					send_email_doc($docname,$docmail4,$PatId);	
					
					
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
							
					//For sending SMS notification to Refering Doctors only when messge_status is active
					if($get_pro4[0]['message_status']==1 && $pro_contact4!=""){
					$mobile4 = $pro_contact4;
					send_msg($mobile4,$msg);	
					}
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact4!="" && $get_pro4[0]['message_status']==1)
					{
					$mobile4 = $doc_contact4;
					send_msg($mobile4,$msg);	
					}
					
					
		}
		
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!=""){
					$Pat_mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query has been successfully referred to prefered panelist. Please check your mail for further detail. Medisensehealth.com";
					send_msg($Pat_mobile,$responsemsg);
					}
					
					//EMAIL notification to Patient					
					if($getPatInfo[0]['patient_email']!=""){
						
						$getTotRef = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$PatId."'","","","","");	
							$doctorProfile='';
							foreach($getTotRef as $key=>$value){
								$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$value['ref_id']."'","","","","");
								$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$value['ref_id']."'","","","","");
								
								$randid=time();
								//Doc Info EMAIL notification Sent to Patient
					
									if(!empty($getRef[0]['doc_photo'])){
										$docimg=HOST_MAIN_URL."Doc/".$getRef[0]['ref_id']."/".$getRef[0]['doc_photo'];
									}	
									else{
										$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
									}
				
									$getDocName=urlencode(str_replace(' ','-',$getRef[0]['ref_name']));
									$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
									$getDocCity=urlencode(str_replace(' ','-',$getRef[0]['ref_address']));
									$getDocState=urlencode(str_replace(' ','-',$getRef[0]['doc_state']));
									$getDocHosp=urlencode(str_replace(' ','-',$getRef[0]['hosp_name']));
									$getDocHospAdd=urlencode(str_replace(' ','-',$getRef[0]['hosp_addrs']));		

								$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocCity.'-'.$getDocState.'/'.$getRef[0]['ref_id'];
								
								$docAddress=$getSpec[0]['spec_name'];
				
								$doctorProfile .="<table cellpadding='0' cellspacing='0' width='50%' border='0' style='float:left; margin-bottom:20px;'><tbody><tr><td style='width:120px;'><div style='float:left; border:1px solid #FFCC00; margin-top:10px; padding:10px; width:100px; height:120px;'><img src=".$docimg." width='100' height='120' /></div></td><td style='width:170px;'><div style='font-family:Tahoma; font-size:13px; color:#404141; width:200px; text-align:left; font-style:italic;line-height:20px;font-weight:bold;padding-left:20px;padding-top:6pt;padding-bottom:0pt;font-family:Arial;'>".$getRef[0]['ref_name']."<br>".$getSpec[0]['spec_name']."<br>For more information please <a href='".$Link."' target='_blank'>Click here</a><br><br><a href='".HOST_MAIN_URL."Email_Response/response.php?randid=".$randid."&patid=".$PatId."&docid=".$getRef[0]['ref_id']."&eventtype=4 style='text-decoration:none;' target='_blank'><div style='float:left; width:150px; text-align:center; font-size:11px;  color:#fff; font-weight:bold; border-radius:20px; padding:5px;background:#f68c34;'>MEET THIS EXPERT</div></a></div></td></tr></tbody></table>";
								
							}
						$url_page = 'After_refer_pat_mail_new.php';
						
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?patname=" . urlencode($getPatInfo[0]['patient_name']);
						$url .= "&doctorprofile=".urlencode($doctorProfile);					
						$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);	
							
						$ch = curl_init (); // setup a curl					
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
						
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );					
						// echo "output".$output;					
						curl_close ( $ch );
					}
					
function send_email_doc($docname,$docmail,$patid){
	require_once("classes/querymaker.class.php");
	$objQuery = new CLSQueryMaker();
	$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'" ,"","","","");
	$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$patid."'" ,"","","","");
	$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");		
			
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}
						
						if($getPatInfo[0]['hyper_cond']==0){
							
							$Hyper_Cond="No";
						} else {
							$Hyper_Cond="Yes";
						}
						
						if($getPatInfo[0]['diabetes_cond']==0){
							
							$Diabetic_Cond="No";
						} else {
							$Diabetic_Cond="Yes";
						}
						
						if($getPatInfo[0]['lead_type']=="Hot"){
							
							$Lead_Cond="H";
							$Time="4hrs";
						} else if($getPatInfo[0]['lead_type']=="Warm"){
							$Lead_Cond="W";
							$Time="7hrs";
						} else {
							$Lead_Cond="O";
							$Time="24hrs";
						}
						
						if($getPatInfo[0]['qualification']==0){
							$pat_qualification="NS";
						} else {
							$pat_qualification=$getPatInfo[0]['qualification'];
						}
						
						if($getPatInfo[0]['pat_country']=="India"){
							$queryType="D";
						} else {
							$queryType="I";
						}
						
						if($getPatInfo[0]['repnotattach']==1)
						{
							$noreportmsg="No medical report attached";
						}
						
						
						
						if($getPatInfo[0]['transaction_status']=="TXN_SUCCESS"){
							$paid_msg="PAID QUERY- ";
						}
						
						//INCLUDE PATIENT CONTACT DETAILS
						$patContactDet= "Patient Contact Details: <br>Contact No. :".$getPatInfo[0]['patient_mob']."<br>Email Address :".$getPatInfo[0]['patient_email'];
						$chk_prior="PRIORITY";
					
					$subject=$chk_prior." ".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
					
					$ccmail="medical@medisense.me";
					$url_page  = 'refdocmail.php';					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patid=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patage=" . urlencode($getPatInfo[0]['patient_age']);
					$url .= "&patgend=" . urlencode($Pat_Gen);
					$url .= "&patweight=" . urlencode($getPatInfo[0]['weight']);
					$url .= "&patmerital=" . urlencode($getPatInfo[0]['merital_status']);
					$url .= "&pathyper=" . urlencode($Hyper_Cond);
					$url .= "&patdiabetes=" . urlencode($Diabetic_Cond);
					$url .= "&patloc=" . urlencode($getPatInfo[0]['patient_loc']);
					$url .= "&patState=" . urlencode($getPatInfo[0]['pat_state']);
					$url .= "&patCountry=" . urlencode($getPatInfo[0]['pat_country']);
					$url .= "&patcomp=" . urlencode($getPatInfo[0]['patient_complaint']);
					$url .= "&patdesc=" . urlencode($getPatInfo[0]['patient_desc']);
					$url .= "&patquery=" . urlencode($getPatInfo[0]['pat_query']);
					$url .= "&patqualification=" . urlencode($pat_qualification);
					$url .= "&patblood=" . urlencode($getPatInfo[0]['pat_blood']);
					$url .= "&patContactDet=". urlencode($patContactDet);
					$url .= "&patnoreportmsg=" . urlencode($noreportmsg);
					if(!empty($getPatAttach[0]['attach_id'])){
					$url .= "&patattachid1=" . urlencode($getPatAttach[0]['attach_id']);
					$url .= "&patattachname1=" . urlencode($getPatAttach[0]['attachments']);
					}
					if(!empty($getPatAttach[1]['attach_id'])){
					$url .= "&patattachid2=" . urlencode($getPatAttach[1]['attach_id']);
					$url .= "&patattachname2=" . urlencode($getPatAttach[1]['attachments']);
					}
					if(!empty($getPatAttach[2]['attach_id'])){
					$url .= "&patattachid3=" . urlencode($getPatAttach[2]['attach_id']);
					$url .= "&patattachname3=" . urlencode($getPatAttach[2]['attachments']);
					}
					if(!empty($getPatAttach[3]['attach_id'])){
					$url .= "&patattachid4=" . urlencode($getPatAttach[3]['attach_id']);
					$url .= "&patattachname4=" . urlencode($getPatAttach[3]['attachments']);
					}
					if(!empty($getPatAttach[4]['attach_id'])){
					$url .= "&patattachid5=" . urlencode($getPatAttach[4]['attach_id']);
					$url .= "&patattachname5=" . urlencode($getPatAttach[4]['attachments']);
					}
					if(!empty($getPatAttach[5]['attach_id'])){
					$url .= "&patattachid6=" . urlencode($getPatAttach[5]['attach_id']);
					$url .= "&patattachname6=" . urlencode($getPatAttach[5]['attachments']);
					}
					if(!empty($getPatAttach[6]['attach_id'])){
					$url .= "&patattachid7=" . urlencode($getPatAttach[6]['attach_id']);
					$url .= "&patattachname7=" . urlencode($getPatAttach[6]['attachments']);
					}
					if(!empty($getPatAttach[7]['attach_id'])){
					$url .= "&patattachid8=" . urlencode($getPatAttach[7]['attach_id']);
					$url .= "&patattachname8=" . urlencode($getPatAttach[7]['attachments']);
					}
					if(!empty($getPatAttach[8]['attach_id'])){
					$url .= "&patattachid9=" . urlencode($getPatAttach[8]['attach_id']);
					$url .= "&patattachname9=" . urlencode($getPatAttach[8]['attachments']);
					}
					if(!empty($getPatAttach[9]['attach_id'])){
					$url .= "&patattachid10=" . urlencode($getPatAttach[9]['attach_id']);
					$url .= "&patattachname10=" . urlencode($getPatAttach[9]['attachments']);
					}
					$url .= "&proname=" . urlencode($docname);					
					$url .= "&docmail=" . urlencode($docmail);					
					$url .= "&ccmail=" . urlencode($ccmail);
							
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&subject=" . urlencode($subject);					
							
					$ch = curl_init (); // setup a curl						
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
					$output = curl_exec ( $ch );				
					curl_close ( $ch );
}					
		
function send_msg($mobile,$mobile_msg){

	$msg="";

	$msg=urlencode($mobile_msg);
		
	$url="http://sms6.routesms.com:8080/bulksms/bulksms?username=medisense&password=medi2015&type=5&dlr=0&destination=".$mobile."&source=HCHKIN&message=".$msg;
		
	//$logger->write("INFO :","login with url".$url);

	$ch = curl_init();  // setup a curl
	curl_setopt($ch, CURLOPT_URL, $url);  // set url to send to
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // required as godaddy fails
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	$output=curl_exec($ch);
	//echo "output".$output;
	curl_close($ch);
	//return $output;
}
?>


