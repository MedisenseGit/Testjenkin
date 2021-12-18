<?php 
    ob_start();
    error_reporting(0);
    session_start(); 
	require_once("../classes/querymaker.class.php");
   $_SESSION['new_terms_condition'] = 0;        
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="MediSenseHealth is a online platform that provides medical second opinion, Medical tourism for international patients, Information on treatment like Ayurveda etc">
        <meta name="keywords" content="Best Medical Tourism Company in India, Medical treatment abroad, reliable medical tourism company, online medical opinion, Online Diagnosis, online medical advice">
        <title>Medisense - My Account</title>
        <meta property="og:image" content="https://medisense.me/new_assets/img/medisense_og.jpg" />
        <meta property="og:title" content="Medisense Health Solutions">
        <meta name="google-site-verification" content="7KU_CVFP21KnYciTjJtEIGoy_xH3nXF0Er39DtmA44A" />
        <meta name="msvalidate.01" content="7D01C13A2BA97CDB2C103CF763EC4AC5" />
        <meta property="og:site_name" content="Medisense Health Solutions">
        <meta property="og:url" content="https://medisense.me/">
        <meta property="og:description" content="MediSenseHealth is an online platform, which helps patients from all walks of life receive an unbiased second opinion from volunteering Medical experts who could be individuals or Institutions.">
        <meta property="fb:app_id" content="">
        <meta property="og:type" content="article">
        <?php include('include/meta-tags.php') ?>
       
          
    </head>
    <body>
        <!--==================== Header ====================-->
        <!-- top bar -->
        <?php include('include/inner-top-bar.php') ?>
        <!--//End top bar -->
		
        <?php //include('include/inner-top-nav.php') ?>
        <!-- Sub header -->
        <section class="space sub-header">
            <div class="container container-custom">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sub-header_content">
                            <p>Be a Panelist</p>
                            <h3>Doctor's Registration</h3>
                            <span><i>Home / Be a Panelist</i></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sub-header_main">
                            <h2>Be a Panelist</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--//End Sub header -->
		<link rel="stylesheet" href="css/bootstrap-drawer.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
		
        

        <!--//End Header -->
        <!--==================== Service Detail ====================-->
        <section class="light">
            <div class="container container-custom">
                <div class="row">                    
                    <div class="col-md-12 col-lg-12">
                        <div class="service-detail_wrap">
                            <div class="service-detail_img">
                               
							   <div class="row">
                                    <div class="col col-lg-12">
                                        <h2>Doctor's Registration Form</h2>        
                                    </div>
                                </div>
                                <hr>

                                <!-- Form Success Message -->
                                <?php
                                    if(isset($_GET['respond']))
									{
                                        switch($_GET['respond'])
										{
                                            case '0' : echo '<div class="alert alert-success" role="alert">Thank you, Your profile has been created successfully</div>';
                                            break;
                                            case '1' : echo '<div class="alert alert-danger" role="alert">Failed to submit your request!!!!! Since your profile already exist in our system</div>';
                                            break;
                                        }
                                    }
                                ?>
								<!-- End Success Message -->

								<form enctype="multipart/form-data"  action="send.php" method="post" id="vol_doctor"  >
									<div class="row">
                                        <div class="col-xs-6 col-md-2 col-sm-2 ">
                                            <img src="assets/img/profile_bg.png" height="120" class="img-fluid">
                                        </div>
                                        <div class="col-xs-6 col-md-10 col-sm-8" style="margin-top:40px;">
                                            <div class="form-group">
                                                <label for="exampleFormControlFile1">Add Profile Photo<span class="red">*</span></label>
                                                <input required type="file" class="form-control-file" id="profile_photo" name="txtPhoto" />
											</div>
                                        </div>
                                    </div>
									<hr/>
									
									<h3>General Information</h3>
									<div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2" >First Name<span class="red">*</span></label>
												<input autocomplete="true" type="text"  class="form-control" id="first_name" name="first_name" id="first_name" placeholder="First Name"  />
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Middle Name</label>
                                                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name" autocomplete="true" >
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2" >Last Name <span class="red">*</span></label>
                                                <input type="text" required class="form-control"  id="last_name" name="last_name" placeholder="Last Name" autocomplete="true">
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="inputState" >Gender<span class="red">*</span></label>
                                                <select required id="gender"  name="gender" class="form-control" autocomplete="true"  >
                                                    <option>  </option>
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                    <option>Other</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2" >DOB<span class="red">*</span></label>
                                                <input type="date" required class="form-control" id="dob"  name="dob" placeholder="DOB" value="" autocomplete="true">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2" id="error_file">Email<span class="red" >*</span></label>
                                                <input type="email" required  class="form-control" id="doc_email"  name="doc_email" aria-describedby="emailHelp" placeholder="Enter email" autocomplete="true">
                                            </div>
                                        </div>
                                    </div>
									<div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group ">
                                                <label for="specialization" >Specialization<span class="red">*</span></label>
                                                <select name="specialization[]" id="specialization" placeholder="" multiple required>
													<option value="18" >General Medicine / Internal Medicine</option>
                                                    <?php 
                                                        $SrcName= mysqlSelect("*","specialization","","spec_name asc","","","");
                                                        $i=30;
                                                        foreach($SrcName as $srcList){ ?>
                                                    <option value="<?php echo stripslashes($srcList['spec_id']);?>" />
                                                        <?php echo stripslashes($srcList['spec_name']);?>
                                                    </option>
                                                    <?php   $i++;
                                                        ?>
                                                    <?php 
                                                        }
                                                        
                                                        
                                                        
                                                        ?>   
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Years of Experience<span class="red">*</span></label>
                                                <input  type="text" required class="form-control" id="year_of_exp" name="year_of_exp" placeholder="Year of experience" autocomplete="true">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Consultation Languages(Select Multiple)<span class="red">*</span></label>
                                                <select name="consult_lang[]" id="consult_lang" placeholder="" multiple required >
													<option value="" >Select Languages</option>
                                                    <?php 
                                                        $SrcName1= mysqlSelect("*","languages","","","","","");
                                                        $i=30;
                                                        foreach($SrcName1 as $srcList){ ?>
                                                    <option value="<?php echo stripslashes($srcList['id']);?>" />
                                                        <?php echo stripslashes($srcList['name']);?>
                                                    </option>
                                                    <?php 
                                                        $i++;
                                                        }?>   
                                                </select>
                                            </div>
                                        </div>
                                    </div>
									
									<div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Country (Current)<span class="red">*</span></label>
                                                <input type="hidden" id="selected_country_id" name="selected_country_id">
                                                <select name="doc_country" required  class="form-control" id="doc_country" onchange="return getState(this.value);">
                                                    <option value="" myTag=""  selected>select</option>
                                                    <?php
                                                        $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
                                                            ?>
                                                    </option>
                                                    <?php
                                                        $i++;
														?>
														
                                                   <?php      }
                                                        ?>
                                                </select>
                                                <i></i>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">State (Current)<span class="red">*</span></label>
                                                <select name="doc_state"  class="form-control" id="doc_state" placeholder="State">
                                                    <option value='' selected>State</option>
                                                    <?php
                                                        $GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "a.country_id=100", "b.state_name asc", "", "", "");
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
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">City (Current)<span class="red">*</span></label>
                                                <input  type="text" class="form-control" id="city" name="city" placeholder="City" required autocomplete="true">
                                            </div>
                                        </div>
                                    </div>
									  <div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <label for="formGroupExampleInput2">Contact Number<span class="red">*</span></label>
                                            <div class="row">
                                                <div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
                                                    <select id="Country_code"   name="Country_code" class="form-control" >
                                                        <option value="" ></option>
                                                        <?php 
                                                            $SrcName1= mysqlSelect("*","countries","","","","","");
                                                                $i=30;
                                                                foreach($SrcName1 as $srcList){ ?>
                                                        <option value="<?php echo stripslashes($srcList['ph_extn']);?>" /> +
                                                            <?php echo stripslashes($srcList['ph_extn']);?>
                                                        </option>
                                                        <?php 
                                                            $i++;
                                                            }?>   
                                                    </select>
                                                </div>
                                                <div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
                                                    <input type="number"  class="form-control " id="Contact_num" name="Contact_num" placeholder="Contact Number" autocomplete="true"> 
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Alternative Contact Number </label>
                                                <div class="row">
                                                    <div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
                                                        <select id="alt_Country_code"  name="alt_Country_code" class="form-control">
                                                            <option value="" ></option>
                                                            <?php 
                                                                $SrcName1= mysqlSelect("*","countries","","","","","");
                                                                $i=30;
                                                                foreach($SrcName1 as $srcList){ ?>
                                                            <option value="<?php echo stripslashes($srcList['ph_extn']);?>" />+
                                                                <?php echo stripslashes($srcList['ph_extn']);?> 
                                                            </option>
                                                            <?php 
                                                                $i++;
                                                                }
                                                                ?>   
                                                        </select>
                                                    </div>
                                                    <div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
                                                        <input type="number" class="form-control no-spinner" id="alt_Contact_num" name="alt_Contact_num" placeholder="Alternative Contact No" autocomplete="true">  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Address (Current)<span class="red">*</span></label>
                                                <input  type="text" class="form-control" id="address"  name="address" placeholder="Address" required autocomplete="true">
                                            </div>
                                        </div>
                                    </div>
									
									<div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Country of Origin<span class="red">*</span></label>
                                                <select id="country_of_origin"  name="country_of_origin" class="form-control" required >
                                                    <option value="" >Select country</option>
                                                    <?php 
                                                        $SrcName= mysqlSelect("*","countries","","country_name asc","","","");
                                                        $i=30;
                                                        foreach($SrcName as $srcList){ ?>
                                                    <option value="<?php echo stripslashes($srcList['country_id']);?>" />
                                                        <?php echo stripslashes($srcList['country_name']);?>
                                                    </option>
                                                    <?php   $i++;
                                                        }?>   
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
									
									 <h3>Academic Information <button type="button" class="btn btn-primary academic_add_details" ><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
									 
									<div class="academic_user-details">
                                        <div class="academic_use_data">
                                            <div class="row" >
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Type of Qualification<span class="red">*</span></label>
                                                        <input  type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification" required autocomplete="true"> 
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Country<span class="red">*</span></label>
                                                        <select name="acd_doc_country[]"  class="form-control" id="doc_country" onchange="return getState(this.value);" required>
                                                            <option value="India" myTag="100"  selected>India</option>
                                                            <?php
                                                                $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
                                                                    ?>
                                                            </option>
                                                            <?php
                                                                $i++;
                                                                }
                                                                ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">City<span class="red">*</span></label>
                                                        <input  type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City" required autocomplete="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
                                                        <input  type="date" class="form-control" id="acd_Start_Date" name="acd_Start_Date[]" placeholder="Start Date" required>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">End Date</span></label>
                                                        <input 
                                                            type="date" class="form-control" id="acd_End_Date" name="acd_End_Date[]" placeholder="End Date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									
								
									<script>
									$(".academic_add_details").click(function(){
										
										$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
									});
									$("body").on("click",".remove-btn",function(e){
									
										$(this).parents('.academic_use_data').remove();
										//the above method will remove the user_data div
									});
									</script>
									
									<!--<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Internship Details <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Institution</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Institution">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Country</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Country">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">City</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="City">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Start Date</label>
                                                    <input type="date" class="form-control" id="formGroupExampleInput2" placeholder="Start Date">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">End Date</label>
                                                    <input type="date" class="form-control" id="formGroupExampleInput2" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>-->
                                    <!--<h4 style="padding-bottom:10px;padding-top:10px;color:#16B4B5;">Qualification Exam Information <button type="button" class="btn btn-primary" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h4>
                                        <div class="row">
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Examination ID</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="ExaminationID">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Date</label>
                                                    <input type="date" class="form-control" id="formGroupExampleInput2" placeholder="Date">
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-md-4 col-sm-4">
                                                <div class="form-group">
                                                    <label for="formGroupExampleInput2">Score</label>
                                                    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Score">
                                                </div>
                                            </div>
                                        </div>-->
										
                                    <hr/>
									<h3>Work History <button type="button" class="btn btn-primary add_work_his_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
                                    <div class="user-details1">
                                        <div class="work_his_data">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Institution Name<span class="red">*</span> </label>
                                                        <input  type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name" required autocomplete="true">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="inputState">Work Type<span class="red">*</span></label>
                                                        <select id="work_type"  name="work_type[]" class="form-control" required>
                                                            <option selected>Work Type</option>
                                                            <option>Clinic</option>
                                                            <option>Hospital</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Communication Address (Institution)<span class="red">*</span></label>
                                                        <input  type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address" required autocomplete="true">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <label for="formGroupExampleInput2">Phone Number (Institution)<span class="red"></span></label>
                                                    <div class="row">
                                                        <div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;">
                                                            <select id="Phone_Country_code"   name="Phone_Country_code[]" class="form-control" >
                                                                <option value="" ></option>
                                                                <?php 
                                                                    $SrcName1= mysqlSelect("*","countries","","","","","");
                                                                    $i=30;
                                                                    foreach($SrcName1 as $srcList){ ?>
                                                                <option value="<?php echo stripslashes($srcList['ph_extn']);?>" > 
                                                                    <?php echo stripslashes($srcList['ph_extn']);?>
                                                                </option>
                                                                <?php 
                                                                    $i++;
                                                                    }?>   
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;">
                                                            <input type="number"  class="form-control no-spinner" id="Phone_Number"  name="Phone_Number[]" placeholder="Contact Number">  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Start Date<span class="red">*</span></label>
                                                        <input required  type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">End Date</label>
                                                        <input   type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<hr/>
									<script type="text/javascript">
									// APPEND WORK DETAILS
									$(".add_work_his_details").click(function(){

										$(".user-details1").append('<div class="work_his_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Institution Name</label><input type="text" class="form-control" id="Institution_Name"  name="Institution_Name[]" placeholder="Institution Name"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="inputState">Work Type</label><select id="work_type"  name="work_type[]" class="form-control"><option selected>Work Type</option><option>Clinic</option><option>Hospital</option></select></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Communication Address (Institution)</label><input type="text" class="form-control" id="Communication_Address"  name="Communication_Address[]" placeholder="Communication Address"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><label for="formGroupExampleInput2">Phone Number (Institution)<span class="red">*</span></label><div class="row"><div class="col-xs-4 col-md-4 col-sm-4" style="padding-right:5px;"><select id="Phone_Country_code"  name="Phone_Country_code[]" class="form-control" ><option value="" ></option><?php $SrcName1= mysqlSelect("*","countries","","","","","");$i=30; foreach($SrcName1 as $srcList){ ?><option value="<?php echo stripslashes($srcList["ph_extn"]);?>" > <?php echo stripslashes($srcList["ph_extn"]);?></option><?php $i++; } ?>   </select></div><div class="col-xs-8 col-md-8 col-sm-8" style="padding-left:0px;"><input type="number" class="form-control no-spinner" id="Phone_Number" name="Phone_Number[]" placeholder="Contact Number">  </div></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="work_Start_Date"  name="work_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="work_End_Date" name="work_End_Date[]" placeholder="End Date"></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
									});
									$("body").on("click",".remove-btn",function(e){
										$(this).parents('.work_his_data').remove();
										//the above method will remove the user_data div
									});
									</script>
									<h3>Other Information</h3>
									 <div class="row">
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Area of Interest<span class="red">*</span></label>
                                                <textarea required class="form-control" rows="5"  id="Area_of_interest" name="Area_of_interest" placeholder="Enter area of your interest..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Professional Contribution</label>
                                                <!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Professional Construction"> -->
                                                <textarea class="form-control" rows="5" id="Professional_Contribution" name="Professional_Contribution" placeholder="Enter your professional contribution..."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group" style="margin-top:50px;">
                                                <label for="exampleFormControlFile1">Professional Contribution</label>
                                                <input type="file" class="form-control-file" id="Professional_Contribution_file" name="txtProfessional_Contribution_file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Research Details</label>
                                                <!--<input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Research Details"> -->
                                                <textarea class="form-control" rows="5" id="Research_Details" name="Research_Details" placeholder="Enter your research details..."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group" style="margin-top:50px;">
                                                <label for="exampleFormControlFile1">Research Details</label>
                                                <input type="file" class="form-control-file" id="Research_Details_file" name="txtResearch_Details_file">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Publications</label>
                                                
                                                <textarea class="form-control" rows="5" id="Publications" name="Publications" placeholder="Enter publications if any..."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-6 col-sm-6">
                                            <div class="form-group" style="margin-top:50px;">
                                                <label for="exampleFormControlFile1">Publications</label>
                                                <input type="file" class="form-control-file" id="Publications_file" name="txtPublications_file">
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
									<h3>Registration History <button type="button" class="btn btn-primary add_details" style="background-color: #16B4B5;"><i class="fa fa-plus" aria-hidden="true"></i>  Add New</button></h3>
                                    <div class="user-details">
                                        <div class="use_data">
                                            <div class="row">
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Medical Council registered with<span class="red">*</span></label>
                                                        <input type="text" required  class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="formGroupExampleInput2">Registration Number<span class="red">*</span></label>
                                                        <input required type="text" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number">
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-md-4 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="exampleFormControlFile1">Upload Registration Certificate<span class="red">*</span></label>
                                                        <input type="file" required  class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]">
                                                    </div>
                                                </div>
                                            </div>
											<div class="row">
												<div class="col-xs-12 col-md-4 col-sm-4">
													<div class="form-group">
														<label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label>
														<input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value="<?php echo $doctor_registration_list['reg_date']; ?>">
													</div>
												</div>
											</div>
											
                                        </div>
                                    </div>
                                    <hr/>
									<script type="text/javascript">
										//APPEND DETAILS
										$(".add_details").click(function(){		
											$(".user-details").append('<div class="use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Medical Council Registered with</label><input type="text" class="form-control" id="Medical_Council_reg" name="Medical_Council_reg[]" placeholder="Medical Council Registered with"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Number</label><input type="number" class="form-control" id="Reg_Num" name="Reg_Num[]"  placeholder="Registration Number"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="exampleFormControlFile1">Upload Registration Certificate</label><input type="file" class="form-control-file" id="Upload_Reg_cer" name="txtUpload_Reg_cer[]"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Registration Date<span class="red">*</span></label><input   type="date" class="form-control" id="Registration_Date"  name="Registration_Date[]" placeholder="Registration Date" value=""></div></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
										});

										$("body").on("click",".remove-btn",function(e){
											$(this).parents('.use_data').remove();
											//the above method will remove the user_data div
										});
									
									</script>
									
									 <h3>Passport Information</h3>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Passport Number<span class="red">*</span></label>
                                                <input required type="text"  class="form-control" id="passport_num" name="passport_num" placeholder="Passport Number">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Passport - Country<span class="red">*</span></label>
                                                <select required id="passport_country"   name="passport_country" class="form-control">
                                                    <option value="India" myTag="100"  selected>India</option>
                                                    <?php
                                                        $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
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
                                                            ?>
                                                    </option>
                                                    <?php
                                                        $i++;
                                                        }
                                                        ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="exampleFormControlFile1">Upload Passport<span class="red">*</span></label>
                                                <input required type="file"  class="form-control-file" id="txtpassport_file" name="txtpassport_file" >
                                            </div>
                                        </div>
                                    </div>
									<hr/>
									<!------------------------------------------------  Location  Information  ------------------------------------------->
									
									<h3>Location Information</h3>
									<div class = "row" >
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Latitude<span class="red">*</span></label>
												<input  type="text"  class="form-control" id="geo_latitude" name="geo_latitude" placeholder="Latitude" value="">
											</div>
										</div>
										<div class="col-xs-12 col-md-4 col-sm-4">
											<div class="form-group">
												<label for="formGroupExampleInput2">Longitude<span class="red">*</span></label>
												<input  type="text"  class="form-control" id="geo_longitude" name="geo_longitude" placeholder="Longitude" value="">
											</div>
										</div>
									</div>
									
									<div class="hrline" style=" border-top: 1px dashed gray;margin-top:20px;padding-bottom:20px;"></div>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2">Password<span class="red">*</span></label>
                                                <input required type="password" class="form-control" id="Password"  name="Password" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label for="formGroupExampleInput2"  class="error_pass">Confirm Password</label>
                                                <input required type="password" class="form-control" id="Confirm_Password" name="Confirm_Password" placeholder="Confirm Password" onkeyup="validate();">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="subject"><span id="new_terms_condition" class="error"></span></label>
                                                <input required type="checkbox" name="new_terms_condition" id="new_terms_condition" value="" ><a href="empanel-terms" target="_blank"> Terms and condition</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="subject"><span id="new_terms_condition2" class="error"></span></label>
                                                <input type="checkbox" name="new_terms_condition2" id="new_terms_condition" value="" required> I agree that my undergraduate/postgraduate medical qualification(s) are registered with the medical council.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="subject"><span id="new_terms_condition3" class="error"></span></label>
                                                <input type="checkbox" name="new_terms_condition3" id="new_terms_condition" value="" required > I agree that I have not been held guilty of medical negligence by a court of law or by the medical council.
                                            </div>
                                        </div>
                                    </div>		
									
									
									
									<div class="row form-group center">
                                        <input type ="submit" name="submit" value="Complete Registration" onclick='return validationFun();'  class="btn btn-primary" style="background-color: #16B4B5;padding-top:10px;padding-bottom:10px;padding-left:40px; padding-right:40px;margin-top:20px; font-size: 20px;">
                                    </div>
									
								
								</form>
							   
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--//End Service Detail -->
        <!--==================== Footer ====================-->
		<script src="js/bootstrap-drawer.js"></script>
		<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
		<!-- Script Js -->
		<script src="js/script.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<!-- Slick Slider Js -->
		<script src="js/slick.min.js"></script>
		<script src="js/jquery.magnific-popup.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
		
		<script type="text/javascript">
		$('#my-drawer').drawer('toggle');
		$(document).ready(function(){
			/*var multipleCancelButton = new Choices('#choices-multiple-remove-button', {
				removeItemButton: true,
				maxItemCount:5,
				searchResultLimit:5,
				renderChoiceLimit:5
			});*/

			var multipleCancelButton1 = new Choices('#specialization', {
				removeItemButton: true,
				//maxItemCount:5,
				//searchResultLimit:5,
				renderChoiceLimit:15 
				
			});

			var multipleCancelButton2 = new Choices('#consult_lang', {
				removeItemButton: true,
				//maxItemCount:5,
				//searchResultLimit:5,
				renderChoiceLimit:15          
			});
		
		});
		
		function getState(val) 
		{
			var data_val = $("#doc_country option:selected").attr("myTag");
			$('#sel_country_id').val(data_val);
			$('#selected_country_id').val(data_val);
			$.ajax({
				type: "POST",
				url: "get_state.php",
				data:{"country_name":data_val},
				success: function(data)
				{
					var val=data.split("@");
					$("#doc_state").html(val[0]);
					$("#Country_code").html(val[1]);
					$("#alt_Country_code").html(val[1]);
				}
			});
		}
		function validate(){
		var password = $('#Password').val();//document.getElementById("password")
		var  confirm_password = $('#Confirm_Password').val();//document.getElementById("confirm_password");

		if(password != confirm_password){
			$('.error_pass').html("<span class='text-danger'><i class='fas fa-times'></i> Passwords don't match</span>");
			//class="error"
			return false;
		}
		$('.error_pass').html("<span class='text-success'><i class='fas fa-check'></i> Passwords Match</span>");
	}
		</script>
		
			<script>
		//APPEND ACADEMIC DETAILS
		/*$(".academic_add_details").click(function(){
			alert("hhhh");
			
			$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div></div></div>');
			
			
			//$(".academic_user-details").append('<div class="academic_use_data"><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Type of Qualification</label><input type="text" class="form-control" id="Type_of_qualification" name="Type_of_qualification[]" placeholder="Type of qualification"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Country</label><select name="acd_doc_country[]" class="form-control" id="doc_country" onchange="return getState(this.value);"><option value="India" myTag="100"  selected>Select</option><?php $CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");$i= 30; foreach ($CntName as $CntNameList) {?> <option   myTag="<?php echo stripslashes($CntNameList["country_id"]); ?>" value="<?php echo stripslashes($CntNameList["country_name"]); ?>" ><?php echo stripslashes($CntNameList["country_name"]); ?></option><?php $i++; } ?></select><i></i></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">City</label><input type="text" class="form-control" id="acd_City" name="acd_City[]" placeholder="City"></div></div></div><div class="row"><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">Start Date</label><input type="date" class="form-control" id="Start_Date" name="acd_Start_Date[]" placeholder="Start Date"></div></div><div class="col-xs-12 col-md-4 col-sm-4"><div class="form-group"><label for="formGroupExampleInput2">End Date</label><input type="date" class="form-control" id="End_Date" name="acd_End_Date[]" placeholder="End Date"></div></div></div><button class="remove-btn btn btn-danger" data-toggle="tooltip" data-placement="top" title="Delete" style="float: right;"><i class="fas fa-trash" style="color:red" aria-hidden="true"></i></button></div>');
		});

		$("body").on("click",".remove-btn",function(e){
		
			$(this).parents('.academic_use_data').remove();
			//the above method will remove the user_data div
		});*/
		</script>
	
    </body>
</html>

