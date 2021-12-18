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

$medicineid=$_GET['medicineid'];

if(isset($_GET['delprescid']))
{
	mysqlDelete('pharma_temp_medicine_invoice',"ptm_id='".$_GET['delprescid']."'");
	
}
if(isset($medicineid) && !empty($medicineid)){

				
		$getMedicine= mysqlSelect("episode_prescription_id,prescription_trade_name,prescription_generic_name","doc_patient_episode_prescriptions","episode_prescription_id='".$medicineid."'","","","","");
			
		
			$arrFields[] = 'pharma_id';
			$arrValues[] = $admin_id;
			$arrFields[] = 'prescription_trade_name';
			$arrValues[] = $getMedicine[0]['prescription_trade_name'];
			$arrFields[] = 'prescription_generic_name';
			$arrValues[] = $getMedicine[0]['prescription_generic_name'];
			
		if(COUNT($getMedicine)>0){
		$insert_patient=mysqlInsert('pharma_temp_medicine_invoice',$arrFields,$arrValues);
		}		
}
?>

																	<table id="medicine-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																			<th>Sr. No.</th>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<th>Qty</th>
																				<th>Price</th>
																				<th>Discount</th>
																				<th>Total</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			<?php 
																			$getPrescription= mysqlSelect("*","pharma_temp_medicine_invoice","pharma_id='".$admin_id."'","ptm_id asc","","","");
																			$i=1;		
																			while(list($key_presc, $value_presc) = each($getPrescription)){?>
																			<tr>
																				<td><?php echo $i; ?></td>
																				<td><?php echo $value_presc['prescription_trade_name']; ?></td>
																				<td><?php echo $value_presc['prescription_generic_name']; ?></td>
																				<td><input type="number" class="oceanIn" name="prescription_qty[<?php echo $key_presc; ?>][val_qty]" id="" value="" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_price[]" id="" value="20.89" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_discount[]" id="" value="0.25" placeholder="" style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_total[]" id="" value="20.64" placeholder="" style="width:100px;"></td>
																				<td><a href="javascript:void(0)" onclick="return deleteTempMedicine(<?php echo $value_presc['ptm_id'];?>);"><span class="label label-danger">Delete</span></a></td>																			
																			</tr>
																			<?php $i++;} ?>
																			</tbody>
																			
																		</table>
					