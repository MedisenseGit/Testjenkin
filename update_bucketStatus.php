<?php ob_start();
 error_reporting(0);
 session_start(); 

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

//TO CHECK COMPANY USER WORK ASSIGN STATUS
if(isset($_POST['send'])){
	
	$getValue = $objQuery->mysqlSelect("*","patient_referal","","","","","");

	foreach($getValue as $List) {
		$arrValues = array();
		$arrFields = array();

	//NOT CONVERTED
	if($List['ref_id']==0 && $List['provider_status']==0 && $List['flag_val']==4){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "10";
	}

		$update=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_referal_id='".$List['patient_referal_id']."'");
	
				
	}
	
				
	
}

?>

<form method="post" name="sendMail" >
<input type="submit" name="send" />
</form>