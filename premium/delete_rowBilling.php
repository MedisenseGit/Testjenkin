<?php

//add_comment.php

ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$error = '';
$comment_content = '';

if(!empty($_POST['rowid']))
{

	
		mysqlDelete('out_patient_billing',"billing_id='".$_POST['rowid']."'");
		$error = '<label class="text-navy">Deleted Successfully</label>';
}

$data = array(
 'error'  => $error
);

echo json_encode($data);

?>