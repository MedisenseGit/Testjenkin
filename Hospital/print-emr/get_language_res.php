<?php
ob_start();
error_reporting(0); 

require_once("../../classes/querymaker.class.php");


		
		//Prescription Details
		$doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $_GET['episode'] ."' ","episode_prescription_id asc","","","");
		$patient_episodes = mysqlSelect("prescription_template","doc_patient_episodes","episode_id='".$_GET['episode']."'","","","","");
			
		$patient_state =$_GET['language'];

function get_regional_language($pst) {
    $state = strtolower($pst);
	$hindi_states = array('hindi');
    $marathi_states = array('marathi', 'maharashtra');
    $kannada_states = array('kannada', 'karnataka');
    $tamil_states = array('tamil', 'tamil nadu', 'tamilnadu', 'pondicherry', 'puducherry');
    $gujarati_states = array('gujarat', 'gujarath', 'gujrat', 'gujrath', 'daman', 'diu');
    $telugu_states = array('telugu','andhra pradesh', 'telangana', 'andhra', 'andhrapradesh');
    if(in_array($state, $hindi_states)) {
	$regional_language = "hindi";
	} else if(in_array($state, $marathi_states)) {
	$regional_language = "marathi";
    } else if(in_array($state, $kannada_states)) {
	$regional_language = "kannada";
    } else if(in_array($state, $tamil_states)) {
	$regional_language = "tamil";
    } else if(in_array($state, $gujarati_states)) {
	$regional_language = "gujrathi";
    } else if(in_array($state, $telugu_states)) {
	$regional_language = "telugu";
    } else {
		$regional_language = "english";
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

function get_doc_regional_state($dst) 
{
	$state = strtolower($dst);
	$hindi_states = array('hindi');
	$marathi_states = array('marathi','maharashtra');
	$kannada_states = array('kannada','karnataka');
	$tamil_states = array('tamil','tamil nadu', 'tamilnadu', 'pondicherry', 'puducherry');
	$gujarati_states = array('gujarat', 'gujarath', 'gujrat', 'gujrath', 'daman', 'diu');
	$telugu_states = array('telugu','andhra pradesh', 'telangana', 'andhra', 'andhrapradesh');
	$kerala_states = array('kerala');
	if(in_array($state, $hindi_states)) {
	$regional_language = "hindi";
	} else if(in_array($state, $marathi_states)) {
		$regional_language = "marathi";
	} else if(in_array($state, $kannada_states)) {
		$regional_language = "kannada";
	} else if(in_array($state, $tamil_states)) {
		$regional_language = "tamil";
	} else if(in_array($state, $gujarati_states)) {
		$regional_language = "gujrathi";
	} else if(in_array($state, $telugu_states)) {
		$regional_language = "telugu";
	} else if(in_array($state, $kerala_states)) {
		$regional_language = "malayalam";
	} else {
		$regional_language = "english";
	}
		return $regional_language;
}

function get_doc_regional_language($lang) {
									
	$marathi = array("औषध", "सकाळी", "दुपारी", "रात्री","कालावधी","वेळ","सूचना");
	$hindi = array("दवा", "सुबह", "दोपहर", "रात","अवधि","समय","अनुदेश");
	$kannada = array("ಔಷಧ", "ಬೆಳಿಗ್ಗೆ", "ಮಧ್ಯಾಹ್ನ", "ರಾತ್ರಿ","ಅವಧಿ", "ಸಮಯ", "ಸೂಚನೆಗಳು");
	$tamil = array("மருந்து", "காலை", "பிற்பகல்", "இரவு","கால","நேரம்","அறிவுறுத்தல்கள்");
	$gujrathi = array("દવા", "સવાર", "બપોર", "રાત્રે","અવધિ","સમય","સૂચનો");
	$telugu = array("వైద్యం", "ఉదయం", "మధ్యాహ్నం", "రాత్రి","వ్యవధి","సమయం","సూచనలను");
	$malayalam = array("മരുന്ന്", "രാവിലെ", "ഉച്ചകഴിഞ്ഞ്", "രാത്രി","കാലാവധി","സമയം","നിർദ്ദേശങ്ങൾ");
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
		case 'malayalam':
		$arr = $malayalam;
		break; 
	}
		return $arr;
}
$regional_state = get_doc_regional_state($patient_state);	
$get_doc_lang = get_doc_regional_language($regional_state);		
?>
<div cellpadding="0" cellspacing="0" border="0">
<?php  if($patient_episodes[0]['prescription_template']==0){ ?>

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
		<?php } else if($patient_episodes[0]['prescription_template']==1){ ?>
<table >
				<thead>
																				<!--<th style="width:2px;">S.No.</th>-->	
																				<th style="width:450px;padding: 0px;">Medicine <br><?php echo $get_doc_lang[0];?></th>
																				<th><table style="width:100%" padding="0" border="0">
																					<tr style="border-bottom:none;">
																					<th style="width:10px;text-align:left;padding: 0px; ">Morning<br><?php echo $get_doc_lang[1];?></th>
																					<th style="width:10px;text-align:left;padding: 0px;">Noon<br><?php echo $get_doc_lang[2];?></th>
																					<th style="width:10px;text-align:left;padding: 0px;">Night<br><?php echo $get_doc_lang[3];?></th>
																					</tr>
																					</table>
																				</th>
																				<th style="width:60px;text-align:left;padding: 0px;">Duration<br><?php echo $get_doc_lang[4];?></th>
																				<!--<th style="width:30px;padding: 0px;" >Timing<br><?php echo $get_doc_lang[5];?></th>
																				<th style="width:10px;text-align:left;padding: 0px; ">Morning<br><?php echo $get_doc_lang[1];?></th>
																				<th style="width:10px;text-align:left;padding: 0px;">Noon<br><?php echo $get_doc_lang[2];?></th>
																				<th style="width:10px;text-align:left;padding: 0px;">Night<br><?php echo $get_doc_lang[3];?></th>
																				<th style="width:80px;text-align:left;padding: 0px;">Duration<br><?php echo $get_doc_lang[4];?></th>
																				<th style="width:30px;padding: 0px;" >Timing<br><?php echo $get_doc_lang[5];?></th>
																				-->
																			
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

							$sl_num = $patient_episode_prescription_key +1;
					?>
						<tr>
						<!--<td><?php echo $sl_num; ?></td>-->
						
						<td valign="top" colspan="3" style="padding: 0px;">
							<table width="100%" border="0" valign="top">
							<tr style="border-bottom:none;">
							<td valign="top" style="width:60%;padding: 0px;"><?php echo "<b>".strtoupper($patient_episode_prescription_val['prescription_trade_name'])."</b><br><span style='font-size:10px;'>".strtoupper($patient_episode_prescription_val['prescription_generic_name'])."</span>"; ?></td>
							<td valign="top" style="width:30%;">
								<table style="width:90%" padding="0" border="0" >
								<tr style="border-bottom:none; text-align:center;">
								<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;<b><?php if(empty($patient_episode_prescription_val['med_frequency_morning'])){ echo "0"; } else { echo $patient_episode_prescription_val['med_frequency_morning']; } ?></b></td>
								<td valign="top">------</td>
								<td valign="top"><b><?php if(empty($patient_episode_prescription_val['med_frequency_noon'])){ echo "0"; } else { echo $patient_episode_prescription_val['med_frequency_noon']; } ?></b></td>
								<td valign="top">------</td>
								<td valign="top"><b><?php if(empty($patient_episode_prescription_val['med_frequency_night'])){ echo "0"; } else { echo $patient_episode_prescription_val['med_frequency_night']; } ?></b></td>
								
								</tr>
								<tr style="border-bottom:none;">
								<td colspan="5" style="font-size:8px; text-align:center;padding: 0px;"><?php echo $prescription_timing[0]['english']." ".$presc_timing_regional; ?><br></td>
								</tr>
								</table>	
							</td>
							<td valign="top" align="left" style="width:10%;text-align:center;padding: 0px;"><?php echo $patient_episode_prescription_val['duration']." ".$patient_episode_prescription_val['med_duration_type']; ?></td>
							</tr>	
							<?php if(!empty($patient_episode_prescription_val['prescription_instruction'])){ ?>
							<tr style="border:none;">
								
								<td valign="top" colspan="5" style="border:none;">
									<?php echo "<span style='font-size:10px;line-height:4px;'><b>INSTRUCTIONS:</b> ".$patient_episode_prescription_val['prescription_instruction']."</span>"; ?>
								</td>
							</tr>
							<?php } ?>
						
						</table>
					</td>
					</tr>
					<?php } //end while ?>
					</tbody>
		</table>
<?php } ?>
	    </div>