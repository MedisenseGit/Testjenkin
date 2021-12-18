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

			/*if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 50;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
//SEARCH Patient
if(isset($_POST['cmdSearch'])){
	$disp=0;
	$params     = split(" ", $_POST['search']);
	$searchid = $params[0];
	
	 	
		$allRecord = mysqlSelect("*","pharma_customer","pharma_id='".$admin_id."' and pharma_customer_id ='".$searchid."'","pharma_customer_id desc","pharma_customer_id","","$eu, $limit");
		$pag_result = mysqlSelect("pharma_customer_id","pharma_customer","pharma_id='".$admin_id."' and pharma_customer_id ='".$searchid."'","pharma_customer_id desc","pharma_customer_id");
		$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
		$arrPage = explode("-",$pageing);
		
		if(COUNT($allRecord)==0){
		//Clear all temp. details from 'pharma_temp_medicine_invoice' table
		mysqlDelete('diagnosis_temp_test_invoice',"diagnostic_id='".$admin_id."'");
		$disp=0;
		}else{
		$disp=1;
		header('Location:Customer_Profile_Info?p='.md5($allRecord[0]['pharma_customer_id']));	
		}
		
} 
else if(!isset($_POST['cmdSearch']))
{*/
	if (isset($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
        } else {
            $pageno = 1;
        }
	if (isset($_GET['pagenum'])) {
            $pageno1 = $_GET['pagenum'];
        } else {
            $pageno1 = 1;
        }
        $no_of_records_per_page = 30;
        $offset = ($pageno-1) * $no_of_records_per_page;
		$offset1 = ($pageno1-1) * $no_of_records_per_page;
			
			$TotalRecord = mysqlSelect("episode_id","pharma_referrals","pharma_id='".$admin_id."'","pharma_customer_id desc","episode_id","","");	
			$total_rows = count($TotalRecord);
			$total_pages = ceil($total_rows / $no_of_records_per_page);
			$allRecord = mysqlSelect("*","pharma_referrals","pharma_id='".$admin_id."'","pharma_customer_id desc","episode_id","","$offset, $no_of_records_per_page");
			$TotalRecord1 = mysqlSelect("*","health_pharma_request as a left join login_user as b on a.login_id=b.login_id","a.pharma_id='".$admin_id."'","a.id desc","","","");	
			$total_rows1 = count($TotalRecord1);
			$total_pages1 = ceil($total_rows1 / $no_of_records_per_page);
			$patientRecord = mysqlSelect("*","health_pharma_request as a left join login_user as b on a.login_id=b.login_id","a.pharma_id='".$admin_id."'","a.id desc","","","$offset1, $no_of_records_per_page");
	
	//$allRecord = mysqlSelect("*","pharma_referrals as a left join pharma_customer as b on a.pharma_customer_id=b.pharma_customer_id","a.pharma_id='".$admin_id."'","a.pharma_customer_id desc","a.pharma_customer_id","","$eu, $limit");
	//$patientRecord = mysqlSelect("*","health_pharma_request as a left join login_user as b on a.login_id=b.login_id","a.pharma_id='".$admin_id."'","a.id desc","","","$eu, $limit");

//$pag_result = mysqlSelect("a.pr_id","pharma_referrals as a left join pharma_customer as b on a.pharma_customer_id=b.pharma_customer_id","a.pharma_id='".$admin_id."'","a.pharma_customer_id desc","a.pharma_customer_id");
//$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
//$arrPage = explode("-",$pageing);   
 $disp=1;
//}
           
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Requests</title>

   <?php include_once('support.php'); ?>
<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<script language="JavaScript" src="js/status_validationJs.js"></script>
</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Requests</h2>
                    <ol class="breadcrumb">
                      <!--  <li>
                            <a href="Home">Home</a>
                        </li>-->
                        
                        <li class="active">
                            <strong>Requests</strong>
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
                        <h5>Requests From EMR</h5>
                        
                    </div>
                    <div class="ibox-content">
					<!--<div class="search-form">
                                <form method="post" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search /Add New Customer" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>-->
					<?php if($disp==1) { ?>	
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Ref. Date</th>                               
								<th>Contact Details</th>
								<th>Ref. By</th>
								<th>Status</th>
                               	<!--<th>Status</th>-->
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
									 $getPat = mysqlSelect("patient_name,patient_email,patient_mob","doc_my_patient","patient_id='".$list['patient_id']."'","","","",""); 
									//$getRefDet = mysqlSelect("*","pharma_referrals","patient_id='".$list['patient_id']."'","","","",""); 
								
								/*if($getRefDet==true && $getRefDet[0]['doc_type']=="1")
								{
									$getRefByDet = mysqlSelect("ref_name,doc_state,ref_address","referal","ref_id='".$getRefDet[0]['doc_id']."'","","","",""); 
									 $refBy=$getRefByDet[0]['ref_name'];
								} else if($getRefDet[0]['doc_type']=="2")
								{ 
									 $getRefByDet = mysqlSelect("partner_name,cont_num1,Email_id","our_partners","partner_id='".$getRefDet[0]['doc_id']."'","","","",""); 
										$refBy=$getRefByDet[0]['partner_name'];
								}
								else
								{
									$refBy="Self";
								}*/
								if($list['referred_by']== "1"){
									$getRefByDet = mysqlSelect("sub_name","login_user","login_id='".$list['login_id']."'","","","",""); 
									 $refBy='<i class="fa fa-user"></i>  '.$getRefByDet[0]['sub_name'];
								}
								else{
									$getRefByDet = mysqlSelect("ref_name,doc_state,ref_address","referal","ref_id='".$list['doc_id']."'","","","",""); 
									 $refBy= '<i class="fa fa-user-md"></i>  '.$getRefByDet[0]['ref_name'];
								}
							?>
									
										
                             <a href="Request"><tr>
                               <td><a href="Customer_Profile_Info?p=<?php echo md5($list['pr_id']); ?>"><?php echo $getPat[0]['patient_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($list['referred_date']));  ?></td>
                                <td><i class="fa fa-envelope"></i> <?php echo $getPat[0]['patient_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $getPat[0]['patient_mob'];  ?></td>
                                <td> <?php echo $refBy;  ?></td>
								<td><?php if($list['order_status'] == '2') { echo '<span class="label label-warning">Payment Link Sent</span>'; } 
									else if($list['order_status'] == '3') { echo '<span class="label label-info">Paid</span>'; }
									else if($list['order_status'] == '4') { echo '<span class="label label-success">Completed</span>'; }
									else { echo '<span class="label label-warning">Referred</span>'; } ?></td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
						<ul class="pagination">
        <li><a href="?pageno=1&pagenum=<?php echo $pageno1; ?>">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1).'&pagenum='.$pageno1; } ?>">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1).'&pagenum='.$pageno1; } ?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_pages; ?>&pagenum=<?php echo $pageno1; ?>">Last</a></li>
    </ul>
					<?php } else if($disp==0){
					?>
					<br>
					
					<h3>Add New Customer</h3>
					<div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                    <form method="post" name="frmSave" action="add_details.php">   
                        <div class="ibox-content">
                         
                            <div class="row">
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Name
                                    </p>
									
                                    <div class="form-group"><input type="text" placeholder="Enter Name" value="<?php echo $searchid; ?>" name="custName" class="form-control" required></div>
                                </div>
                                <div class="col-md-2">
                                    <p class="font-bold">
                                       Age
                                    </p>
                                   <div class="form-group"><input type="text" placeholder="Age" name="custAge" class="form-control"></div>
                                </div>
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Gender
                                    </p>
                                  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="radioInline" required>
                                            <label for="inlineRadio1" > Male </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio1" value="2" name="radioInline" required>
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
										<div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio1" value="3" name="radioInline" required>
                                            <label for="inlineRadio2"> Other</label>
                                        </div>
                                </div>
                            </div>
							
							<div class="row">
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Mobile No.
                                    </p>
									
                                    <div class="form-group"><input type="text" placeholder="10 digit mobile no." name="custMobile" class="form-control"maxlength="10" required></div>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-bold">
                                       Email Address
                                    </p>
                                   <div class="form-group"><input type="email" placeholder="Email address" name="custEmail" class="form-control"></div>
                                </div>
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        City
                                    </p>
                                  <div class="form-group"><input type="text" placeholder="City" name="custCity" class="form-control" required></div>
                                </div>
                            </div>
							
							<br><br>
							
								<div class="row">
								<div class="col-lg-2 pull-right m-t">
									 <button class="btn btn-primary" name="cmdSave" type="submit">Save</button>
									  
								</div>
                        </div>
						
                    </div>
					</form>
                </div>
            </div>
			</div>			
					<?php } ?>
                   
                    </div>
                </div>
            </div>
             <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Requests From Patient App</h5>
                        
                    </div>
                    <div class="ibox-content">
					<!--<div class="search-form">
                                <form method="post" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search /Add New Customer" name="search" value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>-->
					<?php if($disp==1) { ?>	
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Ref. Date</th>                               
								<th>Contact Details</th>
								<!--<th>Ref. By</th>-->
                               	<th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($patientRecord as $list1){ 
								
							?>
									
										
                            <a href="Home"><tr>
                               <td><a href="Patient_Profile_Info?p=<?php echo md5($list1['id']); ?>"><?php echo $list1['customer_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($list1['created_date']));  ?></td>
                                <td><i class="fa fa-envelope"></i> <?php echo $list1['customer_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list1['customer_mobile'];  ?></td>
                                <!--<td><i class="fa fa-user-md"></i> <?php echo $refBy;  ?></td>
								<td><span class="label label-danger">Pending</span></td>-->
								<td><?php if($list1['order_status'] == '2') { echo '<span class="label label-warning">Payment Link Sent</span>'; } 
									else if($list1['order_status'] == '3') { echo '<span class="label label-info">Paid</span>'; }
									else if($list1['order_status'] == '4') { echo '<span class="label label-success">Completed</span>'; }
									else { echo '<span class="label label-warning">Referred</span>'; } ?></td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
										<ul class="pagination">
        <li><a href="?pageno=<?php echo $pageno; ?>&pagenum=1">First</a></li>
        <li class="<?php if($pageno1 <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno1 <= 1){ echo '#'; } else { echo "?pageno=".$pageno."&pagenum=".($pageno1 - 1); } ?>">Prev</a>
        </li>
        <li class="<?php if($pageno1 >= $total_pages1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno1 >= $total_pages1){ echo '#'; } else { echo "?pageno=".$pageno."&pagenum=".($pageno1 + 1); } ?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $pageno; ?>&pagenum=<?php echo $total_pages1; ?>">Last</a></li>
    </ul>
					<?php }  ?>
                   
                    </div>
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
	$get_PatientDetails = mysqlSelect("diagnostic_customer_id,diagnostic_customer_name,diagnostic_customer_phone","diagnostic_customer","diagnostic_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['diagnostic_customer_id']." ".$listPat['diagnostic_customer_name']." ".$listPat['diagnostic_customer_phone']."',"; }?>]
            });

            

        });
    </script>
</body>

</html>
