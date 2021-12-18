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


// $busResult = mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hosp_Name,hosp_city as Hosp_City,hosp_state as Hosp_State","hosp_tab","company_id='".$admin_id."'","hosp_id desc","","","");

$busResult = mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hosp_Name,hosp_city as Hosp_City,hosp_state as Hosp_State","hosp_tab","","hosp_id desc","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hospital List</title>

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
                    <h2>Hospital List</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Hospital List</strong>
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
                        <h5>Hospital List</h5>
                        
                    </div>
                    <div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                               <th style="width:300px;">Hospital</th>
							   <th style="width:50px;">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
							<?php foreach($busResult as $list){ ?>
                            <tr>
                                <td><?php echo $list['Hosp_Name'].', '.$list['Hosp_City'].', '.$list['Hosp_State'];  ?></td> 
                                       <td><a href="Edit-Hospital?hosp_id=<?php echo $list['Hosp_Id']; ?>"  class="btn btn-white btn-bitbucket">
                      <i class="fa fa-edit"></i></a></td>	<!--onclick="return showHospital(<?php echo $list['Hosp_Id']; ?>);" -->										
						</tr>
                           <?php }  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins" id="addHospSection">
						<?php
						if($_GET['response']=="add"){ ?>
						<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>SUCCESS !! Details are added successfully.</strong>
						</div>
						<?php 
						} else if($_GET['response']=="update"){ ?>
						<div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>UPDATED!! Details are updated successfully.</strong>
						</div>
						<?php } else if($_GET['response']=="error"){ ?>
						<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                               <strong>Error!!! please fill required field properly.</strong>
						</div>
						<?php } ?>
						 <div class="ibox-title">
                        <h5><i class="fa fa-calendar"></i> ADD HOSPITAL</h5>
                       
                    </div>
                       
                       
                  
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data"  class="form-horizontal" action="add_details.php" method="post" name="frmAddHosp" id="frmAddHosp" >
                               <div class="form-group">
									<label class="col-sm-2 control-label">Hospital Name <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtHospName" required="required" class="form-control"></div>
                                
								</div>

                                <script type="text/javascript">
                                                function getState(val) { 
                                                    $.ajax({
                                                    type: "POST",
                                                    url: "get_state.php",
                                                    data:'country_name='+val,
                                                    success: function(data){
                                                        $("#slctState").html(data);
                                                    }
                                                    });
                                                }
                                </script>
                                
								
							   <div class="form-group"><label class="col-sm-2 control-label">Country <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="form-control autotab chosen-select" name="txtCountry"  tabindex="2" onchange="return getState(this.value); ">
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

                                    <div class="col-sm-10"><select data-placeholder="Choose a State..." class="form-control chosen-select"  required="required" name="slctState" id="slctState" tabindex="2">
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Suburb <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtSuburb" required="required" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtCity" required="required" class="form-control"></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Address</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtAddress" rows="3"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Hospital Overview</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtOverview" rows="3"></textarea></div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Hospital Contact Person <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" name="txtPerson" required="required" class="form-control"></div>
                                
								</div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Hospital Phone No. <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="text" class="form-control" name="txtMobile" required="required" placeholder="10 digit mobile no."></div>
										
                                </div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Email Address</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID1</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail1" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID2</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail2" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID3</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail3" class="form-control"></div>
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Alternate Email ID4</label>

                                    <div class="col-sm-10"><input type="email" name="txtEmail4" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-2 control-label">Communication Status <span class="required">*</span></label>

                                    <div class="col-sm-6"><select data-placeholder="Choose a State..." class="chosen-select" required="required" name="slctComm" id="slctComm">
											<option value="" selected>---Please Select---</option>
											<option value="1">Only to doctor</option>
											<option value="2">Only to Hospital </option>
											<option value="3">Both Hospital & Doctor</option>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Re-Visit Charge <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" name="txtrevisitcharge" required="required"></div>
									<label class="col-sm-2 control-label">New-Visit Charge</label>

                                    <div class="col-sm-4"><input type="text" name="txtnewvisitcharge" class="form-control"></div>
								
                                </div>		
								
                               
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="add_hospital" class="btn btn-primary block full-width m-b ">ADD</button>
								</div>
								</div>
							</form>
							</div>
													
                    </div>
                </div>
				
				<!-- EDIT HOSPITAL SECTION -->
				<div id="editContent"></div>
				
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
