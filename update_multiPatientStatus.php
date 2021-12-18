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
	//REFERED
	if($List['respond_state']==1 && !empty($List['ref_id'])){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "2";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "2";
	}
	//RESPONDED
	if($List['provider_status']==1 && !empty($List['ref_id'])){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "5";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "5";
	}
	//OP CONVERTED
	if($List['provider_status']==2 && !empty($List['ref_id'])){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "8";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "8";
	}
	//P-AWAITING
	if($List['respond_state']==5){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "3";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "3";
	}
	//NOT CONVERTED
	if($List['respond_state']==4 && $List['flag_val']==4){
	$arrFields[] = 'status1';
	$arrValues[] = "2";
	$arrFields[] = 'status2';
	$arrValues[] = "10";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "10";
	}
	//STAGED
	if($List['provider_status']==4 && !empty($List['ref_id'])){
	$arrFields[] = 'status1';
	$arrValues[] = "1";
	$arrFields[] = 'status2';
	$arrValues[] = "7";
	$arrFields[] = 'bucket_status';
	$arrValues[] = "7";
	}
		$update=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_referal_id='".$List['patient_referal_id']."'");
	
				
	}
	
				
	
}

?>

<form method="post" name="sendMail" >
<input type="submit" name="send" />
</form>