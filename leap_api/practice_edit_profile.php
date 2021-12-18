<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	
	$admin_id = $_POST['user_id'];
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['txtCity'];
	$slctHosp = addslashes($_POST['selectHosp']);	
	$slctSpec = $_POST['slctSpec'];
	$txtQual = addslashes($_POST['txtQual']);
	$txtExp = $_POST['txtExp'];
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = $_POST['txtEmail'];
	$txtWebsite = $_POST['txtWebsite'];
	$txtInterest = addslashes($_POST['txtInterest']);
	$txtContribute = addslashes($_POST['txtContribute']);
	$txtResearch = addslashes($_POST['txtResearch']);
	$txtPublication = addslashes($_POST['txtPublication']);

	$docImage = basename($_FILES['txtPhoto']['name']);
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'contact_person';
		$arrValues[] = $txtDoc;
		$arrFields[] = 'Address';
		$arrValues[] = $slctHosp;
		if($slctSpec==""){
		$arrFields[] = 'specialisation';
		$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'specialisation';
		$arrValues[] = $slctSpec;	
		}
		$arrFields[] = 'Email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'website';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		
		$arrFields[] = 'location';
		$arrValues[] = $txtCity;
		$arrFields[] = 'state';
		$arrValues[] = $slctState;
		$arrFields[] = 'country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'ref_exp';
		$arrValues[] = $txtExp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $txtInterest;
		$arrFields[] = 'doc_research';
		$arrValues[] = $txtResearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $txtContribute;
		$arrFields[] = 'doc_pub';
		$arrValues[] = $txtPublication;
		
		$arrFields[] = 'cont_num1';
		$arrValues[] = $txtMobile;
		
		
		/* $arrFields[] = 'in_op_cost';
		$arrValues[] = $txtInOpcost;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $txtOnOpcost;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $txtConcharge;
		
		$arrFields[] = 'secretary_phone';
		$arrValues[] = $txtSecPhone;
		$arrFields[] = 'secretary_email';
		$arrValues[] = $txtSecEmail;	*/
		
		if(!empty($docImage)){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		
		/* $arrFields[] = 'tele_op';
		$arrValues[] = $teleOpCond;
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		$arrFields[] = 'video_op';
		$arrValues[] = $videoOpCond;
		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming;  */
	
	$updateProvider=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$admin_id."'");
	$id=$admin_id;	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("../Refer/partnerProfilePic");
					mkdir("../Refer/partnerProfilePic/". "/" . $id, 0777);
					$uploaddir = $uploaddirectory."/".$id;
					$dotpos = strpos($_FILES['txtPhoto']['name'], '.');
					$photo = $docImage;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtPhoto']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
	
	$result = array("result" => "success");
	echo json_encode($result);  
 }
?>
