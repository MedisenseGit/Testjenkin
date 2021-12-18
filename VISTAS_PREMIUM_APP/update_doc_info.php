<?php

ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");

$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata 	= 	$_POST;
$finalHash 	= 	checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

// if(!empty($doctor_id) && !empty($finalHash)) 
// {	

	// if($finalHash == $hashKey) 
	// {
		$doc_id =  $doctor_id;
		
		$doctor_registration = mysqlSelect("*","referal","ref_id='".$doc_id."'","","","");
		if(!empty($doctor_registration))
		{
	
			$doc_first_name        			=	addslashes($_POST['first_name']); //general information     inserted table " reffereal "
			$doc_middle_name       			=	addslashes($_POST['middle_name']);
			$doc_last_name        			=	addslashes($_POST['last_name']);
			$doc_name              			=	$doc_first_name." ".$doc_middle_name." ".$doc_last_name;
			$docgend               			=	$_POST['gender'];
			if($docgend=="")
			{
				$docgend=0;
			}
			$doc_dob              		 	=	$_POST['dob'];
			$doc_email            		 	=	addslashes($_POST['doc_email']);
			//$doc_specialization   		 	=	addslashes($_POST['specialization']);
			$docexp       		   			=	$_POST['year_of_exp'];
			$doc_Consultation_lang		 	=	addslashes($_POST['Consultation_lang']);
			$doc_Contact_num      			=	$_POST['Contact_num'];
			$Country_code         			=	$_POST['Country_code'];
			$alt_Country_code     			=	$_POST['Alt_Country_code'];
			$doc_alt_Contact_num  			=	$_POST['alt_Contact_num'];
			$doc_address         			=	addslashes($_POST['address']);
			$doccity              			=	addslashes($_POST['city']);
			$docstate            			=	addslashes($_POST['doc_state']);
			$doccountry 		   			=	addslashes($_POST['doc_country']);
			$doc_country_id   				=	addslashes($_POST['doc_country_id']);
			$doc_Country_of_origin 			=	addslashes($_POST['country_of_origin']);
			$Area_of_interest               =	addslashes($_POST['Area_of_interest']); //Other Information
			$Professional_Contribution      =	addslashes($_POST['Professional_Contribution']);
			$Professional_Construction_file =	addslashes($_POST['Professional_Construction_file']);//file
			$Research_Details               =	addslashes($_POST['Research_Details']);
			$Research_Details_file          =	addslashes($_POST['Research_Details_file']);//file
			$Publications                   =	addslashes($_POST['Publications']);
			$Publications_file              =	addslashes($_POST['Publications_file']);
			$Password                       =	addslashes($_POST['Password']);
			$Confirm_Password               =	addslashes($_POST['Confirm_Password']);
			$passport_country 				= 	$_POST['passport_country'];
			$passport_num 					= 	$_POST['passport_num'];
			$consult_lang 					= 	$_POST['consult_lang'];
			$geo_latitude 					= 	$_POST['geo_latitude'];
			$geo_longitude 					= 	$_POST['geo_longitude'];
			
			if(($_POST['first_name']!="") || ($_POST['middle_name']!="") || ($_POST['last_name']!=""))
			{
				
				$arrFields[] = 'ref_name';
				$arrValues[] = $doc_name;
			}
			if($docgend!="")
			{			
				$arrFields[] = 'doc_gen';
				$arrValues[] = $docgend;
			}
			if($doc_dob!="")
			{
				$arrFields[] = 'doc_dob';
				$arrValues[] = $doc_dob;
			}
			if($doc_email!="")
			{
				$arrFields[] = 'ref_mail';
				$arrValues[] = $doc_email ;
			}
			/*if($doc_specialization!="")
			{
				$arrFields[] = 'doc_spec';
				$arrValues[] = $doc_specialization;
			}*/
			if($docexp!="")
			{
				$arrFields[] = 'ref_exp';
				$arrValues[] = $docexp;
			}
			if($Country_code!="")
			{
				$arrFields[] = 'contact_num_extension';
				$arrValues[] = $Country_code;
			}
			if($doc_Contact_num!="")
			{
				$arrFields[] = 'contact_num';
				$arrValues[] = $doc_Contact_num;
			}
			if($alt_Country_code!="")
			{
				$arrFields[] = 'secondary_contact_num_extension';
				$arrValues[] = $alt_Country_code;
			}
			if($doc_alt_Contact_num!="")
			{
				$arrFields[] = 'secondary_contact_num';
				$arrValues[] = $doc_alt_Contact_num;
			}
			if($doc_address!="")
			{
				$arrFields[] = 'ref_address';
				$arrValues[] = $doc_address;
			}
			if($doccity!="")
			{
				$arrFields[] = 'doc_city';
				$arrValues[] = $doccity;
			}
			if($docstate!="")
			{
				$arrFields[] = 'doc_state';
				$arrValues[] = $docstate;
			}
			if($doccountry!="")
			{
				$arrFields[] = 'doc_country';
				$arrValues[] = $doccountry;
			}
			if($doc_country_id!="")
			{
				$arrFields[] = 'doc_country_id';
				$arrValues[] = $doc_country_id;
			}
			if($doc_Country_of_origin!="")
			{
				$arrFields[] = 'country_of_origin';
				$arrValues[] = $doc_Country_of_origin;
			}
			if($Area_of_interest!="")
			{
				$arrFields[] = 'doc_interest';
				$arrValues[] = $Area_of_interest;
			}
			if($Professional_Contribution!="")
			{
				$arrFields[] = 'doc_contribute';
				$arrValues[] = $Professional_Contribution;
			}
			if($Research_Details!="")
			{
				$arrFields[] = 'doc_research';
				$arrValues[] = $Research_Details;
			}
			if($Publications!="")
			{
				$arrFields[] = 'doc_pub';
				$arrValues[] = $Publications;
			}
			if($passport_num!="")
			{
				$arrFields[] = 'passport_num';
				$arrValues[] = $passport_num;
			}
			if($passport_country!="")
			{
				$arrFields[] = 'passport_country';
				$arrValues[] = $passport_country;
			}
			if($geo_latitude!="")
			{
				$arrFields[] = 'geo_latitude';
				$arrValues[] = $geo_latitude;
			}
			if($geo_longitude!="")
			{
				$arrFields[] = 'geo_longitude';
				$arrValues[] = $geo_longitude;
			}
			if($_FILES['txtPhoto']['name']!="")
			{
				$docImage =	$_POST['txtPhoto'];
				$docImage =	$_FILES['txtPhoto']['name'];
			}
			if($_FILES['txtPhoto']['name']!="")
			{
				$arrFields[] = 'doc_photo';
				$arrValues[] = $docImage;
			}
			//Professional_Contribution
			
			
			if(!empty($_FILES['txtProfessional_Coontribution_file']['name']))
			{
				$arrFields[] = 'Professional_Construction_file';
				$arrValues[] = $_FILES['txtProfessional_Coontribution_file']['name'];
			}
			if(!empty($_FILES['txtResearch_Details_file']['name']))
			{
				$arrFields[] = 'Research_Details_file';
				$arrValues[] = $_FILES['txtResearch_Details_file']['name'];
			}
			if(!empty($_FILES['txtPublications_file']['name']))
			{
				$arrFields[] = 'Publications_file';
				$arrValues[] = $_FILES['txtPublications_file']['name'];
			}
			
			if(!empty($_FILES['txtpassport_file']['name']))
			{
				$arrFields[] = 'txtpassport_file';
				$arrValues[] = $_FILES['txtpassport_file']['name'];
			}
			
			$postResult = mysqlSelect("*","newcitylist","city_name LIKE '%".$doccity."%'","","","","");
			if(!empty($postResult)) 
			{
				$city_id = $postResult[0]['city_id'];
			
				if(empty($postResult[0]['latitude']) ||  empty($postResult[0]['longitude']))
				{
				
					$geo_latitude  = number_format($geo_latitude, 2);
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
				$city_id 		=   $insert_city;
			}
			if($city_id!="")
			{
				$arrFields[]	=	'doc_new_city';
				$arrValues[]	=	$city_id;
			}
			
			
			$usercreate		=	mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$doc_id."'");
			$companyid		=	413;	//default medisense compay id
			$hospid 		=	1093; //default hospital  ID 	
			if(!empty($_POST['Type_of_qualification']))
			{
				while(list($key_invest, $value_invest) = each($_POST['Type_of_qualification']))//ACADEMIC INFORMATION 
				{
				
					$arrFieldAcd	=	array();
					$arrValueAcd	=	array();
					$id				=	$_POST['id'][$key_invest];
					$arrFieldAcd[]	=	'qualification_type';
					$arrValueAcd[]	=	$_POST['Type_of_qualification'][$key_invest];
					$arrFieldAcd[]	=	'country';
					$arrValueAcd[]	=	$_POST['acd_doc_country'][$key_invest];
					$arrFieldAcd[]	=	'city';
					$arrValueAcd[]	=	$_POST['acd_City'][$key_invest];
					$arrFieldAcd[]	=	'start_date';
					$arrValueAcd[]	=	$_POST['acd_Start_Date'][$key_invest];
					$arrFieldAcd[]	=	'end_date';
					$arrValueAcd[]	=	$_POST['acd_End_Date'][$key_invest];
					if($id==0)
					{
						$arrFieldAcd[]		=	'doc_id';
						$arrValueAcd[]		=	$doc_id;
						$insert_doctor_reg	= 	mysqlInsert('doctor_academics',$arrFieldAcd,$arrValueAcd);
						$reg_id				= 	$insert_doctor_reg;
					}
					else
					{
						$reg_id			= $id;
						$update_doc_reg	= mysqlUpdate('doctor_academics',$arrFieldAcd,$arrValueAcd,"id='".$reg_id."'");
					}
				}
			}
			if(!empty($_POST['Institution_Name']))
			{
				while(list($key_invest, $value_invest) = each($_POST['Institution_Name']))//work history INFORMATION INSERT 
				{
					$arrFieldWrk	=	array();
					$arrValueWrk	=	array();
					$id				=	$_POST['wrk_id'][$key_invest];
					$arrFieldWrk[]	=	'Institution_Name';
					$arrValueWrk[]	=	$_POST['Institution_Name'][$key_invest];
					$arrFieldWrk[]	=	'work_type';
					$arrValueWrk[]	=	$_POST['work_type'][$key_invest];
					$arrFieldWrk[]	=	'Communication_Address';
					$arrValueWrk[]	=	$_POST['Communication_Address'][$key_invest];
					$arrFieldWrk[]	=	'Phone_Number';
					$arrValueWrk[]	=	$_POST['Phone_Number'][$key_invest];
					$arrFieldWrk[]	=	'phone_num_extension';
					$arrValueWrk[]	=	$_POST['Phone_Country_code'][$key_invest];
					$arrFieldWrk[]	=	'work_Start_Date';
					$arrValueWrk[]	=	$_POST['work_Start_Date'][$key_invest];
					$arrFieldWrk[]	=	'work_End_Date';
					$arrValueWrk[]	=	$_POST['work_End_Date'][$key_invest];
			
					if($id==0)
					{
						$arrFieldWrk[]	=	'doc_id';
						$arrValueWrk[]	=	$doc_id;
						$insert_workexp	= 	mysqlInsert('doc_work_exp',$arrFieldWrk,$arrValueWrk);
						$reg_id			=	$insert_workexp;//$insert_workexp;
					}
					else
					{
						$reg_id	= $id;
						$update_workexp = mysqlUpdate('doc_work_exp',$arrFieldWrk,$arrValueWrk,"id='".$reg_id."'");
					}
				}
			}	
			$Medical_Council_reg   =	addslashes($_POST['Medical_Council_reg']); //Registration History
			$Reg_Num               =	$_POST['Reg_Num'];
			$Upload_Reg_cer        =	$_POST['txtUpload_Reg_cer']; //file
			if(!empty($_POST['Medical_Council_reg']))
			{
				while(list($key_invest, $value_invest) = each($_POST['Medical_Council_reg']))//Registration INFORMATION INSERT 
				{
					$fname1			=	time();	
					$arrFieldReg	=	array();
					$arrValueReg	=	array();
					
					$reg_det_id		=	$_POST['reg_det_id'][$key_invest];
					
					$arrFieldReg[]	=	'council_name';
					$arrValueReg[]	=	$_POST['Medical_Council_reg'][$key_invest];
					
					$arrFieldReg[]	=	'reg_num';
					$arrValueReg[]	=	$_POST['Reg_Num'][$key_invest];
					$reg_attachment = 	$fname1."_". basename($_FILES['txtUpload_Reg_cer']['name'][$key_invest]);
		
					if(!empty($_FILES["txtUpload_Reg_cer"][name][$key_invest]))
					{
						$arrFieldReg[]	=	'reg_certificate';
						$arrValueReg[]	=	$reg_attachment;
					}
					$arrFieldReg[]	=	'reg_date';
					$arrValueReg[]	=	$_POST['Registration_Date'][$key_invest];
					
					if($reg_det_id==0)
					{
						$arrFieldReg[]		=	'doc_id';
						$arrValueReg[]		=	$doc_id;
						$insert_doctor_reg	= 	mysqlInsert('doctor_registration_details',$arrFieldReg,$arrValueReg);
						$reg_id				=	$insert_doctor_reg;
					}
					else
					{
							$reg_id			= $reg_det_id;
							$update_doc_reg	= mysqlUpdate('doctor_registration_details',$arrFieldReg,$arrValueReg,"reg_det_id='".$reg_id."'");
					}
					
					if(!empty($_FILES["txtUpload_Reg_cer"]["name"]))
					{
						$folder_name	=	"DocCertificate";
						$sub_folder		=	$doc_id;
						$filename		=	$_FILES['txtUpload_Reg_cer']['name'];
						$file_url		=	$_FILES['txtUpload_Reg_cer']['tmp_name'];
						fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
					}			
				}
			}
		
			//Create Doc Specialisation
			$doc_specialization    =($_POST['specialization']);
			if(!empty($_POST['specialization']))
			{
				$Spc_name =  mysqlDelete('doc_specialization',"doc_id='".$doc_id."'");
				foreach($_POST['specialization'] as $key => $value)
				{
							
					$arrFields_spe = array();
					$arrValues_spe = array();

					$arrFields_spe[] = 'doc_id';
					$arrValues_spe[] = $doc_id;

					$arrFields_spe[] = 'doc_type';
					$arrValues_spe[] = "1";
					
					$arrFields_spe[] = 'spec_id';
					$arrValues_spe[] = $value;
					
					$insert_spec	=	mysqlInsert('doc_specialization',$arrFields_spe,$arrValues_spe);
					
				}
						
			}
		
			//Create Consultation_lang
			$consult_lang = ($_POST['consult_lang']);
			if(!empty($_POST['consult_lang']))
			{
				$Spc_name	=  mysqlDelete('doctor_langauges',"doc_id='".$doc_id."'");
				foreach($_POST['consult_lang'] as $key => $values)
				{
						$arrFields_lang = array();
						$arrValues_lang = array();

						$arrFields_lang[] = 'doc_id';
						$arrValues_lang[] = $doc_id;

						$arrFields_lang[] = 'language_id';
						$arrValues_lang[] = $values;
						
						$insert_spec	=	mysqlInsert('doctor_langauges',$arrFields_lang,$arrValues_lang);
				}
			}
		
	
			if(!empty($_FILES["txtPhoto"]["name"]))
			{
				$folder_name	=	"Doc";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtPhoto']['name'];
				$file_url		=	$_FILES['txtPhoto']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
				
				
			}
			
			//UPLOAD Professional_Construction_file CERTIFICATE
		
			if(!empty($_FILES["txtProfessional_Coontribution_file"]["name"]))
			{
				
				
				$folder_name	=	"Doc_Prof_Certificate";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtProfessional_Coontribution_file']['name'];
				$file_url		=	$_FILES['txtProfessional_Coontribution_file']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
				
			}
	
			//UPLOAD Research_Details_file CERTIFICATE 
		
			if(!empty($_FILES["txtResearch_Details_file"]["name"]))
			{
				$folder_name	=	"Doc_Research_Certificate";
				$sub_folder		=	$doc_id;
				$filename		=	$_FILES['txtResearch_Details_file']['name'];
				$file_url		=	$_FILES['txtResearch_Details_file']['tmp_name'];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 
			}

		//UPLOAD Publications_file CERTIFICATE 
		
		if(!empty($_FILES["txtPublications_file"]["name"]))
		{
			$folder_name	=	"Doc_Public_certificate";
			$sub_folder		=	$doc_id;
			$filename		=	$_FILES['txtPublications_file']['name'];
			$file_url		=	$_FILES['txtPublications_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 

		}
	
		//UPLOAD Professional_Construction_file CERTIFICATE
	
		if(!empty($_FILES["txtpassport_file"]["name"]))
		{
			$folder_name	=	"Doc_passport_file";
			$sub_folder		=	$doc_id;
			$filename		=	$_FILES['txtpassport_file']['name'];
			$file_url		=	$_FILES['txtpassport_file']['tmp_name'];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload 

			
		}
		
		$success = array('status' => "true");
		echo json_encode($success);
	
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
					$ch = curl_init (); // setup a curl
					curl_setopt($ch, CURLOPT_URL, $url); // set url to send to
					curl_setopt($ch, CURLOPT_POST, true);
					//curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: multipart/form-data"));
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return data reather than echo
					$output = curl_exec ($ch);
					curl_close ( $ch );
					$sucessMessage="Updated Successfully";
					
					$respond=0;
					$new_id=$id;
					
					
		}
	// }
	// else 
	// {
		// $failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		// echo json_encode($failure);
	// }
	
// }
// else 
// {
	// $failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	// echo json_encode($failure);
// }
 

?>