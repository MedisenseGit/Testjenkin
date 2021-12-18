<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//CHAT HISTORY
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['patient_id']) && isset($_POST['doc_refId'])) {
	 
	$patient_id = $_POST['patient_id'];
	$doc_refid = $_POST['doc_refId'];
	
	$response["chat_detail"] = array();	 

	$result_chat = $objQuery->mysqlSelect('*','chat_notification',"patient_id='".$patient_id."' and ref_id='".$doc_refid."'");
	foreach($result_chat as $postResultList){
		//echo $postResultList['partner_id'];
		$stuff= array();
		if($postResultList['partner_id'] ==0 && $postResultList['ref_id']!=0) {
			
			$doc_res = $objQuery->mysqlSelect('ref_id, ref_name,doc_photo','referal',"ref_id='".$postResultList['ref_id']."'");
				
				 $doc_name = $doc_res[0]['ref_name'];
				 $doc_photo = "https://medisensecrm.com/Doc/".$doc_res[0]['ref_id']."/".$doc_res[0]['doc_photo'];	
				 
		
		}
		else if($postResultList['partner_id']!=0 && $postResultList['ref_id']!=0)
		{

			$partner_res = $objQuery->mysqlSelect('partner_id, contact_person, doc_photo','our_partners ',"partner_id='".$postResultList['partner_id']."'");
				
				 $doc_name = $partner_res[0]['contact_person'];
				 $doc_photo= "https://medisensecrm.com/standard/partnerProfilePic/".$postResultList['partner_id']."/".$partner_res[0]['doc_photo'];	
				 
		}
		
				$stuff["patient_id"] = $postResultList['patient_id'];
				$stuff["chat_id"] = $postResultList['chat_id'];
				$stuff["partner_id"] = $postResultList['partner_id'];	
				 $stuff["doc_id"] =  $postResultList['ref_id'];
				 $stuff["doc_name"] = $doc_name;
				$stuff["chat_note"] = $postResultList['chat_note'];
				$stuff["time_stamp"] = $postResultList['TImestamp'];
				 $stuff["doc_photo"] = $doc_photo;
		
			array_push($response["chat_detail"], $stuff);
	}
		
			
			$response["status"] = "true";
			echo(json_encode($response));
	


}


?>