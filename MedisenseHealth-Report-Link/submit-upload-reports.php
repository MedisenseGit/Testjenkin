<?php ob_start();
 error_reporting(0);
 session_start(); 


$main_url = str_replace("www.","", $_SERVER['HTTP_HOST']);
$slices = explode('/', $_SERVER['REQUEST_URI']);
$class = implode(' ', $slices); 
$base_URL  = $main_url .'/'. $slices[0].'';

$current_url = $main_url .'/'. $slices[0].'';
//echo $current_url;
//echo $base_URL;

//echo $back_url;
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
//include('../send_mail_function.php');
//include('../send_text_message.php');
$back_url = HOST_MAIN_URL."MedisenseHealth-Report-Link/upload-reports";

function createKey(){
	//create a random key
	$strKey = md5(microtime());
	
	//check to make sure this key isnt already in use
	$resCheck = mysql_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
	$arrCheck = mysql_fetch_assoc($resCheck);
	if($arrCheck['count(*)']){
		//key already in use
		return createKey();
	}else{
		//key is OK
		return $strKey;
	}
}

if(isset($_POST['upload_reports']) && basename($_FILES['txtphoto1']['name']!=="")){
	$arrValues1 = array();
	$arrFields1 = array();
	
	$arrFields1[]= 'repnotattach';
	$arrValues1[]= '2';
	
	$reportState=$objQuery->mysqlUpdate('patient_tab',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");

	
	//echo basename($_FILES['txtphoto1']['name']);
	$errors= array();
	foreach($_FILES['txtphoto1']['tmp_name'] as $key => $tmp_name){	
	
	//get a unique download key
	$strKey = createKey();
	$leadtime=(time()+(60*60*24*7));	
	
	$file_name = $_FILES['txtphoto1']['name'][$key];
	$file_size =$_FILES['txtphoto1']['size'][$key];
	$file_tmp =$_FILES['txtphoto1']['tmp_name'][$key];
	$file_type=$_FILES['txtphoto1']['type'][$key];
	
	//echo "s".$file_name;
		$Photo1  = $file_name;
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'patient_id';
		$arrValues[] = $_POST['Pat_Id'];

		$arrFields[] = 'attachments';
		$arrValues[] = $file_name;
		
		$arrFields[] = 'downloadkey';
		$arrValues[] = $strKey;
		
		$arrFields[] = 'expires';
		$arrValues[] = $leadtime;

			
			$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields,$arrValues);
		$id= mysql_insert_id();


		/* Uploading image file */ 
	    	 $uploaddirectory = realpath("../Attach");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 $dotpos = strpos($fileName, '.');
			 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $id, $Photo1);
			 $uploadfile = $uploaddir . "/" . $Photo1;
			
		
			/*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($file_tmp, $uploadfile)) {
				//echo "File uploaded.";
			} else {
				//echo "File cannot be uploaded";
			}
		
			
	}
//end if

 $response="success";			
		 header("Location:".$back_url."?pat_id=".md5($_POST['Pat_Id'])."&resultMail=".$response);	
}
?>