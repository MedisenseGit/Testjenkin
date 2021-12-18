<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();



$patientid=$_GET['patientid'];

if(isset($_GET['historyid']) && !empty($_GET['historyid'])){
	
	$params     = split("-", $_GET['historyid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_drug = array();
		$arrValues_drug = array();
		
		$arrFileds_drug[]='family_history';
		$arrValues_drug[]=$params[0];
		$arrFileds_drug[]='doc_id';
		$arrValues_drug[]=$admin_id;
		$arrFileds_drug[]='doc_type';
		$arrValues_drug[]='1';
		
		$insert_drugs=mysqlInsert('family_history_auto',$arrFileds_drug,$arrValues_drug);
		$history_id = $insert_drugs; //Get Patient Id
	} else
	{
		$history_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='family_history_id';
		$arrValues[]=$history_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$insert_drug=mysqlInsert('doc_patient_family_history_active',$arrFileds,$arrValues);
		
				$check_history = mysqlSelect("*","doctor_frequent_family_history","family_history_id='".$history_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_history[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_history)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_family_history',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"ffh_id = '".$check_history[0]['ffh_id']."'");
					}
					else{
						$arrFieldsSYMPFREQ[] = 'family_history_id';
						$arrValuesSYMPFREQ[] = $history_id;
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "1";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_family_history',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);
						
						
					}

}

if(isset($_GET['delhistoryid']))
{
	//Delete perticular drug from table 'doc_patient_family_history_active'
	mysqlDelete('doc_patient_family_history_active',"family_active_id='".$_GET['delhistoryid']."'");
}

$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.status='0'","","","","");

?>
							<?php 
								while(list($key, $value) = each($getHistoryRes)){ 
									echo "<span class='tag label label-primary m-r'>" . $value['family_history'] . "<a data-role='remove' class='text-white del_history m-l' data-history-id='".$value['family_active_id']."'>x</a></span>";
								}
								?>
							

