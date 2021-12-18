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


$getPartnerInfo= $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","","");
              
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Refer Patient to Doctor</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../Hospital/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
   
    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include_once('side_menu.php'); ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		   <?php //include_once('header_top_nav.php'); ?>
           

            <div class="clearfix"></div>

				<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-user"></i> Profile</h2>
                    <div class="right">
               
              </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
						   <?php if(!empty($getPartnerInfo[0]['doc_photo'])){ ?>
								<img src="https://medisensecrm.com/Doc/<?php echo $getPartnerInfo[0]['ref_id']; ?>/<?php echo $getPartnerInfo[0]['doc_photo']; ?>" class="img-responsive avatar-view" width="130"/>
							 <?php }  else { ?>
								 <img src="images/user.png" class="img-responsive avatar-view"/>
							<?php  } ?>
                         
                        </div>
                      </div>
                      <h3><?php echo $getPartnerInfo[0]['partner_name']; ?></h3>

                      <ul class="list-unstyled user_data">
                        <li><i class="fa fa-map-marker user-profile-icon"></i> <?php echo $getPartnerInfo[0]['Address']." ".$getPartnerInfo[0]['location']." ".$getPartnerInfo[0]['state']." ".$getPartnerInfo[0]['country']; ?>
                        </li>
						 <li><i class="fa fa-phone user-profile-icon"></i> <?php echo $getPartnerInfo[0]['cont_num1']; ?>
                        </li>
						 <li><i class="fa fa-envelope user-profile-icon"></i> <?php echo $getPartnerInfo[0]['Email_id']; ?>
                        </li>

                        <li>
                          <i class="fa fa-briefcase user-profile-icon"></i> <?php echo $getPartnerInfo[0]['person_position']; ?>
                        </li>

                        <li class="m-top-xs">
                          <i class="fa fa-external-link user-profile-icon"></i>
                          <a href="<?php echo $getPartnerInfo[0]['website']; ?>" target="_blank"><?php echo $getPartnerInfo[0]['website']; ?></a>
                        </li>
                      </ul>

                      
                      <br />

                      <!-- start Interest/Expertise -->
                      <?php if(!empty($getPartnerInfo[0]['doc_interest'])){ ?>
					  <h4><b>Area's of Interest/Expertise</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($getPartnerInfo[0]['doc_interest']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Interest/Expertise -->
					 <!-- start Contribution -->
                      <?php if(!empty($getPartnerInfo[0]['doc_contribute'])){ ?>
					 <h4><b>Professional Contribution</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($getPartnerInfo[0]['doc_contribute']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Contribution -->
					  
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">

                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>Edit Profile Details</h2>
                        </div>
                        
                      </div>
                      <!-- start of user-activity-graph -->
                     
                  
                
                    <br />
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_patient_details.php"  name="frmAddPatient" id="frmAddPatient">
						<input type="hidden" name="selectRef" value="<?php echo $getPartnerInfo[0]['ref_id'];?>" />
						 <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">Change Profile Picture</label>
                           <div class="col-md-3 col-sm-3 col-xs-12">
											  <em></em>
											  <input type="file" name="compLogo">
							</div>
					  </div>
					   <br>
					    <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">Type of Business you are in </label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="business_type" name="business_type" required="required" rows="3"></textarea>
                        
                        </div>
					  </div>
					   <br>
						<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Business Name <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="businessName" name="businessName" required="required" onchange="return call_refer_partner()" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Contact Person <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="contact_person" name="contact_person" required="required" onchange="return call_refer()" class="form-control" placeholder="">
                        </div>
						
                      </div>
					  <br>
					   <div class="form-group">
					   
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Contact Person Position in the company </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="person_position" name="person_position" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Contact Landline Number </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="contact_land" name="contact_land" class="form-control" placeholder="" maxlength="15">
                        </div>
                      </div>
					  <br>
					  <div class="form-group">
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Contact Mobile Number <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="contact_mobile" name="contact_mobile" required="required" class="form-control" placeholder="10 digit Mobile No." maxlength="10">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Email <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="email" id="primaryEmail" name="primaryEmail" class="form-control" placeholder="">
                        </div>
                        
                      </div>
					  <br>
					  <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">Country <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <select class="form-control" name="slctcountry" required="required" name="slctcountry">
                            <option value="India" selected>India</option>
												<?php 
												$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
                          </select>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">State <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <select class="form-control"  name="slctstate" id="slctstate" required="required" placeholder="State"  >
													<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
							</select>
						</div>
						
					</div>
					  <br>
					 
					 
					  <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">Location <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="txt_city" name="txt_city" required="required" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Website</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="txtWebsite" name="txtWebsite" required="required" class="form-control" placeholder="">
                        </div>
						</div>
					  <br>	
					  <div class="form-group">
					
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Address <span class="required">*</span></label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="txt_address" name="txt_address" rows="3"></textarea>
                        
                        </div>
					  </div>
					  <br>	
					   <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">New Password <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="password" id="txtpassword" name="txtpassword" required="required" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Re-type Password</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="password" id="txtrepassword" name="txtrepassword" required="required" class="form-control" placeholder="">
                        </div>
						</div>
					  
					 
                    
						<br><br>
                    <div class="form-group-left">
					
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3" style="margin-top:20px;">
						
                         
                          <button type="submit" name="refer_patient" id="refer_patient" class="btn btn-success">UPDATE </button>
                        </div>
						
                    </div>
						

                    </form>
                  </di
                      <!-- end of user-activity-graph -->

                   
                  </div>
                </div>
              </div>
            </div>
              </div>
            </div>

           
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
       <?php include('footer.php'); ?>
        <!-- /footer content -->
      </div>
    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
      <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
      </ul>
      <div class="clearfix"></div>
      <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../Hospital/vendors/iCheck/icheck.min.js"></script>
    

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
	
  </body>
</html>