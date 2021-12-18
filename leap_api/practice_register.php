<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 	 $txtDocName = $_POST['txtDocName'];
	 $slctCountry = $_POST['slctCountry'];
	 $slctState = $_POST['slctState'];
	 $txtCity = $_POST['txtCity'];
	 $slctSpec = $_POST['slctSpec'];
	 $txtHosp = $_POST['txtHosp'];
	 $txtQual = $_POST['txtQual'];
	 $txtMob = $_POST['txtMob'];
	 $txtEmail = $_POST['txtEmail'];
	 $passwd = $_POST['passwd'];
	 $txtMedCouncil = $_POST['txtMedCouncil'];
	 $txtMedRegnum = $_POST['txtMedRegnum'];
	 $txtregCert = basename($_FILES['txtregCert']['name']);

 	$result = $objQuery->mysqlSelect('*','our_partners',"Email_id='".$txtEmail."'or cont_num1='".$txtMob."'");
	if(empty($result)){

		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'contact_person';
		$arrValues[] = $txtDocName;
		$arrFields[] = 'login_status';
		$arrValues[] = "1";
		$arrFields[] = 'country';
		$arrValues[] = $slctCountry;
		$arrFields[] = 'state';
		$arrValues[] = $slctState;
		$arrFields[] = 'location';
		$arrValues[] = $txtCity;
		$arrFields[] = 'Type';
		$arrValues[] = "Doctor";
		$arrFields[] = 'specialisation';
		$arrValues[] = $slctSpec;
		$arrFields[] = 'partner_name';
		$arrValues[] = $txtHosp;
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		$arrFields[] = 'cont_num1';
		$arrValues[] = $txtMob;
		$arrFields[] = 'Email_id';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'password';
		$arrValues[] = md5($passwd);
		$arrFields[] = 'reg_date';
		$arrValues[] = date('Y-m-d H:i:s');

		$doccreate=$objQuery->mysqlInsert('our_partners',$arrFields,$arrValues);
		$docid= mysql_insert_id();
		$arrFields1 = array();
		$arrValues1 = array();

		$arrFields1[] = 'doc_id';
		$arrValues1[] = $docid;
		$arrFields1[] = 'type';
		$arrValues1[] = "2"; //Type 2 for practice doctors
		$arrFields1[] = 'council_name';
		$arrValues1[] = $txtMedCouncil;
		$arrFields1[] = 'reg_num';
		$arrValues1[] = $txtMedRegnum;
		$arrFields1[] = 'reg_certificate';
		$arrValues1[] = $txtregCert;
		$arrFields1[] = 'create_date';
		$arrValues1[] = date('Y-m-d H:i:s');

		$doccreate=$objQuery->mysqlInsert('doctor_registration_details',$arrFields1,$arrValues1);
		//$docregid= mysql_insert_id();

		/* Uploading image file */ 
		if(basename($_FILES['txtregCert']['name']!=="")){ 
			$uploaddirectory = realpath("../PracticeDocCertificate");
			mkdir("../PracticeDocCertificate/". "/" . $docid, 0777);
			$uploaddir = $uploaddirectory."/".$docid;
			$dotpos = strpos($_FILES['txtregCert']['name'], '.');
			$photo = $txtregCert;
			$uploadfile = $uploaddir . "/" . $photo;			
				
							
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($_FILES['txtregCert']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
				$result = array("result" => "success");
				echo json_encode($result);   
			} 
		}

		$result = array("result" => "success");
		echo json_encode($result);  
	}
	else {
		$result = array("result" => "error");
		echo json_encode($result);
	}
   
	  

?>
