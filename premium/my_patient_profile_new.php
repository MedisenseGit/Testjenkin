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
//$objQuery = new CLSQueryMaker();

	//$patientprofile = $objQuery->mysqlSelect("*","my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
	

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


	$patient_tagName = mysqlSelect("prescription_trade_name","doc_patient_episode_prescriptions","","rand()","prescription_trade_name","","0,2000");
	while(list($key, $val) = each($patient_tagName))
	{
		$arrTradeName[] = $val['prescription_trade_name'] ;
	}

	$patient_genericName = mysqlSelect("prescription_generic_name","doc_patient_episode_prescriptions","","rand()","prescription_generic_name","","0,1000");
	while(list($key, $val) = each($patient_genericName))
	{
		$arrGenericName[] = $val['prescription_generic_name'] ;
	}
	
	/*$patient_dosageName = mysqlSelect("prescription_dosage_name","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_dosage_name","","");
	while(list($key, $val) = each($patient_dosageName))
	{
		$arrDosageName[] = $val['prescription_dosage_name'] ;
	}
	
	$patient_route = mysqlSelect("prescription_route","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_route","","");
	while(list($key, $val) = each($patient_route))
	{
		$arrRoute[] = $val['prescription_route'] ;
	}
*/
	$patient_duration = mysqlSelect("duration","doc_patient_episode_prescriptions","","","duration","","");
	while(list($key, $val) = each($patient_duration))
	{
		$arrDuration[] = $val['duration'] ;
	}
	
	$patient_timing = mysqlSelect("timing","doc_patient_episode_prescriptions","","","timing","","");
	while(list($key, $val) = each($patient_timing))
	{
		$arrTiming[] = $val['timing'] ;
	}
	
	$patient_frequency = mysqlSelect("prescription_frequency","doc_patient_episode_prescriptions",""," episode_id DESC ","prescription_frequency","","");
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
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	
	<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/status_validationJs.js"></script>
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

<body>

    <div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
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
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row m-b-lg m-t-lg">
                <div class="col-md-6">

                    <div class="profile-image">
                        <img src="../assets/img/anonymous-profile.png" class="img-circle circle-border m-b-md" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $patient_tab[0]['patient_name']; ?>
                                </h2>
                                <h4><i class="fa fa-mobile"></i> <?php echo $patient_tab[0]['patient_mob']; ?></h4>
								 <h4><i class="fa fa-stack-exchange"></i> <?php echo $patient_tab[0]['patient_email']; ?></h4>
                                <small>
                                    <?php echo $patient_tab[0]['patient_addrs'].", ".$patient_tab[0]['patient_loc'].", ".$patient_tab[0]['pat_state']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Gender</strong> <?php echo $gender; ?>
                            </td>
                            <td>
                                <strong>Age</strong> <?php echo $patient_tab[0]['patient_age']; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>
                                <strong>Hypertension</strong> <?php echo $hyperStatus; ?>
                            </td>
                            <td>
                                <strong>Diabetes</strong> <?php echo $diabetesStatus; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Smoking</strong> <?php echo $patient_tab[0]['smoking']; ?>
                            </td>
                            <td>
                                <strong>Alcolol</strong> <?php echo $patient_tab[0]['alcoholic']; ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
               

            </div>
            <div class="row">

                <div class="col-lg-12 m-b-lg">
                    <div id="vertical-timeline" class="vertical-container light-timeline no-margins">
                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-wheelchair"></i>
                            </div>
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php">
							<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                            <div class="vertical-timeline-content">
                                 <h2>Medical History</h2>
                                <!--<p>Conference on the sales results for the previous year. Monica please examine sales trends in marketing and products. Below please find the current status of the sale.
                                </p>
                                <a href="#" class="btn btn-sm btn-primary"> More info</a>
                                    <span class="vertical-date">
                                        Today <br>
                                        <small>Dec 24</small>
                                    </span>-->
								
									<div class="col-lg-5">
									
									<dl>
										<dt>Hypertension:</dt><br> <dd><?php if($patient_tab[0]['hyper_cond']=="1"){ ?>
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
                                    <?php } ?></dd><br>
                                        <dt>Diabetes:</dt><br> <dd> <?php if($patient_tab[0]['diabetes_cond']=="1"){ ?>
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
									<?php } ?>  <!--<small><a href="#" data-toggle="modal" data-target="#myModal"> Add/View</a></small>--></dd><br>
                                        <dt>Smoking:</dt><br> <dd><select class="form-control" name="se_smoking" name="se_smoking">
														
														<?php if($patient_tab[0]['smoking']=="Non-smoker"){ ?>
														<option value="Non-smoker" selected>Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day">10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day"  >>20 Cig a day</option>
														<?php } else if($patient_tab[0]['smoking']=="Passive smoker"){ ?>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" selected>Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day">10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day"  >>20 Cig a day</option>
														<?php } else if($patient_tab[0]['smoking']=="5 Cig a day"){ ?>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" selected>5 Cig a day</option>
														<option value="10 Cig a day">10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day"  >>20 Cig a day</option>
														<?php } else if($patient_tab[0]['smoking']=="10 Cig a day"){ ?>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day" selected>10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day"  >>20 Cig a day</option>
														<?php } else if($patient_tab[0]['smoking']=="20 Cig a day"){ ?>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day" >10 Cig a day</option>
														<option value="20 Cig a day" selected>20 Cig a day</option>
														<option value=">20 Cig a day"  >>20 Cig a day</option>
														<?php } else if($patient_tab[0]['smoking']==">20 Cig a day"){ ?>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day" >10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day"  selected >>20 Cig a day</option>
														<?php } else {?>
														<option value=""  selected>Select</option>
														<option value="Non-smoker" >Non-smoker</option>
														<option value="Passive smoker" >Passive smoker</option>
														<option value="5 Cig a day" >5 Cig a day</option>
														<option value="10 Cig a day" >10 Cig a day</option>
														<option value="20 Cig a day" >20 Cig a day</option>
														<option value=">20 Cig a day" >>20 Cig a day</option>
														<?php } ?>
													</select></dd><br>
										<dt>Alcohol:</dt><br> <dd><select class="form-control" name="se_alcoholic" name="se_alcoholic">
														
														<?php if($patient_tab[0]['alcoholic']=="Non-alcoholic"){ ?>
														<option value="Non-alcoholic" selected>Non-alcoholic</option>
														<option value="Moderate" >Moderate</option>
														<option value="Chronic" >Chronic</option>
														<?php } else if($patient_tab[0]['alcoholic']=="Moderate"){ ?>
														<option value="Non-alcoholic" >Non-alcoholic</option>
														<option value="Moderate" selected>Moderate</option>
														<option value="Chronic" >Chronic</option>
														<?php } else if($patient_tab[0]['alcoholic']=="Chronic"){ ?>
														<option value="Non-alcoholic" >Non-alcoholic</option>
														<option value="Moderate" >Moderate</option>
														<option value="Chronic" selected>Chronic</option>
														<?php } else { ?>
														<option value=""  selected>Select</option>
														<option value="Non-alcoholic" >Non-alcoholic</option>
														<option value="Moderate" >Moderate</option>
														<option value="Chronic" >Chronic</option>
														<?php } ?>
														
														
													</select></dd><br>
                                        <dt>Drug Abuse</dt><br> <dd><textarea class="form-control" id="drug_abuse" required="required" name="drug_abuse" rows="2"><?php echo $patient_tab[0]['drug_abuse']; ?></textarea></dd><br>
                                        <dt>Other Details</dt><br> <dd><textarea class="form-control" id="other_details" required="required" name="other_details" rows="2"><?php echo $patient_tab[0]['other_details']; ?></textarea></dd><br>
										
									</dl>
									</div>
									
									<div class="col-lg-7">
									
									<dl>
										
										<dt>Family History</dt><br> <dd><textarea class="form-control" id="family_history" required="required" name="family_history" rows="2"><?php echo $patient_tab[0]['family_history']; ?></textarea></dd><br>
										<dt>Previous Interventions</dt><br> <dd><textarea class="form-control" id="prev_inter" required="required" name="prev_inter" rows="2"><?php echo $patient_tab[0]['prev_inter']; ?></textarea></dd><br>
										<dt>Stroke or known neurological issues</dt><br> <dd><textarea class="form-control" id="neuro_issue" required="required" name="neuro_issue" rows="2"><?php echo $patient_tab[0]['neuro_issue']; ?></textarea></dd><br>
										<dt>Known kidney issues</dt><br> <dd><textarea class="form-control" id="kidney_issue" required="required" name="kidney_issue" rows="2"><?php echo $patient_tab[0]['kidney_issue']; ?></textarea></dd><br>
                                        
										
									</dl>
									<button type="submit" name="updatePatient" class="btn btn-primary"> UPDATE</button>
									
									</div>
								
                            </div>
							</form>	
                        </div>

                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon blue-bg">
                                <i class="fa fa-file-text"></i>
                            </div>

                            <div class="vertical-timeline-content">
                                <h2>Add Episode Details</h2>
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
							<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                               <div class="col-lg-6">
									
									<dl>
										
										<dt>Chief Medical Complaint</dt><br> <dd><textarea class="form-control" id="medical_complaint" name="medical_complaint" rows="2" tabindex="1"></textarea></dd><br>
										<dt>Investigations</dt><br> <dd><select data-placeholder="Enter test here..." class="chosen-select" name="selectDiagnoTest[]" multiple  tabindex="3">
										<?php $getTest= mysqlSelect("*","diagnosis_tests","","test_name asc","","","");
												
												foreach($getTest as $TestList){ ?>
							
													<option value="<?php echo stripslashes($TestList['test_id']);?>" /><?php echo stripslashes($TestList['test_name']);?></option>
												<?php
														
												}?>
											</select></dd><br>
										 <dt>Treatment</dt><br> <dd><textarea class="form-control" id="txt_treatment" required="required" name="txt_treatment" rows="2" tabindex="5"></textarea></dd><br>
									</dl>
								</div>	
								<div class="col-lg-6">
									<dl>
									<dt>Examination</dt><br> <dd><textarea class="form-control" id="examination" required="required" name="medical_examination" rows="2" tabindex="2"></textarea></dd><br>
										
										<dt>Diagnosis</dt><br> <dd><select data-placeholder="Enter ICD Code/investigation name here..." class="chosen-select" name="selectICD[]" multiple  tabindex="4">
										<?php $getICD= mysqlSelect("*","icd_code","","","icd_code","","0,10000");
												
												foreach($getICD as $IcdList){ ?>
							
													<option value="<?php echo stripslashes($IcdList['icd_id']);?>" /><?php echo stripslashes($IcdList['icd_code']);?></option>
												<?php
														
												}?>
											</select></dd><br>
                                       
										
										
									</dl>
								</div>
								<div class="col-lg-12">
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
																						echo '<li role="presentation"><a role="menuitem" tabindex="-1" style="cursor:default" ><input  type="checkbox" id="prescriptionTemplate_'.$prescription_template_val['template_id'].'" name="prescriptionTemplate['. $prescription_template_val['template_id'] .']" class="prescriptionTemplate" value="'. $prescription_template_val['template_id'] .'" onclick="return loadTemplate('.$prescription_template_val['template_id'].','.$patient_tab[0]['patient_id'].')"> '. $prescription_template_val['template_name'].'</a></li>';
																					}
																					echo '</ul>';
																				}
																			?>
																		</li>
																	</ul>
								<select data-placeholder="Add Medicine here..." class="chosen-select" name="selectMedicine" id="selectMedicine" tabindex="3" multiple onchange="return addMedicine(this.value,<?php echo $patient_tab[0]['patient_id']; ?>);">
										<option value=""></option>
										<?php $getMedicine= mysqlSelect("episode_prescription_id,prescription_trade_name","doc_patient_episode_prescriptions","","prescription_trade_name asc","","","0,2000");
												
												foreach($getMedicine as $MedicineList){ ?>
							
													<option value="<?php echo stripslashes($MedicineList['episode_prescription_id']);?>" /><?php echo stripslashes($MedicineList['prescription_trade_name']);?></option>
												<?php
														
												}?>
								</select>
											
								<div id="dispMedTable"></div>
								
								<!--<br><div class="text-right"><a href="javascript: void(0)" onclick="return addBlankrow(<?php echo $patient_tab[0]['patient_id']; ?>); " class="addTr btn btn-primary"><i class="fa fa-plus"></i> Add More</a></div>	-->					
								<!--<ul class="nav nav-pills navbar-right" role="tablist">
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
																	<?php
																			$prescription_seq = 0;
																		?>
																		<input type="hidden" name="hid_prescription_seq" id="hid_prescription_seq" value="<?php echo $prescription_seq ?>" />
																		<table id="employee-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				th>Dosage</th>
																				<th>Route</th>
																				<th>Frequency</th>
																				<th>Timing</th>
																				<th>Duration</th>
																				<th>Note</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			</tbody>
																			
																		</table>
																		<div class="text-right"><a href="javascript: void(0)" class="addTr btn btn-primary"><i class="fa fa-plus"></i> Add More</a></div>-->
												<label> <input type="checkbox" class="i-checks" name="chkSaveTemplate" id="chkSaveTemplate" value="1"> Save this as template</label>
										 <div class="form-group"><input type="text" name="template_name" id="template_name" placeholder="Template Name" style="width:200px; display: none;" class="form-control"></div>	

											<div class="form-group"><label>Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
                                   <!-- <input name="txtphoto1[]" id="txtphoto1[]" type="file" multiple />-->
								 <!--  <div class="col-md-6">
      <input type="file" class="form-control" id="images" name="txtphoto1[]" onchange="preview_images();" multiple/>
  </div>-->
  
  
									<div class="form-group">
										<div class="file-loading">
											<input id="file-5" name="file-5[]" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="#">
										</div>
									</div>
   
                                </div>
								 <div class="row" id="image_preview"></div>
								 <div class="col-lg-6">
									<dl>
										<dt>Next Follow Up Date</dt><br> <dd><div class="pull-left m-r input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="dateadded" type="text" placeholder="Select date" required="required" class="form-control" >
										</div></dd><br>
									</dl>
								</div>
								</div>
									<button class="btn btn-sm btn-primary pull-right" name="save_patient_edit" id="save_patient_edit" type="submit"><strong><i class="fa fa-floppy-o"></i> ADD EPISODE</strong></button>
									
                            </div>
							</form>
                        </div>


                        <div class="vertical-timeline-block">
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-file-text"></i>
                            </div>

                            <div class="vertical-timeline-content">
							
                               <h2>All Episode Details</h2>
                               <div class="ibox-content">
							<?php
								$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");

								if (count($patient_episodes) > 0)
								{ ?>
                            <table class="footable table table-stripped toggle-arrow-tiny table-responsive">
                                <thead>
                                <tr>

                                    <th data-toggle="true">EPISODES</th>
									 <th data-hide="all">Chief Medical Complaint</th>
									 <th data-hide="all">Examination</th>
									 <th data-hide="all">Diagnosis</th>
									 <th data-hide="all">Investigations</th>
                                    <th data-hide="all">Medical Prescriptions</th>                              
                                    
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
                                    <td><?php echo $patient_episode_val['episode_medical_complaint'] ?><br><br></td>
                                    <td><?php echo $patient_episode_val['examination'] ?><br><br></td>
									
									<td><?php 
									$get_diagnosis = mysqlSelect("a.icd_code as icd_name","icd_code as a left join patient_diagnosis as b on a.icd_id=b.icd_id","b.patient_id = '". $patient_tab[0]['patient_id'] ."' and b.doc_id= '". $admin_id ."' and b.episode_id='". $patient_episode_val['episode_id']."'","","","","");
									while(list($key, $value) = each($get_diagnosis))	
									{
									echo "<span class='label label-danger'>".$value['icd_name']."</span>&nbsp;&nbsp;&nbsp;"; 
									}
									?><br><br></td>
									
									<td>
									
									
									<label>Refer to Diagnostic Center</label>
									<select data-placeholder="Choose Diagnostic Center.." class="chosen-select" name="selectDignosticCenter" id="selectDignosticCenter" tabindex="3">
										<option value=""></option>
										<?php $getDiagnostic= mysqlSelect("*","Diagnostic_center","","diagnosis_name asc","","","");
												
												foreach($getDiagnostic as $getDiagnosticList){ ?>
							
													<option value="<?php echo stripslashes($getDiagnosticList['diagnostic_id']);?>" /><?php echo stripslashes($getDiagnosticList['diagnosis_name']).", ".stripslashes($getDiagnosticList['diagnosis_city']);?></option>
												<?php
														
												}?>
								</select><br><br><button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary">REFER</button><br>
									<br><br><table class="table table-bordered">
										<thead>
										<tr>
										<th>Test</th>
										<th>Normal Value</th>
										<th>Actual Value</th>
										</tr>
										<thead>
										<tbody>
										<tr>
										
									<?php 
									
									$get_invest = mysqlSelect("b.sub_test_name as sub_test_name,b.normal_range as normal_range","patient_investigation as a left join diagnosis_sub_tests as b on a.test_id=b.test_id","a.patient_id = '". $patient_tab[0]['patient_id'] ."' and a.doc_id= '". $admin_id ."' and a.episode_id='". $patient_episode_val['episode_id']."'","","","","");
									while(list($key, $value) = each($get_invest))	
									{  ?>
									<tr>
									<td><?php echo $value['sub_test_name']; ?></td>
									<td><?php echo $value['normal_range']; ?></td>
									<td><input type="text" class="tagName" name=""  value="" placeholder="" style="width:100px;border:none;"></td>
									</tr>
									<?php }
									?>
                                   </tbody>
								   </table>
								   </br>
									<td>
									<label>Refer to Pharmacy</label>
									<select data-placeholder="Choose Pharmacy.." class="chosen-select" name="selectDignosticCenter" id="selectDignosticCenter" tabindex="3" onchange="">
										<option value=""></option>
										<?php $getPharma= mysqlSelect("*","pharma","","pharma_name asc","","","");
												
												foreach($getPharma as $getPharmaList){ ?>
							
													<option value="<?php echo stripslashes($getPharmaList['pharma_id']);?>" /><?php echo stripslashes($getPharmaList['pharma_name']).", ".stripslashes($getPharmaList['pharma_city']);?></option>
												<?php
														
												}?>
								</select><br><br><button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary">REFER</button><br><br>
									<?php
									$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");
									$doc_patient_episode_attachment = mysqlSelect("attach_id,my_patient_id,episode_id,attachments","doc_patient_attachments","episode_id = '". $patient_episode_val['episode_id'] ."' ","","","","");
	
									if (count($doc_patient_episode_prescriptions) > 0)
									{
									?>
									<table class="table table-bordered">
										<thead>
										<tr>
										<th>Medicine</th>
										<th>Generic Name</th>
										<th>Frequency</th>
										<th>Timing</th>
										<th>Duration</th>
										<th>Note</th>
										
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
											<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
											<td><?php echo $patient_episode_prescription_val['timing'] ?></td>
											<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
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
                                  <a href="https://medisensecrm.com/premium/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&episode_attach=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a></small>
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
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="frequency expandwidth" name="prescription_frequency['+ prescription_seq +']" id="prescription_frequency_'+ prescription_seq +'" placeholder="Freq" style="width:100px;border:none;"></textarea>';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="timing expandwidth" name="prescription_timing['+ prescription_seq +']" id="prescription_timing_'+ prescription_seq +'" placeholder="Timing" style="width:80px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="duration expandwidth" name="prescription_duration['+ prescription_seq +']" id="prescription_duration_'+ prescription_seq +'" placeholder="Duration" style="width:80px;border:none;">';
						new_prescription_tr +=  '</td>';
						/*new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="dosageName expandwidth" name="prescription_dosage_name['+ prescription_seq +']" id="prescription_dosage_name_'+ prescription_seq +'" placeholder="Dosage" style="width:80px;border:none;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="route expandwidth" name="prescription_route['+ prescription_seq +']" id="prescription_route_'+ prescription_seq +'" placeholder="Route" style="width:100px;border:none;">';
						*/
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<textarea name="prescription_instruction['+ prescription_seq +']" id="prescription_instruction_'+ prescription_seq +'" placeholder="Note" style="width:100px;border:none;"></textarea>';
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
					
					/*var dosageName = [ <?php echo '"' . implode ('","', $arrDosageName) . '"'; ?> ];
					$( ".dosageName" ).autocomplete({
					  source: dosageName
					});

					 var route = [ <?php echo '"' . implode ('","', $arrRoute) . '"'; ?> ];
					$( ".route" ).autocomplete({
					  source: route
					});*/

					var frequency = [ <?php echo '"' . implode ('","', $arrFrequency) . '"'; ?> ];
					$( ".frequency" ).autocomplete({
					  source: frequency
					});
					
					var timing = [ <?php echo '"' . implode ('","', $arrTiming) . '"'; ?> ];
					$( ".timing" ).autocomplete({
					  source: timing
					});
					
					var duration = [ <?php echo '"' . implode ('","', $arrDuration) . '"'; ?> ];
					$( ".duration" ).autocomplete({
					  source: duration
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
   <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

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
 <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>


    <script>
        $(document).ready(function(){

            $('.typeahead_1').typeahead({
                source: ["item 1","item 2","item 3"]
            });

            $.get('js/api/typehead_collection.json', function(data){
                $(".typeahead_2").typeahead({ source:data.countries });
            },'json');

            $('.typeahead_3').typeahead({
                source: [
                    {"name": "Afghanistan", "code": "AF", "ccn0": "040"},
                    {"name": "Land Islands", "code": "AX", "ccn0": "050"},
                    {"name": "Albania", "code": "AL","ccn0": "060"},
                    {"name": "Algeria", "code": "DZ","ccn0": "070"}
                ]
            });


        });
    </script>