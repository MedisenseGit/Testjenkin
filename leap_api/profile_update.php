<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	
	$admin_id = $_POST['userid'];
	$txtDoc = addslashes($_POST['txtDoc']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtCity = $_POST['txtCity'];
	//$slctHosp = addslashes($_POST['selectHosp']);	
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
	$login_type = $_POST['login_type'];
	
	$txtMobile2 = $_POST['txtMobile2'];
	$txtOnOpcost = $_POST['txtOpinionCost'];
	$txtConcharge = $_POST['txtConsultationFees'];
	$txtSecPhone = $_POST['txtSecretaryPhone'];
	$txtSecEmail = $_POST['txtSecretaryEmail'];
	$teleOpCond = $_POST['txtReadyTeleOp'];
	$telecontact = $_POST['txtTeleOpNum'];
	$videoOpCond = $_POST['txtReadyVideoOp'];
	$videocontact = $_POST['txtVideoOpNum'];
	$teleoptiming = $_POST['txtAvailableTimings'];
	
	if($login_type==1){  //Login type 1 for Doctor
	$arrFields = array();
	$arrValues = array();
	
		$arrFields[] = 'ref_name';
		$arrValues[] = $txtDoc;
		/*$arrFields[] = 'ref_address';
		$arrValues[] = $slctHosp;
		if($slctSpec==""){
		$arrFields[] = 'specialisation';
		$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'doc_spec';
		$arrValues[] = $slctSpec;	
		}*/
		$arrFields[] = 'ref_mail';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'ref_web';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		
		$arrFields[] = 'doc_city';
		$arrValues[] = $txtCity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'doc_country';
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
		
		$arrFields[] = 'contact_num';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'secondary_contact_num';
		$arrValues[] = $txtMobile2;
		
		
		//$arrFields[] = 'in_op_cost';
		//$arrValues[] = $txtInOpcost;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $txtOnOpcost;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $txtConcharge;
		
		$arrFields[] = 'secretary_phone';
		$arrValues[] = $txtSecPhone;
		$arrFields[] = 'secretary_email';
		$arrValues[] = $txtSecEmail;	
		
		if(!empty($docImage)){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		
		$arrFields[] = 'tele_op';
		$arrValues[] = $teleOpCond;
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		$arrFields[] = 'video_op';
		$arrValues[] = $videoOpCond;
		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming; 
	
	$updateProvider=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$admin_id."'");
	$id=$admin_id;	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!=="")){ 
					$uploaddirectory = realpath("../Doc");
					mkdir("../Doc/". "/" . $id, 0777);
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
				
		// Update Doctor Specializations	
		if(!empty($_POST['doc_specialization'])) {
			$objQuery->mysqlDelete('doc_specialization',"doc_id='".$admin_id."' and doc_type='1'");
			
			while (list($key, $val) = each($_POST['doc_specialization'])) {
				$doc_spec_id = $_POST['doc_specialization'][$key];
				
				$arrFileds_spec = array();
				$arrValues_spec = array();
				
				$arrFileds_spec[]='spec_id';
				$arrValues_spec[]=$doc_spec_id;
				
				$arrFileds_spec[]='doc_id';
				$arrValues_spec[]=$admin_id;
				
				$arrFileds_spec[]='doc_type';
				$arrValues_spec[]='1';
				
				$insert_spec=$objQuery->mysqlInsert('doc_specialization',$arrFileds_spec,$arrValues_spec);
			
			}
		}

		// Update Doctor Hospitals
		if(!empty($_POST['doc_hospital'])) {
			$objQuery->mysqlDelete('doctor_hosp',"doc_id='".$admin_id."'");
			
			while (list($key, $val) = each($_POST['doc_hospital'])) {
				$doc_hosp_id = $_POST['doc_hospital'][$key];
				
				$arrFileds_hosp = array();
				$arrValues_hosp = array();
				
				$arrFileds_hosp[]='hosp_id';
				$arrValues_hosp[]=$doc_hosp_id;
				
				$arrFileds_hosp[]='doc_id';
				$arrValues_hosp[]=$admin_id;
				
				$insert_hosp=$objQuery->mysqlInsert('doctor_hosp',$arrFileds_hosp,$arrValues_hosp);
			
			}
		}
	
	$result = array("result" => "success");
	echo json_encode($result); 
	}
	else if($login_type==3){ //Login type 3 for Marketing
		
		$arrFields = array();
		$arrValues = array();
	
		$arrFields[] = 'person_name';
		$arrValues[] = $txtDoc;
		$arrFields[] = 'person_mobile';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'person_email';
		$arrValues[] = $txtEmail;
		
		$updateMarket=$objQuery->mysqlUpdate('hosp_marketing_person',$arrFields,$arrValues,"person_id='".$admin_id."'");
		$result = array("result" => "success");
		echo json_encode($result);
	}
 }
?>
