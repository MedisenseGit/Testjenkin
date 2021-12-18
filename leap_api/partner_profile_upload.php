<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

   $partner_id = isset($_POST['partner_id']) ? $_POST['partner_id'] : '';
   
    $file_path = "";
	
    $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
	$file_name = basename( $_FILES['uploaded_file']['name']);
	
	$arrFields1 = array();
	$arrValues1 = array();


	$arrFields1[] = 'partner_logo';
	$arrValues1[] = $file_name;	
	
	
	$attach_insert=$objQuery->mysqlUpdate('our_partners',$arrFields1,$arrValues1,"partner_id='".$partner_id."'");
	
	$p_id= $partner_id;
	
	$uploaddirectory = realpath("../Doc");
	 $uploaddir = $uploaddirectory . "/" .$p_id;
			 $dotpos = strpos($fileName, '.');
			 $file_path = str_replace(substr($file_path, 0, $dotpos), $p_id, $file_path);
			 $uploadfile = $uploaddir . "/" . $file_path;
			 
			 
		/*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $p_id, 0777);
			}
	


	
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $uploadfile)) {
        $result = array("result" => "success");
    } else{
        $result = array("result" => "error");
    }

    echo json_encode($result);

?>
