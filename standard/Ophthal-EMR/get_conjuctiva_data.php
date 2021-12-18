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

if(isset($_GET['conjuctivaid']) && !empty($_GET['conjuctivaid'])){

	$conjuctivaid=$_GET['conjuctivaid'];
	$patientid=$_GET['patientid'];

	$params     = split("-", $_GET['conjuctivaid']);

	if(is_numeric($params[0]) == false){
		$arrFileds_conjuctiva = array();
		$arrValues_conjuctiva = array();

		$arrFileds_conjuctiva[]='conjuctiva_name';
		$arrValues_conjuctiva[]=$params[0];
		$arrFileds_conjuctiva[]='doc_id';
		$arrValues_conjuctiva[]=$admin_id;
		$arrFileds_conjuctiva[]='doc_type';
		$arrValues_conjuctiva[]='2';


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
			$arrFileds_conjuctiva[]='right_eye';
			$arrValues_conjuctiva[]='1';

			$arrFileds[]='eye_side';
			$arrValues[]="1";

		    } else if($_GET['eye_type'] == 2) {
			$arrFileds_conjuctiva[]='left_eye';
			$arrValues_conjuctiva[]='1';

			$arrFileds[]='eye_side';
			$arrValues[]="2";
		    }
		}

		$check_exists_conjuctiva = $objQuery->mysqlSelect("*","examination_ophthal_conjuctiva","conjuctiva_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' ","","","","");
		if(COUNT($check_exists_conjuctiva)==0){ //To prevent double entry
			$insert_conjuctiva=$objQuery->mysqlInsert('examination_ophthal_conjuctiva',$arrFileds_conjuctiva,$arrValues_conjuctiva);
			$conjuctiva_id = mysql_insert_id(); //Get Patient Id

			$arrFileds[]='conjuctiva';
			$arrValues[]=$conjuctiva_id;
			$insert_lids=$objQuery->mysqlInsert('doc_patient_conjuctiva_active',$arrFileds,$arrValues);

		}


	} else
	{
		$conjuctiva_id = $params[0];
	}

		$arrFileds = array();
		$arrValues = array();

		$arrFileds[]='conjuctiva';
		$arrValues[]=$conjuctiva_id;

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


		// update ophthal lids table entry for selected eye
		if(isset($_GET['eye_type']) && isset($_GET['conjuctiva_status'])) {
		    if($_GET['eye_type'] == 1) {
			$upd_field = 'right_eye';

			$arrFileds[]='eye_side';
			$arrValues[]="1";

		    } else if($_GET['eye_type'] == 2) {
			$upd_field = 'left_eye';

			$arrFileds[]='eye_side';
			$arrValues[]="2";

		    }
		    $upd_val = $_GET['conjuctiva_status'];
		    $upd_where = "conjuctiva_id=" . $conjuctiva_id;
		    $upd_lid = $objQuery->mysqlUpdate("examination_ophthal_conjuctiva", array($upd_field), array($upd_val), $upd_where);

			if($upd_val == 0) {
				if($Edit_Session == 1){
				$objQuery->mysqlDelete('doc_patient_conjuctiva_active',"episode_id='".$Episode_Id."' and conjuctiva='".$conjuctiva_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='0'");
				}
				else
				{
				$objQuery->mysqlDelete('doc_patient_conjuctiva_active',"conjuctiva='".$conjuctiva_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_side='".$_GET['eye_type']."' and patient_id='".$_GET['patientid']."' and status='1'");
				}
			}
			else {
				$insert_lids=$objQuery->mysqlInsert('doc_patient_conjuctiva_active',$arrFileds,$arrValues);
			}

		}

		//echo $conjuctiva_id;

	}

?>
