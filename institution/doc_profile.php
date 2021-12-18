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



$getDocInfo= mysqlSelect("*","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(a.ref_id)='".$_GET['d']."'","","","","");
$getDocSpec= mysqlSelect("*","specialization","spec_id='".$getDocInfo[0]['doc_spec']."'","","","","");
$getDocAddress= mysqlSelect("*","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","md5(a.doc_id)='".$_GET['d']."'","","","","");	
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Doctor's Profile</title>

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
                    <h2>Doctor's Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li>
                            <a href="My-Connections">My Connections</a>
                        </li>
                        <li class="active">
                            <strong>Doctor's Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							<?php if(!empty($getDocInfo[0]['doc_photo'])){ ?>
                                <img alt="image" class="img-xlg img-responsive" src="../Admin/docProfilePic/<?php echo $getDocInfo[0]['ref_id']; ?>/<?php echo $getDocInfo[0]['doc_photo']; ?>">
                             <?php }  else { ?>
							 <img alt="image" class="img-xlg img-responsive" src="../assets/img/anonymous-profile.png">
                             <?php  } ?>
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $getDocInfo[0]['ref_name']; ?></strong><br><br><?php echo $getDocSpec[0]['spec_name']; ?></h4>
                                <p><i class="fa fa-map-marker"></i> <?php echo $getDocInfo[0]['ref_address'].", ".$getDocInfo[0]['doc_state']; ?></p>
                                <br><h4><strong>
                                    About <?php echo $getDocInfo[0]['ref_name']; ?>
                                </strong></h4>
                                <p><b>Exp:</b> <?php echo $getDocInfo[0]['ref_exp']; ?> Yrs<br>
								<b>Hospital:</b> <?php echo $getDocAddress[0]['hosp_name'].", ".$getDocAddress[0]['hosp_city'].", ".$getDocAddress[0]['hosp_state']; ?><br>
                                  
								  <!-- start Interest/Expertise -->
								<?php if(!empty($getDocInfo[0]['doc_interest'])){ ?>
								 <br><b>Area's of Interest/Expertise</b><br>
								 <?php echo stripslashes($getDocInfo[0]['doc_interest']); ?><br>
								  <?php } ?>
								 <!-- end of Interest/Expertise -->
								 
								 <?php if(!empty($getDocInfo[0]['doc_contribute'])){ ?>
								 <br><b>Professional Contribution</b><br>
								 <?php echo stripslashes($getDocInfo[0]['doc_contribute']); ?><br>
								 <?php } ?>
								<!-- end of Contribution -->
								
								
                                </p>
                               <!-- <div class="row m-t-lg">
                                    <div class="col-md-4">
                                        <span class="bar">5,3,9,6,5,9,7,3,5,2</span>
                                        <h5><strong>169</strong> Posts</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="line">5,3,9,6,5,9,7,3,5,2</span>
                                        <h5><strong>28</strong> Following</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <span class="bar">5,3,2,-1,-3,-2,2,3,5,2</span>
                                        <h5><strong>240</strong> Followers</h5>
                                    </div>
                                </div>
                                <div class="user-button">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary btn-sm btn-block"><i class="fa fa-envelope"></i> Send Message</button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-default btn-sm btn-block"><i class="fa fa-coffee"></i> Buy a coffee</button>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-comment-o"></i> Second Opinion</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-calendar"></i> Appointment</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
							
                                <div class="panel-body">
                               
                            <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_patient_details.php"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_name" required="required" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="se_phone_no" required="required" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" name="se_email" class="form-control"></div>
								
                                </div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                                      <div class="col-sm-10">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="option1" name="radioInline" checked="">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="option2" name="radioInline">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										</div>
								</div>		
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<option value="">Select State</option>
													<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="se_city" required="required" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address <span class="required">*</span></label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address" required="required" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Chief Medical Complaint <span class="required">*</span></label>

                                    <div class="col-sm-10"><textarea class="form-control"  name="se_info" required="required" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Medical Reports <span class="required">*</span></label>

                                    <div class="col-sm-10"><label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="file-3[]" required="required" multiple="true" class="hide">
                                        <i class="fa fa-upload"></i> Upload Medical Reports
                                    </label></div>
                                </div>
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="refer_patient" class="btn btn-primary block full-width m-b ">SEND CASE</button>
								</div>
								</div>
							</form>
							
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Date..." class="chosen-select" name="check_date"  tabindex="2">
											<option disabled="disabled" selected value="0">Select Date</option>
                                     <?php 										 
										for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","ref_doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name']){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            echo date('D d-m-Y', $date);
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
										 
									
									 ?> 
										</select></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="chosen-select" name="check_time"  tabindex="2">
											
										</select></div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_name" required="required" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" class="form-control"></div>
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                                      <div class="col-sm-10">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="se_phone_no" required="required" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" name="se_email" class="form-control"></div>
								
                                </div>
								
										
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<option value="">Select State</option>
													<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="se_city" required="required" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address <span class="required">*</span></label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address" required="required" rows="3"></textarea></div>
                                </div>
								
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="ref_appointment" class="btn btn-primary block full-width m-b ">REQUEST APPOINTMENT</button>
								</div>
								</div>
							</form>
							</div>
                            </div>
                        </div>


                    </div>
                            <!--<div>
                                <div class="feed-activity-list">

                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a1.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">1m ago</small>
                                            <strong>Sandra Momot</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Today 4:21 pm - 12.06.2014</small>
                                            <div class="actions">
                                                <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                                <a class="btn btn-xs btn-danger"><i class="fa fa-heart"></i> Love</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">5m ago</small>
                                            <strong>Monica Smith</strong> posted a new blog. <br>
                                            <small class="text-muted">Today 5:60 pm - 12.06.2014</small>

                                        </div>
                                    </div>

                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a2.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">2h ago</small>
                                            <strong>Mark Johnson</strong> posted message on <strong>Monica Smith</strong> site. <br>
                                            <small class="text-muted">Today 2:10 pm - 12.06.2014</small>
                                            <div class="well">
                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                            </div>
                                            <div class="pull-right">
                                                <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                                <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> Love</a>
                                                <a class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Message</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a3.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">2h ago</small>
                                            <strong>Janet Rosowski</strong> add 1 photo on <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 8:30am</small>
                                            <div class="photos">
                                                <a target="_blank" href="http://24.media.tumblr.com/20a9c501846f50c1271210639987000f/tumblr_n4vje69pJm1st5lhmo1_1280.jpg"> <img alt="image" class="feed-photo" src="img/p1.jpg"></a>
                                                <a target="_blank" href="http://37.media.tumblr.com/9afe602b3e624aff6681b0b51f5a062b/tumblr_n4ef69szs71st5lhmo1_1280.jpg"> <img alt="image" class="feed-photo" src="img/p3.jpg"></a>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">5h ago</small>
                                            <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                            <div class="actions">
                                                <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                                <a class="btn btn-xs btn-white"><i class="fa fa-heart"></i> Love</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a5.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">2h ago</small>
                                            <strong>Kim Smith</strong> posted message on <strong>Monica Smith</strong> site. <br>
                                            <small class="text-muted">Yesterday 5:20 pm - 12.06.2014</small>
                                            <div class="well">
                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                            </div>
                                            <div class="pull-right">
                                                <a class="btn btn-xs btn-white"><i class="fa fa-thumbs-up"></i> Like </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/profile.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">23h ago</small>
                                            <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                            <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                        </div>
                                    </div>
                                    <div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="img/a7.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">46h ago</small>
                                            <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                            <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-block m"><i class="fa fa-arrow-down"></i> Show More</button>

                            </div>-->

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
