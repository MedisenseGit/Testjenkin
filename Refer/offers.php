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
if($getOffersResult[0]['event_type']=="1"){
	$post_type="event";
}else if($getOffersResult[0]['event_type']=="2"){
	$post_type="offers";
}else{
	$post_type="jobs";
}										

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
$login_userimg=HOST_MAIN_URL."Doc/".$login_id."/".$getloginuser[0]['doc_photo']; 
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
$get_pro = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_id as ref_id,a.doc_photo as doc_photo,a.ref_name as Doc_Name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");

$commentResult = $objQuery->mysqlSelect("*","home_post_comments","md5(topic_id)='".$_GET['id']."' and topic_type='".$post_type."'","comment_id desc","","","");
$commentCount = $objQuery->mysqlSelect("COUNT(topic_id) as Count","home_post_comments","md5(topic_id)='".$_GET['id']."' and topic_type='".$post_type."'","","","","");

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
	
	
    
    <!-- Themes -->
   
    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/css-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/bootstrap-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars-o.css">

   
	
<script type="text/javascript">
$(document).ready(function(){
	var maxLength = 300;
	$(".show-read-more").each(function(){
		var myStr = $(this).text();
		if($.trim(myStr).length > maxLength){
			var newStr = myStr.substring(0, maxLength);
			var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
			$(this).empty().html(newStr);
			$(this).append(' <a href="javascript:void(0);" class="read-more"><em>read more...</em></a>');
			$(this).append('<span class="more-text">' + removedStr + '</span>');
		}
	});
	$(".read-more").click(function(){
		$(this).siblings(".more-text").contents().unwrap();
		$(this).remove();
	});
});
</script>
<style type="text/css">
    .show-read-more .more-text{
        display: none;
    }
</style>
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
						if($_GET['response']=="job-success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Your job application has been sent successfully.
                  </div>
						
						<?php 
						} if($_GET['response']=="share-link-success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Link has been shared successfully.
                  </div>
						
						<?php 
						} if($_GET['response']=="comment-success"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> Your comment has been posted successfully.
                  </div>
						
						<?php 
						} ?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-7 col-sm-7 col-xs-12">
                      <div class="product-image">
                        <?php if(!empty($getOffersResult[0]['photo'])){ ?><img src="../Hospital/Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>" width="650" class="img-responsive"/> <?php } ?>
                      <p class="show-read-more"><?php echo $getOffersResult[0]['description']; ?></p>
                      <br />
					  </div>
                      <!--<div class="product_gallery">
					  <h4>Speakers</h4>
                        <a>
                          <img src="images/prod-2.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-3.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-4.jpg" alt="..." />
                        </a>
                        <a>
                          <img src="images/prod-5.jpg" alt="..." />
                        </a>
                      </div>-->
					   <!-- Contenedor Principal -->
							<div class="comments-container">
								<h1><a>Comments</a></h1>
								
								<ul id="comments-list" class="comments-list">
								<li>
										<div class="comment-main-level">
											<!-- Avatar -->
											<div class="comment-avatar"><img src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../Hospital/images/anonymous-profile.png"; } ?>" alt=""></div>
											<!-- Contenedor del Comentario -->
											<div class="comment-box">
												
												<div class="comment-content">
													<div class="widget-area no-padding blank">
													<div class="status-upload">
														<form method="post" name="frmComment" action="add_details.php">
														<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
														<input type="hidden" name="topicId" value="<?php echo $getOffersResult[0]['event_id']; ?>" />
														<input type="hidden" name="partnerId" value="<?php echo $admin_id; ?>" />
														<input type="hidden" name="topicType" value="<?php echo $post_type; ?>" />
														
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
					
					
					
					
                    <div class="col-md-5 col-sm-5 col-xs-12" style="border:0px solid #e5e5e5;">

                      <h3 class="prod_title"><?php if(!empty($getOffersResult[0]['title'])){ echo $getOffersResult[0]['title']; } ?></h3>

                      <?php if($_GET['s']=="Events") {  //If type is Events then display following Div  ?>
						 					 
					<div class="product_social">
					<button type="submit"  name="register" id="register" class="btn btn-success"><i class="fa fa-sign-in"></i> REGISTER HERE</button>
					</div>
						
						
						<div class="">
                        <h2><i class="fa fa-user"></i> Organisers</h2>
                        <ul class="list-inline prod_color">
						<?php if(!empty($getOffersResult[0]['oganiser_doc_id'])){ 
						
							
						?>
						 <li>
                            <p><?php echo $get_pro[0]['Doc_Name']; ?></p>
                            <a>
							<img src="../Doc/<?php echo $get_pro[0]['ref_id']; ?>/<?php echo $get_pro[0]['doc_photo']; ?>" alt="..." width="50"/>
							</a>
                          </li>
						<?php } else {	?>
						  <li>
                            <?php echo $getOffersResult[0]['organising_committee']; ?>
                            
                          </li>
						<?php } ?>
                        </ul>
                      </div>
                      <br>
					  <div class="">
                        <h2><i class="fa fa-calendar"></i> Date</h2>
						<ul class="list-inline prod_color">
                          <li>
                            <p><?php echo date('d M Y',strtotime($getOffersResult[0]['start_date']))." - ".date('d M Y',strtotime($getOffersResult[0]['end_date'])); ?></p>
                           
                          </li>
						 </ul>
					  </div>
					  <?php if(!empty($getOffersResult[0]['description_attachment'])){ ?>
					   <br />
					  <div class="">
						<a href="download-Attachments.php?event_id=<?php echo $getOffersResult[0]['event_id'];?>&type=event&attach_name=<?php echo $getOffersResult[0]['description_attachment']; ?>" target="_blank"><h4><i class="fa fa-paperclip"></i><em> Click here to download brochure</em></h4></a>
					  </div>
					  <br>
					  <?php } ?>
					  <br>
					  <h2><i class="fa fa-comments-o"></i> Feedback </h2>
					<div class="product_social">
					<a href="#myModal" data-toggle="modal" ><button type="submit"  name="addType" id="addType" class="btn btn-success">LEAVE FEEDBACK HERE</button></a>
					</div>
					<?php $getKeyNoteSpeakers= $objQuery->mysqlSelect("*","referal as a inner join keynote_speakers as b on a.ref_id=b.doc_id  inner join conference_login as c on c.conf_login_id=b.conf_id","b.conf_id='".$getOffersResult[0]['conf_id']."'","","","","");
						if($getKeyNoteSpeakers==true){
						?>
					  <br />
                      <div class="">
                        <h2><i class="fa fa-microphone"></i> Speakers </h2>
						
                        <ul class="list-inline prod_color">
						<?php foreach($getKeyNoteSpeakers as $list){ 
						$speaker_name=$list['ref_name'];
						if(!empty($list['doc_photo'])){
							$docimg="../Doc/".$list['ref_id']."/".$list['doc_photo'];
						}else{
							$docimg="images/anonymous-profile.png";
						}
						
						if(!empty($list['doc_photo'])){
						?>
                          <li style="max-width:70px; height:100px;">
                            
                            <a>
							<img src="<?php echo $docimg; ?>" alt="..." width="50"/>
							</a>
							<p><?php echo $speaker_name; ?></p>
                          </li>
						<?php
						}
						
						} ?>
						 
                        </ul>
                      </div>
						<?php } ?>
                      <br />
					  <div class="product_social">
					<a href="<?php echo $getOffersResult[0]['website_link']; ?>" target="_blank"><button type="submit"  name="addType" id="addType" class="btn btn-success"><i class="fa fa-external-link"></i> VISIT WEBSITE</button></a>
					</div>
					 
					  <?php }  else if($_GET['s']=="Jobs") {  //If type is Events then display following Div?>
						<div class="product_social">
					<a href="#myModal_job" data-toggle="modal" ><button type="submit"  name="addType" id="addType" class="btn btn-success"><i class="fa fa-graduation-cap"></i> APPLY JOB HERE</button></a>
					</div>
					  <br />
						<div class="">
                        <h2><i class="fa fa-info-circle"></i> Contact Information</h2>
                        <ul class="list-inline prod_color">
												
                          <li>
                            <p><?php echo $getOffersResult[0]['job_contact_info']; ?></p>
                            
                          </li>
						
                        </ul>
                      </div><br>
					  <div class="">
					  <a href="download-Attachments.php?comp_id=<?php echo $getOffersResult[0]['company_id'];?>&attach_name=<?php echo $getOffersResult[0]['description_attachment']; ?>" target="_blank"><h4><i class="fa fa-paperclip"></i><em> Click here to download job description</em></h4></a>
					  </div>
					  
                      <br />
					  <div class="">
                        <h2><i class="fa fa-calendar"></i> Interview Date</h2>
						<ul class="list-inline prod_color">
                          <li>
                            <p><?php echo $getOffersResult[0]['start_end_date']; ?></p>
                           
                          </li>
						 </ul>
					  </div>
					   <br />
					 <?php } 
							$getPostKey=urlencode($getOffersResult[0]['event_trans_id']);
							$getTitle= hyphenize($getOffersResult[0]['title']);
							$shareLink="https://medisensecrm.com/Refer/share-post/".$getTitle."/".$getPostKey;
							
							?>
					 <div class="product_social">
					 <h2><i class="fa fa-share-alt"></i> Share Post</h2>
					 <p>Lets share this post to your friends on their emails</p>
					 <form method="post" action="add_details.php" name="frmShare" >
								<div class="input-group">
								
								<input type="hidden" name="currenturl" value="<?php echo $_SERVER['REQUEST_URI']; ?>" />
								<input type="hidden" name="shareLink" value="<?php echo $shareLink; ?>" />
								<input type="hidden" name="mailsub" value="<?php echo $getOffersResult[0]['title']; ?>" />
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="email" class="form-control" required="required" name="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" type="submit" name="cmdshareinner" ><i class="fa fa-check"></i></button>
									</span>
								
								</div>
					</form>
					 
					 
                        <ul class="list-inline">
                          <li><a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><i class="fa fa-facebook-square"></i></a>
                          </li>
                          <li><a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getOffersResult[0]['title']."...For more info visit : https://medisensecrm.com/Refer/share-post/".$getTitle."/".$getPostKey; ?>"  data-size="small"><i class="fa fa-twitter-square"></i></a>
                          </li>
                          <li><a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a>
                          </li>
                         
                        </ul>
                      </div>
					
                      
					 
					

                    </div>

						
						
					<div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel"> Feedback</h4>
                        
						
				  </div>
																<form method="post" name="frmType" action="add_details.php">
																<div class="alert alert-danger alert-dismissible fade in" role="alert">
                    
																<strong><i class="fa fa-exclamation-triangle"></i> Register yourself to submit your valuable feedback</strong>
															  </div>
																<input type="hidden" name="event_id" value=<?php echo $getOffersResult[0]['conf_id']; ?>" />
																<input type="hidden" name="partner_id" value=<?php echo $admin_id; ?>" />
																<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
																
																	<div class="modal-body" style="padding-bottom:50px;">
																		
																		<div class="col-md-12">
																		<label for="inputComment">Keynote Speaker</label>
																		<select  name="speaker" required="required" class="form-control" >
																		<option value="">Select</option>
																		<?php foreach($getKeyNoteSpeakers as $list){ ?>
																			<option value="<?php echo $list['ref_id']; ?>"><?php echo $list['ref_name']; ?></option>
																		<?php } ?> 
																		</select>
																		</div><br><br><br><br>
																		<div class="col-md-12">
																		<label for="inputComment">Quality of the session</label>
																		<div class="stars stars-example-fontawesome">
																			<select id="example-fontawesome" name="rating" required="required" autocomplete="off">
																			  <option value="1">1</option>
																			  <option value="2">2</option>
																			  <option value="3">3</option>
																			  <option value="4">4</option>
																			  <option value="5">5</option>
																			</select>
																			
																		  </div>
																		</div><br><br><br><br>
																		<div class="col-md-12">
																		<label for="inputComment">Comment</label>
																			<textarea  name="comment"  class="form-control" rows="3" placeholder=""></textarea>
																		</div>
																		
                        
																			<div class="pull-right"  style="margin-top:10px;">	
																				
																			<button type="submit"  name="addFeedback" id="addFeedback" class="btn btn-primary">Submit</button>
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
																			</div>
																	</div>
																	</form>
																	
                       
						</div>
                        

                      </div>
                    </div>
					
					<div id="myModal_job" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel"> Introduce Yourself</h4>
                        </div>
																<form enctype="multipart/form-data" method="post" name="frmType" action="add_details.php">
																<input type="hidden" name="event_id" value=<?php echo $getOffersResult[0]['event_id']; ?>" />
																<input type="hidden" name="partner_id" value=<?php echo $admin_id; ?>" />
																<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
																
																
																	<div class="modal-body" style="padding-bottom:50px;">
																		
																		
																		<div class="col-md-12">
																		<label for="inputComment">Add Cover Note</label>
																			<textarea  name="coverNote"  class="form-control" required="required" rows="3" placeholder=""></textarea>
																		</div>
																		<div class="col-md-12">
																		<label for="inputComment">Attach resume</label>
																			<input type="file" id="txtAttach" required="required" name="txtAttach">
																		</div>
																		
                        
																			<div class="pull-right"  style="margin-top:10px;">	
																				
																			<button type="submit"  name="addJobRequest" id="addJobRequest" class="btn btn-primary">Submit</button>
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
																			</div>
																	</div>
																	</form>
																	
                       
						</div>
                        

                      </div>
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
 
    <script src="starrating/jquery.barrating.js"></script>
    <script src="starrating/js/examples.js"></script>
  </body>
</html>