<?php ob_start();
 error_reporting(0);
 session_start(); 

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

$TransId=time();
//$ccmail="medical@medisense.me";

include('send_mail_function.php');
include('send_text_message.php');
include('push_notification_function.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");

ob_start();

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
//UPDATE PATIENT
if(isset($_POST['updatePatient'])){
	$txtName = addslashes($_POST['se_pat_name']);
	$txtMail = addslashes($_POST['se_email']);
	$txtAge = $_POST['se_pat_age'];
	$txtGen = $_POST['se_gender'];
	$txtContact = $_POST['se_con_per'];
	$txtMob = $_POST['se_phone_no'];
	$txtCountry = $_POST['se_country'];
	$txtState = $_POST['se_state'];
	$txtLoc = $_POST['se_city'];
	$txtAddress = addslashes($_POST['se_address']);
	$txtWeight = $_POST['se_weight'];	
	$hyperCond = $_POST['se_hyper'];
	$diabetesCond = $_POST['se_diabets'];
	$patDept = $_POST['se_depart'];
	$txtTreatDoc = addslashes($_POST['se_treat_doc']);
	$txtTreatHosp = addslashes($_POST['se_treat_hosp']);
	
	$txtNote1 = addslashes($_POST['se_info']);
	$txtNote2 = addslashes($_POST['se_description']);
	$txtNote3 = addslashes($_POST['se_query']);
	
	$arrFields = array();
	$arrValues = array();
		
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
	
		$arrFields[] = 'medDept';
		$arrValues[] = $patDept;		

		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtNote1;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtNote2;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtNote3;

	$updatePat=mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'");
	$response="update";
	header("Location:patient-history?p=".md5($_POST['patient_id'])."&response=".$response);	
}

//ADD PATIENT & REFER TO DOCTOR
if(isset($_POST['refer_patient'])){ //TO CHECK AUTHENTICATION OF POST VALUES
	$txtName = addslashes($_POST['se_pat_name']);
	$txtMail = addslashes($_POST['se_email']);
	$txtAge = $_POST['se_pat_age'];
	$txtGen = $_POST['se_gender'];
	$txtContact = $_POST['se_con_per'];
	$txtMob = $_POST['se_phone_no'];
	$txtCountry = $_POST['se_country'];
	$txtState = $_POST['se_state'];
	$txtLoc = $_POST['se_city'];
	$txtAddress = addslashes($_POST['se_address']);
	$txtWeight = $_POST['se_weight'];	
	$hyperCond = $_POST['se_hyper'];
	$diabetesCond = $_POST['se_diabets'];
	$patDept = $_POST['se_depart'];
	$txtTreatDoc = addslashes($_POST['se_treat_doc']);
	$txtTreatHosp = addslashes($_POST['se_treat_hosp']);
	
	$txtNote1 = addslashes($_POST['se_info']);
	$txtNote2 = addslashes($_POST['se_des']);
	$txtNote3 = addslashes($_POST['se_query']);
	$docid = addslashes($_POST['selectref']);
	$refpartner = addslashes($_POST['selectRefpartner']);
	
	//Get Source Id from Our Partner table
	$getSourceId= mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","a.partner_id='".$refpartner."'","","","","");
	//print_r($getSourceId);
	$PatientSource=$getSourceId[0]['source_id'];
	$arrFields = array();
	$arrValues = array();
		
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
		$arrValues[] = $PatientSource;		
	
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatDoc;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatHosp;
		$arrFields[] = 'medDept';
		$arrValues[] = $patDept;		

		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtNote1;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtNote2;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtNote3;
		$arrFields[] = 'assigned_to';
		$arrValues[] = '0';
		$arrFields[] = 'user_id';
		$arrValues[] = '9';
		$arrFields[] = 'company_id';
		$arrValues[] = $admin_id;
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
		
	
		$usercraete=mysqlInsert('patient_tab',$arrFields,$arrValues);
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
																	
								$bslist_pht=mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
								$id= $bslist_pht;
								
								$folder_name	=	"Attach";
								$sub_folder		=	$id;
								$filename		=	$_FILES['file-3']['name'][$key];
								$file_url		=	$_FILES['file-3']['tmp_name'][$key];
								fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

								//Uploading image file 
									// $uploaddirectory = realpath("../Attach");
									 // $uploaddir = $uploaddirectory . "/" .$id;
									 // $dotpos = strpos($fileName, '.');
									 // $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $id, $Photo1);
									 // $uploadfile = $uploaddir . "/" . $Photo1;
									
								
									//Checking whether folder with category id already exist or not.
									// if (file_exists($uploaddir)) {
										//echo "The file $uploaddir exists";
										// } else {
										// $newdir = mkdir($uploaddirectory . "/" . $id, 0777);
									// }
									
									//Moving uploaded file from temporary folder to desired folder.
									// if(move_uploaded_file ($file_tmp, $uploadfile)) {
										
										// $successAttach="";
									// } else {
										//echo "File cannot be uploaded";
									// }
								
									
							}
						//End of foreach
				}

		$getPatInfo = mysqlSelect("*","patient_tab","patient_id='".$patientid."'" ,"","","","");
		$getPatAttach= mysqlSelect("*","patient_attachment","patient_id='".$patientid."'" ,"","","","");
		$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
		$getDepartment = mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
		$getDocDept = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$docid."'","","","","");
			
		//Update Patient Status	
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'patient_id';
		$arrValues2[] = $patientid;
		$arrFields2[] = 'status1';
		$arrValues2[] = "1";
		$arrFields2[] = 'ref_id';
		$arrValues2[] = $docid;
		$arrFields2[] = 'status2';
		$arrValues2[] = "2";
		$arrFields2[] = 'bucket_status';
		$arrValues2[] = "2";
		$arrFields2[] = 'timestamp';
		$arrValues2[] = $Cur_Date;
			
		
		$insertpatref=mysqlInsert('patient_referal',$arrFields2,$arrValues2);
		$msg="Referred to ".$get_pro[0]['ref_name']." Successfully";
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[] = 'patient_id';
		$arrValues3[] = $patientid;		
		$arrFields3[] = 'status_id';
		$arrValues3[] = "2";
		$arrFields3[] = 'ref_id';
		$arrValues3[] = $docid;
		$arrFields3[] = 'chat_note';
		$arrValues3[] = $msg;
				
		$insertchat=mysqlInsert('chat_notification',$arrFields3,$arrValues3);
		
					
	
	/*if($getPatInfo[0]['patient_email']!=""){
		if($getPatInfo[0]['patient_src']=="11"){
		$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_mediassist.jpg";
		}
		else{
			$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_low1.jpg";
		}
		
					$url_page = 'afterregister_patientmail.php';
					$url = rawurlencode($url_page);
					$url .= "?patmail=".urlencode($getPatInfo[0]['patient_email']);
					$url .= "&patid=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=".urlencode($getPatInfo[0]['patient_name']);
					$url .= "&mailtemp=".urlencode($mailTemplate);
					send_mail($url);
					
		}	*/

					
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
					$updateCount=mysqlUpdate('referal',$arrFields4,$arrValues4,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
															
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your email for further details - Many Thanks".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your email for further details - Many Thanks".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					}
					
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!=""){
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query with Patient Id ". $getPatInfo[0]['patient_id'] . " has been successfully registered & referred to ".$get_pro[0]['ref_name']." Kindly check your email for further details.- Many Thanks ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$responsemsg);
					
					}
					
					//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, You have received a query of patient " . $getPatInfo[0]['patient_name']."(".$getPatInfo[0]['patient_id'].") - Many Thanks";
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Referral";
				$subtitle="New Referral";
				$tickerText="Leap new blog";
				$type="4"; //For Other Text message Type value is 4
				$largeimg="large_icon";
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
					
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$TransId);
				
				//End Push notification functionality
				}
	
				if(!empty($getPatInfo[0]['patient_email'])){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getDocDept[0]['spec_name']));
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
												
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$get_pro[0]['ref_id'];
						
												
						$url_page = 'After_refer_pat_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$url .= "&patid=".urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=".urlencode($getPatInfo[0]['patient_name']);					
						$url .= "&patmail=".urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=".urlencode($ccmail);		
						send_mail($url);
					}
	
	header("Location:Pending-Records");
}

?>


