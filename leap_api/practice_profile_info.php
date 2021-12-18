<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

 if(API_KEY == $_POST['API_KEY']) {	
	$admin_id = $_POST['userid'];
	$patient_id = $_POST['login_type'];
	
	/* $get_docInfo = $objQuery->mysqlSelect("a.partner_id as partner_id,a.doc_photo as doc_photo,a.contact_person as contact_person,a.specialisation as specialisation,a.Address as Address,a.location as location,a.state as state,a.country as country,a.partner_name as partner_name,a.doc_qual as doc_qual,a.ref_exp as ref_exp,a.cont_num1 as cont_num1,a.Email_id as Email_id,a.website as website,a.doc_interest as doc_interest,a.doc_contribute as doc_contribute,a.doc_research as doc_research,a.doc_pub as doc_pub,a.in_op_cost as in_op_cost,a.on_op_cost as on_op_cost,a.cons_charge as cons_charge,a.tele_op as tele_op,a.tele_op_contact as tele_op_contact,a.video_op as video_op,a.video_op_contact as video_op_contact,a.tele_video_op_timing as tele_video_op_timing,a.secretary_phone as secretary_phone,a.secretary_email as secretary_email, b.spec_id as spec_id, b.spec_name as spec_name","our_partners as a inner join specialization as b on b.spec_id = a.specialisation","partner_id='".$admin_id."'","","","","");
	$get_schedule = $objQuery->mysqlSelect("*","ref_doc_time_set","doc_id='".$admin_id."' and time_set=1","","","","");
	$result = array("result" => $get_docInfo, "schedules" => $get_schedule);
	echo json_encode($result); */

	$docResult["result"] = array();
	$docResult["schedules"]=array();

	$docinfo=array();
	$get_docInfo = $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","","");
	$get_spec = $objQuery->mysqlSelect("spec_id, spec_name","specialization","spec_id='".$get_docInfo[0]['specialisation']."'","","","","");
	$docinfo['partner_id']=$get_docInfo[0]['partner_id'];
	$docinfo['contact_person']=$get_docInfo[0]['contact_person'];
	$docinfo['cont_num1']=$get_docInfo[0]['cont_num1'];
	$docinfo['location']=$get_docInfo[0]['location'];
	$docinfo['partner_name']=$get_docInfo[0]['partner_name'];
	$docinfo['Address']=$get_docInfo[0]['Address'];
	$docinfo['doc_qual']=$get_docInfo[0]['doc_qual'];
	$docinfo['ref_exp']=$get_docInfo[0]['ref_exp'];
	$docinfo['Email_id']=$get_docInfo[0]['Email_id'];
	$docinfo['website']=$get_docInfo[0]['website'];
	$docinfo['doc_interest']=$get_docInfo[0]['doc_interest'];
	$docinfo['doc_contribute']=$get_docInfo[0]['doc_contribute'];
	$docinfo['doc_research']=$get_docInfo[0]['doc_research'];
	$docinfo['doc_pub']=$get_docInfo[0]['doc_pub'];
	$docinfo['state']=$get_docInfo[0]['state'];
	$docinfo['doc_photo']=$get_docInfo[0]['doc_photo'];
	if(empty($get_spec[0]['spec_id'])){
	$docinfo['spec_id']="0";
	$docinfo['spec_name']="";
	}else{
	$docinfo['spec_id']=$get_spec[0]['spec_id'];
	$docinfo['spec_name']=$get_spec[0]['spec_name'];
	}
	array_push($docResult["result"],$docinfo);

	/* $docschedule=array();
	$get_schedule = $objQuery->mysqlSelect("*","ref_doc_time_set","doc_id='".$admin_id."' and time_set=1","","","","");
	$docschedule['doc_id'] = $get_schedule[0]['doc_id'];
	$docschedule['time_id'] = $get_schedule[0]['time_id'];
	$docschedule['day_id'] = $get_schedule[0]['day_id'];

	array_push($docResult["schedules"],$docschedule);  */

	$get_schedule = $objQuery->mysqlSelect("*","ref_doc_time_set","doc_id='".$admin_id."' and time_set=1","","","","");
	foreach($get_schedule as $postSchedule){
		$docschedule=array();
		$docschedule['doc_id'] = $postSchedule['doc_id'];
		$docschedule['time_id'] = $postSchedule['time_id'];
		$docschedule['day_id'] = $postSchedule['day_id'];
		array_push($docResult["schedules"],$docschedule);
	}
	
	echo(json_encode($docResult));
 }
?>
