<?php
ob_start();
error_reporting(0); 

require_once("../../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

		
		//Prescription Details
		$doc_patient_episode_prescriptions = $objQuery->mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $_GET['episode'] ."' "," prescription_seq ASC","","","");

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
		
?>
<div cellpadding="0" cellspacing="0" border="0">
		<table>
				<thead>
					<tr>
						<th>Medicine</th>
						<th>Generic Name</th>
						<th>Dosage Frequency</th>
						<th colspan="2">Timing</th>
						<th>Duration</th>
					</tr>
				</thead>
					<tbody>
					<?php
						$regional_lang = get_regional_language($patient_state);
						while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
							{
							//Prescrption Timing iin different language
							$prescription_timing=$objQuery->mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");
							$presc_timing_english = $prescription_timing[0]['english']; //Timing in english
							//$presc_timing_regional = $prescription_timing[0][$regional_lang];
							$presc_timing_regional = get_timing_in_regional_language($regional_lang, $prescription_timing[0]['language_id']);

							//print_r($prescription_timing[0]);
					?>
						<tr>
							<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
							<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
							<td><?php echo $presc_timing_english; ?><br></td>
							<td><?php echo $presc_timing_regional; ?></td>
							<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
						</tr>
					<?php } //end while ?>
					</tbody>
		</table>
	    </div>