<?php
	ob_start();
	error_reporting(1); 
	session_start();
	
	$admin_id 		= $_SESSION['user_id'];
	$secretary_id 	= $_SESSION['secretary_id'];
	$secretary_name = $_SESSION['user_name'];
	//Get the page name 
	//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
	include('functions.php');
	
	if(empty($admin_id))
	{
		header("Location:index.php");
	}
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('d-m-Y',strtotime($cur_Date) + (24*3600*$add_days));
	
	$cur_Time=date('H:i:s',strtotime($Cur_Date));
		
	require_once("../classes/querymaker.class.php");
	//$objQuery = new CLSQueryMaker();
	//echo $_POST['save_patient_edit'];
		if(!isset($_POST['save_patient_edit']))
		{ 
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
		}	
		//$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");

		$patient_tab = mysqlSelect("a.patient_name as patient_name,a.patient_id as patient_id,a.patient_gender as patient_gen,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.transaction_id as transaction_id,b.doc_agora_link as doc_agora_link,a.patient_dob as DOB ,b.pat_bp as pat_bp,b.pat_asthama as pat_asthama,b.pat_epilepsy as pat_epilepsy,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.pat_cholestrole as pat_cholestrole,b.pat_thyroid as thyroid ,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue ,b.address as patient_addrs,b.city as patient_loc,b.state as pat_state ,a.patient_mobile as patient_mob,a.patient_email as patient_email ","patients_appointment as a inner join  patients_transactions as b on a.patient_id = b.patient_id","md5(a.patient_id)='".$_GET['p']."'","","","","");
		
		$_SESSION['patient_id']=$patient_tab[0]['patient_id'];
		
		if($patient_tab[0]['patient_gen']=="1")
		{
			$gender="Male";
		}
		else if($patient_tab[0]['patient_gen']=="2")
		{
			$gender="Female";
		}
	
		if($patient_tab[0]['hyper_cond']=="2")
		{
			$hyperStatus="No";
		}
		else if($patient_tab[0]['hyper_cond']=="1")
		{
			$hyperStatus="Yes";
		}
		if($patient_tab[0]['diabetes_cond']=="2")
		{
			$diabetesStatus="No";
		}
		else if($patient_tab[0]['diabetes_cond']=="1")
		{
			$diabetesStatus="Yes";
		}
		
		
	
		$patient_id = $patient_tab[0]['patient_id'];
	
	 
		$get_TrendAnalysisDate = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_medicineGivenDate = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisPPCount = mysqlSelect("bp_beforefood_count","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisPPAfterCount = mysqlSelect("bp_afterfood_count","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisSystolic = mysqlSelect("systolic","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisDiastolic = mysqlSelect("diastolic","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisHbA1c = mysqlSelect("HbA1c","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisHDL  = mysqlSelect("HDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisVLDL =	mysqlSelect("VLDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisLDL = mysqlSelect("LDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisTriglyceride = mysqlSelect("triglyceride","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
		
		$get_TrendAnalysisCholesterol = mysqlSelect("cholesterol","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","date_added desc","","","0,8");
								
		$get_doc_details = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
		
		$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");
		
		$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		$_SESSION['prescription_template'] = $checkSetting[0]['prescription_template'];
		
		$getChatValue1 =mysqlSelect("MAX(growth_date) as growth_date,AVG(head_circum) as head_circum,AVG(weight) as weight,AVG(height) as height","growth_chart","md5(child_id)='".$_GET['p']."'","gc_id asc","YEAR(growth_date)+MONTH(growth_date)","","");
	
		if(isset($_POST['cmdchangePatient']))
		{
			$params     = explode("-", $_POST['slct_valPat']);
			if($params[0]!=0)
			{
				$patientid = $params[0];
				header("Location:".$_SESSION['EMR_URL'].md5($patientid));
			}
			else
			{
				$patientid = "0";
					
				header("Location:".$_SESSION['EMR_URL'].$patientid."&n=".$params[0]);
			}
		}
		$getDocSpec = mysqlSelect("a.spec_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."' and b.doc_type='1'","","","","");
		
		$child_tab  = mysqlSelect("*","child_tab","md5(patient_id)='".$_GET['p']."'","","","","");
		$parent_tab = mysqlSelect("*","parents_tab","parent_id='".$child_tab[0]['parent_id']."'","","","","");			
																														 
	?>
<!DOCTYPE html>
<html>
	<head>
	
		<!--script src="https://www.gstatic.com/firebasejs/9.0.2/firebase-app.js"></script>
		<script src="https://www.gstatic.com/firebasejs/9.0.2/firebase-firestore.js"></script>
		<!--link rel="stylesheet" href="progress_styles/site.css"><!-- PROGRESS BAR LINK DUMMY-->
		<!--link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cutive+Mono|Open+Sans:300,400&display=swap">
		<link rel="stylesheet" href="progress_styles/progress-tracker.css"-->
		<!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
		
		<!-- Script for tracking the order
		<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,600,700' rel='stylesheet' type='text/css'>
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->
		
		
		<link rel="stylesheet" href="progress_styles/progress-tracker.css">
		<link rel="stylesheet" href="progress_styles/site.css">
		 
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
		<script src="js/Chart.bundle.js"></script>
		<script src="js/utils.js"></script>
		<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
		<script type="text/javascript" src="fusion_chart/js/fusioncharts.js"></script>
		<script type="text/javascript" src="fusion_chart/js/themes/fusioncharts.theme.fint.js"></script>
		
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
				$("#view-video_call").hide();
				$("#visit-tracker").hide();
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
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<link rel="stylesheet" href="css/jquery-ui.css">
		<script type="text/javascript">
			$(function() 
			{
			 $( "#coding_language" ).autocomplete({
			  source: 'get_icd.php'
			 });
			 
			 $( "#coding_language1" ).autocomplete({
			  source: 'get_icd.php'
			 });
			
			 $( "#specialization" ).autocomplete({
			  source: 'get_specialization.php'
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
			 $( "#get_invest_test" ).autocomplete({
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
			
			function validateFloatKeyPress(el, evt) 
			{
				var charCode = (evt.which) ? evt.which : event.keyCode;
				var number = el.value.split('.');
				if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) 
				{
					return false;
				}
				//just one dot
				if(number.length>1 && charCode == 46)
				{
					 return false;
				}
				//get the carat position
				var caratPos = getSelectionStart(el);
				var dotPos = el.value.indexOf(".");
				if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1))
				{
					return false;
				}
				return true;
			}
			
			//thanks: http://javascript.nwbox.com/cursor_position/
			function getSelectionStart(o)
			{
				if (o.createTextRange) 
				{
					var r = document.selection.createRange().duplicate()
					r.moveEnd('character', o.value.length)
					if (r.text == '') return o.value.length
					return o.value.lastIndexOf(r.text)
				} 
				else return o.selectionStart
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
			  
			  var user=document.getElementById('txtref').value;
			  		   
			  if(user=="")
			  {
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
		<script src="js/jquery-1.12.4.min.js"></script>
		<script type="text/javascript" src="date-time-picker.min.js"></script>
		<?php include('ChartSrc.php'); ?>  
		
		<script>
			$(document).ready(function(){  
				setInterval(function(){ 
				//alert("Hi");			
					$("VC_request").load("VC_Request_list.php");
				}, 5000);
			});
		</script>
		
	</head>
	<body>
		<div id="wrapper">
			<?php include_once('sidemenu.php'); ?>
			<div id="page-wrapper" class="gray-bg">
				<?php 
					include_once('header_top.php'); 
					if($getDocSpec[0]['spec_id']=="32")
					{
						include_once('Child-EMR/patient_detail_section.php'); 	
					}
					else
					{
						include_once('patient_detail_section.php'); 
					}
				?>	
				<div class="row m-t">
					<div class="col-lg-2">
						<a href="My-Patient-Details?p=<?php echo $_GET['p']; ?>" id="addvisitDetails">
							<div class="widget style1 navy-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-hospital-o fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">ADD VISIT DETAILS</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
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
						<a href="#" id="trendAnalysis">
							<div class="widget style1 red-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-line-chart fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">TREND ANALYSIS</h3>
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
					<div class="col-lg-2">
						<a href="#" id="visitTracker">
							<div class="widget style1 navy-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-calendar fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">APPOINTMENT TRACKING</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php if($admin_id == '2031' || $admin_id == '3504' || $admin_id == '3058' || $admin_id == '178') { ?>
					<div class="col-lg-2">
						<a href="#" id="videoCall">
							<div class="widget style1 lazur-bg">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-video-camera fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">VIDEO CALL / CHAT</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php } ?>
					<?php  if($getDocSpec[0]['spec_id']=="32"){ ?>
					<div class="col-lg-2">
						<a href="#" id="growthDtl">
							<div class="widget style1" style="background-color: #ff6384;color: #ffffff;">
								<div class="row vertical-align" style="padding-bottom: 10px;">
									<div class="col-xs-3">
										<i class="fa fa-child fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">GROWTH</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2">
						<a href="#" id="vaccineDtl">
							<div class="widget style1" style="background-color: #d8bfd8;color: #ffffff;">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-eyedropper fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">VACCINE</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-lg-2">
						<a href="#" id="vitalDtl">
							<div class="widget style1" style="background-color: #9966ff;color: #ffffff;">
								<div class="row vertical-align">
									<div class="col-xs-3">
										<i class="fa fa-stethoscope fa-2x"></i>
									</div>
									<div class="col-xs-9 text-left">
										<h3 class="font-bold">VITAL</h3>
									</div>
								</div>
							</div>
						</a>
					</div>
					<?php } ?>	  
					<?php } ?>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<?php if($_GET['response']=="success"){ ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">??</button>
							<strong>Patient record has been updated successfully </strong>
						</div>
						<?php } else if($_GET['response']=="episode-created"){ ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">??</button>
							<strong>Patient visit details has been added successfully </strong>
						</div>
						<?php } else if($_GET['response']=="update-investigation"){ ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">??</button>
							<strong>Patient investigation updated successfully </strong>
						</div>
						<?php } else if($_GET['response']=="reports-attached"){ ?>
						<div class="alert alert-success alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">??</button>
							<strong>Reports has been attached successfully </strong>
						</div>
						<?php } else if($_GET['response']=="diagnostic-exists"){ ?>
						<div class="alert alert-danger alert-dismissable">
							<button aria-hidden="true" data-dismiss="alert" class="close" type="button">??</button>
							<strong>This phone number or email already exists.</strong>
						</div>
						<?php }?>
						<div class="col-lg-12 m-b-lg">
							<?php if(isset($_GET['p']) && !isset($_GET['episode'])) { ?>

							<div class="row white-bg page-heading" id="add-visit-dtails">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
									<input type="hidden" name="patient_name" value="<?php echo $patient_tab[0]['patient_name']; ?>">
									<input type="hidden" name="prescription_template" value="<?php echo $checkSetting[0]['prescription_template']; ?>">
									<input type="hidden" name="appnt_trans_id" value="<?php echo $_GET['TID'] ?>">
									<h2 class="pull-left"><i class="fa fa-hospital-o"></i> Add Patient Visit Details </h2>
									<div class="col-lg-3 pull-right m-t">
										<dl>
											<dd>
												<div class="pull-left m-r input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="J-demo-02" name="dateadded2" type="text" placeholder="YYYY-MM-DD" value="<?php echo $Cur_Date;?>" class="form-control" />
												</div>
											</dd>
											<br>
										</dl>
										<script type="text/javascript">
											$('#J-demo-02').dateTimePicker({
											    mode: 'dateTime'
											});
										</script>
									</div>
									<!--Section Starts-->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['medical_complaint']==1 && $secretary_id==1)){include_once('get_chief_medical_comp_section.php');  }  ?>
									<!--Section Ends-->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['examination']==1 && $secretary_id==1)){ ?>
									<!--Section Starts-->
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<h4>Examination</h4>
										<div class="input-group">
											<?php

											$last_five_examination = mysqlSelect("b.examination_id as examination_id,b.examination as examination","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$admin_id."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","a.freq_count DESC","","","5");
												
												$exam_templates = mysqlSelect("*","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1'","exam_template_id desc","","","10");
												
												if(COUNT($exam_templates)>0) {
												?>
											<div class="input-group">				
												<label>Saved Template:  </label><a id="editTempExam" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Edit <i class="fa fa-pencil-square-o"></i></a><a id="cancelTempExam" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>"> Cancel </a>
												<span id="beforeTempEdit">
												<?php
													while(list($key_examtemp, $value_examtemp) = each($exam_templates)){
													?>
												<a class="btn btn-xs btn-white m-l exam_load_template" title="<?php echo $value_examtemp['template_name']; ?>" data-exam-template-id="<?php echo md5($value_examtemp['exam_template_id']);?>" data-edit-status="0" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" ><code> <?php echo substr($value_examtemp['template_name'],0,10); ?></code></a>
												<?php } ?>
												</span>
												<span id="afterTempEdit"></span>
											</div>
											<br>
											<?php }
												if(COUNT($last_five_examination)>0) { ?>
											<label>Recently used:  </label>
											<?php 
												while(list($key_exam, $value_exam) = each($last_five_examination)){
													
												?>
											<a class="btn btn-xs btn-white m-l get_examination_res_prior" data-examination_id="<?php echo $value_exam['examination_id']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo $value_exam['examination']; ?></code></a>
											<?php }
												} ?>
										</div>
										<br>
										<div class="input-group">
											<input type="text" placeholder="Add / Search examination here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" id="get_examination_res" name="srchExam" value="" class="form-control input-lg searchExamination" tabindex="3">
											<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
												</button>
											</div>
										</div>
										<br>
										<div class="input-group">
											<div id="dispExamination"></div>
											<?php
												$getTemplate= mysqlSelect("exam_template_id","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1' and default_visible='1'","","","","");
														
												if(count($getTemplate)>0)
												{
													while(list($key,$value) = each($getTemplate))
														{		
															$getTemplateDetails= mysqlSelect("*","doc_patient_episode_examination_template_details","exam_template_id='".$value['exam_template_id']."'","","","","");
															while(list($key_det,$value_det) = each($getTemplateDetails))
															{
																$arrFileds = array();
																$arrValues = array();
																
																$arrFileds[]='examination';
																$arrValues[]=$value_det['examination'];
																
																$arrFileds[]='exam_result';
																$arrValues[]=$value_det['exam_result'];
																
																$arrFileds[]='findings';
																$arrValues[]=$value_det['findings'];
																					
																$arrFileds[]='patient_id';
																$arrValues[]=$patient_tab[0]['patient_id'];
																
																$arrFileds[]='doc_id';
																$arrValues[]=$admin_id;
																
																$arrFileds[]='doc_type';
																$arrValues[]="1";
																
																$arrFileds[]='status';
																$arrValues[]="1";
																
																$insert_exam_value=mysqlInsert('doc_patient_examination_active',$arrFileds,$arrValues);
															}
															
														}
												?>
											<div id="dispTempExamination">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Examination</th>
															<th>Result</th>
															<th>Finding</th>
															<th>Delete</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<?php 
																$getExamination= mysqlSelect("b.examination as examination,a.examination_id as examination_id,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.doc_id='".$admin_id."' and a.patient_id='".$patient_tab[0]['patient_id']."' and a.doc_type='1' and a.status='1'","a.examination_id asc","","","");
																
																foreach($getExamination as $getExaminationList){ ?>
														<tr id="del_examination_row<?php echo $getExaminationList['examination_id'];?>">
															<td><?php echo $getExaminationList['examination']; ?></td>
															<td>
																<select class="form-control exam_res" name="slctReslt" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" style="width:200px;">
																	<?php if($getExaminationList['exam_result']=="Normal"){ ?>
																	<option value="Normal" selected>Normal</option>
																	<option value="Abnormal">Abnormal</option>
																	<?php } else if($getExaminationList['exam_result']=="Abnormal"){ ?>
																	<option value="Normal" >Normal</option>
																	<option value="Abnormal" selected>Abnormal</option>
																	<?php } else { ?>
																	<option value="">Select</option>
																	<option value="Normal">Normal</option>
																	<option value="Abnormal">Abnormal</option>
																	<?php } ?>
																</select>
															</td>
															<td><input type="text" class="form-control findings" name="finding" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" value="<?php echo $getExaminationList['findings']; ?>" placeholder="Finding" style="width:650px;"></td>
															<td><a class="del_examination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
														</tr>
														<?php }
															?>
													</tbody>
												</table>
											</div>
											<?php  
												}
												?>
										</div>
										<br>
										<div class="input-group col-lg-12 m-b" id="examp_temp_section" style="display: none;">
											<div class="col-lg-4">
												<dl>
													<dt><label> <input type="checkbox" class="i-checks" name="chkExamSaveTemplate" id="chkExamSaveTemplate" value="1"> Save this as template</label></dt>
													<br> 
													<dd><input type="text" name="exam_template_name" id="exam_template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd>
													<br>
												</dl>
											</div>
										</div>
										<br>								
									</div>
									<br>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php } if(($secretary_id!=1) || ($check_reception_permission[0]['investigations']==1 && $secretary_id==1)){include_once('get_investigation_section.php');
									} ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['diagnosis']==1 && $secretary_id==1)){include_once('get_diagnosis_section.php');} ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['treatment_advise']==1 && $secretary_id==1)){include_once('get_treatment_advise_section.php');} ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['prescriptions']==1 && $secretary_id==1)){include_once('get_prescription_section.php');} ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php //include_once('referal_section.php'); ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<?php
										$check_pay_status = mysqlSelect("pay_trans_id","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1' and DATE_FORMAT(trans_date,'%Y-%m-%d')='".$cur_Date."'","","","","");
										$check_last_pay_date = mysqlSelect("pay_trans_id,trans_date","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1'","pay_trans_id desc","","","");
										if($secretary_id!=1){
										?>
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<div class="form-group">
											<div class="col-lg-12" style="padding-bottom:20px;">
												<input type="checkbox" class="i-checks" name="chkRefer" value="1" ><label style="padding-left: 10px;"> Patient agree for our Institute to share the EMR with Professional Health CarePartners (Diagnostic, Pharmacy)</label>
											</div>
											<div class="col-lg-3">
												<dl>
													<dt>Consultation charges(QAR)</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<input name="consult_charge" type="text" placeholder="Consultation charges(QAR)" value="<?php if(count($check_pay_status)>0) { echo " "; } else { echo $get_doc_details[0]['cons_charge']; } ?>" class="form-control" tabindex="8" />
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php if($checkSetting[0]['before_consultation_fee']=="1"){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Payment Status</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<?php if(count($check_pay_status)>0) { ?><span class='label label-primary'>PAYMENT RECEIVED</span><?php } else { ?><span class='label label-danger'>PAYMENT NOT-RECEIVED</span><?php } ?>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } if(!empty($check_last_pay_date)){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Last Payment done </dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($check_last_pay_date[0]['trans_date'])); ?></font>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } if(!empty($patient_episodes[0]['date_time'])){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Last visited on </dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($patient_episodes[0]['date_time'])); ?></font>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } 
												?>
											<div class="col-lg-3 pull-right">
												<dl>
													<dt>Next Follow Up Date</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded1" name="dateadded" type="text" placeholder="Select Next followup date" value="" class="form-control" tabindex="9" autocomplete="off" />
														</div>
													</dd>
													<br>
												</dl>
											</div>
										</div>
									</div>
									<?php } ?>
									<!--Section Ends-->
									<!--Section Starts-->
									<div class="col-lg-12">
										<!--<button class="btn btn-sm btn-primary pull-right m-l" name="save_patient_edit" id="save_patient_edit" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-floppy-o"></i> ADD VISIT</strong></button>-->
										<button class="btn btn-sm btn-primary pull-right" name="save_patient_print" id="save_patient_print" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-print"></i> SAVE & PRINT VISIT</strong></button>
									</div>
									<!--Section Ends-->
									<br>
									<!--<dl>
										<br> <dd><textarea class="form-control" id="examination" name="medical_examination" rows="2" tabindex="3"></textarea>
										</dl>-->
									<br>
								</form>
							</div>
							
							
							<?php } if(isset($_GET['p']) && isset($_GET['episode'])) { 
								$edit_patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","md5(episode_id) = '".$_GET['episode']."' ","","","","");
								
								?>
							<div class="row white-bg page-heading" id="edit_visist_details">
								<?php if($edit_patient_episodes[0]['formated_date_time']==date('d/m/Y')){ $term = "Today's"; } else { $term = "Edit";} ?>
								<h2>
									<b><?php echo $term; ?> Visit Details - Visit <?php echo  $_GET['visit']; ?> (<?php echo $edit_patient_episodes[0]['formated_date_time'] ?>)</b>
									<div class="col-lg-1 pull-right m-t-xs">
										<a href="print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo $_GET['episode']; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-print"></i> PRINT EMR</a>
									</div>
									<div class="col-lg-3 pull-right m-t-xs">
										<dl>
											<dd>
												<form method="post" name="frmDateChange" action="my_patient_profile_save.php">
													<input type="hidden" name="episode_id" value="<?php echo $_GET['episode']; ?>" />
													<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
													<input type="hidden" name="visit" value="<?php echo $_GET['visit']; ?>" />
													<div class="pull-left m-r input-group date">
														<!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="J-demo-02" name="dateadded2" type="text" placeholder="YYYY-MM-DD" value="<?php echo $edit_patient_episodes[0]['date_time'] ?>" class="form-control" /><span class="input-group-addon">Change</span>-->
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="J-demo-02" name="J-demo-02" type="text" placeholder="YYYY-MM-DD" value="<?php echo $edit_patient_episodes[0]['date_time'] ?>" class="form-control" />
														<div class="input-group-btn">
															<button class="btn btn-xl btn-primary"  name="changeDate" type="submit">
															CHANGE
															</button>
														</div>
													</div>
												</form>
											</dd>
											<br>
										</dl>
										<script type="text/javascript">
											$('#J-demo-02').dateTimePicker({
											    mode: 'dateTime'
											});
										</script>
									</div>
								</h2>
								<!-- <button class="btn-white btn btn-xs">View</button>
									<button class="btn-white btn btn-xs">Edit</button>-->
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
									<input type="hidden" name="patient_name" value="<?php echo $patient_tab[0]['patient_name']; ?>">
									<input type="hidden" name="episode_id" value="<?php echo $edit_patient_episodes[0]['episode_id']; ?>">	
									<!--Edit_chief_medical_complaint_section -->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['medical_complaint']==1 && $secretary_id==1)){include_once('edit_chief_medical_complaint_section.php');} ?>
									<!--End Edit_chief_medical_complaint_section -->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['examination']==1 && $secretary_id==1)){ ?>
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<h4>Examination</h4>
										<div class="input-group">
											<?php $last_five_examination = mysqlSelect("b.examination_id as examination_id,b.examination as examination","doctor_frequent_examination as a left join examination as b on a.examination_id=b.examination_id","(a.doc_id='".$admin_id."' and a.doc_type='1') or (a.doc_id='0' and a.doc_type='0')","a.freq_count DESC","","","5");
												$exam_templates = mysqlSelect("*","doc_patient_episode_examination_templates","doc_id='".$admin_id."' and doc_type='1'","exam_template_id desc","","","10");
												
												if(COUNT($exam_templates)>0) {
												?>
											<div class="input-group">				
												<label>Saved Template:  </label>
												<?php
													while(list($key_examtemp, $value_examtemp) = each($exam_templates)){
													?>
												<a class="btn btn-xs btn-white m-l exam_load_template" title="<?php echo $value_examtemp['template_name']; ?>" data-exam-template-id="<?php echo md5($value_examtemp['exam_template_id']);?>" data-edit-status="1" data-patient-id="<?php echo $patientid; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo substr($value_examtemp['template_name'],0,10); ?></code></a>
												<?php } ?>
											</div>
											<br>
											<?php }
												if(COUNT($last_five_examination)>0) { ?>
											<label>Recently used:  </label>
											<?php 
												while(list($key_exam, $value_exam) = each($last_five_examination)){
													
												?>
											<a class="btn btn-xs btn-white m-l get_edit_examination_res_prior" data-examination_id="<?php echo $value_exam['examination_id']; ?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>"><code> <?php echo $value_exam['examination']; ?></code></a>
											<?php }
												} ?>
										</div>
										<br>
										<div class="input-group">
											<input type="text" placeholder="Add / Search examination here..." data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" id="get_examination_res" name="srchExam" value="" class="form-control input-lg searchExamination" tabindex="3">
											<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
												</button>
											</div>
										</div>
										<br>
										<div class="input-group">
											<div id="beforeExaminationResult">
												<?php 
													$getExamination= mysqlSelect("a.examination_id as examination_id,b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$edit_patient_episodes[0]['episode_id']."'","a.examination_id asc","","","");
													
													if(COUNT($getExamination)>0){
													?>
												<a class="btn btn-xs btn-white pull-right delete_all_edit_examination" data-episode-id="<?php echo md5($edit_patient_episodes[0]['episode_id']); ?>" data-doctor-id="<?php echo $admin_id; ?>"><i class="fa fa-trash"></i> Clear All</a>
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Examination</th>
															<th>Result</th>
															<th>Finding</th>
															<th>Delete</th>
														</tr>
													<thead>
													<tbody>
														<tr>
															<?php 
																foreach($getExamination as $getExaminationList){ ?>
														<tr id="del_editexamination_row<?php echo $getExaminationList['examination_id'];?>">
															<td><?php echo $getExaminationList['examination']; ?></td>
															<td>
																<select class="form-control exam_res" name="slctReslt" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" style="width:200px;">
																	<?php if($getExaminationList['exam_result']=="Normal"){ ?>
																	<option value="Normal" selected>Normal</option>
																	<option value="Abnormal">Abnormal</option>
																	<?php } else if($getExaminationList['exam_result']=="Abnormal"){ ?>
																	<option value="Normal" >Normal</option>
																	<option value="Abnormal" selected>Abnormal</option>
																	<?php } else { ?>
																	<option value="">Select</option>
																	<option value="Normal">Normal</option>
																	<option value="Abnormal">Abnormal</option>
																	<?php } ?>
																</select>
															</td>
															<td><input type="text" class="form-control findings" name="finding" data-examination-id="<?php echo $getExaminationList['examination_id']; ?>" value="<?php echo $getExaminationList['findings']; ?>" placeholder="Finding" style="width:650px;"></td>
															<td><a class="del_editexamination" data-examination-id="<?php echo $getExaminationList['examination_id'];?>"><span class="label label-danger">Delete</span></a></td>
														</tr>
														<?php }
															?>
													</tbody>
												</table>
												<?php } ?>
											</div>
											<div id="editExaminationResult"></div>
											<br>
											<div class="input-group col-lg-12 m-b" id="examp_temp_section" style="display: none;">
												<div class="col-lg-4">
													<dl>
														<dt><label> <input type="checkbox" class="i-checks" name="chkExamSaveTemplate" id="chkExamSaveTemplate" value="1"> Save this as template</label></dt>
														<br> 
														<dd><input type="text" name="exam_template_name" id="exam_template_name" placeholder="Template Name" style="display: none;" class="form-control"></dd>
														<br>
													</dl>
												</div>
											</div>
											<br>
										</div>
										<br>
										<!--<dl>
											<br> <dd><textarea class="form-control" id="examination" name="medical_examination" rows="2" tabindex="3"></textarea>
											</dl>-->
									</div>
									<br>
									<!-- edit_iinvsetigation_section starts here -->
									<?php } if(($secretary_id!=1) || ($check_reception_permission[0]['investigations']==1 && $secretary_id==1)){ include_once('edit_invsetigation_section.php');} ?>
									<!-- edit_iinvsetigation_section ends here -->
									<!-- edit_diagnosis_section starts here -->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['diagnosis']==1 && $secretary_id==1)){include_once('edit_diagnosis_section.php');} ?>
									<!-- edit_diagnosis_section ends here -->
									<!-- edit_treatment_advise_section starts here -->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['treatment_advise']==1 && $secretary_id==1)){include_once('edit_treatment_advise_section.php');} ?>
									<!-- edit_treatment_advise_section ends here -->
									<!-- edit_treatment_advise_section starts here -->
									<?php if(($secretary_id!=1) || ($check_reception_permission[0]['prescriptions']==1 && $secretary_id==1)){include_once('edit_prescription_section.php');} ?>
									<!-- edit_treatment_advise_section ends here -->
									<!--<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<div class="form-group">
										 
										 <div class="col-lg-4 pull-right">
											<dl>
												<dt>Next Follow Up Date</dt><br> <dd><div class="pull-left m-r input-group date">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded1" name="dateadded" type="text" placeholder="Select date" value="<?php if(!empty($edit_patient_episodes[0]['next_followup_date'])){ echo date('d-m-Y',strtotime($edit_patient_episodes[0]['next_followup_date']));} ?>" class="form-control" tabindex="9"/>
												</div></dd><br>
											</dl>
											<br>
											<a href="print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo $_GET['episode']; ?>"><button class="btn btn-sm btn-primary pull-right m-b" name="save_patient_print" id="save_patient_print" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-print"></i> PRINT EMR</strong></button></a>
											<br>
										</div>
										
										</div>
										</div>-->
									<!--Section Starts-->
									<?php
										$check_pay_status = mysqlSelect("pay_trans_id","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1' and DATE_FORMAT(trans_date,'%Y-%m-%d')='".$cur_Date."'","","","","");
										$check_last_pay_date = mysqlSelect("pay_trans_id,trans_date","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1'","pay_trans_id desc","","","");
										if($secretary_id!=1){
										?>
									<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										<div class="form-group">
											<div class="col-lg-3">
												<dl>
													<dt>Consultation charges(QAR)</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<input name="consult_charge" type="text" placeholder="Consultation charges(QAR)" value="<?php if(count($check_pay_status)>0) { echo " "; } else { echo $get_doc_details[0]['cons_charge']; } ?>" class="form-control" tabindex="8" />
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php if($checkSetting[0]['before_consultation_fee']=="1"){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Payment Status</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<?php if(count($check_pay_status)>0) { ?><span class='label label-primary'>PAYMENT RECEIVED</span><?php } else { ?><span class='label label-danger'>PAYMENT NOT-RECEIVED</span><?php } ?>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } if(!empty($check_last_pay_date)){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Last Payment done </dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($check_last_pay_date[0]['trans_date'])); ?></font>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } if(!empty($patient_episodes[0]['date_time'])){ ?>
											<div class="col-lg-2 ">
												<dl>
													<dt>Last visited on </dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($patient_episodes[0]['date_time'])); ?></font>
														</div>
													</dd>
													<br>
												</dl>
											</div>
											<?php } 
												?>
											<div class="col-lg-3 pull-right">
												<dl>
													<dt>Next Follow Up Date</dt>
													<br> 
													<dd>
														<div class="pull-left m-r input-group date">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded1" name="dateadded" type="text" placeholder="Select Next followup date" value="<?php if(!empty($edit_patient_episodes[0]['next_followup_date'])){ echo date('d-m-Y',strtotime($edit_patient_episodes[0]['next_followup_date']));} ?>" class="form-control" tabindex="9" autocomplete="off" />
														</div>
													</dd>
													<br>
												</dl>
											</div>
										</div>
									</div>
									<?php } ?>
									<!--Section Starts-->
									<div class="col-lg-12">
										<!--<button class="btn btn-sm btn-primary pull-right m-l" name="save_patient_edit" id="save_patient_edit" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-floppy-o"></i> ADD VISIT</strong></button>-->
										<button class="btn btn-sm btn-primary pull-right" name="update_patient_print" id="update_patient_print" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-print"></i> SAVE & PRINT VISIT</strong></button>
									</div>
									<!--Section Ends-->
								</form>
							</div>
							<?php } ?>
							<!-- START VIEW REPORT SECTION -->
							<?php 
								$doc_patient_reports = mysqlSelect("DISTINCT(report_folder) as report_folder","doc_my_patient_reports","patient_id = '".$patient_tab[0]['patient_id']."' and user_type!='4'","report_folder desc","","","");
								
								?>
							<div class="row white-bg page-heading" id="view-latest-reports">
								<h2><i class="fa fa-copy"></i> View Medical Reports</h2>
								<div class="ibox-content">
									<input type="hidden" name="pat_mobile" id="pat_mobile" value="<?php echo $patient_tab[0]['patient_mob']; ?>" /> 
									<input type="hidden" name="pat_id" id="pat_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" /> 
									<a href="#" title="Click here to share a link with the patients to upload their old reports" class="btn btn-w-m btn-info m-l share_link_report pull-right">Share Link</a>
									<button type="button" id="attachReport" class="btn btn-w-m btn-info pull-right">Attach reports</button>
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
									<?php 
									while(list($key_list, $value_list) = each($doc_patient_reports)) 
									{
										$get_reports = mysqlSelect("*","doc_my_patient_reports","report_folder = '".$value_list['report_folder']."' and user_type!='4'","","","","");
										if($get_reports[0]['user_type']=='1')
										{
											$username=$patient_tab[0]['patient_name'];
										}
										if($get_reports[0]['user_type']=='2')
										{
											
											$username=$get_doc_details[0]['ref_name'];
										}
										if($get_reports[0]['user_type']=='3')
										{
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
													foreach($get_reports as $attachList)
													{ 
														//Here we need to check file type
														$img_type =  array('gif','png' ,'jpg' ,'jpeg');
														$extractPath = pathinfo($attachList['attachments'], PATHINFO_EXTENSION);
													if(in_array($extractPath,$img_type) ) 
													{
														$imgIcon="patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments'];
													}
													else if($extractPath=="docx")
													{
															$imgIcon="../assets/images/doc.png";
													}
													else if($extractPath=="pdf" || $extractPath=="PDF")
													{
														$imgIcon="../assets/images/pdf.png";
													} 
													
													
													if($attachList['report_type']==1)
													{ ?>
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
							<!-- STAR TREND ANALYSIS SECTION -->
							<div class="row white-bg page-heading" id="view-trend-analysis">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-line-chart"></i> Trend Analysis</h2>
									<a href="#"  class="btn btn-w-m btn-info m-l pull-right m-b m-t m-r" id="customTrendClick">Customized Trend Analysis</a>
								</div>
								<div class="m-t m-b" style="margin-bottom:120px;">
									<div style="width:100%; height:400px;">
										<canvas id="canvas" ></canvas>
									</div>
								</div>
								<br><br><br><br><br><br><br><br>
								<a href="javascript:void(0);" data-toggle="collapse" data-target="#demo" class="btn btn-primary pull-right" ></i> ADD</a>
								<br><br>
								<div id="demo" class="collapse col-lg-10">
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php" autocomplete="off" name="frmAddGlucoseCount" id="frmAddGlucoseCount">
										<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
										<table class="table table-bordered">
											<tr>
											<thead>
												<th colspan="2" class="text-center">Add Details</th>
											</thead>
											<tbody>
												<tr>
													<th>Date</th>
													<td>
														<div class="input-group date">
															<!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>--><input id="dateadded" name="dateadded" type="text" required placeholder="DD/MM/YYYY" class="form-control" >
														</div>
													</td>
												</tr>
												<tr>
													<th>BLOOD GLUCOSE (Fasting)</th>
													<td><input type="text" id="before_meals" name="before_meals" value="" maxlength="3" class="form-control"></td>
												</tr>
												<tr>
													<th>BLOOD GLUCOSE (Post Prandial)</th>
													<td><input type="text" id="after_meals" name="after_meals" value="" maxlength="3" class="form-control"></td>
												</tr>
												<tr>
													<th>Systolic</th>
													<td><input name="systolicCount" type="text" class="form-control" ></td>
												</tr>
												<tr>
													<th>Diastolic</th>
													<td><input type="text" name="diastolicCount" value="" class="form-control"></td>
												</tr>
												<tr>
													<th>Glyco Hb(HbA1c)</th>
													<td><input type="text" name="hba1cCount" value="" class="form-control"></td>
												</tr>
												<tr>
													<th>HDL CHOLESTEROL</th>
													<td><input  name="hdlCount" type="text"  class="form-control" ></td>
												</tr>
												<tr>
													<th>VLDL</th>
													<td><input name="vldlCount" type="text" class="form-control" ></td>
												</tr>
												<tr>
													<th>LDL CHOLESTEROL</th>
													<td><input name="ldlCount" type="text"  class="form-control" ></td>
												</tr>
												<tr>
													<th>TRIGLYCERIDES</th>
													<td><input type="text" name="triglycerideCount" value="" class="form-control"></td>
												</tr>
												<tr>
													<th>TOTAL CHOLESTEROL</th>
													<td><input type="text" name="cholestrolCount"  value="" class="form-control"></td>
												</tr>
												<tr>
													<td colspan="2"><button type="submit" name="addPrandialCount" class="btn btn-primary pull-right">SUBMIT</button></td>
												</tr>
											</tbody>
										</table>
									</form>
								</div>
								<div claas="form-control m-t-xs" style="margin-top:30px;">
									<table class="table table-responsive table-bordered">
										<tbody>
											<tr>
										<thead>
											<th>Medical Test</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisDate)){ ?>
											<th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th>
											<?php } ?>
										</thead>
										</tr>
										<tr>
											<th>Prescription Given</th>
											<?php while(list($key, $value) = each($get_medicineGivenDate))
											{ 
												$medicineGiven = mysqlSelect("a.prescription_trade_name as prescription_trade_name,a.duration as duration,a.prescription_frequency as prescription_frequency","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on a.episode_id=b.episode_id","b.patient_id='".$patient_id."' and DATE_FORMAT(a.prescription_date_time,'%Y-%m-%d')='".$value['date_added']."'","a.episode_prescription_id desc","","","4");
												
												?>
											<td>
												<?php if($medicineGiven==true){ ?><!--<a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $key; ?>" class="item">View Prescription</a>
													<div id="demo<?php echo $key; ?>" class="collapse">-->
												<?php $i=1;while(list($key_presc,$value_presc) = each($medicineGiven)) { ?>
												<small><b><?php echo $i; ?>.</b>  </small><small><b><?php echo $value_presc['prescription_trade_name']; ?></b></small> | <small><?php echo $value_presc['prescription_frequency']; ?></small> | <small><?php echo $value_presc['duration']; ?></small>
												<br>
												<?php $i++; }  ?>
												<!--</div>-->
												<?php } ?>
											</td>
											<?php } ?>
										</tr>
										<tr>
											<th>BLOOD GLUCOSE (Fasting)</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisPPCount)){ ?>
											<td><?php echo $value['bp_beforefood_count']; ?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>BLOOD GLUCOSE (Post Prandial)</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisPPAfterCount)){ ?>
											<td><?php echo $value['bp_afterfood_count']; ?></td>
											<?php } ?>
										</tr>
										<th>Systolic</th>
										<?php while(list($key, $value) = each($get_TrendAnalysisSystolic)){ ?>
										<td><?php echo $value['systolic']; ?></td>
										<?php } ?>
										</tr>
										<tr>
											<th>Diastolic</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisDiastolic)){ ?>
											<td><?php echo $value['diastolic']; ?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>Glyco Hb(HbA1c)</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisHbA1c)){ ?>
											<td><?php echo $value['HbA1c'];?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>HDL CHOLESTEROL</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisHDL)){ ?>
											<td><?php echo $value['HDL'];?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>VLDL</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisVLDL)){ ?>
											<td><?php echo $value['VLDL'];?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>LDL CHOLESTEROL</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisLDL)){ ?>
											<td><?php echo $value['LDL']; ?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>TRIGLYCERIDES</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisTriglyceride)){ ?>
											<td><?php echo $value['triglyceride']; ?></td>
											<?php } ?>
										</tr>
										<tr>
											<th>TOTAL CHOLESTEROL</th>
											<?php while(list($key, $value) = each($get_TrendAnalysisCholesterol)){ ?>
											<td><?php echo $value['cholesterol'];?></td>
											<?php } ?>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							
							<!-- STAR TRACKING SECTION -->
							<div class="row white-bg page-heading" id="visit-tracker">
								<h2><i class="fa fa-list"></i> Tracking Details</h2>
								<div class="ibox-content">
									<?php 
										$appoint_track = mysqlSelect("*","appointment_tracking","doc_id='".$admin_id."'","","","","");
									?>
									
									<div class="fullwidth"> 
									
										<div class="container separator">
										<?php foreach ($appoint_track as $applist)
										{ ?>
											<ul class="progress-tracker progress-tracker--vertical">
											  <li class="progress-step is-complete">
												<div class="progress-marker"></div>
												<div class="progress-text">
												  <h4 class="progress-title">
													<?php echo $applist['message']; ?></h4>
													<?php echo $applist['created_date']; ?>
												</div>
											  </li>
											</ul> <?php }?>
										</div>
											
									</div>
										
										
										<!--div class="container">
											<ul class="progressbar">
												<li><?php //echo $applist['message']; ?><br /><small><?php //echo $applist['created_date']; ?></small></li>
											</ul>
										</div-->
									
								</div>
							</div>
							<!-- TRACKING SECTION -->
							
							<!-- STAR VIEW VIDEO CALL SECTION -->
							<div class="row white-bg page-heading" id="view-video_call">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-video-camera"></i> Video Call / Chat Details</h2>
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<form method="post" name="frmSendVideoCallRequest" autocomplete="off" action="add_details.php">
											<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>">
											<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
											<div class="row">
												<div class="col-sm-3">
													<div class="form-group">
														<label class="control-label" for="product_name">Pick Date</label>
														<input id="vid_date" name="vid_date" value="" placeholder="Select Date" required class="form-control">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group">
														<label class="control-label" for="price">Pick Time</label>
														<input type="time" id="vid_time" name="vid_time" value="" placeholder="Select Time"" required class="form-control">
													</div>
												</div>
												<div class="col-sm-1">
													<div class="form-group" style="margin-top:5px;">
														<label class="control-label" for="status" ></label>
														<button type="submit" name="send_video_request" id="send_video_request" class="btn btn-outline btn-primary"><i class="fa fa-paper-plane" style="margin-right:10px;"></i>Send Request </button>
													</div>
												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-md-1">
												<a href="javascript:void(0)" class="btn btn-outline btn-primary"><i class="fa fa-video-camera" aria-hidden="true"></i></a>
											</div>
											<div class="col-md-11"> <strong>Note: </strong>Please instruct the patient to state as below before you start the video consultation.
												<i>"I, patient name, expressly consent for a tele consultation with Dr.DR-NAME for diagnosis and also to receive the treatment based on this consultation, through an electronic medical record or an electronic prescription. I understand the risks of not having a physical consultation."</i>
											</div>
										</div>
									</div>
									<?php 
										//$doc_patient_video_requests = $objQuery->mysqlSelect("a.vid_request_id as vid_request_id, a.video_date as video_date, a.video_time as video_time, b.status as status, a.created_date as created_date","video_call_requests as a inner join video_call_status as b on b.vid_request_id = a.vid_request_id","a.patient_id = '".$patient_episodes[0]['patient_id']."' and doc_id = '".$admin_id."'","vid_request_id desc","","","");
										$doc_patient_video_requests = mysqlSelect("a.vid_request_id as vid_request_id, b.video_date as video_date, b.video_time as video_time, a.status as status, a.url as url, a.created_date as created_date","video_call_status as a inner join video_call_requests as b on b.vid_request_id = a.vid_request_id","b.patient_id = '".$patient_tab[0]['patient_id']."' and b.doc_id = '".$admin_id."'","a.vid_request_id desc","","","");
										
										?>
									<script>
										function myVideoClick(temp) { 
										$('.videoCallDisplay'+temp).css('display','block');
										//$('.iframeVideo').attr("src", "https://maayayoga.com/msvV2.0/index.php?ref_name=<?php echo rawurlencode($_SESSION['user_name']); ?>&pat_name=<?php echo rawurlencode($patient_tab[0]['patient_name']); ?>&type=1&r=<?php echo $admin_id."_".$patient_tab[0]['member_id']."_".$patient_tab[0]['transaction_id']; ?>"); 
										$('.iframeVideo').attr("src", "<?php echo $patient_tab[0]['doc_agora_link'];?>"); 
										}
										$(document).ready(function() {
										// Add drag and resize option to panel
										 $("#toolbox-tools").draggable({
										     handle: ".panel-heading",
										     stop: function(evt, el) {
										         // Save size and position in cookie
										         /*
										         $.cookie($(evt.target).attr("id"), JSON.stringify({
										             "el": $(evt.target).attr("id"),
										             "left": el.position.left,
										             "top": el.position.top,
										             "width": $(evt.target).width(),
										             "height": $(evt.target).height()
										         }));
										         */
										     }
										 }).resizable({
										     handles: "e, w, s, se",
										     stop: function(evt, el) {
										         // Save size and position in cookie
										         /*
										         $.cookie($(evt.target).attr("id"), JSON.stringify({
										             "el": $(evt.target).attr("id"),
										             "left": el.position.left,
										             "top": el.position.top,
										             "width": el.size.width,
										             "height": el.size.height
										         }));
										         */
										     }
										 });    
										
										 
										 // Expand and collaps the toolbar
										 $("#toggle-toolbox-tools").on("click", function() {
										     var panel = $("#toolbox-tools");
										 	
										 	if ($(panel).data("org-height") == undefined) {
										 		$(panel).data("org-height", $(panel).css("height"));
										 		$(panel).css("height","41px");
										 	} else {
										 		$(panel).css("height", $(panel).data("org-height"));
										 		$(panel).removeData("org-height");
										 	}
										 	    	
										 	$(this).toggleClass('fa-chevron-down').toggleClass('fa-chevron-right');
										 });
										
										
										 // Make toolbar groups sortable
										 $( "#sortable" ).sortable({
										stop: function (event, ui) {
										         var ids = [];
										$.each($(".draggable-group"), function(idx, grp) {
											ids.push($(grp).attr("id"));
										});
										
										         // Save order of groups in cookie
										//$.cookie("group_order", ids.join());
										}
										});
										$( "#sortable" ).disableSelection();
										
										
										 // Make Tools panel group minimizable
										$.each($(".draggable-group"), function(idx, grp) {
										var tb = $(grp).find(".toggle-button-group");
										
										$(tb).on("click", function() {
										$(grp).toggleClass("minimized");
										$(this).toggleClass("fa-caret-down").toggleClass("fa-caret-up");
										
										// Save draggable groups to cookie (frue = Minimized, false = Not Minimized)
										var ids = [];
										$.each($(".draggable-group"), function(iidx, igrp) {
											var itb = $(igrp).find(".toggle-button-group");
											var min = $(igrp).hasClass("minimized");
										
											ids.push($(igrp).attr("id") + "=" + min);
										});
										
										$.cookie("group_order", ids.join());
										});
										});
										
										
										
										 // Close thr panel
										 $(".close-panel").on("click", function() {
										$(this).parent().parent().hide();
										});
										
										 
										 // Add Tooltips
										 $('button').tooltip();
										 $('.toggle-button-group').tooltip();
										 
										
										/*var iframe = $("#iframe"); 
										var newWindow = window.open(iframe.attr(src), 'Dynamic Popup', 'height=' + iframe.height() + ', width=' + iframe.width() + 'scrollbars=auto, resizable=no, location=no, status=no');
										newWindow.document.write(iframe[0].outerHTML);
										newWindow.document.close();
										iframe[0].outerHTML = '';*/ // to remove iframe in page.
										 
										});
									</script>
									<div class="ibox-content m-b-sm" style="margin-left:10px;margin-right:10px;">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th colspan="5" class="text-center">Video Call Status</th>
												</tr>
												<tr>
													<th class="text-center" width="100px;">SI. No.</th>
													<th class="text-center">Date</th>
													<th class="text-center">Time</th>
													<th class="text-center">Status</th>
													<!--<th class="text-center"></th>-->  
												</tr>
											</thead>
											<tbody>
												<?php 
													$temp = 1;
													foreach($doc_patient_video_requests as $videoRequestLists) 
													{
															if($videoRequestLists['status'] == 1) 
															{   
																// 1-request sent, 2-request accepted, 3-request declined, 4-call in progress, 		5-call disconnected, 6-call finished
																$vid_status = 'Call Request Sent';
															}
															else if($videoRequestLists['status'] == 2)
															{
																$vid_status = 'Call Request Accepted';
															}
															else if($videoRequestLists['status'] == 3)
															{
																$vid_status = 'Call Request Declined';
															}
															else if($videoRequestLists['status'] == 4)
															{
																$vid_status = 'Call in Progress';
															}
															else if($videoRequestLists['status'] == 5) 
															{
																$vid_status = 'Call Disconnected';
															}
															else if($videoRequestLists['status'] == 6) 
															{
																$vid_status = 'Call Finished';
															}
															else 
															{
																$vid_status = '';
															}
															
														?>
												<tr>
													<!--	<td>  <?php echo $temp.".  ". $vid_status; ?> on Date: <?php echo date('d-m-Y',strtotime($videoRequestLists['video_date'])); ?> and Time: <?php echo date('h:i:s a',strtotime($videoRequestLists['video_time'])); ; ?></td>
														-->
													<td width="100px;" class="text-center"><?php echo $temp; ?></td>
													<td class="text-center"><?php echo date('d-m-Y',strtotime($videoRequestLists['video_date'])); ?></td>
													<td class="text-center"><?php echo date('h:i:s a',strtotime($videoRequestLists['video_time'])); ; ?></td>
													<td><?php echo $vid_status; ?> </td>
													<?php if( ($videoRequestLists['status'] < 6 ) && ($Cur_Date <= date('Y-m-d',strtotime($videoRequestLists['video_date'])))  ) { ?>
													<!--<td class="text-center">--><!--<a href="<?php echo $videoRequestLists['url'];?>" target="_blank"><i class="fa fa-video-camera" aria-hidden="true"></i></a>-->
													<!--<button onclick="myVideoClick(<?php echo $temp; ?>)" class="btn btn-outline btn-primary"><i class="fa fa-video-camera" aria-hidden="true"> </i> Start Video Call</button>
														<div class="panel panel-primary draggable-panel toolbar-panel ui-draggable ui-resizable videoCallDisplay<?php echo $temp; ?>" id="toolbox-tools" style="position:absolute;display:none;top:50px;right:100px;">
														<div class="panel-heading lang-panel-header-tools">Calling To <?php echo $patient_tab[0]['patient_name']; ?> <i class="fa fa-times pull-right close-panel" id="close-toolbox-tools"></i><i class="fa pull-right fa-chevron-down" id="toggle-toolbox-tools"></i></div>
														<iframe  width="100%" height="100%" style="height:100%;width:100%;overflow:scroll" id="toolbox-tools" id="resizable" class="resizable" class="panel panel-primary draggable-panel toolbar-panel ui-draggable ui-resizable" src="https://maayayoga.com/msvV2.0/agent1.html" allow="camera;microphone"></iframe>
														
														</div>-->
													<!--<a class="btn btn-outline btn-primary" onclick="window.open('<?php echo $videoRequestLists['url'];?>', '_blank', 'location=yes,height=570,width=520,scrollbars=yes,status=yes');">
														<i class="fa fa-video-camera" aria-hidden="true"> </i> Start Video Call
														</a>-->
													<!--</td>-->
													<?php } else { ?>
													<td></td>
													<?php }  ?>
												</tr>
												<?php $temp++; } ?>
											</tbody>
										</table>
									</div>
									<?php
										?>
								</div>
							</div>
							<!-- STAR VIEW VACCINE SECTION -->
							<div class="row white-bg page-heading" id="vaccineDetailSec">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-eyedropper"></i> Vaccine Details</h2>
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<?php  $vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","");?>
										<div class="x_title">
											<h2>Update vaccination schedule date</h2>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<div class="form-group">
												<form method="post" name="frmFollowDate" action="add_details.php">
													<input type="hidden" name="child_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
													<label class="control-label col-md-2 col-sm-2 col-xs-12">Vaccine Duration: <span class="required">*</span></label>
													<div class="col-md-3 col-sm-3 col-xs-12">
														<select class="form-control" name="vaccine_duration" id="vaccine_duration" required="required"  >
															<option value="">Select</option>
															<?php foreach($vaccineduration as $vaccinedurationList){ 
																if($vaccinedurationList['duartion_id']!=1){
																?>
															<option value="<?= $vaccinedurationList['duartion_id'] ?>"><?= $vaccinedurationList['duration_name'] ?></option>
															<?php 
																}
																} ?>
														</select>
													</div>
													<label class="control-label col-md-2 col-sm-2 col-xs-12">Vaccine Due Date: <span class="required">*</span></label>
													<div class="col-md-3 col-sm-3 col-xs-12">
														<input type="text" id="J-demo1-02" name="vaccine_due_date" required="required" class="form-control" placeholder="">
														<script type="text/javascript">
															$('#J-demo1-02').dateTimePicker();
														</script>
													</div>
													<div class="col-md-2 col-sm-2 col-xs-12">
														<button type="submit" name="update_vaccine_due_date" id="update_vaccine_due_date" class="btn btn-success">Update</button>
													</div>
												</form>
											</div>
										</div>
										<br><br>
										<!-- start user projects -->
										<table class="data table table-striped no-margin">
											<thead>
												<tr>
													<th width="200">Vaccine Duration</th>
													<th width="400">Vaccine Name</th>
													<th colspan="1">Last Updated</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$getDob=date('Y-m-d',strtotime($child_tab[0]['vaccine_start_date']));
													
													 foreach($vaccineduration as $vaccinedurationList){
													$chkVaccineDue = mysqlSelect("*","vaccine_child_due_date","child_id='".$patient_tab[0]['patient_id']."' and vaccine_duration_id='".$vaccinedurationList['duartion_id']."'","","","","");
													if($vaccinedurationList['duartion_id']=="1") {
															if($chkVaccineDue[0]['vaccine_duration_id']=="1")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
															$vaccin_date=date('d M Y',strtotime($child_tab[0]['vaccine_start_date']));
															}
													} 
													else if($vaccinedurationList['duartion_id']=="2"){
														
														if($chkVaccineDue[0]['vaccine_duration_id']=="2")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
															//Add 6 weeks to DOB
															$add_days = 7*6;
															$vaccin_date = date('d M Y',strtotime($getDob) + (24*3600*$add_days));
															}
													}
													else if($vaccinedurationList['duartion_id']=="3"){
														if($chkVaccineDue[0]['vaccine_duration_id']=="3")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														//Add 10 weeks to DOB
														$add_days = 7*10;
														$vaccin_date = date('d M Y',strtotime($getDob) + (24*3600*$add_days));
															}
													}
													else if($vaccinedurationList['duartion_id']=="4"){
														if($chkVaccineDue[0]['vaccine_duration_id']=="4")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														//Add 14 weeks to DOB
														$add_days = 7*14;
														$vaccin_date = date('d M Y',strtotime($getDob) + (24*3600*$add_days));
															}
													}
													else if($vaccinedurationList['duartion_id']=="5"){
														if($chkVaccineDue[0]['vaccine_duration_id']=="5")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														//Add 18 weeks to DOB
														$add_days = 7*18;
														$vaccin_date = date('d M Y',strtotime($getDob) + (24*3600*$add_days));
														}
													}
													else if($vaccinedurationList['duartion_id']=="6"){
														if($chkVaccineDue[0]['vaccine_duration_id']=="6")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
													
														$vaccin_date = date('d M Y', strtotime("+6 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="7"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="7")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{	
															$vaccin_date = date('d M Y', strtotime("+9 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="8"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="2")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+12 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="9"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="9")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+15 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="10"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="10")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+18 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="11"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="11")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+23 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="13"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="13")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+4 years", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="14"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="14")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+13 years", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="15"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="15")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+6 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="16"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="16")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+12 months", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													else if($vaccinedurationList['duartion_id']=="17"){
													
														if($chkVaccineDue[0]['vaccine_duration_id']=="17")
															{
															$vaccin_date = 	date('d M Y',strtotime($chkVaccineDue[0]['vaccine_due_date']));
															}
															else 
															{
														$vaccin_date = date('d M Y', strtotime("+2 years", strtotime($child_tab[0]['vaccine_start_date'])));
															}
													}
													 ?>
												<tr>
													<td><?php echo $vaccinedurationList['duration_name']."<br>".$vaccin_date; ?></td>
													<td><?php 
														$vaccinename =mysqlSelect("*","vaccine_mapping as a left join vaccine_tab as b on a.vaccine_tab_id=b.vaccine_id","a.vaccine_duration_id='".$vaccinedurationList['duartion_id']."' AND country_id =0","a.vaccine_duration_id asc","","","");
														
														foreach($vaccinename as $vaccinenameList){
														$getActualVaccine= mysqlSelect("*","vaccine_child_tab","child_id='".$patient_tab[0]['patient_id']."' and vaccine_id='".$vaccinenameList['vaccine_id']."' and vaccine_duration_id='".$vaccinenameList['vaccine_duration_id']."'","","","","");
														
																								
														if($getActualVaccine==true)	{
														echo "<span class='label label-success'>".$vaccinenameList['vaccine_name']."</span>, "; 
														
														}
														else{
															echo $vaccinenameList['vaccine_name'].", ";
														}
														}
														?>
													</td>
													<td>
														<?php 
															$getLastVaccineVal= mysqlSelect("*","vaccine_child_tab","child_id='".$patient_tab[0]['patient_id']."' and vaccine_duration_id='".$vaccinedurationList['duartion_id']."'","vaccine_given_date desc","","","");
															
															if($getLastVaccineVal==true){
															//To check last upadated User Type
																if($getLastVaccineVal[0]['user_type']==1){ //User Type 1 for Asha worker & 2 for Parents
																	$vaccinGiven = mysqlSelect("partner_name","our_partners","partner_id='".$getLastVaccineVal[0]['user_id']."'","","","","");
																	$lastUpadatedUser=$vaccinGiven[0]['partner_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($getLastVaccineVal[0]['vaccine_given_date']));
																}
																else if($getLastVaccineVal[0]['user_type']==2){
																	$vaccinGiven = mysqlSelect("mother_name","parents_tab","parent_id='".$getLastVaccineVal[0]['user_id']."'","","","","");
																	$lastUpadatedUser=$vaccinGiven[0]['mother_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($getLastVaccineVal[0]['vaccine_given_date']));
																}
																else if($getLastVaccineVal[0]['user_type']==3){
																	$vaccinGiven = mysqlSelect("ref_name","referal","ref_id='".$getLastVaccineVal[0]['user_id']."'","","","","");
																	$lastUpadatedUser=$vaccinGiven[0]['ref_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($getLastVaccineVal[0]['vaccine_given_date']));
																}
															?>
														<small><?php echo //$lastUpadatedUser."<br>".
															$lastUpdatedDate; ?></small>
														<?php } ?>
													</td>
													<td>
														<a href="#myModal<?php echo $vaccinedurationList['duartion_id']; ?>" class="label label-primary pull-right" data-toggle="modal" ><i class="fa fa-edit"></i> - <small>VIEW</small></a>
														<div id="myModal<?php echo $vaccinedurationList['duartion_id']; ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
																		</button>
																		<h4 class="modal-title" id="myModalLabel"><?php echo $vaccinedurationList['duration_name']."<br>Due Date: ".$vaccin_date; ?></h4>
																	</div>
																	<form enctype="multipart/form-data" method="post" action="add_details.php" >
																		<input type="hidden" name="child_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
																		<div class="modal-body" style="padding-bottom:50px;">
																			<?php $vacCount=0;
																				foreach($vaccinename as $vaccinenameList){
																					$getActualVaccine= mysqlSelect("*","vaccine_child_tab","child_id='".$patient_tab[0]['patient_id']."' and vaccine_id='".$vaccinenameList['vaccine_id']."' and vaccine_duration_id='".$vaccinenameList['vaccine_duration_id']."'","","","","");
																					if($getActualVaccine==true)	{
																					echo "<span class='label label-success'>".$vaccinenameList['vaccine_name']."</span>,<br>";
																						//Given Date: ".date('d M Y',strtotime($getActualVaccine[0]['vaccine_given_date'])); 
																					}
																					else{
																						echo $vaccinenameList['vaccine_name'];
																					}
																					$vacCount++;
																				?><br/>
																			<input type="hidden" name="vaccineId<?php echo $vacCount; ?>" value="<?php echo $vaccinenameList['vaccine_id']; ?>" >	
																			<input type="hidden" name="vaccineDurationId<?php echo $vacCount; ?>" value="<?php echo $vaccinenameList['vaccine_duration_id']; ?>" >
																			<label class="control-label">Vaccine Given Date: <span class="required">*</span></label>
																			<input type="text" id="J1-demo<?php echo $vaccinenameList['vaccine_duration_id'].$vacCount; ?>" name="vaccine_given_date<?php echo $vacCount; ?>" value="<?php if($getActualVaccine==true)	{ echo date('m/d/Y',strtotime($getActualVaccine[0]['vaccine_given_date'])); } ?>" required="required" class="form-control" placeholder="">
																			<script type="text/javascript">
																				$(document).ready(function(){
																				$('#J1-demo<?php echo $vaccinenameList['vaccine_duration_id'].$vacCount; ?>').datepicker({
																					todayBtn: "linked",
																					keyboardNavigation: false,
																					forceParse: false,
																					calendarWeeks: true,
																					autoclose: true
																				});
																				});
																			</script>									
																			<label for="inputComment">Comments</label>
																			<textarea class="form-control" id="txtComment<?php echo $vacCount; ?>" name="txtComment<?php echo $vacCount; ?>" rows="2"><?php echo $getActualVaccine[0]['remarks']; ?></textarea>
																			<br/>					
																			<?php	}
																				?>
																			<input type="hidden" name="vacCount" value="<?php echo $vacCount; ?>" >								  
																		</div>
																		<div class="modal-footer">
																			<button type="submit" name="update_vaccine_details" id="update_vaccine_details" class="btn btn-success">Submit</button> 
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<?php } 
													?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- END VACCINE SECTION -->
							<!-- STAR VIEW GROWTH SECTION -->
							<div class="row white-bg page-heading" id="growthDetailSec">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-eyedropper"></i> Growth Chart Details</h2>
									<a href="#"  class="btn btn-w-m btn-info m-l pull-right m-b m-t m-r" id="growthChartClick">View Chart</a>
								</div>
								<div class="row">
									<!--<h2 style="float:left;" class="m-l"><i class="fa fa-eyedropper"></i> Growth Chart Details</h2>-->
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<form method="post" name="frmSendVideoCallRequest" autocomplete="off" action="add_details.php">
											<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>">
											<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
											<input id="dateadded" name="date_birth" type="hidden" value="<?php echo date('m/d/Y',strtotime($patient_tab[0]['DOB']));?>">
											<input id="edd" name="edd" type="hidden" value="<?php echo date('m/d/Y',strtotime($child_tab[0]['edd']));?>">
											<div class="row">
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Growth Date</label>
														<input id="growth_date" name="growth_date" value="" placeholder="Select Date" required class="form-control">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Height</label>
														<input name="growth_height" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Weight</label>
														<input name="growth_weight" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Head Circumference</label>
														<input name="growth_circum" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Measurement</label>
														<input name="growth_measure" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-1">
													<div class="form-group" style="margin-top:5px;">
														<label class="control-label" for="status" ></label>
														<button type="submit" name="add_growthDtl" id="add_growthDtl" class="btn btn-outline btn-primary"><i class="fa fa-paper-plane" style="margin-right:10px;"></i>Add Growth Detail </button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<!-- start user projects -->
										<table class="data table table-striped no-margin">
											<thead>
												<tr>
													<th>Date</th>
													<th>Height<br><span style="font-size:11px; color:#979797;">(cm)</span></th>
													<th>Weight<br><span style="font-size:11px; color:#979797;">(gm)</span></th>
													<th>Head Circumference<br><span style="font-size:11px; color:#979797;">(cm)</span></th>
													<th>Last Updated</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$growthInfo = mysqlSelect("*","growth_chart","child_id='".$patient_tab[0]['patient_id']."'","gc_id desc","","","");
													if($growthInfo == true){
													foreach($growthInfo as $growthList){
													?>
												<tr>
													<td><?php echo date('d-m-Y',strtotime($growthList['growth_date'])); ?><br><span style="font-size:11px;"><?php echo $growthList['actual_age']; ?>M/<?php echo $growthList['corrected_age']; ?>M</span></td>
													<td><?php echo $growthList['height']; ?> cm</td>
													<td><?php echo $growthList['weight']; ?> gms</td>
													<td class="hidden-phone"><?php echo $growthList['head_circum']; ?> cm</td>
													<td class="hidden-phone">
														<?php 
															//To check last upadated User Type
																if($growthList['user_type']==1){ //User Type 1 for Asha worker & 2 for Parents
																	$updatedBy = mysqlSelect("partner_name","our_partners","partner_id='".$growthList['user_id']."'","","","","");
																	$lastUpadatedUser=$updatedBy[0]['partner_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($growthList['created_date']));
																}
																else if($growthList['user_type']==2){
																	$updatedBy = mysqlSelect("mother_name","parents_tab","parent_id='".$growthList['user_id']."'","","","","");
																	$lastUpadatedUser=$updatedBy[0]['mother_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($growthList['created_date']));
																}
															?>
														<small><?php echo $lastUpadatedUser."<br>".$lastUpdatedDate; ?></small>
													</td>
													<td>
														<a href="#myModalGrowth<?php echo $growthList['gc_id']; ?>" class="label label-primary pull-right" data-toggle="modal" ><i class="fa fa-edit"></i> - <small>Edit</small></a>
														<div id="myModalGrowth<?php echo $growthList['gc_id']; ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
																		</button>
																		<h4 class="modal-title" id="myModalLabel">Edit Details</h4>
																	</div>
																	<form enctype="multipart/form-data" method="post" action="add_details.php" >
																		<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>">
																		<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
																		<input id="dateadded" name="date_birth" type="hidden" value="<?php echo date('m/d/Y',strtotime($patient_tab[0]['DOB']));?>">
																		<input id="edd" name="edd" type="hidden" value="<?php echo date('m/d/Y',strtotime($child_tab[0]['edd']));?>">
																		<div class="modal-body" style="padding-bottom:50px;">
																			<label class="control-label" for="product_name">Growth Date</label>
																			<input id="growth_date<?php echo $growthList['gc_id']; ?>" name="growth_date" placeholder="Select Date" value="<?php echo date('m/d/Y',strtotime($growthList['growth_date']));?>" required class="form-control">
																			<script type="text/javascript">
																				$(document).ready(function(){
																				$('#growth_date<?php echo $growthList['gc_id']; ?>').datepicker({
																					todayBtn: "linked",
																					keyboardNavigation: false,
																					forceParse: false,
																					calendarWeeks: true,
																					autoclose: true
																				});
																				});
																			</script>
																			<label class="control-label" for="product_name">Height</label>
																			<input name="growth_height" type="text" placeholder=" " class="form-control" value="<?php echo $growthList['height']; ?>">
																			<label class="control-label" for="product_name">Weight</label>
																			<input name="growth_weight" type="text" placeholder=" " class="form-control" value="<?php echo $growthList['weight']; ?>">
																			<label class="control-label" for="product_name">Head Circumference</label>
																			<input name="growth_circum" type="text" placeholder=" " class="form-control" value="<?php echo $growthList['head_circum']; ?>">
																			<label class="control-label" for="product_name">Measurement</label>
																			<input name="growth_measure" type="text" placeholder=" " class="form-control" value="<?php echo $growthList['measurement']; ?>">
																		</div>
																		<div class="modal-footer">
																			<button type="submit" name="add_growthDtl" id="add_growthDtl" class="btn btn-success">Submit</button> 
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<?php } 
													}
													else { ?>
												<tr>
													<td colspan="5" style="text-align:center;">No result found</td>
												</tr>
												<?php }?>
											</tbody>
										</table>
										<!-- end user projects -->
									</div>
								</div>
							</div>
							<!-- END GROWTH SECTION -->
							<!-- STAR VIEW GROWTHCAHRT SECTION -->
							<div class="row white-bg page-heading" id="growthChartDisp">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-eyedropper"></i> View Growth Chart</h2>
								</div>
								<div class="row">
									<!--<h2 style="float:left;" class="m-l"><i class="fa fa-eyedropper"></i> Growth Chart Details</h2>-->
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<div id="chart-container">Height Charts will render here</div>
										<div id="chart-container1">Weight Charts will render here</div>
										<div id="chart-container2">Head Circumference Charts will render here</div>
									</div>
								</div>
							</div>
							<!-- END GROWTHCAHRT SECTION -->
							<!-- STAR VIEW GROWTHCAHRT SECTION -->
							<div class="row white-bg page-heading" id="vitalDetailSec">
								<div class="row">
									<h2 style="float:left;" class="m-l"><i class="fa fa-stethoscope"></i> Vital Details</h2>
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<form method="post" name="frmSendVideoCallRequest" autocomplete="off" action="add_details.php">
											<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>">
											<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
											<input id="dateadded" name="date_birth" type="hidden" value="<?php echo date('m/d/Y',strtotime($patient_tab[0]['DOB']));?>">
											<input id="edd" name="edd" type="hidden" value="<?php echo date('m/d/Y',strtotime($child_tab[0]['edd']));?>">
											<div class="row">
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Vital Date</label>
														<input id="vital_date" name="vital_date" value="" placeholder="Select Date" required class="form-control">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group">
														<label class="control-label" for="product_name">Heart Rate(Breaths per min)</label>
														<input name="heart_rate" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group">
														<label class="control-label" for="product_name">Respiratory Rate(Breaths per min)</label>
														<input name="respiratory_rate" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-2">
													<div class="form-group">
														<label class="control-label" for="product_name">Temperature(Farenheit)</label>
														<input name="temperature" type="text" placeholder=" " class="form-control" tabindex="8">
													</div>
												</div>
												<div class="col-sm-1">
													<div class="form-group" style="margin-top:5px;">
														<label class="control-label" for="status" ></label>
														<button type="submit" name="add_vitalDtl" id="add_vitalDtl" class="btn btn-outline btn-primary"><i class="fa fa-paper-plane" style="margin-right:10px;"></i>Add Vital Detail </button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="ibox-content m-b-sm border-bottom" style="margin-left:10px;margin-right:10px;">
										<!-- start user projects -->
										<table class="data table table-striped no-margin">
											<thead>
												<tr>
													<th>Date</th>
													<th>Heart Rate<br><span style="font-size:11px; color:#979797;">Breaths per min</span></th>
													<th>Respiratory Rate<br><span style="font-size:11px; color:#979797;">Breaths per min</span></th>
													<th>Temperature<br><span style="font-size:11px; color:#979797;">Farenheit</span></th>
													<th>Last Updated</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<?php 
													$vitalInfo = mysqlSelect("*","vital_signs","child_id='".$patient_tab[0]['patient_id']."'","","","","");
													
													foreach($vitalInfo as $vitalList){
													?>
												<tr>
													<td>
														<?php echo date('d-m-Y',strtotime($vitalList['vital_date'])); ?><!--<br><span style="font-size:11px;"><?php echo $vitalList['actual_age']; ?>M/<?php echo $vitalList['corrected_age']; ?>M</span>-->
													</td>
													<td><?php echo $vitalList['heart_rate']; ?></td>
													<td><?php echo $vitalList['respiratory_rate']; ?></td>
													<td class="hidden-phone"><?php echo $vitalList['temperature']; ?>F</td>
													<td class="hidden-phone">
														<?php 
															//To check last upadated User Type
																if($vitalList['user_type']==1){ //User Type 1 for Asha worker & 2 for Parents
																	$vitalGiven = mysqlSelect("partner_name","our_partners","partner_id='".$vitalList['user_id']."'","","","","");
																	$lastUpadatedUser=$vitalGiven[0]['partner_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($vitalList['created_date']));
																}
																else if($vitalList['user_type']==2){
																	$vitalGiven = mysqlSelect("mother_name","parents_tab","parent_id='".$vitalList['user_id']."'","","","","");
																	$lastUpadatedUser=$vitalGiven[0]['mother_name'];
																	$lastUpdatedDate=date('d-M-Y',strtotime($vitalList['created_date']));
																}
															?>
														<small><?php echo $lastUpadatedUser."<br>".$lastUpdatedDate; ?></small>
													</td>
													<td>
														<a href="#myModalVital<?php echo $vitalList['vs_id']; ?>" class="label label-primary pull-right" data-toggle="modal" ><i class="fa fa-edit"></i> - <small>Edit</small></a>
														<div id="myModalVital<?php echo $vitalList['vs_id']; ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
																		</button>
																		<h4 class="modal-title" id="myModalLabel">Edit Details</h4>
																	</div>
																	<form enctype="multipart/form-data" method="post" action="add_details.php" >
																		<input type="hidden" name="doc_id" value="<?php echo $admin_id; ?>">
																		<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
																		<input id="dateadded" name="date_birth" type="hidden" value="<?php echo date('m/d/Y',strtotime($patient_tab[0]['DOB']));?>">
																		<input id="edd" name="edd" type="hidden" value="<?php echo date('m/d/Y',strtotime($child_tab[0]['edd']));?>">
																		<div class="modal-body" style="padding-bottom:50px;">
																			<label class="control-label" for="product_name">Vital Date</label>
																			<input id="vital_date<?php echo $vitalList['vs_id']; ?>" name="vital_date"  placeholder="Select Date" required class="form-control" value="<?php echo date('m/d/Y',strtotime($vitalList['vital_date']));?>">
																			<script type="text/javascript">
																				$(document).ready(function(){
																				$('#vital_date<?php echo $vitalList['vs_id']; ?>').datepicker({
																					todayBtn: "linked",
																					keyboardNavigation: false,
																					forceParse: false,
																					calendarWeeks: true,
																					autoclose: true
																				});
																				});
																			</script>
																			<label class="control-label" for="product_name">Heart Rate(Breaths per min)</label>
																			<input name="heart_rate" type="text" placeholder=" " class="form-control" value="<?php echo $vitalList['heart_rate']; ?>">
																			<label class="control-label" for="product_name">Respiratory Rate(Breaths per min)</label>
																			<input name="respiratory_rate" type="text" placeholder=" " class="form-control" value="<?php echo $vitalList['respiratory_rate']; ?>">
																			<label class="control-label" for="product_name">Temperature(Farenheit)</label>
																			<input name="temperature" type="text" placeholder=" " class="form-control" value="<?php echo $vitalList['temperature']; ?>">
																		</div>
																		<div class="modal-footer">
																			<button type="submit" name="add_vitalDtl" id="add_vitalDtl" class="btn btn-success">Submit</button> 
																		</div>
																	</form>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<?php } ?>	
											</tbody>
										</table>
										<!-- end user projects -->
									</div>
								</div>
							</div>
							<!-- END VITAL SECTION -->
							<!--Data Modal Section -->
							<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content animated bounceInRight">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<!--
												<h4 class="modal-title">Blood Glucose chart</h4>
												<small class="font-bold">Patient Profile</small>
												
												
												<div id="morris-line-chart"></div>-->
										</div>
										<div class="modal-body">
											<div style="width:100%; height:400px;">
												<canvas id="canvas" ></canvas>
											</div>
											<br><br>
											<a href="javascript:void(0);" data-toggle="collapse" data-target="#demo" class="btn btn-primary pull-right" ></i> ADD</a>
											<div id="demo" class="collapse col-lg-6">
												<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddGlucoseCount" id="frmAddGlucoseCount">
													<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
													<table class="table table-bordered">
														<tr>
														<thead>
															<th colspan="2" class="text-center">Add Details</th>
														</thead>
														<tbody>
															<tr>
																<th>Date</th>
																<td>
																	<div class="input-group date">
																		<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="dateadded" type="text" required placeholder="Select date" class="form-control" >
																	</div>
																</td>
															</tr>
															<tr>
																<th>BLOOD GLUCOSE (Fasting)</th>
																<td><input type="number" id="before_meals" name="before_meals" value="" maxlength="3" class="form-control"></td>
															</tr>
															<tr>
																<th>BLOOD GLUCOSE (Post Prandial)</th>
																<td><input type="number" id="after_meals" name="after_meals" value="" maxlength="3" class="form-control"></td>
															</tr>
															<tr>
																<th>Systolic</th>
																<td><input name="systolicCount" type="text" class="form-control" ></td>
															</tr>
															<tr>
																<th>Diastolic</th>
																<td><input type="text" name="diastolicCount" value="" class="form-control"></td>
															</tr>
															<tr>
																<th>Glyco Hb(HbA1c)</th>
																<td><input type="text" name="hba1cCount" value="" class="form-control"></td>
															</tr>
															<tr>
																<th>HDL CHOLESTEROL</th>
																<td><input  name="hdlCount" type="text"  class="form-control" ></td>
															</tr>
															<tr>
																<th>VLDL</th>
																<td><input name="vldlCount" type="text" class="form-control" ></td>
															</tr>
															<tr>
																<th>LDL CHOLESTEROL</th>
																<td><input name="ldlCount" type="text"  class="form-control" ></td>
															</tr>
															<tr>
																<th>TRIGLYCERIDES</th>
																<td><input type="text" name="triglycerideCount" value="" class="form-control"></td>
															</tr>
															<tr>
																<th>TOTAL CHOLESTEROL</th>
																<td><input type="text" name="cholestrolCount"  value="" class="form-control"></td>
															</tr>
															<tr>
																<td colspan="2"><button type="submit" name="addPrandialCount" class="btn btn-primary pull-right">SUBMIT</button></td>
															</tr>
														</tbody>
													</table>
												</form>
											</div>
											<table class="table table-bordered">
												<tbody>
													<tr>
												<thead>
													<th>Medical Test</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisDate)){ ?>
													<th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th>
													<?php } ?>
												</thead>
												</tr>
												<tr>
													<th>Prescription Given</th>
													<?php while(list($key, $value) = each($get_medicineGivenDate)){ 
														$medicineGiven = mysqlSelect("a.prescription_trade_name as prescription_trade_name,a.duration as duration,a.prescription_frequency as prescription_frequency","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on a.episode_id=b.episode_id","b.patient_id='".$patient_id."' and DATE_FORMAT(a.prescription_date_time,'%Y-%m-%d')='".$value['date_added']."'","a.episode_prescription_id desc","","","");
														
														?>
													<td>
														<?php if($medicineGiven==true){ ?><a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $key; ?>" class="item">View Prescription</a>
														<div id="demo<?php echo $key; ?>" class="collapse">
															<?php $i=1;while(list($key_presc,$value_presc) = each($medicineGiven)) { ?>
															<small><?php echo $i; ?>.  </small><small><?php echo $value_presc['prescription_trade_name']; ?></small> | <small><?php echo $value_presc['prescription_frequency']; ?></small> | <small><?php echo $value_presc['duration']; ?></small>
															<br>
															<?php $i++; }  ?>
														</div>
														<?php } ?>
													</td>
													<?php } ?>
												</tr>
												<tr>
													<th>BLOOD GLUCOSE (Fasting)</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisPPCount)){ ?>
													<td><?php echo $value['bp_beforefood_count']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>BLOOD GLUCOSE (Post Prandial)</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisPPAfterCount)){ ?>
													<td><?php echo $value['bp_afterfood_count']; ?></td>
													<?php } ?>
												</tr>
												<th>Systolic</th>
												<?php while(list($key, $value) = each($get_TrendAnalysisSystolic)){ ?>
												<td><?php echo $value['systolic']; ?></td>
												<?php } ?>
												</tr>
												<tr>
													<th>Diastolic</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisDiastolic)){ ?>
													<td><?php echo $value['diastolic']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>Glyco Hb(HbA1c)</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisHbA1c)){ ?>
													<td><?php echo $value['HbA1c'];?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>HDL CHOLESTEROL</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisHDL)){ ?>
													<td><?php echo $value['HDL'];?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>VLDL</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisVLDL)){ ?>
													<td><?php echo $value['VLDL'];?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>LDL CHOLESTEROL</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisLDL)){ ?>
													<td><?php echo $value['LDL']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>TRIGLYCERIDES</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisTriglyceride)){ ?>
													<td><?php echo $value['triglyceride']; ?></td>
													<?php } ?>
												</tr>
												<tr>
													<th>TOTAL CHOLESTEROL</th>
													<?php while(list($key, $value) = each($get_TrendAnalysisCholesterol)){ ?>
													<td><?php echo $value['cholesterol'];?></td>
													<?php } ?>
												</tr>
												</tbody>
											</table>
										</div>
										<div class="modal-footer">
										</div>
									</div>
								</div>
							</div>
							<!-- END TREND ANALYSIS SECTION -->
							<!-- STAR CUSTOMIZED TREND ANALYSIS SECTION -->
							<div class="row white-bg page-heading" id="custom-view-trend-analysis" style="display:none;">
								<h2><i class="fa fa-line-chart"></i>Customized Trend Analysis</h2>
								<!--<a role="button" data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									<i class="fa fa-plus-square" style="font-size:20px;"></i> Medical Test</a>-->
								<div class="col-lg-12 " id="collapseOne" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<!--panel-collapse collapse-->
									<h4>Medical Tests</h4>
									<div id="medTestError" style="color:red; display:none;">
										<strong>Maximum of 10 medical tests can be added</strong>
									</div>
									<div class="input-group">
										<div id="investBefore">
											<?php $last_five_tst = mysqlSelect("a.trend_invest_id,a.doc_id,a.invest_id,b.test_name_site_name,a.invest_value,a.date_added","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","a.doc_id='".$admin_id."' and a.invest_value='0' and patient_id='".$patient_id."' and a.date_added='0000-00-00' and a.active_status='0'","a.trend_invest_id DESC","","","10");
												if(COUNT($last_five_tst)>0) { ?>
											<!--<label>Recently Added:  </label>-->
											<?php 
												foreach($last_five_tst as $last_five_tests_list){
												
												?>
											<input type='hidden' name='investID[]' value='<?php echo $last_five_tests_list['trend_invest_id']; ?>' /><span class='tag label label-primary m-r' style="display:inline-block;"><?php echo $last_five_tests_list['test_name_site_name']; ?><a data-role='remove' class='text-white del_trend_invest m-l' data-invest-id='<?php echo $last_five_tests_list['trend_invest_id']; ?>' data-patient-id='<?php echo $patient_id; ?>'>x</a></span>
											<!--<a class="btn btn-xs btn-white m-l get_invest_test_prior" data-main-test-id="<?php echo $last_five_tests_list['main_test_id']; ?>" title="<?php echo $last_five_tests_list['test_name_site_name']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo substr($last_five_tests_list['test_name_site_name'],0,15); ?></code></a>-->
											<?php }
												} ?>
										</div>
									</div>
									<br>
									<div class="input-group">
										<input type="text" id="get_invest_test" placeholder="Add / Search test here..." data-episode-id="0"  data-patient-id="<?php echo $patient_id; ?>" name="searchInvestTest" value="" class="form-control input-lg searchInvestTest" tabindex="2">
										<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
											</button>
										</div>
									</div>
									<br/>
									<div class="input-group">
									</div>
									<br/>
								</div>
								<div class="m-t m-b" style="margin-bottom:120px;">
									<div id="before-status">
										<div style="width:100%; height:400px;">
											<canvas id="canvasCustomTrend" ></canvas>
										</div>
									</div>
									<div id="after-status"></div>
								</div>
								<br/><br/><br/><br/>
								<br><br><br><br><br><br><br><br><br><br><br><br>
								<a href="javascript:void(0);" data-toggle="collapse" data-target="#demoTrend" class="btn btn-primary pull-right m-t m-b" ></i> ADD</a>
								<br><br><br><br>
								<div id="demoTrend" class="collapse col-lg-10 m-t m-b">
									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"   autocomplete="off" name="frmCustomTrend" id="frmCustomTrend">
										<input type="hidden" name="custom_patient_id" id="custom_patient_id" value="<?php echo $patient_id ?>">
										<div id="addTrendDet">
											<table class="table table-bordered" id="addTrendTable">
												<tr>
												<thead>
													<th colspan="2" class="text-center">Add Details</th>
												</thead>
												<tbody>
													<?php
														$getInvestigate= mysqlSelect("a.trend_invest_id,a.doc_id,a.invest_id,b.test_name_site_name,a.invest_value,a.date_added","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","a.doc_id='".$admin_id."' and patient_id='".$patient_id."'  and a.invest_value='0' and a.date_added='0000-00-00' and a.active_status='0'","","","","");
														
														$get_CustomTrendAnalysisDate = mysqlSelect("date_added","trend_analysis_investigations","patient_id='".$patient_id."' and date_added!='0000-00-00' and active_status='0'","date_added desc","date_added","","0,8");
														
														$get_CustommedicineGivenDate = mysqlSelect("date_added","trend_analysis_investigations","patient_id='".$patient_id."' and date_added!='0000-00-00' and active_status='0'","date_added desc","date_added","","0,8");
														
														$get_CustomTrendAnalysisPPCount = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[0]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														
														$get_CustomTrendAnalysisPPAfterCount = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[1]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisSystolic = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[2]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisDiastolic = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[3]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisHbA1c = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[4]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisHDL = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[5]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisVLDL = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[6]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisLDL = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[7]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisTriglyceride = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[8]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														$get_CustomTrendAnalysisCholesterol = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[9]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added desc","","","0,8");
														?>	
													<tr>
														<th>Date</th>
														<td>
															<div class="input-group date">
																<!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>--><input id="custom_dateadded" name="custom_dateadded" type="text" required placeholder="DD/MM/YYYY" class="form-control" >
															</div>
														</td>
													</tr>
													<?php  if(!empty($getInvestigate[0]['test_name_site_name'])){?>				
													<tr>
														<th><?php echo $getInvestigate[0]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id0" name="invest_id0" value="<?php echo $getInvestigate[0]['invest_id']; ?>"><input type="number" id="custom_before_meals" name="custom_before_meals" value=""  style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>	
													<tr>
														<th><?php echo $getInvestigate[1]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id1" name="invest_id1" value="<?php echo $getInvestigate[1]['invest_id']; ?>"><input type="number" id="custom_after_meals" name="custom_after_meals" value=""  style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[2]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id2" name="invest_id2" value="<?php echo $getInvestigate[2]['invest_id']; ?>"><input id="custom_systolicCount" name="custom_systolicCount" type="number" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[3]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id3" name="invest_id3" value="<?php echo $getInvestigate[3]['invest_id']; ?>"><input type="number" id="custom_diastolicCount" name="custom_diastolicCount" value="" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>	
													<tr>
														<th><?php echo $getInvestigate[4]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id4" name="invest_id4" value="<?php echo $getInvestigate[4]['invest_id']; ?>"><input type="number" id="custom_hba1cCount" name="custom_hba1cCount" value="" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[5]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id5" name="invest_id5" value="<?php echo $getInvestigate[5]['invest_id']; ?>"><input  id="custom_hdlCount" name="custom_hdlCount" type="number" style="width:52%"  class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[6]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id6" name="invest_id6" value="<?php echo $getInvestigate[6]['invest_id']; ?>"><input id="custom_vldlCount"  name="custom_vldlCount" type="number" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[7]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id7" name="invest_id7" value="<?php echo $getInvestigate[7]['invest_id']; ?>"><input id="custom_ldlCount" name="custom_ldlCount" type="number" style="width:52%"  class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[8]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id8" name="invest_id8" value="<?php echo $getInvestigate[8]['invest_id']; ?>"><input type="number" id="custom_triglycerideCount" name="custom_triglycerideCount" value="" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>
													<tr>
														<th><?php echo $getInvestigate[9]['test_name_site_name']; ?></th>
														<td><input type="hidden" id="invest_id9" name="invest_id9" value="<?php echo $getInvestigate[9]['invest_id']; ?>"><input type="number" id="custom_cholestrolCount" name="custom_cholestrolCount"  value="" style="width:52%" class="form-control" step="any"></td>
													</tr>
													<?php } ?>
													<tr>
														<td colspan="2"><button type="submit" name="addTrendAnalyseCount" class="btn btn-primary pull-right">SUBMIT</button></td>
													</tr>
												</tbody>
											</table>
										</div>
									</form>
								</div>
								<div claas="form-control m-t-xs" style="margin-top:30px;" id="viewTrendDiv">
									<table class="table table-responsive table-bordered" id="tableViewTrend">
										<tbody>
											<tr>
										<thead>
											<th>Medical Test</th>
											<?php while(list($key, $value) = each($get_CustomTrendAnalysisDate)){ ?>
											<th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th>
											<?php } ?>
										</thead>
										</tr>
										<tr>
											<th>Prescription Given</th>
											<?php while(list($key, $value) = each($get_CustommedicineGivenDate)){ 
												$medicineGiven = mysqlSelect("a.prescription_trade_name as prescription_trade_name,a.duration as duration,a.prescription_frequency as prescription_frequency","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on a.episode_id=b.episode_id","b.patient_id='".$patient_id."' and DATE_FORMAT(a.prescription_date_time,'%Y-%m-%d')='".$value['date_added']."'","a.episode_prescription_id desc","","","4");
												   //$getInvestigation= $objQuery->mysqlSelect("a.trend_invest_id,a.doc_id,a.invest_id,b.test_name_site_name","trend_analysis_investigations as a left join patient_diagnosis_tests as b on a.invest_id=b.id","a.doc_id='".$admin_id."'","","","","");
												?>
											<td>
												<?php if($medicineGiven==true){ ?><!--<a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $key; ?>" class="item">View Prescription</a>
													<div id="demo<?php echo $key; ?>" class="collapse">-->
												<?php $i=1;while(list($key_presc,$value_presc) = each($medicineGiven)) { ?>
												<small><b><?php echo $i; ?>.</b>  </small><small><b><?php echo $value_presc['prescription_trade_name']; ?></b></small> | <small><?php echo $value_presc['prescription_frequency']; ?></small> | <small><?php echo $value_presc['duration']; ?></small>
												<br>
												<?php $i++; }  ?>
												<!--</div>-->
												<?php } ?>
											</td>
											<?php } ?>
										</tr>
										<?php  if(!empty($getInvestigate[0]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[0]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0; for($i=0;$i<count($get_CustomTrendAnalysisPPCount);$i++){ if($get_CustomTrendAnalysisPPCount[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisPPCount[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[1]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisPPAfterCount);$i++){if($get_CustomTrendAnalysisPPAfterCount[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisPPAfterCount[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[2]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisSystolic);$i++){if($get_CustomTrendAnalysisSystolic[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisSystolic[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[3]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisDiastolic);$i++){if($get_CustomTrendAnalysisDiastolic[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisDiastolic[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[4]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisHbA1c);$i++){if($get_CustomTrendAnalysisHbA1c[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisHbA1c[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[5]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){ $j=0;for($i=0;$i<count($get_CustomTrendAnalysisHDL);$i++){if($get_CustomTrendAnalysisHDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisHDL[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[6]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisVLDL);$i++){if($get_CustomTrendAnalysisVLDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisVLDL[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[7]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisLDL);$i++){if($get_CustomTrendAnalysisLDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisLDL[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[8]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisTriglyceride);$i++){if($get_CustomTrendAnalysisTriglyceride[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisTriglyceride[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>
										<tr>
											<th><?php echo $getInvestigate[9]['test_name_site_name']; ?></th>
											<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisCholesterol);$i++){if($get_CustomTrendAnalysisCholesterol[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
											<td><?php echo $get_CustomTrendAnalysisCholesterol[$i]['invest_value']; ?></td>
											<?php $j++;}} if($j==0){?>
											<td></td>
											<?php }?><?php } ?>
										</tr>
										<?php }?>
										</tbody>
									</table>
								</div>
							</div>
							<!--Data Modal Section -->
							<div class="modal inmodal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content animated bounceInRight">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<!--
												<h4 class="modal-title">Blood Glucose chart</h4>
												<small class="font-bold">Patient Profile</small>
												
												
												<div id="morris-line-chart"></div>-->
										</div>
										<div class="modal-body">
											<div id="before-status">
												<div style="width:100%; height:400px;" >
													<canvas id="canvasCustomTrend" ></canvas>
												</div>
											</div>
											<div id="after-status"></div>
											<br/><br/><br/><br/>
											<br><br>
											<a href="javascript:void(0);" data-toggle="collapse" data-target="#demoTrend" class="btn btn-primary pull-right" ></i> ADD</a>
											<div id="demoTrend" class="collapse col-lg-6">
												<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"   name="frmCustomTrend" id="frmCustomTrend">
													<input type="hidden" name="custom_patient_id" value="<?php echo $patient_id ?>">
													<table class="table table-bordered" >
														<tr>
														<thead>
															<th colspan="2" class="text-center">Add Details</th>
														</thead>
														<tbody>
															<tr>
																<th>Date</th>
																<td>
																	<div class="input-group date">
																		<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="custom_dateadded" name="custom_dateadded" type="text" required placeholder="Select date" class="form-control" >
																	</div>
																</td>
															</tr>
															<script>
																$(document).ready(function() {
																
																	$('#custom_dateadded').datepicker({
																		todayBtn: "linked",
																		keyboardNavigation: false,
																		forceParse: false,
																		calendarWeeks: true,
																		autoclose: true
																	});	
																});
															</script>
															<?php if(!empty($getInvestigate[0]['test_name_site_name'])){?>				
															<tr>
																<th><?php echo $getInvestigate[0]['test_name_site_name']; ?></th>
																<td><input type="number" id="custom_before_meals" name="custom_before_meals" value=""  class="form-control"></td>
															</tr>
															<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[1]['test_name_site_name']; ?></th>
																<td><input type="number" id="custom_after_meals" name="custom_after_meals" value=""  class="form-control"></td>
															</tr>
															<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[2]['test_name_site_name']; ?></th>
																<td><input name="custom_systolicCount" type="number" class="form-control" ></td>
															</tr>
															<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[3]['test_name_site_name']; ?></th>
																<td><input type="number" name="custom_diastolicCount" value="" class="form-control"></td>
															</tr>
															<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[4]['test_name_site_name']; ?></th>
																<td><input type="number" name="custom_hba1cCount" value="" class="form-control"></td>
															</tr>
															<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[5]['test_name_site_name']; ?></th>
																<td><input  name="custom_hdlCount" type="number"  class="form-control" ></td>
															</tr>
															<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[6]['test_name_site_name']; ?></th>
																<td><input name="custom_vldlCount" type="number" class="form-control" ></td>
															</tr>
															<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[7]['test_name_site_name']; ?></th>
																<td><input name="custom_ldlCount" type="number"  class="form-control" ></td>
															</tr>
															<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[8]['test_name_site_name']; ?></th>
																<td><input type="number" name="custom_triglycerideCount" value="" class="form-control"></td>
															</tr>
															<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>
															<tr>
																<th><?php echo $getInvestigate[9]['test_name_site_name']; ?></th>
																<td><input type="number" name="custom_cholestrolCount"  value="" class="form-control"></td>
															</tr>
															<?php }?>
															<tr>
																<td colspan="2"><button type="submit" name="addTrendAnalyseCount" class="btn btn-primary pull-right">SUBMIT</button></td>
															</tr>
														</tbody>
													</table>
												</form>
											</div>
											<table class="table table-bordered">
												<tbody>
													<tr>
												<thead>
													<th>Medical Test</th>
													<?php while(list($key, $value) = each($get_CustomTrendAnalysisDate)){ ?>
													<th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th>
													<?php } ?>
												</thead>
												</tr>
												<tr>
													<th>Prescription Given</th>
													<?php while(list($key, $value) = each($get_CustommedicineGivenDate)){ 
														$medicineGiven = mysqlSelect("a.prescription_trade_name as prescription_trade_name,a.duration as duration,a.prescription_frequency as prescription_frequency","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on a.episode_id=b.episode_id","b.patient_id='".$patient_id."' and DATE_FORMAT(a.prescription_date_time,'%Y-%m-%d')='".$value['date_added']."'","a.episode_prescription_id desc","","","");
														
														?>
													<td>
														<?php if($medicineGiven==true){ ?><a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $key; ?>" class="item">View Prescription</a>
														<div id="demo<?php echo $key; ?>" class="collapse">
															<?php $i=1;while(list($key_presc,$value_presc) = each($medicineGiven)) { ?>
															<small><?php echo $i; ?>.  </small><small><?php echo $value_presc['prescription_trade_name']; ?></small> | <small><?php echo $value_presc['prescription_frequency']; ?></small> | <small><?php echo $value_presc['duration']; ?></small>
															<br>
															<?php $i++; }  ?>
														</div>
														<?php } ?>
													</td>
													<?php } ?>
												</tr>
												<?php  if(!empty($getInvestigate[0]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[0]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0; for($i=0;$i<count($get_CustomTrendAnalysisPPCount);$i++){ if($get_CustomTrendAnalysisPPCount[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisPPCount[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[1]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisPPAfterCount);$i++){if($get_CustomTrendAnalysisPPAfterCount[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisPPAfterCount[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[2]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisSystolic);$i++){if($get_CustomTrendAnalysisSystolic[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisSystolic[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[3]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisDiastolic);$i++){if($get_CustomTrendAnalysisDiastolic[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisDiastolic[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[4]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisHbA1c);$i++){if($get_CustomTrendAnalysisHbA1c[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisHbA1c[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[5]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){ $j=0;for($i=0;$i<count($get_CustomTrendAnalysisHDL);$i++){if($get_CustomTrendAnalysisHDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisHDL[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[6]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisVLDL);$i++){if($get_CustomTrendAnalysisVLDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisVLDL[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[7]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisLDL);$i++){if($get_CustomTrendAnalysisLDL[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisLDL[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[8]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisTriglyceride);$i++){if($get_CustomTrendAnalysisTriglyceride[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisTriglyceride[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>
												<tr>
													<th><?php echo $getInvestigate[9]['test_name_site_name']; ?></th>
													<?php for($k=0;$k<count($get_CustomTrendAnalysisDate);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisCholesterol);$i++){if($get_CustomTrendAnalysisCholesterol[$i]['date_added']==$get_CustomTrendAnalysisDate[$k]['date_added']){?>
													<td><?php echo $get_CustomTrendAnalysisCholesterol[$i]['invest_value']; ?></td>
													<?php $j++;}} if($j==0){?>
													<td></td>
													<?php }?><?php } ?>
												</tr>
												<?php }?>
												</tbody>
											</table>
										</div>
										<div class="modal-footer">
										</div>
									</div>
								</div>
							</div>
							<!-- END CUSTOMIZED TREND ANALYSIS SECTION -->
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
											<dt>BP:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['pat_bp']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_bp" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_bp">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['pat_bp']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_bp">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_bp"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_bp">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_bp">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>
											</dd>
											<br>
											<dt>Asthama:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['pat_asthama']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_astha" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_astha">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['pat_asthama']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_astha">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_astha"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_astha">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_astha">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>
											</dd>
											<br>
											<dt>Epilepsy:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['pat_epilepsy']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_epil" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_epil">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['pat_epilepsy']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_epil">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_epil"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_epil">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_epil">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>
											</dd>
											<!--dt>Smoking:</dt>
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
											</dd-->
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
											<dt>Cholestrol:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['pat_cholestrole']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_chol" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_chol">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['pat_cholestrole']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_chol">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo $patient_id ?>" id="inlineRadio2" value="2" name="se_chol"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_chol">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_chol">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>  
											</dd>
											<br>
											<dt>Thyroid:</dt>
											<br> 
											<dd>
												<?php if($patient_tab[0]['pat_thyroid']=="1"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_thyr" checked="">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_thyr">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else if($patient_tab[0]['pat_thyroid']=="2"){ ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_thyr">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo $patient_id ?>" id="inlineRadio2" value="2" name="se_thyr"  checked="">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } else { ?>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio1" value="1" name="se_thyr">
													<label for="inlineRadio1"> Yes </label>
												</div>
												<div class="radio radio-info radio-inline">
													<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($patient_id); ?>" id="inlineRadio2" value="2" name="se_thyr">
													<label for="inlineRadio2"> No </label>
												</div>
												<?php } ?>  
											</dd>
											<br>
											<dt>Alergies if any...</dt>
											<br> 
											<dd>
												<textarea class="form-control otherDetail" id=""  name="other_details" rows="4" value="" placeholder="Alergies if any..."></textarea>
											</dd>
											<!--dt>Alcohol:</dt>
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
											</dd-->
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
												$getAllergyRes= mysqlSelect("*","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and doc_id ='".$admin_id."' and doc_type='1' and status='0'","","","","");
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
										<?php $last_five_drugs = mysqlSelect("drug_abuse_id","doctor_frequent_drug_abuse","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","5");
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
												$getDrugRes= mysqlSelect("b.drug_abuse as drug_abuse,a.drug_active_id as drug_active_id","doc_patient_drug_active as a left join drug_abuse_auto as b on a.drug_abuse_id=b.drug_abuse_id","a.doc_id='".$admin_id."' and a.patient_id='".$patient_id."' and a.doc_type='1' and a.status='0'","","","","");
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
										<?php $last_five_history = mysqlSelect("family_history_id","doctor_frequent_family_history","doc_id='".$admin_id."' and doc_type='1'","freq_count DESC","","","5");
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
												$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","a.doc_id='".$admin_id."' and a.patient_id='".$patient_id."' and a.doc_type='1' and a.status='0'","","","","");
												
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
													<?php 
													$get_cardio_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_tab[0]['patient_id'] ."' and episode_id='". $patient_episode_val['episode_id']."' and department='1'","","","","");
														
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
															<?php $getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
																$getDiagnostic= mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.doc_id='".$admin_id."'  or b.company_id='".$getDocDetails[0]['company_id']."'","b.doc_diagno_id desc","","","");
																		
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
															<?php $getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
																$getPharma= mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.doc_id='".$admin_id."' or b.company_id='".$getDocDetails[0]['company_id']."'","b.doc_pharma_id desc","","","");
																	
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
												<td class="text-right">
													<div class="btn-group">
														<!--<a href="#" class="delEpisode" data-episode-id="<?php echo md5($patient_episode_val['episode_id']); ?>" data-print-content="<?php echo "Visit ".$visit_count; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-trash"></i> Delete</a>-->
														<a href="print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-print"></i> Print</a>
														<a href="My-Patient-Details?p=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>&visit=<?php echo $visit_count; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-edit"></i> Edit</a>
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
						$commentsCount= mysqlSelect("*","patient_internal_notes","doc_id='".$admin_id."' and patient_id='".$patient_tab[0]['patient_id']."'","","","","");
						if(COUNT($commentsCount)>0){
						?>
					<span class="badge badge-warning pull-right"><?= COUNT($commentsCount);?></span><?php } ?>
					<a class="open-small-chat">
					<i class="fa fa-sticky-note-o" style="font-size:13px;"></i>
					</a>
				</div>
				<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
				<script type="text/javascript">
					function getPatientDet(srchText)
					{
						var params     = srchText.split("-");
					    if(!isNaN(params[0]))
						{
							document.frmchangePatient.cmdchangePatient.value="submit";
							document.frmchangePatient.slct_valPat.value=srchText;
							document.frmchangePatient.submit();
						}		
					}
					
					
					$(document).ready(function () {
					//Disable cut copy paste
					/*$('body').bind('cut copy paste', function (e) {
					e.preventDefault();
					});*/
					var s=window.location.href;
					var n = s.indexOf('My-Patient-Details?');
					//var n = s.indexOf('my_patient_profile_raksha.php?');
					var m = s.indexOf('Quick-Prescription?');
					s = s.substring(0, n != -1 ? n : s.length);
					s = s.substring(0, m != -1 ? m : s.length);
					var hostUrl = s;
					
					//Disable mouse right click
					$("body").on("contextmenu",function(e){
					return false;
					});				 
					
					$('.open-small-chat').click(function(){
					 $(this).find('i').toggleClass('fa-sticky-note-o fa-comments fa-times');
					});
					
					$("#get_invest_test").on("blur", function(){
					var countInvest=<?php echo count($last_five_tst);?>;
					if(countInvest<10){
					var patientid = $(this).attr("data-patient-id");
					var investid = $(this).val();
					var episodeid = $(this).attr("data-episode-id");
					
					var url = hostUrl+"get_trend_analysis.php?investid="+investid+"&patientid="+patientid;
					
					if(investid!=""){
					$("#before-status").hide();
					$.get(url, function(response) {
						
						$("#investBefore").html("").prepend(response);
						$("#addTrendDet").load(" #addTrendTable");
						$("#viewTrendDiv").load(" #tableViewTrend");
						
						//location.reload();
						var canvasurl = hostUrl+"get_trend_analysis.php?pat_id="+patientid;
					$.get(canvasurl, function(response) {
					
						$("#after-status").html("").prepend(response);
						
						
						//location.reload();
					});
						
					});
					$('.searchInvestTest').val(''); //Clear search field
					
					
					//$("#get_complaints").focus();	//focus search filed 
					return false;
					}
					}
					else{
					$("#medTestError").css("display","block");
					}
					});
					
					
					$("#get_invest_test").keydown(function(e) {
					
					if(e.originalEvent.keyCode == 13){ //If User press enter key
					 var countInvest=<?php echo count($last_five_tst);?>;
					
					if(countInvest<10){
					var patientid = $(this).attr("data-patient-id");
					var investid = $(this).val();
					var episodeid = $(this).attr("data-episode-id");
					
					var url = hostUrl+"get_trend_analysis.php?investid="+investid+"&patientid="+patientid;
					
					if(investid!=""){
					$("#before-status").hide();
					$.get(url, function(response) {
					
						$("#investBefore").html("").prepend(response);
						$("#addTrendDet").load(" #addTrendTable");
						$("#viewTrendDiv").load(" #tableViewTrend");
						
						
						//location.reload();
						var canvasurl = hostUrl+"get_trend_analysis.php?pat_id="+patientid;
					$.get(canvasurl, function(response) {
					
						$("#after-status").html("").prepend(response);
						
						
						//location.reload();
					});
					});
					$('.searchInvestTest').val(''); //Clear search field
					
					
					
					return false;
					}
					}
					else{
					 $("#medTestError").css("display","block");
					}
					}
					
					});
					
												
					
					
					$("body").on("click", ".del_trend_invest", function() {
					 $("#medTestError").css("display","none");
					 var patientid = $(this).attr("data-patient-id");
					var investid = $(this).attr("data-invest-id");
					var url = hostUrl+"get_trend_analysis.php?delinvestid="+investid;
					//console.log(sympid, url);
					var parentRemove=$(this);
					if(investid == "") {
					return false;
					} else {
					
					swal({
					             title: "Are you sure?",
					             text: "You want to delete this medical test!",
					             type: "warning",
					             showCancelButton: true,
					             confirmButtonColor: "#DD6B55",
					             confirmButtonText: "Yes, delete it!",
					             closeOnConfirm: false
					         }, function () {
						
						parentRemove.parent("span").remove();
						$("#before-status").hide();
						$.get(url, function(response) {
						//console.log(response);
						$("#addTrendDet").load(" #addTrendTable");
						$("#viewTrendDiv").load(" #tableViewTrend");
					  // getCanvas();
					     //location.reload();
					  var canvasurl = hostUrl+"get_trend_analysis.php?pat_id="+patientid;
					$.get(canvasurl, function(response) {
					
						$("#after-status").html("").prepend(response);
						
						
						//location.reload();
					});
							
					});
					             swal("Deleted!", "Selected Medical Test has been deleted.", "success");
					         });
					
					
					
					}
					
					
					});
					
					$("#customTrendClick").click(function(){
					$("#view-trend-analysis").css("display","none");
					$("#custom-view-trend-analysis").css("display","block");
					
					});
					
					$('#frmCustomTrend').on('submit', function(event){
					event.preventDefault();
					var custom_dateadded = $("#custom_dateadded").val();
					var before_meals_count = $("#custom_before_meals").val();
					var after_meals_count = $("#custom_after_meals").val();
					var systolicCount = $("#custom_systolicCount").val();
					var diastolicCount = $("#custom_diastolicCount").val();
					var hba1cCount = $("#custom_hba1cCount").val();
					var hdlCount = $("#custom_hdlCount").val();
					var vldlCount = $("#custom_vldlCount").val();
					var ldlCount = $("#custom_ldlCount").val();
					var triglycerideCount = $("#custom_triglycerideCount").val();
					var cholestrolCount = $("#custom_cholestrolCount").val();
					var patient_id = $("#custom_patient_id").val();
					
					var invest_id0 = $("#invest_id0").val();
					var invest_id1 = $("#invest_id1").val();
					var invest_id2 = $("#invest_id2").val();
					var invest_id3 = $("#invest_id3").val();
					var invest_id4 = $("#invest_id4").val();
					var invest_id5 = $("#invest_id5").val();
					var invest_id6 = $("#invest_id6").val();
					var invest_id7 = $("#invest_id7").val();
					var invest_id8 = $("#invest_id8").val();
					var invest_id9 = $("#invest_id9").val();
					
					var dataValue='addTrendAnalyseCount=true&custom_dateadded='+custom_dateadded+'&custom_before_meals='+before_meals_count+'&custom_after_meals='+after_meals_count+'&custom_systolicCount='+systolicCount+'&custom_diastolicCount='+diastolicCount+'&custom_hba1cCount='+hba1cCount+'&custom_hdlCount='+hdlCount+'&custom_vldlCount='+vldlCount+'&custom_ldlCount='+ldlCount+'&custom_triglycerideCount='+triglycerideCount+'&custom_cholestrolCount='+cholestrolCount+'&custom_patient_id='+patient_id+'&invest_id0='+invest_id0+'&invest_id1='+invest_id1+'&invest_id2='+invest_id2+'&invest_id3='+invest_id3+'&invest_id4='+invest_id4+'&invest_id5='+invest_id5+'&invest_id6='+invest_id6+'&invest_id7='+invest_id7+'&invest_id8='+invest_id8+'&invest_id9='+invest_id9;
						
					$.ajax({
					type: "POST",
					url: "get_trend_analysis.php",
					data:dataValue,
					success: function(data){
						$("#before-status").hide();
						$("#addTrendDet").load(" #addTrendTable");
						$("#viewTrendDiv").load(" #tableViewTrend");
						$('.collapse').collapse('hide');
						
					  // getCanvas();
					     //location.reload();
					  var canvasurl = hostUrl+"get_trend_analysis.php?pat_id="+patient_id;
					$.get(canvasurl, function(response) {
					
						$("#after-status").html("").prepend(response);
						
						
						//location.reload();
					});
						
					}
					});
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
					
					$(document).on('click', '#custom_dateadded', function(){
					$(this).datepicker({
					    todayBtn: "linked",
					             keyboardNavigation: false,
					             forceParse: false,
					             calendarWeeks: true,
					             autoclose: true
					}).focus();
					
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
			        autoclose: true,
			    });
			
			$('#custom_dateadded').datepicker({
			        todayBtn: "linked",
			        keyboardNavigation: false,
			        forceParse: false,
			        calendarWeeks: true,
			        autoclose: true
			    });						   
			$('#dateadded1').datepicker({
		        startDate: new Date(),
		        keyboardNavigation: false
			});
			$('#vid_date').datepicker({
			        keyboardNavigation: false,
			        forceParse: false,
			        calendarWeeks: true,
			        autoclose: true
			    });
			$('#growth_date').datepicker({
			        keyboardNavigation: false,
			        forceParse: false,
			        calendarWeeks: true,
			        autoclose: true
			    });
			$('#vital_date').datepicker({
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
				//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
				$get_PatientDetails = mysqlSelect("a.patient_id as patient_id ,a.patient_name as patient_name ,a.patient_mob as patient_mobile ","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
														
				?>
			    $('.typeahead_1').typeahead({
			       source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; } ?>]
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
<?php
	$get_TrendAnalysisDate1 = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisPPCount1 = mysqlSelect("bp_beforefood_count","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisPPAfterCount1 = mysqlSelect("bp_afterfood_count","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisSystolic1 = mysqlSelect("systolic","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisDiastolic1 = mysqlSelect("diastolic","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisHbA1c1 = mysqlSelect("HbA1c","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisHDL1 = mysqlSelect("HDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisVLDL1 = mysqlSelect("VLDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisLDL1 = mysqlSelect("LDL","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisTriglyceride1 = mysqlSelect("triglyceride","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	$get_TrendAnalysisCholesterol1 = mysqlSelect("cholesterol","trend_analysis","patient_id='".$patient_id."' and patient_type = '1'","trend_id asc","","","0,5");
	
	$get_CustomTrendAnalysisDate1 = mysqlSelect("date_added","trend_analysis_investigations","patient_id='".$patient_id."' and date_added!='0000-00-00' and active_status='0'","date_added asc","date_added","","0,8");
	$get_CustomTrendAnalysisPPCount1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[0]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisPPAfterCount1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[1]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisSystolic1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[2]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisDiastolic1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[3]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisHbA1c1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[4]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisHDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[5]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisVLDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[6]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisLDL1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[7]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisTriglyceride1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[8]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
	$get_CustomTrendAnalysisCholesterol1 = mysqlSelect("invest_value,date_added","trend_analysis_investigations","patient_id='".$patient_id."' and invest_id = '".$getInvestigate[9]['invest_id']."' and date_added!='0000-00-00' and active_status='0'","date_added asc","","","0,8");
		
	$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","","language_id desc","","","");
	?>
<script>
	var config = {
		type: 'line',
		data: {
			labels: [<?php while(list($key, $value) = each($get_TrendAnalysisDate1)){ echo $dateAdded= "'".date('d-M-Y',strtotime($value['date_added']))."',"; } ?>],
			datasets: [{
				label: 'Pre-Prandial Count',
				backgroundColor: window.chartColoQARred,
				borderColor: window.chartColoQARred,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisPPCount1)){ echo $value['bp_beforefood_count'].","; } ?>],
				//data: [120,124,122,126,],
			}, {
				label: 'Post-Prandial Count',
				backgroundColor: window.chartColoQARblue,
				borderColor: window.chartColoQARblue,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisPPAfterCount1)){ echo $value['bp_afterfood_count'].","; } ?>],
				//data: [120,124,122,126,],
			}, {
				label: 'Systolic',
				backgroundColor: window.chartColoQARorange,
				borderColor: window.chartColoQARorange,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisSystolic1)){ echo $value['systolic'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'Diastolic',
				backgroundColor: window.chartColoQARmediumaquamarine,
				borderColor: window.chartColoQARmediumaquamarine,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisDiastolic1)){ echo $value['diastolic'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'HbA1c',
				backgroundColor: window.chartColoQARpurple,
				borderColor: window.chartColoQARpurple,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisHbA1c1)){ echo $value['HbA1c'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'HDL',
				backgroundColor: window.chartColoQARthistle,
				borderColor: window.chartColoQARthistle,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisHDL1)){ echo $value['HDL'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'VLDL',
				backgroundColor: window.chartColoQARsienna,
				borderColor: window.chartColoQARsienna,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisVLDL1)){ echo $value['VLDL'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'LDL',
				backgroundColor: window.chartColoQARteal,
				borderColor: window.chartColoQARteal,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisLDL1)){ echo $value['LDL'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'Triglycerides',
				backgroundColor: window.chartColoQARyellow,
				borderColor: window.chartColoQARyellow,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisTriglyceride1)){ echo $value['triglyceride'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'Total Cholesterol',
				backgroundColor: window.chartColoQARgreen,
				borderColor: window.chartColoQARgreen,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_TrendAnalysisCholesterol1)){ echo $value['cholesterol'].","; } ?>],
				
				//data: [120,124,122,126,],
			}]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Trend Analysis'
			},
			scales: {
				xAxes: [{
					display: true,
				}],
				yAxes: [{
					display: true,
					type: 'logarithmic',
				}]
			}
		}
	};
	
	var configuration = {
		type: 'line',
		data: {
			labels: [<?php while(list($key, $value) = each($get_CustomTrendAnalysisDate1)){ echo $dateAdded= "'".date('d-M-Y',strtotime($value['date_added']))."',"; } ?>],
			datasets: [<?php  if(!empty($getInvestigate[0]['test_name_site_name'])){?>{
				label: <?php echo "'".$getInvestigate[0]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARred,
				borderColor: window.chartColoQARred,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0; for($i=0;$i<count($get_CustomTrendAnalysisPPCount1);$i++){ if($get_CustomTrendAnalysisPPCount1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisPPCount1[$i]['invest_value'].","; $j++;}} if($j==0){  echo '0'.','; }?><?php } ?>],
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[1]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[1]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARblue,
				borderColor: window.chartColoQARblue,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisPPAfterCount1);$i++){if($get_CustomTrendAnalysisPPAfterCount1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisPPAfterCount1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[2]['test_name_site_name'])){?>, {
				
				label: <?php echo "'".$getInvestigate[2]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARorange,
				borderColor: window.chartColoQARorange,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisSystolic1);$i++){if($get_CustomTrendAnalysisSystolic1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisSystolic1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
				
		}<?php } if(!empty($getInvestigate[3]['test_name_site_name'])){?>, {
				
				label: <?php echo "'".$getInvestigate[3]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARmediumaquamarine,
				borderColor: window.chartColoQARmediumaquamarine,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisDiastolic1);$i++){if($get_CustomTrendAnalysisDiastolic1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisDiastolic1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[4]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[4]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARpurple,
				borderColor: window.chartColoQARpurple,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisHbA1c1);$i++){if($get_CustomTrendAnalysisHbA1c1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisHbA1c1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[5]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[5]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARthistle,
				borderColor: window.chartColoQARthistle,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){ $j=0;for($i=0;$i<count($get_CustomTrendAnalysisHDL1);$i++){if($get_CustomTrendAnalysisHDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisHDL1[$i]['invest_value'].",";  $j++;}} if($j==0){echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[6]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[6]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARsienna,
				borderColor: window.chartColoQARsienna,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisVLDL1);$i++){if($get_CustomTrendAnalysisVLDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisVLDL1[$i]['invest_value'].","; $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[7]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[7]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARteal,
				borderColor: window.chartColoQARteal,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisLDL1);$i++){if($get_CustomTrendAnalysisLDL1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisLDL1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[8]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[8]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARyellow,
				borderColor: window.chartColoQARyellow,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisTriglyceride1);$i++){if($get_CustomTrendAnalysisTriglyceride1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisTriglyceride1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php }?>],
				
				//data: [120,124,122,126,],
			}<?php } if(!empty($getInvestigate[9]['test_name_site_name'])){?>, {
				label: <?php echo "'".$getInvestigate[9]['test_name_site_name']."'"; ?>,
				backgroundColor: window.chartColoQARgreen,
				borderColor: window.chartColoQARgreen,
				fill: false,
				data: [<?php for($k=0;$k<count($get_CustomTrendAnalysisDate1);$k++){$j=0;for($i=0;$i<count($get_CustomTrendAnalysisCholesterol1);$i++){if($get_CustomTrendAnalysisCholesterol1[$i]['date_added']==$get_CustomTrendAnalysisDate1[$k]['date_added']){ echo $get_CustomTrendAnalysisCholesterol1[$i]['invest_value'].",";  $j++;}} if($j==0){ echo '0'.','; }?><?php } ?>],
				
				//data: [120,124,122,126,],
			}<?php }?>]
		},
		options: {
			responsive: true,
			title: {
				display: true,
				text: 'Customized Trend Analysis'
			},
			scales: {
				xAxes: [{
					display: true,
				}],
				yAxes: [{
					display: true,
					type: 'logarithmic',
				}]
			}
		}
	};				  
	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myLine = new Chart(ctx, config);
		var ctxx = document.getElementById('canvasCustomTrend').getContext('2d');
		window.myLine = new Chart(ctxx, configuration);
	};
	
	document.getElementById('randomizeData').addEventListener('click', function() {
		config.data.datasets.forEach(function(dataset) {
			dataset.data = dataset.data.map(function() {
				return randomScalingFactor();
			});
	
		});
	
		window.myLine.update();
	});
</script>
<script src="progress_styles/site.js"></script>