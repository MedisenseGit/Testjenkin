<?php

$host="localhost";
$username="root";
$password="refSqlRef007";
$databasename="medisense_crm";

$connect=mysql_connect($host,$username,$password);
$db=mysql_select_db($databasename);

$searchTerm = $_GET['term'];

$select =mysql_query("SELECT pincode FROM all_india_postal_code WHERE pincode LIKE '%".$searchTerm."%' LIMIT 50");
while ($row=mysql_fetch_array($select)) 
{
 $data[] = $row['pincode'];
}
//return json data
echo json_encode($data);
?>