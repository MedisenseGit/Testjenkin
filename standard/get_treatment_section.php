<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Treatment Advise</h4>
								<div class="input-group">				
								<?php $last_five_treatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='1')","freq_count DESC","","","5");
								
								if(COUNT($last_five_treatment)>0) { ?>
								<label>Frequently used:  </label>
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