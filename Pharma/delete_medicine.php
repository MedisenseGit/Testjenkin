<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


$delprescid=$_GET['delprescid'];
$episodeid=$_GET['episodeid'];

mysqlDelete('doc_patient_episode_prescriptions',"episode_prescription_id='".$delprescid."'");
		

?>
							
								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<th>Qty</th>
																				<th>Price</th>
																				<th>Discount</th>
																				<th>Total</th>
																				<th>Delete</th>
																			</thead>
																			<?php 
																			$getPrescription= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$episodeid."'","prescription_trade_name asc","","","");
												
																			while(list($key_presc, $value_presc) = each($getPrescription)){?>
																			<tr>
																				<td><?php echo $value_presc['prescription_trade_name']; ?></td>
																				<td><?php echo $value_presc['prescription_generic_name']; ?></td>
																				<td><input type="number" class="oceanIn" name="prescription_qty[<?php echo $key_presc; ?>][val_qty]" id="" value="" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_price[]" id="" value="20.89" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_discount[]" id="" value="0.25" placeholder="" style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_total[]" id="" value="20.64" placeholder="" style="width:100px;"></td>
																				<td><a href="javascript:void(0)" onclick="return deleteMedicine(<?php echo $TempList['presc_temp_id'];?>,<?php echo $patientid;?>);"><span class="label label-danger">Delete</span></a></td>																			
																			</tr>
																			<?php } ?>
																			
																		</table>
					