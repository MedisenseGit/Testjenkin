<?php
	ob_start();
	error_reporting(0); 
	session_start();
	
	$admin_id = $_SESSION['user_id'];
	//$secretary_id = $_SESSION['secretary_id'];
	//$secretary_name = $_SESSION['user_name'];
	//Get the page name 
	//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
	include('functions.php');
	if(empty($admin_id)){
		header("Location:index.php");
	}
	
		date_default_timezone_set('Asia/Kolkata');
		$Cur_Date=date('Y-m-d H:i:s');
		$cur_Date=date('Y-m-d',strtotime($Cur_Date));
		$add_days = 3;
		$Follow_Date = date('d-m-Y',strtotime($cur_Date) + (24*3600*$add_days));
		
	require_once("../classes/querymaker.class.php");
	
	
		/*if(!isset($_POST['save_patient_edit'])){
		//Clear all temp. details from 'doc_medicine_prescription_template_details' & 'patient_temp_investigation' table
		mysqlDelete('doc_patient_treatment_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doc_patient_drug_allergy_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doc_patient_family_history_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doc_patient_drug_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doc_patient_examination_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doc_patient_symptoms_active',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('doctor_temp_frequent_medicine',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('patient_temp_investigation',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		
		mysqlDelete('patient_diagnosis',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
		}	*/
		$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
		
		$_SESSION['patient_id']=$patient_tab[0]['patient_id'];
		
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
		
		
	
		$patient_id = $patient_tab[0]['patient_id'];
	 
								
		$get_doc_details = mysqlSelect("*","referal as a inner join doc_my_patient as b on a.ref_id=b.doc_id","b.patient_id='".$patient_id."'","","","","");
		
		$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");
		
		$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		$_SESSION['prescription_template'] = $checkSetting[0]['prescription_template'];
		
		if(isset($_POST['cmdchangePatient']))
		{
			$params     = explode("-", $_POST['slct_valPat']);
			if($params[0]!=0){
			$patientid = $params[0];
			header("Location:My-Patient-Details?p=".md5($patientid));
			}
			else
			{
			$patientid = "0";
					
			header("Location:My-Patient-Details?p=".$patientid."&n=".$params[0]);
			}
		}
	?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="refresh" content="1800"/>
		<title>My Patient Profile</title>
		<?php include_once('support.php'); ?>
		<!-- FooTable -->
		<link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
		<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
		<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
		<!-- orris -->
		<link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
		<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
		<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
		<!-- Toastr style -->
		<link href="../assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
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
		<!--<script src="js/Chart.bundle.js"></script>
			<script src="js/utils.js"></script>-->
		<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
		<style>
			canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
			}
		</style>
		<script>
			$(document).ready(function() {
			   $("#hideSerMed").hide();
			
			<?php if($_GET['active']=="4"){ ?>
				$("#view-latest-reports").show();
				$("#view-trend-analysis").hide();
				$("#add-visit-dtails").hide();
				$("#visit-details").hide();
				$("#medical-history").hide();
				$("#edit_visist_details").hide();
			
			<?php } ?>
			
			});
			
			function printContent(el){
				var restorepage=document.body.innerHTML;
				var printcontent=document.getElementById(el).innerHTML;
				document.body.innerHTML=printcontent;
				window.print();
				document.body.innerHTML=restorepage;
				
			}
		</script>
		<!--<script type="text/javascript" src="js/jquery.js"></script>
			<script type="text/javascript" src="js/jquery-ui.js"></script>
			<link rel="stylesheet" href="css/jquery-ui.css">-->
		<script type="text/javascript">
			$(function() 
			{
			 $( "#coding_language" ).autocomplete({
			  source: 'get_icd.php'
			 });
			 
			 $( "#coding_language1" ).autocomplete({
			  source: 'get_icd.php'
			 });
			 
			  $( "#get_diagnosis" ).autocomplete({
			  source: 'get_diagnosis.php'
			 });
			 $( "#get_complaints" ).autocomplete({
			  source: 'get_healthcomplaint.php'
			 });
			 $( "#get_diagnosis_test" ).autocomplete({
			  source: 'get_diagnosis_test.php'
			 });
			  $( "#get_examination_res" ).autocomplete({
			  source: 'get_examination_res.php'
			 });
			 $( "#get_drug_abuse" ).autocomplete({
			  source: 'get_drug_abuse.php'
			 });
			 $( "#get_family_history" ).autocomplete({
			  source: 'get_family_history.php'
			 });
			 $( "#get_treatment_res" ).autocomplete({
			  source: 'get_treatment_res.php'
			 });
			 $( "#get_allergy" ).autocomplete({
			  source: 'get_allergy.php'
			 });
			 
			});
		</script>
		<style>
			.autocomplete {
			/*the container must be positioned relative:*/
			position: relative;
			display: inline-block;
			}
			.autocomplete-items {
			position: absolute;
			border: 1px solid #d4d4d4;
			border-bottom: none;
			border-top: none;
			z-index: 99;
			/*position the autocomplete items to be the same width as the container:*/
			top: 100%;
			left: 0;
			right: 0;
			}
			.autocomplete-items div {
			padding: 10px;
			cursor: pointer;
			background-color: #fff; 
			border-bottom: 1px solid #d4d4d4; 
			}
			.autocomplete-items div:hover {
			/*when hovering an item:*/
			background-color: #e9e9e9; 
			}
			.autocomplete-active {
			/*when navigating through the items using the arrow keys:*/
			background-color: DodgerBlue !important; 
			color: #ffffff; 
			}
		</style>
		<?php if($_GET['p']==="0"){?>
		<script type="text/javascript">
			$(window).on('load',function(){
			    $('#myModal').modal('show');
			});
		</script>
		<?php } if($_GET['w']==="1"){?>
		<script type="text/javascript">
			$(window).on('load',function(){
			    $('#myModaWaiting').modal('show');
			});
		</script>
		<?php } ?>
		<script>
			$(window).on('load',function(){
			
			$('#serPatient').focus();
			
			});
			
			function validateFloatKeyPress(el, evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode;
			var number = el.value.split('.');
			if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
			    return false;
			}
			//just one dot
			if(number.length>1 && charCode == 46){
			     return false;
			}
			//get the carat position
			var caratPos = getSelectionStart(el);
			var dotPos = el.value.indexOf(".");
			if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
			    return false;
			}
			return true;
			}
			
			//thanks: http://javascript.nwbox.com/cursor_position/
			function getSelectionStart(o) {
			if (o.createTextRange) {
			var r = document.selection.createRange().duplicate()
			r.moveEnd('character', o.value.length)
			if (r.text == '') return o.value.length
			return o.value.lastIndexOf(r.text)
			} else return o.selectionStart
			}
		</script>
		<script src="../search/jquery-1.11.1.min.js"></script>
		<script src="../search/jquery-ui.min.js"></script>
		<script src="../search/jquery.select-to-autocomplete.js"></script>
		<script>
			(function($){
			  $(function(){
			    $('#txtref').selectToAutocomplete();
			    
			  });
			})(jQuery);
			function call_login_hospital(){
			  //alert("Logging in.........");
			  var user=document.getElementById('txtref').value;
			  		   
			  if(user==""){
			    alert("Enter investigation name");
			 return false;
			  }
			  
			}
			
		</script>
		<style>
			.ui-autocomplete {
			padding: 10px;
			font-size:12px;
			list-style: none;
			background-color: #fff;
			width: 658px;
			border: 1px solid #B0BECA;
			max-height: 350px;
			overflow-x: hidden;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 658px;
			}
			.ui-autocomplete .ui-menu-item {
			border-top: 1px solid #B0BECA;
			display: block;
			padding: 4px 6px;
			color: #353D44;
			cursor: pointer;
			}
			.ui-autocomplete .ui-menu-item:first-child {
			border-top: none;
			}
			.ui-autocomplete .ui-menu-item.ui-state-focus {
			background-color: #D5E5F4;
			color: #161A1C;
			}
		</style>
		<!--<script src="js/jquery-1.12.4.min.js"></script>
			<script type="text/javascript" src="date-time-picker.min.js"></script>-->
	</head>
	<body >
		<div id="wrapper">
			<?php include_once('sidemenu.php'); ?>
			<div id="page-wrapper" class="gray-bg">
				<?php include_once('header_top.php'); 
					include_once('patient_detail_section.php'); 
					
					?>
				<div class="row m-t">
					<div class="col-lg-2">
						<a href="#" id="visitDetails">
							<div class="widget style1 lazur-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-wheelchair fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">PREVIOUS VISITS</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php if($secretary_id!=1) { ?>  
					<div class="col-lg-2">
						<a href="#" id="medicalHistory">
							<div class="widget style1 blue-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-h-square fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">MEDICAL PROFILE</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2">
						<a href="#" id="latestReports">
							<div class="widget style1 yellow-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-copy fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">MEDICAL REPORTS</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php } ?>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-lg-12 m-b-lg">
							<!-- START VIEW REPORT SECTION -->
							<?php 
								$doc_patient_reports = mysqlSelect("DISTINCT(report_folder) as report_folder","doc_my_patient_reports","patient_id = '".$patient_tab[0]['patient_id']."'","report_folder desc","","","");
								
								?>
							<div class="row white-bg page-heading" id="view-latest-reports">
								<h2><i class="fa fa-copy"></i> View Medical Reports</h2>
								<div class="ibox-content">
									<input type="hidden" name="pat_mobile" id="pat_mobile" value="<?php echo $patient_tab[0]['patient_mob']; ?>" /> 
									<input type="hidden" name="pat_id" id="pat_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" /> 
									<a href="#" title="Click here to share a link with the patients to upload their old reports" class="btn btn-w-m btn-info m-l share_link_report pull-right">Share Link</a>
									<!--<button type="button" id="attachReport" class="btn btn-w-m btn-info pull-right">Attach reports</button>-->
									<div class="row" id="ReportSection">
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
											<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
											<input type="hidden" name="upload_user" value="<?php echo $admin_id; ?>">
											<label class="col-sm-12">Report Title <span class="required">*</span></label>
											<div class="form-group col-lg-12"><input type="text" name="report_title" required="required" class="form-control"></div>
											<label class="col-sm-12"><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
											<div class="form-group col-lg-12">
												<div class="file-loading">
													<input id="file-5" name="file-5[]" class="file" type="file" required="required" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
												</div>
											</div>
											<div class="row" id="image_preview"></div>
											<div class="row">
												<div class="form-group col-lg-12">
													<div class="pull-right">
														<button type="button" id="cancel" class="btn btn-primary m-b ">CANCEL</button>
														<button type="submit" name="addAttachments" class="btn btn-primary m-b m-r">UPLOAD REPORTS</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="feed-activity-list">
									<?php while(list($key_list, $value_list) = each($doc_patient_reports)) 
										{
											$get_reports = mysqlSelect("*","doc_my_patient_reports","report_folder = '".$value_list['report_folder']."'","","","","");
											if($get_reports[0]['user_type']=='1'){
												$username=$patient_tab[0]['patient_name'];
											}
											if($get_reports[0]['user_type']=='2'){
												
												$username=$get_doc_details[0]['ref_name'];
											}
											if($get_reports[0]['user_type']=='3'){
												$get_daignosis = mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id = '".$get_reports[0]['user_id']."'","","","","");
											
												$username=$get_daignosis[0]['diagnosis_name'];
											}
											?>
									<div class="feed-element">
										<a href="#" class="pull-left">
										<img alt="image" class="img-circle" src="../assets/img/anonymous-profile.png">
										</a>
										<div class="media-body ">
											Uploaded by <strong><?php echo $username; ?></strong><br>
											<small class="text-muted"><?php echo date('H:i a',strtotime($get_reports[0]['date_added'])); ?> - <?php echo date('d.m.Y',strtotime($get_reports[0]['date_added'])); ?></small>
											<br><br><?php if(!empty($get_reports[0]['report_title'])){ ?>Report Title: <strong><?php echo $get_reports[0]['report_title']; ?></strong><?php } ?>
											<p>
												<span><i class="fa fa-paperclip"></i> <?php echo COUNT($get_reports); ?> attachments </span>
											</p>
											<ul>
												<?php 
													foreach($get_reports as $attachList){ 
													//Here we need to check file type
													$img_type =  array('gif','png' ,'jpg' ,'jpeg');
													$extractPath = pathinfo($attachList['attachments'], PATHINFO_EXTENSION);
													if(in_array($extractPath,$img_type) ) {
														$imgIcon="patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments'];
													}
													else if($extractPath=="docx"){
															$imgIcon="../assets/images/doc.png";
													}
													else if($extractPath=="pdf" || $extractPath=="PDF"){
														$imgIcon="../assets/images/pdf.png";
													} 
													
													
													if($attachList['report_type']==1){ ?>
												<div class="file-box">
													<div class="file">
														<span class="corner"></span>
														<a href="<?php echo "print-discharge-summary/?id=".md5($attachList['attachments']);?>" target="_blank"  title="Patient Discharge Summary">
															<div class="image">
																<img alt="image" class="img-responsive" src="hms-discharge1.png" width="200">
															</div>
														</a>
														<div class="file-name">
															<small><a href="<?php echo "print-discharge-summary/?id=".md5($attachList['attachments']);?>" class="pull-left" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View Discharge Summary</a> | 
														</div>
														<form method="post" class="pull-left" action="Discharge-Summary">
															<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>"/>
															<input type="hidden" name="discharge_id" value="<?php echo $attachList['attachments']; ?>"/>
															<button type="submit" class="btn btn-danger btn-xs" name="cmdUpdateDischarge"><i class="fa fa-edit"></i> Edit</button>
														</form>
													</div>
												</div>
												<?php } else { ?>
												<div class="file-box">
													<div class="file">
														<a href="#">
														<span class="corner"></span>
														<a href="<?php echo "patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
															<div class="image">
																<img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
															</div>
														</a>
														<div class="file-name">
															<?php echo substr($attachList['attachments'],0,10); ?>
															<br/>
															<small>
																<a href="<?php echo "patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a>
																<!--<a href="https://medisensecrm.com/premium/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&episode_attach=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a>-->
															</small>
															<!--<small class="pull-right"><a href="#" claas="delAttachments" data-report-id="<?php echo md5($attachList['report_id']);?>" data-report-folder="<?php echo $attachList['report_folder'];?>" style="color:red;" title="Delete"><i class="fa fa-trash"></i></a></small>-->
														</div>
														</a>
													</div>
												</div>
												<?php }
													} ?>
											</ul>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>
							<!-- END VIEW REPORT SECTION -->
							<!-- STAR MEDICAL HISTORY SECTION -->
							<div class="row white-bg page-heading" id="medical-history">
								<h2><i class="fa fa-h-square"></i> Medical Profile</h2>
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<div class="col-lg-5">
										<dl>
											<dt>Hypertension:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['hyper_cond']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_hyper" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_hyper">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['hyper_cond']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_hyper">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_hyper"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_hyper">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_hyper">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>
											</dd>
											<br>
											<dt>Smoking:</dt>
											<br> 
											<dd>
												<select class="form-control smokeCondition" data-patient-id="<?php echo md5($patient_id); ?>" name="se_smoking" id="se_smoking">
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
												</select>
											</dd>
											<br>
										</dl>
										<!--    <dt>Drug Abuse</dt><br> <dd><textarea class="form-control" id="drug_abuse"  name="drug_abuse" rows="2"><?php echo $patient_tab[0]['drug_abuse']; ?></textarea></dd><br>
											<dt>Previous Interventions</dt><br> <dd><textarea class="form-control" id="prev_inter"  name="prev_inter" rows="2"><?php echo $patient_tab[0]['prev_inter']; ?></textarea></dd><br>
											<dt>Other Details</dt><br> <dd><textarea class="form-control" id="other_details"  name="other_details" rows="2"><?php echo $patient_tab[0]['other_details']; ?></textarea></dd><br>
											-->
									</div>
									<div class="col-lg-7">
										<dl>
											<dt>Diabetes:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['diabetes_cond']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_diabets" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_diabets">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['diabetes_cond']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_diabets">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo $patient_id ?>" id="inlineRadio2" value="2" name="se_diabets"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_diabets">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_diabets">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>  
											</dd>
											<br>
											<dt>Alcohol:</dt>
											<br> 
											<dd>
												<select class="form-control alcoholCondtion" data-patient-id="<?php echo md5($patient_id); ?>" name="se_alcoholic" name="se_alcoholic">
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
												</select>
											</dd>
											<br>	
										</dl>
										<!--	<dt>Family History</dt><br> <dd><textarea class="form-control" id="family_history"  name="family_history" rows="2"><?php echo $patient_tab[0]['family_history']; ?></textarea></dd><br>
											<dt>Stroke or known neurological issues</dt><br> <dd><textarea class="form-control" id="neuro_issue" name="neuro_issue" rows="2"><?php echo $patient_tab[0]['neuro_issue']; ?></textarea></dd><br>
											<dt>Known kidney issues</dt><br> <dd><textarea class="form-control" id="kidney_issue"  name="kidney_issue" rows="2"><?php echo $patient_tab[0]['kidney_issue']; ?></textarea></dd><br>
											                          -->    
									</div>
								</div>
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<h4>Drug allergy</h4>
									<div class="input-group">
										<input type="text" placeholder="Drug allergy ..." data-patient-id="<?php echo $patient_id; ?>" id="get_allergy" name="searchDrugAllergy" value="" class="form-control input-lg searchAllergy" tabindex="5">
										<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
											</button>
										</div>
									</div>
									<br>
									<div class="input-group">
										<div id="drugAllergyBefore">
											<?php 
												$getAllergyRes= mysqlSelect("*","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and doc_id ='".$patient_tab[0]['doc_id']."' and doc_type='1' and status='0'","","","","");
												if(!empty($getAllergyRes)){
												while(list($key, $value) = each($getAllergyRes)){ 
													echo "<span class='tag label label-primary m-r'>" . $value['generic_name'] . "<a data-role='remove' class='text-white del_allergy m-l' data-drug-allergy-id='".$value['allergy_id']."'>x</a></span>";
												}
												} //end while ?>
										</div>
										<div id="drugAllergyAfter"></div>
									</div>
									<br>
								</div>
								<br>
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<h4>Drug Abuse</h4>
									<div class="input-group">				
										<?php $last_five_drugs = mysqlSelect("drug_abuse_id","doctor_frequent_drug_abuse","doc_id='".$patient_tab[0]['doc_id']."' and doc_type='1'","freq_count DESC","","","5");
											if(COUNT($last_five_drugs)>0) { ?>
										<label>Recently used:  </label>
										<?php 
											while(list($key_drug, $value_drug) = each($last_five_drugs)){
												$getDrugs= mysqlSelect("drug_abuse_id,drug_abuse","drug_abuse_auto","drug_abuse_id='".$value_drug['drug_abuse_id']."'");
											?>
										<a class="btn btn-xs btn-white m-l get_drug_prior" data-drug-abuse-id="<?php echo $getDrugs[0]['drug_abuse_id']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo $getDrugs[0]['drug_abuse']; ?></code></a>
										<?php }
											} ?>
									</div>
									<br>
									<div class="input-group">
										<input type="text" placeholder="Add / Search ..." data-patient-id="<?php echo $patient_id; ?>" id="get_drug_abuse" name="searchDrugAbuse" value="" class="form-control input-lg searchDrugAbuse" tabindex="5">
										<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
											</button>
										</div>
									</div>
									<br>
									<div class="input-group">
										<div id="drugAbuseBefore">
											<?php 
												$getDrugRes= mysqlSelect("b.drug_abuse as drug_abuse,a.drug_active_id as drug_active_id","doc_patient_drug_active as a left join drug_abuse_auto as b on a.drug_abuse_id=b.drug_abuse_id","a.doc_id='".$patient_tab[0]['doc_id']."' and a.patient_id='".$patient_id."' and a.doc_type='1' and a.status='0'","","","","");
												if(!empty($getDrugRes)){
												while(list($key, $value) = each($getDrugRes)){ 
													echo "<span class='tag label label-primary m-r'>" . $value['drug_abuse'] . "<a data-role='remove' class='text-white del_drugs m-l' data-drug-abuse-id='".$value['drug_active_id']."'>x</a></span>";
												}
												} //end while ?>
										</div>
										<div id="drugAbuseAfter"></div>
									</div>
									<br>
								</div>
								<br>
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<h4>Family History</h4>
									<div class="input-group">				
										<?php $last_five_history = mysqlSelect("family_history_id","doctor_frequent_family_history","doc_id='".$patient_tab[0]['doc_id']."' and doc_type='1'","freq_count DESC","","","5");
											if(COUNT($last_five_history)>0) { ?>
										<label>Recently used:  </label>
										<?php 
											while(list($key_history, $value_history) = each($last_five_history)){
												$getHistory= mysqlSelect("family_history_id,family_history","family_history_auto","family_history_id='".$value_history['family_history_id']."'");
											?>
										<a class="btn btn-xs btn-white m-l get_histoy_prior" data-history-id="<?php echo $getHistory[0]['family_history_id']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo $getHistory[0]['family_history']; ?></code></a>
										<?php }
											} ?>
									</div>
									<br>
									<div class="input-group">
										<input type="text" placeholder="Add / Search here..." data-patient-id="<?php echo $patient_id; ?>" id="get_history_abuse" name="searchDrugAbuse" value="" class="form-control input-lg searchDrugAbuse" tabindex="5">
										<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
											</button>
										</div>
									</div>
									<br>
									<div class="input-group">
										<div id="familyHistoryBefore">
											<?php 
												$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","a.doc_id='".$patient_tab[0]['doc_id']."' and a.patient_id='".$patient_id."' and a.doc_type='1' and a.status='0'","","","","");
												
												while(list($key, $value) = each($getHistoryRes)){ 
													echo "<span class='tag label label-primary m-r'>" . $value['family_history'] . "<a data-role='remove' class='text-white del_history m-l' data-history-id='".$value['family_active_id']."'>x</a></span>";
												}
												?>	
										</div>
										<div id="familyHistoryAfter"></div>
									</div>
									<br>
								</div>
								<br>
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<div class="col-lg-6">
										<dl>
											<dt>Previous Interventions</dt>
											<br> 
											<dd><textarea class="form-control prevIntervent" id="prev_inter" data-patient-id="<?php echo md5($patient_id); ?>"  name="prev_inter" rows="2"><?php echo $patient_tab[0]['prev_inter']; ?></textarea></dd>
											<br>
											<dt>Other Details</dt>
											<br> 
											<dd><textarea class="form-control otherDetail" id="other_details" data-patient-id="<?php echo md5($patient_id); ?>" name="other_details" rows="2"><?php echo $patient_tab[0]['other_details']; ?></textarea></dd>
											<br>
										</dl>
									</div>
									<div class="col-lg-6">
										<dl>
											<dt>Stroke or known neurological issues</dt>
											<br> 
											<dd><textarea class="form-control neuroIssue" data-patient-id="<?php echo md5($patient_id); ?>" id="neuro_issue" name="neuro_issue" rows="2"><?php echo $patient_tab[0]['neuro_issue']; ?></textarea></dd>
											<br>
											<dt>Known kidney issues</dt>
											<br> 
											<dd><textarea class="form-control kidneyIssue" id="kidney_issue" data-patient-id="<?php echo md5($patient_id); ?>" name="kidney_issue" rows="2"><?php echo $patient_tab[0]['kidney_issue']; ?></textarea></dd>
											<br>
										</dl>
									</div>
								</div>
								<br>
								<!--<button type="submit" name="updatePatient" class="btn btn-primary pull-right"> UPDATE</button>	-->
							</div>
							<!-- END MEDICAL HISTORY SECTION -->
							<!-- STAR ALL VISIT DETAILS SECTION -->
							<div class="row white-bg page-heading" id="visit-details">
								<h2><i class="fa fa-wheelchair"></i> Previous Visit Details</h2>
								<div class="ibox-content">
									<?php
										if (count($patient_episodes) > 0)
										{ ?>
									<table class="footable table table-stripped toggle-arrow-tiny">
										<thead>
											<tr>
												<th data-toggle="true">VISITS</th>
												<th data-hide="all">Chief Medical Complaint</th>
												<th data-hide="all">Examination</th>
												<th data-hide="all">Diagnosis</th>
												<th data-hide="all">Investigations</th>
												<th data-hide="all">Treatment Advise</th>
												<!-- <th data-hide="all">Reports</th>-->
												<th data-hide="all">Medical Prescriptions</th>
												<th data-hide="all">Next Follow up date</th>
												<th data-hide="all">Patient Note</th>
											</tr>
										</thead>
										<tbody>
											<?php 								
												$patient_episode_count = count($patient_episodes);
												$i=0;
												while (list($patient_episode_key, $patient_episode_val) = each($patient_episodes))
												{
													$visit_count=$patient_episode_count-$i;
												?>
											<tr id="myTableRow<?php echo $patient_episode_val['episode_id']; ?>">
												<td>
													#Visit <?php echo $visit_count." - ".$patient_episode_val['formated_date_time']; ?>
												</td>
												<!-- DISPLAY CHIEF MEDICAL -->
												<td><?php 
													$get_medical_complaint = mysqlSelect("b.symptoms as symptoms","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
													if(!empty($get_medical_complaint)){
													while(list($key_symp, $value_symp) = each($get_medical_complaint)){
													echo $value_symp['symptoms'].", "; 
													} //endif
													} //end while ?><br><br></td>
												<!-- DISPLAY EXAMINATION -->
												<td>
													<?php 
														$get_examination = mysqlSelect("b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
														if(!empty($get_examination)){
															?>
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>Examination</th>
																<th>Result</th>
																<th>Findings</th>
															</tr>
														<thead>
														<tbody>
															<?php 
																while(list($key_exam, $value_exam) = each($get_examination))	
																{  
																?>
															<tr>
																<td>
																	<?php echo $value_exam['examination']; ?>
																</td>
																<td><?php echo $value_exam['exam_result']; ?></td>
																<td><?php echo $value_exam['findings']; ?></td>
															</tr>
															<?php } //end while
																?>
														</tbody>
													</table>
													<?php 
														} //end if ?><br><br>
												</td>
												<!-- DISPLAY DIAGNOSIS -->
												<td>
													<?php 
														$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
														if(!empty($get_diagnosis)){
															while(list($key_diagno, $value_diagno) = each($get_diagnosis)){
															echo $value_diagno['icd_code'].", <br>"; 
															} //endif
														} //end while ?><br><br>
													<?php if(!empty($patient_episode_val['diagnosis_details'])){ ?>
													<p><b>Diagnosis Details:</b> 
														<?php echo $patient_episode_val['diagnosis_details']; ?>
													</p>
													<?php } ?>
												</td>
												<!-- DISPLAY INVESTIGATION -->
												<td>
													<?php $get_cardio_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_tab[0]['patient_id'] ."' and episode_id='". $patient_episode_val['episode_id']."' and department='1'","","","","");
														$get_ophthal_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_tab[0]['patient_id'] ."' and episode_id='". $patient_episode_val['episode_id']."' and department='2'","","","","");
														
														if(!empty($get_cardio_invest) || !empty($get_ophthal_invest))
														{
														?>
													<form method="post" name="frmAddTest" action="my_patient_profile_save.php" >
														<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
														<input type="hidden" name="date_added" value="<?php echo date('Y-m-d',strtotime($patient_episode_val['date_time'])); ?>" />
														<table class="table table-bordered">
															<?php if(COUNT($get_cardio_invest)>0){ ?>
															<thead>
																<tr>
																	<th>Test <?php echo $patient_episode_val['date_time']; ?></th>
																	<th>Normal Value</th>
																	<th>Actual Value</th>
																</tr>
															<thead>
															<tbody>
																<?php 
																	while(list($key_invest, $value_invest) = each($get_cardio_invest))	
																	{  
																	
																	?>
																<tr>
																	<td>
																		<input type="hidden" name="main_test_id[]" value="<?php echo $value_invest['main_test_id']; ?>"/>
																		<input type="hidden" name="investigation_id[]" value="<?php echo $value_invest['pti_id']; ?>"/><?php echo $value_invest['test_name']; ?>
																	</td>
																	<td><?php echo $value_invest['normal_range']; ?></td>
																	<td><input type="text" name="actualVal[]"  value="<?php echo $value_invest['test_actual_value']; ?>" placeholder="" style="width:100px;"></td>
																</tr>
																<?php }
																	?>
															</tbody>
															<?php } if(COUNT($get_ophthal_invest)>0){ ?>
															<thead>
																<tr>
																	<th colspan="4">Ophthal Specific Tests</th>
																</tr>
																<tr>
																	<th>Test</th>
																	<th>Right Eye</th>
																	<th>Left Eye</th>
																</tr>
															<thead>
															<tbody>
																<?php 
																	while(list($key_opinvest, $value_opinvest) = each($get_ophthal_invest))	
																	{  
																	
																	
																	?>
																<tr>
																	<td>
																		<input type="hidden" name="main_test_id[]" value="<?php echo $value_opinvest['main_test_id']; ?>"/>
																		<input type="hidden" name="op_investigation_id[]" value="<?php echo $value_opinvest['pti_id']; ?>"/><?php echo $value_opinvest['test_name']; ?>
																	</td>
																	<td><input type="text" name="lefteye[]"  value="<?php echo $value_opinvest['left_eye']; ?>" placeholder="" style="width:100px;"></td>
																	<td><input type="text" name="righteye[]"  value="<?php echo $value_opinvest['right_eye']; ?>" placeholder="" style="width:100px;"></td>
																</tr>
																<?php }
																	?>
															</tbody>
															<?php } ?>
															<tbody>
																<tr>
																	<td colspan="3"><button type="submit" name="updateInvestigation" id="updateInvestigation" class="btn btn-outline btn-primary pull-right">UPDATE</button></td>
																</tr>
															</tbody>
														</table>
													</form>
													<br>
													<!--<label>Refer to Diagnostic Center</label>-->
													<span id="success"></span>
													<div class="form-group col-md-1">
														<a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new diagnostic center" data-toggle="modal" data-target="#myModal1"><i class="fa fa-plus"></i>
														</a>
														<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content animated bounceInRight">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																		<img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
																		<h4 class="modal-title">Add New Diagnostic</h4>
																	</div>
																	<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddDiagnostic">
																		<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
																		<div class="modal-body">
																			<div class="form-group"><label>Diagnostic Name</label> <input type="text" name="diagno_name" required value="" class="form-control"></div>
																			<div class="form-group"><label>Email</label> <input type="email" name="txtemail" value="" required class="form-control"></div>
																			<div class="form-group"><label>Mobile</label> <input type="text" name="mobile" value="" required class="form-control"></div>
																			<div class="form-group"><label>City</label> <input type="text" name="city" value="" required class="form-control"></div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
																			<button type="submit" name="add_diagno_patient" class="btn btn-primary">Add</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group col-md-9">
														<select data-placeholder="Refer to Diagnostic Center" class="chosen-select diagnoCenter" name="selectDignosticCenter" id="selectDignosticCenter" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" data-episode-id="<?php echo $patient_episode_val['episode_id']; ?>" tabindex="3">
															<option value=""></option>
															<?php $getDiagnostic= mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.doc_id='".$patient_tab[0]['doc_id']."'","b.doc_diagno_id desc","","","");
																foreach($getDiagnostic as $getDiagnosticList){ ?>
															<option value="<?php echo stripslashes($getDiagnosticList['diagnostic_id']);?>" /><?php echo stripslashes($getDiagnosticList['diagnosis_name']).", ".stripslashes($getDiagnosticList['diagnosis_city']);?></option>
															<?php
																}?>
														</select>
													</div>
													<!--<div class="form-group col-md-2"><a href='#'><i class="fa fa-plus"></i>ADD</a></div>-->
													<div class="form-group col-md-2 m-t-30">
														<div class="input-group-btn">
															<button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary">REFER</button>
														</div>
													</div>
													<!--<div class="form-group col-md-12" id="beforeRefer">
														<table>
														<tr><th>Diagnostic Center</th><th>Referred on</th><th>status</th></tr>
														<tr><td></td><td></td><td></td><td></td></tr>
														</table>
														</div>
														<div id="afterRefer"></div>	-->		<br><br><br>
													<?php } 
														else
														{
															echo "NA";
														}?>
												</td>
												<!-- DISPLAY TREATMENT -->
												<td>
													<?php 
														$get_treatment = mysqlSelect("b.treatment as treatment","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
														if(!empty($get_examination)){
														while(list($key_treat, $value_treat) = each($get_treatment)){
														echo $value_treat['treatment'].", "; 
														} //end while
														} //endif 
														else
														{
															echo "NA";
															
														}?><br><br>
													<p><b>Treatment Details:</b> 
														<?php echo $patient_episode_val['treatment_details']; ?>
													</p>
												</td>
												<!--<td>
													<p>
														  <span><i class="fa fa-paperclip"></i> <?php $doc_patient_episode_attachment = mysqlSelect("attach_id,my_patient_id,episode_id,attachments","doc_patient_attachments","episode_id = '". $patient_episode_val['episode_id'] ."' ","","","","");
														echo COUNT($doc_patient_episode_attachment); ?> attachments </span>
														
													</p>
													<ul>
													<?php 
														foreach($doc_patient_episode_attachment as $attachList){ 
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
													-->
												<td>
													<?php
														$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");
														
														if (COUNT($doc_patient_episode_prescriptions) > 0)
														{
															
															if($doc_patient_episode_prescriptions[0]['prescription_template']==0){
														?>	
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>Medicine</th>
																<th>Generic Name</th>
																<th>Frequency</th>
																<th>Timing</th>
																<th>Duration</th>
															</tr>
														</thead>
														<tbody>
															<?php
																while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
																
																{
																	$prescription_timing=mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");
																
																?>
															<tr>
																<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
																<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
																<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
																<td><?php echo $prescription_timing[0]['english'] ?></td>
																<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
															</tr>
															<?php
																}
																?>
														</tbody>
													</table>
													<?php 
														} 
														else if($doc_patient_episode_prescriptions[0]['prescription_template']==1){ ?>
													<table class="table table-bordered">
														<thead>
															<tr>
																<th>S.No.</th>
																<th>Medicine</th>
																<th>Morning</th>
																<th>Afternoon</th>
																<th>Night</th>
																<th>Duration</th>
																<th>Timing</th>
															</tr>
														</thead>
														<?php
															while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
															
															{
																$prescription_timing=mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");
																$sl_num = $patient_episode_prescription_key+1	
															?>
														<tr>
															<td rowspan="2" style="text-align:center;"><?php echo $sl_num; ?></td>
															<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_morning'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_noon'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_night'] ?></td>
															<td><?php echo $patient_episode_prescription_val['duration']." ".$patient_episode_prescription_val['med_duration_type']; ?></td>
															<td><?php echo $prescription_timing[0]['english'] ?></td>
														</tr>
														<tr>
															<td><?php echo "<b>Generic:</b> ".$patient_episode_prescription_val['prescription_generic_name']; ?></td>
															<td colspan="5"> <?php if(!empty($patient_episode_prescription_val['prescription_instruction'])){ echo "<b>Instructions:</b> ".$patient_episode_prescription_val['prescription_instruction'];} ?></td>
														</tr>
														<?php
															}
															?>
														</tbody>
													</table>
													<?php } ?>
													<br>
													<!--<label>Refer to Pharmacy</label>-->
													<div class="form-group col-md-1">
														<a href="#" class="btn btn-default btn-circle" title="Click here to add new pharmacy" type="button" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus"></i>
														</a>
														<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content animated bounceInRight">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																		<img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
																		<h4 class="modal-title">Add New Pharmacy</h4>
																	</div>
																	<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPharmacy" >
																		<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
																		<div class="modal-body">
																			<div class="form-group"><label>Pharmacy Name</label> <input type="text" name="pharma_name" required value="" class="form-control"></div>
																			<div class="form-group"><label>Email</label> <input type="email" name="txtemail" value="" required class="form-control"></div>
																			<div class="form-group"><label>Mobile</label> <input type="text" name="mobile" value="" required class="form-control"></div>
																			<div class="form-group"><label>City</label> <input type="text" name="city" value="" required class="form-control"></div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
																			<button type="submit" name="add_pharma_patient" class="btn btn-primary">Add</button>
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group col-md-9">
														<select data-placeholder="Refer to Pharmacy.." class="chosen-select" name="selectPharma" id="selectPharma" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" data-episode-id="<?php echo $patient_episode_val['episode_id']; ?>" tabindex="3" onchange="">
															<option value=""></option>
															<?php $getPharma= mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.doc_id='".$patient_tab[0]['doc_id']."'","b.doc_pharma_id desc","","","");
																foreach($getPharma as $getPharmaList){ ?>
															<option value="<?php echo stripslashes($getPharmaList['pharma_id']);?>" /><?php echo stripslashes($getPharmaList['pharma_name']).", ".stripslashes($getPharmaList['pharma_city']);?></option>
															<?php
																}?>
														</select>
													</div>
													<div class="form-group col-md-2 m-t-30">
														<div class="input-group-btn">
															<button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary">REFER</button>
															<i class="fa fa-plus"></i>
															</button>
														</div>
													</div>
													<br><br><br><br>
													<?php } ?>
												</td>
												<td ><?php if(!empty($patient_episode_val['next_followup_date'])){ echo "<font style=color:red;font-weight:bold;>".date('d-M-Y',strtotime($patient_episode_val['next_followup_date']))."</font>"; } ?>
												</td>
												<td ><?php if(!empty($patient_episode_val['patientNote'])){ echo nl2br($patient_episode_val['patientNote']); } ?>
												</td>
												<td class="text-right">
													<div class="btn-group">
														<!--<a href="#" class="delEpisode" data-episode-id="<?php echo md5($patient_episode_val['episode_id']); ?>" data-print-content="<?php echo "Visit ".$visit_count; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-trash"></i> Delete</a>-->
														<a href="print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-print"></i> Print</a>
														<!--<a href="My-Patient-Details?p=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>&visit=<?php echo $visit_count; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-edit"></i> Edit</a>
															<a href="#" class="btn btn-white btn-bitbucket pull-right" id="delEpisode" data-episode-id="<?php echo md5($patient_episode_val['episode_id']); ?>" data-row-id="<?php echo $patient_episode_val['episode_id']; ?>" data-print-content="<?php echo "Visit ".$visit_count; ?>" ><i class="fa fa-trash"></i> Delete</a>
															<!--<a href="print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>&ref=true" class="btn btn-white btn-bitbucket pull-right" ><i class="fa fa-share"></i> Refer</a>
															<button class="btn-white btn btn-xs">View</button>
															    <button class="btn-white btn btn-xs">Edit</button>-->
													</div>
												</td>
											</tr>
											<?php   
												$i++;
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
				<?php include_once('footer.php'); ?>
				<div class="small-chat-box fadeInRight animated">
					<div class="heading" draggable="true" style="font-size:13px;">
						<i class="fa fa-sticky-note-o"></i>
						Internal notes
					</div>
					<div class="content">
						<form method="POST">
							<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
							<span id="comment_message"></span>
							<br />
							<div id="display_comment"></div>
						</form>
					</div>
					<div class="form-chat">
						<form method="POST" id="comment_form">
							<div class="input-group input-group-sm">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
								<input type="text" name="comment_content" id="comment_content" class="form-control">
								<span class="input-group-btn"> <input type="submit" name="submit" id="submit" class="btn btn-primary" value="ADD"/> </span>
							</div>
						</form>
					</div>
				</div>
				<div id="small-chat">
					<?php
						$commentsCount= mysqlSelect("*","patient_internal_notes","doc_id='".$patient_tab[0]['doc_id']."' and patient_id='".$patient_tab[0]['patient_id']."'","","","","");
						if(COUNT($commentsCount)>0){
						?>
					<span class="badge badge-warning pull-right"><?= COUNT($commentsCount);?></span><?php } ?>
					<a class="open-small-chat">
					<i class="fa fa-sticky-note-o" style="font-size:13px;"></i>
					</a>
				</div>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
				<script type="text/javascript">
					function getPatientDet(srchText){
					   var params     = srchText.split("-");
					if(!isNaN(params[0])){
					document.frmchangePatient.cmdchangePatient.value="submit";
					document.frmchangePatient.slct_valPat.value=srchText;
					document.frmchangePatient.submit();
					}		
					}
					
					$(document).ready(function () {
					
					$('.open-small-chat').click(function(){
					 $(this).find('i').toggleClass('fa-sticky-note-o fa-comments fa-times');
					});
					});
					
					$(window).on('keydown',function(event)
					 {
					 if(event.keyCode==123)
					 {
					     return false;
					 }
					 else if(event.ctrlKey && event.shiftKey && event.keyCode==73)
					 {
					     return false;  //Prevent from ctrl+shift+i
					 }
					 else if(event.ctrlKey && event.keyCode==73)
					 {
					    return false;  //Prevent from ctrl+shift+i
					 }
					else if(event.ctrlKey && event.keyCode==85)
					 {
					    return false;  //Prevent from ctrl+u
					 }
					});
					
					$(document).on("contextmenu",function(e)
					{
					
					e.preventDefault();
					});
				</script>
			</div>
		</div>
		<!-- Sweet alert -->
		<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
		<script src="../assets/js/jquery-3.1.1.min.js"></script>
		<script src="../assets/js/bootstrap.min.js"></script>
		<script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- slick carousel-->
		<script src="../assets/js/plugins/slick/slick.min.js"></script>
		<!-- Additional style only for demo purpose -->
		<style>
			.slick_demo_2 .ibox-content {
			margin: 0 10px;
			}
		</style>
		<script>
			$(document).ready(function(){
			
			
			    $('.slick_demo_1').slick({
			        dots: true
			    });
			
			    $('.slick_demo_2').slick({
			        infinite: true,
			        slidesToShow: 3,
			        slidesToScroll: 1,
			        centerMode: true,
			        responsive: [
			            {
			                breakpoint: 1024,
			                settings: {
			                    slidesToShow: 3,
			                    slidesToScroll: 3,
			                    infinite: true,
			                    dots: true
			                }
			            },
			            {
			                breakpoint: 600,
			                settings: {
			                    slidesToShow: 2,
			                    slidesToScroll: 2
			                }
			            },
			            {
			                breakpoint: 480,
			                settings: {
			                    slidesToShow: 1,
			                    slidesToScroll: 1
			                }
			            }
			        ]
			    });
			
			    $('.slick_demo_3').slick({
			autoplay:true,
			autoplaySpeed:3500,
			        infinite: true,
			        speed: 500,
			        fade: true,
			        cssEase: 'linear',
			        adaptiveHeight: true
			    });
			$('.slick-next').hide();
			$('.slick-prev').hide();
			});
			
		</script>
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
		<!-- Toastr -->
		<script src="../assets/js/plugins/toastr/toastr.min.js"></script>
		<!-- Tags Input -->
		<script src="../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
		<script>
			$(document).ready(function(){
			
			    $('.tagsinput1').tagsinput({
			        tagClass: 'label label-primary'
			    });
			
			   
			});
			$(document).ready(function(){
			
			    $('.tagsinput').tagsinput({
			        tagClass: 'label label-primary'
			    });
			
			   
			});
			
			
		</script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<!-- Custom Theme Scripts -->
		<script src="../assets/js/custom.min.js"></script>
		<!-- iCheck -->
		<script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
		<script>
			$( document ).ready(function() {
				//var prescription_seq = parseInt('<?php echo $prescription_seq ?>');
			
				function addPrescriptionTr() {
					var prescription_seq = parseInt($('#hid_prescription_seq').val());
			
					prescription_seq = (prescription_seq + 1);
					var new_prescription_tr = '<tr class="link1" id="prescription_del_'+ prescription_seq +'_row">';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="form-control tagName" disabled name="prescription_trade_name['+ prescription_seq +']" id="prescription_trade_name_'+ prescription_seq +'" placeholder="Trade" style="width:310px;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="form-control genericName" disabled name="prescription_generic_name['+ prescription_seq +']" id="prescription_generic_name_'+ prescription_seq +'" placeholder="Generic" style="width:310px;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="form-control frequency" disabled name="prescription_frequency['+ prescription_seq +']" id="prescription_frequency_'+ prescription_seq +'" placeholder="Freq" style="width:70px;">';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<select class="form-control" name="slctTiming" disabled style="width:160px;"><?php while(list($key_lng, $value_lng) = each($get_Timing)){ ?><option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option><?php } ?></select>';
						new_prescription_tr +=  '</td>';
						new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<input type="text" class="form-control duration" disabled  name="prescription_duration['+ prescription_seq +']" id="prescription_duration_'+ prescription_seq +'" placeholder="Duration" style="width:90px;">';
						new_prescription_tr +=  '</td>';
					
						/*new_prescription_tr +=  '<td class="fields">';
							new_prescription_tr +=  '<a href="javascript:void(0)"><span class="label label-danger">Delete</span></a>';
						new_prescription_tr +=  '</td>';*/
					new_prescription_tr +=  '</tr>';
					
					var new_prescription_tr_theme2 = '<tr class="link1" id="prescription_del_'+ prescription_seq +'_row">';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<input type="text" class="form-control tagName" disabled name="prescription_trade_name['+ prescription_seq +']" id="prescription_trade_name_'+ prescription_seq +'" placeholder="Trade" style="width:100%;">';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<select class="form-control slctFreqMorning" name="slctFreqMorning" style="width:70;"><option value="0" selected >0</option><option value="0.5" >0.5</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="2.5 ml" >2.5 ml</option><option value="5 ml" >5 ml</option><option value="10 ml" >10 ml</option><option value="20 ml" >20 ml</option><option value="30 ml" >30 ml</option></select>';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<select class="form-control slctFreqAfternoon" name="slctFreqAfternoon" style="width:70;"><option value="0" selected >0</option><option value="0.5" >0.5</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="2.5 ml" >2.5 ml</option><option value="5 ml" >5 ml</option><option value="10 ml" >10 ml</option><option value="20 ml" >20 ml</option><option value="30 ml" >30 ml</option></select>';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<select class="form-control slctFreqNight" name="slctFreqNight" style="width:70;"><option value="0" selected >0</option><option value="0.5" >0.5</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option><option value="2.5 ml" >2.5 ml</option><option value="5 ml" >5 ml</option><option value="10 ml" >10 ml</option><option value="20 ml" >20 ml</option><option value="30 ml" >30 ml</option></select>';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<select class="form-control duration" name="prescription_duration[]" data-episode-id="0"  data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left; width:70px;"><option value="1">1</option><?php for($i=2;$i<=10;$i++){ ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?></select><select class="form-control duration_type" name="slctDurationType[]" style="width:130px;"><option value="Day" >Day</option><option value="Week" >Week</option><option value="Month" >Month</option></select>';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
							new_prescription_tr_theme2 +=  '<select class="form-control medtiming" name="slctTiming"  style="width:100%;"><option value="0">Select</option><option value="5">Before food</option><option value="6">After food</option></select>';
						new_prescription_tr_theme2 +=  '</td>';
					
						new_prescription_tr_theme2 +=  '</tr>';
						new_prescription_tr_theme2 +=  '<tr class="link1" id="prescription_del_'+ prescription_seq +'_row">';
						new_prescription_tr_theme2 +=  '<td class="fields" style="background-color:#9ea8bd;border:none;">';
						new_prescription_tr_theme2 +=  '<input type="text" class="form-control genericName" disabled name="prescription_generic_name['+ prescription_seq +']" id="prescription_generic_name_'+ prescription_seq +'" placeholder="Generic" style="width:100%;">';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '<td class="fields" colspan="5" style="background-color:#9ea8bd;border:none;">';
						new_prescription_tr_theme2 +=  '<input type="text" class="form-control genericName" disabled name="prescription_generic_name['+ prescription_seq +']" id="prescription_generic_name_'+ prescription_seq +'" placeholder="Instructions" style="width:100%;">';
						new_prescription_tr_theme2 +=  '</td>';
						new_prescription_tr_theme2 +=  '</tr>';
			
					$('#hid_prescription_seq').val(prescription_seq);
			
					$( "#prescription-template1" ).append( new_prescription_tr );
					$( "#prescription-template2" ).append( new_prescription_tr_theme2 );
					
			
					
			
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
					var templateName = [];
					
					$("#sympBefore span").each(function() {
					    templateName.push($(this).contents().filter(function() {
						return this.nodeType == 3;
					    })[0].nodeValue.trim());
					});
					$("#template_name").val(templateName.join("-"));
					$("#template_name").toggle();
				});
				
				$('#chkInvestSaveTemplate').click(function() {
					$("#invest_template_name").toggle();
				});
				
				$('#chkExamSaveTemplate').click(function() {
					$("#exam_template_name").toggle();
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
			
			$('#dateadded1').datepicker({
			        keyboardNavigation: false,
			        forceParse: false,
			        calendarWeeks: true,
			        autoclose: true
			    });
			$('#dateadded2').datepicker({
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
		<!-- Typehead -->
		<script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>
		<script>
			$(document).ready(function(){
			<?php 
				$get_PatientDetails = mysqlSelect("e.patient_id,e.patient_name,e.patient_mob","doc_my_patient as e inner join referal as a on a.ref_id=e.doc_id inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","","","","");
														
				?>
			    $('.typeahead_1').typeahead({
			       source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
			    });
			     
			$('#comment_form').on('submit', function(event){
			event.preventDefault();
			var form_data = $(this).serialize();
			$.ajax({
			url:"add_internal_note.php",
			method:"POST",
			data:form_data,
			dataType:"JSON",
			success:function(data)
			{
			if(data.error != '')
			{
			$('#comment_form')[0].reset();
			$('#comment_message').html(data.error);
			
			load_comment();
			}
			}
			})
			});
			
			load_comment();
			
			function load_comment()
			{
			
			$.ajax({
			url:"fetch_internal_note.php",
			method:"POST",
			data:'patient_id=<?php echo $patient_tab[0]['patient_id']; ?>',
			success:function(data)
			{
			$('#display_comment').html(data);
			}
			})
			}
			});
			
		</script>
		<script>
			function autocomplete(inp, arr) {
			  /*the autocomplete function takes two arguments,
			  the text field element and an array of possible autocompleted values:*/
			  var currentFocus;
			  /*execute a function when someone writes in the text field:*/
			  inp.addEventListener("input", function(e) {
			      var a, b, i, val = this.value;
			      /*close any already open lists of autocompleted values*/
			      closeAllLists();
			      if (!val) { return false;}
			      currentFocus = -1;
			      /*create a DIV element that will contain the items (values):*/
			      a = document.createElement("DIV");
			      a.setAttribute("id", this.id + "autocomplete-list");
			      a.setAttribute("class", "autocomplete-items");
			      /*append the DIV element as a child of the autocomplete container:*/
			      this.parentNode.appendChild(a);
			      /*for each item in the array...*/
			      for (i = 0; i < arr.length; i++) {
			        /*check if the item starts with the same letters as the text field value:*/
			        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			          /*create a DIV element for each matching element:*/
			          b = document.createElement("DIV");
			          /*make the matching letters bold:*/
			          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			          b.innerHTML += arr[i].substr(val.length);
			          /*insert a input field that will hold the current array item's value:*/
			          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			          /*execute a function when someone clicks on the item value (DIV element):*/
			          b.addEventListener("click", function(e) {
			              /*insert the value for the autocomplete text field:*/
			              inp.value = this.getElementsByTagName("input")[0].value;
			              /*close the list of autocompleted values,
			              (or any other open lists of autocompleted values:*/
			              closeAllLists();
			          });
			          a.appendChild(b);
			        }
			      }
			  });
			  /*execute a function presses a key on the keyboard:*/
			  inp.addEventListener("keydown", function(e) {
			      var x = document.getElementById(this.id + "autocomplete-list");
			      if (x) x = x.getElementsByTagName("div");
			      if (e.keyCode == 40) {
			        /*If the arrow DOWN key is pressed,
			        increase the currentFocus variable:*/
			        currentFocus++;
			        /*and and make the current item more visible:*/
			        addActive(x);
			      } else if (e.keyCode == 38) { //up
			        /*If the arrow UP key is pressed,
			        decrease the currentFocus variable:*/
			        currentFocus--;
			        /*and and make the current item more visible:*/
			        addActive(x);
			      } else if (e.keyCode == 13) {
			        /*If the ENTER key is pressed, prevent the form from being submitted,*/
			        e.preventDefault();
			        if (currentFocus > -1) {
			          /*and simulate a click on the "active" item:*/
			          if (x) x[currentFocus].click();
			        }
			      }
			  });
			  function addActive(x) {
			    /*a function to classify an item as "active":*/
			    if (!x) return false;
			    /*start by removing the "active" class on all items:*/
			    removeActive(x);
			    if (currentFocus >= x.length) currentFocus = 0;
			    if (currentFocus < 0) currentFocus = (x.length - 1);
			    /*add class "autocomplete-active":*/
			    x[currentFocus].classList.add("autocomplete-active");
			  }
			  function removeActive(x) {
			    /*a function to remove the "active" class from all autocomplete items:*/
			    for (var i = 0; i < x.length; i++) {
			      x[i].classList.remove("autocomplete-active");
			    }
			  }
			  function closeAllLists(elmnt) {
			    /*close all autocomplete lists in the document,
			    except the one passed as an argument:*/
			    var x = document.getElementsByClassName("autocomplete-items");
			    for (var i = 0; i < x.length; i++) {
			      if (elmnt != x[i] && elmnt != inp) {
			        x[i].parentNode.removeChild(x[i]);
			      }
			    }
			  }
			  /*execute a function when someone clicks in the document:*/
			  document.addEventListener("click", function (e) {
			      closeAllLists(e.target);
			      });
			}
			
			
		</script>
	</body>
	<script src="js/symptoms.js"></script>
</html>
<script type="text/javascript">
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
	
	
	
	
</script>