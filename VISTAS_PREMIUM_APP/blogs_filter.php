<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");



$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$user_id = $doctor_id;
		$blog_type = $_POST['blog_type'];
		$blogs = array();
		$response["blog_details"] = array();
		
		if($blog_type != "All") {
			$postResult = mysqlSelect("*","blogs_offers_events_listing","listing_type = '".$blog_type."'","listing_id desc","","","");
		}
		else {
			$postResult = mysqlSelect("*","blogs_offers_events_listing","","listing_id desc","","","");
		}				
		
			foreach($postResult as $postResultList){
			// echo $postResultList['listing_type'];
			$stuff= array();
			
			if($postResultList['listing_type']=="Blog"){
				 
							$getPostResult = mysqlSelect("post_id as post_id, post_tittle as title, post_description as description, post_image as post_image, post_date as created_date, Login_User_Id as Login_User_Id, company_id as company_id, postkey as transaction_id, num_views as num_views","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
		

							$postdate=$getPostResult[0]['post_date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$postDescription=$getPostResult[0]['post_description'];
							$postContactInfo="";
							$postAttachments="";
							$postCompanyId=$getPostResult[0]['company_id'];
							$postTransactionId=$getPostResult[0]['transaction_id'];
							$postNumViews=$getPostResult[0]['num_views'];
							$postVideoURL = "";
							$from_to_date = "";

							if($getPostResult[0]['Login_User_Id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								}
								else{
									$getOrg = mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
								}
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg="https://medisensemd.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="https://medisensemd.com/Refer/assets/images/anonymous-profile.png";
								}

							
				} else if($postResultList['listing_type']=="Offers"){
					
							$getPostResult = mysqlSelect("event_id as post_id, title as title,company_id as company_id,  description as description, photo as post_image, created_date as created_date, start_end_date as created_date, start_date as start_date, end_date as end_date, job_contact_info as contact_info, description_attachment as attachments, event_trans_id as transaction_id, num_views as num_views","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
							$from_to_date = $getPostResult[0]['start_date']." / ".$getPostResult[0]['end_date'];
							$postdate=$from_to_date;
							//$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
							$postContactInfo=$getPostResult[0]['contact_info'];
							$postAttachments=$getPostResult[0]['attachments'];
							$postCompanyId=$getPostResult[0]['company_id'];
							$postTransactionId=$getPostResult[0]['transaction_id'];
							$postNumViews=$getPostResult[0]['num_views'];
							$postVideoURL = "";
							
							$getOrg = mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									
							
								
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								$userimg="";
						
					}
					else if($postResultList['listing_type']=="Events"){
						 
							$getPostResult = mysqlSelect("event_id as post_id, title as title, company_id as company_id,  description as description, photo as post_image, created_date as created_date, start_end_date as created_date, start_date as 	start_date, end_date as end_date, job_contact_info as contact_info, description_attachment as attachments, event_trans_id as transaction_id, num_views as num_views","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$from_to_date = $getPostResult[0]['start_date']." / ".$getPostResult[0]['end_date'];
							$postdate=$from_to_date;
							$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
							$postContactInfo=$getPostResult[0]['contact_info'];
							$postAttachments=$getPostResult[0]['attachments'];
							$postCompanyId=$getPostResult[0]['company_id'];
							$postTransactionId=$getPostResult[0]['transaction_id'];	
							$postNumViews=$getPostResult[0]['num_views'];
							$postVideoURL = "";
							
							$getOrg = mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								$userimg=""; 
						
						}
					else if($postResultList['listing_type']=="Jobs"){
					
							$getPostResult = mysqlSelect("event_id as post_id,company_id as company_id, title as title,  description as description, photo as post_image, created_date as created_date, start_end_date as created_date, start_date as 	start_date, end_date as end_date, job_contact_info as contact_info, description_attachment as attachments, event_trans_id as transaction_id, num_views as num_views","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
							$from_to_date = $getPostResult[0]['start_date']." / ".$getPostResult[0]['end_date'];
							$postdate=$from_to_date;
							$postdate=$getPostResult[0]['created_date'];	
							$posttitle=$getPostResult[0]['title'];
							$postDescription=$getPostResult[0]['description'];
							$postContactInfo=$getPostResult[0]['contact_info'];
							$postAttachments=$getPostResult[0]['attachments'];
							$postCompanyId=$getPostResult[0]['company_id'];
							$postTransactionId=$getPostResult[0]['transaction_id'];
							$postNumViews=$getPostResult[0]['num_views'];
							$postVideoURL = "";
							
							$getOrg = mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									
							
								
								$username=$getOrg[0]['company_name'];
								$userprof="";
								$userimg=""; 
								
					}
					else if($postResultList['listing_type']=="Surgical"){
				 
						$getPostResult = mysqlSelect("post_id as post_id,Login_User_Id as Login_User_Id, post_tittle as title, post_description as description, post_image as post_image, post_date as created_date,company_id as company_id, postkey as transaction_id, num_views as num_views, video_url as video_url","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
						$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
							
						$postdate=$getPostResult[0]['post_date'];	
						$posttitle=$getPostResult[0]['post_tittle'];
						$postDescription=$getPostResult[0]['post_description'];
						$postContactInfo="";
						$postAttachments="";
						$postCompanyId=$getPostResult[0]['company_id'];
						$postTransactionId=$getPostResult[0]['transaction_id'];
						$postNumViews=$getPostResult[0]['num_views'];
						$postVideoURL = $getPostResult[0]['video_url'];
						$from_to_date = "";
				
						if($getPostResult[0]['Login_User_Id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								}
								else{
									$getOrg = mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
								}
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg="https://medisensemd.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="https://medisensemd.com/Refer/assets/images/anonymous-profile.png";
								}				
				
				}
				 
				 $stuff["post_id"] = $getPostResult[0]['post_id'];		
				 $stuff["title"] = $getPostResult[0]['title'];
				 $stuff["description"] = $getPostResult[0]['description'];
				$stuff["contactInfo"] = $getPostResult[0]['contact_info'];
				 $stuff["attachments"] = $getPostResult[0]['attachments'];
				 $stuff["post_image"] = $getPostResult[0]['post_image'];
				// $stuff["created_date"] = date('d M Y',strtotime($getPostResult[0]['created_date']));
				$stuff["created_date"] = $getPostResult[0]['created_date'];
				 $stuff["listing_type"] = $postResultList['listing_type'];
				$stuff["company_id"]=$getPostResult[0]['company_id'];
				$stuff["transaction_id"]=$getPostResult[0]['transaction_id'];
				$stuff["num_views"]=$getPostResult[0]['num_views'];
				$stuff["video_url"]=$getPostResult[0]['video_url'];
				$stuff["from_to_date"] = $from_to_date;

				$stuff["username"] = $username;
				   $stuff["userprof"] = $userprof;
				    $stuff["userimg"] = $userimg;	
				 array_push($response["blog_details"], $stuff);
				
		}
			
			  $response["status"] = 1;
			echo(json_encode($response));
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