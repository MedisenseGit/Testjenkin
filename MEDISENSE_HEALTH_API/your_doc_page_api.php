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


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type>=0)
	
	{
		$get_all_specialization = $objQuery->mysqlSelect('*','specialization',"");
		$get_all_doc_state = $objQuery->mysqlSelect('DISTINCT(doc_state) as doc_state','referal',"","","","","");
			
		if($data ->filter_type == 0) {	 //List all doctors
			
		$getFeatureDoctors = $objQuery->mysqlSelect("ref_id, ref_name, ref_exp,ref_address, doc_photo, doc_city, doc_state, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status!=1","doc_type_val asc","","","");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}	
			$response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
			
		}
		else if($data ->filter_type == 1) {	 //List all doctors based on spec
			
		$getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_name,a.ref_address, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a left join doc_specialization as b on a.ref_id=b.doc_id","a.doc_spec!=555 and a.anonymous_status!=1 and b.spec_id='".$data ->spec_id."'","a.doc_type_val asc","","","");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}	
			$response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
			
		}
		else if($data ->filter_type == 2) {	 //List all doctors based on state
			
		$getFeatureDoctors = $objQuery->mysqlSelect("ref_id, ref_name,ref_address, ref_exp, doc_photo, doc_city, doc_state, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status!=1 and doc_state like '%".$data ->state_name."%'","doc_type_val asc","","","");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}	
			$response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
			
		}
		else if($data ->filter_type == 3) {	 //List all doctors based on spec and state
			
		$getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_name,a.ref_address, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a left join doc_specialization as b on a.ref_id=b.doc_id","a.doc_spec!=555 and a.anonymous_status!=1 and b.spec_id='".$data ->spec_id."' and a.doc_state='".$data ->state_name."'","a.doc_type_val asc","","","");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}	
			$response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
			
		}
		else if($data ->filter_type == 4) {	  //list based on search in medisensepatientcare
		 $getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_address, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","doc_specialization as b right join referal as a on a.ref_id=b.doc_id left join specialization as c on b.spec_id=c.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and ((".$data ->compare_query."))","a.doc_type_val asc","","","0,30");//and ('".$params."')// or a.ref_address IN (".$doctor_det.") or a.doc_state IN (".$doctor_det.")
		$doc_details= array();
		foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			//$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			//$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
		    $getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}
            $response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		
			echo json_encode($response);			
		}
		else if($data ->filter_type == 5) {	  //list based on search filter in medisense 
		 $getdocDet = $objQuery->mysqlSelect("a.ref_id, a.ref_address, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude, b.spec_id","doc_specialization as b right join referal as a on a.ref_id=b.doc_id left join specialization as c on b.spec_id=c.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and md5(a.ref_id) = '".$data->ref_id."'","a.doc_type_val asc","","","");
		$doc_details= array();
		$getDocList['ref_id']=$getdocDet[0]['ref_id'];
			//$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$getdocDet[0]['ref_name'];
			//$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$getdocDet[0]['doc_photo'];
			$getDocList['doc_city']=$getdocDet[0]['doc_city'];
		    $getDocList['doc_state']=$getdocDet[0]['doc_state'];
			$getDocList['ref_address']=$getdocDet[0]['ref_address'];
			$getDocList['doc_qual']=$getdocDet[0]['doc_qual'];
			$getDocList['doc_interest']=$getdocDet[0]['doc_interest'];	
			$getDocList['geo_latitude']=$getdocDet[0]['geo_latitude'];
			$getDocList['geo_longitude']=$getdocDet[0]['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$getdocDet[0]['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$getdocDet[0]['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			
		 $getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_address, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","doc_specialization as b right join referal as a on a.ref_id=b.doc_id left join specialization as c on b.spec_id=c.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_state='".$getdocDet[0]['doc_state']."' and b.spec_id= '".$getdocDet[0]['spec_id']."' and md5(a.ref_id) != '".$data->ref_id."'","a.doc_type_val asc","","","");
		
		
		foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			//$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			//$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
		    $getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}
            $response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		
			echo json_encode($response);			
		}
		else if($data->filter_type==6)	//filter based on hospid on click map of home page
		{
		
		$getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_name,a.ref_address, a.ref_exp, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id","a.doc_spec!=555 and a.anonymous_status!=1 and md5(b.hosp_id)='".$data->filter_search."'","a.doc_type_val asc","","","");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_exp']=$result_doctorList['ref_exp'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			$getDocList['ref_address']=$result_doctorList['ref_address'];
			$getDocList['doc_qual']=$result_doctorList['doc_qual'];
			$getDocList['doc_interest']=$result_doctorList['doc_interest'];	
			$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
			$getDocList['geo_longitude']=$result_doctorList['geo_longitude'];
			
			$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHospital;
			
			array_push($doc_details, $getDocList);
			}	
			$response = array('status' => "true","getFeatureDoctors" => $doc_details,"getAllSpecialization" => $get_all_specialization,"getAllStates" => $get_all_doc_state);
		
			echo json_encode($response);
		}
		
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


