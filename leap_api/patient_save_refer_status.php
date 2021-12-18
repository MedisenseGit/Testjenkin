<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
include('push_notification_function.php');
$ccmail="ambarish@medisense.me";
$objQuery = new CLSQueryMaker();

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
// PATIENT SAVE AND REFER
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['patient_id']) || isset($_POST['doctor_id']) || isset($_POST['user_id']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	

	$patientid = $_POST['patient_id'];
	$docid = $_POST['doctor_id'];
	$user_id = $_POST['user_id'];
	$cur_Date = date("Y-m-d");
	
	//echo $patientid ;
	//echo $docid;
	//echo $user_id;
	
		$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patientid."'" ,"","","","");
		$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$patientid."'" ,"","","","");
		$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
		$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
		$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$docid."'","","","","");
	
		$chekPatRefCondition = $objQuery->mysqlSelect("patient_id,ref_id","patient_referal","patient_id='".$patientid."' and ref_id='".$docid."'","","","","");
						

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
		$arrValues2[] = date("Y-m-d,h:i:s");	

		if($chekPatRefCondition==false){
			$insertpatref=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
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
			$arrFields3[] = 'TImestamp';
			$arrValues3[] = date("Y-m-d,h:i:s");
				
			$insertchat=$objQuery->mysqlInsert('chat_notification',$arrFields3,$arrValues3);
		
		
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
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your mail for further details - Many Thanks".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="")
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your mail for further details - Many Thanks".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					}
					
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!="" && $_POST['patient_response_condition']==1)
					{
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
				push_notification_doctor($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				
				} //End Push notification functionality	
				
			
				if(!empty($getPatInfo[0]['patient_email']) && $_POST['patient_response_condition']==1){
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
					
					$patattachments='';
					foreach($getPatAttach as $key=>$value){
		
						$patattachments .="<li>".$value['attachments']."</li>";
					}				
										
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
					$url .= "&patattachments=" . urlencode($patattachments);
					
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
					
					$url .= "&docmail=" . urlencode($docmail);
					
					$url .= "&ccmail=" . urlencode($ccmail);
							
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&subject=" . urlencode($subject);
					send_mail($url);
				}
		
				$success = array('status' => "true","patient_status" => "success");      // patient craeted successfully
				echo json_encode($success);
			
			}//End of Patient refer condition

}


?>