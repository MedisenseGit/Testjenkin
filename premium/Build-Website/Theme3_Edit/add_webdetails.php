<?php
ob_start();
session_start();
error_reporting(0);  


require_once("../../classes/querymaker.class.php");
require_once("../../DigitalOceanSpaces/src/upload_function.php");

//$objQuery = new CLSQueryMaker();

$admin_id = 178;
//$ccmail="medical@medisense.me";

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$time = date("d-m-Y")."-".time();

if(isset($_POST['add_home']))
{
	
	$txtDocName 		= addslashes($_POST['home_name']);
	$txtDesignation 	= addslashes($_POST['home_designation']);
	$txtCompany 		= addslashes($_POST['home_company']);
	$arrFields_home 	= array();
	$arrValues_home 	= array();
	$arrFields_home[] 	= 'doc_id';
	$arrValues_home[] 	= $admin_id;
	$arrFields_home[] 	= 'webtemplate_id';
	$arrValues_home[]	= "3";	// Template1
	$arrFields_home[] 	= 'doc_type';
	$arrValues_home[] 	= "1"; 
	$arrFields_home[]	= 'home_name';
	$arrValues_home[]	= $txtDocName;
	$arrFields_home[] 	= 'home_designation';
	$arrValues_home[] 	= $txtDesignation;
	$arrFields_home[] 	= 'home_company';
	$arrValues_home[] 	= $txtCompany;
	$get_home 	= mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate3_details',$arrFields_home,$arrValues_home);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Home page added Successfully";
		$action="1"; //1 for success
		
		$response="Home details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate3_details',$arrFields_home,$arrValues_home,"doc_id='".$admin_id."'");
		$response="Home details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate3_deatil_id'];
	}
	
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php';</script>";

}
if(isset($_POST['add_about']))
{
	$aboutname 		= addslashes($_POST['about_name']);
	$specialization = addslashes($_POST['about_specialization']);
	$address 		= addslashes($_POST['about_address']);
	$experience 	= addslashes($_POST['about_experience']);
	$aboutinfo 		= addslashes($_POST['about_info']);
	$aboutimg 		= $time."-".basename($_FILES['txtPhoto']['name']);
	$arrFields_about = array();
	$arrValues_about = array();
	$arrFields_about[] = 'doc_id';
	$arrValues_about[] = $admin_id;
	$arrFields_about[] = 'webtemplate_id';
	$arrValues_about[] = "3";	// Template1
	$arrFields_about[] = 'doc_type';
	$arrValues_about[] = "1"; 
	$arrFields_about[] = 'about_name';
	$arrValues_about[] = $aboutname;
	$arrFields_about[] = 'about_specialization';
	$arrValues_about[] = $specialization;
	$arrFields_about[] = 'about_address';
	$arrValues_about[] = $address;
	$arrFields_about[] = 'about_experience';
	$arrValues_about[] = $experience;
	$arrFields_about[] = 'about_info';
	$arrValues_about[] = $aboutinfo;
	$arrFields_about[] = 'about_img';
	$arrValues_about[] = $aboutimg ;
	$get_home = mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate	=	mysqlInsert('webtemplate3_details',$arrFields_about,$arrValues_about);
		$detailID 	= 	$homecreate;  //Get doc web Id
		$msg		=	"About page added Successfully";
		$action		=	"1"; //1 for success
		
		$response="About details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate3_details',$arrFields_about,$arrValues_about,"doc_id='".$admin_id."'");
		$response="About details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate3_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto']['name']!==""))
	{ 
		$folder_name	=	"theme3ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto']['name'];
		$file_url		=	$_FILES['txtPhoto']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#about';</script>";
	// header("Location:index.php#about#res=0");
}
if(isset($_POST['add_skills']))
{
	$eduyear1 	= $_POST['skills_edu_year1'];
	$edustream1 = addslashes($_POST['skills_edu_stream1']);
	$edudes1 	= addslashes($_POST['skills_edu_description1']); 
	$eduyear2 	= $_POST['skills_edu_year2'];
	$edustream2 = addslashes($_POST['skills_edu_stream2']);
	$edudes2 	= addslashes($_POST['skills_edu_description2']); 
	$eduyear3 	= $_POST['skills_edu_year3'];
	$edustream3 = addslashes($_POST['skills_edu_stream3']);
	$edudes3 	= addslashes($_POST['skills_edu_description3']);
	$expyear1 	= $_POST['skills_exp_year1'];
	$expdesignation1 = addslashes($_POST['skills_exp_designtaion1']);
	$expdes1 	= addslashes($_POST['skills_exp_description1']); 
	$expyear2 	= $_POST['skills_exp_year2'];
	$expdesignation2 = addslashes($_POST['skills_exp_designtaion2']);
	$expdes2 	= addslashes($_POST['skills_exp_description2']); 
	$expyear3 	= $_POST['skills_exp_year3'];
	$expdesignation3 = addslashes($_POST['skills_exp_designtaion3']);
	$expdes3 	= addslashes($_POST['skills_exp_description3']); 
	$ratingtitle1 = addslashes($_POST['skills_rating_title1']); 
	$ratingvalue1 = $_POST['skills_rating_value1']; 
	$ratingtitle2 = addslashes($_POST['skills_rating_title2']); 
	$ratingvalue2 = $_POST['skills_rating_value2']; 
	$ratingtitle3 = addslashes($_POST['skills_rating_title3']); 
	$ratingvalue3 = $_POST['skills_rating_value3']; 
	$ratingtitle4 = addslashes($_POST['skills_rating_title4']); 
	$ratingvalue4 = $_POST['skills_rating_value4'];  
	
	$arrFields_skills = array();
	$arrValues_skills = array();
	
	
	
	$arrFields_skills[] = 'doc_id';
	$arrValues_skills[] = $admin_id;
	$arrFields_skills[] = 'webtemplate_id';
	$arrValues_skills[] = "3";	// Template1
	$arrFields_skills[] = 'doc_type';
	$arrValues_skills[] = "1"; 
	$arrFields_skills[] = 'skills_edu_year1';
	$arrValues_skills[] = $eduyear1;
	$arrFields_skills[] = 'skills_edu_stream1';
	$arrValues_skills[] = $edustream1;
	$arrFields_skills[] = 'skills_edu_description1';
	$arrValues_skills[] =  $edudes1;

	$arrFields_skills[] = 'skills_edu_year2';
	$arrValues_skills[] = $eduyear2;
	$arrFields_skills[] = 'skills_edu_stream2';
	$arrValues_skills[] = $edustream2;
	$arrFields_skills[] = 'skills_edu_description2';
	$arrValues_skills[] =  $edudes2;

	$arrFields_skills[] = 'skills_edu_year3';
	$arrValues_skills[] = $eduyear3;
	$arrFields_skills[] = 'skills_edu_stream3';
	$arrValues_skills[] = $edustream3;
	$arrFields_skills[] = 'skills_edu_description3';
	$arrValues_skills[] =  $edudes3;
	
	$arrFields_skills[] = 'skills_exp_year1';
	$arrValues_skills[] = $expyear1;
	$arrFields_skills[] = 'skills_exp_designtaion1';
	$arrValues_skills[] = $expdesignation1;
	$arrFields_skills[] = 'skills_exp_description1';
	$arrValues_skills[] = $expdes1;
	
	$arrFields_skills[] = 'skills_exp_year2';
	$arrValues_skills[] = $expyear2;
	$arrFields_skills[] = 'skills_exp_designtaion2';
	$arrValues_skills[] = $expdesignation2;
	$arrFields_skills[] = 'skills_exp_description2';
	$arrValues_skills[] = $expdes2;
	
	$arrFields_skills[] = 'skills_exp_year3';
	$arrValues_skills[] = $expyear3;
	$arrFields_skills[] = 'skills_exp_designtaion3';
	$arrValues_skills[] = $expdesignation3;
	$arrFields_skills[] = 'skills_exp_description3';
	$arrValues_skills[] = $expdes3;
	 
	$arrFields_skills[] = 'skills_rating_title1';
	$arrValues_skills[] = $ratingtitle1;
	$arrFields_skills[] = 'skills_rating_value1';
	$arrValues_skills[] = $ratingvalue1;

	$arrFields_skills[] = 'skills_rating_title2';
	$arrValues_skills[] = $ratingtitle2;
	$arrFields_skills[] = 'skills_rating_value2';
	$arrValues_skills[] = $ratingvalue2;

	$arrFields_skills[] = 'skills_rating_title3';
	$arrValues_skills[] = $ratingtitle3;
	$arrFields_skills[] = 'skills_rating_value3';
	$arrValues_skills[] = $ratingvalue3;
	
	$arrFields_skills[] = 'skills_rating_title4';
	$arrValues_skills[] = $ratingtitle4;
	$arrFields_skills[] = 'skills_rating_value4';
	$arrValues_skills[] = $ratingvalue4;
	 
	$get_home = mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate	=	mysqlInsert('webtemplate3_details',$arrFields_skills,$arrValues_skills);
		$detailID 	= $homecreate;  //Get doc web Id
		$msg		=	"Skills page added Successfully";
		$action		=	"1"; //1 for success
		$response	=	"Skills details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate	=	mysqlUpdate('webtemplate3_details',$arrFields_skills,$arrValues_skills,"doc_id='".$admin_id."'");
		$response	=	"Skills details updated successfully !!!";
		$action		=	"1"; //0 for Update
		$detailID 	= 	$get_home[0]['webtemplate3_deatil_id'];
	}
	
	
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#resume';</script>";
	// header("Location:index.php#about#res=0");
}

if(isset($_POST['add_service']))
{
	$servicetitle1	= addslashes($_POST['service_title1']);
	$servicedes1 	= addslashes($_POST['service_description1']);
	$servicetitle2 	= addslashes($_POST['service_title2']);
	$servicedes2 	= addslashes($_POST['service_description2']);
	$servicetitle3 	= addslashes($_POST['service_title3']);
	$servicedes3 	= addslashes($_POST['service_description3']);
	$servicetitle4 	= addslashes($_POST['service_title4']);
	$servicedes4 	= addslashes($_POST['service_description4']);
	$servicetitle5 	= addslashes($_POST['service_title5']);
	$servicedes5 	= addslashes($_POST['service_description5']);
	$servicetitle6	= addslashes($_POST['service_title6']);
	$servicedes6	= addslashes($_POST['service_description6']);
	$servicevalue1 	= $_POST['service_value1'];
	$servicetext1 	= addslashes($_POST['service_text1']);
	$servicevalue2 	= $_POST['service_value2'];
	$servicetext2 	= addslashes($_POST['service_text2']);
	$servicevalue3 	= $_POST['service_value3'];
	$servicetext3	= addslashes($_POST['service_text3']);
	$servicevalue4 	= $_POST['service_value4'];
	$servicetext4 	= addslashes($_POST['service_text4']);
	
	$arrFields_service = array();
	$arrValues_service = array();
	$arrFields_service[] = 'doc_id';
	$arrValues_service[] = $admin_id;
	$arrFields_service[] = 'webtemplate_id';
	$arrValues_service[] = "3";	// Template1
	$arrFields_service[] = 'doc_type';
	$arrValues_service[] = "1";
	
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
	
	$arrFields_service[] = 'service_title4';
	$arrValues_service[] = $servicetitle4;
	$arrFields_service[] = 'service_description4';
	$arrValues_service[] = $servicedes4;
	
	$arrFields_service[] = 'service_title5';
	$arrValues_service[] = $servicetitle5;
	$arrFields_service[] = 'service_description5';
	$arrValues_service[] = $servicedes5;
	
	$arrFields_service[] = 'service_title6';
	$arrValues_service[] = $servicetitle6;
	$arrFields_service[] = 'service_description6';
	$arrValues_service[] = $servicedes6;
	
	$arrFields_service[] = 'service_value1';
	$arrValues_service[] = $servicevalue1;
	$arrFields_service[] = 'service_text1';
	$arrValues_service[] = $servicetext1;
	
	$arrFields_service[] = 'service_value2';
	$arrValues_service[] = $servicevalue2;
	$arrFields_service[] = 'service_text2';
	$arrValues_service[] = $servicetext2;
	
	$arrFields_service[] = 'service_value3';
	$arrValues_service[] = $servicevalue3;
	$arrFields_service[] = 'service_text3';
	$arrValues_service[] = $servicetext3;
	
	$arrFields_service[] = 'service_value4';
	$arrValues_service[] = $servicevalue4;
	$arrFields_service[] = 'service_text4';
	$arrValues_service[] = $servicetext4;
	
	$get_home = mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate3_details',$arrFields_service,$arrValues_service);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Service page added Successfully";
		$action="1"; //1 for success
		
		$response="Service details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate3_details',$arrFields_service,$arrValues_service,"doc_id='".$admin_id."'");
		$response="Service details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate3_deatil_id'];
	}
	
	echo "<script type='text/javascript'>alert(' $response'); location.href='index.php#service';</script>";
	// header("Location:index.php#about#res=0");
}
if(isset($_POST['add_singleproject']))
{	

	$id 		= $_POST['project_id'];
	$projtitle 	= addslashes($_POST['project_title']);
	$img 		= $time."-".basename($_FILES['txtPhoto1']['name']);	
	$projdes 	= addslashes($_POST['project_description']);	

	$arrFields_project = array();
	$arrValues_project = array();
	
	$arrFields_project[] = 'doc_id';
	$arrValues_project[] = $admin_id;
	$arrFields_project[] = 'webtemplate_id';
	$arrValues_project[] = "3";	// Template1
	$arrFields_project[] = 'doc_type';
	$arrValues_project[] = "1";
	
if($id==1)
{
	$arrFields_project[] = 'project_title1';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img1';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description1';
	$arrValues_project[] = $projdes;
	
}
else if($id==2)
{
	$arrFields_project[] = 'project_title2';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img2';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description2';
	$arrValues_project[] = $projdes;
}
else if($id==3)
{
	$arrFields_project[] = 'project_title3';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img3';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description3';
	$arrValues_project[] = $projdes;
}
else if($id==4)
{
	$arrFields_project[] = 'project_title4';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img4';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description4';
	$arrValues_project[] = $projdes;
}
else if($id==5)
{
	$arrFields_project[] = 'project_title5';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img5';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description5';
	$arrValues_project[] = $projdes;
}
else if($id==6)
{
	$arrFields_project[] = 'project_title6';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img6';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description6';
	$arrValues_project[] = $projdes;
}
else if($id==7)
{
	$arrFields_project[] = 'project_title7';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img7';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description7';
	$arrValues_project[] = $projdes;
}
else if($id==8)
{
	$arrFields_project[] = 'project_title8';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img8';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description8';
	$arrValues_project[] = $projdes;
}
else if($id==9)
{
	$arrFields_project[] = 'project_title9';
	$arrValues_project[] = $projtitle;
	$arrFields_project[] = 'project_img9';
	$arrValues_project[] = $img;
	$arrFields_project[] = 'project_description9';
	$arrValues_project[] = $projdes;
}
$get_home = mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate3_details',$arrFields_project,$arrValues_project);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Project page added Successfully";
		$action="1"; //1 for success
		
		$response="Project details saved successfully !!!";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate3_details',$arrFields_project,$arrValues_project,"doc_id='".$admin_id."'");
		$response="Project details updated successfully !!!";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate3_deatil_id'];
	}
	
	
	if(basename($_FILES['txtPhoto1']['name']!==""))
	{ 
		$folder_name	=	"theme3ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto1']['name'];
		$file_url		=	$_FILES['txtPhoto1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	echo "<script type='text/javascript'>alert(' $response'); location.href='single_portfolio.php?id=$id';</script>";
	// header("Location:index.php#about#res=0");
}
  
	
if(isset($_POST['add_contact']))
{	

	$contactemail 	= addslashes($_POST['contact_email']);
	$contactphone	= $_POST['contact_phone'];
	$contactaddress = addslashes($_POST['contact_address_info']);
	$contacthours 	= addslashes($_POST['contact_working_hours']);
	$contactfacebook= addslashes($_POST['contact_facebook']);
	$contacttwitter = addslashes($_POST['contact_twitter']);
	$contactgplus 	= addslashes($_POST['contact_gplus']);
	$contactlinkedin= addslashes($_POST['contact_linkedin']);
	$arrFields_contact = array();
	$arrValues_contact = array();
	$arrFields_contact[] = 'doc_id';
	$arrValues_contact[] = $admin_id;
	$arrFields_contact[] = 'webtemplate_id';
	$arrValues_contact[] = "3";	// Template1
	$arrFields_contact[] = 'doc_type';
	$arrValues_contact[] = "1";
	$arrFields_contact[] = 'contact_email';
	$arrValues_contact[] = $contactemail;
	$arrFields_contact[] = 'contact_phone';
	$arrValues_contact[] = $contactphone;
	$arrFields_contact[] = 'contact_address_info';
	$arrValues_contact[] = $contactaddress;
	$arrFields_contact[] = 'contact_working_hours';
	$arrValues_contact[] = $contacthours;
	
	$arrFields_contact[] = 'contact_facebook';
	$arrValues_contact[] = $contactfacebook;
	$arrFields_contact[] = 'contact_twitter';
	$arrValues_contact[] = $contacttwitter;
	$arrFields_contact[] = 'contact_gplus';
	$arrValues_contact[] = $contactgplus;
	$arrFields_contact[] = 'contact_linkedin';
	$arrValues_contact[] = $contactlinkedin;
	
	$get_home = mysqlSelect('*','webtemplate3_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate3_details',$arrFields_contact,$arrValues_contact);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Contact page added Successfully";
		$action="1"; //1 for success
		
		$response="Contact details saved successfully !!!";
	}
	else
	{
		$homeUpdate=mysqlUpdate('webtemplate3_details',$arrFields_contact,$arrValues_contact,"doc_id='".$admin_id."'");
		$response="Contact details updated successfully !!!";
		$action="1"; //0 for Update
		$detailID = $get_home[0]['webtemplate3_deatil_id'];
	}
	echo"<script type='text/javascript'>alert(' $response'); location.href='index.php#contact';</script>";
	// header("Location:index.php#about#res=0");
}
?>	
	