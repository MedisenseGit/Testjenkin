<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

//$getClientIp=$_SERVER['REMOTE_ADDR'];
//include('connect.php');   // removed on 17/11/2021
include('functions.php');

$admin_id = $_SESSION['user_id'];
if(empty($admin_id))
{
	header("Location:login");
}


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Blog-Surgical List</title>
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
	<!-- Side Menu -->
    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
		<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-4">
                    <h2>Home</h2>
                    <ol class="breadcrumb">
                        <li >
                           <a href="Home"> Home</a>
                        </li>
						<li class="active">
                           <strong>Blog Video List</strong>
                        </li>
                    </ol>
					
                </div>
				 <div class="col-lg-2 mgTop">
					<a href="Add-Blog"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Add Blog</button></a>
                                
			   </div>
                <div class="col-lg-2 mgTop">
					<a href="Add-Surgical-Video"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-film"></i> Add Video</button></a>
                                
			   </div>
			    <div class="col-lg-2 mgTop">
					<a href="Add-Campaign"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Create Campaign</button></a>
                                
			   </div>
			    <div class="col-lg-2 mgTop">
					<a href="Import-Contacts"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-upload"></i> Import Contacts</button></a>
                                
			   </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row">
				
                
                <div class="col-lg-8">
				<?php 
				$getPostResult = mysqlSelect("post_id,post_type","home_posts","Login_User_Id='".$admin_id."'","post_id desc","","","");
											
				if(empty($getPostResult)) { ?>
				<div class="tab-content">			
					<div class="tab-pane m-l-xl active">
						<center><p>You haven't shared any content on this page so far. <br>Would you like to add a Blog or Video ?<br>Click here to add</p>
					 <div class="col-lg-2 m-l-xl mgTop hidden-xs">
					<a href="Add-Blog"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Add Blog</button></a>
                                
					</div>
					<div class="col-lg-2 m-l-xl mgTop hidden-xs">
					<a href="Add-Surgical-Video"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-film"></i> Add Video</button></a>
                                
					</div>
					
					<div class="col-lg-2 m-l-xl mgTop hidden-xs">
					<a href="Add-Campaign"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-rss"></i> Create Campaign</button></a>
                                
					</div>
					
					<div class="col-lg-2 m-l-xl mgTop hidden-xs">
					<a href="Import-Contacts"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-upload"></i> Import Contacts</button></a>
                                
					</div>
					
					</center>
					
					</div>
				</div>
				<?php } else { ?>
					<div class="tab-content">			
					<div id="tab-1" class="tab-pane active">
					
					<?php 
					foreach($getPostResult as $postResultList){
						
															
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['post_type']=="blog"){
							$getPostResult = mysqlSelect("*","home_posts","post_id='".$postResultList['post_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$gethypenTitle= hyphenize($getPostResult[0]['post_tittle']);
							$cat_type="1";
							$posttype="Blog";
							$postdate=$getPostResult[0]['post_date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['postkey'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['post_description']),0,400)."</p>";
							
							$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
							
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
									$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="Company_Logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="Blogs";
							$blogtype="share-blogs";
							
						} else if($postResultList['post_type']=="surgical"){
							$getPostResult = mysqlSelect("*","home_posts","post_id='".$postResultList['post_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$gethypenTitle= hyphenize($getPostResult[0]['post_tittle']);
							$cat_type="2";
							$posttype="Surgical";
							$postdate=$getPostResult[0]['post_date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['postkey'];
							//$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $getPostResult[0]['video_url']);
							$getCode=$getPostResult[0]['video_id'];
							$postDescription="<div class='ibox float-e-margins'><div class='ibox-content'><figure><iframe width='355' height='189' src='https://www.youtube.com/embed/".$getCode."' frameborder='0' allowfullscreen></iframe></figure></div></div>".$getPostResult[0]['post_description'];
							//$postDescription=$getCode;
							$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['Login_User_Id']."'","","","","");
							
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
									$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
									$username=$getOrg[0]['company_name'];
									$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="Company_Logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="Blogs";
							$blogtype="share-blogs";
							
						} 	

									
						?>	
							
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right"><?php echo $posttype; ?></span></a>
                            
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
                               <?php if(!empty($postDescription)){ echo $postDescription; } ?>
                           <a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>" target="_blank"><span class="label label-info pull-right">VIEW MORE</span></a>
							 <br><br>
							 <!--<button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i> Like this!</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
                                <button class="btn btn-white btn-xs"><i class="fa fa-share"></i> Share</button>-->		
                           <div class="btn-group">	
						   <?php 
							if(check_bloglike($admin_id,$posttype,$postnonencyid,2) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline like<?php echo $postResultList['post_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='liked<?php echo $postResultList['post_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<a target="_blank" href="https://plus.google.com/share?url=<?php echo HOST_MAIN_URL; ?>Refer%2Fshare-blogs%2F<?php echo $getTitle; ?>%2F<?php echo $getPostKey; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button></a>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="#" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-linkedin btn-circle btn-outline" type="button"><i class="fa fa-linkedin"></i> 
                            </button></a>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $getPostResult[0]['post_tittle']."...For more info visit : ".HOST_MAIN_URL."Refer/share-blogs/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>	
														
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOST_MAIN_URL; ?>Refer%2Fshare-blogs%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<!--<div class="social-comment">
									<a href="" class="pull-left">
										<img alt="image" src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "../Partners/partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>">
									</a>
									<div class="media-body">
										<textarea class="form-control" placeholder="Write comment..."></textarea>
									</div>
							</div>-->
							<?php $getComment = mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
							foreach($getComment as $commentList){ 
							if($commentList['login_User_Type']=="1"){  //For Partner
							$getUser = mysqlSelect("partner_name,doc_photo","our_partners","partner_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['partner_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['doc_photo'])){
								$userimg="../Partners/partnerProfilePic/".$commentList['login_id']."/".$getUser[0]['doc_photo']; 
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
								$userimg="Company_Logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
                                    <!--<a href="#" class="small"><i class="fa fa-thumbs-up"></i> 26 Like this!</a> --->
                                    <small class="text-muted"><?php echo timeAgo($commentList['post_date']); ?></small>
                                </div>
                            </div>
							<?php } ?>
							<div id="latestCom"></div>
							<br>
							
							<!-- Comment Section -->
							<div class="form-chat">
					<input type="hidden" name="listingid" id="listingid<?php echo $postResultList['post_id']; ?>" value="<?php echo $postResultList['post_id']; ?>" />
					<input type="hidden" name="posttype" id="posttype<?php echo $postResultList['post_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_id" id="comment_id<?php echo $postResultList['post_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_type" id="user_type" value="2" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txt" id="medical_cmnt_txt<?php echo $postResultList['post_id']; ?>" required="required" class="form-control the-new-com<?php echo $postResultList['post_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-com<?php echo $postResultList['post_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
			
					<script type="text/javascript">
				   $(function(){ 
						
						/* when start writing the comment activate the "add" button */
						$('.the-new-com<?php echo $postResultList['post_id']; ?>').bind('input propertychange', function() {
						   $(".bt-add-com<?php echo $postResultList['post_id']; ?>").css({opacity:0.6});
						   var checklength = $(this).val().length;
						   if(checklength){ $(".bt-add-com<?php echo $postResultList['post_id']; ?>").css({opacity:1}); }
						});

						
						// on post Like click 
						$('.like<?php echo $postResultList['post_id']; ?>').click(function(){
							
							var listingId = $('#listingid');
							var thePostType = $('#posttype<?php echo $postResultList['post_id']; ?>');
							var thePostId = $('#comment_id<?php echo $postResultList['post_id']; ?>');
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
						$('.bt-add-com<?php echo $postResultList['post_id']; ?>').click(function(){
							var theCom = $('.the-new-com<?php echo $postResultList['post_id']; ?>');
							var thePostType = $('#posttype<?php echo $postResultList['post_id']; ?>');
							var thePostId = $('#comment_id<?php echo $postResultList['post_id']; ?>');
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
					
					
					<?php } 
					if($countall[0]['Count_All']>6){
					?>
					
					<div id="loadMore">
					<a href="javascript:void(0)" onclick="return showmoreFeed(1);"><button class="btn btn-primary btn-block s" ><i class="fa fa-arrow-down"></i> Show More</button></a>
					<br>
					</div>
					
					<div id="showMore"></div>
					<?php } 
					
				?>
					</div>
					</div>
				<?php } ?>
                </div>
                <div class="col-lg-4 m-b-lg" style="float:right;">
                    <div id="vertical-timeline" class="vertical-container light-timeline no-margins">
                        <div class="vertical-timeline-block" id="sendLink">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                
                            <h3>Send Appointment Link</h3>

                            <p class="small">
                                Send appointment link to your patient
                            </p>
							
                            <div class="form-group">
                                <label>Email Id</label>
                                <input type="email" name="txtEmail" id="txtEmail" class="form-control" placeholder="Email Id">
                            </div>
                            <div class="form-group">
                                <label>Mobile No.</label>
                                <input type="number" name="txMobile" id="txMobile" class="form-control" placeholder="Mobile No.">
                            </div>
                            <button class="ladda-button ladda-button-demo btn btn-primary bt-send-link demo2"  data-style="zoom-in">Send</button>
							                      
                            </div>
							
							
                        </div>

                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon blue-bg">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <div class="ibox float-e-margins">
                                    <h3>Today's Appointments</h3>
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
											
                                            <tr>
                                                <th>Date</th>
                                                <th>Name</th>
                                               
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php 
											$Today_Appointment = mysqlSelect("a.patient_name as patient_name,b.Timing as Timing","appointment_transaction_detail as a inner join timings as b on b.Timing_id=a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date='".date('Y-m-d')."'","","","","0,3");
											if(empty($Today_Appointment)){
											?>
											<tr>
											<td colspan="2">No appointments</td>
											</tr>
											<?php } else {
												foreach($Today_Appointment as $appList){
												?>
                                            <tr>
                                               <td><i class="fa fa-clock-o"></i> <?php echo $appList['Timing']; ?></td>
                                                <td><?php echo $appList['patient_name']; ?></td>
                                            </tr>
												<?php }
												 ?>
                                          
                                             <?php } ?>
											</tbody>
                                        </table><br>
										
                                   
									</div>
                                </div>
                            </div>
                        </div>

                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon blue-bg">
                                <i class="fa fa-wheelchair"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <div class="ibox float-e-margins">
                                    <h3>My Patients</h3>
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Contact</th>
                                               
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php $myPatient = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","a.patient_id desc","","","0,3");
											if(empty($myPatient)){ ?>
											<tr><td colspan="2">No record found</td>
											<?php } else {
											foreach($myPatient as $list){
											?>
                                            <tr>
                                              
                                                <td><?php echo $list['patient_name'];  ?></td>
												 <td><i class="fa fa-mobile"></i> <?php echo $list['patient_mob'];  ?></td>
                                            </tr>
											<?php } 
											}?>
                                           
                                            </tbody>
                                        </table><br>
										<a href="My-Patients"><span class="label label-info pull-right">VIEW MORE</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon blue-bg">
                                <i class="fa fa-user"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <div class="ibox float-e-margins">
                                    <h3>Cases Received</h3>
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                               
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php $casesReceived = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","b.ref_id='".$admin_id."'","a.patient_id desc","","","0,3");
											if(empty($casesReceived)){ ?>
											<tr><td colspan="2">No record found</td>
											<?php } else {
											foreach($casesReceived as $list){ 
										
										$refDoctors = mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.bucket_status as Bucket_Status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$list['Patient_Id']."'","","","","");
										$getCurrentStatus = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");
                      
										if($refDoctors[0]['Bucket_Status']=="2"){ $patient_status="<span class='label label-warning'>REFERRED</span>"; ?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; }
											
										?>
											
                                            <tr>
                                              
                                                <td><?php echo $refDoctors[0]['Patient_Name']; ?></td>
												 <td><?php echo $patient_status; ?></td>
                                                </tr>
											<?php } 
											}?>
                                            </tbody>
                                        </table><br>
										<a href="Cases-Sent"><span class="label label-info pull-right">VIEW MORE</span></a>
                                    </div>
                                </div>
                            </div>
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
