<?php ob_start();
 error_reporting(0);
 session_start();

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$cur_Date=date('Y-m-d');
include('send_text_message.php');
include('send_mail_function.php');

$today_date = date('Y-m-d',strtotime($cur_Date));
//echo $today_date."<br>";
		
$getScheduledDate = mysqlSelect("event_id, campaign_scheduled_date, campaign_email_content, campaign_sms_content, doc_id","offers_events","campaign_scheduled_date='".$today_date."'","","","","");

if($getScheduledDate==true){

	foreach($getScheduledDate as $getList)
	{
		
	//$getDocInfo = mysqlSelect("ref_id, ref_mail","referal","ref_id='".$getList['doc_id']."'","","","","");
	//echo $getDocInfo[0]['ref_mail']."<br>";
		
	$getDetails = mysqlSelect("Name, Mobile_Number, Email_ID","campaign_contact_lists","","","","","");
	
	foreach($getDetails as $getDetailsList) {
		
		//SMS Message notification
		if(!empty($getDetailsList['Mobile_Number'])) {
			$responsemsg= $getList['campaign_sms_content'];
			send_msg($getDetailsList['Mobile_Number'],$responsemsg);
		}
		
	/*	//Email Message notification
		if(!empty($getDetailsList['Email_ID'])) {
			$doc_mail='medical@medisense.me';
			$email=$getDetailsList['Email_ID'];
			
			$url_page = 'send_auto_campaigns.php';
			$url .= rawurlencode($url_page);
			$url .= "?usermail=".urlencode($email);
			$url .= "&username=".urlencode($getDetailsList['Name']);
			$url .= "&message=".urlencode($getList['campaign_email_content']);
			$url .= "&docmail=".urlencode('medical@medisense.me');
			//$url .= "&docmail=".urlencode($doc_mail);
		//	send_mail($url);
		
			$page_url = $url;
		
			$url1 = "https://referralio.com/REFERRALIO_EMAIL/";
			$url1 .=$page_url;
			$ch = curl_init (); // setup a curl
			curl_setopt ( $ch, CURLOPT_URL, $url1); // set url1 to send to
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
			$output = curl_exec ( $ch );
			// echo "output".$output;
			curl_close ( $ch );
		} */
	}
		
	}
}



?>
