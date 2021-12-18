<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
include('short_url.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

	//$admin_id = $_SESSION['user_id'];
	//$ccmail="medical@medisense.me";
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	//Random Password Generator

	function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

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

	function hyphenize($string) {
		
		return 
		## strtolower(
			preg_replace(
				array('#[\\s+]+#', '#[^A-Za-z0-9\. -]+#', '/\@^|(\.+)/'),
				array('-',''),
			##     cleanString(
				urldecode($string)
			##     )
			)
		## )
		;
	}
	date_default_timezone_set('Asia/Kolkata');
	$curDate=date('Y-m-d H:i:s');

	$name = ''; $type = ''; $size = ''; $error = '';
	function compress_image($source_url, $destination_url, $quality) {

		$info = getimagesize($source_url);

    		if ($info['mime'] == 'image/jpeg')
        			$image = imagecreatefromjpeg($source_url);

    		elseif ($info['mime'] == 'image/gif')
        			$image = imagecreatefromgif($source_url);

   		elseif ($info['mime'] == 'image/png')
        			$image = imagecreatefrompng($source_url);

    		imagejpeg($image, $destination_url, $quality);
		return $destination_url;
	}


//Medical History	
if(isset($_GET['updatecon']) && !empty($_GET['updatecon']))
{

    if(isset($_GET['patsmoke']))
	{        
		$arrFileds_medical[]='smoking';
		$arrValues_medical[]=$_GET['patsmoke'];
	}
	if(isset($_GET['patalcohol']))
	{        
		$arrFileds_medical[]='alcohol';
		$arrValues_medical[]=$_GET['patalcohol'];
	}
    if(isset($_GET['pat_allergies']))
	{        
		$arrFileds_medical[]= 'allergies_any';
		$arrValues_medical[] = $_GET['pat_allergies'];
	}
	
	if(isset($_GET['pathyper']))
	{
		$arrFileds_medical[]='hypertension';
		$arrValues_medical[]=$_GET['pathyper'];
	}
	if(isset($_GET['patdiabetes']))
	{
		$arrFileds_medical[]='diabetic';
		$arrValues_medical[]=$_GET['patdiabetes'];
	}

    if(isset($_GET['pat_bp']))
	{
		$arrFileds_medical[]='bp';
		$arrValues_medical[]=$_GET['pat_bp'];
	}
	
	if(isset($_GET['pat_thyroid']))
	{
		$arrFileds_medical[]='thyroid';
		$arrValues_medical[]=$_GET['pat_thyroid'];
	}

    if(isset($_GET['pat_epilepsy']))
	{
		$arrFileds_medical[]='epilepsy';
		$arrValues_medical[]=$_GET['pat_epilepsy'];
	}

	if(isset($_GET['pat_asthama']))
	{
		$arrFileds_medical[]='asthama';
		$arrValues_medical[]=$_GET['pat_asthama'];
	}
	
	if(isset($_GET['pat_cholestrole']))
	{
		$arrFileds_medical[]='cholesterol';
		$arrValues_medical[]=$_GET['pat_cholestrole'];
	}	

	// $member_general_health = $objQuery->mysqlSelect('*','user_family_general_health',"md5(member_id)='".$_GET['patientid']."'","","","","");
    // if(!empty($member_general_health)){
    //     $update_medical = $objQuery->mysqlUpdate('user_family_general_health',$arrFileds_medical,$arrValues_medical,"md5(member_id) = '".$_GET['patientid']."'");
    // }else{
    //     $find_id = $objQuery->mysqlSelect('*','user_family_member',"md5(member_id)='".$_GET['patientid']."'","","","","");
    //     $arrFileds_medical[]='member_id';
	// 	$arrValues_medical[]= $find_id[0]['member_id']
    //     $update_medical = $objQuery->mysqlInsert('user_family_general_health',$arrFileds_medical,$arrValues_medical );
    // }    

    $update_medical = $objQuery->mysqlUpdate('user_family_general_health',$arrFileds_medical,$arrValues_medical,"md5(member_id) = '".$_GET['patientid']."'");
	

}

if(isset($_GET['updateMember']))
{
    if(isset($_GET['patBlood']))
	{        
		$arrFileds_medical[]= 'blood_group';
		$arrValues_medical[]=$_GET['patBlood'];
	}

    if(isset($_GET['pt_height']))
	{        
		$arrFileds_medical[]= 'height';
		$arrValues_medical[]=$_GET['pt_height'];
	}

    if(isset($_GET['pt_weight']))
	{        
		$arrFileds_medical[]= 'weight';
		$arrValues_medical[]=$_GET['pt_weight'];
	}

    if(isset($_GET['pt_age']))
	{        
		$arrFileds_medical[]= 'age';
		$arrValues_medical[]=$_GET['pt_age'];
	}

    if(isset($_GET['pt_gender']))
	{        
		$arrFileds_medical[]= 'gender';
		$arrValues_medical[]=$_GET['pt_gender'];
	}

    $update_medical = $objQuery->mysqlUpdate('user_family_member',$arrFileds_medical,$arrValues_medical,"md5(member_id) = '".$_GET['patientid']."'");
}




if(isset($_POST['update_patient'])){
	$txtName = addslashes($_POST['se_pat_name']);
	$txtMail = addslashes($_POST['se_email']);
	$txtAge = $_POST['se_pat_age'];
	$txtGen = $_POST['se_gender'];
	
	$res_height = $feet.".".$inches;
	$weight = $_POST['weight'];
	
	$txtContact = $_POST['se_con_per'];
	$txtMob = $_POST['se_phone_no'];
	$txtCountry = $_POST['se_country'];
	$txtState = $_POST['se_state'];
	$txtLoc = $_POST['se_city'];
	$txtAddress = addslashes($_POST['se_address']);

	
	$dob = date('Y-m-d',strtotime($_POST['date_birth']));
	
	$patImage = addslashes($_FILES['txtPhoto']['name']);
	
	$arrFields = array();
	$arrValues = array();
	
						
	$arrFields[] = 'patient_name';
	$arrValues[] = $txtName;
	
	if(!empty($_POST['se_pat_age'])){
	$arrFields[] = 'patient_age';
	$arrValues[] = $txtAge;
	}
	
	if(!empty($_POST['date_birth'])){
	$arrFields[] = 'DOB';
	$arrValues[] = $dob;
	}

	$arrFields[] = 'patient_email';
	$arrValues[] = $txtMail;

	$arrFields[] = 'patient_gen';
	$arrValues[] = $txtGen;
	
	$arrFields[] = 'patient_mob';
	$arrValues[] = $txtMob;
	
	$arrFields[] = 'weight';
	$arrValues[] = $weight;
	
	$arrFields[] = 'height_cm';
	$arrValues[] = $_POST['height'];

	$arrFields[] = 'patient_loc';
	$arrValues[] = $txtLoc;

	$arrFields[] = 'pat_state';
	$arrValues[] = $txtState;

	$arrFields[] = 'pat_country';
	$arrValues[] = $txtCountry;

	$arrFields[] = 'patient_addrs';
	$arrValues[] = $txtAddress;
			
	if(!empty($_FILES['txtPhoto']['name'])){
		$arrFields[]="patient_image";
		$arrValues[]=$patImage;
	}
	
	$userupdate=$objQuery->mysqlUpdate('doc_my_patient',$arrFields,$arrValues, "patient_id = '". $_POST['patient_id'] ."' ");
	$patientid = $_POST['patient_id'];


	//UPLOAD COMPRESSED IMAGE
	if ($_FILES["txtPhoto"]["error"] > 0) {
			$error = $_FILES["txtPhoto"]["error"];
	} 
	else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
	($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
	($_FILES["txtPhoto"]["type"] == "image/png") || 
	($_FILES["txtPhoto"]["type"] == "image/pjpeg")) {
	
		$uploaddirectory = realpath("patientImage");
		$uploaddir = $uploaddirectory . "/" .$patientid;
		
		/*Checking whether folder with category id already exist or not. */
	if (file_exists($uploaddir)) {
		//echo "The file $uploaddir exists";
		} else {
		$newdir = mkdir($uploaddirectory . "/" . $patientid, 0777);
	}
		
		
			$url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];

			$filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 60);
			$buffer = file_get_contents($url);

	}else {
			$error = "Uploaded image should be jpg or gif or png";
	}
	$response="updated";
	header("Location:Patient-Profile-Details?p=".md5($patientid)."&d=".md5($_POST['doc_id'])."&t=".$_POST['trans_id']);		
}

//Add Patient Attachment
//Add Patient Attachments
if(isset($_POST['addAttachments'])){
	//Save patient episode attachments
				
    $errors= array();
    $timestring = time();
    if(!empty($_POST['upload_user'])){
    $uploadUser = $_POST['upload_user'];
    $userType = "2";
    }
    else
    {
    $uploadUser = $_POST['patient_id'];	
    $userType = "1";						
    }
    $patientId = $_POST['patient_id'];
    $uploaddirectory = realpath("patientAttachments");
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
            
            $arrFields_Attach[] = 'report_title';
            $arrValues_Attach[] = $_POST['report_title'];
            
            $arrFields_Attach[] = 'report_folder';
            $arrValues_Attach[] = $timestring;
            
            $arrFields_Attach[] = 'attachments';
            $arrValues_Attach[] = $file_name;
            
            $arrFields_Attach[] = 'user_id';
            $arrValues_Attach[] = $uploadUser;
            
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
	$response="reports-attached";
	header("Location:Patient-Profile-Details?p=".md5($patientId)."&d=".md5($_POST['doc_id'])."&t=".$_POST['trans_id']);	
	
}

?>