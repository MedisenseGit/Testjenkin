<!--Prescrition Section -->
<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
	<h4><i class="fa fa-medkit"></i> Add Prescriptions</h4>
	<?php 
		$prev_prescription = mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_tab[0]['patient_id']."'","b.episode_id desc","","","");								
		
		$last_five_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","10");
		
		$last_five_medicine = mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","10");
		
	?>
	<br>
	<div class="input-group">
		<label>Saved prescriptions: </label>
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
		<label>Recent medicines: </label>
		<?php
			while(list($key_medicine, $value_medicine) = each($last_five_medicine)){
			?>
		<a class="btn btn-xs btn-white m-l edit_frequent_medicine" title="<?php echo strtolower($value_medicine['med_trade_name']); ?>" data-medicine-id="<?php echo $value_medicine['freq_medicine_id']; ?>" data-product-id="<?php echo $value_medicine['pp_id']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr(strtolower($value_medicine['med_trade_name']),0,15); ?></code></a>
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
		<?php $getTmplate= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$edit_patient_episodes[0]['episode_id']."'","episode_prescription_id asc","","","");
			if(COUNT($getTmplate)>0){
			?>
		<!--<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>	-->		
		<?php if($edit_patient_episodes[0]['prescription_template']==0){ ?>
		<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="85%">
			<thead>
				<th style="width:330px;">Medicine</th>
				<th style="width:330px;">Generic Name</th>
				<th style="width:30px;">Dosage Frequency</th>
				<th style="width:30px;">Timing</th>
				<th style="width:30px;">Duration</th>
				<!--<th>Note</th>-->
				<th style="width:10px;">Delete</th>
			</thead>
			<tbody>
				<?php foreach($getTmplate as $TempList) { 
					$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
					$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
					$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
					$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
					
					?>
				<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>">
					<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> <input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:100%;"></td>
					<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_generic_name'];?>" placeholder="Generic Name" style="width:100%;"></td>
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
		<?php } else if($edit_patient_episodes[0]['prescription_template']==1){ ?>
		<table  cellpadding="2" cellspacing="2" border="0" class="table table-responsive" width="85%" style="border:none;">
			<thead>
				<!--<th style="width:2px;">S.No.</th>-->
				<th style="width:360px;background-color:#1a2530; color:#fff; text-align:center;">Medicine <br><?php echo $get_doc_lang[0];?></th>
				<th style="width:130px;background-color:#1a2530; color:#fff; text-align:center;"><span style="float:left;">Morning<br><?php echo $get_doc_lang[1];?></span> <span style="float:left;margin-left:20px;">Afternoon<br><?php echo $get_doc_lang[2];?></span> <span style="float:left;margin-left:20px;">Night<br><?php echo $get_doc_lang[3];?></span></th>
				<th style="width:110px;background-color:#1a2530; color:#fff; text-align:center;">Duration<br><?php echo $get_doc_lang[4];?></th>
				<th style="width:90px;background-color:#1a2530; color:#fff; text-align:center; " >Timing<br><?php echo $get_doc_lang[5];?></th>
				<th style="width:10px;background-color:#1a2530; color:#fff;"></th>
			</thead>
			<tbody>
				<?php while(list($TempKey, $TempList) = each($getTmplate)) { 
					$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
					$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
					$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
					$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
					
					$sl_num = $TempKey +1;
					if($TempKey%2==0){
						$rowColor = "style='background-color:#9ea8bd;border:none;'";
					}
					else
					{
						$rowColor = "style='background-color:#1a2530;border:none;'";
					}
					?>
				<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>" style="border-top:none; border-bottom:none;">
					<!--<td style="width:2px;" rowspan="2"><?php echo $sl_num; ?></td>-->
					<td <?php echo $rowColor; ?>><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?>
						<input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" required="required" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:100%;">
					</td>
					<td colspan="1" <?php echo $rowColor; ?>>
						<select class="form-control slctFreqMorning" name="slctFreqMorning[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;">
							<option value="0" <?php if($TempList['med_frequency_morning']==0 || empty($TempList['med_frequency_morning'])){ echo "selected"; } ?>>0</option>
							<option value="0.5" <?php if($TempList['med_frequency_morning']==0.5){ echo "selected"; } ?>>0.5</option>
							<option value="1" <?php if($TempList['med_frequency_morning']==1){ echo "selected"; } ?>>1</option>
							<option value="2" <?php if($TempList['med_frequency_morning']==2){ echo "selected"; } ?>>2</option>
							<option value="3" <?php if($TempList['med_frequency_morning']==3){ echo "selected"; } ?>>3</option>
							<option value="4" <?php if($TempList['med_frequency_morning']==4){ echo "selected"; } ?>>4</option>
							<option value="5" <?php if($TempList['med_frequency_morning']==5){ echo "selected"; } ?>>5</option>
							<option value="2.5 ml" <?php if($TempList['med_frequency_morning']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option>
							<option value="5 ml" <?php if($TempList['med_frequency_morning']=="5 ml"){ echo "selected"; } ?>>5 ml</option>
							<option value="10 ml" <?php if($TempList['med_frequency_morning']=="10 ml"){ echo "selected"; } ?>>10 ml</option>
							<option value="20 ml" <?php if($TempList['med_frequency_morning']=="20 ml"){ echo "selected"; } ?>>20 ml</option>
							<option value="30 ml" <?php if($TempList['med_frequency_morning']=="30 ml"){ echo "selected"; } ?>>30 ml</option>
						</select>
						<select class="form-control slctFreqAfternoon" name="slctFreqAfternoon[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;">
							<option value="0" <?php if($TempList['med_frequency_noon']==0 || empty($TempList['med_frequency_noon'])){ echo "selected"; } ?>>0</option>
							<option value="0.5" <?php if($TempList['med_frequency_noon']==0.5){ echo "selected"; } ?>>0.5</option>
							<option value="1" <?php if($TempList['med_frequency_noon']==1){ echo "selected"; } ?>>1</option>
							<option value="2" <?php if($TempList['med_frequency_noon']==2){ echo "selected"; } ?>>2</option>
							<option value="3" <?php if($TempList['med_frequency_noon']==3){ echo "selected"; } ?>>3</option>
							<option value="4" <?php if($TempList['med_frequency_noon']==4){ echo "selected"; } ?>>4</option>
							<option value="5" <?php if($TempList['med_frequency_noon']==5){ echo "selected"; } ?>>5</option>
							<option value="2.5 ml" <?php if($TempList['med_frequency_noon']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option>
							<option value="5 ml" <?php if($TempList['med_frequency_noon']=="5 ml"){ echo "selected"; } ?>>5 ml</option>
							<option value="10 ml" <?php if($TempList['med_frequency_noon']=="10 ml"){ echo "selected"; } ?>>10 ml</option>
							<option value="20 ml" <?php if($TempList['med_frequency_noon']=="20 ml"){ echo "selected"; } ?>>20 ml</option>
							<option value="30 ml" <?php if($TempList['med_frequency_noon']=="30 ml"){ echo "selected"; } ?>>30 ml</option>
						</select>
						<select class="form-control slctFreqNight" name="slctFreqNight[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;">
							<option value="0" <?php if($TempList['med_frequency_night']==0 || empty($TempList['med_frequency_night'])){ echo "selected"; } ?>>0</option>
							<option value="0.5" <?php if($TempList['med_frequency_night']==0.5){ echo "selected"; } ?>>0.5</option>
							<option value="1" <?php if($TempList['med_frequency_night']==1){ echo "selected"; } ?>>1</option>
							<option value="2" <?php if($TempList['med_frequency_night']==2){ echo "selected"; } ?>>2</option>
							<option value="3" <?php if($TempList['med_frequency_night']==3){ echo "selected"; } ?>>3</option>
							<option value="4" <?php if($TempList['med_frequency_night']==4){ echo "selected"; } ?>>4</option>
							<option value="5" <?php if($TempList['med_frequency_night']==5){ echo "selected"; } ?>>5</option>
							<option value="2.5 ml" <?php if($TempList['med_frequency_night']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option>
							<option value="5 ml" <?php if($TempList['med_frequency_night']=="5 ml"){ echo "selected"; } ?>>5 ml</option>
							<option value="10 ml" <?php if($TempList['med_frequency_night']=="10 ml"){ echo "selected"; } ?>>10 ml</option>
							<option value="20 ml" <?php if($TempList['med_frequency_night']=="20 ml"){ echo "selected"; } ?>>20 ml</option>
							<option value="30 ml" <?php if($TempList['med_frequency_night']=="30 ml"){ echo "selected"; } ?>>30 ml</option>
						</select>
					</td>
					<td <?php echo $rowColor; ?>>
						<select class="form-control duration" name="prescription_duration[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left; width:70px;">
							<option value="" <?php if($TempList['duration']==""){ echo "selected"; } ?>>Select</option>
							<?php for($i=0;$i<=30;$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($TempList['duration']==$i){ echo "selected"; } ?>><?php echo $i; ?></option>
							<?php } ?>
						</select>
						<select class="form-control duration_type" name="slctDurationType[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="width:100px;">
							<option value="" <?php if(empty($TempList['med_duration_type'])){ echo "selected"; } ?>>Select</option>
							<option value="Days" <?php if($TempList['med_duration_type']=="Days"){ echo "selected"; } ?>>Days</option>
							<option value="Weeks" <?php if($TempList['med_duration_type']=="Weeks"){ echo "selected"; } ?>>Weeks</option>
							<option value="Months" <?php if($TempList['med_duration_type']=="Months"){ echo "selected"; } ?>>Months</option>
						</select>
					</td>
					<td <?php echo $rowColor; ?>>
						<select class="form-control medtiming" name="slctTiming" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="width:100%;">
							<option value="">Select</option>
							<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
							<option value="<?php echo $value_lng['language_id']; ?>" <?php if($TempList['timing']==$value_lng['language_id']){ echo "selected"; }?>><?php echo $value_lng['english']; ?></option>
							<?php } ?>
					</td>
					<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
					<td <?php echo $rowColor; ?>  rowspan="2" class="text-center"><a valign="middle" title="Delete" class="edit_del_medicine" data-medicine-id="<?php echo $TempList['episode_prescription_id'];?>"><img src="<?php echo HOST_MAIN_URL; ?>premium/remove-icon.png" width="20"/></a> </td>
				</tr>
				<tr  <?php echo $rowColor; ?> id="medRow1<?php echo $TempList['episode_prescription_id'];?>" style="border-top:none; border-bottom:none;">
				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" required data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_generic_name'];?>" placeholder="Generic Name" style="width:90%;float:right"></td>
				<td class="fields" colspan="5"><textarea class="form-control instructions" name="prescription_other_instruct[]" id="prescription_other_instruct[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" placeholder="Instructions" style="width:100%;" rows="1"><?php echo $TempList['prescription_instruction'];?></textarea></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php 	}
			} ?>
	</div>
	
	<div class="input-group col-lg-12 m-b">
		<!-- <div class="col-lg-3">
			<dl>
				<dt>
					<i class="fa fa-user-md"></i> Refer To <a href="Refer-Out-Doctor" target="_blank" title="Add new outward referral" class="pull-right">ADD <i class="fa fa-plus"></i></a>
				</dt>
				<br>
				<dd>
					<select name="refer_to" id="refer_to" class="form-control">
						<option value="">Select</option>
						<?php 
							$refer_out_doc = mysqlSelect("*","doctor_out_referral","doc_id='".$admin_id."'","","","","");

							while(list($key_doc,$value_doc) = each($refer_out_doc))
							{
								$doc_spec = mysqlSelect("spec_name","specialization","spec_id='".$value_doc['doc_specialization']."'","","","","");
								if($doc_spec){ $spec = " - ".$doc_spec[0]['spec_name'];}
								?>
								<option value="<?php echo $value_doc['doc_out_ref_id']; ?>" <?php if($edit_patient_episodes[0]['outward_ref_id']==$value_doc['doc_out_ref_id']){ echo "selected"; }?> ><?php echo $value_doc['doctor_name'].$spec; ?></option>
								<?php
							}
						?>
					</select>
				</dd>
				<br>
			</dl>
		</div> -->
		<div class="col-lg-6">
			<dl>
				<dt><i class="fa fa-sticky-note-o"></i> Prescription Note</dt>
				<br>
				<dd><textarea class="form-control presc_note" name="presc_note" id="presc_note" data-episode-id="<?php echo md5($edit_patient_episodes[0]['episode_id']); ?>" rows="2"><?php echo $edit_patient_episodes[0]['prescription_note']; ?></textarea></dd>
				<br>
			</dl>
		</div>
		<?php 
			$get_pat_edu = mysqlSelect("*","patient_education","doc_id='".$admin_id."' and doc_type=1","","","","");
		?>
		<div class="col-lg-6">
			<dl>
				<dt><i class="fa fa-info-circle"></i> Patient Education <a href="javascript:void(0)" data-toggle="popover" data-placement="right" title="Patient instruction note. If you want to add any instruction note for patient then goto Settings & add instruction in Patient education section"><img src="<?php echo HOST_URL_PREMIUM;?>info.png" width="15"/></a></dt>
				<br>
				<dd>
					<div class="pull-left m-r input-group date">
						<select name="pat_edu_type" id="pat_edu_type" class="form-control">
							<option value="" >Select</option>
							<?php 
								while(list($key,$value) = each($get_pat_edu))
								{
								?>
								<option value="<?php echo $value['edu_id']; ?>" <?php if($edit_patient_episodes[0]['patient_education']==$value['edu_id']) { echo "selected"; } ?> ><?php echo $value['edu_title']; ?></option>
								<?php 
								} 
							?>
						</select>
					</div>
				</dd>
				<br>
			</dl>
		</div>
	</div>
	<!--<div class="input-group" >
		<label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label>
		</div>
		<div class="input-group m-b" >
		 <input type="text" name="template_name" id="template_name" placeholder="Template Name" style="width:200px; display: none;" class="form-control">
		</div>-->
</div>
<!-- End PRescription Section -->

<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
	<h4><i class="fa fa-medkit"></i> Add Referal</h4>
	<div class="input-group col-lg-12 m-b" >
		<div class="col-lg-4">
			<dl>
				<dt><i class="fa fa-user-md"></i> Referal To <a href="Refer-Out-Doctor" target="_blank" title="Add new outward referral" class="pull-right">ADD <i class="fa fa-plus"></i></a></dt>
				<br> 
				<dd>
					<input type="text" name="refer_to" id="refer_to" placeholder="Refer To" class="form-control input-lg" value="<?php echo $edit_patient_episodes[0]['referTo']; ?>">
				</dd>
				<br>
			</dl>
		</div>
		<div class="col-lg-4">
			<dl>
				<dt>
					<i class="fa fa-user-md"></i> Specialization
				</dt>
				<br> 
				<dd>
					<div class="input-group">
						<input type="text" id="specialization" placeholder="Search Specialization here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" name="specialization" value="<?php echo $edit_patient_episodes[0]['specialization']; ?>" class="form-control input-lg searchSpecialization" tabindex="6">
						<!-- <div class="input-group-btn">
							<button class="btn btn-lg btn-primary" name="cmdSearchTab" type="button">
							ADD
							</button>
						</div> -->
					</div>
					<br>
					<div class="input-group">
						<div id="dispSpecialization"></div>
					</div>
					<!-- <select name="specialization" id="specialization" class="form-control">
						<option value="" selected>Select Specialization</option>
						<?php 
							while(list($key_spec,$value_spec) = each($specialization)) {
							?>
						<option value="<?php echo $value_spec['spec_id']; ?>" >-- <?php echo $value_spec['spec_name']; ?></option>
						<?php } ?>
					</select> -->
				</dd>
				<br>
			</dl>
		</div>
		<div class="col-lg-4">
			<dl>
				<dt><i class="fa fa-sticky-note-o"></i> Referal Note</dt>
				<br> 
				<dd><textarea class="form-control input-lg treatment_notes" name="treatment_notes" rows="2"><?php echo $edit_patient_episodes[0]['patientNote']; ?></textarea></dd>
				<br>
			</dl>
		</div>
	</div>
</div>