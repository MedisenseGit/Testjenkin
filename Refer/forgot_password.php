<!DOCTYPE html>
<html>
<head>
<title>Medisense Leap - Doctors Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="login_assets/css/bootstrap.min.css" rel="stylesheet">
<link href="login_assets/css/style.css" rel="stylesheet">
<link href="login_assets/css/font-awesome.min.css" rel="stylesheet">
<!-- Favicons================================================== -->
<link rel="shortcut icon" href="login_assets/images/leap_favicon.png">
<script src="login_assets/js/jquery.min.js"></script>
</head>
<body>

<section id="signin_main" class="medisense signin-main centered" style=" margin-top:6%;>
				<div class="section-content">
				  <div class="wrap">
					  <div class="container">	  
							<div class="form-wrap">
								<div class="row">
								  <!--<h3 class="partner">Doctors Login</h3>-->
									<div id="form_1">
										<div class="form-header">
										 <img src="login_assets/images/medisense_og.png" alt="user login" />
									  </div>
									  <form enctype="multipart/form-data" action="check_credentials.php" id="leaplogin" method="post">
									  <div class="form-main">
									  <span class="error">
											<?php if(isset($_GET['response'])){
												switch($_GET['response']){
													case '0' : echo '<font style="color:red;">Login failed. Username or password are invalid.</font>';
													break;
													case '1' : echo '<font color=#124801; font-weight:bold;>Recover password has sent to your email address successfully</font>';
													break;
													case '2' : echo '<font style="color:red; font-weight:bold;">This email address has not been registered</font>';
													break;
												}
											}
											?></span>
										  <div class="form-group">
								  			<input type="email" id="txtemail" name="txtemail" class="form-control" required="required" placeholder="Enter Email address">
											
										  </div>
										  
									    <button id="forgot" name="forgot" type="submit" class="btn btn-block signin">Submit</button>
									  </div>
									  </form>
										<div class="form-footer">
											<div class="row">
												<div class="col-xs-7">
													<i class="fa fa-arrow"></i>
													<a href="https://medisensecrm.com/Refer/login" id="forgot_from_1"><< Back</a>
												</div>
												<!--<div class="col-xs-5">
													<i class="fa fa-check"></i>
													<a href="#" id="signup_from_1">Sign Up</a>
												</div>-->
											</div>
										</div>		
								  </div>
								</div>
							</div>
					  </div>
				  </div>
				</div>
			</section>
			<p class="footer">Powered by <a href="https://medisensecrm.com/Refer/" target="_blank">Medisenseleap.com</a>


	<script src="js/validationInit.js"></script>
    <script src="js/validationengine/js/jquery.validationEngine.js"></script>
    <script src="js/validationengine/js/languages/jquery.validationEngine-en.js"></script>
    <script src="js/jquery-validation-1.11.1/dist/jquery.validate.min.js"></script>
    <script>
        $(function () { formValidation(); });
    </script>
</body>
</html>
