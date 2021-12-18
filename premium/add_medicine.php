<?php
ob_start();
error_reporting(0); 
session_start();


$admin_id = $_SESSION['user_id'];
$patient_id = $_SESSION['patient_id'];

if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

$medid=$_GET['medid'];
$patientid=$_GET['patientid'];


function get_regional_language($dst) 
{
	$state = strtolower($dst);
	$marathi_states = array('maharashtra');
	$kannada_states = array('karnataka');
	$tamil_states = array('tamil nadu', 'tamilnadu', 'pondicherry', 'puducherry');
	$gujarati_states = array('gujarat', 'gujarath', 'gujrat', 'gujrath', 'daman', 'diu');
	$telugu_states = array('andhra pradesh', 'telangana', 'andhra', 'andhrapradesh');
	$kerala_states = array('kerala');
									
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
	} else if(in_array($state, $kerala_states)) {
		$regional_language = "malayalam";
	} else {
		$regional_language = "hindi";	
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
$get_doc_details = mysqlSelect("doc_state","referal","ref_id='".$admin_id."'","","","","");
$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
$regional_lang = get_regional_language($get_doc_details[0]['doc_state']);	
//$get_doc_lang = get_doc_regional_language($regional_lang);
							
if(isset($medid) && !empty($medid)){

$param = explode("-", $medid);
//echo $param[0];
//echo $param[1];
	if(is_numeric($param[0]) == false && $param[0] != "I"){
				
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=time();
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$param[0];
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
		
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";
		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = $insert_medicine; //Get Frequent Medicine Id
	}
	else if($param[0] == "I" && is_numeric($param[1])){
	
	$getMedicine= mysqlSelect("pharma_brand,pharma_generic","pharma_products","pp_id='".$param[1]."'","","","","");
		$chkFreqMedicine= mysqlSelect("*","doctor_frequent_medicine","pp_id='".$param[1]."' and doc_id = '".$admin_id."' and doc_type='1'","","","","");
		
			

		if($chkFreqMedicine==true)
		{
			if(is_numeric($chkFreqMedicine[0]['med_duration'])){
			$med_duration = $chkFreqMedicine[0]['med_duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $chkFreqMedicine[0]['med_duration'], $matches);
				$med_duration = $matches[1];
			}
			
			$arrFileds_freq[]='med_frequency';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency'];
			$arrFileds_freq[]='med_timing';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_timing'];
			$arrFileds_freq[]='med_frequency_morning';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_morning'];
			$arrFileds_freq[]='med_frequency_noon';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_noon'];
			$arrFileds_freq[]='med_frequency_night';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_night'];
			
			$arrFileds_freq[]='med_duration';
			$arrValues_freq[]=$med_duration;
			$arrFileds_freq[]='med_duration_type';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_duration_type'];
			$arrFileds_freq[]='other_instruction';
			$arrValues_freq[]=$chkFreqMedicine[0]['prescription_instruction'];
		}
		
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$param[1];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_brand'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_generic'];
		
		/*$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]="0";
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]="0";
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]="0";
		
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]="Day";
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]="0";
		*/
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = $insert_medicine; //Get Frequent Medicine Id
		
	}
	else if(is_numeric($param[0]) && isset($_GET['productid']))
	{
		//echo "How??";
		
		$getFreqCount= mysqlSelect("*","doc_patient_episode_prescriptions","pp_id='".$_GET['productid']."' and doc_id ='".$admin_id."'","episode_prescription_id desc","","","");
		
		if(is_numeric($getFreqCount[0]['duration'])){
			$med_duration = $getFreqCount[0]['duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $getFreqCount[0]['duration'], $matches);
				$med_duration = $matches[1];
			}
			
		if(!empty($getFreqCount[0]['pp_id']))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$getFreqCount[0]['pp_id'];
		}
			
		
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['prescription_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['prescription_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$getFreqCount[0]['prescription_frequency'];
		
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_noon'];
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_night'];
		
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$getFreqCount[0]['timing'];
		
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$med_duration;
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$getFreqCount[0]['med_duration_type'];
		$arrFileds_freq[]='other_instruction';
		$arrValues_freq[]=$getFreqCount[0]['prescription_instruction'];
		
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
			
			
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		
	}
	else
	{
		$getFreqCount= mysqlSelect("*","doctor_frequent_medicine","freq_medicine_id='".$param[0]."' and doc_type='1'","","","","");
		
		if(is_numeric($getFreqCount[0]['med_duration'])){
			$med_duration = $getFreqCount[0]['med_duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $getFreqCount[0]['med_duration'], $matches);
				$med_duration = $matches[1];
			}
			
		if(!empty($getFreqCount[0]['pp_id']))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$getFreqCount[0]['pp_id'];
		}
		
		
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['med_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['med_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency'];
		
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_noon'];
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_night'];
		
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$getFreqCount[0]['med_timing'];
		
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$med_duration;
		
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$getFreqCount[0]['med_duration_type'];
		$arrFileds_freq[]='other_instruction';
		$arrValues_freq[]=$getFreqCount[0]['prescription_instruction'];
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		$freq_id = $insert_medicine; //Get Frequent Medicine Id
	}

}

if(isset($_GET['editmedid']) && !empty($_GET['editmedid'])){

$param = explode("-", $_GET['editmedid']);
//echo $param[0];
//echo $param[1];
	if(is_numeric($param[0]) == false && $param[0] != "I"){
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=time();
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$param[0];
		
		if(!empty($admin_id))
		{
				$arrFileds_freq[]='doc_id';
				$arrValues_freq[]=$admin_id;
		}
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds_freq[]='episode_id';
			$arrValues_freq[]=$_GET['episodeid'];
		}
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
	}
	else if($param[0] == "I" && is_numeric($param[1])){
	
	$getMedicine= mysqlSelect("pharma_brand,pharma_generic","pharma_products","pp_id='".$param[1]."'","","","","");
		$chkFreqMedicine= mysqlSelect("*","doctor_frequent_medicine","pp_id='".$param[1]."' and doc_id = '".$admin_id."' and doc_type='1'","","","","");
		
				
		if($chkFreqMedicine==true)
		{
			if(is_numeric($chkFreqMedicine[0]['med_duration'])){
			$med_duration = $chkFreqMedicine[0]['med_duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $chkFreqMedicine[0]['med_duration'], $matches);
				$med_duration = $matches[1];
			}
			
			$arrFileds_freq[]='prescription_frequency';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency'];
			$arrFileds_freq[]='timing';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_timing'];
			
			$arrFileds_freq[]='med_frequency_morning';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_morning'];
			$arrFileds_freq[]='med_frequency_noon';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_noon'];
			$arrFileds_freq[]='med_frequency_night';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_frequency_night'];
			
			$arrFileds_freq[]='duration';
			$arrValues_freq[]=$med_duration;
			$arrFileds_freq[]='med_duration_type';
			$arrValues_freq[]=$chkFreqMedicine[0]['med_duration_type'];
			$arrFileds_freq[]='prescription_instruction';
			$arrValues_freq[]=$chkFreqMedicine[0]['prescription_instruction'];
			
		}
				
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$param[1];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_brand'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$getMedicine[0]['pharma_generic'];
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		$arrFileds_freq[]='episode_id';
		$arrValues_freq[]=$_GET['episodeid'];
		$arrFileds_freq[]='doc_id';
		$arrValues_freq[]=$admin_id;
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
	}
	else if(is_numeric($param[0]) && isset($_GET['productid']))
	{
		
		
		$getFreqCount= mysqlSelect("*","doc_patient_episode_prescriptions","pp_id='".$_GET['productid']."' and doc_id ='".$admin_id."'","episode_prescription_id desc","","","");
		
			if(is_numeric($getFreqCount[0]['duration'])){
			$med_duration = $getFreqCount[0]['duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $getFreqCount[0]['duration'], $matches);
				$med_duration = $matches[1];
			}
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$admin_id;
		}
			
		
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['med_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['med_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$getFreqCount[0]['med_timing'];
			
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_noon'];
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_night'];
		
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$med_duration;
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$getFreqCount[0]['med_duration_type'];
		$arrFileds_freq[]='prescription_instruction';
		$arrValues_freq[]=$getFreqCount[0]['prescription_instruction'];
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds_freq[]='episode_id';
			$arrValues_freq[]=$_GET['episodeid'];
		}
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
		
		
		
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
	}
	else
	{
		$getFreqCount= mysqlSelect("*","doctor_frequent_medicine","freq_medicine_id='".$param[0]."' and doc_id ='".$admin_id."' and doc_type='1'","","","","");
		
			if(is_numeric($getFreqCount[0]['med_duration'])){
			$med_duration = $getFreqCount[0]['med_duration'];
			}
			else
			{
				preg_match("/(\\d+)([a-zA-Z]+)/", $getFreqCount[0]['med_duration'], $matches);
				$med_duration = $matches[1];
			}
			
		if(!empty($getFreqCount[0]['pp_id']))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$getFreqCount[0]['pp_id'];
		}
		
		
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$getFreqCount[0]['med_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$getFreqCount[0]['med_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$getFreqCount[0]['med_timing'];
		
		
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_noon'];
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$getFreqCount[0]['med_frequency_night'];
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$med_duration;
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$getFreqCount[0]['med_duration_type'];
		$arrFileds_freq[]='prescription_instruction';
		$arrValues_freq[]=$getFreqCount[0]['prescription_instruction'];
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds_freq[]='episode_id';
			$arrValues_freq[]=$_GET['episodeid'];
		}
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}
		
		
		
		$arrFileds_freq[]='prescription_date_time';
		$arrValues_freq[]=$curDate;
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
	}

}

if(isset($_GET['updatefreqmedid']) && !empty($_GET['updatefreqmedid'])){
	
	if(isset($_GET['tradename']))
	{
				$arrFileds_freq[]='med_trade_name';
				$arrValues_freq[]=$_GET['tradename'];
	}
	if(isset($_GET['genericname']))
	{
				$arrFileds_freq[]='med_generic_name';
				$arrValues_freq[]=$_GET['genericname'];
	}
	if(isset($_GET['frequency']))
	{
				$arrFileds_freq[]='med_frequency';
				$arrValues_freq[]=$_GET['frequency'];
	}
	if(isset($_GET['medtiming']))
	{
				$arrFileds_freq[]='med_timing';
				$arrValues_freq[]=$_GET['medtiming'];
	}
	if(isset($_GET['duration']))
	{
				$arrFileds_freq[]='med_duration';
				$arrValues_freq[]=$_GET['duration'];
	}
	
	if(isset($_GET['durationType']))
	{
				$arrFileds_freq[]='med_duration_type';
				$arrValues_freq[]=$_GET['durationType'];
	}
	if(isset($_GET['FreqMorning']))
	{
				$arrFileds_freq[]='med_frequency_morning';
				$arrValues_freq[]=$_GET['FreqMorning'];
	}
	if(isset($_GET['FreqAfternoon']))
	{
				$arrFileds_freq[]='med_frequency_noon';
				$arrValues_freq[]=$_GET['FreqAfternoon'];
	}
	if(isset($_GET['FreqNight']))
	{
				$arrFileds_freq[]='med_frequency_night';
				$arrValues_freq[]=$_GET['FreqNight'];
	}
	if(isset($_GET['instructions']))
	{
				$arrFileds_freq[]='other_instruction';
				$arrValues_freq[]=addslashes($_GET['instructions']);
	}
		
	$update_medicine=mysqlUpdate('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq,"temp_freq_id = '".$_GET['updatefreqmedid']."'");

}

if(isset($_GET['editfreqmedid']) && !empty($_GET['editfreqmedid'])){
	
	if(isset($_GET['tradename']))
	{
				$arrFileds_freq[]='prescription_trade_name';
				$arrValues_freq[]=$_GET['tradename'];
	}
	if(isset($_GET['genericname']))
	{
				$arrFileds_freq[]='prescription_generic_name';
				$arrValues_freq[]=$_GET['genericname'];
	}
	if(isset($_GET['frequency']))
	{
				$arrFileds_freq[]='prescription_frequency';
				$arrValues_freq[]=$_GET['frequency'];
	}
	if(isset($_GET['medtiming']))
	{
				$arrFileds_freq[]='timing';
				$arrValues_freq[]=$_GET['medtiming'];
	}
	if(isset($_GET['duration']))
	{
				$arrFileds_freq[]='duration';
				$arrValues_freq[]=$_GET['duration'];
	}
	if(isset($_GET['durationType']))
	{
				$arrFileds_freq[]='med_duration_type';
				$arrValues_freq[]=$_GET['durationType'];
	}
	if(isset($_GET['FreqMorning']))
	{
				$arrFileds_freq[]='med_frequency_morning';
				$arrValues_freq[]=$_GET['FreqMorning'];
	}
	if(isset($_GET['FreqAfternoon']))
	{
				$arrFileds_freq[]='med_frequency_noon';
				$arrValues_freq[]=$_GET['FreqAfternoon'];
	}
	if(isset($_GET['FreqNight']))
	{
				$arrFileds_freq[]='med_frequency_night';
				$arrValues_freq[]=$_GET['FreqNight'];
	}
	if(isset($_GET['instructions']))
	{
				$arrFileds_freq[]='prescription_instruction';
				$arrValues_freq[]=addslashes($_GET['instructions']);
	}
		
	$update_medicine=mysqlUpdate('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq,"episode_prescription_id = '".$_GET['editfreqmedid']."'");

}

if(isset($_GET['delprescid']) && !empty($_GET['delprescid'])){
	mysqlDelete('doctor_temp_frequent_medicine',"temp_freq_id = '".$_GET['delprescid']."'");
	
}
if(isset($_GET['clearall']) && !empty($_GET['clearall'])){
	mysqlDelete('doctor_temp_frequent_medicine',"doc_id='".$admin_id."' and doc_type ='1' and status='1'");
}


if(isset($_GET['deleditprescid']) && !empty($_GET['deleditprescid'])){
	mysqlDelete('doc_patient_episode_prescriptions',"episode_prescription_id = '".$_GET['deleditprescid']."'");
	
}

if(isset($_GET['prevprescid']) && !empty($_GET['prevprescid'])){
	$getTemplateDetails= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$_GET['prevprescid']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
		if(is_numeric($value['duration'])){
			$med_duration = $value['duration'];
		}
		else
		{
			preg_match("/(\\d+)([a-zA-Z]+)/", $value['duration'], $matches);
			$med_duration = $matches[1];
		}
			
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$value['timing'];
		
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$med_duration;
				
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$value['med_duration_type'];

		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$value['med_frequency_morning'];
	
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$value['med_frequency_noon'];
	
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$value['med_frequency_night'];
				
		$arrFileds_freq[]='other_instruction';
		$arrValues_freq[]=addslashes($value['prescription_instruction']);
				
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}			
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}
if(isset($_GET['editprevprescid']) && !empty($_GET['editprevprescid'])){
	$getTemplateDetails= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$_GET['editprevprescid']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{	
		if(is_numeric($value['duration'])){
			$med_duration = $value['duration'];
		}
		else
		{
			preg_match("/(\\d+)([a-zA-Z]+)/", $value['duration'], $matches);
			$med_duration = $matches[1];
		}
		
		$arrFileds_freq = array();
		$arrValues_freq = array();
		
		if(!empty($value['pp_id']))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$value['pp_id'];
		}	
		
		
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$value['timing'];
		
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$med_duration;
		
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$value['med_duration_type'];

		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$value['med_frequency_morning'];
	
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$value['med_frequency_noon'];
	
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$value['med_frequency_night'];
				
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		
		$arrFileds_freq[]='prescription_instruction';
		$arrValues_freq[]=addslashes($value['prescription_instruction']);
		
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds_freq[]='episode_id';
			$arrValues_freq[]=$_GET['episodeid'];
		}	
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}	
		
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}
if(isset($_GET['loadtemplate']) && !empty($_GET['loadtemplate'])){
	$getTemplateDetails= mysqlSelect("*","doc_medicine_prescription_template_details","template_id='".$_GET['loadtemplate']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{		
		if(is_numeric($value['prescription_duration'])){
			$med_duration = $value['prescription_duration'];
		}
		else
		{
			preg_match("/(\\d+)([a-zA-Z]+)/", $value['prescription_duration'], $matches);
			$med_duration = $matches[1];
		}
		
		
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		if(!empty($value['pp_id']))
		{
			$arrFileds_freq[]='pp_id';
			$arrValues_freq[]=$value['pp_id'];
		}		
			
		
		$arrFileds_freq[]='med_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='med_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='med_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='med_timing';
		$arrValues_freq[]=$value['prescription_timing'];
		
		
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$value['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$value['med_frequency_noon'];		
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$value['med_frequency_night'];
		
		$arrFileds_freq[]='med_duration';
		$arrValues_freq[]=$med_duration;
		
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$value['med_duration_type'];
		$arrFileds_freq[]='other_instruction';
		$arrValues_freq[]=$value['other_instruction'];
		
		
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}	
		
		
		$arrFileds_freq[]='doc_type';
		$arrValues_freq[]="1";
		$arrFileds_freq[]='status';
		$arrValues_freq[]="1";

		$insert_medicine=mysqlInsert('doctor_temp_frequent_medicine',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}

if(isset($_GET['editloadtemplate']) && !empty($_GET['editloadtemplate'])){
	$getTemplateDetails= mysqlSelect("*","doc_medicine_prescription_template_details","template_id='".$_GET['editloadtemplate']."'","","","","");
while(list($key,$value) = each($getTemplateDetails))
	{	
		if(is_numeric($value['prescription_duration'])){
			$med_duration = $value['prescription_duration'];
		}
		else
		{
			preg_match("/(\\d+)([a-zA-Z]+)/", $value['prescription_duration'], $matches);
			$med_duration = $matches[1];
		}
		
			$arrFileds_freq=array();
			$arrValues_freq=array();
			
		$arrFileds_freq[]='pp_id';
		$arrValues_freq[]=$value['pp_id'];
		$arrFileds_freq[]='prescription_trade_name';
		$arrValues_freq[]=$value['prescription_trade_name'];
		$arrFileds_freq[]='prescription_generic_name';
		$arrValues_freq[]=$value['prescription_generic_name'];
		$arrFileds_freq[]='prescription_frequency';
		$arrValues_freq[]=$value['prescription_frequency'];
		$arrFileds_freq[]='timing';
		$arrValues_freq[]=$value['prescription_timing'];
				
		$arrFileds_freq[]='med_frequency_morning';
		$arrValues_freq[]=$value['med_frequency_morning'];
		$arrFileds_freq[]='med_frequency_noon';
		$arrValues_freq[]=$value['med_frequency_noon'];		
		$arrFileds_freq[]='med_frequency_night';
		$arrValues_freq[]=$value['med_frequency_night'];
		
		$arrFileds_freq[]='duration';
		$arrValues_freq[]=$med_duration;
		
		$arrFileds_freq[]='med_duration_type';
		$arrValues_freq[]=$value['med_duration_type'];
		$arrFileds_freq[]='prescription_instruction';
		$arrValues_freq[]=$value['other_instruction'];
		
		$arrFileds_freq[]='prescription_template';
		$arrValues_freq[]=$_SESSION['prescription_template'];
		
		if(!empty($_GET['episodeid']))
		{
			$arrFileds_freq[]='episode_id';
			$arrValues_freq[]=$_GET['episodeid'];
		}	
		if(!empty($admin_id))
		{
			$arrFileds_freq[]='doc_id';
			$arrValues_freq[]=$admin_id;
		}	
		
		
	
		$insert_medicine=mysqlInsert('doc_patient_episode_prescriptions',$arrFileds_freq,$arrValues_freq);
		
		
	}
	
}
							
if(isset($_GET['delpresctemp']))
{
	//Delete perticular symptoms from table 'doc_patient_examination_active'
	mysqlDelete('doc_patient_episode_prescription_templates',"md5(template_id)='".$_GET['delpresctemp']."'");
	mysqlDelete('doc_patient_episode_prescription_template_details',"md5(template_id)='".$_GET['delpresctemp']."'");
}

if(isset($_GET['gettemplateval']))
{
	$last_five_templates = mysqlSelect("*","doc_patient_episode_prescription_templates","admin_id='".$admin_id."'","template_id desc","","","10");
	if($_GET['gettempEdit']==1)
	{
		while(list($key_temp, $value_temp) = each($last_five_templates)){
		
		echo "<span class='tag label label-primary m-l' >".$value_temp['template_name']."<a data-role='remove' class='text-white m-l del_prescription_template' data-template-id='".md5($value_temp['template_id'])."'>x</a></span>";
		 }
	}
	else
	{
		
		while(list($key_temp, $value_temp) = each($last_five_templates)){
		
		echo "<a class='btn btn-xs btn-white m-l load-template' title='".$value_temp['template_name']."' data-template-id='".$value_temp['template_id']."' data-edit-status='0' data-patient-id='".$_GET['patientid']."' ><code> ".substr($value_temp['template_name'],0,10)."</code></a>";
		 }
	}
}
							
							
if(isset($medid) || isset($_GET['loadtemplate']) || isset($_GET['prevprescid']) || isset($_GET['updatefreqmedid']))
{
//$getTmplate= mysqlSelect("*","doc_medicine_prescription_template_details","doc_id='".$admin_id."' and patient_id='".$patientid."' and status=1","","","","");
$getTmplate= mysqlSelect("*","doctor_temp_frequent_medicine","doc_id='".$admin_id."' and doc_type='1' and status='1'","temp_freq_id asc","","","");
		

?>
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddEpisode" id="frmAddEpisode">
										<input type="hidden" name="patient_id" value="<?php echo $patientid; ?>">
							
										
									<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>	
									<?php if($checkSetting[0]['prescription_template']==0){ ?>
									<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="82%">
																			<thead>
																				<th style="width:30px;">Medicine</th>
																				<th style="width:30px;">Generic Name</th>
																				<!--<th>Dosage</th>
																				<th>Route</th>-->
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>-->
																				<th></th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { 
																			$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['med_timing']."'","","","","");
																			$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patientid."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
																			
																			?>
																			<tr id="medRow<?php echo $TempList['temp_freq_id'];?>">
																				<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> <input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="0" required data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_trade_name'];?>" placeholder="Medicine" style="width:280px;"></td>
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="0" required data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_generic_name'];?>" placeholder="Generic Name" style="width:290px;"></td>
																				<td><input type="text" class="form-control tagName frequency" name="prescription_frequency[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_frequency'];?>" placeholder="Frequency" style="width:70px;"></td>
																				<td>
																				<select name="slctTiming" class="form-control medtiming" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" data-episode-id="0" style="width:160px;" >
																				<?php if($get_Timing>0){
																				?>
																				<option value="<?php echo $get_Timing[0]['language_id']; ?>" selected><?php echo $get_Timing[0]['english']; ?></option>
																				<?php
																				
																				while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				} else { ?>
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				}?>
																				</select>
																				
																				<td><input type="text" class="form-control tagName duration" name="prescription_duration[]" data-episode-id="0"  data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_duration'];?>" placeholder="Duration" style="width:90px;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td class="text-center"><a class="del_medicine" data-medicine-id="<?php echo $TempList['temp_freq_id'];?>"><img src="<?php echo HOST_MAIN_URL; ?>premium/trash.png" width="15"/></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
										<?php } else if($checkSetting[0]['prescription_template']==1){ ?>
										<table  cellpadding="2" cellspacing="2" border="0" class="table table-responsive" width="85%" style="border:none;">
																			<thead>
																				<!--<th style="width:2px;">S.No.</th>	-->
																				<th style="width:360px;background-color:#1a2530; color:#fff; text-align:center;">Medicine <br><?php echo $get_doc_lang[0];?></th>
																				<th style="width:130px;background-color:#1a2530; color:#fff; text-align:center;"><span style="float:left;">Morning<br><?php echo $get_doc_lang[1];?></span> <span style="float:left;margin-left:20px;">Afternoon<br><?php echo $get_doc_lang[2];?></span> <span style="float:left;margin-left:20px;">Night<br><?php echo $get_doc_lang[3];?></span></th>
																				<th style="width:110px;background-color:#1a2530; color:#fff; text-align:center;">Duration<br><?php echo $get_doc_lang[4];?></th>
																				<th style="width:90px;background-color:#1a2530; color:#fff; text-align:center; " >Timing<br><?php echo $get_doc_lang[5];?></th>
																				<th style="width:10px;background-color:#1a2530; color:#fff;"></th>
																			
																			</thead>
																			
																			<tbody>
																			<?php 
																			$medicineCount = COUNT($getTmplate);
																			
																			while(list($TempKey, $TempList) = each($getTmplate)) { 
																			$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['med_timing']."'","","","","");
																			$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patientid."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
																			$sl_num = $TempKey +1;
																			
																			if($TempKey%2==0){
																				$rowColor = "style='background-color:#9ea8bd;border:none;'";
																			}
																			else
																			{
																				$rowColor = "style='background-color:#1a2530;border:none;'";
																			}
																			?>
																			<tr id="medRow<?php echo $TempList['temp_freq_id'];?>" style="border-top:none; border-bottom:none;">
																				<!--<td style="width:2px;" rowspan="2"><?php echo $sl_num; ?></td>-->
																				<td <?php echo $rowColor; ?>><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?>
																				<input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" data-episode-id="0" required data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_trade_name'];?>" placeholder="Medicine" style="width:100%;"></td>
																				<td colspan="1" <?php echo $rowColor; ?>>
																				<select class="form-control slctFreqMorning" name="slctFreqMorning[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left;width:75px;"><option value="0" <?php if($TempList['med_frequency_morning']==0 || empty($TempList['med_frequency_morning'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_morning']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_morning']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_morning']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_morning']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_morning']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_morning']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_morning']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_morning']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_morning']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_morning']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_morning']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select>
																				<select class="form-control slctFreqAfternoon" name="slctFreqAfternoon[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left;width:75px;"><option value="0" <?php if($TempList['med_frequency_noon']==0 || empty($TempList['med_frequency_noon'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_noon']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_noon']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_noon']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_noon']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_noon']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_noon']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_noon']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_noon']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_noon']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_noon']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_noon']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select>
																				<select class="form-control slctFreqNight" name="slctFreqNight[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left;width:75px;"><option value="0" <?php if($TempList['med_frequency_night']==0 || empty($TempList['med_frequency_night'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_night']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_night']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_night']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_night']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_night']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_night']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_night']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_night']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_night']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_night']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_night']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select></td>
																				<td <?php echo $rowColor; ?>>
																				<select class="form-control duration" name="prescription_duration[]" data-episode-id="0"  data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left; width:70px;"><?php for($i=0;$i<=30;$i++){ ?><option value="<?php echo $i; ?>" <?php if($TempList['med_duration']==$i){ echo "selected"; } ?>><?php echo $i; ?></option><?php } ?></select>
																				<select class="form-control duration_type" name="slctDurationType[]" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="width:100px;"><option value="" <?php if($TempList['med_duration_type']==" "){ echo "selected"; } ?>>Select</option><option value="Days" <?php if($TempList['med_duration_type']=="Days"){ echo "selected"; } ?>>Days</option><option value="Weeks" <?php if($TempList['med_duration_type']=="Weeks"){ echo "selected"; } ?>>Weeks</option><option value="Months" <?php if($TempList['med_duration_type']=="Months"){ echo "selected"; } ?>>Months</option></select>
																				</td>
																				<td <?php echo $rowColor; ?>>
																				<select class="form-control medtiming" name="slctTiming[]" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" data-episode-id="0" style="width:100%;">
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>" <?php if($TempList['med_timing']==$value_lng['language_id']){ echo "selected"; }?>><?php echo $value_lng['english']; ?></option>
																				<?php } ?>
																				</select>
																				</td>
																				
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td <?php echo $rowColor; ?> rowspan="2" class="text-center"><a class="del_medicine" data-medicine-id="<?php echo $TempList['temp_freq_id'];?>"><img src="<?php echo HOST_MAIN_URL; ?>premium/remove-icon.png" width="20"/></a> </td>
																			</tr>
																			<tr id="medRow1<?php echo $TempList['temp_freq_id'];?>">
																				<td <?php echo $rowColor; ?> ><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="0" required data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" value="<?php echo $TempList['med_generic_name'];?>" placeholder="Generic Name" style="width:90%;float:right"></td>
																				<td <?php echo $rowColor; ?> class="fields" colspan="5"><textarea class="form-control instructions" name="prescription_other_instruct[]" id="prescription_other_instruct" data-episode-id="0" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" placeholder="Instructions" style="width:100%;" rows="1"><?php echo $TempList['other_instruction'];?></textarea></td>
																			</tr>
																			<?php 
																			
																			} 
																			?>
																			</tbody>
																			
																		</table>								
										
										<?php } ?>
						</form>
<?php } 
if(isset($_GET['editmedid']) || isset($_GET['editloadtemplate']) || isset($_GET['editprevprescid']) || isset($_GET['editfreqmedid']))
{
	$getEpisode= mysqlSelect("episode_id,prescription_template","doc_patient_episodes","episode_id='".$_GET['episodeid']."'","","","","");
$getTmplate= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$_GET['episodeid']."'","episode_prescription_id asc","","","");
								if(COUNT($getTmplate)>0){
								?>
														<a class="btn btn-xs btn-white pull-right clear_all"><i class="fa fa-trash"></i> Clear All</a>			
														
														<?php if($getEpisode[0]['prescription_template']==0){ ?>
														<table  cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="82%">
																	<thead>
																				<th style="width:330px;">Medicine</th>
																				<th style="width:330px;">Generic Name</th>
																				<th style="width:30px;">Dosage Frequency</th>
																				<th style="width:30px;">Timing</th>
																				<th style="width:30px;">Duration</th>
																				<!--<th>Note</th>-->
																				<th></th>
																			</thead>
																			
																			<tbody>
																			<?php foreach($getTmplate as $TempList) { 
																			$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
																			$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."' and doc_type = '1'","","","","");
																			
																			?>
																			<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>">
																				<td><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?> 
																				<input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" required="required" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:100%;"></td>
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" required="required" value="<?php echo $TempList['prescription_generic_name'];?>" data-episode-id="<?php echo $_GET['episodeid']; ?>" placeholder="Generic Name" style="width:100%;"></td>
																				<td><input type="text" class="form-control tagName frequency" name="prescription_frequency[]"  data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_frequency'];?>" data-episode-id="<?php echo $_GET['episodeid']; ?>" placeholder="Frequency" style="width:70px;"></td>
																				<td>
																				<select name="slctTiming" class="form-control medtiming" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" style="width:160px;" >
																				<?php if($get_Timing>0){
																				?>
																				<option value="<?php echo $get_Timing[0]['language_id']; ?>" selected><?php echo $get_Timing[0]['english']; ?></option>
																				<?php
																				
																				while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				} else { ?>
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>"><?php echo $value_lng['english']; ?></option>
																				<?php } 
																				}?>
																				</select>
																				
																				<td><input type="text" class="form-control tagName duration" name="prescription_duration[]" data-episode-id="<?php echo $_GET['episodeid']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['duration'];?>" placeholder="Duration" style="width:90px;"></td>
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td class="text-center"><a class="edit_del_medicine" data-medicine-id="<?php echo $TempList['episode_prescription_id'];?>"><img src="<?php echo HOST_MAIN_URL; ?>premium/remove-icon.png" width="20"/></a> </td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
														<?php } else if($getEpisode[0]['prescription_template']==1){ ?>
														
														<table  cellpadding="2" cellspacing="2" border="0" class="table table-responsive" width="85%" style="border:none;">
																			<thead>
																				<!--<th style="width:2px;">S.No.</th>-->
																				<th style="width:360px;background-color:#1a2530; color:#fff; text-align:center;">Medicine <br><?php echo $get_doc_lang[0];?></th>
																				<th style="width:130px;background-color:#1a2530; color:#fff; text-align:center;"><span style="float:left;">Morning<br><?php echo $get_doc_lang[1];?></span> <span style="float:left;margin-left:20px;">Afternoon<br><?php echo $get_doc_lang[2];?></span> <span style="float:left;margin-left:20px;">Night<br><?php echo $get_doc_lang[3];?></span></th>
																				<th style="width:110px;background-color:#1a2530; color:#fff; text-align:center;">Duration<br><?php echo $get_doc_lang[4];?></th>
																				<th style="width:90px;background-color:#1a2530; color:#fff; text-align:center; " >Timing<br><?php echo $get_doc_lang[5];?></th>
																				<th style="width:10px;background-color:#1a2530; color:#fff;"></th>
																			</thead>
																			
																			<tbody>
																			<?php while(list($TempKey, $TempList) = each($getTmplate)) { 
																			$get_Timing = mysqlSelect("language_id,english","doc_medicine_timing_language","language_id='".$TempList['timing']."'","","","","");
																			$get_Timing_list = mysqlSelect("language_id,english","doc_medicine_timing_language","","priority ASC","","","");
																			$check_pharma = mysqlSelect("generic_id","pharma_products","pp_id='".$TempList['pp_id']."'","","","","");
																			$check_allergy = mysqlSelect("generic_id","doc_patient_drug_allergy_active","patient_id='".$patient_id."' and generic_id='".$check_pharma[0]['generic_id']."'","","","","");
																			
																			$sl_num = $TempKey +1;
																			if($TempKey%2==0){
																				$rowColor = "style='background-color:#9ea8bd;border:none;'";
																			}
																			else
																			{
																				$rowColor = "style='background-color:#1a2530;border:none;'";
																			}
																			?>
																			<tr id="medRow<?php echo $TempList['episode_prescription_id'];?>" style="border-top:none; border-bottom:none;">
																				<!--<td style="width:2px;" rowspan="2"><?php echo $sl_num; ?></td>-->
																				<td <?php echo $rowColor; ?>><input type="hidden" name="product_id[]" value="<?php echo $TempList['pp_id'];?>"/><?php if(COUNT($check_allergy)>0) { ?><img src="danger_icon.gif" width="20" /><?php } ?>
																				<input type="text" class="form-control tagName tradename" name="prescription_trade_name[]" required="required" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_trade_name'];?>" placeholder="Medicine" style="width:100%;"></td>
																				<td colspan="1" <?php echo $rowColor; ?>>
																				<select class="form-control slctFreqMorning" name="slctFreqMorning[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;"><option value="0" <?php if($TempList['med_frequency_morning']==0 || empty($TempList['med_frequency_morning'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_morning']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_morning']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_morning']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_morning']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_morning']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_morning']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_morning']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_morning']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_morning']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_morning']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_morning']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select>
																				<select class="form-control slctFreqAfternoon" name="slctFreqAfternoon[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;"><option value="0" <?php if($TempList['med_frequency_noon']==0 || empty($TempList['med_frequency_noon'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_noon']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_noon']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_noon']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_noon']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_noon']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_noon']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_noon']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_noon']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_noon']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_noon']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_noon']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select>
																				<select class="form-control slctFreqNight" name="slctFreqNight[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="float:left; width:70px;"><option value="0" <?php if($TempList['med_frequency_night']==0 || empty($TempList['med_frequency_night'])){ echo "selected"; } ?>>0</option><option value="0.5" <?php if($TempList['med_frequency_night']==0.5){ echo "selected"; } ?>>0.5</option><option value="1" <?php if($TempList['med_frequency_night']==1){ echo "selected"; } ?>>1</option><option value="2" <?php if($TempList['med_frequency_night']==2){ echo "selected"; } ?>>2</option><option value="3" <?php if($TempList['med_frequency_night']==3){ echo "selected"; } ?>>3</option><option value="4" <?php if($TempList['med_frequency_night']==4){ echo "selected"; } ?>>4</option><option value="5" <?php if($TempList['med_frequency_night']==5){ echo "selected"; } ?>>5</option><option value="2.5 ml" <?php if($TempList['med_frequency_night']=="2.5 ml"){ echo "selected"; } ?>>2.5 ml</option><option value="5 ml" <?php if($TempList['med_frequency_night']=="5 ml"){ echo "selected"; } ?>>5 ml</option><option value="10 ml" <?php if($TempList['med_frequency_night']=="10 ml"){ echo "selected"; } ?>>10 ml</option><option value="20 ml" <?php if($TempList['med_frequency_night']=="20 ml"){ echo "selected"; } ?>>20 ml</option><option value="30 ml" <?php if($TempList['med_frequency_night']=="30 ml"){ echo "selected"; } ?>>30 ml</option></select></td>
																				<td <?php echo $rowColor; ?>>
																				<select class="form-control duration" name="prescription_duration[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" data-freq-medicine-id="<?php echo $TempList['temp_freq_id'];?>" style="float:left; width:70px;"><option value="" <?php if($TempList['duration']==""){ echo "selected"; } ?>>Select</option><?php for($i=0;$i<=30;$i++){ ?><option value="<?php echo $i; ?>" <?php if($TempList['duration']==$i){ echo "selected"; } ?>><?php echo $i; ?></option><?php } ?></select>
																				<select class="form-control duration_type" name="slctDurationType[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="width:100px;"><option value="" <?php if(empty($TempList['med_duration_type'])){ echo "selected"; } ?>>Select</option><option value="Days" <?php if($TempList['med_duration_type']=="Days"){ echo "selected"; } ?>>Days</option><option value="Weeks" <?php if($TempList['med_duration_type']=="Weeks"){ echo "selected"; } ?>>Weeks</option><option value="Months" <?php if($TempList['med_duration_type']=="Months"){ echo "selected"; } ?>>Months</option></select>
																				</td>
																				<td <?php echo $rowColor; ?>>
																				<select class="form-control medtiming" name="slctTiming" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" style="width:100%;">
																				<option value="">Select</option>
																				<?php while(list($key_lng, $value_lng) = each($get_Timing_list)){ ?>
																				<option value="<?php echo $value_lng['language_id']; ?>" <?php if($TempList['timing']==$value_lng['language_id']){ echo "selected"; }?>><?php echo $value_lng['english']; ?></option>
																				<?php } ?>
																				</td>
																				
																				<!--<td><textarea name="prescription_instruction" id="prescription_instruction[]" placeholder="Note" style="width:100px;border:none;"></textarea></td>-->
																				<td <?php echo $rowColor; ?>  rowspan="2" class="text-center"><a valign="middle" title="Delete" class="edit_del_medicine" data-medicine-id="<?php echo $TempList['episode_prescription_id'];?>"><img src="<?php echo HOST_MAIN_URL; ?>premium/remove-icon.png" width="20"/></a> </td>
																			</tr>
																			<tr  <?php echo $rowColor; ?> id="medRow1<?php echo $TempList['episode_prescription_id'];?>" style="border-top:none; border-bottom:none;">
																				<td><input type="text" class="form-control tagName genericname" name="prescription_generic_name[]" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" required data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" value="<?php echo $TempList['prescription_generic_name'];?>" placeholder="Generic Name" style="width:90%;float:right"></td>
																				<td class="fields" colspan="5"><textarea class="form-control instructions" name="prescription_other_instruct[]" id="prescription_other_instruct[]" data-freq-medicine-id="<?php echo $TempList['episode_prescription_id'];?>" data-episode-id="<?php echo $edit_patient_episodes[0]['episode_id']; ?>" placeholder="Instructions" style="width:100%;" rows="1"><?php echo $TempList['prescription_instruction'];?></textarea></td>
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
								
														<?php	}
									}
}	?>