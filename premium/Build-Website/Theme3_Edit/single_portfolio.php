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
$project_id = $_GET['id'];			
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

    <title>Rida vCard - Personal Portfolio</title>

    <!-- owl carousel css -->
    <link rel="stylesheet" href="css/owl.carousel.css"/>

    <!-- venobox css -->
    <link rel="stylesheet" href="css/venobox.css">

    <!-- font awesome -->
    <link rel="stylesheet" href="css/font-awesome.min.css"/>

    <!-- fontello -->
    <link rel="stylesheet" href="css/pe-icon-7-stroke.css"/>

    <!-- camera css -->
    <link rel="stylesheet" href="css/camera.css">

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
<body class="single_work_page">

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
                    <p class="name">Dr. Rida</p>
                </div>

                <div class="main_menu">
                    <ul>
                        <li><a href="index.php#home"><span class="pe-7s-home"></span><p>Home</p></a></li>
                        <li><a href="index.php#portfolio"><span class="pe-7s-portfolio"></span><p>portfolio</p></a></li>
                    </ul>

                    <div class="social">
                        <ul>
                            <li><a href=""><span class="fa fa-facebook"></span></a></li>
                            <li><a href=""><span class="fa fa-twitter"></span></a></li>
                            <li><a href=""><span class="fa fa-google-plus"></span></a></li>
                            <li><a href=""><span class="fa fa-linkedin"></span></a></li>
                        </ul>
                    </div>
                </div>
            </div>

        </aside><!-- end header -->

        <!-- menu toggler -->
        <div class="menu_toggler">
            <span class="fa fa-bars" aria-hidden="true"></span>
        </div>

        <!-- main_content -->
        <div class="main_content">

            <!-- start .single_work -->
            <section class="single_work single_page active">
			<form method="post" enctype="multipart/form-data" name="frmproject" id="frmproject" action="add_webdetails.php">
                    <input type="hidden"  id="project_id" name="project_id"  placeholder="project id" value="<?php echo $project_id?>"  >
                                      
                <div class="single_work_wrapper section_padding">
				<?php  if($project_id == 1) {
									?> 
                    <!-- start .container -->
                    <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title1'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
              <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description1']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!--
                                        <div class="project_social">
                                            <p>Share:</p>
                                            <ul>
                                                <li><a href="#"><span class="fa fa-facebook"></span></a></li>
                                                <li><a href="#"><span class="fa fa-twitter"></span></a></li>
                                                <li><a href="#"><span class="fa  fa-google-plus"></span></a></li>
                                                <li><a href="#"><span class="fa fa-linkedin"></span></a></li>
                                            </ul>
                                        </div> -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					  <?php } else if($project_id == 2)  {
										?>
										<div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title2'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description2']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                    
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					  <?php } else if($project_id == 3){?>
					  <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title3'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description3']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                      <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					  <?php } else if($project_id == 4){?>
					   <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title4'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description4']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                       <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					   <?php } else if($project_id == 5){?>
					  <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title5'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description5']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                        <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					 <?php } else if($project_id == 6){?>
					  <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title6'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description6']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                       <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
					 <?php } else if($project_id == 7){?>
					   <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title7'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description7']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                        <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
						 <?php } else if($project_id == 8){?>
						    <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title8'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description8']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                       <!-- end /.project_social -->
                                    </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
						 <?php } else if($project_id == 9){?>
						 			    <div class="container">
					
                	
                        <!-- row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="section_title">
								      
                                    <h2 style="margin-left:90px"><input type="text"  id="project_title" name="project_title" maxlength="500" placeholder="project title" value="<?php echo $getdetails[0]['project_title9'];?>"  >
                                       </h2>
                                    <p class="sub_title">My latest works</p>
                                </div>
                            </div>
                        </div><!-- end .row -->

                        <!-- start .row -->
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="project_img_slide_wrapper">
                                    
                                          <div class="single_img"><img src="images/single_work.jpg" alt="Single work img"></div>
                                   

                                   
                                </div>
								<h5><strong>Upload Image</strong></h5>
   <input type="file" name="txtPhoto1" id="txtPhoto1" >
                                <!-- start .row -->
                                <div class="row">
                                    <!-- start /.col-md-6 -->
                                    <div class="col-md-12">
                                        <!-- Project Overview -->
                                        <div class="project_overview">
                                            <h4 class="project_title">Project overview</h4>

                                            <p><textarea  rows="12" maxlength="1000" id="project_description" name="project_description"  placeholder="Description"  ><?php echo $getdetails[0]['project_description9']; ?></textarea>    
										</p>
                                        </div><!-- Project overview -->

                                        <!-- Start .project_social -->
                                          </div><!-- end /.col-md-6 -->

                                    <!--<div class="col-md-5 col-md-offset-1">
                                        <div class="project_info_wrapper">
                                            <h4 class="project_title">Project Details</h4>

                                            <div class="project_infos">
                                                <ul class="info_list">
                                                    <li><span>Client</span><p>Jonathon Doe</p></li>
                                                    <li><span>Agency</span><p>Shaken Technology</p></li>
                                                    <li><span>Type</span><p>Web Design, Product Design</p></li>
                                                    <li><span>Date</span><p>24 Dec 2016</p></li>
                                                </ul>

                                                <button type="button" class="project_btn">LAUNCH PROJECT</button>
                                            </div>
                                        </div>
                                    </div>-->
 <button type="submit"  name="add_singleproject" id="add_singleproject" class="btn" > Save Details </button>
			            
                                </div> <!-- end /.row -->
                            </div>
                        </div><!-- end /.row -->
                  

					</div>
						 <?php } ?>
                </div>

                <!-- start footer -->
                <footer class="footer">
                    <p> &copy; 2018 copyright  all right reserved. Designed and developed by <a href="https://www.medisensehealth.com/">Medisense Healthcare</a></p>
                </footer>
				</form>
                <!-- end footer -->
            </section><!-- end /.single_work -->
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
</body>
</html>
