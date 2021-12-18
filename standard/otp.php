<?php ob_start();
session_start();
error_reporting(0);

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Practice - Partner Login Panel</title>
	<link rel="icon" href="../assets/img/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><img src="../assets/img/FDC_Pixel_Logo.png" /></h1>

            </div>
            <h3>Welcome to Practice</h3>
           
            <p>OTP Request</p>
			<?php if(isset($_GET['respond'])){ 
			switch($_GET['respond']){
					case '2' : ?><div class="alert alert-danger alert-dismissable">
					<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					<a class="alert-link" href="">OTP mismatch!!!! Please retry</a>
						</div> <?php ;
					break;
					
					}							
			 }  ?>
            <form class="m-t" role="form" method="post" action="check_credentials.php">
			<input type="hidden" name="partner_id" value="<?php echo $_SESSION['id']; ?>" />
                <div class="form-group">
                    <input type="text" id="txtOtp" name="txtOtp" maxlength="4" class="form-control" placeholder="Please Enter OTP here" required="">
                </div>
               
                <button type="submit" name="cmdOTP" id="cmdOTP" class="btn btn-primary block full-width m-b">Submit</button>

				<a class="btn btn-sm btn-white btn-block" href="login">Cancel</a>	
            </form>
            <p class="m-t"> <small>Copyright © <?php echo date('Y'); ?> Medisense Healthcare Solutions Pvt Ltd. All right reserved</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>
</html>
