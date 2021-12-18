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


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && isset($data ->userid))
	
	{
		//Get Recommended Doctor List
		$memberid = $data ->userid;
		$getUserDet=$objQuery->mysqlSelect('*','login_user',"login_id='".$memberid."'","","","","");
		$getUserLocation=$objQuery->mysqlSelect('sub_city','login_user',"login_id='".$memberid."'","","","","");
		/* if(count($getUserLocation)==0 || $getUserDet[0]['sub_country']!="India"){
			$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status=0 ","doc_type_val asc","","","0,100");
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
					
				array_push($doc_details, $getDocList);
			}
		
		$getHospitalMap = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id,md5(a.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id","b.geo_longitude!=0 and b.geo_latitude!=0","","","","");
		
		
		}
		else 
		{
		$result_doctor = $objQuery->mysqlSelect("c.ref_id ,md5(c.ref_id) as doc_encyid, c.ref_name, c.ref_exp, c.doc_photo, c.doc_city, c.doc_qual, c.doc_interest, c.geo_latitude, c.geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal as c on a.doc_id=c.ref_id inner join newcitylist as d on d.city_id=b.hosp_new_city","c.doc_spec!=555 and c.anonymous_status=0 and (c.ref_address LIKE '%".$getUserLocation[0]['sub_city']."%' or d.city_name LIKE '%".$getUserLocation[0]['sub_city']."%')","c.doc_type_val asc","","","0,100");
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
						
				array_push($doc_details, $getDocList);
			}
          $getHospitalMap = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id,md5(a.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on a.hosp_id = b.hosp_id  inner join referal as c on a.doc_id=c.ref_id inner join newcitylist as d on b.hosp_new_city=d.city_id","c.doc_spec!=555 and c.anonymous_status=0 and (c.ref_address LIKE '%".$getUserLocation[0]['sub_city']."%' or d.city_name LIKE '%".$getUserLocation[0]['sub_city']."%') and b.geo_longitude!=0 and b.geo_latitude!=0","","","","");
					
		} */
		
		if(count($getUserLocation)==0 || $getUserDet[0]['sub_country']!="India"){
			//$result_doctor = $objQuery->mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status=0 ","doc_type_val asc","","","0,100");
			
			$result_doctor = $objQuery->mysqlSelect("DISTINCT(a.ref_id) ,md5(a.ref_id) as doc_encyid, a.ref_name, a.ref_exp, a.doc_photo, a.doc_city, a.doc_qual, a.doc_interest, a.geo_latitude, a.geo_longitude","referal as a inner join doc_my_patient as b on b.doc_id = a.ref_id","a.doc_spec!=555 and a.anonymous_status=0 AND b.patient_mob='".$getUserDet[0]['sub_contact']."'","a.doc_type_val asc","","","0,100");
			
			$doc_details= array();
			$getHospitalMap= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
					
				array_push($doc_details, $getDocList);
				
				
			}
		
		
		$getHospitalMap = $objQuery->mysqlSelect("DISTINCT(a.doc_hosp_id) as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id,md5(a.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id inner join referal as c on c.ref_id = a.doc_id inner join doc_my_patient as d on d.doc_id = c.ref_id","b.geo_longitude!=0 and b.geo_latitude!=0 and d.patient_mob='".$getUserDet[0]['sub_contact']."' AND c.doc_spec!=555 and c.anonymous_status=0","","","","");
		
		
		} else 
		{
		
			$result_doctor = $objQuery->mysqlSelect("c.ref_id ,md5(c.ref_id) as doc_encyid, c.ref_name, c.ref_exp, c.doc_photo, c.doc_city, c.doc_qual, c.doc_interest, c.geo_latitude, c.geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal as c on a.doc_id=c.ref_id inner join newcitylist as d on d.city_id=b.hosp_new_city inner join doc_my_patient as e on e.doc_id = c.ref_id","c.doc_spec!=555 and c.anonymous_status=0 and (c.ref_address LIKE '%".$getUserLocation[0]['sub_city']."%' or d.city_name LIKE '%".$getUserLocation[0]['sub_city']."%') AND e.patient_mob='".$getUserDet[0]['sub_contact']."'","c.doc_type_val asc","","","0,100");
			
			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = $objQuery->mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
						
				array_push($doc_details, $getDocList);
			}
          $getHospitalMap = $objQuery->mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id,md5(a.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on a.hosp_id = b.hosp_id  inner join referal as c on a.doc_id=c.ref_id inner join newcitylist as d on b.hosp_new_city=d.city_id inner join doc_my_patient as e on e.doc_id = c.ref_id","c.doc_spec!=555 and c.anonymous_status=0 and (c.ref_address LIKE '%".$getUserLocation[0]['sub_city']."%' or d.city_name LIKE '%".$getUserLocation[0]['sub_city']."%') and b.geo_longitude!=0 and b.geo_latitude!=0 and e.patient_mob='".$getUserDet[0]['sub_contact']."'","","","","");
		
		 			
		}
					
		$getCountries= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
		$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getUserDet[0]['sub_country']."'", "b.state_name asc", "", "", "");
		$fetchCity = $objQuery->mysqlSelect("d.city_name",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"d.state='".$getUserDet[0]['sub_state']."'","d.city_name asc","d.city_name","","");
		$postResult = $objQuery->mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='blog'","post_id desc","","","");
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
			
			$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$result_blogList['post_id']."'","","","","");
	        $getBlogList['comments_count']= $CommentsCounts;
			
			$likeCounts = $objQuery->mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$result_blogList['post_id']."'","","","","");
	         $getBlogList['likes_count']= $likeCounts;
			 
			array_push($blog_details, $getBlogList);
			
				}
				
				$StoryResult = $objQuery->mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='story'","post_id desc","","","0,4");
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
			
			array_push($story_details, $getStoryList);
			
				}
				$latestBlogDoc = $objQuery->mysqlSelect("*","health_author_login as c right join health_home_posts as a on c.login_id=a.Login_User_Id left join referal as b on b.ref_id=a.Login_User_Id","a.post_type='blog'","COUNT(a.Login_User_Id) desc","a.Login_User_Id","",""); 
			 //$getCity= $objQuery->mysqlSelect("*","newcitylist","","city_name asc","","","");
			 $getCity=$objQuery->mysqlSelect("d.city_name,d.city_id",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"","d.city_name asc","d.city_id","","");
			  $getSpec= $objQuery->mysqlSelect("*","specialization","","spec_id asc","","","");
		   	$healthPackage = $objQuery->mysqlSelect("package_id,package_name,package_image,	package_cost","health_checkup_package","","","","","");
		    $getCompany= $objQuery->mysqlSelect("*","compny_tab","company_logo!='' AND visibility_status=1 ","","","","");
			
			$response = array('status' => "true","getState" => $GetState,"fetchCity" => $fetchCity,"company_array" => $getCompany,"user_array" => $getUserDet,"blog_array" => $blog_details,"story_array" => $story_details,"doctor_array" => $doc_details,"getCountries" => $getCountries,"getAuthors" => $latestBlogDoc,"getMapHosp" => $getHospitalMap,"city_name" => $getCity,"spec_array" => $getSpec,"healthPackage" => $healthPackage);
		
			echo json_encode($response);
		
		
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==2)
	{
		if($data->specId!="0" && $data->cityId!="0"){
			$getHospitalMap = $objQuery->mysqlSelect("b.hosp_id as hosp_id,md5(b.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city,b.hosp_new_city as hosp_new_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","hosp_tab as b inner join doctor_hosp as a on b.hosp_id = a.hosp_id inner join doc_specialization as c on c.doc_id = a.doc_id inner join specialization as d on d.spec_id = c.spec_id","b.hosp_new_city='".$data->cityId."' and b.anonymous_status=0 and c.spec_id='".$data->specId."' and b.geo_longitude!=0 and b.geo_latitude!=0","","","","");
		}
		else if($data->cityId!="0"){
			$getHospitalMap = $objQuery->mysqlSelect("b.hosp_id as hosp_id,md5(b.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city,b.hosp_new_city as hosp_new_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","hosp_tab as b inner join newcitylist as a on a.city_id=b.hosp_new_city inner join doctor_hosp as c on b.hosp_id = c.hosp_id","b.hosp_new_city='".$data->cityId."' and b.anonymous_status=0 and b.geo_longitude!=0 and b.geo_latitude!=0","","","","");
		}
		else if($data->specId!="0"){
			$getHospitalMap = $objQuery->mysqlSelect("b.hosp_id as hosp_id,md5(b.hosp_id) as hosp_encyid, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city,b.hosp_new_city as hosp_new_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","hosp_tab as b inner join doctor_hosp as a on b.hosp_id = a.hosp_id inner join doc_specialization as c on c.doc_id = a.doc_id inner join specialization as d on d.spec_id = c.spec_id","b.anonymous_status=0 and c.spec_id='".$data->specId."' and b.geo_longitude!=0 and b.geo_latitude!=0","","","","");
		
		}
		
		$response = array('status' => "true","get_mapHosp" => $getHospitalMap);
		
			echo json_encode($response);
	}
	else if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==3)	
	{
		$postResult = $objQuery->mysqlSelect("*","health_home_posts","md5(post_id)='".$data->post_id."'","","","","");
		$num_views=$postResult[0]['num_views'];
		$num_views=$num_views+1;
		$arrFields = array();
		$arrValues = array();
		$arrFields[]= 'num_views';
		$arrValues[]= $num_views;
		$updateviews=$objQuery->mysqlUpdate('health_home_posts',$arrFields,$arrValues,"post_id='".$postResult[0]['post_id']."'");
		
		if($postResult[0]['Login_User_Type']=="doc"){
		$getUser = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResult[0]['Login_User_Id']."'","","","","");
							$userName=$getUser[0]['ref_name'];
							//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$userimg=HOST_MAIN_URL."Doc/".$postResult[0]['Login_User_Id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="http://placehold.it/75x75";
			
					}
			}
			else if($postResult[0]['Login_User_Type']=="user"){
					$getloginuser = $objQuery->mysqlSelect("*","health_author_login","login_id='".$postResult[0]['Login_User_Id']."'","","","","");					
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
		$postComments = $objQuery->mysqlSelect("*","health_home_post_comments","topic_id='".$postResult[0]['post_id']."'","comment_id desc","","","");
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
				
		
		$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as commentCount","health_home_post_comments","topic_id='".$postResult[0]['post_id']."'","","","","");
		$likeCounts = $objQuery->mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$postResult[0]['post_id']."'","","","","");
	    $latestblog = $objQuery->mysqlSelect("*","health_home_posts","post_type='blog'","post_id desc","","","0,3");
		$likeUser = $objQuery->mysqlSelect("COUNT(like_id) as likeCount","health_home_post_like","category_id='".$postResult[0]['post_id']."' and likes='".$data->admin_id."'","","","","");
	    
		$response = array('status' => "true","likeUser" => $likeUser,"userName" => $userName,"userImg" => $userimg,"numViews" => $num_views,"latestblog" => $latestblog,"blogDet" => $postResult,"commentDet" => $comment_details,"commentCount" => $CommentsCounts,"likeCount" => $likeCounts);
		echo json_encode($response);
	}
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


