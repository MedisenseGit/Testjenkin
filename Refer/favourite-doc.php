<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();


	if(isset($_GET['s'])){
	$params     = explode(" ", $_GET['s']);
	$postid1 = $params[0];
	$postid2 = $params[1];
	
	
	$getFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");	
	$countRslt = $objQuery->mysqlSelect("COUNT(a.ref_id) as count","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
	}
	else {
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
	$getFavour = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization,c.favourite_id as Favour_Id","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join add_favourite_doctor as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.user_id='".$admin_id."' and c.user_type=1)","a.doc_type_val asc","","","$eu, $limit");
	$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join add_favourite_doctor as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1) and (c.user_id='".$admin_id."' and c.user_type=1)","a.doc_type_val asc","");
	$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
	$arrPage = explode("-",$pageing);
  
	}
 
//Delete doctors from favourite list
if(isset($_POST['cmdDel'])){
	
	$favourid = $_POST['FavourId'];
	
	//echo $favour_id;	
	$objQuery->mysqlDelete('add_favourite_doctor',"favourite_id='".$favourid ."'");			
	$response="delete";
	header("Location:Favourite-Doctors?response=".$response);
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

    <title>Refer Patient</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../Hospital/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
   
    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	<link href="../Hospital/css/pagination.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<?php include_once('side_menu.php'); ?>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
		   <?php include_once('header_top_nav.php'); ?>
           <div class="page-title">
              <div class="title_left">
                <h3>Favourite Doctor <!-- <small>Some examples to get you started</small>--></h3>
              </div>
			  
				
			
			
		<form method="post" name="frmSrchBox" action="add_details.php" onsubmit="ShowLoading()">
		<input type="hidden" name="postTextSrchCmd" value="" />
		<input type="hidden" name="postTextSrch" value="" />
			 <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" name="postTextSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </div>
              </div><br>
			 
			  </form>
            </div>

            <div class="clearfix"></div>
			<?php
						if($_GET['response']=="delete"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>SUCCESS !!</strong> removed from your favourite list.
                  </div>
						
						<?php 
						} ?>
				<div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="row">
                      <div class="col-md-12 col-sm-12 col-xs-12">
                         <ul class="nav navbar-right panel_toolbox">
					<li>Displaying results 1 - <?php echo $pages; ?> of <?php echo $_GET['start']; ?> </li>
                     <li>&nbsp;&nbsp;</li>
					 <li><?php echo $arrPage[0];?></a>
                      </li>
                    </ul><br><br>
                      </div>

                      <div class="clearfix"></div>
					<?php foreach($getFavour as $getList){ 
					if(empty($getList)){ ?>
					<div class="col-md-4 col-sm-4 col-xs-12 profile_details">
						<div class="well profile_view">
                          <div class="col-sm-12">
						  <div class="left col-xs-7">
                       <h2>No result found </h2>	
					   </div>
						</div>
					   </div>
					   </div>
					<?php } if(!empty($getList)){
					?>
                      <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                         
                            <div class="left col-xs-7">
                              <h2><?php echo $getList['Ref_Name']; ?></h2>
                              <p><?php echo $getList['Specialization']; ?> </p>
                              <ul class="list-unstyled">
                                <li><i class="fa fa-star"></i> <?php echo $getList['Ref_Exp']; ?> Yrs</li>
                                <li><i class="fa fa-home"></i> <?php echo $getList['Ref_Address'].", ".$getList['Doc_State']; ?> </li>
                              </ul>
                            </div>
                            <div class="right col-xs-5 text-center">
							 <?php if(!empty($getList['Doc_Image'])){ ?>
								<img src="https://medisensecrm.com/Doc/<?php echo $getList['Ref_Id']; ?>/<?php echo $getList['Doc_Image']; ?>" alt="" class="img-circle img-responsive" height="20"/>
							 <?php }  else { ?>
								 <img src="images/anonymus_docimg.jpg" alt="" class="img-circle img-responsive"/>
							<?php  } ?>
							
                              
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                            <!--<div class="col-xs-12 col-sm-6 emphasis">
                              <?php if($getList['Doc_Type']==1){?>
							  <p class="small ratings">
                                <a>5.0</a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                              </p>
							  <?php } else if($getList['Doc_Type']==4){?>
							  <p class="small ratings">
                                <a>3.0</a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                              </p>
							  <?php } else if($getList['Doc_Type']==5){?>
							  <p class="small ratings">
                                <a>2.0</a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                                <a href="#"><span class="fa fa-star-o"></span></a>
                              </p>
							  <?php } ?>
                            </div>-->
							 <!--<script language="JavaScript" src="js/status_validation.js"></script>-->
							<form method="post" name="frmDelFavourite" >
							<input type="hidden" name="FavourId" value="<?php echo $getList['Favour_Id'];?>" />
							 <!--<input type="hidden" name="cmdDel" value="" />
							<input type="hidden" name="FavourId" value="" />
                            <div class="col-xs-12 col-sm-6 emphasis">
							
                             <a href='#' onclick="return delFavour(<?php echo $getList['Favour_Id'];?>)" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Remove from favourite list"><i class="fa fa-trash-o"></i></a>-->
							<div class="col-xs-12 col-sm-6 emphasis">
							<button type="submit" name="cmdDel" class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="bottom" title="Remove from your favourite list" ></i><i class="fa fa-trash-o"></i> </button>
							</form>
							<input type="hidden" name="ref_id" value="<?php echo $getList['Ref_Id']; ?>" />
                              <a href="Refer-Patient?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>"><button type="button" name="cmdRefer" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Refer Patient">
                                <i class="fa fa-user"> </i> REFER</button></a>
							
                            </div>
							
                          </div>
                        </div>
                      </div>
					<?php }
					} ?>	
                   
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

           
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
       <?php include('footer.php'); ?>
        <!-- /footer content -->
      </div>
    </div>

    <div id="custom_notifications" class="custom-notifications dsp_none">
      <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
      </ul>
      <div class="clearfix"></div>
      <div id="notif-group" class="tabbed_notifications"></div>
    </div>

    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../Hospital/vendors/iCheck/icheck.min.js"></script>
    

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
	
  </body>
</html>