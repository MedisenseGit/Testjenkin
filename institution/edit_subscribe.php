<?php
ob_start();
error_reporting(0); 
session_start();

$id=$_GET['id'];
$scid=$_GET['scid'];
$company_code=$_GET['company_code'];

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


$getSubs = mysqlSelect("*","subscribers","md5(id)='".$id."'","","","","");
//var_dump($getHosp); exit;

                     
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
                    <h2>Subscribers List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Subscribers List</strong>
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
                        <h5><i class="fa fa-calendar"></i> EDIT SUBSCRIBER</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <input type="hidden" name="Subsid" value="<?php echo $getSubs[0]['id']; ?>" />
                                <input type="hidden" name="scid" value="<?php echo $scid; ?>" />
							   <div class="form-group">
									<label class="col-sm-2 control-label">Subscriber Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtSubsName" value="<?php echo $getSubs[0]['employee_name']; ?>" class="form-control"></div>
                                
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Employee ID <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtemplId" required="required" class="form-control" value="<?php echo $getSubs[0]['employee_id']; ?>"></div>
                                
								</div>
								 <div class="form-group">
									<label class="col-sm-2 control-label">Subscribe Company Code <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtSubsCompCode" value="<?php echo $company_code ?>" class="form-control" readonly></div>
                                
								</div>
								
							   <div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="txtCountry"  tabindex="2">
										<!--<option value="<?php echo $getSubs[0]['hosp_country']; ?>" selected ><?php echo $getSubs[0]['country']; ?></option>-->
											<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php if($getSubs[0]['country'] == stripslashes($CountryList['country_name'])){?>selected<?php }?>/>
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
							</select>
									</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="slctState" id="slctState" tabindex="2">
											<option value="<?php echo $getSubs[0]['state']; ?>" selected><?php echo $getSubs[0]['state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getSubs[0]['country']."'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
																									<option value="<?php
													echo $StateList["state_name"];
												?>"><?php
													echo $StateList["state_name"];
												?></option>
																									<?php
												}
												?>
										</select>
									</div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" value="<?php echo $getSubs[0]['city']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtAddress" rows="3"><?php echo $getSubs[0]['address']; ?></textarea></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Subscribe Phone No. <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" class="form-control" name="txtMobile" value="<?php echo $getSubs[0]['mobile_num']; ?>" ></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" value="<?php echo $getSubs[0]['email_id']; ?>" class="form-control"></div>
								</div>
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="edit_subscribe" class="btn btn-primary block full-width m-b ">EDIT</button>
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