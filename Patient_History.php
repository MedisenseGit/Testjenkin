<?php ob_start();
 error_reporting(0);
 session_start(); 

$admin_id = $_SESSION['admin_id'];
$Company_id=$_SESSION['comp_id'];
$user_flag = $_SESSION['flag_id'];
$_SESSION['Pat_Id']=$_GET['pat_id'];

$ccmail="medical@medisense.me";

include('JIO_API/send_patient_status.php');

$_SESSION['contatInclude']=0;
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$Assign_Date=date('Y-m-d H:i:s');

$Cur_Date_Time=date('d-m-Y H:i a');

if(empty($admin_id)){
header("Location:index.php");
}
//connect to the DB
$resDB = mysql_connect("localhost", "root", "qe9Ke9BcdMT4KQY9@25Nova");
mysql_select_db("nova_crm", $resDB);
function createKey(){
	//create a random key
	$strKey = md5(microtime());
	
	//check to make sure this key isnt already in use
	$resCheck = mysql_query("SELECT count(*) FROM patient_attachment WHERE downloadkey = '{$strKey}' LIMIT 1");
	$arrCheck = mysql_fetch_assoc($resCheck);
	if($arrCheck['count(*)']){
		//key already in use
		return createKey();
	}else{
		//key is OK
		return $strKey;
	}
}


function hyphenize($string) {
	
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

include('send_text_message.php');
require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

//TO CHECK PATIENT ATTACHMENT IS REQUIRED OR NOT
if(isset($_POST['cmdRepoStatus'])){
	
	$arrValues = array();
	$arrFields = array();
	
	$arrFields[]= 'repnotattach';
	$arrValues[]= $_POST['repoState'];
	
	$assignTask=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'");
}

//TO SEND REMINDER TO Doctors
if(isset($_POST['cmdSendReminder']) && $_POST['status_id']==1){
	$referer_id=$_POST['reminder_id'];
	
	$chkreflist = $objQuery->mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$referer_id."'","","","","");
	$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
	$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
	$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$referer_id."'");
	$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			
	
	
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}
						if($getPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="Normal";

						} else if($getPatInfo[0]['hyper_cond']==1) {

							$Hyper_Cond="High";

						} else if($getPatInfo[0]['hyper_cond']==3) {

							$Hyper_Cond="Low";

						} else {
							$Hyper_Cond="NA";
						}
						if($getPatInfo[0]['diabetes_cond']==2){
							
							$Diabetic_Cond="No";
						} else if($getPatInfo[0]['diabetes_cond']==1){
							$Diabetic_Cond="Yes";
						} else {
							$Diabetic_Cond="NA";
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
						if($get_pro[0]['communication_status']==1)
						{
							$docmail= $get_pro[0]['ref_mail'];
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
					$subject="Reminder-".$paid_msg."[".$Lead_Cond."]- ".$Time."/ Ref. No.".$queryType." - ".$getPatInfo[0]['patient_id']." Patient Information";
					$url_page = 'refdocmail.php';
					
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
					$url .= "&curtreatdoc=" . urlencode($getPatInfo[0]['currentTreatDoc']);
					$url .= "&curtreathosp=" . urlencode($getPatInfo[0]['currentTreatHosp']);
					if(!empty($getPatAttach[0]['external_attachments'])){						
						$url .= "&externalAttachLink=" . urlencode($getPatAttach[0]['external_attachments']);
					}
					else
					{
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
						if(!empty($getPatAttach[10]['attach_id'])){
						$url .= "&patattachid11=" . urlencode($getPatAttach[10]['attach_id']);
						$url .= "&patattachname11=" . urlencode($getPatAttach[10]['attachments']);
						}
						if(!empty($getPatAttach[11]['attach_id'])){
						$url .= "&patattachid12=" . urlencode($getPatAttach[11]['attach_id']);
						$url .= "&patattachname12=" . urlencode($getPatAttach[11]['attachments']);
						}
						if(!empty($getPatAttach[12]['attach_id'])){
						$url .= "&patattachid13=" . urlencode($getPatAttach[12]['attach_id']);
						$url .= "&patattachname13=" . urlencode($getPatAttach[12]['attachments']);
						}
						if(!empty($getPatAttach[13]['attach_id'])){
						$url .= "&patattachid14=" . urlencode($getPatAttach[13]['attach_id']);
						$url .= "&patattachname14=" . urlencode($getPatAttach[13]['attachments']);
						}
						if(!empty($getPatAttach[14]['attach_id'])){
						$url .= "&patattachid15=" . urlencode($getPatAttach[14]['attach_id']);
						$url .= "&patattachname15=" . urlencode($getPatAttach[14]['attachments']);
						}
						if(!empty($getPatAttach[15]['attach_id'])){
						$url .= "&patattachid16=" . urlencode($getPatAttach[15]['attach_id']);
						$url .= "&patattachname16=" . urlencode($getPatAttach[15]['attachments']);
						}
						if(!empty($getPatAttach[16]['attach_id'])){
						$url .= "&patattachid17=" . urlencode($getPatAttach[16]['attach_id']);
						$url .= "&patattachname17=" . urlencode($getPatAttach[16]['attachments']);
						}
						if(!empty($getPatAttach[17]['attach_id'])){
						$url .= "&patattachid18=" . urlencode($getPatAttach[17]['attach_id']);
						$url .= "&patattachname18=" . urlencode($getPatAttach[17]['attachments']);
						}
						if(!empty($getPatAttach[18]['attach_id'])){
						$url .= "&patattachid19=" . urlencode($getPatAttach[18]['attach_id']);
						$url .= "&patattachname19=" . urlencode($getPatAttach[18]['attachments']);
						}
						if(!empty($getPatAttach[19]['attach_id'])){
						$url .= "&patattachid20=" . urlencode($getPatAttach[19]['attach_id']);
						$url .= "&patattachname20=" . urlencode($getPatAttach[19]['attachments']);
						}
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
					
					// echo "output".$output;
					
					curl_close ( $ch );
					//$Successmessage1 = "Reminder Sent to Doctors Successfully";
				
					
					$arrFields1 = array();
					$arrValues1 = array();
					$txtProNote1= "Reminder Sent to volunteering Doctors Successfully";
					
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					
					
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "*REMINDER* Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by email, please check your email inbox or spam box - Many Thanks";
					send_msg($mobile,$msg);
					
					}
					//WHEN DOCTOR COMMUNICATION STATUS IS IN BOTH HOSP & DOC
					if($doc_contact!="" && $get_pro[0]['message_status']==1)
					{
					$mobile = $doc_contact;
					$msg = "*REMINDER* Dear Doctor it's from Medisensehealth.com, We have sent you a query of patient " . $getPatInfo[0]['patient_name'] . " by email, please check your email inbox or spam box - Many Thanks";
					send_msg($mobile,$msg);
					
					}
	
}


//TO SEND PATIENT RESPONSE MAIL TO Doctors
if(isset($_POST['cmdSendReminder']) && $_POST['status_id']==2){
	$referer_id=$_POST['reminder_id'];
	
	$chkreflist = $objQuery->mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$referer_id."'","","","","");
	$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
	$getPatReply = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['Pat_Id']."'and user_id=13" ,"","","","");
	$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
	$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$referer_id."'");
	$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			
	
	
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}

						if($getPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="Normal";

						} else if($getPatInfo[0]['hyper_cond']==1) {

							$Hyper_Cond="High";

						} else if($getPatInfo[0]['hyper_cond']==3) {

							$Hyper_Cond="Low";

						} else {
							$Hyper_Cond="NA";
						}
						if($getPatInfo[0]['diabetes_cond']==2){
							
							$Diabetic_Cond="No";
						} else if($getPatInfo[0]['diabetes_cond']==1){
							$Diabetic_Cond="Yes";
						} else {
							$Diabetic_Cond="NA";
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
						if($get_pro[0]['communication_status']==1)//SEND REF. MAIL ONLY TO DOCTOR
						{
							$pro_mail1=$get_pro[0]['ref_mail'];
							$pro_contact=$get_pro[0]['contact_num'];
							
						} else if($get_pro[0]['communication_status']==2) //SEND REF. MAIL ONLY TO HOSPITAL
						{
							$pro_mail1=$get_pro[0]['hosp_email'];
							$pro_mail2=$get_pro[0]['hosp_email1'];
							$pro_mail3=$get_pro[0]['hosp_email2'];
							$pro_contact=$get_pro[0]['hosp_contact'];
						} else if($get_pro[0]['communication_status']==3) //SEND REF. MAIL BOTH HOSPITAL & DOCTOR
						{
							$pro_mail1=$get_pro[0]['hosp_email'];
							$pro_mail2=$get_pro[0]['hosp_email1'];
							$pro_mail3=$get_pro[0]['hosp_email2'];
							$pro_mail4=$get_pro[0]['ref_mail'];
							$pro_contact1=$get_pro[0]['hosp_contact'];
							$pro_contact2=$get_pro[0]['contact_num'];
						}
			
					$url_page = 'patresponse_mail.php';
					
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
					$url .= "&patreply=" . urlencode($getPatReply[0]['chat_note']);
					$url .= "&patqualification=" . urlencode($pat_qualification);
					$url .= "&patblood=" . urlencode($getPatInfo[0]['pat_blood']);
					if(!empty($getPatAttach[0]['external_attachments'])){						
						$url .= "&externalAttachLink=" . urlencode($getPatAttach[0]['external_attachments']);
					}
					else
					{
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
					}
					$url .= "&proname=" . urlencode($get_pro[0]['ref_name']);
				
					$url .= "&promail1=" . urlencode($pro_mail1);
					$url .= "&promail2=" . urlencode($get_pro[0]['ref_mail1']);
					$url .= "&promail3=" . urlencode($get_pro[0]['ref_mail2']);
					
					$url .= "&promail4=" . urlencode($pro_mail2);
					$url .= "&promail5=" . urlencode($pro_mail3);
					$url .= "&promail6=" . urlencode($pro_mail4);
					
					
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
					//$Successmessage1 = "Reminder Sent to Doctors Successfully";
				
					
					$arrFields1 = array();
					$arrValues1 = array();
					$txtPatResp= "Patient Response Sent Successfully";
					
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtPatResp;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					
					
					//SMS notification to Refering Doctors only when messge_status is active
					if($get_pro[0]['message_status']==1 && $pro_contact!=""){
					$mobile = $pro_contact;
					$msg = "Dear Doctor, Pleases check your mail to see the feedback on ".$getPatInfo[0]['patient_name']." - Many Thanks";
					
					send_msg($mobile,$msg);
					
					}
	
}


//TO CHECK COMPANY USER WORK ASSIGN STATUS
if(isset($_POST['assignStatus'])){
	$bus_id = $_POST['pat_id1'];
	$status = $_POST['assign_id'];
	$arrValues = array();
	$arrFields = array();

	$arrFields[] = 'assigned_to';
	$arrValues[] = $status;
	
	$arrFields[] = 'assign_date';
	$arrValues[] = $Assign_Date;
	
	

	$assignTask=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$bus_id."'");
	
	//Message Send to Shashi and Vikas only when query assign to those people
	
					$getPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$bus_id."'" ,"","","","");
					$getMobile = $objQuery->mysqlSelect("*","chckin_user","chk_userid='".$status."'" ,"","","","");
					$mobile = $getMobile[0]['contact_no'];
					if(!empty($getMobile[0]['contact_no'])){
					$msg = "Dear Sir, We have assigned you a query of patient " . $getPatDet[0]['patient_name'] . ",& Patient Id " . $getPatDet[0]['patient_id'] . "  please login to our website and let us know - Many Thanks";
					
					send_msg($mobile,$msg);
					}
				
	
}

//TO CHANGE PATIENT STATUS1 OPEN / CLOSE  
if(isset($_POST['cmdPatStatus']) && !empty($_POST['pat_status_id'])){
	$status2_val=$_POST['pat_status_id'];
	$chkPatref = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_POST['Pat_Id']."'","","","","");
	if($chkPatref==true){
		
		
			$arrFields = array();
			$arrValues = array();
			
			$arrFields[]= 'status1';
			$arrValues[]= $status2_val;
			
			
						
			$assignTask=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'");
		
	}
	
	
}

//TO CHECK PROVIDER STATUS
if(isset($_POST['cmdStatus'])){
	$Status_id = $_POST['status_id'];
	
	
	$chkPatref = $objQuery->mysqlSelect("*","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['Pat_Id']."' and b.ref_id='".$_POST['Ref_Id']."'","","","","");
	$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['Ref_Id']."'","","","","");
	$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['Ref_Id']."'","","","","");
	$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id']."'","chat_id desc","","","");
	
	if($chkPatref==true){
	$arrFields = array();
	$arrValues = array();
	$arrFields[]= 'provider_status';
	$arrValues[]= $Status_id;
	
	
	$arrFields[]= 'status2';
	$arrValues[]= $Status_id;
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id']."'");
	
	$arrFields1 = array();
	$arrValues1 = array();
	
	
	if($Status_id==5){			//If Status2 is Responded Then Bucket Status "Responded"
		$bucket_state="5";					
	
									//Both are appear in "Respond" Bucket only
	
	}if($Status_id==6){			//If Status2 is Response P-Failed Then Bucket Status "Respondse P-Failed"
		$bucket_state="6";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	
	if($Status_id==7){			//If Status2 is Staged Then Bucket Status "Staged"
		$bucket_state="7";					//Both are appear in "Converted" Bucket only
	}
	if($Status_id==8){			//If Status2 is OP Converted Then Bucket Status "OP Converted"
		$bucket_state="8";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	if($Status_id==9){			//If Status2 is IP Converted Then Bucket Status "IP Converted"
		$bucket_state="9";			//Both are appear in "Invoice" Bucket only
	} if($Status_id==11){		//If Status2 is Invoiced Then Bucket Status "Invoiced"
		$bucket_state="11";			//Both are appear in "Invoice" Bucket only
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	if($Status_id==12){		//If Status2 is Payment received Then Bucket Status "Payment received"
		$bucket_state="12";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	if($Status_id==10){			//If Status2 is Not Converted Then Bucket Status "Not Converted"
		$bucket_state="10";			//appear in "Close" Bucket only
		
	$arrFields1[]= 'status1';
	$arrValues1[]= "2";
	}
	
	if($Status_id==13){			//If Status2 is OP Visited Then Bucket Status "OP Converted"
		$bucket_state="8";
	
	}
	if($Status_id==14){			
					
		//If Status2 is Not Responded Then Bucket Status remains as it is
	}
	else if($Status_id!=14){
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= $bucket_state;
	}
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");

	//Medisense Track Note
	
	//DOCTOR RESPONSE SENT TO PATIENT EMAIL & MOBILE ONLY WHEN DOCTOR STATUS WOULD BE RESPONDED
	if($Status_id==5){
	//NO. OF RESPONDED COUNT INCREMENTED BY ONE
		$TotCount=$getRef[0]['Tot_responded'];
		$TotCount=$TotCount+1;
		
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[]= 'Tot_responded';
		$arrValues3[]= $TotCount;
		$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['Ref_Id']."'");
		
		//Begin auto response condition
		if($chkPatref[0]['response_time']==0 && $chkPatref[0]['response_status']!=1) {
			//Update response time 
			//RETREIVE DOCTOR'S RESPONSE
			$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id']."'","","","","");
										
			$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
			$datetime2 = new DateTime($Cur_Date);
			$interval = $datetime1->diff($datetime2);
											
			$numdays=$interval->format('%a');
			$numhours=$interval->format('%H');
			$nummin=$interval->format('%i');
			$daystominute=$numdays*24*60;
			$hourstominute=$numhours*60;
			$totmin=$daystominute+$hourstominute+$nummin;
			//echo "Day to Minute: ".$daystominute."<br>Hours to minute:".$hourstominute."<br>Minute to minute:".$nummin."<br>Tot. minute: ".$totmin;
			//To check, If No. of days more than 100, then its not applicable
			if($numdays<100){
			
			$arrFields4[]= 'response_time';
			$arrValues4[]= $totmin;
			$arrFields4[]= 'response_status';
			$arrValues4[]= "2";
			$updateCount=$objQuery->mysqlUpdate('patient_referal',$arrFields4,$arrValues4,"ref_id='".$_POST['Ref_Id']."' and patient_id='".$_POST['Pat_Id']."'");
			}
		}//End of auto response condition
		
	$msg=$getRef[0]['ref_name']." reponded -".$Cur_Date_Time; //MEDISENSE NOTE
	
	
	//EMAIL notification to Patient
		if(!empty($chkPatref[0]['patient_email'])){
		$doctorresponse='';
		foreach($getChatMsg as $key=>$value){
		
		$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
		}
	
		
		//Doc Info EMAIL notification Sent to Patient
			
			if(!empty($getRef[0]['doc_photo'])){
				$docimg=HOST_MAIN_URL."Doc/".$getRef[0]['ref_id']."/".$getRef[0]['doc_photo'];
			}	
			else{
				$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
			}
		$find=array("/",",","&"," ");
		$getDocSpec=urlencode(str_replace($find, "-", $getSpec[0]['spec_name']));
		$getDocName=urlencode(str_replace(' ','-',$getRef[0]['ref_name']));
		$getDocCity=urlencode(str_replace(' ','-',$getRef[0]['ref_address']));
		$getDocState=urlencode(str_replace(' ','-',$getRef[0]['doc_state']));
		$getDocHosp=urlencode(str_replace(' ','-',$getRef[0]['hosp_name']));
		$getDocHospAdd=urlencode(str_replace(' ','-',$getRef[0]['hosp_addrs']));

		$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($getRef[0]['ref_id']);
		
		$docAddress=$getSpec[0]['spec_name'];
		
		//TO CHECK MEDI ASSIST Source
			if($chkPatref[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
				$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
			}
			else{
				$mas_logo="";
			}
					$url_page = 'Doc_pat_opinion.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?docname=".urlencode($getRef[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&doccity=" . urlencode($getRef[0]['ref_address']);
					$url .= "&docid=" . urlencode($getRef[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($chkPatref[0]['patient_id']);
					$url .= "&patname=" . urlencode($chkPatref[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($chkPatref[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);		
							
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
		
					//SMS notification to Patient
					if($chkPatref[0]['patient_mob']!=""){
					$mobile = $chkPatref[0]['patient_mob'];
					$responsemsg = "Dear ".$chkPatref[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx, Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
					
					
	}
	if($Status_id==6){
	$msg="Response-P failed -".$Cur_Date_Time;
	}
	if($Status_id==7){
	$msg="Patient Staged -".$Cur_Date_Time;
	}
	if($Status_id==8){
	$msg="OP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==9){
	$msg="IP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==10){
	$msg="Not Converted -".$Cur_Date_Time;
	}
	if($Status_id==11){
	$msg="Invoice raised to".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==12){
		$msg=$getRef[0]['ref_name']."- Payment received -".$Cur_Date_Time;
		
	
	}
	if($Status_id==13){
	$msg=$getRef[0]['ref_name']."- OP Visited -".$Cur_Date_Time;
	}
	if($Status_id==14){
	$msg=$getRef[0]['ref_name']."- Not Responded -".$Cur_Date_Time;
	}
	
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_POST['Pat_Id'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = "0";
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $msg;
	$arrFields2[] = 'user_id';
	$arrValues2[] = $admin_id;
	$arrFields2[] = 'status_id';
	$arrValues2[] = $Status_id;
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
	
	if(empty($_POST['txtpro1'])){
	$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	}
	}
	
	
	
	//TO CHECK IF REF1 DELETE OPTION. IF ITS TRUE THEN DELETE PERTICULAR REF. HISTORY FROM patient_referal & chat_notification Table 
	
	if(!empty($_POST['Del_Ref_Id'])){
	$objQuery->mysqlDelete('patient_referal',"ref_id='".$_POST['Del_Ref_Id']."'and patient_id='".$_POST['Pat_Id']."'");
	$objQuery->mysqlDelete('chat_notification',"ref_id='".$_POST['Del_Ref_Id']."'and patient_id='".$_POST['Pat_Id']."'");
	}
	
}
//TO CHECK PROVIDER1 STATUS
if(isset($_POST['cmdStatus1'])){
	$Status_id = $_POST['status_id1'];
	
	$chkPatref = $objQuery->mysqlSelect("*","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['Pat_Id']."' and b.ref_id='".$_POST['Ref_Id1']."'","","","","");
	$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['Ref_Id1']."'","","","","");
	$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['Ref_Id1']."'","","","","");
	$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id1']."'","chat_id desc","","","");
	
	
	if($chkPatref==true){
	$arrFields = array();
	$arrValues = array();
	$arrFields[]= 'provider_status';
	$arrValues[]= $Status_id;
	
	$arrFields[]= 'status2';
	$arrValues[]= $Status_id;
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id1']."'");
	
	$arrFields1 = array();
	$arrValues1 = array();
	
	
	if($Status_id==5){			//If Status2 is Responded Then Bucket Status "Responded"
		$bucket_state="5";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";			//Both are appear in "Respond" Bucket only
	}if($Status_id==6){			//If Status2 is Response P-Failed Then Bucket Status "Respondse P-Failed"
		$bucket_state="6";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	
	if($Status_id==7){			//If Status2 is Staged Then Bucket Status "Staged"
		$bucket_state="7";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Converted" Bucket only
	}if($Status_id==8){			//If Status2 is OP Converted Then Bucket Status "OP Converted"
		$bucket_state="8";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==9){			//If Status2 is IP Converted Then Bucket Status "IP Converted"
		$bucket_state="9";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";//Both are appear in "Invoice" Bucket only
	} if($Status_id==11){		//If Status2 is Invoiced Then Bucket Status "Invoiced"
		$bucket_state="11";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Invoice" Bucket only
	}if($Status_id==12){		//If Status2 is Payment received Then Bucket Status "Payment received"
		$bucket_state="12";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==10){			//If Status2 is Not Converted Then Bucket Status "Not Converted"
		$bucket_state="10";			//appear in "Close" Bucket only
		
	$arrFields1[]= 'status1';
	$arrValues1[]= "2";
	}
	
	if($Status_id==13){			//If Status2 is OP Visited Then Bucket Status "OP Converted"
		$bucket_state="8";
	
	}
	if($Status_id==14){			
					
		//If Status2 is Not Responded Then Bucket Status remains as it is
	}
	else if($Status_id!=14){
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= $bucket_state;
	}
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");
	
	//Medisense Track Note
		
	//DOCTOR RESPONSE SENT TO PATIENT EMAIL & MOBILE ONLY WHEN DOCTOR STATUS WOULD BE RESPONDED
	if($Status_id==5){
		
		//NO. OF RESPONDED COUNT INCREMENTED BY ONE
		$TotCount=$getRef[0]['Tot_responded'];
		$TotCount=$TotCount+1;
		
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[]= 'Tot_responded';
		$arrValues3[]= $TotCount;
		$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['Ref_Id1']."'");
		
		//Begin auto response condition
		if($chkPatref[0]['response_time']==0 && $chkPatref[0]['response_status']!=1) {
			//Update response time 
			//RETREIVE DOCTOR'S RESPONSE
			$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id1']."'","","","","");
										
			$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
			$datetime2 = new DateTime($Cur_Date);
			$interval = $datetime1->diff($datetime2);
											
			$numdays=$interval->format('%a');
			$numhours=$interval->format('%H');
			$nummin=$interval->format('%i');
			$daystominute=$numdays*24*60;
			$hourstominute=$numhours*60;
			$totmin=$daystominute+$hourstominute+$nummin;
			//echo "Day to Minute: ".$daystominute."<br>Hours to minute:".$hourstominute."<br>Minute to minute:".$nummin."<br>Tot. minute: ".$totmin;
			//To check, If No. of days more than 100, then its not applicable
			if($numdays<100){
			
			$arrFields4[]= 'response_time';
			$arrValues4[]= $totmin;
			$arrFields4[]= 'response_status';
			$arrValues4[]= "2";
			$updateCount=$objQuery->mysqlUpdate('patient_referal',$arrFields4,$arrValues4,"ref_id='".$_POST['Ref_Id1']."' and patient_id='".$_POST['Pat_Id']."'");
			}
		}//End of auto response condition
		
	$msg=$getRef[0]['ref_name']." reponded -".$Cur_Date_Time; //MEDISENSE NOTE
	
	
	//EMAIL notification to Patient
		if(!empty($chkPatref[0]['patient_email'])){
		$doctorresponse='';
		foreach($getChatMsg as $key=>$value){
		
		$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
		}
	
		
		//Doc Info EMAIL notification Sent to Patient
			
			if(!empty($getRef[0]['doc_photo'])){
				$docimg=HOST_MAIN_URL."Doc/".$getRef[0]['ref_id']."/".$getRef[0]['doc_photo'];
			}	
			else{
				$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
			}
		$find=array("/",",","&"," ");
		$getDocSpec=urlencode(str_replace($find, "-", $getSpec[0]['spec_name']));
		$getDocName=urlencode(str_replace(' ','-',$getRef[0]['ref_name']));
		$getDocCity=urlencode(str_replace(' ','-',$getRef[0]['ref_address']));
		$getDocState=urlencode(str_replace(' ','-',$getRef[0]['doc_state']));
		$getDocHosp=urlencode(str_replace(' ','-',$getRef[0]['hosp_name']));
		$getDocHospAdd=urlencode(str_replace(' ','-',$getRef[0]['hosp_addrs']));

		$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($getRef[0]['ref_id']);
		
		$docAddress=$getSpec[0]['spec_name'];
		
		//TO CHECK MEDI ASSIST Source
			if($chkPatref[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
				$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
			}
			else{
				$mas_logo="";
			}
		
					$url_page = 'Doc_pat_opinion.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?docname=".urlencode($getRef[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getRef[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($chkPatref[0]['patient_id']);
					$url .= "&patname=" . urlencode($chkPatref[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($chkPatref[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);		
							
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
		
		//SMS notification to Patient
					if($chkPatref[0]['patient_mob']!=""){
					$mobile = $chkPatref[0]['patient_mob'];
					$responsemsg = "Dear ".$chkPatref[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx, Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
							
					
	}	
	if($Status_id==6){
	$msg="Response-P failed -".$Cur_Date_Time;
	}
	if($Status_id==7){
	$msg="Patient Staged -".$Cur_Date_Time;
	}
	if($Status_id==8){
	$msg="OP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==9){
	$msg="IP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==10){
	$msg="Not Converted -".$Cur_Date_Time;
	}
	if($Status_id==11){
	$msg="Invoice raised to".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==12){
	$msg=$getRef[0]['ref_name']."- Payment received -".$Cur_Date_Time;
	}
	if($Status_id==13){
	$msg=$getRef[0]['ref_name']."- OP Visited -".$Cur_Date_Time;
	}
	if($Status_id==14){
	$msg=$getRef[0]['ref_name']."- Not Responded -".$Cur_Date_Time;
	}
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_POST['Pat_Id'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = "0";
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $msg;
	$arrFields2[] = 'user_id';
	$arrValues2[] = $admin_id;
	$arrFields2[] = 'status_id';
	$arrValues2[] = $Status_id;
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
	if(empty($_POST['txtpro2'])){				
	$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	}
	}
//TO CHECK IF REF1. DELETE OPTION.IF ITS TRUE THEN DELETE PERTICULAR REF. HISTORY FROM patient_referal & chat_notification Table 
	
	else if($_POST['Del_Ref_Id1']!==0){
	$objQuery->mysqlDelete('patient_referal',"ref_id='".$_POST['Del_Ref_Id1']."'and patient_id='".$_POST['Pat_Id']."'");
	$objQuery->mysqlDelete('chat_notification',"ref_id='".$_POST['Del_Ref_Id1']."'and patient_id='".$_POST['Pat_Id']."'");
	}
}

//TO CHECK PROVIDER2 STATUS
if(isset($_POST['cmdStatus2'])){
	$Status_id = $_POST['status_id2'];
	
	$chkPatref = $objQuery->mysqlSelect("*","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['Pat_Id']."' and b.ref_id='".$_POST['Ref_Id2']."'","","","","");
	$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['Ref_Id2']."'","","","","");
	$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['Ref_Id2']."'","","","","");
	$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id2']."'","chat_id desc","","","");
	
	
	if($chkPatref==true){
	$arrFields = array();
	$arrValues = array();
	$arrFields[]= 'provider_status';
	$arrValues[]= $Status_id;
	
	$arrFields[]= 'status2';
	$arrValues[]= $Status_id;
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id2']."'");
	
	$arrFields1 = array();
	$arrValues1 = array();
	
	
	if($Status_id==5){			//If Status2 is Responded Then Bucket Status "Responded"
		$bucket_state="5";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";			//Both are appear in "Respond" Bucket only
	}if($Status_id==6){			//If Status2 is Response P-Failed Then Bucket Status "Respondse P-Failed"
		$bucket_state="6";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	
	if($Status_id==7){			//If Status2 is Staged Then Bucket Status "Staged"
		$bucket_state="7";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Converted" Bucket only
	}if($Status_id==8){			//If Status2 is OP Converted Then Bucket Status "OP Converted"
		$bucket_state="8";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==9){			//If Status2 is IP Converted Then Bucket Status "IP Converted"
		$bucket_state="9";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";//Both are appear in "Invoice" Bucket only
	} if($Status_id==11){		//If Status2 is Invoiced Then Bucket Status "Invoiced"
		$bucket_state="11";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Invoice" Bucket only
	}if($Status_id==12){		//If Status2 is Payment received Then Bucket Status "Payment received"
		$bucket_state="12";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==10){			//If Status2 is Not Converted Then Bucket Status "Not Converted"
		$bucket_state="10";			//appear in "Close" Bucket only
		
	$arrFields1[]= 'status1';
	$arrValues1[]= "2";
	}
	
	if($Status_id==13){			//If Status2 is OP Visited Then Bucket Status "OP Converted"
		$bucket_state="8";
	
	}
	if($Status_id==14){			
					
		//If Status2 is Not Responded Then Bucket Status remains as it is
	}
	else if($Status_id!=14){
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= $bucket_state;
	}
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");
	
	//Medisense Track Note
		
	//DOCTOR RESPONSE SENT TO PATIENT EMAIL & MOBILE ONLY WHEN DOCTOR STATUS WOULD BE RESPONDED
	if($Status_id==5){
		
		//NO. OF RESPONDED COUNT INCREMENTED BY ONE
		$TotCount=$getRef[0]['Tot_responded'];
		$TotCount=$TotCount+1;
		
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[]= 'Tot_responded';
		$arrValues3[]= $TotCount;
		$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['Ref_Id2']."'");
		
		//Begin auto response condition
		if($chkPatref[0]['response_time']==0 && $chkPatref[0]['response_status']!=1) {
			//Update response time 
			//RETREIVE DOCTOR'S RESPONSE
			$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id2']."'","","","","");
										
			$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
			$datetime2 = new DateTime($Cur_Date);
			$interval = $datetime1->diff($datetime2);
											
			$numdays=$interval->format('%a');
			$numhours=$interval->format('%H');
			$nummin=$interval->format('%i');
			$daystominute=$numdays*24*60;
			$hourstominute=$numhours*60;
			$totmin=$daystominute+$hourstominute+$nummin;
			//echo "Day to Minute: ".$daystominute."<br>Hours to minute:".$hourstominute."<br>Minute to minute:".$nummin."<br>Tot. minute: ".$totmin;
			//To check, If No. of days more than 100, then its not applicable
			if($numdays<100){
			
			$arrFields4[]= 'response_time';
			$arrValues4[]= $totmin;
			$arrFields4[]= 'response_status';
			$arrValues4[]= "2";
			$updateCount=$objQuery->mysqlUpdate('patient_referal',$arrFields4,$arrValues4,"ref_id='".$_POST['Ref_Id2']."' and patient_id='".$_POST['Pat_Id']."'");
			}
		}//End of auto response condition
		
	$msg=$getRef[0]['ref_name']." reponded -".$Cur_Date_Time; //MEDISENSE NOTE
	
	
	//EMAIL notification to Patient
		if(!empty($chkPatref[0]['patient_email'])){
		$doctorresponse='';
		foreach($getChatMsg as $key=>$value){
		
		$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
		}
	
		
		//Doc Info EMAIL notification Sent to Patient
			
			if(!empty($getRef[0]['doc_photo'])){
				$docimg=HOST_MAIN_URL."Doc/".$getRef[0]['ref_id']."/".$getRef[0]['doc_photo'];
			}	
			else{
				$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
			}
		
		$find=array("/",",","&"," ");
		$getDocSpec=urlencode(str_replace($find, "-", $getSpec[0]['spec_name']));
		$getDocName=urlencode(str_replace(' ','-',$getRef[0]['ref_name']));
		$getDocCity=urlencode(str_replace(' ','-',$getRef[0]['ref_address']));
		$getDocState=urlencode(str_replace(' ','-',$getRef[0]['doc_state']));
		$getDocHosp=urlencode(str_replace(' ','-',$getRef[0]['hosp_name']));
		$getDocHospAdd=urlencode(str_replace(' ','-',$getRef[0]['hosp_addrs']));

		$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocCity.'-'.$getDocState.'/'.md5($getRef[0]['ref_id']);
		
		$docAddress=$getSpec[0]['spec_name'];
		
		//TO CHECK MEDI ASSIST Source
			if($chkPatref[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
				$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
			}
			else{
				$mas_logo="";
			}
			
					$url_page = 'Doc_pat_opinion.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?docname=".urlencode($getRef[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getRef[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($chkPatref[0]['patient_id']);
					$url .= "&patname=" . urlencode($chkPatref[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($chkPatref[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);		
							
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
		
		//SMS notification to Patient
					if($chkPatref[0]['patient_mob']!=""){
					$mobile = $chkPatref[0]['patient_mob'];
					$responsemsg = "Dear ".$chkPatref[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx, Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
					
	}	
	if($Status_id==6){
	$msg="Response-P failed -".$Cur_Date_Time;
	}
	if($Status_id==7){
	$msg="Patient Staged -".$Cur_Date_Time;
	}
	if($Status_id==8){
	$msg="OP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==9){
	$msg="IP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==10){
	$msg="Not Converted -".$Cur_Date_Time;
	}
	if($Status_id==11){
	$msg="Invoice raised to".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==12){
	$msg=$getRef[0]['ref_name']."- Payment received -".$Cur_Date_Time;
	}
	if($Status_id==13){
	$msg=$getRef[0]['ref_name']."- OP Visited -".$Cur_Date_Time;
	}
	if($Status_id==14){
	$msg=$getRef[0]['ref_name']."- Not Responded -".$Cur_Date_Time;
	}
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_POST['Pat_Id'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = "0";
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $msg;
	$arrFields2[] = 'user_id';
	$arrValues2[] = $admin_id;
	$arrFields2[] = 'status_id';
	$arrValues2[] = $Status_id;
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
	if(empty($_POST['txtpro2'])){				
	$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	}
	}
//TO CHECK IF REF1. DELETE OPTION.IF ITS TRUE THEN DELETE PERTICULAR REF. HISTORY FROM patient_referal & chat_notification Table 
	
	else if($_POST['Del_Ref_Id1']!==0){
	$objQuery->mysqlDelete('patient_referal',"ref_id='".$_POST['Del_Ref_Id1']."'and patient_id='".$_POST['Pat_Id']."'");
	$objQuery->mysqlDelete('chat_notification',"ref_id='".$_POST['Del_Ref_Id1']."'and patient_id='".$_POST['Pat_Id']."'");
	}
}

//TO CHECK PROVIDER2 STATUS
if(isset($_POST['cmdStatus3'])){
	$Status_id = $_POST['status_id3'];
	
	$chkPatref = $objQuery->mysqlSelect("*","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['Pat_Id']."' and b.ref_id='".$_POST['Ref_Id3']."'","","","","");
	$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['Ref_Id3']."'","","","","");
	$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['Ref_Id3']."'","","","","");
	$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id3']."'","chat_id desc","","","");
	
	
	if($chkPatref==true){
	$arrFields = array();
	$arrValues = array();
	$arrFields[]= 'provider_status';
	$arrValues[]= $Status_id;
	
	$arrFields[]= 'status2';
	$arrValues[]= $Status_id;
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id3']."'");
	
	$arrFields1 = array();
	$arrValues1 = array();
		
	if($Status_id==5){			//If Status2 is Responded Then Bucket Status "Responded"
		$bucket_state="5";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";			//Both are appear in "Respond" Bucket only
	}if($Status_id==6){			//If Status2 is Response P-Failed Then Bucket Status "Respondse P-Failed"
		$bucket_state="6";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";
	}
	
	
	if($Status_id==7){			//If Status2 is Staged Then Bucket Status "Staged"
		$bucket_state="7";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Converted" Bucket only
	}if($Status_id==8){			//If Status2 is OP Converted Then Bucket Status "OP Converted"
		$bucket_state="8";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==9){			//If Status2 is IP Converted Then Bucket Status "IP Converted"
		$bucket_state="9";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";//Both are appear in "Invoice" Bucket only
	} if($Status_id==11){		//If Status2 is Invoiced Then Bucket Status "Invoiced"
		$bucket_state="11";	
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";		//Both are appear in "Invoice" Bucket only
	}if($Status_id==12){		//If Status2 is Payment received Then Bucket Status "Payment received"
		$bucket_state="12";
	$arrFields1[]= 'status1';
	$arrValues1[]= "1";	
	}
	
	if($Status_id==10){			//If Status2 is Not Converted Then Bucket Status "Not Converted"
		$bucket_state="10";			//appear in "Close" Bucket only
		
	$arrFields1[]= 'status1';
	$arrValues1[]= "2";
	}
	
	if($Status_id==13){			//If Status2 is OP Visited Then Bucket Status "OP Converted"
		$bucket_state="8";
	
	}
	
	if($Status_id==14){			
					
		//If Status2 is Not Responded Then Bucket Status remains as it is
	}
	else if($Status_id!=14){
	$arrFields1[]= 'bucket_status';
	$arrValues1[]= $bucket_state;
	}
	
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");
	
	//Medisense Track Note
		
	//DOCTOR RESPONSE SENT TO PATIENT EMAIL & MOBILE ONLY WHEN DOCTOR STATUS WOULD BE RESPONDED
	if($Status_id==5){
		
		//NO. OF RESPONDED COUNT INCREMENTED BY ONE
		$TotCount=$getRef[0]['Tot_responded'];
		$TotCount=$TotCount+1;
		
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[]= 'Tot_responded';
		$arrValues3[]= $TotCount;
		$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['Ref_Id3']."'");
		
		//Begin auto response condition
		if($chkPatref[0]['response_time']==0 && $chkPatref[0]['response_status']!=1) {
			//Update response time 
			//RETREIVE DOCTOR'S RESPONSE
			$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['Pat_Id']."'and ref_id='".$_POST['Ref_Id3']."'","","","","");
										
			$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
			$datetime2 = new DateTime($Cur_Date);
			$interval = $datetime1->diff($datetime2);
											
			$numdays=$interval->format('%a');
			$numhours=$interval->format('%H');
			$nummin=$interval->format('%i');
			$daystominute=$numdays*24*60;
			$hourstominute=$numhours*60;
			$totmin=$daystominute+$hourstominute+$nummin;
			//echo "Day to Minute: ".$daystominute."<br>Hours to minute:".$hourstominute."<br>Minute to minute:".$nummin."<br>Tot. minute: ".$totmin;
			//To check, If No. of days more than 100, then its not applicable
			if($numdays<100){
			
			$arrFields4[]= 'response_time';
			$arrValues4[]= $totmin;
			$arrFields4[]= 'response_status';
			$arrValues4[]= "2";
			$updateCount=$objQuery->mysqlUpdate('patient_referal',$arrFields4,$arrValues4,"ref_id='".$_POST['Ref_Id3']."' and patient_id='".$_POST['Pat_Id']."'");
			}
		}//End of auto response condition
		
	$msg=$getRef[0]['ref_name']." reponded -".$Cur_Date_Time; //MEDISENSE NOTE
	
	
	//EMAIL notification to Patient
		if(!empty($chkPatref[0]['patient_email'])){
		$doctorresponse='';
		foreach($getChatMsg as $key=>$value){
		
		$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
		}
	
		
		//Doc Info EMAIL notification Sent to Patient
			
			if(!empty($getRef[0]['doc_photo'])){
				$docimg=HOST_MAIN_URL."Doc/".$getRef[0]['ref_id']."/".$getRef[0]['doc_photo'];
			}	
			else{
				$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
			}
		$find=array("/",",","&"," ");
		$getDocSpec=urlencode(str_replace($find, "-", $getSpec[0]['spec_name']));
		$getDocName=urlencode(str_replace(' ','-',$getRef[0]['ref_name']));
		$getDocCity=urlencode(str_replace(' ','-',$getRef[0]['ref_address']));
		$getDocState=urlencode(str_replace(' ','-',$getRef[0]['doc_state']));
		$getDocHosp=urlencode(str_replace(' ','-',$getRef[0]['hosp_name']));
		$getDocHospAdd=urlencode(str_replace(' ','-',$getRef[0]['hosp_addrs']));

		$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocCity.'-'.$getDocState.'/'.md5($getRef[0]['ref_id']);
		
		$docAddress=$getSpec[0]['spec_name'];
		
		//TO CHECK MEDI ASSIST Source
			if($chkPatref[0]['patient_src']=="11"){   //IF SO, THEN SEND MEDI ASSIST LOGO
				$mas_logo="<img src='".HOST_HEALTH_URL."new_assets/images/mediassist-logo-new.png' alt='Medi Assist' width='78' height='62'>";
			}
			else{
				$mas_logo="";
			}
					$url_page = 'Doc_pat_opinion.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?docname=".urlencode($getRef[0]['ref_name']);
					$url .= "&docresponse=" . urlencode($doctorresponse);
					$url .= "&docid=" . urlencode($getRef[0]['ref_id']);
					$url .= "&docimg=".urlencode($docimg);
					$url .= "&doclink=".urlencode($Link);
					$url .= "&maslogo=".urlencode($mas_logo);
					$url .= "&docspec=".urlencode($getSpec[0]['spec_name']);					
					$url .= "&patid=" . urlencode($chkPatref[0]['patient_id']);
					$url .= "&patname=" . urlencode($chkPatref[0]['patient_name']);					
					$url .= "&patmail=" . urlencode($chkPatref[0]['patient_email']);
					$url .= "&ccmail=" . urlencode($ccmail);		
							
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
		
		//SMS notification to Patient
					if($chkPatref[0]['patient_mob']!=""){
					$mobile = $chkPatref[0]['patient_mob'];
					$responsemsg = "Dear ".$chkPatref[0]['patient_name']." You have received the opinion for your medical query. Check your registered email. Thx, Medisensehealth.com";
					
					send_msg($mobile,$responsemsg);
					
					}
					
					
					
	}	
	if($Status_id==6){
	$msg="Response-P failed -".$Cur_Date_Time;
	}
	if($Status_id==7){
	$msg="Patient Staged -".$Cur_Date_Time;
	}
	if($Status_id==8){
	$msg="OP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==9){
	$msg="IP Converted -".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==10){
	$msg="Not Converted -".$Cur_Date_Time;
	}
	if($Status_id==11){
	$msg="Invoice raised to".$getRef[0]['ref_name']."-".$Cur_Date_Time;
	}
	if($Status_id==12){
	$msg=$getRef[0]['ref_name']."- Payment received -".$Cur_Date_Time;
	}
	if($Status_id==13){
	$msg=$getRef[0]['ref_name']."- OP Visited -".$Cur_Date_Time;
	}
	if($Status_id==14){
	$msg=$getRef[0]['ref_name']."- Not Responded -".$Cur_Date_Time;
	}
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_POST['Pat_Id'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = "0";
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $msg;
	$arrFields2[] = 'user_id';
	$arrValues2[] = $admin_id;
	$arrFields2[] = 'status_id';
	$arrValues2[] = $Status_id;
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
	if(empty($_POST['txtpro3'])){				
	$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	}
	}
//TO CHECK IF REF1. DELETE OPTION.IF ITS TRUE THEN DELETE PERTICULAR REF. HISTORY FROM patient_referal & chat_notification Table 
	
	else if($_POST['Del_Ref_Id3']!==0){
	$objQuery->mysqlDelete('patient_referal',"ref_id='".$_POST['Del_Ref_Id3']."'and patient_id='".$_POST['Pat_Id']."'");
	$objQuery->mysqlDelete('chat_notification',"ref_id='".$_POST['Del_Ref_Id3']."'and patient_id='".$_POST['Pat_Id']."'");
	}
}
//NEED MORE INFO SECTION
if(isset($_POST['cmdEmailPatient'])){
	$Pat_id= $_POST['pat_id'];
	$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$Pat_id."'","","","","");
	
	if($chkPatInfo[0]['patient_gen']==1){
				
				$Pat_Gen="Male";
			} else {
				$Pat_Gen="Female";
			}

						if($chkPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="Normal";

						} else if($chkPatInfo[0]['hyper_cond']==1) {

							$Hyper_Cond="High";

						} else if($chkPatInfo[0]['hyper_cond']==3) {

							$Hyper_Cond="Low";

						}else {
							$Hyper_Cond="NA";
						}
						if($chkPatInfo[0]['diabetes_cond']==2){
							
							$Diabetic_Cond="No";
						} else if($chkPatInfo[0]['diabetes_cond']==1){
							$Diabetic_Cond="Yes";
						} else {
							$Diabetic_Cond="NA";
						}
			
	
		//To send Need More Info Email notification to Patient email id
			if($chkPatInfo[0]['patient_email']!=""){
		
					$url_page = 'needinfomail.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patid=".urlencode($chkPatInfo[0]['patient_id']);
					$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
					$url .= "&patage=" . urlencode($chkPatInfo[0]['patient_age']);
					$url .= "&patgend=" . urlencode($Pat_Gen);
					$url .= "&patweight=" . urlencode($chkPatInfo[0]['weight']);
					$url .= "&patmerital=" . urlencode($chkPatInfo[0]['merital_status']);
					$url .= "&pathyper=" . urlencode($Hyper_Cond);
					$url .= "&patdiabetes=" . urlencode($Diabetic_Cond);
					$url .= "&patloc=" . urlencode($chkPatInfo[0]['patient_loc']);
					$url .= "&pataddress=" . urlencode($chkPatInfo[0]['patient_addrs']);
					$url .= "&patcomp=" . urlencode($chkPatInfo[0]['patient_complaint']);
					$url .= "&patdesc=" . urlencode($chkPatInfo[0]['patient_desc']);
					$url .= "&patquery=" . urlencode($chkPatInfo[0]['pat_query']);
					$url .= "&patemail=" . urlencode($chkPatInfo[0]['patient_email']);
					$url .= "&patphone=" . urlencode($chkPatInfo[0]['patient_mob']);
					$url .= "&patblood=" . urlencode($chkPatInfo[0]['pat_blood']);
					$url .= "&patcontact=" . urlencode($chkPatInfo[0]['contact_person']);
					$url .= "&patprof=" . urlencode($chkPatInfo[0]['profession']);
					$url .= "&ccmail=" . urlencode($ccmail);		
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
					$Successmessage = "Email Sent to patient Successfully";
					
					//Assigned to Patient condition, when need more info mail sent
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'assigned_to';
					$arrValues[]= "10";
					$patientRef=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$Pat_id."'");
					$arrFields1 = array();
					$arrValues1 = array();
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $Pat_id;
					$arrFields1[]= 'flag_val';
					$arrValues1[]= "5";
					$arrFields1[]= 'respond_state';
					$arrValues1[]= "5";
					$arrFields1[]= 'status1';
					$arrValues1[]= "1";
					$arrFields1[]= 'status2';
					$arrValues1[]= "3";
					$arrFields1[]= 'bucket_status';
					$arrValues1[]= "3";
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$Pat_id."'");
					
					$msg="Need more info msg has sent to Patient on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();
					$arrFields2[] = 'patient_id';
					$arrValues2[] = $Pat_id;
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;
					$arrFields2[] = 'user_id';
					$arrValues2[] = $admin_id;
					$arrFields2[]= 'status_id';
					$arrValues2[]= "3";
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
			}
	
	
					//SMS notification to Patient
					if($chkPatInfo[0]['patient_mob']!=""){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Dear ". $chkPatInfo[0]['patient_name'] . ",this is from Medisensehealth.com, We requested you for more patient information, please check your mail inbox or spam box - Many Thanks";
					
					send_msg($mobile,$msg);
					
					}
		}

		//Patient Not Qualify Section
		if(isset($_POST['cmdqualifyPatient'])){
			$Pat_id= $_POST['pat_id'];
			$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$Pat_id."'","","","","");
	
			//Patient Status become Closed when Not Qualify Button clicked
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'assigned_to';
					$arrValues[]= "10";
					$patientRef=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$Pat_id."'");
					$arrFields1 = array();
					$arrValues1 = array();
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $Pat_id;
					$arrFields1[]= 'flag_val';
					$arrValues1[]= "4";
					$arrFields1[]= 'status1';
					$arrValues1[]= "2";
					$arrFields1[]= 'status2';
					$arrValues1[]= "4";
					$arrFields1[]= 'bucket_status';
					$arrValues1[]= "4";
					
					$chkPatRefInfo = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$Pat_id."'","","","","");
					$msg="Not Qualified msg has sent to Patient on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();
					$arrFields2[] = 'patient_id';
					$arrValues2[] = $Pat_id;
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;
					$arrFields2[] = 'user_id';
					$arrValues2[] = $admin_id;
					$arrFields2[]= 'status_id';
					$arrValues2[]= '4';
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					if($chkPatRefInfo==true){
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$Pat_id."'");
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
					}else {
					$insertpatientRef=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					}
			
			
			//To send Not Qualify Email notification to Patient email id
			if($chkPatInfo[0]['patient_email']!=""){
		
					$url_page = 'notqualifymail.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?patmail=".urlencode($chkPatInfo[0]['patient_email']);
					$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
					$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
					$url .= "&ccmail=" . urlencode($ccmail);
					
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					
					curl_close ( $ch );
					$Successmessage = "Email Sent to patient Successfully";
					
				
			}
				//Jio Patient Status Update
				if(!empty($chkPatInfo[0]['external_orderid']))
				{
				$chkMember = $objQuery->mysqlSelect("medAuthToken","login_user","login_id='".$chkPatInfo[0]['member_id']."'","","","","");
		
				$statuscode ="S101";
				$authToken =$chkMember[0]['medAuthToken'];
				$healthHubId = $chkPatInfo[0]['external_hubid'];
				$healthHubOrderId = $chkPatInfo[0]['external_orderid'];
				$mobileNum = $chkPatInfo[0]['patient_mob'];
				$description = "Not relevant to surgery case.";
				$status ="Not Qualified";
				$docName = "";
				$transactionID = $chkPatInfo[0]['transaction_id'];
				$paymentID = "";				
				send_jio_status($statuscode,$authToken,$healthHubId,$healthHubOrderId,$mobileNum,$description,$status,$docName,$transactionID,$paymentID);	
				}
				
				//SMS notification to Patient
				if($chkPatInfo[0]['patient_mob']!=""){
				$mobile = $chkPatInfo[0]['patient_mob'];
				$msg = "Hi,To resolve your medical query you will have to visit a doctor personally as the info provided is inadequate for online opinion. Medisensehealth.com";
				send_msg($mobile,$msg);
				}
		}

//TURN TO PAID
if(isset($_POST['turntopaid']) && !empty($_POST['txtref'])){
$txtRefId= $_POST['txtref'];

$trans_id=time(); //UPDATE TRANSACTION ID	
$arrFields = array();
$arrValues = array();
$arrFields[]= 'transaction_id';
$arrValues[]= $trans_id;
$arrFields[]= 'transaction_status';
$arrValues[]= "Pending";

$editPatient=$objQuery->mysqlUpdate('patient_tab',$arrFields,$arrValues,"patient_id='".$_POST['Pat_Id']."'");

$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "P-AWAITING"
$arrValues2[]= "3";
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['Pat_Id']."'");

$chkDocStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_POST['Pat_Id']."'and ref_id='".$txtRefId."'","","","","");
$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'","","","","");	
$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
$getConfigInfo = $objQuery->mysqlSelect("*","crm_configuration","" ,"","","","");

$chkMember = $objQuery->mysqlSelect("medAuthToken","login_user","login_id='".$chkPatInfo[0]['login_user_id']."'","","","","");
						
				
				if(!empty($get_pro[0]['on_op_cost'])){
						
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						
						if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']=="India")
						{ //DOMESTIC PATIENT MAIL
					
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getDepartment[0]['spec_name']));						
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));						
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($get_pro[0]['ref_id']);
						
						$service="Second Opinion";
						//$opcost=$get_pro[0]['on_op_cost'].".00";
						
						if($chkPatInfo[0]['opinion_for'] == 1){
							$opinion_cost_data = $getConfigInfo[0]['corona_test_cost'];
						}
						else {
							$opinion_cost_data = $getConfigInfo[0]['opinion_cost'];
						}
						
						//$opcost=$getConfigInfo[0]['opinion_cost'];
						$opcost= $opinion_cost_data;
						
						$paylink=HOST_HEALTH_URL."turn-to-pay.php?patid=".$_POST['Pat_Id']."&patname=".$chkPatInfo[0]['patient_name']."&mobile=".$chkPatInfo[0]['patient_mob']."&email=".$chkPatInfo[0]['patient_email']."&amount=".$opcost."&service=".$service."&docname=".$get_pro[0]['ref_name']."&docid=".$txtRefId."&authToken=".$chkMember[0]['medAuthToken']."&healthHubId=".$chkPatInfo[0]['external_hubid']."&healthHubOrderId=".$chkPatInfo[0]['external_orderid']."&transactionID=".$chkPatInfo[0]['transaction_id'];
							if($chkDocStatus[0]['status2']==5){ //IF DOCTOR ALREADY RESPONDED TO PATIENT QUERY THEN FOLLOWING PAYMENT MAIL WILL SEND TO PATIENT
								$url_page = 'Turn_to_Paylink_After_Respond.php';
							}else{
								$url_page = 'Turn_to_Paylink.php';
							}
								$url = "https://referralio.com/EMAIL/";
								$url .= rawurlencode($url_page);
								$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
								$url .= "&doccity=" . urlencode($get_pro[0]['ref_address']);
								$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
								$url .= "&docimg=".urlencode($docimg);
								$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
								$url .= "&doclink=".urlencode($Link);
								$url .= "&regdate=" . urlencode($reg_date);
								$url .= "&paylink=".urlencode($paylink);
								$url .= "&docamount=".urlencode($opcost);
								$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
								$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
								$url .= "&patmobile=" . urlencode($chkPatInfo[0]['patient_mob']);					
								$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
								$url .= "&ccmail=" . urlencode($ccmail);		
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
						}
						else if(!empty($chkPatInfo[0]['patient_email']) && $chkPatInfo[0]['pat_country']!=" " && $chkPatInfo[0]['pat_country']!="India")
						{ //INTERNATIONAL PATIENT MAIL (PAYPAL LINK NEED TO BE SEND)
						
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getDepartment[0]['spec_name']));						
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($get_pro[0]['ref_id']);
						
						$opcost=$getConfigInfo[0]['international_op_cost'];
						
						$url_page = 'Non_Indian_paylink.php';
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
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
						$url .= "&ccmail=" . urlencode($ccmail);	
						$url .= "&docamount=".urlencode($opcost);						
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
						}
								
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an opinion. Thanks, Medisensehealth.com";
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Payment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $_POST['Pat_Id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= '0';
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//SET PAYEMNT REMINDER TABLE
					$arrFields3 = array();
					$arrValues3 = array();
									
					$arrFields3[]= 'patient_id';
					$arrValues3[]= $_POST['Pat_Id'];
					$arrFields3[]= 'doc_id';
					$arrValues3[]= $get_pro[0]['ref_id'];
					$arrFields3[]= 'reminder_count';
					$arrValues3[]= '0';
					$arrFields3[]= 'payment_status';
					$arrValues3[]= '1';
					$arrFields3[]= 'TImestamp';
					$arrValues3[]= $Cur_Date;
					$inserReminder=$objQuery->mysqlInsert('payment_reminder',$arrFields3,$arrValues3);
					
					$Successmessage="Payment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
				}
				else{
					$errormessage="Error !!!! Please check this Expert Opinion Cost";
				}
}		

//TURN TO DIRECT APPOINTMENT
if(isset($_POST['fixappointment']) && !empty($_POST['txtref'])){
$txtRefId= $_POST['txtref'];
$_SESSION['Ref_Id']=$txtRefId;
$trans_id=time(); //GET TRANSACTION ID
	
$chkRefInfo = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$txtRefId."' and patient_id='".$_POST['Pat_Id']."'","","","","");
$arrFields2 = array();
$arrValues2 = array();
$arrFields2[]= 'patient_id'; 
$arrValues2[]= $_POST['Pat_Id'];
$arrFields2[]= 'ref_id'; 
$arrValues2[]= $txtRefId;
$arrFields2[]= 'status1';
$arrValues2[]= "1";
$arrFields2[]= 'status2';
$arrValues2[]= "7";

if($chkRefInfo==true){
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['Pat_Id']."' and ref_id='".$txtRefId."'");
$arrFields1 = array();
$arrValues1 = array();
$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "STAGED"
$arrValues1[]= "7";
$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");

}
else{
	$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
	$patientNote=$objQuery->mysqlInsert('patient_referal',$arrFields2,$arrValues2);	
	$arrFields1 = array();
	$arrValues1 = array();
	$arrFields1[]= 'bucket_status'; //UPDATE BUCKET STATUS TO "STAGED"
	$arrValues1[]= "7";
	$editPatientStatus=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");

}

$chkPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'","","","","");	
$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$get_pro[0]['doc_spec']."'" ,"","","","");
			
									
						if(!empty($chkPatInfo[0]['patient_email'])){
							
							
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getDepartment[0]['spec_name']));		
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$reg_date=date('d-m-Y h:i',strtotime($chkPatInfo[0]['TImestamp']));
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($get_pro[0]['ref_id']);
						
						$url_page = 'Turn_to_Appointment.php';
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&docspec=".urlencode($getDepartment[0]['spec_name']);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&regdate=" . urlencode($reg_date);
						$url .= "&patid=" . urlencode($chkPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($chkPatInfo[0]['patient_name']);
						$url .= "&patmail=" . urlencode($chkPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
						}	
						
					//SMS notification to Refering Doctors only when messge_status is active
					if(!empty($chkPatInfo[0]['patient_mob'])){
					$mobile = $chkPatInfo[0]['patient_mob'];
					$msg = "Action Required. We have sent you a mail. Please complete the action to get an appointment. Thanks, Medisensehealth.com";
					
					send_msg($mobile,$msg);
					
					}
					
					$txtProNote= "Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $_POST['Pat_Id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $txtRefId;
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= "7";
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
										
					$Successmessage="Appointment Link for ".$get_pro[0]['ref_name']." Sent to ".$chkPatInfo[0]['patient_name']." Successfully";
				
}	
//REFER TO PANELIST
if(isset($_POST['addRef']) && !isset($_POST['turntopaid']) && !empty($_POST['txtref'])){
$txtRefId= $_POST['txtref'];

if($txtRefId!=""){
	
	$arrFields1 = array();
	$arrValues1 = array();

	$arrFields1[]= 'patient_id';
	$arrValues1[]= $_POST['Pat_Id'];
	$arrFields1[]= 'ref_id';
	$arrValues1[]= $txtRefId;
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
	$arrFields1[]= 'share_parient_contact';
	$arrValues1[]= $_POST['contatInclude'];
	
	$chkreflist = $objQuery->mysqlSelect("*","referal as a left join patient_referal as b on a.ref_id=b.ref_id","b.patient_id='".$_POST['Pat_Id']."'and b.ref_id='".$txtRefId."'","","","","");
	if($chkreflist==true){
		$errorMessage="Sorry '".$chkreflist[0]['ref_name']."' referal is already existed";
	}else{
		
			$getPatInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$getPatAttach= $objQuery->mysqlSelect("*","patient_attachment","patient_id='".$_POST['Pat_Id']."'" ,"","","","");
			$get_pro = $objQuery->mysqlSelect('*','referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$txtRefId."'");
			$getDepartment = $objQuery->mysqlSelect("*","specialization","spec_id='".$getPatInfo[0]['medDept']."'" ,"","","","");
			$getDocDept = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$txtRefId."'","","","","");
			
			//if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']=="" || $getPatInfo[0]['pat_country']=="" || $getPatInfo[0]['patient_complaint']=="" || $getPatInfo[0]['patient_desc']=="" || $getPatInfo[0]['repnotattach']==0){
			if($getPatInfo[0]['patient_loc']=="" || $getPatInfo[0]['contact_person']=="" || $getPatInfo[0]['patient_mob']==""){
			
			echo '<script language="javascript">';
			echo 'alert("Please fill the required patient details properly")';
			echo '</script>'; 
			
			} else if($getPatAttach==true || $getPatInfo[0]['repnotattach']==1 ) {
		
						$objQuery->mysqlDelete('patient_referal',"ref_id=0 and patient_id='".$_POST['Pat_Id']."'");	
						$patientRef=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
						$ref_id=mysql_insert_id();
						$pat_id = $_POST['Pat_Id'];
						$_SESSION['Ref_Id']=$txtRefId;
	
						
						if($getPatInfo[0]['patient_gen']==1){
							
							$Pat_Gen="Male";
						} else {
							$Pat_Gen="Female";
						}

						if($getPatInfo[0]['hyper_cond']==2){
							
							$Hyper_Cond="Normal";

						} else if($getPatInfo[0]['hyper_cond']==1) {

							$Hyper_Cond="High";

						} else if($getPatInfo[0]['hyper_cond']==3) {

							$Hyper_Cond="Low";

						} else {
							$Hyper_Cond="NA";
						}
						if($getPatInfo[0]['diabetes_cond']==2){
							
							$Diabetic_Cond="No";
						} else if($getPatInfo[0]['diabetes_cond']==1){
							$Diabetic_Cond="Yes";
						} else {
							$Diabetic_Cond="NA";
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
					
					$url .= "&curtreatdoc=" . urlencode($getPatInfo[0]['currentTreatDoc']);
					$url .= "&curtreathosp=" . urlencode($getPatInfo[0]['currentTreatHosp']);
					
					$url .= "&patqualification=" . urlencode($pat_qualification);
					$url .= "&patblood=" . urlencode($getPatInfo[0]['pat_blood']);
					$url .= "&patContactDet=". urlencode($patContactDet);
					$url .= "&patnoreportmsg=" . urlencode($noreportmsg);
					if(!empty($getPatAttach[0]['external_attachments'])){						
						$url .= "&externalAttachLink=" . urlencode($getPatAttach[0]['external_attachments']);
					}
					else
					{
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
						if(!empty($getPatAttach[10]['attach_id'])){
						$url .= "&patattachid11=" . urlencode($getPatAttach[10]['attach_id']);
						$url .= "&patattachname11=" . urlencode($getPatAttach[10]['attachments']);
						}
						if(!empty($getPatAttach[11]['attach_id'])){
						$url .= "&patattachid12=" . urlencode($getPatAttach[11]['attach_id']);
						$url .= "&patattachname12=" . urlencode($getPatAttach[11]['attachments']);
						}
						if(!empty($getPatAttach[12]['attach_id'])){
						$url .= "&patattachid13=" . urlencode($getPatAttach[12]['attach_id']);
						$url .= "&patattachname13=" . urlencode($getPatAttach[12]['attachments']);
						}
						if(!empty($getPatAttach[13]['attach_id'])){
						$url .= "&patattachid14=" . urlencode($getPatAttach[13]['attach_id']);
						$url .= "&patattachname14=" . urlencode($getPatAttach[13]['attachments']);
						}
						if(!empty($getPatAttach[14]['attach_id'])){
						$url .= "&patattachid15=" . urlencode($getPatAttach[14]['attach_id']);
						$url .= "&patattachname15=" . urlencode($getPatAttach[14]['attachments']);
						}
						if(!empty($getPatAttach[15]['attach_id'])){
						$url .= "&patattachid16=" . urlencode($getPatAttach[15]['attach_id']);
						$url .= "&patattachname16=" . urlencode($getPatAttach[15]['attachments']);
						}
						if(!empty($getPatAttach[16]['attach_id'])){
						$url .= "&patattachid17=" . urlencode($getPatAttach[16]['attach_id']);
						$url .= "&patattachname17=" . urlencode($getPatAttach[16]['attachments']);
						}
						if(!empty($getPatAttach[17]['attach_id'])){
						$url .= "&patattachid18=" . urlencode($getPatAttach[17]['attach_id']);
						$url .= "&patattachname18=" . urlencode($getPatAttach[17]['attachments']);
						}
						if(!empty($getPatAttach[18]['attach_id'])){
						$url .= "&patattachid19=" . urlencode($getPatAttach[18]['attach_id']);
						$url .= "&patattachname19=" . urlencode($getPatAttach[18]['attachments']);
						}
						if(!empty($getPatAttach[19]['attach_id'])){
						$url .= "&patattachid20=" . urlencode($getPatAttach[19]['attach_id']);
						$url .= "&patattachname20=" . urlencode($getPatAttach[19]['attachments']);
						}
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
					
					
					if(!empty($getPatInfo[0]['patient_email'])){
					//Doc Info EMAIL notification Sent to Patient
			
						if(!empty($get_pro[0]['doc_photo'])){
							$docimg=HOST_MAIN_URL."Doc/".$get_pro[0]['ref_id']."/".$get_pro[0]['doc_photo'];
						}	
						else{
							$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
						}
						$find=array("/",",","&"," ");
						$getDocSpec=urlencode(str_replace($find, "-", $getDocDept[0]['spec_name']));
						$getDocName=urlencode(str_replace(' ','-',$get_pro[0]['ref_name']));
						$getDocCity=urlencode(str_replace(' ','-',$get_pro[0]['ref_address']));
						$getDocState=urlencode(str_replace(' ','-',$get_pro[0]['doc_state']));
						$getDocHosp=urlencode(str_replace(' ','-',$get_pro[0]['hosp_name']));
						//$getDocHospAdd=urlencode(str_replace(' ','-',$get_pro[0]['hosp_addrs']));
						
						$Getlink=$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocHospAdd.'-'.$getDocCity.'-'.$getDocState;
						$actualLink=hyphenize($Getlink);
						$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$actualLink.'/'.md5($get_pro[0]['ref_id']);
						
											
						$url_page = 'After_refer_pat_mail.php';
						
						$url = "https://referralio.com/EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?docname=".urlencode($get_pro[0]['ref_name']);
						$url .= "&doccity=" . urlencode($get_pro[0]['ref_address']);
						$url .= "&docid=" . urlencode($get_pro[0]['ref_id']);
						$url .= "&docimg=".urlencode($docimg);
						$url .= "&doclink=".urlencode($Link);
						$url .= "&docspec=".urlencode($getDocDept[0]['spec_name']);					
						$url .= "&patid=" . urlencode($getPatInfo[0]['patient_id']);
						$url .= "&patname=" . urlencode($getPatInfo[0]['patient_name']);					
						$url .= "&patmail=" . urlencode($getPatInfo[0]['patient_email']);
						$url .= "&ccmail=" . urlencode($ccmail);		
								
						$ch = curl_init (); // setup a curl						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to					
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers					
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails					
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );					
						$output = curl_exec ( $ch );				
						curl_close ( $ch );
					}
					
					$Successmessage = "Referred to ".$get_pro[0]['ref_name']." Successfully";
				
					$arrFields = array();
					$arrValues = array();
					$arrFields[]= 'email_status';
					$arrValues[]= "1";
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$pat_id."'and ref_id='".$refer_id."'");
					
					//NO. OF REFFERED COUNT INCREMENTED BY ONE
					$Tot_ref=$get_pro[0]['Total_Referred'];
					$Tot_ref=$Tot_ref+1;
					
					$arrFields3 = array();
					$arrValues3 = array();
					$arrFields3[]= 'Total_Referred';
					$arrValues3[]= $Tot_ref;
					$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$get_pro[0]['ref_id']."'");
					
					
					$txtProNote1= "Referred to ".$get_pro[0]['ref_name']." Successfully";
					$arrFields1 = array();
					$arrValues1 = array();
									
					$arrFields1[]= 'patient_id';
					$arrValues1[]= $getPatInfo[0]['patient_id'];
					$arrFields1[]= 'ref_id';
					$arrValues1[]= $get_pro[0]['ref_id'];
					$arrFields1[]= 'chat_note';
					$arrValues1[]= $txtProNote1;
					$arrFields1[]= 'user_id';
					$arrValues1[]= $admin_id;
					$arrFields1[]= 'status_id';
					$arrValues1[]= '2';
					$arrFields1[]= 'TImestamp';
					$arrValues1[]= $Cur_Date;
					
				
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields1,$arrValues1);
					
					//Medisense Note
					$msg="Refered to ".$get_pro[0]['ref_name']."  on ".$Cur_Date_Time;
					$arrFields2 = array();
					$arrValues2 = array();
					$arrFields2[] = 'patient_id';
					$arrValues2[] = $getPatInfo[0]['patient_id'];
					$arrFields2[] = 'ref_id';
					$arrValues2[] = "0";
					$arrFields2[] = 'chat_note';
					$arrValues2[] = $msg;
					$arrFields2[] = 'user_id';
					$arrValues2[] = $admin_id;
					$arrFields2[] = 'TImestamp';
					$arrValues2[] = $Cur_Date;
					
					$usercraete=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					
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
					
					//SMS notification to Patient
					$responsemsg = "Dear ".$getPatInfo[0]['patient_name']." Your medical query has been successfully referred to ".$get_pro[0]['ref_name']." Please check your mail for further detail. Medisensehealth.com";
					if($getPatInfo[0]['patient_mob']!="" && $get_pro[0]['communication_status']!=0){
					$mobile = $getPatInfo[0]['patient_mob'];
					
					send_msg($mobile,$responsemsg);
					
					}
					
					//Jio Patient Status Update
					if(!empty($getPatInfo[0]['external_orderid']))
					{
					$chkMember = $objQuery->mysqlSelect("medAuthToken","login_user","login_id='".$getPatInfo[0]['login_user_id']."'","","","","");
			
					$statuscode ="S102";
					$authToken =$chkMember[0]['medAuthToken'];
					$healthHubId = $getPatInfo[0]['external_hubid'];
					$healthHubOrderId = $getPatInfo[0]['external_orderid'];
					$mobileNum = $getPatInfo[0]['patient_mob'];
					$description = $responsemsg;
					$status ="Referred";
					$docName = $get_pro[0]['ref_name'];
					$transactionID = $getPatInfo[0]['transaction_id'];
					$paymentID = "";				
					send_jio_status($statuscode,$authToken,$healthHubId,$healthHubOrderId,$mobileNum,$description,$status,$docName,$transactionID,$paymentID);	
					}
					
					
				}
					
		}
	
}//End If loop

}//End If Loop

if((isset($_POST['turntopaid']) && empty($_POST['txtref'])) || (isset($_POST['addRef']) && empty($_POST['txtref'])) ){
	
	$errormessage="Error !!!! Please Select our panelist before proceeding further";
}

//MEDISENSE INTERACTION
if(isset($_POST['cmdAdd'])){
					$arrFields2 = array();
					$arrValues2 = array();
					$txtMedNote= addslashes($_POST['medInt']);

					$arrFields2[]= 'patient_id';
					$arrValues2[]= $_POST['Pat_Id'];
					$arrFields2[]= 'ref_id';
					$arrValues2[]= '0';
					$arrFields2[]= 'chat_note';
					$arrValues2[]= $txtMedNote;
					$arrFields2[]= 'user_id';
					$arrValues2[]= $admin_id;
					$arrFields2[]= 'TImestamp';
					$arrValues2[]= $Cur_Date;
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
					//print_r($patientNote);
}


if(isset($_POST['addAttach']) && basename($_FILES['txtphoto1']['name']!=="")){
	$arrValues1 = array();
	$arrFields1 = array();
	
	$arrFields1[]= 'repnotattach';
	$arrValues1[]= '2';
	
	$reportState=$objQuery->mysqlUpdate('patient_tab',$arrFields1,$arrValues1,"patient_id='".$_POST['Pat_Id']."'");

	
	
	$errors= array();
	foreach($_FILES['txtphoto1']['tmp_name'] as $key => $tmp_name){	
	
	//get a unique download key
	$strKey = createKey();
	$leadtime=(time()+(60*60*24*7));	
	
	$file_name = $_FILES['txtphoto1']['name'][$key];
	$file_size =$_FILES['txtphoto1']['size'][$key];
	$file_tmp =$_FILES['txtphoto1']['tmp_name'][$key];
	$file_type=$_FILES['txtphoto1']['type'][$key];
	
	
		$Photo1  = $file_name;
		$arrFields = array();
		$arrValues = array();

		$arrFields[] = 'patient_id';
		$arrValues[] = $_POST['Pat_Id'];

		$arrFields[] = 'attachments';
		$arrValues[] = $file_name;
		
		$arrFields[] = 'downloadkey';
		$arrValues[] = $strKey;
		
		$arrFields[] = 'expires';
		$arrValues[] = $leadtime;

			
			$bslist_pht=$objQuery->mysqlInsert('patient_attachment',$arrFields,$arrValues);
		$id= mysql_insert_id();


		/* Uploading image file */ 
			 $uploaddirectory = realpath("Attach");
			 $uploaddir = $uploaddirectory . "/" .$id;
			 $dotpos = strpos($fileName, '.');
			 $Photo1 = str_replace(substr($Photo1, 0, $dotpos), $id, $Photo1);
			 $uploadfile = $uploaddir . "/" . $Photo1;
			
		
			/*Checking whether folder with category id already exist or not. */
			if (file_exists($uploaddir)) {
				//echo "The file $uploaddir exists";
				} else {
				$newdir = mkdir($uploaddirectory . "/" . $id, 0777);
			}
			
			/* Moving uploaded file from temporary folder to desired folder. */
			if(move_uploaded_file ($file_tmp, $uploadfile)) {
				//echo "File uploaded.";
			} else {
				//echo "File cannot be uploaded";
			}
		
			
	}
//end if
}



$getInfo = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$_GET['pat_id']."'" ,"","","","");
$getSearchResult = $objQuery->mysqlSelect("*","search_history","patient_id='".$_GET['pat_id']."'" ,"search_id desc","","","");
$getSource = $objQuery->mysqlSelect("*","source_list","source_id='".$getInfo[0]['patient_src']."'" ,"","","","");
$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$getInfo[0]['medDept']."'" ,"","","","");
$getAttach= $objQuery->mysqlSelect("COUNT(patient_id) as Count_Attach","patient_attachment","patient_id='".$_GET['pat_id']."'" ,"","","","");	
$result_pro = $objQuery->mysqlSelect("*","patient_referal as a left join referal as b on a.ref_id=b.ref_id","a.patient_id='".$_GET['pat_id']."'");
$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","","Tot_responded desc","","","");


		//To check patient Status here

		$chkStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$_GET['pat_id']."'","","","","");
		foreach($chkStatus as $chkStatusList){
		date_default_timezone_set('Asia/Kolkata');
		$cur_Date=date('Y-m-d h:i:s');
		$subtract_days = 2;
		$Check_Date = date('Y-m-d h:i:s',strtotime($cur_Date) - (24*3600*$subtract_days));
		
		$Ref_Date = date('d-m-Y',strtotime($chkStatus[0]['timestamp']));
		
		//For reminder button, button will appear only when refered date exceeds 24hrs and provider status is not in RESPONDED
			
		$subtract_days1 = 1;
		$Reminder_Date = date('Y-m-d h:i:s',strtotime($cur_Date) - (24*3600*$subtract_days1));
		
		
		if($chkStatusList['bucket_status']==1)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NEW</span>"; //Capture Bucket
		}
		if($chkStatusList['bucket_status']==2)
		{
			if($chkStatusList['timestamp']<= $Check_Date){
				$status="<span style='font-size:12px; color:red;font-weight:bold;'>REFERRED</span>";
			}else{																			//Refer Bucket
			$status="<span style='font-size:12px; color:#f9e223;font-weight:bold;'>REFERRED</span>";
			}
		}
		if($chkStatusList['bucket_status']==3)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>P-AWAITING</span>"; //P-Awaiting Bucket
		}
		if($chkStatusList['bucket_status']==4)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT-QUALIFIED</span>"; //Close Bucket
		}
		
		if($chkStatusList['bucket_status']==10)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT CONVERTED</span>"; //Close Bucket
		}
		if($chkStatusList['bucket_status']==5)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONDED</span>"; //Respond Bucket
		}
		if($chkStatusList['bucket_status']==6)
		{
			$status="<span style='font-size:12px;  color:#01d337;font-weight:bold;'>RESPONSE-P FAILED</span>";//Respond Bucket
		}
		if($chkStatusList['bucket_status']==7)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>STAGED</span>"; //Conversion Bucket
		}
		if($chkStatusList['bucket_status']==8)
		{
			$status="<span style='font-size:12px; color:#f68c34;font-weight:bold;'>OP-DESIRED</span>"; //Conversion Bucket
		}
		if($chkStatusList['bucket_status']==9)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>IP-CONVERTED</span>";//Invoice Bucket
		}
		if($chkStatusList['bucket_status']==11)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>INVOICED</span>";//Invoice Bucket
		}
		if($chkStatusList['bucket_status']==12)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>PAYMENT RECEIVED</span>"; //Invoice Bucket
		}
		if($chkStatusList['status2']==13)
		{
			$status="<span style='font-size:12px;  color:#f68c34;font-weight:bold;'>OP-VISITED</span>"; //Conversion Bucket
		}
		
		if($chkStatusList['bucket_status']==2 && $chkStatusList['status2']==14)
		{
			$status="<span style='font-size:12px;  color:red;font-weight:bold;'>NOT RESPONDED</span>"; //Refer Bucket
		}
		
		
		
	}

?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>Medisense CRM</title>
<?php include_once('support_file.php'); ?>
<link type="text/css" rel="stylesheet" href="jsPopup/popModal.css">
<link type="text/css" rel="stylesheet" href="bootstrap.min.css">

<script src="search/jquery-1.11.1.min.js"></script>
	<script src="search/jquery-ui.min.js"></script>
	<script src="search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#txtref').selectToAutocomplete();
	      
	    });
	  })(jQuery);
	  function call_login_hospital(){
		   //alert("Logging in.........");
		   var user=document.getElementById('txtref').value;
		   		   
		   if(user==""){
		     alert("Please choose our panel doctor");
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
	

	
	
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
  


<?php include_once('header.php'); ?>

<div class="content">
<div class="clearall">
 <div class="wrapper">
 
<div class="Nav fl">
	<!--<div class="backPageNav">
	<?php echo $arrPage[0];?>
		<a href="#"><< BACK</a>&nbsp;&nbsp;
		<a href="Patient_History.php?pat_id=<?php echo "3393"; ?>">NEXT >> </a>&nbsp;&nbsp;
	</div>-->
	<div class="backNav">
		<a href="#">Respond</a>&nbsp;&nbsp;
		<a href="feedback_form.php?<?php if(!empty($_GET['pat_id'])) { ?>pat_id=<?php echo $_GET['pat_id']; } if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>">Feedback</a>&nbsp;&nbsp;
		<a href="Add-Patient.php?<?php if(!empty($_GET['disp'])) { ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])) { ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>">Add New</a>
		
	</div>
	<div class="message">
	<?php
					if(isset($Successmessage)){
						echo '<font color="green"><b>'.$Successmessage.'</b></font>';
					}
					
					if(isset($errormessage)){
						echo '<font color="red"><b>'.$errormessage.'</b></font>';
					}
				if($getInfo[0]['patient_gen']=="1"){
					$gender="Male";
				}
				else if($getInfo[0]['patient_gen']=="2"){
					$gender="Female";
				}
				
				/*if($getInfo[0]['hyper_cond']==2){
							
					$hyperStatus="No";

				} else if($getInfo[0]['hyper_cond']==1) {

					$hyperStatus="Yes";

				} else {
					$hyperStatus="NA";
				}*/
				if($getInfo[0]['hyper_cond']==2){
							
					$hyperStatus="Normal";

				} else if($getInfo[0]['hyper_cond']==1) {

					$hyperStatus="High";

				} else if($getInfo[0]['hyper_cond']==3) {

					$hyperStatus="Low";

				} else {
					$hyperStatus="NA";
				}
				if($getInfo[0]['diabetes_cond']==2){
					$diabetesStatus="No";
				} else if($getInfo[0]['diabetes_cond']==1){
					$diabetesStatus="Yes";
				} else {
					$diabetesStatus="NA";
				}

					?>
	</div>
</div>
 <script language="javaScript" src="js/validation.js"></script> 
    
	<div class="patCaseSheet fl">
	
	
		<div class="leftFirst fl">
		
			<div class="leftTop">
				<div class="clearfix">
					<h2>Patient Details</h2>
					<div class="addref">
					<a href="main.php?<?php if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } if(!empty($_GET['disp'])){ ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])){ ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>"><input type="button" name="addbtn" value="BACK" class="addBtn"  style="float:right; margin-right:10px;" /></a>
					<form name="mailfrmstatus" method="POST" action="">
					<input type="hidden" name="cmdEmailStatus" value="">
					<input type="hidden" name="mail_pat_id" value="">
					<input type="hidden" name="mail_ref_id" value="">
					
					<!--<?php if($result_pro[0]['flag_val']==3){ ?>
					<a href="#" onclick="return mailActive(<?php echo $_GET['pat_id']; ?>)"><input type="button" name="sendMail" value="EMAIL" class="addBtn" style="float:right; margin-right:10px;" disabled/></a>
					<?php } else { ?>
					<a href="#" onclick="return mailActive(<?php echo $_GET['pat_id']; ?>)"><input type="button" name="sendMail" value="EMAIL" class="addBtn" style="float:right; margin-right:10px;"/></a>
					<?php } ?>-->
					</form>
					
					<!-- Does not qualify section -->
					<?php if($user_flag==0 || $user_flag==1) { ?>
					<form name="patqualifyfrmstatus" method="POST" action="">
					<input type="hidden" name="cmdqualifyPatient" value="">
					<input type="hidden" name="pat_id" value="">
					
					<a href="#" onclick="return patQualify(<?php echo $_GET['pat_id']; ?>)"><input type="button" name="QulPat" value="NQ" class="addBtn" style="float:right; margin-right:10px;"/></a>
						
					</form>
					
					
					<!-- Require more informationsection -->
					<form name="patmailfrmstatus" method="POST" action="">
					<input type="hidden" name="cmdEmailPatient" value="">
					<input type="hidden" name="pat_id" value="">
					
					<a href="#" onclick="return patmailActive(<?php echo $_GET['pat_id']; ?>)"><input type="button" name="needInfo" value="NEED INFO" class="addBtn1" style="float:right; margin-right:10px;"/></a>
					
					</form>
					<?php } ?>
					<!--								
					<form method="POST" name="frmPatient" action="" >
					<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />-->
					<a href="Edit-Patient.php?pat_id=<?php echo $_GET['pat_id']; ?>&refid=<?php echo $_GET['refid']; ?>&disp=<?php echo $_GET['disp']; ?>&assignId=<?php echo $_GET['assignId']; ?>"><input type="button" name="addbtn" value="EDIT" class="addBtn"  style="float:right; margin-right:10px;" /></a>
					<!--<button type="button" name="addbtn" class="addBtn" id="addBtn" onclick="javascript:displaymainCat();" style="float:right; margin-right:10px;" >REFER</button>
					<div id="TableOut1" style="margin-bottom:0px;"></div>
					</form>-->
					
					<form method="post" name="reportFrm">
				<input type="hidden" name="cmdRef" value="" />
				<input type="hidden" name="refferId" value="" />
				<input type="hidden" name="Pat_Id" value="<?php echo $_GET['pat_id']; ?>" />
				<select name='txtref' id="txtref" placeholder="Select Panel Doctor" style="width:140px; padding:5px; margin-top:5px; margin-left:65px;" >
				<option value="">Select Panel Doctor</option>
	<?php foreach($get_pro as $listDoc) {
			$get_Ref = $objQuery->mysqlSelect('*','referal',"ref_id='".$listDoc['RefId']."'","","","","");
			$get_spec = $objQuery->mysqlSelect('*','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
			$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
			//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
			
			$ResponseRate=($get_Ref[0]['Tot_responded']/$get_Ref[0]['Total_Referred'])*100;
			
			if($get_Ref[0]['doc_type']=="volunteer"){
				$star="<span style='color:red;'>@@</font>";
			} else if($get_Ref[0]['doc_type']=="featured" || $get_Ref[0]['doc_type']=="star"){
				$star="<span style='color:red;'>*****</font>";
			} else {
				$star="<span style='color:red;'></font>";
				
			}
			?>	
			
			
      <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo $star."[".$get_Ref[0]['Tot_responded']."/".$get_Ref[0]['Total_Referred']."=".ceil($ResponseRate)."% ] ".addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['doc_state'])."&nbsp;".trim(preg_replace('/\s+/',' ', $get_Ref[0]['doc_keywords'])); ?></option>
	<?php } ?>
    </select>
    <a href="#" title="Include Patient Contact Details"><input type="checkbox" name="contatInclude" id="contatInclude" value="1"/>...</a>
    <input type='submit' name='addRef' value='REFER' class='addBtn' onClick="call_login_hospital()"/>
	
	<?php if($user_flag==0 || $user_flag==1) { ?><a href='Add-Doctors.php?pat_id=<?php echo $_GET['pat_id'];if(!empty($_GET['refid'])){ ?>refid=<?php echo $_GET['refid']; } if(!empty($_GET['disp'])){ ?>&disp=<?php echo $_GET['disp']; } if(!empty($_GET['assignId'])){ ?>&assignId=<?php echo $_GET['assignId']; } if(!empty($_GET['start'])) { ?>&start=<?php echo $_GET['start']; } ?>' ><img src="images/plus-button.png" /></a><?php } ?>
  <br><input type='submit' name='turntopaid' value='TURN TO PAID' class='turnpay' style="float:left;" onClick="call_login_hospital()"/>
  <?php if($getInfo[0]['pat_country']=="India"){ ?><input type='submit' name='fixappointment' value='FIX APPOINTMENT' class='fixApp' style="float:left;" onClick="call_login_hospital()"/><?php } ?>
  </form>
										
					</div>
					
				<div class="patDet">
				<h1><?php echo $_POST['refferId']; ?></h1>
					<div class="det"><b>Patient ID :</b> <?php echo $getInfo[0]['patient_id']; ?></div>
					<div class="det"><b>Date :</b> <?php echo $Date=date('d-m-Y h:i:s',strtotime($getInfo[0]['TImestamp']));?></div>
					<!--<div class="det"><b>Lead Type :</b> <?php if($getInfo[0]['lead_type']=="Hot") { ?><img src="images/hot_icon.png" /> 
				<?php } else if($getInfo[0]['lead_type']=="Warm") { ?><img src="images/warm_icon.png" /> 
				<?php } else { ?><img src="images/cold_icon.png" /> <?php } ?></div>-->
				<div class="det"><b>Status :</b> <?php echo $status; if($getInfo[0]['transaction_status']=="Pending"){ ?><img src="images/pending.png" /><?php } ?></div>
					<?php if(!empty($getInfo[0]['data_source'])){ ?><div class="det"><b>Data Source :</b> <span style='font-size:12px;  color:red;font-weight:bold;'><?php echo $getInfo[0]['data_source']; ?></span></div><?php } ?>
					<div class="det"><b>Name :</b> <?php echo $getInfo[0]['patient_name']; ?></div>
					<div class="det"><b>Age :</b> <?php echo $getInfo[0]['patient_age']; ?></div>
					<div class="det"><b>Gender :</b> <?php echo $gender; ?></div>
					<div class="det"><b>Marital Status :</b> <?php if(empty($getInfo[0]['merital_status'])){ echo "NS"; } else { echo $getInfo[0]['merital_status']; } ?></div>
					<div class="det"><b>Weight :</b> <?php echo $getInfo[0]['weight']; ?></div>
					<div class="det"><b>Blood Pressure ? :</b> <?php echo $hyperStatus; ?></div>
					<div class="det"><b>Diabetes ? :</b> <?php echo $diabetesStatus; ?></div>
					<div class="det"><b>Qualification :</b> <?php echo $getInfo[0]['qualification']; ?></div>
					<div class="det"><b>Location :</b> <?php echo $getInfo[0]['patient_loc']; ?></div>
					<div class="det"><b>Address :</b> <?php echo $getInfo[0]['patient_addrs']; ?></div><br>
					<div class="det"><b>State :</b> <?php if($getInfo[0]['pat_state']==""){ echo "NS"; } else { echo $getInfo[0]['pat_state']; } ?></div>
					<div class="det"><b>Country :</b> <?php if($getInfo[0]['pat_country']==""){ echo "NS"; } else { echo $getInfo[0]['pat_country']; } ?></div>
					<div class="det"><b>Decision Maker :</b> <?php echo $getInfo[0]['contact_person']; ?></div>
					<div class="det"><b>Contact No. :</b> <?php echo $getInfo[0]['patient_mob']; ?></div>
					<div class="det"><b>Email Id :</b> <?php echo $getInfo[0]['patient_email']; ?></div>
					<!--<div class="det"><b>Source :</b> <?php echo $getSource[0]['source_name']; ?></div>-->
					<div class="det"><b>Department :</b> <?php echo $getDept[0]['spec_name']; ?></div>
					<div class="det"><b>Profession :</b> <?php if(!empty($getInfo[0]['profession'])){ echo $getInfo[0]['profession'];} else { echo "NILL"; }  ?></div>
					<div class="det"><b>Insurance Status :</b> <?php if(!empty($getInfo[0]['insurance_state'])){ echo $getInfo[0]['insurance_state'];} else { echo "NILL"; } ?></div>
					<!--<div class="det"><b>Medical reports attached ? <?php if($getInfo[0]['attchState']==1) { ?><input type="checkbox" name="attchState" class="fr" style="margin-right:50px; margin-top:0px;" value="0" disabled checked/>
					<?php } else { ?><input type="checkbox" name="attchState" class="chkBox fr" style="margin-right:50px; margin-top:0px;" value="0" disabled />
					<?php } ?></div>-->
					<form method="post" name="reportFrmState">
						<input type="hidden" name="repoState" value="">
						<input type="hidden" name="cmdRepoStatus" value="">
						<input type="hidden" name="Pat_Id" value="<?php echo $_GET['pat_id']; ?>">
						<div class="det"><b>If medical report not required <?php if($getInfo[0]['repnotattach']==1) { ?><input type="checkbox" name="repNotAtachstate" value="0" onclick="return reportNotAttach(this.value)" class="chkBox fr" style="margin-right:40px; margin-top:0px;" checked /><?php } else { ?>
						<input type="checkbox" name="repNotAtachstate" value="1" onclick="return reportNotAttach(this.value)" class="chkBox fr" style="margin-right:40px; margin-top:0px;" /><?php } ?><label style="margin-right:10px; float:right;">Yes</label></div>
					</form>
					
					<div class="det"><b>Attachments :</b></div>
					
					<form enctype="multipart/form-data" method="post" name="frmPatient" action="" onsubmit="return createPatient()">
					<div class="det"><a href="Add_Patient_Attachments.php?pat_id=<?php echo $_GET['pat_id']; ?>&refid=<?php echo $_GET['refid']; ?>&disp=<?php echo $_GET['disp']; ?>&assignId=<?php echo $_GET['assignId']; ?>">No. of records : <?php echo $getAttach[0]['Count_Attach']; ?></a><br><br>
						
						<div id="fileAttach" ><form method='post' name='frmAttach'><input type='hidden' name='Pat_Id' value='<?php echo $_GET['pat_id']; ?>' /><input type='file' name='txtphoto1[]' id='txtphoto1[]' multiple style='margin-bottom:10px;'><input type='submit' name='addAttach'  value='ADD' class='addAttchFile' /></form></div>
						
					</div>
					</form><br>
					
			</div>			
				<div class="row marketing" style="display:none;">
					<div class="col-lg-12" id="result-list">
					<?php foreach($srchRef as $list){ ?>
					<p id="b"><?php echo $list['ref_name']."&nbsp;".$list['ref_name']; ?></p>
					<?php } ?>
					</div>
				</div>
			
				<div class="patNote">

					<div class="det" style="font-size:12px;color:red;"><b>Patient Looking for :</b> <?php if($getInfo[0]['looking_for']==1){ echo "Only Opinion"; } else if($getInfo[0]['looking_for']==2){ echo "Opinion & Treatment"; } else if($getInfo[0]['looking_for']==3){ echo "Video Call"; } ?></div>
					<div class="det" style="font-size:12px;color:red;"><b>Treating Doctor :</b> <?php if(empty($getInfo[0]['currentTreatDoc'])){ echo "NS"; } else { echo $getInfo[0]['currentTreatDoc']; } ?></div>
					<div class="det" style="font-size:12px;color:red;"><b>Treating Hospital :</b> <?php if(empty($getInfo[0]['currentTreatHosp'])){ echo "NS"; } else { echo $getInfo[0]['currentTreatHosp']; } ?></div>
					<div class="det" style="font-size:12px;color:red;"><b>Prefered City :</b> <?php if(empty($getInfo[0]['pref_city'])){ echo "NS"; } else { echo $getInfo[0]['pref_city']; } ?></div>
					<div class="det" style="font-size:12px;color:red;"><b>Prefered Hospital :</b> <?php if(empty($getInfo[0]['pref_hosp'])){ echo "NS"; } else { echo $getInfo[0]['pref_hosp']; } ?></div>
					<div class="det" style="font-size:12px;color:red;"><b>Prefered Doctor :</b> <?php if(empty($getInfo[0]['pref_doc'])){ echo "NS"; } else { echo $getInfo[0]['pref_doc']; } ?></div>
					

					<?php if(!empty($getSearchResult[0]['search_result'])){ ?>
					<div class="page active" data-page="popModal"><h5>Patient Search History
					<a href="#" id="popModal_ex11" >View more</a></h5>
					<textarea name="tsearch" class="txtArea" disabled><?php foreach($getSearchResult as $srchList){ echo $srchList['search_result'].", "; } ?></textarea>
					</div>
					
					
					<div style="display:none">
						<div id="content11" style="width:370px;">
							
							<div>
								<p><?php foreach($getSearchResult as $srchList){ echo $srchList['search_result'].", "; } ?></p>
							</div>
						</div>
					</div>
					<div class="devider"></div>
					<?php } ?>
					<?php if(!empty($getSearchResult[0]['chosen_doc'])){ ?>
					<h5 style="color:red;">CHOSEN DOCTOR IS: <br>
					<?php echo $getSearchResult[0]['chosen_doc']; ?></h5>
					<?php } ?>
					<h5>Chief Medical Complaints</h5>
					
					<textarea name="tdesc" class="txtArea" disabled><?php echo $getInfo[0]['patient_complaint']; ?></textarea>
					<div class="devider"></div>
					
					<?php if(!empty($getInfo[0]['pat_query'])){ ?>
					<div class="page active" data-page="popModal"><h5>Medical Query to the Doctor
					<a href="#" id="popModal_ex4">View more</a></h5>
					</div>
					<textarea name="tdesc" class="txtArea" disabled><?php echo $getInfo[0]['pat_query']; ?></textarea>
					<div style="display:none">
						<div id="content3" style="width:370px;">
							
							<div>
								<p><?php echo $getInfo[0]['pat_query']; ?></p>
							</div>
						</div>
					</div>
					<div class="devider"></div>
					<?php } ?>
					
					<div class="page active" data-page="popModal"><h5>Brief Descriptions
					<a href="#" id="popModal_ex3" >View more</a></h5>
				</div>
					<textarea name="tdesc" class="txtArea1" cols="15" disabled><?php echo $getInfo[0]['patient_desc']; ?></textarea>
				
					
				<div style="display:none">
					<div id="content2" style="width:370px;">
						
						<div>
							<p><?php echo $getInfo[0]['patient_desc']; ?></p>
						</div>
					</div>
				</div>
				
				</div>
				
			</div>
			</div>
			
			<?php
			
			?>
			
			<div class="leftBtm">
			<?php $chkPatref1 = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$getInfo[0]['patient_id']."'and ref_id<>0","","","",""); ?>
				<div class="clearfix">
					<h2>Medisense Interaction</h2>
					<form method="post" name="frmPatStat">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type='hidden' name='cmdPatStatus' />
				<input type='hidden' name='pat_status_id' />
				<select name="slctPatState" id="slctPatState" class="slctField fr" onchange='return chgPatStatus(this.value)'>
					<?php if($result_pro[0]['status1']==1) { ?>
					<option value="1" selected>Open</option>
					<option value="2">Closed</option>
				<?php } else if($result_pro[0]['status1']==2) { ?>
					<option value="2" selected>Closed</option>
					<option value="1">Open</option>
					
				<?php } ?>
						
					</select><span class="fr">&nbsp;&nbsp;Patient Status: &nbsp;</span>	
				</form>
				<form method="post" name="frmMedInt" onsubmit="return createMedInt()">
			<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
			<input type="hidden" name="assignStatus" value="">
			<input type="hidden" name="pat_id1" value="">
			<input type="hidden" name="assign_id" value="">
					 <select name="slctAssign" onchange="return Assignsubmit1(this.value,<?php echo $getInfo[0]['patient_id']; ?>);" class="fr">
								<option value="0">Select</option>
				<?php 
								$UsrName= $objQuery->mysqlSelect("*","chckin_user","cmpny_id='".$Company_id."' and user_status='ACTIVE'","rand()","","","");
									$i=30;
									
									foreach($UsrName as $UsrNameList){
										if($UsrNameList['chk_userid']==$getInfo[0]['assigned_to']){
								?> 
									<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" selected>
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									<?php }?>
										<option value="<?php echo stripslashes($UsrNameList['chk_userid']);?>" >
										<?php echo stripslashes($UsrNameList['chk_username']);?></option>
									
									
									<?php 
										$i++;
										
									}?> 
				
				</select><span>Assigned To: </span>
					<iframe src="interaction_page.php" width="560" height="140">
					</iframe>
					<textarea class="txtArea" name="medInt" ></textarea>
					<input type="submit" value="ADD" name="cmdAdd" class="addRefBtn" />
				</div>
			</div>
			</form>
			
		</div>
		
		<?php if($result_pro[0]['ref_name']!=""){ ?>
		<div class="leftsecSec fl">
				
			<div class="provider">
				<div class="clearfix">
				<form method="post" name="frmReminder" onsubmit="return createPro1Int()">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type="hidden" name="cmdSendReminder" value="">
				<input type="hidden" name="reminder_id" value="">
				<input type="hidden" name="status_id" value="">
				<?php
				$Get_Vani_conv = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and user_id=13 and ref_id!=0","","","","");
				$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$result_pro[0]['ref_id']."'" ,"","","","");	
				$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$result_pro[0]['doc_spec']."'" ,"","","","");	
				$getSuperSpec = $objQuery->mysqlSelect("*","super_specialization","super_spec_id='".$result_pro[0]['doc_super_spec']."'" ,"","","","");	
								
				?>
				<select name="slctMail" class="slctField fl" onchange="return mailStatus(this.value,<?php echo $result_pro[0]['ref_id']; ?>)">
					<option value="0">Select</option>
					<option value="1">Reminder to Doc</option>
					<?php if($Get_Vani_conv==true) { ?><option value="2">Respond to Doc</option><?php } ?>
				</select>
				
				</form>
				
				<form method="post" name="frmProvider1" onsubmit="return createPro1Int()">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type="hidden" name="Ref_Id" value="<?php echo stripslashes($result_pro[0]['ref_id']);?>" />
				<input type="hidden" name="Pro_Status_Id" value="<?php echo stripslashes($result_pro[0]['provider_status']);?>" />
				<input type="hidden" name="Pro1_status2" value="<?php echo stripslashes($result_pro[0]['status2']);?>" />
				<input type='hidden' name='cmdStatus' />
				<input type='hidden' name='status_id' />
				<input type='hidden' name='Del_Ref_Id' />
				
				
				<!--<a href="#" onclick="return delRef(<?php echo stripslashes($result_pro[0]['ref_id']);?>)" style="color:red; float:right;"><img src="images/i-11.png" />--></a><br><br>
				
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex1" ><h2><?php echo $result_pro[0]['ref_name']."(".$result_pro[0]['ref_id'].")";?> Interaction</h2></a>
				</div>	
				<div style="display:none">
					<div id="content">
					<?php if(empty($result_pro[0]['doc_photo'])){ ?>
						<img src="images/noImage-p.jpg" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php } else {  ?>
						<img src="Doc/<?php echo $result_pro[0]['ref_id']; ?>/<?php echo $result_pro[0]['doc_photo']; ?>" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php }	?>
						<div>
						<?php $getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$result_pro[0]['ref_id']."'" ,"","","",""); ?>
							<p><b>Ref.Name :</b> <?php echo $result_pro[0]['ref_name'];?><br>
							<?php if(!empty($getHosp[0]['hosp_name'])){ ?><b>Hospital :</b> <?php echo $getHosp[0]['hosp_name'];?><br><?php } ?>
							<?php if(!empty($getSpec[0]['spec_name'])){ ?><b>Specialization :</b> <?php echo $getSpec[0]['spec_name'];?><br><?php } ?>
							<?php if(!empty($result_pro[0]['ref_exp'])){ ?><b>Year of Exp. :</b> <?php echo $result_pro[0]['ref_exp'];?><br><?php } ?>
							<?php if(!empty($result_pro[0]['ref_address'])){ ?><b>Location :</b> <?php echo $result_pro[0]['ref_address'];?><br><?php } ?>
							<?php if(!empty($getHosp[0]['hosp_addrs'])){ ?><b>Hospital Address :</b> <?php echo $getHosp[0]['hosp_name']."- &nbsp;".$getHosp[0]['hosp_addrs']."<br>"; if(!empty($getHosp[0]['hosp_contact'])){ ?>Hosp.Contact:<?php echo $getHosp[0]['hosp_contact']; }?><br><?php if(!empty($getHosp[0]['hosp_email']) || !empty($getHosp[0]['hosp_email1']) || !empty($getHosp[0]['hosp_email2']) || !empty($getHosp[0]['hosp_email3'])){ ?>Hosp.Email:<?php echo $getHosp[0]['hosp_email'].", ".$getHosp[0]['hosp_email1'].", ".$getHosp[0]['hosp_email2'].", ".$getHosp[0]['hosp_email3'].", ".$getHosp[0]['hosp_email4']; }?><?php } ?>
							<?php if(!empty($result_pro[0]['doc_state'])){ ?><b>State :</b> <?php echo $result_pro[0]['doc_state'];?><br><?php } ?>
							<?php if(!empty($result_pro[0]['contact_num'])){ ?><b>Doc Phone :</b> <?php echo $result_pro[0]['contact_num'];?><br><?php } ?>
							<?php if(!empty($result_pro[0]['ref_mail'])){ ?><b>Doc Email :</b> <?php echo $result_pro[0]['ref_mail'];?><br><?php } ?>
							<?php if(!empty($result_pro[0]['doc_contribute'])){ ?><b>Contributions :</b> <?php echo $result_pro[0]['doc_contribute'];?><br><?php } ?>
							</p>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				<select name="slctState" id="slctState" class="slctField fr" onchange='return chgStatus(this.value)'>
				<option value="0">Select</option>
				<?php 
				$Pro_Name= $objQuery->mysqlSelect("*","provider_status","","value asc","","","");
									$i=30;
									foreach($Pro_Name as $Pro_Name_list){
										if($Pro_Name_list['value']==$result_pro[0]['status2']){
											?>
								       <option value="<?php echo stripslashes($Pro_Name_list['value']);?>" selected>
									   <?php echo stripslashes($Pro_Name_list['status']);?></option>
									   <?php } ?>
										<option value=<?php echo stripslashes($Pro_Name_list['value']);?>><?php echo stripslashes($Pro_Name_list['status']);?></option> 
				<?php $i++; }?></select><br><br>
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex5" style="font-size:12px; color:red;">View Doc Response</a>
				</div>
				<div style="display:none;">
					<div id="content4" style="width:330px;">
						
						<div>
						<?php $Pro1_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and ref_id='".$result_pro[0]['ref_id']."'","chat_id desc","","","");
							foreach($Pro1_Interact as $chalist){
							?>
							<p><span><b><?php echo $result_pro[0]['ref_name']."( ";?><?php echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )";?> </b></span><?php echo $chalist['chat_note']; ?></p><br>
							<?php } ?>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				<iframe src="interaction_page1.php" width="290" height="380">
				</iframe>
				<!--<select name="slctUSer" class="slctField1 fl">
					<option>Sachin</option>
					<option>Dr.Ranjan Shetty</option>
				</select>-->
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Add Response</button>
				<div class="modal fade bs-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Enter <?php echo $result_pro[0]['ref_name']; ?> Response</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" >
        <form>
         
          
            <!--<label for="message-text" class="form-control-label">Response:</label>
            <textarea class="form-control" id="message-text"></textarea>-->
			<iframe src="editor.php?docid=<?php echo $result_pro[0]['ref_id']; ?>&patid=<?php echo $getInfo[0]['patient_id']; ?>&userid=<?php echo $admin_id; ?>" width="100%" height="470" style="border:none;"></iframe>
         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
				<!--<textarea class="txtArea" name="txtpro1" ></textarea>
				<input type="submit" value="ADD" name="cmdAdd1" class="addRefBtn" />-->
							
				</div>
			</div>
			</form>
			
				
				
		</div>
		<?php } ?>
		<?php if($result_pro[1]['ref_name']!=""){ ?>
		<div class="leftsecSec fl">
		<div class="provider">
		<div class="clearfix">
		
		<form method="post" name="frmReminder2" onsubmit="return createPro2Int()">
				<input type="hidden" name="cmdSendReminder" value="">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type="hidden" name="reminder_id" value="">
				<?php
				$Get_Vani_conv = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and user_id=13 and ref_id!=0","","","","");
				$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$result_pro[1]['ref_id']."'" ,"","","","");	
				$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$result_pro[1]['doc_spec']."'" ,"","","","");	
				$getSuperSpec = $objQuery->mysqlSelect("*","super_specialization","super_spec_id='".$result_pro[1]['doc_super_spec']."'" ,"","","","");	
				
				?>
				
				<select name="slctMail" class="slctField fl" onchange="return mailStatus(this.value,<?php echo $result_pro[1]['ref_id']; ?>)">
					<option value="0">Select</option>
					<option value="1">Reminder to Doc</option>
					<?php if($Get_Vani_conv==true) { ?><option value="2">Respond to Doc</option><?php } ?>
				</select>
				
		</form>
		
		<form method="post" name="frmProvider2" onsubmit="return createPro2Int()">
		<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
		<input type="hidden" name="Ref_Id1" value="<?php echo stripslashes($result_pro[1]['ref_id']);?>" />	
		<input type="hidden" name="Pro_Status_Id1" value="<?php echo stripslashes($result_pro[1]['provider_status']);?>" />
		<input type="hidden" name="Pro2_status2" value="<?php echo stripslashes($result_pro[1]['status2']);?>" />
		<input type='hidden' name='cmdStatus1' />
		<input type='hidden' name='status_id1' />
		<input type='hidden' name='Del_Ref_Id1' />
	
			
				<!--<a href="#" onclick="return delRef(<?php echo stripslashes($result_pro[1]['ref_id']);?>)" style="color:red; float:right;"><img src="images/i-11.png" /></a>--><br><br>
				
				
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex2"><h2><?php echo $result_pro[1]['ref_name']."(".$result_pro[1]['ref_id'].")";?> Interaction</h2></a>
				</div>	
				<div style="display:none">
					<div id="content1">
						<?php if(empty($result_pro[1]['doc_photo'])){ ?>
						<img src="images/noImage-p.jpg" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php } else {  ?>
						<img src="Doc/<?php echo $result_pro[1]['ref_id']; ?>/<?php echo $result_pro[1]['doc_photo']; ?>" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php }	?><div>
						<?php $getHosp1 = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$result_pro[1]['ref_id']."'" ,"","","",""); ?>
							<p><b>Ref.Name :</b> <?php echo $result_pro[1]['ref_name'];?><br>
							<?php if(!empty($getHosp[0]['hosp_name'])){ ?><b>Hospital :</b> <?php echo $getHosp[0]['hosp_name'];?><br><?php } ?>
							<?php if(!empty($getSpec[0]['spec_name'])){ ?><b>Specialization :</b> <?php echo $getSpec[0]['spec_name'];?><br><?php } ?>
							<?php if(!empty($result_pro[1]['ref_exp'])){ ?><b>Year of Exp. :</b> <?php echo $result_pro[1]['ref_exp'];?><br><?php } ?>
							<?php if(!empty($result_pro[1]['ref_address'])){ ?><b>Location :</b> <?php echo $result_pro[1]['ref_address'];?><br><?php } ?>
							<?php if(!empty($getHosp1[0]['hosp_addrs'])){ ?><b>Hospital Address :</b> <?php echo $getHosp1[0]['hosp_name']."- &nbsp;".$getHosp1[0]['hosp_addrs'];  if(!empty($getHosp1[0]['hosp_contact'])){ ?>  Hosp.Contact:<?php echo $getHosp1[0]['hosp_contact']; }?><br><?php if(!empty($getHosp1[0]['hosp_email']) || !empty($getHosp1[0]['hosp_email1']) || !empty($getHosp1[0]['hosp_email2']) || !empty($getHosp1[0]['hosp_email3'])){ ?>Hosp.Email:<?php echo $getHosp1[0]['hosp_email'].", ".$getHosp1[0]['hosp_email1'].", ".$getHosp1[0]['hosp_email2'].", ".$getHosp[0]['hosp_email3'].", ".$getHosp[0]['hosp_email4']; }?><?php } ?>
							<?php if(!empty($result_pro[1]['doc_state'])){ ?><b>State :</b> <?php echo $result_pro[1]['doc_state'];?><br><?php } ?>
							<?php if(!empty($result_pro[1]['contact_num'])){ ?><b>Doc Phone :</b> <?php echo $result_pro[1]['contact_num'];?><br><?php } ?>
							<?php if(!empty($result_pro[1]['ref_mail'])){ ?><b>Doc Email :</b> <?php echo $result_pro[1]['ref_mail'];?><br><?php } ?>
							<?php if(!empty($result_pro[1]['doc_contribute'])){ ?><b>Contributions :</b> <?php echo $result_pro[1]['doc_contribute'];?><br><?php } ?>
							</p>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				
				
				<select name="slctState" id="slctState" class="slctField fr" onchange='return chgStatus1(this.value)'>
				<option value="0">Select</option>
				<?php $Pro_Name= $objQuery->mysqlSelect("*","provider_status","","value asc","","","");
									$i=30;
									foreach($Pro_Name as $Pro_Name_list){
										if($Pro_Name_list['value']==$result_pro[1]['status2']){
											?>
								       <option value="<?php echo stripslashes($Pro_Name_list['value']);?>" selected>
									   <?php echo stripslashes($Pro_Name_list['status']);?></option>
									   <?php } ?>
										<option value=<?php echo stripslashes($Pro_Name_list['value']);?>><?php echo stripslashes($Pro_Name_list['status']);?></option> 
				<?php $i++; }?></select><br><br>
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex6" style="font-size:12px; color:red;">View Doc Response</a>
				</div>
				<div style="display:none;">
					<div id="content5" style="width:330px;">
						
						<div>
						<?php $Pro1_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and ref_id='".$result_pro[1]['ref_id']."'","chat_id desc","","","");
							foreach($Pro1_Interact as $chalist){
							?>
							<p><span><b><?php echo $result_pro[1]['ref_name']."( ";?><?php echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )";?> </b></span><?php echo $chalist['chat_note']; ?></p><br>
							<?php } ?>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				<iframe src="interaction_page2.php" width="290" height="380">
				</iframe>
				
				
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal2" data-whatever="@mdo">Add Response</button>
				<div class="modal fade bs-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Enter <?php echo $result_pro[1]['ref_name']; ?> Response</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body" >
							<form>
							 
							  
								<!--<label for="message-text" class="form-control-label">Response:</label>
								<textarea class="form-control" id="message-text"></textarea>-->
								<iframe src="editor.php?docid=<?php echo $result_pro[1]['ref_id']; ?>&patid=<?php echo $getInfo[0]['patient_id']; ?>&userid=<?php echo $admin_id; ?>" width="100%" height="470" style="border:none;"></iframe>
							 
							</form>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							
						  </div>
						</div>
					  </div>
					</div>
				
				<!--<textarea class="txtArea" name="txtpro2" ></textarea>
				<input type="submit" value="ADD" name="cmdAdd2" class="addRefBtn" />-->
				</div>
			</div>
		</form>	
		</div>
	<?php } ?>
		<?php if($result_pro[2]['ref_name']!=""){ ?>
		<div class="leftsecSec fl" >
		<div class="provider" style="border-top:4px solid #da0c0c;">
		<div class="clearfix">
		
		<form method="post" name="frmReminder2" onsubmit="return createPro3Int()">
				<input type="hidden" name="cmdSendReminder" value="">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type="hidden" name="reminder_id" value="">
				<?php
				$Get_Vani_conv = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and user_id=13 and ref_id!=0","","","","");
				$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$result_pro[2]['ref_id']."'" ,"","","","");	
				$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$result_pro[2]['doc_spec']."'" ,"","","","");	
				$getSuperSpec = $objQuery->mysqlSelect("*","super_specialization","super_spec_id='".$result_pro[2]['doc_super_spec']."'" ,"","","","");	
				
				?>
				
				<select name="slctMail" class="slctField fl" onchange="return mailStatus(this.value,<?php echo $result_pro[2]['ref_id']; ?>)">
					<option value="0">Select</option>
					<option value="1">Reminder to Doc</option>
					
				</select>
				
		</form>
		
		<form method="post" name="frmProvider3" onsubmit="return createPro3Int()">
		<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
		<input type="hidden" name="Ref_Id2" value="<?php echo stripslashes($result_pro[2]['ref_id']);?>" />	
		<input type="hidden" name="Pro_Status_Id2" value="<?php echo stripslashes($result_pro[2]['provider_status']);?>" />
		<input type="hidden" name="Pro3_status2" value="<?php echo stripslashes($result_pro[2]['status2']);?>" />
		<input type='hidden' name='cmdStatus2' />
		<input type='hidden' name='status_id2' />
		<input type='hidden' name='Del_Ref_Id2' />
	
			
				<!--<a href="#" onclick="return delRef(<?php echo stripslashes($result_pro[2]['ref_id']);?>)" style="color:red; float:right;"><img src="images/i-11.png" /></a>--><br><br>
				
				
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex7" ><h2><?php echo $result_pro[2]['ref_name']."(".$result_pro[2]['ref_id'].")";?> Interaction</h2></a>
				</div>	
				<div style="display:none;">
					<div id="content7" >
						<?php if(empty($result_pro[2]['doc_photo'])){ ?>
						<img src="images/noImage-p.jpg" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php } else {  ?>
						<img src="Doc/<?php echo $result_pro[2]['ref_id']; ?>/<?php echo $result_pro[2]['doc_photo']; ?>" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php }	?><div>
						<?php $getHosp1 = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$result_pro[2]['ref_id']."'" ,"","","",""); ?>
							<p><b>Ref.Name :</b> <?php echo $result_pro[2]['ref_name'];?><br>
							<?php if(!empty($getHosp[0]['hosp_name'])){ ?><b>Hospital :</b> <?php echo $getHosp[0]['hosp_name'];?><br><?php } ?>
							<?php if(!empty($getSpec[0]['spec_name'])){ ?><b>Specialization :</b> <?php echo $getSpec[0]['spec_name'];?><br><?php } ?>
							<?php if(!empty($result_pro[2]['ref_exp'])){ ?><b>Year of Exp. :</b> <?php echo $result_pro[2]['ref_exp'];?><br><?php } ?>
							<?php if(!empty($result_pro[2]['ref_address'])){ ?><b>Location :</b> <?php echo $result_pro[2]['ref_address'];?><br><?php } ?>
							<?php if(!empty($getHosp1[0]['hosp_addrs'])){ ?><b>Hospital Address :</b> <?php echo $getHosp1[0]['hosp_name']."- &nbsp;".$getHosp1[0]['hosp_addrs'];  if(!empty($getHosp1[0]['hosp_contact'])){ ?>  Hosp.Contact:<?php echo $getHosp1[0]['hosp_contact']; }?><br><?php if(!empty($getHosp1[0]['hosp_email']) || !empty($getHosp1[0]['hosp_email1']) || !empty($getHosp1[0]['hosp_email2']) || !empty($getHosp1[0]['hosp_email3'])){ ?>Hosp.Email:<?php echo $getHosp1[0]['hosp_email'].", ".$getHosp1[0]['hosp_email1'].", ".$getHosp1[0]['hosp_email2'].", ".$getHosp[0]['hosp_email3'].", ".$getHosp[0]['hosp_email4']; }?><?php } ?>
							<?php if(!empty($result_pro[2]['doc_state'])){ ?><b>State :</b> <?php echo $result_pro[2]['doc_state'];?><br><?php } ?>
							<?php if(!empty($result_pro[2]['contact_num'])){ ?><b>Doc Phone :</b> <?php echo $result_pro[2]['contact_num'];?><br><?php } ?>
							<?php if(!empty($result_pro[2]['ref_mail'])){ ?><b>Doc Email :</b> <?php echo $result_pro[2]['ref_mail'];?><br><?php } ?>
							<?php if(!empty($result_pro[2]['doc_contribute'])){ ?><b>Contributions :</b> <?php echo $result_pro[2]['doc_contribute'];?><br><?php } ?>
							</p>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				
				
				<select name="slctState" id="slctState" class="slctField fr" onchange='return chgStatus2(this.value)'>
				<option value="0">Select</option>
				<?php $Pro_Name= $objQuery->mysqlSelect("*","provider_status","","value asc","","","");
									$i=30;
									foreach($Pro_Name as $Pro_Name_list){
										if($Pro_Name_list['value']==$result_pro[2]['status2']){
											?>
								       <option value="<?php echo stripslashes($Pro_Name_list['value']);?>" selected>
									   <?php echo stripslashes($Pro_Name_list['status']);?></option>
									   <?php } ?>
										<option value=<?php echo stripslashes($Pro_Name_list['value']);?>><?php echo stripslashes($Pro_Name_list['status']);?></option> 
				<?php $i++; }?></select><br><br>
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex8" style="font-size:12px; color:red;">View Doc Response</a>
				</div>
				
				<div style="display:none;">
					<div id="content8" style="width:330px;">
						
						<div>
						<?php $Pro1_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and ref_id='".$result_pro[2]['ref_id']."'","chat_id desc","","","");
							foreach($Pro1_Interact as $chalist){
							?>
							<p><span><b><?php echo $result_pro[2]['ref_name']."( ";?><?php echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )";?> </b></span><?php echo $chalist['chat_note']; ?></p><br>
							<?php } ?>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				<iframe src="interaction_page3.php" width="290" height="380">
				</iframe>
				
				<!--<textarea class="txtArea" name="txtpro3" ></textarea>
				<input type="submit" value="ADD" name="cmdAdd3" class="addRefBtn" />-->
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal3" data-whatever="@mdo">Add Response</button>
				<div class="modal fade bs-example-modal-lg" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Enter <?php echo $result_pro[2]['ref_name']; ?> Response</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body" >
							<form>
							 
							  
								<!--<label for="message-text" class="form-control-label">Response:</label>
								<textarea class="form-control" id="message-text"></textarea>-->
								<iframe src="editor.php?docid=<?php echo $result_pro[2]['ref_id']; ?>&patid=<?php echo $getInfo[0]['patient_id']; ?>&userid=<?php echo $admin_id; ?>" width="100%" height="470" style="border:none;"></iframe>
							 
							</form>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							
						  </div>
						</div>
					  </div>
					</div>
					
				</div>
			</div>
		</form>	
		</div>
	<?php } ?>
	<?php if($result_pro[3]['ref_name']!=""){ ?>
		<div class="leftsecSec fl">
		<div class="provider" style="border-top:4px solid #da0c0c;">
		<div class="clearfix">
		
		<form method="post" name="frmProvider4" onsubmit="return createPro4Int()">
				<input type="hidden" name="cmdSendReminder" value="">
				<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
				<input type="hidden" name="reminder_id" value="">
				<?php
				$Get_Vani_conv = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and user_id=13 and ref_id!=0","","","","");
				$getHosp = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$result_pro[3]['ref_id']."'" ,"","","","");	
				$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$result_pro[3]['doc_spec']."'" ,"","","","");	
				$getSuperSpec = $objQuery->mysqlSelect("*","super_specialization","super_spec_id='".$result_pro[3]['doc_super_spec']."'" ,"","","","");	
				
				?>
				
				<select name="slctMail" class="slctField fl" onchange="return mailStatus(this.value,<?php echo $result_pro[3]['ref_id']; ?>)">
					<option value="0">Select</option>
					<option value="1">Reminder to Doc</option>
					
				</select>
				
		</form>
		
		<form method="post" name="frmProvider5" onsubmit="return createPro5Int()">
		<input type="hidden" name="Pat_Id" value="<?php echo stripslashes($getInfo[0]['patient_id']);?>" />
		<input type="hidden" name="Ref_Id3" value="<?php echo stripslashes($result_pro[3]['ref_id']);?>" />	
		
		<input type="hidden" name="Pro4_status2" value="<?php echo stripslashes($result_pro[3]['status2']);?>" />
		<input type='hidden' name='cmdStatus3' />
		<input type='hidden' name='status_id3' />
		<input type='hidden' name='Del_Ref_Id3' />
	
			
				<!--<a href="#" onclick="return delRef(<?php echo stripslashes($result_pro[3]['ref_id']);?>)" style="color:red; float:right;"><img src="images/i-11.png" /></a>--><br><br>
				
				
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex10" ><h2><?php echo $result_pro[3]['ref_name']."(".$result_pro[3]['ref_id'].")";?> Interaction</h2></a>
				</div>	
				<div style="display:none;">
					<div id="content10" >
						<?php if(empty($result_pro[3]['doc_photo'])){ ?>
						<img src="images/noImage-p.jpg" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php } else {  ?>
						<img src="Doc/<?php echo $result_pro[3]['ref_id']; ?>/<?php echo $result_pro[3]['doc_photo']; ?>" width="80" height="60" style="float:left;margin-right:5px;"/>
					<?php }	?><div>
						<?php $getHosp2 = $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$result_pro[3]['ref_id']."'" ,"","","",""); ?>
							<p><b>Ref.Name :</b> <?php echo $result_pro[3]['ref_name'];?><br>
							<?php if(!empty($getHosp[0]['hosp_name'])){ ?><b>Hospital :</b> <?php echo $getHosp[0]['hosp_name'];?><br><?php } ?>
							<?php if(!empty($getSpec[0]['spec_name'])){ ?><b>Specialization :</b> <?php echo $getSpec[0]['spec_name'];?><br><?php } ?>
							<?php if(!empty($result_pro[3]['ref_exp'])){ ?><b>Year of Exp. :</b> <?php echo $result_pro[3]['ref_exp'];?><br><?php } ?>
							<?php if(!empty($result_pro[3]['ref_address'])){ ?><b>Location :</b> <?php echo $result_pro[3]['ref_address'];?><br><?php } ?>
							<?php if(!empty($getHosp2[0]['hosp_addrs'])){ ?><b>Hospital Address :</b> <?php echo $getHosp2[0]['hosp_name']."- &nbsp;".$getHosp2[0]['hosp_addrs'];  if(!empty($getHosp2[0]['hosp_contact'])){ ?>  Hosp.Contact:<?php echo $getHosp2[0]['hosp_contact']; }?><br><?php if(!empty($getHosp2[0]['hosp_email']) || !empty($getHosp2[0]['hosp_email1']) || !empty($getHosp2[0]['hosp_email2']) || !empty($getHosp1[0]['hosp_email3'])){ ?>Hosp.Email:<?php echo $getHosp2[0]['hosp_email'].", ".$getHosp2[0]['hosp_email1'].", ".$getHosp2[0]['hosp_email2'].", ".$getHosp2[0]['hosp_email3'].", ".$getHosp2[0]['hosp_email4']; }?><?php } ?>
							<?php if(!empty($result_pro[3]['doc_state'])){ ?><b>State :</b> <?php echo $result_pro[3]['doc_state'];?><br><?php } ?>
							<?php if(!empty($result_pro[3]['contact_num'])){ ?><b>Doc Phone :</b> <?php echo $result_pro[3]['contact_num'];?><br><?php } ?>
							<?php if(!empty($result_pro[3]['ref_mail'])){ ?><b>Doc Email :</b> <?php echo $result_pro[3]['ref_mail'];?><br><?php } ?>
							<?php if(!empty($result_pro[3]['doc_contribute'])){ ?><b>Contributions :</b> <?php echo $result_pro[3]['doc_contribute'];?><br><?php } ?>
							</p>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				
				
				<select name="slctState" id="slctState" class="slctField fr" onchange='return chgStatus3(this.value)'>
				<option value="0">Select</option>
				<?php $Pro_Name= $objQuery->mysqlSelect("*","provider_status","","value asc","","","");
									$i=30;
									foreach($Pro_Name as $Pro_Name_list){
										if($Pro_Name_list['value']==$result_pro[3]['status2']){
											?>
								       <option value="<?php echo stripslashes($Pro_Name_list['value']);?>" selected>
									   <?php echo stripslashes($Pro_Name_list['status']);?></option>
									   <?php } ?>
										<option value=<?php echo stripslashes($Pro_Name_list['value']);?>><?php echo stripslashes($Pro_Name_list['status']);?></option> 
				<?php $i++; }?></select><br><br>
				<div class="page active" data-page="popModal">
					<a href="javascript:void(0);" id="popModal_ex9" style="font-size:12px; color:red;">View Doc Response</a>
				</div>
				
				<div style="display:none;">
					<div id="content9" style="width:330px;">
						
						<div>
						<?php $Pro1_Interact = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$getInfo[0]['patient_id']."'and ref_id='".$result_pro[3]['ref_id']."'","chat_id desc","","","");
							foreach($Pro1_Interact as $chalist){
							?>
							<p><span><b><?php echo $result_pro[3]['ref_name']."( ";?><?php echo $Date=date('d-m-Y H:i:s',strtotime($chalist['TImestamp']))." )";?> </b></span><?php echo $chalist['chat_note']; ?></p><br>
							<?php } ?>
						</div>
						
						
						<!--<div class="popModal_footer">
							<button type="button" class="btn btn-primary" data-popmodal-but="ok">ok</button>
							<button type="button" class="btn btn-default" data-popmodal-but="cancel">cancel</button>
						</div>-->
					</div>
				</div>
				<iframe src="interaction_page4.php" width="290" height="380">
				</iframe>
				
				<!--<textarea class="txtArea" name="txtpro4" ></textarea>
				<input type="submit" value="ADD" name="cmdAdd4" class="addRefBtn" />-->
				
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal4" data-whatever="@mdo">Add Response</button>
				<div class="modal fade bs-example-modal-lg" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Enter <?php echo $result_pro[3]['ref_name']; ?> Response</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body" >
							<form>
							 
							  
								<!--<label for="message-text" class="form-control-label">Response:</label>
								<textarea class="form-control" id="message-text"></textarea>-->
								<iframe src="editor.php?docid=<?php echo $result_pro[3]['ref_id']; ?>&patid=<?php echo $getInfo[0]['patient_id']; ?>&userid=<?php echo $admin_id; ?>" width="100%" height="470" style="border:none;"></iframe>
							 
							</form>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							
						  </div>
						</div>
					  </div>
					</div>
				
				</div>
			</div>
		</form>	
		</div>
	<?php } ?>
	
</div>
</div>
</div>




<div class="footer">
<div class="clearfix">
   
  </div>
</div>



<script>
		$(function() {
			// Clickable Dropdown
			$('.click-nav > ul').toggleClass('no-js js');
			$('.click-nav .js ul').hide();
			$('.click-nav .js').click(function(e) {
				$('.click-nav .js ul').slideToggle(200);
				$('.clicker').toggleClass('active');
				e.stopPropagation();
			});
			$(document).click(function() {
				if ($('.click-nav .js ul').is(':visible')) {
					$('.click-nav .js ul', this).slideUp();
					$('.clicker').removeClass('active');
				}
			});
		});
		</script>

<script src="jsPopup/popModal.js"></script>
<script>
$(function(){
	$('#popModal_ex1').click(function(){
		$('#popModal_ex1').popModal({
			html : $('#content'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	
	$('#popModal_ex2').click(function(){
		$('#popModal_ex2').popModal({
			html : $('#content1'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	
	$('#popModal_ex3').click(function(){
		$('#popModal_ex3').popModal({
			html : $('#content2'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	
	$('#popModal_ex4').click(function(){
		$('#popModal_ex4').popModal({
			html : $('#content3'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	
	$('#popModal_ex5').click(function(){
		$('#popModal_ex5').popModal({
			html : $('#content4'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex6').click(function(){
		$('#popModal_ex6').popModal({
			html : $('#content5'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex7').click(function(){
		$('#popModal_ex7').popModal({
			html : $('#content7'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex8').click(function(){
		$('#popModal_ex8').popModal({
			html : $('#content8'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex9').click(function(){
		$('#popModal_ex9').popModal({
			html : $('#content9'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex10').click(function(){
		$('#popModal_ex10').popModal({
			html : $('#content10'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	$('#popModal_ex11').click(function(){
		$('#popModal_ex11').popModal({
			html : $('#content11'),
			placement : 'bottomLeft',
			showCloseBut : true,
			onDocumentClickClose : true,
			onDocumentClickClosePrevent : '',
			overflowContent : false,
			inline : true,
			asMenu : false,
			beforeLoadingContent : 'Please, wait...',
			onOkBut : function() {},
			onCancelBut : function() {},
			onLoad : function() {},
			onClose : function() {}
		});
	});
	/* tab */
(function($) {
	$.fn.tab = function(method){
	
		var methods = {
			init : function(params) {

				$('.tab').click(function() {
					var curPage = $(this).attr('data-tab');
					$(this).parent().find('> .tab').each(function(){
						$(this).removeClass('active');
					});
					$(this).parent().find('+ .page_container > .page').each(function(){
						$(this).removeClass('active');
					});
					$(this).addClass('active');
					$('.page[data-page="' + curPage + '"]').addClass('active');
				});
			
			}
		};

		if (methods[method]) {
			return methods[method].apply( this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		}
		
	};
	$('html').tab();
	
})(jQuery);
	
});
</script>		
<script src="bootstrap.min.js"></script>
</body>
</html>

