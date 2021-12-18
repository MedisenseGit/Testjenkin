<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../../classes/querymaker.class.php");
require_once("../../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();

$admin_id = 2030;
//$ccmail="medical@medisense.me";

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$time = date("d-m-Y")."-".time();

if(isset($_POST['add_home']))
{
	
	$txtDocName 	= addslashes($_POST['home_name']);
	$txtDesignation = addslashes($_POST['home_designation']);
	$homeimg 		= $time."-".basename($_FILES['txtPhoto']['name']);
    $arrFields_home = array();
	$arrValues_home = array();
	$arrFields_home[] = 'doc_id';
	$arrValues_home[] = $admin_id;
	$arrFields_home[] = 'webtemplate_id';
	$arrValues_home[] = "4";	// Template1
	
	$arrFields_home[] = 'home_name';
	$arrValues_home[] = $txtDocName;
	$arrFields_home[] = 'home_designation';
	$arrValues_home[] = $txtDesignation;
	$arrFields_home[] = 'home_image';
	$arrValues_home[] = $homeimg;
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_home,$arrValues_home);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Home page added Successfully";
		$action="1"; //1 for success
		
		$response="Home details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate4_details',$arrFields_home,$arrValues_home,"doc_id='".$admin_id."'");
		$response="Home details updated successfully !!!";
		$action="1"; //0 for Update
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	if(basename($_FILES['txtPhoto']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto']['name'];
		$file_url		=	$_FILES['txtPhoto']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php';</script>";

}
if(isset($_POST['add_about']))
{
	
	$aboutinfo 		= addslashes($_POST['about_info']);
	$aboutname 		= addslashes($_POST['about_name']);
	$aboutspecialization = addslashes($_POST['about_specialization']);
	$aboutexperience= addslashes($_POST['about_experience']);
	$aboutaddress 	= addslashes($_POST['about_address']);
	$signimg 		= $time."-".basename($_FILES['txtPhoto6']['name']);
	$ratingname1 	= addslashes($_POST['about_rating_name1']);
	$ratingvalue1 	= $_POST['about_rating_value1'];
	
	$ratingname2 	= addslashes($_POST['about_rating_name2']);
	$ratingvalue2 	= $_POST['about_rating_value2'];
	
	$ratingname3 	= addslashes($_POST['about_rating_name3']);
	$ratingvalue3 	= $_POST['about_rating_value3'];
	
	$ratingname4 	= addslashes($_POST['about_rating_name4']);
	$ratingvalue4 	= $_POST['about_rating_value4'];
	
	$ratingname5 	= addslashes($_POST['about_rating_name5']);
	$ratingvalue5 	= $_POST['about_rating_value5'];
	
	$ratingname6 	= addslashes($_POST['about_rating_name6']);
	$ratingvalue6 	= $_POST['about_rating_value6'];
	
	$ratingname7 	= addslashes($_POST['about_rating_name7']);
	$ratingvalue7 	= $_POST['about_rating_value7'];
	
	$ratingname8 	= addslashes($_POST['about_rating_name8']);
	$ratingvalue8 	= $_POST['about_rating_value8'];
	
	$ratingname9 	= addslashes($_POST['about_rating_name9']);
	$ratingvalue9 	= $_POST['about_rating_value9'];
	
	$ratingname10 	= addslashes($_POST['about_rating_name10']);
	$ratingvalue10 	= $_POST['about_rating_value10'];
	
	
    $arrFields_about = array();
	$arrValues_about = array();
	$arrFields_about[] = 'doc_id';
	$arrValues_about[] = $admin_id;
	$arrFields_about[] = 'webtemplate_id';
	$arrValues_about[] = "4";	// Template1
	$arrFields_about[] = 'doc_type';
	$arrValues_about[] = "1"; 
	$arrFields_about[] = 'about_info';
	$arrValues_about[] = $aboutinfo;
	$arrFields_about[] = 'about_name';
	$arrValues_about[] = $aboutname;
	$arrFields_about[] = 'about_specialization';
	$arrValues_about[] = $aboutspecialization;
	$arrFields_about[] = 'about_experience';
	$arrValues_about[] = $aboutexperience;
	$arrFields_about[] = 'about_address';
	$arrValues_about[] = $aboutaddress;
	$arrFields_about[] = 'about_signature';
	$arrValues_about[] = $signimg;
	$arrFields_about[] = 'about_rating_name1';
	$arrValues_about[] = $ratingname1;
	$arrFields_about[] = 'about_rating_value1';
	$arrValues_about[] = $ratingvalue1;
	
	$arrFields_about[] = 'about_rating_name2';
	$arrValues_about[] = $ratingname2;
	$arrFields_about[] = 'about_rating_value2';
	$arrValues_about[] = $ratingvalue2;
	
	$arrFields_about[] = 'about_rating_name3';
	$arrValues_about[] = $ratingname3;
	$arrFields_about[] = 'about_rating_value3';
	$arrValues_about[] = $ratingvalue3;
	
	$arrFields_about[] = 'about_rating_name4';
	$arrValues_about[] = $ratingname4;
	$arrFields_about[] = 'about_rating_value4';
	$arrValues_about[] = $ratingvalue4;
	
	$arrFields_about[] = 'about_rating_name5';
	$arrValues_about[] = $ratingname5;
	$arrFields_about[] = 'about_rating_value5';
	$arrValues_about[] = $ratingvalue5;
	
	$arrFields_about[] = 'about_rating_name6';
	$arrValues_about[] = $ratingname6;
	$arrFields_about[] = 'about_rating_value6';
	$arrValues_about[] = $ratingvalue6;
	
	$arrFields_about[] = 'about_rating_name7';
	$arrValues_about[] = $ratingname7;
	$arrFields_about[] = 'about_rating_value7';
	$arrValues_about[] = $ratingvalue7;
	
	$arrFields_about[] = 'about_rating_name8';
	$arrValues_about[] = $ratingname8;
	$arrFields_about[] = 'about_rating_value8';
	$arrValues_about[] = $ratingvalue8;
	
	$arrFields_about[] = 'about_rating_name9';
	$arrValues_about[] = $ratingname9;
	$arrFields_about[] = 'about_rating_value9';
	$arrValues_about[] = $ratingvalue9;
	
	$arrFields_about[] = 'about_rating_name10';
	$arrValues_about[] = $ratingname10;
	$arrFields_about[] = 'about_rating_value10';
	$arrValues_about[] = $ratingvalue10;
	
	
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate	=	mysqlInsert('webtemplate4_details',$arrFields_about,$arrValues_about);
		$detailID 	= 	$homecreate;  //Get doc web Id
	
		$msg	=	"About page added Successfully";
		$action	=	"1"; //1 for success
		
		$response="About details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate	=	mysqlUpdate('webtemplate4_details',$arrFields_about,$arrValues_about,"doc_id='".$admin_id."'");
		$response	=	"About details updated successfully !!!";
		$action		=	"1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	if(basename($_FILES['txtPhoto6']['name']!==""))
	{ 

		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto6']['name'];
		$file_url		=	$_FILES['txtPhoto6']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

		
	}
	 echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#about';</script>";

}
if(isset($_POST['add_experience']))
{
	$expinfo 	= addslashes($_POST['experience_info']);
	$expyear1 	= $_POST['experience_year1'];
	$expdesig1 	= addslashes($_POST['experience_title1']);
	$expdes1 	= $_POST['experience_subtitle1'];
	$expyear2 	= $_POST['experience_year2'];
	$expdesig2 	= addslashes($_POST['experience_title2']);
	$expdes2 	= $_POST['experience_subtitle2'];
	$expyear3 	= $_POST['experience_year3'];
	$expdesig3 	= addslashes($_POST['experience_title3']);
	$expdes3 	= $_POST['experience_subtitle3'];
	$expyear4 	= $_POST['experience_year4'];
	$expdesig4 	= addslashes($_POST['experience_title4']);
	$expdes4 	= $_POST['experience_subtitle4'];
	$expyear5 	= $_POST['experience_year5'];
	$expdesig5 	= addslashes($_POST['experience_title5']);
	$expdes5 	= $_POST['experience_subtitle5'];
	
	$expyear6 	= $_POST['experience_year6'];
	$expdesig6 	= addslashes($_POST['experience_title6']);
	$expdes6 	= $_POST['experience_subtitle6'];
	
	$arrFields_exp = array();
	$arrValues_exp = array();
	$arrFields_exp[] = 'doc_id';
	$arrValues_exp[] = $admin_id;
	$arrFields_exp[] = 'webtemplate_id';
	$arrValues_exp[] = "4";	// Template1
	$arrFields_exp[] = 'doc_type';
	$arrValues_exp[] = "1"; 
	$arrFields_exp[] = 'experience_info';
	$arrValues_exp[] = $expinfo;
	
	$arrFields_exp[] = 'experience_year1';
	$arrValues_exp[] = $expyear1;
	$arrFields_exp[] = 'experience_title1';
	$arrValues_exp[] = $expdesig1;
	$arrFields_exp[] = 'experience_subtitle1';
	$arrValues_exp[] = $expdes1;
	
	$arrFields_exp[] = 'experience_year2';
	$arrValues_exp[] = $expyear2;
	$arrFields_exp[] = 'experience_title2';
	$arrValues_exp[] = $expdesig2;
	$arrFields_exp[] = 'experience_subtitle2';
	$arrValues_exp[] = $expdes2;
	
	$arrFields_exp[] = 'experience_year3';
	$arrValues_exp[] = $expyear3;
	$arrFields_exp[] = 'experience_title3';
	$arrValues_exp[] = $expdesig3;
	$arrFields_exp[] = 'experience_subtitle3';
	$arrValues_exp[] = $expdes3;
	
	$arrFields_exp[] = 'experience_year4';
	$arrValues_exp[] = $expyear4;
	$arrFields_exp[] = 'experience_title4';
	$arrValues_exp[] = $expdesig4;
	$arrFields_exp[] = 'experience_subtitle4';
	$arrValues_exp[] = $expdes4;
	
	$arrFields_exp[] = 'experience_year5';
	$arrValues_exp[] = $expyear5;
	$arrFields_exp[] = 'experience_title5';
	$arrValues_exp[] = $expdesig5;
	$arrFields_exp[] = 'experience_subtitle5';
	$arrValues_exp[] = $expdes5;
	
	$arrFields_exp[] = 'experience_year6';
	$arrValues_exp[] = $expyear6;
	$arrFields_exp[] = 'experience_title6';
	$arrValues_exp[] = $expdesig6;
	$arrFields_exp[] = 'experience_subtitle6';
	$arrValues_exp[] = $expdes6;
	
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_exp,$arrValues_exp);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Experience page added Successfully";
		$action="1"; //1 for success
		
		$response="Experience details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate4_details',$arrFields_exp,$arrValues_exp,"doc_id='".$admin_id."'");
		$response="Experience details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#experiences';</script>";

}
if(isset($_POST['add_scount']))
{
	$expname1 	= addslashes($_POST['experience_name1']);
	$expvalue1 	= $_POST['experience_value1'];
	
	$expname2 	= addslashes($_POST['experience_name2']);
	$expvalue2 	= $_POST['experience_value2'];
	
	$expname3 	= addslashes($_POST['experience_name3']);
	$expvalue3 	= $_POST['experience_value3'];
	
	$expname4 	= addslashes($_POST['experience_name4']);
	$expvalue4 	= $_POST['experience_value4'];
	
	$arrFields_scount = array();
	$arrValues_scount = array();
	$arrFields_scount[] = 'doc_id';
	$arrValues_scount[] = $admin_id;
	$arrFields_scount[] = 'webtemplate_id';
	$arrValues_scount[] = "4";	// Template1
	$arrFields_scount[] = 'doc_type';
	$arrValues_scount[] = "1"; 
	$arrFields_scount[] = 'experience_name1';
	$arrValues_scount[] = $expname1;
	$arrFields_scount[] = 'experience_value1';
	$arrValues_scount[] = $expvalue1;
	
	$arrFields_scount[] = 'experience_name2';
	$arrValues_scount[] = $expname2;
	$arrFields_scount[] = 'experience_value2';
	$arrValues_scount[] = $expvalue2;
	
	$arrFields_scount[] = 'experience_name3';
	$arrValues_scount[] = $expname3;
	$arrFields_scount[] = 'experience_value3';
	$arrValues_scount[] = $expvalue3;
	
	$arrFields_scount[] = 'experience_name4';
	$arrValues_scount[] = $expname4;
	$arrFields_scount[] = 'experience_value4';
	$arrValues_scount[] = $expvalue4;
	
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_scount,$arrValues_scount);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Service counter section added Successfully";
		$action="1"; //1 for success
		
		$response="Service counter section details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate4_details',$arrFields_scount,$arrValues_scount,"doc_id='".$admin_id."'");
		$response="Service counter section details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#counter';</script>";

}
if(isset($_POST['add_service']))
{
	$serviceinfo 	= addslashes($_POST['service_info']);	
	$servicetitle1 	= addslashes($_POST['service_title1']);	
	$servicedes1 	= addslashes($_POST['service_description1']);	

	$servicetitle2 	= addslashes($_POST['service_title2']);	
	$servicedes2 	= addslashes($_POST['service_description2']);	

	$servicetitle3 	= addslashes($_POST['service_title3']);	
	$servicedes3 	= addslashes($_POST['service_description3']);	

	$bgimg = $time."-".basename($_FILES['txtPhoto7']['name']);
	$arrFields_service = array();
	$arrValues_service = array();
	$arrFields_service[] = 'doc_id';
	$arrValues_service[] = $admin_id;
	$arrFields_service[] = 'webtemplate_id';
	$arrValues_service[] = "4";	// Template1
	$arrFields_service[] = 'doc_type';
	$arrValues_service[] = "1"; 
	$arrFields_service[] = 'service_info';
	$arrValues_service[] = $serviceinfo;
	
	$arrFields_service[] = 'service_title1';
	$arrValues_service[] = $servicetitle1;
	$arrFields_service[] = 'service_description1';
	$arrValues_service[] = $servicedes1;
	
	$arrFields_service[] = 'service_title2';
	$arrValues_service[] = $servicetitle2;
	$arrFields_service[] = 'service_description2';
	$arrValues_service[] = $servicedes2;
	
	$arrFields_service[] = 'service_title3';
	$arrValues_service[] = $servicetitle3;
	$arrFields_service[] = 'service_description3';
	$arrValues_service[] = $servicedes3;
	
	$arrFields_service[] = 'service_image';
	$arrValues_service[] = $bgimg;
	
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_service,$arrValues_service);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Service page added Successfully";
		$action="1"; //1 for success
		
		$response="Service details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate4_details',$arrFields_service,$arrValues_service,"doc_id='".$admin_id."'");
		$response="Service details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto7']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto7']['name'];
		$file_url		=	$_FILES['txtPhoto7']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#services';</script>";

}
if(isset($_POST['add_project']))
{

	$projectinfo = addslashes($_POST['project_info']);	
	$projtitle1	 = addslashes($_POST['project_title1']);	
	$projdes1 	 = addslashes($_POST['project_description1']);	
	$projimg1 	 = $time."-".basename($_FILES['txtPhoto1']['name']);

	$projtitle2 = addslashes($_POST['project_title2']);	
	$projdes2 	= addslashes($_POST['project_description2']);	
	$projimg2 	= $time."-".basename($_FILES['txtPhoto2']['name']);

	$projtitle3 = addslashes($_POST['project_title3']);	
	$projdes3 	= addslashes($_POST['project_description3']);	
	$projimg3 	= $time."-".basename($_FILES['txtPhoto3']['name']);

	$projtitle4 = addslashes($_POST['project_title4']);	
	$projdes4 	= addslashes($_POST['project_description4']);	
	$projimg4 	= $time."-".basename($_FILES['txtPhoto4']['name']);

	$projtitle5 = addslashes($_POST['project_title5']);	
	$projdes5 	= addslashes($_POST['project_description5']);	
	$projimg5 	= $time."-".basename($_FILES['txtPhoto5']['name']);
	
    $arrFields_project = array();
    $arrValues_project = array();
	$arrFields_project[] = 'doc_id';
	$arrValues_project[] = $admin_id;
	$arrFields_project[] = 'webtemplate_id';
	$arrValues_project[] = "4";	// Template1
	$arrFields_project[] = 'doc_type';
	$arrValues_project[] = "1"; 
	$arrFields_project[] = 'project_info';
	$arrValues_project[] = $projectinfo;
	
	$arrFields_project[] = 'project_title1';
	$arrValues_project[] = $projtitle1;
	$arrFields_project[] = 'project_description1';
	$arrValues_project[] = $projdes1;
     $arrFields_project[] = 'project_img1';
	$arrValues_project[] = $projimg1;
	
	$arrFields_project[] = 'project_title2';
	$arrValues_project[] = $projtitle2;
	$arrFields_project[] = 'project_description2';
	$arrValues_project[] = $projdes2;
     $arrFields_project[] = 'project_img2';
	$arrValues_project[] = $projimg2;

	$arrFields_project[] = 'project_title3';
	$arrValues_project[] = $projtitle3;
	$arrFields_project[] = 'project_description3';
	$arrValues_project[] = $projdes3;
     $arrFields_project[] = 'project_img3';
	$arrValues_project[] = $projimg3;

	$arrFields_project[] = 'project_title4';
	$arrValues_project[] = $projtitle4;
	$arrFields_project[] = 'project_description4';
	$arrValues_project[] = $projdes4;
     $arrFields_project[] = 'project_img4';
	$arrValues_project[] = $projimg4;

	$arrFields_project[] = 'project_title5';
	$arrValues_project[] = $projtitle5;
	$arrFields_project[] = 'project_description5';
	$arrValues_project[] = $projdes5;
     $arrFields_project[] = 'project_img5';
	$arrValues_project[] = $projimg5;
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_project,$arrValues_project);
		$detailID =$homecreate;  //Get doc web Id
	
		$msg="Project page added Successfully";
		$action="1"; //1 for success
		
		$response="Project details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate4_details',$arrFields_project,$arrValues_project,"doc_id='".$admin_id."'");
		$response="Project details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto1']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto1']['name'];
		$file_url		=	$_FILES['txtPhoto1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	}
	
	if(basename($_FILES['txtPhoto2']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto2']['name'];
		$file_url		=	$_FILES['txtPhoto2']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	}
	
	if(basename($_FILES['txtPhoto3']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto3']['name'];
		$file_url		=	$_FILES['txtPhoto3']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

	}
	
	if(basename($_FILES['txtPhoto4']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto4']['name'];
		$file_url		=	$_FILES['txtPhoto4']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	
	}
	
	if(basename($_FILES['txtPhoto5']['name']!==""))
	{ 
		$folder_name	=	"theme4ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto5']['name'];
		$file_url		=	$_FILES['txtPhoto5']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#portfolio';</script>";

}
if(isset($_POST['add_contact']))
{
	$contactemail 		= addslashes($_POST['contact_email']);
	$contactphone 		= $_POST['contact_phone'];
	$contactfacebook 	= addslashes($_POST['contact_facebook']);
	$contacttwitter 	= addslashes($_POST['contact_twitter']);
	$contactlinkedin 	= addslashes($_POST['contact_linkedin']);
	$contactyoutube 	= addslashes($_POST['contact_youtube']);
	
	$arrFields_contact = array();
    $arrValues_contact = array();
	
	$arrFields_contact[] = 'doc_id';
	$arrValues_contact[] = $admin_id;
	$arrFields_contact[] = 'webtemplate_id';
	$arrValues_contact[] = "4";	// Template1
	$arrFields_contact[] = 'doc_type';
	$arrValues_contact[] = "1"; 
	$arrFields_contact[] = 'contact_email';
	$arrValues_contact[] = $contactemail;
	$arrFields_contact[] = 'contact_phone';
	$arrValues_contact[] = $contactphone;
	$arrFields_contact[] = 'contact_facebook';
	$arrValues_contact[] = $contactfacebook;
	$arrFields_contact[] = 'contact_twitter';
	$arrValues_contact[] = $contacttwitter;
	$arrFields_contact[] = 'contact_linkedin';
	$arrValues_contact[] = $contactlinkedin;
	$arrFields_contact[] = 'contact_youtube';
	$arrValues_contact[] = $contactyoutube;
	
	$get_home = mysqlSelect('*','webtemplate4_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate4_details',$arrFields_contact,$arrValues_contact);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Contact page added Successfully";
		$action="1"; //1 for success
		
		$response="Contact details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate	=	mysqlUpdate('webtemplate4_details',$arrFields_contact,$arrValues_contact,"doc_id='".$admin_id."'");
		$response	=	"Contact details updated successfully !!!";
		$action		=	"1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate4_deatil_id'];
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#contact';</script>";

}