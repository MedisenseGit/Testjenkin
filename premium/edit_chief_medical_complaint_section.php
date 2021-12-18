<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Chief Medical Complaint</h4>
								<div class="input-group">				
								<?php $last_five_complaints = mysqlSelect("symptoms_id","doctor_frequent_symptoms","(doc_id='".$admin_id."' and doc_type='1') or (emr_type='".$getDocEMR[0]['spec_group_id']."' and doc_id='0' and doc_type='0')","freq_count desc","","","5");
								
								if(COUNT($last_five_complaints)>0) { ?>
								<label>Recently used:  </label>
								<?php 
								
								while(list($key_comp, $value_comp) = each($last_five_complaints)){
									$getSymptoms = mysqlSelect("complaint_id,symptoms","chief_medical_complaints","complaint_id='".$value_comp['symptoms_id']."'");
								?>
								
								<a class="btn btn-xs btn-white m-l get_edit_complaints_prior" data-symptom-id="<?php echo $getSymptoms[0]['complaint_id']; ?>" title="<?php echo $getSymptoms[0]['symptoms']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr($getSymptoms[0]['symptoms'],0,15); ?></code></a>
								<?php }
								
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Add / Search symptoms here..." data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"  id="get_complaints" name="srchSymptoms" value="" class="form-control input-lg searchSymptoms" tabindex="1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
								<div id="beforeSymptom">
								<?php
								$getSymptomActive= mysqlSelect("b.symptoms as symptoms,a.symptoms_id as symptoms_id,b.complaint_id as complaint_id","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$edit_patient_episodes[0]['episode_id']."' and a.status='0'","","","","");
								while(list($key, $value) = each($getSymptomActive)){ 
									echo "<input type='hidden' name='symptomID[]' value='" . $value['complaint_id'] . "' /><span class='tag label label-primary m-r'>" . $value['symptoms'] . "<a data-role='remove' class='text-white del_edit_complaints m-l' data-symptom-id='".$value['symptoms_id']."'>x</a></span>";
								}
								?>
								</div>
								<div id="editSymptomResult"></div>
								</div>
								<br>
								<div class="input-group">
								<label class="pull-left">Suffering since:  </label>
									<div class="col-sm-6"><input type="text" name="suffering_from" data-episode-id="<?php echo md5($edit_patient_episodes[0]['episode_id']); ?>" value="<?php echo $edit_patient_episodes[0]['episode_medical_complaint']; ?>" class="form-control" id="suffering_since"></div>
								</div>
								<br>
								</div>
								<br>