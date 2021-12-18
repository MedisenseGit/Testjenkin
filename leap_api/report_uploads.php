<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));

// My Patients View Reports Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$patientId = $_POST['patient_id'];
	
	if($login_type == 1) {  // Premium Login
		
		$errors= array();
		$timestring = time();
		$userType = "2";	// Premium User - 2, Patient - 1
		
		$uploaddirectory = realpath("../premium/patientAttachments");
		$uploaddir = $uploaddirectory . "/" . $patientId . "/" .$timestring;
						
		/*Checking whether folder with category id already exist or not. */
		if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
		} 
		else {
				$newdir = mkdir($uploaddirectory . "/" . $patientId , 0777);
				$newdir = mkdir($uploaddirectory . "/" . $patientId . "/" .$timestring , 0777);
		}
		
		foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name )
		{	
			$file_name = $_FILES['file-5']['name'][$key];
			$file_size =$_FILES['file-5']['size'][$key];
			$file_tmp =$_FILES['file-5']['tmp_name'][$key];
			$file_type=$_FILES['file-5']['type'][$key];
						
			if(!empty($file_name)){
				$Photo1  = $file_name;
				$arrFields_Attach = array();
				$arrValues_Attach  = array();

				$arrFields_Attach[] = 'patient_id';
				$arrValues_Attach[] = $patientId;

				$arrFields_Attach[] = 'report_folder';
				$arrValues_Attach[] = $timestring;
							
				$arrFields_Attach[] = 'attachments';
				$arrValues_Attach[] = $file_name;
							
				$arrFields_Attach[] = 'user_id';
				$arrValues_Attach[] = $admin_id;
							
				$arrFields_Attach[] = 'user_type';
				$arrValues_Attach[] = $userType;
							
				$arrFields_Attach[] = 'date_added';
				$arrValues_Attach[] = $Cur_Date;
							
									
				$bslist_pht=$objQuery->mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
				$epiid= mysql_insert_id();


				/* Uploading image file */ 
				$dotpos = strpos($fileName, '.');
				$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $timestring, $Photo1);
				$uploadfile = $uploaddir . "/" . $Photo1;
								
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($file_tmp, $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
								
				} //End file empty conditions
								
		}//End of foreach
		
		
		$success = array('result' => "success");
		echo json_encode($success);
		
	}
	else {
		$success = array('result' => "failure");
		echo json_encode($success);
	} 
		

	
}


?>