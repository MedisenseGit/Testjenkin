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


$marketPerson = mysqlSelect("a.person_id as Person_Id,a.person_name as Person_Name,a.person_mobile as Person_Mobile,a.person_email as Person_Email,b.hosp_name as Hosp_Name,b.hosp_city as Hosp_City,b.hosp_state as Hosp_State","hosp_marketing_person as a inner join hosp_tab as b on a.hosp_id=b.hosp_id","b.hosp_id='".$admin_id."'","a.person_id desc","","","");

                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Marketing Persons</title>

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
                    <h2>Marketing Persons</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Marketing Persons</strong>
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
                        <h5>Marketing Persons List</h5>
                        
                    </div>
                    <div class="ibox-content table-responsive">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                               <th style="width:200px;">Name</th>
								<th style="width:200px;">Hospital</th>
								<th style="width:50px;">Edit</th>
							</tr>
                            </thead>
                            <tbody>
						<?php if(empty($marketPerson)) { ?><tr>
                                            <td colspan="5"><center>No result found</center></td>
                                        </tr> <?php } ?>
									<?php foreach($marketPerson as $list){ ?>	
				
                            <tr>
                                 <td><i class="fa fa-user"></i> <?php echo $list['Person_Name'];  ?><br>
											<i class="fa fa-envelope"></i> <?php echo $list['Person_Email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list['Person_Mobile'];  ?>
											</td> 
											
											<td><?php echo $list['Hosp_Name'].", ".$list['Hosp_City'];  ?></td> 
                                       <td><center> <a href="Edit-Marketing-Persons?person_id=<?php echo $list['Person_Id']; ?>" class="btn btn-white btn-bitbucket">
                 <i class="fa fa-edit"></i></a></center></td> 
                            </tr>
                           <?php }  ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins" id="addMarketSection">
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
                        <h5><i class="fa fa-calendar"></i> Add new marketing professional</h5>
                       
                    </div>
                    <div class="ibox-content">

                        <div class="panel-body">
                                    <form enctype="multipart/form-data" method="post" class="form-horizontal" action="add_details.php"  name="frmAddPatient" >
                                <div class="form-group"><label class="col-sm-2 control-label">Select Hospital <span class="required">*</span></label>

                                    <div class="col-sm-10"><select data-placeholder="Choose Hospital..." class="chosen-select" name="selectHosp"  tabindex="2">
											<option value="" selected>Select Hospital</option>
												<?php
												$GetHosp = mysqlSelect("*", "hosp_tab", "hosp_id='".$admin_id."'", "hosp_name asc", "", "", "");
												foreach ($GetHosp as $HospList) {
												?>
													<option value="<?php echo $HospList["hosp_id"];	?>"><?php echo $HospList["hosp_name"].", ".$HospList["hosp_city"].", ".$HospList["hosp_state"];	?></option>
												<?php
												}
												?>
										</select>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Name <span class="required">*</span></label>

                                     <div class="col-sm-10"><input type="text" name="person_name" required="required" class="form-control"></div>
                                
								</div>	
                                <div class="form-group">
									<label class="col-sm-2 control-label">Mobile No. <span class="required">*</span></label>

                                     <div class="col-sm-10"><input type="text" name="person_mobile" required="required" placeholder="10 digit mobile no." class="form-control" maxlength="10" minlength="10"></div>
                                
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Email Id <span class="required">*</span></label>

                                    <div class="col-sm-10"><input type="email" name="person_email" required="required" class="form-control"></div>
                                
								</div>
								
								
								<div class="form-group">
								<div class="col-sm-6 pull-right">
								<button type="submit" name="add_person" class="btn btn-primary block full-width m-b ">ADD</button>
								</div>
								</div>
							</form>
							</div>
                    </div>
                </div>
				
				<!-- EDIT HOSPITAL SECTION -->
				<div id="editMarketContent"></div> 
				
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
