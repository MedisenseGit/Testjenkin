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
			
			
			
			
$allRecord = mysqlSelect("*","payment_transaction","user_id='".$admin_id."' and user_type=1","pay_trans_id desc","","","$eu, $limit");
$pag_result = mysqlSelect("pay_trans_id","payment_transaction","user_id='".$admin_id."' and user_type=1","");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
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
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">

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
			<form method="post" name="frmAddPayments" action="add_details.php">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="product_name">Search Patient Name</label>
                            <select data-placeholder="Search Patient Name..." class="chosen-select" name="txtCountry"  tabindex="2" onchange="return getPatient(this.value);">
											<option value="" selected></option>
											<option value="other">Other</option>
												
												<?php 
												$CntName = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_email as patient_email,a.patient_mob as patient_mob", "doc_my_patient as a left join pharma_referrals as b on a.patient_id=b.patient_id", "b.pharma_id='".$admin_id."'", "a.patient_name asc", "", "", "");
														
														
														foreach ($CntName as $CntNameList) {
														?> 
																								
															<option value="<?php echo stripslashes($CntNameList['patient_id']); ?>" />
														<?php
															echo stripslashes($CntNameList['patient_name'])."(".$CntNameList['patient_email']."-".$CntNameList['patient_mob'].")";
														?></option>
																										
														<?php
															
														}
														?>
										</select>
                        </div>
                    </div>
                    
					<!--<div class="col-sm-2">
                        <div class="form-group">
						 <label class="control-label" for="status" style="margin-top:35px;"></label>
                             <button type="submit" name="add_button" id="add_button" class="btn btn-outline btn-primary"><i class="fa fa-credit-card"></i> Add Payment</button>
						</div>
                    </div>-->
                </div>
				
				
			</form>

            </div>

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
                                        <th>Amount (Rs.)</th>  
										<th>Payment mode</th>
                                        <th>Status</th>
										<th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php foreach($allRecord as $list)
									{ 									
									?>
                                    <tr>
                                       
                                        <td><?php echo $list['patient_name']; ?> </td>
										<td><?php echo date('d M Y H:i',strtotime($list['trans_date'])); ?></td>
                                        <td><?php echo $list['amount']; ?></td> 
										<td><?php echo $list['pay_method']; ?></td>										
                                        <td><span class="fa fa-check text-navy"><?php echo $list['payment_status']; ?></span></td>
										<td><a href="javascript:void(0)" onclick="return delPayment(<?php echo $list['pay_trans_id']; ?>);" class="btn btn-danger btn-bitbucket btn-xs">
                      <i class="fa fa-trash-o"></i> DELETE</a></td>
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
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>
 <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>


</body>

</html>
