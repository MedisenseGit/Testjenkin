<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Medisense Practice Premium - Login Panel</title>
	<link rel="icon" href="new_theme/images/favicon_icon.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">

</head>

<body class="banner-white-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name"><img src="../assets/img/Practice_Premium_Logo.png" /></h1>

            </div>
            <h3>Forgot your Password?</h3>
           
            <p>Don't worry! Follow the steps</p>
            <form class="m-t" role="form" method="post" action="check_credentials.php">
                <div class="form-group">
                    <input type="email" name="txtemail" class="form-control" placeholder="Enter your registered Email Id" required="">
                </div>
               
                <button type="submit" type="submit" name="forgot" class="btn btn-primary block full-width m-b">Submit</button>

                <a href="login"><small>Login</small></a>
                <!--<p class="text-muted text-center"><small>Do not have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="register">Create an account</a>-->
            </form>
            <p class="m-t"> <small>Copyright © 2018 Medisense Practice. All right reserved</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>

</html>
