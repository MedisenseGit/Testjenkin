<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

include('connect.php');
include('functions.php');
//$getClientIp=$_SERVER['REMOTE_ADDR'];


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}


$countConnection = $objQuery->mysqlSelect("COUNT(ref_id) as Count_Connections","referal","anonymous_status!=1","","","","");
	
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome to Practice</title>

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

<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="../assets/release/chariot.css" rel="stylesheet" type="text/css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="../assets/js/scroll_up.js"></script>


<style>

.scrollToTop{
	width:100px; 
	height:130px;
	padding:10px; 
	text-align:center; 
	background: whiteSmoke;
	font-weight: bold;
	color: #444;
	text-decoration: none;
	position:fixed;
	bottom:55px;
	right:40px;
	display:none;
	background: url('arrow_up.png') no-repeat 0px 20px;
}
.scrollToTop:hover{
	text-decoration:none;
}

</style>
</head>

<body>

    <div id="wrapper">
	<!-- Side Menu -->
    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
		<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Home</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Home</strong>
                        </li>
                      
                    </ol>
					
                </div>
                <!--<div class="col-lg-2 mgTop">
					<a href="http://lms1.bmj.com/html3/bmjindia/cep/bjo" target="_blank"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-newspaper-o"></i> Journal Access</button></a>
                                
			   </div>-->
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row m-b-lg m-t-lg">
							<div>
							<?php if($_GET['response']=="appointment-success") {  ?>
							<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <i class="fa fa-check"></i><a class="alert-link" href="#">Appointment Link Sent Successfully </a>.
                            </div>
							<?php } if($_GET['response']=="appointment-failure") {  ?>
							<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">Error!!!</a> appointment link has not sent.
                            </div>
							<?php } ?>									
							</div>
                <div class="col-md-5">
					<div class="pull-right"><a href="Profile"><i class="fa fa-pencil-square-o"></i> Edit</a></div>
                    <div class="profile-image">
                        <img src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>" class="img-circle circle-border m-b-md" width="128" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $getPartner[0]['contact_person']; ?>
                                </h2>
                                <h4><?php echo $getPartner[0]['location'].", ".$getPartner[0]['state'].", ".$getPartner[0]['country']; ?></h4>
                                <small>
                                    <i class="fa fa-mobile"></i> <?php echo $getPartner[0]['cont_num1']; ?>,<br> <i class="fa fa-envelope"></i> <?php echo $getPartner[0]['Email_id']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
				 <div class="col-md-3">
				 </div>
                <div class="col-md-4">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            
                            <td>
                                <a href="Appointments"><strong><span class="label label-success"><?php echo $Total_Appointment_Count[0]['count']; ?></span></strong> APPOINTMENTS</a>
                            </td>
							<td>
                                <a href="My-Connections"><strong><span class="label label-danger"><?php echo $countConnection[0]['Count_Connections']; ?></span></strong> CONNECTIONS</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
								<a href="My-Patients"><strong><span class="label label-success"><?php echo $countMyPatient[0]['Total_count']; ?></span></strong> MY PATIENTS</a>
                              <!--  <strong><span class="label label-primary"><?php echo $countMyPatient[0]['Total_count']; ?></span></strong> MY PATIENTS -->
                            </td>
							 <td>
                                <a href="Cases-Sent"><strong><span class="label label-default"><?php echo $getCasesSent[0]['Count']; ?></span></strong> CASES SENT</a>
                            </td>
                            <!--<td>
                                <a href="My-Connections"><strong><span class="label label-danger"><?php echo $countConnection[0]['Count_Connections']; ?></span></strong> CONNECTIONS</a>
                            </td> -->
                        </tr>
                        <tr>
                          <!--  <td>
                                <a href="Cases-Sent"><strong><span class="label label-default"><?php echo $getCasesSent[0]['Count']; ?></span></strong> CASES SENT</a>
                            </td>
                           <!-- <td>
                                <strong><span class="label label-success">0</span></strong> INVITE SENT
                            </td> -->
                        </tr>
                        </tbody>
                    </table>
                </div>
             


            </div>
            <div class="row">
				
                <div class="col-lg-3">

                    <!--<div class="ibox">
                        <div class="ibox-content">
                                <h3>About Alex Smith</h3>

                            <p class="small">
                                There are many variations of passages of Lorem Ipsum available, but the majority have
                                suffered alteration in some form, by injected humour, or randomised words which don't.
                                <br/>
                                <br/>
                                If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't
                                anything embarrassing
                            </p>

                            <p class="small font-bold">
                                <span><i class="fa fa-circle text-navy"></i> Online status</span>
                                </p>

                        </div>
                    </div>-->

                    <div class="ibox">
                        <div class="ibox-content">
                            <h3>My Connections</h3>
                            <!--<p class="small">
                                If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't
                                anything embarrassing
                            </p>-->
                            <div class="user-friends">
							<?php $getConnections = $objQuery->mysqlSelect("ref_id,doc_photo","referal","doc_spec!=555 and anonymous_status!=1","doc_type_val asc","","","0,16"); 
								foreach($getConnections as $ListDoc){ 
								if(!empty($ListDoc['doc_photo'])){
							?>
                                <a href=""><img alt="image" class="img-circle" src="https://medisensecrm.com/Doc/<?php echo $ListDoc['ref_id']; ?>/<?php echo $ListDoc['doc_photo']; ?>"></a>
								<?php }
								else { ?>
								 <a href=""><img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png"></a>
							<?php 
								}
								}
								?>
								<a href="My-Connections"><span class="label label-info pull-right">VIEW MORE</span></a>
								
                            </div>
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-content">
                            <h3>About Practice</h3>
                            <div class="slick_demo_3">
                            <div>
                                
                                   <img src="images/1.png" />
                                   
                               
                            </div>
                            <div>
                                
                                     <img src="images/2.png" />
                               
                            </div>
							<div>
                                
                                     <img src="images/3.png" />
                               
                            </div>
							<div>
                                
                                     <img src="images/4.png" />
                               
                            </div>
                           
                        </div>
						<a href="http://medisensepractice.com/" target="_blank"><span class="label label-info pull-right">VIEW MORE</span></a>
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-content">
                            <h3>How It Works</h3>
							<iframe width="210" height="150" src="https://www.youtube.com/embed/j4qW_KiDh2I" frameborder="0"  allowfullscreen></iframe>
                         </div>
                    </div>

                </div>
				<div class="col-lg-5" id="feedSection">
				<div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-1" data-toggle="tab">All</a></li>
                                            <li class=""><a href="#tab-2" data-toggle="tab">Blogs</a></li>
											 <li class=""><a href="#tab-3" data-toggle="tab">CME</a></li>
											 <li class=""><a href="#tab-4" data-toggle="tab">Videos</a></li>
											 <li class=""><a href="#tab-5" data-toggle="tab">Jobs</a></li>
                                        </ul>
                                    </div>
                                </div>
					<div class="tab-content">			
					<div id="tab-1" class="tab-pane active">
					
					<?php 
					$allResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","","Create_Date desc","","","0,6");
					$countall  = $objQuery->mysqlSelect("COUNT(listing_id) as Count_All","blogs_offers_events_listing","","Create_Date desc","","","0,6");
					foreach($allResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Blog"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../premium/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="Blogs";
							$blogtype="share-blogs";
							
						} else if($postResultList['listing_type']=="Surgical"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$gethypenTitle= hyphenize($getPostResult[0]['post_tittle']);
							$cat_type="2";
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postkey=$getPostResult[0]['postkey'];
							$getCode = $getPostResult[0]['video_id'];
							//$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $getPostResult[0]['video_url']);
							$postDescription="<div class='ibox float-e-margins'><div class='ibox-content'><figure><iframe width='355' height='189' src='https://www.youtube.com/embed/".$getCode."' frameborder='0' allowfullscreen></iframe></figure></div></div>";
							//$postDescription=$getCode;
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../premium/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="Blogs";
							$blogtype="share-blogs";
							
						} else if($postResultList['listing_type']=="Offers"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
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
							
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
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
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../premium/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$blogtype="share-post";
							
						} else if($postResultList['listing_type']=="Events"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
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
							
								$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
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
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../premium/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$blogtype="share-post";
							
						}	else if($postResultList['listing_type']=="Jobs"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
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
							
								$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
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
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs,company_logo","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof="";
									//Profile Pic
									if(!empty($getOrg[0]['company_logo'])){
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
								
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../premium/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
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
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : http://pixeleyecare.com/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpixeleyecare.com%2F<?php echo $blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<!--<div class="social-comment">
									<a href="" class="pull-left">
										<img alt="image" src="<?php if(!empty($getPartner[0]['doc_photo'])){ echo "partnerProfilePic/".$getPartner[0]['partner_id']."/".$getPartner[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>">
									</a>
									<div class="media-body">
										<textarea class="form-control" placeholder="Write comment..."></textarea>
									</div>
							</div>-->
							<?php $getComment = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
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
							else if($commentList['login_User_Type']=="3"){  //For premium
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../premium/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
					<input type="hidden" name="user_type" id="user_type" value="1" />
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
					<?php } ?>
                   </div>
				   
				    <!--Begin Blogs tab -->
				   <div id="tab-2" class="tab-pane">
					<?php 
					$blogResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='Blog'","Create_Date desc","","","0,5");
					$blogcount = $objQuery->mysqlSelect("COUNT(listing_id) as CountBlog","blogs_offers_events_listing","listing_type='Blog'","Create_Date desc","","","");
					
					foreach($blogResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Blog"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['post_description']),0,400)."</p>";
							
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
													
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../premium/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="Blogs";
							$icon="images/blogs.png";
						} 	

									
						?>	
						
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right"><?php echo $posttype; ?></span></a>
                            
                        </div>
                        <div class="social-avatar">
                            <a href="" class="pull-left">
                                <img alt="image" src="<?php echo $userimg; ?>">
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

                            <div class="btn-group">	
						   <?php 
							if(check_bloglike($admin_id,$posttype,$postnonencyid,1) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline likeBlog<?php echo $postResultList['listing_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='likedBlog<?php echo $postResultList['listing_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : http://pixeleyecare.com/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpixeleyecare.com%2F<?php echo $blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<?php $getComment = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
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
							else if($commentList['login_User_Type']=="3"){  //For premium
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../premium/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
								
								<div class="form-chat">
								<input type="hidden" name="listingidBlog" id="listingidBlog<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postResultList['listing_id']; ?>" />
					<input type="hidden" name="posttypeBlog" id="posttypeBlog<?php echo $postResultList['listing_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_idBlog" id="comment_idBlog<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_idBlog" id="user_idBlog" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_typeBlg" id="user_typeBlog" value="1" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txtBlog" id="medical_cmnt_txtBlog<?php echo $postResultList['listing_id']; ?>" required="required" class="form-control the-new-comBlog<?php echo $postResultList['listing_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-comBlog<?php echo $postResultList['listing_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
                       <script type="text/javascript">
				   $(function(){ 
						
						
						// on post Like click 
						$('.likeBlog<?php echo $postResultList['listing_id']; ?>').click(function(){
							
							var listingId = $('#listingidBlog');
							var thePostType = $('#posttypeBlog<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idBlog<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idBlog');
							var theUserType = $('#user_typeBlog');

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
						$('.bt-add-comBlog<?php echo $postResultList['listing_id']; ?>').click(function(){
							var theCom = $('.the-new-comBlog<?php echo $postResultList['listing_id']; ?>');
							var thePostType = $('#posttypeBlog<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idBlog<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idBlog');
							var theUserType = $('#user_typeBlog');

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
					if($blogcount[0]['CountBlog']>5){
					?>
					
					<div id="loadMoreBlogs">
					<a href="javascript:void(0)" onclick="return showmoreBlogs(1);"><button class="btn btn-primary btn-block s" ><i class="fa fa-arrow-down"></i> Show More</button></a>
					<br>
					</div>
					
					<div id="showmoreBlogs"></div>
					<?php } ?>
				   </div>
				    <!--End of Blogs tab -->
					
				    <!--Begin Events tab -->
				   <div id="tab-3" class="tab-pane">
					<?php 
					$blogResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='Events'","Create_Date desc","","","0,5");
					$eventcount = $objQuery->mysqlSelect("COUNT(listing_id) as CountEvent","blogs_offers_events_listing","listing_type='Events'","Create_Date desc","","","");
					
					foreach($blogResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Events"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$postnonencyid=$getPostResult[0]['event_id'];
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['description']),0,400)."</p>";
							
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){
								
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								//Profile Pic
									if(!empty($getDocName[0]['Prof_pic'])){
									$userimg="../Doc/".$getPostResult[0]['oganiser_doc_id']."/".$getDocName[0]['Prof_pic']; 
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
								
							
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../premium/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="Offers";
							$icon="images/blogs.png";
						} 	

									
						?>	
						
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right"><?php echo $posttype; ?></span></a>
                            
                        </div>
                        <div class="social-avatar">
                            <a href="" class="pull-left">
                                <img alt="image" src="<?php echo $userimg; ?>">
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

                            <div class="btn-group">	
						   <?php 
							if(check_bloglike($admin_id,$posttype,$postnonencyid,1) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline likeEvent<?php echo $postResultList['listing_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='likedEvent<?php echo $postResultList['listing_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : http://pixeleyecare.com/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpixeleyecare.com%2F<?php echo $blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<?php $getComment = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
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
							else if($commentList['login_User_Type']=="3"){  //For premium
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../premium/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
								
								<div class="form-chat">
								<input type="hidden" name="listingidEvent" id="listingidEvent<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postResultList['listing_id']; ?>" />
					<input type="hidden" name="posttypeEvent" id="posttypeEvent<?php echo $postResultList['listing_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_idEvent" id="comment_idEvent<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_idEvent" id="user_idEvent" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_typeEvent" id="user_typeEvent" value="1" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txtBlog" id="medical_cmnt_txtEvent<?php echo $postResultList['listing_id']; ?>" required="required" class="form-control the-new-comEvent<?php echo $postResultList['listing_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-comEvent<?php echo $postResultList['listing_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
                       <script type="text/javascript">
				   $(function(){ 
						
						
						// on post Like click 
						$('.likeEvent<?php echo $postResultList['listing_id']; ?>').click(function(){
							
							var listingId = $('#listingidEvent');
							var thePostType = $('#posttypeEvent<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idEvent<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idEvent');
							var theUserType = $('#user_typeEvent');

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
						$('.bt-add-comEvent<?php echo $postResultList['listing_id']; ?>').click(function(){
							var theCom = $('.the-new-comEvent<?php echo $postResultList['listing_id']; ?>');
							var thePostType = $('#posttypeEvent<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idEvent<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idEvent');
							var theUserType = $('#user_typeEvent');

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
					if($eventcount[0]['CountEvent']>5){
					?>
					
					<div id="loadMoreEvents">
					<a href="javascript:void(0)" onclick="return showMoreEvents(1);"><button class="btn btn-primary btn-block s" ><i class="fa fa-arrow-down"></i> Show More</button></a>
					<br>
					</div>
					
					<div id="showMoreEvents"></div>
					<?php } ?>
				   </div>
				    <!--End of Events tab -->
					
				   <!--Begin Surgical Video tab -->
				   <div id="tab-4" class="tab-pane">
					<?php 
					$blogResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='Surgical'","Create_Date desc","","","0,5");
					$countvideo = $objQuery->mysqlSelect("COUNT(listing_id) as CountVideo","blogs_offers_events_listing","listing_type='Surgical'","Create_Date desc","","","0,5");
					
					foreach($blogResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Surgical"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$postnonencyid=$getPostResult[0]['post_id'];
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							//$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $getPostResult[0]['video_url']);
							$getCode=$getPostResult[0]['video_id'];
							$postDescription="<div class='ibox float-e-margins'><div class='ibox-content'><figure><iframe width='355' height='189' src='https://www.youtube.com/embed/".$getCode."' frameborder='0' allowfullscreen></iframe></figure></div></div>";
							//$postDescription=$getCode;
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../premium/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="blogs.php";
							$icon="images/blogs.png";
						} 	

									
						?>	
						
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right"><?php echo $posttype; ?></span></a>
                            
                        </div>
                        <div class="social-avatar">
                            <a href="" class="pull-left">
                                <img alt="image" src="<?php echo $userimg; ?>">
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

                            <div class="btn-group">	
						   <?php 
							if(check_bloglike($admin_id,$posttype,$postnonencyid,1) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline likeSurg<?php echo $postResultList['listing_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='likedSurg<?php echo $postResultList['listing_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : http://pixeleyecare.com/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpixeleyecare.com%2F<?php echo $blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<?php $getComment = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
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
							else if($commentList['login_User_Type']=="3"){  //For premium
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../premium/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
								
								<div class="form-chat">
								<input type="hidden" name="listingidSurg" id="listingidSurg<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postResultList['listing_id']; ?>" />
					<input type="hidden" name="posttypeSurg" id="posttypeSurg<?php echo $postResultList['listing_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_idSurg" id="comment_idSurg<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_idSurg" id="user_idSurg" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_typeSurg" id="user_typeSurg" value="1" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txtSurg" id="medical_cmnt_txtSurg<?php echo $postResultList['listing_id']; ?>" required="required" class="form-control the-new-comSurg<?php echo $postResultList['listing_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-comSurg<?php echo $postResultList['listing_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
                       <script type="text/javascript">
				   $(function(){ 
						
						
						// on post Like click 
						$('.likeSurg<?php echo $postResultList['listing_id']; ?>').click(function(){
							
							var listingId = $('#listingidSurg');
							var thePostType = $('#posttypeSurg<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idSurg<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idSurg');
							var theUserType = $('#user_typeSurg');

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
						$('.bt-add-comSurg<?php echo $postResultList['listing_id']; ?>').click(function(){
							var theCom = $('.the-new-comSurg<?php echo $postResultList['listing_id']; ?>');
							var thePostType = $('#posttypeSurg<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idSurg<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idSurg');
							var theUserType = $('#user_typeSurg');

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
					if($countvideo[0]['CountVideo']>5){
					?>
					
					<div id="loadMoreVideo">
					<a href="javascript:void(0)" onclick="return showMoreVideo(1);"><button class="btn btn-primary btn-block s" ><i class="fa fa-arrow-down"></i> Show More</button></a>
					<br>
					</div>
					
					<div id="showMoreVideo"></div>
					<?php } ?>
                   </div>
				 <!--End of Surgical Video tab -->
					
					<!--Begin Jobs tab -->
				   <div id="tab-5" class="tab-pane">
					<?php 
					$blogResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='Jobs'","Create_Date desc","","","0,5");
					$countjobs = $objQuery->mysqlSelect("COUNT(listing_id) as CountJobs","blogs_offers_events_listing","listing_type='Jobs'","Create_Date desc","","","0,5");
					
					foreach($blogResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Jobs"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$postnonencyid=$getPostResult[0]['event_id'];
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription="<p>".substr(strip_tags($getPostResult[0]['description']),0,400)."</p>";
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
									$userimg="../premium/company_logo/".$getPostResult[0]['company_id']."/".$getOrg[0]['company_logo']; 
									}else{
										$userimg="../assets/img/anonymous-profile.png";
									}
								}
							
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../premium/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							
							$url="Offers";
							$icon="images/blogs.png";
						} 	

									
						?>	
						
                    <div class="social-feed-box">

                        <div class="pull-right social-action dropdown">
                            <a href="#"><span class="label label-info pull-right"><?php echo $posttype; ?></span></a>
                            
                        </div>
                        <div class="social-avatar">
                            <a href="" class="pull-left">
                                <img alt="image" src="<?php echo $userimg; ?>">
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

                            <div class="btn-group">	
						   <?php 
							if(check_bloglike($admin_id,$posttype,$postnonencyid,1) == 0) { ?>
													
							<button class="btn btn-danger btn-circle btn-outline likeJob<?php echo $postResultList['listing_id']; ?>"  type="submit"><i class="fa fa-heart"></i> 
                            </button>
                               
                           	<?php } else { ?>
							
							<a href='javascript:void();' class='likedJob<?php echo $postResultList['listing_id']; ?>' data-toggle='tooltip' data-placement='bottom' title='Like'><button class="btn btn-danger btn-circle" type="submit"><i class="fa fa-heart"></i>
                            </button></a>
                           
							<?php } ?>
							</div><code><?php echo bloglike($postnonencyid,$posttype); ?> likes</code>
							
							 <div class="btn-group pull-right">
							<button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-google"></i> 
                            </button>
							</div>
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo $posttitle."...For more info visit : http://pixeleyecare.com/".$blogtype."/".$gethypenTitle."/".$postkey; ?>"  data-size="small"><button class="btn btn-info btn-circle btn-outline" type="button"><i class="fa fa-twitter"></i> 
                            </button></a>
							</div>							
							<div class="btn-group pull-right m-r-xs">
							<a target="_blank" class="fb-xfbml-parse-ignore" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpixeleyecare.com%2F<?php echo $blogtype; ?>%2F<?php echo $gethypenTitle; ?>%2F<?php echo $postkey; ?>" data-layout="button_count" data-size="small" data-mobile-iframe="true"><button class="btn btn-success btn-circle btn-outline" type="button"><i class="fa fa-facebook"></i> 
                            </button></a>
							</div>
                        </div>
                        <div class="social-footer">
							<?php $getComment = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postnonencyid."' and topic_type='".$posttype."'","","","","");
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
							else if($commentList['login_User_Type']=="3"){  //For premium
							$getUser = $objQuery->mysqlSelect("company_name,company_logo","compny_tab","company_id='".$commentList['login_id']."'","","","","");
							$userName=$getUser[0]['company_name'];
							
								//Profile Pic
								if(!empty($getUser[0]['company_logo'])){
								$userimg="../premium/company_logo/".$commentList['login_id']."/".$getUser[0]['company_logo']; 
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
								
								<div class="form-chat">
								<input type="hidden" name="listingidJob" id="listingidJob<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postResultList['listing_id']; ?>" />
					<input type="hidden" name="posttypeJob" id="posttypeJob<?php echo $postResultList['listing_id']; ?>" value="<?php echo $posttype; ?>" />
					<input type="hidden" name="comment_idJob" id="comment_idJob<?php echo $postResultList['listing_id']; ?>" value="<?php echo $postnonencyid; ?>" />
					<input type="hidden" name="user_idJob" id="user_idJob" value="<?php echo $admin_id; ?>" />
					<input type="hidden" name="user_typeJob" id="user_typeJob" value="1" />
                <div class="input-group input-group-sm">
				
                    <textarea name="medical_cmnt_txtJob" id="medical_cmnt_txtJob<?php echo $postResultList['listing_id']; ?>" required="required" class="form-control the-new-comJob<?php echo $postResultList['listing_id']; ?>"></textarea>
                    <span class="input-group-btn"> <button  class="ladda-button btn btn-primary bt-add-comJob<?php echo $postResultList['listing_id']; ?>" data-style="zoom-in" type="submit">Comment
                </button> </span></div>
            </div>
                       <script type="text/javascript">
				   $(function(){ 
						
						
						// on post Like click 
						$('.likeJob<?php echo $postResultList['listing_id']; ?>').click(function(){
							
							var listingId = $('#listingidJob');
							var thePostType = $('#posttypeJob<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idJob<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idJob');
							var theUserType = $('#user_typeJob');

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
						$('.bt-add-comJob<?php echo $postResultList['listing_id']; ?>').click(function(){
							var theCom = $('.the-new-comJob<?php echo $postResultList['listing_id']; ?>');
							var thePostType = $('#posttypeJob<?php echo $postResultList['listing_id']; ?>');
							var thePostId = $('#comment_idJob<?php echo $postResultList['listing_id']; ?>');
							var theUserId = $('#user_idJob');
							var theUserType = $('#user_typeJob');

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
					
					if($countjobs[0]['CountJobs']>5){
					?>
					
					<div id="showMoreJobs">
					<a href="javascript:void(0)" onclick="return showMoreJobs(1);"><button class="btn btn-primary btn-block s" ><i class="fa fa-arrow-down"></i> Show More</button></a>
					<br>
					</div>
					
					<div id="showMoreJobs"></div>
					<?php } ?>
					
                   </div>
				 <!--End of Jobs tab -->
					
					
					</div>
					<!-- End of tab panel -->

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
							<?php if(COUNT($chkProfileTime)==0){ ?>
							<button class="ladda-button ladda-button-demo btn btn-primary errorshare demo2"  data-style="zoom-in">Send</button>
							<?php } else {?>
                            <button class="ladda-button ladda-button-demo btn btn-primary bt-send-link demo2"  data-style="zoom-in">Send</button>
							<?php } ?>                 
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
											$Today_Appointment = $objQuery->mysqlSelect("a.patient_name as patient_name,c.Timing as Timing","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id inner join timings as c on c.Timing_id=a.Visiting_time","a.pref_doc='".$admin_id."' and a.Visiting_date='".date('Y-m-d')."'","","","","0,3");
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
											<?php $myPatient = $objQuery->mysqlSelect("patient_name,patient_email,patient_mob","my_patient","partner_id='".$admin_id."'","patient_id desc","","","0,3");
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
                                    <h3>Cases Sent</h3>
                                    <div class="ibox-content">
                                        <table class="table table-hover no-margins">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                               
                                            </tr>
                                            </thead>
                                            <tbody>
											<?php $casesSent = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","a.patient_id desc","","","0,3");
											if(empty($casesSent)){ ?>
											<tr><td colspan="2">No record found</td>
											<?php } else {
											foreach($casesSent as $list){ 
										
										$refDoctors = $objQuery->mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.bucket_status as Bucket_Status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$list['Patient_Id']."'","","","","");
										$getCurrentStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['patient_id']."'","","","","");
                      
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

                       <!-- <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon yellow-bg">
                                <i class="fa fa-phone"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <h2>Phone with Jeronimo</h2>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde? Iste voluptatibus minus veritatis qui ut.</p>
                                <span class="vertical-date">Yesterday <br><small>Dec 23</small></span>
                            </div>
                        </div>

                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-comments"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <h2>Chat with Monica and Sandra</h2>
                                <p>Web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like). </p>
                                <span class="vertical-date">Yesterday <br><small>Dec 23</small></span>
                            </div>
                        </div>-->
                    </div>

                </div>

            </div>

        </div>
		<a href="#" class="scrollToTop"><h1 class="f-xs text-navy"><i class="fa fa-arrow-circle-up"></i></h1> </a>
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
 <!-- Bootstrap Tour -->
    <script src="../assets/js/plugins/bootstrapTour/bootstrap-tour.min.js"></script>
<script>

    $(document).ready(function (){

        // Instance the tour
        var tour = new Tour({
            steps: [{

                    element: "#myPatient",
                    title: "My Patients",
                    content: "<i class='fa fa-info-circle'></i> Here you can add patient details, visit details, prescriptions, follow up visit date etc.<br><br><i class='fa fa-lightbulb-o'></i> Try one click prescription by creating your own template.",
                    placement: "right"
                },
                {

                    element: "#myAppointment",
                    title: "My Appointments",
                    content: "<i class='fa fa-info-circle'></i> Here you can view (Or add)  all the appointments requested by your patients. Upon every booking/reschedule/Cancel patient will be sent an SMS/Email.<br><br><i class='fa fa-lightbulb-o'></i> Try sending your own appointment link to patients just by entering mobile number or email ID of the patient.",
                    placement: "right"
                },
                {

                    element: "#casesSent",
                    title: "Cases Sent",
                    content: "<i class='fa fa-info-circle'></i> Here you could be for an appointment or simply referring a case to an expert for his expertise. You will have to mention patient details for these requests. Along with your request you will see all the responses from the experts. <br><br><i class='fa fa-lightbulb-o'></i> Open a request submitted by you. Try sending the same case to more doctors. When they respond you will be notified.",
                    placement: "right"
                },
                {

                    element: "#feedSection",
                    title: "Feed",
                    content: "<i class='fa fa-info-circle'></i> Here you will find industry updates, blogs from experts, Conference invites, Job opportunities. <br><br><i class='fa fa-lightbulb-o'></i> Try accepting an invite, like or commenting on a blog, applying for a job.",
                    placement: "top"
                },
                {

                    element: "#sendLink",
                    title: "Send Appointment Link",
                    content: "<i class='fa fa-info-circle'></i> Here you can send your own appointment link to patients just by entering mobile number or email ID of the patient.",
                    placement: "top"
                }
            ]});

        // Initialize the tour
        tour.init();

        $('.startTour').click(function(){
            tour.restart();

            // Start the tour
            // tour.start();
        })

    });

</script>
<!-- Sweet alert -->
<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>

</body>

</html>
