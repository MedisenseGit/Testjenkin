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


$busResult = mysqlSelect("*","subscribing_company","","","","","");


                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Corporates List</title>

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
                    <h2>Corporates List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Corporates List</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Corporates List</h5>
                        
                    </div>
                    <div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                               <th style="width:300px;">Corporates</th>
							   <th style="width:50px;">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach($busResult as $list){ ?>
                            <tr>
                                <td><a href="Subscribes?company_code=<?php echo $list['company_code'];?>&scid=<?php echo md5($list['id']); ?>"><?php echo $list['company_name'].', '.$list['company_code'].', '.$list['address'];  ?></a></td> 
                                       <td><a href="Edit-Subscribe-Company?id=<?php echo md5($list['id']); ?>"  class="btn btn-white btn-bitbucket">
                      <i class="fa fa-edit"></i></a></td>	<!--onclick="return showHospital(<?php echo $list['Hosp_Id']; ?>);" -->										
						</tr>
                           <?php }  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins" id="addHospSection">
						<?php
						if($_GET['response']=="add"){ ?>
						<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>SUCCESS !! Details are added successfully.</strong>
						</div>
						<?php 
						} else if($_GET['response']=="update"){ ?>
						<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>UPDATED!! Details are updated successfully.</strong>
						</div>
						<?php } else if($_GET['response']=="error"){ ?>
						<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>Error!!! please fill required field properly.</strong>
						</div>
						<?php } ?>
						 <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> ADD CORPORATES</h5>
                       
                    </div>
                       
                       
                  
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <div class="form-group">
									<label class="col-sm-2 control-label">Subscribe Company Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtcompany_name" required="required" class="form-control"></div>
                                
								</div>
                                  <div class="form-group">
                                    <label class="col-sm-2 control-label"> Company Code <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtcompany_code" required="required" class="form-control"></div>
                                
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Contact Number <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" class="form-control" name="txtMobile" required="required"></div>
                                        
                                </div>
                                <div class="form-group">
                                <label class="col-sm-2 control-label">Email Address <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" required="required" class="form-control"></div>
                                </div>
								
                                 <div class="form-group">
                                    <label class="col-sm-2 control-label"> Subscription Start Date  <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="Date" name="txtsubscription_start_date" required="required" class="form-control"></div>
                                
                                </div>
                                 <div class="form-group">
                                    <label class="col-sm-2 control-label"> Subscription End Date  <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="Date" name="txtsubscription_end_date" required="required" class="form-control"></div>
                                
                                </div>

                                <div class="form-group"><label class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtAddress" rows="3"></textarea></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Number of Employees  <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="Number" name="txtnum_employees" required="required" class="form-control"></div>
                                
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"> Number of Dependants  <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="Number" name="txtnum_dependants" required="required" class="form-control"></div>
                                
                                </div>
                               
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="add_company_subscribe" class="btn btn-primary block full-width m-b ">ADD</button>
								</div>
								</div>
							</form>
							</div>
													
                    </div>
                </div>
				
				<!-- EDIT HOSPITAL SECTION -->
				<div id="editContent"></div>
				
            </div>
            </div>
                       
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
</body>

</html>
