<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");

//include('connect.php');
include('functions.php');
$admin_id = $_SESSION['user_id'];
$Patient_id=$_GET['p'];
if(empty($admin_id))
{
	header("Location:login");
}
$no_of_records_per_page = 10;
//TELECUNSALTAION DETAILS
//$gettelDeta = mysqlSelect("*","appointment_transaction_detail","tele_communication='1'","","","","$no_of_records_per_page");
$gettelDeta = mysqlSelect("c.id as appoint_id,c.patient_name as patient_name,c.pay_status as status, a.app_date as app_date, a.app_time as app_time,c.Email_address as patient_email, c.Mobile_no as patient_mob,b.ref_name as Ref_Name, a.appoint_trans_id as appoint_trans_id","appointment_token_system as a inner join referal as b on b.ref_id = a.doc_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id ","c.appointment_type='2' or c.appointment_type='3' and c.hosp_id='".$admin_id."'","c.id desc","","","$no_of_records_per_page");
//APPIONTMENT DETAILS
//$getAppointDeta = mysqlSelect("*","appointment_transaction_detail","tele_communication='0'","","","","$no_of_records_per_page");

$getAppointDeta = mysqlSelect("c.id as appoint_id,c.patient_name as patient_name,c.pay_status as status, a.app_date as app_date, a.app_time as app_time,c.Email_address as patient_email, c.Mobile_no as patient_mob,b.ref_name as Ref_Name, a.appoint_trans_id as appoint_trans_id","appointment_token_system as a inner join referal as b on b.ref_id = a.doc_id inner join appointment_transaction_detail as c on c.appoint_trans_id = a.appoint_trans_id ","c.appointment_type='0' or c.appointment_type='1' and c.hosp_id='".$admin_id."'","c.id desc","","","$no_of_records_per_page");

//PHARMA TABLE DETAILS
$getPharma = mysqlSelect("*","pharma","hospital_id='".$admin_id."'","","","","");
//DIAGNOSTIC TABLE DETAILS
$getDiagnos = mysqlSelect("*","Diagnostic_center","hospital_id='".$admin_id."'","","","","");
//SECOND OPINION DETAILS
//$getSecOp = mysqlSelect("a.patient_id as PID,a.patient_name as patient_name ,a.patient_id as patient_id ,a.TImestamp as TImestamp,b.status2 as Status,c.ref_name as Ref_Name","patient_tab as a inner join patient_referal as b on b.patient_id=a.patient_id inner join referal as c on c.ref_id=b.ref_id doctor_hosp as d on d.doc_id=c.ref_id","a.opinion_for='0' and d.hosp_id='".$admin_id."'","a.patient_id desc","","","$no_of_records_per_page");
$getSecOp = mysqlSelect("a.patient_id as PID,a.patient_name as patient_name ,a.patient_id as patient_id ,a.TImestamp as TImestamp,b.status2 as Status,c.ref_name as Ref_Name","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id  inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id","a.opinion_for='0' and d.hosp_id='".$admin_id."'","a.patient_id desc","","","$no_of_records_per_page");

//$get_provInfo = mysqlSelect("*","referal ","enc_key='".$_GET['ency_id']."'","","","","");


$get_diagnoInfo = mysqlSelect("count(a.dr_id) as did","diagnostic_referrals as a inner join Diagnostic_center as b on b.diagnostic_id=a.diagnostic_id","b.hospital_id='".$admin_id."'","","","","");
$data_diagno=$get_diagnoInfo;

$get_pharmaInfo = mysqlSelect("count(a.pr_id) as pid","pharma_referrals as a inner join pharma as b on b.pharma_id=a.pharma_id ","b.hospital_id='".$admin_id."'","","","","");
$data_pharma=$get_pharmaInfo;

//$get_SecOpInfo = mysqlSelect("count(a.patient_id) as soid","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id  inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.opinion_for='0' and d.hosp_id='".$admin_id."'","","","","");
$get_SecOpInfo = mysqlSelect("count(a.patient_id) as soid","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id  inner join referal as c on c.ref_id=b.ref_id inner join doctor_hosp as d on d.doc_id=c.ref_id","a.opinion_for='0' and d.hosp_id='".$admin_id."'","","","","");
//$get_SecOpInfo = mysqlSelect("count(a.patient_id) as soid","patient_tab","opinion_for='0'","","","","");
$data_SecOp=$get_SecOpInfo;
//TOTAL NUMBER OF TELECUNSULTATION
$get_teleInfo = mysqlSelect("count(id) as tid","appointment_transaction_detail ","hosp_id='".$admin_id."'","","","","");
$data_tele=$get_teleInfo;
//PENDING TELECUNSULTATION
// $get_telepend = mysqlSelect("count(id) as peid","appointment_transaction_detail ","hosp_id='".$admin_id."' and tele_communication='1' and pay_status='VC Confirmed'","","","","");

$get_telepend = mysqlSelect("count(distinct id) as peid","appointment_transaction_detail","tele_communication='1' and pay_status='VC Confirmed' and hosp_id='".$admin_id."'","","","","");

$tele_pend=$get_telepend;

//GET PHARMA HOSPITAL NAME
$sel_hosp_name = mysqlSelect("hosp_name as Hosp_name","hosp_tab ","hosp_id='".$admin_id."'","","","","");
//$Hosp_name=$sel_hosp_name
//GET DIAGNOSTIC HOSPITAL NAME
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Welcome to Medisense Practice</title>
	<?php include_once('support.php'); ?>
	<!-- Ladda style -->
    <link href="../assets/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">			
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=191717377898171&quote=medisense-community";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script> 

<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link href="../assets/release/chariot.css" rel="stylesheet" type="text/css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="../assets/js/scroll_up.js"></script>
<!-- c3 Charts -->
    <link href="../assets/css/plugins/c3/c3.min.css" rel="stylesheet">
 <link href="../assets/css/plugins/chartist/chartist.min.css" rel="stylesheet">
 
<style>

.scrollToTop{
	width:100px; 
	height:130px;
	padding:10px; 
	text-align:center; 
	background: whiteSmoke;
	font-weight: bold;
	color: #444;
	text-decoration: none;
	position:fixed;
	bottom:55px;
	right:40px;
	display:none;
	background: url('arrow_up.png') no-repeat 0px 20px;
}
.scrollToTop:hover{
	text-decoration:none;
}

</style> 
</head>

<body>

    <div id="wrapper">
	<!-- Side Menu -->
    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
		<?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Home</h2>
                    <ol class="breadcrumb">
                        <li class="active">
                            <strong>Home</strong>
                        </li>
                      
                    </ol>
					
                </div>
               <!-- <div class="col-lg-2 mgTop">
					<a href="http://lms1.bmj.com/html3/bmjindia/cep/bjo" target="_blank"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-newspaper-o"></i> Journal Access</button></a>
                                
			   </div>-->
			</div>
			
			<div class="row" style="padding-top:40px;padding-left:40px;">
				<div class="col-md-6">
					<div class="pull-right"><a href="Profile"><i class="fa fa-pencil-square-o"></i> Edit</a></div>
                    <div class="profile-image">
                        <img src="hospital-icon.png" class="img-circle circle-border m-b-md" width="128" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                  <strong>  <?php echo $getCompanyProfile[0]['hosp_name']; ?></strong>
                                </h2>
                                <h4><?php echo $getCompanyProfile[0]['hosp_addrs']; ?></h4>
                                <small>
                                    <i class="fa fa-mobile"></i> <?php echo $getCompanyProfile[0]['hosp_contact']; ?>,<br> <i class="fa fa-envelope"></i> <?php echo $getCompanyProfile[0]['hosp_email']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
			
			<div class="row" style="margin-left:20px;padding:0px;">
				<div class="col-lg-2 col" style="margin:40px;padding:10px;background-color:#957DAD; border-radius:10px;">
					<h3 style="text-align:center;color:#337ab7;margin-top:35px;margin-bottom:35px;color:#ffff"><a><span style="color:#000000;">Number of Teleconsultations <hr>Paid / Total  <br><?php echo $tele_pend[0][peid]; ?> / <?php echo $data_tele[0][tid]; ?></span></a></h3>
				</div>
				<div href="Cases-Recieved" class="col-lg-2" style="margin:40px;padding:5px; background-color:#FF968A;border-radius:10px;">
					<h3 style="text-align:center;color:#337ab7;margin-top:47px;margin-bottom:47px;color:#ffff"><a><span style="color:#000000;">Number of Second Opinion <br><hr><?php echo $data_SecOp[0][soid]; ?></span></a></h3>
				</div>
				<div class="col-lg-2" style="margin:40px;padding:10px; background-color:#97C1A9;border-radius:5px;">
					<h3 style="text-align:center;color:#337ab7;margin-top:41px;margin-bottom:41px;color:#ffff"><a><span style="color:#000000;">Number of Diagnostic Orders<br><hr><?php echo $data_diagno[0][did]; ?></span></a></h3>
				</div>	
				<div class="col-lg-2" style="margin:40px;padding:10px; background-color:#ECD5E3;border-radius:10px;">
					<h3 style="text-align:center;color:#337ab7;margin-top:41px;margin-bottom:41px; color:#ffff"><a><span style="color:#000000;">Number of Pharma Orders<br><hr><?php echo $data_pharma[0][pid]; ?></span></a></h3>
				</div>		
			</div>
		<div class="row" style="margin-right:100px;padding-left:40px;">
			<div class="col-lg-6">
				<h2 style="text-align:center;color:#00000;">Teleconsultations</h2>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Patient Name</th>
								<th>Doctor Name</th>
								<th>Appointment Slot</th>
                                <th>Contact Number</th>
                                <th>Status</th>
							</tr>
                            </thead>
                            <tbody>
										<?php foreach($gettelDeta as $list5){	
										?>
									<a>
										<tr>
										   <td><a href="Reschedule?a=<?php echo $list5['appoint_trans_id']; ?>"><?php echo $list5['patient_name'];  ?></a></td>
										   <td><a><?php echo $list5['Ref_Name'];  ?></a></td>
										   <td><a><?php echo $list5['app_date'];?><br><?php echo $list5['app_time'];  ?></a></td>
										   <td><a><?php echo $list5['patient_mob'];?> | <a><?php echo $list5['patient_email'];  ?> </a></td>
										   <td><a><?php echo $list5['status'];  ?></a></td>
										   
										</tr>
									</a>
									<?php } ?>
							</tbody>
                        </table>
						<small><a href="Appointments">More</a></small>
                    </div>
				</div>
            </div>
			<div class="col-lg-6">
				<h2 style="text-align:center;color:#00000;">Appointment</h2>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
								<th>Patient Name</th>
								<th>Doctor Name</th>
								<th>Appointment Slot</th>
                                <th>Contact Number</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
										<?php foreach($getAppointDeta as $list6){	
										?>
									<a>
										<tr>
										<td><a href="Reschedule?a=<?php echo $list6['appoint_trans_id']; ?>"><?php echo $list6['patient_name'];  ?></a></td>
										   <td><a><?php echo $list6['Ref_Name'];  ?></a></td>
										   <td><a><?php echo $list6['app_date'];?> <br> <?php echo $list5['app_time'];  ?></a></td>
										   <td><a><?php echo $list6['patient_mob'];?> | <a><?php echo $list5['patient_email'];  ?> </a></td>
										   <td><a><?php echo $list6['status'];  ?></a></td>
											<!-- <?php if  ($list6['status']=="Pending"){
													$btn_type2= "btn-primary";
												}
												else if ($list6['status']=="VC Confirmed") {
													$btn_type5= "btn-info";
												}
												else if ($list6['status']=="Cancelled") {
													$btn_type5= "btn-warning";
												}
												else { $App_status="Comple";
												$btn_type5= "btn-success";
												}
											?>
										   <td>
											<button class="btn <?php echo $btn_type5;?> btn-xs"><?php echo $App_status;  ?> </button>
								    		</td>  -->
										</tr>
									</a>
									<?php } ?>
							</tbody>
                        </table>
						<small><a href="Appointments">More</a></small>
                    </div>
				</div>
			</div>
		</div>
        <div class="row" style="margin-right:100px;padding-left:40px;">
			<div class="col-lg-6">
				<h2 style="text-align:center;color:#00000;"><?php echo $sel_hosp_name[0][Hosp_name];?> Pharma</h2>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox-content table-responsive">
						<table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
								<th>Date</th>
								<!--<th>Pharma Name</th>-->
                                <th>Contact Number</th>
								<th>Status</th>
                                <!--<th>Contact Person</th>-->
							</tr>
                            </thead>
                            <tbody>
								<?php foreach($getPharma as $list){
									$getPhaCustomer = mysqlSelect("a.pr_id as PRID,b.pharma_customer_name as customer_Name,b.pharma_customer_phone as PhoneNo,b.pharma_customer_email as EmailID,a.referred_date as Reffered_Date, a.order_status as Order_Status,c.pharma_name as Pharma_name","pharma_referrals as a inner join pharma_customer as b on b.pharma_customer_id=a.pharma_customer_id inner join pharma as c on c.pharma_id=a.pharma_id","b.pharma_id='".$list['pharma_id']."'","a.pr_id desc","","","$no_of_records_per_page");
									foreach($getPhaCustomer as $list3){
								?>
								
                            <a>
								<tr>
								   <td><a><?php echo $list3['customer_Name'];  ?></a></td>
								   <td><a><?php echo date('d/m/y', strtotime($list3['Reffered_Date']));  ?><br> <?php echo date('H:m', strtotime($list3['Reffered_Date']));  ?></a></td>
								    <!--<td><a><?php echo $list3['Reffered_Date'];  ?></a></td>
								   <td><a><?php echo $list3['Pharma_name'];  ?></a></td>-->
								   <td><a><?php echo $list3['PhoneNo'];?> | <a><?php echo $list3['EmailID'];  ?> </a></td>
								   	<?php if  ($list3['Order_Status']==1){
										$Pharma_status="Reffered";
										$btn_type2= "btn-primary";
										}
										else if ($list3['Order_Status']==2) {
											$Pharma_status="Payment link sent";
											$btn_type2= "btn-info";
										}
										else if ($list3['Order_Status']==3) {
											$Pharma_status="Paid";
											$btn_type2= "btn-warning";
										}
										else { $Pharma_status="Completed";
										$btn_type2= "btn-success";
										}
									?>
									<td>
										<button class="btn <?php echo $btn_type2;?> btn-xs"><?php echo $Pharma_status;  ?> </button>
								    </td>
								   
								  
								  
								</tr>
							</a>
								<?php }} ?>
                            </tbody>
                        </table>
						<small><a href="Pharma-Diagnostic-List">More</a></small>
					</div>
				</div>
            </div>
			<div class="col-lg-6">
				<h2 style="text-align:center;color:#00000;"><?php echo $sel_hosp_name[0][Hosp_name];?> Diagnostic</h2>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
								<th>Date</th>
								<!--<th>Diagnostic Name</th>-->
                                <th>Contact Number</th>
								<th>Status</th><!--<th>Contact Person</th>-->
								
                            </tr>
                            </thead>
                            <tbody>
								<?php foreach($getDiagnos as $list1){
										$getDiagnosCustomer = mysqlSelect("a.dr_id as DRID,b.diagnostic_customer_name as customer_Name,b.diagnostic_customer_phone as PhoneNo,b.diagnostic_customer_email as EmailID,a.referred_date as Reffered_Date, a.order_status as Order_Status,c.diagnosis_name as Diagno_name","diagnostic_referrals as a inner join diagnostic_customer as b on b.diagnostic_customer_id=a.diagnostic_customer_id inner join Diagnostic_center as c on c.diagnostic_id=b.diagnostic_id","b.diagnostic_id='".$list1['diagnostic_id']."'","a.dr_id desc","","","$no_of_records_per_page");
									  foreach($getDiagnosCustomer as $list4){									
								?>
								 <a>
								<tr>
									<td><a><?php echo $list4['customer_Name'];  ?></a></td>
									<td><a><?php echo date('d/m/y', strtotime($list4['Reffered_Date']));  ?><br> <?php echo date('H:m', strtotime($list4['Reffered_Date']));  ?></a></td>
								   <!-- <td><a><?php echo $list4['Diagno_name'];  ?></a></td>-->
								    <td><a><?php echo $list4['PhoneNo'];?> | <br><a><?php echo $list4['EmailID'];  ?> </a></td>
									<?php if  ($list4['Order_Status']==1){
										$diagno_status="Reffered";
										$btn_type1= "btn-primary";
										}
										else if ($list4['Order_Status']==2) {
											$diagno_status="Payment link sent";
											$btn_type1= "btn-info";
										}
										else if ($list4['Order_Status']==3) {
											$diagno_status="Paid";
											$btn_type1= "btn-warning";
										}
										else { $diagno_status="Completed";
										$btn_type1= "btn-success";
										}
									?>
									<td>
										<button class="btn <?php echo $btn_type1;?> btn-xs"><?php echo $diagno_status;  ?> </button>
								    </td>
								</tr>
							</a>
								<?php } }?>
                            </tbody>
                        </table>
						<small><a href="Pharma-Diagnostic-List">More</a></small>
                    </div>
				</div>
            </div>
		</div>    
        <div class="row" style="margin-right:100px;padding-left:40px;padding-bottom:80px;">
			<div class="col-lg-6">
				<h2 style="text-align:center;color:#00000;">Second Opinion</h2>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                           <tr>
                                <th>Patient Name</th>
								<th>Paitient Id</th>
                                <th> Reg. Date</th>
								<th>Reffered To</th>
                                <th>Status</th>
								<!--<th>Contact Person</th>-->
							</tr>
                            </thead>
								<tbody>
										<?php foreach($getSecOp as $list2){	
										
										
										?>
									<a>
										<tr>
										   <td><a href="Second-Openion-history?p=<?php echo md5($list2['patient_id']);?>" ><?php echo $list2['patient_name'];  ?></a></td>
										   <td><a><?php echo $list2['patient_id'];  ?></a></td>
										   <td><a><?php echo $list2['TImestamp'];  ?></a></td>
										   <td><a><?php echo $list2['Ref_Name'];  ?></a></td>
											<?php if  ($list2['Status']==1){
													$SecOpStatus="Capture";
													$btn_type= "btn-danger";
													}
													else if ($list2['Status']==2) {
														$SecOpStatus="Reffered";
														$btn_type= "btn-warning";
													}
													else if ($list2['Status']==3) {
														$SecOpStatus="P-Waiting";
														$btn_type= "btn-primary";
													}
													else if ($list2['Status']==4) {
														$SecOpStatus="Not Qualified";
														$btn_type= "btn-info";
													}
													else if ($list2['Status']==5) {
														$SecOpStatus="responded";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==6) {
														$SecOpStatus="resonse-P Failed";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==7) {
														$SecOpStatus="Staged";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==8) {
														$SecOpStatus="OP Converted";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==9) {
														$SecOpStatus="IP Converted";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==10) {
														$SecOpStatus="IP Converted";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==11) {
														$SecOpStatus="Invioced";
														$btn_type= "btn-success";
													}
													else if ($list2['Status']==12) {
														$SecOpStatus="Payment Recirved";
														$btn_type= "btn-info";
													}
													else if ($list2['Status']==13) {
														$SecOpStatus="OP visited";
														$btn_type= "btn-success";
													}
													else { $SecOpStatus="Not Responded";
															$btn_type= "btn-success";
													}
											?>
											<td>
												<button class="btn <?php echo $btn_type;?> btn-xs"><?php echo $SecOpStatus;  ?> </button>
											</td>
										   
										</tr>
									</a>
									<?php } ?>
								</tbody>
                        </table>
						<small><a href="Cases-Recieved">More</a></small>
                    </div>
				</div>
            </div>
			
			<div class="col-lg-6">
			
			
			</div>
		</div>		
        
		<a href="#" class="scrollToTop"><h1 class="f-xs text-navy"><i class="fa fa-arrow-circle-up"></i></h1> </a>
        <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Sparkline -->
    <script src="../assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <script>
        $(document).ready(function() {


            $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 48], {
                type: 'line',
                width: '100%',
                height: '50',
                lineColor: '#1ab394',
                fillColor: "transparent"
            });


        });
    </script>
	
	
	 <!-- slick carousel-->
    <script src="../assets/js/plugins/slick/slick.min.js"></script>

    <!-- Additional style only for demo purpose -->
    <style>
        .slick_demo_2 .ibox-content {
            margin: 0 10px;
        }
    </style>

    <script>
        $(document).ready(function(){


            $('.slick_demo_1').slick({
                dots: true
            });

            $('.slick_demo_2').slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                centerMode: true,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });

            $('.slick_demo_3').slick({
                infinite: true,
                speed: 500,
                fade: true,
                cssEase: 'linear',
                adaptiveHeight: true
            });
        });

    </script>
 <script src="../assets/js/plugins/video/responsible-video.js"></script>
 <!-- Ladda -->
    <script src="../assets/js/plugins/ladda/spin.min.js"></script>
    <script src="../assets/js/plugins/ladda/ladda.min.js"></script>
    <script src="../assets/js/plugins/ladda/ladda.jquery.min.js"></script>
 <script src="js/share.js"></script>
<script>

    $(document).ready(function (){

        // Bind normal buttons
        Ladda.bind( '.ladda-button',{ timeout: 500 });

        // Bind progress buttons and simulate loading progress
        Ladda.bind( '.progress-demo .ladda-button',{
            callback: function( instance ){
                var progress = 0;
                var interval = setInterval( function(){
                    progress = Math.min( progress + Math.random() * 0.1, 1 );
                    instance.setProgress( progress );

                    if( progress === 1 ){
                        instance.stop();
                        clearInterval( interval );
                    }
                }, 200 );
            }
        });


        var l = $( '.ladda-button-demo' ).ladda();

        l.click(function(){
            // Start loading
            l.ladda( 'start' );

            // Timeout example
            // Do something in backend and then stop ladda
            setTimeout(function(){
                l.ladda('stop');
            },5000)


        });

    });

</script>
 <!-- Bootstrap Tour -->
    <script src="../assets/js/plugins/bootstrapTour/bootstrap-tour.min.js"></script>
<script>

    $(document).ready(function (){

        // Instance the tour
        var tour = new Tour({
            steps: [{

                    element: "#myPatient",
                    title: "My Patients",
                    content: "<i class='fa fa-info-circle'></i> Here you can add patient details, visit details, prescriptions, follow up visit date etc.<br><br><i class='fa fa-lightbulb-o'></i> Try one click prescription by creating your own template.",
                    placement: "right"
                },
                {

                    element: "#myAppointment",
                    title: "Appointments",
                    content: "<i class='fa fa-info-circle'></i> Here you can view (Or add)  all the appointments requested by your patients. Upon every booking/reschedule/Cancel patient will be sent an SMS/Email.<br><br><i class='fa fa-lightbulb-o'></i> Try sending your doctor appointment link to patients just by entering mobile number or email ID of the patient.",
                    placement: "right"
                },
                {

                    element: "#casesReceive",
                    title: "Cases Received",
                    content: "<i class='fa fa-info-circle'></i> Here you could be receive cases from your care partners. You will have to mention patient details for these requests. Along with your request you will see all the responses from the experts. <br><br><i class='fa fa-lightbulb-o'></i> Open a patient case sheet submitted by your care partners. Try to respond patient queries. When doctor respond care partners and patient will be notified.",
                    placement: "right"
                },
				{

                    element: "#manageHosp",
                    title: "Manage Hospital",
                    content: "<i class='fa fa-info-circle'></i> Here you can add hospital Unit, doctor, care partners, marketing person",
                    placement: "right"
                },
                {

                    element: "#prCloud",
                    title: "PR Cloud",
                    content: "<i class='fa fa-info-circle'></i> Here you can add industry updates, blogs from experts, Conference invites, Job opportunities, Videos. <br><br><i class='fa fa-lightbulb-o'></i> Try to to post blogs, surgical videos, job opportunities etc ",
                    placement: "right"
                }
            ]});

        // Initialize the tour
        tour.init();

        $('.startTour').click(function(){
            tour.restart();

            // Start the tour
            // tour.start();
        })

    });

</script>
<!-- Sweet alert -->
<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
<!-- d3 and c3 charts -->
    <script src="../assets/js/plugins/d3/d3.min.js"></script>
    <script src="../assets/js/plugins/c3/c3.min.js"></script>
	<!-- Chartist -->
    <script src="../assets/js/plugins/chartist/chartist.min.js"></script>
	 <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>
  
    <script>

        $(document).ready(function () {

            
            c3.generate({
                bindto: '#gauge',
                data:{
                    columns: [
                        ['Response Rate', <?php echo $_SESSION['response_rate']; ?>]
                    ],

                    type: 'gauge'
                },
                color:{
                    pattern: ['#1ab394', '#BABABA']

                }
            });
			
			var barData = {
        labels: [<?php for( $i = 5; $i >= 0 ; $i--) { echo "'".date("M Y", strtotime("-".$i." month"))."',"; }?>],
        datasets: [
            {
                label: "Cases Received",
                backgroundColor: '#fc89ac',
				borderColor: "#f1326d",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 5; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Received = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.hosp_id='".$_SESSION['user_id']."') and (b.timestamp between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Received[0]['Total_count'].", "; }?>]
            },
            {
                label: "Responded",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 5; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Responded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.hosp_id='".$_SESSION['user_id']."') and (b.timestamp between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Responded[0]['Total_count'].", "; }?>]
            }
        ]
    };

    var barOptions = {
        responsive: true
    };


    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});


        });

    </script>
	
</body>

</html>
