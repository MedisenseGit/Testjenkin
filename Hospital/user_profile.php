<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
              
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
                            <strong>Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							
							 <img alt="image" class="img-xlg img-responsive" src="hospital-icon.png">
                           
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $getCompanyProfile[0]['hosp_name']; ?></strong></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($getCompanyProfile[0]['hosp_addrs'])){ echo ", ".$getCompanyProfile[0]['hosp_addrs']; } ?></p>
                                <br>
                                <p>
								<i class="fa fa-phone"></i> <?php echo $getCompanyProfile[0]['hosp_contact']; ?><br>
								<i class="fa fa-envelope"></i> <?php echo $getCompanyProfile[0]['hosp_email']; ?>
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
                            <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
							
                                <div class="panel-body">
                               
                            <form method="post" class="form-horizontal" enctype="multipart/form-data" action="add_details.php" method="post"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">Hospital Name</label>

                                    <div class="col-sm-10"><input type="text" id="txtOrgName" name="txtOrgName" value="<?php echo $getCompanyProfile[0]['hosp_name']; ?>" class="form-control"></div>
                                
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Contact Person Name</label>

                                    <div class="col-sm-10"><input type="text" id="txtContactPerson" name="txtContactPerson" value="<?php echo $getCompanyProfile[0]['hosp_name']; ?>" class="form-control"></div>
                                
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtAddress" name="txtAddress" rows="3"><?php echo $getCompanyProfile[0]['hosp_addrs']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" id="txtMobile" name="txtMobile" value="<?php echo $getCompanyProfile[0]['hosp_contact']; ?>" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input id="txtEmail" name="txtEmail" value="<?php echo $getCompanyProfile[0]['hosp_email']; ?>" class="form-control"></div>
								
                                </div>
								
																
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_organization" id="edit_organization" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
							</form>
							
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane">
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                                
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
</body>

</html>
