<?php 
ob_start();
	
	//echo "<PRE>"; print_r($_POST); exit;

	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];
	$Hosp_Id = $_SESSION['login_hosp_id'];
	if(empty($admin_id))
	{
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
	require_once("../classes/querymaker.class.php");
	require_once("../DigitalOceanSpaces/src/upload_function.php");
	//$objQuery = new CLSQueryMaker();
	ob_start();

	function hyphenize($string)
	{

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
	function compress_image($source_url, $destination_url, $quality) 
	{

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
	
	
	$getDocEMR = mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."'","","","","");
				
	if($getDocEMR[0]['spec_group_id']==1)
	{  //If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
		$navigateLink = HOST_MAIN_URL."premium/My-Patient-Details";
	}
	else if($getDocEMR[0]['spec_group_id']==2)
	{ //If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
		$navigateLink = HOST_MAIN_URL."premium/Ophthal-EMR/";
	}

if(isset($_GET['suffersincedetail']) && !empty($_GET['suffersincedetail']))
{
	
	if(isset($_GET['suffersincedetail']))
	{
		$arrFileds_diagno[]='episode_medical_complaint';
		$arrValues_diagno[]=$_GET['suffersincedetail'];
	}
	$update_diagnodetail=mysqlUpdate('doc_patient_episodes',$arrFileds_diagno,$arrValues_diagno,"md5(episode_id) = '".$_GET['episodeid']."'");

}
if(isset($_GET['diagnodetail']) && !empty($_GET['diagnodetail']))
{
	if(isset($_GET['diagnodetail']))
	{
		$arrFileds_diagno[]='diagnosis_details';
		$arrValues_diagno[]=$_GET['diagnodetail'];
	}
	$update_diagnodetail=mysqlUpdate('doc_patient_episodes',$arrFileds_diagno,$arrValues_diagno,"md5(episode_id) = '".$_GET['episodeid']."'");

}
if(isset($_GET['treatmentdetail']) && !empty($_GET['treatmentdetail']))
{
	if(isset($_GET['treatmentdetail']))
	{
		$arrFileds_diagno[]='treatment_details';
		$arrValues_diagno[]=$_GET['treatmentdetail'];
	}
	$update_treatmentdetail=mysqlUpdate('doc_patient_episodes',$arrFileds_diagno,$arrValues_diagno,"md5(episode_id) = '".$_GET['episodeid']."'");
}

if(isset($_GET['prescnote']) && !empty($_GET['prescnote']))
{
	
	if(isset($_GET['prescnote']))
	{
		$arrFileds_presc[]='prescription_note';
		$arrValues_presc[]=$_GET['prescnote'];
	}
	
	$update_prescnote=mysqlUpdate('doc_patient_episodes',$arrFileds_presc,$arrValues_presc,"md5(episode_id) = '".$_GET['episodeid']."'");

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
		if(!empty($_POST['patient_id'][$key_invest]))
		{
			$arrFieldTrend[]='patient_id';
			$arrValueTrend[]=$_POST['patient_id'][$key_invest];
		}
		
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
	$getInvestDetails	= mysqlSelect("*","patient_temp_investigation","pti_id = '".$_POST['investigation_id']."'","","","","");
	$getDocDetails		= mysqlSelect("ref_name,contact_num","referal","ref_id='".$_POST['doc_id']."'","","","","");
	$getDiagnoDetails	= mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id='".$_POST['diagno_id']."'","","","","");
	
	
	$errors= array();
	$timestring = time();
	$patientId = $_POST['patient_id'];
	$uploaddirectory = realpath("patientAttachments");
	$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
	
	/*Checking whether folder with category id already exist or not. */
	if (file_exists($uploaddir)) 
	{
		//echo "The file $uploaddir exists";
	} 
	else 
	{
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

			if(!empty($patientId))
			{
				$arrFields_Attach[] = 'patient_id';
				$arrValues_Attach[] = $patientId;
			}

			$arrFields_Attach[] = 'report_folder';
			$arrValues_Attach[] = $timestring;
			
			$arrFields_Attach[] = 'attachments';
			$arrValues_Attach[] = $file_name;

			if(!empty($_POST['diagno_id']))
			{
				$arrFields_Attach[] = 'user_id';
				$arrValues_Attach[] = $_POST['diagno_id'];
			}
			
			$arrFields_Attach[] = 'user_type';
			$arrValues_Attach[] = "3"; //Diagnosis User
			
			$arrFields_Attach[] = 'date_added';
			$arrValues_Attach[] = $Cur_Date;
			
					
			$bslist_pht=mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
			$epiid= $bslist_pht;

			$folder_name	=	"patientAttachments";
			$sub_folder		=	$patientId;
			$filename		=	$_FILES['file-5']['name'][$key];
			$file_url		=	$_FILES['file-5']['tmp_name'][$key];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

			
				
		} 
		//End file empty conditions
				
	}//End of foreach
				
	//Update Patient diagnostic Status				
	$arrFieldStatus[]='status2';
	$arrValueStatus[]="2";
	$update_status=mysqlUpdate('diagnostic_referrals',$arrFieldStatus,$arrValueStatus,"diagnostic_id='".$_POST['diagno_id']."' and patient_id = '".$getInvestDetails[0]['patient_id']."' and episode_id='".$getInvestDetails[0]['episode_id']."'");

	//Send SMS to Doctor
	$DocContatct = $getDocDetails[0]['contact_num'];
	$msg= "Dear ".$getDocDetails[0]['ref_name'].", ".$getDiagnoDetails[0]['diagnosis_name']." has updated the reports of ".$_POST['patient_name']." dated ".$_POST['refer_date'].". Kindly login to your practice account to view the reports. Thanks";
	send_msg($DocContatct,$msg);		
						
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
		$res_height = $feet.".".$inches;
		$weight 	= $_POST['weight'];
		$txtContact = $_POST['se_con_per'];
		$txtMob 	= $_POST['se_phone_no'];
		$txtCountry = $_POST['se_country'];
		$txtState 	= $_POST['se_state'];
		$txtLoc 	= $_POST['se_city'];
		$txtAddress = addslashes($_POST['se_address']);
		$hyperCond 	= $_POST['se_hyper'];
		$diabetesCond = $_POST['se_diabets'];
		$dob = date('Y-m-d',strtotime($_POST['date_birth']));
		
		$patImage = addslashes($_FILES['txtPhoto']['name']);
		$arrFields = array();
		$arrValues = array();

		if(!empty($_POST['se_pat_age']))
		{
			$arrFields[] = 'patient_age';
			$arrValues[] = $txtAge;
		}

		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;

		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
	
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;

		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;
		
		if(!empty($_POST['date_birth']))
		{
			$arrFields[] = 'DOB';
			$arrValues[] = $dob;
		}

		$arrFields_patient[] = 'patient_name';
		$arrValues_patient[] = $txtName;

		
		// $arrFields_patient[] = 'member_id';
		// $arrValues_patient[] = '';

		// $arrFields_patient[] = 'login_id';
		// $arrValues_patient[] = '';//$txtContact;

		$arrFields_patient[] = 'created_date';
		$arrValues_patient[] = $curDate;

		
		

		if(isset($_POST['save_patient']))
		{
			$insert_patient	=	mysqlInsert('patients_appointment',$arrFields,$arrValues); // doc_my_patient to " patients_appointment "
			$patientid 		= 	$insert_patient;
			
		}
		else if(isset($_POST['update_patient']))
		{
			$patientid  = $_POST['patient_id'];
			$getPatInfo = mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
			$userupdate = mysqlUpdate('patients_appointment',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
			
		}


			//UPLOAD COMPRESSED IMAGE
			if ($_FILES["txtPhoto"]["error"] > 0) 
			{
        			$error = $_FILES["txtPhoto"]["error"];
    		} 
    		else if (($_FILES["txtPhoto"]["type"] == "image/gif") || ($_FILES["txtPhoto"]["type"] == "image/jpeg") || ($_FILES["txtPhoto"]["type"] =="image/png") || 
			($_FILES["txtPhoto"]["type"] == "image/pjpeg")) 
			{
				$folder_name	=	"patientImage";
				$sub_folder		=	$patientid;
				$filename		=	$_FILES['txtPhoto']['name'];
				$file_url		=	$_FILES['txtPhoto']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			}
			
		$response="updated";
		header("Location:".HOST_URL_PREMIUM."My-Patient-Details?p=".md5($patientid));
	}
	
	//CHANGE PATIENT EPISODE DATE
	if(isset($_POST['changeDate']))
	{
		$arrFields[] = 'date_time';
		$arrValues[] = $_POST['J-demo-02'];
		$userupdate	 =	mysqlUpdate('doc_patient_episodes',$arrFields,$arrValues, "md5(episode_id) = '".$_POST['episode_id']."'");
		
		$response="date-updated";
		header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&episode=".$_POST['episode_id']."&visit=".$_POST['visit']."&response=".$response);
	}
	
	//UPDATE PATIENT
	
	if(isset($_POST['updatePatient']))
	{
		
		$se_hyper 		= $_POST['se_hyper'];
		$se_diabets 	= $_POST['se_diabets'];
		$se_smoking 	= $_POST['se_smoking'];
		$se_alcoholic 	= $_POST['se_alcoholic'];
		$drug_abuse 	= $_POST['drug_abuse'];
		$other_details 	= $_POST['other_details'];
		$family_history = $_POST['family_history'];
		$prev_inter 	= $_POST['prev_inter'];
		$neuro_issue 	= $_POST['neuro_issue'];
		$kidney_issue 	= $_POST['kidney_issue'];
		
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
		
		$arrFields[] = 'other_details';
		$arrValues[] = $other_details;
		
		$arrFields[] = 'family_history';
		$arrValues[] = $family_history;
		
		$arrFields[] = 'prev_inter';
		$arrValues[] = $prev_inter;
		
		$arrFields[] = 'neuro_issue';
		$arrValues[] = $neuro_issue;
		
		$arrFields[] = 'kidney_issue';
		$arrValues[] = $kidney_issue;
		
		$userupdate=mysqlUpdate('patients_transactions',$arrFields,$arrValues, "patient_id ='". $_POST['patient_id'] ."' ");
		
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
	//Update Episode
	if(isset($_POST['update_patient_print']))
	{
		$arrFieldsEPISODE[] = 'patient_education';
		$arrValuesEPISODE[] = $_POST['pat_edu_type'];
		
		$arrFieldsEPISODE[] = 'outward_ref_id';
		$arrValuesEPISODE[] = $_POST['refer_to'];
		
		$update_episode=mysqlUpdate('doc_patient_episodes',$arrFieldsEPISODE,$arrValuesEPISODE,"episode_id = '".$_POST['episode_id']."'");
				
		/* Save Examination template details Starts here */
				
		$chkExamSaveTemplate = $_POST['chkExamSaveTemplate'];
		if ($chkExamSaveTemplate == 1)
		{
					
			$exam_template_name = $_POST['exam_template_name'];
			if ($exam_template_name == '')
			{
				$exam_template_name = 'Template';
			}
			$arrFieldsEXAMTEMP = array();
			$arrValuesEXAMTEMP = array();

			if(!empty($admin_id))
			{
				$arrFieldsEXAMTEMP[] = 'doc_id';
				$arrValuesEXAMTEMP[] = $admin_id;
			}
			$arrFieldsEXAMTEMP[] = 'doc_type';
			$arrValuesEXAMTEMP[] = "1";

			$arrFieldsEXAMTEMP[] = 'template_name';
			$arrValuesEXAMTEMP[] = $exam_template_name;					

			$insert_patient_episode_exam_template = mysqlInsert('doc_patient_episode_examination_templates',$arrFieldsEXAMTEMP,$arrValuesEXAMTEMP);
			$exam_template_id = $insert_patient_episode_exam_template;
			
			$getChosenExam= mysqlSelect("*","doc_patient_examination_active","doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_POST['episode_id']."'","","","","");
			while(list($key_examtemp, $val_examtemp) = each($getChosenExam))
			{	
				$arrFieldsEXAMTD = array();
				$arrValuesEXAMTD = array();

				if(!empty($exam_template_id))
				{
					$arrFieldsEXAMTD[] = 'exam_template_id';
					$arrValuesEXAMTD[] = $exam_template_id;
				}
				$arrFieldsEXAMTD[] = 'examination';
				$arrValuesEXAMTD[] = $val_examtemp['examination'];
				$arrFieldsEXAMTD[] = 'exam_result';
				$arrValuesEXAMTD[] = $val_examtemp['exam_result'];
				$arrFieldsEXAMTD[] = 'findings';
				$arrValuesEXAMTD[] = $val_examtemp['findings'];
				$insert_patient_episode_exam_template_desc = mysqlInsert('doc_patient_episode_examination_template_details',$arrFieldsEXAMTD,$arrValuesEXAMTD);
				
			}
		}
		/* Save Examination template details ends here */
		/* Save Invetigation template details Starts here */
		$chkInvestSaveTemplate = $_POST['chkInvestSaveTemplate'];
		if ($chkInvestSaveTemplate == 1)
		{
			$invest_template_name = $_POST['invest_template_name'];
			//echo $invest_template_name;
			if ($invest_template_name == '')
			{
				$invest_template_name = 'Template';
			}

			$arrFieldsINVESTTEMP = array();
			$arrValuesINVESTTEMP = array();

			if(!empty($admin_id))
			{
				$arrFieldsINVESTTEMP[] = 'doc_id';
				$arrValuesINVESTTEMP[] = $admin_id;
			}
			$arrFieldsINVESTTEMP[] = 'doc_type';
			$arrValuesINVESTTEMP[] = "1";
			$arrFieldsINVESTTEMP[] = 'template_name';
			$arrValuesINVESTTEMP[] = $invest_template_name;					

			$insert_patient_episode_prescription_template = mysqlInsert('doc_patient_episode_investigations_templates',$arrFieldsINVESTTEMP,$arrValuesINVESTTEMP);
			$invets_template_id = $insert_patient_episode_prescription_template;
			
			$getChosenInvset= mysqlSelect("*","patient_temp_investigation","doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_POST['episode_id']."'","","","","");
					
			while(list($key_invtemp, $val_invtemp) = each($getChosenInvset))
			{	
				$arrFieldsINVESTTD = array();
				$arrValuesINVESTTD = array();

				if(!empty($invets_template_id))
				{
					$arrFieldsINVESTTD[] = 'invest_template_id';
					$arrValuesINVESTTD[] = $invets_template_id;
				}
				if(!empty($val_invtemp['main_test_id']))
				{
					$arrFieldsINVESTTD[] = 'main_test_id';
					$arrValuesINVESTTD[] = $val_invtemp['main_test_id'];
				}

				$arrFieldsINVESTTD[] = 'test_name';
				$arrValuesINVESTTD[] = $val_invtemp['test_name'];
				$arrFieldsINVESTTD[] = 'test_actual_value';
				$arrValuesINVESTTD[] = $val_invtemp['test_actual_value'];
				$insert_patient_episode_invest_template_desc = mysqlInsert('doc_patient_episode_investigation_template_details',$arrFieldsINVESTTD,$arrValuesINVESTTD);
			}
		}
		/* Save Investigation template details ends here */
		//Save for Appointment Payment Transaction
		if(intval($_POST['consult_charge'])>0)
		{
			$arrFieldsPayment=array();	
			$arrValuesPayment=array();
						
			$arrFieldsPayment[]	='patient_name';
			$arrValuesPayment[]	=$_POST['patient_name'];

			if(!empty($_POST['patient_id']))
			{
				$arrFieldsPayment[]	='patient_id';
				$arrValuesPayment[]	=$_POST['patient_id'];
			}

			if(!empty($admin_id))
			{
				$arrFieldsPayment[]	='user_id';
				$arrValuesPayment[]	=$admin_id;
			}

			if(!empty($Hosp_Id))
			{
				$arrFieldsPayment[]	='hosp_id';
				$arrValuesPayment[]	=$Hosp_Id;
			}

			$arrFieldsPayment[]	='trans_date';
			$arrValuesPayment[]	=$Cur_Date;
			$arrFieldsPayment[]	='narration';
			$arrValuesPayment[]	="Consultation Charge";
			$arrFieldsPayment[]	='amount';
			$arrValuesPayment[]	=$_POST['consult_charge'];
			$arrFieldsPayment[]	='user_type';
			$arrValuesPayment[] ="1";
			$arrFieldsPayment[]	='payment_status';
			$arrValuesPayment[]	="PENDING";
			$arrFieldsPayment[]	='pay_method';
			$arrValuesPayment[]	="Cash";
			$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
		}
		//Save for Appointment Payment Transaction ends here

		$chkPatientAppTab = mysqlSelect("*","patients_transactions","patient_id='".$_POST['patient_id']."' and doc_id='".$admin_id."'  and hosp_id='".$_SESSION['login_hosp_id']."' and visiting_date='".date('Y-m-d')."'","","","","");
		if(count($chkPatientAppTab)>0)
		{
			$arrFieldsAppTransStatus[] = 'pay_status';
			$arrValuesAppTransStatus[] = "Consulted";
			$arrFieldsAppTransStatus[] = 'visit_status';
			$arrValuesAppTransStatus[] = "new_visit";
		
			$update_appoint_trans=mysqlUpdate('patients_transactions',$arrFieldsAppTransStatus,$arrValuesAppTransStatus,"Visiting_date = '".date('Y-m-d')."' and patient_id='".$_POST['patient_id']."'and pref_doc='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'");	
			
		}
		
		/*$chkPatientAppTab = mysqlSelect("*","appointment_token_system","patient_id='".$_POST['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".date('Y-m-d')."'","","","","");
		if(count($chkPatientAppTab)>0)
		{
			//Update Appointment Status as  -'Consulted'
			$arrFieldsAppStatus[] = 'status';
			$arrValuesAppStatus[] = "Consulted";
			$update_appointment	 =	mysqlUpdate('appointment_token_system',$arrFieldsAppStatus,$arrValuesAppStatus,"token_id = '".$chkPatientAppTab[0]['token_id']."'");	
			$arrFieldsAppTransStatus[] = 'pay_status';
			$arrValuesAppTransStatus[] = "Consulted";
			$arrFieldsAppTransStatus[] = 'visit_status';
			$arrValuesAppTransStatus[] = "new_visit";
		
			$update_appoint_trans=mysqlUpdate('appointment_transaction_detail',$arrFieldsAppTransStatus,$arrValuesAppTransStatus,"Visiting_date = '".date('Y-m-d')."' and patient_id='".$_POST['patient_id']."'and pref_doc='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'");	
			
		}*/
		header("Location:print-emr?pid=".md5($_POST['patient_id'])."&episode=".md5($_POST['episode_id']));		
	}
	//CREATE EPISODE
	if(isset($_POST['save_patient_edit']) || isset($_POST['save_patient_print']))
	{ 
		//TO CHECK AUTHENTICATION OF POST VALUES
		$patient_id 	 = (int)$_POST['patient_id'];				
		$episode_desc 	 = $_POST['episode_desc'];
		$suffering_since =  $_POST['suffering_since'];
		$medical_examination =  $_POST['medical_examination'];
		$txt_treatment 	     =  $_POST['txt_treatment'];
		$diagnosis_details   =  $_POST['diagnosis_details'];
		$treatment_details   =  $_POST['treatment_details'];
		$chkConsent          =  $_POST['chkRefer'];
		$patient_education   =  $_POST['pat_edu_type'];
		$presc_note 	=  $_POST['presc_note'];
		$patient_note 	=  $_POST['treatment_notes'];
		$refer_to 		=  $_POST['refer_to'];
		$specialization =  $_POST['specialization'];
		
		$arrFieldsPE = array();
		$arrValuesPE = array();

		if(!empty($patient_id))
		{
			$arrFieldsPE[] = 'patient_id';
			$arrValuesPE[] = $patient_id;
		}

		if(!empty($admin_id))
		{
			$arrFieldsPE[] = 'admin_id';
			$arrValuesPE[] = $admin_id;
		}


		$arrFieldsPE[] = 'treatment';
		$arrValuesPE[] = $txt_treatment;

		$arrFieldsPE[] = 'episode_medical_complaint';
		$arrValuesPE[] = $suffering_since; 
		
		$arrFieldsPE[] = 'prescription_template';
		$arrValuesPE[] = $_POST['prescription_template'];
		
		$arrFieldsPE[] = 'patient_education';
		$arrValuesPE[] = $patient_education;
				
		$arrFieldsPE[] = 'prescription_note';
		$arrValuesPE[] = $presc_note;

		$arrFieldsPE[] = 'patientNote';
		$arrValuesPE[] = $patient_note;

		$arrFieldsPE[] = 'referTo';
		$arrValuesPE[] = $refer_to;

		$arrFieldsPE[] = 'specialization';
		$arrValuesPE[] = $specialization;
	
		$arrFieldsPE[] = 'diagnosis_details';
		$arrValuesPE[] = $diagnosis_details;

		$arrFieldsPE[] = 'treatment_details';
		$arrValuesPE[] = $treatment_details;
		
		$arrFieldsPE[] = 'chkPatConsent';
		$arrValuesPE[] = $chkConsent;
		
		$arrFieldsPE[] = 'emr_type';
		$arrValuesPE[] = "1"; //1 for cardiodiabetic
		
		if(!empty($_POST['dateadded']))
		{
			$arrFieldsPE[] = 'next_followup_date';
			$arrValuesPE[] = date('Y-m-d',strtotime($_POST['dateadded']));
		}
	
		if(!empty($_POST['dateadded2']))
		{
			$arrFieldsPE[] = 'date_time';
			$arrValuesPE[] = $_POST['dateadded2'];
		}
		else
		{
			$arrFieldsPE[] = 'date_time';
			$arrValuesPE[] = $Cur_Date;
		}

			if($patient_note != '' || $refer_to != '' || $specialization != '')
			{
				
				//$insert_patient_episodes=mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
				$hid_appnt_trans_id = $_POST['appnt_trans_id'];

				
				
				$select_doc_my_pat = mysqlSelect(" a.patient_name as patient_name,b.patient_age as  patient_age,a.patient_dob as  DOB, a.patient_email as patient_email, a.patient_gender as patient_gen,b.height_cms as height_cm,b.weight as weight,b.hyper_cond as hyper_cond,b.smoking as smoking,b.alcoholic as alcoholic ,b.diabetes_cond as diabetes_cond,b.blood_group as pat_blood,b.drug_abuse as  drug_abuse,b.other_details as other_details,b.family_history as family_history,b.prev_intervention as prev_inter,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.contact_person as contact_person,a.patient_mobile as patient_mob,b.city as patient_loc,b.state as pat_state,b.country as pat_country,b.address as patient_addrs ,b.patientEMR_consent as patientEMR_consent,b.created_date as TImestamp,a.login_id as user_id,b.doc_id as doc_id,b.created_date as system_date,b.transaction_id as transaction_id,a.member_id as member_id,b.pat_bp as pat_bp,b.pat_thyroid as pat_thyroid,b.pat_cholestrole as pat_cholestrole,b.pat_epilepsy as pat_epilepsy,b.pat_asthama as pat_asthama,b.doc_video_link as doc_video_link,b.pat_video_link as pat_video_link,b.subscriber_id as subscriber_id"," patients_appointment as a INNER JOIN patients_transactions as b on a.patient_id = b.patient_id","a.patient_id = '". $patient_id ."' ","","","","");
				
				if(empty($_POST['patient_id']))
				{
					$arrFieldsDMP1[] = 'patient_name';
					
					
					$arrFieldsDMP1[] = 'patient_email';
					$arrFieldsDMP1[] = 'patient_mobile';
					$arrFieldsDMP1[] = 'patient_dob';
					$arrFieldsDMP1[] = 'patient_gen';
					$arrFieldsDMP1[] = 'created_date';

					$arrValuesDMP1[] = $select_doc_my_pat[0]['patient_name'];
					
					
					$arrValuesDMP1[] = $select_doc_my_pat[0]['patient_email'];
					$arrValuesDMP1[] = $select_doc_my_pat[0]['patient_mob'];
					$arrValuesDMP1[] = $select_doc_my_pat[0]['DOB'];
					$arrValuesDMP1[] = $select_doc_my_pat[0]['patient_gen'];
					$arrValuesDMP1[] = $select_doc_my_pat[0]['TImestamp'];

					if(!empty($select_doc_my_pat[0]['member_id']))
					{
						$arrFieldsDMP1[] = 'member_id';
						$arrValuesDMP1[] = $select_doc_my_pat[0]['member_id'];

					}
					if(!empty($select_doc_my_pat[0]['user_id']))
					{
						$arrFieldsDMP1[] = 'login_id';
						$arrValuesDMP1[] = $select_doc_my_pat[0]['user_id'];

					}


					$insert_doc_my_pat = mysqlInsert('patients_appointment',$arrFieldsDMP,$arrValuesDMP);
					$strPatientID 	   = $insert_doc_my_pat;
				}
				else
				{
					$strPatientID 	=	$_POST['patient_id'];

				}

				$arrFieldsDMP[] = 'height_cm';
				$arrFieldsDMP[] = 'weight';
				$arrFieldsDMP[] = 'hyper_cond';
				$arrFieldsDMP[] = 'smoking';
				$arrFieldsDMP[] = 'alcoholic';
				$arrFieldsDMP[] = 'diabetes_cond';
				$arrFieldsDMP[] = 'blood_group';
				$arrFieldsDMP[] = 'drug_abuse';
				$arrFieldsDMP[] = 'other_details';
				$arrFieldsDMP[] = 'family_history';
				$arrFieldsDMP[] = 'prev_intervention';
				$arrFieldsDMP[] = 'neuro_issue';
				$arrFieldsDMP[] = 'kidney_issue';
				$arrFieldsDMP[] = 'contact_person';
				$arrFieldsDMP[] = 'patient_age';
				$arrFieldsDMP[] = 'city';
				$arrFieldsDMP[] = 'state';
				$arrFieldsDMP[] = 'country';
				$arrFieldsDMP[] = 'address';
				$arrFieldsDMP[] = 'patientEMR_consent';
				$arrFieldsDMP[] = 'created_date';
				$arrFieldsDMP[] = 'doc_id';
				$arrFieldsDMP[] = 'transaction_id';
				$arrFieldsDMP[] = 'pat_bp';
				$arrFieldsDMP[] = 'pat_thyroid';
				$arrFieldsDMP[] = 'pat_cholestrole';
				$arrFieldsDMP[] = 'pat_epilepsy';
				$arrFieldsDMP[] = 'pat_asthama';
				$arrFieldsDMP[] = 'doc_video_link';
				$arrFieldsDMP[] = 'pat_video_link';
				$arrFieldsDMP[] = 'subscriber_id';
				
				$arrValuesDMP[] = $select_doc_my_pat[0]['height_cm'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['weight'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['hyper_cond'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['smoking'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['alcoholic'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['diabetes_cond'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_blood'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['drug_abuse'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['other_details'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['family_history'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['prev_inter'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['neuro_issue'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['kidney_issue'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['contact_person'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['profession'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['patient_loc'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_state'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_country'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['patient_addrs'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['patientEMR_consent'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['doc_id'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['patient_image'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['transaction_id'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_bp'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_thyroid'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_cholestrole'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_epilepsy'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_asthama'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['doc_video_link'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['pat_video_link'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['teleconsult_status'];
				$arrValuesDMP[] = $select_doc_my_pat[0]['subscriber_id'];				

				$insert_doc_my_pat = mysqlInsert('patients_transactions',$arrFieldsDMP,$arrValuesDMP);
				$strGetTrId = explode('_', $_POST['appnt_trans_id']);

				if($strGetTrId != '')
				{
					//echo " SELECT * FROM appointment_transaction_detail where appoint_trans_id = '". $strGetTrId['1'] ."' "; exit;
					$select_app_tr_det = mysqlSelect("appoint_trans_id, Payment_id, patient_id, hosp_id, member_id, pref_doc, user_type, department, Login_User_Id, Hosp_patient_Id, Visiting_date, Visiting_time, patient_name, Mobile_no, Email_address, Amount, pay_status, visit_status, tele_communication, patientEMR_consent, Time_stamp, medisense_share, hosp_share, src_type, appointment_type, reference_id, referring_hosp, referring_doc, teleconsult_status","appointment_transaction_detail","appoint_trans_id = '". $strGetTrId['1'] ."' ","","","","");

					$arrFieldsATD[] = 'appoint_trans_id';
					$arrFieldsATD[] = 'Payment_id';
					$arrFieldsATD[] = 'patient_id';
					$arrFieldsATD[] = 'hosp_id';
					$arrFieldsATD[] = 'member_id';
					$arrFieldsATD[] = 'pref_doc';
					$arrFieldsATD[] = 'user_type';
					$arrFieldsATD[] = 'department';
					$arrFieldsATD[] = 'Login_User_Id';
					$arrFieldsATD[] = 'Hosp_patient_Id';
					$arrFieldsATD[] = 'Visiting_date';
					$arrFieldsATD[] = 'Visiting_time';
					$arrFieldsATD[] = 'patient_name';
					$arrFieldsATD[] = 'Mobile_no';
					$arrFieldsATD[] = 'Email_address';
					$arrFieldsATD[] = 'Amount';
					$arrFieldsATD[] = 'pay_status';
					$arrFieldsATD[] = 'visit_status';
					$arrFieldsATD[] = 'tele_communication';
					$arrFieldsATD[] = 'patientEMR_consent';
					$arrFieldsATD[] = 'Time_stamp';
					$arrFieldsATD[] = 'medisense_share';
					$arrFieldsATD[] = 'hosp_share';
					$arrFieldsATD[] = 'src_type';
					$arrFieldsATD[] = 'appointment_type';
					$arrFieldsATD[] = 'reference_id';
					$arrFieldsATD[] = 'referring_hosp';
					$arrFieldsATD[] = 'referring_doc';
					$arrFieldsATD[] = 'teleconsult_status';

					$arrValuesATD[] = $select_app_tr_det[0]['appoint_trans_id'];
					$arrValuesATD[] = $select_app_tr_det[0]['Payment_id'];
					$arrValuesATD[] = $strPatientID;
					$arrValuesATD[] = $select_app_tr_det[0]['hosp_id'];
					$arrValuesATD[] = $select_app_tr_det[0]['member_id'];
					$arrValuesATD[] = $select_app_tr_det[0]['pref_doc'];
					$arrValuesATD[] = $select_app_tr_det[0]['user_type'];
					$arrValuesATD[] = $select_app_tr_det[0]['department'];
					$arrValuesATD[] = $select_app_tr_det[0]['Login_User_Id'];
					$arrValuesATD[] = $select_app_tr_det[0]['Hosp_patient_Id'];
					$arrValuesATD[] = $select_app_tr_det[0]['Visiting_date'];
					$arrValuesATD[] = $select_app_tr_det[0]['Visiting_time'];
					$arrValuesATD[] = $select_app_tr_det[0]['patient_name'];
					$arrValuesATD[] = $select_app_tr_det[0]['Mobile_no'];
					$arrValuesATD[] = $select_app_tr_det[0]['Email_address'];
					$arrValuesATD[] = $select_app_tr_det[0]['Amount'];
					$arrValuesATD[] = $select_app_tr_det[0]['pay_status'];
					$arrValuesATD[] = $select_app_tr_det[0]['visit_status'];
					$arrValuesATD[] = $select_app_tr_det[0]['tele_communication'];
					$arrValuesATD[] = $select_app_tr_det[0]['patientEMR_consent'];
					$arrValuesATD[] = $select_app_tr_det[0]['Time_stamp'];
					$arrValuesATD[] = $select_app_tr_det[0]['medisense_share'];
					$arrValuesATD[] = $select_app_tr_det[0]['hosp_share'];
					$arrValuesATD[] = $select_app_tr_det[0]['src_type'];
					$arrValuesATD[] = $select_app_tr_det[0]['appointment_type'];
					$arrValuesATD[] = $select_app_tr_det[0]['reference_id'];
					$arrValuesATD[] = $select_app_tr_det[0]['referring_hosp'];
					$arrValuesATD[] = $select_app_tr_det[0]['referring_doc'];
					$arrValuesATD[] = $select_app_tr_det[0]['teleconsult_status'];

			$insert_app_tr_det = mysqlInsert('appointment_transaction_detail',$arrFieldsATD,$arrValuesATD);

			$select_app_tkn_sys = mysqlSelect("token_id, token_no, patient_id, appoint_trans_id, patient_name, doc_id, doc_type, hosp_id, status, tele_communication, patientEMR_consent, app_date, app_time, set_diation_timer, dilation_status, appointment_type, reference_id, referring_hosp, referring_doc, referal_note, created_date","appointment_token_system","appoint_trans_id = '". $strGetTrId['1'] ."' ","","","","");
					
			$arrFieldsATS[] = 'token_no';
			$arrFieldsATS[] = 'patient_id';
			$arrFieldsATS[] = 'appoint_trans_id';
			$arrFieldsATS[] = 'patient_name';
			$arrFieldsATS[] = 'doc_id';
			$arrFieldsATS[] = 'doc_type';
			$arrFieldsATS[] = 'hosp_id';
			$arrFieldsATS[] = 'status';
			$arrFieldsATS[] = 'tele_communication';
			$arrFieldsATS[] = 'patientEMR_consent';
			$arrFieldsATS[] = 'app_date';
			$arrFieldsATS[] = 'app_time';
			$arrFieldsATS[] = 'set_diation_timer';
			$arrFieldsATS[] = 'dilation_status';
			$arrFieldsATS[] = 'appointment_type';
			$arrFieldsATS[] = 'reference_id';
			$arrFieldsATS[] = 'referring_hosp';
			$arrFieldsATS[] = 'referring_doc';
			$arrFieldsATS[] = 'referal_note';
			$arrFieldsATS[] = 'created_date';

			$arrValuesATS[] = '1';
			$arrValuesATS[] = $strPatientID;
			$arrValuesATS[] = $select_app_tkn_sys[0]['appoint_trans_id'];
			$arrValuesATS[] = $select_app_tkn_sys[0]['patient_name'];
			$arrValuesATS[] = '3744';
			$arrValuesATS[] = $select_app_tkn_sys[0]['doc_type'];
			$arrValuesATS[] = $select_app_tkn_sys[0]['hosp_id'];
			$arrValuesATS[] = $select_app_tkn_sys[0]['status'];
			$arrValuesATS[] = $select_app_tkn_sys[0]['tele_communication'];
			$arrValuesATS[] = $select_app_tkn_sys[0]['patientEMR_consent'];
			$arrValuesATS[] = date('Y-m-d');
			$arrValuesATS[] = '';
			$arrValuesATS[] = '';
			$arrValuesATS[] = '';
			$arrValuesATS[] = '0';
			$arrValuesATS[] = '0';
			$arrValuesATS[] = '';
			$arrValuesATS[] = $admin_id;
			$arrValuesATS[] = $patient_note;
			$arrValuesATS[] = date('Y-m-d h:i:s');						

			$insert_app_tkn_sys = mysqlInsert('appointment_token_system',   $arrFieldsATS,$arrValuesATS);

			//echo "<PRE>"; print_r($insert_app_tkn_sys); exit;


		}
	}
	else{
		//echo "No Referral";
	}

	//echo "<PRE>"; print_r($_POST); exit;

	$insert_patient_episodes=mysqlInsert('doc_patient_episodes',$arrFieldsPE,$arrValuesPE);
	$episode_id = $insert_patient_episodes;
	
	if($chkConsent=="1")
	{
		$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");

	$arrFieldsChat = array();
	$arrValuesChat = array();
	if(!empty($patient_id))
	{
		$arrFieldsChat[] = 'patient_id';
		$arrValuesChat[] = $patient_id;
	}
	if(!empty($episode_id))
	{
		$arrFieldsChat[] = 'episode_id';
		$arrValuesChat[] = $episode_id;
	}
	if(!empty($admin_id))
	{
		$arrFieldsChat[] = 'doc_id';
		$arrValuesChat[] = $admin_id;
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFieldsChat[] = 'company_id';
		$arrValuesChat[] = $getDocDetails[0]['company_id'];
	}
	
	$arrFieldsChat[] = 'chat_note';
	$arrValuesChat[] = "EMR is referred to Institution successfully";
	$arrFieldsChat[] = 'status';
	$arrValuesChat[] = "1";
	$arrFieldsChat[] = 'created_date';
	$arrValuesChat[] = $Cur_Date;
	
	$insert_chat_notification=mysqlInsert('emr_referred_notifications',$arrFieldsChat,$arrValuesChat);
	}
				
				if(!empty($refer_to))
				{
					if(!empty($patient_id))
					{
						$arrFileds_outref[]='patient_id';
						$arrValues_outref[]=$patient_id;
					}
					if(!empty($episode_id))
					{
						$arrFileds_outref[]='episode_id';
						$arrValues_outref[]=$episode_id;
					}
					if(!empty($admin_id))
					{
						$arrFileds_outref[]='doc_id';
						$arrValues_outref[]=$admin_id;
					}

					if(!empty($refer_to))
					{
						$arrFileds_outref[]='referral_id';
						$arrValues_outref[]=$refer_to;
					}

					$arrFileds_outref[]='doc_type';
					$arrValues_outref[]="1";
					
					$arrFileds_outref[]='type';
					$arrValues_outref[]="4";
					$arrFileds_outref[]='timestamp';
					$arrValues_outref[]=$Cur_Date;
					$insert_outgoing_referrals=mysqlInsert('doctor_outgoing_referrals',$arrFileds_outref,$arrValues_outref);	
				}
				
				if(!empty($episode_id))
				{
					$arrFieldsSYMPTOMS[] = 'episode_id';
					$arrValuesSYMPTOMS[] = $episode_id;
				}

				
				$arrFieldsSYMPTOMS[] = 'status';
				$arrValuesSYMPTOMS[] = "0";
				$update_icd=mysqlUpdate('doc_patient_symptoms_active',$arrFieldsSYMPTOMS,$arrValuesSYMPTOMS,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
				
						
			
				//Update Diagnosis -' patient_diagnosis' table

				if(!empty($episode_id))
				{
					$arrFieldsExam[] = 'episode_id';
					$arrValuesExam[] = $episode_id;
				}

				
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=mysqlUpdate('patient_diagnosis',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
				
				//Update Treatment -'doc_patient_treatment_active' table
				if(!empty($episode_id))
				{
					$arrFieldsTreat[] = 'episode_id';
					$arrValuesTreat[] = $episode_id;
				}

				
				$arrFieldsTreat[] = 'status';
				$arrValuesTreat[] = "0";
				$update_icd=mysqlUpdate('doc_patient_treatment_active',$arrFieldsTreat,$arrValuesTreat,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");	
				
				$getTreatmentDetails = mysqlSelect("b.treatment as surgery_name","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.patient_id = '".$patient_id."' and a.doc_id='".$admin_id."' and a.doc_type='1' and a.episode_id='".$episode_id."'","","","","");
	
				if(!empty($patient_id))
				{
					$arrField_Scheduler[]	="patient_id";
					$arrVal_Scheduler[]		=$patient_id;
				}

				if(!empty($admin_id))
				{
					$arrField_Scheduler[]	="doc_id";
					$arrVal_Scheduler[]		=$admin_id;
				}

				$arrField_Scheduler[]="doc_type";
				$arrVal_Scheduler[]="1";
				
				$arrField_Scheduler[]="title";
				$arrVal_Scheduler[]=$getTreatmentDetails[0]['surgery_name'];
				
				$arrField_Scheduler[]="status";
				$arrVal_Scheduler[]="Scheduled";
				
				$arrField_Scheduler[]="date";
				$arrVal_Scheduler[]=date('Y-m-d',strtotime($_POST['dateadded5']));
				
				$arrField_Scheduler[]="time";
				$arrVal_Scheduler[]=date('H:i:s',strtotime($_POST['dateadded5']));
				
				$arrField_Scheduler[]="created";
				$arrVal_Scheduler[]=$Cur_Date;
				
				$arrField_Scheduler[]="modified";
				$arrVal_Scheduler[]=$Cur_Date;
				
				if(!empty($_POST['dateadded5']))
				{
					$insert_treatment=mysqlInsert('ot_scheduler',$arrField_Scheduler,$arrVal_Scheduler);
				}
				

				/* save for patient_episode_prescriptions starts here */
				
				$getChosenProduct= mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
				
				while(list($key_prod, $val_prod) = each($getChosenProduct))
				{
					$prescription_product_id 	= $val_prod['pp_id'];
					$prescription_trade_name 	= $val_prod['med_trade_name'];
					$prescription_generic_name 	= $val_prod['med_generic_name'];
					$prescription_frequency 	= $val_prod['med_frequency'];
					$prescription_timing 		= $val_prod['med_timing'];
					$prescription_duration 		= $val_prod['med_duration'];
					
					$prescription_frequency_morning = $val_prod['med_frequency_morning'];
					$prescription_frequency_noon 	= $val_prod['med_frequency_noon'];
					$prescription_frequency_night 	= $val_prod['med_frequency_night'];
					$prescription_duration_type 	= $val_prod['med_duration_type'];
					$prescription_other_instruction = $val_prod['other_instruction'];
				
					$prescription_date_time = $_POST['dateadded2'];

					
						$arrFieldsPEP = array();
						$arrValuesPEP = array();

						if(!empty($episode_id))
						{
							$arrFieldsPEP[] = 'episode_id';
							$arrValuesPEP[] = $episode_id;
						}
						if(!empty($admin_id))
						{
							$arrFieldsPEP[] = 'doc_id';
							$arrValuesPEP[] = $admin_id;
						}
						if(!empty($prescription_product_id))
						{
							$arrFieldsPEP[] = 'pp_id';
							$arrValuesPEP[] = $prescription_product_id;
						}

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
						
						$arrFieldsPEP[] = 'med_frequency_morning';
						$arrValuesPEP[] = $prescription_frequency_morning;
						$arrFieldsPEP[] = 'med_frequency_noon';
						$arrValuesPEP[] = $prescription_frequency_noon;
						$arrFieldsPEP[] = 'med_frequency_night';
						$arrValuesPEP[] = $prescription_frequency_night;
						$arrFieldsPEP[] = 'med_duration_type';
						$arrValuesPEP[] = $prescription_duration_type;
						$arrFieldsPEP[] = 'prescription_instruction';
						$arrValuesPEP[] = $prescription_other_instruction;
						$arrFieldsPEP[] = 'prescription_template';
						$arrValuesPEP[] = $_POST['prescription_template'];
						
						
						$arrFieldsPEP[] = 'prescription_date_time';
						$arrValuesPEP[] = $prescription_date_time;
						$insert_patient_episode_prescriptions = mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);
					
					$chkProduct= mysqlSelect("pp_id,freq_count","doctor_frequent_medicine","pp_id='".$prescription_product_id."'","","","","");
					
					$arrFileds_freq = array();
					$arrValues_freq = array();
					
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
						
						$arrFileds_freq[]='med_frequency_morning';
						$arrValues_freq[]=$prescription_frequency_morning;
						$arrFileds_freq[]='med_frequency_noon';
						$arrValues_freq[]=$prescription_frequency_noon;
						$arrFileds_freq[]='med_frequency_night';
						$arrValues_freq[]=$prescription_frequency_night;
						$arrFileds_freq[]='med_duration_type';
						$arrValues_freq[]=$prescription_duration_type;
						$arrFileds_freq[]='prescription_instruction';
						$arrValues_freq[]=$prescription_other_instruction;
						
						if(!empty($admin_id))
						{
							$arrFileds_freq[]='doc_id';
							$arrValues_freq[]=$admin_id;
						}

						
						$arrFileds_freq[]='doc_type';
						$arrValues_freq[]="1";
						
					if($chkProduct == true)
					{
						$freq_count=$chkProduct[0]['freq_count']+1;
					
					
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;	
						$update_medicine=mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");
	
					}
					else
					{
						if(!empty($prescription_product_id))
						{
							$arrFileds_freq[]='pp_id';
							$arrValues_freq[]=$prescription_product_id;
						}

						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]="1";

						$insert_medicine=mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
						
					}
					
					
					
					
				}  //end while loop
				/* save for patient_episode_prescriptions Ends here */
				
				/* Save Examination template details Starts here */
				
				$chkExamSaveTemplate = $_POST['chkExamSaveTemplate'];
				
				if ($chkExamSaveTemplate == 1)
				{
					
					$exam_template_name = $_POST['exam_template_name'];
					if ($exam_template_name == '')
					{
						$exam_template_name = 'Template';
					}

					$arrFieldsEXAMTEMP = array();
					$arrValuesEXAMTEMP = array();

					if(!empty($admin_id))
					{
						$arrFieldsEXAMTEMP[] = 'doc_id';
						$arrValuesEXAMTEMP[] = $admin_id;
					}
					
					$arrFieldsEXAMTEMP[] = 'doc_type';
					$arrValuesEXAMTEMP[] = "1";
					$arrFieldsEXAMTEMP[] = 'template_name';
					$arrValuesEXAMTEMP[] = $exam_template_name;					

					$insert_patient_episode_exam_template = mysqlInsert('doc_patient_episode_examination_templates',$arrFieldsEXAMTEMP,$arrValuesEXAMTEMP);
					$exam_template_id = $insert_patient_episode_exam_template;
					
				
					$getChosenExam= mysqlSelect("*","doc_patient_examination_active","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
					
					while(list($key_examtemp, $val_examtemp) = each($getChosenExam))
					{	
						$arrFieldsEXAMTD = array();
						$arrValuesEXAMTD = array();

						if(!empty($exam_template_id))
						{
							$arrFieldsEXAMTD[] = 'exam_template_id';
							$arrValuesEXAMTD[] = $exam_template_id;
						}

						$arrFieldsEXAMTD[] = 'examination';
						$arrValuesEXAMTD[] = $val_examtemp['examination'];
						$arrFieldsEXAMTD[] = 'exam_result';
						$arrValuesEXAMTD[] = $val_examtemp['exam_result'];
						$arrFieldsEXAMTD[] = 'findings';
						$arrValuesEXAMTD[] = $val_examtemp['findings'];
						
						
						$insert_patient_episode_exam_template_desc = mysqlInsert('doc_patient_episode_examination_template_details',$arrFieldsEXAMTD,$arrValuesEXAMTD);
						
					}
					
					
				}
				/* Save Examination template details ends here */
				//Update Examination -'doc_patient_examination_active' table

				if(!empty($episode_id))
				{
					$arrFieldsExam[] = 'episode_id';
					$arrValuesExam[] = $episode_id;
				}

				
				$arrFieldsExam[] = 'status';
				$arrValuesExam[] = "0";
				$update_icd=mysqlUpdate('doc_patient_examination_active',$arrFieldsExam,$arrValuesExam,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");	
				
				/* Save Invetigation template details Starts here */
				
				$chkInvestSaveTemplate = $_POST['chkInvestSaveTemplate'];
				
				//echo $chkInvestSaveTemplate."<br>";
				
				if ($chkInvestSaveTemplate == 1)
				{
					
					$invest_template_name = $_POST['invest_template_name'];
					//echo $invest_template_name;
					if ($invest_template_name == '')
					{
						$invest_template_name = 'Template';
					}

					$arrFieldsINVESTTEMP = array();
					$arrValuesINVESTTEMP = array();

					if(!empty($admin_id))
					{
						$arrFieldsINVESTTEMP[] = 'doc_id';
						$arrValuesINVESTTEMP[] = $admin_id;
					}


					
					$arrFieldsINVESTTEMP[] = 'doc_type';
					$arrValuesINVESTTEMP[] = "1";
					$arrFieldsINVESTTEMP[] = 'template_name';
					$arrValuesINVESTTEMP[] = $invest_template_name;					

					$insert_patient_episode_prescription_template = mysqlInsert('doc_patient_episode_investigations_templates',$arrFieldsINVESTTEMP,$arrValuesINVESTTEMP);
					$invets_template_id = $insert_patient_episode_prescription_template;
					
				
					$getChosenInvset= mysqlSelect("*","patient_temp_investigation","doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
					
					while(list($key_invtemp, $val_invtemp) = each($getChosenInvset))
					{	
						$arrFieldsINVESTTD = array();
						$arrValuesINVESTTD = array();
						if(!empty($invets_template_id))
						{
							$arrFieldsINVESTTD[] = 'invest_template_id';
							$arrValuesINVESTTD[] = $invets_template_id;
						}

						if(!empty($val_invtemp['main_test_id']))
						{
							$arrFieldsINVESTTD[] = 'main_test_id';
							$arrValuesINVESTTD[] = $val_invtemp['main_test_id'];
						}

						
						
						$arrFieldsINVESTTD[] = 'test_name';
						$arrValuesINVESTTD[] = $val_invtemp['test_name'];
						$arrFieldsINVESTTD[] = 'test_actual_value';
						$arrValuesINVESTTD[] = $val_invtemp['test_actual_value'];
						
						
						$insert_patient_episode_invest_template_desc = mysqlInsert('doc_patient_episode_investigation_template_details',$arrFieldsINVESTTD,$arrValuesINVESTTD);
						
					}
					
					
				}
				/* Save Investigation template details ends here */
				
				//Update Investigation -' patient_temp_investigation' table
				$arrFieldsINVEST=array();
				$arrValuesINVEST=array();

				if(!empty($episode_id))
				{
					$arrFieldsINVEST[] = 'episode_id';
					$arrValuesINVEST[] = $episode_id;
				}

				
				$arrFieldsINVEST[] = 'status';
				$arrValuesINVEST[] = "0";
				$update_icd=mysqlUpdate('patient_temp_investigation',$arrFieldsINVEST,$arrValuesINVEST,"patient_id = '".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and status='1'");
				
				
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

					if(!empty($admin_id))
					{
						$arrFieldsPEPT[] = 'admin_id';
						$arrValuesPEPT[] = $admin_id;
					}

					
					$arrFieldsPEPT[] = 'template_name';
					$arrValuesPEPT[] = $template_name;					

					$insert_patient_episode_prescription_template = mysqlInsert('doc_patient_episode_prescription_templates',$arrFieldsPEPT,$arrValuesPEPT);
					$template_id = $insert_patient_episode_prescription_template;
					
				
					
					reset($getChosenProduct);
					while(list($key_temp, $val_temp) = each($getChosenProduct))
					{			
						$prescription_product_id 	= $val_temp['pp_id'];
						$prescription_trade_name 	= $val_temp['med_trade_name'];
						$prescription_generic_name 	= $val_temp['med_generic_name'];
						$prescription_frequency 	= $val_temp['med_frequency'];
						$prescription_timing 		= $val_temp['med_timing'];
						$prescription_duration 		= $val_temp['med_duration'];
						$med_frequency_morning 		= $val_temp['med_frequency_morning'];
						$med_frequency_noon 		= $val_temp['med_frequency_noon'];
						$med_frequency_night 		= $val_temp['med_frequency_night'];
						$med_duration_type			= $val_temp['med_duration_type'];
						$other_instruction 			= $val_temp['other_instruction'];

						$arrFieldsPEPTD = array();
						$arrValuesPEPTD = array();

						if(!empty($template_id))
						{
							$arrFieldsPEPTD[] = 'template_id';
							$arrValuesPEPTD[] = $template_id;
						}
						if(!empty($prescription_product_id))
						{
							$arrFieldsPEPTD[] = 'pp_id';
							$arrValuesPEPTD[] = $prescription_product_id;
						}
						if(!empty($admin_id))
						{
							$arrFieldsPEPTD[] = 'doc_id';
							$arrValuesPEPTD[] = $admin_id;
						}
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
					
						$arrFieldsPEPTD[] = 'med_frequency_morning';
						$arrValuesPEPTD[] = $med_frequency_morning;
						$arrFieldsPEPTD[] = 'med_frequency_noon';
						$arrValuesPEPTD[] = $med_frequency_noon;
						$arrFieldsPEPTD[] = 'med_frequency_night';
						$arrValuesPEPTD[] = $med_frequency_night;
						$arrFieldsPEPTD[] = 'med_duration_type';
						$arrValuesPEPTD[] = $med_duration_type;
						$arrFieldsPEPTD[] = 'other_instruction';
						$arrValuesPEPTD[] = $other_instruction;
						
						
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

						if(!empty($admin_id))
						{
							$arrFieldsPayment[]='user_id';
							$arrValuesPayment[]=$admin_id;
						}
						
						$arrFieldsPayment[]='user_type';
						$arrValuesPayment[]="1";

						
						if(!empty($Hosp_Id))
						{
							$arrFieldsPayment[]='hosp_id';
							$arrValuesPayment[]=$Hosp_Id;
						}
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
		
		//Insert to 'trend_analysis'
		$arrFieldTrend=array();
		$arrValueTrend=array();
		
		$arrFieldTrend[]='date_added';
		$arrValueTrend[]=date('Y-m-d',strtotime($_POST['dateadded2']));
		if(!empty($patient_id))
		{
			$arrFieldTrend[]='patient_id';
		$arrValueTrend[]=$patient_id;
		}
		
		
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".date('Y-m-d',strtotime($_POST['dateadded2']))."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		if(count($checkTrend)==0)
		{
			$insert_trend_analysis= mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
		
		$response="episode-created";
		//header("Location:All-Patient-Records?response=".$response);

		/**/
		//echo "redirecting"; exit;
		if(isset($_POST['save_patient_edit']))
		{
			header("Location:My-Patient-Details?p=".md5($patient_id)."&response=".$response);
		} 
		else if(isset($_POST['save_patient_print']))
		{
			header("Location:print-emr?pid=".md5($patient_id)."&episode=".md5($episode_id));	
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
		$arrValueTrend[]=$_POST['date_added'];

		if(!empty($patient_id))
		{
			$arrFieldTrend[]='patient_id';
			$arrValueTrend[]=$_POST['patient_id'];	
		}

		
		$arrFieldTrend[]='patient_type';
		$arrValueTrend[]="1";
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$_POST['date_added']."' and patient_id='".$_POST['patient_id']."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_trend=mysqlUpdate('trend_analysis',$arrFieldTrend,$arrValueTrend,"date_added='".$_POST['date_added']."' and patient_id = '".$_POST['patient_id']."' and patient_type='1'");
		}
		else
		{
		$insert_trend_analysis= mysqlInsert('trend_analysis',$arrFieldTrend,$arrValueTrend);
		}
	
	}
	while(list($key_opinvest, $value_opinvest) = each($_POST['op_investigation_id']))
	{
		if(!empty($_POST['lefteye'][$key_opinvest])){
		$arrFiedOpInvest[]='left_eye';
		$arrValueOpInvest[]=$_POST['lefteye'][$key_opinvest];
		}
		
		if(!empty($_POST['righteye'][$key_opinvest])){
		$arrFiedOpInvest[]='right_eye';
		$arrValueOpInvest[]=$_POST['righteye'][$key_opinvest];
		}
		$update_opinvest=mysqlUpdate('patient_temp_investigation',$arrFiedOpInvest,$arrValueOpInvest, "pti_id = '".$_POST['op_investigation_id'][$key_opinvest]."'");
	
	}
	$response="update-investigation";
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
}

//Delete Patient Attachments
if(isset($_GET['reportid']) && !empty($_GET['reportid']))
{
	mysqlDelete('doc_my_patient_reports',"md5(report_id)='".$_GET['reportid']."'");	
}

//Add Patient Attachments
if(isset($_POST['addAttachments']))
{
	//Save patient episode attachments
				
	$errors= array();
	$timestring = time();
	if(!empty($_POST['upload_user']))
	{
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
	
	/*Checking whether folder with category id already exist or not.*/
	if (file_exists($uploaddir)) 
	{
		//echo "The file $uploaddir exists";
	} 
	else 
	{
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

			
			if(!empty($patient_id))
			{
				$arrFields_Attach[] = 'patient_id';
				$arrValues_Attach[] = $patientId;	
			}

			
			
			$arrFields_Attach[] = 'report_title';
			$arrValues_Attach[] = $_POST['report_title'];
			
			$arrFields_Attach[] = 'report_folder';
			$arrValues_Attach[] = $timestring;
			
			$arrFields_Attach[] = 'attachments';
			$arrValues_Attach[] = $file_name;

			if(!empty($uploadUser))
			{
				$arrFields_Attach[] = 'user_id';
				$arrValues_Attach[] = $uploadUser;
			}
			
			
							
			$arrFields_Attach[] = 'user_type';
			$arrValues_Attach[] = $userType;
			
			$arrFields_Attach[] = 'date_added';
			$arrValues_Attach[] = $Cur_Date;
			
					
			$bslist_pht	=	mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
			$epiid		=   $bslist_pht;
			
			$folder_name	=	"patientAttachments";
			$sub_folder		=	$patientId;
			$filename		=	$_FILES['file-5']['name'];
			$file_url		=	$_FILES['file-5']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload


			
				
		} //End file empty conditions
				
	}//End of foreach
	$response="reports-attached";
	if(!empty($_POST['upload_user'])){
	header("Location:".$_SESSION['EMR_URL'].md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Patient-Attachments?d=".md5($_POST['patient_id'])."&response=".$response);
	}

}


//Add Patient Attachments for fundus image
if(isset($_POST['addFundusImageAttach']))
{
	//Save patient episode attachments
				
	$errors= array();
	$timestring = time();
	if(!empty($_POST['upload_user']))
	{
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
			if (file_exists($uploaddir)) 
			{
				//echo "The file $uploaddir exists";
			} 
			else 
			{
				$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
				$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
			}
					//	foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
					//	{	
												
						
						//$file_name = $_FILES['file-5']['name'][$key];
						//$file_size =$_FILES['file-5']['size'][$key];
						//$file_tmp =$_FILES['file-5']['tmp_name'][$key];
						//$file_type=$_FILES['file-5']['type'][$key];
						
						//if(!empty($file_name)){
							//$Photo1  = $file_name;
							$file_name = $timestring."-"."fundus_image.png";
							$Photo1  = $timestring."-"."fundus_image.png";
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							if(!empty($patientId))
							{
								$arrFields_Attach[] = 'patient_id';
								$arrValues_Attach[] = $patientId;
							}

							
							
							$arrFields_Attach[] = 'report_title';
							$arrValues_Attach[] = $_POST['report_title'];
							
							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = $timestring;
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $file_name;

							if(!empty($uploadUser))
							{
								$arrFields_Attach[] = 'user_id';
								$arrValues_Attach[] = $uploadUser;
							}

							$arrFields_Attach[] = 'user_type';
							$arrValues_Attach[] = $userType;
							
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $Cur_Date;
							
									
							$bslist_pht	=	mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							$epiid		= 	$bslist_pht;


							/* Uploading image file */ 
							$folder_name	=	"patientAttachments";
							$sub_folder		=	$patientId;
							$filename		=	$_FILES['txtProfessional_Contribution_file']['name'];
							$file_url		=	$_FILES['txtProfessional_Contribution_file']['tmp_name'];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
															 
							/*$dotpos = strpos($fileName, '.');
							$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
							$uploadfile = $uploaddir . "/" . $Photo1;
								
							$img = $_POST['fundus_image'];
							$img = str_replace('data:image/png;base64,', '', $img);
							$img = str_replace(' ', '+', $img);
							$data = base64_decode($img);
							 // $file = 'uploads/img'.date("YmdHis").'.png';
							   
							if (file_put_contents($uploadfile, $data)) 
							{
								//echo "<p>The canvas was saved as $file.</p>";
							} 
							else 
							{
								// echo "<p>The canvas could not be saved.</p>";
							} */
								/* Moving uploaded file from temporary folder to desired folder. */
							/*	if(move_uploaded_file ($file_tmp, $uploadfile)) {
									//echo "File uploaded.";
								} else {
									//echo "File cannot be uploaded";
								}*/
								
							//} //End file empty conditions
								
					//	}//End of foreach
	$response="fundus-image-attached";
/*	if(!empty($_POST['upload_user'])){
	header("Location:".$_SESSION['EMR_URL'].md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Patient-Attachments?d=".md5($_POST['patient_id'])."&response=".$response);
	}*/
    $result = array('status' => "true");
	
	$data = array(
	 'result'  => $result
	);

	echo json_encode($data);
}	

?>