<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment List
 if(API_KEY == $_POST['API_KEY']) {
 
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
		$txtName = $_POST['se_pat_name'];
		$txtMail = $_POST['se_email'];
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
		$patient_id = $_POST['se_patientID'];

		$txtNote1 = addslashes($_POST['se_info']);
		$txtNote2 = addslashes($_POST['se_des']);
		$txtNote3 = addslashes($_POST['se_query']);
		
		$arrFields = array();
		$arrValues = array();

		
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;

		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;

		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;

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

		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;

		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;

		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;

		$arrFields[] = 'doc_id';
		$arrValues[] = $admin_id;

		$arrFields[] = 'system_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
			
		
		$patientprofile = $objQuery->mysqlSelect("*","doc_my_patient","patient_id='".$patient_id."'","","","","");
		$patient_id = (int)$patientprofile[0]['patient_id'];
		
		if ($patient_id > 0)
		{
			$usercraete=$objQuery->mysqlUpdate('doc_my_patient',$arrFields,$arrValues, " patient_id = '". $patient_id ."' ");
			$patientid = $patient_id; //Get Patient Id
		}
		else
		{
			$usercraete=$objQuery->mysqlInsert('doc_my_patient',$arrFields,$arrValues);
			$patientid = mysql_insert_id(); //Get Patient Id
		}
		/* save for doc_my_patient Ends here */
		
		
			
			$episode_desc = $_POST['episode_desc'];
			$episode_medical_complaint =  $_POST['episode_medical_complaint'];
			$episode_special_instruction =  $_POST['episode_special_instruction'];
			
			if ($episode_desc != '' || $episode_medical_complaint != '' || $episode_special_instruction != '') 
			{
			
				$arrFieldsPE = array();
				$arrValuesPE = array();
				$arrFieldsPE[] = 'patient_id';
				$arrValuesPE[] = $patientid;
				$arrFieldsPE[] = 'admin_id';
				$arrValuesPE[] = $admin_id;
				$arrFieldsPE[] = 'episode_desc';
				$arrValuesPE[] = $episode_desc;
				$arrFieldsPE[] = 'episode_medical_complaint';
				$arrValuesPE[] = $episode_medical_complaint;  // Value 1 for "New"
				$arrFieldsPE[] = 'episode_special_instruction';
				$arrValuesPE[] = $episode_special_instruction;
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $Cur_Date;
				
				$insert_patient_episodes=$objQuery->mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = mysql_insert_id(); //Get episode_id
				
				/* save for patient_attachments starts here */
		//Add Patient Attachments functionality
			if($_FILES['txtPhoto']['name']!=""){
					$errors= array();
					foreach($_FILES['txtPhoto']['tmp_name'] as $key => $tmp_name ){

						$file_name = $_FILES['txtPhoto']['name'][$key];
						$file_size =$_FILES['txtPhoto']['size'][$key];
						$file_tmp =$_FILES['txtPhoto']['tmp_name'][$key];
						$file_type=$_FILES['txtPhoto']['type'][$key];


							$Photo1  = $file_name;
							$arrFields1 = array();
							$arrValues1 = array();

							$arrFields1[] = 'my_patient_id';
							$arrValues1[] = $patientid;
							
							$arrFields1[] = 'episode_id';
							$arrValues1[] = $episode_id;

							$arrFields1[] = 'attachments';
							$arrValues1[] = $file_name;

							$bslist_pht=$objQuery->mysqlInsert('doc_patient_attachments',$arrFields1,$arrValues1);
							$id= mysql_insert_id();


							//Uploading image file
							$uploaddirectory = realpath("../premium/episodeAttach");
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

							} //End of foreach
					}
			/* save for patient_attachments ends here */
				
				
				
				/* save for patient_episode_prescriptions starts here */
				$episode_desc = $_POST['prescription_trade_name'];
				while (list($key, $val) = each($_POST['prescription_trade_name']))
				{
					$prescription_trade_name = $_POST['prescription_trade_name'][$key];
					$prescription_generic_name = $_POST['prescription_generic_name'][$key];
					$prescription_dosage_name = $_POST['prescription_dosage_name'][$key];
					$prescription_route = $_POST['prescription_route'][$key];
					$prescription_frequency = $_POST['prescription_frequency'][$key];
					$prescription_instruction = $_POST['prescription_instruction'][$key];
					$prescription_seq = $key;
					$prescription_date_time = $Cur_Date;

					if($prescription_trade_name != "" && $prescription_generic_name != "" )
					{
						$arrFieldsPEP = array();
						$arrValuesPEP = array();
						$arrFieldsPEP[] = 'episode_id';
						$arrValuesPEP[] = $episode_id;
						$arrFieldsPEP[] = 'prescription_trade_name';
						$arrValuesPEP[] = $prescription_trade_name;
						$arrFieldsPEP[] = 'prescription_generic_name';
						$arrValuesPEP[] = $prescription_generic_name;
						$arrFieldsPEP[] = 'prescription_dosage_name';
						$arrValuesPEP[] = $prescription_dosage_name;
						$arrFieldsPEP[] = 'prescription_route';
						$arrValuesPEP[] = $prescription_route;
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'prescription_instruction';
						$arrValuesPEP[] = $prescription_instruction;
						$arrFieldsPEP[] = 'prescription_seq';
						$arrValuesPEP[] = $prescription_seq;
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);

					}
					
				}
			}
				/* save for patient_episode_prescriptions Ends here */
				
				$result = array("result" => "success");
				echo json_encode($result);
		
}


?>