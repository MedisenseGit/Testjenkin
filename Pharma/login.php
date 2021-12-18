<?php 
ob_start();
session_start();
error_reporting(0); 
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Practice Pharma - Login Panel</title>
	<link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style_pharma.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


</head>

<body class="banner-white-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><img src="../assets/img/Practice_Premium_Logo.png" /></h1>

            </div>
            <h3>Welcome to PRACTICE Pharma</h3>
           
            <p>Practice Pharma Login.</p>
			<?php if(isset($_GET['response'])){
				switch($_GET['response']){ 
				case '0' : echo '<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">Login failed. Username or password are invalid.</a>.
                            </div>';
				break;
				case '1' : echo '<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">Registration failed!!!!</a>.
                            </div>';
				break;
				case '2' : echo '<div class="alert alert-danger alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <a class="alert-link" href="#">This user already exists. Please click "Forgot password" link to recover password </a>.
                            </div>';
				break;
				}
			}
			?>
			
            <form class="m-t" role="form" method="post" action="check_credentials.php">
                <div class="form-group">
                    <input type="text" id="txtuser" name="txtuser" class="form-control" placeholder="Email Id / Mobile No." required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="txtpassword" name="txtpassword" placeholder="Password" required="">
                </div>
				
                <button type="submit" name="signin" id="signin" class="btn btn-primary block full-width m-b">Login</button>

                <a href="forgot"><small>Forgot password?</small></a>
               <!--  <p class="text-muted text-center"><small>Do not have an account?</small></p>
               <a class="btn btn-sm btn-white btn-block" href="register">Create an account</a>-->
            </form>
            <p class="m-t"> <small>Copyright © 2018 Medisense Practice. All right reserved</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
<!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
</body>
</html>
