<?php 
ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

require_once("../classes/querymaker.class.php");

ob_start();


$doc_id		 = $_POST['doc_id'];
$update_type = $_POST['update_type'];
$Medisense	 = $_POST['Medisense'];
$Medical_Professionist		= $_POST['Medical_Professionist'];


if($doc_id!="")
{
	$data_val="1";
	
}
else {
	$data_val="0";
}

			$arrFields_attach = array();
			$arrValues_attach = array();
			
			if($update_type == 1)
			{
				$verified_by	 = $_POST['verified_by'];
				$video_verify	 = $_POST['Video_Verified'];
				$arrFields_attach[] = 'verified_by_medisense';
				$arrValues_attach[] = $data_val;
				$arrFields_attach[] = 'verified_by_medisense_user';
				$arrValues_attach[] = $verified_by;
				$arrFields_attach[] = 'video_veification_status';
				$arrValues_attach[] = $video_verify;
				if(!empty($Medisense))
				{
					$arrFields_attach[] = 'comments_by_medisense';
					$arrValues_attach[] = $Medisense;
				}
			}

			if($update_type == 2)
			{
				if(!empty($Medical_Professionist))
				{
					$arrFields_attach[] = 'comments_by_medical_professional';
					$arrValues_attach[] = $Medical_Professionist;
				}
				$arrFields_attach[] = 'verified_by_medical_professional';
				$arrValues_attach[] = $data_val;
			}
			
		$usercraete1=mysqlUpdate('referal',$arrFields_attach,$arrValues_attach,"ref_id='".$doc_id."'");
		//echo $usercraete1;
		
		
		$response = array('status' => "true");
		
		echo json_encode($response)

?>