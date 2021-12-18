<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Appointment List
 if(API_KEY == $_POST['API_KEY']) {
 
	$logintype = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$TransId=time();
	
		$txtTemplateName = $_POST['template_name'];
		
		$admin_id = addslashes($_POST['userid']);
		$login_type = addslashes($_POST['login_type']);
		
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'admin_id';
		$arrValues[] = $admin_id;
		
		$arrFields[] = 'template_name';
		$arrValues[] = $txtTemplateName;
		
		$usercraete=$objQuery->mysqlInsert('patient_episode_prescription_templates',$arrFields,$arrValues);
		$templateID = mysql_insert_id(); //Get Template Id
		/* save for template table Ends here */
		
		
				/* save for patient_episode_prescriptions starts here */
				$episode_desc = $_POST['prescription_trade_name'];
				while (list($key, $val) = each($_POST['prescription_trade_name']))
				{
					$prescription_trade_name = $_POST['prescription_trade_name'][$key];
					$prescription_generic_name = $_POST['prescription_generic_name'][$key];
					$prescription_dosage_name = $_POST['prescription_dosage_name'][$key];
					$prescription_route = $_POST['prescription_route'][$key];
					$prescription_frequency = $_POST['prescription_frequency'][$key];
					$prescription_instruction = $_POST['prescription_instruction'][$key];
					$prescription_seq = $key;
					$prescription_date_time = $Cur_Date;

					if($prescription_trade_name != "" && $prescription_generic_name != "" )
					{
						$arrFieldsPEP = array();
						$arrValuesPEP = array();
						$arrFieldsPEP[] = 'template_id';
						$arrValuesPEP[] = $templateID;
						$arrFieldsPEP[] = 'admin_id';
						$arrValuesPEP[] = $admin_id;
						$arrFieldsPEP[] = 'prescription_trade_name';
						$arrValuesPEP[] = $prescription_trade_name;
						$arrFieldsPEP[] = 'prescription_generic_name';
						$arrValuesPEP[] = $prescription_generic_name;
						$arrFieldsPEP[] = 'prescription_dosage_name';
						$arrValuesPEP[] = $prescription_dosage_name;
						$arrFieldsPEP[] = 'prescription_route';
						$arrValuesPEP[] = $prescription_route;
						$arrFieldsPEP[] = 'prescription_frequency';
						$arrValuesPEP[] = $prescription_frequency;
						$arrFieldsPEP[] = 'prescription_instruction';
						$arrValuesPEP[] = $prescription_instruction;
						$arrFieldsPEP[] = 'prescription_seq';
						$arrValuesPEP[] = $prescription_seq;
						$insert_patient_episode_prescriptions = $objQuery->mysqlInsert('patient_episode_prescription_template_details',$arrFieldsPEP,$arrValuesPEP);

					}
					
				}
				/* save for template_prescriptions Ends here */
				
				$result = array("result" => "success");
				echo json_encode($result);
		
}


?>