<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
include('send_text_message.php');
include('send_mail_function.php');
include('short_url.php');
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");


if(isset($_GET['diagnoid']))
{
	

	$getPatient= mysqlSelect(" a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email,a.created_date as system_date","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_GET['patientid']."'");
	
	
	
	$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$_GET['diagnoid']."'");
	$getComp= mysqlSelect("*","compny_tab","company_id='".$admin_id."'");
	$checkDiagnoCust= mysqlSelect("*","diagnostic_customer","diagnostic_id='".$_GET['diagnoid']."' and patient_id='".$_GET['patientid']."'");
	//Insert 'diagnostic_customer and diagnostic_referrals table'
		$arrFileds[]='diagnostic_id';
		$arrValues[]=$_GET['diagnoid'];
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		$arrFileds[]='patient_type';
		$arrValues[]="1";
		$arrFileds[]='diagnostic_customer_name';
		$arrValues[]=$getPatient[0]['patient_name'];
		$arrFileds[]='diagnostic_cust_age';
		$arrValues[]=$getPatient[0]['patient_age'];
		$arrFileds[]='diagnostic_cust_gender';
		$arrValues[]=$getPatient[0]['patient_gen'];
		$arrFileds[]='diagnostic_customer_phone';
		$arrValues[]=$getPatient[0]['patient_mob'];
		$arrFileds[]='diagnostic_customer_email';
		$arrValues[]=$getPatient[0]['patient_email'];
		$arrFileds[]='diagnostic_cust_address';
		$arrValues[]=$getPatient[0]['patient_addrs'];
		$arrFileds[]='diagnostic_cust_city';
		$arrValues[]=$getPatient[0]['patient_loc'];
		$arrFileds[]='diagnostic_cust_state';
		$arrValues[]=$getPatient[0]['pat_state'];
		$arrFileds[]='diagnostic_cust_country';
		$arrValues[]=$getPatient[0]['pat_country'];
		if(count($checkDiagnoCust)>0){
			$update_icd=mysqlUpdate('diagnostic_customer',$arrFileds,$arrValues,"diagnostic_id = '".$_GET['diagnoid']."' and patient_id='".$_GET['patientid']."'");
					
		}
		else{
			$insert_temp_value=mysqlInsert('diagnostic_customer',$arrFileds,$arrValues);
		}
	//$checkDiagnoRefer= mysqlSelect("*","diagnostic_referrals","diagnostic_id='".$_GET['diagnoid']."' and doc_id='".$admin_id."' and doc_type='1' and patient_id='".$_GET['patientid']."' and patient_type='1' and episode_id='".$_GET['episodeid']."'");
		$diagnoCust= mysqlSelect("*","diagnostic_customer","diagnostic_id='".$_GET['diagnoid']."' and patient_id='".$_GET['patientid']."'");
	
		$checkDiagnoRefer= mysqlSelect("*","diagnostic_patient_episodes","diagnostic_id='".$_GET['diagnoid']."' and doc_id='".$_GET['docid']."' and doc_type='1' and diagnostic_customer_id='".$diagnoCust[0]['diagnostic_customer_id']."' and doc_episode_id='".$_GET['episodeid']."'");
		
		
		if(count($checkDiagnoRefer)==0){
			// insert 'diagnostic_patient_episode'
			$arrFileds_episode= array();
			$arrValues_episode= array();
			$arrFileds_episode[]= 'doc_id';
			$arrValues_episode[]= $_GET['docid'];
			$arrFileds_episode[]='doc_type';
			$arrValues_episode[]="1";
			$arrFileds_episode[]='diagnostic_customer_id';
			$arrValues_episode[]=$diagnoCust[0]['diagnostic_customer_id'];
			$arrFileds_episode[]='datetime';
			$arrValues_episode[]=$Cur_Date;
			$arrFileds_episode[]='doc_episode_id';
			$arrValues_episode[]=$_GET['episodeid'];
			$arrFileds_episode[]='diagnostic_id';
			$arrValues_episode[]=$_GET['diagnoid'];
			$insert_episode=mysqlInsert('diagnostic_patient_episodes',$arrFileds_episode,$arrValues_episode);
			$ep_id = mysql_insert_id();
			
			//Insert 'diagnostic_referrals' table
			$arrFileds_referral= array();
			$arrValues_referral= array();
			$arrFileds_referral[]='patient_id';
			$arrValues_referral[]=$_GET['patientid'];
			$arrFileds_referral[]='patient_type';
			$arrValues_referral[]="1";
			$arrFileds_referral[]='doc_id';
			$arrValues_referral[]=$_GET['docid'];
			$arrFileds_referral[]='doc_type';
			$arrValues_referral[]="1";
			//$arrFileds_referral[]='episode_id';
			//$arrValues_referral[]=$_GET['episodeid'];
			$arrFileds_referral[]='diagnostic_customer_id';
			$arrValues_referral[]=$diagnoCust[0]['diagnostic_customer_id'];
			$arrFileds_referral[]='diagnostic_id';
			$arrValues_referral[]=$_GET['diagnoid'];
			$arrFileds_referral[]='status1';
			$arrValues_referral[]="1";
			$arrFileds_referral[]='status2';
			$arrValues_referral[]="1"; //1 for referred
			$arrFileds_referral[]='referred_date';
			$arrValues_referral[]=$Cur_Date;
			$arrFileds_referral[]='episode_id';
		    $arrValues_referral[]=$ep_id;
			
			$insert_temp_value=mysqlInsert('diagnostic_referrals',$arrFileds_referral,$arrValues_referral);
			
			//insert 'doctor_outgoing_referals'
			$arrFileds_outreferral[]='patient_id';
			$arrValues_outreferral[]=$_GET['patientid'];
			$arrFileds_outreferral[]='episode_id';
			$arrValues_outreferral[]=$_GET['episodeid'];
			$arrFileds_outreferral[]='doc_id';
			$arrValues_outreferral[]=$_GET['docid'];
			$arrFileds_outreferral[]='doc_type';
			$arrValues_outreferral[]="1";
			$arrFileds_outreferral[]='referral_id';
			$arrValues_outreferral[]=$_GET['diagnoid'];
			$arrFileds_outreferral[]='type';
			$arrValues_outreferral[]="1";
			$arrFileds_outreferral[]='timestamp';
			$arrValues_outreferral[]=$Cur_Date;
			$insert_outgoing_referrals=mysqlInsert('doctor_outgoing_referrals',$arrFileds_outreferral,$arrValues_outreferral);
			
			$getChosenExam= mysqlSelect("*","doc_patient_examination_active","doc_id='".$_GET['docid']."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");
					
					while(list($key_examtemp, $val_examtemp) = each($getChosenExam))
					{	
						$arrFileds_exam = array();
						$arrValues_exam = array();
						
						$arrFileds_exam[]='examination';
						$arrValues_exam[]= $val_examtemp['examination'];
											
						$arrFileds_exam[]='exam_result';
						$arrValues_exam[]= $val_examtemp['exam_result'];
						
						$arrFileds_exam[]='findings';
						$arrValues_exam[]=addslashes($val_examtemp['findings']);
							
						$arrFileds_exam[]='diagnostic_customer_id';
						$arrValues_exam[]=$diagnoCust[0]['diagnostic_customer_id'];
						
						$arrFileds_exam[]='episode_id';
						$arrValues_exam[]=$ep_id;
						$arrFileds_exam[]='diagnostic_id';
						$arrValues_exam[]=$_GET['diagnoid'];
						
						
						$insert_patient_episode_exam_template_desc = mysqlInsert('diagnostic_patient_examination_active',$arrFileds_exam,$arrValues_exam);
						
					}
					
				$getChosenInvset= mysqlSelect("*","patient_temp_investigation","doc_id='".$_GET['docid']."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");
					
			while(list($key_invtemp, $val_invtemp) = each($getChosenInvset))
					{	
						$arrFieldsINVESTTD = array();
						$arrValuesINVESTTD = array();
						$arrFieldsINVESTTD[] = 'main_test_id';
						$arrValuesINVESTTD[] = $val_invtemp['main_test_id'];
						$arrFieldsINVESTTD[] = 'group_test_id';
						$arrValuesINVESTTD[] = $val_invtemp['group_test_id'];
						$arrFieldsINVESTTD[] = 'test_name';
						$arrValuesINVESTTD[] = $val_invtemp['test_name'];
						$arrFieldsINVESTTD[] = 'department';
						$arrValuesINVESTTD[] = $val_invtemp['department'];
						$arrFieldsINVESTTD[] = 'normal_range';
						$arrValuesINVESTTD[] = $val_invtemp['normal_range'];
						$arrFieldsINVESTTD[] = 'right_eye';
						$arrValuesINVESTTD[] = $val_invtemp['right_eye'];
						$arrFieldsINVESTTD[] = 'left_eye';
						$arrValuesINVESTTD[] = $val_invtemp['left_eye'];
						$arrFieldsINVESTTD[] = 'test_actual_value';
						$arrValuesINVESTTD[] = $val_invtemp['test_actual_value'];
						$arrFieldsINVESTTD[] = 'diagnostic_customer_id';
						$arrValuesINVESTTD[] = $diagnoCust[0]['diagnostic_customer_id'];
						$arrFieldsINVESTTD[]='diagnostic_id';
						$arrValuesINVESTTD[]=$_GET['diagnoid'];
						$arrFieldsINVESTTD[] = 'episode_id';
						$arrValuesINVESTTD[] = $ep_id;
						
						
						$insert_patient_episode_invest_template_desc = mysqlInsert('diagnostic_patient_temp_investigation',$arrFieldsINVESTTD,$arrValuesINVESTTD);
						
					}
			
			
		}
		else {
			$err_status="Error!! Already referred";
		}
		
	$link = "/institution/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//$link = "https://medisensecrm.com/premium/Diagnostic-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//Get Shorten Url
	$getUrl= get_shorturl($link);
	
	$chatMessage="EMR sent to Diagnosis- ".$getDiagno[0]['diagnosis_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_GET['patientid'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_GET['episodeid'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $admin_id;
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "2";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "1";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_GET['diagnoid'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $getUrl;
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $Cur_Date;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);
	
	//SMS notification to Diagnostic center
	if(!empty($getDiagno[0]['diagnosis_contact_num'])){
	$mobile = $getDiagno[0]['diagnosis_contact_num'];
	$msg = "Hello ".$getDiagno[0]['diagnosis_name'].", ".$getComp[0]['company_name']." has referred the following tests. Click here to view & update reports \n ".$getUrl." \nThank you";
	send_msg($mobile,$msg);
	}

	//EMAIL notification Diagnostic center
		if(!empty($getDiagno[0]['diagnosis_email'])){
		$PatAddress=$getPatient[0]['patient_addrs'].",<br>".$getPatient[0]['patient_loc'].", ".$getPatient[0]['pat_state'].", ".$getPatient[0]['pat_country'];
		
					$url_page = 'refer_diagno.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatient[0]['patient_id']);
					$url .= "&link=".urlencode($getUrl);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatient[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatient[0]['patient_email']);
					$url .= "&diagnoName=" . urlencode($getDiagno[0]['diagnosis_name']);
					$url .= "&tomail=" . urlencode($getDiagno[0]['diagnosis_email']);
					$url .= "&docname=" . urlencode($getComp[0]['company_name']);
					$url .= "&replymail=" . urlencode($getComp[0]['email_id']);						
					send_mail($url);	
		}
	?>	
	<!--<div class="form-group col-md-12">
				<table>
					<tr><th>Diagnostic Center</th><th>Referred on</th><th>status</th></tr>
					<tr><td></td><td></td><td></td><td></td></tr>
				</table>
		</div>	-->
		
<?php 		
}

if(isset($_GET['pharmaid']))
{
	$getPatient	= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email,a.created_date as system_date","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_GET['patientid']."'");
	
	
	$getPharma	= mysqlSelect("*","pharma","pharma_id='".$_GET['pharmaid']."'");
	$getComp	= mysqlSelect("*","compny_tab","company_id='".$admin_id."'");
	$checkPharmaCust= mysqlSelect("*","pharma_customer","pharma_id='".$_GET['pharmaid']."' and patient_id='".$_GET['patientid']."'");
	
	
	//Insert 'diagnostic_customer and diagnostic_referrals table'
		$arrFileds[]='pharma_id';
		$arrValues[]=$_GET['pharmaid'];
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		$arrFileds[]='patient_type';
		$arrValues[]="1";
		$arrFileds[]='pharma_customer_name';
		$arrValues[]=$getPatient[0]['patient_name'];
		$arrFileds[]='pharma_cust_age';
		$arrValues[]=$getPatient[0]['patient_age'];
		$arrFileds[]='pharma_cust_gender';
		$arrValues[]=$getPatient[0]['patient_gen'];
		$arrFileds[]='pharma_customer_phone';
		$arrValues[]=$getPatient[0]['patient_mob'];
		$arrFileds[]='pharma_customer_email';
		$arrValues[]=$getPatient[0]['patient_email'];
		$arrFileds[]='pharma_cust_address';
		$arrValues[]=$getPatient[0]['patient_addrs'];
		$arrFileds[]='pharma_cust_city';
		$arrValues[]=$getPatient[0]['patient_loc'];
		$arrFileds[]='pharma_cust_state';
		$arrValues[]=$getPatient[0]['pat_state'];
		$arrFileds[]='pharma_cust_country';
		$arrValues[]=$getPatient[0]['pat_country'];
		if(count($checkPharmaCust)>0)
		{
			$update_cust=mysqlUpdate('pharma_customer',$arrFileds,$arrValues,"pharma_id = '".$_GET['pharmaid']."' and patient_id='".$_GET['patientid']."'");
			$pharma_customer_id= $checkPharmaCust[0]['pharma_customer_id'];		
		}
		else
		{
			$insert_temp_value=mysqlInsert('pharma_customer',$arrFileds,$arrValues);
			$pharma_customer_id= mysql_insert_id();
		}
		
	$checkPharmaRefer= mysqlSelect("*","pharma_referrals","pharma_id='".$_GET['pharmaid']."' and doc_id='".$_GET['docid']."' and doc_type='1' and patient_id='".$_GET['patientid']."' and episode_id='".$_GET['episodeid']."'");
		
	//Insert 'diagnostic_referrals' table
		$arrFileds_referral[]='patient_id';
		$arrValues_referral[]=$_GET['patientid'];
		$arrFileds_referral[]='pharma_customer_id';
		$arrValues_referral[]=$pharma_customer_id;
		$arrFileds_referral[]='doc_id';
		$arrValues_referral[]=$_GET['docid'];
		$arrFileds_referral[]='doc_type';
		$arrValues_referral[]="1";
		$arrFileds_referral[]='episode_id';
		$arrValues_referral[]=$_GET['episodeid'];
		$arrFileds_referral[]='pharma_id';
		$arrValues_referral[]=$_GET['pharmaid'];
		$arrFileds_referral[]='status1';
		$arrValues_referral[]="1";
		$arrFileds_referral[]='status2';
		$arrValues_referral[]="1";
		$arrFileds_referral[]='referred_date';
		$arrValues_referral[]=$Cur_Date;
		if(count($checkPharmaRefer)==0){
		$insert_temp_value=mysqlInsert('pharma_referrals',$arrFileds_referral,$arrValues_referral);
		
		$arrFileds_outreferral[]='patient_id';
		$arrValues_outreferral[]=$_GET['patientid'];
		$arrFileds_outreferral[]='episode_id';
		$arrValues_outreferral[]=$_GET['episodeid'];
		$arrFileds_outreferral[]='doc_id';
		$arrValues_outreferral[]=$_GET['docid'];
		$arrFileds_outreferral[]='doc_type';
		$arrValues_outreferral[]="1";
		$arrFileds_outreferral[]='referral_id';
		$arrValues_outreferral[]=$_GET['pharmaid'];
		$arrFileds_outreferral[]='type';
		$arrValues_outreferral[]="2";
		$arrFileds_outreferral[]='timestamp';
		$arrValues_outreferral[]=$Cur_Date;
		$insert_outgoing_referrals=mysqlInsert('doctor_outgoing_referrals',$arrFileds_outreferral,$arrValues_outreferral);
		}
		else {
			$err_status="Error!! Already referred";
		}
		$link = "/institution/Pharma-Refer?d=" . md5($_GET['patientid'])."&e=".md5($_GET['episodeid']);
	
	
	//$link = "https://medisensecrm.com/premium/Pharma-Refer?d=" . md5($_GET['patientid']) ."&e=".md5($_GET['episodeid']);
	
	//Get Shorten Url
	$getUrl= get_shorturl($link);
	
		$chatMessage="EMR sent to Pharmacy- ".$getPharma[0]['pharma_name']." successfully";
				$arrFieldsChat = array();
				$arrValuesChat = array();
				$arrFieldsChat[] = 'patient_id';
				$arrValuesChat[] = $_GET['patientid'];
				$arrFieldsChat[] = 'episode_id';
				$arrValuesChat[] = $_GET['episodeid'];				
				$arrFieldsChat[] = 'company_id';
				$arrValuesChat[] = $admin_id;
				$arrFieldsChat[] = 'chat_note';
				$arrValuesChat[] = $chatMessage;
				$arrFieldsChat[] = 'status';
				$arrValuesChat[] = "3";
				$arrFieldsChat[] = 'type';
				$arrValuesChat[] = "2";
				$arrFieldsChat[] = 'refer_id';
				$arrValuesChat[] = $_GET['pharmaid'];
				$arrFieldsChat[] = 'url';
				$arrValuesChat[] = $getUrl;
				$arrFieldsChat[] = 'created_date';
				$arrValuesChat[] = $Cur_Date;
				
				$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);
				
	
	
	//SMS notification to Diagnostic center
	if(!empty($getPharma[0]['pharma_contact_num'])){
	$mobile = $getPharma[0]['pharma_contact_num'];
	//$msg = "Request from ".$getDoc[0]['ref_name']." For more details click here ".$link." - Thank you";
	$msg = "Hello ".$getPharma[0]['pharma_name'].", ".$getComp[0]['company_name']." has sent the digitized prescription. Click here to view & update \n".$getUrl." \nThank you";
	
	send_msg($mobile,$msg);
	}

	//EMAIL notification Diagnostic center
		if(!empty($getPharma[0]['pharma_email'])){
		$PatAddress=$getPatient[0]['patient_addrs'].",<br>".$getComp[0]['company_name'].", ".$getPatient[0]['pat_state'].", ".$getPatient[0]['pat_country'];
		
					$url_page = 'refer_pharma.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['patient_name']);
					$url .= "&patID=".urlencode($getPatient[0]['patient_id']);
					$url .= "&link=".urlencode($getUrl);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($getPatient[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($getPatient[0]['patient_email']);
					$url .= "&pharmaName=" . urlencode($getPharma[0]['pharma_name']);
					$url .= "&tomail=" . urlencode($getPharma[0]['pharma_email']);
					$url .= "&docname=" . urlencode($getComp[0]['company_name']);
					$url .= "&replymail=" . urlencode($getComp[0]['email_id']);						
					send_mail($url);	
		}		
		
}
           
?>
