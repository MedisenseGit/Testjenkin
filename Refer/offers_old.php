<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}

include('connect.php');
include('functions.php');

$getOffersResult = $objQuery->mysqlSelect("*","offers_events","md5(event_id)='".$_GET['id']."'","","","","");
										

$countBlog = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Blog","","","","");
$countOffer = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Offers","","","","");
$countEvent = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Events","","","","");


//TO CHECK LOGIN USER TYPE WHETHER HE IS DOCTOR OR NORMAL USER
if($login_user_type=="doc"){
//$trimdocid  = str_replace("doc-", "", $postResult[$i]['Login_User_Id']);
$getloginuser = $objQuery->mysqlSelect("*","referal","ref_id='".$login_id."'","","","","");
$getloginSpec=$objQuery->mysqlSelect("*","specialization","spec_id='".$getloginuser[0]['doc_spec']."'","","","","");	
$login_username=$getloginuser[0]['ref_name'];
$login_userprof=$getloginSpec[0]['spec_name'];
						
if(!empty($getloginuser[0]['doc_photo'])){
$login_userimg="https://medisensecrm.com/Doc/".$login_id."/".$getloginuser[0]['doc_photo']; 
}
else{
	$login_userimg="images/anonymous-profile.png";
	}
} else if($login_user_type=="user"){
	$getloginuser = $objQuery->mysqlSelect("*","login_user","login_id='".$login_id."'","","","","");
	$username=$getloginuser[0]['sub_name'];
	$userprof=$getloginuser[0]['sub_proff'];
								
	if(!empty($getloginuser[0]['user_img'])){
	$login_userimg=$getloginuser[0]['user_img'];	
	}
	else{
		$login_userimg="images/anonymous-profile.png";	
		}
}


function hyphenize($string) {
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#'),
            array('-', ''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}
$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = $objQuery->mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(c.company_id)='".$admin_id."'","a.Tot_responded desc","","","");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Our Blogs</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	
	<script type="text/javascript" src="like_assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="like_assets/js/function.js"></script>
		<script type="text/javascript" src="like_assets/js/comment_function.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<?php include_once('side_menu.php');?>
		 
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		  <?php include_once('header_top_nav.php'); ?>
            <!--<div class="page-title">
              <div class="title_left">
                <h3>BLOGS</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>-->
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-9 col-sm-9 col-xs-12">

                     
					
					
					<div id="blogSection">
					
					<ul class="messages" >
                          <li>
							
                            <!--<img src="<?php echo $icon; ?>" class="avatar fa fa-rss" alt="Avatar">-->
                            <div class="message_date" style="float:left;">
                              <h3 class="date text-info"><?php echo date('d',strtotime($getOffersResult[0]['created_date'])); ?></h3>
                              <p class="month"><?php echo date('M',strtotime($getOffersResult[0]['created_date'])); ?></p>
                            </div>
                            <div class="message_wrapper">
                              <!--<h4 class="heading"><?php echo $username; ?><br><small><em><?php echo $userprof; ?></em></small></h4>-->
                              <blockquote class="message"><?php if(!empty($getOffersResult[0]['title'])){ echo $getOffersResult[0]['title']; } ?></blockquote>
                              <br />
							 	
                              <p >
							   <?php if(!empty($getOffersResult[0]['photo'])){ ?><img src="../Hospital/Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>" width="650" class="img-responsive"/> <?php } ?>
							   
							   <?php if(!empty($getOffersResult[0]['description'])){ echo $getOffersResult[0]['description']; } ?>
                                <br></p>
								
							<p class="url">
							
								<ul class="nav navbar-left panel_toolbox" >
								<!--<li ><?php 
												
												if(check_going($admin_id,$getOffersResult[0]['event_id']) == 0) { ?>
								<a href="javascript:void();" class="going" data-toggle="tooltip" id="<?php echo $getOffersResult[0]['event_id']; ?>" data-placement="bottom" title="Going" style="font-size:20px;" ><i class="fa fa-check"></i> <small><?php echo going($getOffersResult[0]['event_id']); ?></small></a>
												<?php } else { ?>
												<a href="javascript:void();" class="gone" data-toggle="tooltip" data-placement="bottom" title="Going" style="font-size:20px; color:green;"> <i class="fa fa-check"></i> <small><?php echo going($getOffersResult[0]['event_id']); ?></small></a>
												<?php } 
												 ?></li>-->
								<li ><a href="#myModal" data-toggle="modal" data-target=".bs-example-modal-sm" data-placement="bottom" title="Going" style="font-size:20px;"><i class="fa fa-check"></i> <small><?php echo going($getOffersResult[0]['event_id']); ?></small></a></li>				 
							  <li >&nbsp;&nbsp;&nbsp;</li>
							   <li>
							  <?php if(check_maybe($admin_id,$getOffersResult[0]['event_id']) == 0) { ?>
							 <a herf="javascript:void();" class="maybe" id="<?php echo $getOffersResult[0]['event_id']; ?>" data-toggle="tooltip" data-placement="bottom" title="May be" style="font-size:20px;"><i class="fa fa-question"></i> <small><?php echo maybe($getOffersResult[0]['event_id']); ?></small></a>
							 <?php } else { ?>
												<a href="javascript:void();" class="maybe_done" data-toggle="tooltip" data-placement="bottom" title="May be" style="font-size:20px; color:green;"> <i class="fa fa-question"></i> <small><?php echo maybe($getOffersResult[0]['event_id']); ?></small></a>
												<?php } 
												 ?>
							 </li>
							  <li >&nbsp;&nbsp;&nbsp;</li>
							    <li>
							   <?php if(check_cannot($admin_id,$getOffersResult[0]['event_id']) == 0) { ?>
							  <a herf="javascript:void();" class="cannot" id="<?php echo $getOffersResult[0]['event_id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Can't go" style="font-size:20px;" ><i class="fa fa-times"></i> <small><?php echo cannot($getOffersResult[0]['event_id']); ?></small></a>
							  <?php } else { ?>
												<a href="javascript:void();" class="cannot_done" data-toggle="tooltip" data-placement="bottom" title="Can't go" style="font-size:20px; color:green;"> <i class="fa fa-times"></i> <small><?php echo cannot($getOffersResult[0]['event_id']); ?></small></a>
												<?php }	 ?>

							 </li>
							  
								</ul>
						<!-- start modals -->		
							<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                          </button>
                          <!--<h4 class="modal-title" id="myModalLabel2"><?php echo $getOffersResult[0]['title']; ?></h4>-->
                        </div>
                        <div class="modal-body">
                          <h4>Please select one of the following options below</h4>
                          <p><input type="radio" name="cmereg" value="1"> Register Now</p>
                          <p><input type="radio" name="cmereg" value="2"> On-spot Registration</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary">Submit</button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- /modals -->
								</p>
								
								 <div class="clearfix"></div>
                            </div>
                          </li>
                          
                        </ul>
						 <div class="clearfix"></div>
					
					
					<?php 
					$getBlogPost = $objQuery->mysqlSelect("*","offers_events as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and md5(a.event_id)!='".$_GET['id']."'","a.event_id desc","","","");
					foreach($getBlogPost as $postResultList){
					
						
							$postid=md5($postResultList['event_id']);
							$posttype=$postResultList['event_type'];
							$postdate=$postResultList['created_date'];	
							$posttitle=$postResultList['title'];
							$postDescription=$postResultList['description'];
							if(!empty($postResultList['photo'])){
							$postimage="../Hospital/Eventimages/".$postResultList['event_id']."/".$postResultList['photo'];
							} else {
							$postimage="";
							}
							$icon="images/blogs.png";
					

									
						?>	
						
						<!--Begin blog section -->
                        <ul class="messages" >
                          <li>
							
                            <!--<img src="<?php echo $icon; ?>" class="avatar fa fa-rss" alt="Avatar">-->
                            <div class="message_date" style="float:left;">
                              <h3 class="date text-info"><?php echo date('d',strtotime($postdate)); ?></h3>
                              <p class="month"><?php echo date('M',strtotime($postdate)); ?></p>
                            </div>
                            <div class="message_wrapper">
                              <!--<h4 class="heading"><?php echo $username; ?><br><small><em><?php echo $userprof; ?></em></small></h4>-->
                              <a href="offers.php?id=<?php echo $postid; ?>"><blockquote class="message"><?php if(!empty($posttitle)){ echo $posttitle; } ?></blockquote></a>
                              <br />
							 	
                              <p >
							   <?php if(!empty($postimage)){ ?><img src="<?php echo $postimage; ?>" width="650" class="img-responsive"/> <?php } ?>
							   
							   <?php if(!empty($postDescription)){ echo $postDescription; } ?>
                                <br></p>
								
							
								
								 <div class="clearfix"></div>
                            </div>
                          </li>
                          
                        </ul>
						 <div class="clearfix"></div>
                        <!-- end of blog -->
					<?php } ?> 
						</div>
						</form>
						<!-- Add Blog Section -->
					<div id="addBlog"></div>
					
                    </div>

                    <!-- start project-detail sidebar -->
                    <div class="col-md-3 col-sm-3 col-xs-12">

                      <section class="panel">

                        <div class="x_title">
                          <h2><i class="fa fa-list"></i> Category</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                          <div class="project_detail">
						  <div class="project_detail">
                         
                          <ul class="list-unstyled project_files">
						 
                            <li><a href="Blogs-Offers-Events-List?s=Blog"><i class="fa fa-rss"></i> Blogs <!--<span style="color:#ccc;">(<?php echo $countBlog[0]['Count']; ?>)</span>--></a>
							<li><a href="Blogs-Offers-Events-List?s=Offers"><i class="fa fa-volume-up"></i> Offers <!--<span style="color:#ccc;">(<?php echo $countOffer[0]['Count']; ?>)</span>--></a>
							<li><a href="Blogs-Offers-Events-List?s=Events"><i class="fa fa-volume-up"></i> Events <!--<span style="color:#ccc;">(<?php echo $countEvent[0]['Count']; ?>)</span>--></a>
                            </li>
						 
                          </ul>
						  </div>
                          <br />

                          
                        </div>

                      </section>
					  <?php if($getPartner[0]['Type']=="Doctor"){ ?>
					 <section class="panel">

                        <div class="x_title">
                          <h2><i class="fa fa-calendar"></i> Send Appointment Link</h2>
						  <div class="clearfix"></div>
				<div>

							<?php if($_GET['response']=="send") {  ?>
							<h4><span style="color:green; font-weight:bold;"><i class="fa fa-check"></i> Link sent successfully</span><br></h4>
							<?php } ?>									
                        </div>
                        </div>
						<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
						<div class="form-group">
                        
						<br>
                        <div class="col-xs-12">
						<input type="email" id="pat_email" name="pat_email" class="form-control" placeholder="Email Id">
						</div>
						
						<div class="col-xs-12 text-center">
						Or
						</div>
						<br><br><br>
						<div class="col-xs-12">
						<input type="text" id="pat_mobile" name="pat_mobile" class="form-control" placeholder="Mobile No.">
						</div>
						<br><br><br>
						<div class="col-xs-12">
						<button type="submit" name="sendappointment" id="sendappointment" class="btn btn-success"><i class="fa fa-mail-forward"></i> SEND </button>
						</div>
						</div>
						</form>
						</section>
					  <?php } ?>
					  <section class="panel">

                        <div class="x_title">
                          <h2>How it works</h2>
						  <div class="clearfix"></div>
                        </div>
						<iframe width="250" height="200" src="https://www.youtube.com/embed/oLYtYCpq0OY?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>   
					  </section>
                    </div>
                    <!-- end project-detail sidebar -->

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
    <!-- ECharts -->
    <script src="../Hospital/vendors/echarts/dist/echarts.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>