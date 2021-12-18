<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Chief Medical Complaint</h4>
								<div class="input-group">				
								<?php $last_five_complaints = $objQuery->mysqlSelect("symptoms_id","doctor_frequent_symptoms","(doc_id='".$admin_id."' and doc_type='2') or (doc_id='0' and doc_type='0')","freq_count desc","","","5");
								
								if(COUNT($last_five_complaints)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								
								while(list($key_comp, $value_comp) = each($last_five_complaints)){
									$getSymptoms = $objQuery->mysqlSelect("complaint_id,symptoms","chief_medical_complaints","complaint_id='".$value_comp['symptoms_id']."'");
								?>
								
								<a class="btn btn-xs btn-white m-l get_complaints_prior" data-symptom-id="<?php echo $getSymptoms[0]['complaint_id']; ?>" title="<?php echo $getSymptoms[0]['symptoms']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo substr($getSymptoms[0]['symptoms'],0,15); ?></code></a>
								<?php }
								
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Add / Search symptoms here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" id="get_complaints" name="srchSymptoms" value="" class="form-control input-lg searchSymptoms" tabindex="1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
										<div id="sympBefore">
										 <!--<input id="symptags"  class="tagsinput1 form-control input-lg" style="width:100%;"  name="medical_complaint" type="text"   />-->
										
										</div>
								</div>
								<br>
								</div>
								<br>