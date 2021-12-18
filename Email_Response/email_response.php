<?php
error_reporting(0);

session_start();


header("Pragma: no-cache");

header("Cache-Control: no-cache");

header("Expires: 0");


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
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/flexslider.css" rel="stylesheet">
   
 <link rel="stylesheet" href="assets/layerslider/css/layerslider.css" type="text/css">
 
   <link rel="stylesheet" href="assets/css/contact.css" type="text/css">

    <link rel="stylesheet" href="assets/css/datepicker.css" type="text/css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
 
   <!--[if lt IE 9]>
      <script src="assets/js/html5shiv.js"></script>
  
    <script src="assets/js/respond.min.js"></script>
    <![endif]-->
 
   <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
   
 
	<script src="jquery.js"></script>


	
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

<span><a href="https://medisensehealth.com/"> 
<img src="assets/img/medisense_og.png" alt="Medisense-Healthcare Solutions Pvt Ltd"> </a></span>							
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

  
    <section class="contact ">

        <div class="container">

            <div class="row">


                <div class="col-md-10 col-md-offset-1 form-table1">
				
	
	<h3 class="featurette-heading"><?php  echo $successmsg; ?></h3>
    
    								<div class="col-md-10">
									<?php if($_GET['response']==4){ ?>
									<h4><span style="color:green; font-weight:bold; font-style:italic;">Your appointment will be confirmed within few hours. Hospital/Doctor's team shall contact you and confirm the appointment. </span><br><br> If you don't hear then call 70266 46022.</h4>
									<?php }
									if($_GET['response']==44) {?> 
									<h4><span style="color:red; font-weight:bold;">Your request for appointment with the doctor has been already sent to the doctor....<br><br> If you don't hear then call 70266 46022.</span></h4>
									<?php }
									 if($_GET['response']==3){ ?>
									<h4>Thank you for giving us your valuable time<br><br><span style="color:green; font-weight:bold; font-style:italic;">Your appreciation has already been sent to the doctor</span></h4>
									<?php } 
									if($_GET['response']==33) {?> 
									<h4><span style="color:red; font-weight:bold;">Your appreciation has already been  sent to the doctor....</span></h4>
									
									<?php } 


									if($_GET['response']==1){ ?>
									
									<h4>
									<span style="color:green; font-weight:bold; font-style:italic;">Your request to talk to the Hospital or doctor's team has been submitted. You will receive a call on your preferred date/time. If you do not hear from the hospital, do write to us at medical@medisense.me and we will do the needful.</span><br><br></h4>
									<?php } if($_GET['response']==5){ ?>
									
									<h4>
									<span style="color:green; font-weight:bold; font-style:italic;">Your request has been sent successfully. You will soon be receiving a call from Medisense Team. We wish you get well soon. </span><br><br></h4>
									<?php } if($_GET['response']==55){ ?>
									
									<h4>
									<span style="color:red; font-weight:bold; font-style:italic;">Your "Need opinion from another doctor" service request has already been processed. You will soon be receiving a call from Medisense Team. We wish you get well soon. </span><br><br></h4>
									<?php }
									if($_GET['response']==11) {?> 
									<h4><span style="color:red; font-weight:bold;">Your request to talk to the Hospital or doctor's team has been submitted. You will receive a call on your preferred date/time. If you do not hear from the hospital, do write to us at medical@medisense.me and we will do the needful.</span></h4>
									<?php } 
									if($_GET['response']==22) {?> 
									<h4><span style="color:red; font-weight:bold;">This link has expired</span></h4>
									<?php } ?>
                                   <br />
									 
									
									
<br />

   			 						</div>
                                     
                                    
                                  <div style="clear:both;"></div>
                                 
  
    <div class="col-md-2"  style="margin-top:20px;">
   

    <!--
	<span class="btn btn-success"><a href="home.php" style="color:#FFF" style="text-decoration:none">&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;Home&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;</a></span> -->

    </div>


    </div>
</section>


    
   
</body>

</html>
<?php ob_flush(); 
?>