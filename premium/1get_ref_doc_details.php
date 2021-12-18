<?php
ob_start();
error_reporting(0);
session_start();
require_once("../classes/querymaker.class.php");

ob_start();


if(!empty($_POST["ref_hosp_id"]))
{
	$getDoctor	= mysqlSelect("a.doc_out_ref_id as Ref_Id,a.doctor_name as Doc_name,b.hospital_name as Hosp_name,c.spec_name as Department","doctor_in_referral as a inner join hospital_in_referral as b on b.hos_out_ref_id=a.ref_hosp_id left join specialization as c on c.spec_id=a.doc_specialization","b.hos_out_ref_id='".$_POST["ref_hosp_id"]."'","a.doctor_name asc","","","");
?>
	<option value="">Select Doctor</option>
<?php $i=30;
	foreach($getDoctor as $DocList) 
	{	
		
?>
		<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" >
			<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
<?php
		 ?>	
<?php   $i++;
	}
}
?>
