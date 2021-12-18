<?php ob_start();
 error_reporting(0);
 session_start();


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));


require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


if(HEALTH_API_KEY == $data ->api_key){

	$postkey = $data ->postkey;
	$Story_title = $data ->Story_title;	
	$Blog_val = $data ->Blog_val;	
	$User_id = $data ->User_id;
	$User_type = $data ->User_type;	
	$anonymus = $data ->anonymus;	
	$storyImage = $data ->storyImage;
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[] = 'postkey';
	$arrValues[] = $postkey;
	$arrFields[] = 'post_tittle';
	$arrValues[] = $Story_title;
	$arrFields[] = 'post_description';
	$arrValues[] = $Blog_val;
	$arrFields[] = 'Login_User_Id';
	$arrValues[] = $User_id;
	$arrFields[] = 'Login_User_Type';
	$arrValues[] = $User_type;
	$arrFields[] = 'post_type';
	$arrValues[] = "story";
	$arrFields[] = 'anonymous_status';
	$arrValues[] = $anonymus;
	$arrFields[] = 'post_date';
	$arrValues[] = $curDate;
	$arrFields[] = 'post_image';
	$arrValues[] = $storyImage;
	
	$usercraete=$objQuery->mysqlInsert('health_home_posts',$arrFields,$arrValues);
	$id=mysql_insert_id();
	

    $success_appointment = array('result' => "success",'getStoryId' => $id);
    echo json_encode($success_appointment);

}
else
{
	$success_appointment = array('result' => "failure",'result_bookappoint' => "failure",'err_msg' => "API key mismatch");
	echo json_encode($success_appointment);
}
