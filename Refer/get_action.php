<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
$ccmail="ambarish@medisense.me";

//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$chkinUser = $objQuery->mysqlSelect("chk_userid","chckin_user","partner_id='".$admin_id."'","","","","");
	
	
//REFER TO PANELIST
if(isset($_POST['cmdRefer'])){
$txtRefId= $_POST['selectref1'];

if($txtRefId!=""){
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'patient_id';
	$arrValues1[]= $_POST['Pat_Id'];
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $txtRefId;
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	$arrFields1[]= 'status2';
	$arrValues1[]= "2";
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= "2";
	$arrFields1[]= 'timestamp';
	$arrValues1[]= $curDate;
	
	
	$chkreflist = $objQuery->mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		//$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
		header('Location:patient-history?p='.md5($_POST['Pat_Id']).'&response=referral_error');
	}else{
		
			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
		
		
						$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id=mysql_insert_id();
						$pat_id = $_POST['Pat_Id'];
						//$_SESSION['Ref_Id']=$txtRefId;
	
						
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
						
						if($get_pro[0]['communication_status']==1)
						{
							$docmail = $get_pro[0]['ref_mail'];
							$pro_contact=$get_pro[0]['contact_num'];
							
						} else if($get_pro[0]['communication_status']==2)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'];
							$pro_contact=$get_pro[0]['hosp_contact'];
						} else if($get_pro[0]['communication_status']==3)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'] . ', ';
							$docmail .= $get_pro[0]['ref_mail'];
							
							$pro_contact=$get_pro[0]['hosp_contact'];
							$doc_contact=$get_pro[0]['contact_num'];
						}
						
						if($getPatInfo[0]['transaction_status']=="TXN_SUCCESS"){
							$paid_msg="PAID QUERY- ";
						}
						
					if($_POST['contatInclude']==1){
						$patContactDet= "Patient Contact Details: <br>Contact No. :".$getPatInfo[0]['patient_mob']."<br>Email Address :".$getPatInfo[0]['patient_email'];
						$chk_prior="PRIORITY";
					}	
					$subject=$chk_prior." ".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
									
										
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
					
					$url .= "&curtreatdoc=" . urlencode($getPatInfo[0]['currentTreatDoc']);
					$url .= "&curtreathosp=" . urlencode($getPatInfo[0]['currentTreatHosp']);
					
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
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
					
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
					
					
					
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro[0]['ref_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;
					
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//Medisense Note
					$msg="Refered to ".$get_pro[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();
					$arrFields2[] = 'patient_id';
					$arrValues2[] = $getPatInfo[0]['patient_id'];
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;
					
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $curDate;
					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					}
					
					
					
			header('Location:patient-history?p='.md5($getPatInfo[0]['patient_id']).'&response=refer');		
				
		}
		
		
	
	}//End If loop
	
}//End If Loop
	

//BOOK AN APPOINTMENT
if(isset($_POST['bookAppointment'])){
	$docid=$_POST['selectref2'];
	$patid=$_POST['Pat_Id'];
	
	$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$_POST['selectTime']."'","","","","");
	$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'","","","","");
	$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
	
	$chkPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$patid."' and ref_id='".$docid."'","","","","");

	//To check whether Doctor belongs Medisense Panel or Hospital 
	if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
		$docnum=$getDocDet[0]['contact_num'];
		
		$docmail .= $getDocDet[0]['ref_mail'];
		
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
	
	
	} else
	{
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
				$arrValues_app1[] = $_POST['department'];
				
				
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
		
		
	header('Location:patient-history?p='.md5($chkPatDet[0]['patient_id']).'&response=book-appointment');
	
	
}

?>