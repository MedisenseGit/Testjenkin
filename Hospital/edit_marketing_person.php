<?php
ob_start();
error_reporting(0); 
session_start();

$hosp_id=$_GET['hosp_id'];

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");


 

if(isset($_POST['cmdGetId'])){
	$bus_id = $_POST['user_id'];
	$_SESSION['trans_id']=$_POST['user_id'];
	header('location:view');	
}

$get_personInfo = mysqlSelect("*","hosp_marketing_person","person_id='".$_GET['person_id']."'","","","","");

                     
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

 <!-- BEGIN HEAD -->
<head>
     <meta charset="UTF-8" />
    <title></title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
    <!-- GLOBAL STYLES -->
	<?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

	
</head>
     <!-- END HEAD -->
     <!-- BEGIN BODY -->
<body>
<div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Marketing Persons List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Marketing Persons List</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			 <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">									
				<div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> EDIT DETAILS</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                               <input type="hidden" name="Person_Id"	value="<?php echo $_GET['person_id']; ?>" />
							   <div class="form-group"><label class="col-sm-2 control-label">Select Hospital <span class="required">*</span></label>

                                    <div class="col-sm-10">
									 <select class="form-control autotab" name="selectHosp" id="selectHosp" placeholder="State"  >
												<?php
													$HospName= mysqlSelect("*","hosp_tab","hosp_id='".$admin_id."'","hosp_id desc","","","");
													$i=30;
														foreach($HospName as $HospList){
															if($HospList['hosp_id']==$get_personInfo[0]['hosp_id']){ 
																?>
														   <option value="<?php echo stripslashes($HospList['hosp_id']);?>" selected>
															<?php echo stripslashes($HospList['hosp_name'])."&nbsp;".$HospList['hosp_city'];?></option>
															<?php } else{ ?>
															<option value="<?php echo stripslashes($HospList['hosp_id']);?>" />
															<?php echo $HospList['hosp_name']."&nbsp;".$HospList['hosp_city']; ?></option>												
														
															<?php }	$i++;
														}?>
									</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Name </label>

                                     <div class="col-sm-10"><input type="text" name="person_name" value="<?php echo $get_personInfo[0]['person_name']; ?>" class="form-control"></div>
                                
								</div>	
                                <div class="form-group">
									<label class="col-sm-2 control-label">Mobile No. </label>

                                     <div class="col-sm-10"><input type="text" name="person_mobile" value="<?php echo $get_personInfo[0]['person_mobile']; ?>" class="form-control"></div>
                                
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Email Id </label>

                                    <div class="col-sm-10"><input type="text" name="person_email" value="<?php echo $get_personInfo[0]['person_email']; ?>" class="form-control"></div>
                                
								</div>
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<a href="Add-Marketing-Persons"><button type="button" name="cancel" class="btn btn-success block full-width m-b ">CANCEL</button></a>
								<button type="submit" name="edit_person" class="btn btn-primary block full-width m-b ">UPDATE</button>
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
     <!-- END BODY -->
</html>		