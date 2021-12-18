<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//
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


//USER LOGIN
if(isset($_POST['signin'])){

	$txtUserName = $_POST['txtuser'];
	 $txtPass = md5($_POST['txtpassword']);
	 
	
	$result = mysqlSelect('*','pharma',"(pharma_email='".$txtUserName."' or pharma_contact_num='".$txtUserName."') and pharma_password='".$txtPass."'");
	
			if($result==true){
				
				//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
				$_SESSION['user_name'] = $result[0]['pharma_name'];
				$_SESSION['user_id'] = $result[0]['pharma_id'];
							
				header('location:Request');
				
				
			}
			else
			{
				$response=0;
				$errorMessage="Login failed. Username or password are invalid.";
				header('location:login?response='.$response);
			}
	


}



if(isset($_POST['forgot'])) {

	$useremail = $_POST['txtemail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	
	
	$chkUser = mysqlSelect("*","our_partners","Email_id='".$useremail."'","","","","");
		if($chkUser==true){
			$arrFields[] = 'password';
			$arrValues[] = $encypassword;
		
		$updateUser=mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$chkUser[0]['partner_id']."'");
		
		$recoverLink="Link: www.medisensepractice.com<br>User Name: ".$chkUser[0]['Email_id']." / ".$chkUser[0]['cont_num1']."<br>Password: ".$password;
			
			$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

	Please use below temporary user name & password using our secure server:<br><br> 

	".$recoverLink."<br><br>
	If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
			
			
						$url_page = 'send_recoverLink.php';
						$url = "https://referralio.com/REFERRALIO_EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkUser[0]['contact_person']);
						$url .= "&message=".urlencode($message);
						$url .= "&reclink=".urlencode($recoverLink);
						$ch = curl_init (); // setup a curl
						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
						
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
						
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
						
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
						
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
						
						$output = curl_exec ( $ch );
						
						// echo "output".$output;
						
						curl_close ( $ch ); 
		header('Location:login?respond=3');
		}
		else
		{
			header('Location:login?respond=4');
		}
	
	
}
?>