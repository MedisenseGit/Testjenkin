<?php
ob_start();
session_start();
error_reporting(0);  

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

//Prescription Lists
 if(API_KEY == $_POST['API_KEY']) {
 
	$login_type = $_POST['login_type'];	
	$admin_id = $_POST['userid'];
	$searchTerm = $_POST['searchTerm'];
	
	$response["drug_allergy_details"] = array();
	
	if($login_type == 1) {						// Premium LoginType
		
		$select1= $objQuery->mysqlSelect("DISTINCT(pharma_generic) as pharma_generic,generic_id","pharma_products","pharma_generic LIKE '%".$searchTerm."%'","generic_id DESC","","","0,40");
		

		foreach($select1 as $postPharma) {
			$stuff= array();
			$pharma_generic=$postPharma['pharma_generic'];
			$generic_id=$postPharma['generic_id'];
				
			$stuff["generic_id"] = $generic_id;	
			$stuff["pharma_generic"] = $pharma_generic;	
			$stuff["doc_id"] = $admin_id;
			$stuff["doc_type"] = '1';
			
			array_push($response["drug_allergy_details"], $stuff);
		}
		 
		 $response["status"] = "true";
		 echo(json_encode($response));
		
	}
	else {
			$success = array('status' => "false");
			echo json_encode($success);
	}	
		
}


?>