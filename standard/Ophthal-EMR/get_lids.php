<script src="js/symptoms.js"></script>
<?php
ob_start();
error_reporting(0);
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


$sympid=$_GET['sympid'];
$patientid=$_GET['patientid'];

if(isset($_GET['sympid']) && !empty($_GET['sympid'])){

	$params     = split("-", $_GET['sympid']);

	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();

		$arrFileds_symp[]='lids_name';
		$arrValues_symp[]=$params[0];
		$arrFileds_symp[]='eye_type';
		$arrValues_symp[]='1';
		$arrFileds_symp[]='doc_id';
		$arrValues_symp[]=$admin_id;
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='2';

		$arrFileds_symp_le = array();
		$arrValues_symp_le = array();

		$arrFileds_symp_le[]='lids_name';
		$arrValues_symp_le[]=$params[0];
		$arrFileds_symp_le[]='eye_type';
		$arrValues_symp_le[]='2';
		$arrFileds_symp_le[]='doc_id';
		$arrValues_symp_le[]=$admin_id;
		$arrFileds_symp_le[]='doc_type';
		$arrValues_symp_le[]='2';

		$check_exists_lids = $objQuery->mysqlSelect("*","examination_ophthal_lids","lids_name='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' ","","","","");
		if(COUNT($check_exists_lids)==0){ //To prevent double entry
			$insert_lids=$objQuery->mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
			$symp_id = mysql_insert_id(); //Get Patient Id

			$insert_lids_left=$objQuery->mysqlInsert('examination_ophthal_lids',$arrFileds_symp_le,$arrValues_symp_le);
		}

		//$insert_symptoms=$objQuery->mysqlInsert('examination_ophthal_lids',$arrFileds_symp,$arrValues_symp);
		//$symp_id = mysql_insert_id(); //Get Patient Id
	} else
	{
		$symp_id = $params[0];
	}

		$arrFileds = array();
		$arrValues = array();

		$arrFileds[]='lids';
		$arrValues[]=$symp_id;

		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;

		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;

		$arrFileds[]='doc_type';
		$arrValues[]="2";

		$arrFileds[]='status';
		$arrValues[]="1";

		$arrFileds[]='eye_type';
		$arrValues[]="1";

		//$insert_symptoms=$objQuery->mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
		$check_active_lids = $objQuery->mysqlSelect("*","doc_patient_lids_active","lids='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' and eye_type='1' and patient_id='".$_GET['patientid']."' and status='1'","","","","");
		if(COUNT($check_active_lids)==0){ //To prevent double entry
		$insert_lids=$objQuery->mysqlInsert('doc_patient_lids_active',$arrFileds,$arrValues);
		}
		else {
			$objQuery->mysqlDelete('doc_patient_lids_active',"lids='".$params[0]."' and doc_id='".$admin_id."' and doc_type='2' and eye_type='1' and patient_id='".$_GET['patientid']."' and status='1'");
		}

				/*$check_symp = $objQuery->mysqlSelect("*","examination_ophthal_frequent_lids","lids_id='".$symp_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_type='1'","","","","");
					$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('examination_ophthal_frequent_lids',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"flids_id = '".$check_symp[0]['flids_id']."'");
					}
					else{
						$arrFieldsSYMPFREQ[] = 'lids_id';
						$arrValuesSYMPFREQ[] = $symp_id;
						$arrFieldsSYMPFREQ[] = 'eye_type';
						$arrValuesSYMPFREQ[] = '1';
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "2";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('examination_ophthal_frequent_lids',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);


					} */

}

if(isset($_GET['dellidsid']))
{
	//Delete perticular symptoms from table 'doc_patient_lids_active'
	$objQuery->mysqlDelete('doc_patient_lids_active',"lids_id='".$_GET['dellidsid']."'");
}

$get_all_lids = $objQuery->mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='2' and eye_type='1') or (doc_id='0' and doc_type='0' and eye_type='1') ","lids_id DESC","","","");

?>
						<form method="post" action="my_patient_profile_save.php"  name="frmAddSymp" >

								<?php
								while(list($key, $value) = each($get_all_lids)){
								// echo "<input type='hidden' name='lidsID[]' value='" . $value['lids_id'] . "' /><span class='tag label label-primary m-r'>" . $value['lids_name'] . "<a data-role='remove' class='text-white del_lids m-l' data-lids-id='".$value['lids']."'>x</a></span>";

								$getSelectedLidsRE= $objQuery->mysqlSelect("b.lids_name as lids_name,a.lids as lids,b.lids_id as lids_id","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='2' and a.eye_type='1' and a.status='1' and a.lids='".$value['lids_id']."'","","","","");
								if(COUNT($getSelectedLidsRE)>0 ) {
									echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lids-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."' checked / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
								}
								else {
									echo "<input type='checkbox' style='width: 20px;height: 20px; vertical-align: bottom;margin-right:5px;' class='i-checks m-l get_lids_prior' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lids-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."'  / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";
								}



								//echo "<input type='checkbox'  class='i-checks m-l get_lids_prior' name='" . $value['lids_id'] . "' value='" . $value['lids_id'] . "' data-lids-id='" . $value['lids_id'] . "' data-patient-id='".$patientid."'  / >" . $value['lids_name'] . "</label>&nbsp;&nbsp;&nbsp;&nbsp";

								}
								?>

						</form>
