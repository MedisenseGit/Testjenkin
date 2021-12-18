<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");


//$getClientIp=$_SERVER['REMOTE_ADDR'];


include('connect.php');
include('functions.php');
$admin_id = $_SESSION['user_id'];
if(empty($admin_id)){
	header("Location:login");
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title></title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body>


                     
                        <div class="ibox-content">
                            <div>
                                <canvas id="barChart" height="300"></canvas>
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

    <!-- ChartJS-->
    <script src="../assets/js/plugins/chartJs/Chart.min.js"></script>
    <script src="../assets/js/demo/chartjs-demo.js"></script>

</body>

</html>
