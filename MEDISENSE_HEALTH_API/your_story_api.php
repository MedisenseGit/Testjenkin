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
		$StoryResult = $objQuery->mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='story'","post_id desc","","","");
				$story_details= array();
			foreach($StoryResult as $result_storyList) 
			{
				$getStoryList['post_id']=$result_storyList['post_id'];
			$getStoryList['post_tittle']=$result_storyList['post_tittle'];
			$getStoryList['postkey']=$result_storyList['postkey'];
			$getStoryList['Login_User_Type']=$result_storyList['Login_User_Type'];
			$getStoryList['Login_User_Id']=$result_storyList['Login_User_Id'];
			$getStoryList['post_image']=$result_storyList['post_image'];
			$getStoryList['post_date']=$result_storyList['post_date'];
			$getStoryList['post_description']=$result_storyList['post_description'];
			$getStoryList['num_views']=$result_storyList['num_views'];

			$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$result_storyList['post_id']."'","","","","");
	        $getStoryList['comments_count']= $CommentsCounts;
			
			$likeCounts = $objQuery->mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$result_storyList['post_id']."'","","","","");
	         $getStoryList['likes_count']= $likeCounts;		

		    $postComments = $objQuery->mysqlSelect("*","health_home_post_comments","topic_id='".$result_storyList['post_id']."'","comment_id desc","","","");
			$comment_details= array();
			foreach($postComments as $result_commentList) 
			{
				$getCommentList['post_date']=$result_commentList['post_date'];
			$getCommentList['comments']=$result_commentList['comments'];
			
			if($result_commentList['login_User_Type']=="doc"){
						$getUser = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$result_commentList['login_id']."'","","","","");
							$userNme=$getUser[0]['ref_name'];
					//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$usrimg=HOST_MAIN_URL."Doc/".$result_commentList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$usrimg="http://placehold.it/75x75";
			
					}
					}
					else if($result_commentList['login_User_Type']=="user"){
						$getloginuser = $objQuery->mysqlSelect("*","health_author_login","login_id='".$result_commentList['login_id']."'","","","","");					
						
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
					 $getCommentList['userName']= $userNme;
					 $getCommentList['userImage']= $usrimg;
					 
					array_push($comment_details, $getCommentList);
			
				}
				 $getStoryList['commentsdet']= $comment_details;		
			
				array_push($story_details, $getStoryList);
			
				}
		$response = array('status' => "true","story_result" => $story_details);	
		echo json_encode($response);
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


