<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Diagnosis</h4>
								<div class="col-lg-6">
								<div class="input-group">				
								<?php $last_five_icd = mysqlSelect("a.icd_id as icd_id,b.icd_code as icd_code","doctor_frequent_diagnosis as a inner join icd_code as b on a.icd_id=b.icd_id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_count DESC","","","6");
								
								if(COUNT($last_five_icd)>0) { ?>
								<label>Recently used:  </label>
								<?php 
								while(list($key_icd, $value_icd) = each($last_five_icd)){
								
								?>
								<a class="btn btn-xs btn-white m-l get_diagnosis_prior "  title="<?php echo $value_icd['icd_code']; ?>" data-icd-id="<?php echo $value_icd['icd_id']; ?>" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" ><code> <?php echo substr($value_icd['icd_code'],0,15); ?></code></a>
								
								<?php }
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                <input type="text" placeholder="Add / Search ICD Code or investigation name here..." data-episode-id="0" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" id="get_diagnosis" name="srchSymptoms" value="" class="form-control input-lg searchDiagnosis" tabindex="4">
                                <div class="input-group-btn">
                                <button class="btn btn-lg btn-primary"  name="" type="button"> ADD</button>
								</div>
										
                                    </div><br>
								<div class="input-group">
										<div id="dispICDTest"></div>
								</div><br>
								</div>
								
								<div class="col-lg-6">
								<dl>
								 <dt>Diagnosis Details</dt><br> <dd><textarea class="form-control" id="diagnosis_details"  name="diagnosis_details" rows="3"></textarea></dd><br>
								</dl>
								</div>
								</div>