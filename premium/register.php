<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Premium Landing Page</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet">
	<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#slctState").html(data);
	}
	});
}
	</script>
</head>
<body id="page-top" class="landing-page no-skin-config">
<div class="navbar-wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
               <!-- <div class="navbar-header page-scroll">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">WEBAPPLAYERS</a>
                </div>-->
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                       <!-- <li><a class="page-scroll" href="#page-top">Home</a></li>
                        <li><a class="page-scroll" href="#features">Features</a></li>
                        <li><a class="page-scroll" href="#team">Team</a></li>
                        <li><a class="page-scroll" href="#testimonials">Testimonials</a></li>
                        <li><a class="page-scroll" href="#pricing">Pricing</a></li>-->
                        <li><a class="page-scroll" href="#contact">Register Now</a></li>
                    </ul>
                </div>
            </div>
        </nav>
</div>
<div id="inSlider" class="carousel carousel-fade" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#inSlider" data-slide-to="0" class="active"></li>
        <li data-target="#inSlider" data-slide-to="1"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <div class="container">
                <div class="carousel-caption">
                    <h1>practice management suite,<br/>
                        encompassing specialty<br/>
                        specific EMR </h1>
                    <p>EMR tool which does more than just digitize records</p>
                    <p>
                        <a class="btn btn-lg btn-primary page-scroll" href="#contact" role="button">REGISTER NOW</a>
                      
                    </p>
                </div>
                <div class="carousel-image wow zoomIn">
                    <img src="../assets/img/landing/laptop.png" alt="laptop"/>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back one"></div>

        </div>
        <div class="item">
            <div class="container">
                <div class="carousel-caption blank">
                    <h1>Fully integrated <br/> smartphone enabled<br>practice management application</h1>
                    <p>Access patient information from virtually anywhere.</p>
                    <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                </div>
            </div>
            <!-- Set background for slide in css -->
            <div class="header-back two"></div>
        </div>
    </div>
    <a class="left carousel-control" href="#inSlider" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#inSlider" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>


<section id="features" class="container services">
    <div class="row">
        <div class="col-sm-3">
            <h2>PAPERLESS PRACTICE</h2>
            <p>A user friendly tools to book appointments, token system, direct walk-in, mobile  & web registration.</p>
           <!-- <p><a class="navy-link" href="#" role="button">Details &raquo;</a></p>-->
        </div>
        <div class="col-sm-3">
            <h2>SPECIALTY SPECIFIC EMR</h2>
            <p>Self customizable Electronic Medical Record with trusted online backup keeps your data safe and secure.</p>
            
        </div>
        <div class="col-sm-3">
            <h2>E-PRESCRIBING</h2>
            <p>One-click preloaded prescription, MCI complaint & also allows you to print prescriptions in local languages.</p>
           
        </div>
        <div class="col-sm-3">
            <h2>CONNECTED HEALTHCARE</h2>
            <p>Add pharmacy & diagnostic partners. Share prescriptions & tests orders to your partners.</p>
            
        </div>
    </div>
</section>

<section  class="container features">
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>Over 10,000+ healthcare professionals<br/> <span class="navy"> automated their clinics/hospitals efficiently</span> </h1>
          <!-- <p></p>-->
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 text-center wow fadeInLeft">
            <div>
                <i class="fa fa-file features-icon"></i>
                <h2>Medical records</h2>
                <p>Add a new or existing patient which includes visit details, investigations, exams, prescriptions, diagnosis and lots more.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-hospital-o features-icon"></i>
                <h2>Manage Multiple clinics</h2>
                <p>Whether you practice in 2 different clinics, or run a chain of clinics, now you can manage your practice centrally and hassle free.</p>
            </div>
        </div>
        <div class="col-md-6 text-center  wow zoomIn">
            <img src="../assets/img/landing/perspective.png" alt="dashboard" class="img-responsive">
        </div>
        <div class="col-md-3 text-center wow fadeInRight">
            <div>
                <i class="fa fa-envelope features-icon"></i>
                <h2>Automatic Reminders</h2>
                <p>SMS or Email, appointment or follow up visit reminders automatically.</p>
            </div>
            <div class="m-t-lg">
                <i class="fa fa-google features-icon"></i>
                <h2>Digitize Patient Billing</h2>
                <p>Easy billing & full support for online payment. Send instant payment links to patients with just one-click.</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center">
            <div class="navy-line"></div>
            <h1>Enabling healthy decisions</h1>
            <p>Dosage response & trend analysis</p>
        </div>
    </div>
    <div class="row features-block">
        <div class="col-lg-6 features-text wow fadeInLeft">
            <!--<small>INSPINIA</small>-->
            <h2>Health trend analysis</h2>
            <p>PRACTICE software allows you to graphically view the progress of the patient over the period of time. You can correlate the drug response over the test results. It enables you to monitor the progress of a disease and adjust the treatment or dosage accordingly.</p>
            <a href="" class="btn btn-primary">Learn more</a>
        </div>
        <div class="col-lg-6 text-right wow fadeInRight">
            <img src="../assets/img/landing/dashboard.png" alt="dashboard" class="img-responsive pull-right">
        </div>
    </div>
</section>

<!--<section id="team" class="gray-section team">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Our Team</h1>
                <p>Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 wow fadeInLeft">
                <div class="team-member">
                    <img src="../assets/img/landing/avatar3.jpg" class="img-responsive img-circle img-small" alt="">
                    <h4><span class="navy">Amelia</span> Smith</h4>
                    <p>Lorem ipsum dolor sit amet, illum fastidii dissentias quo ne. Sea ne sint animal iisque, nam an soluta sensibus. </p>
                    <ul class="list-inline social-icon">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="team-member wow zoomIn">
                    <img src="../assets/img/landing/avatar1.jpg" class="img-responsive img-circle" alt="">
                    <h4><span class="navy">John</span> Novak</h4>
                    <p>Lorem ipsum dolor sit amet, illum fastidii dissentias quo ne. Sea ne sint animal iisque, nam an soluta sensibus.</p>
                    <ul class="list-inline social-icon">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 wow fadeInRight">
                <div class="team-member">
                    <img src="../assets/img/landing/avatar2.jpg" class="img-responsive img-circle img-small" alt="">
                    <h4><span class="navy">Peter</span> Johnson</h4>
                    <p>Lorem ipsum dolor sit amet, illum fastidii dissentias quo ne. Sea ne sint animal iisque, nam an soluta sensibus.</p>
                    <ul class="list-inline social-icon">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
            </div>
        </div>
    </div>
</section>-->
<section class="timeline gray-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>How to get started with PRACTICE ?</h1>
                <p>Send a request & our support team shall be in touch with you.</p>
            </div>
        </div>
        <div class="row features-block">

            <div class="col-lg-12">
                <div id="vertical-timeline" class="vertical-container light-timeline center-orientation">
                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-angle-left"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Submit the request Form</h2>
                            <p>Fill out the form to request information. Our care support team shall contact you within few hours of the submission.
                            </p>
                            <a href="#" class="btn btn-xs btn-primary"> Step 1</a>
                            <!--<span class="vertical-date"> Today <br/> <small>Dec 24</small> </span>-->
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-angle-right"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Get a demo</h2>
                            <p>We shall ask your requirements and also brief you about the features and the packages. Select and receive your PRACTICE account login credentials.</p>
                            <a href="#" class="btn btn-xs btn-primary"> Step 2</a>
                            <!--<span class="vertical-date"> Tomorrow <br/> <small>Dec 26</small> </span>-->
                        </div>
                    </div>

                    <div class="vertical-timeline-block">
                        <div class="vertical-timeline-icon navy-bg">
                            <i class="fa fa-angle-left"></i>
                        </div>

                        <div class="vertical-timeline-content">
                            <h2>Get started</h2>
                            <p>Improve your clinical productivity with PRACTICE EMR system and get a seamless experience to your medical practice. </p>
                            <a href="#" class="btn btn-xs btn-primary"> Step 3</a>
                            <!--<span class="vertical-date"> Monday <br/> <small>Jan 02</small> </span>-->
                        </div>
                    </div>
					
					

                </div>
            </div>

        </div>
    </div>

</section>
<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Practice in a connected healthcare environment</h1>
                
				<p>Add pharmacy & diagnostic partners. Share prescriptions & tests orders to your partners.</p>
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-3 features-text wow fadeInLeft">
                <!--<small>Pharmacy</small>-->
                <h2>Pharmacy</h2>
                <p>Add pharmacy of your choice into your PRACTICE system and directly send the prescriptions to the pharmacy with just one-click. All the prescriptions are QR coded.
It also allows you to share prescriptions in local languages.
</p>
                <a href="" class="btn btn-primary">Learn more</a>
            </div>
            <div class="col-lg-6 text-right m-t-n-lg wow zoomIn">
                <img src="../assets/img/landing/iphone.jpg" class="img-responsive" alt="dashboard">
            </div>
            <div class="col-lg-3 features-text text-right wow fadeInRight">
                <!--<small></small>-->
                <h2>Diagnostic Centres</h2>
                <p>Add diagnostic centre of your choice into your PRACTICE system and directly refer the tests to the centre. They can share the reports to you and the patient.  The test results can be automatically updated into your EMR system.</p>
                <a href="" class="btn btn-primary">Learn more</a>
            </div>
        </div>
    </div>

</section>



<section id="testimonials" class="navy-section testimonials" style="margin-top: 0">

    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center wow zoomIn">
                <i class="fa fa-user-md big-icon"></i>
                <h1>
                    Over 10,000+ healthcare professionals automated their clinics/hospitals efficiently
                </h1>
                
            </div>
        </div>
    </div>

</section>

<section class="comments gray-section" style="margin-top: 0">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>What our users say</h1>
               <!-- <p>Donec sed odio dui. Etiam porta sem malesuada. </p>-->
            </div>
        </div>
        <div class="row features-block">
            <div class="col-lg-4">
                <div class="bubble">
                    "PRACTICE has been a wonderful upgrade from my previous EMR. It enables me to add investigations and prescriptions with just one click."
                </div>
                <div class="comments-avatar">
                    <a href="" class="pull-left">
						<img alt="image" src="../assets/img/landing/avatar_1.jpg">
                       
                    </a>
                    <div class="media-body">
                        <div class="commens-name">
                           Dr. Vasudev Pai
                        </div>
                        <small class="text-muted">Cardiovascular & Thoracic Surgeron</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bubble">
                    "User friendly and intuitive EMR. I started adding patients from day one without any personal training."
                </div>
                <div class="comments-avatar">
                    <a href="" class="pull-left">
                         <img alt="image" src="../assets/img/landing/avatar_1.jpg">
                    </a>
                    <div class="media-body">
                        <div class="commens-name">
                            Dr Shakila
                        </div>
                        <small class="text-muted">Ophthalmologist</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="bubble">
                    "I can run PRACTICE application in my laptop & smartphone simultaneously. It really helps me retrieve patient data anytime & anywhere."
                </div>
                <div class="comments-avatar">
                    <a href="" class="pull-left">
                        <img alt="image" src="../assets/img/landing/avatar_1.jpg">
                    </a>
                    <div class="media-body">
                        <div class="commens-name">
                           Dr. Rajeshwari
                        </div>
                        <small class="text-muted">Interventional Cardiologist </small>
                    </div>
                </div>
            </div>



        </div>
    </div>

</section>

<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Unique features</h1>
                <p>Eliminate paperwork & improve your clinical productivity</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 features-text">
               <h2>24 x 7 support</h2>
                <i class="fa fa-bar-chart big-icon pull-right"></i>
                <p>Our care support team is available all the time whenever you need us. We would be more than happy to help and solve any issues.</p>
            </div>
            <div class="col-lg-5 features-text">
                <h2>256 bit end to end encryption</h2>
                <i class="fa fa-bolt big-icon pull-right"></i>
                <p>All your medical data is securely stored in a cloud with modern encryption algorithms. Even if your phone takes a swim or your laptop takes a hike, your data is safe.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 features-text">
                <h2>Manage using PC & smartphone</h2>
                <i class="fa fa-clock-o big-icon pull-right"></i>
                <p>You can use the PRACTICE app in your smartphone & PC simultaneously. Retrieve patient information anytime & anywhere.</p>
            </div>
            <div class="col-lg-5 features-text">
                <h2>Everything in one place</h2>
                <i class="fa fa-users big-icon pull-right"></i>
                <p>Connect your PRACTICE account with your website, 3rd party providers. Through our app, receive notifications and manage your medical practice on the go</p>
            </div>
        </div>
    </div>

</section>


<section id="contact" class="gray-section contact">
    <div class="container">
        <div class="row m-b-lg">
            <div class="col-lg-12 text-center">
                <div class="navy-line"></div>
                <h1>Register Now</h1>
			</div>
				<div class="row">
	<section class="col-sm-6 col-md-6">

<div>
     <div class="padding-bottom-4">
       Name
     </div>
      
		<input type="text" placeholder="Name" id="txtDocName" name="txtDocName" required="required" class="form-control" />
     
   </div>
   
	<div>
     <div class="padding-bottom-4 padding-top-20">
      Country
     </div>
      
										<select class="form-control autotab" name="slctCountry" name="slctCountry" required="required" onchange="return getState(this.value);">
												<option value="India" selected>India</option>
												<?php 
												$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														
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
	
	<div>
     <div class="padding-bottom-4 padding-top-20">State</div>
     <select class="form-control autotab" name="slctState" id="slctState" required="required" placeholder="State"  >
	 <option value="" selected>Select</option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id=100", "b.state_name asc", "", "", "");
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
	
    <div>
     <div class="padding-bottom-4 padding-top-20">City</div>
      <input type="text" id="txtCity" name="txtCity" value="" class="form-control" />
                                            
    </div>
								<div>
									<div class="padding-bottom-4 padding-top-20">Specialization</div>
									<select class="form-control autotab" name="slctSpec" id="slctSpec" required="required" placeholder="State"  >
																					<option value="" >Select Specialization</option>
																					<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
																					$i=30;
																					foreach($DeptName as $DeptList){
																						if($DeptList['spec_id']==$get_docInfo[0]['specialisation']){ ?> 
																					<option value="<?php echo stripslashes($DeptList['spec_name']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
																					<?php 
																						}?>

																						<option value="<?php echo stripslashes($DeptList['spec_name']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
																					<?php
																							$i++;
																					}?> 
									</select>
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Hospital Name</div>
									  <input type="text" id="txtHosp" name="txtHosp" value="" required="required" class="form-control" />
																			
									</div>
									



					</section>

					<section class="col-sm-6 col-md-6">
									
									<div>
									 <div class="padding-bottom-4">Qualification</div>
									  <input type="text" id="txtQual" name="txtQual" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Mobile No.</div>
									  <input type="text" id="txtMob" name="txtMob" value="" required="required" placeholder="10 digit mobile no." class="form-control"  maxlength="15" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Email Id</div>
									  <input type="email" id="txtEmail" name="txtEmail" value=""  required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Medical council name</div>
									  <input type="text" id="txtMedCouncil" name="txtMedCouncil" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Registration no.</div>
									  <input type="text" id="txtMedRegnum" name="txtMedRegnum" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Upload Registration Certificate</div>
									  <input type="file" id="txtregCert" name="txtregCert" value="" class="form-control" />
																			
									</div>
									
				</section>
   
  </div>
               
            
        </div>
        <!--<div class="row m-b-lg">
            <div class="col-lg-3 col-lg-offset-3">
                <address>
                    <strong><span class="navy">MedisenseHealthcare Solutions Pvt. Ltd.</span></strong><br/>
                    Ground floor, Mohini plaza, Kalsanka, <br/>
                    Udupi, Karnataka, India 576101<br/>
                    
                </address>
            </div>
            <div class="col-lg-4">
                <p class="text-color">
                    Medisense PRACTICE  is a web and smart phone based application for doctors’ to manage their medical practice at their fingertips. It is an all-in-one solution packed with modern features.
                </p>
            </div>
        </div>-->
        <div class="row">
            <div class="col-lg-12 text-center">
			
                <button type="submit" name="register" class="btn btn-primary">Register</button>
               <br> <p class="m-t-sm">
                    follow us on social platform
                </p>
                <ul class="list-inline social-icon">
                    <li><a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li><a href="#"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                <p><strong>&copy; <?php echo date('Y'); ?> Medisense Healthcare Solutions Pvt. Ltd.</strong><br/></p>
            </div>
        </div>
    </div>
</section>

<!-- Mainly scripts -->
<script src="../assets/js/jquery-3.1.1.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../assets/js/inspinia.js"></script>
<script src="../assets/js/plugins/pace/pace.min.js"></script>
<script src="../assets/js/plugins/wow/wow.min.js"></script>


<script>

    $(document).ready(function () {

        $('body').scrollspy({
            target: '.navbar-fixed-top',
            offset: 80
        });

        // Page scrolling feature
        $('a.page-scroll').bind('click', function(event) {
            var link = $(this);
            $('html, body').stop().animate({
                scrollTop: $(link.attr('href')).offset().top - 50
            }, 500);
            event.preventDefault();
            $("#navbar").collapse('hide');
        });
    });

    var cbpAnimatedHeader = (function() {
        var docElem = document.documentElement,
                header = document.querySelector( '.navbar-default' ),
                didScroll = false,
                changeHeaderOn = 200;
        function init() {
            window.addEventListener( 'scroll', function( event ) {
                if( !didScroll ) {
                    didScroll = true;
                    setTimeout( scrollPage, 250 );
                }
            }, false );
        }
        function scrollPage() {
            var sy = scrollY();
            if ( sy >= changeHeaderOn ) {
                $(header).addClass('navbar-scroll')
            }
            else {
                $(header).removeClass('navbar-scroll')
            }
            didScroll = false;
        }
        function scrollY() {
            return window.pageYOffset || docElem.scrollTop;
        }
        init();

    })();

    // Activate WOW.js plugin for animation on scrol
    new WOW().init();

</script>

</body>
</html>

