<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

 if( (API_KEY == $_POST['API_KEY']) || isset($_POST['login_type']) || isset($_POST['userid']) || isset($_POST['eventid']) || isset($_POST['listing_type'])) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	
	$login_type = $_POST['login_type'];
	$user_id = $_POST['userid'];
	$event_id = $_POST['eventid'];
	$listing_type = $_POST['listing_type'];
	$response["comments"] = array();
	
	if($login_type == 1)		// Type-1 Hospital Doctors
	{
		
		if($listing_type=="Blog"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."'","","","","1");
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","home_post_like","category_id='".$event_id."' and and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='surgical'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {
					$result = $objQuery->mysqlSelect("*","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['contact_person'];		
			 		$stuff["user_image"] = $result[0]['doc_photo'];
				}
				else {
					$result = $objQuery->mysqlSelect("*","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];		
				 	 $stuff["user_image"] = $result[0]['doc_photo'];
				}
				/* $stuff["comments"] = $commentResult[0]['comments'];		
			 	$stuff["post_date"] = $commentResult[0]['post_date'];
 			 	$stuff["topic_id"] = $commentResult[0]['topic_id'];
			 	$stuff["topic_type"] = $commentResult[0]['topic_type']; */
				
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = $postResultList['post_date'];
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => 0, "maybe_count" => 0,"cannot" => 0, "cannot_count" => 0);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Offers"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","event_visitors_tab","going='".$user_id."' and event_id='".$event_id."'","","","","1");
							
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","event_visitors_tab","event_id='".$event_id."' and going!=0","","","","");
			
			$maybe = $objQuery->mysqlSelect("COUNT(*) as maybe","event_visitors_tab","maybe='".$user_id."' and event_id='".$event_id."'","","","","1");
			
			$num_maybe = $objQuery->mysqlSelect("COUNT(*) as num_maybe","event_visitors_tab","event_id='".$event_id."' and maybe!=0","","","","");
			
			$cannot = $objQuery->mysqlSelect("COUNT(*) as cannot","event_visitors_tab","cannotgo='".$user_id."' and event_id='".$event_id."'","","","","1");
		
			$num_cannot = $objQuery->mysqlSelect("COUNT(*) as num_cannot","event_visitors_tab","event_id='".$event_id."' and cannotgo!=0","","","","");
			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => $maybe[0]['maybe'], "maybe_count" => $num_maybe[0]['num_maybe'],"cannot" => $cannot[0]['cannot'], "cannot_count" => $num_cannot[0]['num_cannot']);
			echo json_encode($success);
		}
		else if($listing_type=="Events"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","event_visitors_tab","going='".$user_id."' and event_id='".$event_id."'","","","","1");
							
			//$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","event_visitors_tab","event_id='".$event_id."' and going!=0","","","","");
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","home_post_like","category_id='".$event_id."' and likes!=0","","","","");
		

			$maybe = $objQuery->mysqlSelect("COUNT(*) as maybe","event_visitors_tab","maybe='".$user_id."' and event_id='".$event_id."'","","","","1");
			
			$num_maybe = $objQuery->mysqlSelect("COUNT(*) as num_maybe","event_visitors_tab","event_id='".$event_id."' and maybe!=0","","","","");
			
			$cannot = $objQuery->mysqlSelect("COUNT(*) as cannot","event_visitors_tab","cannotgo='".$user_id."' and event_id='".$event_id."'","","","","1");
		
			$num_cannot = $objQuery->mysqlSelect("COUNT(*) as num_cannot","event_visitors_tab","event_id='".$event_id."' and cannotgo!=0","","","","");
			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => $maybe[0]['maybe'], "maybe_count" => $num_maybe[0]['num_maybe'],"cannot" => $cannot[0]['cannot'], "cannot_count" => $num_cannot[0]['num_cannot']);
			echo json_encode($success);
		}
		else if($listing_type=="Surgical"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","home_post_like","likes='".$user_id."' and category_id='".$event_id."'","","","","1");
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","home_post_like","category_id='".$event_id."' and likes!=0","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='surgical'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {
					$result = $objQuery->mysqlSelect("*","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['contact_person'];		
			 		$stuff["user_image"] = $result[0]['doc_photo'];
				}
				else {
					$result = $objQuery->mysqlSelect("*","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];		
				 	 $stuff["user_image"] = $result[0]['doc_photo'];
				}
				/* $stuff["comments"] = $commentResult[0]['comments'];		
			 	$stuff["post_date"] = $commentResult[0]['post_date'];
 			 	$stuff["topic_id"] = $commentResult[0]['topic_id'];
			 	$stuff["topic_type"] = $commentResult[0]['topic_type']; */
	
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = $postResultList['post_date'];
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			 $success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => 0, "maybe_count" => 0,"cannot" => 0, "cannot_count" => 0);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		
		// $success = array('status' => "true","going" => $going, "going_count" => $num_goings,"maybe" => $maybe, "maybe_count" => $num_maybe,"cannot" => $cannot, "cannot_count" => $num_cannot);
		
		
	}
	else if($login_type == 2)	// Type-2 Referring Partners
	{
		if($listing_type=="Blog"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","home_post_like","likes='".$user_id."' and category_id='".$event_id."'","","","","1");
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","home_post_like","category_id='".$event_id."' and likes!=0","","","","");
		
			//$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => 0, "maybe_count" => 0,"cannot" => 0, "cannot_count" => 0);
			//echo json_encode($success);	
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='surgical'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {
					$result = $objQuery->mysqlSelect("*","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['contact_person'];		
			 		$stuff["user_image"] = $result[0]['doc_photo'];
				}
				else {
					$result = $objQuery->mysqlSelect("*","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];		
				 	 $stuff["user_image"] = $result[0]['doc_photo'];
				}
				/* $stuff["comments"] = $commentResult[0]['comments'];		
			 	$stuff["post_date"] = $commentResult[0]['post_date'];
 			 	$stuff["topic_id"] = $commentResult[0]['topic_id'];
			 	$stuff["topic_type"] = $commentResult[0]['topic_type']; */
				
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = $postResultList['post_date'];
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => 0, "maybe_count" => 0,"cannot" => 0, "cannot_count" => 0);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Offers"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","event_visitors_tab","going='".$user_id."' and event_id='".$event_id."'","","","","1");
							
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","event_visitors_tab","event_id='".$event_id."' and going!=0","","","","");
			
			$maybe = $objQuery->mysqlSelect("COUNT(*) as maybe","event_visitors_tab","maybe='".$user_id."' and event_id='".$event_id."'","","","","1");
			
			$num_maybe = $objQuery->mysqlSelect("COUNT(*) as num_maybe","event_visitors_tab","event_id='".$event_id."' and maybe!=0","","","","");
			
			$cannot = $objQuery->mysqlSelect("COUNT(*) as cannot","event_visitors_tab","cannotgo='".$user_id."' and event_id='".$event_id."'","","","","1");
		
			$num_cannot = $objQuery->mysqlSelect("COUNT(*) as num_cannot","event_visitors_tab","event_id='".$event_id."' and cannotgo!=0","","","","");
			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => $maybe[0]['maybe'], "maybe_count" => $num_maybe[0]['num_maybe'],"cannot" => $cannot[0]['cannot'], "cannot_count" => $num_cannot[0]['num_cannot']);
			echo json_encode($success);
		}
		else if($listing_type=="Events"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","event_visitors_tab","going='".$user_id."' and event_id='".$event_id."'","","","","1");
							
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","event_visitors_tab","event_id='".$event_id."' and going!=0","","","","");
			
			$maybe = $objQuery->mysqlSelect("COUNT(*) as maybe","event_visitors_tab","maybe='".$user_id."' and event_id='".$event_id."'","","","","1");
			
			$num_maybe = $objQuery->mysqlSelect("COUNT(*) as num_maybe","event_visitors_tab","event_id='".$event_id."' and maybe!=0","","","","");
			
			$cannot = $objQuery->mysqlSelect("COUNT(*) as cannot","event_visitors_tab","cannotgo='".$user_id."' and event_id='".$event_id."'","","","","1");
		
			$num_cannot = $objQuery->mysqlSelect("COUNT(*) as num_cannot","event_visitors_tab","event_id='".$event_id."' and cannotgo!=0","","","","");
			
			$success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => $maybe[0]['maybe'], "maybe_count" => $num_maybe[0]['num_maybe'],"cannot" => $cannot[0]['cannot'], "cannot_count" => $num_cannot[0]['num_cannot']);
			echo json_encode($success);
		}

		else if($listing_type=="Surgical"){
			$going = $objQuery->mysqlSelect("COUNT(*) as going","home_post_like","likes='".$user_id."' and category_id='".$event_id."'","","","","1");
			$num_goings = $objQuery->mysqlSelect("COUNT(*) as num_going","home_post_like","category_id='".$event_id."' and likes!=0","","","","");
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='surgical'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {
					$result = $objQuery->mysqlSelect("*","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['contact_person'];		
			 		$stuff["user_image"] = $result[0]['doc_photo'];
				}
				else {
					$result = $objQuery->mysqlSelect("*","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];		
				 	 $stuff["user_image"] = $result[0]['doc_photo'];
				}
				/* $stuff["comments"] = $commentResult[0]['comments'];		
			 	$stuff["post_date"] = $commentResult[0]['post_date'];
 			 	$stuff["topic_id"] = $commentResult[0]['topic_id'];
			 	$stuff["topic_type"] = $commentResult[0]['topic_type']; */
	
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = $postResultList['post_date'];
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			 $success = array('status' => "true","going" => $going[0]['going'], "going_count" => $num_goings[0]['num_going'],"maybe" => 0, "maybe_count" => 0,"cannot" => 0, "cannot_count" => 0);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		
		// $success = array('status' => "true","going" => $going, "going_count" => $num_goings,"maybe" => $maybe, "maybe_count" => $num_maybe,"cannot" => $cannot, "cannot_count" => $num_cannot);
		
		
		
	}
	else if($login_type == 3)	// Type-3 Marketing Person
	{
	}  
	
}


?>