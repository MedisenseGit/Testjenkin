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
			
			
$allRecord = mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","doc_my_patient","doc_id='".$admin_id."'","patient_id desc","","","$eu, $limit");
$pag_result = mysqlSelect("patient_id","doc_my_patient","doc_id='".$admin_id."'","patient_id desc");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
$getRefDet = mysqlSelect("doc_state,ref_address","referal","ref_id='".$admin_id."'","","","","");             
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patients</title>

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
                    <h2>My Patients</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>My Patients</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>My Patient List</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Reg. Date</th>
                               
								<th>Contact Details</th>
                               	
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ ?>
										
										
                            <a href="Home"><tr>
                               <td><a href="my_patient_profile.php?p=<?php echo md5($list['patient_id']); ?>"><?php echo $list['patient_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($list['TImestamp']));  ?></td>
                                <td><i class="fa fa-envelope"></i> <?php echo $list['patient_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list['patient_mob'];  ?></td>
                                
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
                        <h5><i class="fa fa-comment"></i> Add New Patient</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="my_patient_profile_save.php"  name="frmAddPatient" >
                                
								<div class="form-group">
									<label class="col-sm-2 control-label">Patient Name <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_name" required="required" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Age </label>

                                    <div class="col-sm-4"><input type="text" name="se_pat_age" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="se_phone_no" required="required" maxlength="10" minlength="10" placeholder="10 digit mobile no."></div>
									<label class="col-sm-2 control-label">Weight </label>

                                    <div class="col-sm-4"><input type="text" name="se_weight" class="form-control"></div>
																	
                                </div>
								
								<div class="form-group">
								<label class="col-sm-3 control-label ">Gender <span class="required">*</span></label>
                                      <div class="col-sm-9">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_gender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_gender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="3" name="se_gender">
                                            <label for="inlineRadio2"> Others </label>
                                        </div>
										</div>
								</div>
								<div class="form-group">
								<label class="col-sm-3 control-label">Hypertension </label>
                                     <div class="col-sm-8">
									 <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_hyper">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_hyper">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									</div>
								</div>		
								<div class="form-group">
								<label class="col-sm-3 control-label">Diabetes </label>
                                     <div class="col-sm-8">
									 <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="se_diabets">
                                            <label for="inlineRadio1"> Yes </label>
                                        </div>
										<div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="se_diabets">
                                            <label for="inlineRadio2"> No </label>
                                        </div>
									</div>
								</div>	
								<div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="se_country"  tabindex="2">
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="se_state" id="se_state" tabindex="2">
											<?php
													$GetState = mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													if(!empty($getRefDet[0]['doc_state'])){
													?><option value="<?php echo $getRefDet[0]['doc_state']; ?>"><?php echo $getRefDet[0]['doc_state']; ?></option>
													<?php } else{ ?>
													<option value="">Select State</option>
													
													<?php }
													
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

                                    <div class="col-sm-10"><input type="text" name="se_city" value="<?php echo $getRefDet[0]['ref_address']; ?>" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-10"><input type="email" name="se_email" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Address </label>

                                    <div class="col-sm-10"><textarea class="form-control" name="se_address" rows="3"></textarea></div>
                                </div>
								
								
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="save_patient" class="btn btn-primary block full-width m-b ">SAVE</button>
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
