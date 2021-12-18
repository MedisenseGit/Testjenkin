<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

require_once("../DigitalOceanSpaces/src/upload_function.php");


include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if($headers)
{
    $user_id 	= $headers['user-id'];
	$timestamp 	= $headers['x-timestamp'];
	$hashKey 	= $headers['x-hash'];
	$device_id 	= $headers['device-id'];
}

$postdata  = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);



/*if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {*/
		$txtName 	= $_POST['member_name'];
		$member_id 	= $_POST['member_id'];
		$login_id 	= $user_id;
		
		//$doc_id = $_POST['doc_id'];
		//$spec_id = $_POST['spec_id'];
		//$data_source = $_POST['data_source'];
		
		$login_user_name = $_POST['opinionContactPerson']; //It should be login user actual name
		$txtAge 		 = $_POST['member_age'];
		$txtGen 		 = $_POST['member_gender'];			// 1- Male, 2-Female, 3-Other, 0-Not mentioned
		$txtHeight       = $_POST['member_height'];
		$txtWeight       = $_POST['member_weight'];
		$bloodGroup 	 = $_POST['member_blood_group']; 
		
		$txtBP 			 = $_POST['member_bp']; 
		$thyroidCond 	 = $_POST['member_thyroid']; 			// 1-NO, 2-YES, 0-NOT MENTIONED
		
		$hyperCondOrg 	 = $_POST['member_hypertension'];   // 1-NO, 2-YES, 0-NOT MENTIONED
		$diabetesCondOrg = $_POST['member_diabetic'];	 // 1-NO, 2-YES, 0-NOT MENTIONED
		
		$asthamaCondOrg  = $_POST['member_asthama']; 		 // 1-NO, 2-YES, 0-NOT MENTIONED
		$cholestrolCondOrg  = $_POST['member_cholestrol'];	 // 1-NO, 2-YES, 0-NOT MENTIONED
		
		$epilepsyCondOrg    = $_POST['member_epilepsy'];	 // 1-NO, 2-YES, 0-NOT MENTIONED
		
		$txtAllergies    = $_POST['member_allergies'];
		$txtSmoking      = $_POST['member_smoking'];
		$txtAlcohol      = $_POST['member_alcohol'];
		//$cholesterol      = $_POST['member_alcohol'];
		
		  
		
		$txtNote1 		 = addslashes($_POST['opinionMedicalComplaint']);
		$txtNote2 		 = addslashes($_POST['opinionMedicalDescription']);
		$txtNote3 		 = addslashes($_POST['opinionMedicalQueryDoctor']);
		$txtMail 		 = $_POST['user_email'];
		$txtMob 		 = addslashes($_POST['contact_num']);
		$txtAddress 	 = addslashes($_POST['opinionAddress']);
		$txtPincode 	 = addslashes($_POST['opinionPincode']);
		$txtLoc 		 = addslashes($_POST['opinionCity']);
		$txtState 		 = addslashes($_POST['opinionState']);
		$txtCountry 	 = addslashes($_POST['opinionCountry']);
		$data_source     = "Android";
		
		$preferredCountry = $_POST['opinion_preferred_country'];
		$preferredHospital = $_POST['opinion_preferred_hospital'];
		$preferredDoctor = $_POST['opinion_preferred_doctor'];

		$patAttachments = $_FILES['file-3']['name'];
		
		if($hyperCondOrg == 1)   //1 for Yes, 2 for No,0 for NA 
		{				
			$hyperCond = 2;
		}
		else if($hyperCondOrg == 2)
		{
			$hyperCond = 1;
		}
		else
		{
			$hyperCond = 0;
		}
		
		if($diabetesCondOrg == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$diabetesCond = 2;
		}
		else if($diabetesCondOrg == 2)
		{
			$diabetesCond = 1;
		}
		else 
		{
			$diabetesCond = 0;
		}
		
		if($asthamaCondOrg == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$asthamaCond = 2;
		}
		else if($asthamaCondOrg == 2)
		{
			$asthamaCond = 1;
		}
		else 
		{
			$asthamaCond = 0;
		}
		
		if($cholestrolCondOrg == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$cholestrolCond = 2;
		}
		else if($cholestrolCondOrg == 2)
		{
			$cholestrolCond = 1;
		}
		else 
		{
			$cholestrolCond = 0;
		}
		
		if($epilepsyCondOrg == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$epilepsyCond = 2;
		}
		else if($epilepsyCondOrg == 2)
		{
			$epilepsyCond = 1;
		}
		else 
		{
			$epilepsyCond = 0;
		}
		
		if($txtAllergies == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$txtAllergiesCond = 2;
		}
		else if($txtAllergies == 2)
		{
			$txtAllergiesCond = 1;
		}
		else 
		{
			$txtAllergiesCond = 0;
		}
		
		if($txtSmoking == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$txtSmokingCond = 2;
		}
		else if($txtSmoking == 2)
		{
			$txtSmokingCond = 1;
		}
		else 
		{
			$txtSmokingCond = 0;
		}
		
		if($txtAlcohol == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$txtAlcoholCond = 2;
		}
		else if($txtAlcohol == 2)
		{
			$txtAlcoholCond = 1;
		}
		else 
		{
			$txtAlcoholCond = 0;
		}
		
		
		if($txtBP == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$txtBPCond = 2;
		}
		else if($txtBP == 2)
		{
			$txtBPCond = 1;
		}
		else 
		{
			$txtBP = 0;
		}
		
		
		if($thyroidCond == 1) //1 for Yes, 2 for No,0 for NA 
		{				
			$thyroidcond = 2;
		}
		else if($thyroidCond == 2)
		{
			$thyroidcond = 1;
		}
		else 
		{
			$thyroidcond = 0;
		}
		//exit();
		
		//$getPrefDoc= mysqlSelect("a.ref_name as doc_name,c.hosp_name as hosp_name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$doc_id."'","","","","");
		$TransId=time();
		
		$getSrcInfo = $objQuery->mysqlSelect("*","source_list","partner_id='".$login_id."' and src_type='1'" ,"","","","");
		$src_id = $getSrcInfo[0]['source_id'];
			

			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'TImestamp';
			$arrValues[] = $Cur_Date;

			$arrFields[] = 'member_id';
			$arrValues[] = $member_id;

			$arrFields[] = 'login_user_id';
			$arrValues[] = $login_id;

			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;

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
			
			$arrFields[] = 'bp';
			$arrValues[] = $txtBP;
			
			$arrFields[] = 'cholesterol';
			$arrValues[] = $cholestrolCond;
			
			$arrFields[] = 'thyroid';
			$arrValues[] = $thyroidcond;
			
			$arrFields[] = 'asthama';
			$arrValues[] = $asthamaCond;
			
			$arrFields[] = 'epilepsy';
			$arrValues[] = $epilepsyCond;
			
			$arrFields[] = 'allergies_any';
			$arrValues[] = $txtAllergiesCond;
			
			$arrFields[] = 'smoking';
			$arrValues[] = $txtSmokingCond;
			
			$arrFields[] = 'alcohol';
			$arrValues[] = $txtAlcoholCond;
			

			$arrFields[] = 'contact_person';
			$arrValues[] = $login_user_name;

			$arrFields[] = 'patient_mob';
			$arrValues[] = $txtMob;

			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;
			
			$arrFields[] = 'patient_addrs';
			$arrValues[] = $txtAddress;
			
			$arrFields[] = 'patient_loc';
			$arrValues[] = $txtLoc;
			
			$arrFields[] = 'pat_state';
			$arrValues[] = $txtState;
			
			$arrFields[] = 'pat_country';
			$arrValues[] = $txtCountry;

			$arrFields[] = 'patient_src';
			$arrValues[] = $src_id;

			$arrFields[] = 'currentTreatDoc';
			$arrValues[] = $txtTreatDoc;
			
			$arrFields[] = 'currentTreatHosp';
			$arrValues[] = $txtTreatHosp;
			
			$arrFields[] = 'medDept';
			$arrValues[] = $spec_id;

			$arrFields[] = 'patient_complaint';
			$arrValues[] = $txtNote1;
			
			$arrFields[] = 'patient_desc';
			$arrValues[] = $txtNote2;
			
			$arrFields[] = 'pat_query';
			$arrValues[] = $txtNote3;

			$arrFields[] = 'user_id';
			$arrValues[] = '9';
			
			$arrFields[] = 'company_id';
			$arrValues[] = '3';

			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;

			$arrFields[] = 'transaction_id';
			$arrValues[] = $TransId;

			/*$arrFields[] = 'pref_hosp';
			$arrValues[] = $getPrefDoc[0]['hosp_name'];
			$arrFields[] = 'pref_doc';
			$arrValues[] = $getPrefDoc[0]['doc_name']; */

			$arrFields[] = 'data_source';
			$arrValues[] = $data_source;
			
			$arrFields[] = 'service_type';
			$arrValues[] = '2';
			
			$arrFields[] = 'pref_country';
			$arrValues[] = $preferredCountry;
			
			$arrFields[] = 'pref_doc';
			$arrValues[] = $preferredDoctor;
			
			$arrFields[] = 'pref_hosp';
			$arrValues[] = $preferredHospital;



			$usercraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
			$id = Mysql_insert_id();

			//Add Patient Attachments functionality
			$errors= array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
			{

					$file_name	 = $_FILES['file-3']['name'][$key];
					$file_size 	 = $_FILES['file-3']['size'][$key];
					$file_tmp    = $_FILES['file-3']['tmp_name'][$key];
					$file_type   = $_FILES['file-3']['type'][$key];

					if(!empty($file_name))
					{
						$Photo1  = $file_name;
						$arrFields_attach = array();
						$arrValues_attach = array();

						$arrFields_attach[] = 'patient_id';
						$arrValues_attach[] = $id;

						$arrFields_attach[] = 'attachments';
						$arrValues_attach[] = $file_name;

						$pat_attach=$objQuery->mysqlInsert('patient_attachment',$arrFields_attach,$arrValues_attach);
						$attachid= mysql_insert_id();

						/*$folder_name	=	"Attach";
						$sub_folder		=	$attachid;
						$filename		=	$_FILES['file-3']['name'][$key];
						$file_url		=	$_FILES['file-3']['tmp_name'][$key];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload*/
						
						//Uploading image file
						$uploaddirectory = realpath("../Attach");
						$uploaddir = $uploaddirectory . "/" .$attachid;
						$dotpos = strpos($fileName, '.');
						$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
						$uploadfile = $uploaddir . "/" . $Photo1;


						//Checking whether folder with category id already exist or not.
						if (file_exists($uploaddir)) 
						{
							echo "The file $uploaddir exists";
						} 
						else 
						{
								$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
							
						}

							//Moving uploaded file from temporary folder to desired folder.
							if(move_uploaded_file ($file_tmp, $uploadfile)) 
							{

								$successAttach="";
									
							} 
							else
							{
										echo "File cannot be uploaded";
							}
					}

			}
				//End of foreach


			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[] = 'patient_id';
			$arrValues1[] = $id;
			
			$arrFields1[] = 'ref_id';
			$arrValues1[] = $doc_id;
			
			$arrFields1[] = 'status1';
			$arrValues1[] = "1";
			
			$arrFields1[] = 'status2';
			$arrValues1[] = "1";
			
			$arrFields1[] = 'bucket_status';
			$arrValues1[] = "1";
			
			$arrFields1[] = 'timestamp';
			$arrValues1[] = $Cur_Date;

			$createreferral=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);

			$msg="Patient Registered on ".$Cur_Date;
			
			$arrFields2 = array();
			$arrValues2 = array();
			
			$arrFields2[] = 'patient_id';
			$arrValues2[] = $id;
			
			$arrFields2[] = 'ref_id';
			$arrValues2[] = "0";
			
			$arrFields2[] = 'chat_note';
			$arrValues2[] = $msg;
			
			$arrFields2[] = 'user_id';
			$arrValues2[] = "9";
			
			$arrFields2[] = 'TImestamp';
			$arrValues2[] = $Cur_Date;

			$userchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);


			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$id."'" ,"","","","");


		
		$success_opinion = array('result' => "success", 'status' => '1', 'message' => "Your medical tourism request has been sent successfully. \n\nYou will receive an update within 24-48hrs. ", 'err_msg' => '');
		echo json_encode($success_opinion);
		
	/*}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}*/


?>
