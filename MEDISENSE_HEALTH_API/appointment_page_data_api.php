<?php 
ob_start();
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
//$objQuery = new CLSQueryMaker();

if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && isset($data ->userid))	
{	

    $getDoctors = mysqlSelect("*","referal","md5(ref_id)='".$data ->docId."'","doc_type_val asc","","","");

    $doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"md5(a.doc_id)='".$data ->docId."'","","","","");

    $doc_lang = mysqlSelect('b.name','doctor_langauges as a inner join languages as b on a.language_id=b.id',"md5(a.doc_id)='".$data ->docId."'","","","","");

    $getLanguage = mysqlSelect("*","languages","","id asc","","","");
	$getCountries = mysqlSelect("*","countries","visibility = 1","country_name asc","","","");
	$spec_array= mysqlSelect("*","specialization","","spec_name asc","","","");
	$symp_array= mysqlSelect("*","health_app_symptoms","","name asc","","","");

    $memberid = $data ->userid;
	$familyMembers = mysqlSelect("*", "user_family_member", "user_id='".$memberid."'", "member_id asc", "", "", "");
	
    $allMembersDetails = array();
    foreach($familyMembers as $member) {
        $memberDetails['member_id']= $member['member_id'];
        $memberDetails['member_name']= $member['member_name'];
        $memberDetails['gender']= $member['gender'];
        $memberDetails['age']= $member['age'];
        $memberDetails['height']= $member['height'];
        $memberDetails['weight']= $member['weight'];
        $memberDetails['blood_group']= $member['blood_group'];

        $member_general_health = mysqlSelect('*','user_family_general_health',"member_id='".$member['member_id']."'","","","","");
        $memberDetails['g_health']= $member_general_health[0];
            
        array_push($allMembersDetails, $memberDetails);
    }

	
	$response = array('status' => "true","getCountries" => $getCountries,"spec_array" => $spec_array,"symp_array" => $symp_array,"memberDet"=>$familyMembers, "getLanguage"=>$getLanguage, "allMembersDetails"=>$allMembersDetails,"getDoctorDetails" => $getDoctors,"getSpecialization" => $doc_specialization,"doc_lang"=>$doc_lang);
	echo json_encode($response);
}
else
{	
	$response["status"] = "false";
    $response["data"] = "api problem";
	echo(json_encode($response));
}


?>


