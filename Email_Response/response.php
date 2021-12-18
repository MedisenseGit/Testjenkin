<?php ob_start();
 error_reporting(0);
 session_start();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");
include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
$objQuery = new CLSQueryMaker();
ob_start();
$ccmail="medical@medisense.me";

function hyphenize($string) {
	
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s+]+#', '#[^A-Za-z0-9\. -]+#', '/\@^|(\.+)/'),
            array('-',''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}

$chkEvntStatus = $objQuery->mysqlSelect("*","patient_email_event","eventtype='".$_GET['eventtype']."' and patient_id='".$_GET['patid']."' and random_id='".$_GET['randid']."'","","","","");
$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_GET['patid']."'","","","","");
$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$_GET['docid']."'");
$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_GET['docid']."'","","","","");
$chkPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_GET['patid']."'","","","","");

//Need oponion from another expert section
if($chkEvntStatus==false && $_GET['eventtype']==5)
{

	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'eventtype';
	$arrValues[]= $_GET['eventtype'];
	$arrFields[]= 'patient_id';
	$arrValues[]= $_GET['patid'];
	$arrFields[]= 'random_id';
	$arrValues[]= $_GET['randid'];
	$arrFields[]= 'TImestamp';
	$arrValues[]= $Cur_Date;
	
	$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields,$arrValues);
	//Medisense Panel notification
		
					
                    $url_page = 'opinion_from_another_doc.php';
					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patid=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patmobile=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patmail=".urlencode($chkPatDet[0]['patient_email']);
																						
					send_mail($url);
	
	header('Location:Respone-note?response=5');
}	
if($chkEvntStatus==true && $_GET['eventtype']==5) //SEND ERROR NOTE
	{
	header('Location:Respone-note?response=55');
	}


//To check whether Doctor belongs Medisense Panel or Hospital 
	if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
		$docnum=$getDocDet[0]['contact_num'];
		
		$docmail .= $getDocDet[0]['ref_mail'] . ', ';
		$docmail .= $getDocDet[0]['ref_mail1'] . ', ';
		$docmail .= $getDocDet[0]['ref_mail2'];
		
	}else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
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
	
if($chkEvntStatus==false && $_GET['eventtype']==4) //DIRECT MEET THE DOCTOR
{

	$_SESSION['docid']=$_GET['docid'];
	$_SESSION['patid']=$_GET['patid'];
	$_SESSION['eventtype']=$_GET['eventtype'];
	$_SESSION['randid']=$_GET['randid'];
	header('Location:Confirm-Appointment');
}	
if($chkEvntStatus==true && $_GET['eventtype']==4) //SEND ERROR NOTE
	{
	header('Location:Respone-note?response=44');
	}	
	

if($chkEvntStatus==false && $_GET['eventtype']==3) //THANK TO DOCTOR
{
	
		
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'eventtype';
	$arrValues[]= $_GET['eventtype'];
	$arrFields[]= 'patient_id';
	$arrValues[]= $_GET['patid'];
	$arrFields[]= 'random_id';
	$arrValues[]= $_GET['randid'];
	$arrFields[]= 'TImestamp';
	$arrValues[]= $Cur_Date;
	
	$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields,$arrValues);
	
	$mednote="Patient appreciated to ".$getDocDet[0]['ref_name']."-".$Cur_Date; //MEDISENSE NOTE
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_GET['patid'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = $_GET['docid'];
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $mednote;
	$arrFields2[] = 'user_id';
	$arrValues2[] = '10';
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
				
	$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	
		
	//EMAIL notification to Doctor
		if(!empty($docmail)){
		
					$url_page = 'Doc_Thanks_mail.php';
					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getDocDet[0]['ref_name']);
					$url .= "&patname=" . urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patid=" . urlencode($chkPatDet[0]['patient_id']);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);
					
		}	


		
					//SMS notification to Refering Doctors
                                        $msg = "Dear Sir, ".$chkPatDet[0]['patient_name']." ( ".$chkPatDet[0]['patient_mob']." )has expressed his gratitude for the help offered by you. Pls check your mail. Many Thanks";
					if(!empty($docnum)){
					send_msg($docnum,$msg);
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$msg);
					}
	header('Location:Respone-note?response=3');
}
if($chkEvntStatus==true && $_GET['eventtype']==3) //SEND ERROR NOTE
{
	header('Location:Respone-note?response=33');
}

//TALK TO THE DOCTOR
if($chkEvntStatus==false && $_GET['eventtype']==1) 
{
	
	
	//If Chosen doctor is listed in "Talk to the Panelist" then it will goes to payment gateway
	if($getDocDet[0]['tele_op']=="1")
	{
		$pay_service="Tele Opinion";
		$pay_op_cost="500.00";
		$trans_id=time();
		
		$arrFields_trans=array();
			$arrValues_trans=array();
			
			$arrFields_trans[] = 'Payment_id';
			$arrValues_trans[] = "Null";
			
			$arrFields_trans[] = 'transaction_id';
			$arrValues_trans[] = $trans_id;
                        
                        $arrFields_trans[] = 'patient_id';
			$arrValues_trans[] = $chkPatDet[0]['patient_id'];
			
			$arrFields_trans[] = 'patient_name';
			$arrValues_trans[] = $chkPatDet[0]['patient_name'];
			
			$arrFields_trans[] = 'service_type';
			$arrValues_trans[] = $pay_service;
			
			$arrFields_trans[] = 'ref_id';
			$arrValues_trans[] = $_GET['docid'];
			
			$arrFields_trans[] = 'email_id';
			$arrValues_trans[] = $chkPatDet[0]['patient_email'];
					
			$arrFields_trans[] = 'mobile_no';
			$arrValues_trans[] = $chkPatDet[0]['patient_mob'];
			
			$arrFields_trans[] = 'amount';
			$arrValues_trans[] = $pay_op_cost;
			
			$arrFields_trans[] = 'transaction_time';
			$arrValues_trans[] = $Cur_Date;
			
			$transcreate=$objQuery->mysqlInsert('customer_transaction',$arrFields_trans,$arrValues_trans);
			$cust_id = mysql_insert_id();
			
			$_SESSION['docid']=$_GET['docid'];
			$_SESSION['patid']=$chkPatDet[0]['patient_id'];
			$_SESSION['cust_id']=$cust_id;
			$_SESSION['our_transaction_id']=$trans_id;
			$_SESSION['total_amount']=$pay_op_cost;
			$_SESSION['email_address']=$chkPatDet[0]['patient_email'];
			$_SESSION['mobile_number']=$chkPatDet[0]['patient_mob'];
			header('Location:tele_opinion_confirmation.php');
		
	}
	else{	
	
			$_SESSION['docid']=$_GET['docid'];
			$_SESSION['patid']=$chkPatDet[0]['patient_id'];
			$_SESSION['eventtype']=$_GET['eventtype'];
			$_SESSION['randid']=$_GET['randid'];
			header('Location:confirm_tele_op.php');
		
	}
	
}
if($chkEvntStatus==true && $_GET['eventtype']==1) //SEND ERROR NOTE
{
	header('Location:Respone-note?response=11');
}

if($chkEvntStatus==false && $_GET['eventtype']==2) //To meet the doctor 
{
	
	//WHEN USER HAS PRESS "MEER THIS DOCTOR" DOCTOR STATUS WILL MAKE IT "OP-CONVERTED"
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'eventtype';
	$arrValues[]= $_GET['eventtype'];
	$arrFields[]= 'patient_id';
	$arrValues[]= $_GET['patid'];
	$arrFields[]= 'random_id';
	$arrValues[]= $_GET['randid'];
	$arrFields[]= 'TImestamp';
	$arrValues[]= $Cur_Date;
	
	$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields,$arrValues);
	
	$arrFields1 = array();
	$arrValues1 = array();
	
	$arrFields1[]= 'status1';
	$arrValues1[]= '1';
	$arrFields1[]= 'status2';
	$arrValues1[]= '8';
	
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_GET['patid']."'and ref_id='".$_GET['docid']."'");
	
	$arrFields3 = array();
	$arrValues3 = array();
	$arrFields3[]= 'bucket_status';
	$arrValues3[]= '8';
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields3,$arrValues3,"patient_id='".$_GET['patid']."'");
	
	
	$mednote=$chkPatDet[0]['patient_name']." want to take an appointment from ".$getDocDet[0]['ref_name']." -".$Cur_Date; //MEDISENSE NOTE
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_GET['patid'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = $_GET['docid'];
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $mednote;
	$arrFields2[] = 'user_id';
	$arrValues2[] = '10';
	$arrFields2[] = 'status_id';
	$arrValues2[] = '8';
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
				
	$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($docmail)){
		$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
					
                                        $url_page = 'Doc_pat_info.php';					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
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
					
		$getDocName=urlencode(str_replace(' ','-',$getDocDet[0]['ref_name']));
		$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
		$getDocCity=urlencode(str_replace(' ','-',$getDocDet[0]['ref_address']));
		$getDocState=urlencode(str_replace(' ','-',$getDocDet[0]['doc_state']));
		$getDocHosp=urlencode(str_replace(' ','-',$getDocDet[0]['hosp_name']));
		$getDocHospAdd=urlencode(str_replace(' ','-',$getDocDet[0]['hosp_addrs']));
		
		$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
		$actualLink=hyphenize($Getlink);
		$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$actualLink.'/'.$getDocDet[0]['ref_id'];
		
		
	header('Location:'.$Link);
}
if($chkEvntStatus==true && $_GET['eventtype']==2) //SEND ERROR NOTE
{
	header('Location:Respone-note?response=22');
}

ob_flush(); 
?>