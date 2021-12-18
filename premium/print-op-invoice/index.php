<?php
ob_start();
error_reporting(0); 
session_start();

require_once("../../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:../logout.php");
}
date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d H:i:s');
	
	$hostType="https://";
	$getUrl = $_SERVER['REQUEST_URI'];
	$getHost = $_SERVER['HTTP_HOST'];
	$url = explode("print-emr/?", $getUrl)[0];
	$completeURL = $hostType.$getHost.$url;
	
	
	$getPayList = mysqlSelect("*","out_patient_billing","md5(opb_temp_id)='".$_GET['id']."'","","","","");
	$getTotAmount= mysqlSelect("SUM(total_amount) as tot_amount","out_patient_billing","md5(opb_temp_id)='".$_GET['id']."'","","","","");
	$getBillTemplate = mysqlSelect("*","out_patient_billing_template","md5(opb_temp_id)='".$_GET['id']."'","","","","");
	
	$checkSetting= mysqlSelect("*","doctor_settings","doc_id='".$getBillTemplate[0]['doc_id']."' and doc_type='1'","","","","");
	
		$patientName=$getBillTemplate[0]['patient_name'];
		$patientMobile=$getBillTemplate[0]['patient_mobile'];
		$patientAddress=$getBillTemplate[0]['address'];
		
	//Doctors Details	
			$get_doc_details = mysqlSelect("ref_name,doc_state","referal","ref_id='".$getBillTemplate[0]['doc_id']."'","","","","");
			$doctor_name = $get_doc_details[0]['ref_name'];  //Doctor Name
			$doctor_id = $get_doc_details[0]['ref_id'];  //Doctor Id
			
		//Doctors Clinic Details
			$get_doc_clinic = mysqlSelect("a.hosp_name as Hospital,a.hosp_addrs as Hosp_address,a.hosp_city as hosp_city,a.hosp_state as hosp_state,a.hosp_country as hosp_country, a.hosp_contact as hosp_contact, a.hosp_email as hosp_email","hosp_tab as a inner join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$getBillTemplate[0]['doc_id']."'","","","","");
			
			$Clinic_name = $get_doc_clinic[0]['Hospital'];  //Clinic Name
			$Clinic_address = $get_doc_clinic[0]['Hosp_address'];  //Clinic Address
			$Clinic_City = $get_doc_clinic[0]['hosp_city'];  //Clinic Address
			$Clinic_State= $get_doc_clinic[0]['hosp_state'];  //Clinic State
			$Clinic_Country = $get_doc_clinic[0]['hosp_country'];  //Clinic Country
			$clinic_contact = $get_doc_clinic[0]['hosp_contact'];
			$clinic_email = $get_doc_clinic[0]['hosp_email'];
			
	
?>

<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Print Out Patient Billing</title>
	<link rel="stylesheet" media="all" href="assets/css/print-emr.css">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    </head>
    <body id="<?php echo $_GET['pid']; ?>">
	
	<div id="actions">
			
	    <a href="#" class="btn" onclick="window.print(); return false;">PRINT</a>
	
		<a href="../Out-Patient-Billing" class="btn" >EXIT</a>
		
	</div>
	<div class="container" id="main-content">
		
		<?php if(count($checkSetting)==0 || $checkSetting[0]['prescription_pad']=="0" || $checkSetting[0]['prescription_pad']=="2") { 
		
		if(!empty($checkSetting[0]['doc_logo'])){ $docLogo = "../docLogo/".$getBillTemplate[0]['doc_id']."/".$checkSetting[0]['doc_logo'];} else { $docLogo = "assets/images/doctor-symbol-b.png";}
		?>
	    <header class="group">
		<img src="<?php echo $docLogo; ?>" width="150">
		<div id="doc-details">
		    <p>
		    <strong>Dr. <?php echo $doctor_name; ?></strong><br>
		    <?php
			while(list($key_spec, $value_spec) = each($get_doc_spec)){
				echo $value_spec['specialization'].", ";   //Doctor Specialization				
			}
		    ?><br>
		    <?php echo $Clinic_name; ?>
		    <?php echo $Clinic_address; ?><br>
		    <?php echo $Clinic_City . ", " . $Clinic_State; ?><br>
		   <!-- <?php if($clinic_contact) { echo "Tel: " . $clinic_contact . "<br>"; } ?>
		    <?php if($clinic_email) { echo "Email: " . $clinic_email . "<br>"; } ?>-->
		    </p>
		</div>
	    </header>
		<?php } 
		else {
			$headerHeightPixel = $checkSetting[0]['presc_pad_header_height']*37.795276;
			$footerHeightPixel = $checkSetting[0]['presc_pad_footer_height']*37.795276;
			?>
		 <header class="group">
			
			<div style="margin-top:<?php echo $headerHeightPixel; ?>px;"></div>
		 
		 </header>
		 
		<?php } ?>
		 <div id="patient-details" class="group">
		 <h3>Patient Details</h3>
		<p style="width:250px;">
		    <b>Name:</b> <?php echo $patientName; ?><br>
			 <b>Address:</b><br>
		    <?php echo $patientAddress; ?><br>
		    <b>Phone:</b> <?php echo $patientMobile; ?>
		</p>
		
		<p style="width:160px; text-align:left;">
		<b>Date:</b> <?php echo date('d M Y H:i a',strtotime($curDate))."<br>"; ?>
		<?php if($patient_tab[0]['hyper_cond']!=0) { ?><strong>Hypertension:</strong> <?php echo $hyperStatus."<br>";  } ?>
		<?php if($patient_tab[0]['diabetes_cond']!=0) { ?><strong>Diabetes:</strong> <?php echo $diabetesStatus; } ?>
		<!--<div id="qr" style="padding:0px;">
		<img src="assets/images/medisense-qr.png">
	    </div>-->
		</p>
	    </div>
	   

		<div id="prescription" cellpadding="0" cellspacing="0" border="0">
			<table >
				<thead>
				<th style="width:10%;text-align:left;">Sl. No.</th>
				<th style="width:60%; text-align:left;">Narration</th>
				<th style="width:10%;text-align:left;">Amount</th>
				<th style="width:10%;text-align:left;">Discount</th>
				<th style="width:30%;text-align:left;">Total</th>
				</thead>
					<tbody>
						<?php 
						$i=1;
						foreach($getPayList as $row)
	
							{
								
							 $output .= '<tr><td style="width:10%;">'.$i.'</td>
											<td style="width:60%; text-align:left;">'.$row['narration'].'</td>
											<td style="width:10%;text-align:left;" class="alignright">Rs. '.$row['amount'].'</td>
											<td style="width:10%;text-align:left;" class="alignright">'.$row['discount'].'%</td>
											<td style="width:30%;text-align:left;" class="alignright">Rs. '.$row['total_amount'].'</td>
											
										</tr>';
							 $i++;
							}
							$output .='<tr class="total"><td style="width:10%;">&nbsp;</td>
														<td style="width:60%; text-align:left;">&nbsp;</td>
														<td style="width:10%;text-align:left;" class="alignright">&nbsp;</td>
														<td style="width:10%;text-align:left;" class="alignright">Total</td>
														<td style="width:30%;text-align:left;" class="alignright">Rs. '.$getTotAmount[0]['tot_amount'].'</td>
										</tr></table>';

							echo $output;
						
						?>
					</tbody>
			</table>
			
		</div>


	    
		 <!-- Sweet alert -->
      <script src="assets/js/print-emr.min.js"></script>
		
		
		<div style="margin-bottom:<?php echo $footerHeightPixel; ?>px;"></div>
		
		
	</div>
    </body>
</html>
