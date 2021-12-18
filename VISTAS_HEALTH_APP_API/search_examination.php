<?php
ob_start();
session_start();
error_reporting(0);

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

function Haversine($lat_from, $lon_from, $lat_to, $lon_to)
{
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
/*if(!empty($user_id) && !empty($finalHash)) 
{	

	if($finalHash == $hashKey) 
	{*/
			
		$searchTerm = $_POST['searchTerm'];
		
		$get_examination		= $objQuery->mysqlSelect("*","examination","examination LIKE '%".$searchTerm."%'","examination asc","","","");
		
		$success = array('status' => "false","examination" => $get_examination);
		echo json_encode($success);
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
}
*/

?>
