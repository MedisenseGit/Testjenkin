<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$patient_id=$_GET['p'];
$episode_id=$_GET['r'];
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


	 if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
		
		$no_of_records_per_page = 10;
		$offset = ($pageno-1) * $no_of_records_per_page;
		
		$TotalRecord = mysqlSelect("a.episode_id as episode_id","pharma_referrals as a inner join doc_my_patient as b on a.patient_id=b.patient_id inner join pharma as c on a.pharma_id=c.pharma_id","a.patient_id!=0","a.patient_id desc","","","");
		
		$total_rows = count($TotalRecord);
		$total_pages = ceil($total_rows / $no_of_records_per_page);
		
			
			
			
		
		$getPharmalist =mysqlSelect("a.episode_id as episode_id,a.patient_id as patient_id,a.acceptance_status as acceptance_status,b.patient_id as patientId,b.patient_name as patient_name,b.patient_email as patient_email,b.patient_mob as patient_mob,c.pharma_name as pharma_name","pharma_referrals as a inner join doc_my_patient as b on a.patient_id=b.patient_id inner join pharma as c on a.pharma_id=c.pharma_id","a.patient_id!=0" ,"","a.patient_id desc","","$offset,$no_of_records_per_page");
		//$getPharmalist = mysqlSelect("*","pharma_referrals ","a.patient_id!=0","a.patient_id desc","","");
			//var_dump($$total_pages);
			//exit();
			
	
			
			
			
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
		
		<!--link href="css/jquery.dataTables.min.css" rel="stylesheet"-->
		<script src="js/jquery.js"></script>
		<!--script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
		<script>
			$(document).ready(function () {
				$('#table_id').DataTable();
			});
		</script-->
		
			<script>
			function myFunction() {
				  var input, filter, table, tr, td, i, txtValue;
				  input = document.getElementById("myInput");
				  filter = input.value.toUpperCase();
				  table = document.getElementById("myTable");
				  tr = table.getElementsByTagName("tr");
				  for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[2];
					if (td) {
					  txtValue = td.textContent || td.innerText;
					  if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					  } else {
						tr[i].style.display = "none";
					  }
					}       
				  }
				}
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
		<style>
			.curPage {
			font-weight:bold;
			font-size:16px;	
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
						<h2>List of incoming requests</h2>
						<ol class="breadcrumb">
							<li>
								<a href="Pharma-validattion-List">Pharma Requests</a>
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
									<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
									<table class="table table-stripped" id="myTable">
										<thead>
											<tr>
												<th>SI NO.</th>
											    <th>Patient ID</th>
				                                <th>Patient Name</th>
				                                <th>Email ID</th>
												<th>Mobile No</th>
				                                <th>Pharmacy Name</th>
												<th>Status</th>
				                            </tr>
										</thead>
										<tbody>
													<?php $j=1;
													foreach($getPharmalist as $list){	
														
													?>
												<a>
													<tr>
														<td><?php echo $j;?></td>
														<td><?php echo $list['patient_id'];  ?></td>
														<td><a href="Pharma-validattion-Info?p=<?php echo md5($list['patient_id']); ?>&r=<?php echo md5($list['episode_id']); ?>"><?php echo $list['patient_name'];  ?></a></td>
														<td><?php echo $list['patient_email'];  ?> </td>
														<td><?php echo $list['patient_mob'];?></td>
													    <td><?php echo $list['pharma_name'];?></td>
															<?php if  ($list['acceptance_status']==0){
																		$status="In Progress";
																		$btn_type= "btn-warning";
																}
																else if ($list['acceptance_status']==1) {
																	$status="Accepted";
																	$btn_type= "btn-primary";
																}
																else if ($list['acceptance_status']==2) {
																	$status="Rejected";
																	$btn_type= "btn-danger";
																}
															?>
														<td><label class="<?php echo $btn_type;?>" style="padding-left:10px;padding-right:10px;padding-top:0px;padding-bottom:0px; margin:0px;"><?php echo $status;  ?></label></td>
													</tr>
												</a>
												
												<?php $j = $j+1;
												} ?>
										</tbody>
									</table>
									<div class="row">
										<div class="col-lg-6">
											<ul class="pagination">
												<li><a href="?pageno=1">First</a></li>
												<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
													<a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
												</li>
												<li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
													<a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
												</li>
												<li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li><br>
											</ul>
										</div>
										<div class="col-lg-6" style="align-items:right;">
											<ul class="pagination">
												<?php 
													for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages?>
															<li><?php	echo "<a href='Pharma-validattion-List?pageno=".$i."'";
																if ($i==$pageno)  
																	echo " class='curPage'";
																echo "<a>".$i."</a> "; ?></li><?php
													}; 
												?>
											<ul>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
			<?php include_once('footer.php'); ?>
		</div>
	</body>
</html>