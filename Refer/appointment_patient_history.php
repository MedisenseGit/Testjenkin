<?php
ob_start();
error_reporting(0); 
session_start();

include('send_text_message.php');
include('send_mail_function.php');
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

$transid=$_GET['pattransid'];

$admin_id = $_SESSION['adminid'];
$Patient_id=$_SESSION['patientid'];

$appointmentResult = $objQuery->mysqlSelect("a.appoint_id as App_ID,a.appoint_trans_id as Trans_ID,a.Time_stamp as Create_Date,b.contact_person as contact_person,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.visit_status as Status,a.patient_name as Patient_name,a.pat_age as Age,a.pat_gen as Gender,a.Mobile_no as Mobile,a.Email_address as Email,a.Address as Address,a.City as City,a.State as State,a.Country as Country,a.pay_status as Pay_Status,a.visit_status as Visit_Status","partner_appointment_transaction as a inner join our_partners as b on a.pref_doc=b.partner_id","a.appoint_trans_id='".$transid."'","","","","");
			
//Update patient status
if(isset($_POST['cmdAppStatus'])){
	if($_POST['slct_val']==1){
		$visitStatus="Confirmed";
	}else if($_POST['slct_val']==2){
		$visitStatus="Visited";
	}else if($_POST['slct_val']==3){
		$visitStatus="Cancel";
	}
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'visit_status';
	$arrValues[]= $visitStatus;
	//Update Patient Status
	$patientRef=$objQuery->mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['patient_id']."'");
	//GET Patient Details
	$getPatient = $objQuery->mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['patient_id']."'","","","","");
	$getDoc=$objQuery->mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPatient[0]['pref_doc']."'","","","","");
	//Get Timing
	$getTiming = $objQuery->mysqlSelect("*","timings","Timing_id='".$getPatient[0]['Visiting_time']."'","","","","");
	

					//Mail Notification to Referred Parties
					/*$url_page = 'status_notification_partner.php';					
					$url = rawurlencode($url_page);
					$url .= "?patname=".urlencode($getPatient[0]['PatName']);
					$url .= "&patplace=" . urlencode($getPatient[0]['Pat_loc']);
					$url .= "&patid=" . urlencode($getPatient[0]['Pat_Id']);
					$url .= "&currentstatus=".urlencode($Current_Status);
					$url .= "&partnername=".urlencode($getPartner[0]['Partner_Name']);
					$url .= "&partnermail=".urlencode($getPartner[0]['Partner_Email']);
					$url .= "&hospname=".urlencode($getHospital[0]['Hosp_Name']);
					$url .= "&hospmail=".urlencode($getHospital[0]['Hosp_Email']);
					send_mail($url);*/
					
					if($_POST['slct_val']==1){
					//Message Notification to Patient only when appointment is confirmed
					$mobile = $getPatient[0]['Mobile_no'];
					$msg = "Appointment Confirmed, TransactionID ". $getPatient[0]['appoint_trans_id'] . " | ". $getPatient[0]['patient_name'] . " | ".$getDoc[0]['spec_name']." | ".$getPatient[0]['Visiting_date']." | ".$getTiming[0]['Timing']." | ".$getDoc[0]['ref_name'];
					send_msg($mobile,$msg);
					}
					
	
	header('location:appointment_patient_history.php?pattransid='.$_POST['patient_id']);		
}
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
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="../Hospital/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">

    <!-- Custom styling plus plugins -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
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
			
			<div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2><i class="fa fa-user"></i> Patient Case History</h2>
					<div class="right">
                <div class="form-group pull-right top_search">
                  <div class="input-group">
                    <a href="Appointments" class="btn btn-primary btn-xs"><i class="fa fa-arrow-left"></i> BACK </a>
                     
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
                          							
                            <div class="col-md-12">
							<h2 class="left"><?php echo $appointmentResult[0]['Patient_name']; ?>( #<?php echo $appointmentResult[0]['Trans_ID']; ?> )</h2>
                              <p class="date text-right"><i class="fa fa-calendar"></i> Reg. Date:  <?php echo date('M d-Y h:i:s',strtotime($appointmentResult[0]['Create_Date'])); ?></p>
                            </div>
							<br><br><br>
                            <div class="col-md-12">
							<!-- info row -->
							<div class="row invoice-info">
							<?php $getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$appointmentResult[0]['Dept']."'","","","","");
							$getTimeSlot= $objQuery->mysqlSelect("*","timings","Timing_id='".$appointmentResult[0]['Visit_Time']."'","","","","");
							$getDoc= $objQuery->mysqlSelect("*","referal","ref_id='".$appointmentResult[0]['Pref_Doc']."'","","","","");
							?>
							
									<div class="col-sm-4 invoice-col">
									  <b>Transaction ID: </b><?php echo $appointmentResult[0]['Trans_ID']; ?>
									  <br><br>
									 <b>Visit Date: </b> <?php echo date('M d-Y',strtotime($appointmentResult[0]['Visit_Date']))." Time: ".$getTimeSlot[0]['Timing']; ?>
									  <br><br> 
									  <b>Patient Name: </b> <?php echo $appointmentResult[0]['Patient_name']; ?>
									  <br><br>
									  <b>Age: </b> <?php echo $appointmentResult[0]['Age']; ?>
										<br><br>
									 <b>Gender: </b><?php if($appointmentResult[0]['Gender']=="1"){ echo "Male";} else if($appointmentResult[0]['Gender']=="2"){ echo "Female";} else { echo "NS";}  ?>
									  <br><br>
									  <b>Spouse Name: </b> <?php echo $appointmentResult[0]['Spouse'];  ?>
									  <br><br>
									   
									  </div>
									<!-- /.col -->
								
								  
									<div class="col-sm-5 invoice-col">
									  
									  <br><br><b>Address</b>
									  <address><?php echo $appointmentResult[0]['Address'];  ?><br>
													  <?php echo $appointmentResult[0]['City'];  ?>, <?php echo $appointmentResult[0]['State'];  ?>
												  </address>
												  
										 <b>City: </b><?php echo $appointmentResult[0]['City'];  ?><br><br>
										 <b>State: </b><?php echo $appointmentResult[0]['State'];  ?><br><br>
										  <b>Mobile No: </b> <?php echo $appointmentResult[0]['Mobile']; ?>
									  <br><br>
									  <b>Email: </b> <?php echo $appointmentResult[0]['Email']; ?><br><br>
									 <b>Doctor: </b><?php echo $appointmentResult[0]['contact_person']; ?><br><br>
										 
									</div>
									<!-- /.col -->
									<div class="col-sm-3 invoice-col">
									<ul class="nav navbar-right">
										<script language="javaScript" src="js/status_validation.js"></script>
										<!-- Status Change button -->
										<form method="post" name="frmAppStatus" id="frmAppStatus">
										  
										<input type="hidden" name="slct_val" value="" />
										<input type="hidden" name="patient_id" value="" />
										<input type="hidden" name="cmdAppStatus" value="" />  
											<div class="btn-group">
											  <button type="button" class="btn btn-success"><?php echo $appointmentResult[0]['Status']; ?></button>
											  <button type="button" class="btn btn-success dropdown-toggle"  data-toggle="dropdown" aria-expanded="false">
												<span class="caret" style="color:#fff;"></span>
												<span class="sr-only">Toggle Dropdown</span>
											  </button>
											  <ul class="dropdown-menu" role="menu">
												<li><a href="#" onclick="return ChangeAppStatus(1,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Confirmed</a>
												</li>
												<li><a href="#" onclick="return ChangeAppStatus(2,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Visited</a>
												</li>
												<li><a href="#" onclick="return ChangeAppStatus(3,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Cancel</a>
												</li>
												
											  </ul>
											</div>
										</form>
										</ul>
									</div>
									<!-- /.col -->
									
								  </div>
								<hr>
								</div>
                          </div>
                         						  
						 
                        </div>

                      </div>
                      <!-- /CONTENT MAIL -->
					  <div class="col-sm-4 mail_list_column" style="min-height:600px;">
					  <!-- Message Section -->
							<?php if($_GET['response']=="reschedule"){ ?> <div class="alert alert-success alert-dismissable">
												<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
												<strong>Appointment has been rescheduled successfully</strong>
											  </div>
								<?php } ?>
                        
						
						<!--START REASSIGN SECTION -->
					
					<div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                      
                        <a class="panel" role="tab" id="headingOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          <h4 class="btn-sm btn-success"> <i class="fa fa-reply"></i> Reschedule the Appointment </h4>   
						
                        </a>
						<form method="post" name="frmReassign" action="add_details.php">
						<input type="hidden" name="Pat_Trans_Id" value="<?php echo $_GET['pattransid']; ?>" />
                        <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                        <a class="panel" role="tab" id="headingOne" data-toggle="collapse" href="#collapseOne"> <span style="float:right; font-size:16px; float:right;margin-right:20px;">X</span></a>
						
						<div class="panel-body">	
								  <div class="col-md-6 col-sm-6 col-xs-12">
							 <!-- <input type="text"  name="se_date_of_birth" id="datepicker" required="required" class="form-control" placeholder="">
							-->
							<input type="text"  name="reschedule_date" id="J-demo-02" required="required" class="form-control" placeholder="">
							<script type="text/javascript">
								$('#J-demo-02').dateTimePicker({
									mode: 'date'
								});
							</script>
							
							</div>
							<div class="col-md-5 col-sm-5 col-xs-12">
							<select class="left form-control autotab" name="selectTime" id="selectTime" >
												
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
							
							</div>
						</div>	
						<div class="panel-body" style="margin-right:30px;">
							<button type="submit" name="cmdreschedule" id="cmdreschedule" class="btn btn-sm btn-primary" style="float:right;"> RESCHEDULE</button>
						</div>
							
							</div>
							</form>
					  
					  </div>
                    	
					
					<!--END REASSIGN SECTION -->
					 
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