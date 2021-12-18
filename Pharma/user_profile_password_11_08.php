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



$get_docInfo = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'","","","","");
$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$admin_id."'","","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Your Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
			<?php if($_GET['response']=="update"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Your profile has been updated successfully </strong>
								 </div>
								<?php }  if($_GET['response']=="password"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Password has been updated successfully </strong>
								 </div>
								<?php } if($_GET['response']=="error-password"){ ?>
								<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Password mismatch, please try again </strong>
								 </div>
								<?php } ?>
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							<?php if(!empty($get_docInfo[0]['doc_photo'])){ ?>
                                <img alt="image" class="img-xlg img-responsive" src="../Doc/<?php echo $get_docInfo[0]['ref_id']; ?>/<?php echo $get_docInfo[0]['doc_photo']; ?>">
                             <?php }  else { ?>
							 <img alt="image" class="img-xlg img-responsive" src="../assets/img/anonymous-profile.png">
                             <?php  } ?>
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $get_docInfo[0]['ref_name']; ?></strong><br><br><?php echo $get_docInfo[0]['spec_name']; ?></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($get_provHospInfo[0]['hosp_name'])){ echo $get_provHospInfo[0]['hosp_name']; } if(!empty($get_docInfo[0]['doc_city'])){ echo ", ".$get_docInfo[0]['doc_city'];} if(!empty($get_docInfo[0]['doc_state'])){ echo ", ".$get_docInfo[0]['doc_state']; } if(!empty($get_docInfo[0]['doc_country'])){ echo ", ".$get_docInfo[0]['doc_country']; } ?></p>
                                <br><h4><strong>
                                    About <?php echo $get_docInfo[0]['ref_name']; ?>
                                </strong></h4>
                                <p><?php if(!empty($get_docInfo[0]['ref_exp'])){ ?><b>Exp:</b> <?php echo $get_docInfo[0]['ref_exp']; ?> Yrs<br><?php } ?>
								   
								  <!-- start Interest/Expertise -->
								<?php if(!empty($get_docInfo[0]['doc_interest'])){ ?>
								 <br><b>Area's of Interest/Expertise</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_interest']); ?><br>
								  <?php } ?>
								 <!-- end of Interest/Expertise -->
								 
								 <?php if(!empty($get_docInfo[0]['doc_contribute'])){ ?>
								 <br><b>Professional Contribution</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_contribute']); ?><br>
								 <?php } ?>
								<!-- end of Contribution -->
								
								 <?php if(!empty($get_docInfo[0]['doc_pub'])){ ?>
								 <br><b>Publications</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_pub']); ?><br>
								 <?php } ?>
								<!-- end of Publications -->
								
								
                                </p>
                              
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li><a href="Profile"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <li><a href="Set-Appointment"><i class="fa fa-calendar"></i>Set Appointment Timing</a></li>
                            <li class="active"><a href="#"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            	
							 <div class="tab-pane active">
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
									<input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />
								<div class="form-group">
									<label class="col-sm-2 control-label">New Password <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="password" id="new_password" name="new_password" required="required" class="form-control"></div>
                                
									
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Retype Password <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="password" id="retype_password" name="retype_password" required="required" class="form-control"></div>
                                
									
                                </div>
								
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="change_password" id="change_password" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
							</form>
							</div>
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

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>
 <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
    <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
</body>

</html>
