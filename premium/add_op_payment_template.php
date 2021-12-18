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

if(empty($_POST["txtNarration"]) || empty($_POST["txtAmount"]))
{
 $error .= '<p class="text-danger">Please fill required field</p>';
}
else
{
 //$comment_content = $_POST["comment_content"];
}

if($error == '')
{
		$gross_amt = $_POST["txtAmount"]*$_POST["qty"];
		$discount_amt = ($gross_amt*$_POST["discount"])/100;
		$TotalAmount = $gross_amt-$discount_amt;
		$temp_id = $_POST['txtTemplateIDNew'];
		$arrFields = array();
		$arrValues = array();
				
		//$arrFields[]='patient_id';
		//$arrValues[]=$admin_id;
		
		if(!empty($temp_id))
		{
			$arrFields[]='opb_temp_id';
			$arrValues[]=$temp_id;
		}	
		if(!empty($admin_id))
		{
			$arrFields[]='doc_id';
			$arrValues[]=$admin_id;
		}	
		
		$arrFields[]='narration';
		$arrValues[]=addslashes($_POST["txtNarration"]);
		$arrFields[]='qty';
		$arrValues[]=$_POST["qty"];
		$arrFields[]='amount';
		$arrValues[]=$_POST["txtAmount"];
		$arrFields[]='discount';
		$arrValues[]=$_POST["discount"];
		$arrFields[]='total_amount';
		$arrValues[]=$TotalAmount;
		$arrFields[]='active_status';
		$arrValues[]="0";
		$insert_bill=mysqlInsert('out_patient_billing',$arrFields,$arrValues);
		
		$error = '<label class="text-navy">Added Successfully</label>';
}

$data = array(
 'error'  => $error
);

echo json_encode($data);

?>