<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date = date('Y-m-d');

require_once("../classes/querymaker.class.php");


include("send_mail_function.php");
include("send_text_message.php");


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}


$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
$data = json_decode(file_get_contents('php://input'), true);
// Order Lab Tests
/*if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{*/
		$login_id = $user_id;
		$patient_id = '0';
		$episode_id = '0';
		$diagnostic_ID = '77';	// Default Nova Diagnostic Center
		$diagno_date_time = $Cur_Date;
		
		
		$txtPatientName 	= $data['orderCustomerName'];
		$txtPhone			= $data['orderCustomerMobile'];
		$txtEmail			= $data['orderCustomerEmail'];
		$txtAddress			= $data['orderCustomerAddress'];
		$txtCity 			= $data['orderCustomerCity'];
		$txtState 			= $data['orderCustomerState'];
		$txtCountry 		= $data['orderCustomerCountry'];	
		$txtPincode 		= $data['orderCustomerPincode'];
		$txtShippingAddress = $data['orderShippingAddress'];
		$txtShippingCity 	= $data['orderShippingCity'];
		$txtShippingState 	= $data['orderShippingState'];
		$txtShippingCountry = $data['orderShippingCountry'];
		$txtShippingPincode = $data['orderShippingPincode'];
		$txtCustomerMsg		= $data['orderCustomerMsg'];
		$member_id  	  	= $data['member_id'];
		$spec_instruct    	= $data['spec_instruct'];
		$orderType    	  	= $data['orderType'];
		if($orderType == 2 )
		{
			
			$referralType = '0';				// 0-Default EMR referrals, 1- Attachments referrals from patient app
			$getDiagno			= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
			
			$arrFields_customer = array();
			$arrValues_customer = array();
			$arrFields_customer[] = 'diagnostic_id';
			$arrValues_customer[] = $diagnostic_ID;
			$arrFields_customer[] = 'patient_id';
			$arrValues_customer[] = $patient_id;
			$arrFields_customer[] = 'patient_type';
			$arrValues_customer[] = "1";
			$arrFields_customer[] = 'diagnostic_customer_name';
			$arrValues_customer[] = $txtPatientName;
			/*$arrFields_customer[] = 'diagnostic_cust_age';
			$arrValues_customer[] = $txtAge;
			$arrFields_customer[] = 'diagnostic_cust_gender';
			$arrValues_customer[] = $txtGender; */
			$arrFields_customer[] = 'diagnostic_customer_phone';
			$arrValues_customer[] = $txtPhone;
			$arrFields_customer[] = 'diagnostic_customer_email';
			$arrValues_customer[] = $txtEmail;
			$arrFields_customer[] = 'diagnostic_cust_address';
			$arrValues_customer[] = $txtAddress;
			$arrFields_customer[] = 'diagnostic_cust_city';
			$arrValues_customer[] = $txtCity;
			$arrFields_customer[] = 'diagnostic_cust_state';
			$arrValues_customer[] = $txtState;
			$arrFields_customer[] = 'diagnostic_cust_country';
			$arrValues_customer[] = $txtCountry;			
			$arrFields_customer[] = 'diagnostic_customer_pincode';
			$arrValues_customer[] = $txtPincode;
			$arrFields_customer[] = 'diagnostic_shipping_address';
			$arrValues_customer[] = $txtShippingAddress;
			$arrFields_customer[] = 'diagnostic_shipping_city';
			$arrValues_customer[] = $txtShippingCity;
			$arrFields_customer[] = 'diagnostic_shipping_state';
			$arrValues_customer[] = $txtShippingState;
			$arrFields_customer[] = 'diagnostic_shipping_country';
			$arrValues_customer[] = $txtShippingCountry;
			$arrFields_customer[] = 'diagnostic_shipping_pincode';
			$arrValues_customer[] = $txtShippingPincode;
			$arrFields_customer[] = 'login_id';
			$arrValues_customer[] = $login_id;
				
			$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
			$customer_id = $insert_diagnostic_customer; //Get customer_id
			
			$arrFields_refer = array();
			$arrValues_refer = array();
			$arrFields_refer[] = 'member_id';
			$arrValues_refer[] = $member_id;
			
			$arrFields_refer[] = 'patient_id';
			$arrValues_refer[] = $patient_id;
			$arrFields_refer[] = 'diagnostic_customer_id';
			$arrValues_refer[] = $customer_id;
			
			$arrFields_refer[] = 'episode_id';
			$arrValues_refer[] = $episode_id;
			$arrFields_refer[] = 'diagnostic_id';
			$arrValues_refer[] = $diagnostic_ID;
			$arrFields_refer[] = 'status1';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'status2';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'referred_date';
			$arrValues_refer[] = $diagno_date_time;
			$arrFields_refer[] = 'referred_by';
			$arrValues_refer[] = '1';					// 1- referred from patient app
			$arrFields_refer[] = 'login_id';
			$arrValues_refer[] = $login_id;
			$arrFields_refer[] = 'patient_type';
			$arrValues_refer[] = '1';					// For Prime Referrals
			$arrFields_refer[] = 'order_status';
			$arrValues_refer[] = '1';					// 1- Referred
			$arrFields_refer[] = 'customer_note';
			$arrValues_refer[] = $txtCustomerMsg;
			$arrFields_refer[] = 'referral_type';
			$arrValues_refer[] = $referralType;
			
			
			$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
			$dr_id = $insert_diagnostic_customer; //Get diagnostic_referrals id 
			
			
			while (list($key, $val) = each($data['investigation_id']))
			{
				$investigation_id = $data['investigation_id'][$key];
				$test_id 		  = $data['test_id'][$key];
				$group_test_id 	  = $data['grouptest_id'][$key];
				$test_name 		  = $data['test_name'][$key];
				$normal_range 	  = $data['normalRange'][$key];
				$actual_value     = $data['actualRange'][$key];
				$right_eye        = $data['rightEyeRange'][$key];
				$left_eye         = $data['leftEyeRange'][$key];
				$department       = $data['departmentRange'][$key];
				
				$arrFileds = array();
				$arrValues = array();

				$arrFileds[]='main_test_id';
				$arrValues[]=$test_id;

				$arrFileds[]='group_test_id';
				$arrValues[]=$test_id;//$group_test_id;

				$arrFileds[]='test_name';
				$arrValues[]=$test_name;

				if($normal_range != 'null') 
				{
					$arrFileds[]='normal_range';
					$arrValues[]=$normal_range;
				}
				$arrFileds[]='test_actual_value';
				$arrValues[]=$actual_value;

				$arrFileds[]='right_eye';
				$arrValues[]=$right_eye;

				$arrFileds[]='left_eye';
				$arrValues[]=$left_eye;

				$arrFileds[]='department';
				$arrValues[]=$department;

				$arrFileds[]='patient_id';
				$arrValues[]=$patient_id;

				$arrFileds[]='episode_id';
				$arrValues[]=$episode_id;

				
				$arrFileds[]='doc_type';
				$arrValues[]="1";
				$arrFileds[]='status';
				$arrValues[]="0";
				
				$arrFileds[]='login_id';
				$arrValues[]=$login_id;

				$arrFileds[]='dr_id';
				$arrValues[]=$dr_id;
				

				$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);

				$check_invest = mysqlSelect("*","doctor_frequent_investigations","main_test_id='".$investigation_id."'  and doc_type='1'","","","","");
				$freq_count = $check_invest[0]['freq_test_count']+1; //Count will increment by one
					$arrFieldsINVESTFREQ = array();
					$arrValuesINVESTFREQ = array();
					if(count($check_invest)>0)
					{
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ,"dfi_id = '".$check_invest[0]['dfi_id']."'");
					}
					else
					{
						$arrFieldsINVESTFREQ[] = 'main_test_id';
						$arrValuesINVESTFREQ[] = $investigation_id;
						$arrFieldsINVESTFREQ[] = 'doc_id';
						$arrValuesINVESTFREQ[] = $admin_id;
						$arrFieldsINVESTFREQ[] = 'doc_type';
						$arrValuesINVESTFREQ[] = "1";
						$arrFieldsINVESTFREQ[] = 'freq_test_count';
						$arrValuesINVESTFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_investigations',$arrFieldsINVESTFREQ,$arrValuesINVESTFREQ);
					}
			}
			
			
			
			
		
		}
		else if($orderType == 3 ) // 1- arttachment 3- search order 
		{
			$referralType = '2';				// 0-Default EMR referrals, 1- Attachments referrals from patient app
			$getDiagno			= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
			
			$arrFields_customer = array();
			$arrValues_customer = array();
			$arrFields_customer[] = 'diagnostic_id';
			$arrValues_customer[] = $diagnostic_ID;
			$arrFields_customer[] = 'patient_id';
			$arrValues_customer[] = $patient_id;
			$arrFields_customer[] = 'patient_type';
			$arrValues_customer[] = "1";
			$arrFields_customer[] = 'diagnostic_customer_name';
			$arrValues_customer[] = $txtPatientName;
			/*$arrFields_customer[] = 'diagnostic_cust_age';
			$arrValues_customer[] = $txtAge;
			$arrFields_customer[] = 'diagnostic_cust_gender';
			$arrValues_customer[] = $txtGender; */
			$arrFields_customer[] = 'diagnostic_customer_phone';
			$arrValues_customer[] = $txtPhone;
			$arrFields_customer[] = 'diagnostic_customer_email';
			$arrValues_customer[] = $txtEmail;
			$arrFields_customer[] = 'diagnostic_cust_address';
			$arrValues_customer[] = $txtAddress;
			$arrFields_customer[] = 'diagnostic_cust_city';
			$arrValues_customer[] = $txtCity;
			$arrFields_customer[] = 'diagnostic_cust_state';
			$arrValues_customer[] = $txtState;
			$arrFields_customer[] = 'diagnostic_cust_country';
			$arrValues_customer[] = $txtCountry;			
			$arrFields_customer[] = 'diagnostic_customer_pincode';
			$arrValues_customer[] = $txtPincode;
			$arrFields_customer[] = 'diagnostic_shipping_address';
			$arrValues_customer[] = $txtShippingAddress;
			$arrFields_customer[] = 'diagnostic_shipping_city';
			$arrValues_customer[] = $txtShippingCity;
			$arrFields_customer[] = 'diagnostic_shipping_state';
			$arrValues_customer[] = $txtShippingState;
			$arrFields_customer[] = 'diagnostic_shipping_country';
			$arrValues_customer[] = $txtShippingCountry;
			$arrFields_customer[] = 'diagnostic_shipping_pincode';
			$arrValues_customer[] = $txtShippingPincode;
			$arrFields_customer[] = 'login_id';
			$arrValues_customer[] = $login_id;
				
			$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
			$customer_id = $insert_diagnostic_customer; //Get customer_id
			
			$arrFields_refer = array();
			$arrValues_refer = array();
			$arrFields_refer[] = 'member_id';
			$arrValues_refer[] = $member_id;
			
			$arrFields_refer[] = 'patient_id';
			$arrValues_refer[] = $patient_id;
			$arrFields_refer[] = 'diagnostic_customer_id';
			$arrValues_refer[] = $customer_id;
			
			$arrFields_refer[] = 'episode_id';
			$arrValues_refer[] = $episode_id;
			$arrFields_refer[] = 'diagnostic_id';
			$arrValues_refer[] = $diagnostic_ID;
			$arrFields_refer[] = 'status1';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'status2';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'referred_date';
			$arrValues_refer[] = $diagno_date_time;
			$arrFields_refer[] = 'referred_by';
			$arrValues_refer[] = '1';					// 1- referred from patient app
			$arrFields_refer[] = 'login_id';
			$arrValues_refer[] = $login_id;
			$arrFields_refer[] = 'patient_type';
			$arrValues_refer[] = '1';					// For Prime Referrals
			$arrFields_refer[] = 'order_status';
			$arrValues_refer[] = '1';					// 1- Referred
			$arrFields_refer[] = 'customer_note';
			$arrValues_refer[] = $txtCustomerMsg;
			$arrFields_refer[] = 'referral_type';
			$arrValues_refer[] = $referralType;
			
			
			$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
			$dr_id = $insert_diagnostic_customer; //Get diagnostic_referrals id 
			
			while(list($key_invest, $value_invest) = each($data['main_test_id']))//ACADEMIC INFORMATION 
			{
		
				$arrOrderFields = array();
				$arrOrderValues = array();
				
				
				$arrOrderFields[]	=	'dr_id';
				$arrOrderValues[]	=	$dr_id;
				
				//investigation_id
				
				$arrOrderFields[]	=	'main_test_id';  // investigation_id  == main_test_id
				$arrOrderValues[]	=	$data['main_test_id'][$key_invest];
				
				$arrOrderFields[]	=	'group_test_id';
				$arrOrderValues[]	=	$data['group_test_id'][$key_invest];
				
				$arrOrderFields[]	=	'test_name';
				$arrOrderValues[]	=	$data['test_name'][$key_invest];
				
				
				$arrOrderFields[]	=	'login_id';
				$arrOrderValues[]	=	$login_id;
				
				$arrOrderFields[]	=	'member_id';
				$arrOrderValues[]	=	$member_id;
				$insert_doctor_reg	= mysqlInsert('order_labtest',$arrOrderFields,$arrOrderValues);
				
			}
			
		}
		
		else if($orderType == 2)
		{
			$referralType = '1';				// 0-Default EMR referrals, 1- Attachments referrals from patient app
			
			$getDiagno			= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
			
			$arrFields_customer = array();
			$arrValues_customer = array();
			$arrFields_customer[] = 'diagnostic_id';
			$arrValues_customer[] = $diagnostic_ID;
			$arrFields_customer[] = 'patient_id';
			$arrValues_customer[] = $patient_id;
			$arrFields_customer[] = 'patient_type';
			$arrValues_customer[] = "1";
			$arrFields_customer[] = 'diagnostic_customer_name';
			$arrValues_customer[] = $txtPatientName;
			/*$arrFields_customer[] = 'diagnostic_cust_age';
			$arrValues_customer[] = $txtAge;
			$arrFields_customer[] = 'diagnostic_cust_gender';
			$arrValues_customer[] = $txtGender; */
			$arrFields_customer[] = 'diagnostic_customer_phone';
			$arrValues_customer[] = $txtPhone;
			$arrFields_customer[] = 'diagnostic_customer_email';
			$arrValues_customer[] = $txtEmail;
			$arrFields_customer[] = 'diagnostic_cust_address';
			$arrValues_customer[] = $txtAddress;
			$arrFields_customer[] = 'diagnostic_cust_city';
			$arrValues_customer[] = $txtCity;
			$arrFields_customer[] = 'diagnostic_cust_state';
			$arrValues_customer[] = $txtState;
			$arrFields_customer[] = 'diagnostic_cust_country';
			$arrValues_customer[] = $txtCountry;			
			$arrFields_customer[] = 'diagnostic_customer_pincode';
			$arrValues_customer[] = $txtPincode;
			$arrFields_customer[] = 'diagnostic_shipping_address';
			$arrValues_customer[] = $txtShippingAddress;
			$arrFields_customer[] = 'diagnostic_shipping_city';
			$arrValues_customer[] = $txtShippingCity;
			$arrFields_customer[] = 'diagnostic_shipping_state';
			$arrValues_customer[] = $txtShippingState;
			$arrFields_customer[] = 'diagnostic_shipping_country';
			$arrValues_customer[] = $txtShippingCountry;
			$arrFields_customer[] = 'diagnostic_shipping_pincode';
			$arrValues_customer[] = $txtShippingPincode;
			$arrFields_customer[] = 'login_id';
			$arrValues_customer[] = $login_id;
				
			$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
			$customer_id = $insert_diagnostic_customer; //Get customer_id
			
			$arrFields_refer   = array();
			$arrValues_refer   = array();
			$arrFields_refer[] = 'member_id';
			$arrValues_refer[] = $member_id;
			
			$arrFields_refer[] = 'patient_id';
			$arrValues_refer[] = $patient_id;
			$arrFields_refer[] = 'diagnostic_customer_id';
			$arrValues_refer[] = $customer_id;
			/*$arrFields_refer[] = 'doc_id';
			$arrValues_refer[] = $getDoc[0]['ref_id'];
			$arrFields_refer[] = 'doc_type';
			$arrValues_refer[] = "1"; */
			$arrFields_refer[] = 'episode_id';
			$arrValues_refer[] = $episode_id;
			$arrFields_refer[] = 'diagnostic_id';
			$arrValues_refer[] = $diagnostic_ID;
			$arrFields_refer[] = 'status1';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'status2';
			$arrValues_refer[] = "0";
			$arrFields_refer[] = 'referred_date';
			$arrValues_refer[] = $diagno_date_time;
			$arrFields_refer[] = 'referred_by';
			$arrValues_refer[] = '1';					// 1- referred from patient app
			$arrFields_refer[] = 'login_id';
			$arrValues_refer[] = $login_id;
			$arrFields_refer[] = 'patient_type';
			$arrValues_refer[] = '1';					// For Prime Referrals
			$arrFields_refer[] = 'order_status';
			$arrValues_refer[] = '1';					// 1- Referred
			$arrFields_refer[] = 'customer_note';
			$arrValues_refer[] = $txtCustomerMsg;
			$arrFields_refer[] = 'referral_type';
			$arrValues_refer[] = $referralType;
			
			
			$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
			$diagno_referral_id = mysql_insert_id();
		
				 
			 //Add Lab Test Attachments functionality
			$errors= array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
			{
				$file_name = $_FILES['file-3']['name'][$key];
				$file_size =$_FILES['file-3']['size'][$key];
				$file_tmp =$_FILES['file-3']['tmp_name'][$key];
				$file_type=$_FILES['file-3']['type'][$key];

				if(!empty($file_name))
				{
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'customer_id';
					$arrValues_attach[] = $diagno_referral_id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $login_id;

					$arrFields_attach[] = 'attachments';
					$arrValues_attach[] = $file_name;

					$pat_attach=mysqlInsert('health_lab_test_request_attachments',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;
					//Uploading image file
					$uploaddirectory = realpath("../HealthLabTestsAttachments");
					$uploaddir = $uploaddirectory . "/" .$attachid;
					$dotpos = strpos($fileName, '.');
					$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					$uploadfile = $uploaddir . "/" . $Photo1;

					//Checking whether folder with category id already exist or not.
					if (file_exists($uploaddir))
					{
						//echo "The file $uploaddir exists";
					}
					else 
					{
						$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777);
					}
					// Moving uploaded file from temporary folder to desired folder.
					if(move_uploaded_file ($file_tmp, $uploadfile)) 
					{
						$successAttach="";
					}
					else
					{
						//echo "File cannot be uploaded";
					}
				}

			}
			
			//End of foreach
		}
		else
		{
			echo"Invalid Order Type";
		}
		
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
