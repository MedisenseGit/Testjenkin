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
//$objQuery = new CLSQueryMaker();


$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
$docDetails= mysqlSelect("contact_num","referal","ref_id='".$admin_id."'","","","","");
	               
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Other Settings</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	<link href="../assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Other Settings</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Other Settings</strong>
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
               
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <?php include_once('settings_common_header.php'); ?>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
								

            <div class="ibox-content m-b-sm border-bottom">
			<form enctype="multipart/form-data" method="post" name="frmAddOption" autocomplete="off" action="add_details.php">
                <div class="row">
                    <div class="col-sm-12 m-t">
                        <div class="form-group col-sm-12">
                            <label class="control-label" for="product_name">Payment Section  </label>
									<?php if($checkSetting[0]['payment_opt']=="1"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="pay_option" checked>
                                            <label for="inlineRadio1"> Enable </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="pay_option">
                                            <label for="inlineRadio2"> Disable </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="pay_option">
                                            <label for="inlineRadio1"> Enable </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="pay_option" checked>
                                            <label for="inlineRadio2"> Disable </label>
                                        </div>
									<?php } ?>
									
							</div>
							<div class="form-group col-sm-12">
                            <label class="control-label" for="product_name">Do you have pre printed letter head prescription pad?  </label>
									<?php if($checkSetting[0]['prescription_pad']=="1"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" class="prescEnable" value="1" name="prescription_pad" checked>
                                            <label for="inlineRadio3"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio4" value="2" class="prescDisable" name="prescription_pad">
                                            <label for="inlineRadio4"> No </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" value="1" class="prescEnable" name="prescription_pad">
                                            <label for="inlineRadio3"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio4" value="2" class="prescDisable" name="prescription_pad" checked>
                                            <label for="inlineRadio4"> No </label>
                                        </div>
									<?php } ?>
									
							</div>
							<div class="form-group col-sm-12" id="presc_pad_setting" <?php if($checkSetting[0]['prescription_pad']=="1"){ echo ""; } else { echo "style='display:none;'"; }  ?>>
											<label class="col-sm-2 control-label" for="date_added">Spacing<br>Header Height?(in cm) <span class="required">*</span></label>

											<div class="col-sm-3"><div class="input-group date">
												<input type="text" name="header_height" value="<?php echo $checkSetting[0]['presc_pad_header_height']; ?>"  class="form-control" tabindex="4">
											</div>
											</div>
											
											<label class="col-sm-2 control-label" for="date_added">Spacing<br>Footer Height?(in cm) <span class="required">*</span></label>

											<div class="col-sm-3"><div class="input-group date">
												<input type="text" name="footer_height" value="<?php echo $checkSetting[0]['presc_pad_footer_height']; ?>"  class="form-control" tabindex="4">
											</div>
											</div>
							</div>
						
							<div class="form-group col-sm-12">
                            <label class="control-label" for="product_name">Do you collect consultation fee before consultation?  </label>
									<?php if($checkSetting[0]['before_consultation_fee']=="1"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" name="consultation_before" checked>
                                            <label for="inlineRadio5"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="2" name="consultation_before">
                                            <label for="inlineRadio6"> No </label>
                                        </div>
										
										
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" name="consultation_before">
                                            <label for="inlineRadio5"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="2" name="consultation_before" checked>
                                            <label for="inlineRadio6"> No </label>
                                        </div>
									<?php } ?>
								
																	
							</div>
							<div id="logoSection" <?php if($checkSetting[0]['prescription_pad']=="1"){ echo "style='display:none;'"; } else { echo ""; }  ?>>
								<div class="form-group col-sm-12">
								<label class="control-label" for="product_name">Logo to be appear in printed prescription pad <small>(Preferred size 120px x 120px)</small> </label> 
								
							</div>
							<div class="form-group col-sm-12" >
							<?php if(!empty($checkSetting[0]['doc_logo'])){ ?>
								<div class="col-sm-3">
									<img src="docLogo/<?php echo $admin_id;?>/<?php echo $checkSetting[0]['doc_logo']; ?>"  width="120"/>
								</div>
								<?php } ?>
								<div class="col-sm-3">
								<div class="fileinput fileinput-new" data-provides="fileinput">
											<span class="btn btn-default btn-file"><span class="fileinput-new">Change Logo</span><span class="fileinput-exists">Change</span><input type="file" name="txtLogo"></span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										</div>
								</div>
							</div>
							</div>
							
							<div class="form-group col-sm-12">
							<label class="control-label" for="product_name">Type flash message to be printed below prescription </label> 
							</div>
							<div class="form-group col-sm-12">
							
							<div class="col-sm-5"><textarea class="form-control" placeholder="<?php echo "Ex: For appointments call ".$docDetails[0]['contact_num'];?>" name="docFlashMsg" rows="3"><?php echo $checkSetting[0]['doc_flash_msg']; ?></textarea></div>
							</div>
							
						<div class="form-group col-sm-12">
                            <label class="control-label" for="product_name">How do you want to enter patient's age details? </label>
									<?php if($checkSetting[0]['patient_age_type']=="1"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" name="patient_age_type" checked>
                                            <label for="inlineRadio7"> By DOB(DD/MM/YYYY) </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="0" name="patient_age_type">
                                            <label for="inlineRadio8"> By Age </label>
                                        </div>
										
										
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" name="patient_age_type">
                                            <label for="inlineRadio7"> By DOB(DD/MM/YYYY) </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="0" name="patient_age_type" checked>
                                            <label for="inlineRadio8"> By Age </label>
                                        </div>
									<?php } ?>
								
																	
							</div>
							
                    </div>
					<div class="col-sm-12">
                        <div class="form-group">
						 <label class="control-label" for="status" style="margin-top:40px;"></label>
                             <button type="submit" name="update_settings"  class="btn btn-outline btn-primary">Save Changes </button>
						</div>
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
	<!-- Jasny -->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
				
         
            $('.prescEnable').click(function() {
				$("#presc_pad_setting").toggle();
				$("#logoSection").hide();
			});
			
			$('.prescDisable').click(function() {
				$("#presc_pad_setting").hide();
				$("#logoSection").show();
			});
 
            });
        </script>
			
</body>

</html>
