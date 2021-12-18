<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id))
{
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$searchTerm = $_GET['term'];
//$select1= $objQuery->mysqlSelect("pp_id,pharma_brand,pharma_priority,pharma_generic","pharma_products","pharma_brand LIKE '%".$searchTerm."%' or pharma_generic LIKE '%".$searchTerm."%'","pharma_priority DESC","","","0,100");

$select1= mysqlSelect("*","specialization","spec_name LIKE '%". $searchTerm ."%'","","","",""); 

if(count($select1)>0)
{
	while (list($key, $value) = each($select1)) 
	{
		$data[] = $value['spec_name'];

		// if($value['pharma_priority']==1){
		// $data[] = "I-".$value['pp_id']."-".$value['pharma_brand']."-".$value['pharma_generic']."-FDC";
		// }
		// else
		// {
		// $data[] = "I-".$value['pp_id']."-".$value['pharma_brand']."-".$value['pharma_generic'];	
		// }
	}	
} 
//return json data
echo json_encode($data);
?>