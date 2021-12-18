<?php ob_start();
 error_reporting(0);
 session_start(); 

 require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

?>
<!DOCTYPE html>
<html lang="en">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <meta name="description" content="">
      <meta name="keywords" content="">
		 <title>Medical Second Opinion | Hospital Login Page</title>
		 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
		 <meta property="og:title" content="Medisense Health Solutions">
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <!-- Bootstrap  -->
      <link type="text/css" rel="stylesheet" href="../attachments/new_assets/css/bootstrap.min.css">
	   <!-- Favicons================================================== -->
      <link rel="shortcut icon" href="../attachments/new_assets/img/favicon.ico">
	  
	 <meta property="og:image" content="https://medisensehealth.com/new_assets/img/medisense_og.jpg" />
<meta property="og:title" content="Medisense Health Solutions">
<meta property="og:site_name" content="Medisense Health Solutions">
<meta property="og:url" content="https://medisensehealth.com/">
<meta property="og:description" content="MedisenseHealth.com is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
<meta property="fb:app_id" content="">
<meta property="og:type" content="article">

 <!-- Font awesome icons================================================== -->
      <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.css">
      <link href="../attachments/new_assets/css/style.css" rel="stylesheet">
      <link href="../attachments/new_assets/css/menu.css" rel="stylesheet">
      <link href="../attachments/new_assets/css/review.css" rel="stylesheet">
      <link href="../attachments/new_assets/css/bootstrap-select.css" rel="stylesheet">
      <link href="../attachments/new_assets/css/login.css" rel="stylesheet">
    	  		
			

  <link href="../attachments/new_assets/css/icon.css" rel="stylesheet">
  <link href="../attachments/new_assets/css/bootstrap-wysihtml5.css" rel="stylesheet">
  		      <link href="../attachments/new_assets/css/editor.css" rel="stylesheet">
  
  
	  
	 
 <a href="#" class="scrollup" style="display: none;">Scroll</a> 

    <script>
      var recaptcha1;
      var recaptcha2;
      var myCallBack = function() {
        //Render the recaptcha1 on the element with ID "recaptcha1"
        recaptcha1 = grecaptcha.render('recaptcha1', {
          'sitekey' : '6LdU5BsTAAAAAEQts92kmJdZsIQynxhyyF2TpSYk', //Replace this with your Site key
          'theme' : 'light'
        });
        
      };
    </script>
	 </head>
	 <body >


	<section id="login">
	
	<div class="row">
	
		<div class="col-md-12 col-xs-12">
		<center><a href="https://medisensecrm.com/Hospital_login/"  >
              <img src="https://medisensehealth.com/new_assets/images/medisense_og.png" class="img-responsive logo "  alt="Medisense Healthcare Solutions">
            </a></center>
	<div class="login-form margin-top-5" >
	
  <!-- Nav tabs -->
  <ul class="nav nav-tabs form-header" role="tablist" >
   <li class="b active">
   <a href="#login-section" aria-controls="login-section" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> LOGIN</a>
</li>
 <li class="a" >
   <a href="#signup-section" aria-controls="signup-section" role="tab" data-toggle="tab"><i class="fa fa-credit-card"></i> REGISTER</a>
</li>
<!--<li class="c">
   <a href="#login-section" aria-controls="login-section" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> DOCTOR LOGIN</a>
</li>-->

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane content-1 active" id="login-section">
		<div class="row login-frm" >
	<section class="login-section col-sm-6 col-md-6">
<div class="login">

					<div ><span class="error">
											<?php if(isset($_GET['sec'])){
												switch($_GET['sec']){
													case '0' : echo 'Login failed. Username or password are invalid.';
													break;
													case '1' : echo 'Registration failed!!!!';
													break;
													case '2' : echo 'This user is already existed';
													break;
												}
											}
											?></span>
					</div>


<form method="post" action="check_credentials.php" name="frm-login" id="frm-login" >
<div>
     <div class="padding-bottom-8">
       User Name / Email ID
     </div>
      
    <input autofocus="" class="form-control" id="user" name="user" placeholder="User Name / Email ID" type="text" value="" >
      

      
   </div>
   <div >
     <div class="padding-bottom-8 padding-top-20">Password</div>
      <input class="form-control" id="usr_passwd" name="usr_passwd" placeholder="Password" type="password">
   </div>
<div class="padding-top-8 font-12">
<span class="remember">
 <input type="checkbox" name="new_terms_condition" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>>
 <label for="check"><a href="empanel-terms" target="_blank">Terms and condition</a></label>
 </span>
</div>
<!--<div class="padding-top-8" >
<p>Lost your password? <a href="#" class="password-link">Click here to recover.</a></p>

</div>-->
<div class="row">
<div style="margin-top:20px;">
<button type="submit" class="btn-submit col-xs-5" name="signin" id="signin" >Submit</button>
</div>
</div>
</form>
</div>


</section>
<section class="col-sm-6 col-md-6">
<div class="social-login">

<p><img src="../attachments/new_assets/images/login_user.png" alt="user login" /></p>

</div>
</section>
</div>
<section class="forpass padding-btm-20 ">
<div class="row">
<div class="col-sm-6 col-md-6">
<h3>Forgot your Password?</h3>
<form method="post" action="check_credentials.php" id="f-password" >
<div>
     <div class="padding-bottom-8">
       Mobile Number / Email ID
     </div>
      
    <input autofocus="" class="form-control " id="email" name="email" placeholder="Email ID" type="text">
      

      
   </div>

<div class=" padding-top-20 ">
<button class="btn-submit col-xs-5" name="forgot" type="submit">Submit</button>

</div>
 </form>
 </div>
<div class="col-sm-6 col-md-6">
<div class="social-login ">
<ul>
<li ><button class="btn-submit col-xs-5 btn-register btn-signin ">SignIn</button></a>
</li>
<li><button  class="btn-submit col-xs-5  btn-register btn-signup">Register</button></li>
</ul>
</div>

</div>

</section>

</div>
    <div role="tabpanel" class="tab-pane content-2" id="signup-section">
	<div class="row">
	<form method="post" action="check_credentials.php" name="frm-Hosp-register" id="frm-Hosp-register" class="form-signin">
	<input type="hidden" name="comp_key" id="comp_key" value="med123" />
	<section class="signup-section col-sm-6 col-md-6">
<div class="login">

<div>
     <div class="padding-bottom-8">
       Organisation Name
     </div>
      
		<input type="text" placeholder="Organisation Name" id="orgname" name="orgname" class="form-control" />
     
   </div>
   
	<div>
     <div class="padding-bottom-8 padding-top-20">
      User Name
     </div>
      
   <input type="text" placeholder="User Name" id="username" name="username" class="form-control" />
	</div>
	
	<div>
     <div class="padding-bottom-8 padding-top-20">Email Id</div>
     <input type="text" placeholder="Email Id" id="umail" name="umail" class="form-control" />
    </div>
	
    <div>
     <div class="padding-bottom-8 padding-top-20">Mobile No.</div>
       <input type="text" placeholder="Mobile No." id="umobile" name="umobile" class="form-control" />
    </div>
	
</div>


</section>

   <section class="col-sm-6 col-md-6">
	
											
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Password
											 </div>
											 <input type="password" placeholder="password" id="passwd" name="passwd" class="form-control" />
										</div>
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Re-type Password
											 </div>
											 <input type="password" placeholder="Re type password" id="repasswd" name="repasswd" class="form-control" />
										</div>
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Enter Key
											 </div>
											 <input type="text" placeholder="Enter Key" id="compkey" name="compkey" class="form-control" />
										</div>

	
										<div class="row">									 
											  <div class="col-md-12 no-paddinglt">
																	
											<p class="padding-top-20 ">
												<img src="../captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' data-pin-nopin="true"><br>
												<label for="message">Enter the code above here :</label><br>

												<input id="letters_code" name="letters_code" type="text"><br>
												<small>Can't read the image? click <a href="javascript: refreshCaptcha();">here</a> to refresh</small>
											</p>
											</div>
										</div>
  
										<div class="row ">
											<div class=" padding-top-20">
											<button type="submit" class="btn-submit col-xs-4" name="register">Submit</button>
											</div>
										</div>
	</section>
   </form>
  </div>
  

</div>
	
	
	</div >
	<footer class="main-footer">
        <div class="copyright">
            <div class=" text-center">
                <a href="FAQ">FAQ

</a>&nbsp;|&nbsp;<a href="Terms-of-Use">TERMS OF USE

</a>&nbsp;|&nbsp; <a href="privacy-policy">PRIVACY POLICY</a> &nbsp;|&nbsp;
                <a href="hipaa-compliance">HIPAA COMPLIANCE</a>
                <br> Copyrights © 2017 Medisense Healthcare Solutions
                <br>

            </div>
        </div>
    </footer>
	</div >
	</div >
	
	</section>




			    
	  <script src="../attachments/new_assets/js/jquery-3.1.0.min.js"></script>
	  <script src="../attachments/new_assets/js/bootstrap.min.js"></script>
	
	
	 
	  <script src="../attachments/new_assets/js/icon.js"></script>
	  <script src="../attachments/new_assets/js/validate.js"></script>
	  <script src="../attachments/new_assets/js/loginvalidation.js"></script>
		  
	
 
  <script type="text/javascript" src="../attachments/new_assets/js/jquery.autocomplete.js"></script>
  <script type="text/javascript" src="../attachments/new_assets/js/bootstrap-select.js"></script>
  <script type="text/javascript" src="../attachments/new_assets/js/autosize.js"></script>
 
	  	 
	  <script>
		$("#menu-close").click(function(e) {
   e.stopPropagation();
   $("#sidebar-wrapper").toggleClass("active");
});
$("#menu-toggle").click(function(e) {
   e.stopPropagation();
   $("#sidebar-wrapper").toggleClass("active");
});
$(document).click(function(){
   if($("#sidebar-wrapper").hasClass('active')){
      $("#sidebar-wrapper").removeClass("active");
   }
});
$('.dropdown-toggle').dropdown()
</script>

<!-- PAGE LEVEL SCRIPTS -->
      <script src="assets/plugins/jquery-2.0.3.min.js"></script>
      <script src="assets/plugins/bootstrap/js/bootstrap.js"></script>
   <script src="assets/js/login.js"></script>
      <!--END PAGE LEVEL SCRIPTS -->

	  <!-- PAGE LEVEL SCRIPTS -->

     <script src="assets/plugins/validationengine/js/jquery.validationEngine.js"></script>
    <script src="assets/plugins/validationengine/js/languages/jquery.validationEngine-en.js"></script>
    <script src="assets/plugins/jquery-validation-1.11.1/dist/jquery.validate.min.js"></script>
    <script src="assets/js/validationInit.js"></script>
    <script>
        $(function () { formValidation(); });
        </script>


  <script>



 $(window).scroll(function () {
        if ($(this).scrollTop() >150) {
            $(".sticky-header").css({"visibility": "visible", "opacity": "1", "position": "fixed", "top": "0"});
        } else {
            $(".sticky-header").css({"visibility": "hidden", "opacity": "0", "top": "-30px"});
        }
    });
	$(window).scroll(function () {
        if ($(this).scrollTop() >240) {
            $(".sticky-left-menu").css({"visibility": "visible", "opacity": "1", "position": "fixed", "top": "150px"});
            $(".moving-left-menu").css({"visibility": "hidden", "opacity": "0"});
			
        } 
		 else {
            $(".sticky-left-menu").css({"visibility": "hidden", "opacity": "0"});
			            $(".moving-left-menu").css({"visibility": "visible", "opacity": "1"});
        }
    });
</script>
<script>
        
</script>
<script>
		autosize(document.querySelectorAll('textarea'));
		
		function refreshCaptcha() {
            var img = document.images['captchaimg'];
            img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;

        }
</script>
<script>
	$(document).ready(function(){
	
	$('.login-frm').addClass('d_visible');
	$('.login-frm').removeClass('login-frm1');
	//login-section	
	});
	 $('.password-link').click(function () {
                   
						$('.login-frm').removeClass('d_visible');
						$('.login-frm').addClass('login-frm1');
						
						$('.forpass').addClass('d_visible');
							$('.forpass').removeClass('login-frm1');
					 
                    	
                    });
						 $('.btn-signin').click(function () {
                   
						$('.login-frm').removeClass('login-frm1 ');
						$('.login-frm').addClass('d_visible');
						
						$('.forpass').addClass('login-frm1');
						$('.forpass').removeClass('d_visible');
						
					 
                    	
                    });

	 $('.btn-signup').click(function () {
$('.form-header>.a').addClass('active');
$('.form-header>.b').removeClass('active');
	
	$('#signup-section').addClass('active');
	$('#login-section').removeClass('active');
	
	    });
		
 $('.form-header>.b').click(function () {
$('.form-header>.a').removeClass('active');
$('.form-header>.b').addClass('active');
	
	$('#signup-section').removeClass('active');
	$('#login-section').addClass('active');
	$('.forpass').addClass('login-frm1');
						$('.forpass').removeClass('d_visible');
						$('.login-frm').removeClass('login-frm1 ');
						$('.login-frm').addClass('d_visible');
	    });
	</script>
	  </body>
	  </html>
	  