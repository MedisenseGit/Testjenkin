<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

	
	$txtName = $_GET['name'];
	$txtGen = $_GET['gen']
	$txtMob = $_GET['mob'];

	$txtLoc = $_GET['loc'];
	
	$txtNote2 = $_GET['desc'];
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'pat_name';
		$arrValues[] = $txtName;

		$arrFields[] = 'pat_gen';
		$arrValues[] = $txtGen;

		$arrFields[] = 'pat_mbl';
		$arrValues[] = $txtMob;

		$arrFields[] = 'pat_loc';
		$arrValues[] = $txtLoc;
		
		$arrFields[] = 'pat_desc';
		$arrValues[] = $txtNote2;


		$usercraete=$objQuery->mysqlInsert('pat_master',$arrFields,$arrValues);
		$id = mysql_insert_id();

	

?>