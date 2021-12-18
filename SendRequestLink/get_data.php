<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
$TransId=time();
$ccmail="ambarish@medisense.me";

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

if(isset($_POST['ref_appointment'])){

	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	$txtName = $_POST['se_pat_name'];
	$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	$chkDate = date('Y-m-d',strtotime($Cur_Date));
	
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state1']);
	$docid = addslashes($_SESSION['docid']);
	$docspec = addslashes($_SESSION['docspec']);
	$transid=time();
	
			$arrFields_patient = array();
			$arrValues_patient = array();
			
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge;

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;

		
			/*pat_blood*/

			$arrFields_patient[] = 'contact_person';
			$arrValues_patient[] = $txtContact;

			/*profession*/

			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;

			$arrFields_patient[] = 'patient_loc';
			$arrValues_patient[] = $txtLoc;

			$arrFields_patient[] = 'pat_state';
			$arrValues_patient[] = $txtState;

			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $txtCountry;

			$arrFields_patient[] = 'patient_addrs';
			$arrValues_patient[] = $txtAddress;

			$arrFields_patient[] = 'partner_id';
			$arrValues_patient[] = $_SESSION['docid'];

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = $cur_Date;
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $Cur_Date;
		

		
		$patientcreate=$objQuery->mysqlInsert('my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = mysql_insert_id();  //Get Patient Id
		
		//Insert to new_hospvisitor_details table
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[] = 'appoint_trans_id';
				$arrValues[] = $transid;
				$arrFields[] = 'pref_doc';
				$arrValues[] = $docid;
				$arrFields[] = 'department';
				$arrValues[] = $docspec;
				$arrFields[] = 'Visiting_date';
				$arrValues[] = $chkInDate;
				$arrFields[] = 'Visiting_time';
				$arrValues[] = $chkInTime;
				$arrFields[] = 'patient_name';
				$arrValues[] = $txtName;
				$arrFields[] = 'Mobile_no';
				$arrValues[] = $txtMob;
				$arrFields[] = 'Email_address';
				$arrValues[] = $txtMail;
				$arrFields[] = 'pay_status';
				$arrValues[] = "Pending";
				$arrFields[] = 'visit_status';
				$arrValues[] = "new_visit";
				$arrFields[] = 'Time_stamp';
				$arrValues[] = $Cur_Date;
				$arrFields[] = 'pat_age';
				$arrValues[] = $txtAge;
				$arrFields[] = 'pat_gen';
				$arrValues[] = $txtGen;
				
		
				
				$createvisitor=$objQuery->mysqlInsert('partner_appointment_transaction',$arrFields,$arrValues);
				$newvisitorid= mysql_insert_id();
				$getPatInfo = $objQuery->mysqlSelect("*","my_patient","patient_id='".$patientid."'" ,"","","","");
		
				
	$get_pro = $objQuery->mysqlSelect('partner_id,cont_num1,Email_id,contact_person,partner_name,gcm_tokenid','our_partners',"partner_id='".$docid."'");
	$docmsg="Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense leap dash board or email . Thanks";
	$mobile = $get_pro[0]['cont_num1'];
	send_msg($mobile,$docmsg);
	
								
				//Push notification for partners		
				
				if($get_pro[0]['gcm_tokenid']!=""){				
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Appointment Request";
				$subtitle="New Appointment Request";
				$tickerText="Appointment Request";
				$type="4"; //others
				$patientid=$getPatInfo[0]['patient_id'];
				$postid="0";
				$docid=$get_pro[0]['partner_id'];
				$largeimg='large_icon';	
				$postkey="0";
				$smalimg="https://medisensecrm.com/assets/images/practice_push_icon.png";
				push_notification_refer($regid,$docmsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
				}
				
	
	$getTime=$objQuery->mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($get_pro[0]['Email_id'])){
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
					$url .= "&docname=" . urlencode($get_pro[0]['contact_person']);
					$url .= "&docmail=" . urlencode($get_pro[0]['Email_id']);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);	
		}
				

	
	$response="appointment";
	header("Location:RefLink?d=".$_POST['doc_ency_id']."&response=".$response."&curdate=".$Cur_Date."&visitdate=".$chkInDate."&visittime=".$getTime[0]['Timing']."&patid=".$getPatInfo[0]['patient_id']."&docname=".$get_pro[0]['contact_person']."&patname=".$getPatInfo[0]['patient_name']."&patcontact=".$getPatInfo[0]['patient_mob']."&patemail=".$getPatInfo[0]['patient_email']."&hospcontact=".$get_pro[0]['cont_num1']);

	
}

if(isset($_POST['appointment'])){
	$selHosp = $_POST['selHosp'];
	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	$txtName = $_POST['se_pat_name'];
	$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	$chkDate = date('Y-m-d',strtotime($Cur_Date));
	
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state1']);
	$docid = addslashes($_SESSION['docid']);
	$docspec = addslashes($_SESSION['docspec']);
	$transid=time();
	
			
			$arrFields_patient[] = 'patient_name';
			$arrValues_patient[] = $txtName;

			$arrFields_patient[] = 'patient_age';
			$arrValues_patient[] = $txtAge;

			$arrFields_patient[] = 'patient_email';
			$arrValues_patient[] = $txtMail;

			$arrFields_patient[] = 'patient_gen';
			$arrValues_patient[] = $txtGen;

			$arrFields_patient[] = 'contact_person';
			$arrValues_patient[] = $txtContact;

			$arrFields_patient[] = 'patient_mob';
			$arrValues_patient[] = $txtMob;

			$arrFields_patient[] = 'patient_loc';
			$arrValues_patient[] = $txtLoc;

			$arrFields_patient[] = 'pat_state';
			$arrValues_patient[] = $txtState;

			$arrFields_patient[] = 'pat_country';
			$arrValues_patient[] = $txtCountry;

			$arrFields_patient[] = 'patient_addrs';
			$arrValues_patient[] = $txtAddress;

			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $_SESSION['docid'];

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = $cur_Date;
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $Cur_Date;
		
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;

		
		$patientcreate=$objQuery->mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = mysql_insert_id();  //Get Patient Id
		
		//Insert to new_hospvisitor_details table
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
				$arrFields[] = 'pat_age';
				$arrValues[] = $txtAge;
				$arrFields[] = 'pat_gen';
				$arrValues[] = $txtGen;
				$arrFields[] = 'City';
				$arrValues[] = $txtLoc;
				$arrFields[] = 'State';
				$arrValues[] = $txtState;
				$arrFields[] = 'Country';
				$arrValues[] = $txtCountry;
				$arrFields[] = 'Address';
				$arrValues[] = $txtAddress;
		
				
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields,$arrValues);
				$newvisitorid= mysql_insert_id();
				$getPatInfo = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		
				
				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'appoint_trans_id';
				$arrValues1[] = $transid;
				$arrFields1[] = 'pref_doc';
				$arrValues1[] = $docid;
				$arrFields1[] = 'patient_id';
				$arrValues1[] = $patientid;
				$arrFields1[] = 'hosp_id';
				$arrValues1[] = $selHosp;
				$arrFields1[] = 'department';
				$arrValues1[] = $_SESSION['docspec'];
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
	$docmsg="Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense leap dash board or email . Thanks";
	$mobile = $get_pro[0]['contact_num'];
	send_msg($mobile,$docmsg);
	
				
				$msg = "Dear Doctor, ".$getPatInfo[0]['patient_name']."( Ph: ".$getPatInfo[0]['patient_mob']." )has expressed interest to meet you in person. For more info please login into your medisense leap dash board. Many Thanks";
							
				
				
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
		
	
	//Send SMS to patient
	//$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	//Get Shorten Url
	//$getUrl= get_shorturl($longurl);	
	$txtMob = $getPatInfo[0]['patient_mob'];
	
	$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
	send_msg($txtMob,$msg);
	
	$response="appointment";
	header("Location:index.php?response=".$response."&curdate=".$Cur_Date."&visitdate=".$chkInDate."&visittime=".$getTime[0]['Timing']."&patid=".$getPatInfo[0]['patient_id']."&docname=".$get_pro[0]['ref_name']."&patname=".$getPatInfo[0]['patient_name']."&patcontact=".$getPatInfo[0]['patient_mob']."&patemail=".$getPatInfo[0]['patient_email']."&hospcontact=".$get_pro[0]['hosp_contact']);

	
}

if(isset($_POST['second'])){
	$txtDate = $Cur_Date;
	$txtName = $_POST['se_pat_name'];
	$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	$chkDate = date('Y-m-d',strtotime($Cur_Date));
	$txtWeight = $_POST['se_weight'];
	$hyperCond = $_POST['se_hyper'];
	$diabetesCond = $_POST['se_diabets'];	
	$patDept = $_SESSION['docspec'];
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state']);
	
	$txtTreatDoc = addslashes($_POST['se_treat_doc']);
	$txtTreatHosp = addslashes($_POST['se_treat_hosp']);
	$patquery = addslashes($_POST['se_query']);
	
	$docid = addslashes($_SESSION['docid']);
	
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $txtDate;
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;
		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;
		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $hyperCond;
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $diabetesCond;
				
		$arrFields[] = 'contact_person';
		$arrValues[] = $txtContact;
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;
		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;
		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'patient_src';
		$arrValues[] = '8';
		
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatDoc;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatHosp;
		$arrFields[] = 'medDept';
		$arrValues[] = $patDept;		

		$arrFields[] = 'pat_query';
		$arrValues[] = $patquery;
		
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;		
			
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
		
		$arrFields[] = 'data_source';
		$arrValues[] = $_POST['client_src'];
		
		$usercraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
		$patientid = mysql_insert_id();
		
		//Add Patient Attachments functionality
		if($_FILES['file-3']['name']!=""){
								$errors= array();
							foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
							
							$file_name = $_FILES['file-3']['name'][$key];
							$file_size =$_FILES['file-3']['size'][$key];
							$file_tmp =$_FILES['file-3']['tmp_name'][$key];
							$file_type=$_FILES['file-3']['type'][$key];
							
							
								$Photo1  = $file_name;
								$arrFields1 = array();
								$arrValues1 = array();

								$arrFields1[] = 'patient_id';
								$arrValues1[] = $patientid;

								$arrFields1[] = 'attachments';
								$arrValues1[] = $file_name;
																	
								$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
								$id= mysql_insert_id();


								//Uploading image file 
									$uploaddirectory = realpath("../Attach");
									 $uploaddir = $uploaddirectory . "/" .$id;
									 $dotpos = strpos($fileName, '.');
									 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $id, $Photo1);
									 $uploadfile = $uploaddir . "/" . $Photo1;
									
								
									//Checking whether folder with category id already exist or not.
									if (file_exists($uploaddir)) {
										//echo "The file $uploaddir exists";
										} else {
										$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
									}
									
									// Moving uploaded file from temporary folder to desired folder.
									if(move_uploaded_file ($file_tmp, $uploadfile)) {
										
										$successAttach="";
									} else {
										//echo "File cannot be uploaded";
									}
								
									
							}
						//End of foreach
				}

		$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patientid."'" ,"","","","");
		$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$patientid."'" ,"","","","");
		$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
		$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
		$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$get_pro[0]['ref_id']."'","","","","");
			
		//Update Patient Status	
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'patient_id';
		$arrValues2[] = $patientid;
		$arrFields2[] = 'status1';
		$arrValues2[] = "1";
		$arrFields2[] = 'ref_id';
		$arrValues2[] = $get_pro[0]['ref_id'];
		$arrFields2[] = 'status2';
		$arrValues2[] = "2";
		$arrFields2[] = 'bucket_status';
		$arrValues2[] = "2";
		$arrFields2[] = 'timestamp';
		$arrValues2[] = $Cur_Date;
			
		
		$insertpatref=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
		$msg="Referred to ".$get_pro[0]['ref_name']." Successfully";
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[] = 'patient_id';
		$arrValues3[] = $patientid;		
		$arrFields3[] = 'status_id';
		$arrValues3[] = "2";
		$arrFields3[] = 'ref_id';
		$arrValues3[] = $get_pro[0]['ref_id'];
		$arrFields3[] = 'chat_note';
		$arrValues3[] = $msg;
		$arrFields3[] = 'TImestamp';
		$arrValues3[] = $Cur_Date;
				
		$insertchat=$objQuery->mysqlInsert('chat_notification',$arrFields3,$arrValues3);
		
					
	
						
				//Here It sends patient info to referred doctor	
				
					if(!empty($docid)){		
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
						
					
						$patContactDet= "Patient Contact Details: <br>Contact No. :".$getPatInfo[0]['patient_mob']."<br>Email Address :".$getPatInfo[0]['patient_email'];
						$chk_prior="PRIORITY";
					
					$subject=$chk_prior." ".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
									
										
					$url_page  = 'refdocmail.php';
					$url = rawurlencode($url_page);
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
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
					
					$url .= "&docmail=" . urlencode($docmail);
					
					$url .= "&ccmail=" . urlencode($ccmail);
							
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&subject=" . urlencode($subject);
					send_mail($url);
				}	
					
					
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					$arrFields4 = array();
					$arrValues4 = array();
					$arrFields4[]= 'Total_Referred';
					$arrValues4[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields4,$arrValues4,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
															
					//SMS notification to Refering Doctors only when messge_status is active
				if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your mail for further details - Many Thanks ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your mail for further details - Many Thanks ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					}
					
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!=""){
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query with Patient Id ". $getPatInfo[0]['patient_id'] . " has been successfully registered & referred to ".$get_pro[0]['ref_name']." Kindly check your mail for further details.- Many Thanks ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$responsemsg);
					
					} 
					
				//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") , Kindly check your dashboard for further details - Many Thanks ".$get_pro[0]['hosp_name'];
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Referral";
				$subtitle="New Referral";
				$tickerText="Medisense Leap";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				}	
					
					
	
	if($getPatInfo[0]['patient_email']!=""){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg="https://medisensecrm.com/Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg="https://medisensecrm.com/images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDocDept[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
												
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$actualLink.'/'.$get_pro[0]['ref_id'];
						
												
						$page_url = 'After_refer_pat_mail.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$paturl .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$paturl .= "&docimg=".urlencode($docimg);
						$paturl .= "&doclink=".urlencode($Link);
						$paturl .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$paturl .= "&patid=".urlencode($getPatInfo[0]['patient_id']);
						$paturl .= "&patname=".urlencode($getPatInfo[0]['patient_name']);					
						$paturl .= "&patmail=".urlencode($getPatInfo[0]['patient_email']);
						$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
					} 
	$response="opinion";
	header("Location:index.php?response=".$response."&curdate=".$Cur_Date."&patid=".$getPatInfo[0]['patient_id']."&patname=".$getPatInfo[0]['patient_name']."&patcontact=".$getPatInfo[0]['patient_mob']."&patemail=".$getPatInfo[0]['patient_email']);
}	

?>


