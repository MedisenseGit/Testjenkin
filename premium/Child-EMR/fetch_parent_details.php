<?php
ob_start();
error_reporting(0);
session_start();

//$docid = $_SESSION['user_id'];

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();


if(!empty($_POST["mobileNo"])) {
	$getParentDtl= mysqlSelect('*','parents_tab',"primary_mobile_num='".$_POST["mobileNo"]."'");
  echo json_encode($getParentDtl);
}
?>
