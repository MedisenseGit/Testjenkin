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
			
			
			
			
$pendingRecord = $objQuery->mysqlSelect("a.TImestamp as Reg_Date,a.transaction_status as Trans_status,a.patient_id as Patient_Id,a.patient_name as Patient_Name,a.patient_src as Ref_By,b.ref_id as Doc_Id,b.status2 as Patient_status,b.timestamp as Ref_Date,d.hosp_name as Hosp_Name,d.hosp_city as Hosp_City,d.hosp_state as Hosp_State","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$_SESSION['user_id']."') and (b.status2=2 or b.status2=3)","a.patient_id desc","","","$eu, $limit");
$pag_result = $objQuery->mysqlSelect("a.patient_id as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join doctor_hosp as c on c.doc_id=b.ref_id inner join hosp_tab as d on d.hosp_id=c.hosp_id","(b.ref_id='".$_SESSION['user_id']."') and (b.status2=2 or b.status2=3)","a.patient_id desc","");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   
//$TotalCount= $objQuery->mysqlSelect("COUNT(DISTINCT(a.patient_id)) as Result_Count","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","e.partner_id='".$admin_id."'","","","","");

                 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>All Patient Records</title>

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
           <?php include_once('header_top_nav.php'); ?>
			<div class="page-title">
              <div class="title_left">
                <h3> <!-- <small>Some examples to get you started</small>--></h3>
              </div>
			
            </div>
			
			
            <div class="clearfix"></div>

            <div class="row">
             

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pending Patient Records<small>Total Count:<?php echo $_SESSION['pending_count']; ?></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
					<li>Displaying results 1 - <?php echo $pages; ?> of  <?php echo $_GET['start']; ?> </li>
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
                        <tr>
                          <th style="width:100px;">Patient Id</th>
											<th style="width:100px;">Ref. Date</th>
                                            <th style="width:200px;">Patient Name</th>
											<th style="width:200px;">Referred By</th>
                                            <th style="width:200px;">Referred To</th>
                                            <th style="width:200px;">Hospital</th>
											<th style="width:200px;">Status</th>
                        </tr>
                      </thead>


                      <tbody>
					    <?php if(empty($pendingRecord)) { ?><tr>
                                            <td colspan="7"><center>No result found</center></td>
                                        </tr> <?php } ?>
					  <?php foreach($pendingRecord as $list){ 
									
										//RETREIVE DOCTOR DETAILS
                                        $getDoc = $objQuery->mysqlSelect("*","referal","ref_id='".$list['Doc_Id']."'","","","","");
										if($list['Patient_status']=="2"){ $patient_status="<span class='label label-warning'>REFERRED</span>";  } 
										else if($list['Patient_status']=="3"){ $patient_status="<span class='label label-danger'>P-AWATING</span>";  }
										//Get Referrer info
										 $getRefBy = $objQuery->mysqlSelect("source_name as ref_by","source_list","source_id='".$list['Ref_By']."'","","","","");
										if(!empty($getRefBy[0]['ref_by'])){
											$referBy=$getRefBy[0]['ref_by'];
										}
										else{
											$referBy="Unknown";
										}
										?>
										
										<tr>
											<td><?php echo $list['Patient_Id'];  ?><?php if($list['Trans_status']=="TXN_SUCCESS"){ ?><input type="hidden" name="txtPaid" value="paid" /><img src="../images/paid_icon.png"/><?php }?></td>
                                            <td><?php echo date('M d, Y',strtotime($list['Ref_Date']));  ?></td> 
                                            <!--<td><a href="#" onclick="return getPatient(<?php echo $list['Patient_Id']; ?>);"><?php echo $list['Patient_Name'];  ?></a></td>-->
											<td><a href="patient-history?p=<?php echo md5($list['Patient_Id']); ?>&c=<?php echo md5($admin_id); ?>" target="_blank"><?php echo $list['Patient_Name'];  ?></a></td>
											<td><?php echo $referBy;  ?></td>
											<td><?php echo $getDoc[0]['ref_name'];  ?></td>
											<td><?php echo $list['Hosp_Name'].', '.$list['Hosp_City'].', '.$list['Hosp_State'];  ?></td>
											<td><?php echo $patient_status; ?></td>
																																
                                        </tr>
																				
									<?php } ?>
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