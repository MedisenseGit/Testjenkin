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
require_once("../DigitalOceanSpaces/src/upload_function.php");
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}

if(HEALTH_API_KEY == $data ->api_key && $data ->query == "insert"){

	$login_id 		= $data ->admin_id;
	$member_name 	= $data ->member_name;
	$username 		= $data ->username;
	$gender 		= $data ->gender;
	$relationship 	= $data ->relationship;
	
	$dob 			= date('Y-m-d',strtotime($data ->dob));
	$age = $data ->age;

	$height = $data ->height;
	$weight = $data ->weight;
	$blood_group = $data ->blood_group;	

	$temp_name=$data ->temp_name;
	$file_name=$data ->file_name;
			
	$arrFields_family[] = 'member_name';
	$arrValues_family[] = $member_name;
	$arrFields_family[] = 'member_type';
	$arrValues_family[] = "secondary";
	$arrFields_family[] = 'gender';
	$arrValues_family[] = $gender;
	$arrFields_family[] = 'relationship';
	$arrValues_family[] = $relationship;
	$arrFields_family[] = 'dob';
	$arrValues_family[] = $dob;
	$arrFields_family[] = 'age';
	$arrValues_family[] = $age;
	$arrFields_family[] = 'user_id';
	$arrValues_family[] = $login_id;

	$arrFields_family[] = 'height';
	$arrValues_family[] = $height;
	$arrFields_family[] = 'weight';
	$arrValues_family[] = $weight;
	$arrFields_family[] = 'blood_group';
	$arrValues_family[] = $blood_group;

	$arrFields_family[] = 'member_photo';
	$arrValues_family[] = $file_name;

	$patientNote=mysqlInsert('user_family_member',$arrFields_family,$arrValues_family);		
	$id = $patientNote;

	if($data ->age < 18)
	{
		$arrFields_parent[] = 'mother_name';
		$arrValues_parent[] = $username;

		$arrFields_parent[] = 'login_id';
		$arrValues_parent[] = $login_id;

		$arrFields_parent[] = 'member_id';
		$arrValues_parent[] = $id;

		$parentNote=mysqlInsert('parents_tab',$arrFields_parent,$arrValues_parent);
		$parentid = $parentNote;

		$arrFields_child[] = 'parent_id';
		$arrValues_child[] = $parentid;

		$arrFields_child[] = 'login_id';
		$arrValues_child[] = $login_id;

		$arrFields_child[] = 'member_id';
		$arrValues_child[] = $id;

		$childNote=mysqlInsert('child_tab',$arrFields_child,$arrValues_child);
	}	
	
	$folder_name	=	"memberPics"; 
	$sub_folder		=	$id; //member_id
	$filename		=	$data ->file_name;
	$file_url		=	$data ->temp_name;
	fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
     
	$result_family = mysqlSelect("*","user_family_member","user_id ='".$login_id."'","","","","");
	
	$success_register = array('result' => "success","family_details" => $result_family,"memberid"=>$id);
	echo json_encode($success_register);
		
}
else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "update"){
	$login_id = $data ->admin_id;
	$member_name = $data ->member_name;
	$gender = $data ->gender;
	$relationship = $data ->relationship;
	$dob = date('Y-m-d',strtotime($data ->dob));
	$age = $data ->age;

	$height = $data ->height;
	$weight = $data ->weight;
	$blood_group = $data ->blood_group;

	$temp_name=$data ->temp_name;
	$file_name=$data ->file_name;	
	
	$result_referrring = mysqlSelect("*","user_family_member","member_id ='".$login_id."'","","","","");


	$admin=mysqlSelect("*","user_family_member","user_id ='".$result_referrring[0]['user_id']."'","","","","");

	if($result_referrring==true){	
		$arrFields_family[] = 'member_name';
		$arrValues_family[] = $member_name;
		$arrFields_family[] = 'gender';
		$arrValues_family[] = $gender;
		$arrFields_family[] = 'relationship';
		$arrValues_family[] = $relationship;
		$arrFields_family[] = 'dob';
		$arrValues_family[] = $dob;
		$arrFields_family[] = 'age';
		$arrValues_family[] = $age;

		$arrFields_family[] = 'height';
		$arrValues_family[] = $height;
		$arrFields_family[] = 'weight';
		$arrValues_family[] = $weight;
		$arrFields_family[] = 'blood_group';
		$arrValues_family[] = $blood_group;

		if(!empty($file_name))
		{
		
			$arrFields_family[] = 'member_photo';
			$arrValues_family[] = $file_name;
			
			$folder_name	=	"memberPics"; // change this  name 
			$sub_folder		=	$login_id; // member id 
			$filename		=	$data ->file_name;
			$file_url		=	$data ->temp_name;
			fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		}



		$patientNote=mysqlUpdate('user_family_member',$arrFields_family,$arrValues_family,"member_id='".$result_referrring[0]['member_id']."'");


		if($age < 18)
		{
			$arrFields_parent[] = 'mother_name';
			$arrValues_parent[] = $admin[0]['member_name'];

			$arrFields_parent[] = 'login_id';
			$arrValues_parent[] = $result_referrring[0]['user_id'];

			$arrFields_parent[] = 'member_id';
			$arrValues_parent[] = $result_referrring[0]['member_id'];

			$parentNote=mysqlInsert('parents_tab',$arrFields_parent,$arrValues_parent);
			$parentid = $parentNote;

			$arrFields_child[] = 'parent_id';
			$arrValues_child[] = $parentid;

			$arrFields_child[] = 'login_id';
			$arrValues_child[] = $result_referrring[0]['user_id'];

			$arrFields_child[] = 'member_id';
			$arrValues_child[] = $result_referrring[0]['member_id'];

			$childNote=mysqlInsert('child_tab',$arrFields_child,$arrValues_child);
		}	
     
		
		$arrFields_user[] = 'sub_gender';
		$arrValues_user[] = $gender;
		$arrFields_user[] = 'sub_age';
		$arrValues_user[] = $age;
		
		$loginUser=mysqlUpdate('login_user',$arrFields_user,$arrValues_user,"login_id='".$result_referrring[0]['user_id']."'");
		
		$success_register = array('status'=>"true","data ->file_name;"=>$data ->file_name);
      	echo json_encode($success_register);
	}
	else
	{
		// $response["status"] = "false";
		// echo(json_encode($response));
	}
}
else if(HEALTH_API_KEY == $data ->api_key && $data ->query == "delete" && isset($data ->member_id))
{
	$member_id = $data ->member_id;

	$delMember = mysqlDelete('user_family_member',"member_id='".$member_id."'");
	
	$result = array("result"=>"success");

	echo json_encode($result);
}
else
{
	$response["status"] = "false";
			echo(json_encode($response));
}
