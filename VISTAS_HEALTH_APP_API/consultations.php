<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//My Consultation Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$login_id = $user_id;
		
		$get_MobileNo = mysqlSelect('sub_contact','login_user',"login_id ='".$login_id."'","login_id DESC","","","");
		$txtMobile = $get_MobileNo[0]['sub_contact'];
		
		$get_MyPatient = mysqlSelect('b.Visiting_date as Visiting_date,b.Visiting_time as Visiting_time,a.patient_id as patient_id, a.patient_name as patient_name, a.patient_age as patient_age, a.patient_email as patient_email, a.patient_gen as patient_gen, a.height as height, a.weight as weight, a.hyper_cond as hyper_cond, a.smoking as smoking, a.alcoholic as alcoholic, a.diabetes_cond as diabetes_cond, a.tele_communication as tele_communication, a.doc_id as doc_id, a.system_date as system_date, a.member_id as member_id, a.pat_bp as pat_bp, a.pat_thyroid as pat_thyroid, a.pat_cholestrole as pat_cholestrole, a.pat_epilepsy as pat_epilepsy, a.pat_asthama as pat_asthama, a.pat_video_link as pat_video_link, b.pay_status as pay_status, c.ref_name as ref_name','doc_my_patient as a inner join appointment_transaction_detail as b on b.patient_id = a.patient_id inner join referal as c on c.ref_id = a.doc_id',"a.patient_mob ='".$txtMobile."'","a.TImestamp DESC","","","");
		$consulat_details= array();
		foreach($get_MyPatient as $my_patientLists)
		{	
			$getConsultList['patient_id']		=	$my_patientLists['patient_id'];
			$getConsultList['patient_name']		=	$my_patientLists['patient_name'];
			$getConsultList['patient_age']		=	$my_patientLists['patient_age'];
			$getConsultList['patient_email']	=	$my_patientLists['patient_email'];
			$getConsultList['patient_gen']		=	$my_patientLists['patient_gen'];
			$getConsultList['height']			=	$my_patientLists['height'];
			$getConsultList['weight']			=	$my_patientLists['weight'];
			$getConsultList['hyper_cond']		=	$my_patientLists['hyper_cond'];
			$getConsultList['smoking']			=	$my_patientLists['smoking'];
			$getConsultList['alcoholic']		=	$my_patientLists['alcoholic'];
			$getConsultList['diabetes_cond']	=	$my_patientLists['diabetes_cond'];
			$getConsultList['tele_communication']=	$my_patientLists['tele_communication'];
			$getConsultList['doc_id']			=	$my_patientLists['doc_id'];
			$getConsultList['system_date']		=	$my_patientLists['system_date'];
			$getConsultList['member_id']		=	$my_patientLists['member_id'];
			$getConsultList['pat_bp']			=	$my_patientLists['pat_bp'];
			$getConsultList['pat_thyroid']		=	$my_patientLists['pat_thyroid'];
			$getConsultList['pat_cholestrole']	=	$my_patientLists['pat_cholestrole'];
			$getConsultList['pat_epilepsy']		=	$my_patientLists['pat_epilepsy'];
			$getConsultList['pat_asthama']		=	$my_patientLists['pat_asthama'];
			$getConsultList['pat_video_link']	=	$my_patientLists['pat_video_link'];
			$getConsultList['pay_status']		=	$my_patientLists['pay_status'];
			$getConsultList['ref_name']			=	$my_patientLists['ref_name'];
			$getConsultList['visiting_time']	=	$my_patientLists['Visiting_time'];
			$getConsultList['visiting_date']	=	$my_patientLists['Visiting_date'];
			$getConsultList['time_slot']		=	$my_patientLists['Time_slot'];
			
			
			
			
			array_push($consulat_details, $getConsultList);
		}
		
	/*	$get_Members = mysqlSelect('*','user_family_member',"user_id ='".$login_id."'","member_id ASC","","","");
		$consulat_details= array();
		
		foreach($get_Members as $getMemberLists){
			//$mem =$getMemberLists['member_id'];
			
			$get_MyPatient = mysqlSelect('a.patient_id as patient_id, a.patient_name as patient_name, a.patient_age as patient_age, a.patient_email as patient_email, a.patient_gen as patient_gen, a.height as height, a.weight as weight, a.hyper_cond as hyper_cond, a.smoking as smoking, a.alcoholic as alcoholic, a.diabetes_cond as diabetes_cond, a.tele_communication as tele_communication, a.doc_id as doc_id, a.system_date as system_date, a.member_id as member_id, a.pat_bp as pat_bp, a.pat_thyroid as pat_thyroid, a.pat_cholestrole as pat_cholestrole, a.pat_epilepsy as pat_epilepsy, a.pat_asthama as pat_asthama, a.pat_video_link as pat_video_link, b.pay_status as pay_status, c.ref_name as ref_name','doc_my_patient as a inner join appointment_transaction_detail as b on b.patient_id = a.patient_id inner join referal as c on c.ref_id = a.doc_id',"a.member_id ='".$getMemberLists['member_id']."'","a.TImestamp DESC","","","");
			foreach($get_MyPatient as $my_patientLists){	
				$getConsultList['patient_id']=$my_patientLists['patient_id'];
				$getConsultList['patient_name']=$my_patientLists['patient_name'];
				$getConsultList['patient_age']=$my_patientLists['patient_age'];
				$getConsultList['patient_email']=$my_patientLists['patient_email'];
				$getConsultList['patient_gen']=$my_patientLists['patient_gen'];
				$getConsultList['height']=$my_patientLists['height'];
				$getConsultList['weight']=$my_patientLists['weight'];
				$getConsultList['hyper_cond']=$my_patientLists['hyper_cond'];
				$getConsultList['smoking']=$my_patientLists['smoking'];
				$getConsultList['alcoholic']=$my_patientLists['alcoholic'];
				$getConsultList['diabetes_cond']=$my_patientLists['diabetes_cond'];
				$getConsultList['tele_communication']=$my_patientLists['tele_communication'];
				$getConsultList['doc_id']=$my_patientLists['doc_id'];
				$getConsultList['system_date']=$my_patientLists['system_date'];
				$getConsultList['member_id']=$my_patientLists['member_id'];
				$getConsultList['pat_bp']=$my_patientLists['pat_bp'];
				$getConsultList['pat_thyroid']=$my_patientLists['pat_thyroid'];
				$getConsultList['pat_cholestrole']=$my_patientLists['pat_cholestrole'];
				$getConsultList['pat_epilepsy']=$my_patientLists['pat_epilepsy'];
				$getConsultList['pat_asthama']=$my_patientLists['pat_asthama'];
				$getConsultList['pat_video_link']=$my_patientLists['pat_video_link'];
				$getConsultList['pay_status']=$my_patientLists['pay_status'];
				$getConsultList['ref_name']=$my_patientLists['ref_name'];
				
				array_push($consulat_details, $getConsultList);
			}
		}
		
		*/
		
					
		$success_wallet = array('result' => "success", "consultation_details"=>$consulat_details, 'message' => "Your Consulatations !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
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
