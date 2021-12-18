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
//$getDeaprtment = $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","0,10");

if(isset($_GET['category'])){

$postResult = $objQuery->mysqlSelect("*","home_posts","post_type='".$_GET['category']."'","post_id desc","","","");

}else{
			
$postResult = $objQuery->mysqlSelect("*","home_posts","","post_id desc","","","");

}


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
$getFeature = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1","rand()","","","");
$get_pro = $objQuery->mysqlSelect("a.ref_id as RefId","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","md5(c.company_id)='".$admin_id."'","a.Tot_responded desc","","","");

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
            <div class="page-title">
              <div class="title_left">
                <h3>OUR BLOGS</h3>
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
            </div>
            
            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  
                  <div class="x_content">

                    <div class="col-md-9 col-sm-9 col-xs-12">

                     
					
					<div class="text-right">
                            <!-- Large modal -->
							<a href="Add-Blog" class="btn btn-primary" ><i class="fa fa-plus"></i> Add Blogs</a>
                     </div>
					<div id="blogSection">
					<?php 
					foreach($postResult as $postResultList){
						
							$commentCount = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
							$postComments = $objQuery->mysqlSelect("*","home_post_comments","topic_id='".$postResultList['post_id']."'","comment_id desc","","","");
							$CommentsCounts = $objQuery->mysqlSelect("COUNT(comment_id) as count","home_post_comments","topic_id='".$postResultList['post_id']."'","","","","");
						

						//TO CHECK USER TYPE WHETHER HE IS DOCTOR OR NOT
						if($postResultList['Login_User_Type']=="doc"){
							//$trimdocid  = str_replace("doc-", "", $postResult[$i]['Login_User_Id']);
							$user = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$postResultList['Login_User_Id']."'","","","","");
							$getSpec=$objQuery->mysqlSelect("*","specialization","spec_id='".$user[0]['doc_spec']."'","","","","");	
								if($postResultList['anonymous_status']==0){
								$username=$user[0]['ref_name'];
								$userprof=$getSpec[0]['spec_name'];
								}
								else{
									$username="Anonymous";
									$userprof=$getSpec[0]['spec_name'];
								}
								if(!empty($user[0]['doc_photo']) && $postResultList['anonymous_status']==0){
								$userimg="https://medisensecrm.com/Doc/".$postResultList['Login_User_Id']."/".$user[0]['doc_photo']; 
								}
								else{
									$userimg="new_assets/images/no_user_icon.png";
								}
						$getDocName=urlencode(str_replace(' ','-',$user[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$user[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$user[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$user[0]['hosp_name']));
						$getDocHospAdd=urlencode(str_replace(' ','-',$user[0]['hosp_addrs']));

						$Link='https://medisensehealth.com/Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocCity.'-'.$getDocState.'/'.$user[0]['ref_id'];
							
								
								
						} else if($postResultList['Login_User_Type']=="user"){
							//$user = $objQuery->mysqlSelect("*","login_user","login_id='".$postResultList['Login_User_Id']."'","","","","");
								if($postResultList['anonymous_status']==0){
								$username=$_SESSION['company_name'];
								$userprof=$user[0]['sub_proff'];
								}
								else{
									$username="Anonymous";
								}
								
								if(!empty($user[0]['user_img']) && $postResultList['anonymous_status']==0){
								$userimg=$user[0]['user_img'];	
								}
								else{
									$userimg="images/anonymous-profile.png";	
								}
						}						
						?>	
						
						<!--Begin blog section -->
                        <ul class="messages" >
                          <li>
                            <img src="<?php echo $userimg;?>" class="avatar" alt="Avatar">
                            <div class="message_date">
                              <h3 class="date text-info"><?php echo date('d',strtotime($postResultList['post_date'])); ?></h3>
                              <p class="month"><?php echo date('M',strtotime($postResultList['post_date'])); ?></p>
                            </div>
                            <div class="message_wrapper">
                              <!--<h4 class="heading"><?php echo $username; ?><br><small><em><?php echo $userprof; ?></em></small></h4>-->
                              <blockquote class="message"><?php if(!empty($postResultList['post_tittle'])){ echo $postResultList['post_tittle']; } ?></blockquote>
                              <br />
							 	
                              <p>
							   <?php if(!empty($postResultList['post_image'])){ ?><img src="../Hospital/Postimages/<?php echo $postResultList['post_id']; ?>/<?php echo $postResultList['post_image']; ?>" width="650" class="img-responsive"/> <?php } ?>
							   
							   <?php if(!empty($postResultList['post_description'])){ echo substr($postResultList['post_description'],0,600)."..."; } ?>
                                <br><br></p>
							 
								<p class="url">
								<ul class="nav navbar-left panel_toolbox">
								<li><a ><i class="fa fa-thumbs-up"></i> <small>23</small></a>
							  <li><a ><i class="fa fa-comment"></i> <small>5</small></a>
							  </li>
							  <li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-share-alt"></i></a>
								<ul class="dropdown-menu" role="menu">
								  <li><a href="#"><i class="fa fa-facebook-square"></i> Facebook</a>
								  </li>
								  <li><a href="#"><i class="fa fa-twitter-square"></i> Twitter</a>
								  </li>
								  <li><a href="#"><i class="fa fa-google-plus-square"></i> Google +</a>
								  </li>
								  
								</ul>
							  </li>
								</ul>
								</p>
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
						 
                            <li><a href="Blogs-Offers-Events-List?s=Blog"><i class="fa fa-rss"></i> Blogs <!--<span style="color:#ccc;">(<?php echo $countBlog[0]['Count']; ?>)</span>--></a>
							<li><a href="Blogs-Offers-Events-List?s=Offers"><i class="fa fa-volume-up"></i> Offers <!--<span style="color:#ccc;">(<?php echo $countOffer[0]['Count']; ?>)</span>--></a>
							<li><a href="Blogs-Offers-Events-List?s=Events"><i class="fa fa-volume-up"></i> Events <!--<span style="color:#ccc;">(<?php echo $countEvent[0]['Count']; ?>)</span>--></a>
                            </li>
						 
                          </ul>
						  </div>
                          <br />

                          
                        </div>

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