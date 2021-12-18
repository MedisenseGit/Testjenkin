<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$patient_id=$_GET['p'];
$episode_id=$_GET['r'];
//echo $patient_id; 

//echo $episode_id;
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}
$curdate=date('Y-m-d');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


	//$getPharmalist 	 = mysqlSelect("*","doc_my_patient","md5(patient_id)='".$patient_id."'" ,"","","","");
	
	$getPharmalist 		 = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","md5(a.patient_id)='".$patient_id."'" ,"","","","");
	
	
	$prescription_result = mysqlSelect('*','doc_patient_episode_prescriptions',"md5(episode_id) ='".$episode_id."'","","","","");
	$pharma_referrals    = mysqlSelect('*','pharma_referrals',"md5(episode_id) ='".$episode_id."'","","","","");
		
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Onboard Doctors</title>
		<?php include_once('support.php'); ?>
		<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
		<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
		<link href="css/jquery.dataTables.min.css" rel="stylesheet">
		<style>
			.collapsible {
			  background-color: #d3d3d3ab;
			  color: black;
			  cursor: pointer;
			  padding: 10px;
			  width: 100%;
			  border: none;
			  text-align: left;
			  outline: none;
			  font-size: 14px;									 
			}

			.active, .collapsible:hover {
			 /* background-color: #d3d3d3ab;*/
			}

			.collapsible:after {
			  content: '\002B';
			  color: black;
			  font-weight: bold;
			  float: right;
			  margin-left: 5px;
			}

			#before-search .active:after {
			  content: "\2212";
			}

			.content {
			  padding: 0 18px;
			 /* max-height: 0;*/
			  overflow: hidden;
			  transition: max-height 0.2s ease-out;
			  background-color: white;
			   margin-bottom:20px;									  
			   border: 2px solid #d3d3d3ab;
			}
		</style>
	
	</head>
	<body>
		<div id="wrapper">
			<?php include_once('sidemenu.php'); ?>
			<div id="page-wrapper" class="gray-bg">
				<?php include_once('header_top.php'); ?>
				<div class="row wrapper border-bottom white-bg page-heading">
					<div class="col-lg-10">
						<h2>Pharma Details</h2>
						<ol class="breadcrumb">
							<li>
								<a href="Pharma-validattion-List">Pharma Requests</a>
							</li>
							<li class="active">
								<strong>Details</strong>
							</li>
						</ol>
					</div>
					<div class="col-lg-2">
					</div>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="ibox float-e-margins">
						<div class="ibox-content" id="before-status">
							<div class="row">
								<div class="col-lg-12">
									<h3>Patient Details</h3>
									<div class="row">
										<div class="col-lg-3">
											<span><strong>Name :</strong> <?php echo $getPharmalist[0]['patient_name'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Age :</strong> <?php echo $getPharmalist[0]['patient_age'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Email :</strong> <?php echo $getPharmalist[0]['patient_email'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Mobile No : </strong><?php echo $getPharmalist[0]['patient_mob'];  ?></span>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-lg-12">
									<h3>Contact Address</h3>
									<div class="row">
										<div class="col-lg-3">
											<span><strong>Address :</strong> <?php echo $getPharmalist[0]['patient_addrs'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Location :</strong> <?php echo $getPharmalist[0]['patient_loc'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>State :</strong> <?php echo $getPharmalist[0]['pat_state'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Country : </strong><?php echo $getPharmalist[0]['pat_country'];  ?></span>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-lg-12">
									<?php $getPharmacustlist = mysqlSelect("*","pharma_customer","md5(patient_id)='".$patient_id."'" ,"","","","");?>
									<h3>Shipping Address</h3>
									<div class="row">
										<div class="col-lg-3">
											<span><strong>Address : </strong><?php echo $getPharmacustlist[0]['pharma_cust_address'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>City :</strong> <?php echo $getPharmacustlist[0]['pharma_cust_city'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>State : </strong><?php echo $getPharmacustlist[0]['pharma_cust_state'];  ?></span>
										</div>
										<div class="col-lg-3">
											<span><strong>Country :</strong> <?php echo $getPharmacustlist[0]['pharma_cust_country'];  ?></span>
										</div>
									</div>
								</div>
							</div>
							
						</div>
					</div>	
					<div class="ibox float-e-margins">
						<div class="ibox-content" id="before-status">
							<div class="row">
								<div class="col-lg-12">
									<h3>Prescription Details</h3>
									<table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
										<thead>
											<tr>
												<th>SI NO.</th>
											    <th>Medicine</th>
				                                <th>Generic Name</th>
				                                <th>frequency</th>
												<th>Timing</th>
				                                <th>Duration</th>
											</tr>
										</thead>
										<tbody>
													<?php $j=1;
													foreach($prescription_result as $list){	
														
													?>
												<a>
													<tr>
														<td><?php echo $j;?></td>
														<td><?php echo $list['prescription_trade_name'];?></td>
														<td><?php echo $list['prescription_generic_name'];?> </td>
														
														<td><?php echo $list['prescription_frequency'];?></td>
														
														<?php $prescription_timings = mysqlSelect('*','doc_medicine_timing_language',"language_id='".$list['timing']."'","","","","");
														 ?>
														<td><?php echo $prescription_timings[0]['english']; ?></td>
														<td><?php echo $list['duration'];?></td>
													</tr>
												</a>
												
												<?php $j = $j+1;
												} ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="ibox float-e-margins">
						<div class="ibox-content" id="before-status">
							<div class="row">
								<div class="col-lg-12">
									<div class="col-lg-8">
										<?php 
											
										?>
										<input type="hidden" id="pr_id" name="pr_id" value="<?php echo $pharma_referrals[0]['pr_id']; ?>" />
											<textarea type="submit" class="form-control" rows="5" id="Doc_comments" name="Doc_comments" placeholder="Write your summary..."><?php echo $pharma_referrals[0]['accept_doc_comments']; ?></textarea>
									</div>
									<div class="col-lg-4">
										<button type="submit" class="btn btn-primary" style="padding-t0p:10px; margin:10px;" id="Doc_comments" onclick="varifiedfuc(1);">Accept</button><br>
										<button type="submit" class="btn btn-danger" style="padding-top:10px;margin:10px;" id="Doc_comments" onclick="varifiedfuc(2);" >Reject</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php include_once('footer.php'); ?>
			</div>
		</div>
	</body>
</html>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
		<script languages="text/javascript">
			function varifiedfuc(type)
			{
					var Doc_comments = $('#Doc_comments').val();
					var pr_id = $('#pr_id').val();
					//alert(Doc_comments);
					//alert(type);
					//alert(pr_id);
					
						$.ajax({
						type: "POST",
						url: "pharma_comments.php",
						data:{"Doc_comments":Doc_comments,"type":type,"pr_id":pr_id},
						success: function(data)
							{
								//alert(data);
							}
						});
			}
		</script>
		