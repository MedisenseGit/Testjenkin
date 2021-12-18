<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


$sympid=$_GET['sympid'];
$patientid=$_GET['patientid'];

if(isset($_GET['sympid']) && !empty($_GET['sympid'])){
	
	$params     = explode("-", $_GET['sympid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();
		
		$arrFileds_symp[]='symptoms';
		$arrValues_symp[]=$params[0];
		if(!empty($admin_id)){
			$arrFileds_symp[]='doc_id';
			$arrValues_symp[]=$admin_id;
		}
		
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='1';
		
		$insert_symptoms=mysqlInsert('chief_medical_complaints',$arrFileds_symp,$arrValues_symp);
		$symp_id = $insert_symptoms; //Get Patient Id
	} else
	{
		$symp_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='symptoms';
		$arrValues[]=$symp_id;
		
		if(!empty($patientid))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$patientid;
		}
		
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		
		// if(!empty($admin_id))
		// {
			// $arrFileds_symp[]='doc_id';
			// $arrValues_symp[]=$admin_id;
		// }
							
		
		
		
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="1";
		
		$check_symp_active = mysqlSelect("*","doc_patient_symptoms_active","symptoms='".$symp_id."' and patient_id='".$patientid."' and doc_id='".$admin_id."' and doc_type='1' and status='1'","","","","");
		if(COUNT($check_symp_active)==0){		
		$insert_symptoms=mysqlInsert('doc_patient_symptoms_active',$arrFileds,$arrValues);
		}
				$check_symp = mysqlSelect("*","doctor_frequent_symptoms","symptoms_id='".$symp_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"dfs_id = '".$check_symp[0]['dfs_id']."'");
					}
					else{
						$arrFieldsSYMPFREQ[] = 'symptoms_id';
						$arrValuesSYMPFREQ[] = $symp_id;
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "1";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);
						
						
					}

}
if(isset($_GET['editsympid']) && !empty($_GET['editsympid'])){
	$params     = explode("-", $_GET['editsympid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();
		
		$arrFileds_symp[]='symptoms';
		$arrValues_symp[]=$params[0];
		
		if(!empty($admin_id))
		{
			$arrFileds_symp[]='doc_id';
			$arrValues_symp[]=$admin_id;
		}
		
		
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='1';
		
		$insert_symptoms=mysqlInsert('chief_medical_complaints',$arrFileds_symp,$arrValues_symp);
		$symp_id = $insert_symptoms; //Get Patient Id
	} 
	else
	{
		$symp_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='symptoms';
		$arrValues[]=$symp_id;
							
		if(!empty($patient_id))
		{
			$arrFileds[]='patient_id';
			$arrValues[]=$patient_id;
		}
			
		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;
		}
		if(!empty($_GET['episodeid']))
		{
			$arrFileds[]='episode_id';
			$arrValues[]=$_GET['episodeid'];
		}	
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		$arrFileds[]='status';
		$arrValues[]="0";
		
		$check_symp_active = mysqlSelect("*","doc_patient_symptoms_active","symptoms='".$symp_id."' and patient_id='".$patient_id."' and doc_id='".$admin_id."' and doc_type='1' and episode_id='".$_GET['episodeid']."'","","","","");
		if(COUNT($check_symp_active)==0){		
		$insert_symptoms=mysqlInsert('doc_patient_symptoms_active',$arrFileds,$arrValues);
		}
				$check_symp = mysqlSelect("*","doctor_frequent_symptoms","symptoms_id='".$symp_id."' and doc_id='".$admin_id."' and doc_type='1'","","","","");
					$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=mysqlUpdate('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"dfs_id = '".$check_symp[0]['dfs_id']."'");
					}
					else
					{
						if(!empty($symp_id))
						{
							$arrFieldsSYMPFREQ[] = 'symptoms_id';
							$arrValuesSYMPFREQ[] = $symp_id;
						}
							
						if(!empty($admin_id))
						{
							$arrFieldsSYMPFREQ[] = 'doc_id';
							$arrValuesSYMPFREQ[] = $admin_id;
						}
						
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "1";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=mysqlInsert('doctor_frequent_symptoms',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);
						
						
					}
	
	
}
if(isset($_GET['delsympid']) || isset($_GET['deleditsympid']))
{
	//Delete perticular symptoms from table 'doc_patient_symptoms_active'
	mysqlDelete('doc_patient_symptoms_active',"symptoms_id='".$_GET['delsympid']."' or symptoms_id='".$_GET['deleditsympid']."'");
}


if((isset($_GET['sympid']) && !empty($_GET['sympid'])) || (isset($_GET['delsympid']) && !empty($_GET['delsympid'])))
{
$getSymptom= mysqlSelect("b.symptoms as symptoms,a.symptoms_id as symptoms_id,b.complaint_id as complaint_id","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='1' and a.status='1'","","","","");

 
								while(list($key, $value) = each($getSymptom)){ 
									echo "<input type='hidden' name='symptomID[]' value='" . $value['complaint_id'] . "' /><span class='tag label label-primary m-r'>" . $value['symptoms'] . "<a data-role='remove' class='text-white del_complaints m-l' data-symptom-id='".$value['symptoms_id']."'>x</a></span>";
								}
}

if((isset($_GET['editsympid']) && !empty($_GET['editsympid'])) || (isset($_GET['deleditsympid']) && !empty($_GET['deleditsympid'])))
{
								$getSymptomActive= mysqlSelect("b.symptoms as symptoms,a.symptoms_id as symptoms_id,b.complaint_id as complaint_id","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$_GET['episodeid']."' and a.status='0'","","","","");
								while(list($key, $value) = each($getSymptomActive)){ 
									echo "<input type='hidden' name='symptomID[]' value='" . $value['complaint_id'] . "' /><span class='tag label label-primary m-r'>" . $value['symptoms'] . "<a data-role='remove' class='text-white del_complaints m-l' data-symptom-id='".$value['symptoms_id']."'>x</a></span>";
								}
}
		
?>
						

