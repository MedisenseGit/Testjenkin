<?php

$host="localhost";
$username="root";
$password="refSqlRef007";
$databasename="medisense_crm";

$connect=mysql_connect($host,$username,$password);
$db=mysql_select_db($databasename);

$searchTerm = $_GET['term'];

$select =mysql_query("SELECT episode_prescription_id,prescription_trade_name FROM doc_patient_episode_prescriptions WHERE prescription_trade_name LIKE '%".$searchTerm."%' LIMIT 50");
while ($row=mysql_fetch_array($select)) 
{
 $data[] = $row['episode_prescription_id']."-".$row['prescription_trade_name'];
}
//return json data
echo json_encode($data);
?>