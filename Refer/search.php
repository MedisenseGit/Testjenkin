<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
//$request_uri  = str_replace("", "", $_SERVER['REQUEST_URI']);

if(empty($admin_id)){
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

if(isset($_GET['s'])){
	$SerchResult = $objQuery->mysqlSelect("DISTINCT(a.patient_id) as Patient_Id","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id inner join source_list as e on e.source_id=a.patient_src","(e.partner_id='".$admin_id."') and (a.patient_id ='".$_GET['s']."'or a.patient_mob ='".$_GET['s']."'or a.patient_name LIKE '%".$_GET['s']."%'or a.patient_email ='".$_GET['s']."'or a.patient_loc LIKE '%".$_GET['s']."%'or a.patient_desc LIKE '%".$_GET['s']."%'or a.patient_complaint LIKE '%".$_GET['s']."%')" ,"a.TImestamp desc","","","");	

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

    <title>Search Result</title>
	<!-- Favicons================================================== -->
<link rel="shortcut icon" href="../attachments/new_assets/img/favicon.ico">
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
			
              
			<form method="post" action="add_details.php" name="frmSrchBox">
			 <div class="title_right">
                <div class="col-md-3 col-sm-3 col-xs-12 pull-right form-group top_search">
                  <div class="input-group">
                    <input type="text" name="postTextSrch" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button type="submit" name="patientSrchCmd" class="btn btn-default" >Go!</button>
                    </span>
                  </div>
                </div>
              </div><br>
			 
			  </form>
           
            

            <div class="row">
             

              

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Search Result <small>Total Count:<?php echo $Total_Rslt_Count[0]['Total_count']; ?></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--<p class="text-muted font-13 m-b-30">
                      The Buttons extension for DataTables provides a common set of options, API methods and styling to display buttons on a page that will interact with a DataTable. The core library provides the based framework upon which plug-ins can built.
                    </p>-->
                    <!--<table id="datatable-buttons" class="table table-striped table-bordered">-->
					<table id="" class="table table-striped table-bordered">
                      <thead>
					          <th style="width:100px;">Patient Id</th>
											<th style="width:100px;">Reg.Date</th>
                                            <th style="width:200px;">Patient Name</th>
											<th style="width:200px;">Referred To</th>
                                           	<th style="width:200px;">Status</th>
                        </tr>
                      </thead>


                      <tbody>
					  <?php 
					  if(empty($SerchResult)){ ?>
						 <tr><td colspan="8"><center>No result found</center></td></tr>
					  <?php }
							else{ 
							foreach($SerchResult as $list){ 
										
										$refDoctors = $objQuery->mysqlSelect("a.patient_name as Patient_Name,a.TImestamp as Reg_Date,a.patient_id as Patient_Id,b.ref_id as Doc_Id,a.transaction_status as Pay_Status,b.bucket_status as Bucket_Status","patient_tab as a inner join patient_referal as b on a.patient_id=b.patient_id","a.patient_id='".$list['Patient_Id']."'","","","","");
										$getCurrentStatus = $objQuery->mysqlSelect("*","patient_referal","patient_id='".$list['patient_id']."'","","","","");
                      
										if($refDoctors[0]['Bucket_Status']=="2"){ $patient_status="<span class='label label-warning'>REFERRED</span>"; ?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="3"){  $patient_status="<span class='label label-danger'>P-AWAITING</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="5"){  $patient_status="<span class='label label-success'>RESPONDED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="6"){  $patient_status="<span class='label label-info'>RESPONSE-PATIENT-FAILED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="7"){  $patient_status="<span class='label label-info'>STAGED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="8"){  $patient_status="<span class='label label-warning'>OP-DESIRED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="9"){  $patient_status="<span class='label label-success'>IP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="10"){  $patient_status="<span class='label label-danger'>NOT-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="11"){  $patient_status="<span class='label label-success'>INVOICED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="12"){  $patient_status="<span class='label label-success'>PAYMENT RECEIVED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="13"){  $patient_status="<span class='label label-success'>OP-CONVERTED</span>";?>
										<?php } else if($refDoctors[0]['Bucket_Status']=="1"){  $patient_status="<span class='label label-primary'>NEW</span>"; } ?>
										
										<tr>
											<td><?php echo $refDoctors[0]['Patient_Id'];  ?><?php if($refDoctors[0]['Pay_Status']=="TXN_SUCCESS"){ ?><input type="hidden" name="txtPaid" value="paid" /><img src="../images/paid_icon.png"/><?php }?></td>
                                            <td><?php echo date('M d, Y',strtotime($refDoctors[0]['Reg_Date']));  ?></td> 
                                            <td><a href="patient-history?p=<?php echo md5($refDoctors[0]['Patient_Id']);  ?>"><?php echo $refDoctors[0]['Patient_Name'];  ?></a></td>
											<td><?php
											if(!empty($refDoctors)){
											foreach($refDoctors as $listDoc) { 
											$getDocDet = $objQuery->mysqlSelect("a.ref_name as Doc_Name,c.hosp_name as Doc_Hosp,c.hosp_city as Hosp_City","referal as a inner join doctor_hosp as b on a.ref_id=b.doc_id inner join hosp_tab as c on c.hosp_id=b.hosp_id","a.ref_id='".$listDoc['Doc_Id']."'","","","","");
											?>
											 
											<?php echo "<b>".$getDocDet[0]['Doc_Name']."</b><br>  ".$getDocDet[0]['Doc_Hosp'].",  ".$getDocDet[0]['Hosp_city'],"<br><br>";
											}
											}
											else{
												echo " ";
											}
											?></td>
											<td><?php echo $patient_status; ?></td>
										
                                        </tr>
										
					  <?php } 
							}?>
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
    <script src="../Hospital/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../Hospital/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Hospital/build/js/custom.min.js"></script>

  </body>
</html>