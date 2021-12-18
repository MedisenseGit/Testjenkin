<?php ob_start();
 error_reporting(0);
 session_start(); 


require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


	$Appointid = addslashes($_POST['appointid']);
	$Appointtransid = addslashes($_POST['appointtransid']);
	$Prefdoc = addslashes($_POST['prefdoc']);
	$Userid = addslashes($_POST['userid']);
	$Dept = addslashes($_POST['dept']);
	$Hid = addslashes($_POST['hid']);
	$Visitdate = addslashes($_POST['visitdate']);
	$Visittime = addslashes($_POST['visittime']);
	$Patname = addslashes($_POST['patname']);
	$Mobno = addslashes($_POST['mobno']);
	$Email = addslashes($_POST['email']);
	$Amount = addslashes($_POST['amount']);
	$Paystate = addslashes($_POST['paystate']);
	$Visitstate = addslashes($_POST['visitstate']);
	$Timestamp = addslashes($_POST['timestamp']);
	
	
	$arrFields = array();
	$arrValues = array();
				
			
				$arrFields[] = 'appoint_trans_id';
				$arrValues[] = $Appointtransid;
				$arrFields[] = 'pref_doc';
				$arrValues[] = $Prefdoc;
				$arrFields[] = 'department';
				$arrValues[] = $Dept;
				$arrFields[] = 'Login_User_Id';
				$arrValues[] = $Userid;
				$arrFields[] = 'Hosp_patient_Id';
				$arrValues[] = $Hid;
				$arrFields[] = 'Visiting_date';
				$arrValues[] = $Visitdate;
				$arrFields[] = 'Visiting_time';
				$arrValues[] = $Visittime;
				$arrFields[] = 'patient_name';
				$arrValues[] = $Patname;
				$arrFields[] = 'Mobile_no';
				$arrValues[] = $Mobno;
				$arrFields[] = 'Email_address';
				$arrValues[] = $Email;
				$arrFields[] = 'Amount';
				$arrValues[] = $Amount;
				$arrFields[] = 'pay_status';
				$arrValues[] = $Paystate;
				$arrFields[] = 'visit_status';
				$arrValues[] = $Visitstate;
				$arrFields[] = 'Time_stamp';
				$arrValues[] = $Timestamp;
	
	$usercraete=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields,$arrValues);
	$appoint_id=mysql_insert_id();
	
	$getAppointDet= $objQuery->mysqlSelect("*","appointment_transaction_detail","id='".$appoint_id."'","","","","");
	$getDoc= $objQuery->mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id left join referal as c on c.ref_id=b.doc_id","b.doc_id='".$getAppointDet[0]['pref_doc']."'" ,"","","","");
	$getSpec = $objQuery->mysqlSelect("*","specialization","spec_id='".$getAppointDet[0]['department']."'","","","","");
	$getTime= $objQuery->mysqlSelect("*","timings","Timing_id='".$getAppointDet[0]['Visiting_time']."'","","","","");

					$ccmail="medical@medisense.me";
					$bccmail=$getDoc[0]['ref_mail'];
					$visit_date=date('M.d,Y',strtotime($getAppointDet[0]['Visiting_date']));
					if($getAppointDet[0]['Email_address']!=""){
					$url_page = 'checkin_mail_template.php';
					
					$url = "https://referralio.com/EMAIL/";
					$url .= rawurlencode($url_page);
					$url .= "?place=".urlencode($getDoc[0]['hosp_city']);
					$url .= "&transid=".urlencode($getAppointDet[0]['appoint_trans_id']);
					$url .= "&visitdate=".urlencode($visit_date);
					$url .= "&dept=".urlencode($getSpec[0]['spec_name']);
					$url .= "&hospital=".urlencode($getDoc[0]['hosp_name']);
					$url .= "&docname=".urlencode($getDoc[0]['ref_name']);
					$url .= "&patname=".urlencode($getAppointDet[0]['patient_name']);
					$url .= "&patmail=".urlencode($getAppointDet[0]['Email_address']);
					$url .= "&transid=".urlencode($getAppointDet[0]['appoint_trans_id']);
					$url .= "&hid=".urlencode($getAppointDet[0]['Hosp_patient_Id']);
					$url .= "&paystate=".urlencode($getAppointDet[0]['pay_status']);
					$url .= "&visitstate=".urlencode($getAppointDet[0]['visit_status']);
					$url .= "&visittime=".urlencode($getTime[0]['Timing']);
					$url .= "&city=".urlencode($getDoc[0]['doc_city']);
					$url .= "&hospaddress=".urlencode($getDoc[0]['hosp_addrs']);
					$url .= "&amount=".urlencode($getAppointDet[0]['Amount']);
					$url .= "&ccmail=".urlencode($ccmail);
					$url .= "&bccmail=".urlencode($bccmail);
					
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
					if($getAppointDet[0]['Mobile_no']!=""){
					$mobile = $getAppointDet[0]['Mobile_no'];
					$vis_date=date('d/m/Y',strtotime($getAppointDet[0]['Visiting_date']));
					$msg = urlencode ( "Please make the payment at OPD counter for, TransactionID ". $getAppointDet[0]['appoint_trans_id'] . " | ". $getAppointDet[0]['patient_name'] . " | ".$getAppointDet[0]['Hosp_patient_Id']." | ".$getDoc[0]['hosp_name']." | ".$getSpec[0]['spec_name']." | ".$vis_date." | ".$getTime[0]['Timing']." |Rs. ".$getAppointDet[0]['Amount']." | ".$getDoc[0]['ref_name']);
					
					$url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=medisenseotp&password=VHASQzka&type=0&dlr=1&destination=" . $mobile . "&source=HCHKIN&message=" . $msg;
		
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					}
					//SMS notification to Doctor
					if($getDoc[0]['contact_num']!=""){
					$mobile = $getDoc[0]['contact_num'];
					$vis_date=date('d/m/Y',strtotime($getAppointDet[0]['Visiting_date']));
					$msg = urlencode ( "APPOINTMENT REQUEST, TransactionID ". $getAppointDet[0]['appoint_trans_id'] . " | ". $getAppointDet[0]['patient_name'] . "(ph:".$getAppointDet[0]['Mobile_no'].") | ".$getAppointDet[0]['Hosp_patient_Id']." | ".$getDoc[0]['hosp_name']." | ".$getSpec[0]['spec_name']." | ".$vis_date." | ".$getTime[0]['Timing']." |Rs. ".$getAppointDet[0]['Amount']." | ".$getDoc[0]['ref_name']);
					
					$url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=medisenseotp&password=VHASQzka&type=0&dlr=1&destination=" . $mobile . "&source=HCHKIN&message=" . $msg;
		
					$ch = curl_init (); // setup a curl
					
					curl_setopt ( $ch, CURLOPT_URL, $url ); // set url to send to
					
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers ); // set custom headers
					
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true ); // return data reather than echo
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false ); // required as godaddy fails
					
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
					
					$output = curl_exec ( $ch );
					
					// echo "output".$output;
					}
?>