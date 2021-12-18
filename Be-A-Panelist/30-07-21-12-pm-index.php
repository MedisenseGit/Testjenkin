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
 <script>
 <style >
 
 .block {
    display: block;
}
input {
    width: 50%;
    display: inline-block;
}
span {
    display: inline-block;
    cursor: pointer;
    text-decoration: underline;
}
 </style>
  <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">      
  <style> 
    .user_data{background:#F0F0F0;width:500px;padding:10px;margin-bottom:5px;position:relative;} 
    .user_data .form-control{margin-bottom:10px;}
    .control-label{width:200px;float: left;}
    .remove-btn{position:absolute;right:0;bottom:10%;border:none;font-size:22px;}
  </style>  
function getState(val) {
	

	var data_val = $("#doc_country option:selected").attr("myTag")
	$('#sel_country_id').val(data_val);
	
	
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#doc_state").html(data);
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

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
  
 <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
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
				<h3 class="life">
					<span>Doctor's Registration Form</span></h3>
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
			<form>
				<div class="row">
					<div class="col-xs-4 col-md-2 col-sm-2 ">
						<img src="assets/img/profile_bg.png" width="120" height="120"/>
					</div>
					<div class="col-xs-6 col-md-10 col-sm-8" style="margin-top:40px;">
						<div class="form-group">
							<label for="exampleFormControlFile1">Add Profile Photo</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Doctor's Information</h3>
				<div class="row">
					
						
						
						<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">First Name</label>
							
							
							
							<input type="text"  class="form-control" list="browsers" name="myBrowser" />
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
								</datalist>
							
							
							
						</div>
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
							<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
						</div>
					</div>	
				</div>	
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState">Gender</label>
							  <select id="gender" required name="gender" class="form-control">
								<option selected>Gender</option>
									<option>Male</option>
									<option>Female</option>
									<option>Other</option>
							  </select>
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">DOB</label>
							<input type="date" class="form-control" id="age" name="age"  placeholder="DOB" value="">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Email</label>
							<input type="email" required class="form-control" id="exampleInputEmail1" name="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
						</div>
					</div>	
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="inputState">Specilization</label>
							  <!--select id="Specilization" class="form-control" required>
								<option selected>Select Specilization...</option>
									<option>...</option>
							  </select-->
							  
											<select id="myselect1" name="specialization" class="form-control"  onchange="return getSubSpecific(this.value);" >	
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
							<label for="formGroupExampleInput2">Contact Number</label>
							<input type="number" required class="form-control" id="contact_num" name="contact_num" class="no-spinner" placeholder="(+91) - Contact Number">  
						
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Alternative Contact Number </label>
							<input type="text" class="form-control" id="alt_num" name="alt_num" placeholder="Alternative Contact No">
						</div>
					</div>	
				</div>	
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country</label>
							<input type="text" class="form-control" id="country_name"  name="country_name" placeholder="Country">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">State</label>
							<input type="text" class="form-control" id="state" name="state" placeholder="State">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">City</label>
							<input type="text" class="form-control" id="city" name="city" placeholder="City">
						</div>
					</div>	


					


							
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Academic Information <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3> 
				<div class="row">
					<div class="user-details" >
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Type of qualification</label>
							<input type="text" required class="form-control" id="type_of_qualification" name="type_of_qualification" placeholder="Type of qualification">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country</label>
							<input type="text" class="form-control" id="country_name" name="country_name" placeholder="Country">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">State</label>
							<input type="text" class="form-control" id="state" name="state" placeholder="State">
						</div>
					</div>
					</div>
					<div class="form-group">
          <input value="Add More" class="add_details" autocomplete="false" type="button">
</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">City</label>
							<input type="text" class="form-control" id="city" name="city" placeholder="City">
						</div>
					</div>					
				
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
				</div>
				<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Internship <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Country">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">State</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="State">
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
				</div>
				<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Qualification Examination Information <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
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
				</div>
				<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Year Of Experience</h4>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Year Of Experience</label>
							<input type="number" class="form-control" id="formGroupExampleInput2" placeholder="Year Of Experience">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Hospital Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Name</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Name">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Work Type</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Work Type">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Communication Address</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Work Type">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Consultation Language</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Consultation Language">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Other Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Area of interest</label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Area of interest"> -->
							<textarea class="form-control" rows="5" placeholder="Enter area of your interest..."></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Professional Contribution</label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Professional Construction"> -->
							<textarea class="form-control" rows="5" placeholder="Enter your professional contribution..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Professional Construction</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Research Details</label>
							<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Research Details"> -->
							<textarea class="form-control" rows="5" placeholder="Enter your research details..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Research Details</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group">
							<label for="formGroupExampleInput2">Publications</label>
							<!-- <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Publications"> -->
							<textarea class="form-control" rows="5" placeholder="Enter publications if any..."></textarea>
						</div>
					</div>
					<div class="col-xs-12 col-md-6 col-sm-6">
						<div class="form-group" style="margin-top:50px;">
							<label for="exampleFormControlFile1">Publications</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>
					</div>
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
				<h3 style="padding-bottom:10px;color:#16B4B5;">Registration Information</h3>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country Medical Council base</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Country Medical Council base">
						</div>
					</div>
					
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Upload Registration Certificate</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Upload Registration Certificate">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="exampleFormControlFile1">Upload Registration Certificate</label>
							<input type="file" class="form-control-file" id="exampleFormControlFile1">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Registration History</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Registration History">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Registration Number</label>
							<input type="number" class="form-control" id="formGroupExampleInput2" placeholder="Registration Number">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Date of Registration</label>
							<input type="date" class="form-control" id="formGroupExampleInput2" placeholder="Date of Registration">
						</div>
					</div>
					
				</div>
				<div class="hrline" style=" border-top: 1px dashed gray;margin-top:20px;padding-bottom:20px;"></div>
				<div class="row">
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Country Worked In</label>
							<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Country Worked In">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Password</label>
							<input type="password" class="form-control" id="formGroupExampleInput2" placeholder="Password">
						</div>
					</div>
					<div class="col-xs-12 col-md-4 col-sm-4">
						<div class="form-group">
							<label for="formGroupExampleInput2">Confirm Password</label>
							<input type="password" class="form-control" id="formGroupExampleInput2" placeholder="Confirm Password">
						</div>
					</div>
					
				</div>
				
				<div class="row form-group center">
				<button type="button" class="btn btn-primary" style="background-color: #16B4B5;padding-top:10px;padding-bottom:10px;padding-left:40px; padding-right:40px;margin-top:20px; font-size: 20px;">Complete Registration</button>
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
<script>    
  $(".add_details").click(function(){
    
      //the below code will append a new user_data div inside user-details container
   
        $(".user-details").append(' <div class="col-xs-12 col-md-6 col-sm-6"><div class="form-group"><label for="formGroupExampleInput2">Professional Contribution</label><!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Professional Construction"> --><textarea class="form-control" rows="5" placeholder="Enter your professional contribution..."></textarea></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');
    
          
  });
  $("body").on("click",".remove-btn",function(e){
       $(this).parents('.user_data').remove();
      //the above method will remove the user_data div
  });
</script>