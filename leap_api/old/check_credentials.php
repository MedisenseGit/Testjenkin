<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


//USER LOGIN
 if(isset($_GET['contact_num'])) {
	// $txtUserName = $_POST['user'];
	// $txtPass = md5($_POST['usr_passwd']);
	 $mobile_num = "9535291621";
    $password = md5("sach123");
	$invetory_report = array();	 
	
	$result = $objQuery->mysqlSelect('*','referal',"contact_num='".$mobile_num."' and doc_password='".$password."'");
	
	//echo "<br />";
	//echo $result[0]['contact_num'];
	// print_r($result);
	
	
	array_push($invetory_report ,array("ref_id"=>$result[0]['ref_id'],"contact_num"=>$result[0]['contact_num'],"doc_password"=>$result[0]['doc_password']));
//	print_r($invetory_report);


				
	echo json_encode(array("medical_report"=>$invetory_report));

	

}


?>