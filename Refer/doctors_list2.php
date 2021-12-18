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


//Filter Option
if(isset($_POST['filterStatus'])){
$filter_val = $_POST['filter_val'];
?>
<SCRIPT LANGUAGE="JavaScript">	
	window.location.href="<?php echo $_SERVER['PHP_SELF'];?>?f="+<?php echo $filter_val; ?>;
	</SCRIPT>
<?php
}

if(isset($_GET['f'])){
	
	if($_GET['f']==0)
	{ 
		header("Location:".$_SERVER['PHP_SELF']);
	}
	

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

    <title>Featured Specialist</title>

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
	
	<!-- PNotify -->
    <link href="../Hospital/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../Hospital/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../Hospital/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
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
          
			  
		<form method="post" name="frmSrchBox" action="add_details.php" onsubmit="ShowLoading()">
		<input type="hidden" name="postTextSrchCmd" value="" />
		<input type="hidden" name="postTextSrch" value="" />
			 <div class="title_right">
                <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" name="postTextSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </div>
              </div><br>
			 
			  </form>
			  
           
			 <div class="page-title">
			 <div class="title_left">
                <div class="left">
                <div class="form-group pull-right top_search">
                  <div class="input-group">
				  <label>Filter By:</label>
				  <script language="JavaScript" src="js/status_validation.js"></script>
				  <form method="post" name="frmFilter" >
					<input type="hidden" name="filterStatus" value="" />
					<input type="hidden" name="filter_val" value=""/>
                   <select class="form-control" name="slctStatus" onchange="return filterBy(this.value);">
				   <option value="0" >Select Department</option>
		<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
		$i=30;
		foreach($DeptName as $DeptList){
			if($DeptList['spec_id']==$_GET['f']){ ?> 
		<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected/><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php 
			}?>

			<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
		<?php
				$i++;
		}?>   
		</select>
                    </form> 
                    </span>
                  </div>
                </div>
              </div>
              </div>
			 
				<div class="title_right">
                <div class="col-md-1 col-sm-1 col-xs-12 form-group pull-right">
				<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-sm"><i class="fa fa-paper-plane"></i> Send Invite</button>-->
               <div id="myModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
					<form method="post" name="frmInvite" action="">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">Invite Doctors</h4>
                        </div>
						<div class="modal-body" style="padding-bottom:50px;">
						<div class="col-xs-12">
						<input type="email" id="pat_email" name="doc_name" required="required" class="form-control" placeholder="Doctor Name">
						</div>
						<br><br><br>
						<div class="col-xs-12">
						<input type="email" id="pat_email" name="doc_email" class="form-control" placeholder="Email Id">
						</div>
						<span style="margin-left:60px; text-align:center;">Or</span>
						<br><br>
						<div class="col-xs-12">
						<input type="email" id="pat_mobile" name="doc_mobile"  class="form-control" placeholder="Mobile No.">
						</div>
						
						<br><br><br>
						<div class="col-xs-12 right">
						<button type="submit" name="refer_patient" id="refer_patient" class="btn btn-success"><i class="fa fa-paper-plane"></i> SEND </button>
						</div>
																	</div>
                       
                </div>
                   </form>     

                      </div>
                    </div>
                </div>
              </div><br>
			 </div>
            <div class="clearfix"></div>

				<div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_content">
				  <div class="clearfix"></div>
                    <div class="row">
                      

                      
					<?php 
					//get only mapped hospital doctors list			
					$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$admin_id."'","a.doc_type_val asc","","","");
					
					
					if($getMappedFeature==true){
										
					foreach($getMappedFeature as $getList){ ?>
                     <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <!--<h4 class="brief"><i>Digital Strategist</i></h4>-->
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
								 <img src="images/doc_icon.jpg" alt="" class="img-circle img-responsive"/>
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
							<form method="post" action="add_details.php">
							<input type="hidden" name="partner_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="doc_id" value="<?php echo $getList['Ref_Id']; ?>" />
                            <div class="col-xs-12 col-sm-6 emphasis">
							<?php $getFavourDoc = $objQuery->mysqlSelect("*","add_favourite_doctor","doc_id='".$getList['Ref_Id']."' and user_id='".$admin_id."'","","","","");
							?>
                              <button type="submit" name="addFavour" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Add to favourite" <?php if($getFavourDoc==true){ echo "disabled";} else { } ?> onclick="new PNotify({
                                  title: 'Regular Success',
                                  text: 'That thing that you were trying to do worked!',
                                  type: 'success',
                                  styling: 'bootstrap3'
                              });"> <i class="fa fa-user-md">
                                </i> <i class="fa fa-bookmark"></i> </button>
							</form>	
							
							<input type="hidden" name="ref_id" value="<?php echo $getList['Ref_Id']; ?>" />
                              <a href="Refer-Patient?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>"><button type="button" name="cmdRefer" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Refer Patient">
                                <i class="fa fa-user"> </i> REFER</button></a>
							
                            </div>
                          </div>
                        </div>
                      </div>
					<?php } 
						
					
					
					}
					else if(COUNT($getMappedFeature)==0){ 
							if(isset($_GET['s'])){
							$params     = explode(" ", $_GET['s']);
							$postid1 = $params[0];
							$postid2 = $params[1];
							
							
							$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");	
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
							$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","$eu, $limit");
							$pag_result = $objQuery->mysqlSelect("*","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","");
							$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
							$arrPage = explode("-",$pageing);
						  
							}	
					
						?>
						<div class="col-md-12 col-sm-12 col-xs-12">
                         <ul class="nav navbar-right panel_toolbox">
					<li>Displaying results 1 - <?php echo $pages; ?> of <?php echo $_GET['start']; ?> </li>
                     <li>&nbsp;&nbsp;</li>
					 <li><?php echo $arrPage[0];?></a>
                      </li>
                    </ul><br><br>
                      </div>

                      <div class="clearfix"></div>
					<?php foreach($getMappedFeature as $getList){ ?>
                      <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <!--<h4 class="brief"><i>Digital Strategist</i></h4>-->
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
								 <img src="images/doc_icon.jpg" alt="" class="img-circle img-responsive"/>
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
							<form method="post" action="add_details.php">
							<input type="hidden" name="partner_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="doc_id" value="<?php echo $getList['Ref_Id']; ?>" />
                            <div class="col-xs-12 col-sm-6 emphasis">
							<?php $getFavourDoc = $objQuery->mysqlSelect("*","add_favourite_doctor","doc_id='".$getList['Ref_Id']."' and user_id='".$admin_id."'","","","","");
							?>
                              <button type="submit" name="addFavour" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Add to favourite" <?php if($getFavourDoc==true){ echo "disabled";} else { } ?> onclick="new PNotify({
                                  title: 'Regular Success',
                                  text: 'That thing that you were trying to do worked!',
                                  type: 'success',
                                  styling: 'bootstrap3'
                              });"> <i class="fa fa-user-md">
                                </i> <i class="fa fa-bookmark"></i> </button>
							</form>	
							
							<input type="hidden" name="ref_id" value="<?php echo $getList['Ref_Id']; ?>" />
                              <a href="Refer-Patient?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>"><button type="button" name="cmdRefer" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Refer Patient">
                                <i class="fa fa-user"> </i> REFER</button></a>
							
                            </div>
                          </div>
                        </div>
                      </div>
					
					<?php } 
				
						
					}
					?>	
                   
				   <?php 
					if(isset($_GET['f'])){
					foreach($getMappedHospital as $hospList){ 

					if($_GET['f']==2){
					$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1 and a.Tot_responded!=0) and (c.hosp_id='".$hospList['hosp_id']."')","a.Tot_responded asc","","","");
					}
					else if($_GET['f']==3){
					$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=1) and (c.hosp_id='".$hospList['hosp_id']."')","a.Tot_responded asc","","","");
					
					}
					else if($_GET['f']==4){
					$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=4) and (c.hosp_id='".$hospList['hosp_id']."')","a.Tot_responded asc","","","");
					
					}
					else if($_GET['f']==5){
					$getMappedFeature = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on a.doc_spec=b.spec_id inner join doctor_hosp as c on c.doc_id=a.ref_id","(a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_type_val=5) and (c.hosp_id='".$hospList['hosp_id']."')","a.Tot_responded asc","","","");
					
					}
					
					if(!empty($getMappedFeature)){
					foreach($getMappedFeature as $getList){ ?>
                      <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <!--<h4 class="brief"><i>Digital Strategist</i></h4>-->
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
								 <img src="images/doc_icon.jpg" alt="" class="img-circle img-responsive"/>
							<?php  } ?>
							
                              
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                            <div class="col-xs-12 col-sm-6 emphasis">
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
							  <?php } 
							  ?>
                            </div>
							<form method="post" action="add_details.php">
							<input type="hidden" name="partner_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="doc_id" value="<?php echo $getList['Ref_Id']; ?>" />
                            <div class="col-xs-12 col-sm-6 emphasis">
							<?php $getFavourDoc = $objQuery->mysqlSelect("*","add_favourite_doctor","doc_id='".$getList['Ref_Id']."'","","","","");
							?>
                              <button type="submit" name="addFavour" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Add to favourite" <?php if($getFavourDoc==true){ echo "disabled";} else { } ?> > <i class="fa fa-user-md">
                                </i> <i class="fa fa-bookmark"></i> </button>
							</form>	
							
							<input type="hidden" name="ref_id" value="<?php echo $getList['Ref_Id']; ?>" />
                              <a href="Refer-Patient?d=<?php echo md5($getList['Ref_Id']); ?>&start=<?php echo $_GET['start']; ?>"><button type="button" name="cmdRefer" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="bottom" title="Refer Patient">
                                <i class="fa fa-user"> </i> REFER</button></a>
							
                            </div>
                          </div>
                        </div>
                      </div>
					<?php } 
						}
						
					
						}
					
					}
					?>
					
					
					
					
					
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