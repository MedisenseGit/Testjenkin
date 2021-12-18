<?php
ob_start();
session_start();
error_reporting(0);

require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$curdate=date('Y-m-d');


$headers = apache_request_headers();
if ($headers){
    $user_id = $headers['user-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $user_id, $device_id);

//Blogs Lists
if(!empty($user_id) && !empty($finalHash)) {	

	if($finalHash == $hashKey) {
		$blogs = array();
		//$response["blog_details"] = array();
		$blog_details= array();
		
		$getPostResult = mysqlSelect("post_id as post_id,Login_User_Id as Login_User_Id, Login_User_Type as Login_User_Type, post_tittle as title, post_description as description, post_image as post_image, post_date as created_date, postkey as transaction_id, num_views as num_views","health_home_posts","post_type='blog'","post_id desc","","","0,30");
	
		foreach($getPostResult as $postResultList) {
		$stuff= array();
			
		$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$postResultList['Login_User_Id']."'","","","","");
					
		
		if($postResultList['Login_User_Type']=="doc"){
			$getUser = mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['Login_User_Id']."'","","","","");
			$username=$getUser[0]['ref_name'];
			$userprof='';
			//Profile Pic
			if(!empty($getUser[0]['doc_photo'])){ 
				$userimg="https://medisensemd.com/Doc/".$postResult[0]['Login_User_Id']."/".$getUser[0]['doc_photo']; 
			}else{
				$userimg="http://placehold.it/75x75";
			}
		}
		else if($postResultList['Login_User_Type']=="user"){
			$getloginuser = mysqlSelect("*","login_user","login_id='".$postResultList['Login_User_Id']."'","","","","");					
			$username=$getUser[0]['ref_name'];
			$userprof=$getloginuser[0]['sub_proff'];
			//Profile Pic
			$username=$getloginuser[0]['sub_name'];
				if(!empty($getloginuser[0]['user_img'])){
					$userimg=$getloginuser[0]['user_img'];	
				}
				else{
					$userimg="https://medisensehealth.com/new_assets/images/no_user_icon.png";	
				}
		}
		else{
				$userimg="https://medisensehealth.com/new_assets/images/no_user_icon.png";
		}
 

			$stuff["post_id"] = $postResultList['post_id'];		
			$stuff["title"] = $postResultList['title'];
			$stuff["description"] = $postResultList['description'];
			$stuff["contactInfo"] = '';
			$stuff["attachments"] = '';
			$stuff["post_image"] = $postResultList['post_image'];
			$stuff["created_date"] = $postResultList['created_date'];
			$stuff["listing_type"] = '';
			$stuff["company_id"]='0';
			$stuff["transaction_id"]=$postResultList['transaction_id'];
			$stuff["num_views"]=$postResultList['num_views'];
			$stuff["video_url"]='';
			$stuff["from_to_date"] = '';
					 
			$stuff["username"] = $username;
			$stuff["userprof"] = $userprof;
			$stuff["userimg"] = $userimg;
			array_push($blog_details, $stuff);

		}
					
		$success_wallet = array('result' => "success", "blog_details"=>$blog_details, 'message' => "Blogs !!!", 'err_msg' => '');
		echo json_encode($success_wallet);
		
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}
?>
