<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
//$ccmail="medical@medisense.me";
$ccmail="salmabanu.h@gmail.com";
$objQuery = new CLSQueryMaker();

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

//Doctor Reassign
// if(API_KEY == $_POST['API_KEY'] && isset($_POST['userid']) && isset($_POST['login_type']) && isset($_POST['patientid']) && isset($_POST['selectref']) && isset($_POST['patemail']) && isset($_POST['patname']) && isset($_POST['patmobile']) ) {

 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 

	$logintype = $_POST['login_type'];	
	$oldrefid = $_POST['userid'];
	
	$patid = $_POST['patientid'];
	$SelectRef = $_POST['selectref'];
	
	if($logintype == 1)			// Hospital Doctor
	{
		
		$arrFields = array();
		$arrValues = array();

		$arrFields[]= 'ref_id';
		$arrValues[]= $SelectRef;
		
		$updatereferral=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$oldrefid."'");	

		$updateChatHistory=$objQuery->mysqlUpdate('chat_notification',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$oldrefid."'");	
	
		//Get Reassigned Doctor details
		$getReassignedDoc=$objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.Total_Referred as Total_Referred,c.communication_status as communication_status,a.doc_photo as doc_photo,d.spec_name as spec_name,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,c.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join specialization as d on d.spec_id=a.doc_spec","a.ref_id='".$SelectRef."'","","","","");	
		$reassignmsg="Case has been re-assign to ".$getReassignedDoc[0]['ref_name'];
		$get_organisation=$objQuery->mysqlSelect("company_id,company_name,company_logo,email_id,mobile","compny_tab","company_id='".$getReassignedDoc[0]['company_id']."'","","","","");	
		
		
	
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
		$addoffers=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
		
		//Update Old Doctor No.of referral count, ie. we should decrement it by one
		//Get Reassigned Old Doctor details
		$getOldDoc=$objQuery->mysqlSelect("*","referal","ref_id='".$oldrefid."'","","","","");
		$getUpdateCount=$getOldDoc[0]['Total_Referred']-1;//Decrement it by one
		
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[]= 'Total_Referred';
		$arrValues2[]= $getUpdateCount;
		
		$updatereferral1=$objQuery->mysqlUpdate('referal',$arrFields2,$arrValues2,"ref_id='".$oldrefid."'");	
		
		//Update Ressigned Doctor No.of referral count, ie. we should increment it by one
		$getUpdateNewCount=$getReassignedDoc[0]['Total_Referred']+1;//Increment it by one
		
		$arrFields3[]= 'Total_Referred';
		$arrValues3[]= $getUpdateNewCount;
		
		$updatereferral2=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$SelectRef."'");


		
		 if(!empty($_POST['patemail']) && $getReassignedDoc[0]['communication_status']!=0){
		//Doc Info EMAIL notification Sent to Patient
			
			if(!empty($getReassignedDoc[0]['doc_photo'])){
				$docimg="https://medisensecrm.com/Doc/".$getReassignedDoc[0]['ref_id']."/".$getReassignedDoc[0]['doc_photo'];
				}	
				else{
					$docimg="https://medisensecrm.com/images/doc_icon.jpg";
				}
		
				$getDocName=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_name']));
				$getDocSpec=urlencode(str_replace(' ','-',$getReassignedDoc[0]['spec_name']));
				$getDocCity=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_address']));
				$getDocState=urlencode(str_replace(' ','-',$getReassignedDoc[0]['doc_state']));
				$getDocHosp=urlencode(str_replace(' ','-',$getReassignedDoc[0]['hosp_name']));
				//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
				$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
				$actualLink=hyphenize($Getlink);
				$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$actualLink.'/'.$getReassignedDoc[0]['ref_id'];
				$compLogo='https://medisensecrm.com/Hospital/company_logo/'.$get_organisation[0]['company_id'].'/'.$get_organisation[0]['company_logo'];
	
											
				$url_page = 'Custom_after_reassign_pat_mail.php';
			
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
				send_mail($url);		
					
			}
			
		//Message Notification to patient
		$mobile = $_POST['patmobile'];
		$responsemsg = "Dear ".$_POST['patname'].", Your medical query has been reassign to ".$getReassignedDoc[0]['ref_name'].". Thx";
		send_msg($mobile,$responsemsg);
		$response="reassign";
	

		$success = array('status' => "true","reassign_details" => "Your medical query has been reassign to ".$getReassignedDoc[0]['ref_name'].".");
		echo json_encode($success);
	}
	else if($logintype == 2)	// Partner
	{
	
	}
	else if($logintype == 3)	// Marketing Person
	{
		
	
	}
	else {
		$success = array('status' => "false","reassign_details" => "Failed to reassign");
		echo json_encode($success);
	}
}


?>