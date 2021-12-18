<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");

if(empty($_GET['r'])){
	echo "<h2>Error!!!!!!</h2>";
}
$diagno_referal = mysqlSelect("*","pharma_referrals","md5(pr_id)='".$_GET['r']."'","","","","");
//$patient_tab= mysqlSelect("*","doc_my_patient","patient_id='".$diagno_referal[0]['patient_id']."'","","","","");
$patient_tab = mysqlSelect("*","pharma_customer","pharma_customer_id='".$diagno_referal[0]['pharma_customer_id']."'","","","",""); 
$payment_amount = mysqlSelect("*","payment_diagno_pharma","referred_id='".$diagno_referal[0]['pr_id']."'  and type='".$_GET['t']."' and request_from='2'","","","","");
$pat_mob=$patient_tab[0]['pharma_customer_phone'];
$pat_email=$patient_tab[0]['pharma_customer_email'];
$pat_name=$patient_tab[0]['pharma_customer_name'];
$pat_age=$patient_tab[0]['pharma_cust_age'];
$refer_id=$diagno_referal[0]['pr_id'];
$address=$patient_tab[0]['pharma_cust_address'].", ".$patient_tab[0]['pharma_cust_city'].", ".$patient_tab[0]['pharma_cust_state'].", ".$patient_tab[0]['pharma_cust_country'];
if($patient_tab[0]['pharma_cust_gender']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['pharma_cust_gender']=="2"){
		$gender="Female";
	}
	else if($patient_tab[0]['pharma_cust_gender']=="3"){
		$gender="Other";
	}
if($_GET['t'] == '1'){
	
$patient_id=$diagno_referal[0]['patient_id'];

} else if($_GET['t'] == '2'){
	$patient_id=$diagno_referal[0]['login_id'];
	
}

$gatewayUrl = "../PaytmKit/pgRedirect.php"; 


date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$TransId=time();	
                $arrFields_arr1 = array();
				$arrValues_arr1 = array();	
				$arrFields_arr1[] = 'patient_name';
				$arrValues_arr1[] = $pat_name; //booking id from booking table.
				
				$arrFields_arr1[] = 'patient_id';
				$arrValues_arr1[] = $patient_id;
				$arrFields_arr1[] = 'trans_date';
				$arrValues_arr1[] = $curDate;
				$arrFields_arr1[] = 'amount';
				$arrValues_arr1[] = $payment_amount[0]['payment_amount'];
				$arrFields_arr1[] = 'payment_status';
				$arrValues_arr1[] = "PENDING";
				$arrFields_arr1[] = 'pay_method';
				$arrValues_arr1[] = 'PAYTM';
				$arrFields_arr1[] = 'type';
				$arrValues_arr1[] = $_GET['t'];
				$arrFields_arr1[] = 'diagno_pharma_id';
				$arrValues_arr1[] = $payment_amount[0]['diagno_pharma_id'];
				
				$arrFields_arr1[] = 'request_from';
				$arrValues_arr1[] = '2';
				$arrFields_arr1[] = 'referred_id';
				$arrValues_arr1[] = $refer_id;
				$arrFields_arr1[] = 'transaction_id';
				$arrValues_arr1[] = $TransId;
				
		
		$check_pay_trans = mysqlSelect("*","payment_diag_pha_transaction","referred_id='".$refer_id."' and type='".$_GET['t']."' and request_from='2'","","","","");
		if(COUNT($check_pay_trans)>0){	
		   $usercreate1=mysqlUpdate('payment_diag_pha_transaction',$arrFields_arr1,$arrValues_arr1,"referred_id='".$refer_id."' and type='".$_GET['t']."' and request_from='2'");
			
		}
else{
	$usercreate1=mysqlInsert('payment_diag_pha_transaction',$arrFields_arr1,$arrValues_arr1);
}		
				
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pharma Payment</title>
<link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

	<!--<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>-->
	<style>

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f15e;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 10px 16px;
  transition: 0.3s;
  font-size: 15px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-top: none;
}
</style>
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                
                <a href="#" class="navbar-brand"><img alt="image" class="img" src="../assets/img/Practice_premium.png" width="80"/></a>
            </div>
            
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
            <div class="row">
				
			<?php if($_GET['response']=="reports-attached"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Reports uploaded successfully!!! </strong>
			</div>
			<?php } ?>
			
			<div class="col-md-12">
                <div class="ibox-content text-left p-md">
				
				
				<div class="row m-b-lg m-t-lg">
                <div class="col-md-10">

                    <div class="">
					<form method="post" action="<?php echo $gatewayUrl; ?>">
                        <div class="">
                            <div>
							
							<input type="hidden" id="CUST_ID" name="CUST_ID" value="<?php echo $patient_id; ?>">
							<input type="hidden" id="ORDER_ID" name="ORDER_ID" value="<?php echo $TransId; ?>">
							<input type="hidden" id="INDUSTRY_TYPE_ID" name="INDUSTRY_TYPE_ID" value="Retail120">
							<input type="hidden" id="CHANNEL_ID" name="CHANNEL_ID" value="WEB">
							<input type="hidden" id="TXN_AMOUNT" name="TXN_AMOUNT" value="<?php echo $payment_amount[0]['payment_amount']; ?>">
							<input type="hidden" name="MOBILE_NO" value="<?php echo $pat_mob; ?>">
							<input type="hidden" name="EMAIL" value="<?php echo $pat_email; ?>">										
							<input type="hidden" name="CALLBACK_URL" value="<?php echo HOST_MAIN_URL;?>Pharma/payment_success.php">
							
							
                                <h4><strong>Patient Name: </strong><?php echo $pat_name; ?></h4>
								<h4><strong>Mobile: </strong> <?php echo $pat_mob; ?></h4>
								<h4><strong>Email: </strong> <?php echo $pat_email; ?></h4>
								<h4> <strong>Gender: </strong> <?php echo $gender; ?></h4>
								<h4> <strong>Age: </strong> <?php echo $pat_age; ?></h4>
								<h4> <strong>Address: </strong> 
                                    <?php echo $address; ?>
								<h3> <strong>Amount: </strong> <?php echo $payment_amount[0]['currency_code'].'  '; ?><?php echo $payment_amount[0]['payment_amount']; ?></h4>
                              </h4> <br />
                            </div>
							
							<div class="row">
							<div class="col-md-2" style="margin-bottom:20px; float:left;">
                                <input type="submit" class="btn btn-colored btn-rounded btn-theme-colored pl-30 pr-30" value="Pay Now">
							</div>
                        </div>	
						
                        </div>
						</form>
                    </div>
                </div>
				</div>
			 
				</div>
			</div>
		</div>
        </div>

        </div>
        <div class="footer">
            
            <div>
                <strong>Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
            </div>
        </div>

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

    <!-- Flot -->
    <script src="../assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="../assets/js/demo/peity-demo.js"></script>


   

</body>

</html>
