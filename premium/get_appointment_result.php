<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$cudate=date('Y-m-d');

//Update patient status
if(isset($_GET['patientid']) && !empty($_GET['patientid'])){
	$params     = split("-", $_GET['patientid']);
	
	if(is_numeric($params[0]) == false)
	{
		$patient_name=$_GET['patientid'];
		$patient_id="";
	}
	else
	{
	//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_age,patient_email,patient_mob,patient_gen,patient_loc,pat_state,pat_country,patient_addrs","doc_my_patient","patient_id='".$params[0]."'","","","","");

	$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$params[0]."'","","","","");	

		$patient_name = $get_PatientDetails[0]['patient_name'];
		$patient_id=$get_PatientDetails[0]['patient_id'];
	}
	
			
}	
	?>
<script>
function getDocTiming(val) {
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val,
	success: function(data){
		$("#check_time1").html(data);
	}
	});
}	
</script>
					
							
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" value="<?php echo $patient_name;?>" name="se_pat_name" required="required" class="form-control" ></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" value="<?php echo $get_PatientDetails[0]['patient_age'];?>" class="form-control"></div>
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
									 <div class="col-sm-10"> 
									<?php if($get_PatientDetails[0]['patient_gen']=="1") { ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" required="required" name="se_gender" checked>
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" required="required" name="se_gender" >
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" required="required" name="se_gender" >
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										<?php } else if($get_PatientDetails[0]['patient_gen']=="2") { ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" required="required" name="se_gender" >
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" required="required" name="se_gender" checked>
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" required="required" name="se_gender" >
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										<?php } else if($get_PatientDetails[0]['patient_gen']=="3"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" required="required" name="se_gender" >
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" required="required" name="se_gender" >
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" required="required" name="se_gender" checked/>
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										
										<?php } else { ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" required="required" name="se_gender" >
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" required="required" name="se_gender" >
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" required="required" name="se_gender" />
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										
										<?php } ?>
										</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $get_PatientDetails[0]['patient_mob']; ?>" name="se_phone_no" required="required" maxlength="10" minlength="10" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" value="<?php echo $get_PatientDetails[0]['patient_email']; ?>" name="se_email" class="form-control"></div>
								
                                </div>
								
								<!--<div class="form-group">
									<label class="col-sm-2 control-label">Pincode</label>
									<div class="col-sm-4">
									<input type="text" id="pincode_gen" placeholder="Pincode"  name="pincode" value="" class="form-control">								
									</div>									
									
								</div>-->
								<div id="beforeLoad">
								<div class="form-group">
								<label class="col-sm-2 control-label">City <span class="required">*</span></label>
                                    <div class="col-sm-4">
									<input type="text" name="se_city" value="<?php echo $get_PatientDetails[0]['patient_loc']; ?>" class="form-control">
									</div>
									<label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<?php if(!empty($get_PatientDetails[0]['pat_state'])){ ?>
											<option value="<?php echo $get_PatientDetails[0]['pat_state']; ?>"><?php echo $get_PatientDetails[0]['pat_state']; ?></option>
													<?php } else { ?>
													<option value="">Select State</option>
													
													<?php 
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
											}
										?>
										</select>
									</div>
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
											<?php if(!empty($get_PatientDetails[0]['pat_country'])){ ?>
											<option value="<?php echo $get_PatientDetails[0]['pat_country']; ?>"><?php echo $get_PatientDetails[0]['pat_country']; ?></option>
											
													<?php } else { ?>
												<option value="India">India</option>
											<?php	$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" >
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														
													}
													}?> 
										</select>
									</div>
                                </div>
								
								</div>
								<div id="dispCity"></div>
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address"  rows="3"><?php echo $get_PatientDetails[0]['patient_addrs']; ?></textarea></div>
                                </div>
								
								
							
							<!--</form>-->
								
						
	