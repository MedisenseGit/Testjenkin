								
								<h2><i class="fa fa-medkit"></i> Add Prescriptions</h2>	
								<?php 
								$prev_prescription = mysqlSelect("b.episode_id as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_tab[0]['patient_id']."'","b.episode_id desc","","","");								
								
								$last_five_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","5");
								
								$last_five_medicine = mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","5");
								
								?>
								<br>
								<div class="input-group">				
								
								<label>Frequent prescriptions:  </label>
								<?php
								
								if(COUNT($last_five_templates)>0) {
								while(list($key_temp, $value_temp) = each($last_five_templates)){
								?>
								<a class="btn btn-xs btn-white m-l load-template" title="<?php echo $value_temp['template_name']; ?>" data-template-id="<?php echo $value_temp['template_id'];?>"><code> <?php echo substr($value_temp['template_name'],0,10); ?></code></a>
								<?php }
								}
								if(COUNT($prev_prescription)>0){ ?>
								<!--<a class="m-r m-l" onclick="return loadPrevTemplate(<?php echo $prev_prescription[0]['episode_id'].",".$patient_tab[0]['patient_id']; ?>)"><code>REPEAT PRESCRIPTION</code></a>-->
								<a class="btn btn-xs btn-danger prev_prescription m-l" data-prev-episode-id="<?php echo $prev_prescription[0]['episode_id']; ?>"> Repeat Prescription</a>
								<?php 
								}
								?>
								<br>
								
								</div>
								<br>
								<?php 
								if(COUNT($last_five_medicine)>0) {
								?>
								<div class="input-group">
								<label>Frequent medicines: </label>
								<?php
								while(list($key_medicine, $value_medicine) = each($last_five_medicine)){
								?>
								<a class="btn btn-xs btn-white m-l frequent_medicine" title="<?php echo strtolower($value_medicine['med_trade_name']); ?>" data-medicine-id="<?php echo $value_medicine['freq_medicine_id']; ?>" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"><code> <?php echo substr(strtolower($value_medicine['med_trade_name']),0,15); ?></code></a>
								<?php } ?>
								</div>
								<?php }?><br>
								<ul class="nav nav-pills navbar-right" role="tablist">
																		<li role="presentation" class="dropdown">
																			<a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Load Template<span class="caret"></span></a>
																			<?php
																				$prescription_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'"," template_id DESC","","","");
																				
	
																				if (count($prescription_templates) > 0)
																				{
																					echo '<ul id="menu6" class="dropdown-menu animated fadeInDown" role="menu">';
																					if(COUNT($prev_prescription)>0){ echo '<li role="presentation"><a role="menuitem" tabindex="-1" style="cursor:default" ><input  type="checkbox" id="prescriptionTemplate_'.$prev_prescription[0]['episode_id'].'" name="prescriptionTemplate['. $prev_prescription[0]['episode_id'] .']" class="prescriptionTemplate" value="'.$prev_prescription[0]['episode_id'].'" onclick="return loadPrevTemplate('.$prev_prescription[0]['episode_id'].','.$patient_tab[0]['patient_id'].')"> Last Episode Prescription</a></li>'; } 
																					while (list($prescription_template_key, $prescription_template_val) = each($prescription_templates))
																					{
																						
																						echo '<li role="presentation"><a role="menuitem" tabindex="-1" style="cursor:default" ><input  type="checkbox" id="prescriptionTemplate_'.$prescription_template_val['template_id'].'" name="prescriptionTemplate['. $prescription_template_val['template_id'] .']" data-template-id="'.$prescription_template_val['template_id'].'" class="load-template" value="'. $prescription_template_val['template_id'] .'" > '. $prescription_template_val['template_name'].'</a></li>';
																					}
																					echo '</ul>';
																				}
																			?>
																		</li>
																	</ul>
							
																
								<div class="input-group" id="searchMedicine">
										
                                       <input type="text" id="coding_language" placeholder="Add / Search Medicine here..." data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" name="search" value="" class="form-control input-lg searchMedicine" tabindex="6">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearchTab" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
									
																
								
								<div id="dispMedTable"></div>
								<br><br>
									<table id="employee-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th style="width:30px;">Medicine</th>
																				<th style="width:30px;">Generic Name</th>
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>
																				<th>Delete</th>-->
																			</thead>
																			<tbody>
																			</tbody>
																			
																		</table>
								<label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label>
										 <div class="form-group"><input type="text" name="template_name" id="template_name" placeholder="Template Name" style="width:200px; display: none;" class="form-control"></div>	
