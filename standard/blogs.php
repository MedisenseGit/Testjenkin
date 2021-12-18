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
if($getPostResult[0]['post_type']=="blog"){
	$post_type="Blog";
}
else if($getPostResult[0]['post_type']=="surgical") {
	$post_type="Surgical";
}
$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = $objQuery->mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(c.company_id)='".$admin_id."'","a.Tot_responded desc","","","");

$getPostKey=urlencode($getPostResult[0]['postkey']);
$getTitle= hyphenize($getPostResult[0]['post_tittle']);
$shareLink="https://medisensecrm.com/Refer/share-blogs/".$getTitle."/".$getPostKey;


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blogs</title>

    <?php include_once("support.php"); ?>
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
                <h2>Blog detail</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="Home">Home</a>
                    </li>
                    
                    <li class="active">
                        <strong>Blog detail</strong>
                    </li>
                </ol>
            </div>
             <div class="col-lg-2 mgTop">
					<a href="Home"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">

            <div class="row">
                <div class="col-lg-12">
				<?php   //TO CHECK USER TYPE WHETHER HE IS DOCTOR OR NOT
						
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
							
								if($getPostResult[0]['Login_User_Id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$getPostResult[0]['Login_User_Id']."/".$getDocName[0]['Prof_pic']; 
									}else{
									$userimg="../assets/img/anonymous-profile.png";
									}
								}
								else{
									$getOrg = $objQuery->mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="../Hospital/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
													
								
						 ?>
                    <div class="ibox product-detail">
                        <div class="ibox-content">

                            <div class="row">
                                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        
                        <div>
                            <div class="ibox-content no-padding border-left-right">
							<?php if(!empty($getPostResult[0]['post_image'])){ ?>
							   <img alt="image" class="img-responsive" src="../Hospital/Postimages/<?php echo $getPostResult[0]['post_id']; ?>/<?php echo $getPostResult[0]['post_image']; ?>">
                            <?php } 
							
							$getCode=$getPostResult[0]['video_id'];
							$getVideoPost="<figure><iframe width='320' height='172' src='https://www.youtube.com/embed/".$getCode."' frameborder='0' allowfullscreen></iframe></figure>";
							
							if(!empty($getCode)){ echo $getVideoPost; }
							
							?>
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
								<input type="hidden" name="mailsub" id="mailsub" value="<?php echo $getPostResult[0]['post_tittle']; ?>" />
								
									<input type="email" class="form-control" required="required" name="receiverMail" id="receiverMail" placeholder="raj@mail.com" />
									<span class="input-group-btn">
										<button class="btn" name="cmdshareinner" id="cmdshareinner"><i class="fa fa-check"></i></button>
									</span>
								
								</div>
					<br>
                           <a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success dim" type="button"><i class="fa fa-facebook"></i></button></a>
                           <a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getPostResult[0]['post_tittle']."...For more info visit : https://medisensecrm.com/Refer/share-blogs/".$getTitle."/".$getPostKey; ?>"  data-size="small"><button class="btn btn-info dim" type="button"><i class="fa fa-twitter"></i></button></a>
                            <a target="_blank" href="https://plus.google.com/share?url=https%3A%2F%2Fmedisensecrm.com%2FRefer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><button class="btn btn-danger dim" type="button"><i class="fa fa-google"></i></button></a>
                                
									 </div>
                                
                            </div>
                    </div>
                </div>
                    </div>
                                <div class="col-md-8">

                                    <h2 class="font-bold m-b-xs">
                                        <?php if(!empty($getPostResult[0]['post_tittle'])){ echo $getPostResult[0]['post_tittle']; } ?>
                                    </h2>
									<div class="feed-element">
                                        <a href="#" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?php echo $userimg; ?>">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right">Posted on: <br><?php echo date('d',strtotime($getPostResult[0]['post_date'])); ?>
                              <?php echo date('M',strtotime($getPostResult[0]['post_date'])); ?></small>
                                            <h4><strong><?php echo $username; ?></strong>
                                            
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    <h4>Description</h4>

                                    <div class="text-muted">
                                        <?php if(!empty($getPostResult[0]['post_description'])){ echo $getPostResult[0]['post_description']; } ?>
                                    </div>
                                  
                                  
							
                                    <div>
                                        
										<!--<div class="btn-group">
                                <button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i> Like this!</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
                                </div>
                                    </div>-->
									<div class="social-footer">
							<div class="social-comment">
									<a href="" class="pull-left">
										<img alt="image" src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "../Refer/partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>">
									</a>
									<div class="media-body">
									<input type="hidden" name="posttype" id="posttype" value="<?php echo $post_type; ?>" />
									<input type="hidden" name="comment_id" id="comment_id" value="<?php echo $getPostResult[0]['post_id']; ?>" />
									<input type="hidden" name="user_id" id="user_id" value="<?php echo $admin_id; ?>" />
									<input type="hidden" name="user_type" id="user_type" value="1" />
										<textarea class="form-control the-new-com" name="medical_cmnt_txt" id="medical_cmnt_txt" required="required" placeholder="Write comment..."></textarea>
										<span class="input-group-btn"> <button  class="ladda-button btn btn-primary m-t bt-add-com pull-right" data-style="zoom-in" type="submit">Comment</button> </span>
						<script type="text/javascript">
						$(function(){ 
						
											
						// on post Like click 
						$('.like').click(function(){
							
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
							<?php 
							
							$getComment = $objQuery->mysqlSelect("*","home_post_comments","md5(topic_id)='".$_GET['id']."' and topic_type LIKE'%".$getPostResult[0]['post_type']."%'","comment_id desc","","","");

							foreach($getComment as $commentList){ 
							if($commentList['login_User_Type']=="1"){  //For Partner
							$getUser = $objQuery->mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['partner_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){
								$userimg="../Refer/partnerProfilePic/".$commentList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="../assets/img/anonymous-profile.png";
								}
							}
							else if($commentList['login_User_Type']=="2"){ //For Doctor
							$getUser = $objQuery->mysqlSelect("ref_name,doc_photo","referal","ref_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['ref_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){ 
								$userimg="../Doc/".$commentList['login_id']."/".$getUser[0]['doc_photo']; 
								}else{
								$userimg="../assets/img/anonymous-profile.png";
								}
							}	
							else if($commentList['login_User_Type']=="3"){  //For Hospital
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../Hospital/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
                                Posted on: <?php echo date('d',strtotime($getPostResult[0]['post_date'])); ?>
                              <?php echo date('M Y',strtotime($getPostResult[0]['post_date'])); ?>
                            </span>
                            <i class="fa fa-eye"></i><em><?php echo $getPostResult[0]['num_views']; ?></em>
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
