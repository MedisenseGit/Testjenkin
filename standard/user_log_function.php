<?php 

//USER LOG FUNCTIONALITY
function userLog($userid,$usertype,$message,$platform,$action)
{
ob_start();
session_start();
error_reporting(0); 

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$systemIP = $_SERVER['REMOTE_ADDR'];

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

	$arrFields_log[] = 'timestamp';
	$arrValues_log[] = $curDate;
	$arrFields_log[] = 'user_id';
	$arrValues_log[] = $userid;
	$arrFields_log[] = 'user_type';
	$arrValues_log[] = $usertype;
	$arrFields_log[] = 'message';
	$arrValues_log[] = $message;
	$arrFields_log[] = 'platform';
	$arrValues_log[] = $platform;
	$arrFields_log[] = 'action';
	$arrValues_log[] = $action;
	$arrFields_log[] = 'system_ip';
	$arrValues_log[] = $systemIP;
	
	$logcreate=$objQuery->mysqlInsert('log',$arrFields_log,$arrValues_log);
	//return $logcreate;
}
?>