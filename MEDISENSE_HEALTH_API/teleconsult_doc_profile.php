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


if(HEALTH_API_KEY == $data ->api_key)
	{
		$userDetails = $objQuery->mysqlSelect("*","login_user","login_id='".$data ->userid."'","","","","");
		$getDoctors = $objQuery->mysqlSelect("*","referal","md5(ref_id)='".$data ->docId."'","doc_type_val asc","","","");
		$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"md5(a.doc_id)='".$data ->docId."'","","","","");

		$doc_lang = $objQuery->mysqlSelect('b.name','doctor_langauges as a inner join languages as b on a.language_id=b.id',"md5(a.doc_id)='".$data ->docId."'","","","","");

		$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","md5(a.doc_id) = '".$data ->docId."'","","","","");
		$getCountries= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
		$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
		
		
		$memberid = $data ->userid;
		$familyMembers = $objQuery->mysqlSelect("*", "user_family_member", "user_id='".$memberid."'", "member_id asc", "", "", "");
		
		$allMembersDetails = array();
		foreach($familyMembers as $member) {
			$memberDetails['member_id']= $member['member_id'];
			$memberDetails['member_name']= $member['member_name'];
			$memberDetails['gender']= $member['gender'];
			$memberDetails['age']= $member['age'];
			$memberDetails['height']= $member['height'];
			$memberDetails['weight']= $member['weight'];
			$memberDetails['blood_group']= $member['blood_group'];

			$member_general_health = $objQuery->mysqlSelect('*','user_family_general_health',"member_id='".$member['member_id']."'","","","","");
			$memberDetails['g_health']= $member_general_health[0];
				
			array_push($allMembersDetails, $memberDetails);
		}
			
		
		
		$docSpec=array();
		foreach($doc_specialization as $docSpecList){
			//$docSpec.push($docSpecList['spec_id']);
			array_push($docSpec, $docSpecList['spec_id']);
		}
		$speczn_id=implode(',',$docSpec);
		
		$getFeatureDoctors = $objQuery->mysqlSelect("a.ref_id, a.ref_name, a.ref_exp,a.ref_address, a.doc_photo, a.doc_city, a.doc_state, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a join doc_specialization as b on a.ref_id=b.doc_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_state='".$getDoctors[0]['doc_state']."' and b.spec_id IN (".$speczn_id.")","a.doc_type_val asc","","","");
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
				
			$getDocHosp = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_hospitals']= $getDocHosp;
			
			array_push($doc_details, $getDocList);
			}	
			$getDays = $objQuery->mysqlSelect("*","seven_days ","","","","","");
			$getTimings = $objQuery->mysqlSelect("*","timings ","","","","","");
			$chkDay = $objQuery->mysqlSelect("day_id,time_id","doc_time_set","md5(doc_id)='".$data ->docId."' and time_set='1'","","time_id,day_id","","");
			$postResult = $objQuery->mysqlSelect("*","health_home_posts","md5(Login_User_Id)='".$data->docId."' and Login_User_Type='doc' and post_type='blog'","post_id desc","","","");
			$docVideo = $objQuery->mysqlSelect("*","health_home_posts","md5(Login_User_Id)='".$data->docId."' and Login_User_Type='doc' and post_type='video'","post_id desc","","","");
			
			$latestblog = $objQuery->mysqlSelect("*","health_home_posts","post_type='blog'","post_id desc","","","0,3");
			
			$familyResult = $objQuery->mysqlSelect("*","user_family_member","user_id='".$data ->userid."'","","","","");
			
			$getSpec= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
			
		$response = array('status' => "true","getDoctorDetails" => $getDoctors,"getFeaturedDoc" => $doc_details,"getSpecialization" => $doc_specialization,"getDocHospital" => $getDocHospital,"countryDetails" => $getCountries,"getStates" => $GetState,"getDays" => $getDays,"getTimings" => $getTimings,"chkDay" => $chkDay,"getBlogs" => $postResult,"docVideo" => $docVideo,"latestblog" => $latestblog,"familyMember" => $familyResult, "getSpec" => $getSpec , "userDetails" => $userDetails,"doc_lang"=>$doc_lang,"allMembersDetails"=>$allMembersDetails,"memberDet"=>$familyMembers);	
		echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


