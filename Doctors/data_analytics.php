<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:user-login");
}
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

date_default_timezone_set('Asia/Kolkata');
$curDate=date('Y-m-d h:i:s');


//Top Responded doctors
$Top_responded_doc= $objQuery->mysqlSelect("a.ref_id as Ref_Id,a.ref_name as Ref_Name,a.Tot_responded as Tot_Responded,a.Total_Referred as TotalReferred,d.hosp_name as Hopital_Name,a.doc_spec as Specialization","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","");
$Top_responded_hosp= $objQuery->mysqlSelect("DISTINCT(d.hosp_id) as Hosp_Id","referal as a inner join doctor_hosp as c on c.doc_id=a.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.company_id='".$_SESSION['user_id']."' and a.Total_Referred!=0","a.Tot_responded desc","","","");
$Get_Hosp= $objQuery->mysqlSelect("hosp_id as Hosp_Id,hosp_name as Hopital_Name","hosp_tab","company_id='".$_SESSION['user_id']."'","","","","");
$get_Specialization=$objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
					



//Convert Minutes to Days HH:MM Format functionality
 function con_min_days($mins)
    {

            $hours = str_pad(floor($mins /60),2,"0",STR_PAD_LEFT);
            $mins  = str_pad($mins %60,2,"0",STR_PAD_LEFT);

            if((int)$hours > 24){
            $days = str_pad(floor($hours /24),2,"0",STR_PAD_LEFT);
            $hours = str_pad($hours %24,2,"0",STR_PAD_LEFT);
            }
            if(isset($days)) { $days = $days." Day[s] ";}

            return $days.$hours." Hour[s] ".$mins." Min[s]";
    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Analytics</title>

    <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

	 <!-- Datatables -->
    <link href="../Hospital/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	
    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	
	
	<script type="text/javascript">
		window.onload = function () {
			var chart = new CanvasJS.Chart("chartContainer", {
				title: {
					text: "Refer & Response Rate",
					fontSize: 26
				},
				animationEnabled: true,
				axisX: {
					gridColor: "Silver",
					tickColor: "silver",
					valueFormatString: "MMM"
				},
				toolTip: {
					shared: true
				},
				theme: "theme2",
				axisY: {
					gridColor: "Silver",
					tickColor: "silver"
				},
				legend: {
					verticalAlign: "center",
					horizontalAlign: "right"
				},
				data: [
				{
					type: "line",
					showInLegend: true,
					lineThickness: 2,
					name: "No.referred",
					markerType: "square",
					color: "#F08080",
					dataPoints: [
					{ x: new Date(2017, 5, 0), y: <?php echo $_SESSION['Total_Referred_May']; ?> },
					{ x: new Date(2017, 4, 0), y: <?php echo $_SESSION['Total_Referred_Apr']; ?> },
					{ x: new Date(2017, 3, 0), y: <?php echo $_SESSION['Total_Referred_Mar']; ?> },
					{ x: new Date(2017, 2, 0), y: <?php echo $_SESSION['Total_Referred_Feb']; ?> },
					{ x: new Date(2017, 1, 0), y: <?php echo $_SESSION['Total_Referred_Jan']; ?> },
					{ x: new Date(2017, 0, 0), y: <?php echo $_SESSION['Total_Referred_Dec']; ?> }
					
					
					
					
					
					]
				},
				{
					type: "line",
					showInLegend: true,
					name: "No.responded",
					color: "#20B2AA",
					lineThickness: 2,

					dataPoints: [
					{ x: new Date(2017, 5, 0), y: <?php echo $_SESSION['Total_Responded_May']; ?> },
					{ x: new Date(2017, 4, 0), y: <?php echo $_SESSION['Total_Responded_Apr']; ?> },
					{ x: new Date(2017, 3, 0), y: <?php echo $_SESSION['Total_Responded_Mar']; ?> },
					{ x: new Date(2017, 2, 0), y: <?php echo $_SESSION['Total_Responded_Feb']; ?> },
					{ x: new Date(2017, 1, 0), y: <?php echo $_SESSION['Total_Responded_Jan']; ?> },
					{ x: new Date(2017, 0, 0), y: <?php echo $_SESSION['Total_Responded_Dec']; ?> }
					
					
					
									
					]
				}
				],
				legend: {
					cursor: "pointer",
					itemclick: function (e) {
						if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
							e.dataSeries.visible = false;
						}
						else {
							e.dataSeries.visible = true;
						}
						chart.render();
					}
				}
			});

			chart.render();
		}
	</script>
	
	
	
	<script src="../Hospital/canvasjs.min.js"></script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
       <!--Side Menu & Top Navigation -->
        <?php include_once('side_menu.php'); ?>


        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           <!-- top tiles -->
          <div class="row tile_count">
            
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="My-Patient-List"> <span class="count_top"><i class="fa fa-stethoscope"></i> <b>MY PRACTICE</b></span><br>
			 <span class="count_top"><i class="fa fa-user"></i> Patients</span>
              <div class="count"><?php echo $countMyPatient[0]['Total_count'];; ?></div></a>
			  <a href="Appointments"><span class="count_bottom"><i class="red"><?php echo $Total_Appointment_Count[0]['count']; ?> </i> Appointments </a>
              <!--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>-->
            </a>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="Responded-Records"><span class="count_top"><i class="fa fa-code-fork"></i> <b>MY NETWORK</b></span>
              <br><span class="count_top"><i class="fa fa-user"></i> Total Referrals</span>
			  <div class="count"><?php echo $_SESSION['all_record']; ?></div></a>
              <a href="Responded-Records"><span class="count_bottom"><i class="green"><?php echo $_SESSION['tot_resp_count']; ?> </i> Responded  &</a><br><a href="Not-Responded"> <i class="red"><?php echo $_SESSION['not_resp_count']; ?></i> not responded</span></a>
            
			</div>
			
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
             <a href="All-Records"> <span class="count_top"><b> VISIT FROM NETWORK</b></span>
              <div class="count green"><?php echo $_SESSION['tot_converted_count']; ?></div>
              <span class="count_bottom"><i class="green"><?php echo $_SESSION['conversion_rate']; ?>% </i> Visit rate</span>
            </div>
			<div class="col-md-2 col-sm-2 col-xs-6 tile_stats_count">
              <a href="Converted-Records"><span class="count_top"><b> RESPONSE RATE</b></span>
              <div class="count"><?php echo $_SESSION['response_rate']; ?>%</div>
             
            </a>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-clock-o"></i> AVG. RESPONSE TIME</span><br><br>
              <div class="green count_bottom"><b><?php echo con_min_days($_SESSION['response_time']); ?></b></div>
              
            </div>
                     
            
           <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
             <a href="Blogs-List"> <span class="count_top"><b>BLOGS, EVENTS, OFFERS</b></span>
              <div class="count"><b><?php echo $totalBlogs[0]['Count_Blogs']; ?></b></div></a>
              
            </div>
          </div>
          <!-- /top tiles -->

            <div class="row" style="max-height:600px;">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Refer & Response Rate <small>Monthly trend</small>
					</h2>
                    
					
					
					<div class="filter">
					<span class="col-md-3">
				   <select class="form-control" name="slctStatus">
				   <option value="" selected>Referring Partner</option>
					
				   <option value="">Medisense Health</option>
				
				   </select>
                     
                    </span>
					
					<span class="col-md-3">
				   <select class="form-control" name="slctStatus">
				   <option value="" selected>Unit</option>
					<?php foreach($Get_Hosp as $hosplist) { ?>
				   <option value="<?php echo $hosplist['Hosp_Id'];?>"><?php echo $hosplist['Hopital_Name']; ?></option>
					<?php } ?>
				   </select>
                     
                    </span>
                      <div id="" class="col-md-5" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span>Feb 06, 2017 - May 06, 2017</span> <b class="caret"></b>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                      <div class="demo-container" >
                        <!--<div id="chart_plot_02" class="demo-placeholder"></div>-->
						<table id="" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th style="width:200px;"></th>
											<th style="width:100px;">Total Referred</th>
											<th style="width:100px;">Total Responded</th>
                                            <th style="width:100px;">Response Rate</th>
											<th style="width:200px;">Avg. Response Time</th>
                                            <th style="width:100px;">Provisional Visit Rate</th>
                                           
                        </tr>
                      </thead>


                      <tbody>
					 <tr class="odd gradeY">
											<td>May 2017</td>
                                            <td><?php echo $_SESSION['Total_Referred_May']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_May']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_May']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_May']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_May'])){ echo $_SESSION['conversion_rate_May']."%";} else { echo "0%"; } ?></td>
											
							</tr>
						<tr class="odd gradeY">
											<td>Apr 2017</td>
                                            <td><?php echo $_SESSION['Total_Referred_Apr']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_Apr']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_Apr']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_Apr']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_Apr'])){ echo $_SESSION['conversion_rate_Apr']."%";} else { echo "0%"; } ?></td>
											
						</tr>
					  <tr class="odd gradeY">
											<td>Mar 2017</td>
                                            <td><?php echo $_SESSION['Total_Referred_Mar']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_Mar']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_Mar']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_Mar']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_Mar'])){ echo $_SESSION['conversion_rate_Mar']."%"; } else { echo "0%"; } ?></td>
											
							</tr>
						<tr class="odd gradeY">
											<td>Feb 2017</td>
                                            <td><?php echo $_SESSION['Total_Referred_Feb']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_Feb']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_Feb']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_Feb']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_Feb'])){ echo $_SESSION['conversion_rate_Feb']."%"; } else { echo "0%"; } ?></td>
											
							</tr>
						<tr class="odd gradeY">
											<td>Jan 2017</td>
                                            <td><?php echo $_SESSION['Total_Referred_Jan']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_Jan']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_Jan']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_Jan']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_Jan'])){ echo $_SESSION['conversion_rate_Jan']."%"; } else { echo "0%"; } ?></td>
											
							</tr>
						<tr class="odd gradeY">
											<td>Dec 2016</td>
                                            <td><?php echo $_SESSION['Total_Referred_Dec']; ?></td> 
											<td><?php echo $_SESSION['Total_Responded_Dec']; ?></td>
                                            <td><?php echo $_SESSION['response_rate_Dec']."%"; ?></td>
											<td><?php echo con_min_days($_SESSION['response_time_Dec']); ?></td>
											<td><?php if(!empty($_SESSION['conversion_rate_Dec'])){ echo $_SESSION['conversion_rate_Dec']."%"; } else { echo "0%"; } ?></td>
											
							</tr>
						
						</table>
						
                      </div>
                      

                    </div>
					<div class="col-md-6 col-sm-12 col-xs-12">
					<div id="chartContainer" style="height: 400px; width: 100%;">
                    </div>
					
					<div class="clearfix"></div>	
                  </div>
                </div>
              </div>
			  
			  
			  <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top responded doctors</h2>
                    <div class="filter">
					<span class="col-md-6">
				   <select class="form-control" name="slctStatus">
				   <option value="" selected>Select Unit</option>
					<?php foreach($Get_Hosp as $hosplist) { ?>
				   <option value="<?php echo $hosplist['Hosp_Id'];?>"><?php echo $hosplist['Hopital_Name']; ?></option>
					<?php } ?>
				   </select>
                     
                    </span>
					<span class="col-md-6">
				   <select class="form-control" name="slctStatus">
				   <option value="" selected>Select Specialization</option>
					<?php foreach($get_Specialization as $speclist) { ?>
				   <option value="<?php echo $speclist['spec_id'];?>"><?php echo $speclist['spec_name']; ?></option>
					<?php } ?>
				   </select>
                     
                    </span>
                      
                    </div>
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="demo-container" >
                        <!--<div id="chart_plot_02" class="demo-placeholder"></div>-->
						<table id="datatable-responsive" class="table table-striped table-bordered">
                      <thead>
                        <tr>
											<th style="width:50px;">No.Referred</th>
											<th style="width:50px;">No.Responded</th>
											<th style="width:200px;">Top Responded Doctor</th>
											<th style="width:200px;">Hospital Unit</th>
											<th style="width:100px;">Response Rate</th>
											<th style="width:200px;">Avg. Response Time</th>
                                            <th style="width:100px;">Provisional visit rate</th>
                                           
                        </tr>
                      </thead>


                      <tbody>
					 <?php foreach($Top_responded_doc as $TopList){ 
					 $Total_Referred_Doc = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.company_id='".$_SESSION['user_id']."') and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
					 $Total_Responded_Doc = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","b.ref_id='".$TopList['Ref_Id']."' and b.response_status=2","","","","");

					 $response_rate=floor(($Total_Responded_Doc[0]['Total_count']/($Total_Referred_Doc[0]['Total_count']))*100);
					 
					$Totresponsetime_Doc= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
					$Countresponsetime_Doc= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
					$response_time_Doc=floor($Totresponsetime_Doc[0]['Tot_response_time']/$Countresponsetime_Doc[0]['Count_Response_Time']);
					
					
					//Conversion Rete
					$countConverted_Doc= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (b.ref_id='".$TopList['Ref_Id']."')","","","","");
					$conversion_rate_Doc=floor(($countConverted_Doc[0]['Total_count']*100)/$Total_Responded_Doc[0]['Total_count']);
					$getDocDept=$objQuery->mysqlSelect("spec_name as Spec_name","specialization","spec_id='".$TopList['Specialization']."'","","","","");
					
					?>
					  <tr class="odd gradeY">
											<td><?php echo $Total_Referred_Doc[0]['Total_count']; ?></td>
											<td><?php echo $Total_Responded_Doc[0]['Total_count']; ?></td>
											<td><?php echo $TopList['Ref_Name'].", ".$getDocDept[0]['Spec_name']; ?></td>	
											<td><?php echo $TopList['Hopital_Name']; ?></td>											
                                            <td><?php echo $response_rate."%"; ?></td>
											<td><?php echo con_min_days($response_time_Doc); ?></td>
											<td><?php if(!empty($conversion_rate_Doc)){ echo $conversion_rate_Doc."%"; } else { echo "0%"; } ?></td>
											
							</tr>
					 <?php } ?>
						</table>
						
                      </div>
                     
                    </div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<iframe src="Bar_Chart_Top_Responded_doctor.php" height="320" width="800" style="border:none;"></iframe>
					</div>
					<div class="clearfix"></div>	
                  </div>
                </div>
              </div>
			  
			  
			 <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top responded Hospital</h2>
                   
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="demo-container" >
                        <!--<div id="chart_plot_02" class="demo-placeholder"></div>-->
						<table id="datatable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
											<th style="width:50px;">No.Referred</th>
											<th style="width:50px;">No.Responded</th>
											<th style="width:200px;">Hospital Unit</th>
											<th style="width:100px;">Response Rate</th>
											<th style="width:200px;">Avg. Response Time</th>
                                            <th style="width:100px;">Provisional visit rate</th>
                                           
                        </tr>
                      </thead>


                      <tbody>
					 <?php foreach($Top_responded_hosp as $TopList){ 
					 $get_Hosp = $objQuery->mysqlSelect("hosp_name as Hosp_Name","hosp_tab","hosp_id='".$TopList['Hosp_Id']."'","","","","");
					 $Total_Referred_Hosp = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(d.hosp_id='".$TopList['Hosp_Id']."')","","","","");
					 $Total_Responded_Hosp = $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","d.hosp_id='".$TopList['Hosp_Id']."' and b.response_status=2","","","","");

					 $response_rate_hosp=floor(($Total_Responded_Hosp[0]['Total_count']/($Total_Referred_Hosp[0]['Total_count']))*100);
					 
					$Totresponsetime_Hosp= $objQuery->mysqlSelect("SUM(b.response_time) as Tot_response_time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.hosp_id='".$TopList['Hosp_Id']."')","","","","");
					$Countresponsetime_Hosp= $objQuery->mysqlSelect("COUNT(a.patient_id) as Count_Response_Time","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.response_status=2) and (b.response_time!=0) and (d.hosp_id='".$TopList['Hosp_Id']."')","","","","");
					$response_time_Hosp=floor($Totresponsetime_Hosp[0]['Tot_response_time']/$Countresponsetime_Hosp[0]['Count_Response_Time']);
					
					
					//Conversion Rete
					$countConverted_Hosp= $objQuery->mysqlSelect("COUNT(a.patient_id) as Total_count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.status2=8 or b.status2=7 or b.status2=9 or b.status2=11 or b.status2=12 or b.status2=13) and (d.hosp_id='".$TopList['Hosp_Id']."')","","","","");
					$conversion_rate_Hosp=floor(($countConverted_Hosp[0]['Total_count']*100)/$Total_Responded_Hosp[0]['Total_count']);
					
					?>
					  <tr class="odd gradeY">
											<td><?php echo $Total_Referred_Hosp[0]['Total_count']; ?></td>
											<td><?php echo $Total_Responded_Hosp[0]['Total_count']; ?></td>
											<td><?php echo $get_Hosp[0]['Hosp_Name']; ?></td>											
                                            <td><?php echo $response_rate_hosp."%"; ?></td>
											<td><?php echo con_min_days($response_time_Hosp); ?></td>
											<td><?php if(!empty($conversion_rate_Hosp)){ echo $conversion_rate_Hosp."%"; } else { echo "0%"; } ?></td>
											
							</tr>
					 <?php } ?>
						</table>
						
                      </div>
                     
                    </div>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<iframe src="Bar_Chart_Top_Responded_Hospital.php" height="320" width="800" style="border:none;"></iframe>
					</div>
					<div class="clearfix"></div>	
                  </div>
                </div>
              </div> 
			  
			  
			  
			  
            </div>



          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
           Powered by <a href="https://medisensehealth.com">Medisense Healthcare Pvt Ltd</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../Hospital/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Hospital/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Hospital/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Hospital/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="../Hospital/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- jQuery Sparklines -->
    <script src="../Hospital/vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- Flot -->
    <script src="../Hospital/vendors/Flot/jquery.flot.js"></script>
    <script src="../Hospital/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="../Hospital/vendors/Flot/jquery.flot.time.js"></script>
    <script src="../Hospital/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="../Hospital/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="../Hospital/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="../Hospital/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="../Hospital/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="../Hospital/vendors/DateJS/build/date.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../Hospital/vendors/moment/min/moment.min.js"></script>
    <script src="../Hospital/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    
	<!-- Datatables -->
    <script src="../Hospital/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../Hospital/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../Hospital/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
	
    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>
	
	
  </body>
</html>