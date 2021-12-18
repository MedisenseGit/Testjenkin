<?php ob_start();
 error_reporting(0);
 session_start();

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
	$docid=$_SESSION['docid'];
	$patid=$_SESSION['patid'];
	$eventtype=$_SESSION['eventtype'];
	$randid=$_SESSION['randid'];
	$amount=$_SESSION['total_amount'];

$chkPatDet = $objQuery->mysqlSelect("*","patient_tab","patient_id='".$patid."'","","","","");



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
	<div class="medisene-form">
							<fieldset class="no-padding">
							 <section class="sub-heading">
							<div class="form-body " style="padding-bottom:20px !important;">
							<div class="row">
													
							<div class="col-xs-12">
                              <div class="form-group">
                                 <p style="padding-left:10px;"><b>Dear <?php echo $chkPatDet[0]['patient_name']; ?></b><br><br>

Thanks for submitting your query at <b>medisensehealth.com</b> on <b><?php echo date('d M Y',strtotime($chkPatDet[0]['TImestamp'])); ?>.</b><br>
We have marked your query as a paid query to render further services or for the services already consumed. Please complete the transaction using credit/debit/Net banking.</p>
<p style="padding-left:10px;">
<b>Doctor :</b> <?php echo $getDocInfo[0]['ref_name']; ?><br>
<b>Amount to be Paid :</b> <?php echo $amount; ?></p>
<div class="col-xs-4">
									<form method="post" action="../PaytmKit/pgRedirect.php">
										<input type="hidden" id="CUST_ID" name="CUST_ID" value="<?php echo $_SESSION['cust_id']; ?>">
										<input type="hidden" id="ORDER_ID" name="ORDER_ID" value="<?php echo $_SESSION['our_transaction_id']; ?>">
										<input type="hidden" id="INDUSTRY_TYPE_ID" name="INDUSTRY_TYPE_ID" value="Retail120">
										<input type="hidden" id="CHANNEL_ID" name="CHANNEL_ID" value="WEB">
										<input type="hidden" id="TXN_AMOUNT" name="TXN_AMOUNT" value="<?php echo $_SESSION['total_amount']; ?>">
										<input type="hidden" name="MOBILE_NO" value="<?php echo $_SESSION['mobile_number']; ?>">
										<input type="hidden" name="EMAIL" value="<?php echo $_SESSION['email_address']; ?>">										
										<input type="hidden" name="CALLBACK_URL" value="https://medisensecrm.com/Email_Response/payment_success.php">
										
 <button type="submit" style="width:100px; float: left;  margin-top:20px;" class="form-control submit" value=""><i class="fa fa-credit-card"></i> PROCEED FOR PAYMENT </button><br><br> 
                                                                        </form>
</div><br><br><br><br>
<p style="padding-left:10px;">For any further questions please contact <b>+91 70266 46022.</b> <br><br>

Note : If you are not happy with the opinion, you will be entitled for complete refund. </p>
                                 
								</div>
							</div>
                           
						   
						   
						   
						   </div>
						   </div>
						  
						   </section>
						   </fieldset>
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