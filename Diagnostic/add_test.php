<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");



$testid=$_GET['testid'];
$patientid=$_GET['patientid'];
$episodeid=$_GET['episodeid'];

if(isset($testid) && !empty($testid)){

				
				$arrFieldsInvest = array();
				$arrValuesInvest = array();
				
				$arrFieldsInvest[] = 'test_id';
				$arrValuesInvest[] = $testid;
				$arrFieldsInvest[] = 'patient_id';
				$arrValuesInvest[] = $patientid;
				$arrFieldsInvest[] = 'episode_id';
				$arrValuesInvest[] = $episodeid;
				$arrFieldsInvest[] = 'doc_id';
				$arrValuesInvest[] = $admin_id;
				$arrFieldsInvest[] = 'doc_type';
				$arrValuesInvest[] = "1";
				$insert_investigation=mysqlInsert('patient_investigation',$arrFieldsInvest,$arrValuesInvest);				
				
}
?>

								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Test</th>
																				<th>Normal Value</th>
																				<th>Actual Value</th>
																			</thead>
																			<tbody>
																			<?php 
									
																				$get_invest = mysqlSelect("b.sub_test_name as sub_test_name,b.normal_range as normal_range","patient_investigation as a left join diagnosis_sub_tests as b on a.test_id=b.test_id","a.episode_id='".$episodeid."'","","","","");
																				while(list($key, $value) = each($get_invest))	
																				{  ?>
																				<tr>
																				<td><?php echo $value['sub_test_name']; ?></td>
																				<td><?php echo $value['normal_range']; ?></td>
																				<td><input type="text" class="tagName" name=""  value="" placeholder="" style="width:100px;"></td>
																				</tr>
																				<?php }
																				?>
																			</tbody>
																			
																		</table>
					