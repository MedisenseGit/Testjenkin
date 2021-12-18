

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
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);
$data = json_decode(file_get_contents('php://input'), true);
// Doctors Near Me
if(!empty($doctor_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{
			
			$searchTerm = $_POST['searchTerm'];
			
			$get_Investigation  = mysqlSelect("*","patient_diagnosis_tests","test_name_site_name LIKE '%".$searchTerm."%'","test_name_site_name asc","","","");
			
			$success = array('status' => "true","Investigation" => $get_Investigation);
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
