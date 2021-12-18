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
		 <title>Practice Login Page</title>
		 
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <!-- Bootstrap  -->
      <link type="text/css" rel="stylesheet" href="../attachments/new_assets/css/bootstrap.min.css">
	   <!-- Favicons================================================== -->
      <link rel="shortcut icon" href="../attachments/new_assets/img/favicon.ico">
	  
	

 <!-- Font awesome icons================================================== -->
      <link rel="stylesheet" type="text/css" href="https://cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.css">
      <link href="css/style.css" rel="stylesheet">
     
      <link href="../attachments/new_assets/css/bootstrap-select.css" rel="stylesheet">  
    <!--  <link href="../attachments/new_assets/css/login.css" rel="stylesheet">   -->
	 <link href="css/login.css" rel="stylesheet">
			

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
	
	 <script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#slctState").html(data);
	}
	});
}
	</script>
	 </head>
	 <body >


	<section id="login">
	
	<div class="row">
	
		<div class="col-md-12 col-xs-12" style=" margin-top: 7%;">
	<!--	<center><a href="https://medisensecrm.com/Hospital_login/"  >
           <img src="../attachments/new_assets/images/leap_og.png" class="img-responsive logo "  alt="Medisense Healthcare Solutions">
            </a></center>   -->
	<div class="login-form margin-top-5" >
	
  <!-- Nav tabs -->
  <ul class="nav nav-tabs form-header" role="tablist" >
   <li class="b active">
   <a href="#login-section" aria-controls="login-section" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> LOGIN</a>
</li>
 <li class="a" >
   <a href="#signup-section" aria-controls="signup-section" role="tab" data-toggle="tab"><i class="fa fa-hospital-o"></i> REGISTER</a>
</li>


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
													case '0' : echo '<font style="color:green; font-size:14px; font-weight:bold;">You have been registered successfully in practice</font>';
													break;
													case '1' : echo '<font style="color:red; font-size:14px; font-weight:bold;">Registration failed!!!!</font>';
													break;
													case '2' : echo '<font style="color:red; font-size:14px; font-weight:bold;">This user already exists. Please click "Forgot password" link to recover password </font>';
													break;
													case '3' : echo '<font style="color:green; font-size:14px; font-weight:bold;">Recover password has been sent to your registered email address successfully</font>';
													break;
													case '4' : echo '<font style="color:red; font-size:14px; font-weight:bold;">This email address has not been registered</font>';
													break;
												}
											}
											?></span>
					</div>


<form method="post" action="check_credentials.php" name="frm-login" id="frm-login" >
<div>
     <div class="padding-bottom-8">
       Email Id/ Mob No.
     </div>
      
    <input autofocus="" class="form-control" id="txtuser" name="txtuser" required="required" placeholder="Email Id / Mob No." type="text" value="" >
      

      
   </div>
   <div >
     <div class="padding-bottom-8 padding-top-20">Password</div>
      <input class="form-control" id="txtpassword" name="txtpassword" required="required" placeholder="Password" type="password">
   </div>
<div class="padding-top-8 font-12">
<span class="remember">
 <input type="checkbox" name="new_terms_condition" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>>
 <label for="check"><a href="https://medisensecrm.com/Be-A-Panelist/empanel-terms" target="_blank">Terms and condition</a></label>
 </span>
</div>
<div class="padding-top-8" >
<p>Lost your password? <a href="#forpass" class="password-link">Click here to recover.</a></p>

</div>
<div class="row">
<div style="margin-top:20px;">
<button type="submit" class="btn-submit col-xs-5" name="signin" id="signin" >Sign In</button>
</div>
</div>
</form>
</div>


</section>
<section class="col-sm-6 col-md-6">
<div class="social-login">

<p><center> <img src="assets/images/Practice-Medisense-Logo.png" style="width:250px; margin-top: 20%;" alt="user login" /> </center></p>

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
       Email ID
     </div>
      
    <input autofocus="" class="form-control " id="txtemail" name="txtemail" required="required" placeholder="Email ID" type="email">
      

      
   </div>

<div class=" padding-top-20 ">
<button class="btn-submit col-xs-5" name="forgot" type="submit">Submit</button>

</div>
 </form>
 </div>
<!--<div class="col-sm-6 col-md-6">
<div class="social-login ">
<ul>
<li ><button class="btn-submit col-xs-5 btn-register btn-signin ">SignIn</button></a>
</li>
<li><button  class="btn-submit col-xs-5  btn-register btn-signup">Register</button></li>
</ul>
</div>

</div>-->
<section class="col-sm-6 col-md-6">
<div class="social-login">

<p><center> <img src="assets/images/Practice-Medisense-Logo.png" style="width:250px; margin-top: 20%;" alt="user login" /> </center></p>

</div>
</section>

</section>

</div>
    <div role="tabpanel" class="tab-pane content-2" id="signup-section">
	<div class="row">
	<form enctype="multipart/form-data" method="post" action="check_credentials.php" name="frm-Doc-register" id="frm-Doc-register" class="form-signin">
	<section class="signup-section col-sm-6 col-md-6">
<div class="login">

<div>
     <div class="padding-bottom-8">
       Name
     </div>
      
		<input type="text" placeholder="Name" id="txtDocName" name="txtDocName" required="required" class="form-control" />
     
   </div>
   
	<div>
     <div class="padding-bottom-8 padding-top-20">
      Country
     </div>
      
										<select class="form-control autotab" name="slctCountry" name="slctCountry" required="required" onchange="return getState(this.value);">
												<option value="" selected>Select</option>
												<?php 
												$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														
														$i = 30;
														foreach ($CntName as $CntNameList) {
														?> 
																								
															<option value="<?php echo stripslashes($CntNameList['country_name']); ?>" />
														<?php
															echo stripslashes($CntNameList['country_name']);
														?></option>
																										
														<?php
															$i++;
														}
														?>
										 </select>
	</div>
	
	<div>
     <div class="padding-bottom-8 padding-top-20">State</div>
     <select class="form-control autotab" name="slctState" id="slctState" required="required" placeholder="State"  >
	 <option value="" selected>Select</option>
												<?php
												$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_name='".$get_docInfo[0]['country']."'", "b.state_name asc", "", "", "");
												foreach ($GetState as $StateList) {
												?>
												<option value="<?php echo $StateList["state_name"];	?>">
												<?php echo $StateList["state_name"]; ?>
												</option>												
												<?php
												}
												?>
												</select>
    </div>
	
    <div>
     <div class="padding-bottom-8 padding-top-20">City</div>
      <input type="text" id="txtCity" name="txtCity" value="" class="form-control" />
                                            
    </div>
	<div>
     <div class="padding-bottom-8 padding-top-20">Specialization</div>
			<select class="form-control autotab" name="slctSpec" id="slctSpec" required="required" placeholder="State"  >
												<option value="" >Select Specialization</option>
												<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
												$i=30;
												foreach($DeptName as $DeptList){
													if($DeptList['spec_id']==$get_docInfo[0]['specialisation']){ ?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														$i++;
												}?> 
			</select>
    </div>
	<div>
									 <div class="padding-bottom-8 padding-top-20">Hospital Name</div>
									  <input type="text" id="txtHosp" name="txtHosp" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-8 padding-top-20">Qualification</div>
									  <input type="text" id="txtQual" name="txtQual" value="" required="required" class="form-control" />
																			
									</div>
</div>


</section>

   <section class="col-sm-6 col-md-6">
									
									
									<div>
									 <div class="padding-bottom-8 padding-top-20">Mobile No.</div>
									  <input type="text" id="txtMob" name="txtMob" value="" required="required" placeholder="10 digit mobile no." class="form-control"  maxlength="15" />
																			
									</div>
									<div>
									 <div class="padding-bottom-8 padding-top-20">Email Id</div>
									  <input type="email" id="txtEmail" name="txtEmail" value=""  required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-8 padding-top-20">The medical council with which you are registered</div>
									  <input type="text" id="txtMedCouncil" name="txtMedCouncil" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-8 padding-top-20">Registration no.</div>
									  <input type="text" id="txtMedRegnum" name="txtMedRegnum" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-8 padding-top-20">Upload Registration Certificate</div>
									  <input type="file" id="txtregCert" name="txtregCert" value="" class="form-control" />
																			
									</div>
									
											
										<div>
											 <div class="padding-bottom-8 padding-top-20">
											  Password
											 </div>
											 <input type="password" placeholder="password" id="passwd"  name="passwd" required="required" class="form-control" />
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
											
											
											<input type="checkbox" name="new_terms_condition" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>>
											<label for="check"><a href="https://medisensecrm.com/Be-A-Panelist/empanel-terms" target="_blank">Terms and condition</a></label>
 
										</div>
  
										<div class="row ">
											<div class=" padding-top-20">
											<button type="submit" class="btn-submit col-xs-4" name="register">Submit</button>
											</div>
										</div><br><br>
	</section>
   </form>
  </div>
  

</div>
	
	
	</div >
	<footer class="main-footer">
        <div class="copyright">
            <div class=" text-center" style="color:#fff;">
                <!--<a href="FAQ">FAQ

</a>&nbsp;|&nbsp;<a href="Terms-of-Use">TERMS OF USE

</a>&nbsp;|&nbsp; <a href="privacy-policy">PRIVACY POLICY</a> &nbsp;|&nbsp;
                <a href="hipaa-compliance">HIPAA COMPLIANCE</a>-->
                Copyrights © 2017 Medisense Healthcare Solutions
                <br>

            </div>
        </div>
    </footer>
	</div >
	</div >
	
	</section>




			    
	  <script src="../attachments/new_assets/js/jquery-3.1.0.min.js"></script>
	  <script src="../attachments/new_assets/js/bootstrap.min.js"></script>
	
	
	 
	  <!--<script src="../attachments/new_assets/js/icon.js"></script>
	  <script src="../attachments/new_assets/js/validate.js"></script>
	  <script src="../attachments/new_assets/js/loginvalidation.js"></script>-->
		  
	
 
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


    <script src="js/validationInit.js"></script>
    <script src="../Hospital/js/validationengine/js/jquery.validationEngine.js"></script>
    <script src="../Hospital/js/validationengine/js/languages/jquery.validationEngine-en.js"></script>
    <script src="../Hospital/js/jquery-validation-1.11.1/dist/jquery.validate.min.js"></script>
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
	  