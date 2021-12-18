<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
$secretary_name = $_SESSION['user_name'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('../functions.php');
if(empty($admin_id)){
	header("Location:../index.php");
}

 		unset($_SESSION['episode_id']);
		unset($_SESSION['edit_session']);  //Active Edit Session
	
	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('d-m-Y',strtotime($cur_Date) + (24*3600*$add_days));
	
require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

	if(!isset($_POST['save_patient_edit'])){
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
	

	mysqlDelete('doc_patient_lids_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_type ='1' and status='1'");
	mysqlDelete('doc_patient_lids_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_type ='2' and status='1'");
	
	mysqlDelete('doc_patient_conjuctiva_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_conjuctiva_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_sclera_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_sclera_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_cornea_ant_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_cornea_ant_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_cornea_post_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_cornea_post_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_anterior_chamber_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_anterior_chamber_active',"doc_id='".$admin_id."' and doc_type ='1' and	eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_iris_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_iris_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_pupil_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_pupil_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_angle_active',"doc_id='".$admin_id."' and doc_type ='1'  and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_angle_active',"doc_id='".$admin_id."' and doc_type ='1'  and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_lens_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_lens_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_viterous_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_viterous_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	mysqlDelete('doc_patient_fundus_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='1' and status='1'");
	mysqlDelete('doc_patient_fundus_active',"doc_id='".$admin_id."' and doc_type ='1' and eye_side ='2' and status='1'");
	
	}	
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


	 
	$get_TrendAnalysisDate = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_medicineGivenDate = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisPPCount = mysqlSelect("bp_beforefood_count","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisPPAfterCount = mysqlSelect("bp_afterfood_count","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisSystolic = mysqlSelect("systolic","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisDiastolic = mysqlSelect("diastolic","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisHbA1c = mysqlSelect("HbA1c","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisHDL = mysqlSelect("HDL","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisVLDL = mysqlSelect("VLDL","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisLDL = mysqlSelect("LDL","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisTriglyceride = mysqlSelect("triglyceride","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
	$get_TrendAnalysisCholesterol = mysqlSelect("cholesterol","trend_analysis","patient_id='".$patient_id."'","date_added desc","","","0,8");
							
	$get_doc_details = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	
	$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");
	
	$get_OphthalTrendAnalysisDate1 = mysqlSelect("date_added","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvSphereRE1 = mysqlSelect("DvSphereRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvCylRE1 = mysqlSelect("DvCylRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvAxisRE1 = mysqlSelect("DvAxisRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvSpeherLE1 = mysqlSelect("DvSpeherLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvCylLE1 = mysqlSelect("DvCylLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvAxisLE1 = mysqlSelect("DvAxisLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvSpeherRE1 = mysqlSelect("NvSpeherRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvCylRE1 = mysqlSelect("NvCylRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvAxisRE1 = mysqlSelect("NvAxisRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvSpeherLE1 = mysqlSelect("NvSpeherLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvCylLE1 = mysqlSelect("NvCylLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvAxisLE1 = mysqlSelect("NvAxisLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_IpdRE1 = mysqlSelect("IpdRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_IpdLE1 = mysqlSelect("IpdLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
							
	$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
	if(isset($_POST['cmdchangePatient']))
	{
		$params     = split("-", $_POST['slct_valPat']);
		if($params[0]!=0){
		$patientid = $params[0];
		header("Location:".$_SESSION['EMR_URL'].md5($patientid));
		}
		else
		{
		$patientid = "0";
				
		header("Location:".$_SESSION['EMR_URL'].$patientid."&n=".$params[0]);
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

    <link rel="icon" href="../../assets/img/favicon_icon.png">
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
	  <link href="../../assets/css/plugins/slick/slick.css" rel="stylesheet">
    <link href="../../assets/css/plugins/slick/slick-theme.css" rel="stylesheet">
    <link href="../../assets/css/animate.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
	
	<!-- Bootstrap Tour -->
    <link href="../../assets/css/plugins/bootstrapTour/bootstrap-tour.min.css" rel="stylesheet">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	<!-- FooTable -->
    <link href="../../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	<!-- Toastr style -->
    <link href="../../assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
	
	<link href="../fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="../fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="../fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="../fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="../fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="../fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="../fileUpload/themes/fa/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="../js/status_validationJs.js"></script>
	<script src="../js/Chart.bundle.js"></script>
	<script src="../js/utils.js"></script>
	<link href="../../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	#cirCanvas {
  border: 1px solid black;
}
	</style>
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
	});
	
	function printContent(el){
		var restorepage=document.body.innerHTML;
		var printcontent=document.getElementById(el).innerHTML;
		document.body.innerHTML=printcontent;
		window.print();
		document.body.innerHTML=restorepage;
		
	}
	</script>
	
	<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: '../get_icd.php'
 });
 
 $( "#coding_language1" ).autocomplete({
  source: '../get_icd.php'
 });
 
  $( "#get_diagnosis" ).autocomplete({
  source: '../get_diagnosis.php'
 });
 $( "#get_complaints" ).autocomplete({
  source: '../get_healthcomplaint.php'
 });
 $( "#get_diagnosis_test" ).autocomplete({
  source: '../get_diagnosis_test.php'
 });
  $( "#get_examination_res" ).autocomplete({
  source: '../get_examination_res.php'
 });
 $( "#get_drug_abuse" ).autocomplete({
  source: '../get_drug_abuse.php'
 });
 $( "#get_family_history" ).autocomplete({
  source: '../get_family_history.php'
 });
 $( "#get_treatment_res" ).autocomplete({
  source: '../get_treatment_res.php'
 });
 $( "#get_allergy" ).autocomplete({
  source: '../get_allergy.php'
 });
 $( "#get_lids_RE" ).autocomplete({
  source: 'get_lids_data.php'
 });
 $( "#get_lids_LE" ).autocomplete({
  source: 'get_lids_data.php'
 });
 $( "#get_conjuctiva_RE" ).autocomplete({
  source: 'get_conjuctiva_data.php'
 });
 $( "#get_conjuctiva_LE" ).autocomplete({
  source: 'get_conjuctiva_data.php'
 });
 $( "#get_sclera_RE" ).autocomplete({
  source: 'get_sclera_data.php'
 });
 $( "#get_sclera_LE" ).autocomplete({
  source: 'get_sclera_data.php'
 });
 $( "#get_corneaAnt_RE" ).autocomplete({
  source: 'get_cornea_anterior_data.php'
 });
 $( "#get_corneaAnt_LE" ).autocomplete({
  source: 'get_cornea_anterior_data.php'
 });
 $( "#get_corneaPost_RE" ).autocomplete({
  source: 'get_cornea_posterior_data.php'
 });
 $( "#get_corneaPost_LE" ).autocomplete({
  source: 'get_cornea_posterior_data.php'
 });
 $( "#get_chamber_RE" ).autocomplete({
  source: 'get_chamber_data.php'
 });
 $( "#get_chamber_LE" ).autocomplete({
  source: 'get_chamber_data.php'
 });
 $( "#get_iris_RE" ).autocomplete({
  source: 'get_iris_data.php'
 });
 $( "#get_iris_LE" ).autocomplete({
  source: 'get_iris_data.php'
 });
 $( "#get_pupil_RE" ).autocomplete({
  source: 'get_pupil_data.php'
 });
 $( "#get_pupil_LE" ).autocomplete({
  source: 'get_pupil_data.php'
 });
 $( "#get_angle_RE" ).autocomplete({
  source: 'get_angle_data.php'
 });
 $( "#get_angle_LE" ).autocomplete({
  source: 'get_angle_data.php'
 });
 $( "#get_lens_RE" ).autocomplete({
  source: 'get_lens_data.php'
 });
 $( "#get_lens_LE" ).autocomplete({
  source: 'get_lens_data.php'
 });
 $( "#get_viterous_RE" ).autocomplete({
  source: 'get_viterous_data.php'
 });
 $( "#get_viterous_LE" ).autocomplete({
  source: 'get_viterous_data.php'
 });
 $( "#get_fundus_RE" ).autocomplete({
  source: 'get_fundus_data.php'
 });
 $( "#get_fundus_LE" ).autocomplete({
  source: 'get_fundus_data.php'
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
<script src="../js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="../date-time-picker.min.js"></script>
</head>

<body>

    <div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); 
		
		include_once('../patient_detail_section.php'); 
		
		?>
            
			
							
							
			<div class="row m-t">
                <div class="col-lg-2">
				<a href="?p=<?php echo $_GET['p']; ?>" id="addvisitDetails">
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
				<a href="#" id="fundusImage">
                    <div class="widget style1" style="background-color: #d8bfd8;color: #ffffff;">
                        <div class="row vertical-align">
                            <div class="col-xs-3">
                                <i class="fa fa-picture-o fa-2x"></i>
                            </div>
                            <div class="col-xs-9 text-left">
                                <h3 class="font-bold">FUNDUS IMAGE</h3>
                            </div>
                        </div>
                    </div>
					</a>
                </div>	
                <?php } ?>
            </div>
			
						
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row">
			<?php if($_GET['response']=="success"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Patient record has been updated successfully </strong>
			</div>
			<?php } else if($_GET['response']=="episode-created"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Patient visit details has been added successfully </strong>
			</div>
			<?php } else if($_GET['response']=="update-investigation"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Patient investigation updated successfully </strong>
			</div>
			<?php } else if($_GET['response']=="reports-attached"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Reports has been attached successfully </strong>
			</div>
			<?php } else if($_GET['response']=="fundus-image-attached"){ ?>
			<div class="alert alert-success alert-dismissable" >
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Fundus Image has been attached successfully </strong>
			</div>
			<?php } ?>
			<div class="alert alert-success alert-dismissable" id="fundus_message" style="display:none">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Fundus Image has been attached successfully </strong>
			</div>
                <div class="col-lg-12 m-b-lg">
					       
							<?php if(isset($_GET['p']) && !isset($_GET['episode'])) { ?>
                            <div class="row white-bg page-heading" id="add-visit-dtails">
                                <h2 class="pull-left">Add Patient Visit Details</h2>
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_ophthal_save.php"  name="frmAddPatient" id="frmAddPatient">
							<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
							<input type="hidden" name="patient_name" value="<?php echo $patient_tab[0]['patient_name']; ?>">
								<div class="col-lg-3 pull-right m-t">
									<dl>
										<dd><div class="pull-left m-r input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="J-demo-02" name="dateadded2" type="text" placeholder="YYYY-MM-DD" value="<?php echo $Cur_Date;?>" class="form-control" />
										</div></dd><br>
									</dl>
									<script type="text/javascript">
										$('#J-demo-02').dateTimePicker({
											mode: 'dateTime'
										});
									</script>
								</div>
							
								<!--Section Starts-->
								<?php include_once('../get_chief_medical_comp_section.php'); ?>
								
								<!--Section Ends-->
																
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Examination</h4>
								
								<div class="input-group">
										
								<table class="table table-bordered" style="table-layout:fixed">
								
								<thead>
									<th style="vertical-align:middle;width:100px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Exams</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#2b3b4b;"><font size="3">Left Eye</font></th>
								</thead>	

							<tbody>
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Distance Vision</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
										<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
										<tr><td>
											<select class="form-control exam_res" name="slctDistVisionRE_Aided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="6/6">6/6</option>
											<option value="6/6p">6/6p</option>
											<option value="6/9">6/9</option>
											<option value="6/9p">6/9p</option>
											<option value="6/12">6/12</option>
											<option value="6/12p">6/12p</option>
											<option value="6/18">6/18</option>
											<option value="6/18p">6/18p</option>
											<option value="6/24">6/24</option>
											<option value="6/24p">6/24p</option>
											<option value="6/36">6/36</option>
											<option value="6/36p">6/36p</option>
											<option value="6/60">6/60</option>
											<option value="6/60p">6/60p</option>
											<option value="HM+">HM+</option>
											<option value="FC @1m">FC @1m</option>
											<option value="FC @2m">FC @2m</option>
											<option value="FC @50cm">FC @50cm</option>
											<option value="PL+">PL+</option>
											<option value="PL-">PL-</option>
											</select>
											</td>
											<td>
											<select class="form-control exam_res" name="slctDistVisionRE_Unaided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="6/6">6/6</option>
											<option value="6/6p">6/6p</option>
											<option value="6/9">6/9</option>
											<option value="6/9p">6/9p</option>
											<option value="6/12">6/12</option>
											<option value="6/12p">6/12p</option>
											<option value="6/18">6/18</option>
											<option value="6/18p">6/18p</option>
											<option value="6/24">6/24</option>
											<option value="6/24p">6/24p</option>
											<option value="6/36">6/36</option>
											<option value="6/36p">6/36p</option>
											<option value="6/60">6/60</option>
											<option value="6/60p">6/60p</option>
											<option value="HM+">HM+</option>
											<option value="FC @1m">FC @1m</option>
											<option value="FC @2m">FC @2m</option>
											<option value="FC @50cm">FC @50cm</option>
											<option value="PL+">PL+</option>
											<option value="PL-">PL-</option>
											</select>
											</td>
										</tr>
										</table>
									</td>
									
									<td style="vertical-align:middle;background-color:#dce6e6;">
										<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control exam_res" name="slctDistVisionLE_Aided" data-examination-id="" style="width:200px;">
												<option value="">Select</option>
												<option value="6/6">6/6</option>
											<option value="6/6p">6/6p</option>
											<option value="6/9">6/9</option>
											<option value="6/9p">6/9p</option>
											<option value="6/12">6/12</option>
											<option value="6/12p">6/12p</option>
											<option value="6/18">6/18</option>
											<option value="6/18p">6/18p</option>
											<option value="6/24">6/24</option>
											<option value="6/24p">6/24p</option>
											<option value="6/36">6/36</option>
											<option value="6/36p">6/36p</option>
											<option value="6/60">6/60</option>
											<option value="6/60p">6/60p</option>
											<option value="HM+">HM+</option>
											<option value="FC @1m">FC @1m</option>
											<option value="FC @2m">FC @2m</option>
											<option value="FC @50cm">FC @50cm</option>
											<option value="PL+">PL+</option>
											<option value="PL-">PL-</option>
											</select>
											</td>
											<td>
											<select class="form-control exam_res" name="slctDistVisionLE_Unaided" data-examination-id="" style="width:200px;">
												<option value="">Select</option>
												<option value="6/6">6/6</option>
											<option value="6/6p">6/6p</option>
											<option value="6/9">6/9</option>
											<option value="6/9p">6/9p</option>
											<option value="6/12">6/12</option>
											<option value="6/12p">6/12p</option>
											<option value="6/18">6/18</option>
											<option value="6/18p">6/18p</option>
											<option value="6/24">6/24</option>
											<option value="6/24p">6/24p</option>
											<option value="6/36">6/36</option>
											<option value="6/36p">6/36p</option>
											<option value="6/60">6/60</option>
											<option value="6/60p">6/60p</option>
											<option value="HM+">HM+</option>
											<option value="FC @1m">FC @1m</option>
											<option value="FC @2m">FC @2m</option>
											<option value="FC @50cm">FC @50cm</option>
											<option value="PL+">PL+</option>
											<option value="PL-">PL-</option>
											</select>
											</td>
											</tr>
										</table>
											
									</td>
									
								</tr>
								
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Near Vision</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
									<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control exam_res" name="slctNearVisionRE_Aided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="N. 36">N. 36</option>
											<option value="N. 18">N. 18</option>
											<option value="N. 12">N. 12</option>
											<option value="N. 10">N. 10</option>
											<option value="N. 8">N. 8</option>
											<option value="N. 6">N. 6</option>
											</select>
											</td>
											<td>
											<select class="form-control exam_res" name="slctNearVisionRE_Unaided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="N. 36">N. 36</option>
											<option value="N. 18">N. 18</option>
											<option value="N. 12">N. 12</option>
											<option value="N. 10">N. 10</option>
											<option value="N. 8">N. 8</option>
											<option value="N. 6">N. 6</option>
											</select>
											</td>
											</tr>
									</table>
									</td>
											
									<td style="vertical-align:middle;background-color:#dce6e6;">
									<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
										<select class="form-control exam_res" name="slctNearVisionLE_Aided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="N. 36">N. 36</option>
											<option value="N. 18">N. 18</option>
											<option value="N. 12">N. 12</option>
											<option value="N. 10">N. 10</option>
											<option value="N. 8">N. 8</option>
											<option value="N. 6">N. 6</option>
											</select>
											</td>
											<td>
											<select class="form-control exam_res" name="slctNearVisionLE_Unaided" data-examination-id="" style="width:200px;">
											<option value="">Select</option>
											<option value="N. 36">N. 36</option>
											<option value="N. 18">N. 18</option>
											<option value="N. 12">N. 12</option>
											<option value="N. 10">N. 10</option>
											<option value="N. 8">N. 8</option>
											<option value="N. 6">N. 6</option>
											</select>
											</td>
										</tr>
									</table>
									</td>
								</tr>
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">IOP</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
									<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th></th></tr>
											<tr>
											<td>
											<select class="form-control exam_res" name="slctIOP_RE" data-examination-id="" style="width:300px;">
											<option value="">Select</option>
											<?php for($i=0;$i<=100;$i++){?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php } ?>
											</select><label class="m-l m-t-xs"> mm of HG</label>
											</td>
											</tr>
									</table>
									</td>
											
									<td style="vertical-align:middle;background-color:#dce6e6;">
									<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th></th></tr>
											<tr><td>
										<select class="form-control exam_res" name="slctIOP_LE" data-examination-id="" style="width:300px;">
											<option value="">Select</option>
											<?php for($i=0;$i<=100;$i++){?>
											<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php } ?>
											</select> <label class="m-l m-t-xs"> mm of HG</label>
											</td>
											
										</tr>
									</table>
									</td>
								</tr>
								<thead >
									<th  style="vertical-align:middle;text-align:center;" colspan="3"><font size="3">Anterior Segment Findings</font></th>
								
								</thead>
								<thead>
									<th style="vertical-align:middle;width:100px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Exams</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#2b3b4b;"><font size="3">Left Eye</font></th>
								</thead>
								
								<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Lids</font></th>
									
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="lidsBeforeRE">
								<?php 
								//$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='1') or (doc_id='0' and doc_type='0' and eye_type='1')","lids_name ASC","","","");			
								$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lids_name ASC","","","");			
								if(COUNT($last_five_lids)>0) { ?>
									
								
								<?php	foreach($last_five_lids as $last_five_lidsList) {
									?>
									
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorRE" data-lidsRE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>"> <label style="color:#000;"> <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									
									<!--<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorRE" data-lidsRE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>" <?php if($last_five_lidsList['right_eye']==1){ echo "checked";}?>>  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;-->
									
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_RE" name="srchLidsRE" value="" class="form-control input-lg searchLidsRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
							</td>
									
							<td style="background-color:#dce6e6;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="lidsBefore_LE">
								<?php 
								//$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='2') or (doc_id='0' and doc_type='0' and eye_type='2')","lids_name ASC","","","");			
								if(COUNT($last_five_lids)>0) { 
									foreach($last_five_lids as $last_five_lidsList) {
									?>
									
									<!--<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorLE" data-lidsLE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>" <?php if($last_five_lidsList['left_eye']==1){ echo "checked";}?>>  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;-->
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorLE" data-lidsLE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>">  <label style="color:#000;"><?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_LE" name="srchLidsLE" value="" class="form-control input-lg searchLidsLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
									</td>	
									
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Conjuctiva</font></th>
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="conjuctivaBeforeRE">
								<?php 
								$get_conjuctiva = mysqlSelect("*","examination_ophthal_conjuctiva","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","conjuctiva_name ASC","","","");			
								if(COUNT($get_conjuctiva)>0) { 
									foreach($get_conjuctiva as $get_conjuctivaList) {
									?>
									
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>"  class="i-checks m-l  get_conjuctiva_priorRE" data-conjuctivaRE-id="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>">  <label style="color:#000;"><?php echo $get_conjuctivaList['conjuctiva_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Conjuctiva here..." data-patient-id="<?php echo $patient_id; ?>" id="get_conjuctiva_RE" name="srchConjuctivaRE" value="" class="form-control input-lg searchConjuctivaRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
								</td>
								<td style="background-color:#dce6e6;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="conjuctivaBefore_LE">
								<?php 
								if(COUNT($get_conjuctiva)>0) { 
									foreach($get_conjuctiva as $get_conjuctivaList) {
									?>
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>"  class="i-checks m-l  get_conjuctiva_priorLE" data-conjuctivaLE-id="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>">  <label style="color:#000;"><?php echo $get_conjuctivaList['conjuctiva_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Conjuctiva here..." data-patient-id="<?php echo $patient_id; ?>" id="get_conjuctiva_LE" name="srchConjuctivaLE" value="" class="form-control input-lg searchConjuctivaLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
								</td>
							</tr>

							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Sclera</font></th>
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
										<div class="input-group">
										<div id="scleraBeforeRE">
										<?php 
										$get_sclera = mysqlSelect("*","examination_ophthal_sclera","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","scelra_name ASC","","","");			
										if(COUNT($get_sclera)>0) { 
											foreach($get_sclera as $get_scleraList) {
											?>
											
											<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_scleraList['sclera_id']; ?>"  class="i-checks m-l  get_sclera_priorRE" data-scleraRE-id="<?php echo $get_scleraList['sclera_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_scleraList['sclera_id']; ?>">  <label style="color:#000;"><?php echo $get_scleraList['scelra_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
														
											<?php  }
											}?>
											</div>
										</div>

										
										<div class="ibox">
										<div class="ibox-tools">
											<a class="collapse-link" style="color:#149d81; font-weight:bold;">
											<i class="fa fa-plus"></i> ADD 
											</a>
										</div>
										<div class="ibox-content" style="display: none;">
											<div class="input-group">
												<input type="text" placeholder="Add Sclera here..." data-patient-id="<?php echo $patient_id; ?>" id="get_sclera_RE" name="srchScleraRE" value="" class="form-control input-lg searchScleraRE" tabindex="1">
												<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
													ADD
												</button>
												</div>
											</div>
										</div>
									</div>
										<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="scleraBefore_LE">
								<?php 
								if(COUNT($get_sclera)>0) { 
									foreach($get_sclera as $get_scleraList) {
									?>
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_scleraList['sclera_id']; ?>"  class="i-checks m-l  get_sclera_priorLE" data-scleraLE-id="<?php echo $get_scleraList['sclera_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_scleraList['sclera_id']; ?>">  <label style="color:#000;"><?php echo $get_scleraList['scelra_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Sclera here..." data-patient-id="<?php echo $patient_id; ?>" id="get_sclera_LE" name="srchScleraLE" value="" class="form-control input-lg searchScleraLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Cornea Anterior Surface</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneatAntBefore_RE">
									<?php 
									$get_corneaAnt = mysqlSelect("*","examination_ophthal_cornea_anterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_ant_name ASC","","","");			
									if(COUNT($get_corneaAnt)>0) { 
										foreach($get_corneaAnt as $get_corneaAntList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaAntList['cornea_ant_id']; ?>"  class="i-checks m-l  get_corneaAnt_priorRE" data-corneaAntRE-id="<?php echo $get_corneaAntList['cornea_ant_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaAntList['cornea_ant_id']; ?>">  <label style="color:#000;"><?php echo $get_corneaAntList['cornea_ant_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Anterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaAnt_RE" name="srchCorneaAntRE" value="" class="form-control input-lg searchCorneaAntRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaAntBefore_LE">
									<?php 
									if(COUNT($get_corneaAnt)>0) { 
										foreach($get_corneaAnt as $get_corneaAntList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaAntList['cornea_ant_id']; ?>"  class="i-checks m-l  get_corneaAnt_priorLE" data-corneaAntLE-id="<?php echo $get_corneaAntList['cornea_ant_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaAntList['cornea_ant_id']; ?>">  <label style="color:#000;"><?php echo $get_corneaAntList['cornea_ant_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Anterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaAnt_LE" name="srchCoorneaAntLE" value="" class="form-control input-lg searchCorneaAntLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Cornea Posterior Surface</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaPostBefore_RE">
									<?php 
									$get_corneaPost = mysqlSelect("*","examination_ophthal_cornea_posterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_post_name ASC","","","");			
									if(COUNT($get_corneaPost)>0) { 
										foreach($get_corneaPost as $get_corneaPostList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaPostList['cornea_post_id']; ?>"  class="i-checks m-l  get_corneaPost_priorRE" data-corneaPostRE-id="<?php echo $get_corneaPostList['cornea_post_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaPostList['cornea_post_id']; ?>">  <label style="color:#000;"><?php echo $get_corneaPostList['cornea_post_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Posterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaPost_RE" name="srchCorneaPostRE" value="" class="form-control input-lg searchCorneaPostRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaPostBefore_LE">
									<?php 
									if(COUNT($get_corneaPost)>0) { 
										foreach($get_corneaPost as $get_corneaPostList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaPostList['cornea_post_id']; ?>"  class="i-checks m-l  get_corneaPost_priorLE" data-corneaPostLE-id="<?php echo $get_corneaPostList['cornea_post_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaPostList['cornea_post_id']; ?>">  <label style="color:#000;"><?php echo $get_corneaPostList['cornea_post_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Posterior here..." data-patient-id="<?php echo $patient_id; ?>" id="" name="srchCorneaPostLE" value="" class="form-control input-lg searchCorneaPostLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">get_corneaPost_LE
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Anterior Chamber</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="chamberBefore_RE">
									<?php 
									$get_chamber = mysqlSelect("*","examination_ophthal_chamber","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","chamber_name ASC","","","");			
									if(COUNT($get_chamber)>0) { 
										foreach($get_chamber as $get_chamberList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_chamberList['chamber_id']; ?>"  class="i-checks m-l  get_chamber_priorRE" data-chamberRE-id="<?php echo $get_chamberList['chamber_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_chamberList['chamber_id']; ?>">  <label style="color:#000;"><?php echo $get_chamberList['chamber_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Anterior Chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_chamber_RE" name="srchChamberRE" value="" class="form-control input-lg searchChamberRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="chamberBefore_LE">
									<?php 
									if(COUNT($get_chamber)>0) { 
										foreach($get_chamber as $get_chamberList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_chamberList['chamber_id']; ?>"  class="i-checks m-l  get_chamber_priorLE" data-chamberLE-id="<?php echo $get_chamberList['chamber_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_chamberList['chamber_id']; ?>">  <label style="color:#000;"><?php echo $get_chamberList['chamber_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Anterior Chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_chamber_LE" name="srchChamberLE" value="" class="form-control input-lg searchChamberLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Iris</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="irisBefore_RE">
									<?php 
									$get_iris = mysqlSelect("*","examination_ophthal_iris","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","iris_name ASC","","","");			
									if(COUNT($get_iris)>0) { 
										foreach($get_iris as $get_irisList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_irisList['iris_id']; ?>"  class="i-checks m-l  get_iris_priorRE" data-irisRE-id="<?php echo $get_irisList['iris_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_irisList['iris_id']; ?>">  <label style="color:#000;"><?php echo $get_irisList['iris_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Iris here..." data-patient-id="<?php echo $patient_id; ?>" id="get_iris_RE" name="srchIrisRE" value="" class="form-control input-lg searchIrisRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="irisBefore_LE">
									<?php 
									if(COUNT($get_iris)>0) { 
										foreach($get_iris as $get_irisList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_irisList['iris_id']; ?>"  class="i-checks m-l  get_iris_priorLE" data-irisLE-id="<?php echo $get_irisList['iris_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_irisList['iris_id']; ?>">  <label style="color:#000;"><?php echo $get_irisList['iris_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Iris here..." data-patient-id="<?php echo $patient_id; ?>" id="get_iris_LE" name="srchIrisLE" value="" class="form-control input-lg searchIrisLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Pupil</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="pupilBefore_RE">
									<?php 
									$get_pupil = mysqlSelect("*","examination_ophthal_pupil","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","pupil_name ASC","","","");			
									if(COUNT($get_pupil)>0) { 
										foreach($get_pupil as $get_pupilList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_pupilList['pupil_id']; ?>"  class="i-checks m-l  get_pupil_priorRE" data-pupilRE-id="<?php echo $get_pupilList['pupil_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_pupilList['pupil_id']; ?>">  <label style="color:#000;"><?php echo $get_pupilList['pupil_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Pupil here..." data-patient-id="<?php echo $patient_id; ?>" id="get_pupil_RE" name="srchPupilRE" value="" class="form-control input-lg searchPupilRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="pupilBefore_LE">
									<?php 
									if(COUNT($get_pupil)>0) { 
										foreach($get_pupil as $get_pupilList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_pupilList['pupil_id']; ?>"  class="i-checks m-l  get_pupil_priorLE" data-pupilLE-id="<?php echo $get_pupilList['pupil_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_pupilList['pupil_id']; ?>">  <label style="color:#000;"><?php echo $get_pupilList['pupil_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Pupil here..." data-patient-id="<?php echo $patient_id; ?>" id="get_pupil_LE" name="srchPupilLE" value="" class="form-control input-lg searchPupilLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Angle of Anterior Chamber</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="angleBefore_RE">
									<?php 
									$get_angle = mysqlSelect("*","examination_ophthal_angle","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","angle_name ASC","","","");			
									if(COUNT($get_angle)>0) { 
										foreach($get_angle as $get_angleList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_angleList['angle_id']; ?>"  class="i-checks m-l  get_angle_priorRE" data-angleRE-id="<?php echo $get_angleList['angle_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_angleList['angle_id']; ?>">  <label style="color:#000;"><?php echo $get_angleList['angle_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add angle of anterior chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_angle_RE" name="srchAngleRE" value="" class="form-control input-lg searchAngleRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="angleBefore_LE">
									<?php 
									if(COUNT($get_angle)>0) { 
										foreach($get_angle as $get_angleList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_angleList['angle_id']; ?>"  class="i-checks m-l  get_angle_priorLE" data-angleLE-id="<?php echo $get_angleList['angle_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_angleList['angle_id']; ?>">  <label style="color:#000;"><?php echo $get_angleList['angle_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add angle of anterior chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_angle_LE" name="srchAngleLE" value="" class="form-control input-lg searchAngleLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Lens</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="lensBefore_RE">
									<?php 
									$get_lens = mysqlSelect("*","examination_ophthal_lens","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lens_name ASC","","","");			
									if(COUNT($get_lens)>0) { 
										foreach($get_lens as $get_lensList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_lensList['lens_id']; ?>"  class="i-checks m-l  get_lens_priorRE" data-lensRE-id="<?php echo $get_lensList['lens_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_lensList['lens_id']; ?>">  <label style="color:#000;"><?php echo $get_lensList['lens_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Lens here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lens_RE" name="srchLensRE" value="" class="form-control input-lg searchLensRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="lensBefore_LE">
									<?php 
									if(COUNT($get_lens)>0) { 
										foreach($get_lens as $get_lensList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_lensList['lens_id']; ?>"  class="i-checks m-l  get_lens_priorLE" data-lensLE-id="<?php echo $get_lensList['lens_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_lensList['lens_id']; ?>">  <label style="color:#000;"><?php echo $get_lensList['lens_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Lens here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lens_LE" name="srchLensLE" value="" class="form-control input-lg searchLensLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<thead >
									<th  style="vertical-align:middle;text-align:center;" colspan="3"><font size="3">Posterior Segment Findings (Retina)</font></th>
							</thead>
							<thead>
									<th style="vertical-align:middle;width:100px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Exams</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#2b3b4b;"><font size="3">Left Eye</font></th>
							</thead>	
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Viterous</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="viterousBefore_RE">
									<?php 
									$get_viterous = mysqlSelect("*","examination_ophthal_viterous","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","viterous_name ASC","","","");			
									if(COUNT($get_viterous)>0) { 
										foreach($get_viterous as $get_viterousList) {
										?>
										
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_viterousList['viterous_id']; ?>"  class="i-checks m-l  get_viterous_priorRE" data-viterousRE-id="<?php echo $get_viterousList['viterous_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_viterousList['viterous_id']; ?>">  <label style="color:#000;"><?php echo $get_viterousList['viterous_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Viterous here..." data-patient-id="<?php echo $patient_id; ?>" id="get_viterous_RE" name="srchViterousRE" value="" class="form-control input-lg searchViterousRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="viterousBefore_LE">
									<?php 
									if(COUNT($get_viterous)>0) { 
										foreach($get_viterous as $get_viterousList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_viterousList['viterous_id']; ?>"  class="i-checks m-l  get_viterous_priorLE" data-viterousLE-id="<?php echo $get_viterousList['viterous_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_viterousList['viterous_id']; ?>">  <label style="color:#000;"><?php echo $get_viterousList['viterous_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Viterous here..." data-patient-id="<?php echo $patient_id; ?>" id="get_viterous_LE" name="srchViterousLE" value="" class="form-control input-lg searchViterousLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Fundus</font></th>
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="fundusBefore_RE">
								<?php 
								$get_fundus = mysqlSelect("*","examination_ophthal_fundus","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","fundus_name ASC","","","");			
								if(COUNT($get_fundus)>0) { 
									foreach($get_fundus as $get_fundusList) {
									?>
									
									<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_fundusList['fundus_id']; ?>"  class="i-checks m-l  get_fundus_priorRE" data-fundusRE-id="<?php echo $get_fundusList['fundus_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_fundusList['fundus_id']; ?>">  <label style="color:#000;"><?php echo $get_fundusList['fundus_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Fundus here..." data-patient-id="<?php echo $patient_id; ?>" id="get_fundus_RE" name="srchFundusRE" value="" class="form-control input-lg searchFundusRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="fundusBefore_LE">
									<?php 
									if(COUNT($get_fundus)>0) { 
										foreach($get_fundus as $get_fundusList) {
										?>
										<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_fundusList['fundus_id']; ?>"  class="i-checks m-l  get_fundus_priorLE" data-fundusLE-id="<?php echo $get_fundusList['fundus_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_fundusList['fundus_id']; ?>">  <label style="color:#000;"><?php echo $get_fundusList['fundus_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Fundus here..." data-patient-id="<?php echo $patient_id; ?>" id="get_fundus_LE" name="srchFundusLE" value="" class="form-control input-lg searchFundusLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
									<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Refraction</font></th>
									<td style="background-color:#cde2e2;"> 
									<div class="wrapper" style="position: relative;">
									<input type="number" step="1" style="margin-left: 100px;
																margin-top: 50px;
																 width: 60px; text-align:center;" name="se_refractionRE_value1" placeholder="Add " value="0" data-patient-id="<?php echo $patient_id; ?>" id="get_refractionRE" name="srchRefractionRE" value="" class="searchRefractionRE" tabindex="1" />
											
										<hr class="vertical1" style=" transform: rotate(90deg);
																  margin-left: 100px;
																  margin-top: 50px;
																  width: 150px;
																  border: 0; border-top: 1px solid red;">
										<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																					  margin-top: 50px;
																					  margin-left: 150px;
																					  width: 200px;">
									  <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="se_refractionRE_value2" placeholder="Add " value="0" data-patient-id="<?php echo $patient_id; ?>" id="get_refractionRE" name="srchRefractionRE" value="" class="searchRefractionRE" tabindex="1" />
									
									</div>
									</td>
									<td style="background-color:#dce6e6;">
									<div class="wrapper" style="position: relative;">
									<input type="number" step="1" style="margin-left: 100px;
																margin-top: 50px;
																 width: 60px; text-align:center;" name="se_refractionLE_value1" placeholder="Add " value="0" data-patient-id="<?php echo $patient_id; ?>" id="get_refractionRE" name="srchRefractionRE" value="" class="searchRefractionRE" tabindex="1" />
											
										<hr class="vertical1" style=" transform: rotate(90deg);
																  margin-left: 100px;
																  margin-top: 50px;
																  width: 150px;
																  border: 0; border-top: 1px solid red;">
										<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																					  margin-top: 50px;
																					  margin-left: 150px;
																					  width: 200px;">
									  <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="se_refractionLE_value2" placeholder="Add " value="0" data-patient-id="<?php echo $patient_id; ?>" id="get_refractionRE" name="srchRefractionRE" value="" class="searchRefractionRE" tabindex="1" />
									
									</div>
									</td>
									
									</tr>
								
								</tbody>								
								
								</table>
								
								
                                </div>
								<br>
								
								</div>
								
								<br>
								<?php
								//if(($secretary_id!=1) || ($check_reception_permission[0]['spectacle_prescription']==1 && $secretary_id==1)){
								?>
								
								<div class="col-lg-12 m-t" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Present power of glasses</h4>
								
								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
																			<thead>
																				
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#2b3b4b"><font size="3">Left Eye</font></th>
																				
																			</thead>
																			<thead>
																				<th style="color:#fff;background-color:#1a2530;"></th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Vision</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Vision</th>
																			</thead>
																			<tbody>
																			<tr><td style="color:#fff;background-color:#1a2530;">D.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvSpeherRE_Present" id="DvSpeherRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvCylRE_Present" id="DvCylRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvAxisRE_Present" id="DvAxisRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvVisionRE_Present" id="DvVisionRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvSpeherLE_Present" id="DvSpeherLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvCylLE_Present" id="DvCylLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvAxisLE_Present" id="DvAxisLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvVisionLE_Present" id="DvVisionLE_Present"  style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">N.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvSpeherRE_Present" id="NvSpeherRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvCylRE_Present" id="NvCylRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvAxisRE_Present" id="NvAxisRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvVisionRE_Present" id="NvVisionRE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvSpeherLE_Present" id="NvSpeherLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvCylLE_Present" id="NvCylLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvAxisLE_Present" id="NvAxisLE_Present"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvVisionLE_Present" id="NvVisionLE_Present"  style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">IPD</td>
																			<td colspan="4" style="background-color:#cde2e2;"><input type="text" class="tagName" name="IpdRE_Present" id="IpdRE_Present"  style="width:100%;"></td>
																			<td colspan="4" style="background-color:#dce6e6;"><input type="text" class="tagName" name="IpdLE_Present" id="IpdLE_Present"  style="width:100%;"></td>
																			</tr>
																			</tbody>
																			
																		</table>
								
								
								</div>
                               	<br>
								
								
							<?php //}
							?><br>
								
								<?php include_once('../get_investigation_section.php'); ?>
							
								
								<?php include_once('../get_diagnosis_section.php'); ?>
								
															
								<?php include_once('../get_treatment_advise_section.php'); ?>
								
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Spectacle Prescriptions</h4>
								
								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
																			<thead>
																				
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#2b3b4b"><font size="3">Left Eye</font></th>
																				
																			</thead>
																			<thead>
																				<th style="color:#fff;background-color:#1a2530;"></th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Vision</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Vision</th>
																			</thead>
																			<tbody>
																			<tr><td style="color:#fff;background-color:#1a2530;">D.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvSpeherRE" id="DvSpeherRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvCylRE" id="DvCylRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvAxisRE" id="DvAxisRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="DvVisionRE" id="DvVisionRE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvSpeherLE" id="DvSpeherLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvCylLE" id="DvCylLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvAxisLE" id="DvAxisLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="DvVisionLE" id="DvVisionLE"  style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">N.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvSpeherRE" id="NvSpeherRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvCylRE" id="NvCylRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvAxisRE" id="NvAxisRE"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName" name="NvVisionRE" id="NvVisionRE"  style="width:100%;"></td>																			
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvSpeherLE" id="NvSpeherLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvCylLE" id="NvCylLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvAxisLE" id="NvAxisLE"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName" name="NvVisionLE" id="NvVisionLE"  style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">IPD</td>
																			<td colspan="4" style="background-color:#cde2e2;"><input type="text" class="tagName" name="IpdRE" id="IpdRE"  style="width:100%;"></td>
																			<td colspan="4" style="background-color:#dce6e6;"><input type="text" class="tagName" name="IpdLE" id="IpdLE"  style="width:100%;"></td>
																			</tr>
																			</tbody>
																			
																		</table>
								
								
								</div>
                               	<br>
								
								<!--Section Starts-->
								<?php include_once('../get_prescription_section.php'); ?>
								<!--Section Ends-->
								
								<!--Section Starts-->
								<?php
								$check_pay_status = mysqlSelect("*","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1' and DATE_FORMAT(trans_date,'%Y-%m-%d')='".$cur_Date."'","","","","");
								$check_last_pay_date = mysqlSelect("pay_trans_id,trans_date","payment_transaction","patient_id='".$patient_tab[0]['patient_id']."' and user_id='".$admin_id."' and user_type='1'","pay_trans_id desc","","","");
	
								?>
								
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									 <div class="form-group">
								 <div class="col-lg-3">
									<dl>
										<dt>Consultation charges(Rs.)</dt><br> 
										<dd>
										<div class="pull-left m-r input-group date">
											<input name="consult_charge" type="text" placeholder="Consultation charges(Rs.)" value="<?php if(count($check_pay_status)>0) { echo " "; } else { echo $get_doc_details[0]['cons_charge']; } ?>" class="form-control" tabindex="8" />
										</div>
										</dd><br>
									</dl>
								</div>
								<?php if($checkSetting[0]['before_consultation_fee']=="1"){ ?>
								<div class="col-lg-2 ">
									<dl>
										<dt>Payment Status</dt><br> <dd><div class="pull-left m-r input-group date">
											<?php if(count($check_pay_status)>0) { ?><span class='label label-primary'>PAYMENT RECEIVED</span><?php } else { ?><span class='label label-danger'>PAYMENT NOT-RECEIVED</span><?php } ?>
										</div></dd><br>
									</dl>
								</div>
								<?php } if(!empty($check_last_pay_date)){ ?>
								<div class="col-lg-2 ">
									<dl>
										<dt>Last Payment done </dt><br> <dd><div class="pull-left m-r input-group date">
											 <font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($check_last_pay_date[0]['trans_date'])); ?></font>
										</div></dd><br>
									</dl>
								</div><?php } if(!empty($patient_episodes[0]['date_time'])){ ?>
								<div class="col-lg-2 ">
									<dl>
										<dt>Last visited on </dt><br> <dd><div class="pull-left m-r input-group date">
											 <font style="color:red;font-weight:bold;"><?php echo date('d-M-Y',strtotime($patient_episodes[0]['date_time'])); ?></font>
										</div></dd><br>
									</dl>
								</div><?php } 
								
								?>
								 <div class="col-lg-3 pull-right">
									<dl>
										<dt>Next Follow Up Date</dt><br> <dd><div class="pull-left m-r input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded1" name="dateadded" type="text" placeholder="Select Next followup date" value="" class="form-control" tabindex="9"/>
										</div></dd><br>
									</dl>
								</div>
								
								</div>
								</div>
								
								<?php if($secretary_id!="1") { ?><button class="btn btn-sm btn-primary pull-right" name="save_patient_print" id="save_patient_print" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-print"></i> SAVE & PRINT VISIT</strong></button>
									<?php } ?>
                            </div>
							</form>
							<?php } if(isset($_GET['p']) && isset($_GET['episode'])) { 
							unset($_SESSION['episode_id']); //Unset previous $_SESSION['episode_id']
							
							$edit_patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","md5(episode_id) = '".$_GET['episode']."' ","","","","");
							$edit_spectacal_presc = mysqlSelect("*","examination_opthal_spectacle_prescription","md5(episode_id) = '".$_GET['episode']."' ","","","","");
							$edit_present_spectacal_presc = mysqlSelect("*","present_examination_opthal_spectacle_prescription","md5(episode_id) = '".$_GET['episode']."' ","","","","");
							if(!empty($_GET['episode'])){
								$_SESSION['episode_id'] = $edit_patient_episodes[0]['episode_id'];
								$_SESSION['edit_session'] = "1";  //Active Edit Session
							}
							?>
							<div class="row white-bg page-heading" id="edit_visist_details">
                                <h2 class="pull-left">Edit Patient Visit Details - Visit <?php echo  $_GET['visit']; ?> (<?php echo $edit_patient_episodes[0]['formated_date_time'] ?>)</h2>
								<div class="col-lg-1 pull-right m-t-xs">
		<a href="../print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo $_GET['episode']; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-print"></i> PRINT EMR</a>
								</div>
								<div class="col-lg-3 pull-right m-t-xs">
									<dl>
										<dd>
										<form method="post" name="frmDateChange" action="my_patient_profile_ophthal_save.php">
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
										</dd><br>
									</dl>
									<script type="text/javascript">
            $('#J-demo-02').dateTimePicker({
                mode: 'dateTime'
            });
        </script>
		</div>
								<!--Edit_chief_medical_complaint_section -->
								<?php include_once('../edit_chief_medical_complaint_section.php'); ?>
								<!--End Edit_chief_medical_complaint_section -->
								
								<!-- Edit Examination Section -->
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Examination</h4>
								
								<div class="input-group">
										
								<table class="table table-bordered" style="table-layout:fixed">
								
								<thead>
									<th style="vertical-align:middle;width:100px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Exams</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
									<th style="vertical-align:middle;width:450px;text-align:center;color:#fff;background-color:#2b3b4b;"><font size="3">Left Eye</font></th>
								</thead>	

							<tbody>
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Distance Vision</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
											<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control" name="slctDistVisionRE" id="slctDistVisionRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
												<option value="" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']==""){ echo "selected"; } ?>>Select</option>
												<option value="6/6" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/6"){ echo "selected"; } ?>>6/6</option>
											    <option value="6/6p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/6p"){ echo "selected"; } ?>>6/6p</option>
												<option value="6/9" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/9"){ echo "selected"; } ?>>6/9</option>
												<option value="6/9p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/9p"){ echo "selected"; } ?>>6/9p</option>
											    <option value="6/12" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/12"){ echo "selected"; } ?>>6/12</option>
												<option value="6/12p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/12p"){ echo "selected"; } ?>>6/12p</option>
												<option value="6/18" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/18"){ echo "selected"; } ?>>6/18</option>
												<option value="6/18p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/18p"){ echo "selected"; } ?>>6/18p</option>
												<option value="6/24" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/24"){ echo "selected"; } ?>>6/24</option>
												<option value="6/24p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/24p"){ echo "selected"; } ?>>6/24p</option>
												<option value="6/36" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/36"){ echo "selected"; } ?>>6/36</option>
												<option value="6/36p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/36p"){ echo "selected"; } ?>>6/36p</option>
												<option value="6/60" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/60"){ echo "selected"; } ?>>6/60</option>
												<option value="6/60p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="6/60p"){ echo "selected"; } ?>>6/60p</option>
												<option value="HM+" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="HM+"){ echo "selected"; } ?>>HM+</option>
												<option value="FC @1m" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="FC @1m"){ echo "selected"; } ?>>FC @1m</option>
												<option value="FC @2m" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="FC @2m"){ echo "selected"; } ?>>FC @2m</option>
												<option value="FC @50cm" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="FC @50cm"){ echo "selected"; } ?>>FC @50cm</option>
												<option value="PL+" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="PL+"){ echo "selected"; } ?>>PL+</option>
												<option value="PL-" <?php if($edit_spectacal_presc[0]['distacnce_vision_right']=="PL-"){ echo "selected"; } ?>>PL-</option>
											</select>
											</td>
											<td>
											<select class="form-control" name="slctDistVisionRE_Unaided" id="slctDistVisionRE_Unaided" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
												<option value="" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']==""){ echo "selected"; } ?>>Select</option>
												<option value="6/6" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/6"){ echo "selected"; } ?>>6/6</option>
											    <option value="6/6p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/6p"){ echo "selected"; } ?>>6/6p</option>
												<option value="6/9" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/9"){ echo "selected"; } ?>>6/9</option>
												<option value="6/9p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/9p"){ echo "selected"; } ?>>6/9p</option>
											    <option value="6/12" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/12"){ echo "selected"; } ?>>6/12</option>
												<option value="6/12p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/12p"){ echo "selected"; } ?>>6/12p</option>
												<option value="6/18" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/18"){ echo "selected"; } ?>>6/18</option>
												<option value="6/18p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/18p"){ echo "selected"; } ?>>6/18p</option>
												<option value="6/24" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/24"){ echo "selected"; } ?>>6/24</option>
												<option value="6/24p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/24p"){ echo "selected"; } ?>>6/24p</option>
												<option value="6/36" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/36"){ echo "selected"; } ?>>6/36</option>
												<option value="6/36p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/36p"){ echo "selected"; } ?>>6/36p</option>
												<option value="6/60" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/60"){ echo "selected"; } ?>>6/60</option>
												<option value="6/60p" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="6/60p"){ echo "selected"; } ?>>6/60p</option>
												<option value="HM+" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="HM+"){ echo "selected"; } ?>>HM+</option>
												<option value="FC @1m" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="FC @1m"){ echo "selected"; } ?>>FC @1m</option>
												<option value="FC @2m" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="FC @2m"){ echo "selected"; } ?>>FC @2m</option>
												<option value="FC @50cm" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="FC @50cm"){ echo "selected"; } ?>>FC @50cm</option>
												<option value="PL+" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="PL+"){ echo "selected"; } ?>>PL+</option>
												<option value="PL-" <?php if($edit_spectacal_presc[0]['distacnce_vision_right_unaided']=="PL-"){ echo "selected"; } ?>>PL-</option>
												
											</select>
											</td>
											</tr>
										</table>
									</td>
									
									<td style="vertical-align:middle;background-color:#dce6e6;">
											<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control" name="slctDistVisionLE" id="slctDistVisionLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
												<option value="" <?php if($edit_spectacal_presc[0]['distance_vision_left']==""){ echo "selected"; } ?>>Select</option>
												<option value="6/6" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/6"){ echo "selected"; } ?>>6/6</option>
											    <option value="6/6p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/6p"){ echo "selected"; } ?>>6/6p</option>
												<option value="6/9" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/9"){ echo "selected"; } ?>>6/9</option>
												<option value="6/9p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/9p"){ echo "selected"; } ?>>6/9p</option>
											    <option value="6/12" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/12"){ echo "selected"; } ?>>6/12</option>
												<option value="6/12p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/12p"){ echo "selected"; } ?>>6/12p</option>
												<option value="6/18" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/18"){ echo "selected"; } ?>>6/18</option>
												<option value="6/18p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/18p"){ echo "selected"; } ?>>6/18p</option>
												<option value="6/24" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/24"){ echo "selected"; } ?>>6/24</option>
												<option value="6/24p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/24p"){ echo "selected"; } ?>>6/24p</option>
												<option value="6/36" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/36"){ echo "selected"; } ?>>6/36</option>
												<option value="6/36p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/36p"){ echo "selected"; } ?>>6/36p</option>
												<option value="6/60" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/60"){ echo "selected"; } ?>>6/60</option>
												<option value="6/60p" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="6/60p"){ echo "selected"; } ?>>6/60p</option>
												<option value="HM+" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="HM+"){ echo "selected"; } ?>>HM+</option>
												<option value="FC @1m" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="FC @1m"){ echo "selected"; } ?>>FC @1m</option>
												<option value="FC @2m" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="FC @2m"){ echo "selected"; } ?>>FC @2m</option>
												<option value="FC @50cm" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="FC @50cm"){ echo "selected"; } ?>>FC @50cm</option>
												<option value="PL+" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="PL+"){ echo "selected"; } ?>>PL+</option>
												<option value="PL-" <?php if($edit_spectacal_presc[0]['distance_vision_left']=="PL-"){ echo "selected"; } ?>>PL-</option>
											</select>
											</td>
											<td>
											<select class="form-control" name="slctDistVisionLE_Unaided" id="slctDistVisionLE_Unaided" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
												<option value="6/6" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/6"){ echo "selected"; } ?>>6/6</option>
											    <option value="6/6p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/6p"){ echo "selected"; } ?>>6/6p</option>
												<option value="6/9" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/9"){ echo "selected"; } ?>>6/9</option>
												<option value="6/9p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/9p"){ echo "selected"; } ?>>6/9p</option>
											    <option value="6/12" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/12"){ echo "selected"; } ?>>6/12</option>
												<option value="6/12p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/12p"){ echo "selected"; } ?>>6/12p</option>
												<option value="6/18" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/18"){ echo "selected"; } ?>>6/18</option>
												<option value="6/18p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/18p"){ echo "selected"; } ?>>6/18p</option>
												<option value="6/24" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/24"){ echo "selected"; } ?>>6/24</option>
												<option value="6/24p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/24p"){ echo "selected"; } ?>>6/24p</option>
												<option value="6/36" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/36"){ echo "selected"; } ?>>6/36</option>
												<option value="6/36p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/36p"){ echo "selected"; } ?>>6/36p</option>
												<option value="6/60" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/60"){ echo "selected"; } ?>>6/60</option>
												<option value="6/60p" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="6/60p"){ echo "selected"; } ?>>6/60p</option>
												<option value="HM+" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="HM+"){ echo "selected"; } ?>>HM+</option>
												<option value="FC @1m" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="FC @1m"){ echo "selected"; } ?>>FC @1m</option>
												<option value="FC @2m" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="FC @2m"){ echo "selected"; } ?>>FC @2m</option>
												<option value="FC @50cm" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="FC @50cm"){ echo "selected"; } ?>>FC @50cm</option>
												<option value="PL+" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="PL+"){ echo "selected"; } ?>>PL+</option>
												<option value="PL-" <?php if($edit_spectacal_presc[0]['distance_vision_left_unaided']=="PL-"){ echo "selected"; } ?>>PL-</option>
											</select>
											</td>
											</tr>
										</table>
									</td>
									
								</tr>
								
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Near Vision</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
									<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control" name="slctNearVisionRE" id="slctNearVisionRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
										
											<option value="" <?php if($edit_spectacal_presc[0]['near_vision_right']==""){ echo "selected"; }?>>Select</option>
											<option value="N. 36" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 36"){ echo "selected"; }?>>N. 36</option>
											<option value="N. 18" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 18"){ echo "selected"; }?>>N. 18</option>
											<option value="N. 12" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 12"){ echo "selected"; }?>>N. 12</option>
											<option value="N. 10" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 10"){ echo "selected"; }?>>N. 10</option>
											<option value="N. 8" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 8"){ echo "selected"; }?>>N. 8</option>
											<option value="N. 6" <?php if($edit_spectacal_presc[0]['near_vision_right']=="N. 6"){ echo "selected"; }?>>N. 6</option>
										
											</select>
											</td>
											<td>
											<select class="form-control" name="slctNearVisionRE_Unaided" id="slctNearVisionRE_Unaided" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
										
											<option value="" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']==""){ echo "selected"; }?>>Select</option>
											<option value="N. 36" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 36"){ echo "selected"; }?>>N. 36</option>
											<option value="N. 18" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 18"){ echo "selected"; }?>>N. 18</option>
											<option value="N. 12" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 12"){ echo "selected"; }?>>N. 12</option>
											<option value="N. 10" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 10"){ echo "selected"; }?>>N. 10</option>
											<option value="N. 8" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 8"){ echo "selected"; }?>>N. 8</option>
											<option value="N. 6" <?php if($edit_spectacal_presc[0]['near_vision_right_unaided']=="N. 6"){ echo "selected"; }?>>N. 6</option>
										
											</select>
											</td>
											</tr>
										</table>
											</td>
											
									<td style="vertical-align:middle;background-color:#dce6e6;">
									<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th><label>Aided</label></th><th><label>Unaided</label></th></tr>
											<tr><td>
											<select class="form-control" name="slctNearVisionLE" id="slctNearVisionLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
											
											<option value="" <?php if($edit_spectacal_presc[0]['near_vision_left']==""){ echo "selected"; }?>>Select</option>
											<option value="N. 36" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 36"){ echo "selected"; }?>>N. 36</option>
											<option value="N. 18" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 18"){ echo "selected"; }?>>N. 18</option>
											<option value="N. 12" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 12"){ echo "selected"; }?>>N. 12</option>
											<option value="N. 10" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 10"){ echo "selected"; }?>>N. 10</option>
											<option value="N. 8" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 8"){ echo "selected"; }?>>N. 8</option>
											<option value="N. 6" <?php if($edit_spectacal_presc[0]['near_vision_left']=="N. 6"){ echo "selected"; }?>>N. 6</option>
											</select>
											</td>
											<td>
											<select class="form-control" name="slctNearVisionLE_Unaided" id="slctNearVisionLE_Unaided" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:200px;">
											
											<option value="" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']==""){ echo "selected"; }?>>Select</option>
											<option value="N. 36" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 36"){ echo "selected"; }?>>N. 36</option>
											<option value="N. 18" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 18"){ echo "selected"; }?>>N. 18</option>
											<option value="N. 12" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 12"){ echo "selected"; }?>>N. 12</option>
											<option value="N. 10" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 10"){ echo "selected"; }?>>N. 10</option>
											<option value="N. 8" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 8"){ echo "selected"; }?>>N. 8</option>
											<option value="N. 6" <?php if($edit_spectacal_presc[0]['near_vision_left_unaided']=="N. 6"){ echo "selected"; }?>>N. 6</option>
											</select>
											</td>
											</tr>
									</table>
											</td>
								</tr>
								<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">IOP</font></th>
									<td style="vertical-align:middle;background-color:#cde2e2;">
									<table class="table" style="vertical-align:middle;background-color:#cde2e2;"><tr><th></th></tr>
											<tr>
											<td>
											<select class="form-control slctIOP_RE" name="slctIOP_RE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:300px;">
											<option value="" <?php if($edit_spectacal_presc[0]['IopRE']==""){ echo "selected"; }?>>Select</option>
											<?php for($i=0;$i<=100;$i++){?>
											<option value="<?php echo $i;?>" <?php if($edit_spectacal_presc[0]['IopRE']==$i){ echo "selected"; } ?>><?php echo $i;?></option>
											<?php } ?>
											</select><label class="m-l m-t-xs"> mm of HG</label>
											</td>
											</tr>
									</table>
									</td>
											
									<td style="vertical-align:middle;background-color:#dce6e6;">
									<table class="table" style="vertical-align:middle;background-color:#dce6e6;"><tr><th></th></tr>
											<tr><td>
										<select class="form-control slctIOP_LE" name="slctIOP_LE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" style="width:300px;">
											<option value="" <?php if($edit_spectacal_presc[0]['IopLE']==""){ echo "selected"; }?>>Select</option>
											<?php for($i=0;$i<=100;$i++){?>
											<option value="<?php echo $i;?>" <?php if($edit_spectacal_presc[0]['IopLE']==$i){ echo "selected"; } ?>><?php echo $i;?></option>
											<?php } ?>
											</select><label class="m-l m-t-xs"> mm of HG</label>
											</td>
											
										</tr>
									</table>
									</td>
								</tr>
								<thead >
									<th  style="vertical-align:middle;text-align:center;" colspan="3"><font size="3">Anterior Segment Findings</font></th>
								</thead>
								
								<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Lids</font></th>
									
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="lidsBeforeRE">
								<?php 
								//$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='1') or (doc_id='0' and doc_type='0' and eye_type='1')","lids_name ASC","","","");			
								$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lids_name ASC","","","");			
								
								if(COUNT($last_five_lids)>0) { 
								
									foreach($last_five_lids as $last_five_lidsList) {
										$active_lids_RE = mysqlSelect("*","doc_patient_lids_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and lids='".$last_five_lidsList['lids_id']."' and md5(patient_id)='".$_GET['p']."' and eye_type='1'","","","","");			
								
									?>
									
									<input type="checkbox" <?php if($active_lids_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorRE" data-lidsRE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>">  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									
									<!--<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorRE" data-lidsRE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>" <?php if($last_five_lidsList['right_eye']==1){ echo "checked";}?>>  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;-->
									
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_RE" name="srchLidsRE" value="" class="form-control input-lg searchLidsRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
							</td>
									
							<td style="background-color:#dce6e6;" >
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="lidsBefore_LE">
								<?php 
								//$last_five_lids = mysqlSelect("*","examination_ophthal_lids","(doc_id='".$admin_id."' and doc_type='1' and eye_type='2') or (doc_id='0' and doc_type='0' and eye_type='2')","lids_name ASC","","","");			
								if(COUNT($last_five_lids)>0) { 
									foreach($last_five_lids as $last_five_lidsList) {
										$active_lids_LE = mysqlSelect("*","doc_patient_lids_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and lids='".$last_five_lidsList['lids_id']."' and md5(patient_id)='".$_GET['p']."' and eye_type='2'","","","","");			
								
									?>
									
									<!--<input type="checkbox" style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorLE" data-lidsLE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>" <?php if($last_five_lidsList['left_eye']==1){ echo "checked";}?>>  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;-->
									<input type="checkbox" <?php if($active_lids_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $last_five_lidsList['lids_id']; ?>"  class="i-checks m-l  get_lids_priorLE" data-lidsLE-id="<?php echo $last_five_lidsList['lids_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $last_five_lidsList['lids_id']; ?>">  <?php echo $last_five_lidsList['lids_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add lids here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lids_LE" name="srchLidsLE" value="" class="form-control input-lg searchLidsLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
									</td>	
									
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Conjuctiva</font></th>
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="conjuctivaBeforeRE">
								<?php 
								$get_conjuctiva = mysqlSelect("*","examination_ophthal_conjuctiva","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","conjuctiva_name ASC","","","");			
								if(COUNT($get_conjuctiva)>0) { 
									foreach($get_conjuctiva as $get_conjuctivaList) {
										$active_conjuctiva_RE = mysqlSelect("*","doc_patient_conjuctiva_active","md5(episode_id)='".$_GET['episode']."' and  doc_id='".$admin_id."' and doc_type='1' and status='0' and conjuctiva='".$get_conjuctivaList['conjuctiva_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
									?>
									
									<input type="checkbox" <?php if($active_conjuctiva_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>"  class="i-checks m-l  get_conjuctiva_priorRE" data-conjuctivaRE-id="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>">  <?php echo $get_conjuctivaList['conjuctiva_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Conjuctiva here..." data-patient-id="<?php echo $patient_id; ?>" id="get_conjuctiva_RE" name="srchConjuctivaRE" value="" class="form-control input-lg searchConjuctivaRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
								</td>
								<td style="background-color:#dce6e6;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="conjuctivaBefore_LE">
								<?php 
								if(COUNT($get_conjuctiva)>0) { 
									foreach($get_conjuctiva as $get_conjuctivaList) {
										$active_conjuctiva_LE = mysqlSelect("*"," doc_patient_conjuctiva_active","md5(episode_id)='".$_GET['episode']."' and  doc_id='".$admin_id."' and doc_type='1' and status='0' and conjuctiva='".$get_conjuctivaList['conjuctiva_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
									?>
									<input type="checkbox" <?php if($active_conjuctiva_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>"  class="i-checks m-l  get_conjuctiva_priorLE" data-conjuctivaLE-id="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_conjuctivaList['conjuctiva_id']; ?>">  <?php echo $get_conjuctivaList['conjuctiva_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Conjuctiva here..." data-patient-id="<?php echo $patient_id; ?>" id="get_conjuctiva_LE" name="srchConjuctivaLE" value="" class="form-control input-lg searchConjuctivaLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
								</td>
							</tr>

							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Sclera</font></th>
								<td style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
										<div class="input-group">
										<div id="scleraBeforeRE">
										<?php 
										$get_sclera = mysqlSelect("*","examination_ophthal_sclera","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","scelra_name ASC","","","");			
										if(COUNT($get_sclera)>0) { 
											foreach($get_sclera as $get_scleraList) {
												$active_sclera_RE = mysqlSelect("*","doc_patient_sclera_active","md5(episode_id)='".$_GET['episode']."' and  doc_id='".$admin_id."' and doc_type='1' and status='0' and sclera='".$get_scleraList['sclera_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
											?>
											
											<input type="checkbox" <?php if($active_sclera_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_scleraList['sclera_id']; ?>"  class="i-checks m-l  get_sclera_priorRE" data-scleraRE-id="<?php echo $get_scleraList['sclera_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_scleraList['sclera_id']; ?>">  <?php echo $get_scleraList['scelra_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
														
											<?php  }
											}?>
											</div>
										</div>

										
										<div class="ibox">
										<div class="ibox-tools">
											<a class="collapse-link" style="color:#149d81; font-weight:bold;">
											<i class="fa fa-plus"></i> ADD 
											</a>
										</div>
										<div class="ibox-content" style="display: none;">
											<div class="input-group">
												<input type="text" placeholder="Add Sclera here..." data-patient-id="<?php echo $patient_id; ?>" id="get_sclera_RE" name="srchScleraRE" value="" class="form-control input-lg searchScleraRE" tabindex="1">
												<div class="input-group-btn">
												<button class="btn btn-lg btn-primary"  name="" type="button">
													ADD
												</button>
												</div>
											</div>
										</div>
									</div>
										<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="scleraBefore_LE">
								<?php 
								if(COUNT($get_sclera)>0) { 
									foreach($get_sclera as $get_scleraList) {
										$active_sclera_LE = mysqlSelect("*","doc_patient_sclera_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and sclera='".$get_scleraList['sclera_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
									?>
									<input type="checkbox" <?php if($active_sclera_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_scleraList['sclera_id']; ?>"  class="i-checks m-l  get_sclera_priorLE" data-scleraLE-id="<?php echo $get_scleraList['sclera_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_scleraList['sclera_id']; ?>">  <?php echo $get_scleraList['scelra_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Sclera here..." data-patient-id="<?php echo $patient_id; ?>" id="get_sclera_LE" name="srchScleraLE" value="" class="form-control input-lg searchScleraLE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
									<br>
									</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Cornea Anterior Surface</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneatAntBefore_RE">
									<?php 
									$get_corneaAnt = mysqlSelect("*","examination_ophthal_cornea_anterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_ant_name ASC","","","");			
									if(COUNT($get_corneaAnt)>0) { 
										foreach($get_corneaAnt as $get_corneaAntList) {
											$active_cornea_RE = mysqlSelect("*","doc_patient_cornea_ant_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and cornea_ant='".$get_corneaAntList['cornea_ant_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_cornea_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaAntList['cornea_ant_id']; ?>"  class="i-checks m-l  get_corneaAnt_priorRE" data-corneaAntRE-id="<?php echo $get_corneaAntList['cornea_ant_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaAntList['cornea_ant_id']; ?>">  <?php echo $get_corneaAntList['cornea_ant_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Anterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaAnt_RE" name="srchCorneaAntRE" value="" class="form-control input-lg searchCorneaAntRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaAntBefore_LE">
									<?php 
									if(COUNT($get_corneaAnt)>0) { 
										foreach($get_corneaAnt as $get_corneaAntList) {
											$active_cornea_LE = mysqlSelect("*","doc_patient_cornea_ant_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and cornea_ant='".$get_corneaAntList['cornea_ant_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_cornea_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaAntList['cornea_ant_id']; ?>"  class="i-checks m-l  get_corneaAnt_priorLE" data-corneaAntLE-id="<?php echo $get_corneaAntList['cornea_ant_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaAntList['cornea_ant_id']; ?>">  <?php echo $get_corneaAntList['cornea_ant_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Anterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaAnt_LE" name="srchCoorneaAntLE" value="" class="form-control input-lg searchCorneaAntLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Cornea Posterior Surface</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaPostBefore_RE">
									<?php 
									$get_corneaPost = mysqlSelect("*","examination_ophthal_cornea_posterior","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","cornea_post_name ASC","","","");			
									if(COUNT($get_corneaPost)>0) { 
										foreach($get_corneaPost as $get_corneaPostList) {
											$active_corneaPost_RE = mysqlSelect("*","doc_patient_cornea_post_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and cornea_post='".$get_corneaPostList['cornea_post_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_corneaPost_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaPostList['cornea_post_id']; ?>"  class="i-checks m-l  get_corneaPost_priorRE" data-corneaPostRE-id="<?php echo $get_corneaPostList['cornea_post_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaPostList['cornea_post_id']; ?>">  <?php echo $get_corneaPostList['cornea_post_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Posterior here..." data-patient-id="<?php echo $patient_id; ?>" id="get_corneaPost_RE" name="srchCorneaPostRE" value="" class="form-control input-lg searchCorneaPostRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="corneaPostBefore_LE">
									<?php 
									if(COUNT($get_corneaPost)>0) { 
										foreach($get_corneaPost as $get_corneaPostList) {
											$active_corneaPost_LE = mysqlSelect("*","doc_patient_cornea_post_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and cornea_post='".$get_corneaPostList['cornea_post_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_corneaPost_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_corneaPostList['cornea_post_id']; ?>"  class="i-checks m-l  get_corneaPost_priorLE" data-corneaPostLE-id="<?php echo $get_corneaPostList['cornea_post_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_corneaPostList['cornea_post_id']; ?>">  <?php echo $get_corneaPostList['cornea_post_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Cornea Posterior here..." data-patient-id="<?php echo $patient_id; ?>" id="" name="srchCorneaPostLE" value="" class="form-control input-lg searchCorneaPostLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">get_corneaPost_LE
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Anterior Chamber</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="chamberBefore_RE">
									<?php 
									$get_chamber = mysqlSelect("*","examination_ophthal_chamber","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","chamber_name ASC","","","");			
									if(COUNT($get_chamber)>0) { 
										foreach($get_chamber as $get_chamberList) {
											$active_chamber_RE = mysqlSelect("*","doc_patient_anterior_chamber_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and chamber='".$get_chamberList['chamber_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_chamber_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_chamberList['chamber_id']; ?>"  class="i-checks m-l  get_chamber_priorRE" data-chamberRE-id="<?php echo $get_chamberList['chamber_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_chamberList['chamber_id']; ?>">  <?php echo $get_chamberList['chamber_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Anterior Chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_chamber_RE" name="srchChamberRE" value="" class="form-control input-lg searchChamberRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="chamberBefore_LE">
									<?php 
									if(COUNT($get_chamber)>0) { 
										foreach($get_chamber as $get_chamberList) {
											$active_chamber_LE = mysqlSelect("*","doc_patient_anterior_chamber_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and chamber='".$get_chamberList['chamber_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_chamber_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_chamberList['chamber_id']; ?>"  class="i-checks m-l  get_chamber_priorLE" data-chamberLE-id="<?php echo $get_chamberList['chamber_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_chamberList['chamber_id']; ?>">  <?php echo $get_chamberList['chamber_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Anterior Chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_chamber_LE" name="srchChamberLE" value="" class="form-control input-lg searchChamberLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Iris</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="irisBefore_RE">
									<?php 
									$get_iris = mysqlSelect("*","examination_ophthal_iris","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","iris_name ASC","","","");			
									if(COUNT($get_iris)>0) { 
										foreach($get_iris as $get_irisList) {
											$active_iris_RE = mysqlSelect("*","doc_patient_iris_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and iris='".$get_irisList['iris_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_iris_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_irisList['iris_id']; ?>"  class="i-checks m-l  get_iris_priorRE" data-irisRE-id="<?php echo $get_irisList['iris_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_irisList['iris_id']; ?>">  <?php echo $get_irisList['iris_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Iris here..." data-patient-id="<?php echo $patient_id; ?>" id="get_iris_RE" name="srchIrisRE" value="" class="form-control input-lg searchIrisRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="irisBefore_LE">
									<?php 
									if(COUNT($get_iris)>0) { 
										foreach($get_iris as $get_irisList) {
											$active_iris_LE = mysqlSelect("*","doc_patient_iris_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and iris='".$get_irisList['iris_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_iris_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_irisList['iris_id']; ?>"  class="i-checks m-l  get_iris_priorLE" data-irisLE-id="<?php echo $get_irisList['iris_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_irisList['iris_id']; ?>">  <?php echo $get_irisList['iris_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Iris here..." data-patient-id="<?php echo $patient_id; ?>" id="get_iris_LE" name="srchIrisLE" value="" class="form-control input-lg searchIrisLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Pupil</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="pupilBefore_RE">
									<?php 
									$get_pupil = mysqlSelect("*","examination_ophthal_pupil","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","pupil_name ASC","","","");			
									if(COUNT($get_pupil)>0) { 
										foreach($get_pupil as $get_pupilList) {
											$active_pupil_RE = mysqlSelect("*","doc_patient_pupil_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and pupil='".$get_pupilList['pupil_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_pupil_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_pupilList['pupil_id']; ?>"  class="i-checks m-l  get_pupil_priorRE" data-pupilRE-id="<?php echo $get_pupilList['pupil_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_pupilList['pupil_id']; ?>">  <?php echo $get_pupilList['pupil_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Pupil here..." data-patient-id="<?php echo $patient_id; ?>" id="get_pupil_RE" name="srchPupilRE" value="" class="form-control input-lg searchPupilRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="pupilBefore_LE">
									<?php 
									if(COUNT($get_pupil)>0) { 
										foreach($get_pupil as $get_pupilList) {
											$active_pupil_LE = mysqlSelect("*","doc_patient_pupil_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and pupil='".$get_pupilList['pupil_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_pupil_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_pupilList['pupil_id']; ?>"  class="i-checks m-l  get_pupil_priorLE" data-pupilLE-id="<?php echo $get_pupilList['pupil_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_pupilList['pupil_id']; ?>">  <?php echo $get_pupilList['pupil_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Pupil here..." data-patient-id="<?php echo $patient_id; ?>" id="get_pupil_LE" name="srchPupilLE" value="" class="form-control input-lg searchPupilLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Angle of Anterior Chamber</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="angleBefore_RE">
									<?php 
									$get_angle = mysqlSelect("*","examination_ophthal_angle","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","angle_name ASC","","","");			
									if(COUNT($get_angle)>0) { 
										foreach($get_angle as $get_angleList) {
											$active_angle_RE = mysqlSelect("*","doc_patient_angle_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and angle='".$get_angleList['angle_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_angle_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_angleList['angle_id']; ?>"  class="i-checks m-l  get_angle_priorRE" data-angleRE-id="<?php echo $get_angleList['angle_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_angleList['angle_id']; ?>">  <?php echo $get_angleList['angle_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add angle of anterior chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_angle_RE" name="srchAngleRE" value="" class="form-control input-lg searchAngleRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="angleBefore_LE">
									<?php 
									if(COUNT($get_angle)>0) { 
										foreach($get_angle as $get_angleList) {
											$active_angle_LE = mysqlSelect("*","doc_patient_angle_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and angle='".$get_angleList['angle_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_angle_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_angleList['angle_id']; ?>"  class="i-checks m-l  get_angle_priorLE" data-angleLE-id="<?php echo $get_angleList['angle_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_angleList['angle_id']; ?>">  <?php echo $get_angleList['angle_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add angle of anterior chamber here..." data-patient-id="<?php echo $patient_id; ?>" id="get_angle_LE" name="srchAngleLE" value="" class="form-control input-lg searchAngleLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Lens</font></th>
								<td style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="lensBefore_RE">
									<?php 
									$get_lens = mysqlSelect("*","examination_ophthal_lens","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","lens_name ASC","","","");			
									if(COUNT($get_lens)>0) { 
										foreach($get_lens as $get_lensList) {
											$active_lens_RE = mysqlSelect("*","doc_patient_lens_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and lens='".$get_lensList['lens_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_lens_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_lensList['lens_id']; ?>"  class="i-checks m-l  get_lens_priorRE" data-lensRE-id="<?php echo $get_lensList['lens_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_lensList['lens_id']; ?>">  <?php echo $get_lensList['lens_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Lens here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lens_RE" name="srchLensRE" value="" class="form-control input-lg searchLensRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="lensBefore_LE">
									<?php 
									if(COUNT($get_lens)>0) { 
										foreach($get_lens as $get_lensList) {
											$active_lens_LE = mysqlSelect("*","doc_patient_lens_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and lens='".$get_lensList['lens_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_lens_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_lensList['lens_id']; ?>"  class="i-checks m-l  get_lens_priorLE" data-lensLE-id="<?php echo $get_lensList['lens_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_lensList['lens_id']; ?>">  <?php echo $get_lensList['lens_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Lens here..." data-patient-id="<?php echo $patient_id; ?>" id="get_lens_LE" name="srchLensLE" value="" class="form-control input-lg searchLensLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<thead >
									<th  style="vertical-align:middle;text-align:center;" colspan="3"><font size="3">Posterior Segment Findings (Retina)</font></th>
							</thead>
								
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Viterous</font></th>
								<td  style="background-color:#cde2e2;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="viterousBefore_RE">
									<?php 
									$get_viterous = mysqlSelect("*","examination_ophthal_viterous","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","viterous_name ASC","","","");			
									if(COUNT($get_viterous)>0) { 
										foreach($get_viterous as $get_viterousList) {
											$active_viterous_RE = mysqlSelect("*","doc_patient_viterous_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and viterous='".$get_viterousList['viterous_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
										?>
										
										<input type="checkbox" <?php if($active_viterous_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_viterousList['viterous_id']; ?>"  class="i-checks m-l  get_viterous_priorRE" data-viterousRE-id="<?php echo $get_viterousList['viterous_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_viterousList['viterous_id']; ?>">  <?php echo $get_viterousList['viterous_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
													
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Viterous here..." data-patient-id="<?php echo $patient_id; ?>" id="get_viterous_RE" name="srchViterousRE" value="" class="form-control input-lg searchViterousRE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
									<br>
								</div>
								</td>
								<td style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="viterousBefore_LE">
									<?php 
									if(COUNT($get_viterous)>0) { 
										foreach($get_viterous as $get_viterousList) {
											$active_viterous_LE = mysqlSelect("*","doc_patient_viterous_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and viterous='".$get_viterousList['viterous_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_viterous_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_viterousList['viterous_id']; ?>"  class="i-checks m-l  get_viterous_priorLE" data-viterousLE-id="<?php echo $get_viterousList['viterous_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_viterousList['viterous_id']; ?>">  <?php echo $get_viterousList['viterous_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Viterous here..." data-patient-id="<?php echo $patient_id; ?>" id="get_viterous_LE" name="srchViterousLE" value="" class="form-control input-lg searchViterousLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
							<tr>
								<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Fundus</font></th>
								<td  style="background-color:#cde2e2;">
								<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									
								<div class="input-group">
								<div id="fundusBefore_RE">
								<?php 
								$get_fundus = mysqlSelect("*","examination_ophthal_fundus","(doc_id='".$admin_id."' and doc_type='1') or (doc_id='0' and doc_type='0')","fundus_name ASC","","","");			
								if(COUNT($get_fundus)>0) { 
									foreach($get_fundus as $get_fundusList) {
										$active_fundus_RE = mysqlSelect("*","doc_patient_fundus_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and fundus='".$get_fundusList['fundus_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='1'","","","","");			
								
									?>
									
									<input type="checkbox" <?php if($active_fundus_RE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_fundusList['fundus_id']; ?>"  class="i-checks m-l  get_fundus_priorRE" data-fundusRE-id="<?php echo $get_fundusList['fundus_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_fundusList['fundus_id']; ?>">  <?php echo $get_fundusList['fundus_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
												
									<?php  }
									}?>
									</div>
								</div>

								
								<div class="ibox">
								<div class="ibox-tools">
									<a class="collapse-link" style="color:#149d81; font-weight:bold;">
									<i class="fa fa-plus"></i> ADD
									</a>
								</div>
								<div class="ibox-content" style="display: none;">
									<div class="input-group">
										<input type="text" placeholder="Add Fundus here..." data-patient-id="<?php echo $patient_id; ?>" id="get_fundus_RE" name="srchFundusRE" value="" class="form-control input-lg searchFundusRE" tabindex="1">
										<div class="input-group-btn">
										<button class="btn btn-lg btn-primary"  name="" type="button">
											ADD
										</button>
										</div>
									</div>
								</div>
							</div>
								<br>
							</div>
								</td>
								<td  style="background-color:#dce6e6;">
									<div class="col-lg-12" style="border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
										
									<div class="input-group">
									<div id="fundusBefore_LE">
									<?php 
									if(COUNT($get_fundus)>0) { 
										foreach($get_fundus as $get_fundusList) {
											$active_fundus_LE = mysqlSelect("*","doc_patient_fundus_active","md5(episode_id)='".$_GET['episode']."' and doc_id='".$admin_id."' and doc_type='1' and status='0' and fundus='".$get_fundusList['fundus_id']."' and md5(patient_id)='".$_GET['p']."' and eye_side='2'","","","","");			
								
										?>
										<input type="checkbox" <?php if($active_fundus_LE==true){ echo "checked"; } ?> style=" width: 20px;height: 20px; vertical-align: bottom; margin-right:5px;" name="<?php echo $get_fundusList['fundus_id']; ?>"  class="i-checks m-l  get_fundus_priorLE" data-fundusLE-id="<?php echo $get_fundusList['fundus_id']; ?>" data-patient-id="<?php echo $patient_id; ?>" value="<?php echo $get_fundusList['fundus_id']; ?>">  <?php echo $get_fundusList['fundus_name']; ?></label>&nbsp;&nbsp;&nbsp;&nbsp;
										<?php  }
										}?>
										</div>
									</div>

									
									<div class="ibox">
									<div class="ibox-tools">
										<a class="collapse-link" style="color:#149d81; font-weight:bold;">
										<i class="fa fa-plus"></i> ADD
										</a>
									</div>
									<div class="ibox-content" style="display: none;">
										<div class="input-group">
											<input type="text" placeholder="Add Fundus here..." data-patient-id="<?php echo $patient_id; ?>" id="get_fundus_LE" name="srchFundusLE" value="" class="form-control input-lg searchFundusLE" tabindex="1">
											<div class="input-group-btn">
											<button class="btn btn-lg btn-primary"  name="" type="button">
												ADD
											</button>
											</div>
										</div>
									</div>
								</div>
										<br>
										</div>
								</td>
							</tr>
							
									<tr>
									<th style="vertical-align:middle;color:#fff;background-color:#1a2530;"> <font size="2">Refraction</font></th>
									<td  style="background-color:#cde2e2;"> 
									<div class="wrapper" style="position: relative;">
									<input type="number" step="1" style="margin-left: 100px;
																margin-top: 50px;
																 width: 60px; text-align:center;" name="se_refractionRE_value1" id="se_refractionRE_value1" placeholder="Add " data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['refraction_right_value1'];  ?>" data-patient-id="<?php echo $patient_id; ?>" tabindex="1" />
											
										<hr class="vertical1" style=" transform: rotate(90deg);
																  margin-left: 100px;
																  margin-top: 50px;
																  width: 150px;
																  border: 0; border-top: 1px solid red;">
										<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																					  margin-top: 50px;
																					  margin-left: 150px;
																					  width: 200px;">
									  <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="se_refractionRE_value2" id="se_refractionRE_value2" placeholder="Add " data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['refraction_right_value2'];  ?>" data-patient-id="<?php echo $patient_id; ?>" tabindex="1" />
									
									</div>
									</td>
									<td style="background-color:#dce6e6;">
									<div class="wrapper" style="position: relative;">
									<input type="number" step="1" style="margin-left: 100px;
																margin-top: 50px;
																 width: 60px; text-align:center;" name="se_refractionLE_value1" id="se_refractionLE_value1" placeholder="Add " data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['refraction_left_value1'];  ?>" data-patient-id="<?php echo $patient_id; ?>" tabindex="1" />
											
										<hr class="vertical1" style=" transform: rotate(90deg);
																  margin-left: 100px;
																  margin-top: 50px;
																  width: 150px;
																  border: 0; border-top: 1px solid red;">
										<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																					  margin-top: 50px;
																					  margin-left: 150px;
																					  width: 200px;">
									  <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="se_refractionLE_value2" id="se_refractionLE_value2" placeholder="Add " data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['refraction_left_value2'];  ?>" data-patient-id="<?php echo $patient_id; ?>"  tabindex="1" />
									
									</div>
									</td>
									
									</tr>
								
								</tbody>								
								
								</table>
								
								
                                </div>
								<br>
								
								</div>
								
								<br>
								<!-- Edit examination section ends here -->
								
								<!--	 Edit Present Spectacle Prescription Starts here -->
								<?php //if(($secretary_id!=1) || ($check_reception_permission[0]['spectacle_prescription']==1 && $secretary_id==1)){ ?>
                               	<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Present power of glasses</h4>
								
								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
																			<thead>
																				
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#2b3b4b"><font size="3">Left Eye</font></th>
																				
																			</thead>
																			<thead>
																				<th style="color:#fff;background-color:#1a2530;"></th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Vision</th>
																				
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Vision</th>
																			</thead>
																			<tbody>
																			<tr><td style="color:#fff;background-color:#1a2530;">D.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvSpeherRE_Present" name="DvSpeherRE_Present" id="DvSpeherRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['dvSphereRE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvCylRE_Present" name="DvCylRE_Present" id="DvCylRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvCylRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvAxisRE_Present" name="DvAxisRE_Present" id="DvAxisRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvAxisRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvVisionRE_Present" name="DvVisionRE_Present" id="DvVisionRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvVisionRE'];  ?>" style="width:100%;"></td>
																			
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName DvSpeherLE_Present" name="DvSpeherLE_Present" id="DvSpeherLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvSpeherLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName DvCylLE_Present" name="DvCylLE_Present" id="DvCylLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvCylLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName DvAxisLE_Present" name="DvAxisLE_Present" id="DvAxisLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvAxisLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName DvVisionLE_Present" name="DvVisionLE_Present" id="DvVisionLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['DvVisionLE'];  ?>" style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">N.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvSpeherRE_Present" name="NvSpeherRE_Present" id="NvSpeherRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvSpeherRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvCylRE_Present" name="NvCylRE_Present" id="NvCylRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvCylRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvAxisRE_Present" name="NvAxisRE_Present" id="NvAxisRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvAxisRE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvVisionRE_Present" name="NvVisionRE_Present" id="NvVisionRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvVisionRE'];  ?>"  style="width:100%;"></td>
																			
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName NvSpeherLE_Present" name="NvSpeherLE_Present" id="NvSpeherLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvSpeherLE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName NvCylLE_Present" name="NvCylLE_Present" id="NvCylLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvCylLE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName NvAxisLE_Present" name="NvAxisLE_Present" id="NvAxisLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvAxisLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#dce6e6;"><input type="text" class="tagName NvVisionLE_Present" name="NvVisionLE_Present" id="NvVisionLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['NvVisionLE'];  ?>" style="width:100%;"></td>
																			
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">IPD</td>
																			<td colspan="4" style="background-color:#cde2e2;"><input type="text" class="tagName IpdRE_Present" name="IpdRE_Present" id="IpdRE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['IpdRE'];  ?>"  style="width:100%;"></td>
																			<td colspan="4" style="background-color:#dce6e6;"><input type="text" class="tagName IpdLE_Present" name="IpdLE_Present" id="IpdLE_Present" data-spectacle-id="<?php echo $edit_present_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_present_spectacal_presc[0]['IpdLE'];  ?>"  style="width:100%;"></td>
																			</tr>
																			</tbody>
																			
																		</table>
								
								
								</div>
                               	<br>
								<!-- Edit Present Spectacle Prescription Ends here -->
								<?php //}?>
								
								<!-- edit_iinvsetigation_section starts here -->
								<?php include_once('../edit_invsetigation_section.php'); ?>
								<!-- edit_iinvsetigation_section ends here -->
							
								<!-- edit_diagnosis_section starts here -->
								<?php include_once('../edit_diagnosis_section.php'); ?>
								<!-- edit_diagnosis_section ends here -->
								
																
								<!-- edit_treatment_advise_section starts here -->
								<?php include_once('../edit_treatment_advise_section.php'); ?>
								<!-- edit_treatment_advise_section ends here -->
								
                               	<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Spectacle Prescriptions</h4>
								
								<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
																			<thead>
																				
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#1a2530;"><font size="3">Right Eye</font></th>
																				<th colspan="5" class="text-center" style="color:#fff;background-color:#2b3b4b"><font size="3">Left Eye</font></th>
																				
																			</thead>
																			<thead>
																				<th style="color:#fff;background-color:#1a2530;"></th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#1a2530;">Vision</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Sphere</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Cyl</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Axis</th>
																				<th class="text-center" style="color:#fff;background-color:#2b3b4b;">Vision</th>
																			</thead>
																			<tbody>
																			<tr ><td style="color:#fff;background-color:#1a2530;">D.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvSpeherRE" name="DvSpeherRE" id="DvSpeherRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['dvSphereRE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvCylRE" name="DvCylRE" id="DvCylRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvCylRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvAxisRE" name="DvAxisRE" id="DvAxisRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvAxisRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvVisionRE" name="DvVisionRE" id="DvVisionRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvVisionRE'];  ?>" style="width:100%;"></td>
																			
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvSpeherLE" name="DvSpeherLE" id="DvSpeherLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvSpeherLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvCylLE" name="DvCylLE" id="DvCylLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvCylLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvAxisLE" name="DvAxisLE" id="DvAxisLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvAxisLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName DvVisionLE" name="DvVisionLE" id="DvVisionLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['DvVisionLE'];  ?>" style="width:100%;"></td>
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">N.V</td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvSpeherRE" name="NvSpeherRE" id="NvSpeherRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvSpeherRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvCylRE" name="NvCylRE" id="NvCylRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvCylRE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvAxisRE" name="NvAxisRE" id="NvAxisRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvAxisRE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvVisionRE" name="NvVisionRE" id="NvVisionRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvVisionRE'];  ?>"  style="width:100%;"></td>
																			
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvSpeherLE" name="NvSpeherLE" id="NvSpeherLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvSpeherLE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvCylLE" name="NvCylLE" id="NvCylLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvCylLE'];  ?>"  style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvAxisLE" name="NvAxisLE" id="NvAxisLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvAxisLE'];  ?>" style="width:100%;"></td>
																			<td style="background-color:#cde2e2;"><input type="text" class="tagName NvVisionLE" name="NvVisionLE" id="NvVisionLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['NvVisionLE'];  ?>" style="width:100%;"></td>
																			
																			</tr>
																			
																			<tr><td style="color:#fff;background-color:#1a2530;">IPD</td>
																			<td colspan="4" style="background-color:#cde2e2;"><input type="text" class="tagName IpdRE" name="IpdRE" id="IpdRE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['IpdRE'];  ?>"  style="width:100%;"></td>
																			<td colspan="4" style="background-color:#cde2e2;"><input type="text" class="tagName IpdLE" name="IpdLE" id="IpdLE" data-spectacle-id="<?php echo $edit_spectacal_presc[0]['spectacle_id']; ?>" value="<?php echo $edit_spectacal_presc[0]['IpdLE'];  ?>"  style="width:100%;"></td>
																			</tr>
																			</tbody>
																			
																		</table>
								
								
								</div>
                               	<br>
								<!-- edit_treatment_advise_section starts here -->
								<?php include_once('../edit_prescription_section.php'); ?>
								<!-- edit_treatment_advise_section ends here -->
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								
								<div class="form-group">
								 
								 <div class="col-lg-4 pull-right">
									<dl>
										<dt>Next Follow Up Date</dt><br> <dd><div class="pull-left m-r input-group date">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded1" name="dateadded" type="text" placeholder="Select date" value="" class="form-control" tabindex="9"/>
										</div></dd><br>
									</dl>
									<br>
									<a href="../print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo $_GET['episode']; ?>"><button class="btn btn-sm btn-primary pull-right m-b" name="save_patient_print" id="save_patient_print" <?php if($_GET['p']=="0") { echo "disabled"; } ?> type="submit"><strong><i class="fa fa-print"></i> PRINT EMR</strong></button></a>
									<br>
								</div>
								
								</div>
								</div>
								
								
                            </div>
							<?php } ?>
                       
						
						
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
								<button type="button" id="attachReport" class="btn btn-w-m btn-info pull-right">Attach reports</button>
								<div class="row" id="ReportSection">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="../my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
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
								 <div class="form-group">
									<div class="col-lg-6 pull-right">
									<button type="button" id="cancel" class="btn btn-primary m-b ">CANCEL</button>
								
									<button type="submit" name="addAttachments" class="btn btn-primary m-b m-r">UPLOAD REPORTS</button>
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
                                            <img alt="image" class="img-circle" src="../../assets/img/anonymous-profile.png">
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
										$imgIcon="../patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments'];
									}
									else if($extractPath=="docx"){
											$imgIcon="../../assets/images/doc.png";
									}
									else if($extractPath=="pdf" || $extractPath=="PDF"){
										$imgIcon="../../assets/images/pdf.png";
									} 
									
									?>
									
									<div class="file-box">
										<div class="file">
											<a href="#">
												<span class="corner"></span>
												<a href="<?php echo "../patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
												<div class="image">
													<img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
													
												</div></a>
											<div class="file-name">
													<?php echo substr($attachList['attachments'],0,10); ?>
													<br/>
													<small><a href="<?php echo "../patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a>
													<!--<a href="https://medisensecrm.com/premium/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&episode_attach=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a>--></small>
												<!--<small class="pull-right"><a href="#" claas="delAttachments" data-report-id="<?php echo md5($attachList['report_id']);?>" data-report-folder="<?php echo $attachList['report_folder'];?>" style="color:red;" title="Delete"><i class="fa fa-trash"></i></a></small>-->
												</div>
											</a>

										</div>
									</div>
									
									<?php  } ?>
									  

									</ul>
                                        </div>
                                    </div>
                              
							<?php } ?>
							</div>
							</div>
							
							<!-- END VIEW REPORT SECTION -->
							
					
					<!-- View Trend Analysis -->
					<div class="row white-bg page-heading" id="view-trend-analysis">
								<h2><i class="fa fa-line-chart"></i> Trend Analysis</h2>
								<div class="m-t m-b" style="margin-bottom:120px;">
										<div style="width:100%; height:400px;">
												<canvas id="canvas" ></canvas>
										</div>
								</div>
								<br><br><br><br><br><br><br><br>
										<a href="javascript:void(0);" data-toggle="collapse" data-target="#demo" class="btn btn-primary pull-right" ></i> ADD</a>
										<div id="demo" class="collapse col-lg-10">
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php" autocomplete="off" name="frmAddGlucoseCount" id="frmAddGlucoseCount">
										<input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                                        
										<!-- <table class="table table-bordered">
											
												<tr>
												<thead><th colspan="2" class="text-center">Add Details</th></thead>
												<tbody>
												<tr><th>Date</th><td><div class="input-group date">
													<input id="dateadded" name="dateadded" type="text" required placeholder="DD/MM/YYYY" class="form-control" >
													</div></td></tr>
																
												<tr><th>DV Sphere (RE)</th><td><input type="text" id="before_meals" name="before_meals" value="" maxlength="3" class="form-control"></td></tr>
												<tr><th>DV Cyl (RE)</th><td><input type="text" id="after_meals" name="after_meals" value="" maxlength="3" class="form-control"></td></tr>
												<tr><th>DV Cyl (RE)</th><td><input name="systolicCount" type="text" class="form-control" ></td></tr>
												<tr><th>Diastolic</th><td><input type="text" name="diastolicCount" value="" class="form-control"></td></tr>
												<tr><th>Glyco Hb(HbA1c)</th><td><input type="text" name="hba1cCount" value="" class="form-control"></td></tr>
												<tr><th>HDL CHOLESTEROL</th><td><input  name="hdlCount" type="text"  class="form-control" ></td></tr>
												<tr><th>VLDL</th><td><input name="vldlCount" type="text" class="form-control" ></td></tr>
												<tr><th>LDL CHOLESTEROL</th><td><input name="ldlCount" type="text"  class="form-control" ></td></tr>
												<tr><th>TRIGLYCERIDES</th><td><input type="text" name="triglycerideCount" value="" class="form-control"></td></tr>
												<tr><th>TOTAL CHOLESTEROL</th><td><input type="text" name="cholestrolCount"  value="" class="form-control"></td></tr>
												<tr><td colspan="2"><button type="submit" name="addPrandialCount" class="btn btn-primary pull-right">SUBMIT</button></td></tr>
												</tbody>
											
											
											 
										</table>  -->
										
										<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
										
											<tr>
												<thead><th colspan="8" class="text-center">Add Details</th></thead>
												<tbody>
												<tr><th colspan="6" class="text-right">Select Date</th><td><div class="input-group date">
													<input id="date_modified" name="dateadded" type="text" required placeholder="DD/MM/YYYY" class="form-control" >
													</div></td></tr>
													
												<thead>
																				
												<th colspan="4" class="text-center"><font size="3">Right Eye</font></th>
												<th colspan="4" class="text-center"><font size="3">Left Eye</font></th>
																				
												</thead>
												<thead>
													<th></th>
													<th class="text-center">Sphere</th>
													<th class="text-center">Cyl</th>
													<th class="text-center">Axis</th>
													<th class="text-center">Sphere</th>
													<th class="text-center">Cyl</th>
													<th class="text-center">Axis</th>
												</thead>
												<tbody>
													<tr><td>D.V</td>
													<td><input type="text" class="tagName" name="txtDvSpeherRE" id="txtDvSpeherRE"  style="width:130px;border:none;"></td>
													<td><input type="text" class="tagName" name="txtDvCylRE" id="txtDvCylRE"  style="width:130px;border:none;"></td>
													<td><input type="text" class="tagName" name="txtDvAxisRE" id="txtDvAxisRE"  style="width:130px;border:none;"></td>
													<td><input type="text" class="tagName" name="txtDvSpeherLE" id="txtDvSpeherLE"  style="width:130px;border:none;"></td>
													<td><input type="text" class="tagName" name="txtDvCylLE" id="txtDvCylLE"  style="width:130px;border:none;"></td>
													<td><input type="text" class="tagName" name="txtDvAxisLE" id="txtDvAxisLE"  style="width:130px;border:none;"></td>
													</tr>
																			
													<tr><td>N.V</td>
														<td><input type="text" class="tagName" name="txtNvSpeherRE" id="txtNvSpeherRE"  style="width:130px;border:none;"></td>
														<td><input type="text" class="tagName" name="txtNvCylRE" id="txtNvCylRE"  style="width:130px;border:none;"></td>
														<td><input type="text" class="tagName" name="txtNvAxisRE" id="txtNvAxisRE"  style="width:130px;border:none;"></td>
														<td><input type="text" class="tagName" name="txtNvSpeherLE" id="txtNvSpeherLE"  style="width:130px;border:none;"></td>
														<td><input type="text" class="tagName" name="txtNvCylLE" id="txtNvCylLE"  style="width:130px;border:none;"></td>
														<td><input type="text" class="tagName" name="txtNvAxisLE" id="txtNvAxisLE"  style="width:130px;border:none;"></td>
														</tr>
																			
													<tr><td>IPD</td>
														<td colspan="3"><input type="text" class="tagName" name="txtIpdRE" id="txtIpdRE"  style="width:400px;border:none;"></td>
														<td colspan="3"><input type="text" class="tagName" name="txtIpdLE" id="txtIpdLE"  style="width:400px;border:none;"></td>
													</tr>
													<br>
													<tr><td colspan="8" class="text-right"><button type="submit" name="addTrendsOpthal" class="btn btn-primary pull-right">SUBMIT</button></td></tr>
												
												</tbody>
																			
											</table>
										
										</form>
								</div>			
							
							<div claas="form-control m-t-xs" style="margin-top:30px;">
										<table class="table table-responsive table-bordered">
                            <tbody>
						
					
                            <tr>
								<thead>
                                <th>Spectacle Test</th><?php while(list($key, $value) = each($get_OphthalTrendAnalysisDate1)){ ?><th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th><?php } ?></thead>
							</tr>
							
							<tr>
                                <th>DvSphereRE</th><?php while(list($key, $value) = each($get_DvSphereRE1)){ ?><td><?php echo $value['DvSphereRE']; ?></td><?php } ?>
							
								
							</tr>
							
							<tr>
                                <th>DvCylRE</th><?php while(list($key, $value) = each($get_DvCylRE1)){ ?><td><?php echo $value['DvCylRE']; ?></td><?php } ?>
							</tr>
								<th>DvAxisRE</th><?php while(list($key, $value) = each($get_DvAxisRE1)){ ?><td><?php echo $value['DvAxisRE']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>DvSpeherLE</th><?php while(list($key, $value) = each($get_DvSpeherLE1)){ ?><td><?php echo $value['DvSpeherLE']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>DvCylLE</th><?php while(list($key, $value) = each($get_DvCylLE1)){ ?><td><?php echo $value['DvCylLE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>DvAxisLE</th><?php while(list($key, $value) = each($get_DvAxisLE1)){ ?><td><?php echo $value['DvAxisLE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>NvSpeherRE</th><?php while(list($key, $value) = each($get_NvSpeherRE1)){ ?><td><?php echo $value['NvSpeherRE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>NvCylRE</th><?php while(list($key, $value) = each($get_NvCylRE1)){ ?><td><?php echo $value['NvCylRE']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>NvAxisRE</th><?php while(list($key, $value) = each($get_NvAxisRE1)){ ?><td><?php echo $value['NvAxisRE']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>NvSpeherLE</th><?php while(list($key, $value) = each($get_NvSpeherLE1)){ ?><td><?php echo $value['NvSpeherLE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>NvCylLE</th><?php while(list($key, $value) = each($get_NvCylLE1)){ ?><td><?php echo $value['NvCylLE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>NvAxisLE</th><?php while(list($key, $value) = each($get_NvAxisLE1)){ ?><td><?php echo $value['NvAxisLE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>IpdRE</th><?php while(list($key, $value) = each($get_IpdRE1)){ ?><td><?php echo $value['IpdRE'];?></td><?php } ?>
							</tr>
							<tr>
								<th>IpdLE</th><?php while(list($key, $value) = each($get_IpdLE1)){ ?><td><?php echo $value['IpdLE'];?></td><?php } ?>
							</tr>
                            
                            </tbody>
							
                        </table>
															
						</div>
							
					</div>
						
					<!-- STAR MEDICAL HISTORY SECTION -->
						<div class="row white-bg page-heading" id="medical-history">
							<h2><i class="fa fa-h-square"></i> Medical Profile</h2>
							
									 
								<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
									<div class="col-lg-5">
									
									<dl>
										<dt>Hypertension:</dt><br> <dd><?php if($patient_tab[0]['hyper_cond']=="1"){ ?>
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
                                    <?php } ?></dd><br>
                                        
                                        <dt>Smoking:</dt><br> <dd><select class="form-control smokeCondition" data-patient-id="<?php echo md5($patient_id); ?>" name="se_smoking" id="se_smoking">
														
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
									</dl>
									
                                    <!--    <dt>Drug Abuse</dt><br> <dd><textarea class="form-control" id="drug_abuse"  name="drug_abuse" rows="2"><?php echo $patient_tab[0]['drug_abuse']; ?></textarea></dd><br>
                                       <dt>Previous Interventions</dt><br> <dd><textarea class="form-control" id="prev_inter"  name="prev_inter" rows="2"><?php echo $patient_tab[0]['prev_inter']; ?></textarea></dd><br>
										<dt>Other Details</dt><br> <dd><textarea class="form-control" id="other_details"  name="other_details" rows="2"><?php echo $patient_tab[0]['other_details']; ?></textarea></dd><br>
									-->
									</div>
									
									<div class="col-lg-7">
									
									<dl>
										<dt>Diabetes:</dt><br> <dd> <?php if($patient_tab[0]['diabetes_cond']=="1"){ ?>
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
									<?php } ?>  </dd><br>
								
								
							
							
									<dt>Alcohol:</dt><br> <dd><select class="form-control alcoholCondtion" data-patient-id="<?php echo md5($patient_id); ?>" name="se_alcoholic" name="se_alcoholic">
														
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
								<label>Frequently used:  </label>
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
								<label>Frequently used:  </label>
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
								 <dt>Previous Interventions</dt><br> <dd><textarea class="form-control prevIntervent" id="prev_inter" data-patient-id="<?php echo md5($patient_id); ?>"  name="prev_inter" rows="2"><?php echo $patient_tab[0]['prev_inter']; ?></textarea></dd><br>
								 <dt>Other Details</dt><br> <dd><textarea class="form-control otherDetail" id="other_details" data-patient-id="<?php echo md5($patient_id); ?>" name="other_details" rows="2"><?php echo $patient_tab[0]['other_details']; ?></textarea></dd><br>
								</dl>
								</div>
								<div class="col-lg-6">	
								<dl>
									<dt>Stroke or known neurological issues</dt><br> <dd><textarea class="form-control neuroIssue" data-patient-id="<?php echo md5($patient_id); ?>" id="neuro_issue" name="neuro_issue" rows="2"><?php echo $patient_tab[0]['neuro_issue']; ?></textarea></dd><br>
									<dt>Known kidney issues</dt><br> <dd><textarea class="form-control kidneyIssue" id="kidney_issue" data-patient-id="<?php echo md5($patient_id); ?>" name="kidney_issue" rows="2"><?php echo $patient_tab[0]['kidney_issue']; ?></textarea></dd><br>
								</dl>
								</div>
								</div>
								<br>
								<!--<button type="submit" name="updatePatient" class="btn btn-primary pull-right"> UPDATE</button>	-->
							
						</div>
						<!-- END MEDICAL HISTORY SECTION -->
						
						
						<!-- View Fundus Image SECTION -->
					<div class="row white-bg page-heading" id="view-fundus-image">
								<h2><i class="fa fa-picture-o"></i> Fundus Image</h2>
							<!--	<div id="circles" style="display:none;">
								  <canvas id="cirCanvas" width="512" height="512"></canvas>
								</div>-->
								<div class="ibox-content">
								<button type="button" id="attachFundusImage" class="btn btn-w-m btn-info pull-right">Attach Fundus Image</button>
								<div class="row" id="fundusImageSection">
								<!--<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  name="frmAddFundusImage" id="frmAddFundusImage">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								<input type="hidden" name="upload_user" value="<?php echo $admin_id; ?>">-->
								
								
								
								
								<label class="col-sm-12"><i class="fa fa-file-medical"></i> Attach Image here ( Allowed file types: jpg, jpeg, png)</label>
                   
									<!--<div class="form-group col-lg-12">
										<div class="file-loading">
										
											<input id="file-5" name="file-5[]" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
										</div>
									</div>-->
								<div class="form-group col-lg-12">
										
										
											<input id="fileUpload" type="file" tabindex="7">
										
									</div>
									
							<!--	 <div class="row" id="image_preview"></div>
								 <div class="row">
								
								</div>
								</form>-->
								
							<div id="circles" style="display:none;">
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  name="frmAddFundusImage" id="frmAddFundusImage">
								<input type="hidden" id="patient_id_fundus" name="patient_id_fundus" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								<input type="hidden" id="upload_user_fundus" name="upload_user_fundus" value="<?php echo $admin_id; ?>">
								
								<label class="col-sm-12">Fundus Image Title <span class="required">*</span></label>

                                    <div class="form-group col-lg-12"><input type="text" id="report_title_fundus" name="report_title_fundus" required="required" class="form-control"></div>
								  <canvas id="cirCanvas" width="512" height="512"></canvas>
								  
								   <div class="form-group col-lg-12">
									<div class="pull-right">
									<button type="button" id="cancelFundusImage" class="btn btn-primary m-b ">CANCEL</button>
								
									<button type="submit" name="addFundusImageAttach" class="btn btn-primary m-b m-r">UPLOAD FUNDUS IMAGE</button>
									</div>
								</div>
								</form>
								</div>
								</div>
							</div>
					</div>
					<!-- END FUNDUS IMAGE SECTION -->
						
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
												<thead><th colspan="2" class="text-center">Add Details</th></thead>
												<tbody>
												<tr><th>Date</th><td><div class="input-group date">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="dateadded" type="text" required placeholder="Select date" class="form-control" >
																</div></td></tr>
																
												<tr><th>BLOOD GLUCOSE (Fasting)</th><td><input type="number" id="before_meals" name="before_meals" value="" maxlength="3" class="form-control"></td></tr>
												<tr><th>BLOOD GLUCOSE (Post Prandial)</th><td><input type="number" id="after_meals" name="after_meals" value="" maxlength="3" class="form-control"></td></tr>
												<tr><th>Systolic</th><td><input name="systolicCount" type="text" class="form-control" ></td></tr>
												<tr><th>Diastolic</th><td><input type="text" name="diastolicCount" value="" class="form-control"></td></tr>
												<tr><th>Glyco Hb(HbA1c)</th><td><input type="text" name="hba1cCount" value="" class="form-control"></td></tr>
												<tr><th>HDL CHOLESTEROL</th><td><input  name="hdlCount" type="text"  class="form-control" ></td></tr>
												<tr><th>VLDL</th><td><input name="vldlCount" type="text" class="form-control" ></td></tr>
												<tr><th>LDL CHOLESTEROL</th><td><input name="ldlCount" type="text"  class="form-control" ></td></tr>
												<tr><th>TRIGLYCERIDES</th><td><input type="text" name="triglycerideCount" value="" class="form-control"></td></tr>
												<tr><th>TOTAL CHOLESTEROL</th><td><input type="text" name="cholestrolCount"  value="" class="form-control"></td></tr>
												<tr><td colspan="2"><button type="submit" name="addPrandialCount" class="btn btn-primary pull-right">SUBMIT</button></td></tr>
												</tbody>
											
											
											
										</table>
										
										</form>
											
										</div>
										<table class="table table-bordered">
                            <tbody>
					
                            <tr>
								<thead>
                                <th>Medical Test</th><?php while(list($key, $value) = each($get_TrendAnalysisDate)){ ?><th><i class="fa fa-calendar"></i> <?php echo date('d-M-Y',strtotime($value['date_added'])); ?></th><?php } ?></thead>
							</tr>
							<tr>
                                <th>Prescription Given</th><?php while(list($key, $value) = each($get_medicineGivenDate)){ 
								$medicineGiven = mysqlSelect("a.prescription_trade_name as prescription_trade_name,a.duration as duration,a.prescription_frequency as prescription_frequency","doc_patient_episode_prescriptions as a inner join doc_patient_episodes as b on a.episode_id=b.episode_id","b.patient_id='".$patient_id."' and DATE_FORMAT(a.prescription_date_time,'%Y-%m-%d')='".$value['date_added']."'","a.episode_prescription_id desc","","","");
							 
								?><td><?php if($medicineGiven==true){ ?><a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $key; ?>" class="item">View Prescription</a>
								
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
                                <th>BLOOD GLUCOSE (Fasting)</th><?php while(list($key, $value) = each($get_TrendAnalysisPPCount)){ ?><td><?php echo $value['bp_beforefood_count']; ?></td><?php } ?>
							
								
							</tr>
							
							<tr>
                                <th>BLOOD GLUCOSE (Post Prandial)</th><?php while(list($key, $value) = each($get_TrendAnalysisPPAfterCount)){ ?><td><?php echo $value['bp_afterfood_count']; ?></td><?php } ?>
							</tr>
								<th>Systolic</th><?php while(list($key, $value) = each($get_TrendAnalysisSystolic)){ ?><td><?php echo $value['systolic']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>Diastolic</th><?php while(list($key, $value) = each($get_TrendAnalysisDiastolic)){ ?><td><?php echo $value['diastolic']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>Glyco Hb(HbA1c)</th><?php while(list($key, $value) = each($get_TrendAnalysisHbA1c)){ ?><td><?php echo $value['HbA1c'];?></td><?php } ?>
							</tr>
							<tr>
								<th>HDL CHOLESTEROL</th><?php while(list($key, $value) = each($get_TrendAnalysisHDL)){ ?><td><?php echo $value['HDL'];?></td><?php } ?>
							</tr>
							<tr>
								<th>VLDL</th><?php while(list($key, $value) = each($get_TrendAnalysisVLDL)){ ?><td><?php echo $value['VLDL'];?></td><?php } ?>
							</tr>
							<tr>
								<th>LDL CHOLESTEROL</th><?php while(list($key, $value) = each($get_TrendAnalysisLDL)){ ?><td><?php echo $value['LDL']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>TRIGLYCERIDES</th><?php while(list($key, $value) = each($get_TrendAnalysisTriglyceride)){ ?><td><?php echo $value['triglyceride']; ?></td><?php } ?>
							</tr>
							<tr>
								<th>TOTAL CHOLESTEROL</th><?php while(list($key, $value) = each($get_TrendAnalysisCholesterol)){ ?><td><?php echo $value['cholesterol'];?></td><?php } ?>
							</tr>
                            
                            </tbody>
							
                        </table>
															
						</div>
                                        <div class="modal-footer">
							
                                            
                                        </div>
										
                                    </div>
                                </div>
								
                        </div>
							
						
						<!-- STAR ALL VISIT DETAILS SECTION -->
						<div class="row white-bg page-heading" id="visit-details">
							 <h2><i class="fa fa-wheelchair"></i> Previous Visit Details</h2>
                        
                               <div class="ibox-content">
							<?php
								

								if (count($patient_episodes) > 0)
								{ ?>
                            <table class="footable table table-stripped toggle-arrow-tiny table-responsive">
                                <thead>
                                <tr>

                                    <th data-toggle="true">VISITS</th>
									 <th data-hide="all">Chief Medical Complaint</th>
									 <th data-hide="all">Examination</th>
									  <th data-hide="all">Present power of glasses</th>
									 <th data-hide="all">Diagnosis</th>
									 <th data-hide="all">Investigations</th>
									 <th data-hide="all">Treatment</th>
									 <th data-hide="all">Reports</th>
									 <th data-hide="all">Spectacle Prescriptions</th>   
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
                                <tr>
                                    <td>#Visit <?php echo $visit_count." - ".$patient_episode_val['formated_date_time']; ?> <a href="../print-emr/?pid=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-print"></i> Print</a>
									<a href="?p=<?php echo md5($patient_tab[0]['patient_id']);?>&episode=<?php echo md5($patient_episode_val['episode_id']); ?>&visit=<?php echo $visit_count; ?>" class="btn btn-white btn-bitbucket pull-right"><i class="fa fa-edit"></i> Edit</a><!--<button name="printBtn" class="pull-right"><i class="fa fa-print"></i> </button>--></td>
									
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
									//$get_examination = mysqlSelect("b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
									$get_exam_lids = mysqlSelect("b.lids_name as lids_name,a.lids as lids","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_type='1'","","","","");
									$get_exam_lidsLE = mysqlSelect("b.lids_name as lids_name,a.lids as lids","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_type='2'","","","","");
									
									$get_exam_conjuctivaRE = mysqlSelect("b.conjuctiva_name as conjuctiva_name,a.conjuctiva as conjuctiva","doc_patient_conjuctiva_active as a left join examination_ophthal_conjuctiva as b on a.conjuctiva=b.conjuctiva_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_conjuctivaLE = mysqlSelect("b.conjuctiva_name as conjuctiva_name,a.conjuctiva as conjuctiva","doc_patient_conjuctiva_active as a left join examination_ophthal_conjuctiva as b on a.conjuctiva=b.conjuctiva_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_scleraRE = mysqlSelect("b.scelra_name as scelra_name,a.sclera as sclera","doc_patient_sclera_active as a left join examination_ophthal_sclera as b on a.sclera=b.sclera_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_scleraLE = mysqlSelect("b.scelra_name as scelra_name,a.sclera as sclera","doc_patient_sclera_active as a left join examination_ophthal_sclera as b on a.sclera=b.sclera_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_cornea_anteriorRE = mysqlSelect("b.cornea_ant_name as cornea_ant_name,a.cornea_ant as cornea_ant","doc_patient_cornea_ant_active as a left join examination_ophthal_cornea_anterior as b on a.cornea_ant=b.cornea_ant_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_cornea_anteriorLE = mysqlSelect("b.cornea_ant_name as cornea_ant_name,a.cornea_ant as cornea_ant","doc_patient_cornea_ant_active as a left join examination_ophthal_cornea_anterior as b on a.cornea_ant=b.cornea_ant_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_cornea_posteriorRE = mysqlSelect("b.cornea_post_name as cornea_post_name,a.cornea_post as cornea_post","doc_patient_cornea_post_active as a left join examination_ophthal_cornea_posterior as b on a.cornea_post=b.cornea_post_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_cornea_posteriorLE = mysqlSelect("b.cornea_post_name as cornea_post_name,a.cornea_post as cornea_post","doc_patient_cornea_post_active as a left join examination_ophthal_cornea_posterior as b on a.cornea_post=b.cornea_post_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_anterior_chamberRE = mysqlSelect("b.chamber_name as chamber_name,a.chamber as chamber","doc_patient_anterior_chamber_active as a left join examination_ophthal_chamber as b on a.chamber=b.chamber_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_anterior_chamberLE = mysqlSelect("b.chamber_name as chamber_name,a.chamber as chamber","doc_patient_anterior_chamber_active as a left join examination_ophthal_chamber as b on a.chamber=b.chamber_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									
									$get_exam_anterior_irisRE = mysqlSelect("b.iris_name as iris_name,a.iris as iris","doc_patient_iris_active as a left join examination_ophthal_iris as b on a.iris=b.iris_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_anterior_irisLE = mysqlSelect("b.iris_name as iris_name,a.iris as iris","doc_patient_iris_active as a left join examination_ophthal_iris as b on a.iris=b.iris_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_pupil_RE = mysqlSelect("b.pupil_name as pupil_name,a.pupil as pupil","doc_patient_pupil_active as a left join examination_ophthal_pupil as b on a.pupil=b.pupil_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_pupil_LE = mysqlSelect("b.pupil_name as pupil_name,a.pupil as pupil","doc_patient_pupil_active as a left join examination_ophthal_pupil as b on a.pupil=b.pupil_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_angle_RE = mysqlSelect("b.angle_name as angle_name,a.angle as angle","doc_patient_angle_active as a left join examination_ophthal_angle as b on a.angle=b.angle_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_angle_LE = mysqlSelect("b.angle_name as angle_name,a.angle as angle","doc_patient_angle_active as a left join examination_ophthal_angle as b on a.angle=b.angle_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_lens_RE = mysqlSelect("b.lens_name as lens_name,a.lens as lens","doc_patient_lens_active as a left join examination_ophthal_lens as b on a.lens=b.lens_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_lens_LE = mysqlSelect("b.lens_name as lens_name,a.lens as lens","doc_patient_lens_active as a left join examination_ophthal_lens as b on a.lens=b.lens_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_viterous_RE = mysqlSelect("b.viterous_name as viterous_name,a.viterous as viterous","doc_patient_viterous_active as a left join examination_ophthal_viterous as b on a.viterous=b.viterous_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_viterous_LE = mysqlSelect("b.viterous_name as viterous_name,a.viterous as viterous","doc_patient_viterous_active as a left join examination_ophthal_viterous as b on a.viterous=b.viterous_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_fundus_RE = mysqlSelect("b.fundus_name as fundus_name,a.fundus as fundus","doc_patient_fundus_active as a left join examination_ophthal_fundus as b on a.fundus=b.fundus_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='1'","","","","");
									$get_exam_fundus_LE = mysqlSelect("b.fundus_name as fundus_name,a.fundus as fundus","doc_patient_fundus_active as a left join examination_ophthal_fundus as b on a.fundus=b.fundus_id","a.episode_id='".$patient_episode_val['episode_id']."' and a.eye_side='2'","","","","");
									
									$get_exam_refraction = mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id = '".$patient_episode_val['episode_id']."' and doc_id = '".$admin_id."' and doc_type = '1'","","","","");
									
									$get_present_spectacal_presc = mysqlSelect("*","present_examination_opthal_spectacle_prescription","episode_id = '".$patient_episode_val['episode_id']."' ","","","","");
									?>
									<table class="table table-bordered">
											<thead>
											<tr>
											<th style="width:220px;">Name</th>
											<th>Right Eye</th>
											<th>Left Eye</th>
											</tr>
											<thead>
											<tbody>
											<tr>
											<td><b>Distace Vision</b></td>
												<td>
													<?php echo "<b>Aided: </b>".$get_exam_refraction[0]['distacnce_vision_right']."&nbsp;&nbsp;&nbsp;&nbsp;<b>Unaided: </b>".$get_exam_refraction[0]['distacnce_vision_right_unaided'];  ?>
												</td>
												<td>
													<?php echo "<b>Aided: </b>".$get_exam_refraction[0]['distance_vision_left']."&nbsp;&nbsp;&nbsp;&nbsp;<b>Unaided: </b>".$get_exam_refraction[0]['distance_vision_left_unaided'];  ?>
												</td>
												</tr>
												<tr>
											<td><b>Near Vision</b></td>
												<td>
													<?php echo "<b>Aided: </b>".$get_exam_refraction[0]['near_vision_right']."&nbsp;&nbsp;&nbsp;&nbsp;<b>Unaided: </b>".$get_exam_refraction[0]['near_vision_right_unaided'];  ?>
												</td>
												<td>
													<?php echo "<b>Aided: </b>".$get_exam_refraction[0]['near_vision_left']."&nbsp;&nbsp;&nbsp;&nbsp;<b>Unaided: </b>".$get_exam_refraction[0]['near_vision_left_unaided'];  ?>
												</td>
												</tr>
												<tr>
											<td><b>IOP</b></td>
											<td><?php echo $get_exam_refraction[0]['IopRE']." mm of HG"; ?></td>
												<td><?php echo $get_exam_refraction[0]['IopLE']." mm of HG";  ?></td>
												</tr>
											<tr>
											<td><b>Lids</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_lids) = each($get_exam_lids))	
														{  
														?>
															<?php echo $value_exam_lids['lids_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_lidsLE) = each($get_exam_lidsLE))	
														{  
														?>
															<?php echo $value_exam_lidsLE['lids_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Conjuctiva</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_conjuctivaRE) = each($get_exam_conjuctivaRE))	
														{  
														?>
															<?php echo $value_exam_conjuctivaRE['conjuctiva_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_conjuctivaLE) = each($get_exam_conjuctivaLE))	
														{  
														?>
															<?php echo $value_exam_conjuctivaLE['conjuctiva_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Sclera</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_scleraRE) = each($get_exam_scleraRE))	
														{  
														?>
															<?php echo $value_exam_scleraRE['scelra_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_scleraLE) = each($get_exam_scleraLE))	
														{  
														?>
															<?php echo $value_exam_scleraLE['scelra_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Cornea Anterior</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_corneaantRE) = each($get_exam_cornea_anteriorRE))	
														{  
														?>
															<?php echo $value_exam_corneaantRE['cornea_ant_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_corneaantLE) = each($get_exam_cornea_anteriorLE))	
														{  
														?>
															<?php echo $value_exam_corneaantLE['cornea_ant_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Cornea Posterior</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_corneapostRE) = each($get_exam_cornea_posteriorRE))	
														{  
														?>
															<?php echo $value_exam_corneapostRE['cornea_post_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_corneapostLE) = each($get_exam_cornea_posteriorLE))	
														{  
														?>
															<?php echo $value_exam_corneapostLE['cornea_post_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Anterior Chamber</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_chamberRE) = each($get_exam_anterior_chamberRE))	
														{  
														?>
															<?php echo $value_exam_chamberRE['chamber_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_chamberLE) = each($get_exam_anterior_chamberLE))	
														{  
														?>
															<?php echo $value_exam_chamberLE['chamber_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Iris</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_irisRE) = each($get_exam_anterior_irisRE))	
														{  
														?>
															<?php echo $value_exam_irisRE['iris_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_irisLE) = each($get_exam_anterior_irisLE))	
														{  
														?>
															<?php echo $value_exam_irisLE['iris_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Pupil</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_ipupilRE) = each($get_exam_pupil_RE))	
														{  
														?>
															<?php echo $value_exam_ipupilRE['pupil_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_pupilLE) = each($get_exam_pupil_LE))	
														{  
														?>
															<?php echo $value_exam_pupilLE['pupil_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Angle of Anterior Chamber</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_angleRE) = each($get_exam_angle_RE))	
														{  
														?>
															<?php echo $value_exam_angleRE['angle_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_angleLE) = each($get_exam_angle_LE))	
														{  
														?>
															<?php echo $value_exam_angleLE['angle_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Lens</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_lensRE) = each($get_exam_lens_RE))	
														{  
														?>
															<?php echo $value_exam_lensRE['lens_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_lensLE) = each($get_exam_lens_LE))	
														{  
														?>
															<?php echo $value_exam_lensLE['lens_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
									
									
											<tr>
												<td><b>Viterous</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_viterousRE) = each($get_exam_viterous_RE))	
														{  
														?>
															<?php echo $value_exam_viterousRE['viterous_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_viterousLE) = each($get_exam_viterous_LE))	
														{  
														?>
															<?php echo $value_exam_viterousLE['viterous_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Fundus</b></td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_fundusRE) = each($get_exam_fundus_RE))	
														{  
														?>
															<?php echo $value_exam_fundusRE['fundus_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												<td>
													<?php 
														while(list($key_exam, $value_exam_fundusLE) = each($get_exam_fundus_LE))	
														{  
														?>
															<?php echo $value_exam_fundusLE['fundus_name'];  ?>
															<?php echo ", ";  ?>
														<?php } //end while
													?>
												</td>
												</tr>
												
												<tr>
												<td><b>Refraction</b></td> 
												<td>
													<div class="wrapper" style="position: relative;">
													
													<input type="number" step="1" style="margin-left: 100px;
																 margin-top: 50px;
																 width: 60px; text-align:center;" name=""  value="<?php echo $get_exam_refraction[0]['refraction_right_value1']; ?>"  id="" name="" value="" class="searchRefractionRE" tabindex="1" />
									
																										
													<hr class="vertical1" style=" transform: rotate(90deg);
																					  margin-left: 100px;
																					  margin-top: 50px;
																					  width: 150px;
																					  border: 0; border-top: 1px solid red;">
													<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																										  margin-top: 50px;
																										  margin-left: 150px;
																										  width: 200px;">
												 <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="" placeholder=" " value="<?php echo $get_exam_refraction[0]['refraction_right_value2']; ?>" id="" name="" value="" class="searchRefractionRE" tabindex="1" />
								
													</div>
												</td>
												<td>
													<div class="wrapper" style="position: relative;">
														
								<input type="number" step="1" style="margin-left: 100px;
																 margin-top: 50px;
																 width: 60px; text-align:center;" name=""  value="<?php echo $get_exam_refraction[0]['refraction_left_value1']; ?>"  id="" name="" value="" class="searchRefractionRE" tabindex="1" />
																							
													<hr class="vertical1" style=" transform: rotate(90deg);
																					  margin-left: 100px;
																					  margin-top: 50px;
																					  width: 150px;
																					  border: 0; border-top: 1px solid red;">
													<hr class="horizontal1" style=" border: 0; border-top: 1px solid red; 
																										  margin-top: 50px;
																										  margin-left: 150px;
																										  width: 200px;">
													 <input type="number" step="1" style="margin-top: 0px;
																margin-left: 300px;
																width: 60px;text-align:center;" name="" placeholder=" " value="<?php echo $get_exam_refraction[0]['refraction_left_value2']; ?>" id="" name="" value="" class="searchRefractionRE" tabindex="1" />
									 
													</div>
												</td>
												</tr>
									
									
											   </tbody>
										</table>
									
									<br><br></td>
									
									<!-- DISPLAY PRESENT POWER OF GLASSES -->
									
									<td>
									<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				
																				<th colspan="5" class="text-center">Right Eye</th>
																				<th colspan="4" class="text-center">Left Eye</th>
																				
																			</thead>
																			<thead>
																				<th></th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																				<th>Vision</th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																				<th>Vision</th>
																			</thead>
																			<tbody>
																			<tr><td>D.V</td>
																			<td><?php echo $get_present_spectacal_presc[0]['dvSphereRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvCylRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvAxisRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvVisionRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvSpeherLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvCylLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvAxisLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['DvVisionLE']; ?></td>
																			</tr>
																			
																			<tr><td>N.V</td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvSpeherRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvCylRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvAxisRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvVisionRE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvSpeherLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvCylLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvAxisLE']; ?></td>
																			<td><?php echo $get_present_spectacal_presc[0]['NvVisionLE']; ?></td>
																			</tr>
																			
																			<tr><td>IPD</td>
																			<td colspan="4"><?php echo $get_present_spectacal_presc[0]['IpdRE']; ?></td>
																			<td colspan="4"><?php echo $get_present_spectacal_presc[0]['IpdLE']; ?></td>
																			</tr>
																			</tbody>
																			
																		</table>
											<br>
									</td>
									<!-- DISPLAY DIAGNOSIS -->
									<td><?php 
									$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
									if(!empty($get_diagnosis)){
										while(list($key_diagno, $value_diagno) = each($get_diagnosis)){
										echo $value_diagno['icd_code'].", <br>"; 
										} //endif
									} //end while ?><br><br></td>
									
									<!-- DISPLAY INVESTIGATION -->
									
									<td>
									<?php $get_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_tab[0]['patient_id'] ."' and episode_id='". $patient_episode_val['episode_id']."'","","","","");
									if(!empty($get_invest))
									{
									?>
										<form method="post" name="frmAddTest" action="my_patient_profile_save.php" >
										<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
										<table class="table table-bordered">
											<thead>
											<tr>
											<th>Test</th>
											<th>Normal Value</th>
											<th>Actual Value</th>
											</tr>
											<thead>
											<tbody>
											
													
												<?php 
												
												while(list($key_invest, $value_invest) = each($get_invest))	
												{  
												
											
												?>
												<tr>
												<td>
												<input type="hidden" name="main_test_id[]" value="<?php echo $value_invest['main_test_id']; ?>"/>
												<input type="hidden" name="investigation_id[]" value="<?php echo $value_invest['pti_id']; ?>"/><?php echo $value_invest['test_name']; ?></td>
												<td><?php echo $value_invest['normal_range']; ?></td>
												<td><input type="text" name="actualVal[]"  value="<?php echo $value_invest['test_actual_value']; ?>" placeholder="" style="width:100px;"></td>
												</tr>
												
												<?php }
												?>
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
                                            <img src="../../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
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
															<?php $getDiagnostic= mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.doc_id='".$admin_id."'","b.doc_diagno_id desc","","","");
																	
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
													<br><br><br>
									<?php } 
									else
									{
										echo "NA";
									}?>
									</td> 
									
									<!-- DISPLAY TREATMENT -->
									<td><?php 
									$get_treatment = mysqlSelect("b.treatment as treatment","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
									if(!empty($get_examination)){
									while(list($key_treat, $value_treat) = each($get_treatment)){
									echo $value_treat['treatment'].", "; 
									} //end while
									} //endif 
									else
									{
										echo "NA";
										
									}?><br><br></td>
									<td>
									<p>
										  <span><i class="fa fa-paperclip"></i> <?php $doc_patient_episode_attachment = mysqlSelect("attach_id,my_patient_id,episode_id,attachments","doc_patient_attachments","episode_id = '". $patient_episode_val['episode_id'] ."' ","","","","");
										 echo COUNT($doc_patient_episode_attachment); ?> attachments </span>
										  <!--<a href="#">Download all attachments</a> |
										  <a href="#">View all images</a>-->
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
											$imgIcon="../../assets/images/doc.png";
									}
									else if($extractPath=="pdf" || $extractPath=="PDF"){
										$imgIcon="../../assets/images/pdf.png";
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
									
									<!-- DISPLAY SPECTACLE PRESCRIPTIONS -->
									<?php
									$get_exam_spectacle = mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id = '".$patient_episode_val['episode_id']."' and doc_id = '".$admin_id."' and doc_type = '1'","","","","");
									
									?>
									<td>
									<form method="post" name="frmAddTest" action="my_patient_profile_save.php" >
									<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>" />
									<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				
																				<th colspan="5" class="text-center">Right Eye</th>
																				<th colspan="4" class="text-center">Left Eye</th>
																				
																			</thead>
																			<thead>
																				<th></th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																				<th>Vision</th>
																				<th>Sphere</th>
																				<th>Cyl</th>
																				<th>Axis</th>
																				<th>Vision</th>
																			</thead>
																			<tbody>
																			<tr><td>D.V</td>
																			<td><?php echo $get_exam_spectacle[0]['dvSphereRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvCylRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvAxisRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvVisionRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvSpeherLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvCylLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvAxisLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['DvVisionLE']; ?></td>
																			</tr>
																			
																			<tr><td>N.V</td>
																			<td><?php echo $get_exam_spectacle[0]['NvSpeherRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvCylRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvAxisRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvVisionRE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvSpeherLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvCylLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvAxisLE']; ?></td>
																			<td><?php echo $get_exam_spectacle[0]['NvVisionLE']; ?></td>
																			</tr>
																			
																			<tr><td>IPD</td>
																			<td colspan="4"><?php echo $get_exam_spectacle[0]['IpdRE']; ?></td>
																			<td colspan="4"><?php echo $get_exam_spectacle[0]['IpdLE']; ?></td>
																			</tr>
																			</tbody>
																			
																		</table>
											</form>
											
											<br>
										<!--<label>Refer to Spectacle Center</label>-->
										<span id="success"></span>
										<div class="form-group col-md-1">
										<a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new opticals" data-toggle="modal" data-target="#myModalOptical"><i class="fa fa-plus"></i>
										</a>
										
										<div class="modal inmodal" id="myModalOptical" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title">Add New Opticals</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddOpticals">
										<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
                                   
                                        <div class="modal-body">
                                            <div class="form-group"><label>Opticals Name</label> <input type="text" name="optical_name" required value="" class="form-control"></div>
											<div class="form-group"><label>Email</label> <input type="email" name="txtemail_opt" value="" required class="form-control"></div>
											<div class="form-group"><label>Mobile</label> <input type="text" name="mobile_opt" value="" required class="form-control"></div>
											<div class="form-group"><label>City</label> <input type="text" name="city_opt" value="" required class="form-control"></div>
										
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_opticals_patient" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>
								
								</div>
										<div class="form-group col-md-9">
										
														<select data-placeholder="Refer to Opticles" class="chosen-select diagnoCenter" name="selectOpticleCenter" id="selectOpticleCenter" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" data-episode-id="<?php echo $patient_episode_val['episode_id']; ?>" tabindex="3">
															<option value=""></option>
															<?php $getOpticles= mysqlSelect("*","Opticle_center as a left join doc_opticles as b on a.opticale_id=b.opticale_id","b.doc_id='".$admin_id."'","b.doc_opticle_id desc","","","");
																	
																	foreach($getOpticles as $getOpticlesList){ ?>
												
																		<option value="<?php echo stripslashes($getOpticlesList['opticale_id']);?>" /><?php echo stripslashes($getOpticlesList['opticle_name']).", ".stripslashes($getOpticlesList['opticle_city']);?></option>
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
										<br><br><br>
									
								
									</td>
											
									<td>
											<?php
											$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");
											
											if (COUNT($doc_patient_episode_prescriptions) > 0)
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
															<img src="../../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
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
												<select data-placeholder="Refer to Pharmacy.." class="chosen-select" name="selectDignosticCenter" id="selectPharma" data-patient-id="<?php echo $patient_tab[0]['patient_id']; ?>" data-episode-id="<?php echo $patient_episode_val['episode_id']; ?>" tabindex="3" onchange="">
												<option value=""></option>
												<?php $getPharma= mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.doc_id='".$admin_id."'","b.doc_pharma_id desc","","","");
														
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
										</div><br><br><br><br>
										<?php } ?>
												
									</td>
									<td ><?php if(!empty($patient_episode_val['next_followup_date'])){ echo "<font style=color:red;font-weight:bold;>".date('d-M-Y',strtotime($patient_episode_val['next_followup_date']))."</font>"; } ?>
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
       <?php include_once('../footer.php'); ?>

        </div>
		
		<!--<div class="small-chat-box fadeInRight animated">

            <div class="heading" draggable="true">
                <small class="chat-date pull-right">
                    <i class="fa fa-calendar"></i> 29.05.2018
                </small>
               Waiting Room
            </div>
			<div class="content">
			<?php $allRecord = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","5");
			while(list($key, $value) = each($allRecord)) { 
			?>
			<a href="My-Patient-Details?p=<?php echo md5($value['patient_id']); ?>">
			  <div class="left">
				
                    <div class="author-name">
                    <small>
                        <span class="label label-warning">WAITING</span>
                    </small>
                    </div>
                    <div class="chat-message active">
					<small>App. Time: 11.20am</small><br>
                      <b> <?php echo $value['patient_name']; ?></b>
                    </div>

                </div>
			</a>
			<?php } ?>
			</div>
            

            
        </div>
        <div id="small-chat">

            <span class="badge badge-warning pull-right">5</span>
            <a class="open-small-chat">
                <i class="fa fa-calendar"></i>

            </a>
        </div>-->
		
        </div>
	
	
	<!-- Sweet alert -->
	<script src="../../assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script src="../../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../../assets/js/inspinia.js"></script>
    <script src="../../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	 <!-- Toastr -->
    <script src="../../assets/js/plugins/toastr/toastr.min.js"></script>
	
    <!-- Tags Input -->
    <script src="../../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

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
		
		$(document).ready(function(){
	
		$("#view-fundus-image").hide();
		$('#fundus_message').css('display','none');
		
		$("#fundusImage").click(function(){
		
		$("#visit-details").hide();
		$("#view-trend-analysis").hide();
		$("#view-latest-reports").hide();
		$("#medical-history").hide();
		$("#add-visit-dtails").hide();
		$("#edit_visist_details").hide();
		$("#custom-view-trend-analysis").css("display","none");
		$("#view-fundus-image").show();
		$('#attachFundusImage').css('display','block');
		$("#fundusImageSection").hide();
		$('#circles').css('display','none');
		$('#fileUpload').val('');
		$('#fundus_message').css('display','none');
	});
	
            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });
			
           
        });


    </script>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../../assets/js/custom.min.js"></script>
	<!-- iCheck -->
    <script src="../../assets/js/plugins/iCheck/icheck.min.js"></script>
	
    
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

					$('#hid_prescription_seq').val(prescription_seq);

					$( "#employee-grid" ).append( new_prescription_tr );
					

					var tradeName = [ <?php echo '"' . implode ('","', $arrTradeName) . '"'; ?> ];

					$( ".tagName" ).autocomplete({
					  source: tradeName
					});

					var genericName = [ <?php echo '"' . implode ('","', $arrGenericName) . '"'; ?> ];
					$( ".genericName" ).autocomplete({
					  source: genericName
					});
					
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
					var templateName = [];
					
					$("#sympBefore span").each(function() {
					    templateName.push($(this).contents().filter(function() {
						return this.nodeType == 3;
					    })[0].nodeValue.trim());
					});
					$("#template_name").val(templateName.join("-"));
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
	
<!-- Chosen -->
    <script src="../../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	
	<!-- Switchery -->
   <script src="../../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
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
				minDate:new Date()
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
    <script src="../../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	
    <script>
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
											
	?>
            $('.typeahead_1').typeahead({
               source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });
		

		$('#frmAddFundusImage').on('submit', function(event){
			event.preventDefault();
			
			var upload_user = $("#upload_user_fundus").val();
		var patient_id = $("#patient_id_fundus").val();
		var report_title = $("#report_title_fundus").val();
		var canvas = document.getElementById('cirCanvas');
		var fundus_image=canvas.toDataURL();
		
		var dataValue='addFundusImageAttach=true&upload_user='+upload_user+'&patient_id='+patient_id+'&report_title='+report_title+'&fundus_image='+fundus_image;
		
			$.ajax({
			type: "POST",
			url: "../my_patient_profile_save.php",
			data:dataValue,
			dataType:'json',
			success: function(data){
				$('#fundus_message').css('display','block');
			    $('#frmAddFundusImage')[0].reset();
				$('#circles').css('display','none');
				$('#fileUpload').val('');
				 var canvas = document.getElementById("cirCanvas");
				var contxt = canvas.getContext("2d");
				contxt.clearRect(0, 0, canvas.width, canvas.height);
				location.reload();
			}
			});
		});

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
	
	$get_TrendAnalysisDate1 = mysqlSelect("date_added","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisPPCount1 = mysqlSelect("bp_beforefood_count","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisPPAfterCount1 = mysqlSelect("bp_afterfood_count","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisSystolic1 = mysqlSelect("systolic","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisDiastolic1 = mysqlSelect("diastolic","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisHbA1c1 = mysqlSelect("HbA1c","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisHDL1 = mysqlSelect("HDL","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisVLDL1 = mysqlSelect("VLDL","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisLDL1 = mysqlSelect("LDL","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisTriglyceride1 = mysqlSelect("triglyceride","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_TrendAnalysisCholesterol1 = mysqlSelect("cholesterol","trend_analysis","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	
	$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","","language_id desc","","","");
	
	$get_OphthalTrendAnalysisDate = mysqlSelect("date_added","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvSphereRE = mysqlSelect("DvSphereRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvCylRE = mysqlSelect("DvCylRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvAxisRE = mysqlSelect("DvAxisRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvSpeherLE = mysqlSelect("DvSpeherLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvCylLE = mysqlSelect("DvCylLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_DvAxisLE = mysqlSelect("DvAxisLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvSpeherRE = mysqlSelect("NvSpeherRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvCylRE = mysqlSelect("NvCylRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvAxisRE = mysqlSelect("NvAxisRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvSpeherLE = mysqlSelect("NvSpeherLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvCylLE = mysqlSelect("NvCylLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_NvAxisLE = mysqlSelect("NvAxisLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_IpdRE = mysqlSelect("IpdRE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	$get_IpdLE = mysqlSelect("IpdLE","trend_analysis_ophthal","patient_id='".$patient_id."'","trend_id asc","","","0,5");
	
	?>
	
	<script>

	var config = {
		type: 'line',
		data: {
			labels: [<?php while(list($key, $value) = each($get_OphthalTrendAnalysisDate)){ echo $dateAdded= "'".date('d-M-Y',strtotime($value['date_added']))."',"; } ?>],
			datasets: [{
				label: 'DvSphereRE ',
				backgroundColor: window.chartColors.red,
				borderColor: window.chartColors.red,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvSphereRE)){ echo $value['DvSphereRE'].","; } ?>],
				//data: [120,124,122,126,],
			}, {
				label: 'DvCylRE',
				backgroundColor: window.chartColors.blue,
				borderColor: window.chartColors.blue,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvCylRE)){ echo $value['DvCylRE'].","; } ?>],
				//data: [120,124,122,126,],
			}, {
				label: 'DvAxisRE',
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvAxisRE)){ echo $value['DvAxisRE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'DvSpeherLE',
				backgroundColor: window.chartColors.mediumaquamarine,
				borderColor: window.chartColors.mediumaquamarine,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvSpeherLE)){ echo $value['DvSpeherLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'DvCylLE',
				backgroundColor: window.chartColors.purple,
				borderColor: window.chartColors.purple,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvCylLE)){ echo $value['DvCylLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'DvAxisLE',
				backgroundColor: window.chartColors.thistle,
				borderColor: window.chartColors.thistle,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_DvAxisLE)){ echo $value['DvAxisLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvSpeherRE',
				backgroundColor: window.chartColors.sienna,
				borderColor: window.chartColors.sienna,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvSpeherRE)){ echo $value['NvSpeherRE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvCylRE',
				backgroundColor: window.chartColors.teal,
				borderColor: window.chartColors.teal,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvCylRE)){ echo $value['NvCylRE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvAxisRE',
				backgroundColor: window.chartColors.yellow,
				borderColor: window.chartColors.yellow,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvAxisRE)){ echo $value['NvAxisRE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvSpeherLE',
				backgroundColor: window.chartColors.green,
				borderColor: window.chartColors.green,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvSpeherLE)){ echo $value['NvSpeherLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvCylLE',
				backgroundColor: window.chartColors.thistle,
				borderColor: window.chartColors.thistle,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvCylLE)){ echo $value['NvCylLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'NvAxisLE',
				backgroundColor: window.chartColors.mediumaquamarine,
				borderColor: window.chartColors.mediumaquamarine,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_NvAxisLE)){ echo $value['NvAxisLE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'IpdRE',
				backgroundColor: window.chartColors.sienna,
				borderColor: window.chartColors.sienna,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_IpdRE)){ echo $value['IpdRE'].","; } ?>],
				
				//data: [120,124,122,126,],
			}, {
				label: 'IpdLE',
				backgroundColor: window.chartColors.yellow,
				borderColor: window.chartColors.yellow,
				fill: false,
				data: [<?php while(list($key, $value) = each($get_IpdLE)){ echo $value['IpdLE'].","; } ?>],
				
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

	window.onload = function() {
		var ctx = document.getElementById('canvas').getContext('2d');
		window.myLine = new Chart(ctx, config);
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
	<script>
	  var canvas = document.getElementById("cirCanvas");
      var contxt = canvas.getContext("2d");
		var fileUpload = document.getElementById('fileUpload');

		fileUpload.onchange = function fileUploadChanged() {
			loadFile(contxt, this.files);
		}

		canvas.onclick = function canvasClicked(event) {
			drawPoint(contxt, event.offsetX, event.offsetY);
		};
      /*img = new Image();
	  img.onload = function(){
		contxt.drawImage(img, 0, 0, 250, 250);
		//$("span").text("Loaded.");
	  };
		//img.src = "http://photojournal.jpl.nasa.gov/jpeg/PIA17555.jpg";
		img.src="https://pixeleyecare.com/assets/img/FDC Pixel.png";

      canvas.addEventListener('click', draw, false);*/
	  
	  function drawImage(canvasCtx, imageSrc) {
    var img = new Image();
    img.onload = function() {
        canvasCtx.drawImage(img, 0, 0, 512, 512);
    };
    img.src = imageSrc;
}

function drawPoint(canvasCtx, x, y) {
    canvasCtx.beginPath();
    canvasCtx.fillStyle = 'black';
    canvasCtx.arc(x, y, 3, 0, 2 * Math.PI);
    canvasCtx.fill();
}

function loadFile(canvasCtx, files) {
    if (files && files[0]) {
        var reader = new FileReader();
        reader.onload = function() {
            drawImage(canvasCtx, reader.result);
        };
        reader.readAsDataURL(files[0]);
		$('#circles').css('display','block');
    }
}

	function draw(e) {
	  var pos = getMousePos(canvas, e);
	  posx = pos.x;
	  posy = pos.y;
	  contxt.fillStyle = randomColor();
	  contxt.beginPath();
	  //context.arc(posx, posy, 50, 0, 2 * Math.PI);
	  contxt.arc(posx, posy, 3, 0, 2 * Math.PI);
	  contxt.fill();
	}

	function randomColor() {
	  //var color = [];
	 // for (var i = 0; i < 3; i++) {
	 //   color.push(Math.floor(Math.random() * 256));
	 // }
	 // return 'rgb(' + color.join(',') + ')';
	 return 'rgb(0,0,0)';
	}

	function getMousePos(canvas, evt) {
	  var rect = canvas.getBoundingClientRect();
	  return {
		x: evt.clientX - rect.left,
		y: evt.clientY - rect.top
	  };
	}
	
	 function getPatientDet(srchText){
	     var params     = srchText.split("-");
	if(!isNaN(params[0])){
		document.frmchangePatient.cmdchangePatient.value="submit";
		document.frmchangePatient.slct_valPat.value=srchText;
		document.frmchangePatient.submit();
	}		
}
	</script>	 
	