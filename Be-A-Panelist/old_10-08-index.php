<?php ob_start();
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
		 
<link href="jquery-ui.css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
<link href="jquery.multiselect.css" rel="stylesheet" type="text/css">
 <script>
function getState(val) {
	var data_val = $("#doc_country option:selected").attr("myTag")
	$('#sel_country_id').val(data_val);
	
	
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
	
	var val=data.split("@");
		$("#doc_state").html(val[0]);
		$("#Country_code").html(val[1]);
		$("#alt_Country_code").html(val[1]);
		
	}
	});
}

</script>

 <script>
function getHospCountry(val) {
	
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
   <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
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
    .user_data{background:#F0F0F0;width:500px;padding:10px;margin-bottom:5px;position:relative;} 
    .user_data .form-control{margin-bottom:10px;}
    .control-label{width:200px;float: left;}
    .remove-btn{position:absolute;right:0;bottom:10%;border:none;font-size:22px;}
</style>  

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
if($get_provInfo==true){
	
?>
    <div class="header">
    <div class="container ">
		 <div class="row ">
			<div class="col-sm-4 col-xs-12">
				<div class="left img-responsive " style="margin:5%;">
					<!--<img src="assets/img/medisenselogo.jpg" class="" alt="Medisense-Healthcare">  -->
					<img src="../assets/img/logo.png" class="" alt="Medisense-Practice" style="width:25%; height=25%;">
				</div>
			</div>
			
			<div class="col-sm-8 col-xs-12">
				<div class="left img-responsive " style="margin-top:7%;">
					<h3 style="font-size:35px;">
						<span><b>Doctor's Registration Form</b></span>
					</h3>
				</div>
			</div>
		</div>
	 </div>
	</div>
	
<div id="nn"></div>
	
<div class="home_slider">
		
		 
  <div class="container ">
	<div class="sectionBox" style="border-top:4px solid #16B4B5;">
		<div><span class="sucess">
											
		<?php if(isset($_GET['respond'])){
			switch($_GET['respond']){
				case '0' : echo '<font color=green>Thank you, Your profile has been created successfully</font>';
				break;
				case '1' : echo '<font color=red>Failed to submit your request!!!!! Since your profile already exist in our system</font>';
				break;
			}
		}
		?></span>
        </div>
			<form enctype="multipart/form-data" class="" action="send.php" method="post" id="vol_doctor" novalidate="novalidate">
				<div class="row">
					<div class="col-xs-4 col-md-2 col-sm-2 ">
						<img src="assets/img/profile_bg.png" width="120" height="120"/>
					</div>
					<div class="col-xs-6 col-md-10 col-sm-8" style="margin-top:40px;">
						<div class="form-group">
							<label for="exampleFormControlFile1">Add Profile Photo</label>
								<input type="file" id="profile_photo" name="txtPhoto" class="form-control-file"><br>
								<label for="file" class="textarea"><label class="label">Add Profile Photo </label>
							</label>

						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Genaral Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">First Name</label>
							
							<input type="text" required class="form-control" list="browsers" name="first_name" id="first_name" />
							<datalist id="browsers">
							<option value="" ></option>
										
								<?php 
								$SrcName= $objQuery->mysqlSelect("*","referal","","ref_name asc","","","");
								$i=30;
								foreach($SrcName as $srcList){ ?>

								<option value="<?php echo stripslashes($srcList['ref_name']);?>" />
								<!--?php echo stripslashes($srcList['spec_name']);?--></option>


								<?php 	$i++;
								}?>   
								</datalist></div>
								
					</div>
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Middle Name</label>
							<input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Last Name</label>
							<input required type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
						</div>
					</div>	
				</div>	
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState">Gender</label>
							  <select required id="gender"  name="gender" class="form-control">
								<option selected></option>
									<option>Male</option>
									<option>Female</option>
									<option>Other</option>
							  </select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">DOB</label>
							<input type="date" class="form-control" id="dob" name="dob" placeholder="DOB" value="">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Email</label>
							<input type="email" class="form-control" id="doc_email"  name="doc_email"  placeholder="Enter email">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState">Specialization</label>
							  
							  
											<select name="specialization" class="form-control selectpicker" multiple data-live-search="true" >


											<option value="" >Select Specialization</option>

											<?php 
											$SrcName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
											$i=30;
											foreach($SrcName as $srcList){ ?>

											<option value="<?php echo stripslashes($srcList['spec_id']);?>" />
											<?php echo stripslashes($srcList['spec_name']);?></option>


											<?php 	$i++;
											}?>   
											</select>
											
											
											
											
									
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Years of Experience</label>
							<input type="text" class="form-control" id="year_of_exp" name="year_of_exp" placeholder="Year of experience">
						</div>
					</div>
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Consultation Languages</label>
							
							<select id="consult_lang"  name="consult_lang" class="form-control selectpicker" multiple data-live-search="true" >
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
							<label for="formGroupExampleInput2">Country (Current)</label>
							<select name="doc_country" class="form-control" id="doc_country" onchange="return getState(this.value);">
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
							<label for="formGroupExampleInput2">State (Current)</label>
							
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
						<label for="formGroupExampleInput2">City (Current)</label>
						<input type="text" class="form-control" id="city" name="city" placeholder="City">
					</div>
				</div>
					
				</div>	
				
				
				
				
				<div class="row">
				
				
				
				
				
				
				
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<label for="formGroupExampleInput2">Contact Number</label>
							<div class="row">
							<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
									
							<select id="Country_code"  name="Country_code" class="form-control" >
							
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
									<input type="number" class="form-control no-spinner" id="Contact_num" name="Contact_num" placeholder="Contact Number">  
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
						<input type="number" class="form-control no-spinner" id="alt_Contact_num" name="alt_Contact_num" placeholder="Alternative Contact No">  
								</div>
							</div>
						
						</div>
					</div>
					
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Address (Current)</label>
							<input type="text" class="form-control" id="address"  name="address" placeholder="Address">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country of Origin</label>
							<select id="country_of_origin"  name="country_of_origin" class="form-control" >
							
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
				
				<h3 style="padding-bottom:10px;color:#16B4B5;">Academic Information <button id="aca_click" type="button" class="btn btn-primary academic_add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3> 
				<div class="academic_user-details">
				<div class="academic_use_data">
				
				
				<div class="row">
				
				
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Type of Qualification</label>
							<input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification">
						</div>
					</div>

							
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country</label>
							
							<select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);">
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
							<i></i>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">City</label>
							<input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City">
						</div>
					</div>					
				</div>
				
				
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Start Date</label>
							<input type="date" class="form-control" id="acd_Start_Date" name="acd_Start_Date[]" placeholder="Start Date">
							
							
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">End Date</label>
							<input type="date" class="form-control" id="acd_End_Date" name="acd_End_Date[]" placeholder="End Date">
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
				<h3 style="padding-bottom:10px;color:#16B4B5;">Work History <button id="work_id"type="button" class="btn btn-primary add_work_his_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
				<!-- strat --->
				<div class="user-details1">
				<div class="work_his_data">
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Institution Name</label>
							<input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="work_type">Work Type</label>
							  <select id="work_type"  name="work_type[]" class="form-control">
								<option selected>Work Type</option>
									<option>Clinic</option>
									<option>Hospital</option>
								</select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Communication Address (Institution)</label>
							<input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Work Type">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Phone Number (Institution)</label>
							<input type="text" class="form-control" id="Phone_Number" name="Phone_Number[]" placeholder="Phone Number">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Start Date</label>
							<input type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">End Date</label>
							<input type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date">
						</div>
					</div>
				</div>
				
		</div></div>		
				
				
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Other Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Area of Interest</label>
							
							<textarea class="form-control" rows="5"  id="Area_of_interest" name="Area_of_interest" placeholder="Enter area of your interest..."></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Professional Contribution</label>
							
							<textarea class="form-control" rows="5" id="Professional_Contribution" name="Professional_Contribution" placeholder="Enter your professional contribution..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Professional Construction</label>
							<input type="file" class="form-control-file" id="txtProfessional_Construction_file" name="txtProfessional_Construction_file">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Research Details</label>
							
							<textarea class="form-control" rows="5" id="Research_Details" name="Research_Details" placeholder="Enter your research details..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Research Details</label>
							<input type="file" class="form-control-file" id="txtResearch_Details_file" name="txtResearch_Details_file">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Publications</label>
							
							<textarea class="form-control" rows="5" id="Publications" name="Publications" placeholder="Enter publications if any..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Publications</label>
							<input type="file" class="form-control-file" id="txtPublications_file" name="txtPublications_file">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Registration History <button type="button" id="a" class="btn btn-primary add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>Add New</button></h3>
				
				<div class="user-details">
				<div class="use_data">
				
				<div class="row">
				
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Medical Council Registered with</label>
							<input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with">
						</div>
					</div>
					
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Registration Number</label>
							<input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="exampleFormControlFile1">Upload Registration Certificate</label>
							<input type="file" class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]">
						</div>
					</div>
					
				</div>
				</div></div>
				
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:20px;padding-bottom:20px;"></div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Password</label>
							<input type="password" class="form-control" id="Password"  name="Password" placeholder="Password">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Confirm Password</label>
							<input type="password" class="form-control" id="Confirm_Password" name="Confirm_Password" placeholder="Confirm Password">
						</div>
					</div>
					
				</div>
				
				<div class="row form-group center">
				
				<input type = "submit" name = "submit" value = "Complete Registration"  class="btn btn-primary" style="background-color: #16B4B5;padding-top:10px;padding-bottom:10px;padding-left:40px; padding-right:40px;margin-top:20px; font-size: 20px;">
				</div>
				
				 
			</form>
	

	
	
	</div>   
</div>
<?php 
}

?>	
	<script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
		$('.chosen-select').chosen({width: "100%"});
	</script>
	

</body>
</html>



<!--WORK HISTORY ADD NEW FUNCTION----->
<script>    
	$(".add_work_his_details").click(function(){
		//alert("APPEND DEATAILS");
		
	$(".user-details1").append('<div class="work_his_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Institution Name</label><input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="inputState">Work Type</label><select id="work_type"  name="work_type[]" class="form-control"><option selected>Work Type</option><option>Clinic</option><option>Hospital</option></select></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Communication Address (Institution)</label><input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Work Type"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Phone Number (Institution)</label><input type="text" class="form-control" id="Phone_Number" name="Phone_Number[]" placeholder="Phone Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date"  name="work_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="work_End_Date[]" placeholder="End Date"></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');
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
	$(".user-details").append('<div class="use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Medical Council Registered with</label><input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number</label><input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate</label><input type="file" class="form-control-file" id="Upload_Reg_cer" name="Upload_Reg_cer[]"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');     
});
$("body").on("click",".remove-btn",function(e){
	//alert("fgfgfgfgfg");
$(this).parents('.use_data').remove();
//the above method will remove the user_data div
});
</script>   


<!--ACADEMIC ADD NEW FUNCTION ----->
<script>    
$(".academic_add_details").click(function(){
	//APPEND DETAILS
	

$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');


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



