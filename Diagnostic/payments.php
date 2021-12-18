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
//
			 if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
		$no_of_records_per_page = 30;
        $offset = ($pageno-1) * $no_of_records_per_page;
			
$TotalRecord =mysqlSelect("pay_trans_id","payment_diag_pha_transaction","diagno_pharma_id='".$admin_id."'","pay_trans_id desc","pay_trans_id","","");	
$total_rows = count($TotalRecord);	
$total_pages = ceil($total_rows / $no_of_records_per_page);		
$allRecord =mysqlSelect("*","payment_diag_pha_transaction","diagno_pharma_id='".$admin_id."'","pay_trans_id desc","","","$offset, $no_of_records_per_page");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payments</title>
	<?php include_once('support.php'); ?>
    <script language="JavaScript" src="js/status_validationJs.js"></script>
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
                    <h2>Payments</h2>
                    <ol class="breadcrumb">
                        <!--<li>
                            <a href="Home">Home</a>
                        </li>-->
                        
                        <li class="active">
                            <strong>Payments</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>

        <div class="wrapper wrapper-content animated fadeInRight ecommerce">


           <!-- <div class="ibox-content m-b-sm border-bottom">
			<form method="post" name="frmAddPayments" autocomplete="off" action="add_details.php">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Patient Name</label>
                            <input type="text" id="patient_name" name="patient_name" value="" placeholder="Patient Name" required class="form-control typeahead_1">
                        </div>
                    </div>
					<div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label" for="price">Narration</label>
                            <input type="text" id="txtNarration" name="txtNarration" value="" placeholder="Narration" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="price">Amount(Rs.)</label>
                            <input type="text" id="price" name="price" value="" required placeholder="Price" class="form-control">
                        </div>
                    </div>
                    
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label class="control-label" for="status">Payment mode</label>
                            <select name="pay_mode" id="pay_mode" required class="form-control">
								<option value="" selected>Select</option>
                                <option value="Cash" >Cash</option>
                                <option value="Credit/Debit Card" >Credit/Debit Card</option>
								<option value="Net Banking" >Net Banking</option>
                            </select>
                        </div>
                    </div>
					<div class="col-sm-1">
                        <div class="form-group">
						 <label class="control-label" for="status" style="margin-top:35px;"></label>
                             <button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary"><i class="fa fa-credit-card"></i> Add </button>
						</div>
                    </div>
                </div>
			</form>

            </div>-->

            <div class="row" id="allPay">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content" >

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>

                                        
                                        <th>Patient </th>
										<th>Transaction Date</th>
										<!--<th>Narration</th>-->
                                        <th>Amount</th>  
										<th>Payment mode</th>
                                        <th>Status</th>
										<!--<th>Delete</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><?php echo $list['patient_name']; ?> </td>
										<td><?php echo date('d M Y H:i',strtotime($list['trans_date'])); ?></td>
										<!--<td><?php echo $list['narration']; ?></td>-->
                                        <td><?php echo $list['amount']; ?></td> 
										<td><?php echo $list['pay_method']; ?></td>										
                                        <td>
										<?php if($list['payment_status']=="PENDING"){ ?>
										<span class="fa text-warning"><?php echo $list['payment_status']; ?></span>
										<?php } else { ?>
										<span class="fa fa-check text-navy"><?php echo $list['payment_status']; ?></span>
										<?php } ?>
										
										</td>
										<!--<td><a href="javascript:void(0)" onclick="return delPayment(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-trash-o"></i> DELETE</a></td>-->
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
								<ul class="pagination">
        <li><a href="?pageno=1">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
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
	$get_PatientDetails =mysqlSelect("patient_id,patient_name,patient_mob","doc_my_patient","doc_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });

            

        });
    </script>

</body>

</html>
