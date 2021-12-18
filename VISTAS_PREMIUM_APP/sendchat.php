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


if(!empty($doctor_id) && !empty($finalHash))
{

	if($finalHash == $hashKey) 
	{
		//$patient_history = array();
		$Patient_id      = $data['patient_id'];//21668;//$_POST['Patient_id'];
		
		
		$chathistory = array();
		
		$getRefDoc = mysqlSelect("a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name,b.doc_spec as doc_spec,a.timestamp as timestamp","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=a.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","a.patient_id='".$Patient_id."'","a.timestamp desc","","","");
		foreach($getRefDoc as $getRefDocList)
		{ 
		
			$getSpec= mysqlSelect("spec_name","specialization","spec_id='".$getRefDocList['doc_spec']."'","","","","");
			
			//$getChatList['spec_name']    		 = $getSpec[0]['spec_name'];
			
			//$getChatList['ref_id']       		 = $getRefDocList['ref_id'];
			//$getChatList['ref_name']       		 = $getRefDocList['ref_name'];
			
			
			$getchatHistory = mysqlSelect("a.status_id as status_id,a.user_id as user_id,a.msg_send_status as msg_send_status,a.patient_id as patient_id,a.chat_id as chat_id,b.ref_id as ref_id,b.ref_name as ref_name,b.doc_photo as doc_photo,a.ref_id as ref_id,a.partner_id as partner_id,a.chat_note as chat_note,b.doc_photo as doc_photo,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$getRefDocList['patient_id']."'and a.ref_id='".$getRefDocList['ref_id']."'","a.chat_id desc","","",""); 
			foreach($getchatHistory as $chatList)
			{
				
				
				$getChatList['chat_id']       	 		 = $chatList['chat_id'];
				$getChatList['patient_id']       	     = $chatList['patient_id'];
				$getChatList['chat_ref_id']       		 = $chatList['ref_id'];
				$getChatList['chat_chat_note']       	 = $chatList['chat_note'];
				$getChatList['user_id']       			 = $chatList['user_id'];
				$getChatList['status_id']       		 = $chatList['status_id'];
				$getChatList['chat_partner_id']       	 = $chatList['partner_id'];
				$getChatList['msg_send_status']       	 = $chatList['msg_send_status'];
				$getChatList['chat_Ref_Date']       	 = $chatList['Ref_Date'];
				
				
				$getChatList['chat_doc_photo']       	 = $chatList['doc_photo'];
				$getChatList['chat_ref_name']       	 = $chatList['ref_name'];
				
					
			}
			$getChatList['patient_status']       = $getRefDocList['status2'];
			$getChatList['doc_photo']       	 = $getRefDocList['doc_photo'];
			
			array_push($chathistory, $getChatList);
		}
		
		
		$success = array('status' => "true", "chat_history"=>$chathistory, 'err_msg' => '');
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