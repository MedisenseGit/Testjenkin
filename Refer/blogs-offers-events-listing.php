<?php
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$getClientIp=$_SERVER['REMOTE_ADDR'];


$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:index.php");
}

if(isset($_GET['s'])){

//$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type='".$_GET['s']."'","a.listing_id desc","","","");
$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='".$_GET['s']."'","Create_Date desc","","","0,20");

}else{
			
//$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."'","a.listing_id desc","","","");
$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","","Create_Date desc","","","0,20");

}
//$countBlog = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Blog","","","","");
//$countOffer = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Offers","","","","");
//$countEvent = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$admin_id."' and a.listing_type=Events","","","","");
$countBlog = $objQuery->mysqlSelect("COUNT(listing_id) as Count","blogs_offers_events_listing","listing_type=Blog","","","","");
$countOffer = $objQuery->mysqlSelect("COUNT(listing_id) as Count","blogs_offers_events_listing","listing_type=Offers","","","","");
$countEvent = $objQuery->mysqlSelect("COUNT(listing_id) as Count","blogs_offers_events_listing","listing_type=Events","","","","");


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
$get_pro = $objQuery->mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(c.company_id)='".$admin_id."'","a.Tot_responded desc","","","");

$checkFirstLogin = $objQuery->mysqlSelect("doc_id","practice_login_tracker","doc_id='".$admin_id."' and type=1","","","","");


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
	
	
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="release/chariot.css" rel="stylesheet" type="text/css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="js/scroll_up.js"></script>


<style>

.chariot-tooltip {
  background-color: #fff;
  padding: 30px;
  width: 320px;
  text-align: center;
  box-shadow: 0 0 5px 0 rgba(31, 28, 28, 0.3);
  border: 1px solid #ddd;
  color: #999;
}

.chariot-tooltip .chariot-tooltip-icon {
  width: 52px;
  height: 52px;
  margin: auto;
}

.chariot-tooltip .chariot-tooltip-icon img {
  width: 52px;
  height: 52px;
}

.chariot-tooltip .chariot-tooltip-header {
  font-size: 18px;
  line-height: 18px;
  font-weight: 500;
  color: #555;
  padding: 5px 0;
}

.chariot-tooltip .chariot-tooltip-content { padding: 5px 0; }

.chariot-tooltip .chariot-tooltip-content p {
  font-size: 14px;
  font-weight: 300;
  color: #999;
  padding-bottom: 15px;
}

.chariot-tooltip .chariot-btn-row { padding-top: 5px; }

.chariot-tooltip .chariot-btn-row .btn {
  font-size: 13px;
  font-weight: 400;
  color: #fff;
  background-color: #337ab7;
  border-radius: 3px;
  height: 36px;
  padding: 0 20px;
  border: none;
}

.chariot-tooltip .chariot-btn-row .btn:hover { background-color: #3e8fd5; }

.chariot-tooltip .chariot-btn-row .chariot-tooltip-subtext {
  float: left;
  color: #ddd;
  font-size: 13px;
  padding-top: 10px;
}

.chariot-tooltip-arrow { background: #fff; }

.chariot-tooltip-arrow-left {
  border-left: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  box-shadow: -2px 2px 2px 0 rgba(31, 28, 28, 0.1);
}

.chariot-tooltip-arrow-right {
  border-right: 1px solid #ddd;
  border-top: 1px solid #ddd;
  box-shadow: 2px -2px 2px 0 rgba(31, 28, 28, 0.1);
}

.chariot-tooltip-arrow-top {
  border-left: 1px solid #ddd;
  border-top: 1px solid #ddd;
  box-shadow: -2px -2px 4px 0 rgba(31, 28, 28, 0.1);
}

.chariot-tooltip-arrow-bottom {
  border-right: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  box-shadow: 2px 2px 4px 0 rgba(31, 28, 28, 0.1);
}

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

  <body class="nav-md">

  <?php if($checkFirstLogin==false){  ?>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script> 
<script src="release/chariot.js"></script> 
<script>
chariot.startTutorial([
  {
    selectors: "#tutorAppointment",
    tooltip: {
      position: 'bottom',
      title: "<i class='fa fa-calendar'></i> Appointments",
      text: "<i class='fa fa-info-circle'></i> Here you can view (Or add)  all the appointments requested by your patients. Upon every booking/reschedule/Cancel patient will be sent an SMS/Email.<br><br><i class='fa fa-lightbulb-o'></i> Try sending your own appointment link to patients just by entering mobile number or email ID of the patient."
    },
  },
  {
    selectors: "#tutorPatient",
    tooltip: {
      position: 'bottom',
      title: "<i class='fa fa-stethoscope'></i> My Patients",
      text: "<i class='fa fa-info-circle'></i> Here you can add patient details, visit details, prescriptions, follow up visit date etc.<br><br><i class='fa fa-lightbulb-o'></i> Try one click prescription by creating your own template."
    },
  },
  {
    selectors: "#tutorConnections",
    tooltip: {
      position: 'bottom',
      title: "<i class='fa fa-user-md'></i> Connections",
      text: "<i class='fa fa-info-circle'></i> Here you connect with doctors, Online pharmacy, diagnostics, Hospitals and more. Refer a case and receive expertise from enlisted connections. <br><br><i class='fa fa-lightbulb-o'></i>  If you want to be an expertise provider, write to us at medical@medisense.me"
    },
  },
  {
    selectors: "#tutorRequest",
    tooltip: {
      position: 'bottom',
      title: "<i class='fa fa-user'></i> Requests",
      text: "<i class='fa fa-info-circle'></i> Request could be for an appointment or simply referring a case to an expert for his expertise. You will have to mention patient details for these requests. Along with your request you will see all the responses from the experts. <br><br><i class='fa fa-lightbulb-o'></i> Open a request submitted by you. Try sending the same case to more doctors. When they respond you will be notified."
    },
  },
  {
    selectors: "#tutorFeed",
    tooltip: {
      position: 'right',
      title: "<i class='fa fa-rss'></i> Feed",
      text: "<i class='fa fa-info-circle'></i>  Here you will find industry updates, blogs from experts, Conference invites, Job opportunities. <br><br><i class='fa fa-lightbulb-o'></i> Try accepting an invite, like or commenting on a blog, applying for a job."
    }
  }
]);

</script>
  <?php 
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'doc_id';
	$arrValues[] = $admin_id;
	$arrFields[] = 'system_ip';
	$arrValues[] = $getClientIp;
	$arrFields[] = 'type';
	$arrValues[] = "1";
	$arrFields[] = 'timestamp';
	$arrValues[] = date('Y-m-d H:i:s');
  $doctimecreate=$objQuery->mysqlInsert('practice_login_tracker',$arrFields,$arrValues);
  
  }  else {
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'system_ip';
	$arrValues[] = $getClientIp;
	$arrFields[] = 'timestamp';
	$arrValues[] = date('Y-m-d H:i:s');
  $updateUser=$objQuery->mysqlUpdate('practice_login_tracker',$arrFields,$arrValues,"doc_id='".$checkFirstLogin[0]['doc_id']."' and type=1");
		
  
  }
	  
	  
	  ?>
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
					<?php 
					foreach($postResult as $postResultList){
						
							/*$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							*/
								
						//TO CHECK POST TYPE IS WHETHER BLOG/OFFER/EVENT
						if($postResultList['listing_type']=="Blog"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription=$getPostResult[0]['post_description'];
							
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
								$userimg=HOST_MAIN_URL."Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="assets/images/anonymous-profile.png";
								}
							
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../Hospital/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="blogs.php";
							$icon="images/blogs.png";
						} else if($postResultList['listing_type']=="Surgical"){
							$getPostResult = $objQuery->mysqlSelect("*","home_posts","post_id='".$postResultList['listing_type_id']."'","","","","");
							$postid=md5($getPostResult[0]['post_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['post_tittle'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription=$getPostResult[0]['post_description'];
							
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
								$userimg=HOST_MAIN_URL."Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="assets/images/anonymous-profile.png";
								}
							
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../Hospital/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="blogs.php";
							$icon="images/blogs.png";
						} else if($postResultList['listing_type']=="Offers"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription=$getPostResult[0]['description'];
							
							$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								} else{
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								}
							
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg=HOST_MAIN_URL."Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="assets/images/anonymous-profile.png";
								}
							
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../Hospital/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="offers.php";
							$icon="images/offers.png";
						} else if($postResultList['listing_type']=="Events"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=1","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription=$getPostResult[0]['description'];
							
								$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								} else{
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof=$getOrg[0]['company_addrs'];
								}
							
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg=HOST_MAIN_URL."Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="assets/images/anonymous-profile.png";
								}
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../Hospital/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="offers.php";
							$icon="images/offers.png";
						}	else if($postResultList['listing_type']=="Jobs"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$postResultList['Create_Date'];	
							$posttitle=$getPostResult[0]['title'];
							$numviews=$getPostResult[0]['num_views'];
							$postDescription=$getPostResult[0]['description'];
							
								$getDocName = $objQuery->mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_photo as Prof_pic,b.spec_name as spec_name","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPostResult[0]['oganiser_doc_id']."'","","","","");
							
								if($getPostResult[0]['oganiser_doc_id']!=0){				
								$username=$getDocName[0]['ref_name'];
								$userprof=$getDocName[0]['spec_name'];
								} else{
								$getOrg = $objQuery->mysqlSelect("company_name,company_addrs","compny_tab","company_id='".$getPostResult[0]['company_id']."'","","","","");
								$username=$getOrg[0]['company_name'];
								$userprof="";
								}
							
								if(!empty($getDocName[0]['Prof_pic'])){
								$userimg=HOST_MAIN_URL."Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="assets/images/anonymous-profile.png";
								}
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../Hospital/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="offers.php";
							$icon="images/offers.png";
						}	

									
						?>	
						
						<!--Begin blog section -->
                        <ul class="messages" >
                          <li>
							<img src="<?php echo $userimg;?>" class="avatar" alt="Avatar">
                            <div class="message_date" style="float:right;">
							
                              <h4 class="date text-info"><?php echo date('d',strtotime($postdate)); ?></h4>
                              <h4 class="date text-info"><?php echo date('M',strtotime($postdate)); ?></h4>
							  <span class='label label-primary pull-right' style="padding:10px; text-transform:uppercase; "><?php echo $posttype; ?></span>
                            </div>
                            <!--<img src="<?php echo $icon; ?>" class="avatar fa fa-rss" alt="Avatar">
                            <div class="message_date" style="float:left;">
                              <h3 class="date text-info"><?php echo date('d',strtotime($postdate)); ?></h3>
                              <p class="month"><?php echo date('M',strtotime($postdate)); ?></p>
                            </div>-->
                            <div class="message_wrapper">
                             
                              <a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>"><blockquote class="message"><?php if(!empty($posttitle)){ echo $posttitle; } ?>
							  <h5 class="heading"><?php echo $username; ?></h5><small><em><?php echo $userprof; ?></em></small>
							   <h5 class="heading" title="No. of views"><i class="fa fa-eye"></i> <em><?php echo $numviews; ?></em></h5>
							  </blockquote></a>
							 
							
                              <p >
							   <?php if(!empty($postimage)){ ?><a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>"><img src="<?php echo $postimage; ?>" width="650" class="img-responsive"/></a> <?php } ?>
							   
							   <?php if(!empty($postDescription)){ echo substr($postDescription,0,600)."..."; } ?>
                                <br><br></p>
								
												
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
                          <h2><i class="fa fa-list"></i> Feed Category</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                          <div class="project_detail">
						  <div class="project_detail">
                         
                          <ul class="list-unstyled project_files">
						 
                            <li><a href="Blogs-Offers-Events-List?s=Blog"><i class="fa fa-rss"></i> Blogs <!--<span style="color:#ccc;">(<?php echo $countBlog[0]['Count']; ?>)</span>--></a></li>
							<li><a href="Blogs-Offers-Events-List?s=Jobs"><i class="fa fa-graduation-cap"></i> Jobs <!--<span style="color:#ccc;">(<?php echo $countOffer[0]['Count']; ?>)</span>--></a></li>
							<li><a href="Blogs-Offers-Events-List?s=Events"><i class="fa fa-volume-up"></i> Events <!--<span style="color:#ccc;">(<?php echo $countEvent[0]['Count']; ?>)</span>--></a>
							</li>
							<li><a href="Blogs-Offers-Events-List?s=Surgical"><i class="fa fa-film"></i> Surgical Videos <!--<span style="color:#ccc;">(<?php echo $countEvent[0]['Count']; ?>)</span>--></a>
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
							<?php } if($_GET['response']=="error-link") {  ?>
							<h4><span style="color:red; font-weight:bold;"><i class="fa fa-warning"></i> Error!!!</span><br></h4>
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
					 <!-- <section class="panel">

                        <div class="x_title">
                          <h2>How it works</h2>
						  <div class="clearfix"></div>
                        </div>
						<iframe width="250" height="200" src="https://www.youtube.com/embed/oLYtYCpq0OY?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>   
					  </section>-->
					  
					  
                    </div>
                    <!-- end project-detail sidebar -->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
<a href="#" class="scrollToTop"><h1><i class="fa fa-arrow-up"></i></h1> </a>
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