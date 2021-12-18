<?php
// What is this line below??? Recursive calls to js files will crash the system.
// <script src="js/symptoms.js"></script>
?>
<?php
ob_start();
error_reporting(0); 
session_start();

$Edit_Session = $_SESSION['edit_session']; // If $_SESSION['edit_session'] is '1' then edit process will active
$Episode_Id = $_SESSION['episode_id'];
//echo $Edit_Session."<br>";
//echo $Episode_Id."<br>";
$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

if(isset($_GET['lidsid']) && !empty($_GET['lidsid']))
{
	
	$lidsid=$_GET['lidsid'];
	$patientid=$_GET['patientid'];
	
	$params     = split("-", $_GET['lidsid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();
		
		$arrFileds_symp[]='lids_name';
		$arrValues_symp[]=$params[0];
		//$arrFileds_symp[]='eye_type';
		//$arrValues_symp[]='1';
		$arrFileds_symp[]='doc_id';
		$arrValues_symp[]=$admin_id;
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='1';
		
		
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		if($Edit_Session == 1)
		{
			$arrFileds[]='status';
			$arrValues[]="0";
			$arrFileds[]='episode_id';
			$arrValues[]=$Episode_Id;
		}
		else
		{
			$arrFileds[]='status';
			$arrValues[]="1";
		}
		
		
		if(isset($_GET['eye_type'])) {
		    if($_GET['eye_type'] == 1) {
			$arrFileds_symp[]='right_eye';
			$arrValues_symp[]='1';
			
			$arrFileds[]='eye_type';
			$arrValues[]="1";
			
		    } else if($_GET['eye_type'] == 2) {
			$arrFileds_symp[]='left_eye';
			$arrValues_symp[]='1';
			
			$arrFileds[]='eye_type';
			$arrValues[]="2";
		    }
		}
		
		$check_exists_lids = mysqlSelect("*","examination_ophthal_lids","lids_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
		if(COUNT($check_exists_lids)==0){ //To prevent double entry
			$insert_lids=mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
			$lids_id = $insert_lids;
			
			$arrFileds[]='lids';
			$arrValues[]=$lids_id;
			$insert_lids=mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
			
		}

	
	} else
	{
		$lids_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='lids';
		$arrValues[]=$lids_id;
							
		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;
		
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;
		
		$arrFileds[]='doc_type';
		$arrValues[]="1";
		
		if($Edit_Session == 1)
		{
			$arrFileds[]='status';
			$arrValues[]="0";
			$arrFileds[]='episode_id';
			$arrValues[]=$Episode_Id;
		}
		else
		{
			$arrFileds[]='status';
			$arrValues[]="1";
		}
		
		
	
		// update ophthal lids table entry for selected eye
		if(isset($_GET['eye_type']) && isset($_GET['lid_status'])) {
		    if($_GET['eye_type'] == 1) {
			$upd_field = 'right_eye';
			
			$arrFileds[]='eye_type';
			$arrValues[]="1";
			
		    } else if($_GET['eye_type'] == 2) {
			$upd_field = 'left_eye';
			
			$arrFileds[]='eye_type';
			$arrValues[]="2";
			
		    }
		    $upd_val = $_GET['lid_status'];
		    $upd_where = "lids_id=" . $lids_id;
		    $upd_lid = mysqlUpdate("examination_ophthal_lids", array($upd_field), array($upd_val), $upd_where);
			
			if($upd_val == 0) {
				if($Edit_Session == 1){
				mysqlDelete('doc_patient_lids_active',"episode_id='".$Episode_Id."' and lids='".$lids_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='0'");
				}
				else
				{
				mysqlDelete('doc_patient_lids_active',"lids='".$lids_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='1'");
				}
			}
			else {
				$insert_lids=mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
			}
			
			
		
		}

		//echo $lids_id;

}

if(isset($_GET['lidsidLE']) && !empty($_GET['lidsidLE']))
	{
	
	$lidsidLE=$_GET['lidsidLE'];
	$patientid=$_GET['patientid'];
	
	$params1     = split("-", $_GET['lidsidLE']);
	
	if(is_numeric($params1[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();
		
		$arrFileds_symp[]='lids_name';
		$arrValues_symp[]=$params1[0];
		$arrFileds_symp[]='eye_type';
		$arrValues_symp[]='2';
		$arrFileds_symp[]='doc_id';
		$arrValues_symp[]=$admin_id;
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='1';
		
		
		$check_exists_lids = mysqlSelect("*","examination_ophthal_lids","lids_name='".$params1[0]."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
		if(COUNT($check_exists_lids)==0){ //To prevent double entry
			$insert_lids=mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
			$lidsLE_id = $insert_lids;
			
		}
	
	} else
	{
		$lidsLE_id = $params1[0];
	}
	
		$arrFileds_LE = array();
		$arrValues_LE = array();
		
		$arrFileds_LE[]='lids';
		$arrValues_LE[]=$lidsLE_id;
							
		$arrFileds_LE[]='patient_id';
		$arrValues_LE[]=$patientid;
		
		$arrFileds_LE[]='doc_id';
		$arrValues_LE[]=$admin_id;
		
		$arrFileds_LE[]='doc_type';
		$arrValues_LE[]="1";
		
		if($Edit_Session == 1)
		{
			$arrFileds_LE[]='status';
			$arrValues_LE[]="0";
			$arrFileds_LE[]='episode_id';
			$arrValues_LE[]=$Episode_Id;
		}
		else
		{
			$arrFileds_LE[]='status';
			$arrValues_LE[]="1";
		}
		
		
		
		$arrFileds_LE[]='eye_type';
		$arrValues_LE[]="2";
		
		$check_active_lidsLE = mysqlSelect("lids_id","doc_patient_lids_active","lids='".$params1[0]."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='2' and patient_id='".$_GET['patientid']."' and status='1'","","","","");
		//print_r("param val");
		//print_r($params1[0]);
			//print_r("count num");
		//print_r($check_active_lidsLE[0]['LID_COUNT']);
		if(COUNT($check_active_lidsLE)==0 || $Edit_Session == 1){ //To prevent double entry
			$insert_lidsLE=mysqlInsert('doc_patient_lids_active',$arrFileds_LE,$arrValues_LE);
		}
		else {
			if($Edit_Session == 1)
			{
			mysqlDelete('doc_patient_lids_active',"episode_id='".$Episode_Id."' and lids='".$params1[0]."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='2' and patient_id='".$_GET['patientid']."' and status='0'");
			}
			else
			{
			mysqlDelete('doc_patient_lids_active',"lids='".$params1[0]."' and doc_id='".$admin_id."' and doc_type='1' and eye_type='2' and patient_id='".$_GET['patientid']."' and status='1'");
			}
		}	
 
	}
?>
					
