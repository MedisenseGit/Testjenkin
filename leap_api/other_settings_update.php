<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

// Other Settings Updates
 if(API_KEY == $_POST['API_KEY'] ) {
	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type']; // 1 -  Premium Login, 2 - Standard Login, 3 - MArketing Person
	$payment_status = $_POST['txtPaymentStatus'];
	$preprint_status = $_POST['txtPrePrint'];
	$consult_status = $_POST['txtConsulFees'];
	$flash_message = $_POST['txtFlashMessage'];
	$header_height = $_POST['txtHeaderHeight'];
	$footer_height = $_POST['txtFooterHeight'];
	$docLogo = basename($_FILES['txtLogo']['name']);

	if($login_type == 1) {		// 1 -  Premium Login
		
		$arrField[] = "payment_opt";
		$arrVales[] = $payment_status;
		
		$arrField[] = "prescription_pad";
		$arrVales[] = $preprint_status;
		
		$arrField[] = "presc_pad_header_height";
		$arrVales[] = $header_height;
		
		$arrField[] = "presc_pad_footer_height";
		$arrVales[] = $footer_height;
		
		$arrField[] = "before_consultation_fee";
		$arrVales[] = $consult_status;
		
		if(!empty($_FILES['txtLogo']['name'])){
			$arrField[]="doc_logo";
			$arrVales[]=$docLogo;
		}
		
		if(!empty($flash_message)) {
			$arrField[]="doc_flash_msg";
			$arrVales[]=$flash_message;
		}
		
		$checkSetting= $objQuery->mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		if(count($checkSetting)>0)
		{
			$update_medicine=$objQuery->mysqlUpdate('doctor_settings',$arrField,$arrVales,"doc_id='".$admin_id."' and doc_type='1'");
		
		}
		else
		{
			$arrField[]="doc_id";
			$arrVales[]=$admin_id;
			$arrField[]="doc_type";
			$arrVales[]='1';
			$insert_patient=$objQuery->mysqlInsert('doctor_settings',$arrField,$arrVales);
		}
		
		/* Uploading image file */ 
		if(basename($_FILES['txtLogo']['name']!=="")){ 
			$uploaddirectory = realpath("../premium/docLogo");
			
			$uploaddir = $uploaddirectory."/".$admin_id;
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
			} else {
				mkdir("../premium/docLogo/". "/" . $admin_id, 0777);
			}
			
			
			$dotpos = strpos($_FILES['txtLogo']['name'], '.');
			$photo = $docLogo;
			$uploadfile = $uploaddir . "/" . $photo;			
				
							
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($_FILES['txtLogo']['tmp_name'], $uploadfile)) {
			//echo "File uploaded.";
			} 
		}
			 
		
			
			$result = array('status' => "true",'settings_result' => "Settings updated successfully.");
			echo json_encode($result);
	}
	else {	
			$result = array('status' => "false",'settings_result' => "Failed to update settings. ");
			echo json_encode($result);
	}
		
}


?>