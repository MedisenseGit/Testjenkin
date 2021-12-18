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
//$objQuery = new CLSQueryMaker();
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 100;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
$allRecord = mysqlSelect("*","doctor_outgoing_referrals","doc_id='".$admin_id."' and doc_type='1'","timestamp desc","","","$eu, $limit");
$pag_result = mysqlSelect("*","doctor_outgoing_referrals","doc_id='".$admin_id."' and doc_type='1'");
//$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
              
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sent Referral List</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	 <!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">


</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
              <!--  <div class="col-lg-3">
                    <h2>My Patient Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>My Patient Profile</strong>
                        </li>
                    </ol>
                </div>-->
				 <div class="col-lg-12 mgTop">
					<div class="search-form">
                                <!--<form action="add_details.php" method="post" autocomplete="off">-->
								<input type="hidden" name="curURI" value="My-Patient-Details" />
                                    <div class="input-group">
				
                                      <!-- <input type="text" id="serPatient" placeholder="Search patient here" name="search" value="" class="form-control input-lg typeahead_1">-->
									  <input type="text" id="filter" placeholder="Search here..." name="search" value="" class="form-control input-lg">
									  
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
                                                <i class="fa fa-search"></i> Search
                                            </button>
                                        </div>
                                    </div>

                               <!-- </form>-->
                    </div>            
			   </div>
               <!-- <div class="col-lg-2 mgTop">
					<a href="My-Patients"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>-->
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Sent Referral List</h5>
						<!--<div class="ibox-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">
                                    Add New <i class="fa fa-plus"></i>
									</button>
								
											
						</div>-->
						<div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog">
												<div class="modal-content animated bounceInRight">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
															<img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
															<h4 class="modal-title">Add New Pharmacy</h4>
															
														</div>
														<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddPharmacy" >
														<input type="hidden" name="patient_id" value="<?php echo $patient_tab[0]['patient_id']; ?>">
                                   
														<div class="modal-body">
														<div class="form-group"><label>Type</label> <select name="type" required  class="form-control"><option value="1"></option></select></div>
															<div class="form-group"><label>Name</label> <input type="text" name="pharma_name" required value="" class="form-control"></div>
															<div class="form-group"><label>Email</label> <input type="email" name="txtemail" value="" required class="form-control"></div>
															<div class="form-group"><label>Mobile</label> <input type="text" name="mobile" value="" required class="form-control"></div>
															<div class="form-group"><label>City</label> <input type="text" name="city" value="" required class="form-control"></div>
														
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
															
															<button type="submit" name="add_pharma_patient" class="btn btn-primary">Add</button>
															
														</div>
														</form>
													</div>
													</div>
						</div>
                    </div>
                    <div class="ibox-content">

                         <table class="footable table table-stripped" data-page-size="100" data-filter=#filter>
                            <thead>
                            <tr>
								<th>Name</th>
                                <th>Referred Date</th>
								<th>Referred To</th>
								<th>Status</th>
								
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
										
										$getPatDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob,a.patient_gender as patient_gen,b.city as patient_loc ,b.state as pat_state,b.country as pat_country,b.address as patient_addrs,b.hyper_cond as hyper_cond,b.diabetes_cond as diabetes_cond,b.smoking as smoking,b.drug_abuse as drug_abuse,b.prev_intervention as prev_inter,b.other_details as other_details,b.diabetes_cond as diabetes_cond,b.alcoholic as alcoholic,b.family_history as family_history,b.neuro_issue as neuro_issue,b.kidney_issue as kidney_issue,b.height_cms as height,b.weight as weight,b.patient_age as patient_age,a.patient_email as patient_email","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.patient_id='".$list['patient_id']."'","","","","");
										if($list['type']=="1"){
											$getReferralStatus = mysqlSelect("*","diagnostic_referrals as a left join Diagnostic_center as b on a.diagnostic_id=b.diagnostic_id","a.patient_id='".$list['patient_id']."' and a.episode_id='".$list['episode_id']."'","","","","");
											$referredTo = $getReferralStatus[0]['diagnosis_name'];
											if($getReferralStatus[0]['status2'] == "1"){
											$status = "<span class='label label-warning'>REFERRED</span>";
											} else if($getReferralStatus[0]['status2'] == "2") {
											$status = "<span class='label label-primary'>RESPONDED</span>";	
											}
										} else if($list['type']=="2"){
											$getReferralStatus = mysqlSelect("*","pharma_referrals as a left join pharma as b on a.pharma_id=b.pharma_id","a.patient_id='".$list['patient_id']."' and a.episode_id='".$list['episode_id']."'","","","","");
											$referredTo = $getReferralStatus[0]['pharma_name'];
											
												if($getReferralStatus[0]['status2'] == "1"){
											$status = "<span class='label label-warning'>REFERRED</span>";
											} else if($getReferralStatus[0]['status2'] == "2") {
											$status = "<span class='label label-primary'>RESPONDED</span>";	
											}
										}
										else if($list['type']=="3"){
											$getReferralStatus = mysqlSelect("*","optical_referrals as a left join opticals as b on a.optical_id=b.optical_id","a.patient_id='".$list['patient_id']."' and a.episode_id='".$list['episode_id']."'","","","","");
											$referredTo = $getReferralStatus[0]['optical_name'];
											
												if($getReferralStatus[0]['status2'] == "1"){
											$status = "<span class='label label-warning'>REFERRED</span>";
											} else if($getReferralStatus[0]['status2'] == "2") {
											$status = "<span class='label label-primary'>RESPONDED</span>";	
											}
										}
										else if($list['type']=="4"){
											$getReferralStatus = mysqlSelect("*","doctor_out_referral","doc_out_ref_id='".$list['referral_id']."'","","","","");
											$referredTo = $getReferralStatus[0]['doctor_name'].", ".$getReferralStatus[0]['doctor_city'];
											
											$status = "<span class='label label-warning'>REFERRED</span>";
											
										}
																				
							?>
										
                            <a href="Home"><tr class="gradeX">
							<td><a href="My-Patient-Details?p=<?php echo md5($getPatDetails[0]['patient_id']);  ?>"><?php echo $getPatDetails[0]['patient_name']."<br>".$getPatDetails[0]['patient_loc'];  ?></a></td>
								<td><?php echo date('M d, Y H:i',strtotime($list['timestamp']));  ?></td>
                                <td><?php echo $referredTo;  ?></td>
                                <td> <?php echo $status; ?></td>
								
                            </tr></a>
                            <?php } ?>
                            </tbody>
							 <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
                        </table>
						
						 
                    </div>
                </div>
            </div>
            
            </div>
                       
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
	
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	 <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
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
	$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","b.doc_id='".$admin_id."'","","","","");
											
	?>
            $('.typeahead_1').typeahead({
               source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });
			          

        });
		
    </script>
</body>

</html>
