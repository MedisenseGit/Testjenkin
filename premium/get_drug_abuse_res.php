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


$drugid=$_GET['drugid'];
$patientid=$_GET['patientid'];

if(isset($_GET['drugid']) && !empty($_GET['drugid'])){
	
	$params     = split("-", $_GET['drugid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_drug = array();
		$arrValues_drug = array();
		
		$arrFileds_drug[]='drug_abuse';
		$arrValues_drug[]=$params[0];
		$arrFileds_drug[]='doc_id';
		$arrValues_drug[]=$admin_id;
		$arrFileds_drug[]='doc_type';
		$arrValues_drug[]='1';
		
		$insert_drugs=$objQuery->mysqlInsert('drug_abuse_auto',$arrFileds_drug,$arrValues_drug);
		$drug_id = $insert_drugs; //Get Patient Id
	} else
	{
		$drug_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='drug_abuse_id';
		$arrValues[]=$drug_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$insert_drug=mysqlInsert('doc_patient_drug_active',$arrFileds,$arrValues);
		
				$check_drug = mysqlSelect("*","doctor_frequent_drug_abuse","drug_abuse_id='".$drug_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_drug[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_drug)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_drug_abuse',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"fda_id = '".$check_drug[0]['fda_id']."'");
					}
					else{
						$arrFieldsSYMPFREQ[] = 'drug_abuse_id';
						$arrValuesSYMPFREQ[] = $drug_id;
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "1";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_drug_abuse',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);
						
						
					}

}

if(isset($_GET['deldrugid']))
{
	//Delete perticular drug from table 'doc_patient_drug_active'
	mysqlDelete('doc_patient_drug_active',"drug_active_id='".$_GET['deldrugid']."'");
}

$getDrugRes= mysqlSelect("b.drug_abuse as drug_abuse,a.drug_active_id as drug_active_id","doc_patient_drug_active as a left join drug_abuse_auto as b on a.drug_abuse_id=b.drug_abuse_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.status='0'","","","","");

?>
							<?php 
								while(list($key, $value) = each($getDrugRes)){ 
									echo "<span class='tag label label-primary m-r'>" . $value['drug_abuse'] . "<a data-role='remove' class='text-white del_drugs m-l' data-drug-abuse-id='".$value['drug_active_id']."'>x</a></span>";
								}
								?>
							

