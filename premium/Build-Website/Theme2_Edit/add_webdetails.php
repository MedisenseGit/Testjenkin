<?php
ob_start();
session_start();
error_reporting(0);  


require_once("../../../classes/querymaker.class.php");
require_once("../../../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();

$admin_id = 178;
//$ccmail="medical@medisense.me";

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$time = date("d-m-Y")."-".time();

if(isset($_POST['add_about']))
{
	
	$txtDocName 		= addslashes($_POST['about_name']);
	$txtDocDesignation 	= addslashes($_POST['about_designation']);
	$txtDocCompany 		= addslashes($_POST['about_company']);
	$txtDocPlace 		= addslashes($_POST['about_place']);
	$aboutproimg 		= $time."-".basename($_FILES['txtPhoto']['name']);
	$aboutimg 			= $time."-".basename($_FILES['txtPhoto1']['name']);
	$aboutbio 			= addslashes($_POST['about_bio']);
	$aboutadminto1 		= $_POST['about_admin_to1'];
	$aboutadminfrom1 	= $_POST['about_admin_from1'];
	$aboutadmintitle1 	= addslashes($_POST['about_admin_title1']);
	$aboutadminsubtitle1= addslashes($_POST['about_admin_subtitle1']);
	$aboutadminto2 		= $_POST['about_admin_to2'];
	$aboutadminfrom2 	= $_POST['about_admin_from2'];
	$aboutadmintitle2 	= addslashes($_POST['about_admin_title2']);
	$aboutadminsubtitle2= addslashes($_POST['about_admin_subtitle2']);
	$aboutadminto3 		= $_POST['about_admin_to3'];
	$aboutadminfrom3 	= $_POST['about_admin_from3'];
	$aboutadmintitle3 	= addslashes($_POST['about_admin_title3']);
	$aboutadminsubtitle3= addslashes($_POST['about_admin_subtitle3']);
	$aboutadminto4 		= $_POST['about_admin_to4'];
	$aboutadminfrom4 	= $_POST['about_admin_from4'];
	$aboutadmintitle4 	= $_POST['about_admin_title4'];
	$aboutadminsubtitle4= addslashes($_POST['about_admin_subtitle4']);
	$abouteduname1 		= addslashes($_POST['about_edu_name1']);
	$abouteduyear1 		= $_POST['about_edu_year1'];
	$aboutedutitle1 	= addslashes($_POST['about_edu_title1']);
	$aboutedusubtitle1 	= addslashes($_POST['about_edu_subtitle1']);
	$abouteduname1 		= addslashes($_POST['about_edu_name2']);
	$abouteduyear1 		= $_POST['about_edu_year2'];
	$aboutedutitle1 	= addslashes($_POST['about_edu_title2']);
	$aboutedusubtitle1 	= addslashes($_POST['about_edu_subtitle2']);
	$abouteduname1 		= addslashes($_POST['about_edu_name3']);
	$abouteduyear1 		= $_POST['about_edu_year3'];
	$aboutedutitle1 	= addslashes($_POST['about_edu_title3']);
	$aboutedusubtitle1 	= addslashes($_POST['about_edu_subtitle3']);
	$aboutawardyear1 	= $_POST['about_award_year1'];
	$aboutawardtitle1 	= addslashes($_POST['about_award_title1']);
	$aboutawardsubtitle1= addslashes($_POST['about_award_subtitle1']);
	$aboutawardyear2 	= $_POST['about_award_year2'];
	$aboutawardtitle2 	= addslashes($_POST['about_award_title2']);
	$aboutawardsubtitle2= addslashes($_POST['about_award_subtitle2']);
	$aboutawardyear3 	= $_POST['about_award_year3'];
	$aboutawardtitle3 	= addslashes($_POST['about_award_title3']);
	$aboutawardsubtitle3= addslashes($_POST['about_award_subtitle3']);
	$aboutawardyear4 	= $_POST['about_award_year4'];
	$aboutawardtitle4 	= addslashes($_POST['about_award_title4']);
	$aboutawardsubtitle4= addslashes($_POST['about_award_subtitle4']);
	$aboutawardyear5 	= $_POST['about_award_year5'];
	$aboutawardtitle5 	= addslashes($_POST['about_award_title5']);
	$aboutawardsubtitle5= addslashes($_POST['about_award_subtitle5']);
	
	$arrFields_home = array();
	$arrValues_home = array();
	
	if(!empty($admin_id))
	{
		$arrFields_home[] = 'doc_id';
		$arrValues_home[] = $admin_id;
	}
	
	
	$arrFields_home[] = 'webtemplate_id';
	$arrValues_home[] = "2";	// Template1
	$arrFields_home[] = 'doc_type';
	$arrValues_home[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	$arrFields_home[] = 'about_name';
	$arrValues_home[] = $txtDocName;
	$arrFields_home[] = 'about_designation';
	$arrValues_home[] = $txtDocDesignation;
	$arrFields_home[] = 'about_company';
	$arrValues_home[] = $txtDocCompany;
	$arrFields_home[] = 'about_place';
	$arrValues_home[] = $txtDocPlace;
	$arrFields_home[] = 'about_profile_img';
	$arrValues_home[] = $aboutproimg;
	$arrFields_home[] = 'about_image';
	$arrValues_home[] = $aboutimg;
	$arrFields_home[] = 'about_bio';
	$arrValues_home[] = $aboutbio;
	$arrFields_home[] = 'about_admin_to1';
	$arrValues_home[] = $aboutadminto1;
	$arrFields_home[] = 'about_admin_from1';
	$arrValues_home[] = $aboutadminfrom1;
	$arrFields_home[] = 'about_admin_title1';
	$arrValues_home[] = $aboutadmintitle1;
	$arrFields_home[] = 'about_admin_subtitle1';
	$arrValues_home[] = $aboutadminsubtitle1;
	$arrFields_home[] = 'about_admin_to2';
	$arrValues_home[] = $aboutadminto2;
	$arrFields_home[] = 'about_admin_from2';
	$arrValues_home[] = $aboutadminfrom2;
	$arrFields_home[] = 'about_admin_title2';
	$arrValues_home[] = $aboutadmintitle2;
	$arrFields_home[] = 'about_admin_subtitle2';
	$arrValues_home[] = $aboutadminsubtitle2;
	$arrFields_home[] = 'about_admin_to3';
	$arrValues_home[] = $aboutadminto3;
	$arrFields_home[] = 'about_admin_from3';
	$arrValues_home[] = $aboutadminfrom3;
	$arrFields_home[] = 'about_admin_title3';
	$arrValues_home[] = $aboutadmintitle3;
	$arrFields_home[] = 'about_admin_subtitle3';
	$arrValues_home[] = $aboutadminsubtitle3;
	$arrFields_home[] = 'about_admin_to4';
	$arrValues_home[] = $aboutadminto4;
	$arrFields_home[] = 'about_admin_from4';
	$arrValues_home[] = $aboutadminfrom4;
	$arrFields_home[] = 'about_admin_title4';
	$arrValues_home[] = $aboutadmintitle4;
	$arrFields_home[] = 'about_admin_subtitle4';
	$arrValues_home[] = $aboutadminsubtitle4;
	$arrFields_home[] = 'about_edu_name1';
	$arrValues_home[] = $abouteduname1;
	$arrFields_home[] = 'about_edu_year1';
	$arrValues_home[] = $abouteduyear1;
	$arrFields_home[] = 'about_edu_title1';
	$arrValues_home[] = $aboutedutitle1;
	$arrFields_home[] = 'about_edu_subtitle1';
	$arrValues_home[] = $aboutedusubtitle1;
	$arrFields_home[] = 'about_edu_name2';
	$arrValues_home[] = $abouteduname2;
	$arrFields_home[] = 'about_edu_year2';
	$arrValues_home[] = $abouteduyear2;
	$arrFields_home[] = 'about_edu_title2';
	$arrValues_home[] = $aboutedutitle2;
	$arrFields_home[] = 'about_edu_subtitle2';
	$arrValues_home[] = $aboutedusubtitle2;
	$arrFields_home[] = 'about_edu_name3';
	$arrValues_home[] = $abouteduname3;
	$arrFields_home[] = 'about_edu_year3';
	$arrValues_home[] = $abouteduyear3;
	$arrFields_home[] = 'about_edu_title3';
	$arrValues_home[] = $aboutedutitle3;
	$arrFields_home[] = 'about_edu_subtitle3';
	$arrValues_home[] = $aboutedusubtitle3;
	$arrFields_home[] = 'about_award_year1';
	$arrValues_home[] = $aboutawardyear1;
	$arrFields_home[] = 'about_award_title1';
	$arrValues_home[] = $aboutawardtitle1;
	$arrFields_home[] = 'about_award_subtitle1';
	$arrValues_home[] = $aboutawardsubtitle1;
	
	$arrFields_home[] = 'about_award_year2';
	$arrValues_home[] = $aboutawardyear2;
	$arrFields_home[] = 'about_award_title2';
	$arrValues_home[] = $aboutawardtitle2;
	$arrFields_home[] = 'about_award_subtitle2';
	$arrValues_home[] = $aboutawardsubtitle2;
	
	$arrFields_home[] = 'about_award_year3';
	$arrValues_home[] = $aboutawardyear3;
	$arrFields_home[] = 'about_award_title3';
	$arrValues_home[] = $aboutawardtitle3;
	$arrFields_home[] = 'about_award_subtitle3';
	$arrValues_home[] = $aboutawardsubtitle3;
	
	$arrFields_home[] = 'about_award_year4';
	$arrValues_home[] = $aboutawardyear4;
	$arrFields_home[] = 'about_award_title4';
	$arrValues_home[] = $aboutawardtitle4;
	$arrFields_home[] = 'about_award_subtitle4';
	$arrValues_home[] = $aboutawardsubtitle4;
	
	$arrFields_home[] = 'about_award_year5';
	$arrValues_home[] = $aboutawardyear5;
	$arrFields_home[] = 'about_award_title5';
	$arrValues_home[] = $aboutawardtitle5;
	$arrFields_home[] = 'about_award_subtitle5';
	$arrValues_home[] = $aboutawardsubtitle5;
	$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate2_details',$arrFields_home,$arrValues_home);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="About page added Successfully";
		$action="1"; //1 for success
		
		$response="home-success";
	}
	else 
	{
		
		$homeUpdate=mysqlUpdate('webtemplate2_details',$arrFields_home,$arrValues_home,"doc_id='".$admin_id."'");
		$response="home-updated";
		$action="1"; //0 for Update
		
		$detailID = $get_home[0]['webtemplate2_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto']['name'];
		$file_url		=	$_FILES['txtPhoto']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

		
	}
	if(basename($_FILES['txtPhoto1']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto1']['name'];
		$file_url		=	$_FILES['txtPhoto1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	header("Location:index.php?response=".$response);
}
if(isset($_POST['add_research']))
{
	$txtresearchsum 	= addslashes($_POST['research_summary']);
	$txtinterest1 		= addslashes($_POST['research_interest1']);
	$txtinterest2 		= addslashes($_POST['research_interest2']);
	$txtinterest3 		= addslashes($_POST['research_interest3']);
	$txtinterest4 		= addslashes($_POST['research_interest4']);
	$txtinterest5 		= addslashes($_POST['research_interest5']);
	$txtinterest6 		= addslashes($_POST['research_interest6']);
	$researchtitle1 	= addslashes($_POST['research_proj_title1']);
	$researchdes1 		= addslashes($_POST['research_proj_description1']);
	$researchlink1		= addslashes($_POST['research_proj_link1']);
	$researchimg1 		= $time."-".basename($_FILES['txtPhoto2']['name']);
	$researchtitle2 	= addslashes($_POST['research_proj_title2']);
	$researchdes2		= addslashes($_POST['research_proj_description2']);
	$researchlink2 		= addslashes($_POST['research_proj_link2']);
	$researchimg2 		= $time."-". basename($_FILES['txtPhoto3']['name']);
	$researchtitle3 	= addslashes($_POST['research_proj_title3']);
	$researchdes3 		= addslashes($_POST['research_proj_description3']);
	$researchlink3 		= addslashes($_POST['research_proj_link3']);
	$researchimg3 		= $time."-".basename($_FILES['txtPhoto4']['name']);
	
	$arrFields_research = array();
	$arrValues_research = array();
	
	if(!empty($admin_id))
	{
		$arrFields_research[] = 'doc_id';
		$arrValues_research[] = $admin_id;
	}
	
	
	$arrFields_research[] = 'webtemplate_id';
	$arrValues_research[] = "2";	// Template1
	$arrFields_research[] = 'doc_type';
	$arrValues_research[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor
	$arrFields_research[] = 'research_summary';
	$arrValues_research[] = $txtresearchsum;
	$arrFields_research[] = 'research_interest1';
	$arrValues_research[] = $txtinterest1;
	$arrFields_research[] = 'research_interest2';
	$arrValues_research[] = $txtinterest2;
	$arrFields_research[] = 'research_interest3';
	$arrValues_research[] = $txtinterest3;
	$arrFields_research[] = 'research_interest4';
	$arrValues_research[] = $txtinterest4;
	$arrFields_research[] = 'research_interest5';
	$arrValues_research[] = $txtinterest5;
	$arrFields_research[] = 'research_interest6';
	$arrValues_research[] = $txtinterest6;
	$arrFields_research[] = 'research_proj_title1';
	$arrValues_research[] = $researchtitle1;
	$arrFields_research[] = 'research_proj_description1';
	$arrValues_research[] = $researchdes1;
	$arrFields_research[] = 'research_proj_link1';
	$arrValues_research[] = $researchlink1;
	$arrFields_research[] = 'research_proj_img1';
	$arrValues_research[] = $researchimg1;
	
	$arrFields_research[] = 'research_proj_title2';
	$arrValues_research[] = $researchtitle2;
	$arrFields_research[] = 'research_proj_description2';
	$arrValues_research[] = $researchdes2;
	$arrFields_research[] = 'research_proj_link2';
	$arrValues_research[] = $researchlink2;
	$arrFields_research[] = 'research_proj_img2';
	$arrValues_research[] = $researchimg2;
	
	$arrFields_research[] = 'research_proj_title3';
	$arrValues_research[] = $researchtitle3;
	$arrFields_research[] = 'research_proj_description3';
	$arrValues_research[] = $researchdes3;
	$arrFields_research[] = 'research_proj_link3';
	$arrValues_research[] = $researchlink3;
	$arrFields_research[] = 'research_proj_img3';
	$arrValues_research[] = $researchimg3;
$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate2_details',$arrFields_research,$arrValues_research);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Research added Successfully";
		$action="1"; //1 for success
		
		$response="Research-success";
	}
	else 
	{
		
			$homeUpdate=mysqlUpdate('webtemplate2_details',$arrFields_research,$arrValues_research,"doc_id='".$admin_id."' and doc_type='1'");
			$response="research-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate2_deatil_id'];
	}
if(basename($_FILES['txtPhoto2']['name']!==""))
{ 
	$folder_name	=	"theme2ImageAttach";
	$sub_folder		=	$detailID;
	$filename		=	$_FILES['txtPhoto2']['name'];
	$file_url		=	$_FILES['txtPhoto2']['tmp_name'];
	fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	
}
	if(basename($_FILES['txtPhoto3']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto3']['name'];
		$file_url		=	$_FILES['txtPhoto3']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	
		
	}
	if(basename($_FILES['txtPhoto4']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto4']['name'];
		$file_url		=	$_FILES['txtPhoto4']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	header("Location:index.php?response=".$response);
}

if(isset($_POST['add_teach']))
{
	$txtteaching 		= 	addslashes($_POST['teaching_title']);
	$txtteachingto1 	= 	$_POST['teaching_curr_to1'];
    $txtteachingfrom1 	=	$_POST['teaching_curr_from1'];
	$teachingtitle1 	=	addslashes($_POST['teaching_curr_title1']);
	$teachingsubtitle1 	=	addslashes($_POST['teaching_curr_subtitle1']);
	$txtteachingto2 	= 	$_POST['teaching_curr_to2'];
	$txtteachingfrom2 	=	$_POST['teaching_curr_from2'];
	$teachingtitle2 	= 	addslashes($_POST['teaching_curr_title2']);
	$teachingsubtitle2 	= 	addslashes($_POST['teaching_curr_subtitle2']);
	$texthistto1 		= 	$_POST['teaching_hist_to1'];
	$texthistfrom1 		= 	$_POST['teaching_hist_from1'];
	$txthisttitle1 		= 	addslashes($_POST['teaching_hist_title1']);
	$txthistsubtitle1 	= 	addslashes($_POST['teaching_hist_subttle1']);
	$texthistto2 		= 	$_POST['teaching_hist_to2'];
	$texthistfrom2 		= 	$_POST['teaching_hist_from2'];
	$txthisttitle2 		= 	addslashes($_POST['teaching_hist_title2']);
	$txthistsubtitle2 	= 	addslashes($_POST['teaching_hist_subttle2']);
	$texthistto3 		= 	$_POST['teaching_hist_to3'];
	$texthistfrom3 		= 	$_POST['teaching_hist_from3'];
	$txthisttitle3 		= 	addslashes($_POST['teaching_hist_title3']);
	$txthistsubtitle3 	= 	addslashes($_POST['teaching_hist_subttle3']);
	$texthistto4 		= 	$_POST['teaching_hist_to4'];
	$texthistfrom4 		= 	$_POST['teaching_hist_from4'];
	$txthisttitle4 		= 	addslashes($_POST['teaching_hist_title4']);
	$txthistsubtitle4 	= 	addslashes($_POST['teaching_hist_subttle4']);

	$texthistto5 		= 	$_POST['teaching_hist_to5'];
	$texthistfrom5 		= 	$_POST['teaching_hist_from5'];
	$txthisttitle5 		= 	addslashes($_POST['teaching_hist_title5']);
	$txthistsubtitle5 	= 	addslashes($_POST['teaching_hist_subttle5']);
	 
	$arrFields_teaching = array();
	$arrValues_teaching = array();
	
	$arrFields_teaching[] = 'teaching_title'; 
	$arrValues_teaching[] = $txtteaching;
	
	$arrFields_teaching[] = 'teaching_curr_to1'; 
	$arrValues_teaching[] = $txtteachingto1;
	
	$arrFields_teaching[] = 'teaching_curr_from1'; 
	$arrValues_teaching[] = $txtteachingfrom1;
	
	$arrFields_teaching[] = 'teaching_curr_title1'; 
	$arrValues_teaching[] =  $teachingtitle1;
	
	$arrFields_teaching[] = 'teaching_curr_subtitle1'; 
	$arrValues_teaching[] = $teachingsubtitle1;
	
	$arrFields_teaching[] = 'teaching_curr_to2'; 
	$arrValues_teaching[] = $txtteachingto2;
	
	$arrFields_teaching[] = 'teaching_curr_from2'; 
	$arrValues_teaching[] = $txtteachingfrom2;
	
	$arrFields_teaching[] = 'teaching_curr_title2'; 
	$arrValues_teaching[] =  $teachingtitle2;
	
	$arrFields_teaching[] = 'teaching_curr_subtitle2'; 
	$arrValues_teaching[] = $teachingsubtitle2;
	
	$arrFields_teaching[] = 'teaching_hist_to1'; 
	$arrValues_teaching[] = $texthistto1;
	
	$arrFields_teaching[] = 'teaching_hist_from1'; 
	$arrValues_teaching[] = $texthistfrom1;
	
	$arrFields_teaching[] = 'teaching_hist_title1'; 
	$arrValues_teaching[] = $txthisttitle1;
	
	$arrFields_teaching[] = 'teaching_hist_subttle1'; 
	$arrValues_teaching[] = $txthistsubtitle1;
	
	$arrFields_teaching[] = 'teaching_hist_to2'; 
	$arrValues_teaching[] = $texthistto2;
	
	$arrFields_teaching[] = 'teaching_hist_from2'; 
	$arrValues_teaching[] = $texthistfrom2;
	
	$arrFields_teaching[] = 'teaching_hist_title2'; 
	$arrValues_teaching[] = $txthisttitle2;
	
	$arrFields_teaching[] = 'teaching_hist_subttle2'; 
	$arrValues_teaching[] = $txthistsubtitle2;
	
	$arrFields_teaching[] = 'teaching_hist_to3'; 
	$arrValues_teaching[] = $texthistto3;
	
	$arrFields_teaching[] = 'teaching_hist_from3'; 
	$arrValues_teaching[] = $texthistfrom3;
	
	$arrFields_teaching[] = 'teaching_hist_title3'; 
	$arrValues_teaching[] = $txthisttitle3;
	
	$arrFields_teaching[] = 'teaching_hist_subttle3'; 
	$arrValues_teaching[] = $txthistsubtitle3;
	
	$arrFields_teaching[] = 'teaching_hist_to4'; 
	$arrValues_teaching[] = $texthistto4;
	
	$arrFields_teaching[] = 'teaching_hist_from4'; 
	$arrValues_teaching[] = $texthistfrom4;
	
	$arrFields_teaching[] = 'teaching_hist_title4'; 
	$arrValues_teaching[] = $txthisttitle4;
	
	$arrFields_teaching[] = 'teaching_hist_subttle4'; 
	$arrValues_teaching[] = $txthistsubtitle4;
	
	$arrFields_teaching[] = 'teaching_hist_to5'; 
	$arrValues_teaching[] = $texthistto5;
	
	$arrFields_teaching[] = 'teaching_hist_from5'; 
	$arrValues_teaching[] = $texthistfrom5;
	
	$arrFields_teaching[] = 'teaching_hist_title5'; 
	$arrValues_teaching[] = $txthisttitle5;
	
	$arrFields_teaching[] = 'teaching_hist_subttle5'; 
	$arrValues_teaching[] = $txthistsubtitle5;
	
	$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate2_details',$arrFields_teaching,$arrValues_teaching);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Teaching added Successfully";
		$action="1"; //1 for success
		
		$response="Teaching-success";
	}
	else 
	{
		
			$homeUpdate=mysqlUpdate('webtemplate2_details',$arrFields_teaching,$arrValues_teaching,"doc_id='".$admin_id."' and doc_type='1'");
			$response="Teaching-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate2_deatil_id'];
	}
	header("Location:index.php?response=".$response);

}	

if(isset($_POST['add_publication']))
{
	$pubtitle 		= addslashes($_POST['publication_title']);
	$pubtitle1 		= addslashes($_POST['publication_title1']);
	$pubauthor1 	= addslashes($_POST['publication_author1']);
	$pubarticle1 	= addslashes($_POST['publication_article1']);
	$pubdescription1= addslashes($_POST['publication_description1']);
	$publink1 		= addslashes($_POST['publication_link1']);
	$pubtitle2 		= addslashes($_POST['publication_title2']);
	$pubauthor2 	= addslashes($_POST['publication_author2']);
	$pubarticle2 	= addslashes($_POST['publication_article2']);
	$pubdescription2= addslashes($_POST['publication_description2']);
	$publink2 		= addslashes($_POST['publication_link2']);
	$pubtitle3 		= addslashes($_POST['publication_title3']);
	$pubauthor3 	= addslashes($_POST['publication_author3']);
	$pubarticle3 	= addslashes($_POST['publication_article3']);
	$pubdescription3= addslashes($_POST['publication_description3']);
	$publink3 		= addslashes($_POST['publication_link3']);
	$pubtitle4 		= addslashes($_POST['publication_title4']);
	$pubauthor4 	= addslashes($_POST['publication_author4']);
	$pubarticle4 	= addslashes($_POST['publication_article4']);
	$pubdescription4= addslashes($_POST['publication_description4']);
	$publink4 		= addslashes($_POST['publication_link4']);
	$pubtitle5 		= addslashes($_POST['publication_title5']);
	$pubauthor5 	= addslashes($_POST['publication_author5']);
	$pubarticle5 	= addslashes($_POST['publication_article5']);
	$pubdescription5= addslashes($_POST['publication_description5']);
	$publink5 		= addslashes($_POST['publication_link5']);
	$pubfile1 		= $time."-".basename($_FILES['txtPhotop1']['name']);
	$pubtitle6 		= addslashes($_POST['publication_title6']);
	$pubauthor6 	= addslashes($_POST['publication_author6']);
	$pubarticle6 	= addslashes($_POST['publication_article6']);
	$pubdescription6= addslashes($_POST['publication_description6']);
	$publink6 		= addslashes($_POST['publication_link6']);
	$pubfile2 		= $time."-".basename($_FILES['txtPhotop2']['name']);
	$pubtitle7 		= addslashes($_POST['publication_title7']);
	$pubauthor7 	= addslashes($_POST['publication_author7']);
	$pubarticle7 	= addslashes($_POST['publication_article7']);
	$pubdescription7= addslashes($_POST['publication_description7']);
	$publink7 		= addslashes($_POST['publication_link7']);
	$pubfile3 		= $time."-".basename($_FILES['txtPhotop3']['name']);
	$pubtitle8 		= addslashes($_POST['publication_title8']);
	$pubauthor8 	= addslashes($_POST['publication_author8']);
	$pubarticle8 	= addslashes($_POST['publication_article8']);
	$pubdescription8= addslashes($_POST['publication_description8']);
	$publink8 		= addslashes($_POST['publication_link8']);
	$pubfile4 		= $time."-".basename($_FILES['txtPhotop4']['name']);
	$pubtitle9 		= addslashes($_POST['publication_title9']);
	$pubauthor9 	= addslashes($_POST['publication_author9']);
	$pubarticle9 	= addslashes($_POST['publication_article9']);
	$pubdescription9= addslashes($_POST['publication_description9']);
	$publink9 		= addslashes($_POST['publication_link9']);
	$pubfile5 		= $time."-".basename($_FILES['txtPhotop5']['name']);
	
	$arrFields_publication = array();
	$arrValues_publication = array();
	
	$arrFields_publication[] = 'publication_title'; 
	$arrValues_publication[] = $pubtitle;
	
	$arrFields_publication[] = 'publication_title1'; 
	$arrValues_publication[] = $pubtitle1;
	
	$arrFields_publication[] = 'publication_author1'; 
	$arrValues_publication[] = $pubauthor1;
	
	$arrFields_publication[] = 'publication_article1'; 
	$arrValues_publication[] = $pubarticle1;
	
	$arrFields_publication[] = 'publication_description1'; 
	$arrValues_publication[] = $pubdescription1;
	
	$arrFields_publication[] = 'publication_link1'; 
	$arrValues_publication[] = $publink1;
	
	
	$arrFields_publication[] = 'publication_title2'; 
	$arrValues_publication[] = $pubtitle2;
	
	$arrFields_publication[] = 'publication_author2'; 
	$arrValues_publication[] = $pubauthor2;
	
	$arrFields_publication[] = 'publication_article2'; 
	$arrValues_publication[] = $pubarticle2;
	
	$arrFields_publication[] = 'publication_description2'; 
	$arrValues_publication[] = $pubdescription2;
	
	$arrFields_publication[] = 'publication_link2'; 
	$arrValues_publication[] = $publink2;

	
	$arrFields_publication[] = 'publication_title3'; 
	$arrValues_publication[] = $pubtitle3;
	
	$arrFields_publication[] = 'publication_author3'; 
	$arrValues_publication[] = $pubauthor3;
	
	$arrFields_publication[] = 'publication_article3'; 
	$arrValues_publication[] = $pubarticle3;
	
	$arrFields_publication[] = 'publication_description3'; 
	$arrValues_publication[] = $pubdescription3;
	
	$arrFields_publication[] = 'publication_link3'; 
	$arrValues_publication[] = $publink3;
	
	$arrFields_publication[] = 'publication_title4'; 
	$arrValues_publication[] = $pubtitle4;
	
	$arrFields_publication[] = 'publication_author4'; 
	$arrValues_publication[] = $pubauthor4;
	
	$arrFields_publication[] = 'publication_article4'; 
	$arrValues_publication[] = $pubarticle4;
	
	$arrFields_publication[] = 'publication_description4'; 
	$arrValues_publication[] = $pubdescription4;
	
	$arrFields_publication[] = 'publication_link4'; 
	$arrValues_publication[] = $publink4;

	$arrFields_publication[] = 'publication_title5'; 
	$arrValues_publication[] = $pubtitle5;
	
	$arrFields_publication[] = 'publication_author5'; 
	$arrValues_publication[] = $pubauthor5;
	
	$arrFields_publication[] = 'publication_article5'; 
	$arrValues_publication[] = $pubarticle5;
	
	$arrFields_publication[] = 'publication_description5'; 
	$arrValues_publication[] = $pubdescription5;

	$arrFields_publication[] = 'publication_link5'; 
	$arrValues_publication[] = $publink5;
	
	$arrFields_publication[] = 'publication_download5'; 
	$arrValues_publication[] = $pubfile1;
	
	$arrFields_publication[] = 'publication_title6'; 
	$arrValues_publication[] = $pubtitle6;
	
	$arrFields_publication[] = 'publication_author6'; 
	$arrValues_publication[] = $pubauthor6;
	
	$arrFields_publication[] = 'publication_article6'; 
	$arrValues_publication[] = $pubarticle6;
	
	$arrFields_publication[] = 'publication_description6'; 
	$arrValues_publication[] = $pubdescription6;

	$arrFields_publication[] = 'publication_link6'; 
	$arrValues_publication[] = $publink6;
	
	$arrFields_publication[] = 'publication_download6'; 
	$arrValues_publication[] = $pubfile2;
	
	$arrFields_publication[] = 'publication_title7'; 
	$arrValues_publication[] = $pubtitle7;
	
	$arrFields_publication[] = 'publication_author7'; 
	$arrValues_publication[] = $pubauthor7;
	
	$arrFields_publication[] = 'publication_article7'; 
	$arrValues_publication[] = $pubarticle7;
	
	$arrFields_publication[] = 'publication_description7'; 
	$arrValues_publication[] = $pubdescription7;

	$arrFields_publication[] = 'publication_link7'; 
	$arrValues_publication[] = $publink7;
	
	$arrFields_publication[] = 'publication_download7'; 
	$arrValues_publication[] = $pubfile3;
	
	$arrFields_publication[] = 'publication_title8'; 
	$arrValues_publication[] = $pubtitle8;
	
	$arrFields_publication[] = 'publication_author8'; 
	$arrValues_publication[] = $pubauthor8;
	
	$arrFields_publication[] = 'publication_article8'; 
	$arrValues_publication[] = $pubarticle8;
	
	$arrFields_publication[] = 'publication_description8'; 
	$arrValues_publication[] = $pubdescription8;
	
	$arrFields_publication[] = 'publication_link8'; 
	$arrValues_publication[] = $publink8;
	
	$arrFields_publication[] = 'publication_download8'; 
	$arrValues_publication[] = $pubfile4;
	
	$arrFields_publication[] = 'publication_title9'; 
	$arrValues_publication[] = $pubtitle9;
	
	$arrFields_publication[] = 'publication_author9'; 
	$arrValues_publication[] = $pubauthor9;
	
	$arrFields_publication[] = 'publication_article9'; 
	$arrValues_publication[] = $pubarticle9;
	
	$arrFields_publication[] = 'publication_description9'; 
	$arrValues_publication[] = $pubdescription9;
	
	$arrFields_publication[] = 'publication_link9'; 
	$arrValues_publication[] = $publink9;
	
	$arrFields_publication[] = 'publication_download9'; 
	$arrValues_publication[] = $pubfile5;
	

	$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate2_details',$arrFields_publication,$arrValues_publication);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Publication added Successfully";
		$action="1"; //1 for success
		
		$response="Publication-success";
	}
	else
	{
		
			$homeUpdate=mysqlUpdate('webtemplate2_details',$arrFields_publication,$arrValues_publication,"doc_id='".$admin_id."' and doc_type='1'");
			$response="Publication-updated";
			$action="1"; //0 for Update
			
			$detailID = $get_home[0]['webtemplate2_deatil_id'];
	}
	
	if(basename($_FILES['txtPhotop1']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhotop1']['name'];
		$file_url		=	$_FILES['txtPhotop1']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	if(basename($_FILES['txtPhotop2']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhotop2']['name'];
		$file_url		=	$_FILES['txtPhotop2']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	if(basename($_FILES['txtPhotop3']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhotop3']['name'];
		$file_url		=	$_FILES['txtPhotop3']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	if(basename($_FILES['txtPhotop4']['name']!==""))
	{ 
		
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhotop4']['name'];
		$file_url		=	$_FILES['txtPhotop4']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	if(basename($_FILES['txtPhotop5']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhotop5']['name'];
		$file_url		=	$_FILES['txtPhotop5']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	
	header("Location:index.php?response=".$response);

}	
	
if(isset($_POST['add_gallery']))
{	
	$galtitle =	$_POST['gallery_title'];
	$galimg1 = $time."-".basename($_FILES['txtPhoto5']['name']);
	$galimg2 = $time."-".basename($_FILES['txtPhoto6']['name']);
	$galimg3 = $time."-".basename($_FILES['txtPhoto7']['name']);
	$galimg4 = $time."-".basename($_FILES['txtPhoto8']['name']);
	$galimg5 = $time."-".basename($_FILES['txtPhoto9']['name']);
	$galimg6 = $time."-".basename($_FILES['txtPhoto10']['name']);

	$arrFields_gallery = array();
	$arrValues_gallery  = array();
	
	$arrFields_gallery[] = 'gallery_title'; 
	$arrValues_gallery[] = $galtitle;
	
	$arrFields_gallery [] = 'gallery_img1'; 
	$arrValues_gallery [] = $galimg1;
	
	$arrFields_gallery [] = 'gallery_img2'; 
	$arrValues_gallery [] = $galimg2;
	
	$arrFields_gallery [] = 'gallery_img3'; 
	$arrValues_gallery [] = $galimg3;
	
	$arrFields_gallery [] = 'gallery_img4'; 
	$arrValues_gallery [] = $galimg4;
	
	
	$arrFields_gallery [] = 'gallery_img5'; 
	$arrValues_gallery [] = $galimg5;
	
	$arrFields_gallery [] = 'gallery_img6'; 
	$arrValues_gallery [] = $galimg6;
	
	
	$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate=mysqlInsert('webtemplate2_details',$arrFields_gallery,$arrValues_gallery);
		$detailID = $homecreate;  //Get doc web Id
	
		$msg="Gallery added Successfully";
		$action="1"; //1 for success
		
		$response="Gallery-success";
	}
	else 
	{
	
		$homeUpdate	=	mysqlUpdate('webtemplate2_details',$arrFields_gallery,$arrValues_gallery,"doc_id='".$admin_id."' and doc_type='1'");
		$response	=	"Gallery-updated";
		$action		=	"1"; //0 for Update
		$detailID = $get_home[0]['webtemplate2_deatil_id'];
	}
	
	if(basename($_FILES['txtPhoto5']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto5']['name'];
		$file_url		=	$_FILES['txtPhoto5']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
	}
	
	if(basename($_FILES['txtPhoto6']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto6']['name'];
		$file_url		=	$_FILES['txtPhoto6']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	if(basename($_FILES['txtPhoto7']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto7']['name'];
		$file_url		=	$_FILES['txtPhoto7']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	if(basename($_FILES['txtPhoto8']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto8']['name'];
		$file_url		=	$_FILES['txtPhoto8']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	}
	
	if(basename($_FILES['txtPhoto9']['name']!==""))
	{ 
		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto9']['name'];
		$file_url		=	$_FILES['txtPhoto9']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

		
	}
	
	if(basename($_FILES['txtPhoto10']['name']!==""))
	{ 

		$folder_name	=	"theme2ImageAttach";
		$sub_folder		=	$detailID;
		$filename		=	$_FILES['txtPhoto10']['name'];
		$file_url		=	$_FILES['txtPhoto10']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	
	header("Location:index.php?response=".$response);

}	
	

if(isset($_POST['add_contact']))
{
	$contacttitle 		= 	addslashes($_POST['contact_title']);
	$contactphone 		= 	$_POST['contact_phone'];
	$contactemail 		= 	addslashes($_POST['contact_email']);
	$contactfacebook 	= 	addslashes($_POST['contact_facebook']);
	$contactskype 		= 	addslashes($_POST['contact_skype']);
	$contactlinkedin 	= 	addslashes($_POST['contact_linkedin']);
	$contactoffice 		= 	addslashes($_POST['contact_office']);
	$contactwork 		= 	addslashes($_POST['contact_work']);
	$contactlab 		= 	addslashes($_POST['contact_lab']);
			
	$arrFields_contact = array();
	$arrValues_contact = array();
	
	$arrFields_contact[] = 'contact_title'; 
	$arrValues_contact[] = $contacttitle;
	$arrFields_contact[] = 'contact_phone'; 
	$arrValues_contact[] = $contactphone;
	$arrFields_contact[] = 'contact_email'; 
	$arrValues_contact[] = $contactemail;
	$arrFields_contact[] = 'contact_facebook'; 
	$arrValues_contact[] = $contactfacebook;
	$arrFields_contact[] = 'contact_skype'; 
	$arrValues_contact[] = $contactskype;
	$arrFields_contact[] = 'contact_linkedin'; 
	$arrValues_contact[] = $contactlinkedin;
	$arrFields_contact[] = 'contact_office'; 
	$arrValues_contact[] = $contactoffice;
	$arrFields_contact[] = 'contact_work'; 
	$arrValues_contact[] = $contactwork;
	$arrFields_contact[] = 'contact_lab'; 
	$arrValues_contact[] = $contactlab;
	
	$get_home = mysqlSelect('*','webtemplate2_details',"doc_id='".$admin_id."' and doc_type='1'");
	if(COUNT($get_home) == 0) 
	{
		
		$homecreate	=	mysqlInsert('webtemplate2_details',$arrFields_contact,$arrValues_contact);
		$detailID 	= 	$homecreate;  //Get doc web Id
		$msg		=	"Contact added Successfully";
		$action		=	"1"; //1 for success
		$response	=	"Contact-success";
	}
	else 
	{
		
		$homeUpdate	=	mysqlUpdate('webtemplate2_details',$arrFields_contact,$arrValues_contact,"doc_id='".$admin_id."' and doc_type='1'");
		$response	=	"Contact-updated";
		$action		=	"1"; //0 for Update
		$detailID 	= 	$get_home[0]['webtemplate2_deatil_id'];
	}
	header("Location:index.php?response=".$response);

}
	
	

?>


	
