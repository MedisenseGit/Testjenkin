<?php
ob_start();
error_reporting(0); 
session_start();


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];
$select1= $objQuery->mysqlSelect("DISTINCT(pharma_generic),pharma_priority","pharma_products","pharma_generic LIKE '%".$searchTerm."%'","pharma_priority DESC","","","0,100");

while (list($key, $value) = each($select1)) 
{
	
 $data[] = $value['pharma_generic'];
	
}	
	

//return json data
echo json_encode($data);
?>