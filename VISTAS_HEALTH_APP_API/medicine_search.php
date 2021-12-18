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
if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey) 
	{
			
			$searchTerm = $_POST['searchTerm'];
			$response["prescription_details"] = array();
				
			$select1= mysqlSelect("pp_id,pharma_brand,pharma_priority,pharma_generic,generic_id","pharma_products","pharma_brand LIKE '%".$searchTerm."%' or pharma_generic LIKE '%".$searchTerm."%'","pharma_priority DESC","","","0,40");
			
			if(count($select1)>0)
			{ 
				foreach($select1 as $postPharma)
				{
					$stuff= array();
					$pp_id=$postPharma['pp_id'];	
					$pharma_brand=$postPharma['pharma_brand'];
					$pharma_generic=$postPharma['pharma_generic'];
					$generic_id=$postPharma['generic_id'];
					$pharma_priority=$postPharma['pharma_priority'];
					
					$stuff["freq_medicine_id"] = '0';	
					$stuff["pp_id"] = $pp_id;	
					$stuff["med_trade_name"] = $pharma_brand;	
					$stuff["generic_id"] = $generic_id;	
					$stuff["pharma_generic"] = $pharma_generic;	
					$stuff["pharma_priority"] = $pharma_priority;	
					$stuff["med_frequency"] = "";	
					$stuff["med_timing"] = "";
					$stuff["med_duration"] = "";
					$stuff["doc_id"] = $admin_id;
					$stuff["doc_type"] = '1';
					$stuff["freq_count"] = '0';
					$stuff["med_frequency_morning"] = "";
					$stuff["med_frequency_noon"] = "";
					$stuff["med_frequency_night"] = "";
					$stuff["med_duration_type"] = "";
					$stuff["prescription_instruction"] = "";
					
					array_push($response["prescription_details"], $stuff);
				}
			
			} 
			else 
			{
				$select2= mysqlSelect("*","doctor_frequent_medicine","(med_trade_name LIKE '%".$searchTerm."%' or med_generic_name LIKE '%".$searchTerm."%') and doc_id='".$admin_id."' and doc_type = '1'","freq_count DESC","","","0,40");
				foreach($select2 as $postPharma2)
				{
					$stuff= array();
					$freq_medicine_id=$postPharma2['freq_medicine_id'];
					$pp_id=$postPharma2['pp_id'];	
					$med_trade_name=$postPharma2['med_trade_name'];
					$med_generic_name=$postPharma2['med_generic_name'];
					$med_frequency=$postPharma2['med_frequency'];
					$med_timing=$postPharma2['med_timing'];
					$med_duration=$postPharma2['med_duration'];
					$doc_id=$postPharma2['doc_id'];
					$doc_type=$postPharma2['doc_type'];
					$freq_count=$postPharma2['freq_count'];
					$med_frequency_morning = $postPharma2['med_frequency_morning'];
					$med_frequency_noon = $postPharma2['med_frequency_noon'];
					$med_frequency_night = $postPharma2['med_frequency_night'];
					$med_duration_type = $postPharma2['med_duration_type'];
					$prescription_instruction = $postPharma2['prescription_instruction'];
					
					$stuff["freq_medicine_id"] = $freq_medicine_id;	
					$stuff["pp_id"] = $pp_id;	
					$stuff["med_trade_name"] = $med_trade_name;	
					$stuff["generic_id"] = '0';	
					$stuff["pharma_generic"] = $med_generic_name;	
					$stuff["pharma_priority"] = '0';	
					$stuff["med_frequency"] = $med_frequency;	
					$stuff["med_timing"] = $med_timing;
					$stuff["med_duration"] = $med_duration;
					$stuff["doc_id"] = $doc_id;
					$stuff["doc_type"] = $doc_type;
					$stuff["freq_count"] = $freq_count;
					$stuff["med_frequency_morning"] = $med_frequency_morning;
					$stuff["med_frequency_noon"] = $med_frequency_noon;
					$stuff["med_frequency_night"] = $med_frequency_night;
					$stuff["med_duration_type"] = $med_duration_type;
					$stuff["prescription_instruction"] = $prescription_instruction;
					
					 array_push($response["prescription_details"], $stuff);
				}
				
			}
			
			 $response["status"] = "true";
			 echo(json_encode($response));
			
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
