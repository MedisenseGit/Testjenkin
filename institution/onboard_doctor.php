<?php

ob_start();
error_reporting(0); 
session_start();
$admin_id 	= 	$_SESSION['user_id'];
$ref_id		=	$_GET['p'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}
$curdate	=	date('Y-m-d');
require_once("../classes/querymaker.class.php");
$getdocDeta = mysqlSelect("*","referal","","ref_id desc","","");
		
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
		<script src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
		<script>
			$(document).ready(function () {
				$('#table_id').DataTable();
			});
		</script>
		
			
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
						<h2>List of Doctors</h2>
						<ol class="breadcrumb">
							<li>
								<a href="Home">Home</a>
							</li>
							<li class="active">
								<strong>List of Doctors</strong>
							</li>
						</ol>
					</div>
					<div class="col-lg-2">
					</div>
				</div>
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox float-e-margins">
								<div class="ibox-content" id="before-status">
									<table class="footable table table-stripped" data-page-size="100" data-filter=#filter id="table_id">
										<thead>
											<tr>
												<th>SI NO.</th>
											    <th>Doctor's name</th>
				                                <th>City / State</th>
				                                <th>Country</th>
												<th>Verified By medisense</th>
												<th>Video Verification</th>
				                                <th>Verified By medical proffessional</th>
				                            </tr>
										</thead>
										<tbody>
										<?php 
										$j=1;
										foreach($getdocDeta as $list)
										{	
										?>
										<a>
											<tr>
												<td><?php echo $j;?></td>
												<td><a href="Onboard-Doctor-details?p=<?php echo md5($list['ref_id']);  ?>" ><?php echo $list['ref_name'];  ?></a></td>
												<td><a><?php echo $list['doc_city'];  ?> | <?php echo $list['doc_state'];  ?></a></td>
												<td><a><?php echo $list['doc_country'];?></a></td>
												<?php 
													if  ($list['verified_by_medisense']==0)
													{
														$status1="Pending";
														$btn_type= "btn-warning";
														}
														else if ($list['verified_by_medisense']==1) {
															$status1="Verified";
															$btn_type= "btn-primary";
														}
												?>
												<td><a class="<?php echo $btn_type;?>" style="padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px; margin:0px;"><?php echo $status1;  ?></a></td>
													
													<?php if  ($list['video_veification_status']==0){
														$status3="Pending";
														$btn_type= "btn-warning";
														}
														else if ($list['video_veification_status']==1) {
															$status3="Verified";
															$btn_type= "btn-primary";
														}
													?>
													<td><a class="<?php echo $btn_type;?>" style="padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px; margin:0px;"><?php echo $status3;  ?></a></td>
														
														<?php if  ($list['verified_by_medical_professional']==0){
															$status2="Pending";
															$btn_type= "btn-warning";
															}
															else if ($list['verified_by_medical_professional']==1) {
																$status2="Verified";
																$btn_type= "btn-primary";
															}
														?>
													<td><a  class="<?php echo $btn_type;?>" style="padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px; margin:0px;"><?php echo $status2;  ?></a></td>
													
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
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</body>
</html>