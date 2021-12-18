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
$getDocInfo = $objQuery->mysqlSelect("*","our_partners","md5(partner_id)='".$docid."'","","","","");
		
$_SESSION['docid']=$getDocInfo[0]['partner_id'];
$_SESSION['docname']=$getDocInfo[0]['contact_person'];
$_SESSION['docspec']=$getDocInfo[0]['specialisation'];	


$getDocSpec= $objQuery->mysqlSelect("*","specialization","spec_id='".$getDocInfo[0]['specialisation']."'","","","","");

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
    

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

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
	data:{"doc_val":val},
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
	<strong><?php echo $getDocInfo[0]['partner_name']; ?></strong>
<span><?php if(!empty($getDocInfo[0]['Address'])){ echo $getDocInfo[0]['Address'];?><br><?php } ?><?php echo $getDocInfo[0]['location']; ?>,<?php echo $getDocInfo[0]['state'].", ".$getDocInfo[0]['country']; ?></span>
</div>
</div>
</div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">

<div class="top_info_box">

<div class="icon">
<i class="fa fa-phone"></i>
</div>
<div class="text">

<strong><?php echo $getDocInfo[0]['cont_num1']; ?></strong>

</div>
</div>
</div>

			  </div>
						</div>
					</div>
					</header>
  
  
    <hr class="blue">
	 <div class="container ">
			<div class=" ">
	 <div class="row ">
	 <div class="">
	
<section>
	
	
<div>

<?php if($_GET['response']=="appointment") { ?>
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
	 
	
	<div id="appointment" class="tab-pane fade timeline-main ">
                            <br><br>
							
										<div class="user-form ">
                            <form enctype="multipart/form-data" class="medisene-form" action="get_data.php" method="post" id="appointment_form" >
                             <input type="hidden" name="doc_ency_id" value="<?php echo $_GET['d']; ?>" />
							 <input type="hidden" name="client_src" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />		
									<div class="row">

										<div class="col-xs-6 col-md-4">
										  <div class="form-group">
											 <label>Choose Preferred Date</label>
											 <label class="select">
									   <select name="check_date" id="check_date" required="required" onchange="return getDocTiming(this.value);">
											<option disabled="disabled" selected value="0">Select Date</option>
                                     <?php 										 
										for($i=1; $i<=20; $i++) { ?>
                                        
                                    <?php $date = strtotime('+' . $i . 'day');
									$chkdate=date('D', $date);
									$getDocDays= $objQuery->mysqlSelect("DISTINCT(b.day_id) as DayId","ref_doc_time_set as a left join seven_days as b on a.day_id=b.day_id","a.doc_id='".$getDocInfo[0]['partner_id']."'","","","","");
									
									   $current_date=date('d-m-Y', $date);
									   $date_1 = new DateTime($current_date);
									   $current_time_stamp=$date_1->format("U"); 
									  

									   $check_holiday=0; 
									 
									
									   foreach($getDocDays as $daylist) { 
									   $getDayName= $objQuery->mysqlSelect("*","seven_days","day_id='".$daylist['DayId']."'","","","","");
									
									   ?>

									<?php 
									if(date('D', $date)==$getDayName[0]['da_name']){ ?>
                                     <option value="<?php echo date('Y-m-d', $date);?>" >
                                         
                                         <?php
                                            echo date('D d-m-Y', $date);
                                         ?>
                                         </option>
                                     <?php 
									}
									   }
									 } 
										 
									
									 ?>



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
																  <input class="radio" type="radio" name="se_gender" id="se_gender" value="2" >
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
										<button type="submit" value="GET APPOINTMENT" class="btn-submit btn-form" name="ref_appointment" id="ref_appointment">GET APPOINTMENT</button>
																	  
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
<?php if(empty($getDocInfo[0]['doc_photo'])){ ?>
<img src="assets/img/doc_icon.jpg" draggable="false" class="img-responsive single-img">
<?php } else { ?>
<img src="https://medisensecrm.com/Refer/partnerProfilePic/<?php echo $getDocInfo[0]['partner_id']; ?>/<?php echo $getDocInfo[0]['doc_photo']; ?>" alt=""  draggable="false" class="img-responsive single-img">
 <?php } ?>

  <a data-toggle="tab" href="#appointment"><button type="submit" value="GET APPOINTMENT" class="btn-submit btn-form" name="ref_appointment" id="ref_appointment">BOOK AN APPOINTMENT</button></a>
                               
 </div>
<div class="col-md-10 col-sm-10 pull-right">
 <div class="padd-left-10">

  <h3 class="panel-name black"><?php echo $getDocInfo[0]['contact_person']; ?></h3>
	 <ul class="single-list ">
 <li  class="panel-exp black"><?php echo $getDocInfo[0]['doc_qual']; ?></li>
 <li ><?php if(!empty($getDocInfo[0]['ref_exp'])){ ?><strong class="black">Experience :</strong><?php echo $getDocInfo[0]['ref_exp']; ?> Years<?php } ?></li>
 <li ><?php if(!empty($getDocSpec[0]['spec_name'])){ ?><strong class="black">Specialization  :</strong><?php echo $getDocSpec[0]['spec_name']; ?><?php } ?></li>
 </ul>

 <ul class="single-list">
 
 <li><strong class="black">Address :</strong><?php echo $getDocInfo[0]['partner_name']; ?><br><?php if(!empty($getDocInfo[0]['location'])){ echo $getDocInfo[0]['location'];?><br><?php } ?><?php echo $getDocInfo[0]['state']; ?>,<?php echo $getDocInfo[0]['country']; ?></li>
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
            'allowedFileExtensions' : ['jpg','jpeg', 'png','gif'],
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