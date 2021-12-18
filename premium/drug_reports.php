<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
$secretary_id = $_SESSION['secretary_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);
include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}

date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d H:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$curYear 	= date('Y');
$curMonth 	= date('M');
require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(!isset($_GET['start'])) 
{
	$start = 0;
}
else
{
	$start = $_GET['start'];
}

$eu = ($start - 0); 
$limit = 50;         // No of records to be shown per page.
$this1 = $eu + $limit; 
$back = $eu - $limit; 
$next = $eu + $limit;
if(isset($_POST['cmdSearch']))
{
	$allRecord = mysqlSelect("DISTINCT(pp_id) as product_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."' and (prescription_trade_name LIKE '%".$_POST['search']."%' or prescription_generic_name LIKE '%".$_POST['search']."%')","episode_prescription_id desc","","","$eu, $limit");
	$pag_result = mysqlSelect("DISTINCT(pp_id) as product_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."' and (prescription_trade_name LIEK '%".$_POST['search']."%' or prescription_generic_name LIKE '%".$_POST['search']."%')");
	$total_prescribed = mysqlSelect("pp_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."' and (prescription_trade_name LIKE '%".$_POST['search']."%' or prescription_generic_name LIKE '%".$_POST['search']."%')");

	$nume = count($pag_result);

}
else
{	
	$allRecord = mysqlSelect("DISTINCT(pp_id) as product_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."'","episode_prescription_id desc","","","$eu, $limit");
	$pag_result = mysqlSelect("DISTINCT(pp_id) as product_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."'");
	$total_prescribed = mysqlSelect("pp_id","doc_patient_episode_prescriptions","doc_id='".$admin_id."'");

	$nume = count($pag_result);  
}
				

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Drug Reports</title>

   <?php include_once('support.php'); ?>
		<!-- FooTable -->
    <link href="../assets/css/plugins/footable/footable.core.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<!-- orris -->
    <link href="../assets/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/jsTree/style.min.css" rel="stylesheet">
	

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript">
$(function() 
{
 $( "#coding_language" ).autocomplete({
  source: 'get_pincode.php'
 });
});

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    //just one dot
    if(number.length>1 && charCode == 46){
         return false;
    }
    //get the carat position
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if( caratPos > dotPos && dotPos>-1 && (number[1].length > 1)){
        return false;
    }
    return true;
}

function getSelectionStart(o) {
	if (o.createTextRange) {
		var r = document.selection.createRange().duplicate()
		r.moveEnd('character', o.value.length)
		if (r.text == '') return o.value.length
		return o.value.lastIndexOf(r.text)
	} else return o.selectionStart
}
</script>

</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
		<div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12 mgTop">
					<div class="search-form">
						<form method="post" autocomplete="off">
						<input type="hidden" name="curURI" value="My-Patient-Details" />
							<div class="input-group">
		
							   <input type="text" id="serPatient" placeholder="Search medicine..." name="search" value="" class="form-control input-lg typeahead_1">
								<div class="input-group-btn">
									<button class="btn btn-lg btn-primary m-r" name="cmdSearch" type="submit">
										<i class="fa fa-search"></i> Search
									</button>&nbsp;&nbsp;&nbsp;
									
									
								</div>
							</div>

						</form>
                    </div>  
				</div>
		</div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
				 <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><i class="fa fa-plus-square"></i> <?php if(isset($_POST['cmdSearch'])){ echo "Search Medicine"; } else { echo "Recently Given Medicine"; } ?></h5>
                       <span class="text-navy pull-right">Total Prescribed Medicine: <?php echo count($total_prescribed); ?></span>
                    </div>
                    <div class="ibox-content">
					 
					<div id="allPatient">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Generic</th>                               
								<th>Company</th>
								<th colspan="2">Tot.Prescription</th>
                               	
                            </tr>
                            </thead>
                            <tbody>
							 <?php foreach($allRecord as $list){ 
							 $getCount = mysqlSelect("COUNT(pp_id) as count","doc_patient_episode_prescriptions","pp_id='".$list['product_id']."'");
							 $getMedName = mysqlSelect("pharma_brand,pharma_generic,company","pharma_products","pp_id='".$list['product_id']."'");
							 		
								if($getMedName==true){
									$brand_name = $getMedName[0]['pharma_brand'];
									$genric_name = $getMedName[0]['pharma_generic'];
									$company = $getMedName[0]['company'];
								}
								else{
									$getMedName1 = mysqlSelect("prescription_trade_name,prescription_trade_name","doc_patient_episode_prescriptions","pp_id='".$list['product_id']."'");
							     	$brand_name = $getMedName1[0]['prescription_trade_name'];
									$genric_name = $getMedName1[0]['prescription_trade_name'];
									$company = "";	
								}
							 
							 ?>
										
										
                           <tr>
                               <td><?php echo $brand_name;  ?></td>
								 <td><?php echo $genric_name;  ?></td>
                                <td><?php echo $company;  ?></td>
                                <td style="text-align:center;" class="text-navy"><?php echo $getCount[0]['count']; ?>
								
								</td>
								<td><a href="#" onclick="return getPatientList(<?= $list['product_id']; ?>);">VIEW PATIENT</a></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
						</div>
						
						<?php if($nume>=$limit){ ?>
						<div class="row">
					 
					<?php  
				if($nume>=500){
					$tot_num=500;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				
				if($back >=0){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $back; ?>' class='btn btn-white'><i class='fa fa-chevron-left'></i></a> 
				<?php }else{
				 
				}
				
				$i=0;
				$l=1;
				
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){ ?>
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $i; ?>'><button class='btn btn-white' ><?php echo $l; ?></button></a>
				<?php	} else { ?>
				<button class='btn btn-white active' ><?php echo  $l; ?></button>
				<?php	}
					$l=$l+1;
				}
				
				if($this1 < $nume) { ?>					
				<a href='<?php echo $_SERVER['PHP_SELF'];?>?start=<?php echo $next; ?>' class='btn btn-white'><i class='fa fa-chevron-right'></i></button></a>
				<?php } else 
				{
				
				}
				?>
				
                    </div>
						<?php } ?>
					
				
                    </div>
                </div>
            </div>
			<div id="dispPatient"></div>
               <!-- <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Patient Visit List</h5>
                        <span class="text-navy pull-right">Total Patient Visits: <?php echo count($total_visits); ?></span>
                    </div>
                    <div class="ibox-content">
					  
				
                    </div>
                </div>
            </div>-->
           
            </div>
                       
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>

<!-- Sweet alert -->
	<script src="../assets/js/plugins/sweetalert/sweetalert.min.js"></script>
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
	
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
   <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>

	<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
		function getPatientList(prdid)
			{
				console.log(prdid);
				$.ajax({
				url:"get_drug_patient_list.php",
				method:"POST",
				data:'drug_id='+prdid,
				success:function(data)
				{
					$('#dispPatient').html(data);	
				}
				})
			}
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

	
   <!-- <script>
        $(document).ready(function(){
		<?php 
	$get_PatientDetails = mysqlSelect("a.patient_id as patient_id,a.patient_name as patient_name,a.patient_mobile as patient_mob","patients_appointment as a inner join patients_transactions as b on a.patient_id=b.patient_id","a.doc_id='".$admin_id."'","","","","");
											
	?>
            $('.typeahead_1').typeahead({
               source: [<?php foreach($get_PatientDetails as $listPat){ echo "'".$listPat['patient_id']."-".$listPat['patient_name']."-".$listPat['patient_mob']."',"; }?>]
            });
			          

        });
		
    </script>-->
	
<script language="JavaScript" src="js/status_validationJs.js"></script>
</body>

</html>
