<?php ob_start();
 error_reporting(0);
 session_start(); 

 
//connect to the DB
require_once("../classes/querymaker.class.php");
$objQuery = new CLSQueryMaker();

	$_SESSION['new_terms_condition']=0;
?>

<!DOCTYPE html>
<html lang="en">
   <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	  <meta name="description" content="">
      <meta name="keywords" content="">
		 <title>Medisense-Healthcare Solutions</title>
         <?php include_once("support.php"); ?>
		 
		 <link href="jquery-ui.css" rel="stylesheet">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
 <link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
 <script>
function getState(val) {
	

	var data_val = $("#doc_country option:selected").attr("myTag")
	$('#sel_country_id').val(data_val);
	
	
	$.ajax({
	type: "POST",
	url: "get_state.php",
	data:{"country_name":val},
	success: function(data){
		$("#doc_state").html(data);
	}
	});
}

</script>

 <script>
function getHospCountry(val) {
	

	var data_val = $("#hospital_country option:selected").attr("myHospTag")
	$('#sel_hospCountry_id').val(data_val);
	
	
	
}

</script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
   <script>
  $( function() {
    $( "#datepicker" ).datepicker();
  } );
  </script>
  
 <!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<script type="text/javascript">
    $(function () {
        $("#chkTeleOp").click(function () {
            if ($(this).is(":checked")) {
                $("#get-telenum").show();
				$("#get-timing").show();
            } else {
                $("#get-telenum").hide();
				$("#get-timing").hide();
				
            }
        });
    });
	
	$(function () {
        $("#chkVideoOp").click(function () {
            if ($(this).is(":checked")) {
                $("#get-videonum").show();
				$("#get-timing").show();
				$("#get-video-detail").show();				  
            } else {
                $("#get-videonum").hide();
				$("#get-timing").hide();
				$("#get-video-detail").hide();				  
            }
        });
    });
</script>
	 </head>
	
   <body >
   <?php 
   $get_provInfo = $objQuery->mysqlSelect("*","referal ","enc_key='".$_GET['ency_id']."'","","","","");

	
	if($get_provInfo==true){
		
   ?>
    <div class="header">
     <div class="container ">
	 <div class="row ">
		<div class="col-sm-12 col-xs-12 ">
			<div class="center mar_top_10 img-responsive ">
				<!--<img src="assets/img/medisenselogo.jpg" class="" alt="Medisense-Healthcare">  -->
				<img src="../assets/img/Practice_Premium_Logo.png" class="" alt="Medisense-Practice">
				
				<ul class="social-widget ">

	<!--<li><a href="Register" class="dropdown-toggle" > Register</a>-->
  <li><a href="https://www.facebook.com/medisensehealthcom-1542369946078959/" class="facebook" title="Facebook" target="_blank">
  <i class="fa fa-facebook"></i>
  </a></li>

 
 <li><a href="https://twitter.com/medisenseworld" class="twitter" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
  
  </ul>
			</div>
		</div>
	 </div>
	 </div>
	</div>
<div class="home_slider">
		<!-- <div class="container pbg">
				<div class="bg-slider">
				<div class="col-sm-12 col-xs-12">
				<p class="camp_par  ">
				Here is a chance to save someone's life and may be yours, some day. Click below link and mention that great doctor's name who you worship, for having saved your or your loved ones's life.  Please fill the below form, We will consolidate and open it for patients to refer whenever they are down with that dreaded condition.
				</p>
				</div>
				
				</div>
		 </div>-->
		 
  <div class="container ">
		 
	<div class="sectionBox">
			<div class="row">
			<div class="col-md-12">
         <div class="col-md-6">
				<h3 class="life"><i class="fa fa-user-md red fa-2x"></i>
				<span>Registration Form</span></h3><p> </div>
				 <div class="col-md-5">
					<a href="https://medisensepractice.com" target="_blank" class="btn pull-right" style="background-color:#49C3EC;color:#fff;">
						<h3 class="font-bold">Medisense Practice</h3>
					</a>
                </div>
			</div>
			</div>
		<div><span class="sucess">
											
											<?php if(isset($_GET['respond'])){
												switch($_GET['respond']){
													case '0' : echo '<font color=green>Thank you, Your profile has been created successfully</font>';
													break;
													case '1' : echo '<font color=red>Failed to submit your request!!!!! Since your profile already exist in our system</font>';
													break;
												}
											}
											?></span>
        </div>
        <form enctype="multipart/form-data" class="" action="send.php" method="post" id="vol_doctor" novalidate="novalidate">
        <input type="hidden" name="docId" value="<?php echo $get_provInfo[0]['ref_id']; ?>" />
		<input type="hidden" name="docEncyId" value="<?php echo $get_provInfo[0]['enc_key']; ?>" />
		
		<div class="medisene-form">
		<div class="row">
		<div class="col-md-2 col-xs-12">
			<div style="border:2px solid #e1e0e0; padding:1px; width:138px; height:157px; margin-bottom:20px;" ><img src="doc_icon.jpg" width="130" height="150"/></div>
		 
                                                                 
		
		</div>
		<div class="col-md-2 col-xs-12" style="margin-top:40px;">
		<label for="file" class="textarea"><label class="label">Add Profile Photo </label>
                                                                    <input type="file" name="txtPhoto">

         </div>                                                       </label>
		</div><br>
        <div class="row">
		
		
                                                            
															 <div class="col-xs-12 col-md-4 col-sm-4">
															 											 
															 
                                                                <label class="label">Doctor name <span class="red">*</span></label>
                                                                <label class="input">
                                                                    <i class="icon-append fa fa-user"></i>
																	
                                                                    <input type="text" name="doc_name" id="autocomplete" value="" class="ui-autocomplete-input" autocomplete="off">
																	
                                                                </label>
                                                            </div>
															

                                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Specialization <span class="red">*</span></label>
                                                                     <label class="select">

                                                                        <select id="myselect1" name="specialization" class="specialization fr" onchange="return getSubSpecific(this.value);" >	
																		<option value="" >Select Specialization</option>
																		
																		<?php 
																		$SrcName= $objQuery->mysqlSelect("*","specialization","","spec_name asc","","","");
																			$i=30;
																			foreach($SrcName as $srcList){ ?>
																				
																				<option value="<?php echo stripslashes($srcList['spec_id']);?>" />
																				<?php echo stripslashes($srcList['spec_name']);?></option>
																			
																			
																			<?php 	$i++;
																			}?>   
																	</select>
                                                                        															 <i></i>
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Gender <span class="red">*</span></label>
																
															<label class="select">
                                                            <select name="doc_gender" id="doc_gender" class="select">
															<?php if($get_provInfo[0]['doc_gen']=="Male"){ ?>
															<option value="Male" selected>Male</option>
															<option value="Female">Female</option>
															<?php } else if($get_provInfo[0]['doc_gen']=="Female"){ ?>
															<option value="Female" selected>Female</option>
															<option value="Male">Male</option>
															<?php } else { ?>
																 <option value="" selected="">Select</option>
																<option value="Male">Male</option>
																<option value="Female">Female</option>
															<?php } ?>	 
															</select>
															 <i></i>
                                                        <!-- <input type="Text" name="se_status" id="se_status" placeholder="">
                                                        <b class="tooltip tooltip-bottom-right">Married/Unmarried/Widow</b>-->
                                                        </label>

															</div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Age </label>
																<label class="input">
                                                     
																	<input type="text" name="doc_age" id="doc_age" value="" placeholder="">
																</label>

															</div>
														                                                    
        </div><br>
		
		<div class="row">
															<div class="col-md-2 col-xs-12">
																<label class="label">Academic Qualification <span class="red">*</span></label>
															
                                                            <label class="input">
                                                     
																	<input type="text" name="doc_qual" value="" id="doc_qual" placeholder="">
																</label>
															 <i></i>
                                                        <!-- <input type="Text" name="se_status" id="se_status" placeholder="">
                                                        <b class="tooltip tooltip-bottom-right">Married/Unmarried/Widow</b>-->
                                                        </label>

															</div>
															<div class="col-md-2 col-xs-12">
																<label class="label">Year of Exp. <span class="red">*</span></label>
																<label class="input">
                                                     
																	<input type="text" name="doc_exp" value="" id="doc_exp" placeholder="">
																</label>

															</div>
															
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Email ID <span class="red">*</span></label>
                                                                     <label class="input">

																	<i class="icon-append fa fa-envelope"></i>
                                                                        <input type="email" name="doc_mail" value="" id="doc_mail" placeholder="">
                                                                        
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="label">Contact No.<span class="red">*</span></label>
                                                                     <label class="input">
																		<i class="icon-append fa fa-phone"></i>
                                                                        <input type="text" name="doc_contact" value="" id="doc_contact" placeholder="10 digit mobile no." maxlength="10" minlength="10">
                                                                        
                                                                    
                                                                    </label>
                                                                
                                                                </div>
                                                            </div>
															
															
															
                                                           
															 
		</div><br>
		<div class="row">
													   
															<div class="col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="label">Doctor's Country <span class="red">*</span></label>
                                                                    <label class="select">
																		<select name="doc_country" id="doc_country" onchange="return getState(this.value);">
																		<option value="India" myTag="100"  selected>India</option>
																		<?php
$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
$i       = 30;
foreach ($CntName as $CntNameList) {
?> 
										
													<option   myTag="<?php
    echo stripslashes($CntNameList['country_id']);
?>" value="<?php
    echo stripslashes($CntNameList['country_name']);
?>" />
													<?php
    echo stripslashes($CntNameList['country_name']);
?></option>
												
													<?php
    $i++;
}
?>
																	</select>
																	<i></i>
																</label>

                                                                </div>
                                                            </div>
															
															<div class="col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="label">Doctor's State <span class="red">*</span></label>
																	<input type="hidden" id="sel_country_id" name="sel_country_id" value="100" />
                                                                    <label class="select">
																	
                                                                       <select name="doc_state" id="doc_state" placeholder="State">
                                                                            
																			<option value='' selected>State</option>
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
                                                                    </label>

                                                                </div>
                                                            </div>
															<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Doctor's City <span class="red">*</span></label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="doc_city" id="doc_city" value="">
                                                                </label>
                                                            </div>
        </div><br>
		<div class="row">
		<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Website Address </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="doc_website" id="doc_website" value="">
                                                                </label>
                                                            </div>
		
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Online Opinion Charge</label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="online_charge" id="online_charge" placeholder="" value="">
                                                                </label>
                                                            </div>
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Inperson Opinion Charge </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="inper_charge" id="inper_charge" placeholder="" value="">
                                                                </label>
                                                            </div>
			<div class="col-md-2 col-xs-12">
                                                                <label class="label">Consultation Charge</label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="cons_charge" id="cons_charge" placeholder="" value="">
                                                                </label>
                                                            </div>
		</div><br>
		<div class="row">
		<div class="col-xs-12 col-md-4 col-sm-4">
								<label class="label">Hospital/Clinic Name <span class="red">*</span></label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="hosp_name" id="hosp_name" placeholder="" value="">
                                                                </label>
			</div>
			
			<!-- <div class="col-md-4 col-sm-4 col-xs-12">
                                                                <div class="form-group">
                                                                    <label class="label">Hospital Country <span class="red">*</span></label>
																	<input type="hidden" id="sel_hospCountry_id" name="sel_hospCountry_id" value="100" />
                                                                    <label class="select">
																		<select name="hospital_country" id="hospital_country" onchange="return getHospCountry(this.value);">
																		<option value="India" myHospTag="100"  selected>India</option>
																		<?php
$CntName = $objQuery->mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
$i       = 30;
foreach ($CntName as $CntNameList) {
?> 
										
													<option   myHospTag="<?php
    echo stripslashes($CntNameList['country_id']);
?>" value="<?php
    echo stripslashes($CntNameList['country_name']);
?>" />
													<?php
    echo stripslashes($CntNameList['country_name']);
?></option>
												
													<?php
    $i++;
}
?>
																	</select>
																	<i></i>
																</label>

                                                                </div>
                                                            </div>  -->
			
			<div class="col-xs-12 col-md-4 col-sm-4">
								<label class="label">Hospital Contact No.<span class="red">*</span></label>
                                                                <label class="input">
                                                                 <i class="icon-append fa fa-phone"></i>
                                                                    <input type="text" name="hosp_phone" id="hosp_phone" placeholder="" value="">
                                                                </label>
			</div>
			<div class="col-xs-12 col-md-4 col-sm-4">
								<label class="label">Hospital email id<span class="red">*</span></label>
                                                                <label class="input">
                                                                <i class="icon-append fa fa-envelope"></i> 
                                                                    <input type="text" name="hosp_email" id="hosp_email" placeholder="" value="">
                                                                </label>
			</div>
			
			</div>
			<br>
			<div class="row">
			
			
			<div class="col-xs-12 col-md-4 col-sm-4">
								 <label for="file" class="textarea">
									<div class="field_wrapper"><div><label class="label">Communication Address: <span class="red">*</span></label><textarea rows="3" name="doc_hosp_address" id="doc_hosp_address" placeholder=""></textarea></div></div>
								
								</label>
			</div>
			<div class="col-xs-12 col-md-4 col-sm-4" >
								 <div class="form-group">
                                                                    <label class="label">Consultation Languages </label>
                                                                     <label class="select">

                                                                        <select id="consult_lang" class="chosen-select fr" name="consult_lang[]" multiple style="background: #F5F5F5;">	
																		<option value="" >Select Languages</option>
																		
																		<?php 
																		$SrcName1= $objQuery->mysqlSelect("*","languages","","","","","");
																			$i=30;
																			foreach($SrcName1 as $srcList){ ?>
																				
																				<option value="<?php echo stripslashes($srcList['id']);?>" />
																				<?php echo stripslashes($srcList['name']);?></option>
																			
																			
																			<?php 	$i++;
																			}?>   
																	</select>
                                                                        															 <i></i>
                                                                    
                                                                    </label>
                                                                
                                                                </div>
			</div>
			
			</div>
			
			<br>
		
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Area's of Interest, or Expertise <span class="red">*</span></label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="8" name="doc_expert" id="doc_expert" placeholder=""></textarea>

                                                                </label>
                                                            </div>
															
                                                            															 
        </div><br>
		<div class="row"></div><br>
		
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Professional Contributions <span class="red">*</span></label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="8" name="doc_contrubute" id="doc_contrubute" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div><br>
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Research Details </label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="8" name="doc_research" id="doc_research" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div><br>
		<div class="row">
															<div class="col-xs-12 col-md-8 col-sm-8">
                                                                <label class="label">Publications </label>
                                                                 <label for="file" class="textarea">
                                                                    <textarea rows="8" name="doc_publication" id="doc_publication" placeholder=""></textarea>

                                                                </label>
                                                            </div>
		</div><br>
		<div class="row">
			<div class="col-md-4">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition" class="error"></span></label>
                                       <b>Ready for tele consultation ? </b> <input type="checkbox" name="chkTeleOp" id="chkTeleOp" value="1">
                                    
                                  </div>
                              </div>
			<div id="get-telenum" class="col-md-4 col-xs-12" style="display: none;">
                                                                <label class="label">Tele Consultation contact number </label>
                                                                <label class="input">
                                                                 <i class="icon-append fa fa-phone"></i>
                                                                    <input type="text" name="tele_contact" id="tele_contact" placeholder="" value="">
                                                                </label>
                                                            </div>				  
		                                                
		</div><br>
		<div class="row">
			<div class="col-md-4">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition" class="error"></span></label>
                                      <b>Ready for video consultation ?</b> <input type="checkbox" name="chkVideoOp" id="chkVideoOp" value="1" >
                                    
                                  </div>
                              </div>
			<div id="get-videonum" class="col-md-4 col-xs-12" style="display: none;">
                                                                <label class="label">skype ID / Whatsapp video call number, Google hangout </label>
                                                                <label class="input">
                                                                 <i class="icon-append fa fa-phone"></i>
                                                                    <input type="text" name="video_contact" id="video_contact" placeholder="" value="">
                                                                </label>
                                                            </div>
		                                                
		</div><br>
		<div class="row">
			
			<div id="get-timing" class="col-md-8 col-xs-12" style="display: none;">
                                                                <label class="label">Available timings for tele/Video Opinion </label>
                                                                <label class="input">
                                                                 <i class="icon-append fa fa-clock-o"></i>
                                                                    <input type="text" name="available_time" id="available_time" placeholder="" value="">
                                                                </label>
                                                            </div>
		                                                
		</div><br>
		<div class="row">
   <div id="get-video-detail" class="col-md-12 col-xs-12" style="display: none;">
                                                                  <div class="form-group">
																					  
																	 <!--<div class="radio radio-info radio-inline">
																		   <input type="radio" id="inlineRadio1" value="1" name="logintype" checked="">
																							<label for="inlineRadio1"> Medisense Video Call </label>
																	  </div>
																	  <div class="radio radio-info radio-inline">
																		 <input type="radio" id="inlineRadio2" value="2" name="logintype">
																		 <label for="inlineRadio2"> Google Meet </label>
																	   </div>
																		<div class="radio radio-info radio-inline">
																		 <input type="radio" id="inlineRadio4" value="4" name="logintype">
																		 <label for="inlineRadio4"> Microsoft Team </label>
																	   </div>-->
																	
																	   <input type="radio" id="inlineRadio1" value="0" name="videotype" checked="">
																		<label for="inlineRadio1" style="margin-left: 5px;margin-right: 20px;">   Medisense Video Call </label>
																		<input type="radio" id="inlineRadio2" value="1" name="videotype">
																		 <label for="inlineRadio2" style="margin-left: 5px;margin-right: 20px;"> Google Meet </label>
																		
                                                               
                                                                <label class="input" style="display:inline-block;width:200px;">
                                                                 
                                                                    <input type="text" name="google_meet_link" id="google_meet_link" placeholder="Link" value="" style="width: 90%;">
                                                                </label>
																	  <input type="radio" id="inlineRadio4" value="2" name="videotype">
																		 <label for="inlineRadio4" style="margin-left: 5px;margin-right: 20px;"> Microsoft Team </label>
																		  
                                                                <label class="input" style="display:inline-block;width:200px;">
                                                                 
                                                                    <input type="text" name="micro_team_link" id="micro_team_link" placeholder="Link" value="" style="width: 90%;">
                                                                </label>
																</div>
                                                               
                                                            </div>
		                                                
		</div>
		<div class="row">  
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Password </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="password" name="doc_passwd" id="doc_passwd" value="">
                                                                </label>
                                                            </div>
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Confirm Password</label>
                                                                <label class="input">
                                                                 
                                                                    <input type="password" name="doc_passwd_confirm" id="doc_passwd_confirm" value="">
                                                                </label>
                </div>
		</div><br>
		<hr>
		<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">The medical council with which you are registered </label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="council_name" id="council_name" value="">
                                                                </label>
                </div>
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Registration no.</label>
                                                                <label class="input">
                                                                 
                                                                    <input type="text" name="reg_num" id="reg_num" value="">
                                                                </label>
                </div>
		</div><br>
	
		<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label class="label">Date of registration</label>
                                                                <label class="input">
                                                                <i class="icon-append fa fa-calendar"></i>
                                                                    <input type="text" name="date_registration" id="datepicker" value="">
                                                                </label>
                </div>
				<div class="col-xs-12 col-md-4 col-sm-4">
                                                                <label for="file" class="textarea"><label class="label">Upload reg. Certificate </label>
                                                                    <input type="file" name="uploadCertificate">

				</div>
		</div><br><br>
		<div class="row">
				<div class="col-xs-12 col-md-4 col-sm-4">
															<label class="label">Enter Coupon Code</label>
                                                                <label class="input">
                                                               <input type="text" name="txtcoupon" id="txtcoupon" value="">
				</div>
				
		</div>
		
		</div>
		<div class="row">
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition" class="error"></span></label>
                                      <input type="checkbox" name="new_terms_condition" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>><a href="empanel-terms" target="_blank"> Terms and condition</a>
                                    
                                  </div>
                              </div>										
		                                                
		</div>
		<div class="row">
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition2" class="error"></span></label>
                                      <input type="checkbox" name="new_terms_condition2" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>> I agree that my postgraduate medical qualification(s) are registered with the medical council.
                                    
                                  </div>
                              </div>										
		                                                
		</div>
		<div class="row">
			<div class="col-md-6">
                                  <div class="form-group">
                                    <label for="subject"><span id="new_terms_condition3" class="error"></span></label>
                                      <input type="checkbox" name="new_terms_condition3" id="new_terms_condition" value="<?php echo $_SESSION['new_terms_condition']; ?>" onChange="if(this.checked){value=1} else{value=0}"; <?php if($_SESSION['new_terms_condition_checked']== '1'){echo("checked");}?>> I agree that I have not been held guilty of medical negligence by a court of law or by the medical council.
                                    
                                  </div>
                              </div>										
		                                                
		</div>
		<div class="row">
																											
												
		 
													<div class="col-sm-4 pull-right">
                                                        <input type="submit" value="SUBMIT" name="submit" id="sumbit" class="form-control submit">
													</div>
		</div>
												
												
		
		</div></form>
	</div>
					
											
		
  </div>
</div>

	
	
<footer class="main-footer">
	<div class="copyright">
					
						<p>Copyrights © 2017 Medisense Healthcare Solutions Pvt. Ltd</p>
						
	</div>
				
</footer>	
   
   
    <script src="assets/js/bootstrap.min.js"></script>
 <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="assets/js/validation.js"></script>
<?php 
		
	}
	else{
		$error="Page Not Found";
	}

if(isset($error)){ ?>
	
	<h3><?php echo $error;  ?></h3>
	
											
											
     
	<form method="post" name="frmRequest" action="send.php">
	<input type="hidden" name="docId" value="<?php echo $get_provInfo[0]['ref_id']; ?>" />
	<input type="hidden" name="docEncyId" value="<?php echo $get_provInfo[0]['enc_key']; ?>" />
	<input type="hidden" name="docName" value="<?php echo $get_provInfo[0]['ref_name']; ?>" />
		<input type="submit" name="requestSubmit" value="Request to resend"/>
		
		<?php if($_GET['respond']==2){ 
											echo "<h4 style='color:red;'>Request Sent Successfully</h4>";
											 }
											?>
	</form>
<?php 
}

?>	
	<script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
		<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
</body>
</html>