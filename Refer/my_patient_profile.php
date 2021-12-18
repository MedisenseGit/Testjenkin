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

	$patientprofile = $objQuery->mysqlSelect("*","my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	if($patientprofile[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($patientprofile[0]['patient_gen']=="2"){
		$gender="Female";
	}

	if($patientprofile[0]['hyper_cond']=="0"){
		$hyperStatus="No";
	}
	else if($patientprofile[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($patientprofile[0]['diabetes_cond']=="0"){
		$diabetesStatus="No";
	}
	else if($patientprofile[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}

	$patient_tab = $objQuery->mysqlSelect("*","my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	$patient_referal = $objQuery->mysqlSelect("*","patient_referal","md5(patient_id)='".$_GET['p']."'"," patient_referal_id DESC ","",""," 0,1 ");

	$patient_id = $patient_tab[0]['patient_id'];


	$patient_tagName = $objQuery->mysqlSelect("prescription_trade_name","patient_episode_prescriptions",""," episode_id DESC ","prescription_trade_name","","");
	while(list($key, $val) = each($patient_tagName))
	{
		$arrTradeName[] = $val['prescription_trade_name'] ;
	}

	$patient_genericName = $objQuery->mysqlSelect("prescription_generic_name","patient_episode_prescriptions",""," episode_id DESC ","prescription_generic_name","","");
	while(list($key, $val) = each($patient_genericName))
	{
		$arrGenericName[] = $val['prescription_generic_name'] ;
	}
	
	$patient_dosageName = $objQuery->mysqlSelect("prescription_dosage_name","patient_episode_prescriptions",""," episode_id DESC ","prescription_dosage_name","","");
	while(list($key, $val) = each($patient_dosageName))
	{
		$arrDosageName[] = $val['prescription_dosage_name'] ;
	}
	
	$patient_route = $objQuery->mysqlSelect("prescription_route","patient_episode_prescriptions",""," episode_id DESC ","prescription_route","","");
	while(list($key, $val) = each($patient_route))
	{
		$arrRoute[] = $val['prescription_route'] ;
	}

	$patient_frequency = $objQuery->mysqlSelect("prescription_frequency","patient_episode_prescriptions",""," episode_id DESC ","prescription_frequency","","");
	while(list($key, $val) = each($patient_frequency))
	{
		$arrFrequency[] = $val['prescription_frequency'] ;
	}


	$patient_list = $objQuery->mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","my_patient","partner_id='".$admin_id."'","patient_id desc","","","");
	
	$cnt = 0;
	while(list($key, $val) = each($patient_list))
	{
		$arrPatientList[$cnt]['label'] = $val['patient_name'] .'  '.$val['patient_email'] .'  '.$val['patient_mob'] ;
		$arrPatientList[$cnt]['value'] = 'My-Patient-Profile?p='. md5($val['patient_id']) ;
		$cnt++;
	}

	//echo '<pre>'; print_r($arrPatientList);
	$arrPatientList = json_encode($arrPatientList);
	//print_r($arrPatientList);		echo '</pre>';




?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- Meta, title, CSS, favicons, etc. -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<title>Patient Profile</title>
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
	</head>

	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				<?php include_once('side_menu.php'); ?>

				<!-- page content -->
				<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left"><h3>Patient Profile</h3></div><div class="col-md-6">
							<input type="text" style="width:100%;height:40px" placeholder="Search Patient" class="patientList">
						</div>
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
								<div class="x_title">
									<h2>Patient Report <!--<small>Activity report</small>--></h2>
									<!--<a class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Send Payment Link</a>
									<a class="btn btn-primary pull-right"><i class="fa fa-calendar"></i> Send Appointment Link</a>
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
										<ul class="dropdown-menu" role="menu">
											<li><a href="#">Settings 1</a>
											</li>
											<li><a href="#">Settings 2</a>
											</li>
										</ul>
										</li>
										<li><a class="close-link"><i class="fa fa-close"></i></a>
										</li>
									</ul>-->
									<div class="clearfix"></div>
								</div>
								<div class="x_content">
									<div class="col-md-3 col-sm-3 col-xs-12 profile_left">
										<div class="profile_img">
											<div id="crop-avatar">
												<!-- Current avatar -->
												<img class="img-responsive avatar-view" src="images/user.png" alt="Avatar" title="Change the avatar">
											</div>
										</div>
										<h3><?php echo $patientprofile[0]['patient_name']; ?></h3>

										<ul class="list-unstyled user_data">
											<li><b>Age:</b>  <?php echo $patientprofile[0]['patient_age']; ?>
											</li>
											<li><b>Gender:</b>  <?php echo $gender; ?>
											</li>

											<li><b>Marital Status:</b>  <?php echo $patientprofile[0]['merital_status']; ?>
											</li>
											<li><b>Qualification:</b>  <?php echo $patientprofile[0]['qualification']; ?>
											</li>
											<li><b>Weight:</b>  <?php echo $patientprofile[0]['weight']; ?>
											</li>

											<li><b>Hyper Condition:</b>  <?php echo $hyperStatus; ?>
											</li>
											<li><b>Diabetes Condition:</b>  <?php echo $diabetesStatus; ?>
											</li>
											<li><b>Blood Group:</b>  <?php echo $patientprofile[0]['pat_blood']; ?>
											</li>
											<li><b>Contact Person:</b>  <?php echo $patientprofile[0]['contact_person']; ?>
											</li>

											<li class="m-top-xs">
											<i class="fa fa-envelope user-profile-icon"></i>
											<?php echo $patientprofile[0]['patient_email']; ?>
											</li>
											<li class="m-top-xs">
											<i class="fa fa-mobile user-profile-icon"></i>
											<?php echo $patientprofile[0]['patient_mob']; ?>
											</li>
											<li><b>Address: </b>  <?php echo $patientprofile[0]['patient_addrs'].", ".$patientprofile[0]['patient_loc'].", ".$patientprofile[0]['pat_state']; ?>
											</li>

										</ul>
										<br />
									</div>
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
									
									<div class="col-md-6 col-sm-6 col-xs-12 text-right" style="margin-top:10px; float:right;">
														<button type="submit" name="save_patient_edit" id="save_patient_edit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> SAVE</button>
														<button type="submit" name="save_patient" id="save_patient" class="btn btn-primary"><i class="fa fa-floppy-o"></i> SAVE & EXIT</button>
														
													</div>
									<div class="col-md-9 col-sm-9 col-xs-12">
										<!-- start of user-activity-graph -->
										<!--<div id="graph_bar" style="width:100%; height:280px;"></div>-->
										<!-- end of user-activity-graph -->
										<h2><?php echo ( isset($_GET['p']) ? 'Edit' : 'Add') ?> Patient</h2>
										<hr>
										<div class="row <?php echo (!isset($_GET['p']) ? 'active in' : ''); ?>" id="manage_profile_tab" aria-labelledby="add-edit-profile-tab">
											
											<div class="form-group">
												<div class="col-md-4 col-sm-4 col-xs-12">
													Patient Name <span style="color:red; font-size=42px;">*</span>
													<input type="text" id="se_pat_name" name="se_pat_name" required="required" class="form-control" placeholder="Patient Name *" value="<?php echo $patient_tab[0]['patient_name'] ?>">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Contact Number <span style="color:red; font-size=42px;">*</span>
													<input type="text" id="se_phone_no" name="se_phone_no" required="required" class="form-control" placeholder="10 digit Mobile No.*" maxlength="10" value="<?php echo $patient_tab[0]['patient_mob'] ?>">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Email
													<input type="email" id="se_email" name="se_email" class="form-control" placeholder="Email" value="<?php echo $patient_tab[0]['patient_email'] ?>">
												</div>
											</div>
											<br>
											<div class="form-group">
													<div class="col-md-4 col-sm-4 col-xs-12">
														Gender
														<div class="radio">
															<label><input type="radio" name="se_gender" id="male" <?php echo (!isset($patientprofile[0]['patient_gen']) ? 'checked' : ( $patientprofile[0]['patient_gen'] == "1" ? 'checked' : '' ))  ?> value="1" >Male</label>
															<label><input type="radio" name="se_gender" id="female" value="2" <?php echo ( $patientprofile[0]['patient_gen'] == "2" ? 'checked' : '' ) ?> >Female</label>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12">
														Hypertension
														<div class="radio">
															<label><input type="radio" name="se_hyper" id="option2" value="1" <?php echo ( $patientprofile[0]['hyper_cond'] == "1" ? 'checked' : '' ) ?>>Yes</label>
															<label><input type="radio" name="se_hyper" id="option4" value="0" <?php echo (!isset($patientprofile[0]['hyper_cond']) ? 'checked' : ( $patientprofile[0]['hyper_cond'] == "0" ? 'checked' : '' )) ?>> No</label>
														</div>
													</div>
													<div class="col-md-4 col-sm-4 col-xs-12">
														Diabetes
														<div class="radio">
															<label><input type="radio" name="se_diabets" value="1" id="option3" <?php echo ( $patientprofile[0]['diabetes_cond'] == "1" ? 'checked' : '' ) ?>>Yes</label>
															<label><input type="radio" name="se_diabets" id="option4" value="0" <?php echo (!isset($patientprofile[0]['diabetes_cond']) ? 'checked' : ( $patientprofile[0]['diabetes_cond'] == "0" ? 'checked' : '' )) ?> > No</label>
														</div>
													</div>
											</div>
											<br>
											<div class="form-group">
												
												
												<div class="col-md-4 col-sm-4 col-xs-12">
													Age 
													<input type="text" id="se_pat_age" name="se_pat_age" class="form-control" placeholder="Age *" value="<?php echo $patient_tab[0]['patient_age'] ?>">
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Weight
													<input type="text" id="se_weight" name="se_weight" class="form-control" placeholder="Weight" value="<?php echo $patient_tab[0]['weight'] ?>">
												</div>
											</div>
											<br>
											<div class="form-group">
												<div class="col-md-4 col-sm-4 col-xs-12">
													Country <span class="required">*</span>
													<select class="form-control" name="se_country" name="se_country">
														<option value="India" <?php echo (!isset($patient_tab[0]['pat_country']) ? 'selected' : ($patient_tab[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($patient_tab[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
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
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($patient_tab[0]['pat_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

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
												<div class="col-md-8 col-sm-8 col-xs-12">
													Address <span class="required">*</span>
													<textarea class="form-control" id="se_address" name="se_address" rows="3"><?php echo $patient_tab[0]['patient_addrs'] ?></textarea>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-12">
													Add Attachments
													<em>Multiple Select</em>
													<input type="file" id="file-3" name="file-3[]"  multiple="true">
												</div>
											</div>
											<br>
											<div class="form-group">
													<div class="col-md-8 col-sm-8 col-xs-12">
														Family History
														<textarea class="form-control" id="se_query" name="se_query" rows="3"><?php echo  $patient_tab[0]['pat_query'] ?></textarea>
													</div>
												</div>
												<br>
											
										
										</div>
										<hr><br>

										<div class="" role="tabpanel" data-example-id="togglable-tabs">
											<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
												<?php
													if (isset($_GET['p']))
													{
														echo '<li role="presentation" class="active"><a href="#all_episodes" role="tab" id="profile-tab" data-toggle="tab" aria-expanded="false">ALL EPISODES</a></li>';
													}
												?>
												<!-- <li role="presentation" class="<?php echo (!isset($_GET['p']) ? 'active' : '') ?>"><a href="#manage_profile_tab" role="tab" id="add-edit-profile-tab" data-toggle="tab" aria-expanded="true">ADD/EDIT PATIENT</a>
												</li> -->
												<li role="presentation" class=""><a href="#add_episode" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true">ADD EPISODES</a>
												</li>
											</ul>

												<div id="myTabContent" class="tab-content">
													<?php
														if (isset($_GET['p']))
														{
													?>
													<!-- All Episodes section -->
													<div role="tabpanel" class="tab-pane fade active in" id="all_episodes" aria-labelledby="profile-tab">
														<?php
															$patient_episodes = $objQuery->mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");

															if (count($patient_episodes) > 0)
															{
																echo '<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">';
																	$patient_episode_count = 0;
																	while (list($patient_episode_key, $patient_episode_val) = each($patient_episodes))
																	{
																		$patient_episode_count++;
																		?>
																	<div class="panel">
																		<a class="panel-heading" role="tab" id="heading<?php echo $patient_episode_count ?>" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $patient_episode_count ?>" aria-expanded="<?php echo ($patient_episode_count == 1 ? 'true' : 'false') ?>" aria-controls="collapse<?php echo $patient_episode_count ?>">
																			<h4 class="panel-title">#<?php echo '<b>'.$patient_episode_count.'</b>&nbsp;&nbsp;&nbsp;( '. $patient_episode_val['formated_date_time'].' ) ' ?> </h4>
																		</a>
																		<div id="collapse<?php echo $patient_episode_count ?>" class="panel-collapse collapse <?php echo ($patient_episode_count == 1 ? 'in' : '') ?>" role="tabpanel" aria-labelledby="heading<?php echo $patient_episode_count ?>">
																		<div class="panel-body">
																			<b>Description:</b><br />
																			<p><?php echo $patient_episode_val['episode_desc'] ?></p>
																			<br />
																			<b>Medical Complaint:</b><br />
																			<p><?php echo $patient_episode_val['episode_medical_complaint'] ?></p>
																			<br />
																			
																			<?php
																				$patient_episode_prescriptions = $objQuery->mysqlSelect("*","patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");

																				if (count($patient_episode_prescriptions) > 0)
																				{
																					?>
																				<h3 class="pull-right"><button name="printBtn" onclick="printContent('printPrescript<?php echo $patient_episode_val['episode_id']; ?>');" ><i class="fa fa-print"></i> </button></h3>	
																				<table class="table table-bordered">
																					<thead>
																						<tr>
																							<th>Name of the Drug</th>
																							<th>Generic Name</th>
																							<th>Dosage</th>
																							<th>Route</th>
																							<th>Frequency</th>
																							<th>Instruction</th>
																						</tr>
																					</thead>
																					<tbody>
																					<?php
																					while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($patient_episode_prescriptions))
																					{
																						?>
																						<tr>
																							<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
																							<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
																							<td><?php echo $patient_episode_prescription_val['prescription_dosage_name'] ?></td>
																							<td><?php echo $patient_episode_prescription_val['prescription_route'] ?></td>
																							<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
																							<td><?php echo $patient_episode_prescription_val['prescription_instruction'] ?></td>
																						</tr>
																						<?php
																					}
																				?>
																					</tbody>
																				</table>
																				
																			<p><b>Special Instructions:</b>  <?php echo $patient_episode_val['episode_special_instruction'] ?></p>
																			<br />
																				<div id="printPrescript<?php echo $patient_episode_val['episode_id']; ?>" style="display:none;">
																				<?php $getDoc = $objQuery->mysqlSelect("*","our_partners","partner_id='".$admin_id."'","","","",""); 
																				if($patientprofile[0]['patient_gen']==1){
																					$gender="Male";
																				} else {
																					$gender="Female";
																				}
																				?>
	
																				<h2 style="text-align:center;" ><?php echo $getDoc[0]['partner_name']; ?></h2><br><br>
																				<span style="font-size:12px; color:#000; float:right;">Date: <?php echo date('d-M-Y, h:i a');?></span>
																				<p>Patient ID: <?php echo $patient_tab[0]['patient_id']; ?><br>
																				Patient Name: <?php echo $patient_tab[0]['patient_name']; ?><br>
																				Age: <?php echo $patient_tab[0]['patient_age']; ?><br>
																				Gender: <?php echo $gender; ?><br>
																				Doctor Name: <?php echo $getDoc[0]['contact_person']; ?><br>
																				Hospital ID: <br>
																				</p>
																				<table  class="table table-bordered">
																					<thead>
																						<tr>
																							<th>Name of the Drug</th>
																							<th>Generic Name</th>
																							<th>Dosage</th>
																							<th>Route</th>
																							<th>Frequency</th>
																							<th>Instruction</th>
																						</tr>
																					</thead>
																					<tbody>
																				<?php foreach($patient_episode_prescriptions as $listPresc) { ?>
																				<tr>
																							<td><?php echo $listPresc['prescription_trade_name'] ?></td>
																							<td><?php echo $listPresc['prescription_generic_name'] ?></td>
																							<td><?php echo $listPresc['prescription_dosage_name'] ?></td>
																							<td><?php echo $listPresc['prescription_route'] ?></td>
																							<td><?php echo $listPresc['prescription_frequency'] ?></td>
																							<td><?php echo $listPresc['prescription_instruction'] ?></td>
																						</tr>
																				<?php } ?>
																				</tbody>
																				</table>
																				 <br />
																			<p style="float:left; font-weight:bold; font-size:14px;">Instructions: <?php echo $patient_episode_val['episode_special_instruction'] ?></p>
																			
																				<br><br><br>
																				<p style="float:right; font-weight:bold; font-size:14px;">Doctor's Signature</p>
																				</div>
																				
																				<?php
																				}
																			?>
																			</div>
																		</div>
																	</div>
																		<?php
																	}
																echo '</div>';
															}
														?>
													</div>
													<!-- End episodes -->
													<?php
														}
													?>
														<!-- Add/Edit Profile section -->

														<!-- Add/Edit Profile section -->

														<!-- add episodes section -->
														<div role="tabpanel" class="tab-pane fade <?php echo (!isset($_GET['p']) ? 'active in' : '') ?>" id="add_episode" aria-labelledby="home-tab">
															<div class="x_title">
																<h2>ADD Episodes</h2>
																<div class="clearfix"></div>
															</div>
															<br />
																<div class="form-group">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		Chief Medical Complaint
																		<textarea class="form-control" id="episode_medical_complaint" name="episode_medical_complaint" rows="2"></textarea>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		Detailed Description
																		<textarea class="form-control" id="episode_desc" name="episode_desc" rows="2"></textarea>
																	</div>
																</div>
																
																<br>
																<!-- <div class="form-group">
																	<label class="control-label col-md-2 col-sm-2 col-xs-12">Patient query </label>
																	<div class="col-md-10 col-sm-10 col-xs-12">
																		<textarea class="form-control" id="se_query" name="se_query" rows="2"></textarea>
																	</div>
																</div> -->
																<br>
																<div class="x_title">
																	<h2>Add Prescriptions</h2>																	
																	<ul class="nav nav-pills navbar-right" role="tablist">
																		<li role="presentation" class="dropdown">
																			<a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Load Template<span class="caret"></span></a>
																			<?php
																				$prescription_templates = $objQuery->mysqlSelect("*","patient_episode_prescription_templates","admin_id='".$admin_id."'"," template_name ASC","","","");

																				if (count($prescription_templates) > 0)
																				{
																					echo '<ul id="menu6" class="dropdown-menu animated fadeInDown" role="menu">';

																					while (list($prescription_template_key, $prescription_template_val) = each($prescription_templates))
																					{
																						//echo '<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript: loadPrescriptionTemplate(\''. $prescription_template_val['template_id'] .'\')"><input type="checkbox" id="prescriptionTemplate_'.$prescription_template_val['template_id'].'" name="prescriptionTemplate['. $prescription_template_val['template_id'] .']" class="prescriptionTemplate" value="'. $prescription_template_val['template_id'] .'"> '. $prescription_template_val['template_name'].'</a></li>';
																						echo '<li role="presentation"><a role="menuitem" tabindex="-1" style="cursor:default"><input  type="checkbox" id="prescriptionTemplate_'.$prescription_template_val['template_id'].'" name="prescriptionTemplate['. $prescription_template_val['template_id'] .']" class="prescriptionTemplate" value="'. $prescription_template_val['template_id'] .'"> '. $prescription_template_val['template_name'].'</a></li>';
																					}
																					echo '</ul>';
																				}
																			?>
																		</li>
																	</ul>
																	<div class="clearfix"></div>
																</div>
																<div class="panel-body">
																	<div class="container">
																		<?php
																			$prescription_seq = 0;
																		?>
																		<input type="hidden" name="hid_prescription_seq" id="hid_prescription_seq" value="<?php echo $prescription_seq ?>" />
																		<table id="employee-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Trade Name</th>
																				<th>Generic Name</th>
																				<th>Dosage</th>
																				<th>Route</th>
																				<th>Frequency</th>
																				<th>Instruction</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			</tbody>
																			<!-- <form method="post" action="send.php"> -->
																		</table>
																		<div class="text-right"><a href="javascript: void(0)" class="addTr btn btn-primary">Add More</a></div>
																	</div>
																</div>
																<div class="form-group">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<input type="checkbox" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save Template
																		<input type="text" name="template_name" id="template_name" placeholder="Template Name" style="display: none;" />
																	</div>
																</div>
																
																<div class="form-group">
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		Special Instruction
																		<textarea class="form-control" id="episode_special_instruction" name="episode_special_instruction" rows="2"></textarea>
																	</div>
																</div>
																</br>
																<div class="form-group">
																
																	<div class="col-md-12 col-sm-12 col-xs-12">
																		<div class="col-md-4 col-sm-4 col-xs-4">Next Follow Up Date & Time</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																		<input type="text"  name="follow_date" id="J-demo-02" class="form-control" placeholder="Select Date">
																		<script type="text/javascript">
																			$('#J-demo-02').dateTimePicker({
																				mode: 'date'
																			});
																		</script>
																		</div>
																		<div class="col-md-4 col-sm-4 col-xs-4">
																		<select class="left form-control autotab" name="selectTime" id="selectTime" >
												
																			<option value="">Timing</option>
																				<?php
																					$Timing= $objQuery->mysqlSelect("*","timings","","","","","");
																					foreach($Timing as $TimeList) {
																						
																				?>
																					<option value="<?php echo $TimeList["Timing_id"]; ?>"><?php echo $TimeList["Timing"]; ?></option>
																				<?php
																					}
																			
																				?>		
																	</select>
																	</div>
																	</div>
																</div>
																
															<!-- </form> -->
														</div>
														<!-- add episodes section -->

												</div>
												<div class="form-group">
													<div class="col-md-6 col-sm-6 col-xs-12 text-right" style="margin-top:10px; float:right;">
														<button type="submit" name="save_patient_edit" id="save_patient_edit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> SAVE</button>
														<button type="submit" name="save_patient" id="save_patient" class="btn btn-primary"><i class="fa fa-floppy-o"></i> SAVE & EXIT</button>
														
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

		<script>
			$( document ).ready(function() {
				//var prescription_seq = parseInt('<?php echo $prescription_seq ?>');

				function addPrescriptionTr() {
					var prescription_seq = parseInt($('#hid_prescription_seq').val());

					prescription_seq = (prescription_seq + 1);
					var new_prescription_tr = '<tr class="link1" id="prescription_del_'+ prescription_seq +'_row">';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="tagName  expandwidth" name="prescription_trade_name['+ prescription_seq +']" id="prescription_trade_name_'+ prescription_seq +'" placeholder="Trade" style="width:100px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="genericName expandwidth" name="prescription_generic_name['+ prescription_seq +']" id="prescription_generic_name_'+ prescription_seq +'" placeholder="Generic" style="width:100px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="dosageName expandwidth" name="prescription_dosage_name['+ prescription_seq +']" id="prescription_dosage_name_'+ prescription_seq +'" placeholder="Dosage" style="width:80px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="route expandwidth" name="prescription_route['+ prescription_seq +']" id="prescription_route_'+ prescription_seq +'" placeholder="Route" style="width:100px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="frequency expandwidth" name="prescription_frequency['+ prescription_seq +']" id="prescription_frequency_'+ prescription_seq +'" placeholder="Freq" style="width:100px;border:none;"></textarea>';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<textarea name="prescription_instruction['+ prescription_seq +']" id="prescription_instruction_'+ prescription_seq +'" placeholder="Instruction" style="width:100px;border:none;"></textarea>';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<button id="prescription_del_'+ prescription_seq +'" class="delbutton">Delete</button>';
						new_prescription_tr +=  '</td>';
					new_prescription_tr +=  '</tr>';

					$('#hid_prescription_seq').val(prescription_seq);

					$( "#employee-grid" ).append( new_prescription_tr );

				
					$(".delbutton").click(function() {
						var del_id = $(this).attr("id");
						if (confirm("Sure you want to delete this post? This cannot be undone later.")) {
							$("#"+del_id+"_row").remove();
						}
					});
					

					var tradeName = [ <?php echo '"' . implode ('","', $arrTradeName) . '"'; ?> ];

					$( ".tagName" ).autocomplete({
					  source: tradeName
					});

					var genericName = [ <?php echo '"' . implode ('","', $arrGenericName) . '"'; ?> ];
					$( ".genericName" ).autocomplete({
					  source: genericName
					});
					
					var dosageName = [ <?php echo '"' . implode ('","', $arrDosageName) . '"'; ?> ];
					$( ".dosageName" ).autocomplete({
					  source: dosageName
					});

					 var route = [ <?php echo '"' . implode ('","', $arrRoute) . '"'; ?> ];
					$( ".route" ).autocomplete({
					  source: route
					});

					var frequency = [ <?php echo '"' . implode ('","', $arrFrequency) . '"'; ?> ];
					$( ".frequency" ).autocomplete({
					  source: frequency
					});

					$('.expandwidth').focus(function()
					{
						/*to make this flexible, I'm storing the current width in an attribute*/
						$(this).attr('data-default', $(this).width());
						$(this).animate({ width: 250 }, 'slow');
					}).blur(function()
					{
						/* lookup the original width */
						var w = $(this).attr('data-default');
						$(this).animate({ width: w }, 'slow');
					});
				}

				$('.addTr').click(function() {
					addPrescriptionTr();
				});

				addPrescriptionTr();

				$('#chkSaveTemplate').click(function() {
					$("#template_name").val('');
					$("#template_name").toggle();
				});

			});

			function loadPrescriptionTemplate(template_id)
			{
				var delay = 1000;
				var prescription_seq = $('#hid_prescription_seq').val();
				//alert(template_id);
				$.ajax({
					type: "POST",
					url: "my_patient_prescription_template.php",
					data:{"template_id":template_id, prescription_seq: prescription_seq},
					success: function(data) {
						setTimeout(function() {
						  delaySuccess(data);
						}, delay);
					  }
					/*
					success: function(data){
						//$("#slctState").html(data);
						alert("aa");
						$('#employee-grid tbody').append(data, function () {
							alert("a");
						});
						$('#employee-grid tbody').html(data);
						setTimeout(continueExecution, 10000)
						alert("bb");


					}
					*/
				});
			}

			function delaySuccess(data) {
				$('#employee-grid tbody').append(data);
				$(".delbutton").click(function() {
					var del_id = $(this).attr("id");
					if (confirm("Sure you want to delete this post? This cannot be undone later.")) {
						$("#"+del_id+"_row").remove();
					}
				});
				var prescription_seq = $('#employee-grid tbody tr').length;
				$('#hid_prescription_seq').val(prescription_seq);

				$('.expandwidth').focus(function()
				{
					/*to make this flexible, I'm storing the current width in an attribute*/
					$(this).attr('data-default', $(this).width());
					$(this).animate({ width: 250 }, 'slow');
				}).blur(function()
				{
					/* lookup the original width */
					var w = $(this).attr('data-default');
					$(this).animate({ width: w }, 'slow');
				});
			}
		</script>

  </body>
</html>

<script type="text/javascript">
<!--
	$('.expandwidth').focus(function()
	{		
		/*to make this flexible, I'm storing the current width in an attribute*/
		$(this).attr('data-default', $(this).width());
		$(this).animate({ width: 250 }, 'slow');
	}).blur(function()
	{
		/* lookup the original width */
		var w = $(this).attr('data-default');
		$(this).animate({ width: w }, 'slow');
	});

	$(".prescriptionTemplate").change(function() {
		
		var template_id = this.value;
		if(this.checked) {
			loadPrescriptionTemplate(template_id);
		}
		else
		{
			$("[id^='prescription_del_"+ template_id +"']").remove(); 
		}
	});

	function deletePrec(deleteID)
	{
		alert("asdsd");
		/*
		if (confirm("Sure you want to delete this post? This cannot be undone later.")) {
			$("#"+deleteID).remove(); 
		}
		*/
		
	}
	
			
		var data = <?php echo $arrPatientList ?>;

		//alert(data);

		$(".patientList").autocomplete({
				minLength: 2,
				source: data,
				focus: function(event, ui) {
					$(".patientList").val(ui.item.label);
					return false;
				}
			})
			.autocomplete("instance")._renderItem = function(ul, item) {
				return $("<li>")
					.append('<a href="' + item.value + '">' + item.label + '</a>')
					.appendTo(ul);
			};


	
//-->
</script>