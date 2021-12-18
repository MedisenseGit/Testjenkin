<?php
ob_start();
error_reporting(0); 
session_start();

$doc_id=$_GET['doc_id'];

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");

include('connect.php');
include('functions.php');	

					$allResult2 = mysqlSelect("*","blogs_offers_events_listing","","Create_Date desc","","","7,60");
					foreach($allResult2 as $postResultList){
						
							/*$commentCount = mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Blog"){
							$getPostResult = mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$gethypenTitle= hyphenize($getPostResult[0]['post_tittle']);
							$cat_type="1";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
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
									$userimg="company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
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
							
						} else if($postResultList['listing_type']=="Surgical"){
							$getPostResult = mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$gethypenTitle= hyphenize($getPostResult[0]['post_tittle']);
							$cat_type="2";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['postkey'];
							//$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $getPostResult[0]['video_url']);
							$getCode=$getPostResult[0]['video_id'];
							$postDescription="<div class='ibox float-e-margins'><div class='ibox-content'><figure><iframe width='355' height='189' src='https://www.youtube.com/embed/".$getCode."' frameborder='0' allowfullscreen></iframe></figure></div></div>";
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
									$userimg="company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
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
							
						} else if($postResultList['listing_type']=="Offers"){
							$getPostResult = mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$postnonencyid=$getPostResult[0]['event_id'];
							$cat_type="5";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$gethypenTitle= hyphenize($getPostResult[0]['title']);
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['event_key'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['description']),0,400)."</p>";
							
							$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$getPostResult[0]['Login_User_Id']."/".$getDocName[0]['Prof_pic']; 
									}else{
									$userimg="../assets/img/anonymous-profile.png";
									}
								} else{
								$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$blogtype="share-post";
							
						} else if($postResultList['listing_type']=="Events"){
							$getPostResult = mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$postnonencyid=$getPostResult[0]['event_id'];
							$cat_type="3";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$gethypenTitle= hyphenize($getPostResult[0]['title']);
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['event_key'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['description']),0,400)."</p>";
							
								$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
									//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$getPostResult[0]['Login_User_Id']."/".$getDocName[0]['Prof_pic']; 
									}else{
									$userimg="../assets/img/anonymous-profile.png";
									}
								} else{
								$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$blogtype="share-post";
							
						}	else if($postResultList['listing_type']=="Jobs"){
							$getPostResult = mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$postnonencyid=$getPostResult[0]['event_id'];
							$cat_type="4";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$gethypenTitle= hyphenize($getPostResult[0]['title']);
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['event_key'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['description']),0,400)."</p>";
							
								$getDocName = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
									//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$getPostResult[0]['Login_User_Id']."/".$getDocName[0]['Prof_pic']; 
									}else{
									$userimg="../assets/img/anonymous-profile.png";
									}
								} else{
								$getOrg = mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof="";
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$blogtype="share-post";
							
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
							if(check_bloglike($admin_id,$posttype,$postnonencyid,1) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline like<?php echo $postResultList['listing_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='liked<?php echo $postResultList['listing_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : ".HOST_MAIN_URL."Refer/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo HOST_MAIN_URL."Refer/".$blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
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
                                    <!--<a href="#" class="small"><i class="fa fa-thumbs-up"></i> 26 Like this!</a> --->
                                    <small class="text-muted"><?php echo timeAgo($commentList['post_date']); ?></small>
                                </div>
                            </div>
							<?php } ?>
							<div id="latestCom"></div>
							<br>
							
							<!-- Comment Section -->
							<div class="form-chat">
					<input type="hidden" name="listingid" id="listingid<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postResultList['listing_id']; ?>" />
					<input type="hidden" name="posttype" id="posttype<?php echo $postResultList['listing_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_id" id="comment_id<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_id" id="user_id" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_type" id="user_type" value="2" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txt" id="medical_cmnt_txt<?php echo $postResultList['listing_id']; ?>" required="required" class="form-control the-new-com<?php echo $postResultList['listing_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-com<?php echo $postResultList['listing_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
					<script type="text/javascript">
				   $(function(){ 
						
						/* when start writing the comment activate the "add" button */
						$('.the-new-com<?php echo $postResultList['listing_id']; ?>').bind('input propertychange', function() {
						   $(".bt-add-com<?php echo $postResultList['listing_id']; ?>").css({opacity:0.6});
						   var checklength = $(this).val().length;
						   if(checklength){ $(".bt-add-com<?php echo $postResultList['listing_id']; ?>").css({opacity:1}); }
						});

						
						// on post Like click 
						$('.like<?php echo $postResultList['listing_id']; ?>').click(function(){
							
							var listingId = $('#listingid');
							var thePostType = $('#posttype<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_id<?php echo $postResultList['listing_id']; ?>');
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
						$('.bt-add-com<?php echo $postResultList['listing_id']; ?>').click(function(){
							var theCom = $('.the-new-com<?php echo $postResultList['listing_id']; ?>');
							var thePostType = $('#posttype<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_id<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_id');
							var theUserType = $('#user_type');

							if( !theCom.val()){ 
								alert('You need to write a comment!'); 
								document.getElementById("latestCom").innerHTML = "";
								return;
							}else{ 
								$.ajax({
									type: "POST",
									url: "add_comments.php",
									data: 'act=add-com&postCom='+theCom.val()+'&postType='+thePostType.val()+'&postId='+thePostId.val()+'&userId='+theUserId.val()+'&userType='+theUserType.val(),
									success: function(html){
										theCom.val('');
									alert('Your comment has been posted successfully');
									}  
								});
							}
						});

					});
				</script>	
                        

                        </div>

                    </div>
					
					
					<?php } ?>
<!-- Sweet alert -->
<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
