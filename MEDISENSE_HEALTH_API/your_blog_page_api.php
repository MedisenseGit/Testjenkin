<?php ob_start();
 error_reporting(1);
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
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0)
	
	{
		$getFeatureDoctors = mysqlSelect("ref_id, ref_name, doc_photo, doc_city, doc_state, doc_qual","referal","doc_spec!=555 and anonymous_status!=1","doc_type_val asc","","","0,30");
		$doc_details= array();
			foreach($getFeatureDoctors as $result_doctorList) 
			{
				
			$getDocList['ref_id']=$result_doctorList['ref_id'];
			$getDocList['ref_name']=$result_doctorList['ref_name'];
			$getDocList['doc_photo']=$result_doctorList['doc_photo'];
			$getDocList['doc_city']=$result_doctorList['doc_city'];
			$getDocList['doc_state']=$result_doctorList['doc_state'];
			
			$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
			$getDocList['doc_specializations']= $doc_specialization;
				
			
			array_push($doc_details, $getDocList);
			}	
		$getCountries= mysqlSelect("*","countries","","country_name asc","","","");
		$postResult =mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='blog'","post_id desc","","","");
		$blog_details= array();
			foreach($postResult as $result_blogList) 
			{
				$getBlogList['post_id']=$result_blogList['post_id'];
			$getBlogList['post_tittle']=$result_blogList['post_tittle'];
			$getBlogList['postkey']=$result_blogList['postkey'];
			$getBlogList['Login_User_Type']=$result_blogList['Login_User_Type'];
			$getBlogList['Login_User_Id']=$result_blogList['Login_User_Id'];
			$getBlogList['post_image']=$result_blogList['post_image'];
			$getBlogList['post_date']=$result_blogList['post_date'];
			$getBlogList['post_description']=$result_blogList['post_description'];
			$getBlogList['num_views']=$result_blogList['num_views'];
			
			$CommentsCounts = mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$result_blogList['post_id']."'","","","","");
	        $getBlogList['comments_count']= $CommentsCounts;
			
			$likeCounts = mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$result_blogList['post_id']."'","","","","");
	         $getBlogList['likes_count']= $likeCounts;
			 
			array_push($blog_details, $getBlogList);
			
				}
				$latestBlogDoc = mysqlSelect("*","health_author_login as c right join health_home_posts as a on c.login_id=a.Login_User_Id left join referal as b on b.ref_id=a.Login_User_Id","a.post_type='blog'","COUNT(a.Login_User_Id) desc","a.Login_User_Id","",""); 
			 
				
			$response = array('status' => "true","blog_array" => $blog_details,"doctor_array" => $doc_details,"getCountries" => $getCountries,"getAuthors" => $latestBlogDoc);
			
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
		
		
	}
	else if(HEALTH_API_KEY == $data ->api_key && isset($data->filter_search))
	
	{
		$postResult = mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='blog' and ((post_tittle LIKE '%".$data->filter_search."%') or (post_description LIKE '%".$data->filter_search."%'))","post_id desc","","","");
		$blog_details= array();
			foreach($postResult as $result_blogList) 
			{
				$getBlogList['post_id']=$result_blogList['post_id'];
			$getBlogList['post_tittle']=$result_blogList['post_tittle'];
			$getBlogList['postkey']=$result_blogList['postkey'];
			$getBlogList['Login_User_Type']=$result_blogList['Login_User_Type'];
			$getBlogList['Login_User_Id']=$result_blogList['Login_User_Id'];
			$getBlogList['post_image']=$result_blogList['post_image'];
			$getBlogList['post_date']=$result_blogList['post_date'];
			$getBlogList['post_description']=$result_blogList['post_description'];
			$getBlogList['num_views']=$result_blogList['num_views'];
			
			$CommentsCounts = mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$result_blogList['post_id']."'","","","","");
	        $getBlogList['comments_count']= $CommentsCounts;
			
			$likeCounts =mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$result_blogList['post_id']."'","","","","");
	         $getBlogList['likes_count']= $likeCounts;
			 
			array_push($blog_details, $getBlogList);
			
				}
				$latestBlogDoc = mysqlSelect("*","health_author_login as c right join health_home_posts as a on c.login_id=a.Login_User_Id left join referal as b on b.ref_id=a.Login_User_Id","a.post_type='blog'","COUNT(a.Login_User_Id) desc","a.Login_User_Id","",""); 
			 
				
			$response = array('status' => "true","blog_array" => $blog_details,"getAuthors" => $latestBlogDoc);
			
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
		
		
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)	
	{
		$postblog = mysqlSelect("*","health_home_posts","post_type='blog'","post_id desc","","","0,3");
		$response = array('status' => "true","latest_blog" => $postblog);
		echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==3)	
	{
		$postResult = mysqlSelect("*","health_home_posts","postkey='".$data->post_id."'","","","","");
		$num_views=$postResult[0]['num_views'];
		$num_views=$num_views+1;
		$arrFields = array();
		$arrValues = array();
		$arrFields[]= 'num_views';
		$arrValues[]= $num_views;
		$updateviews=mysqlUpdate('health_home_posts',$arrFields,$arrValues,"post_id='".$postResult[0]['post_id']."'");
		
		if($postResult[0]['Login_User_Type']=="doc"){
		$getUser = mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResult[0]['Login_User_Id']."'","","","","");
							$userName=$getUser[0]['ref_name'];
							//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$userimg=HOST_MAIN_URL."Doc/".$postResult[0]['Login_User_Id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="http://placehold.it/75x75";
			
					}
			}
			else if($postResult[0]['Login_User_Type']=="user"){
					$getloginuser = mysqlSelect("*","health_author_login","login_id='".$postResult[0]['Login_User_Id']."'","","","","");					
					$userName=$getUser[0]['ref_name'];
				//Profile Pic
				$userName=$getloginuser[0]['sub_name'];
											if(!empty($getloginuser[0]['user_img'])){
											$userimg=$getloginuser[0]['user_img'];	
											}
											else{
												$userimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";	
											}
				}
				else{
										$userimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";
									}
		$postComments = mysqlSelect("*","health_home_post_comments","topic_id='".$postResult[0]['post_id']."'","comment_id desc","","","");
		$comment_details= array();
			foreach($postComments as $result_blogList) 
			{
				$getBlogList['post_date']=$result_blogList['post_date'];
			$getBlogList['comments']=$result_blogList['comments'];
			
			if($result_blogList['login_User_Type']=="doc"){
						$getUser = mysqlSelect("ref_name,doc_photo","referal","ref_id='".$result_blogList['login_id']."'","","","","");
							$usrName=$getUser[0]['ref_name'];
					//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$usrimg=HOST_MAIN_URL."Doc/".$result_blogList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$usrimg="http://placehold.it/75x75";
			
					}
					}
					else if($result_blogList['login_User_Type']=="user"){
						//$getloginuser = $objQuery->mysqlSelect("*","health_author_login","login_id='".$result_blogList['login_id']."'","","","","");					
						$getloginuser = $objQuery->mysqlSelect("*","login_user","login_id='".$result_blogList['login_id']."'","","","","");					
						$usrName=$getUser[0]['ref_name'];
					//Profile Pic
					$usrName=$getloginuser[0]['sub_name'];
												if(!empty($getloginuser[0]['user_img'])){
												$usrimg=$getloginuser[0]['user_img'];	
												}
												
												else{
													$usrimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";	
												}
					}
					else{
											$userimg=HOST_HEALTH_URL."new_assets/images/no_user_icon.png";
										}			
			 $getBlogList['userName']= $usrName;
			 $getBlogList['userImage']= $usrimg;
			 
			array_push($comment_details, $getBlogList);
			
				}
				
		
		$CommentsCounts = mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$postResult[0]['post_id']."'","","","","");
		$likeCounts = mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$postResult[0]['post_id']."'","","","","");
	    $latestblog = mysqlSelect("*","health_home_posts","post_type='blog'","post_id desc","","","0,3");
		
		$response = array('status' => "true","userName" => $userName,"userImg" => $userimg,"numViews" => $num_views,"latestblog" => $latestblog,"blogDet" => $postResult,"commentDet" => $comment_details,"commentCount" => $CommentsCounts,"likeCount" => $likeCounts);
		echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==4)	{
		$blog_id=implode(',',$data ->checked_blog);

		if($data ->checked_blog[0]=='0'){
			$postResult = mysqlSelect("*","health_home_posts","post_type='blog'","post_id desc","","","0,30");
		}
		else{
		$postResult = mysqlSelect("*","health_home_posts","Login_User_Id IN (".$blog_id.") and post_type='blog'","post_id desc","","","0,30"); 
		}
		$blog_details= array();
			foreach($postResult as $result_blogList) 
			{
				$getBlogList['post_id']=$result_blogList['post_id'];
			$getBlogList['post_tittle']=$result_blogList['post_tittle'];
			$getBlogList['postkey']=$result_blogList['postkey'];
			$getBlogList['Login_User_Type']=$result_blogList['Login_User_Type'];
			$getBlogList['Login_User_Id']=$result_blogList['Login_User_Id'];
			$getBlogList['post_image']=$result_blogList['post_image'];
			$getBlogList['post_date']=$result_blogList['post_date'];
			$getBlogList['post_description']=$result_blogList['post_description'];
			$getBlogList['num_views']=$result_blogList['num_views'];
			
			$CommentsCounts = mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$result_blogList['post_id']."'","","","","");
	        $getBlogList['comments_count']= $CommentsCounts;
			
			$likeCounts = mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$result_blogList['post_id']."'","","","","");
	         $getBlogList['likes_count']= $likeCounts;
			 
			array_push($blog_details, $getBlogList);
			
				}
				
				
			$response = array('status' => "true","blog_array" => $blog_details);
			
		//echo json_encode($success);
			//$response["status"] = "true";
			//$response["getFeatureDoctors"] = $doc_details;
			echo json_encode($response);
		
	}
	
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


