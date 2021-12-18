<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
ob_start();


 if(API_KEY == $_POST['API_KEY']) {	
	
	$speaker = addslashes($_POST['speaker']);
	$rating = $_POST['rating'];
	$comment = addslashes($_POST['comment']);
	$event_id = $_POST['event_id'];
	$partner_id = $_POST['partner_id'];
	$username = $_POST['user_name'];
	$curDate=date('Y-m-d H:i:s');
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'partner_id';
	$arrValues[] = $partner_id;
	$arrFields[] = 'doc_id';
	$arrValues[] = $speaker;
	$arrFields[] = 'event_id';
	$arrValues[] = $event_id;
	$arrFields[] = 'quality_rating';
	$arrValues[] = $rating;
	$arrFields[] = 'comments';
	$arrValues[] = $comment;
	$arrFields[] = 'Timestamp';
	$arrValues[] = $curDate;
	
	$doctimecreate=$objQuery->mysqlInsert('partner_feedback',$arrFields,$arrValues);
	
	$getDocMail = $objQuery->mysqlSelect("ref_name,ref_mail","referal","ref_id='".$speaker."'" ,"","","","");
		
						$url_page = 'event_feedback_mail.php';
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?docname=".urlencode($getDocMail[0]['ref_name']);
						$url .= "&docmail=" . urlencode($getDocMail[0]['ref_mail']);		
						$url .= "&username=" . urlencode($username);
						$url .= "&rating=" . urlencode($rating);	
						$url .= "&comments=" . urlencode($comment);						
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
	// header("location:offers.php?s=Events&id=".$_POST['id']."&response=success");
	
		$result = array("result" => "success");
		echo json_encode($result);
	
 }
?>
