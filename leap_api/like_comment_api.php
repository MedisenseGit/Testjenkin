<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//function used to convert normal date & time to facebook like timeago
function timeAgo($datefrom,$dateto=-1)
	{
		// Defaults and assume if 0 is passed in that
		// its an error rather than the epoch
		
		if($datefrom<=0) { return "A long time ago"; }
		if($dateto==-1) { $dateto = time(); }
		
		// Calculate the difference in seconds betweeen
		// the two timestamps
		
		$difference = $dateto - $datefrom;
		
		// If difference is less than 60 seconds,
		// seconds is a good interval of choice
		
		if($difference < 60)
		{
		$interval = "s";
		}
		
		// If difference is between 60 seconds and
		// 60 minutes, minutes is a good interval
		elseif($difference >= 60 && $difference<60*60)
		{
		$interval = "n";
		}
		
		// If difference is between 1 hour and 24 hours
		// hours is a good interval
		elseif($difference >= 60*60 && $difference<60*60*24)
		{
		$interval = "h";
		}
		
		// If difference is between 1 day and 7 days
		// days is a good interval
		elseif($difference >= 60*60*24 && $difference<60*60*24*7)
		{
		$interval = "d";
		}
		
		// If difference is between 1 week and 30 days
		// weeks is a good interval
		elseif($difference >= 60*60*24*7 && $difference <
		60*60*24*30)
		{
		$interval = "ww";
		}
		
		// If difference is between 30 days and 365 days
		// months is a good interval, again, the same thing
		// applies, if the 29th February happens to exist
		// between your 2 dates, the function will return
		// the 'incorrect' value for a day
		elseif($difference >= 60*60*24*30 && $difference <
		60*60*24*365)
		{
		$interval = "m";
		}
		
		// If difference is greater than or equal to 365
		// days, return year. This will be incorrect if
		// for example, you call the function on the 28th April
		// 2008 passing in 29th April 2007. It will return
		// 1 year ago when in actual fact (yawn!) not quite
		// a year has gone by
		elseif($difference >= 60*60*24*365)
		{
		$interval = "y";
		}
		
		// Based on the interval, determine the
		// number of units between the two dates
		// From this point on, you would be hard
		// pushed telling the difference between
		// this function and DateDiff. If the $datediff
		// returned is 1, be sure to return the singular
		// of the unit, e.g. 'day' rather 'days'
		
		switch($interval)
		{
		case "m":
		$months_difference = floor($difference / 60 / 60 / 24 /
		29);
		while (mktime(date("H", $datefrom), date("i", $datefrom),
		date("s", $datefrom), date("n", $datefrom)+($months_difference),
		date("j", $dateto), date("Y", $datefrom)) < $dateto)
		{
		$months_difference++;
		}
		$datediff = $months_difference;
		
		// We need this in here because it is possible
		// to have an 'm' interval and a months
		// difference of 12 because we are using 29 days
		// in a month
		
		if($datediff==12)
		{
		$datediff--;
		}
		
		$res = ($datediff==1) ? "$datediff month ago" :
		"$datediff months ago";
		break;
		
		case "y":
		$datediff = floor($difference / 60 / 60 / 24 / 365);
		$res = ($datediff==1) ? "$datediff year ago" :
		"$datediff years ago";
		break;
		
		case "d":
		$datediff = floor($difference / 60 / 60 / 24);
		$res = ($datediff==1) ? "$datediff day ago" :
		"$datediff days ago";
		break;
		
		case "ww":
		$datediff = floor($difference / 60 / 60 / 24 / 7);
		$res = ($datediff==1) ? "$datediff week ago" :
		"$datediff weeks ago";
		break;
		
		case "h":
		$datediff = floor($difference / 60 / 60);
		$res = ($datediff==1) ? "$datediff hour ago" :
		"$datediff hours ago";
		break;
		
		case "n":
		$datediff = floor($difference / 60);
		$res = ($datediff==1) ? "$datediff minute ago" :
		"$datediff minutes ago";
		break;
		
		case "s":
		$datediff = $difference;
		$res = ($datediff==1) ? "$datediff second ago" :
		"$datediff seconds ago";
		break;
		}
		return $res;
}
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
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=2","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Offers"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=2","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Events"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=2","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Surgical"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=2","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Jobs"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=2","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
			
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		
		
		
	}
	else if($login_type == 2)	// Type-2 Referring Partners
	{
		if($listing_type=="Blog"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=1","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }

			
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Offers"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=1","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Events"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=1","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Surgical"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=1","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
		
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
		else if($listing_type=="Jobs"){
			$checkLike = $objQuery->mysqlSelect("COUNT(like_id) as checkUser","home_post_like","likes='".$user_id."' and category_id='".$event_id."' and category_type='".$listing_type."' and user_type=1","","","","");
			$numlikes = $objQuery->mysqlSelect("COUNT(like_id) as countLike","home_post_like","category_id='".$event_id."' and category_type='".$listing_type."'","","","","");
			
			$stuff= array();
			$commentResult = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as CountComment","home_post_comments","topic_id='".$event_id."' and topic_type='".$listing_type."'","","","","");
			foreach($commentResult as $postResultList) {
				// echo $postResultList['login_User_Type'];
				if($postResultList['login_User_Type'] == 1) {  //For Standard Doctor
					$result = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['partner_name'];
						//Profile Pic
						if(!empty($result[0]['doc_photo'])){
						$userimg="https://medisensecrm.com/Refer/partnerProfilePic/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
						}else{
						$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
						}		
			 		$stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 2) {  //For Premium Doctors
					$result = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['ref_name'];	

					//Profile Pic
					if(!empty($result[0]['doc_photo'])){ 
					$userimg="https://medisensecrm.com/Doc/".$postResultList['login_id']."/".$result[0]['doc_photo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				else if($postResultList['login_User_Type'] == 3) {  //For Hospital
					$result = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$postResultList['login_id']."'","","","","");
					 $stuff["username"] = $result[0]['company_name'];	

					//Profile Pic
					if(!empty($result[0]['company_logo'])){ 
					$userimg="https://medisensecrm.com/Hospital/company_logo/".$postResultList['login_id']."/".$result[0]['company_logo']; 
					}else{
					$userimg="https://medisensecrm.com/assets/img/anonymous-profile.png";
					}	
				 	 $stuff["user_image"] = $userimg;
				}
				
				$commentDate=timeAgo($postResultList['post_date']);
				$stuff["comments"] = $postResultList['comments'];		
			 	$stuff["post_date"] = stripslashes($commentDate); ;
 			 	$stuff["topic_id"] = $postResultList['topic_id'];
			 	$stuff["topic_type"] = $postResultList['topic_type'];
			        array_push($response["comments"], $stuff);

			 }
			$success = array('status' => "true","checkLike" => $checkLike[0]['checkUser'], "countLike" => $numlikes[0]['countLike'], "countComment" => $commentCount[0]['CountComment']);
			// echo json_encode($success);
			$response["like"] = $success;
			echo json_encode($response);
		}
	}
	else if($login_type == 3)	// Type-3 Marketing Person
	{
	}  
	
}


?>