<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = 178;
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
			
$getdetails = mysqlSelect("*","webtemplate3_details","doc_id='".$admin_id."' and doc_type=1","","","","");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <!-- viewport meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Rida vCard -  Responsive HTML5 Portfolio/Resume Template">
    <meta name="keywords" content="vcard, html5, portfolio, resume, material">

    <title>Doctor - Personal Portfolio</title>

    <!-- owl carousel css -->
    <link rel="stylesheet" href="css/owl.carousel.css"/>

    <!-- venobox css -->
    <link rel="stylesheet" href="css/venobox.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css"/>

    <!-- camera css -->
    <link rel="stylesheet" href="css/camera.css">

    <!-- fontello -->
    <link rel="stylesheet" href="css/pe-icon-7-stroke.css"/>

    <!-- linear icons css -->
    <link rel="stylesheet" href="css/lnr-icon.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700,900" rel="stylesheet">

    <!-- bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css"/>

    <!-- animte css -->
    <link rel="stylesheet" href="css/animate.css"/>

    <!-- style css -->
    <link rel="stylesheet" href="style.css"/>

    <!-- responsive css -->
    <link rel="stylesheet" href="css/responsive.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
</head>
<body>

    <!-- start main wrapper .site -->
    <div class="site">
        <!-- start header -->
        <aside class="nav_sidebar">
            <div class="menu_wrapper">
                <div class="header_top">
                    <div class="toggle_icon hidden-xs"><span class="lnr lnr-menu"></span></div>
                    <div class="head_img_wrap">
                        <img src="images/person.png" alt="Images">
							
                    </div>
					
                    <p class="name"><?php echo $getdetails[0]['about_name']; ?> 
                                       </p>
                </div>

                <div class="main_menu">
                    <ul>
                        <li class="active"><a href="#home"><span class="lnr lnr-home"></span><p>Home</p></a></li>
                        <li><a href="#about"><span class="lnr lnr-user"></span><p>about me</p></a></li>
                        <li><a href="#resume"><span class="lnr lnr-book"></span><p>My Skills</p></a></li>
                        <li><a href="#service"><span class="lnr lnr-cog"></span><p>services</p></a></li>
                        <li><a href="#portfolio"><span class="lnr lnr-briefcase"></span><p>Projects</p></a></li>
                        <li><a href="#blog"><span class="lnr lnr-leaf"></span><p>blog</p></a></li>
                        <li><a href="#contact"><span class="lnr lnr-envelope"></span><p>contact</p></a></li>
                    </ul>

                    <div class="social">
                        <ul>
                            <li><a href="https://www.facebook.com"><span class="fa fa-facebook"></span></a></li>
                            <li><a href="https://twitter.com/"><span class="fa fa-twitter"></span></a></li>
                            <li><a href="https://plus.google.com/"><span class="fa fa-google-plus"></span></a></li>
                            <li><a href="https://www.linkedin.com/"><span class="fa fa-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </aside><!-- end header -->

        <!-- menu toggler -->
        <div class="menu_toggler">
            <span class="fa fa-bars" aria-hidden="true"></span>
        </div>
        <!-- menu toggler -->
        

        <!-- main_content -->
        <div class="main_content">
            <!-- #home -->
            <section id="home" class="home single_page" style="background-image: url(http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/home_bg.jpg);">
			<form method="post" enctype="multipart/form-data" name="frmhome" id="frmhome" action="add_webdetails.php">
                          
                <!-- hero_content  -->
				 <div class="hero_content">
                       <div class="col-md-6 col-md-offset-2">
                        <input type="text"  id="home_name" maxlength="200" placeholder="Name" name="home_name"  value="<?php echo $getdetails[0]['home_name']; ?>" >
                                   <br><br>
							 <input type="text" id="home_designation" maxlength="200" name="home_designation" placeholder="Designation" value="<?php echo $getdetails[0]['home_designation']; ?>" >
                                     <br><br>
							<input type="text"  id="home_company"  maxlength="200" name="home_company" placeholder="Company" value="<?php echo $getdetails[0]['home_company']; ?>" >
                                     <br>
							<br><button type="submit"  name="add_home" id="add_home" class="btn" > Save Details </button>		 
					 </div>

			  					 
                    </div>
					        
                 <!--<div class="hero_content">
                    <div class="big_title typed">
                        <h1>Hello</h1>
                        <p>I AM <span>JONATHON DOE</span></p>
                    </div> 
                </div>-->
				
				<!-- hero_content -->
				</form>
            </section><!-- end #home -->
   
            <!-- about -->
            <section id="about" class="about single_page">
                <!-- start about_area -->
                <div class="about_area section_padding">
                    <!-- container -->
                    <div class="container">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>ABOUT ME</h2>
                                    <p class="sub_title">I AM Dr. JONATHON DOE</p>
                                </div>
                            </div><!-- .col-md-12 -->
                        </div><!-- row -->

                        <!-- start row -->
                        <div class="row">
                            <!-- col-md-4 -->
							<form method="post" enctype="multipart/form-data" name="frmabout" id="frmabout" action="add_webdetails.php">
                            <div class="col-md-4 col-sm-6 col-lg-4 col-md-offset-0 col-sm-offset-3 v_middle_md">
                                <div class="about_img">
                                    <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/about_img.jpg" alt="Image of someone">
                                </div>
                            </div><!-- end col-md-4 -->

                            <!-- .col-md-8 -->
                            <div class="col-md-8 col-sm-12 v_middle_md">
                                <!-- about infos -->
                                <div class="col-md-6">
								
                                    <ul>
                                        <li>
                                           <p>Full name</p>
                                            <span><input type="text" maxlength="200"  id="about_name" name="about_name"  value="<?php echo $getdetails[0]['about_name']; ?>" ></span>
                                        </li>
                                        <li>
                                            <p>Address</p>
                                            <span><input type="text"  maxlength="200" id="about_address" name="about_address"  value="<?php echo $getdetails[0]['about_address']; ?>" ></span>
                                     
                                        </li>
                                       
                                        
                                    </ul>
									
                                 </div>
                                  <div class="col-md-6">
                                    <ul>
                                        <li>
                                            <p>Specialization</p>
                                            <span><input type="text"  maxlength="200" id="about_specialization" name="about_specialization"  value="<?php echo $getdetails[0]['about_specialization']; ?>" ></span>
                                     
                                        </li>
                                        <li>
                                            <p>Experience</p>
                                         <span><input type="text" maxlength="200" id="about_experience" name="about_experience"  value="<?php echo $getdetails[0]['about_experience']; ?>" ></span>
                                     
                                        </li>
                                      <br>
                                      
                                    </ul>
                                </div>

                                <!-- about_content -->
                                <div class="about_content hidden_md hidden_sm">
								
                                    <p><textarea  rows="8" maxlength="600" id="about_info" name="about_info"  placeholder="About me"  ><?php echo $getdetails[0]['about_info']; ?></textarea>    
										</p>
                                   
                                    <div class="about_btn">
                                        <a href="#" class="btn">hire me</a>
                                        <a href="#" class="btn">download resume</a>
                                    </div>
                                </div> 
                            </div><!-- end .col-md-8 -->
							<label>Upload image</label><br>
	<label class="buttonsmall"> <input type="file" name="txtPhoto" id="txtPhoto" /></label> <br>
                          <button type="submit"  name="add_about" id="add_about" class="btn" > Save Details </button>
			              </form> 
					   </div><!-- end row -->

                        <!-- start row -->
                        <div class="row">
                            <!-- start col-md-12 -->
                            <div class="col-md-12">
                                <!-- about_content -->
                                <div class="about_content visible_md visible_sm">
                                    <p> <textarea  rows="6" maxlength="600" id="about_info" name="about_info"  placeholder="About me"  ><?php echo $getdetails[0]['about_info']; ?></textarea>    
														  </p>

                                    <!-- about_btn -->
                                    <div class="about_btn">
                                        <a href="#" class="btn">hire me</a>
                                        <a href="#" class="btn">download resume</a>
                                    </div><!-- end about_btn -->
                                </div><!-- end about_content -->
                            </div><!-- end col-md-12 -->
                            </div><!-- end /.row -->
                    </div><!-- end container -->
                </div><!-- end about_area -->
            </section><!-- end about -->

            <!-- #resume -->
            <section id="resume" class="resume single_page">
				<form method="post" enctype="multipart/form-data" name="frmskills" id="frmskills" action="add_webdetails.php">
                        
                <!-- resume_area -->
                <div class="resume_area section_padding">
                    <!-- container -->
                    <div class="container">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>My skills</h2>
                                    <p class="sub_title">My special expertise</p>
                                </div>
                            </div><!-- .col-md-12 -->
                        </div><!-- end .row -->

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-6 -->
                            <div class="col-md-6">
                                <div class="resume_are_title">
                                    <span class="pe-7s-study"></span><p>EDUCATION</p>
                                </div>

                                <!-- timeline -->
                                <ul class="timeline">
                                    <li>
                                        <div class="time_period">
                                            <p><input type="text"  id="skills_edu_year1" name="skills_edu_year1"  placeholder="year" value="<?php echo $getdetails[0]['skills_edu_year1'];?>"  ></p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text"  id="skills_edu_stream1" name="skills_edu_stream1"  placeholder="Stream" value="<?php echo $getdetails[0]['skills_edu_stream1'];?>"  >
                                       </h4>
                                        </div>
                                        <div class="about_time_period">
                                            <p><textarea  rows="3" maxlength="200" id="skills_edu_description1" name="skills_edu_description1"  placeholder="Description"  ><?php echo $getdetails[0]['skills_edu_description1']; ?></textarea>    
										  </p>
                                        </div>
                                    </li>
                                   <li>
                                        <div class="time_period">
                                            <p><input type="text"  id="skills_edu_year2" name="skills_edu_year2"  placeholder="year" value="<?php echo $getdetails[0]['skills_edu_year2'];?>"  ></p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text"  id="skills_edu_stream2" name="skills_edu_stream2"  placeholder="Stream" value="<?php echo $getdetails[0]['skills_edu_stream2'];?>"  >
                                       </h4>
                                        </div>
                                        <div class="about_time_period">
                                            <p><textarea  rows="3" maxlength="200" id="skills_edu_description2" name="skills_edu_description2"  placeholder="Description"  ><?php echo $getdetails[0]['skills_edu_description2']; ?></textarea>    
										  </p>
                                        </div>
                                    </li>
									<li>
                                        <div class="time_period">
                                            <p><input type="text"  id="skills_edu_year3" name="skills_edu_year3"  placeholder="year" value="<?php echo $getdetails[0]['skills_edu_year3'];?>"  ></p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text"  id="skills_edu_stream3" name="skills_edu_stream3"  placeholder="Stream" value="<?php echo $getdetails[0]['skills_edu_stream3'];?>"  >
                                       </h4>
                                        </div>
                                        <div class="about_time_period">
                                            <p><textarea rows="3" maxlength="200" id="skills_edu_description3" name="skills_edu_description3"  placeholder="Description"  ><?php echo $getdetails[0]['skills_edu_description3']; ?></textarea>    
										  </p>
                                        </div>
                                    </li>
                                    
                                </ul>
                                <!-- end timeline -->
                            </div><!-- end col-md-6 -->

                            <!-- col-md-6 -->
                            <div class="col-md-6">
                                <div class="resume_are_title">
                                    <span class="pe-7s-portfolio"></span><p>work EXPERIENCE</p>
                                </div>

                                <!-- timeline -->
                                <ul class="timeline no-margin">
                                    <li>
                                        <div class="time_period">
                                            <p><input type="text"  id="skills_exp_year1" name="skills_exp_year1"  placeholder="year" value="<?php echo $getdetails[0]['skills_exp_year1'];?>"  >
                                  </p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text"  id="skills_exp_designtaion1" name="skills_exp_designtaion1"  placeholder="designtaion" value="<?php echo $getdetails[0]['skills_exp_designtaion1'];?>"  >
                                </h4>
                                        </div>
                                        <div class="about_time_period">
										<p><textarea  rows="3" maxlength="200" id="skills_exp_description1" name="skills_exp_description1"  placeholder="Description"  ><?php echo $getdetails[0]['skills_exp_description1']; ?></textarea>    
										  </p>
                                             </div>
                                    </li>
                                    <li>
                                        <div class="time_period">
                                            <p><input type="text"  id="skills_exp_year2" name="skills_exp_year2"  placeholder="year" value="<?php echo $getdetails[0]['skills_exp_year2'];?>"  >
                                  </p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text" id="skills_exp_designtaion2" name="skills_exp_designtaion2"  placeholder="designtaion" value="<?php echo $getdetails[0]['skills_exp_designtaion2'];?>"  >
                                </h4>
                                        </div>
                                        <div class="about_time_period">
										<p><textarea  rows="3" maxlength="200" id="skills_exp_description2" name="skills_exp_description2"  placeholder="Description"  ><?php echo $getdetails[0]['skills_exp_description2']; ?></textarea>    
										  </p>
                                             </div>
                                    </li>
									<li>
                                        <div class="time_period">
                                            <p><input type="text" id="skills_exp_year3" name="skills_exp_year3"  placeholder="year" value="<?php echo $getdetails[0]['skills_exp_year3'];?>"  >
                                  </p>
                                        </div>
                                        <div class="timeline_title">
                                            <h4><input type="text"  id="skills_exp_designtaion3" name="skills_exp_designtaion3"  placeholder="designtaion" value="<?php echo $getdetails[0]['skills_exp_designtaion3'];?>">
                                </h4>
                                        </div>
                                        <div class="about_time_period">
										<p><textarea  rows="3" maxlength="200" id="skills_exp_description3" name="skills_exp_description3"  placeholder="Description"  ><?php echo $getdetails[0]['skills_exp_description3']; ?></textarea>    
										  </p>
                                             </div>
                                    </li>
                                    
                                </ul>
                                <!-- end timeline -->
                            </div><!-- end col-md-6 -->
                        </div><!-- end .row -->
                    </div><!-- end .container -->
               
			   </div><!-- end resume_area -->

                <!-- skill_area -->
               <div class="skill_area section_padding">
                    <!-- start container -->
                    <div class="container">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>my skills</h2>
                                    <p class="sub_title">my special expertise</p>
                                </div>
                            </div><!-- .col-md-12 -->
                        </div><!-- row -->

                        <!-- row -->
                        <div class="row">
                            <!-- col-md-6 -->
                            <div class="col-md-6 col-sm-6">
                                <!-- skill_wrapper -->
                                <div class="skill_wrapper">
                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                            <p><input type="text" maxlength="200" id="skills_rating_title1" name="skills_rating_title1"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title1'];?>"  >
                             <label>Rating value (1-100)</label>
                              <input type="text" maxlength="3" id="skills_rating_value1" name="skills_rating_value1"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value1'];?>">
                             
                   							 </p>
                                        </div>
                                        <!--<div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">90% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>

                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                             <p><input type="text" maxlength="200"  id="skills_rating_title2" name="skills_rating_title2"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title2'];?>"  >
                             <label>Rating value (1-100)</label>
                                <input type="text" maxlength="3" id="skills_rating_value2" name="skills_rating_value2"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value2'];?>">
                             
                   							 </p>
											 </div>
                                        <!--<div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">85% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>

                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                          <p><input type="text"  maxlength="200" id="skills_rating_title3" name="skills_rating_title3"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title3'];?>"  >
                             <label>Rating value (1-100)</label>
                                <input type="text" maxlength="3" id="skills_rating_value3" name="skills_rating_value3"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value3'];?>">
                             
                   							 </p>
                                        </div>
                                        <!--<div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">85% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>
                                </div><!-- end skill_wrapper -->
                            </div><!-- end .col-md-6 -->

                            <!-- col-md-6 -->
                            <div class="col-md-6 col-sm-6">
                                <!-- skill_wrapper -->
                                <div class="skill_wrapper">
                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                            <p><input type="text"  maxlength="200" id="skills_rating_title4" name="skills_rating_title4"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title4'];?>"  >
                             <label>Rating value (1-100)</label>
                                  								  <!-- <span data-width="89">0%</span>--><input type="text" maxlength="3" id="skills_rating_value4" name="skills_rating_value4"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value4'];?>">
                             
                   							 </p>
                                        </div>
                                       <!-- <div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">90% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>

                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                            <p><input type="text" maxlength="200" id="skills_rating_title5" name="skills_rating_title5"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title5'];?>"  >
                             <label>Rating value (1-100)</label>
                                  								  <!-- <span data-width="89">0%</span>--><input type="text"   maxlength="3" id="skills_rating_value5" name="skills_rating_value5"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value5'];?>">
                             
                   							 </p>
                                        </div>
                                        <!--<div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">85% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>

                                    <!-- single_skill -->
                                    <div class="single_skill">
                                        <div class="labels clearfix">
                                           <p><input type="text" maxlength="200" id="skills_rating_title6" name="skills_rating_title6"  placeholder="skills title" value="<?php echo $getdetails[0]['skills_rating_title6'];?>"  >
                             <label>Rating value (1-100)</label>
                                  								  <!-- <span data-width="89">0%</span>--><input type="text" maxlength="3" id="skills_rating_value6" name="skills_rating_value6"  placeholder="skills rating" value="<?php echo $getdetails[0]['skills_rating_value6'];?>">
                             
                   							 </p>
                                        </div>
                                       <!-- <div class="progress">
                                            <div class="progress-bar" data-anim="slideInLeft" role="progressbar" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100">
                                            <span class="percent_indicator"></span>
                                            <span class="sr-only">85% Complete</span>
                                            </div>
                                        </div>-->
                                    </div>
                                </div><!-- end skill_wrapper -->
                            </div><!-- end .col-md-6 -->
							  
                        </div>
						 <button type="submit"  name="add_skills" id="add_skills" class="btn" > Save Details </button>
			             <!-- end row -->
                    </div><!-- end .container -->
                </div><!-- end skill_area -->

                <!-- testimonial_area -->
          <!--  <div class="testimonial_area section_padding">
                   
                    <div class="container">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>TESTIMONIALS</h2>
                                    <p class="sub_title">what clients says about me</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="row">
                         
                            <div class="col-md-8 col-md-offset-2">
                                <div class="testimonial_wrapper">
                                   
                                    <div class="single_testimonial">
                                        <p class="content">I throw myself down among the tall grass by the trickling stream; and, as I lie clos
                                            e to the earth, a thousand unknown plants are noticed by me. un strikes the upper surface of the
                                            impenetrable foliage of my trees.I throw myself down among the</p>

                                        <div class="testimonial_img">
                                            <img src="images/clients_img.png" alt="Image Of Clinets.">
                                        </div>
                                        <div class="client_info">
                                            <p>Johna Wick</p>
                                            <span class="desig">CEO of AazzTech</span>
                                        </div>
                                    </div>

                                   
                                    <div class="single_testimonial">
                                        <p class="content">I throw myself down among the tall grass by. un strikes the upper surface of the
                                            impenetrable foliag the trickling stream; and, as I lie clos to the earth, a thousand unknown plants
                                            are noticed by mee of my trees.I throw myself down among the tall grass by the trickling stream; and,</p>

                                        <div class="testimonial_img">
                                            <img src="images/clients_img.png" alt="Image Of Clinets.">
                                        </div>
                                        <div class="client_info">
                                            <p>Leodacap Rio</p>
                                            <span class="desig">Excutive of Nomansi</span>
                                        </div>
                                    </div>

                                    <div class="single_testimonial">
                                        <p class="content">I throw myself down among the tall grass by the trickling stream; and, as I lie clos
                                            e to the earth, a thousand unkno un strikes the upper surface of the
                                            impenetrable foliage of my trees.I throw myself down among the tall grass by the trickling stream; and,</p>

                                        <div class="testimonial_img">
                                            <img src="images/clients_img.png" alt="Image Of Clinets.">
                                        </div>
                                        <div class="client_info">
                                            <p>Cycle Mackson</p>
                                            <span class="desig">UI/UX Designer</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="slider_nav slider_nav_testi">
                                    <span class="nav_left fa fa-angle-left"></span>
                                    <span class="nav_right fa fa-angle-right"></span>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>-->

                <!-- start clients area 
                <div class="clients_area section_padding">
                   
                    <div class="container">
                        <!--
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>Clients</h2>
                                    <p class="sub_title">People who trusted me</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                        <div class="col-md-12">
                                <div class="clinet_slider">
                                    <div class="clsider"><img src="images/cl1.png" alt=""></div>
                                    <div class="clsider"><img src="images/cl2.png" alt=""></div>
                                    <div class="clsider"><img src="images/cl3.png" alt=""></div>
                                    <div class="clsider"><img src="images/cl1.png" alt=""></div>
                                    <div class="clsider"><img src="images/cl2.png" alt=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                 -->
			</form>
            </section><!-- end #resume -->

            <!-- #service  -->
            <section id="service" class="service single_page">
				<form method="post" enctype="multipart/form-data" name="frmservice" id="frmservice" action="add_webdetails.php">
                        
                <!-- services_area -->
                <div class="service_area section_padding">
                    <!-- container -->
                    <div class="container">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>services</h2>
                                    <p class="sub_title">what i can do for you</p>
                                </div>
                            </div><!-- .col-md-12 -->
                        </div><!-- end .row -->


                        <!-- row -->
                        <div class="row">
                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-diamond"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title1" name="service_title1"  placeholder="service title" value="<?php echo $getdetails[0]['service_title1'];?>">
                               </h5>
                                        <p><input type="text" maxlength="200" id="service_description1" name="service_description1"  placeholder="service description" value="<?php echo $getdetails[0]['service_description1'];?>">
                              </p>
                                    </div>
                                </div>
                            </div>

                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-airplay"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title2" name="service_title2"  placeholder="service_title" value="<?php echo $getdetails[0]['service_title2'];?>">
                               </h5>
                                        <p><input type="text" maxlength="200" id="service_description2" name="service_description2"  placeholder="service description" value="<?php echo $getdetails[0]['service_description2'];?>">
                             	</p>
                                    </div>
                                </div>
                            </div>

                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-phone"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title3" name="service_title3"  placeholder="service_title" value="<?php echo $getdetails[0]['service_title3'];?>">
                              </h5>
                                        <p><input type="text"  id="service_description3" maxlength="200" name="service_description3"  placeholder="service description" value="<?php echo $getdetails[0]['service_description3'];?>">
                             </p>
                                    </div>
                                </div>
                            </div>

                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-light"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title4" name="service_title4"  placeholder="service_title" value="<?php echo $getdetails[0]['service_title4'];?>">
                              </h5>
                                        <p><input type="text"  id="service_description4" maxlength="200" name="service_description4"  placeholder="service description" value="<?php echo $getdetails[0]['service_description4'];?>">
                             </p>
                                    </div>
                                </div>
                            </div>

                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-vector"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title5" name="service_title5"  placeholder="service_title" value="<?php echo $getdetails[0]['service_title5'];?>">
                              </h5>
                                        <p><input type="text"  id="service_description5" maxlength="200" name="service_description5"  placeholder="service description" value="<?php echo $getdetails[0]['service_description5'];?>">
                             </p>
                                    </div>
                                </div>
                            </div>

                            <!-- single_service -->
                            <div class="col-md-6 col-sm-6 col-lg-4">
                                <div class="single_service">
                                    <div class="service_icon"><span class="pe-7s-display1"></span></div>
                                    <div class="service_content">
                                        <h5 class="service_title"><input type="text" maxlength="200" id="service_title6" name="service_title6"  placeholder="service_title" value="<?php echo $getdetails[0]['service_title6'];?>">
                              </h5>
                                        <p><input type="text"  id="service_description6" maxlength="200" name="service_description6"  placeholder="service description" value="<?php echo $getdetails[0]['service_description6'];?>">
                             </p>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .row -->
                    </div><!-- end .container -->
                </div><!-- end services_area -->

                <!-- counterup_area -->
                <div class="counterup_area">
                    <!-- container -->
                    <div class="container">
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <!-- start single_counter_wrapper -->
                                    <div class="single_counter_wrapper tiny_fullwidth xs_halfwidth">
                                        <!-- start count_icon -->
                                        <div class="count_icon">
                                            <span class="pe-7s-diamond"></span>
                                        </div>

                                        <!-- start count -->
                                        <div class="count">
                                            <span class="count_up"><input type="text" maxlength="3" class="form-control" id="service_value1" name="service_value1"   value="<?php echo $getdetails[0]['service_value1'];?>" placeholder="service value" >
                        </span>
                                            <p><input type="text" class="form-control" maxlength="20"  id="service_text1" name="service_text1"  value="<?php echo $getdetails[0]['service_text1'];?>"  placeholder="service text"  >
                     </p>
                                        </div>
                                    </div>

                                    <!-- start single_counter_wrapper -->
                                    <div class="single_counter_wrapper tiny_fullwidth xs_halfwidth">
                                        <!-- start count_icon -->
                                        <div class="count_icon">
                                            <span class="pe-7s-smile"></span>
                                        </div>

                                        <!-- start count -->
                                        <div class="count">
                                             <span class="count_up"><input type="text"  maxlength="3" class="form-control"  id="service_value2" name="service_value2"   value="<?php echo $getdetails[0]['service_value2'];?>" placeholder="service value" >
                        </span>
                                            <p><input type="text" class="form-control" maxlength="20" id="service_text2" name="service_text2"  value="<?php echo $getdetails[0]['service_text2'];?>"  placeholder="service text"  >
                     </p>
                                        </div>
                                    </div>

                                    <!-- start single_counter_wrapper -->
                                    <div class="single_counter_wrapper tiny_fullwidth xs_halfwidth">
                                        <!-- start count_icon -->
                                        <div class="count_icon">
                                            <span class="pe-7s-cup"></span>
                                        </div>

                                        <!-- start count -->
                                        <div class="count">
                                           <span class="count_up"><input type="text" maxlength="3" class="form-control"  id="service_value3" name="service_value3"   value="<?php echo $getdetails[0]['service_value3'];?>" placeholder="service value" >
                        </span>
 <p><input type="text"  id="service_text3" class="form-control" name="service_text3" maxlength="20" value="<?php echo $getdetails[0]['service_text3'];?>"  placeholder="service text"  >
                     </p>
                                        </div>
                                    </div>
                          <!-- start single_counter_wrapper -->
                                    <div class="single_counter_wrapper tiny_fullwidth xs_halfwidth">
                                       
                                        <div class="count_icon">
                                            <span class="pe-7s-wine"></span>
                                        </div>

                                     
                                        <div class="count">
                                            <span class="count_up"><input type="text" maxlength="3" class="form-control" id="service_value4" name="service_value4"   value="<?php echo $getdetails[0]['service_value4'];?>" placeholder="service value" >
                   </span>
                                            <p><input type="text"  id="service_text4" maxlength="20" class="form-control" name="service_text4"  value="<?php echo $getdetails[0]['service_text4'];?>"  placeholder="service text"  >
      </p>
                                        </div>
                                    </div>
									 
          
                                </div>
                            </div>
							
						 </div>
						 <button type="submit"  name="add_service" id="add_service" class="btn" > Save Details </button>
               
						<!-- end .row -->
                    </div>
					  <!-- end .container -->
                </div>
				
                <!-- end .counterup -->

                <!-- 
                <div class="working_process section_padding">
                   
                    <div class="container">
                       
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
                                    <h2>my WORKING PROCESS</h2>
                                    <p class="sub_title">METHOD OF BUSINESS PROCESS</p>
                                </div>
                            </div>
                        </div>

                       
                        <div class="row">
                           
                            <div class="col-md-4 ltr">
                             
                                <div class="single_working_process sm-half-width process_1">
                                    <p class="step">01</p>
                                    <h4 class="process_title">DISCUSS IDEA</h4>
                                    <p>I could describe these conceptions, also impress upon paper all that is living.</p>
                                </div>

                              
                                <div class="single_working_process sm-half-width process_2">
                                    <p class="step">02</p>
                                    <h4 class="process_title">CREATIVE CONCEPT</h4>
                                    <p>I could describe these conceptions, also impress upon paper all that is living.</p>
                                </div>
                            </div> -->

                            <!-- col->
                            <div class="col-md-4 hidden-sm hidden-xs">
                                <div class="working_process_circle">
                                    <h1>WORKING PROCESS</h1>
                                    <span class="dots top_left"></span>
                                    <span class="dots bottom_left"></span>
                                    <span class="dots top_right"></span>
                                    <span class="dots bottom_right"></span>
                                </div>
                            </div>-->

                            <!-- col-md-4 
                            <div class="col-md-4 rtl">
                               single_working_process 
                                <div class="single_working_process sm-half-width process_3">
                                    <p class="step">03</p>
                                    <h4 class="process_title">DISCUSS IDEA</h4>
                                    <p>I could describe these conceptions, also impress upon paper all that is living.</p>
                                </div>

                               
                                <div class="single_working_process sm-half-width process_4">
                                    <p class="step">04</p>
                                    <h4 class="process_title">CREATIVE CONCEPT</h4>
                                    <p>I could describe these conceptions, also impress upon paper all that is living.</p>
                                </div>
                            </div>
                        </div>
                </div>-->

			          
				</form>
				
            </section><!-- end #service_area  -->

            <!-- #portfolio area -->
            <section id="portfolio" class="portfolio section_padding single_page">
				  
                <!-- container -->
                <div class="container">
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section_title">
                                <h2>PROJECTS</h2>
                                <p class="sub_title">my latest works</p>
                            </div>
                        </div>
                    </div><!-- end .row -->

                    <!-- row -->
                    <!--<div class="row">
                        
                        <div class="col-md-12">
                           
                            <div class="filter_area">
                                <ul id="sorting_control">
                                    <li class="active"  data-uk-filter="">All works</li>
                                    <li data-uk-filter="web">web design</li>
                                    <li data-uk-filter="ux">UI/UX design</li>
                                    <li data-uk-filter="wordpress"> wordpress</li>
                                    <li data-uk-filter="branding"> branding</li>
                                </ul>
                            </div>
                        </div>
                    </div>  -->

                    <!-- row -->
					
					
                    <div class="row" data-uk-grid="{controls: '#sorting_control'}">
					
          
                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="wordpress">
						
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio1.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title1" name="project_title1"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title1'];?>">
                               </p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=1"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio1.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->
                         
                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="ux">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio2.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title2" name="project_title2"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title2'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=2"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio2.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="web">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio3.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title3" name="project_title3"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title3'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=3"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio3.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="ux">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio4.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title4" name="project_title4"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title4'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=4"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio4.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="web">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio5.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title5" name="project_title5"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title5'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=5"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio5.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="wordpress">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio2.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title6" name="project_title6"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title6'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=6"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio2.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="branding">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio3.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title7" name="project_title7"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title7'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=7"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio3.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4  col-xs-12 col-sm-6 md-half-width" data-uk-filter="wordpress">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio2.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title8" name="project_title8"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title8'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=8"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio2.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div><!-- end single_item -->

                        <!-- single_item -->
                        <div class="col-md-4 col-xs-12 col-sm-6  md-half-width" data-uk-filter="branding">
                            <div class="single_portfolio_item">
                                <figure>
                                    <div class="portfolio_img">
                                        <img src="http://localhost/Doctor_template/template3demo/template3/theme3ImageAttach/1/portfolio1.png" alt="Portfolio">
                                    </div>

                                    <figcaption>
                                        <p><input type="text" maxlength="200" id="project_title9" name="project_title9"  placeholder="Project Title" value="<?php echo $getdetails[0]['project_title9'];?>"></p>
                                        <div class="classless">
                                            <div class="icon"><a href="single_portfolio.php?id=9"><span class="fa fa-link"></span></a></div>
                                            <a class="icon venobox" href="images/portfolio1.png"><span class="fa fa-search"></span></a>
                                        </div>
                                    </figcaption>
                                </figure>
                            </div>
                        </div>
						
						     </div>
							
						<!-- end row -->
                </div><!-- end container -->
            
			</section>

            <!-- #blog -->
            <section id="blog" class="blog section_padding single_page">
                <!-- container -->
                <div class="container">
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section_title">
                                <h2>blog post</h2>
                                <p class="sub_title">latest news</p>
                            </div>
                        </div>
                    </div><!-- end .row -->

                    <!-- row -->
                    <div class="row" data-uk-grid>
                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- single_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog1.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">The new brand identity</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- single_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog2.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Make it clean and simple</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog3.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">A day alone at the office</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog4.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">My amazing office desk</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog5.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">A day alone at the office</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog6.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">The new brand identity</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog7.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Credibly brand standards</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog8.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Inspired by typography</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog9.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Some amazing buildings</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog9.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Some amazing buildings</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <figure>
                                    <img src="images/blog1.jpg" alt="Blog Images">
                                </figure>

                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">The new brand identity</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except...</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->

                        <!-- col-md-4 -->
                        <div class="col-md-4 col-sm-6 md-half-width">
                            <!-- sigle_blog -->
                            <article class="single_blog">
                                <div class="blog_content">
                                    <a href="single_blog.html"><h4 class="blog_title">Credibly brand standards</h4></a>
                                    <div class="date"><p>January 30, 2017</p></div>
                                    <p>Lorem ipsum dolor amet, consectetur they adipisicing elit. Inventore adipi except.
                                        When you are alone for days or weeks at a time, you eventually become drawn to When
                                        you are alone for days or weeks at a time, you eventually become drawn.</p>

                                    <ul class="meta_data">
                                        <li class="auth"><img src="images/auth.jpg" alt="Author Avatar"> <p>By Aazztech</p></li>
                                        <li class="tag"><span class="pe-7s-ticket"></span> <p>Design</p></li>
                                    </ul>
                                </div>
                            </article><!-- single_blog -->
                        </div><!-- end .col-md-4 -->
                    </div><!-- .row -->
                </div><!-- end container -->
            </section>
            <!-- end #blog -->

            <!-- start #contact -->
            <section id="contact" class="contact section_padding single_page">
			<form method="post" enctype="multipart/form-data" name="frmcontact" id="frmcontact" action="add_webdetails.php">
                        
             
                <!-- start .container -->
                <div class="container">
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section_title">
                                <h2>LET'S TALK</h2>
                                <p class="sub_title">Get in touch with me</p>
                            </div>
                        </div>
                    </div><!-- end .row -->

                    <!-- start row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact_txt">
                                <p>Please Feel free to get in Touch Anytime, whether for Work Inquiries or to just say Hello!</p>
                            </div>
                        </div>
                        
                       <!-- End col-md-5 -->

                        <div class="col-md-5 col-sm-6 col-lg-5 ">
                            <ul class="contact_info">
                                <li>
                                    <p class="info_title">ADDRESS</p>
                                    <p class="info"><input type="text" maxlength="200" name="contact_address_info" placeholder="address" id="contact_address_info"value="<?php echo $getdetails[0]['contact_address_info'];?>"></p>
                                </li>
                                <li>
                                    <p class="info_title">CONTACT INFO</p>
                                    <p class="info"><input type="text"  name="contact_email"placeholder="Email" id="contact_email"value="<?php echo $getdetails[0]['contact_email'];?>">
                            <br><br><input type="text"  name="contact_phone"placeholder="phone" id="contact_phone"value="<?php echo $getdetails[0]['contact_phone'];?>"> </p>
                                </li>
                                <li>
                                    <p class="info_title">WORKING HOURS</p>
                                    <p><input type="text"  name="contact_working_hours" placeholder="working hours" id="contact_working_hours" value="<?php echo $getdetails[0]['contact_working_hours'];?>"> </p>
                               
                                </li>
                            </ul>
                        </div>
						<div class="col-md-5 col-sm-6 col-lg-5 col-lg-offset-1">
                            <ul class="contact_info">
                                <li>
                                    <p class="info_title">FACEBOOK</p>
                                    <p class="info"><input type="text"  name="contact_facebook" placeholder="Facebook link" id="contact_facebook"value="<?php echo $getdetails[0]['contact_facebook'];?>"></p>
                                </li>
                                <li>
                                    <p class="info_title">TWITTER AND GOOGLE+</p>
                                    <p class="info"><input type="text"  name="contact_twitter" placeholder="Twitter link" id="contact_twitter" value="<?php echo $getdetails[0]['contact_twitter'];?>">
                            <br><br><input type="text"  name="contact_gplus" placeholder="Google+ link" id="contact_gplus" value="<?php echo $getdetails[0]['contact_gplus'];?>"> </p>
                                </li>
                                <li>
                                    <p class="info_title">LINKEDIN</p>
                                    <p><input type="text"  name="contact_linkedin" placeholder="Linkedin link" id="contact_linkedin" value="<?php echo $getdetails[0]['contact_linkedin'];?>"> </p>
                               
                                </li>
                            </ul>
                        </div>
                    </div>
					
					
					<div class="contact-text">
					
					<input type="hidden" id="lattitudevalue" name="lat_value" value="<?php echo $getdetails[0]['contact_latitude']; ?>"  />
					<input type="hidden" id="longitudevalue" name="long_value" value="<?php echo $getdetails[0]['contact_longitude']; ?>"  />
					<div>Your Latitude:   <label id="lat_value" name="lat_value" value="<?php echo $getdetails[0]['contact_latitude']; ?>"><?php echo $getdetails[0]['contact_latitude']; ?></label></div> 
					<div>Your Longitude: <label id="long_value" name="long_value" value="<?php echo $getdetails[0]['contact_longitude']; ?>"><?php echo $getdetails[0]['contact_longitude']; ?></label> </div> 
					
					<div id="map" style="width:300px;height:400px;"></div>
					</div>
					
					<script>
						  function initMap() {
							var uluru = {lat: 21.7679, lng: 78.8718};
							var map = new google.maps.Map(document.getElementById('map'), {
							  zoom: 4,
							  disableDoubleClickZoom: true,
							  center: uluru
							});
							var marker = new google.maps.Marker({
							  position: uluru,
							  map: map
							});  
							
							  // double click event
						  google.maps.event.addListener(map, 'dblclick', function(e) {
							var positionDoubleclick = e.latLng;
							marker.setPosition(positionDoubleclick);
							// if you don't do this, the map will zoom in
							 document.getElementById("lat_value").innerHTML = e.latLng.lat();
							document.getElementById("long_value").innerHTML = e.latLng.lng();
							
							document.getElementById("lattitudevalue").value  = e.latLng.lat();
							document.getElementById("longitudevalue").value  = e.latLng.lng();
						  });
					  google.maps.event.addDomListener(window, 'load', initialize);
						  }
	
					</script>
					<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbuQD_xndo_Xl53keO48KQMo0fLZ8RsxQ&callback=initMap"></script>

				
				<button type="submit"  name="add_contact" id="add_contact" class="btn" > Save Details </button>
				</form>

					<!-- end row -->
                </div><!-- start /.container -->

           <!-- start footer -->
                    <footer class="footer">
                        <p>	&copy; 2018 copyright  all right reserved. Designed and developed by<a href="https://www.medisensehealth.com/" > Medisense Healthcare</a></p>
						
                    </footer>
                <!-- end footer -->
			
            </section>
            <!-- end #contact -->

        </div><!-- end main_content -->
    </div><!-- end main wrapper /.site -->



    <!--//////////////////// JS GOES HERE ////////////////-->

    <!-- jquery latest version -->
    <script src="js/jquery-1.12.3.js"></script>

    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>

    <!-- jquery easing 1.3 -->
    <script src="js/jquery.easing1.3.js"></script>

    <!-- Owl carousel js-->
    <script src="js/owl.carousel.min.js"></script>

    <!-- venobox js -->
    <script src="js/venobox.min.js"></script>

    <!-- waypoint js -->
    <script src="js/waypoints.min.js"></script>

    <!-- Counter up js-->
    <script src="js/jquery.counterup.min.js"></script>

    <!-- google map js -->
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBeySPFGz7DIUTrReCRQT6HYaMM0ia0knA"></script>

    <!-- uikit js -->
    <script src="js/uikit.min.js"></script>

    <!-- jquery uikit js -->
    <script src="js/grid.min.js"></script>

    <!-- Typed js -->
    <script src="js/typed.min.js"></script>

    <!-- jQuery tubler js -->
    <script src="js/jquery.tubular.1.0.js"></script>

    <!-- Main js -->
    <script src="js/main.js"></script>

    <!-- GOOGLE MAP JS -->
   <!-- <script type="text/javascript" src="js/map.js"></script> -->
</body>
</html>
