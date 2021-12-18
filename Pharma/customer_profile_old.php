<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];

include('functions.php');
if(empty($admin_id)){
	header("Location:index.php");
}
require_once("../classes/querymaker.class.php");

					
	$patient_tab = mysqlSelect("*","pharma_customer","md5(pharma_customer_id)='".$_GET['p']."'","","","","");
	if($patient_tab[0]['pharma_cust_gender']=="1"){
		$gender="Male";
	}
	else if($patient_tab[0]['pharma_cust_gender']=="2"){
		$gender="Female";
	}
	$cust_episode = mysqlSelect("*","pharma_referrals","patient_id='".$patient_tab[0]['patient_id']."'","pr_id DESC ","","","");

	$patient_id = $patient_tab[0]['pharma_customer_id'];

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Patient Profile</title>

   <?php include_once('support.php'); ?>
	<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">

<script language="JavaScript" src="js/status_validationJs.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: 'get_icd.php'
 });
});
</script>
</head>

<body>

    <div id="wrapper">

   
         <?php include_once('sidemenu.php'); ?>
    

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-3">
                    <h2>Customer Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        <li class="active">
                            <strong>Customer Profile</strong>
                        </li>
                    </ol>
                </div>
				 <div class="col-lg-7 mgTop">
					<div class="search-form">
                                <form action="add_details.php" method="post">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search Customer" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>            
			   </div>
                <!--<div class="col-lg-2 mgTop">
					<a href="My-Patients"> <button type="button" class="btn btn-w-m btn-success"><i class="fa fa-arrow-left"></i> BACK</button></a>
                                
			   </div>-->
            </div>
			<div class="row m-t">
				<div class="col-md-4">

                    <div class="profile-image">
                        <img src="../assets/img/anonymous-profile.png" class="img-circle circle-border m-b-md" alt="profile">
                    </div>
                    <div class="profile-info">
                        <div class="">
                            <div>
                                <h2 class="no-margins">
                                    <?php echo $patient_tab[0]['pharma_customer_name']; ?>
                                </h2>
                                <h4><i class="fa fa-mobile"></i> <?php echo $patient_tab[0]['pharma_customer_phone']; ?></h4>
								 <h4><i class="fa fa-stack-exchange"></i> <?php echo $patient_tab[0]['pharma_customer_email']; ?></h4>
                                <small>
                                    
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <table class="table small m-b-xs">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Gender</strong> <?php echo $gender; ?>
                            </td>
						</tr>
						<tr>	
                            <td>
                                <strong>Age</strong> <?php echo $patient_tab[0]['pharma_cust_age']; ?>
                            </td>

                        </tr>
						<tr>
							<td>
                                <strong>Address</strong> <?php echo $patient_tab[0]['pharma_cust_address'].", ".$patient_tab[0]['pharma_cust_city'].", ".$patient_tab[0]['pharma_cust_state']; ?>
                            </td>
						
						</tr>
                       
                        </tbody>
                    </table>
                </div>
			
			</div>
			<div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>All episode details</h5>
                            <!--<div class="ibox-tools">
                                <a href="" class="btn btn-primary btn-xs">Create new episode</a>
                            </div>-->
                        </div>
						<?php $i=1; while(list($key_episode, $value_episode) = each($cust_episode)) { ?>
                        <div class="ibox-content">
                           <form method="post" >
                            <div class="project-list">

                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <td class="project-status">
                                            <span class="label label-primary">Episode <?php echo $i; ?></span>
                                        </td>
                                        <td class="project-title">
                                           <i class="fa fa-calendar"></i> Referred on
                                            <br/>
                                            <small><?php echo date('d M Y',strtotime($value_episode['referred_date'])); ?></small>
                                        </td>
                                        <td class="project-completion">
                                            Status
											<br/>
											<span class="label label-danger">Pending</span>
                                               
                                        </td>
                                      
                                        <td class="project-actions">
                                            <a href="javascript:void(0);" data-toggle="collapse" data-target="#demo<?php echo $i;?>" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> View </a>
                                           <!-- <a href="#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> Edit </a>-->
                                        </td>
                                    </tr>
									<tr>
									<td colspan="4">
									<div id="demo<?php echo $i;?>" class="collapse col-lg-12">
											
								<div id="dispMedTable"></div>
								<br>
									<table id="medicine-grid" cellpadding="2" cellspacing="2" border="1" class="table table-bordered" width="90%">
																			<thead>
																				<th>Medicine</th>
																				<th>Generic Name</th>
																				<th>Qty</th>
																				<th>Price</th>
																				<th>Discount</th>
																				<th>Total</th>
																				<th>Delete</th>
																			</thead>
																			<tbody>
																			<?php 
																			$getPrescription= mysqlSelect("*","doc_patient_episode_prescriptions","episode_id='".$value_episode['episode_id']."'","prescription_trade_name asc","","","");
												
																			while(list($key_presc, $value_presc) = each($getPrescription)){?>
																			<tr>
																				<td><?php echo $value_presc['prescription_trade_name']; ?></td>
																				<td><?php echo $value_presc['prescription_generic_name']; ?></td>
																				<td><input type="number" class="oceanIn" name="prescription_qty[<?php echo $key_presc; ?>][val_qty]" id="" value="" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_price[]" id="" value="20.89" placeholder="" required style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_discount[]" id="" value="0.25" placeholder="" style="width:100px;"></td>
																				<td><input type="text" class="tagName" name="prescription_total[]" id="" value="20.64" placeholder="" style="width:100px;"></td>
																				<td><a href="javascript:void(0)" onclick="return deleteMedicine(<?php echo $value_presc['episode_prescription_id'];?>,<?php echo $value_episode['episode_id'];?>);"><span class="label label-danger">Delete</span></a></td>																			
																			</tr>
																			<?php } ?>
																			</tbody>
																			
																		</table>
																		<div class="input-group">
				
                                       <input type="text" id="coding_language" placeholder="Add Medicine here..." onchange="return addMedicine(this.value,<?php echo $patient_tab[0]['patient_id']; ?>,<?php echo $getPrescription[0]['episode_id']; ?>,<?php echo $value_episode['doc_id']; ?>);" name="search" value="" class="form-control input-lg">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="button">
                                                ADD
                                            </button>
                                        </div>
                                    </div>
								<div class="row">
										<div class="col-lg-5 pull-right m-t">
											<div class="form-group"><label class="col-lg-4 control-label">Less Discount(%)</label>

												<div class="col-lg-6"><input type="email" placeholder="Eg.2%" class="form-control"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-5 pull-right m-t">
											<div class="form-group"><label class="col-lg-4 control-label">Total invoice(Rs.)</label>

												<div class="col-lg-6"><input type="email" id="oceanTotal" placeholder="" class="form-control"></div>
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-lg-2 pull-right m-t">
									 <button class="btn btn-primary" type="submit">Save Invoice</button>
									  
								</div>
								</div>
									</td>
									</tr>
									
                                    </tbody>
                                </table>
                            </div>
                        </div>
						</form>
						<?php $i++; } ?>
						
						
                    </div>
                </div>
            </div>
        </div>
		<?php include_once('footer.php'); ?>
        </div>
        </div>
	
	
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>
	 <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();
            $('.footable2').footable();

        });

    </script>
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../assets/js/custom.min.js"></script>


<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});

	$(document).ready(function() {
	$(".oceanIn").keyup(function() {
  	var total = 0.0;
    $.each($(".oceanIn"), function(key, input) {
      if(input.value && !isNaN(input.value)) {
        total += parseFloat(input.value);
      }
    });
    $("#oceanTotal").html("Total: " + total);
  });
});

    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
			
			$('#dateadded1').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	 <!-- Typehead -->
    <script src="../assets/js/plugins/typehead/bootstrap3-typeahead.min.js"></script>

	<script>
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("pharma_customer_name,pharma_customer_phone","pharma_customer","pharma_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['pharma_customer_name']."-".$listPat['pharma_customer_phone']."',"; }?>]
            });

            

        });
    </script>

</body>

</html>
