<?php 
ob_start();
session_start();
error_reporting(0);  
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PRACTICE Standard Account Registration</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="../assets/css/animate.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
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

<body class="gray-bg">

    <div class="middle-box-register loginscreen   animated fadeInDown">
        <div>
            <div class="text-center">

                <h1 class="logo-name"><img src="../assets/img/Practice-Std-Logo.png" /></h1>
				 <h3>Register to Practice Standard</h3>
            </div>
           
            <p>Create account to see it in action.</p>
            <form enctype="multipart/form-data" method="post" class="m-t" role="form" action="check_credentials.php">
               <div class="row">
	<section class="col-sm-6 col-md-6">

<div>
     <div class="padding-bottom-4">
       Name
     </div>
      
		<input type="text" placeholder="Name" id="txtDocName" name="txtDocName" required="required" class="form-control" />
     
   </div>
   
	<div>
     <div class="padding-bottom-4 padding-top-20">
      Country
     </div>
      
										<select class="form-control autotab" name="slctCountry" name="slctCountry" required="required" onchange="return getState(this.value);">
												<option value="India" selected>India</option>
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
     <div class="padding-bottom-4 padding-top-20">State</div>
     <select class="form-control autotab" name="slctState" id="slctState" required="required" placeholder="State"  >
	 <option value="" selected>Select</option>
												<?php
												$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id=100", "b.state_name asc", "", "", "");
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
     <div class="padding-bottom-4 padding-top-20">City</div>
      <input type="text" id="txtCity" name="txtCity" value="" class="form-control" />
                                            
    </div>
								<div>
									<div class="padding-bottom-4 padding-top-20">Specialization</div>
									<select class="form-control autotab" name="slctSpec" id="slctSpec" required="required" placeholder="State"  >
																					<option value="" >Select Specialization</option>
																					<?php $DeptName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
																					$i=30;
																					foreach($DeptName as $DeptList){
																						if($DeptList['spec_id']==$get_docInfo[0]['specialisation']){ ?> 
																					<option value="<?php echo stripslashes($DeptList['spec_name']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
																					<?php 
																						}?>

																						<option value="<?php echo stripslashes($DeptList['spec_name']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
																					<?php
																							$i++;
																					}?> 
									</select>
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Hospital Name</div>
									  <input type="text" id="txtHosp" name="txtHosp" value="" required="required" class="form-control" />
																			
									</div>
									
									<div>
									 <div class="padding-bottom-4 padding-top-20">Upload Registration Certificate</div>
									  <input type="file" id="txtregCert" name="txtregCert" value="" class="form-control" />
																			
									</div>



					</section>

					<section class="col-sm-6 col-md-6">
									
									<div>
									 <div class="padding-bottom-4">Qualification</div>
									  <input type="text" id="txtQual" name="txtQual" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Mobile No.</div>
									  <input type="text" id="txtMob" name="txtMob" value="" required="required" placeholder="10 digit mobile no." class="form-control"  maxlength="15" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Email Id</div>
									  <input type="email" id="txtEmail" name="txtEmail" value=""  required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Medical council name</div>
									  <input type="text" id="txtMedCouncil" name="txtMedCouncil" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Registration no.</div>
									  <input type="text" id="txtMedRegnum" name="txtMedRegnum" value="" required="required" class="form-control" />
																			
									</div>
									<div>
									 <div class="padding-bottom-4 padding-top-20">Password</div>
									  <input type="password" placeholder="password" id="passwd"  name="passwd" required="required" class="form-control" />
																			
									</div>
									
									<!--<div>
									 <div class="padding-bottom-4 padding-top-20">Upload Registration Certificate</div>
									  <input type="file" id="txtregCert" name="txtregCert" value="" class="form-control" />
																			
									</div>-->
									
				</section>
   
  </div>
                <div class="form-group">
                        <div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agree the terms and policy </label></div>
                </div>
                <button type="submit" name="register" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                <a class="btn btn-sm btn-white btn-block" href="login">Login</a>
            </form>
            <p class="m-t"> <small>Copyrights © 2017 Medisense Healthcare Solutions</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
</body>

</html>
