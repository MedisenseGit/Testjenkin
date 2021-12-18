<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type'];
	
	if($login_type==1){
	$get_docInfo = $objQuery->mysqlSelect("a.ref_id as partner_id,a.doc_photo as doc_photo,a.doc_spec as specialisation,a.ref_address as Address,a.doc_city as location,a.doc_state as state,a.doc_country as country,a.ref_name as partner_name,a.doc_qual as doc_qual,a.ref_exp as ref_exp,a.contact_num as cont_num1,a.ref_mail as Email_id,a.ref_web as website,a.doc_interest as doc_interest,a.doc_contribute as doc_contribute,a.doc_research as doc_research,a.doc_pub as doc_pub,a.in_op_cost as in_op_cost,a.on_op_cost as on_op_cost,a.cons_charge as cons_charge,a.tele_op as tele_op,a.tele_op_contact as tele_op_contact,a.video_op as video_op,a.video_op_contact as video_op_contact,a.tele_video_op_timing as tele_video_op_timing,a.secretary_phone as secretary_phone,a.secretary_email as secretary_email, b.spec_id as spec_id, b.spec_name as spec_name","referal as a inner join specialization as b on b.spec_id = a.doc_spec","ref_id='".$admin_id."'","","","","");

	$get_schedule = $objQuery->mysqlSelect("*","doc_time_set","doc_id='".$admin_id."' and time_set=1","","","","");
	
	$get_docInfo1 = $objQuery->mysqlSelect("ref_id as partner_id,doc_photo as doc_photo,doc_spec as specialisation,ref_address as Address,doc_city as location,doc_state as state,doc_country as country,ref_name as partner_name,doc_qual as doc_qual,ref_exp as ref_exp,contact_num as cont_num1,ref_mail as Email_id,ref_web as website,doc_interest as doc_interest,doc_contribute as doc_contribute,doc_research as doc_research,doc_pub as doc_pub,in_op_cost as in_op_cost,on_op_cost as on_op_cost,cons_charge as cons_charge,tele_op as tele_op,tele_op_contact as tele_op_contact,video_op as video_op,video_op_contact as video_op_contact,tele_video_op_timing as tele_video_op_timing,secretary_phone as secretary_phone,secretary_email as secretary_email","referal","ref_id='".$admin_id."'","","","","");
	$get_docSpec = $objQuery->mysqlSelect("a.doc_id as doc_id, a.spec_id as spec_id, b.spec_name as spec_name","doc_specialization as a inner join specialization as b on b.spec_id = a.spec_id","doc_id='".$admin_id."' and doc_type=1","","","","");			
	$get_docHospital = $objQuery->mysqlSelect("a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_addrs as hosp_addrs, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id","a.doc_id='".$admin_id."'","","","","");			

	$result = array("result" => $get_docInfo, "schedules" => $get_schedule, "docInfoResult" => $get_docInfo1, "docSpecResult" => $get_docSpec, "docHospResult" => $get_docHospital);
	echo json_encode($result);
	}
	else if($login_type==3){
	$get_docInfo = $objQuery->mysqlSelect("*","hosp_marketing_person","person_id='".$admin_id."'","","","","");

	$result = array("result" => $get_docInfo);
	echo json_encode($result);
	}
 }
?>
