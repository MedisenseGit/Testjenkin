<?php
error_reporting(0);
session_start();

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("../PaytmKit/lib/config_paytm.php");
require_once("../PaytmKit/lib/encdec_paytm.php");

require_once("../classes/querymaker.class.php");
include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
$objQuery = new CLSQueryMaker();
$ccmail="medical@medisense.me";


$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	$chkcust = $objQuery->mysqlSelect("*","customer_transaction","transaction_id='".$_POST["ORDERID"]."'and amount='".$_POST["TXNAMOUNT"]."'","","","","");
               
        //Update Customer Transaction ID
        if ($_POST["STATUS"] == "TXN_SUCCESS" && $chkcust==true) {
		$arrFields = array();
		$arrValues = array();
		
	
		$arrFields[] = 'Payment_id';
		$arrValues[] = $_POST["TXNID"];
		$arrFields[] = 'transaction_time';
		$arrValues[] = $_POST["TXNDATE"];
		$editPatient=$objQuery->mysqlUpdate('customer_transaction',$arrFields,$arrValues,"transaction_id='".$_POST["ORDERID"]."'");
		$successmsg="Transaction has been completed successfully..";
		//Process your transaction here as success transaction.
		//Verify amount & order id received from Payment gateway with your application's order id and amount.
	}
	else {
		
		$errormsg="Transaction Failure..<br>".$_POST["RESPMSG"];
	}

	if (isset($_POST) && count($_POST)>0 && $_POST["STATUS"] == "TXN_SUCCESS" )
	{ 
		
			
			//WHEN USER HAS PRESS "TALK TO DOCTOR" DOCTOR STATUS WILL MAKE IT "STAGED"
			$arrFields1 = array();
			$arrValues1 = array();
			
			$arrFields1[]= 'status1';
			$arrValues1[]= '1';
			$arrFields1[]= 'status2';
			$arrValues1[]= '7';
			$arrFields1[]= 'conversion_status';
			$arrValues1[]= '1'; //1 for call desired
			
			
			$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields1,$arrValues1,"patient_id='".$chkcust[0]['patient_id']."'and ref_id='".$chkcust[0]['ref_id']."'");
			
			$arrFields3 = array();
			$arrValues3 = array();
			$arrFields3[]= 'bucket_status';
			$arrValues3[]= '7';
			$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields3,$arrValues3,"patient_id='".$chkcust[0]['patient_id']."'");
			


		//Get Patient Details
                $chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$chkcust[0]['patient_id']."'","","","","");

                //Get Doctors Details
               $getDocDet = $objQuery->mysqlSelect('*','referal as a left join doctor_hosp as b on a.ref_id=b.doc_id left join hosp_tab as c on c.hosp_id=b.hosp_id',"a.ref_id='".$chkcust[0]['ref_id']."'");
               //To check whether Doctor belongs Medisense Panel or Hospital 
                if($getDocDet[0]['communication_status']==1){  //If communication_status=1 then Notification will Send to doctor personal No.
                        $docnum=$getDocDet[0]['tele_op_contact'];

                        $docmail .= $getDocDet[0]['ref_mail'] . ', ';
                        $docmail .= $getDocDet[0]['ref_mail1'] . ', ';
                        $docmail .= $getDocDet[0]['ref_mail2'];

                }else if($getDocDet[0]['communication_status']==2){ //If communication_status=2, then Notification will Send to Hospital POint of contact
                        $docnum=$getDocDet[0]['hosp_contact'];

                        $docmail .= $getDocDet[0]['hosp_email'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email1'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email2'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email3'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email4'];

                }
                else if($getDocDet[0]['communication_status']==3){ //If communication_status=3 then Notification will Send to both  Hospital POint of contact as well as Doctor personal No. 
                        $docnum=$getDocDet[0]['tele_op_contact'];
                        $hospnum=$getDocDet[0]['hosp_contact'];

                        $docmail .= $getDocDet[0]['ref_mail'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email1'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email2'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email3'] . ', ';
                        $docmail .= $getDocDet[0]['hosp_email4'];
                }
		
		$trans_id=$_POST["ORDERID"];
		$trans_amount=$_POST["TXNAMOUNT"];
		$trans_date=$_POST["TXNDATE"];
		$trans_mode=$_POST["PAYMENTMODE"];	
		$trans_status=$_POST["STATUS"];	
		$PatId=$chkcust[0]['patient_id'];
		$DocId=$chkcust[0]['ref_id'];
		$service_type=$chkcust[0]['service_type'];
		$Patient_name=$chkcust[0]['patient_name'];
		$Email=$chkcust[0]['email_id'];
		$Mobile=$chkcust[0]['mobile_no'];
		$Doc_name=$_SESSION['doc_name'];
		$Doc_hosp=$_SESSION['dochosp'];
		
		
			
					//Transaction Email Sent to User";
					$url_page = 'talkto_paymentmail.php';					
					$url = rawurlencode($url_page);
					$url .= "?transid=" . urlencode($trans_id);
					$url .= "&transamount=" . urlencode($trans_amount);
					$url .= "&transdate=" . urlencode($trans_date);
					$url .= "&transmode=" . urlencode($trans_mode);
					$url .= "&service=" . urlencode($service_type);
					$url .= "&patname=" . urlencode($Patient_name);
                                        $url .= "&patid=" . urlencode($PatId);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
                                        $url .= "&hospname=" . urlencode($getDocDet[0]['hosp_name']);
                                        $url .= "&docphone=" . urlencode($getDocDet[0]['tele_op_contact']);
					$url .= "&patmob=" . urlencode($Mobile);
					$url .= "&patmail=" . urlencode($Email);
					$url .= "&ccmail=" . urlencode($ccmail);
									
					send_mail($url);
					
		//Transaction Message Sent to Patient";
		if(!empty($Mobile)){
		$msg="Payment Confirmed, Doctor Contact Details : ".$getDocDet[0]['ref_name']." ( ".$getDocDet[0]['tele_op_contact']." ) Transaction Id:". $trans_id .",Service Type: ".$service_type.", Amount: Rs. " . $trans_amount." Many Thanks";
							
		send_msg($Mobile,$msg);
		}
		
		//Patient Info EMAIL notification Sent to Doctor
		if(!empty($docmail)){
		$PatAddress=$chkPatDet[0]['patient_addrs'].",<br>".$chkPatDet[0]['patient_loc'].", ".$chkPatDet[0]['pat_state'].", ".$chkPatDet[0]['pat_country'];
		
					$url_page = 'custom_pat_contact_info.php';
					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($chkPatDet[0]['patient_name']);
					$url .= "&patID=".urlencode($chkPatDet[0]['patient_id']);
					$url .= "&patAddress=".urlencode($PatAddress);
					$url .= "&patContact=".urlencode($chkPatDet[0]['patient_mob']);
					$url .= "&patEmail=".urlencode($chkPatDet[0]['patient_email']);
                                        $url .= "&hospName=".urlencode($getDocDet[0]['hosp_name']);
					$url .= "&patContactName=" . urlencode($chkPatDet[0]['contact_person']);
					$url .= "&docname=" . urlencode($getDocDet[0]['ref_name']);
					$url .= "&docmail=" . urlencode($docmail);
					$url .= "&ccmail=" . urlencode($ccmail);		
					send_mail($url);		
					
		}

		
                         		//SMS notification to Doctor & Hospital
                                        $docmsg = "Dear Sir ".$chkPatDet[0]['patient_name']."( Ph: ".$chkPatDet[0]['patient_mob']." )has expressed interest to speak with you. We have also sent your contact details. Many Thanks";
					        
					if(!empty($docnum)){
					
					send_msg($docnum,$docmsg);
					
					}
					if(!empty($hospnum)){
					send_msg($hospnum,$docmsg);
					
					}
		
		
			
		
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>
<!DOCTYPE html>
<html lang="en">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <meta name="description" content="">
      <meta name="keywords" content="">
		 <title>Medical Second Opinion | Medisense Healthcare Solutions</title>
		 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
		 <meta property="og:title" content="Medisense Health Solutions">
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <!-- Bootstrap  -->
      <link type="text/css" rel="stylesheet" href="new_assets/css/bootstrap.min.css">
	   <!-- Favicons================================================== -->
      <link rel="shortcut icon" href="new_assets/img/favicon.ico">
	  
	 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
<meta property="og:title" content="Medisense Health Solutions">
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">

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
	  		
			

  <link href="new_assets/css/icon.css" rel="stylesheet">
  <link href="new_assets/css/bootstrap-wysihtml5.css" rel="stylesheet">
  		      <link href="new_assets/css/editor.css" rel="stylesheet">
  		   <link href="new_assets/css/subpage.css" rel="stylesheet">
  
  
	  
	 


 
		
		<script type="text/javascript" src="like_assets/js/jquery.min.js"></script>
		<script type="text/javascript" src="like_assets/js/function.js"></script>
		<script type="text/javascript" src="like_assets/js/comment_function.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
			  <script src="new_assets/js/bootstrap.min.js"></script>
			   <a href="#" class="scrollup" style="display: none;">Scroll</a> 
		<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-72680026-1', 'auto');
  ga('send', 'pageview');

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
<span><a href="https://medisensehealth.com/" target="_blank"> 
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
     





            <div class="row  ">

                <div class="col-md-12  ">
                   <h3 class="black ">PAYMENT TRANSACTION</h3>

                </div>
				
				
            </div>
<hr class="blue">
<div class="grad">
	<div class="container ">
	<div class="panel padd-40"  style="min-height:400px">
<div class="row">
<div class="col-md-12">
<div class="timeline-main">


<div class="hr-line"></div>
<div class="content-ser">

<br>


    <?php if(isset($errormsg)){ ?>
					<h3 class="featurette-heading" style="margin-bottom:20px;"><?php  echo $errormsg; ?><br></h3>
					<hr>
				<?php } ?>


<?php if(isset($successmsg)){ ?>
	<h3 class="featurette-heading" style="margin-bottom:20px;"><strong><?php  echo $successmsg; ?><br></strong>
	<hr>
	</h3>
	<form method="post" action="PaytmKit/pgRedirect.php">
				<div class="col-md-12">
									<h4>Patient Info</h4>
									 
									<p><b> Patient Name: &nbsp;&nbsp;</b> <?php echo $Patient_name; ?><br />
                                   <b> Mobile Number: &nbsp;&nbsp;</b> <?php echo $Mobile; ?><br /> 
									<b> Email: &nbsp;&nbsp;</b> <?php echo $Email; ?><br />
										
                                      <b>Service Type: &nbsp;&nbsp;</b> <?php echo $service_type; ?>
                                   <br />
									  
                                   <br /><br />
									</p>
   			 	</div>
				<div class="col-md-4">
                                    <div class="payment-success">
                                    <h4>Transaction Info</h4>
									<p><b> Transaction Date:</b> <?php echo $trans_date; ?> <br />
									<b> Transaction id:</b> <?php echo $trans_id; ?> <br />
                                   <b> Transaction Mode:</b> <?php echo $trans_mode; ?> <br />
                                   <b>Total Amount: </b><?php echo $trans_amount; ?>Rs.<br />
                                   </p>
                                     </div>
                                    </div>
                                   
  
                                    <div class="col-md-4">
                                    <div class="payment-success">
                                    <h4>Doctor Info</h4>
                                 
                                <p><b> Doctor Name:</b> <?php echo $Doc_name; ?> <br />
								<b> Doctor Address:</b> <?php echo $Doc_hosp; ?> <br />
                              </p>
							
                                     </div>
                                    </div>
                                    
                                  <div style="clear:both;"></div>
                         <?php } ?>           
     
	
<br /><br />
 


	</div>
	</div>

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
                <br> Copyrights © 2017 Medisense Healthcare Solutions
                <br>

            </div>
        </div>
    </footer>
    <!-- bottom bar-->           
       
	  </body>
	  </html>
	  