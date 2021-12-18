<?php
ob_start();
session_start();
error_reporting(0);  

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();
if(empty($_GET['d'])){
	echo "<h2>Error!!!!!!</h2>";
}
$checkPatient= mysqlSelect("patient_id,patient_name","patients_appointment","md5(patient_id)='".$_GET['d']."'","","","","");

		
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Premium</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

	<link href="fileUpload/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link href="fileUpload/themes/explorer-fa/theme.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="fileUpload/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="fileUpload/js/fileinput.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/fr.js" type="text/javascript"></script>
    <script src="fileUpload/js/locales/es.js" type="text/javascript"></script>
    <script src="fileUpload/themes/explorer-fa/theme.js" type="text/javascript"></script>
    <script src="fileUpload/themes/fa/theme.js" type="text/javascript"></script>
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                
                <a href="#" class="navbar-brand"><img alt="image" class="img" src="../assets/img/Practice_premium.png" width="80"/></a>
            </div>
            
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
            <div class="row">
				
			<?php if($_GET['response']=="reports-attached"){ ?>
			<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close"  type="button">×</button>
					<strong>Reports uploaded successfully!!! </strong>
			</div>
			<?php } ?>
                <div class="col-md-12">
                    <div class="ibox-content text-center p-md">

                    <h2><span class="text-navy">Hello <?php echo $checkPatient[0]['patient_name']; ?> !!!</span>
                    If you have any medical report, then please upload here</h2>

                    


                </div>
                </div>
				
				 <div class="col-md-12">
				 <form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="my_patient_profile_save.php"  name="frmAddPatient" id="frmAddPatient">
					<input type="hidden" name="patient_id" value="<?php echo $checkPatient[0]['patient_id']; ?>">
                    <div class="ibox-content text-center p-md">
					<div class="row">	
					<label><i class="fa fa-file-medical"></i> Attach Reports here ( Allowed file types: jpg, jpeg, png)</label>
                   
									<div class="form-group col-lg-12">
										<div class="file-loading">
											<input id="file-5" name="file-5[]" class="file" type="file" required multiple data-preview-file-type="any" data-upload-url="#" tabindex="7">
										</div>
									</div>
                    
					</div>
					<div class="row" id="image_preview"></div>
					<div class="row">
						<button type="submit" name="addAttachments" class="btn btn-primary block full-width m-b ">CLICK HERE TO ATTACH REPORTS</button>
					</div>
					</div>
				</form>
                </div>
               
            </div>
                

               

            </div>

        </div>
        <div class="footer">
            
            <div>
                <strong>Copyright</strong> Medisense Healthcare Solutions Pvt. Ltd. &copy; <?php echo date('Y'); ?>
            </div>
        </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Flot -->
    <script src="../assets/js/plugins/flot/jquery.flot.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../assets/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="../assets/js/demo/peity-demo.js"></script>


   

</body>

</html>
