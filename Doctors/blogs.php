<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('connect.php');
include('functions.php');

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}


$getPostResult = $objQuery->mysqlSelect("*","home_posts","md5(post_id)='".$_GET['id']."'","","","","");
										

$countBlog = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Blog","","","","");
$countOffer = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Offers","","","","");
$countEvent = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Events","","","","");




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

$getPostKey=urlencode($getPostResult[0]['postkey']);
$getTitle= hyphenize($getPostResult[0]['post_tittle']);
$shareLink="https://medisensecrm.com/Refer/share-blogs/".$getTitle."/".$getPostKey;

$commentResult = $objQuery->mysqlSelect("*","home_post_comments","md5(topic_id)='".$_GET['id']."' and topic_type='".$getPostResult[0]['post_type']."'","comment_id desc","","","");
$commentCount = $objQuery->mysqlSelect("COUNT(topic_id) as Count","home_post_comments","md5(topic_id)='".$_GET['id']."' and topic_type='".$getPostResult[0]['post_type']."'","","","","");

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
	
	<link href="css/comment_css.css" rel="stylesheet">
	<link href="css/comment_box.css" rel="stylesheet">
	
	<script type="text/javascript" src="like_assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="like_assets/js/function.js"></script>
		<script type="text/javascript" src="like_assets/js/comment_function.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		
		<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=191717377898171&quote=medisense-community";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<?php include_once('side_menu.php');?>
		 
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		  <?php include_once('header_top_nav.php'); ?>
           
            
            <div class="clearfix"></div>
				<!-- Notification Section -->				
				<?php
						if($_GET['response']=="success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> feedback has been sent successfully.
                  </div>
						
						<?php 
						}
						if($_GET['response']=="comment-success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Your comment has been posted successfully.
                  </div>
						
						<?php 
						} if($_GET['response']=="share-link-success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Link has been shared successfully.
                  </div>
						
						<?php 
						}?>
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-9 col-sm-9 col-xs-12">

                     
					
					
					<div id="blogSection">
					
					<ul class="messages" >
                          <li>
							<?php   //TO CHECK USER TYPE WHETHER HE IS DOCTOR OR NOT
						if($getPostResult[0]['Login_User_Type']=="doc"){
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
							
								if($getPostResult[0]['Login_User_Id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								}
								else{
									$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
								}
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg1="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg1="images/anonymous-profile.png";
								}
					
								
						} ?>
                            <img src="<?php echo $userimg1;?>" class="avatar" alt="Avatar">
                            <div class="message_date" style="float:right;">
							
                              <h4 class="date text-info"><?php echo date('d',strtotime($getPostResult[0]['post_date'])); ?></h4>
                              <h4 class="date text-info"><?php echo date('M',strtotime($getPostResult[0]['post_date'])); ?></h4>
                            </div>
                            <div class="message_wrapper">
                              <!--<h4 class="heading"><?php echo $username; ?><br><small><em><?php echo $userprof; ?></em></small></h4>-->
                              <blockquote class="message"><?php if(!empty($getPostResult[0]['post_tittle'])){ echo $getPostResult[0]['post_tittle']; } ?>
							  <h5 class="heading"><?php echo $username; ?></h5><small><em><?php echo $userprof; ?></em></small>
							  </blockquote>
                              <br />
							 	
                              <p >
							   <?php if(!empty($getPostResult[0]['post_image'])){ ?><img src="../Hospital/Postimages/<?php echo $getPostResult[0]['post_id']; ?>/<?php echo $getPostResult[0]['post_image']; ?>" width="650" class="img-responsive"/> <?php } ?>
							   
							   <?php if(!empty($getPostResult[0]['post_description'])){ echo $getPostResult[0]['post_description']; } ?>
                                <br></p>
								
							<p class="url">
							
								<ul class="nav navbar-left panel_toolbox">
								
								<li>
								<?php 
								if(check_bloglike($admin_id,$getPostResult[0]['post_id']) == 0) { ?>
								<a href="javascript:void();" class="like" data-toggle="tooltip" id="<?php echo $getPostResult[0]['post_id']; ?>" data-placement="bottom" title="Like" style="font-size:20px;"><i class="fa fa-thumbs-up"></i> <small><?php echo bloglike($getPostResult[0]['post_id']); ?></small></a>
								<?php } else { ?>
								<a href="javascript:void();" class="liked" data-toggle="tooltip" data-placement="bottom" title="Like" style="font-size:20px; color:green;"> <i class="fa fa-thumbs-up"></i> <small><?php echo bloglike($getPostResult[0]['post_id']); ?></small></a>
								<?php } ?>
								</li>
							  <li><a style="font-size:20px;"><i class="fa fa-comment"></i> <small><?php echo $commentCount[0]['Count']; ?></small></a>
							  
							  
							  </li>
							  <li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="font-size:20px;"><i class="fa fa-share-alt"></i></a>
								<ul class="dropdown-menu" role="menu">
								  <li><a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><i class="fa fa-facebook-square"></i> Facebook</a>
								  </li>
								  <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getPostResult[0]['post_tittle']."...For more info visit : https://medisensecrm.com/Refer/share-blogs/".$getTitle."/".$getPostKey; ?>"  data-size="small"><i class="fa fa-twitter-square"></i> Twitter</a>
								  </li>
								  <li><a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus-square"></i> Google +</a>
								  </li>
								 
								</ul>
							  </li>
								</ul>
							
								</p>
								
								 <div class="clearfix"></div>
                            </div>
                          </li>
                          
                        </ul>
						<!--<div class="row">
    
						<div class="col-md-12">
    						<div class="widget-area no-padding blank">
								<div class="status-upload">
									<form>
										<textarea placeholder="Write comments here..." required="required" ></textarea>
										
										<button type="submit" class="btn btn-success green"><i class="fa fa-comment"></i> Add Comment</button>
									</form>
								</div>
							</div>
						</div>
        
					</div>-->
						<div class="row" id="" >
							<!-- Contenedor Principal -->
							<div class="comments-container">
								<h1><a href="http://creaticode.com">Comments</a></h1>
								
								<ul id="comments-list" class="comments-list">
								<li>
										<div class="comment-main-level">
											<!-- Avatar -->
											<div class="comment-avatar"><img src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "../Doc/".$getDoctorProfile[0]['Doc_Id']."/".$getDoctorProfile[0]['Doc_Photo']; } else { echo "../Hospital/images/anonymous-profile.png"; } ?>" alt=""></div>
											<!-- Contenedor del Comentario -->
											<div class="comment-box">
												
												<div class="comment-content">
													<div class="widget-area no-padding blank">
													<div class="status-upload">
														<form method="post" name="frmComment" action="add_details.php">
														<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />	
														<input type="hidden" name="topicId" value="<?php echo $getPostResult[0]['post_id']; ?>" />
														<input type="hidden" name="partnerId" value="<?php echo $admin_id; ?>" />
														<input type="hidden" name="topicType" value="<?php echo $getPostResult[0]['post_type']; ?>" />
														
														<textarea placeholder="Write comments here..." required="required" name="userComment" ></textarea>
														<button type="submit" name="CommentBtn" class="btn btn-success green"><i class="fa fa-comment"></i> Add Comment</button>
													</form>
													</div>
													</div>
												</div>
											</div>
										</div>
										
									</li>
									<?php  
										
									foreach($commentResult as $listRslt){
										if($listRslt['login_User_Type']=="1"){ //1 for practice doctor
										$getUser = $objQuery->mysqlSelect("*","our_partners","partner_id='".$listRslt['login_id']."'","","","","");
											if(!empty($getUser[0]['doc_photo']))
											{ $userPic="partnerProfilePic/".$getUser[0]['partner_id']."/".$getUser[0]['doc_photo']; 
											} else { 
												$userPic="../Hospital/images/anonymous-profile.png"; 
											}
											$username=$getUser[0]['contact_person'];
										}
										else{ //2 for leap doctor
											$getUser = $objQuery->mysqlSelect("*","referal","ref_id='".$listRslt['login_id']."'","","","","");
											if(!empty($getUser[0]['doc_photo']))
											{ $userPic="../Doc/".$getUser[0]['ref_id']."/".$getUser[0]['doc_photo']; 
											} else { 
												$userPic="../Hospital/images/anonymous-profile.png"; 
											}
											$username=$getUser[0]['ref_name'];
										
										}
										
									?>
									
									
									<li>
										<div class="comment-main-level">
											<!-- Avatar -->
											<div class="comment-avatar"><img src="<?php echo $userPic; ?>" alt="User Image"></div>
											<!-- Contenedor del Comentario -->
											<div class="comment-box">
												<div class="comment-head">
													<h6 class="comment-name"><?php echo $username; ?></h6>
													<!--<span></span>-->
													<i class="fa fa-calendar"> <?php echo date('d M Y',strtotime($listRslt['post_date'])); ?></i> 
													<!--<i class="fa fa-heart"></i>-->
												</div>
												<div class="comment-content">
													<?php echo $listRslt['comments']; ?>
												</div>
											</div>
										</div>
										
									</li>
									<?php } ?>

								</ul>
							</div>
							</div>
						
						 <div class="clearfix"></div>
					
					
						</div>
						</form>
						<!-- Add Blog Section -->
					<div id="addBlog"></div>
					
                    </div>
					<div class="col-md-3 col-sm-3 col-xs-12">
              
                <div class="x_title">
                  <h2><i class="fa fa-newspaper-o"></i> Related Posts </h2>
                  
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="dashboard-widget-content">

                    <ul class="list-unstyled timeline widget">
					<?php $getBlogList = $objQuery->mysqlSelect("*","home_posts","md5(post_id)!='".$_GET['id']."'","post_id desc","","","0,6"); 
										foreach($getBlogList as $listBlog){
											
											$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$listBlog['Login_User_Id']."'","","","","");
							
												if($listBlog['Login_User_Id']!=0){
												
													$username=$getDocName[0]['ref_name'];
													
												}
												else{
													$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$listBlog['company_id']."'","","","","");
													$username=$getOrg[0]['company_name'];
													
												}
										
							?>
                      <li>
                        <div class="block">
                          <div class="block_content">
                            <h2 class="title" style="font-size:14px;">
                                              <a href="blogs.php?s=Blog&id=<?php echo md5($listBlog['post_id']); ?>"> <?php echo $listBlog['post_tittle']; ?> </a>
                                          </h2>
                            <div class="byline">
                              <span><?php echo date('d M',strtotime($listBlog['post_date'])); ?></span> by <a><?php echo $username; ?></a>
                            </div>
                             </div>
                        </div>
                      </li>
                     <?php } ?>
                     
                    </ul>
                  </div>
                </div>
             
            </div>	
                    <!-- start project-detail sidebar -->
                    <div class="col-md-3 col-sm-3 col-xs-12">

                      
					  
					  <section class="panel">
						
                         <div class="product_social">
					 <h2><i class="fa fa-share-alt"></i> Share Post</h2>
					 <p>Lets share this post to your friends on their emails</p>
					 <form method="post" action="add_details.php" name="frmShare" >
								<div class="input-group">
								
								<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="shareLink" value="<?php echo $shareLink; ?>" />
								<input type="hidden" name="mailsub" value="<?php echo $getPostResult[0]['post_tittle']; ?>" />
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" required="required" name="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" type="submit" name="cmdshareinner" ><i class="fa fa-check"></i></button>
									</span>
								
								</div>
					</form>
					 
					 
                        <ul class="list-inline">
                          <li><a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><i class="fa fa-facebook-square"></i></a>
                          </li>
                          <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getPostResult[0]['post_tittle']."...For more info visit : https://medisensecrm.com/Refer/share-blogs/".$getTitle."/".$getPostKey; ?>"  data-size="small"><i class="fa fa-twitter-square"></i></a>
                          </li>
                          <li><a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
                          </li>
                         
                        </ul>
                      </div>
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
	
	  <script src="js/showHide.js"></script>
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