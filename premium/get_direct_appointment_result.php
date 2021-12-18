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
	$params     = explode("-", $_GET['patientid']);
	
	if(is_numeric($params[0]) == false)
	{
		$patient_name=$_GET['patientid'];
		$patient_id="";
	}
	else
	{
	$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_age,patient_email,patient_mob,patient_gen,patient_loc,pat_state,pat_country,patient_addrs,DOB","doc_my_patient","patient_id='".$params[0]."'","","","","");	

		$patient_name = $get_PatientDetails[0]['patient_name'];
		$patient_id=$get_PatientDetails[0]['patient_id'];
		$child_tab = mysqlSelect("*","child_tab","patient_id='".$params[0]."'","","","","");
	$parent_tab = mysqlSelect("*","parents_tab","parent_id='".$child_tab[0]['parent_id']."'","","","","");
	}
	
			
}	
$getRefDet = mysqlSelect("doc_state,ref_address,cons_charge","referal","ref_id='".$admin_id."'","","","","");             

$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");

$patient_episodes = mysqlSelect("*","doc_patient_episodes","admin_id = '". $admin_id ."' and patient_id = '". $get_PatientDetails[0]['patient_id'] ."' "," episode_id DESC ","","","");
	
$getDocSpec = mysqlSelect("a.spec_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."' and b.doc_type='1'","","","","");
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
				<form enctype="multipart/form-data" method="post"  action="add_details.php"  name="frmAddPatient" >
				<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" />				
				<input type="hidden" value="<?php if($getDocSpec[0]['spec_id']=="32"){ echo 1;} else{ echo 0; }?>" name="ChildEmr" >				
					<div class="form-horizontal">
							
							<?php if(count($patient_episodes)>0){ ?>
							<div class="form-group col-sm-12">
							<small class="pull-right">Last Visited On: <font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($patient_episodes[0]['date_time'])); ?></font></small>
							</div>
							<?php } ?>
								
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" value="<?php echo $patient_name;?>" name="se_pat_name" required="required" class="form-control" ></div>
                                
                                   <?php	if($getDocSpec[0]['spec_id']=="32"){ ?>
								
								<label class="col-sm-2 control-label">DOB</label>
                                    <div class="col-sm-4">									
									<input id="dateadded1" name="date_birth" type="text" value="<?php echo date('m/d/Y',strtotime($get_PatientDetails[0]['DOB']));?>" placeholder="" class="form-control" >
                                  
									</div>
								<?php }  else{ ?>
								<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" value="<?php echo $get_PatientDetails[0]['patient_age'];?>" class="form-control"></div>
								<?php } ?>                       
							   			 
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
									 <div class="col-sm-6"> 
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
										<?php	if($getDocSpec[0]['spec_id']=="32"){ ?>
									<label class="control-label col-sm-2">Vaccination Start Date <span class="required">*</span></label>
											<div class="col-sm-2">
											  <input type="text" id="J-demo-08" name="vaccine_start_date" required="required" class="form-control" value="<?php echo $child_tab[0]['vaccine_start_date']; ?>">
										  
											</div>
										  <?php } ?>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="number" class="form-control" value="<?php echo $get_PatientDetails[0]['patient_mob']; ?>" name="se_phone_no" required="required" maxlength="10" minlength="10" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" value="<?php echo $get_PatientDetails[0]['patient_email']; ?>" name="se_email" class="form-control"></div>
								
                                </div>
								<?php	if($getDocSpec[0]['spec_id']=="32"){ ?>
								<div class="form-group">
								 <label class="control-label col-sm-2">Mother Name <span class="required">*</span></label>
									<div class="col-sm-4">
									  <input type="text" id="mother_name" name="se_mother_name"  required="required" class="form-control" placeholder="" value="<?php echo $parent_tab[0]['mother_name']; ?>">
									</div>
									<label class="control-label col-md-2">Father Name <span class="required">*</span></label>
									<div class="col-md-4">
									  <input type="text" id="father_name" name="se_father_name" required="required" class="form-control" placeholder="" value="<?php echo $parent_tab[0]['father_name']; ?>">
									</div>
								</div>
								  <?php } ?>
								<!--
								<div class="form-group">
									<label class="col-sm-2 control-label">Pincode</label>
									<div class="col-sm-4">
									<input type="text" id="pincode_gen" placeholder="Pincode"  name="pincode" value="" class="form-control">								
									</div>									
									
								</div>-->
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address"  rows="3"><?php echo $get_PatientDetails[0]['patient_addrs']; ?></textarea></div>
                                </div> 
								<div id="beforeLoad">
								<div class="form-group">
								<label class="col-sm-2 control-label">City </label>
                                    <div class="col-sm-4">
									<input type="text" name="se_city" value="<?php echo $get_PatientDetails[0]['patient_loc']; ?>" class="form-control">
									</div>
									<label class="col-sm-2 control-label">State </label>

                                    <div class="col-sm-4">
										<select data-placeholder="Choose a State..." class="form-control" name="se_state" id="se_state" tabindex="2">
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
								
			  			</div>
								<div id="dispCity"></div>
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
		   			<button type="button" class="collapsible" data-toggle="collapse" data-target="#collapseRow1">Add Reference Details</button>
									<div class="content collapse" id="collapseRow1">
									  
								<div class="form-group"><label class="col-sm-2 control-label">Reference </label>

                                    <div class="col-sm-8"><select class="form-control" name="reference_from" id="reference_from" >
									<option value="">Select </option>
									<?php $select1= mysqlSelect("referred_doc_id,referral_name","add_referred_doctor","doc_id='".$admin_id."'","referral_name ASC","","","");
									foreach($select1 as $listDoc){ 
									?>
									<option value="<?php echo $listDoc['referred_doc_id']; ?>"><?php echo $listDoc['referral_name']; ?> </option>
									
									<?php } ?>
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new reference doctor" data-toggle="modal" data-target="#myModal1"><i class="fa fa-plus"></i>
										</a></div>
										
                                </div>
							<div class="form-group"><label class="col-sm-2 control-label">Referring Hospital </label>

                                    <div class="col-sm-8"><select class="form-control" name="reference_hosp" id="reference_hosp" onchange="return getrefDocDet1(this.value); ">
									<option value="">Select </option>
									<?php $select1= mysqlSelect("*","hospital_in_referral","doc_id='".$admin_id."'","hospital_name ASC","","","");
									foreach($select1 as $listDoc){ 
									?>
									<option value="<?php echo $listDoc['hos_out_ref_id']; ?>"><?php echo $listDoc['hospital_name']; ?> </option>
									
									<?php } ?>
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring hospital" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus"></i>
										</a></div>
									 <script type="text/javascript" src="date-time-picker.min.js"></script>	
								<script type="text/javascript">
												function getrefDocDet1(val) {
													$.ajax({
													type: "POST",
													url: "get_ref_doc_details.php",
													data:'ref_hosp_id='+val,
													success: function(data){
														$(".refering_doc").html(data);
													}
													});
												}
												 $(document).ready(function () {
												$('#dateadded1').datepicker({
													todayBtn: "linked",
													keyboardNavigation: false,
													forceParse: false,
													calendarWeeks: true,
													autoclose: true
												});
												$('#J-demo-08').dateTimePicker({
													mode: 'date',
													format: 'yyyy-MM-dd'
												});
												});
											</script>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referring Doctor </label>

                                    <div class="col-sm-8"><select class="form-control refering_doc" name="refering_doc" id="refering_doc" >
									<option value="">Select </option>
									
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring doctor" data-toggle="modal" data-target="#myModal3"><i class="fa fa-plus"></i>
										</a></div>
									
								
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referral Note</label>
									<div class="col-sm-4">
											<input type="file" name="txtReferalNote">
										</div></div>
										
									</div>
								<div class="form-group teleDisp1" style="display:none;">
									<label class="col-sm-2 control-label"> </label>
									
									<div class="col-sm-10">
									<input type="checkbox" class="i-checks" name="chkTeleCom" value="1"> I'm ready for teleconsultation
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"> </label>
									
									<div class="col-sm-10">
									 <input type="checkbox"  class="i-checks" name="chkPatConsent" value="1"> Patient agree for our Institute to share the EMR with Professional Health CarePartners (Diagnostic, Pharmacy)
								
									</div>
								</div>
								<?php if($checkSetting[0]['before_consultation_fee'] =="1") { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label">Consultation Charges </label>
									<div class="col-sm-4">
									<input type="text" name="consult_charge" value="<?php echo $getRefDet[0]['cons_charge'];?>" class="form-control">
									</div>
									
									<br><br>
									<div class="col-sm-8 m-t">
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkReceipt"  value="1"> Would you like to send payment receipt to patient via SMS?</label></dt><br> <br>
									</dl>
									</div>
									
								</div>
								<?php } ?>
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="direct_appointment" class="btn btn-primary block full-width m-b ">BOOK APPOINTMENT</button>
								</div>
								</div>
							
							</div>
						</form>
				
				