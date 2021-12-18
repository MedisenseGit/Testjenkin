<?php
ob_start();
session_start();
error_reporting(0);  

include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
include('short_url.php');
require_once("../classes/querymaker.class.php");
require_once("../DigitalOceanSpaces/src/upload_function.php");
$admin_id = $_SESSION['user_id'];
//Random Password Generator
function randomPassword() 
{
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) 
	{
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}

function createKey()
{
	//create a random key
	$strKey = md5(microtime());
	//check to make sure this key isnt already in use
	$resCheck = mysqli_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
	$arrCheck = mysqli_fetch_assoc($resCheck);
	if($arrCheck['count(*)'])
	{
		//key already in use
		return createKey();
	}
	else
	{
		//key is OK
		return $strKey;
	}
}

function hyphenize($string) 
{
	
    return 
    ## strtolower(
          preg_replace(
            array('#[\\s+]+#', '#[^A-Za-z0-9\. -]+#', '/\@^|(\.+)/'),
            array('-',''),
        ##     cleanString(
              urldecode($string)
        ##     )
        )
    ## )
    ;
}
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');

//$hostname="http://beta.referralio.com"; //For Beta version
$hostname=HOST_MAIN_URL;    //"https://medisensecrm.com/"; //For Prod version
//Image Compress functionality
$name = ''; $type = ''; $size = ''; $error = '';
function compress_image($source_url, $destination_url, $quality)
{

	$info = getimagesize($source_url);
	if ($info['mime'] == 'image/jpeg')
	$image = imagecreatefromjpeg($source_url);
	elseif ($info['mime'] == 'image/gif')
	$image = imagecreatefromgif($source_url);
	elseif ($info['mime'] == 'image/png')
	$image = imagecreatefrompng($source_url);
	imagejpeg($image, $destination_url, $quality);
	return $destination_url;
}
//Vital
if(isset($_POST['add_vitalDtl']))
{
	$child_id 	 = $_POST['patient_id'];
	$login_type  ='3';				// login type 1= Asha Worker, 2 = Parents
	$vital_date  = date('Y-m-d',strtotime($_POST['vital_date']));
	$heart_rate  = $_POST['heart_rate'];
	$respiratory_rate = $_POST['respiratory_rate'];
	$temperature = $_POST['temperature'];
	$dob 		 = date('Y-m-d',strtotime($_POST['date_birth']));
	$txtEDD 	 = date('Y-m-d',strtotime($_POST['edd']));
	$birth_date  = new DateTime($dob);
	$current_date= new DateTime($growth_date);
	$diff        = $birth_date->diff($current_date);
	$actualAge   = $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)";
	
	//Calculate Corrected Age(Current Date minus EDD)
	$expected_date  = new DateTime($txtEDD);
	$corrected_diff = $expected_date->diff($current_date);
	$correctedAge   = $corrected_diff->y . " years " . $corrected_diff->m . " months " . $corrected_diff->d . " day(s)";

	$arrFields1 = array();
	$arrValues1 = array();
	
	$arrFields1[]= 'vital_date';
	$arrValues1[]=  $vital_date;
	$arrFields1[]= 'heart_rate';
	$arrValues1[]=  $heart_rate;
	$arrFields1[]= 'respiratory_rate';
	$arrValues1[]=  $respiratory_rate;
	$arrFields1[]= 'temperature';
	$arrValues1[]=  $temperature;
	$arrFields1[]= 'child_id';
	$arrValues1[]=  $child_id;
	if(!empty($admin_id))
	{
		$arrFields1[]= 'user_id';
		$arrValues1[]=  $admin_id;
	}
	
	if(!empty($login_type))
	{
		$arrFields1[]= 'user_type';
		$arrValues1[]=  $login_type;
	}
	
	$arrFields1[]= 'created_date';
	$arrValues1[]=  $curDate;	
	$arrFields1[]= 'actual_age';
	$arrValues1[]=  $actualAge;
	$arrFields1[]= 'corrected_age';
	$arrValues1[]=  $correctedAge;
	
	$getChild 	 = mysqlSelect('child_name','child_tab',"child_id='".$child_id."'","","","","");
	
	$userDetails = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	
	$userName    =	$userDetails[0]['ref_name'];
	
	$getcount    = mysqlSelect('count(vital_date) AS NumberofVital','vital_signs',"child_id='".$child_id."' and user_id='".$admin_id."'  and vital_date='".$vital_date."'","","","","");
	if( $getcount[0]['NumberofVital'] > 0) 
	{
		$vitsl_sign_update	=	mysqlUpdate('vital_signs',$arrFields1,$arrValues1," child_id='".$child_id."' and user_id='".$admin_id."'  and vital_date='".$vital_date."'");
		
	}
	else 
	{
		$add_vitalsign_result	=	mysqlInsert('vital_signs',$arrFields1,$arrValues1);
		
	}

	header("Location:My-Patient-Details?p=".md5($child_id));
}

//Growth Chart
if(isset($_POST['add_growthDtl']))
{
	$child_id 		= $_POST['patient_id'];
	$login_type 	='3';				// login type 1= Asha Worker, 2 = Parents
	$growth_date 	= date('Y-m-d',strtotime($_POST['growth_date']));
	$height 		= $_POST['growth_height'];
	$weight 		= $_POST['growth_weight'];
	$head_circum 	= $_POST['growth_circum'];
	$measurement 	= $_POST['growth_measure'];
	$dob 			= date('Y-m-d',strtotime($_POST['date_birth']));
	$txtEDD 		= date('Y-m-d',strtotime($_POST['edd']));
	$birth_date     = new DateTime($dob);
	$current_date   = new DateTime($growth_date);
	$diff           = $birth_date->diff($current_date);
	$actualAge      = $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)";
	//Calculate Corrected Age(Current Date minus EDD)
	$expected_date   = new DateTime($txtEDD);
	$corrected_diff  = $expected_date->diff($current_date);
	$correctedAge    = $corrected_diff->y . " years " . $corrected_diff->m . " months " . $corrected_diff->d . " day(s)";

	$arrFields1 = array();
	$arrValues1 = array();
	$arrFields1[]= 'growth_date';
	$arrValues1[]=  $growth_date;
	$arrFields1[]= 'height';
	$arrValues1[]=  $height;
	$arrFields1[]= 'weight';
	$arrValues1[]=  $weight;
	$arrFields1[]= 'head_circum';
	$arrValues1[]=  $head_circum;
	$arrFields1[]= 'measurement';
	$arrValues1[]=  $measurement;
	$arrFields1[]= 'child_id';
	$arrValues1[]=  $child_id;
	$arrFields1[]= 'user_id';
	$arrValues1[]=  $admin_id;
	$arrFields1[]= 'created_date';
	$arrValues1[]=  $curDate;
	$arrFields1[]= 'actual_age';
	$arrValues1[]=  $actualAge;
	$arrFields1[]= 'corrected_age';
	$arrValues1[]=  $correctedAge;
	$arrFields1[]= 'user_type';
	$arrValues1[]=  $login_type;
	
	$getChild 	 = 	mysqlSelect('child_name','child_tab',"child_id='".$child_id."'","","","","");
	
	$userDetails = 	mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	
	$userName    =	$userDetails[0]['ref_name'];
	
	$getcount 	 = mysqlSelect('count(gc_id) AS NumberofGrowth','growth_chart',"child_id='".$child_id."' and user_id='".$admin_id."'  and growth_date='".
	
	$growth_date."'","","","","");
	if( $getcount[0]['NumberofGrowth'] > 0) 
	{
		$growth_chart_update=mysqlUpdate('growth_chart',$arrFields1,$arrValues1,"child_id='".$child_id."' and user_id='".$admin_id."'  and growth_date='".$growth_date."'");
	}
	else 
	{
		$add_growthchart_result=mysqlInsert('growth_chart',$arrFields1,$arrValues1);
	}
	header("Location:My-Patient-Details?p=".md5($child_id));
}

if(isset($_POST['update_vaccine_details']))
{
	$vacCount= $_POST['vacCount'];
	for($i=1;$i<=$vacCount;$i++)
	{
		
		$child_vaccine_id	=	$_POST['vaccineId'.$i];
		$child_id			=	$_POST['child_id'];
		$child_duration_id	= 	$_POST['vaccineDurationId'.$i];
		$vaccine_given_date	=	date('Y-m-d',strtotime($_POST['vaccine_given_date'.$i]));
	
		$arrFields1 = array();
		$arrValues1 = array();
		$arrFields1[]= 'vaccine_given_date';
		$arrValues1[]=  $vaccine_given_date;
		$arrFields1[]= 'vaccine_id';
		$arrValues1[]=  $child_vaccine_id;
		$arrFields1[]= 'vaccine_duration_id';
		$arrValues1[]=  $child_duration_id;
		$arrFields1[]= 'child_id';
		$arrValues1[]=  $child_id;
		$arrFields1[]= 'remarks';
		$arrValues1[]=  $_POST['txtComment'.$i];
		if(!empty($admin_id))
		{
			$arrFields1[]= 'user_id';
			$arrValues1[]=  $admin_id;

		}
		
		$arrFields1[]= 'user_type';
		$arrValues1[]=  '3';
		$arrFields1[]= 'created_at';
		$arrValues1[]=  $curDate;
			
		$getChild 	 = 	mysqlSelect('child_name','child_tab',"patient_id='".$child_id."'","","","","");
		$userDetails = 	mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
		$userName	 =	$userDetails[0]['ref_name'];
		$getcount 	 =  mysqlSelect('count(vaccine_given_date) AS NumberofGivenDate','vaccine_child_tab',"vaccine_id='".$child_vaccine_id."' and child_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$admin_id."' and user_type='3'","","","","");
		if( $getcount[0]['NumberofGivenDate'] >= 1) 
		{
			$vaccine_update	=	mysqlUpdate('vaccine_child_tab',$arrFields1,$arrValues1,"vaccine_id='".$child_vaccine_id."' and child_id='".$child_id."' and vaccine_duration_id='".$child_duration_id."' and user_id='".$admin_id."' and user_type='3'");
		}
		else 
		{
			$add_vaccine	=	mysqlInsert('vaccine_child_tab',$arrFields1,$arrValues1);
		}
	}
	header("Location:My-Patient-Details?p=".md5($child_id));
}
	//Update Vaccine Schedule Date
if(isset($_POST['update_vaccine_due_date']))
{
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'child_id';
	$arrValues[]= $_POST['child_id'];
	
	$arrFields[]= 'vaccine_duration_id';
	$arrValues[]= $_POST['vaccine_duration'];
	
	$arrFields[]= 'vaccine_due_date';
	$arrValues[]= date('Y-m-d',strtotime($_POST['vaccine_due_date']));
	
	$getVaccineDue	=	mysqlSelect("*","vaccine_child_due_date","child_id='".$_POST['child_id']."'","","","","");	
	if($getVaccineDue==true)
	{
		$updateVaccineDueDate=mysqlUpdate('vaccine_child_due_date',$arrFields,$arrValues,"child_id='".$_POST['child_id']."'");	

	}
	else
	{
		$addVaccineDueDate=mysqlInsert('vaccine_child_due_date',$arrFields,$arrValues);
	}
	$response	="Vaccine-Due-Updated";
	header("Location:My-Patient-Details?p=".md5($_POST['child_id']));
	
}

//Surgery scheduler
if(isset($_POST['bookSurgery']))
{
	//$checkPatient= mysqlSelect("patient_id","doc_my_patient","patient_id='".$_POST['patient_id']."' and doc_id='".$admin_id."'","","","","");
	
	$checkPatient= mysqlSelect("DISTINCT (a.patient_id) as patient_id","patients_appointment as a INNER JOIN patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."' and b.doc_id='".$admin_id."'","","","","");
	if(!empty($checkPatient[0]['patient_id']))
	{
		$arrField[]="patient_id";
		$arrVal[]=$checkPatient[0]['patient_id'];
	}
	if(!empty($admin_id))
	{
		$arrField[]="doc_id";
		$arrVal[]=$admin_id;
	}
	
	
	
	
	$arrField[]="doc_type";
	$arrVal[]="1";
	
	$arrField[]="title";
	$arrVal[]=$_POST['surgery_name'];
	
	$arrField[]="status";
	$arrVal[]="Scheduled";
	
	$arrField[]="date";
	$arrVal[]=date('Y-m-d',strtotime($_POST['dateadded2']));
	
	$arrField[]="time";
	$arrVal[]=date('H:i:s',strtotime($_POST['dateadded2']));
	
	$arrField[]="created";
	$arrVal[]=$curDate;
	
	$arrField[]="modified";
	$arrVal[]=$curDate;
	
	$insert_patient=mysqlInsert('ot_scheduler',$arrField,$arrVal);
	
	$response = "updated";
	header("Location:Surgery-Scheduler?response=".$response);
}

//Update patient status
if(isset($_GET['schedulerId']) && !empty($_GET['schedulerId']))
{
	if($_GET['statusId']==1)
	{
		$surgeryStatus="Scheduled";
	}
	else if($_GET['statusId']==2)
	{
		$surgeryStatus="Cancelled";
	}
	else if($_GET['statusId']==3)
	{
		$surgeryStatus="Postponed";
	}
	else if($_GET['statusId']==4)
	{
		$surgeryStatus="Preponed";
	}
	else if($_GET['statusId']==5)
	{
		$surgeryStatus="Completed";
	}
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'status';
	$arrValues[]= $surgeryStatus;
	//Update Patient Status
	$updateSurgeryStatus	=	mysqlUpdate('ot_scheduler',$arrFields,$arrValues,"scheduler_id='".$_GET['schedulerId']."'");
}	

//Delete Surgery Scheduler
if(isset($_GET['delschedulerid']))
{
	mysqlDelete('ot_scheduler',"md5(scheduler_id)='".$_GET['delschedulerid']."'");
}

	
//ADD Referring Doctors
if(isset($_POST['add_referin_doctor']))
{
	$doc_name	=	$_POST['doc_name'];	
	$txtemail	=	$_POST['txtemail'];
	$mobile		=	$_POST['mobile'];
	$city		=	$_POST['city'];
	$address	=	$_POST['address'];
	$slctSpec	=	$_POST['slctSpec'];
	$type		=	$_POST['type'];
	$slctHospt	=	$_POST['slctHospt'];	  
		
	$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");

	$arrFields_doctor[] = 'doctor_name';
	$arrValues_doctor[] = $doc_name;
	$arrFields_doctor[] = 'doctor_email';
	$arrValues_doctor[] = $txtemail;
	$arrFields_doctor[] = 'doctor_mobile';
	$arrValues_doctor[] = $mobile;
	$arrFields_doctor[] = 'doctor_city';
	$arrValues_doctor[] = $city;
	if(!empty($slctSpec))
	{
		$arrFields_doctor[] = 'doc_specialization';
		$arrValues_doctor[] = $slctSpec;

	}
	
	$arrFields_doctor[] = 'doc_address';
	$arrValues_doctor[] = $address;
	
	if(!empty($type))
	{
		$arrFields_doctor[] = 'type';
		$arrValues_doctor[] = $type;
	}

	if(!empty($slctHospt))
	{
		$arrFields_doctor[] = 'ref_hosp_id';
		$arrValues_doctor[] = $slctHospt;
	}
	
	if(!empty($admin_id))
	{
		$arrFields_doctor[] = 'doc_id';
		$arrValues_doctor[] = $admin_id;
	}
	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields_doctor[] = 'hosp_id';
		$arrValues_doctor[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields_doctor[] = 'company_id';
		$arrValues_doctor[] = $getDocDetails[0]['company_id'];
	}
	
	$doccreate=mysqlInsert('doctor_in_referral',$arrFields_doctor,$arrValues_doctor);	
		
	$response="created-success";
	if($_POST['appointSec']=="1")
	{
		header("Location:Appointments?response=".$response);
	}
	else
	{
		header("Location:Refer-Out-Doctor?response=".$response);
	}
}
 
//ADD Referring Hospitals
if(isset($_POST['add_referout_hospital']))
{
	$hos_name	=	$_POST['hos_name'];	
	$txtemail	=	$_POST['txtemail'];
	$mobile		=	$_POST['mobile'];
	$address	=	$_POST['address'];
	
	$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	
	$arrFields_hospital[] = 'hospital_name';
	$arrValues_hospital[] = $hos_name;
	$arrFields_hospital[] = 'hospital_email';
	$arrValues_hospital[] = $txtemail;
	$arrFields_hospital[] = 'hospital_mobile';
	$arrValues_hospital[] = $mobile;
	$arrFields_hospital[] = 'hos_address';
	$arrValues_hospital[] = $address;
	if(!empty($admin_id))
	{
		$arrFields_hospital[] = 'doc_id';
		$arrValues_hospital[] = $admin_id;
	}
	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields_hospital[] = 'hosp_id';
		$arrValues_hospital[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields_hospital[] = 'company_id';
		$arrValues_hospital[] = $getDocDetails[0]['company_id'];
	}
	$arrFields_hospital[] = 'created_date';
	$arrValues_hospital[] = $curDate;
	
	$hoscreate	=	mysqlInsert('hospital_in_referral',$arrFields_hospital,$arrValues_hospital);	
		
	$response="created-success";
	if($_POST['appointSec']=="1")
	{
		header("Location:Appointments?response=".$response);
	}
	else
	{
		header("Location:Refer-Out-Hospital?response=".$response);
	}
}
//ADD Reference
if(isset($_POST['add_referred_doc']) || isset($_POST['update_referred']))
{
	$referral_name		=	addslashes($_POST['referral_name']);	
	$referral_email		=	addslashes($_POST['referral_email']);
	$referral_mobile	=	addslashes($_POST['referral_mobile']);
	$referral_city		=	addslashes($_POST['referral_city']);
	$referral_add		=	addslashes($_POST['referral_address']);
	$referral_state		=	addslashes($_POST['se_state1']);
	$referral_country	=	addslashes($_POST['se_country']);
	$reference_type		=	addslashes($_POST['reference_type']);

	$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	
	$arrFields[] = 'referral_name';
	$arrValues[] = $referral_name;
	$arrFields[] = 'referral_email';
	$arrValues[] = $referral_email;
	$arrFields[] = 'referral_mobile';
	$arrValues[] = $referral_mobile;
	$arrFields[] = 'referral_city';
	$arrValues[] = $referral_city;
	$arrFields[] = 'referral_address';
	$arrValues[] = $referral_add;
	$arrFields[] = 'referral_state';
	$arrValues[] = $referral_state;
	$arrFields[] = 'referral_country';
	$arrValues[] = $referral_country;
	$arrFields[] = 'reference_type';
	$arrValues[] = $reference_type;

	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields[] = 'hosp_id';
		$arrValues[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields[] = 'company_id';
		$arrValues[] = $getDocDetails[0]['company_id'];
	}
	
	if(isset($_POST['add_referred_doc']))
	{
		if(!empty($admin_id))
		{
			$arrFields[] = 'doc_id';
			$arrValues[] = $admin_id;
		}
		
		$createrefdoc=mysqlInsert('add_referred_doctor',$arrFields,$arrValues);
		$response="created-success";
	}
	if(isset($_POST['update_referred']))
	{
		$updaterefdoc=mysqlUpdate('add_referred_doctor',$arrFields,$arrValues,"referred_doc_id='".$_POST['referred_doc_id']."'");
		$response="update-success";
	}
		
	if($_POST['appointSec']=="1")
	{
		header("Location:Appointments?response=".$response);
	}
	else
	{	
		header("Location:Add-Referred-Doctor?response=".$response);
	}
}	
//Dilation Timer	
if(isset($_POST['startDilationBtn']))
{
	date_default_timezone_set('Asia/Kolkata');
	$currentTime = date('Y-m-d H:i:s');
	$currentTime = strtotime($currentTime);
	$currentTime = strtotime("+".$_POST['dilationTime']." minute", $currentTime);
	$dilationTime = date('H:i:s', $currentTime);

	$arrField[]	="set_diation_timer";
	$arrVal[]	=$dilationTime;
	$arrField[] ="dilation_status";
	$arrVal[]	="1";
	$update_dialtion=mysqlUpdate('appointment_token_system',$arrField,$arrVal,"token_id='".$_POST['token_id']."'");
	$response = "updated";
	header("Location:Appointments?response=".$response);
}
if(isset($_POST['stopDilationBtn']))
{
	$arrField[]	="set_diation_timer";
	$arrVal[]	="00:00:00";
	$arrField[]	="dilation_status";
	$arrVal[]	="0";
	$update_dialtion=mysqlUpdate('appointment_token_system',$arrField,$arrVal,"token_id='".$_POST['token_id']."'");
	$response 	= "updated";
	header("Location:Appointments?response=".$response);
}
	
	//Customized Trend Analysis
if(isset($_POST['addTrendAnalyseCount']))
{
	$getDate			=	date('Y-m-d',strtotime($_POST['custom_dateadded']));
	$before_meals_count =	$_POST['custom_before_meals'];
	$after_meals_count	=	$_POST['custom_after_meals'];
	$systolicCount		=   $_POST['custom_systolicCount'];
	$diastolicCount		=	$_POST['custom_diastolicCount'];
	$hba1cCount			=	$_POST['custom_hba1cCount'];
	$hdlCount			=	$_POST['custom_hdlCount'];
	$vldlCount			=	$_POST['custom_vldlCount'];
	$ldlCount			=	$_POST['custom_ldlCount'];
	$triglycerideCount	=	$_POST['custom_triglycerideCount'];
	$cholestrolCount	=	$_POST['custom_cholestrolCount'];
	$patient_id			=	$_POST['custom_patient_id'];
	
	//$arrFields = array();
	//$arrValues = array();
	
	//$arrFields[]='patient_id';
	//$arrValues[]=$patient_id;
	
	//$arrFields[]='date_added';
	//$arrValues[]=$getDate;
	
	if(!empty($before_meals_count))
	{
		$arrFieldsInvest1 = array();
		$arrValuesInvest1 = array();
		$arrFieldsInvest1[]='invest_value';
		$arrValuesInvest1[]=$before_meals_count;
		$arrFieldsInvest1[]='date_added';
		$arrValuesInvest1[]=$getDate;
		if(!empty($admin_id))
		{
			$arrFieldsInvest1[]='doc_id';
			$arrValuesInvest1[]=$admin_id;
		}
		if(!empty($_POST['invest_id0']))
		{
			$arrFieldsInvest1[]='invest_id';
			$arrValuesInvest1[]=$_POST['invest_id0'];
		}
		if(!empty($patient_id))
		{
			$arrFieldsInvest1[]='patient_id';
			$arrValuesInvest1[]=$patient_id;
		}
		
		
		
	
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and patient_id='".$patient_id."' and date_added='".$getDate."' and invest_id='".$_POST['invest_id0']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0)
		{	
			$insert_invests1=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest1,$arrValuesInvest1,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id0']."' and date_added!='0000-00-00'");
		}
	else
	{
		$insert_invests1=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest1,$arrValuesInvest1);
	}
}
if(!empty($after_meals_count))
{
	$arrFieldsInvest2 = array();
	$arrValuesInvest2 = array();
	$arrFieldsInvest2[]='invest_value';
	$arrValuesInvest2[]=$after_meals_count;
	$arrFieldsInvest2[]='date_added';
	$arrValuesInvest2[]=$getDate;
	if(!empty($admin_id))
	{
		$arrFieldsInvest2[]='doc_id';
		$arrValuesInvest2[]=$admin_id;
	}
	if(!empty($_POST['invest_id1']))
	{
		$arrFieldsInvest2[]='invest_id';
		$arrValuesInvest2[]=$_POST['invest_id1'];
	}
	if(!empty($patient_id))
	{
		$arrFieldsInvest2[]='patient_id';
		$arrValuesInvest2[]=$patient_id;
	}
		
	$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id1']."' and date_added!='0000-00-00'","","","","");
	if(COUNT($check_trend_active)>0)
	{		
		$insert_invests2=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest2,$arrValuesInvest2,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id1']."' and date_added!='0000-00-00'");
	
	}
	else
	{
		$insert_invests2=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest2,$arrValuesInvest2);
	}
}
if(!empty($systolicCount))
{
	$arrFieldsInvest3 = array();
	$arrValuesInvest3 = array();
	$arrFieldsInvest3[]='invest_value';
	$arrValuesInvest3[]=$systolicCount;
	$arrFieldsInvest3[]='date_added';
	$arrValuesInvest3[]=$getDate;
	if(!empty($admin_id))
	{
		$arrFieldsInvest3[]='doc_id';
		$arrValuesInvest3[]=$admin_id;
	}
	if(!empty($_POST['invest_id2']))
	{
		$arrFieldsInvest3[]='invest_id';
		$arrValuesInvest3[]=$_POST['invest_id2'];
	}
	if(!empty($patient_id))
	{
		$arrFieldsInvest3[]='patient_id';
		$arrValuesInvest3[]=$patient_id;
	}
	
	$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id2']."' and date_added!='0000-00-00'","","","","");
	if(COUNT($check_trend_active)>0)
	{		
	
		$insert_invests3=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest3,$arrValuesInvest3,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id2']."' and date_added!='0000-00-00'");
	
	}
	else
	{
		$insert_invests3=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest3,$arrValuesInvest3);
	}
}
		if(!empty($diastolicCount))
		{
			$arrFieldsInvest4 = array();
			$arrValuesInvest4 = array();
			$arrFieldsInvest4[]='invest_value';
			$arrValuesInvest4[]=$diastolicCount;
			$arrFieldsInvest4[]='date_added';
			$arrValuesInvest4[]=$getDate;
			if(!empty($admin_id))
			{
				$arrFieldsInvest4[]='doc_id';
				$arrValuesInvest4[]=$admin_id;
			}
			if(!empty($_POST['invest_id3']))
			{
				$arrFieldsInvest4[]='invest_id';
				$arrValuesInvest4[]=$_POST['invest_id3'];
			}
			if(!empty($patient_id))
			{
				$arrFieldsInvest4[]='patient_id';
				$arrValuesInvest4[]=$patient_id;
			}
			
			$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id3']."' and date_added!='0000-00-00'","","","","");
			if(COUNT($check_trend_active)>0)
			{		
				$insert_invests4=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest4,$arrValuesInvest4,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id3']."' and date_added!='0000-00-00'");
			}
			else
			{
				$insert_invests4=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest4,$arrValuesInvest4);
			}
		}
		if(!empty($hba1cCount))
		{
			$arrFieldsInvest5 = array();
			$arrValuesInvest5 = array();
			$arrFieldsInvest5[]='invest_value';
			$arrValuesInvest5[]=$hba1cCount;
			$arrFieldsInvest5[]='date_added';
			$arrValuesInvest5[]=$getDate;
			if(!empty($admin_id))
			{
				$arrFieldsInvest5[]='doc_id';
				$arrValuesInvest5[]=$admin_id;
			}
			if(!empty($_POST['invest_id4']))
			{
				$arrFieldsInvest5[]='invest_id';
				$arrValuesInvest5[]=$_POST['invest_id4'];
			}
			if(!empty($patient_id))
			{
				$arrFieldsInvest5[]='patient_id';
				$arrValuesInvest5[]=$patient_id;
			}
		
			
			
		
			$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id4']."' and date_added!='0000-00-00'","","","","");
			if(COUNT($check_trend_active)>0)
			{		
		
				$insert_invests5=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest5,$arrValuesInvest5,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id4']."' and date_added!='0000-00-00'");
		
			}
			else
			{
				$insert_invests5=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest5,$arrValuesInvest5);
			}
		}
		if(!empty($hdlCount))
		{
			$arrFieldsInvest6 = array();
			$arrValuesInvest6 = array();
			$arrFieldsInvest6[]='invest_value';
			$arrValuesInvest6[]=$hdlCount;
			$arrFieldsInvest6[]='date_added';
			$arrValuesInvest6[]=$getDate;

			if(!empty($admin_id))
			{
				$arrFieldsInvest5[]='doc_id';
				$arrValuesInvest5[]=$admin_id;
			}
			if(!empty($_POST['invest_id5']))
			{
				$arrFieldsInvest5[]='invest_id';
				$arrValuesInvest5[]=$_POST['invest_id5'];
			}
			if(!empty($patient_id))
			{
				$arrFieldsInvest5[]='patient_id';
				$arrValuesInvest5[]=$patient_id;
			}
		

			// $arrFieldsInvest6[]='doc_id';
			// $arrValuesInvest6[]=$admin_id;
			// $arrFieldsInvest6[]='invest_id';
			// $arrValuesInvest6[]=$_POST['invest_id5'];
			// $arrFieldsInvest6[]='patient_id';
			// $arrValuesInvest6[]=$patient_id;
		
			$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id5']."' and date_added!='0000-00-00'","","","","");
			
			if(COUNT($check_trend_active)>0)
			{		
				$insert_invests6=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest6,$arrValuesInvest6,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id5']."' and date_added!='0000-00-00'");
			
			}
			else
			{
				$insert_invests6=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest6,$arrValuesInvest6);
			
			}
		}
		if(!empty($vldlCount))
		{
			$arrFieldsInvest7 = array();
			$arrValuesInvest7 = array();
			$arrFieldsInvest7[]='invest_value';
			$arrValuesInvest7[]=$vldlCount;
			$arrFieldsInvest7[]='date_added';
			$arrValuesInvest7[]=$getDate;
			if(!empty($admin_id))
			{
				$arrFieldsInvest5[]='doc_id';
				$arrValuesInvest5[]=$admin_id;
			}
			if(!empty($_POST['invest_id6']))
			{
				$arrFieldsInvest5[]='invest_id';
				$arrValuesInvest5[]=$_POST['invest_id6'];
			}
			if(!empty($patient_id))
			{
				$arrFieldsInvest5[]='patient_id';
				$arrValuesInvest5[]=$patient_id;
			}
			// $arrFieldsInvest7[]='doc_id';
			// $arrValuesInvest7[]=$admin_id;
			// $arrFieldsInvest7[]='invest_id';
			// $arrValuesInvest7[]=$_POST['invest_id6'];
			// $arrFieldsInvest7[]='patient_id';
			// $arrValuesInvest7[]=$patient_id;
			
			$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id6']."' and date_added!='0000-00-00'","","","","");
			if(COUNT($check_trend_active)>0){		
			$insert_invests7=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest7,$arrValuesInvest7,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id6']."' and date_added!='0000-00-00'");
		
			}
			else
			{
				$insert_invests7=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest7,$arrValuesInvest7);
			}
		}
		if(!empty($ldlCount)){
		$arrFieldsInvest8 = array();
		$arrValuesInvest8 = array();
		$arrFieldsInvest8[]='invest_value';
		$arrValuesInvest8[]=$ldlCount;
		$arrFieldsInvest8[]='date_added';
		$arrValuesInvest8[]=$getDate;
		if(!empty($admin_id))
		{
			$arrFieldsInvest5[]='doc_id';
			$arrValuesInvest5[]=$admin_id;
		}
		if(!empty($_POST['invest_id7']))
		{
			$arrFieldsInvest5[]='invest_id';
			$arrValuesInvest5[]=$_POST['invest_id7'];
		}
		if(!empty($patient_id))
		{
			$arrFieldsInvest5[]='patient_id';
			$arrValuesInvest5[]=$patient_id;
		}

		// $arrFieldsInvest8[]='doc_id';
		// $arrValuesInvest8[]=$admin_id;
		// $arrFieldsInvest8[]='invest_id';
		// $arrValuesInvest8[]=$_POST['invest_id7'];
		// $arrFieldsInvest8[]='patient_id';
		// $arrValuesInvest8[]=$patient_id;
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id7']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests8=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest8,$arrValuesInvest8,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id7']."' and date_added!='0000-00-00'");
		
		}
		else
		{
			$insert_invests8=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest8,$arrValuesInvest8);
		
		}
		}
		if(!empty($triglycerideCount)){
		$arrFieldsInvest9 = array();
		$arrValuesInvest9 = array();
		$arrFieldsInvest9[]='invest_value';
		$arrValuesInvest9[]=$triglycerideCount;
		$arrFieldsInvest9[]='date_added';
		$arrValuesInvest9[]=$getDate;
		if(!empty($admin_id))
		{
			$arrFieldsInvest5[]='doc_id';
			$arrValuesInvest5[]=$admin_id;
		}
		if(!empty($_POST['invest_id8']))
		{
			$arrFieldsInvest5[]='invest_id';
			$arrValuesInvest5[]=$_POST['invest_id8'];
		}
		if(!empty($patient_id))
		{
			$arrFieldsInvest5[]='patient_id';
			$arrValuesInvest5[]=$patient_id;
		}

		// $arrFieldsInvest9[]='doc_id';
		// $arrValuesInvest9[]=$admin_id;
		// $arrFieldsInvest9[]='invest_id';
		// $arrValuesInvest9[]=$_POST['invest_id8'];
		// $arrFieldsInvest9[]='patient_id';
		// $arrValuesInvest9[]=$patient_id;
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id8']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests9=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest9,$arrValuesInvest9,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id8']."' and date_added!='0000-00-00'");
		}
		else{
			$insert_invests9=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest9,$arrValuesInvest9);
		
		}
		}
		if(!empty($cholestrolCount)){
		$arrFieldsInvest10 = array();
		$arrValuesInvest10 = array();
		$arrFieldsInvest10[]='invest_value';
		$arrValuesInvest10[]=$cholestrolCount;
		$arrFieldsInvest10[]='date_added';
		$arrValuesInvest10[]=$getDate;

		if(!empty($admin_id))
		{
			$arrFieldsInvest5[]='doc_id';
			$arrValuesInvest5[]=$admin_id;
		}
		if(!empty($_POST['invest_id9']))
		{
			$arrFieldsInvest5[]='invest_id';
			$arrValuesInvest5[]=$_POST['invest_id9'];
		}
		if(!empty($patient_id))
		{
			$arrFieldsInvest5[]='patient_id';
			$arrValuesInvest5[]=$patient_id;
		}

		// $arrFieldsInvest10[]='doc_id';
		// $arrValuesInvest10[]=$admin_id;
		// $arrFieldsInvest10[]='invest_id';
		// $arrValuesInvest10[]=$_POST['invest_id9'];
		// $arrFieldsInvest10[]='patient_id';
		// $arrValuesInvest10[]=$patient_id;
		
		$check_trend_active = mysqlSelect("*","trend_analysis_investigations","doc_id='".$admin_id."' and date_added='".$getDate."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id9']."' and date_added!='0000-00-00'","","","","");
		if(COUNT($check_trend_active)>0){		
		$insert_invests10=mysqlUpdate('trend_analysis_investigations',$arrFieldsInvest10,$arrValuesInvest10,"doc_id='".$admin_id."' and patient_id='".$patient_id."' and invest_id='".$_POST['invest_id9']."' and date_added!='0000-00-00'");
		
		}
		else{
			$insert_invests10=mysqlInsert('trend_analysis_investigations',$arrFieldsInvest10,$arrValuesInvest10);
		
		}
		}
		//$arrFields[]='patient_type';
		//$arrValues[]="1";
		
		//$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$getDate."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		//if(count($checkTrend)>0)
		//{
		//	$update_medicine=mysqlUpdate('trend_analysis',$arrFields,$arrValues,"date_added='".$getDate."' and patient_id = '".$patient_id."' and patient_type='1'");
		//}
		//else
		//{
		//$insert_patient=mysqlInsert('trend_analysis',$arrFields,$arrValues);
		//}
		$response="updated";
		header("Location:My-Patient-Details?p=".md5($patient_id));
}	


	//Update Out Patient Billing
	if(isset($_POST['cmdUpdateOpBilling']))
	{
		$patientId 				= $_POST['patiet_id'];
		$patientName 			= $_POST['patName'];
		$patientMobile 			= $_POST['patMobile'];
		$patientAddress 		= $_POST['patAddress'];
		$payType 				= $_POST['radiochkType'];
		$textCreditBillPayer 	= $_POST['textCreditBillPayer'];
		$template_id=$_POST['opb_temp_id'];
		
		//Save Billing Template 
		$template_name = $patientName."-".$curDate;
		$arrField = array();
		$arrValue = array();
		
		$arrField[] = 'template_name';
		$arrValue[] =  $template_name;
		$arrField[] = 'patient_id';
		$arrValue[] =  $patientId;
		$arrField[] = 'patient_name';
		$arrValue[] =  $patientName;
		$arrField[] = 'patient_mobile';
		$arrValue[] =  $patientMobile;
		$arrField[] = 'address';
		$arrValue[] =  $patientAddress;
		
		$arrField[] = 'payment_type';	
		$arrValue[] =  $payType;
		$arrField[] = 'credit_bill_payer';	
		$arrValue[] =  $textCreditBillPayer;
			
		$updateBilling=mysqlUpdate('out_patient_billing_template',$arrField,$arrValue,"opb_temp_id='".$template_id."' and doc_id='".$admin_id."'");
	    
		
		/*$arrFieldBill = array();
		$arrValueBill = array();
		
		$arrFieldBill[] = 'opb_temp_id';
		$arrValueBill[] = $template_id;
		$arrFieldBill[] = 'patient_id';
		$arrValueBill[] = $patientId;
		$arrFieldBill[] = 'active_status';
		$arrValueBill[] = "0";
		
		$updateBilling=mysqlUpdate('out_patient_billing',$arrFieldBill,$arrValueBill,"active_status='1' and doc_id='".$admin_id."'");
	    */
		
		mysqlDelete('out_patient_billing_payment_method',"opb_temp_id='".$template_id."'");	
		
		if(!empty($_POST['pay_mode1']) && !empty($_POST['payAmount1'])){
			$arrFieldpay1 = array();
			$arrValuepay1 = array();
			if(!empty($template_id))
			{
				$arrFieldpay1[] = 'opb_temp_id';
				$arrValuepay1[] = $template_id;

			}
			
			
			$arrFieldpay1[] = 'pay_type';
			$arrValuepay1[] = $_POST['pay_mode1'];
			$arrFieldpay1[] = 'narration';
			$arrValuepay1[] = $_POST['payNarration1'];
			$arrFieldpay1[] = 'amount';
			$arrValuepay1[] = $_POST['payAmount1'];
			$insert_mode1 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay1,$arrValuepay1);
					
		}
		if(!empty($_POST['pay_mode2']) && !empty($_POST['payAmount2'])){
			$arrFieldpay2 = array();
			$arrValuepay2 = array();

			if(!empty($template_id))
			{
				$arrFieldpay2[] = 'opb_temp_id';
				$arrValuepay2[] = $template_id;
			}
			
			
			$arrFieldpay2[] = 'pay_type';
			$arrValuepay2[] = $_POST['pay_mode2'];
			$arrFieldpay2[] = 'narration';
			$arrValuepay2[] = $_POST['payNarration2'];
			$arrFieldpay2[] = 'amount';
			$arrValuepay2[] = $_POST['payAmount2'];
			$insert_mode2 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay2,$arrValuepay2);
		}
		if(!empty($_POST['pay_mode3']) && !empty($_POST['payAmount3'])){
			$arrFieldpay3 = array();
			$arrValuepay3 = array();

			if(!empty($template_id))
			{
				$arrFieldpay3[] = 'opb_temp_id';
				$arrValuepay3[] = $template_id;
			}
			
			
			$arrFieldpay3[] = 'pay_type';
			$arrValuepay3[] = $_POST['pay_mode3'];
			$arrFieldpay3[] = 'narration';
			$arrValuepay3[] = $_POST['payNarration3'];
			$arrFieldpay3[] = 'amount';
			$arrValuepay3[] = $_POST['payAmount3'];
			$insert_mode3 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay3,$arrValuepay3);
		}
		
		$billingId = md5($template_id);
		header("Location:print-op-invoice/?id=".$billingId);
	}
	
	
	//Save Patient Discharge Summary
	if(isset($_POST['cmdSaveOpBilling']))
	{
		$patientId = $_POST['patiet_id'];
		$patientName = $_POST['patName'];
		$patientMobile = $_POST['patMobile'];
		$patientAddress = $_POST['patAddress'];
		$payType = $_POST['radiochkType'];
		$textCreditBillPayer = $_POST['textCreditBillPayer'];
		
		//Save Billing Template 
		$template_name = $patientName."-".$curDate;
		$arrField = array();
		$arrValue = array();
		
		$arrField[] = 'template_name';
		$arrValue[] =  $template_name;
		
		$arrField[] = 'patient_name';
		$arrValue[] =  $patientName;
		$arrField[] = 'patient_mobile';
		$arrValue[] =  $patientMobile;
		if(!empty($payType))
		{
			$arrField[] = 'payment_type';	
			$arrValue[] =  $payType;
		}	
		if(!empty($admin_id))
		{
			$arrField[] = 'doc_id';
			$arrValue[] =  $admin_id;
		}	
		if(!empty($patientId))
		{
			$arrField[] = 'patient_id';
			$arrValue[] =  $patientId;
		}		
		
		$arrField[] = 'credit_bill_payer';	
		$arrValue[] =  $textCreditBillPayer;		
		
		$arrField[] = 'address';
		$arrValue[] =  $patientAddress;
		
		$arrField[] = 'created_date';
		$arrValue[] =  $curDate;
		
		$insert_patient_op_billing_template = mysqlInsert('out_patient_billing_template',$arrField,$arrValue);
		$template_id = 	$insert_patient_op_billing_template; //mysqli_insert_id();
			
		$arrFieldBill = array();
		$arrValueBill = array();
		
		if(!empty($template_id))
		{
			$arrFieldBill[] = 'opb_temp_id';
			$arrValueBill[] = $template_id;
		}	
		
		if(!empty($patientId))
		{
			$arrField[] = 'patient_id';
			$arrValue[] =  $patientId;
		}	

		// $arrFieldBill[] = 'patient_id';
		// $arrValueBill[] = $patientId;
		$arrFieldBill[] = 'active_status';
		$arrValueBill[] = "0";
		
		$updateBilling=mysqlUpdate('out_patient_billing',$arrFieldBill,$arrValueBill,"active_status='1' and doc_id='".$admin_id."'");
	    
		if(!empty($_POST['pay_mode1']) && !empty($_POST['payAmount1'])){
			$arrFieldpay1 = array();
			$arrValuepay1 = array();

			if(!empty($template_id))
			{
				$arrFieldpay1[] = 'opb_temp_id';
				$arrValuepay1[] = $template_id;
			}
			
			
			if(!empty($_POST['pay_mode1']))
			{
				$arrFieldpay1[] = 'pay_type';
				$arrValuepay1[] = $_POST['pay_mode1'];
			}	
			
			$arrFieldpay1[] = 'narration';
			$arrValuepay1[] = $_POST['payNarration1'];
			$arrFieldpay1[] = 'amount';
			$arrValuepay1[] = $_POST['payAmount1'];
			$insert_mode1 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay1,$arrValuepay1);
					
		}
		if(!empty($_POST['pay_mode2']) && !empty($_POST['payAmount2'])){
			$arrFieldpay2 = array();
			$arrValuepay2 = array();

			if(!empty($template_id))
			{
				$arrFieldpay1[] = 'opb_temp_id';
				$arrValuepay1[] = $template_id;
			}
			if(!empty($_POST['pay_mode2']))
			{
				$arrFieldpay1[] = 'pay_type';
				$arrValuepay1[] = $_POST['pay_mode2'];
			}	
			
			$arrFieldpay2[] = 'narration';
			$arrValuepay2[] = $_POST['payNarration2'];
			$arrFieldpay2[] = 'amount';
			$arrValuepay2[] = $_POST['payAmount2'];
			$insert_mode2 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay2,$arrValuepay2);
		}
		if(!empty($_POST['pay_mode3']) && !empty($_POST['payAmount3'])){
			$arrFieldpay3 = array();
			$arrValuepay3 = array();

			if(!empty($template_id))
			{
				$arrFieldpay1[] = 'opb_temp_id';
				$arrValuepay1[] = $template_id;
			}
			if(!empty($_POST['pay_mode3']))
			{
				$arrFieldpay1[] = 'pay_type';
				$arrValuepay1[] = $_POST['pay_mode3'];
			}	
			$arrFieldpay3[] = 'narration';
			$arrValuepay3[] = $_POST['payNarration3'];
			$arrFieldpay3[] = 'amount';
			$arrValuepay3[] = $_POST['payAmount3'];
			$insert_mode3 = mysqlInsert('out_patient_billing_payment_method',$arrFieldpay3,$arrValuepay3);
		}
		
		$billingId = md5($template_id);
		header("Location:print-op-invoice/?id=".$billingId);
	}
		
	//Save Patient Discharge Summary
	if(isset($_POST['cmdSaveDischarge']) || isset($_POST['cmdUpdateDischarge']))
	{
		/* Save Examination template details Starts here */
				
				$chkSaveTemplate = $_POST['chkSaveTemplate'];
				
				if ($chkSaveTemplate == 1)
				{
					
					$template_name = $_POST['template_name'];
					if ($template_name == '')
					{
						$template_name = 'discharge-Template-'.$curDate;
					}

					$arrFieldsDISCHTEMP = array();
					$arrValuesDISCHTEMP = array();
					if(!empty($admin_id))
					{
						$arrFieldsDISCHTEMP[] = 'doc_id';
						$arrValuesDISCHTEMP[] = $admin_id;
					}

					$arrFieldsDISCHTEMP[] = 'template_name';
					$arrValuesDISCHTEMP[] = addslashes($template_name);					

					$insert_patient_discharge_template = mysqlInsert('doc_discharge_summary_templates',$arrFieldsDISCHTEMP,$arrValuesDISCHTEMP);
					$summary_template_id = $insert_patient_discharge_template;//mysqli_insert_id(); //Get episode_id
					
					$arrFileds[]='template_id';
					$arrValues[]=$summary_template_id;
				}
		
		if(isset($_POST['cmdSaveDischarge']))
		{	
			if(!empty($admin_id))
			{
				$arrFileds[]='doc_id';
				$arrValues[]=$admin_id;	
			}
			if(!empty($_POST['patient_id']))
			{
				$arrFileds[]='patient_id';
				$arrValues[]=$_POST['patient_id'];
			}	
			
		
		
		$arrFileds[]='discharge_summary';
		$arrValues[]=addslashes($_POST['descr']);
		$arrFileds[]='created_date';
		$arrValues[]=$curDate;
		
		$insert_discharge=mysqlInsert('patient_discharge_summaray',$arrFileds,$arrValues);
		$summary_id = $insert_discharge;
		
							$report_title = "Discharge-Summary-".$curDate;
							$arrFields_Attach = array();
							$arrValues_Attach  = array();

							$arrFields_Attach[] = 'patient_id';
							$arrValues_Attach[] = $_POST['patient_id'];
							
							$arrFields_Attach[] = 'report_title';
							$arrValues_Attach[] = $report_title;
							
							$arrFields_Attach[] = 'report_folder';
							$arrValues_Attach[] = time();
							
							$arrFields_Attach[] = 'attachments';
							$arrValues_Attach[] = $summary_id;

							if(!empty($admin_id))
							{
								$arrFields_Attach[] = 'user_id';
								$arrValues_Attach[] = $admin_id;
							}
							$arrFields_Attach[] = 'date_added';
							$arrValues_Attach[] = $curDate;
							
							$bslist_pht=mysqlInsert('doc_my_patient_reports',$arrFields_Attach,$arrValues_Attach);
							
		} 
		else if(isset($_POST['cmdUpdateDischarge']))
		{
		$arrFileds[]='discharge_summary';
		$arrValues[]=addslashes($_POST['descr']);
		$arrFileds[]='created_date';
		$arrValues[]=$curDate;
		$updatedischarge=mysqlUpdate('patient_discharge_summaray',$arrFileds,$arrValues,"discharge_id='".$_POST['discharge_id']."'");
		$summary_id = $_POST['discharge_id'];
		}
		
							
		header("Location:print-discharge-summary/?id=".md5($summary_id));
	}
	
	
//Appointment Searchby Date range

if(!empty($_GET['frmDate']) && !empty($_GET['toDate']))
{
	$fromDate=date('Y-m-d',strtotime($_GET['frmDate']));
	$toDate=date('Y-m-d',strtotime($_GET['toDate']));
	//Search by date range
	$appointmentRange = mysqlSelect("*","appointment_transaction_detail","pref_doc='".$admin_id."' and Visiting_date BETWEEN '".$fromDate."' and '".$toDate."'","Visiting_date ASC","","","");
	$status_val=array("At reception"=>"6","Consulted"=>"2","Cancelled"=>"3","Missed"=>"5");
	?>
	 <div class="ibox-content">
					
	 <input type="text" class="form-control input-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
					 <table class="footable table table-stripped" data-page-size="8" data-filter=#filter>
                            <thead>
                            <tr>
								
								<th>Patient Name</th>
								<th>Appointment Slot</th>
                                <th>Status</th>
                            </tr>
                            </thead>
							<tbody>
							<?php if(COUNT($appointmentRange)=='0'){ ?>
	
							<tr>
							<td colspan="4">No Appointments</td>
							</tr>
							<?php }
								else
								{ 
							foreach($appointmentRange as $appointmentlist){ 
							
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$appointmentlist['Visiting_time']."'","","","","");
							?>
							<tr>
								<td><a href="My-Appointment-Patient-Details?appid=<?php echo $appointmentlist['appoint_trans_id']; ?>"><?php echo $appointmentlist['patient_name']; ?></a></td>
								<td style="min-width:200px;" ><?php echo date('d-m-Y',strtotime($appointmentlist['Visiting_date']))." | ".$getTimeSlot[0]['Timing']; ?></td>
								<td>
								<div class="btn-group pull-right">
								<?php 
								if($appointmentlist['pay_status']=="Pending"){
									$btn_type= "btn-danger";
								}else if($appointmentlist['pay_status']=="At reception"){
									$btn_type= "btn-warning";
								}else if($appointmentlist['pay_status']=="Consulted"){
									$btn_type= "btn-primary";
								}
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $appointmentlist['pay_status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $appointmentlist['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
								
								</td>
							</tr>
							<?php } //end foreach
	
							} ?>
							</tbody>
						</table>
	</div>
	<?php }
//Update Receptionist Permission
if(isset($_POST['update_permission'])){
	$checkPermission= mysqlSelect("*","receptionist_permission","reception_id='".$_POST['selectReception']."' and doc_id='".$admin_id."'","","","","");


	if(!empty($admin_id))
	{
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;	
	}
	// $arrField[] = 'doc_id';
	// $arrValue[] = $admin_id;
	$arrField[] = 'reception_id';
	$arrValue[] = $_POST['selectReception'];
	$arrField[] = 'medical_complaint';
	$arrValue[] = $_POST['check_chief_medical'];
	$arrField[] = 'examination';
	$arrValue[] = $_POST['check_exam'];
	$arrField[] = 'investigations';
	$arrValue[] = $_POST['check_invest'];
	$arrField[] = 'diagnosis';
	$arrValue[] = $_POST['check_diagno'];
	$arrField[] = 'treatment_advise';
	$arrValue[] = $_POST['check_treatment'];
	$arrField[] = 'prescriptions';
	$arrValue[] = $_POST['check_presc'];
	
	if($checkPermission==true)
	{
	$updatepermision=mysqlUpdate('receptionist_permission',$arrField,$arrValue,"reception_id='".$_POST['selectReception']."'");
	$response="update-success";
	}
	else {
	$createpermision=mysqlInsert('receptionist_permission',$arrField,$arrValue);
	$response="created-success";
	}
	header("Location:Add-Receptionist?response=".$response);
}	

//Delete Receptionist
if(isset($_GET['deldrugdata'])){

mysqlDelete('doctor_frequent_medicine',"md5(freq_medicine_id)='".$_GET['deldrugdata']."'");
}

//Delete Receptionist
if(isset($_GET['delreceptionid'])){

mysqlDelete('receptionist_login',"md5(reception_id)='".$_GET['delreceptionid']."'");
}	
//ADD/Update Receptionist
if(isset($_POST['add_receptionist']) || isset($_POST['update_receptionist'])){
	$receptionist_name=addslashes($_POST['receptionist_name']);	
	$receptionist_mobile=addslashes($_POST['receptionist_mobile']);
	$password=md5($_POST['password']);
			
	
	$arrFields[] = 'reception_user';
	$arrValues[] = $receptionist_name;
	$arrFields[] = 'receptionist_mobile';
	$arrValues[] = $receptionist_mobile;
	$arrFields[] = 'reception_password';
	$arrValues[] = $password;

	
	if(isset($_POST['add_receptionist'])){

		if(!empty($admin_id))
		{
			$arrFileds[]='doc_id';
			$arrValues[]=$admin_id;	
		}
	
	$arrFields[] = 'created_date';
	$arrValues[] = $curDate;
	
	$createreception=mysqlInsert('receptionist_login',$arrFields,$arrValues);
	$response="created-success";
	}
	if(isset($_POST['update_receptionist'])){
	$updatedrug=mysqlUpdate('receptionist_login',$arrFields,$arrValues,"reception_id='".$_POST['reception_id']."'");
	$response="update-success";
	}
		
	
	
	header("Location:Add-Receptionist?response=".$response);
	
}	
//ADD Doctor Drug Database
if(isset($_POST['add_drug_list']) || isset($_POST['update_drug_list'])){
	$medicine_name=addslashes($_POST['medicine_name']);	
	$generic_name=addslashes($_POST['generic_name']);
			
	
	$arrFields[] = 'med_trade_name';
	$arrValues[] = $medicine_name;
	$arrFields[] = 'med_generic_name';
	$arrValues[] = $generic_name;

	
	if(isset($_POST['add_drug_list'])){

	if(!empty($admin_id))
	{
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;	
	}
	// if(!empty($_POST['patient_id']))
	// {
	// 	$arrFileds[]='patient_id';
	// 	$arrValues[]=$_POST['patient_id'];
	// }

	$arrFields[] = 'pp_id';
	$arrValues[] = time();
	// $arrFields[] = 'doc_id';
	// $arrValues[] = $admin_id;
	$arrFields[] = 'doc_type';
	$arrValues[] = "1";
	
	$createnewdrug=mysqlInsert('doctor_frequent_medicine',$arrFields,$arrValues);
	$response="created-success";
	}
	if(isset($_POST['update_drug_list'])){
	$updatedrug=mysqlUpdate('doctor_frequent_medicine',$arrFields,$arrValues,"freq_medicine_id='".$_POST['medid']."'");
	$response="update-success";
	}
		
	
	
	header("Location:Drug-Database?response=".$response);
	
}		
//ADD Opticals
if(isset($_POST['cmdPatEdu'])){
	$edu_title=addslashes($_POST['edu_title']);	
	$edu_descr=addslashes($_POST['edu_descr']);
			
	$arrFields[] = 'edu_title';
	$arrValues[] = $edu_title;
	$arrFields[] = 'edu_description';
	$arrValues[] = $edu_descr;

	if(!empty($admin_id))
	{
		$arrFileds[]='doc_id';
		$arrValues[]=$admin_id;	
	}
	// $arrFields[] = 'doc_id';
	// $arrValues[] = $admin_id;

	$arrFields[] = 'doc_type';
	$arrValues[] = "1";
	
	$createpatientedu=mysqlInsert('patient_education',$arrFields,$arrValues);
		
	$response="created-success";
	
	header("Location:Patient-Eduction?response=".$response);
	
}
//ADD Opticals
if(isset($_POST['add_optical']) || isset($_POST['add_optical_patient'])){
	$optical_name=$_POST['optical_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
		
	$arrFields_optical[] = 'optical_name';
	$arrValues_optical[] = $optical_name;
	$arrFields_optical[] = 'optical_email';
	$arrValues_optical[] = $txtemail;
	$arrFields_optical[] = 'optical_contact_num';
	$arrValues_optical[] = $mobile;
	$arrFields_optical[] = 'optical_city';
	$arrValues_optical[] = $city;
	
	$opticalcreate	=	mysqlInsert('opticals',$arrFields_optical,$arrValues_optical);
	$optical_id 	= $opticalcreate;//mysqli_insert_id();

	if(!empty($admin_id))
	{
		$arrFields_refer[] = 'doc_id';
		$arrValues_refer[] = $admin_id;
	}
	if(!empty($optical_id))
	{
		$arrFields_refer[] = 'optical_id';
		$arrValues_refer[] = $optical_id;
	}

	
	
	$arrFields_refer[] = 'doc_type';
	$arrValues_refer[] = "1";
	
	$opticalrefer=mysqlInsert('doc_opticals',$arrFields_refer,$arrValues_refer);
		
	$response="created-success";
	if(isset($_POST['add_optical_patient'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Add-Opticals?response=".$response);
	}
}

//EMR Settings
if(isset($_POST['update_emr_settings'])){
				//Update Examination
				$arrFieldExam[] = 'default_visible';
				$arrValExam[] = "0";
				$update_before_exam=mysqlUpdate('doc_patient_episode_examination_templates',$arrFieldExam,$arrValExam,"doc_id='".$admin_id."' and doc_type='1'");
					
				foreach($_POST['slctExamTemp'] as $key => $value)
				{
					$arrField = array();
					$arrVal = array();

					$arrField[] = 'default_visible';
					$arrVal[] = "1";

											
					$update_exam=mysqlUpdate('doc_patient_episode_examination_templates',$arrField,$arrVal,"exam_template_id='".$value."'");
				}
					
					//Update Investigation
					$arrFieldInvest[] = 'default_visible';
					$arrValInvest[] = "0";
					$update_before_exam=mysqlUpdate('doc_patient_episode_investigations_templates',$arrFieldInvest,$arrValInvest,"doc_id='".$admin_id."' and doc_type='1'");
					
					foreach($_POST['slctInvestTemp'] as $key_Invest => $value_Invest)
					{
						$arrFieldInvest = array();
						$arrValInvest = array();
		
						$arrFieldInvest[] = 'default_visible';
						$arrValInvest[] = "1";

												
						$update_exam=mysqlUpdate('doc_patient_episode_investigations_templates',$arrFieldInvest,$arrValInvest,"invest_template_id='".$value_Invest."'");
					}
		$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		if(count($checkSetting)>0)
		{
			
			$arrField_temp[]	="prescription_template";
			$arrVal_temp[]		=$_POST['presc_format'];
			$update_medicine	=mysqlUpdate('doctor_settings',$arrField_temp,$arrVal_temp,"doc_id='".$admin_id."' and doc_type='1'");
		
		}
		else
		{
			if(!empty($admin_id))
			{
				$arrField_temp[]="doc_id";
				$arrVal_temp[]=$admin_id;
			}
			
			$arrField_temp[]="doc_type";
			$arrVal_temp[]='1';
			$arrField_temp[]="prescription_template";
			$arrVal_temp[]=$_POST['presc_format'];
			$update_setting=mysqlInsert('doctor_settings',$arrField_temp,$arrVal_temp);
		}
	$response = "updated-success";
	header("Location:EMR-Settings?response=".$response);
}

//Other Settings
if(isset($_POST['update_settings']))
{
	$docLogo 	= addslashes($_FILES['txtLogo']['name']);
	$arrField[]	="payment_opt";
	$arrVal[]	=$_POST['pay_option'];
	
	$arrField[]="prescription_pad";
	$arrVal[]=$_POST['prescription_pad'];
	
	$arrField[]="presc_pad_header_height";
	$arrVal[]=$_POST['header_height'];
	
	$arrField[]="presc_pad_footer_height";
	$arrVal[]=$_POST['footer_height'];
	
	$arrField[]="before_consultation_fee";
	$arrVal[]=$_POST['consultation_before'];
	
	
	
	if(!empty($_FILES['txtLogo']['name'])){
	$arrField[]="doc_logo";
	$arrVal[]=$docLogo;
	}
	$arrField[]="doc_flash_msg";
	$arrVal[]=$_POST['docFlashMsg'];
	
	$arrField[]="patient_age_type";
	$arrVal[]=$_POST['patient_age_type'];
	
	$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
		if(count($checkSetting)>0)
		{
			$update_medicine=mysqlUpdate('doctor_settings',$arrField,$arrVal,"doc_id='".$admin_id."' and doc_type='1'");
		
		}
		else
		{
			if(!empty($admin_id))
			{
				$arrField[]="doc_id";
				$arrVal[]=$admin_id;
			}
			
			$arrField[]="doc_type";
			$arrVal[]='1';
			$insert_patient=mysqlInsert('doctor_settings',$arrField,$arrVal);
		}
		
		//UPLOAD COMPRESSED IMAGE
		if ($_FILES["txtLogo"]["error"] > 0) {
        			$error = $_FILES["txtLogo"]["error"];
    		} 
    		else if (($_FILES["txtLogo"]["type"] == "image/gif") || 
			($_FILES["txtLogo"]["type"] == "image/jpeg") || 
			($_FILES["txtLogo"]["type"] == "image/png") || 
			($_FILES["txtLogo"]["type"] == "image/pjpeg")) {
			
			 $uploaddirectory = realpath("docLogo");
			 $uploaddir = $uploaddirectory . "/" .$admin_id;
			 
			 /*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $admin_id, 0777);
			}
			 
			 
        			$url = $uploaddir.'/'.$_FILES["txtLogo"]["name"];

        			$filename = compress_image($_FILES["txtLogo"]["tmp_name"], $url, 40);
        			$buffer = file_get_contents($url);

    		}else {
        			$error = "Uploaded image should be jpg or gif or png";
    		}
			
		$response="Updated ".$error;
		header("Location:Other-Settings?response=".$response);
}

	
//SEARCH Patient
if(isset($_POST['cmdSearch1'])){
	$params     = explode("-", $_POST['search1']);
	if($params[0]!=0){
	$patientid = $params[0];
	header("Location:Ophthal-EMR/?p=".md5($patientid));
	}
	else
	{
	$patientid = "0";	
	header("Location:Ophthal-EMR/?p=".$patientid."&n=".$params[0]);
	}
	
	
}


//SEARCH Patient
if(isset($_POST['cmdSearch'])){
	$params     = explode("-", $_POST['search']);
	if($params[0]!=0){
	$patientid = $params[0];
	
	$checkTodayVisit= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$admin_id."' and patient_id='".$patientid."' and DATE_FORMAT(date_time,'%Y-%m-%d')='".date('Y-m-d')."'","episode_id desc","","","");
		if(COUNT($checkTodayVisit)>0){
		header("Location:".$_SESSION['EMR_URL'].md5($patientid)."&episode=".md5($checkTodayVisit[0]['episode_id']));
		}
		else
		{
		header("Location:".$_SESSION['EMR_URL'].md5($patientid));
		}
	}
	else
	{
	$patientid = "0";	
	header("Location:".$_SESSION['EMR_URL'].$patientid."&n=".$params[0]);
	}
	
	
}
	
//ADD PATIENT GLUCOSE COUNT
	
if(isset($_POST['addPrandialCount']))
{
		$getDate=date('Y-m-d',strtotime($_POST['dateadded']));
		$before_meals_count=$_POST['before_meals'];
		$after_meals_count=$_POST['after_meals'];
		$systolicCount=$_POST['systolicCount'];
		$diastolicCount=$_POST['diastolicCount'];
		$hba1cCount=$_POST['hba1cCount'];
		$hdlCount=$_POST['hdlCount'];
		$vldlCount=$_POST['vldlCount'];
		$ldlCount=$_POST['ldlCount'];
		$triglycerideCount=$_POST['triglycerideCount'];
		$cholestrolCount=$_POST['cholestrolCount'];
		$patient_id=$_POST['patient_id'];
		
		$arrFields = array();
		$arrValues = array();
		if(!empty($patient_id))
		{
			$arrFields[]='patient_id';
			$arrValues[]=$patient_id;
		}
		
		$arrFields[]='date_added';
		$arrValues[]=$getDate;
		
		if(!empty($before_meals_count)){
		$arrFields[]='bp_beforefood_count';
		$arrValues[]=$before_meals_count;
		}
		if(!empty($after_meals_count)){
		$arrFields[]='bp_afterfood_count';
		$arrValues[]=$after_meals_count;
		}
		if(!empty($systolicCount)){
		$arrFields[]='systolic';
		$arrValues[]=$systolicCount;
		}
		if(!empty($diastolicCount)){
		$arrFields[]='diastolic';
		$arrValues[]=$diastolicCount;
		}
		if(!empty($hba1cCount)){
		$arrFields[]='HbA1c';
		$arrValues[]=$hba1cCount;
		}
		if(!empty($hdlCount)){
		$arrFields[]='HDL';
		$arrValues[]=$hdlCount;
		}
		if(!empty($vldlCount)){
		$arrFields[]='VLDL';
		$arrValues[]=$vldlCount;
		}
		if(!empty($ldlCount)){
		$arrFields[]='LDL';
		$arrValues[]=$ldlCount;
		}
		if(!empty($triglycerideCount)){
		$arrFields[]='triglyceride';
		$arrValues[]=$triglycerideCount;
		}
		if(!empty($cholestrolCount)){
		$arrFields[]='cholesterol';
		$arrValues[]=$cholestrolCount;
		}
		$arrFields[]='patient_type';
		$arrValues[]="1";
		
		$checkTrend= mysqlSelect("*","trend_analysis","date_added='".$getDate."' and patient_id='".$patient_id."' and patient_type='1'","","","","");
		if(count($checkTrend)>0)
		{
			$update_medicine=mysqlUpdate('trend_analysis',$arrFields,$arrValues,"date_added='".$getDate."' and patient_id = '".$patient_id."' and patient_type='1'");
		}
		else
		{
		$insert_patient=mysqlInsert('trend_analysis',$arrFields,$arrValues);
		}
		$response="updated";
		header("Location:My-Patient-Details?p=".md5($patient_id));
}	


//ADD New Hospital
if(isset($_POST['add_new_hospital']))
{
	$hosp_name		=	addslashes($_POST['hosp_name']);	
	$hosp_email		=	addslashes($_POST['hosp_email']);
	$hosp_contact	=	addslashes($_POST['hosp_contact']);
	$hosp_city		=	addslashes($_POST['hosp_city']);
	$hosp_state		=	addslashes($_POST['hosp_state']);
	$hospAddress	=	addslashes($_POST['hospAddress']);
		
	$arrFields[] 	= 	'hosp_name';
	$arrValues[] 	= 	$hosp_name;
	$arrFields[] 	= 	'hosp_email';
	$arrValues[] 	= 	$hosp_email;
	$arrFields[] 	= 	'hosp_contact';
	$arrValues[] 	= 	$hosp_contact;
	$arrFields[] 	= 	'hosp_city';
	$arrValues[] 	= 	$hosp_city;
	$arrFields[] 	= 	'hosp_state';
	$arrValues[] 	= 	$hosp_state;
	$arrFields[] 	= 	'hosp_addrs';
	$arrValues[] 	= 	$hospAddress;
	$arrFields[] 	= 	'communication_status';
	$arrValues[] 	= 	"1";  
	
	$hospcreate	=	mysqlInsert('hosp_tab',$arrFields,$arrValues);
	$hosp_id 	= 	$hospcreate;

	if(!empty($admin_id))
	{
		$arrFields_docHosp[] = 'doc_id';
		$arrValues_docHosp[] = $admin_id;
	}
	if(!empty($hosp_id))
	{
		$arrFields_docHosp[] = 'hosp_id';
		$arrValues_docHosp[] = $hosp_id;
	}
	
	$dochospcreate=mysqlInsert('doctor_hosp',$arrFields_docHosp,$arrValues_docHosp);	
		
	$response="created-success";
	
	header("Location:".$_POST['page_name']."?response=".$response);
	
}
//Delete Doctor
if(isset($_GET['docid'])){
	
	mysqlDelete('doctor_out_referral',"doc_out_ref_id='".$_GET['docid']."'");
	$get_docInfo = mysqlSelect("*","doctor_out_referral","doc_id='".$admin_id."'","doctor_name asc","","","");

?>	
<div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Doctor </th>
										<th>Email Id</th>
										<th>Mobile</th>
                                        <th>City</th>  
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									if(count($get_docInfo)==0){
									?>
									<tr><td colspan="4" class="text-center">No records found</td></tr>	
									<?php
									}
									else
									{ 
									while(list($key, $val) = each($get_docInfo))
									{ 									
									?>
                                    <tr >
                                       
                                        <td><?php echo $val['doctor_name']; ?> </td>
										<td><?php echo $val['doctor_email']; ?></td>
                                        <td><?php echo $val['doctor_mobile']; ?></td> 
										<td><?php echo $val['doctor_city']; ?></td>										
                                       		<td><a href="javascript:void(0)" href="javascript:void(0)" onclick="return delDoctor(<?php echo $val['doc_out_ref_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
										<i class="fa fa-trash-o"></i> DELETE</a></td>
                                    </tr>
                                    <?php }
									}
									?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
</div>
			
<?php }
//ADD Doctors
if(isset($_POST['add_referout_doctor'])){
	$doc_name=$_POST['doc_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
	
	$address=$_POST['address'];
	$slctSpec=$_POST['slctSpec'];
		
	$arrFields_doctor[] = 'doctor_name';
	$arrValues_doctor[] = $doc_name;
	$arrFields_doctor[] = 'doctor_email';
	$arrValues_doctor[] = $txtemail;
	$arrFields_doctor[] = 'doctor_mobile';
	$arrValues_doctor[] = $mobile;
	$arrFields_doctor[] = 'doctor_city';
	$arrValues_doctor[] = $city;
	$arrFields_doctor[] = 'doc_specialization';
	$arrValues_doctor[] = $slctSpec;
	$arrFields_doctor[] = 'doc_address';
	$arrValues_doctor[] = $address;

	if(!empty($admin_id))
	{
		$arrFields_doctor[] = 'doc_id';
		$arrValues_doctor[] = $admin_id;
	}
	
	
	$doccreate=mysqlInsert('doctor_out_referral',$arrFields_doctor,$arrValues_doctor);	
		
	$response="created-success";

	header("Location:Refer-Out-Doctor?response=".$response);
	
}

//ADD Diagnostics
if(isset($_POST['add_diagno']) || isset($_POST['add_diagno_patient'])){
	$diagno_name=$_POST['diagno_name'];	
	$txtemail=$_POST['txtemail'];
	$mobile=$_POST['mobile'];
	$city=$_POST['city'];
	$password = randomPassword();
	$encypassword = md5($password);
		
	$arrFields_diagno[] = 'diagnosis_name';
	$arrValues_diagno[] = $diagno_name;
	$arrFields_diagno[] = 'diagnosis_email';
	$arrValues_diagno[] = $txtemail;
	$arrFields_diagno[] = 'diagnosis_contact_num';
	$arrValues_diagno[] = $mobile;
	$arrFields_diagno[] = 'diagnosis_city';
	$arrValues_diagno[] = $city;
	$arrFields_diagno[] = 'diagnosis_password';
	$arrValues_diagno[] = $encypassword;
	
	$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");

	$chkUser = mysqlSelect("*","Diagnostic_center","	diagnosis_email='".$txtemail."' or diagnosis_contact_num='".$mobile."'","","","","");
	if(count($chkUser)>0){
		$response="diagnostic-exists";
	   if(isset($_POST['add_diagno_patient'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Add-Diagnostics?response=".$response);
	}
	}
	else
	{
		$diagnocreate=mysqlInsert('Diagnostic_center',$arrFields_diagno,$arrValues_diagno);
	$diagno_id = $diagnocreate;

	if(!empty($diagno_id))
	{
		$arrFields_refer[] = 'diagnostic_id';
		$arrValues_refer[] = $diagno_id;
	}
	if(!empty($admin_id))
	{
		$arrFields_refer[] = 'doc_id';
		$arrValues_refer[] = $admin_id;
	}
	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields_refer[] = 'hosp_id';
		$arrValues_refer[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields_refer[] = 'company_id';
		$arrValues_refer[] = $getDocDetails[0]['company_id'];
	}
	$arrFields_refer[] = 'doc_type';
	$arrValues_refer[] = "1";
	
	$diagnocreate	=	mysqlInsert('doc_diagnostics',$arrFields_refer,$arrValues_refer);	
	
	$recoverLink	=	"Link: ".HOST_MAIN_URL."Diagnostic/login <br>User Name: ".$txtemail." / ".$mobile."<br>Password: ".$password;
			
	$message= stripslashes("<b>Congratulations!</b><br>You’ve been granted access to Medisense Diagnostic Software. <br><br> Please use below user name & password to login:<br><br>

	".$recoverLink."");
			
			
	$url_page = 'diagnostic_registration.php';
	$url .= rawurlencode($url_page);
	$url .= "?usermail=".urlencode($txtemail);
	$url .= "&username=".urlencode($diagno_name);
	$url .= "&message=".urlencode($message);
	$url .= "&reclink=".urlencode($recoverLink);
	send_mail($url);
		
	$response="created-success";
	
	if(isset($_POST['add_diagno_patient'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Add-Diagnostics?response=".$response);
	}
	}
}

//ADD Receptionist
if(isset($_POST['add_reception'])){
	$recepUSer=$_POST['recepUSer'];	
	$recepPasswd=md5($_POST['recepPasswd']);
			
	$arrFields_reception[] = 'secretary_username';
	$arrValues_reception[] = $recepUSer;
	$arrFields_reception[] = 'secretary_password';
	$arrValues_reception[] = $recepPasswd;
	
	$update_medicine=mysqlUpdate('referal',$arrFields_reception,$arrValues_reception,"ref_id='".$admin_id."'");
			
	$response="created-success";

	header("Location:Add-Receptionist?response=".$response);
	
}
//ADD Pharmacy
if(isset($_POST['add_pharma']) || isset($_POST['add_pharma_patient']))
{
	$pharma_name	=	$_POST['pharma_name'];	
	$txtemail		=	$_POST['txtemail'];
	$mobile			=	$_POST['mobile'];
	$city			=	$_POST['city'];
		
	$arrFields_pharma[] = 'pharma_name';
	$arrValues_pharma[] = $pharma_name;
	$arrFields_pharma[] = 'pharma_email';
	$arrValues_pharma[] = $txtemail;
	$arrFields_pharma[] = 'pharma_contact_num';
	$arrValues_pharma[] = $mobile;
	$arrFields_pharma[] = 'pharma_city';
	$arrValues_pharma[] = $city;
	
	$getDocDetails = mysqlSelect("c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");

	$pharmacreate=mysqlInsert('pharma',$arrFields_pharma,$arrValues_pharma);
	$pharma_id = $pharmacreate;

	if(!empty($pharma_id))
	{
		$arrFields_refer[] = 'pharma_id';
		$arrValues_refer[] = $pharma_id;
	}
	if(!empty($admin_id))
	{
		$arrFields_refer[] = 'doc_id';
		$arrValues_refer[] = $admin_id;
	}

	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields_refer[] = 'hosp_id';
		$arrValues_refer[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields_refer[] = 'company_id';
		$arrValues_refer[] = $getDocDetails[0]['company_id'];
	}
	$arrFields_refer[] = 'doc_type';
	$arrValues_refer[] = "1";

	
	$pharmarefer=mysqlInsert('doc_pharma',$arrFields_refer,$arrValues_refer);
		
	$response="created-success";
	if(isset($_POST['add_pharma_patient'])){
	header("Location:My-Patient-Details?p=".md5($_POST['patient_id'])."&response=".$response);
	}
	else{
	header("Location:Add-Pharmacy?response=".$response);
	}
}
	
//ADD Payment TransactionID
if(isset($_POST['add_button'])){
	$param=explode("-", $_POST['patient_name']);
	$patient_name=$param[0];
	$price=$_POST['price'];
	$txtNarration=$_POST['txtNarration'];
	$pay_mode=$_POST['pay_mode'];
		
	$arrFields_payment[] = 'patient_name';
	$arrValues_payment[] = $patient_name;
	$arrFields_payment[] = 'trans_date';
	$arrValues_payment[] = $curDate;
	$arrFields_payment[] = 'amount';
	$arrValues_payment[] = $price;

	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields_payment[] = 'hosp_id';
		$arrValues_payment[] = $_SESSION['login_hosp_id'];
	}
	if(!empty($admin_id))
	{
		$arrFields_payment[] = 'user_id';
		$arrValues_payment[] = $admin_id;
	}
	$arrFields_payment[] = 'narration';
	$arrValues_payment[] = $txtNarration;	
	
	$arrFields_payment[] = 'user_type';
	$arrValues_payment[] = "1";   //User type 1 for Prime Doctor,and 2 for non prime doctor

	$arrFields_payment[] = 'payment_status';
	$arrValues_payment[] = "PAID";

	$arrFields_payment[] = 'pay_method';
	$arrValues_payment[] = $pay_mode;
	
	$transactioncreate	=	mysqlInsert('payment_transaction',$arrFields_payment,$arrValues_payment);
	$patientid 			=   $transactioncreate;//mysqli_insert_id();  //Get Patient Id
	
	//Send Payment Receipt to patient
	if($_POST['chkReceipt']=="1")
	{
		$getDocDetails= mysqlSelect("ref_name","referal","ref_id='".$admin_id."'","","","","");
		$txtMob = $_POST['txtMobile'];					
		$recieptmsg= "Dear ".$patient_name.", We have successfully received your payment. Transaction Details :  Rs. ".$price." on ".date('d/M/Y, H:i a',strtotime($curDate)).". Thanks ".$getDocDetails[0]['ref_name'];

		send_msg($txtMob,$recieptmsg);
	}
	//Add user log
	$msg="Payment Transaction Added";
	$action="1"; //1 for payment transaction
	//userLog($admin_id,$userType,$msg,$platform,$action);	
	
	
	$response="transaction-success";
	header("Location:Payments?response=".$response);
}	
//REGISTER EVENT
if(isset($_POST['cmdReg']))
{

	$event_id 	= $_POST['event_id'];
	$doc_id 	= $_POST['doc_id'];
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[] = 'applicant_type';
	$arrValues[] = "2";

	if(!empty($event_id))
	{
		$arrFields[] = 'job_id';
		$arrValues[] = $event_id;
	}
	if(!empty($doc_id))
	{
		$arrFields[] = 'applicant_id';
		$arrValues[] = $doc_id;
	}
	
	$arrFields[] = 'type';
	$arrValues[] = "Event";

	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	
	$createJob=mysqlInsert('job_event_application',$arrFields,$arrValues);
	$getDocMail = mysqlSelect("b.contact_email as Contact_mail,a.company_name as OrgName","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = mysqlSelect("a.contact_person as Doc_Name,a.Email_id as Email_id,a.cont_num1 as Contact_num,b.spec_name as Specialization","our_partners as a left join specialization as b on a.specialisation=b.spec_id","a.partner_id='".$admin_id."'" ,"","","","");
	
	$getEventName	= mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	$id	= $getEventName; //mysqli_insert_id();
	
					$tomail=$getDocMail[0]['Contact_mail'];
					$userType="Premium User";
						$url_page = 'event_registration.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&eventtitle=" . urlencode($getEventName[0]['title']);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&usertype=" . urlencode($userType);
						send_mail($url);
						
					
				
		header("location:Offers?s=Events&id=".md5($event_id)."&response=event-success");
}
//APPLY JOB
if(isset($_POST['addJobRequest'])){

	$coverNote 	= addslashes($_POST['coverNote']);
	$event_id 	= $_POST['event_id'];
	$partner_id = $_POST['doc_id'];
	$attachment = basename($_FILES['txtAttach']['name']);
	
	
	$arrFields = array();
	$arrValues = array();

	if(!empty($partner_id))
	{
		$arrFields[] = 'applicant_id';
		$arrValues[] = $partner_id;
	}
	if(!empty($event_id))
	{
		$arrFields[] = 'job_id';
		$arrValues[] = $event_id;
	}
	
	
	$arrFields[] = 'applicant_type';
	$arrValues[] = "2";
	
	$arrFields[] = 'type';
	$arrValues[] = "Job";
	$arrFields[] = 'cover_note';
	$arrValues[] = $coverNote;
	$arrFields[] = 'resume';
	$arrValues[] = $attachment;
	$arrFields[] = 'TImestamp';
	$arrValues[] = $curDate;
	
	$createJob=mysqlInsert('job_event_application',$arrFields,$arrValues);
	$getDocMail = mysqlSelect("b.contact_email as Contact_mail,a.company_name as OrgName","compny_tab as a left join offers_events as b on a.company_id=b.company_id","b.event_id='".$event_id."'" ,"","","","");
	
	$getApplicantDetails = mysqlSelect("a.ref_name as Doc_Name,a.ref_mail as Email_id,a.contact_num as Contact_num,b.spec_name as Specialization","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'" ,"","","","");
	
	$getEventName= mysqlSelect("title","offers_events","event_id='".$event_id."'" ,"","","","");
	$id	= $getEventName;//mysqli_insert_id();
	/* Uploading image file */ 
				if(basename($_FILES['txtAttach']['name']!==""))
				{ 
					$folder_name	=	"Resume";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtAttach']['name'];
					$file_url		=	$_FILES['txtAttach']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
				
				}
				$downloadlink=HOST_MAIN_URL."Refer/download-Attachments.php?appid=".$id."&resume=".$attachment;
					$tomail=$getDocMail[0]['Contact_mail'];
					$userType="Premium User";
						$url_page = 'job_application_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?tomail=" . urlencode($tomail);
						$url .= "&jobtitle=" . urlencode($getEventName[0]['title']);
						$url .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$url .= "&contnum=" . urlencode($getApplicantDetails[0]['Contact_num']);
						$url .= "&email=" . urlencode($getApplicantDetails[0]['Email_id']);
						$url .= "&specialisation=" . urlencode($getApplicantDetails[0]['Specialization']);
						$url .= "&usertype=" . urlencode($userType);
						$url .= "&resumelink=" . urlencode($downloadlink);	
						$url .= "&covernote=" . urlencode($coverNote);				
								
						send_mail($url);
						
						//MAIL TO JOB APPLICANT
						if(!empty($getApplicantDetails[0]['Email_id'])){
						$url_page = 'job_event_application_success_mail.php';
						$userurl = rawurlencode($url_page);
						$userurl .= "?tomail=" . urlencode($getApplicantDetails[0]['Email_id']);
						$userurl .= "&username=" . urlencode($getApplicantDetails[0]['Doc_Name']);
						$userurl .= "&jobheading=" . urlencode($getEventName[0]['title']);
						$userurl .= "&orgname=" . urlencode($getDocMail[0]['OrgName']);
						send_mail($userurl);
						}
				
		header("location:offers.php?s=Jobs&id=".$_POST['id']."&response=job-success");
}	
 
//TURN TO DIRECT APPOINTMENT
if(isset($_POST['cmdAppt'])){
	
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$patientID."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
if(!empty($patientID))
{
	$arrFields2[]= 'patient_id'; 
	$arrValues2[]= $patientID;
}
if(!empty($txtRefId))
{
	$arrFields2[]= 'ref_id'; 
	$arrValues2[]= $txtRefId;
}


$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";
$arrFields2[]= 'conversion_status';
$arrValues2[]= "2";

if($chkRefInfo==true){
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$patientID."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "OP-DESIRED"
$arrValues1[]= "8";
$editPatientStatus=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patientID."'");

}

$chkPatInfo = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,TImestamp","patient_tab","patient_id='".$patientID."'","","","","");	
$get_pro = mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.ref_address as ref_address,a.doc_state as doc_state,a.doc_spec as doc_spec,a.doc_photo as doc_photo,c.hosp_name as hosp_name,d.company_name as company_name,d.email_id as CompEmail','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
						$url_page = 'Custom_Turn_to_Appointment.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['CompEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thx, ".$get_pro[0]['hosp_name'];
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']."has been sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();

					if(!empty($patientID))
					{
						$arrFields1[]= 'patient_id';
						$arrValues1[]= $patientID;
					}
					if(!empty($txtRefId))
					{
						$arrFields1[]= 'ref_id';
						$arrValues1[]= $txtRefId;
					}
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					if(!empty($admin_id))
					{
						$arrFields1[]= 'user_id';
						$arrValues1[]= $admin_id;
					}
				
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $curDate;
					
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$response="Appointment-Success";
					header("Location:patient-history?p=".md5($patientID)."&response=".$response);			
}

//SEND PAYMENT LINK
if(isset($_POST['cmdPay'])){
$txtRefId= $_POST['ref_id'];
$patientID= $_POST['patient_id'];

$trans_id=time(); //UPDATE TRANSACTION ID	
$arrFields = array();
$arrValues = array();
$arrFields[]= 'transaction_id';
$arrValues[]= $trans_id;
$arrFields[]= 'transaction_status';
$arrValues[]= "Pending";

$editPatient=mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'");


$chkDocStatus 	= mysqlSelect("status2","patient_referal","patient_id='".$_POST['patient_id']."'and ref_id='".$txtRefId."'","","","","");
$chkPatInfo 	= mysqlSelect("patient_id,patient_name,patient_mob,patient_email,pat_country,TImestamp","patient_tab","patient_id='".$_POST['patient_id']."'","","","","");	
$get_pro = mysqlSelect('a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.on_op_cost as on_op_cost,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,d.company_name as companyName,d.email_id as compEmail,d.company_logo as compLogo','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join compny_tab as d on d.company_id=c.company_id',"a.ref_id='".$txtRefId."'");
$getDepartment = mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
				
				if(!empty($get_pro[0]['on_op_cost']))
				{
						
					if(!empty($get_pro[0]['doc_photo']))
					{
						$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
					}	
					else
					{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
						
					$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getDepartment[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
					//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
					$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$get_pro[0]['ref_id'];
						
					$service="Second Opinion";
					if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']=="India")
					{ 
						//DOMESTIC PATIENT MAIL
						$opcost	=$get_pro[0]['on_op_cost'].".00";
						$paylink=HOST_HEALTH_URL."turn-to-pay.php?patid=".$_POST['patient_id']."&patname=".$chkPatInfo[0]['patient_name']."&mobile=".$chkPatInfo[0]['patient_mob']."&email=".$chkPatInfo[0]['patient_email']."&amount=".$opcost."&service=".$service."&docname=".$get_pro[0]['ref_name']."&docid=".$txtRefId;
						if($chkDocStatus[0]['status2']==5){ //IF DOCTOR ALREADY RESPONDED TO PATIENT QUERY THEN FOLLOWING PAYMENT MAIL WILL SEND TO PATIENT
							$url_page = 'Custom_Turn_to_Paylink.php';
						}
						else
						{
							$url_page = 'Custom_Turn_to_Paylink.php';
						}
								
								$url = rawurlencode($url_page);
								$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
								$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
								$url .= "&docimg=".urlencode($docimg);
								$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
								$url .= "&doclink=".urlencode($Link);
								$url .= "&regdate=" . urlencode($reg_date);
								$url .= "&paylink=".urlencode($paylink);
								$url .= "&docamount=".urlencode($get_pro[0]['on_op_cost']);
								$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
								$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
								$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
								$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
								$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
								$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
								$url .= "&ccmail=" . urlencode($ccmail);		
								
								send_mail($url);
						}
						else if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']!="India")
						{ //INTERNATIONAL PATIENT MAIL (PAYPAL LINK NEED TO BE SEND)
						
						
						$url_page = 'Custom_Non_Indian_paylink.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&hospname=" . urlencode($get_pro[0]['hosp_name']);
						$url .= "&compemail=" . urlencode($get_pro[0]['compEmail']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
						}
								
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = $get_pro[0]['ref_name']."-Action Required. We have sent you a mail. Please complete the action to get an opinion. Thanks, Medisensehealth.com";
					
					//send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Payment Link Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();

					if(!empty($_POST['patient_id']))
					{
						$arrFields1[]= 'patient_id';
						$arrValues1[]= $_POST['patient_id'];
					}
					if(!empty($get_pro[0]['ref_id']))
					{
						$arrFields1[]= 'ref_id';
						$arrValues1[]= $get_pro[0]['ref_id'];
					}
						
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= '0';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//SET PAYEMNT REMINDER TABLE
					$arrFields3 = array();
					$arrValues3 = array();

					if(!empty($_POST['patient_id']))
					{
						$arrFields3[]= 'patient_id';
						$arrValues3[]= $_POST['patient_id'];
					}

					if(!empty($get_pro[0]['ref_id']))
					{
						$arrFields3[]= 'doc_id';
						$arrValues3[]= $get_pro[0]['ref_id'];
					}
					
					$arrFields3[]= 'reminder_count';
					$arrValues3[]= '0';

					$arrFields3[]= 'payment_status';
					$arrValues3[]= '1';
					$arrFields3[]= 'TImestamp';
					$arrValues3[]= $Cur_Date;
					$inserReminder=mysqlInsert('payment_reminder',$arrFields3,$arrValues3);
					
					$Successmessage="Payment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
				
				}
				else
				{
					$errormessage="Error !!!! Please check this Expert Opinion Cost";
					
				}
}
	

//RESCHEDULE APPOINTMENT	
if($_POST['act']=="add-reschedule"){
	
	$visitDate = date('Y-m-d',strtotime($_POST['appDate']));
	$slctTime = $_POST['appTime'];
	
	$arrFields = array();
	$arrValues = array();
		
	
		$arrFields[] = 'Visiting_date';
		$arrValues[] = $visitDate;
		$arrFields[] = 'Visiting_time';
		$arrValues[] = $slctTime;
		
		$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['patTransId']."'");
		
		$checkTokenAppInfo = mysqlSelect("*","appointment_token_system","appoint_trans_id='".$_POST['patTransId']."'" ,"","","","");
		if(!empty($checkTokenAppInfo)){
			$getTiming = mysqlSelect("*","timings","Timing_id='".$slctTime."'","","","","");
			$arrFields_token[] = 'app_date';
			$arrValues_token[] = $visitDate;
			$arrFields_token[] = 'app_time';
			$arrValues_token[] = $getTiming[0]['Timing'];
		
		$patientRef=mysqlUpdate('appointment_token_system',$arrFields_token,$arrValues_token,"appoint_trans_id='".$_POST['patTransId']."'");
		}		
				
		$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['patTransId']."'" ,"","","","");	
		$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
		$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
		
		$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
			
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks";
	send_msg($mobile,$responsemsg);
	
}

if(isset($_GET['chkTime'])){
	$_SESSION['visit_time'] = $_GET['chkTime'];
	
}
if(isset($_GET['appointTypeChange']))
{
	$_SESSION['appointment_type'] = $_GET['appointTypeChange'];
	
}
	
if(isset($_POST['direct_appointment']))
{
	
	if(!empty($_POST['visit_date']) && !empty($_POST['visit_time']))
	{
		$chkInDate 		= $_POST['visit_date'];
		$chkInTime_slot = $_POST['visit_time'];
		$chkInTime 		= $_POST['visit_time_id'];
		$status="Pending";
	}
	else if(empty($_POST['visit_date']) && empty($_POST['visit_time']))
	{
		$status="At reception";
		$chkInDate 		= '';
		$chkInTime_slot = '';
		$chkInTime 		= '';
	}
	$txtName 		= 	$_POST['se_pat_name'];
	$txtAge 		= 	$_POST['se_pat_age'];
	$txtMail 		= 	$_POST['se_email'];
	$txtGen 		= 	$_POST['se_gender'];
	
	$txtContact 	= 	addslashes($_POST['se_con_per']);
	$txtMob 		= 	addslashes($_POST['se_phone_no']);
	$txtAddress 	= 	addslashes($_POST['se_address']);
	$txtLoc 		= 	addslashes($_POST['se_city']);
	$txtCountry		= 	addslashes($_POST['se_country']);
	$txtState 		= 	addslashes($_POST['se_state']);
	$docspec 		= 	addslashes($_SESSION['docspec']);
	$teleCom 		= 	0;//$_POST['chkTeleCom'];
	$patConsent 	= 	$_POST['chkPatConsent'];
	$transid		=	time();
	$txtRef_id 		=	addslashes($_POST['reference_from']);
	$txtRef_Hosp 	= 	addslashes($_POST['reference_hosp']);
	$txtRef_Doc 	= 	addslashes($_POST['refering_doc']);
	$refNoteAttach 	= 	addslashes($_FILES['txtReferalNote']['name']);
	$dob 			= 	date('Y-m-d',strtotime($_POST['date_birth']));
	$appointType 	= 	$_SESSION['appointment_type'];
	
	if($appointType == "2")
	{		 
		$status="VC Confirmed";
		$teleCom = 1;
	}
	
	$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$admin_id."'");
	
	if($_POST['ChildEmr'] == "1")
	{	
		$result = mysqlSelect('*','parents_tab',"primary_mobile_num='".$txtMob."'");
	}
	
	$arrFields_patient[] = 'patient_age';
	$arrValues_patient[] = $txtAge;

	$arrFields_patient[] = 'patient_email';
	$arrValues_patient[] = $txtMail;

	$arrFields_patient[] = 'patient_gen';
	$arrValues_patient[] = $txtGen;
	
	if(!empty($_POST['date_birth']))
	{
		$arrFields_patient[] = 'DOB';
		$arrValues_patient[] = $dob;
	}

	$arrFields_patient[] = 'patient_mob';
	$arrValues_patient[] = $txtMob;
	
	if(empty($_POST['patient_id']))
	{

		$arrFields_patient[] = 'patient_name';
		$arrValues_patient[] = $txtName;

		
		$arrFields_patient[] = 'member_id';
		$arrValues_patient[] = '';

		$arrFields_patient[] = 'login_id';
		$arrValues_patient[] = '';//$txtContact;

		$arrFields_patient[] = 'created_date';
		$arrValues_patient[] = $curDate;

		
		$patientcreate	=	mysqlInsert('patients_appointment',$arrFields_patient,$arrValues_patient); // doc_my_patient to " patients_appointment "
		$patientid 		= 	$patientcreate;
		$getPatInfo 	= 	mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");

		
	}	
	else
	{
		$patientid  = $_POST['patient_id'];
		$getPatInfo = mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
		$userupdate = mysqlUpdate('patients_appointment',$arrFields_patient,$arrValues_patient, "patient_id = '". $_POST['patient_id'] ."' ");
		
	}
	
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($patientid))
	{
		$arrFields1[] = 'patient_id';
		$arrValues1[] = $patientid;
	}

	if(!empty($appointType))
	{
		$arrFields1[] = 'service_type';
		$arrValues1[] = $appointType; 
	}
	
	if(!empty($transid))
	{
		$arrFields1[] = 'transaction_id';
		$arrValues1[] = $transid;
	}
	// if(!empty($transid))
	// {
	// 	$arrFields1[] = 'payment_id'; // empty
	// 	$arrValues1[] = '';
	// }

	if(!empty($admin_id))
	{
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $admin_id;
	}
	
	if(!empty($_SESSION['login_hosp_id']))
	{
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $_SESSION['login_hosp_id'];
	}
	

	$arrFields1[] = 'contact_person';
	$arrValues1[] = $txtName;

	$arrFields1[] = 'patient_age';
	$arrValues1[] = $txtAge;

	$arrFields1[] = 'address';
	$arrValues1[] = $txtAddress;

	$arrFields1[] = 'city';
	$arrValues1[] = $txtLoc; 

	$arrFields1[] = 'state';
	$arrValues1[] = $txtState;

	$arrFields1[] = 'country';
	$arrValues1[] = $txtCountry;

	$arrFields1[] = 'height_cms';
	$arrValues1[] = $txtHeight;

	$arrFields1[] = 'weight';
	$arrValues1[] = $txtWeight;

	$arrFields1[] = 'hyper_cond';
	$arrValues1[] = $txthypertension;

	$arrFields1[] = 'diabetes_cond';
	$arrValues1[] = $txtdiabetic;

	$arrFields1[] = 'smoking'; // smoking
	$arrValues1[] = '';//$smoking;

	$arrFields1[] = 'alcoholic';
	$arrValues1[] = '';//$alcoholic;

	$arrFields1[] = 'blood_group';
	$arrValues1[] = $txtBlood;

	$arrFields1[] = 'drug_abuse';
	$arrValues1[] = 'drug_abuse'; // empty 

	// $arrFields1[] = 'other_details';
	// $arrValues1[] = ''; // empty 

	// $arrFields1[] = 'family_history';
	// $arrValues1[] = ''; // empty 

	// $arrFields1[] = 'prev_intervention';
	// $arrValues1[] = ''; // empty 

	// $arrFields1[] = 'neuro_issue';
	// $arrValues1[] = ''; // empty 

	// $arrFields1[] = 'kidney_issue';
	// $arrValues1[] = ''; // empty 

	if(!empty($txtbp))
	{
		$arrFields1[] = 'pat_bp';
		$arrValues1[] = $txtbp; 
	}
	if(!empty($txtthyroid))
	{
		$arrFields1[] = 'pat_thyroid';
		$arrValues1[] = $txtthyroid;  
	}
	if(!empty($txtcholestrol))
	{
		$arrFields1[] = 'pat_cholestrole';
		$arrValues1[] = $txtcholestrol;
	}
	if(!empty($txtepilepsy))
	{
		$arrFields1[] = 'pat_epilepsy';
		$arrValues1[] = $txtepilepsy; 
	}
	if(!empty($txtxtasthamatbp))
	{
		$arrFields1[] = 'pat_asthama';
		$arrValues1[] = $txtxtasthamatbp;
	}
	if(!empty($txtallergies))
	{
		$arrFields1[] = 'allergies_any';
		$arrValues1[] = $txtallergies; 
	}
	
	

	// $arrFields1[] = 'subscriber_id'; // from subscriber table( empty )
	// $arrValues1[] = '';//$subscriber_id; 

	

	$arrFields1[] = 'user_type';
	$arrValues1[] = '1';

	

	$arrFields1[] = 'Visiting_date';
	$arrValues1[] = date('Y-m-d',strtotime($chkInDate));

	$arrFields1[] = 'Visiting_time';
	$arrValues1[] = $chkInTime;

	$arrFields1[] = 'time_slot';
	$arrValues1[] = $chkInTime_slot;

	// $arrFields1[] = 'amount';
	// $arrValues1[] = '';

	// $arrFields1[] = 'currency_type'; 
	// $arrValues1[] = '';

	$arrFields1[] = 'pay_status'; 
	$arrValues1[] = $status;

	$arrFields1[] = 'visit_status';
	$arrValues1[] = "new_visit";

	$arrFields1[] = 'patientEMR_consent';
	$arrValues1[] = $patConsent;
	if(!empty($txtRef_id))
	{
		$arrFields1[] = 'reference_id';
		$arrValues1[] = $txtRef_id;
	}
	

	$arrFields1[] = 'referring_hosp';
	$arrValues1[] = $txtRef_Hosp;

	$arrFields1[] = 'referring_doc';
	$arrValues1[] = $txtRef_Doc;

	if(!empty($_FILES['txtReferalNote']['name']))
	{
		$arrFields1[]="referal_note";
		$arrValues1[]=$refNoteAttach;
	}

	$arrFields1[] = "created_date";
	$arrValues1[] = $curDate;


	
	$createappointment=mysqlInsert('patients_transactions',$arrFields1,$arrValues1);

	$getTime	=	mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	$arrFieldsAppSlot = array();
	$arrValuesAppSlot = array();

	$arrFieldsAppSlot[] = 'patient_trans_id';
	$arrValuesAppSlot[] = $transid;
				
	if(empty($_POST['visit_date']) && empty($_POST['visit_time']))
	{
		//Check Last Appointment Token No
		$getLastAppInfo = mysqlSelect("*","patients_token_system"," patient_trans_id='".$transid."' and token_no!='555'" ,"token_no desc","","","");
		if(COUNT($getLastAppInfo)>0)
		{
			$getTokenNo = $getLastAppInfo[0]['token_no']+1;
		}
		else
		{
			$getTokenNo = 1;
		}
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = $getTokenNo;
	}
	else if(!empty($_POST['visit_date']) && !empty($_POST['visit_time']))
	{
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = "555"; //For Online Booking
	}

	$arrFieldsAppSlot[] = 'created_date';
	$arrValuesAppSlot[] = $curDate;

	$createappointment	=	mysqlUpdate('patients_token_system',$arrFieldsAppSlot,$arrValuesAppSlot,"patient_trans_id='".$_POST['trans_id']."'");



	$arrFieldsPat	=	array();
	$arrValuesPat	=	array();

	$arrFieldsPat[] = 'doc_video_link';
	$arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=1&r=".$admin_id."_".$getPatInfo[0]['patient_id']."_".$transid;				
	$arrFieldsPat[] = 'pat_video_link';
	$arrValuesPat[] = HOST_VIDEO_URL."index.php?ref_name=".$get_pro[0]['ref_name']."&pat_name=".$getPatInfo[0]['patient_name']."&type=2&r=".$admin_id."_".$getPatInfo[0]['patient_id']."_".$transid;
	
	$userupdate1	=	mysqlUpdate('patients_transactions',$arrFieldsPat,$arrValuesPat, "patient_id = '". $getPatInfo[0]['patient_id'] ."' ");

	if(basename($_FILES['txtReferalNote']['name']!==""))
	{ 
		$folder_name	=	"referalNoteAttach";
		$sub_folder		=	$patientid;
		$filename		=	$_FILES['txtReferalNote']['name'];
		$file_url		=	$_FILES['txtReferalNote']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	} 

	if($_POST['ChildEmr'] == "1")
	{
		$vaccine_start_date = $_POST['vaccine_start_date'];
		$txtMotherName = $_POST['se_mother_name'];
		$txtFatherName = $_POST['se_father_name'];
		$birth_date     = new DateTime($dob);
		$current_date   = new DateTime();

		$diff           = $birth_date->diff($current_date);
		$actualAge     = $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)";
			// Parent Table Data
		$arrFields_Parent = array();
		$arrValues_Parent = array();
		
		$arrFields_Parent[] = 'mother_name';
		$arrValues_Parent[] = $txtMotherName;
		
		$arrFields_Parent[] = 'father_name';
		$arrValues_Parent[] = $txtFatherName;
		
		$arrFields_Parent[] = 'primary_mobile_num';
		$arrValues_Parent[] = $txtMob;	
			
		$arrFields_Parent[] = 'email';
		$arrValues_Parent[] = $txtMail;
		
		$arrFields_Parent[] = 'address';
		$arrValues_Parent[] = $txtAddress;
			
		$arrFields_Parent[] = 'city';
		$arrValues_Parent[] = $txtLoc;
		
		$arrFields_Parent[] = 'state';
		$arrValues_Parent[] = $txtState;
		
		$arrFields_Parent[] = 'country';
		$arrValues_Parent[] = $txtCountry;
		if(!empty($admin_id))
		{
			$arrFields_Parent[] = 'doc_id';
			$arrValues_Parent[] = $admin_id;
		}
		
			
		$arrFields_Parent[] = 'f_sync'; //Forward Sync
		$arrValues_Parent[] = 1;
		
		$current_date = date("Y-m-d H:i:s");
			
		$arrFields_Parent[] = 'system_date';
		$arrValues_Parent[] = date('Y-m-d',strtotime($current_date));
			
		if($result == false)
		{
			$parentCreate=mysqlInsert('parents_tab',$arrFields_Parent,$arrValues_Parent);
			$pid= $parentCreate;
		}
		else if($result == true)
		{
			$userupdate=mysqlUpdate('parents_tab',$arrFields_Parent,$arrValues_Parent, "parent_id = '". $result[0]['parent_id']."' ");
			$pid = $result[0]['parent_id'];
		}
					
		// Child Table Data
		$arrFields_Child = array();
		$arrValues_Child = array();

		if(!empty($patientid))
		{
			$arrFields_Child[] = 'patient_id';
			$arrValues_Child[] = $patientid;
		}
		if(!empty($admin_id))
		{
			$arrFields_Child[] = 'doc_id';
			$arrValues_Child[] = $admin_id;
		}
		
		$arrFields_Child[] = 'actual_age';
		$arrValues_Child[] = $actualAge;

		$arrFields_Child[] = 'vaccine_start_date';
		$arrValues_Child[] = $vaccine_start_date;
		
		$arrFields_Child[] = 'f_sync';
		$arrValues_Child[] = 1;
			
		$arrFields_Child[] = 'system_entry_date';
		$arrValues_Child[] = $current_date;

		if(!empty($pid))
		{
			$arrFields_Child[] = 'parent_id';
			$arrValues_Child[] = $pid;
		}
		
		
			
		if(empty($_POST['patient_id']))
		{
			$childCreate=mysqlInsert('child_tab',$arrFields_Child,$arrValues_Child);
			$Childid= $childCreate;

			$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","1,11");
			//$getStartDate=date('Y-m-d',strtotime($vaccine_start_date));								
			foreach($vaccineduration as $vaccinedurationList)
			{
				if($vaccinedurationList['duartion_id']=="2"){
					//Add 6 weeks to DOB
					$add_days = 7*6;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="3"){
					//Add 10 weeks to DOB
					$add_days = 7*10;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="4"){
					//Add 14 weeks to DOB
					$add_days = 7*14;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="5"){
					//Add 18 weeks to DOB
					$add_days = 7*18;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="6"){
					$vaccine_date = date('Y-m-d', strtotime("+6 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="7"){
					$vaccine_date = date('Y-m-d', strtotime("+9 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="8"){
					$vaccine_date = date('Y-m-d', strtotime("+12 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="9"){
					$vaccine_date = date('Y-m-d', strtotime("+15 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="10"){
					$vaccine_date = date('Y-m-d', strtotime("+18 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="11"){
					$vaccine_date = date('Y-m-d', strtotime("+23 months", strtotime($vaccine_start_date)));
				}
								
				//Insert Vaccine notification table	
				if($vaccine_date!="0000-00-00")
				{
					$arrFields_Notify = array();
					$arrValues_Notify = array();
					
					$arrFields_Notify[] = 'vaccine_date';
					$arrValues_Notify[] = $vaccine_date;
					$arrFields_Notify[] = 'child_id';
					$arrValues_Notify[] = $Childid;
					$insertVaccineNotify=mysqlInsert('vaccine_notification',$arrFields_Notify,$arrValues_Notify);	
				}	
			}
		}
		else
		{
			$userupdate=mysqlUpdate('child_tab',$arrFields_Child,$arrValues_Child, "patient_id = '". $_POST['patient_id'] ."' ");
			$result1 = mysqlSelect('*','child_tab',"patient_id = '". $_POST['patient_id'] ."'");
			$Childid= $result1[0]['child_id'];
		
			mysqlDelete('vaccine_notification',"child_id='".$Childid."'");	
			//Update Next 10 vaccine dates
			$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","1,11");
								
			foreach($vaccineduration as $vaccinedurationList)
			{
				if($vaccinedurationList['duartion_id']=="2"){
					//Add 6 weeks to DOB
					$add_days = 7*6;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="3"){
					//Add 10 weeks to DOB
					$add_days = 7*10;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="4"){
					//Add 14 weeks to DOB
					$add_days = 7*14;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="5"){
					//Add 18 weeks to DOB
					$add_days = 7*18;
					$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
				}
				else if($vaccinedurationList['duartion_id']=="6"){
					$vaccine_date = date('Y-m-d', strtotime("+6 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="7"){
					$vaccine_date = date('Y-m-d', strtotime("+9 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="8"){
					$vaccine_date = date('Y-m-d', strtotime("+12 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="9"){
					$vaccine_date = date('Y-m-d', strtotime("+15 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="10"){
					$vaccine_date = date('Y-m-d', strtotime("+18 months", strtotime($vaccine_start_date)));
				}
				else if($vaccinedurationList['duartion_id']=="11"){
					$vaccine_date = date('Y-m-d', strtotime("+23 months", strtotime($vaccine_start_date)));
				}
				
					//Insert Vaccine notification table	
					if($vaccine_date!="0000-00-00")
					{
						$arrFields_Notify = array();
						$arrValues_Notify = array();
						
						$arrFields_Notify[] = 'vaccine_date';
						$arrValues_Notify[] = $vaccine_date;
						$arrFields_Notify[] = 'child_id';
						$arrValues_Notify[] = $Childid;
						$insertVaccineNotify=mysqlInsert('vaccine_notification',$arrFields_Notify,$arrValues_Notify);	
					}	
			}
		}
	}
	
	//Patient Info EMAIL notification Sent to Doctor
	if(!empty($get_pro[0]['ref_mail']))
	{
		$PatAddress=$getPatInfo[0]['patient_addrs'].",<br>".$getPatInfo[0]['patient_loc'].", ".$getPatInfo[0]['pat_state'].", ".$getPatInfo[0]['pat_country'];
	
		$url_page = 'pat_appointment_info.php';
		$url = rawurlencode($url_page);
		$url .= "?patname=".urlencode($getPatInfo[0]['patient_name']);
		$url .= "&patID=".urlencode($getPatInfo[0]['patient_id']);
		$url .= "&patAddress=".urlencode($PatAddress);
		$url .= "&patContact=".urlencode($getPatInfo[0]['patient_mob']);
		$url .= "&patEmail=".urlencode($getPatInfo[0]['patient_email']);
		$url .= "&patContactName=" . urlencode($getPatInfo[0]['contact_person']);
		$url .= "&prefDate=" . urlencode(date('d M Y',strtotime($chkInDate)));
		$url .= "&prefTime=" . urlencode($getTime[0]['Timing']);
		$url .= "&docname=" . urlencode($get_pro[0]['ref_name']);
		$url .= "&docmail=" . urlencode($get_pro[0]['ref_mail']);
		$url .= "&ccmail=" . urlencode($ccmail);	
		$url .= "&replymail=" . urlencode($getPatInfo[0]['patient_email']);						
		send_mail($url);	
	}

	//Save for Appointment Payment Transaction
	if(!empty($_POST['consult_charge']))
	{
		$arrFieldsPayment=array();	
		$arrValuesPayment=array();
						
		$arrFieldsPayment[]='patient_name';
		$arrValuesPayment[]=$getPatInfo[0]['patient_name'];
		if(!empty($getPatInfo[0]['patient_id']))
		{
			$arrFieldsPayment[]='patient_id';
			$arrValuesPayment[]=$getPatInfo[0]['patient_id'];
		}
		
		$arrFieldsPayment[]='trans_date';
		$arrValuesPayment[]=$curDate;
		$arrFieldsPayment[]='narration';
		$arrValuesPayment[]="Consultation Charge";
		$arrFieldsPayment[]='amount';
		$arrValuesPayment[]=$_POST['consult_charge'];

		if(!empty($admin_id))
		{
			$arrFieldsPayment[]='user_id';
			$arrValuesPayment[]=$admin_id;
		}

		
		$arrFieldsPayment[]='user_type';
		$arrValuesPayment[]="1";

		if(!empty($_SESSION['login_hosp_id']))
		{
			$arrFieldsPayment[]='hosp_id';
			$arrValuesPayment[]=$_SESSION['login_hosp_id'];
		}

		if(!empty($transid))
		{
			$arrFieldsPayment[] = 'appoint_trans_id';
			$arrValuesPayment[] = $transid;	
		}

		
		$arrFieldsPayment[]='payment_status';
		$arrValuesPayment[]="PAID";
		$arrFieldsPayment[]='pay_method';
		$arrValuesPayment[]="Cash";
				 
		$insert_pay_transaction= mysqlInsert('payment_transaction',$arrFieldsPayment,$arrValuesPayment);
	}
	//Save for Appointment Payment Transaction ends here
					
	//Send SMS to patient
	$longurl = "/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	//$link = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($getPatInfo[0]['patient_id']);
	
	//Get Shorten Url
	//$getUrl= get_shorturl($longurl);	
	$patient_profile_link = HOST_MAIN_URL."premium/Patient-Profile-Details?d=" . md5($admin_id)."&p=" . md5($getPatInfo[0]['patient_id'])."&t=".$transid;
	
	//$msg = "Appointment Confirmed - if you have any reports upload here ".$link." - Thank you";
	//$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with (".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". If you have any reports, upload here:".$getUrl." Thanks";
	if($appointType == "0")
	{		 
	$msg= "Hello ".$getPatInfo[0]['patient_name']." , Your token number for visit with ".$get_pro[0]['ref_name']." is ".$getTokenNo." .While you await your turn you can click the link ".$patient_profile_link." to view/update/upload your medical details. \nThanks, ".$_SESSION['login_hosp_name'];
	
	}
	else
	{
		$msg= "Hello ".$getPatInfo[0]['patient_name']." Your appointment with Dr.".$get_pro[0]['ref_name']." is confirmed on ".date('d M Y',strtotime($chkInDate))." at ".$getTime[0]['Timing'].". To view/update/upload your medical details or reports click here ".$patient_profile_link." \nThanks, ".$_SESSION['login_hosp_name'];
																																																																						   
	}
	send_msg($txtMob,$msg);
	
	//Send Payment Receipt to patient
	if($_POST['chkReceipt']=="1")
	{
		$recieptmsg= "Dear ".$getPatInfo[0]['patient_name'].", We have successfully received your payment. Transaction Details :  Rs. ".$_POST['consult_charge']." on ".date('d/M/Y, H:i a',strtotime($curDate)).". Thanks ".$get_pro[0]['ref_name'];

		send_msg($txtMob,$recieptmsg);
	}
	
	$checkAppLink = mysqlSelect("login_id","login_user","sub_contact='".$txtMob."'" ,"","","","");
	if(count($checkAppLink)==0)
	{			
		$offlineMsg="Welcome to Medisense Healthcare App. Download the patient app Now! \n Download link - https://goo.gl/u8P5us \n Thanks Medisense";
		send_msg($txtMob,$offlineMsg);	
	}
	
	unset($_SESSION['visit_date']);	
	unset($_SESSION['visit_time']);
	$response="appointment-success";
	header("Location:Appointments?response=".$response);

	
}

if(isset($_POST['reschedule_appointment']))
{
	
	$chkInDate 		= '';
	$chkInTime 		= '';
	$chkInTime_slot	= '';
	if(isset($_POST['visit_date']) && isset($_POST['visit_time']))
	{
		
		$chkInDate 		= $_POST['visit_date'];
		$chkInTime 		= $_POST['visit_time_id'];
		$chkInTime_slot	= $_POST['visit_time'];
	
		$status="Pending";
	}
	else if(empty($_POST['visit_date']) && empty($_POST['visit_time']))
	{
		
		$status="At reception";
	}
	
	$txtName 	= $_POST['se_pat_name'];
	$txtAge 	= $_POST['se_pat_age'];
	$txtMail 	= $_POST['se_email'];
	$txtGen		= $_POST['se_gender'];
	$txtContact = addslashes($_POST['se_con_per']);
	$txtMob 	= addslashes($_POST['se_phone_no']);
	$txtAddress = addslashes($_POST['se_address']);
	$txtLoc 	= addslashes($_POST['se_city']);
	$txtCountry = addslashes($_POST['se_country']);
	$txtState 	= addslashes($_POST['se_state']);
	$docspec 	= addslashes($_SESSION['docspec']);
	$teleCom 	= 0;//$_POST['chkTeleCom'];
	$patConsent = $_POST['chkPatConsent'];
	$transid	= time();
	$txtRef_id 	= addslashes($_POST['reference_from']);
	$txtRef_Hosp= addslashes($_POST['reference_hosp']);
	$txtRef_Doc = addslashes($_POST['refering_doc']);
	$refNoteAttach = addslashes($_FILES['txtReferalNote']['name']);
	$dob 		= date('Y-m-d',strtotime($_POST['date_birth']));												  
	$appointType= $_SESSION['appointment_type'];
	$service_type= $_POST['service_type'];
	$hosp_id	= $_POST['hosp_id'];
	
	
	if($appointType == "2")
	{		 
		$status="VC Confirmed";
		$teleCom = 1;
	}
	//echo $status;
	$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$admin_id."'");
	if($_POST['ChildEmr'] == "1")
	{	
		$result = mysqlSelect('*','parents_tab',"primary_mobile_num='".$txtMob."'");
	}
	$arrFields_patient[] = 'patient_email';
	$arrValues_patient[] = $txtMail;

	$arrFields_patient[] = 'patient_gender';
	$arrValues_patient[] = $txtGen;
	
	if(!empty($_POST['date_birth']))
	{
		$arrFields_patient[] = 'patient_dob';
		$arrValues_patient[] = $dob;
	}

	$arrFields_patient[] = 'patient_mobile';
	$arrValues_patient[] = $txtMob;
	if(empty($_POST['patient_id']))
	{

		$arrFields_patient[] = 'patient_name';
		$arrValues_patient[] = $txtName;

		
		$arrFields_patient[] = 'member_id';
		$arrValues_patient[] = '';

		$arrFields_patient[] = 'login_id';
		$arrValues_patient[] = '';//$txtContact;

		$arrFields_patient[] = 'created_date';
		$arrValues_patient[] = $curDate;

		
		$patientcreate=mysqlInsert('patients_appointment',$arrFields_patient,$arrValues_patient); // doc_my_patient to " patients_appointment "
		$patientid = $patientcreate;
		$getPatInfo = mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
		
		
	}	
	else
	{
		$patientid = $_POST['patient_id'];
		$getPatInfo = mysqlSelect("*","patients_appointment","patient_id='".$patientid."'" ,"","","","");
		$userupdate = mysqlUpdate('patients_appointment',$arrFields_patient,$arrValues_patient, "patient_id = '". $_POST['patient_id'] ."' ");
		$patient_trans_id=$userupdate;

	}
	
	
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($patientid))
	{
		$arrFields1[] = 'patient_id';
		$arrValues1[] = $patientid;
	}
	
	if(!empty($_POST['trans_id']))
	{
		$arrFields1[] = 'transaction_id';
		$arrValues1[] = $_POST['trans_id'];
	}
	
	

	$arrFields1[] = 'service_type';
	$arrValues1[] = $appointType;

	

	// $arrFields1[] = 'payment_id';
	// $arrValues1[] = '';

	if(!empty($admin_id))
	{
		$arrFields1[] = 'doc_id';
		$arrValues1[] = $admin_id;
	}

	if(!empty($hosp_id))
	{
		$arrFields1[] = 'hosp_id';
		$arrValues1[] = $hosp_id;
	}

	$arrFields1[] = 'contact_person';
	$arrValues1[] = $txtContact;

	$arrFields1[] = 'patient_age';
	$arrValues1[] = $txtAge;

	$arrFields1[] = 'address';
	$arrValues1[] = $txtAddress;

	$arrFields1[] = 'city';
	$arrValues1[] = $txtLoc;

	$arrFields1[] = 'state';
	$arrValues1[] = $txtState;

	$arrFields1[] = 'country';
	$arrValues1[] = $txtCountry;
	
	// $arrFields1[] = 'height_cms';
	// $arrValues1[] = '';

	// $arrFields1[] = 'weight';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'hyper_cond';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'diabetes_cond';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'smoking';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'alcoholic';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'blood_group';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'pat_bp';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'pat_thyroid';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'pat_cholestrole';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'pat_epilepsy';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'pat_asthama';
	// $arrValues1[] = '';
	
	// $arrFields1[] = 'allergies_any';
	// $arrValues1[] ='';

	// $arrFields1[] = 'subscriber_id';
	// $arrValues1[] ='';

	// $arrFields1[] = 'doc_video_link';
	// $arrValues1[] ='';
	
	// $arrFields1[] = 'pat_video_link';
	// $arrValues1[] ='';

	// $arrFields1[] = 'doc_agora_link';
	// $arrValues1[] ='';

	// $arrFields1[] = 'pat_agora_link';
	// $arrValues1[] ='';


	$arrFields1[] = 'user_type';
	$arrValues1[] =	'1';

	$arrFields1[] = 'Visiting_date';
	$arrValues1[] = date('Y-m-d',strtotime($chkInDate));

	$arrFields1[] = 'Visiting_time';
	$arrValues1[] = $chkInTime;

	$arrFields1[] = 'time_slot';
	$arrValues1[] = $chkInTime_slot;

	// $arrFields1[] = 'amount';
	// $arrValues1[] = '';

	// $arrFields1[] = 'currency_type'; 
	// $arrValues1[] = '';

	$arrFields1[] = 'pay_status'; 
	$arrValues1[] = $status;

	$arrFields1[] = 'visit_status';
	$arrValues1[] = "new_visit";

	$arrFields1[] = 'patientEMR_consent';
	$arrValues1[] = $patConsent;

	if(!empty($txtRef_id))
	{
		$arrFields1[] = 'reference_id';
		$arrValues1[] = $txtRef_id;
	}
	

	$arrFields1[] = 'referring_hosp';
	$arrValues1[] = $txtRef_Hosp;

	$arrFields1[] = 'referring_doc';
	$arrValues1[] = $txtRef_Doc;

	if(!empty($_FILES['txtReferalNote']['name']))
	{
		$arrFields1[]="referal_note";
		$arrValues1[]=$refNoteAttach;
	}

	$arrFields1[] = "created_date";
	$arrValues1[] = $curDate;

	$createappointment=mysqlUpdate('patients_transactions',$arrFields1,$arrValues1,"patient_id='".$patientid."'");

	
	$getTime	=	mysqlSelect('*','timings',"Timing_id='".$chkInTime."'");
	
	$arrFieldsAppSlot = array();
	$arrValuesAppSlot = array();

	$arrFieldsAppSlot[] = 'patient_trans_id';
	$arrValuesAppSlot[] = $_POST['trans_id'];
				
	if(empty($_POST['visit_date']) && empty($_POST['visit_time']))
	{
		//Check Last Appointment Token No

		$getLastAppInfo = mysqlSelect("*","patients_token_system"," patient_trans_id='".$_POST['trans_id']."' and token_no!='555'" ,"token_no desc","","","");
		if(COUNT($getLastAppInfo)>0)
		{
			$getTokenNo = $getLastAppInfo[0]['token_no']+1;
		}
		else
		{
			$getTokenNo = 1;
		}
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = $getTokenNo;
	}
	else if(!empty($_POST['visit_date']) && !empty($_POST['visit_time']))
	{
		$arrFieldsAppSlot[] = 'token_no';
		$arrValuesAppSlot[] = "555"; //For Online Booking
	}

	$arrFieldsAppSlot[] = 'created_date';
	$arrValuesAppSlot[] = $curDate;

	$createappointment	=	mysqlUpdate('patients_token_system',$arrFieldsAppSlot,$arrValuesAppSlot,"patient_trans_id='".$_POST['trans_id']."'");
	
	if($_POST['ChildEmr'] == "1")
	{
		$vaccine_start_date = $_POST['vaccine_start_date'];
		$txtMotherName 		= $_POST['se_mother_name'];
		$txtFatherName 		= $_POST['se_father_name'];
		$birth_date     	= new DateTime($dob);
		$current_date   	= new DateTime();
		$diff           	= $birth_date->diff($current_date);
		$actualAge    		= $diff->y . " years " . $diff->m . " months " . $diff->d . " day(s)";
			// Parent Table Data
		$arrFields_Parent = array();
		$arrValues_Parent = array();
	
		$arrFields_Parent[] = 'mother_name';
		$arrValues_Parent[] = $txtMotherName;
		
		$arrFields_Parent[] = 'father_name';
		$arrValues_Parent[] = $txtFatherName;
		
		$arrFields_Parent[] = 'primary_mobile_num';
		$arrValues_Parent[] = $txtMob;	
			
		$arrFields_Parent[] = 'email';
		$arrValues_Parent[] = $txtMail;
		
		$arrFields_Parent[] = 'address';
		$arrValues_Parent[] = $txtAddress;
		
		$arrFields_Parent[] = 'city';
		$arrValues_Parent[] = $txtLoc;
	
		$arrFields_Parent[] = 'state';
		$arrValues_Parent[] = $txtState;
		
		$arrFields_Parent[] = 'country';
		$arrValues_Parent[] = $txtCountry;

		if(!empty($admin_id))
		{
			$arrFields_Parent[] = 'doc_id';
			$arrValues_Parent[] = $admin_id;
		}
		
		$arrFields_Parent[] = 'f_sync'; //Forward Sync
		$arrValues_Parent[] = 1;
		
		$current_date = date("Y-m-d H:i:s");
	
		$arrFields_Parent[] = 'system_date';
		$arrValues_Parent[] = date('Y-m-d',strtotime($current_date));
	
		if($result == false)
		{
			$parentCreate	=	mysqlInsert('parents_tab',$arrFields_Parent,$arrValues_Parent);
			$pid= $parentCreate;
		}
		else if($result == true)
		{
			$userupdate	=	mysqlUpdate('parents_tab',$arrFields_Parent,$arrValues_Parent, "parent_id = '". $result[0]['parent_id']."' ");
			$pid = $result[0]['parent_id'];
		}
		
		// Child Table Data
		$arrFields_Child = array();
		$arrValues_Child = array();

		if(!empty($patientid))
		{
			$arrFields_Child[] = 'patient_id';
			$arrValues_Child[] = $patientid;
		}

		if(!empty($admin_id))
		{
			$arrFields_Child[] = 'doc_id';
			$arrValues_Child[] = $admin_id;
		}


		$arrFields_Child[] = 'actual_age';
		$arrValues_Child[] = $actualAge;

		$arrFields_Child[] = 'vaccine_start_date';
		$arrValues_Child[] = $vaccine_start_date;
		
		$arrFields_Child[] = 'f_sync';
		$arrValues_Child[] = 1;
		
		$arrFields_Child[] = 'system_entry_date';
		$arrValues_Child[] = $current_date;
		
		$arrFields_Child[] = 'parent_id';
		$arrValues_Child[] = $pid;
		
		$userupdate	=	mysqlUpdate('child_tab',$arrFields_Child,$arrValues_Child, "patient_id = '". $_POST['patient_id'] ."' ");
		$result1 	= 	mysqlSelect('*','child_tab',"patient_id = '". $_POST['patient_id'] ."'");
		$Childid	= 	$result1[0]['child_id'];
		
		mysqlDelete('vaccine_notification',"child_id='".$Childid."'");	
		//Update Next 10 vaccine dates
		$vaccineduration = mysqlSelect("*","vaccine_duration","","duartion_id asc","","","1,11");
						
		foreach($vaccineduration as $vaccinedurationList)
		{
			if($vaccinedurationList['duartion_id']=="2")
			{
				//Add 6 weeks to DOB
				$add_days = 7*6;
				$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
			}
			else if($vaccinedurationList['duartion_id']=="3")
			{
				//Add 10 weeks to DOB
				$add_days = 7*10;
				$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
			}
			else if($vaccinedurationList['duartion_id']=="4")
			{
				//Add 14 weeks to DOB
				$add_days = 7*14;
				$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
			}
			else if($vaccinedurationList['duartion_id']=="5")
			{
				//Add 18 weeks to DOB
				$add_days = 7*18;
				$vaccine_date = date('Y-m-d',strtotime($vaccine_start_date) + (24*3600*$add_days));
			}
			else if($vaccinedurationList['duartion_id']=="6")
			{
				$vaccine_date = date('Y-m-d', strtotime("+6 months", strtotime($vaccine_start_date)));
			}
			else if($vaccinedurationList['duartion_id']=="7")
			{
				$vaccine_date = date('Y-m-d', strtotime("+9 months", strtotime($vaccine_start_date)));
			}
			else if($vaccinedurationList['duartion_id']=="8")
			{
				$vaccine_date = date('Y-m-d', strtotime("+12 months", strtotime($vaccine_start_date)));
			}
			else if($vaccinedurationList['duartion_id']=="9")
			{
				$vaccine_date = date('Y-m-d', strtotime("+15 months", strtotime($vaccine_start_date)));
			}
			else if($vaccinedurationList['duartion_id']=="10")
			{
				$vaccine_date = date('Y-m-d', strtotime("+18 months", strtotime($vaccine_start_date)));
			}
			else if($vaccinedurationList['duartion_id']=="11")
			{
				$vaccine_date = date('Y-m-d', strtotime("+23 months", strtotime($vaccine_start_date)));
			}
		
			//Insert Vaccine notification table	
			if($vaccine_date!="0000-00-00")
			{
				$arrFields_Notify = array();
				$arrValues_Notify = array();
				
				$arrFields_Notify[] = 'vaccine_date';
				$arrValues_Notify[] = $vaccine_date;
				$arrFields_Notify[] = 'child_id';
				$arrValues_Notify[] = $Childid;
				$insertVaccineNotify=mysqlInsert('vaccine_notification',$arrFields_Notify,$arrValues_Notify);	
			}	
		}
	
	}

	if(basename($_FILES['txtReferalNote']['name']!==""))
	{ 

		$folder_name	=	"referalNoteAttach";
		$sub_folder		=	$patientid;
		$filename		=	$_FILES['txtReferalNote']['name'];
		$file_url		=	$_FILES['txtReferalNote']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
	} 
	//Message to Patient	
	$mobile		 =	$txtMob;	
	$responsemsg = "Dear ".$getPatInfo[0]['patient_name'].", your appointment with ".$get_pro[0]['ref_name']." has been rescheduled for ".date('d M Y',strtotime($chkInDate))." / ".$getTime[0]['Timing']."- Thanks ".$_SESSION['login_hosp_name'];
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	unset($_SESSION['visit_date']);	
	unset($_SESSION['visit_time']);
	$response="appointment-success";
	header("Location:Appointments?response=".$response);

	
}

if(isset($_POST['act'])){
	$getPatInfo = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patientid']."'" ,"","","","");
	
		
	//Send SMS to patient
	$longurl = "/premium/Patient-Attachments?d=" . md5($_POST['patientid']);
	//$longurl = "https://medisensecrm.com/premium/Patient-Attachments?d=" . md5($_POST['patientid']);
	//Get Shorten Url
	$getUrl= get_shorturl($longurl);	
	$txtMob = $_POST['userMobile'];
	$msg = "Hello ".$getPatInfo[0]['patient_name']." - If you have any medical reports upload here ".$getUrl." - Thank you";
	send_msg($txtMob,$msg);
	
}	
	
//Add Blog Post
if(isset($_POST['cmdBlg']))
{
	$blogTitle	= addslashes($_POST['blog_title']);
	$txtRefId	= $admin_id;
	$blogDesc	= addslashes($_POST['descr']);
	$blog_pic 	= basename($_FILES['txtPhoto']['name']);
	$postkey=time();
	$logintype="doc";
	$view_user = $_POST['se_visible'];
	
	
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $blogTitle;
	if(!empty($admin_id))
	{
		$arrFields[]= 'Login_User_Id';
		$arrValues[]= $admin_id;
	}
	
	$arrFields[]= 'post_description';
	$arrValues[]= $blogDesc;
	$arrFields[]= 'post_type';
	$arrValues[]= "blog";
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;

	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields[]= 'company_id';
		$arrValues[]= $getDocDetails[0]['company_id'];
	}

	if(!empty($getDocDetails[0]['hosp_id']))
	{
		$arrFields1[]= 'hosp_id';
		$arrValues1[]= $getDocDetails[0]['hosp_id'];
	}

	
	
	$arrFields[]= 'post_date';
	$arrValues[]= $curDate;
	$arrFields[]= 'post_image';
	$arrValues[]= $blog_pic;
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $blog_id;
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Blog";
	
	// $arrFields1[]= 'company_id';
	// $arrValues1[]= $getDocDetails[0]['company_id'];
	$arrFields1[]= 'view_user';
	$arrValues1[]= $view_user;
	if($view_user==2)
	{
		$arrFields1[]= 'default_blog';
		$arrValues1[]= "0";
	}
	else{
		$arrFields1[]= 'default_blog';
		$arrValues1[]= "1";
	}
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$blogTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	$arrFields_search[]= 'type_id';
	$arrValues_search[]= $blog_id;
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Blog";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	/* Uploading image file */ 
	if(basename($_FILES['txtPhoto']['name']!==""))
	{ 
		
		$folder_name	=	"Postimages";
		$sub_folder		=	$blog_id;
		$filename		=	$_FILES['txtPhoto']['name'];
		$file_url		=	$_FILES['txtPhoto']['tmp_name'];
		fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
		
		
	}
	
	header("Location:Blog-Surgical-List");
}	
//ADD SURGICAL VIDEO	
if(isset($_POST['video_publish'])){
	
	$videoTitle= addslashes($_POST['video_title']);
	$txtRefId= $admin_id;
	$videoUrl= $_POST['video_link'];
	$videoDesc= addslashes($_POST['video_Description']);
	$postkey=time();
	$getCode  = str_replace("https://www.youtube.com/watch?v=", "", $videoUrl);
	$mainDesc='<p>'.$videoDesc.'</p>';
	
	$getDocDetails = mysqlSelect("a.ref_id as ref_id,b.hosp_id as hosp_id,c.company_id as company_id","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'","","","","");
	$loginid=$txtRefId;
	$logintype="doc";
	
	
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'postkey';
	$arrValues[]= $postkey;
	$arrFields[]= 'post_tittle';
	$arrValues[]= $videoTitle;
	$arrFields[]= 'Login_User_Id';
	$arrValues[]= $admin_id;
	$arrFields[]= 'post_description';
	$arrValues[]= $mainDesc;
	$arrFields[]= 'video_url';
	$arrValues[]= $videoUrl;
	$arrFields[]= 'video_id';
	$arrValues[]= $getCode;
	$arrFields[]= 'Login_User_Type';
	$arrValues[]= $logintype;
	$arrFields[]= 'post_type';
	$arrValues[]= "surgical";
	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields[]	= 'company_id';
		$arrValues[]	= $getDocDetails[0]['company_id'];
	}
	if(!empty($getDocDetails[0]['hosp_id']))
	{
		$arrFields[]= 'hosp_id';
		$arrValues[]= $getDocDetails[0]['hosp_id'];
	}
	$arrFields[]	= 'post_date';
	$arrValues[]	= $curDate;
	
	$addblogs=mysqlInsert('home_posts',$arrFields,$arrValues);
	$blog_id= $addblogs;
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($blog_id))
	{
		$arrFields1[]= 'listing_type_id';
		$arrValues1[]= $blog_id;
	}

	
	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Surgical";

	if(!empty($getDocDetails[0]['company_id']))
	{
		$arrFields1[]= 'company_id';
		$arrValues1[]= $getDocDetails[0]['company_id'];
	}

	if(!empty($getDocDetails[0]['hosp_id']))
	{
		$arrFields1[]= 'hosp_id';
		$arrValues1[]= $getDocDetails[0]['hosp_id'];
	}
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	
	$searchTags=$_POST['searchTags'].",".$videoTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	if(!empty($blog_id))
	{
		$arrFields_search[]= 'type_id';
		$arrValues_search[]= $blog_id;
	}
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Surgical";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	
	header("Location:Blog-Surgical-List");

}	
	
	
	
//CHANGE PASSWORD 
if(isset($_POST['change_password'])){
	 
	 $txtPass = md5($_POST['new_password']);
	 $txtRePass = md5($_POST['retype_password']);
	
	$result = mysqlSelect('ref_id','referal',"ref_id='".$_POST['Prov_Id']."'");
	if($txtPass==$txtRePass){
	
		
		$arrFields = array();
		$arrValues = array();		
		
		$arrFields[] = 'doc_password';
		$arrValues[] = $txtPass;
		$arrFields[] = 'password_recovery';
		$arrValues[] = "0";
		
		
		$editrecord=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
		
						
		header('location:Password?response=password');
	}
	else
	{
		header('location:Password?response=error-password');	
	}

}
//SHARE LINK TO EMAIL(Inner Page)
if(isset($_POST['cmdshareinner'])){
	
	

	if($_POST['receiverMail']!="")
	{
				$page_url = 'share_post_link.php';
				$paturl = rawurlencode($page_url);
				$paturl .= "?sharelink=".urlencode($_POST['shareLink']);										
				$paturl .= "&receiverMail=".urlencode($_POST['receiverMail']);
				$paturl .= "&subject=".urlencode($_POST['mailsub']);		
				send_mail($paturl);
	
	$response="share-link-success";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl'].'&response='.$response);
	}
	else{
		$_SESSION['status']="error";
		
		header('location:'.HOST_MAIN_URL.''.$_POST['currenturl']);
	}
}	
//SEND APPOINTMENT/OPINION LINK
 if(isset($_POST['sendappointment'])){
	$getDoc = mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.ref_mail as ref_mail,c.hosp_name as hosp_name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$admin_id."'" ,"","","","");	
	$weblink=HOST_MAIN_URL."SendRequestLink/?d=".md5($getDoc[0]['ref_id']);	 
	//Send SMS to requested person
	if($_POST['pat_mobile']!="")
	{
		$mobile = $_POST['pat_mobile'];
		$msg = $getDoc[0]['ref_name']." - For Appointments/Opinion Please visit " . $weblink." - Thank you";
					
		send_msg($mobile,$msg);
	}	
	
	if($_POST['pat_email']!="")
	{
	$page_url = 'Custom_send_request_link.php';
						$paturl = rawurlencode($page_url);
						$paturl .= "?doclink=".urlencode($weblink);										
						$paturl .= "&custmail=".urlencode($_POST['pat_email']);
						$paturl .= "&hospName=".urlencode($getDoc[0]['hosp_name']);
						$paturl .= "&docEmail=".urlencode($getDoc[0]['ref_mail']);
						$paturl .= "&ccmail=".urlencode($ccmail);		
						send_mail($paturl);
	}
	$response="send";
	header("Location:Blogs-Offers-Events-List?response=".$response);			

 }

	
	
//RESCHEDULE APPOINTMENT	
if(isset($_POST['cmdreschedule']))
{
	
	$reschedule_date = date('Y-m-d',strtotime($_POST['reschedule_date']));
	$slctTime = $_POST['check_time'];
	
	$arrFields = array();
	$arrValues = array();
		
	
	$arrFields[] = 'Visiting_date';
	$arrValues[] = $reschedule_date;
	$arrFields[] = 'Visiting_time';
	$arrValues[] = $slctTime;
	$arrFields[] = 'pay_status';
	$arrValues[] = "Pending";
		
	$updatepatientApp=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['trans_id']."'");
	$getTime=mysqlSelect('*','timings',"Timing_id='".$slctTime."'");
	$arrFields_token[] = 'token_no';
	$arrValues_token[] = "555";
	$arrFields_token[] = 'status';
	$arrValues_token[] = "Pending";
	$arrFields_token[] = 'app_date';
	$arrValues_token[] = $reschedule_date;
	$arrFields_token[] = 'app_time';
	$arrValues_token[] = $getTime[0]['Timing'];
		
	$updateAppToken=mysqlUpdate('appointment_token_system',$arrFields_token,$arrValues_token,"appoint_trans_id='".$_POST['trans_id']."'");
	
	$getInfo1 = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['trans_id']."'" ,"","","","");	
	$getDoc = mysqlSelect("*","referal","ref_id='".$getInfo1[0]['pref_doc']."'" ,"","","","");	
	$getTime = mysqlSelect("*","timings","Timing_id='".$getInfo1[0]['Visiting_time']."'" ,"","","","");
	
	$visitDate=date('d M-Y',strtotime($getInfo1[0]['Visiting_date']));
		
			//Purpose:Appointment token System
				
				//Check Last Appointment Token No
			/*	$getLastAppInfo = mysqlSelect("*","appointment_token_system","app_date='".date('Y-m-d',strtotime($visitDate))."'" ,"","","","");
				if(COUNT($getLastAppInfo)>0){
					$getTokenNo = $getLastAppInfo[0]['token_no']+1;
				}
				else{
					$getTokenNo = 1;
				}
				$arrFieldsAppSlot = array();
				$arrValuesAppSlot = array();
				
				$arrFieldsAppSlot[] = 'token_no';
				$arrValuesAppSlot[] = $getTokenNo;
				$arrFieldsAppSlot[] = 'patient_id';
				$arrValuesAppSlot[] = $patientid;
				$arrFieldsAppSlot[] = 'appoint_trans_id';
				$arrValuesAppSlot[] = $_POST['Pat_Trans_Id'];
				$arrFieldsAppSlot[] = 'patient_name';
				$arrValuesAppSlot[] = $txtName;
				$arrFieldsAppSlot[] = 'doc_id';
				$arrValuesAppSlot[] = $admin_id;
				$arrFieldsAppSlot[] = 'doc_type';
				$arrValuesAppSlot[] = "1";
				$arrFieldsAppSlot[] = 'status';
				$arrValuesAppSlot[] = "Pending";
				$arrFieldsAppSlot[] = 'app_date';
				$arrValuesAppSlot[] = date('Y-m-d',strtotime($chkInDate));
				$arrFieldsAppSlot[] = 'app_time';
				$arrValuesAppSlot[] = $getTime[0]['Timing'];				
				$arrFieldsAppSlot[] = 'created_date';
				$arrValuesAppSlot[] = $curDate;
				$createappointment=mysqlInsert('appointment_token_system',$arrFieldsAppSlot,$arrValuesAppSlot);
				*/
		
	//Message to Patient	
	$mobile=$getInfo1[0]['Mobile_no'];	
	$responsemsg = "Dear ".$getInfo1[0]['patient_name'].", your appointment with ".$getDoc[0]['ref_name']." has been rescheduled for ".$visitDate." / ".$getTime[0]['Timing']."- Thanks ".$_SESSION['login_hosp_name'];
	send_msg($mobile,$responsemsg);
	$response="reschedule";
	header("Location:Appointments?response=".$response);			

}	
	
	
//Doctor reassign functionality
if(isset($_POST['cmdreassign'])){
	$patid = $_POST['patientid'];
	$SelectRef = $_POST['selectref'];
	
	if(!empty($SelectRef))
	{
		$arrFields[]= 'ref_id';
		$arrValues[]= $SelectRef;
	}

	
	$arrFields[]= 'timestamp';
	$arrValues[]= $curDate;
	
	$updatereferral=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	

	$updateChatHistory=mysqlUpdate('chat_notification',$arrFields,$arrValues,"patient_id='".$patid."' and ref_id='".$_POST['oldrefid']."'");	
	
	//Get Reassigned Doctor details
	$getReassignedDoc=mysqlSelect("a.ref_id as ref_id,a.ref_name as ref_name,a.doc_spec as doc_spec,a.Total_Referred as Total_Referred,c.communication_status as communication_status,a.doc_photo as doc_photo,a.ref_address as ref_address,a.doc_state as doc_state,c.hosp_name as hosp_name,c.company_id as company_id","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$SelectRef."'","","","","");	
	$getReassignedDocSpec=mysqlSelect("spec_name","specialization","spec_id='".$getReassignedDoc[0]['doc_spec']."'","","","","");
	$reassignmsg="Case has been re-assign to ".$getReassignedDoc[0]['ref_name'];
	$get_organisation=mysqlSelect("company_id,company_name,company_logo,email_id,mobile","compny_tab","company_id='".$getReassignedDoc[0]['company_id']."'","","","","");	
	
		
	
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($patid))
	{
		$arrFields1[]= 'patient_id';
		$arrValues1[]= $patid;
	}
	if(!empty($SelectRef))
	{
		$arrFields1[]= 'ref_id';
		$arrValues1[]= $SelectRef;
	}
	$arrFields1[]= 'status_id';
	$arrValues1[]= "2";
	$arrFields1[]= 'chat_note';
	$arrValues1[]= $reassignmsg;
	$arrFields1[]= 'TImestamp';
	$arrValues1[]= $curDate;
	$addoffers=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
	
	//Update Old Doctor No.of referral count, ie. we should decrement it by one
	//Get Reassigned Old Doctor details
	$getOldDoc=mysqlSelect("*","referal","ref_id='".$_POST['oldrefid']."'","","","","");
	$getUpdateCount=$getOldDoc[0]['Total_Referred']-1;//Decrement it by one
	
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[]= 'Total_Referred';
	$arrValues2[]= $getUpdateCount;
	
	$updatereferral1=mysqlUpdate('referal',$arrFields2,$arrValues2,"ref_id='".$_POST['oldrefid']."'");	

	
	//Update Ressigned Doctor No.of referral count, ie. we should increment it by one
	$getUpdateNewCount=$getReassignedDoc[0]['Total_Referred']+1;//Increment it by one
	
	$arrFields3[]= 'Total_Referred';
	$arrValues3[]= $getUpdateNewCount;
	
	$updatereferral2=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$SelectRef."'");
	
	if(!empty($_POST['patemail']) && $getReassignedDoc[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($getReassignedDoc[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$getReassignedDoc[0]['ref_id']."/".$getReassignedDoc[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getReassignedDocSpec[0]['spec_name']));
						$getDocName=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$getReassignedDoc[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$getReassignedDoc[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$getReassignedDoc[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$getReassignedDoc[0]['ref_id'];
						$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['company_id'].'/'.$get_organisation[0]['company_logo'];
	
											
						$url_page = 'Custom_after_reassign_pat_mail.php';
						$ccmail="medical@medisense.me";
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($getReassignedDoc[0]['ref_name']);
						$url .= "&docid=" . urlencode($getReassignedDoc[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getReassignedDoc[0]['spec_name']);					
						$url .= "&patid=" . urlencode($_POST['patientid']);
						$url .= "&patname=" . urlencode($_POST['patname']);					
						$url .= "&patmail=" . urlencode($_POST['patemail']);
						$url .= "&hospName=".urlencode($getReassignedDoc[0]['hosp_name']);
						$url .= "&compLogo=".urlencode($compLogo);
						$url .= "&compMail=".urlencode($get_organisation[0]['email_id']);
						$url .= "&ccmail=" . urlencode($ccmail);		
						//send_mail($url);		
					
					}
	
	//Message Notification to Partner
	$getPartnerInfo=mysqlSelect("a.partner_name as partner_name,a.cont_num1 as cont_num,a.Email_id as Email_id","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$_POST['sourceid']."'","","","","");
	$mobile = $getPartnerInfo[0]['cont_num'];
	$responsemsg = "Dear ".$getPartnerInfo[0]['partner_name'].", ".$_POST['patname']."(".$_POST['patientid']."), patient medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	send_msg($mobile,$responsemsg);
	
	
	//Message Notification to patient
	//$mobile = $_POST['patmobile'];
	//$responsemsg = "Dear ".$_POST['patname'].", Your medical query has been reassigned to ".$getReassignedDoc[0]['ref_name'].". Thanks ".$getReassignedDoc[0]['hosp_name'];
	//send_msg($mobile,$responsemsg);
	
	$response="reassign";
	header("Location:Cases-Recieved?response=".$response);
	
}

	
//Search By Name,Email,Location & Contact No.
if(isset($_POST['postTextSrchCmd'])){
	$txtSearch = addslashes($_POST['postTextSrch']);
	header("Location:search.php?s=".$txtSearch);
	
}


//Add Offers & Events
if(isset($_POST['addOffers']) || isset($_POST['editOffers'])){

	$startDate= $_POST['startendDate'];
	$slctHosp= $_POST['selectHosp'];
	$docId= $_POST['selectref'];
	//$marketId= $_POST['selectmarket'];
	$offerTitle= addslashes($_POST['offer_title']);
	$eventType= $_POST['eventType'];
	$Descr= addslashes($_POST['descr']);
	$event_pic = basename($_FILES['txtPhoto']['name']);	
	$event_key=time();
	$arrFields = array();
	$arrValues = array();

	$arrFields[]= 'event_key';
	$arrValues[]= $event_key;
	$arrFields[]= 'start_end_date';
	$arrValues[]= $startDate;
	if(!empty($docId))
	{
		$arrFields[]= 'oganiser_doc_id';
		$arrValues[]= $docId;
	}
	
	if(!empty($marketId))
	{
		$arrFields[]= 'organiser_market_id';
		$arrValues[]= $marketId;
	}

	
	$arrFields[]= 'title';
	$arrValues[]= $offerTitle;
	$arrFields[]= 'description';
	$arrValues[]= $Descr;
	$arrFields[]= 'event_type';
	$arrValues[]= $eventType;

	if(!empty($admin_id))
	{
		$arrFields[]= 'company_id';
		$arrValues[]= $admin_id;
	}
	if(!empty($slctHosp))
	{
		$arrFields[]= 'hosp_id';
		$arrValues[]= $slctHosp;
	}
		
	
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	
	if(isset($_POST['addOffers'])){
	$addoffers=mysqlInsert('offers_events',$arrFields,$arrValues);
	$id= $addoffers;
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'listing_type_id';
	$arrValues1[]= $id;
		if($eventType==1){
		$arrFields1[]= 'listing_type';
		$arrValues1[]= "Events";
		}
		else
		{
		$arrFields1[]= 'listing_type';
		$arrValues1[]= "Offers";	
		}

		if(!empty($admin_id))
		{
			$arrFields1[]= 'company_id';
			$arrValues1[]= $admin_id;
		}
		
		if(!empty($slctHosp))
		{
			$arrFields1[]= 'hosp_id';
			$arrValues1[]= $slctHosp;
		}

	
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	$response="add";
	} else if(isset($_POST['editOffers'])){
		$updateOffer=mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Hospital/Eventimages";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];

					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload


				}
		//Here we need to Send Push notification to mapped partners
		$getrefPartlist = mysqlSelect("*","our_partners as a left join mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.hosp_id='".$slctHosp."'","","","","");
	
		$msg="";
		$title=substr($offerTitle,0,20);
		$subtitle=substr($Descr,0,20);
		$tickerText="New Event";
		$type="2"; //For Event type 2
		$patientid="0";
		$docid=$admin_id;
			if(!empty($event_pic)){
			$largeimg=$hostname."Hospital/Eventimages/".$id."/".$event_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}	
			
			$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
		foreach($getrefPartlist as $PartList){
		$regid=$PartList['gcm_tokenid'];		
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$id,$patientid,$docid,$event_key);
		}
		
		//Push notification for Doctors
		//Retrieve all doctors gcm id
		$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];		
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
		}
		//End Push notification functionality
		
	header("Location:Offers-Events?response=".$response);
	
}


	
if(isset($_POST['addRef'])){
	
	$txtRefId= $_POST['txtref'];

	if($txtRefId!=""){
	
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($_POST['Pat_Id']))
	{
		$arrFields1[]= 'patient_id';
		$arrValues1[]= $_POST['Pat_Id'];
	}

	if(!empty($txtRefId))
	{
		$arrFields1[]= 'ref_id';
		$arrValues1[]= $txtRefId;
	}
	$arrFields1[]= 'email_status';
	$arrValues1[]= "1";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	$arrFields1[]= 'status2';
	$arrValues1[]= "2";
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= "2";
	$arrFields1[]= 'timestamp';
	$arrValues1[]= $Cur_Date;
	$chkreflist = mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
	}else{
		
			$getPatInfo = mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
			if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']=="" || $getPatInfo[0]['pat_country']=="" || $getPatInfo[0]['patient_complaint']=="" || $getPatInfo[0]['patient_desc']=="" || $getPatInfo[0]['repnotattach']==0){
			
			echo '<script language="javascript">';
			echo 'alert("Please fill the required patient details properly")';
			echo '</script>'; 
			
			} else if($getPatAttach==true || $getPatInfo[0]['repnotattach']==1 ) {
		
						mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef	=	mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id		=	$patientRef;
						$pat_id = $_POST['Pat_Id'];
						$_SESSION['Ref_Id']=$txtRefId;
	
						
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}
						if($getPatInfo[0]['hyper_cond']==0){
							
							$Hyper_Cond="No";
						} else {
							$Hyper_Cond="Yes";
						}
						if($getPatInfo[0]['diabetes_cond']==0){
							
							$Diabetic_Cond="No";
						} else {
							$Diabetic_Cond="Yes";
						}
						if($getPatInfo[0]['lead_type']=="Hot"){
							
							$Lead_Cond="H";
							$Time="4hrs";
						} else if($getPatInfo[0]['lead_type']=="Warm"){
							$Lead_Cond="W";
							$Time="7hrs";
						} else {
							$Lead_Cond="O";
							$Time="24hrs";
						}
						if($getPatInfo[0]['qualification']==0){
							$pat_qualification="NS";
						} else {
							$pat_qualification=$getPatInfo[0]['qualification'];
						}
						
						if($getPatInfo[0]['pat_country']=="India"){
							$queryType="D";
						} else {
							$queryType="I";
						}
						
						if($getPatInfo[0]['repnotattach']==1)
						{
							$noreportmsg="No medical report attached";
						}
						
						if($get_pro[0]['communication_status']==1)
						{
							$docmail = $get_pro[0]['ref_mail'];
							$pro_contact=$get_pro[0]['contact_num'];
							
						} else if($get_pro[0]['communication_status']==2)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'];
							$pro_contact=$get_pro[0]['hosp_contact'];
						} else if($get_pro[0]['communication_status']==3)
						{
							$docmail .= $get_pro[0]['hosp_email'] . ', ';
							$docmail .= $get_pro[0]['hosp_email1'] . ', ';
							$docmail .= $get_pro[0]['hosp_email2'] . ', ';
							$docmail .= $get_pro[0]['hosp_email3'] . ', ';
							$docmail .= $get_pro[0]['hosp_email4'] . ', ';
							$docmail .= $get_pro[0]['ref_mail'];
							
							$pro_contact=$get_pro[0]['hosp_contact'];
							$doc_contact=$get_pro[0]['contact_num'];
						}
						
						if($getPatInfo[0]['transaction_status']=="TXN_SUCCESS"){
							$paid_msg="PAID QUERY- ";
						}
						
					if($_POST['contatInclude']==1){
						$patContactDet= "Patient Contact Details: <br>Contact No. :".$getPatInfo[0]['patient_mob']."<br>Email Address :".$getPatInfo[0]['patient_email'];
						$chk_prior="PRIORITY";
					}	
					$subject=$chk_prior." ".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
									
										
					$url_page  = 'refdocmail.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patid=".urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);
					$url .= "&patage=" . urlencode($getPatInfo[0]['patient_age']);
					$url .= "&patgend=" . urlencode($Pat_Gen);
					$url .= "&patweight=" . urlencode($getPatInfo[0]['weight']);
					$url .= "&patmerital=" . urlencode($getPatInfo[0]['merital_status']);
					$url .= "&pathyper=" . urlencode($Hyper_Cond);
					$url .= "&patdiabetes=" . urlencode($Diabetic_Cond);
					$url .= "&patloc=" . urlencode($getPatInfo[0]['patient_loc']);
					$url .= "&patState=" . urlencode($getPatInfo[0]['pat_state']);
					$url .= "&patCountry=" . urlencode($getPatInfo[0]['pat_country']);
					$url .= "&patcomp=" . urlencode($getPatInfo[0]['patient_complaint']);
					$url .= "&patdesc=" . urlencode($getPatInfo[0]['patient_desc']);
					$url .= "&patquery=" . urlencode($getPatInfo[0]['pat_query']);
					$url .= "&patqualification=" . urlencode($pat_qualification);
					$url .= "&patblood=" . urlencode($getPatInfo[0]['pat_blood']);
					$url .= "&patContactDet=". urlencode($patContactDet);
					$url .= "&patnoreportmsg=" . urlencode($noreportmsg);
					if(!empty($getPatAttach[0]['attach_id'])){
					$url .= "&patattachid1=" . urlencode($getPatAttach[0]['attach_id']);
					$url .= "&patattachname1=" . urlencode($getPatAttach[0]['attachments']);
					}
					if(!empty($getPatAttach[1]['attach_id'])){
					$url .= "&patattachid2=" . urlencode($getPatAttach[1]['attach_id']);
					$url .= "&patattachname2=" . urlencode($getPatAttach[1]['attachments']);
					}
					if(!empty($getPatAttach[2]['attach_id'])){
					$url .= "&patattachid3=" . urlencode($getPatAttach[2]['attach_id']);
					$url .= "&patattachname3=" . urlencode($getPatAttach[2]['attachments']);
					}
					if(!empty($getPatAttach[3]['attach_id'])){
					$url .= "&patattachid4=" . urlencode($getPatAttach[3]['attach_id']);
					$url .= "&patattachname4=" . urlencode($getPatAttach[3]['attachments']);
					}
					if(!empty($getPatAttach[4]['attach_id'])){
					$url .= "&patattachid5=" . urlencode($getPatAttach[4]['attach_id']);
					$url .= "&patattachname5=" . urlencode($getPatAttach[4]['attachments']);
					}
					if(!empty($getPatAttach[5]['attach_id'])){
					$url .= "&patattachid6=" . urlencode($getPatAttach[5]['attach_id']);
					$url .= "&patattachname6=" . urlencode($getPatAttach[5]['attachments']);
					}
					if(!empty($getPatAttach[6]['attach_id'])){
					$url .= "&patattachid7=" . urlencode($getPatAttach[6]['attach_id']);
					$url .= "&patattachname7=" . urlencode($getPatAttach[6]['attachments']);
					}
					if(!empty($getPatAttach[7]['attach_id'])){
					$url .= "&patattachid8=" . urlencode($getPatAttach[7]['attach_id']);
					$url .= "&patattachname8=" . urlencode($getPatAttach[7]['attachments']);
					}
					if(!empty($getPatAttach[8]['attach_id'])){
					$url .= "&patattachid9=" . urlencode($getPatAttach[8]['attach_id']);
					$url .= "&patattachname9=" . urlencode($getPatAttach[8]['attachments']);
					}
					if(!empty($getPatAttach[9]['attach_id'])){
					$url .= "&patattachid10=" . urlencode($getPatAttach[9]['attach_id']);
					$url .= "&patattachname10=" . urlencode($getPatAttach[9]['attachments']);
					}
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
					
					$url .= "&docmail=" . urlencode($docmail);
					
					$url .= "&ccmail=" . urlencode($ccmail);
							
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&subject=" . urlencode($subject);
					
							
					$ch = curl_init (); // setup a curl						
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
					$output = curl_exec ( $ch );				
					curl_close ( $ch );
					
					
					if(!empty($getPatInfo[0]['patient_email']) && $get_pro[0]['communication_status']!=0){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocSpec=urlencode(str_replace(' ','-',$getDocDept[0]['spec_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.$get_pro[0]['ref_id'];
						
						//TO CHECK MEDI ASSIST Source
						if($getPatInfo[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
						$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
						}
						else{
							$mas_logo="";
						}
						
						$url_page = 'After_refer_pat_mail.php';
						$url = rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
						$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						send_mail($url);
					}
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'email_status';
					$arrValues[]= "1";
					$patientRef=mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$pat_id."'and ref_id='".$refer_id."'");
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro[0]['ref_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();

					if(!empty($getPatInfo[0]['patient_id']))
					{
						$arrFields1[]= 'patient_id';
						$arrValues1[]= $getPatInfo[0]['patient_id'];
					}
					if(!empty($get_pro[0]['ref_id']))
					{
						$arrFields1[]= 'ref_id';
						$arrValues1[]= $get_pro[0]['ref_id'];
					}
									
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;

					if(!empty($admin_id))
					{
						$arrFields1[]= 'user_id';
						$arrValues1[]= $admin_id;
					}
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//Medisense Note
					$msg="Refered to ".$get_pro[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();

					if(!empty($getPatInfo[0]['patient_id']))
					{
						$arrFields2[] = 'patient_id';
						$arrValues2[] = $getPatInfo[0]['patient_id'];
					}
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;

					if(!empty($admin_id))
					{
						$arrFields2[] = 'user_id';
						$arrValues2[] = $admin_id;
					}
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					
					$usercraete=mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					
					}
					
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by mail, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					}
					
				//Here we need to Send Push notification to Doctors
				if($get_pro[0]['gcm_tokenid']!=""){
				$msg = "Dear Doctor, You have received a query of patient " . $getPatInfo[0]['patient_name'] . $getPatInfo[0]['patient_id'] ." - Many Thanks";
							
				$regid=$get_pro[0]['gcm_tokenid'];
				$title="New Referral";
				$subtitle="New Referral";
				$tickerText="Leap new blog";
				$type="4"; //For Blog Type value is 1
				$largeimg='large_icon';
				$blog_id="0";
				$patientid=$getPatInfo[0]['patient_id'];
				$docid=$get_pro[0]['ref_id'];
				$postkey=time();
				push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$blog_id,$patientid,$docid,$postkey);
				
				//End Push notification functionality
				}	
					//SMS notification to Patient
					if($getPatInfo[0]['patient_mob']!="" && $get_pro[0]['communication_status']!=0){
					$mobile = $getPatInfo[0]['patient_mob'];
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query has been successfully referred to ".$get_pro[0]['ref_name']." Please check your mail for further detail. Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
				}
					
		}
	
}//End If loop

}
	
	
//ADD / UPDATE MARKETING PERSON
if(isset($_POST['add_person']) || isset($_POST['edit_person'])){
	//Check Empty condition
	if(!empty($_POST['selectHosp']) || !empty($_POST['person_name']) || !empty($_POST['person_mobile']))
	{
	$selectHosp = $_POST['selectHosp'];
	$person_name = addslashes($_POST['person_name']);
	$person_mobile = $_POST['person_mobile'];
	$person_email = addslashes($_POST['person_email']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'person_name';
		$arrValues[] = $person_name;

		if(!empty($selectHosp))
		{
			$arrFields[] = 'hosp_id';
			$arrValues[] = $selectHosp;
		}

		
		$arrFields[] = 'person_mobile';
		$arrValues[] = $person_mobile;
		$arrFields[] = 'person_email';
		$arrValues[] = $person_email;
		
		if(isset($_POST['add_person'])){
			$personcreate=mysqlInsert('hosp_marketing_person',$arrFields,$arrValues);
			$person_id= $personcreate;
			header("Location:Add-Marketing-Persons?response=add");
				
		}
		else{	
			$updateProvider=mysqlUpdate('hosp_marketing_person',$arrFields,$arrValues,"person_id='".$_POST['Person_Id']."'");	
			header("Location:Add-Marketing-Persons?response=update");
		}
	
	}
	else
	{
	header("Location:Add-Marketing-Persons?response=error");	
	}
}	
	
//ADD / UPDATE REFERRING PARTNER
if(isset($_POST['add_referrer']) || isset($_POST['edit_referrer'])){
	//Check Empty condition
	if(!empty($_POST['refPartName']))
	{	
	$selectHosp = $_POST['selectHosp'];
	$selectPerson = $_POST['selectPerson'];
	$ref_name = addslashes($_POST['refPartName']);
	$partnertype = $_POST['selectType'];
	$partnercategory = $_POST['partner_cat'];
	$ref_mobile = $_POST['refPartMobile'];
	$ref_email = $_POST['refPartEmail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	//Check Referrer mobile/email id exists in our partner table
	$chkPartner = mysqlSelect("*","our_partners","Email_id='".$ref_email."' or Email_id1='".$ref_email."' or cont_num1='".$ref_mobile."'","","","","");
	$getHosp = mysqlSelect("*","hosp_tab","hosp_id='".$selectHosp."'","","","","");
	$getDoc = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	//Check Referrer is already mapped in marketing person
	$chkMappedReferrer = mysqlSelect("*","mapping_hosp_referrer","partner_id='".$chkPartner[0]['partner_id']."' and hosp_id='".$selectHosp."' and doc_id='".$admin_id."'","","","","");
	$get_organisation = mysqlSelect('company_id as Comp_Id,company_name as Comp_name,mobile as Org_Contact,email_id as Comp_Email,company_logo as Logo','compny_tab',"company_id='".$_POST['CompId']."'");
	$compLogo=HOST_MAIN_URL.'Hospital/company_logo/'.$get_organisation[0]['Comp_Id'].'/'.$get_organisation[0]['Logo'];
		
	//$webLink=$hostname."/Refer/";  
	$webLink="www.medisensepractice.com";
	
	if($chkPartner==true && $chkMappedReferrer==true){
					
					header("Location:Add-Referring-Partner?response=error");
	}
	
	else if($chkPartner==true){
			
			$partner_id= $chkPartner[0]['partner_id'];
			$arrFields1 = array();
			$arrValues1 = array();

			
			if(!empty($partner_id))
			{
				$arrFields1[] = 'partner_id';
				$arrValues1[] = $partner_id;
			}

			if(!empty($partnertype))
			{
				$arrFields1[] = 'partner_type';
				$arrValues1[] = $partnertype;
			}

			if(!empty($selectHosp))
			{
				$arrFields1[] = 'hosp_id';
				$arrValues1[] = $selectHosp;
			}

			if(!empty($_POST['CompId']))
			{
				$arrFields1[] = 'company_id';
				$arrValues1[] = $_POST['CompId'];
			}
			
			if(!empty($admin_id))
			{
				$arrFields1[] = 'doc_id';
				$arrValues1[] = $admin_id;
			}
			
			
			
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
					//Mail Notification to Referring Partner
					
					$usercredentials="Link :".$webLink."<br>User ID :".$chkPartner[0]['Email_id']." / ".$chkPartner[0]['cont_num1']."<br>Password: You have already registered. If you have forgotten password, then click forgot password in login page. <br><br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Pls use link www.medisensepractice.com to login. Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
					header("Location:Add-Referring-Partner?response=add");
	}
	else{
		
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'partner_name';
		$arrValues[] = $ref_name;
		$arrFields[] = 'contact_person';
		$arrValues[] = $ref_name;
		$arrFields[] = 'Email_id';
		$arrValues[] = $ref_email;
		$arrFields[] = 'cont_num1';
		$arrValues[] = $ref_mobile;
		$arrFields[] = 'password';
		$arrValues[] = $encypassword;
		$arrFields[] = 'reg_date';
		$arrValues[] = $curDate;
		$arrFields[] = 'Type';
		$arrValues[] = $partnercategory;
		
		if(isset($_POST['add_referrer'])){
			$personcreate=mysqlInsert('our_partners',$arrFields,$arrValues);
			$partner_id= $personcreate;
			
			//Insert Partner Id to Source List table
			$arrFields2[] = 'source_name';
			$arrValues2[] = $ref_name;
			if(!empty($partner_id))
			{
				$arrFields2[] = 'partner_id';
				$arrValues2[] = $partner_id;
			}
			
		
			$createsource=mysqlInsert('source_list',$arrFields2,$arrValues2);
			
			
			$arrFields1 = array();
			$arrValues1 = array();

			if(!empty($partner_id))
			{
				$arrFields1[] = 'partner_id';
				$arrValues1[] = $partner_id;
			}
			if(!empty($partnertype))
			{
				$arrFields1[] = 'partner_type';
				$arrValues1[] = $partnertype;
			}
			if(!empty($selectHosp))
			{
				$arrFields1[] = 'hosp_id';
				$arrValues1[] = $selectHosp;
			}
			if(!empty($_POST['CompId']))
			{
				$arrFields1[] = 'company_id';
				$arrValues1[] = $_POST['CompId'];
			}
			if(!empty($admin_id))
			{
				$arrFields1[] = 'doc_id';
				$arrValues1[] = $admin_id;
			}
			
			
			
			
			$personcreate=mysqlInsert('mapping_hosp_referrer',$arrFields1,$arrValues1);
			
			
			
			
					//Mail Notification to Referring Partner
					 $usercredentials="Link :".$webLink."<br>User ID :".$ref_email." / ".$ref_mobile."<br>Password: ".$password."<br>";
					
					$url_page = 'After_mapping_partner_mail.php';					
					$url = rawurlencode($url_page);
					$url .= "?partnername=".urlencode($ref_name);
					$url .= "&orgname=" . urlencode($getHosp[0]['hosp_name']);
					$url .= "&orgcontact=" . urlencode($get_organisation[0]['Org_Contact']);
					$url .= "&docname=" . urlencode($getDoc[0]['ref_name']);
					$url .= "&marketingmail=" . urlencode($getDoc[0]['ref_mail']);
					$url .= "&marketingmobile=".urlencode($getDoc[0]['contact_num']);
					$url .= "&usercredential=".urlencode($usercredentials);
					$url .= "&partnermail=".urlencode($ref_email);
					$url .= "&orgmail=".urlencode($get_organisation[0]['Comp_Email']);
					$url .= "&compLogo=".urlencode($compLogo);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to Referring Partner
					$mobile = $ref_mobile;
					$responsemsg = "Congrats. You have been added as a partner with ".$getHosp[0]['hosp_name'].". Please use link www.medisensepractice.com to login with user ID : ".$ref_email." and password : ".$password.". Pls check ".$ref_email." for further details. Thanks, ".$getDoc[0]['ref_name'];
					send_msg($mobile,$responsemsg);
			
			header("Location:Add-Referring-Partner?response=add");
		}
		//EDIT DETAILS
		else{	
			$updatePartner=mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$_POST['Partner_Id']."'");	
			$arrFields1[] = 'market_person_id';
			$arrValues1[] = $selectPerson;
			$updateMapping=mysqlUpdate('mapping_hosp_referrer',$arrFields1,$arrValues1,"partner_id='".$_POST['Partner_Id']."' and company_id='".$admin_id."'");
			header("Location:Add-Referring-Partner?response=update");
		}
	
		}
	}
	//Send Error message
	else
	{
		header("Location:Add-Referring-Partner?response=error");
	}
}	
//ADD / UPDATE HOSPITAL DETAILS
if(isset($_POST['add_hospital']) || isset($_POST['edit_hospital'])){
	//Check Empty condition
	if(!empty($_POST['txtHospName']) || !empty($_POST['slctComm']))
	{
	$txtHospName = addslashes($_POST['txtHospName']);
	$txtAddress = addslashes($_POST['txtAddress']);
	$txtCountry = $_POST['txtCountry'];
	$slctState = $_POST['slctState'];
	$txtSuburb = addslashes($_POST['txtSuburb']);
	$txtCity = addslashes($_POST['txtCity']);	
	$txtOverview = addslashes($_POST['txtOverview']);
	$txtBeds = addslashes($_POST['txtBeds']);
	$txtAmbulance = addslashes($_POST['txtAmbulance']);
	$txtServices = addslashes($_POST['txtServices']);
	$txtPerson = addslashes($_POST['txtPerson']);
	$txtMobile = $_POST['txtMobile'];
	$txtEmail = addslashes($_POST['txtEmail']);
	$txtEmail1 = addslashes($_POST['txtEmail1']);
	$txtEmail2 = addslashes($_POST['txtEmail2']);
	$txtEmail3 = addslashes($_POST['txtEmail3']);
	$txtEmail4 = addslashes($_POST['txtEmail4']);
	$slctComm = addslashes($_POST['slctComm']);
	
	$txtrevisitcharge = addslashes($_POST['txtrevisitcharge']);
	$txtnewvisitcharge = addslashes($_POST['txtnewvisitcharge']);
	
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp = $_POST['slctHosp'];	
	$docImage = basename($_FILES['file-3']['name']);
	
	$arrFields = array();
	$arrValues = array();

		$arrFields[] = 'hosp_name';
		$arrValues[] = $txtHospName;
		$arrFields[] = 'hosp_suburb';
		$arrValues[] = $txtSuburb;
		$arrFields[] = 'hosp_city';
		$arrValues[] = $txtCity;
		$arrFields[] = 'hosp_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'hosp_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'hosp_contact_name';
		$arrValues[] = $txtPerson;
		$arrFields[] = 'hosp_contact';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'hosp_email';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'hosp_email1';
		$arrValues[] = $txtEmail1;
		$arrFields[] = 'hosp_email2';
		$arrValues[] = $txtEmail2;		
		$arrFields[] = 'hosp_email3';
		$arrValues[] = $txtEmail3;
		$arrFields[] = 'hosp_email4';
		$arrValues[] = $txtEmail4;
		$arrFields[] = 'hosp_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'communication_status';
		$arrValues[] = $slctComm;
		
		$arrFields[] = 'hosp_overview';
		$arrValues[] = $txtOverview;
		$arrFields[] = 'num_beds';
		$arrValues[] = $txtBeds;
		$arrFields[] = 'num_ambulance';
		$arrValues[] = $txtAmbulance;	
		$arrFields[] = 'hosp_services';
		$arrValues[] = $txtServices;

		if(!empty($txtrevisitcharge))
		{
			$arrFields[] = 'revisit_charge';
			$arrValues[] = $txtrevisitcharge;
		}
		if(!empty($txtnewvisitcharge))
		{
			$arrFields[] = 'newvist_charge';
			$arrValues[] = $txtnewvisitcharge;
		}
		if(!empty($_SESSION['user_id']))
		{
			$arrFields[] = 'company_id';
			$arrValues[] = $_SESSION['user_id'];
		}

					
				
	if(isset($_POST['add_hospital'])){
			$usercraete=mysqlInsert('hosp_tab',$arrFields,$arrValues);
			$hosp_id= $usercraete;
			
			foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
				$arrFields2 = array();
				$arrValues2 = array();

				if(!empty($hosp_id))
				{
					$arrFields2[] = 'hosp_id';
					$arrValues2[] = $hosp_id;
				}
				if(!empty($amenValue))
				{
					$arrFields2[] = 'amenity_id';
					$arrValues2[] = $amenValue;
				}

				$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
			}
			
			
				
			//UPLOAD MULTIPLE IMAGES
			if(!empty($_FILES['file-3']['name']))
			{
				$errors= array();
				foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name )
				{	
				
				
					$file_name = $_FILES['file-3']['name'][$key];
					$file_size =$_FILES['file-3']['size'][$key];
					$file_tmp =$_FILES['file-3']['tmp_name'][$key];
					$file_type=$_FILES['file-3']['type'][$key];
					
			
					$arrFields3 = array();
					$arrValues3 = array();
					if(!empty($hosp_id))
					{
						$arrFields3[] = 'hosp_id';
						$arrValues3[] = $hosp_id;
					}

					$arrFields3[] = 'hosp_image';
					$arrValues3[] = $file_name;
					
					
					$bslist_pht	=	mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
					$id		    = $bslist_pht;


					//UPLOAD COMPRESSED IMAGE
					if ($_FILES["file-3"]["error"][$key] > 0) {
							$error = $_FILES["file-3"]["error"][$key];
					} 
					else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
					($_FILES['file-3']['type'][$key] == "image/jpeg") || 
					($_FILES['file-3']['type'][$key] == "image/png") || 
					($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
					{

					$folder_name	=	"Hosp_image";
					$sub_folder		=	$id;
					$filename		=	$_FILES['file-3']['name'][$key];
					$file_url		=	$_FILES['file-3']['tmp_name'][$key];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					}
				
					
				}
				//End of foreach
			} //End of Not Empty condition

			
			header("Location:Add-Hospital?response=add");
		}
		else if(isset($_POST['edit_hospital'])){	
		$updateProvider=mysqlUpdate('hosp_tab',$arrFields,$arrValues,"hosp_id='".$_POST['Hosp_Id']."'");	
					foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
						$arrFields2 = array();
						$arrValues2 = array();

						if(!empty($_POST['Hosp_Id']))
						{
							$arrFields2[] = 'hosp_id';
							$arrValues2[] = $_POST['Hosp_Id'];
						}

						if(!empty($amenValue))
						{
							$arrFields2[] = 'amenity_id';
							$arrValues2[] = $amenValue;
						}

						$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
					}
		
		
			
				//UPLOAD MULTIPLE IMAGES
				if(!empty($_FILES['file-3']['name'])){
				$errors= array();
				foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
				
					
				$file_name = $_FILES['file-3']['name'][$key];
				$file_size =$_FILES['file-3']['size'][$key];
				$file_tmp =$_FILES['file-3']['tmp_name'][$key];
				$file_type=$_FILES['file-3']['type'][$key];
				
				
					$arrFields3 = array();
					$arrValues3 = array();

					if(!empty($_POST['Hosp_Id']))
					{
						$arrFields3[] = 'hosp_id';
						$arrValues3[] = $_POST['Hosp_Id'];
					}

					$arrFields3[] = 'hosp_image';
					$arrValues3[] = $file_name;
					
						
						$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
						$id		   = $bslist_pht;


						//UPLOAD COMPRESSED IMAGE
						if ($_FILES["file-3"]["error"][$key] > 0) {
								$error = $_FILES["file-3"]["error"][$key];
						} 
						else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
						($_FILES['file-3']['type'][$key] == "image/jpeg") || 
						($_FILES['file-3']['type'][$key] == "image/png") || 
						($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
						{
							$folder_name	=	"Hosp_image";
							$sub_folder		=	$id;
							$filename		=	$_FILES["file-3"]["name"][$key];
							$file_url		=	$_FILES["file-3"]["tmp_name"][$key];
							fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
								
						}
						
					
						
					}
				}
			//End of foreach
		header("Location:Add-Hospital?response=update");
		}
	}
	//Send Error message
	else
	{
		header("Location:Add-Hospital?response=error");
	}
}

//ADD / UPDATE HOPITAL DOCTOR
if(isset($_POST['edit_doctor'])){
	
	$txtDoc		 	= addslashes($_POST['txtDoc']);
	$txtCountry 	= $_POST['txtCountry'];
	$slctState 		= $_POST['slctState'];
	$txtCity 		= $_POST['se_city'];
	$slctHosp 		= $_POST['selectHosp'];	
	$slctSpec 		= $_POST['slctSpec'];
	$txtQual 		= addslashes($_POST['txtQual']);
	$txtExp 		= $_POST['txtExp'];
	$txtMobile 		= $_POST['txtMobile'];
	$txtMobile2 	= $_POST['txtMobile2'];
	$txtEmail 		= $_POST['txtEmail'];
	$txtWebsite 	= $_POST['txtWebsite'];
	$txtInterest 	= addslashes($_POST['txtInterest']);
	$txtContribute 	= addslashes($_POST['txtContribute']);
	$txtResearch 	= addslashes($_POST['txtResearch']);
	$txtPublication = addslashes($_POST['txtPublication']);
	$txtKeyword 	= addslashes($_POST['txtKeywords']);
	$txtNumOpinion 	= addslashes($_POST['numopinion']);
	
	$txtInOpcost 	= addslashes($_POST['inopcost']);
	

	$txtOnOpcost 	= addslashes($_POST['onopcost']);
	if($txtOnOpcost!="")
	{
		$arrFields[] = 'on_op_cost';
		$arrValues[] = $txtOnOpcost;
	}
	
	$txtConcharge 	= addslashes($_POST['conscharge']);
	$txtSecEmail	= $_POST['txtSecEmail'];
	$txtSecPhone 	= $_POST['txtSecPhone'];
	$docImage 		= basename($_FILES['txtPhoto']['name']);	
	$txtCurrency 	= $_POST['slctCurrency'];
	$consult_lang 	= $_POST['consult_lang'];	
	$teleOpCond		= addslashes($_POST['teleop']);
	$telecontact 	= addslashes($_POST['teleopnumber']);
	$videoOpCond 	= addslashes($_POST['videoop']);
	$videocontact 	= addslashes($_POST['videoopnumber']);
	$teleoptiming 	= addslashes($_POST['televidop_time']);
	
	$arrFields = array();
	$arrValues = array();

		if($txtInOpcost!="")
		{
			$arrFields[] = 'in_op_cost';
			$arrValues[] = $txtInOpcost;
		}
		if($videoOpCond!="")
		{
			$arrFields[] = 'video_op';
			$arrValues[] = $videoOpCond;
		}
		if($teleOpCond!="")
		{
			$arrFields[] = 'tele_op';
			$arrValues[] = $teleOpCond;
		}
		
		$arrFields[] = 'ref_name';
		$arrValues[] = $txtDoc;
		if($slctSpec=="")
		{
			$arrFields[] = 'doc_spec';
			$arrValues[] = "555";
		}
		else{
		$arrFields[] = 'doc_spec';
		$arrValues[] = $slctSpec;	
		}
		$arrFields[] = 'ref_mail';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'ref_web';
		$arrValues[] = $txtWebsite;		
		$arrFields[] = 'doc_qual';
		$arrValues[] = $txtQual;
		$arrFields[] = 'doc_type_val';
		$arrValues[] = "5";
		$arrFields[] = 'ref_address';
		$arrValues[] = $txtCity;
		$arrFields[] = 'doc_state';
		$arrValues[] = $slctState;
		$arrFields[] = 'doc_country';
		$arrValues[] = $txtCountry;

		$arrFields[] = 'ref_exp';
		$arrValues[] = $txtExp;
		$arrFields[] = 'doc_interest';
		$arrValues[] = $txtInterest;
		$arrFields[] = 'doc_research';
		$arrValues[] = $txtResearch;
		$arrFields[] = 'doc_contribute';
		$arrValues[] = $txtContribute;
		$arrFields[] = 'doc_pub';
		$arrValues[] = $txtPublication;
		$arrFields[] = 'doc_keywords';
		$arrValues[] = $txtKeyword;
		$arrFields[] = 'contact_num';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'secondary_contact_num';
		$arrValues[] = $txtMobile2;
		
		$arrFields[] = 'numfreeop';
		$arrValues[] = $txtNumOpinion;
		
		
		$arrFields[] = 'cons_charge';
		$arrValues[] = $txtConcharge;
		
		$arrFields[] = 'secretary_phone';
		$arrValues[] = $txtSecPhone;
		$arrFields[] = 'secretary_email';
		$arrValues[] = $txtSecEmail;	
		
		if(!empty($docImage)){
		$arrFields[] = 'doc_photo';
		$arrValues[] = $docImage;
		}
		$arrFields[] = 'message_status';
		$arrValues[] = "1";
		if(!empty($admin_id))
		{
			$arrFields[] = 'company_id';
			$arrValues[] = $admin_id;
		}

		$arrFields[] = 'TImestamp';
		$arrValues[] = $curDate;
		
		
		$arrFields[] = 'tele_op_contact';
		$arrValues[] = $telecontact;
		

		$arrFields[] = 'video_op_contact';
		$arrValues[] = $videocontact;	
		$arrFields[] = 'tele_video_op_timing';
		$arrValues[] = $teleoptiming;
		if(!empty($txtCurrency))
		{
			$arrFields[] = 'currency_id';
			$arrValues[] = $txtCurrency;
		}
		
	$updateProvider=mysqlUpdate('referal',$arrFields,$arrValues,"ref_id='".$_POST['Prov_Id']."'");
			mysqlDelete('doc_specialization',"doc_id='".$_POST['Prov_Id']."'");

					foreach($slctSpec as $key => $value)
					{
						$arrFields_spec = array();
						$arrValues_spec = array();

						if(!empty($_POST['Prov_Id']))
						{
							$arrFields_spec[] = 'doc_id';
							$arrValues_spec[] = $_POST['Prov_Id'];
						}

						if(!empty($value))
						{
							$arrFields_spec[] = 'spec_id';
							$arrValues_spec[] = $value;
						}
			
						$arrFields_spec[] = 'doc_type';
						$arrValues_spec[] = "1";
											
						$insert_spec=mysqlInsert('doc_specialization',$arrFields_spec,$arrValues_spec);
					}	
					mysqlDelete('doctor_hosp',"doc_id='".$_POST['Prov_Id']."'");

					foreach($slctHosp as $key => $value)
					{
					$arrFields_hosp = array();
					$arrValues_hosp = array();

					if(!empty($_POST['Prov_Id']))
					{
						$arrFields_hosp[] = 'doc_id';
						$arrValues_hosp[] = $_POST['Prov_Id'];
					}
					if(!empty($value))
					{
						$arrFields_hosp[] = 'hosp_id';
						$arrValues_hosp[] = $value;
					}
				
					$insert_spec=mysqlInsert('doctor_hosp',$arrFields_hosp,$arrValues_hosp);
					}
					
					mysqlDelete('doctor_langauges',"doc_id='".$_POST['Prov_Id']."'");
					foreach($consult_lang as $key => $value)
					{
					$arrFields_lang = array();
					$arrValues_lang = array();

					if(!empty($_POST['Prov_Id']))
					{
						$arrFields_lang[] = 'doc_id';
						$arrValues_lang[] = $_POST['Prov_Id'];

					}
					if(!empty($value))
					{
						$arrFields_lang[] = 'language_id';
						$arrValues_lang[] = $value;

					}

					$insert_spec=mysqlInsert('doctor_langauges',$arrFields_lang,$arrValues_lang);
					}
	
	$arrFields1 = array();
	$arrValues1= array();
	$chkHosp = mysqlSelect("*","doctor_hosp ","doc_id='".$_POST['Prov_Id']."'","","","","");
	$id=$_POST['Prov_Id'];
	
	
				/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
					$folder_name	=	"Doc";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
										
					
					
				}
	
		if(empty($slctHosp)){
		unset($_SESSION['login_hosp_name']);
		unset($_SESSION['login_hosp_id']);
		}
		else{
		unset($_SESSION['login_hosp_name']);
		unset($_SESSION['login_hosp_id']);	
		$getHospData = mysqlSelect("hosp_id,hosp_name", "hosp_tab", "hosp_id='".$slctHosp[0]."'", "", "", "", "");
		$_SESSION['login_hosp_id'] = $getHospData[0]['hosp_id'];
		$_SESSION['login_hosp_name'] = $getHospData[0]['hosp_name'];
		}
	header("Location:Profile?response=update");
	
	}
	//End Doctors profile updation
	
	
	
	if(isset($_GET['continue']) && isset($_GET['hospital']))
	{
		unset($_SESSION['login_hosp_name']);
		unset($_SESSION['login_hosp_id']);
		$getHospData = mysqlSelect("hosp_id,hosp_name", "hosp_tab", "md5(hosp_id)='".$_GET['hospital']."'", "", "", "", "");
		$_SESSION['login_hosp_id'] = $getHospData[0]['hosp_id'];
		$_SESSION['login_hosp_name'] = $getHospData[0]['hosp_name'];
		header("Location:".$_GET['continue']);
	}
if(isset($_POST['edit_timings']))
{	
	
	$arrFields_time[] = 'doc_type';
	$arrValues_time[] = "1";
	
	if(!empty($_POST['slctHosp']))
	{
		$arrFields_time[] = 'hosp_id';
		$arrValues_time[] = $_POST['slctHosp'];
	}
	if(!empty($_POST['Prov_Id']))
	{
		$arrFields_time[] = 'doc_id';
		$arrValues_time[] = $_POST['Prov_Id'];
	}
	
	$arrFields_time[] = 'num_patient_hour';
	$arrValues_time[] = $_POST['num_slot'];
	
	$checkHospTiming = mysqlSelect("slot_id", "doc_appointment_slots", "doc_id='".$_POST['Prov_Id']."' and doc_type='1' and hosp_id='".$_POST['slctHosp']."'", "", "", "", "");
											
	if(COUNT($checkHospTiming)==0)
	{
	
	$docslotcreate=mysqlInsert('doc_appointment_slots',$arrFields_time,$arrValues_time);	

		mysqlDelete('doc_time_set',"doc_id='".$_POST['Prov_Id']."' and hosp_id='".$_POST['slctHosp']."'");				
		for($i=1; $i<=$_POST['limit_i']; $i++)
		{
			$Timing_id=$_POST['time_id' . $i];
			for($j=1; $j<=$_POST['limit_j']; $j++)
			{
				$day_id=$_POST['day_id'. $i . $j];
				$time_limit=$_POST['time'. $i . $j];
				if($time_limit!=0){
				$arrFields_time = array();
				$arrValues_time = array();

				if(!empty($_POST['Prov_Id']))
				{
					$arrFields_time[] = 'doc_id';
					$arrValues_time[] = $_POST['Prov_Id'];
				}

				if(!empty($_POST['slctHosp']))
				{
					$arrFields_time[] = 'hosp_id';
					$arrValues_time[] = $_POST['slctHosp'];
				}

				if(!empty($Timing_id))
				{
					$arrFields_time[] = 'time_id';
					$arrValues_time[] = $Timing_id;
				}

				if(!empty($day_id))
				{
					$arrFields_time[] = 'day_id';
					$arrValues_time[] = $day_id;
				}

				

				$arrFields_time[] = 'time_set';
				$arrValues_time[] = $time_limit;
				
				$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
						
				}
			}
		}
	} else
	{
		
		$docslotcreate=mysqlUpdate('doc_appointment_slots',$arrFields_time,$arrValues_time,"doc_id = '".$_POST['Prov_Id']."' and doc_type = '1' and hosp_id='".$_POST['slctHosp']."'");
		mysqlDelete('doc_time_set',"doc_id='".$_POST['Prov_Id']."' and hosp_id='".$_POST['slctHosp']."'");				
		for($i=1; $i<=$_POST['limit_i']; $i++)
		{
			$Timing_id=$_POST['time_id' . $i];
			for($j=1; $j<=$_POST['limit_j']; $j++)
			{
				$day_id=$_POST['day_id'. $i . $j];
				$time_limit=$_POST['time'. $i . $j];
				if($time_limit!=0){
				$arrFields_time = array();
				$arrValues_time = array();

				if(!empty($_POST['Prov_Id']))
				{
					$arrFields_time[] = 'doc_id';
					$arrValues_time[] = $_POST['Prov_Id'];
				}

				if(!empty($_POST['slctHosp']))
				{
					$arrFields_time[] = 'hosp_id';
					$arrValues_time[] = $_POST['slctHosp'];
				}

				if(!empty($Timing_id))
				{
					$arrFields_time[] = 'time_id';
					$arrValues_time[] = $Timing_id;
				}

				if(!empty($day_id))
				{
					$arrFields_time[] = 'day_id';
					$arrValues_time[] = $day_id;
				}

				
				$arrFields_time[] = 'time_set';
				$arrValues_time[] = $time_limit;
				
				$doctimecreate=mysqlInsert('doc_time_set',$arrFields_time,$arrValues_time);
						
				}
			}
		}	
	}
	unset($_SESSION['login_hosp_id']);
	unset($_SESSION['login_hosp_name']);
	$getHospData = mysqlSelect("hosp_id,hosp_name", "hosp_tab", "hosp_id='".$_POST['slctHosp']."'", "", "", "", "");
	$_SESSION['login_hosp_id'] = $getHospData[0]['hosp_id'];
	$_SESSION['login_hosp_name'] = $getHospData[0]['hosp_name'];
	
	header("Location:Set-Appointment?response=update");
}
if(isset($_POST['add_holiday'])){	
	
	$arrFields_holiday = array();
	$arrValues_holiday = array();

	if(!empty($_POST['Prov_Id']))
	{
		$arrFields_holiday[] = 'doc_id';
		$arrValues_holiday[] = $_POST['Prov_Id'];
	}

	if(!empty($_POST['Hosp_id']))
	{
		$arrFields_holiday[] = 'hosp_id';
		$arrValues_holiday[] = $_POST['Hosp_id']; 
	}

	$arrFields_holiday[] = 'doc_type';
	$arrValues_holiday[] = "1"; //1 for prime doctor
	
	$arrFields_holiday[] = 'holiday_date';
	$arrValues_holiday[] = date('Y-m-d',strtotime($_POST['dateadded']));
	
	$arrFields_holiday[] = 'reason';
	$arrValues_holiday[] = $_POST['txt_desc']; 
	
	$insertHoliday=mysqlInsert('doc_holidays',$arrFields_holiday,$arrValues_holiday);
	
	header("Location:Set-Appointment?response=holiday-update");
}	

//ADD / UPDATE HOPITAL AMENITIES
if(isset($_POST['add_amenity']) || isset($_POST['edit_amenity']))
{
	$slct_amenity = $_POST['slct_amenity'];
	$slctHosp 	  = $_POST['slctHosp'];	
	$docImage 	  = basename($_FILES['file-3']['name']);
	
	foreach($_POST['slct_amenity'] as $amenkey => $amenValue ){
		$arrFields2 = array();
		$arrValues2 = array();

		if(!empty($_POST['slctHosp']))
		{
			$arrFields2[] = 'hosp_id';
			$arrValues2[] = $_POST['slctHosp'];
		}
		if(!empty($amenValue))
		{
			$arrFields2[] = 'amenity_id';
			$arrValues2[] = $amenValue;
		}
		$insertAmenity=mysqlInsert('add_hosp_amenity',$arrFields2,$arrValues2);
	}
	
	
	/* Add New Photoes to the perticular hosp_id */
	$chkHospPicture= mysqlSelect("*","add_hosp_picture","hosp_id='".$_POST['slctHosp']."'","","","","");

	
	//UPLOAD MULTIPLE IMAGES
	if($chkHospPicture==false){ 	
	
	$errors= array();
	foreach($_FILES['file-3']['tmp_name'] as $key => $tmp_name ){	
	
		
	$file_name = $_FILES['file-3']['name'][$key];
	$file_size =$_FILES['file-3']['size'][$key];
	$file_tmp =$_FILES['file-3']['tmp_name'][$key];
	$file_type=$_FILES['file-3']['type'][$key];
	
	
		$arrFields3 = array();
		$arrValues3 = array();

		if(!empty($_POST['slctHosp']))
		{
			$arrFields3[] = 'hosp_id';
			$arrValues3[] = $_POST['slctHosp'];
		}

		$arrFields3[] = 'hosp_image';
		$arrValues3[] = $file_name;
		
			
		$bslist_pht=mysqlInsert('add_hosp_picture',$arrFields3,$arrValues3);
		$id= $bslist_pht;


			//UPLOAD COMPRESSED IMAGE
			if ($_FILES["file-3"]["error"][$key] > 0) {
        			$error = $_FILES["file-3"]["error"][$key];
    		} 
    		else if (($_FILES['file-3']['type'][$key] == "image/gif") || 
			($_FILES['file-3']['type'][$key] == "image/jpeg") || 
			($_FILES['file-3']['type'][$key] == "image/png") || 
			($_FILES['file-3']['type'][$key] == "image/pjpeg")) 
			{
				$folder_name	=	"Hosp_image";
				$sub_folder		=	$id;
				$filename		=	$_FILES["file-3"]["name"][$key];
				$file_url		=	$_FILES["file-3"]["tmp_name"][$key];
				fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
			
			 

    		}
			else {
        			$error = "Uploaded image should be jpg or gif or png";
    		}
		
			
		}
		//End of foreach
		
	}//end if
	header("Location:Add-Hospital-Amenity?response=add");	
} //end main if

//ADD ACTIVITY
if(isset($_POST['addActivity']) && !empty($_POST['txtDesc'])){
					$txtDesc = addslashes($_POST['txtDesc']);
	
					$arrFields = array();
					$arrValues = array();

					if(!empty($_POST['patient_id']))
					{
						$arrFields[]= 'patient_id';
						$arrValues[]= $_POST['patient_id'];
					}

					if(!empty($_POST['doc_id']))
					{
						$arrFields[]= 'ref_id';
						$arrValues[]= $_POST['doc_id'];
					}
					
					$arrFields[]= 'chat_note';
					$arrValues[]= $txtDesc;
					$arrFields[]= 'user_id';
					$arrValues[]= "0";
					$arrFields[]= 'TImestamp';
					$arrValues[]= $curDate;
					$arrFields[]= 'msg_send_status';
					$arrValues[]= $_POST['patient_response_send'];
					
					$patientNote=mysqlInsert('chat_notification',$arrFields,$arrValues);
					
					//Change Status2 condition
					$getPatInfo= mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
					$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
					$getBucket=$getPatInfo[0]['bucket_status']; //Get present patient status of perticular referral
					if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						
						$getRef = mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
						//NO. OF RESPONDED COUNT INCREMENTED BY ONE
						$TotCount=$getRef[0]['Tot_responded'];
						$TotCount=$TotCount+1;
						
						$arrFields3[]= 'Tot_responded';
						$arrValues3[]= $TotCount;
						$updateCount=mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['doc_id']."'");
						
						//Update response time 
						//RETREIVE DOCTOR'S FIRST REFERRED DATE
						$getDocResponse = mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","","","","");
													
						$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
						$datetime2 = new DateTime($curDate);
						$interval = $datetime1->diff($datetime2);
														
						$numdays=$interval->format('%a');
						$numhours=$interval->format('%H');
						$nummin=$interval->format('%i');
						$daystominute=$numdays*24*60;
						$hourstominute=$numhours*60;
						$totmin=$daystominute+$hourstominute+$nummin;
						
						$arrFields1[]= 'status2';
						$arrValues1[]= "5";
						$arrFields1[]= 'response_status';
						$arrValues1[]= "2";
						$arrFields1[]= 'response_time';
						$arrValues1[]= $totmin;
						
						//Bucket Status will update only when its below 5
						if($getBucket<5){
						$arrFields2[]= 'bucket_status';
						$arrValues2[]= "5";
						$updateBucket=mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['patient_id']."'");
					
						}
					}
					
					/*$arrFields1[]= 'bucket_status';
					$arrValues1[]= $_POST['Pro4_status2'];*/
					$patientRef=mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
					
					
					//Email Notification to patient
					//$getChatMsg = mysqlSelect("*","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","chat_id desc","","","");
					$getSpec = mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['doc_id']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg=HOST_MAIN_URL."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
					
					
					$getPartnerRespSetting = mysqlSelect("*","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatInfo[0]['patient_src']."'","","","","");
					
					$getMarketPerson = mysqlSelect("*","hosp_marketing_person as a left join mapping_hosp_referrer as b on a.person_id=b.market_person_id","b.partner_id='".$getPartnerRespSetting[0]['partner_id']."' and b.hosp_id='".$getPatInfo[0]['hosp_id']."'","","","","");
					
					
					//Check Doctor response should go to partner / patient directly
			if($_POST['patient_response_send']==1){ // 1 for response should go to patient with a copy to partner & Point of contact(Marketing Person)
					$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
						
			}
			else if($_POST['patient_response_send']==0){ // 0 for response should go only to partner
					//$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$mailto .=$getPartnerRespSetting[0]['Email_id'] .", ";
					$mailto .=$getMarketPerson[0]['person_email'] .", ";
					//$patientnum =$getPatInfo[0]['patient_mob'];
					$partnernum =$getPartnerRespSetting[0]['cont_num1'];
					$marketnum =$getMarketPerson[0]['person_mobile'];
			}
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.$getPatInfo[0]['ref_id'];
		
					/*$doctorresponse='';
						foreach($getChatMsg as $key=>$value){
						
						$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
						} */
					$doctorresponse ="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$txtDesc."<br><span style='float:right;color:#6b6b6b'>".date('d M Y H:i',strtotime($curDate))."</span></p></td></tr>";
							
					$url_page = 'Doc_pat_opinion.php';					
					$url = rawurlencode($url_page);
					$url .= "?docname=".urlencode($getPatInfo[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getPatInfo[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					//$param .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($mailto);
					$url .= "&ccmail=" . urlencode($ccmail);
					send_mail($url);
					
					
					//Message Notification to patient
					if(!empty($patientnum)){
					
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") You have received the opinion from ".$getDocName." for your medical query. Check your registered email. Thx";
					send_msg($patientnum,$responsemsg);
					}
					//Message Notification to partners
					if(!empty($partnernum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
					send_msg($partnernum,$responsemsg);
					}
					
					//Push notification for partners		
					if(!empty($getPartnerRespSetting[0]['gcm_tokenid'])){			
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Many Thanks";
					$subtit= $getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].")";
					$pushDesc=strip_tags($responsemsg);
					$msg="";
					$title="Doctors Response";
					$subtitle=strip_tags($subtit);
					$tickerText="Doctors Response";
					$type="4"; //For Other Message
					$patientid=$getPatInfo[0]['patient_id'];
					$docid=$getPatInfo[0]['ref_id'];
					$blog_id="0";
					$largeimg='large_icon';	
					$regid=$getPartnerRespSetting[0]['gcm_tokenid'];
					$postkey=time();
					
					if(!empty($getPatInfo[0]['doc_photo'])){ 
					$smalimg=$hostname."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}else{
					$smalimg=HOST_MAIN_URL."Hospital/images/leap_push_icon.png";
					}
					
					push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$blog_id,$patientid,$docid,$postkey);
					
					}
					
					//Message Notification to Marketing person
					if(!empty($marketnum)){
					
					$responsemsg = "Dear Sir/Madam, ".$getPatInfo[0]['patient_name']."( ".$getPatInfo[0]['patient_id'].") has received the opinion from ".$getDocName.". Check your registered email. Thx";
					send_msg($marketnum,$responsemsg);
					}
					
					$response=1;	
					header('location:patient-history?response='.$response.'&p='.$_POST['ency_patient_id']);					
					
					
}


//Send Video Call Request
if(isset($_POST['send_video_request']))
{
		$getDate = date('Y-m-d',strtotime($_POST['vid_date']));		
		$getTime = date('H:i:s a',strtotime($_POST['vid_time']));	
		$patient_id = $_POST['patient_id'];
		$doc_id = $_POST['doc_id'];
		
		$arrFields = array();
		$arrValues = array();
		
		$arrFields[]='video_date';
		$arrValues[]=$getDate;
		$arrFields[]='video_time';
		$arrValues[]=$getTime;

		if(!empty($patient_id))
		{
			$arrFields[]='patient_id';
			$arrValues[]=$patient_id;

		}

		if(!empty($doc_id))
		{
			$arrFields[]='doc_id';
			$arrValues[]=$doc_id;
		}
		
		
				
		$arrFields[]='created_date';
		$arrValues[]=$curDate;
		
		
		
		$insert_request=mysqlInsert('video_call_requests',$arrFields,$arrValues);
		$id= $insert_request;
		
		 $portNo = rand(7000, 11000);
		
		//$vid_call_url = 'https://medisensecrm.com/premium/VideoChat/index.php?d='.$doc_id.'&p='.$patient_id.'&v='.$id;
		//$vid_call_url = 'https://maayayoga.com:8443/?u='.md5($patient_id).'&v='.$id.'&t=1'.'&p='.$portNo;
		//$vid_call_url_pat = 'https://maayayoga.com:8443/?u='.md5($doc_id).'&v='.$id.'&t=2'.'&p='.$portNo;
		$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$patient_id."'","","","","");
		
		$vid_call_url = 'https://maayayoga.com/msv/index.php?ref_name='.$_SESSION['user_name'].'&pat_name='.$get_PatientDetails[0]['patient_name'].'&type=1&r='.$admin_id.'_'.$get_PatientDetails[0]['patient_id'];
		$vid_call_url_pat = 'https://maayayoga.com/msv/index.php?ref_name='.$_SESSION['user_name'].'&pat_name='.$get_PatientDetails[0]['patient_name'].'&type=2&r='.$admin_id.'_'.$get_PatientDetails[0]['patient_id'];
		
		$arrFields_status = array();
		$arrValues_status = array();

		if(!empty($id))
		{

			$arrFields_status[]='vid_request_id';
			$arrValues_status[]=$id;
		}
		
		
		$arrFields_status[]='status';
		$arrValues_status[]='1';			// 1-request sent, 2-request accepted, 3-request declined, 4-call in progress, 5-call disconnected, 6-call finished
		$arrFields_status[]='url';
		$arrValues_status[]=$vid_call_url;
		$arrFields_status[]='patient_url';
		$arrValues_status[]=$vid_call_url_pat;
		$arrFields_status[]='created_date';
		$arrValues_status[]=$curDate;
		
		$insert_status =mysqlInsert('video_call_status',$arrFields_status,$arrValues_status);
		
		
		$urlPat= str_replace(' ', '%20', $vid_call_url_pat);
		$getDocInfo = mysqlSelect("*","referal","ref_id='".$doc_id."'" ,"","","","");
		$getPatInfo = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$patient_id."'" ,"","","","");
		
		if(!empty($getPatInfo[0]['patient_mob'])){
			$mobile = $getPatInfo[0]['patient_mob'];
			$msg = "You have received video call request from the doctor ".$getDocInfo[0]['ref_name']." on Date: ".date('d-m-Y',strtotime($_POST['vid_date']))." and Time: ".date('H:i:s a',strtotime($_POST['vid_time']))." \n Please click on the below link to accept/decline the call.\n\n ".$urlPat." \nThx, \n".$getDocInfo[0]['ref_name'];
			send_msg($mobile,$msg);
		}
		
		$response="updated";
		header("Location:My-Patient-Details?p=".md5($patient_id));
}

//Add Offers & Events
if(isset($_POST['addCampaigns'])){

	$startDate= $_POST['startendDate'];
	
	$start_date= date('Y-m-d',strtotime($_POST['campaign_start_date']));
	$end_date= date('Y-m-d',strtotime($_POST['campaign_end_date']));
	$org_committee= $_POST['campaign_org_committee'];
	$key_speaker= $_POST['campaign_key_speaker'];
	$web_link= $_POST['campaign_web_link'];
	$slctHosp= $_SESSION['login_hosp_id'];
	
	$cont_num= $_POST['campaign_cont_num'];
	$cont_email= $_POST['campaign_cont_email'];
	
	$offerTitle= addslashes($_POST['campaign_offer_title']);
	$Descr= addslashes($_POST['campaign_descr']);
	$Email_Descr= addslashes($_POST['campaign_email_descr']);
	$SMS_Descr= addslashes($_POST['campaign_sms_descr']);
	$scheduled_date= date('Y-m-d',strtotime($_POST['scheduled_date']));
	$event_pic = basename($_FILES['txtPhoto']['name']);	
	$event_attachment = basename($_FILES['txtBrochure']['name']);
	
	$getCompany= mysqlSelect("company_id","hosp_tab","hosp_id='".$slctHosp."'","","","","");
	
	$event_key=time();
	$arrFields = array();
	$arrValues = array();

	if(!empty($event_key))
	{

		$arrFields[]= 'event_trans_id';
		$arrValues[]= $event_key;
	}
		

	
		
	$arrFields[]= 'start_date';
	$arrValues[]= $start_date;	
	$arrFields[]= 'end_date';
	$arrValues[]= $end_date;

	$arrFields[]= 'organising_committee';
	$arrValues[]= $org_committee;	
	$arrFields[]= 'kenote_speakers';
	$arrValues[]= $key_speaker;	
	
	$arrFields[]= 'website_link';
	$arrValues[]= $web_link;
	
	$arrFields[]= 'title';
	$arrValues[]= $offerTitle;
	$arrFields[]= 'description';
	$arrValues[]= $Descr;
	$arrFields[]= 'event_type';
	$arrValues[]= "1";

	if(!empty($getCompany[0]['company_id']))
	{
		$arrFields[]= 'company_id';
		$arrValues[]= $getCompany[0]['company_id'];	
	}
	if(!empty($slctHosp))
	{
		$arrFields[]= 'hosp_id';
		$arrValues[]= $slctHosp;
	}
	if(!empty($admin_id))
	{
		$arrFields[]= 'doc_id';
		$arrValues[]= $admin_id;
	}
	
	$arrFields[]= 'photo';
	$arrValues[]= $event_pic;
	$arrFields[]= 'job_contact_info';
	$arrValues[]= $cont_num;
	$arrFields[]= 'contact_email';
	$arrValues[]= $cont_email;
	$arrFields[]= 'description_attachment';
	$arrValues[]= $event_attachment;
	$arrFields[]= 'created_date';
	$arrValues[]= $curDate;

	$arrFields[]= 'campaign_scheduled_date';
	$arrValues[]= $scheduled_date;
	$arrFields[]= 'campaign_email_content';
	$arrValues[]= $Email_Descr;
	$arrFields[]= 'campaign_sms_content';
	$arrValues[]= $SMS_Descr;
	
	if(isset($_POST['addCampaigns'])){
	$addoffers=mysqlInsert('offers_events',$arrFields,$arrValues);
	$id		  = $addoffers;
	
	$arrFields1 = array();
	$arrValues1 = array();

	if(!empty($id))
	{
		$arrFields1[]= 'listing_type_id';
		$arrValues1[]= $id;
	}

	$arrFields1[]= 'listing_type';
	$arrValues1[]= "Events";

	if(!empty($getCompany[0]['company_id']))
	{
		$arrFields1[]= 'company_id';
		$arrValues1[]= $getCompany[0]['company_id'];
	}
	$arrFields1[]= 'Create_Date';
	$arrValues1[]= $curDate;

	if(!empty($slctHosp))
	{
		$arrFields1[]= 'hosp_id';
		$arrValues1[]= $slctHosp;
	}
	
	
	$addblogsofferlist=mysqlInsert('blogs_offers_events_listing',$arrFields1,$arrValues1);
	$searchTags=$_POST['searchTags'].",".$offerTitle;
	//Insert to search tags table
	$arrFields_search = array();
	$arrValues_search = array();

	if(!empty($id))
	{
		$arrFields_search[]= 'type_id';
		$arrValues_search[]= $id;
	}
	$arrFields_search[]= 'type_name';
	$arrValues_search[]= "Events";
	$arrFields_search[]= 'search_result';
	$arrValues_search[]= $searchTags;

	$addSearch=mysqlInsert('blogs_offers_events_search',$arrFields_search,$arrValues_search);
	
	$response="Added";
	} else if(isset($_POST['editCampaigns'])){
		$updateOffer=mysqlUpdate('offers_events',$arrFields,$arrValues,"event_id='".$_POST['Event_Id']."'");	
			
	$id= $_POST['Event_Id'];
	$response="update";
	}
	
	/* Uploading image file */ 
				if(basename($_FILES['txtPhoto']['name']!==""))
				{ 
			
					$folder_name	=	"Eventimages";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtPhoto']['name'];
					$file_url		=	$_FILES['txtPhoto']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload

					
				}
				
				/* Uploading Event Brochure */ 
				if(basename($_FILES['txtBrochure']['name']!==""))
				{ 
					$folder_name	=	"EventAttachments";
					$sub_folder		=	$id;
					$filename		=	$_FILES['txtBrochure']['name'];
					$file_url		=	$_FILES['txtBrochure']['tmp_name'];
					fileuploadFunc($folder_name,$sub_folder,$filename,$file_url); //for file upload
					
					
				}
		//Here we need to Send Push notification to mapped partners
		//$getrefPartlist = mysqlSelect("*","our_partners as a left join mapping_hosp_referrer as b on a.partner_id=b.partner_id","b.hosp_id='".$slctHosp."'","","","","");
		//Retrieve all partners gcm id
		//$getrefPartlist = mysqlSelect("gcm_tokenid as GCM","our_partners","login_status=1","","","","");
		//Retrieve all doctors gcm id
		$getDoclist = mysqlSelect("gcm_tokenid as GCM","referal","gcm_tokenid!=''","","","","");
		
		$msg=$Descr;
		$title=$offerTitle;
		$subtitle=$Descr;
		$tickerText="Test Ticker";
		$type="2"; //For Event type 2
		$patientid="0";
		$docid=$_POST['selectref'];
			if(!empty($event_pic)){
			$largeimg=HOST_MAIN_URL."Eventimages/".$id."/".$event_pic;
			}
			else
			{
			$largeimg='large_icon';	
			}
			$smalimg=HOST_MAIN_URL."../Hospital/images/leap_push_icon.png";
		//Push Notification to partner	
		/*foreach($getrefPartlist as $PartList){
		$regid=$PartList['GCM'];
		
		push_notification_refer($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$smalimg,$id,$patientid,$docid,$event_key);
		}*/
		//Push Notification to Doctors
		/*foreach($getDoclist as $DocList){
		$regid=$DocList['GCM'];
		
		push_notification($regid,$msg,$title,$subtitle,$tickerText,$type,$largeimg,$id,$patientid,$docid,$event_key);
		}*/
		//End Push notification functionality
		
	header("Location:Add-Campaign?response=".$response);
	
}

?>