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

//Check Doctor response should go to partner / patient directly
$getPartnerRespSetting = $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","","");
					             
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Profile Settings</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../Hospital/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../Hospital/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../Hospital/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../Hospital/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Profile Settings</h3>
              </div>

              
            </div>
			
            <div class="clearfix"></div>
          
			<?php
						if($_GET['response']=="update"){ ?>
						<div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <strong>Updated Successfully !!</strong>
                  </div>
						
						<?php 
						} ?>	
            <div class="row">
             
              <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                  
                  <div class="x_content">
                    <br />
                    <form method="post" mame="frmSetting" action="add_details.php" class="form-horizontal form-label-left" >
					<input type="hidden" name="partner_id" value="<?php echo $admin_id; ?>" >
                      

                      <div class="form-group">
					  <h4>Setting for mail back</h4>
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">
                         
                          <small class="text-navy">Responses to queries should go to </small>
                        </label>
					
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <?php if($getPartnerRespSetting[0]['resp_query_setting']==0) { ?>
                          <div class="radio">
                            <label>
                              <input type="radio" class="flat" checked name="iCheck" value="0"> Me only
                            </label>
                          </div>
						  <div class="radio">
                            <label>
                              <input type="radio" class="flat" name="iCheck" value="1"> To patient directly with a copy to me
                            </label>
                          </div>
						  <?php } else if($getPartnerRespSetting[0]['resp_query_setting']==1){ ?>
                          <div class="radio">
                            <label>
                              <input type="radio" class="flat" name="iCheck" value="0"> Me only
                            </label>
                          </div>
						  <div class="radio">
                            <label>
                              <input type="radio" class="flat" checked name="iCheck" value="1"> To patient directly with a copy to me
                            </label>
                          </div>
						  <?php } ?>
                        </div>
						
                      </div>
                      <!--<div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Switch</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <div class="">
                            <label>
                              <input type="checkbox" class="js-switch" checked /> Checked
                            </label>
                          </div>
                          <div class="">
                            <label>
                              <input type="checkbox" class="js-switch" /> Unchecked
                            </label>
                          </div>
                          <div class="">
                            <label>
                              <input type="checkbox" class="js-switch" disabled="disabled" /> Disabled
                            </label>
                          </div>
                          <div class="">
                            <label>
                              <input type="checkbox" class="js-switch" disabled="disabled" checked="checked" /> Disabled Checked
                            </label>
                          </div>
                        </div>
                      </div>-->


                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                          
                          <button type="submit" name="updateMailNotification" class="btn btn-success">UPDATE</button>
                        </div>
                      </div>

                    </form>
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
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../Hospital/vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../Hospital/vendors/moment/min/moment.min.js"></script>
    <script src="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../Hospital/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../Hospital/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../Hospital/vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../Hospital/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../Hospital/vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../Hospital/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../Hospital/vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../Hospital/vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../Hospital/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../Hospital/vendors/starrr/dist/starrr.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
	
  </body>
</html>
