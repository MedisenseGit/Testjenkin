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
if ($headers)
{
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
//$data = json_decode(file_get_contents('php://input'), true);

// Health Reports Lists
/*if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{*/
		
		$user_id 	  = $user_id;
		$member_id	  = $_POST['member_id'];
		$country_name = $_POST['country_name'];
		//echo $country_name;
		
		//user family member 
		$user_family_member = mysqlSelect("*","user_family_member","(member_id) ='".$member_id."'","","","","");
		
		
		//adults vaccine
		$vaccinelist_details = mysqlSelect("*","vaccine_adults","(member_id) ='".$member_id."'","id DESC","","","");
		if(!empty($user_family_member[0]['age']))
		{
			$vaccine_details= array();
			if($user_family_member[0]['age'] >= 18)
			{
				//echo "greater..";
				foreach($vaccinelist_details as $result_vaccineList) 
				{
						$getVaccineList['report_id']	=	$result_vaccineList['id'];
						$getVaccineList['member_id']	=	$result_vaccineList['member_id'];
						$getVaccineList['given_date']	=	$result_vaccineList['given_date'];
						$getVaccineList['vaccine_name']	=	$result_vaccineList['vaccine_name'];
						$getVaccineList['hospital_name']=	$result_vaccineList['hospital_name'];
						$getVaccineList['dose']			=	$result_vaccineList['dose'];
						$getVaccineList['created_date']	=	$result_vaccineList['created_date'];
						
						$attachment_details = mysqlSelect("id as attachment_id, report_name as report_name","vaccine_adults_reports","vaccine_id ='".$result_vaccineList['id']."'","id ASC","","","");
						$getVaccineList['attachments']	= $attachment_details;
						
					array_push($vaccine_details, $getVaccineList);
				}
			}
			else
			{
					//echo "less";
					//child vaccine
					$getCountry=mysqlSelect('*','countries',"country_name='".$country_name."'","","","","");

					if(!empty($getCountry) && $getCountry[0]['country_id']=='179')
					{ 
						//only for qatar vaccine
						$country_id = $getCountry[0]['country_id'];
					}
					else 
					{
						$country_id = '0'; 					// Default Local doctors 
					}

					$childId	= mysqlSelect("*","child_tab","(member_id)='".$member_id."'","","","","");

					$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","");

					$vaccineduration_details	= array();
					
					$actual_array	= array();

					foreach($vaccineduration as $result_vaccineduration) 
					{

						$getVaccineDur['duartion_id']=$result_vaccineduration['duartion_id'];
						$getVaccineDur['duration_name']=$result_vaccineduration['duration_name'];

						$vaccinename = mysqlSelect("*","vaccine_mapping as a left join vaccine_tab as b on a.vaccine_tab_id=b.vaccine_id","a.vaccine_duration_id='".$result_vaccineduration['duartion_id']."' AND b.country_id ='".$country_id."'","a.vaccine_duration_id asc","","","");

						$getVaccineDur['vaccinename']=$vaccinename;

						if(!empty($childId))
						{
							$getLastVaccineVal= mysqlSelect("*","vaccine_child_tab","child_tab_id='".$childId[0]['child_id']."' and vaccine_duration_id='".$result_vaccineduration['duartion_id']."'","","","","");
						}
						else
						{
							$getLastVaccineVal=array();
						}
						$getVaccineDur['LastVaccineVal']=$getLastVaccineVal;

						
						//foreach($vaccinename as $vaccinenameList) {
							
						if(!empty($childId))
						{

							$getActualVaccine= mysqlSelect("*","vaccine_child_tab","child_tab_id='".$childId[0]['child_id']."' and vaccine_id='".$vaccinename[0]['vaccine_id']."' and vaccine_duration_id='".$vaccinename[0]['vaccine_duration_id']."'","","","","");
						}
						else
						{
							$getActualVaccine=array();
						}
						$getVaccineDur['ActualVaccine']	=	$getActualVaccine;
						array_push($vaccineduration_details, $getVaccineDur);
						
					}
			
			}
		}
		$share_tests = array('result' => "success", 'vaccine_details' => $vaccine_details, 'vaccineduration' => $vaccineduration_details, 'err_msg' => '');
		echo json_encode($share_tests);
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
