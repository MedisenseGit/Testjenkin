<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();
include('send_mail_function.php');
include("send_text_message.php");

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

if(API_KEY == $_POST['API_KEY'] || isset($_POST['submit'])){

	$docname = addslashes($_POST['doc_name']);
	$docspec = addslashes($_POST['specialization']);
	$docgend = $_POST['doc_gender'];
	$docage = addslashes($_POST['doc_age']);
	$doctqual = addslashes($_POST['doc_qual']);
	$docexp = addslashes($_POST['doc_exp']);
	$docmail = addslashes($_POST['doc_mail']);
	$docweb = addslashes($_POST['doc_website']);
	$doccontact = addslashes($_POST['doc_contact']);
	$doccountry = addslashes($_POST['doc_country']);
	$docstate = addslashes($_POST['doc_state']);
	$doccity = addslashes($_POST['doc_city']);	
	
	$hospname = addslashes($_POST['hosp_name']);
	$dochospAddress = addslashes($_POST['doc_hosp_address']);
	
	$docexpert = addslashes($_POST['doc_expert']);
	$doccontribute = addslashes($_POST['doc_contrubute']);
	$docresearch = addslashes($_POST['doc_research']);
	$docpublication = addslashes($_POST['doc_publication']);
	$doconlinecharge = addslashes($_POST['online_charge']);
	$docinpercharge = addslashes($_POST['inper_charge']);
	$docconscharge = addslashes($_POST['cons_charge']);
	$doc_passwd = md5($_POST['doc_passwd']);
	$docImage = addslashes($_FILES['txtPhoto']['name']);
	
	$teleOpCond = addslashes($_POST['chkTeleOp']);
	$telecontact = addslashes($_POST['tele_contact']);
	$videoOpCond = addslashes($_POST['chkVideoOp']);
	$videocontact = addslashes($_POST['video_contact']);
	$teleoptiming = addslashes($_POST['available_time']);
		
	$council_name = addslashes($_POST['council_name']);
	$reg_num = addslashes($_POST['reg_num']);
	$date_registration = date('Y-m-d',strtotime($_POST['date_registration']));
	$uploadcertificate = addslashes($_FILES['uploadCertificate']['name']);
		
	
	$arrFields_hosp[] = 'hosp_name';
	$arrValues_hosp[] = $hospname;
	$arrFields_hosp[] = 'hosp_suburb';
	$arrValues_hosp[] = $doccity;
	$arrFields_hosp[] = 'hosp_city';
	$arrValues_hosp[] = $doccity;
	$arrFields_hosp[] = 'hosp_state';
	$arrValues_hosp[] = $docstate;
	$arrFields_hosp[] = 'hosp_addrs';
	$arrValues_hosp[] = $dochospAddress;
	$arrFields_hosp[] = 'hosp_country';
	$arrValues_hosp[] = $doccountry;
	$arrFields_hosp[] = 'hosp_contact';
	$arrValues_hosp[] = $hosp_phone;
	$arrFields_hosp[] = 'hosp_email';
	$arrValues_hosp[] = $hosp_email;
	$arrFields_hosp[] = 'communication_status';
	$arrValues_hosp[] = "1";
	$arrFields_hosp[] = 'medisense_fee';
	$arrValues_hosp[] = "50";
	
	
	
		
		$arrFields[] = 'ref_name';
		$arrValues[] = $docname;
		$arrFields[] = 'doc_spec';
		$arrValues[] = $docspec;
		$arrFields[] = 'ref_mail';
		$arrValues[] = $docmail;
		$arrFields[] = 'contact_num';
		$arrValues[] = $doccontact;		
		$arrFields[] = 'ref_web';
		$arrValues[] = $docweb;
		/*$arrFields[] = 'doc_gen';
		$arrValues[] = $docgend; */
		$arrFields[] = 'doc_age';
		$arrValues[] = $docage;
		$arrFields[] = 'doc_qual';
		$arrValues[] = $doctqual;
		$arrFields[] = 'doc_city';
		$arrValues[] = $doccity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $docstate;
		$arrFields[] = 'doc_country';
		$arrValues[] = $doccountry;
		$arrFields[] = 'ref_address';
		$arrValues[] = $doccity;
		$arrFields[] = 'cons_hosp_address1';
		$arrValues[] = $dochospAddress;
		$arrFields[] = 'ref_exp';
		$arrValues[] = $docexp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $docexpert;
		$arrFields[] = 'doc_research';
		$arrValues[] = $docresearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $doccontribute;
		
		$arrFields[] = 'doc_type';
		$arrValues[] = "volunteer";
		$arrFields[] = 'doc_type_val';
		$arrValues[] = "5";
		
		$arrFields[] = 'doc_pub';
		$arrValues[] = $docpublication;
		$arrFields[] = 'in_op_cost';
		$arrValues[] = $docinpercharge;
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $doconlinecharge;
		$arrFields[] = 'cons_charge';
		$arrValues[] = $docconscharge;
		$arrFields[] = 'doc_password';
		$arrValues[] = $doc_passwd;
		if(!empty($_FILES['txtPhoto']['name'])){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		
		$arrFields[] = 'tele_op';
		$arrValues[] = $teleOpCond;
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		$arrFields[] = 'video_op';
		$arrValues[] = $videoOpCond;
		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming;
		$arrFields[] = 'sponsor_id';
		$arrValues[] = "1"; //By default save it as Medisense Sponsors Code
		
		if($docgend == 1) {
			$arrFields[] = 'doc_gen';
			$arrValues[] = "Male";
		}
		else if($docgend == 2) {
			$arrFields[] = 'doc_gen';
			$arrValues[] = "Female";
		}
		
		
		$chkDoc = $objQuery->mysqlSelect("*","referal","ref_mail='".$docmail."' or contact_num='".$doccontact."'","","","","");
		if(empty($chkDoc)){
		//Create Organization
		$arrFields_org[] = 'company_name';
		$arrValues_org[] = $hospname;
		$arrFields_org[] = 'owner_name';
		$arrValues_org[] = $docname;
		$arrFields_org[] = 'company_addrs';
		$arrValues_org[] = $dochospAddress;
		$arrFields_org[] = 'email_id';
		$arrValues_org[] = $docmail;
		$arrFields_org[] = 'mobile';
		$arrValues_org[] = $doccontact;
		$arrFields_org[] = 'password';
		$arrValues_org[] = $doc_passwd;
		
		$compcreate=$objQuery->mysqlInsert('compny_tab',$arrFields_org,$arrValues_org);
		$companyid = mysql_insert_id();
		//Create Hospital
		$arrFields_hosp[] = 'company_id';
		$arrValues_hosp[] = $companyid;
	
		$hospcreate=$objQuery->mysqlInsert('hosp_tab',$arrFields_hosp,$arrValues_hosp);	
		$hospid = mysql_insert_id();	
		$usercreate=$objQuery->mysqlInsert('referal',$arrFields,$arrValues);
		$id = mysql_insert_id();
		
		//Create Doc Specialisation
		
		$arrFields_docspec[] = 'doc_id';
		$arrValues_docspec[] = $id;
		$arrFields_docspec[] = 'doc_type';
		$arrValues_docspec[] = "1";
		$arrFields_docspec[] = 'spec_id';
		$arrValues_docspec[] = $docspec;
		
		$dochospcreate=$objQuery->mysqlInsert('doc_specialization',$arrFields_docspec,$arrValues_docspec);		
		
		//Create Doc Hospital
		
		$arrFields_dochosp[] = 'doc_id';
		$arrValues_dochosp[] = $id;
		$arrFields_dochosp[] = 'hosp_id';
		$arrValues_dochosp[] = $hospid;
		
		$dochospcreate=$objQuery->mysqlInsert('doctor_hosp',$arrFields_dochosp,$arrValues_dochosp);	
		
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'council_name';
		$arrValues1[] = $council_name;
		$arrFields1[] = 'reg_num';
		$arrValues1[] = $reg_num;
		$arrFields1[] = 'reg_date';
		$arrValues1[] = $date_registration;
		$arrFields1[] = 'reg_certificate';
		$arrValues1[] = $uploadcertificate;
		$arrFields1[] = 'create_date';
		$arrValues1[] = $Cur_Date;
		
		$createReg=$objQuery->mysqlInsert('doctor_registration_details',$arrFields1,$arrValues1);
		
		//UPLOAD COMPRESSED IMAGE
		if ($_FILES["txtPhoto"]["error"] > 0) {
        			$error = $_FILES["txtPhoto"]["error"];
    		} 
    		else if (($_FILES["txtPhoto"]["type"] == "image/gif") || 
			($_FILES["txtPhoto"]["type"] == "image/jpeg") || 
			($_FILES["txtPhoto"]["type"] == "image/png") || 
			($_FILES["txtPhoto"]["type"] == "image/pjpeg")) {
			
			 $uploaddirectory = realpath("../Doc");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 
			 /*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			 
			 
        			$url = $uploaddir.'/'.$_FILES["txtPhoto"]["name"];

        			$filename = compress_image($_FILES["txtPhoto"]["tmp_name"], $url, 40);
        			$buffer = file_get_contents($url);

    		}else {
        			$error = "Uploaded image should be jpg or gif or png";
    		}
			
			//UPLOAD DOCTOR REGISTRATION CERTIFICATE
		if ($_FILES["uploadCertificate"]["error"] > 0) {
        			$error = $_FILES["uploadCertificate"]["error"];
    		} 
    		else if (($_FILES["uploadCertificate"]["type"] == "image/gif") || 
			($_FILES["uploadCertificate"]["type"] == "image/jpeg") || 
			($_FILES["uploadCertificate"]["type"] == "image/png") || 
			($_FILES["uploadCertificate"]["type"] == "image/pjpeg")) {
			
			 $uploaddirectory = realpath("../DocCertificate");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 
			 /*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			 
			 
        			$url = $uploaddir.'/'.$_FILES["uploadCertificate"]["name"];

        			$filename = compress_image($_FILES["uploadCertificate"]["tmp_name"], $url, 40);
        			$buffer = file_get_contents($url);

    		}else {
        			$error = "Uploaded image should be jpg or gif or png";
    		}
		
		//SEND SUCCESSFULL REGESTRATION NOTIFICATION TO DOCTORS
		/* $docmessage=stripslashes("Thank you for registering with Medisense Premium.<br><br>You have successfully completed your registration. Your account is now set and ready to go.<br><br>Please find the user credentials below");
		$doccredentials="Web Link: https://medisensecrm.com/Doctors <br>User Name: ".$docmail."<br><br>For more information about Premium, please visit our website http://www.medisensepractice.com/ <br>If you experience any problems running this application, contact us via email at medical@medisense.me<br><br>Sincerly,<br>Assistance team- Medisense Health Solutions Pvt Ltd<br>ph:1800-30005-206/ +917026646022";
		//$ccmail1="ambarish@medisense.me";
		//$ccmail2="shashi@medisense.me"; */
		
		//SEND SUCCESSFULL REGESTRATION NOTIFICATION TO DOCTORS
		$docmessage=stripslashes("Congratulations!<br>You’ve been granted access to “Practice” Electronic Medical Record (EMR) software powered by Medisense Healthcare.<br><br>Dear ".$docname."<br>To access “Practice” EMR software, kindly visit the link below:<br><br>
		Web Link: https://medisensecrm.com/premium/login<br><br>Use the following login credentials<br><br>");
		$doccredentials=stripslashes("<b>User Name:</b> ".$docmail." Or ".$doccontact."<br><b>Password:</b> ".$_POST['doc_passwd']."<br><br><b>Note: No installation required. You’re all set to use the EMR software instantly.</b><br><br> 
We have uploaded the training videos for you on youtube. These videos will introduce and explain the features of “Practice” EMR software.<br><br>
The same videos can be viewed by your staff/receptionist if they’ll be handling your appointments.<br><br>
<b>Introduction to Practice:</b> https://youtu.be/wrTOfRE5LEU <br>
<b>One time set up:</b> https://youtu.be/JcuIRMJASLQ <br>
<b>Appointments:</b> https://youtu.be/-n2QLS-YMrU<br>
<b>EMR:</b> https://youtu.be/1e4XowVxZR4<br>
<br>
Have any questions? You can call at Doctors Hotline number +91 8095555842.<br><br>
Regards,<br>
Shashidhar Pai<br>
Founder & CEO - Medisense Healthcare Solutions Pvt. Ltd.<br>
Mob (India) : +91 9880130842 <br>
Mob (USA) : +1 917 310 7984");
		
		$ccmail1="medisensedev@medisense.me,medical@medisense.me";
		$url_page = 'med_new_ref_notification.php';
					$url = rawurlencode($url_page);
					$url .= "?refname=".urlencode($ref_name);
					$url .= "&message=".urlencode($docmessage);
					$url .= "&reflink=".urlencode($doccredentials);
					$url .= "&ccmail1=".urlencode($docmail);
					$url .= "&ccmail2=".urlencode($ccmail1);
					send_mail($url);
		
		
		
		//SEND DOCTOR PROFILE UPDATION EMAIL NOTIFICATION TO MEDISENSE PANEL
		$message=stripslashes("This is a confirmation that the profile of <b>".$docname."</b> has just been created on <b>".$Cur_Date."</b><br><br>Please check doctor profile");
		$dochistory="Doctor Name:".$docname."<br>Exp. : ".$docexp."<br>Mobile: ".$doccontact."<br>Email Id: ".$docmail."<br>Communication Address: ".$dochosp."<br>City/State : ".$doccity.", ".$docstate.", ".$doccountry;
	//	$ccmail1="ambarish@medisense.me";
	//	$ccmail2="shashi@medisense.me";
		$ccmail1="medisensedev@medisense.me,medical@medisense.me";
		$url_page = 'med_new_ref_notification.php';
					$url = rawurlencode($url_page);
					$url .= "?refname=".urlencode($ref_name);
					$url .= "&message=".urlencode($message);
					$url .= "&reflink=".urlencode($dochistory);
					$url .= "&ccmail1=".urlencode($ccmail1);
					$url .= "&ccmail2=".urlencode($ccmail2);
					send_mail($url);
		
			//Send value to remote server
					
					$url_page = 'get_new_refer_val.php';
					
					$url = "https://medisensehealth.com/CRM/";
					$url .= rawurlencode($url_page);
					$post .= "&docid=" . urlencode($id);
					$post .= "&docname=" . urlencode($docname);
					$post .= "&docgend=" . urlencode($docgend);
					$post .= "&docage=" . urlencode($docage);
					$post .= "&doccity=" . urlencode($doccity);
					$post .= "&docstate=" . urlencode($docstate);
					$post .= "&doccountry=" . urlencode($doccountry);
					$post .= "&dochosp=" . urlencode($dochosp);
					$post .= "&docspec=" . urlencode($docspec);
					$post .= "&docqual=" . urlencode($doctqual);
					$post .= "&docexp=" . urlencode($docexp);
					$post .= "&docmobile=" . urlencode($doccontact);
					$post .= "&docemail=" . urlencode($docmail);
					$post .= "&docweb=" . urlencode($docweb);
					$post .= "&docinterest=" . urlencode($docexpert);
					$post .= "&doccontribute=" . urlencode($doccontribute);
					$post .= "&docresearch=" . urlencode($docresearch);
					$post .= "&docpublication=" . urlencode($docpublication);
					$post .= "&docimage=" . urlencode($docImage);
					$post .= "&inopcost=" . urlencode($docinpercharge);
					$post .= "&onopcost=" . urlencode($doconlinecharge);
					$post .= "&conscharge=" . urlencode($docconscharge);
					$post .= "&docpasswd=" . urlencode($doc_passwd);
					$post .= "&timestamp=" . urlencode($Cur_Date);
					
					$post .= "&teleOpCond=" . urlencode($teleOpCond);
					$post .= "&telecontact=" . urlencode($telecontact);
					$post .= "&videoOpCond=" . urlencode($videoOpCond);
					$post .= "&videocontact=" . urlencode($videocontact);
					$post .= "&teleoptiming=" . urlencode($teleoptiming);
					
					$post .= "&hospid=" . urlencode($hospid);
					$post .= "&hospname=" . urlencode($hospname);
					$post .= "&dochospAddress=" . urlencode($dochospAddress);
					$post .= "&hospphone=" . urlencode($hosp_phone);
					$post .= "&hospemail=" . urlencode($hosp_email);
				
					$ch = curl_init (); // setup a curl
					
					curl_setopt($ch, CURLOPT_URL, $url); // set url to send to
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
					
						
					$output = curl_exec ($ch);
					echo $output;
					// echo "output".$output;
					
					curl_close ( $ch );
					$sucessMessage="Updated Successfully";
					$respond=0;
					
					//SMS notification to Doctors
					if(!empty($doccontact)){
					$mobile = $doccontact;
					$msg = "Dear ".$docname."- We have sharing the user ID and password credentials of Practice EMR software with you. User Name: ".$docmail."  or  ".$doccontact."<br>Password: ".$_POST['doc_passwd']." Thanks";
					
					send_msg($mobile,$msg);
					
					}
					
				$success_referring = array('status' => "true",'register_status' => "success");
				echo json_encode($success_referring);
		}else{
			$respond=1;
				$success_referring = array('status' => "false",'register_status' => "failure");
				echo json_encode($success_referring);
		}
		
}
