<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
include("push_notification_function.php");

//$ccmail="medical@medisense.me";
//$ccmail="salmabanu.h@gmail.com";
$objQuery = new CLSQueryMaker();

//$hostname="http://beta.referralio.com"; //For Beta version
$hostname="https://medisensecrm.com/"; //For Prod version

//CHAT MESSAGES
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['chat_patientid']) || isset($_POST['chat_doctorid'])|| isset($_POST['chat_partnerid'])|| isset($_POST['chat_message']) || isset($_POST['login_type']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$Cur_Date=date('Y-m-d H:i:s');
	// echo date("Y-m-d");
	
	$login_type = $_POST['login_type'];
	
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$arrFields1[]= 'patient_id';
	$arrValues1[]=  $_POST['chat_patientid'];
	$arrFields1[]= 'ref_id';
	$arrValues1[]=  $_POST['chat_doctorid'];
	$arrFields1[]= 'chat_note';
	$arrValues1[]=  $_POST['chat_message'];
	$arrFields1[]= 'TImestamp';
	$arrValues1[]= $Cur_Date;
	$arrFields1[]= 'msg_send_status';
	$arrValues1[]= $_POST['patient_response_send'];

	$getPatInfo= $objQuery->mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['chat_patientid']."'and b.ref_id='".$_POST['chat_doctorid']."'","","","","");
	$getSpec = $objQuery->mysqlSelect("b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['chat_doctorid']."'","","","","");
			
				
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));
			
					$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
					
					$doctorresponse ="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$_POST['chat_message']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y H:i',strtotime($Cur_Date))."</span></p></td></tr>";
					
					if(!empty($getPatInfo[0]['doc_photo'])){
					$docimg="https://medisensecrm.com/Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
				}	
				else{
					$docimg="https://medisensecrm.com/images/doc_icon.jpg";
				}
			
				$getPartnerRespSetting = $objQuery->mysqlSelect("a.partner_id as partner_id,a.Email_id as Email_id,a.cont_num1 as cont_num1,a.gcm_tokenid as gcm_tokenid","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatInfo[0]['patient_src']."'","","","","");
					
				$getMarketPerson = $objQuery->mysqlSelect("a.person_email as person_email,a.person_mobile as person_mobile,a.gcm_tokenid as gcm_id","hosp_marketing_person as a left join mapping_hosp_referrer as b on a.person_id=b.market_person_id","b.partner_id='".$getPartnerRespSetting[0]['partner_id']."' and b.hosp_id='".$getPatInfo[0]['hosp_id']."'","","","","");
			
			
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		$patientCreate=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
		//Change Status2 condition
		
		$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
		$getBucket=$getPatInfo[0]['bucket_status']; //Get present patient status of perticular referral
		if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						
				$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['chat_doctorid']."'","","","","");
	
				//NO. OF RESPONDED COUNT INCREMENTED BY ONE
						$TotCount=$getRef[0]['Tot_responded'];
						$TotCount=$TotCount+1;
						
						$arrFields3[]= 'Tot_responded';
						$arrValues3[]= $TotCount;
						$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['chat_doctorid']."'");
						
						//Update response time 
						//RETREIVE DOCTOR'S FIRST REFERRED DATE
						$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date","chat_notification","patient_id='".$_POST['chat_patientid']."'and ref_id='".$_POST['chat_doctorid']."'","","","","");
													
						$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
						$datetime2 = new DateTime($curDate);
						$interval = $datetime1->diff($datetime2);
														
						$numdays=$interval->format('%a');
						$numhours=$interval->format('%H');
						$nummin=$interval->format('%i');
						$daystominute=$numdays*24*60;
						$hourstominute=$numhours*60;
						$totmin=$daystominute+$hourstominute+$nummin;
						
						$arrFields2[]= 'status2';
						$arrValues2[]= "5";
						$arrFields2[]= 'response_status';
						$arrValues2[]= "2";
						$arrFields2[]= 'response_time';
						$arrValues2[]= $totmin;
						
						//Bucket Status will update only when its below 5
						if($getBucket<5){
						$arrFields2[]= 'bucket_status';
						$arrValues2[]= "5";
						}
						$updateBucket=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['chat_patientid']."' and ref_id='".$_POST['chat_doctorid']."'");
					
						
					}		
					
					
					//Check Doctor response should go to partner / Both partner & patient directly
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
				//Email Notification to patient
				
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
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
					
						$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
						send_msg($partnernum,$responsemsg);
					}
					//Message Notification to Marketing person
					if(!empty($marketnum)){
						
						$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
						send_msg($marketnum,$responsemsg);
					}
					
				//Here we need to Send Push notification to Partner & Marketing Professional
				
								
		$responsemsg = "Dear Sir/Madam, ".$getDocName." has responded the query of patient ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id']."). Many Thanks";
		$title="Doctor Response";
		$subtitle=$getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .")";
		$tickerText="Test Ticker";
		$type="4"; //For Event type 2
		$patientid=$getPatInfo[0]['patient_id'];
		$postid="0";
		$docid=$getPatInfo[0]['ref_id'];
		$largeimg='large_icon';	
		$postkey="0";
		if(!empty($getPatInfo[0]['doc_photo'])){ 
		$smalimg=$hostname."/Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
		}else{
		$smalimg="https://medisensecrm.com/assets/images/practice_push_icon.png";
		}
					//Push notification to Parteners
					if(!empty($getPartnerRespSetting[0]['gcm_tokenid'])){
						$regid=$getPartnerRespSetting[0]['gcm_tokenid'];
						push_notification_refer($regid,$responsemsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
						push_notification_aster_refer($regid,$responsemsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
					}
					//Push notification to Marketing person
					if(!empty($getMarketPerson[0]['gcm_id'])){
						$regid=$getMarketPerson[0]['gcm_id'];
						push_notification_prem_doc($regid,$responsemsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
					}
					
				
		//End Push notification functionality
				
					
	
			if($patientCreate == true)
				{
					$success = array('status' => "true","chat_insert" => $patientCreate);    	//  chat message insert successfull
					echo json_encode($success);
				}
			else {
				$success = array('status' => "false","chat_insert" => $patientCreate);      // chat message insert failed
				echo json_encode($success);
			}	
	}
	else if($login_type == 2) 		// Type-2 Referring Partners
	{
		$arrFields1[]= 'partner_id';
		$arrValues1[]=  $_POST['chat_partnerid'];
		$patientCreate=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
		
		$getDocGCM = $objQuery->mysqlSelect("ref_id,gcm_tokenid","referal","ref_id='".$_POST['chat_doctorid']."'","","","","");
		$getPartner1 = $objQuery->mysqlSelect("partner_id,partner_name,contact_person,doc_photo","our_partners","partner_id='".$_POST['chat_partnerid']."'","","","","");			
		
		//Check Doctor response should go to partner / Both partner & patient directly
			if($_POST['patient_response_send']==1){ // 1 for response should go to patient with a copy to partner & Point of contact(Marketing Person)
					$mailto =$getPatInfo[0]['patient_email'];
					$patientnum =$getPatInfo[0]['patient_mob'];
					
					//Email Notification to patient
				
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($mailto);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
						
			}
		
				

			
		//Here we need to Send Push notification to Partner
				
				
		$docmsg = "You have got one message from ".$getPartner1[0]['contact_person'];
		$title="Partner Response";
		$subtitle=$getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .")";
		$tickerText="Test Ticker";
		$type="4"; //For Event type 2
		$patientid=$getPatInfo[0]['patient_id'];
		$postid="0";
		$docid=$getPatInfo[0]['ref_id'];
		$largeimg='large_icon';	
		$postkey="0";
		if(!empty($getPartner1[0]['doc_photo'])){ 
		$smalimg=$hostname."/standard/partnerProfilePic/".$getPartner1[0]['partner_id']."/".$getPartner1[0]['doc_photo'];
		}else{
		$smalimg="https://medisensecrm.com/assets/images/practice_push_icon.png";
		}
		//Push Notification to premium doctor	
		if(!empty($getDocGCM[0]['gcm_tokenid'])){
		$regid = $getDocGCM[0]['gcm_tokenid'];
		push_notification_prem_doc($regid,$docmsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
		}
		//Push Notification to marketing person
		if(!empty($getMarketPerson[0]['gcm_id'])){
		$regid=$getMarketPerson[0]['gcm_id'];
		push_notification_prem_doc($regid,$docmsg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$postid,$patientid,$docid,$postkey);
		}
		//End Push notification functionality
				
				
				
				
		if($patientCreate == true)
			{
				$success = array('status' => "true","chat_insert" => $patientCreate);    	//  chat message insert successfull
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","chat_insert" => $patientCreate);      // chat message insert failed
				echo json_encode($success);
			}	
	}
	
			
	
}



?>