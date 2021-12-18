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

    <title>Medisense Practice Premium - Login Panel</title>
	<link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">


</head>

<body class="banner-white-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown" >
        <div >
            <div style="margin-top:60px;">

                <h1 class="logo-name"><img src="../assets/img/nova_logo1.png" width="380px" height="120px;" style="padding-right:60px;"/></h1>

            </div>
            <h3>Login</h3>
           
            <!--<p>Practice Premium Login.</p>-->
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
				case '3' : echo '<div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">Recover password has been sent to your registered email address successfully</a>
						</div>';
				break;
				case '4' : echo '<div class="alert alert-danger alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">This email address has not been registered </a>
						</div>';
				break;
				
				}
			}
			?>
			
            <form  role="form" method="post" action="check_credentials.php">
                <div class="form-group">
                    <input type="text" id="txtuser" name="txtuser" class="form-control" placeholder="Email Id / Mobile No." required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="txtpassword" name="txtpassword" placeholder="Password" required="">
                </div>
				<div class="form-group">
					<!--<label class="col-sm-12 control-label">Type</label>-->
                                     
                     <div class="radio radio-info radio-inline">
                           <input type="radio" id="inlineRadio1" value="1" name="logintype" checked="">
                                            <label for="inlineRadio1"> Doctor </label>
                      </div>
                      <div class="radio radio-info radio-inline">
                         <input type="radio" id="inlineRadio2" value="2" name="logintype">
                         <label for="inlineRadio2"> Institution </label>
                       </div>
					    <div class="radio radio-info radio-inline">
                         <input type="radio" id="inlineRadio4" value="4" name="logintype">
                         <label for="inlineRadio4"> Hospital </label>
                       </div>
					   <div class="radio radio-info radio-inline">
                         <input type="radio" id="inlineRadio3" value="3" name="logintype">
                         <label for="inlineRadio3"> Receptionist </label>
                       </div>
					  
				</div>
				 <!--<h3>APPLY COUPON</h3>
				 <div class="form-group">
                    <input type="text" id="txtcoupon" name="txtcoupon" class="form-control" placeholder="Enter Coupon Code">
                </div>-->
                <button type="submit" name="signin" id="signin" class="btn btn-success block full-width m-b">Login</button>

                <a href="forgot"><small>Forgot password?</small></a>
               <!--  <p class="text-muted text-center"><small>Do not have an account?</small></p>
               <a class="btn btn-sm btn-white btn-block" href="register">Create an account</a>-->
            </form>
			
            <p class="m-t"> <small>Copyright © 2018 Medisense Practice. All right reserved<br> Best viewed on Chrome and Firefox browsers</small> </p>
			<a href="<?php echo HOST_HEALTH_URL; ?>privacy-policy" target="_blank" style="float:center;"><small><font color = "#1a7bb9">Privacy Policy</font> </small></a>
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
