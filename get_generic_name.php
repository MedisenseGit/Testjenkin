<?php
ob_start();
error_reporting(0); 
session_start();


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];
$select1= $objQuery->mysqlSelect("DISTINCT(generic_name) as generic_name,generic_id","analytics_tab","generic_name LIKE '%".$searchTerm."%' and generic_id!=0","generic_name ASC","","","");

if(count($select1)>0){
while (list($key, $value) = each($select1)) 
{
	
	
	$data[] = $value['generic_id']."-".$value['generic_name'];	

	
}	
	
}
//return json data
echo json_encode($data);
?>