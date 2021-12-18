<!--Prescrition Section -->
								
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4><i class="fa fa-medkit"></i> Add Prescriptions</h4>
								
								<?php 
								$prev_prescription = $objQuery->mysqlSelect("DISTINCT(b.episode_id) as episode_id","patient_episodes as a left join patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_tab[0]['patient_id']."'","b.episode_id desc","","","");								
								
								$last_five_templates = $objQuery->mysqlSelect("*","patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","5");
								
								$last_five_medicine = $objQuery->mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='2'","freq_count desc","","","5");
								
								?>
								<br>

								<div class="input-group">				
								
								<label>Frequent prescriptions: </label>
								<?php
								
								if(COUNT($last_five_templates)>0) {
								while(list($key_temp, $value_temp) = each($last_five_templates)){
								?>
								<a class="btn btn-xs btn-white m-l edit_load_template" title="<?php echo $value_temp['template_name']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-template-id="<?php echo $value_temp['template_id'];?>"><code> <?php echo substr($value_temp['template_name'],0,10); ?></code></a>
								<?php }
								}
								if(COUNT($prev_prescription)>0){ ?>
								<!--<a class="m-r m-l" onclick="return loadPrevTemplate(<?php echo $prev_prescription[0]['episode_id'].",".$patient_tab[0]['patient_id']; ?>)"><code>REPEAT PRESCRIPTION</code></a>-->
								<a class="btn btn-xs btn-danger edit_prev_prescription m-l" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-prev-episode-id="<?php echo $prev_prescription[1]['episode_id']; ?>"> Repeat Prescription</a>
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
								<a class="btn btn-xs btn-white m-l edit_frequent_medicine" title="<?php echo strtolower($value_medicine['med_trade_name']); ?>" data-medicine-id="<?php echo $value_medicine['freq_medicine_id']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr(strtolower($value_medicine['med_trade_name']),0,15); ?></code></a>
								<?php } ?>
								</div>
								<?php }?><br>
								
								<ul class="nav nav-pills navbar-right" role="tablist">
																		<li role="presentation" class="dropdown">
																			<a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Load Template<span class="caret"></span></a>
																			<?php
																				$prescription_templates = $objQuery->mysqlSelect("*","patient_episode_prescription_templates","admin_id='".$admin_id."'"," template_id DESC","","","");
																				
	
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
										
                                       <input type="text" id="coding_language" placeholder="Add / Search Medicine here..." data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" name="search" value="" class="form-control input-lg searchMedicine" tabindex="6">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearchTab" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
									
																
								
								<div id="editMedTable"></div>
								<br><br>
									<div id="beforeEditMedTable">
								<?php $getTmplate= $objQuery->mysqlSelect("*","patient_episode_prescriptions","episode_id='".$edit_patient_episodes[0]['episode_id']."'","episode_prescription_id asc","","","");
								if(COUNT($getTmplate)>0){
								?>
														<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>			
														<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="85%">
																	<thead>
																				<th style="width:30px;">Medicine</th>
																				<th style="width:30px;">Generic Name</th>
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>-->
																				<th>Delete</th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { 
																			$get_Timing = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
																			$get_Timing_list = $objQuery->mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = $objQuery->mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = $objQuery->mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."' and doc_type='2'","","","","");
																			
																			?>
																			<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>">
																				<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> <input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:280px;"></td>
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_generic_name'];?>" placeholder="Generic Name" style="width:290px;"></td>
																				<td><input type="text" class="form-control tagName frequency" name="prescription_frequency[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_frequency'];?>" placeholder="Frequency" style="width:70px;"></td>
																				<td>
																				<select name="slctTiming" class="form-control medtiming" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" style="width:160px;" >
																				<?php if($get_Timing>0){
																				?>
																				<option value="<?php echo $get_Timing[0]['language_id']; ?>" selected><?php echo $get_Timing[0]['english']; ?></option>
																				<?php
																				
																				while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				} else { ?>
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				}?>
																				</select>
																				
																				<td><input type="text" class="form-control tagName duration" name="prescription_duration[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['duration'];?>" placeholder="Duration" style="width:90px;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td><a class="edit_del_medicine" data-medicine-id="<?php echo $TempList['episode_prescription_id'];?>"><span class="label label-danger">Delete</span></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
								<?php } ?>
								</div>
								
								<!--<div class="input-group" >
								<label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label>
								</div>
								<div class="input-group m-b" >
								 <input type="text" name="template_name" id="template_name" placeholder="Template Name" style="width:200px; display: none;" class="form-control">
								</div>-->
								</div>
								<!-- End PRescription Section -->