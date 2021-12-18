<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");


/* 
$timestamps1 = strtotime("2021-08-17 10:30:00");
echo "<br>";
echo(date_default_timezone_get() ." : ". date("Y-d-m T h:i:s a",$timestamps1)). "<br>";
$postResult = mysqlSelect("*","timezones","","id ASC","","","");
foreach($postResult as $postResultList){
	//echo "<br>";
	date_default_timezone_set($postResultList['timezone_names']);
	echo $postResultList['timezone_names']." : ".date("Y-d-m T h:i:s a",$timestamps1). "<br>";
	//echo "<br>";
}  */


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d');
$timestamps1 = strtotime($Cur_Date);

/*
date_default_timezone_set('UTC');
echo "Cur_Date: ".$Cur_Date. "<br>";
echo "UTC timestamps1: ".date("Y-d-m h:i:s A",$timestamps1). "<br>";

echo "Timezone: ".date("h:i A",$timestamps1). "<br>";  */

$postResult = mysqlSelect("*","appointment_slots","","id ASC","","","");
foreach($postResult as $postResultList){
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d');
	$Time = $postResultList['slots'];
	echo "Cur_Date: ".$Cur_Date." ".$Time. "<br>";
	$timestamps1 = strtotime($Cur_Date." ".$Time);
	date_default_timezone_set('UTC');
	echo "UTC timestamps1: ".date("Y-d-m h:i:s A",$timestamps1). "<br>";
	echo "Timezone: ".date("h:i A",$timestamps1). "<br>";
	$utc_slots = date("h:i A",$timestamps1);
	
	$arrFiedInvest=array();
	$arrValueInvest=array();
	
	$arrFiedInvest[]='UTC_slots';
	$arrValueInvest[]=$utc_slots;
		
	
	$update=mysqlUpdate('appointment_slots',$arrFiedInvest,$arrValueInvest, "id = '".$postResultList['id']."'");
	
}

?>