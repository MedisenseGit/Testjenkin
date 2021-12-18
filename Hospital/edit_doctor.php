<?php
ob_start();
error_reporting(0); 
session_start();

$hosp_id=$_GET['hosp_id'];

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");


 

if(isset($_POST['cmdGetId'])){
	$bus_id = $_POST['user_id'];
	$_SESSION['trans_id']=$_POST['user_id'];
	header('location:view');	
}


$get_docInfo = mysqlSelect("*","referal ","ref_id='".$_GET['doc_id']."'","","","","");
$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_GET['doc_id']."'","","","","");
$GetTimeSlot = mysqlSelect("*", "doc_appointment_slots", "doc_id='".$_GET['doc_id']."' and doc_type='1' and hosp_id='".$get_provHospInfo[0]['hosp_id']."'", "", "", "", "");  
                     
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

 <!-- BEGIN HEAD -->
<head>
     <meta charset="UTF-8" />
    <title></title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
    <!-- GLOBAL STYLES -->
	<?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

	
</head>
     <!-- END HEAD -->
     <!-- BEGIN BODY -->
<body>
<div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Doctor List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Doctor List</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			 <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">										 
				<div class="ibox float-e-margins" id="addHospSection">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> EDIT HOSPITAL DOCTOR</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <input type="hidden" name="Prov_Id"	value="<?php echo $_GET['doc_id']; ?>" />
							   <div class="form-group"><label class="col-sm-2 control-label">Doctor Profile picture</label>
									
                                        
                                    <div class="col-sm-10 pull-right"><img src="../Doc/<?php echo $get_docInfo[0]['ref_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>" width="80" title="logo" />
									<input type="file"  name="txtPhoto">
                                       Change Profile picture
                                   </div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Doctor Name </label>

                                    <div class="col-sm-10"><input type="text" name="txtDoc" value="<?php echo $get_docInfo[0]['ref_name']; ?>" class="form-control"></div>
                                </div>
								<script type="text/javascript">
									function getState(val) { 
										var data_val = $("#txtCountry option:selected").attr("myTag");
										$('#cntryid').val(data_val);
										$.ajax({
										type: "POST",
										url: "get_state.php",
										data:'country_name='+val,
										success: function(data){
											$("#slctState").html(data);
										}
										});
									}
								</script>
							   <div class="form-group"><label class="col-sm-2 control-label">Country </label>

                                    <div class="col-sm-10">
                                    	<input type="hidden" id="cntryid" name="countryId">
                                    	<select class="form-control autotab" name="txtCountry" id="txtCountry" onchange="return getState(this.value);">
												<option value="<?php echo $get_docInfo[0]['doc_country']; ?>" selected><?php echo $get_docInfo[0]['doc_country']; ?></option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" myTag="<?php echo stripslashes($CountryList['country_id']); ?> " />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
												</select>
									</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label">State</label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="slctState" id="slctState" placeholder="State"  >
												<option value="<?php echo $get_docInfo[0]['doc_state']; ?>" selected><?php echo $get_docInfo[0]['doc_state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$get_docInfo[0]['doc_country']."'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo $StateList["state_name"];	?>">
												<?php echo $StateList["state_name"]; ?>
												</option>												
												<?php
												}
												?>
												</select>
									</div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">City</label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" value="<?php echo $get_docInfo[0]['ref_address']; ?>" class="form-control"></div>
                                </div>
								<!--<div class="form-group"><label class="col-sm-2 control-label">Select Hospital</label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="selectHosp" id="selectHosp" placeholder="State"  >
												<option value="" selected>---Please Select---</option>
												<?php
													$HospName= mysqlSelect("*","hosp_tab","company_id='".$admin_id."'","hosp_id desc","","","");
													$i=30;
														foreach($HospName as $HospList){
															if($HospList['hosp_id']==$get_provHospInfo[0]['hosp_id']){ 
																?>
														   <option value="<?php echo stripslashes($HospList['hosp_id']);?>" selected>
															<?php echo stripslashes($HospList['hosp_name']);?></option>
															<?php } ?>
															<option value="<?php echo stripslashes($HospList['hosp_id']);?>" />
															<?php echo $HospList['hosp_name']."&nbsp;".$HospList['hosp_city']; ?></option>												
														
														<?php 	$i++;
														}?>  
												</select>
									</div>
                                </div>-->
								<div class="form-group"><label class="col-sm-2 control-label">Select Specialization </label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="slctSpec" id="slctSpec" placeholder="State"  >
												<option value="" >Select Specialization</option>
												<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['doc_spec']){ ?> 
												<!--<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>-->
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" <?php if($DeptList['spec_id']==$get_docInfo[0]['doc_spec']){ echo "selected"; } ?> /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														$i++;
												}?> 
												</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Qualification </label>

                                    <div class="col-sm-10"><input type="text" name="txtQual" value="<?php echo $get_docInfo[0]['doc_qual']; ?>" class="form-control"></div>
                                
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Years of Experience</label>

                                    <div class="col-sm-6"><input type="text" class="form-control" name="txtExp" value="<?php echo $get_docInfo[0]['ref_exp']; ?>"></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" value="<?php echo $get_docInfo[0]['ref_mail']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Mobile No.</label>

                                    <div class="col-sm-10"><input type="text" name="txtMobile" value="<?php echo $get_docInfo[0]['contact_num']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Website</label>

                                    <div class="col-sm-10"><input type="text" name="txtWebsite" value="<?php echo $get_docInfo[0]['ref_web']; ?>" class="form-control"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Area's of Interest, Expertise</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtInterest" rows="3"><?php echo $get_docInfo[0]['doc_interest']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Professional Contributions</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtContribute" rows="3"><?php echo $get_docInfo[0]['doc_contribute']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Research Details</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtResearch" rows="3"><?php echo $get_docInfo[0]['doc_research']; ?></textarea></div>
                                </div>	
								<div class="form-group"><label class="col-sm-2 control-label">Publications</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtPublication" rows="3"><?php echo $get_docInfo[0]['doc_pub']; ?></textarea></div>
                                </div>	
								<div class="form-group"><label class="col-sm-2 control-label">Online Opinion Cost(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="onopcost" value="<?php echo $get_docInfo[0]['on_op_cost']; ?>"></div>
									<label class="col-sm-2 control-label">Consultation Charge(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" name="conscharge" value="<?php echo $get_docInfo[0]['cons_charge']; ?>" class="form-control"></div>
								
                                </div>	
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Email Id</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecEmail" value="<?php echo $get_docInfo[0]['secretary_email']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Phone</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecPhone" value="<?php echo $get_docInfo[0]['secretary_phone']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for tele opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="teleop" value="1" <?php if($get_docInfo[0]['tele_op']==1){ echo "checked"; } ?> > Yes
								</label><input type="text" name="teleopnumber" class="form-control" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" placeholder="Tele Op. contact no."></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for video opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="videoop" value="1" <?php if($get_docInfo[0]['video_op']==1){ echo "checked"; } ?>> Yes
								</label><input type="text" id="videoopnumber" name="videoopnumber" class="form-control" value="<?php echo $get_docInfo[0]['video_op_contact']; ?>" placeholder="Video Op. contact no."></div>
								</div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Available timings for Tele/Video Opinion</label>

                                    <div class="col-sm-10"><input type="text" name="televidop_time" value="<?php echo $get_docInfo[0]['tele_video_op_timing']; ?>" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">No. of Patient per hour <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="number" name="num_slot" value="<?php echo $GetTimeSlot[0]['num_patient_hour']; ?>"  class="form-control" required="required"></div>
                                </div>
                               <div class="form-group"><label class="col-sm-2 control-label">Set Appointment Timing</label>

                                    <div class="col-sm-10"><table border="1" width="100%">
						<tr>
						<td style="text-align:center; font-weight:bold;">Schedule</td>
						<?php
						$getDays = mysqlSelect("*","seven_days ","","","","","");


							foreach($getDays as $daysList) 
							{
								?>
								<td style="text-align:center; font-weight:bold;"><?php echo $daysList['da_name']; ?></td>
								<?php
							}

						?>
						</tr>
						<?php

						$getTimings = mysqlSelect("*","timings ","","","","","");
							$i=0;
							foreach($getTimings as $timeList) 
							{
								
								
								$i++;
								$j=0;
								?>
								<tr>
								<td style="text-align:center; "><input type="hidden" name="<?php echo "time_id" .$i ?>" value="<?php echo $timeList['Timing_id']?>" /><?php echo $timeList['Timing']?></td>
								<?php
								$getDaycount = mysqlSelect("*","seven_days ","","","","","");
								foreach($getDaycount as $countList) 
									{
										
										$j++;
										 $chkDay = mysqlSelect("*","doc_time_set","doc_id=".$_GET['doc_id']." and time_set=1 and day_id=".$j." and time_id=".$i,"","","","");
										?> 	
											 <td style="text-align:center;">
											
											 <input type="hidden" size="4" value="<?php echo $countList['day_id'] ?>" name="<?php echo "day_id" . $i . $j ?>">
												<?php if($chkDay==true){ ?>
												<div class="checkbox checkbox-success checkbox-inline">
													<input type="checkbox" id="inlineCheckbox2" checked="true" value="1" name="<?php echo "time". $i . $j ?>">
													<label for="inlineCheckbox2"></label>
												</div>
												
												<?php } else { ?>
												<div class="checkbox checkbox-success checkbox-inline">
													<input type="checkbox" id="inlineCheckbox<?php echo $j; ?>" value="1" name="<?php echo "time". $i . $j ?>">
													<label for="inlineCheckbox2"></label>
												</div>
												
												<?php } ?>
												<input type="hidden" name="limit_j" value="<?php echo $j; ?>" size="4">
												</td>
											
										<?php
									}
									
									
							
								?></tr>
								<input type="hidden" name="limit_i" value="<?php echo $i; ?>" size="4">
								<?php
								

							}


						?>
						</table></div>
                                </div>
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="edit_doctor" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
							</form>
							</div>
							
							
							
							
                    </div>
                </div>
			</div>
			</div>
		</div>
	 </div>
	</div>
	<!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>												  
					</body>
     <!-- END BODY -->
</html>		