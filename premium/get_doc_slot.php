<?php
ob_start();
error_reporting(0);
session_start();
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
ob_start();

$ip = $_SERVER['REMOTE_ADDR']; // find time zone
//echo $ip;
$ipInfo = file_get_contents('http://ip-api.com/json/' .$ip);
$ipInfo = json_decode($ipInfo);
$timezone = $ipInfo->timezone;
date_default_timezone_set($timezone);
if(empty($timezone))
{
	$timezone ='Asia/Kolkata'; // This for localhost
}
$Cur_Date	=	date('H:i');
$Cur_Date1 	=	date("Y/m/d");
$selec_date = $_POST['selec_date'];
$str="";
//echo $_POST["doc_id"];
if(isset($_POST["doc_id"]))
{
	//echo"hjhhjh";
	$doc_id		=	$_POST["doc_id"];
	$hosp_id	=	$_POST["hosp_id"];
	$day		=	$_POST["day"];
	$selec_date	=	$_POST["selec_date"];
	
	$getday_id 	= mysqlSelect("day_id","seven_days","da_name='".$day."'","","","","");
	
	$GetTimeSlot	=	mysqlSelect("a.id as id,b.time_id AS time_id,a.utc_slots as utc_slots,a.categoty as categoty ","appointment_utc_slots AS a INNER JOIN doctor_appointment_slots_set AS b ON a.id = b.time_id","b.doc_id='".$doc_id."' and b.hosp_id='".$hosp_id."' AND b.day_id = '".$getday_id[0]['day_id']."'","","","","");	
	$str=$str.'<div class="row well well-lg">';
	$str=$str."<div class='scrolling-wrapper'>";
	$str=$str."<div style='text-align:left;'><i class='fa fa-sun' style='color:#4d72d0;'></i> <b>Morning</b>  slots </div><br>";
	$str=$str."<div class='row no guttors'>";
	//var_dump($GetTimeSlot);
	foreach($GetTimeSlot as $TimeSlot)
	{ 
		$utc_slot = $TimeSlot['utc_slots'];
		$UTCObj = new DateTime($utc_slot, new DateTimeZone("UTC"));
		$utc_time ='"'.$UTCObj->format("g:i A").'"';
		
		$LocalObj = $UTCObj;
		$LocalObj->setTimezone(new DateTimeZone($timezone));
		
		$categoty = $TimeSlot['categoty'];
		$timeId = 	$TimeSlot['time_id'];
		$dtA 	=	$Cur_Date;
		$dtB 	=	$LocalObj->format("H:i");
		
		if($Cur_Date1 == $selec_date) 
		{
			
			if( ($dtB>="00:00" && $dtB<="11:45" ) && (strtotime($dtA) <= strtotime($dtB)) )
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}
		}
		else
		{
			
			if(($dtB>="00:00" && $dtB<="11:45" ))
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}

		}	
		
	}
	
	$str=$str."</div>";
	
	$str=$str."<hr>";
	$str=$str."<div style='text-align:left;'><i class='fa fa-sun' style='color:#4d72d0;'></i><b> Afternoon</b> slots </div> <br>";
	$str=$str."<div class='row'>";
	
	foreach($GetTimeSlot as $TimeSlot)
	{ 
		$utc_slot = $TimeSlot['utc_slots'];
		$categoty = $TimeSlot['categoty'];
		$timeId = 	$TimeSlot['time_id'];
		
		$UTCObj = new DateTime($utc_slot, new DateTimeZone("UTC"));
		$utc_time ='"'.$UTCObj->format("g:i A").'"';
		
		$LocalObj = $UTCObj;
		$LocalObj->setTimezone(new DateTimeZone($timezone));
		
		$dtA 	=	$Cur_Date;
		$dtB 	=	$LocalObj->format("H:i");
		if($Cur_Date1 == $selec_date) 
		{
			if(($dtB>="13:00" && $dtB<="15:45" ) && (strtotime($dtA) <= strtotime($dtB)) )
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}
			
		}
		else
		{
			
			if(($dtB>="13:00" && $dtB<="15:45" ) )
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}
		}
	}
	$str=$str."</div>";
	
	$str=$str."<hr>";
	$str=$str."<div style='text-align:left;'><i class='fa fa-moon' style='color:#4d72d0;'></i><b>Evening</b> slots  </div> <br>";
	$str=$str."<div class='row'>";
	foreach($GetTimeSlot as $TimeSlot)
	{ 
		$utc_slot = $TimeSlot['utc_slots'];
		$categoty = $TimeSlot['categoty'];
		$timeId = 	$TimeSlot['time_id'];
		$UTCObj = new DateTime($utc_slot, new DateTimeZone("UTC"));
		$utc_time ='"'.$UTCObj->format("g:i A").'"';
		$LocalObj = $UTCObj;
		$LocalObj->setTimezone(new DateTimeZone($timezone));
		$dtA 	=	$Cur_Date;
		$dtB 	=	$LocalObj->format("H:i");
		
		if($Cur_Date1 == $selec_date) 
		{
			if(($dtB>="16:00" && $dtB<="19:45") && (strtotime($dtA) <= strtotime($dtB)) )
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' > </div>";
			}
		}
		else
		{
			if(($dtB>="16:00" && $dtB<="19:45") )
			{
				$str=$str."<div class='col-lg-2'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 80px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' > </div>";
			}

		}		
	}
	$str=$str."</div>";
	
	$str=$str."<hr>";
	
	$str=$str."<div style='text-align:left;'><i class='fa fa-moon' style='color:#4d72d0;'></i><b> Night</b> slots </div>  <br>";
	$str=$str."<div class='row'>";
	foreach($GetTimeSlot as $TimeSlot)
	{ 
		$utc_slot = $TimeSlot['utc_slots'];
		$categoty = $TimeSlot['categoty'];
		$timeId = 	$TimeSlot['time_id'];
		$UTCObj = new DateTime($utc_slot, new DateTimeZone("UTC"));
		$utc_time ='"'.$UTCObj->format("g:i A").'"';
		$LocalObj = $UTCObj;
		$LocalObj->setTimezone(new DateTimeZone($timezone));
	
		$dtA 	=	$Cur_Date;
		$dtB 	=	$LocalObj->format("H:i");
		if($Cur_Date1 == $selec_date) 
		{
			if(($dtB>="20:00" && $dtB<="23:45") && (strtotime($dtA) <= strtotime($dtB)) )
			{
				$str=$str."<div class='col-lg-3'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 100px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}
		}
		else
		{
			if((($dtB>="20:00" && $dtB<="23:45")) )
			{
				
				$str=$str."<div class='col-lg-3'><input type='text' id='utc_slots' name='utc_slots' value='".$LocalObj->format("g:i A")."' style='border-radius:5px;background-color:rgba(0, 0, 0, .03); border:solid 1px;padding:2px;margin-bottom:5px;width: 100px;' onclick='getSlotFun(this.value,$timeId,$utc_time);' ></div>";
			}
		}			
	}
	$str=$str."</div>";
	$str=$str."</div>";
	$str=$str."</div>";
	echo $str;
	
												
											
}

?>
