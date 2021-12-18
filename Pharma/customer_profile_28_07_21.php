<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
$secretary_name = $_SESSION['user_name'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

	date_default_timezone_set('Asia/Kolkata');
	$Cur_Date=date('Y-m-d H:i:s');
	$cur_Date=date('Y-m-d',strtotime($Cur_Date));
	$add_days = 3;
	$Follow_Date = date('d-m-Y',strtotime($cur_Date) + (24*3600*$add_days));
	
require_once("../classes/querymaker.class.php");

	/*if(!isset($_POST['save_patient_edit'])){
	//Clear all temp. details from 'doc_medicine_prescription_template_details' & 'patient_temp_investigation' table

	mysqlDelete('diagnostic_patient_temp_investigation',"diagnostic_id='".$admin_id."' and status='1'");
	mysqlDelete('diagnostic_patient_examination_active',"diagnostic_id='".$admin_id."' and status='1'");
	}*/
	
	$pharma_referal = mysqlSelect("*","pharma_referrals as a inner join pharma_customer as b on b.pharma_customer_id = a.pharma_customer_id","md5(a.pr_id)='".$_GET['p']."'","","","","");
	//$pharma_ship = mysqlSelect("*","pharma_customer","md5(pr_id)='".$_GET['p']."'","","","","");
	//$patient_tab = mysqlSelect("*","doc_my_patient","patient_id='".$pharma_referal[0]['patient_id']."'","","","","");
	$patient_tab = mysqlSelect("*","pharma_customer","pharma_customer_id='".$diagno_referal[0]['pharma_customer_id']."'","","","","");
	//$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$pharma_referal[0]['pr_id']."'  and type='1' and request_from='2'","","","","");
	$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$pharma_referal[0]['pr_id']."' and request_from='2'","","","","");
	//echo $pharma_referal[0]['pharma_cust_gender'];
	if($pharma_referal[0]['pharma_cust_gender']=="1"){
		$gender="Male";
	}
	else if($pharma_referal[0]['pharma_cust_gender']=="2"){
		$gender="Female";
	}

	
	$patient_id = $patient_tab[0]['patient_id'];
						
	//$get_doc_details = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	
	
	//$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","diagnosis_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");
    $patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","episode_id = '".$pharma_referal[0]['episode_id']."'","","","","");
    $attachments = mysqlSelect("*","health_lab_test_request_attachments","customer_id = '".$diagno_referal[0]['dr_id']."'","","","","");
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Profile</title>

    <?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
	
	<link href="../premium/fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="../premium/fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../premium/fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="../premium/fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="../premium/fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="../premium/fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="../premium/fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="../premium/fileUpload/themes/fa/theme.js" type="text/javascript"></script>
	<script language="JavaScript" src="js/status_validationJs.js"></script>
	<script src="js/Chart.bundle.js"></script>
	<script src="js/utils.js"></script>
	
	
	<style>
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
	<script>
	$(document).ready(function() {
    $("#hideSerMed").hide();
	});
	
	function printContent(el){
		var restorepage=document.body.innerHTML;
		var printcontent=document.getElementById(el).innerHTML;
		document.body.innerHTML=printcontent;
		window.print();
		document.body.innerHTML=restorepage;
		
	}
	</script>
	
	<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 
 $( "#get_diagnosis_test" ).autocomplete({
  source: 'get_diagnosis_test.php'
 });
 $( "#get_examination_res" ).autocomplete({
	  source: 'get_examination_res.php'
 });
  
});
</script>

	<style>

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
<?php if($_GET['p']==="0"){?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
<?php } ?>
<script>

    $(window).on('load',function(){
		
		 $('#serPatient').focus();
		
	});
</script>	
<script>
	function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:'country_name='+val,
	success: function(data){
		//$("#slctState").empty();
		$("#diagnostic_cust_state").html(data);
	}
	});
}
	</script>
</head>

<body>

    <div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10 mgTop">
					<h2>Patient Details</h2>					
			   </div>
                <div class="col-lg-2 mgTop">
					<a href="Request"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
        <div class="wrapper wrapper-content">
			<div class="row">
			<?php if($_GET['response']=="success"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Payment Link is sent successfully </strong>
			</div>
			<?php } else if($_GET['response']=="episode-created"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Patient visit details has been added successfully </strong>
			</div>
			<?php } else if($_GET['response']=="update-investigation"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Patient investigation updated successfully </strong>
			</div>
			<?php } else if($_GET['response']=="update-examination"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Patient examination updated successfully </strong>
			</div>
			<?php } else if($_GET['response']=="message-sent"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<strong>Message sent successfully </strong>
			</div>
			<?php }?>
                <div class="col-lg-12 m-b-lg">
                    <div id="vertical-timeline" class="vertical-container light-timeline no-margins">
						<div class="vertical-timeline-block" id="visit-details">
							<div class="ibox-content">
							<h2>Customer Information</h2>
							<div class="hrline"><hr></div>
								<div class="row">
									<div class="col-lg-2 m-b-lg"><strong>NAME</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_customer_name']; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>CONTACT NO</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_customer_phone']; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>STATUS</strong></div>
									<div class="col-lg-2 m-b-lg">
									<h2><?php if($pharma_referal[0]['order_status'] == '2') { echo '<span class="label label-warning" style="float: right;font-size: 13px;margin-right: 30px;padding: 7px;">Payment Link Sent</span>'; } 
										else if($pharma_referal[0]['order_status'] == '3') { echo '<span class="label label-info" style="float: right;font-size: 13px;margin-right: 30px;padding: 7px;">Paid</span>'; }
										else if($pharma_referal[0]['order_status'] == '4') { echo '<span class="label label-success" style="float: right;font-size: 13px;margin-right: 30px;padding: 7px;">Completed</span>'; }
										else { echo '<span class="label label-warning" style="float: right;font-size: 13px;margin-right: 30px;padding: 7px;">Referred</span>'; } ?>
									</h2>
									</div>
								</div>
								
								<div class="row">
									
									<div class="col-lg-2 m-b-lg"><strong>EMAIL</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_customer_email']; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>ADDRESS</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_cust_address']; ?></div>
								</div>
								<div class="row">
									
									<div class="col-lg-2 m-b-lg"><strong>GENDER</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $gender; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>AGE</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_cust_age']; ?></div>
								</div>
								<div class="row">
									
									
								</div>
							</div>
						</div>
						<div class="vertical-timeline-block" id="visit-details">
							<div class="ibox-content">
							<h2>Shipping Address</h2>
							<div class="hrline"><hr></div>
								<div class="row">
									<div class="col-lg-2 m-b-lg"><strong>ADDRESS</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_shipping_address']; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>CITY</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_shipping_city']; ?></div>
								</div>
								
								<div class="row">
									
									<div class="col-lg-2 m-b-lg"><strong>STATE</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_shipping_state']; ?></div>
									<div class="col-lg-2 m-b-lg"><strong>COUNTRY</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_shipping_country']; ?></div>
								</div>
								<div class="row">
									<div class="col-lg-2 m-b-lg"><strong>PINCODE</strong></div>
									<div class="col-lg-2 m-b-lg"><?php echo $pharma_referal[0]['pharma_shipping_pincode']; ?></div>
								</div>
							</div>
						</div>
						<div class="vertical-timeline-block" id="visit-details">
					
                            <div class="ibox-content">
							<?php
								if (count($patient_episodes) > 0)
								{ ?>
                            <table class="footable table table-stripped toggle-arrow-tiny table-responsive">
                                <thead>
									<tr>
										<th>Precription</th>
									</tr>
								</thead>
                                <tbody>
									<?php 								
										$patient_episode_count = 0;
										while (list($patient_episode_key, $patient_episode_val) = each($patient_episodes))
										{
											$patient_episode_count--;
									?>
                                <tr class="footable-even footable-detail-show">
                                    <!--<td>#Visit <?php echo $patient_episode_count." - ".$patient_episode_val['formated_date_time']; ?></td>-->
									
									<!-- DISPLAY PRESCRIPTIONS -->
									
									<td>
										<?php $doc_patient_episode_prescriptions = mysqlSelect("*","doc_patient_episode_prescriptions","episode_id = '". $patient_episode_val['episode_id'] ."' "," prescription_seq ASC","","","");

											if (COUNT($doc_patient_episode_prescriptions) > 0)
											{
												
												if($doc_patient_episode_prescriptions[0]['prescription_template']==0){
										?>
										
										<table class="table table-bordered">
											<thead>
											<tr>
											<th>Medicine</th>
											<th>Generic Name</th>
											<th>Frequency</th>
											<th>Timing</th>
											<th>Duration</th>
											
											
											</tr>
											</thead>
											<tbody>
												<?php
												while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
												
												{
													$prescription_timing=mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");

												?>
													<tr>
													<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
													<td><?php echo $patient_episode_prescription_val['prescription_generic_name'] ?></td>
													<td><?php echo $patient_episode_prescription_val['prescription_frequency'] ?></td>
													<td><?php echo $prescription_timing[0]['english'] ?></td>
													<td><?php echo $patient_episode_prescription_val['duration'] ?></td>
													
													</tr>
												<?php
												}
												?>
											</tbody>
										</table>
											<?php 
														} 
														else if($doc_patient_episode_prescriptions[0]['prescription_template']==1){ ?>
														<table class="table table-bordered">
														<thead>
														<tr>
														<th>S.No.</th>
														<th>Medicine</th>
														<th>Morning</th>
														<th>Afternoon</th>
														<th>Night</th>
														<th>Duration</th>
														<th>Timing</th>												
														</tr>
														</thead>
														<?php
														while (list($patient_episode_prescription_key, $patient_episode_prescription_val) = each($doc_patient_episode_prescriptions))
														
														{
															$prescription_timing=mysqlSelect("*","doc_medicine_timing_language","language_id='".$patient_episode_prescription_val['timing']."'","","","","");
															$sl_num = $patient_episode_prescription_key+1	
														?>
															<tr>
															<td rowspan="2" style="text-align:center;"><?php echo $sl_num; ?></td>
															<td><?php echo $patient_episode_prescription_val['prescription_trade_name'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_morning'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_noon'] ?></td>
															<td><?php echo $patient_episode_prescription_val['med_frequency_night'] ?></td>
															<td><?php echo $patient_episode_prescription_val['duration']." ".$patient_episode_prescription_val['med_duration_type']; ?></td>
															<td><?php echo $prescription_timing[0]['english'] ?></td>
															</tr>
															<tr>
															<td><?php echo "<b>Generic:</b> ".$patient_episode_prescription_val['prescription_generic_name']; ?></td>
														<td colspan="5"> <?php if(!empty($patient_episode_prescription_val['prescription_instruction'])){ echo "<b>Instructions:</b> ".$patient_episode_prescription_val['prescription_instruction'];} ?></td>
															</tr>
														<?php
														}
														?>
														</tbody>
														</table>
														<?php } ?>
														<br>
											<br>
											<!--<br><br><br>-->
										<?php } 
										else
										{
											echo "NA";
										}?>
										</td> 
									</tr>
									<?php   
										}
									
									?>
								</tbody>
								
                                <tfoot>
                               <!-- <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>-->
                                </tfoot>
                            </table>
							
							<?php } 
							else { 
							?>
							<h3> No episodes created </h3>
							<?php } ?>
							
                        </div>
                            <!--</div>-->
                        </div>
						
						
						<!-- View report section -->
					
					
                        
                    </div>

                </div>

            </div>
			<div class="vertical-timeline-block" id="visit-details">
				<div class="ibox-content">
					<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmSMSSubmit" id="frmSMSSubmit">
						<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
						 <input type="hidden" name="episode_id" value="<?php echo $patient_episodes[0]['episode_id']; ?>">
						 <input type="hidden" name="type" value="1">
						 <input type="hidden" name="diagno_pharma_id" value="<?php echo $pharma_referal[0]['pharma_id']; ?>">
						 <input type="hidden" name="request_from" value="2">
						 <input type="hidden" name="referred_id" value="<?php echo $pharma_referal[0]['pr_id']; ?>">
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
									 <label style="margin-right:10px;"> Payment Amount in QAR</label>
									 <input type="number" name="paymentValue"  value="<?php echo $payment_amount[0]['payment_amount']; ?>" placeholder="";>
									<!--<button type="submit" name="smsPatient" id="smsPatient" class="btn btn-primary m-b m-r">SMS To Patient</button>-->
									</div>
									<div class="col-lg-6" style="";>
										<button type="submit" name="sendPayment" id="sendPayment" class="btn btn-primary m-b m-r">Send Payment Link</button>
									</div>
									
									<?php if($patient_episode_val['doc_id']!=0){ ?>
									<!--<div class="col-lg-6 pull-right">
									
									<button type="submit" name="smsDoctor" id="smsDoctor" class="btn btn-primary m-b m-r">SMS To Doctor</button>
									</div>-->
									<?php } ?>
								</div>
							</div>
					</form>
				</div>
			</div>
			<div class="vertical-timeline-block" id="visit-details">
				<div class="ibox-content">
					<h2>View Reports</h2>
					<div class="hrline"><hr></div>
					<button type="button" id="attachReport" class="btn btn-w-m btn-info">Attach reports</button>
						<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								 <input type="hidden" name="episode_id" value="<?php echo $patient_episodes[0]['episode_id']; ?>">
								 <input type="hidden" name="type" value="1">
								 <input type="hidden" name="diagno_pharma_id" value="<?php echo $diagno_referal[0]['diagnostic_id']; ?>">
								 <input type="hidden" name="request_from" value="1">
							     <input type="hidden" name="referred_id" value="<?php echo $diagno_referal[0]['dr_id']; ?>">
								<label><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
                   
									<div class="form-group col-lg-12">
										<div class="file-loading">
											<input id="file-5" name="file-5[]" class="file" type="file" multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
										</div>
									</div>
   
								 <div class="row" id="image_preview"></div>
								 <div class="form-group">
									<div class="col-lg-6 pull-right">
									<button type="button" id="cancel" class="btn btn-primary m-b ">CANCEL</button>
								
									<button type="submit" name="addAttachments" class="btn btn-primary m-b m-r">UPLOAD REPORTS</button>
									</div>
								</div>
						</form>
						
						<div>
                            <div class="feed-activity-list">
							
                                    <div class="feed-element" style="padding-bottom: 40px;">
                                        
                                        <div class="media-body ">
                                         
									<ul>
									<?php 
									
									foreach($doc_patient_reports as $attachList){ 
									//Here we need to check file type
									$img_type =  array('gif','png' ,'jpg' ,'jpeg');
									$extractPath = pathinfo($attachList['image_name'], PATHINFO_EXTENSION);
									if(in_array($extractPath,$img_type) ) {
										$imgIcon="../HealthReportsAttachment/".$attachList['id']."/".$attachList['image_name'];
									}
									else if($extractPath=="docx"){
											$imgIcon="../assets/images/doc.png";
									}
									else if($extractPath=="pdf" || $extractPath=="PDF"){
										$imgIcon="../assets/images/pdf.png";
									} 
									
									?>
									
									<div class="file-box">
										<div class="file">
											<a href="#">
												<span class="corner"></span>
												<a href="../HealthReportsAttachment/<?php echo stripslashes($attachList['id']);?>/<?php echo stripslashes($attachList['image_name']);?>" target="_blank"  title="<?php echo stripslashes($attachList['image_name']);?>">
												<div class="image">
													<img alt="image" class="img-responsive" src="<?php echo $imgIcon; ?>">
													
												</div></a>
											<div class="file-name">
													<?php echo substr($attachList['image_name'],0,10); ?>
													<br/>
													<small><a href="<?php echo "../HealthReportsAttachment/".$attachList['id']."/".$attachList['image_name']?>" target="_blank"  title="<?php echo stripslashes($attachList['image_name']);?>">View</a> 
													</small>
												</div>
											</a>

										</div>
									</div>
									
									<?php  } ?>
									  

									</ul>
                                        </div>
                                    </div>
                              
							<?php //} ?>
							</div>
							</div>
							
				</div>
			</div>
			
			
		</div>
       <!--<?php include_once('footer.php'); ?>-->

        </div>
        </div>
	
	
	
	
	<!-- Sweet alert -->
	<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>

    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
    <!-- Tags Input -->
    <script src="../assets/js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

    <script>
        $(document).ready(function(){

            $('.tagsinput1').tagsinput({
                tagClass: 'label label-primary'
            });
			
           
        });
		$(document).ready(function(){

            $('.tagsinput').tagsinput({
                tagClass: 'label label-primary'
            });
			
           
        });


    </script>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>

<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
			
			$('#dateadded1').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	 <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	
    <script>
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("diagnostic_customer_id,diagnostic_customer_name,diagnostic_customer_phone","diagnostic_customer","diagnostic_id='".$admin_id."'","","","","");
											
	?>
            $('.typeahead_1').typeahead({
               source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['diagnostic_customer_id']."-".$listPat['diagnostic_customer_name']."-".$listPat['diagnostic_customer_phone']."',"; }?>]
            });
			          

        });
		
    </script>
	<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
      });
}


</script>

</body>
<script src="js/symptoms.js"></script>

</html>
<script type="text/javascript">

	$('.expandwidth').focus(function()
	{		
		/*to make this flexible, I'm storing the current width in an attribute*/
		$(this).attr('data-default', $(this).width());
		$(this).animate({ width: 250 }, 'slow');
	}).blur(function()
	{
		/* lookup the original width */
		var w = $(this).attr('data-default');
		$(this).animate({ width: w }, 'slow');
	});

	$(".prescriptionTemplate").change(function() {
		
		var template_id = this.value;
		if(this.checked) {
			loadPrescriptionTemplate(template_id);
		}
		else
		{
			$("[id^='prescription_del_"+ template_id +"']").remove(); 
		}
	});

		
			
		var data = <?php echo $arrPatientList ?>;

		//alert(data);

		$(".patientList").autocomplete({
				minLength: 2,
				source: data,
				focus: function(event, ui) {
					$(".patientList").val(ui.item.label);
					return false;
				}
			})
			.autocomplete("instance")._renderItem = function(ul, item) {
				return $("<li>")
					.append('<a href="' + item.value + '">' + item.label + '</a>')
					.appendTo(ul);
			};


	

</script>
