<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//DOCTOR LOGIN
 if(API_KEY == $_POST['API_KEY'] && isset($_POST['user_id']) && isset($_POST['login_type']) ) {
	 
	 $json = json_decode(file_get_contents('php://input'), true);	//Purpose: Used to read parameters as String not as JSON 	
	 
	$user_id = $_POST['user_id'];
	$login_type = $_POST['login_type'];				// login type 1= Asha Worker, 2 = Parents
	
		
	if($login_type == 1)
	{
		$result_child = $objQuery->mysqlSelect('*','child_tab as a inner join parents_tab as b on a.parent_id=b.parent_id inner join our_partners as c on c.partner_id=a.partner_id',"c.partner_id='".$user_id."'");
		$success_result = array('status' => "true","child_info" => $result_child);
		echo json_encode($success_result);
		
	}
	else if($login_type == 2)
	{
		$result_child = $objQuery->mysqlSelect('*','child_tab as a inner join parents_tab as b on a.parent_id=b.parent_id inner join our_partners as c on c.partner_id=a.partner_id',"c.parent_id='".$user_id."'");
		$success_result = array('status' => "true","child_info" => $result_child);
		echo json_encode($success_result);
	}

	
}


?>