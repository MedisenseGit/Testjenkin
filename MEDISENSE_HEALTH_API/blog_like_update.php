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

if(HEALTH_API_KEY == $data ->api_key && isset($data ->user_id) && isset($data ->cat_id) && $data ->attr == "like"){

	$user_id = $data ->user_id;
	$post_id = $data ->cat_id;
	
		$result_referrring = $objQuery->mysqlSelect("*","health_home_post_like","category_id='".$post_id."' and likes='".$user_id."'","","","","");
	if($result_referrring==false){	
			$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'category_id';
	$arrValues[]= $post_id;
	$arrFields[]= 'likes';
	$arrValues[]= $user_id;
		
	$addLike=$objQuery->mysqlInsert('health_home_post_like',$arrFields,$arrValues);
	
	$likeCounts = $objQuery->mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$post_id."'","","","","");
	        
      $success_register = array('result' => "success","likeCount" => $likeCounts);
      echo json_encode($success_register);
	}
	else if($result_referrring==true)
{
	$success_register = array('result' => "liked");
      echo json_encode($success_register);
}
		
}
else if(HEALTH_API_KEY == $data ->api_key && isset($data ->user_id) && isset($data ->cat_id) && $data ->attr == "comment"){
	
	$topicId = $data ->cat_id;
	$userId = $data ->user_id;
	$userComment = $data ->post_comm;
	
	$arrFields = array();
		$arrValues = array();

		$arrFields[]= 'login_id';
		$arrValues[]= $userId;
		$arrFields[]= 'login_User_Type';
		$arrValues[]= 'user';
		$arrFields[]= 'topic_id';
		$arrValues[]= $topicId;
		$arrFields[]= 'comments';
		$arrValues[]= $userComment;
		$arrFields[]= 'post_date';
		$arrValues[]= $Cur_Date;
		
		$addComment=$objQuery->mysqlInsert('health_home_post_comments',$arrFields,$arrValues);
		
		$postComments = $objQuery->mysqlSelect("*","health_home_post_comments","topic_id='".$topicId."'","comment_id desc","","","");
		$comment_details= array();
			foreach($postComments as $result_blogList) 
			{
				$getBlogList['post_date']=$result_blogList['post_date'];
			$getBlogList['comments']=$result_blogList['comments'];
			
			if($result_blogList['login_User_Type']=="doc"){
						$getUser = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$result_blogList['login_id']."'","","","","");
							$userNme=$getUser[0]['ref_name'];
					//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$usrimg=HOST_MAIN_URL."Doc/".$result_blogList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$usrimg="http://placehold.it/75x75";
			
					}
					}
					else if($result_blogList['login_User_Type']=="user"){
						$getloginuser = $objQuery->mysqlSelect("*","login_user","login_id='".$result_blogList['login_id']."'","","","","");					
						
					//Profile Pic
					$userNme=$getloginuser[0]['sub_name'];
												if(!empty($getloginuser[0]['user_img'])){
												$usrimg=$getloginuser[0]['user_img'];	
												}
												
												else{
													$usrimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";	
												}
					}
					else{
											$usrimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";
										}			
			 $getBlogList['userName']= $userNme;
			 $getBlogList['userImage']= $usrimg;
			 
			array_push($comment_details, $getBlogList);
			
				}
		
		$result = array("result" => "success","commentDet" => $comment_details);
		echo json_encode($result);
	
}
else
{
	$response["status"] = "false";
			echo(json_encode($response));
}
