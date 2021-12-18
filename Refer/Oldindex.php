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
		 <title>Medical Second Opinion | Medisense Healthcare Solutions</title>
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
      <link rel="shortcut icon" href="new_assets/img/favicon.ico">
	  
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
 <link href="../Hospital/css/login.css" rel="stylesheet">
    	  		
			

  <link href="../attachments/new_assets/css/icon.css" rel="stylesheet">
  <link href="../attachments/new_assets/css/bootstrap-wysihtml5.css" rel="stylesheet">
  		      <link href="../attachments/new_assets/css/editor.css" rel="stylesheet">
  
  
	  
	 
 <a href="#" class="scrollup" style="display: none;">Scroll</a> 

 <script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "../../get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#sub_state").html(data);
	}
	});
}

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
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
	
		<div class="col-md-12 col-xs-12" style=" margin-top: 7%;">
		
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
											<?php if(isset($_GET['respond'])){
												switch($_GET['respond']){
													case '1' : echo '<b>This user is already registered with us</b>';
													break;
													case '2' : echo '<b>Login Failed, Please check user name & password</b>';
													break;
													case '3' : echo '<b>This email address has not been registered</b>';
													break;
													case '4' : echo '<font color=green>Recover password website link has sent to your email address successfully</font>';
													break;
													case '5' : echo '<font color=green>Your password has been changed successfully</font>';
													break;
												}
											}
											?></span>
					</div>


<form enctype="multipart/form-data" action="check_credentials.php" id="partner-login" method="post">
<div>
     <div class="padding-bottom-8">
       Mobile Number / Email ID
     </div>
      
    <input autofocus="" class="form-control" id="txtuser" name="txtuser" placeholder="Mobile Number / Email ID" type="text" value="" autocomplete="off">
      

      
   </div>
   <div >
     <div class="padding-bottom-8 padding-top-20">Password</div>
      <input class="form-control" id="txtpassword" name="txtpassword" placeholder="Password" type="password" autocomplete="off" >
   </div>
<!--<div class="padding-top-8 font-12">
<span class="remember">
<input type="checkbox" id="check">
 <label for="check">Remember Me</label>
 </span>
</div>-->
<div class="padding-top-8" >
<p>Lost your password? <a href="#" class="password-link">Click here to recover.</a></p>

</div>
<div class="row">
<div class=" padding-top-8">
<button class="btn-submit col-xs-5" name="signin" type="submit">Submit</button>
</div>
</div>
</form>
</div>


</section>
<section class="col-sm-6 col-md-6">
<div class="social-login">

<p><center> <img src="../Hospital/images/leap.png" style=" margin-top: 20%;" alt="user login" /> </center></p>
<!--<div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false"></div>
<div id="status"></div>-->

<ul>
<!--<li><a href="" class="tw"><i class="fa fa-twitter"></i><span>Sign In with Twitter</span></a></li>
<li><a href="login.php" class="gp"><i class="fa fa-google-plus"></i><span>Sign In with Google+</span></a></li>
<li><a href="" class="in"><i class="fa fa-linkedin"></i><span>Sign In with Linkedin</span></a></li>-->
</ul>
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
	<form enctype="multipart/form-data" action="check_credentials.php" method="post" id="partner-signup">
	<section class="signup-section col-sm-6 col-md-6">
<div class="login">

<div>
     <div class="padding-bottom-8">
       I am a
     </div>
      
		<select name="comp_type" id="comp_type" class="form-control">
			<option value="">Select</option>
			<option value="Individual">Individual</option>
			<option value="Company">Company</option>
			<option value="Doctor">Doctor</option>
			<option value="Hospital">Hospital</option>
													
		</select>
      

      
   </div>
   
	<div>
     <div class="padding-bottom-8 padding-top-20">
       Type of Business you are in
     </div>
      
    <textarea rows="4" name="business_type" id="business_type"  class="form-control"></textarea>
	</div>
	
	<div>
     <div class="padding-bottom-8 padding-top-20">Name</div>
     <input class="form-control " id="businessName" name="businessName" type="text" value="">
    </div>
	
    <div>
     <div class="padding-bottom-8 padding-top-20">Contact Person</div>
       <input class="form-control " id="contact_person" name="contact_person" type="text" value="">
    </div>
	
	  <div>
		<div class="padding-bottom-8 padding-top-20">
			Contact Person Position in the company
        </div>
      <input class="form-control " id="person_position" name="person_position"  type="text" value="">
     </div>
	 
	 <div>
		<div class="padding-bottom-8 padding-top-20">
			Contact Landline Number 
        </div>
      <input class="form-control " id="contact_land" name="contact_land" type="text" value="">
     </div>
	 
	 <div>
		<div class="padding-bottom-8 padding-top-20">
			Contact mobile Number
        </div>
      <input class="form-control " id="contact_mobile" name="contact_mobile" type="text" value="">
     </div>
	 <div>
		 <div class="padding-bottom-8 padding-top-20">
		  Website
		 </div>
		 <input class="form-control " id="txtWebsite" name="txtWebsite" type="text" value="">
    </div>
  
 
</div>


</section>

   <section class="col-sm-6 col-md-6">
	
	<div>
		 <div class="padding-bottom-8 padding-top-20">
		  Primary Email ID
		 </div>
		 <input class="form-control " id="primaryEmail" name="primaryEmail" type="text" value="">
    </div>
	<div>
		 <div class="padding-bottom-8 padding-top-20">
		  Secondary Email ID
		 </div>
		 <input class="form-control " id="secondaryEmail" name="secondaryEmail" type="text" value="">
    </div>
	
   
										<div class="row">
                                             <section class="col-sm-6 no-paddinglt">
											<div class="padding-bottom-8 padding-top-20">Country</div>                                               
											   <label class="">
													
                                                    <select name="slctcountry" id="slctcountry" onchange="return getState(this.value);" class="valid form-control">
													    <option value="India"  selected>India</option>
												<?php 
												$CntName= $objQuery->mysqlSelect("*","countries","","country_name asc","","","");
												$i=30;
												foreach($CntName as $CntNameList){
													?> 
										
													<option value="<?php echo stripslashes($CntNameList['country_name']);?>" />
													<?php echo stripslashes($CntNameList['country_name']);?></option>
												
													<?php 
													$i++;
												}?>
                                                    </select>
                                                    <i></i>
										
                                                </label>
                                            </section> 
											<section class="col-sm-6 no-paddingrt no-paddinglt">
                                                <label class="">
												<div class="padding-bottom-8 padding-top-20">State</div>
                                                    <select name="slctstate" id="slctstate" placeholder="State" class="form-control">
													<option value="">Select State</option>
													<?php $GetState= $objQuery->mysqlSelect("*","countries as a left join states as b on a.country_id=b.country_id","a.country_id=100","b.state_name asc","","","");
													foreach($GetState as $StateList) {
													?>
													<option value="<?php echo $StateList["state_name"]; ?>"><?php echo $StateList["state_name"]; ?></option>
													<?php }
													?>
												</select>
												 <i></i><span id="se_state_err" class="error"></span>   
											   </label>
                                            </section>
										
										</div>  
										<div class="row">
                                             <section class="col-sm-6 no-paddinglt">
											 <div class="padding-bottom-8 ">Location </div>
											 <input type="text" name="txt_city" id="txt_city" class="form-control" placeholder="City">
											 </section>
											 <section class="col-sm-6  no-paddinglt no-paddingrt">
											 <div class="padding-bottom-8 ">Address </div>
											 <textarea rows="4" name="txt_address" id="txt_address" placeholder="Address" class="form-control"></textarea>
											 </section>
										</div>
										
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Password
											 </div>
											 <input class="form-control " id="txtpassword" name="txtpassword" placeholder="*******" type="password"  value="">
										</div>
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Re-type Password
											 </div>
											 <input class="form-control "  name="txtrepassword" id="txtrepassword" placeholder="*******" type="password" value="">
										</div>
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Attach Logo
											 </div>
											 <input type="file" class="form-control " name="compLogo"  value="" />
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
											<button class="btn-submit  col-xs-4" name="register" type="submit" >Submit</button>
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
 
	    <script src='https://www.google.com/recaptcha/api.js'></script> 
	 
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
	  