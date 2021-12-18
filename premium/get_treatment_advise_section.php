<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Treatment Advise</h4>
								<div class="col-lg-6">
								<div class="input-group">				
								<?php 
								
								$last_five_treatment = mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","6");
								
								if(COUNT($last_five_treatment)>0) { ?>
								<label>Recently used:  </label>
								<?php 
								
								while(list($key_treat, $value_treat) = each($last_five_treatment)){
									
								?>
								
								<a class="btn btn-xs btn-white m-l get_treatment_prior" data-treatment-id="<?php echo $value_treat['dft_id']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo $value_treat['treatment']; ?></code></a>
								<?php }
								
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Write treatment here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" id="get_treatment_res" name="srchTreat" value="" class="form-control input-lg searchTreatment" tabindex="3">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
										<div id="dispTreatment"></div>
								</div>
								<br>
								</div>
								<div class="col-lg-6">
								<dl>
								 <dt>Treatment Details</dt><br> <dd><textarea class="form-control" id="treatment_details"  name="treatment_details" rows="3"></textarea></dd><br>
								 <dt>Schedule Surgery </dt><br> <dd><div class="col-sm-4">
											<input id="J-demo-05" name="dateadded5" type="text" placeholder="YYYY-MM-DD" value="" class="form-control" />
										</div>
									<script type="text/javascript">
										$('#J-demo-05').dateTimePicker({
											mode: 'dateTime'
										});
									</script>
									<a href="#" data-toggle="modal" data-target="#myModalScheduler">View Calender</a>
									</dd><br>
								</dl>
								<div class="modal inmodal" id="myModalScheduler" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
									<iframe src="<?php echo HOST_URL_PREMIUM ?>Surgery_Scheduler/" width="100%" height="758px"  style="border:none; background:none;"></iframe>
								</div>
								</div>
								</div>
								
								</div>
								
	</div>
								