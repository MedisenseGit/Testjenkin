<?php
ob_start();
session_start();
error_reporting(0);  

$admin_id = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();	
$getPayList= mysqlSelect("*","out_patient_billing","doc_id='".$admin_id."' and billing_id='".$_POST['billing_id']."'","","","","");
				


echo $getPayList[0]['amount'];


?>