<!DOCTYPE html>
<html>
<head>
<title>Medisense Practice - Partner Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="login_assets/css/bootstrap.min.css" rel="stylesheet">
<link href="login_assets/css/style.css" rel="stylesheet">
<link rel="icon" href="favicon.png" sizes="16x16">
<link href="login_assets/css/font-awesome.min.css" rel="stylesheet">

<script src="login_assets/js/jquery.min.js"></script>
</head>
<body>

<section id="signin_main" class="medisense signin-main centered" style=" margin-top:8%;>
				<div class="section-content">
				  <div class="wrap">
					  <div class="container">	  
							<div class="form-wrap">
								<div class="row">
								  <!--<h3 class="partner">Partner Login</h3>-->
									<div id="form_1">
										<div class="form-header">
										 <img src="login_assets/images/medisense_og.png" alt="user login" />
									  </div>
									  <form enctype="multipart/form-data" action="check_credentials.php" id="partner-login" method="post">
									  <div class="form-main">
										  <div class="form-group">
								  			<input type="text" id="txtuser" name="txtuser" class="form-control" placeholder="Email Id/ Mob No." required="required">
												<input type="password" id="txtpassword" name="txtpassword" class="form-control" placeholder="Password" required="required">
										  </div>
									    <button id="signin" name="signin" type="submit" class="btn btn-block signin">Sign In</button>
									  </div>
									  </form>
										<div class="form-footer">
											<div class="row">
												<div class="col-xs-7">
													<i class="fa fa-unlock-alt"></i>
													<a href="forgot" id="forgot_from_1">Forgot password?</a>
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
			<p class="footer">Powered by <a href="https://medisensecrm.com/Refer/" target="_blank">Medisense Healthcare Solutions Pvt Ltd.</a>


<script src="login_assets/js/validate.js"></script>
<script src="login_assets/js/loginvalidation.js"></script>
<script src="login_assets/js/jquery.min.js"></script>
<script src="login_assets/js/bootstrap.min.js"></script> 


</body>
</html>
