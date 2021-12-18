<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
//$sub_comapny_name = $_SESSION[$patient_name];


include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");


$cudate=date('Y-m-d');
//var_dump($_GET); exit;
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
	$get_PatientDetails = mysqlSelect("id as patient_id,subscribing_company_id as subscribing_company_id, employee_name as patient_name,email_id as patient_email,mobile_num as patient_mob, city as patient_loc, state as pat_state, country as pat_country, address as patient_addrs","subscribers","id='".$params[0]."'","","","","");	 
	
	//echo'<pre>';var_dump($get_PatientDetails);  echo '</pre>'; exit;

		$patient_name = $get_PatientDetails[0]['patient_name'];
		$_SESSION['name'] = $patient_name;
		$patient_id=$get_PatientDetails[0]['patient_id'];
		$sub_company_id=$get_PatientDetails[0]['subscribing_company_id'];
		
	}
	
			
}	
$getRefDet = mysqlSelect("doc_state,ref_address,cons_charge","referal","ref_id='".$get_PatientDetails[0]['doc_id']."'","","","","");    

$getEmpName = mysqlSelect("*","subscribers t1,subscribing_company t2","t1.subscribing_company_id = t2.id and t2.id='".$params[0]."'","","","","");


/*$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");*/

$patient_episodes = mysqlSelect("*","doc_patient_episodes","admin_id = '". $get_PatientDetails[0]['doc_id']."' and patient_id = '". $get_PatientDetails[0]['patient_id'] ."' "," episode_id DESC ","","","");
	
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
$(document).ready(function(){
	
	  if($("input[type='radio'][name='appointment_type']:checked").val()=="1"){
		$("#date_section1").hide();  
	  }
	  else{
		  $("#date_section1").show();
	  }
  });
</script>
				<form enctype="multipart/form-data" method="post"  action="add_details.php"  name="frmAddPatient" >
				<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>" />				
					<div class="form-horizontal">
							
							<?php if(count($patient_episodes)>0){ ?>
							<div class="form-group col-sm-12">
							<small class="pull-right">Last Visited On: <font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($patient_episodes[0]['date_time'])); ?></font></small>
							</div>
							<?php } ?>
							<!-- <h1><?php  //echo "hi". $_SESSION['name'] ?></h1> -->
								<!-- start extra  -->
								<div class="form-group"  style="padding-top: 30px;"><label class="col-sm-2 control-label">Subscribe Company <span class="required">*</span></label>

                                    <div class="col-sm-8"><select data-placeholder="Choose a Subscribe Company..." class="form-control" name="se_sub_comp"  tabindex="2" >
                                    	<?php //if(!empty($get_PatientDetails[0]['patient_name'])){ ?>
											<!--<option value="<?php echo $get_PatientDetails[0]['patient_name']; ?>"><?php echo $get_PatientDetails[0]['patient_name']; ?></option>-->
													<?php //} else { ?>

											<option value="" >Choose Subscribe Company</option>
												<?php 
												$getSubComp= mysqlSelect("*","subscribing_company","","company_name asc","","","");
													$i=30;
													foreach($getSubComp as $SubCompList){
												?> 
														
														<option value="<?php echo stripslashes($SubCompList['id']); ?>" <?php if($sub_company_id==$SubCompList['id']){ ?>selected<?php } ?>>
														<?php echo stripslashes($SubCompList['company_name']);?></option>
													
													
													<?php 
														$i++;
													//}
												}?> 
										</select>
									</div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new reference doctor" data-toggle="modal" data-target="#myModal5"><i class="fa fa-plus"></i>
										</a></div>
								</div>
								 <div class="form-group" ><label class="col-sm-2 control-label">Choose Hospital <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose doctor..." class="form-control" name="se_hosp" id="se_hosp1" required="required"  tabindex="2" onchange="return getDocDet1(this.value); ">
											<option value="" selected >Choose Hospital</option>
												<?php 
												$hospResult = mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hosp_Name,hosp_city as Hosp_City,hosp_state as Hosp_State","hosp_tab","company_id='".$admin_id."'","hosp_id desc","","","");
	$i=30;
													foreach($hospResult as $hospList){
												?> 
														
														<option value="<?php echo stripslashes($hospList['Hosp_Id']); ?>" >
														<?php echo stripslashes($hospList['Hosp_Name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                                </div>
									
									<!-- <div class="form-group" id="doctor_section" style="display:none;"> -->
										<div class="form-group" id="doctor_section">
									 
									 <label class="col-sm-2 control-label">Choose Doctor <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose doctor..." class="form-control" name="se_doc" id="se_doc1" required="required"  tabindex="2" onchange="return getDocDate1(this.value);">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join specialization as d on d.spec_id=a.doc_spec","c.hosp_id='".$admin_id."'","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" >
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                               </div>
							   <div class="form-group" id="date_section1" style="display:none;">
									<label class="col-sm-2 control-label">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Date..." class="form-control" name="check_date" id="check_date1" tabindex="2" onchange="return getDocTiming1(this.value); ">
											<option disabled="disabled" selected value="0">Select Date</option>
                                   
										</select></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time" id="check_time1"  tabindex="2">
											
										</select></div>
                                </div>
								<!-- added extra 27/04 -->
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" value="<?php //echo $patient_name;?> <?php  echo $get_PatientDetails[0]['patient_name']?>" name="se_pat_name" required="required" class="form-control" ></div>
                                
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

                                    <div class="col-sm-8"><select class="form-control" name="reference_hosp" id="reference_hosp" onchange="return getrefDocDet(this.value); ">
									<option value="">Select </option>
									<?php $select1= mysqlSelect("*","hospital_in_referral","doc_id='".$admin_id."'","hospital_name ASC","","","");
									foreach($select1 as $listDoc){ 
									?>
									<option value="<?php echo $listDoc['hos_out_ref_id']; ?>"><?php echo $listDoc['hospital_name']; ?> </option>
									
									<?php } ?>
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring hospital" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus"></i>
										</a></div>
									
								<script type="text/javascript">
												function getrefDocDet(val) {
													$.ajax({
													type: "POST",
													url: "get_ref_doc_details.php",
													data:'ref_hosp_id='+val,
													success: function(data){
														$("#refering_doc").html(data);
													}
													});
												}
											</script>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referring Doctor </label>

                                    <div class="col-sm-8"><select class="form-control" name="refering_doc" id="refering_doc" >
									<option value="">Select </option>
									
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring doctor" data-toggle="modal" data-target="#myModal3"><i class="fa fa-plus"></i>
										</a></div>
									
								
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referral Note</label>
									<div class="col-sm-4">
											<input type="file" name="txtReferalNote">
										</div></div>
										
									</div>
								<div class="form-group">
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
								<button type="submit" name="corporate_appointment" class="btn btn-primary block full-width m-b ">BOOK APPOINTMENT</button>
								</div>
								</div>
							
							</div>
						</form>
				<script>
				