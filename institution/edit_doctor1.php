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

//echo $_GET['doc_id'];
$get_docInfo = mysqlSelect("*","referal ","ref_id='".$_GET['doc_id']."'","","","","");
$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_GET['doc_id']."'","","","","");
  
 $GetTimeSlot = mysqlSelect("*", "doc_appointment_slots", "doc_id='".$_GET['doc_id']."' and doc_type='1' and hosp_id='".$get_provHospInfo[0]['hosp_id']."'", "", "", "", "");                    
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
                    <h2>Doctor List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Doctor List</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
			 <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">				  
				<div class="ibox float-e-margins" id="addHospSection">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> EDIT HOSPITAL DOCTOR</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details1.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <input type="hidden" name="Prov_Id"	value="<?php echo $_GET['doc_id']; ?>" />
							   <div class="form-group"><label class="col-sm-2 control-label">Doctor Profile picture</label>
									
                                        <?php 
										$profile_url =IMG_URL_VIEW."Doc/".$get_docInfo[0]['ref_id']."/".$get_docInfo[0]['doc_photo'];
									
										?>
                                    <div class="col-sm-10 pull-right"><img src="<?php echo $profile_url; ?>" width="80" title="logo" />
									<input type="file"  name="txtPhoto">
                                       Change Profile picture
                                   </div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Doctor Name </label>

                                    <div class="col-sm-10"><input type="text" name="txtDoc" value="<?php echo $get_docInfo[0]['ref_name']; ?>" class="form-control"></div>
                                </div>

								<script type="text/javascript">
									function getState(val) { 
										var data_val = $("#txtCountry option:selected").attr("myTag");
										$('#cntryid').val(data_val);
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
								
							   <div class="form-group"><label class="col-sm-2 control-label">Country </label>

                                    <div class="col-sm-10">
                                    	<input type="hidden" id="cntryid" name="countryId">
                                    	<select class="form-control autotab" name="txtCountry" id="txtCountry" onchange="return getState(this.value);">
                                    	
												<option value="<?php echo $get_docInfo[0]['doc_country']; ?>" selected> <?php echo $get_docInfo[0]['doc_country']; ?></option>
												
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" myTag="<?php echo stripslashes($CountryList['country_id']); ?> " />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
												</select>
									</div>
									</div>
									<div class="form-group"><label class="col-sm-2 control-label">State</label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="slctState" id="slctState" placeholder="State"  >
												<option value="<?php echo $get_docInfo[0]['doc_state']; ?>" selected><?php echo $get_docInfo[0]['doc_state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$get_docInfo[0]['doc_country']."'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo $StateList["state_name"];	?>">
												<?php echo $StateList["state_name"]; ?>
												</option>												
												<?php
												}
												?>
												</select>
									</div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">City</label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" value="<?php echo $get_docInfo[0]['ref_address']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Select Hospital</label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="selectHosp" id="selectHosp" placeholder="State"  >
												<option value="" selected>---Please Select---</option>
												<?php
													$HospName= mysqlSelect("*","hosp_tab","company_id='".$admin_id."'","hosp_id desc","","","");
													$i=30;
														foreach($HospName as $HospList){
															if($HospList['hosp_id']==$get_provHospInfo[0]['hosp_id']){ 
																?>
														   <option value="<?php echo stripslashes($HospList['hosp_id']);?>" selected>
															<?php echo stripslashes($HospList['hosp_name']);?></option>
															<?php } ?>
															<option value="<?php echo stripslashes($HospList['hosp_id']);?>" />
															<?php echo $HospList['hosp_name']."&nbsp;".$HospList['hosp_city']; ?></option>												
														
														<?php 	$i++;
														}?>  
												</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Select Specialization </label>

                                    <div class="col-sm-10"><select class="form-control autotab" name="slctSpec" id="slctSpec" placeholder="State"  >
												<option value="" >Select Specialization</option>
												<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['doc_spec']){ ?> 
												<!--<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>-->
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" <?php if($DeptList['spec_id']==$get_docInfo[0]['doc_spec']){ echo "selected"; } ?> /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														$i++;
												}?> 
												</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Qualification </label>

                                    <div class="col-sm-10"><input type="text" name="txtQual" value="<?php echo $get_docInfo[0]['doc_qual']; ?>" class="form-control"></div>
                                
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Years of Experience</label>

                                    <div class="col-sm-6"><input type="text" class="form-control" name="txtExp" value="<?php echo $get_docInfo[0]['ref_exp']; ?>"></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" value="<?php echo $get_docInfo[0]['ref_mail']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Mobile No.</label>

                                    <div class="col-sm-10"><input type="text" name="txtMobile" value="<?php echo $get_docInfo[0]['contact_num']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Website</label>

                                    <div class="col-sm-10"><input type="text" name="txtWebsite" value="<?php echo $get_docInfo[0]['ref_web']; ?>" class="form-control"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Area's of Interest, Expertise</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtInterest" rows="3"><?php echo $get_docInfo[0]['doc_interest']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Professional Contributions</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtContribute" rows="3"><?php echo $get_docInfo[0]['doc_contribute']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Research Details</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtResearch" rows="3"><?php echo $get_docInfo[0]['doc_research']; ?></textarea></div>
                                </div>	
								<div class="form-group"><label class="col-sm-2 control-label">Publications</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtPublication" rows="3"><?php echo $get_docInfo[0]['doc_pub']; ?></textarea></div>
                                </div>	
								<div class="form-group"><label class="col-sm-2 control-label">Online Opinion Cost(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="onopcost" value="<?php echo $get_docInfo[0]['on_op_cost']; ?>"></div>
									<label class="col-sm-2 control-label">Consultation Charge(Rs.)</label>

                                    <div class="col-sm-4"><input type="text" name="conscharge" value="<?php echo $get_docInfo[0]['cons_charge']; ?>" class="form-control"></div>
								
                                </div>	
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Email Id</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecEmail" value="<?php echo $get_docInfo[0]['secretary_email']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Secretary Phone</label>

                                    <div class="col-sm-10"><input type="text" name="txtSecPhone" value="<?php echo $get_docInfo[0]['secretary_phone']; ?>" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for tele opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="teleop" value="1" <?php if($get_docInfo[0]['tele_op']==1){ echo "checked"; } ?> > Yes
								</label><input type="text" name="teleopnumber" class="form-control" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" placeholder="Tele Op. contact no."></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Ready for video opinion ?</label>

                                <div class="col-sm-10"><label>
								<input type="checkbox" class="flat" name="videoop" value="1" <?php if($get_docInfo[0]['video_op']==1){ echo "checked"; } ?>> Yes
								</label><input type="text" id="videoopnumber" name="videoopnumber" class="form-control" value="<?php echo $get_docInfo[0]['video_op_contact']; ?>" placeholder="Video Op. contact no."></div>
								</div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Available timings for Tele/Video Opinion</label>

                                    <div class="col-sm-10"><input type="text" name="televidop_time" value="<?php echo $get_docInfo[0]['tele_video_op_timing']; ?>" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">No. of Patient per hour <span class="required">*</span></label>
								
                                    <div class="col-sm-10"><input type="number" name="num_slot" value="<?php echo $GetTimeSlot[0]['num_patient_hour']; ?>"  class="form-control" required="required"></div>
                                </div>
                               
							   
							   
							   
							     <div class="form-group"><label class="col-sm-2 control-label">Set Appointment Timing</label>

                        <div class="col-sm-10">
									

							<table border="" width="100%" id="tablesun">
								<thead>
									<tr>
										<th>Schedule</th>
										<th>From</th>
										<th>To</th>
									  <th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$GetTimeSlot1 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=1", "", "", "", "");
										
											foreach($GetTimeSlot1 as $daysList) 
											{
												
									?>
									<tr>
									<td>Sun<input type="hidden" id="Sunid" name="Sunid[]" value="1" ></td>
									<td>
										<select id="from" name="fromSun[]">
										<option value="" >Empty </option>
										<option value="00:00" <?php if($daysList['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
										<option value="00:30" <?php if($daysList['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
										<option value="01:00" <?php if($daysList['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
										<option value="01:30" <?php if($daysList['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
										<option value="02:00" <?php if($daysList['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
										<option value="02:30" <?php if($daysList['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
										<option value="03:00" <?php if($daysList['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
										<option value="03:30" <?php if($daysList['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
										<option value="04:00" <?php if($daysList['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
										<option value="04:30" <?php if($daysList['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
										<option value="05:00" <?php if($daysList['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
										<option value="05:30" <?php if($daysList['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
										<option value="06:00" <?php if($daysList['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
										<option value="06:30" <?php if($daysList['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
										<option value="07:00" <?php if($daysList['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
										<option value="07:30" <?php if($daysList['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
										<option value="08:00" <?php if($daysList['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
										<option value="08:30" <?php if($daysList['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
										<option value="09:00" <?php if($daysList['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
										<option value="09:30" <?php if($daysList['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
										<option value="10:00" <?php if($daysList['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
										<option value="10:30" <?php if($daysList['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
										<option value="11:00" <?php if($daysList['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
										<option value="11:30" <?php if($daysList['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
										<option value="12:00" <?php if($daysList['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
										<option value="12:30" <?php if($daysList['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
										<option value="13:00" <?php if($daysList['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
										<option value="13:30" <?php if($daysList['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
										<option value="14:00" <?php if($daysList['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
										<option value="14:30" <?php if($daysList['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
										<option value="15:00" <?php if($daysList['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
										<option value="15:30" <?php if($daysList['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
										<option value="16:00" <?php if($daysList['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
										<option value="16:30" <?php if($daysList['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
										<option value="17:00" <?php if($daysList['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
										<option value="17:30" <?php if($daysList['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
										<option value="18:00" <?php if($daysList['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
										<option value="18:30" <?php if($daysList['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
										<option value="19:00" <?php if($daysList['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
										<option value="19:30" <?php if($daysList['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
										<option value="20:00" <?php if($daysList['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
										<option value="20:30" <?php if($daysList['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
										<option value="21:00" <?php if($daysList['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
										<option value="21:30" <?php if($daysList['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
										<option value="22:00" <?php if($daysList['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
										<option value="22:30" <?php if($daysList['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
										<option value="23:00" <?php if($daysList['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
										<option value="23:30" <?php if($daysList['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
										</select>
									</td>
									<td>
										<select id="to" name="toSun[]">
											<option value="" >Empty </option>
											<option value="00:00" <?php if($daysList['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
											<option value="00:30" <?php if($daysList['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
											<option value="01:00" <?php if($daysList['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
											<option value="01:30" <?php if($daysList['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
											<option value="02:00" <?php if($daysList['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
											<option value="02:30" <?php if($daysList['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
											<option value="03:00" <?php if($daysList['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
											<option value="03:30" <?php if($daysList['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
											<option value="04:00" <?php if($daysList['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
											<option value="04:30" <?php if($daysList['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
											<option value="05:00" <?php if($daysList['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
											<option value="05:30" <?php if($daysList['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
											<option value="06:00" <?php if($daysList['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
											<option value="06:30" <?php if($daysList['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
											<option value="07:00" <?php if($daysList['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
											<option value="07:30" <?php if($daysList['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
											<option value="08:00" <?php if($daysList['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
											<option value="08:30" <?php if($daysList['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
											<option value="09:00" <?php if($daysList['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
											<option value="09:30" <?php if($daysList['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
											<option value="10:00" <?php if($daysList['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
											<option value="10:30" <?php if($daysList['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
											<option value="11:00" <?php if($daysList['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
											<option value="11:30" <?php if($daysList['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
											<option value="12:00" <?php if($daysList['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
											<option value="12:30" <?php if($daysList['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
											<option value="13:00" <?php if($daysList['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
											<option value="13:30" <?php if($daysList['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
											<option value="14:00" <?php if($daysList['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
											<option value="14:30" <?php if($daysList['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
											<option value="15:00" <?php if($daysList['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
											<option value="15:30" <?php if($daysList['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
											<option value="16:00" <?php if($daysList['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
											<option value="16:30" <?php if($daysList['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
											<option value="17:00" <?php if($daysList['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
											<option value="17:30" <?php if($daysList['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
											<option value="18:00" <?php if($daysList['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
											<option value="18:30" <?php if($daysList['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
											<option value="19:00" <?php if($daysList['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
											<option value="19:30" <?php if($daysList['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
											<option value="20:00" <?php if($daysList['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
											<option value="20:30" <?php if($daysList['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
											<option value="21:00" <?php if($daysList['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
											<option value="21:30" <?php if($daysList['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
											<option value="22:00" <?php if($daysList['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
											<option value="22:30" <?php if($daysList['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
											<option value="23:00" <?php if($daysList['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
											<option value="23:30" <?php if($daysList['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
										</select>
									</td>
								<td><input type="button" id="email" onclick="addrow(1);"  value="Add Row" /></td>
							</tr>
							
							
							<?php
							}
							
							if(empty($GetTimeSlot1))
							{
							?>
							
							<tr>
								<td >Sun<input type="hidden" id="Sunid" name="Sunid[]" value="1" ></td>
								<td>
									<select id="from" name="fromSun[]">
										<option value="">Select</option>	
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{
										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>

								</td>
								<td>
									<select id="to" name="toSun[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(1);"  value="Add Row" /></td>
							</tr>	
							<?php } ?>
							</tbody>
							</table>
									
							
							
									
							<table border="" width="100%" id="tablemon">
								<tbody>
									<?php

									$GetTimeSlot2 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=2", "", "", "", "");
									foreach($GetTimeSlot2 as $daysList2) 
									{
										
									?>
								<tr>
								
								<td style="padding-right: 31px;">Mon<input type="hidden" id="Monid" name="Monid[]" value="2" ></td>
								<td>
								<select id="from" name="fromMon[]">
								<option value="" >Empty </option>
								
								<option value="00:00" <?php if($daysList2['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList2['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList2['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList2['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList2['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList2['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList2['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList2['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList2['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList2['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList2['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList2['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList2['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList2['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList2['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList2['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList2['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList2['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList2['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList2['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList2['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList2['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList2['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList2['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList2['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList2['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList2['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList2['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList2['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList2['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList2['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList2['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList2['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList2['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList2['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList2['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList2['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList2['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList2['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList2['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList2['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList2['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList2['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList2['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList2['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList2['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList2['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList2['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
								<!--input type="time" id="from" name="fromMon[]"  -->
								</td>
								<td>
								<select id="to" name="toMon[]">
								<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList2['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList2['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList2['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList2['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList2['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList2['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList2['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList2['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList2['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList2['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList2['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList2['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList2['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList2['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList2['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList2['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList2['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList2['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList2['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList2['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList2['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList2['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList2['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList2['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList2['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList2['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList2['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList2['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList2['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList2['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList2['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList2['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList2['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList2['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList2['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList2['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList2['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList2['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList2['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList2['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList2['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList2['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList2['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList2['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList2['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList2['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList2['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList2['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
										<!--input type="time" id="to" name="toMon[]" -->
								</td>
				
								<td><input type="button" id="email" onclick="addrow(2);"  value="Add Row" /></td>
							</tr>
							
							<?php
							} 
							if(empty($GetTimeSlot2))
							{
							?>
							
							<tr>
								<td style="padding-right: 31px;">Mon<input type="hidden" id="Monid" name="Monid[]" value="2" ></td>
								<td>
									<select id="from" name="fromMon[]">
										<option value="">Select</option>	
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{
										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>

								</td>
								<td>
									<select id="to" name="toMon[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(2);"  value="Add Row" /></td>
							</tr>	
							<?php } ?>
							</tbody>
							</table>
									
							<table border="" width="100%" id="tableTue">
							<thead>
								
							</thead>
							<tbody>
								<?php

								$GetTimeSlot3 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=3", "", "", "", "");
									foreach($GetTimeSlot3 as $daysList3) 
									{
								?>
								<tr>
									<!--td style="padding-right: 36px;">
									<input type="hidden" id="Tueid" name="Tueid[]" value="3"><?php //echo $daysList3['da_name']; ?>
									</td-->
									<td style="padding-right: 36px;">Tue<input type="hidden" id="Tueid" name="Tueid[]" value="3" ></td>
									<td>
								<select id="from" name="fromTue[]">
								<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList3['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList3['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList3['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList3['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList3['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList3['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList3['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList3['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList3['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList3['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList3['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList3['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList3['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList3['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList3['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList3['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList3['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList3['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList3['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList3['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList3['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList3['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList3['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList3['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList3['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList3['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList3['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList3['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList3['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList3['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList3['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList3['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList3['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList3['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList3['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList3['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList3['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList3['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList3['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList3['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList3['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList3['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList3['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList3['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList3['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList3['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList3['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList3['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							<!--input type="time" id="from" name="fromTue[]" -->
							</td>
							<td>
							<!--input type="time" id="to" name="toTue[]" -->
							<select id="to" name="toTue[]">
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList3['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList3['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList3['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList3['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList3['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList3['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList3['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList3['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList3['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList3['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList3['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList3['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList3['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList3['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList3['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList3['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList3['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList3['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList3['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList3['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList3['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList3['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList3['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList3['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList3['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList3['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList3['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList3['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList3['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList3['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList3['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList3['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList3['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList3['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList3['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList3['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList3['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList3['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList3['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList3['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList3['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList3['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList3['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList3['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList3['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList3['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList3['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList3['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>

							</td>
							<td><input type="button" id="email" onclick="addrow(3);"  value="Add Row" /></td>
							</tr>
									
								<?php } 
								
								if(empty($GetTimeSlot3)){
							?>
							
							<tr>
								<td style="padding-right: 36px;">Tue<input type="hidden" id="Tueid" name="Tueid[]" value="3" ></td>
								<td>
									<select id="from" name="fromTue[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>

								</td>
								<td>
									<select id="to" name="toTue[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(3);"  value="Add Row" /></td>
							</tr>
							<?php } ?>
							</tbody>
							</table>
							
							
							
							
							<table border="" width="100%" id="tableWed">
							<thead>
								
							</thead>
							<tbody>
								<?php

								$GetTimeSlot4 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=4", "", "", "", "");
									foreach($GetTimeSlot4 as $daysList4) 
									{
								?>
								<tr>
									<td style="padding-right: 31px;">
									<input type="hidden" id="Wedid" name="Wedid[]" value="4">Wed
									</td>
									<td>
								<select id="from" name="fromWed[]">
								<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList4['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList4['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList4['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList4['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList4['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList4['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList4['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList4['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList4['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList4['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList4['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList4['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList4['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList4['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList4['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList4['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList4['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList4['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList4['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList4['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList4['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList4['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList4['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList4['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList4['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList4['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList4['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList4['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList4['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList4['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList4['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList4['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList4['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList4['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList4['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList4['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList4['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList4['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList4['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList4['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList4['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList4['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList4['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList4['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList4['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList4['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList4['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList4['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							
							</td>
							<td>
							
							<select id="to" name="toWed[]">
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList4['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList4['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList4['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList4['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList4['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList4['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList4['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList4['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList4['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList4['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList4['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList4['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList4['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList4['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList4['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList4['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList4['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList4['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList4['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList4['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList4['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList4['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList4['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList4['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList4['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList4['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList4['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList4['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList4['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList4['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList4['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList4['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList4['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList4['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList4['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList4['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList4['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList4['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList4['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList4['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList4['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList4['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList4['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList4['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList4['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList4['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList4['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList4['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>

							</td>
							<td><input type="button" id="email" onclick="addrow(4);"  value="Add Row" /></td>
							</tr>
									
								<?php 
								} 
								
								if(empty($GetTimeSlot4))
								{
							?>
							
							<tr>
								<td style="padding-right: 31px;">Wed<input type="hidden" id="Wedid" name="Wedid[]" value="4" ></td>
								<td>
									<select id="from" name="fromWed[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>

								</td>
								<td>
									<select id="to" name="toWed[]">
										<option value="">Select</option>
											<?php
												$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
												$i       = 30;
												foreach ($Time as $TimeList)
												{

											?> 
												<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
											<?php 
												}
											?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(4);"  value="Add Row" /></td>
							</tr>
							<?php } ?>
							</tbody>
							</table>
									
							
									
									
									
							<table border="" width="100%" id="tableThu">
							<thead>
								
							</thead>
							<tbody>
							

							<?php

								$GetTimeSlot5 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=5", "", "", "", "");
									foreach($GetTimeSlot5 as $daysList5) 
									{
								?>
							
							<tr>
								<td style="padding-right: 35px;"> <input type="hidden" id="Thuid" name="Thuid[]" value="5">  Thu</td>
								<td>
								
								<select id="from" name="fromThu[]" >
								<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList5['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList5['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList5['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList5['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList5['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList5['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList5['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList5['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList5['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList5['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList5['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList5['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList5['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList5['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList5['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList5['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList5['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList5['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList5['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList5['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList5['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList5['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList5['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList5['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList5['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList5['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList5['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList5['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList5['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList5['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList5['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList5['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList5['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList5['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList5['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList5['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList5['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList5['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList5['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList5['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList5['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList5['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList5['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList5['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList5['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList5['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList5['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList5['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							</td>
							</td>
							<td>
							<select id="to" name="toThu[]" >
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList5['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList5['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList5['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList5['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList5['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList5['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList5['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList5['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList5['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList5['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList5['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList5['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList5['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList5['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList5['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList5['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList5['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList5['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList5['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList5['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList5['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList5['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList5['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList5['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList5['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList5['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList5['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList5['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList5['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList5['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList5['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList5['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList5['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList5['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList5['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList5['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList5['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList5['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList5['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList5['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList5['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList5['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList5['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList5['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList5['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList5['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList5['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList5['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>

							</td>
							<td><input type="button" id="email" onclick="addrow(5);"  value="Add Row" /></td>
							</tr>
							
							
							<?php 
							} 
								
							if(empty($GetTimeSlot5)){
							?>
							
							<tr>
								<td style="padding-right: 35px;">Thu<input type="hidden" id="Thuid" name="Thuid[]" value="5" ></td>
								<td>
									<select id="from" name="fromThu[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>

								</td>
								<td>
									<select id="to" name="toThu[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(5);"  value="Add Row" /></td>
							</tr>	
							<?php } ?>
								
							
							</tbody>
							</table>
									
							<table border="" width="100%" id="tableFri">
							<thead>
								
							</thead>
							<tbody>
							<?php

							$GetTimeSlot6 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=6", "", "", "", "");
								foreach($GetTimeSlot6 as $daysList6) 
								{
							?>
							<tr>
							<td style="padding-right: 43px;"> <input type="hidden" id="Friid" name="Friid[]" value="6"> Fri</td>
							<td>
							
							<select id="from" name="fromFri[]" >
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList6['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList6['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList6['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList6['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList6['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList6['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList6['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList6['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList6['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList6['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList6['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList6['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList6['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList6['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList6['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList6['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList6['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList6['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList6['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList6['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList6['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList6['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList6['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList6['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList6['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList6['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList6['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList6['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList6['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList6['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList6['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList6['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList6['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList6['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList6['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList6['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList6['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList6['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList6['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList6['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList6['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList6['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList6['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList6['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList6['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList6['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList6['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList6['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							</td>
							<td>
							
							<select id="to" name="toFri[]" >
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList6['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList6['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList6['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList6['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList6['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList6['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList6['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList6['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList6['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList6['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList6['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList6['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList6['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList6['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList6['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList6['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList6['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList6['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList6['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList6['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList6['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList6['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList6['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList6['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList6['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList6['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList6['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList6['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList6['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList6['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList6['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList6['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList6['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList6['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList6['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList6['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList6['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList6['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList6['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList6['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList6['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList6['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList6['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList6['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList6['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList6['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList6['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList6['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							
							</td>
							<td><input type="button" id="email" onclick="addrow(6);"  value="Add Row" /></td>
							</tr>
								
							
								
							<?php 
							} 
								
							if(empty($GetTimeSlot6)){
							?>
							
							<tr>
								<td style="padding-right: 43px;">Fri<input type="hidden" id="Friid" name="Friid[]" value="6" ></td>
								<td>
									<select id="from" name="fromFri[]">
										<option value="">Select</option>
											<?php
												$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
												$i       = 30;
												foreach ($Time as $TimeList)
												{

											?> 
												<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
											<?php 
												}
											?>
									</select>

								</td>
								<td>
									<select id="to" name="toFri[]">
										<option value="">Select</option>
										<?php
											$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
											$i       = 30;
											foreach ($Time as $TimeList)
											{

										?> 
											<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
										<?php 
											}
										?>
									</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(6);"  value="Add Row" /></td>
							</tr>	
							<?php } ?>	
								
								
								
							</tbody>
							</table>
									
							<table border="" width="100%" id="tableSat">
							<thead>
								
							</thead>
							<tbody>
								<?php

							$GetTimeSlot7 = mysqlSelect("*", "doctor_appointment_timezone_slots", "doc_id='".$_GET['doc_id']."' and day_id=6", "", "", "", "");
								foreach($GetTimeSlot7 as $daysList7) 
								{
							?>
							<tr>
							<td style="padding-right: 39px;"><input type="hidden" id="Satid" name="Satid[]" value="7">Sat</td>
							<td>
							
							<select id="from" name="fromSat[]" >
							<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList7['time_from']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList7['time_from']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList7['time_from']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList7['time_from']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList7['time_from']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList7['time_from']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList7['time_from']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList7['time_from']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList7['time_from']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList7['time_from']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList7['time_from']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList7['time_from']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList7['time_from']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList7['time_from']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList7['time_from']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList7['time_from']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList7['time_from']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList7['time_from']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList7['time_from']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList7['time_from']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList7['time_from']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList7['time_from']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList7['time_from']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList7['time_from']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList7['time_from']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList7['time_from']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList7['time_from']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList7['time_from']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList7['time_from']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList7['time_from']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList7['time_from']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList7['time_from']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList7['time_from']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList7['time_from']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList7['time_from']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList7['time_from']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList7['time_from']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList7['time_from']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList7['time_from']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList7['time_from']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList7['time_from']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList7['time_from']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList7['time_from']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList7['time_from']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList7['time_from']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList7['time_from']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList7['time_from']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList7['time_from']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							
							</td>
							<td>
							
							<select id="to" name="toSat[]" >
								<option value="" >Empty </option>
								<option value="00:00" <?php if($daysList7['time_to']== "00:00"){ ?>selected<?php } ?>>12.00 AM</option>
								<option value="00:30" <?php if($daysList7['time_to']== "00:30"){ ?>selected<?php } ?>>12.30 AM</option>
								<option value="01:00" <?php if($daysList7['time_to']== "01:00"){ ?>selected<?php } ?>>01.00 AM</option>
								<option value="01:30" <?php if($daysList7['time_to']== "01:30"){ ?>selected<?php } ?>>01.30 AM</option>
								<option value="02:00" <?php if($daysList7['time_to']== "02:00"){ ?>selected<?php } ?>>02.00 AM</option>
								<option value="02:30" <?php if($daysList7['time_to']== "02:30"){ ?>selected<?php } ?>>02.30 AM</option>
								<option value="03:00" <?php if($daysList7['time_to']== "03:00"){ ?>selected<?php } ?>>03.00 AM</option>
								<option value="03:30" <?php if($daysList7['time_to']== "03:30"){ ?>selected<?php } ?>>03.30 AM</option>
								<option value="04:00" <?php if($daysList7['time_to']== "04:00"){ ?>selected<?php } ?>>04.00 AM</option>
								<option value="04:30" <?php if($daysList7['time_to']== "04:30"){ ?>selected<?php } ?>>04.30 AM</option>
								<option value="05:00" <?php if($daysList7['time_to']== "05:00"){ ?>selected<?php } ?>>05.00 AM</option>
								<option value="05:30" <?php if($daysList7['time_to']== "05:30"){ ?>selected<?php } ?>>05.30 AM</option>
								<option value="06:00" <?php if($daysList7['time_to']== "06:00"){ ?>selected<?php } ?>>06.00 AM</option>
								<option value="06:30" <?php if($daysList7['time_to']== "06:30"){ ?>selected<?php } ?>>06.30 AM</option>
								<option value="07:00" <?php if($daysList7['time_to']== "07:00"){ ?>selected<?php } ?>>07.00 AM</option>
								<option value="07:30" <?php if($daysList7['time_to']== "07:30"){ ?>selected<?php } ?>>07.30 AM</option>
								<option value="08:00" <?php if($daysList7['time_to']== "08:00"){ ?>selected<?php } ?>>08.00 AM</option>
								<option value="08:30" <?php if($daysList7['time_to']== "08:30"){ ?>selected<?php } ?>>08.30 AM</option>
								<option value="09:00" <?php if($daysList7['time_to']== "09:00"){ ?>selected<?php } ?>>09.00 AM</option>
								<option value="09:30" <?php if($daysList7['time_to']== "09:30"){ ?>selected<?php } ?>>09.30 AM</option>
								<option value="10:00" <?php if($daysList7['time_to']== "10:00"){ ?>selected<?php } ?>>10.00 AM</option>
								<option value="10:30" <?php if($daysList7['time_to']== "10:30"){ ?>selected<?php } ?>>10.30 AM</option>
								<option value="11:00" <?php if($daysList7['time_to']== "11:00"){ ?>selected<?php } ?>>11.00 AM</option>
								<option value="11:30" <?php if($daysList7['time_to']== "11:30"){ ?>selected<?php } ?>>11.30 AM</option>
								<option value="12:00" <?php if($daysList7['time_to']== "12:00"){ ?>selected<?php } ?>>12.00 PM</option>
								<option value="12:30" <?php if($daysList7['time_to']== "12:30"){ ?>selected<?php } ?>>12.30 PM</option>
								<option value="13:00" <?php if($daysList7['time_to']== "13:00"){ ?>selected<?php } ?>>01.00 PM</option>
								<option value="13:30" <?php if($daysList7['time_to']== "13:30"){ ?>selected<?php } ?>>01.30 PM</option>
								<option value="14:00" <?php if($daysList7['time_to']== "14:00"){ ?>selected<?php } ?>>02.00 PM</option>
								<option value="14:30" <?php if($daysList7['time_to']== "14:30"){ ?>selected<?php } ?>>02.30 PM</option>
								<option value="15:00" <?php if($daysList7['time_to']== "15:00"){ ?>selected<?php } ?>>03.00 PM</option>
								<option value="15:30" <?php if($daysList7['time_to']== "15:30"){ ?>selected<?php } ?>>03.30 PM</option>
								<option value="16:00" <?php if($daysList7['time_to']== "16:00"){ ?>selected<?php } ?>>04.00 PM</option>
								<option value="16:30" <?php if($daysList7['time_to']== "16:30"){ ?>selected<?php } ?>>04.30 PM</option>
								<option value="17:00" <?php if($daysList7['time_to']== "17:00"){ ?>selected<?php } ?>>05.00 PM</option>
								<option value="17:30" <?php if($daysList7['time_to']== "17:30"){ ?>selected<?php } ?>>05.30 PM</option>
								<option value="18:00" <?php if($daysList7['time_to']== "18:00"){ ?>selected<?php } ?>>06.00 PM</option>
								<option value="18:30" <?php if($daysList7['time_to']== "18:30"){ ?>selected<?php } ?>>06.30 PM</option>
								<option value="19:00" <?php if($daysList7['time_to']== "19:00"){ ?>selected<?php } ?>>07.00 PM</option>
								<option value="19:30" <?php if($daysList7['time_to']== "19:30"){ ?>selected<?php } ?>>07.30 PM</option>
								<option value="20:00" <?php if($daysList7['time_to']== "20:00"){ ?>selected<?php } ?>>08.00 PM</option>
								<option value="20:30" <?php if($daysList7['time_to']== "20:30"){ ?>selected<?php } ?>>08.30 PM</option>
								<option value="21:00" <?php if($daysList7['time_to']== "21:00"){ ?>selected<?php } ?>>09.00 PM</option>
								<option value="21:30" <?php if($daysList7['time_to']== "21:30"){ ?>selected<?php } ?>>09.30 PM</option>
								<option value="22:00" <?php if($daysList7['time_to']== "22:00"){ ?>selected<?php } ?>>10.00 PM</option>
								<option value="22:30" <?php if($daysList7['time_to']== "22:30"){ ?>selected<?php } ?>>10.30 PM</option>
								<option value="23:00" <?php if($daysList7['time_to']== "23:00"){ ?>selected<?php } ?>>11.00 PM</option>
								<option value="23:30" <?php if($daysList7['time_to']== "23:30"){ ?>selected<?php } ?>>11.30 PM</option>
							</select>
							
							</td>
							<td><input type="button" id="email" onclick="addrow(7);"  value="Add Row" /></td>
							</tr>
								
								<?php } 
								
								
								
								if(empty($GetTimeSlot7)){
							?>
							
							<tr>
								<td style="padding-right: 39px;">Sat<input type="hidden" id="Satid" name="Satid[]" value="7" ></td>
								<td>
								<select id="from" name="fromSat[]">
									<option value="">Select</option>
									<?php
										$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
										$i       = 30;
										foreach ($Time as $TimeList)
										{

									?> 
										<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
									<?php 
										}
									?>
								</select>

								</td>
								<td>
								<select id="to" name="toSat[]">
									<option value="">Select</option>
									<?php
										$Time = mysqlSelect("*", "doctor_appointment_timings", "", "", "", "", "");
										$i       = 30;
										foreach ($Time as $TimeList)
										{

									?> 
										<option value="<?php echo $TimeList['time_val'] ?>"><?php echo $TimeList['time_name'] ?> </option>
									<?php 
										}
									?>
								</select>
								</td>
								<td><input type="button" id="email" onclick="addrow(7);"  value="Add Row" /></td>
							</tr>	
							<?php } ?>
							</tbody>
							</table>
									
									
									<br>
								
						</div>
                    </div>
							   
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="edit_doctor" id="edit_doctor" class="btn btn-primary block full-width m-b ">UPDATE</button>
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
	<script>

function addrow(val)
{
	
	var from = $("#from").val();
	var to = $("#to").val();
	
	if(val==1)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Sunid' name='Sunid[]' value='1' ><select id='from' name='fromSun[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toSun[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		
		$("#tablesun").append(markup);
		$("body").on("click",".remove-btn",function(e){
		$(this).parents('.work_his_data').remove();
		//the above method will remove the user_data div
		});
	}
	else if(val==2)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Monid' name='Monid[]' value='2' ><select id='from' name='fromMon[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toMon[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tablemon").append(markup);
	}
	else if(val==3)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Tueid' name='Tueid[]' value='3' ><select id='from' name='fromTue[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toTue[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableTue").append(markup);
	}
	else if(val==4)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Wedid' name='Wedid[]' value='4' ><select id='from' name='fromWed[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toWed[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		
		$("#tableWed").append(markup);
	}
	
	else if(val==5)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Thuid' name='Thuid[]' value='5' ><select id='from' name='fromThu[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toThu[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableThu").append(markup);
	}
	else if(val==6)
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Friid' name='Friid[]' value='6' ><select id='from' name='fromFri[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toFri[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableFri").append(markup);
	}
	
	else
	{
		var markup = "<tr><td></td><td><input type='hidden' id='Satid' name='Satid[]' value='7' ><select id='from' name='fromSat[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td><td><select id='to' name='toSat[]'><option value='00:00'>12.00 AM</option><option value='00:30'>12.30 AM</option><option value='01:00'>01.00 AM</option><option value='01:30'>01.30 AM</option><option value='02:00'>02.00 AM</option><option value='02:30'>02.30 AM</option><option value='03:00'>03.00 AM</option><option value='03:30'>03.30 AM</option><option value='04:00'>04.00 AM</option><option value='04:30'>04.30 AM</option><option value='05:00'>05.00 AM</option><option value='05:30'>05.30 AM</option><option value='06:00'>06.00 AM</option><option value='06:30'>06.30 AM</option><option value='07:00'>07.00 AM</option><option value='07:30'>07.30 AM</option><option value='08:00'>08.00 AM</option><option value='08:30'>08.30 AM</option><option value='09:00'>09.00 AM</option><option value='09:30'>09.30 AM</option><option value='10:00'>10.00 AM</option><option value='10:30'>10.30 AM</option><option value='11:00'>11.00 AM</option><option value='11:30'>11.30 AM</option><option value='12:00'>12.00 PM</option><option value='12:30'>12.30 PM</option><option value='13:00'>01.00 PM</option><option value='13:30'>01.30 PM</option><option value='14:00'>02.00 PM</option><option value='14:30'>02.30 PM</option><option value='15:00'>03.00 PM</option><option value='15:30'>03.30 PM</option><option value='16:00'>04.00 PM</option><option value='16:30'>04.30 PM</option><option value='17:00'>05.00 PM</option><option value='17:30'>05.30 PM</option><option value='18:00'>06.00 PM</option><option value='18:30'>06.30 PM</option><option value='19:00'>07.00 PM</option><option value='19:30'>07.30 PM</option><option value='20:00'>08.00 PM</option><option value='20:30'>08.30 PM</option><option value='21:00'>09.00 PM</option><option value='21:30'>09.30 PM</option><option value='22:00'>10.00 PM</option><option value='22:30'>10.30 PM</option><option value='23:00'>11.00 PM</option><option value='23:30'>11.30 PM</option></select></td></tr>";
		$("#tableSat").append(markup);
	}
	
}
   
</script>
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