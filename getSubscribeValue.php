<?php ob_start();
 error_reporting(0);
 session_start(); 


date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');

$add_days = 365;
$Expiry_Date = date('Y-m-d h:i:s',strtotime($Cur_Date) + (24*3600*$add_days));

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();

if(isset($_POST['subname'])){
	
	$txtSubId = $_POST['loginid'];
	$txtname = $_POST['subname'];
	$txtcontact = $_POST['subcontact'];
	$txtmail = $_POST['submail'];
	$txtage = $_POST['subage'];
	$txtmerital = $_POST['submerital'];
	$txtqual = $_POST['subqual'];
	$txtprof = $_POST['subprof'];
	$txtgend = $_POST['subgend'];
	$txtcountry = $_POST['subcountry'];
	$txtstate = $_POST['substate'];
	$txtcity = $_POST['subcity'];
	$txtaddress = $_POST['subaddress'];
	$txtpasswd = $_POST['subpasswd'];
	
	$txtfbname1 = $_POST['subfbname1'];
	$txtrel1 = $_POST['subrel1'];
	$txtage1 = $_POST['subage1'];
	
	$txtfbname2 = $_POST['subfbname2'];
	$txtrel2 = $_POST['subrel2'];
	$txtage2 = $_POST['subage2'];
	
	$txtfbname3 = $_POST['subfbname3'];
	$txtrel3 = $_POST['subrel3'];
	$txtage3 = $_POST['subage3'];
	
	$txtfbname4 = $_POST['subfbname4'];
	$txtrel4 = $_POST['subrel4'];
	$txtage4 = $_POST['subage4'];
	
	$txtfbname5 = $_POST['subfbname5'];
	$txtrel5 = $_POST['subrel5'];
	$txtage5 = $_POST['subage5'];
	
	$arrFields = array();
	$arrValues = array();
		$arrFields[] = 'login_id';
		$arrValues[] = $txtSubId;
		
		$arrFields[] = 'sub_name';
		$arrValues[] = $txtname;
		
		$arrFields[] = 'sub_contact';
		$arrValues[] = $txtcontact;
		
		$arrFields[] = 'sub_email';
		$arrValues[] = $txtmail;
		
		$arrFields[] = 'sub_age';
		$arrValues[] = $txtage;
		
		$arrFields[] = 'sub_merital';
		$arrValues[] = $txtmerital;
		
		$arrFields[] = 'sub_gender';
		$arrValues[] = $txtgend;
		
		$arrFields[] = 'sub_qualification';
		$arrValues[] = $txtqual;
		
		$arrFields[] = 'sub_proff';
		$arrValues[] = $txtprof;
		
		$arrFields[] = 'sub_address';
		$arrValues[] = $txtaddress;
		
		$arrFields[] = 'sub_city';
		$arrValues[] = $txtcity;
		
		$arrFields[] = 'sub_state';
		$arrValues[] = $txtstate;
		
		$arrFields[] = 'sub_country';
		$arrValues[] = $txtcountry;
		
		$arrFields[] = 'reg_date';
		$arrValues[] = $Cur_Date;
		
		$arrFields[] = 'expiry_date';
		$arrValues[] = $Expiry_Date;
		
		$arrFields[] = 'passwd';
		$arrValues[] = $txtpasswd;
		
		$usercraete=$objQuery->mysqlInsert('login_user',$arrFields,$arrValues);
		$id = mysql_insert_id();
		$subrefId="MED16S".$id;
		
		$arrFields2 = array();
		$arrValues2 = array();
		$arrFields2[] = 'suscribe_refid';
		$arrValues2[] = $subrefId;
		$updateSub=$objQuery->mysqlUpdate('login_user',$arrFields2,$arrValues2,"login_id='".$id."'");	
		
		/*$familyrefId1="MED16S".$id."A";
		$familyrefId2="MED16S".$id."B";
		$familyrefId3="MED16S".$id."C";
		$familyrefId4="MED16S".$id."D";
		$familyrefId5="MED16S".$id."E";	
		
		if(!empty($txtfbname1)){
				$arrFields1 = array();
				$arrValues1 = array();
				$arrFields1[] = 'login_id';
				$arrValues1[] = $id;		
				$arrFields1[] = 'subfamilyref_id';
				$arrValues1[] = $familyrefId1;	
				$arrFields1[] = 'name';
				$arrValues1[] = $txtfbname1;
				$arrFields1[] = 'relationship';
				$arrValues1[] = $txtrel1;
				$arrFields1[] = 'age';
				$arrValues1[] = $txtage1;
				$arrFields1[] = 'system_date';
				$arrValues1[] = $Cur_Date;
				$usercraete1=$objQuery->mysqlInsert('subscribe_family',$arrFields1,$arrValues1);
		}
		if(!empty($txtfbname2)){
				$arrFields2 = array();
				$arrValues2 = array();	
				$arrFields2[] = 'login_id';
				$arrValues2[] = $id;
				$arrFields2[] = 'subfamilyref_id';
				$arrValues2[] = $familyrefId2;
				$arrFields2[] = 'name';
				$arrValues2[] = $txtfbname2;
				$arrFields2[] = 'relationship';
				$arrValues2[] = $txtrel2;
				$arrFields2[] = 'age';
				$arrValues2[] = $txtage2;
				$arrFields2[] = 'system_date';
				$arrValues2[] = $Cur_Date;
				$usercraete2=$objQuery->mysqlInsert('subscribe_family',$arrFields2,$arrValues2);
		}
		if(!empty($txtfbname3)){
				$arrFields3 = array();
				$arrValues3 = array();	
				$arrFields3[] = 'login_id';
				$arrValues3[] = $id;
				$arrFields3[] = 'subfamilyref_id';
				$arrValues3[] = $familyrefId3;
				$arrFields3[] = 'name';
				$arrValues3[] = $txtfbname3;
				$arrFields3[] = 'relationship';
				$arrValues3[] = $txtrel3;
				$arrFields3[] = 'age';
				$arrValues3[] = $txtage3;
				$arrFields3[] = 'system_date';
				$arrValues3[] = $Cur_Date;
				$usercraete3=$objQuery->mysqlInsert('subscribe_family',$arrFields3,$arrValues3);
		}
		if(!empty($txtfbname4)){
		$arrFields4 = array();
		$arrValues4 = array();	
		$arrFields4[] = 'login_id';
		$arrValues4[] = $id;
		$arrFields4[] = 'subfamilyref_id';
		$arrValues4[] = $familyrefId4;
		$arrFields4[] = 'name';
		$arrValues4[] = $txtfbname4;
		$arrFields4[] = 'relationship';
		$arrValues4[] = $txtrel4;
		$arrFields4[] = 'age';
		$arrValues4[] = $txtage4;
		$arrFields4[] = 'system_date';
		$arrValues4[] = $Cur_Date;
		$usercraete4=$objQuery->mysqlInsert('subscribe_family',$arrFields4,$arrValues4);
		}
		if(!empty($txtfbname5)){
		$arrFields5 = array();
		$arrValues5 = array();	
		$arrFields5[] = 'login_id';
		$arrValues5[] = $id;
		$arrFields5[] = 'subfamilyref_id';
		$arrValues5[] = $familyrefId5;
		$arrFields5[] = 'name';
		$arrValues5[] = $txtfbname5;
		$arrFields5[] = 'relationship';
		$arrValues5[] = $txtrel5;
		$arrFields5[] = 'age';
		$arrValues5[] = $txtage5;
		$arrFields5[] = 'system_date';
		$arrValues5[] = $Cur_Date;
		$usercraete5=$objQuery->mysqlInsert('subscribe_family',$arrFields5,$arrValues5);
		} */
		
			
	//MAIL TO SUBSCRIBER
	$getSubInfo = $objQuery->mysqlSelect("*","login_user","login_id='".$id."'" ,"","","","");
	$getSubFamInfo = $objQuery->mysqlSelect("*","subscribe_family","login_id='".$id."'" ,"","","","");
	if($getSubInfo[0]['sub_email']!=""){
		$Sub_mail=$getSubInfo[0]['sub_email'];
		$Sub_name=$getSubInfo[0]['sub_name'];
		$Suscribe_refid=$getSubInfo[0]['suscribe_refid'];
		$Sub_contact=$getSubInfo[0]['sub_contact'];
		$Reg_date=date('d-m-Y',strtotime($getSubInfo[0]['reg_date']));
		$Expiry_date=date('d-m-Y',strtotime($getSubInfo[0]['expiry_date']));
		$Passwd=$getSubInfo[0]['passwd'];
		
		if(!empty($getSubFamInfo[0]['name'])){
		$Subfam_name1=$getSubFamInfo[0]['name'];
		$Subfam_age1=$getSubFamInfo[0]['age'];
		$Subfam_rel1=$getSubFamInfo[0]['relationship'];
		$Subfam_refid1=$getSubFamInfo[0]['subfamilyref_id'];
		}
		if(!empty($getSubFamInfo[1]['name'])){
		$Subfam_name2=$getSubFamInfo[1]['name'];
		$Subfam_age2=$getSubFamInfo[1]['age'];
		$Subfam_rel2=$getSubFamInfo[1]['relationship'];
		$Subfam_refid2=$getSubFamInfo[1]['subfamilyref_id'];
		}
		if(!empty($getSubFamInfo[2]['name'])){
		$Subfam_name3=$getSubFamInfo[2]['name'];
		$Subfam_age3=$getSubFamInfo[2]['age'];
		$Subfam_rel3=$getSubFamInfo[2]['relationship'];
		$Subfam_refid3=$getSubFamInfo[2]['subfamilyref_id'];
		}
		if(!empty($getSubFamInfo[3]['name'])){
		$Subfam_name4=$getSubFamInfo[3]['name'];
		$Subfam_age4=$getSubFamInfo[3]['age'];
		$Subfam_rel4=$getSubFamInfo[3]['relationship'];
		$Subfam_refid4=$getSubFamInfo[3]['subfamilyref_id'];
		}
		if(!empty($getSubFamInfo[4]['name'])){
		$Subfam_name5=$getSubFamInfo[4]['name'];
		$Subfam_age5=$getSubFamInfo[4]['age'];
		$Subfam_rel5=$getSubFamInfo[4]['relationship'];
		$Subfam_refid5=$getSubFamInfo[4]['subfamilyref_id'];
		}
		
		//SEND MAIL TO SUBSCRIBER
		
					$url_page = 'subscriber_mail.php';
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?submail=" . urlencode($Sub_mail);
					$url .= "&subname=" . urlencode($Sub_name);
					$url .= "&subrefid=" . urlencode($Suscribe_refid);
					$url .= "&subcontact=" . urlencode($Sub_contact);
					$url .= "&regdate=" . urlencode($Reg_date);
					$url .= "&expdate=" . urlencode($Expiry_date);
					$url .= "&passwd=" . urlencode($Passwd);
					
					$url .= "&subfamname1=" . urlencode($Subfam_name1);
					$url .= "&subfamage1=" . urlencode($Subfam_age1);
					$url .= "&subfamrel1=" . urlencode($Subfam_rel1);
					$url .= "&subfamrefid1=" . urlencode($Subfam_refid1);
					
					$url .= "&subfamname2=" . urlencode($Subfam_name2);
					$url .= "&subfamage2=" . urlencode($Subfam_age2);
					$url .= "&subfamrel2=" . urlencode($Subfam_rel2);
					$url .= "&subfamrefid2=" . urlencode($Subfam_refid2);
					
					$url .= "&subfamname3=" . urlencode($Subfam_name3);
					$url .= "&subfamage3=" . urlencode($Subfam_age3);
					$url .= "&subfamrel3=" . urlencode($Subfam_rel3);
					$url .= "&subfamrefid3=" . urlencode($Subfam_refid3);
					
					$url .= "&subfamname4=" . urlencode($Subfam_name4);
					$url .= "&subfamage4=" . urlencode($Subfam_age4);
					$url .= "&subfamrel4=" . urlencode($Subfam_rel4);
					$url .= "&subfamrefid4=" . urlencode($Subfam_refid4);
					
					$url .= "&subfamname5=" . urlencode($Subfam_name5);
					$url .= "&subfamage5=" . urlencode($Subfam_age5);
					$url .= "&subfamrel5=" . urlencode($Subfam_rel5);
					$url .= "&subfamrefid5=" . urlencode($Subfam_refid5);
					
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
				if($getSubInfo[0]['sub_contact']!=""){
					$mobile = $getSubInfo[0]['sub_contact'];
					$msg = urlencode ( "Congrats ".$getSubInfo[0]['sub_name'].",You have successfully registered and logged in. For more details please check your mail inbox or spam box, Thanks. MedisenseHealth.com" );
					
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
					
	}
	

?>


