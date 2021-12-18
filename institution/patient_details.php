<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$Patient_id=$_GET['p'];
include('../send_text_message.php');
include('../send_mail_function.php');
require_once("../classes/querymaker.class.php");

if(empty($admin_id)){
	header("Location:index.php");
}
date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	
//$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");
$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,a.patient_email as patient_email,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['p']."'","","","","");



$getChatStatusDiagno= mysqlSelect("*,MAX(status) as status1","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='1')","chat_id desc","refer_id","","");

$getChatStatusPharma= mysqlSelect("*,MAX(status) as status1","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='2')","chat_id desc","refer_id","","");

$getChatStatusInst= mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."'","chat_id desc","","","");

	//print_r($patient_tab);
	if(COUNT($patient_tab)==0){
	    echo "<h2>Error!!!!!!!</h2>";
	}
	else{
			
			$patient_id = $patient_tab[0]['patient_id']; //Patient ID			
			$patient_name = $patient_tab[0]['patient_name']; //Patient Name			
			$patient_age = $patient_tab[0]['patient_age']; //Patient Age	
			$patient_mob = $patient_tab[0]['patient_mob']; //Patient Mobile No.				
			$patient_loc = $patient_tab[0]['patient_loc']; //Patient City	
			$patient_state = $patient_tab[0]['pat_state']; //Patient State
			$patient_country = $patient_tab[0]['pat_country']; //Patient Country
			$patient_address = $patient_tab[0]['patient_addrs']; //Patient Country			
			$patient_email = $patient_tab[0]['patient_email'];		
			
			$patient_height = $patient_tab[0]['height'];
			$patient_weight = $patient_tab[0]['weight'];
			
							//BMI Calculation
							$explode = explode(".", $patient_tab[0]['height']);  
							$wholeFeet = $explode[0];							
							$fraction = $explode[1];
							$frctionFeet=$fraction*0.0833333; // Convert inches to feet
							$actaulFeet = $wholeFeet+$frctionFeet;
							$heightinMeter=$actaulFeet*0.3048; //Convert feet to meter
							//echo $wholeFeet.", ".$fraction.", ".$actaulFeet."<br>";
							$patient_BMI = substr(($patient_tab[0]['weight']/($heightinMeter*$heightinMeter)),0,4);
		
			if($patient_BMI>=18.5 && $patient_BMI<=24.9){ $bmiStatus="Healthy"; } else if($patient_BMI>=25 && $patient_BMI<=30){ $bmiStatus="Overweight"; } else if($patient_BMI>=30){ $bmiStatus="Obese"; }
			
			if($patient_tab[0]['patient_gen']=="1"){
				$patient_gender="Male";
			}
			else if($patient_tab[0]['patient_gen']=="2"){   //Patient Gender
				$patient_gender="Female";
			}
			else if($patient_tab[0]['patient_gen']=="3"){
				$patient_gender="Other";
			}			

			if($patient_tab[0]['hyper_cond']=="2"){
				$hyperStatus="No";								//Patient Hyper Status
			}
			else if($patient_tab[0]['hyper_cond']=="1"){
				$hyperStatus="Yes";
			}
			
			
			if($patient_tab[0]['diabetes_cond']=="2"){
				$diabetesStatus="No";							//Patient Diabetes Status
			}
			else if($patient_tab[0]['diabetes_cond']=="1"){
				$diabetesStatus="Yes";
			}
		
		//Episode Details
			$patient_id = $patient_tab[0]['patient_id'];

			$patient_episodes = mysqlSelect("*","doc_patient_episodes","patient_id = '". $patient_id ."' and md5(episode_id)='".$_GET['e']."'","","","","");
			
			$episode_created_date=date('d M Y, H:i a',strtotime($patient_episodes[0]['date_time']));   //Prescription Date
			
			
			$get_medical_complaint = mysqlSelect("b.symptoms as symptoms","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				
			$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
			
			$get_examination = mysqlSelect("b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				
			$get_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_id ."' and episode_id='". $patient_episodes[0]['episode_id']."'","","","","");
			
			$get_treatment_advise = mysqlSelect("b.treatment as treatment","doc_patient_treatment_active as a left join doctor_frequent_treatment as b on a.dft_id=b.dft_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
			
		//Doctors Details	
			$get_doc_details = mysqlSelect("ref_name,doc_state","referal","ref_id='".$patient_episodes[0]['admin_id']."'","","","","");
			$_SESSION['doc_state'] = $get_doc_details[0]['doc_state'];
			$doctor_name = $get_doc_details[0]['ref_name'];  //Doctor Name
			$_SESSION['doc_name'] = $get_doc_details[0]['ref_name'];
		//Doctors Specialization
			$get_doc_spec = mysqlSelect("a.spec_name as specialization, a.spec_group_id as spec_group_id","specialization as a inner join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","a.spec_id ASC","","","");
			
		//Get Host Name
			if($get_doc_spec[0]['spec_group_id']==1)
			{
				$pageName = "My-Patient-Details";
			}else if($get_doc_spec[0]['spec_group_id']==2)
			{
				$pageName = "Ophthal-EMR/";				
			}
			
		//Doctors Clinic Details
			$get_doc_clinic = mysqlSelect("a.hosp_name as Hospital,a.hosp_addrs as Hosp_address,a.hosp_city as hosp_city,a.hosp_state as hosp_state,a.hosp_country as hosp_country, a.hosp_contact as hosp_contact, a.hosp_email as hosp_email","hosp_tab as a inner join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","","","","");
			
			$Clinic_name = $get_doc_clinic[0]['Hospital'];  //Clinic Name
			$Clinic_address = $get_doc_clinic[0]['Hosp_address'];  //Clinic Address
			$Clinic_City = $get_doc_clinic[0]['hosp_city'];  //Clinic Address
			$Clinic_State= $get_doc_clinic[0]['hosp_state'];  //Clinic State
			$Clinic_Country = $get_doc_clinic[0]['hosp_country'];  //Clinic Country
			$clinic_contact = $get_doc_clinic[0]['hosp_contact'];
			$clinic_email = $get_doc_clinic[0]['hosp_email'];
			
		//Prescription Details
		$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episodes[0]['episode_id'] ."' ","episode_prescription_id asc","","","");

		//$timing_array = Array("रत्रीच्या जेवणानंतर", "दोपारी जेवणानंतर", "नश्ते से पहले", "रात के खाने के बाद", "ಡಿನ್ನರ್ ನಂತರ", "ಮಧ್ಯಾನ್ನದ ಊಟದ ನಂತರ");

		if($get_doc_spec[0]['spec_group_id'] == 2) {
		//Spectacle Prescription Details
		$doc_patient_spectacle_prescriptions = mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id = '". $patient_episodes[0]['episode_id'] ."' ","spectacle_id ASC","","","");

		$get_exam_lids = mysqlSelect("b.lids_name as lids_name,a.lids as lids","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_type='1'","","","","");
		$get_exam_lidsLE = mysqlSelect("b.lids_name as lids_name,a.lids as lids","doc_patient_lids_active as a left join examination_ophthal_lids as b on a.lids=b.lids_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_type='2'","","","","");
									
		$get_exam_conjuctivaRE = mysqlSelect("b.conjuctiva_name as conjuctiva_name,a.conjuctiva as conjuctiva","doc_patient_conjuctiva_active as a left join examination_ophthal_conjuctiva as b on a.conjuctiva=b.conjuctiva_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_conjuctivaLE = mysqlSelect("b.conjuctiva_name as conjuctiva_name,a.conjuctiva as conjuctiva","doc_patient_conjuctiva_active as a left join examination_ophthal_conjuctiva as b on a.conjuctiva=b.conjuctiva_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_scleraRE = mysqlSelect("b.scelra_name as scelra_name,a.sclera as sclera","doc_patient_sclera_active as a left join examination_ophthal_sclera as b on a.sclera=b.sclera_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_scleraLE = mysqlSelect("b.scelra_name as scelra_name,a.sclera as sclera","doc_patient_sclera_active as a left join examination_ophthal_sclera as b on a.sclera=b.sclera_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_cornea_anteriorRE = mysqlSelect("b.cornea_ant_name as cornea_ant_name,a.cornea_ant as cornea_ant","doc_patient_cornea_ant_active as a left join examination_ophthal_cornea_anterior as b on a.cornea_ant=b.cornea_ant_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_cornea_anteriorLE = mysqlSelect("b.cornea_ant_name as cornea_ant_name,a.cornea_ant as cornea_ant","doc_patient_cornea_ant_active as a left join examination_ophthal_cornea_anterior as b on a.cornea_ant=b.cornea_ant_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_cornea_posteriorRE = mysqlSelect("b.cornea_post_name as cornea_post_name,a.cornea_post as cornea_post","doc_patient_cornea_post_active as a left join examination_ophthal_cornea_posterior as b on a.cornea_post=b.cornea_post_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_cornea_posteriorLE = mysqlSelect("b.cornea_post_name as cornea_post_name,a.cornea_post as cornea_post","doc_patient_cornea_post_active as a left join examination_ophthal_cornea_posterior as b on a.cornea_post=b.cornea_post_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_anterior_chamberRE = mysqlSelect("b.chamber_name as chamber_name,a.chamber as chamber","doc_patient_anterior_chamber_active as a left join examination_ophthal_chamber as b on a.chamber=b.chamber_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_anterior_chamberLE = mysqlSelect("b.chamber_name as chamber_name,a.chamber as chamber","doc_patient_anterior_chamber_active as a left join examination_ophthal_chamber as b on a.chamber=b.chamber_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_anterior_irisRE = mysqlSelect("b.iris_name as iris_name,a.iris as iris","doc_patient_iris_active as a left join examination_ophthal_iris as b on a.iris=b.iris_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_anterior_irisLE = mysqlSelect("b.iris_name as iris_name,a.iris as iris","doc_patient_iris_active as a left join examination_ophthal_iris as b on a.iris=b.iris_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_pupil_RE = mysqlSelect("b.pupil_name as pupil_name,a.pupil as pupil","doc_patient_pupil_active as a left join examination_ophthal_pupil as b on a.pupil=b.pupil_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_pupil_LE = mysqlSelect("b.pupil_name as pupil_name,a.pupil as pupil","doc_patient_pupil_active as a left join examination_ophthal_pupil as b on a.pupil=b.pupil_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_angle_RE = mysqlSelect("b.angle_name as angle_name,a.angle as angle","doc_patient_angle_active as a left join examination_ophthal_angle as b on a.angle=b.angle_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_angle_LE = mysqlSelect("b.angle_name as angle_name,a.angle as angle","doc_patient_angle_active as a left join examination_ophthal_angle as b on a.angle=b.angle_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_lens_RE = mysqlSelect("b.lens_name as lens_name,a.lens as lens","doc_patient_lens_active as a left join examination_ophthal_lens as b on a.lens=b.lens_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_lens_LE = mysqlSelect("b.lens_name as lens_name,a.lens as lens","doc_patient_lens_active as a left join examination_ophthal_lens as b on a.lens=b.lens_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_viterous_RE = mysqlSelect("b.viterous_name as viterous_name,a.viterous as viterous","doc_patient_viterous_active as a left join examination_ophthal_viterous as b on a.viterous=b.viterous_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_viterous_LE = mysqlSelect("b.viterous_name as viterous_name,a.viterous as viterous","doc_patient_viterous_active as a left join examination_ophthal_viterous as b on a.viterous=b.viterous_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_fundus_RE = mysqlSelect("b.fundus_name as fundus_name,a.fundus as fundus","doc_patient_fundus_active as a left join examination_ophthal_fundus as b on a.fundus=b.fundus_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='1'","","","","");
		$get_exam_fundus_LE = mysqlSelect("b.fundus_name as fundus_name,a.fundus as fundus","doc_patient_fundus_active as a left join examination_ophthal_fundus as b on a.fundus=b.fundus_id","a.episode_id='".$patient_episodes[0]['episode_id']."' and a.eye_side='2'","","","","");
									
		$get_exam_refraction = mysqlSelect("*","examination_opthal_spectacle_prescription","episode_id = '".$patient_episodes[0]['episode_id']."' and doc_id = '".$patient_episodes[0]['admin_id']."' and doc_type = '1'","","","","");
		}			
$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$patient_episodes[0]['admin_id']."' and doc_type='1'","","","","");

$getReferredDetails= mysqlSelect("*","diagnostic_referrals","patient_id='".$patient_id."'","referred_date desc","","","");

$getReferredDetails1= mysqlSelect("*","pharma_referrals","patient_id='".$patient_id."'","referred_date desc","","","");
	}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient Detail</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	 <script src="../assets/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="../premium/date-time-picker.min.js"></script>
	
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-10">
                    <h2>Patient Detail</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>Patient Detail</strong>
                        </li>
                    </ol>
                </div>
				<div class="col-lg-2 mgTop">
					<a href="Referred-EMR"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
		<div class="wrapper wrapper-content animated fadeInUp">	
		<?php if($_GET['response']=="Appointment-Success"){ ?>
			<div class="alert alert-success alert-dismissable">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<strong>Appointment link has been sent successfully </strong>
			</div>
		<?php } ?>
        <div class="row">
            <div class="col-lg-7">
                  <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
						
					 
                                    <div class="m-b-md">
                                        <!--<a href="#" class="btn btn-white btn-xs pull-right">Edit Patient</a>-->
                                        <h2><?php echo $patient_tab[0]['patient_name']; ?>( #<?php echo $patient_tab[0]['patient_id']; ?> ) </h2>
                                   
								   </div>
									<dl class="dl-horizontal">
									<?php if($getChatStatusInst[0]['status']=="1"){ ?> <dt>Status: </dt> <dd>  <span class='label label-warning'>PENDING</span></dd><br/><?php } ?>
									<?php foreach($getChatStatusDiagno as $getChatlist){
										$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$getChatlist['refer_id']."'");
										if($getChatlist['status1']=="1"){ $patient_status="<span class='label label-warning' style='margin-left: 10px;'>PENDING</span>"; ?>
										<?php } else if($getChatlist['status1']=="2"){  $patient_status="<span class='label label-info' style='margin-left: 10px;'>Sent To Diagnosis</span>";?>
										<?php } else if($getChatlist['status1']=="3"){  $patient_status="<span class='label label-info' style='margin-left: 10px;'>Sent to Pharmacy</span>";?>
										<?php } else if($getChatlist['status1']=="4"){  $patient_status="<span class='label label-info' style='margin-left: 10px;'>PAYMENT LINK SENT</span>";?>
										<?php } else if($getChatlist['status1']=="5"){  $patient_status="<span class='label label-success' style='margin-left: 10px;'>PAYMENT SUCCESS</span>";?>
										<?php } else if($getChatlist['status1']=="6"){  $patient_status="<span class='label label-warning' style='margin-left: 10px;'>ORDERED</span>";?>
										<?php } else if($getChatlist['status1']=="7"){  $patient_status="<span class='label label-success' style='margin-left: 10px;'>COMPLETED</span>";?>
										<?php } 
										
									  if($getChatlist['type']=="1"){ ?> <dt style="width:300px;">Status (Diagnostic) - <?php echo $getDiagno[0]['diagnosis_name']; ?> : </dt> <dd >  <?php echo $patient_status; ?></dd><br/><?php } ?>
									   <?php if(($getChatlist['status1']=="7") && $getChatlist['type']=="1"){ 
									   $getChatComment= mysqlSelect("*","emr_referred_notifications","patient_id='".$getChatlist['patient_id']."' and episode_id='".$getChatlist['episode_id']."' and (type='1') and status='7' and refer_id='".$getChatlist['refer_id']."'","chat_id desc","refer_id","","");
							 ?> <dt style="width:300px;">Comments (Diagnostic) - <?php echo $getDiagno[0]['diagnosis_name']; ?> : </dt> <dd style="padding-left: 130px;">  <?php echo $getChatComment[0]['note']; ?></dd><?php } 
										
									} ?>
										
										<?php foreach($getChatStatusPharma as $getChatPharmalist){ 
										 $getDiagno= mysqlSelect("*","pharma","pharma_id='".$getChatPharmalist['refer_id']."'");
										 
										if($getChatPharmalist['status1']=="3"){  $patient_status1="<span class='label label-info' style='margin-left: 10px;'>Sent to Pharmacy</span>";?>
										<?php } else if($getChatPharmalist['status1']=="4"){  $patient_status1="<span class='label label-info' style='margin-left: 10px;'>PAYMENT LINK SENT</span>";?>
										<?php } else if($getChatPharmalist['status1']=="5"){  $patient_status1="<span class='label label-success' style='margin-left: 10px;'>PAYMENT SUCCESS</span>";?>
										<?php } else if($getChatPharmalist['status1']=="6"){  $patient_status1="<span class='label label-warning' style='margin-left: 10px;'>ORDERED</span>";?>
										<?php } else if($getChatPharmalist['status1']=="7"){  $patient_status1="<span class='label label-success' style='margin-left: 10px;'>COMPLETED</span>";?>
										<?php } ?>
						
                                      <?php if($getChatPharmalist['type']=="2"){ ?> <dt  style="width:300px;">Status (Pharmacy) - <?php echo $getDiagno[0]['pharma_name']; ?> :  </dt> <dd>  <?php echo $patient_status1; ?></dd><br/><?php } 
									 if(($getChatPharmalist['status1']=="7") && $getChatPharmalist['type']=="2"){ 
									 $getChatPharmaComment= mysqlSelect("*","emr_referred_notifications","patient_id='".$getChatPharmalist['patient_id']."' and episode_id='".$getChatPharmalist['episode_id']."' and (type='2') and status='7' and refer_id='".$getChatPharmalist['refer_id']."'","chat_id desc","refer_id","","");
								?> <dt style="width:300px;">Comments (Pharmacy) - <?php echo $getDiagno[0]['pharma_name']; ?> : </dt> <dd style="padding-left: 130px;">  <?php echo $getChatPharmaComment[0]['note']; ?></dd><?php } 
										
										} ?>
										
										
								 </dl>
                                </div>
                            </div>
							<?php foreach($getChatStatusDiagno as $getChatlist){
								$getChatStatusDia= mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and refer_id='".$getChatlist['refer_id']."' and status='4'","","","","");
							if($getChatlist['status1']=="5" && $getChatlist['type']=="1"){ ?>
							<div class="row" style="background: #eeeeee;padding: 10px;">
                                <form enctype="multipart/form-data" method="post" action="add_details.php"  autocomplete="off">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								<input type="hidden" name="episode_id" value="<?php echo $patient_episodes[0]['episode_id']; ?>">
								<input type="hidden" name="diagno_url" value="<?php echo $getChatStatusDia[0]['url']; ?>">
								<input type="hidden" name="diagno_id" value="<?php echo $getChatlist['refer_id']; ?>">
								<div class="">
								<div class="form-group">
									<label class="col-sm-3 control-label">Scheduled Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><input id="J-demo-02" name="dateadded2" type="text" placeholder="YYYY-MM-DD" value="<?php echo $Cur_Date;?>" class="form-control" required="" />
									<script type="text/javascript">
										$('#J-demo-02').dateTimePicker({
											mode: 'dateTime'
										});
									</script>
									</div>
                                   <div class="col-sm-4">  <button class="btn btn-sm btn-primary" name="orderDiagnoTest" type="submit">
                                                Order Now
                                            </button> </div>
									 </div>	
								</div>
								
								</form>
                            </div>
							<?php } 
							} ?>
							<?php foreach($getChatStatusDiagno as $getChatlist){
								$getChatStatusDia= mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and refer_id='".$getChatlist['refer_id']."' and status='4'","","","","");
							
							if($getChatlist['status1']=="5" && $getChatlist['type']=="2"){ ?>
							<div class="row" style="background: #eeeeee;padding: 10px;">
                                <form enctype="multipart/form-data" method="post" action="add_details.php"  autocomplete="off">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								<input type="hidden" name="episode_id" value="<?php echo $patient_episodes[0]['episode_id']; ?>">
								<input type="hidden" name="diagno_url" value="<?php echo $getChatStatusDia[0]['url']; ?>">
								<input type="hidden" name="pharma_id" value="<?php echo $getChatlist['refer_id']; ?>">
								<div class="">
								<div class="form-group">
									<label class="col-sm-3 control-label">Delivery Address <span class="required">*</span></label>

                                    <div class="col-sm-4"> <textarea class="form-control" name="delAddress" id="delAddress"  placeholder="Enter the address here"></textarea>
									
									</div>
                                   <div class="col-sm-4">  <button class="btn btn-sm btn-primary" name="orderPharmaPrescription" type="submit">
                                                Order Now
                                            </button> </div>
									 </div>	
								</div>
								
								</form>
                            </div>
							<?php } 
							} ?>
							  <div class="row">
                                <div class="col-lg-7">
								<h4><strong>Doctor Details</strong></h4>
                                    <dl class="dl-horizontal">

                                        <dt>Name:</dt> <dd>Dr. <?php echo $doctor_name; ?></dd>
                                        <dt>Specialization:</dt> <dd>    <?php
										while(list($key_spec, $value_spec) = each($get_doc_spec)){
											echo $value_spec['specialization'].", ";   //Doctor Specialization				
										}
										?></dd>
                                        
                                    </dl>
                                </div>
                                <div class="col-lg-3" id="cluster_info">
                                    <dl class="dl-horizontal" >

								   </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
								<h4><strong>Patient Details</strong></h4>
                                    <dl class="dl-horizontal">

                                        <?php if(!empty($patient_age)){ ?> <dt>Age:</dt> <dd><?php echo $patient_age; ?></dd><?php  } ?>
                                        <dt>Gender:</dt> <dd>  <?php echo $patient_gender; ?></dd>
                                       <?php if(!empty($patient_height)){ ?> <dt>Height:</dt>  <dd><?php echo $wholeFeet."' ".$fraction.'"'; ?></dd><?php  } ?>
										<?php if(!empty($patient_weight)){ ?> <dt>Weight:</dt>  <dd> <?php echo $patient_weight; ?> Kgs</dd><?php  } ?>
										<?php if(!empty($patient_height) && !empty($patient_weight)){ ?><dt>BMI:</dt> <dd><?php echo $patient_BMI."( ".$bmiStatus." )"; ?> </dd><?php  } ?>										
										<dt>Address:</dt> <dd> 	<?php echo $patient_loc; ?><br>
										<?php echo $patient_state; ?><br></dd>
										<dt>Phone:</dt> <dd>  <?php echo $patient_mob; ?></dd>
                                    </dl>
                                </div>
                                <div class="col-lg-6" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                       <?php if($patient_tab[0]['hyper_cond']!=0) { ?> <dt>Hypertension ?:</dt> <dd><?php echo $hyperStatus;  ?></dd><?php  } ?>
                                       <?php if($patient_tab[0]['diabetes_cond']!=0) { ?> <dt>Diabetes ?:</dt> <dd> <?php echo $diabetesStatus;  ?></dd><?php  } ?>
                                      
										
								   </dl>
                                </div>
                            </div>
                            
                            <div class="row m-t-sm">
                                <div class="col-lg-12">
                               <div class="panel blank-panel">
                                <div class="panel-heading">
                                    <div class="panel-options">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#tab-1" data-toggle="tab">Visit Details</a></li>
                                            <li class=""><a href="#tab-2" data-toggle="tab"> Reports (Diagnostic /Pharmacy) </a></li>
                                        </ul>
                                    </div>
                                </div>
								<div class="panel-body">

                                <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                  <table class="footable table table-stripped toggle-arrow-tiny" data-show-toggle="false" >
                                <thead>
                                <tr>

                                    <th data-show-toggle="false"></th>
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
                                <tr class="footable-detail-show">
									 <td>
									<label style="font-size: 15px"><strong>Visit Details</strong></label>
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
												<?php echo $value_exam['examination']; ?></td>
												<td><?php echo $value_exam['exam_result']; ?></td>
												<td><?php echo $value_exam['findings']; ?></td>
												</tr>
												
												<?php } //end while
												?>
												
											   </tbody>
										</table>
									<?php 
									
									} //end if ?><br><br></td>
									
									<!-- DISPLAY DIAGNOSIS -->
									<td><?php 
									$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episode_val['episode_id']."'","","","","");
									if(!empty($get_diagnosis)){
										while(list($key_diagno, $value_diagno) = each($get_diagnosis)){
										echo $value_diagno['icd_code'].", <br>"; 
										} //endif
									} //end while ?><br><br>
									<?php if(!empty($patient_episode_val['diagnosis_details'])){ ?><p><b>Diagnosis Details:</b> 
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
												<input type="hidden" name="investigation_id[]" value="<?php echo $value_invest['pti_id']; ?>"/><?php echo $value_invest['test_name']; ?></td>
												<td><?php echo $value_invest['normal_range']; ?></td>
												<td><?php echo $value_invest['test_actual_value']; ?></td>
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
												<input type="hidden" name="op_investigation_id[]" value="<?php echo $value_opinvest['pti_id']; ?>"/><?php echo $value_opinvest['test_name']; ?></td>
												<td><?php echo $value_opinvest['left_eye']; ?></td>
												<td><?php echo $value_opinvest['right_eye']; ?></td>
												</tr>
												
												<?php }
												?>
												</tbody>
											<?php } ?>
												<tbody>
												
											   </tbody>
										</table>
										
										<br>

										
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
										
									}?><br><br>
									<p><b>Treatment Details:</b> 
									<?php echo $patient_episode_val['treatment_details']; ?>
									</p>
									</td>
									
											
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
										
										<?php } ?>
												
									</td>
									
								</tr>
								<?php   
									
									$i++;
									}
								
								?>
								</tbody>
								
                            </table>

                               </div>
                                <div class="tab-pane" id="tab-2">
								<?php 
								$doc_patient_reports = mysqlSelect("DISTINCT(report_folder) as report_folder","doc_my_patient_reports","patient_id = '".$patient_tab[0]['patient_id']."' and (user_type='3' or user_type='4')","report_folder desc","","","");
								
							?>
								     <div class="feed-activity-list">
							<?php while(list($key_list, $value_list) = each($doc_patient_reports)) 
								
							{
								$get_reports = mysqlSelect("*","doc_my_patient_reports","report_folder = '".$value_list['report_folder']."' and (user_type='3' or user_type='4')","","","","");
								
								if($get_reports[0]['user_type']=='3'){
									$get_daignosis = mysqlSelect("diagnosis_name","Diagnostic_center","diagnostic_id = '".$get_reports[0]['user_id']."'","","","","");
								
									$username=$get_daignosis[0]['diagnosis_name'];
								}
								if($get_reports[0]['user_type']=='4'){
									$get_daignosis = mysqlSelect("pharma_name","pharma","pharma_id = '".$get_reports[0]['user_id']."'","","","","");
								
									$username=$get_daignosis[0]['pharma_name'];
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
										$imgIcon="../premium/patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments'];
									}
									else if($extractPath=="docx"){
											$imgIcon="../assets/images/doc.png";
									}
									else if($extractPath=="pdf" || $extractPath=="PDF"){
										$imgIcon="../assets/images/pdf.png";
									} 
									
									 ?>
									
									<div class="file-box">
										<div class="file">
											<a href="#">
												<span class="corner"></span>
												<a href="<?php echo "../premium/patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">
												<div class="image">
																								
													<img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
													
												</div></a>
											<div class="file-name">
													<?php echo substr($attachList['attachments'],0,10); ?>
													<br/>
													<small><a href="<?php echo "../premium/patientAttachments/".$patient_tab[0]['patient_id']."/".$attachList['report_folder']."/".$attachList['attachments']?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a>
													<!--<a href="https://medisensecrm.com/premium/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&episode_attach=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a>--></small>
												<!--<small class="pull-right"><a href="#" claas="delAttachments" data-report-id="<?php echo md5($attachList['report_id']);?>" data-report-folder="<?php echo $attachList['report_folder'];?>" style="color:red;" title="Delete"><i class="fa fa-trash"></i></a></small>-->
												</div>
											</a>

										</div>
									</div>
									
									<?php 

									} ?>
									  

									</ul>
                                        </div>
                                    </div>
                              
							<?php } ?>
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
            <div class="col-sm-5">
			<?php $getRefDoc = mysqlSelect("a.timestamp as ReferredOn,a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name","patient_referal as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$GetPatient[0]['patient_id']."' and a.ref_id='".$admin_id."'","","","","");
						
                        ?>
			<div class="ibox-title"><!--<b>Queries received on:  <?php echo date('d M Y,H:i',strtotime($getRefDoc[0]['ReferredOn'])); ?></b></small>-->
                       <div class="row"> 
					   <div class="col-sm-6">
					     <select class="chosen-select" id="selectDignosticCenter" data-patient-id="<?php echo $patient_id; ?>" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
							<option value="">Refer to Diagnostic Center</option>
							<?php $getDiagnostic= mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.company_id='".$admin_id."'","b.doc_diagno_id desc","","","");
																						
																						foreach($getDiagnostic as $getDiagnosticList){ ?>
																	
																							<option value="<?php echo stripslashes($getDiagnosticList['diagnostic_id']);?>" /><?php echo stripslashes($getDiagnosticList['diagnosis_name']).", ".stripslashes($getDiagnosticList['diagnosis_city']);?></option>
																						<?php
																								
																						}?>
						</select>
					   </div>
					   <div class="col-sm-6">
					     <select class="chosen-select" id="selectPharma" data-patient-id="<?php echo $patient_id; ?>" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
		<option value="">Refer to Pharmacy</option>
		<?php $getPharma= mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.company_id='".$admin_id."'","b.doc_pharma_id desc","","","");
														
														foreach($getPharma as $getPharmaList){ ?>
									
															<option value="<?php echo stripslashes($getPharmaList['pharma_id']);?>" /><?php echo stripslashes($getPharmaList['pharma_name']).", ".stripslashes($getPharmaList['pharma_city']);?></option>
														<?php
																
														}?>
	</select>
					   </div>
						</div>
                    </div>
                
				<div class="chat-discussion" style="height:600px;">
									
									<?php $getChatStatusInst1= mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and type='0'","chat_id desc","","","");
									if(count($getChatStatusInst1)>0){ ?>
									 <div class="chat-message left">
                                        <!--<img class="message-avatar" src="<?php echo $userimg; ?>" alt="" >-->
                                        <div class="message">
                                           <!-- <a class="message-author" href="#"> <?php echo $chatUserName; ?> </a><br>-->
											<span class="message-date"><?php echo date('d-M-Y H:i:s',strtotime($getChatStatusInst1[0]['created_date'])); ?></span><br>
                                            <span class="message-content">
											<?php echo $getChatStatusInst1[0]['chat_note'];  ?>
                                            </span>
                                        </div>
                                    </div>
									<?php } ?>
									<?php $getchatHistory1 = mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='1')","chat_id asc","refer_id","",""); 
									foreach($getchatHistory1 as $getList1){
										$getDiagno= mysqlSelect("*","Diagnostic_center","diagnostic_id='".$getList1['refer_id']."'");
									    $getScheduledDiagno= mysqlSelect("*","emr_referred_orderDetail","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and type='1' and refer_id='".$getList1['refer_id']."'","","","","");
									
									?>
									<div class="chat-message right">
                                        <div class="message">
                                            <span class="message-content">
											<strong>Diagnostic Centre- <?php echo $getDiagno[0]['diagnosis_name']?> Referred Notifications</strong>
											 </span>
                                        </div>
                                    </div>
									<?php $getchatHistory = mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='1') and refer_id='".$getList1['refer_id']."'","chat_id asc","","",""); 
										
										foreach($getchatHistory as $chatList){
											
										?>
                                    <div class="chat-message left">
                                        <!--<img class="message-avatar" src="<?php echo $userimg; ?>" alt="" >-->
                                        <div class="message">
                                           <!-- <a class="message-author" href="#"> <?php echo $chatUserName; ?> </a><br>-->
											<span class="message-date"><?php echo date('d-M-Y H:i:s',strtotime($chatList['created_date'])); ?></span><br>
                                            <span class="message-content">
											<?php if($chatList['status']=='6'){ echo $chatList['chat_note'].' <br/> Scheduled Date: '.$getScheduledDiagno[0]['date'].'  '.$getScheduledDiagno[0]['time']; } else{ echo $chatList['chat_note']; } ?>
                                            </span>
                                        </div>
                                    </div>
									 <?php } 
									} ?>
                                    
                                    

                                </div>	
							<div class="chat-discussion" style="height:600px;">
							<?php $getchatHistory2 = mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='2')","chat_id asc","refer_id","",""); 
									foreach($getchatHistory2 as $getList1){
										 $getDiagno= mysqlSelect("*","pharma","pharma_id='".$getList1['refer_id']."'");
											$getScheduledPharma= mysqlSelect("*","emr_referred_orderDetail","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and type='2' and refer_id='".$getList1['refer_id']."'","","","","");

									?>
							    <div class="chat-message right">
                                        <div class="message">
                                            <span class="message-content">
											<strong>Pharmacy Referred- <?php echo $getDiagno[0]['pharma_name']; ?> Notifications</strong>
											 </span>
                                        </div>
                                    </div>
									<?php $getchatHistoryPharma = mysqlSelect("*","emr_referred_notifications","md5(patient_id)='".$_GET['p']."' and md5(episode_id)='".$_GET['e']."' and (type='2') and refer_id='".$getList1['refer_id']."'","chat_id asc","","",""); 
										
										foreach($getchatHistoryPharma as $chatList){
											
										?>
                                    <div class="chat-message left">
                                        <!--<img class="message-avatar" src="<?php echo $userimg; ?>" alt="" >-->
                                        <div class="message">
                                           <!-- <a class="message-author" href="#"> <?php echo $chatUserName; ?> </a><br>-->
											<span class="message-date"><?php echo date('d-M-Y H:i:s',strtotime($chatList['created_date'])); ?></span><br>
                                            <span class="message-content">
											<?php if($chatList['status']=='6'){ echo $chatList['chat_note'].' <br/> Delivery Address: '.$getScheduledPharma[0]['delivery_address']; } else{ echo $chatList['chat_note']; } ?>
                                            </span>
                                        </div>
                                    </div>
									 <?php }
									}									 ?>
                                    
                                    

                                </div>									
						
					
        </div>
		</div>
		</div>
        <?php include_once('footer.php'); ?>

        </div>
        </div>

    <!-- Mainly scripts -->
   
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <script>
        $(document).ready(function(){

            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)

                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
    </script>
	<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
			
			$('.footable-detail-show').css('display','none');
			$('.footable-first-column').css('display','none');
			$('.footable-first-column').css('border-bottom','1px solid #ffffff');
			$('.footable-row-detail-cell').css('border-top','1px solid #ffffff');
			
			$("#selectDignosticCenter").on("change",function(){
				var t=$(this).attr("data-patient-id");
				var r=$(this).attr("data-episode-id");
				var n=$(this).val();
				
				swal({title:"Referring this case to a diagnostic ?",
				      text:"They will only be able to see patient name and tests ordered and not other details. " ,
					  type:"warning",
					  showCancelButton:true,
					  confirmButtonColor:"#DD6B55",
					  confirmButtonText:"Yes, refer it!",
					  cancelButtonText:"No, cancel!",
					  closeOnConfirm:false,
					  closeOnCancel:false },
					  function(A){
						  if(A){
						  var e="refer_diagnosis.php?diagnoid="+n+"&patientid="+t+"&episodeid="+r+"&docid="+<?php echo $patient_episodes[0]['admin_id']; ?>;
						  $.get(e,function(A){
							  console.log(A);
							  //swal("Referred Successfully!","","success");
							   swal({ title: "Referred Successfully!", text: "", type: "success" }, function(){ location.reload(); });
							  });
							  }
							  else swal("Cancelled","","error"); 
							  });
				});
				
				$("body").on("change","#selectPharma",function(){
					var t=$(this).attr("data-patient-id");
					var r=$(this).attr("data-episode-id");
					var p=$(this).val();
					swal({        title:"Referring this case to a Pharmacy ?",
								  text:"They will only be able to see patient name and Prescription and not other details.",
								  type:"warning",
								  showCancelButton:true,
								  confirmButtonColor:"#DD6B55",
								  confirmButtonText:"Yes, refer it!",
								  cancelButtonText:"No, cancel!",
								  closeOnConfirm:false,
								  closeOnCancel:false },
								  function(A){
									  if(A){
										  var e="refer_diagnosis.php?pharmaid="+p+"&patientid="+t+"&episodeid="+r+"&docid="+<?php echo $patient_episodes[0]['admin_id']; ?>;
										  $.get(e,function(A){
											  console.log(A);
											 // swal("Referred Successfully!","","success").then(function(){  location.reload();});
											  swal({ title: "Referred Successfully!", text: "", type: "success" }, function(){ location.reload(); });
											 
											  });
											  }
											  else swal("Cancelled","","error");
								 });
				  });

        });

    </script>
	
</body>

</html>
