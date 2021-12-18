<?php
ob_start();
session_start();
error_reporting(0);

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

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
//$data = json_decode(file_get_contents('php://input'), true);

// Health Reports Lists
if(!empty($user_id) && !empty($finalHash)) 
{	
	if($finalHash == $hashKey) 
	{
		
		$user_id 		= $user_id;
		$member_id 		= $_POST['member_id'];
		$country_name 	= $_POST['country_name'];
		$txtVaccineName = addslashes($_POST['vaccine_name']);
		$txtHospital 	= addslashes($_POST['hospital_name']);
		$txtDose		= $_POST['dose'];
		$txtGivenDate 	= $_POST['certifiacte_date'];
		$Cur_Date 		= date('Y-m-d H:i:s');
		$file_name 		= $_FILES['file-3'];
		
			$arrFields = array();
			$arrValues = array();

			$arrFields[] = 'login_id';
			$arrValues[] = $user_id;
			
			$arrFields[] = 'member_id';
			$arrValues[] = $member_id;

			$arrFields[] = 'given_date';
			$arrValues[] = date('Y-m-d',strtotime($txtGivenDate));

			$arrFields[] = 'vaccine_name';
			$arrValues[] = $txtVaccineName;

			$arrFields[] = 'hospital_name';
			$arrValues[] = $txtHospital;
			
			$arrFields[] = 'dose';
			$arrValues[] = $txtDose;
			
			$arrFields[] = 'created_date';
			$arrValues[] = $Cur_Date;


			$usercraete=mysqlInsert('vaccine_adults',$arrFields,$arrValues);
			$id = $usercraete;
			
			//Add Adult Vaccination functionality
			$errors = array();
			foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
			{

				$file_name = $_FILES['file-3']['name'][$key];
				$file_size = $_FILES['file-3']['size'][$key];
				$file_tmp  = $_FILES['file-3']['tmp_name'][$key];
				$file_type = $_FILES['file-3']['type'][$key];

				if(!empty($file_name))
				{
					$Photo1  = $file_name;
					$arrFields_attach = array();
					$arrValues_attach = array();

					$arrFields_attach[] = 'vaccine_id';
					$arrValues_attach[] = $id;
					
					$arrFields_attach[] = 'login_id';
					$arrValues_attach[] = $user_id;
					
					$arrFields_attach[] = 'member_id';
					$arrValues_attach[] = $member_id;

					$arrFields_attach[] = 'report_name';
					$arrValues_attach[] = $file_name;
					
					$arrFields_attach[] = 'created_date';
					$arrValues_attach[] = $Cur_Date;


					$pat_attach=mysqlInsert('vaccine_adults_reports',$arrFields_attach,$arrValues_attach);
					$attachid= $pat_attach;

					//Uploading image file
					$uploaddirectory = realpath("../VaccineAdultReports");

					$uploaddir = $uploaddirectory . "/" .$attachid;
					$dotpos = strpos($uploaddir, '.');

					$Photo1 = str_replace(substr($Photo1, 0, $dotpos), $attachid, $Photo1);
					$uploadfile = $uploaddir . "/" . $Photo1;


					//Checking whether folder with category id already exist or not.
					if (file_exists($uploaddir)) 
					{
						//echo "The file $uploaddir exists";
					} 
					else 
					{
						$newdir = mkdir($uploaddirectory . "/" . $attachid, 0777, true);
					}
				}  
			}
		
		
		
		//Get All Adults Vaccine
		$vaccinelist_details = mysqlSelect("*","vaccine_adults","(member_id) ='".$member_id."'","id DESC","","","");
		$vaccine_details= array();
		foreach($vaccinelist_details as $result_vaccineList)
		{
				$getVaccineList['report_id']=$result_vaccineList['id'];
				$getVaccineList['member_id']=$result_vaccineList['member_id'];
				$getVaccineList['given_date']=$result_vaccineList['given_date'];
				$getVaccineList['vaccine_name']=$result_vaccineList['vaccine_name'];
				$getVaccineList['hospital_name']=$result_vaccineList['hospital_name'];
				$getVaccineList['dose']=$result_vaccineList['dose'];
				$getVaccineList['created_date']=$result_vaccineList['created_date'];
				
				$attachment_details = mysqlSelect("id as attachment_id, report_name as report_name","vaccine_adults_reports","vaccine_id ='".$result_vaccineList['id']."'","id ASC","","","");
				$getVaccineList['attachments']= $attachment_details;
				
				array_push($vaccine_details, $getVaccineList);
		}
		
		
		$share_tests = array('result' => "success", 'vaccine_details' => $vaccine_details, 'err_msg' => '');
		echo json_encode($share_tests);
	}
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
}
?>
