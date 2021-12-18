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


$getHosp = mysqlSelect("*","hosp_tab","hosp_id='".$hosp_id."'","","","","");

                     
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
                    <h2>Hospital List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Hospital List</strong>
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
                        <h5><i class="fa fa-calendar"></i> EDIT HOSPITAL</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <input type="hidden" name="Hosp_Id" value="<?php echo $getHosp[0]['hosp_id']; ?>" />
							   <div class="form-group">
									<label class="col-sm-2 control-label">Hospital Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtHospName" value="<?php echo $getHosp[0]['hosp_name']; ?>" class="form-control"></div>
                                
								</div>
								<script type="text/javascript">
									function getState(val) { 
										$.ajax({
										type: "POST",
										url: "get_state.php",
										data:'country_name='+val,
										success: function(data){
											$("#slctState").html(data);
										}
										});
									}
								</script>

								
							   <div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="txtCountry"  tabindex="2"  onchange="return getState(this.value); ">
										<!--<option value="<?php echo $getHosp[0]['hosp_country']; ?>" selected ><?php echo $getHosp[0]['hosp_country']; ?></option>-->
											<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php if($getHosp[0]['hosp_country'] == stripslashes($CountryList['country_name'])){?>selected<?php }?>/>
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
							</select>
									</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="form-control autotab" required="required" name="slctState" id="slctState" tabindex="2">
											<option value="<?php echo $getHosp[0]['hosp_state']; ?>" selected><?php echo $getHosp[0]['hosp_state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$getHosp[0]['hosp_country']."'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo $StateList["state_name"];?>"><?php
													echo $StateList["state_name"];?>
														
												</option>
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Suburb <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtSuburb" value="<?php echo $getHosp[0]['hosp_suburb']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" value="<?php echo $getHosp[0]['hosp_city']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtAddress" rows="3"><?php echo $getHosp[0]['hosp_addrs']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Hospital Overview</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtOverview" rows="3"><?php echo $getHosp[0]['hosp_overview']; ?></textarea></div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Hospital Contact Person <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtPerson" value="<?php echo $getHosp[0]['hosp_contact_name']; ?>" class="form-control"></div>
                                
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Hospital Phone No. <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" class="form-control" name="txtMobile" value="<?php echo $getHosp[0]['hosp_contact']; ?>" ></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" value="<?php echo $getHosp[0]['hosp_email']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID1</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail1" value="<?php echo $getHosp[0]['hosp_email1']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID2</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail2" value="<?php echo $getHosp[0]['hosp_email2']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID3</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail3" value="<?php echo $getHosp[0]['hosp_email3']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID4</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail4" value="<?php echo $getHosp[0]['hosp_email4']; ?>" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Communication Status <span class="required">*</span></label>

                                    <div class="col-sm-6"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="slctComm" id="slctComm">
											<option value="" selected>---Please Select---</option>
											<?php if($getHosp[0]['communication_status']==1){ 
												?>
														<option value="1" selected>Only to doctor</option>
														<option value="2">Only to Hospital </option>
														<option value="3">Both Hospital & Doctor</option>
												
											<?php } else if($getHosp[0]['communication_status']==2) { ?>
											<option value="1" >Only to doctor</option>
														<option value="2" selected>Only to Hospital </option>
														<option value="3">Both Hospital & Doctor</option>
											<?php } else if($getHosp[0]['communication_status']==3) { ?>
											<option value="1" >Only to doctor</option>
														<option value="2" >Only to Hospital </option>
														<option value="3" selected>Both Hospital & Doctor</option>
											<?php } else { ?>
														<option value="0"selected>Communication</option>
														<option value="1">Only to doctor</option>
														<option value="2">Only to Hospital </option>
														<option value="3">Both Hospital & Doctor</option>
											<?php } ?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Re-Visit Charge </label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="txtrevisitcharge" value="<?php echo $getHosp[0]['revisit_charge']; ?>"></div>
									<label class="col-sm-2 control-label">New-Visit Charge</label>

                                    <div class="col-sm-4"><input type="text" name="txtnewvisitcharge" value="<?php echo $getHosp[0]['newvist_charge']; ?>" class="form-control"></div>
								
                                </div>		
								
                               
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="edit_hospital" class="btn btn-primary block full-width m-b ">EDIT</button>
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