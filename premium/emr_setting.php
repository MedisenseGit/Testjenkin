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

    <title>User Settings</title>

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
                    <h2>EMR Settings</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>EMR Settings</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
			<?php  if($_GET['response']=="updated-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>EMR Settings has been updated successfully </strong>
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
					<code>NOTE: To preload a template, goto EMR page for a patient, then add list of tests/examinations and create a template.</code>
				</div>
				</div>
				<div class="row">
                    <div class="col-sm-12 m-t">
                        <div class="form-group col-sm-12 m-t">
                           
							<div class="form-group"><label class="col-sm-4 control-label">1. Preload an examination template</label>

                                    <div class="col-sm-8">
									
										<select  class="chosen-select" name="slctExamTemp[]" multiple style="width:350px;" tabindex="4">
										<?php $getExamTemaplate= mysqlSelect("*","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1'","","","","");
												
												foreach($getExamTemaplate as $TempList){
													
													if($TempList['default_visible']=="1"){ ?> 
												<option value="<?php echo stripslashes($TempList['exam_template_id']);?>" selected /><?php echo stripslashes($TempList['template_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($TempList['exam_template_id']);?>" /><?php echo stripslashes($TempList['template_name']);?></option>
												<?php
														
												}?>
											</select>
										
										
										</div>
										
										
										
										
                                </div>		
							</div>
							
							<div class="form-group col-sm-12 m-t">
                           
								<div class="form-group"><label class="col-sm-4 control-label">2. Preload an Lab Investigation template</label>

                                    <div class="col-sm-8">
									
										<select class="chosen-select" name="slctInvestTemp[]" multiple style="width:350px;" tabindex="4">
										<?php $getInvestTemaplate= mysqlSelect("*","doc_patient_episode_investigations_templates","doc_id='".$admin_id."' and doc_type='1'","","","","");
												
												foreach($getInvestTemaplate as $InvestList){
													
													if($InvestList['default_visible']=="1"){ ?> 
												<option value="<?php echo stripslashes($InvestList['invest_template_id']);?>" selected /><?php echo stripslashes($InvestList['template_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($InvestList['invest_template_id']);?>" /><?php echo stripslashes($InvestList['template_name']);?></option>
												<?php
														
												}?>
											</select>
										
										
										</div>
										
										
										
										
                                </div>
									
							</div>
							
							<div class="form-group col-sm-12 m-t">
                           
								<div class="form-group"><label class="col-sm-4 control-label">3. Prescription Format</label>

                                    <div class="col-sm-8">
									<?php if($checkSetting[0]['prescription_template']=="0"){ ?>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="0" class="prescDefaultTempShow" name="presc_format" checked>
                                            <label for="inlineRadio1"> Default </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="1" class="prescCustomTempShow" name="presc_format">
                                            <label for="inlineRadio2"> Custom </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="0" class="prescDefaultTempShow" name="presc_format">
                                            <label for="inlineRadio1"> Default </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="1" class="prescCustomTempShow" name="presc_format" checked>
                                            <label for="inlineRadio2"> Custom </label>
                                        </div>
									<?php } ?>
										
										
										</div>
										
										
										
										
                                </div>
									<div id="precTempShow">
										<div class="form-group col-sm-12">
										<?php if($checkSetting[0]['prescription_template']=="0"){ $imageSrc="images/Default_presc_template.jpg"; } else if($checkSetting[0]['prescription_template']=="1"){ $imageSrc="images/Default_presc_template1.jpg"; }?>
										<img src="<?php echo $imageSrc; ?>" />
										
										</div>
									</div>
									
									<div class="form-group col-sm-12" >
										<div id="prescDefaultTempShow" style="display:none;"><img src="images/Default_presc_template.jpg" /></div>
										<div id="prescCustomTempShow" style="display:none;"><img src="images/Default_presc_template1.jpg" /></div>
									</div>
									
									
							
                    </div>
					<div class="col-sm-12">
                        <div class="form-group">
						 <label class="control-label" for="status" style="margin-top:40px;"></label>
                             <button type="submit" name="update_emr_settings"  class="btn btn-outline btn-primary">Save Changes </button>
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
				
         
            $('.prescDefaultTempShow').click(function() {
				$("#prescDefaultTempShow").toggle();
				$("#prescCustomTempShow").hide();
				$("#precTempShow").hide();
			});
			
			$('.prescCustomTempShow').click(function() {
				$("#prescDefaultTempShow").hide();
				$("#prescCustomTempShow").show();
				$("#precTempShow").hide();
			});
 
            });
        </script>
			
</body>

</html>
