<?php ob_start();
 error_reporting(0);
 session_start();

require_once("../classes/querymaker.class.php");
include('send_text_message.php');
include('send_mail_function.php');
include('push_notification_function.php');
//$ccmail="medical@medisense.me";
$objQuery = new CLSQueryMaker();
date_default_timezone_set('Asia/Kolkata');
$Cur_Date=date('Y-m-d h:i:s');
$docid=$_GET['d'];
$getDocInfo = $objQuery->mysqlSelect("*","referal","md5(ref_id)='".$docid."'","","","","");
		
$_SESSION['docid']=$getDocInfo[0]['ref_id'];
$_SESSION['docname']=$getDocInfo[0]['ref_name'];
$_SESSION['docspec']=$getDocInfo[0]['doc_spec'];	


$getDocSpec= $objQuery->mysqlSelect("*","specialization","spec_id='".$getDocInfo[0]['doc_spec']."'","","","","");
$getDocAddress= $objQuery->mysqlSelect("*","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","a.doc_id='".$getDocInfo[0]['ref_id']."'","","","","");	

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Medisense-Healthcare Solutions</title>

    <!-- Mobile Specific Metas================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- Bootstrap  -->
    <link type="text/css" rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Custom css -->
    <link rel="stylesheet" href="assets/owl-carousel/owl.carousel.css">
    <link type="text/css" rel="stylesheet" href="assets/css/style.css">
    <!-- Favicons================================================== -->
    <link rel="shortcut icon" href="assets/img/favicon.ico">
    <!-- Font awesome icons================================================== -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
   
   
   <link href="new_assets/css/style1.css" rel="stylesheet">
   <link href="new_assets/css/subpage.css" rel="stylesheet">
    
	<link href="fileupload/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="fileupload/js/fileinput.js" type="text/javascript"></script>
        <script src="fileupload/js/fileinput_locale_fr.js" type="text/javascript"></script>
        <script src="fileupload/js/fileinput_locale_es.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js" type="text/javascript"></script>
	  	
<script>
function getState(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#se_state").html(data);
	}
	});
}
function getState1(val) {
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#se_state1").html(data);
	}
	});
}
function getDocTiming(val) {
	$.ajax({
	type: "POST",
	url: "get_doc_timing.php",
	data:'day_val='+val,
	success: function(data){
		$("#check_time").html(data);
	}
	});
}
function getDate(val) {
	$.ajax({
	type: "POST",
	url: "get_doc_date.php",
	data:{"hosp_id":val},
	success: function(data){
		$("#check_date").html(data);
	}
	});
}
function setTime(val) {
	$.ajax({
	type: "POST",
	url: "set_doc_time.php",
	data:{"set_time":val},
	success: function(data){
		$("#set_time").html(data);
	}
	});
}
</script>	
</head>

<body>


<!-- top header -->
    <header class="header-main1">
  
  <!-- Bottom Bar -->
<div class="top_info_boxes1">
						<div class="container">
							<div class="row">
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
<div class="top_info_box">							
<span><a href="https://medisensecrm.com/SendRequestLink/"> 
<img src="assets/img/practice_logo.png" alt="Medisense-Leap" width="150"> </a></span>							
</div>
</div>
							
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
<div class="top_info_box">
<div class="icon">
<i class="fa fa-hospital-o"></i>
</div>
<div class="text">
	<strong><?php echo $getDocAddress[0]['hosp_name']; ?></strong>
<span><?php if(!empty($getDocAddress[0]['hosp_addrs'])){ echo $getDocAddress[0]['hosp_addrs'];?><br><?php } ?><?php echo $getDocAddress[0]['hosp_city']; ?>,<?php echo $getDocAddress[0]['hosp_state']; ?></span>
</div>
</div>
</div>
<!--<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

<div class="top_info_box">

<div class="icon">
<i class="fa fa-phone"></i>
</div>
<div class="text">

<strong>Open Hours</strong>

</div>
</div><br>
<div class="top_info_box">
<div class="icon">
<i class="fa fa-phone"></i>
</div>
<div class="text">

<strong>Open Hours</strong>

</div>
</div>
</div>-->

			  </div>
						</div>
					</div>
					</header>
  
  
    <hr class="blue">
	 <div class="container ">
			<div class=" ">
	 <div class="row ">
	 <div class="">
	 <ul class="nav nav-tabs">
                                <!--<li class="active"><a data-toggle="tab" href="#second"><i class="fa fa-medkit fa-2x hidden-sm hidden-md hidden-lg"></i><span class="hidden-xs">Second Opinion &nbsp;&nbsp;</span></a>
                                </li>-->
                                <!--<li class="active"><a data-toggle="tab" href="#appoint"><i class="fa fa-calendar fa-2x hidden-sm hidden-md hidden-lg"></i><span class="hidden-xs">Make an Appointment</span></a>
                                </li>-->
                                <li ><a data-toggle="tab" class="li-tab" href="#opinion"><p class="font12">GET MEDICAL OPINION</p></a>
                                </li>
                                <li><a data-toggle="tab" class="li-tab" href="#appointment"><p class="font12">BOOK AN APPOINTMENT</p></a>
                                </li>
                               


                            </ul>
<section>
	
	
<div>

<?php if($_GET['response']=="opinion") {  ?>
<h4><span style="color:green; font-weight:bold;"><i class="fa fa-check"></i> Your medical query has been successfully registered & referred to the doctor. You may receive a response within next 24 to 48 Hrs.</span><br></h2>
									<p><b>Date: </b><?php echo $_GET['curdate']; ?> <br>
									<b>Patient Id: </b>#<?php echo $_GET['patid']; ?> <br>
									<b>Patient Name: </b><?php echo $_GET['patname']; ?> <br>
									<b>Contact No.: </b><?php echo $_GET['patcontact']; ?> <br>
									<b>Email: </b><?php echo $_GET['patemail']; ?> <br></p>
<?php } if($_GET['response']=="appointment") { ?>
<h4><span style="color:green; font-weight:bold;"><i class="fa fa-check"></i> Your request for appointment with the doctor <?php echo $_GET['docname']; ?> has been successfully sent. You may receive a mail/call on the confirmed date and time within next 4 working Hrs.</span><br><br></h4>
									<p><b>Created Date: </b><?php echo $_GET['curdate']; ?> <br>
									<p><b>Visit Date & Time: </b><?php echo $_GET['visitdate']." / ".$_GET['visittime']; ?> <br>
									<b>Patient Id: </b>#<?php echo $_GET['patid']; ?> <br>
									<b>Patient Name: </b><?php echo $_GET['patname']; ?> <br>
									<b>Contact No.: </b><?php echo $_GET['patcontact']; ?> <br>
									<b>Email: </b><?php echo $_GET['patemail']; ?> <br></p>
<?php } ?>
</div>
</section>
	<!-- BEGIN TAB CONTENT --> 
	<div class="tab-content ">
	 <div id="opinion" class="tab-pane fade timeline-main ">
                            
							
										
										<br>
										<div class="user-form ">
                            <form enctype="multipart/form-data" class="medisene-form" action="get_data.php" method="post" id="second_opinion" >
                             
                                        <div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label  >Patient Name <span class="error-star">*</span></label>
                                                        <label class="input">
                                                         
                                                            <input type="text" name="se_pat_name" id="se_pat_name" required="required" placeholder="" validate>
                                                           
                                                        </label>
                                                    </div>               
                                                </div>
                                               <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label  >Age <span class="error-star">*</span></label>
                                                        <label class="input">
                                                            
                                                            <input type="text" name="se_pat_age" id="se_pat_age" required="required" placeholder="" >
                                                              
                                                        </label>
                                                    </div>               
                                                </div>
												<div class="col-md-2">
												 <div class="form-group">
                                                    <label  >Weight<span class="error-star"></span></label>
                                                    <label class="input">
                                                        
                                                        <input type="text" name="se_weight" id="se_weight" placeholder="">
                                                    </label>
                                                </div>
                                                </div>
                                                 
                                            </div>   
                                            
										<div class="row">
										<div class="col-md-4 ">
									   <div class="form-group">
											<label> Gender &nbsp;&nbsp; <span class="error-star">*</span></label>
											 <label class="radio radio-inline">
											  <input type="radio" name="se_gender" id="male" checked="checked" value="1" class="radio" >
												<i></i>Male
											   </label>
											<label class="radio radio-inline">
													  <input class="radio" type="radio" name="se_gender" id="female" value="2" >
																									<i></i>Female
											</label>
										</div>
										</div>
										<div class="col-md-4 ">
											 							   <div class="form-group">
											 <label> Hypertension &nbsp;&nbsp;<span class="error-star">*</span> </label>
											 <label class="radio radio-inline">
											  <input type="radio" name="se_hyper" id="option2" class="radio" value="1">
												<i></i>Yes
											   </label>
											<label class="radio radio-inline">
																									<input class="radio" type="radio" name="se_hyper" checked="checked" id="option4" value="0" >
																									<i></i>No
																								</label>
										</div>
										</div>
									<div class="col-md-4 ">
									 <div class="form-group">
									 <label> Diabetes  &nbsp;&nbsp; <span class="error-star">*</span></label>
									 <label class="radio radio-inline">
									  <input type="radio" name="se_diabets" value="1" id="option3" class="radio" >
										<i></i>Yes
									   </label>
									<label class="radio radio-inline">
																							<input class="radio" type="radio" name="se_diabets" checked="checked" id="option4" value="0">
																							<i></i>No
																						</label>
									 </div>
									 </div>
										 
										
									</div> 
									
							
										
                                      
									  <hr class="line">
                                        
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                  							   <div class="form-group">  <label  >Contact Person<br>(Decision maker for the patient) <span class="error-star">*</span></label>
                                                    <label class="input">

                                                        <input type="text" name="se_con_per" id="se_con_per" required="required"  placeholder=""  >
														
                                                    </label>
                                          </div>
                                          </div>
                                                <div class="col-md-4 ">
												 <div class="form-group">
                                                    <label  >Contact Number <span class="error-star">*</span><br><div class="hidden-xs hidden-sm">&nbsp;</div></label>
                                                    <label class="input">
                                                      
                                                        <input type="text" name="se_phone_no" id="se_phone_no" required="required" placeholder="10 digit Mobile No." maxlength="10" >
													
                                                   
												   </label>
                                                </div>
                                                </div>
												<div class="col-md-4 ">
                                                    <div class="form-group">
													<label  >E-mail <span class="error-star">*</span><br><div class="hidden-xs hidden-sm">&nbsp;</div></label>
                                                    <label class="input">
                                                              
                                                                <input type="email" name="se_email" id="se_email" required="required" placeholder=""  >
                                                              
                                                     </label>
                                                </div>
                                                </div>
												
										</div> 
										<div class="row">
										<div class="col-md-4  ">
										 <div class="form-group">
											<label>Select Your Country <span class="error-star">*</span></label>
                                                <label class="select">
													
                                                    <select name="se_country" id="se_country" onchange="return getState(this.value);"  >
													    <option value="India"  selected>India</option>
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
                                                    <i></i>
													<span id="se_country_err" class="error"></span>
                                                </label>
                                            </div>
                                            </div>
										<div class="col-md-4 ">
										 <div class="form-group">
										<label  >State <span class="error-star">*</span></label>
                                                <label class="select">
                                                    <select name="se_state" id="se_state" placeholder="State" required="required" >
													<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
																										<option value="<?php
														echo $StateList["state_name"];
													?>"><?php
														echo $StateList["state_name"];
													?></option>
																										<?php
													}
													?>
													</select>
													 <i></i> 
												<span id="se_state_err" class="error"></span>   
											   </label>
                                        </div>
                                        </div>
                                        <div class="col-md-4 ">
										 <div class="form-group">
										<label  >City <span class="error-star">*</span></label>
                                                <label class="input">
                                                    <input type="text" name="se_city" id="se_city" required="required" placeholder="City"  >
													
                                                </label>
                                        </div>
                                        </div>
										
										 </div>
										 <div class="row">
										 <div class="col-md-8 ">
										  <div class="form-group">
										<label>Address </label>
                                            <label  class="textarea">
                                               <textarea rows="3" name="se_address" id="se_address" required="required" placeholder="Address"  ></textarea>
                                             
											</label>
                                        </div>
                                        </div>
										
										
                                          </div>  
                                                                           
                         
									
									<hr class="line">
                                
										  <div class="row">
                                            
                                            <div class="col-md-4 ">
                                                <div class="form-group">
											   <label> Current Treating Doctor <span class="error-star">*</span></label>
											   <label class="input">
                                                    <input type="text" name="se_treat_doc" id="se_treat_doc" required="required" placeholder="Current Treating Doctor">
													 <!--<b class="tooltip tooltip-bottom-right">If you know</b>-->
                                                </label>
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-4 ">
                                                 <div class="form-group">
												<label>Current Treating Hospital <span class="error-star">*</span> </label>
												<label class="input">
                                                    <input type="text" name="se_treat_hosp" id="se_treat_hosp" required="required" placeholder="Current Treating Hospital">
													
                                                </label>
                                            </div>
                                            </div>
                                           
                                           
                                        </div>
										
										 										
                                      
										<div class="row">
										<div class="col-md-12">
										 <div class="form-group">
                                            <label for="file" class="textarea">Ask your question <span class="error-star">*</span>
                                               <textarea rows="4" name="se_query" id="se_query"  required="required" placeholder="Please mention your query to the doctors"  ></textarea>
											   <span id="se_des_err" class="error"></span> 
                                            </label>
                                        </div>
										</div>
										</div>
										
										<div class="row">
										<div class="col-md-12">
												<label class="input ">
											 	<i></i>Attach medical reports (Maximum file size : 10 mb)<br>( File format: jpg, jpeg, png)
												
											   </label>	
											<div class="attach-file"> 
												<p class="attach"><input type="file" id="file-3" name="file-3[]"  multiple="true"><br>
												</p>
											</div>
										</div>
										</div>
									<br>	
								<br>
								
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="checkbox" name="new_terms_condition" id="new_terms_condition" required="required" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; >
											<label for="check"><a href="https://medisensehealth.com/Terms-of-Use" target="_blank">Terms and condition</a></label>
																
										</div>
                                    </div> 	
								</div>
								
								<div class="row">
									<div class="col-md-4 ">
										 <div class="form-group">
										<button type="submit" value="SEND OPINION" class="btn-submit btn-form" name="second" id="second">SUBMIT</button>
																	  
										</div>
									</div>
								</div>
									<br>
									
                                </form>    
								
	</div>
	</div>
	
	<div id="appointment" class="tab-pane fade timeline-main ">
                            
							 <div >
										<span class="sucess">
											<?php
										if (isset($_GET['sec'])) {
											switch ($_GET['sec']) {
												case '0':
													echo 'Thank you. We will try to get back in 24-48 Hrs';
													break;
												case '1':
													echo 'Failed to submit your request. Please click browser Go Back button and reload Captcha Code, then enter captcha code properly';
													break;
											}
										}
										?>
										</span>
										</div>
										
										<br>
										<div class="user-form ">
                            <form enctype="multipart/form-data" class="medisene-form" action="get_data.php" method="post" id="appointment_form" >
                             <input type="hidden" name="client_src" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />		
									<div class="row">
										<div class="col-xs-6 col-md-4">
										  <div class="form-group">
											 <label>Select Hospital</label>
											 <label class="select">
									   <select name="selHosp" id="selHosp" required="required" onchange="return getDate(this.value);">
											<option  value="0">Select</option>
										<?php 										 
									
									   
									   $getDcoHosp= $objQuery->mysqlSelect("a.hosp_id as hosp_id, b.hosp_name as hosp_name,b.hosp_city as hosp_city","doctor_hosp as a left join hosp_tab as b on a.hosp_id=b.hosp_id","a.doc_id='".$getDocInfo[0]['ref_id']."'","","","","");
										foreach($getDcoHosp as $hospList) { 
									   ?>
										<option value="<?php echo $hospList['hosp_id'];?>" >
                                         
                                         <?php echo $hospList['hosp_name'];?>
                                         </option>
										<?php 
										}
										?>
									    </select>
												 <i></i>
											</label>
											</div>
										</div>
										<div class="col-xs-6 col-md-4">
										  <div class="form-group">
											 <label>Choose Preferred Date</label>
											 <label class="select">
									   <select name="check_date" id="check_date" required="required" onchange="return getDocTiming(this.value);">
											
									   </select>
												 <i></i>
											</label>
											</div>
										</div>
									   <div class="col-xs-6 col-md-4">
										  <div class="form-group">
											 <div class="form-group">
												<label>Choose Preferred Time</label>
																				  
												<label class="select">
												
												<select name="check_time" id="check_time" required="required" onchange="return setTime(this.value);" >
												
												</select>
												<i></i>
												</label>
											 </div>
										  </div>
									   </div>
									</div>
									  <hr class="line">
                                        <div class="row">
                                                <div class=" col-md-6">
                                                    <div class="form-group">
                                                        <label  > Patient Name <span class="error-star">*</span></label>
                                                        <label class="input">
                                                         
                                                            <input type="text" name="se_pat_name" id="se_pat_name" required="required" placeholder="" validate>
                                                           
                                                        </label>
                                                    </div>               
                                                </div>
                                               <div class="col-md-2 ">
                                                    <div class="form-group">
                                                        <label  >Age <span class="error-star">*</span></label>
                                                        <label class="input">
                                                            
                                                            <input type="number" name="se_pat_age" id="se_pat_age"required="required" placeholder="" maxlength="2" >
                                                              
                                                        </label>
                                                    </div>               
                                                </div>
												<div class="col-md-4 ">
												   <div class="form-group">
														<label> Gender &nbsp;&nbsp; <span class="error-star">*</span></label><br>
														 <label class="radio radio-inline">
														  <input type="radio" name="se_gender" id="male" checked="checked" value="1" class="radio" >
															<i></i>Male
														   </label>
														<label class="radio radio-inline">
																  <input class="radio" type="radio" name="se_gender" id="2" value="Female" >
																												<i></i>Female
														</label>
													</div>
												</div>
                                                 
                                            </div>   
                                            
										<div class="row">
										
										
										 
										
									</div> 
									
							
										
                                      
									  <hr class="line">
                                        
                                            <div class="row">
                                                <div class="col-md-4 ">
                                                  							   <div class="form-group">  <label  >Contact Person<br>(Decision maker for the patient) <span class="error-star">*</span></label>
                                                    <label class="input">

                                                        <input type="text" name="se_con_per" id="se_con_per" required="required"  placeholder=""  >
														
                                                    </label>
                                          </div>
                                          </div>
                                                <div class="col-md-4 ">
												 <div class="form-group">
                                                    <label  >Contact Number <span class="error-star">*</span><br><div class="hidden-xs hidden-sm">&nbsp;</div></label>
                                                    <label class="input">
                                                      
                                                        <input type="text" name="se_phone_no" id="se_phone_no" required="required" placeholder="10 digit Mobile No." maxlength="10" >
													
                                                   
												   </label>
                                                </div>
                                                </div>
												<div class="col-md-4 ">
                                                    <div class="form-group">
													<label  >E-mail <span class="error-star">*</span><br><div class="hidden-xs hidden-sm">&nbsp;</div></label>
                                                    <label class="input">
                                                              
                                                                <input type="email" name="se_email" id="se_email" required="required"  placeholder=""  >
                                                              
                                                     </label>
                                                </div>
                                                </div>
												
										</div> 
										<div class="row">
										<div class="col-md-4  ">
										 <div class="form-group">
											<label>Select Your Country <span class="error-star">*</span></label>
                                                <label class="select">
													
                                                    <select name="se_country" id="se_country" onchange="return getState1(this.value);"  >
													    <option value="India"  selected>India</option>
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
                                                    <i></i>
													<span id="se_country_err" class="error"></span>
                                                </label>
                                            </div>
                                            </div>
										<div class="col-md-4 ">
										 <div class="form-group">
										<label  >State <span class="error-star">*</span></label>
                                                <label class="select">
                                                    <select name="se_state1" id="se_state1" required="required" placeholder="State"  >
													<option value="">Select State</option>
													<?php
													$GetState = $objQuery->mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
													foreach ($GetState as $StateList) {
													?>
																										<option value="<?php
														echo $StateList["state_name"];
													?>"><?php
														echo $StateList["state_name"];
													?></option>
																										<?php
													}
													?>
													</select>
													 <i></i> 
												<span id="se_state_err" class="error"></span>   
											   </label>
                                        </div>
                                        </div>
                                        <div class="col-md-4 ">
										 <div class="form-group">
										<label  >City <span class="error-star">*</span></label>
                                                <label class="input">
                                                    <input type="text" name="se_city" id="se_city" required="required" placeholder="City"  >
													
                                                </label>
                                        </div>
                                        </div>
										
										 </div>
										 <div class="row">
										 <div class="col-md-8 ">
										  <div class="form-group">
										<label>Address </label>
                                            <label  class="textarea">
                                               <textarea rows="3" name="se_address" id="se_address"  placeholder="Address"  ></textarea>
                                             
											</label>
                                        </div>
                                        </div>
										
										
                                          </div>  
                                                                           
                         
										
										
											
										
						
								<!--<div class="row">
									<div class="col-md-12">
									 <div class="form-group">
									<p>
									<img src="captcha_code_file.php?rand=<?php
									echo rand();
									?>" id='captchaimg' ><br>
									<label for='message'>Enter the code above here :</label><br>

									<input id="letters_code" name="letters_code" type="text"><br>
									<small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
									</p>
									</div>
									</div>                                 
								</div>  -->  
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<input type="checkbox" name="new_terms_condition" required="required" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; >
											<label for="check"><a href="https://medisensehealth.com/Terms-of-Use" target="_blank">Terms and condition</a></label>
																
										</div>
                                    </div> 	
								</div>
								
								<div class="row">
									<div class="col-md-4 ">
										 <div class="form-group">
										<button type="submit" value="GET APPOINTMENT" class="btn-submit btn-form" name="appointment" id="appointment">GET APPOINTMENT</button>
																	  
										</div>
									</div>
								</div>
									<br>
									
                                </form>    
								
	</div>
	</div>
	
	</div>
	<!-- END TAB CONTENT -->
	  </div>
	  
	</div>
	
	</div>
		</div>
     
        <div class="container ">
     





            <div class="row  ">

                <div class="col-md-12  ">
                   <h3 class="black ">DOCTOR'S PROFILE</h2>

                </div>
				
				
            </div>

<div >
	<div class="container ">
	<div class="panel"  style="min-height:400px">
<div class="row">
<div class="col-md-2 col-sm-2 pull-left  padd-left-10">
<?php if(empty($getDocInfo[0]['doc_photo']) || $getDocInfo[0]['anonymous_status']==1){ ?>
<img src="assets/img/doc_icon.jpg" draggable="false" class="img-responsive single-img">
<?php } else { ?>
<img src="https://medisensecrm.com/Doc/<?php echo $getDocInfo[0]['ref_id']; ?>/<?php echo $getDocInfo[0]['doc_photo']; ?>" alt=""  draggable="false" class="img-responsive single-img">
 <?php } ?>
</div>
<div class="col-md-10 col-sm-10 pull-right">
 <div class="padd-left-10">

  <h3 class="panel-name black"><?php if($getDocInfo[0]['anonymous_status']==1) { echo "Anonymous";} else { echo $getDocInfo[0]['ref_name']; } ?></h3>
	 <ul class="single-list ">
 <li  class="panel-exp black"><?php echo $getDocInfo[0]['doc_qual']; ?></li>
 <li ><?php if(!empty($getDocInfo[0]['ref_exp'])){ ?><strong class="black">Experience :</strong><?php echo $getDocInfo[0]['ref_exp']; ?> Years<?php } ?></li>
 <li ><?php if(!empty($getDocSpec[0]['spec_name'])){ ?><strong class="black">Specialization  :</strong><?php echo $getDocSpec[0]['spec_name']; ?><?php } ?></li>
 </ul>

 <ul class="single-list">
 
 <?php if(!empty($getDocAddress) && $getDocInfo[0]['anonymous_status']==0 ){ ?><li><strong class="black">Address :</strong><?php echo $getDocAddress[0]['hosp_name']; ?><br><?php if(!empty($getDocAddress[0]['hosp_addrs'])){ echo $getDocAddress[0]['hosp_addrs'];?><br><?php } ?><?php echo $getDocAddress[0]['hosp_city']; ?>,<?php echo $getDocAddress[0]['hosp_state']; ?></li><?php } ?>
  <?php if(!empty($getDocInfo[0]['doc_interest'])){ ?><li><strong class="black">Area's of Interest/Expertise :</strong><?php echo $getDocInfo[0]['doc_interest']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_contribute'])){ ?><li><strong class="black">Professional Contribution :</strong><?php echo $getDocInfo[0]['doc_contribute']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_research'])){ ?><li><strong class="black">Research Details :</strong><?php echo $getDocInfo[0]['doc_research']; ?></li><?php } ?>
 <?php if(!empty($getDocInfo[0]['doc_pub'])){ ?><li><strong class="black">Awards / Publications :</strong><?php echo $getDocInfo[0]['doc_pub']; ?></li><?php } ?>
 
 </ul>
 	 
</div>
</div>


</div>
<div class="row">
<div class="col-md-12">
 
 
 </div>

</div>
</div>
</div>
	
	</div>



</div>

        

	</div>
	</div>
	</div>
 
    <footer class="main-footer">
        <div class="copyright">
            <div class="container text-center">
		
                Powered by Medisense Healthcare Solutions Pvt. Ltd.
                <br>
 <br>
            </div>
        </div>
    </footer>
    <!-- bottom bar-->
<script>
    $('#file-fr').fileinput({
        language: 'fr',
        uploadUrl: '#',
        allowedFileExtensions : ['jpg', 'png','gif'],
    });
    $('#file-es').fileinput({
        language: 'es',
        uploadUrl: '#',
        allowedFileExtensions : ['jpg', 'png','gif'],
    });
    $("#file-0").fileinput({
        'allowedFileExtensions' : ['jpg', 'png','gif'],
    });
    $("#file-1").fileinput({
        uploadUrl: '#', // you must set a valid URL here else you will get an error
        allowedFileExtensions : ['jpg', 'png','gif'],
        overwriteInitial: false,
        maxFileSize: 1000,
        maxFilesNum: 10,
        //allowedFileTypes: ['image', 'video', 'flash'],
        slugCallback: function(filename) {
            return filename.replace('(', '_').replace(']', '_');
        }
	});
    /*
    $(".file").on('fileselect', function(event, n, l) {
        alert('File Selected. Name: ' + l + ', Num: ' + n);
    });
    */
	$("#file-3").fileinput({
		showUpload: false,
		showCaption: false,
		allowedFileExtensions : ['jpg','jpeg','png','gif'],
		browseClass: "col-md-2 btn-submit btn-form btn-lg",
		overwriteInitial: false,
        maxFileSize: 2000,
        maxFilesNum: 5,
        previewFileIcon: "<i class='glyphicon glyphicon-king'></i>"
	});
	$("#file-4").fileinput({
		uploadExtraData: {kvId: '10'}
	});
    $(".btn-warning").on('click', function() {
        if ($('#file-4').attr('disabled')) {
            $('#file-4').fileinput('enable');
        } else {
            $('#file-4').fileinput('disable');
        }
    });    
    $(".btn-info").on('click', function() {
        $('#file-4').fileinput('refresh', {previewClass:'bg-info'});
    });
    /*
    $('#file-4').on('fileselectnone', function() {
        alert('Huh! You selected no files.');
    });
    $('#file-4').on('filebrowse', function() {
        alert('File browse clicked for #file-4');
    });
    */
    $(document).ready(function() {
        $("#test-upload").fileinput({
            'showPreview' : false,
            'allowedFileExtensions' : ['jpg', 'png','gif'],
            'elErrorContainer': '#errorBlock'
        });
        /*
        $("#test-upload").on('fileloaded', function(event, file, previewId, index) {
            alert('i = ' + index + ', id = ' + previewId + ', file = ' + file.name);
        });
        */
    });
	</script>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
	
}
</script>

<script src="new_assets/js/jquery-3.1.0.min.js"></script>
	  <script src="new_assets/js/bootstrap.min.js"></script>
	
	
	  <script src="new_assets/js/showHide.js"></script>
	  <script src="new_assets/js/icon.js"></script>
	  <script src="new_assets/js/validate.js"></script>
	 
 
  <script type="text/javascript" src="new_assets/js/jquery.autocomplete.js"></script>
  <script type="text/javascript" src="new_assets/js/bootstrap-select.js"></script>
  <script type="text/javascript" src="new_assets/js/autosize.js"></script>
 
	 
	 
	 
	<script type="text/javascript" src="new_assets/js/medisense.js"></script>
	<script type="text/javascript"
     src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js">
    </script> 
</body>


</html>