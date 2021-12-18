<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Diagnosis</h4>
								<div class="col-lg-6">
								<div class="input-group">				
								<?php $last_five_icd = $objQuery->mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.doc_id='".$admin_id."' and a.doc_type='2'","a.freq_count DESC","","","5");
								
								if(COUNT($last_five_icd)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								while(list($key_icd, $value_icd) = each($last_five_icd)){
								
								?>
								<a class="btn btn-xs btn-white m-l get_edit_diagnosis_prior"  title="<?php echo $value_icd['icd_code']; ?>" data-icd-id="<?php echo $value_icd['icd_id']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" ><code> <?php echo substr($value_icd['icd_code'],0,15); ?></code></a>
								
								<?php }
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Enter ICD Code/investigation name here..." data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" id="get_diagnosis" name="srchSymptoms" value="" class="form-control input-lg searchDiagnosis" tabindex="4">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
											</div>
										
                                    </div><br>
								<div class="input-group">
								<div id="beforeICDTest">
								<?php
								$get_diagnosis = $objQuery->mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_name,a.patient_diagnosis_id as patient_diagnosis_id","patient_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$edit_patient_episodes[0]['episode_id']."' and a.doc_type='2'","","","","");
									while(list($key, $value) = each($get_diagnosis))	
									{
									echo "<input type='hidden' name='icd_id[]' value='".$value['icd_id']."' />
									<input type='hidden' name='patient_diagnosis_id[]' value='".$value['patient_diagnosis_id']."' />
									<span class='tag label label-primary m-b m-r' style='margin-bottom:30px;'>" . $value['icd_name'] . "<a data-role='remove' class='text-white del_editdiagnosis m-l' data-diagnosis-id='".$value['patient_diagnosis_id']."'>x</a></span>"; 
									}
								?>
								</div>
								<div id="editICDTest"></div>
								</div><br>
								</div>
								
								<div class="col-lg-6">
								<dl>
								 <dt>Diagnosis Details</dt><br> <dd><textarea class="form-control diagno_details" id="diagnosis_details" data-episode-id="<?php echo md5($edit_patient_episodes[0]['episode_id']); ?>"  name="diagnosis_details" rows="3"><?php echo $edit_patient_episodes[0]['diagnosis_details']; ?></textarea></dd><br>
								</dl>
								</div>
								
								</div>
								<br>