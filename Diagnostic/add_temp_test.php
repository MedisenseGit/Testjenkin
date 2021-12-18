<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$testid=$_GET['testid'];

if(isset($_GET['deltestid']))
{
	mysqlDelete('diagnosis_temp_test_invoice',"dtt_id='".$_GET['deltestid']."'");
	
}
if(isset($testid) && !empty($testid)){

				
		$getTest= mysqlSelect("*","diagnosis_sub_tests","test_id='".$testid."'","","","","");
			
		foreach($getTest as $getTestList)
		{
			$arrFields=array();
			$arrValues=array();
			$arrFields[] = 'diagnostic_id';
			$arrValues[] = $admin_id;
			$arrFields[] = 'diagno_sub_test_id';
			$arrValues[] = $getTestList['main_sub_test_id'];
			$arrFields[] = 'diagno_sub_test_name';
			$arrValues[] = $getTestList['sub_test_name'];
			$arrFields[] = 'normal_range';
			$arrValues[] = $getTestList['normal_range'];
			
		
		$insert_patient=mysqlInsert('diagnosis_temp_test_invoice',$arrFields,$arrValues);
		}		
}
?>

																	<table id="test-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Test</th>
																				<th>Normal Value</th>
																				<th>Actual Value</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			<?php 
									
																				$get_invest = mysqlSelect("*","diagnosis_temp_test_invoice","diagnostic_id='". $admin_id."'","","","","");
																				while(list($key, $value) = each($get_invest))	
																				{  ?>
																				<tr>
																				<td><?php echo $value['diagno_sub_test_name']; ?></td>
																				<td><?php echo $value['normal_range']; ?></td>
																				<td><input type="text" class="tagName" name=""  value="" placeholder="" style="width:100px;"></td>
																				<td><a href="javascript:void(0)" onclick="return deleteTempTest(<?php echo $TempList['presc_temp_id'];?>,<?php echo $patientid;?>);"><span class="label label-danger">Delete</span></a></td>
																				</tr>
																				<?php }
																				?>
																			</tbody>
																			
																		</table>
					