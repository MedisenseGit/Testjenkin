<?php

ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['patient_id']) || isset($_POST['attach_name']) )
	
{
	$pid = $_POST['patient_id'];
	$Photo1 = $_POST['attach_name'];
	echo $Photo1;

	$uploaddirectory = realpath("PatientAttachmentUpload");
			 $uploaddir = $uploaddirectory . "/" .$pid;
			 $dotpos = strpos($fileName, '.');
			 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $pid, $Photo1);
			 $uploadfile = $uploaddir . "/" . $Photo1;
			 
			 if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($_POST['attach_name'], $uploadfile)) {
				echo "File uploaded.";
			} else {
				echo "File cannot be uploaded";
			}
			
}

 
?>