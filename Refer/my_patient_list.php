<?php
ob_start();
error_reporting(0); 
session_start();
//echo "aa"; exit;
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
			
			
			
			
$allRecord = $objQuery->mysqlSelect("patient_id,patient_name,patient_email,patient_mob,patient_loc,TImestamp","my_patient","partner_id='".$admin_id."'","patient_id desc","","","$eu, $limit");
$pag_result = $objQuery->mysqlSelect("patient_id","my_patient","partner_id='".$admin_id."'","patient_id desc");
$pageing = firstPaging($pag_result,$limit,$back,$next,$eu,$field,$type2);
$arrPage = explode("-",$pageing);   

                 
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
			
			
			
            <div class="clearfix"></div>

            <div class="row">
             

              

              <div class="col-md-12 col-sm-12 col-xs-12">
			  <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                          <li role="presentation" class="active" ><a href="My-Patient-List" id="home-tab"  >MY PATIENT LIST <span class="badge bg-red" style="font-size:8px;"><?php echo $countMyPatient[0]['Total_count']; ?></span></a>
                          </li>
                          <li role="presentation" ><a href="Appointments"  id="profile-tab" >APPOINTMENTS <span class="badge bg-red" style="font-size:8px;"><?php echo $Total_Appointment_Count[0]['count']; ?></span></a>
                          </li>
						   <div class="right">
                <div class="form-group pull-right top_search">
                  <div class="input-group">
                    <a href="My-Patient-Profile" class="btn btn-primary"><i class="fa fa-wheelchair"></i> ADD PATIENT </a>                     
                    </span>
                  </div>
                </div>
              </div>
                          
              </ul>
			 
                <div class="x_panel">
				
                  <div class="x_title">
				  
					<?php 
					if(isset($_GET['response']))
					{
					?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<strong>Success!</strong> Patient Records has been successfuly saved.
					</div>
					<?php 
					}
					?>
                    <h2>My Patient List<small>Total Count: <?php echo $countMyPatient[0]['Total_count']; ?></small></h2>
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
					           <th style="width:100px;">Patient Id</th>
											<th style="width:100px;">Reg.Date</th>
                                            <th style="width:200px;">Patient Name</th>
											<th style="width:200px;">Contact Details</th>
                                           
											
                        </tr>
                      </thead>


                      <tbody>
					  <?php foreach($allRecord as $list){ 
										
							?>			
										<tr>
											<td><?php echo $list['patient_id'];  ?></td>
                                            <td><?php echo date('M d, Y',strtotime($list['TImestamp']));  ?></td> 
                                            <td><a href="My-Patient-Profile?p=<?php echo md5($list['patient_id']); ?>"><?php echo $list['patient_name'];  ?></a></td>
											<td><i class="fa fa-envelope"></i> <?php echo $list['patient_email'];  ?><br>
											<i class="fa fa-mobile"></i> <?php echo $list['patient_mob'];  ?></td>
											
										
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