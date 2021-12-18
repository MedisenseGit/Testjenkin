<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(empty($_GET['d'])){
	echo "<h2>Error!!!!!!</h2>";
}
//$checkPatient= mysqlSelect("*","doc_my_patient","md5(patient_id)='".$_GET['p']."'","","","","");

$checkPatient= mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email"," patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$_GET['p']."'","","","","");


$getDocInfo= mysqlSelect("*","referal","md5(ref_id)='".$_GET['d']."'","","","","");
$get_docSpec = mysqlSelect("*","doc_specialization as a left join specialization as b on a.spec_id=b.spec_id","md5(a.doc_id)='".$_GET['d']."'","","","","");	
$getDocHospital = mysqlSelect("*","doctor_hosp as a inner join hosp_tab as b on b.hosp_id = a.hosp_id ","md5(a.doc_id) = '".$_GET['d']."'","","","","");
$checkSetting= mysqlSelect("*","doctor_settings","md5(doc_id)='".$_GET['p']."' and doc_type='1'","","","","");	
$appointTransaction= mysqlSelect("*","appointment_transaction_detail","md5(patient_id)='".$_GET['p']."' and appoint_trans_id='".$_GET['t']."'","Visiting_date desc","","","");
		
if($checkPatient[0]['patient_gen']=="1"){
		$gender="Male";
	}
	else if($checkPatient[0]['patient_gen']=="2"){
		$gender="Female";
	}
	else if($checkPatient[0]['patient_gen']=="3"){
		$gender="Other";
	}

	if($checkPatient[0]['hyper_cond']=="2"){
		$hyperStatus="No";
	}
	else if($checkPatient[0]['hyper_cond']=="1"){
		$hyperStatus="Yes";
	}
	if($checkPatient[0]['diabetes_cond']=="2"){
		$diabetesStatus="No";
	}
	else if($checkPatient[0]['diabetes_cond']=="1"){
		$diabetesStatus="Yes";
	}

$gatewayUrl = "../PaytmKit/pgRedirect.php"; 

 if(!empty($getDocInfo[0]['cons_charge'])) {
		$consultation_fees = $getDocInfo[0]['cons_charge'];
		$gst=18; //18% GST
		$total_tax_amount=($consultation_fees*$gst)/100;	
		$tot_amount=$consultation_fees+$total_tax_amount;	
		$Total_Cost_curency =   "Rs. ".$tot_amount." (GST included)";
	}
	else {
		$consultation_fees = 0;
		$tot_amount=$consultation_fees;
		$Total_Cost_curency =   "Rs. ".$consultation_fees;
	}
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
$TransId=time();	
                $arrFields_arr1 = array();
				$arrValues_arr1 = array();	
				$arrFields_arr1[] = 'patient_name';
				$arrValues_arr1[] = $checkPatient[0]['patient_name']; //booking id from booking table.
				
				$arrFields_arr1[] = 'patient_id';
				$arrValues_arr1[] = $checkPatient[0]['patient_id'];
				$arrFields_arr1[] = 'trans_date';
				$arrValues_arr1[] = $curDate;
				$arrFields_arr1[] = 'amount';
				$arrValues_arr1[] = $tot_amount;
				$arrFields_arr1[] = 'user_id';
				$arrValues_arr1[] = $getDocInfo[0]['ref_id'];
				$arrFields_arr1[] = 'user_type';
				$arrValues_arr1[] = '1';
				$arrFields_arr1[] = 'hosp_id';
				$arrValues_arr1[] = $getDocHospital[0]['hosp_id'];
				
				
				$arrFields_arr1[] = 'payment_status';
				$arrValues_arr1[] = "Pending";
				$arrFields_arr1[] = 'pay_method';
				$arrValues_arr1[] = 'PAYTM';
				/*$arrFields_arr1[] = 'Payment_id';
				$arrValues_arr1[] = "";*/
				$arrFields_arr1[] = 'transaction_id';
				$arrValues_arr1[] = $TransId;
				$arrFields_arr1[] = 'appoint_trans_id';
				$arrValues_arr1[] = $appointTransaction[0]['appoint_trans_id'];
		
		$check_pay_trans = mysqlSelect("*","payment_transaction","appoint_trans_id='".$appointTransaction[0]['appoint_trans_id']."' and patient_id='".$checkPatient[0]['patient_id']."' and user_id='".$getDocInfo[0]['ref_id']."'","","","","");
		if(COUNT($check_pay_trans)>0){	
		   $usercreate1=mysqlUpdate('payment_transaction',$arrFields_arr1,$arrValues_arr1,"appoint_trans_id='".$appointTransaction[0]['appoint_trans_id']."' and patient_id='".$checkPatient[0]['patient_id']."' and user_id='".$getDocInfo[0]['ref_id']."'");
			
		}
else{
	$usercreate1=mysqlInsert('payment_transaction',$arrFields_arr1,$arrValues_arr1);
}		
				
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Premium</title>
<link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

	<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
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
							
							<input type="hidden" id="CUST_ID" name="CUST_ID" value="<?php echo $checkPatient[0]['patient_id']; ?>">
							<input type="hidden" id="ORDER_ID" name="ORDER_ID" value="<?php echo $TransId; ?>">
							<input type="hidden" id="INDUSTRY_TYPE_ID" name="INDUSTRY_TYPE_ID" value="Retail120">
							<input type="hidden" id="CHANNEL_ID" name="CHANNEL_ID" value="WEB">
							<input type="hidden" id="TXN_AMOUNT" name="TXN_AMOUNT" value="<?php echo $tot_amount; ?>">
							<input type="hidden" name="MOBILE_NO" value="<?php echo $checkPatient[0]['patient_mob']; ?>">
							<input type="hidden" name="EMAIL" value="<?php echo $checkPatient[0]['patient_email']; ?>">										
							<input type="hidden" name="CALLBACK_URL" value="<?php echo HOST_MAIN_URL;?>premium/payment_success.php">
							
							
                                <h4><strong>Patient Name: </strong><?php echo $checkPatient[0]['patient_name']; ?></h4>
								<h4><strong>Mobile: </strong> <?php echo $checkPatient[0]['patient_mob']; ?></h4>
								<h4><strong>Email: </strong> <?php echo $checkPatient[0]['patient_email']; ?></h4>
								<h4> <strong>Gender: </strong> <?php echo $gender; ?></h4>
								<h4> <strong>Age: </strong> <?php echo $checkPatient[0]['patient_age']; ?></h4>
								<h4> <strong>Address: </strong> 
                                    <?php echo $checkPatient[0]['patient_addrs'].", ".$checkPatient[0]['patient_loc'].", ".$checkPatient[0]['pat_state']; ?>
								<h3> <strong>Consultation Fees: </strong> <?php echo $Total_Cost_curency; ?></h4>
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
