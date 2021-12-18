<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];
 
 date_default_timezone_set('Asia/Kolkata');
 $Cur_Date=date('Y-m-d H:i:s');

if(empty($admin_id)){
header("Location:index.php");
}

if(isset($_POST['cmdReconcile'])){
	$pat_id = $_POST['pat_id'];
	$ref_id1 = $_POST['ref_id1'];
	$amt1 = $_POST['amt1'];
	$ref_id2 = $_POST['ref_id2'];
	$amt2 = $_POST['amt2'];
	$ref_id3 = $_POST['ref_id3'];
	$amt3 = $_POST['amt3'];
	$ref_id4 = $_POST['ref_id4'];
	$amt4 = $_POST['amt4'];
	$paytm_id=$_POST['Pay_Id'];	
	
	$getResult1 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$pat_id."' and ref_id='".$ref_id1."'","");
	$getResult2 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$pat_id."' and ref_id='".$ref_id2."'","");	
	$getResult3 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$pat_id."' and ref_id='".$ref_id3."'","");
	$getResult4 = $objQuery->mysqlSelect("*","doc_op_payment","patient_id='".$pat_id."' and ref_id='".$ref_id4."'","");
	
	$getPayResult1 = $objQuery->mysqlSelect("*","doc_paid_transaction","ref_id='".$ref_id1."' and status='RECONCILED'","");
	$getPayResult2 = $objQuery->mysqlSelect("*","doc_paid_transaction","ref_id='".$ref_id2."' and status='RECONCILED'","");	
	$getPayResult3 = $objQuery->mysqlSelect("*","doc_paid_transaction","ref_id='".$ref_id3."' and status='RECONCILED'","");
	$getPayResult4 = $objQuery->mysqlSelect("*","doc_paid_transaction","ref_id='".$ref_id4."' and status='RECONCILED'","");
	
	$arrValues = array();
	$arrFields = array();

	$arrFields[] = 'paytm_id';
	$arrValues[] = $paytm_id;
	$arrFields[] = 'patient_id';
	$arrValues[] = $pat_id;
	$arrFields[] = 'ref_id';
	$arrValues[] = $ref_id1;
	$arrFields[] = 'ref_amount';
	$arrValues[] = $amt1;
	$arrFields[] = 'status';
	$arrValues[] = "RECONCILED";
	$arrFields[] = 'trans_date';
	$arrValues[] = $Cur_Date;
	if($getResult1==true){	
	$updateTrans=$objQuery->mysqlUpdate('doc_op_payment',$arrFields,$arrValues,"patient_id='".$pat_id."' and ref_id='".$ref_id1."'");	
	}else
	{
		if($ref_id1!=0 && $amt1!=""){
		$insertTrans=$objQuery->mysqlInsert('doc_op_payment',$arrFields,$arrValues);
		
		$arrValues11 = array();
		$arrFields11 = array();

		$arrFields11[] = 'amount_paid';
		$arrValues11[] = $amt1;
		
		$updateTrans=$objQuery->mysqlUpdate('patient_referal',$arrFields11,$arrValues11,"patient_id='".$pat_id."' and ref_id='".$ref_id1."'");	
	
		}
	}
	$arrValues1 = array();
	$arrFields1 = array();

	$arrFields1[] = 'paytm_id';
	$arrValues1[] = $paytm_id;
	$arrFields1[] = 'patient_id';
	$arrValues1[] = $pat_id;
	$arrFields1[] = 'ref_id';
	$arrValues1[] = $ref_id2;
	$arrFields1[] = 'ref_amount';
	$arrValues1[] = $amt2;
	$arrFields1[] = 'status';
	$arrValues1[] = "RECONCILED";
	$arrFields1[] = 'trans_date';
	$arrValues1[] = $Cur_Date;
	if($getResult2==true){	
	$updateTrans=$objQuery->mysqlUpdate('doc_op_payment',$arrFields1,$arrValues1,"patient_id='".$pat_id."' and ref_id='".$ref_id2."'");	
	}else
	{
		if($ref_id2!=0 && $amt2!=""){
		$insertTrans=$objQuery->mysqlInsert('doc_op_payment',$arrFields1,$arrValues1);
		
		$arrValues22 = array();
		$arrFields22 = array();

		$arrFields22[] = 'amount_paid';
		$arrValues22[] = $amt2;
		
		$updateTrans=$objQuery->mysqlUpdate('patient_referal',$arrFields22,$arrValues22,"patient_id='".$pat_id."' and ref_id='".$ref_id2."'");
		}
	}
	$arrValues2 = array();
	$arrFields2 = array();

	$arrFields2[] = 'paytm_id';
	$arrValues2[] = $paytm_id;
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $pat_id;
	$arrFields2[] = 'ref_id';
	$arrValues2[] = $ref_id3;
	$arrFields2[] = 'ref_amount';
	$arrValues2[] = $amt3;
	$arrFields2[] = 'status';
	$arrValues2[] = "RECONCILED";
	$arrFields2[] = 'trans_date';
	$arrValues2[] = $Cur_Date;
	if($getResult3==true){	
	$updateTrans=$objQuery->mysqlUpdate('doc_op_payment',$arrFields2,$arrValues2,"patient_id='".$pat_id."' and ref_id='".$ref_id3."'");	
	}else
	{
		if($ref_id3!=0 && $amt3!=""){	
		$insertTrans=$objQuery->mysqlInsert('doc_op_payment',$arrFields2,$arrValues2);
		
		$arrValues33 = array();
		$arrFields33 = array();

		$arrFields33[] = 'amount_paid';
		$arrValues33[] = $amt3;
		
		$updateTrans=$objQuery->mysqlUpdate('patient_referal',$arrFields33,$arrValues33,"patient_id='".$pat_id."' and ref_id='".$ref_id3."'");
		
		}
	}
	$arrValues3 = array();
	$arrFields3 = array();

	$arrFields3[] = 'paytm_id';
	$arrValues3[] = $paytm_id;
	$arrFields3[] = 'patient_id';
	$arrValues3[] = $pat_id;
	$arrFields3[] = 'ref_id';
	$arrValues3[] = $ref_id4;
	$arrFields3[] = 'ref_amount';
	$arrValues3[] = $amt4;
	$arrFields3[] = 'status';
	$arrValues3[] = "RECONCILED";
	$arrFields3[] = 'trans_date';
	$arrValues3[] = $Cur_Date;
	if($getResult4==true){	
	$updateTrans=$objQuery->mysqlUpdate('doc_op_payment',$arrFields3,$arrValues3,"patient_id='".$pat_id."' and ref_id='".$ref_id4."'");	
	}else
	{
		if($ref_id4!=0 && $amt4!=""){
		$insertTrans=$objQuery->mysqlInsert('doc_op_payment',$arrFields3,$arrValues3);
		
		$arrValues44 = array();
		$arrFields44 = array();

		$arrFields44[] = 'amount_paid';
		$arrValues44[] = $amt4;
		
		$updateTrans=$objQuery->mysqlUpdate('patient_referal',$arrFields44,$arrValues44,"patient_id='".$pat_id."' and ref_id='".$ref_id4."'");
		
		}
	}
	
	$arrValues55= array();
	$arrFields55 = array();
	$arrFields55[] = 'Pay_status';
	$arrValues55[] = "RECONCILED";
	$updateCustTrans=$objQuery->mysqlUpdate('customer_transaction',$arrFields55,$arrValues55,"Payment_id='".$_POST['Pay_Id']."'");
header('Location:op_payment.php?disp=1')	
}

?>