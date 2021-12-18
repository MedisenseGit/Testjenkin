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
//$objQuery = new CLSQueryMaker();
ob_start();

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key)
	
	{
		//Get Recommended Doctor List
		$admin_id = $data ->userid;
		$country_name = $data ->country_name;
		$getUserDet=mysqlSelect('*','login_user',"login_id='".$admin_id."'","","","","");
		
		$getcountryid	=	mysqlSelect('country_id','countries',"country_name='".$country_name."'","","","","");
		
		
		$result_doctor =mysqlSelect("ref_id ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude, doc_country_id","referal","doc_spec!=555 and anonymous_status=0 and doc_photo!='' and doc_type='featured' and doc_country_id !='".$getcountryid[0]['country_id']."' ","ref_id desc","","","10");
			
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
				$getDocList['doc_country_id']=$result_doctorList['doc_country_id'];
			
				$doc_specialization = mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital = mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_doctorList['ref_id']."'","","","","");
				$getDocList['doc_hospitals']= $getDocHospital;
				
				$getDocCountry=mysqlSelect("b.country_id as country_id, b.country_name as country_name","referal as a inner join countries as b on b.country_id = a.doc_country_id","b.country_id='".$result_doctorList['doc_country_id']."'","","","","");
				$getDocList['doc_country']= $getDocCountry[0]['country_name']; 
					
				array_push($doc_details, $getDocList);
			}

		$doctors=mysqlSelect("count(ref_id) as id","referal","","","","","");

		$getCountries=mysqlSelect("count(DISTINCT(b.doc_country_id)) as total","countries as a right join referal as b on a.country_id=b.doc_country_id","","","","","");

		$getLang=mysqlSelect("count(DISTINCT(b.id)) as total","doctor_langauges as a inner join languages as b on a.language_id=b.id","","","","","");

		$gethospital=mysqlSelect("count(hosp_id) as total","hosp_tab","","","","","");

		$date=date('Y-m-d H:i:s');
		$getpatient=mysqlSelect("count(patient_id) as total","doc_my_patient","","","","","");

		$getopinion=mysqlSelect("count(patient_id) as total","patient_tab","","","","","");


		$postResult =mysqlSelect("post_id,num_views,post_tittle,postkey,Login_User_Type,Login_User_Id,post_image,post_date,post_description","health_home_posts","post_type='blog'","post_id desc","","","10");
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
			
			
		$getCountry=mysqlSelect('*','countries',"country_name='".$country_name."'","","","","");
		if(!empty($getCountry)) {
			$country_id = $getCountry[0]['country_id'];
			//$country_id = '179'; 			// for demo
		}
		else {
			$country_id = '179'; 					// Default Local doctors 
		}
		
		$result_local_doctor = mysqlSelect("DISTINCT(ref_id) ,md5(ref_id) as doc_encyid, ref_name, ref_exp, doc_photo, doc_country_id, doc_city, doc_qual, doc_interest, geo_latitude, geo_longitude","referal","doc_country_id = '".$country_id."' AND anonymous_status=0 and doc_type='featured'","ref_id desc","","","0,15");

		$localDoc_details= array();
			foreach($result_local_doctor as $result_local_doctorList) {
				$getLocalDocList['ref_id']=$result_local_doctorList['ref_id'];
				$getLocalDocList['doc_encyid']=$result_local_doctorList['doc_encyid'];
				$getLocalDocList['ref_name']=$result_local_doctorList['ref_name'];
				$getLocalDocList['doc_exp']=$result_local_doctorList['ref_exp'];
				$getLocalDocList['doc_photo']=$result_local_doctorList['doc_photo'];
				$getLocalDocList['doc_country_id']=$result_local_doctorList['doc_country_id'];
				$getLocalDocList['doc_city']=$result_local_doctorList['doc_city'];
				$getLocalDocList['doc_qual']=$result_local_doctorList['doc_qual'];
				$getLocalDocList['doc_interest']=$result_local_doctorList['doc_interest'];
				$getLocalDocList['geo_latitude']=$result_local_doctorList['geo_latitude'];
				$getLocalDocList['geo_longitude']=$result_local_doctorList['geo_longitude']; 
			
				$doc_specialization =mysqlSelect('a.doc_id as doc_id, a.doc_type as doc_type, a.spec_id as spec_id, b.spec_name as spec_name, b.spec_group_id as spec_group_id','doc_specialization as a left join specialization as b on a.spec_id=b.spec_id',"a.doc_id='".$result_local_doctorList['ref_id']."'","","","","");
				$getLocalDocList['doc_specializations']= $doc_specialization;
				
				$getDocHospital =mysqlSelect("a.doc_hosp_id as doc_hosp_id, a.doc_id as doc_id, a.hosp_id as hosp_id, b.hosp_name as hosp_name, b.hosp_addrs as hosp_addrs, b.hosp_city as hosp_city, b.hosp_state as hosp_state, b.hosp_country as hosp_country, b.geo_latitude as geo_latitude, b.geo_longitude as geo_longitude","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","a.doc_id = '".$result_local_doctorList['ref_id']."'","","","","");
				$getLocalDocList['doc_hospitals']= $getDocHospital;
	 
				$getDocCountry=mysqlSelect("b.country_id as country_id, b.country_name as country_name","referal as a inner join countries as b on b.country_id = a.doc_country_id","b.country_id='".$result_local_doctorList['doc_country_id']."'","","","","");
				$getLocalDocList['doc_country']= $getDocCountry[0]['country_name'];
				
				array_push($localDoc_details, $getLocalDocList);
			
			}

		// Our Hospital
		$getCompany=mysqlSelect("*","compny_tab","company_logo!='' AND visibility_status=1 ","company_id DESC","","","0,20");
		
  
		$response = array('status' => "true","doctor_array" => $doc_details,"getCountries" => $getCountries[0]['total'],"language" => $getLang[0]['total'],"hospital" => $gethospital[0]['total'],"doctors" => $doctors[0]['id'],"getpatient"=> $getpatient[0]['total'],"getopinion"=> $getopinion[0]['total'],"blog_array" => $blog_details,"localDoc_array" => $localDoc_details,"company_array" => $getCompany,"country_name"=>$country_name);
		
			echo json_encode($response);

	}
	else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}


?>


