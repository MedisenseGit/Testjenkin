<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}

include('connect.php');
include('functions.php');

$getOffersResult = mysqlSelect("*","offers_events","md5(event_id)='".$_GET['id']."'","","","","");

$getPostKey=urlencode($getOffersResult[0]['event_trans_id']);
$getTitle= hyphenize($getOffersResult[0]['title']);
$shareLink=HOST_MAIN_URL."Refer/share-post/".$getTitle."/".$getPostKey;

if($getOffersResult[0]['event_type']=="1"){
	$post_type="Events";
	//To Check Job apllied or not
	$checkEventApplied = mysqlSelect("*","job_event_application","applicant_id='".$admin_id."' and job_id='".$getOffersResult[0]['event_id']."'","","","","");

}else if($getOffersResult[0]['event_type']=="2"){
	$post_type="Offers";
}else if($getOffersResult[0]['event_type']=="3"){
	$post_type="Jobs";
	//To Check Job apllied or not
	$checkJobApplied = mysqlSelect("*","job_event_application","applicant_id='".$admin_id."' and job_id='".$getOffersResult[0]['event_id']."'","","","","");

}										


$getFeature = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = mysqlSelect("a.ref_id as ref_id,a.ref_id as ref_id,a.doc_photo as doc_photo,a.ref_name as Doc_Name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$getOffersResult[0]['oganiser_doc_id']."'","","","","");


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blogs</title>

    <?php include_once('support.php'); ?>
	<!-- Ladda style -->
    <link href="../assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=191717377898171&quote=medisense-community";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</head>

<body>

<div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Event detail</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="Home">Home</a>
                    </li>
                    
                    <li class="active">
                        <strong>Event detail</strong>
                    </li>
                </ol>
            </div>
             <div class="col-lg-2 mgTop">
					<a href="Home"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
			<?php if($_GET['response']=="job-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>SUCCESS !!</strong> Your job application has been sent successfully.
								 </div>
								<?php } if($_GET['response']=="event-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>SUCCESS !!</strong> Your registration request has been sent successfully to the organising committee.
								 </div>
								<?php } ?>
                <div class="col-lg-12">
				<?php   //TO CHECK USER TYPE WHETHER HE IS DOCTOR OR NOT
						
							
									$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getOffersResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="company_logo/".$getOffersResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								
													
								
						 ?>
                    <div class="ibox product-detail">
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        
                        <div>
                            <div class="ibox-content no-padding border-left-right">
							<?php if(!empty($getOffersResult[0]['photo'])){ ?>
							   <img alt="image" class="img-responsive" src="Eventimages/<?php echo $getOffersResult[0]['event_id']; ?>/<?php echo $getOffersResult[0]['photo']; ?>">
                            <?php } ?>
							</div>
							<!--<div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo $userimg; ?>">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">5m ago</small>
                                            <h4><strong><?php echo $username; ?></strong> <?php echo $userprof; ?></h4> <br>
                                            <small class="text-muted">Today 5:60 pm - 12.06.2014</small>

                                        </div>
                                    </div>-->
							
							<hr>
                            <div class="ibox-content no-padding  profile-content">
							<div class="col-md-12">
                                        <h3>Share to social media:</h3>
										
										<p>Lets share this post to your friends on their emails</p>
					
								<div class="input-group">
								
								<input type="hidden" name="shareLink" id="shareLink" value="<?php echo $shareLink; ?>" />
								<input type="hidden" name="mailsub" id="mailsub" value="<?php echo $getOffersResult[0]['title']; ?>" />
								
									<input type="email" class="form-control" required="required" name="receiverMail" id="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" name="cmdshareinner" id="cmdshareinner" ><i class="fa fa-check"></i></button>
									</span>
								
								</div>
								<br>
                                  <a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOST_MAIN_URL; ?>Refer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success dim" type="button"><i class="fa fa-facebook"></i></button></a>
								   <a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getOffersResult[0]['title']."...For more info visit : ".HOST_MAIN_URL."Refer/share-post/".$getTitle."/".$getPostKey; ?>"  data-size="small"><button class="btn btn-info dim" type="button"><i class="fa fa-twitter"></i></button></a>
									<a target="_blank" href="https://plus.google.com/share?url=<?php echo HOST_MAIN_URL; ?>Refer%2Fshare-post%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><button class="btn btn-danger dim" type="button"><i class="fa fa-google"></i></button></a>
									
									 </div>
                                
                            </div>
                    </div>
                </div>
                    </div>
                                <div class="col-md-8">

                                    <h2 class="font-bold m-b-xs">
                                        <?php if(!empty($getOffersResult[0]['title'])){ echo $getOffersResult[0]['title']; } ?>
                                    </h2>
									<div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo $userimg; ?>">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">Posted on: <br><?php echo date('d',strtotime($getOffersResult[0]['created_date'])); ?>
                              <?php echo date('M',strtotime($getOffersResult[0]['created_date'])); ?></small>
                                            <h4><strong><?php echo $username; ?></strong>
                                            
                                        </div>
                                    </div>
									 <?php if($_GET['s']=="Events") {  //If type is Events then display following Div  ?>
									<h4><i class="fa fa-calendar"></i> Event Date</h4>
                                    <div class="text-muted">
										<?php echo date('d M Y',strtotime($getOffersResult[0]['start_date']))." - ".date('d M Y',strtotime($getOffersResult[0]['end_date'])); ?></p>
                           
									</div>	
						
					 
                                    <hr>
									<h4><i class="fa fa-user"></i> Organisers</h4>
                                    <div class="text-muted">
										<p><ul class="list-inline prod_color">
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
                        </ul></p>
                           
									</div>	
						
					 
                                    <hr>

									
                                    <h4>Description</h4>

                                    <div class="text-muted">
                                        <?php if(!empty($getOffersResult[0]['description'])){ echo $getOffersResult[0]['description']; } ?>
                                    </div>
                                  
                                    <hr>
					<script language="JavaScript" src="js/status_validation.js"></script>				
					
						<div class="product_social">
						<form method="post" name="frmRegister" action="add_details.php">	
						<input type="hidden" name="cmdReg" value="" />
						<input type="hidden" name="partner_id" value="" />
						<input type="hidden" name="event_id" value="" />
						
						<button type="submit"  name="register" id="register" class="btn btn-success" <?php if($checkEventApplied==true){ echo "disabled"; } else { echo "";} ?> onclick="return cmdRegister(<?php echo $admin_id; ?>,<?php echo $getOffersResult[0]['event_id']; ?>);" ><i class="fa fa-sign-in"></i> REGISTER HERE</button>
						</form>
						</div>
					
						  <hr>
					  
					  <?php if(!empty($getOffersResult[0]['description_attachment'])){ ?>
					  
					  <div class="text-muted">
						<a href="download-Attachments.php?event_id=<?php echo $getOffersResult[0]['event_id'];?>&type=event&attach_name=<?php echo $getOffersResult[0]['description_attachment']; ?>" target="_blank"><h4><i class="fa fa-paperclip"></i><em> Click here to download brochure</em></h4></a>
					  </div>
					  <br>
					  <?php } ?>
					 
                      
					  <div class="text-muted">
					<a href="<?php echo $getOffersResult[0]['website_link']; ?>" target="_blank"><button type="submit"  name="addType" id="addType" class="btn btn-success"><i class="fa fa-external-link"></i> VISIT WEBSITE</button></a>
					</div>
					 <br>
					  <?php }  else if($_GET['s']=="Jobs") {  //If type is Events then display following Div?>
					<h4><i class="fa fa-calendar"></i> Interview Date</h4>
                                    <div class="text-muted">
										<?php echo date('d M Y',strtotime($getOffersResult[0]['start_date']))." - ".date('d M Y',strtotime($getOffersResult[0]['end_date'])); ?></p>
                           
									</div>	
						
					 
                                    <hr>
					<h4>Description</h4>

                                    <div class="text-muted">
                                        <?php if(!empty($getOffersResult[0]['description'])){ echo $getOffersResult[0]['description']; } ?>
                                    </div>
                                  
                                    <hr>

					<div class="product_social">
					
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" <?php if($checkJobApplied==true){ echo "disabled"; } else { echo ""; } ?> >
                                <i class="fa fa-graduation-cap"></i> APPLY JOB HERE
                            </button>
					</div>
					<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-graduation-cap modal-icon"></i>
                                            <h4 class="modal-title">Introduce Yourself</h4>
                                            <small class="font-bold">Apply Job Here</small>
                                        </div>
										<form enctype="multipart/form-data" method="post" name="frmType" action="add_details.php">
										<input type="hidden" name="event_id" value=<?php echo $getOffersResult[0]['event_id']; ?>" />
										<input type="hidden" name="doc_id" value=<?php echo $admin_id; ?>" />
										<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
                                        <div class="modal-body">
                                           
                                                    <div class="form-group"><label>Add Cover Note</label> <textarea  name="coverNote"  class="form-control" required="required" rows="3" placeholder=""></textarea></div>
													<div class="form-group"><label>Attach resume</label> <input type="file" id="txtAttach" required="required" name="txtAttach"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                                            <button type="submit"  name="addJobRequest" id="addJobRequest" class="btn btn-primary">Submit</button>
                                        </div>
										</form>
                                    </div>
                                </div>
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
					  
					   <br />
					 <?php } 
							$getPostKey=urlencode($getOffersResult[0]['event_trans_id']);
							$getTitle= hyphenize($getOffersResult[0]['title']);
							$shareLink=HOST_MAIN_URL."Refer/share-post/".$getTitle."/".$getPostKey;
							
							?>
                                    <!--<div>
                                        
										<div class="btn-group">
                                <button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i> Like this!</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
                                </div>
                                    </div>-->
									<div class="social-footer">
							<div class="social-comment">
									<a href="" class="pull-left">
										<img alt="image" src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "../Partners/partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>">
									</a>
									<div class="media-body">
									<input type="hidden" name="listingid" id="listingid" value="<?php echo $postResultList['listing_id']; ?>" />
									<input type="hidden" name="posttype" id="posttype" value="<?php echo $post_type; ?>" />
									<input type="hidden" name="comment_id" id="comment_id" value="<?php echo $getOffersResult[0]['event_id']; ?>" />
									<input type="hidden" name="user_id" id="user_id" value="<?php echo $admin_id; ?>" />
									<input type="hidden" name="user_type" id="user_type" value="2" />
										<textarea class="form-control the-new-com" name="medical_cmnt_txt" id="medical_cmnt_txt" required="required" placeholder="Write comment..."></textarea>
										<span class="input-group-btn"> <button  class="ladda-button btn btn-primary m-t bt-add-com pull-right" data-style="zoom-in" type="submit">Comment</button> </span>
						<script type="text/javascript">
						$(function(){ 
						
											
						// on post Like click 
						$('.like').click(function(){
							
							var listingId = $('#listingid');
							var thePostType = $('#posttype');
							var thePostId = $('#comment_id');
							var theUserId = $('#user_id');
							var theUserType = $('#user_type');

							$.ajax({
									type: "POST",
									url: "add_like.php",
									data: 'act=add-like&postType='+thePostType.val()+'&postId='+thePostId.val()+'&userId='+theUserId.val()+'&userType='+theUserType.val(),
									success: function(html){
									//$('a#'+listingId).addClass('liked<?php echo $postResultList['listing_id']; ?>');	
									alert('Your have liked this post');
									}  
								});
							
						});
						
						
						
						
						// on post comment click 
						$('.bt-add-com').click(function(){
							var theCom = $('.the-new-com');
							var thePostType = $('#posttype');
							var thePostId = $('#comment_id');
							var theUserId = $('#user_id');
							var theUserType = $('#user_type');

							if( !theCom.val()){ 
								//alert('You need to write a comment!'); 
								
								swal({
										title: "Required!",
										text: "You need to write a comment!!!",
										type: "warning",
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok, got it!"
									});
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_comments.php",
									data: 'act=add-com&postCom='+theCom.val()+'&postType='+thePostType.val()+'&postId='+thePostId.val()+'&userId='+theUserId.val()+'&userType='+theUserType.val(),
									success: function(html){
										theCom.val('');
									//alert('Your comment has been posted successfully');
									swal({
											title: "Good job!",
											text: "Your comment has been posted successfully!",
											type: "success"
										});
									}  
								});
							}
						});
						
						

					});
				</script>
									</div>
							</div>
							<?php $getComment = mysqlSelect("*","home_post_comments","topic_id='".$getOffersResult[0]['event_id']."' and topic_type LIKE'%".$post_type."%'","","","","");
							foreach($getComment as $commentList){ 
							if($commentList['login_User_Type']=="1"){  //For Partner
							$getUser = mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['partner_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){
								$userimg="../standard/partnerProfilePic/".$commentList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="../assets/img/anonymous-profile.png";
								}
							}
							else if($commentList['login_User_Type']=="2"){ //For Doctor
							$getUser = mysqlSelect("ref_name,doc_photo","referal","ref_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['ref_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$userimg="../Doc/".$commentList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="../assets/img/anonymous-profile.png";
								}
							}	
							else if($commentList['login_User_Type']=="3"){  //For Hospital
							$getUser = mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
								}else{
								$userimg="../assets/img/anonymous-profile.png";
								}
							} ?>
                            <div class="social-comment">
                                <a href="" class="pull-left">
                                    <img alt="image" src="<?php echo $userimg; ?>">
                                </a>
                                <div class="media-body">
                                   <a href="#">
                                        <?php echo $userName; ?>
                                    </a>
                                   
                                    <br/>
                                       <?php echo $commentList['comments']; ?>
                                  
                                    <small class="text-muted"><?php echo timeAgo($commentList['post_date']); ?></small>
                                </div>
                            </div>

							<?php } ?>

                            

                        </div>


                                </div>
                            </div>

                        </div>
                        <div class="ibox-footer">
                            <span class="pull-right">
                                Posted on: <?php echo date('d',strtotime($getOffersResult[0]['created_date'])); ?>
                              <?php echo date('M Y',strtotime($getOffersResult[0]['created_date'])); ?>
                            </span>
                            <i class="fa fa-eye"></i><em><?php echo $getOffersResult[0]['num_views']; ?></em>
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
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="../assets/js/inspinia.js"></script>
<script src="../assets/js/plugins/pace/pace.min.js"></script>

<!-- slick carousel-->
<script src="../assets/js/plugins/slick/slick.min.js"></script>
<script src="js/share.js"></script>
<script>
    $(document).ready(function(){


        $('.product-images').slick({
            dots: true
        });

    });

</script>
<!-- Ladda -->
    <script src="../assets/js/plugins/ladda/spin.min.js"></script>
    <script src="../assets/js/plugins/ladda/ladda.min.js"></script>
    <script src="../assets/js/plugins/ladda/ladda.jquery.min.js"></script>
 <script src="js/share.js"></script>
<script>

    $(document).ready(function (){

        // Bind normal buttons
        Ladda.bind( '.ladda-button',{ timeout: 500 });

        // Bind progress buttons and simulate loading progress
        Ladda.bind( '.progress-demo .ladda-button',{
            callback: function( instance ){
                var progress = 0;
                var interval = setInterval( function(){
                    progress = Math.min( progress + Math.random() * 0.1, 1 );
                    instance.setProgress( progress );

                    if( progress === 1 ){
                        instance.stop();
                        clearInterval( interval );
                    }
                }, 200 );
            }
        });


        var l = $( '.ladda-button-demo' ).ladda();

        l.click(function(){
            // Start loading
            l.ladda( 'start' );

            // Timeout example
            // Do something in backend and then stop ladda
            setTimeout(function(){
                l.ladda('stop');
            },5000)


        });

    });

</script>
<!-- Sweet alert -->
<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
</body>

</html>
