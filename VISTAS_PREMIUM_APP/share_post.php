<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
include("send_mail_function.php");
include("send_text_message.php");
$ccmail="medical@medisense.me";

ob_start();
$curDate=date('Y-m-d H:i:s');

function hyphenize($string) {
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'),
            array('-', ''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}


$headers = apache_request_headers();
if ($headers){
    $doctor_id = $headers['doctor-id'];
	$timestamp = $headers['x-timestamp'];
	$hashKey = $headers['x-hash'];
	$device_id = $headers['device-id'];
}

$postdata = $_POST;
$finalHash = checkAccessTokenFunction($objQuery, $timestamp, "POST", $postdata, $doctor_id, $device_id);

if(!empty($doctor_id) && !empty($finalHash)) {
	
	if($finalHash == $hashKey) {
		$receiverMail = $_POST['receiverMail'];
		$postTransId = $_POST['postTransId'];
		$postType = $_POST['postType'];
		
		if($postType=="Blog"){
			$getPostdet = mysqlSelect('*','home_posts',"postkey='".$postTransId."'");
			$postTitle = hyphenize($getPostdet[0]['post_tittle']);
			$mailsub = $getPostdet[0]['post_tittle'];
		}
		else 
		{
			$getPostdet = mysqlSelect('*','offers_events',"event_trans_id='".$postTransId."'");
			$postTitle = hyphenize($getPostdet[0]['title']);
			$mailsub = $getPostdet[0]['title'];
		}
		
		
		$shareLink = "https://medisensemd.com/Refer/share-post/".$postTitle."/".$postTransId;
		if($receiverMail!="")
		{
					$page_url = 'share_post_link.php';
					$paturl = rawurlencode($page_url);
					$paturl .= "?sharelink=".urlencode($shareLink);										
					$paturl .= "&receiverMail=".urlencode($receiverMail);
					$paturl .= "&subject=".urlencode($mailsub);		
					send_mail($paturl);
					
			//header("location:offers.php?s=Jobs&id=".$_POST['id']."&response=job-success");
			
			$result = array("result" => "success");
			echo json_encode($result);
		}
		else
		{
			$result = array("result" => "failure");
			echo json_encode($result);
		}
	}
	else {
		$failure = array('status' => "false",'err_msg' => 'Invalid Authorization Key !!!');
		echo json_encode($failure);
	}
	
}
else {
	$failure = array('status' => "false",'err_msg' => 'Invalid User !!!');
	echo json_encode($failure);
}

?>
