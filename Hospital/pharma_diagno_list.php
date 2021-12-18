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
	
	
	//PHARMA TABLE DETAILS
	$getPharma = mysqlSelect("*","pharma","hospital_id='".$admin_id."'","","","","");
	//DIAGNOSTIC TABLE DETAILS
	$getDiagnos = mysqlSelect("*","Diagnostic_center","hospital_id='".$admin_id."'","","","","");
	$sel_hosp_name = mysqlSelect("hosp_name as Hosp_name","hosp_tab ","hosp_id='".$admin_id."'","","","","");
	
	//$get_diagnoInfo = mysqlSelect("count(a.dr_id) as did","diagnostic_referrals as a inner join Diagnostic_center as b on b.diagnostic_id=a.diagnostic_id","b.hospital_id='".$admin_id."'","","","","");
	//$data_diagno=$get_diagnoInfo;

	//$get_pharmaInfo = mysqlSelect("count(a.pr_id) as pid","pharma_referrals as a inner join pharma as b on b.pharma_id=a.pharma_id ","b.hospital_id='".$admin_id."'","","","","");
	//$data_pharma=$get_pharmaInfo;
		
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pharmacy and Diagnostic</title>
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
			
			function getDocTiming(val) {
				$.ajax({
					type: "POST",
					url: "get_doc_timing.php",
					data:'day_val='+val,
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
						<h2>Pharmacy and Diagnostic</h2>
						<ol class="breadcrumb">
							<li>
								<a href="Home">Home</a>
							</li>
							<li class="active">
								<strong>Pharmacy and Diagnostic</strong>
							</li>
						</ol>
					</div>
					<div class="col-lg-2">
					</div>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
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
									$getPhaCustomer = mysqlSelect("a.pr_id as PRID,b.pharma_customer_name as customer_Name,b.pharma_customer_phone as PhoneNo,b.pharma_customer_email as EmailID,a.referred_date as Reffered_Date, a.order_status as Order_Status,c.pharma_name as Pharma_name","pharma_referrals as a inner join pharma_customer as b on b.pharma_customer_id=a.pharma_customer_id inner join pharma as c on c.pharma_id=a.pharma_id","b.pharma_id='".$list['pharma_id']."'","a.pr_id desc","","","");
									foreach($getPhaCustomer as $list3){
								?>
								
                            <a>
								<tr>
								   <td><a><?php echo $list3['customer_Name'];  ?></a></td>
								   <td><a><?php echo date('d/m/y', strtotime($list4['Reffered_Date']));  ?><br> <?php echo date('H:m', strtotime($list4['Reffered_Date']));  ?></a></td>
								    <!--<td><a><?php echo $list3['Reffered_Date'];  ?></a></td>
								   <td><a><?php echo $list3['Pharma_name'];  ?></a></td>-->
								   <td><a><?php echo $list3['PhoneNo'];?> | <a><?php echo $list3['EmailID'];  ?> </a></td>
								   	<?php if  ($list3['Order_Status']==1){
										$Pharma_status="Reffered";
										}
										else if ($list3['Order_Status']==2) {
											$Pharma_status="Payment link sent";
										}
										else if ($list3['Order_Status']==3) {
											$Pharma_status="Paid";
										}
										else { $Pharma_status="Completed";
										}
									?>
								   <td><a><?php echo $Pharma_status ;  ?></a></td>
								  
								  
								</tr>
							</a>
								<?php }} ?>
                            </tbody>
                        </table>
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
										$getDiagnosCustomer = mysqlSelect("a.dr_id as DRID,b.diagnostic_customer_name as customer_Name,b.diagnostic_customer_phone as PhoneNo,b.diagnostic_customer_email as EmailID,a.referred_date as Reffered_Date, a.order_status as Order_Status,c.diagnosis_name as Diagno_name","diagnostic_referrals as a inner join diagnostic_customer as b on b.diagnostic_customer_id=a.diagnostic_customer_id inner join Diagnostic_center as c on c.diagnostic_id=b.diagnostic_id","b.diagnostic_id='".$list1['diagnostic_id']."'","a.dr_id desc","","","");
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
										}
										else if ($list4['Order_Status']==2) {
											$diagno_status="Payment link sent";
										}
										else if ($list4['Order_Status']==3) {
											$diagno_status="Paid";
										}
										else { $diagno_status="Completed";
										}
									?>
								    <td><a><?php echo $diagno_status;  ?></a></td>
								</tr>
							</a>
								<?php } }?>
                            </tbody>
                        </table>
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