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
		
			if(!isset($_GET['start'])) {  // This variable is set to zero for the first page
			$start = 0;
			}else{
			$start = $_GET['start'];
			}

			$eu = ($start - 0); 
			$limit = 100;         // No of records to be shown per page.
			$this1 = $eu + $limit; 
			$back = $eu - $limit; 
			$next = $eu + $limit;
			
			
			
			$_SESSION['type'] = $_GET['type'];
			//$busResult2 = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","a.num_visits desc","","","");	
			//$busResult3 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp,login_status,ref_mail,contact_num,ABM_Name,RBM_name","referal","sponsor_id='2'","ref_id desc","","","");	
			$busResult2 = $objQuery->mysqlSelect("*","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","a.num_visits desc","","","");	
			$busResult3 = $objQuery->mysqlSelect("ref_id,ref_name,doc_city,ref_address,TImestamp,login_status,ref_mail,contact_num,ABM_Name,RBM_name","referal","sponsor_id='2' or sponsor_id='3'","ref_id desc","","","");	
			
				
			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);		
		
$lastmonth = date("Y-m",strtotime("-1 month"));		
//echo $lastmonth;		
//$FDC_Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2'","","","");	
$FDC_Total_Rslt = $objQuery->mysqlSelect("COUNT(ref_id) as count","referal","sponsor_id='2' or sponsor_id='3'","","","");	

$FDC_Total_Product = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as count","doc_patient_episode_prescriptions as a left join pharma_products as b on a.pp_id=b.pp_id","b.company LIKE '%FDC LTD%'","","","");

$Last_month_FDC_Total_Product = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as count","doc_patient_episode_prescriptions as a left join pharma_products as b on a.pp_id=b.pp_id","b.company LIKE '%FDC LTD%' and DATE_FORMAT(a.prescription_date_time,'%Y-%m')='".$lastmonth."'","","","");

$Tot_Presc_Count = $objQuery->mysqlSelect("SUM(presc_count) as presc_Count","analytics_tab","","","","","");
//$Tot_Presc_Count = $objQuery->mysqlSelect("COUNT(a.episode_prescription_id) as presc_Count","doc_patient_episode_prescriptions as a inner join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","","","");			
									
//$FDC_active_user = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' and a.type='1'","","","");	
$FDC_active_user = $objQuery->mysqlSelect("COUNT(a.track_id) as count","practice_login_tracker as a left join referal as b on a.doc_id=b.ref_id","(b.sponsor_id='2' or b.sponsor_id='3') and a.type='1'","","","");	

$ChkInUsrName= $objQuery->mysqlSelect("*","chckin_user","cmpny_id='".$Company_id."' and user_status='ACTIVE'","","","","");
function firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2){
				$nume = count($pag_result);
				if($nume>=2600){
					$tot_num=2600;
				}else {
					$tot_num=count($pag_result);
				}
				$this1 = $eu + $limit; 
				$strPaging = " ";
				$strPaging.="<table width='100%' border='none' align = 'center'><tr><td  align='left' width='5%'>";
				if($back >=0){ 
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$back&field=".$field."&type=".$type2."'class=\"pagenav\"><font face='Verdana' size='8' color='#3c3e3e'>PREV</font></a>"; 
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>PREV</span></font>"; 
				}
				$strPaging.="</td><td align=center width='1200px' class='pagenav'><center>";
				$i=0;
				$l=1;
				$strPaging.="Page No.";
				for($i=0;$i < $tot_num;$i=$i+$limit){
					if($i <> $eu){
						$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$i&field=".$field."&type=".$type2."'class=\"pagenav1\" ><font face='Verdana' size='8' color='#3c3e3e' >$l</font></a>&nbsp;";
					}else{
						$strPaging.="<font face='Verdana' size='8' color='#ec042a' ><span class='pagenav1' ><b>$l</b></span></font>&nbsp;";
					}
					$l=$l+1;
				}
				$strPaging.="</center></td><td  align='right' width='5%'>";
				if($this1 < $nume) { 
					
					$strPaging.="<a href='$page_name?disp=$_GET[disp]&start=$next&field=".$field."&type=".$type2."'class=\"pagenav1\"><font face='Verdana' size='8' color='#3c3e3e'>NEXT</font></a>";
				}else{
					$strPaging.="<font face='Verdana' size='8' color='#3c3e3e'><span class='pagenav'>NEXT</span></font>";
				}

				$strPaging.="</td></tr></table>";
				return $strPaging."-".$nume;
}
			
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

<script type="text/javascript">
$(function() 
{
 $( "#medicine_name" ).autocomplete({
  source: 'premium/get_medicine_name.php'
 });
 $( "#generic_name" ).autocomplete({
  source: 'get_generic_name.php'
 });
 
 
});


</script>

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
					 <h5 class="float-right">Server Last Updated: 27 Aug 2020 10:42am</h5>
					 </li>
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

        <div class="wrapper wrapper-content">
           
            
              
				<div class="col-md-2">
				<div class="widget white-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_Total_Rslt[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total FDC Panelist
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget navy-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_active_user[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Active Users
                            </h3>
                           
                        </div>
                    </div>
				</div>
				
				<div class="col-md-2">
				<div class="widget red-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $Tot_Presc_Count[0]['presc_Count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                Total Prescriptions
                            </h3>
                           
                        </div>
                    </div>
				</div>
				<div class="col-md-2">
				<div class="widget yellow-bg p-lg text-center">
                        <div class="m-b-md">
                           
                            <h1 class="m-xs"><?php echo $FDC_Total_Product[0]['count']; ?></h1>
                            <h3 class="font-bold no-margins">
                                FDC Prescriptions
                            </h3>
                           
                        </div>
                    </div>
				</div>
				
            </div>
               
				
               
                            <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                               <!-- <h5>Orders</h5>
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-white active">Today</button>
                                        <button type="button" class="btn btn-xs btn-white">Monthly</button>
                                        <button type="button" class="btn btn-xs btn-white">Annual</button>
                                    </div>
                                </div>-->
								
							</div>
							
                            <div class="ibox-content">
                                <div class="row">
                                <div class="col-lg-9">
                                   <!-- <div class="flot-chart">
                                        <div class="flot-chart-content" id="flot-dashboard-chart"></div>
                                    </div>-->
									
									<div>
											<canvas id="lineChart1" height="100"></canvas>
										</div>
                                </div>
                                <div class="col-lg-3">
                                    <ul class="stat-list">
                                        <li>
                                            <h2 class="no-margins"><?php echo $Tot_Presc_Count[0]['presc_Count']; ?></h2>
                                            <small>Total Prescriptions</small>
                                            <div class="stat-percent">68% <i class="fa fa-level-up text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 48%;" class="progress-bar"></div>
                                            </div>
                                        </li>
										<li>
                                            <h2 class="no-margins "><?php echo $FDC_Total_Product[0]['count']; ?></h2>
                                            <small>Total FDC Prescriptions</small>
                                            <div class="stat-percent">30% <i class="fa fa-level-down text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 60%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <h2 class="no-margins "><?php echo $Last_month_FDC_Total_Product[0]['count']; ?></h2>
                                            <small>Total FDC Prescriptions in last month</small>
                                            <div class="stat-percent">10% <i class="fa fa-level-down text-navy"></i></div>
                                            <div class="progress progress-mini">
                                                <div style="width: 60%;" class="progress-bar"></div>
                                            </div>
                                        </li>
                                       
                                        </ul>
                                    </div>
                                </div>
                                </div>

                            </div>
                        </div>
                    </div>
					
					<div class="row">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                         <div class="row">
											<label class="control-label m-l" for="price">Competitor Analysis </label>
										</div>
                                        <div class="row">
                                        <!-- <div class="col-sm-4">
											<div class="form-group">
												<label class="control-label" for="product_name">Medicine Name <span class="required">*</span></label>
												<input type="text" id="medicine_name" name="medicine_name" value="" placeholder="Medicine Name" required class="form-control typeahead_1">
											</div>
										</div>-->
										<div class="col-sm-3">
											<div class="form-group">
												<label class="control-label" for="price">Generic Name <span class="required">*</span></label>
												<input type="text" id="generic_name" name="generic_name" value="" placeholder="Generic Name" required class="form-control">
											</div>
										</div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label class="control-label" for="quantity">Company</label>
                                                <input type="text" id="company" name="company" value="" placeholder="Search Company" class="form-control">
                                            </div>
                                        </div>
										<div class="col-sm-3">
                                            <div class="form-group">
                                                <button class="btn btn-danger btn-sm m-t search_analytic" data-placement="top" title="Search"><i class="fa fa-search"></i> Search</button>
												<button class="btn btn-danger btn-sm m-t search_cancel" data-placement="top" title="Cancel"><i class="fa fa-window-close"></i> Cancel</button>
                                            
											</div>
											
                                        </div>
										<div class="col-sm-2">
                                            <div class="form-group">
                                                <button class="btn btn-danger btn-sm m-t" data-placement="top" title="Search" onclick="location.href = '<?php echo HOST_MAIN_URL; ?>Drug-List';"> Drug List</button>
												
											</div>
											
                                        </div>
										<div class="col-sm-2">
                                            <div class="form-group">
                                                <button class="btn btn-danger btn-sm m-t" data-placement="top" title="Search" onclick="location.href = '<?php echo HOST_MAIN_URL; ?>Condition-Treated';"> List Of Conditions Treated</button>
												
											</div>
											
                                        </div>
                                        
                                    </div>
                                    </div>
                                    <div class="ibox-content">

                                        <div class="row">
                                            <div class="col-lg-12" id="before-status">
											<?php
											//$getDocId = $objQuery->mysqlSelect("DISTINCT(ref_address) as doc_city","doc_patient_episode_prescriptions as a inner join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2'","a.episode_prescription_id asc","","");	 
											$getDocId = $objQuery->mysqlSelect("DISTINCT(ref_address) as doc_city","doc_patient_episode_prescriptions as a inner join referal as b on a.doc_id=b.ref_id","b.sponsor_id='2' or b.sponsor_id='3'","a.episode_prescription_id asc","","");	 
										
											$getBrands = $objQuery->mysqlSelect("DISTINCT(b.pharma_brand) as pharma_brand","doc_patient_episode_prescriptions as a inner join pharma_products as b on a.pp_id=b.pp_id","","b.pharma_brand asc","","");	 
											
											$getHighestAllIndiaPrescDet = $objQuery->mysqlSelect("brand_name,generic_name,company,presc_count","analytics_tab","","presc_count desc","","");
													
											$getTotFDCPrescDet = $objQuery->mysqlSelect("SUM(presc_count) as tot_count","analytics_tab","company LIKE '%FDC LTD%'","","","");
											$getHighestFDCPrescDet = $objQuery->mysqlSelect("brand_name,generic_name,presc_count","analytics_tab","company LIKE '%FDC LTD%'","presc_count desc","","");
											?>
											<div class="col-lg-3">
											<small><b>Most Frequently Used FDC Prescription: </b><br><span class="text-navy"><?php echo "<b>Brand: </b>".$getHighestFDCPrescDet[0]['brand_name']."<br><b>Generic:</b>".$getHighestFDCPrescDet[0]['generic_name']."<br><b>Count:</b>".$getHighestFDCPrescDet[0]['presc_count'];?></span></small><br>
                                            </div>
											<div class="col-lg-2"> 
											<small><b>FDC growth rate</b><br>
											  <div>
                                                <span id="sparkline8"></span>
                                            </div>
											</div>
											<div class="col-lg-7">
											<small><b>Most Frequently Used Prescription:</b> <br>
											<?php echo "1. <b>Brand: </b>".$getHighestAllIndiaPrescDet[0]['brand_name']."- <b>Generic:</b>".$getHighestAllIndiaPrescDet[0]['generic_name']." - <b>Company:</b>".$getHighestAllIndiaPrescDet[0]['company']."- <b>Count:</b>".$getHighestAllIndiaPrescDet[0]['presc_count']."<br>2. <b>Brand: </b>".$getHighestAllIndiaPrescDet[1]['brand_name']."- <b>Generic:</b>".$getHighestAllIndiaPrescDet[1]['generic_name']." - <b>Company:</b>".$getHighestAllIndiaPrescDet[1]['company']."- <b>Count:</b>".$getHighestAllIndiaPrescDet[1]['presc_count']."<br>3. <b>Brand: </b>".$getHighestAllIndiaPrescDet[2]['brand_name']."- <b>Generic:</b>".$getHighestAllIndiaPrescDet[2]['generic_name']." - <b>Company:</b>".$getHighestAllIndiaPrescDet[2]['company']."- <b>Count:</b>".$getHighestAllIndiaPrescDet[2]['presc_count']."<br>4. <b>Brand: </b>".$getHighestAllIndiaPrescDet[3]['brand_name']."- <b>Generic:</b>".$getHighestAllIndiaPrescDet[3]['generic_name']." - <b>Company:</b>".$getHighestAllIndiaPrescDet[3]['company']."- <b>Count:</b>".$getHighestAllIndiaPrescDet[3]['presc_count']."<br>5. <b>Brand: </b>".$getHighestAllIndiaPrescDet[4]['brand_name']."- <b>Generic:</b>".$getHighestAllIndiaPrescDet[4]['generic_name']." - <b>Company:</b>".$getHighestAllIndiaPrescDet[4]['company']."- <b>Count:</b>".$getHighestAllIndiaPrescDet[4]['presc_count']."<br>"; ?></small> 
											 </div>
											
											 <table class="table table-striped table-bordered table-hover dataTables-example">
                                                    <thead>
                                                    <tr>
                                                        <th style="width: 1%" class="text-center">City</th>
                                                        <th class="text-center">FDC</th>
														<th class="text-center">Top1</th>
														<th class="text-center">Top2</th>
														<th class="text-center">Top3</th>
														<th class="text-center">Top4</th>
														<th class="text-center">Top5</th>
                                                        
                                                    </tr>
                                                    </thead>
                                                    <tbody>
													
													<tr><th class="text-center">All India<br><?php echo "<span class='small'><b>Tot.Presc: </b><span class='text-danger font-bold'>".$Tot_Presc_Count[0]['presc_Count']."</span></span>"; ?></th>
													<td class="text-left small">
													<?php 
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTotFDCPrescDet[0]['tot_count']."</span><br><br><b>Company:</b>FDC LTD<br><br><b>First Highest Sold: </b>".$getHighestFDCPrescDet[0]['brand_name']."( ".$getHighestFDCPrescDet[0]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDet[0]['presc_count']."<br><br><b>Second Highest Sold: </b>".$getHighestFDCPrescDet[1]['brand_name']."( ".$getHighestFDCPrescDet[1]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDet[1]['presc_count']."<br><br><b>Third Highest Sold: </b>".$getHighestFDCPrescDet[2]['brand_name']."( ".$getHighestFDCPrescDet[2]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDet[2]['presc_count'];
													?>
													</td>
													<td class="text-left small"><?php
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getHighestAllIndiaPrescDet[0]['presc_count']."</span><br><br><b>Company:</b>".$getHighestAllIndiaPrescDet[0]['company']."<br><br><b>Brand: </b>".$getHighestAllIndiaPrescDet[0]['brand_name']."<br><b>Generic:</b>".$getHighestAllIndiaPrescDet[0]['generic_name'];
													
													?></td>
													<td class="text-left small">
													<?php
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getHighestAllIndiaPrescDet[1]['presc_count']."</span><br><br><b>Company:</b>".$getHighestAllIndiaPrescDet[1]['company']."<br><br><b>Brand: </b>".$getHighestAllIndiaPrescDet[1]['brand_name']."<br><b>Generic:</b>".$getHighestAllIndiaPrescDet[1]['generic_name'];
													
													?>
													</td>
													<td class="text-left small">
													<?php
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getHighestAllIndiaPrescDet[2]['presc_count']."</span><br><br><b>Company:</b>".$getHighestAllIndiaPrescDet[2]['company']."<br><br><b>Brand: </b>".$getHighestAllIndiaPrescDet[2]['brand_name']."<br><b>Generic:</b>".$getHighestAllIndiaPrescDet[2]['generic_name'];
													
													?>
													</td>
													<td class="text-left small">
													<?php
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getHighestAllIndiaPrescDet[3]['presc_count']."</span><br><br><b>Company:</b>".$getHighestAllIndiaPrescDet[3]['company']."<br><br><b>Brand: </b>".$getHighestAllIndiaPrescDet[3]['brand_name']."<br><b>Generic:</b>".$getHighestAllIndiaPrescDet[3]['generic_name'];
													
													?>
													</td>
													<td class="text-left small">
													<?php
													echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getHighestAllIndiaPrescDet[4]['presc_count']."</span><br><br><b>Company:</b>".$getHighestAllIndiaPrescDet[4]['company']."<br><br><b>Brand: </b>".$getHighestAllIndiaPrescDet[4]['brand_name']."<br><b>Generic:</b>".$getHighestAllIndiaPrescDet[4]['generic_name'];
													
													?>
													</td>
													
													</tr>
													<?php 
													while(list($key, $val) = each($getDocId)){
													//$cities = $objQuery->mysqlSelect("ref_address","referal","ref_id='".$val['doc_id']."'","","","");	 
													$getTopPrescCity = $objQuery->mysqlSelect("brand_name,generic_name,company,presc_count","analytics_tab","city='".$val['doc_city']."'","presc_count desc","","");	 
													$getTotalPrescCity = $objQuery->mysqlSelect("SUM(presc_count) as tot_presc_count","analytics_tab","city='".$val['doc_city']."'","","","");	 
													
													?>
                                                    <tr>
													
                                                        <th class="text-center"><?= $val['doc_city'];?><br><?php echo "<span class='small'><b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTotalPrescCity[0]['tot_presc_count']."</span></span>"; ?></th>
														<td class="text-left small">
														<?php
														$getFDCPrescDet = $objQuery->mysqlSelect("SUM(presc_count) as count","analytics_tab","city='".$val['doc_city']."' and company LIKE '%FDC LTD%'","","","");	 
														$getHighestFDCPrescDetRegion = $objQuery->mysqlSelect("brand_name,generic_name,presc_count","analytics_tab","city='".$val['doc_city']."' and company LIKE '%FDC LTD%'","presc_count desc","","");
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getFDCPrescDet[0]['count']."</span><br><br>";
														if(COUNT($getHighestFDCPrescDetRegion)>0){ 
														echo "<b>First Highest Sold: </b>".$getHighestFDCPrescDetRegion[0]['brand_name']."( ".$getHighestFDCPrescDetRegion[0]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDetRegion[0]['presc_count']."<br><br><b>Second Highest Sold: </b>".$getHighestFDCPrescDetRegion[1]['brand_name']."( ".$getHighestFDCPrescDetRegion[1]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDetRegion[1]['presc_count']."<br><br><b>Third Highest Sold: </b>".$getHighestFDCPrescDetRegion[2]['brand_name']."( ".$getHighestFDCPrescDetRegion[2]['generic_name']." )-<b>Count:</b>".$getHighestFDCPrescDetRegion[2]['presc_count'];
														}													
														?>
														
														</td>
                                                        <td class="text-left small">
														 
														<?php  
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTopPrescCity[0]['presc_count']."</span><b><br>Brand: </b>".$getTopPrescCity[0]['brand_name']."<br><b>Generic:</b>".$getTopPrescCity[0]['generic_name']."<br><b>Company:</b>".$getTopPrescCity[0]['company'];
														?>    
														</td>
														<td class="text-left small">
														 
														<?php  
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTopPrescCity[1]['presc_count']."</span><b><br>Brand: </b>".$getTopPrescCity[1]['brand_name']."<br><b>Generic:</b>".$getTopPrescCity[1]['generic_name']."<br><b>Company:</b>".$getTopPrescCity[1]['company'];
														?>    
														</td>
														<td class="text-left small">
														 
														<?php  
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTopPrescCity[2]['presc_count']."</span><b><br>Brand: </b>".$getTopPrescCity[2]['brand_name']."<br><b>Generic:</b>".$getTopPrescCity[2]['generic_name']."<br><b>Company:</b>".$getTopPrescCity[2]['company'];
														?>    
														</td>
														<td class="text-left small">
														 
														<?php  
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTopPrescCity[3]['presc_count']."</span><b><br>Brand: </b>".$getTopPrescCity[3]['brand_name']."<br><b>Generic:</b>".$getTopPrescCity[3]['generic_name']."<br><b>Company:</b>".$getTopPrescCity[3]['company'];
														?>    
														</td>
														<td class="text-left small">
														 
														<?php  
														echo "<b>Tot.Presc: </b><span class='text-danger font-bold'>".$getTopPrescCity[4]['presc_count']."</span><b><br>Brand: </b>".$getTopPrescCity[4]['brand_name']."<br><b>Generic:</b>".$getTopPrescCity[4]['generic_name']."<br><b>Company:</b>".$getTopPrescCity[4]['company'];
														?>    
														</td>
                                                        
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
												
                                            </div>
											
											<div id="after-status"></div>
                                            
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
