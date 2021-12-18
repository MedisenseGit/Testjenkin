<?php ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
include('send_mail_function.php');
//Random Password Generator
function randomPassword() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


//USER DIRECT LOGIN
if(isset($_POST['signinDirect'])){
	$txtUserName = $_POST['txtuser'];
	 $txtPass = md5($_POST['txtpassword']);
	
	$result = $objQuery->mysqlSelect('*','our_partners',"(Email_id='".$txtUserName."' or cont_num1='".$txtUserName."') and password='".$txtPass."'");
	
	if($result==true){
		
		//When he first login "login_status" should make it 1 ie. 1 for "signup success"
		$arrFields[] = 'login_status';
		$arrValues[] = "1";
		$updateMapping=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$result[0]['partner_id']."'");
		
		$mycircleDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$result[0]['partner_id']."'","","","","");	
		$universalDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","","","","");	
		$getDignosticCenter = $objQuery->mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=89 and anonymous_status!=1","","","","");	
		$getOnlinePharma = $objQuery->mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=90 and anonymous_status!=1","","","","");	
	
		
		$_SESSION['mycircle_doc'] = $mycircleDoctor[0]['Count_Doc'];
		$_SESSION['universal_doc'] = $universalDoctor[0]['Count_Doc'];
		$_SESSION['diagnostics_center'] = $getDignosticCenter[0]['Count_Doc'];
		$_SESSION['online_pharma'] = $getOnlinePharma[0]['Count_Doc'];
		
		$totReferredCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=2 or b.status2>=5)");
		$totRespondedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2>=5)");
		$totTreatedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=6 or b.status2=7 or b.status2=8 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13)");
		$totalBlogs = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count_Blogs","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$result[0]['partner_id']."'","a.listing_id desc","","","");
		$totPendingCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.bucket_status=2)");
		
		//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
		$_SESSION['user_name'] = $result[0]['user_name'];
		$_SESSION['user_id'] = $result[0]['partner_id'];
		$_SESSION['company_name'] = $result[0]['contact_person'];
		$_SESSION['all_record'] = $allRecordCount[0]['Count_Records'];
		$_SESSION['tot_ref_count'] = $totReferredCount[0]['Count_Records'];
		$_SESSION['tot_resp_count'] = $totRespondedCount[0]['Count_Records'];
		$_SESSION['tot_pending_count'] = $totPendingCount[0]['Count_Records'];
		$_SESSION['tot_treated_count'] = $totTreatedCount[0]['Count_Records'];
		$_SESSION['tot_Blogs'] = $totalBlogs[0]['Count_Blogs'];
		header('location:offers.php?s=Jobs&id='.md5($_POST['eventid']));
		
		
	}
	else
	{
		$_SESSION['status']="error";
		$errorMessage="Login failed. Username or password are invalid.";
		header('location:'.$_POST['currenturl']);
	}

}


//USER LOGIN
if(isset($_POST['signin'])){
	$txtUserName = $_POST['txtuser'];
	 $txtPass = md5($_POST['txtpassword']);
	
	$result = $objQuery->mysqlSelect('*','our_partners',"(Email_id='".$txtUserName."' or cont_num1='".$txtUserName."') and password='".$txtPass."'");
	
	if($result==true){
		
		//When he first login "login_status" should make it 1 ie. 1 for "signup success"
		$arrFields[] = 'login_status';
		$arrValues[] = "1";
		$updateMapping=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$result[0]['partner_id']."'");
		
		$mycircleDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join mapping_hosp_referrer as d on d.hosp_id=c.hosp_id","a.doc_spec!=555 and a.anonymous_status!=1 and d.partner_id='".$result[0]['partner_id']."'","","","","");	
		$universalDoctor = $objQuery->mysqlSelect("COUNT(DISTINCT(a.ref_id)) as Count_Doc","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id","a.doc_spec!=555 and a.anonymous_status!=1","","","","");	
		$getDignosticCenter = $objQuery->mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=89 and anonymous_status!=1","","","","");	
		$getOnlinePharma = $objQuery->mysqlSelect("COUNT(DISTINCT(ref_id)) as Count_Doc","referal","doc_spec=90 and anonymous_status!=1","","","","");	
	
		
		$_SESSION['mycircle_doc'] = $mycircleDoctor[0]['Count_Doc'];
		$_SESSION['universal_doc'] = $universalDoctor[0]['Count_Doc'];
		$_SESSION['diagnostics_center'] = $getDignosticCenter[0]['Count_Doc'];
		$_SESSION['online_pharma'] = $getOnlinePharma[0]['Count_Doc'];
		
		$totReferredCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=2 or b.status2>=5)");
		$totRespondedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2>=5)");
		$totTreatedCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.status2=6 or b.status2=7 or b.status2=8 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13)");
		$totalBlogs = $objQuery->mysqlSelect("COUNT(a.listing_id) as Count_Blogs","blogs_offers_events_listing as a inner join mapping_hosp_referrer as b on a.hosp_id=b.hosp_id","b.partner_id='".$result[0]['partner_id']."'","a.listing_id desc","","","");
		$totPendingCount = $objQuery->mysqlSelect('COUNT(DISTINCT(a.patient_id)) as Count_Records','patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src',"(c.partner_id='".$result[0]['partner_id']."') and (b.bucket_status=2)");
		
		//IF ITS TRUE THEN REDIRECTS TO "ALL RECORDS" PAGE
		$_SESSION['user_name'] = $result[0]['user_name'];
		$_SESSION['user_id'] = $result[0]['partner_id'];
		$_SESSION['docspec'] = $result[0]['specialisation'];
		$_SESSION['company_name'] = $result[0]['contact_person'];
		$_SESSION['all_record'] = $allRecordCount[0]['Count_Records'];
		$_SESSION['tot_ref_count'] = $totReferredCount[0]['Count_Records'];
		$_SESSION['tot_resp_count'] = $totRespondedCount[0]['Count_Records'];
		$_SESSION['tot_pending_count'] = $totPendingCount[0]['Count_Records'];
		$_SESSION['tot_treated_count'] = $totTreatedCount[0]['Count_Records'];
		$_SESSION['tot_Blogs'] = $totalBlogs[0]['Count_Blogs'];
		header('location:Home');
		
		
	}
	else
	{
		$respond=2;
		$errorMessage="Login failed. Username or password are invalid.";
		header('location:login?respond='.$respond);
	}

}

//USER REGISTRATION 
if(isset($_POST['register'])){
	 $txtDocName = addslashes($_POST['txtDocName']);
	 $slctCountry = addslashes($_POST['slctCountry']);
	 $slctState = addslashes($_POST['slctState']);
	 $txtCity = addslashes($_POST['txtCity']);
	 $slctSpec = addslashes($_POST['slctSpec']);
	 $txtHosp = addslashes($_POST['txtHosp']);
	 $txtQual = addslashes($_POST['txtQual']);
	 $txtMob = addslashes($_POST['txtMob']);
	 $txtEmail = addslashes($_POST['txtEmail']);
	  $passwd = addslashes($_POST['passwd']);
	 $txtMedCouncil = addslashes($_POST['txtMedCouncil']);
	 $txtMedRegnum = addslashes($_POST['txtMedRegnum']);
		 
	 $txtregCert = basename($_FILES['txtregCert']['name']);
		
	$result = $objQuery->mysqlSelect('*','our_partners',"Email_id='".$txtEmail."'or cont_num1='".$txtMob."'");
	
	if(empty($result)){
		
		$arrFields = array();
		$arrValues = array();
		
	$arrFields[] = 'contact_person';
	$arrValues[] = $txtDocName;
	$arrFields[] = 'login_status';
	$arrValues[] = "1";
	$arrFields[] = 'country';
	$arrValues[] = $slctCountry;
	$arrFields[] = 'state';
	$arrValues[] = $slctState;
	$arrFields[] = 'location';
	$arrValues[] = $txtCity;
	$arrFields[] = 'Type';
	$arrValues[] = "Doctor";
	$arrFields[] = 'specialisation';
	$arrValues[] = $slctSpec;
	$arrFields[] = 'partner_name';
	$arrValues[] = $txtHosp;
	$arrFields[] = 'doc_qual';
	$arrValues[] = $txtQual;
	$arrFields[] = 'cont_num1';
	$arrValues[] = $txtMob;
	$arrFields[] = 'Email_id';
	$arrValues[] = $txtEmail;
	$arrFields[] = 'password';
	$arrValues[] = md5($passwd);
	$arrFields[] = 'reg_date';
	$arrValues[] = date('Y-m-d H:i:s');
	
	$doccreate=$objQuery->mysqlInsert('our_partners',$arrFields,$arrValues);
	$docid= mysql_insert_id();
	$arrFields1 = array();
	$arrValues1 = array();
		
	$arrFields1[] = 'doc_id';
	$arrValues1[] = $docid;
	$arrFields1[] = 'type';
	$arrValues1[] = "2"; //Type 2 for practice doctors
	$arrFields1[] = 'council_name';
	$arrValues1[] = $txtMedCouncil;
	$arrFields1[] = 'reg_num';
	$arrValues1[] = $txtMedRegnum;
	$arrFields1[] = 'reg_certificate';
	$arrValues1[] = $txtregCert;
	$arrFields1[] = 'create_date';
	$arrValues1[] = date('Y-m-d H:i:s');
	
	$doccreate=$objQuery->mysqlInsert('doctor_registration_details',$arrFields1,$arrValues1);
	//$docregid= mysql_insert_id();
	/* Uploading image file */ 
				if(basename($_FILES['txtregCert']['name']!=="")){ 
					$uploaddirectory = realpath("../PracticeDocCertificate");
					mkdir("../PracticeDocCertificate/". "/" . $docid, 0777);
					$uploaddir = $uploaddirectory."/".$docid;
					$dotpos = strpos($_FILES['txtregCert']['name'], '.');
					$photo = $txtregCert;
					$uploadfile = $uploaddir . "/" . $photo;			
				
							
					/* Moving uploaded file from temporary folder to desired folder. */
					if(move_uploaded_file ($_FILES['txtregCert']['tmp_name'], $uploadfile)) {
						//echo "File uploaded.";
					} else {
						//echo "File cannot be uploaded";
					}
				}
				
						$url_page = 'pratctice_registration_mail.php';
						$url .= rawurlencode($url_page);
						$url .= "?receiverMail=".urlencode($txtEmail);
						$url .= "&username=".urlencode($txtDocName);						
						send_mail($url);
		$respond='0';
		header('location:Home');	
	}
	else
	{
		$respond='2';
		header('location:login?respond='.$respond);
	}

}


if(isset($_POST['forgot'])) {

	$useremail = $_POST['txtemail'];
	$password = randomPassword();
	$encypassword = md5($password);
	
	
	
	$chkUser = $objQuery->mysqlSelect("*","our_partners","Email_id='".$useremail."'","","","","");
		if($chkUser==true){
			$arrFields[] = 'password';
			$arrValues[] = $encypassword;
		
		$updateUser=$objQuery->mysqlUpdate('our_partners',$arrFields,$arrValues,"partner_id='".$chkUser[0]['partner_id']."'");
		
		$recoverLink="Link: www.medisensepractice.com<br>User Name: ".$chkUser[0]['Email_id']." / ".$chkUser[0]['cont_num1']."<br>Password: ".$password;
			
			$message= stripslashes("We received a request to reset the password associated with this e-mail address. If you made this request, please follow the instructions below. <br>

	Please use below temporary user name & password using our secure server:<br><br> 

	".$recoverLink."<br><br>
	If you did not request to reset your password, you can safely ignore this email. Rest assured your customer account is safe.");
			
			
						$url_page = 'send_recoverLink.php';
						$url = "https://referralio.com/REFERRALIO_EMAIL/";
						$url .= rawurlencode($url_page);
						$url .= "?usermail=".urlencode($useremail);
						$url .= "&username=".urlencode($chkUser[0]['contact_person']);
						$url .= "&message=".urlencode($message);
						$url .= "&reclink=".urlencode($recoverLink);
						$ch = curl_init (); // setup a curl
						
						curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
						
						curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
						
						curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
						
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
						
						curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
						
						$output = curl_exec ( $ch );
						
						// echo "output".$output;
						
						curl_close ( $ch ); 
		header('Location:login?respond=3');
		}
		else
		{
			header('Location:login?respond=4');
		}
	
	
}
?>