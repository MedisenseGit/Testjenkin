<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['patient_name']) || isset($_POST['patient_age'])|| isset($_POST['patient_gender'])|| isset($_POST['patient_location'])|| isset($_POST['patient_specialization'])|| 
	isset($_POST['patient_mobile'])|| isset($_POST['patient_chiefmedcomplaint']) || isset($_POST['patient_weight']) || isset($_POST['patient_maritalstatus']) || isset($_POST['patient_profession']) || 
	isset($_POST['patient_hypertesnsion']) || isset($_POST['patient_diabetes']) || isset($_POST['patient_contactperson']) || isset($_POST['patient_email']) || isset($_POST['patient_address']) || 
	isset($_POST['patient_city']) || isset($_POST['patient_state']) || isset($_POST['patient_country']) || isset($_POST['patient_current_treating_doctor']) || isset($_POST['patient_current_treating_hospital']) || 
	isset($_POST['patient_brief_description']) || isset($_POST['patient_querytodoctor']) )
	
	{
	
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	//Get Partner Id 
	$getPartner = $objQuery->mysqlSelect('b.source_id as Source_Id','our_partners as a left join source_list as b on a.partner_id=b.partner_id',"a.partner_id='".$_POST['user_id']."'","","","","");
	

	/* echo $_POST['patient_name'];
	 echo $_POST['patient_age'];
	 echo $_POST['patient_gender'];
	 echo $_POST['patient_location'];
	 echo $_POST['patient_specialization'];
	 echo $_POST['patient_mobile'];
	 echo $_POST['patient_chiefmedcomplaint'];
	 echo $_POST['patient_weight'];
	 echo $_POST['patient_maritalstatus'];
	 echo $_POST['patient_profession'];
	 echo $_POST['patient_hypertesnsion'];
	 echo $_POST['patient_diabetes'];
	 echo $_POST['patient_contactperson'];
	 echo $_POST['patient_email'];
	 echo $_POST['patient_address'];
	 echo $_POST['patient_city'];
	 echo $_POST['patient_state'];
	 echo $_POST['patient_country'];
	 echo $_POST['patient_current_treating_doctor'];
	 echo $_POST['patient_current_treating_hospital'];
	 echo $_POST['patient_brief_description'];
	 echo $_POST['patient_querytodoctor'];   */
	 
	
	// echo date("Y-m-d");
	 
	 $arrFields1 = array();
	 $arrValues1 = array();
	 
	$arrFields1[]= 'patient_name';
	$arrValues1[]=  $_POST['patient_name'];
	$arrFields1[]= 'patient_age';
	$arrValues1[]=  $_POST['patient_age'];
	$arrFields1[]= 'patient_gen';
	$arrValues1[]=  $_POST['patient_gender'];
	$arrFields1[]= 'patient_loc';
	$arrValues1[]=  $_POST['patient_location'];
	$arrFields1[]= 'medDept';
	$arrValues1[]=  $_POST['patient_specialization'];
	$arrFields1[]= 'patient_mob';
	$arrValues1[]=  $_POST['patient_mobile'];
	$arrFields1[]= 'patient_complaint';
	$arrValues1[]=  $_POST['patient_chiefmedcomplaint'];
	$arrFields1[]= 'weight';
	$arrValues1[]=  $_POST['patient_weight'];
	$arrFields1[]= 'merital_status';
	$arrValues1[]=  $_POST['patient_maritalstatus'];
	$arrFields1[]= 'profession';
	$arrValues1[]=  $_POST['patient_profession'];
	$arrFields1[]= 'hyper_cond';
	$arrValues1[]=  $_POST['patient_hypertesnsion'];
	$arrFields1[]= 'diabetes_cond';
	$arrValues1[]=  $_POST['patient_diabetes'];

	$arrFields1[]= 'patient_src';
	$arrValues1[]=  $getPartner[0]['Source_Id'];

	$arrFields1[]= 'contact_person';
	$arrValues1[]=  $_POST['patient_contactperson'];
	$arrFields1[]= 'patient_email';
	$arrValues1[]=  $_POST['patient_email'];
	$arrFields1[]= 'patient_addrs';
	$arrValues1[]=  $_POST['patient_address'];
	$arrFields1[]= 'pref_city';
	$arrValues1[]=  $_POST['patient_city'];
	$arrFields1[]= 'pat_state';
	$arrValues1[]=  $_POST['patient_state'];
	$arrFields1[]= 'pat_country';
	$arrValues1[]=  $_POST['patient_country'];
	$arrFields1[]= 'currentTreatDoc';
	$arrValues1[]=  $_POST['patient_current_treating_doctor'];
	$arrFields1[]= 'currentTreatHosp';
	$arrValues1[]=  $_POST['patient_current_treating_hospital'];
	$arrFields1[]= 'patient_desc';
	$arrValues1[]=  $_POST['patient_brief_description'];
	$arrFields1[]= 'pat_query';
	$arrValues1[]=  $_POST['patient_querytodoctor'];
	$arrFields1[]= 'TImestamp';
	$arrValues1[]=  date("Y-m-d,h:i:s");
	$arrFields1[]= 'system_date';
	$arrValues1[]=  date("Y-m-d");
	
	$current_date = date("Y-m-d");
	$mobile_num = $_POST['patient_mobile'];
	
	
	$getcount = $objQuery->mysqlSelect('count(patient_mob) AS NumberOfPatient','patient_tab',"patient_mob='".$mobile_num."' and system_date='".$current_date."'","","","","");
	if($getcount == true)
	{
		if( $getcount[0]['NumberOfPatient'] >= 1)
		{
			$getcount = array('status' => "false","patient_create" => "already_exists", "count" => $getcount[0]['NumberOfPatient'] );      // patient insert failed
			echo json_encode($getcount);
		}
		else {
			$patientCreate=$objQuery->mysqlInsert('patient_tab',$arrFields1,$arrValues1);
			$pid= mysql_insert_id();
			if($patientCreate == true)
			{
				$getPatientDetail = $objQuery->mysqlSelect('*','patient_tab',"patient_id ='".$pid."'","","","","");
	
				$success = array('status' => "true","patient_create" => $patientCreate,"patient_detail" => $getPatientDetail);    	//  patient created resume
				echo json_encode($success);
			}
			else {
				$success = array('status' => "false","patient_create" => $patientCreate);      // patient insert failed
				echo json_encode($success);
			}
		}
		
	}
	else {
		$success = array('status' => "false","patient_create" => $getcount);      // patient insert failed
		echo json_encode($success);
	}
	
}


?>