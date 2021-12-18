﻿<?php
 ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();
include('../send_mail_function.php');
include('../send_text_message.php');
require_once("../DigitalOceanSpaces/src/upload_function.php");

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


$doc_email             =addslashes($_POST['doc_email']);
$doc_Contact_num       =addslashes($_POST['Contact_num']);

if(isset($_POST['submit']))
{
	$doc_id =  addslashes($_POST['doc_id']);
	$doctor_registration = mysqlSelect("*","referal","ref_id='".$doc_id."'","","","");
	if(!empty($doctor_registration))
	{
	
		$doc_first_name        		=	addslashes($_POST['first_name']);//general information     inserted table " reffereal "
		$doc_middle_name       		=	addslashes($_POST['middle_name']);
		$doc_last_name        		=	addslashes($_POST['last_name']);
		$doc_name              		=	$doc_first_name." ".$doc_middle_name." ".$doc_last_name;
		$docgend               		=	$_POST['gender'];
		if($docgend=="")
		{
			$docgend=0;
		}
		$doc_dob              		 	=	$_POST['dob'];
		$doc_email            		 	=	addslashes($_POST['doc_email']);
		$doc_specialization   		 	=	addslashes($_POST['specialization']);
		$docexp       		   			=	$_POST['year_of_exp'];
		$doc_Consultation_lang		 	=	addslashes($_POST['Consultation_lang']);
		$doc_Contact_num      			=	$_POST['Contact_num'];
		$Country_code         			=	$_POST['Country_code'];
		$alt_Country_code     			=	$_POST['Alt_Country_code'];
		$doc_alt_Contact_num  			=	$_POST['alt_Contact_num'];
		$doc_address         			=	addslashes($_POST['address']);
		$doccity              			=	addslashes($_POST['city']);
		$docstate            			=	addslashes($_POST['doc_state']);
		$doccountry 		   			=	addslashes($_POST['doc_country']);
		$selected_country_id   			=	addslashes($_POST['selected_country_id']);
		$doc_Country_of_origin 			=	addslashes($_POST['country_of_origin']);
		$Area_of_interest               =	addslashes($_POST['Area_of_interest']); //Other Information
		$Professional_Contribution      =	addslashes($_POST['Professional_Contribution']);
		$Professional_Construction_file =	addslashes($_POST['Professional_Construction_file']);//file
		$Research_Details               =	addslashes($_POST['Research_Details']);
		$Research_Details_file          =	addslashes($_POST['Research_Details_file']);//file
		$Publications                   =	addslashes($_POST['Publications']);
		$Publications_file              =	addslashes($_POST['Publications_file']);
		$Password                       =	addslashes($_POST['Password']);
		$Confirm_Password               =	addslashes($_POST['Confirm_Password']);
		$passport_country 				= 	$_POST['passport_country'];
		$passport_num 					= 	$_POST['passport_num'];
		$consult_lang 					= 	$_POST['consult_lang'];
		$geo_latitude 					= 	$_POST['geo_latitude'];
		$geo_longitude 					= 	$_POST['geo_longitude'];
		
		
		
		$arrFields[] = 'ref_name';
		$arrValues[] = $doc_name;
		
		$arrFields[] = 'doc_gen';
		$arrValues[] = $docgend;
		
		$arrFields[] = 'doc_dob';
		$arrValues[] = $doc_dob;
		
		$arrFields[] = 'ref_mail';
		$arrValues[] = $doc_email ;
		
		$arrFields[] = 'doc_spec';
		$arrValues[] = $doc_specialization;
		
		$arrFields[] = 'ref_exp';
		$arrValues[] = $docexp;
		
		$arrFields[] = 'contact_num_extension';
		$arrValues[] = $Country_code;
		
		$arrFields[] = 'contact_num';
		$arrValues[] = $doc_Contact_num;
		
		$arrFields[] = 'secondary_contact_num_extension';
		$arrValues[] = $alt_Country_code;
		
		$arrFields[] = 'secondary_contact_num';
		$arrValues[] = $doc_alt_Contact_num;
		
		$arrFields[] = 'ref_address';
		$arrValues[] = $doc_address;
		
		$arrFields[] = 'doc_city';
		$arrValues[] = $doccity;
		
		$arrFields[] = 'doc_state';
		$arrValues[] = $docstate;
		
		$arrFields[] = 'doc_country';
		$arrValues[] = $doccountry;
		
		$arrFields[] = 'doc_country_id';
		$arrValues[] = $selected_country_id;
		
		$arrFields[] = 'country_of_origin';
		$arrValues[] = $doc_Country_of_origin;
	
		$arrFields[] = 'doc_interest';
		$arrValues[] = $Area_of_interest;
		
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $Professional_Contribution;
		
		$arrFields[] = 'doc_research';
		$arrValues[] = $Research_Details;
		
		$arrFields[] = 'doc_pub';
		$arrValues[] = $Publications;
	
		
		$arrFields[] = 'passport_num';
		$arrValues[] = $passport_num;
		
		$arrFields[] = 'passport_country';
		$arrValues[] = $passport_country;
		
		$arrFields[] = 'geo_latitude';
		$arrValues[] = $geo_latitude;
		
		$arrFields[] = 'geo_longitude';
		$arrValues[] = $geo_longitude;
		
		
		$docImage =	$_POST['txtPhoto'];
		$docImage =	$_FILES['txtPhoto']['name'];
		
		
		if(!empty($_FILES['txtPhoto']['name']))
		{
			$arrFields[] = 'doc_photo';
			$arrValues[] = $docImage;
		}
		
		if(!empty($_FILES['txtProfessional_Construction_file']['name']))
		{
			$arrFields[] = 'Professional_Construction_file';
			$arrValues[] = $_FILES['txtProfessional_Construction_file']['name'];
		}
		if(!empty($_FILES['txtResearch_Details_file']['name']))
		{
			$arrFields[] = 'Research_Details_file';
			$arrValues[] = $_FILES['txtResearch_Details_file']['name'];
		}
		if(!empty($_FILES['txtPublications_file']['name']))
		{
			$arrFields[] = 'Publications_file';
			$arrValues[] = $_FILES['txtPublications_file']['name'];
		}
		
		if(!empty($_FILES['txtpassport_file']['name']))
		{
			$arrFields[] = 'txtpassport_file';
			$arrValues[] = $_FILES['txtpassport_file']['name'];
		}
		
		
	
		$postResult = mysqlSelect("*","newcitylist","city_name LIKE '%".$doccity."%'","","","","");
		if(!empty($postResult)) 
		{
			$city_id = $postResult[0]['city_id'];
			
			if(empty($postResult[0]['latitude']) ||  empty($postResult[0]['longitude']))
			{
				
				$geo_latitude  = number_format($geo_latitude, 2);
				$geo_longitude = number_format($geo_longitude, 2);
				
				$arrFiedCity	=	array();
				$arrValueCity	=	array();
			
				$arrFiedCity[]	=	'latitude';
				$arrValueCity[]	=	$geo_latitude." N";
				
				$arrFiedCity[]	=	'longitude';
				$arrValueCity[]	=	$geo_longitude." E";
				
				
					
				$insert_city	=	mysqlUpdate('newcitylist',$arrFiedCity,$arrValueCity,"city_id='".$city_id."'");
				
			}
		}
		else
		{
			
			$arrFiedCity=array();
			$arrValueCity=array();
			
			$arrFiedCity[]	=	'city_name';
			$arrValueCity[]	=	$doccity;
			
			$arrFiedCity[]	=	'state';
			$arrValueCity[]	=	$docstate;
			
			$arrFiedCity[]	=	'latitude';
			$arrValueCity[]	=	$geo_latitude." N";
				
			$arrFiedCity[]	=	'longitude';
			$arrValueCity[]	=	$geo_longitude." E";
		
			$insert_city	=	mysqlInsert('newcitylist',$arrFiedCity,$arrValueCity);
			$city_id = $insert_city;
		}
		$arrFields[]	=	'doc_new_city';
		$arrValues[]	=	$city_id;
		
		$usercreate=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$doc_id."'");
		
		$companyid	=	413;	//default medisense compay id
		$hospid 	=	1093; //default hospital  ID 	
		
		while(list($key_invest, $value_invest) = each($_POST['Type_of_qualification']))//ACADEMIC INFORMATION 
		{
			
			$arrFieldAcd	=	array();
			$arrValueAcd	=	array();
			$id				=	$_POST['id'][$key_invest];
			$arrFieldAcd[]	=	'qualification_type';
			$arrValueAcd[]	=	$_POST['Type_of_qualification'][$key_invest];
			$arrFieldAcd[]	=	'country';
			$arrValueAcd[]	=	$_POST['acd_doc_country'][$key_invest];
			$arrFieldAcd[]	=	'city';
			$arrValueAcd[]	=	$_POST['acd_City'][$key_invest];
			$arrFieldAcd[]	=	'start_date';
			$arrValueAcd[]	=	$_POST['acd_Start_Date'][$key_invest];
			$arrFieldAcd[]	=	'end_date';
			$arrValueAcd[]	=	$_POST['acd_End_Date'][$key_invest];
			if($id==0)
			{
				$arrFieldAcd[]		=	'doc_id';
				$arrValueAcd[]		=	$doc_id;
				$insert_doctor_reg	= mysqlInsert('doctor_academics',$arrFieldAcd,$arrValueAcd);
				$reg_id= $insert_doctor_reg;
			}
			else
			{
				$reg_id			= $id;
				$update_doc_reg	= mysqlUpdate('doctor_academics',$arrFieldAcd,$arrValueAcd,"id='".$reg_id."'");
			}
			
		}
		
		while(list($key_invest, $value_invest) = each($_POST['Institution_Name']))//work history INFORMATION INSERT 
		{
			$arrFieldWrk	=	array();
			$arrValueWrk	=	array();
			$id				=	$_POST['wrk_id'][$key_invest];
			$arrFieldWrk[]	=	'Institution_Name';
			$arrValueWrk[]	=	$_POST['Institution_Name'][$key_invest];
			$arrFieldWrk[]	=	'work_type';
			$arrValueWrk[]	=	$_POST['work_type'][$key_invest];
			$arrFieldWrk[]	=	'Communication_Address';
			$arrValueWrk[]	=	$_POST['Communication_Address'][$key_invest];
			$arrFieldWrk[]	=	'Phone_Number';
			$arrValueWrk[]	=	$_POST['Phone_Number'][$key_invest];
			$arrFieldWrk[]	=	'phone_num_extension';
			$arrValueWrk[]	=	$_POST['Phone_Country_code'][$key_invest];
			$arrFieldWrk[]	=	'work_Start_Date';
			$arrValueWrk[]	=	$_POST['work_Start_Date'][$key_invest];
			$arrFieldWrk[]	=	'work_End_Date';
			$arrValueWrk[]	=	$_POST['work_End_Date'][$key_invest];
			
			if($id==0)
			{
				$arrFieldWrk[]	=	'doc_id';
				$arrValueWrk[]	=	$doc_id;
				$insert_workexp	= 	mysqlInsert('doc_work_exp',$arrFieldWrk,$arrValueWrk);
				$reg_id			=	$insert_workexp;
			}
			else
			{
				$reg_id	= $id;
				$update_workexp= mysqlUpdate('doc_work_exp',$arrFieldWrk,$arrValueWrk,"id='".$reg_id."'");
			}
			
		}
	
		$Medical_Council_reg   =	addslashes($_POST['Medical_Council_reg']); //Registration History
		$Reg_Num               =	$_POST['Reg_Num'];
		$Upload_Reg_cer        =	$_POST['txtUpload_Reg_cer']; //file
		
		while(list($key_invest, $value_invest) = each($_POST['Medical_Council_reg']))//Registration INFORMATION INSERT 
		{
			$fname1	=	time();	
			$arrFieldReg	=	array();
			$arrValueReg	=	array();
			
			$reg_det_id		=	$_POST['reg_det_id'][$key_invest];
			
			$arrFieldReg[]	=	'council_name';
			$arrValueReg[]	=	$_POST['Medical_Council_reg'][$key_invest];
			
			$arrFieldReg[]	=	'reg_num';
			$arrValueReg[]	=	$_POST['Reg_Num'][$key_invest];
			$reg_attachment = $fname1."_". basename($_FILES['txtUpload_Reg_cer']['name'][$key_invest]);
	
			
			if(!empty($_FILES["txtUpload_Reg_cer"][name][$key_invest]))
			{
				$arrFieldReg[]	=	'reg_certificate';
				$arrValueReg[]	=	$reg_attachment;
			}
			
			$arrFieldReg[]	=	'reg_date';
			$arrValueReg[]	=	$_POST['Registration_Date'][$key_invest];
			
			
			if($reg_det_id==0)
			{
				$arrFieldReg[]		=	'doc_id';
				$arrValueReg[]		=	$doc_id;
				$insert_doctor_reg	= 	mysqlInsert('doctor_registration_details',$arrFieldReg,$arrValueReg);
				$reg_id				=	$insert_doctor_reg;
			}
			else
			{
					$reg_id			= $reg_det_id;
					$update_doc_reg	= mysqlUpdate('doctor_registration_details',$arrFieldReg,$arrValueReg,"reg_det_id='".$reg_id."'");
			}
			if(!empty($_FILES["txtUpload_Reg_cer"]["name"]))
			{
				$folder_name	=	"DocCertificate";
				$sub_folder		=	$doc_id;
				$filename		=	$fname1."_".$_FILES['txtUpload_Reg_cer']['name'][$key_invest];
				$file_url		=	$_FILES['txtUpload_Reg_cer']['tmp_name'][$key_invest];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

				// $uploaddirectory = realpath("../DocCertificate");
				// mkdir("../DocCertificate/". "/" . $doc_id, 0777);
				// $uploaddir = $uploaddirectory."/".$doc_id;
				// $dotpos = strpos($_FILES['txtUpload_Reg_cer']['name'][$key_invest], '.');
				// $photo = $reg_attachment;
				// $uploadfile = $uploaddir . "/" . $photo;			
				// /* Moving uploaded file from temporary folder to desired folder. */
				// if(move_uploaded_file ($_FILES['txtUpload_Reg_cer']['tmp_name'][$key_invest], $uploadfile))
				// {
					//echo "File uploaded.";
				// } 
				// else 
				// {
					//echo "File cannot be uploaded";
				// }
				
				
			}			
		}
		
		//Create Doc Specialisation
		$doc_specialization    =($_POST['specialization']);
		if(!empty($_POST['specialization']))
		{
			$Spc_name =  mysqlDelete('doc_specialization',"doc_id='".$doc_id."'");
			foreach($_POST['specialization'] as $key => $value)
			{
						
				$arrFields_spe = array();
				$arrValues_spe = array();

				$arrFields_spe[] = 'doc_id';
				$arrValues_spe[] = $doc_id;

				$arrFields_spe[] = 'doc_type';
				$arrValues_spe[] = "1";
				
				$arrFields_spe[] = 'spec_id';
				$arrValues_spe[] = $value;
				
				$insert_spec	=	mysqlInsert('doc_specialization',$arrFields_spe,$arrValues_spe);
				
				
			}
					
		}
		
		//Create Consultation_lang
		$consult_lang = ($_POST['consult_lang']);
		if(!empty($_POST['consult_lang']))
		{
			$Spc_name	=  mysqlDelete('doctor_langauges',"doc_id='".$doc_id."'");
			foreach($_POST['consult_lang'] as $key => $values)
			{
					$arrFields_lang = array();
					$arrValues_lang = array();

					$arrFields_lang[] = 'doc_id';
					$arrValues_lang[] = $doc_id;

					$arrFields_lang[] = 'language_id';
					$arrValues_lang[] = $values;
					
					$insert_spec=mysqlInsert('doctor_langauges',$arrFields_lang,$arrValues_lang);
			}
		}
		
	
			if(!empty($_FILES["txtPhoto"]["name"]))
			{
				$folder_name	=	"Doc";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtPhoto']['name'];
				$file_url		=	$_FILES['txtPhoto']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
				
				// $uploaddirectory = realpath("../Doc");
				// $uploaddir = $uploaddirectory . "/" .$doc_id;
			 
				// /*Checking whether folder with category id already exist or not. */
				// if (file_exists($uploaddir)) 
				// {
					//echo "The file $uploaddir exists";
				// } 
				// else
				// {
					// $newdir = mkdir($uploaddirectory . "/" . $doc_id, 0777);
				// }
				// $url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];
				// $filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 40);
				// $buffer = file_get_contents($url);
			}
			
	
	
	//UPLOAD Professional_Construction_file CERTIFICATE
	
		if(!empty($_FILES["txtProfessional_Construction_file"]["name"]))
		{
			$folder_name	=	"Doc_Prof_Certificate";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtProfessional_Construction_file']['name'];
				$file_url		=	$_FILES['txtProfessional_Construction_file']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
				
			// $prof_cert_attachment =  basename($_FILES['txtProfessional_Construction_file']['name']);
			// $uploaddirectory = realpath("../Doc_Prof_Certificate");
			// mkdir("../Doc_Prof_Certificate/". "/" . $doc_id, 0777);
			// $uploaddir = $uploaddirectory."/".$doc_id;
			// $dotpos = strpos($_FILES['txtProfessional_Construction_file']['name'], '.');
			// $photo = $prof_cert_attachment;
			// $uploadfile = $uploaddir . "/" . $photo;			
			// /* Moving uploaded file from temporary folder to desired folder. */
			// if(move_uploaded_file ($_FILES['txtProfessional_Construction_file']['tmp_name'], $uploadfile))
			// {
			//echo "File uploaded.";
			// } 
			// else 
			// {
				//echo "File cannot be uploaded";
			// }
		}
	
	//UPLOAD Research_Details_file CERTIFICATE 
	
		if(!empty($_FILES["txtResearch_Details_file"]["name"]))
		{
			$folder_name	=	"Doc_Research_Certificate";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtResearch_Details_file']['name'];
				$file_url		=	$_FILES['txtResearch_Details_file']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
				
				
			// $research_attachment =  basename($_FILES['txtResearch_Details_file']['name']);
			// $uploaddirectory = realpath("../Doc_Research_Certificate");
			// mkdir("../Doc_Research_Certificate/". "/" . $doc_id, 0777);
			// $uploaddir = $uploaddirectory."/".$doc_id;
			// $dotpos = strpos($_FILES['txtResearch_Details_file']['name'], '.');
			// $photo = $research_attachment;
			// $uploadfile = $uploaddir . "/" . $photo;			
			// /* Moving uploaded file from temporary folder to desired folder. */
			// if(move_uploaded_file ($_FILES['txtResearch_Details_file']['tmp_name'], $uploadfile)) {
				//echo "File uploaded.";
			// } else {
				//echo "File cannot be uploaded";
			// }

		}

	//UPLOAD Publications_file CERTIFICATE 
	
	if(!empty($_FILES["txtPublications_file"]["name"]))
	{
		
		$folder_name	=	"Doc_Public_certificate";
		$sub_folder		=	$doc_id;
		$filename		=	$_FILES['txtPublications_file']['name'];
		$file_url		=	$_FILES['txtPublications_file']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
				
				
		// $publications_attachment =  basename($_FILES['txtPublications_file']['name']);
		// $uploaddirectory = realpath("../Doc_Public_certificate");
		// mkdir("../Doc_Public_certificate/". "/" . $doc_id, 0777);
		// $uploaddir = $uploaddirectory."/".$doc_id;
		// $dotpos = strpos($_FILES['txtPublications_file']['name'], '.');
		// $photo = $publications_attachment;
		// $uploadfile = $uploaddir . "/" . $photo;			
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtPublications_file']['tmp_name'], $uploadfile))
		// {
		//	echo "File uploaded.";
		// } 
		// else
		// {
		//	echo "File cannot be uploaded";
		// }

	}
	
	//UPLOAD Professional_Construction_file CERTIFICATE
	
	
		if(!empty($_FILES["txtpassport_file"]["name"]))
		{
			$folder_name	=	"Doc_passport_file";
			$sub_folder		=	$doc_id;
			$filename		=	$_FILES['txtpassport_file']['name'];
			$file_url		=	$_FILES['txtpassport_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
			// $passport_attachment =  basename($_FILES['txtpassport_file']['name']);
			// $uploaddirectory = realpath("../Doc_passport_file");
			// mkdir("../Doc_passport_file/". "/" . $doc_id, 0777);
			// $uploaddir = $uploaddirectory."/".$doc_id;
			// $dotpos = strpos($_FILES['txtpassport_file']['name'], '.');
			// $photo = $passport_attachment;
			// $uploadfile = $uploaddir . "/" . $photo;			
			// /* Moving uploaded file from temporary folder to desired folder. */
			// if(move_uploaded_file ($_FILES['txtpassport_file']['tmp_name'], $uploadfile)) {
			//	echo "File uploaded.";
			// } 
			// else 
			// {
				//echo "File cannot be uploaded";
			// }
		}
	
		//SEND DOCTOR PROFILE UPDATION EMAIL NOTIFICATION TO MEDISENSE PANEL
		$message=stripslashes("This is a confirmation that the profile of <b>".$doc_name."</b> has just been created on <b>".$Cur_Date."</b><br><br>Please check doctor profile");
		$dochistory="Doctor Name:".$doc_name."<br>Exp. : ".$docexp."<br>Mobile: ".$doccontact."<br>Email Id: ".$doc_email."<br>Communication Address: ".$dochosp."<br>City/State : ".$doccity.", ".$docstate.", ".$doccountry;
		$ccmail1="medisensebd@medisense.me";
		//$ccmail2="shashi@medisense.me";
		$url_page = 'med_new_ref_notification.php';
					$url = rawurlencode($url_page);
					$url .= "?refname=".urlencode($ref_name);
					$url .= "&message=".urlencode($message);
					$url .= "&reflink=".urlencode($dochistory);
					$url .= "&ccmail1=".urlencode($ccmail1);
					$url .= "&ccmail2=".urlencode($ccmail2);
					send_mail($url);
		
			//Send value to remote server
					$docweb="";
					$doccontribute="";
					$docresearch="";
					$docinpercharge="";
					$doconlinecharge="";
					$docconscharge="";
					$teleOpCond="";
					$telecontact="";
					$videoOpCond="";
					$videocontact="";
					$teleoptiming="";
					
					
					$url_page = 'get_new_refer_val.php';
					
					$url = HOST_HEALTH_URL."CRM/";
					$url .= rawurlencode($url_page);
					$post .= "&docid=" . urlencode($id);
					$post .= "&docname=" . urlencode($doc_name);
					$post .= "&docgend=" . urlencode($docgend);
					$post .= "&docage=" . urlencode($docage);
					$post .= "&doccity=" . urlencode($doccity);
					$post .= "&docstate=" . urlencode($docstate);
					$post .= "&doccountry=" . urlencode($doccountry);
					$post .= "&dochosp=" . urlencode($dochosp);
					//$post .= "&docspec=" . urlencode($docspec);
					$post .= "&docqual=" . urlencode($doctqual);
					$post .= "&docexp=" . urlencode($docexp);
					$post .= "&docmobile=" . urlencode($doccontact);
					$post .= "&docemail=" . urlencode($doc_email);
					$post .= "&docweb=" . urlencode($docweb);
					$post .= "&docinterest=" . urlencode($docexpert);
					$post .= "&doccontribute=" . urlencode($doccontribute);
					$post .= "&docresearch=" . urlencode($docresearch);
					$post .= "&docpublication=" . urlencode($Publications);
					$post .= "&docimage=" . urlencode($docImage);
					$post .= "&inopcost=" . urlencode($docinpercharge);
					$post .= "&onopcost=" . urlencode($doconlinecharge);
					$post .= "&conscharge=" . urlencode($docconscharge);
					$post .= "&docpasswd=" . urlencode($Password);
					$post .= "&timestamp=" . urlencode($Cur_Date);
					
					$post .= "&teleOpCond=" . urlencode($teleOpCond);
					$post .= "&telecontact=" . urlencode($telecontact);
					$post .= "&videoOpCond=" . urlencode($videoOpCond);
					$post .= "&videocontact=" . urlencode($videocontact);
					$post .= "&teleoptiming=" . urlencode($teleoptiming);
					
					$post .= "&hospid=" . urlencode($hospid);
					//$post .= "&hospname=" . urlencode($hospname);
					//$post .= "&dochospAddress=" . urlencode($dochospAddress);
					//$post .= "&hospphone=" . urlencode($hosp_phone);
					//$post .= "&hospemail=" . urlencode($hosp_email);
				
					$ch = curl_init (); // setup a curl
					
					curl_setopt($ch, CURLOPT_URL, $url); // set url to send to
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
					
						
					$output = curl_exec ($ch);
					// echo $output;
					// echo "output".$output;
					
					curl_close ( $ch );
					$sucessMessage="Updated Successfully";
					
					$respond=0;
					$new_id=$id;
					
					header('Location:Onboard-Doctor-details?respond='.$respond.'&p='.md5($doc_id));
					
	}
	else
	{
		$respond=1;
		header('Location:Onboard-Doctor-details?respond='.$respond.'&p='.md5($doc_id));
	}
					
	
	
}

?>