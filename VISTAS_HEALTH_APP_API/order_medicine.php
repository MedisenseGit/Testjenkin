<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");

include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
$data = json_decode(file_get_contents('php://input'), true);
// Order Medicine
/*if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{*/
		
		$login_id         = $user_id;
		$patient_id       = '0';
		$episode_id       = '0';
		$pharma_ID 		  = '41';	// Default Nova Pharmacy Center
		$pharma_date_time = $Cur_Date;
		$referralType     = '1';				// 0-Default EMR referrals, 1- Attachments referrals from patient app
		
		$txtPatientName 	= $data['orderCustomerName'];
		$txtPhone 			= $data['orderCustomerMobile'];
		$txtEmail			= $data['orderCustomerEmail'];
		$txtAddress 		= $data['orderCustomerAddress'];
		$txtCity			= $data['orderCustomerCity'];
		$txtState 			= $data['orderCustomerState'];
		$txtCountry			= $data['orderCustomerCountry'];
		$txtPincode 		= $data['orderCustomerPincode'];
		$txtShippingAddress = $data['orderShippingAddress'];
		$txtShippingCity 	= $data['orderShippingCity'];
		$txtShippingState 	= $data['orderShippingState'];
		$txtShippingCountry = $data['orderShippingCountry'];
		$txtShippingPincode = $data['orderShippingPincode'];
		$txtCustomerMsg 	= $data['orderCustomerMsg'];
		
		$orderType    	  = $data['orderType'];
		
		if($orderType == "2")
		{
			$referralType = '0';
			
			$arrFields_customer = array();
			$arrValues_customer = array();
			
			$arrFields_customer[] = 'pharma_id';
			$arrValues_customer[] = $pharma_ID;
			$arrFields_customer[] = 'patient_id';
			$arrValues_customer[] = $patient_id;
			$arrFields_customer[] = 'patient_type';
			$arrValues_customer[] = "1";
			$arrFields_customer[] = 'pharma_customer_name';
			$arrValues_customer[] = $txtPatientName;
			
			$arrFields_customer[] = 'pharma_customer_phone';
			$arrValues_customer[] = $txtPhone;
			$arrFields_customer[] = 'pharma_customer_email';
			$arrValues_customer[] = $txtEmail;
			$arrFields_customer[] = 'pharma_cust_address';
			$arrValues_customer[] = $txtAddress;
			$arrFields_customer[] = 'pharma_cust_city';
			$arrValues_customer[] = $txtCity;
			$arrFields_customer[] = 'pharma_cust_state';
			$arrValues_customer[] = $txtState;
			$arrFields_customer[] = 'pharma_cust_country';
			$arrValues_customer[] = $txtCountry;		
			$arrFields_customer[] = 'pharma_customer_pincode';
			$arrValues_customer[] = $txtPincode;
			$arrFields_customer[] = 'pharma_shipping_address';
			$arrValues_customer[] = $txtShippingAddress;
			$arrFields_customer[] = 'pharma_shipping_city';
			$arrValues_customer[] = $txtShippingCity;
			$arrFields_customer[] = 'pharma_shipping_state';
			$arrValues_customer[] = $txtShippingState;
			$arrFields_customer[] = 'pharma_shipping_country';
			$arrValues_customer[] = $txtShippingCountry;
			$arrFields_customer[] = 'pharma_shipping_pincode';
			$arrValues_customer[] = $txtShippingPincode;
			$arrFields_customer[] = 'login_id';
			$arrValues_customer[] = $login_id;
				
				
			$insert_pharma_customer = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
			$customer_id 			= $insert_pharma_customer; //Get customer_id
							
			$arrFields_refer = array();
			$arrValues_refer = array();
			$arrFields_refer[] = 'patient_id';
			$arrValues_refer[] = $patient_id;
			
			$arrFields_refer[] = 'pharma_customer_id';
			$arrValues_refer[] = $customer_id;
			
			$arrFields_refer[] = 'episode_id';
			$arrValues_refer[] = $episode_id;
			$arrFields_refer[] = 'pharma_id';
			$arrValues_refer[] = $pharma_ID;
			$arrFields_refer[] = 'status1';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'status2';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'referred_date';
			$arrValues_refer[] = $pharma_date_time;
			$arrFields_refer[] = 'referred_by';
			$arrValues_refer[] = '1';					// 1- referred from patient app
			$arrFields_refer[] = 'login_id';
			$arrValues_refer[] = $login_id;
			$arrFields_refer[] = 'order_status';
			$arrValues_refer[] = '1';					// 1- Referred
			$arrFields_refer[] = 'customer_note';
			$arrValues_refer[] = $txtCustomerMsg;
			$arrFields_refer[] = 'referral_type';
			$arrValues_refer[] = $referralType;
			
			$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
			
			
			$pharma_referral_id = $insert_pharmacy_customer;
		
			
			while (list($key, $val) = each($data['prescription_ppID']))
			{
				$presc_pp_id		 = $data['prescription_ppID'][$key];
				$presc_trade_name 	 = $data['prescription_tradeName'][$key];
				$presc_generic_id 	 = $data['prescription_genericID'][$key];
				$presc_generic_name  = $data['prescription_genericName'][$key];
				//$presc_timings       = $data['prescription_timings'][$key];
				//$presc_duration      = $data['prescription_duration'][$key];
				$prescription_date_time = $Cur_Date;
				
				
				
				if($presc_pp_id == 0) 
				{
					$arrFileds_freq = array();
					$arrValues_freq = array();

					$arrFileds_freq[]='pp_id';
					$arrValues_freq[]=time();
					$arrFileds_freq[]='med_trade_name';
					$arrValues_freq[]=$presc_trade_name;
					$arrFileds_freq[]='med_generic_name';
					$arrValues_freq[]=$presc_generic_name;
					$arrFileds_freq[]='med_frequency';
					$arrValues_freq[]=$presc_dosage;
					$arrFileds_freq[]='med_timing';
					$arrValues_freq[]=$presc_timings;
					$arrFileds_freq[]='med_duration';
					$arrValues_freq[]=$presc_duration;
					$arrFileds_freq[]='doc_id';
					$arrValues_freq[]=$admin_id;
					$arrFileds_freq[]='doc_type';
					$arrValues_freq[]="1";
					$arrFileds_freq[]='freq_count';
					$arrValues_freq[]="1";
					$arrFileds_freq[]='med_frequency_morning';
					$arrValues_freq[]=$presc_morning;
					$arrFileds_freq[]='med_frequency_noon';
					$arrValues_freq[]=$presc_afternoon;
					$arrFileds_freq[]='med_frequency_night';
					$arrValues_freq[]=$presc_night;
					$arrFileds_freq[]='med_duration_type';
					$arrValues_freq[]=$presc_durationType;
					$arrFileds_freq[]='prescription_instruction';
					$arrValues_freq[]=$presc_instructions;
				
					$insert_medicine	=	mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);
					
					$freq_id 			= 	$insert_medicine; //Get Frequent Medicine Id
					$get_ppid 			= 	time();
				}
				else 
				{
					$get_ppid 	= $presc_pp_id;
					$chkProduct = mysqlSelect("*","doctor_frequent_medicine","pp_id='".$presc_pp_id."'","","","","");

					$arrFileds_freq = array();
					$arrValues_freq = array();
					if($chkProduct == true)
					{
						$freq_count=$chkProduct[0]['freq_count']+1;

						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$presc_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$presc_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$presc_dosage;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$presc_timings;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$presc_duration;
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]=$freq_count;
						$arrFileds_freq[]='med_frequency_morning';
						$arrValues_freq[]=$presc_morning;
						$arrFileds_freq[]='med_frequency_noon';
						$arrValues_freq[]=$presc_afternoon;
						$arrFileds_freq[]='med_frequency_night';
						$arrValues_freq[]=$presc_night;
						$arrFileds_freq[]='med_duration_type';
						$arrValues_freq[]=$presc_durationType;
						$arrFileds_freq[]='prescription_instruction';
						$arrValues_freq[]=$presc_instructions;
						$update_medicine=mysqlUpdate('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq,"pp_id = '".$chkProduct[0]['pp_id']."'");

					}
					else
					{
						$arrFileds_freq[]='pp_id';
						$arrValues_freq[]=$presc_pp_id;
						$arrFileds_freq[]='med_trade_name';
						$arrValues_freq[]=$presc_trade_name;
						$arrFileds_freq[]='med_generic_name';
						$arrValues_freq[]=$presc_generic_name;
						$arrFileds_freq[]='med_frequency';
						$arrValues_freq[]=$presc_dosage;
						$arrFileds_freq[]='med_timing';
						$arrValues_freq[]=$presc_timings;
						$arrFileds_freq[]='med_duration';
						$arrValues_freq[]=$presc_duration;
						$arrFileds_freq[]='doc_id';
						$arrValues_freq[]=$admin_id;
						$arrFileds_freq[]='doc_type';
						$arrValues_freq[]="1";
						$arrFileds_freq[]='freq_count';
						$arrValues_freq[]="1";
						$arrFileds_freq[]='med_frequency_morning';
						$arrValues_freq[]=$presc_morning;
						$arrFileds_freq[]='med_frequency_noon';
						$arrValues_freq[]=$presc_afternoon;
						$arrFileds_freq[]='med_frequency_night';
						$arrValues_freq[]=$presc_night;
						$arrFileds_freq[]='med_duration_type';
						$arrValues_freq[]=$presc_durationType;
						$arrFileds_freq[]='prescription_instruction';
						$arrValues_freq[]=$presc_instructions;

						$insert_medicine=mysqlInsert('doctor_frequent_medicine',$arrFileds_freq,$arrValues_freq);

					}
				}

					$arrFieldsPEP = array();
					$arrValuesPEP = array();
					$arrFieldsPEP[] = 'episode_id';
					$arrValuesPEP[] = $episode_id;
					$arrFieldsPEP[] = 'doc_id';
					$arrValuesPEP[] = $admin_id;
					$arrFieldsPEP[] = 'pp_id';
					$arrValuesPEP[] = $get_ppid;
					$arrFieldsPEP[] = 'prescription_trade_name';
					$arrValuesPEP[] = $presc_trade_name;
					$arrFieldsPEP[] = 'prescription_generic_name';
					$arrValuesPEP[] = $presc_generic_name;
					$arrFieldsPEP[] = 'prescription_frequency';
					$arrValuesPEP[] = $presc_dosage;
					$arrFieldsPEP[] = 'timing';
					$arrValuesPEP[] = $presc_timings;
					$arrFieldsPEP[] = 'duration';
					$arrValuesPEP[] = $presc_duration;
					$arrFieldsPEP[] = 'prescription_date_time';
					$arrValuesPEP[] = $prescription_date_time;
					
					$arrFieldsPEP[]='med_frequency_morning';
					$arrValuesPEP[]=$presc_morning;
					$arrFieldsPEP[]='med_frequency_noon';
					$arrValuesPEP[]=$presc_afternoon;
					$arrFieldsPEP[]='med_frequency_night';
					$arrValuesPEP[]=$presc_night;
					$arrFieldsPEP[]='med_duration_type';
					$arrValuesPEP[]=$presc_durationType;
					$arrFieldsPEP[]='prescription_instruction';
					$arrValuesPEP[]=$presc_instructions;
					
					$arrFieldsPEP[]='login_id';
					$arrValuesPEP[]=$login_id;

					$arrFieldsPEP[]='pr_id';
					$arrValuesPEP[]=$pharma_referral_id;
					
					$insert_patient_episode_prescriptions = mysqlInsert('doc_patient_episode_prescriptions',$arrFieldsPEP,$arrValuesPEP);

				
			}
		}
		else if($orderType == 3)
		{
			$referralType = '2';	
			
			$arrFields_customer = array();
			$arrValues_customer = array();
			
			$arrFields_customer[] = 'pharma_id';
			$arrValues_customer[] = $pharma_ID;
			$arrFields_customer[] = 'patient_id';
			$arrValues_customer[] = $patient_id;
			$arrFields_customer[] = 'patient_type';
			$arrValues_customer[] = "1";
			$arrFields_customer[] = 'pharma_customer_name';
			$arrValues_customer[] = $txtPatientName;
			
			$arrFields_customer[] = 'pharma_customer_phone';
			$arrValues_customer[] = $txtPhone;
			$arrFields_customer[] = 'pharma_customer_email';
			$arrValues_customer[] = $txtEmail;
			$arrFields_customer[] = 'pharma_cust_address';
			$arrValues_customer[] = $txtAddress;
			$arrFields_customer[] = 'pharma_cust_city';
			$arrValues_customer[] = $txtCity;
			$arrFields_customer[] = 'pharma_cust_state';
			$arrValues_customer[] = $txtState;
			$arrFields_customer[] = 'pharma_cust_country';
			$arrValues_customer[] = $txtCountry;		
			$arrFields_customer[] = 'pharma_customer_pincode';
			$arrValues_customer[] = $txtPincode;
			$arrFields_customer[] = 'pharma_shipping_address';
			$arrValues_customer[] = $txtShippingAddress;
			$arrFields_customer[] = 'pharma_shipping_city';
			$arrValues_customer[] = $txtShippingCity;
			$arrFields_customer[] = 'pharma_shipping_state';
			$arrValues_customer[] = $txtShippingState;
			$arrFields_customer[] = 'pharma_shipping_country';
			$arrValues_customer[] = $txtShippingCountry;
			$arrFields_customer[] = 'pharma_shipping_pincode';
			$arrValues_customer[] = $txtShippingPincode;
			$arrFields_customer[] = 'login_id';
			$arrValues_customer[] = $login_id;
				
				
			$insert_pharma_customer = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
			$customer_id 			= $insert_pharma_customer; //Get customer_id
							
			$arrFields_refer = array();
			$arrValues_refer = array();
			$arrFields_refer[] = 'patient_id';
			$arrValues_refer[] = $patient_id;
			
			$arrFields_refer[] = 'pharma_customer_id';
			$arrValues_refer[] = $customer_id;
			
			$arrFields_refer[] = 'episode_id';
			$arrValues_refer[] = $episode_id;
			$arrFields_refer[] = 'pharma_id';
			$arrValues_refer[] = $pharma_ID;
			$arrFields_refer[] = 'status1';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'status2';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'referred_date';
			$arrValues_refer[] = $pharma_date_time;
			$arrFields_refer[] = 'referred_by';
			$arrValues_refer[] = '1';					// 1- referred from patient app
			$arrFields_refer[] = 'login_id';
			$arrValues_refer[] = $login_id;
			$arrFields_refer[] = 'order_status';
			$arrValues_refer[] = '1';					// 1- Referred
			$arrFields_refer[] = 'customer_note';
			$arrValues_refer[] = $txtCustomerMsg;
			$arrFields_refer[] = 'referral_type';
			$arrValues_refer[] = $referralType;
			
			$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
			$pharma_referral_id = $insert_pharmacy_customer;
			
			while(list($key_invest, $value_invest) = each($data['prescription_tradeName']))//ACADEMIC INFORMATION 
			{
		
				$arrOrderFields = array();
				$arrOrderValues = array();
				
				
				$arrOrderFields[]	=	'pr_id';
				$arrOrderValues[]	=	$pharma_referral_id;
				
				$arrOrderFields[]	=	'pp_id';
				$arrOrderValues[]	=	$data['prescription_ppID'][$key_invest];
				
				$arrOrderFields[]	=	'prescription_trade_name';
				$arrOrderValues[]	=	$data['prescription_tradeName'][$key_invest];
				
				$arrOrderFields[]	=	'prescription_generic_name';
				$arrOrderValues[]	=	$data['prescription_genericName'][$key_invest];
				
				
				$arrOrderFields[]	=	'login_id';
				$arrOrderValues[]	=	$login_id;
				
				
				$insert_doctor_reg	= mysqlInsert('order_medicine',$arrOrderFields,$arrOrderValues);
				
			}
			

		}		
		
		else if($orderType == 2)
		{
				$referralType = '1';
				$getPharma= mysqlSelect("*","pharma","pharma_id='".$pharma_ID."'");
			
			
				$arrFields_customer = array();
				$arrValues_customer = array();
				$arrFields_customer[] = 'pharma_id';
				$arrValues_customer[] = $pharma_ID;
				$arrFields_customer[] = 'patient_id';
				$arrValues_customer[] = $patient_id;
				$arrFields_customer[] = 'patient_type';
				$arrValues_customer[] = "1";
				$arrFields_customer[] = 'pharma_customer_name';
				$arrValues_customer[] = $txtPatientName;
				
				$arrFields_customer[] = 'pharma_customer_phone';
				$arrValues_customer[] = $txtPhone;
				$arrFields_customer[] = 'pharma_customer_email';
				$arrValues_customer[] = $txtEmail;
				$arrFields_customer[] = 'pharma_cust_address';
				$arrValues_customer[] = $txtAddress;
				$arrFields_customer[] = 'pharma_cust_city';
				$arrValues_customer[] = $txtCity;
				$arrFields_customer[] = 'pharma_cust_state';
				$arrValues_customer[] = $txtState;
				$arrFields_customer[] = 'pharma_cust_country';
				$arrValues_customer[] = $txtCountry;		
				$arrFields_customer[] = 'pharma_customer_pincode';
				$arrValues_customer[] = $txtPincode;
				$arrFields_customer[] = 'pharma_shipping_address';
				$arrValues_customer[] = $txtShippingAddress;
				$arrFields_customer[] = 'pharma_shipping_city';
				$arrValues_customer[] = $txtShippingCity;
				$arrFields_customer[] = 'pharma_shipping_state';
				$arrValues_customer[] = $txtShippingState;
				$arrFields_customer[] = 'pharma_shipping_country';
				$arrValues_customer[] = $txtShippingCountry;
				$arrFields_customer[] = 'pharma_shipping_pincode';
				$arrValues_customer[] = $txtShippingPincode;
				$arrFields_customer[] = 'login_id';
				$arrValues_customer[] = $login_id;
						
				$insert_pharma_customer = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
				$customer_id = $insert_pharma_customer; //Get customer_id
								
				$arrFields_refer = array();
				$arrValues_refer = array();
				$arrFields_refer[] = 'patient_id';
				$arrValues_refer[] = $patient_id;
				$arrFields_refer[] = 'pharma_customer_id';
				$arrValues_refer[] = $customer_id;
				
				$arrFields_refer[] = 'episode_id';
				$arrValues_refer[] = $episode_id;
				$arrFields_refer[] = 'pharma_id';
				$arrValues_refer[] = $pharma_ID;
				$arrFields_refer[] = 'status1';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'status2';
				$arrValues_refer[] = "0";
				$arrFields_refer[] = 'referred_date';
				$arrValues_refer[] = $pharma_date_time;
				$arrFields_refer[] = 'referred_by';
				$arrValues_refer[] = '1';					// 1- referred from patient app
				$arrFields_refer[] = 'login_id';
				$arrValues_refer[] = $login_id;
				$arrFields_refer[] = 'order_status';
				$arrValues_refer[] = '1';					// 1- Referred
				$arrFields_refer[] = 'customer_note';
				$arrValues_refer[] = $txtCustomerMsg;
				$arrFields_refer[] = 'referral_type';
				$arrValues_refer[] = $referralType;
				
				$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
				$pharma_referral_id = $insert_pharmacy_customer;
			
		
		
			//Add Prescription Attachments functionality
			$errors= array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
			{

				$file_name 	= 	$_FILES['file-3']['name'][$key];
				$file_size 	=	$_FILES['file-3']['size'][$key];
				$file_tmp 	=	$_FILES['file-3']['tmp_name'][$key];
				$file_type	=	$_FILES['file-3']['type'][$key];

				if(!empty($file_name))
				{
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'customer_id';
					$arrValues_attach[] = $pharma_referral_id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $login_id;

					$arrFields_attach[] = 'attachments';
					$arrValues_attach[] = $file_name;

					$pat_attach=mysqlInsert('health_pharma_request_attachments',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;
					
					$folder_name	=	"HealthPharmaAttachments";
					$sub_folder		=	$attachid;
					$filename		=	$_FILES['file-3']['name'][$key];
					$file_url		=	$_FILES['file-3']['tmp_name'][$key];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

					//Uploading image file
					// $uploaddirectory = realpath("../HealthPharmaAttachments");
					// $uploaddir = $uploaddirectory . "/" .$attachid;
					// $dotpos = strpos($fileName, '.');
					// $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					// $uploadfile = $uploaddir . "/" . $Photo1;


					//Checking whether folder with category id already exist or not.
					// if (file_exists($uploaddir))
					// {
					//	echo "The file $uploaddir exists";
					// }
					// else
					// {
						// $newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
					// }

					//Moving uploaded file from temporary folder to desired folder.
					// if(move_uploaded_file ($file_tmp, $uploadfile))
					// {

						// $successAttach="";
					// }
					// else
					// {
					//	echo "File cannot be uploaded";
					// }
				}

			}
		}
		else
		{
			echo"Invalid Order Type";
		}
		//End of foreach
		
		
	
		
		$success_opinion = array('result' => "success", 'status' => '1', 'message' => "Your order request has been sent successfully. \n\nWe will get back to you within 24-48 Hours.", 'err_msg' => '');
		echo json_encode($success_opinion);
	/*}
	else 
	{
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else 
{
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}*/

?>
