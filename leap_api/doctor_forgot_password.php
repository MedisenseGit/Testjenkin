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

if(API_KEY == $_POST['API_KEY'] || !empty($_POST['doctor_email'])) {

	$useremail = $_POST['doctor_email'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	$chkUser = $objQuery->mysqlSelect("*","referal","ref_mail='".$useremail."'","","","","");	
	$chkMarketingUser = $objQuery->mysqlSelect("*","hosp_marketing_person","person_email='".$useremail."'","","","","");
	if($chkUser==true){
			$arrFields[] = 'doc_password';
			$arrValues[] = $encypassword;
		
		$getUser=$objQuery->mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$chkUser[0]['ref_id']."'");
		
		//$getUser = $objQuery->mysqlSelect("*","subscription","subscribe_id='".$chkUser[0]['subscribe_id']."'","","","","");		
			$recoverLink="Link: https://medisensecrm.com/Login/<br>User Name: ".$chkUser[0]['ref_mail']." / ".$chkUser[0]['contact_num']."<br>Password: ".$password;
			
			$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

	Please use below temporary user name & password using our secure server:<br><br> 

	".$recoverLink."<br><br>
	If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
			
			
						$url_page = 'send_recoverLink.php';
						$url = "https://referralio.com/REFERRALIO_EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkUser[0]['ref_name']);
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
		// header('Location:forgot?response=1');
				$success = array('status' => "true","forgot_password" => "success");     
				echo json_encode($success);
		}
		else if($chkMarketingUser==true) {
				$arrFields[] = 'password';
				$arrValues[] = $encypassword;
		
				$getUser=$objQuery->mysqlUpdate('hosp_marketing_person',$arrFields,$arrValues,"person_id='".$chkMarketingUser[0]['person_id']."'");
				$recoverLink="Link: https://medisensecrm.com/Login/<br>User Name: ".$chkMarketingUser[0]['person_email']." / ".$chkMarketingUser[0]['person_mobile']."<br>Password: ".$password;
			
				$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

				Please use below temporary user name & password using our secure server:<br><br> ".$recoverLink."<br><br>
				If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
				
						$url_page = 'send_recoverLink.php';
						$url = "https://referralio.com/REFERRALIO_EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkMarketingUser[0]['person_name']);
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
				$success = array('status' => "true","forgot_password" => "success");     
				echo json_encode($success);
		}
		else
		{
			// header('Location:forgot?response=2');
			$success = array('status' => "false","forgot_password" => "failed");      
			echo json_encode($success);
		}
	

}	
