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
$postResult = $objQuery->mysqlSelect("*","blogs_offers_events_listing","listing_type='".$_GET['s']."'","Create_Date desc","","","");

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
//Convert Minutes to Days HH:MM Format functionality
 function con_min_days($mins)
    {

            $hours = str_pad(floor($mins /60),2,"0",STR_PAD_LEFT);
            $mins  = str_pad($mins %60,2,"0",STR_PAD_LEFT);

            if((int)$hours > 24){
            $days = str_pad(floor($hours /24),2,"0",STR_PAD_LEFT);
            $hours = str_pad($hours %24,2,"0",STR_PAD_LEFT);
            }
            if(isset($days)) { $days = $days." Day[s] ";}

            return $days.$hours." Hour[s] ".$mins." Min[s]";
    }
	
//$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = $objQuery->mysqlSelect("b.hosp_id as hosp_id,c.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","a.Tot_responded desc","","","");

$checkFirstLogin = $objQuery->mysqlSelect("doc_id","practice_login_tracker","doc_id='".$admin_id."' and type=2","","","","");
	
	if($checkFirstLogin==false){  
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'doc_id';
	$arrValues[] = $admin_id;
	$arrFields[] = 'system_ip';
	$arrValues[] = $getClientIp;
	$arrFields[] = 'type';
	$arrValues[] = "2";
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
  $updateUser=$objQuery->mysqlUpdate('practice_login_tracker',$arrFields,$arrValues,"doc_id='".$checkFirstLogin[0]['doc_id']."' and type=2");
		
  
  }


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
	</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<?php include_once('side_menu.php');?>
		 
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		   <div class="row tile_count">
		  
		  <div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="My-Patient-List"> <span class="count_top"><i class="fa fa-stethoscope"></i> <b>MY PRACTICE</b></span><br>
			 <span class="count_top"><i class="fa fa-user"></i> Patients</span>
              <div class="count"><?php echo $countMyPatient[0]['Total_count'];; ?></div></a>
			  <a href="Appointments"><span class="count_bottom"><i class="red"><?php echo $Total_Appointment_Count[0]['count']; ?> </i> Appointments </a>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="Responded-Records"><span class="count_top"><i class="fa fa-code-fork"></i> <b>MY NETWORK</b></span>
              <br><span class="count_top"><i class="fa fa-user"></i> Total Referrals</span>
			  <div class="count"><?php echo $_SESSION['all_record']; ?></div></a>
              <a href="Responded-Records"><span class="count_bottom"><i class="green"><?php echo $_SESSION['tot_resp_count']; ?> </i> Responded  &</a><br><a href="Not-Responded"> <i class="red"><?php echo $_SESSION['not_resp_count']; ?></i> not responded</span></a>
            
			</div>
			
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="All-Records"> <span class="count_top"><b> VISIT FROM NETWORK</b></span>
              <div class="count green"><?php echo $_SESSION['tot_converted_count']; ?></div>
              <span class="count_bottom"><i class="green"><?php echo $_SESSION['conversion_rate']; ?>% </i> Visit rate</span>
            </div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
              <a href="Converted-Records"><span class="count_top"><b> RESPONSE RATE</b></span>
              <div class="count"><?php echo $_SESSION['response_rate']; ?>%</div>
             
            </a>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> AVG. RESPONSE TIME</span><br><br>
              <div class="green count_bottom"><b><?php echo con_min_days($_SESSION['response_time']); ?></b></div>
              
            </div>
                     
            
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
             <a href="Blogs-List"> <span class="count_top"><b>BLOGS, EVENTS, OFFERS</b></span>
              <div class="count"><b><?php echo $totalBlogs[0]['Count_Blogs']; ?></b></div></a>
              
            </div>
		  
		  
            
			
          </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-9 col-sm-9 col-xs-12">
					<div class="row">
					<div class="form-group pull-right">
					<div class="input-group">
						<a href="Add-Blog" class="btn btn-success btn-xs"><i class="fa fa-rss"></i> ADD BLOG </a>                     
						
					  </div>
					</div>
                    </div> 
					
					
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
							$postdate=$getPostResult[0]['post_date'];	
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
								$userimg="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="images/anonymous-profile.png";
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
							$postdate=$getPostResult[0]['post_date'];	
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
								$userimg="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="images/anonymous-profile.png";
								}
							
							
							if(!empty($getPostResult[0]['post_image'])){
							$postimage="../Hospital/Postimages/".$getPostResult[0]['post_id']."/".$getPostResult[0]['post_image'];
							} else {
							$postimage="";
							}
							$url="blogs.php";
							$icon="images/blogs.png";
						}					
						
						else if($postResultList['listing_type']=="Offers"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=2","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$getPostResult[0]['created_date'];	
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
								$userimg="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="images/anonymous-profile.png";
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
							$postdate=$getPostResult[0]['created_date'];	
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
								$userimg="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="images/anonymous-profile.png";
								}
							
							if(!empty($getPostResult[0]['photo'])){
							$postimage="../Hospital/Eventimages/".$getPostResult[0]['event_id']."/".$getPostResult[0]['photo'];
							} else {
							$postimage="";
							}
							$url="offers.php";
							$icon="images/offers.png";
						} else if($postResultList['listing_type']=="Jobs"){
							$getPostResult = $objQuery->mysqlSelect("*","offers_events","event_id='".$postResultList['listing_type_id']."' and event_type=3","","","","");
							$postid=md5($getPostResult[0]['event_id']);
							$posttype=$postResultList['listing_type'];
							$postdate=$getPostResult[0]['created_date'];	
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
								$userimg="https://medisensecrm.com/Doc/".$getDocName[0]['ref_id']."/".$getDocName[0]['Prof_pic']; 
								}
								else{
									$userimg="images/anonymous-profile.png";
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
                            <div class="message_wrapper">
                               <a href="<?php echo $url; ?>?s=<?php echo $posttype; ?>&id=<?php echo $postid; ?>"><blockquote class="message"><?php if(!empty($posttitle)){ echo $posttitle; } ?>
							  <h5 class="heading"><?php echo $username; ?></h5><small><em><?php echo $userprof; ?></em></small>
							  </blockquote></a>
                            <h5 class="heading" title="No. of views"><i class="fa fa-eye"></i> <em><?php echo $numviews; ?></em></h5>
							 <p >
							   <?php if(!empty($postimage)){ ?><img src="<?php echo $postimage; ?>" width="650" class="img-responsive"/> <?php } ?>
							   
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
                          <h2><i class="fa fa-list"></i> Category</h2>
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
					  
					  <section class="panel">

                        <div class="x_title">
                          <h2><i class="fa fa-user-md"></i> Invite Referring Partners</h2>
                          <div class="clearfix"></div>
                        </div>
					  <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          <p>Click here to send invitation to your referring doctors/partners</p>
                        </a>
                        <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">
                            <form class="form-horizontal form-label-left" enctype="multipart/form-data" action="add_details.php" method="post" id="frmReferring" id="frmReferring">
						<input type="hidden" name="selectHosp" value="<?php echo $get_pro[0]['hosp_id'];?>" />
						<input type="hidden" name="CompId" value="<?php echo $get_pro[0]['company_id'];?>" />
						<input type="hidden" name="selectType" value="CLASS-A" />
						<div class="form-group">
                        
						<br>
                        <div class="col-xs-12">
						<input type="text" id="refPartName" name="refPartName" required="required" class="form-control" placeholder="Partners Name *">
						</div>
						
						<br><br><br>
						<div class="col-xs-12">
						<input type="text" id="refPartMobile" name="refPartMobile" required="required" class="form-control" placeholder="Mobile No. *" maxlength="10">
						</div>
						<br><br><br>
						<div class="col-xs-12">
						<input type="email" id="refPartEmail" name="refPartEmail" required="required" class="form-control" placeholder="Email Id *">
						</div>
						<br><br><br>
						<div class="col-xs-12">
						<button type="submit"  name="add_referrer" id="add_referrer" class="btn btn-success"><i class="fa fa-mail-forward"></i> SEND </button>
						</div>
						</div>
						</form>
                          </div>
                        </div>
                      </div>
                     
                      
                    </div>
                    <!-- end of accordion -->
					</section>
					
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
					 
					  <!--<section class="panel">

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