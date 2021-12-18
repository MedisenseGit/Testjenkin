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
$Cur_Date=date('Y-m-d H:i:s');
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
	

if(isset($_POST['cmdTeleOp'])){
	
	$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$_POST['check_time']."'","","","","");

	if($chkEvntStatus==false && $eventtype==1) //DIRECT MEET THE DOCTOR
	{
		$arrFields_patevent[]= 'eventtype';
		$arrValues_patevent[]= $eventtype;
		$arrFields_patevent[]= 'patient_id';
		$arrValues_patevent[]= $_POST['patid'];
		$arrFields_patevent[]= 'random_id';
		$arrValues_patevent[]= $randid;
		$arrFields_patevent[]= 'TImestamp';
		$arrValues_patevent[]= $Cur_Date;
		
		$patientNote=$objQuery->mysqlInsert('patient_email_event',$arrFields_patevent,$arrValues_patevent);
		
	//WHEN USER HAS PRESS "TALK TO DOCTOR" DOCTOR STATUS WILL MAKE IT "STAGED"
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[]= 'status1';
			$arrValues1[]= '1';
			$arrFields1[]= 'status2';
			$arrValues1[]= '7';
			$arrFields1[]= 'conversion_status';
			$arrValues1[]= '1'; //1 for call desired
			
			
			$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$_POST['patid']."'and ref_id='".$_POST['docid']."'");
			
			$arrFields3 = array();
			$arrValues3 = array();
			$arrFields3[]= 'bucket_status';
			$arrValues3[]= '7';
			$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields3,$arrValues3,"patient_id='".$_POST['patid']."'");
				
		
		
		
	$mednote="Patient has expressed interest to talk to the doctor on  -".$_POST['check_date']."/".$getTiming[0]['Timing']; //MEDISENSE NOTE
	$arrFields2 = array();
	$arrValues2 = array();
	$arrFields2[] = 'patient_id';
	$arrValues2[] = $_POST['patid'];
	$arrFields2[] = 'ref_id';
	$arrValues2[] = $_POST['docid'];
	$arrFields2[] = 'chat_note';
	$arrValues2[] = $mednote;
	$arrFields2[] = 'user_id';
	$arrValues2[] = '10';
	$arrFields2[] = 'status_id';
	$arrValues2[] = '7';
	$arrFields2[] = 'TImestamp';
	$arrValues2[] = $Cur_Date;
				
	$docchat=$objQuery->mysqlInsert('chat_notification',$arrFields2,$arrValues2);
	
		
		//Patient Info EMAIL notification Sent to Doctor
		if(!empty($docmail)){
		$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
		$prefDateTime="<b>Prefered date & time:</b> ".$_POST['check_date']." / ".$getTiming[0]['Timing']."<br>";			
        $patContact= $_POST['contact_num']." / ". $_POST['alternate_num'];         
				   $url_page = 'pat_contact_info.php';
					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($patContact);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
					$url .= "&prefTime=" . urlencode($prefDateTime);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
							
					send_mail($url);
					
		}

					
					
					//SMS notification to Doctor
                    $msg = "Dear Sir ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to speak with you. We have also sent your contact details. Thx";
					if(!empty($docnum)){
					send_msg($docnum,$msg);
					
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$msg);
					
					}
		
		
	header('Location:Respone-note?response=1');
	}
	if($chkEvntStatus==true && $_GET['eventtype']==1) //SEND ERROR NOTE
	{
	header('Location:Respone-note?response=11');
	}
}



if(isset($_POST['cmdCancel'])){
	header('Location:https://medisensehealth.com');
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
   <link href="assets/css/checkbox.css" rel="stylesheet">


  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#ap").click(function () {
            if ($(this).is(":checked")) {
                $("#get-val").show();
            } else {
                $("#get-val").hide();
            }
        });
    });
</script>  	
	
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
							<div class="row">
							<div class="col-xs-9" style="margin-left:20px;"> 
												<div class="checkbox-btn">
														<input type="checkbox" name="ap" id="ap" value="1" style="float:right;"/>   
														<label for="ap">I expressly state that I would like to receive a call from Hospital or Doctor's team to discuss about my case
														</label>
													
												 </div>
							 
							 
							 </div>
							 </div>
							 

							 
							 <form id="get-val" style="display:none;" method="post" name="frmConfirm" id="frmConfirm">
							 <input type="hidden" name="patid" value="<?php echo $patid; ?>" />
							<input type="hidden" name="docid" value="<?php echo $docid; ?>" />
							<div class="form-body " style="padding-bottom:20px !important;">
							<br>
							<div class="row">
									<div class="col-xs-3">
								<label><h4 style="color:#f68c34; font-weight:bold; padding-left:20px;">You will be receiving call on following number </h4>
								</label>
								</div>
							
								<div class="col-xs-4">
								  <div class="form-group">
									 
														<label class="input">
																<input type="text" name="contact_num" id="contact_num" value="<?php echo $chkPatDet[0]['patient_mob']; ?>" >
															</label>
									</div>
								</div>
							
							</div>
							<div class="row">
								<div class="col-xs-3">
							<label><h4 style="color:#f68c34; font-weight:bold; padding-left:20px;">Alternate number -</h4>
							</label>
							</div>
							
							<div class="col-xs-4">
                              <div class="form-group">
                                 
													<label class="input">
                                                            <input type="text" name="alternate_num" id="alternate_num" placeholder="Alternate mobile no.">
                                                        </label>
								</div>
							</div>
							<div class="col-xs-2">
						   <div  >
                              <ul class="panel-btn" >
								<li>
								<button type="submit" name="cmdTeleOp" class="btn-new btn-3 btn-3a">SUBMIT</button>
								
								</li>

							   </ul>
							</div>
						   </div>
							</div>
							
							<div class="row">
							<div class="col-xs-3">
							<label><h4 style="color:#f68c34; font-weight:bold; padding-left:20px;">Preferred Date & time -</h4>
							</label>
							</div>
							
							<div class="col-xs-2">
                              <div class="form-group">
                                
                                 <label class="select">
						   <select name="check_date" id="check_date" required="required" >
                                     <option value="">Select Date</option>
                                     <?php
										 
										for($i=0; $i<=2; $i++) { ?>
                                        
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
                                                                                                          
                                    <label class="select">
									<i class="icon-append fa fa-clock-o"></i>
									<select name="check_time" id="check_time" required="required" >
										<option value="">Select Timing</option>
										<?php
											$Timing= $objQuery->mysqlSelect("*","timings","","","","","1,10");
											
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
						   
						   <div class="col-xs-2">
						   <div>
                              <ul class="panel-btn" >
								<li>
								<button type="submit" name="cmdCancel" class="btn-new btn-3 btn-3a">CANCEL</button>
								 
								</li>

							   </ul>
							</div>
						   </div>
						   
						   </div>
						   </div>
						  
						   </section>
						   </fieldset>
           <!-- <p><b> Note: Your appointment will be confirmed within few hours. Hospital/Doctor's team shall contact you and confirm the appointment.</b></p>-->
						   </div>
		</div>
		 </form>
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