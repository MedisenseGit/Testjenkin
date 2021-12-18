<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
//echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && isset($data ->userid))	// country , lang, specilaization filtered
{
	//Get Recommended Doctor List
	$memberid = $data ->userid;
	$preferred_country = $data->country;
	$preferred_language = $data->lang;
	//$preferred_speciality = $data->special;
	$preferred_speciality=implode(',',$data ->special);

	if(empty($preferred_speciality)){ //for country and language
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_address as ref_address , a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_qual as doc_qual, a.doc_country as doc_country, a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type, a.physical_consultation_charge as physical_consultation_charge","referal as a inner join doctor_langauges as b on b.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.doc_country LIKE '%".$preferred_country."%' AND b.language_id = '".$preferred_language."' ","doc_type_val asc","","","0,500");
	}else{ //for country , language , speciality
		$result_doctor = $objQuery->mysqlSelect("a.ref_id as ref_id, md5(a.ref_id) as doc_encyid, a.ref_name as ref_name, a.ref_address as ref_address , a.ref_exp as ref_exp, a.doc_photo as doc_photo, a.doc_city as doc_city, a.doc_qual as doc_qual, a.doc_country as doc_country , a.doc_interest as doc_interest, a.geo_latitude as geo_latitude, a.geo_longitude as geo_longitude, a.cons_charge as cons_charge, a.cons_charge_currency_type as cons_charge_currency_type, a.physical_consultation_charge as physical_consultation_charge","referal as a inner join doctor_langauges as b on b.doc_id = a.ref_id inner join doc_specialization as c on c.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND a.doc_country LIKE '%".$preferred_country."%' AND b.language_id = '".$preferred_language."' and c.spec_id IN (".$preferred_speciality.") ","doc_type_val asc","","","0,500");
	}

	if(empty($result_doctor)){
		$result = 1;
	}else{
		$result = 0;
	}

	if(empty($result_doctor)){
		$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","doc_spec!=555 and anonymous_status=0 and doc_country LIKE '%".$preferred_country."%'","doc_type_val asc","","","0,500");
		
		if(empty($result_doctor)){
			$gResult = 1;
		}else{
			$gResult = 0;
		}
	}

	

	$doc_details= array();
	foreach($result_doctor as $result_doctorList) {
		$getDocList['ref_id']=$result_doctorList['ref_id'];
		$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
		$getDocList['ref_name']=$result_doctorList['ref_name'];
		$getDocList['doc_exp']=$result_doctorList['ref_exp'];
		$getDocList['doc_photo']=$result_doctorList['doc_photo'];
		$getDocList['doc_city']=$result_doctorList['doc_city'];
		$getDocList['doc_country']=$result_doctorList['doc_country'];
		$getDocList['doc_qual']=$result_doctorList['doc_qual'];
		$getDocList['ref_address']=$result_doctorList['ref_address'];
		$getDocList['doc_interest']=$result_doctorList['doc_interest'];
		$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
		$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
		$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
		$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
		$getDocList['consult_charge']=$result_doctorList['physical_consultation_charge']; 
	
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
		$getDocList['doc_specializations']= $doc_specialization;
		
		$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList['doc_hospitals']= $getDocHospital;

		$docLanguages = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList['doc_languages']= $docLanguages;
			
		array_push($doc_details, $getDocList);
	}
	$default_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","nova_default_doctor=1","","","","");
	$doc_details1= array();
	foreach($default_doctor as $result_doctorList1) {
		$getDocList1['ref_id']=$result_doctorList1['ref_id'];
		$getDocList1['doc_encyid']=$result_doctorList1['doc_encyid'];
		$getDocList1['ref_name']=$result_doctorList1['ref_name'];
		$getDocList1['doc_exp']=$result_doctorList1['ref_exp'];
		$getDocList1['doc_photo']=$result_doctorList1['doc_photo'];
		$getDocList1['doc_city']=$result_doctorList1['doc_city'];
		$getDocList1['doc_country']=$result_doctorList1['doc_country'];
		$getDocList1['doc_qual']=$result_doctorList1['doc_qual'];
		$getDocList1['ref_address']=$result_doctorList1['ref_address'];
		$getDocList1['doc_interest']=$result_doctorList1['doc_interest'];
		$getDocList1['geo_latitude']=$result_doctorList1['geo_latitude'];
		$getDocList1['geo_longitude']=$result_doctorList1['geo_longitude']; 
		$getDocList1['cons_charge']=$result_doctorList1['cons_charge']; 
		$getDocList1['cons_charge_currency_type']=$result_doctorList1['cons_charge_currency_type']; 
		$getDocList1['consult_charge']=$result_doctorList1['physical_consultation_charge']; 
	
		$doc_specialization1 = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_specializations']= $doc_specialization1;
		
		$getDocHospital1 = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_hospitals']= $getDocHospital1;

		$docLanguages1 = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList1['doc_languages']= $docLanguages1;
			
		array_push($doc_details1, $getDocList1);
	}
	$response = array('status' => "true","doctor_array" => $doc_details,"default_doctor" => $doc_details1, 'result'=>$result, 'gResult'=>$gResult);
	
	echo json_encode($response);	
	
}
else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==1 && isset($data ->userid)) // for search
{
	$memberid = $data ->userid;
	$params = $data ->search_string;
	$postid1 = $data ->postid1;
	$postid2 = $data ->postid2;

	if(empty($postid2)){
		$result_doctor = $objQuery->mysqlSelect("ref_id, md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_country, doc_qual, ref_address, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge ","referal","doc_spec!=555 and anonymous_status =0 and (ref_name LIKE '%".$params."%' or doc_keywords LIKE '%".$params."%' or ref_name LIKE '%".$postid1."%' or ref_address LIKE '%".$postid1."%' or doc_keywords LIKE '%".$postid1."%')","doc_type_val asc","","","0,500");
	}else{
		$result_doctor = $objQuery->mysqlSelect("ref_id, md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_country, doc_qual, ref_address, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","doc_spec!=555 and anonymous_status =0 and ((ref_name LIKE '%".$params."%' or doc_keywords LIKE '%".$params."%' ) or ((ref_name LIKE '%".$postid1."%' or ref_address LIKE '%".$postid1."%' or doc_keywords LIKE '%".$postid1."%') or (ref_name LIKE '%".$postid2."%' or ref_address LIKE '%".$postid2."%' or doc_keywords LIKE '%".$postid2."%')))","doc_type_val asc","","","0,500");
	}

	if(empty($result_doctor)){
		$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","doc_spec!=555 and anonymous_status=0 ","doc_type_val asc","","","0,500");
		$result = 1;
	}else{
		$result = 0;
	}

	$doc_details= array();
	foreach($result_doctor as $result_doctorList) {
		$getDocList['ref_id']=$result_doctorList['ref_id'];
		$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
		$getDocList['ref_name']=$result_doctorList['ref_name'];
		$getDocList['doc_exp']=$result_doctorList['ref_exp'];
		$getDocList['doc_photo']=$result_doctorList['doc_photo'];
		$getDocList['doc_city']=$result_doctorList['doc_city'];
		$getDocList['doc_country']=$result_doctorList['doc_country'];
		$getDocList['doc_qual']=$result_doctorList['doc_qual'];
		$getDocList['ref_address']=$result_doctorList['ref_address'];
		$getDocList['doc_interest']=$result_doctorList['doc_interest'];
		$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
		$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
		$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
		$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
		$getDocList['consult_charge']=$result_doctorList['physical_consultation_charge']; 
	
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
		$getDocList['doc_specializations']= $doc_specialization;
		
		$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList['doc_hospitals']= $getDocHospital;

		$docLanguages = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList['doc_languages']= $docLanguages;
			
		array_push($doc_details, $getDocList);
	}
	
	$default_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","nova_default_doctor=1","","","","");
	$doc_details1= array();
	foreach($default_doctor as $result_doctorList1) {
		$getDocList1['ref_id']=$result_doctorList1['ref_id'];
		$getDocList1['doc_encyid']=$result_doctorList1['doc_encyid'];
		$getDocList1['ref_name']=$result_doctorList1['ref_name'];
		$getDocList1['doc_exp']=$result_doctorList1['ref_exp'];
		$getDocList1['doc_photo']=$result_doctorList1['doc_photo'];
		$getDocList1['doc_city']=$result_doctorList1['doc_city'];
		$getDocList1['doc_country']=$result_doctorList1['doc_country'];
		$getDocList1['doc_qual']=$result_doctorList1['doc_qual'];
		$getDocList1['ref_address']=$result_doctorList1['ref_address'];
		$getDocList1['doc_interest']=$result_doctorList1['doc_interest'];
		$getDocList1['geo_latitude']=$result_doctorList1['geo_latitude'];
		$getDocList1['geo_longitude']=$result_doctorList1['geo_longitude']; 
		$getDocList1['cons_charge']=$result_doctorList1['cons_charge']; 
		$getDocList1['cons_charge_currency_type']=$result_doctorList1['cons_charge_currency_type']; 
		$getDocList1['consult_charge']=$result_doctorList1['physical_consultation_charge']; 
	
		$doc_specialization1 = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_specializations']= $doc_specialization1;
		
		$getDocHospital1 = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_hospitals']= $getDocHospital1;

		$docLanguages1 = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList1['doc_languages']= $docLanguages1;
			
		array_push($doc_details1, $getDocList1);
	}
	$response = array('status' => "true","doctor_array" => $doc_details,"default_doctor" => $doc_details1, 'result'=>$result);
	
	echo json_encode($response);	
}
else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)	// doc detail for view
{
	//Get Recommended Doctor List
	$memberid = $data ->userid;
	$doc_id = $data ->doc_id;

	$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name,ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, doc_research, doc_contribute, physical_consultation_charge","referal","doc_spec!=555 and anonymous_status=0 and md5(ref_id) = '".$doc_id."' ","doc_type_val asc","","","");

	$doc_details= array();
	foreach($result_doctor as $result_doctorList) {
		$getDocList['ref_id']= $result_doctorList['ref_id'];
		$getDocList['doc_encyid']= $result_doctorList['doc_encyid'];
		$getDocList['ref_name']= $result_doctorList['ref_name'];
		$getDocList['doc_exp']= $result_doctorList['ref_exp'];
		$getDocList['doc_photo']= $result_doctorList['doc_photo'];
		$getDocList['doc_city']= $result_doctorList['doc_city'];
		$getDocList['doc_country']= $result_doctorList['doc_country'];
		$getDocList['doc_qual']= $result_doctorList['doc_qual'];
		$getDocList['ref_address']= $result_doctorList['ref_address'];
		$getDocList['doc_interest']= $result_doctorList['doc_interest'];
		$getDocList['geo_latitude']= $result_doctorList['geo_latitude'];
		$getDocList['geo_longitude']= $result_doctorList['geo_longitude']; 
		$getDocList['cons_charge']=$result_doctorList['cons_charge']; 
		$getDocList['cons_charge_currency_type']=$result_doctorList['cons_charge_currency_type']; 
		$getDocList['doc_research']=$result_doctorList['doc_research']; 
		$getDocList['doc_contribute']=$result_doctorList['doc_contribute']; 
		$getDocList['consult_charge']=$result_doctorList['physical_consultation_charge']; 
	
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."' ","","","","");
		$getDocList['doc_specializations']= $doc_specialization;
		
		$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList['doc_hospitals']= $getDocHospital;

		$docLanguages = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList['doc_languages']= $docLanguages;
			
		array_push($doc_details, $getDocList);
	}
	$default_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_address, ref_exp, doc_photo, doc_city, doc_qual, doc_country, doc_interest, geo_latitude, geo_longitude, cons_charge, cons_charge_currency_type, physical_consultation_charge","referal","nova_default_doctor=1","","","","");
	$doc_details1= array();
	foreach($default_doctor as $result_doctorList1) {
		$getDocList1['ref_id']=$result_doctorList1['ref_id'];
		$getDocList1['doc_encyid']=$result_doctorList1['doc_encyid'];
		$getDocList1['ref_name']=$result_doctorList1['ref_name'];
		$getDocList1['doc_exp']=$result_doctorList1['ref_exp'];
		$getDocList1['doc_photo']=$result_doctorList1['doc_photo'];
		$getDocList1['doc_city']=$result_doctorList1['doc_city'];
		$getDocList1['doc_country']=$result_doctorList1['doc_country'];
		$getDocList1['doc_qual']=$result_doctorList1['doc_qual'];
		$getDocList1['ref_address']=$result_doctorList1['ref_address'];
		$getDocList1['doc_interest']=$result_doctorList1['doc_interest'];
		$getDocList1['geo_latitude']=$result_doctorList1['geo_latitude'];
		$getDocList1['geo_longitude']=$result_doctorList1['geo_longitude']; 
		$getDocList1['cons_charge']=$result_doctorList1['cons_charge']; 
		$getDocList1['cons_charge_currency_type']=$result_doctorList1['cons_charge_currency_type']; 
		$getDocList1['consult_charge']=$result_doctorList1['physical_consultation_charge']; 
	
		$doc_specialization1 = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_specializations']= $doc_specialization1;
		
		$getDocHospital1 = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
		$getDocList1['doc_hospitals']= $getDocHospital1;

		$docLanguages1 = $objQuery->mysqlSelect("a.id as id, a.doc_id as doc_id, a.language_id as language_id, b.name as language_name","doctor_langauges as a inner join languages as b on b.id = a.language_id","a.doc_id='".$result_doctorList['ref_id']."'","a.id ASC","","","");
		$getDocList1['doc_languages']= $docLanguages1;
			
		array_push($doc_details1, $getDocList1);
	}
	$response = array('status' => "true","default_doctor" => $doc_details1,"doctor_details_array" => $doc_details);
	
	echo json_encode($response);	
	
}
else 
{	
	$response["status"] = "false";
	echo(json_encode($response));
}


?>


