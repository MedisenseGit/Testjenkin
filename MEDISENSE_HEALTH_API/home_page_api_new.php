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
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key && $data->filter_type==0 && isset($data ->userid))
	
	{
		//Get Recommended Doctor List
		$memberid = $data ->userid;
		$getMemberDet=mysqlSelect('*','user_family_member',"user_id='".$memberid."'","member_id asc","","","");
		
		$getUserDet=mysqlSelect('*','login_user',"login_id='".$memberid."'","","","","");

		$getUserLocation=mysqlSelect('sub_city','login_user',"login_id='".$memberid."'","","","","");
		// if(count($getUserLocation)==0 || $getUserDet[0]['sub_country']!="India"){
			// $result_doctor = mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_spec!=555 and anonymous_status=0 ","doc_type_val asc","","","0,100");
		
			$result_doctor=mysqlSelect("DISTINCT a.ref_id,md5(a.ref_id) as doc_encyid,a.ref_name,a.ref_exp,a.doc_photo,a.doc_country_id,a.doc_city,a.doc_qual,a.doc_interest,a.geo_latitude,a.geo_longitude","referal as a inner join doc_my_patient as b on a.ref_id=b.doc_id ","b.member_id in(".$getMemberDet[0]['member_id']." )","a.ref_id desc","","","");

			$doc_details= array();
			foreach($result_doctor as $result_doctorList) {
				$getDocList['ref_id']=$result_doctorList['ref_id'];
				$getDocList['doc_encyid']=$result_doctorList['doc_encyid'];
				$getDocList['ref_name']=$result_doctorList['ref_name'];
				$getDocList['doc_exp']=$result_doctorList['ref_exp'];
				$getDocList['doc_photo']=$result_doctorList['doc_photo'];
				$getDocList['doc_country_id']=$result_doctorList['doc_country_id'];
				$getDocList['doc_city']=$result_doctorList['doc_city'];
				$getDocList['doc_qual']=$result_doctorList['doc_qual'];
				$getDocList['doc_interest']=$result_doctorList['doc_interest'];
				$getDocList['geo_latitude']=$result_doctorList['geo_latitude'];
				$getDocList['geo_longitude']=$result_doctorList['geo_longitude']; 
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;

				$getDocCountry=mysqlSelect("DISTINCT a.country_id,a.country_name","countries as a inner join referal as b on a.country_id=b.doc_country_id","b.doc_country_id='".$result_doctorList['doc_country_id']."'","","","","");

				$getDocList['doc_country']= $getDocCountry;
					
				array_push($doc_details, $getDocList);
			}
		
		
					
		$getCountries= mysqlSelect("*","countries","","country_name asc","","","");
		
		$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getUserDet[0]['sub_country']."'", "b.state_name asc", "", "", "");
		$fetchCity = mysqlSelect("d.city_name",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"d.state='".$getUserDet[0]['sub_state']."'","d.city_name asc","d.city_name","","");
		$postResult = mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='blog'","post_id desc","","","10");
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
				
				$StoryResult = mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='story'","post_id desc","","","0,4");
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
				$latestBlogDoc = mysqlSelect("*","health_author_login as c right join health_home_posts as a on c.login_id=a.Login_User_Id left join referal as b on b.ref_id=a.Login_User_Id","a.post_type='blog'","COUNT(a.Login_User_Id) desc","a.Login_User_Id","",""); 
			 //$getCity= mysqlSelect("*","newcitylist","","city_name asc","","","");
			 $getCity=mysqlSelect("d.city_name,d.city_id",'doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id  inner join referal c on a.doc_id=c.ref_id inner join newcitylist d on d.city_id=b.hosp_new_city',"","d.city_name asc","d.city_id","","");
			  $getSpec= mysqlSelect("*","specialization","","spec_id asc","","","");
		   	$healthPackage = mysqlSelect("package_id,package_name,package_image,	package_cost","health_checkup_package","","","","","");
		    $getCompany= mysqlSelect("*","compny_tab","company_logo!='' AND visibility_status=1 ","","","","");
			
				
				
				$personaize_que	= mysqlSelect("*","personalize_app_questions","","","","","");
				$personalize_details= array();
				foreach($personaize_que as $per_app_que) 
				{
					$person_ans = mysqlSelect("*","personalize_app_answers","question_id = '".$per_app_que[id]."'","","","","");
					$getFAQList['que_id']=$per_app_que['id'];		
					$getFAQList['que_name']=$per_app_que['questions'];
					$getFAQList['que_type']=$per_app_que['question_type'];
					$getFAQList['que_title']=$per_app_que['title'];					
					$getFAQList['answers']=$person_ans;						
			
					array_push($personalize_details, $getFAQList);
				}
			
			
			$response = array('status' => "true","getState" => $GetState,"fetchCity" => $fetchCity,"company_array" => $getCompany,"user_array" => $getUserDet,"blog_array" => $blog_details,"story_array" => $story_details,"doctor_array" => $doc_details,"getCountries" => $getCountries,"getAuthors" => $latestBlogDoc,"getMapHosp" => $getHospitalMap,"city_name" => $getCity,"spec_array" => $getSpec,"healthPackage" => $healthPackage,"getMember"=>$getMemberDet,"personaize_que" => $personalize_details);
		
			echo json_encode($response);
		
		
	}
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


