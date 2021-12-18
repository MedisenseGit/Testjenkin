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
    $user_id   = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey   = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//$data = json_decode(file_get_contents('php://input'), true);

/*
if(!empty($user_id) && !empty($finalHash))
{

	if($finalHash == $hashKey) 
	{*/
		$patient_history = array();
		$Patient_id      = $_POST['patient_id'];//21668;
		
		
		$GetPatient = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_src as patient_src,a.patient_age as patient_age,a.patient_gen as patient_gen,a.merital_status as merital_status,a.weight as weight,a.hyper_cond as hyper_cond,a.medDept as medDept, a.diabetes_cond as diabetes_cond,a.qualification as qualification,a.contact_person as contact_person,a.patient_addrs as patient_addrs,a.patient_loc as patient_loc,a.pat_state as pat_state,a.patient_mob as patient_mob,a.patient_email as patient_email,a.patient_complaint as patient_complaint,a.patient_desc as patient_desc,a.pat_query as pat_query,a.TImestamp as TImestamp,b.status1 as status1,b.status2,b.bucket_status as bucket_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.patient_id='".$Patient_id."' and a.service_type=2","","","","");
		
		$patient_detail =	array();
		foreach($GetPatient as $list)
		{ 
			
			$getEpiList['patient_id']    		 = $list['patient_id'];
			$getEpiList['patient_name']    		 = $list['patient_name'];
			$getEpiList['patient_age']    		 = $list['patient_age'];
			$getEpiList['patient_gen']    		 = $list['patient_gen'];
			$getEpiList['hyper_cond']      		 = $list['hyper_cond'];
			$getEpiList['diabetes_cond']   		 = $list['diabetes_cond'];
			$getEpiList['weight']    			 = $list['weight'];
			$getEpiList['city']    				 = $list['patient_loc'];
			$getEpiList['address']    			 = $list['patient_addrs'];
			$getEpiList['Contact_Person']   	 = $list['contact_person'];
			$getEpiList['patient_complaint']	 = $list['patient_complaint'];
			$getEpiList['Medical_Query']		 = $list['pat_query'];
			$getEpiList['patient_Description']	 = $list['patient_desc'];
			$getEpiList['patient_status']	 	 = $list['bucket_status'];
			$getEpiList['Marital_Status']	 	 = $list['merital_status'];
			$getEpiList['patient_Weight']	 	 = $list['weight'];
			$getEpiList['patient_State']	 	 = $list['pat_state'];
			$getEpiList['patient_Qualification'] = $list['qualification'];
			$getEpiList['contact_person']		 = $list['contact_person'];
			$getEpiList['patient_mob']			 = $list['patient_mob'];
			$getEpiList['patient_email']		 = $list['patient_email'];
			$getEpiList['Referred_By']		 	 = $list['source_name'];
			$getEpiList['Referred_By']		 	 = $list['pat_query'];
			
			array_push($patient_detail, $getEpiList);
			
		}
		$getEpiList['patient_details']	= $patient_detail;
		$attacment_list=array();
		
		$attachDet = mysqlSelect("*","patient_attachment","(patient_id)='".$Patient_id."'","","","","");
		
		foreach($attachDet as $attachList)
		{ 
			
			$attachments['attach_id']    		 = $attachList['attach_id'];
			$attachments['attachments']    		 = $attachList['attachments'];
			
			array_push($attacment_list, $attachments);
		
		}	
		$getEpiList['attacment_list']	= $attacment_list;
		
		
		$chathistory = array();
		
		$getRefDoc = mysqlSelect("a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name,b.doc_spec as doc_spec,a.timestamp as timestamp","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=a.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","a.patient_id='".$GetPatient[0]['patient_id']."'","a.timestamp desc","","","");
		foreach($getRefDoc as $getRefDocList)
		{ 
		
			$getSpec= mysqlSelect("spec_name","specialization","spec_id='".$getRefDocList['doc_spec']."'","","","","");
			
			$getChatList['spec_name']    		 = $getSpec[0]['spec_name'];
			$getChatList['patient_status']       = $getRefDocList['status2'];
			$getChatList['doc_photo']       	 = $getRefDocList['doc_photo'];
			$getChatList['ref_id']       		 = $getRefDocList['ref_id'];
			$getChatList['ref_name']       		 = $getRefDocList['ref_name'];
			
			
			$getchatHistory = mysqlSelect("b.ref_id as ref_id,b.ref_name as ref_name,b.doc_photo as doc_photo,a.ref_id as ref_id,a.partner_id as partner_id,a.chat_note as chat_note,b.doc_photo as doc_photo,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$getRefDocList['patient_id']."'and a.ref_id='".$getRefDocList['ref_id']."'","a.chat_id desc","","",""); 
			foreach($getchatHistory as $chatList)
			{
				
				$getChatList['chat_ref_id']       		 = $chatList['ref_id'];
				$getChatList['chat_partner_id']       	 = $chatList['partner_id'];
				$getChatList['chat_doc_photo']       	 = $chatList['doc_photo'];
				$getChatList['chat_ref_name']       	 = $chatList['ref_name'];
				$getChatList['chat_Ref_Date']       	 = $chatList['Ref_Date'];
				$getChatList['chat_chat_note']       	 = $chatList['chat_note'];
				
			}
			
			array_push($chathistory, $getChatList);
		}
		
		$getEpiList['chathistory']	= $chathistory;
		
		array_push($patient_history, $getEpiList);
		
		$success = array('status' => "true", "patient_history"=>$patient_history, 'err_msg' => '');
		echo json_encode($success);

	//}
	/*else 
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