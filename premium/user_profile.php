<?php
ob_start();
error_reporting(0); 
session_start();

$admin_id = $_SESSION['user_id'];
//Get the page name 
$request_uri = str_replace("/premium/","",$_SERVER['REQUEST_URI']);
$param = explode("/",$request_uri);
$page_name = $param[0];

include('functions.php');
if(empty($admin_id))
{
	header("Location:index.php");
}

require_once("../classes/querymaker.class.php");
//$objQuery = new CLSQueryMaker();


$get_docInfo 	= mysqlSelect("*","referal as a left join specialization as b on a.doc_spec=b.spec_id","a.ref_id='".$admin_id."'","","","","");
$get_docSpec 	= mysqlSelect("*","doc_specialization as a left join specialization as b on a.spec_id=b.spec_id","a.doc_id='".$admin_id."'","","","","");

$get_provHospInfo = mysqlSelect("*","hosp_tab as a left join doctor_hosp as b on a.hosp_id=b.hosp_id","b.doc_id='".$admin_id."'","","","","");
                
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Profile</title>

   <?php include_once('support.php'); ?>
	<link href="../assets/css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">	
	<link href="../assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
	<link href="../assets/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
	<link href="../assets/css/plugins/iCheck/custom.css" rel="stylesheet">
	<link href="../assets/css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript">
					function getState(val) { 
						$.ajax({
						type: "POST",
						url: "get_state.php",
						data:'country_name='+val,
						success: function(data){
							$("#slctState").html(data);
						}
						});
					}
	</script>
	</head>

<body>

    <div id="wrapper">

    <?php include_once('sidemenu.php'); ?>

        <div id="page-wrapper" class="gray-bg">
        <?php include_once('header_top.php'); ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Profile</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="Home">Home</a>
                        </li>
                       
                        <li class="active">
                            <strong>Your Profile</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content">
            <div class="row animated fadeInRight">
			<?php if($_GET['response']=="update"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Your profile has been updated successfully </strong>
								 </div>
								<?php }  if($_GET['response']=="password"){ ?>
								<div class="alert alert-success alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Password has been updated successfully </strong>
								 </div>
								<?php } if($_GET['response']=="error-password"){ ?>
								<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Password mismatch, please try again </strong>
								 </div>
								<?php } if($_GET['response']=="spec_req"){ ?>
								<div class="alert alert-danger alert-dismissable">
											<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
										   <strong>Please update your specialization </strong>
								 </div>
								<?php }?>
                <div class="col-md-4">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Profile Detail</h5>
                        </div>
                        <div>
                            <div class="ibox-content border-left-right">
							<?php 
							if(!empty($get_docInfo[0]['doc_photo']))
							{ 
								$image = IMG_URL_VIEW."Doc/".$get_docInfo[0]['ref_id']."/".$get_docInfo[0]['doc_photo'];

							?>
                            <img alt="image" class="img-xlg img-responsive" src="<?php echo $image; ?>">
                            <?php 
							}  
							else 
							{ ?>
							 <img alt="image" class="img-xlg img-responsive" src="../assets/img/anonymous-profile.png">
                             <?php  } ?>
							</div>
                            <div class="ibox-content profile-content">
                                <h4><strong><?php echo $get_docInfo[0]['ref_name']; ?></strong><br><br><?php echo $get_docSpec[0]['spec_name']; if(!empty($get_docSpec[1]['spec_name'])){echo ", ".$get_docSpec[1]['spec_name'];} ?></h4>
                                <p><i class="fa fa-map-marker"></i> <?php if(!empty($get_provHospInfo[0]['hosp_name'])){ echo $get_provHospInfo[0]['hosp_name']; } if(!empty($get_docInfo[0]['ref_address'])){ echo ", ".$get_docInfo[0]['ref_address'];} if(!empty($get_docInfo[0]['doc_state'])){ echo ", ".$get_docInfo[0]['doc_state']; } if(!empty($get_docInfo[0]['doc_country'])){ echo ", ".$get_docInfo[0]['doc_country']; } ?></p>
                                <br><h4><strong>
                                    About <?php echo $get_docInfo[0]['ref_name']; ?>
                                </strong></h4>
                                <p><?php if(!empty($get_docInfo[0]['ref_exp'])){ ?><b>Exp:</b> <?php echo $get_docInfo[0]['ref_exp']; ?> Yrs<br><?php } ?>
								   
								  <!-- start Interest/Expertise -->
								<?php if(!empty($get_docInfo[0]['doc_interest'])){ ?>
								 <br><b>Area's of Interest/Expertise</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_interest']); ?><br>
								  <?php } ?>
								 <!-- end of Interest/Expertise -->
								 
								 <?php if(!empty($get_docInfo[0]['doc_contribute'])){ ?>
								 <br><b>Professional Contribution</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_contribute']); ?><br>
								 <?php } ?>
								<!-- end of Contribution -->
								
								 <?php if(!empty($get_docInfo[0]['doc_pub'])){ ?>
								 <br><b>Publications</b><br>
								 <?php echo stripslashes($get_docInfo[0]['doc_pub']); ?><br>
								 <?php } ?>
								<!-- end of Publications -->
								
								
                                </p>
                              
                            </div>
                    </div>
                </div>
                    </div>
                <div class="col-md-8">
                    <div class="ibox float-e-margins">
                        
                        <div class="ibox-content">
							<div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#"><i class="fa fa-pencil-square-o"></i>Edit Profile</a></li>
                            <li><a href="Set-Appointment"><i class="fa fa-calendar"></i>Set Appointment Timing</a></li>
                            <li><a href="Password"><i class="fa fa-key"></i>Change Password</a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
							
                                <div class="panel-body">
                               
                            <form enctype="multipart/form-data" action="add_details.php" method="post" class="form-horizontal" id="frmAddDoctor">
                                <input type="hidden" name="Prov_Id"	value="<?php echo $admin_id; ?>" />
								<div class="form-group"><label class="col-sm-2 control-label">Profile picture</label>

                                    <div class="col-sm-10"><!--<label title="Upload image file" for="inputImage" class="btn btn-primary">
                                        <input type="file" id="inputImage" name="txtPhoto" class="hide">
                                        <i class="fa fa-upload"></i> Upload profile picture
                                    </label>-->
										<div class="fileinput fileinput-new" data-provides="fileinput">
											<span class="btn btn-default btn-file"><span class="fileinput-new">Change Profile Picture</span><span class="fileinput-exists">Change</span><input type="file" name="txtPhoto"></span>
											<span class="fileinput-filename"></span>
											<a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
										</div>
									</div>
                                </div>
								<div class="form-group">
									<label class="col-sm-2 control-label">Name</label>

                                    <div class="col-sm-4"><input type="text" id="txtDoc" name="txtDoc" value="<?php echo $get_docInfo[0]['ref_name']; ?>" class="form-control"></div>
                                
									<label class="col-sm-2 control-label">Years of Exp.</label>

                                    <div class="col-sm-4"><input type="text" id="txtExp" name="txtExp" value="<?php echo $get_docInfo[0]['ref_exp']; ?>" class="form-control"></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Mobile 1 <span class="required">*</span></label>

                                    <div class="col-sm-4"><input type="text" class="form-control" id="txtMobile" name="txtMobile" value="<?php echo $get_docInfo[0]['contact_num']; ?>" placeholder="10 digit mobile no." maxlength="10"></div>
									<label class="col-sm-2 control-label">Mobile 2</label>

                                    <div class="col-sm-4"><input id="txtMobile2" name="txtMobile2" value="<?php echo $get_docInfo[0]['secondary_contact_num']; ?>" placeholder="10 digit mobile no." class="form-control" maxlength="10"></div>
								
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Email</label>

                                    <div class="col-sm-10"><input id="txtEmail" name="txtEmail" value="<?php echo $get_docInfo[0]['ref_mail']; ?>" class="form-control"></div>
								
                                </div>
								
								<!--<div class="form-group">
								<label class="col-sm-2 control-label">Gender </label>
                                      <div class="col-sm-10">  
									  <?php if($get_docInfo[0]['doc_gen']==1){ ?>
									  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="docGender" checked="">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="docGender">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									  <?php } else{ ?>
									  <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio1" value="1" name="docGender">
                                            <label for="inlineRadio1"> Male </label>
                                        </div>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="inlineRadio2" value="2" name="docGender"  checked="">
                                            <label for="inlineRadio2"> Female </label>
                                        </div>
									  <?php } ?>
										</div>
								</div>	-->	
								<div class="form-group"><label class="col-sm-2 control-label">Country </label>

                                    <div class="col-sm-10"><select data-placeholder="Choose a Country..." class="chosen-select" name="txtCountry"  tabindex="2" onchange="return getState(this.value);">
											<option value="<?php echo $get_docInfo[0]['doc_country']; ?>" selected><?php echo $get_docInfo[0]['doc_country']; ?></option>
												<?php 
												$CntName = mysqlSelect("*", "countries", "", "country_name asc", "", "", "");
														
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">State </label>

                                    <div class="col-sm-10">
										<select data-placeholder="Choose a State..." class="form-control" name="slctState" id="slctState" tabindex="2">
											<option value="<?php echo $get_docInfo[0]['doc_state']; ?>" selected><?php echo $get_docInfo[0]['doc_state']; ?></option>
												<?php
												$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id='100'", "b.state_name asc", "", "", "");
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
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">City </label>

                                    <div class="col-sm-10"><input type="text" name="se_city" value="<?php echo $get_docInfo[0]['ref_address']; ?>"  class="form-control"></div>
                                </div>
								
								
								<div class="form-group"><label class="col-sm-2 control-label">Specialization <span class="required">*</span></label>

                                    <div class="col-sm-10">
									
										<select data-placeholder="Choose a Specialization..." class="chosen-select" name="slctSpec[]" multiple style="width:350px;" tabindex="4">
										<?php $DeptName= mysqlSelect("*","specialization","","spec_name asc","","","");
												
												foreach($DeptName as $DeptList){
													$chooseDept= mysqlSelect("*","doc_specialization","doc_id='".$admin_id."' and spec_id='".$DeptList['spec_id']."'","","","","");
												
													if($DeptList['spec_id']==$chooseDept[0]['spec_id']){ ?> 
												<option value="<?php echo stripslashes($DeptList['spec_id']);?>" selected /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($DeptList['spec_id']);?>" /><?php echo stripslashes($DeptList['spec_name']);?></option>
												<?php
														
												}?>
											</select>
										
										
										</div>
										
										
										
										
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Hospital <span class="required">*</span></label>

                                    <div class="col-sm-8">
									
										<select data-placeholder="Choose a Hospital..." class="chosen-select" name="selectHosp[]" multiple style="width:350px;" tabindex="4">
										<?php $HospName= mysqlSelect("*","hosp_tab","","hosp_name asc","","","");
												
												foreach($HospName as $HospList){
													$chooseHosp= mysqlSelect("*","doctor_hosp","doc_id='".$admin_id."' and hosp_id='".$HospList['hosp_id']."'","","","","");
												
													if($HospList['hosp_id']==$chooseHosp[0]['hosp_id']){ ?> 
												<option value="<?php echo stripslashes($HospList['hosp_id']);?>" selected /><?php echo stripslashes($HospList['hosp_name']);?></option>
												<?php 
													}?>

													<option value="<?php echo stripslashes($HospList['hosp_id']);?>" /><?php echo stripslashes($HospList['hosp_name']);?></option>
												<?php
														
												}?>
											</select>
										
										
									</div>
									<div class="col-sm-2">
										<a href="#" class="btn btn-default btn-circle" type="button" title="Click here to add new hospital" data-toggle="modal" data-target="#myModal1"><i class="fa fa-plus"></i>
										</a>
											
									</div>	
										
										
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Qualification</label>

                                    <div class="col-sm-10"><input type="text" id="txtQual" name="txtQual" value="<?php echo $get_docInfo[0]['doc_qual']; ?>" class="form-control"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Website</label>

                                    <div class="col-sm-10"><input type="text" id="txtWebsite" name="txtWebsite" value="<?php echo $get_docInfo[0]['ref_web']; ?>"  class="form-control"></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Area's of Interest, Expertise</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtInterest" name="txtInterest" rows="3"><?php echo $get_docInfo[0]['doc_interest']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Professional Contributions</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtContribute" name="txtContribute" rows="3"><?php echo $get_docInfo[0]['doc_contribute']; ?></textarea></div>
                                </div>
								<div class="form-group"><label class="col-sm-2 control-label">Research Details</label>

                                    <div class="col-sm-10"><textarea class="form-control" name="txtResearch" rows="3"><?php echo $get_docInfo[0]['doc_research']; ?></textarea></div>
                                </div>
								
								<div class="form-group"><label class="col-sm-2 control-label">Publications</label>

                                    <div class="col-sm-10"><textarea class="form-control" id="txtPublication" name="txtPublication" rows="3"><?php echo $get_docInfo[0]['doc_pub']; ?></textarea></div>
                                </div>
								
								<div class="form-group">
                                            <label class="control-label col-sm-2">Online Opinion Cost</label>

                                            <div class="col-lg-2">
                                                <input type="text" id="onopcost" name="onopcost" value="<?php echo $get_docInfo[0]['on_op_cost']; ?>" class="form-control" />
                                            </div>
											<label class="control-label col-sm-2">Consultation Charge</label>

                                            <div class="col-lg-2">
                                                <input type="text" id="conscharge" name="conscharge" value="<?php echo $get_docInfo[0]['cons_charge']; ?>" class="form-control" />
                                            </div>
											<label class="control-label col-sm-2">Currency</label>

                                            <div class="col-lg-2">
                                                <select data-placeholder="Choose a Currency..." class="chosen-select" name="slctCurrency" id="slctCurrency" tabindex="2">
												<option> Select Currency</option>
												<?php
												$GetCurrency = mysqlSelect("*", "currencies", "", "", "", "", "");
												foreach ($GetCurrency as $currencyList) {
												?>
												<option value="<?php echo $currencyList["id"];	?>" <?php if($get_docInfo[0]['currency_id']==$currencyList["id"]){ echo "selected"; }?>>
												<?php echo $currencyList["currency_code"]; ?>
												</option>												
												<?php
												}
												?>
										</select>
                                            </div>
											
                                        </div>
										<div class="form-group">
										<label class="control-label col-sm-2">Consultation Languages</label>

                                            <div class="col-lg-4">
                                                <select id="consult_lang" class="chosen-select fr" name="consult_lang[]" multiple style="background: #F5F5F5;">	
																		<option value="" >Select Languages</option>
																		
																		<?php 
																		$SrcName1= mysqlSelect("*","languages","","","","","");
																			$i=30;
																			foreach($SrcName1 as $srcList){ 
																			$get_lang = mysqlSelect("*","doctor_langauges ","doc_id='".$admin_id."'  and language_id='".$srcList['id']."'","","","",""); ?>
																				
																				<option value="<?php echo stripslashes($srcList['id']);?>" <?php if($get_lang[0]['language_id']==$srcList["id"]){ echo "selected"; }?>/>
																				<?php echo stripslashes($srcList['name']);?></option>
																			
																			
																			<?php 	$i++;
																			}?>   
																	</select>
                                            </div>
										</div>
										<div class="form-group"><label class="col-sm-2 control-label">Secretary Phone <span class="required">*</span></label>

											<div class="col-sm-4"><input type="text" class="form-control" id="txtSecPhone" name="txtSecPhone" value="<?php echo $get_docInfo[0]['secretary_phone']; ?>" placeholder="10 digit mobile no."></div>
											<label class="col-sm-2 control-label">Secretary Email</label>

											<div class="col-sm-4"><input id="txtSecEmail" name="txtSecEmail" value="<?php echo $get_docInfo[0]['secretary_email']; ?>" class="form-control"></div>
										
										</div>
										
										
										<div class="form-group">
                        <label class="control-label col-sm-2">Ready for tele opinion ?
                          
                        </label>

                        <div class="col-md-4 col-sm-4 col-xs-12">
                          
                            <label> <input type="checkbox" name="teleop" value="1" <?php if($get_docInfo[0]['tele_op']==1){ echo "checked"; } ?> class="i-checks" >  Yes </label>
                           
                        
                          
                        </div>
						<label class="control-label col-sm-2">Tele Opinion contact number</label>
                        <div class="col-sm-4 col-xs-12">
                          <input type="text" id="teleopnumber" name="teleopnumber" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" maxlength="10" placeholder="10 digit mobile no." class="form-control" >
                        </div>
                      </div>
					 <!-- <div class="form-group">
                        <label class="control-label col-sm-2">Tele Opinion contact number</label>
                        <div class="col-sm-4 col-xs-12">
                          <input type="text" id="teleopnumber" name="teleopnumber" value="<?php echo $get_docInfo[0]['tele_op_contact']; ?>" maxlength="10" class="form-control" >
                        </div>
                      </div>-->
					  <div class="form-group">
                        <label class="control-label col-sm-2">Ready for video opinion ?
                          
                        </label>

                        <div class="col-sm-4 col-xs-12">
                         
						   <label> <input type="checkbox" name="videoop" <?php if($get_docInfo[0]['video_op']==1){ echo "checked"; } ?> class="i-checks" value="1">  Yes</label>
                                                   
                        </div>
						
						 <label class="control-label col-lg-2">Video Opinion contact number</label>
                        <div class="col-sm-4 col-xs-12">
                          <input type="text" id="videoopnumber" name="videoopnumber" value="<?php echo $get_docInfo[0]['video_op_contact']; ?>" maxlength="10" class="form-control" >
                        </div>
                      </div>
					 
					  
					  <div class="form-group">
                        <label class="control-label col-lg-2">Available timings for Tele/Video Opinion</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                          <input type="text" id="televidop_time" name="televidop_time" value="<?php echo $get_docInfo[0]['tele_video_op_timing']; ?>" class="form-control" >
                        </div>
                      </div>
								
								
							
								<div class="form-group">
								<div class="col-sm-4 pull-right">
								<button type="submit" name="edit_doctor" id="edit_doctor" class="btn btn-primary block full-width m-b ">UPDATE</button>
								</div>
								</div>
							</form>
							
										<div class="modal inmodal" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog">
											<div class="modal-content animated bounceInRight">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
														<img src="../assets/img/user_noimg.png" width="100" class="img-thumbnail" />
														<h4 class="modal-title">Add New Hospital</h4>
														
													</div>
													<form method="post" enctype="multipart/form-data" class="form-horizontal form-label-left"  action="add_details.php"  name="frmAddDiagnostic">
													<input type="hidden" name="page_name" value="<?php echo $page_name; ?>" />
													<div class="modal-body">
														<div class="form-group"><label>Hospital Name</label> <input type="text" name="hosp_name" required value="" class="form-control" tabindex="1"></div>
														<div class="form-group"><label>Email</label> <input type="email" name="hosp_email" value="" class="form-control" tabindex="2"></div>
														<div class="form-group"><label>Mobile</label> <input type="text" name="hosp_contact" value="" class="form-control" tabindex="3"></div>
														<div class="form-group"><label>City</label> <input type="text" name="hosp_city" value="<?php echo $get_docInfo[0]['ref_address']; ?>"  class="form-control" tabindex="4"></div>
														<div class="form-group"><label>State</label> <select data-placeholder="Choose a State..." class="chosen-select" name="hosp_state" tabindex="2" tabindex="5">
														<option value="<?php echo $get_docInfo[0]['doc_state']; ?>" selected><?php echo $get_docInfo[0]['doc_state']; ?></option>
															<?php
															$GetState = mysqlSelect("*", "countries as a left join states as b on a.country_id=b.country_id", "b.country_id='100'", "b.state_name asc", "", "", "");
															foreach ($GetState as $StateList) {
															?>
															<option value="<?php echo $StateList["state_name"];	?>">
															<?php echo $StateList["state_name"]; ?>
															</option>												
															<?php
															}
															?>
													</select></div>
														<div class="form-group"><label>Address</label> <textarea class="form-control" required id="hospAddress" name="hospAddress" rows="3" tabindex="6"></textarea></div>
														
													
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
														
														<button type="submit" name="add_new_hospital" class="btn btn-primary">Add</button>
														
													</div>
													</form>
												</div>
												</div>
											</div>
                                </div>
                            </div>
							
							
                        </div>


                    </div>
                           

                        </div>
                    </div>

                </div>
            </div>
        </div>
         <?php include_once('footer.php'); ?>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="../assets/js/jquery-3.1.1.min.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
    <script src="../assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="../assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="../assets/js/inspinia.js"></script>
    <script src="../assets/js/plugins/pace/pace.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/plugins/peity/jquery.peity.min.js"></script>

    <!-- Peity -->
    <script src="../assets/js/demo/peity-demo.js"></script>
 <!-- Chosen -->
    <script src="../assets/js/plugins/chosen/chosen.jquery.js"></script>
	<script>
       
        $('.chosen-select').chosen({width: "100%"});



    </script>
	<!-- Switchery -->
   <script src="../assets/js/plugins/switchery/switchery.js"></script>
    <!-- FooTable -->
    <script src="../assets/js/plugins/footable/footable.all.min.js"></script>
<!-- Data picker -->
    <script src="../assets/js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function() {

            $('.footable').footable();

            $('#dateadded').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            $('#date_modified').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

        });

    </script>
	<!-- iCheck -->
    <script src="../assets/js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });
            });
        </script>
		<!-- Jasny -->
    <script src="../assets/js/plugins/jasny/jasny-bootstrap.min.js"></script>

</body>

</html>
