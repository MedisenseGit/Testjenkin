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

//Get the page name 
//echo $_SERVER['REQUEST_URI'];

$request_uri = str_replace("/premium/","",$_SERVER['REQUEST_URI']);
$param 	= explode("/",$request_uri);

$page_name = $param[0];

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
	if(isset($_POST['cmdSearch']))
	{
		unset($_SESSION['fromDate']);
		unset($_SESSION['toDate']);
		$allRecord = mysqlSelect("*","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and trans_date BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))."' and '".date('Y-m-d',strtotime($_POST['toDate']))."'","pay_trans_id desc","","","");
		$pag_result = mysqlSelect("pay_trans_id","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and trans_date BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))."' and '".date('Y-m-d',strtotime($_POST['toDate']))."'","");
		$total_amount = mysqlSelect("SUM(amount) as Tot_amount","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and payment_status='PAID' and trans_date BETWEEN '".date('Y-m-d',strtotime($_POST['fromDate']))."' and '".date('Y-m-d',strtotime($_POST['toDate']))."'","");
		
		$_SESSION['fromDate'] = $_POST['fromDate'];
		$_SESSION['toDate'] = $_POST['toDate'];
		
		$fromDate = $_POST['fromDate'];
		$toDate = $_POST['toDate'];

	}
	else if(isset($_POST['cmdSearchToday']))
	{
		unset($_SESSION['today']);
		$allRecord = mysqlSelect("*","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and DATE_FORMAT(trans_date,'%Y-%m-%d') ='".date('Y-m-d')."'","pay_trans_id desc","","","");
		$pag_result = mysqlSelect("pay_trans_id","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and DATE_FORMAT(trans_date,'%Y-%m-%d') ='".date('Y-m-d')."'","");
		$total_amount = mysqlSelect("SUM(amount) as Tot_amount","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and payment_status='PAID' and DATE_FORMAT(trans_date,'%Y-%m-%d') ='".date('Y-m-d')."'","");
		
		$_SESSION['today'] = $_POST['cmdSearchToday'];
		$fromDate = "";
		$toDate = "";
	}
	else{		
			
	$allRecord = mysqlSelect("*","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."'","pay_trans_id desc","","","");
	$total_amount = mysqlSelect("SUM(amount) as Tot_amount","payment_transaction","user_id='".$admin_id."' and user_type=1 and hosp_id='".$_SESSION['login_hosp_id']."' and payment_status='PAID'","");
		$fromDate = "";
		$toDate = "";
	//$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
	}
$arrPage = explode("-",$pageing);
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payments</title>
	<?php include_once('support.php'); ?>
    <script language="JavaScript" src="js/status_validationJs.js"></script>
	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113157294-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113157294-1');
</script>
</head>

<body>

    <div id="wrapper">
	<?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2><?php echo $_SESSION['login_hosp_name']; ?> Payments</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Payments</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


            <div class="ibox-content m-b-sm border-bottom">
			
			<div class="tabs-container">
			<ul class="nav nav-tabs">
                            <li <?php if($page_name=="Payments") { echo "class=active"; } ?>><a href="Payments"><i class="fa fa-money"></i>Single Item Billing</a></li>
                            <li <?php if($page_name=="Out-Patient-Billing") { echo "class=active"; } ?>><a href="Out-Patient-Billing"><i class="fa fa-money"></i>Detailed Billing</a></li>
						
                        </ul>
			</div><br><br>
			
			<form method="post" name="frmAddPayments" autocomplete="off" action="add_details.php">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Patient Name <span class="required">*</span></label>
                            <input type="text" id="patient_name" name="patient_name" value="" placeholder="Patient Name" required class="form-control typeahead_1">
                        </div>
                    </div>
					<div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="price">Narration </label>
                            <input type="text" id="txtNarration" name="txtNarration" value="" placeholder="Narration" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">Amount(Rs.) <span class="required">*</span></label>
                            <input type="text" id="price" name="price" value="" required placeholder="Price" class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="status">Payment mode <span class="required">*</span></label>
                            <select name="pay_mode" id="pay_mode" required class="form-control">
                                <option value="" selected>Select</option>
                                <option value="Cash" >Cash</option>
                                <option value="Credit/Debit Card" >Credit/Debit Card</option>
								<option value="Net Banking" >Net Banking</option>
                                
                            </select>
                        </div>
                    </div>
					
                </div>
				<div class="row">
				<div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="price">Mobile No.</label>
                            <input type="text" id="txtMobile" name="txtMobile" value="" placeholder="10 Digit mobile no." maxlength="10" minlength="10" class="form-control">
                        </div>
                    </div>
				<div class="col-sm-2 pull-right">
                        <div class="form-group">
						 <label class="control-label" for="status" ></label>
                             <button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary"><i class="fa fa-credit-card"></i> Add </button>
						</div>
                    </div>
					<div class="col-sm-3 pull-right">
						<div class="form-group">
									<dl>
									 <dt><label> <input type="checkbox" class="i-checks" name="chkReceipt"  value="1"> Would you like to send payment receipt to patient via SMS?</label></dt><br> <br>
									</dl>
						</div>
					</div>
					
				</div>
			</form>

            </div>

            <div class="row" id="allPay">
                <div class="col-lg-12">
                    <div class="ibox">
					<div class="ibox-content">
					
						<div class="form-group" id="data_5">
                            <form method="post" name="changeSearch" >    
                                <div class="input-daterange input-group col-lg-4 pull-left " id="datepicker">
                                    <input type="text" class=" input-sm form-control" name="fromDate" id="fromDate" placeholder="select" value="<?php echo $fromDate; ?>"/>
                                    <span class="input-group-addon">to</span>
                                    <input type="text" class="col-lg-4 input-sm form-control" name="toDate" placeholder="select" id="toDate" value="<?php echo $toDate; ?>" />
								</div>
								<div class="col-lg-4">
								<button type="submit" name="cmdSearch" class="btn btn-primary btn-sm searchByDate">Search</button>
								<button type="submit" name="cmdSearchToday" class="btn btn-primary btn-sm searchByDate">Today</button>
								<button type="submit" id="allSearch" class="btn btn-default btn-sm">All</button>
                            
							</div><br><br>
							</form>	
							<h3 class="pull-left text-navy">Total Amount:<?php if(!empty($total_amount)){ echo "Rs. ".$total_amount[0]['Tot_amount']."/-";} ?></h3>
							<form  action="export_data.php" method="post" name="upload_excel" enctype="multipart/form-data">
							<input type="hidden" name="frmDate" value="<?php echo $_POST['fromDate']; ?>" />
							<input type="hidden" name="toDate" value="<?php echo $_POST['toDate']; ?>" />
							<input type="hidden" name="today" value="<?php echo $_POST['cmdSearchToday']; ?>" />
							<input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>" />
							<input type="hidden" name="login_hosp_id" value="<?php echo $_SESSION['login_hosp_id']; ?>" />
							
							<input type="hidden" name="docPrefix" value="<?php echo $checkSetting[0]['patient_id_prefix']; ?>" />
												<button type="submit" name="Export_Payment" class="btn btn-success pull-right" ><i class="fa fa-download" aria-hidden="true"></i> EXPORT</button>
							</form>
						</div>
						
						</div>
                        <div class="ibox-content" >
                            <form method="post" name="frmchangeStatus" action="Out-Patient-Billing">
								<input type="hidden" name="cmdchangeStatus" value=""/>
								<input type="hidden" name="slct_val" value="" />
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Patient </th>
										<th>Transaction Date</th>
										<th>Narration</th>
                                        <th>Amount (Rs.)</th>  
										<th>Payment mode</th>
                                        <th>Status</th>
										 <?php if($secretary_id!=1) { ?><th>Delete</th><?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                       <td><a href="javascript:void(0);" onclick="return getBilling('<?php echo $list['patient_id'];  ?>')"><?php echo $list['patient_name']; ?> </a></td>
										<td><?php echo date('d M Y H:i',strtotime($list['trans_date'])); ?></td>
										<td><?php echo $list['narration']; ?></td>
                                        <td><?php echo $list['amount']; ?></td> 
										<td><?php echo $list['pay_method']; ?></td>										
                                        <td>
										<?php if($list['payment_status']=="PENDING"){ ?>
										<a href="javascript:void(0)" onclick="return changePayStatus(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-check"></i> TURN PAID</a></span>
										<?php } else { ?>
										<span class="fa fa-check text-navy"><?php echo $list['payment_status']; ?></span>
										<?php } ?>
										
										</td>
										 <?php if($secretary_id!=1) { ?><td><a href="javascript:void(0)" onclick="return delPayment(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
										 <i class="fa fa-trash-o"></i> DELETE</a></td><?php } ?>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
							
                    </div>
					
                </div>
            </div>
			<div id="afterDelTrans"></div>

        </div>
        <?php include_once('footer.php'); ?>

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
	function getBilling(patId){
		
		document.frmchangeStatus.cmdchangeStatus.value="submit";
		document.frmchangeStatus.slct_val.value=patId;
		document.frmchangeStatus.submit();
	}	
	
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
        $(document).ready(function(){
		<?php 
	//$get_PatientDetails = mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
	$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
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


        });

    </script>
</body>

</html>
