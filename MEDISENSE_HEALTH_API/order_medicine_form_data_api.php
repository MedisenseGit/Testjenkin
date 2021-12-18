<?php 
ob_start();
error_reporting(0);
session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();

$arrFields_newAddress = $data ->arrFields_newAddress;

if(HEALTH_API_KEY == $data ->api_key  && isset($data ->arrFields_newAddress) && isset($data ->arrValues_newAddress))
{
	$arrFields_newAddress = $data ->arrFields_newAddress;
	$arrValues_newAddress = $data ->arrValues_newAddress;
	
	$newAddress = mysqlInsert('user_address',$arrFields_newAddress,$arrValues_newAddress);
    $newAddress_id = $newAddress;
	if(!empty($newAddress))
	{
		$result =1;
	}
	else
	{
		$result=0;
	}
	
	$response = array('status' => "true","insert" => $result,"newAddress_id" => $newAddress_id);
	echo json_encode($response );
}
if(HEALTH_API_KEY == $data ->api_key  && isset($data ->userid))	
{	
	$admin_id	=	$data ->userid;
	$user_array = 	mysqlSelect("*","login_user","login_id ='".$admin_id."' ","","","","");
	$family_names_array 	= mysqlSelect("*","user_family_member","user_id='".$admin_id."' ","member_id ASC","","","");
	$user_addresses_array 	= mysqlSelect("*","user_address","user_id='".$admin_id."' ","","","","");

	$response = array('status' => "true","user" => $user_array,"family_names" => $family_names_array,"user_addresses" => $user_addresses_array);
	echo json_encode($response );
}

else if(HEALTH_API_KEY == $data ->api_key)
{
	
	$pharma_ID  		= $data ->pharma_ID;
	$login_id   		= $data ->login_id;
	$doc_id    	 		= $data ->doc_id;
	$patient_id			= $data ->patient_id;
	$arrFields_customer = $data->arrFields_customer;
	$arrValues_customer = $data->arrValues_customer;
	
	$arrFields_refer 	= $data->arrFields_refer;
	$arrValues_refer 	= $data->arrValues_refer;
	
	$orderType 	= 		$data->orderType;
	
	$prescription_trade_name   = $data ->prescription_trade_name;
	$episode_prescription_id   = $data ->episode_prescription_id;
	$prescription_generic_name = $data->prescription_generic_name;
	$timing 				   = $data->timing;
	$duration 				   = $data->duration;
	
	
	$getDoc		=	 mysqlSelect("*","referal","ref_id='".$doc_id."'");
	
	$getPharma	= 	 mysqlSelect("*","pharma","pharma_id='".$pharma_ID."'");
	
	/*$insert_pharma_customer = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
	$customer_id = $insert_pharma_customer ;//Get customer_id
	
	$arrFields_refer[] = 'pharma_customer_id';
	$arrValues_refer[] = $customer_id;
	
	$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
	$pharma_referral_id = $insert_pharmacy_customer;*/
		
	if($orderType == 1)
	{
		$insert_pharma_customer7 = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = $insert_pharma_customer7; //Get customer_id
	
		$arrFields_refer[] = 'pharma_customer_id';
		$arrValues_refer[] = $customer_id;
	
		$insert_pharmacy_customer1 = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
		$pharma_referral_id = $insert_pharmacy_customer1;
		
	}
	
	else if($orderType == 2)
	{
		//ORDER TYPE = 1 FOR Attachments  
		
		$insert_pharma_customer2 = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = $insert_pharma_customer2; //Get customer_id
	
		//echo $customer_id;
	
		$arrFields_refer[] = 'pharma_customer_id';
		$arrValues_refer[] = $customer_id;
	
		$insert_pharmacy_customer3 = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
		$pharma_referral_id = $insert_pharmacy_customer3;
		
		
		
		foreach($data->temp_name as $key => $tmp_name)
		{
			$file_name = $data->file_name[$key];
			$temp_name = $data->temp_name[$key];
			
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
				
				$folder_name	=	"HealthPharmaAttachments"; // change this
			$sub_folder		=	$attachid;
			$filename		=	$data->file_name[$key];
			$file_url		=	$data->temp_name[$key];
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			}

			

		}
		
	}
	
	else if ($orderType == 3)
	{
		$insert_pharma_customer5 = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = $insert_pharma_customer5; //Get customer_id
	
		$arrFields_refer[] = 'pharma_customer_id';
		$arrValues_refer[] = $customer_id;
	
		$insert_pharmacy_customer6 = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);

		$pharma_referral_id1 	   = $insert_pharmacy_customer6;

		
		$prescription_trade_name   = $data ->prescription_trade_name;
		$episode_prescription_id   = $data ->episode_prescription_id;
		$prescription_generic_name = $data->prescription_generic_name;
		$timing 				   = $data->timing;
		$duration 				   = $data->duration;
		$pp_id 				   	   = $data->pp_id;
		
		
		while(list($key_invest, $value_invest) = each($prescription_trade_name))//ACADEMIC INFORMATION 
		{
		
			$arrOrderFields = array();
			$arrOrderValues = array();
			
			
			$arrOrderFields[]	=	'pr_id';
			$arrOrderValues[]	=	$pharma_referral_id1;
			
			$arrOrderFields[]	=	'pp_id';
			$arrOrderValues[]	=	$pp_id[$key_invest];
			
			$arrOrderFields[]	=	'prescription_trade_name';
			$arrOrderValues[]	=	$prescription_trade_name[$key_invest];
			
			$arrOrderFields[]	=	'prescription_generic_name';
			$arrOrderValues[]	=	$prescription_generic_name[$key_invest];
			
			
			$arrOrderFields[]	=	'login_id';
			$arrOrderValues[]	=	$login_id;
			
			
			$insert_doctor_reg	= mysqlInsert('order_medicine',$arrOrderFields,$arrOrderValues);
				
		}
	}
	else
	{
		echo "Invalid Deatils";
	}
	
	$response["status"] = "true";
   // $response["data"] = "api problem";
	echo(json_encode($response));
	
	//$link = "http://128.199.207.75/premium/Pharma-Refer?=".$patient_id."&e=".(md5($episode_id));
	//$val="inserted";
	//$response = array('status' => "true","arrFields_refer"=>$arrFields_refer,"arrValues_refer"=>$arrValues_refer,"customer_id"=>$customer_id);
	//$response = array("status"=>"true","insert"=>$val);
	//echo json_encode($response);
	/*$arrFields_customer = $data->arrFields_customer;
	$arrValues_customer = $data->arrValues_customer;
	
	$arrFields_refer = $data->arrFields_refer;
	$arrValues_refer = $data->arrValues_refer;
	
	$check_customer = mysqlSelect('*','pharma_customer',"pharma_id='".$pharma_ID."' and login_id='".$login_id."' and pharma_customer_name LIKE '%".$txtPatientName."%'","","","","");
	
	$getPharma= mysqlSelect("*","pharma","pharma_id='".$pharma_ID."'");
	if(empty($check_customer))
	{
		$insert_pharma_customer = mysqlInsert('pharma_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = mysqli_insert_id($dbConnection); //Get customer_id
		
		$arrFields_refer[] = 'pharma_customer_id';
		$arrValues_refer[] = $customer_id;
		
		$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
		$pharma_referral_id = mysqli_insert_id($dbConnection);
	}	
	else
	{
		$customer_id = (int)$check_customer[0]['pharma_customer_id'];
		
		$arrFields_refer[] = 'pharma_customer_id';
		$arrValues_refer[] = $customer_id;
		
		$insert_pharmacy_customer = mysqlInsert('pharma_referrals',$arrFields_refer,$arrValues_refer);
		$pharma_referral_id = mysqli_insert_id($dbConnection);	
		
	}
	
	foreach($data->temp_name as $key => $tmp_name )
	{
		$file_name = $data->file_name[$key];
		$temp_name = $data->temp_name[$key];
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
			$attachid= mysqli_insert_id($dbConnection);


		}	
	}*/
	
	
}
/*else if(HEALTH_API_KEY == $data ->api_key  && isset($data ->loginId) && isset($data ->patient_id) && isset($data ->diagnostic_ID) && isset($data->arrFields_customer) && isset($data->arrValues_customer) && isset($data->arrFields_refer) && isset($data->arrValues_refer) && isset($data->temp_name) && isset($data->file_name))
{
	$login_id       		= $data ->loginId;
	$patient_id    			= $data ->patient_id;
	$diagnostic_ID 			= $data ->diagnostic_ID;
	$arrFields_customer 	=$data->arrFields_customer;
	$arrValues_customer 	=$data->arrValues_customer;
	
	$arrFields_refer =$data->arrFields_refer;
	$arrValues_refer =$data->arrValues_refer;
	
	
	
	
	$check_diagno_customer = mysqlSelect("*","diagnostic_customer","diagnostic_id='".$diagnostic_ID."' AND login_id='".$login_id."'");
	
	if(empty($check_diagno_customer))
	{
		$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = mysqli_insert_id($dbConnection); //Get customer_id
		
		$arrFields_refer[] = 'diagnostic_customer_id';
		$arrValues_refer[] = $customer_id;
		
		$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
		$diagno_referral_id = mysqli_insert_id($dbConnection);
	}
	else
	{
		
		$customer_id = (int)$check_diagno_customer[0]['diagnostic_customer_id'];
		$arrFields_refer[] = 'diagnostic_customer_id';
		$arrValues_refer[] = $customer_id;
		
		
		$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
		$diagno_referral_id = mysqli_insert_id($dbConnection);
		
		 
	}
	
	foreach($data->temp_name as $key => $tmp_name )
	{
		$file_name = $data->file_name[$key];
		$temp_name = $data->temp_name[$key];
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
			$attachid= mysqli_insert_id($dbConnection);


		}	
	}
	
	$response = array('status' => "true");
	echo json_encode($response);
	
}*/


/*else if(HEALTH_API_KEY == $data ->api_key  && isset($data ->arrFields_attach) && isset($data ->arrValues_attach) )
{
	$arrFields_attach = $data ->arrFields_attach;
	$arrValues_attach = $data ->arrValues_attach;

	$Fields_attach = mysqlInsert('health_lab_test_request_attachments',$arrFields_attach,$arrValues_attach);
    $attachid= mysqli_insert_id($dbConnection);
	if(!empty($Fields_attach))
	{
		$result =1;
	}
	else
	{
		$result=0;
	}
	$response = array('status' => "true","insert"=>$result,"attachid"=>$attachid);
	echo json_encode($response);
	
	
	
}*/


else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}


?>


