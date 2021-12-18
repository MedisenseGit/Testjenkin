<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}

$curdate=date('Y-m-d');
require_once("../classes/querymaker.class.php");



		/*	if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			$appointmentResult = mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","a.Visiting_date desc","","","$eu, $limit");
			$pag_result = mysqlSelect("a.id","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","c.hosp_id='".$admin_id."'","");
			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing); */
			
			if(isset($_POST['appointmentUpcoming'])){	
			 $_SESSION['appoint_list']="1";
			}
			else if(isset($_POST['appointmentAll']))
			{
			$_SESSION['appoint_list']="2";
			}
			else{
				$_SESSION['appoint_list']="3";
			}	
			
		if($_SESSION['appoint_list']=="1"){	
			if(!empty($_SESSION['appointTypeDoc'])){
				$appUpcomingResult = mysqlSelect("*","appointment_token_system"," doc_type='1' and doc_id='".$_SESSION['appointTypeDoc']."' and app_date>='".$curdate."'","app_date ASC","","","");
		
			}else{
				$appUpcomingResult = mysqlSelect("*","appointment_token_system"," doc_type='1' and hosp_id='".$admin_id."' and app_date>='".$curdate."'","app_date ASC","","","");
			}
		}
		else if($_SESSION['appoint_list']=="2")
		{
			if(!empty($_SESSION['appointTypeDoc'])){
				$appointmentResult = mysqlSelect("*","appointment_transaction_detail","pref_doc='".$_SESSION['appointTypeDoc']."'","Visiting_date desc","","","");
			
			}else{
				$appointmentResult = mysqlSelect("*","appointment_transaction_detail","hosp_id='".$admin_id."'","Visiting_date desc","","","");
			}
		}
		else{
			if(!empty($_SESSION['appointTypeDoc'])){
				$appointmentToday = mysqlSelect("*","appointment_token_system"," doc_type='1' and doc_id='".$_SESSION['appointTypeDoc']."' and app_date='".$curdate."' and status!='Cancelled'","token_no DESC","","","");
			
			}else{
				$appointmentToday = mysqlSelect("*","appointment_token_system"," doc_type='1' and hosp_id='".$admin_id."' and app_date='".$curdate."' and status!='Cancelled'","token_no DESC","","","");
			}		
		}
				
			
		//$getRefDet = mysqlSelect("doc_state,ref_address,cons_charge","referal","ref_id='".$admin_id."'","","","","");             

		$status_val=array("At reception"=>"6","Consulted"=>"2","Cancelled"=>"3","Missed"=>"5","VC Ready"=>"7","VC Confirmed"=>"8");	
			
		//$checkDocTimeSet= mysqlSelect("time_id","doc_time_set","doc_id='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");

		//$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");
	$_SESSION['appointment_type'] = 0;		
$getCompanyId = mysqlSelect("company_id as company_id"," hosp_tab","hosp_id='".$admin_id."'","","","","");
	
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Appointments</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    
	
	<script type="text/javascript">
	$(function(){
	 $( "#pincode_gen" ).autocomplete({
	  source: 'get_pincode.php'
	 });
	 $( "#get_pincode" ).autocomplete({
	  source: 'get_pincode.php'
	 });
	});
	  function getDocDate(val) {
		  $("#timeSlot").css('display','none');
		  $("#appointTime").css('display','none');
		  $("#direct_appointment").prop("disabled",false);
	$.ajax({
	type: "POST",
	url: "get_doc_date.php",
	data:'doc_id='+val,
	success: function(data){
		$("#check_date").html(data);
		var timenewSlot=$("#timeSlotCount").text();
	if(timenewSlot=="true"){
		$("#timeSlot").css('display','block');
	}
	if($("#doctimeSlotCount").text()==0){
		$("#direct_appointment").prop("disabled",true);
		$("#appointTime").css('display','block');
	}
	}
	});
	
}

function getDocDateTiming(val) {
		var docId=$('#doc_id :selected').val();
		
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val+'&doc_id='+docId,
	success: function(data){
		$("#check_time1").html(data);
	}
	});
}

function getDocTiming1(val) {
	
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val,
	success: function(data){
		$("#check_time2").html(data);
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
                <div class="col-lg-10">
                    <h2>Appointments</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Appointments</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
				<?php //if(empty($admin_id) || empty($checkDocTimeSet)){ ?>
								<div class="alert alert-danger alert-dismissable" style="display:none" id="appointTime">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-warning"></i> Please update appointment timings <!--<a href="Set-Appointment">Click here</a>--> </strong>
								 </div>
								<?php// } ?>
								<?php if($_GET['response']=="appointment-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-check"></i> Appointment Created Successfully</strong>
								 </div>
								<?php } ?>
								<?php if($_GET['response']=="reschedule"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-check"></i> Appointment has been rescheduled Successfully</strong>
								 </div>
								<?php } ?>
                 <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       <!--<h5 >Appointment List</h5>-->
                         <div class="btn-group pull-left">
						  <script language="javaScript" src="js/status_validationJs.js" ></script>
						
                                   <form method="post" name="changeAppType" >
								    <button class="btn btn-white" name="appointmentToday" type="submit"/>Today's</button>
                                    <button class="btn btn-white" name="appointmentUpcoming"  type="submit">Upcoming</button>
                                    <button class="btn btn-white" name="appointmentAll" type="submit">All</button>
                                </form> 
                                </div>
								<div class="form-group pull-right">
									
                                    <div class="col-sm-12"><select data-placeholder="Choose doctor..." class="chosen-select" tabindex="2" onchange="return saveDoctorId(this.value);">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_specialization as e on e.doc_id=a.ref_id inner join specialization as d on d.spec_id=e.spec_id","c.hosp_id='".$admin_id."'","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" <?php if($_SESSION['appointTypeDoc']==stripslashes($DocList['Ref_Id'])){?>selected<?php }?> />
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                               </div>
								<br><br><br>
						<!--<div class="btn-group pull-right">
                                    <button class="btn btn-white" id="date_filter" type="button"><i class="fa fa-filter"></i> Filter</button>
                        </div><br><br><br>-->
						<div class="form-group" id="data_5">
                                
                                <div class="input-daterange input-group pull-left" id="datepicker">
                                    <input type="text" class="input-sm form-control" name="fromDate" id="fromDate" value="<?php echo date('m/d/Y');?>"/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="input-sm form-control" name="toDate" id="toDate" value="<?php echo date('m/d/Y');?>" />
								</div>
								<div class="pull-right">
								<button type="button" name="cmdSearch" class="btn btn-primary btn-sm searchByDate">Search</button>
								<button type="button" id="canceldatepicker" class="btn btn-default btn-sm">Cancel</button>
                            
							</div><br><br>
								 
						</div>
						
						
                    </div>
                    <div class="ibox-content" id="before-status">
					<?php if(isset($appointmentToday)) { ?>
					 <input type="text" class="form-control input-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
					 <table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
                            <thead>
                            <tr>
								
								<th id="tokenSlot">Token</th>
								<th>Doctor Name</th>
                                <th>Patient Name</th>
								<th>Appointment Slot</th>
                                <th>Status</th>
								<th>Re-Schedule</th>
                            </tr>
                            </thead>
							
						<tbody>
							<tr>
							<td colspan="4">Today's Appointments</td>
							</tr>
						
							
							<?php
							foreach($appointmentToday as $Todaylist){ 
							
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$Todaylist['Visit_Time']."'","","","","");
							
							?>
							<script>
							function getDocTiming<?php echo $Todaylist['appoint_trans_id']; ?>(docid,val) {
								
								$.ajax({
								type: "POST",
								url: "get_doc_timing.php",
								data:'day_val='+val+'&doc_id='+docid,
								success: function(data){
									$("#check_time<?php echo $Todaylist['appoint_trans_id']; ?>").html(data);
								}
								});
							}
							</script>
							<tr>
								<td><?php if($Todaylist['token_no']!="555") { echo "<button class='btn btn-success btn-circle' type='button'>".$Todaylist['token_no']."</button>";} else { echo "<button class='btn btn-primary btn-xs' type='button'>Online</button>"; } ?></td>
								<?php $getDoctorName= mysqlSelect("ref_id as Ref_Id,ref_name as Doc_name","referal","ref_id='".$Todaylist['doc_id']."'","ref_name asc","","","");
												?>
								<td><?php echo $getDoctorName[0]['Doc_name']?></td>
								<td><a href="<?php echo $_SESSION['EMR_URL']."?p=".md5($Todaylist['patient_id']);?>"><?php echo $Todaylist['patient_name']; ?></a></td>
								<td style="min-width:150px;" ><?php echo date('d-m-Y',strtotime($Todaylist['app_date']))." | ".$Todaylist['app_time']; ?></td>
								<td>
								<div class="btn-group pull-right">
								<?php 
								if($Todaylist['status']=="Pending"){
									$btn_type= "btn-danger";
								}else if($Todaylist['status']=="At reception"){
									$btn_type= "btn-warning";
								}else if($Todaylist['status']=="Consulted"){
									$btn_type= "btn-primary";
								}
								else if($Todaylist['status']=="Missed"){
									$btn_type= "btn-danger";
								}
								else if($Todaylist['status']=="VC Ready"){
									$btn_type= "btn-primary";
								}
								else if($Todaylist['status']=="VC Confirmed"){
									$btn_type= "btn-info";
								}
								?>
							
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $Todaylist['status']; ?> <span class="caret"></span></button>
										<?php if($Todaylist['status']!="Cancelled"){ ?>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $Todaylist['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
										<?php } ?>
									</div>
								
								</td>
								<td><center>
									<?php if($Todaylist['status']!="Cancelled"){ ?>	<a href="#" data-toggle="modal" data-target="#myModal<?php echo $Todaylist['appoint_trans_id']; ?>" ><i class="fa fa-edit"></i></a> </center><?php } ?>
										
										
										<div class="modal inmodal" id="myModal<?php echo $Todaylist['appoint_trans_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
										
                                        
										<input type="hidden" name="trans_id" value="<?php echo $Todaylist['appoint_trans_id']; ?>">
                                    <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                            <h4 class="modal-title">Appointment Re-Schedule</h4>
											<h5>Patient Name: <?php echo $Todaylist['patient_name']; ?></h5>
                                        </div>
                                        <div class="modal-body">
                                        
									<div class="form-group">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="reschedule_date"  tabindex="2" onchange="return getDocTiming<?php echo $Todaylist['appoint_trans_id']; ?>(<?php echo $Todaylist['doc_id']; ?>,this.value);" required="required">
									<option value="">Select Date</option>
									<?php 
									for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$Todaylist['doc_id']."' and a.hosp_id='".$admin_id."'","","","","");
									
									
									$current_date=date('d-m-Y', $date);
									
									$checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$Todaylist['doc_id']."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            if($i==0) { echo "Today";} else if($i==1){ echo "Tomorrow";} else { echo date('D d-m-Y', $date);}
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
									 ?>
									</select>
									</div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time"  id="check_time<?php echo $Todaylist['appoint_trans_id']; ?>" tabindex="2" required="required">
												
										</select></div>
                                </div>
								</div>
								
									
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
											<button type="submit" name="cmdreschedule" class="btn btn-primary">RESCHEDULE</button>
											
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
								</td>
							</tr>
							<?php } //end foreach
							
							//Future appointments
							?>						
						
						</tbody>
						</table>
						<?php } //endif
						if(isset($appUpcomingResult)){
						?>
						<input type="text" class="form-control input-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
							<table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
                            <thead>
                            <tr>
								<th>Doctor Name</th>
                                <th>Patient Name</th>
								<th>Appointment Slot</th>
                                <th>Status</th>
								<th>Re-schedule</th>
                            </tr>
                            </thead>
						<tbody>
							<tr>
							<td colspan="3">Upcoming Appointments</td>
							</tr>
							<?php 
							//print_r($appUpcomingResult);
							foreach($appUpcomingResult as $list){ 
							
							//$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$list['Visit_Time']."'","","","","");
							//print_r($uplist);
							?>
								
							<tr>
							<?php $getDoctorName= mysqlSelect("ref_id as Ref_Id,ref_name as Doc_name","referal","ref_id='".$list['doc_id']."'","ref_name asc","","","");
												?>
								<td><?php echo $getDoctorName[0]['Doc_name']?></td>
								<td><a href="<?php echo $_SESSION['EMR_URL']."?p=".md5($list['patient_id']);?>"><?php echo $list['patient_name']; ?></a></td>
								<td style="min-width:150px;" ><?php echo date('d-m-Y',strtotime($list['app_date']))." | ".$list['app_time']; ?></td>
								<td>
								<div class="btn-group pull-right">
								<?php 
								if($list['status']=="Pending"){
									$btn_type= "btn-danger";
								}else if($list['status']=="At reception"){
									$btn_type= "btn-warning";
								}else if($list['status']=="Consulted"){
									$btn_type= "btn-primary";
								}else if($list['status']=="Missed"){
									$btn_type= "btn-danger";
								}
								else if($list['status']=="VC Ready"){
									$btn_type= "btn-primary";
								}
								else if($list['status']=="VC Confirmed"){
									$btn_type= "btn-info";
								}
								?>
								<script>
							function getDocTiming<?php echo $list['appoint_trans_id']; ?>(docid,val) {
								
								$.ajax({
								type: "POST",
								url: "get_doc_timing.php",
								data:'day_val='+val+'&doc_id='+docid,
								success: function(data){
									$("#check_time<?php echo $list['appoint_trans_id']; ?>").html(data);
								}
								});
							}
							</script>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $list['status']; ?> <span class="caret"></span></button>
									<?php if($list['status']!="Cancelled"){ ?>
									<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $list['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									<?php } ?>
									</div>
								
								</td>
								<td><center>
									<?php if($list['status']!="Cancelled"){ ?>	<a href="#" data-toggle="modal" data-target="#myModal<?php echo $list['appoint_trans_id']; ?>" ><i class="fa fa-edit"></i></a> </center><?php } ?>
										
										
										<div class="modal inmodal" id="myModal<?php echo $list['appoint_trans_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
										
                                        
										<input type="hidden" name="trans_id" value="<?php echo $list['appoint_trans_id']; ?>">
                                    <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                            <h4 class="modal-title">Appointment Re-Schedule</h4>
											<h5>Patient Name: <?php echo $list['patient_name']; ?></h5>
                                        </div>
                                        <div class="modal-body">
                                        
									<div class="form-group">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="reschedule_date"  tabindex="2" onchange="return getDocTiming<?php echo $list['appoint_trans_id']; ?>(<?php echo $list['doc_id']; ?>,this.value);" required="required">
									<option value="">Select Date</option>
									<?php 
									for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$list['doc_id']."' and a.hosp_id='".$admin_id."'","","","","");
									
									
									$current_date=date('d-m-Y', $date);
									
									$checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$list['doc_id']."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            if($i==0) { echo "Today";} else if($i==1){ echo "Tomorrow";} else { echo date('D d-m-Y', $date);}
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
									 ?>
									</select>
									</div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time"  id="check_time<?php echo $list['appoint_trans_id']; ?>" tabindex="2" required="required">
												
										</select></div>
                                </div>
								</div>
								
									
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
											<button type="submit" name="cmdreschedule" class="btn btn-primary">RESCHEDULE</button>
											
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
								</td>		
									</div>
								
								</td>
							</tr>
							<?php
									
							}

								   ?>
						
						</tbody>
						</table>
						<?php  }
						if(isset($appointmentResult)) { ?>
						<input type="text" class="form-control input-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
							<table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
                            <thead>
                            <tr>
								<th>Doctor Name</th>
                                <th>Patient Name</th>
								<th>Appointment Slot</th>
                                <th>Status</th>
								<th>Re-Schedule</th>
                            </tr>
                            </thead>
						<tbody>	
						<tr>
							<td colspan="3">All Appointments</td>
							</tr>
							<?php foreach($appointmentResult as $Alllist){ 
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$Alllist['Visiting_time']."'","","","","");
							
							?>
							<script>
							function getDocTiming<?php echo $Alllist['appoint_trans_id']; ?>(docid,val) {
								$.ajax({
								type: "POST",
								url: "get_doc_timing.php",
								data:'day_val='+val+'&doc_id='+docid,
								success: function(data){
									$("#check_time<?php echo $Alllist['appoint_trans_id']; ?>").html(data);
								}
								});
							}
							</script>	
							<tr>
							<?php $getDoctorName= mysqlSelect("ref_id as Ref_Id,ref_name as Doc_name","referal","ref_id='".$Alllist['pref_doc']."'","ref_name asc","","","");
												?>
								<td><?php echo $getDoctorName[0]['Doc_name']?></td>
								<td><a href="<?php echo $_SESSION['EMR_URL']."?p=".md5($Alllist['patient_id']);?>"><?php echo $Alllist['patient_name']; ?></a></td>
								<td style="min-width:150px;" ><?php echo date('d-m-Y',strtotime($Alllist['Visiting_date']))." | ".$getTimeSlot[0]['Timing']; ?></td>
								<td>
								<div class="btn-group pull-right">
								<?php 
								if($Alllist['pay_status']=="Pending"){
									$btn_type= "btn-danger";
								}else if($Alllist['pay_status']=="At reception"){
									$btn_type= "btn-warning";
								}else if($Alllist['pay_status']=="Consulted"){
									$btn_type= "btn-primary";
								}else if($Alllist['pay_status']=="Missed"){
									$btn_type= "btn-danger";
								}
								else if($Alllist['pay_status']=="VC Ready"){
									$btn_type= "btn-primary";
								}
								else if($Alllist['pay_status']=="VC Confirmed"){
									$btn_type= "btn-info";
								}
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $Alllist['pay_status']; ?>
										
										<span class="caret"></span></button>
										<?php if($Alllist['pay_status']!="Cancelled"){ ?>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $Alllist['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
										 <?php } ?>
								</td>
								<td><center>
									<?php if($Alllist['pay_status']!="Cancelled"){ ?>	<a href="#" data-toggle="modal" data-target="#myModal<?php echo $Alllist['appoint_trans_id']; ?>" ><i class="fa fa-edit"></i></a> </center><?php } ?>
										
										
										<div class="modal inmodal" id="myModal<?php echo $Alllist['appoint_trans_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
								<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPatient" id="frmAddPatient">
										
                                        
										<input type="hidden" name="trans_id" value="<?php echo $Alllist['appoint_trans_id']; ?>">
                                    <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            
                                            <h4 class="modal-title">Appointment Re-Schedule</h4>
											<h5>Patient Name: <?php echo $Alllist['patient_name']; ?></h5>
                                        </div>
                                        <div class="modal-body">
                                        
									<div class="form-group">
									<div class="form-group">
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="reschedule_date"  tabindex="2" onchange="return getDocTiming<?php echo $Alllist['appoint_trans_id']; ?>(<?php echo $Alllist['pref_doc']; ?>,this.value);" required="required">
									<option value="">Select Date</option>
									<?php 
									for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$Alllist['pref_doc']."' and a.hosp_id='".$admin_id."'","","","","");
									
									
									$current_date=date('d-m-Y', $date);
									
									$checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$Alllist['doc_id']."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if((date('D', $date)==$getDayName[0]['da_name']) && COUNT($checkHoliday)==0){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            if($i==0) { echo "Today";} else if($i==1){ echo "Tomorrow";} else { echo date('D d-m-Y', $date);}
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
									 ?>
									</select>
									</div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time"  id="check_time<?php echo $Alllist['appoint_trans_id']; ?>" tabindex="2" required="required">
												
										</select></div>
                                </div>
								</div>
								
									
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
											<button type="submit" name="cmdreschedule" class="btn btn-primary">RESCHEDULE</button>
											
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
								</td>		
									</div>
								
								</td>
							</tr>
							<?php } ?>
						
						</tbody>
                      
                        </table>
						<?php
									
							}

								   ?>
						
                    </div>
					
                </div> </div>
            <div class="col-lg-6">
			<div class="tabs-container">
                       
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="panel-body">
								<?php 
								//$day_val=date('D', strtotime($curdate));
								//$chkDocTimeSlot= mysqlSelect("*","seven_days as a left join doc_time_set as b on a.day_id=b.day_id","b.doc_id='".$admin_id."' and b.hosp_id='".$_SESSION['login_hosp_id']."' and a.da_name='".$day_val."'","","","","");
								
								//$docNumPatientHour= mysqlSelect("num_patient_hour","doc_appointment_slots","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");
								//$totalPatientToday = count($chkDocTimeSlot)*$docNumPatientHour[0]['num_patient_hour'];
		
								//$curBookingCount= mysqlSelect("*","appointment_token_system","app_date='".$curdate."' and doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");
								//echo "Total Patient can handle today: ".$totalPatientToday."<br>Current Booking:".count($curBookingCount);
								//if((count($chkDocTimeSlot)==0) || (count($curBookingCount)>$totalPatientToday)){ ?>
								<div class="alert alert-danger alert-dismissable" id="timeSlot" style="display:none">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-alert"></i> No appointment slot available <!--<a href="Set-Appointment">View Settings</a>--></strong>
								 </div>
								<?php// }?>
								<?php if($getCompanyId[0]['company_id'] == "413" ){ ?>
								<div class="btn-group pull-left">
						 
								    <a class="btn btn-white" href="Appointments" name="" style="margin-right: 10px;color: white;background: #1bb394;" />Retail</a>
									<a class="btn btn-white" href="Corporate-Appointment" name="" />Corporate</a>
                                    									
                                </div>
								<?php } ?>
										<div class="search-form" style="display: inline-block;">
										   <form autocomplete="off">
												<div class="input-group">
												
												   <input type="text" id="get_direct_appointment_details" placeholder="Search / Add Patient" name="search" value="" data-hosp-id="<?php echo $admin_id; ?>" class="form-control input-lg typeahead_1">
													<div class="input-group-btn">
														<button class="btn btn-lg btn-primary" name="cmdSearch" type="button">
															Search
														</button>
													</div>
												</div>
											</form>

										</div>
										</br>
									<div class="form-horizontal">

									<div class="form-group">
								 <div class="col-sm-10"> 
										<div class="radio radio-info radio-inline ">
                                            <input type="radio"  class="iradio_square-green" value="3" required="required" name="appointment_type" checked id="onlineConsult">
                                            <label  for="inlineRadio8" class="m-t-xs"><b> TeleConsultation </b></label>
                                        </div>								 
										<div class="radio radio-info radio-inline ">
                                            <input type="radio"   class="iradio_square-green" value="1" required="required" name="appointment_type"  id="today">
                                            <label  class="m-t-xs"><b> Walk-In Patient </b></label>
                                        </div>
                                        <div class="radio radio-info radio-inline ">
                                            <input type="radio"  class="iradio_square-green" value="2" required="required" name="appointment_type" id="future">
                                            <label  class="m-t-xs"><b> Appointments </b></label>
                                        </div>
										 
										</div>
								</div>
								</div>
								<hr>
								 	
								
									<div class="form-horizontal">
									
									<!--<div id="date-time-section"></div>-->
									
									<div class="form-group" id="doctor_section" >
									 
									 <label class="col-sm-2 control-label">Choose Doctor <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose doctor..." class="chosen-select" name="doc_id" id="doc_id" required="required"  tabindex="2" onchange="return getDocDate(this.value);">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join doc_specialization as e on e.doc_id=a.ref_id inner join specialization as d on d.spec_id=e.spec_id","c.hosp_id='".$admin_id."'","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" />
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                               </div>
							   <div class="form-group" id="date_section" >
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="check_date" id="check_date"  tabindex="2" onchange="return getDocDateTiming(this.value);" required="required">
										<option value="">Select Date</option>
									
									</select>
									</div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control chkTime" name="check_time"  id="check_time1" tabindex="2" required="required">
												
										</select></div>
                                </div>

									
									<div id="before-search">
									<form enctype="multipart/form-data" method="post" action="add_details.php"  name="frmAddPatient" autocomplete="off" >
								<style>
									.collapsible {
									  background-color: #d3d3d3ab;
									  color: black;
									  cursor: pointer;
									  padding: 10px;
									  width: 100%;
									  border: none;
									  text-align: left;
									  outline: none;
									  font-size: 14px;									 
									}

									.active, .collapsible:hover {
									 /* background-color: #d3d3d3ab;*/
									}

									.collapsible:after {
									  content: '\002B';
									  color: black;
									  font-weight: bold;
									  float: right;
									  margin-left: 5px;
									}

									#before-search .active:after {
									  content: "\2212";
									}

									.content {
									  padding: 0 18px;
									 /* max-height: 0;*/
									  overflow: hidden;
									  transition: max-height 0.2s ease-out;
									  background-color: white;
									   margin-bottom:20px;									  
									   border: 2px solid #d3d3d3ab;
									}
									</style>
									
										<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_name" required="required" class="form-control" required=""></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" class="form-control"></div>
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                                      <div class="col-sm-10">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" required="required" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" required="required" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" required="required" name="se_gender">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="se_phone_no" required="required" maxlength="10" minlength="10" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" name="se_email" class="form-control"></div>
								
                                </div>
								
								<!--<div class="form-group">
									<label class="col-sm-2 control-label">Pincode</label>
									<div class="col-sm-4">
									<input type="text" id="pincode_gen" placeholder="Pincode"  name="pincode" value="" class="form-control">								
									</div>									
									
								</div>-->
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address"  rows="3"></textarea></div>
                                </div>
								<div id="beforeLoad">
								<div class="form-group">
								<label class="col-sm-2 control-label">City </label>
                                    <div class="col-sm-4">
									<input type="text" name="se_city"  id="se_city" value="" class="form-control">
									</div>
									<label class="col-sm-2 control-label">State </label>

                                    <div class="col-sm-4">
										<select data-placeholder="Choose a State..." class="form-control" name="se_state" id="se_state" tabindex="2">
											<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													if(!empty($getRefDet[0]['doc_state'])){
													?><option value="<?php echo $getRefDet[0]['doc_state']; ?>"><?php echo $getRefDet[0]['doc_state']; ?></option>
													<?php } else{ ?>
													<option value="">Select State</option>
													
													<?php }
													
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
								</div>
									<script type="text/javascript">
												function getState(val) {
													$.ajax({
													type: "POST",
													url: "get_state.php",
													data:'country_name='+val,
													success: function(data){
														$("#se_state").html(data);
													}
													});
												}
											</script>
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2" onchange="return getState(this.value); ">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" />
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                                </div>
								
								</div>
								<div id="dispCity"></div>
								<button type="button" class="collapsible" data-toggle="collapse" data-target="#collapseRow">Add Reference Details</button>
									<div class="content collapse" id="collapseRow">
									 <div class="form-group" style=" padding-top:10px;"><label class="col-sm-2 control-label">Reference </label>

                                    <div class="col-sm-8"><select class="chosen-select" name="reference_from" id="reference_from" >
									<option value="">Select </option>
									<?php $select1= mysqlSelect("referred_doc_id,referral_name","add_referred_doctor","hosp_id='".$admin_id."'","referral_name ASC","","","");
									foreach($select1 as $listDoc){ 
									?>
									<option value="<?php echo $listDoc['referred_doc_id']; ?>"><?php echo $listDoc['referral_name']; ?> </option>
									
									<?php } ?>
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new reference doctor" data-toggle="modal" data-target="#myModal1"><i class="fa fa-plus"></i>
										</a></div>
									
								
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referring Hospital </label>

                                    <div class="col-sm-8"><select class="form-control" name="reference_hosp" id="reference_hosp" onchange="return getrefDocDet(this.value); ">
									<option value="">Select </option>
									<?php $select1= mysqlSelect("*","hospital_in_referral","hosp_id='".$admin_id."'","hospital_name ASC","","","");
									foreach($select1 as $listDoc){ 
									?>
									<option value="<?php echo $listDoc['hos_out_ref_id']; ?>"><?php echo $listDoc['hospital_name']; ?> </option>
									
									<?php } ?>
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring hospital" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus"></i>
										</a></div>
									
								<script type="text/javascript">
												function getrefDocDet(val) {
													$.ajax({
													type: "POST",
													url: "get_ref_doc_details.php",
													data:'ref_hosp_id='+val,
													success: function(data){
														$("#refering_doc").html(data);
													}
													});
												}
												
											</script>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referring Doctor </label>

                                    <div class="col-sm-8"><select class="form-control" name="refering_doc" id="refering_doc" >
									<option value="">Select </option>
									
									</select></div><div class="col-sm-2"><a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring doctor" data-toggle="modal" data-target="#myModal3"><i class="fa fa-plus"></i>
										</a></div>
									
								
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Referral Note</label>
									<div class="col-sm-4">
											<input type="file" name="txtReferalNote">
										</div></div>
										<script>
										/*var coll = document.getElementsByClassName("collapsible");
										var i;

										for (i = 0; i < coll.length; i++) {
										  coll[i].addEventListener("click", function() {
											this.classList.toggle("active");
											var content = this.nextElementSibling;
											if (content.style.maxHeight){
											  content.style.maxHeight = null;
											} else {
											  content.style.maxHeight = content.scrollHeight + "px";
											} 
										  });
										}*/
										</script>
									</div>
									<div class="form-group" style="padding-top:10px;">
									<label class="col-sm-2 control-label"> </label>
									
									<div class="col-sm-10">
									<input type="checkbox" class="" name="chkTeleCom" value="1"> I'm ready for teleconsultation
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label"> </label>
									
									<div class="col-sm-10">
									 <input type="checkbox"  class="" name="chkPatConsent" value="1"> Patient agree for our Institute to share the EMR with Professional Health CarePartners (Diagnostic, Pharmacy)
								
									</div>
								</div>
								
								<?php if($checkSetting[0]['before_consultation_fee'] =="1") { ?>
								<div class="form-group">
									<label class="col-sm-2 control-label">Consultation Charges </label>
									<div class="col-sm-4">
									<input type="text" name="consult_charge" value="<?php echo $getRefDet[0]['cons_charge'];?>" class="form-control">
									</div>
									<br><br>
									<div class="col-sm-8 m-t">
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkReceipt" value="1"> Would you like to send payment receipt to patient via SMS?</label></dt><br> <br>
									</dl>
									</div>
								</div>
								<?php } ?>
								
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="direct_appointment" id="direct_appointment" <?php //if(count($chkDocTimeSlot)==0){ echo "disabled"; }?> class="btn btn-primary block full-width m-b ">BOOK APPOINTMENT</button>
								</div>
								</div>
								</form>
								</div>
								<div id="after-search"></div>
								
								</div>
							
								<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title">Add New Reference</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddReferred">
										<input type="hidden" name="appointSec" value="1">
                                        <div class="modal-body">
                                            <div class="form-group"><label>Name</label> <input type="text" name="referral_name" required value="" class="form-control"></div>
											<div class="form-group"><label>Email</label> <input type="email" name="referral_email" value="" class="form-control"></div>
											<div class="form-group"><label>Mobile</label> <input type="text" name="referral_mobile" value="" class="form-control" title="Mobile Number must be 10 digits"></div>
											<div class="form-group"><label>Address</label> <input type="text" name="referral_address" value="<?php echo $getRefDet[0]['ref_address']; ?>" class="form-control"></div>
											<div class="form-group"><label>City</label> <input type="text" name="referral_city" value="<?php echo $getRefDet[0]['doc_city']; ?>" class="form-control"></div>
											<div class="form-group">
									  <label>State </label>
								<script type="text/javascript">
												function getState1(val) {
													$.ajax({
													type: "POST",
													url: "get_state.php",
													data:'country_name='+val,
													success: function(data){
														$("#se_state1").html(data);
													}
													});
												}
											</script>
										<select data-placeholder="Choose a State..." class="form-control" name="se_state1" id="se_state1" tabindex="2">
											<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													if(!empty($getRefDet[0]['doc_state'])){
													?><option value="<?php echo $getRefDet[0]['doc_state']; ?>"><?php echo $getRefDet[0]['doc_state']; ?></option>
													<?php } else{ ?>
													<option value="">Select State</option>
													
													<?php }
													
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
									<div class="form-group"><label >Country</label>
											<select data-placeholder="Choose a Country..." class="form-control" name="se_country"  tabindex="2" onchange="return getState1(this.value); ">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
													$i=30;
													foreach($getCountry as $CountryList){
												?> 
														
														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php if($getRefDet[0]['doc_country']==stripslashes($CountryList['country_name'])){ ?>selected<?php } ?>/>
														<?php echo stripslashes($CountryList['country_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>									
                                      </div>
										<div class="form-group"><label>Type</label> <select class="form-control" name="reference_type"><option value="">Select</option><option value="doctor">Doctor</option><option value="patient">Patient</option><option value="company">Company</option><option value="other">Other</option></select></div>
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_referred_doc" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>
							
									<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title">Add New Referring Hospital</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddReferred">
										<input type="hidden" name="appointSec" value="1">
                                        <div class="modal-body">
                                            <div class="form-group"><label>Name</label> <input type="text" name="hos_name" required value="" class="form-control"></div>
											<div class="form-group"><label>Email</label> <input type="email" name="txtemail" value="" class="form-control" ></div>
											<div class="form-group"><label>Mobile</label> <input type="text" name="mobile" value="" class="form-control"></div>
											<div class="form-group"><label>Address</label> <input type="text" name="address" value="" class="form-control"></div>
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_referout_hospital" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>

										
									<div class="modal inmodal" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title">Add New Referring Doctor</h4>
                                            
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddReferred">
										<input type="hidden" name="appointSec" value="1">
                                        <div class="modal-body">
										
									
										<div class="form-group">
											<div class="col-sm-10"><label>Hospital</label> 
										<select data-placeholder="Choose a Hospital..." class="form-control" name="slctHospt" tabindex="1">
							<option value="" />Select Hospital</option>
										<?php $RefHospName= mysqlSelect("*","hospital_in_referral","doc_id='".$admin_id."'","hospital_name asc","","","");
												
												foreach($RefHospName as $RefHospList){ ?>
													<option value="<?php echo stripslashes($RefHospList['hos_out_ref_id']);?>" /><?php echo stripslashes($RefHospList['hospital_name']);?></option>
												<?php } ?>
											</select></div><div class="col-sm-2" style="padding-top: 25px;">
											<a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new Referring hospital" id="refHospClick"><i class="fa fa-plus"></i>
										</a></div></div>
                                            <div class="form-group"><label>Name</label> <input type="text" name="doc_name" required value="" class="form-control"></div>
											<div class="form-group"><label>Specialization</label> <select data-placeholder="Choose a Specialization..." class="form-control" name="slctSpec" tabindex="3">
							<option value="" />Select Specialization</option>
										<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												
												foreach($DeptName as $DeptList){ ?>
													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php } ?>
											</select></div>
											<div class="form-group"><label>Email</label> <input type="email" name="txtemail" value="" class="form-control"></div>
											<div class="form-group"><label>Mobile</label> <input type="text" name="mobile" value="" class="form-control"></div>
											<div class="form-group"><label>City</label> <input type="text" name="city" value="" class="form-control"></div>
											<div class="form-group"><label>Address</label> <input type="text" name="address" value="" class="form-control"></div>
										<div class="form-group"><div class="col-sm-12"><label class="col-sm-1 control-label">Type</label>
										<div class="col-sm-6">
																		<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="type">
                                            <label for="inlineRadio1"> Private </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="type" checked="">
                                            <label for="inlineRadio2"> Hospital </label>
                                        </div>
									                                    </div></div>
                             										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="add_referin_doctor" class="btn btn-primary">Add</button>
											
                                        </div>
										</form>
                                    </div>
									</div>
								</div>
										
                                </div>	
										
                                </div>
                            </div>
                            
                        </div>


                    </div>
                
            </div>
            </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	
	
	  <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>


    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
    <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});

    </script>

   <!-- Input Mask-->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

   <!-- Data picker -->
   <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- MENU -->
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>


    <!-- Date range use moment.js same as full calendar plugin -->
    <script src="../assets/js/plugins/fullcalendar/moment.min.js"></script>

    <!-- Date range picker -->
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.js"></script>

    <!-- Select2 -->
    <script src="../assets/js/plugins/select2/select2.full.min.js"></script>

   
      <script>
        $(document).ready(function(){

         

            $('#data_5 .input-daterange').datepicker({
                keyboardNavigation: false,
                forceParse: false,
                autoclose: true
            });

            $('#reportrange').daterangepicker({
                format: 'MM/DD/YYYY',
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                minDate: '01/01/2017',
                maxDate: '12/31/2018',
                dateLimit: { days: 60 },
                showDropdowns: true,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'right',
                drops: 'down',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-primary',
                cancelClass: 'btn-default',
                separator: ' to ',
                locale: {
                    applyLabel: 'Submit',
                    cancelLabel: 'Cancel',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });


        });

    </script>

    <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

    <script>
	function saveDoctorId(id){
		var url = "add_details.php?appointTypeDoc="+id;
		$.get(url, function(response){
		location.reload();
		});
				
	}
	
$(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("e.patient_id,e.patient_name,e.patient_mob","doc_my_patient as e inner join referal as a on a.ref_id=e.doc_id inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join specialization as d on d.spec_id=a.doc_spec","c.hosp_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });

            $('#future').click(function() {
				$("#date_section").show();
				$("#doctor_section").show();
				var appointType=1;
				var url = "add_details.php?appointTypeChange="+appointType;
					
					//$("#date-time-section").hide();
					if(appointType == ""){
						return false;
					}
					else{
						$.get(url, function(response){
						
						});
					}
			});
			
			$('#today').click(function() {
				$("#date_section").hide();
				//$("#doctor_section").hide();
				var appointType=0;
				
				var url = "add_details.php?appointTypeChange="+appointType;
					
					//$("#date-time-section").hide();
					/*if(appointType == ""){
						console.log(appointType);
						return false;
					}
					else{*/
						$.get(url, function(response){
						
						});
					/*}*/
			});
 
$('#onlineConsult').click(function() {
				$("#date_section").show();
				$("#doctor_section").show();
				console.log("online");
				var appointType=2;
				var url = "add_details.php?appointTypeChange="+appointType;
					
					//$("#date-time-section").hide();
					if(appointType == ""){
						return false;
					}
					else{
						$.get(url, function(response){
						
						});
					}
			});
        });
    </script>
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
				 $("#refHospClick").click(function(){
					 $("#myModal3").modal("hide");
					$("#myModal2").modal("show");
				  });
            });
        </script>
	
	<script src="js/appointments.js" ></script>
</body>

</html>
