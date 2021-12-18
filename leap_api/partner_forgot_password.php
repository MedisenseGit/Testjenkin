<?php ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
include('send_text_message.php');
include('send_mail_function.php');
//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

if(API_KEY == $_POST['API_KEY'] || !empty($_POST['txtemail'])) {

	$useremail = $_POST['txtemail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	
	
	$chkUser = $objQuery->mysqlSelect("partner_id,Email_id,cont_num1,contact_person","our_partners","Email_id='".$useremail."'","","","","");
		if($chkUser==true){
			$arrFields[] = 'password';
			$arrValues[] = $encypassword;
		
		$updateUser=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$chkUser[0]['partner_id']."'");
		
		$recoverLink="Link: www.medisensepractice.com<br>User Name: ".$chkUser[0]['Email_id']." / ".$chkUser[0]['cont_num1']."<br>Password: ".$password;
			
			$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

	Please use below temporary user name & password using our secure server:<br><br> 

	".$recoverLink."<br><br>
	If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
			
			
						$url_page = 'send_recoverLink.php';
						$url = rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkUser[0]['contact_person']);
						$url .= "&message=".urlencode($message);
						$url .= "&reclink=".urlencode($recoverLink);
						send_mail($url);
		
		//header('Location:forgot?response=1');
		//Send Success message
				$success = array('status' => "true","forgot_password" => "success");     
				echo json_encode($success);
		}
		else
		{
				$success = array('status' => "false","forgot_password" => "failed");      
				echo json_encode($success);
			//header('Location:forgot?response=2');
			//Send Error message
		}
	
	
}