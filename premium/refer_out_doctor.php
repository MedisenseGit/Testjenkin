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


$get_docInfo = mysqlSelect("*","doctor_out_referral","doc_id='".$admin_id."'","doctor_name asc","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Outward referrals</title>

   <?php include_once('support.php'); ?>
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Outward referrals</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Outward referrals</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
			<?php if($_GET['response']=="created-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Outward referrals created successfully </strong>
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
			<form method="post" name="frmAddPayments" autocomplete="off" action="add_details.php">
				<div class="row">
				<div class="col-sm-12 m-t">
					<code>NOTE: Add doctors who you want to refer cases to</code>
				</div>
				</div><br>
			   <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Name</label>
                            <input type="text" id="diagno_name" name="doc_name" value="" placeholder="Doctor Name" required class="form-control" tabindex="1">
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Specialization</label>
                            <select data-placeholder="Choose a Specialization..." class="form-control" name="slctSpec" tabindex="2">
							<option value="" />Select Specialization</option>
										<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												
												foreach($DeptName as $DeptList){ ?>
													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php } ?>
											</select>
                        </div>
                    </div>
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="price">Email</label>
                            <input type="text" id="txtemail" name="txtemail" value="" placeholder="Email" class="form-control" tabindex="3">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">Mobile</label>
                            <input type="text" id="mobile" name="mobile" value="" placeholder="Mobile" class="form-control" tabindex="4">
                        </div>
                    </div>
					
                    
					
                </div>
				<div class="row">
					<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="price">City</label>
                            <input type="text" id="city" name="city" value=""  placeholder="City" class="form-control" tabindex="5">
                        </div>
                    </div>
					<div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="price">Address</label>
                            <textarea id="address" name="address" placeholder="Address" class="form-control" rows="3" tabindex="6"></textarea>
                        </div>
                    </div>
					<div class="col-sm-1">
                        <div class="form-group" style="margin-top:5px;">
						 <label class="control-label" for="status" ></label>
                             <button type="submit" name="add_referout_doctor" id="add_referout_doctor" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
						</div>
                    </div>
				
				</div>
			</form>

            </div>

            <div class="row" id="allDoctors">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Doctor </th>
										<th>Email Id</th>
										<th>Mobile</th>
                                        <th>City</th>  
										<th>Address</th> 
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									if(count($get_docInfo)==0){
									?>
									<tr><td colspan="4" class="text-center">No records found</td></tr>	
									<?php
									}
									else
									{ 
									while(list($key, $val) = each($get_docInfo))
									{ 	
									$get_docSpec = mysqlSelect("*","specialization","spec_id='".$val['doc_specialization']."'","","","","");	
									?>
                                    <tr >
                                       
                                        <td><?php echo $val['doctor_name']."<br><small>".$get_docSpec[0]['spec_name']."</small>"; ?> </td>
										<td><?php echo $val['doctor_email']; ?></td>
                                        <td><?php echo $val['doctor_mobile']; ?></td> 
										<td><?php echo $val['doctor_city']; ?></td>		
										<td><?php echo $val['doc_address']; ?></td>											
                                       	<td><a href="javascript:void(0)" href="javascript:void(0)" onclick="return delDoctor(<?php echo $val['doc_out_ref_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
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
			<div id="afterDelDoctor"></div>

       
                               
                            
							
							
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
