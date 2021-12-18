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
$objQuery = new CLSQueryMaker();
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
$allRecord = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","a.patient_id desc","","","$eu, $limit");
$pag_result = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","a.patient_id desc");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
$TotalCount= $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Result_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","","","","");
              
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cases Sent</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
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
                    <h2>Cases Sent</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Cases Sent</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
		
							<div>
							<?php if($_GET['response']=="patient-created") {  ?>
							<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <i class="fa fa-check"></i><a class="alert-link" href="#">Patient case sent Successfully </a>.
                            </div>
							<?php } if($_GET['response']=="patient-created-failure") {  ?>
							<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">Error!!!</a> Failed to send case.
                            </div>
							<?php } if($_GET['response']=="appointment-success") {  ?>
							<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#"> Appointment scheduled Successfully </a>.
                            </div>
							<?php } ?>		
							
							</div>
		
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Sent Cases List</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Reg. Date</th>
                                <th>Name</th>
                                <th>Referred To</th>
								<th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
										
										$refDoctors = $objQuery->mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.bucket_status as Bucket_Status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$list['Patient_Id']."'","","","","");
										$getCurrentStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['patient_id']."'","","","","");
                      
										if($refDoctors[0]['Bucket_Status']=="2"){ $patient_status="<span class='label label-warning'>SENT</span>"; ?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; } ?>
										
                            <a href="Home"><tr>
                                <td><?php echo $refDoctors[0]['Patient_Id'];  ?></td>
                                <td><?php echo date('M d, Y',strtotime($refDoctors[0]['Reg_Date']));  ?></td>
                                <td><a href="patient-history?p=<?php echo md5($refDoctors[0]['Patient_Id']);  ?>"><?php echo $refDoctors[0]['Patient_Name'];  ?></a></td>
								<td><?php
											if(!empty($refDoctors)){
											foreach($refDoctors as $listDoc) { 
											$getDocDet = $objQuery->mysqlSelect("a.ref_name as Doc_Name,c.hosp_name as Doc_Hosp,c.hosp_city as Hosp_City","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$listDoc['Doc_Id']."'","","","","");
											?>
											 
											<?php echo "<b>".$getDocDet[0]['Doc_Name']."</b><br>  ".$getDocDet[0]['Doc_Hosp'].",  ".$getDocDet[0]['Hosp_city'],"<br><br>";
											}
											}
											else{
												echo " ";
											}
											?></td>
                                <td> <?php echo $patient_status; ?> </td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-comment"></i> Create New Case</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_patient_details.php"  name="frmAddPatient" >
                                <div class="form-group"><label class="col-sm-2 control-label">Choose Doctor <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose doctor..." class="chosen-select"  name="selectref" id="selectref" required="required"  tabindex="2">
											<option value="" selected>Choose Doctor</option>
												<?php 
												$getDoctor= $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Doc_name,c.hosp_name as Hosp_name","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","","a.ref_name asc","","","");
													$i=30;
													foreach($getDoctor as $DocList){
												?> 
														
														<option value="<?php echo stripslashes($DocList['Ref_Id']); ?>" />
														<?php echo stripslashes($DocList['Doc_name']).", ".stripslashes($DocList['Hosp_name']);?></option>
													
													
													<?php 
														$i++;
													}?> 
										</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text"  id="se_pat_name" name="se_pat_name" required="required" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text"  id="se_pat_age" name="se_pat_age" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" id="se_phone_no" name="se_phone_no" required="required" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-4"><input type="email" id="se_email" name="se_email" class="form-control"></div>
								
                                </div>
								
								<div class="form-group">
								<label class="col-sm-2 control-label">Gender <span class="required">*</span></label>
                                      <div class="col-sm-10">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="option1" name="radioInline" checked="">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="option2" name="radioInline">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										</div>
								</div>		
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
											<option value="India" selected>India</option>
												<?php 
												$getCountry= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"];	?>"><?php echo $StateList["state_name"]; ?></option>
													
													<?php
													}
													?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" id="se_city" name="se_city" required="required" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address <span class="required">*</span></label>

                                    <div class="col-sm-10"><textarea class="form-control" id="se_address" name="se_address" required="required" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Chief Medical Complaint <span class="required">*</span></label>

                                    <div class="col-sm-10"><textarea class="form-control" id="se_info" name="se_info" required="required" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Medical Query </label>

                                    <div class="col-sm-10"><textarea class="form-control" id="se_query" name="se_query" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Brief Description </label>

                                    <div class="col-sm-10"><textarea class="form-control" id="se_description" name="se_description" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Medical Reports </label>

                                    <div class="col-sm-10"><label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="file-3[]"  multiple="true" class="hide">
                                        <i class="fa fa-upload"></i> Upload Medical Reports
                                    </label></div>
                                </div>
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<!-- <button type="submit" name="refer_patient" class="btn btn-primary block full-width m-b ">SEND CASE</button> -->
								<button type="submit" name="refer_patient" id="refer_patient" class="btn btn-success"><i class="fa fa-mail-forward"></i> SEND CASE </button>
								</div>
								</div>
							</form>
							</div>
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
