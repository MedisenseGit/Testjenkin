<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


$get_docInfo = $objQuery->mysqlSelect("partner_id,doc_photo,contact_person,doc_gen,specialisation,Address,location,state,country,partner_name,doc_qual,ref_exp,cont_num1,Email_id,website,doc_interest,doc_contribute,doc_research,doc_pub,in_op_cost,on_op_cost,cons_charge,tele_op,tele_op_contact,video_op,video_op_contact,tele_video_op_timing,secretary_phone,secretary_email","our_partners","partner_id='".$admin_id."'","","","","");
$get_docSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_docInfo[0]['specialisation']."'","","","","");
               
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
			
                <div class="col-lg-10">
                    <h2>Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Your Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
		
							<div>
							<?php if($_GET['response']=="update") {  ?>
							<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <i class="fa fa-check"></i><a class="alert-link" href="#">Profile updated successfully </a>.
                            </div>
							<?php } if($_GET['response']=="update-failure") {  ?>
							<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">Error!!!</a> Failed to update </a>.
                            </div>
							<?php } if($_GET['response']=="password_updated") {  ?>
							<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#"> Password updated successfully </a>.
                            </div>
							<?php } if($_GET['response']=="password_failed") {  ?>
							<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#"> Password mismatch. Failed to update </a>.
                            </div>
							<?php } ?>		
							
							</div>
		
            <div class="row animated fadeInRight">
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							<?php if(!empty($get_docInfo[0]['doc_photo'])){ ?>
                                <img alt="image" class="img-xlg img-responsive" src="partnerProfilePic/<?php echo $get_docInfo[0]['partner_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>">
                             <?php }  else { ?>
							 <img alt="image" class="img-xlg img-responsive" src="../assets/img/anonymous-profile.png">
                             <?php  } ?>
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $get_docInfo[0]['contact_person']; ?></strong><br><br><?php echo $get_docSpec[0]['spec_name']; ?></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($get_docInfo[0]['partner_name'])){ echo $get_docInfo[0]['partner_name']; } if(!empty($get_docInfo[0]['location'])){ echo ", ".$get_docInfo[0]['location'];} if(!empty($get_docInfo[0]['state'])){ echo ", ".$get_docInfo[0]['state']; } if(!empty($get_docInfo[0]['country'])){ echo ", ".$get_docInfo[0]['country']; } ?></p>
                                <br><h4><strong>
                                    About <?php echo $get_docInfo[0]['contact_person']; ?>
                                </strong></h4>
                                <p><?php if(!empty($get_docInfo[0]['ref_exp'])){ ?><b>Exp:</b> <?php echo $get_docInfo[0]['ref_exp']; ?> Yrs<br><?php } ?>
								   
								  <!-- start Interest/Expertise -->
								<?php if(!empty($get_docInfo[0]['doc_interest'])){ ?>
								 <br><b>Area's of Interest/Expertise</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_interest']); ?><br>
								  <?php } ?>
								 <!-- end of Interest/Expertise -->
								 
								 <?php if(!empty($get_docInfo[0]['doc_contribute'])){ ?>
								 <br><b>Professional Contribution</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_contribute']); ?><br>
								 <?php } ?>
								<!-- end of Contribution -->
								
								 <?php if(!empty($get_docInfo[0]['doc_pub'])){ ?>
								 <br><b>Publications</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_pub']); ?><br>
								 <?php } ?>
								<!-- end of Publications -->
								
								
                                </p>
                              
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
							
                                <div class="panel-body">
                               
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" action="add_details.php" method="post" id="frmAddDoctor"  name="frmAddPatient" >
                                <div class="form-group"><label class="col-sm-2 control-label">Profile picture</label>

                                    <div class="col-sm-10">
                                        <!-- <input type="file" id="inputImage" name="txtPhoto" class="hide"> -->
										 <input type="file" type="file" name="txtPhoto"  value=""  class="form-control" />
                                        <i class="fa fa-upload"></i> Upload profile picture
                                    </div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-4"><input type="text" id="txtDoc" name="txtDoc" value="<?php echo $get_docInfo[0]['contact_person']; ?>" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Years of Exp.</label>

                                    <div class="col-sm-4"><input type="text" id="txtExp" name="txtExp" value="<?php echo $get_docInfo[0]['ref_exp']; ?>" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" id="txtMobile" name="txtMobile" value="<?php echo $get_docInfo[0]['cont_num1']; ?>" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input id="txtEmail" name="txtEmail" value="<?php echo $get_docInfo[0]['Email_id']; ?>" class="form-control"></div>
								
                                </div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender </label>
                                      <div class="col-sm-10">  
									  <?php if($get_docInfo[0]['doc_gen']==1){ ?>
									  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="radioInline" checked="">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="radioInline">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									  <?php } else{ ?>
									  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="radioInline">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="radioInline"  checked="">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									  <?php } ?>
										</div>
								</div>		
								<div class="form-group"><label class="col-sm-2 control-label">Country </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="txtCountry"  tabindex="2" onchange="return getState(this.value);">
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
								<div class="form-group"><label class="col-sm-2 control-label">State </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" name="slctState" id="slctState" tabindex="2">
											<option value="<?php echo $get_docInfo[0]['state']; ?>" selected><?php echo $get_docInfo[0]['state']; ?></option>
												<?php
												$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id='100'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo stripslashes($StateList["state_name"]);	?>">
												<?php echo stripslashes($StateList["state_name"]); ?>
												</option>												
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City </label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" id="txtCity" value="<?php echo $get_docInfo[0]['location']; ?>" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Hospital Name</label>

                                    <div class="col-sm-10"><input type="text" id="selectHosp" name="selectHosp" value="<?php echo $get_docInfo[0]['partner_name']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Specialization <span class="required">*</span></label>

                                    <div class="col-sm-10"><select  class="chosen-select"  name="slctSpec" id="slctSpec" tabindex="2">
											<option value="" >Select Specialization</option>
												<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
												
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['specialisation']){ ?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														
												}?> 
												</select>
											
											</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Qualification</label>

                                    <div class="col-sm-10"><input type="text" id="txtQual" name="txtQual" value="<?php echo $get_docInfo[0]['doc_qual']; ?>" class="form-control"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Website</label>

                                    <div class="col-sm-10"><input type="text" id="txtWebsite" name="txtWebsite" value="<?php echo $get_docInfo[0]['website']; ?>"  class="form-control"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Area's of Interest, Expertise</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtInterest" name="txtInterest" rows="3"><?php echo $get_docInfo[0]['doc_interest']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Professional Contributions</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtContribute" name="txtContribute" rows="3"><?php echo $get_docInfo[0]['doc_contribute']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Research Details</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtResearch" rows="3"><?php echo $get_docInfo[0]['doc_research']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Publications</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtPublication" name="txtPublication" rows="3"><?php echo $get_docInfo[0]['doc_pub']; ?></textarea></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Set Appointment Timing</label>

                                    <div class="col-sm-10"><table border="1" width="100%">
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
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_profile" id="edit_profile" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
							</form>
							
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">New Password <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="password" id="new_password" name="new_password" required="required" class="form-control"></div>
                                
									
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Retype Password <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="password" id="retype_password" name="retype_password" required="required" class="form-control"></div>
                                
									
                                </div>
								
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="change_password" id="change_password" class="btn btn-primary block full-width m-b ">UPDATE</button>
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
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>
 <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
</body>

</html>
