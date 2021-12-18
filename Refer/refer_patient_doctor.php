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


$getDocInfo= $objQuery->mysqlSelect("*","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(a.ref_id)='".$_GET['d']."'","","","","");
$getDocSpec= $objQuery->mysqlSelect("*","specialization","spec_id='".$getDocInfo[0]['doc_spec']."'","","","","");
$getDocAddress= $objQuery->mysqlSelect("*","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","md5(a.doc_id)='".$_GET['d']."'","","","","");	
                
?>
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Request Expertise</title>

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
                    <h2>Send Case Details  </h2>
                    <div class="right">
                <div class="form-group pull-right top_search">
                  <div class="input-group">
                    <a href="Doctors-List?start=<?php echo $_GET['start']; ?>" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> BACK </a>
                     
                    </span>
                  </div>
                </div>
              </div><br><br>
			  <small> <i class="fa fa-lightbulb-o"></i> To send a case to an expert, please add all the patient details below. Expert will be notified on mobile app, email and sms. Once expert sends the response you will be notified via email/sms/mobile app. Please note patient will not be notified.</small>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                      <div class="profile_img">
                        <div id="crop-avatar">
                          <!-- Current avatar -->
						   <?php if(!empty($getDocInfo[0]['doc_photo'])){ ?>
								<img src="https://medisensecrm.com/Doc/<?php echo $getDocInfo[0]['ref_id']; ?>/<?php echo $getDocInfo[0]['doc_photo']; ?>" class="img-responsive avatar-view" width="130"/>
							 <?php }  else { ?>
								 <img src="https://medisensehealth.com/new_assets/img/doc_icon.jpg" class="img-responsive avatar-view"/>
							<?php  } ?>
                         
                        </div>
                      </div>
                      <h3><?php echo $getDocInfo[0]['ref_name']; ?></h3>

                      <ul class="list-unstyled user_data">
						<li class="m-top-xs">
                          <?php echo $getDocSpec[0]['spec_name']; ?>
                          
                        </li>
						<li class="m-top-xs">
                          <i class="fa fa-star user-profile-icon"></i>
                          <?php echo $getDocInfo[0]['ref_exp']; ?> Yrs Exp.
                        </li>
                        <li><i class="fa fa-map-marker user-profile-icon"></i> <?php echo $getDocInfo[0]['ref_address'].", ".$getDocInfo[0]['doc_state']; ?>
                        </li>

                        <li>
                          <i class="fa fa-hospital-o user-profile-icon"></i> <?php echo $getDocAddress[0]['hosp_name'].", ".$getDocAddress[0]['hosp_city'].", ".$getDocAddress[0]['hosp_state']; ?>
                        </li>

                        
                      </ul>

                      
                      <br />

                      <!-- start Interest/Expertise -->
                      <?php if(!empty($getDocInfo[0]['doc_interest'])){ ?>
					  <h4><b>Area's of Interest/Expertise</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($getDocInfo[0]['doc_interest']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Interest/Expertise -->
					 <!-- start Contribution -->
                      <?php if(!empty($getDocInfo[0]['doc_contribute'])){ ?>
					 <h4><b>Professional Contribution</b></h4>
                      <ul class="list-unstyled user_data">
                        <li>
                          <p><?php echo stripslashes($getDocInfo[0]['doc_contribute']); ?></p>
                          
                        </li>
                        
                      </ul>
					  <?php } ?>
                      <!-- end of Contribution -->
					  
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">

                      <div class="profile_title">
                        <div class="col-md-6">
                          <h2>Add Case Details</h2>
                        </div>
                        
                      </div>
                      <!-- start of user-activity-graph -->
                     
                  
                
                    <br />
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_patient_details.php"  name="frmAddPatient" id="frmAddPatient">
						<input type="hidden" name="selectRef" value="<?php echo $getDocInfo[0]['ref_id'];?>" />
						<input type="hidden" name="se_depart" id="se_depart" value="<?php echo $getDocInfo[0]['doc_spec'];?>"  />
						<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Patient Name <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_pat_name" name="se_pat_name" required="required" onchange="return call_refer_partner()" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Age <span class="required">*</span></label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <input type="text" id="se_pat_age" name="se_pat_age" required="required" onchange="return call_refer()" class="form-control" placeholder="">
                        </div>
						
                      </div>
					  <br>
					   <div class="form-group">
					   
                        
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Mobile <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_phone_no" name="se_phone_no" required="required" class="form-control" placeholder="10 digit Mobile No." maxlength="10">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Email</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="email" id="se_email" name="se_email" class="form-control" placeholder="">
                        </div>
                      </div>
					  <br>
					  <div class="form-group">
					  
						
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Country <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <select class="form-control" name="se_country" required="required" name="se_country">
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
                          <select class="form-control"  name="se_state" id="se_state" required="required" placeholder="State"  >
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
                        
						<label class="control-label col-md-2 col-sm-2 col-xs-12">City <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_city" name="se_city" required="required" class="form-control" placeholder="">
                        </div>
						
					</div>
					  
					   <br>
					   <div class="form-group">
					   <label class="control-label col-md-2 col-sm-2 col-xs-12">Address <span class="required">*</span></label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="se_address" name="se_address" required="required" rows="3"></textarea>
                        
                        </div>
						</div>
					  <div class="form-group">
					  <label class="control-label col-md-2 col-sm-2 col-xs-12">Add Attachments</label>
                           <div class="col-md-3 col-sm-3 col-xs-12">
											  <em>Multiple Select(Ctrl+Select)</em>
											  <input type="file" id="file-3" name="file-3[]"  multiple="true">
							</div>
					  </div>

					  <br>	
					  <div class="form-group">
					
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Chief Medical Complaint</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="se_info" name="se_info" rows="3"></textarea>
                        
                        </div>
					  </div>
					  
					   <div class="form-group">
					   <label class="control-label col-md-3 col-sm-3 col-xs-12" ><i class="fa fa-arrow-down"></i>
					<a data-toggle="collapse" data-target="#demo" style="cursor:pointer" >					
					More(optional)</a></label>
					</div>
					<div id="demo" class="collapse">	
					<div class="form-group">
						
					
                        <label class="control-label col-md-2 col-sm-2 col-xs-12">Gender</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                         <div class="radio"><label>
                             <input type="radio" name="se_gender" id="male" checked="checked" value="1" >Male
                            </label>
							<label>
                              <input type="radio" name="se_gender" id="female" value="2" >Female
                          </label>
						  </div>
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Hypertension</label>
                        <div class="col-md-2 col-sm-2 col-xs-12">
                          <div class="radio"><label>
                              <input type="radio" name="se_hyper" id="option2" value="1">Yes
                            </label>
							<label>
                              <input type="radio" name="se_hyper" checked="checked" id="option4" value="0" > No
                          </label>
						  </div>
						</div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Diabetes</label>
							<div class="col-md-2 col-sm-2 col-xs-12">
							<div class="radio"><label>
                              <input type="radio" name="se_diabets" value="1" id="option3" >Yes
                            </label>
							<label>
                              <input type="radio" name="se_diabets" checked="checked" id="option4" value="0"> No
                          </label>
						  </div>
							</div>
                      </div>
					<br>
					<div class="form-group">
					<label class="control-label col-md-2 col-sm-2 col-xs-12">Contact Person <span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_con_per" name="se_con_per" required="required" class="form-control" placeholder="">
                        </div>
					</div>
					<br>
					<div class="form-group">
                        
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Current Treating Doctor</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_treat_doc" name="se_treat_doc" class="form-control" placeholder="">
                        </div>
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Current Treating Hospital</label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <input type="text" id="se_treat_hosp" name="se_treat_hosp" class="form-control" placeholder="">
                        </div>
                      </div>
					  <br>
					  <div class="form-group">
                        
						 <label class="control-label col-md-2 col-sm-2 col-xs-12">Detailed Description</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="se_des" name="se_des" rows="3"></textarea>
                        
                        </div>
                      </div>
					  <br>
					  
					  <div class="form-group">
                        
						<label class="control-label col-md-2 col-sm-2 col-xs-12">Please mention your query to the doctors</label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                          <textarea class="form-control" id="se_query" name="se_query" rows="3"></textarea>
                        
                        </div>
                      </div>
					 
					  </div>
					  
					  <div class="form-group">
                        
                      </div>
                    
						<br><br>
                    <div class="form-group-left">
					
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3" style="margin-top:20px;">
						
                         
                          <button type="submit" name="refer_patient" id="refer_patient" class="btn btn-success">SEND CASE </button>
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