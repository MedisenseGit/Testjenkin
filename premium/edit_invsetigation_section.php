<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Investigations</h4>
								<div class="input-group">				
								<?php $last_five_tests = mysqlSelect("DISTINCT(a.main_test_id) as main_test_id,b.test_name_site_name as test_name_site_name","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_test_count DESC","","","8");
								$invest_templates = mysqlSelect("*","doc_patient_episode_investigations_templates","doc_id='".$admin_id."' and doc_type='1'","invest_template_id desc","","","10");
								
								
								if(COUNT($invest_templates)>0) {
								?><div class="input-group">				
								
								<label>Saved Template:  </label>
								<?php
								
								while(list($key_investtemp, $value_investtemp) = each($invest_templates)){
								?>
								<a class="btn btn-xs btn-white m-l invest_load_template" title="<?php echo $value_investtemp['template_name']; ?>" data-edit-status="1" data-invest-template-id="<?php echo md5($value_investtemp['invest_template_id']);?>" data-patient-id="<?php echo $patientid; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" ><code> <?php echo substr($value_investtemp['template_name'],0,10); ?></code></a>
								<?php } ?>
								</div>
								<br>
								<?php } 
								if(COUNT($last_five_tests)>0) { ?>
								<label>Recently used:  </label>
								<?php 
								foreach($last_five_tests as $last_five_tests_list){
								
								?>
								
								<a class="btn btn-xs btn-white m-l get_edit_diagnosis_test_prior" data-main-test-id="<?php echo $last_five_tests_list['main_test_id']; ?>" data-patient-id="<?php echo $patientid; ?>" title="<?php echo $last_five_tests_list['test_name_site_name']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr($last_five_tests_list['test_name_site_name'],0,15); ?></code></a>
								<?php }
								} ?>
								</div>
								<br>
								<?php //echo $edit_patient_episodes[0]['episode_id']="8676" ?>
								<div class="input-group">
										
                                       <input type="text" id="get_diagnosis_test" placeholder="Enter test here..."  data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" name="searchDiagnosTest" value="" class="form-control input-lg searchDiagnosTest" tabindex="2">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<dl>
									<br> <dd>
								<div id="beforeEditInvest">	
									<a class="btn btn-xs btn-white pull-right delete_all_diagnosis_test" data-patient-id="<?php echo $patientid; ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
								<table class="table table-bordered">
								<?php 
								$getTestCardioDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='1'","","","","");
								$getTestRadiologyDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='3'","","","","");
								$getTestOpthalDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='2'","","","","");
								$getLabTestDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='4'","","","","");
								$getOtherTestDetails= mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='5'","","","","");
								
								
								if(COUNT($getLabTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Clinical Laboratory Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getLabTestDetails as $getLabTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getLabTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getLabTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getLabTestDetailsList['main_test_id']; ?>" /><?php echo $getLabTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getLabTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" value="<?php echo $getLabTestDetailsList['test_actual_value']; ?>"  style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Cardiology Specific Tests</th>
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestCardioDetails as $getTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getTestDetailsList['main_test_id']; ?>" /><?php echo $getTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-date-added="<?php echo $edit_patient_episodes[0]['date_time']; ?>" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>" value="<?php echo $getTestDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestRadiologyDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Radiology Specific Tests</th>
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestRadiologyDetails as $getRadiologyDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getRadiologyDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getRadiologyDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getRadiologyDetailsList['main_test_id']; ?>" /><?php echo $getRadiologyDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getRadiologyDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-date-added="<?php echo $edit_patient_episodes[0]['date_time']; ?>" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>" value="<?php echo $getRadiologyDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="3">Ophthalmology Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Left Eye</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestOpthalDetails as $getOpthalDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOpthalDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOpthalDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOpthalDetailsList['main_test_id']; ?>" /><?php echo $getOpthalDetailsList['test_name']; ?></td>
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="<?php echo $getOpthalDetailsList['right_eye']; ?>" placeholder="" style="width:100px;">  &nbsp;&nbsp; <input type="text" class="left_eye" name="" value="<?php echo $getOpthalDetailsList['left_eye']; ?>" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getOtherTestDetails)>0){ ?>
										<thead>
										<tr>
										<th colspan="3">Other Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<!--<th>Normal Value</th>-->
										<th>Result</th>
										<th>Delete</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getOtherTestDetails as $getOtherTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getOtherTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getOtherTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getOtherTestDetailsList['main_test_id']; ?>" /><?php echo $getOtherTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getOtherTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName editTestActualVal" name="" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>" data-date-added="<?php echo $edit_patient_episodes[0]['date_time']; ?>" value="<?php echo $getOtherTestDetailsList['test_actual_value']; ?>"  style="width:100%;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>
								</div>	
								<div id="dispEditInvest"></div>
								<div class="input-group col-lg-12 m-b" id="invest_temp_section" style="display: none;">
																
								<div class="col-lg-4">
								
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkInvestSaveTemplate" id="chkInvestSaveTemplate" value="1"> Save this as template</label></dt><br> <dd><input type="text" name="invest_template_name" id="invest_template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd><br>
									</dl>
									
								</div>
								</div>
								</dl>
								</div>
								<br>