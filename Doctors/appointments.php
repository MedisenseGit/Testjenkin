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
$objQuery = new CLSQueryMaker();

if(isset($_POST['cmdGetId'])){
	$_SESSION['patient_id']=$_POST['patient_id'];
	$_SESSION['request_uri']=$request_uri;
	header('location:patient-history');	
}

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
			$appointmentResult = $objQuery->mysqlSelect("a.id as App_ID,a.appoint_trans_id as Trans_ID,a.pref_doc as Pref_Doc,a.department as Dept,a.Visiting_date as Visit_Date,a.Visiting_time as Visit_Time,a.patient_name as Patient_name,a.Mobile_no as Mobile,a.Email_address as Email,a.pay_status as Pay_Status,a.visit_status as Visit_Status","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.pref_doc='".$admin_id."'","a.Visiting_date desc","","","$eu, $limit");
			$pag_result = $objQuery->mysqlSelect("a.id","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.pref_doc='".$admin_id."'","");
			$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
			$arrPage = explode("-",$pageing);   
		//$Total_App_Count = $objQuery->mysqlSelect("COUNT(a.id) as count","appointment_transaction_detail as a inner join doctor_hosp as b on a.pref_doc=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.pref_doc='".$admin_id."'","","","","");
 
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

    <title>Patient Appointment</title>
 <!-- Bootstrap -->
    <link href="../Hospital/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Hospital/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Hospital/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Hospital/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../Hospital/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Hospital/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<link href="../Hospital/css/pagination.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Hospital/build/css/custom.min.css" rel="stylesheet">
	<link type="text/css" rel="stylesheet" href="../Hospital/jsPopup/popModal.css">
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
              <div class="count"><?php echo $countMyPatient[0]['Total_count']; ?></div></a>
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
			
			<div class="row">
              <!--<div class="title_left">
                <h3><?php echo $_SESSION['company_name']; ?></h3>
              </div>-->
			  
				
			
			
			<form method="post" action="add_details.php" name="frmSrchBox">
			 <div class="title_right pull-right">
                <div class="col-md-3 col-sm-3 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" name="postTextSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </div>
              </div><br>
			 
			  </form>
            </div>
			

            <div class="clearfix"></div>

            <div class="row">
             

              

              <div class="col-md-12 col-sm-12 col-xs-12">
			   <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" ><a href="My-Patient-List" id="home-tab"  >MY PATIENT LIST <span class="badge bg-red" style="font-size:8px;"><?php echo $countMyPatient[0]['Total_count']; ?></span></a>
                          </li>
                          <li role="presentation" class="active"><a href="appointments"  id="profile-tab" >APPOINTMENTS <span class="badge bg-red" style="font-size:8px;"><?php echo $Total_Appointment_Count[0]['count']; ?></span></a>
                          </li>
                          
              </ul>
			  
			  
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Patient Appointment Request<small>Total Count:<?php echo $Total_Count[0]['count']; ?></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
					<li>Displaying results 1 - <?php echo $pages; ?> of <?php echo $_GET['start']; ?> </li>
                     <li>&nbsp;&nbsp;</li>
					 <li><?php echo $arrPage[0];?></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--<p class="text-muted font-13 m-b-30">
                      The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
                    </p>-->
                    <!--<table id="datatable-buttons" class="table table-striped table-bordered">-->
					<table id="" class="table table-striped table-bordered">
                      <thead>
					           <th style="width:100px;">Transaction Id</th>
								<th style="width:100px;">Visit Date</th>
                                <th style="width:200px;">Patient Name</th>
								<th style="width:200px;">Appointment Slot</th>
                                <th style="width:200px;">Status</th>
                                <th style="width:200px;">View</th>
											
                        </tr>
                      </thead>


                      <tbody>
					  <?php if(empty($appointmentResult)) { ?><tr>
                                            <td colspan="6"><center>No result found</center></td>
                                        </tr> <?php } ?>
					  <?php foreach($appointmentResult as $list){ 
									
							$getDept = $objQuery->mysqlSelect("*","specialization","spec_id='".$list['Dept']."'","","","","");
			$getTimeSlot= $objQuery->mysqlSelect("*","timings","Timing_id='".$list['Visit_Time']."'","","","","");
			$getDoc= $objQuery->mysqlSelect("*","referal","ref_id='".$list['Pref_Doc']."'","","","","");
				
			if($list['Pay_Status']!="Canceled"){
			?>
			
			<tr>
				<td class="textAlign"><?php echo $list['Trans_ID']; ?></td>
				<td><?php echo date('d-m-Y',strtotime($list['Visit_Date'])); ?></td>
				<td class="textAlign"><a href="appointment_patient_history.php?pattransid=<?php echo $list['Trans_ID']; ?>"><?php echo $list['Patient_name']; ?></a>
			
				</td>
				
				
				<td style="min-width:200px;" ><?php echo $getDept[0]['spec_name']." | ".$getDoc[0]['ref_name']." | ".$getTimeSlot[0]['Timing']; ?></td>
				<td><?php if($list['Pay_Status']=="COA_Pending"){ ?><a href="#" onclick="return chngPay(<?php echo $list['App_ID']; ?>,2);"><?php echo $list['Pay_Status']; ?>
				</a><?php } else { echo $list['Pay_Status']; } ?></td>
				
				<td><?php if($list['Pay_Status']=="Confirmed"){ ?>Already processed<?php } else { ?><a href="#" onclick="return cancelTrans(<?php echo $list['App_ID']; ?>,1);">Cancel
				</a><?php } ?></td>
				
				
				</tr>
				<?php
			}

				$j++; }  ?>
										
										
										
					 
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              
              
          </div>
        </div>
        <!-- /page content -->
		<?php include_once('footer.php'); ?>
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
    <!-- iCheck -->
    <script src="../Hospital/vendors/iCheck/icheck.min.js"></script>
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
    <script src="../Hospital/vendors/jszip/dist/jszip.min.js"></script>
    <script src="../Hospital/vendors/pdfmake/../Hospital/build/pdfmake.min.js"></script>
    <script src="../Hospital/vendors/pdfmake/../Hospital/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>