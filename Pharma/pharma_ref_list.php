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
			

//SEARCH Patient
if(isset($_POST['cmdSearch'])){
	$disp=0;
	$params     = split(" ", $_POST['search']);
	$searchid = $params[0];
	
	 	
		$allRecord = mysqlSelect("*","pharma_customer","pharma_id='".$admin_id."' and pharma_customer_id ='".$searchid."'","pharma_customer_id desc","","","$eu, $limit");
		$pag_result = mysqlSelect("pharma_customer_id","pharma_customer","pharma_id='".$admin_id."' and pharma_customer_id ='".$searchid."'","pharma_customer_id desc");
		$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
		$arrPage = explode("-",$pageing);
		
		if(COUNT($allRecord)==0){
		//Clear all temp. details from 'pharma_temp_medicine_invoice' table
		mysqlDelete('pharma_temp_medicine_invoice',"pharma_id='".$admin_id."'");
		$disp=0;
		}else{
		$disp=1;
		header('Location:Customer_Profile_Info?p='.md5($allRecord[0]['pharma_customer_id']));	
		}
		
} 
else if(!isset($_POST['cmdSearch']))
{
	
	$allRecord = mysqlSelect("*","pharma_customer","pharma_id='".$admin_id."'","pharma_customer_id desc","","","$eu, $limit");

$pag_result = mysqlSelect("pharma_customer_id","pharma_customer","pharma_id='".$admin_id."'","pharma_customer_id desc");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
 $disp=1;
}

	
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Referrals</title>

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
                <div class="col-lg-10">
                    <h2>Referrals</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Referrals</strong>
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
                        <h5>Referral List</h5>
                        
                    </div>
                    <div class="ibox-content">
					<div class="search-form">
                                <form method="post" autocomplete="off">
                                    <div class="input-group">
				
                                       <input type="text" placeholder="Search /Add New Customer" name="search"  value="" class="form-control input-lg typeahead_1">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="submit">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </form>
                    </div>
					
					<?php if($disp==1) { ?>	
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Ref. Date</th>                               
								<th>Contact Details</th>
								<th>Ref. By</th>
								<th>Status</th>
                               	
                            </tr>
                            </thead>
                            <tbody>
							 <?php 
							 
								foreach($allRecord as $list){ 
								  
								$getRefDet = mysqlSelect("*","pharma_referrals","patient_id='".$list['patient_id']."'","","","",""); 
								
								if($getRefDet==true && $getRefDet[0]['doc_type']=="1")
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
								}
							?>
									
										
                            <a href="Home"><tr>
                               <td><a href="Customer_Profile_Info?p=<?php echo md5($list['pharma_customer_id']); ?>"><?php echo $list['pharma_customer_name'];  ?></a></td>
								 <td><?php echo date('M d, Y',strtotime($getRefDet[0]['referred_date']));  ?></td>
                                <td><i class="fa fa-envelope"></i> <?php echo $list['pharma_customer_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list['pharma_customer_phone'];  ?></td>
                                <td> <?php echo $refBy;  ?></td>
								<td><span class="label label-danger">Pending</span></td>
                            </tr></a>
                            <?php } ?>
                            </tbody>
                        </table>
					<?php } 
					else if($disp==0){
					?>
					<br>
					
					<h3>Add New Customer</h3>
					<div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                       
                        <div class="ibox-content">
                         
                            <div class="row">
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Name
                                    </p>
									
                                    <div class="form-group"><input type="text" placeholder="Enter Name" value="<?php echo $searchid; ?>" class="form-control"required></div>
                                </div>
                                <div class="col-md-2">
                                    <p class="font-bold">
                                       Age
                                    </p>
                                   <div class="form-group"><input type="text" placeholder="Age" class="form-control"></div>
                                </div>
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Gender
                                    </p>
                                  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="option1" name="radioInline" required>
                                            <label for="inlineRadio1" > Male </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="inlineRadio1" value="option2" name="radioInline" required>
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
                                </div>
                            </div>
							
							<div class="row">
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        Mobile No.
                                    </p>
									
                                    <div class="form-group"><input type="text" placeholder="10 digit mobile no." class="form-control"maxlength="10" required></div>
                                </div>
                                <div class="col-md-4">
                                    <p class="font-bold">
                                       Email Address
                                    </p>
                                   <div class="form-group"><input type="email" placeholder="Email address" class="form-control"></div>
                                </div>
                                <div class="col-md-4">

                                    <p class="font-bold">
                                        City
                                    </p>
                                  <div class="form-group"><input type="text" placeholder="City" class="form-control" required></div>
                                </div>
                            </div>
							<div class="row">
							<div class="col-md-12">
							<p class="font-bold">
                                        Add Medicine here...
                                    </p>
							<div class="input-group">
										
                                       <input type="text" id="coding_language" placeholder="Add Medicine here..." onchange="return addTempMedicine(this.value);" name="search" value="" class="form-control input-lg" autocomplete="off">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" name="cmdSearch" type="button">
                                                ADD
                                            </button>
                                        </div>
                                    </div>
									<br>
									<div id="dispMedTable"></div>
								</div>
								
								
								
							</div>
							<br><br>
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
						
                    </div>
                </div>
            </div>	
					<?php } ?>
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
	$get_PatientDetails = mysqlSelect("pharma_customer_id,pharma_customer_name,pharma_customer_phone","pharma_customer","pharma_id='".$admin_id."'","","","","");
	
	?>
            $('.typeahead_1').typeahead({
                source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['pharma_customer_id']." ".$listPat['pharma_customer_name']." ".$listPat['pharma_customer_phone']."',"; }?>]
            });

            

        });
    </script>
</body>

</html>
