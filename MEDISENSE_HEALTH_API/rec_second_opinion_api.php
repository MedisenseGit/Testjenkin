<?php ob_start();
 error_reporting(0);
 session_start(); 

// required headers
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
echo $data ->api_key;

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 3;
$Follow_Date = date('Y-m-d',strtotime($cur_Date) + (24*3600*$add_days));

include('../premium/short_url.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

function ageCalculator($dob){
    if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    }else{
        return 0;
    }
}

function getAuthToken($limit)
{
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
}


if(HEALTH_API_KEY == $data ->api_key  && !empty($data ->mobileNum) && !empty($data ->emailID))
{ 
    $member_id = $data ->member_id;
	$login_id = $data ->login_id;
	
	$txtName = addslashes($data ->patientName);
	$txtDOB = $data ->dob;
	$txtWeight = $data ->patientWeight;
	$txtheight = $data ->patientheight;
	
	$txtGender = $data ->patientGender;
	$txtpatientblood = $data ->patientblood;
	
	$txtMobile = addslashes($data ->mobileNum);
	$txtEmail = addslashes($data ->emailID);
	$txtAddress = addslashes($data ->address);
	$txtCity = addslashes($data ->city);
	$txtState = addslashes($data ->state);
	$txtCountry = addslashes($data ->country);
	$txtpincode = addslashes($data ->pincode);
	
	$txtMedicalQuery = addslashes($data ->medicalQuery);
	$txtBriefDescription = addslashes($data ->briefDescription);
	$txtChiefComplaint= addslashes($data ->MedicalComplaints);
	
	
	
	$txtHypertension = $data ->hypertension;
	$txtDiabetes = $data ->diabetes;
	$txtContactPerson = addslashes($data ->contactPerson);
	$txtSpecialty = addslashes($data ->specialty);
	$txtTreatingDoctor = addslashes($data ->treatingDoctor);
	$txtTreatingHospital = addslashes($data ->treatingHospital);
	$txtLookingFor = addslashes($data ->lookingFor);
	$txtPreferredCity = addslashes($data ->preferredCity);
	
	$txtPrefered_country = addslashes($data ->Prefered_country);
	$txtPreferredHospital = addslashes($data ->preferredHospital);
	$txtPreferredDoctor = addslashes($data ->preferredDoctor);
		
	//$txtAge =  ageCalculator($txtDOB);
	$txtAge =  $txtDOB;
	$txtDate = $Cur_Date;
	$TransId = $data ->transid;
	

	
	
		$arrFields = array();
		$arrValues = array();
	
		$arrFields[] = 'TImestamp';
		$arrValues[] = $txtDate;
		$arrFields[] = 'member_id';
		$arrValues[] = $member_id;
		$arrFields[] = 'login_user_id';
		$arrValues[] = $login_id;
		
		
		$arrFields[] = 'patient_name';
		$arrValues[] = $txtName;
		$arrFields[] = 'patient_age';
		$arrValues[] = $txtAge;
		$arrFields[] = 'patient_email';
		$arrValues[] = $txtEmail;
		$arrFields[] = 'patient_gen';
		$arrValues[] = $txtGender;
		
		$arrFields[] = 'pat_blood';
		$arrValues[] = $txtpatientblood;
		
		
		$arrFields[] = 'weight';
		$arrValues[] = $txtWeight;
		
		$arrFields[] = 'height';
		$arrValues[] = $txtheight;
		
		
		
		$arrFields[] = 'hyper_cond';
		$arrValues[] = $txtHypertension;
		$arrFields[] = 'diabetes_cond';
		$arrValues[] = $txtDiabetes;
		$arrFields[] = 'pref_city';
		$arrValues[] = $txtPreferredCity;
		
		$arrFields[] = 'pincode';
		$arrValues[] = $txtpincode;
		
		
		$arrFields[] = 'pref_hosp';
		$arrValues[] = $txtPreferredHospital;
		$arrFields[] = 'pref_doc';
		$arrValues[] = $txtPreferredDoctor;
		
		$arrFields[] = 'pref_country';
		$arrValues[] = $txtPrefered_country;
		
		$arrFields[] = 'contact_person';
		$arrValues[] = $txtContactPerson;
		$arrFields[] = 'patient_mob';
		$arrValues[] = $txtMobile;
		$arrFields[] = 'patient_loc';
		$arrValues[] = $txtCity;
		$arrFields[] = 'pat_state';
		$arrValues[] = $txtState;
		$arrFields[] = 'pat_country';
		$arrValues[] = $txtCountry;
		
		$arrFields[] = 'patient_addrs';
		
		$arrValues[] = $txtAddress;
		$arrFields[] = 'patient_src';
		$arrValues[] = '8';		// Medisense Health source id
		$arrFields[] = 'medDept';
		$arrValues[] = $txtSpecialty;		// Specialty ID
		$arrFields[] = 'currentTreatDoc';
		$arrValues[] = $txtTreatingDoctor;
		$arrFields[] = 'currentTreatHosp';
		$arrValues[] = $txtTreatingHospital;
		$arrFields[] = 'patient_complaint';
		$arrValues[] = $txtChiefComplaint;
		$arrFields[] = 'patient_desc';
		$arrValues[] = $txtBriefDescription;
		$arrFields[] = 'pat_query';
		$arrValues[] = $txtMedicalQuery;
		$arrFields[] = 'looking_for';
		$arrValues[] = $txtLookingFor;
		$arrFields[] = 'company_id';
		$arrValues[] = '413';					// For Medisense Health
		$arrFields[] = 'system_date';
		$arrValues[] = $cur_Date;
		$arrFields[] = 'transaction_id';
		$arrValues[] = $TransId;
	
		
		$ChkPatient = $objQuery->mysqlSelect("*","patient_tab","system_date='".$cur_Date."'and patient_email='".$txtEmail."'","","","","");
		if($ChkPatient==false || $member_id!=0){
			
		$patientcraete=$objQuery->mysqlInsert('patient_tab',$arrFields,$arrValues);
		$patient_id = mysql_insert_id();
		
		
		/*$txtbp           = addslashes($data ->bp);
		$txtthyroid      = addslashes($data ->thyroid);
		$txthypertension = addslashes($data ->hypertension);
		$txtasthama      = addslashes($data ->asthama);
		$txtcholestrol   = addslashes($data ->cholestrol);
		$txtepilepsy     = addslashes($data ->epilepsy);
		$txtdiabetic      = addslashes($data ->diabetic);
		$txtallergies     = addslashes($data ->allergies);
		$user_id          = addslashes($data ->user_id);
		
			$arrhealthFields = array();
			$arrhealthValues = array();
			
			$arrhealthFields[] = 'member_id';
			$arrhealthValues[] = $member_id;
			
			$arrhealthFields[] = 'user_id';
			$arrhealthValues[] = $user_id;
			
			$arrhealthFields[] = 'bp';
			$arrhealthValues[] = $txtbp;
			
			$arrhealthFields[] = 'hypertension';
			$arrhealthValues[] = $txthypertension;
			
			$arrhealthFields[] = 'cholesterol';
			$arrhealthValues[] = $txtcholestrol;
			
			$arrhealthFields[] = 'diabetic';
			$arrhealthValues[] = $txtdiabetic;
			
			$arrhealthFields[] = 'thyroid';
			$arrhealthValues[] = $txtthyroid;
			
			$arrhealthFields[] = 'asthama';
			$arrhealthValues[] = $txtasthama;
			
			$arrhealthFields[] = 'epilepsy';
			$arrhealthValues[] = $txtepilepsy;
			
			$arrhealthFields[] = 'allergies_any';
			$arrhealthValues[] = $txtallergies;
			
			
			
			$usercraete=$objQuery->mysqlInsert('user_family_general_health',$arrhealthFields,$arrhealthValues); //user_family_general_health*/
			
		
			$arrFields1 = array();
			$arrValues1 = array();
			$arrFields1[] = 'patient_id';
			$arrValues1[] = $patient_id;
			$arrFields1[] = 'status1';
			$arrValues1[] = "1";
			$arrFields1[] = 'status2';
			$arrValues1[] = "1";
			$arrFields1[] = 'bucket_status';
			$arrValues1[] = "1";
			$arrFields1[] = 'timestamp';
			$arrValues1[] = $Cur_Date;
			
			$usercraete=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
			
			$response["status"] = "true";
			echo(json_encode($data ->patientName));
		
			exit();
			$msg="Patient Registered on ".$Cur_Date;
			$arrFields2 = array();
			$arrValues2 = array();
			$arrFields2[] = 'patient_id';
			$arrValues2[] = $patient_id;
			$arrFields2[] = 'ref_id';
			$arrValues2[] = "0";
			$arrFields2[] = 'chat_note';
			$arrValues2[] = $msg;
			$arrFields2[] = 'user_id';
			$arrValues2[] = "9";
			$arrFields2[] = 'TImestamp';
			$arrValues2[] = $Cur_Date;
			
			$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
			
			$getConfigInfo = $objQuery->mysqlSelect("*","crm_configuration","" ,"","","","");
			if($txtCountry == "India"){ 
				$opcost=$getConfigInfo[0]['opinion_cost'];
				$gst=18; //18% GST
		
			$total_tax_amount=($opcost*$gst)/100;	
			$tot_amount=$opcost+$total_tax_amount;	
			}
			else{
				$opcost=$getConfigInfo[0]['international_op_cost'];
			//$gst=18; //18% GST
		
			$total_tax_amount=0;//($opcost*$gst)/100;	
			$tot_amount=$opcost;	
			}
			
			
			
			$arrFields3=array();
			$arrValues3=array();
			
			$arrFields3[] = 'Payment_id';
			$arrValues3[] = "Null";
			
			$arrFields3[] = 'patient_id';
			$arrValues3[] = $patient_id;
			
			$arrFields3[] = 'transaction_id';
			$arrValues3[] = $TransId;
			
			$arrFields3[] = 'patient_name';
			$arrValues3[] = $txtName;
			
			$arrFields3[] = 'service_type';
			$arrValues3[] = "Second Opinion";
			
			$arrFields3[] = 'ref_id';
			$arrValues3[] = "1";
			
			$arrFields3[] = 'email_id';
			$arrValues3[] = $txtEmail;
					
			$arrFields3[] = 'mobile_no';
			$arrValues3[] = $txtMobile;		
					
			$arrFields3[] = 'transaction_time';
			$arrValues3[] = $Cur_Date;
			
			
			$arrFields3[] = 'opinion_cost';
			$arrValues3[] = $opcost;
			
			$arrFields3[] = 'GST';
			$arrValues3[] = $total_tax_amount;
			
			$arrFields3[] = 'amount';
			$arrValues3[] = $tot_amount;
			
			$transcreate=$objQuery->mysqlInsert('customer_transaction',$arrFields3,$arrValues3);
			
			// Send Email
			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patient_id."'" ,"","","","");
		/*	if($getPatInfo[0]['patient_email']!=""){
				if($getPatInfo[0]['patient_src']=="11"){
					$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_mediassist.jpg";
				}
				else{
					$mailTemplate="https://medisensehealth.com/assets/img/Email_Template_low1.jpg";
				}
					$url = "https://referralio.com/EMAIL/patmail.php?patmail=".$getPatInfo[0]['patient_email']."&patid=".$getPatInfo[0]['patient_id']."&patname=".$getPatInfo[0]['patient_name']."&mailtemp=".$mailTemplate;
		
					$ch = curl_init (); // setup a curl
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					$output = curl_exec ( $ch );
					curl_close ( $ch );
			}

			//SMS notification to Successfull Registered Patient
				if($getPatInfo[0]['patient_mob']!=""){
					$mobile = $getPatInfo[0]['patient_mob'];
			
					$msg = urlencode ( "Hi ".$getPatInfo[0]['patient_name'].", We have registered your medical query with number : ". $getPatInfo[0]['patient_id'] . "  We will try to get back in 24-48 Hrs. Thanks. MedisenseHealth.com" );
					$url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=medisense&password=medi2015&type=5&dlr=0&destination=" . $mobile . "&source=MEDICL&message=" . $msg;
					$ch = curl_init (); // setup a curl
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					$output = curl_exec ( $ch );
					curl_close ( $ch );
				}	*/		
			
			//EMAIL NOTIFICATION TO MEDISENSE PANEL
			if($getPatInfo[0]['patient_email']!=""){
				$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			
					if($getPatInfo[0]['patient_gen']==1){
						$Pat_Gen="Male";
					} else {
						$Pat_Gen="Female";
					}
					if($getPatInfo[0]['hyper_cond']==1){
							
							$Hyper_Cond="High";
							
						} else if($getPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="Normal";
						} else if($getPatInfo[0]['hyper_cond']==3){
							
							$Hyper_Cond="Low";
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
					curl_close ( $ch );
			}
			
					
			//$success = array('code' => "S100", 'authToken' =>$authToken, 'healthHubId' => $txtHealthHubId,'healthHubOrderId' => $txtHealthHubOrderId,'mobileNum' => $txtMobile,'description' => "Case received successfully.");
		//	echo json_encode($success);
			
			
		}
		else {
			
			$response["status"] = "false";
			echo(json_encode($response));
		}
	
}

if(HEALTH_API_KEY == $_POST['api_key'] && !empty($_POST['trans_id']))
	
	{
		$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","transaction_id='".$_POST['trans_id']."'" ,"","","","");
		$getConfigInfo = $objQuery->mysqlSelect("*","crm_configuration","" ,"","","","");
		$getTransactionInfo = $objQuery->mysqlSelect("*","customer_transaction","transaction_id='".$_POST['trans_id']."'" ,"","","","");
		
		if($_POST['txn_status']=="TXN_SUCCESS" && !empty($_POST['TXNID'])){
			
			//Update Transaction
			$arrFields_trans[] = 'Payment_id';
			$arrValues_trans[] = $_POST['TXNID'];
				
			$updateTransaction=$objQuery->mysqlUpdate('customer_transaction',$arrFields_trans,$arrValues_trans,"transaction_id='".$_POST['trans_id']."'");
		
			//Patient Status
			$arrFields_patient[] = 'transaction_status';
			$arrValues_patient[] = $_POST['txn_status'];
				
			$editPatient=$objQuery->mysqlUpdate('patient_tab',$arrFields_patient,$arrValues_patient,"transaction_id='".$_POST['trans_id']."'");
			$response["status"] = "true";
			$response["patient_details"] = $getPatInfo;
			$response["getConfigInfo"] = $getConfigInfo;
			$response["getTransactionInfo"] = $getTransactionInfo;
			echo(json_encode($response));
		}
		else{
			$response["status"] = "true";
			$response["patient_details"] = $getPatInfo;
			$response["getConfigInfo"] = $getConfigInfo;
			$response["getTransactionInfo"] = $getTransactionInfo;
			echo(json_encode($response));
		}
		
	}

//Update Transaction ID
if(HEALTH_API_KEY == $data ->api_key && !empty($data ->patient_id) && !empty($data ->docid))
	
	{
		$arrFields=array();
		$arrValues=array();
		
		$arrFields[] = 'Payment_id';
		$arrValues[] = "Null";
		
		$arrFields[] = 'patient_id';
		$arrValues[] = $data ->patient_id;
		
		$arrFields[] = 'transaction_id';
		$arrValues[] = $data ->transaction_id;
		
		$arrFields[] = 'patient_name';
		$arrValues[] = $data ->patient_name;
		
		$arrFields[] = 'service_type';
		$arrValues[] = $data ->service;
		
		$arrFields[] = 'ref_id';
		$arrValues[] = $data ->docid;
		
		$arrFields[] = 'email_id';
		$arrValues[] = $data ->email;
				
		$arrFields[] = 'mobile_no';
		$arrValues[] = $data ->mobile;		
				
		$arrFields[] = 'transaction_time';
		$arrValues[] = $data ->trans_date;
		
		
		$arrFields[] = 'opinion_cost';
		$arrValues[] = $data ->opcost;
		
		$arrFields[] = 'GST';
		$arrValues[] = $data ->total_tax_amount;
		
		$arrFields[] = 'amount';
		$arrValues[] = $data ->tot_amount;
		
		$usercraete=$objQuery->mysqlInsert('customer_transaction',$arrFields,$arrValues);
		
		//Patient Status
		$arrFields_patient[] = 'transaction_id';
		$arrValues_patient[] = $data ->transaction_id;				
		$editPatient=$objQuery->mysqlUpdate('patient_tab',$arrFields_patient,$arrValues_patient,"patient_id='".$data ->patient_id."'");
		
		$getTransactionInfo = $objQuery->mysqlSelect("*","customer_transaction","transaction_id='".$data ->transaction_id."'","","","","");
		
			$response["status"] = "true";
			$response["TransactionInfo"] = $getTransactionInfo;
			
			echo(json_encode($response));
		
	}
?>


