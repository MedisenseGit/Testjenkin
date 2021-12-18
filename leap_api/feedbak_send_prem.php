<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$objQuery = new CLSQueryMaker();

// Feedback
 if(API_KEY == $_POST['API_KEY'] ) {
	
	$admin_id = $_POST['userid'];
	$login_type = $_POST['login_type']; // 1 -  Hospital Doctor, 2 - Partner, 3 - MArketing Person
	$feedback_text = $_POST['txtFeedback'];
	
	$ownermail="medical@medisense.me,shashi@medisense.me,ambarish@medisense.me";

	if($login_type == 1) {
		$getDocDet = $objQuery->mysqlSelect('*','referal',"ref_id='".$admin_id."'");
		$account = "PRACTICE PREMIUM";
		$mailsubject = "PRACTICE PREMIUM Feedback";
		$mailformat="Dear Team<br><br>We got one new PRACTICE PREMIUM Feedback. Please go through below details <br> </br>
					<b>Name :</b> ".$getDocDet[0]['ref_name']."<br><b>Email :</b>".$getDocDet[0]['ref_mail']."<br><b>Mobile : </b>".$getDocDet[0]['contact_num']."<br><b>FeedbackFrom :</b> ".$account."<br><b>Feedback Content :</b> ".$feedback_text."<br><br><b>Many Thanks</b>";
					
					//Registration Email notification to Primary members
																			
						$url_page1 = 'feedback_request.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailSubject=" . urlencode($mailsubject);
						$url .= "&mailformat=".urlencode($mailformat);
						$url .= "&recipientMail=".urlencode($ownermail);
						$url .= "&fromName=".urlencode($getDocDet[0]['ref_name']);
						$url .= "&fromMail=".urlencode($getDocDet[0]['ref_mail']);		
						send_mail($url);
						
			$result = array('status' => "true",'feedback_res' => "Thank you for your valuable feedback.");
			echo json_encode($result);
	}
	else if($login_type == 2) {
		$getDocDet = $objQuery->mysqlSelect('*','our_partners',"partner_id='".$admin_id."'");
		$account = "PRACTICE STANDARD";
		$mailsubject = "PRACTICE STANDARD Feedback";
		$mailformat="Dear Team<br><br>We got one new PRACTICE STANDARD Feedback. Please go through below details <br> </br>
					<b>Name :</b> ".$getDocDet[0]['contact_person']."<br><b>Email :</b>".$getDocDet[0]['Email_id']."<br><b>Mobile : </b>".$getDocDet[0]['cont_num1']."<br><b>FeedbackFrom :</b> ".$account."<br><b>Feedback Content :</b> ".$feedback_text."<br><br><b>Many Thanks</b>";
					
					//Registration Email notification to Primary members
																			
						$url_page1 = 'feedback_request.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailSubject=" . urlencode($mailsubject);
						$url .= "&mailformat=".urlencode($mailformat);
						$url .= "&recipientMail=".urlencode($ownermail);
						$url .= "&fromName=".urlencode($getDocDet[0]['contact_person']);
						$url .= "&fromMail=".urlencode($getDocDet[0]['Email_id']);		
						send_mail($url);
						
			
			$result = array('status' => "true",'feedback_res' => "Thank you for your valuable feedback. ");
			echo json_encode($result);
	}
	else if($login_type == 3) {
		$getDocDet = $objQuery->mysqlSelect('*','hosp_marketing_person',"person_id='".$admin_id."'");
		$account = "PRACTICE PREMIUM";
		$mailsubject = "PRACTICE PREMIUM Feedback  Marketing (Marketing Person) Help Request";
		$mailformat="Dear Team<br><br>We got one new PRACTICE PREMIUM Feedback. Please go through below details <br> </br>
					<b>Name :</b> ".$getDocDet[0]['person_name']."<br><b>Email :</b>".$getDocDet[0]['person_email']."<br><b>Mobile : </b>".$getDocDet[0]['person_mobile']."<br><b>FeedbackFrom :</b> ".$account."<br><b>Feedback Content :</b> ".$feedback_text."<br><br><b>Many Thanks</b>";
					
					//Registration Email notification to Primary members
																			
						$url_page1 = 'feedback_request.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailSubject=" . urlencode($mailsubject);
						$url .= "&mailformat=".urlencode($mailformat);
						$url .= "&recipientMail=".urlencode($ownermail);
						$url .= "&fromName=".urlencode($getDocDet[0]['person_name']);
						$url .= "&fromMail=".urlencode($getDocDet[0]['person_email']);		
						send_mail($url);
						
			
			$result = array('status' => "true",'feedback_res' => "Thank you for your valuable feedback. ");
			echo json_encode($result);
	}
	

	
}


?>