<?php
ob_start();
error_reporting(0); 
session_start();

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

		
	//$patient_tab = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['pid']."'","","","","");
	$patient_tab = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['pid']."'","","","","");
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

			$patient_episodes = mysqlSelect("*","doc_patient_episodes","patient_id = '". $patient_id ."' and md5(episode_id)='".$_GET['episode']."'","","","","");
			
			$episode_created_date=date('d M Y, H:i a',strtotime($patient_episodes[0]['date_time']));   //Prescription Date
			
			
			$get_medical_complaint = mysqlSelect("b.symptoms as symptoms","doc_patient_symptoms_active as a left join chief_medical_complaints as b on a.symptoms=b.complaint_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				
			$get_diagnosis = mysqlSelect("b.icd_code as icd_code","patient_diagnosis as a left join icd_code as b on a.icd_id=b.icd_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
			
			$get_examination = mysqlSelect("b.examination as examination,a.exam_result as exam_result,a.findings as findings","doc_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				
			$get_invest = mysqlSelect("*","patient_temp_investigation","patient_id = '". $patient_id ."' and episode_id='". $patient_episodes[0]['episode_id']."'","","","","");
										
		//Doctors Details	
			$get_doc_details = mysqlSelect("ref_name","referal","ref_id='".$patient_episodes[0]['admin_id']."'","","","","");
			$doctor_name = $get_doc_details[0]['ref_name'];  //Doctor Name
			$_SESSION['doc_name'] = $get_doc_details[0]['ref_name'];
		//Doctors Specialization
			$get_doc_spec = mysqlSelect("a.spec_name as specialization, a.spec_group_id as spec_group_id","specialization as a inner join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","a.spec_id ASC","","","");
			
		//Get Host Name
			if($get_doc_spec[0]['spec_group_id']==1)
			{
				$hostName = "https://medisensecrm.com/premium/My-Patient-Details";
			}else if($get_doc_spec[0]['spec_group_id']==2)
			{
				$hostName = "https://medisensecrm.com/premium/Ophthal-EMR/";				
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
		$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episodes[0]['episode_id'] ."' "," prescription_seq ASC","","","");

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

function get_regional_language($pst) {
    $state = strtolower($pst);
    $marathi_states = array('maharashtra');
    $kannada_states = array('karnataka');
    $tamil_states = array('tamil nadu', 'tamilnadu', 'pondicherry', 'puducherry');
    $gujarati_states = array('gujarat', 'gujarath', 'gujrat', 'gujrath', 'daman', 'diu');
    $telugu_states = array('andhra pradesh', 'telangana', 'andhra', 'andhrapradesh');
    $regional_language = "hindi";
    if(in_array($state, $marathi_states)) {
	$regional_language = "marathi";
    } else if(in_array($state, $kannada_states)) {
	$regional_language = "kannada";
    } else if(in_array($state, $tamil_states)) {
	$regional_language = "tamil";
    } else if(in_array($state, $gujarati_states)) {
	$regional_language = "gujrathi";
    } else if(in_array($state, $telugu_states)) {
	$regional_language = "telugu";
    }
    return $regional_language;
}

function get_timing_in_regional_language($lang, $id) {
    if(!$id) { return ""; }
    $marathi = array("रात्री च्या जेवणा नंतर", "जेवणानंतर", "न्याहारी पूर्वी", "गरज असेल तेव्हांं","अन्न करण्यापूर्वी","अन्न झाल्यानंतर","दुपारच्या जेवणा आधी","रात्रीच्या जेवण च्या अगोदर","नाश्ता केल्यानंतर");
    $hindi = array("रात के खाने के बाद", "दोपहर के भोजन के बाद", "नाश्ते से पहले", "जब जरूरत", "भोजन से पहले", "भोजन के बाद", "दोपहर के भोजन से पहले", "रात के खाने से पहले","नाश्ते के बाद");
    $kannada = array("ರಾತ್ರಿ ಊಟದ ನಂತರ", "ಮಧ್ಯಾನ್ನದ ಊಟದ ನಂತರ", "ಉಪಹಾರದ ಮೊದಲು", "ಅಗತ್ಯವಿದ್ದಾಗ","ಆಹಾರದ ಮೊದಲು", "ಆಹಾರದ ನಂತರ", "ಮಧ್ಯಾನ್ನದ ಊಟದ ಮೊದಲು", "ರಾತ್ರಿ ಊಟದ ಮೊದಲು", "ಉಪಹಾರದ ನಂತರ");
    $tamil = array("இரவு உணவிற்குப்பின்", "மதிய உணவிற்கு பின்", "முன் சிற்றுண்டி", "தேவைப்படும் போது","உணவுக்கு முன்","உணவுக்குப் பிறகு","மதிய உணவிற்கு முன்","மதிய உணவிற்கு முன்","காலை உணவிற்கு பிறகு");
    $gujrathi = array("પછી રાત્રિભોજન", "પછી બપોરના", "પહેલાં નાસ્તા", "જ્યારે જરૂર પડે ત્યારે","ખોરાક પહેલાં","ખોરાક પછી","લંચ પહેલાં","રાત્રિભોજન પહેલાં","નાસ્તા પછી");
    $telugu = array("రాత్రి భోజనం తర్వాత", "భోజనము తర్వాత", "అల్పాహారం ముందు", "ఎప్పుడు అవసరమైతే","ఆహారం ముందు","ఆహారం తరువాత","భోజనం ముందు","విందు ముందు","అల్పాహారం తరువాత");
    switch($lang) {
	case 'marathi':
	    $arr = $marathi;
	    break;
	case 'hindi':
	    $arr = $hindi;
	    break; 
	case 'kannada':
	    $arr = $kannada;
	    break; 
	case 'tamil':
	    $arr = $tamil;
	    break; 
	case 'gujrathi':
	    $arr = $gujrathi;
	    break; 
	case 'telugu':
	    $arr = $telugu;
	    break; 
    }
    return $arr[$id - 1];
}

$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$patient_episodes[0]['admin_id']."' and doc_type='1'","","","","");
		
?>

<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Print Patient Visit Details</title>
	<link rel="stylesheet" media="all" href="assets/css/print-emr.css">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    </head>
    <body id="<?php echo $_GET['pid']; ?>" data-pmail="<?php echo $patient_email; ?>" data-mobile="<?php echo $patient_mob; ?>">
	
	<!-- <div id="actions">
		<select data-placeholder="Select Language ..." class="chosen-select" id="selLanguage" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
	<option value="">Select Language ...</option>
	<option value="english" >English</option>
	<option value="hindi" >Hindi</option>
	<option value="gujarath" >Gujarati</option>
	<option value="marathi" >Marathi</option>
	<option value="kannada" >Kannada</option>
	<option value="telugu" >Telugu</option>	
	<option value="tamil" >Tamil</option>
	</select><br>
	<div class="radioSection">
	
	<?php if($_GET['s'] == '2') {  ?>
	<input type="radio" class="checkEMR" value="1"  checked="checked" onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=2';" autocomplete="off">
	<?php } else if(empty($_GET['s'])) { ?>
	<input type="radio" class="checkEMR" value="1" checked="checked" onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=2';" autocomplete="off">
	<?php } else { ?>
	<input type="radio" class="checkEMR" value="1" onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=2';" autocomplete="off">
	<?php } ?>	
	<label for="inlineRadio1"> EMR </label>
	
	<?php if($_GET['s'] == '3') {  ?>
	 <input type="radio" class="checkEMR" value="2"  checked="checked" onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=3';" autocomplete="off">   
	<?php } else if(empty($_GET['s'])) { ?>
	 <input type="radio" class="checkEMR" value="2"  onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=3';" autocomplete="off">
	<?php } else { ?>	
	<input type="radio" class="checkEMR" value="2"  onClick="window.location = '?pid=<?php echo $_GET['pid']; ?>&episode=<?php echo $_GET['episode']; ?>&s=3';" autocomplete="off">
	 <?php } ?>   
	<label for="inlineRadio1"> Prescription only </label>
	 </div>
	    <a href="#" class="btn" onclick="window.print(); return false;">PRINT</a>
	    <a href="#" class="btn" id="sendEMAIL" data-id="<?php echo $_GET['pid']; ?>" data-email="<?php echo $patient_email; ?>">Email to patient</a>
		<a href="#" class="btn" id="sendSMS" data-id="<?php echo $_GET['pid']; ?>" data-mobile="<?php echo $patient_mob; ?>">SMS to patient</a>
		
		<select class="chosen-select" id="selectDignosticCenter" data-patient-id="<?php echo $patient_id; ?>" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
		<option value="">Refer to Diagnostic Center</option>
		<?php $getDiagnostic= mysqlSelect("*","Diagnostic_center as a left join doc_diagnostics as b on a.diagnostic_id=b.diagnostic_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","b.doc_diagno_id desc","","","");
																	
																	foreach($getDiagnostic as $getDiagnosticList){ ?>
												
																		<option value="<?php echo stripslashes($getDiagnosticList['diagnostic_id']);?>" /><?php echo stripslashes($getDiagnosticList['diagnosis_name']).", ".stripslashes($getDiagnosticList['diagnosis_city']);?></option>
																	<?php
																			
																	}?>
	</select><br><br>
	<select class="chosen-select" id="selectPharma" data-patient-id="<?php echo $patient_id; ?>" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
		<option value="">Refer to Pharmacy</option>
		<?php $getPharma= mysqlSelect("*","pharma as a left join doc_pharma as b on a.pharma_id=b.pharma_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","b.doc_pharma_id desc","","","");
														
														foreach($getPharma as $getPharmaList){ ?>
									
															<option value="<?php echo stripslashes($getPharmaList['pharma_id']);?>" /><?php echo stripslashes($getPharmaList['pharma_name']).", ".stripslashes($getPharmaList['pharma_city']);?></option>
														<?php
																
														}?>
	</select><br><br>
	
	<?php if($get_doc_spec[0]['spec_group_id'] == 2) { ?>
	
	<select class="chosen-select" id="selectOpticleCenter" data-patient-id="<?php echo $patient_id; ?>" data-episode-id="<?php echo $patient_episodes[0]['episode_id']; ?>" autocomplete="off">
		<option value="">Refer to Opticles</option>
		<?php $getOpticles= mysqlSelect("*","Opticle_center as a left join doc_opticles as b on a.opticale_id=b.opticale_id","b.doc_id='".$patient_episodes[0]['admin_id']."'","b.doc_opticle_id desc","","","");
														
														foreach($getOpticles as $getOpticlesList){ ?>
									
															<option value="<?php echo stripslashes($getOpticlesList['opticale_id']);?>" /><?php echo stripslashes($getOpticlesList['opticle_name']).", ".stripslashes($getOpticlesList['opticle_city']);?></option>
														<?php
																
														}?>
	</select><br>
	<?php } ?>
	    <a href="<?php echo $hostName;?>?p=<?php echo $_GET['pid'];?>&episode=<?php echo $_GET['episode'];?>" class="btn">BACK</a>
		
		<a href="#" class="btn" onclick="window.close(); return false;">EXIT</a>
		
	</div> -->
	<div class="container" id="main-content">
		
		<?php if($checkSetting[0]['prescription_pad']=="0" || $checkSetting[0]['prescription_pad']=="2") { ?>
	    <header class="group">
		<img src="assets/images/doctor-symbol-b.png" width="120">
		<div id="doc-details">
		    <p>
		    <strong>Dr. <?php echo $doctor_name; ?></strong><br>
		    <?php
			while(list($key_spec, $value_spec) = each($get_doc_spec)){
				echo $value_spec['specialization'].", ";   //Doctor Specialization				
			}
		    ?><br>
		    <?php echo $Clinic_name; ?>
		    <?php echo $Clinic_address; ?><br>
		    <?php echo $Clinic_City . ", " . $Clinic_State; ?><br>
		    <?php if($clinic_contact) { echo "Tel: " . $clinic_contact . "<br>"; } ?>
		    <?php if($clinic_email) { echo "Email: " . $clinic_email . "<br>"; } ?>
		    </p>
		</div>
	    </header>
		<?php } 
		else {
			$headerHeightPixel = $checkSetting[0]['presc_pad_header_height']*37.795276;
			$footerHeightPixel = $checkSetting[0]['presc_pad_footer_height']*37.795276;
			?>
		 <header class="group">
			
			<div style="margin-top:<?php echo $headerHeightPixel; ?>px;"></div>
		 
		 </header>
		 
		<?php } ?>
	    <div id="patient-details" class="group">
		<p style="width:250px;">
		    <b>ID: #</b><?php echo $patient_id; ?><br>
		    <b>Name:</b> <?php echo $patient_name; ?><br>
		    <?php if(!empty($patient_age)){ ?><b>Age:</b> <?php echo $patient_age; ?> Yrs<br><?php  } ?>
		    <b>Gender:</b> <?php echo $patient_gender; ?><br>
			<?php if(!empty($patient_height)){ ?><b>Height:</b> <?php echo $wholeFeet."' ".$fraction.'"'; ?><br><?php  } ?>
			<?php if(!empty($patient_weight)){ ?><b>Weight:</b> <?php echo $patient_weight; ?> Kgs<br><?php  } ?>
			<?php if(!empty($patient_height) && !empty($patient_weight)){ ?><b>BMI:</b> <?php echo $patient_BMI."( ".$bmiStatus." )"; ?><br><?php  } ?>
		</p>
		<p style="width:200px;">
		    <b>Address:</b><br>
		    <?php echo $patient_loc; ?><br>
		    <?php echo $patient_state; ?><br>
		    <b>Phone:</b> <?php echo $patient_mob; ?>
		</p>
		<p style="width:240px;">
		<b>Date:</b> <?php echo $episode_created_date; ?>
		
		<div id="qr">
		<img src="assets/images/medisense-qr.png">
	    </div>
		</p>
	    </div>

		<?php  if(($_GET['s'] == '2') || empty($_GET['s'])) { ?>
	    <div id="diagnosis" >
		<ul>
		<?php if($patient_tab[0]['hyper_cond']!=0) { ?><li><strong>Hypertension:</strong> <?php echo $hyperStatus; ?></li><?php } ?>
		<?php if($patient_tab[0]['diabetes_cond']!=0) { ?><li><strong>Diabetes:</strong> <?php echo $diabetesStatus; ?></li><?php } ?>
		<?php if(!empty($get_medical_complaint)){ ?>
		    <li><strong>Chief Medical Complaint:</strong>
		    <?php
			while(list($key_symp, $value_symp) = each($get_medical_complaint)){
			    echo $value_symp['symptoms'].", ";      //Patient Chief medical complaints
			}
		    ?>
		    </strong></li>
		<?php 
		} 
			if(!empty($get_diagnosis)){ ?>
		    <li><strong>Diagnosis:</strong><br>
		    <?php
			while(list($key_diagno, $value_diagno) = each($get_diagnosis)){
				echo $value_diagno['icd_code']."<br>";  //Patient Diagnosis Conditions
			}
		    ?></strong></li>
		    <?php }

			if(!empty($patient_episodes[0]['diagnosis_details'])){ ?>
		    <li><strong>Diagnosis Details:</strong><br>
		    <?php
			echo $patient_episodes[0]['diagnosis_details'];
		    ?></strong></li>
		    <?php } 
			
			if(!empty($get_invest)){ ?>
			 <li><strong>Investigations:</strong><br>
		    <?php
			
				while(list($key_invest, $value_invest) = each($get_invest)){
				echo $value_invest['test_name'].", ";  //Patient Investigation
			}
		    ?></strong></li>
		    <?php }
			if(!empty($patient_episodes[0]['treatment_details'])){ ?>
		    <li><strong>Treatment Details:</strong><br>
		    <?php
			echo $patient_episodes[0]['treatment_details'];
		    ?></strong></li>
		    <?php }
			
			if(!empty($get_examination)){ ?>
		    <li><strong>Examination:</strong><br>
			<?php $i=1;
					while(list($key_exam, $value_exam) = each($get_examination)){  
					echo $i.". ".$value_exam['examination']." - ".$value_exam['exam_result']." - ".$value_exam['findings']."<br>";
					$i++;
					} //end while
			?>
			</li>
		    <?php } ?>
			
		</ul>
	    </div>
		<?php } ?>
		
		<?php 
		if($get_doc_spec[0]['spec_group_id'] == 2) { ?>
		<div id="examination" cellpadding="0" cellspacing="0" border="0">
		<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
				<thead>
					<th colspan="6" class="text-center" style="vertical-align:middle;text-align:center;"><font size="3">Examinations</font></th>
				</thead>
				<thead>
					<th class="text-center" style="vertical-align:middle;text-align:center;">NAME</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">RIGHT</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">LEFT</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">NAME</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">RIGHT</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">LEFT</th>
				</thead>
				<tbody>
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Distance Vision</td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $get_exam_refraction[0]['distacnce_vision_right'];  ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $get_exam_refraction[0]['distance_vision_left'];  ?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Near Vision</td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $get_exam_refraction[0]['near_vision_right'];  ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $get_exam_refraction[0]['near_vision_left'];  ?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Lids</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$lids_content_RE ="";
														while(list($key_exam, $value_exam_lids) = each($get_exam_lids))	
														{
														$lids_content_RE .=$value_exam_lids['lids_name'];
														$lids_content_RE .=", ";
														} //end while
														echo substr(trim($lids_content_RE), 0, -1);
													?>
													</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$lids_content_LE ="";
														while(list($key_exam, $value_exam_lidsLE) = each($get_exam_lidsLE))	
														{ 
														$lids_content_LE .=$value_exam_lidsLE['lids_name'];
														$lids_content_LE .=", ";													
														} //end while
														echo substr(trim($lids_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Conjuctiva</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$conjuctiva_content_RE ="";
														while(list($key_exam, $value_exam_conjuctivaRE) = each($get_exam_conjuctivaRE))	
														{  
														$conjuctiva_content_RE .=$value_exam_conjuctivaRE['conjuctiva_name'];
														$conjuctiva_content_RE .=", ";
														 } //end while
														 echo substr(trim($conjuctiva_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$conjuctiva_content_LE ="";
														while(list($key_exam, $value_exam_conjuctivaLE) = each($get_exam_conjuctivaLE))	
														{  
														$conjuctiva_content_LE .=$value_exam_conjuctivaLE['conjuctiva_name'];
														$conjuctiva_content_LE .=", ";
														} //end while
														 echo substr(trim($conjuctiva_content_LE), 0, -1);
													?></td>
					</tr>
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Sclera</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$sclera_content_RE ="";
														while(list($key_exam, $value_exam_scleraRE) = each($get_exam_scleraRE))	
														{ 
														$sclera_content_RE .=$value_exam_scleraRE['scelra_name'];
														$sclera_content_RE .=", ";		
														} //end while
														echo substr(trim($sclera_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$sclera_content_LE ="";
														while(list($key_exam, $value_exam_scleraLE) = each($get_exam_scleraLE))	
														{  
														$sclera_content_LE .=$value_exam_scleraLE['scelra_name'];
														$sclera_content_LE .=", ";
														} //end while
														echo substr(trim($sclera_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Cornea Anterior</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$corneaant_content_RE ="";
														while(list($key_exam, $value_exam_corneaantRE) = each($get_exam_cornea_anteriorRE))	
														{  
														$corneaant_content_RE .=$value_exam_corneaantRE['cornea_ant_name'];
														$corneaant_content_RE .=", ";
														} //end while
														echo substr(trim($corneaant_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$corneaant_content_LE ="";
														while(list($key_exam, $value_exam_corneaantLE) = each($get_exam_cornea_anteriorLE))	
														{  
														$corneaant_content_LE .=$value_exam_corneaantLE['cornea_ant_name'];
														$corneaant_content_LE .=", ";
														} //end while
														echo substr(trim($corneaant_content_LE), 0, -1);
													?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Cornea Posterior</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$corneapost_content_RE ="";
														while(list($key_exam, $value_exam_corneapostRE) = each($get_exam_cornea_posteriorRE))	
														{  
														$corneapost_content_RE .=$value_exam_corneapostRE['cornea_post_name'];
														$corneapost_content_RE .=", ";
														} //end while
														echo substr(trim($corneapost_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;"><?php
														$corneapost_content_LE ="";
														while(list($key_exam, $value_exam_corneapostLE) = each($get_exam_cornea_posteriorLE))	
														{ 
														$corneapost_content_LE .=$value_exam_corneapostLE['cornea_post_name'];
														$corneapost_content_LE .=", ";
														} //end while
														echo substr(trim($corneapost_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Anterior Chamber</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$chamber_content_RE ="";
														while(list($key_exam, $value_exam_chamberRE) = each($get_exam_anterior_chamberRE))	
														{  
														$chamber_content_RE .=$value_exam_chamberRE['chamber_name'];
														$chamber_content_RE .=", ";
														} //end while
														echo substr(trim($chamber_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$chamber_content_LE ="";
														while(list($key_exam, $value_exam_chamberLE) = each($get_exam_anterior_chamberLE))	
														{ 
														$chamber_content_LE .=$value_exam_chamberLE['chamber_name'];
														$chamber_content_LE .=", ";
														} //end while
														echo substr(trim($chamber_content_LE), 0, -1);
													?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Iris</td>
						<td style="vertical-align:middle;text-align:left;"><?php
														$iris_content_RE ="";
														while(list($key_exam, $value_exam_irisRE) = each($get_exam_anterior_irisRE))	
														{ 
														$iris_content_RE .=$value_exam_irisRE['iris_name'];
														$iris_content_RE .=", ";
														} //end while
														echo substr(trim($iris_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$iris_content_LE ="";
														while(list($key_exam, $value_exam_irisLE) = each($get_exam_anterior_irisLE))	
														{ 
														$iris_content_LE .=$value_exam_irisLE['iris_name'];
														$iris_content_LE .=", ";
														} //end while
														echo substr(trim($iris_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Pupil</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$pupil_content_RE ="";
														while(list($key_exam, $value_exam_ipupilRE) = each($get_exam_pupil_RE))	
														{ 
														$pupil_content_RE .=$value_exam_ipupilRE['pupil_name'];
														$pupil_content_RE .=", ";
														} //end while
														echo substr(trim($pupil_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$pupil_content_LE ="";
														while(list($key_exam, $value_exam_pupilLE) = each($get_exam_pupil_LE))	
														{  
														$pupil_content_LE .=$value_exam_pupilLE['pupil_name'];
														$pupil_content_LE .=", ";
														} //end while
														echo substr(trim($pupil_content_LE), 0, -1);
													?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Angle of Anterior Chamber</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$angle_content_RE ="";
														while(list($key_exam, $value_exam_angleRE) = each($get_exam_angle_RE))	
														{  
														$angle_content_RE .=$value_exam_angleRE['angle_name'];
														$angle_content_RE .=", ";
														} //end while
														echo substr(trim($angle_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$angle_content_LE ="";
														while(list($key_exam, $value_exam_angleLE) = each($get_exam_angle_LE))	
														{ 
														$angle_content_LE .=$value_exam_angleLE['angle_name'];
														$angle_content_LE .=", ";
														} //end while
														echo substr(trim($angle_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Lens</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$lens_content_RE ="";
														while(list($key_exam, $value_exam_lensRE) = each($get_exam_lens_RE))	
														{ 
														$lens_content_RE .=$value_exam_lensRE['lens_name'];
														$lens_content_RE .=", ";
														} //end while
														echo substr(trim($lens_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php
														$lens_content_LE ="";
														while(list($key_exam, $value_exam_lensLE) = each($get_exam_lens_LE))	
														{
														$lens_content_LE .=$value_exam_lensLE['lens_name'];
														$lens_content_LE .=", ";
														} //end while
														echo substr(trim($lens_content_LE), 0, -1);
													?></td>
					</tr>
					
					<tr>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Viterous</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$viterous_content_RE ="";
														while(list($key_exam, $value_exam_viterousRE) = each($get_exam_viterous_RE))	
														{
														$viterous_content_RE .=$value_exam_viterousRE['viterous_name'];
														$viterous_content_RE .=", ";
														} //end while
														echo substr(trim($viterous_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php
														$viterous_content_LE ="";
														while(list($key_exam, $value_exam_viterousLE) = each($get_exam_viterous_LE))	
														{
														$viterous_content_LE .=$value_exam_viterousLE['viterous_name'];
														$viterous_content_LE .=", ";
														} //end while
														echo substr(trim($viterous_content_LE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;padding-left: 20px;">Fundus</td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$fundus_content_RE ="";
														while(list($key_exam, $value_exam_fundusRE) = each($get_exam_fundus_RE))	
														{
														$fundus_content_RE .=$value_exam_fundusRE['fundus_name'];
														$fundus_content_RE .=", ";
														} //end while
														echo substr(trim($fundus_content_RE), 0, -1);
													?></td>
						<td style="vertical-align:middle;text-align:left;"><?php 
														$fundus_content_LE ="";
														while(list($key_exam, $value_exam_fundusLE) = each($get_exam_fundus_LE))	
														{  
														$fundus_content_LE .=$value_exam_fundusLE['fundus_name'];
														$fundus_content_LE .=", ";
														} //end while
														echo substr(trim($fundus_content_LE), 0, -1);
													?></td>
					</tr>
					
					
				</tbody>
				</table>
		</div>
		<br>
		
		<?php 
		if (COUNT($doc_patient_spectacle_prescriptions) > 0)  {
			?>
			
		<div id="prescription" cellpadding="0" cellspacing="0" border="0">
		<table cellpadding="2" cellspacing="2" border="1" class="table table-bordered col-lg-12" width="100%">
				<thead>
					<th colspan="8" class="text-center" style="vertical-align:middle;text-align:center;"><font size="3">Spectacle Prescriptions</font></th>
				</thead>
				<thead>
					<th colspan="4" class="text-center" style="vertical-align:middle;text-align:center;"><font size="3">RIGHT EYE</font></th>
					<th colspan="4" class="text-center" style="vertical-align:middle;text-align:center;"><font size="3">LEFT EYE</font></th>
				</thead>
				<thead>
					<th></th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Sphere</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Cyl</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Axis</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Sphere</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Cyl</th>
					<th class="text-center" style="vertical-align:middle;text-align:center;">Axis</th>
				</thead>
				<tbody>
					<tr><td>D.V</td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['dvSphereRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['DvCylRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['DvAxisRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['DvSpeherLE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['DvCylLE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['DvAxisLE']; ?></td>
					</tr>
																			
					<tr><td>N.V</td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvSpeherRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvCylRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvAxisRE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvSpeherLE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvCylLE']; ?></td>
						<td style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['NvAxisLE']; ?></td>
					</tr>
																			
					<tr><td>IPD</td>
						<td colspan="3" style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['IpdRE']; ?></td>
						<td colspan="3" style="vertical-align:middle;text-align:center;"><?php echo $doc_patient_spectacle_prescriptions[0]['IpdLE']; ?></td>
					</tr>
			</tbody>
		</table>
		</div>
	<br>		
		<?php
		}
		}
		?>

	
<?php if (COUNT($doc_patient_episode_prescriptions) > 0) { ?>
	    <div id="prescription" cellpadding="0" cellspacing="0" border="0">
		<table>
				<thead>
					<tr>
						<th width="220">Medicine</th>
						<th width="220">Generic Name</th>
						<th>Dosage Frequency</th>
						<th>Timing</th>
						<th>Duration</th>
					</tr>
				</thead>
					<tbody>
					<?php
						$regional_lang = get_regional_language($patient_state);
						while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
							{
							//Prescrption Timing iin different language
							$prescription_timing=mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");
							$presc_timing_english = $prescription_timing[0]['english']; //Timing in english
							//$presc_timing_regional = $prescription_timing[0][$regional_lang];
							$presc_timing_regional = get_timing_in_regional_language($regional_lang, $prescription_timing[0]['language_id']);

							//print_r($prescription_timing[0]);
					?>
						<tr>
							<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
							<td><?php echo $presc_timing_english."<br>".$presc_timing_regional; ?><br></td>
							<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
						</tr>
					<?php } //end while ?>
					</tbody>
		</table>
	    </div>
		<div id="aferPrescription"></div>
	
			<?php } //endif
			
			if(!empty($patient_episodes[0]['prescription_note'])){
		?>	
			<div id="diagnosis" >
			<ul>
			<li><strong>Prescription Note :</strong><br><?php echo $patient_episodes[0]['prescription_note']; ?></li>
			</ul>
			</div><br><br>
	<?php }	
			if(!empty($checkSetting[0]['doc_flash_msg'])){
		?>	
			<div id="diagnosis" >
			<ul>
			<li><?php echo $checkSetting[0]['doc_flash_msg']; ?></li>
			</ul>
			</div>
	<?php }
	
	} //endif
?>

	    
		 <!-- Sweet alert -->
    <script src="../../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
	    <script src="assets/js/print-emr.min.js"></script>
		
		<?php if($checkSetting[0]['prescription_pad']=="1"){ ?>
		<div style="margin-bottom:<?php echo $footerHeightPixel; ?>px;"></div>
		<?php } ?>
		
	</div>
    </body>
</html>
