<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Treatment Advise</h4>
								<div class="col-lg-6">
								<div class="input-group">				
								<?php $last_five_treatment = $objQuery->mysqlSelect("*","doctor_frequent_treatment","(doc_id='0' and doc_type='0') or (doc_id='".$admin_id."' and doc_type='2')","freq_count DESC","","","5");
								
								if(COUNT($last_five_treatment)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								
								while(list($key_treat, $value_treat) = each($last_five_treatment)){
									
								?>
								
								<a class="btn btn-xs btn-white m-l edit_get_treatment_prior" data-treatment-id="<?php echo $value_treat['dft_id']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo $value_treat['treatment']; ?></code></a>
								<?php }
								
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Write treatment here..." data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" id="get_treatment_res" name="srchTreat" value="" class="form-control input-lg searchTreatment" tabindex="3">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
								<div id="beforeeditTreatment">
								<?php 
								$getTreatment= $objQuery->mysqlSelect("b.treatment as treatment,a.treatment_id as treatment_id","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.episode_id='".$edit_patient_episodes[0]['episode_id']."' and a.doc_type='2'","a.treatment_id asc","","","");

								while(list($key, $value) = each($getTreatment)){ 
									echo '<span class="tag label label-primary m-r">' . $value['treatment'] . '<a data-role="remove" class="text-white edit_del_treatment m-l" data-treatment-id="'.$value['treatment_id'].'">x</a></span>';
								}
								?>
								</div>
								<div id="editTreatment"></div>
								</div>
								<br>
								</div>
								<div class="col-lg-6">
								<dl>
								 <dt>Treatment Details</dt><br> <dd><textarea class="form-control treatment_details" id="treatment_details" data-episode-id="<?php echo md5($edit_patient_episodes[0]['episode_id']); ?>" name="treatment_details" rows="3"><?php echo $edit_patient_episodes[0]['treatment_details']; ?></textarea></dd><br>
								</dl>
								</div>
								
								
								<!--<dl>
									<br> <dd><textarea class="form-control" id="examination" name="medical_examination" rows="2" tabindex="3"></textarea>
								</dl>-->
								</div>
								<br>