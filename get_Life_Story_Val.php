<?php ob_start();
 error_reporting(0);
 session_start(); 


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

	$strId = addslashes($_POST['storyid']);
	$txtDoc = addslashes($_POST['docname']);
	$txtSpec = addslashes($_POST['docspec']);
	$txtCity = $_POST['doccity'];
	$slctState = $_POST['docstate'];
	$txtCountry = $_POST['doccountry'];
	$slctHosp = addslashes($_POST['dochosp']);
	$txtTreated = addslashes($_POST['doctreated']);
	$txtTreatCost = addslashes($_POST['doctreatcost']);
	$txtGend = addslashes($_POST['docgend']);
	$txtAge = addslashes($_POST['docage']);
	$txtYourStory = addslashes($_POST['yourstory']);
	$txtContactName = addslashes($_POST['contname']);
	$txtContactNum = addslashes($_POST['contnum']);
	$txtContactCity = addslashes($_POST['contcity']);
	$contState = addslashes($_POST['contstate']);
	$txtContactCountry = addslashes($_POST['contcountry']);
	
	$anonyState = addslashes($_POST['anonystate']);
	$Timestamp = addslashes($_POST['timestamp']);

	
	$arrFields = array();
	$arrValues = array();
	
	if(!empty($txtDoc) && !empty($txtYourStory) && !empty($txtDoc) && !empty($txtCountry) && !empty($slctState) && !empty($txtCity)){
		
		$arrFields[] = 'docname';
		$arrValues[] = $txtDoc;
		$arrFields[] = 'docspec';
		$arrValues[] = $txtSpec;
		$arrFields[] = 'doccity';
		$arrValues[] = $txtCity;
		$arrFields[] = 'docstate';
		$arrValues[] = $slctState;
		$arrFields[] = 'doccountry';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'hospname';
		$arrValues[] = $slctHosp;		
		$arrFields[] = 'doctreat';
		$arrValues[] = $txtTreated;
		$arrFields[] = 'hospcost';
		$arrValues[] = $txtTreatCost;
		$arrFields[] = 'patgend';
		$arrValues[] = $txtGend;
		$arrFields[] = 'patage';
		$arrValues[] = $txtAge;

		$arrFields[] = 'moreinfo';
		$arrValues[] = $txtYourStory;
		$arrFields[] = 'contname';
		$arrValues[] = $txtContactName;
		$arrFields[] = 'contnum';
		$arrValues[] = $txtContactNum;
		$arrFields[] = 'contcity';
		$arrValues[] = $txtContactCity;
		$arrFields[] = 'contstate';
		$arrValues[] = $contState;
		$arrFields[] = 'contcountry';
		$arrValues[] = $txtContactCountry;
		
		$arrFields[] = 'curdate';
		$arrValues[] = $Timestamp;
	
		$arrFields[] = 'anonymous_status';
		$arrValues[] = $anonyState;
		
		
		$createlifestory=$objQuery->mysqlInsert('best_doc',$arrFields,$arrValues);
			
	} 


?>