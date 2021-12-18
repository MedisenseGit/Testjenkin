<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


$tempid=$_GET['tempid'];
$prevprescid=$_GET['prevprescid'];
$patientid=$_GET['patientid'];

if(isset($tempid)){
$getTemplateDetails= mysqlSelect("*","doc_medicine_prescription_template_details","template_id='".$tempid."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFields=array();
			$arrValues=array();
			
			$arrFields[] = 'patient_id';
			$arrValues[] = $patientid;
			$arrFields[] = 'doc_id';
			$arrValues[] = $admin_id ;
			$arrFields[] = 'doc_type';
			$arrValues[] = "1";
			$arrFields[] = 'prescription_trade_name';
			$arrValues[] = $value['prescription_trade_name'];
			$arrFields[] = 'prescription_generic_name';
			$arrValues[] = $value['prescription_generic_name'];
			$arrFields[] = 'prescription_frequency';
			$arrValues[] = $value['prescription_frequency'];
			$arrFields[] = 'prescription_timing';
			$arrValues[] = $value['prescription_timing'];
			$arrFields[] = 'prescription_duration';
			$arrValues[] = $value['prescription_duration'];
			$arrFields[] = 'status';
			$arrValues[] = "1";
			

		$insert_patient=mysqlInsert('doc_medicine_prescription_template_details',$arrFields,$arrValues);
	}
}
else if(isset($prevprescid)){
$getTemplateDetails= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$prevprescid."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
			$arrFields=array();
			$arrValues=array();
			
			$arrFields[] = 'patient_id';
			$arrValues[] = $patientid;
			$arrFields[] = 'doc_id';
			$arrValues[] = $admin_id ;
			$arrFields[] = 'doc_type';
			$arrValues[] = "1";
			$arrFields[] = 'prescription_trade_name';
			$arrValues[] = $value['prescription_trade_name'];
			$arrFields[] = 'prescription_generic_name';
			$arrValues[] = $value['prescription_generic_name'];
			$arrFields[] = 'prescription_frequency';
			$arrValues[] = $value['prescription_frequency'];
			$arrFields[] = 'prescription_timing';
			$arrValues[] = $value['timing'];
			$arrFields[] = 'prescription_duration';
			$arrValues[] = $value['duration'];
			$arrFields[] = 'status';
			$arrValues[] = "1";
			

		$insert_patient=mysqlInsert('doc_medicine_prescription_template_details',$arrFields,$arrValues);
	}

}
	

$getTmplate= mysqlSelect("*","doc_medicine_prescription_template_details","doc_id='".$admin_id."' and patient_id='".$patientid."' and status=1","","","","");
		

?>

								<div class="ibox-content">
										
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patientid; ?>">	
									<a class="btn btn-xs btn-white pull-right" onclick="return deleteAll(<?php echo $admin_id.','.$patientid; ?>)"><i class="fa fa-trash"></i> Clear All</a>
									<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<!--<th>Dosage</th>
																				<th>Route</th>-->
																				<th>Dosage Frequency</th>
																				<th>Timing</th>
																				<th>Duration</th>
																				<!--<th>Note</th>-->
																				<th>Delete</th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { ?>
																			<tr>
																				<td><input type="text" class="tagName" name="prescription_trade_name[]" id="" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:100px;border:none;"></td>
																				<td><input type="text" class="tagName" name="prescription_generic_name[]" id="" value="<?php echo $TempList['prescription_generic_name'];?>" placeholder="Generic Name" style="width:100px;border:none;"></td>
																				<td><input type="text" class="tagName" name="prescription_frequency[]" id="" value="<?php echo $TempList['prescription_frequency'];?>" placeholder="Frequency" style="width:100px;border:none;"></td>
																				<td><input type="text" class="tagName" name="prescription_timing[]" id="" value="<?php echo $TempList['prescription_timing'];?>" placeholder="Timing" style="width:100px;border:none;"></td>
																				<td><input type="text" class="tagName" name="prescription_duration[]" id="" value="<?php echo $TempList['prescription_duration'];?>" placeholder="Duration" style="width:100px;border:none;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td><a href="javascript:void(0)" onclick="return deleteMedicine(<?php echo $TempList['presc_temp_id'];?>,<?php echo $patientid;?>);"><span class="label label-danger">Delete</span></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			<!-- <form method="post" action="send.php"> -->
																		</table>	
									</form>			
							</div>
					