<?php
ob_start();
error_reporting(0); 

require_once("../../classes/querymaker.class.php");


		
	$patient_tab = mysqlSelect("*","diagnostic_customer","md5(diagnostic_customer_id)='".$_GET['pid']."'","","","","");
	//print_r($patient_tab);
	if(COUNT($patient_tab)==0){
	    echo "<h2>Error!!!!!!!</h2>";
	}
	else{
			
			$patient_id = $patient_tab[0]['diagnostic_customer_id']; //Patient ID			
			$patient_name = $patient_tab[0]['diagnostic_customer_name']; //Patient Name			
			$patient_age = $patient_tab[0]['diagnostic_cust_age']; //Patient Age	
			$patient_mob = $patient_tab[0]['diagnostic_customer_phone']; //Patient Mobile No.				
			$patient_loc = $patient_tab[0]['diagnostic_cust_city']; //Patient City	
			$patient_state = $patient_tab[0]['diagnostic_cust_state']; //Patient State
			$patient_country = $patient_tab[0]['diagnostic_cust_country']; //Patient Country
			$patient_address = $patient_tab[0]['diagnostic_cust_address']; //Patient Country			
			$patient_email = $patient_tab[0]['diagnostic_customer_email'];			
			
			if($patient_tab[0]['diagnostic_cust_gender']=="1"){
				$patient_gender="Male";
			}
			else if($patient_tab[0]['diagnostic_cust_gender']=="2"){   //Patient Gender
				$patient_gender="Female";
			}
			else if($patient_tab[0]['diagnostic_cust_gender']=="3"){
				$patient_gender="Other";
			}			

		
		
		//Episode Details
			
			$patient_episodes = mysqlSelect("*","diagnostic_patient_episodes","diagnostic_customer_id = '". $patient_id ."' and md5(episode_id)='".$_GET['episode']."'","","","","");
			
			$episode_created_date=date('d M Y, H:i a',strtotime($patient_episodes[0]['datetime']));   //Prescription Date
			
			
			$get_medical_complaint = mysqlSelect("a.examination as diagno_examination_id, a.examination_id as diagno_exam_id, a.exam_result as exam_result,a.findings as findings, a.diagnostic_customer_id as diagnostic_customer_id, a.episode_id as episode_id, b.examination_id as examination_id,b.examination as examination","diagnostic_patient_examination_active as a left join examination as b on a.examination=b.examination_id","a.episode_id='".$patient_episodes[0]['episode_id']."'","","","","");
				
				
		//Doctors Details	
			$get_diagno_details = mysqlSelect("*","Diagnostic_center","diagnostic_id='".$patient_episodes[0]['diagnostic_id']."'","","","","");
			$diagno_name = $get_diagno_details[0]['diagnosis_name'];  //Doctor Name
	
			$Clinic_City = $get_diagno_details[0]['diagnosis_city'];  //Clinic Address
			$Clinic_State= $get_diagno_details[0]['diagnosis_state'];  //Clinic State
			$Clinic_Country = $get_diagno_details[0]['diagnosis_country'];  //Clinic Country
			$clinic_contact = $get_diagno_details[0]['diagnosis_contact_num'];
			$clinic_email = $get_diagno_details[0]['diagnosis_email'];
			
		//Prescription Details
		$doc_patient_episode_test = mysqlSelect("*","diagnostic_patient_temp_investigation","episode_id = '". $patient_episodes[0]['episode_id'] ."' "," pti_id ASC","","","");

		
?>

<!DOCTYPE html>
<html>
    <head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width">
	<title>Print Patient Visit Details</title>
	<link rel="stylesheet" media="all" href="visit-print.css">
	<!-- Sweet Alert -->
    <link href="../../assets/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    </head>
    <body id="<?php echo $_GET['pid']; ?>" data-pmail="<?php echo $patient_email; ?>">
	<div id="actions">
	    <a href="#" class="btn" onclick="window.print(); return false;">PRINT</a>
	  <a href="#" class="btn" id="sendEMAIL" data-id="<?php echo $_GET['pid']; ?>" data-email="<?php echo $patient_email; ?>">Email Report</a>
		<a href="#" class="btn" id="sendSMS" data-id="<?php echo $_GET['pid']; ?>" data-mobile="<?php echo $patient_mob; ?>">SMS Report</a>
		
	    <a href="/Diagnostic/Customer_Profile_Info?p=<?php echo $_GET['pid'];?>" class="btn">EXIT</a>
	</div>
	<div class="container" id="main-content">
	    <header class="group">
		<img src="dilse.jpg" width="120">
		<div id="doc-details">
		    <p>
		    <strong><?php echo $diagno_name; ?></strong><br>
		   
		    <?php echo $Clinic_City . ", " . $Clinic_State; ?><br>
		    <?php if($clinic_contact) { echo "Tel: " . $clinic_contact . "<br>"; } ?>
		    <?php if($clinic_email) { echo "Email: " . $clinic_email . "<br>"; } ?>
		    </p>
		</div>
	    </header>

	    <div id="patient-details" class="group">
		<p>
		    <b>ID: </b>#<?php echo $patient_id; ?><br>
		    <b>Patient Name:</b> <?php echo $patient_name; ?><br>
		    <b>Age: </b><?php echo $patient_age; ?> Yrs<br>
		   <b> Gender:</b> <?php echo $patient_gender; ?>
		</p>
		<p>
		    <b>Address:</b><br>
		    <?php echo $patient_address; ?><br>
		    <?php echo $patient_loc; ?><br>
		    <?php echo $patient_state; ?><br>
		    <b>Phone:</b> <?php echo $patient_mob; ?>
		</p>
		<p>
		<b>Date:</b> <?php echo $episode_created_date; ?>
		</p>
		<div id="qr">
		<img src="medisense-qr.png">
	    </div>
	    </div>

	    <div id="diagnosis">
		<ul>
		
		<?php if(!empty($get_medical_complaint)){ ?>
		    <li><strong>Examination:</strong>
		    <div class="table-responsive">	
										<table class="table table-bordered">
											<thead>
											<tr>
											<th>Examination</th>
											<th>Result</th>
											<th>Findings</th>
											</tr>
											<thead>
											<tbody>
											
													
												<?php 
												
												while(list($key_exam, $value_exam) = each($get_medical_complaint))	
												{  
												?>
												<tr>
												<td>
												<input type="hidden" name="examination_id[]" value="<?php echo $value_exam['diagno_exam_id']; ?>"/><?php echo $value_exam['examination']; ?></td>
											<!--	<td>
												<?php echo $value_exam['examination']; ?></td>-->
												<!--<td><?php echo $value_exam['exam_result']; ?></td>-->
												<td><?php echo $value_exam['exam_result']; ?></td>
												<td><?php echo $value_exam['findings']; ?></td>
												</tr>
												
												<?php } //end while
												?>
												  
											   </tbody>
										</table>
									</div>
		    </strong></li>
		<?php 
		}  ?>
		</ul>
	    </div>

<?php if (COUNT($doc_patient_episode_test) > 0) { ?>
	    <div id="prescription" cellpadding="0" cellspacing="0" border="0">
		<table>
				<thead>
					<tr>
						<th>Test</th>
						<th>Normal Value</th>
						<th>Actual Value</th>
						
					</tr>
				</thead>
					<tbody>
					<?php
						
						while (list($patient_episode_prescription_key, $patient_episode_test_val) = each($doc_patient_episode_test))
							{
							
					?>
						<tr>
							<td><?php echo $patient_episode_test_val['test_name'] ?></td>
							<td><?php echo $patient_episode_test_val['normal_range'] ?></td>
							<td><?php echo $patient_episode_test_val['test_actual_value'] ?></td>
							
						</tr>
					<?php } //end while ?>
					</tbody>
		</table>
	    </div>
			<?php } //endif
	
	} //endif
?>
	

	     <script src="../../assets/js/jquery-3.1.1.min.js"></script>
		 <!-- Sweet alert -->
	<script src="../../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
		 <script src="language.js"></script>
	    <script src="print.min.js"></script>
	</div>
    </body>
</html>
