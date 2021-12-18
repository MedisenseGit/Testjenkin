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

$delprescid=$_GET['delprescid'];
$patientid=$_GET['patientid'];
if(isset($delprescid) && !empty($delprescid)){
mysqlDelete('doc_medicine_prescription_template_details',"presc_temp_id='".$delprescid."'");

$getTmplate= mysqlSelect("*","doc_medicine_prescription_template_details","doc_id='".$admin_id."' and patient_id='".$patientid."' and status=1","","","","");
		

?>
							
								<div class="ibox-content">
										
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patientid; ?>">	
									<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<!--<th>Dosage</th>
																				<th>Route</th>-->
																				<th>Frequency</th>
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
							<?php }
							else if(isset($_GET['delall']) && !empty($_GET['delall'])){ //Delete all selected medicine
								
							mysqlDelete('doc_medicine_prescription_template_details',"patient_id='".$_GET['patid']."' and doc_id='".$_GET['docid']."' and status='1'");
								?> 
							<div class="ibox-content">	
							<table id="employee-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<th>Frequency</th>
																				<th>Timing</th>
																				<th>Duration</th>
																				<!--<th>Note</th>-->
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			</tbody>
																			
																		</table>
																</div>		
																 <!-- Custom Theme Scripts -->
																<script src="../assets/js/custom.min.js"></script>		
																	<script>
													$( document ).ready(function() {
														//var prescription_seq = parseInt('<?php echo $prescription_seq ?>');

														function addPrescriptionTr() {
															var prescription_seq = parseInt($('#hid_prescription_seq').val());

															prescription_seq = (prescription_seq + 1);
															var new_prescription_tr = '<tr class="link1" id="prescription_del_'+ prescription_seq +'_row">';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="tagName" name="prescription_trade_name['+ prescription_seq +']" id="prescription_trade_name_'+ prescription_seq +'" placeholder="Trade" style="width:100px;border:none;">';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="genericName" name="prescription_generic_name['+ prescription_seq +']" id="prescription_generic_name_'+ prescription_seq +'" placeholder="Generic" style="width:100px;border:none;">';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="frequency" name="prescription_frequency['+ prescription_seq +']" id="prescription_frequency_'+ prescription_seq +'" placeholder="Freq" style="width:100px;border:none;"></textarea>';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="timing" name="prescription_timing['+ prescription_seq +']" id="prescription_timing_'+ prescription_seq +'" placeholder="Timing" style="width:80px;border:none;">';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="duration" name="prescription_duration['+ prescription_seq +']" id="prescription_duration_'+ prescription_seq +'" placeholder="Duration" style="width:80px;border:none;">';
																new_prescription_tr +=  '</td>';
																/*new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="dosageName expandwidth" name="prescription_dosage_name['+ prescription_seq +']" id="prescription_dosage_name_'+ prescription_seq +'" placeholder="Dosage" style="width:80px;border:none;">';
																new_prescription_tr +=  '</td>';
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<input type="text" class="route expandwidth" name="prescription_route['+ prescription_seq +']" id="prescription_route_'+ prescription_seq +'" placeholder="Route" style="width:100px;border:none;">';
																
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<textarea name="prescription_instruction['+ prescription_seq +']" id="prescription_instruction_'+ prescription_seq +'" placeholder="Note" style="width:100px;border:none;"></textarea>';
																new_prescription_tr +=  '</td>';*/
																new_prescription_tr +=  '<td class="fields">';
																	new_prescription_tr +=  '<button id="prescription_del_'+ prescription_seq +'" class="btn btn-sm btn-danger pull-right m-xs delbutton"><i class="fa fa-trash"></i> Delete</button>';
																new_prescription_tr +=  '</td>';
															new_prescription_tr +=  '</tr>';

															$('#hid_prescription_seq').val(prescription_seq);

															$( "#employee-grid" ).append( new_prescription_tr );

														
															$(".delbutton").click(function() {
																var del_id = $(this).attr("id");
																if (confirm("Sure you want to delete this post? This cannot be undone later.")) {
																	$("#"+del_id+"_row").remove();
																}
															});
															

															var tradeName = [ <?php echo '"' . implode ('","', $arrTradeName) . '"'; ?> ];

															$( ".tagName" ).autocomplete({
															  source: tradeName
															});

															var genericName = [ <?php echo '"' . implode ('","', $arrGenericName) . '"'; ?> ];
															$( ".genericName" ).autocomplete({
															  source: genericName
															});
															
															/*var dosageName = [ <?php echo '"' . implode ('","', $arrDosageName) . '"'; ?> ];
															$( ".dosageName" ).autocomplete({
															  source: dosageName
															});

															 var route = [ <?php echo '"' . implode ('","', $arrRoute) . '"'; ?> ];
															$( ".route" ).autocomplete({
															  source: route
															});*/

															var frequency = [ <?php echo '"' . implode ('","', $arrFrequency) . '"'; ?> ];
															$( ".frequency" ).autocomplete({
															  source: frequency
															});
															
															var timing = [ <?php echo '"' . implode ('","', $arrTiming) . '"'; ?> ];
															$( ".timing" ).autocomplete({
															  source: timing
															});
															
															var duration = [ <?php echo '"' . implode ('","', $arrDuration) . '"'; ?> ];
															$( ".duration" ).autocomplete({
															  source: duration
															});

															$('.expandwidth').focus(function()
															{
																/*to make this flexible, I'm storing the current width in an attribute*/
																$(this).attr('data-default', $(this).width());
																$(this).animate({ width: 250 }, 'slow');
															}).blur(function()
															{
																/* lookup the original width */
																var w = $(this).attr('data-default');
																$(this).animate({ width: w }, 'slow');
															});
														}

														$('.addTr').click(function() {
															addPrescriptionTr();
														});

														addPrescriptionTr();

														$('#chkSaveTemplate').click(function() {
															$("#template_name").val('');
															$("#template_name").toggle();
														});

													});

													function loadPrescriptionTemplate(template_id)
													{
														var delay = 1000;
														var prescription_seq = $('#hid_prescription_seq').val();
														//alert(template_id);
														$.ajax({
															type: "POST",
															url: "my_patient_prescription_template.php",
															data:{"template_id":template_id, prescription_seq: prescription_seq},
															success: function(data) {
																setTimeout(function() {
																  delaySuccess(data);
																}, delay);
															  }
															/*
															success: function(data){
																//$("#slctState").html(data);
																alert("aa");
																$('#employee-grid tbody').append(data, function () {
																	alert("a");
																});
																$('#employee-grid tbody').html(data);
																setTimeout(continueExecution, 10000)
																alert("bb");


															}
															*/
														});
													}

													function delaySuccess(data) {
														$('#employee-grid tbody').append(data);
														$(".delbutton").click(function() {
															var del_id = $(this).attr("id");
															if (confirm("Sure you want to delete this post? This cannot be undone later.")) {
																$("#"+del_id+"_row").remove();
															}
														});
														var prescription_seq = $('#employee-grid tbody tr').length;
														$('#hid_prescription_seq').val(prescription_seq);

														$('.expandwidth').focus(function()
														{
															/*to make this flexible, I'm storing the current width in an attribute*/
															$(this).attr('data-default', $(this).width());
															$(this).animate({ width: 250 }, 'slow');
														}).blur(function()
														{
															/* lookup the original width */
															var w = $(this).attr('data-default');
															$(this).animate({ width: w }, 'slow');
														});
													}
												</script>
											<?php } ?>