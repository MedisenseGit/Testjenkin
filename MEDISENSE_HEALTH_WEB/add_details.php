<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
include("push_notification_function.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['addAttachments'])){ 

	$patient_id = $_POST['patient_id'];
	
	//Add Patient Attachments functionality
	$errors= array();
	foreach($_FILES['file-5']['tmp_name'] as $key => $tmp_name ){	
							
				$file_name = $_FILES['file-5']['name'][$key];
				$file_size =$_FILES['file-5']['size'][$key];
				$file_tmp =$_FILES['file-5']['tmp_name'][$key];
				$file_type=$_FILES['file-5']['type'][$key];
							
				if(!empty($file_name)){
					$Photo1  = $file_name;
					$arrFields1 = array();
					$arrValues1 = array();

					$arrFields1[] = 'patient_id';
					$arrValues1[] = $patient_id;

					$arrFields1[] = 'attachments';
					$arrValues1[] = $file_name;
																	
					$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
					$id= mysql_insert_id();


					//Uploading image file 
					$uploaddirectory = realpath("../Attach");
					$uploaddir = $uploaddirectory . "/" .$id;
					$dotpos = strpos($fileName, '.');
					$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $id, $Photo1);
					$uploadfile = $uploaddir . "/" . $Photo1;
									
								
					//Checking whether folder with category id already exist or not.
					if (file_exists($uploaddir)) {
					//echo "The file $uploaddir exists";
						} else {
							$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
						}
									
						// Moving uploaded file from temporary folder to desired folder.
						if(move_uploaded_file ($file_tmp, $uploadfile)) {
										
							$successAttach="";
								} else {
										//echo "File cannot be uploaded";
							}
				}
									
		}
		//End of foreach

		$respond= "reports-attached";
		header('Location:PATIENT_ATTACHMENTS?response='.$respond);
	
}

?>