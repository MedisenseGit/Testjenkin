<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//State Lists
 if(API_KEY == $_POST['API_KEY'] ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
		
	$get_States = $objQuery->mysqlSelect('b.name as StateName','countries_tab as a left join states_tab as b on a.id=b.country_id',"a.sortname='".$_POST['shortname']."'","","","","");
	$success = array('status' => "true","page_result" => $get_States);
	echo json_encode($success);
	
}


?>