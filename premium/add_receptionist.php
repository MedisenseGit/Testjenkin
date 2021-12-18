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


$get_receptionInfo = mysqlSelect("*","receptionist_login","doc_id='".$admin_id."'","reception_id desc","","","");

if(isset($_GET['id'])){
$getReceptionist= mysqlSelect("*","receptionist_login","md5(reception_id)='".$_GET['id']."'","","","","");
	
}                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Receptionist</title>

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
                    <h2>Add Receptionist</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Add Receptionist</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">
			<?php if($_GET['response']=="created-success"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Created successfully </strong>
			</div>
			<?php } if($_GET['response']=="update-success"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Updated successfully </strong>
			</div>
			<?php } ?>
			
			<?php include_once('settings_common_header.php'); ?>		
              <form method="post"  action="add_details.php">
			  
			  <input type="hidden" name="reception_id" value="<?php echo $getReceptionist[0]['reception_id']; ?>" />
				
                <div class="row m-t">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" >Receptionist Name <span class="required">*</span></label>
                            <input type="text" id="receptionist_name" name="receptionist_name" value="<?php echo $getReceptionist[0]['reception_user']; ?>" placeholder="User Name" required class="form-control" autocomplete="off">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" >Mobile No. <span class="required">*</span></label>
                            <input type="text" id="receptionist_mobile" name="receptionist_mobile" value="<?php echo $getReceptionist[0]['receptionist_mobile']; ?>" placeholder="Mobile No." required class="form-control" autocomplete="off" minlength="10" maxlength="10">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" >Password <span class="required">*</span></label>
                            <input type="password" id="password" name="password" value="" placeholder="*******" required class="form-control" minlength="5">
                        </div>
                    </div>
                   <div class="col-sm-3">
                        <div class="form-group">
						 <label class="control-label" for="status" ></label>
						 <?php if(isset($_GET['id'])) { ?>
                             <button type="submit" name="update_receptionist" id="update_receptionist" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Update </button>
							<button type="submit" name="add_receptionist" id="add_receptionist" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add New</button>
						 
						 <?php } else { ?>
						 <button type="submit" name="add_receptionist" id="add_receptionist" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
						 <?php } ?>
						</div>
                    </div>
                   
					
                </div>
				
			</form>

            </div>

            <div class="row" id="allRecption">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Receptionist Name </th>
										<th>Mobile No. </th>
										<th>Password</th>
										
                                    </tr>
                                    </thead>
                                    <tbody>
									
									<?php 
									if(!empty($get_receptionInfo )){
									foreach($get_receptionInfo  as $list)
									{ 									
									?>
                                    <tr id="delete_reception_row<?php echo $list['reception_id'];?>">
                                       
                                        <td><strong><?php echo $list['reception_user']; ?></strong> </td>
										<td><strong><?php echo $list['receptionist_mobile']; ?></strong> </td>
										<td>********</td>
										<td><a href="Add-Receptionist?id=<?php echo md5($list['reception_id']); ?>" class="btn btn-danger btn-bitbucket btn-xs">
										 <i class="fa fa-pencil-square-o"></i> EDIT</a> | <a href="javascript:void(0)" data-row-id = "<?php echo $list['reception_id']; ?>" data-reception-id = "<?php echo md5($list['reception_id']); ?>" class="btn btn-danger btn-bitbucket btn-xs delete_reception">
										 <i class="fa fa-trash-o"></i> DELETE</a></td>
                                       
                                    </tr>
                                    <?php } 
									} else { 
									?>
									<tr>
                                       
                                        <td colspan="2" class="text-center">No record found </td>
										                                       
                                    </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
			<div id="afterDelReception"></div>
			
			<div class="row" id="allRecption">
                <div class="col-lg-6">
				 <h2>EMR Permission</h2>
                    <div class="ibox">
                        <div class="ibox-content" >
						
						<div class="row m-t">
                    <div class="col-sm-12">
                        <div class="form-group">
						<form method="post" name="frmEMRPermission" class="form-horizontal" action="add_details.php">
						<div class="row m-t m-b">
                            <label class="control-label" >Select Receptionist <span class="required">*</span></label>
                            <select class="form-control" name="selectReception" onchange="return getPermitTab(this.value);" required>
							<option value="">Select</option>
										<?php foreach($get_receptionInfo  as $list)
										{
										?>
										<option value="<?php echo $list['reception_id']; ?>" /><?php echo $list['reception_user']; ?></option>
										<?php
										}
										?>
							</select>
						</div>
						<div class="table-responsive m-t" id="beforePermit">
                                <table class="table table-striped" >
                                    <thead>
                                    <tr>

                                        <th>Sr. No.</th>
                                        <th>Feature Name </th>
										<th>Permission </th>
										
										
                                    </tr>
                                    </thead>
                                    <tbody>
										<tr><th>1</th><td>Chief medical complaint</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" disabled name="check_chief_medical">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="0" disabled name="check_chief_medical">
                                            <label for="inlineRadio2"> No </label>
                                        </div></td></tr>
										<tr><th>2</th><td>Examination</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio3" value="1" disabled name="check_exam" >
                                            <label for="inlineRadio3"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio4" value="0" disabled name="check_exam">
                                            <label for="inlineRadio4"> No </label>
                                        </div></td></tr>
										<tr><th>3</th><td>Investigations</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio5" value="1" disabled name="check_invest">
                                            <label for="inlineRadio5"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio6" value="0" disabled name="check_invest">
                                            <label for="inlineRadio6"> No </label>
                                        </div></td></tr>
										<tr><th>4</th><td>Diagnosis</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio7" value="1" disabled name="check_diagno" >
                                            <label for="inlineRadio7"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio8" value="0" disabled name="check_diagno">
                                            <label for="inlineRadio8"> No </label>
                                        </div></td></tr>
										<tr><th>5</th><td>Treatment Advise</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio9" value="1" disabled name="check_treatment" >
                                            <label for="inlineRadio9"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio10" value="0" disabled name="check_treatment">
                                            <label for="inlineRadio10"> No </label>
                                        </div></td></tr>
										<tr><th>6</th><td>Add Prescriptions</td><td><div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio11" value="1" disabled name="check_presc" >
                                            <label for="inlineRadio11"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio12" value="0" disabled name="check_presc">
                                            <label for="inlineRadio12"> No </label>
                                        </div></td></tr>
										
									
									</tbody>
									
								</table>
								
							</div>
							<div id="afterPermit"></div>
							<div class="row m-t">	
						<button type="submit" name="update_permission" id="update_permission" class="btn btn-outline btn-primary pull-right"><i class="fa fa-plus"></i> Update </button>
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
