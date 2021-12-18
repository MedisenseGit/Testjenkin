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
//


$get_diagnoInfo = mysqlSelect("*","Diagnostic_center","diagnostic_id='".$admin_id."'","","","","");
                
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
	
	<script>
	function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_name='+val,
	success: function(data){
		//$("#slctState").empty();
		$("#slctState").html(data);
	}
	});
}
	</script>
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
                        <!--<li>
                            <a href="Home">Home</a>
                        </li>-->
                       
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
                           
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $get_diagnoInfo[0]['diagnosis_name']; ?></strong></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($get_diagnoInfo[0]['diagnosis_city'])){ echo ", ".$get_diagnoInfo[0]['diagnosis_city'];} if(!empty($get_diagnoInfo[0]['diagnosis_state'])){ echo ", ".$get_diagnoInfo[0]['diagnosis_state']; } if(!empty($get_diagnoInfo[0]['diagnosis_country'])){ echo ", ".$get_diagnoInfo[0]['diagnosis_country']; } ?></p>
                              
								
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <!--<li><a href="Set-Appointment"><i class="fa fa-calendar"></i>Set Appointment Timing</a></li>-->
                            <li><a href="Password"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
							
                                <div class="panel-body">
                               
                           <form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal" id="frmAddDoctor">
                                <input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />
								
								<div class="form-group">
									<label class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-10"><input type="text" id="txtDoc" name="txtDoc" value="<?php echo $get_diagnoInfo[0]['diagnosis_name']; ?>" class="form-control"></div>
                                
									
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" id="txtMobile" name="txtMobile" value="<?php echo $get_diagnoInfo[0]['diagnosis_contact_num']; ?>" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input id="txtEmail" name="txtEmail" value="<?php echo $get_diagnoInfo[0]['diagnosis_email']; ?>" class="form-control"></div>
								
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Country </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="txtCountry"  tabindex="2" onchange="return getState(this.value);">
											<option value="<?php echo $get_diagnoInfo[0]['diagnosis_country']; ?>" selected><?php echo $get_diagnoInfo[0]['diagnosis_country']; ?></option>
												<?php 
												$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														
														$i = 30;
														foreach ($CntName as $CntNameList) {
														?> 
																								
															<option value="<?php echo stripslashes($CntNameList['country_name']); ?>" />
														<?php
															echo stripslashes($CntNameList['country_name']);
														?></option>
																										
														<?php
															$i++;
														}
														?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..."  name="slctState" id="slctState" tabindex="2" class="form-control">
											<option value="<?php echo $get_diagnoInfo[0]['diagnosis_state']; ?>" selected><?php echo $get_diagnoInfo[0]['diagnosis_state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id='100'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo $StateList["state_name"];	?>">
												<?php echo $StateList["state_name"]; ?>
												</option>												
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City </label>

                                    <div class="col-sm-10"><input type="text" name="se_city" value="<?php echo $get_diagnoInfo[0]['diagnosis_city']; ?>"  class="form-control"></div>
                                </div>
								
								
								
							
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_doctor" id="edit_doctor" class="btn btn-primary block full-width m-b ">UPDATE</button>
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
