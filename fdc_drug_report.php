<?php ob_start();
 error_reporting(0);
 session_start();

 $admin_id = $_SESSION['admin_id'];
 $Company_id=$_SESSION['comp_id'];
 $user_flag = $_SESSION['flag_id'];

 date_default_timezone_set('Asia/Kolkata');
 $Assign_Date=date('Y-m-d h:i:s');
$Cur_Date=date('d-m-Y h:i:s');
$cur_Date=date('Y-m-d',strtotime($Cur_Date));
$add_days = 2;
$Follow_Date = date('d-m-Y',strtotime($cur_Date) - (24*3600*$add_days));

if(empty($admin_id)){
header("Location:index.php");
}

require_once("classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
 

//Display by Capture, Refer,Respond,&Close Buttons	
		
			if(!isset($_GET['start'])) {
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 100;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
$allRecord = $objQuery->mysqlSelect("pp_id as product_id,ref_name,episode_id","doc_patient_episode_prescriptions as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","episode_prescription_id desc","","","$eu, $limit");													
$pag_result = $objQuery->mysqlSelect("pp_id as product_id","doc_patient_episode_prescriptions as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'");
$total_prescribed = $objQuery->mysqlSelect("pp_id","doc_patient_episode_prescriptions as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'");

$nume = count($pag_result);  

				
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Practice Tracker</title>

	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
	
	<script type="text/javascript" src="premium/js/jquery.js"></script>
<script type="text/javascript" src="premium/js/jquery-ui.js"></script>
<link rel="stylesheet" href="premium/css/jquery-ui.css">

<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">


	<style>

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}

.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9; 
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}

</style>
</head>

<body class="top-navigation">
	
    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a href="#" class="navbar-brand">Practice</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
              
                <ul class="nav navbar-top-links navbar-right">
					
                    <li>
                        <a href="logout.php">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
		<script language="javaScript" src="js/validation1.js"></script>

					
					<div class="row">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                   <div class="ibox-title">
                                <div class="ibox-tools">
								  <form  action="export_data.php" method="post" name="upload_excel" enctype="multipart/form-data">
												<button type="submit" name="ExportPrescription" class="btn btn-success " ><i class="fa fa-download" aria-hidden="true"></i> EXPORT</button>
									</form>
								   </div>
                            </div>
                                    <div class="ibox-content">

                                        <div class="row">
                                            <div class="col-lg-12" id="before-status">
																				
											 <table class="table table-striped table-bordered table-hover dataTables-example">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Doctor Name</th>
                                                        <th class="text-center">Patient Id</th>
														<th class="text-center">Brand</th>
														<th class="text-center">Generic</th>
														<th class="text-center">Visit Date</th>
														<th class="text-center">Company</th>
                                                        
                                                    </tr>
                                                    </thead>
                                                    <tbody>
													
													<?php
													foreach($allRecord as $recrdList){
													$getEpisode = $objQuery->mysqlSelect("patient_id,date_time","doc_patient_episodes","episode_id='".$recrdList['episode_id']."'","","","","");													
													$getMedName = $objQuery->mysqlSelect("pharma_brand,pharma_generic,company","pharma_products","pp_id='".$recrdList['product_id']."'");
							 		
													if($getMedName==true){
														$brand_name = $getMedName[0]['pharma_brand'];
														$genric_name = $getMedName[0]['pharma_generic'];
														$company = $getMedName[0]['company'];
													}
													else{
														$getMedName1 = $objQuery->mysqlSelect("prescription_trade_name,prescription_trade_name","doc_patient_episode_prescriptions","pp_id='".$recrdList['product_id']."'");
														$brand_name = $getMedName1[0]['prescription_trade_name'];
														$genric_name = $getMedName1[0]['prescription_trade_name'];
														$company = "";	
													}
													?>
                                                    <tr>
													
                                                     <td class="text-left small">
														<?php echo $recrdList['ref_name']; ?>
														
														</td>
                                                        <td class="text-left small">
														 
														<?php  
														echo $getEpisode[0]['patient_id'];?>    
														</td>
														<td class="text-left small">
														 <?php echo $brand_name;  ?> 
														</td>
														<td class="text-left small">
														  <?php echo $genric_name;  ?>
														</td>
														<td class="text-left small">
														  <?php  
														echo date('M d Y',strtotime($getEpisode[0]['date_time']));?> 
														</td>
														<td class="text-left small">
														  <?php echo $company;  ?>
														</td>
                                                        
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
                            </div>
                        </div>
						
        <div class="footer">
            
            <div>
                <strong>Copyright @</strong> Medisense Healthcare Solutions Pvt. Ltd.
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="assets/js/jquery-3.1.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

		
		$('input:radio').change(function() {
			// alert('ole');
			window.location.href="View-Customization-Request?type=1"
		});
	
        });
		
    </script>
	 <!-- Flot -->
    <script src="assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="assets/js/plugins/flot/jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="assets/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="assets/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="assets/js/inspinia.js"></script>
    <script src="assets/js/plugins/pace/pace.min.js"></script>

	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
 <!-- d3 and c3 charts -->
    <script src="assets/js/plugins/d3/d3.min.js"></script>
    <script src="assets/js/plugins/c3/c3.min.js"></script>
	<!-- Chartist -->
    <script src="assets/js/plugins/chartist/chartist.min.js"></script>
	 <!-- ChartJS-->
    <script src="assets/js/plugins/chartJs/Chart.min.js"></script>
	 <!-- Sparkline -->
    <script src="assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>
    <script>

        $(document).ready(function () {

            
            c3.generate({
                bindto: '#gauge',
                data:{
                    columns: [
                        ['Response Rate', <?php echo $_SESSION['response_rate']; ?>]
                    ],

                    type: 'gauge'
                },
                color:{
                    pattern: ['#1ab394', '#BABABA']

                }
            });
			
			var barData = {
        labels: [<?php for( $i = 8; $i >= 0 ; $i--) { echo "'".date("M Y", strtotime( date( 'Y-m-01' )." -$i months"))."',"; }?>],
        datasets: [
            {
                label: "FDC Prescriptions",
                backgroundColor: '#fc89ac',
				borderColor: "#f1326d",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 8; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Responded = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as Total_count","doc_patient_episode_prescriptions as a left join pharma_products as b on a.pp_id=b.pp_id","b.company LIKE '%FDC LTD%' and (a.prescription_date_time between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Responded[0]['Total_count'].", "; }?>]
            },
			{
                label: "All Prescriptions",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 8; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Received = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as Total_count","doc_patient_episode_prescriptions as a left join pharma_products as b on a.pp_id=b.pp_id","b.company NOT LIKE '%FDC LTD%' and a.prescription_date_time between '".$startdate."' and '".$enddate."'","","","","");
				echo $Total_Received[0]['Total_count'].", "; }?>]
            }
            
        ]
    };

    var lineOptions = {
        responsive: true
    };


    var ctx2 = document.getElementById("lineChart1").getContext("2d");
    new Chart(ctx2, {type: 'line', data: barData, options:lineOptions});


        });

    
	
	$(function () {
   
    $("#sparkline8").sparkline([<?php for( $i = 8; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Responded = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as Total_count","doc_patient_episode_prescriptions as a left join pharma_products as b on a.pp_id=b.pp_id","b.company LIKE '%FDC LTD%' and (a.prescription_date_time between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Responded[0]['Total_count'].", "; }?>], {
        type: 'bar',
        barWidth: 8,
        height: '80px',
        barColor: '#1ab394',
        negBarColor: '#c6c6c6'});

  
});

</script>
	<script src="js/validation.js"></script>
</body>

</html>
