<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);
$data = json_decode(file_get_contents('php://input'), true);

// Doctors Lists
/*if(!empty($user_id) && !empty($finalHash))
{	

	if($finalHash == $hashKey)
	{*/
		$pageVal = $data['page_val'];
		$country_id = $data['country_id'];
		
		echo $pageVal;
		/*if($pageVal==1)
		{
			$this1 = 0;
			$page_limit = 15;
		}
		else if($pageVal>1)
		{
			$limit = 15*$pageVal;
			$page_limit = 15;
			$this1 = $limit-15;
		}

		$getCountry=mysqlSelect('*','countries',"country_id='".$country_id."'","","","","");
		if(!empty($getCountry))
		{
			$country_id = $getCountry[0]['country_id'];
			//$country_id = '179'; 			// for demo
		}
		else 
		{
			$country_id = '179'; 					// Default Local doctors 
		}
		
		$result_local_doctor = mysqlSelect("DISTINCT(ref_id) ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_country_id, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_country_id = '".$country_id."' AND anonymous_status=0 and doc_type='featured'","ref_id desc","","","$this1, $page_limit");

			$localDoc_details= array();
			foreach($result_local_doctor as $result_local_doctorList)
			{
				$getLocalDocList['ref_id']		=	$result_local_doctorList['ref_id'];
				$getLocalDocList['doc_encyid']  =	$result_local_doctorList['doc_encyid'];
				$getLocalDocList['ref_name']	=	$result_local_doctorList['ref_name'];
				$getLocalDocList['doc_exp']		=	$result_local_doctorList['ref_exp'];
				$getLocalDocList['doc_photo']	=	$result_local_doctorList['doc_photo'];
				$getLocalDocList['doc_city']	=	$result_local_doctorList['doc_city'];
				$getLocalDocList['doc_qual']	=	$result_local_doctorList['doc_qual'];
				$getLocalDocList['doc_interest']=	$result_local_doctorList['doc_interest'];
				$getLocalDocList['geo_latitude']=	$result_local_doctorList['geo_latitude'];
				$getLocalDocList['geo_longitude']=	$result_local_doctorList['geo_longitude']; 
				$getLocalDocList['doc_country_id']=$result_local_doctorList['doc_country_id'];
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".
				$result_local_doctorList['ref_id']."'","","","","");
				
				$getLocalDocList['doc_specializations']	= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".
				$result_local_doctorList['ref_id']."'","","","","");
				
				$getLocalDocList['doc_hospitals']	= $getDocHospital;
	 
				$getDocCountry=mysqlSelect("b.country_id as country_id, b.country_name as country_name","referal as a inner join countries as b on b.country_id = a.doc_country_id","b.country_id='".$result_local_doctorList['doc_country_id']."'","","","","");
				
				$getLocalDocList['doc_country']	= $getDocCountry[0]['country_name'];
				
				$docRatings = mysqlSelect('id, doc_id, login_id, ratings, notes, created_date','doctor_feedback',"doc_id='".$result_local_doctorList['ref_id']."'","","","","");
				
				///echo $result_local_doctorList['ref_id'];
				
				
				$getLocalDocList['doc_ratings']	=	$docRatings;
				
				array_push($localDoc_details, $getLocalDocList);
			
			}
			
			if(COUNT($result_local_doctor)==$page_limit)
			{
				$page_val=$pageVal+1;
			}
			else
			{
				$page_val=0;
			}
			*/
			$success = array('status' => "true","pagination_val" => $page_val);
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
