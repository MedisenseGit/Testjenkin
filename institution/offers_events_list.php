<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");


//$getClientIp=$_SERVER['REMOTE_ADDR'];


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Offer & Event List</title>
	<?php include_once('support.php'); ?>

</head>

<body>

    <div id="wrapper">
	<!-- Side Menu -->
    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
		<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8">
                    <h2>Offer & Event List</h2>
                    <ol class="breadcrumb">
                        <li >
                           <a href="Home"> Home</a>
                        </li>
						<li class="active">
                           <strong>Offer & Event List</strong>
                        </li>
                    </ol>
					
                </div>
				 <div class="col-lg-2 mgTop pull-right">
					<a href="Add-Event"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Add Event</button></a>
                                
			   </div>
               
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row">
				
                
                <div class="col-lg-8">
				<?php if($_GET['response']=="Added"){ ?>
					<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>SUCCESS !! Event has been posted successfully.</strong>
                     </div>
					<?php 
						}
				$getEventResult = mysqlSelect("*","offers_events","company_id='".$admin_id."' and event_type=1","event_id desc","","","");
												
				if(empty($getEventResult)) { ?>
				<div class="tab-content">			
					<div class="tab-pane m-l-xxl active">
						<center><p>You haven't shared any content on this page so far. <br>Would you like to add a Blog ?<br>Click here to add</p>
					 <div class="col-lg-2 m-l-xxl mgTop hidden-xs">
					<a href="Add-Event"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Add Event</button></a>
                                
					</div>
										
					
					</center>
					</div>
				</div>
				<?php } else { ?>
					<div class="tab-content">			
					<div id="tab-1" class="tab-pane active">
					<?php 
					foreach($getEventResult as $postResultList){
						
							$postid=md5($postResultList['event_id']);
							$posttype=$postResultList['post_type'];
							$postdate=$postResultList['created_date'];	
							$posttitle=$postResultList['title'];
							$numviews=$postResultList['num_views'];
							$postDescription="<p>".substr(strip_tags($postResultList['description']),0,400)."</p>";
							
							$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$postResultList['Login_User_Id']."'","","","","");
							
								if($postResultList['Login_User_Id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
									//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$postResultList['Login_User_Id']."/".$getDocName[0]['Prof_pic']; 
									}else{
									$userimg="../assets/img/anonymous-profile.png";
									}
								}
								else{
									$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$postResultList['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="../premium/company_logo/".$postResultList['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							if(!empty($postResultList['photo'])){
							$postimage="../premium/Eventimages/".$postResultList['event_id']."/".$postResultList['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							
					
									
						?>	
						
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right" style="text-transform:uppercase;"><?php echo $posttype; ?></span></a>
                            
                        </div>
                        <div class="social-avatar">
                            <a href="" class="pull-left">
                                <img alt="image" src="<?php echo $userimg;?>">
                            </a>
                            <div class="media-body">
                                 <a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>">
                                    <?php if(!empty($posttitle)){ echo $posttitle; } ?>
                                </a>
                                <small class="text-muted">Posted on  <?php echo date('d M',strtotime($postdate)); ?></small>
                            </div>
                        </div>
                        <div class="social-body">
                           
							 <?php if(!empty($postimage)){ ?><a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>"><img src="<?php echo $postimage; ?>" width="650" class="img-responsive"/></a> <?php } ?>
                               <?php if(!empty($postDescription)){ echo $postDescription." <a class='label label-info pull-right' href=".$url."?s=".$posttype."&id=".$postid.">Read more</a>"; } ?>
                           

                            <div class="btn-group">
                                <button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i> Like this!</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-share"></i> Share</button>
                            </div>
                        </div>
                        <div class="social-footer">
							<div class="social-comment">
									<a href="" class="pull-left">
										<img alt="image" src="<?php if(!empty($getDoctorProfile[0]['Doc_Photo'])){ echo "docProfilePic/".$getDoctorProfile[0]['Doc_Id']."/".$getDoctorProfile[0]['Doc_Photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>">
									</a>
									<div class="media-body">
										<textarea class="form-control" placeholder="Write comment..."></textarea>
									</div>
							</div>
                            

                        </div>

                    </div>
					<?php }
					
					?>
					
					<!--<button class="btn btn-primary btn-block m"><i class="fa fa-arrow-down"></i> Show More</button>-->
                   </div>
				 
				
					
					</div>
				<?php } ?>

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
	<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Sparkline -->
    <script src="../assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <script>
        $(document).ready(function() {


            $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 48], {
                type: 'line',
                width: '100%',
                height: '50',
                lineColor: '#1ab394',
                fillColor: "transparent"
            });


        });
    </script>
	
	
	 <!-- slick carousel-->
    <script src="../assets/js/plugins/slick/slick.min.js"></script>

    <!-- Additional style only for demo purpose -->
    <style>
        .slick_demo_2 .ibox-content {
            margin: 0 10px;
        }
    </style>

    <script>
        $(document).ready(function(){


            $('.slick_demo_1').slick({
                dots: true
            });

            $('.slick_demo_2').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                centerMode: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            $('.slick_demo_3').slick({
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                adaptiveHeight: true
            });
        });

    </script>
 <script src="../assets/js/plugins/video/responsible-video.js"></script>
</body>

</html>
