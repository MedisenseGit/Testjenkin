<?php
ob_start();
error_reporting(0);
session_start();

//$docid = $_SESSION['user_id'];

require_once("../classes/querymaker.class.php");

ob_start();


if(!empty($_POST["hosp_id"])) {
	$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_specialization as e on e.doc_id=a.ref_id inner join specialization as d on d.spec_id=e.spec_id","c.hosp_id='".$_POST["hosp_id"]."'","a.ref_name asc","","","");
?>
	<option value="">Select Doctor</option>
<?php $i=30;
	foreach($getDoctor as $DocList) {	
		
?>
		<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" >
			<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
<?php
		 ?>	
<?php   $i++;
	}
}
?>
