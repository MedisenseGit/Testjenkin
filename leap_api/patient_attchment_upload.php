<?php
  
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

   $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : '';
   
    $file_path = "";
	
    
  	$file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
	$file_name = basename( $_FILES['uploaded_file']['name']);
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[] = 'patient_id';
	$arrValues1[] = $member_id;

	$arrFields1[] = 'attachments';
	$arrValues1[] = $file_name;	

	
	$attach_insert=$objQuery->mysqlInsert('patient_attachment',$arrFields1,$arrValues1);
	$attach_id= mysql_insert_id();
	
	$uploaddirectory = realpath("../Attach");
	 $uploaddir = $uploaddirectory . "/" .$attach_id;
			 $dotpos = strpos($fileName, '.');
			 $file_path = str_replace(substr($file_path, 0, $dotpos), $attach_id, $file_path);
			 $uploadfile = $uploaddir . "/" . $file_path;
			 
			 
		/*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $attach_id, 0777);
			}
	
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $uploadfile)) {
        $result = array("result" => "success");
    } else{
        $result = array("result" => "error");
    }

    echo json_encode($result);

?>
