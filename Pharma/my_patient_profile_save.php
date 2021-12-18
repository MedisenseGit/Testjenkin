<?php ob_start();
	error_reporting(1);
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
	include('short_url.php');
	require_once("../classes/querymaker.class.php");
	require_once("../DigitalOceanSpaces/src/upload_function.php");
	//
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

	//Payemnt Link
	if(isset($_POST['sendPayment']))
	{
		$patientId = $_POST['patient_id'];
		$referred_id = $_POST['referred_id'];
		
		$arrFiedPay=array();
		$arrValuePay=array();
		
		$arrFiedPay[]='episode_id';
		$arrValuePay[]=$_POST['episode_id'];
		$arrFiedPay[]='type';
		$arrValuePay[]=$_POST['type'];
		$arrFiedPay[]='diagno_pharma_id';
		$arrValuePay[]=$_POST['diagno_pharma_id'];
		$arrFiedPay[]='patient_id';
		$arrValuePay[]=$_POST['patient_id'];
		$arrFiedPay[]='request_from';
		$arrValuePay[]=$_POST['request_from'];
		$arrFiedPay[]='referred_id';
		$arrValuePay[]=$_POST['referred_id'];
		$arrFiedPay[]='payment_amount';
		$arrValuePay[]=$_POST['paymentValue'];
		//$arrFiedPay[]='currency_code';
		//$arrValuePay[]=$_POST['currency_code'];
			
			$getDiagno= mysqlSelect("*","payment_diagno_pharma","referred_id='".$_POST['referred_id']."' and type='".$_POST['type']."' and request_from='".$_POST['request_from']."'");
		if(COUNT($getDiagno)>0){
			$insert_pay=mysqlUpdate('payment_diagno_pharma',$arrFiedPay,$arrValuePay,"referred_id='".$referred_id."' and type='".$_POST['type']."' and request_from='".$_POST['request_from']."'");
		
		}
		else{
			$insert_pay=mysqlInsert('payment_diagno_pharma',$arrFiedPay,$arrValuePay);
		}
		
		//if($_POST['type'] == '1'){
			$arrFiedPay1=array();
			$arrValuePay1=array();
			
			$arrFiedPay1[]='order_status';
			$arrValuePay1[]='2';
			
			$update_pay=mysqlUpdate('pharma_referrals',$arrFiedPay1,$arrValuePay1,"pr_id='".$referred_id."'");
			
			//$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'" ,"","","","");
			$diagno_referal = mysqlSelect("*","pharma_referrals","pr_id='".$_POST['referred_id']."'","","","","");
			 $getPatInfo = mysqlSelect("*","pharma_customer","pharma_customer_id='".$diagno_referal[0]['pharma_customer_id']."'","","","",""); 
			$link = HOST_MAIN_URL."Pharma/Patient-Profile-Payment?r=" . md5($_POST['referred_id'])."&t=".$_POST['type'];//HOST_MAIN_URL."premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
			$toEmail=$getPatInfo[0]['pharma_customer_email'];
		$mailSubject='Payment Link- Pharma';  
		$fromContent='Payment Link';
		$email='medical@medisense.me';
		$contentSection="Dear ".$getPatInfo[0]['pharma_customer_name'].",<br/> Please pay to get Prescription items. Please click the link to pay : ".$link." <br/>Thanks";
				
						
				$url_page = 'send_medical_tourism_email.php';
				$url = rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($email);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
		$response="success";	
		header("Location:Customer_Profile_Info?p=".md5($referred_id)."&response=".$response);	
	/*	}
		else if($_POST['type'] == '2'){
			$arrFiedPay1=array();
			$arrValuePay1=array();
			
			$arrFiedPay1[]='order_status';
			$arrValuePay1[]='2';
			
			$update_pay=mysqlUpdate('health_lab_test_request',$arrFiedPay1,$arrValuePay1,"id='".$referred_id."'");
			
			$getPatInfo = mysqlSelect("*","health_lab_test_request","id='".$referred_id."'" ,"","","","");
			$link = HOST_MAIN_URL."Diagnostic/Patient-Profile-Payment?r=" . md5($_POST['referred_id'])."&t=2";//HOST_MAIN_URL."premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
			$toEmail=$getPatInfo[0]['customer_email'];
		$mailSubject='Payment Link- Diagnostic';  
		$fromContent='Payment Link';
		$email='medical@medisense.me';
		$contentSection="Dear ".$getPatInfo[0]['customer_name'].",<br/> Diagnostic Centre has sent payment link for investigations conducted. Please click the link to pay : ".$link." <br/>Thanks";
				
						
				$url_page = 'send_medical_tourism_email.php';
				$url = rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($email);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
		$response="success";	
		header("Location:Patient_Profile_Info?p=".md5($referred_id)."&response=".$response);	
		}*/
	}
	
	
	if(isset($_GET['diagnoinvestid']))
	{
		
			$arrFiedInvest=array();
			$arrValueInvest=array();
			
			if(!empty($_GET['actualval'])){
			$arrFiedInvest[]='test_actual_value';
			$arrValueInvest[]=$_GET['actualval'];
			}
			
			$update_invest=mysqlUpdate('diagnostic_patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_GET['diagnoinvestid']."'");
	}

	
	//CREATE EPISODE
	if(isset($_POST['save_patient_edit']) || isset($_POST['save_patient_print'])){ //TO CHECK AUTHENTICATION OF POST VALUES
		//echo "<pre>"; print_r($_POST); exit;
		

			$patient_id = (int)$_POST['patient_id'];				
			//$episode_desc = $_POST['episode_desc'];
			//$medical_complaint =  $_POST['medical_complaint'];
			//$medical_examination =  $_POST['medical_examination'];
			//$txt_treatment =  $_POST['txt_treatment'];
			
				$arrFieldsPE = array();
				$arrValuesPE = array();
				$arrFieldsPE[] = 'pharma_customer_id';
				$arrValuesPE[] = $patient_id;
				$arrFieldsPE[] = 'pharma_id';
				$arrValuesPE[] = $admin_id;
				
				$arrFieldsPE[] = 'datetime';
				$arrValuesPE[] = $Cur_Date;

				$insert_patient_episodes=mysqlInsert('diagnostic_patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = $insert_patient; //Get episode_id
			
							
				//Insert 'diagnostic_referrals' table
			$arrFileds_referral= array();
			$arrValues_referral= array();
			//$arrFileds_referral[]='patient_id';
			//$arrValues_referral[]=$_GET['patientid'];
			//$arrFileds_referral[]='patient_type';
			//$arrValues_referral[]="1";
			//$arrFileds_referral[]='doc_id';
			//$arrValues_referral[]=$admin_id;
			//$arrFileds_referral[]='doc_type';
			//$arrValues_referral[]="1";
			//$arrFileds_referral[]='episode_id';
			//$arrValues_referral[]=$_GET['episodeid'];
			$arrFileds_referral[]='pharma_customer_id';
			$arrValues_referral[]=$patient_id;
			$arrFileds_referral[]='pharma_id';
			$arrValues_referral[]=$admin_id;
			$arrFileds_referral[]='status1';
			$arrValues_referral[]="1";
			$arrFileds_referral[]='status2';
			$arrValues_referral[]="1"; //1 for referred
			$arrFileds_referral[]='referred_date';
			$arrValues_referral[]=$Cur_Date;
			$arrFileds_referral[]='episode_id';
		    $arrValues_referral[]=$episode_id;
			
			$insert_temp_value=mysqlInsert('pharma_referrals',$arrFileds_referral,$arrValues_referral);
			
			
				//Update Investigation -' diagnostic_patient_temp_investigation' table
				$arrFieldsINVEST=array();
				$arrValuesINVEST=array();
				$arrFieldsINVEST[] = 'episode_id';
				$arrValuesINVEST[] = $episode_id;
				$arrFieldsINVEST[] = 'status';
				$arrValuesINVEST[] = "0";
				$update_icd=mysqlUpdate('diagnostic_patient_temp_investigation',$arrFieldsINVEST,$arrValuesINVEST,"diagnostic_customer_id = '".$patient_id."' and diagnostic_id='".$admin_id."' and status='1'");
				
						
				//Update Examination -' diagnostic_patient_examination_active' table
				$arrFieldsExam[] = 'episode_id';
				$arrValuesExam[] = $episode_id;
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=mysqlUpdate('diagnostic_patient_examination_active',$arrFieldsExam,$arrValuesExam,"pharma_customer_id = '".$patient_id."' and diagnostic_id='".$admin_id."' and status='1'");	
			
		/*	while(list($key_invest, $value_invest) = each($_POST['examination_id']))
			{
				$arrFiedInvest=array();
				$arrValueInvest=array();
				
				if(!empty($_POST['slctReslt'][$key_invest])){
				$arrFiedInvest[]='exam_result';
				$arrValueInvest[]=$_POST['slctReslt'][$key_invest];
				}
				if(!empty($_POST['findings'][$key_invest])){
				$arrFiedInvest[]='findings';
				$arrValueInvest[]=$_POST['findings'][$key_invest];
				}
				
				$update_invest=mysqlUpdate('diagnostic_patient_examination_active',$arrFiedInvest,$arrValueInvest, "examination_id = '".$_POST['examination_id'][$key_invest]."'");
			
			
			}
			
			while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
			{
				$arrFiedInvest=array();
				$arrValueInvest=array();
				
				if(!empty($_POST['actualVal'][$key_invest])){
				$arrFiedInvest[]='test_actual_value';
				$arrValueInvest[]=$_POST['actualVal'][$key_invest];
				}
				
				$update_invest=mysqlUpdate('diagnostic_patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
			
			
			}*/
			//reset($_POST['investigation_id']);			
			/*while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
				{
					$arrFiedInvest=array();
					$arrValueInvest=array();
					
					if(!empty($_POST['actualVal'][$key_invest])){
					$arrFiedInvest[]='test_actual_value';
					$arrValueInvest[]=$_POST['actualVal'][$key_invest];
					}
					
					$update_invest=mysqlUpdate('diagnosis_temp_investigation',$arrFiedInvest,$arrValueInvest, "diagno_invest_id = '".$_POST['investigation_id'][$key_invest]."'");
				
					
				
				}	*/
			
			
					//Save for Appointment Payment Transaction
					if(!empty($_POST['consult_charge']))
					{
						$arrFieldsPayment=array();	
						$arrValuesPayment=array();
						
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$_POST['patient_name'];
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$Cur_Date;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]="Diagnostic Charge";
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$_POST['consult_charge'];
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$admin_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]="3";
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]="PENDING";
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]="Cash";
						$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
					}
					//Save for Appointment Payment Transaction ends here
	
		$response="episode-created";
		//header("Location:All-Patient-Records?response=".$response);

		/**/
		//echo "redirecting"; exit;
		if(isset($_POST['save_patient_edit'])){
		header("Location:Customer_Profile_Info?p=".md5($patient_id)."&response=".$response);
		} else if(isset($_POST['save_patient_print']))
		{
		header("Location:print-emr?pid=".md5($patient_id)."&episode=".md5($episode_id));	
		}
		//header("Location:My-Patient-List?response=".$response);
	}
	
if(isset($_POST['updateInvestigation']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['actualVal'][$key_invest])){
		$arrFiedInvest[]='test_actual_value';
		$arrValueInvest[]=$_POST['actualVal'][$key_invest];
		}
		
		$update_invest=mysqlUpdate('diagnostic_patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
	
	
	}
	$response="update-investigation";
	header("Location:Customer_Profile_Info?p=".md5($_POST['patient_id'])."&response=".$response);
}


if(isset($_POST['updateExamination']))
{
	while(list($key_invest, $value_invest) = each($_POST['examination_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['slctReslt'][$key_invest])){
		$arrFiedInvest[]='exam_result';
		$arrValueInvest[]=$_POST['slctReslt'][$key_invest];
		}
		if(!empty($_POST['findings'][$key_invest])){
		$arrFiedInvest[]='findings';
		$arrValueInvest[]=$_POST['findings'][$key_invest];
		}
		
		$update_invest=mysqlUpdate('diagnostic_patient_examination_active',$arrFiedInvest,$arrValueInvest, "examination_id = '".$_POST['examination_id'][$key_invest]."'");
	
	
	}
	$response="update-examination";
	header("Location:Customer_Profile_Info?p=".md5($_POST['patient_id'])."&response=".$response);
}
//sms to patient
if(isset($_POST['smsPatient'])){
	$checkDiagnoCust= mysqlSelect("*","diagnostic_customer","diagnostic_customer_id='".$_POST['patient_id']."'");
	
	$link = "/Diagnostic/Visit-Detail?d=" . md5($_POST['patient_id']) ."&e=".md5($_POST['episode_id']);
	
	//$link = "https://medisensecrm.com/premium/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//Get Shorten Url
	$getUrl= get_shorturl($link);
	
	//SMS notification to Diagnostic center
	if(!empty($checkDiagnoCust[0]['diagnostic_customer_phone'])){
	$mobile = $checkDiagnoCust[0]['diagnostic_customer_phone'];
	$msg = "Hello ".$checkDiagnoCust[0]['diagnostic_customer_name'].", click here to view reports \n ".$getUrl." \nThank you";
	send_msg($mobile,$msg);
	}
	$response="message-sent";
	header("Location:Customer_Profile_Info?p=".md5($_POST['patient_id'])."&response=".$response);
	
}
//sms to doctor
if(isset($_POST['smsDoctor'])){
	$checkDiagnoCust= mysqlSelect("*","diagnostic_referrals","diagnostic_customer_id='".$_POST['patient_id']."' and episode_id='".$_POST['episode_id']."'");
	$checkReferal= mysqlSelect("*","referal","ref_id='".$checkDiagnoCust[0]['doc_id']."'");
	
	$link = "/Diagnostic/Visit-Detail?d=" . md5($_POST['patient_id']) ."&e=".md5($_POST['episode_id']);
	
	//$link = "https://medisensecrm.com/premium/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//Get Shorten Url
	$getUrl= get_shorturl($link);
	
	//SMS notification to Diagnostic center
	if(!empty($checkReferal[0]['contact_num'])){
	$mobile = $checkReferal[0]['contact_num'];
	$msg = "Hello ".$checkReferal[0]['ref_name'].", click here to view reports \n ".$getUrl." \nThank you";
	send_msg($mobile,$msg);
	}
	$response="message-sent";
	header("Location:Customer_Profile_Info?p=".md5($_POST['patient_id'])."&response=".$response);
	
}


if(isset($_POST['addAttachments'])){
	//Save patient episode attachments
				
						$errors= array();
						$timestring = time();
						/*if(!empty($_POST['upload_user'])){
						$uploadUser = $_POST['upload_user'];
						$userType = "2";
						}
						else
						{
						$uploadUser = $_POST['patient_id'];	
						$userType = "1";						
						}*/
						$patientId = $_POST['patient_id'];
						$uploaddirectory = realpath("../HealthReportsAttachment");
						
						foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
						{	
												
						$dotpos = strpos($file_name, '.');
						$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
						
						$file_name = $_FILES['file-5']['name'][$key];
						$file_size =$_FILES['file-5']['size'][$key];
						$file_tmp =$_FILES['file-5']['tmp_name'][$key];
						$file_type=$_FILES['file-5']['type'][$key];
						
						if(!empty($file_name)){
							$Photo1  = $file_name;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();
							
							$arrFields_Attach[]='episode_id';
							$arrValues_Attach[]=$_POST['episode_id'];
							
							$arrFields_Attach[]='type';
							$arrValues_Attach[]=$_POST['type'];
							
							$arrFields_Attach[]='diagno_pharma_id';
							$arrValues_Attach[]=$_POST['diagno_pharma_id'];
							
							$arrFields_Attach[]='patient_id';
							$arrValues_Attach[]=$_POST['patient_id'];
							
							$arrFields_Attach[]='request_from';
							$arrValues_Attach[]=$_POST['request_from'];
							
							$arrFields_Attach[]='referred_id';
							$arrValues_Attach[]=$_POST['referred_id'];
							
							$arrFields_Attach[] = 'image_name';
							$arrValues_Attach[] = $Photo1;							
							
							$arrFields_Attach[] = 'created_date';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht=mysqlInsert('attachment_diagno_pharma',$arrFields_Attach,$arrValues_Attach);
							$epiid= $bslist_pht;

							
							$folder_name	=	"HealthReportsAttachment";
							$sub_folder		=	$epiid;
							$filename		=	$_FILES['file-5']['name'][$key];
							$file_url		=	$_FILES['file-5']['tmp_name'][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
														
							/* Uploading image file */ 
								 // $uploaddir = $uploaddirectory . "/" . $epiid;
						
							// /*Checking whether folder with category id already exist or not. */
								// if (file_exists($uploaddir)) {
									//echo "The file $uploaddir exists";
									// } 
								// else {
									// $newdir = mkdir($uploaddirectory . "/" . $epiid , 0777);
									//$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
								// }
								 
								 // $uploadfile = $uploaddir . "/" . $Photo1;
								
								
								// /* Moving uploaded file from temporary folder to desired folder. */
								// if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								// } else {
									//echo "File cannot be uploaded";
								// }
								
							} //End file empty conditions
								
						}//End of foreach
	$response="reports-attached";
	
	//header("Location:Customer_Profile_Info?p=".md5($_POST['patient_id'])."&response=".$response);
	//if($_POST['type'] == '1'){
		
			$arrFiedPay1=array();
			$arrValuePay1=array();
			
			$arrFiedPay1[]='order_status';
			$arrValuePay1[]='4';
			
			$update_pay=mysqlUpdate('pharma_referrals',$arrFiedPay1,$arrValuePay1,"pr_id='".$_POST['referred_id']."'");
			
		header("Location:Customer_Profile_Info?p=".md5($_POST['referred_id'])."&response=".$response);	
		/*}
		else if($_POST['type'] == '2'){
			$arrFiedPay1=array();
			$arrValuePay1=array();
			
			$arrFiedPay1[]='order_status';
			$arrValuePay1[]='4';
			
			$update_pay=mysqlUpdate('health_lab_test_request',$arrFiedPay1,$arrValuePay1,"id='".$_POST['referred_id']."'");
				
		header("Location:Patient_Profile_Info?p=".md5($_POST['referred_id'])."&response=".$response);	
		}*/
	
}	



?>