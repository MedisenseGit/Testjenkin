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


if(HEALTH_API_KEY == $data ->api_key  )
{
	$order_type		= $data ->order_type;
	$login_id  	 	= $data ->loginId;
	$member_id		= $data ->member_id;
	
	//exit();
	
	
	$doc_id    	 	= $data ->doc_id;
	$patient_id		= $data ->patient_id;
	$diagnostic_ID	= $data ->diagnostic_ID;
	$episode_id		= $data ->episode_id;
	
	
	$getDoc= mysqlSelect("*","referal","ref_id='".$doc_id."'");
	$check_customer = mysqlSelect('*','diagnostic_customer',"md5(patient_id)='".$patient_id."' and patient_type='1'","","","","");
	$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$diagnostic_ID."'");
	
	$arrFields_customer		= $data ->arrFields_customer;
	$arrValues_customer		= $data ->arrValues_customer;
	
	$arrFields_refer		= $data ->arrFields_refer;
	$arrValues_refer		= $data ->arrValues_refer;
	
	$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
	$customer_id = $insert_diagnostic_customer; //Get customer_id
	
	$arrFields_refer[] = 'diagnostic_customer_id';
	$arrValues_refer[] = $customer_id;
	
	$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
	$diagno_referral_id = $insert_diagnostic_customer;

	/*if(empty($check_customer))
	{
		$insert_diagnostic_customer = mysqlInsert('diagnostic_customer',$arrFields_customer,$arrValues_customer);
		$customer_id = mysqli_insert_id(); //Get customer_id
		
		$arrFields_refer[] = 'diagnostic_customer_id';
		$arrValues_refer[] = $customer_id;
		
		$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
		$diagno_referral_id = mysqli_insert_id();
	}
	else
	{
		$customer_id = (int)$check_diagno_customer[0]['diagnostic_customer_id'];
		$arrFields_refer[] = 'diagnostic_customer_id';
		$arrValues_refer[] = $customer_id;
		
		$insert_diagnostic_customer = mysqlInsert('diagnostic_referrals',$arrFields_refer,$arrValues_refer);
		$diagno_referral_id = mysqli_insert_id();
	}*/
	
	if($order_type == "3" )
	{
		$group_test_id	= $data ->group_test_id;
		$main_test_id	= $data ->main_test_id;
		$test_name		= $data ->test_name;
		
		while(list($key_invest, $value_invest) = each($group_test_id))//ACADEMIC INFORMATION 
		{
		
			$arrOrderFields = array();
			$arrOrderValues = array();
			
			
			$arrOrderFields[]	=	'dr_id';
			$arrOrderValues[]	=	$diagno_referral_id;
			
			$arrOrderFields[]	=	'main_test_id';
			$arrOrderValues[]	=	$main_test_id[$key_invest];
			
			$arrOrderFields[]	=	'group_test_id';
			$arrOrderValues[]	=	$group_test_id[$key_invest];
			
			$arrOrderFields[]	=	'test_name';
			$arrOrderValues[]	=	$test_name[$key_invest];
			
			$arrOrderFields[]	=	'department';
			$arrOrderValues[]	=	$department[$key_invest];
			
			$arrOrderFields[]	=	'login_id';
			$arrOrderValues[]	=	$login_id;
			
			$arrOrderFields[]	=	'member_id';
			$arrOrderValues[]	=	$member_id;
			$insert_doctor_reg	= mysqlInsert('order_labtest',$arrOrderFields,$arrOrderValues);
			
	
		
		}

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
			$attachid= $pat_attach;
			
			$folder_name	=	"HealthLabTestsAttachments"; 
		$sub_folder		=	$attachid;
		$filename		=	$data->file_name[$key];
		$file_url		=	$data->temp_name[$key];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload


		}	
		
		
	}
	
	$link = "http://128.199.207.75/premium/Diagnostic-Refer?d=".$patient_id."&e=".(md5($episode_id));
	
	$patient_addrs		= $data ->txtAddress;
	$patient_loc		= $data ->txtCity;
	$pat_state			= $data ->txtState;
	$pat_country		= $data ->txtCountry;
	$patient_name		= $data ->txtPatientName;
	$patient_mob		= $data ->txtPhone;
	$patient_email		= $data ->txtEmail;
	
	
	//SMS notification to Diagnostic center
	/*if(!empty($getDiagno[0]['diagnosis_contact_num']))
	{
			$mobile = $getDiagno[0]['diagnosis_contact_num'];
			$msg = "Hello ".$getDiagno[0]['diagnosis_name'].", ".$data ->txtPatientName." has requested the following tests. Click here to view & update reports ".$link." - Thank you";
			send_msg($mobile,$msg);
	}*/
	
	
	
	
	//EMAIL notification Diagnostic center
	/*	if(!empty($getDiagno[0]['diagnosis_email'])){
		$PatAddress=$patient_addrs.",<br>".$patient_loc.", ".$pat_state.", ".$pat_country;
		
					$url_page = 'refer_diagno.php';
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($patient_name);
					$url .= "&patID=".urlencode(md5($patient_id));
					$url .= "&link=".urlencode($link);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($patient_mob);
					$url .= "&patEmail=".urlencode($patient_email);
					$url .= "&diagnoName=" . urlencode($getDiagno[0]['diagnosis_name']);
					$url .= "&tomail=" . urlencode($getDiagno[0]['diagnosis_email']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&replymail=" . urlencode($getDoc[0]['ref_mail']);						
				//	send_mail($url);	
		}*/
	
	$response = array('status' => "true");
	echo json_encode($response);
}

else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}


?>


