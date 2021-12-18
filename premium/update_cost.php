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
if(!empty($_POST['bill_id']) && !empty($_POST['narration']))
{
	$bill_id = $_POST['bill_id'];
	$narration = $_POST['narration'];
	$template_id = $_POST['template_id'];
	
	$arrFields_bill = array();
	$arrValues_bill  = array();
	
		$arrFields_bill[] = 'narration';
		$arrValues_bill[] = $narration;
	
	$updateProdCost = mysqlUpdate('out_patient_billing',$arrFields_bill,$arrValues_bill,"billing_id = '".$bill_id."'");
	//$getTotAmount= mysqlSelect("SUM(total_amount) as tot_amount","out_patient_billing","doc_id='".$admin_id."' and opb_temp_id='".$template_id."'","","","","");

	
	//$error = array('status' => "true","getTotAmount" => $getTotAmount[0]['tot_amount']);
	//$error = array('status' => "true");
}

if(!empty($_POST['bill_id']) && !empty($_POST['template_id'])){
	
		$gross_amt = $_POST["amount"]*$_POST["quantity"];
		$discount_amt = ($gross_amt*$_POST["discount"])/100;
		$TotalAmount = $gross_amt-$discount_amt;
		
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[]='qty';
		$arrValues[]=$_POST["quantity"];
		$arrFields[]='amount';
		$arrValues[]=$_POST["amount"];
		$arrFields[]='discount';
		$arrValues[]=$_POST["discount"];
		$arrFields[]='total_amount';
		$arrValues[]=$TotalAmount;
		$updateProdCost = mysqlUpdate('out_patient_billing',$arrFields,$arrValues,"billing_id = '".$_POST['bill_id']."'");
		$getTotAmount= mysqlSelect("SUM(total_amount) as tot_amount","out_patient_billing","doc_id='".$admin_id."' and opb_temp_id='".$_POST['template_id']."'","","","","");
		$error = array('status' => "true","getTotAmount" => $getTotAmount[0]['tot_amount'],"getTotRowAmount" =>$TotalAmount);
	
}

$data = array(
 'error'  => $error
);

echo json_encode($data);


?>