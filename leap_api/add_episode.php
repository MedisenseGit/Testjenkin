<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//State Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patient_id = (int)$_POST['patient_id'];
	$medical_complaint =  $_POST['medical_complaint'];
	$medical_examination =  $_POST['medical_examination'];
	$txt_treatment =  $_POST['txt_treatment'];
	$next_followup_date =  date('Y-m-d',strtotime($_POST['dateadded']));	
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
	if($login_type == 1) {						// Premium LoginType
	
				$arrFieldsPE = array();
				$arrValuesPE = array();
				$arrFieldsPE[] = 'patient_id';
				$arrValuesPE[] = $patient_id;
				$arrFieldsPE[] = 'admin_id';
				$arrValuesPE[] = $admin_id;
				$arrFieldsPE[] = 'treatment';
				$arrValuesPE[] = $txt_treatment;
				$arrFieldsPE[] = 'episode_medical_complaint';
				$arrValuesPE[] = $medical_complaint;  // Value 1 for "New"
				$arrFieldsPE[] = 'examination';
				$arrValuesPE[] = $medical_examination;
				$arrFieldsPE[] = 'next_followup_date';
				$arrValuesPE[] = $next_followup_date;
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $Cur_Date;

				$insert_patient_episodes=$objQuery->mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = mysql_insert_id(); //Get episode_id
				
				//Insert Investigation
				while(list($key_invest, $value_invest) = each($_POST['selectDiagnoSubTestID']))
				{
					
				$investigation_testid = $_POST['selectDiagnoTestID'][$key_invest];
				$investigation_subtestID = $_POST['selectDiagnoSubTestID'][$key_invest];
					
				$arrFieldsInvest = array();
				$arrValuesInvest = array();
				
				$arrFieldsInvest[] = 'test_id';
				$arrValuesInvest[] = $investigation_subtestID;
				$arrFieldsInvest[] = 'patient_id';
				$arrValuesInvest[] = $patient_id;
				$arrFieldsInvest[] = 'episode_id';
				$arrValuesInvest[] = $episode_id;
				$arrFieldsInvest[] = 'doc_id';
				$arrValuesInvest[] = $admin_id;
				$arrFieldsInvest[] = 'doc_type';
				$arrValuesInvest[] = "1";
				$arrFieldsInvest[] = 'main_testid';
				$arrValuesInvest[] = $investigation_testid;
				$insert_investigation=$objQuery->mysqlInsert('patient_investigation',$arrFieldsInvest,$arrValuesInvest);				
				}
				
				//Insert ICD Test
				while(list($key_ICD, $value_ICD) = each($_POST['selectICD']))
				{
				$arrFieldsICD = array();
				$arrValuesICD = array();
				
				$arrFieldsICD[] = 'icd_id';
				$arrValuesICD[] = $value_ICD;
				$arrFieldsICD[] = 'patient_id';
				$arrValuesICD[] = $patient_id;
				$arrFieldsICD[] = 'doc_id';
				$arrValuesICD[] = $admin_id;
				$arrFieldsICD[] = 'episode_id';
				$arrValuesICD[] = $episode_id;
				$arrFieldsICD[] = 'doc_type';
				$arrValuesICD[] = "1";
				$insert_investigation=$objQuery->mysqlInsert('patient_diagnosis',$arrFieldsICD,$arrValuesICD);				
				}
				
				//Save patient episode attachments
				$errors= array();
				foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name ){	
					$file_name = $_FILES['file-5']['name'][$key];
					$file_size =$_FILES['file-5']['size'][$key];
					$file_tmp =$_FILES['file-5']['tmp_name'][$key];
					$file_type=$_FILES['file-5']['type'][$key];
						
					if(!empty($file_name)){
						$Photo1  = $file_name;
						$arrFields_Attach = array();
						$arrValues_Attach  = array();

						$arrFields_Attach[] = 'my_patient_id';
						$arrValues_Attach[] = $patient_id;

						$arrFields_Attach[] = 'attachments';
						$arrValues_Attach[] = $file_name;
							
						$arrFields_Attach[] = 'episode_id';
						$arrValues_Attach[] = $episode_id;
							
						$bslist_pht=$objQuery->mysqlInsert('doc_patient_attachments',$arrFields_Attach,$arrValues_Attach);
						$epiid= mysql_insert_id();

						/* Uploading image file */ 
					    $uploaddirectory = realpath("../premium/episodeAttach");
						$uploaddir = $uploaddirectory . "/" .$epiid;
						$dotpos = strpos($fileName, '.');
						$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $epiid, $Photo1);
						$uploadfile = $uploaddir . "/" . $Photo1;
								
						/*Checking whether folder with category id already exist or not. */
						if (file_exists($uploaddir)) {
							//echo "The file $uploaddir exists";
						} 
						else {
							$newdir = mkdir($uploaddirectory . "/" . $epiid, 0777);
						}
								
						/* Moving uploaded file from temporary folder to desired folder. */
						if(move_uploaded_file ($file_tmp, $uploadfile)) {
							//echo "File uploaded.";
						} else {
							//echo "File cannot be uploaded";
						}
							
						} //End file empty conditions
				}
				//End of foreach Attachments
				
				/* save for patient_episode_prescriptions starts here */
				$episode_desc = $_POST['prescription_trade_name'];
				while (list($key, $val) = each($_POST['prescription_trade_name']))
				{
					$prescription_trade_name = $_POST['prescription_trade_name'][$key];
					$prescription_generic_name = $_POST['prescription_generic_name'][$key];
					$prescription_frequency = $_POST['prescription_frequency'][$key];
					$prescription_timing = $_POST['prescription_timing'][$key];
					$prescription_duration = $_POST['prescription_duration'][$key];
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
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'timing';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'duration';
						$arrValuesPEP[] = $prescription_duration;
						$arrFieldsPEP[] = 'prescription_instruction';
						$arrValuesPEP[] = $prescription_instruction;
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);

					}
					
				}
				/* save for patient_episode_prescriptions Ends here */
	
		
				$result = array("result" => "success");
				echo json_encode($result);
	}
	else {
			$success = array('result' => "failure");
			echo json_encode($success);
	}	
	
}


?>