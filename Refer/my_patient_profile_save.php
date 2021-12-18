<?php ob_start();
	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];
	if(empty($admin_id)){
		header("Location:index.php");
	}

	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d h:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

	$TransId=time();
	//$ccmail="medical@medisense.me";

	include('send_mail_function.php');
	include('send_text_message.php');
	require_once("../classes/querymaker.class.php");
	$objQuery = new CLSQueryMaker();
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

	//SAVE PATIENT
	
	if(isset($_POST['save_patient']) || isset($_POST['save_patient_edit']) ){ //TO CHECK AUTHENTICATION OF POST VALUES
		//echo "<pre>"; print_r($_POST); exit;
		$txtName = addslashes($_POST['se_pat_name']);
		$txtMail = addslashes($_POST['se_email']);
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

		$txtNote1 = addslashes($_POST['se_info']);
		$txtNote2 = addslashes($_POST['se_des']);
		$txtNote3 = addslashes($_POST['se_query']);

		$patient_id = (int)$_POST['patient_id'];

		//Get Source Id from Our Partner table
		$getSourceId= $objQuery->mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","a.partner_id='".$admin_id."'","","","","");
		//print_r($getSourceId); exit;

		$PatientSource=$getSourceId[0]['source_id'];
			/* save for patient_tab starts here 
			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;
			//echo $txtName; exit;
			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;
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

			$arrFields[] = 'contact_person';
			$arrValues[] = $txtContact;
			$arrFields[] = 'patient_mob';
			$arrValues[] = $txtMob;
			$arrFields[] = 'patient_addrs';
			$arrValues[] = $txtAddress;
			$arrFields[] = 'patient_loc';
			$arrValues[] = $txtLoc;
			$arrFields[] = 'pat_state';
			$arrValues[] = $txtState;
			$arrFields[] = 'pat_country';
			$arrValues[] = $txtCountry;
			$arrFields[] = 'patient_src';
			$arrValues[] = $PatientSource;

			$arrFields[] = 'currentTreatDoc';
			$arrValues[] = $txtTreatDoc;
			$arrFields[] = 'currentTreatHosp';
			$arrValues[] = $txtTreatHosp;
			$arrFields[] = 'medDept';
			$arrValues[] = $patDept;

			$arrFields[] = 'patient_complaint';
			$arrValues[] = $txtNote1;
			$arrFields[] = 'patient_desc';
			$arrValues[] = $txtNote2;
			$arrFields[] = 'pat_query';
			$arrValues[] = $txtNote3;

			$arrFields[] = 'company_id';
			$arrValues[] = $admin_id;
			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;

			$arrFields[] = 'transaction_id';
			$arrValues[] = $TransId;

			if ($patient_id > 0)
			{
				$usercraete=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues, " patient_id = '". $patient_id ."' ");
				$patientid = $patient_id; //Get Patient Id
			}
			else
			{
				$usercraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
				$patientid = mysql_insert_id(); //Get Patient Id
			}*/

			/* save for patient_tab ends here */

			/* save for my_patient starts here */
			/* insert for my_patient 
			unset($arrFields);
			unset($arrValues);*/

			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'patient_id';
			$arrValues[] = $patientid;

			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;

			$arrFields[] = 'patient_age';
			$arrValues[] = $txtAge;

			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;

			$arrFields[] = 'patient_gen';
			$arrValues[] = $txtGen;

			/*merital_status*/
			/*qualification*/

			$arrFields[] = 'weight';
			$arrValues[] = $txtWeight;

			$arrFields[] = 'hyper_cond';
			$arrValues[] = $hyperCond;

			$arrFields[] = 'diabetes_cond';
			$arrValues[] = $diabetesCond;

			/*pat_blood*/

			$arrFields[] = 'contact_person';
			$arrValues[] = $txtContact;

			/*profession*/

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

			$arrFields[] = 'user_id';
			$arrValues[] = '9';

			$arrFields[] = 'partner_id';
			$arrValues[] = $admin_id;

			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;
			/*
			$arrFields[] = 'transaction_id';
			$arrValues[] = $TransId;
			*/

			$patientprofile = $objQuery->mysqlSelect("*","my_patient","patient_id='".$patient_id."'","","","","");
			$patient_id = (int)$patientprofile[0]['patient_id'];


			if ($patient_id > 0)
			{
				$usercraete=$objQuery->mysqlUpdate('my_patient',$arrFields,$arrValues, " patient_id = '". $patient_id ."' ");
				$patientid = $patient_id; //Get Patient Id
			}
			else
			{
				$usercraete=$objQuery->mysqlInsert('my_patient',$arrFields,$arrValues);
				$patientid = mysql_insert_id(); //Get Patient Id
			}
			/* save for my_patient Ends here */


			/* save for patient_attachments starts here */

			//echo $usercraete; print_r($usercraete); var_dump($usercraete); exit;
			//$patientid = mysql_insert_id();

			//Add Patient Attachments functionality
			if($_FILES['file-3']['name']!=""){
									$errors= array();
								foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){

								$file_name = $_FILES['file-3']['name'][$key];
								$file_size =$_FILES['file-3']['size'][$key];
								$file_tmp =$_FILES['file-3']['tmp_name'][$key];
								$file_type=$_FILES['file-3']['type'][$key];


									$Photo1  = $file_name;
									$arrFields1 = array();
									$arrValues1 = array();

									$arrFields1[] = 'patient_id';
									$arrValues1[] = $patientid;

									$arrFields1[] = 'attachments';
									$arrValues1[] = $file_name;

									$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
									$id= mysql_insert_id();


									//Uploading image file
										$uploaddirectory = realpath("../Attach");
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


								}
							//End of foreach
					}

			/* save for patient_attachments ends here */

			/* save for patient_referal starts here */

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
			$arrValues2[] = "1";  // Value 1 for "New"
			$arrFields2[] = 'bucket_status';
			$arrValues2[] = "1";
			$arrFields2[] = 'timestamp';
			$arrValues2[] = $Cur_Date;

			$insertpatref=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);
			//echo $insertpatref; exit;
			/* save for patient_referal starts here */

			/* save for patient_episodes starts here */
			/*
				episode_id
				admin_id
				patient_id
				episode_desc
				episode_medical_complaint
				episode_special_instruction
				date_time


				CREATE TABLE IF NOT EXISTS `patient_episodes` (
				  `episode_id` int(11) NOT NULL AUTO_INCREMENT,
				  `admin_id` int(11) DEFAULT NULL,
				  `patient_id` int(11) DEFAULT NULL,
				  `episode_desc` text,
				  `episode_medical_complaint` text,
				  `episode_special_instruction` text,
				  `date_time` datetime DEFAULT NULL,
				  PRIMARY KEY (`episode_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


				episode_id
				prescription_trade_name
				prescription_generic_name
				prescription_route
				prescription_frequency
				prescription_instruction
				date_time


				CREATE TABLE IF NOT EXISTS `patient_episode_prescriptions` (
				  `episode_prescription_id` bigint(18) NOT NULL AUTO_INCREMENT,
				  `episode_id` int(11) DEFAULT NULL,
				  `prescription_trade_name` text,
				  `prescription_generic_name` text,
				  `prescription_route` text,
				  `prescription_frequency` text,
				  `prescription_instruction` text,
				  `prescription_seq` int(11) DEFAULT NULL,
				  `prescription_date_time` datetime DEFAULT CURRENT_TIMESTAMP,
				  PRIMARY KEY (`episode_prescription_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


				chkSaveTemplate
				template_name

					admin_id
					template_id
					template_name

					CREATE TABLE IF NOT EXISTS `patient_episode_prescription_templates` (
					  `template_id` int(11) NOT NULL AUTO_INCREMENT,
					  `admin_id` int(11) NOT NULL,
					  `template_name` varchar(255) DEFAULT NULL,
					  PRIMARY KEY (`template_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


					template_id
					admin_id
					prescription_trade_name
					prescription_generic_name
					prescription_route
					prescription_frequency
					prescription_instruction


					CREATE TABLE IF NOT EXISTS `patient_episode_prescription_template_details` (
					  `template_id` int(11) NOT NULL,
					  `admin_id` int(11) NOT NULL,
					  `prescription_seq` int(11) NOT NULL,
					  `prescription_trade_name` text,
					  `prescription_generic_name` text,
					  `prescription_route` text,
					  `prescription_frequency` text,
					  `prescription_instruction` text
					) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			*/
			$episode_desc = $_POST['episode_desc'];
			$episode_medical_complaint =  $_POST['episode_medical_complaint'];
			$episode_special_instruction =  $_POST['episode_special_instruction'];

			//if ($episode_desc != '' || $episode_medical_complaint != '' || $episode_special_instruction != '')
			
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

				$insert_patient_episodes=$objQuery->mysqlInsert('patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = mysql_insert_id(); //Get episode_id

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
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);

					}
					
				}
				/* save for patient_episode_prescriptions Ends here */

				$chkSaveTemplate = $_POST['chkSaveTemplate'];
				if ($chkSaveTemplate == '1')
				{
					/* save for patient_episode_prescription_templates starts here */
					$template_name = $_POST['template_name'];
					if ($template_name == '')
					{
						$template_name = 'Template';
					}

					$arrFieldsPEPT = array();
					$arrValuesPEPT = array();
					$arrFieldsPEPT[] = 'admin_id';
					$arrValuesPEPT[] = $admin_id;
					$arrFieldsPEPT[] = 'template_name';
					$arrValuesPEPT[] = $template_name;					

					$insert_patient_episode_prescription_template = $objQuery->mysqlInsert('patient_episode_prescription_templates',$arrFieldsPEPT,$arrValuesPEPT);
					$template_id = mysql_insert_id(); //Get episode_id
					/* save for patient_episode_prescription_templates Ends here */

					/* save for patient_episode_prescription_template_details starts here */
					reset($_POST['prescription_trade_name']);
					while (list($key, $val) = each($_POST['prescription_trade_name']))
					{					
						$prescription_trade_name = $_POST['prescription_trade_name'][$key];
						$prescription_generic_name = $_POST['prescription_generic_name'][$key];
						$prescription_dosage_name = $_POST['prescription_dosage_name'][$key];
						$prescription_route = $_POST['prescription_route'][$key];
						$prescription_frequency = $_POST['prescription_frequency'][$key];
						$prescription_instruction = $_POST['prescription_instruction'][$key];
						$prescription_seq = $key;

						$arrFieldsPEPTD = array();
						$arrValuesPEPTD = array();
						$arrFieldsPEPTD[] = 'template_id';
						$arrValuesPEPTD[] = $template_id;
						$arrFieldsPEPTD[] = 'admin_id';
						$arrValuesPEPTD[] = $admin_id;	
						$arrFieldsPEPTD[] = 'prescription_seq';
						$arrValuesPEPTD[] = $prescription_seq;
						$arrFieldsPEPTD[] = 'prescription_trade_name';
						$arrValuesPEPTD[] = $prescription_trade_name;
						$arrFieldsPEPTD[] = 'prescription_generic_name';
						$arrValuesPEPTD[] = $prescription_generic_name;
						$arrFieldsPEPTD[] = 'prescription_dosage_name';
						$arrValuesPEPTD[] = $prescription_dosage_name;
						$arrFieldsPEPTD[] = 'prescription_route';
						$arrValuesPEPTD[] = $prescription_route;
						$arrFieldsPEPTD[] = 'prescription_frequency';
						$arrValuesPEPTD[] = $prescription_frequency;
						$arrFieldsPEPTD[] = 'prescription_instruction';
						$arrValuesPEPTD[] = $prescription_instruction;

						$insert_patient_episode_prescription_template_desc = $objQuery->mysqlInsert('patient_episode_prescription_template_details',$arrFieldsPEPTD,$arrValuesPEPTD);
						
					}
					/* save for patient_episode_prescription_template_details ends here */
				}



			

		//save for next appointment date & time	
		if($_POST['follow_date']!= '' || $_POST['selectTime']!= ''){
				
			
			$trans_id=time();
				$arrFields_app=array();
				$arrValues_app=array();
				
				$arrFields_app[] = 'Transaction_id';
				$arrValues_app[] = $trans_id;
				$arrFields_app[] = 'pat_name';
				$arrValues_app[] = $txtName;
				$arrFields_app[] = 'Email_id';
				$arrValues_app[] = $txtMail;
				$arrFields_app[] = 'Mobile_number';
				$arrValues_app[] = $txtMob;
				$arrFields_app[] = 'pat_age';
				$arrValues_app[] = $txtAge;
				$arrFields_app[] = 'pat_gen';
				$arrValues_app[] = $txtGen;
				$arrFields_app[] = 'City';
				$arrValues_app[] = $txtLoc;
				$arrFields_app[] = 'State';
				$arrValues_app[] = $txtState;
				$arrFields_app[] = 'Country';
				$arrValues_app[] = $txtCountry;
				$arrFields_app[] = 'Address';
				$arrValues_app[] = $txtAddress;
		
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields_app,$arrValues_app);
				$newvisitorid= mysql_insert_id();
				
							
				$arrFields_app1[] = 'appoint_trans_id';
				$arrValues_app1[] = $trans_id;
				$arrFields_app1[] = 'pref_doc';
				$arrValues_app1[] = $admin_id ;
				$arrFields_app1[] = 'user_type';
				$arrValues_app1[] = "1";
				$arrFields_app1[] = 'Visiting_date';
				$arrValues_app1[] = $_POST['follow_date'];
				$arrFields_app1[] = 'Visiting_time';
				$arrValues_app1[] = $_POST['selectTime'];
				$arrFields_app1[] = 'patient_name';
				$arrValues_app1[] = $txtName;
				$arrFields_app1[] = 'Mobile_no';
				$arrValues_app1[] = $txtMob;
				$arrFields_app1[] = 'Email_address';
				$arrValues_app1[] = $txtMail;
				$arrFields_app1[] = 'pay_status';
				$arrValues_app1[] = "Pending";
				$arrFields_app1[] = 'visit_status';
				$arrValues_app1[] = "new_visit";
				$arrFields_app1[] = 'Time_stamp';
				$arrValues_app1[] = $Cur_Date;
				
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields_app1,$arrValues_app1);
				
		}	
		/* save for next appointment date & time ends here */
		
		
		$response="success";
		//header("Location:All-Patient-Records?response=".$response);

		/**/
		//echo "redirecting"; exit;
		header("Location:my_patient_list.php");
		//header("Location:My-Patient-List?response=".$response);
	}
	else{
		$response="error";
		header("Location:Add-Patient?response=".$response);
	}




	//ADD PATIENT & REFER TO DOCTOR
	if(isset($_POST['refer_patient'])){ //TO CHECK AUTHENTICATION OF POST VALUES
		$txtName = addslashes($_POST['se_pat_name']);
		$txtMail = addslashes($_POST['se_email']);
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

		$txtNote1 = addslashes($_POST['se_info']);
		$txtNote2 = addslashes($_POST['se_des']);
		$txtNote3 = addslashes($_POST['se_query']);
		//$docid = addslashes($_POST['selectref']);
		$docid = '';
		$refpartner = addslashes($_POST['selectRefpartner']);

		//Get Source Id from Our Partner table
		$getSourceId= $objQuery->mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","a.partner_id='".$admin_id."'","","","","");
		//print_r($getSourceId);
		$PatientSource=$getSourceId[0]['source_id'];

		/* Insert for patient_tab */
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

			$arrFields[] = 'patient_src';
			$arrValues[] = $PatientSource;

			$arrFields[] = 'currentTreatDoc';
			$arrValues[] = $txtTreatDoc;

			$arrFields[] = 'currentTreatHosp';
			$arrValues[] = $txtTreatHosp;

			$arrFields[] = 'medDept';
			$arrValues[] = $patDept;

			$arrFields[] = 'patient_complaint';
			$arrValues[] = $txtNote1;

			$arrFields[] = 'patient_desc';
			$arrValues[] = $txtNote2;

			$arrFields[] = 'pat_query';
			$arrValues[] = $txtNote3;

			$arrFields[] = 'assigned_to';
			$arrValues[] = '0';

			$arrFields[] = 'user_id';
			$arrValues[] = '9';

			$arrFields[] = 'company_id';
			$arrValues[] = $admin_id;

			/*
			$arrFields[] = 'partner_id';
			$arrValues[] = $admin_id;
			*/

			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;

			$arrFields[] = 'transaction_id';
			$arrValues[] = $TransId;


			$usercraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
			$patientid = mysql_insert_id();

			/* insert for my_patient */
			unset($arrFields);
			unset($arrValues);

			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'patient_id';
			$arrValues[] = $patientid;

			$arrFields[] = 'patient_name';
			$arrValues[] = $txtName;

			$arrFields[] = 'patient_age';
			$arrValues[] = $txtAge;

			$arrFields[] = 'patient_email';
			$arrValues[] = $txtMail;

			$arrFields[] = 'patient_gen';
			$arrValues[] = $txtGen;

			/*merital_status*/
			/*qualification*/

			$arrFields[] = 'weight';
			$arrValues[] = $txtWeight;

			$arrFields[] = 'hyper_cond';
			$arrValues[] = $hyperCond;

			$arrFields[] = 'diabetes_cond';
			$arrValues[] = $diabetesCond;

			/*pat_blood*/

			$arrFields[] = 'contact_person';
			$arrValues[] = $txtContact;

			/*profession*/

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

			/*
			$arrFields[] = 'patient_src';
			$arrValues[] = $PatientSource;


			$arrFields[] = 'currentTreatDoc';
			$arrValues[] = $txtTreatDoc;

			$arrFields[] = 'currentTreatHosp';
			$arrValues[] = $txtTreatHosp;

			$arrFields[] = 'medDept';
			$arrValues[] = $patDept;

			$arrFields[] = 'patient_complaint';
			$arrValues[] = $txtNote1;

			$arrFields[] = 'patient_desc';
			$arrValues[] = $txtNote2;

			$arrFields[] = 'pat_query';
			$arrValues[] = $txtNote3;

			$arrFields[] = 'assigned_to';
			$arrValues[] = '0';
			*/
			$arrFields[] = 'user_id';
			$arrValues[] = '9';

			/*
			$arrFields[] = 'company_id';
			$arrValues[] = $admin_id;
			*/

			$arrFields[] = 'partner_id';
			$arrValues[] = $admin_id;

			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;
			/*
			$arrFields[] = 'transaction_id';
			$arrValues[] = $TransId;
			*/

			$usercraete=$objQuery->mysqlInsert('my_patient',$arrFields,$arrValues);
			echo $usercraete; print_r($usercraete); var_dump($usercraete); exit;
			//$patientid = mysql_insert_id();

			//Add Patient Attachments functionality
			if($_FILES['file-3']['name']!=""){
									$errors= array();
								foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){

								$file_name = $_FILES['file-3']['name'][$key];
								$file_size =$_FILES['file-3']['size'][$key];
								$file_tmp =$_FILES['file-3']['tmp_name'][$key];
								$file_type=$_FILES['file-3']['type'][$key];


									$Photo1  = $file_name;
									$arrFields1 = array();
									$arrValues1 = array();

									$arrFields1[] = 'patient_id';
									$arrValues1[] = $patientid;

									$arrFields1[] = 'attachments';
									$arrValues1[] = $file_name;

									$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
									$id= mysql_insert_id();


									//Uploading image file
										$uploaddirectory = realpath("../Attach");
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


								}
							//End of foreach
					}

			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patientid."'" ,"","","","");

			$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$patientid."'" ,"","","","");

			$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");

			$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");

			$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$docid."'","","","","");

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

			$insertchat=$objQuery->mysqlInsert('chat_notification',$arrFields3,$arrValues3);



		/*if($getPatInfo[0]['patient_email']!=""){
			if($getPatInfo[0]['patient_src']=="11"){
			$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_mediassist.jpg";
			}
			else{
				$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_low1.jpg";
			}

						$url_page = 'afterregister_patientmail.php';
						$url = rawurlencode($url_page);
						$url .= "?patmail=".urlencode($getPatInfo[0]['patient_email']);
						$url .= "&patid=".urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=".urlencode($getPatInfo[0]['patient_name']);
						$url .= "&mailtemp=".urlencode($mailTemplate);
						send_mail($url);

			}	*/


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
						send_mail($url);
					}



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
						if($doc_contact!="" && $get_pro[0]['message_status']==1)
						{
						$mobile = $doc_contact;
						$msg = "Dear Doctor, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . "( Patient ID: ". $getPatInfo[0]['patient_id'] .") by mail, Kindly check your mail for further details - Many Thanks".$get_pro[0]['hosp_name'];

						send_msg($mobile,$msg);
						}

						//SMS notification to Patient
						if($getPatInfo[0]['patient_mob']!=""){
						$mobile = $getPatInfo[0]['patient_mob'];
						$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query with Patient Id ". $getPatInfo[0]['patient_id'] . " has been successfully registered & referred to ".$get_pro[0]['ref_name']." Kindly check your mail for further details.- Many Thanks ".$get_pro[0]['hosp_name'];

						send_msg($mobile,$responsemsg);

						}

		if(!empty($getPatInfo[0]['patient_email'])){
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
		$response="success";
		header("Location:All-Patient-Records?response=".$response);
	}
	else{

		if(isset($_POST['save_patient_edit']))
		{
			header("Location:My-Patient-Profile?p=". md5($patient_id));
		}
		else
		{
			header("Location:My-Patient-List?response=".$response);
		}
	}
?>