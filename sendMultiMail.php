<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

//TO CHECK COMPANY USER WORK ASSIGN STATUS
if(isset($_POST['send'])){
	
	$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","b.ref_id=26","","","","");
	foreach($chkPatInfo as $patList) {
				if(!empty($patList['patient_email'])){
					echo $patList['patient_email']."\n";
					$url_page = 'MultiEmail.php';
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patmail=".urlencode($patList['patient_email']);
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch ); 
				}
	}
					$Successmessage = "Email Sent to patient Successfully";
	
}

?>
<?php if(isset($Successmessage)){
	echo '<font color="green"><b>'.$Successmessage.'</b></font>';
}
?>
<form method="post" name="sendMail" >
<input type="submit" name="send" />
</form>