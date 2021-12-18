<?php
	ob_start();
	error_reporting(0);
	session_start();
	$admin_id = $_SESSION['user_id'];

	if(empty($admin_id)){
		header("Location:index.php");
	}
	require_once("../classes/querymaker.class.php");
	$objQuery = new CLSQueryMaker();

	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<title>Create New Appointment</title>
		<?php include('support_file.php'); ?>
		<script src="../Hospital/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="../Hospital/date-time-picker.min.js"></script>
	<script>
	function printContent(el){
		var restorepage=document.body.innerHTML;
		var printcontent=document.getElementById(el).innerHTML;
		document.body.innerHTML=printcontent;
		window.print();
		document.body.innerHTML=restorepage;
		
	}
	</script>
	<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#se_state").html(data);
	}
	});
}

function getDocTiming(val) {
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val,
	success: function(data){
		$("#check_time").html(data);
	}
	});
}

</script>
	</head>

	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<?php include_once('side_menu.php'); ?>

				<!-- page content -->
				<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						
						<div class="right">
							<div class="form-group pull-right top_search">
								<div class="input-group">
									<a href="My-Patient-List" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> BACK</a>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>

					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="x_panel">
								
								<div class="x_content">
									
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
									
									<div class="col-md-6 col-sm-6 col-xs-12 text-right" style="margin-top:10px; float:right;">
														<button type="submit" name="ref_appointment" id="ref_appointment" class="btn btn-primary"><i class="fa fa-floppy-o"></i> CREATE APPOINTMENT</button>
														
													</div>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<!-- start of user-activity-graph -->
										<!--<div id="graph_bar" style="width:100%; height:280px;"></div>-->
										<!-- end of user-activity-graph -->
										<h2> Create New Appointment</h2>
										<hr>
										<div class="row" id="manage_profile_tab" aria-labelledby="add-edit-profile-tab">
											<div class="form-group">
												<div class="col-md-4 col-sm-4 col-xs-12">
													Choose Preferred Date <span class="required">*</span>
													<select class="form-control" name="check_date" id="check_date" required="required" onchange="return getDocTiming(this.value);">
														<option disabled="disabled" selected value="0">Select Date</option>
                                     <?php 										 
										for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","ref_doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name']){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            echo date('D d-m-Y', $date);
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
										 
									
									 ?>



									   </select>
												 
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Choose Preferred Time <span class="required">*</span>
													<select class="form-control" name="check_time" id="check_time" required="required" onchange="return setTime(this.value);">
														
													</select>
												</div>
											</div><br>
											<div class="form-group">
												<div class="col-md-4 col-sm-4 col-xs-12">
													Patient Name <span style="color:red; font-size=42px;">*</span>
													<input type="text" name="se_pat_name" id="se_pat_name" required="required" class="form-control" placeholder="Patient Name *" value="<?php echo $patient_tab[0]['patient_name'] ?>">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Age 
													<input type="text" name="se_pat_age" id="se_pat_age"required="required" class="form-control" placeholder="Age *" value="<?php echo $patient_tab[0]['patient_age'] ?>">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
														Gender
														<div class="radio">
															<label><input type="radio" name="se_gender" checked="checked" value="1" >Male</label>
															<label><input type="radio" name="se_gender" value="2"  >Female</label>
														</div>
												</div>
												
											</div>
											<br>
											<div class="form-group">
													<div class="col-md-4 col-sm-4 col-xs-12">
													Contact Number <span style="color:red; font-size=42px;">*</span>
													<input type="text" id="se_phone_no" name="se_phone_no" required="required" class="form-control" placeholder="10 digit Mobile No.*" maxlength="10" value="">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Email
													<input type="email" id="se_email" name="se_email" class="form-control" placeholder="Email" value="">
												</div>
													
											</div>
											<br>
										
											<div class="form-group">
												<div class="col-md-4 col-sm-4 col-xs-12">
													Country <span class="required">*</span>
													<select class="form-control" name="se_country" name="se_country">
														<option value="India"  selected>India</option>
																<?php
														$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														$i = 30;
														foreach ($CntName as $CntNameList) {
														?> 
																								
															<option value="<?php echo stripslashes($CntNameList['country_name']); ?>" />
														<?php
															echo stripslashes($CntNameList['country_name']);
														?></option>
																										
														<?php
															$i++;
														}
														?>
													</select>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													State <span class="required">*</span>
													<select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
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
												<div class="col-md-4 col-sm-4 col-xs-12">
													City <span class="required">*</span>
													<input type="text" id="se_city" name="se_city" class="form-control" placeholder="" value="<?php echo $patient_tab[0]['patient_loc'] ?>">
												</div>
											</div>
											<br>
											<div class="form-group">
												<div class="col-md-12 col-sm-12 col-xs-12">
													Address <span class="required">*</span>
													<textarea class="form-control" id="se_address" name="se_address" rows="3"><?php echo $patient_tab[0]['patient_addrs'] ?></textarea>
												</div>
												
											</div>
											<br>
																						
										
										</div>
										<hr><br>

																					
												<div class="form-group">
													<div class="col-md-6 col-sm-6 col-xs-12 text-right" style="margin-top:10px; float:right;">
														<button type="submit" name="ref_appointment" id="ref_appointment" class="btn btn-primary"><i class="fa fa-floppy-o"></i> CREATE APPOINTMENT</button>
															
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
        <!-- /page content -->

		<?php include_once('footer.php'); ?>
	</div>
    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- morris.js -->
    <script src="../Hospital/vendors/raphael/raphael.min.js"></script>
    <script src="../Hospital/vendors/morris.js/morris.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="../Hospital/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../Hospital/vendors/moment/min/moment.min.js"></script>
    <script src="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

		

  </body>
</html>

</script>