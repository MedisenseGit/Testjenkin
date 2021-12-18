<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


function Haversine($lat_from, $lon_from, $lat_to, $lon_to) {
    $radius = 6371000;
    $delta_lat = deg2rad($lat_to-$lat_from);
    $delta_lon = deg2rad($lon_to-$lon_from);

    $a = sin($delta_lat/2) * sin($delta_lat/2) +
        cos(deg2rad($lat_from)) * cos(deg2rad($lat_to)) *
        sin($delta_lon/2) * sin($delta_lon/2);
    $c = 2*atan2(sqrt($a), sqrt(1-$a));

    // Convert the distance from meters to miles
    return ceil(($radius*$c)*0.000621371);
}


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
$data = json_decode(file_get_contents('php://input'), true);
// Doctors Near Me
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
			
			$searchTerm = $_POST['searchTerm'];
			
			$prescription_details = array();
			$selectInvest= mysqlSelect("*","patient_diagnosis_tests","test_name_site_name LIKE '%".$searchTerm."%'","test_name_site_name asc","","","");
			
			foreach($selectInvest as $Invest){
			
			
	if($selectInvest[0]['group_test']=="Y")
	{
		$getTestList= mysqlSelect("test_id as group_test_id,sub_test_id as main_test_id","patient_diagnosis_group_tests ","test_id='".$selectInvest[0]['test_id']."'","","","","");	
		if(!empty($getTestList))
		{
			while(list($key, $value) = each($getTestList))
			{
				$getTestName= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,max_range,test_units","patient_diagnosis_tests","test_id='".$value['main_test_id']."'","","","","");
			
			
				$check_temp_invest_active = mysqlSelect("episode_id,patient_id,main_test_id,pti_id,test_name,group_test_id,department,status","patient_temp_investigation","main_test_id='".$getTestName[0]['test_id']."'  and doc_type='1'","","","","");
				
				$getPrescList['pti_id']			=	$check_temp_invest_active[0]['pti_id'];
				$getPrescList['test_name']	 	=	$check_temp_invest_active[0]['test_name'];
				$getPrescList['main_test_id']	=	$check_temp_invest_active[0]['main_test_id'];
				$getPrescList['group_test_id']	=	$check_temp_invest_active[0]['group_test_id'];
				
				
				
				array_push($prescription_details, $getPrescList);
				
			}
		}
	
		
	}
	if($selectInvest[0]['group_test']=="N")
	{
		$getTestList= mysqlSelect("test_id,test_name_site_name,normal_range,is_mref_range,min_range,normal_range,max_range,test_units","patient_diagnosis_tests","id='".$selectInvest[0]['id']."'","","","","");
		
		//echo $getTestList[0]['test_id'];
		
		$check_temp_invest_active = mysqlSelect("episode_id,patient_id,main_test_id,pti_id,test_name,group_test_id,department,status","patient_temp_investigation","main_test_id='".$getTestList[0]['test_id']."'","","","","");
		
		//var_dump($check_temp_invest_active );
		foreach($check_temp_invest_active as $check_invest_active)
		{
			$getPrescList['pti_id']			=	$check_temp_invest_active[0]['pti_id'];
			$getPrescList['test_name']	 	=	$check_temp_invest_active[0]['test_name'];
			$getPrescList['main_test_id']	=	$check_temp_invest_active[0]['main_test_id'];
			$getPrescList['group_test_id']	=	$check_temp_invest_active[0]['group_test_id'];
			
			
			array_push($prescription_details, $getPrescList);
		}
		
	}
			}
			
		
			
		
			$success = array('status' => "false","lab_test_details" => $prescription_details, 'err_msg' => '');
			echo json_encode($success);
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
