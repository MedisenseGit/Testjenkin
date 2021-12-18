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
			
$getdetails = mysqlSelect("*","webtemplate2_details","doc_id='".$admin_id."' and doc_type=1","","","","");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Faculty-Responsive university staff personal page</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="author" content="owwwlab.com">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <meta name="description" content="A theme for faculty profile page" />
        <meta name="keywords" content="faculty profile, theme,css, html, jquery, transition, transform, 3d, css3" />

        <link rel="shortcut icon" href="../favicon.ico">

        <!--CSS styles-->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">  
        <link rel="stylesheet" href="css/perfect-scrollbar-0.4.5.min.css">
        <link rel="stylesheet" href="css/magnific-popup.css">
        <link rel="stylesheet" href="css/style.css">
        <link id="theme-style" rel="stylesheet" href="css/styles/default.css">

        
        <!--/CSS styles-->
        <!--Javascript files-->
        <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="js/TweenMax.min.js"></script>
        <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
        <script type="text/javascript" src="js/jquery.carouFredSel-6.2.1-packed.js"></script>
        
        <script type="text/javascript" src="js/modernizr.custom.63321.js"></script>
        <script type="text/javascript" src="js/jquery.dropdownit.js"></script>

        <script type="text/javascript" src="js/jquery.stellar.min.js"></script>
        <script type="text/javascript" src="js/ScrollToPlugin.min.js"></script>

        <script type="text/javascript" src="js/bootstrap.min.js"></script>

        <script type="text/javascript" src="js/jquery.mixitup.min.js"></script>

        <script type="text/javascript" src="js/masonry.min.js"></script>

        <script type="text/javascript" src="js/perfect-scrollbar-0.4.5.with-mousewheel.min.js"></script>

        <script type="text/javascript" src="js/magnific-popup.js"></script>
        <script type="text/javascript" src="js/custom.js"></script>

        <!--/Javascript files-->

    </head>
    <body>

        <div id="wrapper">
            <a href="#sidebar" class="mobilemenu"><i class="icon-reorder"></i></a>

            <div id="sidebar">
                <div id="main-nav">
                    <div id="nav-container">
                        <div id="profile" class="clearfix">
                            <div class="portrate hidden-xs" style="background-image: url(http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/<?php echo $getdetails[0]['webtemplate2_deatil_id']; ?>/<?php echo $getdetails[0]['about_profile_img']?>);"></div>
                            <div class="title">
                                <h2><?php echo $getdetails[0]['about_name']?></h2>
                                <h3><?php echo $getdetails[0]['about_company']; ?></h3>
								<h3><?php echo $getdetails[0]['about_place']; ?></h3>
								
                            </div>
                            
                        </div>
                        <ul id="navigation">
                            <li>
                              <a href="#biography">
                                <div class="icon icon-user"></div>
                                <div class="text">About Me</div>
                              </a>
                            </li>  
                            
                            <li>
                              <a href="#research">
                                <div class="icon icon-book"></div>
                                <div class="text">Research</div>
                              </a>
                            </li> 
                            
                            <li>
                              <a href="#publications">
                                <div class="icon icon-edit"></div>
                                <div class="text">Publications</div>
                              </a>
                            </li> 

                            <li>
                              <a href="#teaching">
                                <div class="icon icon-time"></div>
                                <div class="text">Teaching</div>
                              </a>
                            </li>

                            <li>
                              <a href="#gallery">
                                <div class="icon icon-picture"></div>
                                <div class="text">Gallery</div>
                              </a>
                            </li>

                            <li>
                              <a href="#contact">
                                  <div class="icon icon-calendar"></div>
                                  <div class="text">Contact & Meet Me</div>
                              </a>
                            </li>

                            <!--<li class="external">
                              <a href="#">
                                  <div class="icon icon-download-alt"></div>
                                  <div class="text">Download CV</div>
                              </a>
                            </li>-->
                        </ul>
                    </div>        
                </div>
                
                <div class="social-icons">
                    <ul>
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-linkedin"></i></a></li>
                    </ul>
                </div>    
            </div>

            <div id="main">
            
                <div id="biography" class="page home" data-pos="home">
				<form method="post" enctype="multipart/form-data" name="frmAddAbout" id="frmAddAbout" action="add_webdetails.php">
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <div class="row">
                                    <div class="col-sm-2 visible-sm"></div>
                                    <div class="col-sm-8 col-md-5">
                                        <div class="biothumb">
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/<?php echo $getdetails[0]['webtemplate2_deatil_id']; ?>/<?php echo $getdetails[0]['about_image']?>" class="img-responsive">
										   <!--<label>Upload image</label>
										   		<label class="buttonsmall"> <input type="file" name="txtPhoto" id="txtPhoto" /></label>  -->
                                            <div class="overlay">
                                                
                                               <h3> <input type="text" class="form-control" id="about_name" name="about_name"  value="<?php echo $getdetails[0]['about_name']; ?>" placeholder="Name" ></h3>
                                                <ul class="list-unstyled">
                                                    <li> <input type="text" id="about_designation" name="about_designation" class="form-control" value="<?php echo $getdetails[0]['about_designation']; ?>" placeholder="Designation" ></li><br>
                                                    <li><input type="text" id="about_company" name="about_company" class="form-control" value="<?php echo $getdetails[0]['about_company']; ?>" placeholder="Company" ></li><br>
                                                    <li><input type="text" id="about_place" name="about_place" class="form-control" value="<?php echo $getdetails[0]['about_place']; ?>" placeholder="Place" ></li><br>
                                                </ul>
												      </div> 
                                            
                                               
                                        </div>
                                        
                                    </div>
                                    <div class="clearfix visible-sm visible-xs"></div>
                                    <div class="col-sm-12 col-md-7">
                                        <h3 class="title">Bio</h3>
                                        <p><textarea class="form-control" rows="13" maxlength="900" id="about_bio" name="about_bio" placeholder="about bio" ><?php echo $getdetails[0]['about_bio']; ?></textarea> </p> 
										</div>  
										
                                    <label class="buttonsmall"> <input type="file" name="txtPhoto" id="txtPhoto" /></label> 
												<label class="buttonsmall"> <input type="file" name="txtPhoto1" id="txtPhoto1" /></label> 
                                      
                                </div>
                            </div>        
                        </div>
                    </div>

                    <div class="pagecontents">
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="title text-center">
                                            <h3>Administrative Positions</h3>
                                        </div>
                                        <ul class="ul-dates">
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_admin_to1" name="about_admin_to1"  value="<?php echo $getdetails[0]['about_admin_to1']; ?>" placeholder="Year" ></span>
                                                    <span><input type="text" class="form-control" id="about_admin_from1" name="about_admin_from1"  value="<?php echo $getdetails[0]['about_admin_from1']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_admin_title1" name="about_admin_title1"  value="<?php echo $getdetails[0]['about_admin_title1']; ?>" placeholder="Designation" ></h4>
                                                    <p><input type="text" class="form-control" id="about_admin_subtitle1" name="about_admin_subtitle1"  value="<?php echo $getdetails[0]['about_admin_subtitle1']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_admin_to2" name="about_admin_to2"  value="<?php echo $getdetails[0]['about_admin_to2']; ?>" placeholder="Year" ></span>
                                                    <span><input type="text" class="form-control" id="about_admin_from2" name="about_admin_from2"  value="<?php echo $getdetails[0]['about_admin_from2']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_admin_title2" name="about_admin_title2"  value="<?php echo $getdetails[0]['about_admin_title2']; ?>" placeholder="Designation" ></h4>
                                                    <p><input type="text" class="form-control" id="about_admin_subtitle2" name="about_admin_subtitle2"  value="<?php echo $getdetails[0]['about_admin_subtitle2']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_admin_to3" name="about_admin_to3"  value="<?php echo $getdetails[0]['about_admin_to3']; ?>" placeholder="Year" ></span>
                                                    <span><input type="text" class="form-control" id="about_admin_from3" name="about_admin_from3"  value="<?php echo $getdetails[0]['about_admin_from3']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_admin_title3" name="about_admin_title3"  value="<?php echo $getdetails[0]['about_admin_title3']; ?>" placeholder="Designation" ></h4>
                                                    <p><input type="text"  class="form-control"id="about_admin_subtitle2" name="about_admin_subtitle3"  value="<?php echo $getdetails[0]['about_admin_subtitle3']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_admin_to4" name="about_admin_to4"  value="<?php echo $getdetails[0]['about_admin_to4']; ?>" placeholder="Year" ></span>
                                                    <span><input type="text" class="form-control" id="about_admin_from4" name="about_admin_from4"  value="<?php echo $getdetails[0]['about_admin_from4']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_admin_title4" name="about_admin_title4"  value="<?php echo $getdetails[0]['about_admin_title4']; ?>" placeholder="Designation" ></h4>
                                                    <p><input type="text" class="form-control" id="about_admin_subtitle4" name="about_admin_subtitle4"  value="<?php echo $getdetails[0]['about_admin_subtitle4']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="title text-center">
                                            <h3>Education & Training</h3>
                                        </div>
                                       <!-- <ul class="ul-card">
                                            <li>
                                                <div class="dy">
                                                    <span class="degree"><input type="text" class="form-control" id="about_edu_year1" name="about_edu_year1"  value="<?php echo $getdetails[0]['about_edu_year1']; ?>" placeholder="Ph.D"> </span>
                                                    <span class="year">1890</span>
                                                </div>
                                                <div class="description">
                                                   <h4> <p class="waht"><input type="text" class="form-control" id="about_edu_title1" name="about_edu_title1"  value="<?php echo $getdetails[0]['about_edu_title1']; ?>" placeholder="Stream" ></p>  </h4>
                                                    <p class="where"><input type="text" class="form-control" id="about_edu_subtitle1" name="about_edu_subtitle1"  value="<?php echo $getdetails[0]['about_edu_subtitle1']; ?>" placeholder="University" ></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dy">
                                                    <span class="degree">M.B.A.</span><span class="year">1993</span>
                                                </div>
                                                <div class="description">
                                                    <p class="waht"><input type="text" class="form-control" id="about_edu_title2" name="about_edu_title2"  value="<?php echo $getdetails[0]['about_edu_title2']; ?>" placeholder="Stream" ></p>
                                                    <p class="where"><input type="text" class="form-control"  id="about_edu_subtitle2" name="about_edu_subtitle2"  value="<?php echo $getdetails[0]['about_edu_subtitle2']; ?>" placeholder="University" ></p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="dy">
                                                    <span class="degree">B.A.</span><span class="year">1989</span>
                                                </div>
                                                <div class="description">
                                                    <p class="waht"><input type="text" class="form-control"  id="about_edu_title3" name="about_edu_title3"  value="<?php echo $getdetails[0]['about_edu_title3']; ?>" placeholder="Stream" ></p>
                                                    <p class="where"><input type="text" class="form-control"  id="about_edu_subtitle3" name="about_edu_subtitle3"  value="<?php echo $getdetails[0]['about_edu_subtitle3']; ?>" placeholder="University" ></p>
                                                </div>
                                            </li>
                                            
                                        </ul>-->
										 <ul class="ul-dates">
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_edu_name1" name="about_edu_name1"  value="<?php echo $getdetails[0]['about_edu_name1']; ?>" placeholder="Course" ></span>
                                                    <span><input type="text" class="form-control" id="about_edu_year1" name="about_edu_year1"  value="<?php echo $getdetails[0]['about_edu_year1']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_edu_title1" name="about_edu_title1"  value="<?php echo $getdetails[0]['about_edu_title1']; ?>" placeholder="Education" ></h4>
                                                    <p><input type="text" class="form-control" id="about_edu_subtitle1" name="about_edu_subtitle1"  value="<?php echo $getdetails[0]['about_edu_subtitle1']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
											<br>
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_edu_name2" name="about_edu_name2"  value="<?php echo $getdetails[0]['about_edu_name2']; ?>" placeholder="Course" ></span>
                                                    <span><input type="text" class="form-control" id="about_edu_year2" name="about_edu_year2"  value="<?php echo $getdetails[0]['about_edu_year2']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_edu_title2" name="about_edu_title2"  value="<?php echo $getdetails[0]['about_edu_title2']; ?>" placeholder="Education" ></h4>
                                                    <p><input type="text" class="form-control" id="about_edu_subtitle2" name="about_edu_subtitle2"  value="<?php echo $getdetails[0]['about_edu_subtitle2']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
											<br>
                                            <li>
                                                <div class="dates">
                                                    <span><input type="text" class="form-control" id="about_edu_name3" name="about_edu_name3"  value="<?php echo $getdetails[0]['about_edu_name3']; ?>" placeholder="Course" ></span>
                                                    <span><input type="text" class="form-control" id="about_edu_year3" name="about_edu_year3"  value="<?php echo $getdetails[0]['about_edu_year3']; ?>" placeholder="Year" ></span>
                                                </div>
                                                <div class="content">
                                                    <h4><input type="text" class="form-control" id="about_edu_title3" name="about_edu_title3"  value="<?php echo $getdetails[0]['about_edu_title3']; ?>" placeholder="Education" ></h4>
                                                    <p><input type="text"  class="form-control"id="about_edu_subtitle2" name="about_edu_subtitle3"  value="<?php echo $getdetails[0]['about_edu_subtitle3']; ?>" placeholder="Institute" ></p>
                                                </div>
                                            </li>
                                            
                                        </ul>

                                    </div>    
                                </div>    
                            </div>
                                
                        </div>

                        <div class="section color-2">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="title text-center">
                                            <h3>Honors and awards</h3>
                                        </div>
                                        <ul class="timeline">
                                                    
                                            <li class="open">
                                                <div class="date"><input type="text" class="form-control" id="about_award_year1" name="about_award_year1"  value="<?php echo $getdetails[0]['about_award_year1']; ?>" placeholder="Year" ></div>
                                                <div class="circle"></div>
                                                <div class="data">
                                                    <div class="subject"><textarea class="form-control" rows="1" maxlength="30" id="about_award_title1" name="about_award_title1"  placeholder="Awards"  ><?php echo $getdetails[0]['about_award_title1']; ?></textarea></div>
                                                    <div class="text row">
                                                        <div class="col-md-2">
                                                            <img alt="image" class="thumbnail img-responsive" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/award.png" >
                                                        </div>
                                                        <div class="col-md-10">
                                                           <textarea class="form-control" rows="4" maxlength="100" id="about_award_subtitle1" name="about_award_subtitle1"  placeholder="Description  about award 1"  ><?php echo $getdetails[0]['about_award_subtitle1']; ?></textarea>    
														   </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="date"><input type="text" class="form-control" id="about_award_year2" name="about_award_year2"  value="<?php echo $getdetails[0]['about_award_year2']; ?>" placeholder="Year" ></div>
                                                <div class="circle"></div>
                                                <div class="data">
                                                    <div class="subject"><textarea class="form-control" rows="1" maxlength="30" id="about_award_title2" name="about_award_title2"  placeholder="Awards"  ><?php echo $getdetails[0]['about_award_title2']; ?></textarea></div>
                                                    <div class="text row">
                                                        <div class="col-md-2">
                                                            <img alt="image" class="thumbnail img-responsive" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/award.png" >
                                                        </div>
                                                        <div class="col-md-10">
														 <textarea class="form-control" rows="4" maxlength="100" id="about_award_subtitle2" name="about_award_subtitle2"  placeholder="Description  about award 2"  ><?php echo $getdetails[0]['about_award_subtitle2']; ?></textarea>    
														
                                                              </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="date"><input type="text" class="form-control" id="about_award_year3" name="about_award_year3"  value="<?php echo $getdetails[0]['about_award_year3']; ?>" placeholder="Year" ></div>
                                                <div class="circle"></div>
                                                <div class="data">
                                                    <div class="subject"><textarea class="form-control" rows="1" maxlength="30" id="about_award_title3" name="about_award_title3"  placeholder="Awards"  ><?php echo $getdetails[0]['about_award_title3']; ?></textarea></div>
                                                    <div class="text row">
                                                        <div class="col-md-2">
                                                            <img alt="image" class="thumbnail img-responsive" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/award.png" >
                                                        </div>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" rows="4" maxlength="100" id="about_award_subtitle3" name="about_award_subtitle3"  placeholder="Description  about award 3"  ><?php echo $getdetails[0]['about_award_subtitle3']; ?></textarea>    
														      </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="date"><input type="text" class="form-control" id="about_award_year4" name="about_award_year4"  value="<?php echo $getdetails[0]['about_award_year4']; ?>" placeholder="Year" ></div>
                                                <div class="circle"></div>
                                                <div class="data">
                                                    <div class="subject"><textarea class="form-control" rows="1" maxlength="30" id="about_award_title4" name="about_award_title4"  placeholder="Awards"  ><?php echo $getdetails[0]['about_award_title4']; ?></textarea></div>
                                                    <div class="text row">
                                                        <div class="col-md-2">
                                                            <img alt="image" class="thumbnail img-responsive" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/award.png" >
                                                        </div>
                                                        <div class="col-md-10">
                                                             <textarea class="form-control" rows="4" maxlength="100" id="about_award_subtitle4" name="about_award_subtitle4"  placeholder="Description  about award 4"  ><?php echo $getdetails[0]['about_award_subtitle4']; ?></textarea>    
														      </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="date"><input type="text" class="form-control" id="about_award_year5" name="about_award_year5"  value="<?php echo $getdetails[0]['about_award_year5']; ?>" placeholder="Year" ></div>
                                                <div class="circle"></div>
                                                <div class="data">
                                                    <div class="subject"><textarea class="form-control" rows="1" maxlength="30" id="about_award_title5" name="about_award_title5"  placeholder="Awards"  ><?php echo $getdetails[0]['about_award_title5']; ?></textarea></div>
                                                    <div class="text row">
                                                        <div class="col-md-2">
                                                            <img alt="image" class="thumbnail img-responsive" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/award.png" >
                                                        </div>
                                                        <div class="col-md-10">
                                                            <textarea class="form-control" rows="4" maxlength="100" id="about_award_subtitle5" name="about_award_subtitle5"  placeholder="Description  about award 5"  ><?php echo $getdetails[0]['about_award_subtitle5']; ?></textarea>    
														        </div>
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>
										  <button type="submit"  name="add_about" id="add_about" class="btn btn-success btn-lg" > Save Details </button>
			
                                    </div>
                                </div>
                            </div>
                        </div>                            
                    </div>
					
						
					</form>
                </div>

                <div id="research" class="page">
				<form method="post" enctype="multipart/form-data" name="frmResearch" id="frmResearch" action="add_webdetails.php">
                    <div class="pageheader">

                        <div class="headercontent">

                            <div class="section-container">
                                <h2 class="title">Research Summary</h2>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><textarea class="form-control" rows="15" maxlength="800" id="research_summary" name="research_summary" placeholder="Research description" ><?php echo $getdetails[0]['research_summary']; ?></textarea></p>
                                           </div>
                                    <div class="col-md-4">
                                        <div class="subtitle text-center">
                                            <h3>Interests</h3>
                                        </div>
                                        <ul class="ul-boxed list-unstyled">
                                            <li><input type="text" class="form-control" id="research_interest1" name="research_interest1"  value="<?php echo $getdetails[0]['research_interest1']; ?>" placeholder="Interest" ></li>
                                            <li><input type="text" class="form-control" id="research_interest2" name="research_interest2"  value="<?php echo $getdetails[0]['research_interest2']; ?>" placeholder="Interest" ></li>
                                            <li><input type="text" class="form-control" id="research_interest3" name="research_interest3"  value="<?php echo $getdetails[0]['research_interest3']; ?>" placeholder="Interest" ></li>
                                            <li><input type="text"  class="form-control" id="research_interest4" name="research_interest4"  value="<?php echo $getdetails[0]['research_interest4']; ?>" placeholder="Interest" ></li>
                                            <li><input type="text"  class="form-control" id="research_interest5" name="research_interest5"  value="<?php echo $getdetails[0]['research_interest5']; ?>" placeholder="Interest" ></li>
                                            <li><input type="text" class="form-control" id="research_interest6" name="research_interest6"  value="<?php echo $getdetails[0]['research_interest6']; ?>" placeholder="Interest" ></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pagecontents">
                        <!--<div class="section color-1">
                            <div class="section-container">
                                <div class="title text-center">
                                    <h3>Laboratory Personel</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        
                                        <div id="labp-heads-wrap">
                                            
                                            <div id="lab-carousel">
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                                <div><img alt="image" src="img/lab/120x120.png" width="120" height="120" class="img-circle lab-img" /></div>
                                            </div>
                                            <div>
                                                <a href="#" id="prev"><i class="icon-chevron-sign-left"></i></a>
                                                <a href="#" id="next"><i class="icon-chevron-sign-right"></i></a>
                                            </div>
                                        </div>

                                        <div id="lab-details">
                                            <div>
                                                <h3>David A. Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>James Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Nadja Sriram</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Davide Doe</h3>
                                                <h4>Research Assistant</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>Pauline Doe</h3>
                                                <h4>Summer Intern</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                            <div>
                                                <h3>James Doe</h3>
                                                <h4>Postdoctoral fellow</h4>
                                                <a href="#" class="btn btn-info">+ Follow</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <h3>Great lab Personel!</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        
                        <div class="section color-2">
                            <div class="section-container">
                                <div class="title text-center">
                                    <h3>Research Projects</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="ul-withdetails">
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/lab.jpg" class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3><textarea class="form-control" rows="2" maxlength="200" id="research_proj_title1" name="research_proj_title1"  placeholder="Research Project Title"  ><?php echo $getdetails[0]['research_proj_title1']; ?></textarea>    </h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <p><textarea class="form-control" rows="4" maxlength="350" id="research_proj_description1" name="research_proj_description1"  placeholder="Research Project description"  ><?php echo $getdetails[0]['research_proj_description1']; ?></textarea></p>                
													    <p><textarea class="form-control" rows="3" maxlength="350" id="research_proj_link1" name="research_proj_link1"  placeholder="To know more find the link below"  ><?php echo $getdetails[0]['research_proj_link1']; ?></textarea> </p>
													</div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/lab.jpg"  class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3><textarea class="form-control" rows="2" maxlength="200" id="research_proj_title2" name="research_proj_title2"  placeholder="Research Project Title"  ><?php echo $getdetails[0]['research_proj_title2']; ?></textarea>   </h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                  <p><textarea class="form-control" rows="4" maxlength="350" id="research_proj_description2" name="research_proj_description2"  placeholder="Research Project description"  ><?php echo $getdetails[0]['research_proj_description2']; ?></textarea></p>                
												
												  <p><textarea class="form-control" rows="3" maxlength="350" id="research_proj_link2" name="research_proj_link2"  placeholder="To know more find the link below"  ><?php echo $getdetails[0]['research_proj_link2']; ?></textarea> </p>
												 </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/lab.jpg"  class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3><textarea class="form-control" rows="2" maxlength="200" id="research_proj_title3" name="research_proj_title3"  placeholder="Research Project Title"  ><?php echo $getdetails[0]['research_proj_title3']; ?></textarea> </h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                        <p><textarea class="form-control" rows="4" maxlength="350" id="research_proj_description3" name="research_proj_description3"  placeholder="Research Project description"  ><?php echo $getdetails[0]['research_proj_description3']; ?></textarea></p>                
												
												  <p><textarea class="form-control" rows="3" maxlength="350" id="research_proj_link3" name="research_proj_link3"  placeholder="To know more find the link below"  ><?php echo $getdetails[0]['research_proj_link3']; ?></textarea>  </p>
										                      
												</div>
                                            </li>
                                          <!--  <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="img/lab/400x400.png"  class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3>Title of Preject</h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="img/lab/400x400.png"  class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3>Title of Preject</h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="image">
                                                            <img alt="image" src="img/lab/400x400.png"  class="img-responsive">
                                                            <div class="imageoverlay">
                                                                <i class="icon icon-search"></i>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-9">
                                                        <div class="meta">
                                                            <h3>Title of Preject</h3>
                                                            <p>Very short description of the project.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="details">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                </div>
                                            </li>-->
                                            
                                        </ul>
                                    </div>
									<label class="buttonsmall"> <input type="file" name="txtphoto2" id="txtphoto2" /></label> 
									<label class="buttonsmall"> <input type="file" name="txtphoto3" id="txtphoto3" /></label> 
									<label class="buttonsmall"> <input type="file" name="txtphoto4" id="txtphoto4" /></label> 
									
									 <button type="submit"  name="add_research" id="add_research" class="btn btn-success btn-lg" > Save Details </button>
			
                                </div>
                            </div>
							
                        </div> 
						
                    </div>
					</form>
                </div>

                <div id="publications" class="page">
					<form method="post" enctype="multipart/form-data" name="frmpublication" id="frmpublication" action="add_webdetails.php">
          
                    <div class="page-container">
                        <div class="pageheader">
                            <div class="headercontent">
                                <div class="section-container">
                                    
                                    <h2 class="title">Selected Publications</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><textarea class="form-control" rows="6" maxlength="400" id="publication_title" name="publication_title"  placeholder="about publication"  ><?php echo $getdetails[0]['publication_title']; ?></textarea></p>
											</div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="pagecontents">
                            
                            <div class="section color-1" id="filters">
                                <div class="section-container">
                                    <div class="row">
                                        
                                        <div class="col-md-3">
                                            <h3>Filter by type:</h3>
                                        </div>
                                        <div class="col-md-6">
                                            <select id="cd-dropdown" name="cd-dropdown" class="cd-select">
                                                <option class="filter" value="all" selected>All types</option>
												 <option class="filter" value="book">Books</option>
                                                <option class="filter" value="jpaper">Journal Papers</option>
                                                <option class="filter" value="cpaper">Conference Papers</option>
                                             
                                               
                                                <!-- <option class="filter" value="report">Reports</option>
                                                <option class="filter" value="tpaper">Technical Papers</option> -->
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3" id="sort">
                                            <span>Sort by year:</span>
                                            <div class="btn-group pull-right"> 

                                                <button type="button" data-sort="data-year" data-order="desc" class="sort btn btn-default"><i class="icon-sort-by-order"></i></button>
                                                <button type="button" data-sort="data-year" data-order="asc" class="sort btn btn-default"><i class="icon-sort-by-order-alt"></i></button>
                                            </div>
                                        </div>    
                                    </div>
                                </div>
                            </div>

                            <div class="section color-2" id="pub-grid">
                                <div class="section-container">
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pitems">
											
                                                <div class="item mix book" data-year="2010">
												
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link1']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
														<textarea class="form-control" rows="1" maxlength="100" id="publication_title1" name="publication_title1"  placeholder="Publications"  ><?php echo $getdetails[0]['publication_title1']; ?></textarea>
                                                          </h4>
                                                        <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author1" name="publication_author1"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author1']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article1" name="publication_article1"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article1']; ?></textarea>
															 <br><textarea class="form-control" rows="2" maxlength="200" id="publication_link1" name="publication_link1"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link1']; ?></textarea>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
          
                                                         <p><textarea class="form-control" rows="10" maxlength="800" id="publication_description1" name="publication_description1"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description1']; ?></textarea></p>
                                                    </div>
                                                </div>
												
                                                <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link2']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                          	<textarea class="form-control" rows="1" maxlength="100" id="publication_title2" name="publication_title2"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title2']; ?></textarea>
                                                        </h4>
                                                        <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author2" name="publication_author2"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author2']; ?></textarea></strong></div>
                                                        
                                                    <div class="pubcite">
                                                            <span class="label label-primary">Book</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article2" name="publication_article2"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article2']; ?></textarea>
                                                           </div>
                                                         <br> <textarea class="form-control" rows="2" maxlength="200" id="publication_link2" name="publication_link2"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link2']; ?></textarea>
                                                    </div>
                                                    <div class="pubdetails">
                                                      
                                                      <p><textarea class="form-control" rows="10" maxlength="800" id="publication_description2" name="publication_description2"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description2']; ?></textarea></p>
                                                                                            
                                                        
                                                    </div>
                                                </div>
												
                                                <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link3']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                         	<textarea class="form-control" rows="1" maxlength="100" id="publication_title3" name="publication_title3"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title3']; ?></textarea>
                                                        </h4>
                                                    <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author3" name="publication_author3"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author3']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article3" name="publication_article3"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article3']; ?></textarea>
                                               <br>  <textarea class="form-control" rows="2" maxlength="200" id="publication_link3" name="publication_link3"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link3']; ?></textarea>

													   </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                      
                                                        <p><textarea class="form-control" rows="10" maxlength="800" id="publication_description3" name="publication_description3"  placeholder="Description"  ><?php echo $getdetails[0]['publication_description3']; ?></textarea></p>
                                                  
                                                    </div>
                                                </div>
												 <div class="item mix book" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link4']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title4" name="publication_title4"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title4']; ?></textarea>
                                                      </h4>
													  <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author4" name="publication_author4"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author4']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-primary">Book</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article4" name="publication_article4"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article4']; ?></textarea>
                                                     <br> <textarea class="form-control" rows="2" maxlength="200" id="publication_link4" name="publication_link4"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link4']; ?></textarea>
                                                      	</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                    
                                                        <p><textarea class="form-control" rows="10" maxlength="800" id="publication_description4" name="publication_description4"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description4']; ?></textarea></p>
                                                  
                                                    </div>
                                                </div>

                                            <div class="item mix jpaper" data-year="2013">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link5']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title5" name="publication_title5"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title5']; ?></textarea>
                                                      </h4>
                                                       <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author5" name="publication_author5"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author5']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-success">Journal Papers</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article5" name="publication_article5"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article5']; ?></textarea>
                                   <br>  <textarea class="form-control" rows="2" maxlength="200" id="publication_link5" name="publication_link5"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link5']; ?></textarea>
									 <br> <label class="label label-success">Upload Journal Papers</label>
													  <label class="buttonsmall"> <input type="file" name="txtPhotop1" id="txtPhotop1" /></label> 
                            
														</div>
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                         <p><textarea class="form-control" rows="15" maxlength="1000" id="publication_description5" name="publication_description5"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description5']; ?></textarea></p>
                                                 </div>
                                                </div>
												
												   <div class="item mix jpaper" data-year="2012">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link6']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>
                                                         <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title6" name="publication_title6"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title6']; ?></textarea>
                                                      </h4>
                                                       <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author6" name="publication_author6"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author6']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-success">Journal Papers</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article6" name="publication_article6"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article6']; ?></textarea>
                                                        <br> <textarea class="form-control" rows="2" maxlength="200" id="publication_link6" name="publication_link6"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link6']; ?></textarea>
									  <br><label class="label label-success">Upload Journal Papers</label>
									  <label class="buttonsmall"> <input type="file" name="txtPhotop2" id="txtPhotop2" /></label>
													
														 </div>
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                       <p><textarea class="form-control" rows="15" maxlength="1000" id="publication_description6" name="publication_description6"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description6']; ?></textarea></p>
                                                  
														</div>
                                                </div>
                                                 <div class="item mix jpaper" data-year="2011">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link7']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title7" name="publication_title7"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title7']; ?></textarea>
                                                      </h4>
                                                       <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author7" name="publication_author7" placeholder="Author"  ><?php echo $getdetails[0]['publication_author7']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-success">Journal Papers</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article7" name="publication_article7"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article7']; ?></textarea>
                                                        <br> <textarea class="form-control" rows="2" maxlength="200" id="publication_link7" name="publication_link7"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link7']; ?></textarea>
									   <br><label class="label label-success">Upload Journal Papers</label>
									   <label class="buttonsmall"> <input type="file" name="txtPhotop3" id="txtPhotop3" /></label>
													 
														 </div>
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                       <p><textarea class="form-control" rows="15" maxlength="1000" id="publication_description7" name="publication_description7"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description7']; ?></textarea></p>
                                                        </div>
                                                </div>
                                                <div class="item mix cpaper" data-year="2013">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link8']?><?php echo $getdetails[0]['publication_link8']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                      <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title8" name="publication_title8"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title8']; ?></textarea>
                                                      </h4>
                                                       <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author8" name="publication_author8"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author8']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-warning">Conference Papers</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article8" name="publication_article8"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article8']; ?></textarea>
                                                     <br>  <textarea class="form-control" rows="2" maxlength="200" id="publication_link8" name="publication_link8"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link8']; ?></textarea>
									    <br><label class="label label-warning">Upload Conference Papers</label>
										<label class="buttonsmall"> <input type="file" name="txtPhotop4" id="txtPhotop4" /></label>
													 
														</div>
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                       <p><textarea class="form-control" rows="20" maxlength="1500" id="publication_description8" name="publication_description8"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description8']; ?></textarea></p>
                                                  
														</div>
                                                </div>


                                                


                                             
                                                <div class="item mix cpaper" data-year="2012">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="<?php echo $getdetails[0]['publication_link9']?>" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                       <h4 class="pubtitle">
                                                           	<textarea class="form-control" rows="1" maxlength="100" id="publication_title9" name="publication_title9"  placeholder="Publication title"  ><?php echo $getdetails[0]['publication_title9']; ?></textarea>
                                                      </h4>
                                                       <div class="pubauthor"><strong><textarea class="form-control" rows="1" maxlength="100" id="publication_author9" name="publication_author9"  placeholder="Author"  ><?php echo $getdetails[0]['publication_author9']; ?></textarea></strong></div>
                                                        <div class="pubcite">
                                                            <span class="label label-warning">Conference Papers</span> <textarea class="form-control" rows="1" maxlength="100" id="publication_article9" name="publication_article9"  placeholder="Article"  ><?php echo $getdetails[0]['publication_article9']; ?></textarea>
                                                        <br> <textarea class="form-control" rows="2" maxlength="200" id="publication_link9" name="publication_link9"  placeholder="Attach link here"  ><?php echo $getdetails[0]['publication_link9']; ?></textarea>
									       <br><label class="label label-warning">Upload Conference Papers</label>
										<label class="buttonsmall"> <input type="file" name="txtPhotop5" id="txtPhotop5" /></label>
													
														 </div>
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p><textarea class="form-control" rows="20" maxlength="1500" id="publication_description9" name="publication_description9"  placeholder="publication_description"  ><?php echo $getdetails[0]['publication_description9']; ?></textarea></p>
                                                  
                                                    </div>
                                                </div>



                                               

                                                <!--<div class="item mix bookchapter" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-info">Book Chapter</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="img/pubs/150x200.png" align="left"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>

                                                <div class="item mix jpaper" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2010, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>
                                                <div class="item mix cpaper" data-year="2011">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-warning">Conference Papers</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2011, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>


                                               

                                                <div class="item mix jpaper" data-year="2009">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2009, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>

                                                <div class="item mix bookchapter" data-year="2010">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">
                                                            The Dragonfly Effect: Quick, Effective, and Powerful Ways To Use Social Media to Drive Social Change
                                                        </h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite">
                                                            <span class="label label-info">Book Chapter</span> John Wiley & Sons | September 28, 2010 | <strong>ISBN-10:</strong> 0470614153
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <img alt="image" src="img/pubs/150x200.png" align="left"  style="padding:0 30px 30px 0;">
                                                        <h4>Proven strategies for harnessing the power of social media to drive social change</h4>
                                                        <p>Many books teach the mechanics of using Facebook, Twitter, and YouTube to compete in business. But no book addresses how to harness the incredible power of social media to make a difference. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                                        <ul>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</li>
                                                            <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>.sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                            <li>Onsectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
                                                        </ul>
                                                        
                                                    </div>
                                                </div>-->

                                              <!--  <div class="item mix jpaper" data-year="2008">
                                                    <div class="pubmain">
                                                        <div class="pubassets">
                                                            
                                                            <a href="#" class="pubcollapse">
                                                                <i class="icon-expand-alt"></i>
                                                            </a>
                                                            <a href="http://www.sciencedirect.com/science/article/pii/S1057740812000290" class="tooltips" title="External link" target="_blank">
                                                                <i class="icon-external-link"></i>
                                                            </a>
                                                            <a href="http://faculty-gsb.stanford.edu/aaker/pages/documents/CultivatingAdmirationinBrands_JCP2012.pdf" class="tooltips" title="Download" target="_blank">
                                                                <i class="icon-cloud-download"></i>
                                                            </a>
                                                            
                                                        </div>

                                                        <h4 class="pubtitle">Cultivating admiration in brands: Warmth, competence, and landing in the “golden quadrant”</h4>
                                                        <div class="pubauthor"><strong>Jennifer Doe</strong>,  Emily N. Garbinsky, Kathleen D. Vohs</div>
                                                        <div class="pubcite"><span class="label label-success">Journal Paper</span> Journal of Consumer Psychology, Volume 22, Issue 2, April 2008, Pages 191-194</div>
                                                        
                                                    </div>
                                                    <div class="pubdetails">
                                                        <h4>Abstract</h4>
                                                        <p>Although a substantial amount of research has examined the constructs of warmth and competence, far less has examined how these constructs develop and what benefits may accrue when warmth and competence are cultivated. Yet there are positive consequences, both emotional and behavioral, that are likely to occur when brands hold perceptions of both. In this paper, we shed light on when and how warmth and competence are jointly promoted in brands, and why these reputations matter.</p>
                                                    </div>
                                                </div>-->

                                            </div>
                                        </div>
                                    </div>
 <button type="submit"  name="add_publication" id="add_publication" class="btn btn-success btn-lg" > Save Details </button>
			
                                </div>
                            </div>

                        </div>
                    </div>
					</form>
                </div>


                <div id="teaching" class="page">
						<form method="post" enctype="multipart/form-data" name="frmteaching" id="frmteaching" action="add_webdetails.php">
          
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <h2 class="title">Teaching</h2>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><textarea class="form-control" rows="6" maxlength="400" id="teaching_title" name="teaching_title"  placeholder="Description"  ><?php echo $getdetails[0]['teaching_title']; ?></textarea></p>     
										</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagecontents">
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="title text-center">
                                        <h3>Currrent Teaching</h3>
                                    </div>
                                    <ul class="ul-dates">
                                        <li>
                                            <div class="dates">
                                               <span><input type="text" class="form-control" id="teaching_curr_to1" name="teaching_curr_to1"  value="<?php echo $getdetails[0]['teaching_curr_to1']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_curr_from1" name="teaching_curr_from1"  value="<?php echo $getdetails[0]['teaching_curr_from1']; ?>" placeholder="Year" ></span>
                                         
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_curr_title1" name="teaching_curr_title1"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_curr_title1']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_curr_subtitle1" name="teaching_curr_subtitle1"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_curr_subtitle1']; ?></textarea></p>      
												</div>
                                        </li>
                                       <li>
                                            <div class="dates">
                                                  <span><input type="text" class="form-control" id="teaching_curr_to2" name="teaching_curr_to2"  value="<?php echo $getdetails[0]['teaching_curr_to2']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_curr_from2" name="teaching_curr_from2"  value="<?php echo $getdetails[0]['teaching_curr_from2']; ?>" placeholder="Year" ></span>
                                         
                                     
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_curr_title2" name="teaching_curr_title2"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_curr_title2']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_curr_subtitle2" name="teaching_curr_subtitle2"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_curr_subtitle2']; ?></textarea></p>   
												</div>
                                        </li>
                                        <!--<li>
                                            <div class="dates">
                                                <span>Present</span>
                                                <span>2010</span>
                                            </div>
                                            <div class="content">
                                                <h4>Endodontics Postdoctoral AEGD Program</h4>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices ac elit sit amet porttitor. Suspendisse congue, erat vulputate pharetra mollis, est eros fermentum nibh, vitae rhoncus est arcu vitae elit.</p>
                                            </div>
                                        </li>-->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="section color-2">
                            <div class="section-container">
                                <div class="row">
                                    <div class="title text-center">
                                        <h3>Teaching History</h3>
                                    </div>
                                    <ul class="ul-dates-gray">
                                        <li>
                                            <div class="dates">
                                                <span><input type="text" class="form-control" id="teaching_hist_to1" name="teaching_hist_to1"  value="<?php echo $getdetails[0]['teaching_hist_to1']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_hist_from1" name="teaching_hist_from1"  value="<?php echo $getdetails[0]['teaching_hist_from1']; ?>" placeholder="Year" ></span>
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_title1" name="teaching_hist_title1"  placeholder="Courses "  ><?php echo $getdetails[0]['teaching_hist_title1']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_subttle1" name="teaching_hist_subttle1"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_hist_subttle1']; ?></textarea></p> 
												</div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                  <span><input type="text" class="form-control" id="teaching_hist_to2" name="teaching_hist_to2"  value="<?php echo $getdetails[0]['teaching_hist_to2']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_hist_from2" name="teaching_hist_from2"  value="<?php echo $getdetails[0]['teaching_hist_from2']; ?>" placeholder="Year" ></span>
                                         
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_title2" name="teaching_hist_title2"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_hist_title2']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_subttle2" name="teaching_hist_subttle2"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_hist_subttle2']; ?></textarea></p>
												</div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                 <span><input type="text" class="form-control" id="teaching_hist_to3" name="teaching_hist_to3"  value="<?php echo $getdetails[0]['teaching_hist_to3']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_hist_from3" name="teaching_hist_from3"  value="<?php echo $getdetails[0]['teaching_hist_from3']; ?>" placeholder="Year" ></span>
                                         
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_title3" name="teaching_hist_title3"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_hist_title3']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_subttle3" name="teaching_hist_subttle3"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_hist_subttle3']; ?></textarea></p>
												</div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                  <span><input type="text" class="form-control" id="teaching_hist_to4" name="teaching_hist_to4"  value="<?php echo $getdetails[0]['teaching_hist_to4']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_hist_from4" name="teaching_hist_from4"  value="<?php echo $getdetails[0]['teaching_hist_from4']; ?>" placeholder="Year" ></span>
                                         
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_title4" name="teaching_hist_title4"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_hist_title4']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_subttle4" name="teaching_hist_subttle4"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_hist_subttle4']; ?></textarea></p>   
												</div>
                                        </li>
                                        <li>
                                            <div class="dates">
                                                 <span><input type="text" class="form-control" id="teaching_hist_to5" name="teaching_hist_to5"  value="<?php echo $getdetails[0]['teaching_hist_to5']; ?>" placeholder="Year" ></span>
                                                <span><input type="text" class="form-control" id="teaching_hist_from5" name="teaching_hist_from5"  value="<?php echo $getdetails[0]['teaching_hist_from5']; ?>" placeholder="Year" ></span>
                                         
                                            </div>
                                            <div class="content">
                                                <h4><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_title5" name="teaching_hist_title5"  placeholder="Courses"  ><?php echo $getdetails[0]['teaching_hist_title5']; ?></textarea></h4>
                                                <p><textarea class="form-control" rows="1" maxlength="100" id="teaching_hist_subttle5" name="teaching_hist_subttle5"  placeholder="Institution"  ><?php echo $getdetails[0]['teaching_hist_subttle5']; ?></textarea></p>
												</div>
                                        </li>
                                    </ul>
									 <button type="submit"  name="add_teach" id="add_teach" class="btn btn-success btn-lg" > Save Details </button>
			
                                </div>
                            </div>
                        </div>
                    </div>
					</form>
                </div>
                
                <div id="gallery" class="page">
					<form method="post" enctype="multipart/form-data" name="frmgallery" id="frmgallery" action="add_webdetails.php">
          
            
                    <div class="pagecontents">
                        
                        <div class="section color-3" id="gallery-header">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-3">
                                        <h2>Gallery</h2>
                                    </div>
                                    <div class="col-md-9">
                                        <p><textarea class="form-control" rows="6" maxlength="400" id="gallery_title" name="gallery_title"  placeholder="About Gallery"  ><?php echo $getdetails[0]['gallery_title']; ?></textarea></p>
										</div>
                                </div>
                            </div>
                        </div>

                        <div class="section color-3" id="gallery-large">
                            <div class="section-container">
                                
                                <ul id="grid" class="grid">
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal1.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal1.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal2.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal2.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                      <i class="icon-search"></i>
													  <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal3.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal3.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal4.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal4.jpg" class="popup-with-move-anim"> 
                                                <div class="over">
                                                    <div class="comein">
                                                         <i class="icon-search"></i>
														 <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal5.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal5.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
                                                        <i class="icon-search"></i>
                                                        <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <div>
                                            <img alt="image" src="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal6.jpg">
                                            <a href="http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/gal6.jpg" class="popup-with-move-anim">
                                                <div class="over">
                                                    <div class="comein">
													 <i class="icon-search"></i>
                                                         <div class="comein-bg"></div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    
                                    
                                </ul>
								Upload images<br>
                                  <label class="buttonsmall"> <input type="file" name="txtPhoto5" id="txtPhoto5" /></label>
                                   <label class="buttonsmall"> <input type="file" name="txtPhoto6" id="txtPhoto6" /></label>
 <label class="buttonsmall"> <input type="file" name="txtPhoto7" id="txtPhoto7" /></label>
 <label class="buttonsmall"> <input type="file" name="txtPhoto8" id="txtPhoto8" /></label>
  <label class="buttonsmall"> <input type="file" name="txtPhoto9" id="txtPhoto9" /></label>
  <label class="buttonsmall"> <input type="file" name="txtPhoto10" id="txtPhoto10" /></label>	
       <button type="submit"  name="add_gallery" id="add_gallery" class="btn btn-success btn-lg" > Save Details </button>
									   
                            </div>
							
                        </div>
                    </div>
                    </form>
                </div>
          <div id="contact" class="page stellar">
		  	<form method="post" enctype="multipart/form-data" name="frmcontact" id="frmcontact" action="add_webdetails.php">
          
            
                    <div class="pageheader">
                        <div class="headercontent">
                            <div class="section-container">
                                
                                <h2 class="title">Contact & Meet Me</h2>
                            
                                <div class="row">
                                    <div class="col-md-8">
                                        <p><textarea class="form-control" rows="6" maxlength="400" id="contact_title" name="contact_title"  placeholder="Courses"  ><?php echo $getdetails[0]['contact_title']; ?></textarea></p>
										</div>
                                    <div class="col-md-4">
                                        <ul class="list-unstyled">
                                            <li>
                                                <strong><i class="icon-phone"></i></strong>&nbsp;&nbsp;Phone Number
                                                <span><input type="text" class="form-control" id="contact_phone" name="contact_phone"  value="<?php echo $getdetails[0]['contact_phone']; ?>" placeholder="Phone Number" ></span>
                                            </li>
                                           <!-- <li>
                                                <strong><i class="icon-phone"></i>&nbsp;&nbsp;</strong>
                                                <span>lab: 808-808 88 88</span>
                                            </li>-->
                                            <li>
                                                <strong><i class="icon-envelope"></i></strong>&nbsp;&nbsp;Email ID
                                                <span><input type="text" class="form-control" id="contact_email" name="contact_email"  value="<?php echo $getdetails[0]['contact_email']; ?>" placeholder="Phone Number" ></span>
                                            </li>
                                            <li>
                                                <strong><i class="icon-facebook-sign"></i></strong>&nbsp;&nbsp;Facebook
                                               <a href="https://www.facebook.com/namrata.sharma.7921975" target="blank"><input type="text" class="form-control" id="contact_facebook" name="contact_facebook"  value="<?php echo $getdetails[0]['contact_facebook']; ?>" placeholder="Facebook " ></a>
                                            </li>
                                            <li>
                                                <strong><i class="icon-skype"></i></strong>&nbsp;&nbsp;Skype 
                                                <span><input type="text" class="form-control" id="contact_skype" name="contact_skype"  value="<?php echo $getdetails[0]['contact_skype']; ?>" placeholder="Skype" ></span>
                                            </li>
                                            <!--<li>
                                                <strong><i class="icon-twitter"></i>&nbsp;&nbsp;</strong>
                                                <span>#jenniferDoe</span>
                                            </li>-->
                                            <li>
                                                <strong><i class="icon-linkedin-sign"></i></strong>&nbsp;&nbsp;Linkedin
                                                <span><a href="https://www.linkedin.com/in/namrata-sharma-42b5b6120/"><input type="text" class="form-control" id="contact_linkedin" name="contact_linkedin"  value="<?php echo $getdetails[0]['contact_linkedin']; ?>" placeholder="Linkedin" ></a></span>
                                            </li>
                                        </ul>    

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pagecontents">
                        <div class="section contact-office" style="background: #fff url(http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/contact-office.jpg) no-repeat;" data-stellar-background-ratio="0.1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h2 class="title">At My Office</h2>
                                        <p> <textarea class="form-control" rows="6" maxlength="400" id="contact_office" name="contact_office"  ><?php echo $getdetails[0]['contact_office']; ?></textarea></p> 
										</div>
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-coffee icon-huge"></i>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="section color-1">
                            <div class="section-container">
                                <div class="row">
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-stethoscope icon-huge"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h2 class="title">At My Work</h2>
                                       <p><textarea class="form-control" rows="6" maxlength="400" id="contact_work" name="contact_work"  placeholder="Work"  ><?php echo $getdetails[0]['contact_work']; ?></textarea> </p>
									    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section contact-lab" style="background: #fff url(http://localhost/Doctor_template/template2demo/template2/theme2ImageAttach/1/contact-lab.jpg) no-repeat;" data-stellar-background-ratio="0.1">
                            <div class="section-container">
                                <div class="row">
                                    
                                    <div class="col-md-8">
                                        <h2 class="title">At My Lab</h2>
                                      <p> <textarea class="form-control" rows="6" maxlength="400" id="contact_lab" name="contact_lab"  placeholder="Lab"  ><?php echo $getdetails[0]['contact_lab']; ?></textarea> </p>
									    
									  <button type="submit"  name="add_contact" id="add_contact" class="btn btn-success btn-lg" > Save Details </button>
			
                                    </div>
                                    <div class="col-md-4 text-center hidden-xs hidden-sm">
                                        <i class="icon-superscript icon-huge"></i>
                                    </div>

                                </div>
								 
                            </div>
							
                        </div>
                    </div>
                    </form>
                </div>
                
                <div id="overlay"></div>
            
            </div>
        </div>
    </body>
</html>

