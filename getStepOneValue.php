<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

require_once("get_config.php");
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(API_KEY == $_POST['apikey']){  //TO CHECK AUTHENTICATION OF POST VALUES
	$txtDate = $Cur_Date;
	$txtName = $_POST['patname'];
	$txtId = $_POST['subid'];
	$TransId = $_POST['transid'];
	$txtAge = $_POST['patage'];
	$txtMail = $_POST['patmail'];
	$txtGen = $_POST['patgend'];
	$chkDate = date('Y-m-d',strtotime($Cur_Date));
	$slctMerital = $_POST['patmerital'];
	$txtQualification = $_POST['patqualification'];
	$txtWeight = $_POST['patweight'];
	$hyperCond = $_POST['pathyper'];
	$diabetesCond = $_POST['patdiabetes'];	
	$patBlood = addslashes($_POST['patblood']);	
	$patDept = $_POST['patDept'];
	$txtContact = addslashes($_POST['patcontactper']);
	$txtMob = addslashes($_POST['patmobile']);
	$txtAddress = addslashes($_POST['pataddress']);
	$txtLoc = addslashes($_POST['patloc']);
	$txtCountry = addslashes($_POST['patCountry']);
	$txtState = addslashes($_POST['patState']);
	
	$txtProf = addslashes($_POST['patprof']);
	$txtTreatDoc = addslashes($_POST['patTreatDoc']);
	$txtTreatHosp = addslashes($_POST['patTreatHosp']);
	$txtNote1 = addslashes($_POST['patcomp']);
	$txtNote2 = addslashes($_POST['patdesc']);
	$txtNote3 = addslashes($_POST['patquery']);
	
	$prefCty = addslashes($_POST['prefcity']);
	$prefHosp = addslashes($_POST['prefhosp']);
	$prefDoc = addslashes($_POST['prefdoc']);
	
	$patSource = $_POST['patsource'];
	$lookingfor = $_POST['lookingfor'];
	
	$arrFields = array();
	$arrValues = array();
		
		$arrFields[] = 'TImestamp';
		$arrValues[] = $txtDate;
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'subscriber_id';
		$arrValues[] = $txtId;
		$arrFields[] = 'patient_email';
		$arrValues[] = $txtMail;
		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;
		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGen;
		
		$arrFields[] = 'merital_status';
		$arrValues[] = $slctMerital;
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $hyperCond;
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $diabetesCond;
		$arrFields[] = 'pat_blood';
		$arrValues[] = $patBlood;
		
		$arrFields[] = 'contact_person';
		$arrValues[] = $txtContact;
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMob;
		$arrFields[] = 'patient_addrs';
		$arrValues[] = $txtAddress;
		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtLoc;
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;
		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;
		$arrFields[] = 'patient_src';
		$arrValues[] = $patSource;
		
		$arrFields[] = 'profession';
		$arrValues[] = $txtProf;
		$arrFields[] = 'proflevel';
		$arrValues[] = "";
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatDoc;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatHosp;
		$arrFields[] = 'medDept';
		$arrValues[] = $patDept;		

		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtNote1;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtNote2;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtNote3;
		$arrFields[] = 'assigned_to';
		$arrValues[] = '0';
		$arrFields[] = 'user_id';
		$arrValues[] = '9';
		$arrFields[] = 'company_id';
		$arrValues[] = '3';
		$arrFields[] = 'attchState';
		$arrValues[] = $AttachState;
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
		
		$arrFields[] = 'pref_city';
		$arrValues[] = $prefCty;
		$arrFields[] = 'pref_hosp';
		$arrValues[] = $prefHosp;
		$arrFields[] = 'pref_doc';
		$arrValues[] = $prefDoc;
		
		$arrFields[] = 'looking_for';
		$arrValues[] = $lookingfor;
			
		$ChkPatient= $objQuery->mysqlSelect("*","patient_tab","system_date='".$cur_Date."'and patient_email='".$txtMail."'","","","","");
	
	if($ChkPatient==false){	
		$usercraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
		$id = mysql_insert_id();
		
		$arrFields1 = array();
		$arrValues1 = array();
		$arrFields1[] = 'patient_id';
		$arrValues1[] = $id;
		$arrFields1[] = 'status1';
		$arrValues1[] = "1";
		$arrFields1[] = 'status2';
		$arrValues1[] = "1";
		$arrFields1[] = 'bucket_status';
		$arrValues1[] = "1";
		$arrFields1[] = 'timestamp';
		$arrValues1[] = $Cur_Date;
		
		$usercraete=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
		
		$msg="Patient Registered on ".$Cur_Date;
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'patient_id';
		$arrValues2[] = $id;
		$arrFields2[] = 'ref_id';
		$arrValues2[] = "0";
		$arrFields2[] = 'chat_note';
		$arrValues2[] = $msg;
		$arrFields2[] = 'user_id';
		$arrValues2[] = "9";
		$arrFields2[] = 'TImestamp';
		$arrValues2[] = $Cur_Date;
		
		$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
		
					
	$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$id."'" ,"","","","");
	if($getPatInfo[0]['patient_email']!=""){
		if($getPatInfo[0]['patient_src']=="11"){
		$mailTemplate=HOST_HEALTH_URL."assets/img/Email_Template_mediassist.jpg";
		}
		else{
			$mailTemplate=HOST_HEALTH_URL."assets/img/Email_Template_low1.jpg";
		}
	$url = "https://referralio.com/EMAIL/patmail.php?patmail=".$getPatInfo[0]['patient_email']."&patid=".$getPatInfo[0]['patient_id']."&patname=".$getPatInfo[0]['patient_name']."&mailtemp=".$mailTemplate;
		
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
					
					
					
					
		}	

		//SMS notification to Successfull Registered Patient
					if($getPatInfo[0]['patient_mob']!=""){
					$mobile = $getPatInfo[0]['patient_mob'];
					$msg = urlencode ( "Hi ".$getPatInfo[0]['patient_name'].", We have registered your medical query with number : ". $getPatInfo[0]['patient_id'] . ", We will try to get back in 24-48 Hrs. Thanks. MedisenseHealth.com" );
					
					$url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=medisense&password=medi2015&type=5&dlr=0&destination=" . $mobile . "&source=HCHKIN&message=" . $msg;
		
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
					
					}
					
		//EMAIL NOTIFICATION TO MEDISENSE PANEL
		if($getPatInfo[0]['patient_email']!=""){
		$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			
	
	
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}
						if($getPatInfo[0]['hyper_cond']==1){
							
							$Hyper_Cond="Yes";
							
						} else if($getPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="No";
						}
						if($getPatInfo[0]['diabetes_cond']==1){
							
							$Diabetic_Cond="Yes";
							
						} else if($getPatInfo[0]['diabetes_cond']==2){
							
							$Diabetic_Cond="No";
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
						if($getPatInfo[0]['qualification']==""){
							$pat_qualification="NS";
						} else {
							$pat_qualification=$getPatInfo[0]['qualification'];
						}
						
						if($getPatInfo[0]['pat_country']=="India"){
							$queryType="D";
						} else {
							$queryType="I";
						}
					$tomail="medical@medisense.me";
					$fromail=$getPatInfo[0]['patient_email'];
			
					$url_page = 'medisense_notice.php';
					
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
					
					$url .= "&promail1=" . urlencode($tomail);
					$url .= "&patmail1=" . urlencode($fromail);
					$url .= "&patcontact=" . urlencode($getPatInfo[0]['contact_person']);
					$url .= "&patdepart=" . urlencode($getDepartment[0]['spec_name']);
					$url .= "&patprof=" . urlencode($getPatInfo[0]['profession']);
					$url .= "&patlead=" . urlencode($Lead_Cond);
					$url .= "&patTime=" . urlencode($Time);
					$url .= "&queryType=" . urlencode($queryType);
					
							
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
		}
					
	}
}	

?>


