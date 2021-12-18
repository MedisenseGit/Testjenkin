<?php
ob_start();
session_start();
error_reporting(0);  


include('send_text_message.php');
include('send_mail_function.php');
// Update Login Account Password

 if(API_KEY == $_POST['API_KEY'] ||  isset($_POST['username']) || isset($_POST['email']) || isset($_POST['mobile_num'])  || isset($_POST['comments'])  || isset($_POST['member_type'])) {
	 
	$username = $_POST['username'];
	$email = $_POST['email'];
	$mobilenum = $_POST['mobile_num'];
	$comments = $_POST['comments'];
	$accountType = $_POST['member_type'];
	$hospmail = "medical@medisense.me";
	$ownermail="shashi@medisense.me";
	$ccmail="ambarish@medisense.me, shashistavarmath@medisense.me";

	if($accountType == 1) {
		$account = "As a Partner";
	}
	else if($accountType == 2) {
		$account = "As a Doctor";
	}
	

	$mailformat="Dear Team<br><br>We got one new Leap App signup request. Please go through below client details <br>
					<b>Name :</b> ".$username."<br><b>Email :</b>".$email."<br><b>Mobile : </b>".$mobilenum."<br><b>RequestType :</b> ".$account."<br><b>Comments :</b> ".$comments."<br><br><b>Many Thanks</b>";
					//Registration Email notification to Primary members
					$hospname="Medisense Leap";														
						$url_page1 = 'newsignupapp_reg_mail.php';
						$url = rawurlencode($url_page1);
						$url .= "?mailformat=" . urlencode($mailformat);
						$url .= "&patmail=".urlencode($ownermail);
						$url .= "&hospmail=".urlencode($ownermail);
						$url .= "&hospname=".urlencode($hospname);
						$url .= "&ccmail=".urlencode($ccmail);		
						send_mail($url);
	$result = array('status' => "true",'signup' => "Your Signup request has been successfully submitted.\n You may receive an email/call to your registered mail id or phone number.");
	echo json_encode($result);

	
}


?>