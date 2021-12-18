<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];
$select1= mysqlSelect("DISTINCT(pharma_generic) as pharma_generic","pharma_products","pharma_generic LIKE '%".$searchTerm."%'","pharma_generic ASC","","","0,100");

if(count($select1)>0){
while (list($key, $value) = each($select1)) 
{
	
	
	$data[] = $value['pharma_generic'];	

	
}	
	
}
//return json data
echo json_encode($data);
?>