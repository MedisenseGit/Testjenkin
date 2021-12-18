<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$Cur_Time=date('H:iA');
//echo $Cur_Time."<br>";
$headers = apache_request_headers();
if ($headers)
{
    $doctor_id = 3727;//$headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}



$data = json_decode(file_get_contents('php://input'), true);

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

/*if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) 
	{*/
		$filter_type =1;// $data['appt_filter_type']; //appt_filter_type is 1 for Upcoming, 2 - Missed, 3 - Completed ,4- all appointments
		$ToDay=date('Y-m-d');
		
		$pageVal =1;// $data['paginationPointer'];
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
		$member_id = $data['member_id'];
		
		$doc_hospital = mysqlSelect('*','doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id',"a.doc_id='".$doctor_id."'","a.doc_hosp_id ASC","","","");
		$hospital_id = $doc_hospital[0]['hosp_id'];
	
		if($filter_type == 1)
		{
			$appointmentResult = mysqlSelect("c.id as appoint_id, c.appoint_trans_id as appoint_trans_id, c.patient_id as patient_id, c.patient_name as patient_name, c.pref_doc as doc_id, c.hosp_id as hosp_id, c.pay_status as status, a.app_date as app_date, a.app_time as app_time, c.Time_stamp as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link, c.Time_stamp as Time_stamp, c.appointment_type as appointment_type, b.member_id as member_id","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id","a.doc_id='".$doctor_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."' and a.app_date>='".$ToDay."' and c.pay_status!='Cancelled'","a.app_date DESC","","","$this1, $page_limit");				
		
			$appoint_details= array();
			foreach($appointmentResult as $appointmentResultList)
			{
				$getAppointList['appoint_id']=$appointmentResultList['appoint_id'];
				$getAppointList['appoint_trans_id']=$appointmentResultList['appoint_trans_id'];
				$getAppointList['patient_id']=$appointmentResultList['patient_id'];
				$getAppointList['patient_name']=$appointmentResultList['patient_name'];
				$getAppointList['doc_id']=$appointmentResultList['doc_id'];
				$getAppointList['hosp_id']=$appointmentResultList['hosp_id'];
				$getAppointList['status']=$appointmentResultList['status'];
				$getAppointList['app_date']=$appointmentResultList['app_date'];
				
				$getAppointList['created_date']=$appointmentResultList['created_date'];
				$getAppointList['patient_email']=$appointmentResultList['patient_email'];
				$getAppointList['patient_mob']=$appointmentResultList['patient_mob'];
				$getAppointList['doc_video_link']=$appointmentResultList['doc_video_link'];
				$getAppointList['Time_stamp']=$appointmentResultList['Time_stamp'];
				$getAppointList['appointment_type']=$appointmentResultList['appointment_type'];
				$getAppointList['member_id']=$appointmentResultList['member_id'];
				
				$login_user = mysqlSelect('b.login_id as login_id, b.civil_id as civil_id','user_family_member as a inner join login_user as b on b.login_id = a.user_id',"a.member_id='".$appointmentResultList['member_id']."'","","","","");
				$getAppointList['civil_id']=$login_user[0]['civil_id'];
				
				array_push($appoint_details, $getAppointList);
				$date2=explode(" To",$appointmentResultList['app_time']);
				
				
				
				$time1 = date('H:i:s',strtotime("$Cur_Time"));
				$time2 = date('H:i:s',strtotime("$date2[0]"));
				$time3 = date('H:i:s',strtotime("$date2[1]"));
				
				if($time1 <= $time2 && $time1 <= $time3)
				{
					
					$getAppointList['app_time'] = $appointmentResultList['app_time'];
				}
				
				
				 
			}
		
		} 
		else if($filter_type == 2)
		{
			$appointmentResult = mysqlSelect("c.id as appoint_id, c.appoint_trans_id as appoint_trans_id, c.patient_id as patient_id, c.patient_name as patient_name, c.pref_doc as doc_id, c.hosp_id as hosp_id, c.pay_status as status, a.app_date as app_date, a.app_time as app_time, c.Time_stamp as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link, c.Time_stamp as Time_stamp, c.appointment_type as appointment_type, b.member_id as member_id","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id","a.doc_id='".$doctor_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."' and (c.pay_status='Pending' OR c.pay_status='Missed')","a.app_date DESC","","","$this1, $page_limit");
		
			$appoint_details= array();
			foreach($appointmentResult as $appointmentResultList){
				$getAppointList['appoint_id']=$appointmentResultList['appoint_id'];
				$getAppointList['appoint_trans_id']=$appointmentResultList['appoint_trans_id'];
				$getAppointList['patient_id']=$appointmentResultList['patient_id'];
				$getAppointList['patient_name']=$appointmentResultList['patient_name'];
				$getAppointList['doc_id']=$appointmentResultList['doc_id'];
				$getAppointList['hosp_id']=$appointmentResultList['hosp_id'];
				$getAppointList['status']=$appointmentResultList['status'];
				$getAppointList['app_date']=$appointmentResultList['app_date'];
				$getAppointList['app_time']=$appointmentResultList['app_time'];
				$getAppointList['created_date']=$appointmentResultList['created_date'];
				$getAppointList['patient_email']=$appointmentResultList['patient_email'];
				$getAppointList['patient_mob']=$appointmentResultList['patient_mob'];
				$getAppointList['doc_video_link']=$appointmentResultList['doc_video_link'];
				$getAppointList['Time_stamp']=$appointmentResultList['Time_stamp'];
				$getAppointList['appointment_type']=$appointmentResultList['appointment_type'];
				$getAppointList['member_id']=$appointmentResultList['member_id'];
				
				$login_user = mysqlSelect('b.login_id as login_id, b.civil_id as civil_id','user_family_member as a inner join login_user as b on b.login_id = a.user_id',"a.member_id='".$appointmentResultList['member_id']."'","","","","");
				$getAppointList['civil_id']=$login_user[0]['civil_id'];
				
				array_push($appoint_details, $getAppointList);
			}
		
		}
		else if($filter_type == 3)
		{
			$appointmentResult = mysqlSelect("c.id as appoint_id, c.appoint_trans_id as appoint_trans_id, c.patient_id as patient_id, c.patient_name as patient_name, c.pref_doc as doc_id, c.hosp_id as hosp_id, c.pay_status as status, a.app_date as app_date, a.app_time as app_time, c.Time_stamp as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link, c.Time_stamp as Time_stamp, c.appointment_type as appointment_type, b.member_id as member_id","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id","a.doc_id='".$doctor_id."' and a.doc_type='1' and a.hosp_id='".$hospital_id."' and c.pay_status='VC Confirmed'","a.app_date DESC","","","$this1, $page_limit");
		
			$appoint_details= array();
			foreach($appointmentResult as $appointmentResultList){
				$getAppointList['appoint_id']=$appointmentResultList['appoint_id'];
				$getAppointList['appoint_trans_id']=$appointmentResultList['appoint_trans_id'];
				$getAppointList['patient_id']=$appointmentResultList['patient_id'];
				$getAppointList['patient_name']=$appointmentResultList['patient_name'];
				$getAppointList['doc_id']=$appointmentResultList['doc_id'];
				$getAppointList['hosp_id']=$appointmentResultList['hosp_id'];
				$getAppointList['status']=$appointmentResultList['status'];
				$getAppointList['app_date']=$appointmentResultList['app_date'];
				$getAppointList['app_time']=$appointmentResultList['app_time'];
				$getAppointList['created_date']=$appointmentResultList['created_date'];
				$getAppointList['patient_email']=$appointmentResultList['patient_email'];
				$getAppointList['patient_mob']=$appointmentResultList['patient_mob'];
				$getAppointList['doc_video_link']=$appointmentResultList['doc_video_link'];
				$getAppointList['Time_stamp']=$appointmentResultList['Time_stamp'];
				$getAppointList['appointment_type']=$appointmentResultList['appointment_type'];
				
				$getAppointList['member_id']=$appointmentResultList['member_id'];
				
				$login_user = mysqlSelect('b.login_id as login_id, b.civil_id as civil_id','user_family_member as a inner join login_user as b on b.login_id = a.user_id',"a.member_id='".$appointmentResultList['member_id']."'","","","","");
				$getAppointList['civil_id']=$login_user[0]['civil_id'];
				
				array_push($appoint_details, $getAppointList);
			}
		}
		else if($filter_type == 4)
		{
			
			
			$appointmentResult = mysqlSelect("c.id as appoint_id, c.appoint_trans_id as appoint_trans_id, c.patient_id as patient_id, c.patient_name as patient_name, c.pref_doc as doc_id, c.hosp_id as hosp_id, c.pay_status as status, a.app_date as app_date, a.app_time as app_time, c.Time_stamp as created_date, b.patient_email as patient_email, b.patient_mob as patient_mob, b.doc_video_link as doc_video_link, c.Time_stamp as Time_stamp, c.appointment_type as appointment_type, b.member_id as member_id","appointment_token_system as a inner join doc_my_patient as b on b.patient_id = a.patient_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id"," a.doc_id='".$doctor_id."' and b.member_id='".$member_id."' and a.doc_type='1' and  c.pay_status='VC Confirmed'","a.app_date DESC","","","$this1, $page_limit");
		
			$appoint_details= array();
			foreach($appointmentResult as $appointmentResultList)
			{
				$getAppointList['appoint_id']      =	$appointmentResultList['appoint_id'];
				$getAppointList['appoint_trans_id']=	$appointmentResultList['appoint_trans_id'];
				$getAppointList['patient_id']	   =	$appointmentResultList['patient_id'];
				$getAppointList['patient_name']	   =	$appointmentResultList['patient_name'];
				$getAppointList['doc_id']		   =	$appointmentResultList['doc_id'];
				$getAppointList['hosp_id']		   =	$appointmentResultList['hosp_id'];
				$getAppointList['status']		   =	$appointmentResultList['status'];
				$getAppointList['app_date']		   =	$appointmentResultList['app_date'];
				$getAppointList['app_time']		   =	$appointmentResultList['app_time'];
				$getAppointList['created_date']	   =	$appointmentResultList['created_date'];
				$getAppointList['patient_email']   =	$appointmentResultList['patient_email'];
				$getAppointList['patient_mob']	   =	$appointmentResultList['patient_mob'];
				$getAppointList['doc_video_link']  =	$appointmentResultList['doc_video_link'];
				$getAppointList['Time_stamp']	   =	$appointmentResultList['Time_stamp'];
				$getAppointList['appointment_type']=	$appointmentResultList['appointment_type'];
				$getAppointList['member_id']		=	$appointmentResultList['member_id'];
				//$appointmentResultList['member_id']
				
				$login_user = mysqlSelect('b.login_id as login_id, b.civil_id as civil_id','user_family_member as a inner join login_user as b on b.login_id = a.user_id',"a.member_id='".$appointmentResultList['member_id']."'","","","","");
				$getAppointList['civil_id']		=	$login_user[0]['civil_id'];
				
				array_push($appoint_details, $getAppointList);
			}
			
		}


		if(COUNT($appoint_details)==$page_limit)
		{
			$page_val=$pageVal+1;
		}
		
		else
		{
			$page_val=0;
		}		
	
		$success = array('status' => "true", "appointment_details" => $appoint_details, "pagination_val" => $page_val, 'err_msg' => '');
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
}*/

?>