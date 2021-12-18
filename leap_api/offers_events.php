<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] || isset($_POST['login_type']) || isset($_POST['userid']) ) {
	 
	$json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
		
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];	
	$response["offers_details"] = array();

	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		$getHospital = $objQuery->mysqlSelect("b.hosp_id as hosp_id","referal as a inner join doctor_hosp as b on a.ref_id = b.doc_id","a.ref_id='".$user_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];

		$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing as a inner join offers_events as b on a.listing_type_id=b.event_id","a.listing_type = 'Events' or a.listing_type = 'Offers' ","a.listing_id desc","","","");
		foreach($postResult as $postResultList){
			 $stuff= array();
			 if( ($postResultList['listing_type']=="Offers")){
					
					$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
					$postdate=$getPostResult[0]['event_date'];	
					$posttitle=$getPostResult[0]['title'];
					$postDescription=$getPostResult[0]['description'];
				}
			else if($postResultList['listing_type']=="Events"){
						 
							$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
						
				}
				 $stuff["post_id"] = $getPostResult[0]['post_id'];		
				 $stuff["title"] = $getPostResult[0]['title'];
				 $stuff["description"] = $getPostResult[0]['description'];
				 $stuff["post_image"] = $getPostResult[0]['post_image'];
				 $stuff["event_date"] = $getPostResult[0]['event_date'];
				 $stuff["event_type"] = $getPostResult[0]['event_type'];
				 array_push($response["offers_details"], $stuff);
		}
		
		
			  $response["status"] = "true";
			  echo(json_encode($response));
	}
	else if($login_type == 2)		// Type-2 Referring Partners
	{
		$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$user_id."' and a.listing_type = 'Events' or a.listing_type = 'Offers' ","a.listing_id desc","","","");
		foreach($postResult as $postResultList){
			 $stuff= array();
			 if( ($postResultList['listing_type']=="Offers")){
					
					$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
					$postdate=$getPostResult[0]['event_date'];	
					$posttitle=$getPostResult[0]['title'];
					$postDescription=$getPostResult[0]['description'];
				}
			else if($postResultList['listing_type']=="Events"){
						 
							$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
						
				}
				 $stuff["post_id"] = $getPostResult[0]['post_id'];		
				 $stuff["title"] = $getPostResult[0]['title'];
				 $stuff["description"] = $getPostResult[0]['description'];
				 $stuff["post_image"] = $getPostResult[0]['post_image'];
				 $stuff["event_date"] = $getPostResult[0]['event_date'];
				 $stuff["event_type"] = $getPostResult[0]['event_type'];
				 array_push($response["offers_details"], $stuff);
		}
		
		
			  $response["status"] = "true";
			  echo(json_encode($response));
	}
	else if($login_type == 3)		// Type-3 Marketing Person
	{
		$getHospital = $objQuery->mysqlSelect("a.hosp_id","hosp_marketing_person as a inner join hosp_tab as b on a.hosp_id = b.hosp_id","a.person_id='".$user_id."'","","","","");
		$hospital_id = $getHospital[0]['hosp_id'];
		$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing as a inner join offers_events as b on a.listing_type_id=b.event_id","a.hosp_id='".$hospital_id."' and a.listing_type = 'Events' or a.listing_type = 'Offers' ","a.listing_id desc","","","");
		foreach($postResult as $postResultList){
			 $stuff= array();
			 if( ($postResultList['listing_type']=="Offers")){
					
					$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
					$postdate=$getPostResult[0]['event_date'];	
					$posttitle=$getPostResult[0]['title'];
					$postDescription=$getPostResult[0]['description'];
				}
			else if($postResultList['listing_type']=="Events"){
						 
							$getPostResult = $objQuery->mysqlSelect("event_id as post_id, title as title,  description as description, photo as post_image, start_end_date as event_date, event_type as event_type ","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
						
				}
				 $stuff["post_id"] = $getPostResult[0]['post_id'];		
				 $stuff["title"] = $getPostResult[0]['title'];
				 $stuff["description"] = $getPostResult[0]['description'];
				 $stuff["post_image"] = $getPostResult[0]['post_image'];
				 $stuff["event_date"] = $getPostResult[0]['event_date'];
				 $stuff["event_type"] = $getPostResult[0]['event_type'];
				 array_push($response["offers_details"], $stuff);
		}
		
		
			  $response["status"] = "true";
			  echo(json_encode($response));
		
	}  

}


?>