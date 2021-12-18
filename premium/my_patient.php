<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id 		= $_SESSION['user_id'];
$secretary_id 	= $_SESSION['secretary_id'];

include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}

date_default_timezone_set('Asia/Kolkata');
$Cur_Date	=	date('Y-m-d H:i:s');
$cur_Date	=	date('Y-m-d',strtotime($Cur_Date));
$curYear 	= date('Y');
$curMonth 	= date('M');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(!isset($_GET['start']))
{
	$start = 0;
}
else
{
	$start = $_GET['start'];
}

$eu = ($start - 0); 
$limit = 50;         // No of records to be shown per page.
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit;
			
			
//$allRecord 	= mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","doc_my_patient","doc_id='".$admin_id."'","TImestamp desc","","","$eu, $limit");

$allRecord 	= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,b.city as patient_loc,a.created_date as TImestamp","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.doc_id='".$admin_id."'","a.created_date desc","","","$eu, $limit");




$pag_result = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","a.created_date desc");
$nume = count($pag_result);  
  
$getDocEMR = mysqlSelect("spec_group_id","specialization as a left join doc_specialization as b on a.spec_id=b.spec_id","b.doc_id='".$admin_id."' and b.doc_type='1'","","","","");
				
if($getDocEMR[0]['spec_group_id']==1)
{ 
	//If 'spec_group_id' is 1, Then it will navigate to Cardio Diabetic EMR
	$navigateLink = "My-Patient-Details";
}
else if($getDocEMR[0]['spec_group_id']==2)
{
	//If 'spec_group_id' is 2, Then it will navigate to Ophthal EMR
	$navigateLink = "Ophthal-EMR/";
}  
$get_doc_details 	= mysqlSelect("*","referal","ref_id='".$admin_id."'","","","","");
$total_visits 		= mysqlSelect("episode_id","doc_patient_episodes","admin_id='".$admin_id."'","");	
$checkSetting		= mysqlSelect("*","doctor_settings","doc_id='".$admin_id."' and doc_type='1'","","","","");				


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patients</title>

   <?php include_once('support.php'); ?>
		<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/jsTree/style.min.css" rel="stylesheet">
	

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: 'get_pincode.php'
 });
});

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
}

function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
</script>

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12 mgTop">
					<div class="search-form">
                                <form action="add_details.php" method="post" autocomplete="off">
								<input type="hidden" name="curURI" value="My-Patient-Details" />
                                    <div class="input-group">
				
                                       <input type="text" id="serPatient" placeholder="Enter name or mobile number to search an existing patient or add a new patient" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>&nbsp;&nbsp;&nbsp;
											<a href="#"  data-toggle="modal" data-target="#myModal" class="btn btn-lg btn-primary m-r" >
                                              <i class="fa fa-wheelchair"></i> Add New
                                            </a>&nbsp;&nbsp;&nbsp;
											<a href="#" data-toggle="modal" data-target="#myModaWaiting" class="btn btn-lg btn-warning" >
                                                <i class="fa fa-clock-o"></i> <!--<img src="waiting_room_icon.png" width="22"/>-->Waiting Room
                                            </a>
											
                                        </div>
                                    </div>

                                </form>
                    </div>  

				<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
                                            <h4 class="modal-title"><?php if($_GET['p']==="0"){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?></h4>
                                            <small class="font-bold">Patient Profile</small>
                                        </div>
										<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
									<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
                                    
                                        <div class="modal-body">
                                            <div class="form-group"><label>Patient Name</label> <input type="text" id="se_pat_name" name="se_pat_name" value="<?php if($_GET['p']==="0"){ echo $_GET['n']; } else { echo $patient_tab[0]['patient_name']; } ?>" class="form-control"></div>
                                 
								 <div class="row"><div class="form-group"><label class="col-sm-2 control-label"><?php if($checkSetting[0]['patient_age_type']=="0"){ echo "Age"; } else if($checkSetting[0]['patient_age_type']=="1") { echo "DOB"; } ?></label>
                                    <div class="col-sm-4">
									<?php if($checkSetting[0]['patient_age_type']=="0"){ ?>
									<input type="text" id="se_pat_age" name="se_pat_age" value="<?php echo $patient_tab[0]['patient_age']; ?>" class="form-control">
									 <?php } else if($checkSetting[0]['patient_age_type']=="1") { ?>
									 <input id="dateadded" name="date_birth" type="text"  placeholder="DD/MM/YYYY" class="form-control" >
                                    <?php } ?>
								   </div>
									<label class="col-sm-2 control-label">Gender</label>
                                    <div class="col-sm-4">
									<?php if($patient_tab[0]['patient_gen']=="1"){ ?>

										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender" checked="checked">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									<?php } else if($patient_tab[0]['patient_gen']=="2") { ?>
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
									<input type="text"  placeholder="in cm"  name="height" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" value="<?php echo $patient_tab[0]['height']*30; ?>" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Weight(Kgs)</label>
									<div class="col-sm-3">
									<input type="text" placeholder="in kgs"  name="weight" maxlength="3" value="<?php echo $patient_tab[0]['weight']; ?>" class="form-control">								
									</div>
								</div>
								
										
										<div class="form-group"><label>Country</label> <select class="form-control" name="se_country" name="se_country">
														<option value="India" <?php echo (!isset($patient_tab[0]['pat_country']) ? 'selected' : ($patient_tab[0]['pat_country'] == 'India' ? 'Selected' : '' ) ) ?> selected>India</option>
														<?php
														$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
														$i=30;
														foreach($getCountry as $CountryList){
														?>

														<option value="<?php echo stripslashes($CountryList['country_name']); ?>" <?php echo ($patient_tab[0]['pat_country'] == stripslashes($CountryList['country_name']) ? 'selected' : '') ?> />
														<?php echo stripslashes($CountryList['country_name']);?></option>


														<?php
														$i++;
														}?>
													</select></div>
										<div class="form-group"><label>State</label> <select class="form-control"  name="se_state" id="se_state" placeholder="State"  >
														<option value="">Select State</option>
														<?php
														$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
														 
														foreach ($GetState as $StateList) {
														?>
														<option value="<?php echo $StateList["state_name"];	?>" <?php echo ($get_doc_details[0]['doc_state'] == $StateList["state_name"] ? 'selected' : '' ) ?> ><?php echo $StateList["state_name"]; ?></option>

														<?php
														}
													
														?>
													</select></div>
										
										<div class="form-group"><label>City</label> <input type="text" id="se_city" name="se_city" value="<?php if(!empty($patient_tab[0]['patient_loc'])) { echo $patient_tab[0]['patient_loc'];} else { echo $get_doc_details[0]['ref_address']; } ?>" class="form-control"></div>
										
										<div class="form-group"><label>Address</label> <input type="text" id="se_address" name="se_address" value="<?php echo $patient_tab[0]['patient_addrs']; ?>" class="form-control"></div>
										<div class="form-group"><label>Mobile</label> <input type="text" id="se_phone_no" name="se_phone_no" value="<?php echo $patient_tab[0]['patient_mob']; ?>" class="form-control" maxlength="10"></div>
										<div class="form-group"><label>Email</label> <input type="email" id="se_email" name="se_email" value="<?php echo $patient_tab[0]['patient_email']; ?>" class="form-control"></div>
																				
									
										</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
											
                                            <button type="submit" name="save_patient" class="btn btn-primary">SAVE</button>

                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
							
							
							<div class="modal inmodal" id="myModaWaiting" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" >
                                <div class="modal-content animated bounceInRight" style="background:url('bg_image1.jpg');">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true" class="text-white">&times;</span><span class="sr-only text-white">Close</span></button>
                                            <h4 class="modal-title text-white">Waiting Room</h4>
											 <h6 class="modal-title text-white"><i class="fa fa-hospital-o"></i> <?php echo $_SESSION['login_hosp_name']; ?></h6>
                                            <small class="text-white"><i class="fa fa-calendar"></i> <?php echo date('d M Y'); ?></small>
                                        </div>
										<div class="ibox-content">
							
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Token</th>
                                <th>Name</th>
                                <th>Visit Time</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php
							
							$get_app_token_details = mysqlSelect("*","appointment_token_system","doc_id='".$admin_id."' and doc_type='1' and hosp_id='".$_SESSION['login_hosp_id']."' and app_date='".$cur_Date."' and status!='Cancelled'","token_no asc","","","");
							
							if(COUNT($get_app_token_details)>0){
							while(list($key, $value) = each($get_app_token_details)){
								if($value['status']=="Pending"){
									$appStatus="text-danger";
								} 
								else if($value['status']=="At reception")
								{
									$appStatus="text-warning";
								}
								else if($value['status']=="Consulted")
								{									
									$appStatus="text-navy";
								}
								else if($value['status']=="Missed")
								{									
									$appStatus="text-danger";
								}
							?>
                            <tr>
                               <td><?php if($value['token_no']!="555") { echo "<button class='btn btn-success btn-circle' type='button'>".$value['token_no']."</button>";} else { echo "<button class='btn btn-primary btn-xs' type='button'>Online</button>"; } ?></td>
								<td><a href="My-Patient-Details?p=<?php echo md5($value['patient_id']); ?>"><?php echo $value['patient_name']; ?></a></td>
                                <td><span class="line"><i class="fa fa-clock-o"></i> <?php echo $value['app_time']; ?></span></td>
                                
                                <td class="<?php echo $appStatus; ?>"><b> <?php echo $value['status']; ?> </b></td>
                            </tr>
							<?php } 
							}
							?>
                         
                            </tbody>
                        </table>
                    </div>
                                    </div>
                                </div>
                            </div>
			   </div>
		</div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Patient Visit List</h5>
                        <span class="text-navy pull-right">Total Patient Visits: <?php echo count($total_visits); ?></span>
                    </div>
                    <div class="ibox-content">
					  <div id="jstree1">
					  
		
                        <ul>
						<?php
						$getYearly = mysqlSelect("DISTINCT(DATE_FORMAT(a.date_time,'%Y')) as Get_Year","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."'","","","","");
						
						foreach($getYearly as $getYearlyList){
							$getYearlyCount = mysqlSelect("COUNT(a.patient_id) as Yearly_Count","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%Y') ='".$getYearlyList['Get_Year']."'","","","","");
						?>
                            <li <?php if($getYearlyList['Get_Year'] == $curYear){ echo "class='jstree-open'";} ?>><?php echo $getYearlyList['Get_Year'];?> (<span class="text-navy"><?php echo $getYearlyCount[0]['Yearly_Count']; ?></span>)
                                <ul>
								<?php	$getMonthly = mysqlSelect("DISTINCT(DATE_FORMAT(a.date_time,'%M')) as Get_Month","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%Y') ='".$getYearlyList['Get_Year']."'","DATE_FORMAT(a.date_time,'%M') asc","","",""); 
									foreach($getMonthly as $getMonthlyList){
										$getMonthlyCount = mysqlSelect("COUNT(a.patient_id) as Monthly_Count,a.patient_id as patient_id","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%Y') ='".$getYearlyList['Get_Year']."' and DATE_FORMAT(a.date_time,'%M') ='".$getMonthlyList['Get_Month']."'","","","","");
						
								?>
						
						
                                    <li <?php if(substr($getMonthlyList['Get_Month'],0,3) == $curMonth){ echo "class='jstree-open'"; } ?>><?php echo $getMonthlyList['Get_Month']; ?> (<span class="text-navy"><?php echo $getMonthlyCount[0]['Monthly_Count']; ?></span>)
										<ul>
										<?php $getDate = mysqlSelect("DISTINCT(DATE_FORMAT(a.date_time,'%d %M')) as Get_Date","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%Y') ='".$getYearlyList['Get_Year']."' and DATE_FORMAT(a.date_time,'%M') ='".$getMonthlyList['Get_Month']."'","DATE_FORMAT(a.date_time,'%d') desc","","",""); 
											foreach($getDate as $getDateList){
												$getDateCount = mysqlSelect("COUNT(a.patient_id) as Date_Count","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%Y') ='".$getYearlyList['Get_Year']."' and DATE_FORMAT(a.date_time,'%M') ='".$getMonthlyList['Get_Month']."' and DATE_FORMAT(a.date_time,'%d %M') ='".$getDateList['Get_Date']."'","","","","");
						
										?>
                                        <li><?php echo $getDateList['Get_Date']; ?> (<span class="text-navy"><?php echo $getDateCount[0]['Date_Count']; ?></span>)
											<ul>
											<?php $getDatePatient = mysqlSelect("a.patient_id as patient_id,b.patient_name as patient_name","doc_patient_episodes as a left join doc_my_patient as b on a.patient_id=b.patient_id","a.admin_id='".$admin_id."' and DATE_FORMAT(a.date_time,'%d %M') = '".$getDateList['Get_Date']."'","b.patient_name asc","","",""); 
											foreach($getDatePatient as $getDatePatientList){
												//$getDateCount = mysqlSelect("COUNT(patient_id) as Date_Count","doc_my_patient","doc_id='".$admin_id."' and DATE_FORMAT(system_date,'%Y') ='".$getYearlyList['Get_Year']."' and DATE_FORMAT(system_date,'%M') ='".$getMonthlyList['Get_Month']."' and DATE_FORMAT(system_date,'%d %M') ='".$getDateList['Get_Date']."'","","","","");
						
											?>
											
                                            <li data-jstree='"type":"css"}' onclick="window.location.href='<?php echo $navigateLink; ?>?p=<?php echo md5($getDatePatientList['patient_id']); ?>';"><?php echo $getDatePatientList['patient_name']; ?></li>
                                            <?php } ?>
											</ul>
										</li>
										<?php } ?>
										</ul>
                                    </li>
                               <?php } ?>
                                  
                                    
                                </ul>
                            </li>
						<?php } ?>	
                        </ul>
                    </div>
					<!--<div class="search-form">
                                <form method="post" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search Patient" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>
					<div id="allPatient">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reg. Date</th>                               
								<th>Contact Details</th>
								<th>Delete</th>
                               	
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ ?>
										
										
                            <a href="Home"><tr>
                               <td><a href="<?php echo $navigateLink; ?>?p=<?php echo md5($list['patient_id']); ?>"><?php echo $list['patient_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($list['TImestamp']));  ?></td>
                                <td><i class="fa fa-envelope"></i> <?php echo $list['patient_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list['patient_mob'];  ?></td>
                                <td> <a href="#" onclick="return deleteMyPatient(<?php echo $list['patient_id']; ?>);"><span class="label label-danger">Delete</span></a> </td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
						</div>
						<div id="afterDel"></div>-->
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-wheelchair"></i> Recently Added Patients</h5>
                       <span class="text-navy pull-right">Total Patients: <?php echo $nume; ?></span>
                    </div>
                    <div class="ibox-content">
					 
					<div id="allPatient">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reg. Date</th>                               
								<th>Contact Details</th>
								<?php if($secretary_id!=1) { ?> <th>Delete</th><?php } ?>
                               	
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ ?>
										
										
                            <a href="Home"><tr id="myTableRow<?php echo $list['patient_id']; ?>">
                               <td><a href="<?php echo $navigateLink; ?>?p=<?php echo md5($list['patient_id']); ?>"><?php echo $list['patient_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($list['TImestamp']));  ?></td>
                                <td><?php if(!empty($list['patient_email'])){ ?><i class="fa fa-envelope"></i> <?php echo $list['patient_email'];  ?><br><?php } ?>
											<?php if(!empty($list['patient_mob'])){ ?><i class="fa fa-mobile"></i> <?php echo $list['patient_mob'];  ?><?php } ?></td>
                                <?php if($secretary_id!=1) { ?><td><a href="#" class="delPatient" data-patient-id="<?php echo $list['patient_id']; ?>"><span class="label label-danger">Delete</span></a></td><?php } ?>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
						</div>
						<div id="afterDel"></div>
						<?php if($nume>=$limit){ ?>
						<div class="row">
					 
					<?php  
				if($nume>=500){
					$tot_num=500;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				
				if($back >=0){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $back; ?>' class='btn btn-white'><i class='fa fa-chevron-left'></i></a> 
				<?php }else{
				 
				}
				
				$i=0;
				$l=1;
				
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $i; ?>'><button class='btn btn-white' ><?php echo $l; ?></button></a>
				<?php	} else { ?>
				<button class='btn btn-white active' ><?php echo  $l; ?></button>
				<?php	}
					$l=$l+1;
				}
				
				if($this1 < $nume) { ?>					
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $next; ?>' class='btn btn-white'><i class='fa fa-chevron-right'></i></button></a>
				<?php } else 
				{
				
				}
				?>
				
                    </div>
						<?php } ?>
					
					<!--<div class="search-form">
                                <form action="add_details.php" method="post" autocomplete="off">
								<input type="hidden" name="curURI" value="<?php echo "My-Patient-Details";?>" />
                                    <div class="input-group">
				
                                       <input type="text" id="serPatient" placeholder="Enter name or mobile number to search an existing patient" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>
                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="my_patient_profile_save.php"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-5"><input type="text" name="se_pat_name" required="required" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-3"><input type="text" name="se_pat_age" class="form-control" maxlength="3"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="se_phone_no" required="required" maxlength="10" minlength="10" placeholder="10 digit mobile no."></div>
								<label class="col-sm-2 control-label ">Gender</label>
                                      <div class="col-sm-4">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										</div>									
                                </div>
								<div class="form-group">
									<label class="col-sm-3 control-label">Height(Centimeter)</label>
									<div class="col-sm-3">
									<input type="text"  placeholder="in cm"  name="height" value="" id="aninput" onkeypress="return validateFloatKeyPress(this,event);" class="form-control" maxlength="3">								
									</div>
									<label class="col-sm-2 control-label">Weight(Kgs)</label>
									<div class="col-sm-3">
									<input type="text" placeholder="in kgs"  name="weight" value="" class="form-control">								
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-2 control-label">Pincode</label>
									<div class="col-sm-4">
									<input type="text" id="coding_language" placeholder="Pincode" onblur="return getCityState(this.value);" name="pincode" value="" class="form-control">								
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

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" name="se_state" id="se_state" tabindex="2">
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
								
								<div class="form-group"><label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-10"><input type="email" name="se_email" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address" rows="3"></textarea></div>
                                </div>
								
								
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="save_patient" class="btn btn-primary block full-width m-b ">SAVE</button>
								</div>
								</div>
							</form>
							</div>-->
                    </div>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Custom Theme Scripts -->
<script src="../assets/js/custom.min.js"></script>
<!-- Chosen -->
<script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
<script>
       
$('.chosen-select').chosen({width: "100%"});

$(document).ready(function() {
$(".oceanIn").keyup(function() {
var total = 0.0;
$.each($(".oceanIn"), function(key, input) {
if(input.value && !isNaN(input.value)) {
total += parseFloat(input.value);
}
});
$("#oceanTotal").html("Total: " + total);
});
});

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
$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
									
?>
	$('.typeahead_1').typeahead({
	   source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
	});
			  

});
		
</script>
<script src="../assets/js/plugins/jsTree/jstree.min.js"></script>

<style>
    .jstree-open > .jstree-anchor > .fa-folder:before {
        content: "\f07c";
    }

    .jstree-default .jstree-icon.none {
        width: 0;
    }
</style>

<script>
$(document).ready(function(){

    $('#jstree1').on('click', '.jstree-anchor', function (e) {
    $('#jstree1').jstree(true).toggle_node(e.target);
	}).jstree({
            'core' : {
                'check_callback' : true
            },
            'plugins' : [ 'types', 'dnd' ],
            'types' : {
                'default' : {
                    'icon' : 'fa fa-folder'
                },
                'html' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'svg' : {
                    'icon' : 'fa fa-file-picture-o'
                },
                'css' : {
                    'icon' : 'fa fa-file-code-o'
                },
                'img' : {
                    'icon' : 'fa fa-file-image-o'
                },
                'js' : {
                    'icon' : 'fa fa-file-text-o'
                }

            }
        });

        $('#using_json').jstree({
            'core' : {
            'data' : [
                'Empty Folder',
                {
                    'text': 'Resources',
                    'state': {
                        'opened': true
                    },
                    'children': [
                        {
                            'data': 'css',
                            'children': [
                                {
                                    "data":"Facebook", "metadata":{"href":"http://www.fb.com"}
                                },
                                {
                                    'text': 'bootstrap.css', 'icon': 'none'
                                },
                                {
                                    'text': 'main.css', 'icon': 'none'
                                },
                                {
                                    'text': 'style.css', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        },
                        {
                            'text': 'js',
                            'children': [
                                {
                                    'text': 'bootstrap.js', 'icon': 'none'
                                },
                                {
                                    'text': 'inspinia.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'jquery.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'jsTree.min.js', 'icon': 'none'
                                },
                                {
                                    'text': 'custom.min.js', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        },
                        {
                            'text': 'html',
                            'children': [
                                {
                                    'text': 'layout.html', 'icon': 'none'
                                },
                                {
                                    'text': 'navigation.html', 'icon': 'none'
                                },
                                {
                                    'text': 'navbar.html', 'icon': 'none'
                                },
                                {
                                    'text': 'footer.html', 'icon': 'none'
                                },
                                {
                                    'text': 'sidebar.html', 'icon': 'none'
                                }
                            ],
                            'state': {
                                'opened': true
                            }
                        }
                    ]
                },
                'Fonts',
                'Images',
                'Scripts',
                'Templates',
            ]
        } });

    });
</script>
<script language="JavaScript" src="js/status_validationJs.js"></script>
</body>

</html>
