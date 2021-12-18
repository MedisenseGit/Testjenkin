<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = 2030;
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
			
$getdetails = mysqlSelect("*","webtemplate4_details","doc_id='".$admin_id."' and doc_type=1","","","","");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<!-- Meta Tage -->
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta name="description" content="Type Your Website Description Here">
	<meta name="keywords" content="cv, portfolio, cv Html, Html, Html5, portfolio tamplate, personal website" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- Website Title Here -->
	<title>Doctor - Personal Portfolio Html5 Tamplate</title>
	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="favicon.png" />
	<!-- All Fonts Here -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700%7CRoboto" rel="stylesheet"> 
	<!-- All Style Here -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-3.3.7.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome-4.7.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/owl.carousel.css">
	<link rel="stylesheet" type="text/css" href="assets/css/magnific-popup.css">
	<link rel="stylesheet" type="text/css" href="assets/css/animate.css">
	<!-- Custom Style -->
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
	<script src="assets/js/modernizr.js"></script>
</head>
<body>
	<div id="home"></div>
	<!-- Preeloader  -->
	<div class="elegant-preeloader">
		<div class="preloader-spinner"></div>
	</div>
	<!-- Header Start Here -->
	<header>
		<!-- Menu Bars Start -->
		<div class="menu-bars">
			<span></span>
			<span></span>
			<span></span>
			<span></span>
		</div>
		<!-- Menu Bars Ends -->
		
		<!-- Nav Start Here -->
		<nav class="navigation">
			<div class="navigation-inner">
				<ul class="main-menu nav navbar-nav">
					<li class="active smooth-scroll"><a href="#home">Home</a></li>
					<li class="smooth-scroll"><a href="#about">About</a></li>
					<li class="smooth-scroll"><a href="#experiences">Experience</a></li>
					<li class="smooth-scroll"><a href="#services">Service</a></li>
					<li class="smooth-scroll"><a href="#portfolio">Portfolio</a></li>
					<!--<li class="smooth-scroll"><a href="#testimonial">Testimonial</a></li>-->
					<li class="smooth-scroll"><a href="#blog">blog </a></li>
					<li class="smooth-scroll"><a href="#contact">contact</a></li>
				</ul>
			</div>
		</nav>
		<!-- Nav Ends Here -->

		<!-- Start Hero Area Here -->
		<div class="hero-area" style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/hero-bg.png);">
 
		<form method="post" enctype="multipart/form-data" name="frmhome" id="frmhome" action="add_webdetails.php">
              			<div class="elegant-table">
				<div class="elegant-table-cell">
					<div class="container">
						<!-- Hero Content Start -->
						<div class="hero-content">
							<h5>Hello</h5>
							<h2>
								<span>I’m</span> <br><br>
								<input type="text"  maxlength="200" placeholder="Name" id="home_name" name="home_name" value="<?php echo $getdetails[0]['home_name']; ?>"  >
                                  </h2>  <br><br>
							<h2><input type="text"  maxlength="200" placeholder="Designation" id="home_designation" name="home_designation" value="<?php echo $getdetails[0]['home_designation']; ?>">
                                 </h2><br>
								 	<h4><strong>Upload  image</strong></h4>
							<label class="elegant-btn"><input type="file" name="txtPhoto" id="txtPhoto" /></label>
							<!--<a href="index.php" class="elegant-btn">Upload Image</a>
							<a href="index.html" class="elegant-btn">Download</a>-->
							
						</div>
						<!-- Hero Content Ends -->

						<!-- Social Area Start Here -->
						<div class="social-area">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-twitter"></i></a>
							<a href="#"><i class="fa fa-linkedin"></i></a>
							<a href="#"><i class="fa fa-youtube"></i></a>
						</div><br>
						<button type="submit"  name="add_home" id="add_home" class="elegant-btn" > Save Details </button>		 
					
						<!-- Social Area Ends Here -->
					</div>
				</div>
			</div>
			
			</form>
		</div>
		<!-- Ends Hero Area Here -->
	</header>
	<!-- Header Ends Here -->

	<!-- Start About Here -->
	<section class="section-padding" id="about">
	<form method="post" enctype="multipart/form-data" name="frmabout" id="frmabout" action="add_webdetails.php">
                 
		
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
					<div class="section-header">
						<h2 class="section-title">About <span>me</span></h2>
						<p><textarea  rows="3" maxlength="200" class="form-control" id="about_info" name="about_info"  placeholder="About me"  ><?php echo $getdetails[0]['about_info']; ?></textarea>    
												</p>
						</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4  col-sm-4">
					<div class="about-single-item">
						<h3 class="section-title">Personal information</h3>
						<ul class="personal-info">
							<li><span>Name</span><input type="text" class="form-control" placeholder="Name" id="about_name" name="about_name" value="<?php echo $getdetails[0]['about_name']; ?>">
                                 </li>
							<br>
							<li><span>Specialization</span><input type="text" class="form-control" placeholder="Specialization" id="about_specialization" name="about_specialization" value="<?php echo $getdetails[0]['about_specialization']; ?>">
                               </li><br>
                             
							<li><span>Experience</span><input type="text" class="form-control" placeholder="Experience" id="about_experience" name="about_experience" value="<?php echo $getdetails[0]['about_experience']; ?>">
                               </li><br>
							<li><span>Address</span><input type="text" class="form-control" placeholder="Address" id="about_address" name="about_address" value="<?php echo $getdetails[0]['about_address']; ?>">
                               </li>
							
							</ul>
							<br>
							<h4><strong>Upload signature image</strong></h4>
							<img src="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/signature.png"/>
							<br><br>
						<!--<img class="signature" src="assets/img/signature.png" alt="author signature">-->
						<label class="elegant-btn">	 <input type="file" name="txtPhoto6" id="txtPhoto6" /></label>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="about-single-item">
						<div class="skill-area">
							<h3 class="section-title">Language Skills</h3>
							<div class="progress-wraper">
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="200" class="form-control" placeholder="Skill Title" id="about_rating_name1" name="about_rating_name1" value="<?php echo $getdetails[0]['about_rating_name1']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value1" name="about_rating_value1" value="<?php echo $getdetails[0]['about_rating_value1']; ?>">
                             </h5> 
									
								</div><br>
							<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name2" name="about_rating_name2" value="<?php echo $getdetails[0]['about_rating_name2']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value2" name="about_rating_value2" value="<?php echo $getdetails[0]['about_rating_value2']; ?>">
                             </h5> 
									
								</div><br>
							<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name3" name="about_rating_name3" value="<?php echo $getdetails[0]['about_rating_name3']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value3" name="about_rating_value3" value="<?php echo $getdetails[0]['about_rating_value3']; ?>">
                             </h5> 
									
								</div><br>
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name4" name="about_rating_name4" value="<?php echo $getdetails[0]['about_rating_name4']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value4" name="about_rating_value4" value="<?php echo $getdetails[0]['about_rating_value4']; ?>">
                             </h5> 
									
								</div><br>
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name5" name="about_rating_name5" value="<?php echo $getdetails[0]['about_rating_name5']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value5" name="about_rating_value5" value="<?php echo $getdetails[0]['about_rating_value5']; ?>">
                             </h5> 
									
								</div><br>
								
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="about-single-item">
						<div class="skill-area">
							<h3 class="section-title">Softwere Skills</h3>
							<div class="progress-wraper">
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="200" class="form-control" placeholder="Skill Title" id="about_rating_name6" name="about_rating_name6" value="<?php echo $getdetails[0]['about_rating_name6']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value6" name="about_rating_value6" value="<?php echo $getdetails[0]['about_rating_value6']; ?>">
                             </h5> 
									
								</div><br>
							<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name7" name="about_rating_name7" value="<?php echo $getdetails[0]['about_rating_name7']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value7" name="about_rating_value7" value="<?php echo $getdetails[0]['about_rating_value7']; ?>">
                             </h5> 
									
								</div><br>
							<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name8" name="about_rating_name8" value="<?php echo $getdetails[0]['about_rating_name8']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value8" name="about_rating_value8" value="<?php echo $getdetails[0]['about_rating_value8']; ?>">
                             </h5> 
									
								</div><br>
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name9" name="about_rating_name9" value="<?php echo $getdetails[0]['about_rating_name9']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value9" name="about_rating_value9" value="<?php echo $getdetails[0]['about_rating_value9']; ?>">
                             </h5> 
									
								</div><br>
								<div class="single-progress-bar">
									<h5 class="progress-title"><input type="text"  maxlength="30" class="form-control" placeholder="Skill Title" id="about_rating_name10" name="about_rating_name10" value="<?php echo $getdetails[0]['about_rating_name10']; ?>">
                             </h5> 
									
		                   <h5 class="progress-title"><input type="text"  maxlength="4" class="form-control" placeholder="Rating value in %(ex:90%)" id="about_rating_value10" name="about_rating_value10" value="<?php echo $getdetails[0]['about_rating_value10']; ?>">
                             </h5> 
									
								</div><br>
								
							</div>
							
						</div>
					</div>
				</div>
					<button type="submit"  name="add_about" id="add_about" class="elegant-btn" > Save Details </button>		 
				
			</div>
		</div></form>
	</section>
	<!-- Ends About Here -->

	<!-- Start Experience Here -->
	<section class="section-padding" id="experiences">
	<form method="post" enctype="multipart/form-data" name="frmexp" id="frmexp" action="add_webdetails.php">
          
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="section-header">
						<h2 class="section-title">My <span>Experience</span></h2>
						<p><textarea  rows="3" class="form-control" maxlength="500" id="experience_info" name="experience_info"  placeholder="About experience"  ><?php echo $getdetails[0]['experience_info']; ?></textarea>    
										 </p>
					</div>
				</div>
			</div>
			<!-- Experiences Slides Start Here -->
			<div class="row">
			<div class="col-md-4">
				<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year1" name="experience_year1" value="<?php echo $getdetails[0]['experience_year1']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title1" name="experience_title1" value="<?php echo $getdetails[0]['experience_title1']; ?>">
                         </h5>   <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle1" name="experience_subtitle1" ><?php echo $getdetails[0]['experience_subtitle1']; ?></textarea>
                         </p>
					</div>
					</div>
					<div class="col-md-4">
					<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year2" name="experience_year2" value="<?php echo $getdetails[0]['experience_year2']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title2" name="experience_title2" value="<?php echo $getdetails[0]['experience_title2'];?>">
                         </h5> 
						  <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle2" name="experience_subtitle2" ><?php echo $getdetails[0]['experience_subtitle2']; ?></textarea>
                         </p>
					</div>
					</div>
				<div class="col-md-4">
					<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year3" name="experience_year3" value="<?php echo $getdetails[0]['experience_year3']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title3" name="experience_title3" value="<?php echo $getdetails[0]['experience_title3']; ?>">
                         </h5> 
						  <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle3" name="experience_subtitle3" ><?php echo $getdetails[0]['experience_subtitle3']; ?></textarea>
                         </p>
					</div>
					</div>
					</div>
					<br>
					<div class="row">
					<div class="col-md-4">
					<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year4" name="experience_year4" value="<?php echo $getdetails[0]['experience_year4']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title4" name="experience_title4" value="<?php echo $getdetails[0]['experience_title4']; ?>">
                         </h5> 
						  <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle4" name="experience_subtitle4" ><?php echo $getdetails[0]['experience_subtitle4']; ?></textarea>
                         </p>
					</div>
					</div>
					<div class="col-md-4">
					<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year5" name="experience_year5" value="<?php echo $getdetails[0]['experience_year5']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title5" name="experience_title5" value="<?php echo $getdetails[0]['experience_title5']; ?>">
                         </h5> 
						  <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle5" name="experience_subtitle5" ><?php echo $getdetails[0]['experience_subtitle5']; ?></textarea>
                         </p>
					</div>
					</div>
					<div class="col-md-4">
					<div class="experiences-single-box">
					<h3><input type="text"  class="form-control" placeholder="Year" id="experience_year6" name="experience_year6" value="<?php echo $getdetails[0]['experience_year6']; ?>">
                            </h3>
					<h5><input type="text"  class="form-control" placeholder="Designation" id="experience_title6" name="experience_title6" value="<?php echo $getdetails[0]['experience_title6']; ?>">
                         </h5> 
						  <br>
					<p><textarea  rows="3"  maxlength="500" class="form-control" placeholder="description" id="experience_subtitle6" name="experience_subtitle6" ><?php echo $getdetails[0]['experience_subtitle6']; ?></textarea>
                         </p>
					</div>
				</div>
				</div>
			<br>
<button type="submit"  name="add_experience" id="add_experience" class="elegant-btn"> Save Details </button>		 
				
			<!-- Experiences Slides Ends Here -->
		</div>
		</form>
	</section>
	<!-- Ends Experience Here -->

	<!-- Start Counter Here -->
	<div class="section-padding" id="counter">
	<form method="post" enctype="multipart/form-data" name="frmscount" id="frmscount" action="add_webdetails.php">
       
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<!-- Counter Single Box -->
					<div class="counter-box">
						<div class="counter-icons">
							<i class="fa fa-users"></i>
							<p><input type="text"  class="form-control" placeholder="Service text" id="experience_name1" name="experience_name1" value="<?php echo $getdetails[0]['experience_name1']; ?>">
                       </p>
					   <p><input type="text" maxlength="3" class="form-control" placeholder="Service Value" id="experience_value1" name="experience_value1" value="<?php echo $getdetails[0]['experience_value1']; ?>">
                       </p>
						</div>
						
					</div>
					<!-- Counter Single Box -->
				</div>
				<div class="col-sm-3">
					<!-- Counter Single Box -->
					<div class="counter-box">
						<div class="counter-icons">
							<i class="fa fa-users"></i>
							<p><input type="text"  class="form-control" placeholder="Service text" id="experience_name2" name="experience_name2" value="<?php echo $getdetails[0]['experience_name2']; ?>">
                       </p>
					   <p><input type="text" maxlength="3" class="form-control" placeholder="Service Value" id="experience_value2" name="experience_value2" value="<?php echo $getdetails[0]['experience_value2']; ?>">
                       </p>
						</div>
						
					</div>
					<!-- Counter Single Box -->
				</div>
				<div class="col-sm-3">
					<!-- Counter Single Box -->
					<div class="counter-box">
						<div class="counter-icons">
							<i class="fa fa-users"></i>
							<p><input type="text"  class="form-control" placeholder="Service text" id="experience_name3" name="experience_name3" value="<?php echo $getdetails[0]['experience_name3']; ?>">
                       </p>
					   <p><input type="text" maxlength="3" class="form-control" placeholder="Service Value" id="experience_value3" name="experience_value3" value="<?php echo $getdetails[0]['experience_value3']; ?>">
                       </p>
						</div>
						
					</div>
					<!-- Counter Single Box -->
				</div>
				<div class="col-sm-3">
					<!-- Counter Single Box -->
					<div class="counter-box">
						<div class="counter-icons">
							<i class="fa fa-users"></i>
							<p><input type="text"  class="form-control" placeholder="Service text" id="experience_name4" name="experience_name4" value="<?php echo $getdetails[0]['experience_name4']; ?>">
                       </p>
					   <p><input type="text" maxlength="3" class="form-control" placeholder="Service Value" id="experience_value4" name="experience_value4" value="<?php echo $getdetails[0]['experience_value4']; ?>">
                       </p>
						</div>
						
					</div>
					<!-- Counter Single Box -->
				</div>
				<br>
				<button type="submit"  name="add_scount" id="add_scount" class="blog-btn"> Save Details </button>		 
		
			</div>
		</div>
		</form>
	</div>
	<!-- Ends Counter Here -->

	<!-- Start Work Here -->
	<section class="section-padding" id="services">
	<form method="post" enctype="multipart/form-data" name="frmservice" id="frmservice" action="add_webdetails.php">
       
		<div class="container">
			<div class="row">
				<div class="col-md-7">
					<div class="section-header">
						<h2 class="section-title">What <span>i do?</span></h2>
						<p><textarea  rows="2" maxlength="200" class="form-control" placeholder="About Services" id="service_info" name="service_info"><?php echo $getdetails[0]['service_info'];?></textarea>
                      </p>
						</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5 col-md-offset-1">
					<div class="work-box">
						<div class="work-box-single-item">
							<div class="work-icon"><i class="fa fa-laptop"></i></div>
							<h5><input type="text" maxlength="200" class="form-control" placeholder="Service Title1" id="service_title1" name="service_title1" value="<?php echo $getdetails[0]['service_title1']; ?>">
                    </h5>
							<p> <textarea rows="3" maxlength="600" class="form-control" placeholder="description" id="service_description1" name="service_description1"><?php echo $getdetails[0]['service_description1'];?></textarea>
                        </p>
							</div>
						<div class="work-box-single-item">
							<div class="work-icon"><i class="fa fa-laptop"></i></div>
							<h5><input type="text" maxlength="200" class="form-control" placeholder="Service Title2" id="service_title2" name="service_title2" value="<?php echo $getdetails[0]['service_title2']; ?>">
                     </h5>
							<p><textarea rows="3" maxlength="600" class="form-control" placeholder="description" id="service_description2" name="service_description2"><?php echo $getdetails[0]['service_description2'];?></textarea>
      </p>
							</div>
						<div class="work-box-single-item">
							<div class="work-icon"><i class="fa fa-laptop"></i></div>
							<h5><input type="text" maxlength="200" class="form-control" placeholder="Service Title3" id="service_title3" name="service_title3" value="<?php echo $getdetails[0]['service_title3']; ?>">
                      </h5>
							<p><textarea rows="3" maxlength="600" class="form-control" placeholder="description" id="service_description3" name="service_description3"><?php echo $getdetails[0]['service_description3'];?></textarea>
      </p></div>
	 
					</div>
					
				</div>
				<div class="col-md-5 col-md-offset-1">
				<h3><strong>Upload Background Image</strong></h3>
				<label class="elegant-btn">	 <input type="file" name="txtPhoto7" id="txtPhoto7" /></label>
				<br> <br>
					 <button type="submit"  name="add_service" id="add_service" class="elegant-btn"> Save Details </button>		 
				
			</div>	
			</div>
		</div>
		</form>
	</section>
	<!-- Ends Work Here -->

	<!-- Start Portfolio Here -->
	<section class="section-padding" id="portfolio">
	<form method="post" enctype="multipart/form-data" name="frmproject" id="frmproject" action="add_webdetails.php">
       
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="section-header">
						<h2 class="section-title">Best <span>Project</span></h2>
						<p><textarea rows="3" maxlength="200" class="form-control" placeholder="About Project" id="project_info" name="project_info"><?php echo $getdetails[0]['project_info'];?></textarea>
      </p>
						</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<!-- Portfolio Navigation Start Here -->
					<!--<ul class="portfolio-navigation">
						<li class="power-btn active" data-filter="*">All</li>
						<li class="power-btn" data-filter=".graphic">Graphic</li>
						<li class="power-btn" data-filter=".photo">Photo</li>
						<li class="power-btn" data-filter=".design">Design</li>
					</ul>-->
					<!-- Portfolio Navigation Ends Here -->

					<!-- Portfolio List Start Here -->
					<div class="row portfolio-list">
						<!-- Single Portfolio Item Start Here -->
						<div class="col-md-6 col-sm-6 col-xs-12 graphic">
							<div class="single-portfolio-item" style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-1.jpg);">
							  <div class="portfolio-overlay elegant-table">
									<div class="elegant-table-cell">
										<a class="portfolio-view" href="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-1.jpg"><i class="fa fa-arrows-alt"></i></a>
										<h4><input type="text" maxlength="200" class="form-control" placeholder="Project title1" id="project_title1" name="project_title1" value="<?php echo $getdetails[0]['project_title1']; ?>">
                     <span><textarea rows="4" maxlength="200" class="form-control" placeholder="Description" id="project_description1" name="project_description1"><?php echo $getdetails[0]['project_description1'];?></textarea>
 </span></h4><br>
									<label class="elegant-btn">	 <input type="file" name="txtPhoto1" id="txtPhoto1" /></label>
							</div>
								</div>
							</div>
						</div>
						<!-- Single Portfolio Item Ends Here -->
						
						<!-- Single Portfolio Item Start Here -->
						<div class="col-md-6 col-sm-6 col-xs-12 photo design">
							<div class="single-portfolio-item" style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-2.jpg);">
							
								<div class="portfolio-overlay elegant-table">
									<div class="elegant-table-cell">
										<a class="portfolio-view" href="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-2.jpg"><i class="fa fa-arrows-alt"></i></a>
										<h4><input type="text" maxlength="200" class="form-control" placeholder="Project title2" id="project_title2" name="project_title2" value="<?php echo $getdetails[0]['project_title2']; ?>">
                     <span><textarea rows="4" maxlength="200" class="form-control" placeholder="Description" id="project_description2" name="project_description2"><?php echo $getdetails[0]['project_description2'];?></textarea>
 </span></h4><br>
									<label class="elegant-btn">	 <input type="file" name="txtPhoto2" id="txtPhoto2" /></label>
						</div>
								</div>
							</div>
						</div>
						<!-- Single Portfolio Item Ends Here -->
						
						<!-- Single Portfolio Item Start Here -->
						<div class="col-md-6 col-sm-6 col-xs-12 graphic design">
							<div class="single-portfolio-item " style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-3.jpg);">
						
								<div class="portfolio-overlay elegant-table">
									<div class="elegant-table-cell">
										<a class="portfolio-view" href="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-3.jpg"><i class="fa fa-arrows-alt"></i></a>
										<h4><input type="text" maxlength="200" class="form-control" placeholder="Project title3" id="project_title3" name="project_title3" value="<?php echo $getdetails[0]['project_title3']; ?>">
                     <span><textarea rows="4" maxlength="200" class="form-control" placeholder="Description" id="project_description3" name="project_description3"><?php echo $getdetails[0]['project_description3'];?></textarea>
 </span></h4><br>
									<label class="elegant-btn">	 <input type="file" name="txtPhoto3" id="txtPhoto3" /></label>
						</div>
								</div>
							</div>
						</div>
						<!-- Single Portfolio Item Ends Here -->
						
						<!-- Single Portfolio Item Start Here -->
						<div class="col-md-6 col-sm-6 col-xs-12 photo design">
							<div class="single-portfolio-item" style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-4.jpg);">
						
								<div class="portfolio-overlay elegant-table">
									<div class="elegant-table-cell">
										<a class="portfolio-view" href="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-4.jpg"><i class="fa fa-arrows-alt"></i></a>
										<h4><input type="text" maxlength="200" class="form-control" placeholder="Project title4" id="project_title4" name="project_title4" value="<?php echo $getdetails[0]['project_title4']; ?>">
                     <span><textarea rows="4" maxlength="200" class="form-control" placeholder="Description" id="project_description4" name="project_description4"><?php echo $getdetails[0]['project_description4'];?></textarea>
 </span></h4><br>
									<label class="elegant-btn">	 <input type="file" name="txtPhoto4" id="txtPhoto4" /></label>
						</div>
								</div>
							</div>
						</div>
						<!-- Single Portfolio Item Ends Here -->
						
						<!-- Single Portfolio Item Start Here -->
						<div class="col-md-6 col-sm-6 col-xs-12 design graphic">
							<div class="single-portfolio-item" style="background-image: url(http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-5.jpg);">
						
								<div class="portfolio-overlay elegant-table">
									<div class="elegant-table-cell">
										<a class="portfolio-view" href="http://localhost/Doctor_template/template4demo/template4/theme4ImageAttach/1/work-5.jpg"><i class="fa fa-arrows-alt"></i></a>
										<h4><input type="text" maxlength="200" class="form-control" placeholder="Project title5" id="project_title5" name="project_title5" value="<?php echo $getdetails[0]['project_title5']; ?>">
                     <span><textarea rows="4" maxlength="200" class="form-control" placeholder="Description" id="project_description5" name="project_description5"><?php echo $getdetails[0]['project_description5'];?></textarea>
 </span></h4><br>
						<label class="elegant-btn">	 <input type="file" name="txtPhoto5" id="txtPhoto5" /></label>
						</div>
								</div>
							</div>
						</div>
						
						<!-- Single Portfolio Item Ends Here -->
					</div>
					
					
					<!-- Portfolio List Ends Here -->
				</div>
			
			</div>
			<br><br>
				<button type="submit"  name="add_project" id="add_project" class="elegant-btn"> Save Details </button>		 
				
		</div>
		</form>
	</section>
	<!-- Ends Portfolio Here -->

	<!-- Start Testimonial Here -->
	<!--<section class="section-padding" id="testimonial">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="section-header">
						<h2 class="section-title">what <span>client say?</span></h2>
						<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal .</p>
					</div>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 ">
					<div class="testimonial-slides">
						<div class="testimonial-single-item text-center">
							<img src="assets/img/testimonial/1.jpg" alt="testimonial" class="client-img">
							<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 
							</p>
							<h5>Abraham khan <span>Developer</span></h5>
						</div>
						<div class="testimonial-single-item text-center">
							<img src="assets/img/testimonial/2.jpg" alt="testimonial" class="client-img">
							<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 
							</p>
							<h5>Abraham khan <span>Developer</span></h5>
						</div>
						<div class="testimonial-single-item text-center">
							<img src="assets/img/testimonial/3.jpg" alt="testimonial" class="client-img">
							<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 
							</p>
							<h5>Abraham khan <span>Developer</span></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Ends Testimonial Here -->

	<!-- Start Blog Here -->
	<section class="section-padding" id="blog">
		<div class="container">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="section-header">
						<h2 class="section-title">My <span>Blog Post</span></h2>
						<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal .</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<!-- Single Blog Post Start Here -->
					<div class="single-blog-post">
						<!-- Blog Box Background Start Here -->
						<div class="blog-box-bg blog-box-bg-1"></div>
						<!-- Blog Box Background Ends Here -->

						<!-- Blog Content Box Start Here -->
						<div class="blog-content-box">
							<div class="blog-meta">
								<p>April 13, 2017 by <a href="#">John</a></p>
								<span><a href="#"><i class="fa fa-user"></i> 594</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 19</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 33</a></span>
							</div>
							<div class="blog-content">
								<h5>More-or-less normal distribution</h5>
								<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
								<a class="blog-btn" href="#">read more</a>
							</div>
						</div>
						<!-- Blog Content Box Ends Here -->
					</div>
					<!-- Single Blog Post Ends Here -->
				</div>
				<div class="col-md-4">
					<!-- Single Blog Post Start Here -->
					<div class="single-blog-post">
						<!-- Blog Box Background Start Here -->
						<div class="blog-box-bg blog-box-bg-2"></div>
						<!-- Blog Box Background Ends Here -->

						<!-- Blog Content Box Start Here -->
						<div class="blog-content-box">
							<div class="blog-meta">
								<p>April 13, 2017 by <a href="#">John</a></p>
								<span><a href="#"><i class="fa fa-user"></i> 594</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 19</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 33</a></span>
							</div>
							<div class="blog-content">
								<h5>More-or-less normal distribution</h5>
								<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
								<a class="blog-btn" href="#">read more</a>
							</div>
						</div>
						<!-- Blog Content Box Ends Here -->
					</div>
					<!-- Single Blog Post Ends Here -->
				</div>
				<div class="col-md-4">
					<!-- Single Blog Post Start Here -->
					<div class="single-blog-post">
						<!-- Blog Box Background Start Here -->
						<div class="blog-box-bg blog-box-bg-3"></div>
						<!-- Blog Box Background Ends Here -->

						<!-- Blog Content Box Start Here -->
						<div class="blog-content-box">
							<div class="blog-meta">
								<p>April 13, 2017 by <a href="#">John</a></p>
								<span><a href="#"><i class="fa fa-user"></i> 594</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 19</a></span>
								<span><a href="#"><i class="fa fa-user"></i> 33</a></span>
							</div>
							<div class="blog-content">
								<h5>More-or-less normal distribution</h5>
								<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
								<a class="blog-btn" href="#">read more</a>
							</div>
						</div>
						<!-- Blog Content Box Ends Here -->
					</div>
					<!-- Single Blog Post Ends Here -->
				</div>
			</div>
		</div>
	</section>
	<!-- Ends Blog Here -->

	<!-- Start Media Partnar Here -->
	<!--<div class="section-padding" id="branding">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					
					<div class="brand-slides">
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/envato.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/themeforest.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/photodune.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/codecanyon.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/videohive.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
						<div class="brand-single-item">
							<p>
								<img src="assets/img/brand/audiojungle.png" alt="Brand Logo">
								<span>Our Partner</span>
							</p>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>-->
	<!-- Ends Media Partnar Here -->

	<!-- Start Contact Here -->
	<section class="section-padding" id="contact">
	<form method="post" enctype="multipart/form-data" name="frmcontact" id="frmcontact" action="add_webdetails.php">
       
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				
					<div class="contact-us text-center">
						<h2 class="section-title">Get <span>In touch</span></h2>
						<div class="col-md-6">
						<h3><input type="text" class="form-control"   placeholder="Email" id="contact_email" name="contact_email" value="<?php echo $getdetails[0]['contact_email']; ?>">
                   </h3>  
					<h3><input type="text" class="form-control" placeholder="Contact No" id="contact_phone" name="contact_phone" value="<?php echo $getdetails[0]['contact_phone']; ?>">
                   </h3>
				   <h3><input type="text" class="form-control"  placeholder="Facebook link" id="contact_facebook" name="contact_facebook" value="<?php echo $getdetails[0]['contact_facebook']; ?>">
                   </h3>  
					</div>
					<div class="col-md-6">
						<h3><input type="text" class="form-control"   placeholder="Twitter link" id="contact_twitter" name="contact_twitter" value="<?php echo $getdetails[0]['contact_twitter']; ?>">
                   </h3>  
						<h3><input type="text" class="form-control"   placeholder="Linkedin link" id="contact_linkedin" name="contact_linkedin" value="<?php echo $getdetails[0]['contact_linkedin']; ?>">
                    </h3>
					<h3><input type="text" class="form-control"   placeholder="Youtube link" id="contact_youtube" name="contact_youtube" value="<?php echo $getdetails[0]['contact_youtube']; ?>">
                   </h3>  
					</div>
					</div>
					
				</div>
				<button type="submit"  name="add_contact" id="add_contact" class="elegant-btn"> Save Details </button>		 
		
			</div>
		
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="social-area">
						<a href="#"><i class="fa fa-facebook"></i></a>
						<a href="#"><i class="fa fa-twitter"></i></a>
						<a href="#"><i class="fa fa-linkedin"></i></a>
						<a href="#"><i class="fa fa-youtube"></i></a>
					</div>
				</div>
			</div>
		</div>
		</form>
	</section>
	<!-- Ends Contact Here -->

	<!-- Start The ScrollToTop Here -->
	<div class="ScrollToTop">
		<a href="#"><i class="fa fa-angle-up"></i></a>
	</div>
	<!-- ScrollToTop Ends Here -->

	<!-- Start Footer Here -->
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="copyright">
						 &copy; Copyright 2018 All Right Reserved. Design by <a href="https://www.medisensehealth.com/" target="_blank">Medisense Healthcare</a>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- Ends Footer Here -->

	<!--
		All Scripts File Here 
	=========================================== -->
	<script type="text/javascript" src="assets/js/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap-3.3.7.min.js"></script>
	<script type="text/javascript" src="assets/js/owl.carousel.min.js"></script>
	<script type="text/javascript" src="assets/js/isotope-3.0.4.min.js"></script>
	<script type="text/javascript" src="assets/js/magnific-popup.min.js"></script>
	<script type="text/javascript" src="assets/js/counterup.min.js"></script>
	<script type="text/javascript" src="assets/js/waypoints.min.js"></script>
	<script type="text/javascript" src="assets/js/easing.min.js"></script>
	<script type="text/javascript" src="assets/js/wow.min.js"></script>
	<!-- Active Scripts Here -->
	<script type="text/javascript" src="assets/js/active.js"></script>
</body>
</html>
