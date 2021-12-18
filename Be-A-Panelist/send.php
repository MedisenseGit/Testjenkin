<?php 

ob_start();
error_reporting(0);
session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");

ob_start();
include('../send_mail_function.php');
include('../send_text_message.php');

// $strClientIP = $_SERVER['REMOTE_ADDR'];
// $ch = curl_init('http://api.ipstack.com/'.$strClientIP.'?access_key='.GEO_IP_API_KEY.'');
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $json = curl_exec($ch);
// curl_close($ch);
// // Decode JSON response:
// $api_result = json_decode($json, true);
// $country_name = $api_result['country_name'];  
// if(empty($country_name)) 
// {
// 	$country_name = 'India';			// This is applicable only if the free API has reached its max limit.
// }

$name = ''; $type = ''; $size = ''; $error = '';
function compress_image($source_url, $destination_url, $quality)
{

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


$doc_email             = addslashes($_POST['doc_email']);
$doc_Contact_num       = addslashes($_POST['Contact_num']);
$GetEmail = mysqlSelect("*","referal","ref_mail='".$doc_email."'  OR contact_num ='".$doc_Contact_num."' ","ref_id asc","","","");

//if(empty($GetEmail))
{
	if(isset($_POST['submit']))
	{
		
		$doc_first_name        =addslashes($_POST['first_name']); //general information     inserted table " reffereal "
		$doc_middle_name       =addslashes($_POST['middle_name']);
		$doc_last_name         =addslashes($_POST['last_name']);
		$doc_name              =$doc_first_name." ".$doc_middle_name." ".$doc_last_name;
		$docgend               =addslashes($_POST['gender']);
		
		if($docgend=="Male") { $docgend=0; }
		else if($docgend=="Female") { $docgend=1; }
		else { $docgend=2; }
		
		$doc_dob               =addslashes($_POST['dob']);
		$doc_email             =addslashes($_POST['doc_email']);
		$docexp      		   =addslashes($_POST['year_of_exp']);
		$doccountry 		   =addslashes($_POST['doc_country']);
		$docstate              =addslashes($_POST['doc_state']);
		$doccity               =addslashes($_POST['city']);
		$Country_code          =$_POST['Country_code'];
		$doc_Contact_num       =$_POST['Contact_num'];
		$alt_Country_code      =$_POST['alt_Country_code'];
		$doc_alt_Contact_num   =$_POST['alt_Contact_num'];
		$doc_address           =addslashes($_POST['address']);
		$doc_Country_of_origin =addslashes($_POST['country_of_origin']);
		$selected_country_id   =addslashes($_POST['selected_country_id']);
		$Area_of_interest                =addslashes($_POST['Area_of_interest']); //Other Information
		$Professional_Contribution       =addslashes($_POST['Professional_Contribution']);
		$Research_Details                =addslashes($_POST['Research_Details']);
		$Publications                    =addslashes($_POST['Publications']);
		$Password                        =addslashes($_POST['Password']);
		$passport_num                    =addslashes($_POST['passport_num']);
		$passport_country                =addslashes($_POST['passport_country']);
		$geo_latitude 					= 	$_POST['geo_latitude'];
		$geo_longitude 					= 	$_POST['geo_longitude'];
		
		//  ivc platform charge  
		if($selected_country_id == '100')
		{
			foreach($_POST['specialization'] as $key => $value)
			{
				if($value	== '18')
				{
					$arrFields[] = 'ivc_platform_charge_inr';
					$arrValues[] = '100';
				}
				else
				{
					$arrFields[] = 'ivc_platform_charge_inr';
					$arrValues[] = '200';
				}
			}
		}
		else if($selected_country_id == '179')
		{
			foreach($_POST['specialization'] as $key => $value)
			{
				if($value	== '18')
				{
					$arrFields[] = 'ivc_platform_charge_qar';
					$arrValues[] = '100';
				}
				else
				{
					$arrFields[] = 'ivc_platform_charge_qar';
					$arrValues[] = '200';
				}
			}
		}
		else
		{
			foreach($_POST['specialization'] as $key => $value)
			{
				if($value	== '18')
				{
					$arrFields[] = 'ivc_platform_charge_usd';
					$arrValues[] = '100';
				}
				else
				{
					$arrFields[] = 'ivc_platform_charge_usd';
					$arrValues[] = '200';
				}
			}

		}

		
		$arrFields[] = 'ref_name';
		$arrValues[] = $doc_name;
		
		$arrFields[] = 'doc_gen';
		$arrValues[] = $docgend;
		
		$arrFields[] = 'doc_dob';
		$arrValues[] = $doc_dob;
		
		$arrFields[] = 'ref_mail';
		$arrValues[] = $doc_email ;
		
		$arrFields[] = 'ref_exp';
		$arrValues[] = $docexp;
		
		$arrFields[] = 'doc_country';
		$arrValues[] = $doccountry;
		
		$arrFields[] = 'doc_state';
		$arrValues[] = $docstate;
		
		$arrFields[] = 'doc_city';
		$arrValues[] = $doccity;
		
		$arrFields[] = 'contact_num_extension';
		$arrValues[] = $Country_code;
		
		$arrFields[] = 'contact_num';
		$arrValues[] = $doc_Contact_num;
		
		$arrFields[] = 'secondary_contact_num_extension';
		$arrValues[] = $alt_Country_code;
		
		$arrFields[] = 'secondary_contact_num';
		$arrValues[] = $doc_alt_Contact_num;
		
		$arrFields[] = 'ref_address';
		$arrValues[] = $doc_address;
		
		$arrFields[] = 'doc_country_id';
		$arrValues[] = $selected_country_id;
		
		$arrFields[] = 'country_of_origin';
		$arrValues[] = $doc_Country_of_origin;
	
		$arrFields[] = 'doc_interest';
		$arrValues[] = $Area_of_interest;
		
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $Professional_Contribution;
		
		$arrFields[] = 'doc_research';
		$arrValues[] = $Research_Details;
		
		$arrFields[] = 'doc_pub';
		$arrValues[] = $Publications;
		
		$arrFields[] = 'doc_password';
		$arrValues[] = md5($Password);
		
		$arrFields[] = 'passport_num';
		$arrValues[] = $passport_num;
		
		$arrFields[] = 'passport_country';
		$arrValues[] = $passport_country;
		
		$arrFields[] = 'cons_charge_currency_type';
		$arrValues[] = 'QAR';
		
		$arrFields[] = 'cons_charge';
		$arrValues[] = '50';
		
		$arrFields[] = 'physical_consultation_charge';
		$arrValues[] = '70';
		
		$arrFields[] = 'geo_latitude';
		$arrValues[] = $geo_latitude;
		
		$arrFields[] = 'geo_longitude';
		$arrValues[] = $geo_longitude;
		
		$docImage =$_POST['txtPhoto'];
		$docImage =$_FILES['txtPhoto']['name'];
		
		if(!empty($_FILES['txtPhoto']['name']))
		{
			$arrFields[] = 'doc_photo';
			$arrValues[] = $docImage;
		}
		if(!empty($_FILES['txtProfessional_Contribution_file']['name']))
		{
			$arrFields[] = 'Professional_Construction_file';
			$arrValues[] = $_FILES['txtProfessional_Contribution_file']['name'];
		}
		if(!empty($_FILES['txtResearch_Details_file']['name']))
		{
			$arrFields[] = 'Research_Details_file';
			$arrValues[] = $_FILES['txtResearch_Details_file']['name'];
		}
		if(!empty($_FILES['txtPublications_file']['name']))
		{
			$arrFields[] = 'Publications_file';
			$arrValues[] = $_FILES['txtResearch_Details_file']['name'];
		}
		if(!empty($_FILES['txtpassport_file']['name']))
		{
			$arrFields[] = 'txtpassport_file';
			$arrValues[] = $_FILES['txtpassport_file']['name'];
		}
		$arrFields[] = 'sponsor_id';
		$arrValues[] = "1"; //By default save it as Medisense Sponsors Code
		$postResult  = mysqlSelect("*","newcitylist","city_name LIKE '%".$doccity."%'","","","","");
		if(!empty($postResult)) 
		{
			$city_id = $postResult[0]['city_id'];
			if(empty($postResult[0]['latitude']) ||  empty($postResult[0]['longitude']))
			{
				$geo_latitude = number_format($geo_latitude, 2);
				$geo_longitude = number_format($geo_longitude, 2);
				
				$arrFiedCity	=	array();
				$arrValueCity	=	array();
			
				$arrFiedCity[]	=	'latitude';
				$arrValueCity[]	=	$geo_latitude." N";
				
				$arrFiedCity[]	=	'longitude';
				$arrValueCity[]	=	$geo_longitude." E";
				
				$insert_city	=	mysqlUpdate('newcitylist',$arrFiedCity,$arrValueCity,"city_id='".$city_id."'");
				
			}
		}
		else
		{
			$arrFiedCity=array();
			$arrValueCity=array();
			
			$arrFiedCity[]	=	'city_name';
			$arrValueCity[]	=	$doccity;
			
			$arrFiedCity[]	=	'state';
			$arrValueCity[]	=	$docstate;
			
			$arrFiedCity[]	=	'latitude';
			$arrValueCity[]	=	$geo_latitude." N";
				
			$arrFiedCity[]	=	'longitude';
			$arrValueCity[]	=	$geo_longitude." E";
		
			$insert_city	=	mysqlInsert('newcitylist',$arrFiedCity,$arrValueCity);
			$city_id 		= $insert_city;
		}
		$arrFields[]	=	'doc_new_city';
		$arrValues[]	=	$city_id;
		
		$usercreate=mysqlInsert('referal',$arrFields,$arrValues); // Insert general information
		$id = $usercreate;
		
		$companyid	=	413;	//default medisense compay id
		$hospid 	=	1093; 	//default hospital  ID 	
		
		//Create Doc Hospital
		$arrFields_dochosp[] = 'doc_id';
		$arrValues_dochosp[] = $id;

		$arrFields_dochosp[] = 'hosp_id';
		$arrValues_dochosp[] = $hospid;
		
		$dochospcreate=mysqlInsert('doctor_hosp',$arrFields_dochosp,$arrValues_dochosp);	
		
		
		while(list($key_invest, $value_invest) = each($_POST['Type_of_qualification']))//ACADEMIC INFORMATION INSERT 
		{
			$arrFieldAcd=array();
			$arrValueAcd=array();
			
			$arrFieldAcd[]='doc_id';
			$arrValueAcd[]=$id;
			
			$arrFieldAcd[]='qualification_type';
			$arrValueAcd[]=$_POST['Type_of_qualification'][$key_invest];
			
			$arrFieldAcd[]='country';
			$arrValueAcd[]=$_POST['acd_doc_country'][$key_invest];
			
			$arrFieldAcd[]='city';
			$arrValueAcd[]=$_POST['acd_City'][$key_invest];
			
			$arrFieldAcd[]='start_date';
			$arrValueAcd[]=$_POST['acd_Start_Date'][$key_invest];
			
			$arrFieldAcd[]='end_date';
			$arrValueAcd[]=$_POST['acd_End_Date'][$key_invest];
			
			$insert_trend_analysis= mysqlInsert('doctor_academics',$arrFieldAcd,$arrValueAcd);
		}
	
		
		while(list($key_invest, $value_invest) = each($_POST['Institution_Name']))//work history INFORMATION INSERT 
		{
			$arrFieldWrk=array();
			$arrValueWrk=array();
			
			$arrFieldWrk[]='doc_id';
			$arrValueWrk[]=$id;
			
			$arrFieldWrk[]='Institution_Name';
			$arrValueWrk[]=$_POST['Institution_Name'][$key_invest];
			
			$arrFieldWrk[]='work_type';
			$arrValueWrk[]=$_POST['work_type'][$key_invest];
			
			$arrFieldWrk[]='Communication_Address';
			$arrValueWrk[]=$_POST['Communication_Address'][$key_invest];
			
			$arrFieldWrk[]='Phone_Number';
			$arrValueWrk[]=$_POST['Phone_Number'][$key_invest];
			
			$arrFieldWrk[]='phone_num_extension';
			$arrValueWrk[]=$_POST['Phone_Country_code'][$key_invest];
			
			$arrFieldWrk[]='work_Start_Date';
			$arrValueWrk[]=$_POST['work_Start_Date'][$key_invest];
			
			$arrFieldWrk[]='work_End_Date';
			$arrValueWrk[]=$_POST['work_End_Date'][$key_invest];
			
			$insert_trend_analysis= mysqlInsert('doc_work_exp',$arrFieldWrk,$arrValueWrk);
		}
		
		
		while(list($key_invest, $value_invest) = each($_POST['Medical_Council_reg']))//Registration INFORMATION INSERT 
		{
			$fname1=time();
			$arrFieldReg=array();
			$arrValueReg=array();
			
			$arrFieldReg[]='doc_id';
			$arrValueReg[]=$id;
			
			$arrFieldReg[]='council_name';
			$arrValueReg[]=$_POST['Medical_Council_reg'][$key_invest];
			
			$arrFieldReg[]='reg_num';
			$arrValueReg[]=$_POST['Reg_Num'][$key_invest];
			
			$reg_attachment = $fname1."_". basename($_FILES['txtUpload_Reg_cer']['name'][$key_invest]);
			if(!empty($_FILES["txtUpload_Reg_cer"][name][$key_invest]))
			{
				$arrFieldReg[]='reg_certificate';
				$arrValueReg[]=$reg_attachment;
			}
			
			$arrFieldReg[]='reg_date';
			$arrValueReg[]=$_POST['Registration_Date'][$key_invest];
			
			$insert_trend_analysis	= mysqlInsert('doctor_registration_details',$arrFieldReg,$arrValueReg);
			$reg_id = $insert_trend_analysis;
			
			if(!empty($_FILES["txtUpload_Reg_cer"]["name"]))
			{
				$folder_name	=	"DocCertificate";
				$sub_folder		=	$id;
				$filename		=	$reg_attachment;
				$file_url		=	$_FILES['txtUpload_Reg_cer']['tmp_name'][$key_invest];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
			}			
		}
		//Create Doc Specialisation
		$doc_specialization    = addslashes($_POST['specialization']);
		foreach($_POST['specialization'] as $key => $value)
		{
			$arrFields_spe = array();
			$arrValues_spe = array();

			$arrFields_spe[] = 'doc_id';
			$arrValues_spe[] = $id;

			$arrFields_spe[] = 'doc_type';
			$arrValues_spe[] = "1";
			
			$arrFields_spe[] = 'spec_id';
			$arrValues_spe[] = $value;
			
			$insert_spec	=	mysqlInsert('doc_specialization',$arrFields_spe,$arrValues_spe);
		}
		
		//Create Consultation_lang
		$consult_lang = addslashes($_POST['consult_lang']);
		foreach($_POST['consult_lang'] as $key => $values)
		{
			$arrFields_lang = array();
			$arrValues_lang = array();

			$arrFields_lang[] = 'doc_id';
			$arrValues_lang[] = $id;

			$arrFields_lang[] = 'language_id';
			$arrValues_lang[] = $values;
			
			$insert_spec=mysqlInsert('doctor_langauges',$arrFields_lang,$arrValues_lang);
		}
		
		if(!empty($_FILES["txtPhoto"]["name"]))                    // PROFILE IMAGE UPLOAD
		{
			$folder_name	=	"Doc";
			$sub_folder		=	$id;
			$filename		=	$_FILES['txtPhoto']['name'];
			$file_url		=	$_FILES['txtPhoto']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
		}	
		if(!empty($_FILES["txtProfessional_Contribution_file"]["name"]))
		{
			$folder_name	=	"Doc_Prof_Certificate";
			$sub_folder		=	$id;
			$filename		=	$_FILES['txtProfessional_Contribution_file']['name'];
			$file_url		=	$_FILES['txtProfessional_Contribution_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
		}
		if(!empty($_FILES["txtResearch_Details_file"]["name"]))
		{
			$folder_name	=	"Doc_Research_Certificate";
			$sub_folder		=	$id;
			$filename		=	$_FILES['txtResearch_Details_file']['name'];
			$file_url		=	$_FILES['txtResearch_Details_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
		}
		if(!empty($_FILES["txtPublications_file"]["name"]))
		{
			$folder_name	=	"Doc_Public_certificate";
			$sub_folder		=	$id;
			$filename		=	$_FILES['txtPublications_file']['name'];
			$file_url		=	$_FILES['txtPublications_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
		}
		if(!empty($_FILES["txtpassport_file"]["name"]))
		{
			$folder_name	=	"Doc_passport_file";
			$sub_folder		=	$id;
			$filename		=	$_FILES['txtpassport_file']['name'];
			$file_url		=	$_FILES['txtpassport_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
		}
	
	
	//SEND SUCCESSFULL REGESTRATION NOTIFICATION TO DOCTORS
		/*$docmessage=stripslashes("Congratulations!<br>You’ve been granted access to “Practice” Electronic Medical Record (EMR) software powered by Medisense Healthcare.<br><br>Dear ".$doc_name."<br>To access “Practice” EMR software, kindly visit the link below:<br><br>
		Web Link: ".HOST_MAIN_URL."premium/login<br><br>Use the following login credentials<br><br>");
		$doccredentials=stripslashes("<b>User Name:</b> ".$doc_email." Or ".$doccontact."<br><b>Password:</b> ".$_POST['Password']."<br><br><b>Note: No installation required. You’re all set to use the EMR software instantly.</b><br><br> 
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
		$ccmail1="medisensebd@medisense.me";
		
		
		$url_page = 'med_new_ref_notification.php';
		if(!empty($doc_email))
		{
			
			$url = rawurlencode($url_page);
			$url .= "?refname=".urlencode($doc_name);
			$url .= "&message=".urlencode($docmessage);
			$url .= "&reflink=".urlencode($doccredentials);
			$url .= "&ccmail1=".urlencode($doc_email);
			//$url .= "&ccmail2=".urlencode($ccmail1);
			send_mail($url);
		}
			$url = rawurlencode($url_page);
			$url .= "?refname=".urlencode($doc_name);
			$url .= "&message=".urlencode($docmessage);
			$url .= "&reflink=".urlencode($doccredentials);
			$url .= "&ccmail1=".urlencode($ccmail1);
			$url .= "&ccmail2=".urlencode($fdcCCMail);
			send_mail($url);
*/

		//SEND DOCTOR PROFILE UPDATION EMAIL NOTIFICATION TO MEDISENSE PANEL
		$message=stripslashes("This is a confirmation that the profile of <b>".$doc_name."</b> has just been created on <b>".$Cur_Date."</b><br><br>Please check doctor profile");
		$dochistory="Doctor Name:".$doc_name."<br>Exp. : ".$docexp."<br>Mobile: ".$doccontact."<br>Email Id: ".$doc_email."<br>Communication Address: ".$dochosp."<br>City/State : ".$doccity.", ".$docstate.", ".$doccountry;
		$ccmail1="medisensebd@medisense.me";
		//$ccmail2="shashi@medisense.me";
		$url_page = 'med_new_ref_notification.php';
					$url = rawurlencode($url_page);
					$url .= "?refname=".urlencode($ref_name);
					$url .= "&message=".urlencode($message);
					$url .= "&reflink=".urlencode($dochistory);
					$url .= "&ccmail1=".urlencode($ccmail1);
					$url .= "&ccmail2=".urlencode($ccmail2);
					send_mail($url);
		
			//Send value to remote server
					$docweb="";
					$doccontribute="";
					$docresearch="";
					$docinpercharge="";
					$doconlinecharge="";
					$docconscharge="";
					$teleOpCond="";
					$telecontact="";
					$videoOpCond="";
					$videocontact="";
					$teleoptiming="";
					
					
					$url_page = 'get_new_refer_val.php';
					
					$url = HOST_HEALTH_URL."CRM/";
					$url .= rawurlencode($url_page);
					$post .= "&docid=" . urlencode($id);
					$post .= "&docname=" . urlencode($doc_name);
					$post .= "&docgend=" . urlencode($docgend);
					$post .= "&docage=" . urlencode($docage);
					$post .= "&doccity=" . urlencode($doccity);
					$post .= "&docstate=" . urlencode($docstate);
					$post .= "&doccountry=" . urlencode($doccountry);
					$post .= "&dochosp=" . urlencode($dochosp);
					//$post .= "&docspec=" . urlencode($docspec);
					$post .= "&docqual=" . urlencode($doctqual);
					$post .= "&docexp=" . urlencode($docexp);
					$post .= "&docmobile=" . urlencode($doccontact);
					$post .= "&docemail=" . urlencode($doc_email);
					$post .= "&docweb=" . urlencode($docweb);
					$post .= "&docinterest=" . urlencode($docexpert);
					$post .= "&doccontribute=" . urlencode($doccontribute);
					$post .= "&docresearch=" . urlencode($docresearch);
					$post .= "&docpublication=" . urlencode($Publications);
					$post .= "&docimage=" . urlencode($docImage);
					$post .= "&inopcost=" . urlencode($docinpercharge);
					$post .= "&onopcost=" . urlencode($doconlinecharge);
					$post .= "&conscharge=" . urlencode($docconscharge);
					$post .= "&docpasswd=" . urlencode($Password);
					$post .= "&timestamp=" . urlencode($Cur_Date);
					
					$post .= "&teleOpCond=" . urlencode($teleOpCond);
					$post .= "&telecontact=" . urlencode($telecontact);
					$post .= "&videoOpCond=" . urlencode($videoOpCond);
					$post .= "&videocontact=" . urlencode($videocontact);
					$post .= "&teleoptiming=" . urlencode($teleoptiming);
					
					$post .= "&hospid=" . urlencode($hospid);
					//$post .= "&hospname=" . urlencode($hospname);
					//$post .= "&dochospAddress=" . urlencode($dochospAddress);
					//$post .= "&hospphone=" . urlencode($hosp_phone);
					//$post .= "&hospemail=" . urlencode($hosp_email);
				
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
					
					
					//SMS notification to Doctors
					if(!empty($doccontact))
					{
						$mobile = $doccontact;
						$msg = "Dear ".$docname."- We have sharing the user ID and password credentials of Practice EMR software with you. User Name: ".$doc_email."  or  ".$doccontact."<br>Password: ".$_POST['Password']." Thanks";
						
						send_msg($mobile,$msg);
						
					}
					
					
		$respond=0;
		$new_id=$id;
		header('Location:index.php?respond='.$respond.'&ency_id='.$_POST['docEncyId']);
	}
}	
// else
// {
// 	$respond=1;
// 	header('Location:index.php?respond='.$respond.'&ency_id='.$_POST['docEncyId']);
// }

?>