<?php
ob_start();
error_reporting(0);
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


$conid=$_GET['conid'];
$patientid=$_GET['patientid'];

if(isset($_GET['conid']) && !empty($_GET['conid'])){

	$params     = split("-", $_GET['conid']);

	if(is_numeric($params[0]) == false){
		$arrFileds_symp = array();
		$arrValues_symp = array();

		$arrFileds_symp[]='fundus_name';
		$arrValues_symp[]=$params[0];
		$arrFileds_symp[]='eye_side';
		$arrValues_symp[]='2';
		$arrFileds_symp[]='doc_id';
		$arrValues_symp[]=$admin_id;
		$arrFileds_symp[]='doc_type';
		$arrValues_symp[]='2';

		$insert_symptoms=$objQuery->mysqlInsert('examination_ophthal_fundus',$arrFileds_symp,$arrValues_symp);
		$conjuct_id = mysql_insert_id(); //Get Patient Id
	} else
	{
		$conjuct_id = $params[0];
	}

		$arrFileds = array();
		$arrValues = array();

		$arrFileds[]='fundus';
		$arrValues[]=$conjuct_id;

		$arrFileds[]='patient_id';
		$arrValues[]=$patientid;

		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;

		$arrFileds[]='doc_type';
		$arrValues[]="2";

		$arrFileds[]='status';
		$arrValues[]="1";

		$insert_symptoms=$objQuery->mysqlInsert('doc_patient_fundus_active',$arrFileds,$arrValues);

				$check_symp = $objQuery->mysqlSelect("*","examination_ophthal_frequent_fundus","fundus_id='".$conjuct_id."' and doc_id='".$admin_id."' and doc_type='2' and eye_side='2'","","","","");
					$freq_count = $check_symp[0]['freq_count']+1; //Count will increment by one
					$arrFieldsSYMPFREQ = array();
					$arrValuesSYMPFREQ = array();
					if(count($check_symp)>0){
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = $freq_count;
						$update_icd=$objQuery->mysqlUpdate('examination_ophthal_frequent_fundus',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ,"fundus_id = '".$check_symp[0]['fundus_id']."'");
					}
					else{
						$arrFieldsSYMPFREQ[] = 'fundus_id';
						$arrValuesSYMPFREQ[] = $conjuct_id;
						$arrFieldsSYMPFREQ[] = 'eye_side';
						$arrValuesSYMPFREQ[] = '2';
						$arrFieldsSYMPFREQ[] = 'doc_id';
						$arrValuesSYMPFREQ[] = $admin_id;
						$arrFieldsSYMPFREQ[] = 'doc_type';
						$arrValuesSYMPFREQ[] = "2";
						$arrFieldsSYMPFREQ[] = 'freq_count';
						$arrValuesSYMPFREQ[] = "1";
						$insert_freq_symp=$objQuery->mysqlInsert('examination_ophthal_frequent_fundus',$arrFieldsSYMPFREQ,$arrValuesSYMPFREQ);


					}

}

if(isset($_GET['delConid']))
{
	//Delete perticular symptoms from table 'doc_patient_fundus_active'
	$objQuery->mysqlDelete('doc_patient_fundus_active',"fundus_id='".$_GET['delConid']."'");
}

$getConjuct= $objQuery->mysqlSelect("b.fundus_name as fundus_name,a.fundus as fundus,b.fundus_id as fundus_id","doc_patient_fundus_active as a left join examination_ophthal_fundus as b on a.fundus=b.fundus_id","a.doc_id='".$admin_id."' and a.patient_id='".$patientid."' and a.doc_type='2' and a.status='1'","","","","");

?>
						<form method="post" action="my_patient_profile_save.php"  name="frmAddSymp" >

								<?php
								while(list($key, $value) = each($getConjuct)){
									echo "<input type='hidden' name='lidsID[]' value='" . $value['fundus_id'] . "' /><span class='tag label label-primary m-r'>" . $value['fundus_name'] . "<a data-role='remove' class='text-white del_fundusLE m-l' data-fundusLE-id='".$value['fundus']."'>x</a></span>";
								}
								?>

						</form>
