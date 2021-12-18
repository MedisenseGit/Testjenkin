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
//$objQuery = new CLSQueryMaker();
mysqlDelete('out_patient_billing',"doc_id='".$admin_id."' and active_status='1'");	
$get_PatientBill = mysqlSelect("*","out_patient_billing_template","doc_id='".$admin_id."'","opb_temp_id desc","","","");	

mysqlDelete('',"doc_id='".$admin_id."'");
if(isset($_POST['cmdchangeStatus']))
	{
		$params     = split("-", $_POST['slct_val']);
		if(is_numeric($params[0]) == false)
		{
		$patient_name=$_POST['slct_val'];
		
		}
		else
		{
			//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob,patient_gen,patient_loc,pat_state,pat_country,patient_addrs","doc_my_patient","patient_id='".$params[0]."'","","","","");
			
			$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$params[0]."'","","","","");

			$patient_name = $get_PatientDetails[0]['patient_name'];
			$patient_id=$get_PatientDetails[0]['patient_id'];
			$patient_mob=$get_PatientDetails[0]['patient_mob'];
			$patient_addrs=$get_PatientDetails[0]['patient_addrs'];
		}	
	}	

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Out Patient Billing</title>
	<?php include_once('support.php'); ?>
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113157294-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113157294-1');
</script>
<style>

/* Let's make sure all tables have defaults */
table td {
    vertical-align: top;
}

/* -------------------------------------
    BODY & CONTAINER
------------------------------------- */

.body-wrap {
    width: 100%;
}

.container {
    display: block !important;
    max-width: 100% !important;
    /* makes it centered */
    clear: both !important;
}

.content {
    max-width: 100%;
   
    display: block;
    padding: 10px;
}

/* -------------------------------------
    HEADER, FOOTER, MAIN
------------------------------------- */
.main {
    background: #fff;
    border: 1px solid #e9e9e9;
    border-radius: 3px;
}

.content-wrap {
    padding: 10px;
}

.content-block {
    padding: 0 0 10px;
}

.header {
    width: 100%;
    margin-bottom: 20px;
}

/* -------------------------------------
    OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
.last {
    margin-bottom: 0;
}

.first {
    margin-top: 0;
}

.aligncenter {
    text-align: center;
}

.alignright {
    text-align: right;
}

.alignleft {
    text-align: left;
}

.clear {
    clear: both;
}

/* -------------------------------------
    ALERTS
    Change the class depending on warning email, good email or bad email
------------------------------------- */
.alert {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    padding: 20px;
    text-align: center;
    border-radius: 3px 3px 0 0;
}
.alert a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    font-size: 16px;
}
.alert.alert-warning {
    background: #f8ac59;
}
.alert.alert-bad {
    background: #ed5565;
}
.alert.alert-good {
    background: #1ab394;
}

/* -------------------------------------
    INVOICE
    Styles for the billing table
------------------------------------- */
.invoice {
    margin: 10px auto;
    text-align: left;
    width: 100%;
}
.invoice td {
    padding: 5px 0;
}
.invoice .invoice-items {
    width: 100%;
}
.invoice .invoice-items td {
    border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
    font-weight: 700;
}

/* -------------------------------------
    RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
@media only screen and (max-width: 640px) {
    h1, h2, h3, h4 {
        font-weight: 600 !important;
        margin: 20px 0 5px !important;
    }

    h1 {
        font-size: 22px !important;
    }

    h2 {
        font-size: 18px !important;
    }

    h3 {
        font-size: 16px !important;
    }

    .container {
        width: 100% !important;
    }

    .content, .content-wrap {
        padding: 10px !important;
    }

    .invoice {
        width: 100% !important;
    }
}
</style>
<script src="js/status_validationJs.js" ></script>
</head>

<body>

    <div id="wrapper">
	<?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php echo $_SESSION['login_hosp_name']; ?> Out Patient Billing</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Out Patient Billing</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

       		<br>
			
			<div class="row wrapper border-bottom white-bg page-heading">
			<div class="ibox-content m-b-sm border-bottom">
			<div class="row">
			<div class="col-lg-12">
								<a class="btn btn-danger" data-toggle="collapse" data-target="#demo">View Billing History</a>
								</div>
								
							<div class="col-lg-12 m-t collapse" id="demo" style="background-color:#ebe1fc; border:1px dashed #cecdcb; padding-top:20px; margin-bottom:20px;">
								
								<div class="input-group">
										
								<table class="table table-bordered" style="table-layout:fixed">
								
								<tbody>
								<tr>
								<?php foreach($get_PatientBill as $get_PatientBillList){?>
								<td><b><a href="print-op-invoice/?id=<?php echo md5($get_PatientBillList['opb_temp_id']); ?>" target="_blank"><?php echo $get_PatientBillList['template_name'];?></a></b></td>
								<?php } ?>
								</tr>
								</tbody>
								</table>
								</div>
								</div>	
								
			</div><br>
			<form method="post" name="frmchangeStatus" >
													<input type="hidden" name="cmdchangeStatus" value=""/>
													<input type="hidden" name="slct_val" value="" />
													</form>
												<div class="input-group">
												
												   <input type="text" required onchange="return getPatient(this.value);" placeholder="Search / Add Patient" name="search" value="" data-doc-id="<?php echo $admin_id; ?>" class="form-control input-lg typeahead_1">
													<div class="input-group-btn">
														<button class="btn btn-lg btn-primary" name="cmdSearch" type="submit" name="submit">
															Search
														</button>
													</div>
												</div>
												<hr>
												<div class="row">
												<form method="post" name="payment_form" id="payment_form" autocomplete="off">
													
														<div class="col-sm-5">
															<div class="form-group">
															<label class="control-label" for="price">Service </label>
															<input type="text" id="txtNarration" name="txtNarration" value="" required onchange="return getBilling(this.value);" placeholder="Narration"  class="form-control input-lg typeahead_2">
															</div>
															</div>
															<div class="col-sm-1">
															<div class="form-group">
															<label class="control-label" for="price">Qty </label>
															<select name="qty" id="qty" class="form-control">
																	<?php 
																	for($i=1;$i<=50;$i++){ ?>
																	<option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
																	<?php } ?>
																</select></div>
															</div>
															<div class="col-sm-2">
															<div class="form-group">
															<label class="control-label" for="price">Amount(Rs.) </label>
															<input type="text" id="txtAmount" name="txtAmount" value="" required placeholder="Amount" class="form-control">
															</div>
															</div>
														
															<div class="col-sm-2">
															<div class="form-group">
																<label class="control-label" for="price">Discount(%) <span class="required">*</span></label>
																<select name="discount" id="discount" class="form-control">
																	<option value="" selected>0%</option>
																	<option value="1" >1%</option>
																	<option value="2" >2%</option>
																	<option value="3" >3%</option>
																	<option value="4" >4%</option>
																	<option value="5" >5%</option>
																	<option value="10" >10%</option>
																	<option value="15" >15%</option>
																	<option value="20" >20%</option>
																	<option value="25" >25%</option>
																	<option value="30" >30%</option>
																	<option value="35" >35%</option>
																	<option value="40" >40%</option>
																	<option value="45" >45%</option>
																	<option value="50" >50%</option>
																	<option value="60" >60%</option>
																	<option value="70" >70%</option>
																	<option value="80" >80%</option>
																	<option value="90" >90%</option>
																	<option value="100" >100%</option>
																</select>
															</div>
															</div>
														
															<div class="col-sm-2 m-t">
																<div class="form-group">
																 <label class="control-label" for="status" ></label>
																	 <button type="submit" name="submit" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
																</div>
															</div>
												</form>
												</div>
		<hr>
		<div class="row">
            <table class="body-wrap">
    <tr>
        <td></td>
        <td class="container">
           <div id="success_message"></div>
                <table class="main" width="90%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="content-wrap">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                
                                <tr>
                                    <td class="content-block">
									
                                        <table class="invoice">
                                           <tr>
                                                <td>
												
												</td>
											</tr>
                                            <tr>
                                                <td>
												<div id="display_op_paylist"></div>                                              
														
                                                    
                                                </td>
                                            </tr>
											
											<tr>
                                                <td>
												<hr>
												<form method="post" action="add_details.php">
												<input type="hidden" name="patiet_id" value="<?php echo $patient_id; ?>" />
												<div id="paymentAdd">
												
												
												<div class="row" id="paymentMode1">
												<h3 class="m-l">Payment</h3>
															<div class="col-lg-3">
															<div class="form-group">
																<label class="control-label" for="status">Payment mode <span class="required">*</span></label>
																<select name="pay_mode1" id="pay_mode1" required class="form-control">
																	<option value="" selected>Select</option>
																	<option value="Cash" >Cash</option>
																	<option value="Credit/Debit Card" >Credit/Debit Card</option>
																	<option value="Net Banking" >Net Banking</option>
																	<option value="Net Banking" >Check / DD</option>
																	<option value="Net Banking" >UPI</option>
																</select>
															</div>
															
															</div>
															<div class="col-lg-3">
															<div class="form-group">
															<label class="control-label" for="price">Narration </label>
															<input type="text" name="payNarration1" value="" required placeholder=""  class="form-control">
															</div>
															</div>
															<div class="col-lg-2">
															<div class="form-group">
															<label class="control-label" for="price">Amount(Rs.) </label>
															<input type="text" name="payAmount1" id="payAmount1" value=""  placeholder=""  class="form-control">
															</div>
															</div>
															
															<div class="col-lg-2 m-t">
																<div class="form-group">
																 <label class="control-label" for="payment" ></label>
																	 <button  id="addPayment" name="addPayment" class="btn btn-outline btn-primary"><i class="fa fa-plus"></i> Add </button>
																</div>
															</div>
												</div>
												</div>
												<hr>
												<div class="row">
												<h3 class="m-l">Patient Information</h3>
															<div class="col-lg-3">
															<div class="form-group">
															<label class="control-label" for="price">Patient Name </label>
															<input type="text" name="patName" value="<?php echo $patient_name; ?>" required placeholder="Patient Name"  class="form-control">
															</div>
															
															</div>
															<div class="col-lg-3">
															<div class="form-group">
															<label class="control-label" for="price">Mobile No. </label>
															<input type="text" name="patMobile" value="<?php echo $patient_mob; ?>" required placeholder="10 digit mobile no." class="form-control">
															</div>
															</div>
															<div class="col-lg-4">
															<div class="form-group">
															<label class="control-label" for="price">Address </label>
															<input type="text" name="patAddress" value="<?php echo $patient_addrs; ?>"  placeholder="Address"  class="form-control">
															</div>
															</div>
													</div>
													<div class="row">
													<div class="col-lg-3 pull-right">
															
															<button class="btn btn-sm btn-primary pull-right" name="cmdSaveOpBilling" id="cmdSaveOpBilling" type="submit"><strong><i class="fa fa-print"></i> SAVE & PRINT </strong></button>
															
															</div>
													</div>		
												</form>
												</td>
											</tr>
                                        </table>
										
                                    </td>
                                </tr>
								
                               
                            </table>
                        </td>
                    </tr>
					
                </table>
               
        </td>
        <td></td>
    </tr>
	
</table>
</div>
</div>
</div>
</div>
      
 </div>      

<!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
	  <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	
    <script>
	function getBilling(selctval){
		var billing_id=selctval.split("-");
		 $("#txtAmount").focus();
		 if(!isNaN(billing_id[0])){
		$.ajax({
			type: "POST",
			url: "get_billing_details.php",
			data:{"billing_id":billing_id[0]},
			success: function(data){
				$("#txtAmount").val(data);
				$("#txtNarration").val(billing_id[1]);
				
			}
		});
		 }
	}
	
	
        $(document).ready(function(){
	<?php 
		//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
		$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
		$get_BillingDetails = mysqlSelect("billing_id,patient_id,doc_id,narration,amount","out_patient_billing","doc_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });
			
            
			$('.typeahead_2').typeahead({
                source: [<?php foreach($get_BillingDetails as $listBill){ echo "'".$listBill['billing_id']."-".$listBill['narration']."',"; }?>]
            });
            
        var i=1;
			
			$("#addPayment").click(function(){
				var totAmount=$(".invoice-items #totalAmount").text();
				var splitAmount=totAmount.split(" ");
				var pay1=0;
				var pay2=0;
				var pay3=0;
				if($("#payAmount1").val()!="" && typeof($("#payAmount1").val())!="undefined"){
				pay1=$("#payAmount1").val();
				}
				
				if($("#payAmount2").val()!="" && typeof($("#payAmount2").val())!="undefined"){
				pay2=$("#payAmount2").val();
				}
				
				if($("#payAmount3").val()!="" && typeof($("#payAmount3").val())!="undefined"){
				pay3=$("#payAmount3").val();
				}
				
				var remAmount=Number(splitAmount[1])-Number(pay1)-Number(pay2)-Number(pay3);
				
				i++;
				if(i<=3){
				var newpayment='<div class="row" id="paymentMode'+i+'">';
						newpayment+='<div class="col-lg-3">';
						newpayment+='<div class="form-group">';
						newpayment+='<label class="control-label" for="status'+i+'">Payment mode <span class="required">*</span></label>';
						newpayment+='<select name="pay_mode'+i+'" id="pay_mode'+i+'" required class="form-control">';
						newpayment+='<option value="" selected>Select</option>';
						newpayment+='<option value="Cash" >Cash</option>';
						newpayment+='<option value="Credit/Debit Card" >Credit/Debit Card</option>';
						newpayment+='<option value="Net Banking" >Net Banking</option>';
						newpayment+='<option value="Net Banking" >Check / DD</option>';
						newpayment+='<option value="Net Banking" >UPI</option>';
						newpayment+='</select></div></div>';
						newpayment+='<div class="col-lg-3"><div class="form-group">';
						newpayment+='<label class="control-label" for="price'+i+'">Narration </label>';
						newpayment+='<input type="text" name="payNarration'+i+'" value="" required placeholder=""  class="form-control"></div></div>';
						newpayment+='<div class="col-lg-2"><div class="form-group">';
						newpayment+='<label class="control-label" for="price'+i+'">Amount(Rs.) </label>';
						newpayment+='<input type="text" name="payAmount'+i+'" id="payAmount'+i+'" value="'+remAmount+'"  placeholder=""  class="form-control"></div></div>';
						newpayment+='<div class="col-lg-1 m-t"><div class="form-group">';
						newpayment+='<a href="#" id="delPay" data-row-id='+i+' ><img src="delete_icon.png" width="15" /></a></div></div></div>';
						
			$( "#paymentAdd" ).append( newpayment );
			if(i==3){
				$("#addPayment").hide();
			}
				}
			});
			
			$("body").on("click", "#delPay", function(event){
			  event.preventDefault();
		  var rowid = $(this).attr("data-row-id");
		  i--;
		  $('#paymentMode'+rowid+'').remove();
		  $("#addPayment").show();
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
	 <!-- Data picker -->
   <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>

	  <!-- Date range use moment.js same as full calendar plugin -->
    <script src="../assets/js/plugins/fullcalendar/moment.min.js"></script>
	<!-- Date range picker -->
    <script src="../assets/js/plugins/daterangepicker/daterangepicker.js"></script>

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
			
		$('#payment_form').on('submit', function(event){
		  event.preventDefault();
		  var form_data = $(this).serialize();
		  $.ajax({
		   url:"add_op_payment.php",
		   method:"POST",
		   data:form_data,
		   dataType:"JSON",
		   success:function(data)
		   {
			if(data.error != '')
			{
			 $('#payment_form')[0].reset();
			 $('#success_message').html(data.error);
			 
			 load_pay_list();
			}
		   }
		  })
		 });
		 
		 
		 
		 $("body").on("click", "#delRow", function(event){
		 //$('#delRow').on('click', function(event){
		  event.preventDefault();
		  var rowid = $(this).attr("data-row-id");
		  console.log(rowid);
		  $.ajax({
		   url:"delete_rowBilling.php",
		   method:"POST",
		   data:'rowid='+rowid,
		   dataType:"JSON",
		   success:function(data)
		   {
			if(data.error != '')
			{
			 $('#success_message').html(data.error);
			 
			 load_pay_list();
			}
		   }
		  })
		 });

		 load_pay_list();

		 function load_pay_list()
		 {
			console.log(<?php echo $_GET['id']; ?>);
		  $.ajax({
		   url:"123fetch_op_payment_edit.php",
		   method:"POST",
		   data:'temp_id=<?php echo $_GET['id']; ?>',
		   
		   success:function(data)
		   {
			$('#display_op_paylist').html(data);
			
			var totalAm = $(".invoice-items #totalAmount").text();
			var totAmount=totalAm.split(" ");
			$('#payAmount1').val(totAmount[1]);
		   }
		  })
		 }

        });
		</script>
		
</body>

</html>
		