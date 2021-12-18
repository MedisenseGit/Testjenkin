<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

//echo ($_GET['d']);

if(empty($_GET['d']))
{
	echo "<h2>Error!!!!!!</h2>";
}
//$checkPatient= mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");

$checkPatient= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['p']."'","","","","");


$getDocInfo= mysqlSelect("*","referal","md5(ref_id)='".$_GET['d']."'","","","","");
$get_docSpec = mysqlSelect("*","doc_specialization as a left join specialization as b on a.spec_id=b.spec_id","md5(a.doc_id)='".$_GET['d']."'","","","","");	
$getDocHospital = mysqlSelect("*","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","md5(a.doc_id) = '".$_GET['d']."'","","","","");
$checkSetting= mysqlSelect("*","doctor_settings","md5(doc_id)='".$_GET['d']."' and doc_type='1'","","","","");	
$appointmentResult = mysqlSelect("*","appointment_transaction_detail","md5(pref_doc)='".$_GET['d']."' and md5(patient_id)='".$_GET['p']."' and appoint_trans_id='".$_GET['t']."'","Visiting_date desc","","","");

$approverCheck = mysqlSelect("*","patient_page_approval","md5(doc_id)='".$_GET['d']."' and md5(patient_id)='".$_GET['p']."' and trans_id='".$appointmentResult[0]['appoint_trans_id']."' and status=1","","","","");	

$paymentTransact = mysqlSelect("*","payment_transaction","md5(user_id)='".$_GET['d']."' and md5(patient_id)='".$_GET['p']."' and appoint_trans_id='".$appointmentResult[0]['appoint_trans_id']."' and payment_status='PAID'","","","","");	

if($checkPatient[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($checkPatient[0]['patient_gen']=="2"){
		$gender="Female";
	}
	else if($checkPatient[0]['patient_gen']=="3"){
		$gender="Other";
	}

	if($checkPatient[0]['hyper_cond']=="2"){
		$hyperStatus="No";
	}
	else if($checkPatient[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($checkPatient[0]['diabetes_cond']=="2"){
		$diabetesStatus="No";
	}
	else if($checkPatient[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}
		
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Premium</title>
	<link rel="icon" href="../assets/img/favicon_icon.png">
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
	<!-- Sweet alert -->
	<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
	<!-- Sweet Alert -->
    <link href="../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
	  <!-- Toastr style -->
    <link href="../assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

	<style>

		/* Style the tab */
		.tab {
		overflow: hidden;
		border: 1px solid #ccc;
		background-color: #f1f1f15e;
		}

		/* Style the buttons inside the tab */
		.tab button {
		background-color: inherit;
		float: left;
		border: none;
		outline: none;
		cursor: pointer;
		padding: 10px 16px;
		transition: 0.3s;
		font-size: 15px;
		}

		/* Change background color of buttons on hover */
		.tab button:hover {
		background-color: #ddd;
		}

		/* Create an active/current tablink class */
		.tab button.active {
		background-color: #ccc;
		}

		/* Style the tab content */
		.tabcontent {
		display: none;
		padding: 6px 12px;
		border: 1px solid #ccc;
		border-top: none;
		}
	</style>

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">

	<script type="text/javascript">
		$(function() 
		{
			$( "#get_allergy" ).autocomplete({
				source: 'patient_page_add_details.php'
			});
		});

		function modalPopup() {
			<?php if(count($approverCheck)==0){ ?>
				$('#ModalPopup').modal('show');
			<?php } else{ ?>
				$('#ModalPopup').modal('hide');
			<?php }?>
		}

		function termClick(term){
			var teleCom= $('#chkTeleCom').is(":checked");
			var chkPatConsent=$('#chkPatConsent').is(":checked");
			
			if((teleCom==false || chkPatConsent==false) && term==1){
				alert("Please select both checkboxes confirming your approval");
			}
			else{
				$.ajax({
					type: "POST",
					url: "patient_page_add_details.php",
					data:'termClick=true&doc_id='+<?php echo $appointmentResult[0]['pref_doc']; ?>+'&trans_id='+<?php echo $appointmentResult[0]['appoint_trans_id']; ?>+'&patient_id='+<?php echo $checkPatient[0]['patient_id']; ?>+'&status='+term,
					success: function(data){
						//$("#check_time<?php echo $appointmentResult[0]['appoint_trans_id']; ?>").html(data);
						$('#ModalPopup').modal('hide');
					}
				});
			}
		}

		$(document).ready(function () {
			<?php if(count($approverCheck)==0){ ?>
				$('#ModalPopup').modal({
					backdrop: 'static',
					keyboard: false
				});
			<?php } ?>
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

		.checkbox-inline, .radio-inline {
			vertical-align: bottom;
		}
	</style>
	
</head>

<body class="top-navigation" <?php if(count($approverCheck)==0){ ?>onload="modalPopup()"<?php } ?>>

    <div id="wrapper">

        <div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom white-bg">
				<nav class="navbar navbar-static-top" role="navigation">
					<div class="navbar-header">                
						<a href="#" class="navbar-brand"><img alt="image" class="img" src="../assets/img/Practice_premium.png" width="80"/></a>
					</div>            
				</nav>
			</div>
			<div class="wrapper wrapper-content">
				<div class="container">

					<div class="row">
					
						<?php if($_GET['response']=="reports-attached"){ ?>
							<div class="alert alert-success alert-dismissable">
									<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
									<strong>Reports uploaded successfully!!! </strong>
							</div>
						<?php } ?>
					
						<!-- top header -->		
						<div class="col-md-12">
							<div class="ibox-content text-center p-md">

								<div class="row m-b-lg m-t-lg">
									<div class="col-md-5">
										<div class="profile-image">
											<img src="<?php if(!empty($getDocInfo[0]['doc_photo'])){ echo "../Doc/".$getDocInfo[0]['ref_id']."/".$getDocInfo[0]['doc_photo']; } else { echo "../assets/img/anonymous-profile.png"; } ?>" class="img-circle circle-border m-b-md" alt="profile">
										</div>
										<div class="profile-info">
											<div class="">
												<div>
													<h2 class="no-margins"><?php echo $getDocInfo[0]['ref_name']; ?></h2>                              
													<h4><?php echo $get_docSpec[0]['spec_name'];?> </h4>
													<!--<h4><?php echo $getDocInfo[0]['ref_exp']; ?>+ years experience</h4>-->
													<h4><?php echo $getDocHospital[0]['hosp_name'].",";?> </h4>
													<h4><?php echo $getDocHospital[0]['hosp_addrs'];?> </h4>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">                   
									</div>		
								</div>

								<div class="row m-b-lg m-t-lg">
									<div class="col-md-6">
										<?php if($appointmentResult[0]['appointment_type']==0){ ?>
										<h4><strong>Patient details:  </strong>
													<?php 
													$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$appointmentResult[0]['Visiting_time']."'","","","","");?>
													<?php echo $checkPatient[0]['patient_name'].' | '.date('d-m-Y',strtotime($appointmentResult[0]['Visiting_date']))." | <b class='text-warning'>".$appointmentResult[0]['pay_status']."</b>"; ?>
													</h4>
										<?php } else{ ?>
											<h4><strong>Appointment Timings:  </strong>
											<?php 
											$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$appointmentResult[0]['Visiting_time']."'","","","","");?>
											<?php echo $checkPatient[0]['patient_name'].' | '.date('d-m-Y',strtotime($appointmentResult[0]['Visiting_date']))." | ".$getTimeSlot[0]['Timing']." | <b class='text-warning'>".$appointmentResult[0]['pay_status']."</b>"; ?>
											</h4>
										<?php } ?>
									</div>

									<?php if(count($paymentTransact)== 0){ ?>
										<div class="col-md-2">
											<a name="completepayment" class="btn btn-primary block full-width m-b " target="_blank" href="patient_profile_payment.php?p=<?php echo md5($checkPatient[0]['patient_id']); ?>&d=<?php echo md5($getDocInfo[0]['ref_id']); ?>&t=<?php echo $appointmentResult[0]['appoint_trans_id'] ?>">Complete Payment</a>
										</div>
									<?php } else if($appointmentResult[0]['appointment_type'] == "2") {  ?>
										<div class="col-md-2">
											<a name="completepayment" class="btn btn-primary block full-width m-b " target="_blank" href="patient_profile_payment.php?p=<?php echo md5($checkPatient[0]['patient_id']); ?>&d=<?php echo md5($getDocInfo[0]['ref_id']); ?>&t=<?php echo $appointmentResult[0]['appoint_trans_id'] ?>">Complete Payment</a>
										</div>
									<?php }	else{?>
										<div class="col-md-1">
											<button class="btn btn-primary btn-xs" type="button">PAID</button>
										</div>
									<?php } ?>
								</div>
				
							</div>
						</div>

						<div class="col-md-12">
							<div class="ibox-content p-md">
								<div class="row" style="margin: 10px;">
							
									<?php if($appointmentResult[0]['appointment_type'] == "2") { ?>
										<div class="col-md-3">
											<a name="videoConsult" target="_blank" href="https://maayayoga.com/msvV2.0/index.php?ref_name=<?php echo $getDocInfo[0]['ref_name']; ?>&pat_name=<?php echo $checkPatient[0]['patient_name']; ?>&type=1&r=<?php echo $getDocInfo[0]['ref_id']."_".$checkPatient[0]['patient_id']; ?>" class="btn btn-primary block full-width m-b ">Join Video Consult</a>
										</div>
									<?php } ?>
									<div class="col-md-3">
										<a name="rescheduleAppoint" class="btn btn-primary block full-width m-b " data-toggle="modal" data-target="#myModal<?php echo $appointmentResult[0]['appoint_trans_id']; ?>">Change Appointment</a>
									</div>
									<div class="col-md-3">
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="patient_page_add_details.php"  name="" id="">
											<input type="hidden" name="trans_id" value="<?php echo $appointmentResult[0]['appoint_trans_id']; ?>">
											<!-- <button type="submit" name="cancelAppoint" class="btn btn-primary block full-width m-b ">Cancel</button> -->
										</form>
									</div>					
								</div>

								<script>
									function getDocTiming<?php echo $appointmentResult[0]['appoint_trans_id']; ?>(val) {
										$.ajax({
										type: "POST",
										url: "get_doc_timing_patient_page.php",
										data:'day_val='+val+'&doc_id=<?php echo $appointmentResult[0]['pref_doc']; ?>&hosp_id='+<?php echo $appointmentResult[0]['hosp_id']; ?>,
										success: function(data){
											$("#check_time<?php echo $appointmentResult[0]['appoint_trans_id']; ?>").html(data);
										}
										});
									}
								</script>	
							
								<div class="modal inmodal" id="myModal<?php echo $appointmentResult[0]['appoint_trans_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content animated bounceInRight">
										
											<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="patient_page_add_details.php"  name="frmAddPatient" id="frmAddPatient">

												<input type="hidden" name="trans_id" value="<?php echo $appointmentResult[0]['appoint_trans_id']; ?>">
												<input type="hidden" name="doc_id" value="<?php echo $appointmentResult[0]['pref_doc']; ?>">
												<input type="hidden" name="hosp_id" value="<?php echo $appointmentResult[0]['hosp_id']; ?>">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>                                            
													<h4 class="modal-title">Appointment Re-Schedule</h4>
													<h5>Patient Name: <?php echo $appointmentResult[0]['patient_name']; ?></h5>
												</div>
												<div class="modal-body">
													<div class="form-group">
														<div class="form-group">

															<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

															<div class="col-sm-4">
																<div class="input-group date">
																	<select data-placeholder="Choose a Country..." class="form-control" name="reschedule_date"  tabindex="2" onchange="return getDocTiming<?php echo $appointmentResult[0]['appoint_trans_id']; ?>(this.value);" required="required">
																		<option value="">Select Date</option>
																			<?php 
																				for($i=1; $i<=20; $i++) { 
																			?>                                        
																			<?php 
																				$date = strtotime('+' . $i . 'day');
																				$chkdate=date('D', $date);
																				$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","md5(a.doc_id)='".$_GET['d']."' and a.hosp_id='".$appointmentResult[0]['hosp_id']."'","","","","");
																				
																				
																				$current_date=date('d-m-Y', $date);
																			
																				$checkHoliday= mysqlSelect("holiday_id","doc_holidays","md5(doc_id)='".$_GET['d']."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
																				$date_1 = new DateTime($current_date);
																				$current_time_stamp=$date_1->format("U"); 	
																				$check_holiday=0; 
																			
																			
																				foreach($getDocDays as $daylist) {
																					$getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
																					if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0){ ?>
																						<option value="<?php echo date('Y-m-d', $date);?>" >																		
																							<?php
																								if($i==0) { echo "Today";} else if($i==1){ echo "Tomorrow";} else { echo date('D d-m-Y', $date);}
																							?>
																						</option>
																					<?php }
																				}
																			} ?>
																	</select>
																</div>
															</div>
											
															<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

															<div class="col-sm-4">
																<select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time"  id="check_time<?php echo $appointmentResult[0]['appoint_trans_id']; ?>" tabindex="2" required="required"></select>
															</div>

														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" style="float:left" name="cancelAppoint" id="cancelAppoint" class="btn btn-primary">Cancel Appointment</button>
													<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
													<button type="submit" name="cmdreschedulePatPage" class="btn btn-primary">RESCHEDULE</button>
												</div>
											</form>
											
											<script type="text/javascript" >
												$(document).ready(function(){
													$("#cancelAppoint").click(function(){
														console.log("cancel");

														swal({
															title: "Are you sure want to cancel the appointment ?",
															//	text: "They will only be able to see patient name and tests ordered and not other details. ",
															type: "warning",
															showCancelButton: true,
															confirmButtonColor: "#DD6B55",
															confirmButtonText: "Yes, Cancel it!",
															cancelButtonText: "No, cancel!",
															closeOnConfirm: false,
															closeOnCancel: false 
														},

														function (isConfirm) {
															if (isConfirm) {
																var url = "patient_page_add_details.php?canceltransid="+<?php echo $appointmentResult[0]['appoint_trans_id']; ?>;
																$.get(url, function(response){
																console.log(response);	
																swal("Appointment Cancelled Successfully!", "", "success");
																});
																location.reload();
															} else {
																swal("Cancelled", "", "error");
															}
														});
													
													});
												});
												
											</script>

										</div>
									</div>
								</div>
							</div>
						</div>
					
						<div class="col-md-12">

							<div class="ibox-content p-md">
								<div class="tab">
									<button class="tablinks active" onclick="openCity(event, 'PatDetail')">Patient Details</button>
									<button class="tablinks" onclick="openCity(event, 'History')">Medical History</button>
									<button class="tablinks" onclick="openCity(event, 'Report')">Upload Reports</button>
								</div>

								<div id="PatDetail" class="tabcontent" style="display:block;" >
									<h4><strong>Patient Name: </strong><?php echo $checkPatient[0]['patient_name']; ?><a href="#" data-toggle="modal" class="text-navy" data-target="#patDtl"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> </a></h4>
									<h4><strong>Mobile: </strong> <?php echo $checkPatient[0]['patient_mob']; ?></h4>
									<h4><strong>Email: </strong> <?php echo $checkPatient[0]['patient_email']; ?></h4>
									<h4> <strong>Gender: </strong> <?php echo $gender; ?></h4>
									<h4> <strong>Age: </strong> <?php echo $checkPatient[0]['patient_age']; ?></h4>
									<h4><strong>Address: </strong> 
										<?php echo $checkPatient[0]['patient_addrs'].", ".$checkPatient[0]['patient_loc'].", ".$checkPatient[0]['pat_state']; ?>
									</h4>
								</div>

								<div id="History" class="tabcontent" >
									<div class="row" style="padding: 20px;">

										<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
											
											<div class="col-lg-12">
												<dl>
													<dt>Blood Group</dt><br>
													<dd>
														<!-- <select class="form-control pat_blood_type" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="pat_blood" id="pat_blood">	
															<?php if($checkPatient[0]['pat_blood']=="A+"){ ?>
																<option value="" >Select</option>
																<option value="A\+" selected>A+</option>
																<option value="B\+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-">B-</option>
																<option value="O+" >O+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="B+"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" selected>B+</option>
																<option value="A-" >A-</option>
																<option value="B-">B-</option>
																<option value="0+" >0+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="A-"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" selected>A-</option>
																<option value="B-">B-</option>
																<option value="0+" >0+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="B-"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" selected>B-</option>
																<option value="O+" >O+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="0+"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" >B-</option>
																<option value="O+" selected>O+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="O-"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" >B-</option>
																<option value="O+" >O+</option>
																<option value="O-" selected >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="AB+"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" >B-</option>
																<option value="O+" >O+</option>
																<option value="O-" >O-</option>
																<option value="AB+" selected>AB+</option>
																<option value="AB-" >AB-</option>
															<?php } else if($checkPatient[0]['pat_blood']=="AB-"){ ?>
																<option value="" >Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" >B-</option>
																<option value="O+" >O+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" selected>AB-</option>
															<?php } else {?>
																<option value="" selected>Select</option>
																<option value="A+" >A+</option>
																<option value="B+" >B+</option>
																<option value="A-" >A-</option>
																<option value="B-" >B-</option>
																<option value="O+" >0+</option>
																<option value="O-" >O-</option>
																<option value="AB+" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } ?>
														</select> -->

														<select class="form-control pat_blood_type" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="pat_blood" id="pat_blood">	
															<?php if($checkPatient[0]['pat_blood'] !=""){ ?>
																<option value="Select"    <?php  if($checkPatient[0]['pat_blood'] =="Select"){ ?>selected<?php } ?>   >Select</option>
																<option value="A%2B"  <?php  if($checkPatient[0]['pat_blood'] =="A+"){ ?>selected<?php } ?>  >A+</option>
																<option value="A-"  <?php  if($checkPatient[0]['pat_blood'] =="A-"){ ?>selected<?php } ?>    >A-</option>
																<option value="B%2B"  <?php  if($checkPatient[0]['pat_blood'] =="B+"){ ?>selected<?php } ?>  >B+</option>																
																<option value="B-"  <?php  if($checkPatient[0]['pat_blood'] =="B-"){ ?>selected<?php } ?>    >B-</option>
																<option value="O%2B"  <?php  if($checkPatient[0]['pat_blood'] =="O+"){ ?>selected<?php } ?>  >O+</option>
																<option value="O-"  <?php  if($checkPatient[0]['pat_blood'] =="O-"){ ?>selected<?php } ?>    >O-</option>
																<option value="AB%2B" <?php  if($checkPatient[0]['pat_blood'] =="AB+"){ ?>selected<?php } ?> >AB+</option>
																<option value="AB-" <?php  if($checkPatient[0]['pat_blood'] =="AB-"){ ?>selected<?php } ?>   >AB-</option>
															<?php } else { ?>
																<option value="Select" >Select</option>
																<option value="A%2B" >A+</option>																
																<option value="A-" >A-</option>
																<option value="B%2B" >B+</option>
																<option value="B-">B-</option>
																<option value="0%2B" >0+</option>
																<option value="O-" >O-</option>
																<option value="AB%2B" >AB+</option>
																<option value="AB-" >AB-</option>
															<?php } ?>
														</select>
													</dd><br>
												</dl>
											</div>

											<div class="col-lg-6">							
												<dl>

													<dt>Smoking:</dt><br> 
													<dd>
														<select class="form-control smokeCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="se_smoking" id="se_smoking">	
															<?php if($checkPatient[0]['smoking']=="0-5 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day" selected>0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } else if($checkPatient[0]['smoking']=="5-10 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day" selected>5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } else if($checkPatient[0]['smoking']=="10-15 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day" selected>10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } else if($checkPatient[0]['smoking']=="15-20 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day" selected>15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } else if($checkPatient[0]['smoking']=="20-25 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day" selected>20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } else if($checkPatient[0]['smoking']==">25 per day"){ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day" selected>>25 per day</option>
															<?php }else{ ?>
																<option value="Non-smoker">Non-smoker</option>
																<option value="0-5 per day">0-5 per day</option>
																<option value="5-10 per day">5-10 per day</option>
																<option value="10-15 per day">10-15 per day</option>
																<option value="15-20 per day">15-20 per day</option>
																<option value="20-25 per day">20-25 per day</option>
																<option value=">25 per day">>25 per day</option>
															<?php } ?>
														</select>
													</dd><br>

													<dt>Hypertension:</dt><br> 
													<dd>
														<?php if($checkPatient[0]['hyper_cond']=="2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio1" value="2" name="se_hyper" checked="">
																<label for="inlineRadio1"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio2" value="1" name="se_hyper">
																<label for="inlineRadio2"> No </label>
															</div>
														<?php } else if($checkPatient[0]['hyper_cond']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio1" value="2" name="se_hyper">
																<label for="inlineRadio1"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio2" value="1" name="se_hyper"  checked="">
																<label for="inlineRadio2"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio1" value="2" name="se_hyper">
																<label for="inlineRadio1"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="hyperCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio2" value="1" name="se_hyper">
																<label for="inlineRadio2"> No </label>
															</div>
														<?php } ?>
													</dd><br>									
													
												</dl>
											</div>
										
											<div class="col-lg-6">
												<dl>													
													
													<dt>Alcohol:</dt><br> 
													<dd>
														<select class="form-control alcoholCondtion" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="se_alcoholic" name="se_alcoholic">
															<?php if($checkPatient[0]['alcoholic'] != ""){ ?>
																<option value="Select" <?php if($checkPatient[0]['alcoholic']=="Select"){ ?>selected<?php } ?>>Select</option>
																<option value="No Alcohol" <?php if($checkPatient[0]['alcoholic']=="No Alcohol"){ ?>selected<?php } ?>>No Alcohol</option>
																<option value="1 unit" <?php if($checkPatient[0]['alcoholic']=="1 unit"){ ?>selected<?php } ?>>1 unit</option>
																<option value="1.5 unit" <?php if($checkPatient[0]['alcoholic']=="1.5 unit"){ ?>selected<?php } ?>>1.5 unit</option>
																<option value="1.7 unit" <?php if($checkPatient[0]['alcoholic']=="1.7 unit"){ ?>selected<?php } ?>>1.7 unit</option>
																<option value="2 unit" <?php if($checkPatient[0]['alcoholic']=="2 unit"){ ?>selected<?php } ?>>2 unit</option>
																<option value="2.1 unit" <?php if($checkPatient[0]['alcoholic']=="2.1 unit"){ ?>selected<?php } ?>>2.1 unit</option>
																<option value=">2.1 unit" <?php if($checkPatient[0]['alcoholic']==">2.1 unit"){ ?>selected<?php } ?>>>2.1 unit</option>																
															<?php }else{ ?>
																<option value="Select">Select</option>
																<option value="No Alcohol">No Alcohol</option>
																<option value="1 unit">1 unit</option>
																<option value="1.5 unit">1.5 unit</option>
																<option value="1.7 unit">1.7 unit</option>
																<option value="2 unit">2 unit</option>
																<option value="2.1 unit">2.1 unit</option>
																<option value=">2.1 unit">>2.1 unit</option>
															<?php } ?>
														</select>
													</dd><br>

													<dt>Diabetes:</dt><br> 
													<dd>
														<?php if($checkPatient[0]['diabetes_cond']=="2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio3" value="2" name="se_diabets" checked="">
																<label for="inlineRadio3"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio4" value="1" name="se_diabets">
																<label for="inlineRadio4"> No </label>
															</div>
														<?php } else if($checkPatient[0]['diabetes_cond']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio3" value="2" name="se_diabets">
																<label for="inlineRadio3"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio4" value="1" name="se_diabets"  checked="">
																<label for="inlineRadio4"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio3" value="2" name="se_diabets">
																<label for="inlineRadio3"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="diabetesCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="inlineRadio4" value="1" name="se_diabets">
																<label for="inlineRadio4"> No </label>
															</div>
														<?php } ?>
													</dd><br>

												</dl>
											</div>

										</div>

										<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
											<div class="col-lg-6">
												<dl>
													<dt>BP:</dt><br>
													<dd>
														<?php if($checkPatient[0]['pat_bp']=="2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_2" value="2" name="pat_bp" checked="">
																<label for="bp_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_1" value="1" name="pat_bp">
																<label for="bp_1"> No </label>
															</div>
														<?php } else if($checkPatient[0]['pat_bp']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_2" value="2" name="pat_bp">
																<label for="bp_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_1" value="1" name="pat_bp"  checked="">
																<label for="bp_1"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_2" value="2" name="pat_bp">
																<label for="bp_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="bpCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="bp_1" value="1" name="pat_bp">
																<label for="bp_1"> No </label>
															</div>
														<?php } ?>
													</dd><br>
													
													<dt>Thyroid:</dt><br>
													<dd>
														<?php if($checkPatient[0]['pat_thyroid'] == "2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid2" value="2" name="pat_thyroid" checked="">
																<label for="thyroid2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid1" value="1" name="pat_thyroid">
																<label for="thyroid1"> No </label>
															</div>
														<?php } else if($checkPatient[0]['pat_thyroid']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid2" value="2" name="pat_thyroid">
																<label for="thyroid2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid1" value="1" name="pat_thyroid"  checked="">
																<label for="thyroid1"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid2" value="2" name="pat_thyroid">
																<label for="thyroid2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="thyroidCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="thyroid1" value="1" name="pat_thyroid">
																<label for="thyroid1"> No </label>
															</div>
														<?php } ?>
													</dd><br>

													<dt>Epilepsy:</dt><br>
													<dd>
														<?php if($checkPatient[0]['pat_epilepsy'] == "2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_2" value="2" name="pat_epilepsy" checked="">
																<label for="epilepsy_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_1" value="1" name="pat_epilepsy">
																<label for="epilepsy_1"> No </label>
															</div>
														<?php } else if($checkPatient[0]['pat_epilepsy']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_2" value="2" name="pat_epilepsy">
																<label for="epilepsy_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_1" value="1" name="pat_epilepsy"  checked="">
																<label for="epilepsy_1"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_2" value="2" name="pat_epilepsy">
																<label for="epilepsy_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="epilepsyCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="epilepsy_1" value="1" name="pat_epilepsy">
																<label for="epilepsy_1"> No </label>
															</div>
														<?php } ?>
													</dd><br>

												</dl>
											</div>

											<div class="col-lg-6">
												<dl>
													<dt>Asthama:</dt><br>
													<dd>
														<?php if($checkPatient[0]['pat_asthama']=="2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_2" value="2" name="pat_asthama" checked="">
																<label for="asthama_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_1" value="1" name="pat_asthama">
																<label for="asthama_1"> No </label>
															</div>
														<?php } else if($checkPatient[0]['pat_asthama']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_2" value="2" name="pat_asthama">
																<label for="asthama_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_1" value="1" name="pat_asthama"  checked="">
																<label for="asthama_1"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_2" value="2" name="pat_asthama">
																<label for="asthama_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="asthamaCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="asthama_1" value="1" name="pat_asthama">
																<label for="asthama_1"> No </label>
															</div>
														<?php } ?>
													</dd><br>
													
													<dt>Cholestrol:</dt><br>
													<dd>
														<?php if($checkPatient[0]['pat_cholestrole'] == "2"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_2" value="2" name="pat_cholestrole" checked="">
																<label for="cholestrole_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_1" value="1" name="pat_cholestrole">
																<label for="cholestrole_1"> No </label>
															</div>
														<?php } else if($checkPatient[0]['pat_cholestrole']=="1"){ ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_2" value="2" name="pat_cholestrole">
																<label for="cholestrole_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_1" value="1" name="pat_cholestrole"  checked="">
																<label for="cholestrole_1"> No </label>
															</div>
														<?php } else { ?>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_2" value="2" name="pat_cholestrole">
																<label for="cholestrole_2"> Yes </label>
															</div>
															<div class="radio radio-info radio-inline">
																<input type="radio" class="cholestrolCondition" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="cholestrole_1" value="1" name="pat_cholestrole">
																<label for="cholestrole_1"> No </label>
															</div>
														<?php } ?>
													</dd><br>

												</dl>
											</div>
										</div><br>

										<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
											<h4>Drug allergy</h4>
											<div class="input-group">										
												<input type="text" placeholder="Drug allergy ..." data-patient-id="<?php echo $checkPatient[0]['patient_id']; ?>" data-doc-id="<?php echo $getDocInfo[0]['ref_id']; ?>" id="get_allergy" name="searchDrugAllergy" value="" class="form-control input-lg searchAllergy" tabindex="5">
												<div class="input-group-btn">
													<button class="btn btn-lg btn-primary"  name="" type="button">
														ADD
													</button>
												</div>
											</div><br>
											<div class="input-group">
												<div id="drugAllergyBefore">
													<?php 
														$getAllergyRes= mysqlSelect("*","doc_patient_drug_allergy_active","patient_id='".$checkPatient[0]['patient_id']."' and md5(doc_id) ='".$_GET['d']."' and doc_type='1' and status='0'","","","","");
														if(!empty($getAllergyRes)){
															while(list($key, $value) = each($getAllergyRes)){ 
																echo "<span class='tag label label-primary m-r'>" . $value['generic_name'] . "<a data-role='remove' class='text-white del_allergy m-l' data-drug-allergy-id='".$value['allergy_id']."'>x</a></span>";
															}
														} //end while 
													?>
												</div>
												<div id="drugAllergyAfter">
												</div>
											</div><br>
										</div><br><br>

										<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
											<h4>Family History</h4>
											<div class="input-group">				
												<?php
													$last_five_history = mysqlSelect("family_history_id","doctor_frequent_family_history","md5(doc_id)='".$_GET['d']."' and doc_type='1'","freq_count DESC","","","5");
																	
													if(COUNT($last_five_history)>0) { ?>
														<label>Recently used:  </label>
														<?php									
															while(list($key_history, $value_history) = each($last_five_history)){
																$getHistory= mysqlSelect("family_history_id,family_history","family_history_auto","family_history_id='".$value_history['family_history_id']."'");
														?>
																<a class="btn btn-xs btn-white m-l get_histoy_prior" data-history-id="<?php echo $getHistory[0]['family_history_id']; ?>" data-patient-id="<?php echo $checkPatient[0]['patient_id']; ?>" data-doc-id="<?php echo $getDocInfo[0]['ref_id']; ?>"><code> <?php echo $getHistory[0]['family_history']; ?></code></a>
														<?php }
													} 
												?>
											</div><br>
											<div class="input-group">										
												<input type="text" placeholder="Add / Search here..." data-patient-id="<?php echo $checkPatient[0]['patient_id']; ?>" data-doc-id="<?php echo $getDocInfo[0]['ref_id']; ?>" id="get_history_abuse" name="searchDrugAbuse" value="" class="form-control input-lg searchDrugAbuse" tabindex="5">
												<div class="input-group-btn">
													<button class="btn btn-lg btn-primary"  name="" type="button">
														ADD
													</button>
												</div>										
											</div><br>
											<div class="input-group">
												<div id="familyHistoryBefore">
													<?php
														$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","md5(a.doc_id)='".$_GET['d']."' and a.patient_id='".$checkPatient[0]['patient_id']."' and a.doc_type='1' and a.status='0'","","","","");

														while(list($key, $value) = each($getHistoryRes)){ 
															echo "<span class='tag label label-primary m-r'>" . $value['family_history'] . "<a data-role='remove' class='text-white del_history m-l' data-history-id='".$value['family_active_id']."'>x</a></span>";
														}
													?>	
												</div>								
												<div id="familyHistoryAfter">
												</div>
											</div><br>
										</div><br>
									
										<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
											<div class="col-lg-6">
												<dl>
													<dt>Previous Interventions</dt><br> <dd><textarea class="form-control prevIntervent" id="prev_inter" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>"  name="prev_inter" rows="2"><?php echo $checkPatient[0]['prev_inter']; ?></textarea></dd><br>
													<dt>Other Details</dt><br> <dd><textarea class="form-control otherDetail" id="other_details" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="other_details" rows="2"><?php echo $checkPatient[0]['other_details']; ?></textarea></dd><br>
												</dl>
											</div>
											<div class="col-lg-6">	
												<dl>
													<dt>Stroke or known neurological issues</dt><br> <dd><textarea class="form-control neuroIssue" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" id="neuro_issue" name="neuro_issue" rows="2"><?php echo $checkPatient[0]['neuro_issue']; ?></textarea></dd><br>
													<dt>Known kidney issues</dt><br> <dd><textarea class="form-control kidneyIssue" id="kidney_issue" data-patient-id="<?php echo md5($checkPatient[0]['patient_id']); ?>" name="kidney_issue" rows="2"><?php echo $checkPatient[0]['kidney_issue']; ?></textarea></dd><br>
												</dl>
											</div>
										</div><br>

										<!--
											<h4><strong>HyperTension: </strong> <?php echo $hyperStatus; ?></h4>
											<h4> <strong>Diabetes: </strong> <?php echo $diabetesStatus; ?></h4>
											<h4> <strong>Smoking: </strong> <?php echo $checkPatient[0]['smoking']; ?></h4>
											<h4> <strong>Alcohol: </strong> <?php echo $checkPatient[0]['alcoholic']; ?></h4>
											<h4> <strong>Drug allergy: </strong>
												<?php 
													$getAllergyRes= mysqlSelect("*","doc_patient_drug_allergy_active","md5(patient_id)='".$_GET['p']."' and md5(doc_id) ='".$_GET['d']."' and doc_type='1' and status='0'","","","","");
													if(!empty($getAllergyRes)){
														while(list($key, $value) = each($getAllergyRes)){ 
															echo $value['generic_name'] ." , ";
														}
													} //end while 
												?>
											</h4>
											<h4> <strong>Drug Abuse: </strong> 
												<?php 
													$getDrugRes= mysqlSelect("b.drug_abuse as drug_abuse,a.drug_active_id as drug_active_id","doc_patient_drug_active as a left join drug_abuse_auto as b on a.drug_abuse_id=b.drug_abuse_id","md5(a.doc_id)='".$_GET['d']."' and md5(a.patient_id)='".$_GET['p']."' and a.doc_type='1' and a.status='0'","","","","");
													if(!empty($getDrugRes)){
														while(list($key, $value) = each($getDrugRes)){ 
															echo $value['drug_abuse']." , ";
														}
													} //end while 
												?>
											</h4>
											<h4> <strong>Family History: </strong> 
												<?php 
													$getHistoryRes= mysqlSelect("b.family_history as family_history,a.family_active_id as family_active_id","doc_patient_family_history_active as a left join family_history_auto as b on a.family_history_id=b.family_history_id","md5(a.doc_id)='".$_GET['d']."' and md5(a.patient_id)='".$_GET['p']."' and a.doc_type='1' and a.status='0'","","","","");

													while(list($key, $value) = each($getHistoryRes)){ 
														echo $value['family_history']." , ";
													}
												?>	
											</h4>
											<h4> <strong>Previous Interventions: </strong><?php echo $checkPatient[0]['prev_inter']; ?></h4>
											<h4> <strong>Other Details: </strong> <?php echo $checkPatient[0]['other_details']; ?></h4>
											<h4> <strong>Stroke or known neurological issues: </strong> <?php echo $checkPatient[0]['neuro_issue']; ?></h4>
											<h4> <strong>Known kidney issues: </strong> <?php echo $checkPatient[0]['kidney_issue']; ?></h4>
										-->
									</div>
								</div>

								<div id="Report" class="tabcontent">						 
									<h4><span class="text-navy">Hello <?php echo $checkPatient[0]['patient_name']; ?> !!!</span>
									If you have any medical report, then please upload here</h4>

									<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="patient_page_add_details.php"  name="frmAddPatient" id="frmAddPatient">
										<input type="hidden" name="patient_id" value="<?php echo $checkPatient[0]['patient_id']; ?>">
										<input type="hidden" name="doc_id" value="<?php echo $appointmentResult[0]['pref_doc']; ?>">
										<input type="hidden" name="trans_id" value="<?php echo $appointmentResult[0]['appoint_trans_id']; ?>">

										<div class="row" style="margin: 10px;">	
											<label><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
						
											<div class="form-group col-lg-12">
												<div class="file-loading">
													<input id="file-5" name="file-5[]" class="file" type="file" required multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
												</div>
											</div>
										</div>

										<div class="row" id="image_preview"  style="margin: 10px;"></div>

										<div class="row" style="margin: 10px;">
											<button type="submit" name="addAttachments" class="btn btn-primary block full-width m-b ">CLICK HERE TO ATTACH REPORTS</button>
										</div>
									</form>
								</div>

								<script>
									function openCity(evt, cityName) {
										var i, tabcontent, tablinks;
										tabcontent = document.getElementsByClassName("tabcontent");
										for (i = 0; i < tabcontent.length; i++) {
											tabcontent[i].style.display = "none";
										}
										tablinks = document.getElementsByClassName("tablinks");
										for (i = 0; i < tablinks.length; i++) {
											tablinks[i].className = tablinks[i].className.replace(" active", "");
										}
										document.getElementById(cityName).style.display = "block";
										evt.currentTarget.className += " active";
									}
								</script>
							</div>
							
						</div>			
						<!-- top header End-->
				
					</div>
					
					<div class="modal inmodal" id="patDtl" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="patient_page_add_details.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="doc_id" value="<?php echo $appointmentResult[0]['pref_doc']; ?>">
									<input type="hidden" name="trans_id" value="<?php echo $appointmentResult[0]['appoint_trans_id']; ?>">

									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
										<?php if(!empty($checkPatient[0]['patient_image'])){ ?><img src="<?php echo HOST_URL_PREMIUM; ?>patientImage/<?php echo $checkPatient[0]['patient_id']; ?>/<?php echo $checkPatient[0]['patient_image']; ?>" width="100" class="img-thumbnail" /><?php } else { ?><img src="<?php echo HOST_MAIN_URL; ?>assets/img/user_noimg.png" width="100" class="img-thumbnail" /><?php } ?>
										<center><input type="file" name="txtPhoto" style="margin-left:50px;"></center>
										<h4 class="modal-title"><?php echo $checkPatient[0]['patient_name']; ?></h4>
										<small class="font-bold">Patient Profile</small>
									</div>

									<input type="hidden" name="patient_id" value="<?php echo $checkPatient[0]['patient_id']; ?>">
									
									<div class="modal-body">
										<div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="<?php if($_GET['p']==="0"){ echo $_GET['n']; } else { echo $checkPatient[0]['patient_name']; } ?>" class="form-control" required></div>                                 
											<div class="row"><div class="form-group"><label class="col-sm-2 control-label"><?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ echo "Age"; } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
												<div class="col-sm-4">
													<?php if($checkSetting[0]['patient_age_type']=="0" || $checkSetting==false){ ?>
														<input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $checkPatient[0]['patient_age']; ?>" class="form-control">
													<?php } else if($checkSetting==true && $checkSetting[0]['patient_age_type']=="1") { ?>
														<input id="dateadded" name="date_birth" type="text" <?php if($checkPatient[0]['DOB']!="0000-00-00" && $_GET['p']!="0"){ ?>value="<?php echo date('d/m/Y',strtotime($checkPatient[0]['DOB']));?>"<?php } else if($_GET['p']=="0"){ ?>value=""<?php } ?> placeholder="DD/MM/YYYY" class="form-control" >
													<?php } ?>
												</div>
												<label class="col-sm-2 control-label">Gender</label>
												<div class="col-sm-4">
													<?php if($checkPatient[0]['patient_gen']=="1"){ ?>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="checked">
															<label for="inlineRadio1"> Male </label>
														</div>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio2" value="2" name="se_gender">
															<label for="inlineRadio2"> Female </label>
														</div>
													<?php } else if($checkPatient[0]['patient_gen']=="2") { ?>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio1" value="1" name="se_gender">
															<label for="inlineRadio1"> Male </label>
														</div>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio2" value="2" name="se_gender" checked="">
															<label for="inlineRadio2"> Female </label>
														</div>
													<?php } else { ?>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio1" value="1" name="se_gender">
															<label for="inlineRadio1"> Male </label>
														</div>
														<div class="radio radio-info radio-inline">
															<input type="radio" id="inlineRadio2" value="2" name="se_gender">
															<label for="inlineRadio2"> Female </label>
														</div>
													<?php } ?>
												</div>
											</div>
										</div><br>

										<div class="form-group">
											<label class="col-sm-3 control-label">Height(Centimeter)</label>
											<div class="col-sm-3">
												<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="<?php echo $checkPatient[0]['height_cm']; ?>" class="form-control" maxlength="3">								
											</div>
											<label class="col-sm-2 control-label">Weight(Kgs)</label>
											<div class="col-sm-3">
												<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="<?php echo $checkPatient[0]['weight']; ?>" class="form-control">								
											</div>
										</div>
										
										<div class="form-group"><label>Country</label> 
											<select class="form-control" name="se_country" name="se_country">
												<option value="India" <?php echo (!isset($checkPatient[0]['pat_country']) ? 'selected' : ($checkPatient[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
												<?php
													$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?>
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($checkPatient[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
															<?php echo stripslashes($CountryList['country_name']);?>
														</option>
												<?php $i++; }?>
											</select>
										</div>

										<div class="form-group"><label>State</label> 
											<select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
												<option value="">Select State</option>
												<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
												?>
													<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($StateList['state_name'] == $checkPatient[0]["pat_state"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>
												<?php } ?>
											</select>
										</div>
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php if(!empty($checkPatient[0]['patient_loc'])) { echo $checkPatient[0]['patient_loc'];} else { echo $checkPatient[0]['patient_loc']; } ?>" class="form-control"></div>										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="<?php echo $checkPatient[0]['patient_addrs']; ?>" class="form-control"></div>
										<div class="form-group"><label>Mobile</label> <input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $checkPatient[0]['patient_mob']; ?>" class="form-control"></div>
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="<?php echo $checkPatient[0]['patient_email']; ?>" class="form-control"></div>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
										<button type="submit" name="update_patient" class="btn btn-primary">UPDATE</button>
										
									</div>
								</form>
							</div>
						</div>
					</div>   

					<div class="modal inmodal" id="ModalPopup" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content animated bounceInRight" style="margin-right: 1px;margin-left:1px;">
								<div class="modal-header">
									<h2 class="font-bold">Terms and Conditions</h2>
								</div>
								
								<div class="modal-body" style=" height: 400px; overflow-y: auto;">
									<p>
										Medisensehealth.com, Medisensepractice.com, Medisense Healthcare Solutions Pvt Ltd, etc. products and services are NOT FOR MEDICAL EMERGENCIES OR URGENT SITUATIONS. IF YOU THINK YOU HAVE A MEDICAL EMERGENCY, IMMEDIATELY CALL emergency services. <br/>
										Please be aware that we may ask you to confirm your identity in a consultation by providing suitable forms of verification which may include a Photo ID (Passport, Driving Licence, etc.). <br/>
										<strong>Customer Services can be reached by email : email: medisensedev@medisense.me , or online through the website: medisensepractice.com</strong> <br/>
										This Policy dated 03. July 2019 should be read in conjunction with Data Privacy Policy. <br/>
										<strong>WHAT IS Medisense Healthcare Solutions Pvt Ltd: </strong><br/>
										Medisensehealth.com and Medisensepractice.com and others, are a brand of Medisense Healthcare Solutions Pvt Ltd, a priGSTe technology company which enables patients to hold on-demand and by appointment; secure, high-definition, face-to-face, real- time; video, telephone, and online-chat/message consultations with “Healthcare Providers”; the providers are registered doctors with the national or regional medical council. Upon a Healthcare Provider to register with Medisense Healthcare Solutions Pvt Ltd, his or her certificates are verified. This is part of the Medisense Healthcare Solutions Pvt Ltd Quality surveillance procedures. <br/>
										Medisense Healthcare Solutions Pvt Ltd products are not intended to replace the relationship with a patient’s doctor. It is a complementary service offering the advantages of convenience and accessibility to a broad range of healthcare services. Medisense Healthcare Solutions Pvt Ltd products are not suited for emergency care. <br/>
										Medisense Healthcare Solutions Pvt Ltd charges via a monthly or annual subscription (“Subscription”) or pay-as-you-go model (“Self-Pay”) or is paid by patients’ healthcare insurer, employer or membership group (“Group/Insurer”) or using a simple pre-paid coupon code (“Coupon”). <br/>
										The Medisense Telemedicine Service is provided by Medisense Healthcare Solutions Pvt Ltd (herein after “Medisense Healthcare Solutions Pvt Ltd”), a company registered in the Indian State of Karnataka. <br/>
										<strong>OUR CONTRACT WITH YOU WHEN YOU USE OUR SERVICES, APPS AND WEBSITES </strong><br/>
										You may access Our Apps and Websites subject to Our Terms and Conditions below. Medisense Healthcare Solutions Pvt Ltd is available in the Apple AppStore and through Google Play Store. <br/>
										On desktop and laptop, we currently support a wide range or latest browsers. Details can be requested to our technical team at Medisensedev@medisense.me. 
										Your agreement to comply with and be bound by these Terms and Conditions (including our <b>End User License Agreement</b> and Terms of Service) and Privacy Policy is deemed to occur upon first use of Our Apps and Websites and by explicit agreement upon Registration. Further, you will be required to explicitly accept Our most recent <b>Terms and Conditions</b>, <b>Privacy Policy</b> and <b>Medical Consent</b> (collectively “Our Policies”) when accessing Our Consultation Services. <br/>
										By explicitly accepting Our Policies, you authorize Medisense Healthcare Solutions Pvt Ltd to use your Personal Information including your Health Information and details of all your past Consultations, collectively your <b>“Electronic Health Record”</b> which includes <b>“Personal Data”</b> and <b>“Sensitive Personal Data”</b>. <br/>
										You also explicitly consent that Medisense Healthcare Solutions Pvt Ltd may release your Electronic Health Record for the purposes of your treatment and ongoing healthcare to your primary care doctor (General Practitioner or “GP”), preferred (nominated) pharmacy, next of kin and/or persons authorized to make decisions in relation to your healthcare. If you do not wish us to release your Electronic Health Record, please notify the Doctor in your consultation. You will still be deemed to have explicitly agreed to all other parts of Medisense Healthcare Solutions Pvt Ltd Policies. <br/>
										If you are entitled to free or subsidized consultations through your Insurer, Employer or a Group Membership, we will share with them your date of consultation, name, contact details, date of birth, and policy or membership number to assist in reimbursement. We will not share information from consultation without your explicit consent. Please contact Medisensedev@medisense.me to request for assistance. <br/>
										<strong>INTRODUCTION </strong><br/>
										When accessing our Medisense Healthcare Solutions Pvt Ltd Services using Mobile Applications and Websites we ask that you make, a few commitments to us to ensure that you receive care personalized to you, and to protect you against the risk of receiving treatment that is inappropriate or unsafe. For feedback: Medisensedev@medisense.me. <br/>
										We think it is important for you to read and fully understand those commitments, so we have outlined the most important immediately below. <br/>
										We ask that: <br/>
										•	you commit you are the person registering and are not presenting yourself as someone else or acting as an agent for somebody else; <br/>
										•	you will only register once with Medisense Healthcare Solutions Pvt Ltd unless you are registering for a dependent or a person in your care; <br/>
										•	you agree to protect the privacy and security of all Users (Patients, Doctors, Our Healthcare Providers and Medisense Healthcare Solutions Pvt Ltd Employees) and not to make independent recordings of consultations, capture images, or take screen shots or record voice or data or similar. All necessary details of your consultations are stored for your benefit in your own Consult History and Patient Information (Personal and Health Information) (collectively “Electronic Health Record”). We may record your telephone calls with Our Medisense Healthcare Solutions Pvt Ltd Care Team who will assist you with general and technical enquiries;<br/>
										•	you commit that any information you provide to Medisense Healthcare Solutions Pvt Ltd and its Healthcare Providers will be accurate and not to omit anything of relevance or importance, which for the avoidance of doubt includes, current and past, medications and conditions;<br/>
										•	you will maintain and ensure that your Patient Information (Personal and Health), is current and correct at the time of any appointment or consultation;<br/>
										•	you will keep your Patient Information (Personal and Health), current and add any relevant advice, opinions, tests, prescriptions or imaging you have received from all sources;<br/>
										•	you will comply with all instructions and advice given to you by Healthcare Providers, subcontractors and third parties for your continuing care;<br/>
										•	you will comply with the manufacturers’ instructions and guidance as to the use of any medicine (OTC or Prescription) including method, timing, dosages, potential adverse reactions or side effects, expiry dates etc., and the instructions of any Pharmacist dispensing following a consultation;<br/>
										•	you will promptly report any side effects of any prescription to an appropriate Healthcare Provider; and<br/>
										•	you agree, Medisense Healthcare Solutions Pvt Ltd may use Anonymised or PsINDIAdo-Anonymised Data (collectively “De-identified Data”) as defined in the Data Protection Acts (“DPA”) in the improvement of its Products and Services. In this context, De- identified Data refers to data from which the patient cannot be individually identified.<br/>
										•	If you have any concerns or are in any doubt regarding any information or Advice you have received or failed to receive via Our Products and Services, or regarding your health, wellbeing or any conditions, you will immediately seek a further medical opinion from a registered doctor or suitable Healthcare Provider not affiliated with Medisense Healthcare Solutions Pvt Ltd and/or shall utilise the emergency services as necessary.<br/>
										<strong>TERMS AND CONDITIONS (INCORPORATING OUR END USER LICENCE AGREEMENT AND TERMS OF SERVICE) IN DETAIL 
										DEFINITIONS AND INTERPRETATION </strong><br/>
										•	Definitions and Interpretation In these Terms and Conditions, unless the context otherwise requires, the following expressions have the following meanings:<br/>
										1.1. “Medisense Healthcare Solutions Pvt Ltd /Our/Us” means Medisense Healthcare Solutions Pvt Ltd, a Company Limited in Liability under the Laws of the India State of Karnataka; <br/>
										1.2. “Account” means an account required for a User to access and/or use certain areas of Our Apps and Websites; <br/>
										1.3. “Advice” means any communication, diagnosis, discharge, advice or other Services in any form provided or facilitated by Medisense Healthcare Solutions Pvt Ltd or its Healthcare Providers through the Apps and Websites; <br/>
										1.4. “Apps and Websites” means the Application (IOS or Android) or Website, including Mobile Website that you are currently using including medisensepractice.com, Medisensehealth.com, Medisensepractice.com etc. and any sub-domains of these websites unless expressly excluded by their own terms and conditions; <br/>
										1.5. “Content” means all files, documents, text, images, audio, video, scripts, code, software, databases and any other form of information capable of being stored in an application (IOS or Android) or on a computer that appears on, or forms part of, Our Apps and Websites; <br/>
										1.6. “Contract” means a contract for the purchase and sale of Products and Services, as explained in Clause 17; <br/>
										1.7. “Data Protection Acts” (DPA) means the General Data Protection Regulation (GDPR) and any regulations, codes and guidance issued by the Data Protection Commissioner (or any official who may succeed either of him or her) and any other applicable data privacy laws and regulations. It shall also include any superseding data protection legislation that comes into force during the term of this Agreement including Regulation 2016/649 of the Indian Parliament and of the Council, General Data Protection Regulation (“GDPR”); <br/>
										1.8. “De-identified Data” is as defined in the Data Protection Acts including Anonymised and PsINDIAdo-Anonymised Data. De-identified Data refers to data from which the patient cannot be individually identified. Our use of De-identified Data is in conformity with best practice and GDPR and only in the improvement and promotion of Medisense Healthcare Solutions Pvt Ltd Products and Services and in the development of its business including with Third Parties; <br/>
										1.8.1. “Personal Data” means the definition given to it in the Data Protection Acts (“DPA”) including any information relating to an identified or identifiable natural person (“Data Subject”). An identifiable natural person is one who can be identified, directly or indirectly, in particular, by reference to an identifier such as a name, an identification number, location data, an online identifier or to one or more factors specific to the physical, physiological, genetic, mental, economic, cultural or social identity of that natural person; and <br/>
										1.8.2. “Sensitive Personal Data” means the definition given to it in the Data Protection Acts (“DPA”) including Data, revealing racial or ethnic origin, political opinions, religious or philosophical beliefs, trade-union membership; data concerning health or sex life and sexual orientation; genetic data or biometric data. <br/>
										1.9. “Electronic Health Record” means your account in our cloud-based storage holding your Personal Information and your Consultation record (“Consult History”);<br/> 
										1.10. “Healthcare Providers” means any Registered Healthcare Professional providing Medisense Healthcare Solutions Pvt Ltd Services through its Apps and Websites including registered General Practitioners. The registration is verified with competent National or State authorities; <br/>
										1.11. “Order” means your order for the Products and Services which may or may not require a payment; <br/>
										1.12. “Order Confirmation” means Our Medisense Healthcare Solutions Pvt Ltd acceptance and confirmation of your Order. <br/>
										1.13. “Products” means any physical item Ordered including but not limited to a Test, Prescription or Activity Device; <br/>
										1.14. “Products and Services” means the Products and Services provided by or via Medisense Healthcare Solutions Pvt Ltd to you as specified in your Orders; <br/>
										1.15. Karnataka and Indian Law means the Data Protection Acts and the India (Consumer Information, Cancellation and Other Rights) Regulations, (Distance Selling Regulations). <br/>
										1.16. “Services” means any Service to a User including Advice; <br/>
										1.17. “Third Parties” means any entities providing Products and Services through Medisense Healthcare Solutions Pvt Ltd or its Apps and Websites, including independent healthcare providers, subcontractors, agencies and suppliers other than Medisense Healthcare Solutions Pvt Ltd and its subsidiaries or affiliates; <br/>
										1.18. “Third Party Apps and Websites” means Apps and Websites not owned by Medisense Healthcare Solutions Pvt Ltd or any of its subsidiaries or affiliates; <br/>
										1.19. “User” means any patient, customer, third party, healthcare provider, General Practitioners that accesses the Apps and Websites or orders Products and Services from Medisense Healthcare Solutions Pvt Ltd or its subsidiaries or affiliates and is not directly employed by Medisense Healthcare Solutions Pvt Ltd or acting during their Medisense Healthcare Solutions Pvt Ltd employment; and <br/>
										1.20. “User Content” means any content submitted to Our Medisense Healthcare Solutions Pvt Ltd Apps and Websites by Users including, but not limited to Sensitive Personal Data including name, date of birth, state of health, ailments, allergies, diseases or disabilities, medications, physical and mental characteristics, past medical records, photographs, family information, medical diagnoses and notes, details of past consultations; and financial information such as credit and debit card numbers. <br/>
										<strong>REGISTRATION, FIRST AND SUBSEQUENT USE </strong><br/>
										•	What information we collect, store and process when your Register with Medisense Healthcare Solutions Pvt Ltd and use our Services <br/>
										2.1. On Registration and first use of Our Apps and Websites we ask for certain “Personal Data” including biographical and demographic information such as; name, email, contact numbers, address, date of birth, gender and location. We may also collect insurer, employee, group or membership numbers where appropriate. <br/>
										2.2. We may also ask for “Sensitive Personal Data” including your Presenting Complaint which we will not store unless you have a Consultation. Optionally you may also share and/or store with us Medications and Allergies and your Patient Information (Personal and Medical History) in your personal Electronic Health Record. <br/>
										2.3. In order, to have a Consultation with a Doctor you must explicitly agree to our current Terms and Conditions (incorporating our End User Licence Agreement and Terms of Service), Privacy Policy and Medical Consent at which point we will store that Sensitive Personal Data in your Personal Information and your Consultation record (“Consult History”) (collectively your “Electronic Health Record”). This Sensitive Personal Data includes your medical history, symptoms, complaints, allergies and medications. Medisense Healthcare Solutions Pvt Ltd also provides the ability for your Healthcare Providers to add notes to your Electronic Health Record. Any information provided as part of a video, telephone, secure chat session and/or email consultation becomes part of your Electronic Health Record. At any point you can update your Personal Information, but we will keep a record of those changes if you have had a Consultation. <br/>
										2.4. Your agreement to comply with and be bound by these Terms and Conditions (incorporating our End User Licence Agreement and Terms of Service), Privacy Policy and Medical Consent (“Our Policies”) is deemed to occur upon your first use of Our Apps and Websites and updated at the point of a Consultation. <br/>
										<strong>END USER LICENCE AGREEMENT FOR OUR APPS AND WEBSITES </strong><br/>
										•	Access to Our Apps and Websites <br/>
										3.1. You may access Our Apps and Websites from anywhere subject to Karnataka and Indian Law (including the Cross-Border Directive) and the applicable laws in your location. It is your responsibility to confirm that you comply with, all applicable laws and regulations. <br/>
										3.2. On desktops and laptops, we currently support some of the latest Internet Browsers. If help is required please contact Medisensedev@medisense.me. <br/>
										3.3. Our Services may not work well with older versions of browsers. Please check for regular updates, including security updates from your device manufacturer, operating system and browser. Please download Our Apps and Androids in the Apple and Google Play Stores. <br/>
										3.4. Access to Our Apps and Websites is provided “as is” and on an “as available” basis. Medisense Healthcare Solutions Pvt Ltd may alter, suspend or discontinue Our Apps and Websites (or any part of it) at any time and without notice. Medisense Healthcare Solutions Pvt Ltd will not be liable to you in any way if Our Apps and Websites (or any part of it) is unavailable at any time and for any period. Any technical issues are to be raised to Medisensedev@medisense.me. <br/>
										•	Accounts <br/>
										4.1.Certain parts of Our Apps and Websites (including the ability to purchase Products and Services may require that you create (“register”) an Account to access them. <br/>
										4.2. You may not register an Account if you are under 16 years of age or minimum legal age. If you are under 16 years of age or minimum legal age and wish to use parts of Our Apps and Websites that require an Account, your parent, guardian or carer should register their own “Master” Account and add you as their Page of 20 74 
										Dependant. Dependants must only access the Master Account with the supervision of their parent, guardian or carer. <br/>
										4.3. When creating an Account, the information you provide must be accurate and complete. If any of your information changes later, you can update it in your Patient Information page in our Apps and Websites. Falsely provided information may lead to suspension of the Account. <br/>
										4.4. Medisense Healthcare Solutions Pvt Ltd require that you choose a strong password for your Account. It is recommended to combine characters in lowercase and uppercase as well as numbers and not to use simple passwords such as: “Password” or “123457” <br/>
										4.5. It is your responsibility to keep your password safe. You must not share your password or Account with anyone else. If you believe your Account has or is used without your permission, please contact Medisense Healthcare Solutions Pvt Ltd immediately at medisensedev@INDIAroTelemedicne.INDIA . Medisense Healthcare Solutions Pvt Ltd will not be liable for any unauthorized access to your Account.<br/> 
										4.6. Any personal information you provide or store in your Account will be collected, used, and held in accordance with Our data retention policies which follow Indian Law as appropriate and Industry best practices. <br/>
										4.7. If you wish to close your Account, you may do so at any time, contacting us by email at Medisensedev@medisense.me. Closing your Account will result in the removal of your access to your Electronic Health Record. It will not remove any information we have already collected which we are required to maintain in line with Our data retention policies, best practice, the relevant Data Protection Acts and to use in De-identified form. <br/>
										•	Intellectual Property Rights and Trade Marks <br/>
										5.1.Medisense Healthcare Solutions Pvt Ltd and its Apps and Websites contains, embodies and is based upon worldwide patented or patentable inventions, trade secrets, copyrights and other intellectual property rights (collectively “Intellectual Property Rights”) owned by or under license by Medisense Healthcare Solutions Pvt Ltd and its licensors. All Content, including User Content, is protected by applicable India and International intellectual property laws and treaties. <br/>
										5.2. All logos and trademarks on our Apps and Websites are owned by or licensed to Medisense Healthcare Solutions Pvt Ltd. Medisense Healthcare Solutions Pvt Ltd hereby reserve all rights to their respective use. <br/>
										5.3. You may not reproduce, copy, distribute, sell, rent, sub-licence, store, or in any other manner, re-use Content from Our Apps and Websites unless given express written permission to do so by Medisense Healthcare Solutions Pvt Ltd. To request authorization contact medisensedev@medisense.me <br/>
										5.4. This Agreement does not convey to you title or ownership of Medisense Healthcare Solutions Pvt Ltd Apps and Websites. You shall not remove, replace or obscure trademarks or proprietary notices contained in or displayed by Medisense Healthcare Solutions Pvt Ltd. <br/>
										5.5. Any printouts made of content obtained through Medisense Healthcare Solutions Pvt Ltd must include a recognition and Our copyright notice, ©Medisense Healthcare Solutions Pvt Ltd Limited. Medisense Healthcare Solutions Pvt Ltd is a registered trademark of Medisense Healthcare Solutions Pvt Ltd Ltd. This Agreement does not confer any license or right to use any trademark of Medisense Healthcare Solutions Pvt Ltd or its licensors or suppliers without the express written permission of Medisense Healthcare Solutions Pvt Ltd. This permission can be requested to medisensedev@medisense.me <br/>
										5.6. With these restrictions, Medisense Healthcare Solutions Pvt Ltd grants you a limited license to access and make personal use of the Apps and Websites and not to modify, any portion, except with express written consent. This license does not include any resale or commercial use of the Apps and Websites or content; any deriGSTive use of the Apps and Websites or contents; or any use of data mining, robots, or similar data gathering and extraction tools. <br/>
										5.7. The Apps and Websites or any portion of the Apps and Websites may not be reproduced, duplicated, copied, sold, resold, visited, or otherwise exploited for any commercial purpose without express written consent of Medisense Healthcare Solutions Pvt Ltd. Any unauthorized use terminates the permission or license granted by Medisense Healthcare Solutions Pvt Ltd. You may not use any logo or other proprietary graphic or trademark of Insurer, Employer or Group or Medisense Healthcare Solutions Pvt Ltd as part of the link without Medisense Healthcare Solutions Pvt Ltd’s prior permission. This permission may be requested to: medisensedev@medisense.me. <br/>
										5.8. Subject to sub-Clauses 5.6 you may not reproduce, copy, distribute, sell, rent, sub-licence, store, or in any other manner re-use Content from Our Apps and Websites unless given express written permission to do so by Medisense Healthcare Solutions Pvt Ltd. <br/>
										5.9. You may: <br/>
										5.9.1. Access, view and use Our Apps and Websites in an application (IOS or Android) or web browser  
										(including any web browsing capability built into other types of software or app); <br/>
										5.9.2. Download Our Apps and Websites (or any part of it) for caching; <br/>
										5.9.3. Print pages from Our Apps and Websites; <br/>
										5.9.4. Download extracts from pages on Our Apps and Websites; and <br/>
										5.9.5. Save pages from Our Apps and Websites for later and/or offline viewing.<br/> 
										5.10. Our status as the owner and author of the Content on Our Apps and Websites (or that of identified licensors, as appropriate) must always be acknowledged, © Medisense Healthcare Solutions Pvt Ltd Ltd. <br/>
										5.11. You may not use any Content saved or downloaded from Our Apps and Websites for commercial purposes without first obtaining a license from Medisense Healthcare Solutions Pvt Ltd (or Our Medisense Healthcare Solutions Pvt Ltd licensors, as appropriate). This does not prohibit the normal access, viewing and use of Our Apps and Websites for general information purposes. A license may be requested to medisensedev@medisense.me <br/>
										5.12. Nothing in these Terms and Conditions limits or excludes the provisions of the Copyright and Related Rights, covering the making of temporary copies; the making of personal copies for priGSTe use; research and priGSTe study; the making of copies for text and data analysis for non-commercial research; criticism, review, quotation and news reporting; caricature, parody or pastiche; and the incidental inclusion of copyright material.<br/> 
										•	User Content <br/>
										6.1. An Account is required if you wish to submit User Content. For terms and conditions pertaining to Accounts, please refer to Clause 4. <br/>
										6.2. You agree that you will be solely responsible for your User Content. Specifically, you agree, represent and warrant that you have the right to submit the User Content and that all such User Content will comply with Our Acceptable Usage Policy, detailed below in Clause 12. <br/>
										6.3. You agree that you will be liable to Medisense Healthcare Solutions Pvt Ltd and will, to the full extent permissible by law, indemnify Medisense Healthcare Solutions Pvt Ltd for any breach of the warranties given by you in these Terms and Conditions. You will be responsible for any loss or damage suffered by Medisense Healthcare Solutions Pvt Ltd Limited from a breach. <br/>
										6.4. You retain ownership of your User Content and all intellectual property rights subsisting therein. When you submit User Content you grant Medisense Healthcare Solutions Pvt Ltd an unconditional, fully transferable, royalty-free, worldwide licence to use, store, archive your User Content for the purposes of operating and promoting Our Apps and Websites and for providing you with and improving Our Medisense Healthcare Solutions Pvt Ltd and Medisense Healthcare Solutions Pvt Ltd healthcare products and services including the use of that data in anonymized form. We will not share your Personal Data with a third party other than your Healthcare Provider or without your permission other than set out in Our Privacy Policy. <br/>
										•	Disclaimers <br/>
										7.1. Apps and Websites are not suitable for any condition that should reasonably require face to face analysis, diagnosis or treatment, or for sourcing any Product and Service urgently for a medical emergency or acute condition. <br/>
										7.2. Medisense Healthcare Solutions Pvt Ltd cannot guarantee continuity of care through the same Healthcare Provider. <br/>
										7.3. The Content on Our Apps and Websites does not constitute advice on which you should rely. It is provided for general information purposes only. <br/>
										7.4. Medisense Healthcare Solutions Pvt Ltd make no representation, warranty, or guarantee that Our Services or Apps and Websites will: <br/>
										7.4.1. Meet your requirements; <br/>
										7.4.2. be of satisfactory quality; <br/>
										7.4.3. be fit for a purpose; <br/>
										7.4.4. not infringe the rights of third parties; <br/>
										7.4.5. be compatible with all software and hardware; or <br/>
										7.4.6. that it will be secure. <br/>
										7.5. Medisense Healthcare Solutions Pvt Ltd make reasonable efforts to ensure that the displayed Content on Our Apps and Websites is complete, accurate, and up-to-date. Medisense Healthcare Solutions Pvt Ltd does not make any representations, warranties or guarantees (whether express or implied) that the Content is complete, accurate, or up-to-date. <br/>
										•	Our Liability <br/>
										8.1. Medisense Healthcare Solutions Pvt Ltd is not responsible for any loss or damage caused by its Healthcare Providers (except to the extent the Healthcare Provider is an employee of Medisense Healthcare Solutions Pvt Ltd or acting within the scope of their employment), or Third Parties unless caused by the negligence, material breach or wilful default of Medisense Healthcare Solutions Pvt Ltd.<br/> 
										8.2. To the full extent permissible by law, Medisense Healthcare Solutions Pvt Ltd accepts no liability to any user for any loss or damage, whether foreseeable or otherwise, in contract, tort (including negligence), for breach of statutory duty, or otherwise, arising out of or in connection with the use of (or inability to use) Our Apps and Websites or the use of or reliance upon any Content included on Our Apps and Websites. <br/>
										8.3. To the full extent permissible by law, Medisense Healthcare Solutions Pvt Ltd exclude all representations, warranties, and guarantees (whether express or implied) that may apply to Our Apps and Websites or any Content included on Our Apps and Websites. <br/>
										8.4. Medisense Healthcare Solutions Pvt Ltd takes all reasonable steps to ensure that Our Apps and Websites are free from viruses and other malware, however Medisense Healthcare Solutions Pvt Ltd accepts no liability for any loss or damage resulting from a virus or other malware, a distributed denial of service attack, or other harmful material or event that may adversely affect your hardware, software, data or other material that occurs as a result of your use of Our Apps and Websites (including the downloading of any Content from it) or any other Apps and Websites referred to on Our Apps and Websites. <br/>
										8.5. Medisense Healthcare Solutions Pvt Ltd neither assume nor accepts responsibility or liability arising out of any disruption or non-availability of Our Apps and Websites resulting from external causes including, but not limited to, ISP equipment failure, host equipment failure, communications network failure, natural events, acts of war, or legal restrictions and censorship. <br/>
										8.6. The liability of the Company from its Products and Services whether under contract, tort/delict, statute, common law or otherwise (and including for negligence or wilful default) shall not in any circumstances exceed: <br/>
										8.6.1. the legal minimum in aggregate in relation to any products; and <br/>
										8.6.2. the legal minimum in aggregate otherwise. <br/>
										8.7. Nothing in these Terms and Conditions excludes or restricts Our liability for fraud or fraudulent misrepresentation, for death or personal injury resulting from negligence, or for any other forms of liability, which cannot be excluded or restricted by law. <br/>
										•	Your Liability <br/>
										9.1. You commit that you are the person registering and not presenting yourself as someone else or acting as an Agent or, on behalf of somebody else. <br/>
										9.2. You will only register once with Medisense Healthcare Solutions Pvt Ltd unless you are registering for a dependant or a person in your care. <br/>
										9.3. You agree to protect the privacy and security of all Users (Patients, registered Doctors, all Our Healthcare Providers and Medisense Healthcare Solutions Pvt Ltd Employees) and not to make independent recordings of consultations, to capture images or take screen shots or similar. All necessary details of your consultations are stored for your benefit in your own Consult History and Patient Information (Personal and Health History), collectively “Electronic Health Record”. <br/>
										9.4. You commit that any information you provide Medisense Healthcare Solutions Pvt Ltd and its Healthcare Providers will be accurate and not to omit anything of relevance or importance which for the avoidance of doubt includes, current and past, medications and conditions. <br/>
										9.5. That you will maintain and ensure that your Patient Information (Personal and Health History) is current and correct at the time of any appointment or consultation. <br/>
										9.6. That you will promptly notify and/or correct any part of your Patient Information (Personal and Health History), which is incomplete or inaccurate including any Advice (see below), opinions, tests, prescriptions or imaging received. <br/>
										9.7. That you will comply with all instructions and advice given to you by Healthcare Providers, subcontractors and third parties for your continuing care. <br/>
										9.8. That you will comply with the manufacturers’ instructions and guidance as to the use of any medicine (OTC or Prescription) including method, timing, dosages, potential adverse reactions or side effects, expiry dates etc., and the instructions of any Pharmacist dispensing because of, a Medisense Healthcare Solutions Pvt Ltd appointment or consultation. <br/>
										9.9. That you will promptly report any side effects of any prescription to an appropriate Healthcare Provider. <br/>
										9.10. If you have any concerns or are in any doubt regarding any information or Advice you have received or failed to receive via Our Products and Services, or regarding your health, wellbeing or any conditions, you will immediately seek a further medical opinion from a registered GP or suitable Healthcare Provider not affiliated with Medisense Healthcare Solutions Pvt Ltd and/or shall utilise the emergency services as necessary. <br/>
										•	Third Party Apps and Websites <br/>
										10.1.As a convenience to Our Users, the Apps and Websites may include links (including hypertext) to Third Party Websites or material, which is beyond Our control. When you actiGSTe any of these you will leave Our Apps and Websites and Medisense Healthcare Solutions Pvt Ltd has no control over and will accept no responsibility or liability for the material on any App and Website, which is not under the control of Medisense Healthcare Solutions Pvt Ltd. <br/>
										•	Advertising and Sponsorship <br/>
										11.1.Part of the Apps and Websites may contain advertising and sponsorship, including advertising and sponsorship by Medisense Healthcare Solutions Pvt Ltd. Advertisers and Sponsors are responsible for ensuring that material submitted for inclusion on the Website complies with relevant laws and regulations and codes. Medisense Healthcare Solutions Pvt Ltd will not be responsible for any error or inaccuracy in advertising and sponsorship material. <br/>
										11.2.To the full extent permissible by law, Medisense Healthcare Solutions Pvt Ltd accept no liability to any user for any loss or damage, whether foreseeable or otherwise, in contract, tort (including negligence), for breach of statutory duty, or otherwise, arising out of or about the use of (or inability to use) Our Apps and Websites or the use of or reliance upon any Content included on Our Apps and Websites. <br/>
										•	Viruses, Malware and Security <br/>
										12.1.Medisense Healthcare Solutions Pvt Ltd take all reasonable steps to ensure that Our Apps and Websites are secure and free from viruses and malware. Medisense Healthcare Solutions Pvt Ltd do not, however, guarantee that Our Apps and Websites are secure or free from viruses or other malware and accept no liability in respect of the same. <br/>
										12.2.You are responsible for protecting your hardware, software, data and other material from viruses, malware, and other internet security risks. You must not deliberately introduce viruses or other malware, or any other material, which is malicious or technologically harmful either to or via Our Apps and Websites. <br/>
										12.3.You must not attempt to gain unauthorized access to any part of Our Apps and Websites, the server on which Our Apps and Websites is stored, or any other server, computer, or database connected to Our Apps and Websites. <br/>
										12.4.You must not attack Our Apps and Websites by means of a denial of service attack, a distributed denial of service attack, or by any other means. <br/>
										12.5.By breaching the provisions of sub-Clauses 12.3 and 12.4, you may be committing a criminal offense. All, breaches will be reported to the relevant law enforcement authorities and Medisense Healthcare Solutions Pvt Ltd will cooperate fully with those authorities by disclosing your identity to them. Your Medisense Healthcare Solutions Pvt Ltd right to use Our Apps and Websites will cease immediately in the event of such a breach. <br/>
										•	Acceptable Usage Policy <br/>
										13.1.You may only use Our Apps and Websites in a manner that is lawful and that complies with the provisions of this Clause 13. <br/>
										13.2.You agree that Medisense Healthcare Solutions Pvt Ltd may limit, restrict or remove your right to any or all, of its Services, without reason or notice, where in Medisense Healthcare Solutions Pvt Ltd’s sole opinion your usage of the Medisense Healthcare Solutions Pvt Ltd Services exceeds Medisense Healthcare Solutions Pvt Ltd’s current Acceptable Usage Policy, as determined from time to time. <br/>
										13.3.Specifically when submitting User Content (or communicating in any other way using Our Apps and Websites), you must not submit, communicate or otherwise do anything that is; sexually explicit; obscene, deliberately offensive, hateful or otherwise inflammatory; promotes violence; promotes or assists in any form of unlawful activity; discriminates against, is in any way defamatory of, any person, group or class of persons, race, sex, religion, nationality, disability, sexual orientation, age, political beliefs or membership of “trade” organizations; is intended or otherwise likely to threaten, harass, annoy, alarm, inconvenience, upset, or embarrass another person; is calculated or is otherwise likely to deceive; is intended or otherwise likely to infringe (or threaten to infringe) another person’s right to privacy; misleadingly impersonates any person or otherwise misrepresents your identity or affiliation in a way that is calculated to deceive. <br/>
										13.4.You must not infringe, or assist in the infringement of, the intellectual property rights (including, but not limited to, copyright, patents, trademarks and database rights) of any other party; or is in breach of any legal duty owed to a third party including, but not limited to, contractual duties and duties of confidence. <br/>
										13.5.Medisense Healthcare Solutions Pvt Ltd reserve the right to suspend or terminate your access to Our Apps and Websites without notice if, in Our sole opinion, you materially breach the provisions of this Clause or any of the other provisions of these Terms and Conditions. <br/>
										13.6.Medisense Healthcare Solutions Pvt Ltd hereby exclude all liability arising out of any actions (including, but not limited to those set out above) that Medisense Healthcare Solutions Pvt Ltd may take in response to breaches of these Terms and Conditions. <br/>
										•	Cookies <br/>
										14.1.This Clause should be read in conjunction with the Cookies Policy on our Websites and our Privacy Policy. <br/>
										14.2.All Cookies and tracking tools used on our Apps and Websites are used in accordance with current Karnataka and INDIA Cookie Law as applicable. <br/>
										14.3. We may place and access certain first party Cookies on your computer. First party cookies are those placed directly by Medisense Healthcare Solutions Pvt Ltd via this Website and are used only by Medisense Healthcare Solutions Pvt Ltd. Medisense Healthcare Solutions Pvt Ltd uses Cookies to improve your experience of using the Website and to improve our range of products and services. <br/>
										14.4.Before any Cookies are placed on your computer, subject to Clause 3.5, you will be presented with a message bar requesting your consent to set those Cookies. By giving your consent to the placing of Cookies you are enabling Medisense Healthcare Solutions Pvt Ltd to provide the best possible experience and service to you. You may, if you wish, deny consent to the placing of Cookies; however certain features of the Website may not function fully or as intended. <br/>
										14.5.Certain features of the Website depend upon Cookies to function. Karnataka and INDIA Cookie Law deem these Cookies to be “strictly necessary”. There are no strictly necessary cookies currently used on our Websites as at the date of these Terms and Conditions. <br/>
										14.6.You can choose to enable or disable Cookies in your Internet browser. Most Internet browsers also enable you to choose whether you wish to disable all cookies or only third-party cookies. By default, most Internet browsers accept Cookies, but this can be changed. For further details, please consult the help menu in your Internet browser. You may delete all cookies form your browser. <br/>
										14.7.You can choose to delete Cookies at any time however you may lose any information that enables you to access the Website more quickly and efficiently including, but not limited to, personalization settings. <br/>
										14.8.It is recommended that you ensure that your Internet browser is up-to-date and that you consult the help and guidance provided by the developer of your Internet browser if you are unsure about adjusting your privacy settings. <br/>
										<strong>DATA PROTECTION AND PRIVACY: HOW WE COLLECT, STORE AND USE YOUR DATA AND YOUR ELECTRONIC HEALTH RECORD </strong><br/>
										•	Medisense Healthcare Solutions Pvt Ltd’s policies on data protection 
										and privacy: how we collect, store and use your data and your Electronic Health Record are contained in our Privacy Policy which forms part of these Terms and Conditions. <br/>
										<strong>TERMS OF SERVICE  </strong><br/>
										•	Products and Services, Pricing and Availability <br/>
										16.1. Medisense Healthcare Solutions Pvt Ltd make all reasonable efforts to ensure that all general descriptions of the Products and Services correspond to the actual Products and Services that will be provided to you, however please note that the exact nature may vary depending upon your individual requirements and circumstances. <br/>
										16.2. Please note that sub-Clause 16.1 does not exclude responsibility for mistakes due to negligence on Our part and refers only to variations of the described Products and Services, not to different Products and Services altogether. <br/>
										16.3.Medisense Healthcare Solutions Pvt Ltd neither represents nor warrant that all Products and Services will be always available and cannot necessarily confirm availability until your Order is completed. Availability indications are not provided on Our Apps and Websites. <br/>
										16.4. Medisense Healthcare Solutions Pvt Ltd makes all reasonable efforts to ensure that all prices shown on Our Apps and Websites are correct at the time of going online. Medisense Healthcare Solutions Pvt Ltd reserves the right to change prices and to add, alter, or remove special offers from time to time and as necessary. All pricing information is reviewed and updated regularly. Changes in price will not affect any Order that you have already placed (please note sub-Clause 16.5.3 regarding GST), however. <br/>
										16.5. Medisense Healthcare Solutions Pvt Ltd checks all prices when Medisense Healthcare Solutions Pvt Ltd process your Order. In the unlikely event that Medisense Healthcare Solutions Pvt Ltd has shown incorrect pricing information, please note the following: <br/>
										16.5.1. Medisense Healthcare Solutions Pvt Ltd will contact you in writing or by telephone to inform you of the mistake and to ask you how you wish to proceed. Medisense Healthcare Solutions Pvt Ltd will give you the option to purchase the Products and Services at the correct price or to cancel your Order (or the affected part thereof). Medisense Healthcare Solutions Pvt Ltd will not proceed with processing your Order until you respond. If Medisense Healthcare Solutions Pvt Ltd do not receive a response from you within 1 day, Medisense Healthcare Solutions Pvt Ltd will treat your Order as cancelled and notify you of the same in writing; <br/>
										16.5.2. if the price of Products and Services you have ordered changes between your Order being placed and Medisense Healthcare Solutions Pvt Ltd processing that Order and taking payment, you will be charged the price shown on Our Apps and Websites at the time of placing your Order; and <br/>
										16.5.3.all prices on Our Apps and Websites include GST where applicable. If the GST rate changes between your order being placed and Medisense Healthcare Solutions Pvt Ltd taking payment, the amount of GST payable will be automatically adjusted when taking payment. <br/>
										16.6. Subscriptions in our Apps and Websites are calculated annually or monthly and payable in advance. <br/>
										16.7. You acknowledge that subscriptions will automatically renew upon the end of the agreed term, unless you provide an email or written request to cancel the subscription prior to the end of the relevant subscription period. Please contact: medisensedev@medisense.me <br/>
										•	Orders – How Contracts Are Formed <br/>
										17.1.Our Apps and Websites will guide you through the ordering process. Before submitting your Order to Medisense Healthcare Solutions Pvt Ltd, you will be either requested to confirm your order by accepting Our Terms and Conditions (and possibly Privacy Policy and Medical Consent) or given the opportunity to review your Order and amend any errors. Please ensure that you have checked your Order carefully before submitting it. <br/>
										17.2. No part of Our Apps and Websites constitutes a contractual offer capable of acceptance. Your Medisense Healthcare Solutions Pvt Ltd Order constitutes a contractual offer that Medisense Healthcare Solutions Pvt Ltd may, at Our sole discretion, accept. Our acknowledgement of receipt of your Order does not mean that Medisense Healthcare Solutions Pvt Ltd has accepted it. Our acceptance is indicated by either your entry into a Medisense Healthcare Solutions Pvt Ltd Waiting Room for a consultation with a Healthcare Provider where you will also be asked to confirm your personal details or by Medisense Healthcare Solutions Pvt Ltd sending you an Order Confirmation by telephone or email. A telephone order confirmation is a legally binding contract. In the case of an email; only when Medisense Healthcare Solutions Pvt Ltd has sent you an Order Confirmation will there be a legally binding contract between Medisense Healthcare Solutions Pvt Ltd and you (“the Contract”).<br/> 
										17.3. Order Confirmations shall contain the following information: <br/>
										17.3.1. Confirmation of the Products and Services ordered including full details of the main characteristics of those Products and Services; <br/>
										17.3.2.Fully itemized pricing for the Products and Services ordered including, where appropriate, taxes and other additional charges; <br/>
										17.4.If you change your mind, you may cancel your Order or the Contract before or after Medisense Healthcare Solutions Pvt Ltd begin providing the Products and Services subject to these Terms and Conditions. For details of your cancellation rights, please refer to Clauses below. <br/>
										17.5.Medisense Healthcare Solutions Pvt Ltd may cancel your Order at any time before Medisense Healthcare Solutions Pvt Ltd begin providing the Products and Services in the following circumstances; <br/>
										17.6.The required personnel and/or required materials necessary for the provision of the Products and Services are not available; or <br/>
										17.7.An event outside of Our control (please refer to Clauses below for events outside of Our control). <br/>
										17.8.If Medisense Healthcare Solutions Pvt Ltd cancel your Order and Medisense Healthcare Solutions Pvt Ltd has taken payment any such sums will be refunded to you as soon as possible and in any event within 60 days. If Medisense Healthcare Solutions Pvt Ltd cancel your Order, you will be informed by email or text as appropriate. <br/>
										•	Provision of the Products and Services <br/>
										18.1.Medisense Healthcare Solutions Pvt Ltd will begin providing the Products and Services on the date agreed when you make your Order (which Medisense Healthcare Solutions Pvt Ltd shall confirm in the Order Confirmation). Please note that if you request that the Products and Services begin within the statutory 14 (fourteen) calendar day cancellation (or “cooling off”) period, your right to cancel may be limited or lost. Please refer to Clause 19, for your statutory cancellation rights. Medisense Healthcare Solutions Pvt Ltd will use all reasonable endeavours to provide the Products and Services with reasonable skill and care, commensurate with best practice.<br/> 
										18.2. Medisense Healthcare Solutions Pvt Ltd will make every reasonable effort to provide the Products and Services in a timely manner. Medisense Healthcare Solutions Pvt Ltd cannot be held responsible for any delays if an event outside of Our control occurs. Please refer to Clause 24, for events, outside of Our control. <br/>
										18.3. If Medisense Healthcare Solutions Pvt Ltd requires any information or action from you to provide the Products and Services, Medisense Healthcare Solutions Pvt Ltd will inform you of this as soon as is reasonably possible. Depending upon the nature of the Products and Services you have ordered, Medisense Healthcare Solutions Pvt Ltd may require prior information or action such as confirmation of the details or your Preferred Pharmacy, Primary GP and Personal Health Information. <br/>
										18.4. If the information you provide or the action you take under Clause 18, is delayed, incomplete or otherwise incorrect, Medisense Healthcare Solutions Pvt Ltd will not be responsible for any delay caused. <br/>
										18.5.In certain circumstances, for example where there is a delay in you providing Medisense Healthcare Solutions Pvt Ltd information or taking-action required under Clause 18, Medisense Healthcare Solutions Pvt Ltd may suspend the Products and Services (and will inform you of that suspension by email). <br/>
										18.6.In certain circumstances, for example where Medisense Healthcare Solutions Pvt Ltd encounter a technical problem, Medisense Healthcare Solutions Pvt Ltd may need to suspend or otherwise interrupt the Products and Services to resolve the issue. Unless the issue is an emergency that requires immediate action Medisense Healthcare Solutions Pvt Ltd will inform you in advance by email before suspending or interrupting the Products and Services. <br/>
										18.7.If the Products and Services are suspended or interrupted under Clauses 18, you will not be required to pay for them during the period of suspension if you access those Products and Services. You must, however, pay any sums that may already be due by the appropriate due date(s). <br/>
										18.8.If you do not pay Medisense Healthcare Solutions Pvt Ltd for the Products and Services as required by Clause 17, Medisense Healthcare Solutions Pvt Ltd may suspend the Products and Services until you have paid all outstanding sums due. If this happens, we will inform you by email. An administrative fee of €50.00 may be added. <br/>
										•	Your Medisense Healthcare Solutions Pvt Ltd Legal Right to Cancel 
										(Cooling Off Period) <br/>
										19.1. If you are a consumer in the India, you can cancel within a “cooling off” period within which you can cancel the Contract for any reason. This period begins once your Order is accepted and Medisense Healthcare Solutions Pvt Ltd have sent you an Order Confirmation, i.e. when the Contract between you and Medisense Healthcare Solutions Pvt Ltd is formed. The period ends at the end of 1 days’ after that date.<br/> 
										19.2. If you wish to exercise your right to cancel under this Clause 19, you must inform Medisense Healthcare Solutions Pvt Ltd of your decision within the cooling off period. You may do so in any way you wish by contacting us at: medisensedev@medisense.me <br/>
										19.3. Medisense Healthcare Solutions Pvt Ltd may ask you why you have chosen to cancel and may use any answers you provide to improve Our Services in the future, however please note that you are under no obligation to provide any detail if you do not wish to. <br/>
										19.4. As specified in sub-Clause 19.1, if the Products and Services are to begin within the cooling off period you are required to make an express request to that effect. By requesting that the Products and Services begin within the 14 (fourteen) calendar day cooling off period you acknowledge and agree to the following: <br/>
										19.5. If the Products and Services are fully performed within the cooling off period, you will lose your right to cancel after the Products and Services are complete. <br/>
										19.6. If you cancel after provision of the Products and Services has begun but is not yet complete you will still be required to pay for the Products and Services provided up until the point at which you inform Medisense Healthcare Solutions Pvt Ltd that you wish to cancel. The amount due shall be calculated in proportion to the full price of the Products and Services and the actual Products and Services already provided including any initial administration costs. Any sums that have already been paid for the Products and Services shall be refunded subject to deductions calculated in accordance with the foregoing. Refunds, where applicable, will be issued within 60 calendar days’ after you inform Medisense Healthcare Solutions Pvt Ltd that you wish to cancel. Refunds will be made using the same payment method you used when ordering the Products and Services. <br/>
										19.7. The initial administration cost of an Account as at the date of these Terms and Conditions will be communicated to you via mail or messages.<br/>
										•	Cancellation After the Legal Cancellation Period <br/>
										20.1. Cancellation of Contracts after the cooling off period has elapsed shall be subject to the specific terms governing those Products and Services and may be subject to a minimum contract duration. Details of the relevant duration, cancellation provisions and minimum notice periods will be confirmed for the Products and Services in Our Order Confirmation. <br/>
										20.2. If you wish to cancel under this Clause 18, you must inform Medisense Healthcare Solutions Pvt Ltd of your decision to do so. You may do so in any way you wish by emailing us medisensedev@medisense.me. <br/>
										20.3. Medisense Healthcare Solutions Pvt Ltd may ask you why you have chosen to cancel and may use any answers you provide to improve Our Services in the future, however please note that you are under no obligation to provide any detail if you do not wish to. <br/>
										20.4. You may be entitled to cancel immediately by giving Medisense Healthcare Solutions Pvt Ltd written notice in the following circumstances: <br/>
										20.5. Medisense Healthcare Solutions Pvt Ltd breach the Contract in a material way and fail to remedy the breach within 30 calendar days of you asking Medisense Healthcare Solutions Pvt Ltd to do so in writing;<br/> 
										20.6. Medisense Healthcare Solutions Pvt Ltd go into liquidation or have a receiver or administrator appointed over Our assets; <br/>
										20.7. Medisense Healthcare Solutions Pvt Ltd change these Terms and Conditions to your material disadvantage; or <br/>
										20.8.Medisense Healthcare Solutions Pvt Ltd are adversely affected by an event outside of Our control that continues for more than 30 calendar days. <br/>
										20.9. Eligibility for refunds may vary per the Products and Services ordered. You will be required to pay for Products and Services supplied up until the point at which you inform Medisense Healthcare Solutions Pvt Ltd that you wish to cancel (please note that this may include charges in full for any work and services that Medisense Healthcare Solutions Pvt Ltd have undertaken or already provided where Medisense Healthcare Solutions Pvt Ltd have reasonably incurred costs. <br/>
										Such sums will be deducted from any refund due to you or, if no refund is due, Medisense Healthcare Solutions Pvt Ltd will invoice you for the relevant sums. Details of the relevant terms will be provided at the time of cancellation. If you are cancelling due to Our failure to comply with these Terms and Conditions or the Contract, you will not be required to make any payment to Medisense Healthcare Solutions Pvt Ltd (unless such failure is due to an event outside of Our control or is due to your failure to comply with any of your obligations). <br/>
										20.10. Refunds under Clause 20 will be issued to you within 60 (sixty) calendar days’ after the date on which you inform Medisense Healthcare Solutions Pvt Ltd that you wish to cancel. Refunds will be made using the same payment method you used when ordering the Products and Services unless you specifically request that Medisense Healthcare Solutions Pvt Ltd make a refund using a different method. <br/>
										•	Our Rights to Cancel <br/>
										21.1. For cancellations before we begin providing the Products and Services, please refer to Clause 17. <br/>
										21.2. Medisense Healthcare Solutions Pvt Ltd may cancel the Products and Services after Medisense Healthcare Solutions Pvt Ltd have begun providing them due to an Event outside of Our control that continues for more than 30 (thirty) calendar days’, or due to the non-availability of required personnel and/or required materials necessary for the provision of the Products and Services. In such cases, you will only be required to pay for Products and Services that Medisense Healthcare Solutions Pvt Ltd have already provided up until the point at which Medisense Healthcare Solutions Pvt Ltd inform you that Medisense Healthcare Solutions Pvt Ltd are cancelling the contract. Such sums will be deducted from any refund due to you or, if no refund is due, Medisense Healthcare Solutions Pvt Ltd will invoice you for the relevant sums or no payment will be due from you and if you have already made any payment to us, such sums will be refunded to you. <br/>
										21.3. Once Medisense Healthcare Solutions Pvt Ltd have begun providing the Products and Services, Medisense Healthcare Solutions Pvt Ltd may cancel the Contract at any time and will give you at least 30 calendar days’ written notice of such cancellation. You will only be required to pay for Page of 59 74 <br/>
										Products and Services that you have received. Such sums will be deducted from any refund due to you or, if no refund is due, Medisense Healthcare Solutions Pvt Ltd will invoice you for the relevant sums. <br/>
										21.4. Refunds due under this Clause 21 will be issued to you no later than 60 (sixty) calendar days’ after the day on which Medisense Healthcare Solutions Pvt Ltd inform you of the cancellation. Refunds will be made using the same payment method you used when ordering the Products and Services unless you specifically request that Medisense Healthcare Solutions Pvt Ltd make a refund using a different method. <br/>
										21.5. Medisense Healthcare Solutions Pvt Ltd may cancel immediately by giving you written notice in the following circumstances: <br/>
										21.6. You fail to make a payment by the due date as set out in Clause 17. This does not affect Our right to charge you interest on any; or <br/>
										21.7. You breach the contract in a material way and fail to remedy the breach within 14 calendar days’ of Medisense Healthcare Solutions Pvt Ltd asking you to do so in writing. <br/>
										•	Problems with the Products and Services <br/>
										22.1. Medisense Healthcare Solutions Pvt Ltd always uses reasonable endeavour to ensure that Our Products and Services are trouble-free. If, however, there is a problem with the Products and Services please contact Medisense Healthcare Solutions Pvt Ltd as soon as is reasonable possible via Our contact details. <br/>
										22.2. Medisense Healthcare Solutions Pvt Ltd will use reasonable endeavor to remedy problems with the Products and Services as quickly as is reasonably possible and practical. In emergency situations, such as those where vulnerable people may be affected, Medisense Healthcare Solutions Pvt Ltd will use reasonable endeavor to remedy problems within 24 hours. <br/>
										22.3. Medisense Healthcare Solutions Pvt Ltd will not charge you for remedying problems under this Clause 22 where the problems have been caused by Medisense Healthcare Solutions Pvt Ltd, or any of Our agents or sub-contractors, or where nobody is at fault. If Medisense Healthcare Solutions Pvt Ltd determine that a problem has been caused by you, including your provision of incorrect or incomplete information or taking of incorrect action, Medisense Healthcare Solutions Pvt Ltd may charge you for the remedial work. <br/>
										22.4. As a consumer you have certain legal rights with respect to the purchase of products and services. Medisense Healthcare Solutions Pvt Ltd are, for example, required to provide the Products and Services with reasonable care and skill. You also have remedies if Medisense Healthcare Solutions Pvt Ltd use materials or other products that are not as described, not of satisfactory quality, or not fit for purpose. More information on your rights as a consumer can be obtained from your local Citizens’ Information Centre. Nothing in these Terms and Conditions will affect these statutory rights. <br/>
										•	Our Liability<br/> 
										23.1. Medisense Healthcare Solutions Pvt Ltd will not be responsible for any loss or damage that you may suffer because of Our breach of these Terms and Conditions. <br/>
										23.2.Medisense Healthcare Solutions Pvt Ltd make no warranty or representation that the Products and Services are fit for commercial, business or industrial purposes of any kind including resale. Medisense Healthcare Solutions Pvt Ltd will not be liable to you for any loss of profit, loss of business, interruption to business or for any loss of business opportunity.<br/> 
										23.3. These Terms and Conditions seeks to exclude our liability on death or personal injury caused by Our negligence (including that of Our employees, agents or sub-contractors); or for fraud or fraudulent misrepresentation. <br/>
										•	Events Outside of Our Control (Force Majure) <br/>
										24.1.Medisense Healthcare Solutions Pvt Ltd will not be liable for any failure or delay in performing Our obligations where that failure or delay results from any event that is beyond Our reasonable control. Such causes include, but are not limited to: power failure, internet service provider failure, industrial action by third parties, civil unrest, fire, explosion, flood, storms, earthquakes, subsidence, acts of terrorism, acts of war, governmental action, epidemic or other natural disaster, or any other event that is beyond Our reasonable control. <br/>
										24.2. If any event described under this Clause 24, occurs that is likely to adversely affect Our performance of any of Our obligations under these Terms and Conditions: <br/>
										24.2.1.Medisense Healthcare Solutions Pvt Ltd will inform you as soon as is reasonably possible; <br/>
										24.2.2.Our obligations under these Terms and Conditions (and therefore the Contract) will be suspended and any time limits that Medisense Healthcare Solutions Pvt Ltd are bound by will be extended accordingly; <br/>
										24.2.3.Medisense Healthcare Solutions Pvt Ltd will inform you when the event outside of Our control is over and provide details of any new dates, times or availability of Products and Services as necessary; <br/>
										24.2.4.If the event outside of Our control continues for more than 30 calendar days’ Medisense Healthcare Solutions Pvt Ltd may cancel the Contract and inform you of the cancellation. Any refunds due to you because of that cancellation will be paid to you as soon as is reasonably possible and, in any event, no later than 14 calendar days’ after the date on which Medisense Healthcare Solutions Pvt Ltd inform you of the cancellation; and <br/>
										24.2.5.If an event outside of Our control occurs and continues for more than 30 calendar days’ and you wish to cancel the Contract thus, you may do so. Any refunds due to you because of cancellation will be paid to you as soon as is reasonably possible and, in any event, no later than 14 calendar days’ after the date on which you inform Medisense Healthcare Solutions Pvt Ltd that you wish to cancel. <br/>
										<strong>GENERAL PROVISIONS</strong> <br/>
										•	Privacy and Cookies <br/>
										25.1.Use of Our Apps and Websites is also governed by Our Privacy Policies, and Our Cookies Policy. These policies are incorporated into these Terms and Conditions by this reference. <br/>
										•	Other Important Terms <br/>
										26.1.Cross-Border Data Transfers. We may process your data outside the India. Where we do we rely on legally-provided mechanisms to lawfully transfer data across borders and to process your data. <br/>
										26.2.We do not store any credit or debit card information on Our servers. Payments are processed via a third-party payment provider that is fully compliant with Level 1 Payment Card Industry (PCI) data security standards. Any payment transactions are encrypted using SSL technology.<br/> 
										26.3.Medisense Healthcare Solutions Pvt Ltd Limited may transfer or assign Our obligations and rights under these Terms and Conditions (and under the Contract, as applicable) to a third party (this may happen, for example, if we sell Our business). If this occurs, Medisense Healthcare Solutions Pvt Ltd will inform you in writing. Your Medisense Healthcare Solutions Pvt Ltd rights under these Terms and Conditions will not be affected and Our obligations under these Terms and Conditions will be transferred to the third party who will remain bound by them. <br/>
										26.4.You may not transfer (assign) your obligations and rights under these Terms and Conditions (and under the Contract, as applicable) without Our express written permission. <br/>
										26.5.The Contract is between you and Medisense Healthcare Solutions Pvt Ltd Limited. It is not intended to benefit any other person or third party in any way and no such person or party will be entitled to enforce any provision of these Terms and Conditions.<br/> 
										26.6.If any of the provisions of these Terms and Conditions are found to be unlawful, invalid or otherwise unenforceable by any court or other authority, those provision(s) shall be deemed severed from the remainder of these Terms and Conditions. The rest of these Terms and Conditions shall be valid and enforceable. <br/>
										26.7.No failure or delay by Medisense Healthcare Solutions Pvt Ltd in exercising any of Our rights under these Terms and Conditions means that Medisense Healthcare Solutions Pvt Ltd have waived that right, and no waiver by Medisense Healthcare Solutions Pvt Ltd of a breach of any provision of these Terms and Conditions means that Medisense Healthcare Solutions Pvt Ltd will waive any subsequent breach of the same or any other provision. <br/>
										•	Communications from Medisense Healthcare Solutions Pvt Ltd <br/>
										27.1.If Medisense Healthcare Solutions Pvt Ltd has your contact details, Medisense Healthcare Solutions Pvt Ltd may from time to time send you important notices by email. Such notices may relate to matters including, but not limited to, service changes and changes to these Policies. <br/>
										27.2.Medisense Healthcare Solutions Pvt Ltd will never send you marketing emails of any kind without your consent. If you do give such consent, you may opt out at any time. All marketing emails sent by Medisense Healthcare Solutions Pvt Ltd include an unsubscribe link. If you opt out of receiving emails from Medisense Healthcare Solutions Pvt Ltd at any time, it may take up to 7 business days for your new preferences to take effect. Please contact: communication@medisensepractice.com<br/>
										27.3.For questions or complaints about communications from Medisense Healthcare Solutions Pvt Ltd (including, but not limited to marketing emails), please contact Medisense Healthcare Solutions Pvt Ltd by email: medisensedev@medisense.me<br/>
										•	Communication and Contact Details <br/>
										28.1.If you wish to contact Medisense Healthcare Solutions Pvt Ltd with general questions or complaints, you may contact Medisense Healthcare Solutions Pvt Ltd at info@medisensepractice.com<br/> 
										•	Law and Jurisdiction <br/>
										29.1.These Terms and Conditions, and the relationship between you and Medisense Healthcare Solutions Pvt Ltd (whether contractual or otherwise) and Medisense Healthcare Solutions Pvt Ltd Limited shall be governed by and construed in accordance with the Law of the India State of Karnataka. <br/>
										•	Changes <br/>
										30.1.Medisense Healthcare Solutions Pvt Ltd will attempt to keep the information and the resources contained on or accessible through its Apps and Websites timely and accurate, but makes no guarantees, and disclaims any implied warranty or representation, about its accuracy, relevance, timeliness, completeness, or appropriateness for a purpose. <br/>
										30.2.Medisense Healthcare Solutions Pvt Ltd may change or modify the information, services and any other resources contained on or accessible through its Apps and Websites, or discontinue its Apps and Websites altogether, at any time without notice. <br/>
										30.3.Medisense Healthcare Solutions Pvt Ltd reserves the right to change this Policy as we may deem necessary from time to time or as may be required by law. Any changes will be immediately posted on the App and Website and you will be notified on the website that the policy has been altered. You will be required to confirm that you accept the changes to the Policy prior to using certain services. <br/>
										30.4.In the event of any conflict between the current version of these Terms and Conditions and any previous version(s), the provisions current and in effect shall prevail unless it is expressly stated otherwise. <br/>
										•	Marketing Material and Your Rights to Opt-Out <br/>
										31.1.If you no longer wish to receive informational or promotional material from us by alerts, texts and similar messages, email and post please contact us at medisensedev@medisense.me<br/>
										•	Contact <br/>
										32.1. We want to improve Our products and services through your feedback. If you have any enquiries, questions, comments or even complaints please feel free to contact us or our Data Protection Officer at Medisense Healthcare Solutions Pvt Ltd, medisensedev@medisense.me<br/>
										•	Complaints and Feedback <br/>
										33.1.Medisense Healthcare Solutions Pvt Ltd always welcome feedback from Our Users and, whilst Medisense Healthcare Solutions Pvt Ltd always use all reasonable endeavor to ensure that your experience is a positive one. Medisense Healthcare Solutions Pvt Ltd nevertheless want to hear from you if you have any cause for complaint: contact us at Medisensedev@medisense.me <br/>
										33.2.All complaints are handled in accordance with Our complaints handling policy and procedure, available from us on request.<br/> 
										33.3.If you wish to complain about any aspect of your dealings with Medisense Healthcare Solutions Pvt Ltd, please contact the CEO by; mail at Medisense Healthcare Solutions Pvt Ltd Limited Medisensedev@medisense.me<br/> 
										•	Complaints about Data Protection and Privacy <br/>
										34.1. You can lodge a complaint with the Data Protection Commissioner at any stage if you are of the view that any of your rights have been breached. contact: medisensedev@medisense.me <br/>
										•	Version Date <br/>
										35.1.These Terms and Conditions, and the relationship between you Medisense Healthcare Solutions Pvt Ltd are dated 03.06.2020 and should be construed in conjunction with Medisense Healthcare Solutions Pvt Ltd’s Privacy Policy and Medical Consent. <br/>
										<b>© Medisense Healthcare Solutions Pvt Ltd Limited, June 3rd, 2020</b><br/>
										<a href="medisensepractice.com" target="_blank">medisensepractice.com </a><br/>
									</p>								
								</div>

								<div class="modal-footer">
									<div style="<?php if($appointmentResult[0]['appointment_type']==0){ ?>display: none;<?php } else{ ?>display: flex;<?php }?>">
									<input type="checkbox" class="i-checks" name="chkTeleCom" id="chkTeleCom" value="1" checked style="float:left;"><label style="float:left;"> I'm ready for teleconsultation</label><br/>
								</div>
								<div style="display: flex;">
									<input type="checkbox"  class="i-checks" name="chkPatConsent" id="chkPatConsent" value="1" checked style="float:left;"> <label style="float:left;">Patient agree for our Institute to share the EMR with Professional Health CarePartners (Diagnostic, Pharmacy)</label>
									<br/>
								</div>
								<button type="button" name="acceptTerm" onclick="termClick(1)" class="btn btn-primary" >I Accept</button>
								<button type="button" name="rejectTerm" onclick="termClick(2)" class="btn btn-danger">I Reject</button>
							</div>
						</div>
					</div>
				</div>  
			</div>
        </div>

        <div class="footer">            
            <div>
                <strong>Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
            </div>
        </div>

    </div>
    </div>
	

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

														
   	<script src="js/patient_details_page.js"></script>
	 <!-- Toastr -->
    <script src="../assets/js/plugins/toastr/toastr.min.js"></script>
</body>

</html>
