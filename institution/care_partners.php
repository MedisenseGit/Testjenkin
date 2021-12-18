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



$carepartners = mysqlSelect("DISTINCT(a.partner_id) as Partner_Id,a.contact_person as Partner_name,a.cont_num1 as cont_num1,a.Email_id as Email_id,d.hosp_name as Hosp_Name,a.login_status as Login_Status,b.market_person_id as Person_Id,b.doc_id as doc_id","our_partners as a inner join mapping_hosp_referrer as b on a.partner_id=b.partner_id inner join hosp_tab as d on d.hosp_id=b.hosp_id","d.company_id='".$admin_id."'","a.partner_id desc","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Care Partner</title>

    <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validationJs.js"></script>

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Care Partner</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Care Partners</strong>
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
                        <h5>Care Partner List</h5>
                        
                    </div>
                    <div class="ibox-content table-responsive" id="allPartner">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th style="width:200px;">Partner Name</th>
								<th style="width:200px;">Marketing Person</th>
								<th style="width:200px;">Hospital</th>
								<th style="width:200px;">Status</th>
								<th style="width:200px;">Remove</th>
											
                            </tr>
                            </thead>
                            <tbody>
						<?php foreach($carepartners as $list){ 
									$getMarketPerson = mysqlSelect("person_name","hosp_marketing_person","person_id='".$list['Person_Id']."'","","","","");
									if(!empty($list['doc_id'])){
										$getAlert="style=color:red;";
									}
									else{
										$getAlert="";
									}
									?>	
				
                            <tr>
                                <td><?php echo $list['Partner_name']."<br><small><i class='fa fa-mobile'></i> ".$list['cont_num1']."</small><br><small><i class='fa fa-envelope'></i> ".$list['Email_id'];  ?></small></td> 
											 <td><small><font <?php echo $getAlert;?>><?php echo $getMarketPerson[0]['person_name'];  ?></font></small></td> 
											<td><small><?php echo $list['Hosp_Name'];  ?></small></td> 
											<td><?php if($list['Login_Status']==0){ ?><span class='label label-danger' style="text-transform:uppercase;">Pending</span>
											<?php } else { ?><span class='label label-success' style="text-transform:uppercase;">Paired</span><?php } ?></td> 
								<td><a href="#" onclick="return deletePartner(<?php echo $list['Partner_Id']; ?>);" class="label label-danger"><i class="fa fa-trash"></i> Remove</a></td> 
							</tr>
                           <?php }  ?>
                            </tbody>
							
                        </table>
                    </div>
					<div id="reloadPartner"></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
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
                        <h5><i class="fa fa-calendar"></i> Add new care partners</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                                <div class="form-group"><label class="col-sm-2 control-label">Select Hospital <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose Hospital..." class="chosen-select" name="selectHosp"  tabindex="2">
											<option value="" selected>Select Hospital</option>
												<?php
												$GetHosp = mysqlSelect("*", "hosp_tab", "company_id='".$admin_id."'", "hosp_name asc", "", "", "");
												foreach ($GetHosp as $HospList) {
												?>
													<option value="<?php echo $HospList["hosp_id"];	?>"><?php echo $HospList["hosp_name"].", ".$HospList["hosp_city"].", ".$HospList["hosp_state"];	?></option>
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Select Marketing Person </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose Marketing Person..." class="chosen-select" name="selectPerson" tabindex="2">
											<option value="" selected>---Please Select---</option>
												<?php
												$GetPerson = mysqlSelect("a.person_id as Person_Id,a.person_name as Person_Name,b.hosp_name as Hosp_Name,b.hosp_city as Hosp_City", "hosp_marketing_person as a left join hosp_tab as b on a.hosp_id=b.hosp_id", "b.company_id='".$admin_id."'", "a.person_name asc", "", "", "");
												foreach ($GetPerson as $PersonList) {
												?>
													<option value="<?php echo $PersonList["Person_Id"];	?>"><?php echo $PersonList["Person_Name"].", ".$PersonList["Hosp_Name"].", ".$PersonList["Hosp_City"];	?></option>
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Name <span class="required">*</span></label>

                                     <div class="col-sm-10"><input type="text" name="refPartName" required="required" class="form-control"></div>
                                
								</div>	
                                <div class="form-group">
									<label class="col-sm-2 control-label">Mobile No. <span class="required">*</span></label>

                                     <div class="col-sm-10"><input type="text" name="refPartMobile" required="required" class="form-control" placeholder="10 digit mobile no." maxlength="10" minlength="10"></div>
                                
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Email Id </label>

                                    <div class="col-sm-10"><input type="email" name="refPartEmail" required="required" class="form-control"></div>
                                
								</div>
								<div class="form-group">
								<label class="col-sm-2 control-label">Partner Category <span class="required">*</span></label>
                                      <div class="col-sm-10">  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="Doctor" name="partner_cat" checked="">
                                            <label for="inlineRadio1"> Doctor </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="Non-Doctor" name="partner_cat">
                                            <label for="inlineRadio2"> Non-Doctor </label>
                                        </div>
										</div>
								</div>
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="add_referrer" class="btn btn-primary block full-width m-b ">ADD</button>
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
