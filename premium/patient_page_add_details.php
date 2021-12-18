<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
include('short_url.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();

	//$admin_id = $_SESSION['user_id'];
	//$ccmail="medical@medisense.me";
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
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

	function createKey(){
		//create a random key
		$strKey = md5(microtime());
		
		//check to make sure this key isnt already in use
		$resCheck = mysql_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
		$arrCheck = mysql_fetch_assoc($resCheck);
		if($arrCheck['count(*)']){
			//key already in use
			return createKey();
		}else{
			//key is OK
			return $strKey;
		}
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
	date_default_timezone_set('Asia/Kolkata');
	$curDate=date('Y-m-d H:i:s');



	//$hostname="http://beta.referralio.com"; //For Beta version
	$hostname="https://medisensecrm.com/"; //For Prod version
	//Image Compress functionality
	$name = ''; $type = ''; $size = ''; $error = '';
	function compress_image($source_url, $destination_url, $quality) {

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
	
//Approval
if(isset($_POST['termClick'])){
	$patientId=$_POST['patient_id'];
	$doc_id=$_POST['doc_id'];
	$trans_id=$_POST['trans_id'];
	$status=$_POST['status'];
	
	$arrFileds_app = array();
	$arrValues_app = array();
	
	$arrFileds_app[]='doc_id';
	$arrValues_app[]=$doc_id;
	$arrFileds_app[]='patient_id';
	$arrValues_app[]=$patientId;
	$arrFileds_app[]='trans_id';
	$arrValues_app[]=$trans_id;
	$arrFileds_app[]='status';
	$arrValues_app[]=$status;
	$arrFileds_app[]='date_time';
	$arrValues_app[]=$Cur_Date;
	
	$insert_approver=mysqlInsert('patient_page_approval',$arrFileds_app,$arrValues_app);
}

//Family History
if(isset($_GET['historyid']) && !empty($_GET['historyid'])){
	$patientid=$_GET['patientid'];
	$params = split("-", $_GET['historyid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_drug = array();
		$arrValues_drug = array();
		
		$arrFileds_drug[]='family_history';
		$arrValues_drug[]=$params[0];
		$arrFileds_drug[]='doc_id';
		$arrValues_drug[]=$_GET['docid'];
		$arrFileds_drug[]='doc_type';
		$arrValues_drug[]='1';
		
		$insert_drugs=mysqlInsert('family_history_auto',$arrFileds_drug,$arrValues_drug);
		$history_id =$insert_drugs;
	} else
	{
		$history_id = $params[0];
	}
	
	$arrFileds = array();
	$arrValues = array();
	
	$arrFileds[]='family_history_id';
	$arrValues[]=$history_id;
						
	$arrFileds[]='patient_id';
	$arrValues[]=$patientid;
	
	$arrFileds[]='doc_id';
	$arrValues[]=$_GET['docid'];
	
	$arrFileds[]='doc_type';
	$arrValues[]="1";
	
	$arrFileds[]='status';
	$arrValues[]="0";
	
	$insert_drug=mysqlInsert('doc_patient_family_history_active',$arrFileds,$arrValues);
	
	$check_history = mysqlSelect("*","doctor_frequent_family_history","family_history_id='".$history_id."' and doc_id='".$_GET['docid']."' and doc_type='1'","","","","");
	$freq_count = $check_history[0]['freq_count']+1; //Count will increment by one
	$arrFieldsSYMPFREQ = array();
	$arrValuesSYMPFREQ = array();
	if(count($check_history)>0){
		$arrFieldsSYMPFREQ[] = 'freq_count';
		$arrValuesSYMPFREQ[] = $freq_count;
		$update_icd=mysqlUpdate('doctor_frequent_family_history',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"ffh_id = '".$check_history[0]['ffh_id']."'");
	}else{
		$arrFieldsSYMPFREQ[] = 'family_history_id';
		$arrValuesSYMPFREQ[] = $history_id;
		$arrFieldsSYMPFREQ[] = 'doc_id';
		$arrValuesSYMPFREQ[] = $_GET['docid'];
		$arrFieldsSYMPFREQ[] = 'doc_type';
		$arrValuesSYMPFREQ[] = "1";
		$arrFieldsSYMPFREQ[] = 'freq_count';
		$arrValuesSYMPFREQ[] = "1";
		$insert_freq_symp=mysqlInsert('doctor_frequent_family_history',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);
	}
	$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","a.doc_id='".$_GET['docid']."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.status='0'","","","","");
	while(list($key, $value) = each($getHistoryRes)){ 
		echo "<span class='tag label label-primary m-r'>" . $value['family_history'] . "<a data-role='remove' class='text-white del_history m-l' data-history-id='".$value['family_active_id']."'>x</a></span>";
	}
}

if(isset($_GET['delhistoryid']))
{
	//Delete perticular drug from table 'doc_patient_family_history_active'
	mysqlDelete('doc_patient_family_history_active',"family_active_id='".$_GET['delhistoryid']."'");
	
	//$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.status='0'","","","","");
	//while(list($key, $value) = each($getHistoryRes)){ 
	//								echo "<span class='tag label label-primary m-r'>" . $value['family_history'] . "<a data-role='remove' class='text-white del_history m-l' data-history-id='".$value['family_active_id']."'>x</a></span>";
		//						}
}


//Allergy Details

if(isset($_GET['term'])){
	$searchTerm = $_GET['term'];
	$select= mysqlSelect("DISTINCT(pharma_generic) as dist_pharma_generic,generic_id","pharma_products","pharma_generic LIKE '%".$searchTerm."%'","generic_id asc","","","0,20");

	while (list($key, $value) = each($select)) 
	{
	$data[] = $value['generic_id']."-".$value['dist_pharma_generic'];
	}
	//return json data
	echo json_encode($data);
}

if(isset($_GET['generic']) || isset($_GET['allergyid']))
{
	$params = split("-", $_GET['generic']);
	$generic_id = $params[0];
	$generic_name = $params[1];
	
	if(isset($_GET['generic']))
	{
		
		
		$arrFileds = array();
		$arrValues = array();
									
		$arrFileds[]='patient_id';
		$arrValues[]=$_GET['patientid'];
		
		$arrFileds[]='generic_id';
		$arrValues[]=$generic_id;
		
		$arrFileds[]='generic_name';
		$arrValues[]=$generic_name;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$_GET['docid'];
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
			
		$arrFileds[]='status';
		$arrValues[]="0";
		if($generic_id!=0){
		$insert_allergy=mysqlInsert('doc_patient_drug_allergy_active',$arrFileds,$arrValues);
		}
	}	
	if(isset($_GET['allergyid'])){
		
		mysqlDelete('doc_patient_drug_allergy_active',"allergy_id='".$_GET['allergyid']."'");
	}
	
	if($generic_id!=0 || $_GET['allergyid']!=0) {
	$getAllergy= mysqlSelect("*","doc_patient_drug_allergy_active","patient_id='".$_GET['patientid']."' and doc_id ='".$_GET['docid']."' and doc_type='1' and status='0'","allergy_id desc","","","");

	while(list($key, $value) = each($getAllergy)){ 
		echo '<span class="tag label label-primary m-r">' . $value['generic_name'] . '<a data-role="remove" class="text-white del_allergy m-l" data-drug-allergy-id="'.$value['allergy_id'].'">x</a></span>';
	}
	}

}

//Medical History	
if(isset($_GET['updatecon']) && !empty($_GET['updatecon']))
{
	
	if(isset($_GET['pathyper']))
	{
		$arrFileds_medical[]='hyper_cond';
		$arrValues_medical[]=$_GET['pathyper'];
	}
	if(isset($_GET['patdiabetes']))
	{
		$arrFileds_medical[]='diabetes_cond';
		$arrValues_medical[]=$_GET['patdiabetes'];
	}
	if(isset($_GET['patsmoke']))
	{
		$arrFileds_medical[]='smoking';
		$arrValues_medical[]=$_GET['patsmoke'];
	}
	if(isset($_GET['patalcohol']))
	{
		$arrFileds_medical[]='alcoholic';
		$arrValues_medical[]=$_GET['patalcohol'];
	}
	
	if(isset($_GET['previntervent']))
	{
		$arrFileds_medical[]='prev_inter';
		$arrValues_medical[]=$_GET['previntervent'];
	}
	if(isset($_GET['neuroissue']))
	{
		$arrFileds_medical[]='neuro_issue';
		$arrValues_medical[]=$_GET['neuroissue'];
	}
	if(isset($_GET['kedneyissue']))
	{
		$arrFileds_medical[]='kidney_issue';
		$arrValues_medical[]=$_GET['kedneyissue'];
	}
		if(isset($_GET['otherdetail']))
	{
		$arrFileds_medical[]='other_details';
		$arrValues_medical[]=$_GET['otherdetail'];
	}

	if(isset($_GET['pat_blood']))
	{
		$arrFileds_medical[]='pat_blood';
		$arrValues_medical[]= $_GET['pat_blood'] ;
	}

	if(isset($_GET['pat_bp']))
	{
		$arrFileds_medical[]='pat_bp';
		$arrValues_medical[]=$_GET['pat_bp'];
	}
	
	if(isset($_GET['pat_thyroid']))
	{
		$arrFileds_medical[]='pat_thyroid';
		$arrValues_medical[]=$_GET['pat_thyroid'];
	}

	if(isset($_GET['pat_asthama']))
	{
		$arrFileds_medical[]='pat_asthama';
		$arrValues_medical[]=$_GET['pat_asthama'];
	}
	
	if(isset($_GET['pat_cholestrole']))
	{
		$arrFileds_medical[]='pat_cholestrole';
		$arrValues_medical[]=$_GET['pat_cholestrole'];
	}

	if(isset($_GET['pat_epilepsy']))
	{
		$arrFileds_medical[]='pat_epilepsy';
		$arrValues_medical[]=$_GET['pat_epilepsy'];
	}
		
	$update_medical=mysqlUpdate('doc_my_patient',$arrFileds_medical,$arrValues_medical,"md5(patient_id) = '".$_GET['patientid']."'");

}

//RESCHEDULE APPOINTMENT	
if(isset($_POST['cmdreschedulePatPage'])){
	
	$reschedule_date = date('Y-m-d',strtotime($_POST['reschedule_date']));
	$slctTime = $_POST['check_time'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $reschedule_date;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		$arrFields[] = 'pay_status';
		$arrValues[] = "Pending";
		
		$updatepatientApp=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['trans_id']."'");
		$getTime=mysqlSelect('*','timings',"Timing_id='".$slctTime."'");
		$arrFields_token[] = 'token_no';
		$arrValues_token[] = "555";
		$arrFields_token[] = 'status';
		$arrValues_token[] = "Pending";
		$arrFields_token[] = 'app_date';
		$arrValues_token[] = $reschedule_date;
		$arrFields_token[] = 'app_time';
		$arrValues_token[] = $getTime[0]['Timing'];
		
		$updateAppToken=mysqlUpdate('appointment_token_system',$arrFields_token,$arrValues_token,"appoint_trans_id='".$_POST['trans_id']."'");
		
		$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['trans_id']."'" ,"","","","");	
		$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
		
			//Purpose:Appointment token System
				
				//Check Last Appointment Token No
			/*	$getLastAppInfo = mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($visitDate))."'" ,"","","","");
				if(COUNT($getLastAppInfo)>0){
					$getTokenNo = $getLastAppInfo[0]['token_no']+1;
				}
				else{
					$getTokenNo = 1;
				}
				$arrFieldsAppSlot = array();
				$arrValuesAppSlot = array();
				
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = $getTokenNo;
				$arrFieldsAppSlot[] = 'patient_id';
				$arrValuesAppSlot[] = $patientid;
				$arrFieldsAppSlot[] = 'appoint_trans_id';
				$arrValuesAppSlot[] = $_POST['Pat_Trans_Id'];
				$arrFieldsAppSlot[] = 'patient_name';
				$arrValuesAppSlot[] = $txtName;
				$arrFieldsAppSlot[] = 'doc_id';
				$arrValuesAppSlot[] = $admin_id;
				$arrFieldsAppSlot[] = 'doc_type';
				$arrValuesAppSlot[] = "1";
				$arrFieldsAppSlot[] = 'status';
				$arrValuesAppSlot[] = "Pending";
				$arrFieldsAppSlot[] = 'app_date';
				$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
				$arrFieldsAppSlot[] = 'app_time';
				$arrValuesAppSlot[] = $getTime[0]['Timing'];				
				$arrFieldsAppSlot[] = 'created_date';
				$arrValuesAppSlot[] = $curDate;
				$createappointment=mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
				*/
		
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks ";
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:Patient-Profile-Details?p=".md5($getInfo1[0]['patient_id'])."&d=".md5($getInfo1[0]['pref_doc'])."&t=".$_POST['trans_id']);			

}	

//Cancel APPOINTMENT	
//if(isset($_POST['cancelAppoint'])){
if(isset($_GET['canceltransid'])){

	$visitStatus="Cancelled";
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'pay_status';
	$arrValues[]= $visitStatus;
	//Update Patient Status
	$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_GET['canceltransid']."'");
	

	$arrFieldsToken[]= 'status';
	$arrValuesToken[]= $visitStatus;
	//Update Patient Status
	$patientRef=mysqlUpdate('appointment_token_system',$arrFieldsToken,$arrValuesToken,"appoint_trans_id='".$_GET['canceltransid']."'");
	
		$getPatDetails= mysqlSelect("a.patient_name as patient_name,a.patient_id as patient_id,a.Mobile_no as Mobile_no,b.ref_name as doc_name,b.ref_id as doc_id,a.hosp_id as hosp_id","appointment_transaction_detail as a left join referal as b on a.pref_doc=b.ref_id","a.appoint_trans_id='".$_POST['trans_id']."'","","","","");
		
		$longurl = "/SendRequestLink/?d=".md5($getPatDetails[0]['doc_id'])."&hid=".md5($getPatDetails[0]['hosp_id']);
		//Get Shorten Url
		$getUrl= get_shorturl($longurl);
		
		$txtMob = $getPatDetails[0]['Mobile_no'];					
		$recieptmsg= "Your appointment with ".$getPatDetails[0]['doc_name']." has been cancelled. Pls book again using this ".$getUrl." Thanks ";

		send_msg($txtMob,$recieptmsg);
		
	header("Location:Patient-Profile-Details?p=".md5($getPatDetails[0]['patient_id'])."&d=".md5($getPatDetails[0]['doc_id'])."&t=".$_POST['canceltransid']);		
	
}	

if(isset($_POST['update_patient'])){
	$txtName = addslashes($_POST['se_pat_name']);
	$txtMail = addslashes($_POST['se_email']);
	$txtAge = $_POST['se_pat_age'];
	$txtGen = $_POST['se_gender'];
	
	$res_height = $feet.".".$inches;
	$weight = $_POST['weight'];
	
	$txtContact = $_POST['se_con_per'];
	$txtMob = $_POST['se_phone_no'];
	$txtCountry = $_POST['se_country'];
	$txtState = $_POST['se_state'];
	$txtLoc = $_POST['se_city'];
	$txtAddress = addslashes($_POST['se_address']);

	
	$dob = date('Y-m-d',strtotime($_POST['date_birth']));
	
	$patImage = addslashes($_FILES['txtPhoto']['name']);
	
	$arrFields = array();
	$arrValues = array();
	
						
	$arrFields[] = 'patient_name';
	$arrValues[] = $txtName;
	
	if(!empty($_POST['se_pat_age'])){
	$arrFields[] = 'patient_age';
	$arrValues[] = $txtAge;
	}
	
	if(!empty($_POST['date_birth'])){
	$arrFields[] = 'DOB';
	$arrValues[] = $dob;
	}

	$arrFields[] = 'patient_email';
	$arrValues[] = $txtMail;

	$arrFields[] = 'patient_gen';
	$arrValues[] = $txtGen;
	
	$arrFields[] = 'patient_mob';
	$arrValues[] = $txtMob;
	
	$arrFields[] = 'weight';
	$arrValues[] = $weight;
	
	$arrFields[] = 'height_cm';
	$arrValues[] = $_POST['height'];

	$arrFields[] = 'patient_loc';
	$arrValues[] = $txtLoc;

	$arrFields[] = 'pat_state';
	$arrValues[] = $txtState;

	$arrFields[] = 'pat_country';
	$arrValues[] = $txtCountry;

	$arrFields[] = 'patient_addrs';
	$arrValues[] = $txtAddress;
			
	if(!empty($_FILES['txtPhoto']['name'])){
		$arrFields[]="patient_image";
		$arrValues[]=$patImage;
	}
	
	$userupdate=mysqlUpdate('doc_my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
	$patientid = $_POST['patient_id'];


	//UPLOAD COMPRESSED IMAGE
	if ($_FILES["txtPhoto"]["error"] > 0) {
			$error = $_FILES["txtPhoto"]["error"];
	} 
	else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
	($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
	($_FILES["txtPhoto"]["type"] == "image/png") || 
	($_FILES["txtPhoto"]["type"] == "image/pjpeg")) {
	
		$uploaddirectory = realpath("patientImage");
		$uploaddir = $uploaddirectory . "/" .$patientid;
		
		/*Checking whether folder with category id already exist or not. */
	if (file_exists($uploaddir)) {
		//echo "The file $uploaddir exists";
		} else {
		$newdir = mkdir($uploaddirectory . "/" . $patientid, 0777);
	}
		
		
			$url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];

			$filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 60);
			$buffer = file_get_contents($url);

	}else {
			$error = "Uploaded image should be jpg or gif or png";
	}
	$response="updated";
	header("Location:Patient-Profile-Details?p=".md5($patientid)."&d=".md5($_POST['doc_id'])."&t=".$_POST['trans_id']);		
}
	
//Add Patient Attachment
//Add Patient Attachments
if(isset($_POST['addAttachments'])){
	//Save patient episode attachments
				
						$errors= array();
						$timestring = time();
						if(!empty($_POST['upload_user'])){
						$uploadUser = $_POST['upload_user'];
						$userType = "2";
						}
						else
						{
						$uploadUser = $_POST['patient_id'];	
						$userType = "1";						
						}
						$patientId = $_POST['patient_id'];
						$uploaddirectory = realpath("patientAttachments");
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
						$file_size =$_FILES['file-5']['size'][$key];
						$file_tmp =$_FILES['file-5']['tmp_name'][$key];
						$file_type=$_FILES['file-5']['type'][$key];
						
						if(!empty($file_name)){
							$Photo1  = $file_name;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							$arrFields_Attach[] = 'patient_id';
							$arrValues_Attach[] = $patientId;
							
							$arrFields_Attach[] = 'report_title';
							$arrValues_Attach[] = $_POST['report_title'];
							
							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = $timestring;
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $file_name;
							
							$arrFields_Attach[] = 'user_id';
							$arrValues_Attach[] = $uploadUser;
							
							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = $userType;
							
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht=mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid= $bslist_pht;
							
							$folder_name	=	"patientAttachments";
							$sub_folder		=	 $patientId . "/" .$timestring;
							$filename		=	$_FILES['file-5']['name'][$key];
							$file_url		=	$_FILES['file-5']['tmp_name'][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

														
								
							} //End file empty conditions
								
						}//End of foreach
	$response="reports-attached";
	header("Location:Patient-Profile-Details?p=".md5($patientId)."&d=".md5($_POST['doc_id'])."&t=".$_POST['trans_id']);	
	
}

?>