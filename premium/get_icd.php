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
$select1	= mysqlSelect("pp_id,pharma_brand,pharma_priority,pharma_generic","pharma_products","pharma_brand LIKE '%".$searchTerm."%' or pharma_generic LIKE '%".$searchTerm."%'","pharma_priority DESC","","","0,100");

if(count($select1)>0)
{
	while (list($key, $value) = each($select1)) 
	{
		
		if($value['pharma_priority']==1)
		{
			$data[] = "I-".$value['pp_id']."-".$value['pharma_brand']."-".$value['pharma_generic']."-FDC";
		}
		else
		{
			$data[] = "I-".$value['pp_id']."-".$value['pharma_brand']."-".$value['pharma_generic'];	
		}
	}	
	
}
else
{
	$select2= mysqlSelect("freq_medicine_id,med_trade_name,med_generic_name","doctor_frequent_medicine","(med_trade_name LIKE '%".$searchTerm."%' or med_generic_name LIKE '%".$searchTerm."%') and doc_id='".$admin_id."' and doc_type = '1'","freq_count DESC","","","0,20");
	
	while (list($key, $value) = each($select2)) 
	{
		$data[] = $value['freq_medicine_id']."-".$value['med_trade_name'];
	}
}
//return json data
echo json_encode($data);
?>