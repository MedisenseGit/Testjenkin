<!--Prescrition Section -->
<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
	<h4><i class="fa fa-medkit"></i> Add Prescriptions
		<a href="#" data-toggle="modal" class="" name="prescrDtl" id="prescrDtl" data-target="#myModalPrescDtl"> 
		<i class="fa fa-info-circle"></i>
		</a>
	</h4>
	<?php 
		$prev_prescription = mysqlSelect("DISTINCT(b.episode_id) as episode_id","doc_patient_episodes as a left join doc_patient_episode_prescriptions as b on a.episode_id=b.episode_id","a.admin_id='".$admin_id."' and a.patient_id='".$patient_tab[0]['patient_id']."'","b.episode_id desc","","","");								
		
										
		$last_five_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","10");
		
		$last_five_medicine = mysqlSelect("*","doctor_frequent_medicine","doc_id='".$admin_id."' and doc_type ='1'","freq_count desc","","","10");
		
		
		function get_regional_language($dst) {
			$state = strtolower($dst);
			$marathi_states = array('maharashtra');
			$kannada_states = array('karnataka');
			$tamil_states = array('tamil nadu', 'tamilnadu', 'pondicherry', 'puducherry');
			$gujarati_states = array('gujarat', 'gujarath', 'gujrat', 'gujrath', 'daman', 'diu');
			$telugu_states = array('andhra pradesh', 'telangana', 'andhra', 'andhrapradesh');
			$kerala_states = array('kerala');
			
			//$regional_language = "hindi";
			if(in_array($state, $marathi_states)) {
			$regional_language = "marathi";
			} else if(in_array($state, $kannada_states)) {
			$regional_language = "kannada";
			} else if(in_array($state, $tamil_states)) {
			$regional_language = "tamil";
			} else if(in_array($state, $gujarati_states)) {
			$regional_language = "gujrathi";
			} else if(in_array($state, $telugu_states)) {
			$regional_language = "telugu";
			} else if(in_array($state, $kerala_states)) {
			$regional_language = "malayalam";
			} else {
			$regional_language = "hindi";	
			}
			return $regional_language;
		}
		
		function get_doc_regional_language($lang) {
			
			$marathi = array("औषध", "सकाळी", "दुपारी", "रात्री","कालावधी","वेळ","सूचना");
			$hindi = array("दवा", "सुबह", "दोपहर", "रात","अवधि","समय","अनुदेश");
			$kannada = array("ಔಷಧ", "ಬೆಳಿಗ್ಗೆ", "ಮಧ್ಯಾಹ್ನ", "ರಾತ್ರಿ","ಅವಧಿ", "ಸಮಯ", "ಸೂಚನೆಗಳು");
			$tamil = array("மருந்து", "காலை", "பிற்பகல்", "இரவு","கால","நேரம்","அறிவுறுத்தல்கள்");
			$gujrathi = array("દવા", "સવાર", "બપોર", "રાત્રે","અવધિ","સમય","સૂચનો");
			$telugu = array("వైద్యం", "ఉదయం", "మధ్యాహ్నం", "రాత్రి","వ్యవధి","సమయం","సూచనలను");
			$malayalam = array("മരുന്ന്", "രാവിലെ", "ഉച്ചകഴിഞ്ഞ്", "രാത്രി","കാലാവധി","സമയം","നിർദ്ദേശങ്ങൾ");
			switch($lang) {
			case 'marathi':
				$arr = $marathi;
				break;
			case 'hindi':
				$arr = $hindi;
				break; 
			case 'kannada':
				$arr = $kannada;
				break; 
			case 'tamil':
				$arr = $tamil;
				break; 
			case 'gujrathi':
				$arr = $gujrathi;
				break; 
			case 'telugu':
				$arr = $telugu;
				break; 
			case 'malayalam':
				$arr = $malayalam;
				break; 
			}
			return $arr;
		}
		
		$regional_lang = get_regional_language($get_doc_details[0]['doc_state']);	
		
		$get_doc_lang = get_doc_regional_language($regional_lang);
		?>
	<div class="input-group">
		<label>Recent prescriptions:  </label><a id="editTempPrescription" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Edit <i class="fa fa-pencil-square-o"></i></a><a id="cancelTempPrescription" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Cancel </a>
		<?php
			if(COUNT($prev_prescription)>0){ ?>
		<!--<a class="m-r m-l" onclick="return loadPrevTemplate(<?php echo $prev_prescription[0]['episode_id'].",".$patient_tab[0]['patient_id']; ?>)"><code>REPEAT PRESCRIPTION</code></a>-->
		<a class="btn btn-xs btn-danger prev_prescription m-l" data-prev-episode-id="<?php echo $prev_prescription[0]['episode_id']; ?>"> Repeat Prescription</a>
		<?php 
			} ?>
		<span id="beforePrescTempEdit">
		<?php 
			while(list($key_temp, $value_temp) = each($last_five_templates)){
			?>
		<a class="btn btn-xs btn-white m-l load-template" title="<?php echo $value_temp['template_name']; ?>" data-template-id="<?php echo $value_temp['template_id'];?>"><code> <?php echo substr($value_temp['template_name'],0,10); ?></code></a>
		<?php } ?>
		</span>
		<span id="afterPrescTempEdit"></span>
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
		<a class="btn btn-xs btn-white m-l frequent_medicine" title="<?php echo strtolower($value_medicine['med_trade_name']); ?>" data-medicine-id="<?php echo $value_medicine['freq_medicine_id']; ?>" data-product-id="<?php echo $value_medicine['pp_id']; ?>" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"><code> <?php echo substr(strtolower($value_medicine['med_trade_name']),0,15); ?></code></a>
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
		<input type="text" id="coding_language" placeholder="Add / Search Medicine here..." data-episode-id="0" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" name="search" value="" class="form-control input-lg searchMedicine" tabindex="6">
		<div class="input-group-btn">
			<button class="btn btn-lg btn-primary" name="cmdSearchTab" type="button">
			ADD
			</button>
		</div>
	</div>
	<br><br>
	<div id="dispMedTable"></div>
	<?php if($checkSetting[0]['prescription_template']==0){ ?>
	<table id="prescription-template1" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="80%">
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
	<?php } else if($checkSetting[0]['prescription_template']==1){ ?>
	<table id="prescription-template2" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="50%">
		<thead>
			<th style="width:330px;background-color:#1a2530; color:#fff; text-align:center;">Medicine <br><?php echo $get_doc_lang[0];?></th>
			<th style="width:10px;background-color:#1a2530; color:#fff; text-align:center;">Morning<br><?php echo $get_doc_lang[1];?></th>
			<th style="width:10px;background-color:#1a2530; color:#fff; text-align:center;">Afternoon<br><?php echo $get_doc_lang[2];?></th>
			<th style="width:10px;background-color:#1a2530; color:#fff; text-align:center;">Night<br><?php echo $get_doc_lang[3];?></th>
			<th style="width:110px;background-color:#1a2530; color:#fff; text-align:center;">Duration<br><?php echo $get_doc_lang[4];?></th>
			<th style="width:90px;background-color:#1a2530; color:#fff; text-align:center;">Timing<br><?php echo $get_doc_lang[5];?></th>
			<!--<th>Note</th>
				<th>Delete</th>-->
		</thead>
		<tbody>
		</tbody>
	</table>
	<?php } ?>
	<div class="input-group col-lg-12 m-b" >
		<?php $get_pat_edu = mysqlSelect("*","patient_education","doc_id='".$admin_id."' and doc_type=1","","","","");  
			//$refer_out_doc = mysqlSelect("*","doctor_out_referral","doc_id='".$admin_id."'","","","",""); 
			$specialization = mysqlSelect("*","specialization","","","","",""); 
			?>
		<div class="col-lg-4">
			<dl>
				<dt><label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label></dt>
				<br> 
				<dd><input type="text" name="template_name" id="template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd>
				<br>
			</dl>
		</div>		
		<div class="col-lg-4">
			<dl>
				<dt><i class="fa fa-sticky-note-o"></i> Prescription Note</dt>
				<br> 
				<dd><textarea class="form-control treatment_details" name="presc_note" rows="2"><?php echo $edit_patient_episodes[0]['prescription_note']; ?></textarea></dd>
				<br>
			</dl>
		</div>
		<div class="col-lg-4">
			<dl>
				<dt><i class="fa fa-info-circle"></i> Patient Education 
					<a href="javascript:void(0)" data-toggle="popover" data-placement="right" title="If you want to share patient education content (Ex.Food to avoid in Diabetes) along with EMR, then visit Settings - Patient Education"><img src="<?php echo HOST_URL_PREMIUM;?>info.png" width="15"/></a>
				</dt>
				<br> 
				<dd>
					<div class="pull-left m-r input-group date">
						<select name="pat_edu_type" id="pat_edu_type" class="form-control">
							<option value="" selected>Select</option>
							<?php while(list($key,$value) = each($get_pat_edu)) { ?>
							<option value="<?php echo $value['edu_id']; ?>" ><?php echo $value['edu_title']; ?></option>
							<?php } ?>
						</select>
					</div>
				</dd>
				<br>
			</dl>
		</div>
	</div>	
</div>
<!-- End PRescription Section -->
<div class="modal inmodal" id="myModalPrescDtl" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Prescription Note</h4>
			</div>
			<div class="modal-body">
				<span>3.7.4 Prescribing Medicines Prescribing medications, via telemedicine consultation is at the professional discretion of the RMP. It entails the same professional accountability as in the traditional in-person consult. If a medical condition requires a particular protocol to diagnose and prescribe as in a case of in-person consult then same prevailing principle will be applicable to a telemedicine consult. RMP may prescribe medicines via telemedicine ONLY when RMP is satisfied that he/ she has gathered adequate and relevant information about the patient’s medical condition and prescribed medicines are in the best interest of the patient. Prescribing Medicines without an appropriate diagnosis/provisional diagnosis will amount to a professional misconduct Specific Restrictions There are certain limitations on prescribing medicines on consult via telemedicine depending upon the type of consultation and mode of consultation. The categories of medicines that can be prescribed via tele-consultation will be as notified in consultation with the Central Government from time to time. The categories of medicines that can be prescribed are listed below:  List O: It will comprise those medicines which are safe to be prescribed through any mode of tele-consultation. In essence they would comprise of o Medicines which are used for common conditions and are often available ‘over the counter’. For instance, these medicines would include, paracetamol, ORS solutions, cough lozenges etc o Medicines that may be deemed necessary during public health emergencies.  List A: These medications are those which can be prescribed during the first consult which is a video consultation and are being re-prescribed for re-fill, in case of follow-up. <br/>
				<br/>
				This would be an inclusion list, containing relatively safe medicines with low potential for abuse Is a list of medication which RMP can prescribe in a patient who is undergoing follow-up consult, as a refill.<br/>
				<br/>
				List B: Is a list of medication which RMP can prescribe in a patient who is undergoing follow-up consultation in addition to those which have been prescribed during in-person consult for the same medical condition.  Prohibited List: An RMP providing consultation via telemedicine cannot prescribe medicines in this list. These medicine have a high potential of abuse and could harm the patient or the society at large if used improperly o Medicines listed in Schedule X of Drug and Cosmetic Act and Rules or any Narcotic and Psychotropic substance listed in the Narcotic Drugs and Psychotropic Substances, Act, 1985<br/>
				<br/>
				As per Telemedicine practice guidelines by Medical Council of India, Before writing a prescription please note the following. Here is a list of type of drugs you can prescribe. 
				</span>
				<img alt="Prescription 1" class="img" src="images/Presc-1.png">
				<img alt="Prescription 2" class="img" src="images/Presc-2.png">
				<img alt="Prescription 3" class="img" src="images/Presc-3.png">
			</div>
		</div>
	</div>
</div>
<div style="clear: both;"></div>
<!-- Referal Section -->
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
						<input type="text" id="specialization" placeholder="Search Specialization here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" name="specialization" value="" class="form-control input-lg searchSpecialization" tabindex="6">
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