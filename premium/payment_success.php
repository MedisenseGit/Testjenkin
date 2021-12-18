<?php
error_reporting(0);
session_start();

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("../PaytmKit/lib/config_paytm.php");
require_once("../PaytmKit/lib/encdec_paytm.php");
//include('JIO_API/send_patient_status.php');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
include('send_mail_function.php');


$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {

	if(!empty($_POST['ORDERID'])){
	$getBookingTransactionDetails= mysqlSelect("*","payment_transaction","transaction_id='".$_POST["ORDERID"]."'","","","","");
	
	if($_POST['STATUS']=="TXN_SUCCESS" && !empty($_POST['TXNID'])){
		
		//Update Transaction booking table
			$arrFields_trans[] = 'Payment_id';
			$arrValues_trans[] = $_POST['TXNID'];
			$arrFields_trans[] = 'payment_status';
			$arrValues_trans[] = "PAID";
					
			$updateTransaction=mysqlUpdate('payment_transaction',$arrFields_trans,$arrValues_trans,"transaction_id='".$_POST['ORDERID']."'");
			
			
			
			//Update  booking table
			
			/*$arrFields_trans1[] = 'transaction_status';
			$arrValues_trans1[] = $_POST['STATUS'];
					
			$updateTransaction1=mysqlUpdate('bookings',$arrFields_trans1,$arrValues_trans1,"trans_id='".$_POST['ORDERID']."'");*/
			$successmsg="Transaction has been completed successfully.";
			
	}
	else {
		
		$errormsg="Transaction Failure..<br>".$_POST["RESPMSG"];
	}
	}
	

	if ($_POST["STATUS"] == "TXN_SUCCESS" )
	{ 
		
	$getTransactionDetails= mysqlSelect("*","payment_transaction","transaction_id='".$_POST["ORDERID"]."'","","","","");
	//$checkPatient= mysqlSelect("*","doc_my_patient","patient_id='".$getTransactionDetails[0]["patient_id"]."'","","","","");
	
	$checkPatient= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$getTransactionDetails[0]["patient_id"]."'","","","","");
	
	
					
					$trans_id=$_POST["ORDERID"];
					$trans_amount=$_POST["TXNAMOUNT"];
					$trans_date=$_POST["TXNDATE"];
					$trans_mode=$_POST["PAYMENTMODE"];	
					$trans_status=$_POST["STATUS"];	
					//$PatId=$getBookingDetails[0]['id'];
					$BookingId=$checkPatient[0]['id'];
					
					$Name=$checkPatient[0]['patient_name'];
					$Email=$checkPatient[0]['patient_email'];
					$Mobile=$checkPatient[0]['patient_mob'];
					//$Doc_name="Medisense Panel";
					//$Doc_hosp=$_SESSION['dochosp'];
					$ccmail="";
		
			
					
		$ccmail="medisensedev@medisense.me"; 
		$toEmail=$Email.','.$ccmail;
		$mailSubject='Patient Page Payment Details';  
		$fromContent='Medisense-Healthcare';
		$contentSection='Received a message from Patient Page - Your Payment Transaction Details- <br/><br/> Contact Name: '.$Name.'<br/>Email : '.$Email.'  <br/> Mobile number: '.$Mobile.'  <br/> Transaction Id: '.$trans_id.'  <br/> Amount Paid: '.$trans_amount.'  <br/> Transaction Date: '.$trans_date.'<br/> Transaction Mode: '.$trans_mode.'';
		
		
				
						
				$url_page = 'send_medisense_email.php';
				$url .= rawurlencode($url_page);
				$url .= "?contentSection=".urlencode($contentSection);
				$url .= "&toEmail=".urlencode($toEmail);
				$url .= "&mailSubject=".urlencode($mailSubject);
				$url .= "&replyEmail=".urlencode($Email);
				$url .= "&fromContent=".urlencode($fromContent);
				$url .= "&ccmail=" . urlencode($ccmail);
				
						send_mail($url);
						
						
				 //header("Location:".$back_url);			
						
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5M8WWLS');</script>
<!-- End Google Tag Manager -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-M52RB5CJJ7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-M52RB5CJJ7');
</script>
<!-- Meta Tags -->
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="robots" content="index,follow" />
<meta name="author" content="" />

<!-- itemprop meta tags -->
<meta content="" itemprop="name">
<meta content="" itemprop="description">




<!-- Page Title -->
<title>Patient Page - Payment Success</title>


<!-- Favicon and Touch Icons -->
<link href="images/favicon.png" rel="shortcut icon" type="image/png">
<link href="images/apple-touch-icon.png" rel="apple-touch-icon">
<link href="images/apple-touch-icon-72x72.png" rel="apple-touch-icon" sizes="72x72">
<link href="images/apple-touch-icon-114x114.png" rel="apple-touch-icon" sizes="114x114">
<link href="images/apple-touch-icon-144x144.png" rel="apple-touch-icon" sizes="144x144">

<!-- Stylesheet -->
<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

	<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
</head>
<body class="top-navigation">

<div id="wrapper" >
  <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                
                <a href="#" class="navbar-brand"><img alt="image" class="img" src="../assets/img/Practice_premium.png" width="80"/></a>
            </div>
            
        </nav>
        </div> 
  
	 <div class="wrapper wrapper-content">
  <!-- Start main-content -->
   <div class="container">
	<div class="row">
	<div class="col-md-12">	
	<h3 class="text-center text-theme-colored">PAYMENT TRANSACTION</h3>
	<!--<img src="assets/img/medisenselogo.jpg" alt="Medisense-Healthcare Solutions" id="logo"><br>-->
	</div>
	</div>
<div class="row">
<div class="col-md-10 col-md-offset-1">
<div class="timeline-main">


<div class="hr-line"></div>
<div class="contact-box">

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
									<h4>Payment Info</h4>
									 
									<p><b>  Name: &nbsp;&nbsp;</b> <?php echo $Name; ?><br />
                                   <b> Mobile Number: &nbsp;&nbsp;</b> <?php echo $Mobile; ?><br /> 
									<b> Email: &nbsp;&nbsp;</b> <?php echo $Email; ?><br />
										
                                      
                                   <br />
									
									</p>
   			 						</div>
									<div class="col-md-4">
                                    <div class="payment-success">
                                    <h4>Transaction Info</h4>
									<p><b> Transaction Date:</b> <?php echo $trans_date; ?> <br />
									<b> Transaction id:</b> <?php echo $trans_id; ?> <br />
                                   <b> Transaction Mode:</b> <?php echo $trans_mode; ?> <br />
								   <b>Amount: Rs.</b><?php echo $trans_amount; ?><br />
                                   </p>
                                     </div>
                                    </div>
                                   
  
                                    
                                    
                                  <div style="clear:both;"></div>
                         <?php } ?>           
     <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post enctype="multipart/form-data" class="contactForm">
   
    </form>
	<div class="row">
   <!-- <div class="col-md-3"  style="margin-bottom:20px;">
    <a href="Home"><input type="button" name="submit" value="HOME" class="btn btn-theme-colored btn-block" /></a>
     </div>-->
	
	
	<div class="col-md-2" >
	<button type="Button" class="btn btn-theme-colored btn-block" onclick="myFunction()" name="print">PRINT</button>
	</div>
	</div>
 </div>


	</div>
	</div>

</div>

</div>

<script>
function myFunction() {
    window.print();
}
</script>
  
     
          
    
  </div>  
  <!-- end main-content -->
<div class="footer">
            
            <div>
                <strong>Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
            </div>
        </div>
  
</div>
</div>
<!-- end wrapper -->

<!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Flot -->
    <script src="../assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="../assets/js/demo/peity-demo.js"></script>


<!-- JS | Custom script for all pages -->
<!--<script src="js/custom.js"></script>-->

</body>
</html>