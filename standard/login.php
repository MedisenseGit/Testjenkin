<?php ob_start();
 error_reporting(0);
 session_start();
 
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PRACTICE - Partner Login Panel</title>
	<link rel="icon" href="../assets/img/favicon.ico">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><img src="../assets/img/Practice_Standard_Logo.png" /></h1>

            </div>
            <h3>Welcome to Practice</h3>
           
            <p>Login in. To see it in action.</p>
			<?php if(isset($_GET['respond'])){ 
			switch($_GET['respond']){
					case '0' : ?><div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">Your registration was successfull. Now log into your accout</a>
						</div> <?php ;
					break;
					case '1' : ?><div class="alert alert-danger alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">Registration failed!!!!</a>
						</div> <?php ;
					break;
					case '2' : ?><div class="alert alert-danger alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">This user already exists. Please click "Forgot password" link to recover password</a>
						</div> <?php ;
					break;
					case '3' : ?><div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">Recover password has been sent to your registered email address successfully</a>
						</div> <?php ;
					break;
					case '4' : ?><div class="alert alert-success alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">This email address has not been registered </a>
						</div> <?php ;
					break;
					}							
			?>
			<?php }  ?>
			
            <form class="m-t" role="form" method="post" action="check_credentials.php">
                <div class="form-group">
                    <input type="text" id="txtuser" name="txtuser" class="form-control" placeholder="Email Id / Mobile No." required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="txtpassword" name="txtpassword" placeholder="Password" required="">
                </div>
                <button type="submit" name="signin" id="signin" class="btn btn-primary block full-width m-b">Login</button>

                <a href="forgot"><small>Forgot password?</small></a>
                <p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register">Create an account</a>
            </form>
            <p class="m-t"> <small>Copyrights © 2017 Medisense Healthcare Solutions</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
