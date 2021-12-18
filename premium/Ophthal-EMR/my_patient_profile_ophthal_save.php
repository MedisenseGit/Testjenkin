<?php 
	ob_start();
	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];
	$Hosp_Id = $_SESSION['login_hosp_id'];
	if(empty($admin_id)){
		header("Location:index.php");
	}

	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

	$TransId=time();
	//$ccmail="medical@medisense.me";



	include('send_mail_function.php');
	include('send_text_message.php');
	require_once("../../classes/querymaker.class.php");
	require_once("../../DigitalOceanSpaces/src/upload_function.php");
	//$objQuery = new CLSQueryMaker();
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
//CHANGE PATIENT EPISODE DATE
	if(isset($_POST['changeDate']))
	{
		$arrFields[] = 'date_time';
		$arrValues[] = $_POST['J-demo-02'];
		$userupdate=mysqlUpdate('doc_patient_episodes',$arrFields,$arrValues, "md5(episode_id) = '".$_POST['episode_id']."'");
		
		$response="date-updated";
		header("Location:".$_SESSION['EMR_URL'].md5($_POST['patient_id'])."&episode=".$_POST['episode_id']."&visit=".$_POST['visit']."&response=".$response);
	}
if(isset($_POST['updateDiagnoInvestigation']))
{
	while(list($key_invest, $value_invest) = each($_POST['investigation_id']))
	{
		$arrFiedInvest=array();
		$arrValueInvest=array();

		if(!empty($_POST['actualVal'][$key_invest]))
		{
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


						$errors= array();
						$timestring = time();
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
							$epiid		= $bslist_pht;
							
							$folder_name	=	"patientAttachments";
							$sub_folder		=	$patientId;
							$filename		=	$_FILES['file-5']['name'][$key];
							$file_url		=	$_FILES['file-5']['tmp_name'][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

							/* Uploading image file */

								/* $dotpos = strpos($fileName, '.');
								 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
								 $uploadfile = $uploaddir . "/" . $Photo1;*/


								/* Moving uploaded file from temporary folder to desired folder. */
								/*if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								}
								else 
								{
									//echo "File cannot be uploaded";
								}*/

							} //End file empty conditions

						}//End of foreach
	$response="update-investigation";
	header("Location:Diagnostic-Refer?d=".md5($_POST['patient_id'])."&e=".md5($_POST['episode_id'])."&response=".$response);
}
//SAVE PATIENT
	if(isset($_POST['save_patient']) || isset($_POST['update_patient']))
	{
		$txtName 	= addslashes($_POST['se_pat_name']);
		$txtMail 	= addslashes($_POST['se_email']);
		$txtAge 	= $_POST['se_pat_age'];
		$txtGen 	= $_POST['se_gender'];

		$height 	= $_POST['height'];
		$weight 	= $_POST['weight'];

		$txtContact = $_POST['se_con_per'];
		$txtMob 	= $_POST['se_phone_no'];
		$txtCountry = $_POST['se_country'];
		$txtState 	= $_POST['se_state'];
		$txtLoc 	= $_POST['se_city'];
		$txtAddress = addslashes($_POST['se_address']);

		$hyperCond 	= $_POST['se_hyper'];
		$diabetesCond = $_POST['se_diabets'];
		
		$patImage = addslashes($_FILES['txtPhoto']['name']);
		
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
			$arrValues[] = $weight;

			$arrFields[] = 'height';
			$arrValues[] = $height;

			$arrFields[] = 'hyper_cond';
			$arrValues[] = $hyperCond;

			$arrFields[] = 'diabetes_cond';
			$arrValues[] = $diabetesCond;

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
			$arrFields[] = 'TImestamp';
			$arrValues[] = $Cur_Date;
			$arrFields[] = 'system_date';
			$arrValues[] = $cur_Date;
			
			if(!empty($_FILES['txtPhoto']['name'])){
			$arrFields[]="patient_image";
			$arrValues[]=$patImage;
			}
			
		if(isset($_POST['save_patient']))
		{
			$insert_patient=mysqlInsert('doc_my_patient',$arrFields,$arrValues);
			$patientid = $insert_patient;
		}
		else if(isset($_POST['update_patient']))
		{
			$userupdate=mysqlUpdate('doc_my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
			$patientid = $_POST['patient_id'];
		}
		//UPLOAD COMPRESSED IMAGE
			if ($_FILES["txtPhoto"]["error"] > 0) 
			{
        			$error = $_FILES["txtPhoto"]["error"];
    		} 
    		else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
			($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
			($_FILES["txtPhoto"]["type"] == "image/png") || 
			($_FILES["txtPhoto"]["type"] == "image/pjpeg")) 
			{
				
				
				$folder_name	=	"patientImage";
				$sub_folder		=	$patientId;
				$filename		=	$_FILES['txtPhoto']['name'][$key];
				$file_url		=	$_FILES['txtPhoto']['tmp_name'][$key];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			
			/* $uploaddirectory = realpath("../patientImage");
			 $uploaddir = $uploaddirectory . "/" .$patientid;
			 
			 /*Checking whether folder with category id already exist or not. */
				/*if (file_exists($uploaddir)) 
				{
					//echo "The file $uploaddir exists";
				} 
				else 
				{
					$newdir = mkdir($uploaddirectory . "/" . $patientid, 0777);
				}
			 
			 
        			$url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];

        			$filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 60);
        			$buffer = file_get_contents($url);*/

    		}
			else 
			{
        			$error = "Uploaded image should be jpg or gif or png";
    		}
			
		$response="updated";
		header("Location:My-Patient-Details?p=".md5($patientid));
	}

	//UPDATE PATIENT

	if(isset($_POST['updatePatient'])){

		$se_hyper = $_POST['se_hyper'];
		$se_diabets = $_POST['se_diabets'];
		$se_smoking = $_POST['se_smoking'];
		$se_alcoholic = $_POST['se_alcoholic'];
		$drug_abuse = $_POST['drug_abuse'];
		$other_details = $_POST['other_details'];

		$family_history = $_POST['family_history'];
		$prev_inter = $_POST['prev_inter'];
		$neuro_issue = $_POST['neuro_issue'];
		$kidney_issue = $_POST['kidney_issue'];

		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'hyper_cond';
		$arrValues[] = $se_hyper;

		$arrFields[] = 'smoking';
		$arrValues[] = $se_smoking;

		$arrFields[] = 'alcoholic';
		$arrValues[] = $se_alcoholic;

		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $se_diabets;

		//$arrFields[] = 'drug_abuse';
		//$arrValues[] = $drug_abuse;

		$arrFields[] = 'other_details';
		$arrValues[] = $other_details;

		//$arrFields[] = 'family_history';
		//$arrValues[] = $family_history;

		$arrFields[] = 'prev_inter';
		$arrValues[] = $prev_inter;

		$arrFields[] = 'neuro_issue';
		$arrValues[] = $neuro_issue;

		$arrFields[] = 'kidney_issue';
		$arrValues[] = $kidney_issue;


		$userupdate=mysqlUpdate('doc_my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
		//$patientid = $patient_id;

		//Update Drug Abuse -' doc_patient_drug_active' table

				$arrFieldsDrugAbuse[] = 'status';
				$arrValuesDrugAbuse[] = "0";
				$update_icd=mysqlUpdate('doc_patient_drug_active',$arrFieldsDrugAbuse,$arrValuesDrugAbuse,"patient_id = '".$_POST['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Family History -' doc_patient_drug_active' table

				$arrFieldsFamilyHistory[] = 'status';
				$arrValuesFamilyHistory[] = "0";
				$update_icd=mysqlUpdate('doc_patient_family_history_active',$arrFieldsFamilyHistory,$arrValuesFamilyHistory,"patient_id = '".$_POST['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Drug Allergy -' doc_patient_drug_allergy_active' table

				$arrFieldsAllergy[] = 'status';
				$arrValuesAllergy[] = "0";
				$update_icd=mysqlUpdate('doc_patient_drug_allergy_active',$arrFieldsAllergy,$arrValuesAllergy,"patient_id = '".$_POST['patient_id']."' and status='1'");

		$response="medical-history-updated";
		header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}

//Edit Spectacle Prescriptions
if(isset($_GET['updateSpectacle']) && !empty($_GET['updateSpectacle'])){
	
	$Episode_Id = $_SESSION['episode_id'];		
	//echo $Episode_Id;			
	if(isset($_GET['slctDistVisionRE']))
	{
				$arrFileds_spect[]='distacnce_vision_right';
				$arrValues_spect[]=$_GET['slctDistVisionRE'];
	}

	if(isset($_GET['slctDistVisionLE']))
	{
				$arrFileds_spect[]='distance_vision_left';
				$arrValues_spect[]=$_GET['slctDistVisionLE'];
	}
	if(isset($_GET['slctDistVisionLEUnaided']))
	{
			$slctDistVisionLEUnaided = stripslashes($_GET['slctDistVisionLEUnaided']);
				$arrFileds_spect[]='distance_vision_left_unaided';
				$arrValues_spect[]=$slctDistVisionLEUnaided;
	}
	if(isset($_GET['slctDistVisionREUnaided']))
	{
				$slctDistVisionREUnaided = stripslashes($_GET['slctDistVisionREUnaided']);
				$arrFileds_spect[]='distacnce_vision_right_unaided';
				$arrValues_spect[]=$slctDistVisionREUnaided;
	}
	if(isset($_GET['slctNearVisionRE']))
	{
				$arrFileds_spect[]='near_vision_right';
				$arrValues_spect[]=$_GET['slctNearVisionRE'];
	}
	if(isset($_GET['slctNearVisionREUnaided']))
	{
				$arrFileds_spect[]='near_vision_right_unaided';
				$arrValues_spect[]=$_GET['slctNearVisionREUnaided'];
	}
	if(isset($_GET['slctNearVisionLE']))
	{
				$arrFileds_spect[]='near_vision_left';
				$arrValues_spect[]=$_GET['slctNearVisionLE'];
	}
	if(isset($_GET['slctNearVisionLEUnaided']))
	{
				$arrFileds_spect[]='near_vision_left_unaided';
				$arrValues_spect[]=$_GET['slctNearVisionLEUnaided'];
	}
	if(isset($_GET['se_refractionRE_value1']))
	{
				$arrFileds_spect[]='refraction_right_value1';
				$arrValues_spect[]=$_GET['se_refractionRE_value1'];
	}
	if(isset($_GET['se_refractionRE_value2']))
	{
				$arrFileds_spect[]='refraction_right_value2';
				$arrValues_spect[]=$_GET['se_refractionRE_value2'];
	}
	if(isset($_GET['se_refractionLE_value1']))
	{
				$arrFileds_spect[]='refraction_left_value1';
				$arrValues_spect[]=$_GET['se_refractionLE_value1'];
	}
	if(isset($_GET['se_refractionLE_value2']))
	{
				$arrFileds_spect[]='refraction_left_value2';
				$arrValues_spect[]=$_GET['se_refractionLE_value2'];
	}
	if(isset($_GET['DvSpeherRE']))
	{
				$arrFileds_spect[]='dvSphereRE';
				$arrValues_spect[]=$_GET['DvSpeherRE'];
	}
	if(isset($_GET['DvCylRE']))
	{
				$arrFileds_spect[]='DvCylRE';
				$arrValues_spect[]=$_GET['DvCylRE'];
	}
	if(isset($_GET['DvAxisRE']))
	{
				$arrFileds_spect[]='DvAxisRE';
				$arrValues_spect[]=$_GET['DvAxisRE'];
	}
	if(isset($_GET['DvVisionRE']))
	{
				$arrFileds_spect[]='DvVisionRE';
				$arrValues_spect[]=$_GET['DvVisionRE'];
	}
	if(isset($_GET['DvSpeherLE']))
	{
				$arrFileds_spect[]='DvSpeherLE';
				$arrValues_spect[]=$_GET['DvSpeherLE'];
	}
	if(isset($_GET['DvCylLE']))
	{
				$arrFileds_spect[]='DvCylLE';
				$arrValues_spect[]=$_GET['DvCylLE'];
	}
	if(isset($_GET['DvAxisLE']))
	{
				$arrFileds_spect[]='DvAxisLE';
				$arrValues_spect[]=$_GET['DvAxisLE'];
	}
	if(isset($_GET['DvVisionLE']))
	{
				$arrFileds_spect[]='DvVisionLE';
				$arrValues_spect[]=$_GET['DvVisionLE'];
	}
	if(isset($_GET['NvSpeherRE']))
	{
				$arrFileds_spect[]='NvSpeherRE';
				$arrValues_spect[]=$_GET['NvSpeherRE'];
	}
	if(isset($_GET['NvCylRE']))
	{
				$arrFileds_spect[]='NvCylRE';
				$arrValues_spect[]=$_GET['NvCylRE'];
	}
	if(isset($_GET['NvAxisRE']))
	{
				$arrFileds_spect[]='NvAxisRE';
				$arrValues_spect[]=$_GET['NvAxisRE'];
	}
	if(isset($_GET['NvVisionRE']))
	{
				$arrFileds_spect[]='NvVisionRE';
				$arrValues_spect[]=$_GET['NvVisionRE'];
	}
	if(isset($_GET['NvSpeherLE']))
	{
				$arrFileds_spect[]='NvSpeherLE';
				$arrValues_spect[]=$_GET['NvSpeherLE'];
	}
	if(isset($_GET['NvCylLE']))
	{
				$arrFileds_spect[]='NvCylLE';
				$arrValues_spect[]=$_GET['NvCylLE'];
	}
	if(isset($_GET['NvAxisLE']))
	{
				$arrFileds_spect[]='NvAxisLE';
				$arrValues_spect[]=$_GET['NvAxisLE'];
	}
	if(isset($_GET['NvVisionLE']))
	{
				$arrFileds_spect[]='NvVisionLE';
				$arrValues_spect[]=$_GET['NvVisionLE'];
	}
	if(isset($_GET['IpdRE']))
	{
				$arrFileds_spect[]='IpdRE';
				$arrValues_spect[]=$_GET['IpdRE'];
	}
	if(isset($_GET['IpdLE']))
	{
				$arrFileds_spect[]='IpdLE';
				$arrValues_spect[]=$_GET['IpdLE'];
	}
	if(isset($_GET['IopRE']))
	{
				$arrFileds_spect[]='IopRE';
				$arrValues_spect[]=$_GET['IopRE'];
	}
	if(isset($_GET['IopLE']))
	{
				$arrFileds_spect[]='IopLE';
				$arrValues_spect[]=$_GET['IopLE'];
	}
	if(isset($_GET['DvSpeherRE_Present']))
	{
				$arrFileds_spect_Present[]='dvSphereRE';
				$arrValues_spect_Present[]=$_GET['DvSpeherRE_Present'];
	}
	if(isset($_GET['DvCylRE_Present']))
	{
				$arrFileds_spect_Present[]='DvCylRE';
				$arrValues_spect_Present[]=$_GET['DvCylRE_Present'];
	}
	if(isset($_GET['DvAxisRE_Present']))
	{
				$arrFileds_spect_Present[]='DvAxisRE';
				$arrValues_spect_Present[]=$_GET['DvAxisRE_Present'];
	}
	if(isset($_GET['DvVisionRE_Present']))
	{
				$arrFileds_spect_Present[]='DvVisionRE';
				$arrValues_spect_Present[]=$_GET['DvVisionRE_Present'];
	}
	if(isset($_GET['DvSpeherLE_Present']))
	{
				$arrFileds_spect_Present[]='DvSpeherLE';
				$arrValues_spect_Present[]=$_GET['DvSpeherLE_Present'];
	}
	if(isset($_GET['DvCylLE_Present']))
	{
				$arrFileds_spect_Present[]='DvCylLE';
				$arrValues_spect_Present[]=$_GET['DvCylLE_Present'];
	}
	if(isset($_GET['DvAxisLE_Present']))
	{
				$arrFileds_spect_Present[]='DvAxisLE';
				$arrValues_spect_Present[]=$_GET['DvAxisLE_Present'];
	}
	if(isset($_GET['DvVisionLE_Present']))
	{
				$arrFileds_spect_Present[]='DvVisionLE';
				$arrValues_spect_Present[]=$_GET['DvVisionLE_Present'];
	}
	if(isset($_GET['NvSpeherRE_Present']))
	{
				$arrFileds_spect_Present[]='NvSpeherRE';
				$arrValues_spect_Present[]=$_GET['NvSpeherRE_Present'];
	}
	if(isset($_GET['NvCylRE_Present']))
	{
				$arrFileds_spect_Present[]='NvCylRE';
				$arrValues_spect_Present[]=$_GET['NvCylRE_Present'];
	}
	if(isset($_GET['NvAxisRE_Present']))
	{
				$arrFileds_spect_Present[]='NvAxisRE';
				$arrValues_spect_Present[]=$_GET['NvAxisRE_Present'];
	}
	if(isset($_GET['NvVisionRE_Present']))
	{
				$arrFileds_spect_Present[]='NvVisionRE';
				$arrValues_spect_Present[]=$_GET['NvVisionRE_Present'];
	}
	if(isset($_GET['NvSpeherLE_Present']))
	{
				$arrFileds_spect_Present[]='NvSpeherLE';
				$arrValues_spect_Present[]=$_GET['NvSpeherLE_Present'];
	}
	if(isset($_GET['NvCylLE_Present']))
	{
				$arrFileds_spect_Present[]='NvCylLE';
				$arrValues_spect_Present[]=$_GET['NvCylLE_Present'];
	}
	if(isset($_GET['NvAxisLE_Present']))
	{
				$arrFileds_spect_Present[]='NvAxisLE';
				$arrValues_spect_Present[]=$_GET['NvAxisLE_Present'];
	}
	if(isset($_GET['NvVisionLE_Present']))
	{
				$arrFileds_spect_Present[]='NvVisionLE';
				$arrValues_spect_Present[]=$_GET['NvVisionLE_Present'];
	}
	if(isset($_GET['IpdRE_Present']))
	{
				$arrFileds_spect_Present[]='IpdRE';
				$arrValues_spect_Present[]=$_GET['IpdRE_Present'];
	}
	if(isset($_GET['IpdLE_Present']))
	{
				$arrFileds_spect_Present[]='IpdLE';
				$arrValues_spect_Present[]=$_GET['IpdLE_Present'];
	}
	
	$check_Spectal= mysqlSelect("*","examination_opthal_spectacle_prescription","spectacle_id='".$_GET['spectacle_id']."'","","","","");
	if(count($check_Spectal)>0)
	{
	$update_spectacle_prescription=mysqlUpdate('examination_opthal_spectacle_prescription',$arrFileds_spect,$arrValues_spect,"spectacle_id = '".$_GET['spectacle_id']."'");
	} else
	{
	$arrFileds_spect[]='episode_id';
	$arrValues_spect[]=$Episode_Id;
	$arrFileds_spect[]='doc_id';
	$arrValues_spect[]=$admin_id;
	$arrFileds_spect[]='doc_type';
	$arrValues_spect[]="1";
	$insert_patient_episodes=mysqlInsert('examination_opthal_spectacle_prescription',$arrFileds_spect,$arrValues_spect);
	}
}
	
	//CREATE EPISODE
	if(isset($_POST['save_patient_edit']) || isset($_POST['save_patient_print'])){ //TO CHECK AUTHENTICATION OF POST VALUES
		//echo "<pre>"; print_r($_POST); exit;


			$patient_id = (int)$_POST['patient_id'];
			$episode_desc = $_POST['episode_desc'];
			$medical_complaint =  $_POST['medical_complaint'];
			$medical_examination =  $_POST['medical_examination'];
			$txt_treatment =  $_POST['txt_treatment'];
			
			$diagnosis_details =  $_POST['diagnosis_details'];
			$treatment_details =  $_POST['treatment_details'];			
			$presc_note =  $_POST['presc_note'];

			// Ophthal Examination DistanceVision
			$txt_distancevision_num_right =  $_POST['slctDistVisionRE'];
			$txt_distancevision_info_right =  $_POST['distVisionInfoRE'];
			$txt_distancevision_num_left =  $_POST['slctDistVisionLE'];
			$txt_distancevision_info_left =  $_POST['distVisionInfoLE'];

			$txt_nearvision_num_right =  $_POST['slctNearVisionRE'];
			$txt_nearvision_info_right =  $_POST['nearVisionInfoRE'];
			$txt_nearvision_num_left =  $_POST['slctNearVisionLE'];
			$txt_nearvision_info_left =  $_POST['nearVisionInfoLE'];

			$txt_refractionRE_value1 =  $_POST['se_refractionRE_value1'];
			$txt_refractionRE_value2 =  $_POST['se_refractionRE_value2'];
			$txt_refractionLE_value1 =  $_POST['se_refractionLE_value1'];
			$txt_refractionLE_value2 =  $_POST['se_refractionLE_value2'];
			
			
			// Ophthal Examination DistanceVision
			$slctDistVisionRE_Aided =  $_POST['slctDistVisionRE_Aided'];
			$slctDistVisionRE_Unaided =  $_POST['slctDistVisionRE_Unaided'];
			$txt_distancevision_info_right =  $_POST['distVisionInfoRE'];
			$slctDistVisionLE_Aided =  $_POST['slctDistVisionLE_Aided'];
			$slctDistVisionLE_Unaided =  $_POST['slctDistVisionLE_Unaided'];
			$txt_distancevision_info_left =  $_POST['distVisionInfoLE'];

			$slctNearVisionRE_Aided =  $_POST['slctNearVisionRE_Aided'];
			$slctNearVisionRE_Unaided =  $_POST['slctNearVisionRE_Unaided'];
			$txt_nearvision_info_right =  $_POST['nearVisionInfoRE'];
			$slctNearVisionLE_Aided =  $_POST['slctNearVisionLE_Aided'];
			$slctNearVisionLE_Unaided =  $_POST['slctNearVisionLE_Unaided'];
			$txt_nearvision_info_left =  $_POST['nearVisionInfoLE'];
			
			$slctIOP_RE =  $_POST['slctIOP_RE'];
			$slctIOP_LE =  $_POST['slctIOP_LE'];

			$txt_DvSpeherRE =  $_POST['DvSpeherRE'];
			$txt_DvCylRE =  $_POST['DvCylRE'];
			$txt_DvAxisRE =  $_POST['DvAxisRE'];
			$txt_DvSpeherLE =  $_POST['DvSpeherLE'];
			$txt_DvCylLE =  $_POST['DvCylLE'];
			$txt_DvAxisLE =  $_POST['DvAxisLE'];
			$txt_NvSpeherRE =  $_POST['NvSpeherRE'];
			$txt_NvCylRE =  $_POST['NvCylRE'];
			$txt_NvAxisRE =  $_POST['NvAxisRE'];
			$txt_NvSpeherLE =  $_POST['NvSpeherLE'];
			$txt_NvCylLE =  $_POST['NvCylLE'];
			$txt_NvAxisLE =  $_POST['NvAxisLE'];
			$txt_IpdRE =  $_POST['IpdRE'];
			$txt_IpdLE =  $_POST['IpdLE'];

			$DvVisionLE = $_POST['DvVisionLE'];
			$NvVisionLE = $_POST['NvVisionLE'];
			
			$DvVisionRE = $_POST['DvVisionRE'];
			$NvVisionRE = $_POST['NvVisionRE'];

				$arrFieldsPE = array();
				$arrValuesPE = array();
				$arrFieldsPE[] = 'patient_id';
				$arrValuesPE[] = $patient_id;
				$arrFieldsPE[] = 'admin_id';
				$arrValuesPE[] = $admin_id;
				$arrFieldsPE[] = 'treatment';
				$arrValuesPE[] = $txt_treatment;
				/*$arrFieldsPE[] = 'episode_medical_complaint';
				$arrValuesPE[] = $medical_complaint;  // Value 1 for "New"
				$arrFieldsPE[] = 'examination';
				$arrValuesPE[] = $medical_examination;*/
				
				$arrFieldsPE[] = 'prescription_note';
				$arrValuesPE[] = $presc_note;				
				$arrFieldsPE[] = 'diagnosis_details';
				$arrValuesPE[] = $diagnosis_details;
				$arrFieldsPE[] = 'treatment_details';
				$arrValuesPE[] = $treatment_details;
				
				$arrFieldsPE[] = 'emr_type';
				$arrValuesPE[] = "2"; //1 for cardiodiabetic
				
				if(!empty($_POST['dateadded'])){
				$arrFieldsPE[] = 'next_followup_date';
				$arrValuesPE[] = date('Y-m-d',strtotime($_POST['dateadded']));
				}
				
				if(!empty($_POST['dateadded2'])){
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $_POST['dateadded2'];
				}
				else
				{
				$arrFieldsPE[] = 'date_time';
				$arrValuesPE[] = $Cur_Date;
				}

				$insert_patient_episodes=mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
				$episode_id = $insert_patient_episodes;
				
					//Start of - Present Spectacle Prescriptions
				$arrFieldsPreSpect = array();
				$arrValuesPreSpect = array();
				
				$arrFieldsPreSpect[] = 'episode_id';
				$arrValuesPreSpect[] = $episode_id;
				$arrFieldsPreSpect[] = 'doc_id';
				$arrValuesPreSpect[] = $admin_id;
				$arrFieldsPreSpect[] = 'doc_type';
				$arrValuesPreSpect[] = '1';
				$arrFieldsPreSpect[] = 'dvSphereRE';
				$arrValuesPreSpect[] = $_POST['DvSpeherRE_Present'];
				$arrFieldsPreSpect[] = 'DvCylRE';
				$arrValuesPreSpect[] = $_POST['DvCylRE_Present'];
				$arrFieldsPreSpect[] = 'DvAxisRE';
				$arrValuesPreSpect[] = $_POST['DvAxisRE_Present'];
				$arrFieldsPreSpect[] = 'DvSpeherLE';
				$arrValuesPreSpect[] = $_POST['DvSpeherLE_Present'];
				$arrFieldsPreSpect[] = 'DvCylLE';
				$arrValuesPreSpect[] = $_POST['DvCylLE_Present'];
				$arrFieldsPreSpect[] = 'DvAxisLE';
				$arrValuesPreSpect[] = $_POST['DvAxisLE_Present'];
				$arrFieldsPreSpect[] = 'NvSpeherRE';
				$arrValuesPreSpect[] = $_POST['NvSpeherRE_Present'];
				$arrFieldsPreSpect[] = 'NvCylRE';
				$arrValuesPreSpect[] = $_POST['NvCylRE_Present'];
				$arrFieldsPreSpect[] = 'NvAxisRE';
				$arrValuesPreSpect[] = $_POST['NvAxisRE_Present'];
				$arrFieldsPreSpect[] = 'NvSpeherLE';
				$arrValuesPreSpect[] = $_POST['NvSpeherLE_Present'];
				$arrFieldsPreSpect[] = 'NvCylLE';
				$arrValuesPreSpect[] = $_POST['NvCylLE_Present'];
				$arrFieldsPreSpect[] = 'NvAxisLE';
				$arrValuesPreSpect[] = $_POST['NvAxisLE_Present'];
				$arrFieldsPreSpect[] = 'IpdRE';
				$arrValuesPreSpect[] = $_POST['IpdRE_Present'];
				$arrFieldsPreSpect[] = 'IpdLE';
				$arrValuesPreSpect[] = $_POST['IpdLE_Present'];
				
								
				$arrFieldsPreSpect[] = 'DvVisionRE';
				$arrValuesPreSpect[] = $_POST['DvVisionRE_Present'];
				$arrFieldsPreSpect[] = 'DvVisionLE';
				$arrValuesPreSpect[] = $_POST['DvVisionLE_Present'];
				$arrFieldsPreSpect[] = 'NvVisionRE';
				$arrValuesPreSpect[] = $_POST['NvVisionRE_Present'];
				$arrFieldsPreSpect[] = 'NvVisionLE';
				$arrValuesPreSpect[] = $_POST['NvVisionLE_Present'];

				$insert_patient_present_spectacle_presc=mysqlInsert('present_examination_opthal_spectacle_prescription',$arrFieldsPreSpect,$arrValuesPreSpect);
				//End of - Present Spectacle Prescriptions
				
				

				// Add Examination Ophthal Other Details - Spectacle Prescriptions
				$arrFieldsDV = array();
				$arrValuesDV = array();
				$arrFieldsDV[] = 'distacnce_vision_right';
				$arrValuesDV[] = $slctDistVisionRE_Aided;
				$arrFieldsDV[] = 'distacnce_vision_right_unaided';
				$arrValuesDV[] = $slctDistVisionRE_Unaided;
				$arrFieldsDV[] = 'distance_vision_left';
				$arrValuesDV[] = $slctDistVisionLE_Aided;
				$arrFieldsDV[] = 'distance_vision_left_unaided';
				$arrValuesDV[] = $slctDistVisionLE_Unaided;
				$arrFieldsDV[] = 'near_vision_right';
				$arrValuesDV[] = $slctNearVisionRE_Aided;
				$arrFieldsDV[] = 'near_vision_right_unaided';
				$arrValuesDV[] = $slctNearVisionRE_Unaided;
				$arrFieldsDV[] = 'near_vision_left';
				$arrValuesDV[] = $slctNearVisionLE_Aided;
				$arrFieldsDV[] = 'near_vision_left_unaided';
				$arrValuesDV[] = $slctNearVisionLE_Unaided;
				//$arrFieldsDV[] = 'distacnce_vision_right';
				//$arrValuesDV[] = $txt_distancevision_num_right;
			//	$arrFieldsDV[] = 'distance_vision_left';
			//	$arrValuesDV[] = $txt_distancevision_num_left;
			//	$arrFieldsDV[] = 'near_vision_right';
			//	$arrValuesDV[] = $txt_nearvision_num_right;
			//	$arrFieldsDV[] = 'near_vision_left';
			//	$arrValuesDV[] = $txt_nearvision_num_left;
				$arrFieldsDV[] = 'refraction_right_value1';
				$arrValuesDV[] = $txt_refractionRE_value1;
				$arrFieldsDV[] = 'refraction_right_value2';
				$arrValuesDV[] = $txt_refractionRE_value2;
				$arrFieldsDV[] = 'refraction_left_value1';
				$arrValuesDV[] = $txt_refractionLE_value1;
				$arrFieldsDV[] = 'refraction_left_value2';
				$arrValuesDV[] = $txt_refractionLE_value2;
				$arrFieldsDV[] = 'episode_id';
				$arrValuesDV[] = $episode_id;
				$arrFieldsDV[] = 'doc_id';
				$arrValuesDV[] = $admin_id;
				$arrFieldsDV[] = 'doc_type';
				$arrValuesDV[] = '1';
				$arrFieldsDV[] = 'dvSphereRE';
				$arrValuesDV[] = $txt_DvSpeherRE;
				$arrFieldsDV[] = 'DvCylRE';
				$arrValuesDV[] = $txt_DvCylRE;
				$arrFieldsDV[] = 'DvAxisRE';
				$arrValuesDV[] = $txt_DvAxisRE;
				$arrFieldsDV[] = 'DvSpeherLE';
				$arrValuesDV[] = $txt_DvSpeherLE;
				$arrFieldsDV[] = 'DvCylLE';
				$arrValuesDV[] = $txt_DvCylLE;
				$arrFieldsDV[] = 'DvAxisLE';
				$arrValuesDV[] = $txt_DvAxisLE;
				$arrFieldsDV[] = 'NvSpeherRE';
				$arrValuesDV[] = $txt_NvSpeherRE;
				$arrFieldsDV[] = 'NvCylRE';
				$arrValuesDV[] = $txt_NvCylRE;
				$arrFieldsDV[] = 'NvAxisRE';
				$arrValuesDV[] = $txt_NvAxisRE;
				$arrFieldsDV[] = 'NvSpeherLE';
				$arrValuesDV[] = $txt_NvSpeherLE;
				$arrFieldsDV[] = 'NvCylLE';
				$arrValuesDV[] = $txt_NvCylLE;
				$arrFieldsDV[] = 'NvAxisLE';
				$arrValuesDV[] = $txt_NvAxisLE;
				$arrFieldsDV[] = 'DvVisionRE';
				$arrValuesDV[] = $DvVisionRE;
				$arrFieldsDV[] = 'DvVisionLE';
				$arrValuesDV[] = $DvVisionLE;
				$arrFieldsDV[] = 'NvVisionRE';
				$arrValuesDV[] = $NvVisionRE;
				$arrFieldsDV[] = 'NvVisionLE';
				$arrValuesDV[] = $NvVisionLE;
				$arrFieldsDV[] = 'IpdRE';
				$arrValuesDV[] = $txt_IpdRE;
				$arrFieldsDV[] = 'IpdLE';
				$arrValuesDV[] = $txt_IpdLE;
				$arrFieldsDV[] = 'IopRE';
				$arrValuesDV[] = $slctIOP_RE;
				$arrFieldsDV[] = 'IopLE';
				$arrValuesDV[] = $slctIOP_LE;

				$insert_patient_episodes=mysqlInsert('examination_opthal_spectacle_prescription',$arrFieldsDV,$arrValuesDV);
				$otherinfo_id = $insert_patient_episodes;
				
				
				$arrFields = array();
				$arrValues = array();
				
				$arrFields[]='patient_id';
				$arrValues[]=$patient_id;
				
				$arrFields[]='date_added';
				$arrValues[]=date('Y-m-d',strtotime($_POST['dateadded2']));
				
				if(!empty($txt_DvSpeherRE)){
				$arrFields[]='DvSphereRE';
				$arrValues[]=$txt_DvSpeherRE;
				}
				
				if(!empty($txt_DvCylRE)){
				$arrFields[]='DvCylRE';
				$arrValues[]=$txt_DvCylRE;
				}
				
				if(!empty($txt_DvAxisRE)){
				$arrFields[]='DvAxisRE';
				$arrValues[]=$txt_DvAxisRE;
				}
				
				if(!empty($txt_DvSpeherLE)){
				$arrFields[]='DvSpeherLE';
				$arrValues[]=$txt_DvSpeherLE;
				}
				
				if(!empty($txt_DvCylLE)){
				$arrFields[]='DvCylLE';
				$arrValues[]=$txt_DvCylLE;
				}
				
				if(!empty($txt_DvAxisLE)){
				$arrFields[]='DvAxisLE';
				$arrValues[]=$txt_DvAxisLE;
				}
				
				if(!empty($txt_NvSpeherRE)){
				$arrFields[]='NvSpeherRE';
				$arrValues[]=$txt_NvSpeherRE;
				}
				
				if(!empty($txt_NvCylRE)){
				$arrFields[]='NvCylRE';
				$arrValues[]=$txt_NvCylRE;
				}
				
				if(!empty($txt_NvAxisRE)){
				$arrFields[]='NvAxisRE';
				$arrValues[]=$txt_NvAxisRE;
				}
				
				if(!empty($txt_NvSpeherLE)){
				$arrFields[]='NvSpeherLE';
				$arrValues[]=$txt_NvSpeherLE;
				}
				
				if(!empty($txt_NvCylLE)){
				$arrFields[]='NvCylLE';
				$arrValues[]=$txt_NvCylLE;
				}
				
				if(!empty($txt_NvAxisLE)){
				$arrFields[]='NvAxisLE';
				$arrValues[]=$txt_NvAxisLE;
				}
				
				if(!empty($txt_IpdRE)){
				$arrFields[]='IpdRE';
				$arrValues[]=$txt_IpdRE;
				}
				
				if(!empty($txt_IpdLE)){
				$arrFields[]='IpdLE';
				$arrValues[]=$txt_IpdLE;
				}
				
				$arrFields[]='patient_type';
				$arrValues[]="1";
				
				$checkTrend= mysqlSelect("*","trend_analysis_ophthal","date_added='".date('Y-m-d',strtotime($_POST['dateadded2']))."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
				if(count($checkTrend)==0)
				{
					$insert_patient=mysqlInsert('trend_analysis_ophthal',$arrFields,$arrValues);
				}


				$arrFieldsSYMPTOMS[] = 'episode_id';
				$arrValuesSYMPTOMS[] = $episode_id;
				$arrFieldsSYMPTOMS[] = 'status';
				$arrValuesSYMPTOMS[] = "0";
				$update_icd=mysqlUpdate('doc_patient_symptoms_active',$arrFieldsSYMPTOMS,$arrValuesSYMPTOMS,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");



				//Update Investigation -' patient_temp_investigation' table
				$arrFieldsINVEST=array();
				$arrValuesINVEST=array();
				$arrFieldsINVEST[] = 'episode_id';
				$arrValuesINVEST[] = $episode_id;
				$arrFieldsINVEST[] = 'status';
				$arrValuesINVEST[] = "0";
				$update_icd=mysqlUpdate('patient_temp_investigation',$arrFieldsINVEST,$arrValuesINVEST,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");


				//Update Examination -'doc_patient_examination_active' table
				$arrFieldsExam[] = 'episode_id';
				$arrValuesExam[] = $episode_id;
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=mysqlUpdate('doc_patient_examination_active',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Diagnosis -' patient_diagnosis' table
				$arrFieldsExam[] = 'episode_id';
				$arrValuesExam[] = $episode_id;
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=mysqlUpdate('patient_diagnosis',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Treatment -'doc_patient_treatment_active' table
				$arrFieldsTreat[] = 'episode_id';
				$arrValuesTreat[] = $episode_id;
				$arrFieldsTreat[] = 'status';
				$arrValuesTreat[] = "0";
				$update_icd=mysqlUpdate('doc_patient_treatment_active',$arrFieldsTreat,$arrValuesTreat,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Lids -'doc_patient_lids_active' table
				$arrFieldsLids[] = 'episode_id';
				$arrValuesLids[] = $episode_id;
				$arrFieldsLids[] = 'status';
				$arrValuesLids[] = "0";
				$update_lids=mysqlUpdate('doc_patient_lids_active',$arrFieldsLids,$arrValuesLids,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Conjuctiva -'doc_patient_conjuctiva_active' table
				$arrFieldsConjuctiva[] = 'episode_id';
				$arrValuesConjuctiva[] = $episode_id;
				$arrFieldsConjuctiva[] = 'status';
				$arrValuesConjuctiva[] = "0";
				$update_lids=mysqlUpdate('doc_patient_conjuctiva_active',$arrFieldsConjuctiva,$arrValuesConjuctiva,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Sclera -'doc_patient_sclera_active' table
				$arrFieldsSclera[] = 'episode_id';
				$arrValuesSclera[] = $episode_id;
				$arrFieldsSclera[] = 'status';
				$arrValuesSclera[] = "0";
				$update_lids=mysqlUpdate('doc_patient_sclera_active',$arrFieldsSclera,$arrValuesSclera,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Cornea Anterior -'doc_patient_cornea_ant_active' table
				$arrFieldsCorneaAnt[] = 'episode_id';
				$arrValuesCorneaAnt[] = $episode_id;
				$arrFieldsCorneaAnt[] = 'status';
				$arrValuesCorneaAnt[] = "0";
				$update_lids=mysqlUpdate('doc_patient_cornea_ant_active',$arrFieldsCorneaAnt,$arrValuesCorneaAnt,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Cornea Posterior -'doc_patient_cornea_post_active' table
				$arrFieldsCorneaPost[] = 'episode_id';
				$arrValuesCorneaPost[] = $episode_id;
				$arrFieldsCorneaPost[] = 'status';
				$arrValuesCorneaPost[] = "0";
				$update_lids=mysqlUpdate('doc_patient_cornea_post_active',$arrFieldsCorneaPost,$arrValuesCorneaPost,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Anterior Chamber -'doc_patient_anterior_chamber_active' table
				$arrFieldsAnteriorChamber[] = 'episode_id';
				$arrValuesAnteriorChamber[] = $episode_id;
				$arrFieldsAnteriorChamber[] = 'status';
				$arrValuesAnteriorChamber[] = "0";
				$update_lids=mysqlUpdate('doc_patient_anterior_chamber_active',$arrFieldsAnteriorChamber,$arrValuesAnteriorChamber,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Iris -'doc_patient_iris_active' table
				$arrFieldsIris[] = 'episode_id';
				$arrValuesIris[] = $episode_id;
				$arrFieldsIris[] = 'status';
				$arrValuesIris[] = "0";
				$update_lids=mysqlUpdate('doc_patient_iris_active',$arrFieldsIris,$arrValuesIris,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Pupils -'doc_patient_pupil_active' table
				$arrFieldsPupil[] = 'episode_id';
				$arrValuesPupil[] = $episode_id;
				$arrFieldsPupil[] = 'status';
				$arrValuesPupil[] = "0";
				$update_lids=mysqlUpdate('doc_patient_pupil_active',$arrFieldsPupil,$arrValuesPupil,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Angle -'doc_patient_angle_active' table
				$arrFieldsAngle[] = 'episode_id';
				$arrValuesAngle[] = $episode_id;
				$arrFieldsAngle[] = 'status';
				$arrValuesAngle[] = "0";
				$update_lids=mysqlUpdate('doc_patient_angle_active',$arrFieldsAngle,$arrValuesAngle,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Lens -'doc_patient_lens_active' table
				$arrFieldsLens[] = 'episode_id';
				$arrValuesLens[] = $episode_id;
				$arrFieldsLens[] = 'status';
				$arrValuesLens[] = "0";
				$update_lids=mysqlUpdate('doc_patient_lens_active',$arrFieldsLens,$arrValuesLens,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Viterous -'doc_patient_viterous_active' table
				$arrFieldsViterous[] = 'episode_id';
				$arrValuesViterous[] = $episode_id;
				$arrFieldsViterous[] = 'status';
				$arrValuesViterous[] = "0";
				$update_lids=mysqlUpdate('doc_patient_viterous_active',$arrFieldsViterous,$arrValuesViterous,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");

				//Update Examination Ophthal Fundus -'doc_patient_fundus_active' table
				$arrFieldsFundus[] = 'episode_id';
				$arrValuesFundus[] = $episode_id;
				$arrFieldsFundus[] = 'status';
				$arrValuesFundus[] = "0";
				$update_lids=mysqlUpdate('doc_patient_fundus_active',$arrFieldsFundus,$arrValuesFundus,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");


				//End of foreach




				/* save for patient_episode_prescriptions starts here */
				
				$getChosenProduct= mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
				
				while(list($key_prod, $val_prod) = each($getChosenProduct))
				{
					$prescription_product_id = $val_prod['pp_id'];
					$prescription_trade_name = $val_prod['med_trade_name'];
					$prescription_generic_name = $val_prod['med_generic_name'];
					$prescription_frequency = $val_prod['med_frequency'];
					$prescription_timing = $val_prod['med_timing'];
					$prescription_duration = $val_prod['med_duration'];
				
					$prescription_date_time = $_POST['dateadded2'];

					
						$arrFieldsPEP = array();
						$arrValuesPEP = array();
						$arrFieldsPEP[] = 'episode_id';
						$arrValuesPEP[] = $episode_id;
						$arrFieldsPEP[] = 'doc_id';
						$arrValuesPEP[] = $admin_id;
						$arrFieldsPEP[] = 'pp_id';
						$arrValuesPEP[] = $prescription_product_id;
						$arrFieldsPEP[] = 'prescription_trade_name';
						$arrValuesPEP[] = $prescription_trade_name;
						$arrFieldsPEP[] = 'prescription_generic_name';
						$arrValuesPEP[] = $prescription_generic_name;
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'timing';
						$arrValuesPEP[] = $prescription_timing;
						$arrFieldsPEP[] = 'duration';
						$arrValuesPEP[] = $prescription_duration;
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						$insert_patient_episode_prescriptions = mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
					
					$chkProduct= mysqlSelect("pp_id,freq_count","doctor_frequent_medicine","pp_id='".$prescription_product_id."'","","","","");
					
					$arrFileds_freq = array();
					$arrValues_freq = array();
					
					if($chkProduct == true)
					{
					$freq_count=$chkProduct[0]['freq_count']+1;
					
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$prescription_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$prescription_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$prescription_frequency;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$prescription_timing;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$prescription_duration;
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;	
					$update_medicine=mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
	
					}
					else
					{
						$arrFileds_freq[]='pp_id';
						$arrValues_freq[]=$prescription_product_id;
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$prescription_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$prescription_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$prescription_frequency;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$prescription_timing;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$prescription_duration;
						$arrFileds_freq[]='doc_id';
						$arrValues_freq[]=$admin_id;
						$arrFileds_freq[]='doc_type';
						$arrValues_freq[]="1";
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]="1";

						$insert_medicine=mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
						
					}
					
					
					
					
				}  //end while loop
				/* save for patient_episode_prescriptions Ends here */
				
				$chkSaveTemplate = $_POST['chkSaveTemplate'];
				if ($chkSaveTemplate == 1)
				{
					
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

					$insert_patient_episode_prescription_template = mysqlInsert('doc_patient_episode_prescription_templates',$arrFieldsPEPT,$arrValuesPEPT);
					$template_id = $insert_patient_episode_prescription_template;
					
				
					
					reset($getChosenProduct);
					while(list($key_temp, $val_temp) = each($getChosenProduct))
					{			
						$prescription_product_id = $val_temp['pp_id'];
						$prescription_trade_name = $val_temp['med_trade_name'];
						$prescription_generic_name = $val_temp['med_generic_name'];
						$prescription_frequency = $val_temp['med_frequency'];
						$prescription_timing = $val_temp['med_timing'];
						$prescription_duration = $val_temp['med_duration'];

						$arrFieldsPEPTD = array();
						$arrValuesPEPTD = array();
						$arrFieldsPEPTD[] = 'template_id';
						$arrValuesPEPTD[] = $template_id;
						$arrFieldsPEPTD[] = 'pp_id';
						$arrValuesPEPTD[] = $prescription_product_id;
						$arrFieldsPEPTD[] = 'doc_id';
						$arrValuesPEPTD[] = $admin_id;
						$arrFieldsPEPTD[] = 'doc_type';
						$arrValuesPEPTD[] = "1";						
						$arrFieldsPEPTD[] = 'prescription_trade_name';
						$arrValuesPEPTD[] = $prescription_trade_name;
						$arrFieldsPEPTD[] = 'prescription_generic_name';
						$arrValuesPEPTD[] = $prescription_generic_name;
						$arrFieldsPEPTD[] = 'prescription_frequency';
						$arrValuesPEPTD[] = $prescription_frequency;
						$arrFieldsPEPTD[] = 'prescription_timing';
						$arrValuesPEPTD[] = $prescription_timing;
						$arrFieldsPEPTD[] = 'prescription_duration';
						$arrValuesPEPTD[] = $prescription_duration;
					
						
						$insert_patient_episode_prescription_template_desc = mysqlInsert('doc_medicine_prescription_template_details',$arrFieldsPEPTD,$arrValuesPEPTD);
						
					}
					
					
				}


					//Save for Appointment Payment Transaction
					if(!empty($_POST['consult_charge']))
					{
						$arrFieldsPayment=array();	
						$arrValuesPayment=array();
						
						$arrFieldsPayment[]='patient_name';
						$arrValuesPayment[]=$_POST['patient_name'];
						$arrFieldsPayment[]='patient_id';
						$arrValuesPayment[]=$patient_id;
						$arrFieldsPayment[]='trans_date';
						$arrValuesPayment[]=$Cur_Date;
						$arrFieldsPayment[]='narration';
						$arrValuesPayment[]="Consultation Charge";
						$arrFieldsPayment[]='amount';
						$arrValuesPayment[]=$_POST['consult_charge'];
						$arrFieldsPayment[]='user_id';
						$arrValuesPayment[]=$admin_id;
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]="1";
						$arrFieldsPayment[]='hosp_id';
						$arrValuesPayment[]=$_SESSION['login_hosp_id'];
						$arrFieldsPayment[]='payment_status';
						$arrValuesPayment[]="PENDING";
						$arrFieldsPayment[]='pay_method';
						$arrValuesPayment[]="Cash";
						$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
					}
					//Save for Appointment Payment Transaction ends here
					
					$chkPatientAppTab = mysqlSelect("*","appointment_token_system","patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".date('Y-m-d')."'","","","","");
					if(count($chkPatientAppTab)>0)
					{
						//Update Appointment Status as  -'Consulted'
						$arrFieldsAppStatus[] = 'status';
						$arrValuesAppStatus[] = "Consulted";
					
						$update_appointment=mysqlUpdate('appointment_token_system',$arrFieldsAppStatus,$arrValuesAppStatus,"token_id = '".$chkPatientAppTab[0]['token_id']."'");	
						
						$arrFieldsAppTransStatus[] = 'pay_status';
						$arrValuesAppTransStatus[] = "Consulted";
						$arrFieldsAppTransStatus[] = 'visit_status';
						$arrValuesAppTransStatus[] = "new_visit";
					
						$update_appoint_trans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppTransStatus,$arrValuesAppTransStatus,"Visiting_date = '".date('Y-m-d')."' and patient_id='".$patient_id."'and pref_doc='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'");	
						
					}

		

		$response="episode-created";
		//header("Location:All-Patient-Records?response=".$response);

		/**/
		//echo "redirecting"; exit;
		if(isset($_POST['save_patient_edit'])){
		header("Location:Ophthal-EMR/?p=".md5($patient_id)."&response=".$response);
		} else if(isset($_POST['save_patient_print']))
		{
		header("Location:../print-emr?pid=".md5($patient_id)."&episode=".md5($episode_id));
		}
		//header("Location:My-Patient-List?response=".$response);
	}



if(isset($_POST['updateInvestigation']))
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
		$arrValueTrend[]=$_POST['patient_id'];
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$cur_Date."' and patient_id='".$_POST['patient_id']."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend=mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$cur_Date."' and patient_id = '".$_POST['patient_id']."' and patient_type='1'");
		}
		else
		{
		$insert_trend_analysis= mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}

	}
	$response="update-investigation";
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
}

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
							$arrValues_Attach[] = $uploadUser;

							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = $userType;

							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;


							$bslist_pht=mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid= $bslist_pht;
							
							$folder_name	=	"patientAttachments";
							$sub_folder		=	$patientId . "/" .$timestring;
							$filename		=	$_FILES['file-5']['name'][$key];
							$file_url		=	$_FILES['file-5']['tmp_name'][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

							/* Uploading image file */

								/* $dotpos = strpos($fileName, '.');
								 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
								 $uploadfile = $uploaddir . "/" . $Photo1;*/


								/* Moving uploaded file from temporary folder to desired folder. */
								/*if(move_uploaded_file ($file_tmp, $uploadfile)) 
								{
									//echo "File uploaded.";
								} 
								else 
								{
									//echo "File cannot be uploaded";
								}*/

						} //End file empty conditions

						}//End of foreach
	$response="reports-attached";
	if(!empty($_POST['upload_user'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Patient-Attachments?d=".md5($_POST['patient_id'])."&response=".$response);
	}

}

?>
