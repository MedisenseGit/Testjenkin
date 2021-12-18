<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");


	//$patientprofile = mysqlSelect("*","my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	

	$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	if($patient_tab[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['patient_gen']=="2"){
		$gender="Female";
	}

	if($patient_tab[0]['hyper_cond']=="2"){
		$hyperStatus="No";
	}
	else if($patient_tab[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($patient_tab[0]['diabetes_cond']=="2"){
		$diabetesStatus="No";
	}
	else if($patient_tab[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}
	//$patient_referal = mysqlSelect("*","patient_referal","md5(patient_id)='".$_GET['p']."'"," patient_referal_id DESC ","",""," 0,1 ");

	$patient_id = $patient_tab[0]['patient_id'];


	$patient_tagName = mysqlSelect("prescription_trade_name","doc_patient_episode_prescriptions",""," prescription_trade_name ASC ","prescription_trade_name","","0,200");
	while(list($key, $val) = each($patient_tagName))
	{
		$arrTradeName[] = $val['prescription_trade_name'] ;
	}

	$patient_genericName = mysqlSelect("prescription_generic_name","doc_patient_episode_prescriptions",""," prescription_generic_name ASC ","prescription_generic_name","","0,200");
	while(list($key, $val) = each($patient_genericName))
	{
		$arrGenericName[] = $val['prescription_generic_name'] ;
	}
	
	$patient_dosageName = mysqlSelect("prescription_dosage_name","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_dosage_name","","0,10");
	while(list($key, $val) = each($patient_dosageName))
	{
		$arrDosageName[] = $val['prescription_dosage_name'] ;
	}
	
	$patient_route = mysqlSelect("prescription_route","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_route","","0,10");
	while(list($key, $val) = each($patient_route))
	{
		$arrRoute[] = $val['prescription_route'] ;
	}

	$patient_frequency = mysqlSelect("prescription_frequency","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_frequency","","0,10");
	while(list($key, $val) = each($patient_frequency))
	{
		$arrFrequency[] = $val['prescription_frequency'] ;
	}


	$patient_list = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","");
	
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
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patient Profile</title>

    <?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
			<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-10">
                    <h2>My Patient Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>My Patient Profile</strong>
                        </li>
                    </ol>
                </div>
				<div class="col-lg-2 mgTop">
					<a href="My-Patients"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <div class="ibox">
                        <div class="ibox-content">
						
                            <div class="row">
							<?php if($_GET['response']=="updated"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Patient record has been updated successfully </strong>
								 </div>
								<?php } ?>
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <a href="#" class="btn btn-white btn-xs pull-right" data-toggle="modal" data-target="#myModal">Edit Profile</a>
                                        
										<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title"><?php echo $patient_tab[0]['patient_name']; ?></h4>
                                            <small class="font-bold">Patient Profile</small>
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                                    
                                        <div class="modal-body">
                                            <div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="<?php echo $patient_tab[0]['patient_name']; ?>" class="form-control"></div>
                                 
								 <div class="row"><div class="form-group"><label class="col-sm-2 control-label">Age</label>
                                    <div class="col-sm-4"><input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $patient_tab[0]['patient_age']; ?>" class="form-control">
                                    </div>
									<label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-4">
									<?php if($patient_tab[0]['patient_gen']=="1"){ ?>

										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="checked">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Other </label>
                                        </div>
									<?php } else if($patient_tab[0]['patient_gen']=="2") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Other </label>
                                        </div>
									<?php } else if($patient_tab[0]['patient_gen']=="3") { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender" checked="">
                                            <label for="inlineRadio2"> Other </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } ?>
                                    </div>
                                </div>
								</div><br>
								<div class="row">
								 <div class="form-group"><label class="col-sm-3 control-label">Hypertension</label>
                                    <div class="col-sm-3">
									<?php if($patient_tab[0]['hyper_cond']=="1"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper" checked="">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else if($patient_tab[0]['hyper_cond']=="2"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper"  checked="">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
                                    <?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
                                    <?php } ?>
									</div>
                                    
									<label class="col-sm-2 control-label">Diabetes</label>
                                    <div class="col-sm-3">
									<?php if($patient_tab[0]['diabetes_cond']=="1"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets" checked="">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else if($patient_tab[0]['diabetes_cond']=="2"){ ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets"  checked="">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } else { ?>
									<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									<?php } ?>
                                    </div>
                                </div>
								</div>		
								<br>		
										
										<div class="form-group"><label>Weight</label> <input type="text" id="se_weight" name="se_weight" value="<?php echo $patient_tab[0]['weight']; ?>" class="form-control"></div>
										<div class="form-group"><label>Mobile No</label> <input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $patient_tab[0]['patient_mob']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="<?php echo $patient_tab[0]['patient_email']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Country</label> <select class="form-control" name="se_country" name="se_country">
														<option value="India" <?php echo (!isset($patient_tab[0]['pat_country']) ? 'selected' : ($patient_tab[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($patient_tab[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
													</select></div>
										<div class="form-group"><label>State</label> <select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
														<?php
														$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
														foreach ($GetState as $StateList) {
														?>
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($patient_tab[0]['pat_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

														<?php
														}
														?>
													</select></div>
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php echo $patient_tab[0]['patient_loc']; ?>" class="form-control"></div>
										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="<?php echo $patient_tab[0]['patient_addrs']; ?>" class="form-control"></div>
										
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                            <button type="submit" name="updatePatient" class="btn btn-primary">UPDATE</button>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
										
										
										<h2><?php echo $patient_tab[0]['patient_name']; ?>( #<?php echo $patient_tab[0]['patient_id']; ?> )</h2>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
							<div class="col-lg-2 m-l-md" id="cluster_info">
							<img src="../assets/img/user_noimg.png" width="150" class="img-thumbnail" />
							
							</div>
							<div class="col-lg-5" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                        <dt>Mobile No:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['patient_mob']; ?>
										</dd>
										<dt>Email:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['patient_email']; ?>
										</dd>
										<dt>Country:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['pat_country'];  ?>
										</dd>
										<dt>State:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['pat_state'];  ?>
										</dd>
										<dt>City:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['patient_loc'];  ?>
										</dd>
										<dt>Address:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['patient_addrs'];  ?>
										</dd>
										
									
                                       
                                    </dl>
                                </div>
                                <div class="col-lg-4">
                                    <dl class="dl-horizontal">

                                        <dt>Age:</dt> <dd><?php echo $patient_tab[0]['patient_age']; ?></dd>
                                        <dt>Gender:</dt> <dd> <?php echo $gender;  ?></dd>
										<dt>Hypertension:</dt> <dd><?php echo $hyperStatus; ?></dd>
                                        <dt>Diabetes:</dt> <dd> <?php echo $diabetesStatus;  ?></dd>
                                        <dt>Weight:</dt>
                                        <dd class="project-people">
                                        <?php echo $patient_tab[0]['patient_age'];  ?>
										</dd>
										
									</dl>
                                </div>
                                
                            </div>
                           <!-- <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                        <dt>Completed:</dt>
                                        <dd>
                                            
                                            <small>Project completed in <strong>60%</strong>. Remaining close the project, sign a contract and invoice.</small>
                                        </dd>
                                    </dl>
                                </div>
                            </div>-->
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                                <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-1" data-toggle="tab">ALL EPISODES</a></li>
                                            <li class=""><a href="#tab-2" data-toggle="tab">ADD NEW EPISODE</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="panel-body">

                                <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                    <div class="ibox-content">
							<?php
								$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");

								if (count($patient_episodes) > 0)
								{ ?>
                            <table class="footable table table-stripped toggle-arrow-tiny table-responsive">
                                <thead>
                                <tr>

                                    <th data-toggle="true">EPISODES</th>
                                    <th data-hide="all">Description</th>
                                    <th data-hide="all">Medical Complaint</th>
                                    <th data-hide="all">Prescription</th>
									<th data-hide="all">Reports</th>
                                    <th data-hide="all">Special Instructions</th>
                                    
                                </tr>
                                </thead>
                                <tbody>
								<?php 								
									$patient_episode_count = 0;
									while (list($patient_episode_key, $patient_episode_val) = each($patient_episodes))
									{
										$patient_episode_count++;
								?>
                                <tr>
                                    <td><b>#Episode <?php echo $patient_episode_count." - ".$patient_episode_val['formated_date_time']; ?></b></td>
                                    <td><?php echo $patient_episode_val['episode_desc'] ?></td>
                                    <td><?php echo $patient_episode_val['episode_medical_complaint'] ?></td>
                                    <td><br><br>
									<?php
									$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");
									$doc_patient_episode_attachment = mysqlSelect("attach_id,my_patient_id,episode_id,attachments","doc_patient_attachments","episode_id = '". $patient_episode_val['episode_id'] ."' ","","","","");
	
									if (count($doc_patient_episode_prescriptions) > 0)
									{
									?>
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
										while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
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
										</table></td>
										<td>
											<p>
                              <span><i class="fa fa-paperclip"></i> <?php echo COUNT($doc_patient_episode_attachment); ?> attachments </span>
                              <!--<a href="#">Download all attachments</a> |
                              <a href="#">View all images</a>-->
                            </p>
                            <ul>
							<?php foreach($doc_patient_episode_attachment as $attachList){ 
							//Here we need to check file type
							$img_type =  array('gif','png' ,'jpg' ,'jpeg');
							$extractPath = pathinfo($attachList['attachments'], PATHINFO_EXTENSION);
							if(in_array($extractPath,$img_type) ) {
								$imgIcon="episodeAttach/".$attachList['attach_id']."/".$attachList['attachments'];
							}
							else if($extractPath=="docx"){
									$imgIcon="../assets/images/doc.png";
							}
							else if($extractPath=="pdf" || $extractPath=="PDF"){
								$imgIcon="../assets/images/pdf.png";
							} ?>
                              <div class="file-box">
                                <div class="file">
                                    <a href="#">
                                        <span class="corner"></span>
										<a href="episodeAttach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
                                        <div class="image">
                                            <img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
                                        </div></a>
                                        <div class="file-name">
                                            <?php echo substr($attachList['attachments'],0,10); ?>
                                            <br/>
                                            <small><a href="episodeAttach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a> -
                                  <a href="<?php echo HOST_MAIN_URL; ?>premium/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&episode_attach=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a></small>
                                        </div>
                                    </a>

                                </div>
                            </div>
							<?php } ?>
                              

                            </ul>
										
										</td>
                                    <td><?php echo $patient_episode_val['episode_special_instruction'] ?></td>
                                </tr>
								<?php  } 
									}
								
								?>
								</tbody>
								
                                <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
							<?php } 
							else { 
							?>
							<h3> No episodes created </h3>
							<?php } ?>
                        </div>

                                </div>
                                <div class="tab-pane" id="tab-2">

                                   <div class="col-sm-12"><h3 class="m-t-none m-b">Add Episode</h3>
                                <!--<p>Sign in today for more expirience.</p>-->
                                <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                                    <div class="form-group"><label>Chief Medical Complaint</label> <textarea class="form-control" id="episode_medical_complaint" required="required" name="episode_medical_complaint" rows="2"></textarea></div>
                                    <div class="form-group"><label>Detailed Description</label> <textarea class="form-control" id="episode_desc" required="required" name="episode_desc" rows="2"></textarea></div>
                                    
								
															<div class="x_title">
																	<h2>Add Prescriptions</h2>																	
																	<ul class="nav nav-pills navbar-right" role="tablist">
																		<li role="presentation" class="dropdown">
																			<a id="drop4" href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Load Template<span class="caret"></span></a>
																			<?php
																				$prescription_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'"," template_name ASC","","","");

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
																				<th>Instructions</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			</tbody>
																			<!-- <form method="post" action="send.php"> -->
																		</table>
																		<div class="text-right"><a href="javascript: void(0)" class="addTr btn btn-primary"><i class="fa fa-plus"></i> Add More</a></div>
																
									<div>
                                        
                                        <label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label>
										 <div class="form-group"><input type="text" name="template_name" id="template_name" placeholder="Template Name" style="display: none;" class="form-control"></div>
                                    </div>
									<div class="form-group"><label>Add Reports ( Allowed file types: jpg, jpeg, png)</label>
                                    <input name="txtphoto1[]" id="txtphoto1[]" type="file" multiple />
                                </div>
									<div class="form-group"><label>Special Instructions</label> <textarea class="form-control" id="episode_special_instruction" name="episode_special_instruction" rows="2"></textarea></div>
									<div><button class="btn btn-sm btn-primary pull-right" name="save_patient_edit" id="save_patient_edit" type="submit"><strong><i class="fa fa-floppy-o"></i> ADD EPISODE</strong></button></div>
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
                </div>
            </div>
            
        </div>
       <?php include_once('footer.php'); ?>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>

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
							new_prescription_tr +=  '<button id="prescription_del_'+ prescription_seq +'" class="btn btn-sm btn-danger pull-right m-xs delbutton"><i class="fa fa-trash"></i> Delete</button>';
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
