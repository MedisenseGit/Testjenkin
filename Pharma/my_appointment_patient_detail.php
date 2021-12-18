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
require_once("../classes/querymaker.class.php");


include('send_text_message.php');
include('send_mail_function.php');

$transid=$_GET['appid'];


$appointmentResult = mysqlSelect("a.appoint_trans_id as Trans_ID,a.pay_status as Pay_Status,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.Time_stamp as Create_Date","appointment_transaction_detail as a inner join referal as b on a.pref_doc=b.ref_id","a.appoint_trans_id='".$transid."'","","","","");
$getPatientDetails = mysqlSelect("*","new_hospvisitor_details","Transaction_id='".$appointmentResult[0]['Trans_ID']."'","","","","");
//Update patient status
if(isset($_POST['cmdAppStatus1'])){
	if($_POST['slct_val1']==1){
		$visitStatus="Confirmed";
	}else if($_POST['slct_val1']==2){
		$visitStatus="Visited";
	}else if($_POST['slct_val1']==3){
		$visitStatus="Cancelled";
	}else if($_POST['slct_val1']==4){
		$visitStatus="Pending";
	}else if($_POST['slct_val1']==5){
		$visitStatus="Missed";
	}
	
	$arrFields = array();
	$arrValues = array();
	
	$arrFields[]= 'pay_status';
	$arrValues[]= $visitStatus;
	//Update Patient Status
	$patientRef=mysqlUpdate('appointment_transaction_detail',$arrFields,$arrValues,"appoint_trans_id='".$_POST['patient_id']."'");
	//GET Patient Details
	$getPatient = mysqlSelect("*","appointment_transaction_detail","appoint_trans_id='".$_POST['patient_id']."'","","","","");
	$getDoc=mysqlSelect("*","referal as a inner join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$getPatient[0]['pref_doc']."'","","","","");
	//Get Timing
	$getTiming = mysqlSelect("*","timings","Timing_id='".$getPatient[0]['Visiting_time']."'","","","","");
	

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
					
	header('location:My-Appointment-Patient-Details?appid='.$_POST['patient_id']);		
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Appointment Patient Details</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
</head>

<body>

    <div id="wrapper">

    <nav class="navbar-default navbar-static-side" role="navigation">
        <?php include_once('sidemenu.php'); ?>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-10">
                    <h2>My Appointment Patient Details</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>Patient Details</strong>
                        </li>
                    </ol>
                </div>
				<div class="col-lg-2 mgTop">
					<a href="Appointments"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>
            </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
								
								<script language="javaScript" src="js/status_validationJs.js"></script>
								<!-- Status Change button -->
								<form method="post" name="frmAppStatus" id="frmAppStatus">
								<input type="hidden" name="slct_val1" value="" />
								<input type="hidden" name="patient_id" value="" />
								<input type="hidden" name="cmdAppStatus1" value="" /> 
								
                                    <div class="m-b-md">
									<div class="btn-group pull-right">
                                        <button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle"><?php echo $appointmentResult[0]['Pay_Status']; ?> <span class="caret"></span></button>
										<ul class="dropdown-menu">
									
									   <li><a href="#" onclick="return ChangeAppStatus1(4,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Pending</a></li>
										<li><a href="#" onclick="return ChangeAppStatus1(1,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Confirmed</a></li>
										<li><a href="#" onclick="return ChangeAppStatus1(2,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Visited</a></li>
										<li><a href="#" onclick="return ChangeAppStatus1(5,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Missed</a></li>
										<li><a href="#" onclick="return ChangeAppStatus1(3,<?php echo $appointmentResult[0]['Trans_ID']; ?>);">Cancelled</a></li>
									  	
										</ul>
									</div>
                                        <h2><?php echo $appointmentResult[0]['Patient_name']; ?>( #<?php echo $appointmentResult[0]['Trans_ID']; ?> )</h2>
                                    </div>
								</form>
                                   
                                </div>
                            </div>
							
                            
                        
                            <div class="row">
                                <div class="col-lg-5">
                                     <dl class="dl-horizontal">

                                        <dt>Reg. Date: </dt> <dd><?php echo date('M d-Y h:i:s',strtotime($appointmentResult[0]['Create_Date'])); ?></dd>
                                        <dt>Transaction ID: </dt> <dd>  <?php echo $appointmentResult[0]['Trans_ID']; ?></dd>
                                        <dt>Age:</dt> <dd><?php echo $$getPatientDetails[0]['pat_age']; ?></dd>
                                        <dt>Gender:</dt> <dd> <?php if($getPatientDetails[0]['pat_gen']=="1"){ echo "Male";} else if($getPatientDetails[0]['pat_gen']=="2"){ echo "Female";} else { echo "NS";}  ?></dd>
										<?php if(!empty($getPatientDetails[0]['Husband_wife_name'])){ ?><dt>Spouse Name:</dt> <dd> <?php echo $getPatientDetails[0]['Husband_wife_name'];  ?></dd><?php } ?>
										<dt>Mobile No:</dt>
                                        <dd class="project-people">
                                        <?php echo $getPatientDetails[0]['Mobile_number']; ?>
										</dd>
										<dt>Email:</dt>
                                        <dd class="project-people">
                                        <?php echo $getPatientDetails[0]['Email_id']; ?>
										</dd>
									</dl>
                                </div>
                                <div class="col-lg-7" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                        <dt>Visit Date:</dt> <dd><?php echo date('M d-Y',strtotime($appointmentResult[0]['Visit_Date']))."<br> Time: ".$getTimeSlot[0]['Timing']; ?></dd>
                                        <dt>Address:</dt> <dd> <?php echo $getPatientDetails[0]['Address'];  ?><br>
													  <?php echo $getPatientDetails[0]['City'];  ?>, <?php echo $getPatientDetails[0]['State'];  ?></dd>
                                        <dt>City:</dt>
                                        <dd class="project-people">
                                        <?php echo $getPatientDetails[0]['City'];  ?>
										</dd>
										<dt>State:</dt>
                                        <dd class="project-people">
                                        <?php echo $getPatientDetails[0]['State'];  ?>
										</dd>
										
										
                                       
                                    </dl>

                                </div>
                            </div>
                            <!--<div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                        <dt>Completed:</dt>
                                        <dd>
                                            
                                            <small>Project completed in <strong>60%</strong>. Remaining close the project, sign a contract and invoice.</small>
                                        </dd>
                                    </dl>
                                </div>
                            </div>-->
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="wrapper wrapper-content project-manager">
                    <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> Reschedule Appointment</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                          <input type="hidden" name="patTransId" id="patTransId" value="<?php echo $transid; ?>" />
                            <div class="form-group">
                            <label class="control-label" for="date_added">Date</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="dateadded" name="dateadded" type="text" required="required" class="form-control" >
                            </div>
							</div>
                            <div class="form-group">
                                <label>Time</label>
								<select class="left form-control autotab" name="selectTime" id="selectTime" required="required" >
                                <option value="0">Timing</option>
										<?php
											$Timing= mysqlSelect("*","timings","","","","","");
											foreach($Timing as $TimeList) {
												
										?>
											<option value="<?php echo $TimeList["Timing_id"]; ?>"><?php echo $TimeList["Timing"]; ?></option>
										<?php
											}
									
										?>	
										</select>
                            </div>
                            <button class="ladda-button ladda-button-demo btn btn-primary bt-reschedule"  data-style="zoom-in">Reschedule</button>
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
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
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

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
 <script src="js/share.js"></script>
	<!-- Sweet alert -->
<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
</body>

</html>
