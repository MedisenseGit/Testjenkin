<?php ob_start();
	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];
	$Hosp_Id = $_SESSION['login_hosp_id'];
	if(empty($admin_id)){
		header("Location:../index.php");
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
	
	
	$getDocEMR = $objQuery->mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."'","","","","");
				
	if($getDocEMR[0]['spec_group_id']==1){  //If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
		$navigateLink = HOST_MAIN_URL."premium/My-Patient-Details";
	}
	else if($getDocEMR[0]['spec_group_id']==2){ //If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
		$navigateLink = HOST_MAIN_URL."premium/Ophthal-EMR/";
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
		$dob 		= date('Y-m-d',strtotime($_POST['date_birth']));
		$patImage 	= addslashes($_FILES['txtPhoto']['name']);
		$result 	= mysqlSelect('*','parents_tab',"primary_mobile_num='".$txtMob."'");
		
		$arrFields = array();
		$arrValues = array();
	
						
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		
		if(!empty($_POST['se_pat_age']))
		{
			$arrFields[] = 'patient_age';
			$arrValues[] = $txtAge;
		}
			
		if(!empty($_POST['date_birth']))
		{
			$arrFields[] = 'DOB';
			$arrValues[] = $dob;
		}

		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;

		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'weight';
		$arrValues[] = $weight;
		
		$arrFields[] = 'height_cm';
		$arrValues[] = $_POST['height'];
		
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
		if(!empty($admin_id))
		{
			$arrFields[] = 'doc_id';
			$arrValues[] = $admin_id;
		}
		$arrFields[] = 'TImestamp';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		
		if(!empty($_FILES['txtPhoto']['name']))
		{
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
		
		$txtBirthOrder 	= $_POST['se_birth_order'];
		$txtEDD 		= $_POST['se_edd'];
		$txtAdmitDate 	= $_POST['se_date_of_admit'];
		$txtDischargeDate = $_POST['se_date_of_discharge'];
		$creation_date 	= $_POST['creation_date'];
		$vaccine_start_date = $_POST['vaccine_start_date'];
		$se_des = addslashes($_POST['se_des']);
		
		$txtMotherName 	= $_POST['se_mother_name'];
		$txtFatherName 	= $_POST['se_father_name'];
		$txtMotherAge 	= $_POST['se_mother_age'];
		$txtFatherAge 	= $_POST['se_father_age'];
		$txtPHCLocation = $_POST['se_phc_location'];
		
		//Calculate Actual Age(Current Date minus DOB)
		$birth_date     = new DateTime($dob);
		$current_date   = new DateTime();
		$diff           = $birth_date->diff($current_date);
		$actualAge     	= $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)";
		
		//Calculate Corrected Age(Current Date minus EDD)
		$expected_date     = new DateTime($txtEDD);
		
		$corrected_diff           = $expected_date->diff($current_date);
		$correctedAge     = $corrected_diff->y . " years " . $corrected_diff->m . " months " . $corrected_diff->d . " day(s)";
			
		// Parent Table Data
	$arrFields_Parent = array();
	$arrValues_Parent = array();
	
	$arrFields_Parent[] = 'mother_name';
	$arrValues_Parent[] = $txtMotherName;
	
	$arrFields_Parent[] = 'father_name';
	$arrValues_Parent[] = $txtFatherName;
	
	$arrFields_Parent[] = 'mother_age';
	$arrValues_Parent[] = $txtMotherAge;
		
	$arrFields_Parent[] = 'father_age';
	$arrValues_Parent[] = $txtFatherAge;
	
	$arrFields_Parent[] = 'primary_mobile_num';
	$arrValues_Parent[] = $txtMob;	
		
	$arrFields_Parent[] = 'email';
	$arrValues_Parent[] = $txtMail;
	
	$arrFields_Parent[] = 'address';
	$arrValues_Parent[] = $txtAddress;
	
	$arrFields_Parent[] = 'city';
	$arrValues_Parent[] = $txtLoc;
	
	$arrFields_Parent[] = 'state';
	$arrValues_Parent[] = $txtState;
	
	$arrFields_Parent[] = 'country';
	$arrValues_Parent[] = $txtCountry;
	
	$arrFields_Parent[] = 'phc_location';
	$arrValues_Parent[] = $txtPHCLocation;
	
	$arrFields_Parent[] = 'doc_id';
	$arrValues_Parent[] = $admin_id;
		
	$arrFields_Parent[] = 'f_sync'; //Forward Sync
	$arrValues_Parent[] = 1;
	
	$current_date = date("Y-m-d H:i:s");
	
	$arrFields_Parent[] = 'system_date';
	$arrValues_Parent[] = date('Y-m-d',strtotime($current_date));
	
	
	
	if($result == false)
	{
		$parentCreate	=	mysqlInsert('parents_tab',$arrFields_Parent,$arrValues_Parent);
		$pid			= $parentCreate;
	}
	else if($result == true)
	{
		$userupdate	=	mysqlUpdate('parents_tab',$arrFields_Parent,$arrValues_Parent, "parent_id = '". $result[0]['parent_id']."' ");
		$pid 		= 	$result[0]['parent_id'];
	}
		
	// Child Table Data
	$arrFields_Child = array();
	$arrValues_Child = array();
	
	if(!empty($patientid))
	{
		$arrFields_Child[] = 'patient_id';
		$arrValues_Child[] = $patientid;
	}
	if(!empty($admin_id))
	{
		$arrFields_Child[] = 'doc_id';
		$arrValues_Child[] = $admin_id;
	}
	
	$arrFields_Child[] = 'actual_age';
	$arrValues_Child[] = $actualAge;
	
	$arrFields_Child[] = 'edd';
	$arrValues_Child[] = $txtEDD;
	
	$arrFields_Child[] = 'vaccine_start_date';
	$arrValues_Child[] = $vaccine_start_date;
	
	$arrFields_Child[] = 'corrected_age';
	$arrValues_Child[] = $correctedAge;
	
	$arrFields_Child[] = 'birth_weight';
	$arrValues_Child[] = $weight;
	
	$arrFields_Child[] = 'birth_order';
	$arrValues_Child[] = $txtBirthOrder;
	
	$arrFields_Child[] = 'date_of_nicu_admit';
	$arrValues_Child[] = $txtAdmitDate;
	
	$arrFields_Child[] = 'date_of_discharge';
	$arrValues_Child[] = $txtDischargeDate;
	
	$arrFields_Child[] = 'f_sync';
	$arrValues_Child[] = 1;
	
	$arrFields_Child[] = 'creation_date';
	$arrValues_Child[] = $creation_date;
	
	$arrFields_Child[] = 'system_entry_date';
	$arrValues_Child[] = $current_date;
	
	$arrFields_Child[] = 'description';
	$arrValues_Child[] = $se_des;
	
	if(!empty($pid))
	{
		$arrFields_Child[] = 'parent_id';
		$arrValues_Child[] = $pid;
	}
	
	
	
	if(isset($_POST['save_patient']))
	{
		$childCreate=mysqlInsert('child_tab',$arrFields_Child,$arrValues_Child);
		$Childid= $childCreate;
		
		$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","1,11");
								//$getStartDate=date('Y-m-d',strtotime($vaccine_start_date));								
							  foreach($vaccineduration as $vaccinedurationList){
								if($vaccinedurationList['duartion_id']=="2"){
									//Add 6 weeks to DOB
									$add_days = 7*6;
									$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
								}
								else if($vaccinedurationList['duartion_id']=="3"){
									//Add 10 weeks to DOB
									$add_days = 7*10;
									$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
								}
								else if($vaccinedurationList['duartion_id']=="4"){
									//Add 14 weeks to DOB
									$add_days = 7*14;
									$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
								}
								else if($vaccinedurationList['duartion_id']=="5"){
									//Add 18 weeks to DOB
									$add_days = 7*18;
									$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
								}
								else if($vaccinedurationList['duartion_id']=="6"){
									$vaccine_date = date('Y-m-d', strtotime("+6 months", strtotime($vaccine_start_date)));
								}
								else if($vaccinedurationList['duartion_id']=="7"){
									$vaccine_date = date('Y-m-d', strtotime("+9 months", strtotime($vaccine_start_date)));
								}
								else if($vaccinedurationList['duartion_id']=="8"){
									$vaccine_date = date('Y-m-d', strtotime("+12 months", strtotime($vaccine_start_date)));
								}
								else if($vaccinedurationList['duartion_id']=="9"){
									$vaccine_date = date('Y-m-d', strtotime("+15 months", strtotime($vaccine_start_date)));
								}
								else if($vaccinedurationList['duartion_id']=="10"){
									$vaccine_date = date('Y-m-d', strtotime("+18 months", strtotime($vaccine_start_date)));
								}
								else if($vaccinedurationList['duartion_id']=="11"){
									$vaccine_date = date('Y-m-d', strtotime("+23 months", strtotime($vaccine_start_date)));
								}
								
								//Insert Vaccine notification table	
								if($vaccine_date!="0000-00-00"){
								$arrFields_Notify = array();
								$arrValues_Notify = array();
								
								$arrFields_Notify[] = 'vaccine_date';
								$arrValues_Notify[] = $vaccine_date;
								
								if(!empty($Childid))
								{
									$arrFields_Notify[] = 'child_id';
									$arrValues_Notify[] = $Childid;
								}
								
								$insertVaccineNotify=mysqlInsert('vaccine_notification',$arrFields_Notify,$arrValues_Notify);	
								}	
							  }
		}
		else if(isset($_POST['update_patient']))
		{
			$userupdate=mysqlUpdate('child_tab',$arrFields_Child,$arrValues_Child, "patient_id = '". $_POST['patient_id'] ."' ");
			$result1 = mysqlSelect('*','child_tab',"patient_id = '". $_POST['patient_id'] ."'");
			$Childid= $result1[0]['child_id'];
		
			mysqlDelete('vaccine_notification',"child_id='".$Childid."'");	
			//Update Next 10 vaccine dates
			$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","1,11");
								
			
			foreach($vaccineduration as $vaccinedurationList)
			{
				if($vaccinedurationList['duartion_id']=="2")
				{
					//Add 6 weeks to DOB
					$add_days = 7*6;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="3"){
					//Add 10 weeks to DOB
					$add_days = 7*10;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="4"){
					//Add 14 weeks to DOB
					$add_days = 7*14;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="5"){
					//Add 18 weeks to DOB
					$add_days = 7*18;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="6"){
					$vaccine_date = date('Y-m-d', strtotime("+6 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="7"){
					$vaccine_date = date('Y-m-d', strtotime("+9 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="8"){
					$vaccine_date = date('Y-m-d', strtotime("+12 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="9"){
					$vaccine_date = date('Y-m-d', strtotime("+15 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="10"){
					$vaccine_date = date('Y-m-d', strtotime("+18 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="11"){
					$vaccine_date = date('Y-m-d', strtotime("+23 months", strtotime($vaccine_start_date)));
				}
								
				//Insert Vaccine notification table	
				if($vaccine_date!="0000-00-00"){
				$arrFields_Notify = array();
				$arrValues_Notify = array();
				
				$arrFields_Notify[] = 'vaccine_date';
				$arrValues_Notify[] = $vaccine_date;
				if(!empty($Childid))
				{
					$arrFields_Notify[] = 'child_id';
					$arrValues_Notify[] = $Childid;
				}
				
				$insertVaccineNotify=mysqlInsert('vaccine_notification',$arrFields_Notify,$arrValues_Notify);	
				}	
			}
		}
		
			//UPLOAD COMPRESSED IMAGE
			if ($_FILES["txtPhoto"]["error"] > 0) {
        			$error = $_FILES["txtPhoto"]["error"];
    		} 
    		else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
			($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
			($_FILES["txtPhoto"]["type"] == "image/png") || 
			($_FILES["txtPhoto"]["type"] == "image/pjpeg")) 
			{
				$folder_name	=	"patientImage";
				$sub_folder		=	$patientid;
				$filename		=	$_FILES['txtPhoto']['name'];
				$file_url		=	$_FILES['txtPhoto']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			}
			else 
			{
        			$error = "Uploaded image should be jpg or gif or png";
    		}
		$response="updated";
		header("Location:".HOST_URL_PREMIUM."My-Patient-Details?p=".md5($patientid));
	}
	

?>