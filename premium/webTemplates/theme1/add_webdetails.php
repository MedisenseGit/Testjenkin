<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();

$admin_id = 3058;
//$ccmail="medical@medisense.me";

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$time = date("d-m-Y")."-".time();


//ADD Home Page
if(isset($_POST['add_home'])){
	
	$txtDocName = addslashes($_POST['se_doc_name']);
	$txtDocDesignation = addslashes($_POST['se_doc_designation']);
	$home_pic1 = $time."-".basename($_FILES['txtPhoto']['name']);
	$home_pic2 = $time."-".basename($_FILES['txtPhoto1']['name']);
	$home_pic3 = $time."-".basename($_FILES['txtPhoto2']['name']);
	$arrFields_home = array();
	$arrValues_home = array();
	
	$arrFields_home[] = 'doc_id';
	$arrValues_home[] = $admin_id;
	$arrFields_home[] = 'webtemplate_id';
	$arrValues_home[] = "1";	// Template1
	$arrFields_home[] = 'doc_type';
	$arrValues_home[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	$arrFields_home[] = 'home_username';
	$arrValues_home[] = $txtDocName;
	$arrFields_home[] = 'home_designation';
	$arrValues_home[] = $txtDocDesignation;
	$arrFields_home[] = 'home_image1';
	$arrValues_home[] = $home_pic1;

					
	$get_home = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) {
		
		$homecreate=mysqlInsert('webtemplate1_details',$arrFields_home,$arrValues_home);
		$detailID = $homecreate; //Get doc web Id
		
		
	
		$msg="Home page added Successfully";
		$action="1"; //1 for success
		
		$response="home-success";
	}
	else {
		
			$homeUpdate=mysqlUpdate('webtemplate1_details',$arrFields_home,$arrValues_home,"doc_id='".$admin_id."'");
			$response="home-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate1_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto']['name'];
		$file_url		=	$_FILES['txtPhoto']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($home_pic1, '.');
		// $photo = $home_pic1;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	if(basename($_FILES['txtPhoto1']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto1']['name'];
		$file_url		=	$_FILES['txtPhoto1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($home_pic2, '.');
		// $photo = $home_pic2;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtPhoto1']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	if(basename($_FILES['txtPhoto2']['name']!==""))
	{
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto2']['name'];
		$file_url		=	$_FILES['txtPhoto2']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($home_pic3, '.');
		// $photo = $home_pic3;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtPhoto2']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	header("Location:index.php?response=".$response);
}

//ADD About Page
if(isset($_POST['add_about'])){
	
	$txtName 	= addslashes($_POST['about_name']);
	$txtEmail 	= addslashes($_POST['about_email']);
	$txtPhone 	= $_POST['about_phone'];
	$txtProfile = addslashes($_POST['about_profprofile']);
	$about_Signature = $time."-".basename($_FILES['txtSignature']['name']);
	$about_bg 	= $time."-".basename($_FILES['txtBgImage']['name']);
	
	$arrFields_about = array();
	$arrValues_about = array();
	
	$arrFields_about[] = 'doc_id';
	$arrValues_about[] = $admin_id;
	$arrFields_about[] = 'webtemplate_id';
	$arrValues_about[] = "1";	// Template1
	$arrFields_about[] = 'doc_type';
	$arrValues_about[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	$arrFields_about[] = 'about_name';
	$arrValues_about[] = $txtName;
	$arrFields_about[] = 'about_email';
	$arrValues_about[] = $txtEmail;
	$arrFields_about[] = 'about_phone';
	$arrValues_about[] = $txtPhone;
	$arrFields_about[] = 'about_profile';
	$arrValues_about[] = $txtProfile;
	$arrFields_about[] = 'about_signature';
	$arrValues_about[] = $about_Signature;
	$arrFields_about[] = 'about_background';
	$arrValues_about[] = $about_bg;

					
	$get_about = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_about) == 0)
	{
		
		$aboutcreate=mysqlInsert('webtemplate1_details',$arrFields_about,$arrValues_about);
		$detailID = $aboutcreate;
	
		$msg="About page added Successfully";
		$action="1"; //1 for success
		
		$response="about-success";
	}
	else 
	{
		
			$aboutUpdate=mysqlUpdate('webtemplate1_details',$arrFields_about,$arrValues_about,"doc_id='".$admin_id."' and doc_type='1'");
			$response="about-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_about[0]['webtemplate1_deatil_id'];
	
	}
	
	if(basename($_FILES['txtSignature']['name']!==""))
	{
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtSignature']['name'];
		$file_url		=	$_FILES['txtSignature']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($about_Signature, '.');
		// $photo = $about_Signature;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtSignature']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	if(basename($_FILES['txtBgImage']['name']!==""))
	{
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtBgImage']['name'];
		$file_url		=	$_FILES['txtBgImage']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($about_bg, '.');
		// $photo = $about_bg;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtBgImage']['tmp_name'], $uploadfile))
			// {
			//echo "File uploaded.";
			// } 
			// else 
			// {
					//echo "File cannot be uploaded";
			// }
	}
	
	header("Location:index.php?page=about&response=".$response);
}	

//ADD Specialities Page
if(isset($_POST['add_specialty'])){
	
	$txtSurgical = addslashes($_POST['spec_surgical']);
	$txtSurgPercent1 = $_POST['spec_surgical1_percent'];
	$txtSurgName1 = addslashes($_POST['spec_surgical1_name']);	
	$txtSurgPercent2 = $_POST['spec_surgical2_percent'];
	$txtSurgName2 = addslashes($_POST['spec_surgical2_name']);
	$txtSurgPercent3 = $_POST['spec_surgical3_percent'];
	$txtSurgName3 = addslashes($_POST['spec_surgery3_name']);	
	$txtSurgPercent4 = $_POST['spec_surgical4_percent'];
	$txtSurgName4 = addslashes($_POST['spec_surgery4_name']);	
	
	$txtSpecialities = addslashes($_POST['spec_specialities']);	
	$txtSpecialty1 = addslashes($_POST['specialty1']);
	$txtSpecialty2 = addslashes($_POST['specialty2']);
	$txtSpecialty3 = addslashes($_POST['specialty3']);	
	$txtSpecialty4 = addslashes($_POST['specialty4']);
	$txtSpecialty5 = addslashes($_POST['specialty5']);
	$txtSpecialty6 = addslashes($_POST['specialty6']);
	$txtSpecialty7 = addslashes($_POST['specialty7']);
	$txtSpecialty8 = addslashes($_POST['specialty8']);
	$txtSpecialty9 = addslashes($_POST['specialty9']);
	$txtSpecialty10 = addslashes($_POST['specialty10']);
	$txtSpecialty11 = addslashes($_POST['specialty11']);
	$txtSpecialty12 = addslashes($_POST['specialty12']);
	
	$txtPatDemography = addslashes($_POST['se_pat_demography']);	
	$txtPatDomestic = addslashes($_POST['se_pat_domestic']);	
	$txtPatInternational = addslashes($_POST['se_pat_international']);	
	$txtPatCommunityService = addslashes($_POST['se_pat_communityservice']);	
	
	$arrFields_specialty = array();
	$arrValues_specialty = array();
	
	$arrFields_specialty[] = 'doc_id';
	$arrValues_specialty[] = $admin_id;
	$arrFields_specialty[] = 'webtemplate_id';
	$arrValues_specialty[] = "1";	// Template1
	$arrFields_specialty[] = 'doc_type';
	$arrValues_specialty[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	$arrFields_specialty[] = 'spec_surgical';
	$arrValues_specialty[] = $txtSurgical;
	$arrFields_specialty[] = 'spec_surgery1_name';
	$arrValues_specialty[] = $txtSurgName1;
	$arrFields_specialty[] = 'spec_surgery1_percent';
	$arrValues_specialty[] = $txtSurgPercent1;
	$arrFields_specialty[] = 'spec_surgery2_name';
	$arrValues_specialty[] = $txtSurgName2;
	$arrFields_specialty[] = 'spec_surgery2_percent';
	$arrValues_specialty[] = $txtSurgPercent2;
	$arrFields_specialty[] = 'spec_surgery3_name';
	$arrValues_specialty[] = $txtSurgName3;
	$arrFields_specialty[] = 'spec_surgery3_percent';
	$arrValues_specialty[] = $txtSurgPercent3;
	$arrFields_specialty[] = 'spec_surgery4_name';
	$arrValues_specialty[] = $txtSurgName4;
	$arrFields_specialty[] = 'spec_surgery4_percent';
	$arrValues_specialty[] = $txtSurgPercent4;
	
	$arrFields_specialty[] = 'spec_specaialities';
	$arrValues_specialty[] = $txtSpecialities;
	$arrFields_specialty[] = 'spec_speciality1';
	$arrValues_specialty[] = $txtSpecialty1;
	$arrFields_specialty[] = 'spec_speciality2';
	$arrValues_specialty[] = $txtSpecialty2;
	$arrFields_specialty[] = 'spec_speciality3';
	$arrValues_specialty[] = $txtSpecialty3;
	$arrFields_specialty[] = 'spec_speciality4';
	$arrValues_specialty[] = $txtSpecialty4;
	$arrFields_specialty[] = 'spec_speciality5';
	$arrValues_specialty[] = $txtSpecialty5;
	$arrFields_specialty[] = 'spec_speciality6';
	$arrValues_specialty[] = $txtSpecialty6;
	$arrFields_specialty[] = 'spec_speciality7';
	$arrValues_specialty[] = $txtSpecialty7;
	$arrFields_specialty[] = 'spec_speciality8';
	$arrValues_specialty[] = $txtSpecialty8;
	$arrFields_specialty[] = 'spec_speciality9';
	$arrValues_specialty[] = $txtSpecialty9;
	$arrFields_specialty[] = 'spec_speciality10';
	$arrValues_specialty[] = $txtSpecialty10;
	$arrFields_specialty[] = 'spec_speciality11';
	$arrValues_specialty[] = $txtSpecialty11;
	$arrFields_specialty[] = 'spec_speciality12';
	$arrValues_specialty[] = $txtSpecialty12;
	
	$arrFields_specialty[] = 'spec_pat_demography';
	$arrValues_specialty[] = $txtPatDemography;
	$arrFields_specialty[] = 'spec_domestic_count';
	$arrValues_specialty[] = $txtPatDomestic;
	$arrFields_specialty[] = 'spec_international_count';
	$arrValues_specialty[] = $txtPatInternational;
	$arrFields_specialty[] = 'spec_cs_count';
	$arrValues_specialty[] = $txtPatCommunityService;

					
	$get_home = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) {
		
		$homecreate=mysqlInsert('webtemplate1_details',$arrFields_specialty,$arrValues_specialty);
		$detailID = $homecreate;
	
		$msg="Specialities added Successfully";
		$action="1"; //1 for success
		
		$response="spec-success";
	}
	else {
		
			$homeUpdate=mysqlUpdate('webtemplate1_details',$arrFields_specialty,$arrValues_specialty,"doc_id='".$admin_id."' and doc_type='1'");
			$response="spec-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate1_deatil_id'];
	}

	
	header("Location:index.php?page=skills&response=".$response);
}

//ADD Education Page
if(isset($_POST['add_education'])){
	
	$txtTitle = addslashes($_POST['edu_title']);
	
	$txtUniversity1 = addslashes($_POST['edu_university1']);	
	$txtStream1 = addslashes($_POST['edu_stream1']);
	$txtYear1 = $_POST['about_year1'];
	$txtAddress1 = addslashes($_POST['edu_address1']);	
	
	$txtUniversity2 = addslashes($_POST['edu_university2']);
	$txtStream2 = addslashes($_POST['edu_stream2']);
	$txtYear2 = $_POST['edu_year2'];
	$txtAddress2 = addslashes($_POST['edu_address2']);
	
	$txtUniversity3 = addslashes($_POST['edu_university3']);
	$txtStream3 = addslashes($_POST['edu_stream3']);
	$txtYear3 = $_POST['edu_year3'];
	$txtAddress3 = addslashes($_POST['edu_address3']);
	
	$txtUniversity4 = addslashes($_POST['edu_university4']);
	$txtStream4 = addslashes($_POST['edu_stream4']);
	$txtYear4 = $_POST['edu_year4'];
	$txtAddress4 = addslashes($_POST['edu_address4']);
	
	$txtUniversity5 = addslashes($_POST['edu_university5']);
	$txtStream5 = addslashes($_POST['edu_stream5']);
	$txtYear5 = $_POST['edu_year5'];
	$txtAddress5 = addslashes($_POST['edu_address5']);
	
	
	$arrFields_edu = array();
	$arrValues_edu = array();
	
	$arrFields_edu[] = 'doc_id';
	$arrValues_edu[] = $admin_id;
	$arrFields_edu[] = 'webtemplate_id';
	$arrValues_edu[] = "1";	// Template1
	$arrFields_edu[] = 'doc_type';
	$arrValues_edu[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	$arrFields_edu[] = 'edu_title';
	$arrValues_edu[] = $txtTitle;
	
	$arrFields_edu[] = 'edu_university1';
	$arrValues_edu[] = $txtUniversity1;
	$arrFields_edu[] = 'edu_stream1';
	$arrValues_edu[] = $txtStream1;
	$arrFields_edu[] = 'edu_year1';
	$arrValues_edu[] = $txtYear1;
	$arrFields_edu[] = 'edu_address1';
	$arrValues_edu[] = $txtAddress1;
	
	$arrFields_edu[] = 'edu_university2';
	$arrValues_edu[] = $txtUniversity2;
	$arrFields_edu[] = 'edu_stream2';
	$arrValues_edu[] = $txtStream2;
	$arrFields_edu[] = 'edu_year2';
	$arrValues_edu[] = $txtYear2;
	$arrFields_edu[] = 'edu_address2';
	$arrValues_edu[] = $txtAddress2;
	
	$arrFields_edu[] = 'edu_university3';
	$arrValues_edu[] = $txtUniversity3;
	$arrFields_edu[] = 'edu_stream3';
	$arrValues_edu[] = $txtStream3;
	$arrFields_edu[] = 'edu_year3';
	$arrValues_edu[] = $txtYear3;
	$arrFields_edu[] = 'edu_address3';
	$arrValues_edu[] = $txtAddress3;
	
	$arrFields_edu[] = 'edu_university4';
	$arrValues_edu[] = $txtUniversity4;
	$arrFields_edu[] = 'edu_stream4';
	$arrValues_edu[] = $txtStream4;
	$arrFields_edu[] = 'edu_year4';
	$arrValues_edu[] = $txtYear4;
	$arrFields_edu[] = 'edu_address4';
	$arrValues_edu[] = $txtAddress4;
	
	$arrFields_edu[] = 'edu_university5';
	$arrValues_edu[] = $txtUniversity5;
	$arrFields_edu[] = 'edu_stream5';
	$arrValues_edu[] = $txtStream5;
	$arrFields_edu[] = 'edu_year5';
	$arrValues_edu[] = $txtYear5;
	$arrFields_edu[] = 'edu_address5';
	$arrValues_edu[] = $txtAddress5;

					
	$get_home = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) {
		
		$homecreate=mysqlInsert('webtemplate1_details',$arrFields_edu,$arrValues_edu);
		$detailID = $homecreate;
	
		$msg="Specialities added Successfully";
		$action="1"; //1 for success
		
		$response="education-success";
	}
	else {
		
			$homeUpdate=mysqlUpdate('webtemplate1_details',$arrFields_edu,$arrValues_edu,"doc_id='".$admin_id."' and doc_type='1'");
			$response="education-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate1_deatil_id'];
	}

	
	header("Location:index.php?page=experience&response=".$response);
}

//ADD Awards Page
if(isset($_POST['add_awards'])){
	
	$txtTitle = addslashes($_POST['award_title']);
	$txtSubTitle = addslashes($_POST['award_subtitle']);
	
	$txtawardName1 = addslashes($_POST['award_name1']);
	$txtawardInfo1 = addslashes($_POST['awardinfo1']);
	
	$txtawardName2 = addslashes($_POST['award_name2']);
	$txtawardInfo2 = addslashes($_POST['awardinfo2']);
	
	$txtawardName3 = addslashes($_POST['award_name3']);
	$txtawardInfo3 = addslashes($_POST['awardinfo3']);
	
	$txtawardName4 = addslashes($_POST['award_name4']);
	$txtawardInfo4 = addslashes($_POST['awardinfo4']);
	
	$txtawardName5 = addslashes($_POST['award_name5']);
	$txtawardInfo5 = addslashes($_POST['awardinfo5']);
	
	$txtawardName6 = addslashes($_POST['award_name6']);
	$txtawardInfo6 = addslashes($_POST['awardinfo6']);
	
	$arrFields_awards = array();
	$arrValues_awards = array();
	
	$arrFields_awards[] = 'doc_id';
	$arrValues_awards[] = $admin_id;
	$arrFields_awards[] = 'webtemplate_id';
	$arrValues_awards[] = "1";	// Template1
	$arrFields_awards[] = 'doc_type';
	$arrValues_awards[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	$arrFields_awards[] = 'award_title';
	$arrValues_awards[] = $txtTitle;
	
	$arrFields_awards[] = 'award_subtitle';
	$arrValues_awards[] = $txtSubTitle;
	
	$arrFields_awards[] = 'award_name1';
	$arrValues_awards[] = $txtawardName1;
	$arrFields_awards[] = 'award_info1';
	$arrValues_awards[] = $txtawardInfo1;
	
	$arrFields_awards[] = 'award_name2';
	$arrValues_awards[] = $txtawardName2;
	$arrFields_awards[] = 'award_info2';
	$arrValues_awards[] = $txtawardInfo2;
	
	$arrFields_awards[] = 'award_name3';
	$arrValues_awards[] = $txtawardName3;
	$arrFields_awards[] = 'award_info3';
	$arrValues_awards[] = $txtawardInfo3;
	
	$arrFields_awards[] = 'award_name4';
	$arrValues_awards[] = $txtawardName4;
	$arrFields_awards[] = 'award_info4';
	$arrValues_awards[] = $txtawardInfo4;
	
	$arrFields_awards[] = 'award_name5';
	$arrValues_awards[] = $txtawardName5;
	$arrFields_awards[] = 'award_info5';
	$arrValues_awards[] = $txtawardInfo5;
	
	$arrFields_awards[] = 'award_name6';
	$arrValues_awards[] = $txtawardName6;
	$arrFields_awards[] = 'award_info6';
	$arrValues_awards[] = $txtawardInfo6;

					
	$get_award = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_award) == 0) {
		
		$awardcreate=mysqlInsert('webtemplate1_details',$arrFields_awards,$arrValues_awards);
		$detailID = $awardcreate;
	
		$msg="Specialities added Successfully";
		$action="1"; //1 for success
		
		$response="education-success";
	}
	else {
		
			$awardUpdate=mysqlUpdate('webtemplate1_details',$arrFields_awards,$arrValues_awards,"doc_id='".$admin_id."' and doc_type='1'");
			$response="education-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_award[0]['webtemplate1_deatil_id'];
	}

	
	header("Location:index.php?page=education&response=".$response);
}

//ADD Hospital Page
if(isset($_POST['add_hospital'])){
	
	$txtTitle = addslashes($_POST['hosp_title']);
	
	$txtHospTitle1 = addslashes($_POST['hosp_title1']);
	$txtHospSubTitle1 = addslashes($_POST['hosp_subtitle1']);
	$hosp_pic1 = $time."-".basename($_FILES['txtHospPhoto1']['name']);
	
	$txtHospTitle2 = addslashes($_POST['hosp_title2']);
	$txtHospSubTitle2 = addslashes($_POST['hosp_subtitle2']);
	$hosp_pic2 = $time."-".basename($_FILES['txtHospPhoto2']['name']);
	
	$txtHospTitle3 = addslashes($_POST['hosp_title3']);
	$txtHospSubTitle3 = addslashes($_POST['hosp_subtitle3']);
	$hosp_pic3 = $time."-".basename($_FILES['txtHospPhoto3']['name']);
	
	$txtHospTitle4 = addslashes($_POST['hosp_title4']);
	$txtHospSubTitle4 = addslashes($_POST['hosp_subtitle4']);
	$hosp_pic4 = $time."-".basename($_FILES['txtHospPhoto4']['name']);
	
	$txtHospTitle5 = addslashes($_POST['hosp_title5']);
	$txtHospSubTitle5 = addslashes($_POST['hosp_subtitle5']);
	$hosp_pic5 = $time."-".basename($_FILES['txtHospPhoto5']['name']);
	
	$arrFields_hosp = array();
	$arrValues_hosp = array();
	
	$arrFields_hosp[] = 'doc_id';
	$arrValues_hosp[] = $admin_id;
	$arrFields_hosp[] = 'webtemplate_id';
	$arrValues_hosp[] = "1";	// Template1
	$arrFields_hosp[] = 'doc_type';
	$arrValues_hosp[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	
	$arrFields_hosp[] = 'hospital_title';
	$arrValues_hosp[] = $txtTitle;
	
	
	$arrFields_hosp[] = 'hosp_photo1_title';
	$arrValues_hosp[] = $txtHospTitle1;
	$arrFields_hosp[] = 'hosp_photo1_subtitle';
	$arrValues_hosp[] = $txtHospSubTitle1;
	$arrFields_hosp[] = 'hosp_photo1_imag';
	$arrValues_hosp[] = $hosp_pic1;
	
	$arrFields_hosp[] = 'hosp_photo2_title';
	$arrValues_hosp[] = $txtHospTitle2;
	$arrFields_hosp[] = 'hosp_photo2_subtitle';
	$arrValues_hosp[] = $txtHospSubTitle2;
	$arrFields_hosp[] = 'hosp_photo2_img';
	$arrValues_hosp[] = $hosp_pic2;
	
	$arrFields_hosp[] = 'hosp_photo3_title';
	$arrValues_hosp[] = $txtHospTitle3;
	$arrFields_hosp[] = 'hosp_photo3_subtitle';
	$arrValues_hosp[] = $txtHospSubTitle3;
	$arrFields_hosp[] = 'hosp_photo3_img';
	$arrValues_hosp[] = $hosp_pic3;
	
	$arrFields_hosp[] = 'hosp_photo4_title';
	$arrValues_hosp[] = $txtHospTitle4;
	$arrFields_hosp[] = 'hosp_photo4_subtitle';
	$arrValues_hosp[] = $txtHospSubTitle4;
	$arrFields_hosp[] = 'hosp_photo4_img';
	$arrValues_hosp[] = $hosp_pic4;
	
	$arrFields_hosp[] = 'hosp_photo5_title';
	$arrValues_hosp[] = $txtHospTitle5;
	$arrFields_hosp[] = 'hosp_photo5_subtitle';
	$arrValues_hosp[] = $txtHospSubTitle5;
	$arrFields_hosp[] = 'hosp_photo5_img';
	$arrValues_hosp[] = $hosp_pic5;

					
	$get_hospital = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."'");
	if(COUNT($get_hospital) == 0) {
		
		$homecreate=mysqlInsert('webtemplate1_details',$arrFields_hosp,$arrValues_hosp);
		$detailID = $homecreate;
	
		$msg="Hospital page added Successfully";
		$action="1"; //1 for success
		
		$response="hospital-success";
	}
	else {
		
			$homeUpdate=mysqlUpdate('webtemplate1_details',$arrFields_hosp,$arrValues_hosp,"doc_id='".$admin_id."'");
			$response="hospital-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_hospital[0]['webtemplate1_deatil_id'];
	}
	
	if(basename($_FILES['txtHospPhoto1']['name']!==""))
	{
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtHospPhoto1']['name'];
		$file_url		=	$_FILES['txtHospPhoto1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($hosp_pic1, '.');
		// $photo = $hosp_pic1;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtHospPhoto1']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	if(basename($_FILES['txtHospPhoto2']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtHospPhoto2']['name'];
		$file_url		=	$_FILES['txtHospPhoto2']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($hosp_pic2, '.');
		// $photo = $hosp_pic2;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtHospPhoto2']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	if(basename($_FILES['txtHospPhoto3']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtHospPhoto3']['name'];
		$file_url		=	$_FILES['txtHospPhoto3']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($hosp_pic3, '.');
		// $photo = $hosp_pic3;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtHospPhoto3']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	if(basename($_FILES['txtHospPhoto4']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtHospPhoto4']['name'];
		$file_url		=	$_FILES['txtHospPhoto4']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($hosp_pic4, '.');
		// $photo = $hosp_pic4;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtHospPhoto4']['tmp_name'], $uploadfile)) {
		//	echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	if(basename($_FILES['txtHospPhoto5']['name']!==""))
	{ 
		$folder_name	=	"theme1ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtHospPhoto5']['name'];
		$file_url		=	$_FILES['txtHospPhoto5']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		// $uploaddirectory = realpath("theme1ImageAttach");
		// mkdir("theme1ImageAttach/". "/" . $detailID, 0777);
		// $uploaddir = $uploaddirectory."/".$detailID;
		// $dotpos = strpos($hosp_pic5, '.');
		// $photo = $hosp_pic5;
		// $uploadfile = $uploaddir . "/" . $photo;			
				
		// /* Moving uploaded file from temporary folder to desired folder. */
		// if(move_uploaded_file ($_FILES['txtHospPhoto5']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			// } else {
					//echo "File cannot be uploaded";
			// }
	}
	
	header("Location:index.php?page=portfolio&response=".$response);
}

//ADD Contact Page
if(isset($_POST['add_contact'])){
	
	$txtAddressInfo = addslashes($_POST['contact_addressinfo']);
	$txtName 		= addslashes($_POST['contact_name']);
	$txtAddress1 	= addslashes($_POST['contact_address_line1']);
	$txtAddress2 	= addslashes($_POST['contact_address_line2']);
	$txtAddress3 	= addslashes($_POST['contact_address_line3']);
	$txtPhone 		= $_POST['contact_phone'];
	$txtEmail 		= addslashes($_POST['contact_email']);
	$txtSocialNetInfo = addslashes($_POST['contact_socialnetIfo']);
	$txtFacebook 	= addslashes($_POST['contact_facebook']);
	$txtTwitter 	= addslashes($_POST['contact_twitter']);
	$txtYoutube 	= addslashes($_POST['contact_youtube']);
	$txtGPlus 		= addslashes($_POST['contact_gplus']);
	$txtLinkedin 	= addslashes($_POST['contact_linkedin']);
	$txtLatitude 	= $_POST['lat_value'];
	$txtLongitude 	= $_POST['long_value'];
	
	$arrFields_contact = array();
	$arrValues_contact = array();
	
	$arrFields_contact[] = 'doc_id';
	$arrValues_contact[] = $admin_id;
	$arrFields_contact[] = 'webtemplate_id';
	$arrValues_contact[] = "1";	// Template1
	$arrFields_contact[] = 'doc_type';
	$arrValues_contact[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	
	$arrFields_contact[] = 'contact_address_info';
	$arrValues_contact[] = $txtAddressInfo;
	$arrFields_contact[] = 'contact_name';
	$arrValues_contact[] = $txtName;
	$arrFields_contact[] = 'contact_add_line1';
	$arrValues_contact[] = $txtAddress1;
	$arrFields_contact[] = 'contact_add_line2';
	$arrValues_contact[] = $txtAddress2;
	$arrFields_contact[] = 'contact_add_line3';
	$arrValues_contact[] = $txtAddress3;
	$arrFields_contact[] = 'contact_phone';
	$arrValues_contact[] = $txtPhone;
	$arrFields_contact[] = 'contact_email';
	$arrValues_contact[] = $txtEmail;
	$arrFields_contact[] = 'contact_social_network';
	$arrValues_contact[] = $txtSocialNetInfo;
	$arrFields_contact[] = 'contact_facebook';
	$arrValues_contact[] = $txtFacebook;
	$arrFields_contact[] = 'contact_twitter';
	$arrValues_contact[] = $txtTwitter;
	$arrFields_contact[] = 'contact_youtube';
	$arrValues_contact[] = $txtYoutube;
	$arrFields_contact[] = 'contact_gplus';
	$arrValues_contact[] = $txtGPlus;
	$arrFields_contact[] = 'contact_linkedin';
	$arrValues_contact[] = $txtLinkedin;
	$arrFields_contact[] = 'contact_latitude';
	$arrValues_contact[] = $txtLatitude;
	$arrFields_contact[] = 'contact_longitude';
	$arrValues_contact[] = $txtLongitude;

					
	$get_contact = mysqlSelect('*','webtemplate1_details',"doc_id='".$admin_id."'");
	if(COUNT($get_contact) == 0) {
		
		$contactcreate	=	mysqlInsert('webtemplate1_details',$arrFields_contact,$arrValues_contact);
		$detailID 		=	$contactcreate;
	
		$msg="Contact page added Successfully";
		$action="1"; //1 for success
		
		$response="contact-success";
	}
	else {
		
			$contactUpdate=mysqlUpdate('webtemplate1_details',$arrFields_contact,$arrValues_contact,"doc_id='".$admin_id."'");
			$response="contact-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_contact[0]['webtemplate1_deatil_id'];
	}
	
	header("Location:index.php?page=contact&response=".$response);
}
?>