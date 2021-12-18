<?php
ob_start();
error_reporting(0); 
session_start();
$admin_id = $_SESSION['user_id'];

if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$get_docInfo = $objQuery->mysqlSelect("partner_id,doc_photo,contact_person,specialisation,Address,location,state,country,partner_name,doc_qual,ref_exp,cont_num1,Email_id,website,doc_interest,doc_contribute,doc_research,doc_pub,in_op_cost,on_op_cost,cons_charge,tele_op,tele_op_contact,video_op,video_op_contact,tele_video_op_timing,secretary_phone,secretary_email","our_partners","partner_id='".$admin_id."'","","","","");
$get_docSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_docInfo[0]['specialisation']."'","","","","");
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Profile</title>
 <?php include('support_file.php'); ?>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Profile</h3>
              </div>

             
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
						  <?php if(!empty($get_docInfo[0]['doc_photo'])){ ?>
                          <img class="img-responsive avatar-view" src="partnerProfilePic/<?php echo $get_docInfo[0]['partner_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>" alt="Profile Picture" title="Profile Picture">
						  <?php } else { ?>
							<img class="img-responsive avatar-view" src="images/user.png" alt="Profile Picture" title="Profile Picture">
						  <?php } ?>  
						  
						</div>
                      </div>
                        <h3><?php echo $get_docInfo[0]['contact_person']; ?></h3>

                      <ul class="list-unstyled user_data">
                        <li><i class="fa fa-map-marker user-profile-icon"></i> <?php if(!empty($get_docInfo[0]['partner_name'])){ echo $get_docInfo[0]['partner_name']; } if(!empty($get_docInfo[0]['location'])){ echo ", ".$get_docInfo[0]['location'];} if(!empty($get_docInfo[0]['state'])){ echo ", ".$get_docInfo[0]['state']; } if(!empty($get_docInfo[0]['country'])){ echo ", ".$get_docInfo[0]['country']; } ?>
                        </li>
						<?php if($get_docSpec[0]['spec_name']!=0) { ?>
                        <li>
                          <i class="fa fa-briefcase user-profile-icon"></i> <?php echo $get_docSpec[0]['spec_name']; ?>
                        </li>
						
						<?php } if(!empty($get_docInfo[0]['cont_num1'])) { ?>
						<li>
                          <i class="fa fa-phone user-profile-icon"></i> <?php echo $get_docInfo[0]['cont_num1']; ?>
                        </li>
						<?php } 
						if(!empty($get_docInfo[0]['Email_id'])) {
						?>
						<li>
                          <i class="fa fa-envelope user-profile-icon"></i> <?php echo $get_docInfo[0]['Email_id']; ?>
                        </li>
						
						<?php } 
						if(!empty($get_docInfo[0]['website'])) {
						?>
                        <li class="m-top-xs">
                          <i class="fa fa-external-link user-profile-icon"></i>
                          <a href="<?php echo $get_docInfo[0]['website']; ?>" target="_blank"><?php echo $get_docInfo[0]['website']; ?></a>
                        </li>
						<?php } ?>
                      </ul>

                      
                      <br />
						 <br />

                      <!-- start Interest/Expertise -->
                      <?php if(!empty($get_docInfo[0]['doc_interest'])){ ?>
					  <h4><b>Area's of Interest/Expertise</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($get_docInfo[0]['doc_interest']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Interest/Expertise -->
					 <!-- start Contribution -->
                      <?php if(!empty($get_docInfo[0]['doc_contribute'])){ ?>
					 <h4><b>Professional Contribution</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($get_docInfo[0]['doc_contribute']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Contribution -->
					  
					  <!-- start Publications -->
                      <?php if(!empty($get_docInfo[0]['doc_pub'])){ ?>
					 <h4><b>Publications</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($get_docInfo[0]['doc_pub']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Contribution -->
                      

                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">

                      
                      <!-- start of user-activity-graph -->
                      <!--<div id="graph_bar" style="width:100%; height:280px;"></div>-->
					  
					  
					  
                      <!-- end of user-activity-graph -->

                      <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
						  <li role="presentation" class="active"><a href="#tab_content1" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">EDIT PROFILE</a>
                          </li>
                          <li role="presentation" class=""><a href="#tab_content2" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">CHANGE PASSWORD</a>
                          </li>
                                                   
                        </ul>
                        <div id="myTabContent" class="tab-content">
                          <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
								<?php if($_GET['response']=="password") {  ?>
							<center><h4><span style="color:green; font-weight:bold;"><i class="fa fa-check"></i> Password updated successfully</span><br></h4></center>
							<?php } if($_GET['response']=="update") {  ?>
							<center><h4><span style="color:green; font-weight:bold;"><i class="fa fa-check"></i> Profile updated successfully</span><br></h4></center>
							<?php } if($_GET['response']=="error-password") {  ?>
							<center><h4><span style="color:red; font-weight:bold;"><i class="fa fa-exclamation-triangle"></i> Password mismatch, please try again</span><br></h4></center>
							<?php } ?>
								<form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal" id="frmAddDoctor">
									<input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />	
								
									<div class="form-group">                                            
											 <label class="control-label col-lg-4">Change profile picture here </label>
											 <div class="col-lg-8"><input type="file" name="txtPhoto"  value="" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Doctor Name</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtDoc" name="txtDoc" value="<?php echo $get_docInfo[0]['contact_person']; ?>"  class="form-control" />
                                            </div>
                                        </div>
																				
										<div class="form-group">
                                            <label class="control-label col-lg-4">Country</label>

                                            <div class="col-lg-8">
                                               <select class="form-control autotab" name="txtCountry" name="txtCountry" onchange="return getState(this.value);">
												<option value="<?php echo $get_docInfo[0]['country']; ?>" selected><?php echo $get_docInfo[0]['country']; ?></option>
												<?php 
												$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														
														$i = 30;
														foreach ($CntName as $CntNameList) {
														?> 
																								
															<option value="<?php echo stripslashes($CntNameList['country_name']); ?>" />
														<?php
															echo stripslashes($CntNameList['country_name']);
														?></option>
																										
														<?php
															$i++;
														}
														?>
														 </select>
                                            </div>
                                        </div>
										
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">State</label>

                                            <div class="col-lg-8">
                                                <select class="form-control autotab" name="slctState" id="slctState" placeholder="State"  >
												<option value="<?php echo $get_docInfo[0]['state']; ?>" selected><?php echo $get_docInfo[0]['state']; ?></option>
												<?php
												$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$get_docInfo[0]['country']."'", "b.state_name asc", "", "", "");
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
                                                <input type="text" id="txtCity" name="txtCity" value="<?php echo $get_docInfo[0]['location']; ?>" class="form-control" />
                                            </div>
                                        </div>
										
										
										<div class="form-group">
                                            <label class="control-label col-lg-4">Select Hospital</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="selectHosp" name="selectHosp" value="<?php echo $get_docInfo[0]['partner_name']; ?>" class="form-control" />
											
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <label class="control-label col-lg-4">Select Specialization</label>

                                            <div class="col-lg-8">
                                                <select class="form-control autotab" name="slctSpec" id="slctSpec" placeholder="State"  >
												<option value="" >Select Specialization</option>
												<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['specialisation']){ ?> 
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
                                                <input type="text" id="txtMobile" name="txtMobile" value="<?php echo $get_docInfo[0]['cont_num1']; ?>" class="form-control" maxlength="10" />
                                            </div>
                                        </div>
					                     <div class="form-group">
                                            <label class="control-label col-lg-4">Email Id</label>

                                            <div class="col-lg-8">
                                                <input type="email" id="txtEmail" name="txtEmail" value="<?php echo $get_docInfo[0]['Email_id']; ?>" class="form-control" />
                                            </div>
                                        </div> 
										<div class="form-group">
                                            <label class="control-label col-lg-4">Website</label>

                                            <div class="col-lg-8">
                                                <input type="text" id="txtWebsite" name="txtWebsite" value="<?php echo $get_docInfo[0]['website']; ?>" class="form-control" />
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
										
										<!--<div class="form-group">
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
                        <label class="control-label col-lg-4">Ready for tele opinion ?
                          
                        </label>

                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="teleop" value="1" <?php if($get_docInfo[0]['tele_op']==1){ echo "checked"; } ?> > Yes
                            </label>
                          </div>
                        
                          
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-lg-4">Tele Opinion contact number</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" id="teleopnumber" name="teleopnumber" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" class="form-control" >
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-lg-4">Ready for video opinion ?
                          
                        </label>

                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" class="flat" name="videoop" <?php if($get_docInfo[0]['video_op']==1){ echo "checked"; } ?> value="1"> Yes
                            </label>
                          </div>
                        
                          
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="control-label col-lg-4">Video Opinion contact number</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" id="videoopnumber" name="videoopnumber" value="<?php echo $get_docInfo[0]['video_op_contact']; ?>" class="form-control" >
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label class="control-label col-lg-4">Available timings for Tele/Video Opinion</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" id="televidop_time" name="televidop_time" value="<?php echo $get_docInfo[0]['tele_video_op_timing']; ?>" class="form-control" >
                        </div>
                      </div>-->
					  
					  <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12">Set Doctor Timing <span class="required">*</span></label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <table border="1" width="100%">
						<tr>
						<td style="text-align:center; font-weight:bold;">Schedule</td>
						<?php
						$getDays = $objQuery->mysqlSelect("*","seven_days ","","","","","");


							foreach($getDays as $daysList) 
							{
								?>
								<td style="text-align:center; font-weight:bold;"><?php echo $daysList['da_name']; ?></td>
								<?php
							}

						?>
						</tr>
						<?php

						$getTimings = $objQuery->mysqlSelect("*","timings ","","","","","");
							$i=0;
							foreach($getTimings as $timeList) 
							{
								
								
								$i++;
								$j=0;
								?>
								<tr>
								<td style="text-align:center; "><input type="hidden" name="<?php echo "time_id" .$i ?>" value="<?php echo $timeList['Timing_id']?>" /><?php echo $timeList['Timing']?></td>
								<?php
								$getDaycount = $objQuery->mysqlSelect("*","seven_days ","","","","","");
								foreach($getDaycount as $countList) 
									{
										
										$j++;
										 $chkDay = $objQuery->mysqlSelect("*","ref_doc_time_set","doc_id=".$admin_id." and time_set=1 and day_id=".$j." and time_id=".$i,"","","","");
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
                                            <input type="submit" value="UPDATE" name="edit_profile" id="edit_profile" class="btn btn-primary btn-lg " />
                                        </div>
										 
                                    </form>

                          </div>
                         
						  
						  <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
							<br><br>
								
                          <form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal">
									<input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />	
								
									<div class="form-group">
                                            <label class="control-label col-lg-4">New Password</label>

                                            <div class="col-lg-6">
                                                <input type="password" id="new_password" name="new_password" value="" required="required" class="form-control" />
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="control-label col-lg-4">Retype Password</label>

                                            <div class="col-lg-6">
                                                <input type="password" id="retype_password" name="retype_password" required="required" value=""  class="form-control" />
                                            </div>
                                        </div>
										<br>
										                  
                                        <div class="form-actions no-margin-bottom" style="text-align:center;">
                                            <input type="submit" value="UPDATE" name="change_password" id="change_password" class="btn btn-primary btn-lg " />
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
          </div>
        </div>
        <!-- /page content -->

       <?php include_once('footer.php'); ?>
      </div>
    </div>

    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- morris.js -->
    <script src="../Hospital/vendors/raphael/raphael.min.js"></script>
    <script src="../Hospital/vendors/morris.js/morris.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../Hospital/vendors/moment/min/moment.min.js"></script>
    <script src="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    
    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>