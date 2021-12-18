<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$Patient_id=$_GET['p'];
include('../send_text_message.php');
include('../send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$GetPatient = $objQuery->mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_age as patient_age,a.patient_gen as patient_gen,a.merital_status as merital_status,a.weight as weight,a.hyper_cond as hyper_cond,a.diabetes_cond as diabetes_cond,a.qualification as qualification,a.contact_person as contact_person,a.patient_addrs as patient_addrs,a.patient_loc as patient_loc,a.pat_state as pat_state,a.patient_mob as patient_mob,a.patient_email as patient_email,a.patient_complaint as patient_complaint,a.patient_desc as patient_desc,a.pat_query as pat_query,a.TImestamp as TImestamp,b.status1 as status1,b.status2,b.bucket_status as bucket_status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","md5(a.patient_id)='".$Patient_id."'","","","","");
$attachDet = $objQuery->mysqlSelect("*","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$attachCount = $objQuery->mysqlSelect("COUNT(attach_id) as Num_Attach","patient_attachment","md5(patient_id)='".$Patient_id."'","","","","");
$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$GetPatient[0]['medDept']."'" ,"","","","");

				if($GetPatient[0]['patient_gen']=="1"){
					$gender="Male";
				}
				else if($GetPatient[0]['patient_gen']=="2"){
					$gender="Female";
				}
				
				if($GetPatient[0]['hyper_cond']=="0"){
					$hyperStatus="No";
				}
				else if($GetPatient[0]['hyper_cond']=="1"){
					$hyperStatus="Yes";
				}
				if($GetPatient[0]['diabetes_cond']=="0"){
					$diabetesStatus="No";
				}
				else if($GetPatient[0]['diabetes_cond']=="1"){
					$diabetesStatus="Yes";
				}

//Update patient status
if(isset($_POST['cmdchangeStatus'])){
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'status2';
	$arrValues[]= $_POST['slct_val'];
	//Update Patient Status
	$patientRef=$objQuery->mysqlUpdate('patient_referal',$arrFields,$arrValues,"patient_id='".$_POST['patient_id']."'and ref_id='".$_POST['doc_id']."'");
	//GET Patient Details
	$getPatient = $objQuery->mysqlSelect("a.patient_name as PatName,a.patient_loc as Pat_loc,a.patient_id as Pat_Id,b.status2 as Current_Status","patient_tab as a left join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$_POST['patient_id']."'and b.ref_id='".$_POST['doc_id']."'","","","","");
	
	//GET Partner Details
	$getPartner = $objQuery->mysqlSelect("a.partner_name as Partner_Name,a.Email_id as Partner_Email,a.cont_num1 as Partner_Mobile","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$getPatient[0]['patient_src']."'","","","","");
	
	//GET Hospital Datails
	$getHospital = $objQuery->mysqlSelect("a.hosp_name as Hosp_Name,a.hosp_email as Hosp_Email","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$_POST['doc_id']."'","","","","");
	if($getPatient[0]['Current_Status']==13){
		$Current_Status="OP-Visited";
	}
	else if($getPatient[0]['Current_Status']==9){
		$Current_Status="IP-Treated";
	}
					//Mail Notification to Referred Parties
					$url_page = 'status_notification_partner.php';					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['PatName']);
					$url .= "&patplace=" . urlencode($getPatient[0]['Pat_loc']);
					$url .= "&patid=" . urlencode($getPatient[0]['Pat_Id']);
					$url .= "&currentstatus=".urlencode($Current_Status);
					$url .= "&partnername=".urlencode($getPartner[0]['Partner_Name']);
					$url .= "&partnermail=".urlencode($getPartner[0]['Partner_Email']);
					$url .= "&hospname=".urlencode($getHospital[0]['Hosp_Name']);
					$url .= "&hospmail=".urlencode($getHospital[0]['Hosp_Email']);
					send_mail($url);
					
					
					//Message Notification to Referred Parties
					$mobile = $getPartner[0]['Partner_Mobile'];
					$responsemsg = "Dear ".$getPartner[0]['Partner_Name'].", Status for patient ".$getPatient[0]['PatName']." changed to ".$Current_Status." Thanks, ".$getHospital[0]['Hosp_Name'];
					send_msg($mobile,$responsemsg);
	
	header('location:patient-history?p='.$Patient_id.'&c='.$admin_id);		
}

if(isset($_POST['cmdGetId'])){
	$bus_id = $_POST['user_id'];
	$_SESSION['trans_id']=$_POST['user_id'];
	header('location:edit-user');	
}

					
$get_pro = $objQuery->mysqlSelect("ref_id as RefId","referal","doc_spec!=555 and anonymous_status!=1","Tot_responded desc","","","");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Patient History</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
   

    <!-- Custom styling plus plugins -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	<script src="search/jquery-1.11.1.min.js"></script>
	<script src="search/jquery-ui.min.js"></script>
	<script src="search/jquery.select-to-autocomplete.js"></script>
	<script>
	  (function($){
	    $(function(){
	      $('#selectref').selectToAutocomplete();
	      $('#selectref1').selectToAutocomplete();
		  $('#selectref2').selectToAutocomplete();
	    });
	  })(jQuery);
	  function call_refer(){
		   //alert("Logging in.........");
		   var user=document.getElementById('selectref').value;
		   		   
		   if(user==""){
		     alert("Please choose");
			 return false;
		   }
		   
		 }
	</script>

	<style>
	
    .ui-autocomplete {
      padding: 10px;
      list-style: none;
      background-color: #fff;
      width: 720px;
      border: 1px solid #B0BECA;
      max-height: 350px;
      overflow-x: hidden;
	   white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 720px;
    }
    .ui-autocomplete .ui-menu-item {
      border-top: 1px solid #B0BECA;
      display: block;
      padding: 4px 6px;
      color: #353D44;
      cursor: pointer;
    }
    .ui-autocomplete .ui-menu-item:first-child {
      border-top: none;
    }
    .ui-autocomplete .ui-menu-item.ui-state-focus {
      background-color: #D5E5F4;
      color: #161A1C;
    }
	
	</style>
	
	<script type="text/javascript">
    $(function () {
        $("#headingOne").click(function () {
			
				$("#headingOne").hide();
				$("#headingOne2").hide();
				$("#headingOne3").hide();
           
        });
		$("#headingOne2").click(function () {
            
                
				$("#headingOne").hide();
				$("#headingOne2").hide();
				$("#headingOne3").hide();
           
        });
		$("#headingOne3").click(function () {
            
                
				$("#headingOne").hide();
				$("#headingOne2").hide();
				$("#headingOne3").hide();
           
        });
		$("#closetab2").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
				$("#headingOne3").show();
           
        });
		$("#closetab1").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
				$("#headingOne3").show();
           
        });
		$("#closetab3").click(function () {
            
                
				$("#headingOne").show();
				$("#headingOne2").show();
				$("#headingOne3").show();
           
        });
    });
	
	
</script>

<script src="../Hospital/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="../Hospital/date-time-picker.min.js"></script>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       
		<!--Side Menu & Top Navigation -->
        <?php include_once('side_menu.php'); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            
            <div class="clearfix"></div>
			
						<?php if($GetPatient[0]['bucket_status']=="2"){ $patient_status="<span class='label label-warning pull-right'>SENT</span>"; ?>
										<?php } else if($GetPatient[0]['bucket_status']=="3"){  $patient_status="<span class='label label-danger pull-right'>P-AWAITING</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="5"){  $patient_status="<span class='label label-success pull-right'>RESPONDED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="6"){  $patient_status="<span class='label label-info pull-right'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="7"){  $patient_status="<span class='label label-info pull-right'>STAGED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="8"){  $patient_status="<span class='label label-warning pull-right'>OP-DESIRED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="9"){  $patient_status="<span class='label label-success pull-right'>IP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="10"){  $patient_status="<span class='label label-danger pull-right'>NOT-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="11"){  $patient_status="<span class='label label-success pull-right'>INVOICED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="12"){  $patient_status="<span class='label label-success pull-right'>PAYMENT RECEIVED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="13"){  $patient_status="<span class='label label-success pull-right'>OP-CONVERTED</span>";?>
										<?php } else if($GetPatient[0]['bucket_status']=="1"){  $patient_status="<span class='label label-primary pull-right'>NEW</span>"; }?>
						
						
			<div class="right">
			<?php
			
					if($_GET['response']=="book-appointment"){ ?>
						<div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong><i class="fa fa-check" aria-hidden="true"></i> <?php echo "Appointment request has been sent successfully"; ?> </strong>
											  </div>
					<?php }
					if($_GET['response']=="refer"){ ?>
						<div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong><i class="fa fa-check" aria-hidden="true"></i> <?php echo "Patient case has been refer successfully"; ?> </strong>
											  </div>
					<?php }
					
					if($_GET['response']=="error"){ ?>
						<div class="alert alert-danger alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong><?php echo "ERROR!!!!!"; ?> </strong>
											  </div>
					<?php }
					?>
							
				</div>
			
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                   
				   
					<div class="col-sm-8" style="float:left;margin-top:8px; ">
                      
                         <!--<button type="submit" class="btn btn-sm btn-primary" name="" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne" ><i class="fa fa-calendar"></i> SEND APPT. LINK</button>
						<div id="collapseOne1" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                        <span style="margin-left:15px;">Send Appointment Link to Patient</span>
						<a class="panel" role="tab" id="closetab1" data-toggle="collapse" href="#collapseOne1"> <span style="float:right; font-size:16px; float:right;margin-right:20px;">X</span></a>
						 <div class="panel-body">
                            <select class="left form-control autotab" name="selectref" id="selectref" placeholder="Select Panel Doctor" required="required" style="float:left; width:350px; padding:5px;">
													<option value="">Select Panel Doctor</option>
													<?php 
													foreach($get_pro as $listDoc) {
													$get_Ref = $objQuery->mysqlSelect('ref_id,doc_spec,Tot_responded,Total_Referred,Tot_responded,doc_type,ref_name,ref_address,doc_state,doc_keywords','referal',"ref_id='".$listDoc['RefId']."'","","","","");
													$get_spec = $objQuery->mysqlSelect('spec_name','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
													$getHosp = $objQuery->mysqlSelect("a.hosp_name as hosp_name","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
													//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
													
													$ResponseRate=($get_Ref[0]['Tot_responded']/$get_Ref[0]['Total_Referred'])*100;
													
													if($get_Ref[0]['doc_type']=="volunteer"){
														$star="<span style='color:red;'>**</font>";
													} else if($get_Ref[0]['doc_type']=="star"){
														$star="<span style='color:red;'>*****</font>";
													} else if($get_Ref[0]['doc_type']=="featured"){
														$star="<span style='color:red;'>***</font>";
													}else{
														$star="<span style='color:red;'></font>";
														
													}
													?>	
													
													
											  <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo "[".$get_Ref[0]['Tot_responded']."/".$get_Ref[0]['Total_Referred']."=".ceil($ResponseRate)."% ] ".addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_Ref[0]['doc_state'])."&nbsp;".trim(preg_replace('/\s+/',' ', $get_Ref[0]['doc_keywords'])); ?></option>
											<?php } ?>
											</select>
											<button type="submit" name="cmdSendAptLink" id="cmdSendAptLink" class="btn btn-sm btn-primary" style="float:right;"><i class="fa fa-calendar"></i> SEND</button>
							</div>
							</div>-->
                     
                        <button type="submit" class="btn btn-sm btn-success" name="sendAppReq" role="tab" id="headingOne2" data-toggle="collapse" data-parent="#accordion" href="#collapseOne2" aria-expanded="true" aria-controls="collapseOne"><i class="fa fa-credit-card"></i> SEND CASE</button>
						<div id="collapseOne2" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
						<span style="margin-left:15px;">Send Case</span>
                        <a class="panel" role="tab" id="closetab2" data-toggle="collapse" href="#collapseOne2"> <span style="float:right; font-size:16px; float:right;margin-right:20px;">X</span></a>
						 <div class="panel-body">
						  <form method="post" name="frmApptLink" action="get_action.php">
						<input type="hidden" name="Pat_Id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
                            <select class="left form-control autotab" name="selectref1" id="selectref1" placeholder="Select Panel Doctor" required="required" style="float:left; width:350px; padding:5px;">
													<option value="">Select Panel Doctor</option>
													<?php foreach($get_pro as $listDoc) {
													$get_Ref = $objQuery->mysqlSelect('ref_id,doc_spec,Tot_responded,Total_Referred,Tot_responded,doc_type,ref_name,ref_address,doc_state,doc_keywords','referal',"ref_id='".$listDoc['RefId']."'","","","","");
													$get_spec = $objQuery->mysqlSelect('spec_name','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
													$getHosp = $objQuery->mysqlSelect("a.hosp_name as hosp_name","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
													//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
													
													$ResponseRate=($get_Ref[0]['Tot_responded']/$get_Ref[0]['Total_Referred'])*100;
													
													if($get_Ref[0]['doc_type']=="volunteer"){
														$star="<span style='color:red;'>**</font>";
													} else if($get_Ref[0]['doc_type']=="star"){
														$star="<span style='color:red;'>*****</font>";
													} else if($get_Ref[0]['doc_type']=="featured"){
														$star="<span style='color:red;'>***</font>";
													}else{
														$star="<span style='color:red;'></font>";
														
													}
													?>	
													
													
											  <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo "[".$get_Ref[0]['Tot_responded']."/".$get_Ref[0]['Total_Referred']."=".ceil($ResponseRate)."% ] ".addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_Ref[0]['doc_state'])."&nbsp;".trim(preg_replace('/\s+/',' ', $get_Ref[0]['doc_keywords'])); ?></option>
											<?php } ?>
											</select>
											<button type="submit" name="cmdRefer" id="cmdRefer" class="btn btn-sm btn-success" style="float:right;"><i class="fa fa-credit-card"></i> SEND</button>
							</form>
							</div>
							</div>
							
								<button type="submit" class="btn btn-sm btn-primary" name="" role="tab" id="headingOne3" data-toggle="collapse" data-parent="#accordion" href="#collapseOne3" aria-expanded="true" aria-controls="collapseOne" ><i class="fa fa-calendar"></i> BOOK APPOINTMENT</button>
								<div id="collapseOne3" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
								<span style="margin-left:15px;">Book Appointment</span>
								<a class="panel" role="tab" id="closetab3" data-toggle="collapse" href="#collapseOne3"> <span style="float:right; font-size:16px; float:right;margin-right:20px;">X</span></a>
								 <div class="panel-body">
								  <form method="post" name="frmBookAppt" action="get_action.php">
									<input type="hidden" name="Pat_Id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
									<select class="left form-control autotab" name="selectref2" id="selectref2" required="required"  placeholder="Select Panel Doctor" style="float:left; width:200px; padding:5px;">
															<option value="">Select Panel Doctor</option>
															<?php 
															foreach($get_pro as $listDoc) {
															$get_Ref = $objQuery->mysqlSelect('ref_id,doc_spec,Tot_responded,Total_Referred,Tot_responded,doc_type,ref_name,ref_address,doc_state,doc_keywords','referal',"ref_id='".$listDoc['RefId']."'","","","","");
															$get_spec = $objQuery->mysqlSelect('spec_name','specialization',"spec_id='".$get_Ref[0]['doc_spec']."'","","","","");
															$getHosp = $objQuery->mysqlSelect("a.hosp_name as hosp_name","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","doc_id='".$get_Ref[0]['ref_id']."'" ,"","","",""); 
															//$ResponseCount = $objQuery->mysqlSelect("*","patient_referal","ref_id='".$listDoc['ref_id']."'" ,"","","","");
															
															$ResponseRate=($get_Ref[0]['Tot_responded']/$get_Ref[0]['Total_Referred'])*100;
															
															if($get_Ref[0]['doc_type']=="volunteer"){
																$star="<span style='color:red;'>**</font>";
															} else if($get_Ref[0]['doc_type']=="star"){
																$star="<span style='color:red;'>*****</font>";
															} else if($get_Ref[0]['doc_type']=="featured"){
																$star="<span style='color:red;'>***</font>";
															}else{
																$star="<span style='color:red;'></font>";
																
															}
															?>	
															
															
													  <option value="<?php echo $get_Ref[0]['ref_id']; ?>"  ><?php echo "[".$get_Ref[0]['Tot_responded']."/".$get_Ref[0]['Total_Referred']."=".ceil($ResponseRate)."% ] ".addslashes($get_Ref[0]['ref_name'])."&nbsp;".addslashes($get_spec[0]['spec_name'])."&nbsp;".addslashes($getHosp[0]['hosp_name'])."&nbsp;".addslashes($get_Ref[0]['ref_address'])."&nbsp;".addslashes($get_Ref[0]['doc_state'])."&nbsp;".trim(preg_replace('/\s+/',' ', $get_Ref[0]['doc_keywords'])); ?></option>
													<?php } ?>
													</select>
													
													<div class="col-md-4 col-sm-4 col-xs-4">
																				<input type="text"  name="check_date" id="J-demo-02" required="required" class="form-control" placeholder="Select Date">
																				<script type="text/javascript">
																					$('#J-demo-02').dateTimePicker({
																						mode: 'date'
																					});
																				</script>
																			
																		</div>		
																		<div class="col-md-4 col-sm-4 col-xs-4">
																				<select class="left form-control autotab" name="selectTime" required="required" id="selectTime" >
														
																					<option value="">Timing</option>
																						<?php
																							$Timing= $objQuery->mysqlSelect("*","timings","","","","","");
																							foreach($Timing as $TimeList) {
																								
																						?>
																							<option value="<?php echo $TimeList["Timing_id"]; ?>"><?php echo $TimeList["Timing"]; ?></option>
																						<?php
																							}
																					
																						?>		
																			</select>
																		</div>	<br><br><br>
													
													
													<button type="submit" name="bookAppointment" id="bookAppointment" class="btn btn-sm btn-primary" style="float:right;"><i class="fa fa-calendar"></i> REQUEST APPOINTMENT</button>
										</form>
										</div>
									</div>
							
					  
					  </div>
                    	
					
								
					<div class="right">
					<div class="form-group pull-right top_search">
					  <div class="input-group">
						<a href="All-Patient-Records" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> BACK </a>
						 
						</span>
					  </div>
					</div>
				  </div>
						
						
                    <div class="clearfix"></div>
                  </div>
				  
                  <div class="x_content">
                    <div class="row">
                      
                      <!-- CONTENT MAIL -->
                      <div class="col-sm-8 mail_view">
                        <div class="inbox-body">
                          <div class="mail_heading row">
                            <!--<div class="col-md-8">
                              <div class="btn-group">
                                <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                                <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                                <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                              </div>
                            </div>-->
							
                            <div class="col-md-12">
							<h2 class="left"><?php echo $GetPatient[0]['patient_name']; ?>( #<?php echo $GetPatient[0]['patient_id']; ?> )</h2>
                              <p class="date text-right"><i class="fa fa-calendar"></i> Reg. Date:  <?php echo date('H:i A',strtotime($GetPatient[0]['TImestamp'])); ?>  <?php echo date('M d-Y',strtotime($GetPatient[0]['TImestamp'])); ?></p>
                            </div>
							<br><br><br>
                            <div class="col-md-12">
							<!-- info row -->
							<div class="row invoice-info">
									<div class="col-sm-4 invoice-col">
									  <b>Patient ID: </b><?php echo $GetPatient[0]['patient_id']; ?>
									  <br><br>
									
									  <b>Patient Name: </b> <?php echo $GetPatient[0]['patient_name']; ?>
									  <br><br>
									  <b>Age: </b> <?php echo $GetPatient[0]['patient_age']; ?>
									  <br><br>
									  <b>Gender: </b> <?php echo $gender; ?>
									  <br><br>
									  <b>Marital Status: </b> <?php if(empty($GetPatient[0]['merital_status'])){ echo "NS"; } else { echo $GetPatient[0]['merital_status']; } ?>
									  <br><br>
									  <b>Weight: </b> <?php echo $GetPatient[0]['weight'];  ?>
									  <br><br>
									  <b>Hypertension ?: </b> <?php echo $hyperStatus;  ?>
									  <br><br>
									  <b>Diabetes ?: </b> <?php echo $diabetesStatus;  ?>
									  <br><br>
									  <b>Qualification: </b> <?php if(empty($GetPatient[0]['qualification'])){ echo "NS"; } else { echo $GetPatient[0]['qualification']; } ?>
									</div>
									<!-- /.col -->
							
								  
									<div class="col-sm-4 invoice-col">
									  <b>Decision Maker: </b><?php echo $GetPatient[0]['contact_person'];  ?>
									  <br><br><b>Address</b>
									  <address><?php echo $GetPatient[0]['patient_addrs'];  ?><br>
													  <?php echo $GetPatient[0]['patient_loc'];  ?>, <?php echo $GetPatient[0]['pat_state'];  ?>
												  </address>
												  
										 <b>City: </b><?php echo $GetPatient[0]['patient_loc'];  ?><br><br>
										 <b>State: </b><?php echo $GetPatient[0]['pat_state'];  ?><br><br>
										 <b>Department: </b><?php echo $getDept[0]['spec_name']; ?><br><br>
										 <b>Contact No. : </b><?php echo $GetPatient[0]['patient_mob']; ?><br><br>
										 <b>Email : </b><?php echo $GetPatient[0]['patient_email']; ?><br>
									</div>
									<!-- /.col -->
									<div class="col-sm-4 invoice-col">
									<ul class="nav navbar-right">
										<?php echo $patient_status; ?>
										</ul>
									</div>
									<!-- /.col -->
									
								  </div>
								<hr>
								</div>
                          </div>
                           <?php if(!empty($GetPatient[0]['patient_complaint'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Patient Complaint</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['patient_complaint'];  ?></p>
                          </div>
						  <?php } ?>
						  
						  <?php if(!empty($GetPatient[0]['patient_desc'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Description</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['patient_desc'];  ?></p>
                          </div>
						  <?php } ?>
						  
						   <?php if(!empty($GetPatient[0]['pat_query'])){ ?><div class="sender-info">
                            <div class="row">
                              <div class="col-md-12">
                                <strong>Medical Query</strong>
                                
                                <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                              </div>
                            </div>
                          </div>
						 
                          <div class="view-mail">
						    <p><?php echo $GetPatient[0]['pat_query'];  ?></p>
                          </div>
						  <?php } ?>
                          <div class="attachment">
                            <p>
                              <span><i class="fa fa-paperclip"></i> <?php echo $attachCount[0]['Num_Attach']; ?> attachments </span>
                              <!--<a href="#">Download all attachments</a> |
                              <a href="#">View all images</a>-->
                            </p>
                            <ul>
							<?php foreach($attachDet as $attachList){ 
							//Here we need to check file type
							$img_type =  array('gif','png' ,'jpg' ,'jpeg');
							$extractPath = pathinfo($attachList['attachments'], PATHINFO_EXTENSION);
							if(in_array($extractPath,$img_type) ) {
								$imgIcon="../Attach/".$attachList['attach_id']."/".$attachList['attachments'];
							}
							else if($extractPath=="docs"){
									$imgIcon="../Hospital/images/docs_icon.png";
							}
							else{
								$imgIcon="../Hospital/images/PDF-Icon.png";
							} ?>
                              <li>
                                <a href="#" class="atch-thumb">
                                  <img src="<?php echo $imgIcon; ?>" alt="<?php echo $attachList['attachments']; ?>"/>
                                </a>

                                <div class="file-name">
                                 <?php echo substr($attachList['attachments'],0,10); ?>
                                </div>
                               <!--<span>12KB</span>-->


                                <div class="links">
                                  <a href="../Attach/<?php echo stripslashes($attachList['attach_id']);?>/<?php echo stripslashes($attachList['attachments']);?>" target="_blank"  title="<?php echo stripslashes($attachList['attachments']);?>">View</a> -
                                  <a href="https://medisensecrm.com/download-Attachments.php?attach_id=<?php echo stripslashes($attachList['attach_id']);?>&attach_name=<?php echo $attachList['attachments']; ?>" target="_blank">Download</a>
                                </div>
                              </li>
							<?php } ?>
                              

                            </ul>
                          </div>
                         <!-- <div class="btn-group">
                            <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                            <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                            <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                          </div>-->
                        </div>

                      </div>
                      <!-- /CONTENT MAIL -->
					  <div class="col-sm-4 mail_list_column" style="min-height:600px;">
					  <!-- Message Section -->
							<?php if($_GET['response']==1){ ?> <div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Success!</strong> <?php echo "response has been sent to the patient"; ?>
											  </div>
								<?php } ?>
							
                      
					
						<?php $getRefDoc = $objQuery->mysqlSelect("a.patient_id as patient_id,a.status2 as status2,b.doc_photo as doc_photo,b.ref_id as ref_id,b.ref_name as ref_name,c.spec_name as spec_name,a.timestamp as timestamp","patient_referal as a inner join referal as b on a.ref_id=b.ref_id inner join specialization as c on c.spec_id=b.doc_spec","a.patient_id='".$GetPatient[0]['patient_id']."'","a.timestamp desc","","","");
						foreach($getRefDoc as $getRefDocList){ 
                        
										if($getRefDocList['status2']=="2"){ $patient_status="<span class='label label-warning pull-right'>SENT</span>"; ?>
										<?php } else if($getRefDocList['status2']=="3"){  $patient_status="<span class='label label-danger pull-right'>P-AWAITING</span>";?>
										<?php } else if($getRefDocList['status2']=="5"){  $patient_status="<span class='label label-success pull-right'>RESPONDED</span>";?>
										<?php } else if($getRefDocList['status2']=="6"){  $patient_status="<span class='label label-info pull-right'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($getRefDocList['status2']=="7"){  $patient_status="<span class='label label-info pull-right'>STAGED</span>";?>
										<?php } else if($getRefDocList['status2']=="8"){  $patient_status="<span class='label label-warning pull-right'>OP-DESIRED</span>";?>
										<?php } else if($getRefDocList['status2']=="9"){  $patient_status="<span class='label label-success pull-right'>IP-CONVERTED</span>";?>
										<?php } else if($getRefDocList['status2']=="10"){  $patient_status="<span class='label label-danger pull-right'>NOT-CONVERTED</span>";?>
										<?php } else if($getRefDocList['status2']=="11"){  $patient_status="<span class='label label-success pull-right'>INVOICED</span>";?>
										<?php } else if($getRefDocList['status2']=="12"){  $patient_status="<span class='label label-success pull-right'>PAYMENT RECEIVED</span>";?>
										<?php } else if($getRefDocList['status2']=="13"){  $patient_status="<span class='label label-success pull-right'>OP-CONVERTED</span>";?>
										<?php } ?>
								
						
						
                          <div class="mail_list" style="padding-top:20px;">
						 
                            <div class="left">
                              <?php if(!empty($getRefDocList['doc_photo'])){ ?>
											<img src="../Doc/<?php echo $getRefDocList['ref_id']; ?>/<?php echo $getRefDocList['doc_photo']; ?>" width="50" alt="User Avatar" class="img-circle" />
											<?php } else { ?>
											<img src="images/anonymous-profile.png" width="50" alt="User Avatar" class="img-circle" style="margin-right:15px;" />
											<?php } ?>
							  
							  
                            </div>
                            <div style="margin-left:60px;">
                              <h3><?php echo $getRefDocList['ref_name'];  ?> <?php echo $patient_status; ?><br>
							  <small class="pull-left"> <?php echo $getRefDocList['spec_name'];  ?></small>&nbsp;&nbsp;&nbsp;
							  <small style="float:right;">Ref.Date <i class="fa fa-calendar"></i> <?php echo date('d-M-Y H:i',strtotime($getRefDocList['timestamp']));  ?></small></h3>
                              <br><p><a href="#myModal<?php echo $getRefDocList['ref_id']; ?>" class="label label-primary" data-toggle="modal" ><i class="fa fa-edit"></i> - <small>VIEW RESPONSE</small></a></p>
                            </div>
                          </div>
						  <!--Popup window -->
						  <?php $getchatHistory = $objQuery->mysqlSelect("b.ref_id as ref_id,b.ref_name as ref_name,a.chat_note as chat_note,b.doc_photo as doc_photo,a.TImestamp as Ref_Date","chat_notification as a inner join referal as b on a.ref_id=b.ref_id","a.patient_id='".$getRefDocList['patient_id']."'and a.ref_id='".$getRefDocList['ref_id']."'","a.chat_id desc","","","");
						?>
                        <div id="myModal<?php echo $getRefDocList['ref_id']; ?>" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel"><?php echo $getRefDocList['ref_name'];  ?> Interaction</h4>
                        </div>
																	<div class="modal-body" style="padding-bottom:50px;">
																		<label for="inputComment">Comments</label>
																		<textarea class="form-control" id="txtComment" name="txtComment" rows="2"></textarea>
																			<div class="pull-right"  style="margin-top:10px;">	
																			<input type="checkbox" name="slctCondition" />Send to patient	
																			<button type="submit"  name="addComment" id="addComment" class="btn btn-primary">Submit</button>
																			<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
																			</div>
																	</div>
                        <!--<div class="modal-body">
                          <h4><?php echo $getRefDocList['ref_name'];  ?></h4><small><?php echo $getRefDocList['spec_name'];  ?></small>
						 <?php  foreach($getchatHistory as $chatList){ ?>
                          <p><?php echo $chatList['chat_note']; ?></p>
                         <?php } ?>
                        </div>-->
						
						<ul class="list-unstyled msg_list" style="margin-left:10px;">
                    <?php  foreach($getchatHistory as $chatList){ ?>
					<li>
                      <a>
                        <span class="image">
                         <?php if(!empty($chatList['doc_photo'])){ ?>
											<img src="../Doc/<?php echo $chatList['ref_id']; ?>/<?php echo $chatList['doc_photo']; ?>"  alt="img" />
											<?php } else { ?>
											<img src="images/anonymous-profile.png" alt="img" style="margin-right:15px;" />
											<?php } ?>
                        </span>
                        <span>
                          <span><?php echo $chatList['ref_name']; ?></span>
                          <span class="time"><?php echo date('d-M-Y H:i:s',strtotime($chatList['Ref_Date'])); ?></span>
                        </span><br>
                        <p class="modal-body">
                          <?php echo $chatList['chat_note']; ?>
                        </p>
                      </a>
                    </li>
					<?php } ?>
                    
                  </ul>
                </div>
                        

                      </div>
                    </div>
                  <?php } ?>
				  
				  
				  </div>
						
						
						
					
					 
                      </div>
                      <!-- /MAIL LIST -->
					  
					  
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <?php include_once('footer.php'); ?>
      </div>
    </div>

    <!-- compose -->
	<form action="add_details.php" method="post" name="frmActivity" id="frmActivity">
		<input type="hidden" name="patient_id" value="<?php echo $GetPatient[0]['patient_id']; ?>" />
		<input type="hidden" name="doc_id" value="<?php echo $GetPatient[0]['ref_id']; ?>" />
		<input type="hidden" name="ency_patient_id" value="<?php echo $Patient_id; ?>" />
		<input type="hidden" name="ency_admin_id" value="<?php echo $admin_id; ?>" />
									
		<div class="compose col-md-6 col-xs-12">
		  <div class="compose-header">
			Respond Here
			<button type="button" class="close compose-close">
			  <span>×</span>
			</button>
		  </div>
		<textarea style="width:100%; border:none;" rows="8" name="txtDesc" id="txtDesc" placeholder="Text written here will be visible to patients" ></textarea>
		<div class="compose-footer">
			<button type="submit" id="addActivity" name="addActivity" class="btn btn-sm btn-success" >SUBMIT</button>
		</div>
		</div>
	</form>
    <!-- /compose -->
	
	
	
	
	
    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../Hospital/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../Hospital/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../Hospital/vendors/google-code-prettify/src/prettify.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>