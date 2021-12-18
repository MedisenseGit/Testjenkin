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
          
			  
		
			  
           
			 <div class="page-title">
			 <div class="title_left">
                <div class="left">
                <div class="col-md-8 col-sm-8 col-xs-12 form-group pull-left top_search">
                  <div class="input-group">
				  <label>Filter By:</label>
				  <script language="JavaScript" src="js/status_validation.js"></script>
				  <form method="post" name="frmFilter" >
					<input type="hidden" name="filterStatus" value="" />
					<input type="hidden" name="filter_val" value=""/>
                   <select class="form-control" name="slctStatus" onchange="return filterBy(this.value);">
				   <option value="0" >Select Specialization</option>
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
			  
			  <form method="post" name="frmSrchBox" action="add_details.php" onsubmit="ShowLoading()">
		<input type="hidden" name="postTextSrchCmd" value="" />
		<input type="hidden" name="postTextSrch" value="" />
			 <div class="title_right">
                <div class="col-md-6 col-sm-6 col-xs-12 form-group pull-right top_search">
				<div style="margin-left:20px;"><label>Search Doctors :</label><br>  <input type="radio" name="searchType" value="1">  My Conncetions <input type="radio" name="searchType" value="2" checked>   Universal</div>
                  <div class="input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Ex: Cardio Bangalore...">
                    <span class="input-group-btn">
                      <button type="submit" name="postTextSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </div>
              </div>
			 
			  </form>
			 
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
              </div>
			 </div>
            <div class="clearfix"></div>

				<div class="row">
				 <div class="" role="tabpanel" data-example-id="togglable-tabs">
                        <?php if(empty($_GET['f']) && empty($_GET['s'])) {  ?><ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active"><a href="#myConnections" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" >My Connections <span class="badge bg-red" style="font-size:8px;"><?php echo $_SESSION['mycircle_doc']; ?></span></a>
                          </li>
                          <li role="presentation" class=""><a href="#universal" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">Universal <span class="badge bg-red" style="font-size:8px;"><?php echo $_SESSION['universal_doc']; ?></span></a>
                          </li>
                          
                        </ul> <?php } ?>
						 <div id="myTabContent" class="tab-content">
						 
						 <?php if(isset($_GET['f']) || isset($_GET['s'])) { 
						 
						 //get only Filter Value hospital doctors list	
							if(isset($_GET['f'])){
								$getFilterVal = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1 and a.doc_spec='".$_GET['f']."'","a.doc_type_val asc","","","");
							$getSpec = $objQuery->mysqlSelect("spec_name","specialization","spec_id='".$_GET['f']."'","","","","");
					
							}
							else if(isset($_GET['s']) && isset($_GET['type'])){
								$params     = explode(" ", $_GET['s']);
								$postid1 = $params[0];
								$postid2 = $params[1];
								if($_GET['type']==2){	
								$getFilterVal = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
								//$getFilterVal = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1) and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
								
								}
								else{
								$getFilterVal = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id inner join mapping_hosp_referrer as e on e.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and e.partner_id='".$admin_id."') and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
								//$getFilterVal = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id inner join mapping_hosp_referrer as e on e.hosp_id=c.hosp_id","((a.doc_spec!=555 and a.anonymous_status!=1 and e.partner_id='".$admin_id."') and ((a.ref_name LIKE '%".$_GET['s']."%' or a.doc_interest LIKE '%".$_GET['s']."%' or a.doc_research LIKE '%".$_GET['s']."%' or a.doc_contribute LIKE '%".$_GET['s']."%' or a.doc_pub LIKE '%".$_GET['s']."%' or d.hosp_name LIKE '%".$_GET['s']."%') or ((b.spec_name LIKE '%".$postid1."%' or a.ref_name LIKE '%".$postid1."%' or a.ref_address LIKE '%".$postid1."%' or a.doc_state LIKE '%".$postid1."%') and (a.ref_address LIKE '%".$postid2."%' or a.doc_state LIKE '%".$postid2."%' or b.spec_name LIKE '%".$postid2."%'))))","a.doc_type_val asc","","","");
									
								}
							}
								

						 ?>
						 <p><em>Search results for <b><?php echo $getSpec[0]['spec_name'].$_GET['s']; ?></b><br>About <?php echo count($getFilterVal); ?> results found</em></p>
						 <div role="tabpanel" class="tab-pane fade active in" id="myConnections" aria-labelledby="home-tab">

								 <div class="clearfix"></div>

								<div class="row">
								  <div class="col-md-12">
									<div class="x_panel">
									  <div class="x_content">
										<div class="row">
										  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
											
										  </div>

										  <div class="clearfix"></div>

										  <?php 
					
					
					if($getFilterVal==true){
										
					foreach($getFilterVal as $getList){ 
					$getDocVal = $objQuery->mysqlSelect("a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id inner join mapping_hosp_referrer as e on e.hosp_id=c.hosp_id","a.ref_id='".$getList['Ref_Id']."'","a.doc_type_val asc","","","");
								
					?>
                     <div class="col-md-4 col-sm-4 col-xs-12 profile_details">
                        <div class="well profile_view">
                          <div class="col-sm-12">
                            <!--<h4 class="brief"><i>Digital Strategist</i></h4>-->
                            <div class="left col-xs-7">
                              <h2><?php echo $getDocVal[0]['Ref_Name']; ?></h2>
                              <p><?php echo $getDocVal[0]['Specialization']; ?> </p>
                              <ul class="list-unstyled">
                                <li><i class="fa fa-star"></i> <?php echo $getDocVal[0]['Ref_Exp']; ?> Yrs</li>
                                <li><i class="fa fa-home"></i> <?php echo $getDocVal[0]['Ref_Address'].", ".$getDocVal[0]['Doc_State']; ?> </li>
                              </ul>
                            </div>
                            <div class="right col-xs-5 text-center">
							 <?php if(!empty($getDocVal[0]['Doc_Image'])){ ?>
								<img src="https://medisensecrm.com/Doc/<?php echo $getList['Ref_Id']; ?>/<?php echo $getDocVal[0]['Doc_Image']; ?>" alt="" class="img-circle img-responsive" height="20"/>
							 <?php }  else { ?>
								 <img src="images/doc_icon.jpg" alt="" class="img-circle img-responsive"/>
							<?php  } ?>
							
                              
                            </div>
                          </div>
                          <div class="col-xs-12 bottom text-center">
                           
							<form method="post" action="add_details.php">
							<input type="hidden" name="partner_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="doc_id" value="<?php echo $getList['Ref_Id']; ?>" />
                            <div class="col-xs-12 col-sm-6 emphasis">
							<?php $getFavourDoc = $objQuery->mysqlSelect("*","add_favourite_doctor","doc_id='".$getList['Ref_Id']."' and user_id='".$admin_id."'","","","","");
							?>
                              <button type="submit" name="addFavour" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="bottom" title="Add to favourite" <?php if($getFavourDoc==true){ echo "disabled";} else { } ?>> <i class="fa fa-user-md">
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

										 
										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
						  
						  
						  
						  
              
					
                       
                      </div>
						 <?php } if(empty($_GET['f']) && empty($_GET['s'])) {?> 
						 
						  <!-- My connection Doctor listing-->
						  <div role="tabpanel" class="tab-pane fade active in" id="myConnections" aria-labelledby="home-tab">

								 <div class="clearfix"></div>

								<div class="row">
								  <div class="col-md-12">
									<div class="x_panel">
									  <div class="x_content">
										<div class="row">
										  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
											
										  </div>

										  <div class="clearfix"></div>

										  <?php 
					//get only mapped hospital doctors list			
					$getMappedFeature = $objQuery->mysqlSelect("DISTINCT(a.ref_id) as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$admin_id."'","a.doc_type_val asc","","","");
					
					
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

										 
										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
						  
						  
						  
						  
              
					
                       
                      </div>
						 
						 
						 <!-- Universal Doctor listing-->
                          <div role="tabpanel" class="tab-pane fade" id="universal" aria-labelledby="home-tab">
								 <div class="clearfix"></div>

								<div class="row">
								  <div class="col-md-12">
									<div class="x_panel">
									  <div class="x_content">
										<div class="row">
										  <div class="col-md-12 col-sm-12 col-xs-12 text-center">
											
										  </div>

										  <div class="clearfix"></div>

										  <?php 
					//get only mapped hospital doctors list			
					$getUniversal = $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.doc_type_val as Doc_Type,a.ref_exp as Ref_Exp,a.ref_address as Ref_Address,a.doc_state as Doc_State,a.doc_photo as Doc_Image,b.spec_name as Specialization","referal as a inner join specialization as b on b.spec_id=a.doc_spec inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","a.doc_type_val asc","","","0,50");
					
					
					if($getUniversal==true){
										
					foreach($getUniversal as $getList){ ?>
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

										 
										 
										</div>
									  </div>
									</div>
								  </div>
								</div>
              
					
                       
                      </div>
					  
					  
						 <?php } ?>
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