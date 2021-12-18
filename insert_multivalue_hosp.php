<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

//TO CHECK COMPANY USER WORK ASSIGN STATUS
if(isset($_POST['send'])){
	
	$chkPatInfo = $objQuery->mysqlSelect("*","referal","ref_id between 1058 and 1094","","","","");

	foreach($chkPatInfo as $patList) {
		$arrValues = array();
		$arrFields = array();
	
		$arrFields[] = 'doc_id';
		$arrValues[] = $patList['ref_id'];
		$arrFields[] = 'hosp_id';
		$arrValues[] = "212";
		$usercraete=$objQuery->mysqlInsert('doctor_hosp',$arrFields,$arrValues);
				
	}
	
				
	
}

?>

<form method="post" name="sendMail" >
<input type="submit" name="send" />
</form>