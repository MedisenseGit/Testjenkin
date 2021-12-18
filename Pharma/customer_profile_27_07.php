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
	
	$pharma_referal = mysqlSelect("*","pharma_referrals","md5(pr_id)='".$_GET['p']."'","","","","");
	$patient_tab = mysqlSelect("*","doc_my_patient","patient_id='".$pharma_referal[0]['patient_id']."'","","","","");
	$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$pharma_referal[0]['pr_id']."'  and type='1' and request_from='2'","","","","");
	if($patient_tab[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['patient_gen']=="2"){
		$gender="Female";
	}

	
	$patient_id = $patient_tab[0]['patient_id'];
						
	//$get_doc_details = mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
	
	
	//$patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","diagnosis_patient_episodes","admin_id = '". $admin_id ."' and md5(patient_id) = '". $_GET['p'] ."' "," episode_id DESC ","","","");
    $patient_episodes = mysqlSelect("*, DATE_FORMAT(date_time,'%d/%m/%Y') AS formated_date_time","doc_patient_episodes","episode_id = '".$pharma_referal[0]['episode_id']."'","","","","");

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
                <!--<div class="col-lg-3">
                    <h2>My Patient Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>My Patient Profile</strong>
                        </li>
                    </ol>
                </div>-->
				 <div class="col-lg-10 mgTop">
					<!--<div class="search-form">
                                <form action="add_details.php" method="post" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" id="serPatient" placeholder="Enter name or mobile number to search an existing patient or add a new patient" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search/Add
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>    -->        
			   </div>
                <div class="col-lg-2 mgTop">
					<a href="Request"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">


            <div class="row m-b-lg m-t-lg">
                <div class="col-md-5">

                    <!-- -->
                    <div class="profile-info" style="margin-left:50px;">
                        <div class="">
                            <div>
							<!-- -->
                                <h2 class="no-margins">
                                    <?php echo $patient_tab[0]['patient_name']; ?>
                                </h2>
                                <h4><i class="fa fa-mobile"></i> <?php echo $patient_tab[0]['patient_mob']; ?></h4>
								 <h4><i class="fa fa-stack-exchange"></i> <?php echo $patient_tab[0]['patient_email']; ?></h4>
                                <small>
                                    <?php echo $patient_tab[0]['patient_addrs'].", ".$patient_tab[0]['pat_state'].", ".$patient_tab[0]['pat_country']; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            <td>
                                <strong>GENDER</strong> <?php echo $gender; ?>
                            </td>
                            <td>
                                <strong>AGE</strong> <?php echo $patient_tab[0]['patient_age']; ?>
                            </td>

                        </tr>
                      
                      
                        </tbody>
                    </table>
                </div>
				<!-- <div class="col-md-4 pull-right">
				 <a href="#" data-toggle="modal" data-target="#myModal5">
				 <img src="../assets/img/trendAnalysis.png" title="Trend Analysis" width="200"/>
				</a>
				</div>-->
	
            </div>
			
			<div class="row m-l-lg">
			 <p>
								<!--<a class="btn btn-success btn-rounded btn-outline m-l" href="#" id="addvisitDetails"><i class="fa fa-hospital-o"></i> Add Visit Details </a>-->
                                <a class="btn btn-success btn-rounded btn-outline m-l" href="#" id="visitDetails"><i class="fa fa-wheelchair"></i> View Visit Details <span class="label label-danger"><?php echo COUNT($patient_episodes); ?></span></a>
								<a class="btn btn-success btn-rounded btn-outline m-l" href="#" id="latestReports"><i class="fa fa-copy"></i> View Reports</a>
			              <!--  <a class="btn btn-success btn-rounded btn-outline m-l" href="#" id="addvisitDetails"><i class="fa fa-hospital-o"></i> Add Visit Details </a>-->
		</p>
			</div>
		
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
					
                        
						<a id="addVisit"></a>	
                       <!-- <div class="vertical-timeline-block" id="add-visit-dtails">
                            <div class="vertical-timeline-icon blue-bg">
                                <i class="fa fa-file-text"></i>
                            </div>

                            <div class="vertical-timeline-content" >
                                <h2>Add Patient Visit Details</h2>
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
							<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
							<input type="hidden" name="patient_name" value="<?php echo $patient_tab[0]['diagnostic_customer_name']; ?>">
								
								<!--Section Starts-->
							<!--	<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Examination</h4>
								
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Add / Search examination here..." data-episode-id="0" data-patient-id="<?php echo $patient_id; ?>" id="get_examination_res" name="srchExam" value="" class="form-control input-lg searchExamination" tabindex="3">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
										<div id="dispExamination"></div>
																							
								</div>
								</div>
								<br>
								<!--Section Ends-->
								<!--<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Investigations</h4>
								<!--<div class="input-group">				
								<?php $last_five_tests = mysqlSelect("a.main_test_id as main_test_id,b.test_name_site_name as test_name_site_name","diagnosis_frequent_investigations as a left join patient_diagnosis_tests as b on a.main_test_id=b.id","a.diagnostic_id='".$admin_id."'","a.freq_test_count DESC","","","8");
								
								if(COUNT($last_five_tests)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								foreach($last_five_tests as $last_five_tests_list){
								
								?>
								
								<a class="btn btn-xs btn-white m-l get_diagnosis_test_prior" data-main-test-id="<?php echo $last_five_tests_list['main_test_id']; ?>" title="<?php echo $last_five_tests_list['test_name_site_name']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo substr($last_five_tests_list['test_name_site_name'],0,15); ?></code></a>
								<?php }
								} ?>
								</div>-->
								
							<!--	<br>
								<div class="input-group">
										
                                       <input type="text" id="get_diagnosis_test" placeholder="Enter test here..." data-patient-id="<?php echo $patient_id; ?>" name="searchDiagnosTest" value="" class="form-control input-lg searchDiagnosTest" tabindex="2">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<dl>
									<br> <dd>
											<div id="dispDignoTest"></div>
								</dl>
								</div>
								<br>
								
							<!--<div class="col-lg-12" style="background-color:#f8f8f8; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								<h4>Any notes</h4>
								<div class="input-group">				
								<?php $last_five_examination = mysqlSelect("*","diagnosis_frequent_examination","diagnostic_id='".$admin_id."'","freq_count DESC","","","5");
								
								if(COUNT($last_five_examination)>0) { ?>
								<label>Frequently used:  </label>
								<?php 
								
								while(list($key_exam, $value_exam) = each($last_five_examination)){
									
								?>
								
								<a class="btn btn-xs btn-white m-l get_examination_res_prior" data-examination_id="<?php echo $value_exam['diagno_exam_id']; ?>" data-patient-id="<?php echo $patient_id; ?>"><code> <?php echo $value_exam['examination']; ?></code></a>
								<?php }
								
								} ?>
								</div>
								<br>
								<div class="input-group">
										
                                       <input type="text" placeholder="Write examination here..." data-patient-id="<?php echo $patient_id; ?>" id="get_examination_res" name="srchExam" value="" class="form-control input-lg searchExamination" tabindex="3">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary"  name="" type="button">
                                                ADD
                                            </button>
                                        </div>
										
                                    </div>
								<br>
								<div class="input-group">
										<div id="dispExamination"></div>
								</div>
								<br>
								</div>
								<br>-->
                             <!--  	<div class="form-group">
								 <div class="col-lg-6">
									<dl>
										<dt>Total charges(Rs.)</dt><br> 
										<dd>
										<div class="pull-left m-r input-group date">
											<input name="consult_charge" type="text" placeholder="Total charges(Rs.)" value="<?php echo $get_doc_details[0]['cons_charge'];?>" class="form-control" />
										</div>
										</dd><br>
									</dl>
								</div>
								
								</div>
								
								<div class="gray-bg">
									<button class="btn btn-sm btn-primary m-l" name="save_patient_print" id="save_patient_print" type="submit"><strong><i class="fa fa-print"></i> SAVE & PRINT</strong></button>
								
									<button class="btn btn-sm btn-primary m-l" name="save_patient_edit" id="save_patient_edit" type="submit"><strong><i class="fa fa-floppy-o"></i> SAVE</strong></button>
								
								
								</div>
								
                            </div>
							</form>
                        </div>-->
						
						<div class="vertical-timeline-block" id="visit-details">
					
                            <div class="vertical-timeline-icon navy-bg">
                                <i class="fa fa-wheelchair"></i>
                            </div>

                            <div class="vertical-timeline-content">
							
                          
                               <h2>All Patient Details</h2>
							
                        
                               <div class="ibox-content">
							<?php
								

								if (count($patient_episodes) > 0)
								{ ?>
                            <table class="footable table table-stripped toggle-arrow-tiny table-responsive">
                                <thead>
                                <tr>

                                  <!--  <th data-toggle="false">VISITS</th>-->
									<th>Precription</th>
									<!-- <th data-hide="all">Examination</th>-->
								     <th ></th>
                                  
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
							
								
									
									
								<td><!--<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmSMSSubmit" id="frmSMSSubmit">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['diagnostic_customer_id']; ?>">
								 <input type="hidden" name="episode_id" value="<?php echo $patient_episode_val['episode_id']; ?>">
								 <div class="form-group">
									<div class="col-lg-6 pull-left">
									
									<button type="submit" name="smsPatient" id="smsPatient" class="btn btn-primary m-b m-r">SMS To Patient</button>
									</div>
									<?php if($patient_episode_val['doc_id']!=0){ ?>
									<div class="col-lg-6 pull-right">
									
									<button type="submit" name="smsDoctor" id="smsDoctor" class="btn btn-primary m-b m-r">SMS To Doctor</button>
									</div>
									<?php } ?>
								</div>
								</form>--></td>
									
									
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
							<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmSMSSubmit" id="frmSMSSubmit">
								<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
								 <input type="hidden" name="episode_id" value="<?php echo $patient_episodes[0]['episode_id']; ?>">
								 <input type="hidden" name="type" value="1">
								 <input type="hidden" name="diagno_pharma_id" value="<?php echo $pharma_referal[0]['pharma_id']; ?>">
								 <input type="hidden" name="request_from" value="2">
							     <input type="hidden" name="referred_id" value="<?php echo $pharma_referal[0]['pr_id']; ?>">
								 <div class="form-group">
									<div class="col-lg-12 pull-left">
									 <label style="margin-right:10px;"> Payment Amount </label>
									 <select id="currency_code" name="currency_code">
									   <option value="INR">INR</option>
									   <option value="QAR">QAR</option>
									 </select>
									 <input type="number" name="paymentValue"  value="<?php echo $payment_amount[0]['payment_amount']; ?>" placeholder="" style="width:100px;">
									<!--<button type="submit" name="smsPatient" id="smsPatient" class="btn btn-primary m-b m-r">SMS To Patient</button>-->
									</div>
									<div class="col-lg-12 pull-left">
									
									<button type="submit" name="sendPayment" id="sendPayment" class="btn btn-primary m-b m-r">Send Payment Link</button>
									</div>
									<?php if($patient_episode_val['doc_id']!=0){ ?>
									<!--<div class="col-lg-6 pull-right">
									
									<button type="submit" name="smsDoctor" id="smsDoctor" class="btn btn-primary m-b m-r">SMS To Doctor</button>
									</div>-->
									<?php } ?>
								</div>
								</form>
                        </div>
                            </div>
                        </div>
						
						
						<!-- View report section -->
					
					
                        
                    </div>

                </div>

            </div>

        </div>
       <?php include_once('footer.php'); ?>

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
