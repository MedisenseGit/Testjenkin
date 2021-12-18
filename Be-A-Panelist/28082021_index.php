<?php 
		ob_start();
		error_reporting(0);
		session_start(); 

	 
		//connect to the DB
		require_once("../classes/querymaker.class.php");
		$objQuery = new CLSQueryMaker();
		$_SESSION['new_terms_condition']=0;
		
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="description" content="">
	<meta name="keywords" content="">
	 <title>Medisense-Healthcare Solutions</title>
	 <?php include_once("support.php"); ?>
	 <!--link href="jquery-ui.css" rel="stylesheet">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	<link href="jquery.multiselect.css" rel="stylesheet" type="text/css"-->
	<link href="jquery-ui.css" rel="stylesheet">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
	<link href="jquery.multiselect.css" rel="stylesheet" type="text/css">
	<script>
			function getState(val) {
			var data_val = $("#doc_country option:selected").attr("myTag")
			$('#sel_country_id').val(data_val);
			$('#selected_country_id').val(data_val);
			//alert(data_val);
				$.ajax({
				type: "POST",
				url: "get_state.php",
				data:{"country_name":data_val},
				success: function(data){
				//alert(data);
					var val=data.split("@");
					$("#doc_state").html(val[0]);
					$("#Country_code").html(val[1]);
					$("#alt_Country_code").html(val[1]);
					
				}
				});
			}

	</script>


	<script>
	function getHospCountry(val) 
	{
		
		var data_val = $("#hospital_country option:selected").attr("myHospTag")
		$('#sel_hospCountry_id').val(data_val);
			
	}

	</script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">   
	<!--link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />  

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	
	<script>
	$( function() {
	$( "#datepicker" ).datepicker();
	} );
	</script>
  
  
	<script type="text/javascript">
	$(function () {
		$("#chkTeleOp").click(function () {
		if ($(this).is(":checked")) {
			$("#get-telenum").show();
			$("#get-timing").show();
			} else {
			$("#get-telenum").hide();
			$("#get-timing").hide();

		}
		});
	});

	$(function () {
		$("#chkVideoOp").click(function () {
		if ($(this).is(":checked")) {
			$("#get-videonum").show();
			$("#get-timing").show();
			$("#get-video-detail").show();				  
			} else {
			$("#get-videonum").hide();
			$("#get-timing").hide();
			$("#get-video-detail").hide();				  
			}
		});
	});

	</script>
	
	<style>
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
	  -webkit-appearance: none; 
	  margin: 0; 
	}
	</style>

	</head>
	<body>
		   <?php 
				   $get_provInfo = $objQuery->mysqlSelect("*","referal ","enc_key='".$_GET['ency_id']."'","","","","");
				   //echo "<PRE>"; print_r($get_provInfo); exit;
				   if($get_provInfo==true){
				
		   ?>
		<div class="header">
			<div class="container ">
				<div class="row ">
					<div class="col-sm-4 col-xs-12">
						<div class="left img-responsive " style="margin:5%;">
						<img src="../assets/img/logo.png" class="" alt="Medisense-Practice" style="width:25%; height=25%;">
						</div>
					</div>

					<div class="col-sm-6 col-xs-12">
						<div class="left img-responsive " style="margin-top:7%;">
						<h3 style="font-size:35px;">
						<span><b>Doctor's Registration Form</b></span>
						</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
<div class="home_slider">
		<!-- <div class="container pbg">
				<div class="bg-slider">
				<div class="col-sm-12 col-xs-12">
				<p class="camp_par  ">
				Here is a chance to save someone's life and may be yours, some day. Click below link and mention that great doctor's name who you worship, for having saved your or your loved ones's life.  Please fill the below form, We will consolidate and open it for patients to refer whenever they are down with that dreaded condition.
				</p>
				</div>
				
				</div>
		 </div>-->
		 
  <div class="container ">
	<div class="sectionBox" style="border-top:4px solid #16B4B5;">
		<div><span class="sucess">
											
											<?php 
											
											
											
											if(isset($_GET['respond'])){
												switch($_GET['respond']){
													case '0' : echo '<font color=green>Thank you, Your profile has been created successfully</font>';
													break;
													case '1' : echo '<font color=red>Failed to submit your request!!!!! Since your profile already exist in our system</font>';
													
													break;
												}
											}
											?></span>
        </div>
			 <form enctype="multipart/form-data"  action="send.php" method="post" id="vol_doctor" onclick="return validationfun();" >
				<div class="row">
					<div class="col-xs-6 col-md-2 col-sm-2 ">
						<img src="assets/img/profile_bg.png" width="120" height="120"/ class="image">
					</div>
					<div class="col-xs-6 col-md-10 col-sm-8" style="margin-top:40px;">
						<div class="form-group">
							<label for="exampleFormControlFile1">Add Profile Photo<span class="red">*</span></label>
							<input required type="file" class="form-control-file" id="profile_photo" name="txtPhoto" onchange="photouploadfun();">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">General Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2" >First Name<span class="red">*</span></label>
							
							<input type="text"  class="form-control" required list="browsers" name="first_name" id="first_name" />
							<datalist id="browsers" >
							<option value="" ></option>
										
								<?php 
								$SrcName= $objQuery->mysqlSelect("*","referal","","ref_name asc","","","");
								$i=30;
								foreach($SrcName as $srcList){ ?>

								<option value="<?php echo stripslashes($srcList['ref_name']);?>" />
								<!--?php echo stripslashes($srcList['spec_name']);?--></option>


								<?php 	$i++;
								}?>   
								</datalist>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Middle Name</label>
							<input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name" autocomplete="true" >
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2" >Last Name <span class="red">*</span></label>
							<input type="text" required class="form-control"  id="last_name" name="last_name" placeholder="Last Name" autocomplete="true">
						</div>
					</div>	
				</div>	
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState" >Gender<span class="red">*</span></label>
							   <select required id="gender"  name="gender" class="form-control" autocomplete="true">
								<option>  </option>
									<option>Male</option>
									<option>Female</option>
									<option>Other</option>
							  </select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2" >DOB<span class="red">*</span></label>
							<input type="date" required class="form-control" id="dob"  name="dob" placeholder="DOB" value="" autocomplete="true">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2" id="error_file">Email<span class="red" >*</span></label>
							<input type="email" required  class="form-control" id="doc_email"  name="doc_email" aria-describedby="emailHelp" placeholder="Enter email" onchange="emailvalidation();" autocomplete="true">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState" id="error_file">Specialization<span class="red">*</span></label>
							 <select  name="specialization[]" required id="specialization[]" class="form-control selectpicker" multiple data-live-search="true" >


											<option value="" >Select Specialization</option>

											<?php 
											$SrcName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
											$i=30;
											foreach($SrcName as $srcList){ ?>

											<option value="<?php echo stripslashes($srcList['spec_id']);?>" />
											<?php echo stripslashes($srcList['spec_name']);?></option>


											<?php 	$i++;
											
											?>
							
											<?php 
											}
											
											
											
											?>   
											</select>
											
											
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Years of Experience<span class="red">*</span></label>
							<input  type="text" required class="form-control" id="formGroupExampleInput2" placeholder="Year of experience" autocomplete="true">
						</div>
					</div>
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Consultation Languages(Select Multiple)<span class="red">*</span></label>
							<select id="consult_lang" required name="consult_lang[]" id="consult_lang[]" class="form-control selectpicker" multiple data-live-search="true" >
							<option value="" >Select Languages</option>
							<?php 
							$SrcName1= $objQuery->mysqlSelect("*","languages","","","","","");
							$i=30;
							foreach($SrcName1 as $srcList){ ?>
							<option value="<?php echo stripslashes($srcList['id']);?>" />
							<?php echo stripslashes($srcList['name']);?></option>
							<?php 
							$i++;
							}?>   
							</select>
							
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country (Current)<span class="red">*</span></label>
							<input type="text" id="selected_country_id" name="selected_country_id">
							<select name="doc_country" required  class="form-control" id="doc_country" onchange="return getState(this.value);">
							<option value="" myTag=""  selected>select</option>
							
							<?php
									$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
									$i       = 30;
									foreach ($CntName as $CntNameList) {
									?> 

									<option   myTag="<?php
									echo stripslashes($CntNameList['country_id']);
									?>" value="<?php
									echo stripslashes($CntNameList['country_name']);
									?>" />
									<?php
									echo stripslashes($CntNameList['country_name']);
									?></option>

									<?php
									$i++;
									}
							?>
							</select>
							<i></i>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">State (Current)<span class="red">*</span></label>
							
								<select name="doc_state"  class="form-control" id="doc_state" placeholder="State">

								<option value='' selected>State</option>
								<?php
								$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
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
								<i></i> 
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
					<div class="form-group">
						<label for="formGroupExampleInput2">City (Current)<span class="red">*</span></label>
						<input  type="text" class="form-control" id="city" name="city" placeholder="City" required autocomplete="true">
					</div>
					</div>
				</div>	
				
				<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
						<label for="formGroupExampleInput2">Contact Number<span class="red">*</span></label>
							<div class="row">
							<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
									
							<select id="Country_code"   name="Country_code" class="form-control" >
							
							<option value="" ></option>
							<?php 
						$SrcName1= $objQuery->mysqlSelect("*","countries","","","","","");
							$i=30;
							foreach($SrcName1 as $srcList){ ?>
							<option value="<?php echo stripslashes($srcList['country_id']);?>" /> +
							<?php echo stripslashes($srcList['ph_extn']);?></option>
							<?php 
							$i++;
						}?>   
							</select>

									
								</div>
								<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
									<input type="number"  class="form-control " id="Contact_num" name="Contact_num" placeholder="Contact Number" onchange="emailvalidation();" autocomplete="true"> 
								</div>
							</div>
						
					</div>
				<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Alternative Contact Number </label>
							<div class="row">
								<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
									<select id="alt_Country_code"  name="alt_Country_code" class="form-control">
										<option value="" ></option>
							<?php 
							$SrcName1= $objQuery->mysqlSelect("*","countries","","","","","");
							$i=30;
							foreach($SrcName1 as $srcList){ ?>
							<option value="<?php echo stripslashes($srcList['country_id']);?>" />+
							<?php echo stripslashes($srcList['ph_extn']);?> </option>
							<?php 
							$i++;
							}
							?>   
									</select>
								</div>
								<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
						<input type="number" class="form-control no-spinner" id="alt_Contact_num" name="alt_Contact_num" placeholder="Alternative Contact No" autocomplete="true">  
								</div>
							</div>
						
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Address (Current)<span class="red">*</span></label>
							<input  type="text" class="form-control" id="address"  name="address" placeholder="Address" required autocomplete="true">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country of Origin<span class="red">*</span></label>
							<select id="country_of_origin"  name="country_of_origin" class="form-control" required >
							
							<option value="" >Select country</option>

							<?php 
							$SrcName= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
							$i=30;
							foreach($SrcName as $srcList){ ?>

							<option value="<?php echo stripslashes($srcList['country_id']);?>" />
							<?php echo stripslashes($srcList['country_name']);?></option>


							<?php 	$i++;
							}?>   
							</select>
							
							
							
							
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Academic Information <button type="button" class="btn btn-primary academic_add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3> 
				<div class="academic_user-details">
				<div class="academic_use_data">
				
				<div class="row" >
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Type of Qualification<span class="red">*</span></label>
							<input  type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification" required autocomplete="true"> 
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country<span class="red">*</span></label>
							<select name="acd_doc_country[]"  class="form-control" id="doc_country" onchange="return getState(this.value);" required>
							<option value="India" myTag="100"  selected>India</option>
							
							<?php
									$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
									$i       = 30;
									foreach ($CntName as $CntNameList) {
									?> 

									<option   myTag="<?php
									echo stripslashes($CntNameList['country_id']);
									?>" value="<?php
									echo stripslashes($CntNameList['country_name']);
									?>" />
									<?php
									echo stripslashes($CntNameList['country_name']);
									?></option>

									<?php
									$i++;
									}
							?>
							</select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">City<span class="red">*</span></label>
							<input  type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City" required autocomplete="true">
						</div>
					</div>					
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
							<input  type="date" class="form-control" id="acd_Start_Date" name="acd_Start_Date[]" placeholder="Start Date" required>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">End Date</span></label>
							<input 
							type="date" class="form-control" id="acd_End_Date" name="acd_End_Date[]" placeholder="End Date">
						</div>
					</div>
				</div>
				
				</div></div>
				<!--<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Internship Details <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Institution</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Institution">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Country">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">City</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="City">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Start Date</label>
							<input type="date" class="form-control" id="formGroupExampleInput2" placeholder="Start Date">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">End Date</label>
							<input type="date" class="form-control" id="formGroupExampleInput2" placeholder="End Date">
						</div>
					</div>
				</div>-->
				<!--<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Qualification Exam Information <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Examination ID</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="ExaminationID">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Date</label>
							<input type="date" class="form-control" id="formGroupExampleInput2" placeholder="Date">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Score</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Score">
						</div>
					</div>
				</div>-->
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Work History <button type="button" class="btn btn-primary add_work_his_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
				<div class="user-details1">
				<div class="work_his_data">
					<div class="row">
						<div class="col-xs-12 col-md-4 col-sm-4">
							<div class="form-group">
								<label for="formGroupExampleInput2">Institution Name<span class="red">*</span> </label>
								<input  type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name" required autocomplete="true">
							</div>
						</div>
						<div class="col-xs-12 col-md-4 col-sm-4">
							<div class="form-group">
								<label for="inputState">Work Type<span class="red">*</span></label>
									 <select id="work_type"  name="work_type[]" class="form-control" required>
									<option selected>Work Type</option>
										<option>Clinic</option>
										<option>Hospital</option>
									</select>
							</div>
						</div>
						<div class="col-xs-12 col-md-4 col-sm-4">
								<div class="form-group">
									<label for="formGroupExampleInput2">Communication Address (Institution)<span class="red">*</span></label>
								<input  type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address" required autocomplete="true">
								</div>
							</div>
							
						
					</div>
						<div class="row">
							
							<div class="col-xs-12 col-md-4 col-sm-4">
								<label for="formGroupExampleInput2">Phone Number (Institution)<span class="red">*</span></label>
									<div class="row">
									<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
									<select id="Phone_Country_code"   name="Phone_Country_code" class="form-control" required>
									<option value="" ></option>
									<?php 
									$SrcName1= $objQuery->mysqlSelect("*","countries","","","","","");
									$i=30;
									foreach($SrcName1 as $srcList){ ?>
									<option value="<?php echo stripslashes($srcList['country_id']);?>" /> 
									<?php echo stripslashes($srcList['ph_extn']);?></option>
									<?php 
									$i++;
									}?>   
									</select>
									</div>
										<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
											<input type="number"  class="form-control no-spinner" id="Phone_Number"  name="Phone_Number[]" placeholder="Contact Number" required>  
										</div>
									</div>
							</div>
							
								
						<div class="col-xs-12 col-md-4 col-sm-4">
							<div class="form-group">
								<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
								<input required  type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date">
							</div>
						</div>
							
							
							<div class="col-xs-12 col-md-4 col-sm-4">
								<div class="form-group">
									<label for="formGroupExampleInput2">End Date</label>
									<input   type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date">
								</div>
							</div>
						
						</div>
				
				
					
						
				</div>
				</div>
				
				
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Other Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Area of Interest<span class="red">*</span></label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Area of interest"> -->
							<textarea required class="form-control" rows="5"  id="Area_of_interest" name="Area_of_interest" placeholder="Enter area of your interest..."></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Professional Contribution</label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Professional Construction"> -->
							<textarea class="form-control" rows="5" id="Professional_Contribution" name="Professional_Contribution" placeholder="Enter your professional contribution..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Professional Contribution</label>
							<input type="file" class="form-control-file" id="Professional_Construction_file" name="txtProfessional_Construction_file">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Research Details</label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Research Details"> -->
							<textarea class="form-control" rows="5" id="Research_Details" name="Research_Details" placeholder="Enter your research details..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Research Details</label>
							<input type="file" class="form-control-file" id="Research_Details_file" name="txtResearch_Details_file">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Publications</label>
							<!-- <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Publications"> -->
							<textarea class="form-control" rows="5" id="Publications" name="Publications" placeholder="Enter publications if any..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Publications</label>
							<input type="file" class="form-control-file" id="Publications_file" name="txtPublications_file">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Registration History <button type="button" class="btn btn-primary add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
				<div class="user-details">
				<div class="use_data">
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Medical Council registered with<span class="red">*</span></label>
							<input type="text" required  class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Registration Number<span class="red">*</span></label>
							<input required type="text" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="exampleFormControlFile1">Upload Registration Certificate<span class="red">*</span></label>
							<input type="file" required  class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]">
						</div>
					</div>
				</div>
				</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Passport Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Passport Number<span class="red">*</span></label>
							<input required type="text"  class="form-control" id="passport_num" name="passport_num" placeholder="Passport Number">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Passport - Country<span class="red">*</span></label>
							<select required id="passport_country"   name="passport_country" class="form-control">
								<option value="India" myTag="100"  selected>India</option>
							
							<?php
									$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
									$i       = 30;
									foreach ($CntName as $CntNameList) {
									?> 

									<option   myTag="<?php
									echo stripslashes($CntNameList['country_id']);
									?>" value="<?php
									echo stripslashes($CntNameList['country_name']);
									?>" />
									<?php
									echo stripslashes($CntNameList['country_name']);
									?></option>

									<?php
									$i++;
									}
							?>
							  </select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="exampleFormControlFile1">Upload Passport<span class="red">*</span></label>
							<input required type="file"  class="form-control-file" id="txtpassport_file" name="txtpassport_file" >
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:20px;padding-bottom:20px;"></div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Password<span class="red">*</span></label>
							<input required type="password" class="form-control" id="Password"  name="Password" placeholder="Password">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2"  class="error_pass">Confirm Password</label>
							<input required type="password" class="form-control" id="Confirm_Password" name="Confirm_Password" placeholder="Confirm Password" onkeyup="validate();">
						</div>
					</div>
					
				</div>
				
				<div class="row">
				
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition" class="error"></span></label>
                                      <input required type="checkbox" name="new_terms_condition" id="new_terms_condition" value="" ><a href="empanel-terms" target="_blank"> Terms and condition</a>
                                    
                                  </div>
                              </div>										
		                                                
		</div>
		
		
		<div class="row">
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition2" class="error"></span></label>
                                      <input type="checkbox" name="new_terms_condition2" id="new_terms_condition" value="" required> I agree that my undergraduate/postgraduate medical qualification(s) are registered with the medical council.
                                    
                                  </div>
                              </div>										
		                                                
		</div>
		
		<div class="row">
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition3" class="error"></span></label>
                                      <input type="checkbox" name="new_terms_condition3" id="new_terms_condition" value="" required > I agree that I have not been held guilty of medical negligence by a court of law or by the medical council.
                                    
                                  </div>
                              </div>										
		                                                
		</div>
				
				<div class="row form-group center">
				
				
				<input type ="submit" name ="submit" value ="Complete Registration"  class="btn btn-primary" style="background-color: #16B4B5;padding-top:10px;padding-bottom:10px;padding-left:40px; padding-right:40px;margin-top:20px; font-size: 20px;">
				</div>
			

	
	
	</div> 
</form>
		
</div>
<?php 
}

?>	
<script languages="javascript">
 function validate(){
	
	
	var password = $('#Password').val();//document.getElementById("password")
         var  confirm_password = $('#Confirm_Password').val();//document.getElementById("confirm_password");
		 
if(password != confirm_password){
	$('.error_pass').html("Password Miss match..");
	 //class="error"
	return false;
	
}
$('.error_pass').html("Confirm_Password");
           
}



</script>

<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="assets/js/validation.js"></script>
	<script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
		$('.chosen-select').chosen({width: "100%"});
	</script>
	

</body>
</html>

<script>    
	$(".add_work_his_details").click(function(){
		
	$(".user-details1").append('<div class="work_his_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Institution Name</label><input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="inputState">Work Type</label><select id="work_type"  name="work_type[]" class="form-control"><option selected>Work Type</option><option>Clinic</option><option>Hospital</option></select></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Communication Address (Institution)</label><input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><label for="formGroupExampleInput2">Phone Number (Institution)<span class="red">*</span></label><div class="row"><div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;"><select id="Phone_Country_code"  name="Phone_Country_code" class="form-control" ><option value="" ></option><?php $SrcName1= $objQuery->mysqlSelect("*","countries","","","","","");$i=30; foreach($SrcName1 as $srcList){ ?><option value="<?php echo stripslashes($srcList['country_id']);?>" /> <?php echo stripslashes($srcList["ph_extn"]);?></option><?php $i++; } ?>   </select></div><div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;"><input type="number" class="form-control no-spinner" id="Phone_Number" name="Phone_Number[]" placeholder="Contact Number">  </div></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date"></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');
	
	
	
	
	});
	
	
	
	$("body").on("click",".remove-btn",function(e){
	$(this).parents('.work_his_data').remove();
	//the above method will remove the user_data div
	});
</script>    


<!--REGISTRATION HISTORY ADD NEW FUNCTION ----->
<script>    
$(".add_details").click(function(){
	//APPEND DETAILS
	$(".user-details").append('<div class="use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Medical Council Registered with</label><input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number</label><input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate</label><input type="file" class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');     
});
$("body").on("click",".remove-btn",function(e){
	
$(this).parents('.use_data').remove();
//the above method will remove the user_data div
});
</script>   

<script>    
$(".academic_add_details").click(function(){
	//APPEND DETAILS
	

$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');


});
$("body").on("click",".remove-btn",function(e){
	//alert("fgfgfgfgfg");
$(this).parents('.academic_use_data').remove();
//the above method will remove the user_data div
});
</script>     

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="jquery.multiselect.js"></script>
<!--script>
$('select[multiple]').multiselect({
    columns: 1,
    placeholder: 'Select options'
});
</script-->

<script languages="javascript">
$('select').selectpicker();
</script>
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script-->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script languages="text/javascript">

	
	$(function(){
    $('#profile_photo').change( function(e) {
        
        var img = URL.createObjectURL(e.target.files[0]);
        $('.image').attr('src', img);
    });
});



</script>



<script languages="text/javascript">

	function emailvalidation()
	
	{
		
	
		var doc_email=$('#doc_email').val();
		var Contact_num=$('#Contact_num').val();
	
		$.ajax({
				type: "POST",
				url: "get_docemail.php",
				data:{"email": doc_email,"contact": Contact_num},
				success: function(data){
				
				if(data!=""){
				//$('#doc_email').val("");
				return fasle;
				}
				}
				
				});
	}
	
</script>

