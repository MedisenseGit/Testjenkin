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
$objQuery = new CLSQueryMaker();

if(isset($_GET['pupilid']) && !empty($_GET['pupilid'])){

	$pupilid=$_GET['pupilid'];
	$patientid=$_GET['patientid'];

	$params     = split("-", $_GET['pupilid']);

	if(is_numeric($params[0]) == false){
		$arrFileds_master = array();
		$arrValues_master = array();

		$arrFileds_master[]='pupil_name';
		$arrValues_master[]=$params[0];
		$arrFileds_master[]='doc_id';
		$arrValues_master[]=$admin_id;
		$arrFileds_master[]='doc_type';
		$arrValues_master[]='2';


		$arrFileds = array();
		$arrValues = array();

		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;

		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;

		$arrFileds[]='doc_type';
		$arrValues[]="2";

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

		$check_exists_pupil = $objQuery->mysqlSelect("*","examination_ophthal_pupil","pupil_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' ","","","","");
		if(COUNT($check_exists_pupil)==0){ //To prevent double entry
			$insert_pupil=$objQuery->mysqlInsert('examination_ophthal_pupil',$arrFileds_master,$arrValues_master);
			$pupil_id = mysql_insert_id(); //Get Patient Id

			$arrFileds[]='pupil';
			$arrValues[]=$pupil_id;
			$insert_chamber=$objQuery->mysqlInsert('doc_patient_pupil_active',$arrFileds,$arrValues);

		}


	} else
	{
		$pupil_id = $params[0];
	}

		$arrFileds = array();
		$arrValues = array();

		$arrFileds[]='pupil';
		$arrValues[]=$pupil_id;

		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;

		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;

		$arrFileds[]='doc_type';
		$arrValues[]="2";

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


		// update ophthal pupil table entry for selected eye
		if(isset($_GET['eye_type']) && isset($_GET['pupil_status'])) {
		    if($_GET['eye_type'] == 1) {
			$upd_field = 'right_eye';

			$arrFileds[]='eye_side';
			$arrValues[]="1";

		    } else if($_GET['eye_type'] == 2) {
			$upd_field = 'left_eye';

			$arrFileds[]='eye_side';
			$arrValues[]="2";

		    }
		    $upd_val = $_GET['pupil_status'];
		    $upd_where = "pupil_id=" . $pupil_id;
		    $upd_chamber = $objQuery->mysqlUpdate("examination_ophthal_pupil", array($upd_field), array($upd_val), $upd_where);

			if($upd_val == 0) {
				if($Edit_Session == 1){
				$objQuery->mysqlDelete('doc_patient_pupil_active',"episode_id='".$Episode_Id."' and pupil='".$pupil_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='0'");
				}
				else
				{
				$objQuery->mysqlDelete('doc_patient_pupil_active',"pupil='".$pupil_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='1'");
				}
			}
			else {
				$insert_chambert=$objQuery->mysqlInsert('doc_patient_pupil_active',$arrFileds,$arrValues);
			}

		}

		echo $pupil_id;

	}

?>
