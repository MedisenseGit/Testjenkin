<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');


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

/*if(!empty($doctor_id) && !empty($finalHash))
{
	
	if($finalHash == $hashKey) 
	{
		*/
		
		$pageVal     = $data['page_val'];
		
		if($pageVal==1)
		{
			$this1 = 0;
			$page_limit = 8;
		}
		else if($pageVal>1)
		{
		  $limit = 8*$pageVal;
		  $page_limit = 8;
		  $this1 = $limit-8;
		}
		
		//echo"this1 =".$this1;
		//echo"page_limit =".$page_limit;
		$recived_cases= array();
		
		$allRecord = mysqlSelect("DISTINCT (a.patient_id) AS Patient_Id, a.patient_name AS Patient_Name, a.TImestamp AS Reg_Date, b.ref_id AS Doc_Id,a.patient_email as Email,a.patient_mob as Mobile,a.patient_age as Age,a.pat_state as state,a.pat_country as country,a.patient_loc as location,a.patient_addrs as address","patient_tab AS a INNER JOIN patient_referal AS b ON a.patient_id = b.patient_id","b.ref_id = '".$doctor_id."'","","","","","$this1, $page_limit");	
		
		foreach($allRecord as $list)
		{ 
			
				$getEpiList['patient_id']   = $list['Patient_Id'];
				$getEpiList['Reg_Date']     = $list['Reg_Date'];
				$getEpiList['Patient_Name'] = $list['Patient_Name'];
				$getEpiList['Patient_Email'] = $list['Email'];
				$getEpiList['Patient_Mobile'] = $list['Mobile'];
				$getEpiList['Patient_Age'] 		= $list['Age'];
				$getEpiList['Patient_state'] = $list['state'];
				$getEpiList['Patient_country'] = $list['country'];
				$getEpiList['Patient_address'] = $list['address'];
				//echo $list['Doc_Id'];
				$allRecord = mysqlSelect("ref_name","referal","ref_id='".$list['Doc_Id']."'","","","","");
				if($allRecord[0]['ref_name']!="" &&  $allRecord[0]['ref_name']!='null')
				{
					//echo"hghg".$allRecord[0]['ref_name'];
					$getEpiList['ReferredTo'] = $allRecord[0]['ref_name'];
				}
				
			array_push($recived_cases, $getEpiList);
			
		}
		
		if(COUNT($appoint_details)==$page_limit)
		{
			$page_val=$pageVal+1;
		}
		
		else
		{
			$page_val=0;
		}		
	
		$success = array('status' => "true", "recived_cases"=>$recived_cases, "pagination_val" => $page_val ,'err_msg' => '');
		echo json_encode($success);
		
		
		
/*	}
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