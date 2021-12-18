<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");


$admin_id = $_SESSION['user_id'];
//$ccmail="medical@medisense.me";

//Random Password Generator
function randomPassword() 
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) 
	{
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
//Random Password Generator
function randomOtp() 
{
    $alphabet = "0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 4; $i++) 
	{
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function createKey()
{
	//create a random key
	$strKey = md5(microtime());
	//check to make sure this key isnt already in use
	$resCheck = mysql_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
	$arrCheck = mysql_fetch_assoc($resCheck);
	if($arrCheck['count(*)'])
	{
		//key already in use
		return createKey();
	}
	else
	{
		//key is OK
		return $strKey;
	}
}

function hyphenize($string) 
{
	
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
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
//$hostname="http://beta.referralio.com"; //For Beta version
$hostname="https://medisensecrm.com"; //For Prod version
//Image Compress functionality
$name = ''; $type = ''; $size = ''; $error = '';
	function compress_image($source_url, $destination_url, $quality) 
	{

		$info = getimagesize($source_url);

		if ($info['mime'] == 'image/jpeg')
		$image = imagecreatefromjpeg($source_url);

		elseif ($info['mime'] == 'image/gif')
		$image = imagecreatefromgif($source_url);

		elseif ($info['mime'] == 'image/png')
		$image = imagecreatefrompng($source_url);

		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}

	//update invest value in diagnostic refer
if(isset($_POST['updatePharmaPrescribe']))
{
	
	$getDocDetails= mysqlSelect("ref_name,contact_num","referal","ref_id='".$_POST['doc_id']."'","","","","");
	$getDiagnoDetails= mysqlSelect("pharma_name","pharma","pharma_id='".$_POST['pharma_id']."'","","","","");
	

	$errors= array();
	$timestring = time();
	$patientId = $_POST['patient_id'];
	$uploaddirectory = realpath("../premium/patientAttachments");
	$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
	
	/*Checking whether folder with category id already exist or not. */
	if (file_exists($uploaddir)) 
	{
		//echo "The file $uploaddir exists";
	} 
	else
	{
		$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
		$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
	}
	foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
	{	
												
						
		$file_name = $_FILES['file-5']['name'][$key];
		$file_size =$_FILES['file-5']['size'][$key];
		$file_tmp =$_FILES['file-5']['tmp_name'][$key];
		$file_type=$_FILES['file-5']['type'][$key];
		
		if(!empty($file_name))
		{
			$Photo1  = $file_name;
			$arrFields_Attach = array();
			$arrValues_Attach  = array();

			$arrFields_Attach[] = 'patient_id';
			$arrValues_Attach[] = $patientId;

			$arrFields_Attach[] = 'report_folder';
			$arrValues_Attach[] = $timestring;
			
			$arrFields_Attach[] = 'attachments';
			$arrValues_Attach[] = $file_name;
			
			$arrFields_Attach[] = 'user_id';
			$arrValues_Attach[] = $_POST['pharma_id'];
			
			$arrFields_Attach[] = 'user_type';
			$arrValues_Attach[] = "4"; //Pharma User
			
			$arrFields_Attach[] = 'date_added';
			$arrValues_Attach[] = $Cur_Date;
							
									
			$bslist_pht	=	mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
			$epiid		= 	$bslist_pht;
			
			$folder_name	=	"premium/patientAttachments";
			$sub_folder		=	$patientId . "/" .$timestring;
			$filename		=	$_FILES['file-5']['name'][$key];
			$file_url		=	$_FILES['file-5']['tmp_name'][$key];;
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

			/* Uploading image file */ 
				 
				 // $dotpos = strpos($fileName, '.');
				 // $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
				 // $uploadfile = $uploaddir . "/" . $Photo1;
				
				
				// /* Moving uploaded file from temporary folder to desired folder. */
				// if(move_uploaded_file ($file_tmp, $uploadfile)) {
					//echo "File uploaded.";
				// } else {
					//echo "File cannot be uploaded";
				// }
				
		} //End file empty conditions
				
		}//End of foreach
				
				//Update Patient diagnostic Status				
				$arrFieldStatus[]='status2';
				$arrValueStatus[]="2";
				$update_status=mysqlUpdate('pharma_referrals',$arrFieldStatus,$arrValueStatus,"pharma_id='".$_POST['pharma_id']."' and patient_id = '".$_POST['patient_id']."' and episode_id='".$_POST['episode_id']."'");
		
				//Send SMS to Doctor
				/*$DocContatct = $getDocDetails[0]['contact_num'];
				$msg= "Dear ".$getDocDetails[0]['ref_name'].", ".$getDiagnoDetails[0]['diagnosis_name']." has updated the reports of ".$_POST['patient_name']." dated ".$_POST['refer_date'].". Kindly login to your practice account to view the reports. Thanks";
				send_msg($DocContatct,$msg);	*/	
				
				$chatMessage=$getDiagnoDetails[0]['pharma_name']." has sent prescription invoice of ".$_POST['patient_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $_POST['company_id'];
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "7";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "2";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['pharma_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'note';
				$arrValuesChat[] = $_POST['txtDesc'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			$getDiagno= mysqlSelect("*","pharma","pharma_id='".$_POST['pharma_id']."'");
			$getComp= mysqlSelect("*","compny_tab","company_id='".$_POST['company_id']."'");
			
				 $toEmail=$getComp[0]['email_id'];
		$mailSubject='Pharmacy -'.$getDiagno[0]['pharma_name'].' has updated the prescription invoice of '.$_POST['patient_name'];  
		$fromContent='Medisense';
		$contentSection='Hi '.$getComp[0]['company_name'].',<br/> '.$getDiagno[0]['pharma_name'].' has updated the prescription invoice of '.$_POST['patient_name'].'. <br/><br/> Many Thanks';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getDiagno[0]['pharma_email']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	$response="update-invoice";
	header("Location:Pharma-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}

		//order prescription 
if(isset($_POST['orderPharmaPrescription']))
{
	
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'patient_id';
		$arrValues[] = $_POST['patient_id'];	
		$arrFields[] = 'episode_id';
		$arrValues[] = $_POST['episode_id'];
		$arrFields[] = 'company_id';
		$arrValues[] = $admin_id;
		$arrFields[] = 'delivery_address';
		$arrValues[] = $_POST['delAddress'];		
		$arrFields[] = 'type';
		$arrValues[] = '2';
		$arrFields[] = 'refer_id';
		$arrValues[] = $_POST['pharma_id'];
		$arrFields[] = 'created_date';
		$arrValues[] = $curDate;
				
				$insert_emr=mysqlInsert('emr_referred_orderDetail',$arrFields,$arrValues);
				
			$chatMessage="Delivery Address is updated and sent to Pharmacy successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $admin_id;
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "6";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "2";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['pharma_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			//$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'");
			
			$getPatient	= mysqlSelect("*","patients_appointment","patient_id='".$_POST['patient_id']."'");
			
			
			$getDiagno	= mysqlSelect("*","pharma","pharma_id='".$_POST['pharma_id']."'");
			$getComp	= mysqlSelect("*","compny_tab","company_id='".$admin_id."'");
			
				 $toEmail=$getDiagno[0]['pharma_email'];
		$mailSubject='Referral from '.$getComp[0]['company_name'].' Patient '.$getPatient[0]['patient_name'].' ( '.$getPatient[0]['patient_id'].' )';  
		$fromContent='Medisense';
		$contentSection='Dear '.$getDiagno[0]['pharma_name'].',<br/> Prescription needs to be delivered to '.$_POST['delAddress'].'. <br/>  Please visit the below link to view prescriptions and upload the invoice. <br/> <strong>Link: </strong>'.$_POST['diagno_url'].'<br/><br/> Many Thanks, <br/>Medisense';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getComp[0]['email_id']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	//$response="update-price";
	header("Location:Patient-Detail?p=".md5($getPatient[0]['patient_id'])."&e=".md5($_POST['episode_id']));
}

		//update PRICE value in pharma refer
if(isset($_POST['updatePharmaPrice']))
{
	while(list($key_invest, $value_invest) = each($_POST['prescription_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['priceVal'][$key_invest])){
		$arrFiedInvest[]='prescription_priceValue';
		$arrValueInvest[]=$_POST['priceVal'][$key_invest];
		}
		
		$update_invest=mysqlUpdate('doc_patient_episode_prescriptions',$arrFiedInvest,$arrValueInvest, "episode_prescription_id = '".$_POST['prescription_id'][$key_invest]."'");
	
	}
				$getDocDetails= mysqlSelect("ref_name,contact_num","referal","ref_id='".$_POST['doc_id']."'","","","","");
				$getDiagnoDetails= mysqlSelect("pharma_name","pharma","pharma_id='".$_POST['pharma_id']."'","","","","");
				//$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'");
				$getPatient= mysqlSelect("*","patients_appointment","patient_id='".$_POST['patient_id']."'");
				
				
				
			$chatMessage="Payment Link sent to ".$getPatient[0]['patient_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $_POST['company_id'];
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "4";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "2";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['pharma_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			$getDiagno= mysqlSelect("*","pharma","pharma_id='".$_POST['pharma_id']."'");
			$getComp= mysqlSelect("*","compny_tab","company_id='".$_POST['company_id']."'");
			
				 $toEmail=$getComp[0]['email_id'].','.$getPatient[0]['patient_email'];
		$mailSubject='Pharmacy -'.$getDiagno[0]['pharma_name'].' sent you a payment link ';  
		$fromContent='Medisense';
		$contentSection='Hi '.$getPatient[0]['patient_name'].',<br/> Please click the below link, to view the price of the prescriptions referred and to make payment .<br/> Link: '.$_POST['diagno_url'].'<br/><br/> Many Thanks, <br/>Medisense';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getDiagno[0]['pharma_email']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	$response="update-price";
	header("Location:Pharma-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}

	//order test 
if(isset($_POST['orderDiagnoTest']))
{
	
		$arrFields = array();
		$arrValues = array();
		$arrFields[] = 'patient_id';
		$arrValues[] = $_POST['patient_id'];	
		$arrFields[] = 'episode_id';
		$arrValues[] = $_POST['episode_id'];
		$arrFields[] = 'company_id';
		$arrValues[] = $admin_id;
		$arrFields[] = 'date';
		$arrValues[] = date('Y-m-d',strtotime($_POST['dateadded2']));
		$arrFields[] = 'time';
		$arrValues[] = date('H:i:s',strtotime($_POST['dateadded2']));
		$arrFields[] = 'type';
		$arrValues[] = '1';
		$arrFields[] = 'refer_id';
		$arrValues[] = $_POST['diagno_id'];
		$arrFields[] = 'created_date';
		$arrValues[] = $curDate;
				
				$insert_emr=mysqlInsert('emr_referred_orderDetail',$arrFields,$arrValues);
				
			$chatMessage="Scheduled Date and Time is updated and sent to diagnostic centre successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $admin_id;
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "6";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "1";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['diagno_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			//$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'");
			$getPatient	= mysqlSelect("*","patients_appointment","patient_id='".$_POST['patient_id']."'");
			
			$getDiagno	= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'");
			$getComp	= mysqlSelect("*","compny_tab","company_id='".$admin_id."'");
			
				 $toEmail=$getDiagno[0]['diagnosis_email'];
		$mailSubject='Referral from '.$getComp[0]['company_name'].' Patient '.$getPatient[0]['patient_name'].' ( '.$getPatient[0]['patient_id'].' )';  
		$fromContent='Medisense';
		$contentSection='Dear '.$getDiagno[0]['diagnosis_name'].',<br/> Diagnosis is scheduled on '.date('Y-m-d',strtotime($_POST['dateadded2'])).' at '.date('H:i:s',strtotime($_POST['dateadded2'])).'. <br/>  Please visit the below link to view tests and upload the reports. <br/> <strong>Link: </strong>'.$_POST['diagno_url'].'<br/><br/> Many Thanks, <br/>Medisense';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getComp[0]['email_id']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	//$response="update-price";
	header("Location:Patient-Detail?p=".md5($getPatient[0]['patient_id'])."&e=".md5($_POST['episode_id']));
}

	//update PRICE value in diagnostic refer
if(isset($_POST['updateDiagnoPrice']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['priceVal'][$key_invest])){
		$arrFiedInvest[]='test_price_val';
		$arrValueInvest[]=$_POST['priceVal'][$key_invest];
		}
		
		$update_invest=mysqlUpdate('patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
	
	}
				$getInvestDetails= mysqlSelect("*","patient_temp_investigation","pti_id = '".$_POST['investigation_id']."'","","","","");
				$getDocDetails= mysqlSelect("ref_name,contact_num","referal","ref_id='".$_POST['doc_id']."'","","","","");
				$getDiagnoDetails= mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'","","","","");
				//$getPatient= mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'");
				
				$getPatient= mysqlSelect("*","patients_appointment","patient_id='".$_POST['patient_id']."'");
				
				
				
			$chatMessage="Payment Link sent to ".$getPatient[0]['patient_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $_POST['company_id'];
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "4";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "1";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['diagno_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'");
			$getComp= mysqlSelect("*","compny_tab","company_id='".$_POST['company_id']."'");
			
				 $toEmail=$getComp[0]['email_id'].','.$getPatient[0]['patient_email'];
		$mailSubject='Diagnosis -'.$getDiagno[0]['diagnosis_name'].' sent you a payment link ';  
		$fromContent='Medisense';
		$contentSection='Hi '.$getPatient[0]['patient_name'].',<br/> Please click the below link, to view the price of the diagnosis tests referred and to make payment .<br/> Link: '.$_POST['diagno_url'].'<br/><br/> Many Thanks, <br/>Medisense';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getDiagno[0]['diagnosis_email']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	$response="update-price";
	header("Location:Diagnostic-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}

//update invest value in diagnostic refer
if(isset($_POST['updateDiagnoInvestigation']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();
		
		if(!empty($_POST['actualVal'][$key_invest])){
		$arrFiedInvest[]='test_actual_value';
		$arrValueInvest[]=$_POST['actualVal'][$key_invest];
		}
		
		$update_invest=mysqlUpdate('patient_temp_investigation',$arrFiedInvest,$arrValueInvest, "pti_id = '".$_POST['investigation_id'][$key_invest]."'");
	
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		if($_POST['main_test_id'][$key_invest]=="GLU009")  //BLOOD GLUCOSE (Post Prandial)
		{
			$arrFieldTrend[]='bp_afterfood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU017") //BLOOD GLUCOSE (Fasting)
		{
			$arrFieldTrend[]='bp_beforefood_count';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO001") //HDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='HDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="LDL") //LDL CHOLESTEROL
		{
			
			$arrFieldTrend[]='LDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHOL/HDL") //VLDL
		{
			
			$arrFieldTrend[]='VLDL';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="TRI001")   //TRIGLYCERIDES
		{
			
			$arrFieldTrend[]='triglyceride';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="GLU006")  //Glyco Hb (HbA1c)
		{
			
			$arrFieldTrend[]='HbA1c';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="CHO002")  //TOTAL CHOLESTEROL
		{
			
			$arrFieldTrend[]='cholesterol';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		else if($_POST['main_test_id'][$key_invest]=="URI012") //URINE SUGAR
		{
			
			$arrFieldTrend[]='urine_sugar';
			$arrValueTrend[]=$_POST['actualVal'][$key_invest];
		}
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=$cur_Date;
		$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$_POST['patient_id'][$key_invest];
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$_POST['patient_id'][$key_invest]."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend=mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$cur_Date."' and patient_id = '".$_POST['patient_id'][$key_invest]."' and patient_type='1'");
		}
		else
		{
		$insert_trend_analysis= mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
	}
				$getInvestDetails= mysqlSelect("*","patient_temp_investigation","pti_id = '".$_POST['investigation_id']."'","","","","");
				$getDocDetails= mysqlSelect("ref_name,contact_num","referal","ref_id='".$_POST['doc_id']."'","","","","");
				$getDiagnoDetails= mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'","","","","");
				
	
						$errors= array();
						$timestring = time();
						$patientId = $_POST['patient_id'];
						$uploaddirectory = realpath("../premium/patientAttachments");
						$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
						
							/*Checking whether folder with category id already exist or not. */
								if (file_exists($uploaddir)) {
									//echo "The file $uploaddir exists";
									} 
								else {
									$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
									$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
								}
						foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
						{	
												
						
						$file_name = $_FILES['file-5']['name'][$key];
						$file_size = $_FILES['file-5']['size'][$key];
						$file_tmp  = $_FILES['file-5']['tmp_name'][$key];
						$file_type = $_FILES['file-5']['type'][$key];
						
						if(!empty($file_name)){
							$Photo1  = $file_name;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							$arrFields_Attach[] = 'patient_id';
							$arrValues_Attach[] = $patientId;

							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = $timestring;
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $file_name;
							
							$arrFields_Attach[] = 'user_id';
							$arrValues_Attach[] = $_POST['diagno_id'];
							
							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = "3"; //Diagnosis User
							
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht=mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid= $bslist_pht;
							
							$folder_name	=	"premium/patientAttachments";
							$sub_folder		=	$patientId . "/" .$timestring;
							$filename		=	$_FILES['file-5']['name'][$key];
							$file_url		=	$_FILES['file-5']['tmp_name'][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

							/* Uploading image file */ 
								 
								 // $dotpos = strpos($fileName, '.');
								 // $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
								 // $uploadfile = $uploaddir . "/" . $Photo1;
								
								
								// /* Moving uploaded file from temporary folder to desired folder. */
								// if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								// } else {
									//echo "File cannot be uploaded";
								// }
								
							} //End file empty conditions
								
						}//End of foreach
				
				//Update Patient diagnostic Status				
				$arrFieldStatus[]='status2';
				$arrValueStatus[]="2";
				$update_status=mysqlUpdate('diagnostic_referrals',$arrFieldStatus,$arrValueStatus,"diagnostic_id='".$_POST['diagno_id']."' and patient_id = '".$getInvestDetails[0]['patient_id']."' and episode_id='".$getInvestDetails[0]['episode_id']."'");
		
				//Send SMS to Doctor
				/*$DocContatct = $getDocDetails[0]['contact_num'];
				$msg= "Dear ".$getDocDetails[0]['ref_name'].", ".$getDiagnoDetails[0]['diagnosis_name']." has updated the reports of ".$_POST['patient_name']." dated ".$_POST['refer_date'].". Kindly login to your practice account to view the reports. Thanks";
				send_msg($DocContatct,$msg);	*/	
				
				$chatMessage=$getDiagnoDetails[0]['diagnosis_name']." has updated the reports of ".$_POST['patient_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_POST['patient_id'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_POST['episode_id'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $_POST['company_id'];
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "7";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "1";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_POST['diagno_id'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $_POST['diagno_url'];
				$arrFieldsChat[] = 'note';
				$arrValuesChat[] = $_POST['txtDesc'];
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $curDate;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);		
			
			$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'");
			$getComp= mysqlSelect("*","compny_tab","company_id='".$_POST['company_id']."'");
			
				 $toEmail=$getComp[0]['email_id'];
		$mailSubject='Diagnosis -'.$getDiagno[0]['diagnosis_name'].' has updated  the reports of '.$_POST['patient_name'];  
		$fromContent='Medisense';
		$contentSection='Hi '.$getComp[0]['company_name'].',<br/> '.$getDiagno[0]['diagnosis_name'].' has updated  the reports of '.$_POST['patient_name'].'. <br/><br/> Many Thanks';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($getDiagno[0]['diagnosis_email']);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	$response="update-investigation";
	header("Location:Diagnostic-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}
	//ADD Diagnostics
if(isset($_POST['add_diagno'])){
	$diagno_name=$_POST['diagno_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
	$hosp=$_POST['hosp'];				  
	$password = randomPassword();
	$encypassword = md5($password);
		
	$arrFields_diagno[] = 'diagnosis_name';
	$arrValues_diagno[] = $diagno_name;
	$arrFields_diagno[] = 'diagnosis_email';
	$arrValues_diagno[] = $txtemail;
	$arrFields_diagno[] = 'diagnosis_contact_num';
	$arrValues_diagno[] = $mobile;
	$arrFields_diagno[] = 'diagnosis_city';
	$arrValues_diagno[] = $city;
	$arrFields_diagno[] = 'hospital_id';
	$arrValues_diagno[] = $hosp;
	$arrFields_diagno[] = 'company_id';
	$arrValues_diagno[] = $admin_id;								 
	$arrFields_diagno[] = 'diagnosis_password';
	$arrValues_diagno[] = $encypassword;
	
	$chkUser = mysqlSelect("*","Diagnostic_center","	diagnosis_email='".$txtemail."' or diagnosis_contact_num='".$mobile."'","","","","");
	if(count($chkUser)>0){
		$response="diagnostic-exists";	   
		header("Location:Add-Diagnostics?response=".$response);	
	}
	else
	{
		$diagnocreate=mysqlInsert('Diagnostic_center',$arrFields_diagno,$arrValues_diagno);
	$diagno_id = $diagnocreate;	
	
	
							
	$arrFields_refer[] = 'diagnostic_id';
	$arrValues_refer[] = $diagno_id;
	$arrFields_refer[] = 'company_id';
	$arrValues_refer[] = $admin_id;
	
	
	$diagnocreate=mysqlInsert('doc_diagnostics',$arrFields_refer,$arrValues_refer);	
	
	
	header("Location:Add-Diagnostics?response=".$response);
	}
}

//ADD Pharmacy
if(isset($_POST['add_pharma'])){
	$pharma_name=$_POST['pharma_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
	$hosp=$_POST['hosp'];	
	$arrFields_pharma[] = 'pharma_name';
	$arrValues_pharma[] = $pharma_name;
	$arrFields_pharma[] = 'pharma_email';
	$arrValues_pharma[] = $txtemail;
	$arrFields_pharma[] = 'pharma_contact_num';
	$arrValues_pharma[] = $mobile;
	$arrFields_pharma[] = 'pharma_city';
	$arrValues_pharma[] = $city;
	$arrFields_pharma[] = 'hospital_id';
	$arrValues_pharma[] = $hosp;
	$arrFields_pharma[] = 'company_id';
	$arrValues_pharma[] = $admin_id;								 
	
	$chkUser = mysqlSelect("*","pharma","pharma_email='".$txtemail."' or pharma_contact_num='".$mobile."'","","","","");
	if(count($chkUser)>0){
		$response="diagnostic-exists";	   
		header("Location:Add-Pharmacy?response=".$response);	
	}
	else{
	$pharmacreate=mysqlInsert('pharma',$arrFields_pharma,$arrValues_pharma);
	$pharma_id = $pharmacreate;
	

	$arrFields_refer[] = 'pharma_id';
	$arrValues_refer[] = $pharma_id;
	$arrFields_refer[] = 'company_id';
	$arrValues_refer[] = $admin_id;
		
	$pharmarefer=mysqlInsert('doc_pharma',$arrFields_refer,$arrValues_refer);
		
	$response="created-success";
	
	header("Location:Add-Pharmacy?response=".$response);
	}
}

if(isset($_GET['chkTime'])){
	$_SESSION['visit_time'] = $_GET['chkTime'];
	
}
if(isset($_GET['appointTypeDoc'])){
	$_SESSION['appointTypeDoc'] = $_GET['appointTypeDoc'];
	
}
//ADD Referring Doctors
if(isset($_POST['add_referin_doctor']))
{
	$doc_name=$_POST['doc_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
	
	$address=$_POST['address'];
	$slctSpec=$_POST['slctSpec'];
	$type=$_POST['type'];
	$slctHospt=$_POST['slctHospt'];	  
		
	$arrFields_doctor[] = 'doctor_name';
	$arrValues_doctor[] = $doc_name;
	$arrFields_doctor[] = 'doctor_email';
	$arrValues_doctor[] = $txtemail;
	$arrFields_doctor[] = 'doctor_mobile';
	$arrValues_doctor[] = $mobile;
	$arrFields_doctor[] = 'doctor_city';
	$arrValues_doctor[] = $city;
	$arrFields_doctor[] = 'doc_specialization';
	$arrValues_doctor[] = $slctSpec;
	$arrFields_doctor[] = 'doc_address';
	$arrValues_doctor[] = $address;
	$arrFields_doctor[] = 'type';
	$arrValues_doctor[] = $type;
	$arrFields_doctor[] = 'ref_hosp_id';
	$arrValues_doctor[] = $slctHospt;							  
	//$arrFields_doctor[] = 'doc_id';
	//$arrValues_doctor[] = $admin_id;
	//$arrFields_doctor[] = 'hosp_id';
	//$arrValues_doctor[] = $admin_id;
	$arrFields_doctor[] = 'company_id';
	$arrValues_doctor[] = $admin_id;
	
	$doccreate=mysqlInsert('doctor_in_referral',$arrFields_doctor,$arrValues_doctor);	
		
	$response="created-success";
if($_POST['appointSec']=="1"){
	header("Location:Appointments?response=".$response);
}
else{
	header("Location:Refer-Out-Doctor?response=".$response);
}
 }
 
//ADD Referring Hospitals
if(isset($_POST['add_referout_hospital'])){
	$hos_name=$_POST['hos_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$address=$_POST['address'];
	
	
	$arrFields_hospital[] = 'hospital_name';
	$arrValues_hospital[] = $hos_name;
	$arrFields_hospital[] = 'hospital_email';
	$arrValues_hospital[] = $txtemail;
	$arrFields_hospital[] = 'hospital_mobile';
	$arrValues_hospital[] = $mobile;
	$arrFields_hospital[] = 'hos_address';
	$arrValues_hospital[] = $address;
	//$arrFields_hospital[] = 'doc_id';
	//$arrValues_hospital[] = $admin_id;
	//$arrFields_hospital[] = 'hosp_id';
	//$arrValues_hospital[] = $admin_id;
	$arrFields_hospital[] = 'company_id';
	$arrValues_hospital[] = $admin_id;
	$arrFields_hospital[] = 'created_date';
	$arrValues_hospital[] = $curDate;
	
	$hoscreate=mysqlInsert('hospital_in_referral',$arrFields_hospital,$arrValues_hospital);	
		
	$response="created-success";
if($_POST['appointSec']=="1"){
	header("Location:Appointments?response=".$response);
}
else{
	header("Location:Refer-Out-Hospital?response=".$response);
}
}
//ADD Reference
if(isset($_POST['add_referred_doc']) || isset($_POST['update_referred'])){
	$referral_name	=addslashes($_POST['referral_name']);	
	$referral_email	=addslashes($_POST['referral_email']);
	$referral_mobile=addslashes($_POST['referral_mobile']);
	$referral_city	=addslashes($_POST['referral_city']);
	$referral_add	=addslashes($_POST['referral_address']);
	$referral_state	=addslashes($_POST['se_state1']);
	$referral_country=addslashes($_POST['se_country']);
	$reference_type	=addslashes($_POST['reference_type']);
	$getDocDetails 	= mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","","","","");

	$arrFields[] = 'referral_name';
	$arrValues[] = $referral_name;
	$arrFields[] = 'referral_email';
	$arrValues[] = $referral_email;
	$arrFields[] = 'referral_mobile';
	$arrValues[] = $referral_mobile;
	$arrFields[] = 'referral_city';
	$arrValues[] = $referral_city;
	$arrFields[] = 'referral_address';
	$arrValues[] = $referral_add;
	$arrFields[] = 'referral_state';
	$arrValues[] = $referral_state;
	$arrFields[] = 'referral_country';
	$arrValues[] = $referral_country;
	$arrFields[] = 'reference_type';
	$arrValues[] = $reference_type;
	//$arrFields[] = 'hosp_id';
	//$arrValues[] = $admin_id;
	$arrFields[] = 'company_id';
	$arrValues[] = $admin_id;
	
	if(isset($_POST['add_referred_doc'])){
	//$arrFields[] = 'doc_id';
	//$arrValues[] = $admin_id;
	
	$createrefdoc=mysqlInsert('add_referred_doctor',$arrFields,$arrValues);
	$response="created-success";
	}
	if(isset($_POST['update_referred'])){
	$updaterefdoc=mysqlUpdate('add_referred_doctor',$arrFields,$arrValues,"referred_doc_id='".$_POST['referred_doc_id']."'");
	$response="update-success";
	}
		
	if($_POST['appointSec']=="1"){
		header("Location:Appointments?response=".$response);
	}
	else{	
	header("Location:Add-Referred-Doctor?response=".$response);
	}
}	

if(isset($_GET['appointTypeChange'])){
	$_SESSION['appointment_type'] = $_GET['appointTypeChange'];
	
}

//Add Blog Post
if(isset($_POST['cmdBlg'])){
	$blogTitle= addslashes($_POST['blog_title']);
	$txtRefId= $_POST['slctDoc'];
	$blogDesc= addslashes($_POST['descr']);
	$blog_pic = basename($_FILES['txtPhoto']['name']);
	$postkey=time();

	if(!empty($txtRefId)){
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$txtRefId."'","","","","");
	$Login_User_Id =$txtRefId;
	$Login_User_Type = "doc";
	$hosp_id = $getDocDetails[0]['hosp_id'];
	}
	else{
	$Login_User_Id ="0";
	$Login_User_Type = "";
	$hosp_id = "0";	
	}
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $blogTitle;
	$arrFields[]= 'post_description';
	$arrValues[]= $blogDesc;
	$arrFields[]= 'post_type';
	$arrValues[]= "blog";
	
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $Login_User_Id;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $Login_User_Type;
	
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'hosp_id';
	$arrValues[]= $hosp_id;
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	$arrFields[]= 'post_image';
	$arrValues[]= $blog_pic;
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Blog";
	$arrFields1[]= 'hosp_id';
	$arrValues1[]= $hosp_id;;
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$blogTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $blog_id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Blog";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"premium/Postimages";
					$sub_folder		=	$blog_id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					// $uploaddirectory = realpath("../premium/Postimages");
					// mkdir("../premium/Postimages/". "/" . $blog_id, 0777);
					// $uploaddir = $uploaddirectory."/".$blog_id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $blog_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
	$response="Added";
	header("Location:Blog-List?response=".$response);
}	
	
//TURN TO DIRECT APPOINTMENT
if(isset($_POST['cmdAppt'])){
	
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'patient_id'; 
$arrValues2[]= $patientID;
$arrFields2[]= 'ref_id'; 
$arrValues2[]= $txtRefId;
$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";
$arrFields2[]= 'conversion_status';
$arrValues2[]= "2";

if($chkRefInfo==true){
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "OP-DESIRED"
$arrValues1[]= "7";
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,TImestamp","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.ref_address as ref_address,a.doc_state as doc_state,a.doc_spec as doc_spec,a.doc_photo as doc_photo,c.hosp_name as hosp_name,d.company_name as company_name,d.email_id as CompEmail','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$url_page = 'Custom_Turn_to_Appointment.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['CompEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thx, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']."has been sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $patientID;
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
	$response="Appointment-Success";
	header("Location:patient-history?p=".md5($patientID)."&response=".$response);			
}

//RESCHEDULE APPOINTMENT	
if($_POST['act']=="add-reschedule"){
	
	$visitDate = date('Y-m-d',strtotime($_POST['appDate']));
	$slctTime = $_POST['appTime'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['patTransId']."'");
		
		$getInfo1 	= mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['patTransId']."'" ,"","","","");	
		$getDoc 	= mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime 	= mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
	send_msg($mobile,$responsemsg);
	
}	
//EDIT ORGANIZATION
if(isset($_POST['edit_organization'])){
	
	$txtOrgName 		= addslashes($_POST['txtOrgName']);
	$txtContactPerson 	= addslashes($_POST['txtContactPerson']);
	$txtAddress 		= addslashes($_POST['txtAddress']);	
	$txtMobile 			= $_POST['txtMobile'];
	$txtEmail 			= addslashes($_POST['txtEmail']);
	$compImage 			= basename($_FILES['txtPhoto']['name']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'company_name';
		$arrValues[] = $txtOrgName;
		$arrFields[] = 'owner_name';
		$arrValues[] = $txtContactPerson;
		$arrFields[] = 'company_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'mobile';
		$arrValues[] = $txtMobile;
		if(!empty($compImage))
		{
			$arrFields[] = 'company_logo';
			$arrValues[] = $compImage;
		}
		
	$updateCompany=mysqlUpdate('compny_tab',$arrFields,$arrValues,"company_id='".$admin_id."'");
	$id=$admin_id;
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"premium/company_logo";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("../premium/company_logo");
					// mkdir("../premium/company_logo/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $compImage;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } 
					// else 
					// {
						//echo "File cannot be uploaded";
					// }
				}
	header('location:Profile?response=update');
}
//CHANGE PASSWORD 
if(isset($_POST['change_password'])){
	 
	 $txtPass = md5($_POST['new_password']);
	 $txtRePass = md5($_POST['retype_password']);
	
	//$result = mysqlSelect('ref_id','referal',"ref_id='".$_POST['Prov_Id']."'");
	if($txtPass==$txtRePass){
	
		
		$arrFields = array();
		$arrValues = array();		
		
		$arrFields[] = 'password';
		$arrValues[] = $txtPass;
		
		
		$editrecord=mysqlUpdate('compny_tab',$arrFields,$arrValues,"company_id='".$admin_id."'");
		
						
		header('location:Profile?response=password');
	}
	else{
	header('location:Profile?response=error-password');	
	}

}	

	

	
//RESCHEDULE APPOINTMENT	
if(isset($_POST['cmdreschedule'])){
	
	$visitDate = date('Y-m-d',strtotime($_POST['reschedule_date']));
	$slctTime = $_POST['selectTime'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['Pat_Trans_Id']."'");
		
		$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['Pat_Trans_Id']."'" ,"","","","");	
		$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:appointment_patient_history.php?pattransid=".$_POST['Pat_Trans_Id']."&response=".$response);			

}	
	
//TURN TO DIRECT APPOINTMENT
if(isset($_POST['sendAppReq'])){
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'patient_id'; 
$arrValues2[]= $patientID;
$arrFields2[]= 'ref_id'; 
$arrValues2[]= $txtRefId;
$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";
$arrFields2[]= 'conversion_status';
$arrValues2[]= "2";

if($chkRefInfo==true){
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "STAGED"
$arrValues1[]= "7";
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = mysqlSelect("*","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$url_page = 'Turn_to_Appointment.php';
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thanks, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $patientID;
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
	$response="Appointment-Success";
	header("Location:patient-history?p=".$_SESSION['patientid']."&c=".$_SESSION['adminid']."&response=".$response);			
}	
	

//Doctor reassign functionality
if(isset($_POST['cmdreassign'])){
	$patid = $_POST['patientid'];
	$SelectRef = $_POST['selectref'];
	
	
	$arrFields[]= 'ref_id';
	$arrValues[]= $SelectRef;
	
	$updatereferral=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	

	$updateChatHistory=mysqlUpdate('chat_notification',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	
	
	//Get Reassigned Doctor details
	$getReassignedDoc=mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.Total_Referred as Total_Referred,c.communication_status as communication_status,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,c.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$SelectRef."'","","","","");	
	$getReassignedDocSpec=mysqlSelect("spec_name","specialization","spec_id='".$getReassignedDoc[0]['doc_spec']."'","","","","");
	$reassignmsg="Case has been re-assign to ".$getReassignedDoc[0]['ref_name'];
	$get_organisation=mysqlSelect("company_id,company_name,company_logo,email_id,mobile","compny_tab","company_id='".$getReassignedDoc[0]['company_id']."'","","","","");	
	
		
	
	$arrFields1 = array();
	$arrValues1 = array();
	$arrFields1[]= 'patient_id';
	$arrValues1[]= $patid;
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $SelectRef;
	$arrFields1[]= 'status_id';
	$arrValues1[]= "2";
	$arrFields1[]= 'chat_note';
	$arrValues1[]= $reassignmsg;
	$arrFields1[]= 'TImestamp';
	$arrValues1[]= $curDate;
	$addoffers=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
	
	//Update Old Doctor No.of referral count, ie. we should decrement it by one
	//Get Reassigned Old Doctor details
	$getOldDoc=mysqlSelect("*","referal","ref_id='".$_POST['oldrefid']."'","","","","");
	$getUpdateCount=$getOldDoc[0]['Total_Referred']-1;//Decrement it by one
	
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[]= 'Total_Referred';
	$arrValues2[]= $getUpdateCount;
	
	$updatereferral1=mysqlUpdate('referal',$arrFields2,$arrValues2,"ref_id='".$_POST['oldrefid']."'");	

	
	//Update Ressigned Doctor No.of referral count, ie. we should increment it by one
	$getUpdateNewCount=$getReassignedDoc[0]['Total_Referred']+1;//Increment it by one
	
	$arrFields3[]= 'Total_Referred';
	$arrValues3[]= $getUpdateNewCount;
	
	$updatereferral2=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$SelectRef."'");
	
	if(!empty($_POST['patemail']) && $getReassignedDoc[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($getReassignedDoc[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$getReassignedDoc[0]['ref_id']."/".$getReassignedDoc[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getReassignedDocSpec[0]['spec_name']));
						$getDocName=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$getReassignedDoc[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$getReassignedDoc[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$getReassignedDoc[0]['ref_id'];
						$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['company_id'].'/'.$get_organisation[0]['company_logo'];
	
											
						$url_page = 'Custom_after_reassign_pat_mail.php';
						$ccmail="medical@medisense.me";
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($getReassignedDoc[0]['ref_name']);
						$url .= "&docid=" . urlencode($getReassignedDoc[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getReassignedDoc[0]['spec_name']);					
						$url .= "&patid=" . urlencode($_POST['patientid']);
						$url .= "&patname=" . urlencode($_POST['patname']);					
						$url .= "&patmail=" . urlencode($_POST['patemail']);
						$url .= "&hospName=".urlencode($getReassignedDoc[0]['hosp_name']);
						$url .= "&compLogo=".urlencode($compLogo);
						$url .= "&compMail=".urlencode($get_organisation[0]['email_id']);
						$url .= "&ccmail=" . urlencode($ccmail);		
						//send_mail($url);		
					
					}
	
	//Message Notification to Partner
	$getPartnerInfo=mysqlSelect("a.partner_name as partner_name,a.cont_num1 as cont_num,a.Email_id as Email_id","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$_POST['sourceid']."'","","","","");
	$mobile = $getPartnerInfo[0]['cont_num'];
	$responsemsg = "Dear ".$getPartnerInfo[0]['partner_name'].", ".$_POST['patname']."(".$_POST['patientid']."), patient medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	send_msg($mobile,$responsemsg);
	
	
	//Message Notification to patient
	//$mobile = $_POST['patmobile'];
	//$responsemsg = "Dear ".$_POST['patname'].", Your medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	//send_msg($mobile,$responsemsg);
	
	$response="reassign";
	header("Location:patient-history?p=".md5($_POST['patientid'])."&response=".$response);
	
}



	
//Search By Name,Email,Location & Contact No.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	header("Location:search.php?s=".$txtSearch);
	
}


//Add Offers & Events
if(isset($_POST['addOffers']) || isset($_POST['editOffers'])){

	$startDate= $_POST['startendDate'];
	
	$start_date= date('Y-m-d',strtotime($_POST['start_date']));
	$end_date= date('Y-m-d',strtotime($_POST['end_date']));
	$org_committee= $_POST['org_committee'];
	$key_speaker= $_POST['key_speaker'];
	$web_link= $_POST['web_link'];
	$slctHosp= $_POST['selectHosp'];
	
	$cont_num= $_POST['cont_num'];
	$cont_email= $_POST['cont_email'];
	
	$offerTitle= addslashes($_POST['offer_title']);
	$eventType= $_POST['eventType'];
	$Descr= addslashes($_POST['descr']);
	$event_pic = basename($_FILES['txtPhoto']['name']);	
	$event_attachment = basename($_FILES['txtBrochure']['name']);
	$event_key=time();
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'event_trans_id';
	$arrValues[]= $event_key;
		
	$arrFields[]= 'start_date';
	$arrValues[]= $start_date;	
	$arrFields[]= 'end_date';
	$arrValues[]= $end_date;

	$arrFields[]= 'organising_committee';
	$arrValues[]= $org_committee;	
	$arrFields[]= 'kenote_speakers';
	$arrValues[]= $key_speaker;	
	
	$arrFields[]= 'website_link';
	$arrValues[]= $web_link;
	
	$arrFields[]= 'title';
	$arrValues[]= $offerTitle;
	$arrFields[]= 'description';
	$arrValues[]= $Descr;
	$arrFields[]= 'event_type';
	$arrValues[]= "1";
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;	
	//$arrFields[]= 'hosp_id';
	//$arrValues[]= $slctHosp;
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	$arrFields[]= 'job_contact_info';
	$arrValues[]= $cont_num;
	$arrFields[]= 'contact_email';
	$arrValues[]= $cont_email;
	$arrFields[]= 'description_attachment';
	$arrValues[]= $event_attachment;
	$arrFields[]= 'created_date';
	$arrValues[]= $curDate;
	
	if(isset($_POST['addOffers'])){
	$addoffers=mysqlInsert('offers_events',$arrFields,$arrValues);
	$id= $addoffers;
	
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $id;
	
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Events";
		
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;
	//$arrFields1[]= 'hosp_id';
	//$arrValues1[]= $slctHosp;
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
		$searchTags=$_POST['searchTags'].",".$offerTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Events";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	$response="Added";
	} else if(isset($_POST['editOffers'])){
		$updateOffer=mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
			
					$folder_name	=	"premium/Eventimages";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					
					// $uploaddirectory = realpath("../premium/Eventimages");
					// mkdir("../premium/Eventimages/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $event_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
				
				/* Uploading Event Brochure */ 
				if(basename($_FILES['txtBrochure']['name']!==""))
				{ 
					$folder_name	=	"premium/EventAttachments";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtBrochure']['name'];
					$file_url		=	$_FILES['txtBrochure']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("../premium/EventAttachments");
					// mkdir("../premium/EventAttachments/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtBrochure']['name'], '.');
					// $photo = $event_attachment;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtBrochure']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
		//Here we need to Send Push notification to mapped partners
		//$getrefPartlist = mysqlSelect("*","our_partners as a left join mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.hosp_id='".$slctHosp."'","","","","");
		//Retrieve all partners gcm id
		//$getrefPartlist = mysqlSelect("gcm_tokenid as GCM","our_partners","login_status=1","","","","");
		//Retrieve all doctors gcm id
		$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		
		$msg=$Descr;
		$title=$offerTitle;
		$subtitle=$Descr;
		$tickerText="Test Ticker";
		$type="2"; //For Event type 2
		$patientid="0";
		$docid=$_POST['selectref'];
			if(!empty($event_pic)){
			$largeimg=HOST_MAIN_URL."/premium/Eventimages/".$id."/".$event_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}
			$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
		//Push Notification to partner	
		/*foreach($getrefPartlist as $PartList){
		$regid=$PartList['GCM'];
		
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$id,$patientid,$docid,$event_key);
		}*/
		//Push Notification to Doctors
		/*foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];
		
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$id,$patientid,$docid,$event_key);
		}*/
		//End Push notification functionality
		
	header("Location:Offers-Events-List?response=".$response);
	
}

//ADD SURGICAL VIDEO	
if(isset($_POST['video_publish'])){
	
	$videoTitle= addslashes($_POST['video_title']);
	$txtRefId= $_POST['slctDoc'];
	$videoUrl= $_POST['video_link'];
	$videoDesc= addslashes($_POST['video_Description']);
	$postkey=time();
	$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $videoUrl);
	$mainDesc='<p>'.$videoDesc.'</p>';
	
	if(!empty($txtRefId)){
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$txtRefId."'","","","","");
	$Login_User_Id =$txtRefId;
	$Login_User_Type = "doc";
	$hosp_id = $getDocDetails[0]['hosp_id'];
	}
	else{
	$Login_User_Id ="0";
	$Login_User_Type = "";
	$hosp_id = "0";	
	}
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $videoTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $Login_User_Id;
	$arrFields[]= 'post_description';
	$arrValues[]= $mainDesc;
	$arrFields[]= 'video_url';
	$arrValues[]= $videoUrl;
	$arrFields[]= 'video_id';
	$arrValues[]= $getCode;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $Login_User_Type;
	$arrFields[]= 'post_type';
	$arrValues[]= "surgical";
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'hosp_id';
	$arrValues[]= $hosp_id;
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	
	
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Surgical";
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	$arrFields1[]= 'hosp_id';
	$arrValues1[]= $hosp_id;
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$videoTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $blog_id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Surgical";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	$response="Added";
	header("Location:Surgical-Video?response=".$response);

}

//Add Offers & Events
if(isset($_POST['addJobs']) || isset($_POST['editJobs'])){

	
	$start_date= date('Y-m-d',strtotime($_POST['start_date']));
	$end_date= date('Y-m-d',strtotime($_POST['end_date']));
	$web_link= $_POST['web_link'];
	$slctHosp= $_POST['selectHosp'];
	
	$cont_num= $_POST['cont_num'];
	$cont_email= $_POST['cont_email'];
	
	$jobTitle= addslashes($_POST['job_title']);
	$Descr= addslashes($_POST['descr']);
	$event_pic = basename($_FILES['txtPhoto']['name']);	
	$event_attachment = basename($_FILES['txtBrochure']['name']);
	$event_key=time();
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'event_trans_id';
	$arrValues[]= $event_key;
		
	$arrFields[]= 'start_date';
	$arrValues[]= $start_date;	
	$arrFields[]= 'end_date';
	$arrValues[]= $end_date;
	
	
	$arrFields[]= 'website_link';
	$arrValues[]= $web_link;
	
	$arrFields[]= 'title';
	$arrValues[]= $jobTitle;
	$arrFields[]= 'description';
	$arrValues[]= $Descr;
	$arrFields[]= 'event_type';
	$arrValues[]= "3";
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;	
	//$arrFields[]= 'hosp_id';
	//$arrValues[]= $slctHosp;
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	$arrFields[]= 'job_contact_info';
	$arrValues[]= $cont_num;
	$arrFields[]= 'contact_email';
	$arrValues[]= $cont_email;
	$arrFields[]= 'description_attachment';
	$arrValues[]= $event_attachment;
	$arrFields[]= 'created_date';
	$arrValues[]= $curDate;
	
	if(isset($_POST['addJobs'])){
	$addJobs=mysqlInsert('offers_events',$arrFields,$arrValues);
	$id= $addJobs;
	
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $id;
	
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Jobs";
		
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;
	//$arrFields1[]= 'hosp_id';
	//$arrValues1[]= $slctHosp;
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
		$searchTags=$_POST['searchTags'].",".$offerTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Jobs";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	$response="Added";
	} else if(isset($_POST['editOffers'])){
		$updateOffer=mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
			
					$folder_name	=	"premium/Eventimages";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("../premium/Eventimages");
					// mkdir("../premium/Eventimages/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $event_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
					//	echo "File uploaded.";
					// } else {
					//	echo "File cannot be uploaded";
					// }
				}
				
				/* Uploading Event Brochure */ 
				if(basename($_FILES['txtBrochure']['name']!==""))
				{ 
					$folder_name	=	"premium/Jobdescription";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtBrochure']['name'];
					$file_url		=	$_FILES['txtBrochure']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("../premium/Jobdescription");
					// mkdir("../premium/Jobdescription/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtBrochure']['name'], '.');
					// $photo = $event_attachment;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtBrochure']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
		//Here we need to Send Push notification to mapped partners
		//$getrefPartlist = mysqlSelect("*","our_partners as a left join mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.hosp_id='".$slctHosp."'","","","","");
		//Retrieve all partners gcm id
		//$getrefPartlist = mysqlSelect("gcm_tokenid as GCM","our_partners","login_status=1","","","","");
		//Retrieve all doctors gcm id
		$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		
		$msg=$Descr;
		$title=$offerTitle;
		$subtitle=$Descr;
		$tickerText="Test Ticker";
		$type="2"; //For Event type 2
		$patientid="0";
		$docid=$_POST['selectref'];
			if(!empty($event_pic)){
			$largeimg=HOST_MAIN_URL."/premium/Eventimages/".$id."/".$event_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}
			$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
		//Push Notification to partner	
		/*foreach($getrefPartlist as $PartList){
		$regid=$PartList['GCM'];
		
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$id,$patientid,$docid,$event_key);
		}*/
		//Push Notification to Doctors
		/*foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];
		
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$id,$patientid,$docid,$event_key);
		}*/
		//End Push notification functionality
		
	header("Location:Job-Post?response=".$response);
	
}

//Add Blog Post
if(isset($_POST['cmdBlog'])){
	$blogTitle= addslashes($_POST['blog_title']);
	$txtRefId= $_POST['selectref'];
	$blogDesc= addslashes($_POST['descr']);
	$blog_pic = basename($_FILES['txtPhoto']['name']);
	$postkey=time();
	if(!empty($txtRefId)){
		$loginid=$txtRefId;
		$logintype="doc";
	}
	else{
		$loginid=$admin_id;
		$logintype="user";
	}
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $blogTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $loginid;
	$arrFields[]= 'post_description';
	$arrValues[]= $blogDesc;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;
	$arrFields[]= 'company_id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	$arrFields[]= 'post_image';
	$arrValues[]= $blog_pic;
	
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Blog";
	$arrFields1[]= 'company_id';
	$arrValues1[]= $admin_id;
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
			
					$folder_name	=	"Postimages";
					$sub_folder		=	$blog_id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("Postimages");
					// mkdir("Postimages/". "/" . $blog_id, 0777);
					// $uploaddir = $uploaddirectory."/".$blog_id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $blog_pic;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
	
	//Here we need to Send Push notification to mapped partners
	//$pushDesc=strip_tags($blogDesc);
		$pushDesc=strip_tags($blogDesc);
		$msg=substr($pushDesc,0,30);
		$title=substr($blogTitle,0,30);
		$subtitle=substr($pushDesc,0,30);
		$tickerText="Leap new blog";
		$type="1"; //For Blog Type value is 1
		$patientid="0";
		$docid=$_POST['selectref'];
		
			if(!empty($blog_pic)){
			$largeimg=HOST_MAIN_URL."/Hospital/Postimages/".$blog_id."/".$blog_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}
			
		//Retrieve all partners gcm id and ASTER CMI Partners not included
		if($admin_id!=10){
		$getrefPartlist = mysqlSelect("a.gcm_tokenid as GCM","our_partners as a mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.company_id!=10 and a.login_status=1","","","","");
		$smalimg=HOST_MAIN_URL."Hospital/images/practice_push_icon.png";
		}
		else
		{
		//Retrieve all Partners when ASTER will Post any blogs	
		//Aster Push Icons
		$smalimg=HOST_MAIN_URL."Hospital/images/aster_push_icon.png";
		
		$getrefPartlist = mysqlSelect("gcm_tokenid as GCM","our_partners","login_status=1","","","","");
		
		}
		//Push notification for partners
		foreach($getrefPartlist as $PartList){
		$regid=$PartList['GCM'];
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
		push_notification_Aster_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
		
		}
		//End Push notification functionality
		
			
		//Retrieve all doctors gcm id
		/*$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		
		//Push notification for Doctors
		foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];	
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
		}*/
		//End Push notification functionality
	
	
	header("Location:Blogs");
}
	
if(isset($_POST['addRef'])){
	
	$txtRefId= $_POST['txtref'];

	if($txtRefId!=""){
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'patient_id';
	$arrValues1[]= $_POST['Pat_Id'];
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $txtRefId;
	$arrFields1[]= 'email_status';
	$arrValues1[]= "1";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	$arrFields1[]= 'status2';
	$arrValues1[]= "2";
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= "2";
	$arrFields1[]= 'timestamp';
	$arrValues1[]= $Cur_Date;
	$chkreflist = mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
	}else{
		
			$getPatInfo = mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
			if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']=="" || $getPatInfo[0]['pat_country']=="" || $getPatInfo[0]['patient_complaint']=="" || $getPatInfo[0]['patient_desc']=="" || $getPatInfo[0]['repnotattach']==0){
			
			echo '<script language="javascript">';
			echo 'alert("Please fill the required patient details properly")';
			echo '</script>'; 
			
			} else if($getPatAttach==true || $getPatInfo[0]['repnotattach']==1 ) {
		
						mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef=mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id=mysql_insert_id();
						$pat_id = $_POST['Pat_Id'];
						$_SESSION['Ref_Id']=$txtRefId;
	
						
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
					
					
					if(!empty($getPatInfo[0]['patient_email']) && $get_pro[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDocDept[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$get_pro[0]['ref_id'];
						
						//TO CHECK MEDI ASSIST Source
						if($getPatInfo[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
						$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
						}
						else{
							$mas_logo="";
						}
						
						$url_page = 'After_refer_pat_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
						$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
					}
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'email_status';
					$arrValues[]= "1";
					$patientRef=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$pat_id."'and ref_id='".$refer_id."'");
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro[0]['ref_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
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
					$arrFields2[] = 'user_id';
					$arrValues2[] = $admin_id;
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					
					$usercraete=mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
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
					
				//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, You have received a query of patient " . $getPatInfo[0]['patient_name'] . $getPatInfo[0]['patient_id'] ." - Many Thanks";
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Referral";
				$subtitle="New Referral";
				$tickerText="Leap new blog";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				}	
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!="" && $get_pro[0]['communication_status']!=0){
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query has been successfully referred to ".$get_pro[0]['ref_name']." Please check your mail for further detail. Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
				}
					
		}
	
}//End If loop

}
	
	
//ADD / UPDATE MARKETING PERSON
if(isset($_POST['add_person']) || isset($_POST['edit_person'])){
	//Check Empty condition
	if(!empty($_POST['selectHosp']) || !empty($_POST['person_name']) || !empty($_POST['person_mobile']))
	{
	$selectHosp = $_POST['selectHosp'];
	$person_name = addslashes($_POST['person_name']);
	$person_mobile = $_POST['person_mobile'];
	$person_email = addslashes($_POST['person_email']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'person_name';
		$arrValues[] = $person_name;
		$arrFields[] = 'hosp_id';
		$arrValues[] = $selectHosp;
		$arrFields[] = 'person_mobile';
		$arrValues[] = $person_mobile;
		$arrFields[] = 'person_email';
		$arrValues[] = $person_email;
		
		if(isset($_POST['add_person'])){
			$personcreate=mysqlInsert('hosp_marketing_person',$arrFields,$arrValues);
			$person_id= $personcreate;
			
			
			header("Location:Add-Marketing-Persons?response=add");
				
		}
		else{	
			$updateProvider=mysqlUpdate('hosp_marketing_person',$arrFields,$arrValues,"person_id='".$_POST['Person_Id']."'");	
			header("Location:Add-Marketing-Persons?response=update");
		}
	
	}
	else
	{
	header("Location:Add-Marketing-Persons?response=error");	
	}
}	
	
//ADD / UPDATE REFERRING PARTNER
if(isset($_POST['add_referrer']) || isset($_POST['edit_referrer'])){
	//Check Empty condition
	if(!empty($_POST['refPartName']) || !empty($_POST['refPartMobile']))
	{	
	$selectHosp = $_POST['selectHosp'];
	$selectPerson = $_POST['selectPerson'];
	$ref_name = addslashes($_POST['refPartName']);
	$partnertype = $_POST['selectType'];
	$partnercategory = $_POST['partner_cat'];
	$ref_mobile = $_POST['refPartMobile'];
	$ref_email = $_POST['refPartEmail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	//Check Referrer mobile/email id exists in our partner table
	if(!empty($_POST['refPartMobile']) && !empty($_POST['refPartEmail'])){ 
	$chkPartner = mysqlSelect("*","our_partners","Email_id='".$ref_email."' or Email_id1='".$ref_email."' or cont_num1='".$ref_mobile."'","","","","");
	}
	else if(!empty($_POST['refPartMobile']) && empty($_POST['refPartEmail'])){
	$chkPartner = mysqlSelect("*","our_partners","cont_num1='".$ref_mobile."'","","","","");
	}
	$getHosp = mysqlSelect("*","hosp_tab","hosp_id='".$selectHosp."'","","","","");
	
	//Check Referrer is already mapped in marketing person
	$chkMappedReferrer = mysqlSelect("*","mapping_hosp_referrer","partner_id='".$chkPartner[0]['partner_id']."' and hosp_id='".$selectHosp."'","","","","");
	$get_organisation = mysqlSelect('company_id as Comp_Id,company_name as Comp_name,mobile as Org_Contact,email_id as Comp_Email,company_logo as Logo','compny_tab',"company_id='".$admin_id."'");
	$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['Comp_Id'].'/'.$get_organisation[0]['Logo'];
	
	//$webLink=$hostname."/Refer/";  
	$webLink="https://goo.gl/89JY9X";
			
	if($chkPartner==true && $chkMappedReferrer==true){
					
					header("Location:Care-Partners?response=error");
	}
	
	else if($chkPartner==true){
			
			$partner_id= $chkPartner[0]['partner_id'];
			$arrFields1 = array();
			$arrValues1 = array();
			$arrFields1[] = 'market_person_id';
			$arrValues1[] = $selectPerson;
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $admin_id;
			$arrFields1[] = 'TImestamp';
			$arrValues1[] = date('Y-m-d H:i:s');
			
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
					//Mail Notification to Referring Partner
					
					$usercredentials="Web Link :".$webLink."<br>User ID :".$chkPartner[0]['Email_id']." / ".$chkPartner[0]['cont_num1']."<br>Password: You have already registered. If you have forgotten password, then click forgot password in login page. <br><br>";
					
					if(!empty($ref_email)){
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&marketingmail=" . urlencode($getHosp[0]['person_email']);
					$url .= "&marketingmobile=".urlencode($getHosp[0]['person_mobile']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					}
					
					if(!empty($ref_mobile)){
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link https://goo.gl/89JY9X to login. Pls check ".$ref_email.". Get mobile app at https://goo.gl/Cs1CSK. Thanks";
					send_msg($mobile,$responsemsg);
					}
					header("Location:Care-Partners?response=add");
	}
	else{
		
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'partner_name';
		$arrValues[] = $ref_name;
		$arrFields[] = 'contact_person';
		$arrValues[] = $ref_name;
		$arrFields[] = 'Email_id';
		$arrValues[] = $ref_email;
		$arrFields[] = 'cont_num1';
		$arrValues[] = $ref_mobile;
		$arrFields[] = 'specialisation';
		$arrValues[] = "45";
		$arrFields[] = 'password';
		$arrValues[] = $encypassword;
		$arrFields[] = 'reg_date';
		$arrValues[] = $curDate;
		$arrFields[] = 'Type';
		$arrValues[] = $partnercategory;
		
		if(isset($_POST['add_referrer'])){
			$personcreate=mysqlInsert('our_partners',$arrFields,$arrValues);
			$partner_id= $personcreate;
			
			
			
			
			//Insert Partner Id to Source List table
			$arrFields2[] = 'source_name';
			$arrValues2[] = $ref_name;
			$arrFields2[] = 'partner_id';
			$arrValues2[] = $partner_id;
		
			$createsource=mysqlInsert('source_list',$arrFields2,$arrValues2);
			
			
			$arrFields1 = array();
			$arrValues1 = array();
			$arrFields1[] = 'market_person_id';
			$arrValues1[] = $selectPerson;
			$arrFields1[] = 'partner_id';
			$arrValues1[] = $partner_id;
			$arrFields1[] = 'partner_type';
			$arrValues1[] = $partnertype;
			$arrFields1[] = 'hosp_id';
			$arrValues1[] = $selectHosp;
			$arrFields1[] = 'company_id';
			$arrValues1[] = $admin_id;
			$arrFields1[] = 'TImestamp';
			$arrValues1[] = date('Y-m-d H:i:s');
		
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
			
			
					//Mail Notification to Referring Partner
					 $usercredentials="Link :".$webLink."<br>User ID :".$ref_email." / ".$ref_mobile."<br>Password: ".$password."<br>";
					if(!empty($ref_email)){
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&marketingmail=" . urlencode($getHosp[0]['person_email']);
					$url .= "&marketingmobile=".urlencode($getHosp[0]['person_mobile']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					}
					if(!empty($ref_mobile)){
					//Message Notification to Referring Partner
					if(!empty($ref_email)){ $userId1=$ref_email." / "; }
					$userId2=$ref_mobile;
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Login credentials with User ID : ".$userId1.$userId2." and Password : ".$password.". Please check ".$ref_email.". Get mobile app at https://goo.gl/Cs1CSK .Thanks";
					send_msg($mobile,$responsemsg);
					}
			header("Location:Care-Partners?response=add");
		}
		//EDIT DETAILS
		else{	
			$updatePartner=mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$_POST['Partner_Id']."'");	
			$arrFields1[] = 'market_person_id';
			$arrValues1[] = $selectPerson;
			$updateMapping=mysqlUpdate('mapping_hosp_referrer',$arrFields1,$arrValues1,"partner_id='".$_POST['Partner_Id']."' and company_id='".$admin_id."'");
			header("Location:Care-Partners?response=update");
		}
	
		}
	}
	//Send Error message
	else
	{
		header("Location:Care-Partners?response=error");
	}
}	
//ADD / UPDATE HOSPITAL DETAILS
if(isset($_POST['add_hospital']) || isset($_POST['edit_hospital'])){
	//Check Empty condition
	if(!empty($_POST['txtHospName']) || !empty($_POST['slctComm']))
	{
	$txtHospName = addslashes($_POST['txtHospName']);
	$txtAddress = addslashes($_POST['txtAddress']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtSuburb = addslashes($_POST['txtSuburb']);
	$txtCity = addslashes($_POST['txtCity']);	
	$txtOverview = addslashes($_POST['txtOverview']);
	$txtBeds = addslashes($_POST['txtBeds']);
	$txtAmbulance = addslashes($_POST['txtAmbulance']);
	$txtServices = addslashes($_POST['txtServices']);
	$txtPerson = addslashes($_POST['txtPerson']);
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = addslashes($_POST['txtEmail']);
	$txtEmail1 = addslashes($_POST['txtEmail1']);
	$txtEmail2 = addslashes($_POST['txtEmail2']);
	$txtEmail3 = addslashes($_POST['txtEmail3']);
	$txtEmail4 = addslashes($_POST['txtEmail4']);
	$slctComm = addslashes($_POST['slctComm']);
	
	$txtrevisitcharge = addslashes($_POST['txtrevisitcharge']);
	$txtnewvisitcharge = addslashes($_POST['txtnewvisitcharge']);
	
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp = $_POST['slctHosp'];	
	$docImage = basename($_FILES['file-3']['name']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'hosp_name';
		$arrValues[] = $txtHospName;
		$arrFields[] = 'hosp_suburb';
		$arrValues[] = $txtSuburb;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $txtCity;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'hosp_contact_name';
		$arrValues[] = $txtPerson;
		$arrFields[] = 'hosp_contact';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'hosp_email';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'hosp_email1';
		$arrValues[] = $txtEmail1;
		$arrFields[] = 'hosp_email2';
		$arrValues[] = $txtEmail2;		
		$arrFields[] = 'hosp_email3';
		$arrValues[] = $txtEmail3;
		$arrFields[] = 'hosp_email4';
		$arrValues[] = $txtEmail4;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'communication_status';
		$arrValues[] = $slctComm;
		
		$arrFields[] = 'hosp_overview';
		$arrValues[] = $txtOverview;
		$arrFields[] = 'num_beds';
		$arrValues[] = $txtBeds;
		$arrFields[] = 'num_ambulance';
		$arrValues[] = $txtAmbulance;	
		$arrFields[] = 'hosp_services';
		$arrValues[] = $txtServices;
		$arrFields[] = 'revisit_charge';
		$arrValues[] = $txtrevisitcharge;
		$arrFields[] = 'newvist_charge';
		$arrValues[] = $txtnewvisitcharge;
		$arrFields[] = 'company_id';
		$arrValues[] = $_SESSION['user_id'];
					
				
	if(isset($_POST['add_hospital'])){
			$usercraete=mysqlInsert('hosp_tab',$arrFields,$arrValues);
			$hosp_id= $usercraete;
			
			
			
			
			foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
				$arrFields2 = array();
				$arrValues2 = array();

				$arrFields2[] = 'hosp_id';
				$arrValues2[] = $hosp_id;

				$arrFields2[] = 'amenity_id';
				$arrValues2[] = $amenValue;
				
				$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
			}
			
			
				
			//UPLOAD MULTIPLE IMAGES
			
			$errors= array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
			
				
			$file_name = $_FILES['file-3']['name'][$key];
			$file_size =$_FILES['file-3']['size'][$key];
			$file_tmp =$_FILES['file-3']['tmp_name'][$key];
			$file_type=$_FILES['file-3']['type'][$key];
			
			if(!empty($file_name)){
				$arrFields3 = array();
				$arrValues3 = array();

				$arrFields3[] = 'hosp_id';
				$arrValues3[] = $hosp_id;

				$arrFields3[] = 'hosp_image';
				$arrValues3[] = $file_name;
				
					
					$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
					$id= mysql_insert_id();


					//UPLOAD COMPRESSED IMAGE
					if ($_FILES["file-3"]["error"][$key] > 0) {
							$error = $_FILES["file-3"]["error"][$key];
					} 
					else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
					($_FILES['file-3']['type'][$key] == "image/jpeg") || 
					($_FILES['file-3']['type'][$key] == "image/png") || 
					($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
					{
						$folder_name	=	"Hosp_image";
						$sub_folder		=	$id;
						$filename		=	$_FILES['file-3']['name'][$key];
						$file_url		=	$_FILES['file-3']['tmp_name'][$key];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					 // $uploaddirectory = realpath("Hosp_image");
					 // $uploaddir = $uploaddirectory . "/" .$id;
					 
					 // /*Checking whether folder with add_hosp_picture id already exist or not. */
						// if (file_exists($uploaddir)) 
						// {
							//echo "The file $uploaddir exists";
						// } else 
						// {
							// $newdir = mkdir($uploaddirectory . "/" . $id, 0777);
						// }
					 
					 
							// $url = $uploaddir.'/'.$_FILES["file-3"]["name"][$key];

							// $filename = compress_image($_FILES["file-3"]["tmp_name"][$key], $url, 40);
							// $buffer = file_get_contents($url);

					}
					
					else 
					{
							$error = "Uploaded image should be jpg or gif or png";
					}
				
			}//End of if empty condition	
				}
				//End of foreach
			

			
			header("Location:Add-Hospital?response=add");
		}
		else if(isset($_POST['edit_hospital'])){	
		$updateProvider=mysqlUpdate('hosp_tab',$arrFields,$arrValues,"hosp_id='".$_POST['Hosp_Id']."'");	
					
		
				
			//End of foreach
		header("Location:Add-Hospital?response=update");
		}
	}
	//Send Error message
	else
	{
		header("Location:Add-Hospital?response=error");
	}
}

//ADD / UPDATE HOPITAL DOCTOR
if(isset($_POST['add_doctor']) || isset($_POST['edit_doctor'])){
	//Check Empty condition
	if(!empty($_POST['txtDoc']) || !empty($_POST['txtCountry']) || !empty($_POST['slctState']) || !empty($_POST['txtCity']) || !empty($_POST['selectHosp']) || !empty($_POST['slctSpec']))
	{
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$txtCountryId=$_POST['countryId'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['txtCity'];
	$slctHosp = addslashes($_POST['selectHosp']);	
	$slctSpec = $_POST['slctSpec'];
	$txtQual = addslashes($_POST['txtQual']);
	$txtExp = $_POST['txtExp'];
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = $_POST['txtEmail'];
	$txtWebsite = $_POST['txtWebsite'];
	$txtInterest = addslashes($_POST['txtInterest']);
	$txtContribute = addslashes($_POST['txtContribute']);
	$txtResearch = addslashes($_POST['txtResearch']);
	$txtPublication = addslashes($_POST['txtPublication']);
	$txtKeyword = addslashes($_POST['txtKeywords']);
	$txtNumOpinion = addslashes($_POST['numopinion']);
	$txtInOpcost = addslashes($_POST['inopcost']);
	$txtOnOpcost = addslashes($_POST['onopcost']);
	$txtConcharge = addslashes($_POST['conscharge']);
	$txtSecEmail = $_POST['txtSecEmail'];
	$txtSecPhone = $_POST['txtSecPhone'];
	$docImage = basename($_FILES['txtPhoto']['name']);	
	
	$teleOpCond = addslashes($_POST['teleop']);
		$telecontact = addslashes($_POST['teleopnumber']);
		$videoOpCond = addslashes($_POST['videoop']);
		$videocontact = addslashes($_POST['videoopnumber']);
		$teleoptiming = addslashes($_POST['televidop_time']);
		
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'ref_name';
		$arrValues[] = $txtDoc;
		if($slctSpec==""){
		$arrFields[] = 'doc_spec';
		$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'doc_spec';
		$arrValues[] = $slctSpec;	
		}
		$arrFields[] = 'ref_mail';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'ref_web';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		$arrFields[] = 'doc_type_val';
		$arrValues[] = "5";
		$arrFields[] = 'ref_address';
		$arrValues[] = $txtCity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'doc_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'doc_country_id';
		$arrValues[] = $txtCountryId;

		$arrFields[] = 'ref_exp';
		$arrValues[] = $txtExp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $txtInterest;
		$arrFields[] = 'doc_research';
		$arrValues[] = $txtResearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $txtContribute;
		$arrFields[] = 'doc_pub';
		$arrValues[] = $txtPublication;
		$arrFields[] = 'doc_keywords';
		$arrValues[] = $txtKeyword;
		$arrFields[] = 'contact_num';
		$arrValues[] = $txtMobile;
		
		$arrFields[] = 'numfreeop';
		$arrValues[] = $txtNumOpinion;
		$arrFields[] = 'in_op_cost';
		$arrValues[] = $txtInOpcost;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $txtOnOpcost;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $txtConcharge;
		
		$arrFields[] = 'secretary_phone';
		$arrValues[] = $txtSecPhone;
		$arrFields[] = 'secretary_email';
		$arrValues[] = $txtSecEmail;	
		
		if(!empty($docImage)){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		$arrFields[] = 'message_status';
		$arrValues[] = "1";
		$arrFields[] = 'company_id';
		$arrValues[] = $admin_id;
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $curDate;
		
		$arrFields[] = 'tele_op';
		$arrValues[] = $teleOpCond;
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		$arrFields[] = 'video_op';
		$arrValues[] = $videoOpCond;
		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming;
		
	
		
	/* IF $_POST['add_doctor'] VALUE IS TRUE, THEN ONLY DOCTORS RECORDS WILL INSERTED */
	
	if(isset($_POST['add_doctor'])){
	$usercraete=mysqlInsert('referal',$arrFields,$arrValues);
		$id = $usercraete;
		
		/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Doc";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
						
					// $uploaddirectory = realpath("../Doc");
					// mkdir("../Doc/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $docImage;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
						
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$usercreate=mysqlInsert('doctor_hosp',$arrFields1,$arrValues1);	
	
	$arrFields_slot[] = 'doc_id';
	$arrValues_slot[] = $id;
	$arrFields_slot[] = 'doc_type';
	$arrValues_slot[] = "1";	
	$arrFields_slot[] = 'hosp_id';
	$arrValues_slot[] = $slctHosp;
	$arrFields_slot[] = 'num_patient_hour';
	$arrValues_slot[] = $_POST['num_slot'];
	
	$docslotcreate=mysqlInsert('doc_appointment_slots',$arrFields_slot,$arrValues_slot);
	
	//Insert Doctors timings	
	for($i=1; $i<=$_POST['limit_i']; $i++)
	{
		$Timing_id=$_POST['time_id' . $i];
		for($j=1; $j<=$_POST['limit_j']; $j++)
		{
			$day_id=$_POST['day_id'. $i . $j];
			$time_limit=$_POST['time'. $i . $j];
			if($time_limit!=0){
			$arrFields_time = array();
			$arrValues_time = array();

			$arrFields_time[] = 'doc_id';
			$arrValues_time[] = $id;
			
			$arrFields_time[] = 'hosp_id';
		    $arrValues_time[] = $slctHosp;
			
			$arrFields_time[] = 'time_id';
			$arrValues_time[] = $Timing_id;
			
			$arrFields_time[] = 'day_id';
			$arrValues_time[] = $day_id;
			
			$arrFields_time[] = 'time_set';
			$arrValues_time[] = $time_limit;
			
			$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
					
			}
		}
	}
	mysqlDelete('doc_specialization',"doc_id='".$id."'");

				
					$arrFields_spec = array();
					$arrValues_spec = array();

					$arrFields_spec[] = 'doc_id';
					$arrValues_spec[] = $id;

					$arrFields_spec[] = 'spec_id';
					$arrValues_spec[] = $slctSpec;
					
					$arrFields_spec[] = 'doc_type';
					$arrValues_spec[] = "1";
											
					$insert_spec=mysqlInsert('doc_specialization',$arrFields_spec,$arrValues_spec);
					
					$arrFields_lang = array();
					$arrValues_lang = array();

					$arrFields_lang[] = 'doc_id';
					$arrValues_lang[] = $id;

					$arrFields_lang[] = 'language_id';
					$arrValues_lang[] = '1';
					
					$insert_spec1=mysqlInsert('doctor_langauges',$arrFields_lang,$arrValues_lang);
		
		
	header("Location:Add-Hospital-Doctors?response=add");
	}
	/* IF $_POST['edit_doctor'] VALUE IS TRUE, THEN ONLY DOCTORS RECORDS WILL UPDATED */
	else
	{	
		$updateProvider=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
		$arrFields1 =	array();
		$arrValues1	= 	array();
		$chkHosp 	= 	mysqlSelect("*","doctor_hosp ","doc_id='".$_POST['Prov_Id']."'","","","","");
		$id			=	$_POST['Prov_Id'];
				/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Doc";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					// $uploaddirectory = realpath("../Doc");
					// mkdir("../Doc/". "/" . $id, 0777);
					// $uploaddir = $uploaddirectory."/".$id;
					// $dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					// $photo = $docImage;
					// $uploadfile = $uploaddir . "/" . $photo;			
				
							
					// /* Moving uploaded file from temporary folder to desired folder. */
					// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					// } else {
						//echo "File cannot be uploaded";
					// }
				}
		if($chkHosp==true){
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$updateProvider=mysqlUpdate('doctor_hosp',$arrFields1,$arrValues1,"doc_id='".$_POST['Prov_Id']."'");
		} else {
		$arrFields1 = array();
		$arrValues1= array();
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $_POST['Prov_Id'];
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $slctHosp;
		$usercraete=mysqlInsert('doctor_hosp',$arrFields1,$arrValues1);
		}	
		
		$arrFields_slot[] = 'doc_id';
	$arrValues_slot[] = $_POST['Prov_Id'];
	$arrFields_slot[] = 'doc_type';
	$arrValues_slot[] = "1";	
	$arrFields_slot[] = 'hosp_id';
	$arrValues_slot[] = $slctHosp;
	$arrFields_slot[] = 'num_patient_hour';
	$arrValues_slot[] = $_POST['num_slot'];
	
	$chkSlot = mysqlSelect("*","doc_appointment_slots ","doc_id = '".$id."' and doc_type = '1' and hosp_id='".$slctHosp."'","","","","");
	if($chkSlot==true){
	$docslotcreate=mysqlUpdate('doc_appointment_slots',$arrFields_slot,$arrValues_slot,"doc_id = '".$id."' and doc_type = '1' and hosp_id='".$slctHosp."'");
	} else {
		$docslotcreate=mysqlInsert('doc_appointment_slots',$arrFields_slot,$arrValues_slot);
	}
	mysqlDelete('doc_time_set',"doc_id='".$_POST['Prov_Id']."'");
	
	for($i=1; $i<=$_POST['limit_i']; $i++)
	{
		$Timing_id=$_POST['time_id' . $i];
		for($j=1; $j<=$_POST['limit_j']; $j++)
		{
			$day_id=$_POST['day_id'. $i . $j];
			$time_limit=$_POST['time'. $i . $j];
			if($time_limit!=0){
			$arrFields_time = array();
			$arrValues_time = array();

			$arrFields_time[] = 'doc_id';
			$arrValues_time[] = $_POST['Prov_Id'];
			
			$arrFields_time[] = 'hosp_id';
		    $arrValues_time[] = $slctHosp;
			
			$arrFields_time[] = 'time_id';
			$arrValues_time[] = $Timing_id;
			
			$arrFields_time[] = 'day_id';
			$arrValues_time[] = $day_id;
			
			$arrFields_time[] = 'time_set';
			$arrValues_time[] = $time_limit;
			
			$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
					
			}
		}
	}
	
	mysqlDelete('doc_specialization',"doc_id='".$_POST['Prov_Id']."'");

				
					$arrFields_spec = array();
					$arrValues_spec = array();

					$arrFields_spec[] = 'doc_id';
					$arrValues_spec[] = $_POST['Prov_Id'];

					$arrFields_spec[] = 'spec_id';
					$arrValues_spec[] = $slctSpec;
					
					$arrFields_spec[] = 'doc_type';
					$arrValues_spec[] = "1";
											
					$insert_spec=mysqlInsert('doc_specialization',$arrFields_spec,$arrValues_spec);	
		
		
	header("Location:Add-Hospital-Doctors?response=update");
	}
	}
	//Send Error message
	else
	{
		header("Location:Add-Hospital-Doctors?response=error");
	}
}

//ADD / UPDATE HOPITAL AMENITIES
if(isset($_POST['add_amenity']) || isset($_POST['edit_amenity'])){
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp = $_POST['slctHosp'];	
	$docImage = basename($_FILES['file-3']['name']);
	
	foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
		$arrFields2 = array();
		$arrValues2 = array();

		$arrFields2[] = 'hosp_id';
		$arrValues2[] = $_POST['slctHosp'];

		$arrFields2[] = 'amenity_id';
		$arrValues2[] = $amenValue;
		
		$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
	}
	
	
	/* Add New Photoes to the perticular hosp_id */
	$chkHospPicture= mysqlSelect("*","add_hosp_picture","hosp_id='".$_POST['slctHosp']."'","","","","");

	
	//UPLOAD MULTIPLE IMAGES
	if($chkHospPicture==false){ 	
	
	$errors= array();
	foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
	
		
	$file_name = $_FILES['file-3']['name'][$key];
	$file_size =$_FILES['file-3']['size'][$key];
	$file_tmp =$_FILES['file-3']['tmp_name'][$key];
	$file_type=$_FILES['file-3']['type'][$key];
	
	
		$arrFields3 = array();
		$arrValues3 = array();

		$arrFields3[] = 'hosp_id';
		$arrValues3[] = $_POST['slctHosp'];

		$arrFields3[] = 'hosp_image';
		$arrValues3[] = $file_name;
		
			
			$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
			$id= $bslist_pht;
			
			


			//UPLOAD COMPRESSED IMAGE
			if ($_FILES["file-3"]["error"][$key] > 0) {
        			$error = $_FILES["file-3"]["error"][$key];
    		} 
    		else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
			($_FILES['file-3']['type'][$key] == "image/jpeg") || 
			($_FILES['file-3']['type'][$key] == "image/png") || 
			($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
			{
				$folder_name	=	"Hosp_image";
				$sub_folder		=	$id;
				$filename		=	$_FILES["file-3"]["name"][$key];
				$file_url		=	$_FILES["file-3"]['tmp_name'][$key];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			 
    		}
			else 
			{
        		$error = "Uploaded image should be jpg or gif or png";
    		}
		
			
		}
		//End of foreach
		
	}//end if
	header("Location:Add-Hospital-Amenity?response=add");	
} //end main if

//ADD ACTIVITY
if(isset($_POST['addActivity']) && !empty($_POST['txtDesc'])){
					$txtDesc = addslashes($_POST['txtDesc']);
	
					$arrFields = array();
					$arrValues = array();
										
					$arrFields[]= 'patient_id';
					$arrValues[]= $_POST['patient_id'];
					$arrFields[]= 'ref_id';
					$arrValues[]= $_POST['doc_id'];
					$arrFields[]= 'chat_note';
					$arrValues[]= $txtDesc;
					$arrFields[]= 'user_id';
					$arrValues[]= "0";
					$arrFields[]= 'TImestamp';
					$arrValues[]= $curDate;
					$arrFields[]= 'msg_send_status';
					$arrValues[]= $_POST['patient_response_send'];
					
					$patientNote=mysqlInsert('chat_notification',$arrFields,$arrValues);
					
					//Change Status2 condition
					$getPatInfo= mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
					$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
					$getBucket=$getPatInfo[0]['bucket_status']; //Get present patient status of perticular referral
					if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						
						$getRef = mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
						//NO. OF RESPONDED COUNT INCREMENTED BY ONE
						$TotCount=$getRef[0]['Tot_responded'];
						$TotCount=$TotCount+1;
						
						$arrFields3[]= 'Tot_responded';
						$arrValues3[]= $TotCount;
						$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['doc_id']."'");
						
						//Update response time 
						//RETREIVE DOCTOR'S FIRST REFERRED DATE
						$getDocResponse = mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","","","","");
													
						$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
						$datetime2 = new DateTime($curDate);
						$interval = $datetime1->diff($datetime2);
														
						$numdays=$interval->format('%a');
						$numhours=$interval->format('%H');
						$nummin=$interval->format('%i');
						$daystominute=$numdays*24*60;
						$hourstominute=$numhours*60;
						$totmin=$daystominute+$hourstominute+$nummin;
						
						$arrFields1[]= 'status2';
						$arrValues1[]= "5";
						$arrFields1[]= 'response_status';
						$arrValues1[]= "2";
						$arrFields1[]= 'response_time';
						$arrValues1[]= $totmin;
						
						//Bucket Status will update only when its below 5
						if($getBucket<5){
						$arrFields2[]= 'bucket_status';
						$arrValues2[]= "5";
						$updateBucket=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['patient_id']."'");
					
						}
					}
					
					/*$arrFields1[]= 'bucket_status';
					$arrValues1[]= $_POST['Pro4_status2'];*/
					$patientRef=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
					
					
					//Email Notification to patient
					$getSpec = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg=HOST_MAIN_URL."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
					
					
					$getPartnerRespSetting = mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatInfo[0]['patient_src']."'","","","","");
					
					$getMarketPerson = mysqlSelect("*","hosp_marketing_person as a left join mapping_hosp_referrer as b on a.person_id=b.market_person_id","b.partner_id='".$getPartnerRespSetting[0]['partner_id']."' and b.hosp_id='".$getPatInfo[0]['hosp_id']."'","","","","");
					
					
					//Check Doctor response should go to partner / patient directly
			if($_POST['patient_response_send']==1){ // 1 for response should go to patient with a copy to partner & Point of contact(Marketing Person)
					$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
						
			}
			else if($_POST['patient_response_send']==0){ // 0 for response should go only to partner
					//$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					//$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
			}
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
				
						$doctorresponse ="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$txtDesc."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($curDate))."</span></p></td></tr>";
						
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					//$param .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($mailto);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to patient
					if(!empty($patientnum)){
					
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") You have received the opinion from ".$getDocName." for your medical query. Check your registered email. Thx";
					send_msg($patientnum,$responsemsg);
					}
					//Message Notification to partners
					if(!empty($partnernum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thanks";
					send_msg($partnernum,$responsemsg);
					}
					
				//Push notification for partners		
					if(!empty($getPartnerRespSetting[0]['gcm_tokenid'])){			
								
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Many Thanks";
					$subtit= $getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].")";
					$pushDesc=strip_tags($responsemsg);
					$msg="";
					$title="Doctors Response";
					$subtitle=strip_tags($subtit);
					$tickerText="Doctors Response";
					$type="4"; //For Other Message
					$patientid=$getPatInfo[0]['patient_id'];
					$docid=$getPatInfo[0]['ref_id'];
					$blog_id="0";
					$largeimg='large_icon';	
					$regid=$getPartnerRespSetting[0]['gcm_tokenid'];
					$postkey=time();
					
					if(!empty($getPatInfo[0]['doc_photo'])){ 
					$smalimg=HOST_MAIN_URL."/Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}else{
					$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
					}
					
					push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
					push_notification_Aster_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
		
					}		
					
					//Message Notification to Marketing person
					if(!empty($marketnum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thanks";
					send_msg($marketnum,$responsemsg);
					}
					
					$response=1;	
					header('location:patient-history?response='.$response.'&p='.$_POST['ency_patient_id']);					
					
	
}

if(isset($_POST['ref_appointment']))
{
	$chkInDate 	= $_POST['visit_date'];
	$chkInTime 	= $_POST['visit_time_id'];
	$chkInTime_slot = $_POST['visit_time'];
	if(!empty($_POST['visit_date']) && !empty($_SESSION['visit_time_id']))
	{
		$chkInDate = $_POST['visit_date'];
		$chkInTime = $_POST['visit_time_id'];
		$status="Pending";
	}
	else if(empty($_POST['visit_date']) && empty($_POST['visit_time_id']))
	{
		
		$status="At reception";
	}
	
	$txtName	 = $_POST['se_pat_name'];
	$txtAge 	 = $_POST['se_pat_age'];
	$txtMail 	 = $_POST['se_email'];
	$txtGen 	 = $_POST['se_gender'];
	$txtContact  = addslashes($_POST['se_con_per']);
	$txtMob 	 = addslashes($_POST['se_phone_no']);
	$txtAddress  = addslashes($_POST['se_address']);
	$txtLoc      = addslashes($_POST['se_city']);
	$txtCountry  = addslashes($_POST['se_country']);
	$txtState    = addslashes($_POST['se_state']);
	$hosp_id 	 = $_POST['se_hosp'];
	$doc_id      = $_POST['se_doc'];
	$teleCom     = 0;
	$patConsent  = $_POST['chkPatConsent'];
	$txtRef_id 	 = addslashes($_POST['reference_from']);
	$txtRef_Hosp = addslashes($_POST['reference_hosp']);
	$txtRef_Doc  = addslashes($_POST['refering_doc']);
	$refNoteAttach = addslashes($_FILES['txtReferalNote']['name']);
	$docspec 	   = addslashes($_SESSION['docspec']);
	$transid = time();
	
	$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$doc_id."'");
	$appointType = $_SESSION['appointment_type'];
	
	if($appointType == "2")
	{
		$status="VC Confirmed";
		$teleCom=1;
	}
	
	$arrFields_patient[] = 'patient_age';
	$arrValues_patient[] = $txtAge;

	$arrFields_patient[] = 'patient_email';
	$arrValues_patient[] = $txtMail;

	$arrFields_patient[] = 'patient_gen';
	$arrValues_patient[] = $txtGen;
	
	
	if(!empty($_POST['date_birth']))
	{
		$arrFields_patient[] = 'DOB';
		$arrValues_patient[] = $dob;
	}

	$arrFields_patient[] = 'patient_mob';
	$arrValues_patient[] = $txtMob;

	
			
	if(empty($_POST['patient_id']))
	{
	
		$arrFields_patient[] = 'patient_name';
		$arrValues_patient[] = $txtName;
		
		$arrFields_patient[] = 'member_id';
		$arrValues_patient[] = '';

		$arrFields_patient[] = 'login_id';
		$arrValues_patient[] = '';//$txtContact;

		$arrFields_patient[] = 'created_date';
		$arrValues_patient[] = $curDate;

		
		$patientcreate	=	mysqlInsert('patients_appointment',$arrFields_patient,$arrValues_patient); // doc_my_patient to " patients_appointment "
		$patientid 		= 	$patientcreate;
		$getPatInfo 	= 	mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
		
	}	
	else
	{
		
		$patientid  = $_POST['patient_id'];
		$getPatInfo = mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
		$userupdate = mysqlUpdate('patients_appointment',$arrFields_patient,$arrValues_patient, "patient_id = '". $_POST['patient_id'] ."' ");
		
		
		
	}
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[] = 'patient_id';
	$arrValues1[] = $patientid;

	$arrFields1[] = 'service_type';
	$arrValues1[] = $appointType; 

	$arrFields1[] = 'transaction_id';
	$arrValues1[] = $transid;

	$arrFields1[] = 'payment_id'; // empty
	$arrValues1[] = '';

	$arrFields1[] = 'doc_id';
	$arrValues1[] = $doc_id;

	$arrFields1[] = 'hosp_id';
	$arrValues1[] = $hosp_id;

	$arrFields1[] = 'contact_person';
	$arrValues1[] = $txtName;

	$arrFields1[] = 'patient_age';
	$arrValues1[] = $txtAge;

	$arrFields1[] = 'address';
	$arrValues1[] = $txtAddress;

	$arrFields1[] = 'city';
	$arrValues1[] = $txtLoc; 

	$arrFields1[] = 'state';
	$arrValues1[] = $txtState;

	$arrFields1[] = 'country';
	$arrValues1[] = $txtCountry;

	$arrFields1[] = 'height_cms';
	$arrValues1[] = $txtHeight;

	$arrFields1[] = 'weight';
	$arrValues1[] = $txtWeight;

	$arrFields1[] = 'hyper_cond';
	$arrValues1[] = $txthypertension;

	$arrFields1[] = 'diabetes_cond';
	$arrValues1[] = $txtdiabetic;

	$arrFields1[] = 'smoking'; // smoking
	$arrValues1[] = '';//$smoking;

	$arrFields1[] = 'alcoholic';
	$arrValues1[] = '';//$alcoholic;

	$arrFields1[] = 'blood_group';
	$arrValues1[] = $txtBlood;

	$arrFields1[] = 'drug_abuse';
	$arrValues1[] = 'drug_abuse'; // empty 

	$arrFields1[] = 'other_details';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'family_history';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'prev_intervention';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'neuro_issue';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'kidney_issue';
	$arrValues1[] = ''; // empty 

	$arrFields1[] = 'pat_bp';
	$arrValues1[] = $txtbp; 

	$arrFields1[] = 'pat_thyroid';
	$arrValues1[] = $txtthyroid; 

	$arrFields1[] = 'pat_cholestrole';
	$arrValues1[] = $txtcholestrol; 

	$arrFields1[] = 'pat_epilepsy';
	$arrValues1[] = $txtepilepsy; 
	
	$arrFields1[] = 'pat_asthama';
	$arrValues1[] = $txtxtasthamatbp; 

	$arrFields1[] = 'allergies_any';
	$arrValues1[] = $txtallergies; 

	$arrFields1[] = 'subscriber_id'; // from subscriber table( empty )
	$arrValues1[] = '';//$subscriber_id; 

	

	$arrFields1[] = 'user_type';
	$arrValues1[] = '1';

	

	$arrFields1[] = 'Visiting_date';
	$arrValues1[] = date('Y-m-d',strtotime($chkInDate));

	$arrFields1[] = 'Visiting_time';
	$arrValues1[] = $chkInTime;

	$arrFields1[] = 'time_slot';
	$arrValues1[] = $chkInTime_slot;

	$arrFields1[] = 'amount';
	$arrValues1[] = '';

	$arrFields1[] = 'currency_type'; 
	$arrValues1[] = '';

	$arrFields1[] = 'pay_status'; 
	$arrValues1[] = $status;

	$arrFields1[] = 'visit_status';
	$arrValues1[] = "new_visit";

	$arrFields1[] = 'patientEMR_consent';
	$arrValues1[] = $patConsent;

	$arrFields1[] = 'reference_id';
	$arrValues1[] = $txtRef_id;

	$arrFields1[] = 'referring_hosp';
	$arrValues1[] = $txtRef_Hosp;

	$arrFields1[] = 'referring_doc';
	$arrValues1[] = $txtRef_Doc;
	
	if(!empty($_FILES['txtReferalNote']['name']))
	{
		$arrFields1[]="referal_note";
		$arrValues1[]=$refNoteAttach;
	}

	$arrFields1[] = "created_date";
	$arrValues1[] = $curDate;
	
	
	$createappointment=mysqlInsert('patients_transactions',$arrFields1,$arrValues1);

	$getTime	=	mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	$arrFieldsAppSlot = array();
	$arrValuesAppSlot = array();

	$arrFieldsAppSlot[] = 'patient_trans_id';
	$arrValuesAppSlot[] = $transid;
				
	if(empty($_POST['visit_date']) && empty($_POST['visit_time']))
	{
		//Check Last Appointment Token No
		$getLastAppInfo = mysqlSelect("*","patients_token_system"," patient_trans_id='".$transid."' and token_no!='555'" ,"token_no desc","","","");
		if(COUNT($getLastAppInfo)>0)
		{
			$getTokenNo = $getLastAppInfo[0]['token_no']+1;
		}
		else
		{
			$getTokenNo = 1;
		}
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = $getTokenNo;
	}
	else if(!empty($_POST['visit_date']) && !empty($_POST['visit_time']))
	{
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = "555"; //For Online Booking
	}

	$arrFieldsAppSlot[] = 'created_date';
	$arrValuesAppSlot[] = $curDate;

	$createappointment	=	mysqlUpdate('patients_token_system',$arrFieldsAppSlot,$arrValuesAppSlot,"patient_trans_id='".$_POST['trans_id']."'");
	$arrFieldsPat	=	array();
	$arrValuesPat	=	array();

	$arrFieldsPat[] = 'doc_video_link';
	$arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=1&r=".$admin_id."_".$getPatInfo[0]['patient_id']."_".$transid;				
	$arrFieldsPat[] = 'pat_video_link';
	$arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=2&r=".$admin_id."_".$getPatInfo[0]['patient_id']."_".$transid;
	
	$getPatInfo1 = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$patientid."'" ,"","","","");
	
	if(basename($_FILES['txtReferalNote']['name']!==""))
	{ 
		$folder_name	=	"premium/referalNoteAttach";
		$sub_folder		=	$patientid;
		$filename		=	$_FILES["txtReferalNote"]["name"];
		$file_url		=	$_FILES["txtReferalNote"]['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	} 
	
	//Patient Info EMAIL notification Sent to Doctor
	if(!empty($get_pro[0]['ref_mail']))
	{
		$PatAddress=$getPatInfo1[0]['patient_addrs'].",<br>".$getPatInfo1[0]['patient_loc'].", ".$getPatInfo1[0]['pat_state'].", ".$getPatInfo1[0]['pat_country'];
		$url_page = 'pat_appointment_info.php';
		$url = rawurlencode($url_page);
		$url .= "?patname=".urlencode($getPatInfo1[0]['patient_name']);
		$url .= "&patID=".urlencode($getPatInfo1[0]['patient_id']);
		$url .= "&patAddress=".urlencode($PatAddress);
		$url .= "&patContact=".urlencode($getPatInfo1[0]['patient_mob']);
		$url .= "&patEmail=".urlencode($getPatInfo1[0]['patient_email']);
		$url .= "&patContactName=" . urlencode($getPatInfo1[0]['contact_person']);
		$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
		$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
		$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
		$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
		$url .= "&ccmail=" . urlencode($ccmail);	
		$url .= "&replymail=" . urlencode($getPatInfo1[0]['patient_email']);						
		send_mail($url);	
	}

		//Save for Appointment Payment Transaction
		if(!empty($_POST['consult_charge']))
		{
			$arrFieldsPayment=array();	
			$arrValuesPayment=array();
			
			$arrFieldsPayment[]='patient_name';
			$arrValuesPayment[]=$getPatInfo1[0]['patient_name'];
			$arrFieldsPayment[]='patient_id';
			$arrValuesPayment[]=$getPatInfo1[0]['patient_id'];
			$arrFieldsPayment[]='trans_date';
			$arrValuesPayment[]=$curDate;
			$arrFieldsPayment[]='narration';
			$arrValuesPayment[]="Consultation Charge";
			$arrFieldsPayment[]='amount';
			$arrValuesPayment[]=$_POST['consult_charge'];
			$arrFieldsPayment[]='user_id';
			$arrValuesPayment[]=$doc_id;
			$arrFieldsPayment[]='user_type';
			$arrValuesPayment[]="1";
			$arrFieldsPayment[]='hosp_id';
			$arrValuesPayment[]=$hosp_id;
			$arrFieldsPayment[]='payment_status';
			$arrValuesPayment[]="PAID";
			$arrFieldsPayment[]='pay_method';
			$arrValuesPayment[]="Cash";
			$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
		}
		//Save for Appointment Payment Transaction ends here
		
	//Send SMS to patient
	//$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo1[0]['patient_id']);
	
	$link = HOST_MAIN_URL."premium/Patient-Profile-Details?d=" . md5($doc_id)."&p=" . md5($getPatInfo1[0]['patient_id'])."&t=".$transid;//HOST_MAIN_URL."premium/Patient-Attachments?d=" . md5($getPatInfo1[0]['patient_id']);
	
	//Get Shorten Url
	//$getUrl= get_shorturl($longurl);	
	
	//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
	$msg= "Hello ".$getPatInfo1[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$link." Thanks";

	send_msg($txtMob,$msg);
	
		$toEmail=$getPatInfo1[0]['patient_email'];
		$mailSubject='Appointment Request';  
		$fromContent='Appointment';
		$email='medical@medisense.me';
		$contentSection="Dear ".$getPatInfo1[0]['patient_name'].",<br/> Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].".<br/> If you have any reports, upload here: ".$link." <br/>Thanks";
				
						
				$url_page = 'send_medical_tourism_email.php';
				$url = rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($email);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
						
	//Send Payment Receipt to patient
	if($_POST['chkReceipt']=="1")
	{
		$recieptmsg= "Dear ".$getPatInfo1[0]['patient_name'].", We have successfully received your payment. Transaction Details :  Rs. ".$_POST['consult_charge']." on ".date('d/M/Y, H:i a',strtotime($curDate)).". Thanks ".$get_pro[0]['ref_name'];
		send_msg($txtMob,$recieptmsg);
	}
	$checkAppLink = mysqlSelect("login_id","login_user","sub_contact='".$txtMob."'" ,"","","","");
	if(count($checkAppLink)==0)
	{			
		$offlineMsg="Welcome to Medisense Healthcare App. Download the patient app Now! \n Download link - https://goo.gl/u8P5us \n Thanks Medisense";
		send_msg($txtMob,$offlineMsg);	
	}
	unset($_SESSION['visit_date']);	
	unset($_SESSION['visit_time']);
	$response="appointment-success";
	header("Location:Appointments?response=".$response);
}

//corporate appointment
if(isset($_POST['corporate_appointment'])){

	$chkInDate = $_POST['check_date'];
	$chkInTime = $_POST['check_time'];
	
	if(!empty($_SESSION['visit_date']) && !empty($_SESSION['visit_time']))
	{
		$chkInDate = $_SESSION['visit_date'];
		$chkInTime = $_SESSION['visit_time'];
		
		$status="Pending";
	}
	else if(empty($_SESSION['visit_date']) && empty($_SESSION['visit_time']))
	{
	
		$chkInDate = date('Y-m-d'); //Current Date
	
	    
		
		$status="At reception";
	}
	
	$txtName = $_POST['se_pat_name'];
	$txtAge = $_POST['se_pat_age'];
	$txtMail = $_POST['se_email'];
	$txtGen = $_POST['se_gender'];
	//$chkDate = date('Y-m-d',strtotime($Cur_Date));
	
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob = addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc = addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState = addslashes($_POST['se_state']);
	
	$hosp_id = $_POST['se_hosp'];
	$doc_id = $_POST['se_doc'];
	$teleCom = 0;//$_POST['chkTeleCom'];
	$patConsent = $_POST['chkPatConsent'];
	
	$txtRef_id = addslashes($_POST['reference_from']);
	$txtRef_Hosp = addslashes($_POST['reference_hosp']);
	$txtRef_Doc = addslashes($_POST['refering_doc']);
	$refNoteAttach = addslashes($_FILES['txtReferalNote']['name']);
	
	$docspec = addslashes($_SESSION['docspec']);
	$transid=time();
	$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$doc_id."'");
	$appointType = $_SESSION['appointment_type'];
	
	if($appointType == "2"){
		$status="VC Confirmed";
		$teleCom=1;
	}
	
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
			$arrFields_patient[] = 'doc_id';
			$arrValues_patient[] = $doc_id;

			$arrFields_patient[] = 'system_date';
			$arrValues_patient[] = date('Y-m-d');
			
			$arrFields_patient[] = 'TImestamp';
			$arrValues_patient[] = $curDate;
		
			$arrFields_patient[] = 'transaction_id';
			$arrValues_patient[] = $transid;
			
	if(empty($_POST['patient_id'])){
		$arrFields_subscriber = array();
		$arrValues_subscriber = array();

		$arrFields_subscriber[] = 'employee_name';
		$arrValues_subscriber[] = $txtName;
		$arrFields_subscriber[] = 'subscribing_company_id';
		$arrValues_subscriber[] = $_POST['se_sub_comp'];
		$arrFields_subscriber[] = 'email_id';
		$arrValues_subscriber[] = $txtMail;
		$arrFields_subscriber[] = 'mobile_num';
		$arrValues_subscriber[] = $txtMob;
		$arrFields_subscriber[] = 'address';
		$arrValues_subscriber[] = $txtAddress;
		$arrFields_subscriber[] = 'country';
		$arrValues_subscriber[] = $txtCountry;
		$arrFields_subscriber[] = 'state';
		$arrValues_subscriber[] = $txtState;
		$arrFields_subscriber[] = 'city';
		$arrValues_subscriber[] = $txtLoc;
		$arrFields_subscriber[] = 'status';
		$arrValues_subscriber[] = '1';
		
		$usercraete=mysqlInsert('subscribers',$arrFields_subscriber,$arrValues_subscriber);
		$sub_id= $usercraete;
		
		
		
		$arrFields_patient[] = 'subscriber_id';
		$arrValues_patient[] = $sub_id;
		$patientcreate=mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = $patientcreate;  //Get Patient Id
		
		$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
	}	
	else
	{
		$arrFields_patient[] = 'subscriber_id';
		$arrValues_patient[] = $_POST['patient_id'];
		
		$patientcreate=mysqlInsert('doc_my_patient',$arrFields_patient,$arrValues_patient);
		$patientid = $patientcreate;  //Get Patient Id
		
		
		
		$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		//$patientid = $_POST['patient_id'];
		//$getPatInfo = mysqlSelect("*","doc_my_patient","patient_id='".$patientid."'" ,"","","","");
		//$userupdate=mysqlUpdate('doc_my_patient',$arrFields_patient,$arrValues_patient, "patient_id = '". $_POST['patient_id'] ."' ");
	}

				$arrFields1 = array();
				$arrValues1 = array();
				
				$arrFields1[] = 'appoint_trans_id';
				$arrValues1[] = $transid;
				$arrFields1[] = 'patient_id';
				$arrValues1[] = $patientid;
				$arrFields1[] = 'pref_doc';
				$arrValues1[] = $doc_id;
				$arrFields1[] = 'hosp_id';
				$arrValues1[] = $hosp_id;
				$arrFields1[] = 'department';
				$arrValues1[] = $get_pro[0]['doc_spec'];
				$arrFields1[] = 'Visiting_date';
				$arrValues1[] = date('Y-m-d',strtotime($chkInDate));
				$arrFields1[] = 'Visiting_time';
				$arrValues1[] = $chkInTime;
				$arrFields1[] = 'patient_name';
				$arrValues1[] = $txtName;
				$arrFields1[] = 'Mobile_no';
				$arrValues1[] = $txtMob;
				$arrFields1[] = 'Email_address';
				$arrValues1[] = $txtMail;
				$arrFields1[] = 'reference_id';
				$arrValues1[] = $txtRef_id;
				$arrFields1[] = 'referring_hosp';
				$arrValues1[] = $txtRef_Hosp;
				$arrFields1[] = 'referring_doc';
				$arrValues1[] = $txtRef_Doc;
				
				$arrFields1[] = 'tele_communication';
				$arrValues1[] = $teleCom;
				$arrFields1[] = 'patientEMR_consent';
				$arrValues1[] = $patConsent;
				$arrFields1[] = 'appointment_type';
				$arrValues1[] = $appointType;
				
				$arrFields1[] = 'pay_status';
				$arrValues1[] = $status;
				$arrFields1[] = 'visit_status';
				$arrValues1[] = "new_visit";
				$arrFields1[] = 'Time_stamp';
				$arrValues1[] = $curDate;
				
				$createappointment=mysqlInsert('appointment_transaction_detail',$arrFields1,$arrValues1);
				
			$getTime=mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
			//Purpose:Appointment token System
				
				
				$arrFieldsAppSlot = array();
				$arrValuesAppSlot = array();
				
				if(empty($_SESSION['visit_date']) && empty($_SESSION['visit_time']))
				{
					//Check Last Appointment Token No
					$getLastAppInfo = mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($chkInDate))."' and doc_id='".$doc_id."' and doc_type='1' and hosp_id='".$hosp_id."' and token_no!='555'" ,"token_no desc","","","");
					if(COUNT($getLastAppInfo)>0){
						$getTokenNo = $getLastAppInfo[0]['token_no']+1;
					}
					else{
						$getTokenNo = 1;
					}
				
					$arrFieldsAppSlot[] = 'token_no';
					$arrValuesAppSlot[] = $getTokenNo;
				}
				else if(!empty($_SESSION['visit_date']) && !empty($_SESSION['visit_time'])){
					$arrFieldsAppSlot[] = 'token_no';
					$arrValuesAppSlot[] = "555"; //For Online Booking
				}
				$arrFieldsAppSlot[] = 'patient_id';
				$arrValuesAppSlot[] = $patientid;
				$arrFieldsAppSlot[] = 'appoint_trans_id';
				$arrValuesAppSlot[] = $transid;
				$arrFieldsAppSlot[] = 'patient_name';
				$arrValuesAppSlot[] = $txtName;
				$arrFieldsAppSlot[] = 'doc_id';
				$arrValuesAppSlot[] = $doc_id;
				$arrFieldsAppSlot[] = 'doc_type';
				$arrValuesAppSlot[] = "1";
				$arrFieldsAppSlot[] = 'hosp_id';
				$arrValuesAppSlot[] = $hosp_id;
				$arrFieldsAppSlot[] = 'status';
				$arrValuesAppSlot[] = $status;
				$arrFieldsAppSlot[] = 'reference_id';
				$arrValuesAppSlot[] = $txtRef_id;
				$arrFieldsAppSlot[] = 'referring_hosp';
				$arrValuesAppSlot[] = $txtRef_Hosp;
				$arrFieldsAppSlot[] = 'referring_doc';
				$arrValuesAppSlot[] = $txtRef_Doc;
				
				$arrFieldsAppSlot[] = 'tele_communication';
				$arrValuesAppSlot[] = $teleCom;				
				$arrFieldsAppSlot[] = 'patientEMR_consent';
				$arrValuesAppSlot[] = $patConsent;
				$arrFieldsAppSlot[] = 'appointment_type';
				$arrValuesAppSlot[] = $appointType;
				
				if(!empty($_FILES['txtReferalNote']['name'])){
			$arrFieldsAppSlot[]="referal_note";
			$arrValuesAppSlot[]=$refNoteAttach;
			}
				$arrFieldsAppSlot[] = 'app_date';
				$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
				$arrFieldsAppSlot[] = 'app_time';
				$arrValuesAppSlot[] = $getTime[0]['Timing'];				
				$arrFieldsAppSlot[] = 'created_date';
				$arrValuesAppSlot[] = $curDate;
				$createappointment=mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
		
		$arrFieldsPat[] = 'doc_video_link';
			  $arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=1&r=".$doc_id."_".$getPatInfo[0]['patient_id']."_".$transid;				
			  $arrFieldsPat[] = 'pat_video_link';
			  $arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=2&r=".$doc_id."_".$getPatInfo[0]['patient_id']."_".$transid;;
		$userupdate1=mysqlUpdate('doc_my_patient',$arrFieldsPat,$arrValuesPat, "patient_id = '". $getPatInfo[0]['patient_id'] ."' ");
				
	if(basename($_FILES['txtReferalNote']['name']!==""))
	{ 
		$folder_name	=	"premium/referalNoteAttach";
		$sub_folder		=	$patientid;
		$filename		=	$_FILES["txtReferalNote"]["name"];
		$file_url		=	$_FILES["txtReferalNote"]['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("../premium/referalNoteAttach");
		// mkdir("../premium/referalNoteAttach/". "/" . $patientid, 0777);
		// $uploaddir = $uploaddirectory."/".$patientid;
		// $dotpos = strpos($refNoteAttach, '.');
		// $photo = $refNoteAttach;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder.  */
		// if(move_uploaded_file ($_FILES['txtReferalNote']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	} 
	
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($get_pro[0]['ref_mail'])){
		$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
		
					$url_page = 'pat_appointment_info.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
					$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
					$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
					$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
					$url .= "&ccmail=" . urlencode($ccmail);	
					$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
					send_mail($url);	
		}

					//Save for Appointment Payment Transaction
					if(!empty($_POST['consult_charge']))
					{
						$arrFieldsPayment=array();	
						$arrValuesPayment=array();
						
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$getPatInfo[0]['patient_name'];
						$arrFieldsPayment[]='patient_id';
						$arrValuesPayment[]=$getPatInfo[0]['patient_id'];
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$curDate;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]="Consultation Charge";
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$_POST['consult_charge'];
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$doc_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]="1";
						$arrFieldsPayment[]='hosp_id';
						$arrValuesPayment[]=$hosp_id;
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]="PAID";
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]="Cash";
						$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
					}
					//Save for Appointment Payment Transaction ends here
					
	//Send SMS to patient
//	$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	$link = HOST_MAIN_URL."premium/Patient-Profile-Details?d=" . md5($doc_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;//HOST_MAIN_URL."premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	//Get Shorten Url
	//$getUrl= get_shorturl($longurl);	
	
	//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
	$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here: ".$link." Thanks";

	send_msg($txtMob,$msg);
	
	 $toEmail=$getPatInfo[0]['patient_email'];
		$mailSubject='Appointment Request';  
		$fromContent='Appointment';
		$email='medical@medisense.me';
		$contentSection="Dear ".$getPatInfo[0]['patient_name'].",<br/> Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].".<br/> If you have any reports, upload here:".$link." <br/>Thanks";
				
						
				$url_page = 'send_medical_tourism_email.php';
				$url = rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($email);
				$url .= "&fromContent=".urlencode($fromContent);
						send_mail($url);
	
	//Send Payment Receipt to patient
	if($_POST['chkReceipt']=="1"){
	$recieptmsg= "Dear ".$getPatInfo[0]['patient_name'].", We have successfully received your payment. Transaction Details :  Rs. ".$_POST['consult_charge']." on ".date('d/M/Y, H:i a',strtotime($curDate)).". Thanks ".$get_pro[0]['ref_name'];

	send_msg($txtMob,$recieptmsg);
	}
	
	$checkAppLink = mysqlSelect("login_id","login_user","sub_contact='".$txtMob."'" ,"","","","");
	if(count($checkAppLink)==0){			
	$offlineMsg="Welcome to Medisense Healthcare App. Download the patient app Now! \n Download link - https://goo.gl/u8P5us \n Thanks Medisense";
	send_msg($txtMob,$offlineMsg);	
	}
	
	unset($_SESSION['visit_date']);	
	unset($_SESSION['visit_time']);
	$response="appointment-success";
	header("Location:Corporate-Appointment?response=".$response);

	
}

//ADD / UPDATE SUBSCRIBES DETAILS
if(isset($_POST['add_subscribe']) || isset($_POST['edit_subscribe'])){ 
	//Check Empty condition
	if(!empty($_POST['txtSubsName']) || !empty($_POST['slctComm']))
	{ 
	$txtSubsName = addslashes($_POST['txtSubsName']);
	$txtemplId = addslashes($_POST['txtemplId']);
	$txtAddress = addslashes($_POST['txtAddress']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$Subsid = addslashes($_POST['Subsid']); 
	$txtCity = addslashes($_POST['txtCity']);
	$scid = addslashes($_POST['scid']);
	
	$txtMobile = $_POST['txtMobile'];
 	$txtEmail = addslashes($_POST['txtEmail']);
	
	
	$checkCompany = mysqlSelect("*","subscribing_company","md5(id)='".$scid."'" ,"","","","");
	

	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'employee_name';
		$arrValues[] = $txtSubsName;
		$arrFields[] = 'employee_id';
		$arrValues[] = $txtemplId;
		$arrFields[] = 'subscribing_company_id';
		$arrValues[] = $checkCompany[0]['id'];
		$arrFields[] = 'email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'mobile_num';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'address';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'state';
		$arrValues[] = $slctState;
		$arrFields[] = 'city';
		$arrValues[] = $txtCity;
		$arrFields[] = 'status';
		$arrValues[] = '1';
		
		$check_referring = mysqlSelect('*','login_user',"sub_contact='".$txtMobile."' OR sub_email='".$txtEmail."'","","","","");

	if(empty($check_referring))
	{
		$otp = randomOtp();
		$password = randomPassword();
		$arrFields_user[] = 'sub_name';
		$arrValues_user[] = $txtSubsName;
		$arrFields_user[] = 'sub_contact';
		$arrValues_user[] = $txtMobile;
		$arrFields_user[] = 'sub_email';
		$arrValues_user[] = $txtEmail;
		$arrFields_user[] = 'otp';
		$arrValues_user[] = $otp;
		$arrFields_user[] = 'sub_country';
		$arrValues_user[] = $txtCountry;
		$arrFields_user[] = 'passwd';
		$arrValues_user[] = md5($password);
		$arrFields_user[] = 'login_status';
		$arrValues_user[] = '1';
		$arrFields_user[] = 'verification_status';
		$arrValues_user[] = '1';
		$arrFields_user[] = 'login_permission';
		$arrValues_user[] = '1';

		$usercreate=mysqlInsert('login_user',$arrFields_user,$arrValues_user);
		$id = $usercreate;
		
		
		
		$arrFields_src[] = 'source_name';
		$arrValues_src[] = $txtSubsName;
		$arrFields_src[] = 'partner_id';
		$arrValues_src[] = $id;
		$arrFields_src[] = 'src_type';
		$arrValues_src[] = '1';
		
		$userSrccreate=mysqlInsert('source_list',$arrFields_src,$arrValues_src);
		
		
		$arrFields_member[] = 'member_name';
		$arrValues_member[] = $txtSubsName;
		$arrFields_member[] = 'member_type';
		$arrValues_member[] = 'primary';
		$arrFields_member[] = 'user_id';
		$arrValues_member[] = $id;
		
		$userMember=mysqlInsert('user_family_member',$arrFields_member,$arrValues_member);	
	}	
	else
	{
		
		$arrFields_user[] = 'sub_name';
		$arrValues_user[] = $txtSubsName;
		$arrFields_user[] = 'sub_contact';
		$arrValues_user[] = $txtMobile;
		$arrFields_user[] = 'sub_email';
		$arrValues_user[] = $txtEmail;
		$arrFields_user[] = 'sub_country';
		$arrValues_user[] = $txtCountry;
		
		$updateMapping=mysqlUpdate('login_user',$arrFields_user,$arrValues_user,"login_id='".$check_referring[0]['login_id']."'");
		
		$arrFields_src[] = 'source_name';
		$arrValues_src[] = $txtSubsName;
		$updateMapping1=mysqlUpdate('source_list',$arrFields_src,$arrValues_src,"partner_id='".$check_referring[0]['login_id']."'");
		
		$arrFields_member[] = 'member_name';
		$arrValues_member[] = $txtSubsName;
		$updateMapping2=mysqlUpdate('user_family_member',$arrFields_member,$arrValues_member,"user_id='".$check_referring[0]['login_id']."' and member_type='primary'");
		
	}	
				
	if(isset($_POST['add_subscribe'])){
		
			$usercraete=mysqlInsert('subscribers',$arrFields,$arrValues);
			//var_dump($usercraete) ; exit;
			$id= $usercraete;
			
			
			
			if(!empty($txtEmail)) {
				
				$toEmail=$txtEmail;
				$replyEmail=$checkCompany[0]['email_id'];
				$mailSubject='Welcome to Medisense Health Care !!!';  
				$fromContent='Medisense';
				$contentSection='Dear '.$txtSubsName.',<br/><br/> Your Medisense Healthcare Link will be: <br/> Link: '.HOST_HEALTH_URL.'Medisense-Patient-Care/Login <br/> You can register the account by using your registered Email ID: <b>'.$txtEmail.'</b> , Mobile Number: <b>'.$txtMobile.'</b> , Password: <b>'.$password.'</b>  <br/><br/> Many Thanks';
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($replyEmail);
				$url .= "&fromContent=".urlencode($fromContent);
				send_mail($url);
			}
			
			header("Location:Subscribes?response=add&company_code=".$checkCompany[0]['company_code']."&scid=".$scid);
		}
		else if(isset($_POST['edit_subscribe'])){
		
			
		$updateProvider=mysqlUpdate('subscribers',$arrFields,$arrValues,"id='".$Subsid."'");	
					
		
				
			//End of foreach
		header("Location:Subscribes?response=update&company_code=".$checkCompany[0]['company_code']."&scid=".$scid);
		}
	}
	//Send Error message
	else
	{
		header("Location:Subscribes?response=error");
	}
}



//ADD Subscribe
if(isset($_POST['add_subscribe_doc']) || isset($_POST['update_referred'])){
	$subscribe_company_name=addslashes($_POST['subscribe_company_name']);	
	$company_code=addslashes($_POST['company_code']);
	$subs_start_date=addslashes($_POST['subs_start_date']);
	$subs_end_date=addslashes($_POST['subs_end_date']);
	$subs_address=addslashes($_POST['subs_address']);
	$no_of_emp=addslashes($_POST['no_of_emp']);
	$no_of_dep=addslashes($_POST['no_of_dep']);
	
// $getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","","","","");

	$arrFields[] = 'company_name';
	$arrValues[] = $subscribe_company_name;
	$arrFields[] = 'company_code';
	$arrValues[] = $company_code;
	$arrFields[] = 'subscription_start_date';
	$arrValues[] = $subs_start_date;
	$arrFields[] = 'subscription_end_date';
	$arrValues[] = $subs_end_date;
	$arrFields[] = 'address';
	$arrValues[] = $subs_address;
	$arrFields[] = 'num_employees';
	$arrValues[] = $no_of_emp;
	$arrFields[] = 'num_dependants';
	$arrValues[] = $no_of_dep;
	
	// $arrFields[] = 'hosp_id';
	// $arrValues[] = $admin_id;
	// $arrFields[] = 'company_id';
	// $arrValues[] = $getDocDetails[0]['company_id'];
	
	if(isset($_POST['add_subscribe_doc'])){
	//$arrFields[] = 'doc_id';
	//$arrValues[] = $admin_id;
	
	$createrefdoc=mysqlInsert('subscribing_company',$arrFields,$arrValues);
	$response="created-success";
	}
	if(isset($_POST['update_referred'])){
	$updaterefdoc=mysqlUpdate('subscribing_company',$arrFields,$arrValues,"referred_doc_id='".$_POST['referred_doc_id']."'");
	$response="update-success";
	}
		
	if($_POST['appointSec']=="1"){
		header("Location:Corporate-Appointment?response=".$response);
	}
	else{	
	//header("Location:Add-Subscribe-Doctor?response=".$response);
	}
}

//ADD / UPDATE  COMPANY SUBSCRIBES DETAILS
if(isset($_POST['add_company_subscribe']) || isset($_POST['edit_subscribe_company'])){  
	//Check Empty condition
	if(!empty($_POST['txtcompany_name']) || !empty($_POST['txtcompany_code']))
	{ 
	$txtcompany_name = addslashes($_POST['txtcompany_name']);
	$txtcompany_code = addslashes($_POST['txtcompany_code']);
	$txtsubscription_start_date = $_POST['txtsubscription_start_date'];
	$txtsubscription_end_date = $_POST['txtsubscription_end_date'];
	$txtAddress = addslashes($_POST['txtAddress']); 
	$txtnum_employees = addslashes($_POST['txtnum_employees']);	
	$txtnum_dependants = $_POST['txtnum_dependants'];
 	$SubsCompid = $_POST['SubsCompid']; 
	$txtEmail = $_POST['txtEmail']; 
	$txtPhone = $_POST['txtMobile']; 
	

	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'company_name';
		$arrValues[] = $txtcompany_name;
		$arrFields[] = 'company_code';
		$arrValues[] = $txtcompany_code;
		$arrFields[] = 'subscription_start_date';
		$arrValues[] = $txtsubscription_start_date;
		$arrFields[] = 'subscription_end_date';
		$arrValues[] = $txtsubscription_end_date;
		$arrFields[] = 'address';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'num_employees';
		$arrValues[] = $txtnum_employees;
		$arrFields[] = 'num_dependants';
		$arrValues[] = $txtnum_dependants;
		$arrFields[] = 'email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'phone_num';
		$arrValues[] = $txtPhone;
		
		// echo '<pre>';
		// var_dump($arrValues); 
		// echo '</pre>'; exit;

					
				
	if(isset($_POST['add_company_subscribe'])){
		// echo'<pre>';
		// var_dump($arrFields); 
		// echo'</pre>';
		// echo'<pre>';
		// var_dump($arrValues); echo'</pre>'; exit;
			$usercraete=mysqlInsert('subscribing_company',$arrFields,$arrValues);
			//var_dump($usercraete) ; exit;
			$id= $usercraete;
			
			
			
			// foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
			// 	$arrFields2 = array();
			// 	$arrValues2 = array();

			// 	$arrFields2[] = 'hosp_id';
			// 	$arrValues2[] = $hosp_id;

			// 	$arrFields2[] = 'amenity_id';
			// 	$arrValues2[] = $amenValue;
				
			// 	$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
			// }
			
			
				
			//UPLOAD MULTIPLE IMAGES
			
			// $errors= array();
			// foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
			
				
			// $file_name = $_FILES['file-3']['name'][$key];
			// $file_size =$_FILES['file-3']['size'][$key];
			// $file_tmp =$_FILES['file-3']['tmp_name'][$key];
			// $file_type=$_FILES['file-3']['type'][$key];
			
			// if(!empty($file_name)){
			// 	$arrFields3 = array();
			// 	$arrValues3 = array();

			// 	$arrFields3[] = 'hosp_id';
			// 	$arrValues3[] = $hosp_id;

			// 	$arrFields3[] = 'hosp_image';
			// 	$arrValues3[] = $file_name;
				
					
			// 		$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
			// 		$id= mysql_insert_id();


			// 		//UPLOAD COMPRESSED IMAGE
			// 		if ($_FILES["file-3"]["error"][$key] > 0) {
			// 				$error = $_FILES["file-3"]["error"][$key];
			// 		} 
			// 		else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
			// 		($_FILES['file-3']['type'][$key] == "image/jpeg") || 
			// 		($_FILES['file-3']['type'][$key] == "image/png") || 
			// 		($_FILES['file-3']['type'][$key] == "image/pjpeg")) {
					
			// 		 $uploaddirectory = realpath("Hosp_image");
			// 		 $uploaddir = $uploaddirectory . "/" .$id;
					 
			// 		 /*Checking whether folder with add_hosp_picture id already exist or not. */
			// 			if (file_exists($uploaddir)) {
			// 			//echo "The file $uploaddir exists";
			// 			} else {
			// 			$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			// 			}
					 
					 
			// 				$url = $uploaddir.'/'.$_FILES["file-3"]["name"][$key];

			// 				$filename = compress_image($_FILES["file-3"]["tmp_name"][$key], $url, 40);
			// 				$buffer = file_get_contents($url);

			// 		}else {
			// 				$error = "Uploaded image should be jpg or gif or png";
			// 		}
				
			// }//End of if empty condition	
			// 	}
			// 	//End of foreach
			

			
			header("Location:Add-Subscribe-Company?response=add");
		}
		else if(isset($_POST['edit_subscribe_company'])){
		//	echo "hi";exit;
		// $arrFields[] = 'id';
		// $arrValues[] = $SubsCompid;
		// echo'<pre>';
		// var_dump($arrFields); 
		// echo'</pre>';
		// echo'<pre>';
		// var_dump($arrValues); echo'</pre>'; exit;
			
		$updateProvider=mysqlUpdate('subscribing_company',$arrFields,$arrValues,"id='".$SubsCompid."'");	
					
		
				
			//End of foreach
		header("Location:Add-Subscribe-Company?response=update");
		}
	}
	//Send Error message
	else
	{
		header("Location:Subscribes?response=error");
	}
}





?>