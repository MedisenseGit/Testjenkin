<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Investigations </h4>
								<div class="input-group">				
								<?php $last_five_tests = mysqlSelect("DISTINCT(a.main_test_id) as main_test_id,b.test_name_site_name as test_name_site_name","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.doc_id='".$admin_id."' and a.doc_type='1'","a.freq_test_count DESC","","","8");
								
								$invest_templates = mysqlSelect("*","doc_patient_episode_investigations_templates","doc_id='".$admin_id."' and doc_type='1'","invest_template_id desc","","","10");
								
								if(COUNT($invest_templates)>0) {
								?><div class="input-group">				
								
								<label>Saved Template:  </label><a id="editTempInvestigation" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Edit <i class="fa fa-pencil-square-o"></i></a><a id="cancelTempInvestigation" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Cancel </a>
								<span id="beforeInvestTempEdit">
								<?php
								
								while(list($key_investtemp, $value_investtemp) = each($invest_templates)){
								?>
								<a class="btn btn-xs btn-white m-l invest_load_template" title="<?php echo $value_investtemp['template_name']; ?>" data-edit-status="0" data-invest-template-id="<?php echo md5($value_investtemp['invest_template_id']);?>" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" ><code> <?php echo substr($value_investtemp['template_name'],0,10); ?></code></a>
								<?php } ?>
								</span>
								<span id="afterInvestTempEdit"></span>
								</div>
								<br>
								<?php } if(COUNT($last_five_tests)>0) { ?>
								<label>Recently used:  </label>
								<?php 
								foreach($last_five_tests as $last_five_tests_list){
								
								?>
								
								<a class="btn btn-xs btn-white m-l get_diagnosis_test_prior" data-main-test-id="<?php echo $last_five_tests_list['main_test_id']; ?>" title="<?php echo $last_five_tests_list['test_name_site_name']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo substr($last_five_tests_list['test_name_site_name'],0,15); ?></code></a>
								<?php }
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" id="get_diagnosis_test" placeholder="Add / Search test here..." data-episode-id="0"  data-patient-id="<?php echo $patient_id; ?>" name="searchDiagnosTest" value="" class="form-control input-lg searchDiagnosTest" tabindex="2">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                </div>
								<dl>
									<br> <dd>
											<div id="dispDignoTest"></div>
									<?php 
									$getInvestTemplate= mysqlSelect("invest_template_id","doc_patient_episode_investigations_templates","doc_id='".$admin_id."' and doc_type='1' and default_visible='1'","","","","");
												
										if(count($getInvestTemplate)>0)
										{
											while(list($key,$value) = each($getInvestTemplate))
												{		
													$getInvestTemplateDetails= mysqlSelect("*","doc_patient_episode_investigation_template_details","invest_template_id='".$value['invest_template_id']."'","","","","");
													while(list($key_det,$value_det) = each($getInvestTemplateDetails))
													{
													$arrFileds = array();
													$arrValues = array();
													
													$arrFileds[]='main_test_id';
													$arrValues[]=$value_det['main_test_id'];
													
													$arrFileds[]='group_test_id';
													$arrValues[]=$value_det['main_test_id'];	
													
													$arrFileds[]='test_name';
													$arrValues[]=$value_det['test_name'];
													
													$arrFileds[]='department';
													$arrValues[]="1";
													
													$arrFileds[]='patient_id';
													$arrValues[]=$patient_tab[0]['patient_id'];
													
													$arrFileds[]='doc_id';
													$arrValues[]=$admin_id;
													
													$arrFileds[]='doc_type';
													$arrValues[]="1";
													$arrFileds[]='status';
													$arrValues[]="1";
													
													$insert_temp_value=mysqlInsert('patient_temp_investigation',$arrFileds,$arrValues);				
													}
													
												}
										
									$getTestCardioDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patient_tab[0]['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='1'","","","","");
									$getTestRadiologyDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patient_tab[0]['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='3'","","","","");
									$getTestOpthalDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patient_tab[0]['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='2'","","","","");
									$getLabTestDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patient_tab[0]['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='4'","","","","");
									$getOtherTestDetails= mysqlSelect("*","patient_temp_investigation","patient_id='".$patient_tab[0]['patient_id']."' and doc_id='".$admin_id."' and doc_type='1' and status='1' and department='5'","","","","");

									?>		
									<div id="dispTempInvestigation">
											<table class="table table-bordered">
								<?php if(COUNT($getLabTestDetails)>0){ ?>
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
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getLabTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getLabTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
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
										</thead>
										<tbody>
										<tr>
										
									<?php 
									
									foreach($getTestCardioDetails as $getTestDetailsList)	
									
									
									{  ?>
									<tr id="delInvestRow<?php echo $getTestDetailsList['pti_id'];?>">
									<td><input type="hidden" name="group_test_id[]" value="<?php echo $getTestDetailsList['group_test_id']; ?>" /><input type="hidden" name="main_test_id[]" value="<?php echo $getTestDetailsList['main_test_id']; ?>" /><?php echo $getTestDetailsList['test_name']; ?></td>
									<!--<td><?php echo $getTestDetailsList['normal_range']; ?></td>-->
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
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
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getRadiologyDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getRadiologyDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
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
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="" placeholder="" style="width:100px;"> &nbsp;&nbsp; <input type="text" class="left_eye" name="" value="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
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
									<td><input type="text" class="tagName addTestActualVal" name="" value="<?php echo $getOtherTestDetailsList['test_actual_value']; ?>" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>" placeholder="" style="width:100%;"></td>
									<td><a class="del_Invest" data-invest-id="<?php echo $getOtherTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>
								   </div>
								<?php } ?>
								</dl>
								
								<div class="input-group col-lg-12 m-b" id="invest_temp_section" style="display: none;">
																
								<div class="col-lg-4">
								
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkInvestSaveTemplate" id="chkInvestSaveTemplate" value="1"> Save this as template</label></dt><br> <dd><input type="text" name="invest_template_name" id="invest_template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd><br>
									</dl>
									
								</div>
								</div>
								
								</div>
								<br>
								
								
								