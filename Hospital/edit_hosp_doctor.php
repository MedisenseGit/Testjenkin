<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");



$get_docInfo = mysqlSelect("*","referal ","ref_id='".$_GET['doc_id']."'","","","","");
$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_GET['doc_id']."'","","","","");
                     
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
	<?php include('support_file.php'); ?>
	<link href="assets/plugins/jquery.fancybox-1.3.4/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" />
      <style>
        .panel-body a img {
            margin-bottom:5px !important;

        }
          .panel-body a{
              color:transparent!important;
          }
    </style>
</head>
     <!-- END HEAD -->
     <!-- BEGIN BODY -->
<body>
				<div class="col-lg-6">
					
					
				   <div class="panel panel-default">
                        <div class="panel-heading">
                            Edit Doctor
                        </div>
						<div class="panel-body">
						
                        <div class="accordion-body collapse in body">
                                    <form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal" id="frmAddDoctor" id="frmAddDoctor">
									<input type="hidden" name="Prov_Id"	value="<?php echo $_GET['doc_id']; ?>" />	
									<?php if(!empty($get_docInfo[0]['doc_photo'])){ ?>
									<div class="form-group">                                            
											 <label class="control-label col-lg-4">Profile Picture </label>
											 <div class="col-lg-8"><img src="../Doc/<?php echo $get_docInfo[0]['ref_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>" width="80" title="logo" />
                                            </div>
                                        </div>
									<?php } ?>
									<div class="form-group">                                            
											 <label class="control-label col-lg-4">Change profile picture here </label>
											 <div class="col-lg-8"><input type="file" name="txtPhoto"  value="" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Doctor Name</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtDoc" name="txtDoc" value="<?php echo $get_docInfo[0]['ref_name']; ?>"  class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="control-label col-lg-4">Category 
											</label>
											 <div class="col-lg-8">
											  <select class="form-control autotab" name="slctDocType" name="slctDocType">
												<?php if($get_docInfo[0]['doc_type']=="volunteer"){?>	
												<option value="volunteer" selected>Volunteer</option>
												<option value="star" >Star Doctor</option>
												<option value="featured" >Featured</option>
												<option value="">Select</option>
												<?php } else if($get_docInfo[0]['doc_type']=="featured"){?>	
												<option value="featured" selected>Featured</option>
												<option value="star" >Star Doctor</option>
												<option value="volunteer">Volunteer</option>
												<option value="">Select</option>
												<?php } else if($get_docInfo[0]['doc_type']=="star"){?>					
												<option value="star" selected>Star Doctor</option>
												<option value="featured">Featured</option>
												<option value="volunteer">Volunteer</option>
												<option value="">Select Doctor Type</option>
												<?php } else { ?>
												<option value="">Select</option>
												<option value="star" >Star Doctor</option>
												<option value="featured" >Featured</option>
												<option value="volunteer" >Volunteer</option>
												<?php } ?>				
								
											  </select>
											</div>
										</div>										
										<div class="form-group">
                                            <label class="control-label col-lg-4">Country</label>

                                            <div class="col-lg-8">
                                               <select class="form-control autotab" name="txtCountry" name="txtCountry" onchange="return getState(this.value);">
												<option value="<?php echo $get_docInfo[0]['doc_country']; ?>" selected><?php echo $get_docInfo[0]['doc_country']; ?></option>
												</select>
                                            </div>
                                        </div>
										
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">State</label>

                                            <div class="col-lg-8">
                                                <select class="form-control autotab" name="slctState" id="slctState" placeholder="State"  >
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
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">City</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtCity" name="txtCity" value="<?php echo $get_docInfo[0]['ref_address']; ?>" class="form-control" />
                                            </div>
                                        </div>
										
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">Select Hospital</label>

                                            <div class="col-lg-8">
                                                <select class="form-control autotab" name="selectHosp" id="selectHosp" placeholder="State"  >
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
                                        </div>
                                       <div class="form-group">
                                            <label class="control-label col-lg-4">Select Specialization</label>

                                            <div class="col-lg-8">
                                                <select class="form-control autotab" name="slctSpec" id="slctSpec" placeholder="State"  >
												<option value="" >Select Specialization</option>
												<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['doc_spec']){ ?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														$i++;
												}?> 
												</select>
											
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Qualification</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtQual" name="txtQual" value="<?php echo $get_docInfo[0]['doc_qual']; ?>"  class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Years of Experience</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtExp" name="txtExp" value="<?php echo $get_docInfo[0]['ref_exp']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Mobile No.</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtMobile" name="txtMobile" value="<?php echo $get_docInfo[0]['contact_num']; ?>" class="form-control" maxlength="10" />
                                            </div>
                                        </div>
					                     <div class="form-group">
                                            <label class="control-label col-lg-4">Email Id</label>

                                            <div class="col-lg-8">
                                                <input type="email" id="txtEmail" name="txtEmail" value="<?php echo $get_docInfo[0]['ref_mail']; ?>" class="form-control" />
                                            </div>
                                        </div> 
										<div class="form-group">
                                            <label class="control-label col-lg-4">Website</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtWebsite" name="txtWebsite" value="<?php echo $get_docInfo[0]['ref_web']; ?>" class="form-control" />
                                            </div>
                                        </div> 	
										<div class="form-group">
                                            <label class="control-label col-lg-4">Area's of Interest, Expertise</label>

                                            <div class="col-lg-8">
                                                <textarea id="txtInterest" name="txtInterest" class="form-control" rows="10" ><?php echo $get_docInfo[0]['doc_interest']; ?></textarea>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Professional Contributions</label>

                                            <div class="col-lg-8">
                                                <textarea id="txtContribute" name="txtContribute" class="form-control" rows="10" ><?php echo $get_docInfo[0]['doc_contribute']; ?></textarea>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Research Details</label>

                                            <div class="col-lg-8">
                                                <textarea id="txtResearch" name="txtResearch" class="form-control" rows="10" ><?php echo $get_docInfo[0]['doc_research']; ?></textarea>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Publications</label>

                                            <div class="col-lg-8">
                                                <textarea id="txtPublication" name="txtPublication" class="form-control" rows="10" ><?php echo $get_docInfo[0]['doc_pub']; ?></textarea>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Add Keywords</label>

                                            <div class="col-lg-8">
                                                <textarea id="txtKeywords" name="txtKeywords" class="form-control" rows="10" placeholder="Eg: Kidney Transplant Specialist, Best spine surgeon in Bangalore" ><?php echo $get_docInfo[0]['doc_keywords']; ?></textarea>
                                            </div>
                                        </div>
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">Online Opinion Cost(Rs.)</label>

                                            <div class="col-lg-2">
                                                <input type="text" id="onopcost" name="onopcost" value="<?php echo $get_docInfo[0]['on_op_cost']; ?>" class="form-control" />
                                            </div>
											<label class="control-label col-lg-4">Consultation Charge(Rs.)</label>

                                            <div class="col-lg-2">
                                                <input type="text" id="conscharge" name="conscharge" value="<?php echo $get_docInfo[0]['cons_charge']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Secretary Email Id</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtSecEmail" name="txtSecEmail" value="<?php echo $get_docInfo[0]['secretary_email']; ?>" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Secretary Phone</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtSecPhone" name="txtSecPhone" value="<?php echo $get_docInfo[0]['secretary_phone']; ?>" class="form-control" />
                                            </div>
                                        </div>
										
										<div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Ready for tele opinion ?
                          
                        </label>

                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="teleop" value="1" <?php if($get_docInfo[0]['tele_op']==1){ echo "checked"; } ?> > Yes
                            </label>
                          </div>
                        
                          
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tele Opinion contact number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="teleopnumber" name="teleopnumber" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" class="form-control" >
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Ready for video opinion ?
                          
                        </label>

                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="videoop" <?php if($get_docInfo[0]['video_op']==1){ echo "checked"; } ?> value="1"> Yes
                            </label>
                          </div>
                        
                          
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Video Opinion contact number</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="videoopnumber" name="videoopnumber" value="<?php echo $get_docInfo[0]['video_op_contact']; ?>" class="form-control" >
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Available timings for Tele/Video Opinion</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" id="televidop_time" name="televidop_time" value="<?php echo $get_docInfo[0]['tele_video_op_timing']; ?>" class="form-control" >
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12">Set Doctor Timing <span class="required">*</span></label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <table border="1" width="100%">
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
												<input type="checkbox" checked="true" value="1" name="<?php echo "time". $i . $j ?>">
												<?php } else { ?>
												<input type="checkbox" value="1" name="<?php echo "time". $i . $j ?>">
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
						</table>
                        </div>
                      </div>
										                  
                                        <div class="form-actions no-margin-bottom" style="text-align:center;">
                                            <input type="submit" value="UPDATE" name="edit_doctor" id="edit_doctor" class="btn btn-primary btn-lg " />
                                        </div>
										 
                                    </form>
                                </div>
							</div>
                    </div>
					</div>
					
					</body>
     <!-- END BODY -->
</html>		