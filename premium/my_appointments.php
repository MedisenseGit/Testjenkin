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
//$objQuery = new CLSQueryMaker();


		
			
			$appointmentUpcoming = mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date>='".$curdate."' and status!='Cancelled'","app_date asc","","","");
		
			
			//Todays appointment list
			$appointmentToday = mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".$curdate."' and status!='Cancelled'","token_no asc","","","");
			
			$appointmentResult = mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."'","app_date asc","","","");
			                 

$getRefDet = mysqlSelect("doc_state,ref_address","referal","ref_id='".$admin_id."'","","","","");             

$status_val=array("At reception"=>"6","Consulted"=>"2","Cancelled"=>"3","Missed"=>"5");	
	
$checkDocTimeSet= mysqlSelect("time_id","doc_time_set","doc_id='".$admin_id."' and hosp_id='".$_SESSION['login_hosp_id']."'","","","","");
										
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Appointments</title>
	<link rel="icon" href="../assets/img/favicon_icon.png">
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    <link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="../assets/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
		<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<link rel="stylesheet" href="css/jquery-ui.css">
	
      <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
	<script type="text/javascript">
	$(function() 
	{
	 $( "#pincode_gen" ).autocomplete({
	  source: 'get_pincode.php'
	 });
	 $( "#get_pincode" ).autocomplete({
	  source: 'get_pincode.php'
	 });
	});

	
function getDocTiming(val) {
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val,
	success: function(data){
		$("#check_time").html(data);
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
				<?php if(empty($_SESSION['login_hosp_id']) || empty($checkDocTimeSet)){ ?>
								<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-warning"></i> Please update appointment timings <a href="Set-Appointment">Click here</a> </strong>
								 </div>
								<?php } if($_GET['response']=="appointment-success"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong><i class="fa fa-check"></i> Appointment Created Successfully</strong>
								 </div>
								<?php }?>
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                       <!--<h5 >Appointment List</h5>-->
                         <div class="btn-group pull-left">
                                    <button class="btn btn-white" id="today_app" type="button">Today's</button>
                                    <button class="btn btn-white" id="future_app" type="button">Upcoming</button>
                                    <button class="btn btn-white" id="all_app" type="button">All</button>
                                   
                                </div>
						<div class="btn-group pull-right">
                                    <button class="btn btn-white" id="date_filter" type="button"><i class="fa fa-filter"></i> Filter</button>
                        </div><br><br><br>
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
					
					 <input type="text" class="form-control input-sm m-b-xs" id="filter"
                                   placeholder="Search in table">
					 <table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
                            <thead>
                            <tr>
								
								<th id="tokenSlot">Token</th>
                                <th>Patient Name</th>
								<th>Appointment Slot</th>
                                <th>Status</th>
                            </tr>
                            </thead>
						<tbody id="todayAppList">
							<tr>
							<td colspan="4">Today's Appointments</td>
							</tr>
						<?php if(COUNT($appointmentToday)>0) { ?>
							
							<?php
							foreach($appointmentToday as $Todaylist){ 
							
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$Todaylist['Visit_Time']."'","","","","");
							
							?>
							<tr>
								<td><?php if($Todaylist['token_no']!="555") { echo "<button class='btn btn-success btn-circle' type='button'>".$Todaylist['token_no']."</button>";} else { echo "<button class='btn btn-primary btn-xs' type='button'>Online</button>"; } ?></td>
								<td><a href="My-Patient-Details?p=<?php echo md5($Todaylist['patient_id']);?>"><?php echo $Todaylist['patient_name']; ?></a></td>
								<td style="min-width:200px;" ><?php echo date('d-m-Y',strtotime($Todaylist['app_date']))." | ".$Todaylist['app_time']; ?></td>
								<td>
								<div class="btn-group pull-right">
								<?php 
								if($Todaylist['status']=="Pending"){
									$btn_type= "btn-danger";
								}else if($Todaylist['status']=="At reception"){
									$btn_type= "btn-warning";
								}else if($Todaylist['status']=="Consulted"){
									$btn_type= "btn-primary";
								}else if($Todaylist['status']=="Missed"){
									$btn_type= "btn-danger";
								}
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $Todaylist['status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $Todaylist['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
								
								</td>
							</tr>
							<?php } //end foreach
							} //endif
							//Future appointments
							?>						
						
						</tbody>
						<tbody id="futureAppList">
							<tr>
							<td colspan="3">Upcoming Appointments</td>
							</tr>
							<?php foreach($appointmentUpcoming as $list){ 
							
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$list['Visit_Time']."'","","","","");
							
							?>
								
							<tr>
								<td><a href="My-Patient-Details?p=<?php echo md5($list['patient_id']);?>"><?php echo $list['patient_name']; ?></a></td>
								<td style="min-width:200px;" ><?php echo date('d-m-Y',strtotime($list['app_date']))." | ".$list['app_time']; ?></td>
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
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $list['status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $list['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
								
								</td>
							</tr>
							<?php
									
							}

								   ?>
						
						</tbody>
						<tbody id="allAppList">
							
						<tr>
							<td colspan="3">All Appointments</td>
							</tr>
							<?php foreach($appointmentResult as $list){ 
							$getTimeSlot= mysqlSelect("*","timings","Timing_id='".$list['Visit_Time']."'","","","","");
						
							?>
								
							<tr>
								<td><a href="My-Patient-Details?p=<?php echo md5($list['patient_id']);?>"><?php echo $list['patient_name']; ?></a></td>
								<td style="min-width:200px;" ><?php echo date('d-m-Y',strtotime($list['app_date']))." | ".$list['app_time']; ?></td>
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
								?>
                                        <button data-toggle="dropdown" class="btn <?php echo $btn_type;?> btn-xs dropdown-toggle"><?php echo $list['status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
										
									  <?php foreach($status_val as $key=>$value){ ?>
									   <li><a href="#" class="patient-status" data-status-id="<?php echo $value; ?>" data-appoint-transid="<?php echo $list['appoint_trans_id']; ?>"><?php echo $key; ?></a></li>
									  <?php } ?>
										</ul>
									</div>
								
								</td>
							</tr>
							<?php
									
							}

								   ?>
						
						</tbody>
                      
                           
                        </table>
						
                    </div>
					<div id="after-status"></div>
					
                </div>
            </div>
            <div class="col-lg-6">
			<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class=""><a href="Appointments"> <i class="fa fa-calendar"></i> Walk-In Patient</a></li>
                            <li class="active"><a href="#"><i class="fa fa-calendar"></i> Appointments</a></li>
                        </ul>
                        <div class="tab-content">
                            
                            <div id="tab-2" class="tab-pane active">
                                <div class="panel-body">
                                    
									<div class="search-form">
										   <form autocomplete="off">
												<div class="input-group">
												
												   <input type="text" id="get_appointment_details" placeholder="Search / Add Patient" name="search" value="" data-doc-id="<?php echo $admin_id; ?>" class="form-control input-lg typeahead_1">
													<div class="input-group-btn">
														<button class="btn btn-lg btn-primary" name="cmdSearch" type="button">
															Search
														</button>
													</div>
												</div>
											</form>

										</div>
										</br></br>
									<div id="before-search">
									 <form enctype="multipart/form-data" method="post" action="add_details.php"  name="frmAddPatient" autocomplete="off" >
									<div class="form-horizontal">
									
										<div class="form-group">
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="check_date"  tabindex="2" onchange="return getDocTiming(this.value);" required="">
									<option value="">Select Date</option>
									<?php 
									if($_SESSION['login_hosp_id']==""){ ?>
									
									<?php } else { 
									for($i=0; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."' and a.hosp_id='".$_SESSION['login_hosp_id']."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   
									 $checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$admin_id."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");  
									   
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
									}
									 ?>
									</select>
                                
                            </div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control" name="check_time"  id="check_time" tabindex="2" required="">
												
										</select></div>
                                </div>
									
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
								<div id="beforeLoad">
								<div class="form-group">
								<label class="col-sm-2 control-label">City <span class="required">*</span></label>
                                    <div class="col-sm-4">
									<input type="text" name="se_city" value="<?php echo $getRefDet[0]['ref_address']; ?>" class="form-control">
									</div>
									<label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
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
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
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
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address"  rows="3"></textarea></div>
                                </div>
								
								<div class="form-group">
									<label class="control-label col-sm-2">
									  
									</label>

								
									
								</div>
								
							
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="ref_appointment" class="btn btn-primary block full-width m-b ">BOOK APPOINTMENT</button>
								</div>
								</div>
								</div>
							</form>
							</div>
							<div id="after-search"></div>
									
                                </div>
                            </div>
                        </div>


                    </div>
                <!--<div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> Create Appointment</h5>
                       
                    </div>
                    <div class="ibox-content">
					<div class="search-form">
                               <form autocomplete="off">
                                    <div class="input-group">
									
                                       <input type="text" id="get_appointment_details" placeholder="Search / Add Patient" name="search" value="" data-doc-id="<?php echo $admin_id; ?>" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="button">
                                                Search
                                            </button>
                                        </div>
                                    </div>
								</form>

                    </div>
                        <div class="panel-body" id="before-search">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" autocomplete="off" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label" for="date_added">Preferred Date <span class="required">*</span></label>

                                    <div class="col-sm-4"><div class="input-group date">
									<select data-placeholder="Choose a Country..." class="form-control" name="check_date"  tabindex="2" onchange="return getDocTiming(this.value);" required="">
									<option value="">Select Date</option>
									<?php 
									if($_SESSION['login_hosp_id']==""){ ?>
									
									<?php } else { 
									for($i=0; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= mysqlSelect("DISTINCT(b.day_id) as DayId","doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$admin_id."' and a.hosp_id='".$_SESSION['login_hosp_id']."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   
									 $checkHoliday= mysqlSelect("holiday_id","doc_holidays","doc_id='".$admin_id."' and doc_type='1' and DATE_FORMAT(holiday_date,'%d-%m-%Y')='".$current_date."'","","","","");  
									   
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
									}
									 ?>
									</select>
                                
                            </div></div>
                                
									<label class="col-sm-2 control-label">Preferred Time <span class="required">*</span></label>

                                    <div class="col-sm-4"><select data-placeholder="Choose Preferred Time..." class="form-control" name="check_time"  id="check_time" tabindex="2" required="">
												
										</select></div>
                                </div>
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
								
								<div class="form-group">
									<label class="col-sm-2 control-label">Pincode</label>
									<div class="col-sm-4">
									<input type="text" id="pincode_gen" placeholder="Pincode"  name="pincode" value="" class="form-control">								
									</div>									
									
								</div>
								<div id="beforeLoad">
								<div class="form-group">
								<label class="col-sm-2 control-label">City <span class="required">*</span></label>
                                    <div class="col-sm-10">
									<input type="text" name="se_city" value="<?php echo $getRefDet[0]['ref_address']; ?>" class="form-control">
									</div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
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
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
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
								</div>
								<div id="dispCity"></div>
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address"  rows="3"></textarea></div>
                                </div>
								
								<div class="form-group">
                        <label class="control-label col-sm-2">
                          
                        </label>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          
                            <label> <input type="checkbox" name="patient_here" value="1" class="i-checks" >  Is patient here ? </label>
                           
                        
                          
                        </div>
						
                      </div>
								
							
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="ref_appointment" class="btn btn-primary block full-width m-b ">BOOK APPOINTMENT</button>
								</div>
								</div>
							</form>
							</div>
							<div id="after-search">
                    </div>
                </div>-->
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
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
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
            });
        </script>
	
	<script src="js/appointments.js" ></script>
   
</body>

</html>
