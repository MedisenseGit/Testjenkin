<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");

include('functions.php');
$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}

//Top Responded doctors
$Top_responded_doc= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.Tot_responded as Tot_Responded,a.Total_Referred as TotalReferred,d.hosp_name as Hopital_Name,a.doc_spec as Specialization","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","0,20");
$Top_responded_doc_min= mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.Tot_responded as Tot_Responded,a.Total_Referred as TotalReferred","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","0,6");
$Top_responded_hosp= mysqlSelect("DISTINCT(d.hosp_id) as Hosp_Id","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","");
$Get_Hosp= mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hopital_Name","hosp_tab","company_id='".$_SESSION['user_id']."'","","","","");
$get_Specialization=mysqlSelect("*","specialization","","spec_name asc","","","");

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Data Analytics</title>

    <?php include_once('support.php'); ?>
	
    <!-- Toastr style -->
    <link href="../assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">

    <!-- Gritter -->
    <link href="../assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
	<!-- c3 Charts -->
    <link href="../assets/css/plugins/c3/c3.min.css" rel="stylesheet">
 <link href="../assets/css/plugins/chartist/chartist.min.css" rel="stylesheet">
   

</head>

<body>
    <div id="wrapper">
        <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg dashbard-1">
			<?php include_once('header_top.php'); ?>
                <div class="row  border-bottom white-bg dashboard-header">

                    <div class="col-md-3">
                        <h2>Data Analytics</h2>
                        <h4>
                            Top care Partners List
                        </h4>
                        <ul class="list-group clear-list m-t">
						<li class="list-group-item fist-item">
                                <span class="pull-right">
                                    <b>Cases Sent</b>
                                </span>
                                <span><b>Seq.</b></span> 
								<span><b>Partner Name</b></span>
                            </li>
						<?php $getCarePart=mysqlSelect("DISTINCT(a.patient_src) as Pat_Src","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$admin_id."'","","","","");
						$i=1;
						
						foreach($getCarePart as $srcList){ 
						$getCarePartName=mysqlSelect("source_name","source_list","source_id='".$srcList['Pat_Src']."'","","","","");
						$countCases=mysqlSelect("COUNT(DISTINCT(a.patient_id)) as CountCase","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$admin_id."' and a.patient_src='".$srcList['Pat_Src']."'","","","","");
						if($i<6){
						?>
                            <li class="list-group-item fist-item">
                                <span class="pull-right">
                                   <?php echo $countCases[0]['CountCase']; ?>
                                </span>
                                <span class="label label-success"><?php echo $i;?></span> <?php echo $getCarePartName[0]['source_name']; ?>
                            </li>
						<?php } $i++;  
						}?>
                           <!-- <li class="list-group-item">
                                <span class="pull-right">
                                    10:16 am
                                </span>
                                <span class="label label-info">2</span> Sign a contract
                            </li>
                            <li class="list-group-item">
                                <span class="pull-right">
                                    08:22 pm
                                </span>
                                <span class="label label-primary">3</span> Open new shop
                            </li>
                            <li class="list-group-item">
                                <span class="pull-right">
                                    11:06 pm
                                </span>
                                <span class="label label-default">4</span> Call back to Sylvia
                            </li>
                            <li class="list-group-item">
                                <span class="pull-right">
                                    12:00 am
                                </span>
                                <span class="label label-primary">5</span> Write a letter to Sandra
                            </li>-->
                        </ul>
                    </div>
                    <div class="col-md-6">
										<div>
											<canvas id="barChart" height="130"></canvas>
										</div>
                        <div class="row text-left">
                            <div class="col-xs-4">
                                <div class=" m-l-md">
                                <span class="h4 font-bold m-t block"><?php echo $_SESSION['tot_result_count']; ?></span>
                                <small class="text-muted m-b block">Total Cases Received</small>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <span class="h4 font-bold m-t block"><?php echo $_SESSION['responded_count']; ?></span>
                                <small class="text-muted m-b block">Total Responded</small>
                            </div>
                            <div class="col-xs-4">
                                <span class="h4 font-bold m-t block"><?php echo con_min_days($_SESSION['response_time']); ?></span>
                                <small class="text-muted m-b block">Avgerage Response Time</small>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="statistic-box">
                        <h4>
                            Response Rate
                        </h4>
                        <p>
                            Here you can get average response rate. It's based on No. cases received devided by No. of responded
                        </p>
                            <div class="row text-center">
                                <div class="col-lg-12">
                                    
										<div>
											<div id="gauge"></div>
										</div>
									
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>
					
					<br><br>
					
					
                                    <div class="ibox-content">

                                        <div class="row">
                                            <div class="col-lg-6">
												<h4>
													Top Responded Doctors
												</h4>
                                                <table class="table table-hover margin bottom">
                                                    <thead>
                                                    <tr>
                                                        <th>Doctor</th>
														<th style="width:50px;">Referred</th>
														<th style="width:50px;">Responded</th>
                                                        <th class="text-center">Hospital Unit</th>
                                                        <th class="text-center">Response Rate</th>
														<th class="text-center">Avg. Response Time</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
													<?php foreach($Top_responded_doc as $TopList){ 
														 $Total_Referred_Doc = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
														 $Total_Responded_Doc = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$TopList['Ref_Id']."' and b.response_status=2","","","","");

														 $response_rate=floor(($Total_Responded_Doc[0]['Total_count']/($Total_Referred_Doc[0]['Total_count']))*100);
														 
														$Totresponsetime_Doc= mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
														$Countresponsetime_Doc= mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
														$response_time_Doc=floor($Totresponsetime_Doc[0]['Tot_response_time']/$Countresponsetime_Doc[0]['Count_Response_Time']);
														
														
														//Conversion Rete
														$countConverted_Doc= mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
														$conversion_rate_Doc=floor(($countConverted_Doc[0]['Total_count']*100)/$Total_Responded_Doc[0]['Total_count']);
														$getDocDept=mysqlSelect("spec_name as Spec_name","specialization","spec_id='".$TopList['Specialization']."'","","","","");
														
													?>
                                                    <tr>
                                                       
                                                        <td><?php echo $TopList['Ref_Name'].", <small>".$getDocDept[0]['Spec_name']; ?></small></td>	
													<td><?php echo $Total_Referred_Doc[0]['Total_count']; ?></td>
													<td><?php echo $Total_Responded_Doc[0]['Total_count']; ?></td>
													<td><small><?php echo $TopList['Hopital_Name']; ?></small></td>											
													<td><span class="label label-primary"><?php echo $response_rate."%"; ?></span></td>
													<td><small><?php echo con_min_days($response_time_Doc); ?></small></td>
                                                    </tr>
													<?php } ?>
                                                    <!--<tr>
                                                        <td class="text-center">2</td>
                                                        <td> Wardrobes
                                                        </td>
                                                        <td class="text-center small">10 Jun 2014</td>
                                                        <td class="text-center"><span class="label label-primary">$327.00</span></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">3</td>
                                                        <td> Set of tools
                                                        </td>
                                                        <td class="text-center small">12 Jun 2014</td>
                                                        <td class="text-center"><span class="label label-warning">$125.00</span></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">4</td>
                                                        <td> Panoramic pictures</td>
                                                        <td class="text-center small">22 Jun 2013</td>
                                                        <td class="text-center"><span class="label label-primary">$344.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">5</td>
                                                        <td>Phones</td>
                                                        <td class="text-center small">24 Jun 2013</td>
                                                        <td class="text-center"><span class="label label-primary">$235.00</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center">6</td>
                                                        <td>Monitors</td>
                                                        <td class="text-center small">26 Jun 2013</td>
                                                        <td class="text-center"><span class="label label-primary">$100.00</span></td>
                                                    </tr>-->
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-lg-6">
                                                <div>
											<canvas id="barChart3" height="500" width="600"></canvas>
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

    <!-- Flot -->
    <script src="../assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="../assets/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/s/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="../assets/js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- GITTER -->
    <script src="../assets/js/plugins/gritter/jquery.gritter.min.js"></script>

    <!-- Sparkline -->
    <script src="../assets/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="../assets/js/demo/sparkline-demo.js"></script>

    <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Toastr -->
    <script src="../assets/js/plugins/toastr/toastr.min.js"></script>
	<!-- d3 and c3 charts -->
    <script src="../assets/js/plugins/d3/d3.min.js"></script>
    <script src="../assets/js/plugins/c3/c3.min.js"></script>
	<!-- Chartist -->
    <script src="../assets/js/plugins/chartist/chartist.min.js"></script>
	 <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>
  
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
        labels: [<?php for( $i = 5; $i >= 0 ; $i--) { echo "'".date("M Y", strtotime("-".$i." month"))."',"; }?>],
        datasets: [
            {
                label: "Cases Received",
                backgroundColor: '#fc89ac',
				borderColor: "#f1326d",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 5; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Received = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Received[0]['Total_count'].", "; }?>]
            },
            {
                label: "Responded",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: [<?php for( $i = 5; $i >= 0 ; $i--) { 
				$startdate=date("Y-m-01", strtotime("-".$i." month"));
				$enddate=date("Y-m-31", strtotime("-".$i." month"));
				$Total_Responded = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (d.company_id='".$_SESSION['user_id']."') and (b.timestamp between '".$startdate."' and '".$enddate."')","","","","");
				echo $Total_Responded[0]['Total_count'].", "; }?>]
            }
        ]
    };
	
	var docData = {
        labels: [<?php foreach($Top_responded_doc_min as $TopList){ echo "'".$TopList['Ref_Name']."',"; }?>],
        datasets: [
            {
                label: "Cases Referred",
                backgroundColor: '#fc89ac',
				borderColor: "#f1326d",
                pointBorderColor: "#fff",
                data: [<?php foreach($Top_responded_doc_min as $TopList){ 
				$Total_Referred_Doc = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
				echo $Total_Referred_Doc[0]['Total_count'].", "; }?>]
            },
            {
                label: "Cases Responded",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: [<?php foreach($Top_responded_doc_min as $TopList){ 
				$Total_Responded_Doc = mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$TopList['Ref_Id']."' and b.response_status=2","","","","");
				echo $Total_Responded_Doc[0]['Total_count'].", "; }?>]
            }
        ]
    };

    var barOptions = {
        responsive: true
    };


    var ctx2 = document.getElementById("barChart").getContext("2d");
	 var ctx3 = document.getElementById("barChart3").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
	new Chart(ctx3, {type: 'bar', data: docData, options:barOptions});


        });

    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 4000
                };
                toastr.success('Here you can view all data analytics', 'Data Analytics');

            }, 1300);


            var data1 = [
                [0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,30],[11,10],[12,13],[13,4],[14,3],[15,3],[16,6]
            ];
            var data2 = [
                [0,1],[1,0],[2,2],[3,0],[4,1],[5,3],[6,1],[7,5],[8,2],[9,3],[10,2],[11,1],[12,0],[13,2],[14,8],[15,0],[16,0]
            ];
            $("#flot-dashboard-chart").length && $.plot($("#flot-dashboard-chart"), [
                data1, data2
            ],
                    {
                        series: {
                            lines: {
                                show: false,
                                fill: true
                            },
                            splines: {
                                show: true,
                                tension: 0.4,
                                lineWidth: 1,
                                fill: 0.4
                            },
                            points: {
                                radius: 0,
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,
                            tickColor: "#d5d5d5",
                            borderWidth: 1,
                            color: '#d5d5d5'
                        },
                        colors: ["#1ab394", "#1C84C6"],
                        xaxis:{
                        },
                        yaxis: {
                            ticks: 4
                        },
                        tooltip: false
                    }
            );

            var doughnutData = {
                labels: ["App","Software","Laptop" ],
                datasets: [{
                    data: [300,50,100],
                    backgroundColor: ["#a3e1d4","#dedede","#9CC3DA"]
                }]
            } ;


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var ctx4 = document.getElementById("doughnutChart").getContext("2d");
            new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});

            var doughnutData = {
                labels: ["App","Software","Laptop" ],
                datasets: [{
                    data: [70,27,85],
                    backgroundColor: ["#a3e1d4","#dedede","#9CC3DA"]
                }]
            } ;


            var doughnutOptions = {
                responsive: false,
                legend: {
                    display: false
                }
            };


            var ctx4 = document.getElementById("doughnutChart2").getContext("2d");
            new Chart(ctx4, {type: 'doughnut', data: doughnutData, options:doughnutOptions});

        });
    </script>
</body>
</html>
