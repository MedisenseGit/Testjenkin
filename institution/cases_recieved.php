<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//echo $admin_id;
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
			
// $allRecord = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$admin_id."'","b.timestamp desc","","","$eu, $limit");

// $pag_result = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$admin_id."'","b.timestamp desc");

$allRecord = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","","b.timestamp desc","","","$eu, $limit");
$pag_result = mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","","b.timestamp desc");

$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
//$TotalCount= mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Result_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","","","","","");
           
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cases Recieved</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Cases Recieved</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Cases Recieved</strong>
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
                    <div class="ibox-title">
                        <h5>Recieved Cases List</h5>
                        <div class="ibox-tools">
                                <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-xs">Create new case</a>
							
                            </div>
                    </div>
					
					<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            <i class="fa fa-wheelchair modal-icon"></i>
                                            <h4 class="modal-title">Add Patient Details</h4>
                                           <!-- <small class="font-bold">Apply Job Here</small>-->
                                        </div>
										<form enctype="multipart/form-data" method="post" name="frmType" action="add_patient_details.php">
									
                                        <div class="modal-body">
                                           <div class="form-group"><label>Choose Care Partner</label> 
										   <select data-placeholder="Choose doctor..." class="chosen-select" name="selectRefpartner" required="required"  tabindex="2">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$get_partner = mysqlSelect("a.partner_id as Partner_Id,a.contact_person as Contact_name,a.partner_name as Partner_name,a.location as Location","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","d.company_id='".$admin_id."'","a.partner_id desc","","","");  
												$i=30;
													foreach($get_partner as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Partner_Id']); ?>" />
														<?php echo stripslashes($DocList['Partner_name']).", ".stripslashes($DocList['Location']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
													<div class="form-group"><label>Hospital Doctor</label><select data-placeholder="Choose doctor..." class="chosen-select" name="selectref" required="required"  tabindex="2">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name,d.spec_name as Department","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id inner join specialization as d on d.spec_id=a.doc_spec","c.company_id='".$admin_id."'","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" />
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Department']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                                                    <div class="form-group"><label>Patient Name</label> <input type="text"  name="se_pat_name"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Age</label> <input type="text"  name="se_pat_age"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Weight</label> <input type="text"  name="se_weight"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Contact Person</label> <input type="text"  name="se_con_per"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Contact Number</label> <input type="text"  name="se_phone_no"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Email</label> <input type="text"  name="se_email"  class="form-control" required="required" /></div>
													<div class="form-group"><label>Country</label> 
													<select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
														<option value="India" selected>India</option>
															<?php 
															$getCountry= mysqlSelect("*","countries","","country_name asc","","","");
																$i=30;
																foreach($getCountry as $CountryList){
															?> 
																	
																	<option value="<?php echo stripslashes($CountryList['country_name']); ?>" />
																	<?php echo stripslashes($CountryList['country_name']);?></option>
																
																
																<?php 
																	$i++;
																}?> 
														</select>
													</div>
													<div class="form-group"><label>State</label> 
														<select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
															<option value="">Select State</option>
															<?php
															$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
															foreach ($GetState as $StateList) {
															?>
															<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
															
															<?php
															}
															?>
														</select>
													</div>
													<div class="form-group"><label>City</label> <input type="text"  name="se_city"  class="form-control" /></div>
													<div class="form-group"><label>Address</label> <textarea  name="se_address"  class="form-control"  rows="3" placeholder=""></textarea></div>
													<div class="form-group"><label>Chief Medical Complaint</label> <textarea  name="se_info"  class="form-control"  rows="3" placeholder=""></textarea></div>
													<div class="form-group"><label>Patient Query</label> <textarea  name="se_query"  class="form-control"  rows="3" placeholder=""></textarea></div>
													<div class="form-group"><label>Detailed Description</label> <textarea  name="se_des"  class="form-control"  rows="3" placeholder=""></textarea></div>
													
													
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
                                            <button type="submit"  name="refer_patient" id="refer_patient" class="btn btn-primary">Submit</button>
                                        </div>
										</form>
                                    </div>
                                </div>
                            </div>
					
                    <div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                 <th>Patient ID</th>
                                <th>Reg. Date</th>
                                <th>Name</th>
								 <th>Referred To</th>
                                <th>Referred By</th>
								
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach($allRecord as $list){ 
										
								// $refDoctors = mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,a.patient_src as patient_src,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.status2 as status2","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.patient_id='".$list['Patient_Id']."' and d.company_id='".$admin_id."'","","","","");

								$refDoctors = mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,a.patient_src as patient_src,c.source_name as Ref_By,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.status2 as status2,b.response_time as Response_Time,b.response_status as Auto_Response","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as c on c.source_id=a.patient_src","a.patient_id='".$list['Patient_Id']."'","","","","");

								$getRefDoc = mysqlSelect("a.contact_person as partner_name","our_partners as a left join source_list as b on a.partner_id=b.partner_id","b.source_id='".$refDoctors[0]['patient_src']."'","","","","");

								$getCurrentStatus = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");

								$getReferredBy = mysqlSelect("*","patient_referal","patient_id='".$list['Patient_Id']."'","","","","");	
																			
										
										?>										
                            	<a href="Home">
                            		<tr>
										<td><?php echo $refDoctors[0]['Patient_Id'];  ?></td>
                                		<td><?php echo date('M d, Y',strtotime($refDoctors[0]['Reg_Date']));  ?></td>
                                		<td><a href="patient-history?p=<?php echo md5($refDoctors[0]['Patient_Id']);  ?>"><?php echo $refDoctors[0]['Patient_Name'];  ?></a></td>
										<td><?php
											if(!empty($refDoctors)){
											foreach($refDoctors as $listDoc) { 
											
											echo $listDoc['Doc_Id'];
											
											$getDocDet = mysqlSelect("a.ref_name as Doc_Name,b.status2 as status2,b.response_status as Auto_Response,b.response_time as Response_Time,d.hosp_name as Doc_Hosp,d.hosp_city as Hosp_City","referal as a inner join patient_referal as b on a.ref_id=b.ref_id inner join doctor_hosp as c on a.ref_id=c.doc_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","a.ref_id='".$listDoc['Doc_Id']."' and b.patient_id='".$list['Patient_Id']."'","","","","");

											
											if($getDocDet[0]['status2']=="2"){ $patient_status="<span class='label label-warning'>REFERRED</span>"; ?>
										<?php } else if($getDocDet[0]['status2']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($getDocDet[0]['status2']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($getDocDet[0]['status2']=="1"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>"; } 
											?>
											<table>
												<tr>
													<td>
														<?php 
														 echo "<b>".$getDocDet[0]['Doc_Name']."</b><br>  ".$getDocDet[0]['Doc_Hosp']." ".$getDocDet[0]['Hosp_city']."<br>".$patient_status."<br>";?>
													</td>
													<td><div class="m-r-lg"></div></td><td>
													<?php
													if($getDocDet[0]['Auto_Response']=="1") 
														{ ?><b><i class="fa fa-clock-o"></i> Response Time</b><br>
														<?php echo "NE";
											 			} else if(!empty($getDocDet[0]['Response_Time'])){ ?>
															<b><i class="fa fa-clock-o"></i> Response Time</b><br><small><?php  echo con_min_days($getDocDet[0]['Response_Time']); ?></small>
														<?php } ?>
													</td>
												</tr>
											</table>
											<?php	} } /*}
											}
											else{
												echo " ";
											}*/
											?> 
										</td>
								 		<td><?php echo $getRefDoc[0]['partner_name']; ?> </td>
                              			<!-- <td>
											 <?php if($refDoctors[0]['Auto_Response']=="1") 
											{ ?>
											<?php echo "NE";
											 } else if(!empty($refDoctors[0]['Response_Time'])){ ?>
											<i class="fa fa-clock-o"></i> <?php  echo con_min_days($refDoctors[0]['Response_Time']); ?>
											<?php } ?>
										</td> -->
                            		</tr>
                            	</a>
                            <?php } ?>
                            </tbody>
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
</body>

</html>
