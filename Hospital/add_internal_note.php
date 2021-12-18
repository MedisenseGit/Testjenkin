<?php

//add_comment.php

ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");


$error = '';
$comment_content = '';
$getpatient = mysqlSelect("*","doc_my_patient","patient_id='".$_POST['patient_id']."'","","","","");
if(empty($_POST["comment_content"]))
{
 $error .= '<p class="text-danger">Comment is required</p>';
}
else
{
 $comment_content = $_POST["comment_content"];
}

if($error == '')
{
		$arrFields = array();
		$arrValues = array();
				
		$arrFields[]='doc_id';
		$arrValues[]=$getpatient[0]['doc_id'];
		$arrFields[]='patient_id';
		$arrValues[]=$_POST['patient_id'];
		$arrFields[]='notes';
		$arrValues[]=addslashes($comment_content);
		$arrFields[]='date_time';
		$arrValues[]=$curDate;
		$insert_patient=mysqlInsert('patient_internal_notes',$arrFields,$arrValues);
		
		$error = '<label class="text-navy">Comment Added</label>';
}

$data = array(
 'error'  => $error
);

echo json_encode($data);

?>