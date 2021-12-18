<?php ob_start();
 error_reporting(0);
 session_start();

require_once("../classes/querymaker.class.php");
include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
	$docid=$_SESSION['docid'];
	$patid=$_SESSION['patid'];
	$eventtype=$_SESSION['eventtype'];
	$randid=$_SESSION['randid'];

$chkEvntStatus = $objQuery->mysqlSelect("*","patient_email_event","eventtype='".$eventtype."' and patient_id='".$patid."' and random_id='".$randid."'","","","","");
$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'","","","","");
$getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$docid."'");
//$getSpec = $objQuery->mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$docid."'","","","","");
$chkPatReferal = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$patid."' and ref_id='".$docid."'","","","","");
//To check whether Doctor belongs Medisense Panel or Hospital 
	if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
		$docnum=$getDocDet[0]['contact_num'];
		
		$docmail .= $getDocDet[0]['ref_mail'];
		
	}else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
		$docnum=$getDocDet[0]['hosp_contact'];
		
		$docmail .= $getDocDet[0]['hosp_email'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email1'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email2'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email3'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email4'];
		
	}
	else if($getDocDet[0]['communication_status']==3){ //If communication_status=3 then Notification will Send to both  Hospital POint of contact as well as Doctor personal No. 
		$docnum=$getDocDet[0]['contact_num'];
		$hospnum=$getDocDet[0]['hosp_contact'];
		
		$docmail .= $getDocDet[0]['ref_mail'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email1'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email2'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email3'] . ', ';
		$docmail .= $getDocDet[0]['hosp_email4'];
	}	
	

if(isset($_POST['cmdMakeAp'])){
	
	$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$_POST['check_time']."'","","","","");

	if($chkEvntStatus==false && $eventtype==4) //DIRECT MEET THE DOCTOR
	{
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'eventtype';
	$arrValues[]= $eventtype;
	$arrFields[]= 'patient_id';
	$arrValues[]= $patid;
	$arrFields[]= 'random_id';
	$arrValues[]= $randid;
	$arrFields[]= 'TImestamp';
	$arrValues[]= $Cur_Date;
	
	$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields,$arrValues);
	//CHECK WHETHER PATIENT IS ALREADY REFERED OR NOT
	if($chkPatReferal==true){
		$arrFields1[]= 'status1';
		$arrValues1[]= '1';
		$arrFields1[]= 'status2';
		$arrValues1[]= '8';
		$arrFields1[]= 'bucket_status';
		$arrValues1[]= '8';
		
		$updatereferer=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$patid."' and ref_id='".$docid."'");
	} else
	{
		$arrFields1[]= 'ref_id';
		$arrValues1[]= $docid;
		$arrFields1[]= 'patient_id';
		$arrValues1[]= $patid;
		$arrFields1[]= 'status1';
		$arrValues1[]= '1';
		$arrFields1[]= 'status2';
		$arrValues1[]= '8';
		$arrFields1[]= 'bucket_status';
		$arrValues1[]= '8';
		$arrFields1[]= 'timestamp';
		$arrValues1[]= $Cur_Date;
		$insertRefer=$objQuery->mysqlInsert('patient_referal',$arrFields1,$arrValues1);
		
		//REFER COUNT INCREMENTED BY ONE
		$getNumRef=$getDocDet[0]['Total_Referred'];
		$getNumRef=$getNumRef+1;
		$arrFields3 = array();
		$arrValues3 = array();
		$arrFields3[]= 'Total_Referred';
		$arrValues3[]= $getNumRef;
				
		$updateCount=$objQuery->mysqlUpdate('referal',$arrFields3,$arrValues3,"ref_id='".$docid."'");
		
		
	}
	
	//Insert to appointment table
	
				if($chkPatDet[0]['patient_gen']=="1"){
					$gender="Male";
				}else{
					$gender="Female";
				}
				$trans_id=time();
				$arrFields_app=array();
				$arrValues_app=array();
				
				$arrFields_app[] = 'Transaction_id';
				$arrValues_app[] = $trans_id;
				$arrFields_app[] = 'pat_name';
				$arrValues_app[] = $chkPatDet[0]['patient_name'];
				$arrFields_app[] = 'Email_id';
				$arrValues_app[] = $chkPatDet[0]['patient_email'];
				$arrFields_app[] = 'Mobile_number';
				$arrValues_app[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app[] = 'pat_age';
				$arrValues_app[] = $chkPatDet[0]['patient_age'];
				$arrFields_app[] = 'pat_gen';
				$arrValues_app[] = $gender;
				$arrFields_app[] = 'City';
				$arrValues_app[] = $chkPatDet[0]['patient_loc'];
				$arrFields_app[] = 'State';
				$arrValues_app[] = $chkPatDet[0]['pat_state'];
				$arrFields_app[] = 'Country';
				$arrValues_app[] = $chkPatDet[0]['pat_country'];
				$arrFields_app[] = 'Address';
				$arrValues_app[] = $chkPatDet[0]['patient_addrs'];
		
				$craetevisitor=$objQuery->mysqlInsert('new_hospvisitor_details',$arrFields_app,$arrValues_app);
				$newvisitorid= mysql_insert_id();
				
							
				$arrFields_app1[] = 'appoint_trans_id';
				$arrValues_app1[] = $trans_id;
				$arrFields_app1[] = 'pref_doc';
				$arrValues_app1[] = $docid;
				$arrFields_app1[] = 'Visiting_date';
				$arrValues_app1[] = date('Y-m-d',strtotime($_POST['check_date']));
				$arrFields_app1[] = 'Visiting_time';
				$arrValues_app1[] = $getTiming[0]['Timing_id'];
				$arrFields_app1[] = 'patient_name';
				$arrValues_app1[] = $chkPatDet[0]['patient_name'];
				$arrFields_app1[] = 'Mobile_no';
				$arrValues_app1[] = $chkPatDet[0]['patient_mob'];
				$arrFields_app1[] = 'Email_address';
				$arrValues_app1[] = $chkPatDet[0]['patient_email'];
				$arrFields_app1[] = 'pay_status';
				$arrValues_app1[] = "Pending";
				$arrFields_app1[] = 'visit_status';
				$arrValues_app1[] = "new_visit";
				$arrFields_app1[] = 'Time_stamp';
				$arrValues_app1[] = $Cur_Date;
				$arrFields_app1[] = 'department';
				$arrValues_app1[] = $_POST['department'];
				
				
				$createappointment=$objQuery->mysqlInsert('appointment_transaction_detail',$arrFields_app1,$arrValues_app1);
			
			//Insert records into doctors personal patient table 
	
			$arrFields_myPatient[] = 'patient_name';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_name'];

			$arrFields_myPatient[] = 'patient_age';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_age'];

			$arrFields_myPatient[] = 'patient_email';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_email'];

			$arrFields_myPatient[] = 'patient_gen';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_gen'];

			$arrFields_myPatient[] = 'hyper_cond';
			$arrValues_myPatient[] = $chkPatDet[0]['hyper_cond'];

			$arrFields_myPatient[] = 'diabetes_cond';
			$arrValues_myPatient[] = $chkPatDet[0]['diabetes_cond'];

			$arrFields_myPatient[] = 'contact_person';
			$arrValues_myPatient[] = $chkPatDet[0]['contact_person'];

			/*profession*/

			$arrFields_myPatient[] = 'patient_mob';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_mob'];

			$arrFields_myPatient[] = 'patient_loc';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_loc'];

			$arrFields_myPatient[] = 'pat_state';
			$arrValues_myPatient[] = $chkPatDet[0]['pat_state'];

			$arrFields_myPatient[] = 'pat_country';
			$arrValues_myPatient[] = $chkPatDet[0]['pat_country'];

			$arrFields_myPatient[] = 'patient_addrs';
			$arrValues_myPatient[] = $chkPatDet[0]['patient_addrs'];

			$arrFields_myPatient[] = 'doc_id';
			$arrValues_myPatient[] = $docid;

			$arrFields_myPatient[] = 'system_date';
			$arrValues_myPatient[] = date('Y-m-d',strtotime($Cur_Date));
			
			$arrFields_myPatient[] = 'TImestamp';
			$arrValues_myPatient[] = $Cur_Date;	
			
			$arrFields_myPatient[] = 'transaction_id';
			$arrValues_myPatient[] = $trans_id;
			$userPersonal=$objQuery->mysqlInsert('doc_my_patient',$arrFields_myPatient,$arrValues_myPatient);
	
	$mednote=$chkPatDet[0]['patient_name']." want to take an appointment from ".$getDocDet[0]['ref_name']." -".$Cur_Date; //MEDISENSE NOTE
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $patid;
	$arrFields2[] = 'ref_id';
	$arrValues2[] = $docid;
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $mednote;
	$arrFields2[] = 'user_id';
	$arrValues2[] = '10';
	$arrFields2[] = 'status_id';
	$arrValues2[] = '8';
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
				
	$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	
	
		
	//Patient Info EMAIL notification Sent to Doctor
		if(!empty($docmail)){
		$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
		
					$url_page = 'Doc_pat_info.php';					
					$url .= rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&prefDate=".urlencode($_POST['check_date']);
					$url .= "&prefTime=".urlencode($getTiming[0]['Timing']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
					$url .= "&patContact=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);
					
		}
					
					//SMS notification to Doctor
					$msg = "Dear Doctor ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to meet you in person. We have also sent your appointment link. Thanks";
					
					if(!empty($docnum)){
					send_msg($docnum,$msg);
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$msg);
					}
		
		
	header('Location:Respone-note?response=4');
	}
	if($chkEvntStatus==true && $_GET['eventtype']==4) //SEND ERROR NOTE
	{
	header('Location:Respone-note?response=44');
	}
}



if(isset($_POST['cmdCancel'])){
	header('Location:cancel_appointment.php');
}

$getDocInfo= $objQuery->mysqlSelect("*","referal","ref_id='".$docid."'","","","","");
$getDocSpec= $objQuery->mysqlSelect("*","specialization","spec_id='".$getDocInfo[0]['doc_spec']."'","","","","");
$getDocAddress= $objQuery->mysqlSelect("*","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","a.doc_id='".$docid."'","","","","");	

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Medisense-Healthcare Solutions</title>

    <!-- Mobile Specific Metas================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap  -->
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="assets/owl-carousel/owl.carousel.css">
    <link type="text/css" rel="stylesheet" href="assets/css/style.css">
    <!-- Favicons================================================== -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <!-- Font awesome icons================================================== -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
   

    	
	
</head>

<body>


<!-- top header -->
    <header class="header-main1">
  
  <!-- Bottom Bar -->
<div class="top_info_boxes1">
						<div class="container">
							<div class="row">
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
<div class="top_info_box">							
<span><a href="Home"> 
<img src="assets/img/medisense_og.png" alt="Medisense-Healthcare Solutions"> </a></span>							
</div>
</div>
							
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
<div class="top_info_box">
<div class="icon">
<i class="fa fa-phone"></i>
</div>
<div class="text">
	<strong>Call Today 0091 7026 646022</strong>
<span>Give a Missed Call to 1800 3000 5206</span>
</div>
</div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

<div class="top_info_box">

<div class="icon">
<i class="fa fa-clock-o"></i>
</div>
<div class="text">

<strong>Open Hours</strong>

<span>Mon - Sat: 9 am - 6 pm, Sunday: FREE DAY</span>

</div>
</div>
</div>

			  </div>
						</div>
					</div>
					</header>
  
  
    <hr class="blue">
	 <div class="container ">
	<div class="medisene-form">
							<fieldset class="no-padding">
							 <section class="sub-heading">
							 <form method="post" name="frmConfirm" id="frmConfirm" action="">
							 <input type="hidden" name="department" value="<?php echo $getDocDet[0]['doc_spec']; ?>" />
							<div class="form-body " style="padding-bottom:20px !important;">
							<div class="row">
							<div class="col-xs-3">
							<label><h4 style="color:#f68c34; font-weight:bold; padding-left:20px;">Are you sure want to confirm this appointment?</h4>
							</label>
							</div>
							
							<div class="col-xs-2">
                              <div class="form-group">
                                 <label class="label">Preferred Date</label>
                                 <label class="select">
						   <select name="check_date" id="check_date" >
                                     <option value="">Select Date</option>
                                     <?php
										 
										for($i=0; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= $objQuery->mysqlSelect("DISTINCT(day_id) as DayId","seven_days","","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
																 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name']){ ?>
                                     <option value="<?php echo date('D d-m-Y', $date);?>" >
                                         
                                         <?php
                                            echo date('D d-m-Y', $date);
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
										 
								
									 ?>
                                     </select>
									 <i></i>
									 </label>
								</div>
							</div>
                           <div class="col-xs-2">
                              <div class="form-group">
                                 <div class="form-group">
                                    <label class="label">Preferred Time</label>
                                                                      
                                    <label class="select">
									<i class="icon-append fa fa-clock-o"></i>
									<select name="check_time" id="check_time" >
										<option value="">Select Timing</option>
										<?php
											$Timing= $objQuery->mysqlSelect("*","timings","","","","","");
											foreach($Timing as $TimeList) {
												
										?>
											<option value="<?php echo $TimeList["Timing_id"]; ?>"><?php echo $TimeList["Timing"]; ?></option>
										<?php
											}
									
										?>
									</select>
									
									</label>
                                 </div>
                              </div>
                           </div>
						   <div class="col-xs-4">
						   <div  >
                              <ul class="panel-btn" >
								<li>
								<button type="submit" name="cmdMakeAp" class="btn-new btn-3 btn-3a">Request Appointment</button>
								 </form>
								<form method="post"><button type="submit" name="cmdCancel" class="btn-new btn-3 btn-3a">Cancel Appointment</button></form></li>

							   </ul>
							</div>
						   </div>
						   
						   
						   </div>
						   </div>
						  
						   </section>
						   </fieldset>
            <p><b> Note: Your appointment will be confirmed within few hours. Hospital/Doctor's team shall contact you and confirm the appointment.<br>
Details regarding the consultation fee will be provided by the hospital team</b><br><br>
If you don't hear back from the Hospital/Doctor's team, then please contact us at <br>
Email: medical@medisense.me<br>
Phone: +91-7026 646022 / 1800 3000 5206
</p>
						   </div>
		</div>
     
        <div class="container ">
     





            <div class="row  ">

                <div class="col-md-12  ">
                   <h3 class="black ">DOCTOR/HOSPITAL PROFILE</h2>

                </div>
				
				
            </div>
<hr class="blue">
<div class="grad">
	<div class="container ">
	<div class="panel padd-40"  style="min-height:400px">
<div class="row">
<div class="col-md-10 col-sm-10 pull-right">
 <div class="padd-left-10">

  <h3 class="panel-name black"><?php if($getDocInfo[0]['anonymous_status']==1) { echo "Anonymous";} else { echo $getDocInfo[0]['ref_name']; } ?></h3>
	 <ul class="single-list">
 <li  class="panel-exp"><?php echo $getDocInfo[0]['doc_qual']; ?></li>
 <li><?php if(!empty($getDocInfo[0]['ref_exp'])){ ?><li><strong>Experience :</strong><?php echo $getDocInfo[0]['ref_exp']; ?> Years</li><?php } ?></li>
 <li><?php if(!empty($getDocSpec[0]['spec_name'])){ ?><li><strong>Specialization  :</strong><?php echo $getDocSpec[0]['spec_name']; ?></li><?php } ?></li>
 </ul>

 <ul class="single-list">
 
 <?php if(!empty($getDocAddress) && $getDocInfo[0]['anonymous_status']==0 ){ ?><li><strong>Address :</strong><?php echo $getDocAddress[0]['hosp_name']; ?><br><?php if(!empty($getDocAddress[0]['hosp_addrs'])){ echo $getDocAddress[0]['hosp_addrs'];?><br><?php } ?><?php echo $getDocAddress[0]['hosp_city']; ?>,<?php echo $getDocAddress[0]['hosp_state']; ?></li><?php } ?>
  <?php if(!empty($getDocInfo[0]['doc_interest'])){ ?><li><strong>Area's of Interest/Expertise :</strong><?php echo $getDocInfo[0]['doc_interest']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_contribute'])){ ?><li><strong>Professional Contribution :</strong><?php echo $getDocInfo[0]['doc_contribute']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_research'])){ ?><li><strong>Research Details :</strong><?php echo $getDocInfo[0]['doc_research']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_pub'])){ ?><li><strong>Awards / Publications :</strong><?php echo $getDocInfo[0]['doc_pub']; ?></li><?php } ?>
 
 </ul>
 	 
</div>
</div>
<div class="col-md-2 col-sm-2 pull-left  padd-left-10">
<?php if(empty($getDocInfo[0]['doc_photo']) || $getDocInfo[0]['anonymous_status']==1){ ?>
<img src="assets/img/doc_icon.jpg" draggable="false" class="img-responsive single-img">
<?php } else { ?>
<img src="https://medisensecrm.com/Doc/<?php echo $getDocInfo[0]['ref_id']; ?>/<?php echo $getDocInfo[0]['doc_photo']; ?>" alt=""  draggable="false" class="img-responsive single-img">
 <?php } ?>
</div>

</div>
<div class="row">
<div class="col-md-12">
 
 
 </div>

</div>
</div>
</div>
	
	</div>



</div>

        

	</div>
	</div>
	</div>
 
    <footer class="main-footer">
        <div class="copyright">
            <div class="container text-center">
                <a href="https://medisensehealth.com/FAQ">FAQ

</a>&nbsp;|&nbsp;<a href="https://medisensehealth.com/Terms-of-Use">TERMS OF USE

</a>&nbsp;|&nbsp; <a href="https://medisensehealth.com/privacy-policy">PRIVACY POLICY</a> &nbsp;|&nbsp;
                <a href="https://medisensehealth.com/hipaa-compliance">HIPAA COMPLIANCE</a>
                <br> Copyrights © 2016 Medisense Healthcare Solutions
                <br>

            </div>
        </div>
    </footer>
    <!-- bottom bar-->




 <script src="assets/js/jquery-1.10.2.min.js"></script>
  <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
  <script type="text/javascript" src="assets/js/validation.js"></script>
     
   
</body>


</html>