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



/*$get_diagnoInfo = mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.company_id='".$admin_id."'","b.doc_diagno_id desc","","","");*/

$get_diagnoInfo = mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","","b.doc_diagno_id desc","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Diagnostic</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Add Diagnostic</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Add Diagnostic</strong>
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
								<?php } if($_GET['response']=="diagnostic-exists"){ ?>
								<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>This phone number or email already exists.</strong>
								 </div>
								<?php }?>
               
                <div class="col-md-12">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
							<?php include_once('settings_common_header.php'); ?>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
								

            <div class="ibox-content m-b-sm border-bottom">
			<form method="post" name="frmAddPayments" autocomplete="off" action="add_details.php">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Name</label>
                            <input type="text" id="diagno_name" name="diagno_name" value="" placeholder="Diagnostic Centre Name" required class="form-control typeahead_1">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="price">Email</label>
                            <input type="text" id="txtemail" name="txtemail" value="" placeholder="Email" required class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">Mobile</label>
                            <input type="text" id="mobile" name="mobile" value="" required placeholder="Mobile" class="form-control">
                        </div>
                    </div>
					<div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">City</label>
                            <input type="text" id="city" name="city" value=""  placeholder="City" class="form-control">
                        </div>
                    </div>
					<div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">Hospital</label>
                            <select data-placeholder="Choose Hospital..." class="form-control chosen-select" name="hosp" tabindex="2">
                                <option value="" selected>Choose Hospital</option>
                                <?php 
                                    // $getHospital= mysqlSelect("*","hosp_tab","company_id='".$admin_id."'","","","","");
                                    $getHospital= mysqlSelect("*","hosp_tab","","hosp_id desc","","","");
                                    foreach($getHospital as $HospList) 
                                    {?> 
                                                        
                                        <option value="<?php
                                    echo stripslashes($HospList['hosp_id']);
                                    ?>" />
                                    <?php
                                    echo stripslashes($HospList['hosp_name']);
                                    ?></option>

                                    <?php
                                   
                                    } ?>


                                             
                                </select>
                        </div>
                    </div>
                    
                    
					<div class="col-sm-1">
                        <div class="form-group" style="margin-top:5px;">
						 <label class="control-label" for="status" ></label>
                             <button type="submit" name="add_diagno" id="add_diagno" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
						</div>
                    </div>
                </div>
			</form>

            </div>

            <div class="row" id="allDiagnosis">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Diagnostics </th>
										<th>Email Id</th>
										<th>Mobile</th>
                                        <th>City</th>  
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									if(count($get_diagnoInfo)==0){
									?>
									<tr><td colspan="4" class="text-center">No records found</td></tr>	
									<?php
									}
									else
									{ 
									foreach($get_diagnoInfo as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><?php echo $list['diagnosis_name']; ?> </td>
										<td><?php echo $list['diagnosis_email']; ?></td>
                                        <td><?php echo $list['diagnosis_contact_num']; ?></td> 
										<td><?php echo $list['diagnosis_city']; ?></td>										
                                       <td><a href="javascript:void(0)" onclick="return delDiagnostics(<?php echo $list['doc_diagno_id']; ?>,<?php echo $list['diagnostic_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
										<i class="fa fa-trash-o"></i> DELETE</a></td>
                                    </tr>
                                    <?php }
									}
									?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
			<div id="afterDelDiagno"></div>

       
                               
                            
							
							
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
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
        </script>
</body>

</html>
