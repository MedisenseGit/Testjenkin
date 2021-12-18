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

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

if(isset($_GET['angleid']) && !empty($_GET['angleid'])){
	
	$angleid=$_GET['angleid'];
	$patientid=$_GET['patientid'];
	
	$params     = split("-", $_GET['angleid']);
	
	if(is_numeric($params[0]) == false){
		$arrFileds_master = array();
		$arrValues_master = array();
		
		$arrFileds_master[]='angle_name';
		$arrValues_master[]=$params[0];
		$arrFileds_master[]='doc_id';
		$arrValues_master[]=$admin_id;
		$arrFileds_master[]='doc_type';
		$arrValues_master[]='1';
		
		
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
			$arrFileds_master[]='right_eye';
			$arrValues_master[]='1';
			
			$arrFileds[]='eye_side';
			$arrValues[]="1";
			
		    } else if($_GET['eye_type'] == 2) {
			$arrFileds_master[]='left_eye';
			$arrValues_master[]='1';
			
			$arrFileds[]='eye_side';
			$arrValues[]="2";
		    }
		}
		
		$check_exists_angle = mysqlSelect("*","examination_ophthal_angle","angle_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='1' ","","","","");
		if(COUNT($check_exists_angle)==0){ //To prevent double entry
			$insert_angle	=	mysqlInsert('examination_ophthal_angle',$arrFileds_master,$arrValues_master);
			$angle_id = $insert_angle; //Get Patient Id
			
			$arrFileds[]='angle';
			$arrValues[]=$angle_id;
			$insert_chamber=mysqlInsert('doc_patient_angle_active',$arrFileds,$arrValues);
			
		}

	
	} else
	{
		$angle_id = $params[0];
	}
	
		$arrFileds = array();
		$arrValues = array();
		
		$arrFileds[]='angle';
		$arrValues[]=$angle_id;
							
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
		

		// update ophthal angle table entry for selected eye
		if(isset($_GET['eye_type']) && isset($_GET['angle_status'])) {
		    if($_GET['eye_type'] == 1) {
			$upd_field = 'right_eye';
			
			$arrFileds[]='eye_side';
			$arrValues[]="1";
			
		    } else if($_GET['eye_type'] == 2) {
			$upd_field = 'left_eye';
			
			$arrFileds[]='eye_side';
			$arrValues[]="2";
			
		    }
		    $upd_val = $_GET['angle_status'];
		    $upd_where = "angle_id=" . $angle_id;
		    $upd_angle = mysqlUpdate("examination_ophthal_angle", array($upd_field), array($upd_val), $upd_where);
			
			if($upd_val == 0) {
				if($Edit_Session == 1){
				mysqlDelete('doc_patient_angle_active',"episode_id='".$Episode_Id."' and angle='".$angle_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='0'");
				}
				else
				{
				mysqlDelete('doc_patient_angle_active',"angle='".$angle_id."' and doc_id='".$admin_id."' and doc_type='1' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='1'");
				}
			}
			else {
				$insert_angle=mysqlInsert('doc_patient_angle_active',$arrFileds,$arrValues);
			}
			
		}

		//echo $angle_id;

	}

?>
					
