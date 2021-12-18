<?php
ob_start();
 error_reporting(0);
 session_start(); 

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s'); 
 
include('send_text_message.php');
include('send_mail_function.php');

include('JIO_API/send_patient_status.php');

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
ob_start();


if(isset($_POST['cmdResponse'])){
	
	//PROVEDER1 INTERACTION

					
					$txtProNote1= addslashes($_POST['example']);
					$resp_cond=$_POST['patient_response_send'];
					
					if(!empty($txtProNote1)){
					
					$arrFields_chat[]= 'patient_id';
					$arrValues_chat[]= $_POST['patid'];
					$arrFields_chat[]= 'ref_id';
					$arrValues_chat[]= $_POST['docid'];
					$arrFields_chat[]= 'chat_note';
					$arrValues_chat[]= $txtProNote1;
					$arrFields_chat[]= 'user_id';
					$arrValues_chat[]= $_POST['userid'];
					$arrFields_chat[]= 'TImestamp';
					$arrValues_chat[]= $Cur_Date;
					
					$patientNote=$objQuery->mysqlInsert('chat_notification',$arrFields_chat,$arrValues_chat);
			
					//Change Status2 condition
					$getPatInfo= $objQuery->mysqlSelect("*","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id inner join hosp_tab as e on e.hosp_id=d.hosp_id","b.patient_id='".$_POST['patid']."'and b.ref_id='".$_POST['docid']."'","","","","");
					$getStatus2=$getPatInfo[0]['status2']; //Get present patient status of perticular referral
					$getBucket=$getPatInfo[0]['bucket_status']; //Get present patient status of perticular referral
					if($getStatus2<5){  //Status2 will change only when present status remains in below respond level, ie. it must be in 'New'/Refered/P-Awating Status
						
						$getRef = $objQuery->mysqlSelect("*","referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$_POST['docid']."'","","","","");
	
						//NO. OF RESPONDED COUNT INCREMENTED BY ONE
						$TotCount=$getRef[0]['Tot_responded'];
						$TotCount=$TotCount+1;
						
						$arrFields3[]= 'Tot_responded';
						$arrValues3[]= $TotCount;
						$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$_POST['docid']."'");
						
						//Update response time 
						//RETREIVE DOCTOR'S FIRST REFERRED DATE
						$getDocResponse = $objQuery->mysqlSelect("TImestamp as Chat_Date,chat_note as Chat_Note","chat_notification","patient_id='".$_POST['patid']."'and ref_id='".$_POST['docid']."'","","","","");
													
						$datetime1 = new DateTime($getDocResponse[0]['Chat_Date']);
						$datetime2 = new DateTime($Cur_Date);
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
						$updateBucket=$objQuery->mysqlUpdate('patient_referal',$arrFields2,$arrValues2,"patient_id='".$_POST['patid']."'");
					
						}
					}
					
					/*$arrFields1[]= 'bucket_status';
					$arrValues1[]= $_POST['Pro4_status2'];*/
					$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patid']."'and ref_id='".$_POST['docid']."'");
					
					
					//Email Notification to patient
					//$getChatMsg = $objQuery->mysqlSelect("*","chat_notification","patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'","chat_id desc","","","");
					$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$_POST['docid']."'","","","","");
	
					if(!empty($getPatInfo[0]['doc_photo'])){
						$docimg=HOST_MAIN_URL."Doc/".$getPatInfo[0]['ref_id']."/".$getPatInfo[0]['doc_photo'];
					}	
					else{
						$docimg=HOST_MAIN_URL."images/doc_icon.jpg";
					}
					
					
										
					//Check Doctor response should go to partner / patient directly
			if($_POST['patient_response_send']==1){ // 1 for response should go to patient with a copy to partner & Point of contact(Marketing Person)
					$mailto .=$getPatInfo[0]['patient_email'] .", ";
					$ccmail="medical@medisense.me";
					$patientnum =$getPatInfo[0]['patient_mob'];
					
					//Jio Patient Status Update
					$chkMember = $objQuery->mysqlSelect("medAuthToken","login_user","login_id='".$getPatInfo[0]['login_user_id']."'","","","","");
			
					if(!empty($getPatInfo[0]['external_orderid']))
					{
					
					$statuscode ="S103";
					$authToken =$chkMember[0]['medAuthToken'];
					$healthHubId = $getPatInfo[0]['external_hubid'];
					$healthHubOrderId = $getPatInfo[0]['external_orderid'];
					$mobileNum = $getPatInfo[0]['patient_mob'];
					$description = strip_tags($txtProNote1);
					$status ="Responded";
					$docName = $getSpec[0]['ref_name'];
					$transactionID = $getPatInfo[0]['transaction_id'];
					$paymentID = "";				
					send_jio_status($statuscode,$authToken,$healthHubId,$healthHubOrderId,$mobileNum,$description,$status,$docName,$transactionID,$paymentID);	
					}
			}
		
					
					$getDocName=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_name']));
					$getDocSpec=urlencode(str_replace(' ','-',$getSpec[0]['spec_name']));
					$getDocCity=urlencode(str_replace(' ','-',$getPatInfo[0]['ref_address']));
					$getDocState=urlencode(str_replace(' ','-',$getPatInfo[0]['doc_state']));
					$getDocHosp=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_name']));
					$getDocHospAdd=urlencode(str_replace(' ','-',$getPatInfo[0]['hosp_addrs']));

					$Link=HOST_HEALTH_URL.'Panel-Of-Doctors/'.$getDocName.'-'.$getDocSpec.'-'.$getDocHosp.'-'.$getDocCity.'-'.$getDocState.'/'.md5($getPatInfo[0]['ref_id']);
					$randid=time();
					/*$doctorresponse='';
						foreach($getChatMsg as $key=>$value){
						
						$doctorresponse .="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$value['chat_note']."<br><span style='float:right;color:#6b6b6b'>".date('d M Y h:i',strtotime($value['TImestamp']))."</span></p></td></tr>";
						} */
					$doctorresponse ="<tr ><td style='background:#fcfce1;border-bottom:1pt dotted #ffcc00; font-family:Tahoma; border-top:1pt solid #ffcc00;  text-align:left'><p style='padding:15px !important;font-style:italic;'>".$txtProNote1."<br><br><span style='font-size:13px;font-weight:normal;'>If you wish to meet this doctor in person, <a href='".HOST_MAIN_URL."Email_Response/response.php?randid=".$randid."&patid=".$getPatInfo[0]['patient_id']."&docid=".$getPatInfo[0]['ref_id']."&eventtype=4' target='_blank'>click here.</a></span><br><span style='float:right;color:#6b6b6b'>".date('d M Y H:i',strtotime($Cur_Date))."</span></p></td></tr>";
							
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
	$success="Response added successfully";
	
			

	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
        <title></title>
       <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=k4bskbhavbopqhldtcaifwjar0xo7yxzkbb902mi84dto3rj"></script>

  <script>tinymce.init({ selector:'textarea',
 theme: 'modern',
    plugins: [
      'advlist autolink link image lists charmap hr anchor pagebreak spellchecker',
      'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
      'save table contextmenu directionality template paste'
    ],
    toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | forecolor backcolor emoticons'
    });</script>
<link type="text/css" rel="stylesheet" href="bootstrap.min.css">


    </head>
    <body>

	<form method="post" >
	<input type="hidden" name="patid" value="<?php echo $_GET['patid']; ?>" />
	<input type="hidden" name="docid" value="<?php echo $_GET['docid']; ?>" />
	<input type="hidden" name="userid" value="<?php echo $_GET['userid']; ?>" />
        <div class="page-wrapper box-content">
		<?php if(isset($success)){
			echo "<font color=green>".$success."</font>";
		} ?>
            <textarea name="example" rows="14" style="width:99%;"></textarea>
			<div class="modal-footer">	
			Would you like to send this response to patient? <input type="checkbox" name="patient_response_send" value="1" /> Yes
				<button type="submit" name="cmdResponse" class="btn btn-danger" data-dismiss="modal">SUBMIT</button>
			</div>
		</div>

       
	</form>
    </body>
</html>
