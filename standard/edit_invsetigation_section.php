<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Investigations</h4>
								<div class="input-group">				
								<?php $last_five_tests = $objQuery->mysqlSelect("DISTINCT(a.main_test_id) as main_test_id,b.test_name_site_name as test_name_site_name","doctor_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","(a.doc_id='".$admin_id."' and a.doc_type='2') or (doc_id='0' and doc_type='0')","a.freq_test_count DESC","","","8");
								
								if(COUNT($last_five_tests)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								foreach($last_five_tests as $last_five_tests_list){
								
								?>
								
								<a class="btn btn-xs btn-white m-l get_edit_diagnosis_test_prior" data-main-test-id="<?php echo $last_five_tests_list['main_test_id']; ?>" title="<?php echo $last_five_tests_list['test_name_site_name']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr($last_five_tests_list['test_name_site_name'],0,15); ?></code></a>
								<?php }
								} ?>
								</div>
								<br>
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
								$getTestCardioDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='1' and doc_type='2'","","","","");
								$getTestOpthalDetails= $objQuery->mysqlSelect("*","patient_temp_investigation","episode_id='".$edit_patient_episodes[0]['episode_id']."' and department='2' and doc_type='2'","","","","");
								if(COUNT($getTestCardioDetails)>0){ ?>
										<thead>
										
										<tr>
										<th>Test</th>
										<th>Normal Value</th>
										<th>Actual Value</th>
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
									<td><?php echo $getTestDetailsList['normal_range']; ?></td>
									<td><input type="text" class="tagName" name="" value="<?php echo $getTestDetailsList['test_actual_value']; ?>"  style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getTestDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } if(COUNT($getTestOpthalDetails)>0){ ?>
									<thead>
										<tr>
										<th colspan="5">Ophthal Specific Tests</th>
										
										</tr>
										<tr>
										<th>Test</th>
										<th>Right Eye</th>
										<th>Left Eye</th>
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
									<td><input type="text" class="right_eye" name="" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>"  value="<?php echo $getOpthalDetailsList['right_eye']; ?>" placeholder="" style="width:100px;"></td>
									<td><input type="text" class="left_eye" name="" value="<?php echo $getOpthalDetailsList['left_eye']; ?>" data-investigation-id="<?php echo $getOpthalDetailsList['pti_id']; ?>" placeholder="" style="width:100px;"></td>
									<td><a class="del_editInvest" data-invest-id="<?php echo $getOpthalDetailsList['pti_id'];?>"><span class="label label-danger">Delete</span></a></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								<?php } ?>
								   </table>
								</div>	
								<div id="dispEditInvest"></div>
								
								</dl>
								</div>
								<br>