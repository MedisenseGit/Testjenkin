<?php

	ob_start();
	error_reporting(0); 
	session_start();

	$admin_id = $_SESSION['user_id'];
	$ref_id	  = $_GET['p'];
	include('functions.php');
	if(empty($admin_id))
	{
		
		header("Location:index.php");
	}
	$curdate=date('Y-m-d');
	require_once("../classes/querymaker.class.php");
	$getdocinfo = mysqlSelect("*","referal","md5(ref_id)='".$ref_id."'","","","");
	
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
	<link href="jquery.multiselect.css" rel="stylesheet" type="text/css">
	<script>
	function getState(val) 
	{
		var data_val = $("#doc_country option:selected").attr("myTag");
	
		$('#sel_country_id').val(data_val);
		$('#selected_country_id').val(data_val);
		$.ajax({
			type: "POST",
			url: "doc_get_state.php",
			data:{"country_name":data_val},
			success: function(data)
			{
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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />  
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="css/style.css">
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
		if ($(this).is(":checked")) 
		{
			$("#get-videonum").show();
			$("#get-timing").show();
			$("#get-video-detail").show();				  
		} 
		else 
		{
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
				
				
				<div class="row">
				<a href="Onboard-Doctor-List" class="btn btn-primary" style="margin-top: 50px;">Back</a>
				</div>
			</div>
		</div>
	</div>
					
	<div class="container">
		<div class="sectionBox" style="border-top:4px solid #16B4B5;">
					<div>
					<span class="sucess">
							<?php 
								if(isset($_GET['respond'])){
									switch($_GET['respond']){
										case '0' : echo '<font color=green>Thank you, Doctor profile has been updated successfully</font>';
										break;
										case '1' : echo '<font color=red>Failed to submit your request!!!!! Since your profile already exist in our system</font>';
										
										break;
									}
								}
							?>
					</span>
					</div>
		
					<?php
						//$img="https://medisensemd.com/Doc/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['doc_photo'];
						$img=IMG_URL_VIEW."Doc/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['doc_photo'];
						if($getdocinfo[0]['doc_photo']=="")
						{
							$img="assets/img/profile_bg.png";
						}
						
					?>
									
			<form enctype="multipart/form-data"  action="doc_details_send.php" method="post" id="vol_doctor" name="vol_doctor" >
						<input type="hidden" name="doc_id" id="doc_id" value="<?php echo $getdocinfo[0]['ref_id']; ?>" />
						<div class="row">
							<div class="col-xs-6 col-md-2 col-sm-2 ">
								<img src="<?php echo $img; ?>"  width="120" height="120" class="image">
							</div>
							<div class="col-xs-6 col-md-10 col-sm-8" style="margin-top:40px;">
								<div class="form-group">
									<label for="exampleFormControlFile1">Add Profile Photo<span class="red">*</span></label>
									<input  type="file" class="form-control-file" id="profile_photo" name="txtPhoto" onchange="photouploadfun();">
								</div>
							</div>
						</div>
						
							<div class="hrline" style=" border-top: 1px dashed gray;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">General Information</h3>
							<div class="row">
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2" >First Name<span class="red">*</span></label>
										<input type="text"  class="form-control"  list="browsers" name="first_name" id="first_name" value="<?php echo $getdocinfo[0]['ref_name']; ?>"/>
									</div>
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="inputState" >Gender<span class="red">*</span></label>
											<select  id="gender"  name="gender" class="form-control" autocomplete="true">
												<option value="1" <?php if($getdocinfo[0]['doc_gen']== "1"){ ?>selected<?php } ?>>Male</option>
												<option value="2" <?php if($getdocinfo[0]['doc_gen']== "2"){ ?>selected<?php } ?>>Female</option>
												<option value="3" <?php if($getdocinfo[0]['doc_gen']== "3"){ ?>selected<?php } ?>>Others</option>
											</select>
									</div>
									
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2" >DOB<span class="red">*</span></label>
										<input type="date"  class="form-control" id="dob"  name="dob" placeholder="DOB" value="<?php echo $getdocinfo[0]['doc_dob']; ?>" autocomplete="true">
									</div>
									
								</div>	
							</div>	
							<div class="row">
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2" id="error_file">Email<span class="red" >*</span></label>
										<input type="email"   class="form-control" id="doc_email"  name="doc_email" aria-describedby="emailHelp" placeholder="Enter email"  autocomplete="true" value="<?php echo $getdocinfo[0]['ref_mail']; ?>" readonly />
									</div>
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
									<?php
										$getdocspec = mysqlSelect("*","referal","md5(ref_id)='".$ref_id."'","","","");
									?>
										<label for="inputState" id="error_file">Specialization<span class="red">*</span></label>
											<select  name="specialization[]"  id="specialization[]" class="form-control selectpicker" multiple data-live-search="true" >
											<?php 
											$DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
															
															foreach($DeptName as $DeptList)
															{
																$chooseDept= mysqlSelect("*","doc_specialization","md5(doc_id)='".$ref_id."' and spec_id='".$DeptList['spec_id']."'","","","","");
															
																if($DeptList['spec_id']==$chooseDept[0]['spec_id']){
																	?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
															<?php 
																}?>
														<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
															<?php
																	
															}?>  
											</select>
									</div>
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Years of Experience<span class="red">*</span></label>
										<input  type="text"  class="form-control" id="year_of_exp"  name="year_of_exp" placeholder="Year of experience" autocomplete="true" value="<?php echo $getdocinfo[0]['ref_exp']; ?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Consultation Languages(Select Multiple)<span class="red">*</span></label>
										<select id="consult_lang"  name="consult_lang[]" id="consult_lang[]" class="form-control selectpicker" multiple data-live-search="true" >
										<option value="" >Select Languages</option>
										
										<?php 
										$SrcName1= mysqlSelect("*","languages","","","","","");
										$i=30;
										foreach($SrcName1 as $srcList){ 
										$get_lang = mysqlSelect("*","doctor_langauges ","md5(doc_id)='".$ref_id."'  and language_id='".$srcList['id']."'","","","",""); ?>
										<option value="<?php echo stripslashes($srcList['id']);?>" <?php if($get_lang[0]['language_id']==$srcList["id"]){ echo "selected"; }?>/>
										<?php echo stripslashes($srcList['name']);?></option>
										<?php 
										$i++;
										}?>   
										</select>
										
									</div>
								</div>
								
								
								<div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Country (Current)<span class="red">*</span></label>
                                                <input type="hidden" id="selected_country_id" name="selected_country_id" value="<?php echo $getdocinfo[0]['doc_country_id']; ?>" > 
                                                <select name="doc_country" required  class="form-control" id="doc_country" onchange="return getState(this.value);">
												
												
                                                    <option value="<?php echo $getdocinfo[0]['doc_country']; ?>" myTag="<?php echo $getdocinfo[0]['doc_country_id']; ?>"  selected><?php echo $getdocinfo[0]['doc_country']; ?></option>
                                                    <?php
                                                        $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
                                                            ?>
                                                    </option>
                                                    <?php
                                                        $i++; ?>
														
														
                                                      <?php  }
                                                        ?>
                                                </select>
												
                                                <i></i>
                                            </div>
                                        </div>
								
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">State (Current)<span class="red">*</span></label>
										
											<select name="doc_state"  class="form-control" id="doc_state" placeholder="State">

											<option value="<?php echo $getdocinfo[0]['doc_state']; ?>" selected><?php echo $getdocinfo[0]['doc_state']; ?></option>
											<?php
											$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
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
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">City (Current)<span class="red">*</span></label>
										<input type="text" class="form-control" id="city" name="city" placeholder="City"  autocomplete="true" value="<?php echo $getdocinfo[0]['doc_city'	]; ?>">
									</div>
								</div>
								
								<div class="col-xs-12 col-md-4 col-sm-4">
									<label for="formGroupExampleInput2">Contact Number<span class="red">*</span></label>
										<div class="row">
										<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
												
										<select id="Country_code"   name="Country_code" class="form-control">
										
										<option value="<?php echo $getdocinfo[0]['contact_num_extension']; ?>" ><?php echo $getdocinfo[0]['contact_num_extension']; ?></option>
										<?php 
										$SrcName1= mysqlSelect("*","countries","","","","","");
										$i=30;
										foreach($SrcName1 as $srcList){ ?>
										<option value="<?php echo $getdocinfo[0]['contact_num_extension']; ?>" /> +
										<?php echo stripslashes($srcList['ph_extn']);?></option>
										<?php 
										$i++;
									}?>   
										</select>

												
											</div>
											<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
												<input type="number"  class="form-control " id="Contact_num" name="Contact_num" value="<?php echo $getdocinfo[0]['contact_num']; ?>"  autocomplete="true" > 
											</div>
										</div>
								</div>
								
								<div class="col-xs-12 col-md-4 col-sm-4">
									<label for="formGroupExampleInput2">Alternative Contact Number<span class="red">*</span></label>
										<div class="row">
										<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
												
										<select id="Alt_Country_code"   name="Alt_Country_code" class="form-control"  >
										
										<option value="<?php echo $getdocinfo[0]['secondary_contact_num_extension']; ?>" ><?php echo $getdocinfo[0]['secondary_contact_num_extension']; ?></option>
										<?php 
										$SrcName2= mysqlSelect("*","countries","","","","","");
										$i=30;
										foreach($SrcName2 as $srcList1){ ?>
										<option value="<?php echo stripslashes($srcList1['ph_extn']); ?>" /> +
										<?php echo stripslashes($srcList1['ph_extn']); ?></option>
										<?php 
										$i++;
									}?>   
										</select>

												
											</div>
											<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
												<input type="number"  class="form-control " id="alt_Contact_num" name="alt_Contact_num" value="<?php echo $getdocinfo[0]['secondary_contact_num']; ?>"  autocomplete="true"  > 
											</div>
										</div>
								</div>
								
							</div>	
							<div class="row">
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Address (Current)<span class="red">*</span></label>
										<input  type="text" class="form-control" id="address"  name="address" placeholder="Address"  autocomplete="true" value="<?php echo $getdocinfo[0]['ref_address']; ?>">
									</div>
								</div>
								<?php 
								$SrcName= mysqlSelect("*","countries","country_id='".$getdocinfo[0]['country_of_origin']."'","country_name asc","","","");
								?>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Country of Origin<span class="red">*</span></label>
										<select id="country_of_origin"  name="country_of_origin" class="form-control"  >
										
										<option value="<?php echo $SrcName[0]['country_id'];  ?>" ><?php echo $SrcName[0]['country_name'];  ?></option>

										<?php 
										$SrcName= mysqlSelect("*","countries","","country_name asc","","","");
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
							
		<!--------------------------------------Academic Information---------------------------------->
							<?php
							$doctor_academics = mysqlSelect("*","doctor_academics","md5(doc_id)='".$ref_id."'","","","");
								
							?>
							<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">Academic Information <button type="button" class="btn btn-primary academic_add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3> 
							<div class="academic_user-details">
								<div class="academic_use_data">
									<?php 
									if(!empty($doctor_academics)){
											foreach($doctor_academics as $doctor_academics_list) {
												
									?>
									<input type="hidden" id="id" name="id[]"  value="<?php echo $doctor_academics_list['id']; ?>" />
									<div class="row" >
									
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
											<label for="formGroupExampleInput2">Type of Qualification<span class="red">*</span></label>
												<input  type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"  autocomplete="true" value="<?php echo $doctor_academics_list['qualification_type']; ?>"> 
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Country<span class="red">*</span></label>
												<select name="acd_doc_country[]"  class="form-control" id="doc_country" onchange="return getState(this.value);" >
												<option value="<?php echo $doctor_academics_list['country']; ?>" myTag="100"  selected><?php echo $doctor_academics_list['country']; ?></option>
												
												<?php
														$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
												<input  type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"  autocomplete="true" value="<?php echo $doctor_academics_list['city']; ?>">
											</div>
										</div>					
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
												<input  type="date" class="form-control" id="acd_Start_Date" name="acd_Start_Date[]" placeholder="Start Date"  value="<?php echo $doctor_academics_list['start_date']; ?>">
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">End Date</span></label>
												<input 
												type="date" class="form-control" id="acd_End_Date" name="acd_End_Date[]" placeholder="End Date" value="<?php echo $doctor_academics_list['end_date']; ?>">
											</div>
										</div>
										
										
										<input type="button" name="delete" id="delete" class="fa fa-trash-o" style="color:red;float: right;" aria-hidden="true" value="Delete" onclick="deletefunction(<?php echo $doctor_academics_list['id']; ?>,'1');"> <!--i class="fa fa-trash-o" style="color:red" aria-hidden="true" onclick ="deletefunction(1);" ></i-->
										
										
									</div>
									
									
									
									
								<?php
								} 
								}
								else {
								?>
								
								<input type="hidden" id="id" name="id[]"  value="0" />
									<div class="row" >
									
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
											<label for="formGroupExampleInput2">Type of Qualification<span class="red">*</span></label>
												<input  type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"  autocomplete="true" value="<?php echo $doctor_academics_list['qualification_type']; ?>"> 
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Country<span class="red">*</span></label>
												<select name="acd_doc_country[]"  class="form-control" id="doc_country" onchange="return getState(this.value);" >
												<option value="<?php echo $doctor_academics_list['country']; ?>" myTag="100"  selected><?php echo $doctor_academics_list['country']; ?></option>
												
												<?php
														$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
												<input  type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"  autocomplete="true" value="<?php echo $doctor_academics_list['city']; ?>">
											</div>
										</div>					
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
												<input  type="date" class="form-control" id="acd_Start_Date" name="acd_Start_Date[]" placeholder="Start Date"  value="<?php echo $doctor_academics_list['start_date']; ?>">
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">End Date</span></label>
												<input 
												type="date" class="form-control" id="acd_End_Date" name="acd_End_Date[]" placeholder="End Date" value="<?php echo $doctor_academics_list['end_date']; ?>">
											</div>
										</div>
									</div>
									
								<?php } ?>
								
							
								</div>
							</div>
								
		<!-------------------------------------- Work History ---------------------------------->	
							<?php
								//echo $ref_id;
								$doc_work_exp = mysqlSelect("*","doc_work_exp","md5(doc_id)='".$ref_id."'","","","");
								
								
							?>
				
							<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">Work History <button type="button" class="btn btn-primary add_work_his_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>Add New</button></h3>
							<div class="user-details1">
								<div class="work_his_data">
									<?php 
										if(!empty($doc_work_exp))
										{
											foreach($doc_work_exp as $doc_work_exp_list) 
											{
											
												$Phone_Number = $doc_work_exp_list['Phone_Number'];
												$Phone_Number=explode(" ",$Phone_Number);
											
									?>
									<input type="hidden" id="wrk_id" name="wrk_id[]"  value="<?php echo $doc_work_exp_list['id']; ?>" />
									
								
									<div class="row">
												
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Institution Name<span class="red">*</span></label>
												<input  type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"  autocomplete="true" value="<?php echo $doc_work_exp_list['Institution_Name']; ?>">
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="inputState">Work Type<span class="red">*</span></label>
													 <select id="work_type"  name="work_type[]" class="form-control" >
													<option value="<?php echo $doc_work_exp_list['work_type']; ?>" selected><?php echo $doc_work_exp_list['work_type']; ?></option>
														<option>Clinic</option>
														<option>Hospital</option>
													</select>
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
												<div class="form-group">
													<label for="formGroupExampleInput2">Communication Address (Institution)<span class="red">*</span></label>
												<input  type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"  autocomplete="true" value="<?php echo $doc_work_exp_list['Communication_Address']; ?>">
												</div>
											</div>
											
										
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<label for="formGroupExampleInput2">Phone Number (Institution)<span class="red"></span></label>
												<div class="row">
												<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
												<select id="Phone_Country_code"   name="Phone_Country_code[]" class="form-control" >
												<option value="<?php echo $doc_work_exp[0]['phone_num_extension']; ?>" ><?php echo $doc_work_exp[0]['phone_num_extension']; ?></option>
												<?php 
												$SrcName1= mysqlSelect("*","countries","","","","","");
												$i=30;
												foreach($SrcName1 as $srcList){ ?>
												<option value="<?php echo stripslashes($srcList['country_id']);?>" > 
												<?php echo stripslashes($srcList['ph_extn']);?></option>
												<?php 
												$i++;
												}?>   
												</select>
												</div>
													<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
														<input type="number"  class="form-control no-spinner" id="Phone_Number"  name="Phone_Number[]" value="<?php echo $doc_work_exp[0]['Phone_Number']; ?>" >
													</div>
												</div>
										</div>
										
											
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
												<input   type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date" value="<?php echo $doc_work_exp_list['work_Start_Date']; ?>">
											</div>
										</div>
										
										
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">End Date</label>
												<input   type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date" value="<?php echo $doc_work_exp_list['work_End_Date']; ?>">
											</div>
										</div>
									
									</div>
									
									<input type="button" name="delete" id="delete" class="fa fa-trash-o" style="color:red;float: right;" aria-hidden="true" value="Delete" onclick="deletefunction(<?php echo $doc_work_exp_list['id']; ?>,'2');">
								
								<?php 
								} 
								}
								else {
								?>
								
									<input type="hidden" id="wrk_id" name="wrk_id[]"  value="0" />
								
									<div class="row">
												
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Institution Name<span class="red">*</span> </label>
												<input  type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"  autocomplete="true" value="<?php echo $doc_work_exp_list['Institution_Name']; ?>">
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="inputState">Work Type<span class="red">*</span></label>
													 <select id="work_type"  name="work_type[]" class="form-control" >
													<option value="<?php echo $doc_work_exp_list['work_type']; ?>" selected><?php echo $doc_work_exp_list['work_type']; ?></option>
														<option>Clinic</option>
														<option>Hospital</option>
													</select>
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
												<div class="form-group">
													<label for="formGroupExampleInput2">Communication Address (Institution)<span class="red">*</span></label>
												<input  type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"  autocomplete="true" value="<?php echo $doc_work_exp_list['Communication_Address']; ?>">
												</div>
											</div>
											
										
									</div>
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<label for="formGroupExampleInput2">Phone Number (Institution)<span class="red"></span></label>
												<div class="row">
												<div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
												<select id="Phone_Country_code"   name="Phone_Country_code[]" class="form-control" >
												<option value="<?php echo $doc_work_exp_list[0] ?>" ><?php echo $contact1[0] ?></option>
												<?php 
												$SrcName1= mysqlSelect("*","countries","","","","","");
												$i=30;
												foreach($SrcName1 as $srcList){ ?>
												<option value="<?php echo stripslashes($srcList['ph_extn']);?>" /> 
												<?php echo stripslashes($srcList['ph_extn']);?></option>
												<?php 
												$i++;
												}?>   
												</select>
												</div>
													<div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
														<input type="number"  class="form-control no-spinner" id="Phone_Number"  name="Phone_Number[]" value="<?php echo $contact1[1] ?>" >  
													</div>
												</div>
										</div>
										
											
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
												<input   type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date" value="<?php echo $doc_work_exp_list['work_Start_Date']; ?>">
											</div>
										</div>
										
										
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">End Date</label>
												<input   type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date" value="<?php echo $doc_work_exp_list['work_End_Date']; ?>">
											</div>
										</div>
									
									</div>
									
									<?php } ?>
									
									
									
								</div>
							</div>
							
							<!-------------------------------------- Other Information ---------------------------------->	
	
							<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">Other Information</h3>
				
							<div class="row">
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group">
										<label for="formGroupExampleInput2">Area of Interest<span class="red">*</span></label>
										<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Area of interest"> -->
										<textarea required class="form-control" rows="5"  id="Area_of_interest" name="Area_of_interest" placeholder="Enter area of your interest..." ><?php echo $getdocinfo[0]['doc_interest']; ?></textarea>
									</div>
								</div>
							</div>
										
							<div class="row">
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group">
										<label for="formGroupExampleInput2">Professional Contribution</label>
										<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Professional Construction"> -->
										<textarea class="form-control" rows="5" id="Professional_Contribution" name="Professional_Contribution" placeholder="Enter your professional contribution..."><?php echo $getdocinfo[0]['doc_contribute']; ?></textarea>
									</div>
								</div>
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group" style="margin-top:50px;">
										<label for="exampleFormControlFile1">Professional Contribution</label>
										<input type="file" class="form-control-file" id="Professional_Construction_file" name="txtProfessional_Construction_file">
									</div>
									
									<?php  
									if(!empty($getdocinfo[0]['Professional_Construction_file'])) 
									{
										$Prof_url =IMG_URL_VIEW."Doc_Prof_Certificate/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['Professional_Construction_file'];
										?>
									<div>
									<a href="<?php echo $Prof_url; ?>" target="_blank"  data-toggle="popover-hover" data-img="<?php echo $Prof_url; ?>"><i class="fa fa-download"></i> <?php echo $getdocinfo[0]['Professional_Construction_file']; ?></a>
									</div>
									<?php						
									}
									?>
									
								</div>
							</div>
							
							<div class="row">
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group">
										<label for="formGroupExampleInput2">Research Details</label>
										<!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Research Details"> -->
										<textarea class="form-control" rows="5" id="Research_Details" name="Research_Details" placeholder="Enter your research details..."><?php echo $getdocinfo[0]['doc_research']; ?></textarea>
									</div>
								</div>
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group" style="margin-top:50px;">
										<label for="exampleFormControlFile1">Research Details</label>
										<input type="file" class="form-control-file" id="Research_Details_file" name="txtResearch_Details_file">
									</div>
									
									<?php 
									if(!empty($getdocinfo[0]['Research_Details_file']))
									{
										$Rese_url =IMG_URL_VIEW."Doc_Research_Certificate/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['Research_Details_file'];
										?>
									<div>
									<a href="<?php echo $Rese_url; ?>" target="_blank"  data-toggle="popover-hover" data-img="<?php echo $Rese_url; ?>"><i class="fa fa-download"></i> <?php echo $getdocinfo[0]['Research_Details_file']; ?></a>
									</div>
									<?php						
									}
									?>
								</div>
							</div>
							
							<div class="row">
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group">
										<label for="formGroupExampleInput2">Publications</label>
										<!-- <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Publications"> -->
										<textarea class="form-control" rows="5" id="Publications" name="Publications" placeholder="Enter publications if any..."><?php echo $getdocinfo[0]['doc_pub']; ?></textarea>
									</div>
								</div>
								<div class="col-xs-12 col-md-6 col-sm-6">
									<div class="form-group" style="margin-top:50px;">
										<label for="exampleFormControlFile1">Publications</label>
										<input type="file" class="form-control-file" id="Publications_file" name="txtPublications_file">
									</div>
									
									<?php 
									

									if(!empty($getdocinfo[0]['Publications_file'])) 
									{
										$Public_url =IMG_URL_VIEW."Doc_Research_Certificate/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['Publications_file'];
										?>
									<div>
									<a href="<?php echo $Public_url; ?>" target="_blank"  data-toggle="popover-hover" data-img="<?php echo $Public_url; ?>"><i class="fa fa-download"></i> <?php echo $getdocinfo[0]['Publications_file']; ?></a>
									</div>
									<?php						
									}
									?>
								</div>
							</div>
							<?php
							//echo $ref_id;
							$doctor_registration = mysqlSelect("*","doctor_registration_details","md5(doc_id)='".$ref_id."'","","","");
								
							?>
	<!--------------------------------------------------------  Registration History  ------------------------------------------->
							<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">Registration History <button type="button" class="btn btn-primary add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i> Add New</button></h3>
								<div class="user-details">
								<div class="use_data">
								<?php 
								if(!empty($doctor_registration))
								{
										foreach($doctor_registration as $doctor_registration_list) {
											// echo $doctor_registration_list['reg_det_id'];
											 if(!empty($doctor_registration_list['reg_certificate'])){
												 
												$required=""; 
											 }
											 else
											 {
												 $required="required";
											 }
											
								?>
								<div class="row">
								<input type="hidden" id="reg_det_id" name="reg_det_id[]"  value="<?php echo $doctor_registration_list['reg_det_id']; ?>" />
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											
											<label for="formGroupExampleInput2">Medical Council registered with<span class="red">*</span></label>
											<input type="text"  autocomplete="off" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="" value="<?php echo $doctor_registration_list['council_name']; ?>" >
										</div>
									</div>
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Registration Number<span class="red">*</span></label>
											<input  type="text" autocomplete="off" class="form-control" id="Reg_Num" name="Reg_Num[]"  value="<?php echo $doctor_registration_list['reg_num']; ?>">
										</div>
									</div>
									
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="exampleFormControlFile1">Upload Registration Certificate<span class="red">*</span></label>
											<input type="file"   class="form-control-file" id="txtUpload_Reg_cer" name="txtUpload_Reg_cer[]" <?php echo $required; ?> >
										</div>
										<?php  
										//echo"values =".$doctor_registration_list['reg_certificate'];
										if(!empty($doctor_registration_list['reg_certificate'])) 
										{
											$DocCert_url=IMG_URL_VIEW."DocCertificate/".$getdocinfo[0]['ref_id']."/".$doctor_registration_list['reg_certificate'];
											
											?>
											<div>
											<a href="<?php echo $DocCert_url; ?>" target="_blank"  data-toggle="popover-hover" data-img="<?php $DocCert_url; ?>"><i class="fa fa-download"></i> <?php echo $doctor_registration_list['reg_certificate']; ?></a>
											</div>
											<?php						
										}
									?>
										
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label>
											<input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value="<?php echo $doctor_registration_list['reg_date']; ?>">
										</div>
									</div>
									
									<input type="button" name="delete" id="delete" class="fa fa-trash-o" style="color:red;float: right;" aria-hidden="true" value="Delete" onclick="deletefunction(<?php echo $doctor_registration_list['reg_det_id']; ?>,'3');">
									
								</div>
								
								
								<?php 
								} 
								}
								else {
								?>
								
								<div class="row">
								<input type="hidden" id="reg_det_id" name="reg_det_id[]"  value="0" />
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											
											<label for="formGroupExampleInput2">Medical Council registered with<span class="red">*</span></label>
											<input type="text"   class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="" value="<?php echo $doctor_registration[0]['council_name']; ?>" >
										</div>
									</div>
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Registration Number<span class="red">*</span></label>
											<input  type="text" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="" value="<?php echo $doctor_registration[0]['reg_num']; ?>">
										</div>
									</div>
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="exampleFormControlFile1">Upload Registration Certificate<span class="red">*</span></label>
											<input type="file" class="form-control-file" id="txtUpload_Reg_cer" name="txtUpload_Reg_cer[]">
										</div>
										
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label>
											<input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value="<?php echo $doctor_registration[0]['reg_date']; ?>">
										</div>
									</div>
								</div>
								
								<?php } ?>
								
								</div>
								</div>
		<!--------------------------------------------------------  Passport Information  ------------------------------------------->
								<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
								<h3 style="padding-bottom:10px;color:#16B4B5;">Passport Information</h3>
								<div class="row">
								<?php $Medical_Council = mysqlSelect("*"," referal","md5(ref_id)='".$ref_id."'","","","");?>
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Passport Number<span class="red">*</span></label>
											<input  type="text"  class="form-control" id="passport_num" name="passport_num" placeholder="Passport Number" value="<?php echo $getdocinfo[0]['passport_num']; ?>">
										</div>
									</div>
									<div class="col-xs-12 col-md-4 col-sm-4">
										<div class="form-group">
											<label for="formGroupExampleInput2">Passport - Country<span class="red">*</span></label>
											<select  id="passport_country"   name="passport_country" class="form-control">
												<option value="India" myTag="100"  selected><?php echo $getdocinfo[0]['passport_country']; ?></option>
											
											<?php
													$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
											<input  type="file"  class="form-control-file" id="txtpassport_file" name="txtpassport_file" >
										</div>
										<?php  
										
										if(!empty($getdocinfo[0]['txtpassport_file'])) 
										{
											$passport_url =IMG_URL_VIEW."Doc_passport_file/".$getdocinfo[0]['ref_id']."/".$getdocinfo[0]['txtpassport_file'];
											?>
											<div>
											<a href="<?php echo $passport_url; ?>" target="_blank"  data-toggle="popover-hover" data-img="<?php echo $passport_url; ?>"><i class="fa fa-download"></i> <?php echo $getdocinfo[0]['txtpassport_file']; ?></a>
											</div>
											<?php						
										}
										?>
									</div>
								</div>
								
								
								
		<!--------------------------------------------------------  Location  Information  ------------------------------------------->
		
		
							<div class="hrline" style=" border-top: 1px dashed gray;margin-top:10px;"></div>
							<h3 style="padding-bottom:10px;color:#16B4B5;">Location Information</h3>
							<div class = "row" >
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Latitude<span class="red">*</span></label>
										<input  type="text"  class="form-control" id="geo_latitude" name="geo_latitude" placeholder="Latitude" value="<?php echo $getdocinfo[0]['geo_latitude']; ?>">
									</div>
								</div>
								<div class="col-xs-12 col-md-4 col-sm-4">
									<div class="form-group">
										<label for="formGroupExampleInput2">Longitude<span class="red">*</span></label>
										<input  type="text"  class="form-control" id="geo_longitude" name="geo_longitude" placeholder="Longitude" value="<?php echo $getdocinfo[0]['geo_longitude']; ?>">
									</div>
								</div>
							</div>
		<!--------------------------------------------------------  Verification   Information  ------------------------------------------->
								<div class="hrline" style=" border-top: 1px dashed gray;margin-top:20px;padding-bottom:20px;"></div>
									
									<div class="row">
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2" >Verified By<span class="red result">*</span></label>
												
												<select name="verified_by"   class="form-control" id="verified_by" onchange="return getState(this.value);">
												
												
												<?php if($getdocinfo[0]['verified_by_medisense_user'] !=0) {
													$ChkName = mysqlSelect("*", "chckin_user", "chk_userid= '".$getdocinfo[0]['verified_by_medisense_user']."'", "", "", "", "");	?> 
													<option value="<?php echo $ChkName[0]['chk_userid']; ?>"  selected><?php echo $ChkName[0]['chk_username']; ?></option>
												<?php } else { ?>
												<option value=""  selected></option>
												<?php } ?>
												
												<?php
														$ChkName = mysqlSelect("*", "chckin_user", "medisense_user=1", "chk_username asc", "", "", "");
														$i       = 30;
														foreach ($ChkName as $ChkNameList) {
														?> 
														<option value="<?php echo stripslashes($ChkNameList['chk_userid']); ?>" />
														<?php
															echo stripslashes($ChkNameList['chk_username']);
														?></option>

														<?php
														$i++;
														}
												?>
												
												</select>
												<i></i>
											</div>
										</div>
									</div>	
									
									<div class="row">
										<div class="col-xs-12 col-md-6 col-sm-6">
											<div class="form-group">
												<label><input type="checkbox" id="videoVeification" name="videoVeification" <?php echo ($getdocinfo[0]['video_veification_status']==1 ? 'checked' : '');?> > Video verification done</label>
											</div>
										</div>
									</div>
								
									<div class="row">
										<div class="col-xs-12 col-md-6 col-sm-6">
											<div class="form-group">
												<label for="formGroupExampleInput2">Note By Medisense</label>
												
												<textarea class="form-control" rows="5" id="Note_By_Medisense" name="Note_By_Medisense" placeholder="Enter note if any..."><?php echo $getdocinfo[0]['comments_by_medisense'] ?></textarea>
											</div>
										</div>
										<div class="col-xs-12 col-md-6 col-sm-6">
											<div class="form-group">
												<label for="formGroupExampleInput2">Note By Medical Professionist</label>
												
												<textarea class="form-control" rows="5" id="Note_By_Medical_Professionist" name="Note_By_Medical_Professionist" placeholder="Enter note if any..."><?php echo $getdocinfo[0]['comments_by_medical_professional'] ?></textarea>
											</div>
										</div>
										
									</div>
		
									<div class="row" style="padding-bottom:40px;">
										<div class="col-xs-12 col-md-4 col-sm-4">
										<?php if ($getdocinfo[0]['video_veification_status']==0) { ?>
											<input type ="button" name="Verified_By_Medisense" id="Verified_By_Medisense" value="Verified by Medisense"  class="btn btn-info" onclick="varifiedfuc(1);" />
											<input type="hidden" id="doc_id" name="doc_id" value="<?php echo $getdocinfo[0]['ref_id'];  ?>"  />
										<?php }?>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<input type="submit" name="submit" value="Update"  class="btn btn-info" style="margin-right:50px;">
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
										<?php if ($getdocinfo[0]['verified_by_medical_professional']==0) { ?>
										
											<input type ="button" name ="Verified_By_Medical_Proffessionalist" id="Verified_By_Medical_Proffessionalist"value ="Verified by Medical Professionist" class="btn btn-info" onclick="varifiedfuc(2);" />
											<input type="hidden" id="doc_id" name="doc_id" value="<?php echo $getdocinfo[0]['ref_id'];   ?>"  />
										<?php }?>
										</div>
									</div>
		 
			</form>
		</div>
	</div>

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
		
	$(".user-details1").append('<div class="work_his_data"><input type="hidden" id="wrk_id" name="wrk_id[]"  value="0" /><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Institution Name</label><input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="inputState">Work Type</label><select id="work_type"  name="work_type[]" class="form-control"><option selected>Work Type</option><option>Clinic</option><option>Hospital</option></select></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Communication Address (Institution)</label><input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><label for="formGroupExampleInput2">Phone Number (Institution)<span class="red">*</span></label><div class="row"><div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;"><select id="Phone_Country_code" name="Phone_Country_code[]" class="form-control" ><option value="<?php echo $doc_work_exp[0]["phone_num_extension"]; ?>" ><?php echo $doc_work_exp[0]["phone_num_extension"]; ?></option><?php $SrcName1= mysqlSelect("*","countries","","","","",""); $i=30; foreach($SrcName1 as $srcList){ ?><option value="<?php echo stripslashes($srcList["ph_extn"]); ?>" ><?php echo stripslashes($srcList["ph_extn"]); ?></option><?php $i++; } ?></select></div><div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;"><input type="number"  class="form-control no-spinner" id="Phone_Number"  name="Phone_Number[]" value="<?php echo $doc_work_exp[0]["Phone_Number"]; ?>" ></div></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date"></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');
	
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
			//$(".user-details").append('<div class="use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Medical Council Registered with</label><input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number</label><input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate</label><input type="file" class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');

				$(".user-details").append('<div class="use_data"><div class="row"><input type="hidden" id="reg_det_id" name="reg_det_id[]"  value="0" /><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><?php $Medical_Council = mysqlSelect("*","doc_registration","md5(ref_id)='".$ref_id."'","","","");?><label for="formGroupExampleInput2">Medical Council registered with<span class="red">*</span></label><input type="text"   class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="" autocomplete="off" value="<?php echo $Medical_Council[0]["medical_Council_reg"]; ?>"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number<span class="red">*</span></label><input  type="text" class="form-control" autocomplete="off" id="Reg_Num" name="Reg_Num[]"  placeholder="" value="<?php echo $Medical_Council[0]["reg_Num"]; ?>"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate<span class="red">*</span></label><input type="file"   class="form-control-file" id="txtUpload_Reg_cer" name="txtUpload_Reg_cer[]"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label><input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value="" ></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');   
		});
		$("body").on("click",".remove-btn",function(e){
			
		$(this).parents('.use_data').remove();
		//the above method will remove the user_data div
		});
</script>   

<script>    
		$(".academic_add_details").click(function(){
			//APPEND DETAILS
			$(".academic_user-details").append('<div class="academic_use_data"><input type="hidden" id="id" name="id[]"  value="0" /><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fa fa-trash-o" style="color:red" aria-hidden="true"></i></button></div>');


		});
		$("body").on("click",".remove-btn",function(e){
			//alert("fgfgfgfgfg");
		$(this).parents('.academic_use_data').remove();
		//the above method will remove the user_data div
		});
</script>     


<script languages="javascript">
$('select').selectpicker();
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="jquery.multiselect.js"></script>
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
	function varifiedfuc(type)
	{
			
			var verified_by = $('#verified_by').val();
			var data_val = $('#Verified_By_Medisense').val();
			var doc_id = $('#doc_id').val();
			var Medisense = $('#Note_By_Medisense').val();
			var Medical_Professionist = $('#Note_By_Medical_Professionist').val();
			
			console.log(type);
			console.log("verified_by: "+verified_by);
			console.log("Medisense: "+Medisense);
			console.log("Medical_Professionist: "+Medical_Professionist);
			console.log("doc_id: "+doc_id);
			
			if(type==1)
			{//VERIFIED BY Medisense
					
				if(verified_by=="")
					{
						alert("Please choose the user...");
						
						return false;
						
					}
				else
					{
						
				
					var x = document.getElementById("videoVeification").checked;
					if(x == true) {
						var video_check = 1;
					}
					else {
						var video_check = 0;
					}
					//console.log("video_check: "+video_check);
						
					//	alert("Hi choose");
						$.ajax({
							type: "POST",
							url: "update_verification.php",
							data:{"doc_id":doc_id,"verified_by":verified_by,"update_type":type,"Medisense":Medisense,"Medical_Professionist":Medical_Professionist,"Video_Verified":video_check},
							dataType: 'json',
							success: function(data)
							{
								console.log("data: "+data);
								console.log("status: "+data.status);
								
								if(data.status = true) {
									$("#Verified_By_Medisense").hide(); 
								}
								else {
									$("#Verified_By_Medisense").show();
								}
							}
								
							}); 
					}
					
			}
			else{//VERIFIED BY MEDICAL PROFFESSIONAL
				
					$.ajax({
					type: "POST",
					url: "update_verification.php",
					data:{"doc_id":doc_id,"update_type":type,"Medisense":Medisense,"Medical_Professionist":Medical_Professionist},
					dataType: 'json',
					success: function(data)
					{
						console.log("data: "+data);
						console.log("status: "+data.status);
						
						if(data.status = true) {
							$("#Verified_By_Medical_Proffessionalist").hide(); 
						}
						else {
							$("#Verified_By_Medical_Proffessionalist").show();
						}
					}
						
					});
				
			}
			
			
	}
	
	
</script>
	
<script>
function deletefunction(id,type)
	{
				
				$.ajax({
				type: "POST",
				url: "delete_doc_info.php",
				data:{"id":id,"type":type},
				success: function(data)
				{
					 location.reload();
				}				
				});
	}
</script>